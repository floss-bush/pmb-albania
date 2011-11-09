<?php
// +--------------------------------------------------------------------------+
// | PMB est sous licence GPL, la réutilisation du code est cadrée            |
// +--------------------------------------------------------------------------+
// $Id: print.php,v 1.44.2.2 2011-07-21 08:51:46 gueluneau Exp $

$base_path=".";
require_once($base_path."/includes/init.inc.php");
require_once("./includes/error_report.inc.php") ;
require_once("./includes/global_vars.inc.php");
require_once('./includes/opac_config.inc.php');
	
// récupération paramètres MySQL et connection á la base
require_once('./includes/opac_db_param.inc.php');
require_once('./includes/opac_mysql_connect.inc.php');
$dbh = connection_mysql();
// (si la connection est impossible, le script die ici).

require_once("./includes/misc.inc.php");

//Sessions !! Attention, ce doit être impérativement le premier include (à cause des cookies)
require_once("./includes/session.inc.php");
require_once('./includes/start.inc.php');
require_once('./includes/opac_config.inc.php');
require_once("./includes/check_session_time.inc.php");

// récupération localisation
require_once('./includes/localisation.inc.php');

// version actuelle de l'opac
require_once('./includes/opac_version.inc.php');

// fonctions de gestion de formulaire
require_once('./includes/javascript/form.inc.php');
require_once('./includes/templates/common.tpl.php');
require_once('./includes/divers.inc.php');
require_once('./includes/notice_categories.inc.php');

// classe de gestion des catégories
require_once($base_path.'/classes/categorie.class.php');
require_once($base_path.'/classes/notice.class.php');
require_once($base_path.'/classes/notice_display.class.php');

// classe indexation interne
require_once($base_path.'/classes/indexint.class.php');

// classe d'affichage des tags
require_once($base_path.'/classes/tags.class.php');

require_once($base_path."/includes/marc_tables/".$lang."/empty_words");

// pour l'affichage correct des notices
require_once($base_path."/includes/templates/common.tpl.php");
require_once($base_path."/includes/templates/notice.tpl.php");
require_once($base_path."/includes/navbar.inc.php");
require_once($base_path."/includes/notice_authors.inc.php");
require_once($base_path."/includes/notice_categories.inc.php");
require_once($base_path."/includes/explnum.inc.php");

require_once('./classes/notice_affichage.class.php');
require_once('./classes/notice_affichage_unimarc.class.php');
require_once('./classes/notice_affichage.ext.class.php');
require_once($base_path.'/classes/XMLlist.class.php');
require_once("./classes/notice_tpl_gen.class.php");

require_once($include_path."/mail.inc.php") ;


if (file_exists($include_path.'/print/print_options_subst.xml')){
	$xml_print = new XMLlist($include_path.'/print/print_options_subst.xml');
} else {
	$xml_print = new XMLlist($include_path.'/print/print_options.xml');
}
$xml_print->analyser();
$print_options = $xml_print->table;

if (($action=="print_$lvl")&&($output=="tt")) {
	header("Content-Type: application/word");
	header("Content-Disposition: attachement; filename=liste.doc");
}
$output_final = "<html><head><title>".$msg["print_title"]."</title>" .
			 	'<meta http-equiv=Content-Type content="text/html; charset='.$charset.'" />'.
				"</head><body>" ;

if ($action!="print_$lvl") {
	$output_final .= "<link rel=\"stylesheet\" href=\"./styles/".$css."/$css.css\" />";
	$output_final .= "<h3>".$msg["print_options"]."</h3>";
	$output_final .= "<form name='print_options' id='print_options' action='print.php?lvl=$lvl&action=print_$lvl' method='post'>";
	if($id_liste) $output_final .= "<input type='hidden' name='id_liste' value='$id_liste'>";
	
	 if(!$id_liste){
		 $script_selnoti = "
			 <script type='text/javascript'>
				function getSelectedNotice(){	
					 if(document.getElementById('selected').checked){
						var notices = opener.document.forms['cart_form'].elements;
						var hasSelected = false;
						var items='';
						for (var i = 0; i < notices.length; i++) { 
						 	if(notices[i].checked) {
						 		if(hasSelected) 
						 			items += ','+notices[i].value;
						 		else items += notices[i].value;
								hasSelected = true;	
							}
						}
						if(!hasSelected) {
							alert('".$msg[list_lecture_no_ck]."');
							return false;	
						} else {
							document.getElementById('select_noti').value = items;
							return true;
						}
					}
					return true;
				}
			</script>";
	 } else {
	 	 $script_selnoti = "
			 <script type='text/javascript'>
				function getSelectedNotice(){	
					 if(document.getElementById('selected').checked){
						var notices = opener.document.getElementsByName('notice[]');
						var hasSelected = false;
						var items='';
						for (var i = 0; i < notices.length; i++) { 
						 	if(notices[i].checked) {
						 		if(hasSelected) 
						 			items += ','+notices[i].value;
						 		else items += notices[i].value;
								hasSelected = true;	
							}
						}
						if(!hasSelected) {
							alert('".$msg[list_lecture_no_ck]."');
							return false;	
						} else {
							document.getElementById('select_noti').value = items;
							return true;
						}
					}
					return true;
				}
			</script>";
	 }
	 
 	$onchange="
		var div_sel=document.getElementById('sel_notice_tpl');
		var div_sel2=document.getElementById('sel_notice_tpl2');
		var notice_tpl=document.getElementById('notice_tpl');
		var sel=notice_tpl.options[notice_tpl.selectedIndex].value;
	    if(sel>0){
	    	div_sel.style.display='none';
	    	div_sel2.style.display='none';
	    }else { 
	    	div_sel.style.display='block';
	    	div_sel2.style.display='block';
	    }		    
	";
	$sel_notice_tpl=notice_tpl_gen::gen_tpl_select("notice_tpl",0,$onchange);
	 
	$output_final .= $script_selnoti."
	<b>".$msg["print_select_record"]."</b>
	<input type='hidden' name='select_noti' id='select_noti' />
	<blockquote>
		<input type='radio' name='number' value='0' id='all' ".($print_options['all'] ? ' checked ' : '')."/><label for='all'>&nbsp;".$msg["print_all_records"]."</label><br />
		<input type='radio' name='number' value='1' id='selected' ".($print_options['selected'] ? ' checked ' : '')."/><label for='selected'>&nbsp;".$msg["print_selected_records"]."</label>
	</blockquote>";
	$output_final .= "<b>".$msg["print_type_title"]."</b>
	<blockquote>
		$sel_notice_tpl
		<div id='sel_notice_tpl'>
			<input type='radio' name='type' value='ISBD' id='isbd' ".($print_options['isbd'] ? ' checked ' : '')."/><label for='isbd'>&nbsp;".$msg["print_type_isbd"]."</label><br />
			<input type='radio' name='type' value='PUBLIC' id='public' ".($print_options['public'] ? ' checked ' : '')."/><label for='public'>&nbsp;".$msg["print_type_public"]."</label>
		</div>
	</blockquote>
	<div id='sel_notice_tpl2' >
		<div id='print_format'>
			<b>".$msg["print_format_title"]."</b>
			<blockquote>
				<input type='radio' name='short' id='s1' value='1' ".($print_options['s1'] ? ' checked ' : '')."/><label for='s1'>&nbsp;".$msg["print_short_format"]."</label><br />
				<input type='radio' name='short' id='s0' value='0'".($print_options['s0'] ? ' checked ' : '')."/><label for='s0'>&nbsp;".$msg["print_long_format"]."</label><br />
				<input type='checkbox' name='header' id='header' value='1' ".($print_options['header'] ? ' checked ' : '')."/>&nbsp;<label for='header'>".$msg["print_header"]."</label>
			</blockquote>
		</div>
		<b>".$msg["print_ex_title"]."</b>
		<blockquote>";
		if ($opac_print_expl_default) {
			$checkprintexpl="checked";
			$checknoprintexpl="";
		} else {
			$checkprintexpl="";
			$checknoprintexpl="checked";
		}
		$output_final .= "
			<input type='radio' name='ex' id='ex1' value='1' $checkprintexpl /><label for='ex1'>&nbsp;".$msg["print_ex"]."</label><br />
			<input type='radio' name='ex' id='ex0' value='0' $checknoprintexpl /><label for='ex0'>&nbsp;".$msg["print_no_ex"]."</label>
		</blockquote>
	</div>
	<b>".$msg["print_output_title"]."</b>
	<blockquote>
		<input type='radio' name='output' id='outp' value='printer' ".($print_options['outp'] ? ' checked ' : '')."/><label for='outp'>&nbsp;".$msg["print_output_printer"]."</label><br />
		<input type='radio' name='output' id='outt' value='tt' ".($print_options['outt'] ? ' checked ' : '')." /><label for='outt'>&nbsp;".$msg["print_output_writer"]."</label><br />
		<input type='radio' name='output' id='oute' value='email' ".($print_options['oute'] ? ' checked ' : '')."/><label for='oute'>&nbsp;".$msg["print_email"]."</label><br />
			&nbsp;&nbsp;
		".$msg["print_emaildest"]."&nbsp;<input type='text' size='30' name='emaildest' value='' /><br />
		&nbsp;&nbsp;&nbsp;".$msg["print_emailcontent"]."&nbsp;<textarea rows='4' cols='40' name='emailcontent' value=''></textarea><br />
	</blockquote>
	<center>
	<input type='submit' value='".$msg["print_print"]."' class='bouton' onClick='return getSelectedNotice();' />&nbsp;
	<input type='button' value='".$msg["print_cancel"]."' class='bouton' onClick='self.close();'/>
	</center>
	";
	$output_final .= "</form>"; 
} else {
	//print "<link rel=\"stylesheet\" href=\"./styles/".$css."/print.css\" />";
	
		$output_final .= "<style type='text/css'>
			BODY { 	
				font-size: 10pt;
				font-family: verdana, geneva, helvetica, arial;
				color:#000000;
				}
			td {
				font-size: 10pt;
				font-family: verdana, geneva, helvetica, arial;
				color:#000000;
			}
			th {
				font-size: 10pt;
				font-family: verdana, geneva, helvetica, arial;
				font-weight:bold;
				color:#000000;
				background:#DDDDDD;
				text-align:left;
			}
			hr {
				border:none;
				border-bottom:1px solid #000000;
			}
			h3 {
				font-size: 12pt;
			}
			</style>";
	if($notice_tpl)$noti_tpl=new notice_tpl_gen($notice_tpl);
	
	if($action == 'print_cart'){
		if($number && $select_noti){
			$cart_ = explode(",",$select_noti);
		} else $cart_=$_SESSION["cart"];
		$date_today = formatdate(today()) ;
		if ($output=="email") {
			//on rajoute une mention spécifiant l'origine du mail...
			$rqt = "select empr_nom, empr_prenom from empr where id_empr ='".$_SESSION['id_empr_session']."'";
			$res = mysql_query($rqt);
			if(mysql_num_rows($res)){
				$info = mysql_fetch_object($res);		
				$output_final .= "<h3>".$msg['biblio_send_by']." ".$info->empr_nom." ".$info->empr_prenom."</h3>" ;
			}
		}
		$output_final .= "<h3>".$date_today."&nbsp;".sprintf($msg["show_cart_n_notices"],count($cart_))."</h3><hr style='border:none; border-bottom:solid #000000 3px;'/>";
		//print "<table width='100%'>";
		for ($i=0; $i<count($cart_); $i++) {
			if($noti_tpl) {
				$output_final.=$noti_tpl->build_notice(substr($cart_[$i],0,2)!="es"?$cart_[$i]:substr($cart_[$i],2));		
				$output_final.="<hr />";
			} else{
				if (substr($cart_[$i],0,2)!="es") {
					if (!$opac_notice_affichage_class) $opac_notice_affichage_class="notice_affichage";
				} else $opac_notice_affichage_class="notice_affichage_unimarc";
				$current = new $opac_notice_affichage_class((substr($cart_[$i],0,2)!="es"?$cart_[$i]:substr($cart_[$i],2)),array(),0,1);
				$current->do_header();
				if ($type=='PUBLIC') $current->do_public($short,$ex); else $current->do_isbd($short,$ex);
				if ($header) $output_final .= "<h3>".$current->notice_header."</h3>";
				if ($current->notice->niveau_biblio =='s') {
					$perio="<span class='fond-mere'>[".$msg['isbd_type_perio'].$bulletins."]</span>&nbsp;";
				} elseif ($current->notice->niveau_biblio =='a') {
					$perio="<span class='fond-article'>[".$msg['isbd_type_art']."]</span>&nbsp;"; 
				} else $perio="";
				if ($type=='PUBLIC') $output_final .= $perio.$current->notice_public; else $output_final .= $perio.$current->notice_isbd;
				if ($ex) $output_final .= $current->affichage_expl ;
				$output_final .= "<hr /> ";
			}	
		}
		if ($charset!='utf-8') $output_final=cp1252Toiso88591($output_final);
	} elseif($action='print_list'){
		
		if($number && $select_noti){
			$notices = explode(",",$select_noti);
		} else {
			$rqt = "select * from opac_liste_lecture where id_liste='$id_liste'";
			$res = mysql_query($rqt);				
			$liste=mysql_fetch_object($res);
			$nom_liste = $liste->nom_liste;
			$description = $liste->description;
			$notices=explode(',',$liste->notices_associees);
		}
			
		$date_today = formatdate(today()) ;	
		if ($output=="email") {
			//on rajoute une mention spécifiant l'origine du mail...
			$rqt = "select empr_nom, empr_prenom from empr where id_empr ='".$_SESSION['id_empr_session']."'";
			$res = mysql_query($rqt);
			if(mysql_num_rows($res)){
				$info = mysql_fetch_object($res);		
				$output_final .= "<h3>".$msg['biblio_send_by']." ".$info->empr_nom." ".$info->empr_prenom."</h3>" ;
			}
		}
		$output_final .= "<h3>".$date_today."&nbsp;".sprintf($msg["show_cart_n_notices"],count($notices))."</h3><hr style='border:none; border-bottom:solid #000000 3px;'/>";
		for ($i=0; $i<count($notices); $i++) {
			if($noti_tpl) {
				$output_final.=$noti_tpl->build_notice(substr($cart_[$i],0,2)!="es"?$cart_[$i]:substr($cart_[$i],2));
				$output_final.="<hr />";
			} else{				
				if (substr($notices[$i],0,2)!="es") {
					if (!$opac_notice_affichage_class) $opac_notice_affichage_class="notice_affichage";
				} else $opac_notice_affichage_class="notice_affichage_unimarc";
				$current = new $opac_notice_affichage_class((substr($notices[$i],0,2)!="es"?$notices[$i]:substr($notices[$i],2)),array(),0,1);
				$current->do_header();
				if ($type=='PUBLIC') $current->do_public($short,$ex); else $current->do_isbd($short,$ex);
				if ($header) $output_final .= "<h3>".$current->notice_header."</h3>";
				if ($current->notice->niveau_biblio =='s') {
					$perio="<span class='fond-mere'>[".$msg['isbd_type_perio'].$bulletins."]</span>&nbsp;";
				} elseif ($current->notice->niveau_biblio =='a') {
					$perio="<span class='fond-article'>[".$msg['isbd_type_art']."]</span>&nbsp;"; 
				} else $perio="";
				if ($type=='PUBLIC') $output_final .= $perio.$current->notice_public; else $output_final .= $perio.$current->notice_isbd;
				if ($ex) $output_final .= $current->affichage_expl ;
				$output_final .= "<hr /> ";
			}	
			if ($charset!='utf-8') $output_final=cp1252Toiso88591($output_final);
		}	
	}
	//print "</table>";
	if ($output=="printer") $output_final .= "<script>self.print();</script>";
	
}

if ($output!="email") 
	print pmb_bidi($output_final."</body></html>") ;
else {
	$headers  = "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/html; charset=".$charset."\n";
	$res_envoi=mailpmb("", $emaildest,$msg["print_emailobj"]." $opac_biblio_name - $date_today ",($emailcontent ? $msg["print_emailcontent"].$emailcontent."<br />" : '').$output_final."<br /><br />".mail_bloc_adresse()."</body></html> ",$opac_biblio_name, $opac_biblio_email, $headers);
	if ($res_envoi) 
		print "<html><head><title>".$msg["print_title"]."</title></head><body><link rel=\"stylesheet\" href=\"./styles/".$css."/$css.css\" />\n<br /><br /><center><h3>".sprintf($msg["print_emailsucceed"],$emaildest)."</h3><br />
		<a href=\"\" onClick=\"self.close(); return false;\">".$msg["print_emailclose"]."</a></center></body></html>" ;
	else 
		echo "<html><head><title>".$msg["print_title"]."</title></head><body><link rel=\"stylesheet\" href=\"./styles/".$css."/$css.css\" />\n<br /><br /><center><h3>".sprintf($msg["print_emailfailed"],$emaildest)."</h3><br />
		<a href=\"\" onClick=\"self.close(); return false;\">".$msg["print_emailclose"]."</a></center></body></html>" ;
}		

global $pmb_logs_activate;
if($pmb_logs_activate){
	global $log, $infos_notice, $infos_expl;

	$rqt= " select empr_prof,empr_cp, empr_ville as ville, empr_year, empr_sexe,  empr_date_adhesion, empr_date_expiration, count(pret_idexpl) as nbprets, count(resa.id_resa) as nbresa, code.libelle as codestat, es.statut_libelle as statut, categ.libelle as categ, gr.libelle_groupe as groupe,dl.location_libelle as location 
			from empr e
			left join empr_codestat code on code.idcode=e.empr_codestat
			left join empr_statut es on e.empr_statut=es.idstatut
			left join empr_categ categ on categ.id_categ_empr=e.empr_categ
			left join empr_groupe eg on eg.empr_id=e.id_empr
			left join groupe gr on eg.groupe_id=gr.id_groupe
			left join docs_location dl on e.empr_location=dl.idlocation
			left join resa on e.id_empr=resa_idempr
			left join pret on e.id_empr=pret_idempr
			where e.empr_login='".addslashes($login)."'
			group by resa_idempr, pret_idempr";
	$res=mysql_query($rqt);
	if($res){
		$empr_carac = mysql_fetch_array($res);
		$log->add_log('empr',$empr_carac);
	}
	$log->add_log('num_session',session_id());
	$log->add_log('expl',$infos_expl);
	$log->add_log('docs',$infos_notice);
	$log->save();
}
