<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: suggestion_multi.tpl.php,v 1.6 2010-02-23 16:27:22 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

require_once($base_path.'/classes/suggestions_categ.class.php');

$multi_sug_form= "<div id='make_mul_sugg'>
<h3><span>".htmlentities($msg['empr_make_mul_sugg'], ENT_QUOTES, $charset)."</span></h3>
<div id='make_mul_sugg-container'>
<script src='$include_path/javascript/suggestion_multi.js' type='text/javascript'></script>
<script>
	function check_fields(nb_ligne){
	
	var retour=true;
	var qte_error = false;
	var txt_error = false;
	for(var i=0;i<nb_ligne;i++){
		if (document.getElementById('sugg_'+i)){
			if((document.getElementById('sugg_tit_'+i).disabled == true) && (i==0) ){
				alert(\"".$msg[sugg_no_field_fill]."\");
				return false;
			} else if(document.getElementById('sugg_tit_'+i).disabled == true) 
					break;		 
			var tit = document.getElementById('sugg_tit_'+i).value;
			var aut = document.getElementById('sugg_aut_'+i).value;
			var edi = document.getElementById('sugg_edi_'+i).value;
			var qte = document.getElementById('sugg_qte_'+i).value;
			var cod = document.getElementById('sugg_code_'+i).value;
					
			if(!tit || (!aut && !edi && !cod)){
				document.getElementById('sugg_'+i).className = 'erreur_saisie';
				retour=false;
				txt_error = true;
			} else if(isNaN(qte)){
				document.getElementById('sugg_'+i).className = 'erreur_saisie';
				retour=false;
				qte_error = true;
			} else {
				document.getElementById('sugg_'+i).className = '';
			}
		}
	}

	if(qte_error){
		alert('".$msg[empr_sugg_qte_error]."');
	} else if(txt_error){
		alert(\"".$msg[empr_sugg_ko]."\");
	}
		
	return retour;
}
</script>
<form action=\"empr.php\" method=\"post\" name=\"FormName\" onsubmit=\"return check_fields(document.getElementById('max_nblignes').value);\"> 
	<input type='hidden' name='act' id='act' />
	<input type='hidden' name='lvl' id='lvl' />
	<input type='hidden' name='max_nblignes' id='max_nblignes' value='!!max_ligne!!'/>
	<table class='tab_sug'>
		<tbody id='tableau_multi_sugg'>
		<tr>
			<th style='width:14%'>".htmlentities($msg["empr_sugg_tit"], ENT_QUOTES, $charset)."</th>
			<th style='width:11%'>".htmlentities($msg["empr_sugg_aut"], ENT_QUOTES, $charset)."</th>
			<th style='width:11%'>".htmlentities($msg["empr_sugg_edi"], ENT_QUOTES, $charset)."</th>
			<th style='width:10%'>".htmlentities($msg["empr_sugg_code"], ENT_QUOTES, $charset)."</th>
			<th style='width:6%'>".htmlentities($msg["empr_sugg_prix"], ENT_QUOTES, $charset)."</th>
			<th style='width:10%'>".htmlentities($msg["empr_sugg_url"], ENT_QUOTES, $charset)."</th>
			<th style='width:12%'>".htmlentities($msg["empr_sugg_comment"], ENT_QUOTES, $charset)."</th>
			<th style='width:9%'>".htmlentities($msg["empr_sugg_datepubli"], ENT_QUOTES, $charset)."</th>
			<th style='width:10%'>".htmlentities($msg["empr_sugg_src"], ENT_QUOTES, $charset)."</th>
			<th style='width:4%'>".htmlentities($msg["empr_sugg_qte"], ENT_QUOTES, $charset)."</th>
			<th style='width:3%'></th>
		</tr>
		!!ligne!!
		</tbody>
	</table>";
				
if ($opac_sugg_categ == '1' ) {	
	if (suggestions_categ::exists($opac_sugg_categ_default) ){
		$default_categ = $opac_sugg_categ_default;
	} else {
		$default_categ = '1';
	}
	//Selecteur de categories
	if ($acquisition_sugg_categ != '1') {
		$sel_categ="";
	} else {
		$tab_categ = suggestions_categ::getCategList();
		$sel_categ = "<select class='saisie-25em' id='num_categ' name='num_categ' >";
		foreach($tab_categ as $id_categ=>$lib_categ){
			$sel_categ.= "<option value='".$id_categ."' ";
			if ($id_categ==$default_categ) $sel_categ.= "selected='selected' "; 
			$sel_categ.= "> ";
			$sel_categ.= htmlentities($lib_categ, ENT_QUOTES, $charset)."</option>";
		}
		$sel_categ.= "</select>";
	}
	$multi_sug_form .= "	
		<div class='row'>
			<label class='etiquette'>".htmlentities($msg["acquisition_categ"], ENT_QUOTES, $charset)."</label>
			$sel_categ
		</div>
		<br />
	";
}
$multi_sug_form .= "	
	<input type='submit' class='bouton' name='save_multi_sugg' value='$msg[empr_sugg_save_multi]' onclick='this.form.act.value=\"save_multi_sugg\";this.form.lvl.value=\"make_multi_sugg\"' />
</form>
</div>
</div>";

?>