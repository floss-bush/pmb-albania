<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// | creator : Eric ROBERT                                                    |
// | modified : ...                                                           |
// +-------------------------------------------------+
// $Id: notice.inc.php,v 1.6 2007-03-10 08:50:38 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

include("$class_path/marc_table.class.php");

function convert_usmarc_unimarc_functions ($usmarc_func="") {
	if ($usmarc_func == "") return "";
	$usmarc_unimarc_func = array (
				"adp"=>"010",
				"ann"=>"020",
				"arr"=>"030",
				"art"=>"040",
				"asg"=>"050",
				"asn"=>"060",
				"aut"=>"070",
				"aui"=>"080",
				"aus"=>"090",
				"ant"=>"100",
				"bnd"=>"110",
				"bdd"=>"120",
				"bkd"=>"130",
				"bjd"=>"140",
				"bpd"=>"150",
				"bsl"=>"160",
				"cll"=>"170",
				"ctg"=>"180",
				"cns"=>"190",
				"chr"=>"200",
				"cmm"=>"210",
				"com"=>"220",
				"cmp"=>"230",
				"cmt"=>"240",
				"cnd"=>"250",
				"cph"=>"260",
				"crr"=>"270",
				"dte"=>"280",
				"dto"=>"290",
				"drt"=>"300",
				"dst"=>"310",
				"dnr"=>"320",
				"dub"=>"330",
				"edt"=>"340",
				"egr"=>"350",
				"etr"=>"360",
				"flm"=>"370",
				"frg"=>"380",
				"fmo"=>"390",
				"fnd"=>"400",
				"art"=>"410",
				"hnr"=>"420",
				"ilu"=>"430",
				"ill"=>"440",
				"ins"=>"450",
				"ive"=>"460",
				"ivr"=>"470",
				"lbt"=>"480",
				"lse"=>"490",
				"lso"=>"500",
				"ltg"=>"510",
				"lyr"=>"520",
				"mte"=>"530",
				"mon"=>"540",
				"nrt"=>"550",
				"org"=>"560",
				"oth"=>"570",
				"ppm"=>"580",
				"prf"=>"590",
				"pht"=>"600",
				"prt"=>"610",
				"pop"=>"620",
				"pro"=>"630",
				"pfr"=>"640",
				"pbl"=>"650",
				"rcp"=>"660",
				"rce"=>"670",
				"su"=>"675",
				"rbr"=>"680",
				"sce"=>"690",
				"scr"=>"700",
				"sec"=>"710",
				"sgn"=>"720",
				"trl"=>"730",
				"tyd"=>"740",
				"tyg"=>"750",
				"wde"=>"760",
				"wam"=>"770") ;
	if ($usmarc_unimarc_func[$usmarc_func]) return $usmarc_unimarc_func[$usmarc_func];
		else return "570" ;
	}
