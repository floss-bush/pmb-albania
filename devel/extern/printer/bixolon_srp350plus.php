<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: bixolon_srp350plus.php,v 1.3 2010-03-02 09:52:36 ngantier Exp $

//$xml=utf8_decode($_POST["xml"]);
if (get_magic_quotes_gpc()) {
    $xml=stripslashes($_POST["xml"]);
} else {
	$xml=$_POST["xml"];
}

require_once("parser.inc.php");
//print $xml;
$pos_X=$pos_Y=0;
$exec_function['STYLE']="function_style";
$exec_function['TEXT']="function_text";

$handle = printer_open("BIXOLON SRP-350plus");

printer_start_doc($handle, "Mon Document");
printer_start_page($handle);

//font par défaut
$font['defaut']['x']=24;
$font['defaut']['y']=$font['defaut']['x']/2;
$font['defaut']['font'] = printer_create_font("Arial",$font['defaut']['x'], $font['defaut']['y'], PRINTER_FW_NORMAL, false, false, false, 0);
$font_active='defaut';


$param_xml=_parser_text_($xml,$exec_function, "FIELDS");

printer_end_page($handle);
printer_end_doc($handle);
printer_close($handle);
				
function function_style ($param){
	global $font;
	// taille de la police
	if($param['SIZE'])	$font[$param['NAME']]['x']=$param['SIZE'];
	else $font[$param['NAME']]['x']=24;
	$font[$param['NAME']]['y']=$font[$param['NAME']]['x']/2;
		
	// poids de la police: plus ou moins gras
	switch($param['FONT_WEIGHT']) {
		case "THIN": $font_weight=PRINTER_FW_THIN; break;
		case "ULTRALIGHT": $font_weight=PRINTER_FW_ULTRALIGHT; break;
		case "LIGHT": $font_weight=PRINTER_FW_LIGHT; break;			
		case "NORMAL": $font_weight=PRINTER_FW_NORMAL; break;			
		case "MEDIUM": $font_weight=PRINTER_FW_MEDIUM; break;			
		case "BOLD": $font_weight=PRINTER_FW_BOLD; break;			
		case "ULTRABOLD": $font_weight=PRINTER_FW_ULTRABOLD; break;			
		case "HEAVY": $font_weight=PRINTER_FW_HEAVY; break;		
		default:$font_weight=PRINTER_FW_NORMAL; break;
	}
	$italic=$param['ITALIC'];
	$underline=$param['UNDERLINE'];	
	$strikeout=$param['STIKEOUT'];
	$orientaton=$param['ORIENTATION'];

	
	// création de la police
	$font[$param['NAME']]['font'] = printer_create_font($param['NAME'],$font[$param['NAME']]['x'], $font[$param['NAME']]['y'], 
		$font_weight, $italic,$underline, $strikeout,$orientaton);
	//print_r ($font[$param['NAME']]);
		
}

function function_text($param){
	global $handle,$pos_X,$pos_Y,$font_active,$font;
	$pos_X=0;
	if(!$param['X'] && !$param['Y']) $pos_Y+=($font[$font_active]['y']);
	// Si changement de font
	if($param['STYLE'] && array_key_exists($param['STYLE'],$font)) {
		$font_active=$param['STYLE'];
		printer_select_font($handle,$font[$font_active]['font']);		
	}	
	if(!$param['X'] && !$param['Y']) $pos_Y+=($font[$font_active]['y'])*1.2;
	if($param['X']) $pos_X=$param['X'];
	if($param['Y']) $pos_Y=$param['Y'];
	printer_draw_text($handle, $param['value'], $pos_X, $pos_Y);
}
?>

