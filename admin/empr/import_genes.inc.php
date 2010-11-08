<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: import_genes.inc.php,v 1.10 2009-10-06 04:09:26 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/parametres_perso.class.php");
require_once($class_path."/docs_location.class.php");

// on récupère les champs personnalisés emprunteurs
$idchamp=array();
$rqt="select idchamp, name from empr_custom";
$r = mysql_query($rqt) or die(mysql_error()."<br /><br />$rqt<br /><br />");
if (mysql_num_rows($r)) {
	while (($idchamp_obj=mysql_fetch_object($r))) {
		$idchamp[$idchamp_obj->name] = $idchamp_obj->idchamp;
	}
} 
if ($idchamp['id_etudiant']=="") die ("Champ personnalisé id_etudiant inexistant");
if ($idchamp['numero_casier']=="") die ("Champ personnalisé numero_casier inexistant");
// AUTHENITICATION PORTAIL
if ($idchamp['portail_only']=="") die ("Champ personnalisé portail_only inexistant");


switch($action) {
    case 1:
		// on récupère la localisation
		$rqt="select location_libelle from docs_location where idlocation='$empr_location_id'";
		$r = mysql_query($rqt) or die(mysql_error()."<br /><br />$rqt<br /><br />");
		if (mysql_num_rows($r)) {
			while (($idchamp_obj=mysql_fetch_object($r))) {
				$empr_location_lib = $idchamp_obj->location_libelle;
			}
		}
		
    	if ($imp_elv){
    		if($file_format=='ensae')	import_eleves_ensae($Sep_Champs, $dbh, $type_import);
            else import_eleves_ensai($Sep_Champs, $dbh, $type_import);
        } else {
            show_import_choix_fichier($dbh);
        }
        break;
    case 2:
        break;
    default:
        show_import_choix_fichier($dbh);
        break;
}

// <input type=radio name='type_import' id='type_import' value='maj_complete' checked=checked />
// <input type=radio name='type_import' id='type_import_1' value='insert_or_update'/>

function show_import_choix_fichier($dbh) {
	global $msg, $deflt2docs_location;
	global $current_module, $PMBuserid ;

	print "
	<script type='text/javascript'>

function display_part(type)
{
	var type_import = document.getElementById('div_ensae');
	if(type == 'ensae')
	{
		var div_ensae = document.getElementById('div_ensae');
		div_ensae.style.display = 'table-cell';
		var div_ensai = document.getElementById('div_ensai');
		div_ensai.style.display = 'none';	
		type_import.value='maj_complete';
		
	} else {
		var div_ensae = document.getElementById('div_ensae');
		div_ensae.style.display = 'none';
		var div_ensai = document.getElementById('div_ensai');
		div_ensai.style.display = 'table-cell';
		type_import.value='insert_or_update';
	}
	
} 
</script>
	
	<form class='form-$current_module' name='form1' ENCTYPE=\"multipart/form-data\" method='post' action=\"./admin.php?categ=empr&sub=implec&action=1\">
	<h3>Choix du fichier d'import des élèves GENES</h3>
	<div class='form-contenu'>
		<div class='row'>
			<label class='etiquette' for='import_lec'>".$msg["import_lec_fichier"]."</label>
	        <input name='import_lec' id='import_lec' accept='text/plain' type='file' class='saisie-80em' size='80'>
			</div>	
		<div class='row'>
	        <label class='etiquette' for='Sep_Champs'>". $msg["import_lec_separateur"]."</label>
	        <select name='Sep_Champs' id='Sep_Champs'>
	            <option value=';' selected>;</option>
	            <option value='.'>.</option>
	        </select>
	    </div>
	    <div class='row'>
	        <label class='etiquette' for='file_format'>Format du fichier</label>
	        <select name='file_format' id='file_format' onchange='display_part(this.value);'>
	            <option value='ensae' selected>ENSAE</option>
	            <option value='ensai'>ENSAI</option>
	        </select>
	    </div>
	    <br />
	    <div style='display:table'>
	    	<div style='display:table-row'>
		    	<div id=div_ensae style='display:table-cell;width:50%'>
					<div class='row'>
				        <input type=hidden name='type_import' id='type_import' value='maj_complete' />
				        <label class='etiquette' for='type_import'>Mise à jour complète</label>
				        Marquer les lecteurs appartenant aux groupes ci-dessous et ayant pour catégorie et localisation les choix ci-dessous
				        <blockquote>"."
				        Et les placer dans le panier suivant: ";
						$requete = "SELECT idemprcaddie, name FROM empr_caddie where (autorisations='$PMBuserid' or autorisations like '$PMBuserid %' or autorisations like '% $PMBuserid %' or autorisations like '% $PMBuserid') order by name ";
						print gen_liste ($requete, "idemprcaddie", "name", "idemprcaddie", "", "", "", "","","",0);
				    	print "</blockquote>
				    </div>
				    <div class='row'>
					    <blockquote>
					    	<div class='colonne3'>";
								$requete = "SELECT id_groupe, libelle_groupe FROM groupe left join empr_groupe on id_groupe=groupe_id  group by id_groupe, libelle_groupe ORDER BY libelle_groupe ";
								$groupe_form_aff = gen_liste_multiple ($requete, "id_groupe", "libelle_groupe", "id_groupe", "id_grp[]", "", $id, 0, $msg[empr_form_aucungroupe], 0,"", 60) ;
								print  "<div class='row'>
									<label for='form_ajoutgroupe' class='etiquette'>Groupes :</label>
								</div>
								<div class='row'>".$groupe_form_aff."</div>
							</div>
							<div class='colonne3'>";
								$requete = "SELECT id_categ_empr, libelle FROM empr_categ ORDER BY libelle ";
								$categ_form_aff = gen_liste ($requete, "id_categ_empr", "libelle", "id_categ_empr", "", 36, "", "","","",0);
								print  "<div class='row'>
									<label for='form_categ_empr' class='etiquette'>Catégorie de lecteurs :</label>
								</div>
								<div class='row'>".$categ_form_aff."</div>
							</div>
							<div class='colonne_suite'>";
								print "<div class='row'>
											<div class='row'>
												<label for='form_empr_location' class='etiquette'>$msg[empr_location]:</label>
											</div>
											<div class='row'>
												".docs_location::gen_combo_box_empr($deflt2docs_location, 0)."
											</div>
									</div>
							</div>
						</blockquote>
					</div>
				</div>
				<div id=div_ensai style='display:table-cell;width:50%;display:none'>
					<div class='row'>
				        
				        <label class='etiquette' for='type_import_1'>Insertion uniquement</label>
				    </div>
				</div>
			</div>
		</div>
		<div class='row'>&nbsp;</div>
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
    $empr_login = preg_replace('/[^a-z0-9\.\ ]/', '', $empr_login);
    $empr_login = str_replace(" ","-",$empr_login);
    $pb = 1 ;
    $num_login=1 ;
    $debut_log = $empr_login;
    while ($pb==1) {
        $requete = "SELECT empr_login FROM empr WHERE empr_login like '$empr_login' AND (empr_nom <> '$nom' OR empr_prenom <> '$prenom') LIMIT 1 ";
        $res = mysql_query($requete, $dbh);
        $nbr_lignes = mysql_num_rows($res);
        if ($nbr_lignes) {
            $empr_login = $debut_log.$num_login ;
            $num_login++;
        } 
        else $pb = 0 ;
    }
    return $empr_login;
}

function import_eleves_ensae($separateur, $dbh, $type_import){
	global $idchamp, $id_grp, $empr_location_id, $empr_location_lib, $id_categ_empr, $idemprcaddie ;
	
	if (!isset($id_grp)) $id_grp=array();
    //La structure du fichier texte doit être la suivante : 
    //id_etudiant/CB/Voie/Nom/Prénom/courriel/courriel_perso/casier/libelle_etat_civil/année de naissance/tel dom/tel_mobile/identifiant OPAC/
    
    $eleve_abrege = array("Numéro identifiant","Nom","Prénom");
    $date_auj = date("Y-m-d", time());
    $date_an_proch = date("Y-m-d", time()+3600*24*30.42*12);
    
    //Upload du fichier
    if (!($_FILES['import_lec']['tmp_name'])) print "Cliquez sur Pr&eacute;c&eacute;dent et choisissez un fichier";
    elseif (!(move_uploaded_file($_FILES['import_lec']['tmp_name'], "./temp/" .basename($_FILES['import_lec']['tmp_name'])))) {
        print "Le fichier n'a pas pu être téléchargé. Voici plus d'informations :<br />";
        print_r($_FILES)."<p>";
    }
    $fichier = @fopen( "./temp/".basename($_FILES['import_lec']['tmp_name']), "r" );
       
    if ($fichier) {
		
        if ($type_import == 'maj_complete' && implode(',',$id_grp)!="") {
            // les lecteurs de la localisation et appartenant aux groupes $id_grp vont être marqués :
            //          empr_prof = !!A SUPPRIMER!!
			$rqt = "select distinct empr_id from empr_groupe join empr on empr_id=id_empr where empr_location=$empr_location_id and empr_categ=$id_categ_empr and groupe_id in (".implode(',',$id_grp).")";
        	$r = mysql_query($rqt) or die(mysql_error()."<br /><br />$rqt<br /><br />");
			while ($empr=mysql_fetch_object($r)) {
				mysql_query("update empr set empr_prof='!!A SUPPRIMER!!' where id_empr=".$empr->empr_id) or die("update empr set empr_prof='!!A SUPPRIMER!!' where id_empr=".$empr->empr_id);
			} 

        }
        
        $totallignes=0;
        while (!feof($fichier)) {
        	$buffer = fgets($fichier, 4096);
            $tab = explode($separateur, $buffer);
			//La structure du fichier texte doit être la suivante : 
		   	//id_etudiant/CB/Voie/Nom/Prénom/courriel/courriel_perso/casier/libelle_etat_civil/année de naissance/tel dom/tel_mobile
            $idetudiant=trim(str_replace(" ","",$tab[0]));
			if ($idetudiant!="id_etudiant" && $idetudiant!="") {
				// ce n'est pas la première ligne d'entête de colonne
				$voie=trim($tab[2]);
				$nom=trim($tab[3]);
				$prenom=trim($tab[4]);
				$cb = trim(str_replace(" ","",$tab[1]));
				$email=array();
				if (trim($tab[5])) $email[]=trim($tab[5]);
				if (trim($tab[6])) $email[]=trim($tab[6]);
				$emails=implode(';',$email);
				$casier=trim($tab[7]);
				$anneenaiss=trim($tab[9]);
				$tel1=trim($tab[10]);
				$tel2=trim($tab[11]);
				$loginopacfic=trim($tab[12]);
				
	            //Gestion du sexe
            	switch ($tab[8]) {
	                case "Monsieur": 
	                    $sexe = 1;
	                    break;
					case "Madame": 
					case "Mademoiselle": 
						$sexe = 2; 
	                    break;
	                default:
	                    $sexe = 0;
	                    break;
            	}

            	// recherche du groupe
            	$id_groupe = quel_groupe($empr_location_lib." - ".$voie);
            	
            	// Traitement de l'élève
				$rqt="select empr_custom_origine as id_empr from empr_custom_values where empr_custom_champ=".$idchamp['id_etudiant']." and empr_custom_small_text='".addslashes($idetudiant)."' ";
            	$nb = mysql_query($rqt,$dbh);
            	$nb_enreg=mysql_num_rows($nb);
	            switch ($nb_enreg) {
	                case 0:
	                	//Cet élève n'est pas enregistré
	                	if (!$loginopacfic) $login = cre_login($nom, $prenom, $dbh);
	                	else $login = $loginopacfic;
	                    $req_insert = "insert into empr SET empr_nom='".addslashes($nom)."', empr_prenom='".addslashes($prenom)."', empr_cb='".addslashes($cb)."', ";
	                    $req_insert .= "empr_tel1='".addslashes($tel1)."', empr_tel2='".addslashes($tel2)."', empr_year='".addslashes($anneenaiss)."', empr_categ =$id_categ_empr, empr_codestat=8, empr_sexe='$sexe', ";
	                    $req_insert .= "empr_login='".$login."', empr_password='".addslashes($anneenaiss)."', empr_mail='".addslashes($emails)."', ";
	                    $req_insert .= "empr_prof='', empr_lang='fr_FR', empr_statut=1, ";
	                    $req_insert .= "empr_location='$empr_location_id', ";
	                    $req_insert .= "empr_modif='$date_auj', empr_date_adhesion = '$date_auj', empr_date_expiration = '$date_an_proch' ";
						$insert = mysql_query($req_insert,$dbh) or die("<br />".mysql_error()."<br />".$req_insert);
	                    if (!$insert) {
	                        print("<b>Echec de la création de l'élève suivant (Erreur : ".mysql_error().") : </b><br />");
	                        print("<br />");
	                    } else {
		                    $id_cree = mysql_insert_id();
		                    $resu = gestion_groupe($id_groupe, $id_cree, $dbh);
		                    gestion_champ_portail($id_cree, $dbh);
		                    gestion_champ_numero_casier($id_cree, $casier, $dbh);
		                    gestion_champ_id_etudiant($id_cree, $idetudiant, $dbh);
	                    	$cpt_insert ++;
	                    }
	                    $j++;                    
	                    break;
	
	                case 1:
	                	//Cet élève est déja enregistré
	                	$empr=mysql_fetch_object($nb) ;
	                    $req_update = "UPDATE empr SET empr_nom='".addslashes($nom)."', empr_prenom='".addslashes($prenom)."', empr_cb='".addslashes($cb)."', ";
	                    $req_update .= "empr_tel1='".addslashes($tel1)."', empr_tel2='".addslashes($tel2)."', empr_year = '".addslashes($anneenaiss)."', empr_categ =$id_categ_empr, empr_codestat=8, empr_modif='$date_auj', empr_sexe='$sexe', ";
	                    $req_update .= "empr_mail='".addslashes($emails)."', ";
	                    $req_update .= "empr_prof='', empr_lang='fr_FR', empr_statut=1, ";
	                    $req_update .= "empr_date_expiration = '$date_an_proch' ";
	                    $req_update .= "WHERE id_empr = '".$empr->id_empr."'";
	                    $update = mysql_query($req_update, $dbh) or die("<br />".mysql_error()."<br />".$req_update);
	                    if (!$update) {
	                        print("<b>Echec de la modification de l'élève suivant (Erreur : ".mysql_error().") : </b><br />");
	                        print("<br />");
	                    } else {
	                    	$resu = gestion_groupe($id_groupe, $empr->id_empr, $dbh);
		                    gestion_champ_portail($empr->id_empr, $dbh);
		                    gestion_champ_numero_casier($empr->id_empr, $casier, $dbh);
	                    	$cpt_maj ++;
	                    }

	                    $j++;
	                    break;
	                default:
	                    print("<b>Echec pour l'élève suivant (Erreur : ".mysql_error().") : </b><br />");
	                    print "<ul><li><font color=red><b>Absent de la base PMB $voie</b></font></li>
								<li><font color=red>$nom</font></li>
								<li><font color=red>$prenom</font></li>
								<li><font color=red>$cb</font></li>
								<li><font color=red>$idetudiant</font></li>
								</ul>";
	                    print("<br />");
						break;
				}				
				$totallignes++;
            } // fin if pas première ligne ni vide
        } // while
        
        // post traitement
        if ($type_import == 'maj_complete' && implode(',',$id_grp)!="") {
			$req_select_verif_pret = "SELECT id_empr FROM empr WHERE empr_prof='!!A SUPPRIMER!!' ";
            $select_verif_pret = mysql_query($req_select_verif_pret,$dbh);
            while ($verif_pret = mysql_fetch_array($select_verif_pret)) {
            	//pour tous les emprunteurs qui ne figurent pas dans le fichier
				$rqt_del = "delete from empr_groupe where empr_id=".$verif_pret["id_empr"];
				$resultat_del=mysql_query($rqt_del);
				@mysql_query ("insert into empr_caddie_content set empr_caddie_id=$idemprcaddie, object_id=".$verif_pret["id_empr"]) ;
                $cpt_suppr++;
            }
        	// On supprime les groupes qui ne sont plus utilisés parmi ceux qui étaient sélectionnés bien entendu
        	mysql_query("create temporary table tmpidgroupe as SELECT distinct id_groupe FROM groupe left join empr_groupe on groupe_id=id_groupe WHERE empr_id is null",$dbh) or die(mysql_error()."<br />");
        	$req_del_groupe = "delete from groupe where id_groupe in (select id_groupe from tmpidgroupe) and id_groupe in (".implode(',',$id_grp).")";
            mysql_query($req_del_groupe,$dbh) or die(mysql_error()."<br />".$req_del_groupe);
        }
        //Affichage des insert et update
        if ($cpt_insert) print($cpt_insert." élèves créés. <br />");
        if ($cpt_maj) print($cpt_maj." élèves modifiés. <br />");
        if ($cpt_suppr) print($cpt_suppr." élèves à supprimer inscrits dans le panier. <br />");
        print("<br />_____________________<br />");
        print($totallignes." lignes à traiter dans ce fichier.<br />");
        fclose($fichier);
    }
    
}

function gestion_groupe($id_groupe, $id_empr, $dbh) {
	//insertion dans la table empr_groupe
	$delete_empr_grpe = mysql_query("delete from empr_groupe where empr_id=$id_empr",$dbh);
    $insert_empr_grpe = mysql_query("INSERT INTO empr_groupe(empr_id, groupe_id) VALUES ($id_empr,$id_groupe)",$dbh);
}

function gestion_groupe_add($id_groupe, $id_empr, $dbh) {
	//insertion dans la table empr_groupe
    $insert_empr_grpe = mysql_query("INSERT INTO empr_groupe(empr_id, groupe_id) VALUES ($id_empr,$id_groupe)",$dbh);
}

function gestion_empr_categ($libelle, $dbh) {
	$rqt="SELECT id_categ_empr FROM empr_categ where libelle ='".addslashes($libelle)."' "; 
    $r_categ = mysql_query($rqt) or die(mysql_error()."<br /><br />$rqt<br /><br />");
	if (($categ=mysql_fetch_object($r_categ))) {
		$id_categ_empr=	$categ->id_categ_empr;					
	}else {
		mysql_query("INSERT INTO empr_categ(libelle, duree_adhesion ) VALUES ('".addslashes($libelle)."',365)",$dbh);
		$id_categ_empr = mysql_insert_id();
	}
	return $id_categ_empr;
}

function gestion_empr_idcode_codestat($idcode, $dbh) {
	// Si pas existant, on en crée un avec '-' comme label
	$rqt="SELECT * FROM empr_codestat where idcode ='$idcode' "; 
    $r_codestat = mysql_query($rqt) or die(mysql_error()."<br /><br />$rqt<br /><br />");
	if (($codestat=mysql_fetch_object($r_codestat))) {
		$idcode = $codestat->idcode;					
	}else {
		mysql_query("INSERT INTO empr_codestat(libelle ) VALUES ('".addslashes("-")."')",$dbh);
		$idcode = mysql_insert_id();
	}
	return $idcode;
}

function gestion_champ_portail($id_empr, $dbh) {
	global $idchamp;
	//insertion dans la table empr_custom_values
	$del = @mysql_query("delete from empr_custom_values where empr_custom_origine='$id_empr' and empr_custom_champ='".$idchamp['portail_only']."' ",$dbh);
	$ins = @mysql_query("insert into empr_custom_values set empr_custom_origine='$id_empr', empr_custom_champ='".$idchamp['portail_only']."', empr_custom_integer='1' ",$dbh);
}

function gestion_champ_id_etudiant($id_empr, $valeur="", $dbh) {
	global $idchamp;
	//insertion dans la table empr_custom_values
	$del = @mysql_query("delete from empr_custom_values where empr_custom_origine='$id_empr' and empr_custom_champ='".$idchamp['id_etudiant']."' ",$dbh);
	$ins = @mysql_query("insert into empr_custom_values set empr_custom_origine='$id_empr', empr_custom_champ='".$idchamp['id_etudiant']."', empr_custom_small_text='".$valeur."' ",$dbh);
}

function gestion_champ_numero_casier($id_empr, $valeur, $dbh) {
	global $idchamp;
	//insertion dans la table empr_custom_values
	$del = @mysql_query("delete from empr_custom_values where empr_custom_origine='$id_empr' and empr_custom_champ='".$idchamp['numero_casier']."' ",$dbh);
	$ins = @mysql_query("insert into empr_custom_values set empr_custom_origine='$id_empr', empr_custom_champ='".$idchamp['numero_casier']."', empr_custom_small_text='".addslashes($valeur)."' ",$dbh);
}

function quel_groupe($lib) {
	global $dbh;
	global $groupes ;
	if (count($groupes)==0) {
		$rqt="select id_groupe, libelle_groupe from groupe order by 2";
		$r = mysql_query($rqt) or die(mysql_error()."<br /><br />$rqt<br /><br />");
		while ($idgroupe_obj=mysql_fetch_object($r)) {
			$groupes[$idgroupe_obj->libelle_groupe] = $idgroupe_obj->id_groupe;
		} 
	}
	if ($groupes[$lib]) {
		return $groupes[$lib];
	} else {
		// création du groupe et retour de son id
		$insert_grpe = mysql_query("INSERT INTO groupe(libelle_groupe) VALUES('".addslashes($lib)."')");
		$groupes[$lib] = mysql_insert_id();
		return $groupes[$lib];
	}
	return 0;
}

function import_eleves_ensai($separateur, $dbh, $type_import){
	global $idchamp, $id_grp, $empr_location_id, $empr_location_lib, $id_categ_empr, $idemprcaddie ;
	
	if (!isset($id_grp)) $id_grp=array();
    //La structure du fichier texte doit être la suivante : 
    //id_etudiant/CB/Voie/Nom/Prénom/courriel/courriel_perso/casier/libelle_etat_civil/année de naissance/tel dom/tel_mobile
    
    $eleve_abrege = array("Numéro identifiant","Nom","Prénom");
    $date_auj = date("Y-m-d", time());
    $date_an_proch = date("Y-m-d", time()+3600*24*30.42*12);
    $empr_codestat_local=gestion_empr_idcode_codestat(8, $dbh);
    $empr_codestat_etranger=gestion_empr_idcode_codestat(9, $dbh);    
    //Upload du fichier
    if (!($_FILES['import_lec']['tmp_name'])) {
    	print "Cliquez sur Pr&eacute;c&eacute;dent et choisissez un fichier";
    } elseif (!(move_uploaded_file($_FILES['import_lec']['tmp_name'], "./temp/".basename($_FILES['import_lec']['tmp_name'])))) {
        print "Le fichier n'a pas pu être téléchargé. Voici plus d'informations :<br />";
        print_r($_FILES)."<p>";
    }
    $fichier = @fopen( "./temp/".basename($_FILES['import_lec']['tmp_name']), "r" );
       
    if ($fichier) {       
        $totallignes=0;
        while (!feof($fichier)) {
        	$buffer = fgets($fichier, 4096);
            $tab = explode($separateur, $buffer);
			//La structure du fichier texte doit être la suivante : 
			//empr_nom	empr_prenom	empr_mail	categ	Groupe1	Groupe2	statut_libelle	location_libelle	Pays	empr_date_adhesion	
			//empr_date_expiration	Numéro	Identifiant OPAC 																																																																																																																																																																																																																																																			
            $cb=trim(str_replace(" ","",$tab[11]));
			if ($cb!="Numéro" && $cb!="") {
				// ce n'est pas la première ligne d'entête de colonne
				$nom=trim($tab[0]);
				$prenom=trim($tab[1]);				
				$emails=trim($tab[2]);
				
				$categ=trim($tab[3]);
				$groupe1=trim($tab[4]);
				$groupe2=trim($tab[5]);
				$statut_libelle=trim($tab[6]);
				$location_libelle=trim($tab[7]);
				$pays=trim($tab[8]);
				$empr_date_adhesion=trim($tab[9]);
				$empr_date_expiration=trim($tab[10]);
				// cb en 11
				$identifiant_opac=trim($tab[12]);

				$liste_date=explode('/',$empr_date_adhesion);
				if($liste_date[2]<100)$liste_date[2]+=2000;
				$date_adhesion=$liste_date[2]."-".$liste_date[1]."-".$liste_date[0];
				
				$liste_date=explode('/',$empr_date_expiration);
				if($liste_date[2]<100)$liste_date[2]+=2000;
				$date_fin_adhesion=$liste_date[2]."-".$liste_date[1]."-".$liste_date[0];
				
				
				if($pays)$empr_codestat=$empr_codestat_etranger;
				else $empr_codestat=$empr_codestat_local;
				
				// recherche de id catégorie
				$id_categ_empr=gestion_empr_categ($categ, $dbh);
			   
            	// recherche des groupes
            	$id_groupe1 = quel_groupe($groupe1);
            	$id_groupe2 = quel_groupe($groupe2);
            	// Traitement de l'élève
				$rqt="select * from empr where empr_cb='".addslashes($cb)."'  ";
            	$nb = mysql_query($rqt,$dbh);
            	$nb_enreg=mysql_num_rows($nb);
	            switch ($nb_enreg) {
	                case 0:
	                	//Cet élève n'est pas enregistré
	                	if (!$identifiant_opac) $login = cre_login($nom, $prenom, $dbh);
	                	else $login = $identifiant_opac;
	                    $req_insert = "insert into empr SET empr_nom='".addslashes($nom)."', empr_prenom='".addslashes($prenom)."', empr_cb='".addslashes($cb)."', empr_pays='".addslashes($pays)."', ";
	                    $req_insert .= "empr_tel1='".addslashes($tel1)."', empr_tel2='".addslashes($tel2)."', empr_year='".addslashes($anneenaiss)."', empr_categ =$id_categ_empr, empr_codestat=$empr_codestat, empr_sexe='$sexe', ";
	                    $req_insert .= "empr_login='".$login."', empr_password='".addslashes($login)."', empr_mail='".addslashes($emails)."', ";
	                    $req_insert .= "empr_prof='', empr_lang='fr_FR', empr_statut=4, ";
	                    $req_insert .= "empr_location='$empr_location_id', ";
	                    $req_insert .= "empr_creation='$date_auj', empr_modif='$date_auj', empr_date_adhesion = '$date_adhesion', empr_date_expiration = '$date_fin_adhesion' ";

	                    //print $req_insert; exit;
	                    
	                    $insert = mysql_query($req_insert,$dbh) or die("<br />".mysql_error()."<br />".$req_insert);
	                    if (!$insert) {
	                        print("<b>Echec de la création de l'élève suivant (Erreur : ".mysql_error().") : </b><br />");
	                        print("<br />");
	                    } else {
		                    $id_cree = mysql_insert_id();
		                    gestion_groupe_add($id_groupe1, $id_cree, $dbh);
		                    gestion_groupe_add($id_groupe2, $id_cree, $dbh);
		                    /*gestion_champ_portail($id_cree, $dbh);
		                    gestion_champ_numero_casier($id_cree, $casier, $dbh);
		                    gestion_champ_id_etudiant($id_cree, $idetudiant, $dbh);
							*/
	                    	$cpt_insert ++;
	                    }
	                    $j++;                    
	                break;
	                default:
	                  
	                    print "<ul><li><font color=red><b>Echec pour l'élève suivant déjà présent: </b></font></li>
								<li><font color=red>$nom</font></li>
								<li><font color=red>$prenom</font></li>
								<li><font color=red>$cb</font></li>
								</ul>";
	                    print("<br />");
						break;
				}				
				$totallignes++;
            } // fin if pas première ligne ni vide
        } // while      

        //Affichage des insert et update
        if ($cpt_insert) print($cpt_insert." élèves créés. <br />");
        print("<br />_____________________<br />");
        print($totallignes." lignes ont été traitées dans ce fichier.<br />");
        fclose($fichier);
    }
    
}

?>



