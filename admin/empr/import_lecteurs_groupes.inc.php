<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: import_lecteurs_groupes.inc.php,v 1.10 2010-12-01 16:28:26 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//La structure du fichier texte doit être la suivante : 
//Numéro identifiant/Nom/Prénom/Rue/Complément de rue/Code postal/Commune/Téléphone/Année de date de naissance/Classe/Sexe/Tel2/Mail/Profession/Message

function show_import_choix_fichier($dbh) {
	global $msg;
	global $current_module ;
	global $pmb_lecteurs_localises, $deflt2docs_location;

print "
<form class='form-$current_module' name='form1' ENCTYPE=\"multipart/form-data\" method='post' action=\"./admin.php?categ=empr&sub=implec&action=1\">
<h3>Choix du fichier</h3>
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_import_lec'>".$msg["import_lec_fichier"]."</label>
        <input name='import_lec' accept='text/plain' type='file' class='saisie-80em' size='40'>
        <label class='etiquette' for='form_import_lec'>". $msg["import_lec_separateur"]."</label>
        <select name='Sep_Champs' >
            <option value=';'>;</option>
            <option value='.'>.</option>
        </select>
	</div>
	<div class='row'>
		<p>L'ordre des colonnes dans votre fichier doit être :<br />
		<font size='1'>Code-barres ; Nom ; Prénom ; Rue ; Complément de rue ; Code postal ; Commune ; Téléphone ; Année de date de naissance ; Classe ; Sexe ; Téléphone 2 ; Mail ; Profession ; Message</font><br />
		Les trois dernières colonnes mail, profession, message sont facultatives.
		</p>
	</div>
	<div class='row'>
        
    </div>
    <br />
	<div class='row'>
        <input type=radio name='type_import' value='nouveau_lect' checked>
        <label class='etiquette' for='form_import_lec'>Nouveaux lecteurs</label>
        (ajoute ou modifie les lecteurs présents dans le fichier)
        <br />
        <input type=radio name='type_import' value='maj_complete'>
        <label class='etiquette' for='form_import_lec'>Mise à jour complète</label>
        (modifie les lecteurs présents, supprime les lecteurs absents du fichier)
    </div>
    <div class='row'>";
    
// Si les lecteurs sont localisés, affiche le sélecteur de localisation, sinon valeur par défaut de l'utilisateur
    if ($pmb_lecteurs_localises=="1") {
    	print "Localisation des lecteurs <select name='localisation'>";
		$requete_localisation="SELECT idlocation, location_libelle FROM docs_location ORDER BY location_libelle";
		$select_requete_localisation = mysql_query($requete_localisation,$dbh);
		while (($liste_localisation = mysql_fetch_array($select_requete_localisation))) {
			print "<option value='".$liste_localisation["idlocation"]."' >".$liste_localisation["location_libelle"]."</option>";
        }
		print "</select><br />";
    } else {
    	print "<input type='hidden' name='localisation' value='".$deflt2docs_location."' />";
    }
    
// Sélecteur de categ lecteur
print "Catégorie des lecteurs <select name='categorie'>";
$requete_categorie="SELECT id_categ_empr, libelle FROM empr_categ ORDER BY libelle";
$select_requete_categorie = mysql_query($requete_categorie,$dbh);
while (($liste_categorie = mysql_fetch_array($select_requete_categorie))) {
	print "<option value='".$liste_categorie["id_categ_empr"]."' >".$liste_categorie["libelle"]."</option>";
}
print "</select><br />";

// Sélecteur de code stat
print "Code statistique des lecteurs 
<select name='codestat'>";

$requete_codestat="SELECT idcode, libelle FROM empr_codestat ORDER BY libelle";
$select_requete_codestat = mysql_query($requete_codestat,$dbh);
while (($liste_codestat = mysql_fetch_array($select_requete_codestat))) {
	print "<option value='".$liste_codestat["idcode"]."' >".$liste_codestat["libelle"]."</option>";
}

print "
		</select>
	</div>
	<div class='row'>
		<input name='import_launch' value='Import des lecteurs' type='submit' class='bouton'/>
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

function import($separateur, $dbh, $type_import){
	global $categorie, $codestat, $localisation;
	global $pmb_lecteurs_localises;

    //La structure du fichier texte doit être la suivante : 
    //Code-barres ; Nom ; Prénom ; Rue ; Complément de rue ; Code postal ; Commune ; Téléphone ; Année de date de naissance ; Classe ; Sexe ; Téléphone 2 ; Mail ; Profession ; Message
	$requete = "SELECT duree_adhesion FROM empr_categ WHERE id_categ_empr='".$categorie."'";
	$resultat = mysql_query($requete,$dbh);
	if(mysql_num_rows($resultat))
		 $duree = mysql_result($resultat,0,0);
	else $duree=365;
    $eleve_abrege = array("Numéro identifiant","Nom","Prénom");
    $date_auj = date("Y-m-d", time());
    $date_an_proch = date("Y-m-d", time()+3600*24*$duree);
    
    //Upload du fichier
    if (!($_FILES['import_lec']['tmp_name']))
        print "Cliquez sur Pr&eacute;c&eacute;dent et choisissez un fichier";
    elseif (!(move_uploaded_file($_FILES['import_lec']['tmp_name'], "./temp/".basename($_FILES['import_lec']['tmp_name'])))) {
        print "Le fichier n'a pas pu être téléchargé. Voici plus d'informations :<br />";
        print_r($_FILES)."<p>";
    }
    $fichier = @fopen( "./temp/".basename($_FILES['import_lec']['tmp_name']), "r" );
       
    if ($fichier) {
        while (!feof($fichier)) {
			//initialise la variable tableau, au cas où on ait pas toutes les colonnes dans le fichier csv
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
			if ($tab[8]!="0") $password=$tab[8]; else $password="";
			//pour éviter un saut de ligne dans les trois dernières colonnes qui sont facultatives
			$tab[12]=str_replace("\\r\\n","", $tab[12]);
			$tab[13]=str_replace("\\r\\n","", $tab[13]);
			$tab[14]=str_replace("\\r\\n","", $tab[14]);
			 
            // Traitement du lecteur
            $select = mysql_query("SELECT id_empr FROM empr WHERE empr_cb = '".$tab[0]."'",$dbh);
            $nb_enreg = mysql_num_rows($select);
            
            //Test si un numéro id est fourni, rejet si pas d'id avec message si au moins nom ou au moins prénom contient qqch
            //si pas d'id, pas de nom, pas de prénom, erreur muette : dernière ligne
            if ((!$tab[0] || $tab[0] == "") && !($tab[1]=="" && $tab[2]=="" && $tab[3]==""&& $tab[4]=="")) {
            	print("<b> Lecteur non pris en compte car \"Numéro identifiant\" non renseigné : </b><br />");
                for ($i=0;$i<3;$i++) {
                    print($eleve_abrege[$i]." : ".$tab[$i].", ");
                }
                print("<br />");
                $nb_enreg = 2;
            }
            
            $login = cre_login($tab[1],$tab[2], $dbh);
                        
            switch ($nb_enreg) {
                case 0:
                	//Ce lecteur n'est pas enregistré 
                    $req_insert = "INSERT INTO empr(empr_cb, empr_nom, empr_prenom, empr_adr1, empr_adr2, empr_cp, empr_ville, ";
                    $req_insert .= "empr_tel1, empr_year, empr_categ, empr_codestat, empr_creation, empr_sexe,  ";
                    $req_insert .= "empr_login, empr_password, empr_date_adhesion, empr_date_expiration, empr_tel2, empr_mail, empr_prof, empr_msg, empr_location) ";
                    $req_insert .= "VALUES ('$tab[0]','$tab[1]','$tab[2]','$tab[3]', '$tab[4]', '$tab[5]', ";
                    $req_insert .= "'$tab[6]', '$tab[7]', '$tab[8]', $categorie, $codestat, '$date_auj', '$sexe', ";
                    $req_insert .= "'$login', '$password', '$date_auj', '$date_an_proch','$tab[11]','$tab[12]','$tab[13]','$tab[14]','$localisation')";
                    $insert = mysql_query($req_insert,$dbh);
                    if (!$insert) {
                        print("<b>Echec de la création du lecteur suivant (Erreur : ".mysql_error().") : </b><br />");
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
                	//Ce lecteur est déjà enregistré 
                    $req_update = "UPDATE empr SET empr_nom = '$tab[1]', empr_prenom = '$tab[2]', empr_adr1 = '$tab[3]', ";
                    $req_update .= "empr_adr2 = '$tab[4]', empr_cp = '$tab[5]', empr_ville = '$tab[6]', ";
                    $req_update .= "empr_tel1 = '$tab[7]', empr_year = '$tab[8]', empr_categ = '".$categorie."', empr_codestat = '$codestat', empr_modif = '$date_auj', empr_sexe = '$sexe', ";

                    // on ne modifie ni login ni mot de passe pour éviter d'écraser un mot de passe changé par le lecteur
                    // $req_update .= "empr_login = $login, empr_password= $tab[8], ";
                    $req_update .= "empr_date_adhesion = '$date_auj', empr_date_expiration = '$date_an_proch', empr_tel2 = '$tab[11]', empr_location='$localisation' ";
                    $req_update .= "WHERE empr_cb = '$tab[0]'";
                    $update = mysql_query($req_update, $dbh);
                    if (!$update) {
                        print("<b>Echec de la modification du lecteur suivant (Erreur : ".mysql_error().") : </b><br />");
                        for ($i=0;$i<3;$i++) {
                            print($eleve_abrege[$i]." : ".$tab[$i].", ");
                        }
                        print("<br />");
                    }
                    else {
                    	if ($tab[12]!="") {
	                    	$req_update_mail = "UPDATE empr SET empr_mail='$tab[12]' WHERE empr_cb = '$tab[0]'";
	                    	$update_mail = mysql_query($req_update_mail, $dbh);
	                    	if (!$update_mail) {
	                    		print("<b>Echec de la modification du mail du lecteur suivant (Erreur : ".mysql_error().") : </b><br />");
	                        	for ($i=0;$i<3;$i++) print($eleve_abrege[$i]." : ".$tab[$i].", ");
	                        	print("<br />");
	                        	
							}
							$tab[12]="";
                    	}
                    	if ($tab[13]!="") { 
	                    	$req_update_prof = "UPDATE empr SET empr_prof='$tab[13]' WHERE empr_cb = '$tab[0]'";
	                    	$update_prof = mysql_query($req_update_prof, $dbh);
	                    	if (!$update_prof) { 
	                    		print("<b>Echec de la modification de la profession lecteur suivant (Erreur : ".mysql_error().") : </b><br />");
	                        	for ($i=0;$i<3;$i++) print($eleve_abrege[$i]." : ".$tab[$i].", ");
	                        	print("<br />");                    		 
							}
							$tab[13]="";
                    	}
                    	if ($tab[14]!="") {
	                    	$req_update_msg = "UPDATE empr SET empr_msg='$tab[14]' WHERE empr_cb = '$tab[0]'";
	                    	$update_msg = mysql_query($req_update_msg, $dbh);
	                    	if (!$update_msg) {                     		
	                    		print("<b>Echec de la modification du message sur le lecteur suivant (Erreur : ".mysql_error().") : </b><br />");
	                        	for ($i=0;$i<3;$i++) print($eleve_abrege[$i]." : ".$tab[$i].", ");
	                        	print("<br />");                    		 
							}
							$tab[14]="";
                    	}
                    	
                        $cpt_maj ++;
                    }
                    gestion_groupe($tab[9], $tab[0], $dbh);
                    $j++;
                    break;
                case 2:
                    break;
                default:
                    print("<b>Echec pour le lecteur suivant (Erreur : ".mysql_error().") : </b><br />");
                    for ($i=0;$i<3;$i++) {
                        print($eleve_abrege[$i]." : ".$tab[$i].", ");
                    }
                    print("<br />");
                    break;
            }
        }

		           
        if ($type_import == 'maj_complete') {
        	$requete_empr_groupe_delete = "DELETE FROM empr_groupe LEFT JOIN empr ON empr_id=id_empr LEFT JOIN pret ON pret_idempr=id_empr WHERE pret_idempr IS NULL and empr_modif != '$date_auj' and empr_categ=$categorie and empr_codestat= $codestat";
			if ($pmb_lecteurs_localises=="1") {
				$requete_empr_where .= " and empr_location=$localisation";
			}
			mysql_query($requete_empr_groupe_delete.$requete_empr_where,$dbh);
			
        	$requete_list_empr_delete = "SELECT id_empr FROM empr LEFT JOIN pret ON pret_idempr=id_empr 
        		WHERE pret_idempr IS NULL and empr_modif != '$date_auj' and empr_categ=$categorie and empr_codestat= $codestat $requete_empr_where ";
        	$list_empr_delete=mysql_query($requete_list_empr_delete,$dbh);
        	while (($empr_delete = mysql_fetch_array($list_empr_delete))) {
            	emprunteur::del_empr($empr_delete["id_empr"]);
            }
        }
		
        //Affichage des insert et update
        print("<br />");
        if ($cpt_delete) print($cpt_delete." lecteurs supprimés. <br />");
        if ($cpt_insert) print($cpt_insert." lecteurs créés. <br />");
        if ($cpt_maj) print($cpt_maj." lecteurs modifiés. <br />");
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
        if ($import_launch){
            import($Sep_Champs, $dbh, $type_import);
        }
        else {
            show_import_choix_fichier($dbh);
        }
        break;
    case 2:
        break;
    default:
        show_import_choix_fichier($dbh);
        break;
}

?>



