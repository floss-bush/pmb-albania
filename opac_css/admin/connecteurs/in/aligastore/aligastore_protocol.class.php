<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: aligastore_protocol.class.php,v 1.3 2009-02-05 10:06:39 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

global $class_path,$base_path, $include_path;

/**
 * \brief Petit parser dom autonome et élégant
 * 
 * Parse une chaine XML et permet un accès rapide par une interface simplifiée DOM. 
 * Cette classe fonctionne uassi bien en PHP4 que 5.
 * \note Cette classe manipule des noeuds de type noeud (\ref noeud "voir l'attribut $tree").\n
 * \note Des chemins sont utilisés pour accéder aux noeuds, les syntaxes sont détaillées dans les méthodes qui les utilisent :\n
 * \note -\ref path_node "syntaxe des chemins pour la méthode get_node"\n
 * \note -\ref path_nodes "syntaxe des chemins pour la méthode get_nodes"\n
 *   
 * @author Florent TETART
 */
class xml_dom_aligastore {
	var $xml;				/*!< XML d'origine */
	var $charset;			/*!< Charset courant (iso-8859-1 ou utf-8) */
	/**
	 * \brief Arbre des noeuds du document
	 * 
	 * L'arbre est composé de noeuds qui ont la structure suivante :
	 * \anchor noeud
	 * \verbatim
	 $noeud = array(
	 	NAME	=> Nom de l'élément pour un noeud de type élément (TYPE = 1)
	 	ATTRIBS	=> Tableau des attributs (nom => valeur)
	 	TYPE	=> 1 = Noeud élément, 2 = Noeud texte
	 	CHILDS	=> Tableau des noeuds enfants
	 )
	 \endverbatim
	 */
	var $tree; 
	var $error=false; 		/*!< Signalement d'erreur : true : erreur lors du parse, false : pas d'erreur */
	var $error_message=""; 	/*!< Message d'erreur correspondant à l'erreur de parse */
	var $depth=0;			/*!< \protected */
	var $last_elt=array();	/*!< \protected */
	var $n_elt=array();		/*!< \protected */
	var $cur_elt=array();	/*!< \protected */
	var $last_char=false;	/*!< \protected */
	
	/**
	 * \protected
	 */
	function close_node() {
		$this->last_elt[$this->depth-1]["CHILDS"][]=$this->cur_elt;
		$this->last_char=false;
		$this->cur_elt=$this->last_elt[$this->depth-1];
		$this->depth--;
	}
	
	/**
	 * \protected
	 */
	function startElement($parser,$name,$attribs) {
		if ($this->last_char) $this->close_node();
		$this->last_elt[$this->depth]=$this->cur_elt;
		$this->cur_elt=array();
		$this->cur_elt["NAME"]=$name;
		$this->cur_elt["ATTRIBS"]=$attribs;
		$this->cur_elt["TYPE"]=1;
		$this->last_char=false;
		$this->depth++;
	}
	
	/**
	 * \protected
	 */
	function endElement($parser,$name) {
		if ($this->last_char) $this->close_node();
		$this->close_node();
	}
	
	/**
	 * \protected
	 */
	function charElement($parser,$char) {
		if ($this->last_char) $this->close_node();
		$this->last_char=true;
		$this->last_elt[$this->depth]=$this->cur_elt;
		$this->cur_elt=array();
		$this->cur_elt["DATA"].=$char;
		$this->cur_elt["TYPE"]=2;
		$this->depth++;
	}
	
	/**
	 * \brief Instanciation du parser
	 * 
	 * Le document xml est parsé selon le charset donné et une représentation sous forme d'arbre est générée
	 * @param string $xml XML a manipuler
	 * @param string $charset Charset du document XML
	 */
	function xml_dom_aligastore($xml,$charset="iso-8859-1") {
		$this->charset=$charset;
		$this->cur_elt=array("NAME"=>"document","TYPE"=>"0");
		
		//Initialisation du parser
		$xml_parser=xml_parser_create($this->charset);
		xml_set_object($xml_parser,$this);
		xml_parser_set_option( $xml_parser, XML_OPTION_CASE_FOLDING, 0 );
		xml_parser_set_option( $xml_parser, XML_OPTION_SKIP_WHITE, 1 );
		xml_set_element_handler($xml_parser, "startElement", "endElement");
		xml_set_character_data_handler($xml_parser,"charElement");
		
		if (!xml_parse($xml_parser, $xml)) {
       		$this->error_message=sprintf("XML error: %s at line %d",xml_error_string(xml_get_error_code($xml_parser)),xml_get_current_line_number($xml_parser));
       		$this->error=true;
		}
		$this->tree=$this->last_elt[0];
	}
	
	/**
	 * \anchor path_node
	 * \brief Récupération d'un noeud par son chemin
	 * 
	 * Recherche un noeud selon le chemin donné en paramètre. Un noeud de départ peut être précisé
	 * @param string $path Chemin du noeud recherché
	 * @param noeud [$node] Noeud de départ de la recherche (le noeud doit être de type 1)
	 * @return noeud Noeud correspondant au chemin ou \b false si non trouvé
	 * \note Les chemins ont la syntaxe suivante :
	 * \verbatim
	 <a>
	 	<b>
	 		<c id="0">Texte</c>
	 		<c id="1">
	 			<d>Sous texte</d>
	 		</c>
	 		<c id="2">Texte 2</c>
	 	</b>
	 </a>
	 
	 a/b/c		Le premier noeud élément c (<c id="0">Texte</c>)
	 a/b/c[2]/d	Le premier noeud élément d du deuxième noeud c (<d>Sous texte</d>)
	 a/b/c[3]	Le troisième noeud élément c (<c id="2">Texte 2</c>) 
	 a/b/id@c	Le premier noeud élément c (<c id="0">Texte</c>). L'attribut est ignoré
	 a/b/id@c[3]	Le troisème noeud élément c (<c id="2">Texte 2</c>). L'attribut est ignoré
	 
	 Les attributs ne peuvent être cités que sur le noeud final.
	 \endverbatim
	 */
	function get_node($path,$node="") {
		if ($node=="") $node=&$this->tree;
		$paths=explode("/",$path);
		for ($i=0; $i<count($paths); $i++) {
			if ($i==count($paths)-1) {
				$pelt=explode("@",$paths[$i]);
				if (count($pelt)==1) { 
					$p=$pelt[0]; 
				} else {
					$p=$pelt[1];
					$attr=$pelt[0];
				}
			} else $p=$paths[$i];
			if (preg_match("/\[([0-9]*)\]$/",$p,$matches)) {
				$name=substr($p,0,strlen($p)-strlen($matches[0]));
				$n=$matches[1];
			} else {
				$name=$p;
				$n=0;
			}
			$nc=0;
			$found=false;
			for ($j=0; $j<count($node["CHILDS"]); $j++) {
				if (($node["CHILDS"][$j]["TYPE"]==1)&&($node["CHILDS"][$j]["NAME"]==$name)) {
					//C'est celui là !!
					if ($nc==$n) {
						$node=&$node["CHILDS"][$j];
						$found=true;
						break;
					} else $nc++;
				}
			}
			if (!$found) return false;
		}
		return $node;
	}
	
	/**
	 * \anchor path_nodes
	 * \brief Récupération d'un ensemble de noeuds par leur chemin
	 * 
	 * Recherche d'un ensemble de noeuds selon le chemin donné en paramètre. Un noeud de départ peut être précisé
	 * @param string $path Chemin des noeuds recherchés
	 * @param noeud [$node] Noeud de départ de la recherche (le noeud doit être de type 1)
	 * @return array noeud Tableau des noeuds correspondants au chemin ou \b false si non trouvé
	 * \note Les chemins ont la syntaxe suivante :
	 * \verbatim
	 <a>
	 	<b>
	 		<c id="0">Texte</c>
	 		<c id="1">
	 			<d>Sous texte</d>
	 		</c>
	 		<c id="2">Texte 2</c>
	 	</b>
	 </a>
	 
	 a/b/c		Tous les éléments c fils de a/b 
	 a/b/c[2]/d	Tous les éléments d fils de a/b et du deuxième élément c
	 a/b/id@c	Tous les noeuds éléments c fils de a/b. L'attribut est ignoré
	 \endverbatim
	 */
	function get_nodes($path,$node="") {
		$n=0;
		$nodes="";
		while ($nod=$this->get_node($path."[$n]",$node)) {
			$nodes[]=$nod;
			$n++;
		}
		return $nodes;
	}
	
	/**
	 * \brief Récupération des données sérialisées d'un noeud élément
	 * 
	 * Récupère sous forme texte les données d'un noeud élément :\n
	 * -Si c'est un élément qui n'a qu'un noeud texte comme fils, renvoie le texte\n
	 * -Si c'est un élément qui a d'autres éléments comme fils, la version sérialisée des enfants est renvoyée
	 * @param noeud $node Noeud duquel récupérer les données
	 * @param bool $force_entities true : les données sont renvoyées avec les entités xml, false : les données sont renvoyées sans entités
	 * @return string données sérialisées du noeud élément
	 */
	function get_datas($node,$force_entities=false) {
		$char="";
		if ($node["TYPE"]!=1) return false;
		//Recherche des fils et vérification qu'il n'y a que du texte !
		$flag_text=true;
		for ($i=0; $i<count($node["CHILDS"]); $i++) {
			if ($node["CHILDS"][$i]["TYPE"]!=2) $flag_text=false;
		}
		if ((!$flag_text)&&(!$force_entities)) {
			$force_entities=true;
		}
		for ($i=0; $i<count($node["CHILDS"]); $i++) {
			if ($node["CHILDS"][$i]["TYPE"]==2)
				if ($force_entities) 
					$char.=htmlspecialchars($node["CHILDS"][$i]["DATA"],ENT_NOQUOTES,$this->charset);
				else $char.=$node["CHILDS"][$i]["DATA"];
			else {
				$char.="<".$node["CHILDS"][$i]["NAME"];
				if (count($node["CHILDS"][$i]["ATTRIBS"])) {
					foreach ($node["CHILDS"][$i]["ATTRIBS"] as $key=>$val) {
						$char.=" ".$key."=\"".htmlspecialchars($val,ENT_NOQUOTES,$this->charset)."\"";
					}
				}
				$char.=">";
				$char.=$this->get_datas($node["CHILDS"][$i],$force_entities);
				$char.="</".$node["CHILDS"][$i]["NAME"].">";
			}
		}
		return $char;
	}
	
	/**
	 * \brief Récupération des attributs d'un noeud
	 * 
	 * Renvoie le tableau des attributs d'un noeud élément (Type 1)
	 * @param noeud $node Noeud élément duquel on veut les attributs
	 * @return mixed Tableau des attributs Nom => Valeur ou false si ce n'est pas un noeud de type 1
	 */
	function get_attributes($node) {
		if ($node["TYPE"]!=1) return false;
		return $node["ATTRIBUTES"];
	}
	
	/**
	 * \brief Récupère les données ou l'attribut d'un noeud par son chemin
	 * 
	 * Récupère les données sérialisées d'un noeud ou la valeur d'un attribut selon le chemin
	 * @param string $path chemin du noeud recherché
	 * @param noeud $node Noeud de départ de la recherche
	 * @return string Donnée sérialsiée ou valeur de l'attribut, \b false si le chemin n'existe pas
	 * \note Exemples de valeurs renvoyées selon le chemin :
	 * \verbatim
	 <a>
	 	<b>
	 		<c id="0">Texte</c>
	 		<c id="1">
	 			<d>Sous texte</d>
	 		</c>
	 		<c id="2">Texte 2</c>
	 	</b>
	 </a>
	 
	 a/b/c		Renvoie : "Texte"
	 a/b/c[2]/d	Renvoie : "Sous texte"
	 a/b/c[2]	Renvoie : "<d>Sous texte</d>"
	 a/b/c[3]	Renvoie : "Texte 2" 
	 a/b/id@c	Renvoie : "0"
	 a/b/id@c[3]	Renvoie : "2"
	 \endverbatim
	 */
	function get_value($path,$node="") {
		$elt=$this->get_node($path,$node);
		if ($elt) {
			$paths=explode("/",$path);
			$pelt=explode("@",$paths[count($paths)-1]);
			if (count($pelt)>1) {
				$a=$pelt[0];
				//Recherche de l'attribut
				if (preg_match("/\[([0-9]*)\]$/",$a,$matches)) {
					$attr=substr($a,0,strlen($a)-strlen($matches[0]));
					$n=$matches[1];
				} else {
					$attr=$a;
					$n=0;
				}
				$nc=0;
				$found=false;
				foreach($elt["ATTRIBS"] as $key=>$val) {
					if ($key==$attr) {
						//C'est celui là !!
						if ($nc==$n) {
							$value=$val;
							$found=true;
							break;
						} else $nc++;
					}
				}
				if (!$found) $value="";
			} else {
				$value=$this->get_datas($elt);
			}
		}
		return $value;
	}
	
	/**
	 * \brief Récupère les données ou l'attribut d'un ensemble de noeuds par leur chemin
	 * 
	 * Récupère les données sérialisées ou la valeur d'un attribut d'un ensemble de noeuds selon le chemin
	 * @param string $path chemin des noeuds recherchés
	 * @param noeud $node Noeud de départ de la recherche
	 * @return array Tableau des données sérialisées ou des valeur de l'attribut, \b false si le chemin n'existe pas
	 * \note Exemples de valeurs renvoyées selon le chemin :
	 * \verbatim
	 <a>
	 	<b>
	 		<c id="0">Texte</c>
	 		<c id="1">
	 			<d>Sous texte</d>
	 		</c>
	 		<c id="2">Texte 2</c>
	 	</b>
	 </a>
	 
	 a/b/c		Renvoie : [0]=>"Texte",[1]=>"<d>Sous texte</d>",[2]=>"Texte 2"
	 a/b/c[2]/d	Renvoie : [0]=>"Sous texte"
	 a/b/id@c	Renvoie : [0]=>"0",[1]=>"1",[2]=>"2"
	 \endverbatim
	 */
	function get_values($path,$node="") {
		$n=0;
		while ($elt=$this->get_node($path."[$n]",$node)) {
			$elts[$n]=$elt;
			$n++;
		}
		if (count($elts)) {
			for ($i=0; $i<count($elts); $i++) {
				$elt=$elts[$i];
				$paths=explode("/",$path);
				$pelt=explode("@",$paths[count($paths)-1]);
				if (count($pelt)>1) {
					$a=$pelt[0];
					//Recherche de l'attribut
					if (preg_match("/\[([0-9]*)\]$/",$a,$matches)) {
						$attr=substr($a,0,strlen($a)-strlen($matches[0]));
						$n=$matches[1];
					} else {
						$attr=$a;
						$n=0;
					}
					$nc=0;
					$found=false;
					foreach($elt["ATTRIBS"] as $key=>$val) {
						if ($key==$attr) {
							//C'est celui là !!
							if ($nc==$n) {
								$values[]=$val;
								$found=true;
								break;
							} else $nc++;
						}
					}
					if (!$found) $values[]="";
				} else {
					$values[]=$this->get_datas($elt);
				}
			}
		}
		return $values;
	}
}


class aligastore_get_data {
    var $error=false;
    var $error_message="";
    var $response_date;			
    var $charset="iso-8859-1";
    var $time_out;				
    var $xml_parser;			
    var $retry_after;			
    var $data = '';
    					
    function aligastore_get_data($url="", $charset="iso-8859-1", $time_out="") {
    	$this->charset=$charset;
    	$this->time_out=$time_out;
    	if ($url) $this->get_data($url);
    }
    
    function parse_xml($ch,$data) {
    	if (!$this->retry_after) {
	    	//Parse de la ressource
	    	if (!xml_parse($this->xml_parser, $data)) {
	       		$this->error_message=sprintf("XML error: %s at line %d",xml_error_string(xml_get_error_code($this->xml_parser)),xml_get_current_line_number($this->xml_parser));
	       		$this->error=true;
	       		return strlen($data);
	    	} 
    	}
    	return strlen($data);
	}
    
    function verif_header($ch,$headers) {
    	$h=explode("\n",$headers);
    	for ($i=0; $i<count($h); $i++) {
    		$v=explode(":",$h[$i]);
    		if ($v[0]=="Retry-After") { $this->retry_after=$v[1]*1; }
    	}
    	return strlen($headers);
    }
    
    function get_data($url) {
    	//Remise à zéro des erreurs
    	$this->error=false;
    	$this->error_message="";
    	
    	//Initialisation de la ressource
    	$ch = curl_init();
		// configuration des options CURL
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_WRITEFUNCTION,array(&$this,"parse_xml"));
		curl_setopt($ch, CURLOPT_HEADERFUNCTION,array(&$this,"verif_header"));	
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		if ($this->time_out) curl_setopt($ch, CURLOPT_TIMEOUT,$this->time_out);
    	//Réinitialisation du "retry_after"
		$this->retry_after="";    	
    	
    	//Explosion des arguments de la requète pour ceux qui ne respectent pas la norme !!
    	$query=substr($url,strpos($url,"?")+1);
    	$query=explode("&",$query);
    	for ($i=0; $i<count($query); $i++) {
    		if (strpos($query[$i],"operation")!==false) {
    			$operation=substr($query[$i],9);
    			break;
    		}
    	}    	
    	
    	//Initialisation du parser
		$this->xml_parser=xml_parser_create("utf-8");
		xml_parser_set_option( $this->xml_parser, XML_OPTION_CASE_FOLDING, 0 );
		xml_parser_set_option( $this->xml_parser, XML_OPTION_SKIP_WHITE, 1 );
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		configurer_proxy_curl($ch);
		$n_try=0;
		$data =  $cexec=curl_exec($ch);
		while (($cexec)&&($this->retry_after)&&($n_try<3)) {
			$n_try++; 
			sleep((int)$this->retry_after*1);
			$this->retry_after="";
			$data = $cexec=curl_exec($ch);
		}
		if (!$cexec) {
			$this->error=true;
			$this->error_message=curl_error($ch);
		}

		xml_parser_free($this->xml_parser);
		$this->xml_parser="";
		curl_close($ch);
		
		if ($this->error) { $this->error_message.=" - ".$url; unset($s); return; }
		
		$this->data = $data;
    }
}

class aligastore_request {
	var $base_url;
	var $operation;
	var $parameters;
	var $error = false;
	var $error_message = '';
	var $data = '';
	
	function aligastore_request($base_url, $parameters=array()) {
		
		$this->base_url = $base_url;		
		$this->parameters = $parameters;
	}
	
	function aligastore_response($charset='ISO-8859-1') {
		$parameters = '';
		foreach ($this->parameters as $name => $value)
			$parameters[] .= $name.'='.urlencode($value);
		$url = $this->base_url . '?'.implode('&', $parameters);

		$get_data = new aligastore_get_data($url, $charset);

		$this->data = $get_data->data; 
		return $get_data->data;
	}
	
}

?>