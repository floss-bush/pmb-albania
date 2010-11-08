<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: custom_label_no_script.inc.php,v 1.8 2008-07-07 05:45:23 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$label_fmt[s0][label_name] 				= "Standard - 38.1x21.2mm - Avery J8651";
$label_fmt[s0][page_format] 				= "A4";
$label_fmt[s0][page_orientation]			= "P";
$label_fmt[s0][unit] 						= "mm";
$label_fmt[s0][label_grid_nb_per_row] 		= "5";
$label_fmt[s0][label_grid_nb_per_col]		= "13";
$label_fmt[s0][label_width] 				= "38.1";
$label_fmt[s0][label_height] 				= "21.2";
$label_fmt[s0][label_grid_from_top] 		= "11.3";
$label_fmt[s0][label_grid_from_left] 		= "5.5";
$label_fmt[s0][label_grid_h_spacing]		= "40.75";
$label_fmt[s0][label_grid_v_spacing] 		= "21.2";

$label_con[s0][content_type][0] 	= "cote";
$label_con[s0][comment][0] 			= htmlentities($msg[296], ENT_QUOTES, $charset);
$label_con[s0][width][0]			= "22.1";
$label_con[s0][height][0] 			= "18";
$label_con[s0][from_top][0] 		= "1.6";
$label_con[s0][from_left][0] 		= "10";
$label_con[s0][font][0] 			= "Courier";
$label_con[s0][font_size][0]	 	= "14";
$label_con[s0][font_style][0]	 	= "B";
$label_con[s0][font_color][0]	 	= "000000";
$label_con[s0][align][0] 			= "C";
$label_con[s0][rotation][0]			= "0";

$label_con[s0][content_type][1] 	= "image";
$label_con[s0][comment][1] 			= htmlentities($msg[image], ENT_QUOTES, $charset);
$label_con[s0][source][1]			= "pmb.png";
$label_con[s0][width][1]			= "8";
$label_con[s0][height][1] 			= "5";
$label_con[s0][from_top][1] 		= "2";
$label_con[s0][from_left][1] 		= "2";
$label_con[s0][rotation][1]			= "0";



$label_fmt[s1][label_name] 					= "Standard - 38.1x21.2mm - Rotation90° - Avery J8651";
$label_fmt[s1][page_format] 				= "A4";
$label_fmt[s1][page_orientation]			= "P";
$label_fmt[s1][unit] 						= "mm";
$label_fmt[s1][label_grid_nb_per_row] 		= "5";
$label_fmt[s1][label_grid_nb_per_col]		= "13";
$label_fmt[s1][label_width] 				= "38.1";
$label_fmt[s1][label_height] 				= "21.2";
$label_fmt[s1][label_grid_from_top] 		= "11.3";
$label_fmt[s1][label_grid_from_left] 		= "5.5";
$label_fmt[s1][label_grid_h_spacing]		= "40.75";
$label_fmt[s1][label_grid_v_spacing] 		= "21.2";

$label_con[s1][content_type][0] 	= "cote";
$label_con[s1][comment][0] 			= htmlentities($msg[296], ENT_QUOTES, $charset);
$label_con[s1][width][0]			= "18";
$label_con[s1][height][0] 			= "21";
$label_con[s1][from_top][0] 		= "1.5";
$label_con[s1][from_left][0] 		= "37";
$label_con[s1][font][0] 			= "Courier";
$label_con[s1][font_size][0]	 	= "14";
$label_con[s1][font_style][0]	 	= "B";
$label_con[s1][font_color][0]	 	= "000000";
$label_con[s1][align][0] 			= "C";
$label_con[s1][rotation][0]			= "90";

$label_con[s1][content_type][1] 	= "image";
$label_con[s1][comment][1] 			= htmlentities($msg[image], ENT_QUOTES, $charset);
$label_con[s1][source][1]			= "pmb.png";
$label_con[s1][width][1]			= "8";
$label_con[s1][height][1] 			= "5";
$label_con[s1][from_top][1] 		= "7.1";
$label_con[s1][from_left][1] 		= "7";
$label_con[s1][rotation][1]			= "90";


function getLabelFormatList() {

	global $label_fmt;	
	return $label_fmt;

}


function displayLabelFormat($label_id) {
	
	global $label_fmt, $msg, $charset;
	
	$page_size=array("A3","A4","A5","Letter","Legal");
	
	$r ="<div class='row'>
			<div class='left'>".htmlentities($msg[page_format], ENT_QUOTES, $charset)."</div>
			<div class='right' >
				<select id='page_format' name='page_format' >";

	foreach ($page_size as $size) {
		$r.="<option value='".$size."' ";
		if ($label_fmt[$label_id][page_format]==$size) {
			$r.="selected='selected' ";
		}
		$r.=">".htmlentities($size, ENT_QUOTES, $charset)."</option>";							
	}
	$r.="		</select>
			</div>
		</div>";
	

		$r.="<div class='row'>
			<div class='left'>".htmlentities($msg[page_orientation], ENT_QUOTES, $charset)."</div>
			<div class='right'>
				<select id='page_orientation' name='page_orientation' >";
	if ($label_fmt[$label_id][page_orientation]=='P') {
		$a=array('P', $msg[edit_cbgen_mep_portrait],'L', $msg[edit_cbgen_mep_paysage]);
	} else {
		$a=array('L', $msg[edit_cbgen_mep_paysage],'P', $msg[edit_cbgen_mep_portrait]);
	}
	$r.="			<option value='".$a[0]."' selected='selected' >".htmlentities($a[1], ENT_QUOTES, $charset)."</option>
					<option value='".$a[2]."'>".htmlentities($a[3], ENT_QUOTES, $charset)."</option>
				</select>
			</div>
		</div>";


	$r.="<input type='hidden' id='unit' name='unit' value='".$label_fmt[$label_id][unit]."' />";


	$r.="<div class='row'>
			<div class='left'>".htmlentities($msg[label_grid_nb_per_row], ENT_QUOTES, $charset)."</div>
			<div class='right' >
				<input type='text' id='label_grid_nb_per_row' name='label_grid_nb_per_row' class='saisie-5em' style='text-align:right;' value='".$label_fmt[$label_id][label_grid_nb_per_row]."' />
			</div>
		</div>";


	$r.="<div class='row'>
			<div class='left'>".htmlentities($msg[label_grid_nb_per_col], ENT_QUOTES, $charset)."</div>
			<div class='right' >
				<input type='text' id='label_grid_nb_per_col' name='label_grid_nb_per_col' class='saisie-5em' style='text-align:right;' value='".$label_fmt[$label_id][label_grid_nb_per_col]."' />
			</div>
		</div>";


	$r.="<div class='row'>
			<div class='left'>".htmlentities($msg[label_width]." (".$label_fmt[$label_id][unit].")", ENT_QUOTES, $charset)."</div>
			<div class='right' >
				<input type='text' id='label_width' name='label_width' class='saisie-5em' style='text-align:right;' value='".$label_fmt[$label_id][label_width]."' />
			</div>
		</div>";


	$r.="<div class='row'>
			<div class='left'>".htmlentities($msg[label_height]." (".$label_fmt[$label_id][unit].")", ENT_QUOTES, $charset)."</div>
			<div class='right' >
				<input type='text' id='label_height' name='label_height' class='saisie-5em' style='text-align:right;' value='".$label_fmt[$label_id][label_height]."' />
			</div>
		</div>";


	$r.="<div class='row'>
			<div class='left'>".htmlentities($msg[label_grid_from_top]." (".$label_fmt[$label_id][unit].")", ENT_QUOTES, $charset)."</div>
			<div class='right' >
				<input type='text' id='label_grid_from_top' name='label_grid_from_top' class='saisie-5em' style='text-align:right;' value='".$label_fmt[$label_id][label_grid_from_top]."' />
			</div>
		</div>";


	$r.="<div class='row'>
			<div class='left'>".htmlentities($msg[label_grid_from_left]." (".$label_fmt[$label_id][label_page_unit].")", ENT_QUOTES, $charset)."</div>
			<div class='right' >
				<input type='text' id='label_grid_from_left' name='label_grid_from_left'class='saisie-5em' style='text-align:right;' value='".$label_fmt[$label_id][label_grid_from_left]."' />
			</div>
		</div>";


	$r.="<div class='row'>
			<div class='left'>".htmlentities($msg[label_grid_h_spacing]." (".$label_fmt[$label_id][unit].")", ENT_QUOTES, $charset)."</div>
			<div class='right' >
				<input type='text' id='label_grid_h_spacing' name='label_grid_h_spacing' class='saisie-5em' style='text-align:right;' value='".$label_fmt[$label_id][label_grid_h_spacing]."' />
			</div>
		</div>";


	$r.="<div class='row'>
			<div class='left'>".htmlentities($msg[label_grid_v_spacing]." (".$label_fmt[$label_id][unit].")", ENT_QUOTES, $charset)."</div>
			<div class='right' >
				<input type='text' id='label_grid_v_spacing' name='label_grid_v_spacing' class='saisie-5em' style='text-align:right;' value='".$label_fmt[$label_id][label_grid_v_spacing]."' />
			</div>
		</div>";

	return $r;
}


function verifLabelFormat($label_id){

	global $label_fmt, $msg, $charset;
	
	
	$r = "
		var first_col = document.getElementById('first_col').value;
		var max_col = document.getElementById('label_grid_nb_per_row').value;
		if ( (first_col=='') || (max_col=='') || (isNaN(first_col)) || (isNaN(max_col)) ) {
			alert(\"".$msg[param_err_impr]."\");
			return false;
		}
		first_col = parseInt(first_col);
		max_col = parseInt(max_col);
		if ( (first_col < 1) || (max_col < 1) || (first_col > max_col) ) {
			alert(\"".$msg[param_err_impr]."\");
			return false;
		}";


	$r.= "
		var first_row = document.getElementById('first_row').value;
		var max_row = document.getElementById('label_grid_nb_per_col').value;
		if ( (first_row=='') || (max_row=='') || (isNaN(first_row)) || (isNaN(max_row)) ) {
			alert(\"".$msg[param_err_impr]."\");
			return false;
		}
		first_row = parseInt(first_row);
		max_row = parseInt(max_row);
		if ( (first_row < 1) || (max_row < 1) || (first_row > max_row) ) {
			alert(\"".$msg[param_err_impr]."\");
			return false;
		}";


	$r.= "
		var label_width = document.getElementById('label_width').value;
		if ( (label_width=='') || (isNaN(label_width)) || (parseFloat(label_width) <= 0) ) {
			alert(\"".$msg[param_err_impr]."\");
			return false;
		}";


	$r.= "
		var label_height = document.getElementById('label_height').value;
		if ( (label_height=='') || (isNaN(label_height)) || (parseFloat(label_height) <= 0) ) {
			alert(\"".$msg[param_err_impr]."\");
		return false;
	}";


	$r.= "
		var label_grid_from_top = document.getElementById('label_grid_from_top').value;
		if ( (label_grid_from_top=='') || (isNaN(label_grid_from_top)) || (parseFloat(label_grid_from_top) <= 0) ) {
			alert(\"".$msg[param_err_impr]."\");
		return false;
	}";


	$r.= "
		var label_grid_from_left = document.getElementById('label_grid_from_left').value;
		if ( (label_grid_from_left=='') || (isNaN(label_grid_from_left)) || (parseFloat(label_grid_from_left) <= 0) ) {
			alert(\"".$msg[param_err_impr]."\");
		return false;
	}";


	$r.= "
		var label_grid_h_spacing = document.getElementById('label_grid_h_spacing').value;
		if ( (label_grid_h_spacing=='') || (isNaN(label_grid_h_spacing)) || (parseFloat(label_grid_h_spacing) <= 0) ) {
			alert(\"".$msg[param_err_impr]."\");
		return false;
	}";


	$r.= "
		var label_grid_v_spacing = document.getElementById('label_grid_v_spacing').value;
		if ( (label_grid_v_spacing=='') || (isNaN(label_grid_v_spacing)) || (parseFloat(label_grid_v_spacing) <= 0) ) {
			alert(\"".$msg[param_err_impr]."\");
		return false;
	}";

	return $r;	
}



function getLabelContent($label_id) {
	
	global $label_con;
	return $label_con[$label_id];	
}



function displayLabelContent($label_id){

	global $label_con, $msg, $charset;
	
	$r="";
	foreach($label_con[$label_id][content_type] as $step=>$content_type) {

		eval('$r.=display_'.$content_type.'_content($label_id, $step);' );
	}
	return $r;
}


function display_cote_content($label_id, $step) {
	
	global $label_fmt, $label_con, $msg, $charset;

	$r = "<div class='row'>
			<input type='hidden' id='content_type[".$step."]' name='content_type[".$step."]' value='".$label_con[$label_id][content_type][$step]."' />
			<label class='etiquette'>".htmlentities($label_con[$label_id][comment][$step], ENT_QUOTES, $charset)."</label>
		</div>";


	$r.= "<div class='row'>
			<div class='left'>".htmlentities($msg[cote_width].' ('.$label_fmt[$label_id][unit].')', ENT_QUOTES, $charset)."</div>
			<div class='right'>
				<input type='text' id='content_value[".$step."][width]' name='content_value[".$step."][width]' class='saisie-5em' style='text-align:right;' value='".$label_con[$label_id][width][$step]."' />
			</div>
		</div>";


	$r.= "<div class='row'>
			<div class='left'>".htmlentities($msg[cote_height].' ('.$label_fmt[$label_id][unit].')', ENT_QUOTES, $charset)."</div>
			<div class='right'>
				<input type='text' id='content_value[".$step."][height]' name='content_value[".$step."][height]' class='saisie-5em' style='text-align:right;' value='".$label_con[$label_id][height][$step]."' />
			</div>
		</div>";
	
	
	$r.= "<div class='row'>
			<div class='left'>".htmlentities($msg[cote_from_top].' ('.$label_fmt[$label_id][unit].')', ENT_QUOTES, $charset)."</div>
			<div class='right'>
				<input type='text' id='content_value[".$step."][from_top]' name='content_value[".$step."][from_top]' class='saisie-5em' style='text-align:right;' value='".$label_con[$label_id][from_top][$step]."' />
			</div>
		</div>";


	$r.= "<div class='row'>
			<div class='left'>".htmlentities($msg[cote_from_left].' ('.$label_fmt[$label_id][unit].')', ENT_QUOTES, $charset)."</div>
			<div class='right'>
				<input type='text' id='content_value[".$step."][from_left]' name='content_value[".$step."][from_left]' class='saisie-5em' style='text-align:right;' value='".$label_con[$label_id][from_left][$step]."' />
			</div>
		</div>";


	$r.= "<div class='row'>
			<div class='left'>".htmlentities($msg[font], ENT_QUOTES, $charset)."</div>
			<div class='right'>
				<input type='hidden' id='content_value[".$step."][font]' name='content_value[".$step."][font]' value='".$label_con[$label_id][font][$step]."' />
				".htmlentities($label_con[$label_id][font][$step], ENT_QUOTES, $charset)."
			</div>
		</div>";


	$r.= "<div class='row'>
			<div class='left'>".htmlentities($msg[font_size], ENT_QUOTES, $charset)."</div>
			<div class='right'>
				<input type='text' id='content_value[".$step."][font_size]' name='content_value[".$step."][font_size]' class='saisie-5em' style='text-align:right;' value='".$label_con[$label_id][font_size][$step]."' />
			</div>
		</div>";


	$r.= "<div class='row'>
			<div class='left'>".htmlentities($msg[font_style], ENT_QUOTES, $charset)."</div>
			<div class='right'>
				<select id='content_value[".$step."][font_style]' name='content_value[".$step."][font_style]' >";		
	if ($label_con[$label_id][font_style][$step] == '') {
		$r.="		<option value='' selected='selected' >".htmlentities($msg[font_style_normal], ENT_QUOTES, $charset)."</option>
					<option value='B' >".htmlentities($msg[font_style_bold], ENT_QUOTES, $charset)."</option>";	
	} else {
		$r.="		<option value='' >".htmlentities($msg[font_style_normal], ENT_QUOTES, $charset)."</option>
					<option value='B' selected='selected' >".htmlentities($msg[font_style_bold], ENT_QUOTES, $charset)."</option>";	
	}	
	$r.= "		</select>
			</div>
		</div>";


	$r.= "<input type='hidden' id='content_value[".$step."][font_color]' name='content_value[".$step."][font_color]' value='".$label_con[$label_id][font_color][$step]."' />";


	$r.= "<div class='row'>
			<div class='left'>".htmlentities($msg[align], ENT_QUOTES, $charset)."</div>
			<div class='right'>
				<select id='content_value[".$step."][align]' name='content_value[".$step."][align]' >";
	switch ($label_con[$label_id][align][$step]) {
		case 'L' :	
			$r.="	<option value='C' >".htmlentities($msg[centered], ENT_QUOTES, $charset)."</option>
					<option value='L' selected='selected' >".htmlentities($msg[left], ENT_QUOTES, $charset)."</option>
					<option value='R' >".htmlentities($msg[right], ENT_QUOTES, $charset)."</option>
					<option value='J' >".htmlentities($msg[justified], ENT_QUOTES, $charset)."</option>";
			break;
		case 'R' :	
			$r.="	<option value='C' >".htmlentities($msg[centered], ENT_QUOTES, $charset)."</option>
					<option value='L' >".htmlentities($msg[left], ENT_QUOTES, $charset)."</option>
					<option value='R' selected='selected' >".htmlentities($msg[right], ENT_QUOTES, $charset)."</option>
					<option value='J' >".htmlentities($msg[justified], ENT_QUOTES, $charset)."</option>";
			break;
		case 'J' :	
			$r.="	<option value='C' >".htmlentities($msg[centered], ENT_QUOTES, $charset)."</option>
					<option value='L' >".htmlentities($msg[left], ENT_QUOTES, $charset)."</option>
					<option value='R' >".htmlentities($msg[right], ENT_QUOTES, $charset)."</option>
					<option value='J' selected='selected' >".htmlentities($msg[justified], ENT_QUOTES, $charset)."</option>";
		case 'C':
		default :
			$r.="	<option value='C' selected='selected' >".htmlentities($msg[centered], ENT_QUOTES, $charset)."</option>
					<option value='L' >".htmlentities($msg[left], ENT_QUOTES, $charset)."</option>
					<option value='R' >".htmlentities($msg[right], ENT_QUOTES, $charset)."</option>
					<option value='J' >".htmlentities($msg[justified], ENT_QUOTES, $charset)."</option>";
			break;
	}
	$r.= "		</select>
			</div>
		</div>";


	$r.= "<div class='row'>
			<div class='left'>".htmlentities($msg[rotation], ENT_QUOTES, $charset)."</div>
			<div class='right'>
				<input type='text' id='content_value[".$step."][rotation]' name='content_value[".$step."][rotation]' class='saisie-5em' style='text-align:right;' value='".$label_con[$label_id][rotation][$step]."' />
			</div>
		</div>";

	return $r;
}
	

function display_image_content($label_id, $step) {
	
	global $label_fmt, $label_con, $msg, $charset;

	$r = "<div class='row'>
			<input type='hidden' id='content_type[".$step."]' name='content_type[".$step."]' value='".$label_con[$label_id][content_type][$step]."' />
			<label class='etiquette'>".htmlentities($label_con[$label_id][comment][$step], ENT_QUOTES, $charset)."</label>
		</div>";


	$r.= "<div class='row'>
			<div class='left'>".htmlentities($msg[image_source], ENT_QUOTES, $charset)."</div>
			<div class='right'>
				<input type='text' id='content_value[".$step."][source]' name='content_value[".$step."][source]' class='saisie-10em' value='".$label_con[$label_id][source][$step]."' />
			</div>
		</div>";


	$r.= "<div class='row'>
			<div class='left'>".htmlentities($msg[image_width].' ('.$label_fmt[$label_id][unit].')', ENT_QUOTES, $charset)."</div>
			<div class='right'>
				<input type='text' id='content_value[".$step."][width]' name='content_value[".$step."][width]' class='saisie-5em' style='text-align:right;' value='".$label_con[$label_id][width][$step]."' />
			</div>
		</div>";


	$r.= "<div class='row'>
			<div class='left'>".htmlentities($msg[image_height].' ('.$label_fmt[$label_id][unit].')', ENT_QUOTES, $charset)."</div>
			<div class='right'>
				<input type='text' id='content_value[".$step."][height]' name='content_value[".$step."][height]' class='saisie-5em' style='text-align:right;' value='".$label_con[$label_id][height][$step]."' />
			</div>
		</div>";
	
	
	$r.= "<div class='row'>
			<div class='left'>".htmlentities($msg[image_from_top].' ('.$label_fmt[$label_id][unit].')', ENT_QUOTES, $charset)."</div>
			<div class='right'>
				<input type='text' id='content_value[".$step."][from_top]' name='content_value[".$step."][from_top]' class='saisie-5em' style='text-align:right;' value='".$label_con[$label_id][from_top][$step]."' />
			</div>
		</div>";


	$r.= "<div class='row'>
			<div class='left'>".htmlentities($msg[image_from_left].' ('.$label_fmt[$label_id][unit].')', ENT_QUOTES, $charset)."</div>
			<div class='right'>
				<input type='text' id='content_value[".$step."][from_left]' name='content_value[".$step."][from_left]' class='saisie-5em' style='text-align:right;' value='".$label_con[$label_id][from_left][$step]."' />
			</div>
		</div>";

	$r.= "<div class='row'>
			<div class='left'>".htmlentities($msg[rotation], ENT_QUOTES, $charset)."</div>
			<div class='right'>
				<input type='text' id='content_value[".$step."][rotation]' name='content_value[".$step."][rotation]' class='saisie-5em' style='text-align:right;' value='".$label_con[$label_id][rotation][$step]."' />
			</div>
		</div>";

	return $r;
}

	
function verifLabelContent($label_id){

	global $label_con, $msg, $charset;
	
	$r="";
	foreach($label_con[$label_id][content_type] as $step=>$content_type) {

		eval('$r.=verif_'.$content_type.'_content($label_id, $step);' );
	}
	return $r;
}


function  verif_cote_content($label_id, $step) {

	global $label_fmt, $label_con, $msg, $charset;

	$r = "
		var width = document.getElementById('content_value[".$step."][width]').value;	
		if ( (width=='') || (isNaN(width)) || (parseFloat(width) <= 0) ) {
			alert(\"".$msg[param_err_impr]."\");
		return false;
	}";
	

	$r.= "
		var height = document.getElementById('content_value[".$step."][height]').value;	
		if ( (height=='') || (isNaN(height)) || (parseFloat(height) <= 0) ) {
			alert(\"".$msg[param_err_impr]."\");
		return false;
	}";


	$r.= "
		var from_top = document.getElementById('content_value[".$step."][from_top]').value;	
		if ( (from_top=='') || (isNaN(from_top)) || (parseFloat(from_top) <= 0) ) {
			alert(\"".$msg[param_err_impr]."\");
		return false;
	}";


	$r.= "
		var from_left = document.getElementById('content_value[".$step."][from_left]').value;	
		if ( (from_left=='') || (isNaN(from_left)) || (parseFloat(from_left) <= 0) ) {
			alert(\"".$msg[param_err_impr]."\");
		return false;
	}";


	$r.= "
		var font_size = document.getElementById('content_value[".$step."][font_size]').value;	
		if ( (font_size=='') || (isNaN(font_size)) || (parseInt(font_size) < 1) ) {
			alert(\"".$msg[param_err_impr]."\");
		return false;
	}";


	$r.= "
		var rotation = document.getElementById('content_value[".$step."][rotation]').value;	
		if ( (rotation=='') || (isNaN(rotation)) ) {
			alert(\"".$msg[param_err_impr]."\");
		return false;
	}";

	return $r;	
}


function  verif_image_content($label_id, $step) {

	global $label_fmt, $label_con, $msg, $charset;

	$r = "
		var width = document.getElementById('content_value[".$step."][width]').value;	
		if ( (width=='') || (isNaN(width)) || (parseFloat(width) <= 0) ) {
			alert(\"".$msg[param_err_impr]."\");
		return false;
	}";
	

	$r.= "
		var height = document.getElementById('content_value[".$step."][height]').value;	
		if ( (height=='') || (isNaN(height)) || (parseFloat(height) <= 0) ) {
			alert(\"".$msg[param_err_impr]."\");
		return false;
	}";


	$r.= "
		var from_top = document.getElementById('content_value[".$step."][from_top]').value;	
		if ( (from_top=='') || (isNaN(from_top)) || (parseFloat(from_top) <= 0) ) {
			alert(\"".$msg[param_err_impr]."\");
		return false;
	}";


	$r.= "
		var from_left = document.getElementById('content_value[".$step."][from_left]').value;	
		if ( (from_left=='') || (isNaN(from_left)) || (parseFloat(from_left) <= 0) ) {
			alert(\"".$msg[param_err_impr]."\");
		return false;
	}";


	$r.= "
		var rotation = document.getElementById('content_value[".$step."][rotation]').value;	
		if ( (rotation=='') || (isNaN(rotation)) ) {
			alert(\"".$msg[param_err_impr]."\");
		return false;
	}";

	return $r;	
}


function print_cote(&$target, $content_value, $content_src='') {
	
	global $dbh;
	
	$q = "select expl_cote from exemplaires where expl_id = '".$content_src."' ";
	$r = mysql_query($q, $dbh);
	$cote = "";
	if (mysql_num_rows($r)) {
		$row_cote = mysql_fetch_row($r);
		$tab_cote = explode(" ", rtrim(ltrim($row_cote[0])) );
		$str_cote = implode("\n", $tab_cote);
	}
	
	$target->SetFont($content_value[font],$content_value[font_style] ,$content_value[font_size]);
	$r = 0; $g=-1; $b=-1;
	switch (strlen($content_value['font_color'])) {
		case '6':
			$r = hexdec(substr($content_value[font_color],0,2));
			$g = hexdec(substr($content_value[font_color],2,2));
			$b = hexdec(substr($content_value[font_color],4,2));
			break;
		case '2':
			$r = hexdec(substr($content_value[font_color],0,2));
			break;
		default:
			break;
	}
	$target->SetTextColor($r, $g, $b);
	$target->SetXY($target->GetStickX()+$content_value[from_left], $target->GetStickY()+$content_value[from_top]);
	$target->Rotate($content_value[rotation], $target->GetStickX()+$content_value[from_left],$target->GetStickY()+$content_value[from_top] ) ;
	$target->MultiCell($content_value[width], ($content_value[font_size]*25.4/72), $str_cote, 0,  $content_value[align]);
	$target->Rotate(0);
//	$target->Rect($target->GetStickX(), $target->GetStickY(), 38.1, 21.2 );
}


function print_image(&$target, $content_value, $content_src='') {
		
		if($content_value[source] == '') return;
		
		$target->Rotate($content_value[rotation], $target->GetStickX()+$content_value[from_left], $target->GetStickY()+$content_value[from_top] );
		$target->Image("../../../images/".$content_value[source], $target->GetStickX()+$content_value[from_left], $target->GetStickY()+$content_value[from_top], $content_value[width], $content_value[height]);
		$target->Rotate(0);
	
}


?>