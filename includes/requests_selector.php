<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: requests_selector.php,v 1.1 2008-02-20 18:05:09 dbellamy Exp $

$base_path=".";
$base_noheader=1;
$base_nobody=1;
$base_nocheck=1;
require_once("includes/init.inc.php");
require_once("$class_path/requester.class.php");
require_once("$class_path/marc_table.class.php");

header("Content-Type: text/html; charset=$charset");
$start=stripslashes($datas);
$start = str_replace("*","%",$start);

$rqt = new requester();

switch($completion):
	case 'req_fiel':
		// récupération des champs accessibles à partir de l'univers défini
		if (!$req_univ) die;
		$t = $rqt->getFieldUnivList($req_univ);
		
		$array_selector = array();
		foreach($t as $k=>$v) {
			$array_selector[$k]['t']=$v['desc_t'];
			$array_selector[$k]['f']=$v['desc_f'];
		}
		
		$origine = "ARRAY";
		break;
	default: 
		break;
endswitch;


switch ($origine):
	case 'ARRAY':
		$i=1;
		while(list($index, $value) = each($array_selector)) {
			if (strtolower(substr($value['f'],0,strlen($start)))==strtolower($start)) {
			echo "<div id='l".$id.$i."'";
			if ($autfield) echo " autid='".$index."'";
			echo " style='cursor:default;font-family:arial,helvetica;font-size:10px;width:100%' onClick='ajax_set_datas(\"l".$id.$i."\",\"$id\")'>".$value['t']."-".$value['f']."</div>";
			$i++;	
			}
		}
		break;
	default: 
		break;
endswitch;


