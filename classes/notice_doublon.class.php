<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice_doublon.class.php,v 1.1 2007-09-28 15:53:51 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($include_path."/parser.inc.php");
require_once($class_path."/parametres_perso.class.php");

class notice_doublon {
	
	// constructeur
	function notice_doublon() {
		global $include_path;
		global $msg;
				
		// lecture des fonctions de pièges à exécuter pour faire un pret
		$this->parse_xml_fields($include_path."/notice/notice.xml");		
	}

	function parse_xml_fields($filename) {
		if (file_exists($filename."_subst")) $filename.="_subst";
		$fp=fopen($filename,"r") or die("Can't find XML file");
		$xml=fread($fp,filesize($filename));
		fclose($fp);
		$param=_parser_text_no_function_($xml, "FIELDS");
		
		for($i=0; $i<count($param["FIELD"]); $i++) {
			$name=$param["FIELD"][$i]["NAME"];
			$label=$param["FIELD"][$i]["LABEL"];
			$size_max=$param["FIELD"][$i]["SIZE_MAX"];
			$html= $param["FIELD"][$i]["HTML"][0]["value"];
			$sql=$param["FIELD"][$i]["SQL"][0]["value"];
			
			$this->fields[$name]["size_max"]=$size_max;		
			$this->fields[$name]["html"]= $param["FIELD"][$i]["HTML"][0]["value"];
			$this->fields[$name]["sql"]= $param["FIELD"][$i]["SQL"][0]["value"];
		}
		return 0;
	}
	
	function read_field_form($field) {
		$html=$this->fields[$field]["html"];
		$size_max=	$this->fields[$field]["size_max"];
		
		if(!$html) {
			// c'est surement un param perso
			$p_perso=new parametres_perso("notices");
			$chaine=$p_perso->read_form_fields_perso($field); 			
			return $chaine;
		} else  {
			for($i=0;$i<$size_max;$i++) {
				$chaine.=$GLOBALS[$html];
				// incrément du name de l'objet dans le formulaire
				$html++;				
			}	
			return $chaine;
		}	
	}
	
	function read_field_database($field,$id) {
		global $dbh;

		$rqt=$this->fields[$field]["sql"];	
 		if(!$rqt) {			
			// c'est surement un param perso
			$p_perso=new parametres_perso("notices");
			$chaine=$p_perso->read_base_fields_perso($field,$id); 		
			return '';	
		} else {
			$rqt=str_replace('!!id!!',$id,$rqt);			
			$result = mysql_query($rqt, $dbh);			
			if (($row = mysql_fetch_row($result) ) ) {
	        	return $row[0];
			} else {
				// rien
				return '';		
			}	
 		}	
	}
	
	function gen_signature($id=0) {
		global $dbh;
		global $msg;
		global $pmb_notice_controle_doublons;

		$field_list=split(',',str_replace(' ','',$pmb_notice_controle_doublons));
				
		// Pas de control activé en paramétrage: Sortir.
		if( ($metod = $field_list[0]) < 1 ) return 0;
		foreach($field_list as  $i => $field)
		{			
			if ($i>0){	
				if (!$id) {
					// le formulaire à lire
					$chaine.= $this->read_field_form($field);
				} else {
					// la base à lire
					$chaine.= $this->read_field_database($field,$id);
				}	
			}	
		}		
		// encodage signature par SOUNDEX (option 2) et par md5 (32 caractères)
		if($metod == 2) {	
			$rqt = "SELECT SOUNDEX('".addslashes($chaine)."')";
			$result = mysql_query($rqt, $dbh);				
			if (($row = mysql_fetch_row($result) ) ) {
	        	$chaine = $row[0];
			}					
		}		
		$val = md5($chaine);	
		return $val;
	}			

// Fin class notice_doublon		
}

?>