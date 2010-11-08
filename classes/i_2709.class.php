<?php

define("IS3",chr(0x1d));			//Caractère de fin d'enregistrement
define("IS2",chr(0x1e));			//Caractère de fin de champ
define("IS1",chr(0x1f));			//Caractère de début de sous champ
define("NSBB",chr(0x88));			//Caractère de début "non sorting bloc"
define("NSBE",chr(0x89));			//Caractère de fin "non sorting bloc"


class iso2709 {
	//Parties brutes de la notice
	var $notice;					//Notice complète
	var $guide;						//Guide
	var $directory;					//Directory
	var $data;						//Données
	
	//Caractères spéciaux de synchronisation
	var $record_end;				//Caractère de fin d'enregistrement
	var $field_end;					//Caractère de fin de champ
	var $subfield_begin;			//Caractère de début de sous champ
	var $NSB_begin;					//Caractère début "non sorting bloc"
	var $NSB_end;					//Caractère fin "non sorting bloc"
	
	//Longueurs d'encodage de certaines données
	var $ind_lenght=2;				//Longeur des indicateurs (en nombre de caractères)
	var $subfield_code_lenght=2;	//Longueur du code sous champ (subfield_begin compris)
	var $zone_lenght=4;				//Nombre de caractères pour coder la longueur d'un champ complet
	var $first_pos=5;				//Nombre de caractères pour coder la position d'un champ dans la zone data

	//Champs calculés
	var $total_lenght;				//Longueur totale de la notice (calculée à la génération)
	var $data_pos;					//Position de la zone de données dans la notice (calculée à la génération)
	
	//Champs propres au type de données
	var $statut;					//Statut marc de la notice
	var $application_codes;			//Codes propres au type de données
	var	$supplementary;				//Codes supplémentaires propres au type de données
	
	//Champs décodés
	var $guide_infos=array();		//Tableaux des codes propres au type de données
	var $directory_table=array();	//Table décodée du répertoire
	var $fields;					//Tableau des champs / sous-champs décodés
	
	//Gestion des erreurs
	var $error=false;				//Indicateur d'erreur
	var $error_message="";			//Message d'erreur
	
	/*
		Vérification de la cohérence du format de la notice :
			-Vérifie les longueurs, la place des zones, que le répertoire correspond à la zone de données
	*/
	function general_check() {
		//Vérifications sommaires
		//La taille de la notice est-elle correcte ?
		if ($this->total_lenght!=strlen($this->notice)) {
			$this->error=true;
			$this->error_message="La longueur de la notice ne correspond pas aux informations du guide ".$this->total_lenght." ".strlen($this->notice);
			return false;
		}
		
		//La fin de la notice est-elle bien la fin de notice ?
		if (substr($this->notice,strlen($this->notice)-1,1)!=IS3) {
			$this->error=true;
			$this->error_message="La notice est tronquée ou ce n'est pas une notice";
			return false;
		}
		
		//Vérification du directory et structure
		//Si le caractère précédent le début des données n'est pas field_end alors il y a un problème
		if (substr($this->directory,strlen($this->directory)-1,1)!=IS2) {
			$this->error=true;
			$this->error_message="Le répertoire ou la zone de données ne semble pas être au bon endroit";
			return false;
		}
		//Parse du directory
		$dir_entry_lenght=3+$this->zone_lenght+$this->first_pos;
		if (((strlen($this->directory)-1) % $dir_entry_lenght)) {
			$this->error=true;
			$this->error_message="Le répertoire n'a pas la bonne taille";
		} else {
			$nb_fields=(strlen($this->directory)-1)/$dir_entry_lenght;
			for ($i=0; $i<$nb_fields; $i++) {
				$label=substr($this->directory,$i*$dir_entry_lenght,3);
				$lzone=substr($this->directory,$i*$dir_entry_lenght+3,$this->zone_lenght)*1;
				$fpos=substr($this->directory,$i*$dir_entry_lenght+3+$this->zone_lenght,$this->first_pos)*1;
				$this->directory_table[$i]["POS"]=$fpos;
				$this->directory_table[$i]["LENGHT"]=$lzone;
				$this->directory_table[$i]["LABEL"]=$label;
				if (substr($this->data,$fpos+$lzone-1,1)!=IS2) {
					$this->error=true;
					$this->error_message="Erreur sur le champ n°".($i+1)." : le code de fin de champ n'a pas été trouvé";
					return false;
				}
			}
		}
		return true;
	}
	
	
	function get_guide_infos() {
		//A surcharger
	}
	
	function create_guide_infos() {
		//A surcharger
	}
	
	function check_guide_infos() {
		//A surcharger
	}
	
	function default_guide_infos() {
		//A surcharger
	}
	
	function default_statut() {
		//A surcharger
	}
	
	/*
		Lecture du guide et extractions des différentes zones de la notice (guide, directory, data)
	*/
	function read_guide() {
		
		//Extraction du guide
		$this->guide=substr($this->notice,0,24);
		
		//Lecture théorique du guide
		//Longueur totale de la notice
		$this->total_lenght=substr($this->guide,0,5)*1;
		//Longueur de l'indicateur
		$this->ind_lenght=substr($this->guide,10,1)*1;
		//Longueur du code de sous champ 
		$this->subfield_code_lenght=substr($this->guide,11,1)*1;
		//Longueur de zone
		$this->zone_lenght=substr($this->guide,20,1)*1;
		//Longeur de la position du premier caractère
		$this->first_pos=substr($this->guide,21,1)*1;
		//Position du premier caractère de la zone de données
		$this->data_pos=substr($this->guide,12,5)*1;
		//Statut de la notice
		$this->statut=substr($this->guide,5,1);
		//Codes d'application
		$this->application_codes=substr($this->guide,6,4);
		//Définitions supplémentaires
		$this->supplementary=substr($this->guide,17,3);
		
		//Extractions des infos propres au type de notice
		$this->get_guide_infos();
		
		//Extraction du directory
		$this->directory=substr($this->notice,24,$this->data_pos-24);
		//Extraction des données
		$this->data=substr($this->notice,$this->data_pos);
		
		//Vérifications générales
		if (!$this->general_check()) return false;
		
		//Vérifications spécifiques
		return $this->check_guide_infos();
	}

	/*
		Tables de conversion ISO 646 & 5426 / ISO 8859-15
	*/
	function iso_tables() {
		global $ISO5426,$ISO5426_dia,$ISO8859_15,$ISO8859_15_dia;
		//Tableaux de correspondance de ISO646/5426 vers ISO8859-15
			$ISO5426_dia=array(
				chr(0xc1).chr(0x41)=>chr(0xc0),chr(0xc1).chr(0x45)=>chr(0xc8),chr(0xc1).chr(0x49)=>chr(0xcc),
				chr(0xc1).chr(0x4f)=>chr(0xd2),chr(0xc1).chr(0x55)=>chr(0xd9),chr(0xc1).chr(0x61)=>chr(0xe0),
				chr(0xc1).chr(0x65)=>chr(0xe8),chr(0xc1).chr(0x69)=>chr(0xec),chr(0xc1).chr(0x6f)=>chr(0xf2),
				chr(0xc1).chr(0x75)=>chr(0xf9),chr(0xc2).chr(0x41)=>chr(0xc1),chr(0xc2).chr(0x45)=>chr(0xc9),
				chr(0xc2).chr(0x49)=>chr(0xcd),chr(0xc2).chr(0x4f)=>chr(0xd3),chr(0xc2).chr(0x55)=>chr(0xda),
				chr(0xc2).chr(0x59)=>chr(0xdd),chr(0xc2).chr(0x61)=>chr(0xe1),chr(0xc2).chr(0x65)=>chr(0xe9),
				chr(0xc2).chr(0x69)=>chr(0xed),chr(0xc2).chr(0x6f)=>chr(0xf3),chr(0xc2).chr(0x75)=>chr(0xfa),
				chr(0xc2).chr(0x79)=>chr(0xfd),chr(0xc3).chr(0x41)=>chr(0xe2),chr(0xc3).chr(0x45)=>chr(0xca),
				chr(0xc3).chr(0x49)=>chr(0xce),chr(0xc3).chr(0x4f)=>chr(0xd4),chr(0xc3).chr(0x55)=>chr(0xdb),
				chr(0xc3).chr(0x61)=>chr(0xe2),chr(0xc3).chr(0x65)=>chr(0xea),chr(0xc3).chr(0x69)=>chr(0xee),
				chr(0xc3).chr(0x6f)=>chr(0xf4),chr(0xc3).chr(0x75)=>chr(0xfb),chr(0xc4).chr(0x41)=>chr(0xc3),
				chr(0xc4).chr(0x4e)=>chr(0xd1),chr(0xc4).chr(0x4f)=>chr(0xd5),chr(0xc4).chr(0x61)=>chr(0xe3),
				chr(0xc4).chr(0x6e)=>chr(0xf1),chr(0xc4).chr(0x6f)=>chr(0xf5),chr(0xc8).chr(0x41)=>chr(0xc4),
				chr(0xc8).chr(0x45)=>chr(0xcb),chr(0xc8).chr(0x49)=>chr(0xcf),chr(0xc8).chr(0x4f)=>chr(0xd6),
				chr(0xc8).chr(0x55)=>chr(0xdc),chr(0xc8).chr(0x59)=>chr(0xbe),chr(0xc8).chr(0x61)=>chr(0xe4),
				chr(0xc8).chr(0x65)=>chr(0xeb),chr(0xc8).chr(0x69)=>chr(0xef),chr(0xc8).chr(0x6f)=>chr(0xf6),
				chr(0xc8).chr(0x75)=>chr(0xfc),chr(0xc8).chr(0x79)=>chr(0xff),chr(0xc9).chr(0x41)=>chr(0xc4),
				chr(0xc9).chr(0x45)=>chr(0xcb),chr(0xc9).chr(0x49)=>chr(0xcf),chr(0xc9).chr(0x4f)=>chr(0xd6),
				chr(0xc9).chr(0x55)=>chr(0xdc),chr(0xc8).chr(0x59)=>chr(0xbe),chr(0xc9).chr(0x61)=>chr(0xe4),
				chr(0xc9).chr(0x65)=>chr(0xeb),chr(0xc9).chr(0x69)=>chr(0xef),chr(0xc9).chr(0x6f)=>chr(0xf6),
				chr(0xc9).chr(0x75)=>chr(0xfc),chr(0xc9).chr(0x79)=>chr(0xff),chr(0xca).chr(0x41)=>chr(0xc5),
				chr(0xca).chr(0x61)=>chr(0xe5),chr(0xd0).chr(0x43)=>chr(0xc7),chr(0xd0).chr(0x63)=>chr(0xe7),
				chr(0xcf).chr(0x53)=>chr(0xa6),chr(0xcf).chr(0x73)=>chr(0xa8),chr(0xcf).chr(0x5a)=>chr(0xb4),
				chr(0xc5).chr(0x20)=>chr(0xaf),chr(0xca).chr(0x20)=>chr(0xb0),chr(0xc7).chr(0x20)=>chr(0xba)
			);
			
			$ISO5426=array(
				chr(0xa0)=>chr(0xa0),chr(0xa1)=>chr(0xa1),chr(0xa2)=>chr(0x22),chr(0xa3)=>chr(0xa3),
				chr(0xa4)=>chr(0xa4),chr(0xa5)=>chr(0xa5),chr(0xa6)=>chr(0x3f),chr(0xa7)=>chr(0xa7),
				chr(0xa8)=>chr(0x27),chr(0xa9)=>chr(0x60),chr(0xaa)=>chr(0x22),chr(0xab)=>chr(0xab),
				chr(0xac)=>chr(0x62),chr(0xad)=>chr(0xa9),chr(0xae)=>chr(0x28).chr(0x50).chr(0x29) ,
				chr(0xaf)=>chr(0xae),chr(0xb0)=>chr(0x3f),chr(0xb1)=>chr(0x3f),chr(0xb2)=>chr(0x2c),
				chr(0xb3)=>chr(0x3f),chr(0xb4)=>chr(0x3f),chr(0xb5)=>chr(0x3f),chr(0xb6)=>chr(0x3f),
				chr(0xb7)=>chr(0xb7),chr(0xb8)=>chr(0x27).chr(0x27),chr(0xb9)=>chr(0x27),chr(0xba)=>chr(0x22),
				chr(0xbb)=>chr(0xbb),chr(0xbc)=>chr(0x23),chr(0xbd)=>chr(0x27),chr(0xbe)=>chr(0x22),
				chr(0xbf)=>chr(0xbf),chr(0xe0)=>chr(0x3f),chr(0xe1)=>chr(0xc6),chr(0xe2)=>chr(0xd0),
				chr(0xe3)=>chr(0x3f),chr(0xe4)=>chr(0x3f),chr(0xe5)=>chr(0x3f),chr(0xe6)=>chr(0x49).chr(0x4a),
				chr(0xe7)=>chr(0x3f),chr(0xe8)=>chr(0x4c),chr(0xe9)=>chr(0xd8),chr(0xea)=>chr(0xbc),
				chr(0xeb)=>chr(0x3f),chr(0xec)=>chr(0xde),chr(0xed)=>chr(0x3f),chr(0xee)=>chr(0x3f),
				chr(0xef)=>chr(0x3f),chr(0xf0)=>chr(0x3f),chr(0xf1)=>chr(0xe6),chr(0xf2)=>chr(0x64),
				chr(0xf3)=>chr(0xf0),chr(0xf4)=>chr(0x3f),chr(0xf5)=>chr(0x69),chr(0xf6)=>chr(0x69).chr(0x6a),
				chr(0xf7)=>chr(0x3f),chr(0xf8)=>chr(0x6c),chr(0xf9)=>chr(0xf8),chr(0xfa)=>chr(0xbd),
				chr(0xfb)=>chr(0xdf),chr(0xfc)=>chr(0xfe),chr(0xfd)=>chr(0x3f),chr(0xfe)=>chr(0x3f),
				chr(0xff)=>chr(0x3f)
			);
			
			//Tableaux de correspondance de ISO8859-15 vers ISO646/5426
			//Pour les diacritiques, il y a correspondance biunivoque, on fait donc une inversion du tableau
			$ISO8859_15_dia=array_flip($ISO5426_dia);
			
			//Pour les caractères spéciaux, la transformation n'est pas biunivoque
			$ISO8859_15=array(
				chr(0xa0)=>chr(0xa0),chr(0xa1)=>chr(0xa1),chr(0xa2)=>chr(0x3f),chr(0xa3)=>chr(0xa3),
				chr(0xa4)=>chr(0xa4),chr(0xa5)=>chr(0xa5),chr(0xa6)=>chr(0xcf).chr(0x53),chr(0xa7)=>chr(0xa7),
				chr(0xa8)=>chr(0xcf).chr(0x73),chr(0xa9)=>chr(0xad),chr(0xaa)=>chr(0x41),chr(0xab)=>chr(0xab),
				chr(0xac)=>chr(0x3f),chr(0xad)=>chr(0xa0),chr(0xae)=>chr(0xaf),chr(0xb1)=>chr(0xd8).chr(0x2b),
				chr(0xb2)=>chr(0x32),chr(0xb3)=>chr(0x33),chr(0xb4)=>chr(0xcf).chr(0x5a),chr(0xb5)=>chr(0x75),
				chr(0xb6)=>chr(0x20),chr(0xb7)=>chr(0xb7),chr(0xb8)=>chr(0xcf).chr(0x7a),chr(0xb9)=>chr(0x31),
				chr(0xbb)=>chr(0xbb),chr(0xbc)=>chr(0xea),chr(0xbd)=>chr(0xfa),chr(0xbf)=>chr(0xbf),
				chr(0xc6)=>chr(0xe1),chr(0xd0)=>chr(0xe2),chr(0xd7)=>chr(0x2a),chr(0xd8)=>chr(0xe9),
				chr(0xde)=>chr(0xec),chr(0xdf)=>chr(0xfb),chr(0xe6)=>chr(0xf1),chr(0xf0)=>chr(0xf3),
				chr(0xf7)=>chr(0x2f),chr(0xf8)=>chr(0xf9),chr(0xfe)=>chr(0xfc)
			);
	}
	
	/*
		Conversion d'une chaine ISO 8859-15 en ISO 646/5426
	*/
	function ISO_646_5426_encode($string) {
		global $ISO5426,$ISO5426_dia,$ISO8859_15,$ISO8859_15_dia;
		if (!$ISO5426) {
			$this->iso_tables();
		}
		
		$string_r="";
		for ($i=0; $i<strlen($string); $i++) {
			if ($string[$i]<chr(0xa0)) 
				$string_r.=$string[$i];
			else if ($ISO8859_15_dia[$string[$i]])
				$string_r.=$ISO8859_15_dia[$string[$i]];
			else if ($ISO8859_15[$string[$i]])
				$string_r.=$ISO8859_15[$string[$i]];
			else
				$string_r.="?";
		}
		return $string_r;
	}
	
	//	Conversion d'une chaine ISO 646 / 5426 an ISO 8859-15

	function ISO_646_5426_decode($string) {
		global $ISO5426,$ISO5426_dia,$ISO8859_15,$ISO8859_15_dia;
		if (!$ISO5426) {
			$this->iso_tables();
		}
		//Remplacement des symboles et caractères spéciaux
		$string_r="";
		for ($i=0; $i<strlen($string); $i++) {
			//Si c'est un caractère avant 0xA0 alors rien a changer
			if ($string[$i]<chr(0xA0)) 
				$string_r.=$string[$i];
			else if (($string[$i]>=chr(0xC0))&&($string[$i]<=chr(0xDF))) {
				//Si c'est un diacritique on regarde le caractère suivant et on cherche dans la table de correspondance
				$car=$string[$i].$string[$i+1];
				//Si le caractère est connu
				if ($ISO5426_dia[$car]) {
					$string_r.=$ISO5426_dia[$car];
				} else {
					//Sinon on ne tient juste pas compte du diacritique
					$string_r.=$string[$i+1];
				}
				//On avance d'un caractère
				$i++;
			} else {
				//Sinon c'est un catactère spécial ou un symbole
				$car=$string[$i];
				$string_r.=$ISO5426[$car];
			}
		}
		$string_r=str_replace(NSBB,"",$string_r);
		$string_r=str_replace(NSBE,"",$string_r);
		return $string_r;
	}
	
	//	Extraction des champs dans le tableau fields
	
	function read_fields() {
		//Lecture des champs
		for ($i=0; $i<count($this->directory_table); $i++) {
			//Position et longueur du champ dans data 
			$fpos=$this->directory_table[$i]["POS"];
			$lzone=$this->directory_table[$i]["LENGHT"];
			$label=$this->directory_table[$i]["LABEL"];
			$subfields_string=substr($this->data,$fpos,$lzone);
			$subfields=explode(IS1,substr($subfields_string,0,strlen($subfields_string)-1));
			if (count($subfields)==1) {
				$this->fields[$label][]["value"]=$this->ISO_646_5426_decode($subfields[0]);
			} else {
				$n=count($this->fields[$label]);
				$this->fields[$label][$n]["IND"]=$subfields[0];
				for ($j=1; $j<count($subfields); $j++) {
					$sf=substr($subfields[$j],0,1);
					$this->fields[$label][$n][$sf][]=$this->ISO_646_5426_decode(substr($subfields[$j],1));
				}
			}
		}			
	}
	
	//	Génération au format iso2709 de la notice à partir du tableau fields, de statut, de guide_infos
	
	function gen_iso2709() {
		//Longueur maximum d'une zone
		$max_zone_lenght=str_repeat("9",$this->zone_lenght)*1;
		//Position maximum dans la zone de données
		$max_first_pos=str_repeat("9",$this->first_pos)*1;
		//Si les données propres à la notice sont bonnes alors on construit la notice au format iso 2709
		if ($this->create_guide_infos()) {
			//Construction du guide
			$this->guide="%s".$this->statut.$this->application_codes.$this->ind_lenght.$this->subfield_code_lenght."%s".$this->supplementary.$this->zone_lenght.$this->first_pos."  ";
			//Construction du répertoire & de la zone de données
			$this->directory="";
			$this->data="";
			$this->notice="";
			$this->directory_table=array();
			$n=0;
			reset($this->fields);
			while (list($key,$val)=each($this->fields)) {
				if (strlen($key)!=3) {
					$this->error=true;
					$this->error_message="Un label n'a pas la bonne taille (3 caractères)";
					return false;
				}
				for ($i=0; $i<count($val); $i++) {
					//Construction de la zone data
					$data="";
					reset($val[$i]);
					//Traitement du cas spécial sans sous champs
					if (isset($val[$i]["value"])) {
						$data=$this->ISO_646_5426_encode($val[$i]["value"]);
					} else {
						//Sinon il y a des sous champs
						if (strlen($val[$i]["IND"])==$this->ind_lenght) {
							$data.=$val[$i]["IND"];
						} else if ($val[$i]["IND"]=="") {
							$data.=str_repeat(" ",$this->ind_lenght);
						} else {
							$this->error=true;
							$this->error_message="Un indicateur n'a pas la bonne taille !";
							return false;
						}
						while (list($key_s,$val_s)=each($val[$i])) {
							if ($key_s!="IND") {
								for ($j=0;$j<count($val_s); $j++) {
									if (strlen($key_s)!=$this->subfield_code_lenght-1) {
										$this->error=true;
										$this->error_message="Un code sous champ n'est pas de la bonne taille";
										return false;
									} else {
										$data.=IS1.$key_s.$this->ISO_646_5426_encode($val_s[$j]);
									}
								}
							}
						}
					}
					//J'ai mon data qui est prêt
					//Ajout du code de fin de champ
					$data.=IS2;
					if (strlen($data)>$max_zone_lenght) {
						$this->error=true;
						$this->error_message="Un champ dépasse la taille maximum autorisée";
						return false;
					}
					if (strlen($this->data)>$max_first_pos) {
						$this->error=true;
						$this->error_message="La taille de la zone de données est supérieure au maximum autorisé";
						return false;
					}
					$this->directory.=$key.str_pad((string)strlen($data),$this->zone_lenght,"0",STR_PAD_LEFT).str_pad((string)strlen($this->data),$this->first_pos,"0",STR_PAD_LEFT);
					$this->directory_table[$n]["POS"]=strlen($this->data);
					$this->directory_table[$n]["LENGHT"]=strlen($data);
					$this->directory_table[$n]["LABEL"]=$key;
					$this->data.=$data;
					$n++;
				}
			}
			//Ajout code fin de champ au répertoire
			$this->directory=$this->directory.IS2;
			
			//J'ai tout construit : données et repertoire, on calcule les tailles
			//Taille totale
			$this->total_lenght=24+strlen($this->directory)+strlen($this->data)+1;
			if ($this->total_lenght>99999) {
				$this->error=true;
				$this->error_message="La taille totale de la notice dépasse la longueur maximum autorisée";
				return false;
			}
			//Position de la zone de données
			$this->data_pos=24+strlen($this->directory);
			$this->guide=sprintf($this->guide,str_pad((string)$this->total_lenght,5,"0",STR_PAD_LEFT),str_pad((string)$this->data_pos,5,"0",STR_PAD_LEFT));
			
			$this->notice=$this->guide.$this->directory.$this->data.IS3;
		}
	}

	/*
		Sortie format texte de la notice
	*/
	function get_txt() {
		$txt_notice="";
		$txt_notice.="rs ".(trim($this->statut)?$this->statut:"*")."\n";
		reset($this->guide_infos);
		while (list($key,$val)=each($this->guide_infos)) {
			$txt_notice.=$key." ".(trim($val)?$val:"*")."\n";
		}
		reset($this->fields);
		while (list($key,$val)=each($this->fields)) {
			for ($i=0; $i<count($val); $i++) {
				$txt_notice.=$key." ";
				reset($val[$i]);
				while (list($key_s,$val_s)=each($val[$i])) {
					if ($key_s=="IND") $txt_notice.="(".str_pad($val_s,$this->ind_lenght," ").") "; else {
						if ($key_s=="value") { $txt_notice.=$val_s; break; }
						for ($j=0; $j<count($val_s); $j++) {
							$txt_notice.="\$".$key_s." ".$val_s[$j]." ";
						}
					}
				}
				$txt_notice.="\n";
			}
		}
		$txt_notice.="\n";
		return $txt_notice;
	}
	
	/*
		Sortie d'un tableau structuré pour du XML
	*/
	function get_xml_table() {
		$xml_table=array();
		$xml_table["rs"][0]["value"]=$this->statut;
		reset($this->guide_infos);
		while (list($key,$val)=each($this->guide_infos)) {
			$xml_table[$key][0]["value"]=$val;
		}
		reset($this->fields);
		while (list($key,$val)=each($this->fields)) {
			for ($i=0; $i<count($val); $i++) {
				reset($val[$i]);
				while (list($key_s,$val_s)=each($val[$i])) {
					if (!is_array($val_s)) 
						$xml_table[$key][$i][$key_s]=$val_s;
					else
						for ($j=0; $j<count($val_s); $j++) {
							$xml_table["_".$key][$i]["_".$key_s][$j]["value"]=$val_s[$j];
						}
				}
			}
		}
		return $xml_table;
	}
	
	/*
		Sortie d'un tableau structuré pour du XML, avec les codes champs et sous champs en indicateurs
		au lieu de tags
	*/
	function get_translated_xml_table() {
		$xml_table=array();
		$xml_table["RS"][0]["value"]=$this->statut;
		reset($this->guide_infos);
		while (list($key,$val)=each($this->guide_infos)) {
			$xml_table[strtoupper($key)][0]["value"]=$val;
		}
		reset($this->fields);
		$nf=0;
		while (list($key,$val)=each($this->fields)) {
			for ($i=0; $i<count($val); $i++) {
				reset($val[$i]);
				$xml_table["F"][$nf]["C"]=$key;
				while (list($key_s,$val_s)=each($val[$i])) {
					if (!is_array($val_s)) {
						$xml_table["F"][$nf][$key_s]=$val_s;
					} else {
						for ($j=0; $j<count($val_s); $j++) {
							$xml_table["F"][$nf]["S"][$j]["C"]=$key_s;
							$xml_table["F"][$nf]["S"][$j]["value"]=$val_s[$j];
						}
					}
				}
			}
			$nf++;
		}
		return $xml_table;
	}
	
	/*
		Sortie au format XML de la notice
	*/
	function get_xml() {
		$xml="  <rs>".htmlspecialchars(trim($this->statut)?$this->statut:"*")."</rs>\n";
		reset($this->guide_infos);
		while (list($key,$val)=each($this->guide_infos)) {
			$xml.="  <".$key.">".htmlspecialchars(trim($val)?$val:"*")."</$key>\n";
		}
		reset($this->fields);
		while (list($key,$val)=each($this->fields)) {
			for ($i=0; $i<count($val); $i++) {
				$xml.="  <_".$key;
				reset($val[$i]);
				$att="";
				$value="";
				$cr="\n";
				while (list($key_s,$val_s)=each($val[$i])) {
					if (!is_array($val_s)) {
						if ($key_s!="value") 
							$att.=" ".strtolower($key_s)."='".htmlspecialchars($val_s)."'";
						else {
							$value=htmlspecialchars($val_s);
							$cr="";
						}
					} else
						for ($j=0; $j<count($val_s); $j++) {
							$value.="    <_".$key_s.">".htmlspecialchars($val_s[$j])."</_$key_s>\n";
						}
				}
				$xml.=$att.">".$cr.$value.($cr?"  ":"")."</_$key>\n";
			}
		}
		return "<?xml version='1.0' encoding='iso-8859-15'?>\n<notice>\n".$xml."</notice>\n";
	}
	
	function create_from_translated_xml_table($xml_table) {
		//Réinitialisation des variables de la classe
		$this->fields=array();
		$this->statut=$this->default_statut();
		$this->guide_infos=$this->default_guide_infos();
		
		reset($xml_table);
		
		$xml_table=$xml_table["NOTICE"][0];
		
		while (list($key,$val)=each($xml_table)) {
			if ($key!="F") {
				//Si c'est un tag connu pour le guide
				if (isset($this->guide_infos[strtolower($key)])) {
					if ($val[0]["value"]=="*") $val[0]["value"]=" ";
					if ($val[0]["value"])
						$this->guide_infos[strtolower($key)]=$val[0]["value"];
				} else if ($key=="RS") {
					if ($val[0]["value"]=="*") $val[0]["value"]=" ";
					$this->statut=$val[0]["value"];
				} //Sinon, on en tient pas compte
			} else {
				//C'est le début des champs f
				for ($i=0; $i<count($val); $i++) {
					$f_t=array();
					$f=$val[$i];
					//Si il y a une valeur, pas de sous champs
					if ($f["value"])
						$f_t["value"]=$f["value"];
					else {
						//Sinon on récupère l'indicateur
						if ($f["IND"]) $f_t["IND"]=$f["IND"]; else $f_t["IND"]="  ";
						reset($f);
						//Pour tous les sous tags
						while (list($key_s,$val_s)=each($f)) {
							//Si c'est un sous champs (s)
							if ((is_array($val_s))&&($key_s=="S")) {
								//Pour chaque sous champ (normalement, un seul !!)
								for ($j=0; $j<count($val_s); $j++) {
									//Si il y a une valeur, on l'affecte
									if ($val_s[$j]["C"])
										$f_t[$val_s[$j]["C"]][]=$val_s[$j]["value"];
								}
							}
						}
					}
					$this->fields[$f["C"]][]=$f_t;
				}
			}
		}
		return $this->gen_iso2709();
	}
	
	function create_from_translated_xml($xml) {
		$p=new private_parser($xml);
		if (!$p->error)
			return $this->create_from_translated_xml_table($p->table);
		else {
			$this->error=true;
			$this->error_message=$p->error_message;
			return false;
		}
	}
	
	function create_from_xml_table($xml_table) {
		//Réinitialisation des variables de la classe
		$this->fields=array();
		$this->statut=$this->default_statut();
		$this->guide_infos=$this->default_guide_infos();
		
		reset($xml_table);
		while (list($key,$val)=each($xml_table)) {
			//Si c'est un tag connu pour le guide
			if (isset($this->guide_infos[$key])) {
				if ($val[0]["value"]=="*") $val[0]["value"]=" ";
				if ($val[0]["value"])
					$this->guide_infos[$key]=$val[0]["value"];
			} else if ($key=="RS") {
				if ($val[0]["value"]=="*") $val[0]["value"]=" ";
				$this->statut=$val[0]["value"];
			} else {
				//Sinon normalement, c'est un champ
				if (substr($key,0,1)=="_") {
					for ($i=0; $i<count($val); $i++) {
						$f_t=array();
						$f=$val[$i];
						//Si il y a une valeur, pas de sous champs
						if ($f["value"])
							$f_t["value"]=$f["value"];
						else if ($f["ind"]) {
							//Sinon on récupère l'indicateur
							$f_t["IND"]=$f["ind"];
							reset($f);
							//Pour tous les sous tags
							while (list($key_s,$val_s)=each($f)) {
								//Si c'est un sous champ
								if (is_array($val_s)) {
									//Pour chaque sous champ 
									for ($j=0; $j<count($val_s); $j++) {
										//Si il y a une valeur, on l'affecte
										if (substr($key_s,0,1)=="_")
											$f_t[substr($key_s,1)][]=$val_s[$j]["value"];
									}
								}
							}
							$this->fields[substr($key,1)][]=$f_t;
						} else {
							$this->error=true;
							$this->error_message="Il manque un indicateur";
							return false;
						}
					}
				}
			}
		}
		return $this->gen_iso2709();
	}
	
	function create_from_xml($xml) {
		$p=new private_parser($xml, false);
		if (!$p->error)
			return $this->create_from_xml_table($p->table);
		else {
			$this->error=true;
			$this->error_message=$p->error_message;
			print $this->error_message;
			return false;
		}
	}
	
	/*
		Sortie au format XML de la notice avec les codes champs et sous champs en indicateurs
	*/
	function get_translated_xml($gen_xmlheader=1) {
		$xml="  <rs>".htmlspecialchars(trim($this->statut)?$this->statut:"*")."</rs>\n";
		reset($this->guide_infos);
		while (list($key,$val)=each($this->guide_infos)) {
			$xml.="  <".$key.">".htmlspecialchars(trim($val)?$val:"*")."</$key>\n";
		}
		reset($this->fields);
		while (list($key,$val)=each($this->fields)) {
			for ($i=0; $i<count($val); $i++) {
				reset($val[$i]);
				$xml.="  <f c='".$key."'";
				$att="";
				$value="";
				$cr="\n";
				while (list($key_s,$val_s)=each($val[$i])) {
					if (!is_array($val_s)) {
						if ($key_s!="value") 
							$att.=" ".$key_s."='".htmlspecialchars($val_s)."'";
						else {
							$value=htmlspecialchars($val_s);
							$cr="";
						}
					} else {
						for ($j=0; $j<count($val_s); $j++) {
							$value.="    <s c='".$key_s."'>".htmlspecialchars($val_s[$j])."</s>\n";
						}
					}
				}
				$xml.=$att.">".$cr.$value.($cr?"  ":"")."</f>\n";
			}
		}
		if($gen_xmlheader) return"<?xml version='1.0' encoding='iso-8859-15'?>\n<notice>\n".$xml."</notice>\n";
		return "<notice>\n".$xml."</notice>\n";
	}
	
	function field_exist($field) {
		if ($this->fields[$field]) return true; else return false;
	}
	
	function get_list_of_fields() {
		$dir_table=array();
		for ($i=0; $i<count($this->directory_table); $i++) {
			$dir_table[$i]=$this->directory_table[$i]["LABEL"];
		}
	}
	
	function get_list_of_subfields() {
	}
	
	function iso2709($notice="",$type="UNI") {
		if ($notice) {
			//Si il y a une notice, on l'analyse
			switch ($type) {
				case "UNI":
					$this->notice=$notice;
					if ($this->read_guide()) {
						$this->read_fields();
					}
					break;
				case "TXML":
					$this->create_from_translated_xml($notice);
					break;
				case "XML":
					$this->create_from_xml($notice);
					break;
				default:
					$this->error=true;
					$this->error_message="Le type de notice est inconnu";
					break;
			}
		} else {
			//Sinon, on rempli statut par défaut, guide_infos et champs = tableau vide
			$this->statut=$this->default_statut();
			$this->guide_infos=$this->default_guide_infos();
			$this->fields=array();
			return true;
		}
	}
}

/* Un parser XML simple */ 
class private_parser {
	var $table;
	var $xml;
	var $error;
	var $error_message;
	
	// Lecture récursive de la structure et stockage des paramètres
	function recursive($indice, $niveau, $param, $tag_count, $vals) {
		if ($indice > count($vals))
			exit;
		while ($indice < count($vals)) {
			list ($key, $val) = each($vals);
			$indice ++;
			if (!isset($tag_count[$val[tag]]))
				$tag_count[$val[tag]] = 0;
			else {
				$tag_count[$val[tag]]++;
			}
			if (isset($val[attributes])) {
				$attributs = $val[attributes];
				for ($k = 0; $k < count($attributs); $k ++) {
					list ($key_att, $val_att) = each($attributs);
					$param[$val[tag]][$tag_count[$val[tag]]][$key_att] = $val_att;
				}
			}
			if ($val[type] == "open") {
				$tag_count_next = array();
				$this->recursive(& $indice, $niveau +1, & $param[$val[tag]][$tag_count[$val[tag]]], & $tag_count_next, & $vals);
			}
			if ($val[type] == "close") {
				if ($niveau > 2)
					break;
			}
			if ($val[type] == "complete") {
				$param[$val[tag]][$tag_count[$val[tag]]][value] = $val[value];
			}
		}
	}
	
	
	function private_parser($xml,$ucase=true,$rootelement="") {
		$vals = array();
		$index = array();
		if ($xml) {
			$simple = $xml;
			$p = xml_parser_create();
			xml_parser_set_option($p, XML_OPTION_SKIP_WHITE, 1);
			if (!$ucase) xml_parser_set_option($p, XML_OPTION_CASE_FOLDING, 0);
			if (xml_parse_into_struct($p, $simple, & $vals, & $index) == 1) {
				xml_parser_free($p);
				$param = array();
				$tag_count = array();
				$this->recursive(0, 1, & $param, & $tag_count, & $vals);
			} else {
				$this->error=true;
				$this->error_message=xml_error_string(xml_get_error_code($p))." Ligne : ". xml_get_current_line_number($p)." - Colonne : ".xml_get_current_column_number($p);
				return false;
			}
			unset($vals, $index);
			if (is_array($param)) {
				if ($rootelement) {
					if (count($param[$rootelement]) != 1) {
						$this->error=true;
						$this->error_message="Erreur, n'est pas un xml $rootelement !";
						return false;
					}
				}
				list($rootelement,$this->table)=each($param);
				$this->table=$this->table[0];
				return true;
			}
		}
	}
}

define("AUT_TYPE_AUT",0x10);
define("AUT_TYPE_RENV",0x11);
define("AUT_TYPE_EXPL",0x12);

define("AUT_CONTENT_PERS",0x40);
define("AUT_CONTENT_COLL",0x41);
define("AUT_CONTENT_GEO",0x42);
define("AUT_CONTENT_MARQ",0x43);
define("AUT_CONTENT_FAM",0x44);
define("AUT_CONTENT_TIT",0x45);
define("AUT_CONTENT_CLASS",0x46);
define("AUT_CONTENT_AUTH_TIT",0x65);
define("AUT_CONTENT_AUTH_CLASS",0x66);
define("AUT_CONTENT_MAT",0x47);
define("AUT_CONTENT_LIEU_ED",0x48);
define("AUT_CONTENT_FORM",0x49);

class iso2709_authorities extends iso2709 {
	
	function default_guide_infos() {
		$guide_infos=array(
			"nt"=>"x",
			"et"=>"a",
			"el"=>" "
		);
		return $guide_infos;
	}
	
	function default_statut() {
		return "n";
	}
	
	function get_guide_infos() {
		$guide_infos=array();

		$this->guide_infos["nt"]=substr($this->application_codes,0,1);
		$this->guide_infos["et"]=substr($this->application_codes,3,1);
		$this->guide_infos["el"]=substr($this->supplementary,0,1);
	}
	
	function create_guide_infos() {
		if ($this->check_guide_infos()) {
			//Création de la zone application codes et supplementary
			$this->application_codes= $this->guide_infos["nt"]."  ".$this->guide_infos["et"];
			$this->supplementary= $this->guide_infos["el"]."  ";
			return true;
		} else return false;
	}
	
	function check_guide_infos() {
		$rs=array("c","d","n");
		$nt=array("x","y","z");
		$et=array("a","b","c","d","e","f","g","h","i","j","k","l");
		$el=array(" ","3");
		
		//Vérifications des codes autorisés spécifiques au type de notice
		$as=array_search($this->statut,$rs);

		if (($as===false)||($as===null)) {
			$this->error=true;
			$this->error_message="Le statut de la notice est inconnu";
			return false;
		}
		$as=array_search($this->guide_infos["nt"],$nt);
		if (($as===false)||($as===null)) {
			$this->error=true;
			$this->error_message="Le type de notice est inconnu";
			return false;
		}
		$as=array_search($this->guide_infos["et"],$et);
		if (($as===false)||($as===null)) {
			$this->error=true;
			$this->error_message="Le type de l'entité est inconnu";
			return false;
		}
		$as=array_search($this->guide_infos["el"],$el);
		if (($as===false)||($as===null)) {
			$this->error=true;
			$this->error_message="Le niveau d'encodage est inconnu";
			return false;
		}
		
		return true;
	}
}

class iso2709_notices extends iso2709 {
	
	function default_guide_infos() {
		$guide_infos=array(
			"dt"=>"a",
			"bl"=>"m",
			"hl"=>"0",
			"el"=>" ",
			"ru"=>" "
		);
		return $guide_infos;
	}
	
	function default_statut() {
		return "n";
	}
	
	function get_guide_infos() {
		$guide_infos=array();

		$this->guide_infos["dt"]=substr($this->application_codes,0,1);
		$this->guide_infos["bl"]=substr($this->application_codes,1,1);
		$this->guide_infos["hl"]=substr($this->application_codes,2,1);
		$this->guide_infos["el"]=substr($this->supplementary,0,1);
		$this->guide_infos["ru"]=substr($this->supplementary,1,1);
	}
	
	function create_guide_infos() {
		if ($this->check_guide_infos()) {
			//Création de la zone application codes et supplementary
			$this->application_codes= $this->guide_infos["dt"].$this->guide_infos["bl"].$this->guide_infos["hl"]." ";
			$this->supplementary= $this->guide_infos["el"].$this->guide_infos["ru"]." ";
			return true;
		} else return false;
	}
	
	function check_guide_infos() {
		$rs=array(" ","c","d","n","o","p");
		$dt=array("a","b","c","d","e","f","g","h","i","j","k","l","m","r");
		$bl=array("a","m","s","c");
		$hl=array(" ","0","1","2");
		$el=array(" ","1","2","3");
		$ru=array(" ","i","n");
		
		//Vérifications des codes autorisés spécifiques au type de notice
		$as=array_search($this->statut,$rs);

		if (($as===false)||($as===null)) {
			$this->error=true;
			$this->error_message="Le statut de la notice est inconnu";
			return false;
		}
		$as=array_search($this->guide_infos["dt"],$dt);
		if (($as===false)||($as===null)) {
			$this->error=true;
			$this->error_message="Le type de données est inconnu";
			return false;
		}
		$as=array_search($this->guide_infos["bl"],$bl);
		if (($as===false)||($as===null)) {
			$this->error=true;
			$this->error_message="Le type de la notice est inconnu";
			return false;
		}
		$as=array_search($this->guide_infos["hl"],$hl);
		if (($as===false)||($as===null)) {
			$this->error=true;
			$this->error_message="Le niveau hierarchique est inconnu";
			return false;
		}
		$as=array_search($this->guide_infos["el"],$el);
		if (($as===false)||($as===null)) {
			$this->error=true;
			$this->error_message="Le niveau d'encodage est inconnu";
			return false;
		}
		$as=array_search($this->guide_infos["ru"],$ru);
		if (($as===false)||($as===null)) {
			$this->error=true;
			$this->error_message="La forme descriptive du cataloguage est inconnue";
			return false;
		}
		return true;
	}
}
/*
$notice=file_get_contents("/home/ngantier/Documents/essai.uni");
$np=new iso2709_notices($notice);
print_r($np->guide_infos);
print_r($np->fields);
print $np->get_translated_xml();
*/
?>