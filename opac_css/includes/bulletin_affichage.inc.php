<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: bulletin_affichage.inc.php,v 1.16 2010-10-11 08:26:16 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($base_path.'/includes/explnum.inc.php');
require_once($base_path.'/classes/notice_affichage.class.php');
require_once($base_path.'/classes/notice.class.php');

function bulletin_affichage_reduit($id, $no_link=0) {
	global $msg, $dbh, $css, $charset, $opac_show_exemplaires ;
	$requete = "SELECT bulletin_id, bulletin_numero, bulletin_notice, mention_date, date_date, date_format(date_date, '".$msg["format_date_sql"]."') as aff_date_date FROM bulletins WHERE bulletin_id='$id'";
	$res3 = mysql_query($requete, $dbh) or die(mysql_error()) ;
	$obj = mysql_fetch_object($res3) ;
	$notice3 = new notice($obj->bulletin_notice);

	$res_print = "<h3><img src=./images/icon_per.gif>" ;
	if ($no_link) $res_print .= $notice3->tit1 ;
		else $res_print .= $notice3->print_resume(1,$css) ;
	$res_print .= ". <b>".$obj->bulletin_numero."</b></h3>\n";
	
	if ($obj->bulletin_titre) {
		$res_print .=  htmlentities($obj->bulletin_titre,ENT_QUOTES, $charset)."\n";
		$suite_aff = " : " ; 
	} else $suite_aff = "" ;
	if ($obj->mention_date) $res_print .=  $suite_aff.$msg['bull_mention_date'].$obj->mention_date."\n"; 
	if ($obj->date_date) $res_print .= "<br />".$msg['bull_date_date']." ".$obj->aff_date_date." \n";     
	if ($obj->bulletin_cb) {
		$res_print .= "<br />".$msg[code_start]." ".htmlentities($obj->bulletin_cb,ENT_QUOTES, $charset)."\n";
		$code_cb_bulletin = $obj->bulletin_cb;
	}

	do_image(&$res_print, $code_cb_bulletin, 0 ) ;
	
	if ($opac_show_exemplaires) $res_print .= pmb_bidi(notice_affichage::expl_list("m",0,$obj->bulletin_id));
	
	return $res_print ;

}

function bulletin_affichage($id,$type=""){

	global $dbh, $msg;
	
	$display ="";
	$requete = "SELECT bulletin_id, bulletin_numero, bulletin_notice, mention_date, date_date, bulletin_titre, bulletin_cb, date_format(date_date, '".$msg["format_date_sql"]."') as aff_date_date,num_notice FROM bulletins WHERE bulletin_id='$id'";
	$res = @mysql_query($requete, $dbh);
	while(($obj=mysql_fetch_array($res))) {
		$requete3 = "SELECT notice_id FROM notices WHERE notice_id='".$obj["bulletin_notice"]."' ";		
		$res3 = @mysql_query($requete3, $dbh);
		while(($obj3=mysql_fetch_object($res3))) {
			$notice3 = new notice($obj3->notice_id);		
		}
		$notice3->fetch_visibilite();
		//on vient poser l'ancre des docnums...
		$req = "select explnum_id from explnum where explnum_bulletin = ".$obj["bulletin_id"];
		$resultat = mysql_query($req, $dbh) or die ($req." ".mysql_error());
		$nb_ex = mysql_num_rows($resultat);
		$res_print = "<h3><img src=./images/icon_per.gif> ".$notice3->print_resume(1,$css)."."." <b>".$obj["bulletin_numero"]."</b>".($nb_ex ? "&nbsp;<a href='#docnum'>".($nb_ex > 1 ? "<img src='./images/globe_rouge.png' />" : "<img src='./images/globe_orange.png' />")."</a>" : "")."</h3>\n";
		$num_notice=$obj['num_notice'];
		if ($obj['bulletin_titre']) {
			$res_print .=  htmlentities($obj['bulletin_titre'],ENT_QUOTES, $charset)."<br />";
		} 
		if ($obj['mention_date']) $res_print .= $msg['bull_mention_date']." &nbsp;".$obj['mention_date']."\n"; 
		if ($obj['date_date']) $res_print .= "<br />".$msg['bull_date_date']." &nbsp;".$obj['aff_date_date']." \n";     
		if($type != "visionneuse" && $nb_ex) $res_print .= "<br /><a href='#docnum'>".($nb_ex > 1 ? "<img src='./images/globe_rouge.png' />" : "<img src='./images/globe_orange.png' />")."</a>";
		if ($obj['bulletin_cb']) {
			$res_print .= "<br />".$msg["code_start"]." ".htmlentities($obj['bulletin_cb'],ENT_QUOTES, $charset)."\n";
			$code_cb_bulletin = $obj['bulletin_cb'];
		}  	
	}
	
	do_image(&$res_print, $code_cb_bulletin, 0 ) ;
	if ($num_notice) {
		// Il y a une notice de bulletin
		$display .= $res_print ;	
		$opac_notices_depliable = 0;
		$seule=1;
		//$display .= pmb_bidi(aff_notice($num_notice,0,0)) ;
		if($type == "visionneuse") $display .= pmb_bidi(aff_notice($num_notice,1,1,0,"",0,1));
		else $display .= pmb_bidi(aff_notice($num_notice,0,1,0,"",0));
	} else {
		// construction des dépouillements
		$depouill= "<br /><h3>".$msg['bull_dep']."</h3>";
		$requete = "SELECT * FROM analysis, notices, notice_statut WHERE analysis_bulletin='$id' AND notice_id = analysis_notice AND statut = id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").") "; 
		$res = @mysql_query($requete, $dbh);
		if (mysql_num_rows($res)) {
			if ($opac_notices_depliable) $depouill .= $begin_result_liste;
			if ($opac_cart_allow) $depouill.="<a href=\"cart_info.php?id=".$id."&lvl=analysis&header=".rawurlencode(strip_tags($notice_header))."\" target=\"cart_info\" class=\"img_basket\">".$msg["cart_add_result_in"]."</a>"; 		
			$depouill.= "<blockquote>";
			while(($obj=mysql_fetch_array($res))) {
				$depouill.= pmb_bidi(aff_notice($obj["analysis_notice"]));
			}
			$depouill.= "</blockquote>";
		} else $depouill = $msg["no_analysis"];
		
		$display .= $res_print ;	
		$display .= $depouill ;
		if ($notice3->visu_expl && (!$notice3->visu_expl_abon || ($notice3->visu_expl_abon && $_SESSION["user_code"])))	{	
			if (!$opac_resa_planning) {
				$resa_check=check_statut(0,$id) ;
				if ($resa_check) {
					$requete_resa = "SELECT count(1) FROM resa WHERE resa_idbulletin='$id'";
					$nb_resa_encours = mysql_result(mysql_query($requete_resa,$dbh), 0, 0) ;
					if ($nb_resa_encours) $message_nbresa = str_replace("!!nbresa!!", $nb_resa_encours, $msg["resa_nb_deja_resa"]) ;
				
					if (($_SESSION["user_code"] && $allow_book) && $opac_resa && !$popup_resa) {
						$ret_resa .= "<h3>".$msg["bulletin_display_resa"]."</h3>";
						if ($opac_max_resa==0 || $opac_max_resa>$nb_resa_encours) {
							if ($opac_resa_popup) $ret_resa .= "<a href='#' onClick=\"if(confirm('".$msg["confirm_resa"]."')){w=window.open('./do_resa.php?lvl=resa&id_bulletin=".$id."&oresa=popup','doresa','scrollbars=yes,width=500,height=600,menubar=0,resizable=yes'); w.focus(); return false;}else return false;\" id=\"bt_resa\">".$msg["bulletin_display_place_resa"]."</a>" ;
							else $ret_resa .= "<a href='./do_resa.php?lvl=resa&id_bulletin=".$id."&oresa=popup' onClick=\"return confirm('".$msg["confirm_resa"]."')\" id=\"bt_resa\">".$msg["bulletin_display_place_resa"]."</a>" ;
							$ret_resa .= $message_nbresa ;
						} else $ret_resa .= str_replace("!!nb_max_resa!!", $opac_max_resa, $msg["resa_nb_max_resa"]) ; 
						$ret_resa.= "<br />";
					} elseif (!($_SESSION["user_code"]) && $opac_resa && !$popup_resa) {
						// utilisateur pas connecté
						// préparation lien réservation sans être connecté
						$ret_resa .= "<h3>".$msg["bulletin_display_resa"]."</h3>";
						if ($opac_resa_popup) $ret_resa .= "<a href='#' onClick=\"if(confirm('".$msg["confirm_resa"]."')){w=window.open('./do_resa.php?lvl=resa&id_bulletin=".$id."&oresa=popup','doresa','scrollbars=yes,width=500,height=600,menubar=0,resizable=yes'); w.focus(); return false;}else return false;\" id=\"bt_resa\">".$msg["bulletin_display_place_resa"]."</a>" ;
						else $ret_resa .= "<a href='./do_resa.php?lvl=resa&id_bulletin=".$id."&oresa=popup' onClick=\"return confirm('".$msg["confirm_resa"]."')\" id=\"bt_resa\">".$msg["bulletin_display_place_resa"]."</a>" ;
						$ret_resa .= $message_nbresa ;
						$ret_resa .= "<br />";
					} elseif ($fonction=='notice_affichage_custom_bretagne') {
						if ($opac_resa_popup) $reserver = "<a href='#' onClick=\"if(confirm('".$msg["confirm_resa"]."')){w=window.open('./do_resa.php?lvl=resa&id_notice=".$this->notice_id."&oresa=popup','doresa','scrollbars=yes,width=500,height=600,menubar=0,resizable=yes'); w.focus(); return false;}else return false;\" id=\"bt_resa\">".$msg["bulletin_display_place_resa"]."</a>" ;
						else $reserver = "<a href='./do_resa.php?lvl=resa&id_notice=".$this->notice_id."&oresa=popup' onClick=\"return confirm('".$msg["confirm_resa"]."')\" id=\"bt_resa\">".$msg["bulletin_display_place_resa"]."</a>" ;
						$reservernbre = $message_nbresa ;
					} else $ret_resa = ""; 
					$display .= pmb_bidi($ret_resa) ;
				}
			}
			
			if ($opac_show_exemplaires) {
				if($fonction=='notice_affichage_custom_bretagne')	
					$display .= pmb_bidi(notice_affichage_custom_bretagne::expl_list("m",0,$id));
				else 
					$display .= pmb_bidi(notice_affichage::expl_list("m",0,$id));
			}
		}
		if ($type != "visionneuse" && $notice3->visu_explnum && (!$notice3->visu_explnum_abon || ($notice3->visu_explnum_abon && $_SESSION["user_code"]))) { 
			if (($explnum = show_explnum_per_notice(0, $id, ''))) 
				$display .=  pmb_bidi("<a name='docnum'><h3>".$msg["explnum"]."</h3></a>".$explnum);
		}	
	}
	mysql_free_result($res);
	
	$display .=  notice_affichage::autres_lectures (0,$id) ;	
	
	return $display;
}