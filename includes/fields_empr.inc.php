<?php
// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: fields_empr.inc.php,v 1.52 2011-01-20 16:14:55 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$aff_list_empr=array("text"=>"aff_text_empr","list"=>"aff_list_empr","query_list"=>"aff_query_list_empr","date_box"=>"aff_date_box_empr","comment"=>"aff_comment_empr","external"=>"aff_external_empr","url"=>"aff_url_empr");
$aff_list_empr_search=array("text"=>"aff_text_empr_search","list"=>"aff_list_empr_search","query_list"=>"aff_query_list_empr_search","date_box"=>"aff_date_box_empr_search","comment"=>"aff_comment_empr_search","external"=>"aff_external_empr_search","url"=>"aff_url_empr_search");
$aff_filter_list_empr=array("text"=>"aff_filter_text_empr","list"=>"aff_filter_list_empr","query_list"=>"aff_filter_query_list_empr","date_box"=>"aff_filter_date_box_empr","comment"=>"aff_filter_comment_empr","external"=>"aff_filter_external_empr","url"=>"aff_filter_url_empr");
$chk_list_empr=array("text"=>"chk_text_empr","list"=>"chk_list_empr","query_list"=>"chk_query_list_empr","date_box"=>"chk_date_box_empr","comment"=>"chk_comment_empr","external"=>"chk_external_empr","url"=>"chk_url_empr");
$val_list_empr=array("text"=>"val_text_empr","list"=>"val_list_empr","query_list"=>"val_query_list_empr","date_box"=>"val_date_box_empr","comment"=>"val_comment_empr","external"=>"val_external_empr","url"=>"val_url_empr");
$type_list_empr=array("text"=>$msg["parperso_text"],"list"=>$msg["parperso_choice_list"],"query_list"=>$msg["parperso_query_choice_list"],"date_box"=>$msg["parperso_date"],"comment"=>$msg["parperso_comment"],"external"=>$msg["parperso_external"],"url"=>$msg["parperso_url"]);
$options_list_empr=array("text"=>"options_text.php","list"=>"options_list.php","query_list"=>"options_query_list.php","date_box"=>"options_date_box.php","comment"=>"options_comment.php","external"=>"options_external.php","url"=>"options_url.php");

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

//fonction de découpage d'une chaine trop longue
function cutlongwords($valeur) {
	global $charset;
	$valeur=str_replace("\n"," ",$valeur);
	if (strlen($valeur)>=20) {
    	$pos=strrpos(substr($valeur,0,20)," ");
    	if ($pos) {
    		$valeur=substr($valeur,0,$pos+1)."...";
    	} else $valeur=substr($valeur,0,20)."...";
    } 
    return $valeur;		
}

function aff_date_box_empr($field,&$check_scripts) {
	global $charset;
	global $msg;
	global $base_path;
	
	$values=$field[VALUES];
	$d=explode("-",$values[0]);
	
	$options=$field[OPTIONS][0];
	
	if ((!@checkdate($d[1],$d[2],$d[0]))&&(!$options["DEFAULT_TODAY"][0]["value"])) {
		$val=date("Y-m-d",time());
		$val_popup=date("Ymd",time());
	} else if ((!@checkdate($d[1],$d[2],$d[0]))&&($options["DEFAULT_TODAY"][0]["value"])) {
		$val_popup="";
		$val="";
	} else {
		$val_popup=$d[0].$d[1].$d[2];
		$val=$values[0];
	}
	$ret="<input type='hidden' name='".$field[NAME]."[]' value='$val' />
				<input class='bouton' type='button' name='".$field[NAME]."_lib' value='".($val_popup?formatdate($val_popup):htmlentities($msg["parperso_nodate"],ENT_QUOTES,$charset))."' onClick=\"openPopUp('".$base_path."/select.php?what=calendrier&caller='+this.form.name+'&date_caller=".$val_popup."&param1=".$field[NAME]."[]&param2=".$field[NAME]."_lib&auto_submit=NO&date_anterieure=YES', 'date_".$field[NAME]."', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\" />&nbsp;
				<input class='bouton' type='button' value='X' onClick='this.form.elements[\"".$field[NAME]."_lib\"].value=\"".htmlentities($msg["parperso_nodate"],ENT_QUOTES,$charset)."\"; this.form.elements[\"".$field[NAME]."[]\"].value=\"\"; '/>";
	if ($field[MANDATORY]==1) $check_scripts.="if (document.forms[0].elements[\"".$field[NAME]."[]\"].value==\"\") return cancel_submit(\"".sprintf($msg["parperso_field_is_needed"],$field[ALIAS])."\");\n";
	return $ret;
}

function aff_date_box_empr_search($field,&$check_scripts,$varname) {
	global $charset;
	global $msg;
	
	$values=$field[VALUES];
	$d=explode("-",$values[0]);
	if (!@checkdate($d[1],$d[2],$d[0])) {
		$val=date("Y-m-d",time());
		$val_popup=date("Ymd",time());
	} else {
		$val_popup=$d[0].$d[1].$d[2];
		$val=$values[0];
	}
	$ret="<input type='hidden' name='".$varname."[]' value='$val' />
				<input class='bouton' type='button' name='".$varname."_lib' value='".formatdate($val_popup)."' onClick=\"openPopUp('./select.php?what=calendrier&caller='+this.form.name+'&date_caller=".$val_popup."&param1=".$varname."[]&param2=".$varname."_lib&auto_submit=NO&date_anterieure=YES', 'date_".$varname."', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\"   />";
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

	if ($value) $value=format_output($field,$value);
	
	return $value[0];
}

function aff_text_empr($field,&$check_scripts) {
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
		$ret.="<input id=\"".$field[NAME]."\" type=\"text\" size=\"".$options[SIZE][0][value]."\" maxlength=\"".$options[MAXSIZE][0][value]."\" name=\"".$field[NAME]."[]\" value=\"".htmlentities($avalues,ENT_QUOTES,$charset)."\">";
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
		        f_aut0.setAttribute('name',field_name+'[]');
		        f_aut0.setAttribute('type','text');
		        f_aut0.setAttribute('size',field_size);
		        f_aut0.setAttribute('maxlen',field_size);
		        f_aut0.setAttribute('value','');
		        space=document.createElement('br');
				document.getElementById('spaceformorecustomfieldtext_'+field_id).appendChild(f_aut0);
				document.getElementById('spaceformorecustomfieldtext_'+field_id).appendChild(space);
			}
		</script>";
	}
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
	global $charset;

	$value=format_output($field,$value);
	if (!$value) $value=array();
	return implode("/",$value);
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

function aff_list_empr($field,&$check_scripts,$script="") {
	global $charset;
	$_custom_prefixe_=$field["PREFIX"];
	
	$options=$field[OPTIONS][0];
	$values=$field[VALUES];
	if ($values=="") $values=array();
	
	if ($options["AUTORITE"][0]["value"]!="yes") {
		if ($options["CHECKBOX"][0]["value"]=="yes"){
			if ($options[MULTIPLE][0][value]=="yes") $type = "checkbox";
			else $type = "radio";
			$requete="select ".$_custom_prefixe_."_custom_list_value, ".$_custom_prefixe_."_custom_list_lib from ".$_custom_prefixe_."_custom_lists where ".$_custom_prefixe_."_custom_champ=".$field[ID]." order by ordre";
			$resultat=mysql_query($requete);	
			if ($resultat) {
				$i=0;
				$ret="";
				while ($r=mysql_fetch_array($resultat)) {
					$r[$_custom_prefixe_."_custom_list_value"];
					$r[$_custom_prefixe_."_custom_list_lib"];
					$ret.= "<input id='".$field[NAME]."_$i' type='$type' name='".$field[NAME]."[]' ".(in_array($r[$_custom_prefixe_."_custom_list_value"],$values) ? "checked=checked" : "")." value='".$r[$_custom_prefixe_."_custom_list_value"]."'/><span id='lib_".$field[NAME]."_$i'>&nbsp;".$r[$_custom_prefixe_."_custom_list_lib"]."</span>";
					$i++;
				}
			}	
		}else{
			$ret="<select id=\"".$field[NAME]."\" name=\"".$field[NAME];
			$ret.="[]";
			$ret.="\" ";
			if ($script) $ret.=$script." ";
			if ($options[MULTIPLE][0][value]=="yes") $ret.="multiple";
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
				if (count($values)) {
					$as=array_search($options[ITEMS][0][ITEM][$i][VALUE],$values);
					if (($as!==FALSE)&&($as!==NULL)) $ret.=" selected"; 
				} else {
					//Recherche de la valeur par défaut
					if ($options[ITEMS][0][ITEM][$i][VALUE]==$options[DEFAULT_VALUE][0][value]) $ret.=" selected";
				}
				$ret.=">".htmlentities($options[ITEMS][0][ITEM][$i][value],ENT_QUOTES,$charset)."</option>\n";
			}
		$ret.= "</select>\n";
		}
	}else {
		$caller="";
		switch ($_custom_prefixe_) {
			case "empr":
				$caller="empr_form";
				break;
			case "notices":
				$caller="notice";
				break;
			case "expl":
				$caller="expl";
				break;
			case "gestfic0": // a modifier lorsque il y aura du multi fiches!
				$caller="formulaire";
			break;
		}
		if ($values) {
			$values_received=$values;
			$values=array();
			$libelles=array();
			$requete="select ".$_custom_prefixe_."_custom_list_value, ".$_custom_prefixe_."_custom_list_lib from ".$_custom_prefixe_."_custom_lists where ".$_custom_prefixe_."_custom_champ=".$field[ID]." order by ordre";
			$resultat=mysql_query($requete);
			$i=0;
			while ($r=mysql_fetch_array($resultat)) {
				$as=array_search($r[$_custom_prefixe_."_custom_list_value"],$values_received);
				if (($as!==null)&&($as!==false)) {
					$values[$i]=$r[$_custom_prefixe_."_custom_list_value"];
					$libelles[$i]=$r[$_custom_prefixe_."_custom_list_lib"];
					$i++;
				}
			}
		}
		$n=count($values);
		if(($options[MULTIPLE][0][value]=="yes") )	$val_dyn=1;
		else $val_dyn=0;
		if (($n==0)||($options[MULTIPLE][0][value]!="yes")) $n=1;
		if ($options[MULTIPLE][0][value]=="yes") {
			$ret.="<script>
			function fonction_selecteur_".$field["NAME"]."() {
				name=this.getAttribute('id').substring(4);
				name_id = name;
				openPopUp('./select.php?what=perso&caller=$caller&p1='+name_id+'&p2=f_'+name_id+'&perso_id=".$field["ID"]."&custom_prefixe=".$_custom_prefixe_."&dyn=$val_dyn&perso_name=".$field['NAME']."', 'select_author2', 400, 400, -2, -2, 'toolbar=no, dependent=yes, resizable=yes, scrollbars=yes');
			}
			function fonction_raz_".$field["NAME"]."() {
				name=this.getAttribute('id').substring(4);
				document.getElementById(name).value=0;
				document.getElementById('f_'+name).value='';
			}
			function add_".$field["NAME"]."() {
				template = document.getElementById('div_".$field["NAME"]."');
				perso=document.createElement('div');
				perso.className='row';		

				suffixe = eval('document.$caller.n_".$field["NAME"].".value')
				nom_id = '".$field["NAME"]."_'+suffixe
				f_perso = document.createElement('input');
				f_perso.setAttribute('name','f_'+nom_id);
				f_perso.setAttribute('id','f_'+nom_id);
				f_perso.setAttribute('type','text');
				f_perso.className='saisie-50emr';
				f_perso.setAttribute('readonly','');
				f_perso.setAttribute('value','');
				
				del_f_perso = document.createElement('input');
				del_f_perso.setAttribute('id','del_".$field["NAME"]."_'+suffixe);
				del_f_perso.onclick=fonction_raz_".$field["NAME"].";
				del_f_perso.setAttribute('type','button');
				del_f_perso.className='bouton';
				del_f_perso.setAttribute('readonly','');
				del_f_perso.setAttribute('value','X');
		
				sel_f_perso = document.createElement('input');
				sel_f_perso.setAttribute('id','sel_".$field["NAME"]."_'+suffixe);
				sel_f_perso.setAttribute('type','button');
				sel_f_perso.className='bouton';
				sel_f_perso.setAttribute('readonly','');
				sel_f_perso.setAttribute('value','...');
				sel_f_perso.onclick=fonction_selecteur_".$field["NAME"].";
		
				f_perso_id = document.createElement('input');
				f_perso_id.name=nom_id;
				f_perso_id.setAttribute('type','hidden');
				f_perso_id.setAttribute('id',nom_id);
				f_perso_id.setAttribute('value','');
		
				perso.appendChild(f_perso);
				space=document.createTextNode(' ');
				perso.appendChild(space);
				perso.appendChild(sel_f_perso);
				space=document.createTextNode(' ');
				perso.appendChild(space);
				perso.appendChild(del_f_perso);
				perso.appendChild(f_perso_id);
	
				template.appendChild(perso);
		
				document.$caller.n_".$field["NAME"].".value=suffixe*1+1*1 ;
			}
			</script>
			";
		}
		$ret.="<input type='hidden' value='$n' name='n_".$field["NAME"]."'/>\n<div id='div_".$field["NAME"]."'>";
		
		for ($i=0; $i<$n; $i++) {
			$ret.="<input type='text' class='saisie-50emr' id='f_".$field["NAME"]."_$i' name='f_".$field["NAME"]."_$i' readonly value=\"".htmlentities($libelles[$i],ENT_QUOTES,$charset)."\" />\n";
			$ret.="<input type='hidden' id='".$field["NAME"]."_$i' name='".$field["NAME"]."_$i' value=\"".htmlentities($values[$i],ENT_QUOTES,$charset)."\">";
			
			$ret.="<input type='button' class='bouton' value='...' onclick=\"openPopUp('./select.php?what=perso&caller=$caller&p1=".$field["NAME"]."_$i&p2=f_".$field["NAME"]."_$i&perso_id=".$field["ID"]."&custom_prefixe=".$_custom_prefixe_."&dyn=$val_dyn&perso_name=".$field['NAME']."', 'select_perso_".$field["ID"]."', 700, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes, scrollbars=yes')\" /> 
			<input type='button' class='bouton' value='X' onclick=\"this.form.f_".$field["NAME"]."_$i.value=''; this.form.".$field["NAME"]."_$i.value=''; \" />\n";
			if (($i==0)&&($options[MULTIPLE][0][value]=="yes")) {
				$ret.=" <input type='button' class='bouton' value='+' onClick=\"add_".$field["NAME"]."();\"/>";
			}
			$ret.="<br />";
		}
		$ret.="</div>";
	}
	return $ret;
}

function aff_list_empr_search($field,&$check_scripts,$varname,$script="") {
	global $charset;
	$_custom_prefixe_=$field["PREFIX"];
	
	$options=$field[OPTIONS][0];
	$values=$field[VALUES];
	if ($values=="") $values=array();
	$ret="<select id=\"".$varname."\" name=\"".$varname;
	$ret.="[]";
	$ret.="\" ";
	if ($script) $ret.=$script." ";
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

function aff_empr_search($field) {
	$table = array();
	$table['label'] = $field['TITRE'];
	$table['name'] = $field['NAME'];
	$table['type'] =$field['DATATYPE'];
	
	$_custom_prefixe_=$field['PREFIX'];
	$requete="select ".$_custom_prefixe_."_custom_list_value, ".$_custom_prefixe_."_custom_list_lib from ".$_custom_prefixe_."_custom_lists where ".$_custom_prefixe_."_custom_champ=".$field[ID]." order by ordre";
	$resultat=mysql_query($requete);
	if ($resultat) {
		while ($r=mysql_fetch_array($resultat)) {
			$value['value_id']=$r[$_custom_prefixe_."_custom_list_value"];
			$value['value_caption']=$r[$_custom_prefixe_."_custom_list_lib"];
			$table['values'][]=$value;
		}
	}else{
		$table['values'] = array();
	}
	return $table;
}

function chk_list_empr($field,&$check_message) {
	global $charset;
	global $msg;
	
	$name=$field[NAME];
	$options=$field[OPTIONS][0];
	
	global $$name;
	if ($options["AUTORITE"][0]["value"]!="yes") {
		$val=$$name;
	} else {
		 $val=array();
		 $nn="n_".$name;
		 global $$nn;
		 $n=$$nn;
		 for ($i=0; $i<$n; $i++) {
		 	$v=$field["NAME"]."_".$i;
		 	global $$v;
		 	if ($$v!="") {
			 	$val[]=$$v;
		 	}
		 }
		 if (count($val)==0) unset($val);
	}
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
	global $charset;
	global $options_;
	$_custom_prefixe_=$field["PREFIX"];

	if ($val=="") return "";
	
	if (!$options_[$field[ID]]) {
		$requete="select ".$_custom_prefixe_."_custom_list_value, ".$_custom_prefixe_."_custom_list_lib from ".$_custom_prefixe_."_custom_lists where ".$_custom_prefixe_."_custom_champ=".$field[ID]." order by ordre";
		$resultat=mysql_query($requete);
		if ($resultat) {
			$i=0;
			while ($r=mysql_fetch_array($resultat)) {
				$options_[$field[ID]][$r[$_custom_prefixe_."_custom_list_value"]]=$r[$_custom_prefixe_."_custom_list_lib"];
				$i++;
			}
		}
	}

	for ($i=0; $i<count($val); $i++) {
		$val_c[$i]=$options_[$field[ID]][$val[$i]];
	}
	if ($val_c=="") $val_c=array();
	$val_=implode("/",$val_c);
	return $val_;
}

function aff_query_list_empr($field,&$check_scripts,$script="") {
	global $charset;
	global $_custom_prefixe_;
	$values=$field[VALUES];
	
	$options=$field[OPTIONS][0];
	
	if ($values=="") $values=array();
	if ($options["AUTORITE"][0]["value"]!="yes") {
		if ($options["CHECKBOX"][0]["value"]=="yes"){
			if ($options[MULTIPLE][0][value]=="yes") $type = "checkbox";
			else $type = "radio";
			$resultat=mysql_query($options[QUERY][0][value]);
			if ($resultat) {
				$i=0;
				$ret="<table><tr>";
				$limit = $options[CHECKBOX_NB_ON_LINE][0][value];
				if($limit==0) $limit = 4;
				while ($r=mysql_fetch_array($resultat)) {
					if ($i>0 && $i%$limit == 0)$ret.="</tr><tr>";
					$ret.= "<td><input id='".$field[NAME]."_$i' type='$type' name='".$field[NAME]."[]' ".(in_array($r[0],$values) ? "checked=checked" : "")." value='".$r[0]."'/><span id='lib_".$field[NAME]."_$i'>&nbsp;".$r[1]."</span></td>";
					$i++;
				}
				$ret.="</tr></table>";
			}	
		}else{
			$options=$field[OPTIONS][0];
			$ret="<select id=\"".$field[NAME]."\" name=\"".$field[NAME];
			$ret.="[]";
			$ret.="\" ";
			if ($script) $ret.=$script." ";
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
		}
		$ret.= "</select>\n";
	} else {
		$caller="";
		switch ($_custom_prefixe_) {
			case "empr":
				$caller="empr_form";
				break;
			case "notices":
				$caller="notice";
				break;
			case "expl":
				$caller="expl";
				break;
			case "gestfic0": // a modifier lorsque il y aura du multi fiches!
				$caller="formulaire";
			break;
		}
		if ($values) {
			$values_received=$values;
			$values_received_bis=$values;
			$values=array();
			$libelles=array();
			$resultat=mysql_query($options[QUERY][0][value]);
			$i=0;
			while ($r=mysql_fetch_row($resultat)) {
				$as=array_search($r[0],$values_received);
				if (($as!==null)&&($as!==false)) {
					$values[$i]=$r[0];
					$libelles[$i]=$r[1];
					$i++;
					unset($values_received_bis[$as]);
				}
			}
			if ($options["INSERTAUTHORIZED"][0]["value"]=="yes") {
				foreach ($values_received_bis as $key=>$val) {
					$values[$i]="";
					$libelles[$i]=$val;
					$i++;
				}
			}
		}
		$n=count($values);
		if(($options[MULTIPLE][0][value]=="yes") )	$val_dyn=1;
		else $val_dyn=0;
		if (($n==0)||($options[MULTIPLE][0][value]!="yes")) $n=1;
		if ($options[MULTIPLE][0][value]=="yes") {
			$readonly="f_perso.setAttribute('readonly','');";
			if($options["INSERTAUTHORIZED"][0]["value"]=="yes"){
				$readonly="";
			}
			$ret.="<script>
			function fonction_selecteur_".$field["NAME"]."() {
				name=this.getAttribute('id').substring(4);
				name_id = name;
				openPopUp('./select.php?what=perso&caller=$caller&p1='+name_id+'&p2=f_'+name_id+'&perso_id=".$field["ID"]."&custom_prefixe=".$_custom_prefixe_."&dyn=$val_dyn&perso_name=".$field['NAME']."', 'select_author2', 400, 400, -2, -2, 'toolbar=no, dependent=yes, resizable=yes, scrollbars=yes');
			}
			function fonction_raz_".$field["NAME"]."() {
				name=this.getAttribute('id').substring(4);
				document.getElementById(name).value='';
				document.getElementById('f_'+name).value='';
			}
			function add_".$field["NAME"]."() {
				template = document.getElementById('div_".$field["NAME"]."');
				perso=document.createElement('div');
				perso.className='row';		

				suffixe = eval('document.$caller.n_".$field["NAME"].".value')
				nom_id = '".$field["NAME"]."_'+suffixe
				f_perso = document.createElement('input');
				f_perso.setAttribute('name','f_'+nom_id);
				f_perso.setAttribute('id','f_'+nom_id);
				f_perso.setAttribute('type','text');
				f_perso.className='saisie-50emr';
				$readonly
				f_perso.setAttribute('value','');
				
				del_f_perso = document.createElement('input');
				del_f_perso.setAttribute('id','del_".$field["NAME"]."_'+suffixe);
				del_f_perso.onclick=fonction_raz_".$field["NAME"].";
				del_f_perso.setAttribute('type','button');
				del_f_perso.className='bouton';
				del_f_perso.setAttribute('readonly','');
				del_f_perso.setAttribute('value','X');
		
				sel_f_perso = document.createElement('input');
				sel_f_perso.setAttribute('id','sel_".$field["NAME"]."_'+suffixe);
				sel_f_perso.setAttribute('type','button');
				sel_f_perso.className='bouton';
				sel_f_perso.setAttribute('readonly','');
				sel_f_perso.setAttribute('value','...');
				sel_f_perso.onclick=fonction_selecteur_".$field["NAME"].";
		
				f_perso_id = document.createElement('input');
				f_perso_id.name=nom_id;
				f_perso_id.setAttribute('type','hidden');
				f_perso_id.setAttribute('id',nom_id);
				f_perso_id.setAttribute('value','');
		
				perso.appendChild(f_perso);
				space=document.createTextNode(' ');
				perso.appendChild(space);
				perso.appendChild(sel_f_perso);
				space=document.createTextNode(' ');
				perso.appendChild(space);
				perso.appendChild(del_f_perso);
				perso.appendChild(f_perso_id);
	
				template.appendChild(perso);
		
				document.$caller.n_".$field["NAME"].".value=suffixe*1+1*1 ;
			}
			</script>
			";
		}
		$ret.="<input type='hidden' value='$n' name='n_".$field["NAME"]."'\>\n<div id='div_".$field["NAME"]."'>";
		$readonly="readonly";
		if($options["INSERTAUTHORIZED"][0]["value"]=="yes"){
			$readonly="";
		}
		for ($i=0; $i<$n; $i++) {
			$ret.="<input type='text' class='saisie-50emr' id='f_".$field["NAME"]."_$i' name='f_".$field["NAME"]."_$i' $readonly value=\"".htmlentities($libelles[$i],ENT_QUOTES,$charset)."\" />\n";
			$ret.="<input type='hidden' id='".$field["NAME"]."_$i' name='".$field["NAME"]."_$i' value=\"".htmlentities($values[$i],ENT_QUOTES,$charset)."\">";
			$ret.="<input type='button' class='bouton' value='...' onclick=\"openPopUp('./select.php?what=perso&caller=$caller&p1=".$field["NAME"]."_$i&p2=f_".$field["NAME"]."_$i&perso_id=".$field["ID"]."&custom_prefixe=".$_custom_prefixe_."&dyn=$val_dyn&perso_name=".$field['NAME']."', 'select_perso_".$field["ID"]."', 700, 500, -2, -2,'toolbar=no, dependent=yes, resizable=yes, scrollbars=yes')\" /> 
			<input type='button' class='bouton' value='X' onclick=\"this.form.f_".$field["NAME"]."_$i.value=''; this.form.".$field["NAME"]."_$i.value=''; \" />\n";
			if (($i==0)&&($options[MULTIPLE][0][value]=="yes")) {
				$ret.=" <input type='button' class='bouton' value='+' onClick=\"add_".$field["NAME"]."();\"/>";
			}
			$ret.="<br />";
		}
		$ret.="</div>";
	}
	return $ret;
}

function aff_query_list_empr_search($field,&$check_scripts,$varname,$script="") {
	global $charset;
	$values=$field[VALUES];
	if ($values=="") $values=array();
	$options=$field[OPTIONS][0];
	$ret="<select id=\"".$varname."\" name=\"".$varname;
	$ret.="[]";
	$ret.="\" ";
	if ($script) $ret.=$script." ";
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
	$options=$field[OPTIONS][0];
	global $$name;
	if ($options["AUTORITE"][0]["value"]!="yes") {
		$val=$$name;
	} else {
		 $val=array();
		 $nn="n_".$name;
		 global $$nn;
		 $n=$$nn;
		 for ($i=0; $i<$n; $i++) {
		 	$v=$field["NAME"]."_".$i;
		 	global $$v;
		 	if ($$v!="") {
			 	$val[]=$$v;
		 	}elseif($options["INSERTAUTHORIZED"][0]["value"]=="yes"){
		 		$v2="f_".$v;
		 		global $$v2;
		 		if ($$v2!="") {
				 	$val[]=$$v2;
			 	}
		 	}
		 }
		 if (count($val)==0) unset($val);
	}
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
	global $charset;

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
	$val_=implode("/",$val_c);
	return $val_;
}

function aff_filter_comment_empr($field,$varname,$multiple) {
	global $charset;
	global $msg;
	
	$ret="<select id=\"".$varname."\" name=\"".$varname;
	$ret.="[]";
	$ret.="\" ";
	if ($multiple) $ret.="size=5 multiple";
	$ret.=">\n";
		
	$values=$field[VALUES];
	if ($values=="") $values=array();
	$options=$field[OPTIONS][0];
	if (($options[UNSELECT_ITEM][0][VALUE]!="")||($options[UNSELECT_ITEM][0][value]!="")) {
		$ret.="<option value=\"".htmlentities($options[UNSELECT_ITEM][0][VALUE],ENT_QUOTES,$charset)."\"";
		if ($options[UNSELECT_ITEM][0][VALUE]==$options[DEFAULT_VALUE][0][value]) $ret.=" selected"; 
		$ret.=">".htmlentities($options[UNSELECT_ITEM][0][value],ENT_QUOTES,$charset)."</option>\n";
	}
	$resultat=mysql_query($options[QUERY][0][value]);
	while ($r=mysql_fetch_row($resultat)) {
		$ret.="<option value=\"".htmlentities($r[0],ENT_QUOTES,$charset)."\"";
		$as=array_search($r[0],$values);
		if (($as!==FALSE)&&($as!==NULL)) $ret.=" selected"; 
		$ret.=">".htmlentities(cutlongwords($r[0]),ENT_QUOTES,$charset)."</option>\n";
		
	}
	$ret.= "</select>\n";
	return $ret;
}

function aff_filter_date_box_empr($field,$varname,$multiple) {
	global $charset;
	global $msg;
	
	$ret="<select id=\"".$varname."\" name=\"".$varname;
	$ret.="[]";
	$ret.="\" ";
	if ($multiple) $ret.="size=5 multiple";
	$ret.=">\n";	
	
	$values=$field[VALUES];
	if ($values=="") $values=array();
	$options=$field[OPTIONS][0];
	if (($options[UNSELECT_ITEM][0][VALUE]!="")||($options[UNSELECT_ITEM][0][value]!="")) {
		$ret.="<option value=\"".htmlentities($options[UNSELECT_ITEM][0][VALUE],ENT_QUOTES,$charset)."\"";
		if ($options[UNSELECT_ITEM][0][VALUE]==$options[DEFAULT_VALUE][0][value]) $ret.=" selected"; 
		$ret.=">".htmlentities($options[UNSELECT_ITEM][0][value],ENT_QUOTES,$charset)."</option>\n";
	}
	$resultat=mysql_query($options[QUERY][0][value]);
	while ($r=mysql_fetch_row($resultat)) {
		$ret.="<option value=\"".htmlentities($r[0],ENT_QUOTES,$charset)."\"";
		$as=array_search($r[0],$values);
		if (($as!==FALSE)&&($as!==NULL)) $ret.=" selected"; 
		$ret.=">".htmlentities(formatdate($r[0]),ENT_QUOTES,$charset)."</option>\n";
	}
	$ret.= "</select>\n";
	return $ret;
}

function aff_filter_text_empr($field,$varname,$multiple) {
	global $charset;
	global $msg;
	
	$ret="<select id=\"".$varname."\" name=\"".$varname;
	$ret.="[]";
	$ret.="\" ";
	if ($multiple) $ret.="size=5 multiple";
	$ret.=">\n";
		
	$values=$field[VALUES];
	if ($values=="") $values=array();
	$options=$field[OPTIONS][0];
	if (($options[UNSELECT_ITEM][0][VALUE]!="")||($options[UNSELECT_ITEM][0][value]!="")) {
		$ret.="<option value=\"".htmlentities($options[UNSELECT_ITEM][0][VALUE],ENT_QUOTES,$charset)."\"";
		if ($options[UNSELECT_ITEM][0][VALUE]==$options[DEFAULT_VALUE][0][value]) $ret.=" selected"; 
		$ret.=">".htmlentities($options[UNSELECT_ITEM][0][value],ENT_QUOTES,$charset)."</option>\n";
	}
	$resultat=mysql_query($options[QUERY][0][value]);
	while ($r=mysql_fetch_row($resultat)) {
		$ret.="<option value=\"".htmlentities($r[0],ENT_QUOTES,$charset)."\"";
		$as=array_search($r[0],$values);
		if (($as!==FALSE)&&($as!==NULL)) $ret.=" selected"; 
		$ret.=">".htmlentities(cutlongwords($r[0]),ENT_QUOTES,$charset)."</option>\n";
	}
	$ret.= "</select>\n";
	return $ret;
}

function aff_filter_query_list_empr($field,$varname,$multiple) {
	global $charset;
		
	$options=$field[OPTIONS][0];
	$values=$field[VALUES];
	if ($values=="") $values=array();
	
	$ret="<select id=\"".$varname."\" name=\"".$varname;
	$ret.="[]";
	$ret.="\" ";
	if ($multiple) $ret.="size=5 multiple";
	$ret.=">\n";
	if ($options["AUTORITE"][0]["value"]!="yes") {
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
	} else {
			
	}
	$ret.= "</select>\n";
}

function aff_filter_list_empr($field,$varname,$multiple) {
	global $charset;
	global $msg;
	
	$_custom_prefixe_=$field["PREFIX"];
	$options=$field[OPTIONS][0];
	$values=$field[VALUES];
	if ($values=="") $values=array();
	
	$ret="<select id=\"".$varname."\" name=\"".$varname;
	$ret.="[]";
	$ret.="\" ";
	if ($multiple) $ret.="size=5 multiple";
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
		if (count($values)) {
			$as=array_search($options[ITEMS][0][ITEM][$i][VALUE],$values);
			if (($as!==FALSE)&&($as!==NULL)) $ret.=" selected"; 
		} else {
			//Recherche de la valeur par défaut
			if ($options[ITEMS][0][ITEM][$i][VALUE]==$options[DEFAULT_VALUE][0][value]) $ret.=" selected";
		}
		$ret.=">".htmlentities($options[ITEMS][0][ITEM][$i][value],ENT_QUOTES,$charset)."</option>\n";
	}
		$ret.= "</select>\n";
	return $ret;
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
		$ret.="<div id='check_$count' style='display:inline'></div>";
		$ret.= $msg['persofield_url_link']."<input id='".$field[NAME]."_link' type='text' class='saisie-30em' name='".$field[NAME]."[link][]' onchange='cp_chklnk($count);' value='".htmlentities($avalues[0],ENT_QUOTES,$charset)."'>";
		$ret.=" <input class=\"bouton\" type='button' value='".$msg['persofield_url_check']."' onclick='cp_chklnk($count);'>";
		//$ret.="<br />";
		$ret.="&nbsp;".$msg['persofield_url_linklabel']."<input id='".$field[NAME]."_linkname' type='text' class='saisie-15em' size='".$options[SIZE][0][value]."' name='".$field[NAME]."[linkname][]' value='".htmlentities($avalues[1],ENT_QUOTES,$charset)."'>";
		if ($options[REPEATABLE][0][value] && !$count)
			$ret.="<input class='bouton' type='button' value='+' onclick=\"add_custom_url_('.$afield_name.', '".addslashes($field[NAME])."', '".addslashes($options[SIZE][0][value])."')\">";
		$ret.="<br />";
		$count++;
	}
	$ret.= "
	<script type='text/javascript'>
		function cp_chklnk(indice){
			var wait = document.createElement('img');
			wait.setAttribute('src','images/patience.gif');
			wait.setAttribute('align','top');
			while(document.getElementById('check_'+indice).firstChild){
				document.getElementById('check_'+indice).removeChild(document.getElementById('check_'+indice).firstChild);
			}
			document.getElementById('check_'+indice).appendChild(wait);

			var links = document.forms.notice['url[link][]'];
			if(typeof(links.length)) var link = links[indice].value;
			else var link = links.value;
			if(link != ''){
				var testlink = encodeURIComponent(link);
	 			var check = new http_request();
				if(check.request('./ajax.php?module=ajax&categ=chklnk',true,'&timeout=".$options[TIMEOUT][0][value]."&link='+testlink)){
					alert(check.get_text());
				}else{
					var result = check.get_text();
					var img = document.createElement('img');
					var src='';
					if(result == '200') {
						if(link.substr(0,7) != 'http://') document.forms.notice['url[link][]'][indice].value = 'http://'+link;
						//impec, on print un petit message de confirmation
						src = 'images/tick.gif';
					}else{
						//problème...
						src = 'images/error.png';
						img.setAttribute('style','height:1.5em;');
					}
					img.setAttribute('src',src);
					img.setAttribute('align','top');
					while(document.getElementById('check_'+indice).firstChild){
						document.getElementById('check_'+indice).removeChild(document.getElementById('check_'+indice).firstChild);
					}
					document.getElementById('check_'+indice).appendChild(img);
				}
			}
		}
	</script>";
	if ($options[REPEATABLE][0][value]) {
		$ret.='<input id="customfield_text_'.$afield_name.'" type="hidden" name="customfield_text_'.$afield_name.'" value="'.($count).'">';
		//$ret.='<input class="bouton" type="button" value="+" onclick="add_custom_text_(\''.$afield_name.'\', \''.addslashes($field[NAME]).'\', \''.addslashes($options[SIZE][0][value]).'\', \''.addslashes($options[MAXSIZE][0][value]).'\')">';
		$ret .= '<div id="spaceformorecustomfieldtext_'.$afield_name.'"></div>';
		$ret.="<script>
			function add_custom_url_(field_id, field_name, field_size) {
				cpt = document.getElementById('customfield_text_'+field_id).value;
				var check = document.createElement('div');
				check.setAttribute('id','check_'+cpt);
				check.setAttribute('style','display:inline');
				var link_label = document.createTextNode('".$msg['persofield_url_link']."');
				var chklnk = document.createElement('input');
				chklnk.setAttribute('type','button');
				chklnk.setAttribute('value','".$msg['persofield_url_check']."');
				chklnk.setAttribute('class','bouton');
				chklnk.setAttribute('onclick','cp_chklnk('+cpt+');');
				document.getElementById('customfield_text_'+field_id).value = cpt*1 +1;
				var link = document.createElement('input');
		        link.setAttribute('name',field_name+'[link][]');
		        link.setAttribute('type','text');
				link.setAttribute('class','saisie-30em');
		        link.setAttribute('size',field_size);
		        link.setAttribute('value','');
				link.setAttribute('onchange','cp_chklnk('+cpt+');');
				var lib_label = document.createTextNode('".$msg['persofield_url_linklabel']."');
				var lib = document.createElement('input');
		        lib.setAttribute('name',field_name+'[linkname][]');
		        lib.setAttribute('type','text');
				lib.setAttribute('class','saisie-15em');
		        lib.setAttribute('size',field_size);
		        lib.setAttribute('value','');
		        space=document.createElement('br');
				document.getElementById('spaceformorecustomfieldtext_'+field_id).appendChild(check);
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
	for ($i=0;$i<count($values);$i++){
		$val = explode("|",$values[$i]);
		if ($val[1])$lib = $val[1];
		else $lib = ($cut && strlen($val[0]) > $cut ? substr($val[0],0,$cut)."[...]" : $val[0] );
		if( $ret != "") $ret.= " / ";
		$ret .= "<a href='".$val[0]."' />".htmlentities($lib,ENT_QUOTES,$charset)."</a>";
	}
	return array("ishtml" => true, "value"=>$ret);
}