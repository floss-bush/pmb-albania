<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: fields.inc.php,v 1.15 2009-10-21 07:17:45 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$aff_list=array("text"=>"aff_text","list"=>"aff_list","query_list"=>"aff_query_list","date_box"=>"aff_date_box","file_box"=>"aff_file_box","selector"=>"aff_selector");
$chk_list=array("text"=>"chk_text","list"=>"chk_list","query_list"=>"chk_query_list","date_box"=>"chk_date_box","file_box"=>"chk_file_box","selector"=>"chk_selector");
$val_list=array("text"=>"val_text","list"=>"val_list","query_list"=>"val_query_list","date_box"=>"val_date_box","file_box"=>"val_file_box","selector"=>"val_selector");
$type_list=array("text"=>$msg["parperso_text"],"list"=>$msg["parperso_choice_list"],"query_list"=>$msg["parperso_query_choice_list"],"date_box"=>$msg["parperso_date"],"file_box"=>$msg["parperso_file_box"],"selector"=>$msg["parperso_selector"]);
$options_list=array("text"=>"options_text.php","list"=>"options_list.php","query_list"=>"options_query_list.php","date_box"=>"options_date_box.php","file_box"=>"options_file_box.php","selector"=>"options_selector.php");

function aff_selector($field,&$check_scripts) {
	
	global $msg;
	
	if($field["OPTIONS"][0]["METHOD"]["0"]["value"]==1) {
		$text_name=$field[NAME]."_id";
		$hidden_name=$field[NAME];		
	} else {
		$text_name=$field[NAME];
		$hidden_name=$field[NAME]."_id";
	}
	switch($field["OPTIONS"][0]["DATA_TYPE"]["0"]["value"]) {
		case 1:$what="auteur";break;//auteurs
		case 2:$what="categorie";break;//categories
		case 3:$what="editeur";break;//Editeurs
		case 4:$what="collection";break;//collection
		case 5:$what="subcollection";break;// subcollection
		case 6:$what="serie";break;//Titre de serie
		case 7:$what="indexint";break;// Indexation decimale		
	}	
	$ret="<span style='width: 251px;'><input type='text' name='".$text_name."' id='".$text_name."' class='saisie-30emr' ></span>";
	$ret.="<input class='bouton' value='...' onclick=\"window.open('./select.php?what=".$what."&dyn=&caller=formulaire&param1=".$hidden_name."&param2=".$text_name."&p1=".$hidden_name."&p2=".$text_name."&mode=un&deb_rech='+escape(''), 'select_author0', 'scrollbars=yes, toolbar=no, dependent=yes, width=400, height=400, resizable=yes')\" type='button'>";
	$ret.="<input name='".$hidden_name."' id='".$hidden_name."'  type='hidden'>";
	
	if ($field[MANDATORY]=="yes") $check_scripts.="if (document.formulaire.".$field[NAME].".value==\"\") return cancel_submit(\"".sprintf($msg["parperso_field_is_needed"],$field[ALIAS][0][value])."\");\n";
	return $ret;
}


function chk_selector($field,&$check_message) {
	return 1;
}


function val_selector($field) {
	
	$name=$field[NAME];
	global $$name;
	
	return $$name;
}


function aff_file_box($field,&$check_scripts) {
	
	global $msg;
	
	$ret="<input type=\"file\" name=\"".$field["NAME"]."\">";
	if ($field[MANDATORY]=="yes") $check_scripts.="if (document.formulaire.".$field[NAME].".value==\"\") return cancel_submit(\"".sprintf($msg["parperso_field_is_needed"],$field[ALIAS][0][value])."\");\n";
	return $ret;
}


function chk_file_box($field,&$check_message) {
	
	global $msg;
	global $_FILES;
	
	//Supression des vieux fichiers !
	$dir=opendir("temp");
	$files=array();
	while (false !== ($file=readdir($dir))) {
		$files[]=$file;
	}
	for ($i=0; $i<count($files); $i++) {
		$file=$files[$i];
		$date=filemtime("temp/".$file);
		if (((time()-$date)>=24*60*60)&&(substr($file,0,13)=="proc_actions_")) {
			unlink("temp/".$file);
		} 
	}
	
	if ($_FILES[$field[NAME]]["error"]) {
		$check_message=$msg['field_file_download'];
		return 0;
	} else {
		if ($_FILES[$field[NAME]]["tmp_name"]) {
			if (move_uploaded_file($_FILES[$field[NAME]]["tmp_name"],"temp/proc_actions_".basename($_FILES[$field[NAME]]["tmp_name"]))) {
				$field_name=$field[NAME];
				global $$field_name;
				$field_name_[0]="proc_actions_".basename($_FILES[$field[NAME]]["tmp_name"]);
				$$field_name=$field_name_;
				return 1;
			} else {
				$check_message=$msg['field_file_copy'];
				return 0;
			}
		} else {	
			$field_name=$field[NAME];
			global $$field_name;
			$field_name_=$$field_name;
			if (file_exists("temp/".basename($field_name_[0]))) {
				return 1;
			} else {
				$check_message=$msg['field_file_not_exist'];
				return 0;
			}
		}
	}
}


function val_file_box($field) {
	
	if ($field [OPTIONS][0][METHOD][0][value]=="") $field [OPTIONS][0][METHOD][0][value]=1;
	if (($field [OPTIONS][0][METHOD][0][value]==2)&&($field [OPTIONS][0][DATA_TYPE][0][value]=="")) $field [OPTIONS][0][DATA_TYPE][0][value]=1;
	$val=array();
	
	$field_name=$field[NAME];
	global $$field_name;
	$field_name_=$$field_name;
	
	if (($fp=@fopen("temp/".$field_name_[0],"r"))) {
		while (!feof($fp)) {
			$val_=@fgets($fp);
			$val_=rtrim($val_);
			$val[]=$val_;
		}
		fclose($fp);
		//unlink($_FILES[$field["NAME"]]["tmp_name"]);
		if ($field[OPTIONS][0][METHOD][0][value]==1) {
			$ret=implode("', '",$val);
			if ($ret!="") $ret="'".$ret."'";
			return $ret;
		} else {
			if ($field [OPTIONS][0][DATA_TYPE][0][value]=="1") $data_type="varchar(255)"; else $data_type="integer";
			$requete="create temporary table ".$field[OPTIONS][0][TEMP_TABLE_NAME][0][value]." (val $data_type) ENGINE=MyISAM ";
			@mysql_query($requete);
			while (list($key,$value)=each($val)) {
				$requete="insert into ".$field[OPTIONS][0][TEMP_TABLE_NAME][0][value]." values('".addslashes($value)."')";
				mysql_query($requete);
			}
			return $field[OPTIONS][0][TEMP_TABLE_NAME][0][value];
		}
	}
}


function aff_text($field,&$check_scripts) {
	
	global $msg;
	
	$options=$field[OPTIONS][0];
	$ret="<input type=\"text\" size=\"".$options[SIZE][0][value]."\" maxlength=\"".$options[MAXSIZE][0][value]."\" name=\"".$field[NAME]."\">";
	
	if ($field[MANDATORY]=="yes") $check_scripts.="if (document.formulaire.".$field[NAME].".value==\"\") return cancel_submit(\"".sprintf($msg["parperso_field_is_needed"],$field[ALIAS][0][value])."\");\n";
	return $ret;
}


function chk_text($field,&$check_message) {
	return 1;
}


function val_text($field) {
	
	$name=$field[NAME];
	global $$name;
	
	return $$name;
}


function aff_date_box($field,&$check_scripts) {
	
	global $msg;
	
	$val=date("Y-m-d",time());
	$val_popup=date("Ymd",time());
	$ret="<input type='hidden' name='".$field[NAME]."' value='$val' />
				<input class='bouton' type='button' name='".$field[NAME]."_lib' value='".formatdate($val_popup)."' onClick=\"openPopUp('./select.php?what=calendrier&caller=formulaire&date_caller=".$val_popup."&param1=".$field[NAME]."&param2=".$field[NAME]."_lib&auto_submit=NO&date_anterieure=YES', 'date_".$field[NAME]."', 250, 300, -2, -2,'toolbar=no, dependent=yes, resizable=yes')\" />";
	if ($field[MANDATORY]=="yes") $check_scripts.="if (document.formulaire.elements[\"".$field[NAME]."[]\"].value==\"\") return cancel_submit(\"".sprintf($msg["parperso_field_is_needed"],$field[ALIAS][0][value])."\");\n";
	return $ret;
}


function chk_date_box($field,&$check_message) {
	return 1;
}


function val_date_box($field) {
	
	$name=$field[NAME];
	global $$name;

	return stripslashes($$name);
}


function aff_list($field,&$check_scripts) {
	
	global $charset;
	
	$options=$field[OPTIONS][0];
	$ret="<select name=\"".$field[NAME];
	if ($options[MULTIPLE][0][value]=="yes") $ret.="[]";
	$ret.="\" ";
	if ($options[MULTIPLE][0][value]=="yes") $ret.="multiple";
	$ret.=">\n";
	if (($options[UNSELECT_ITEM][0][VALUE]!="")||($options[UNSELECT_ITEM][0][value]!="")) {
		$ret.="<option value=\"".htmlentities($options[UNSELECT_ITEM][0][VALUE],ENT_QUOTES,$charset)."\">".htmlentities($options[UNSELECT_ITEM][0][value],ENT_QUOTES,$charset)."</option>\n";
	}
	for ($i=0; $i<count($options[ITEMS][0][ITEM]); $i++) {
		$ret.="<option value=\"".htmlentities($options[ITEMS][0][ITEM][$i][VALUE],ENT_QUOTES,$charset)."\">".htmlentities($options[ITEMS][0][ITEM][$i][value],ENT_QUOTES,$charset)."</option>\n";
	}
	$ret.= "</select>\n";
	return $ret;
}


function chk_list($field,&$check_message) {
	
	global $msg;
	
	$name=$field[NAME];
	global $$name;
	$val=$$name;
	if ($field[MANDATORY]=="yes") {
	if ((!isset($val))||((count($val)==1)&&($val[0]==""))||($val=="")) {
			$check_message=sprintf($msg["parperso_field_is_needed"],$field[ALIAS][0][value]);
			return 0;
		}
	}
	return 1;
}


function val_list($field) {
	
	$name=$field[NAME];
	global $$name;
	
	$val=$$name;
	
	if ($field[OPTIONS][0][MULTIPLE][0][value]=="yes") {
		$val_=implode("','",$val);
		if ($val_!="") $val_="'".$val_."'";
		$val_=stripslashes($val_);
		return $val_;
	} else {
		$val=stripslashes($val);
		return "'".$val."'";
	}
}


function aff_query_list($field,&$check_scripts) {
	
	global $charset;
	$options=$field[OPTIONS][0];
	$ret="<select name=\"".$field[NAME];
	if ($options[MULTIPLE][0][value]=="yes") $ret.="[]";
	$ret.="\" ";
	if ($options[MULTIPLE][0][value]=="yes") $ret.="multiple";
	$ret.=">\n";
	if (($options[UNSELECT_ITEM][0][VALUE]!="")||($options[UNSELECT_ITEM][0][value]!="")) {
		$ret.="<option value=\"".htmlentities($options[UNSELECT_ITEM][0][VALUE],ENT_QUOTES,$charset)."\">".htmlentities($options[UNSELECT_ITEM][0][value],ENT_QUOTES,$charset)."</option>\n";
	}
	$resultat=mysql_query($options[QUERY][0][value]);
	while (($r=mysql_fetch_row($resultat))) {
		$ret.="<option value=\"".htmlentities($r[0],ENT_QUOTES,$charset)."\">".htmlentities($r[1],ENT_QUOTES,$charset)."</option>\n";
	}
	$ret.= "</select>\n";
	return $ret;
}


function chk_query_list($field,&$check_message) {
	
	global $msg;
	
	$name=$field[NAME];
	global $$name;
	$val=$$name;
	if ($field[MANDATORY]=="yes") {
	if ((!isset($val))||((count($val)==1)&&($val[0]==""))||($val=="")) {
			$check_message=sprintf($msg["parperso_field_is_needed"],$field[ALIAS][0][value]);
			return 0;
		}
	}
	return 1;
}


function val_query_list($field) {
	
	$name=$field[NAME];
	global $$name;
	
	$val=$$name;
	
	if ($field[OPTIONS][0][MULTIPLE][0][value]=="yes") {		
		$val_=implode("','",$val);
		if ($val_!="") $val_="'".$val_."'";
		//$val_=stripslashes($val_);
		return $val_;
	} else {
		//$val=stripslashes($val);
		return $val;
	}
}
?>