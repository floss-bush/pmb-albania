<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: show_cart.inc.php,v 1.37.2.1 2011-07-08 13:19:37 trenon Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// pour export panier
require_once("$base_path/admin/convert/start_export.class.php");

if (isset($_GET["sort"])) {	
	$_SESSION["last_sortnotices"]=$_GET["sort"];
}
if ($count>$opac_nb_max_tri) {
	$_SESSION["last_sortnotices"]="";
}

$cart_=$_SESSION["cart"];

if ($raz_cart) {
	$cart_=array(); 
	$_SESSION["cart"]=$cart_;
}

//Traitement des actions
if ($action) {
	switch ($action) {
		case "del":
			for ($i=0; $i<count($notice); $i++) {
				$as=array_search($notice[$i],$cart_);
				if (($as!==null)&&($as!==false)) {
					//Décalage
					for ($j=$as+1; $j<count($cart_); $j++) {
						$cart_[$j-1]=$cart_[$j];
					}
					unset($cart_[count($cart_)-1]);
				}
			}
			$_SESSION["cart"]=$cart_;
			if (ceil(count($cart_)/$opac_search_results_per_page)<$page) $page=count($cart_)/$opac_search_results_per_page;
			break;
	}
}

print "<script>
function setCheckboxes(the_form, the_objet, do_check) {
	 var elts = document.forms[the_form].elements[the_objet+'[]'] ;
	 var elts_cnt = (typeof(elts.length) != 'undefined') ? elts.length : 0;
	 if (elts_cnt) {
		for (var i = 0; i < elts_cnt; i++) {
	 		elts[i].checked = do_check;
	 	} // end for
	 } else {
	 	elts.checked = do_check;
	 } 
	 return true;
} </script>
";

print "<div id='cart_action'>";

if ($page=="") $page=1;
if (count($cart_)) {
	
	//gestion des notices externes (sauvegarde)
	$cart_ext = array();
	for($i=0;$i<sizeof($cart_);$i++){
		if(strpos($cart_[$i],"es") !== false){
			$cart_ext[] = $cart_[$i];
		}
	}
		
	print "<input type='button' class='bouton' value=\"".$msg["show_cart_empty"]."\" onClick=\"document.location='./index.php?lvl=show_cart&raz_cart=1'\">&nbsp;
		<input type='button' class='bouton' value=\"".$msg["show_cart_del_checked"]."\" onClick=\"document.cart_form.submit();\">&nbsp;
		<input type='button' class='bouton' value=\"".$msg["show_cart_print"]."\" onClick=\"w=window.open('print.php?lvl=cart','print_window','width=500, height=750,scrollbars=yes,resizable=1'); w.focus();\">";
	
	if($opac_shared_lists && $allow_liste_lecture && $id_empr){
		print "<script>
		function confirm_transform(){
			var is_check=false;
			var elts = document.getElementsByName('notice[]') ;
			if (!elts) is_check = false ;
			var elts_cnt  = (typeof(elts.length) != 'undefined')
	                  ? elts.length
	                  : 0;
			if (elts_cnt) {
				for (var i = 0; i < elts_cnt; i++) { 		
					if (elts[i].checked) {
						return true;
					}
				}
			} 
			if(!is_check){
				alert('".$msg[list_lecture_no_ck]."');
				return false;
			}
	        
			return is_check;
		}
		
		</script>";
		print "<br /><br /><input type='button' class='bouton' value=\"".$msg["list_lecture_transform_caddie"]."\" onClick=\"document.location='./index.php?lvl=show_list&sub=transform_caddie'\">&nbsp;
			<input type='button' class='bouton' value=\"".$msg["list_lecture_transform_checked"]."\" onClick=\"document.cart_form.action='./index.php?lvl=show_list&sub=transform_check';if(confirm_transform()) document.cart_form.submit(); else return false;\">&nbsp;
			<input type='button' class='bouton' value=\"".$msg["list_lecture_cart_checked_all"]."\" onClick=\"setCheckboxes('cart_form', 'notice', true); return false;\">";
	}
	if ($opac_show_suggest && $opac_allow_multiple_sugg && $allow_sugg && $id_empr) {
		print "
		 <script>
		 function notice_checked(){
			var is_check=false;
			var elts = document.getElementsByName('notice[]') ;
			if (!elts) is_check = false ;
			var elts_cnt  = (typeof(elts.length) != 'undefined')
	                  ? elts.length
	                  : 0;
			if (elts_cnt) {
				for (var i = 0; i < elts_cnt; i++) { 		
					if (elts[i].checked) {
						return true;
					}
				}
			} 
			if(!is_check){
				alert('".$msg[list_lecture_no_ck]."');
				return false;
			}
	        
			return is_check;
		}	
		</script>
		";
		print "<br /><br />";
		print "<input type='button' class='bouton' value=\"".$msg["transform_caddie_to_multisugg"]."\" onClick=\"document.getElementById('div_src_sugg').style.display='';\">";
		print "&nbsp;<input type='button' class='bouton' value=\"".$msg["transform_caddie_notice_to_multisugg"]."\" onClick=\"if(notice_checked()){ document.getElementById('div_src_sugg').style.display='';} else return false; \">";		
		print "<div class='row' id='div_src_sugg' style='display:none' >";
		print "<label class='etiquette'>".$msg['empr_sugg_src'].": </label>";
		//Affichage du selecteur de source
		$req = "select * from suggestions_source order by libelle_source";
		$res= mysql_query($req,$dbh);
		$option = "<option value='0' selected>".htmlentities($msg['empr_sugg_no_src'],ENT_QUOTES,$charset)."</option>";
		while(($src=mysql_fetch_object($res))){
			$option .= "<option value='".$src->id_source."' $selected >".htmlentities($src->libelle_source,ENT_QUOTES,$charset)."</option>";
		}
		$selecteur = "<select id='sug_src' name='sug_src'>".$option."</select>";
		print $selecteur;
		print "<input type='button' class='bouton' value=\"".$msg[11]."\" onClick=\"document.cart_form.action='./empr.php?lvl=transform_to_sugg&act=transform_caddie&sug_src='+document.getElementById('sug_src').value;document.cart_form.submit();\">";
		print "</div>";
	}
	//Tri
	if ($_SESSION["last_sortnotices"]!="") {
		$sort=new sort('notices','session');
		$sql = "SELECT notice_id FROM notices WHERE notice_id IN (";
		for ($z=0; $z<count($cart_); $z++) {
			$sql.="'". $cart_[$z]."',";
		}
		$sql = substr($sql, 0, strlen($sql) - 1) .")";
		
		$sql=$sort->appliquer_tri($_SESSION["last_sortnotices"],$sql,"notice_id",0,0);		
	} else {
		$sql="select notice_id from notices where notice_id in ('".implode("','",$cart_)."') order by tit1";
	}

	$res=mysql_query($sql,$dbh);
	$cart_=array(); 
	while ($r=mysql_fetch_object($res)) {			
		$cart_[]=$r->notice_id;
	}	
	if($cart_ext) $cart_ = array_merge($cart_,$cart_ext);
	$_SESSION["cart"]=$cart_;	
	
	if (($opac_export_allow=='1') || (($opac_export_allow=='2') && ($_SESSION["user_code"]))) {
		$nb_fiche=0;
		$nb_fiche_total=count($cart_);
			
		for ($z=0; $z<$nb_fiche_total; $z++) {
			if (substr($cart_[$z],0,2)!="es"){
				// Exclure de l'export (opac, panier) les fiches interdites de diffusion dans administration, Notices > Origines des notices NG72
				$sql="select * from origine_notice,notices where notice_id = '$cart_[$z]' and origine_catalogage = orinot_id and orinot_diffusion='1' order by tit1";	 	 
			} else {
				$requete = "SELECT source_id FROM external_count WHERE rid=".addslashes(substr($cart_[$z],2));
				$myQuery = mysql_query($requete, $dbh);
				$source_id = mysql_result($myQuery, 0, 0);				
				$sql="select 1 from entrepot_source_$source_id where recid='".addslashes(substr($cart_[$z],2))."' group by ufield,usubfield,field_order,subfield_order,value";
			}	
			$res=mysql_query($sql,$dbh);
			if ($ligne=mysql_fetch_array($res)) 
				$nb_fiche++;
		}
		if ($nb_fiche!=$nb_fiche_total) {
			$msg_export_partiel = str_replace ("!!nb_export!!",$nb_fiche, $msg[export_partiel]);
			$msg_export_partiel = str_replace ("!!nb_total!!",$nb_fiche_total, $msg_export_partiel);
			$js_export_partiel = "if (confirm('".addslashes($msg_export_partiel)."')) {";
		} else $js_export_partiel = "if (true) {";

		print "<form name='export_form'><br />";
		$radio = "<br />
			<input type='radio' name='radio_exp' id='radio_exp_all' value='0' checked /><label for='radio_exp_all'>".htmlentities($msg['export_cart_all'],ENT_QUOTES,$charset)."</label>
			<input type='radio' name='radio_exp' id='radio_exp_sel' value='1' /><label for='radio_exp_sel'>".htmlentities($msg['export_cart_selected'],ENT_QUOTES,$charset)."</label>
		";
		
		$exp = start_export::get_exports();
		$selector_exp = "<select name='typeexport'>" ;
		for ($i=0;$i<count($exp);$i++) {
			$selector_exp .= "<option value='".$exp[$i]["ID"]."'>".$exp[$i]["NAME"]."</option>";
		}
		$selector_exp .= "</select>" ;
		print sprintf($msg[show_cart_export]."&nbsp;",$selector_exp.$radio);
		if ($opac_export_allow_expl) print "<input type='hidden' name=keep_expl value=\"1\" >";
		print "<script type='text/javascript'>
			function getNoticeSelected(){
				if(document.getElementById('radio_exp_sel').checked){
					var items = '&select_item=';
					var notices = document.forms['cart_form'].elements;
					var hasSelected = false;
					for (var i = 0; i < notices.length; i++) { 
					 	if(notices[i].checked) {
					 		items += notices[i].value+',';
							hasSelected = true;	
						}
					}
					if(!hasSelected) {
						alert('".$msg[list_lecture_no_ck]."');
						return false;	
					} else return items;
				}
				return true;
			}
		</script>";
		print "&nbsp;<input type='button' class='bouton' value=\"".$msg["show_cart_export_ok"]."\" onClick=\"$js_export_partiel if(getNoticeSelected()){ document.location='./export.php?action=export&typeexport='+document.export_form.typeexport.options[top.document.export_form.typeexport.selectedIndex].value+getNoticeSelected();}}\">";
		print "</form>";
		}
	}

print "</div>";

if (count($cart_)) {
	print "<h3><span>".$msg["show_cart_content"]."</span> : <b>".sprintf($msg["show_cart_n_notices"],count($cart_))."</b></h3>";
	
	print "<div class='search_result'>";
	if ($opac_notices_depliable) print $begin_result_liste;
	
	if (count($cart_)<=$pmb_nb_max_tri)
		print str_replace("!!page_en_cours!!","lvl=show_cart",$affich_tris_result_liste);
	
	if ($_SESSION["last_sortnotices"]!="")
		print " ".$msg['tri_par']." ".$sort->descriptionTriParId($_SESSION["last_sortnotices"])."&nbsp;"; 

	print "<blockquote>";
	
	// case à cocher de suppression transférée dans la classe notice_affichage
	$cart_aff_case_traitement = 1 ; 
	print "<form action='./index.php?lvl=show_cart&action=del&page=$page' method='post' name='cart_form'>\n";
	for ($i=(($page-1)*$opac_search_results_per_page); (($i<count($cart_))&&($i<($page*$opac_search_results_per_page))); $i++) {
		if (substr($cart_[$i],0,2)!="es") 
			print pmb_bidi(aff_notice($cart_[$i],1)); 
		else 
			print pmb_bidi(aff_notice_unimarc(substr($cart_[$i],2),1));
	}
	print "</form>";
	print "</blockquote>";
	print "</div>";

	$nbepages = ceil(count($cart_)/$opac_search_results_per_page);
	$suivante = $page+1;
	$precedente = $page-1;

	// affichage du lien précédent si nécéssaire
	print "<hr /><table border='0' summary='navigation bar' align='center'><tr>";

	// affichage du lien pour retour au début
	if($precedente > 1) {
		print "<td width=\"14\" align=\"center\"><a href=\"index.php?lvl=show_cart&page=1\"><img src=\"./images/first.gif\"";
		print " border=\"0\" alt=\"$msg[start]\"";
		print " title=\"$msg[first_page]\"></a></td>";
	} else {
		print "<td width=\"14\" align=\"center\"><img src=\"./images/first-grey.gif\">";
	}

	if($precedente > 0) {
		print "<td width=\"14\" align=\"center\"><a href=\"index.php?lvl=show_cart&page=$precedente\"><img src=\"./images/prev.gif\"";
		print " border=\"0\" alt=\"$msg[prec]\"";
		print " title=\"$msg[prec]\"></a></td>";
	} else {
		print "<td width=\"14\" align=\"center\"><img src=\"./images/prev-grey.gif\">";
	}

	print "<td align='center'>$msg[page] $page/$nbepages</td>";

	// lien suivant
	if($suivante<=$nbepages) {
		print "<td width=\"14\" align=\"center\"><a href=\"index.php?lvl=show_cart&page=$suivante\"><img src=\"./images/next.gif\"";
		print " border=\"0\" alt=\"$msg[next]\"";
		print " title=\"$msg[next]\"></a></td>";
	} else {
		print "<td width=\"14\" align=\"center\"><img src=\"./images/next-grey.gif\">";
	}

	// affichage du lien vers la fin
	if($suivante < $nbepages) {
		print "<td width=\"14\" align=\"center\"><a href=\"index.php?lvl=show_cart&page=$nbepages\"><img src=\"./images/last.gif\"";
		print " border=\"0\" alt=\"$msg[end]\"";
		print " title=\"$msg[end]\"></a></td>";
	} else {
		print "<td width=\"14\" align=\"center\"><img src=\"./images/last-grey.gif\">";
	}

	print "</tr></table><br />";
} else {
	print "<h3><span>".$msg["show_cart_is_empty"]."</span></h3>";
}
?>