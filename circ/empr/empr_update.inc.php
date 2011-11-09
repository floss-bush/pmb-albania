<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: empr_update.inc.php,v 1.48.2.1 2011-09-20 10:25:09 dgoron Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// update d'un emprunteur
if ($forcage == 1) {
	$tab= unserialize(stripslashes($ret_url));
	foreach($tab->GET as $key => $val){
		if (get_magic_quotes_gpc())
			$GLOBALS[$key] = $val;
		else {
			add_sl($val);
			$GLOBALS[$key] = $val;
		}  
	}	
	foreach($tab->POST as $key => $val){
		if (get_magic_quotes_gpc())
			$GLOBALS[$key] = $val;
		else {
			add_sl($val);
			$GLOBALS[$key] = $val;
		}
	}
}
require_once("$class_path/emprunteur.class.php");
require_once("$class_path/serial_display.class.php");
require_once("$class_path/comptes.class.php");

function rec_abonnement($id,$type_abt,$empr_categ,$rec_caution=true) {
	global $pmb_gestion_financiere,$pmb_gestion_abonnement;
	
	if ($pmb_gestion_financiere) {
		//Récupération du tarif
		if ($pmb_gestion_abonnement==1) {
			$requete="select tarif_abt, libelle from empr_categ where id_categ_empr=$empr_categ";
			$resultat=mysql_query($requete);
		} else {
			if ($pmb_gestion_abonnement==2) {
				$requete="select tarif, type_abt_libelle, caution from type_abts where id_type_abt=$type_abt";
				$resultat=mysql_query($requete);
			}
		}
		if (@mysql_num_rows($resultat)) {
			$tarif=mysql_result($resultat,0,0);
			$libelle=mysql_result($resultat,0,1);
			if ($pmb_gestion_abonnement==2) $caution=mysql_result($resultat,0,2);
		}
		$compte_id=comptes::get_compte_id_from_empr($id,1);
		if ($compte_id) {
			$cpte=new comptes($compte_id);
		}
		if ($tarif*1) {
			//Enregistrement de la transaction
			$cpte->record_transaction("",abs($tarif),-1,"Inscription : ".$libelle,0);
		}
		if (($caution*1)&&($rec_caution)) {
			$cpte->record_transaction("",abs($caution),-1,"Caution : ".$libelle,0);
			$requete="update empr set caution='".abs($caution)."' where id_empr=$id";
			mysql_query($requete);
		}
	}
}

function rec_groupe_empr($id, $tableau_groupe) {
	global $dbh;
	$requete="delete from empr_groupe where empr_id='$id' ";
	mysql_query($requete, $dbh);
	for ($i = 0 ; $i < sizeof($tableau_groupe) ; $i++) {
		$rqt = "insert into empr_groupe (empr_id, groupe_id) values ('".$id."', '".$tableau_groupe[$i]."') " ;
		mysql_query($rqt, $dbh);
		}
	}

// inscription automatique du lecteur dans la DSI de sa catégorie 
function ins_lect_categ_dsi($id_empr=0, $categorie_lecteurs=0, $anc_categorie_lecteurs=0) {
	global $dbh;
	global $dsi_insc_categ ;
	if (!$dsi_insc_categ || !$id_empr || !$categorie_lecteurs) return ;
	
	// suppression de l'inscription dans les bannettes de son ancienne catégorie
	if ($anc_categorie_lecteurs) {
		$req_ban = "select id_bannette from bannettes where categorie_lecteurs='$anc_categorie_lecteurs'" ;
    	$res_ban=mysql_query($req_ban, $dbh) ;
    	while ($ban=mysql_fetch_object($res_ban)) {
			mysql_query("delete from bannette_abon where num_bannette='$ban->id_bannette' and num_empr='$id_empr' ", $dbh) ;
    		}
		}
	
	// inscription du lecteur dans la DSI de sa nouvelle catégorie 
	$req_ban = "select id_bannette from bannettes where categorie_lecteurs='$categorie_lecteurs'" ;
    $res_ban=mysql_query($req_ban, $dbh) ;
    while ($ban=mysql_fetch_object($res_ban)) {
    	mysql_query("delete from bannette_abon where num_bannette='$ban->id_bannette' and num_empr='$id_empr' ", $dbh) ;
    	mysql_query("insert into bannette_abon (num_bannette, num_empr) values('$ban->id_bannette', '$id_empr')", $dbh) ;
    	}
    return ;
	}

if ($form_prenom) echo window_title($database_window_title."$form_nom, $form_prenom");
	else echo window_title($database_window_title.$form_nom);

// vérification validité des données fournies.
$nberrors = 0;
$errormessage = "";

// vérification complète de l'email
if ($form_mail != "") {
	$form_mail = pmb_strtolower($form_mail);
	if (strlen($form_mail) < 3) {
		$error_message .= "<p>$form_mail : $msg[756]</p>";
		$nberrors++;
		}
	if (strlen($form_mail) > 255) {
		$error_message .= "<p>$form_mail : $msg[757]</p>";
		$nberrors++;
		}
	if (!ereg("@", $form_mail)) {
		$error_message .= "<p>$form_mail : $msg[758]</p>";
		$nberrors++;
		}
	}

// vérification du login: seulement si auth = MYSQL => $form_ldap='' 
// si auth = LDAP => $form_ldap='on' (check-box)
// le format du login ldap n'est pas contrôlé

if (!$form_ldap) {  
	$form_empr_login = convert_diacrit(pmb_strtolower($form_empr_login)) ;
	$form_empr_login = pmb_alphabetic('^a-z0-9\.\_\-\@', '', $form_empr_login);
	if ($form_empr_login != "") {
		$form_empr_login = pmb_strtolower($form_empr_login);
		if (strlen($form_empr_login) < 2) {
			$error_message .= "<p>$form_empr_login : $msg[empr_form_login]</p>";
			$nberrors++;
		}
		$requete = "SELECT id_empr, empr_login FROM empr WHERE empr_login='$form_empr_login' and id_empr!='$id' ";
		$res = mysql_query($requete, $dbh);
		$nbr_lignes = mysql_num_rows($res);
		if ($nbr_lignes) {
			$error_message .= "<p>$form_empr_login : $msg[empr_form_login_existant]</p>";
			$nberrors++;
		}
	} else {
		$form_empr_login = pmb_substr($form_prenom,0,1).$form_nom ;
		$form_empr_login = str_replace(CHR(32),"",$form_empr_login);
		$form_empr_login = pmb_strtolower($form_empr_login);
		$form_empr_login = clean_string($form_empr_login) ;
		$form_empr_login = convert_diacrit(pmb_strtolower($form_empr_login)) ;
		$form_empr_login = pmb_alphabetic('^a-z0-9\.\_\-\@', '', $form_empr_login);
		$form_empr_login_original = $form_empr_login;
		$pb = 1 ;
		$num_login=1 ;
		while ($pb==1) {
			$requete = "SELECT empr_login FROM empr WHERE empr_login='$form_empr_login' LIMIT 1 ";
			$res = mysql_query($requete, $dbh);
			$nbr_lignes = mysql_num_rows($res);
			if ($nbr_lignes) {
				$form_empr_login = $form_empr_login_original.$num_login ;
				$num_login++;
			} else $pb = 0 ;
		}
	}
} 

if ($empr_birthdate_optional == 0) {
	// vérification de l'année de naissance
	if ($form_year == "") {
		$error_message .= "<p>$msg[762]</p>";
		$nberrors++;
		}
	}

//Vérification des champs personalisés
//Ici on récupère les valeurs des champs personalisés
$p_perso=new parametres_perso("empr");
$nberrors+=$p_perso->check_submited_fields();
$error_message.=$p_perso->error_message;

// vérification des doublons : si param et $id_empr vide (création uniquement)
if ($empr_lecteur_controle_doublons != 0 && !$id && !$forcage) {
		$empr_lecteur_controle_doublons=str_replace(' ','',$empr_lecteur_controle_doublons);
		$param_verif_doublons = explode(",",$empr_lecteur_controle_doublons);
		$requete = "SELECT empr_cb FROM empr WHERE 1 ";
		foreach($param_verif_doublons as $num  => $field) {
			
			if ($num==0) { // C'est la commande
				$cmd_doublons=$field;
				switch($field){
					case "1":
						$requete = "SELECT empr_cb FROM empr WHERE 1 ";
						break;
					case "2":
						$requete = "SELECT empr_cb FROM empr LEFT JOIN empr_custom_values ON empr_custom_origine = id_empr WHERE 1 ";
						break; 
					case "3":
						$requete = "SELECT empr_cb FROM empr LEFT JOIN empr_custom_values ON empr_custom_origine = id_empr LEFT JOIN empr_groupe ON empr_id = id_empr WHERE 1  ";
						$groupe=$_POST["id_grp"];
						$requete.= " AND groupe_id= '$groupe[0]' ";
						break; 
					default:					    
						break;
				}	
			} else {
				// Ce sont les parametres: nom de champ dans empr_cb ou champs perso
				$requete.= " AND";
				switch($field){
					case "empr_nom":$requete.= " $field= '$form_nom' ";break;
					case "empr_prenom":$requete.= " $field= '$form_prenom' ";break;
					case "empr_adr1":$requete.= " $field= '$form_adr1' ";break;
					case "empr_adr2":$requete.= " $field= '$form_adr2' ";break;
					case "empr_cp":$requete.= " $field= '$form_cp' ";break;
					case "empr_ville":$requete.= " $field= '$form_ville' ";break;
					case "empr_pays":$requete.= " $field= '$form_pays' ";break;
					case "empr_mail":$requete.= " $field= '$form_mail' ";break;
					case "empr_tel1":$requete.= " $field= '$form_tel1' ";break;
					case "empr_sms":$requete.= " $field= '$form_sms' ";break;
					case "empr_tel2":$requete.= " $field= '$form_tel2' ";break;
					case "empr_prof":$requete.= " $field= '$form_prof' ";break;
					case "empr_year":$requete.= " $field= '$form_year' ";break;
					case "empr_categ":$requete.= " $field= '$form_categ' ";break;
					case "empr_codestat":$requete.= " $field= '$form_codestat' ";break;			
					case "empr_sexe":$requete.= " $field= '$form_sexe' ";break;
					case "empr_login":$requete.= " $field= '$form_empr_login' ";break;
					case "empr_msg":$requete.= " $field= '$form_empr_msg' ";break;
					case "empr_lang":$requete.= " $field= '$form_empr_lang' ";break;					
					default:
						// Champ perso 
						$perso = "SELECT idchamp ,datatype FROM empr_custom WHERE name = '$field'";						
						$res = mysql_query($perso, $dbh);
						$row=mysql_fetch_row($res);
						if($row){
							$val=$_POST["$field"];
							$champ="empr_custom_".$row[1];
							$requete.= " empr_custom_champ = $row[0] ";
							foreach($val as $dummykey=>$value) {
								$requete.= "AND $champ= '$value' ";
							}
						} else $requete.= " 1 ";
					break;
				}	
			}			
		}
		$res = mysql_query($requete, $dbh) or die ("ERROR with SQL proc to check borrowers<br />".$requete ." <br />".mysql_error());
		if(($result=mysql_num_rows($res))){
			//$error_message .= "<p>".$msg["Doublons_fiche_emprunteur"]."</p>";
			$error_message .= "<p>ERREUR DOUBLON LECTEUR</p>";
			$nberrors++;			
		}
	}
if(!$f_cb){
	print "<script type='text/javascript'>alert ('code vide'); history.go(-1);</script>";
	exit;
}
// y'a t'il eu des erreurs ?
if ($nberrors > 0) {
	if(ereg("<p>ERREUR DOUBLON LECTEUR</p>",$error_message) && $nberrors == 1 && !$id){// Je passe ici pour la création d'un nouveau lecteur et que s'il y a une erreur de doublon
		$tab='';
		$tab->POST = $_POST;
		$tab->GET = $_GET;
		$ret_url= htmlentities(serialize($tab), ENT_QUOTES,$charset);
		print "
			<br /><div class='erreur'>$msg[540]</div>
			<script type='text/javascript' src='./javascript/tablist.js'></script>
			<div class='row'>
				<div class='colonne10'>
					<img src='./images/error.gif' align='left'>
				</div>
				<div class='colonne80'>
					<strong>".$msg["Doublons_fiche_emprunteur"]."</strong>
				</div>
			</div>
			<div class='row'>
				<form class='form-$current_module' name='dummy'  method='post' action='./circ.php?categ=empr_update&amp;id=&amp;groupID='>
					<input type='hidden' name='forcage' value='1'>
					<input type='hidden' name='ret_url' value='$ret_url'>
					<input type='button' name='ok' class='bouton' value=' $msg[76] ' onClick='history.go(-1);'>
					<input type='submit' class='bouton' name='bt_forcage' value=' ".htmlentities($msg["gen_signature_forcage"], ENT_QUOTES)." '>
				</form>
				
			</div>
			";
		$requete.=" GROUP BY empr_cb";
		$res = mysql_query($requete);
		while ($obj_emp = mysql_fetch_object($res)) {
			$requete="SELECT id_empr FROM empr WHERE empr_cb='".$obj_emp->empr_cb."'";
			$result=mysql_query($requete,$dbh);
			$id_empr=mysql_result($result,0,0);
			$link = './circ.php?categ=pret&form_cb='.rawurlencode($obj_emp->empr_cb);
			$lien_suppr_cart = "";
			$empr = new emprunteur($id_empr,"",FALSE,3);
			$empr->fiche_consultation = str_replace('!!image_suppr_caddie_empr!!'    , $lien_suppr_cart    , $empr->fiche_consultation);
			$empr->fiche_consultation = str_replace('!!lien_vers_empr!!'    , $link    , $empr->fiche_consultation);
			
			print $empr->fiche_consultation; 
		}
		exit ;
	}else{
		$error_message=str_replace("<p>ERREUR DOUBLON LECTEUR</p>","",$error_message);
		$error_message=str_replace("<p>","",$error_message);
		error_form_message(str_replace("</p>","",$error_message));
		//error_message("$msg[751] : $nberrors", $error_message."<p>".$msg[760]."</p>");
	}
} else if (!$id) {
		
		// création empr
		$requete = "SELECT empr_cb FROM empr WHERE empr_cb='$f_cb' LIMIT 1 ";
		$res = mysql_query($requete, $dbh);
		$nbr_lignes = mysql_num_rows($res);
		if (!$nbr_lignes) {
			$requete = "INSERT INTO empr SET ";
			$requete .= "empr_cb='".(string)$f_cb."', ";
			$requete .= "empr_nom='$form_nom', ";
			$requete .= "empr_prenom='$form_prenom', ";
			$requete .= "empr_adr1='$form_adr1', ";
			$requete .= "empr_adr2='$form_adr2', ";
			$requete .= "empr_cp='$form_cp', ";
			$requete .= "empr_ville='$form_ville', ";
			$requete .= "empr_pays='$form_pays', ";
			$requete .= "empr_mail='$form_mail', ";
			$requete .= "empr_tel1='$form_tel1', ";
			$requete .= "empr_sms='$form_sms', ";
			$requete .= "empr_tel2='$form_tel2', ";
			$requete .= "empr_prof='$form_prof', ";
			$requete .= "empr_year='$form_year', ";
			$requete .= "empr_categ='$form_categ', ";
			$requete .= "empr_statut='$form_statut', ";
			$requete .= "empr_lang='$form_empr_lang', ";
			
			if ($form_adhesion=="") $requete .= "empr_date_adhesion=CURRENT_DATE(), "; else $requete .= "empr_date_adhesion='$form_adhesion', ";
			if (($form_expiration=="") or ($form_expiration==$form_adhesion)) {
				/* AJOUTER ICI LE CALCUL EN FONCTION DE LA CATEGORIE */
				$rqt_empr_categ = "select duree_adhesion from empr_categ where id_categ_empr = $form_categ ";
				$res_empr_categ = mysql_query($rqt_empr_categ, $dbh);
				$empr_categ = mysql_fetch_object($res_empr_categ);
				//$form_adhesion=preg_replace('/-/', '', $form_adhesion);

				$rqt_date = "select date_add('".$form_adhesion."', INTERVAL $empr_categ->duree_adhesion DAY) as date_expiration " ;
				$resultatdate=mysql_query($rqt_date);
				$resdate=mysql_fetch_object($resultatdate);
				$requete .= "empr_date_expiration='".$resdate->date_expiration."', ";

				} else $requete .= "empr_date_expiration='$form_expiration', ";
			$requete .= "empr_codestat=$form_codestat, ";
			$requete .= "empr_creation=CURRENT_TIMESTAMP(), ";
			$requete .= "empr_modif=CURRENT_DATE(), ";
			$requete .= "empr_sexe=$form_sexe, ";
			$requete .= "empr_msg='$form_empr_msg', ";
			$requete .= "empr_login='$form_empr_login', ";
			if (!$empr_location_id) {
				if ($deflt2docs_location) $empr_location_id=$deflt2docs_location ;
				else {
					$loca = mysql_query("select min(idlocation) as idlocation from docs_location", $dbh);
					$locaid = mysql_fetch_object($loca);
					$empr_location_id = $locaid->idlocation ;
					}
				}
			$requete .= "empr_location='$empr_location_id', ";

			// ldap - MaxMan
			if ($form_ldap=='on'){
				$requete .= "empr_ldap='1', ";
				$form_empr_password="";
			}else{
				$requete .= "empr_ldap='0', ";
			}

			//Gestion financière
			if (($pmb_gestion_abonnement==2)&&($pmb_gestion_financiere)) {
				$requete.="type_abt='".$type_abt."', ";
			} else {
				$requete.="type_abt=0, ";
			}

			if ($form_empr_password!="") $requete .= "empr_password='$form_empr_password' ";
				else $requete .= "empr_password='$form_year' ";
			
			$res = mysql_query($requete, $dbh);

			if($res) {
				// on récupère l'id du de l'emprunteur
				$id = mysql_insert_id($dbh);
				$p_perso->rec_fields_perso($id);
				rec_groupe_empr($id, $id_grp) ;
				ins_lect_categ_dsi($id, $form_categ, 0) ;
				if (($pmb_gestion_financiere)&&($pmb_gestion_abonnement))
					rec_abonnement($id,$type_abt,$form_categ);
				
				$empr = new emprunteur($id, '', FALSE, 1);
				print pmb_bidi($empr->fiche);
			} else {
				error_message(    $msg[42], $msg[78], 1, './circ.php?categ=empr_create');
				}
			} else {
				print "<script type='text/javascript'>alert ('code déjà utilisé'); history.go(-1);</script>";
					exit;
					}
		} else {
			// si l'id est fournie, c'est une modification
			/* il faut vérifier ce qui est modifié pour la durée d'adhésion :
				si fin adhésion modifiée
				on applique celle-ci
				sinon
				si empr_categ modifiée :
					on recalcule la fin d'adhésion avec la nouvelle categ et on l'applique
			*/

			$query_verif = "select empr_cb from empr where id_empr = '".$id."' ";
			$res_cb = mysql_fetch_row(mysql_query($query_verif,$dbh));
			if ($res_cb[0]!=$f_cb) {
				// il y a eu modif du cb, il faut vérifier qu'il n'est pas déjà utilisé
				$query_verif = "select count(1) from empr where empr_cb = '".$f_cb."' ";
				$ok = mysql_result(mysql_query($query_verif,$dbh), 0, 0);
				if ($ok) {
					print "<script type='text/javascript'>alert ('code déjà utilisé'); history.go(-1);</script>";
					exit;
					}
				}

			$rqt_empr = "select empr_categ, empr_date_expiration from empr where id_empr=$id ";
			$res_empr = mysql_query($rqt_empr, $dbh);
			$empr_lu = mysql_fetch_object($res_empr);
			$anc_categ = $empr_lu->empr_categ ;
			$form_expiration_applicable = "";
			if (preg_replace('/-/', '', $empr_lu->empr_date_expiration) != $form_expiration) {
				$form_expiration_applicable = "empr_date_expiration='$form_expiration', ";
			} elseif ($anc_categ != $form_categ) {
				//On ne change rien en fait, car si une date d'adhesion est ancienne on se retrouve avec des lecteurs expirés si ils changent de catégorie...
				/*$rqt_empr_categ = "select duree_adhesion from empr_categ where id_categ_empr = '$form_categ' ";
				$res_empr_categ = mysql_query($rqt_empr_categ, $dbh);
				$empr_categ = mysql_fetch_object($res_empr_categ);

				$rqt_date = "select date_add('".$form_adhesion."', INTERVAL $empr_categ->duree_adhesion DAY) as date_expiration " ;
				$resultatdate=mysql_query($rqt_date);
				$resdate=mysql_fetch_object($resultatdate);
				$form_expiration_applicable = "empr_date_expiration='".$resdate->date_expiration."', ";*/
				$form_expiration_applicable="";
			}

			$requete = "UPDATE empr SET ";
			$requete .= "empr_nom='$form_nom',";
			$requete .= "empr_prenom='$form_prenom',";
			$requete .= "empr_cb='".(string)$f_cb."',";
			$requete .= "empr_adr1='$form_adr1',";
			$requete .= "empr_adr2='$form_adr2',";
			$requete .= "empr_cp='$form_cp',";
			$requete .= "empr_ville='$form_ville',";
			$requete .= "empr_pays='$form_pays',";
			$requete .= "empr_mail='$form_mail',";
			$requete .= "empr_tel1='$form_tel1',";
			$requete .= "empr_sms='$form_sms',";
			$requete .= "empr_tel2='$form_tel2',";
			$requete .= "empr_prof='$form_prof',";
			$requete .= "empr_year='$form_year',";
			$requete .= "empr_categ=$form_categ,";
			$requete .= "empr_statut='$form_statut',";
			$requete .= "empr_lang='$form_empr_lang', ";
			$requete .= "empr_codestat=$form_codestat,";
			$requete .= "empr_date_adhesion='$form_adhesion', ";
			$requete .= $form_expiration_applicable;
			$requete .= "empr_modif=CURRENT_DATE(),";
			$requete .= "empr_sexe=$form_sexe, ";
			$requete .= "empr_location='$empr_location_id', ";

			// ldap - MaxMan
			if ($form_ldap=='on'){
				$requete .= "empr_ldap='1', ";
				$form_empr_password="";
			}else{
				$requete .= "empr_ldap='0', ";
			}

			//Gestion financière
			if (($pmb_gestion_abonnement==2)&&($pmb_gestion_financiere)) {
				$requete.="type_abt='".$type_abt."', ";
			} else {
				$requete.="type_abt=0, ";
			}

			if ($form_empr_password!="") $requete .= "empr_password='$form_empr_password', ";
			$requete .= "empr_msg='$form_empr_msg', ";
			$requete .= "empr_login='$form_empr_login' ";
			$requete .= " WHERE id_empr='$id' ";
				
			$res = mysql_query($requete, $dbh);
	
			if(!mysql_errno($dbh)) {
				$p_perso->rec_fields_perso($id);
				rec_groupe_empr($id, $id_grp) ;
				// DSI : sur modification de lecteur, pas de mofification de ses inscriptions aux bannettes.
				// ins_lect_categ_dsi($id, $form_categ, $anc_categ) ;
				if ($debit) {
					if ($debit==2) $rec_caution=true; else $rec_caution=false;
					rec_abonnement($id,$type_abt,$form_categ,$rec_caution);
				}		

				$empr = new emprunteur($id, '', FALSE, 1);
				print pmb_bidi($empr->fiche);
			} else {
				error_message($msg[540], "erreur modification emprunteur", 1, './circ.php?categ=empr_create');
			}
		}
