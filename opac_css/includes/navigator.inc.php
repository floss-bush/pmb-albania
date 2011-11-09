<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+

if ($lvl=="etagere_see") 
	$navig.="<td><a href=\"index.php?lvl=etageres_see\" class='etageres_see'><span>".$msg["etageres_see"]."</span></a></td>\n";
if (($_SESSION["nb_queries"])&&($lvl!="search_result"))
	$navig.="<td ><a href=\"index.php?lvl=search_result&get_query=".$_SESSION["nb_queries"]."\" class='actions_last_search'><span>".$msg["actions_last_search"]."</span></a></td>\n";
if (($lvl!="more_results")&&($_SESSION["last_query"]!="")) {
	if ($_SESSION["last_query"]==$_SESSION["nb_queries"]) 
		$search_name=" ".$msg["actions_last_page_last_search"]; 
	else {
		if ($_SESSION["lq_mode"]=="extended")
			$search_name=" ".$msg["actions_last_page_extended_search"]." ";
		else
			$search_name=" ".$msg["actions_last_page_simple_search"]." ";
		$search_name.=$msg['number'].$_SESSION["last_query"];
	}
	$navig.="<td ><a href=\"index.php?lvl=more_results&get_last_query=1\" class='actions_last_page'><span>".sprintf($msg["actions_last_page"],$_SESSION["lq_page"],$msg[$_SESSION["list_name_msg"]],$search_name);
	$navig.="</span></a></td>\n";
}
if (($_SESSION["nb_queries"])&&($lvl!="search_history")) 
	$navig.="<td ><a href=\"index.php?lvl=search_history\" class='actions_history'><span>".$msg["actions_history"]."</span></a></td>\n";
if (($lvl!="index")&&($lvl!="")) {
	if ($lvl!="section_see") {
		$item="";
		if ($opac_show_categ_browser) {
			$item=$msg["navig_categ"];
			$class="navig_categ";
		}
		if (($opac_show_dernieresnotices)&&(!$item)) {
			$item=$msg["navig_lastnotices"];
			$class="navig_lastnotices";
		}
		if (($opac_show_etageresaccueil)&&(!$item)) {
			$item=$msg["navig_etageres"];
			$class="navig_etageres";
		}
		if (($opac_show_marguerite_browser)&&(!$item)) {
			$item=$msg["navig_marguerite"];
			$class="navig_marguerite";
		}
		if (($opac_show_100cases_browser)&&(!$item)) {
			$item=$msg["navig_100cases"];
			$class="navig_categ";
		}
		if (!$item) {
			$item=$msg[avec_recherches]; 
			$class="avec_recherches"; 
		}
		
	} else {
		$item=$msg[avec_recherches]; 
		$class="avec_recherches"; 
	}
	$navig.="<td ><a href=\"./index.php?lvl=index\" class='$class'><span>".sprintf($msg["actions_first_screen"],$item)."</span></a></td>\n";
	if($opac_navig_empr)  $navig.="<td ><a href=\"./empr.php\" class='$class'><span>".$msg["empr_bt_show_compte"]."</span></a></td>\n";	
}

if ($_SESSION["user_code"]){
	if($opac_show_onglet_empr==3)  $navig.="<td ><a href=\"./index.php?search_type_asked=connect_empr\" class='$class'><span>".$msg["empr_bt_show_compte"]."</span></a></td>\n";	
	elseif($opac_show_onglet_empr==4)  $navig.="<td ><a href=\"./empr.php\" class='$class'><span>".$msg["empr_bt_show_compte"]."</span></a></td>\n";	
}
if($opac_show_onglet_help && ((($lvl!="index") && ($lvl!="search_type_asked") && ($lvl!="search_result") && ($lvl!=""))||(stristr($_SERVER['REQUEST_URI'], "empr.php"))))
		$navig .= "<td ><a href=\"./index.php?lvl=infopages&pagesid=$opac_show_onglet_help\" ><span>".$msg["search_help"]."</span></a></td>\n";

if ($navig) {
	print "<div id='navigator'>\n";
	print "<strong>".$msg["actions_you_can"]."</strong>\n";
	print "<table width='100%'>";
	print "<tr>";
	print $navig;
	print("</tr>");
	print("</table>");
	print "</div><!-- fermeture de #navigator -->\n";
}
if (((($opac_cart_allow)&&(!$opac_cart_only_for_subscriber))||(($opac_cart_allow)&&($_SESSION["user_code"])))&&($lvl!="show_cart")) 
	print "<iframe recept='yes' recepttype='cart' frameborder='0' id='iframe_resume_panier' name='cart_info' allowtransparency='true' src='cart_info.php?' scrolling='no' scrollbar='0'></iframe>";

?>