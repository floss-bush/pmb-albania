<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: serial_update.inc.php,v 1.50 2010-01-26 14:44:39 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");
	
echo str_replace('!!page_title!!', $msg[4000].$msg[1003].$msg[346], $serial_header);

//droits d'acces
if ($gestion_acces_active==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
}

//verification des droits de modification notice
$acces_m=1;
if ($gestion_acces_active==1 && $gestion_acces_user_notice==1) {
	$dom_1= $ac->setDomain(1);
	if($serial_id!=0) $acces_m = $dom_1->getRights($PMBuserid,$serial_id,8);
}
if ($acces_m==0) {

	error_message('', htmlentities($dom_1->getComment('mod_noti_error'), ENT_QUOTES, $charset), 1, '');

} else {
	
	// nettoyage des valeurs du form
	$f_tit1 = clean_string($f_tit1);
	$f_tit3 = clean_string($f_tit3);
	$f_tit4 = clean_string($f_tit4);
	//$f_indexation = clean_string($f_indexation);
	$f_lien = clean_string($f_lien);
	$f_eformat = clean_string($f_eformat);
	
	require_once($class_path."/notice_doublon.class.php");
	//Si control de dédoublonnage activé	
	if( $pmb_notice_controle_doublons) {
		$sign = new notice_doublon();
		$signature = $sign->gen_signature();
	}	
	
	if ($forcage == 1) {
		$tab= unserialize( urldecode($ret_url) );
		foreach($tab->GET as $key => $val){
			if(!is_array($val)) { $val=addslashes($val);}
			$GLOBALS[$key] = $val;	    
		}	
		foreach($tab->POST as $key => $val){
			if(!is_array($val)) { $val=addslashes($val);}
			$GLOBALS[$key] = $val;
		}
		$signature = $sign->gen_signature();
	} else if( ($pmb_notice_controle_doublons) != 0 && !$serial_id ) {
		
		//Si controle de dedoublonnage active	
		$signature = $sign->gen_signature();	
		$requete="select signature, niveau_biblio ,notice_id from notices where signature='$signature' and niveau_biblio='$b_level' limit 1 ";
	
		$result=mysql_query($requete, $dbh);	
		if (($r=mysql_fetch_object($result))) {
			//affichage de l'erreur, en passant tous les param postes (serialise) pour l'eventuel forcage 	
			$tab->POST = $_POST;
			$tab->GET = $_GET;
			$ret_url= urlencode(serialize($tab));
	
			require_once("$class_path/serial_display.class.php");
			print "
				<br /><div class='erreur'>$msg[540]</div>
				<script type='text/javascript' src='./javascript/tablist.js'></script>
				<div class='row'>
					<div class='colonne10'>
						<img src='./images/error.gif' align='left'>
					</div>
					<div class='colonne80'>
						<strong>".$msg["gen_signature_erreur_similaire"]."</strong>
					</div>
				</div>
				<div class='row'>
					<form class='form-$current_module' name='dummy'  method='post' action='./catalog.php?categ=serials&sub=update&id=0'>
						<input type='hidden' name='forcage' value='1'>
						<input type='hidden' name='signature' value='$signature'>
						<input type='hidden' name='ret_url' value='$ret_url'>
						<input type='button' name='ok' class='bouton' value=' $msg[76] ' onClick='history.go(-1);'>
						<input type='submit' class='bouton' name='bt_forcage' value=' ".htmlentities($msg["gen_signature_forcage"], ENT_QUOTES)." '>
					</form>
					
				</div>
				";		
			// on a affaire a un periodique
			$nt = new serial_display($r->notice_id,1);		
			echo "
				<div class='row'>
				$nt->result
		 	    </div>
				<script>document.getElementById('el".$r->notice_id."Child').setAttribute('startOpen','Yes');</script>
				<script type='text/javascript'>document.forms['dummy'].elements['ok'].focus();</script>";	
			exit();
		}
	}
	
	// les valeurs passees sont mises en tableau pour etre passees
	// a la methode de mise a jour
	$table = array();
	$table['typdoc']        = $typdoc;
	$table['statut']        = $form_notice_statut;
	$table['commentaire_gestion'] =  $f_commentaire_gestion ;
	$table['thumbnail_url'] =  $f_thumbnail_url ;
	$table['code']          = $f_cb;
	$table['tit1']          = $f_tit1;
	$table['tit3']          = $f_tit3;
	$table['tit4']          = $f_tit4;
	
	// auteur principal
	$f_aut[] = array (
			'id' => $f_aut0_id,
			'fonction' => $f_f0_code,
			'type' => '0',
			'ordre' => 0 );
	// autres auteurs
	for ($i=0; $i<$max_aut1; $i++) {
		$var_autid = "f_aut1_id$i" ;
		$var_autfonc = "f_f1_code$i" ;
		$f_aut[] = array (
				'id' => $$var_autid,
				'fonction' => $$var_autfonc,
				'type' => '1',
				'ordre' => $i );
	}
	// auteurs secondaires
	for ($i=0; $i<$max_aut2 ; $i++) {
	
		$var_autid = "f_aut2_id$i" ;
		$var_autfonc = "f_f2_code$i" ;
		$f_aut[] = array (
				'id' => $$var_autid,
				'fonction' => $$var_autfonc,
				'type' => '2',
				'ordre' => $i );
	}
	
	$table['ed1_id']        = $f_ed1_id;
	$table['ed2_id']        = $f_ed2_id;
	$table['year']        	= $f_year;
	$table['n_gen']         = $f_n_gen;
	$table['n_contenu']		= $f_n_contenu;
	$table['n_resume']      = $f_n_resume;
	
	$date_parution = serial::get_date_parution($f_year);
	$table['date_parution']      = $date_parution;
	
	// categories
	for ($i=0; $i< $max_categ ; $i++) {
		$var_categid = "f_categ_id$i" ;
		$f_categ[] = array (
				'id' => $$var_categid,
				'ordre' => $i );
	}
	
	$table['indexint']      = $f_indexint_id;
	$table['index_l']       = clean_tags($f_indexation);
	$table['lien']          = $f_lien;
	$table['eformat']       = $f_eformat;
	$table['niveau_biblio'] = $b_level;
	$table['niveau_hierar'] = $h_level;
	$table['signature'] 	= $signature;
	$table['opac_visible_bulletinage']= $opac_visible_bulletinage;

	$p_perso=new parametres_perso("notices");
	$nberrors=$p_perso->check_submited_fields();
	
	if (!$nberrors) {
		$serial = new serial($serial_id);
		$update_result = $serial->update($table);
	} else {
		error_message_history($msg["notice_champs_perso"],$p_perso->error_message,1);
		exit();
	}
	if ($update_result) {
		//traitement des droits d'acces user_notice
		if ($gestion_acces_active==1 && $gestion_acces_user_notice==1) {
			if ($serial_id) {		
				$dom_1->storeUserRights(1, $update_result, $res_prf, $chk_rights, $prf_rad, $r_rad);
			} else {
				$dom_1->storeUserRights(0, $update_result, $res_prf, $chk_rights, $prf_rad, $r_rad);
			}
		}
		//on applique les memes droits  d'acces user_notice aux bulletins et depouillements lies
		$q = "select num_notice from bulletins where bulletin_notice=$serial_id ";
		$q.= "union ";
		$q.= "select analysis_notice from analysis join bulletins on analysis_bulletin=bulletin_id where bulletin_notice=$serial_id ";
		$r = mysql_query($q,$dbh);
		if (mysql_num_rows($r)) {
			while(($row=mysql_fetch_object($r))) {
				$q = "replace into acces_res_1 select $row->num_notice, res_prf_num,usr_prf_num,res_rights,res_mask from acces_res_1 where res_num=$serial_id ";
				mysql_query($q,$dbh);
			}
		}
		
		//traitement des droits acces empr_notice
		if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
			$dom_2= $ac->setDomain(2);
			if ($serial_id) {	
				$dom_2->storeUserRights(1, $update_result, $res_prf, $chk_rights, $prf_rad, $r_rad);
			} else {
				$dom_2->storeUserRights(0, $update_result, $res_prf, $chk_rights, $prf_rad, $r_rad);
			}
		}
		
		//Traitement des liens
		$requete="delete from notices_relations where num_notice=".$update_result;
		mysql_query($requete);
		for ($i=0; $i<$max_rel; $i++) {
			$f_rel_id="f_rel_id_".$i;
			$f_rel_type="f_rel_type_".$i;
			$f_rel_rank="f_rel_rank_".$i;
			if ($$f_rel_id) {
				if(!$serial_id){				
					$requete_rank = "select count(rank) as rank_max from notices_relations where linked_notice='".$$f_rel_id."' and relation_type='".$$f_rel_type."'";
					$res = mysql_query($requete_rank);
					if(mysql_num_rows($res))
						$rang_max = mysql_result($res,0,0);
					else $rang_max = 0;	
					$requete="insert into notices_relations values($update_result,".$$f_rel_id.",'".$$f_rel_type."',".($rang_max ? $rang_max :$i).")";
					@mysql_query($requete);
				} else {
					$req_exist = "select 1 from notices_relations where linked_notice='".$$f_rel_id."' and relation_type='".$$f_rel_type."' and rank='".$$f_rel_rank."'";
					$res_exist = mysql_query($req_exist);
					if(mysql_num_rows($res_exist)){
						$requete_rank = "select count(rank) as rank_max from notices_relations where linked_notice='".$$f_rel_id."' and relation_type='".$$f_rel_type."'";
						$res = mysql_query($requete_rank);
						if(mysql_num_rows($res))
							$rang_max = mysql_result($res,0,0);
						else $rang_max = $$f_rel_rank;	
					} else $rang_max = $$f_rel_rank;				
					$requete="insert into notices_relations values($update_result,".$$f_rel_id.",'".$$f_rel_type."',".$rang_max.")";
					@mysql_query($requete);
				}				
			}
		}
		
		// traitement des auteurs
		$rqt_del = "DELETE FROM responsability WHERE responsability_notice='$update_result' ";
		$res_del = mysql_query($rqt_del, $dbh);
		$rqt_ins = "INSERT INTO responsability (responsability_author, responsability_notice, responsability_fonction, responsability_type, responsability_ordre) VALUES ";
		$i=0;
		while ($i<=count ($f_aut)-1) {
			$id_aut=$f_aut[$i]['id'];
			if ($id_aut) {
				$fonc_aut=$f_aut[$i]['fonction'];
				$type_aut = $f_aut[$i]['type'];
				$ordre_aut = $f_aut[$i]['ordre'];
				$rqt = $rqt_ins . " ('$id_aut','$update_result','$fonc_aut','$type_aut', $ordre_aut)";
				$res_ins = @mysql_query($rqt, $dbh);
			}
			$i++;
		}
	
		// traitement des categories
		$rqt_del = "DELETE FROM notices_categories WHERE notcateg_notice='$update_result' ";
		$res_del = mysql_query($rqt_del, $dbh);
		$rqt_ins = "INSERT INTO notices_categories (notcateg_notice, num_noeud, ordre_categorie) VALUES ";
		while (list ($key, $val) = each ($f_categ)) {
			$id_categ=$val['id'];
			if ($id_categ) {
				$ordre_categ = $val['ordre'];
				$rqt = $rqt_ins . " ('$update_result','$id_categ', $ordre_categ ) " ; 
				$res_ins = @mysql_query($rqt, $dbh);
			}
		}
	
		// traitement des langues
		// langues
		$f_lang_form = array();
		$f_langorg_form = array() ;
		for ($i=0; $i< $max_lang ; $i++) {
			$var_langcode = "f_lang_code$i" ;
			if ($$var_langcode) $f_lang_form[] =  array ('code' => $$var_langcode);
		}
	
		// langues originales
		for ($i=0; $i< $max_langorg ; $i++) {
			$var_langorgcode = "f_langorg_code$i" ;
			if ($$var_langorgcode) $f_langorg_form[] =  array ('code' => $$var_langorgcode);
		}
	
		$rqt_del = "delete from notices_langues where num_notice='$update_result' ";
		$res_del = mysql_query($rqt_del, $dbh);
		$rqt_ins = "insert into notices_langues (num_notice, type_langue, code_langue) VALUES ";
		while (list ($key, $val) = each ($f_lang_form)) {
			$tmpcode_langue=$val['code'];
			if ($tmpcode_langue) {
				$rqt = $rqt_ins . " ('$update_result',0, '$tmpcode_langue') " ; 
				$res_ins = mysql_query($rqt, $dbh);
			}
		}
		
		// traitement des langues originales
		$rqt_ins = "insert into notices_langues (num_notice, type_langue, code_langue) VALUES ";
		while (list ($key, $val) = each ($f_langorg_form)) {
			$tmpcode_langue=$val['code'];
			if ($tmpcode_langue) {
				$rqt = $rqt_ins . " ('$update_result',1, '$tmpcode_langue') " ; 
				$res_ins = @mysql_query($rqt, $dbh);
			}
		}
		
		//Traitement des champs perso
		$p_perso->rec_fields_perso($update_result);
	
		// Mise à jour de la table notices_global_index
		notice::majNoticesGlobalIndex($serial->serial_id);
		// Mise à jour de la table notices_mots_global_index
		notice::majNoticesMotsGlobalIndex($serial->serial_id);
		
		print "<div class='row'><div class='msg-perio'>".$msg[maj_encours]."</div></div>";
		$retour = "./catalog.php?categ=serials&sub=view&serial_id=".$serial->serial_id;
		print "
			<form class='form-$current_module' name=\"dummy\" method=\"post\" action=\"$retour\" style=\"display:none\">
				<input type=\"hidden\" name=\"id_form\" value=\"$id_form\">
			</form>
			<script type=\"text/javascript\">document.dummy.submit();</script>
			";
	} else {
		error_message($msg[4004] , $msg['catalog_serie_impossible'], 1, './catalog.php?categ=serials');
	}
	
}
?>
