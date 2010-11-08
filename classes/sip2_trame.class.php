<?php
define("UNKNOWN_ORIGIN"				, 1);
define("UNKNOWN_MESSAGE_ID"			, 2);
define("UNKNOWN_MESSAGE_NAME"		, 3);
define("INVALID_SEQUENCE_NUMBER"	, 4);
define("MESSAGE_UNDEFINED"			, 5);
define("FIXED_FIELD_MISSING"		, 6);
define("BAD_LENGTH_FIELD_VALUE"		, 7);
define("FIELD_TOO_LARGE"			, 8);
define("UNKNOWN_FIELD"				, 9);
define("MISSING_FIELDS"				,10);
define("CR_MISSING"					,11);
define("TRAME_TOO_SHORT"			,12);
define("UNAUTHORIZED_FIELD"			,13);
define("CS_AZ_MISSING"				,14);
define("BAD_CHECKSUM"				,15);

/**
 * \brief Construit/Vérifie/Décompose les trames SIP2
 * 
 * Gère les trames SIP2 en lecture / écriture / vérification en fonction des règles de la classe protocol
 * \ingroup sip2_protocol
 */
class sip2_trame {
	var $error=false;
	var $error_code=0;
	var $error_message="";
	var $message_id=0;
	var $proto;
	var $message_structure;
	
	var $trame;
	var $message_values=array();
	var $message_name;
	var $sequence_number=0;
	var $from;
	var $message_pair;
	var $checksum;
	
	function message_exists($message_id) {
		return $this->proto->messages[$message_id];
	}
	
	function set_checksum($checksum=true) {
		$this->checksum=true;
	}
	
	function set_from($from) {
		if (($from!='ACS')&&($from!='SC')) {
			$this->error=true;
			$this->error_code=UNKNOWN_ORIGIN;
			$this->error_message="La provenance du message doit être ACS ou SC";
			return false;
		} else {
			$this->from=$from;
			return $from;
		}
	}
	
	function set_message_id($id) {
		if ($this->message_structure=$this->message_exists($id)) {
			$this->message_id=$id;
			$this->message_name=$this->message_structure["NAME"];
			$from=$this->set_from($this->message_structure["FROM"]);
			if ($from) return $id; else return false;
		} else {
			$this->error=true;
			$this->error_code=UNKNOWN_MESSAGE_ID;
			$this->error_message="Le numéro de message ".$id." n'existe pas";
			return false;
		}
	}
	
	function set_message($message_name) {
		foreach($this->proto->messages as $id => $val) {
			if ($val["NAME"]==$message_name) {
				return $this->set_message_id($id);
			}
		}
		$this->error=true;
		$this->error_code=UNKNOWN_MESSAGE_NAME;
		$this->error_message="Message ".$message_name." inconnu";
		return false;
	}
	
	function set_sequence_number($sqn) {
		if (((string)($sqn*1)!=(string)$sqn)||(strlen($sqn)!=1)) {
			$this->error=true;
			$this->error_code=INVALID_SEQUENCE_NUMBER;
			$this->error_message="Numéro de séquence ".$sqn." invalide";
			return false;
		} else {
			$this->sequence_number=$sqn;
			return $sqn;
		}
	}
	
	function set_message_values($values) {
		if (!$this->message_id) {
			$this->error=true;
			$this->error_code=MESSAGE_UNDEFINED;
			$this->error_message="Il n'y a pas de message défini !";
		} else {
			$fixedf=$this->message_structure["FIXEDFIELDS"];
			//Vérification des champs fixes
			for ($i=0; $i<count($fixedf); $i++) {
				$v=$values[$fixedf[$i]];
				if ((string)$v=="") {
					$this->error=true;
					$this->error_code=FIXED_FIELD_MISSING;
					$this->error_message="Il manque le champ fixe obligatoire ".$fixedf[$i];
					return false;
				} else {
					//Vérification de la longueur de la valeur
					$field=$this->proto->fields[$fixedf[$i]];
					if (strlen((string)$v)!=$field["LEN"]) {
						$this->error=true;
						$this->error_code=BAD_LENGTH_FIELD_VALUE;
						$this->error_message="Le champ ".$fixedf[$i]." ne fait pas la bonne taille (".$field["LEN"].")";
						return false;
					}
				}
			}
			//Les champs fixes sont OK
			//Vérifications des autres
			$optionals=$this->message_structure["OPTIONALS"];
	    	if (!$optionals) $optionals=array();
	    	foreach($values as $ifield=>$val) {
	    		if (array_search($ifield,$this->message_structure["FIXEDFIELDS"])===false) {
	    			//Est-ce un champ connu ?
	    			if (array_search($ifield,$this->message_structure["FIELDS"])!==false) {
	    				if (!$optionals[$ifield]) $optionals[$ifield]=1;
	    				//Vérification de la conformité du champ
	    				$field=$this->proto->fields[$ifield];
	    				for ($v=0; $v<count($val); $v++) {
		    				if ($field["TYPE"]=="identify_fixed") {
		    					if (strlen((string)$val[$v])!=$field["LEN"]) {
		    						$this->error=true;
		    						$this->error_code=BAD_LENGTH_FIELD_VALUE;
									$this->error_message="Le champ ".$ifield." ne fait pas la bonne taille (".$field["LEN"].")";
									return false;
		    					} 
		    				} else {
		    					if (strlen((string)$val[$v])>$field["LEN"]) {
		    						$this->error=true;
		    						$this->error_code=FIELD_TOO_LARGE;
									$this->error_message="Le champ ".$fixedf[$i]." est trop grand (>".$field["LEN"]." caractères)";
									return false;
		    					} 
		    				}
	    				}
	    			} else {
	    				$this->error=true;
	    				$this->error_code=UNKNOWN_FIELD;
	    				$this->error_message="Champ ".$ifield." inconnu dans ce message";
	    				return false;
	    			}
	    		}
	    	}
	    	//Vérification des champs obligatoires
	    	$all_opt=true;
    		$err_opt=array();
    		foreach ($optionals as $fo=>$opt_value) {
    			$all_opt=(($all_opt)&&($opt_value));
    			if (!$opt_value) $err_opt[]=$fo;
    		}
    		//Tous les champs obligatoires n'ont pas étés lus
    		if (!$all_opt) {
    			$this->error=true;
    			$this->error_code=MISSING_FIELDS;
    			$this->error_message="Il manque le(s) champ(s) ".implode(",",$err_opt);
    			return false;
    		}
		}
		$this->message_values=$values;
	}
	
	function make_trame() {
		$trame=$this->message_id;
		//Ajout des champs fixes
		$fixedf=$this->message_structure["FIXEDFIELDS"];
		for ($i=0; $i<count($fixedf); $i++) {
			$trame.=$this->message_values[$fixedf[$i]];
		}
		//Champs identifiés
		$fields=$this->message_structure["FIELDS"];
		for ($i=0; $i<count($fields); $i++) {
			if ($this->message_values[$fields[$i]]) {
				for ($j=0; $j<count($this->message_values[$fields[$i]]); $j++) {
					$trame.=$this->proto->fields[$fields[$i]]["IDENTIFIER"].$this->message_values[$fields[$i]][$j]."|";
				}
			}
		}
		if ($this->checksum) {
			$sum=0;
			if ($this->message_id!=96)
				$trame.="AY".$this->sequence_number."AZ";
			else
				$trame.="AZ";
			for ($i=0; $i<strlen($trame); $i++) {
				$sum+=ord($trame[$i]);
			}
			$sum=~$sum+1;
			$sum=$sum&65535;
			$sum=strtoupper(dechex($sum));
			$trame.=$sum."\r";
		}
		$this->trame=$trame;
	}
	
    function sip2_trame($trame,$protocol) {
    	$this->proto=$protocol;
    	$this->trame=$trame;
    	
    	if ($trame) {
	    	//Analyse de la trame
	    	if ($trame[strlen($trame)-1]!=chr(0x0d)) {
	    		$this->error=true;
	    		$this->error_code=CR_MISSING;
	    		$this->error_message="<CR> attendu en fin de message";
	    		return;
	    	} else {
	    		$trame=substr($trame,0,strlen($trame)-1);
	    	}
	    	
	    	//Numéro de message
	    	$message_id=substr($trame,0,2);
	    	if ($this->message_structure=$this->message_exists($message_id)) {
	    		$this->message_id=substr($trame,0,2);
	    		$this->message_name=$this->message_structure["NAME"];
	    		$this->from=$this->message_structure["FROM"];
	    		$this->message_pair=($this->message_structure["REPLY_ID"]?$this->message_structure["REPLY_ID"]:$this->message_structure["REQUEST_ID"]);
	    		//Lecture des champs fixes
	    		$fixedf=$this->message_structure["FIXEDFIELDS"];
	    		$start_field=2;
	    		for ($i=0; $i<count($fixedf); $i++) {
	    			$field=$this->proto->fields[$fixedf[$i]];
	    			if (!$field) {
	    				$this->error=true;
	    				$this->error_code=FIXED_FIELD_MISSING;
	    				$this->error_message="Le champ fixe ".$fixedf[$i]." est inexistant";
	    				return;
	    			} else {
	    				if (strlen($trame)<$start_field+$field["LEN"]) {
	    					$this->error=true;
	    					$this->error_code=TRAME_TOO_SHORT;
	    					$this->error_message="La trame est trop courte";
	    				} else {
	    					$this->message_values[$fixedf[$i]]=substr($trame,$start_field,$field["LEN"]);
	    					$start_field+=$field["LEN"];
	    				}
	    			}
	    		}
	    		//Lecture des champs identifiés
	    		//Recherche des champs obligatoires
	    		$optionals=$this->message_structure["OPTIONALS"];
	    		if (!$optionals) $optionals=array();
	    		$fields=$this->message_structure["FIELDS"];
	    		if (!$fields) $fields=array();
	    		$flag_end=false;
	    		while (!$flag_end) {
	    			$end_field=strpos($trame,"|",$start_field);
	    			if ($end_field===false) {
	    				$flag_end=true;
	    				$end_field=strlen($trame);
	    				if ($end_field==$start_field) $end_field=false;
	    			} else if ($end_field==$start_field) {
	    					$start_field++;
	    					$end_field=strlen($trame); 
	    			}
	    			if ($end_field!==false) {
	    				$f=substr($trame,$start_field,$end_field-$start_field);
	    				if ((substr($f,0,2)!="AY")&&((substr($f,0,2)!="AZ")||($this->message_id!=97))) {
		    				$start_field=$end_field+1;
		    				//Recherche du champ
		    				$identifier=substr($f,0,2);
		    				if ($this->proto->identifiers[$identifier]) {
		    					$fname=$this->proto->identifiers[$identifier];
		    					$field=$this->proto->fields[$fname];
		    					//Est-ce un champ autorisé ?
		    					if (array_search($fname,$fields)!==false) {
		    						$this->message_values[$fname][]=substr($f,2);
		    						if (!$optionals[$fname]) $optionals[$fname]=1;
		    					} else {
		    						$this->error=true;
		    						$this->error_code=UNAUTHORIZED_FIELD;
		    						$this->error_message="Le champ ".$fname." n'est pas autorisé pour le message ".$this->message_structure["NAME"]." (".$this->message_id.")";
		    						return;
		    					}
		    				} else {
		    					//$this->error=true;
		    					//$this->error_code=UNKNOWN_FIELD;
		    					//$this->error_message="Champ ".$identifier." inexistant";
		    				}
	    				} else {
	    					//Gestion du checksum
	    					$this->checksum=true;
	    					if ($this->message_id!=97) {
	    						if ((string)($f[2]*1)!=(string)$f[2]) {
	    							//Erreur, le numéro de séquence est faux
	    							$this->error=true;
	    							$this->error_code=BAD_SEQUENCE_NUMBER;
	    							$this->error_message="Le numéro de séquence n'est pas conforme";
	    							return;
	    						} else {
	    							$this->sequence_number=$f[2];
	    							$offset=3;
	    						}
	    					} else {
	    						$this->sequence_number="";
	    						$offset=0;
	    					}
    						//Récupération du checksum
    						if (substr($f,$offset,2)!="AZ") {
    							$this->error=true;
    							$this->error_code=CS_AZ_MISSING;
    							$this->error_message="Champ checksum (AZ) attendu";
    							return;
    						} else {
    							$checksum=substr($f,$offset+2);
								//Calcul de la checksum
								$sum=0;
								for ($i=0; $i<strlen($trame)-4; $i++) {
									$sum+=ord($trame[$i]);
								}
								$sum=($sum+hexdec($checksum))&65535;
								if ($sum!=0) {
									$this->error=true;
									$this->error_code=BAD_CHEKCSUM;
									$this->error_message="La checksum est fausse";
									return;
								}
    						}
	    				}
	    			} else $flag_end=true;
	    		}
	    		//Test que tous les champs obligatoires ont été lus
	    		$all_opt=true;
	    		$err_opt=array();
	    		foreach ($optionals as $fo=>$opt_value) {
	    			$all_opt=(($all_opt)&&($opt_value));
	    			if (!$opt_value) $err_opt[]=$fo;
	    		}
	    		//Tous les champs obligatoires n'ont pas étés lus
	    		if (!$all_opt) {
	    			$this->error=true;
	    			$this->error_code=MISSING_FIELDS;
	    			$this->error_message="Il manque le(s) champ(s) ".implode(",",$err_opt);
	    			return;
	    		}
	    	} else {
	    		$this->error=true;
	    		$this->error_code=UNKNOWN_MESSAGE_ID;
	    		$this->error_message="Le message ".$message_id." est inconnu";
	    		return;
	    	}
    	}
    }
}
?>