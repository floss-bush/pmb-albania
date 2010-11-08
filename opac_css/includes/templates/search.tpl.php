<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: search.tpl.php,v 1.30 2009-11-30 16:55:02 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], "search.tpl.php")) die("no access");

//Template du formulaire de recherches avancées
$search_form="
<script src=\"includes/javascript/ajax.js\"></script>
<ul class='search_tabs'>
	!!others!!
	<li id='current'>".($search_type_asked=="external_search"?"<a href='./index.php?search_type_asked=external_search&external_type=simple' title='".$msg["simple_search"]."' alter='".$msg["simple_search"]."'>".$msg["connecteurs_external_search"]."</a>":$msg["search_extended"])."</li>!!others2!!".
   ($opac_show_onglet_help ? "<li><a href=\"./index.php?lvl=infopages&pagesid=$opac_show_onglet_help\">".$msg["search_help"]."</a></li>": '')."
</ul>
<div id='search_crl'></div>
<form class='form-$current_module' name='search_form' action='!!url!!' method='post'  onsubmit='return false'>
	<div class='form-contenu'>
		<div id='choose_criteria'>".$msg["search_add_field"]."</div> !!field_list!!"; 
if(!$opac_extended_search_auto)
	$search_form .= "<input type='button' class='bouton' value='".$msg["925"]."' onClick=\"if (this.form.add_field.value!='') { this.form.action='!!url!!'; this.form.target=''; this.form.submit();} else { alert('".htmlentities($msg["multi_select_champ"],ENT_QUOTES,$charset)."'); }\"/>";
if ($opac_show_help) 
	$search_form.="<input type='button' class='bouton' name='?' value='$msg[search_help]' class='bouton' onClick='window.open(\"$base_path/help.php?whatis=search_multi\", \"search_help\", \"scrollbars=yes, toolbar=no, dependent=yes, width=400, height=400, resizable=yes\"); return false' />\n";
$search_form.="		<br /><br />
		<div class='row'>
			!!already_selected_fields!!
		</div>
	</div>
	<input type='hidden' name='delete_field' value=''/>
	<input type='hidden' name='launch_search' value=''/>
	<input type='hidden' name='page' value='!!page!!'/>
</form>
<script>ajax_parse_dom()</script>
<br />";


//Template du formulaire de recherches avancées
$search_form_perso="
<script src=\"includes/javascript/ajax.js\"></script>
<ul class='search_tabs'>!!others!!<li><a href=\"./index.php?search_type_asked=extended_search&onglet_persopac=\">".$msg["extended_search"]."</a></li>!!others2!!".
	($opac_show_onglet_help ? "<li><a href=\"./index.php?lvl=infopages&pagesid=$opac_show_onglet_help\">".$msg["search_help"]."</a></li>": '')."
</ul>
<div id='search_crl'></div>
<form class='form-$current_module' name='search_form' action='!!url!!' method='post'  onsubmit='return false'>
	<div class='form-contenu'>
		<div id='choose_criteria'>".$msg["search_add_field"]."</div> !!field_list!!";
if(!$opac_extended_search_auto)
	$search_form_perso .= "<input type='button' class='bouton' value='".$msg["925"]."' onClick=\"if (this.form.add_field.value!='') { this.form.action='!!url!!'; this.form.target=''; this.form.submit();} else { alert('".htmlentities($msg["multi_select_champ"],ENT_QUOTES,$charset)."'); }\"/>";
if ($opac_show_help) $search_form_perso.="<input type='button' class='bouton' name='?' value='$msg[search_help]' class='bouton' onClick='window.open(\"$base_path/help.php?whatis=search_multi\", \"search_help\", \"scrollbars=yes, toolbar=no, dependent=yes, width=400, height=400, resizable=yes\"); return false' />\n";
$search_form_perso.="		<br /><br />
		<div class='row'>
			!!already_selected_fields!!
		</div>
	</div>
	<input type='hidden' name='delete_field' value=''/>
	<input type='hidden' name='launch_search' value=''/>
	<input type='hidden' name='page' value='!!page!!'/>
	<input type='hidden' name='onglet_persopac' value='$onglet_persopac'/>
</form>
<script>ajax_parse_dom()</script>
<br />";

$search_form_perso_limitsearch="
<script src=\"includes/javascript/ajax.js\"></script>
<ul class='search_tabs'>!!others!!<li><a href=\"./index.php?search_type_asked=extended_search&onglet_persopac=\">".$msg["extended_search"]."</a></li>!!others2!!".
		($opac_show_onglet_help ? "<li><a href=\"./index.php?lvl=infopages&pagesid=$opac_show_onglet_help\">".$msg["search_help"]."</a></li>": '')."
</ul>
<div id='search_crl'></div>
<form class='form-$current_module' name='search_form' action='!!url!!' method='post'  onsubmit='return false'>
	<div class='form-contenu'>
		<div class='row'>
			!!already_selected_fields!!
		</div>
	</div>
	<input type='hidden' name='delete_field' value=''/>
	<input type='hidden' name='launch_search' value=''/>
	<input type='hidden' name='page' value='!!page!!'/>
	<input type='hidden' name='onglet_persopac' value='$onglet_persopac'/>
</form>
<script>ajax_parse_dom()</script>
<br />";