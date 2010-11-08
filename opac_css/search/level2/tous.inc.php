<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: tous.inc.php,v 1.30 2010-08-03 13:00:56 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// second niveau de recherche OPAC sur  tous (level 2)

if($user_query=="*")$opac_indexation_docnum_allfields=0;
//Enregistrement des stats
if($pmb_logs_activate){
	global $nb_results_tab;
	$nb_results_tab['tous'] = $count;
}

print "	<div id=\"resultatrech\"><h3>$msg[resultat_recherche]</h3>\n
		<div id=\"resultatrech_container\">
		<div id=\"resultatrech_see\">
";

$msg_docnum = ($opac_indexation_docnum_allfields ? $msg[docnum_found_allfield] : '');
// requête de recherche sur tous les champs
print pmb_bidi("<h3><span>$count $msg_docnum $msg[titles_found] '".stripslashes($user_query)."'");
if ($opac_search_other_function) {
	require_once($include_path."/".$opac_search_other_function);
	print pmb_bidi(" ".search_other_function_human_query($_SESSION["last_query"]));
}
print "</span>";
print activation_surlignage();
print "</h3>";

//droits d'acces emprunteur/notice
$acces_j='';
if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom_2= $ac->setDomain(2);
	$acces_j= $dom_2->getJoin($_SESSION['id_empr_session'],4,'notice_id');
} 

if ($acces_j) {
	$statut_j='';
} else {
	$statut_j=',notice_statut';
}

if(!$opac_indexation_docnum_allfields)
	$requete="select distinct notice_id, ".$pert." from notices_global_index,notices $statut_j $acces_j $clause  $tri";
else $requete="select distinct uni.notice_id from  $join join notices n on uni.notice_id=n.notice_id  join notices_global_index on num_notice=uni.notice_id $tri"; 
	
//gestion du tri
if (isset($_GET["sort"])) {	
	$_SESSION["last_sortnotices"]=$_GET["sort"];
}
if ($count>$opac_nb_max_tri) {
	$_SESSION["last_sortnotices"]="";
}

if ($_SESSION["last_sortnotices"]!="") {
	$sort=new sort('notices','session');
	$requete=$sort->appliquer_tri($_SESSION["last_sortnotices"],$requete,"notice_id",$debut,$opac_search_results_per_page);		
} else {
	$requete.=" ".$limiter;
}
//fin gestion du tri

$found = mysql_query($requete, $dbh);

//print "select distinct(no_notice), ".$pert." from notices_global_index, notice_statut $clause group by no_notice $tri $limiter";
print "	</div>\n
		<div id=\"resultatrech_liste\">";

if ($opac_notices_depliable) print $begin_result_liste;

//gestion du tri
if ($count<=$opac_nb_max_tri) {
	$pos=strpos($_SERVER['REQUEST_URI'],"?");
	$pos1=strpos($_SERVER['REQUEST_URI'],"get");
	if ($pos1==0) $pos1=strlen($_SERVER['REQUEST_URI']);
	else $pos1=$pos1-3;
	$para=urlencode(substr($_SERVER['REQUEST_URI'],$pos+1,$pos1-$pos+1));
	$affich_tris_result_liste=str_replace("!!page_en_cours!!",$para,$affich_tris_result_liste); 
	print $affich_tris_result_liste;
	if ($_SESSION["last_sortnotices"]!="") {
		print " ".$msg['tri_par']." ".$sort->descriptionTriParId($_SESSION["last_sortnotices"])."&nbsp;"; 
	}
} else print "&nbsp;";
//fin gestion du tri

print $add_cart_link;
if($opac_visionneuse_allow && $nbexplnum_to_photo){
	print "&nbsp;&nbsp;&nbsp;".$link_to_visionneuse;
	print $sendToVisionneuseByPost; 
}	
//affinage
//enregistrement de l'endroit actuel dans la session
if ($_SESSION["last_query"]) {	$n=$_SESSION["last_query"]; } else { $n=$_SESSION["nb_queries"]; }

$_SESSION["notice_view".$n]["search_mod"]="all";
$_SESSION["notice_view".$n]["search_page"]=$page;

//affichage
print "&nbsp;&nbsp;<a href='$base_path/index.php?search_type_asked=extended_search&mode_aff=aff_simple_search'>".$msg["affiner_recherche"]."</a>";
//fin affinage

//Etendre
if ($opac_allow_external_search) print "&nbsp;&nbsp;<a href='$base_path/index.php?search_type_asked=external_search&mode_aff=aff_simple_search&external_type=simple'>".$msg["connecteurs_external_search_sources"]."</a>";
//fin etendre

if ($opac_show_suggest) {
	$bt_sugg = "&nbsp;&nbsp;&nbsp;<a href=# ";		
	if ($opac_resa_popup) $bt_sugg .= " onClick=\"w=window.open('./do_resa.php?lvl=make_sugg&oresa=popup','doresa','scrollbars=yes,width=600,height=600,menubar=0,resizable=yes'); w.focus(); return false;\"";
		else $bt_sugg .= "onClick=\"document.location='./do_resa.php?lvl=make_sugg&oresa=popup' \" ";			
	$bt_sugg.= " >".$msg[empr_bt_make_sugg]."</a>";
		print $bt_sugg;

}


$search_terms = unserialize(stripslashes($search_terms));
print "<blockquote>";
print aff_notice(-1);
while($mesNotices = mysql_fetch_object($found)) {
	//print "pert : ".$mesNotices->pert;
	print pmb_bidi(aff_notice($mesNotices->notice_id));
}
print aff_notice(-2);
print "</blockquote>";
print " </div>\n
		</div>
		</div>";