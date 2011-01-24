<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: bulletin_display.inc.php,v 1.41 2010-11-04 15:19:06 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// error_reporting (E_ALL);             
// largeur du tableau notice en pixels
$libelle = $msg[270];
$largeur = 500;		

require_once($base_path.'/includes/explnum.inc.php');
require_once($base_path.'/classes/notice_affichage.class.php');
require_once($include_path."/resa_func.inc.php"); 
require_once($base_path.'/classes/notice.class.php');

$requete = "SELECT bulletin_id, bulletin_numero, bulletin_notice, mention_date, date_date, bulletin_titre, bulletin_cb, date_format(date_date, '".$msg["format_date_sql"]."') as aff_date_date,num_notice FROM bulletins WHERE bulletin_id='$id'";

$res = @mysql_query($requete, $dbh);
while(($obj=mysql_fetch_array($res))) {
	//on cherches des documents numériques
	$req = "select explnum_id from explnum where explnum_bulletin = ".$obj["bulletin_id"];
	$resultat = mysql_query($req, $dbh) or die ($req." ".mysql_error());
	$nb_ex = mysql_num_rows($resultat);
	//on met le nécessaire pour la visionneuse
	if($opac_visionneuse_allow && $nb_ex){
		//print "&nbsp;&nbsp;&nbsp;".$link_to_visionneuse;
		print "
		<script type='text/javascript'>
			function sendToVisionneuse(explnum_id){
				document.getElementById('visionneuseIframe').src = 'visionneuse.php?mode=perio_bulletin&idperio=".$obj['bulletin_notice']."'+(typeof(explnum_id) != 'undefined' ? '&explnum_id='+explnum_id+\"\" : '\'');
			}
		</script>";
	}
	$requete3 = "SELECT notice_id FROM notices WHERE notice_id='".$obj["bulletin_notice"]."' ";
	$res3 = @mysql_query($requete3, $dbh);
	while(($obj3=mysql_fetch_object($res3))) {
		$notice3 = new notice($obj3->notice_id);		
	}
	$notice3->fetch_visibilite();
	
	//carrousel pour la navigation
	if($opac_show_bulletin_nav)
		$res_print = do_carroussel($obj);
	else $res_print="";
	
	$res_print .= "<h3><img src=./images/icon_per.gif> ".$notice3->print_resume(1,$css)."."." <b>".$obj["bulletin_numero"]."</b>".($nb_ex ? "&nbsp;<a href='#docnum'>".($nb_ex > 1 ? "<img src='./images/globe_rouge.png' />" : "<img src='./images/globe_orange.png' />")."</a>" : "")."</h3>\n";
	
	$num_notice=$obj['num_notice'];
	if ($obj['bulletin_titre']) {
		$res_print .=  htmlentities($obj['bulletin_titre'],ENT_QUOTES, $charset)."<br />";
	} 
	if ($obj['mention_date']) $res_print .= $msg['bull_mention_date']." &nbsp;".$obj['mention_date']."\n"; 
	if ($obj['date_date']) $res_print .= "<br />".$msg['bull_date_date']." &nbsp;".$obj['aff_date_date']." \n";     
	if ($obj['bulletin_cb']) {
		$res_print .= "<br />".$msg["code_start"]." ".htmlentities($obj['bulletin_cb'],ENT_QUOTES, $charset)."\n";
		$code_cb_bulletin = $obj['bulletin_cb'];
	} 
}


do_image(&$res_print, $code_cb_bulletin, 0 ) ;
if ($num_notice) {
	// Il y a une notice de bulletin
	print $res_print ;	
	$opac_notices_depliable = 0;
	$seule=1;
	print pmb_bidi(aff_notice($num_notice,0,0)) ;	
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
	
	print $res_print ;	
	print $depouill ;
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
				print pmb_bidi($ret_resa) ;
			}
		}
		
		if ($opac_show_exemplaires) {
			if($fonction=='notice_affichage_custom_bretagne')	print pmb_bidi(notice_affichage_custom_bretagne::expl_list("m",0,$id));
			else print pmb_bidi(notice_affichage::expl_list("m",0,$id));
		}
	}
	if ($notice3->visu_explnum && (!$notice3->visu_explnum_abon || ($notice3->visu_explnum_abon && $_SESSION["user_code"]))) { 
		if (($explnum = show_explnum_per_notice(0, $id, ''))) print pmb_bidi("<a name='docnum'><h3>".$msg["explnum"]."</h3></a>".$explnum);
	}	
}
mysql_free_result($res);

print notice_affichage::autres_lectures (0,$id);

function do_carroussel($bull){
	global $msg;
	//on commence par récupérer la liste des 3 bulletins précédents et suivants du courant...
	$req = "(select bulletin_id, bulletin_numero, bulletin_notice, mention_date, date_date, bulletin_titre, bulletin_cb, date_format(date_date, '".$msg["format_date_sql"]."') as aff_date_date,num_notice from bulletins where bulletin_notice=".$bull['bulletin_notice']." and date_date < '".$bull['date_date']."' order by date_date desc limit 0,3) UNION ";
	$req .= "(select bulletin_id, bulletin_numero, bulletin_notice, mention_date, date_date, bulletin_titre, bulletin_cb, date_format(date_date, '".$msg["format_date_sql"]."') as aff_date_date,num_notice from bulletins where bulletin_notice=".$bull['bulletin_notice']." and date_date >= '".$bull['date_date']."' order by date_date asc limit 0,4)";
	$res_caroussel = mysql_query($req);
	if(mysql_num_rows($res_caroussel)){
		$prev = true;
		$current = $previous = $next = array();
		while (($bullForNav=mysql_fetch_array($res_caroussel))) {
			if($bullForNav['bulletin_id'] == $bull['bulletin_id']){
				$prev = false;
				$current = $bullForNav;
			}else{
				if($prev == true){
					$previous[] = $bullForNav;	
				}else{
					$next[] = $bullForNav;
				}
			}
		}
		$carroussel = "
			<table class='carroussel_bulletin' style=''>
				<tr>";
				
		$taille = 100;
		//on a des bulletins précédent
		if (sizeof($previous)>0){
			$taille =$taille - 4;
		}
		//on a des bulletins suivant
		if(sizeof($next)>0){
			$taille =$taille - 4;
		}
			
		
		//ceux d'avant
		//on égalise  : 3 de chaque coté
		if(sizeof($previous)>0)$carroussel .= "<td style='width:4%;'><a href='index.php?lvl=bulletin_display&id=".$previous[0]['bulletin_id']."'><img align='middle' src='images/previous1.png'/></a></td>";
		for($i=0 ; $i<(3-sizeof($previous)) ; $i++){
			$carroussel .="<td style='width:".($taille/((3*2)+1))."%;'>&nbsp;</td>";
		}
		if(sizeof($previous)>0){
			for($i=sizeof($previous)-1 ; $i>=0 ; $i--){
				$carroussel .="<td class='active' style='width:".($taille/((3*2)+1))."%;'><a href='index.php?lvl=bulletin_display&id=".$previous[$i]['bulletin_id']."'>".$previous[$i]['bulletin_numero'].($previous[$i]['bulletin_titre'] ? " - ".$previous[$i]['bulletin_titre'] : "")."<br />".($previous[$i]['mention_date'] ? $previous[$i]['mention_date'] :$previous[$i]['aff_date_date'] )."</a></td>";
			}
		}
		//le bull courant en évidence
		$carroussel .="<td class='current_bull_carroussel' style='width:".($taille/((3*2)+1))."%;'><a href='index.php?lvl=bulletin_display&id=".$current['bulletin_id']."'>".$current['bulletin_numero'].($current['bulletin_titre'] ? " - ".$current['bulletin_titre'] : "")."<br />".($current['mention_date'] ? $current['mention_date'] :$current['aff_date_date'] )."</a></td>";
		//la suite
		if(sizeof($next)>0){
			for($i=0 ; $i<sizeof($next) ; $i++){
				$carroussel .="<td class='active' style='width:".($taille/((3*2)+1))."%;'><a href='index.php?lvl=bulletin_display&id=".$next[$i]['bulletin_id']."'>".$next[$i]['bulletin_numero'].($next[$i]['bulletin_titre'] ? " - ".$next[$i]['bulletin_titre'] : "")."<br />".($next[$i]['mention_date'] ? $next[$i]['mention_date'] :$next[$i]['aff_date_date'] )."</a></td>";
			}
		}
		//on égalise  : 3 de chaque coté
		for($i=0 ; $i<(3-sizeof($next)) ; $i++){
			$carroussel .="<td style='width:".($taille/((3*2)+1))."%;'>&nbsp;</td>";
		}
		if(sizeof($next)>0)$carroussel .= "<td style='width:4%;'><a href='index.php?lvl=bulletin_display&id=".$next[0]['bulletin_id']."'><img align='middle' src='images/next1.png'/></a></td>";
		//on ferme le tout
			$carroussel .= "
				</tr>
			</table>";
	}
	
	return $carroussel;
}