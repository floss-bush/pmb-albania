<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: view_sugg.inc.php,v 1.11 2010-02-23 16:27:22 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// inclusion des classes de gestion des suggestions
require_once($base_path.'/classes/suggestions.class.php');
require_once($base_path.'/classes/suggestions_origine.class.php');
require_once($base_path.'/classes/suggestions_categ.class.php');
require_once($base_path.'/classes/suggestions_map.class.php');
require_once($base_path.'/classes/suggestion_source.class.php');

$sug_map = new suggestions_map();

$sug_form.= "
<div id='view_sugg'>
	<h3><span>".htmlentities($msg['empr_view_sugg'], ENT_QUOTES, $charset)."<span></h3>
	<div id='empr_view-container'>
	<!-- affichage liste des suggestions -->
	<table width='100%'><tr>
		<th>".htmlentities($msg['acquisition_sug_dat_cre'], ENT_QUOTES, $charset)."</th>
		<th>".htmlentities($msg['acquisition_sug_tit'], ENT_QUOTES, $charset)."</th>
		<th>".htmlentities($msg['acquisition_sug_edi'], ENT_QUOTES, $charset)."</th>
		<th>".htmlentities($msg['acquisition_sug_aut'], ENT_QUOTES, $charset)."</th>
		<th>".htmlentities($msg['acquisition_sug_etat'], ENT_QUOTES, $charset)."</th>
		<th>".htmlentities($msg['empr_sugg_datepubli'], ENT_QUOTES, $charset)."</th>
		<th>".htmlentities($msg['empr_sugg_src'], ENT_QUOTES, $charset)."</th>";

if ($opac_sugg_categ=='1') {
	$sug_form.= "<th>".htmlentities($msg['acquisition_categ'], ENT_QUOTES, $charset)."</th>";
}

$sug_form .= "<th>".htmlentities($msg['empr_sugg_piece_jointe'], ENT_QUOTES, $charset)."</th>";
$sug_form.= "<th>&nbsp;</th></tr>";
	
$q = suggestions::listSuggestionsByOrigine($id_empr, '1');
$res = mysql_query($q, $dbh);
$nbr = mysql_num_rows($res); 

if($nbr){
	for($i=0;$i<$nbr;$i++) {
		$row=mysql_fetch_object($res);
			
		$lib_statut = $sug_map->getHtmlComment($row->statut);
		if ($parity++ % 2) $pair_impair = "even"; else $pair_impair = "odd";
		
		if($row->statut == 1){
			//Si la suggestion n'est pas validée on peut la modifier
			$modif_sugg = "onclick=\"document.location='".$opac_url_base."empr.php?lvl=make_sugg&id_sug=$row->id_suggestion'\" ";
			$pointer = "style='cursor:pointer'";
		} else {
			$modif_sugg = "";
			$pointer = "";
		}
		$sug_form.= "
			<tr class='$pair_impair' >
				<td $modif_sugg $pointer>".formatdate($row->date_suggestion)."</td>
				<td $modif_sugg $pointer>".htmlentities($row->titre, ENT_QUOTES, $charset)."</td>
				<td $modif_sugg $pointer>".htmlentities($row->editeur, ENT_QUOTES, $charset)."</td>
				<td $modif_sugg $pointer>".htmlentities($row->auteur, ENT_QUOTES, $charset)."</td>
				<td $modif_sugg $pointer>".$lib_statut."</td>
				<td $modif_sugg $pointer>".htmlentities($row->date_publication, ENT_QUOTES, $charset)."</td>";
		
		$source = new suggestion_source($row->sugg_source);
		$sug_form.= "<td $pointer>".htmlentities($source->libelle_source, ENT_QUOTES, $charset)."</td>";
		if ($opac_sugg_categ=='1') {
			$categ = new suggestions_categ($row->num_categ);
			$sug_form.= "<td $modif_sugg $pointer>".htmlentities($categ->libelle_categ, ENT_QUOTES, $charset)."</td>";
		}
		
		$sug = new suggestions($row->id_suggestion);
		if($sug->get_explnum('id')){
			$sug_form .="<td align='center'><i>".($sug->get_explnum('id') ? "<a href=\"$base_path/explnum_doc.php?explnumdoc_id=".$sug->get_explnum('id')."\" target=\"_LINK_\"><img src='$base_path/images/globe_orange.png' border='0' /></a>" : '' )."</i></td>";
		} else {
			$sug_form .="<td></td>";
		}
		$sug_form.= "
			</tr>";		
	}	
	$sug_form.= "</table></div></div>";
	
	print $sug_form;
	
} else {
	
	print "
		<h3>".htmlentities($msg['empr_view_sugg'], ENT_QUOTES, $charset)."</h3>
		<div class='row'>".htmlentities($msg['empr_view_no_sugg'], ENT_QUOTES, $charset)."
		</div>";
}

?>
