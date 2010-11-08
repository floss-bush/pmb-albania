<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: suggestions_import.inc.php,v 1.4 2009-11-04 14:37:54 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/suggestion_import.class.php");
require_once($class_path."/suggestion_multi.class.php");

$sug_import = new suggestion_import();

switch($act){
	case 'import_sugg':
		if (move_uploaded_file($_FILES["import_file"]["tmp_name"],"temp/".basename($_FILES["import_file"]["tmp_name"]))) {
			$file_name=basename($_FILES["import_file"]["tmp_name"]);
			$redirect=rawurlencode("../../acquisition.php?categ=sug&sub=multi&act=import&src_liste=".$src_liste."&origine_id=".$origine_id."&type_origine=".$type_origine);
			if($import_type == 'uni'){
				//Si on a un fichier unimarc en entrée
				global $file_in;				
				$file_in = rawurlencode($file_name);
				$sug = new suggestion_multi();
				$sug->create_table_from_uni();
				print $sug->display_form();
			} else {
				//Sinon on effectue la conversion vers l'unimarc
				print "<iframe name='import_sugg_frame' src='admin/convert/start_import.php?import_type=$import_type&file_in=".rawurlencode($file_name)."&redirect=$redirect' style='width:100%;height=500px;'></iframe>";
			}
		} elseif($explnum_id) {
			$req = "select explnum_doc_data as data from explnum_doc where id_explnum_doc='".$explnum_id."'";
			$res = mysql_query($req,$dbh);
			$expl = mysql_fetch_object($res);			
			$file_name=SESSid."_".str_replace(" ","",microtime());
			$file_name=str_replace(".","",$file_name);
			$fp=fopen("temp/".$file_name,"w+");
			fwrite($fp,$expl->data);
			fclose($fp);
			$redirect=rawurlencode("../../acquisition.php?categ=sug&sub=multi&act=import&src_liste=".$src_liste."&origine_id=".$origine_id."&type_origine=".$type_origine);
			print "<iframe name='import_sugg_frame' src='admin/convert/start_import.php?import_type=$import_type&file_in=".rawurlencode($file_name)."&redirect=$redirect' style='width:100%;height=500px;'></iframe>";
		} else {
			error_form_message($msg["field_file_copy"]);
		}
		break;
	default:
		$sug_import->show_form();
		break;
	
}

?>