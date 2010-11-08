<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: xml_unimarc.class.php,v 1.5 2009-01-19 16:43:16 gueluneau Exp $

//Classe de conversion unimarc/xml ou xml/unimarc

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once("$base_path/classes/iso2709.class.php");

class xml_unimarc {

	var $n_traitees;		//Nombre de notices traitées
	var $n_valid;			//Nombre de notices valides
	var $n_invalid;			//Nombre de notices invalides

	var $fpw;				//Pointeur du fichier 
	var $n;					//Notice en cours
	var $field;				//Champ en cours de traitement
	var $field_ind;			//Indicateur du champ en cours de traitement
	var $sub_field_array;	//Tableau des sous champs
	var $s_field;			//Sous champ en cours de traitement
	var $new_field;			//Le champ en cours vient-il d'être créé
	var $new_subfield;		//Le sous champ en cours vient-il d'être créé
	var $special;			//Champ spécial d'amorçage de la notice
	var $n_;				//$n_=1 : Le début de traitement des notices à commencé
	var $field_value;		//Valeur du champ en cours
	var $notices_;			//Tableau de notices converties du XML
	var $notices_xml_;		//Tableau de notices XML converties du iso
	var $error_msg;

    function xml_unimarc() {
    	$this->n_traitees=0;
		$this->n_valid=0;
		$this->n_invalid=0;
    }
    
    function iso2709toXML($fileIn,$fileOut) {
    	global $charset;
    	$fp = @fopen($fileIn, "r");
    	if (!$fp) return 0;
		$contents = fread($fp, filesize($fileIn));
		fclose($fp);

		$fp = @fopen($fileOut, "w+");
		if (!$fp) return 0;

		fwrite($fp, "<?xml version=\"1.0\" encoding=\"$charset\" ?>\n");

		fwrite($fp, "<unimarc>\n");
		$n_notices=0;
		$n_valid=0;
		$n_invalid=0;
		
		$this->n_traitees=0;
		$this->n_valid=0;
		$this->n_invalid=0;
		
		while ($contents != "") {
			$e_notice = strpos($contents, chr(0x1d));

			$notice = substr($contents, 0, $e_notice +1);
			$contents = substr($contents, $e_notice +1);
			$n = new iso2709_record($notice);

			if ($n->valid()) {
				//Récupération des infos

				//Taille code sous-champ
				$sl = $n -> inner_guide["sl"];
				//Taille des inticateurs 
				$il = $n -> inner_guide["il"];

				fwrite($fp, "  <notice>\n");

				//Etat de la notice
				$values = array("rs", "dt", "bl", "hl", "el", "ru");
				for ($i = 0; $i < count($values); $i ++) {
					$v=$n -> inner_guide[$values[$i]];
					if (ord($v)==32) $v="*";
					fwrite($fp, "    <".$values[$i].">".$v."</".$values[$i].">\n");
				}

				for ($i = 0; $i < count($n -> inner_data); $i ++) {
					fwrite($fp, "    <f c=\"".$n -> inner_data[$i]["label"]."\"");
					$content = substr($n -> inner_data[$i]["content"], 0, strlen($n -> inner_data[$i]["content"]) - 1);
					$sub_fields = explode(chr(31), $content);
					if (count($sub_fields) == 1) {
						fwrite($fp, ">".htmlspecialchars($n -> ISO_decode($sub_fields[0]))."</f>\n");
					} else {
						if (strlen($sub_fields[0])>2) {
							$sub_fields[0]=substr($sub_fields[0],strlen($sub_fields[0])-2);
						}
						fwrite($fp, " ind=\"".$sub_fields[0]."\">\n");
						for ($j = 1; $j < count($sub_fields); $j ++) {
							fwrite($fp, "      <s c=\"".substr($sub_fields[$j], 0, 1)."\">".htmlspecialchars($n -> ISO_decode(substr($sub_fields[$j], 1)))."</s>\n");
						}
						fwrite($fp, "    </f>\n");
					}
				}
				fwrite($fp, "  </notice>\n");
				$n_valid++;
			} else {
				$n_invalid++;
			}
			$n_notices++;
		}
		fwrite($fp, "</unimarc>\n");
		fclose($fp);
		
		$this->n_traitees=$n_notices;
		$this->n_valid=$n_valid;
		$this->n_invalid=$n_invalid;
		return $n_notices;
    }

	function iso2709toXML_notice($contents) {
		
		$n_notices=0;
		$n_valid=0;
		$n_invalid=0;
		
		$this->n_traitees=0;
		$this->n_valid=0;
		$this->n_invalid=0;
		$this->error_msg=array();
		$this->notices_xml_=array();
		
		while ($contents != "") {
			$e_notice = strpos($contents, chr(0x1d));

			$notice = substr($contents, 0, $e_notice +1);
			$contents = substr($contents, $e_notice +1);
			$n = new iso2709_record($notice);

			if ($n->valid()) {
				//Récupération des infos

				//Taille code sous-champ
				$sl = $n -> inner_guide["sl"];
				//Taille des inticateurs 
				$il = $n -> inner_guide["il"];

				$data.="  <notice>\n";

				//Etat de la notice
				$values = array("rs", "dt", "bl", "hl", "el", "ru");
				for ($i = 0; $i < count($values); $i ++) {
					$v=$n -> inner_guide[$values[$i]];
					if (ord($v)==32) $v="*";
					$data.="    <".$values[$i].">".$v."</".$values[$i].">\n";
				}

				for ($i = 0; $i < count($n -> inner_data); $i ++) {
					$data.="    <f c=\"".$n -> inner_data[$i]["label"]."\"";
					$content = substr($n -> inner_data[$i]["content"], 0, strlen($n -> inner_data[$i]["content"]) - 1);
					$sub_fields = explode(chr(31), $content);
					if (count($sub_fields) == 1) {
						$data.=">".htmlspecialchars($n -> ISO_decode($sub_fields[0]))."</f>\n";
					} else {
						if (strlen($sub_fields[0])>2) {
							$sub_fields[0]=substr($sub_fields[0],strlen($sub_fields[0])-2);
						}
						$data.=" ind=\"".$sub_fields[0]."\">\n";
						for ($j = 1; $j < count($sub_fields); $j ++) {
							$data.="      <s c=\"".substr($sub_fields[$j], 0, 1)."\">".htmlspecialchars($n -> ISO_decode(substr($sub_fields[$j], 1)))."</s>\n";
						}
						$data.="    </f>\n";
					}
				}
				$data.="  </notice>\n";
				$this->notices_xml_[]=$data;
				$n_valid++;
			} else {
				$this->error_msg[]=@implode(" / ",$n->errors);
				$n_invalid++;
			}
			$n_notices++;
		}
		
		$this->n_traitees=$n_notices;
		$this->n_valid=$n_valid;
		$this->n_invalid=$n_invalid;
		return $n_notices;
    }

	function startElement($parser, $name, $attrs) {
		switch ($name) {
			case "NOTICE":
				$this->n=new iso2709_record('',0);
				$this->n_=1;
			break;
			case "F":
				$this->field=$attrs["C"];
				$this->field_ind=$attrs["IND"];
				$this->sub_field_array=array();
				$this->field_value="";
				$this->new_field=true;
			break;
			case "S":
				$this->s_field=$attrs["C"];
				$this->new_subfield=true;
			break;
			default:
				if ($this->n_) $this->special=$name;
			break;
		}
	}
    
    function endElement($parser, $name) {
		switch ($name) {
			case "NOTICE":
				$this->n->update();
				if ($this->n->valid()) { 
					fwrite($this->fpw,$this->n->full_record);
					$this->n_valid++;
				} else {
					$this->error_msg[]=@implode(" / ",$this->n->errors);
					$this->n_invalid++;
				}
				$this->n_traitees++;
				$this->n_="";
			break;
			case "F":
				if (count($this->sub_field_array)) $this->n->add_field($this->field,$this->field_ind,$this->sub_field_array); else $this->n->add_field($this->field,'',$this->field_value);
				$this->field="";
				$this->field_ind="";
			break;
			case "S":
				$this->s_field="";
			break;
			default:
				if ($this->n_) $this->special="";
			break;
		}
	}

	function characterData($parser,$data) {
		//$data=trim($data);
		if ($data=="") return;
		
		//Si l'on est dans une notice
		if ($this->n_) {
			if ($this->special) {
				if ($data=="*") $data=" ";
				eval("\$this->n->set_".strtolower($this->special)."('".$data."');");
				return;
			}
			if ($this->s_field) {
				//Gestion des entités
				if ($this->new_subfield) {
					$t=array();
					$t[0]=$this->s_field;
					$t[1]=$data;
					$this->sub_field_array[]=$t;
					$this->new_subfield=false;
					return;
				} else {
					$this->sub_field_array[count($this->sub_field_array)-1][1].=$data;
					return;
				}
			}
			if ($this->field) {
				//Gestion des entités
				if ($this->new_field) {
					$this->field_value=$data;
					$this->new_field=false;
				} else $this->field_value.=$data;
			}
		}
	}

	
    function XMLtoiso2709($fileIn,$fileOut) {
    	global $charset;
    	$this->fpw=fopen($fileOut,"w+"); 
    	if (!$this->fpw) return 0;

		$this->n_traitees=0;
		$this->n_valid=0;
		$this->n_invalid=0;
		$this->field="";
		$this->s_field="";
		$this->n="";
		$this->n_="";
		$this->sub_field_array=array();
		
		if (!($fp = fopen($fileIn, "r"))) {
		    return 0;
		}

		$file_size=filesize ($filein);
		$data = fread ($fp, $file_size);
		
		$rx = "/<?xml.*encoding=[\'\"](.*?)[\'\"].*?>/m";
		if (preg_match($rx, $data, $m)) $encoding = strtoupper($m[1]);
			else $encoding = "ISO-8859-1";
		
		$xml_parser = xml_parser_create($encoding);
		xml_parser_set_option($xml_parser, XML_OPTION_TARGET_ENCODING, $charset);		
		xml_set_object($xml_parser, &$this);
		xml_set_element_handler($xml_parser, "startElement", "endElement");
		xml_set_character_data_handler($xml_parser, "characterData");


		while ($data = fread($fp, 4096)) {
		    if (!xml_parse($xml_parser, $data, feof($fp))) {
   		     $this->error_msg[]=sprintf("XML error: %s at line %d, column %d",
       	     xml_error_string(xml_get_error_code($xml_parser)),
       	     xml_get_current_line_number($xml_parser), xml_get_current_column_number($xml_parser));
       	     return 0;
 	   		}
		}
		xml_parser_free($xml_parser);

		fclose($this->fpw);
		return $this->n_traitees;
    }
    
    function endElement_notice($parser, $name) {
		switch ($name) {
			case "NOTICE":
				$this->n->update();
				if ($this->n->valid()) { 
					$this->notices_[]=$this->n->full_record;
					$this->n_valid++;
				} else {
					$this->error_msg[]=@implode(" / ",$this->n->errors);
					$this->n_invalid++;
				}
				$this->n_traitees++;
				$this->n_="";
			break;
			case "F":
				if (count($this->sub_field_array)) $this->n->add_field($this->field,$this->field_ind,$this->sub_field_array); else $this->n->add_field($this->field,'',$this->field_value);
				$this->field="";
				$this->field_ind="";
			break;
			case "S":
				$this->s_field="";
			break;
			default:
				if ($this->n_) $this->special="";
			break;
		}
	}
    
    function XMLtoiso2709_notice($notice) {
    	global $charset;
 		$this->n_traitees=0;
		$this->n_valid=0;
		$this->n_invalid=0;
		$this->field="";
		$this->s_field="";
		$this->n="";
		$this->n_="";
		$this->sub_field_array=array();
		
		$this->notices_=array();
		$this->error_msg=array();
		
    	if (strpos($notice,"<?xml")===false) {
			$notice="<?xml version='1.0' encoding='".$charset."' ?>\n".$notice;
		}
		
		$rx = "/<?xml.*encoding=[\'\"](.*?)[\'\"].*?>/m";
		if (preg_match($rx, $notice, $m)) $encoding = strtoupper($m[1]);
			else $encoding = "ISO-8859-1";
		
		$xml_parser = xml_parser_create($encoding);
		xml_parser_set_option($xml_parser, XML_OPTION_TARGET_ENCODING, $charset);		
		xml_set_object($xml_parser, &$this);
		xml_set_element_handler($xml_parser, "startElement", "endElement_notice");
		xml_set_character_data_handler($xml_parser, "characterData");
	    if (!xml_parse($xml_parser, $notice, 1)) {
	    	$this->error_msg[]=sprintf("XML error: %s at line %d, colomn %d -- $notice",
       	     xml_error_string(xml_get_error_code($xml_parser)),
       	     xml_get_current_line_number($xml_parser), xml_get_current_column_number($xml_parser));
   		     return 0;
 	   	}
		xml_parser_free($xml_parser);

		return $this->n_traitees;
    }
}
?>