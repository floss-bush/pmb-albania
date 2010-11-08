<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: pclass_form.inc.php,v 1.3 2007-07-31 09:23:03 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// inclusions diverses
include_once("$include_path/templates/pclass.tpl.php");
if(!$id_pclass) $id_pclass = 0;
$update_url = "./autorites.php?categ=indexint&sub=pclass_update&id_pclass=".$id_pclass;
$delete_url = "./autorites.php?categ=indexint&sub=pclass_delete&id_pclass=".$id_pclass;
$cancel_url = "./autorites.php?categ=indexint&sub=pclass"; 

if($id_pclass) {	//modification
	$title = $msg[pclassement_modification];
	$delete_button = "<input type='button' class='bouton' value='$msg[63]' onClick=\"confirm_delete();\">";
		
	// on récupère les données
	$q = "select id_pclass,name_pclass,typedoc from pclassement where id_pclass='$id_pclass' ";
	$r = mysql_query($q, $dbh);
	
	if ($row = mysql_fetch_object($r)) {
		$identifiant = "<div class='row'><label class='etiquette' >".$msg[38]."</label></div>";
		$identifiant.= "<div class='row'>".$id_pclass."</div>";
		$libelle = $row->name_pclass;	
		$typedoc=$row->typedoc;
	} else {
		error_form_message($msg["pclassement_modification_impossible"]);
		exit;	
	}
} else {	//creation
	
	$title = $msg[pclassement_creation];
	$delete_button = '';	
	$identifiant = '';
	$libelle = '';	
}

$doctype = new marc_list('doctype');
$toprint_typdocfield = " <select name='typedoc_list[]' MULTIPLE SIZE=20 >";

foreach($doctype->table as $value=>$libelletypdoc) {
	if((strpos($typedoc,$value)===false)) $tag = "<option value='$value'>";
		else $tag = "<option value='$value' SELECTED>";
	$toprint_typdocfield .= "$tag$libelletypdoc</option>";
	}

$toprint_typdocfield .= "</select>";

$pclassement_form = str_replace('!!id_thes!!', $id_thes, $pclassement_form);
$pclassement_form = str_replace('!!form_title!!', $title, $pclassement_form);
$pclassement_form = str_replace('!!identifiant!!', $identifiant, $pclassement_form);
$pclassement_form = str_replace('!!libelle!!', $libelle, $pclassement_form);
$pclassement_form = str_replace('!!type_doc!!', $toprint_typdocfield, $pclassement_form);
$pclassement_form = str_replace('!!update_url!!', $update_url, $pclassement_form);
$pclassement_form = str_replace('!!delete_url!!', $delete_url, $pclassement_form);
$pclassement_form = str_replace('!!cancel_url!!', $cancel_url, $pclassement_form);
$pclassement_form = str_replace('!!delete_button!!', $delete_button, $pclassement_form);
print $pclassement_form ;
