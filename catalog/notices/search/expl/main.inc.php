<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.10 2009-12-16 15:45:49 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/search.class.php");
require_once($class_path."/mono_display_expl.class.php");
require_once($class_path."/acces.class.php");

$sc=new search(true,"search_fields_expl");

$sc->link = './catalog.php?categ=isbd&id=!!id!!';
$sc->link_expl = './catalog.php?categ=edit_expl&id=!!notice_id!!&cb=!!expl_cb!!&expl_id=!!expl_id!!'; 
$sc->link_explnum = './catalog.php?categ=edit_explnum&id=!!notice_id!!&explnum_id=!!explnum_id!!';
$sc->link_serial = './catalog.php?categ=serials&sub=view&serial_id=!!id!!';
$sc->link_analysis = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!bul_id!!&art_to_show=!!id!!';
$sc->link_bulletin = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!id!!';
$sc->link_explnum_serial = "./catalog.php?categ=serials&sub=analysis&action=explnum_form&bul_id=!!bul_id!!&analysis_id=!!analysis_id!!&explnum_id=!!explnum_id!!";

switch ($sub) {
	case "launch":
		if ((string)$page=="") {
			$_SESSION["CURRENT"]=count($_SESSION["session_history"]);
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["URI"]="./catalog.php?categ=search&mode=8";
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["POST"]=$_POST;
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["GET"]=$_GET;
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["GET"]["sub"]="";
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["POST"]["sub"]="";
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_QUERY"]=$sc->make_human_query();
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["HUMAN_TITLE"]=$msg["search_exemplaire"];
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["SEARCH_TYPE"]="EXPL";
			$_POST["page"]=0;
			$page=0;
		}
		
		$table=$sc->get_results("./catalog.php?categ=search&mode=8&sub=launch","./catalog.php?categ=search&mode=8&option_show_notice_fille=$option_show_notice_fille&option_show_expl=$option_show_expl",true);
		print_results($sc,$table,"./catalog.php?categ=search&mode=8&sub=launch&option_show_notice_fille=$option_show_notice_fille&option_show_expl=$option_show_expl","./catalog.php?categ=search&mode=8&option_show_notice_fille=$option_show_notice_fille&option_show_expl=$option_show_expl",true);
		if ($_SESSION["CURRENT"]!==false) {
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["EXPL"]["URI"]="./catalog.php?categ=search&mode=8";
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["EXPL"]["POST"]=$_POST;
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["EXPL"]["GET"]=$_GET;
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["EXPL"]["PAGE"]=$page+1;
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["EXPL"]["HUMAN_QUERY"]=$sc->make_human_query();
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["EXPL"]["SEARCH_TYPE"]="expl";
			$_SESSION["session_history"][$_SESSION["CURRENT"]]["EXPL"]["TEXT_QUERY"]="";
		}
	break;
	default:
		print $sc->show_form("./catalog.php?categ=search&mode=8&option_show_notice_fille=$option_show_notice_fille&option_show_expl=$option_show_expl","./catalog.php?categ=search&mode=8&sub=launch");
	break;
}

function print_results($sc,$table,$url,$url_to_search_form,$hidden_form=true,$search_target="") {
    global $dbh;
    global $begin_result_liste;
    global $nb_per_page_search;
    global $page;
    global $charset;
    global $search;
    global $msg;
    global $pmb_nb_max_tri;
    global $affich_tris_result_liste;
    global $pmb_allow_external_search;
 	global $show_results_data;
	global $option_show_expl,$option_show_notice_fille;
	global $gestion_acces_active, $gestion_acces_user_notice;
	global $PMBuserid;
	
	
	//droits d'acces lecture notice
	if ($gestion_acces_active==1 && $gestion_acces_user_notice==1) {
		$ac= new acces();
		$dom_1= $ac->setDomain(1);
		$usr_prf = $dom_1->getUserProfile($PMBuserid);
		
		$requete = "delete from $table using $table, exemplaires, acces_res_1 ";
		$requete.= "where ";
		$requete.= "$table.expl_id=exemplaires.expl_id ";
		$requete.= "and expl_bulletin=0 ";
		$requete.= "and expl_notice = res_num ";
		$requete.= "and usr_prf_num=".$usr_prf." and ((res_rights ^ res_mask) & 4) ";
		mysql_query($requete, $dbh);

		$requete = "delete from $table using $table, exemplaires, acces_res_1 ";
		$requete.= "where ";
		$requete.= "$table.expl_id=exemplaires.expl_id ";
		$requete.= "and expl_notice=0 ";
		$requete.= "and expl_bulletin=bulletin_id ";
		$requete.= "and bulletin_notice=res_num ";
		$requete.= "and usr_prf_num=".$usr_prf." and ((res_rights ^ res_mask) & 4) ";
		mysql_query($requete, $dbh);
		
	}
	
    $start_page=$nb_per_page_search*$page;
    
    $requete="select count(1) from $table"; 
    $res = 	mysql_query($requete);
    if($res)
    	$nb_results=mysql_result(mysql_query($requete),0,0);
    else $nb_results=0;

    $requete="select $table.* from ".$table.", exemplaires where exemplaires.expl_id=$table.expl_id";     
	if ( $nb_results > $sc->nb_per_page ) {
		$requete .= " limit ".$start_page.", ".$nb_per_page_search;
	}

    //Y-a-t-il une erreur lors de la recherche ?
    if ($sc->error_message) {
    	error_message_history("", $sc->error_message, 1);
    	exit();
    }
    
    if ($hidden_form) print $sc->make_hidden_search_form($url);

    $resultat=mysql_query($requete,$dbh);

    $human_requete = $sc->make_human_query();
    
    print "<strong>".$msg["search_search_exemplaire"]."</strong> : ".$human_requete ;

	if ($nb_results) {
		print " => ".$nb_results." ".$msg["search_expl_nb_result"]."<br />\n";
		print $begin_result_liste;
		if ($sc->rec_history) {
			//Affichage des liens paniers et impression
			$current=$_SESSION["CURRENT"];
			if ($current!==false) {
				print "&nbsp;<a href='#' onClick=\"openPopUp('./print_cart.php?current_print=$current&action=print_prepare','print',600,700,-2,-2,'scrollbars=yes,menubar=0,resizable=yes'); return false;\"><img src='./images/basket_small_20x20.gif' border='0' align='center' alt=\"".$msg["histo_add_to_cart"]."\" title=\"".$msg["histo_add_to_cart"]."\"></a>&nbsp;";
				if ($nb_results<=$pmb_nb_max_tri) print $affich_tris_result_liste;
			}
		}
	} else print "<br />".$msg["1915"]." ";

	print "<input type='button' class='bouton' onClick=\"document.search_form.action='$url_to_search_form'; document.search_form.target='$search_target'; document.search_form.submit(); return false;\" value=\"".$msg["search_back"]."\"/>";
	
	// transformation de la recherche en multicritères: on reposte tout avec mode=6
	print "&nbsp;<input  type='button' class='bouton' onClick='document.search_transform.submit(); return false;' value=\"".$msg["search_expl_to_notice_transformation"]."\"/>";
	print "<form name='search_transform' action='./catalog.php?categ=search&mode=6&sub=launch'  method='post'>";	
	foreach($_POST as $key =>$val) {
		if($val) {
			if(is_array($val)) {
				foreach($val as $cle=>$val_array) {
					if(is_array($val_array)){
						foreach($val_array as $valeur){
							print "<input type='hidden' name=\"".$key."[".$cle."][]\" value='".htmlentities($valeur,ENT_QUOTES,$charset)."'/>";
						}
					} else print "<input type='hidden' name='".$key."[]' value='".htmlentities($val_array,ENT_QUOTES,$charset)."'/>";
				}
			}
			else print "<input type='hidden' name='$key' value='$val'/>";
		}		
	}	
	print "</form>"; 
	
	$recherche_ajax_mode=0;
	$nb=0;	
	
	if($resultat){			
	    while ($r=mysql_fetch_object($resultat)) {
	 		$nt = new mono_display_expl('',$r->expl_id, 6, $sc->link, $option_show_expl, $sc->link_expl, '', $sc->link_explnum,1, 0, 1, !$option_show_notice_fille, "", 1);   	
	    	echo "<div class='row'>".$nt->result.$aff."</div>";
	    }
	}
    
    //Gestion de la pagination
    if ($nb_results) {
  	  	$n_max_page=ceil($nb_results/$nb_per_page_search);
   	 	
   	 	if (!$page) $page_en_cours=0 ;
		else $page_en_cours=$page ;
	
   	 	// affichage du lien précédant si nécessaire
   	 	if ($page>0) {
   	 		$nav_bar .= "<a href='#' onClick='document.search_form.page.value-=1; ";
   	 		if (!$hidden_form) $nav_bar .= "document.search_form.launch_search.value=1; ";
   	 		$nav_bar .= "document.search_form.submit(); return false;'>";
    		$nav_bar .= "<img src='./images/left.gif' border='0'  title='".$msg[48]."' alt='[".$msg[48]."]' hspace='3' align='middle'/>";
    		$nav_bar .= "</a>";
    	}
        
		$deb = $page_en_cours - 10 ;
		if ($deb<0) $deb=0;
		for($i = $deb; ($i < $n_max_page) && ($i<$page_en_cours+10); $i++) {
			if($i==$page_en_cours) $nav_bar .= "<strong>".($i+1)."</strong>";
			else {
				$nav_bar .= "<a href='#' onClick=\"if ((isNaN(document.search_form.page.value))||(document.search_form.page.value=='')) document.search_form.page.value=1; else document.search_form.page.value=".($i)."; ";
    			if (!$hidden_form) $nav_bar .= "document.search_form.launch_search.value=1; ";
    			$nav_bar .= "document.search_form.submit(); return false;\">";
    			$nav_bar .= ($i+1);
    			$nav_bar .= "</a>";
			}
			if($i<$n_max_page) $nav_bar .= " "; 
		}
        
		if(($page+1)<$n_max_page) {
    		$nav_bar .= "<a href='#' onClick=\"if ((isNaN(document.search_form.page.value))||(document.search_form.page.value=='')) document.search_form.page.value=1; else document.search_form.page.value=parseInt(document.search_form.page.value)+parseInt(1); ";
    		if (!$hidden_form) $nav_bar .= "document.search_form.launch_search.value=1; ";
    		$nav_bar .= "document.search_form.submit(); return false;\">";
    		$nav_bar .= "<img src='./images/right.gif' border='0' title='".$msg[49]."' alt='[".$msg[49]."]' hspace='3' align='middle'>";
    		$nav_bar .= "</a>";
        } else 	$nav_bar .= "";
		$nav_bar = "<div align='center'>$nav_bar</div>";
   	 	echo $nav_bar ;
  	 	
    }  	
}
?>