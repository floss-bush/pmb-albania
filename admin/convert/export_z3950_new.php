<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: export_z3950_new.php,v 1.4 2009-05-16 11:13:21 dbellamy Exp $

$base_path="../..";

$base_noheader = 1;
$base_nocheck = 1;
$base_nobody = 1;
$base_nosession = 1;

include($base_path."/includes/init.inc.php");
include($base_path."/admin/convert/export.class.php");
include($base_path."/admin/convert/xml_unimarc.class.php");
require_once($base_path."/classes/search.class.php");
require_once($base_path."/includes/isbn.inc.php");

$corresp=array(
"1016"=>	"42",	//Tous les champs
"4"=>		"1", 	// titre
"1003"=>	"2", 	// auteur
"1018"=>	"3",	// editeur
"31"=>		"23",	// année d'édition
"5"=>		"4",	// collection
"7"=>		"22",	// ISBN
"8"=>		"22",	// ISSN
"21"=>		"13"	// Mots clés
);

$corresp_op=array(
"1016"=>	"BOOLEAN",
"4"=>		"BOOLEAN",
"1003"=>	"BOOLEAN",
"1018"=>	"BOOLEAN",
"31"=>		"CONTAINS_AT_LEAST",
"5"=>		"BOOLEAN",
"7"=>		"STARTWITH",
"8"=>		"STARTWITH",
//"21"=>		"11",
"21"=>		"BOOLEAN"
);
function make_error($nerr,$err_message) {
	echo $nerr."@".$err_message."@";
	exit();
}

if (!@mysql_connect(SQL_SERVER,USER_NAME,USER_PASS)) make_error(1,"Could'nt connect to database server");
if (!@mysql_select_db(DATA_BASE)) make_error(2,"Database unknown");

//Commande envoyée
$command=$_GET["command"];
//Requete
$query=$_GET["query"];

function traite_val($value,$idf) {
	switch ($idf) {
		case "22":
			 if(isISBN($value)) {
					// si la saisie est un ISBN
					$code = formatISBN($value);
					// si échec, ISBN erroné on le prend sous cette forme
					if(!$code) $code = $value;
			    } else $code = $value;
			  $ret=$code;
			  break;
		default:
			$ret=$value;
			break;
	}
	return $ret;
}

function construct_query($query,$not,$level,$argn="",$oper="") {
	global $corresp,$search,$corresp_op;
	//La requête commence-t-elle par and, or ou and not ?
	$pos=strpos($query,"and not");
	if (($pos!==false)&&($pos==0)) {
		$ope="ex";
	} else {
		$pos=strpos($query,"or");
		if (($pos!==false)&&($pos==0)) {
			$ope="or";
		} else {
			$pos=strpos($query,"and");
			if (($pos!==false)&&($pos==0)) {
				$ope="and";
			} else $ope="";
		}
	}
	
	if ($ope!="") {
		//Si opérateur, recherche des arguments
		$arqs=array();
		preg_match("/^".($ope=="ex"?"and not":$ope)." arg".$level."!1\((.*)\) arg".$level."!2\((.*)\)$/",$query,$args);
		//print "/^".$ope." arg".$level."!1\((.*)\) arg".$level."!2\((.*)\)$/";
		//print_r($args);
		$return1=construct_query($args[1],0,$level+1,1,$ope);
		if (($oper)&&($return1)) {
			$inter="inter_".($level-2+$argn)."_f_".$return1;
			global $$inter;
			if (!$$inter)
				$$inter=$oper;
			//print $inter."=".$$inter."<br />";
		}
		$return2=construct_query($args[2],0,$level+1,2,$ope);
		if ($return2) {
			//print $level." ".$argn;
			if ($argn=="") $argn=2;
			$inter="inter_".($level-1+$argn)."_f_".$return2;
			global $$inter;
			if (!$$inter)
				$$inter=$ope;
			//print $inter."=".$$inter."<br />";
		}
		return;
	} else {
		$use=explode("=",$query);
		$idf=$corresp[$use[0]];
		if (!$idf) 
			make_error(3,"1=".$use[0]);
		else {
			$search[]="f_".$idf;
			$vals=array();
			$vals[0]=traite_val($use[1],$idf);
			$field="field_".(!$level?0:($level-2+$argn))."_f_".$idf;
			global $$field;
			$$field=$vals;
			$op="op_".(!$level?0:($level-2+$argn))."_f_".$idf;
			global $$op;
			$$op=$corresp_op[$use[0]];
			return $idf;
		}	
	}
	return;
}

switch ($command) {
	case "search":
		//print $query."<br />";
		construct_query($query,0,0);
		$s=new search();
		$table=$s->make_search();
		//print $s->make_human_query();
		$sql_query="select notice_id from $table limit 100";
		$resultat=@mysql_query($sql_query);
		echo "0@No errors@";
		echo @mysql_num_rows($resultat);
		while (list($id)=@mysql_fetch_row($resultat)) {
			echo "@$id";
		}
		break;
	case "get_notice":
		$id=$query;
		$e = new export(array($id));
		$e -> get_next_notice();
		$toiso = new xml_unimarc();
		$toiso->XMLtoiso2709_notice($e->notice);
		echo "0@No errors@";
		echo $toiso->notices_[0];
		break;
}

?>
