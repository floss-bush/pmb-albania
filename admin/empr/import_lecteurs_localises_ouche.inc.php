<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: import_lecteurs_localises_ouche.inc.php,v 1.7 2009-05-16 11:12:01 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//ce script issu d'import belgique a été modifié par Ouche CCVO par Patrice Desfray le 2/4/2007 (p.desfray@orange.fr)
//son fonctionnement est expliqué sur le Wiki PMB dans Import "Belgique" : import des élève ....
//notre contexte d'utilisation: 7 bibliothèques municipales en réseau avec gestion publique et gestion accueil scolaire différenciée
//modification apportées / import Belgique
//suppression import profs
//liste choix pour la localisation de l'école codé en dur mais peut-être remplacé par une requête sur docs_location
//un fichier csv sur le modèle import belgique 11 colonnes avec code barre
//codage du code: année école incrément ex: 2006EV00001 : rentrée 2006 école de velars incrément sur 5 digits
//2 var globales crées: $code_categorie et $code_statistique pour le codage en dur des catégories
//$code_categorie=12 : collectivité - élèves (si il y a des profs dans la liste, il faudra les passer après l'import en 8 : collectivité - école droits de prêt différents
//$code_statistique=3 commune de la bibliothèque
//en option 2 : efface les lecteurs sans prêts, sur la localisation choisie qui sont dans la catégorie : collectivité - élèves (12)  les profs en (8) ne seront donc pas effacés
// merci à A.M. Cubat et à aux concepteur de import_Bretagne
//Patrice Desfray

//La structure du fichier texte doit être la suivante : 11 champs (pour tout le monde)
//Numéro identifiant/Nom/Prénom/Rue/Complément de rue/Code postal/Commune/Téléphone/Date de naissance/Classe/Sexe

//Supprimez la première ligne du fichier excel si elle contient la liste des champs

require_once("$class_path/emprunteur.class.php");

echo $location;
echo $user;

function show_import_choix_fichier($dbh) {
	global $msg;
	global $current_module ;

print "
<form class='form-$current_module' name='form1' ENCTYPE=\"multipart/form-data\" method='post' action=\"./admin.php?categ=empr&sub=implec&action=1\">
<h3>Choix du fichier</h3>
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_import_lec'>".$msg["import_lec_fichier"]."</label>
        <input name='import_lec' accept='text/plain' type='file' class='saisie-80em' size='50'>
		   Fichier csv sur 11 colonnes

	</div>	
	<br />
	<div class='row'>
        <label class='etiquette' for='form_import_lec'>". $msg["empr_location"]."</label>
        <select name='cnl_bibli'>";

/* ajout GM : liste des localisations */
$requete_localisation="SELECT idlocation, location_libelle FROM docs_location ORDER BY location_libelle";
$select_requete_localisation = mysql_query($requete_localisation,$dbh);
while (($liste_localisation = mysql_fetch_array($select_requete_localisation))) {
	print "<option value='".$liste_localisation["idlocation"]."' >".$liste_localisation["location_libelle"]."</option>";
}

print "		</select>
	<br />
	 </div>
	   <form>
	<br />
	</form>
		<div class='row'>
	        <input type=radio name='type_import' value='nouveau_lect' checked>
	        <label class='etiquette' for='form_import_lec'>Nouveaux lecteurs</label>
	        (ajoute ou modifie les lecteurs présents dans le fichier)
	        <br />
	        <input type=radio name='type_import' value='maj_complete'>
	        <label class='etiquette' for='form_import_lec'>Mise à jour complète</label>
	        (supprime les lecteurs non présents de cette localisation qui n'ont pas de prêt en cours)
	    </div>
	    <div class='row'></div>
	    
		</div>
	<div class='row'>
		<input name='imp_elv' type='submit' class='bouton' value='Import des élèves'/>
	</div>
	</form>";
}



function cre_login($nom, $prenom, $dbh) {
    $empr_login = substr($prenom,0,1).$nom ;
    $empr_login = strtolower($empr_login);
    $empr_login = clean_string($empr_login) ;
    $empr_login = convert_diacrit(strtolower($empr_login)) ;
    $empr_login = preg_replace('/[^a-z0-9\.]/', '', $empr_login);
    $pb = 1 ;
    $num_login=1 ;
    while ($pb==1) {
        $requete = "SELECT empr_login FROM empr WHERE empr_login='$empr_login' AND empr_nom <> '$nom' AND empr_prenom <> '$prenom' LIMIT 1 ";
        $res = mysql_query($requete, $dbh);
        $nbr_lignes = mysql_num_rows($res);
        if ($nbr_lignes) {
            $empr_login .= $num_login ;
            $num_login++;
        } 
        else $pb = 0 ;
    }
    return $empr_login;
}

function import_eleves($separateur, $dbh, $type_import,$commune){
	
	global $code_categorie;
	global $code_statistique;
	$code_categorie=12;
	$code_statistique=3;
    
    $eleve_abrege = array("Numéro identifiant","Nom","Prénom");
    $date_auj = date("Y-m-d", time());
    $date_an_proch = date("Y-m-d", time()+3600*24*30.42*12);
    
    //Upload du fichier
    if (!($_FILES['import_lec']['tmp_name']))
        print "Cliquez sur Pr&eacute;c&eacute;dent et choisissez un fichier";
    elseif (!(move_uploaded_file($_FILES['import_lec']['tmp_name'], "./temp/" .basename($_FILES['import_lec']['tmp_name'])))) {
        print "Le fichier n'a pas pu être téléchargé. Voici plus d'informations :<br />";
        print_r($_FILES)."<p>";
    }
    $fichier = @fopen( "./temp/".basename($_FILES['import_lec']['tmp_name']), "r" );
       
    if ($fichier) {
        if ($type_import == 'maj_complete') {
            //Vide la table empr_groupe des élèves qui n'ont pas de prêts en cours et qui sont localisé à la commune sélectionnée et de categorie collectivité eleves
			$req_select_verif_pret = "SELECT id_empr FROM empr left join pret on id_empr=pret_idempr WHERE pret_idempr is null and empr_location= '$commune' and empr_categ = '$code_categorie' ";
            $select_verif_pret = mysql_query($req_select_verif_pret,$dbh);
            while (($verif_pret = mysql_fetch_array($select_verif_pret))) {
            	//pour tous les emprunteurs qui n'ont pas de pret en cours
                $req_delete = "DELETE FROM empr_groupe WHERE empr_id = '".$verif_pret["id_empr"]."'";
                mysql_query($req_delete);
            }
            //$delete_empr_groupe = mysql_query("DELETE FROM empr_groupe",$dbh);
            //Supprime les élèves qui n'ont pas de prêts en cours et qui sont localisé à la commune sélectionnée et de categorie collectivité eleves
            $req_select_verif_pret = "SELECT id_empr FROM empr left join pret on id_empr=pret_idempr WHERE pret_idempr is null and empr_location= '$commune' and empr_categ = '$code_categorie' ";
            $select_verif_pret = mysql_query($req_select_verif_pret,$dbh);
            while (($verif_pret = mysql_fetch_array($select_verif_pret))) {
            	//pour tous les emprunteurs qui n'ont pas de pret en cours
                emprunteur::del_empr($verif_pret["id_empr"]);
            }
        }
        
        while (!feof($fichier)) {
            $buffer = fgets($fichier, 4096);
            $buffer = mysql_escape_string($buffer);
            $tab = explode($separateur, $buffer);

            //Gestion du sexe
            switch ($tab[10]{0}) {
                case M: 
                    $sexe = 1;
                    break;
                case F:
                    $sexe = 2; 
                    break;
                default:
                    $sexe = 0;
                    break;
            }

            // Traitement de l'élève
            $select = mysql_query("SELECT id_empr FROM empr WHERE empr_cb = '".$tab[0]."'",$dbh);
            $nb_enreg = mysql_num_rows($select);
            
            //Test si un numéro id est fourni
            if (!$tab[0] || $tab[0] == "") {
                print("<b> Elève non pris en compte car \"Numéro identifiant\" non renseigné : </b><br />");
                for ($i=0;$i<3;$i++) {
                    print($eleve_abrege[$i]." : ".$tab[$i].", ");
                }
                print("<br />");
                $nb_enreg = 2;
            }
            
            $login = cre_login($tab[1],$tab[2], $dbh);
			
            switch ($nb_enreg) {
                case 0:
                	//Ce élève n'est pas enregistré 
                    $req_insert = "INSERT INTO empr(empr_cb, empr_nom, empr_prenom, empr_adr1, empr_adr2, empr_cp, empr_ville, ";
                    $req_insert .= "empr_tel1, empr_year, empr_categ, empr_codestat, empr_creation, empr_sexe,  ";
                    $req_insert .= "empr_login, empr_password, empr_date_adhesion, empr_date_expiration, empr_location) ";
                    $req_insert .= "VALUES ('$tab[0]','$tab[1]','$tab[2]','$tab[3]', '$tab[4]', '$tab[5]', ";
	            //Vérifier dans la table empr_categ si id_categ_empr 1 = élèves
		    //Vérifier dans la table empr_codestat si idcode 2 = école    Sinon, changer les valeurs
                    $req_insert .= "'$tab[6]', '$tab[7]', '$tab[8]', $code_categorie , '3', '$date_auj', '$sexe', ";
                    $req_insert .= "'$login', '$tab[8]', '$date_auj', '$date_an_proch' , '$commune' )";
                    $insert = mysql_query($req_insert,$dbh);
                    if (!$insert) {
                        print("<b>Echec de la création de l'élève suivant (Erreur : ".mysql_error().") : </b><br />");
print($code_categorie); 
print("3"); 
print( "$location"); 
print( "$user"); 
                        for ($i=0;$i<3;$i++) {
                            print($eleve_abrege[$i]." : ".$tab[$i].", ");
                        }
                        print("<br />");
                    }
                    else {
                        $cpt_insert ++;
                    }
                    gestion_groupe($tab[9], $tab[0], $dbh);
                    $j++;
                    break;

                case 1:
                	//Ce élève est déjà enregistré 
                    $req_update = "UPDATE empr SET empr_nom = '$tab[1]', empr_prenom = '$tab[2]', empr_adr1 = '$tab[3]', ";
                    $req_update .= "empr_adr2 = '$tab[4]', empr_cp = '$tab[5]', empr_ville = '$tab[6]', ";
	//Vérifier dans la table empr_categ si id_categ_empr 1 = élèves    Vérifier dans la table empr_codestat si idcode 2 = école    Sinon, changer les valeurs
                    $req_update .= "empr_tel1 = '$tab[7]', empr_year = '$tab[8]', empr_categ = '$code_categorie ', empr_codestat = '3', empr_modif = '$date_auj', empr_sexe = '$sexe', ";
                    $req_update .= "empr_login = '$login', empr_password= '$tab[8]', ";
                    $req_update .= "empr_date_adhesion = '$date_auj', empr_date_expiration = '$date_an_proch', empr_location = '$commune'";
                    $req_update .= "WHERE empr_cb = '$tab[0]'";
                    $update = mysql_query($req_update, $dbh);
                    if (!$update) {
                        print("<b>Echec de la modification de l'élève suivant (Erreur : ".mysql_error().") : </b><br />");
						print( $path);
print('$code_categorie'); 
print("3"); 
print( "$location"); 
print( "$user"); 
                        for ($i=0;$i<3;$i++) {
                            print($eleve_abrege[$i]." : ".$tab[$i].", ");
                        }
                        print("<br />");
                    }
                    else {
                        $cpt_maj ++;
                    }
                    gestion_groupe($tab[9], $tab[0], $dbh);
                    $j++;
                    break;
                case 2:
                    break;
                default:
				print( $path);
print($code_categorie); 
print(3); 
print( $location); 
echo $user; 
                    print("<b>Echec pour l'élève suivant (Erreur : ".mysql_error().") : </b><br />");
                    for ($i=0;$i<3;$i++) {
                        print($eleve_abrege[$i]." : ".$tab[$i].", ");
                    }
                    print("<br />");
                    break;
            }
        }

        //Affichage des insert et update
        print("<br />_____________________<br />");
        if ($cpt_insert) print($cpt_insert." Elèves créés. <br />");
        if ($cpt_maj) print($cpt_maj." Elèves modifiés. <br />");
        fclose($fichier);
    }
    
}

function gestion_groupe($lib_groupe, $empr_cb, $dbh) {
    
    $sel = mysql_query("SELECT id_groupe from groupe WHERE libelle_groupe = \"".$lib_groupe."\"",$dbh);
    $nb_enreg_grpe = mysql_num_rows($sel);
    
    if (!$nb_enreg_grpe) {
		//insertion dans la table groupe
		mysql_query("INSERT INTO groupe(libelle_groupe) VALUES(\"".$lib_groupe."\")");
		$groupe=mysql_insert_id();
    } else {
   		$grpobj = mysql_fetch_object ($sel) ;
   		$groupe = $grpobj->id_groupe ; 
    }

	//insertion dans la table empr_groupe
    $sel_empr = mysql_query("SELECT id_empr FROM empr WHERE empr_cb = \"".$empr_cb."\"",$dbh);
    $empr = mysql_fetch_array($sel_empr);
    @mysql_query("INSERT INTO empr_groupe(empr_id, groupe_id) VALUES ('$empr[id_empr]','$groupe')");
}


switch($action) {
    case 1:
        import_eleves(";", $dbh, $type_import,$cnl_bibli);
        break;
    case 2:
        break;
    default:
        show_import_choix_fichier($dbh);
        break;
}

?>



