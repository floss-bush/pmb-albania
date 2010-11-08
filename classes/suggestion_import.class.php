<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: suggestion_import.class.php,v 1.4 2009-11-04 14:37:53 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($include_path."/templates/suggestion_multi.tpl.php");
require_once ("$include_path/parser.inc.php");

function _item_($param) {
	global $catalog;
	global $catalog_import;
	$catalog[]=$param['IMPORTNAME'];
	$catalog_import[]=$param['IMPORT'];
}

class suggestion_import{
	
	var $id_import=0;
	
	function suggestion_import($id_imp=0){
		$this->id_import = $id_imp;
	}

	function show_form(){
		
		global $import_sug_form, $dbh, $charset, $msg;
		global $catalog;
		global $catalog_import;
		global $explnumdoc_id;
		
		if($explnumdoc_id){
			$req = "select SUBSTRING(explnum_doc_data,1,250) as data, sugg_source, origine, type_origine from explnum_doc
			join explnum_doc_sugg on num_explnum_doc=id_explnum_doc
			left join suggestions on num_suggestion=id_suggestion
			left join suggestions_origine so on so.num_suggestion=id_suggestion
			where id_explnum_doc='".$explnumdoc_id."'";
			$res = mysql_query($req,$dbh);
			$expl = mysql_fetch_object($res);
			$import_file = htmlentities($expl->data,ENT_QUOTES,$charset);
			$import_sug_form = str_replace('!!explnum_id!!',$explnumdoc_id,$import_sug_form);
			$source = $expl->sugg_source;
			$origine = $expl->origine;
			$type_origine = $expl->type_origine;
			$import_sug_form = str_replace('!!origine_id!!',$origine,$import_sug_form);
			$import_sug_form = str_replace('!!type_origine!!',$type_origine,$import_sug_form);
		} else {
			$import_sug_form = str_replace('!!explnum_id!!',"",$import_sug_form);
			$import_file = "<input class='saisie-80em' size='65' type='file' name='import_file' id='import_file' />	";
			$source = 0;
			$import_sug_form = str_replace('!!origine_id!!',"",$import_sug_form);
			$import_sug_form = str_replace('!!type_origine!!',"",$import_sug_form);
		} 
		$import_sug_form = str_replace('!!import_file!!',$import_file,$import_sug_form);
		$req = "select * from suggestions_source order by libelle_source";
		$res= mysql_query($req,$dbh);
		$option = "<option value='0'>".htmlentities($msg['acquisition_sugg_no_src'],ENT_QUOTES,$charset)."</option>";
		while(($src=mysql_fetch_object($res))){
			if($src->id_source == $source) $selected="selected";
			$option .= "<option value='".$src->id_source."' $selected>".htmlentities($src->libelle_source,ENT_QUOTES,$charset)."</option>";
			$selected ="";
		}
		$selecteur = "<select id='src_liste' name='src_liste'>".$option."</select>";	
		$import_sug_form = str_replace('!!liste_source!!',$selecteur,$import_sug_form);
		
		//Lecture des différents imports possibles
		if (file_exists("./admin/convert/imports/catalog_subst.xml"))
			$fic_catal = "./admin/convert/imports/catalog_subst.xml";
		else
			$fic_catal = "./admin/convert/imports/catalog.xml";		
		$catalog=array();
		_parser_($fic_catal,array("ITEM"=>"_item_"),"CATALOG");		
		//Création de la liste des types d'import
		$select_import="<select name=\"import_type\">\n";
		for ($i=0; $i<count($catalog); $i++) {
			if ($catalog_import[$i]=="yes" && $catalog[$i]) {
			   $select_import.="<option value=\"$i\">".$catalog[$i]."</option>\n";
			}
		}
		$select_import .= "<option value='uni'>".$msg[acquisition_import_sugg_uni]."</option>";
		$select_import.="</select>";
		$import_sug_form = str_replace('!!liste_import!!',$select_import,$import_sug_form);
		print $import_sug_form;
	}
	
}
?>