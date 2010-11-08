<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: perso.inc.php,v 1.10 2009-10-13 07:15:52 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$base_url = "./select.php?what=perso&caller=$caller&p1=$p1&p2=$p2&perso_id=$perso_id&custom_prefixe=".$custom_prefixe."&dyn=$dyn&perso_name=$perso_name";

require_once('./selectors/templates/sel_perso.tpl.php');
require_once($base_path.'/classes/parametres_perso.class.php');

$persos=new parametres_perso($custom_prefixe);

$sel_header=str_replace("!!select_title!!",sprintf($msg["perso_select"],htmlentities($persos->t_fields[$perso_id][TITRE],ENT_QUOTES,$charset)),$sel_header);
// affichage du header
print $sel_header;
print $jscript;
if($recherche){
	$f_user_input=rawurldecode($recherche);
}
$sel_search_form=str_replace("!!deb_rech!!",htmlentities(stripslashes($f_user_input),ENT_QUOTES,$charset),$sel_search_form);
print $sel_search_form;

$type=$persos->t_fields[$perso_id][TYPE];
$options=$param=_parser_text_no_function_("<?xml version='1.0' encoding='".$charset."'?>\n".$persos->t_fields[$perso_id][OPTIONS], "OPTIONS");

if ($type=="list") {
	$requete_count="select count(".$custom_prefixe."_custom_list_value) from ".$custom_prefixe."_custom_lists where ".$custom_prefixe."_custom_champ=".$perso_id;
	$requete="select ".$custom_prefixe."_custom_list_value, ".$custom_prefixe."_custom_list_lib from ".$custom_prefixe."_custom_lists where ".$custom_prefixe."_custom_champ=".$perso_id;
	if ($f_user_input) {
		$recherche=$f_user_input;
		$f_user_input=str_replace("*","%",$f_user_input);
		$requete.=" and ".$custom_prefixe."_custom_list_lib like '".$f_user_input."'";
		$requete_count.=" and ".$custom_prefixe."_custom_list_lib like '".$f_user_input."'";
	}
	$requete.=" order by ordre limit ".($page*$nb_per_page).",$nb_per_page";
	$resultat_count=mysql_query($requete_count);
} else {
	$requete="create temporary table temp_perso_list ENGINE=MyISAM ".$options[QUERY][0][value];
	mysql_query($requete);
	
	$resultat=mysql_query("show columns from temp_perso_list");
	$id_field=mysql_result($resultat,0,0);
	$lib_field=mysql_result($resultat,1,0);
	
	$requete_count="select count($id_field) from temp_perso_list";
	$requete="select $id_field, $lib_field from temp_perso_list";
	if ($f_user_input) {
		$recherche=$f_user_input;
		$f_user_input=str_replace("*","%",$f_user_input);
		$requete.=" where ".$lib_field." like '".$f_user_input."'";
		$requete_count.=" where ".$lib_field." like '".$f_user_input."'";
	}
	
	$requete.=" order by $lib_field limit ".($page*$nb_per_page).",$nb_per_page";
	$resultat_count=mysql_query($requete_count);
}
$nbr_lignes=@mysql_result($resultat_count,0,0);
$resultat=mysql_query($requete);

while($r=mysql_fetch_row($resultat)) {
	print pmb_bidi("<a href='#' onClick=\"set_parent('$caller', '".htmlentities(addslashes($r[0]),ENT_QUOTES,$charset)."','".htmlentities(addslashes($r[1]),ENT_QUOTES,$charset)."' )\">".htmlentities($r[1],ENT_QUOTES,$charset)."</a><br />");
}

// constitution des liens

$nbepages = ceil($nbr_lignes/$nb_per_page);
$suivante = $page+1;
$precedente = $page-1;

// affichage du lien précédent si nécéssaire
print '<hr /><div align=center>';
if($precedente >= 0) {
	print "<a href='$base_url&page=$precedente&nbr_lignes=$nbr_lignes&recherche=".rawurlencode($recherche)."'><img src='./images/left.gif' border='0' title='$msg[48]' alt='[$msg[48]]' hspace='3' align='middle' /></a>";
}
print "<b>".($page+1)."/$nbepages</b>";

if($suivante<$nbepages)
	print "<a href='$base_url&page=$suivante&nbr_lignes=$nbr_lignes&recherche=".rawurlencode($recherche)."'><img src='./images/right.gif' border='0' title='$msg[49]' alt='[$msg[49]]' hspace='3' align='middle' /></a>";
print '</div>';

print $sel_footer;
?>