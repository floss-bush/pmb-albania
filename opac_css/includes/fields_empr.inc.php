<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: fields_empr.inc.php,v 1.23.2.2 2011-09-30 07:21:06 dgoron Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$aff_list_empr=array("text"=>"aff_text_empr","list"=>"aff_list_empr","query_list"=>"aff_query_list_empr","date_box"=>"aff_date_box_empr","comment"=>"aff_comment_empr","external"=>"aff_external_empr","url"=>"aff_url_empr","resolve"=>"aff_resolve_empr");
$aff_list_empr_search=array("text"=>"aff_text_empr_search","list"=>"aff_list_empr_search","query_list"=>"aff_query_list_empr_search","date_box"=>"aff_date_box_empr_search","comment"=>"aff_comment_empr_search","external"=>"aff_external_empr_search","url"=>"aff_url_empr_search","resolve"=>"aff_resolve_empr_search");
$chk_list_empr=array("text"=>"chk_text_empr","list"=>"chk_list_empr","query_list"=>"chk_query_list_empr","date_box"=>"chk_date_box_empr","comment"=>"chk_comment_empr","external"=>"chk_external_empr","url"=>"chk_url_empr","resolve"=>"chk_resolve_empr");
$val_list_empr=array("text"=>"val_text_empr","list"=>"val_list_empr","query_list"=>"val_query_list_empr","date_box"=>"val_date_box_empr","comment"=>"val_comment_empr","external"=>"val_external_empr","url"=>"val_url_empr","resolve"=>"val_resolve_empr");
$type_list_empr=array("text"=>$msg["parperso_text"],"list"=>$msg["parperso_choice_list"],"query_list"=>$msg["parperso_query_choice_list"],"date_box"=>$msg["parperso_date"],"comment"=>$msg["parperso_comment"],"external"=>$msg["parperso_external"],"url"=>$msg["parperso_url"],"resolve"=>$msg["parperso_resolve"]);
$options_list_empr=array("text"=>"options_text.php","list"=>"options_list.php","query_list"=>"options_query_list.php","date_box"=>"options_date_box.php","comment"=>"options_comment.php","external"=>"options_external.php","url"=>"options_url.php","resolve"=>"options_resolve.php");

function chk_datatype($field,$values,&$check_datatype_message) {
	global $chk_type_list;
	global $msg;
	
	if (((!isset($values))||((count($values)==1)&&($values[0]=="")))&&($field[MANDATORY]!=1)) return $values;
	for ($i=0; $i<count($values); $i++) {
		$chk_message="";
		eval("\$val=".$chk_type_list[$field[DATATYPE]]."(stripslashes(\$values[\$i]),\$chk_message);");
		if ($chk_message) {
			$check_datatype_message=sprintf($msg["parperso_chk_datatype"],$field[NAME],$chk_message);
		}
		$values[$i]=addslashes($val);
	}
	return $values;
}

function format_output($field,$values) {
	global $format_list;
	for ($i=0; $i<count($values); $i++) {
		eval("\$val=".$format_list[$field[DATATYPE]]."(\$values[\$i]);");
		$values[$i]=$val;
	}
	return $values;
}

function aff_date_box_empr($field,&$check_scripts) {
	global $charset;
	global $msg;
	
	$values=$field[VALUES];
	$d=explode("-",$values[0]);
	if (!checkdate($d[1],$d[2],$d[0])) {
		$val=date("Y-m-d",time());
		$val_popup=date("Ymd",time());
	} else {
		$val_popup=$d[0].$d[1].$d[2];
		$val=$values[0];
	}
	$ret="<input type='hidden' name='".$field[NAME]."[]' value='$val' />
				<input class='bouton' type='button' name='".$field[NAME]."_lib' value='".formatdate($val_popup)."' onClick=\"window.open('./select.php?what=calendrier&caller='+this.form.name+'&date_caller=".$val_popup."&param1=".$field[NAME]."[]&param2=".$field[NAME]."_lib&auto_submit=NO&date_anterieure=YES', 'date_".$field[NAME]."', 'toolbar=no, dependent=yes, width=250, height=300, resizable=yes')\"   />";
	if ($field[MANDATORY]==1) $check_scripts.="if (document.forms[0].elements[\"".$field[NAME]."[]\"].value==\"\") return cancel_submit(\"".sprintf($msg["parperso_field_is_needed"],$field[ALIAS])."\");\n";
	return $ret;
}

function aff_date_box_empr_search($field,&$check_scripts,$varname) {
	global $charset;
	global $msg;
	
	$values=$field[VALUES];
	$d=explode("-",$values[0]);
	if (!checkdate($d[1],$d[2],$d[0])) {
		$val=date("Y-m-d",time());
		$val_popup=date("Ymd",time());
	} else {
		$val_popup=$d[0].$d[1].$d[2];
		$val=$values[0];
	}
	$ret="<input type='hidden' name='".$varname."[]' value='$val' />
				<input class='bouton' type='button' name='".$varname."_lib' value='".formatdate($val_popup)."' onClick=\"window.open('./select.php?what=calendrier&caller='+this.form.name+'&date_caller=".$val_popup."&param1=".$varname."[]&param2=".$varname."_lib&auto_submit=NO&date_anterieure=YES', 'date_".$varname."', 'toolbar=no, dependent=yes, width=250, height=300, resizable=yes')\"   />";
	return $ret;
}

function chk_date_box_empr($field,&$check_message) {
	$name=$field[NAME];
	global $$name;
	$val=$$name;
	
	$check_datatype_message="";
	$val_1=chk_datatype($field,$val,$check_datatype_message);
	if ($check_datatype_message) {
		$check_message=$check_datatype_message;
		return 0;
	}
	$$name=$val_1;
	return 1;
}

function val_date_box_empr($field,$value) {
	global $charset;

	if ($value[0]=="0000-00-00") $value[0]="";

	if ($value[0]) $value=format_output($field,$value);

	return $value[0];
}

function aff_text_empr($field,&$check_scripts) {
	global $charset;
	global $msg;
	
	$options=$field[OPTIONS][0];
	$values=$field[VALUES];
	$ret="<input id=\"".$field[NAME]."\" type=\"text\" size=\"".$options[SIZE][0][value]."\" maxlength=\"".$options[MAXSIZE][0][value]."\" name=\"".$field[NAME]."[]\" value=\"".htmlentities($values[0],ENT_QUOTES,$charset)."\">";
	if ($field[MANDATORY]==1) $check_scripts.="if (document.forms[0].elements[\"".$field[NAME]."[]\"].value==\"\") return cancel_submit(\"".sprintf($msg["parperso_field_is_needed"],$field[ALIAS])."\");\n";
	return $ret;
}

function aff_text_empr_search($field,&$check_scripts,$varname) {
	global $charset;
	global $msg;
	
	$options=$field[OPTIONS][0];
	$values=$field[VALUES];
	$ret="<input id=\"".$varname."\" type=\"text\" size=\"".$options[SIZE][0][value]."\" name=\"".$varname."[]\" value=\"".htmlentities($values[0],ENT_QUOTES,$charset)."\">";
	return $ret;
}

function chk_text_empr($field,&$check_message) {
	$name=$field[NAME];
	global $$name;
	$val=$$name;
	
	$check_datatype_message="";
	$val_1=chk_datatype($field,$val,$check_datatype_message);
	if ($check_datatype_message) {
		$check_message=$check_datatype_message;
		return 0;
	}
	$$name=$val_1;
	return 1;
}

function val_text_empr($field,$value) {
	global $charset,$pmb_perso_sep;

	$value=format_output($field,$value);
	if (!$value) $value=array();
	return implode($pmb_perso_sep,$value);
}

function aff_comment_empr($field,&$check_scripts) {
	global $charset;
	global $msg;
	
	$options=$field[OPTIONS][0];
	$values=$field[VALUES];
	$ret="<textarea id=\"".$field[NAME]."\" cols=\"".$options[COLS][0][value]."\"  rows=\"".$options[ROWS][0][value]."\" maxlength=\"".$options[MAXSIZE][0][value]."\" name=\"".$field[NAME]."[]\" wrap=virtual>".htmlentities($values[0],ENT_QUOTES,$charset)."</textarea>";
	if ($field[MANDATORY]==1) $check_scripts.="if (document.forms[0].elements[\"".$field[NAME]."[]\"].value==\"\") return cancel_submit(\"".sprintf($msg["parperso_field_is_needed"],$field[ALIAS])."\");\n";
	return $ret;
}

function aff_comment_empr_search($field,&$check_scripts,$varname) {
	global $charset;
	global $msg;
	
	$options=$field[OPTIONS][0];
	$values=$field[VALUES];
	$ret="<textarea id=\"".$varname."\" cols=\"".$options[COLS][0][value]."\"  rows=\"".$options[ROWS][0][value]."\" name=\"".$varname."[]\" wrap=virtual>".htmlentities($values[0],ENT_QUOTES,$charset)."</textarea>";
	return $ret;
}

function chk_comment_empr($field,&$check_message) {
	$name=$field[NAME];
	global $$name;
	$val=$$name;
	
	$check_datatype_message="";
	$val_1=chk_datatype($field,$val,$check_datatype_message);
	if ($check_datatype_message) {
		$check_message=$check_datatype_message;
		return 0;
	}
	$$name=$val_1;
	return 1;
}

function val_comment_empr($field,$value) {
	global $charset;

	$value=format_output($field,$value);

	return $value[0];
}

function aff_list_empr($field,&$check_scripts) {
	global $charset;
	$_custom_prefixe_=$field["PREFIX"];

	$options=$field[OPTIONS][0];
	$values=$field[VALUES];
	if ($values=="") $values=array();
	$ret="<select id=\"".$field[NAME]."\" name=\"".$field[NAME];
	$ret.="[]";
	$ret.="\" ";
	if ($options[MULTIPLE][0][value]=="yes") $ret.="multiple";
	$ret.=">\n";
	if (($options[UNSELECT_ITEM][0][VALUE]!="")||($options[UNSELECT_ITEM][0][value]!="")) {
		$ret.="<option value=\"".htmlentities($options[UNSELECT_ITEM][0][VALUE],ENT_QUOTES,$charset)."\">".htmlentities($options[UNSELECT_ITEM][0][value],ENT_QUOTES,$charset)."</option>\n";
	}
	$requete="select ".$_custom_prefixe_."_custom_list_value, ".$_custom_prefixe_."_custom_list_lib from ".$_custom_prefixe_."_custom_lists where ".$_custom_prefixe_."_custom_champ=".$field[ID]." order by ordre";
	//echo $requete;
	$resultat=mysql_query($requete);
	if ($resultat) {
		$i=0;
		while ($r=mysql_fetch_array($resultat)) {
			$options[ITEMS][0][ITEM][$i][VALUE]=$r[$_custom_prefixe_."_custom_list_value"];
			$options[ITEMS][0][ITEM][$i][value]=$r[$_custom_prefixe_."_custom_list_lib"];
			$i++;
		}
	}
	for ($i=0; $i<count($options[ITEMS][0][ITEM]); $i++) {
		$ret.="<option value=\"".htmlentities($options[ITEMS][0][ITEM][$i][VALUE],ENT_QUOTES,$charset)."\"";
		$as=array_search($options[ITEMS][0][ITEM][$i][VALUE],$values);
		if (($as!==FALSE)&&($as!==NULL)) $ret.=" selected"; 
		$ret.=">".htmlentities($options[ITEMS][0][ITEM][$i][value],ENT_QUOTES,$charset)."</option>\n";
	}
	$ret.= "</select>\n";
	return $ret;
}

function aff_list_empr_search($field,&$check_scripts,$varname) {
	global $charset;
	$_custom_prefixe_=$field["PREFIX"];
	
	$options=$field[OPTIONS][0];
	$values=$field[VALUES];
	if ($values=="") $values=array();
	$ret="<select id=\"".$varname."\" name=\"".$varname;
	$ret.="[]";
	$ret.="\" ";
	$ret.="multiple";
	$ret.=">\n";
	if (($options[UNSELECT_ITEM][0][VALUE]!="")||($options[UNSELECT_ITEM][0][value]!="")) {
		$ret.="<option value=\"".htmlentities($options[UNSELECT_ITEM][0][VALUE],ENT_QUOTES,$charset)."\">".htmlentities($options[UNSELECT_ITEM][0][value],ENT_QUOTES,$charset)."</option>\n";
	}
	$requete="select ".$_custom_prefixe_."_custom_list_value, ".$_custom_prefixe_."_custom_list_lib from ".$_custom_prefixe_."_custom_lists where ".$_custom_prefixe_."_custom_champ=".$field[ID]." order by ordre";
	$resultat=mysql_query($requete);
	if ($resultat) {
		$i=0;
		while ($r=mysql_fetch_array($resultat)) {
			$options[ITEMS][0][ITEM][$i][VALUE]=$r[$_custom_prefixe_."_custom_list_value"];
			$options[ITEMS][0][ITEM][$i][value]=$r[$_custom_prefixe_."_custom_list_lib"];
			$i++;
		}
	}
	for ($i=0; $i<count($options[ITEMS][0][ITEM]); $i++) {
		$ret.="<option value=\"".htmlentities($options[ITEMS][0][ITEM][$i][VALUE],ENT_QUOTES,$charset)."\"";
		$as=array_search($options[ITEMS][0][ITEM][$i][VALUE],$values);
		if (($as!==FALSE)&&($as!==NULL)) $ret.=" selected"; 
		$ret.=">".htmlentities($options[ITEMS][0][ITEM][$i][value],ENT_QUOTES,$charset)."</option>\n";
	}
	$ret.= "</select>\n";
	return $ret;
}


function chk_list_empr($field,&$check_message) {
	global $charset;
	global $msg;
	
	$name=$field[NAME];
	global $$name;
	$val=$$name;
	if ($field[MANDATORY]==1) {
	if ((!isset($val))||((count($val)==1)&&($val[0]==""))) {
			$check_message=sprintf($msg["parperso_field_is_needed"],$field[ALIAS]);
			return 0;
		}
	}
	
	$check_datatype_message="";
	$val_1=chk_datatype($field,$val,$check_datatype_message);
	if ($check_datatype_message) {
		$check_message=$check_datatype_message;
		return 0;
	}
	$$name=$val_1;
	
	return 1;
}

function val_list_empr($field,$val) {
	global $charset,$pmb_perso_sep;
	global $options_;
	$_custom_prefixe_=$field["PREFIX"];

	if ($val=="") return "";
	
	if (!$options_[$_custom_prefixe_][$field[ID]]) {
		$requete="select ".$_custom_prefixe_."_custom_list_value, ".$_custom_prefixe_."_custom_list_lib from ".$_custom_prefixe_."_custom_lists where ".$_custom_prefixe_."_custom_champ=".$field[ID]." order by ordre";
		$resultat=mysql_query($requete);
		if ($resultat) {
			while ($r=mysql_fetch_array($resultat)) {
				$options_[$_custom_prefixe_][$field[ID]][$r[$_custom_prefixe_."_custom_list_value"]]=$r[$_custom_prefixe_."_custom_list_lib"];
			}
		}
	}
	if (!is_array($options_[$_custom_prefixe_][$field[ID]])) return ""; 
	$val_r=array_flip($val);
	$val_c=array_intersect_key($options_[$_custom_prefixe_][$field[ID]],$val_r);
	if ($val_c=="") $val_c=array();
	$val_=implode($pmb_perso_sep,$val_c);
	return $val_;
}

function aff_query_list_empr($field,&$check_scripts) {
	global $charset;
	$values=$field[VALUES];
	if ($values=="") $values=array();
	$options=$field[OPTIONS][0];
	$ret="<select id=\"".$field[NAME]."\" name=\"".$field[NAME];
	$ret.="[]";
	$ret.="\" ";
	if ($options[MULTIPLE][0][value]=="yes") $ret.="multiple";
	$ret.=">\n";
	if (($options[UNSELECT_ITEM][0][VALUE]!="")||($options[UNSELECT_ITEM][0][value]!="")) {
		$ret.="<option value=\"".htmlentities($options[UNSELECT_ITEM][0][VALUE],ENT_QUOTES,$charset)."\">".htmlentities($options[UNSELECT_ITEM][0][value],ENT_QUOTES,$charset)."</option>\n";
	}
	$resultat=mysql_query($options[QUERY][0][value]);
	while ($r=mysql_fetch_row($resultat)) {
		$ret.="<option value=\"".htmlentities($r[0],ENT_QUOTES,$charset)."\"";
		$as=array_search($r[0],$values);
		if (($as!==FALSE)&&($as!==NULL)) $ret.=" selected"; 
		$ret.=">".htmlentities($r[1],ENT_QUOTES,$charset)."</option>\n";
	}
	$ret.= "</select>\n";
	return $ret;
}

function aff_query_list_empr_search($field,&$check_scripts,$varname) {
	global $charset;
	$values=$field[VALUES];
	if ($values=="") $values=array();
	$options=$field[OPTIONS][0];
	$ret="<select id=\"".$varname."\" name=\"".$varname;
	$ret.="[]";
	$ret.="\" ";
	$ret.="multiple";
	$ret.=">\n";
	if (($options[UNSELECT_ITEM][0][VALUE]!="")||($options[UNSELECT_ITEM][0][value]!="")) {
		$ret.="<option value=\"".htmlentities($options[UNSELECT_ITEM][0][VALUE],ENT_QUOTES,$charset)."\">".htmlentities($options[UNSELECT_ITEM][0][value],ENT_QUOTES,$charset)."</option>\n";
	}
	$resultat=mysql_query($options[QUERY][0][value]);
	while ($r=mysql_fetch_row($resultat)) {
		$ret.="<option value=\"".htmlentities($r[0],ENT_QUOTES,$charset)."\"";
		$as=array_search($r[0],$values);
		if (($as!==FALSE)&&($as!==NULL)) $ret.=" selected"; 
		$ret.=">".htmlentities($r[1],ENT_QUOTES,$charset)."</option>\n";
	}
	$ret.= "</select>\n";
	return $ret;
}

function chk_query_list_empr($field,&$check_message) {
	global $charset;
	global $msg;
	
	$name=$field[NAME];
	global $$name;
	$val=$$name;
	if ($field[MANDATORY]==1) {
	if ((!isset($val))||((count($val)==1)&&($val[0]==""))) {
			$check_message=sprintf($msg["parperso_field_is_needed"],$field[ALIAS]);
			return 0;
		}
	}
	
	$check_datatype_message="";
	$val_1=chk_datatype($field,$val,$check_datatype_message);
	if ($check_datatype_message) {
		$check_message=$check_datatype_message;
		return 0;
	}
	$$name=$val_1;
	
	return 1;
}

function val_query_list_empr($field,$val) {
	global $charset,$pmb_perso_sep;

	if ($val=="") return "";
	$val_c="";
	if (($field["OPTIONS"][0]["FIELD0"][0]["value"])&&($field["OPTIONS"][0]["FIELD1"][0]["value"])&&($field["OPTIONS"][0]["OPTIMIZE_QUERY"][0]["value"]=="yes")) {
		$val_ads=array_map("addslashes",$val);
		$requete="select * from (".$field[OPTIONS][0][QUERY][0][value].") as sub1 where ".$field["OPTIONS"][0]["FIELD0"][0]["value"]." in (BINARY '".implode("',BINARY '",$val_ads)."')";
		$resultat=mysql_query($requete);
		if ($resultat) {
			while ($r=mysql_fetch_row($resultat)) {
				$val_c[]=$r[1];
			}
		}
	} else {
		$resultat=mysql_query($field[OPTIONS][0][QUERY][0][value]);
		while ($r=mysql_fetch_row($resultat)) {
			$options_[$r[0]]=$r[1];
		}
	
		for ($i=0; $i<count($val); $i++) {
			$val_c[$i]=$options_[$val[$i]];
		}
	}
	
	if ($val_c=="") $val_c=array();
	$val_=implode($pmb_perso_sep,$val_c);
	return $val_;
}

function aff_external_empr($field,&$check_scripts) {
	global $charset;
	global $msg;
	
	$options=$field[OPTIONS][0];
	$values=$field[VALUES];
	//Recherche du libellé
	$vallib=$values[0];
	if ($options["QUERY"][0]["value"]) {
		$rvalues=mysql_query(str_replace("!!id!!",$values[0],$options["QUERY"][0]["value"]));
		if ($rvalues) {
			$vallib=@mysql_result($rvalues,0,0);
		}
	}
	$ret="<input id=\"".$field["NAME"]."\" type=\"hidden\" name=\"".$field["NAME"]."[]\" value=\"".htmlentities($values[0],ENT_QUOTES,$charset)."\">";
	if (!$options["HIDE"][0]["value"]) {
		$ret.="<input id=\"".$field["NAME"]."_lib\" type=\"text\" readonly='readonly' size=\"".$options["SIZE"][0]["value"]."\" maxlength=\"".$options["MAXSIZE"][0]["value"]."\" name=\"".$field["NAME"]."_lib[]\" value=\"".htmlentities($vallib,ENT_QUOTES,$charset)."\">";
	}
	$ret.="&nbsp;<input type='button' id='".$field["NAME"]."_button' name='".$field["NAME"]."_button' class='bouton' value='".(($vallib&&($options["HIDE"][0]["value"]))?htmlentities($vallib,ENT_QUOTES,$charset):($options["BUTTONTEXT"][0]["value"]?htmlentities($options["BUTTONTEXT"][0]["value"],ENT_QUOTES,$charset):$msg["parperso_external_browse"]))."' onClick='openPopUp(\"".$options["URL"][0]["value"]."?field_val=".$field["NAME"]."&"."field_lib=".($options["HIDE"][0]["value"]?$field["NAME"]."_button":$field["NAME"]."_lib")."\",\"w_".$field["NAME"]."\",".($options["WIDTH"][0]["value"]?$options["WIDTH"][0]["value"]:"400").",".($options["HEIGHT"][0]["value"]?$options["HEIGHT"][0]["value"]:"600").",-2,-2,\"infobar=no, status=no, scrollbars=yes, menubar=no\");'/>";
	if ($options["DELETE"][0]["value"]) $ret.="&nbsp;<input type='button' class='bouton' value='X' onClick=\"document.getElementById('".$field["NAME"]."').value=''; document.getElementById('".($options["HIDE"][0]["value"]?$field["NAME"]."_button":$field["NAME"]."_lib")."').value='".($options["HIDE"][0]["value"]?($options["BUTTONTEXT"][0]["value"]?htmlentities($options["BUTTONTEXT"][0]["value"],ENT_QUOTES,$charset):$msg["parperso_external_browse"]):"")."';\"/>";
	if ($field["MANDATORY"]==1) $check_scripts.="if (document.forms[0].elements[\"".$field["NAME"]."[]\"].value==\"\") return cancel_submit(\"".sprintf($msg["parperso_field_is_needed"],$field["ALIAS"])."\");\n";
	return $ret;
}

function aff_external_empr_search($field,&$check_scripts,$varname) {
	global $charset;
	global $msg;
	
	$options=$field[OPTIONS][0];
	$values=$field[VALUES];
	//Recherche du libellé
	$vallib=$values[0];
	if ($options["QUERY"][0]["value"]) {
		$rvalues=mysql_query(str_replace("!!id!!",$values[0],$options["QUERY"][0]["value"]));
		if ($rvalues) {
			$vallib=@mysql_result($rvalues,0,0);
		}
	}
	$ret="<input id=\"".$varname."\" type=\"hidden\" name=\"".$varname."[]\" value=\"".htmlentities($values[0],ENT_QUOTES,$charset)."\">";
	$ret.="<input id=\"".$varname."_lib\" type=\"text\" size=\"".$options[SIZE][0][value]."\" name=\"".$varname."_lib[]\" readonly=\"readonly\" value=\"".htmlentities($vallib,ENT_QUOTES,$charset)."\">";
	$ret.="&nbsp;<input type='button' name='".$varname."_button' class='bouton' value='".($options["BUTTONTEXT"][0]["value"]?$options["BUTTONTEXT"][0]["value"]:$msg["parperso_external_browse"])."' onClick='openPopUp(\"".$options["URL"][0]["value"]."?field_val=".$varname."&"."field_lib=".$varname."_lib"."\",\"w_".$varname."\",".($options["WIDTH"][0]["value"]?$options["WIDTH"][0]["value"]:"400").",".($options["HEIGHT"][0]["value"]?$options["HEIGHT"][0]["value"]:"600").",-2,-2,\"\");'/>";
	return $ret;
}

function chk_external_empr($field,&$check_message) {
	$name=$field[NAME];
	global $$name;
	$val=$$name;
	
	$check_datatype_message="";
	$val_1=chk_datatype($field,$val,$check_datatype_message);
	if ($check_datatype_message) {
		$check_message=$check_datatype_message;
		return 0;
	}
	$$name=$val_1;
	return 1;
}

function val_external_empr($field,$value) {
	global $charset;

	$options=$field[OPTIONS][0];
	$value=format_output($field,$value);
	//Calcul du libelle
	if ($options["QUERY"][0]["value"]) {
		$rvalues=mysql_query(str_replace("!!id!!",$value[0],$options["QUERY"][0]["value"]));
		if ($rvalues) {
			return @mysql_result($rvalues,0,0);
		}
	}
	return $value[0];
}

function aff_url_empr($field,&$check_scripts){
	global $charset;
	global $msg;
	
	$options=$field[OPTIONS][0];
	$values=$field[VALUES];
	$afield_name = $field["ID"];
	$ret = "";
	$count = 0;
	if (!$values) {
		$values = array("");
	}
	foreach ($values as $avalues) {
		$avalues = explode("|",$avalues);
		$ret.="lien : <input id=\"".$field[NAME]."_link\" type=\"text\" size=\"".$options[SIZE][0][value]."\" name=\"".$field[NAME]."[link][]\" value=\"".htmlentities($avalues[0],ENT_QUOTES,$charset)."\">";
		$ret.=" <input class=\"bouton\" type=\"button\" value=\"vérifier\" onclick='cp_chklnk($count);'>";
		$ret.=" libelle : <input id=\"".$field[NAME]."_linkname\" type=\"text\" size=\"".$options[SIZE][0][value]."\" name=\"".$field[NAME]."[linkname][]\" value=\"".htmlentities($avalues[1],ENT_QUOTES,$charset)."\">";
		if ($options[REPEATABLE][0][value] && !$count)
			$ret.='<input class="bouton" type="button" value="+" onclick="add_custom_url_(\''.$afield_name.'\', \''.addslashes($field[NAME]).'\', \''.addslashes($options[SIZE][0][value]).'\')">';
		$ret.="<br />";
		$count++;
	}
	$ret.= "
	<script type='text/javascript'>
		function cp_chklnk(indice){
			var links = document.forms.notice['url[link][]'];
			var link = links[indice].value;
 			var check = new http_request();
			if(check.request('./ajax.php?module=ajax&categ=chklnk',true,'&link='+link)){
				alert(check.get_text());
			}else{
				var result = check.get_text();
				if(result == '200') {
					//impec, on print un petit message de confirmation
					alert('".$msg['persofield_url_valid']."');
				}else{
					//problème...
					alert('".$msg['persofield_url_invalid']."'+result);
				}
			}
		}
	</script>";
	if ($options[REPEATABLE][0][value]) {
		$ret.='<input id="customfield_text_'.$afield_name.'" type="hidden" name="customfield_text_'.$afield_name.'" value="'.(count($values)).'">';
		//$ret.='<input class="bouton" type="button" value="+" onclick="add_custom_text_(\''.$afield_name.'\', \''.addslashes($field[NAME]).'\', \''.addslashes($options[SIZE][0][value]).'\', \''.addslashes($options[MAXSIZE][0][value]).'\')">';
		$ret .= '<div id="spaceformorecustomfieldtext_'.$afield_name.'"></div>';
		$ret.="<script>
			function add_custom_url_(field_id, field_name, field_size) {
				document.getElementById('customfield_text_'+field_id).value = document.getElementById('customfield_text_'+field_id).value * 1 + 1;
		        count = document.getElementById('customfield_text_'+field_id).value;
				var link_label = document.createTextNode('lien : ');
				var chklnk = document.createElement('input');
				chklnk.setAttribute('type','button');
				chklnk.setAttribute('value','vérifier');
				chklnk.setAttribute('class','bouton');
				chklnk.setAttribute('onclick','cp_chklnk(count);');
				var link = document.createElement('input');
		        link.setAttribute('name',field_name+'[link][]');
		        link.setAttribute('type','text');
		        link.setAttribute('size',field_size);
		        link.setAttribute('value','');
				var lib_label = document.createTextNode(' libelle : ');
				var lib = document.createElement('input');
		        lib.setAttribute('name',field_name+'[linkname][]');
		        lib.setAttribute('type','text');
		        lib.setAttribute('size',field_size);
		        lib.setAttribute('value','');
		        space=document.createElement('br');
				document.getElementById('spaceformorecustomfieldtext_'+field_id).appendChild(link_label);
				document.getElementById('spaceformorecustomfieldtext_'+field_id).appendChild(link);
				document.getElementById('spaceformorecustomfieldtext_'+field_id).appendChild(chklnk);
				document.getElementById('spaceformorecustomfieldtext_'+field_id).appendChild(lib_label);
				document.getElementById('spaceformorecustomfieldtext_'+field_id).appendChild(lib);
				document.getElementById('spaceformorecustomfieldtext_'+field_id).appendChild(space);
			}
		</script>";
	}
	if ($field[MANDATORY]==1) $check_scripts.="if (document.forms[0].elements[\"".$field[NAME]."[]\"].value==\"\") return cancel_submit(\"".sprintf($msg["parperso_field_is_needed"],$field[ALIAS])."\");\n";
	return $ret;
}

function aff_url_empr_search($field,&$check_scripts,$varname) {
	global $charset;
	global $msg;
	
	$options=$field[OPTIONS][0];
	$values=$field[VALUES];
	$ret="<input id=\"".$varname."\" type=\"text\" size=\"".$options[SIZE][0][value]."\" name=\"".$varname."[]\" value=\"".htmlentities($values[0],ENT_QUOTES,$charset)."\">";
	return $ret;
}

function chk_url_empr($field,&$check_message) {
	$name=$field[NAME];
	global $$name;
	$val=$$name;
	$value = array();
	for($i=0;$i<sizeof($val['link']);$i++){
		if($val['link'][$i] != "")
			$value[] = $val['link'][$i]."|".$val['linkname'][$i];
	}
	$val = $value;
	$check_datatype_message="";
	$val_1=chk_datatype($field,$val,$check_datatype_message);
	if ($check_datatype_message) {
		$check_message=$check_datatype_message;
		return 0;
	}
	$$name=$val_1;
	return 1;
}

function val_url_empr($field,$value) {
	global $charset;
	$cut = $field[OPTIONS][0][MAXSIZE][0][value];
	$values=format_output($field,$value);
	$ret = "";
	$without = "";
	for ($i=0;$i<count($values);$i++){
		$val = explode("|",$values[$i]);
		if ($val[1])$lib = $val[1];
		else $lib = ($cut && strlen($val[0]) > $cut ? substr($val[0],0,$cut)."[...]" : $val[0] );
		if( $ret != "") $ret.= " / ";	
		$ret .= "<a href='".$val[0]."' target='_blank'>".htmlentities($lib,ENT_QUOTES,$charset)."</a>";
		if( $without != "") $without.= " / ";
		$without .= $lib;
	}
	return array("ishtml" => true, "value"=>$ret, "withoutHTML" =>$without);
}

function aff_resolve_empr($field,&$check_scripts){
	global $charset;
	global $msg;
	
	$options=$field[OPTIONS][0];
	$values=$field[VALUES];
	$afield_name = $field["ID"];
	$ret = "";
	$count = 0;
	if (!$values) {
		$values = array("");
	}
	foreach ($values as $avalues) {
		$avalues = explode("|",$avalues);
		$ret.="<input id='".$field[NAME]."' type='text' size='".$options[SIZE][0][value]."' name='".$field[NAME]."[id][]' value='".htmlentities($avalues[0],ENT_QUOTES,$charset)."'>";
		$ret.="&nbsp;<select name='".$field[NAME]."[resolve][]'>";
		foreach($options[RESOLVE] as $elem){
			$ret.= "
			<option value='".$elem[ID]."' ".($avalues[1] == $elem[ID] ? "selected=selected":"").">".htmlentities($elem[LABEL],ENT_QUOTES,$charset)."</option>";
		}
		$ret.="
		</select>";
		if ($options[REPEATABLE][0][value] && !$count)
			$ret.='<input class="bouton" type="button" value="+" onclick="add_custom_text_(\''.$afield_name.'\', \''.addslashes($field[NAME]).'\', \''.addslashes($options[SIZE][0][value]).'\', \''.addslashes($options[MAXSIZE][0][value]).'\')">';
		$ret.="<br />";
		$count++;
	}
	if ($options[REPEATABLE][0][value]) {
		$ret.='<input id="customfield_text_'.$afield_name.'" type="hidden" name="customfield_text_'.$afield_name.'" value="'.(count($values)).'">';
		//$ret.='<input class="bouton" type="button" value="+" onclick="add_custom_text_(\''.$afield_name.'\', \''.addslashes($field[NAME]).'\', \''.addslashes($options[SIZE][0][value]).'\', \''.addslashes($options[MAXSIZE][0][value]).'\')">';
		$ret .= '<div id="spaceformorecustomfieldtext_'.$afield_name.'"></div>';
		$ret.="<script>
			function add_custom_text_(field_id, field_name, field_size, field_maxlen) {
				document.getElementById('customfield_text_'+field_id).value = document.getElementById('customfield_text_'+field_id).value * 1 + 1;
		        count = document.getElementById('customfield_text_'+field_id).value;
				f_aut0 = document.createElement('input');
		        f_aut0.setAttribute('name',field_name+'[id][]');
		        f_aut0.setAttribute('type','text');
		        f_aut0.setAttribute('size',field_size);
		        f_aut0.setAttribute('maxlen',field_size);
		        f_aut0.setAttribute('value','');
		        space=document.createElement('br');
				var select = document.createElement('select');
				select.setAttribute('name',field_name+'[resolve][]');
				";
				foreach($options[RESOLVE] as $elem){
					$ret.="
				var option = document.createElement('option');
				option.setAttribute('value','".$elem[ID]."');
				var text = document.createTextNode('".htmlentities($elem[LABEL],ENT_QUOTES,$charset)."');
				option.appendChild(text);
				select.appendChild(option);
";
				}
				$ret.="
				document.getElementById('spaceformorecustomfieldtext_'+field_id).appendChild(f_aut0);
				document.getElementById('spaceformorecustomfieldtext_'+field_id).appendChild(document.createTextNode(' '));
				document.getElementById('spaceformorecustomfieldtext_'+field_id).appendChild(select);				
				document.getElementById('spaceformorecustomfieldtext_'+field_id).appendChild(space);

			}
		</script>";
	}
	if ($field[MANDATORY]==1) $check_scripts.="if (document.forms[0].elements[\"".$field[NAME]."[]\"].value==\"\") return cancel_submit(\"".sprintf($msg["parperso_field_is_needed"],$field[ALIAS])."\");\n";
	return $ret;
}

function chk_resolve_empr($field,&$check_message) {
	$name=$field[NAME];
	global $$name;
	$val=$$name;
	$value = array();
	for($i=0;$i<sizeof($val['id']);$i++){
		if($val['id'][$i] != "")
			$value[] = $val['id'][$i]."|".$val['resolve'][$i];
	}
	$val = $value;

	$check_datatype_message="";
	$val_1=chk_datatype($field,$val,$check_datatype_message);
	if ($check_datatype_message) {
		$check_message=$check_datatype_message;
		return 0;
	}
	$$name=$val_1;
	return 1;
}

function val_resolve_empr($field,$value) {
	global $charset,$pmb_perso_sep;
	
$options=$field[OPTIONS][0];
	$values=format_output($field,$value);
	$ret = "";
	for ($i=0;$i<count($values);$i++){
		$val = explode("|",$values[$i]);
		if(count($val)>1){
			$id =$val[0];
			foreach ($options[RESOLVE] as $res){
				if($res[ID] == $val[1]){
					$label = $res[LABEL];
					$url= $res[value];
					break;
				}
			}
			$link = str_replace("!!id!!",$id,$url);
			if( $ret != "") $ret.= " / ";
			//$ret.= "<a href='$link' target='_blank'>".htmlentities($link,ENT_QUOTES,$charset)."</a>";
			$ret.= htmlentities($label,ENT_QUOTES,$charset)." : $id <a href='$link' target='_blank'><img align='center' src='images/globe.gif' alt='$link' title='link'/></a>";
		}else{
			$without=implode($pmb_perso_sep,$value);
		}
	}
	return array("ishtml" => true, "value"=>$ret,"withoutHTML"=> $without);
}

function aff_resolve_empr_search($field,&$check_scripts,$varname){
	global $charset;
	global $msg;
	
	$options=$field[OPTIONS][0];
	$values=$field[VALUES];
	$ret="<input id='".$varname."' type='text' size='".$options[SIZE][0][value]."' name='".$varname."[]' value='".htmlentities($values[0],ENT_QUOTES,$charset)."'>";
	return $ret;
}