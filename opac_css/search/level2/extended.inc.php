<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: extended.inc.php,v 1.47.2.1 2011-06-16 14:39:02 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// second niveau de recherche OPAC sur titre
// inclusion classe pour affichage notices (level 1)
require_once($base_path.'/includes/templates/notice.tpl.php');
require_once($base_path.'/classes/notice.class.php');
require_once($class_path."/search.class.php");

//Enregistrement des stats
if($pmb_logs_activate){
	global $nb_results_tab;
	$nb_results_tab['extended'] = $count;
}

$es=new search();
$table=$es->make_search();

//droits d'acces emprunteur/notice
$acces_j='';
if ($gestion_acces_active==1 && $gestion_acces_empr_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom_2= $ac->setDomain(2);
	$acces_j = $dom_2->getJoin($_SESSION['id_empr_session'],4,'notice_id');
}
	
if($acces_j) {
	$statut_j='';
	$statut_r='';
} else {
	$statut_j=',notice_statut';
	$statut_r="and statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")";
}

//$requete="select count(1) from $table, notices, notice_statut where $table.notice_id=notices.notice_id and (statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"")."))";
$requete="select count(1) from $table, notices $acces_j $statut_j where $table.notice_id=notices.notice_id $statut_r ";
$resultat=mysql_query($requete);
$count=@mysql_result($resultat,0,0);


//Recherche des types doc

//$requete="select distinct notices.typdoc FROM $table, notices, notice_statut where $table.notice_id=notices.notice_id and (statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"")."))";
$requete="select distinct notices.typdoc FROM $table, notices $acces_j $statut_j where $table.notice_id=notices.notice_id $statut_r group by notices.typdoc";
if($opac_visionneuse_allow){
	$requete_noti="select count(explnum_id) as nbexplnum FROM $table, notices left join explnum on explnum_notice = notice_id and explnum_mimetype in ($opac_photo_filtre_mimetype) $acces_j $statut_j where $table.notice_id=notices.notice_id $statut_r group by notices.typdoc";
	$requete_bull="select count(explnum_id) as nbexplnum FROM $table, notices left join bulletins on bulletins.num_notice = notice_id and bulletins.num_notice != 0 left join explnum on explnum_bulletin = bulletin_id and explnum_bulletin != 0 and explnum_mimetype in ($opac_photo_filtre_mimetype) $acces_j $statut_j where $table.notice_id=notices.notice_id $statut_r group by notices.typdoc";
	$res = mysql_query($requete_noti, $dbh);
	$nbexplnum_to_photo= mysql_result($res,0,0);
	$res = mysql_query($requete_bull, $dbh);
	$nbexplnum_to_photo+= mysql_result($res,0,0);
}

$res = mysql_query($requete, $dbh);
$t_typdoc=array();
while (($tpd=mysql_fetch_object($res))) {
	$t_typdoc[]=$tpd->typdoc;
}
$l_typdoc=implode(",",$t_typdoc);

print "	<div id=\"resultatrech\"><h3>$msg[resultat_recherche]</h3>\n
		<div id=\"resultatrech_container\">
		<div id=\"resultatrech_see\">
";

print pmb_bidi("<h3>$count $msg[titles_found] ".$es->make_human_query()."</h3>");

// pour la DSI
if ($opac_allow_bannette_priv && $allow_dsi_priv && ($_SESSION['abon_cree_bannette_priv']==1 || $opac_allow_bannette_priv==2)) {
	print "<input type='button' class='bouton' name='dsi_priv' value=\"$msg[dsi_bt_bannette_priv]\" onClick=\"document.form_values.action='./empr.php?lvl=bannette_creer'; document.form_values.submit();\">&nbsp;";
}

//Test pour le tri !!
$requete="select pert from $table limit 1";
$r_test=mysql_query($requete);
if ($r_test) $order="pert desc"; else $order="idiot";

//$requete = "select notices.notice_id,tit1 from $table,notices, notice_statut where (notices.notice_id=$table.notice_id) and (statut=id_notice_statut and ((notice_visible_opac=1 and notice_visible_opac_abon=0)".($_SESSION["user_code"]?" or (notice_visible_opac_abon=1 and notice_visible_opac=1)":"").")) order by ".$order;
$requete = "select notices.notice_id,tit1 from $table,notices $acces_j $statut_j where notices.notice_id=$table.notice_id $statut_r  ";

//gestion du tri
if (isset($_GET["sort"])) {	
	$_SESSION["last_sortnotices"]=$_GET["sort"];
}
if ($count>$opac_nb_max_tri) {
	$_SESSION["last_sortnotices"]="";
}
$has_sort = false;
if ($_SESSION["last_sortnotices"]!="") {
	$sort=new sort('notices','session');
	$requete=$sort->appliquer_tri($_SESSION["last_sortnotices"],$requete,"notice_id",$debut,$opac_search_results_per_page);		
	$has_sort = true;
} else {
	if(count($search) > 1 && !$has_sort)
		$requete .= " order by index_serie, tnvol, index_sew  ".$limiter;
	else $requete .= " order by ".$order." ".$limiter;
}
//fin gestion du tri
$found = mysql_query($requete, $dbh);

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
	$search_to_post = $es->serialize_search();
	print "&nbsp;&nbsp;&nbsp;".$link_to_visionneuse;
	print "
<script type='text/javascript'>
	function sendToVisionneuse(explnum_id){
		if (typeof(explnum_id)!= 'undefined') {
			var explnum =document.createElement('input');
			explnum.setAttribute('type','hidden');
			explnum.setAttribute('name','explnum_id');
			explnum.setAttribute('value',explnum_id);
			document.form_values.appendChild(explnum);
		}
		var mode = document.createElement('input');
		mode.setAttribute('type','hidden');
		mode.setAttribute('name','mode');
		mode.setAttribute('value','extended');
		var input = document.createElement('input');
		input.setAttribute('id','search');
		input.setAttribute('name','search');
		input.setAttribute('value','".htmlspecialchars($search_to_post,ENT_QUOTES,$charset)."');
		document.form_values.appendChild(input);
		document.form_values.appendChild(mode);
	

		document.form_values.action='visionneuse.php';
		document.form_values.target='visionneuse';
		document.form_values.submit();
	}
</script>";
}

//affinage
//enregistrement de l'endroit actuel dans la session
if ($_SESSION["last_query"]) {	$n=$_SESSION["last_query"]; } else { $n=$_SESSION["nb_queries"]; }

$_SESSION["notice_view".$n]["search_mod"]="extended";
$_SESSION["notice_view".$n]["search_page"]=$page;

//affichage
print "&nbsp;&nbsp;<a href='$base_path/index.php?search_type_asked=extended_search&get_query=$n'>".$msg["affiner_recherche"]."</a>";
//fin affinage

//Etendre
if ($opac_allow_external_search) print "&nbsp;&nbsp;<a href='$base_path/index.php?search_type_asked=external_search&mode_aff=aff_simple_search&external_type=multi'>".$msg["connecteurs_external_search_sources"]."</a>";
//fin etendre

if ($opac_show_suggest) {
	$bt_sugg = "&nbsp;&nbsp;&nbsp;<a href=# ";		
	if ($opac_resa_popup) $bt_sugg .= " onClick=\"w=window.open('./do_resa.php?lvl=make_sugg&oresa=popup','doresa','scrollbars=yes,width=600,height=600,menubar=0,resizable=yes'); w.focus(); return false;\"";
	else $bt_sugg .= "onClick=\"document.location='./do_resa.php?lvl=make_sugg&oresa=popup' \" ";			
	$bt_sugg.= " >".$msg[empr_bt_make_sugg]."</a>";
	print $bt_sugg;
}

print "<blockquote>";
print aff_notice(-1);
while($mesNotices = mysql_fetch_object($found)) {
	print pmb_bidi(aff_notice($mesNotices->notice_id));
}
print aff_notice(-2);
print "</blockquote>";
print " </div>\n
		</div>
		</div>";

		