<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sel_searcher_templates.tpl.php,v 1.3 2009-11-30 11:46:26 dbellamy Exp $

// page de switch recherche notice

$form_query = "
<div class='row' style='margin:0px 5px 0px 5px;'>
	<form class='form-$current_module' id='form_query' name='form_query' method='post' action='!!action_url!!' onSubmit='return test_form(this)'>
		<h3>!!menu_query!!</h3>
		<div class='row' />
		<div class='row' >
			<input type='text' id='elt_query' name='elt_query' value='!!elt_query!!' class='saisie-30em'/>
			<input type='button' class='bouton_small' value='X' onclick=\"document.forms['form_query'].elt_query.value=''; return false;\"/>
			<!-- extended_query -->
			<input type='submit' class='bouton_small' value='$msg[142]' />
			<div class='right'>
				<!-- bt_add -->
			</div>
		</div>
		<input type='hidden' name='etat' value='first_search' />
	</form>
	<div class='row'>
	<!-- result_query -->
	<!-- pager_query -->
	</div>
</div>
<script type='text/javascript' src='./javascript/tablist.js'></script>
<script type='text/javascript'>

	function test_form(form) {
		if (form.elt_query.value.length == 0) {
			form.elt_query.value='*';
			return true;
		}
		return true;
	}
	document.forms['form_query'].elements['elt_query'].focus();
</script>
";

$nav_bar = "<ul class='sel_navbar' ><!-- other_query --></ul>";
$other_query = "<li !!class!! ><a href=\"".$base_url."&typ_query=!!typ_query!!\" >!!lib!!</a></li>";

//Debut liste notices
$elt_b_list_notice = "
<div class='row'>
	<div class='colonne80'>".$begin_result_liste."!!research!!</div>
	<div class='colonne10'>&nbsp;</div>
	<div class='colonne10'>".htmlentities($msg['acquisition_nb_expl'], ENT_QUOTES, $charset)."</div>
</div>"; 
//Ligne liste notices
$elt_r_list_notice = "
<div class='row' style='margin-left:5px;' >
	<div class='colonne80'>!!result!!</div>
	<div class='colonne10'>&nbsp;</div>
	<div class='colonne10'>!!nb_expl!!</div>
</div>"; 

//Debut liste perios pour bulletins
$aut_b_list_bulletin = "
<div class='row'>
	<div class='colonne80'>".$begin_result_liste."!!research!!</div>
	<div class='colonne10'>&nbsp;</div>
	<div class='colonne10'>".htmlentities($msg['acquisition_nb_bull'], ENT_QUOTES, $charset)."</div>
</div>"; 
//Ligne liste perios pour bulletins
$aut_r_list_bulletin = "
<div class='row' style='margin-left:5px;'>
	<div class='colonne80'>!!result!!</div>
	<div class='colonne10'>&nbsp;</div>
	<div class='colonne10'>!!nb_bull!!</div>
</div>"; 

//Debut liste bulletins
$elt_b_list_bulletin = "
<div class='row'>
	<div class='colonne80'>".$begin_result_liste."!!research!!</div>
	<div class='colonne10'>&nbsp;</div>
	<div class='colonne10'>".htmlentities($msg['acquisition_nb_expl'], ENT_QUOTES, $charset)."</div>
</div>"; 
//Ligne liste bulletins
$elt_r_list_bulletin = "
<div class='row' style='margin-left:5px;'>
	<div class='colonne80'>!!result!!</div>
	<div class='colonne10'>&nbsp;</div>
	<div class='colonne10'>!!nb_expl!!</div>
</div>"; 

//Debut liste abonnements
$elt_b_list_abt = "
<div class='row'>
	<div class='colonne80'>".$begin_result_liste."!!research!!</div>
	<div class='colonne10'>&nbsp;</div>
	<div class='colonne10'>".htmlentities($msg['acquisition_abt_ech'], ENT_QUOTES, $charset)."</div>
</div>"; 
//Ligne liste abonnements
$elt_r_list_abt = "
<div class='row' style='margin-left:5px;'>
	<div class='colonne80'>!!result!!</div>
	<div class='colonne10'>&nbsp;</div>
	<div class='colonne10'>!!aff_date_echeance!!</div>
</div>"; 

//Debut liste frais
$elt_b_list_frais = "
<div class='row'>
	<div class='colonne80'>!!research!!</div>
	<div class='colonne10'>&nbsp;</div>
	<div class='colonne10'>".htmlentities($msg['acquisition_frais_montant'], ENT_QUOTES, $charset)."</div>
</div>
<br /><br />";
//Ligne liste frais
$elt_r_list_frais = "
<div class='row' style='margin-left:5px;'>
	<div class='colonne80'>!!result!!</div>
	<div class='colonne10'>&nbsp;</div>
	<div class='colonne10'>!!lib_montant!!</div>
</div>"; 	

//Debut liste articles
$elt_b_list_article = "
<div class='row'>
	<div class='colonne80'>".$begin_result_liste."!!research!!</div>
</div>"; 
//Ligne liste articles
$elt_r_list_article = "
<div class='row' style='margin-left:5px;' >
	<div class='colonne80'>!!result!!</div>
</div>"; 

?>