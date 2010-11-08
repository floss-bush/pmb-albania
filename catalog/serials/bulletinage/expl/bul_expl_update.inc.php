<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: bul_expl_update.inc.php,v 1.25 2009-07-24 08:20:06 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// mise a jour de l'entete de page
if(!$expl_id) {
	// pas d'id, c'est une creation
	echo str_replace('!!page_title!!', $msg[4000].$msg[1003].$msg[4007], $serial_header);
} else {
	echo str_replace('!!page_title!!', $msg[4000].$msg[1003].$msg[4008], $serial_header);
}


//verification des droits de modification notice
$acces_m=1;
if ($gestion_acces_active==1 && $gestion_acces_user_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom_1= $ac->setDomain(1);
	$acces_j = $dom_1->getJoin($PMBuserid,8,'bulletin_notice');
	$q = "select count(1) from bulletins $acces_j where bulletin_id=".$expl_bulletin;
	$r = mysql_query($q, $dbh);
	if(mysql_result($r,0,0)==0) {
		$acces_m=0;
	}
}

if ($acces_m==0) {

	if (!$expl_id) {
		error_message('', htmlentities($dom_1->getComment('mod_bull_error'), ENT_QUOTES, $charset), 1, '');
	} else {
		error_message('', htmlentities($dom_1->getComment('mod_expl_error'), ENT_QUOTES, $charset), 1, '');
	}

} else {
		
	
	// le form d'exemplaire renvoit :
	// Je nettoie ce qui me parait devoir etre nettoye
	
	// $bul_id
	// $id_form
	// $org_cb
	// $expl_id
	// $expl_bulletin
	// $expl_typdoc
	$expl_cote = clean_string($expl_cote);
	// $expl_section
	// $expl_statut
	// $expl_location
	// $expl_codestat
	$expl_note = clean_string($expl_note);
	$expl_comment = clean_string($f_ex_comment);
	$expl_prix = clean_string($expl_prix);
	// $expl_owner
	
	//Verification des champs personalises
	$p_perso=new parametres_perso("expl");
	$nberrors=$p_perso->check_submited_fields();
	if ($nberrors) {
		error_message_history($msg["notice_champs_perso"],$p_perso->error_message,1);
		exit();
	}
	// controle sur le nouveau code barre si applicable :
	if($org_cb != $f_ex_cb) {
		// si le nouveau code-barre est deja utilise, on reste sur l'ancien
		$requete = "SELECT expl_id FROM exemplaires WHERE expl_cb='$f_ex_cb'";
		
		$myQuery = mysql_query($requete, $dbh);
		if(!($result=mysql_result($myQuery, 0, 0))) {
			$expl_cb = $f_ex_cb;
		} else {
			// Verif si expl_id est celui poste
			if($expl_id == $result[0]) {
				$expl_cb = $org_cb;
			} else {
				//Erreur: code barre deja existant
				error_message_history($msg[301],$msg[303],1);
				exit();
			}
		}	
	} else {
		$expl_cb = $f_ex_cb;
	}
	
	// on prepare la date de creation ou modification
	$expl_date = today();
	
	// on recupere les valeurs 
	$formlocid="f_ex_section".$expl_location ;
	$expl_section=$$formlocid ;
	
	// preparation de la requete
	if($expl_id) {
		// update de l'exemplaire
		// on prepare la requete
		$values = "expl_cb='$expl_cb'";
		$values .= ", expl_typdoc='$expl_typdoc'";
		$values .= ", expl_cote='$expl_cote'";
		$values .= ", expl_section='$expl_section'";
		$values .= ", expl_statut='$expl_statut'";
		$values .= ", expl_location='$expl_location'";
		$values .= ", expl_codestat='$expl_codestat'";
		$values .= ", expl_note='$expl_note'";
		$values .= ", expl_comment='$expl_comment'";
		$values .= ", expl_prix='$expl_prix'";
		$values .= ", expl_owner='$expl_owner'";
		$values .= ", type_antivol='$type_antivol'";
		$values .= ", expl_nbparts='$f_ex_nbparts'";
		$requete = "UPDATE exemplaires SET $values WHERE expl_id=$expl_id AND expl_notice=0 LIMIT 1";
		$myQuery = mysql_query($requete, $dbh);
		audit::insert_modif (AUDIT_EXPL, $expl_id) ;
	} else {
		// insertion d'un nouvel exemplaire
		$values = "expl_cb='$expl_cb'";
		$values .= ", expl_notice='0'";
		$values .= ", expl_bulletin='$expl_bulletin'";
		$values .= ", expl_typdoc='$expl_typdoc'";
		$values .= ", expl_cote='$expl_cote'";
		$values .= ", expl_section='$expl_section'";
		$values .= ", expl_statut='$expl_statut'";
		$values .= ", expl_location='$expl_location'";
		$values .= ", expl_codestat='$expl_codestat'";
		$values .= ", expl_note='$expl_note'";
		$values .= ", expl_comment='$expl_comment'";
		$values .= ", expl_prix='$expl_prix'";
		$values .= ", expl_owner='$expl_owner'";
		$values .= ", type_antivol='$type_antivol'";
		$values .= ", expl_nbparts='$f_ex_nbparts'";
		$requete = "INSERT INTO exemplaires set $values , create_date=sysdate() ";
		$myQuery = mysql_query($requete, $dbh);
		$expl_id=mysql_insert_id();
		audit::insert_creation (AUDIT_EXPL, $expl_id) ;
	}
	
	//Insertion des champs personalises
	$p_perso->rec_fields_perso($expl_id);
	
	$id_form = md5(microtime());
	print "<div class='row'><div class='msg-perio'>".$msg[maj_encours]."</div></div>";
	$retour = "./catalog.php?categ=serials&sub=view&sub=bulletinage&action=view&bul_id=$expl_bulletin";
	
	if ($pointage) {
		$templates="<script type='text/javascript'>
	
			function Fermer(obj,type_doc) {		
				var obj_1=obj+\"_1\";	
				var obj_2=obj+\"_2\";	
				var obj_3=obj+\"_3\";		
				parent.document.getElementById(obj_1).disabled = true;
				parent.document.getElementById(obj_2).disabled = true;
				parent.document.getElementById(obj_3).disabled = true;								
			 	parent.kill_frame_periodique();
			}	
	
		</script>
		<script type='text/javascript'>Fermer('$id_bull','$type_doc');</script>
		";
	} else {
		print "<form class='form-$current_module' name=\"dummy\" method=\"post\" action=\"$retour\" style=\"display:none\">
		<input type=\"hidden\" name=\"id_form\" value=\"$id_form\">
		</form>
		<script type=\"text/javascript\">document.dummy.submit();</script>";
	}
}
?>