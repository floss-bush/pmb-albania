<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: lehmanns.class.php,v 1.2 2009-11-17 16:10:05 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

global $class_path,$base_path, $include_path;
require_once($class_path."/connecteurs.class.php");

if (version_compare(PHP_VERSION,'5','>=') && extension_loaded('xsl')) {
	if (substr(phpversion(), 0, 1) == "5") @ini_set("zend.ze1_compatibility_mode", "0");
	require_once($include_path.'/xslt-php4-to-php5.inc.php');
}

class xml_dom_lehmanns {
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
	function xml_dom_lehmanns($xml,$charset="iso-8859-1") {
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

class lehmanns extends connector {
	//Variables internes pour la progression de la récupération des notices
	var $themes=array(
		"Humanmedizin",
		"Psychologie",
		"Informatik",
		"Veterinärmedizin",
		"Naturwissenschaft",
		"Technik",
		"Recht",
		"Wirtschaft"
	);
	
	//Résultat de la synchro
	var $error;					//Y-a-t-il eu une erreur	
	var $error_message;			//Si oui, message correspondant
	
    function lehmanns($connector_path="") {
    	parent::connector($connector_path);
    }
    
    function get_id() {
    	return "lehmanns";
    }
    
    //Est-ce un entrepot ?
	function is_repository() {
		return 2;
	}
    
    function unserialize_source_params($source_id) {
    	$params=$this->get_source_params($source_id);
		if ($params["PARAMETERS"]) {
			$vars=unserialize($params["PARAMETERS"]);
			$params["PARAMETERS"]=$vars;
		}
		return $params;
    }
    
    function source_get_property_form($source_id) {
    	global $charset;
    	
    	$params=$this->get_source_params($source_id);
		if ($params["PARAMETERS"]) {
			//Affichage du formulaire avec $params["PARAMETERS"]
			$vars=unserialize($params["PARAMETERS"]);
			foreach ($vars as $key=>$val) {
				global $$key;
				$$key=$val;
			}	
		}
		if ($themes=="") $themes=array();
		$form="<br />
		<div class='row'>
			<div class='colonne3'>
				<label for='max_return'>".$this->msg["lehmanns_max_return"]."</label>
			</div>
			<div class='colonne_suite'>
				<input type='text' name='max_return' id='max_return' value='".htmlentities($max_return,ENT_QUOTES,$charset)."' size='10'/>
			</div>
		</div>
		<div class='row'>
			<div class='colonne3'>
				<label for='theme'>".$this->msg["lehmanns_theme"]."</label>
			</div>
			<div class='colonne_suite'>
				<select name='themes[]' id='theme' multiple='yes'>
					<option value='' ".(((count($themes)==0)||($themes[0]==""))?"selected='selected'":"").">Alle</option>\n";
		for ($i=0; $i<count($this->themes); $i++) {
			$form.= "				<option value='".htmlentities($this->themes[$i],ENT_QUOTES,$charset)."' ".(array_search($this->themes[$i],$themes)!==false?"selected='selected' ":"").">".htmlentities($this->themes[$i],ENT_QUOTES,$charset)."</option>\n";
		}
		$form.="
				</select>
			</div>
		</div>
	<div class='row'></div>
";
		return $form;
    }
    
    function make_serialized_source_properties($source_id) {
    	global $themes,$max_return;
    	if ($themes[0]=="") $themes=array();
    	$t["themes"]=$themes;
    	$t["max_return"]=$max_return;
		$this->sources[$source_id]["PARAMETERS"]=serialize($t);
	}
	
	//Récupération  des proriétés globales par défaut du connecteur (timeout, retry, repository, parameters)
	function fetch_default_global_values() {
		$this->timeout=5;
		$this->repository=1;
		$this->retry=3;
		$this->ttl=1800;
		$this->parameters=array();
	}
	
	//Formulaire des propriétés générales
	function get_property_form() {
		$this->fetch_global_properties();
		return "";
	}
	
	function make_serialized_properties() {
		$this->parameters="";
	}
	
	function apply_xsl_to_xml($xml, $xsl) {
		global $charset;
		$xh = xslt_create();
		xslt_set_encoding($xh, $charset);
		$arguments = array(
	   	  '/_xml' => $xml,
	   	  '/_xsl' => $xsl
		);
		$result = xslt_process($xh, 'arg:/_xml', 'arg:/_xsl', NULL, $arguments);
		xslt_free($xh);
		return $result;		
	}
	
	//Fonction de recherche
	function search($source_id,$query,$search_id) {
		global $charset;
		global $pmb_curl_proxy;	
		global $base_path;
			
		$this->error=false;
		$this->error_message="";
		
		$params=$this->get_source_params($source_id);
		$this->fetch_global_properties();
		if ($params["PARAMETERS"]) {
			//Affichage du formulaire avec $params["PARAMETERS"]
			$vars=unserialize($params["PARAMETERS"]);
			foreach ($vars as $key=>$val) {
				global $$key;
				$$key=$val;
			}	
		}
		
		if (!$max_return) $max_return=100;
		
		//Construction de la requête
		$boolsearch="";
		for ($i=0; $i<count($query); $i++) {
			$term=$query[$i];
			$chaine="";
			switch ($term->ufield) {
				case "200\$a":
					$chaine="(TI=".str_replace("*","%",$term->values[0])." or ST=".str_replace("*","%",$term->values[0]).")";
					break;
				case "010\$a":
					$chaine="IS=".str_replace("*","%",$term->values[0]);
					break;
				case "7XX":
					$chaine="AU=".str_replace("*","%",$term->values[0]);
					break;
				case "210\$c":
					$chaine="PU=".str_replace("*","%",$term->values[0]);
					break;
				case "210\$d":
					$chaine="PY=".str_replace("*","%",$term->values[0]);
					break;
				case "300":
				case "327":
				case "330":
				case "3XX":
					$chaine="ME=".str_replace("*","%",$term->values[0]);
					break;
				case "60X":
					$chaine="FT=".str_replace("*","%",$term->values[0]);
					break;
				case "XXX":
					$chaine="(TI=".str_replace("*","%",$term->values[0])." or ST=".str_replace("*","%",$term->values[0])." or AU=".str_replace("*","%",$term->values[0])." or FT=".str_replace("*","%",$term->values[0])." or ME=".str_replace("*","%",$term->values[0])." or IS=".str_replace("*","%",$term->values[0])." or PU=".str_replace("*","%",$term->values[0]).")";
					break;
			}
			if (($chaine!="")&&($i!=0)) {
				switch ($term->inter) {
					case "and":
						$boolsearch.=" and ";
						break;
					case "or":
						$boolsearch.=" or ";
						break;
					case "ex":
						$boolsearch.=" and not ";
						break;
				}
			}
			$boolsearch.=$chaine;
		}
		$boolthemes="";
		if (count($themes)) {
			for ($i=0; $i<count($themes); $i++) {
				if ($i!=0) $boolthemes.=" or ";
				$boolthemes.="FT=".$themes[$i];
			}
			$boolthemes="(".$boolthemes.")";
		}
		if ($boolthemes) $boolsearch="(".$boolsearch.") and ".$boolthemes;
		$nb_pages=floor($max_return/40);
		$stop=false;
		for ($i=0; $i<$nb_pages; $i++) {
			$addr="http://sun2.lehmanns.de/cgi-bin/work/xmlboolsearch?mode=xmlboolsearch&titcount=$max_return&titnext=".($i*40)."&boolstring=".rawurlencode($boolsearch);
			//Initialisation de la ressource
			$ch = curl_init();
			// configuration des options CURL
			curl_setopt($ch, CURLOPT_URL, $addr);
			if ($params["TIMEOUT"]) curl_setopt($ch, CURLOPT_TIMEOUT,(integer)$params["TIMEOUT"]);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	 		configurer_proxy_curl($ch);
	 		$cexec=curl_exec($ch);
	 		if (!$cexec) {
	 			$this->error=true;
	 			$this->error_message="Can't get Lob answer : ".curl_error($ch);
	 			break;
	 		} else {
	 			if (strpos($cexec,"<LBook>")!==false) {
	 				$unixml=$this->apply_xsl_to_xml($cexec,file_get_contents($base_path."/admin/connecteurs/in/lehmanns/xslt/lehmanns2uni.xsl"));
	 				$this->rec_records($unixml, $source_id, $search_id);
	 			} else $stop=true;
	 		}
	 		curl_close($ch);
	 		if ($stop) break;
		}
	}
	
	function rec_records($noticesxml, $source_id, $search_id) {
		global $charset,$base_path;
		if (!trim($noticesxml))
			return;
			
		$rec_uni_dom=new xml_dom_lehmanns($noticesxml,"iso-8859-1");
		$notices=$rec_uni_dom->get_nodes("unimarc/notice");
		if ($notices) {
			foreach ($notices as $anotice) {
				$this->rec_record($rec_uni_dom, $anotice, $source_id, $search_id);
			}
		}
	}
	
	function rec_record($rec_uni_dom,$noticenode, $source_id, $search_id) {
		global $charset,$base_path;
		
		$date_import=date("Y-m-d H:i:s",time());
		
		if (!$rec_uni_dom->error) {
			//Initialisation
			$ref="";
			$ufield="";
			$usubfield="";
			$field_order=0;
			$subfield_order=0;
			$value="";
			
			$fs=$rec_uni_dom->get_nodes("f", $noticenode);
			//Recherche du 001
			for ($i=0; $i<count($fs); $i++) {
				if ($fs[$i]["ATTRIBS"]["c"]=="001") {
					$ref=$rec_uni_dom->get_datas($fs[$i]);
					break;
				}
			}
			//Mise à jour 
			if ($ref) {
				//Si conservation des anciennes notices, on regarde si elle existe
				if (!$this->del_old) {
					$requete="select count(*) from entrepot_source_".$source_id." where ref='".addslashes($ref)."' and search_id='".addslashes($search_id)."'";
					$rref=mysql_query($requete);
					if ($rref) $ref_exists=mysql_result($rref,0,0);
				}
				//Si pas de conservation des anciennes notices, on supprime
				if ($this->del_old) {
					$requete="delete from entrepot_source_".$source_id." where ref='".addslashes($ref)."' and search_id='".addslashes($search_id)."'";
					mysql_query($requete);
				}
				//Si pas de conservation ou reférence inexistante
				if (($this->del_old)||((!$this->del_old)&&(!$ref_exists))) {
					//Insertion de l'entête
					$n_header["rs"]=$rec_uni_dom->get_value("unimarc/notice/rs");
					$n_header["ru"]=$rec_uni_dom->get_value("unimarc/notice/ru");
					$n_header["el"]=$rec_uni_dom->get_value("unimarc/notice/el");
					$n_header["bl"]=$rec_uni_dom->get_value("unimarc/notice/bl");
					$n_header["hl"]=$rec_uni_dom->get_value("unimarc/notice/hl");
					$n_header["dt"]=$rec_uni_dom->get_value("unimarc/notice/dt");
					
					//Récupération d'un ID
					$requete="insert into external_count (recid,source_id) values('".addslashes($this->get_id()." ".$source_id." ".$ref)."',".$source_id.")";
					$rid=mysql_query($requete);
					if ($rid) $recid=mysql_insert_id();
					
					$rqt_parallel="insert into entrepot_source_".$source_id." (connector_id,source_id,ref,date_import,ufield,usubfield,field_order,subfield_order,value,i_value,recid,search_id) values";
					$first=false;
					foreach($n_header as $hc=>$code) {
						/*$requete="insert into entrepot_source_".$source_id." (connector_id,source_id,ref,date_import,ufield,usubfield,field_order,subfield_order,value,i_value,recid,search_id) values(
						'".addslashes($this->get_id())."',".$source_id.",'".addslashes($ref)."','".addslashes($date_import)."',
						'".$hc."','',-1,0,'".addslashes($code)."','',$recid,'".addslashes($search_id)."')";
						mysql_query($requete);*/
						$rqt_parallel.=($first?",":"")."(
						'".addslashes($this->get_id())."',".$source_id.",'".addslashes($ref)."','".addslashes($date_import)."',
						'".$hc."','',-1,0,'".addslashes($code)."','',$recid,'".addslashes($search_id)."')";
						$first=true;
					}
					
					for ($i=0; $i<count($fs); $i++) {
						$ufield=$fs[$i]["ATTRIBS"]["c"];
						$field_order=$i;
						$ss=$rec_uni_dom->get_nodes("s",$fs[$i]);
						if (is_array($ss)) {
							for ($j=0; $j<count($ss); $j++) {
								$usubfield=$ss[$j]["ATTRIBS"]["c"];
								$value=$rec_uni_dom->get_datas($ss[$j]);
								$subfield_order=$j;
								/*$requete="insert into entrepot_source_".$source_id." (connector_id,source_id,ref,date_import,ufield,usubfield,field_order,subfield_order,value,i_value,recid,search_id) values(
								'".addslashes($this->get_id())."',".$source_id.",'".addslashes($ref)."','".addslashes($date_import)."',
								'".addslashes($ufield)."','".addslashes($usubfield)."',".$field_order.",".$subfield_order.",'".addslashes($value)."',
								' ".addslashes(strip_empty_words($value))." ',$recid,'".addslashes($search_id)."')";
								mysql_query($requete);*/
								$rqt_parallel.=",(
								'".addslashes($this->get_id())."',".$source_id.",'".addslashes($ref)."','".addslashes($date_import)."',
								'".addslashes($ufield)."','".addslashes($usubfield)."',".$field_order.",".$subfield_order.",'".addslashes($value)."',
								' ".addslashes(strip_empty_words($value))." ',$recid,'".addslashes($search_id)."')";
							}
						} else {
							$value=$rec_uni_dom->get_datas($fs[$i]);
							/*$requete="insert into entrepot_source_".$source_id." (connector_id,source_id,ref,date_import,ufield,usubfield,field_order,subfield_order,value,i_value,recid,search_id) values(
							'".addslashes($this->get_id())."',".$source_id.",'".addslashes($ref)."','".addslashes($date_import)."',
							'".addslashes($ufield)."','".addslashes($usubfield)."',".$field_order.",".$subfield_order.",'".addslashes($value)."',
							' ".addslashes(strip_empty_words($value))." ',$recid,'".addslashes($search_id)."')";
							mysql_query($requete);*/
							$rqt_parallel.=",(
							'".addslashes($this->get_id())."',".$source_id.",'".addslashes($ref)."','".addslashes($date_import)."',
							'".addslashes($ufield)."','".addslashes($usubfield)."',".$field_order.",".$subfield_order.",'".addslashes($value)."',
							' ".addslashes(strip_empty_words($value))." ',$recid,'".addslashes($search_id)."')";
						}
					}
					mysql_query($rqt_parallel);
					//print $rqt_parallel."\n";
				}
			}
		}
	}
		
	function cancel_maj($source_id) {
		return false;
	}
	
	function break_maj($source_id) {
		return false;
	}
	
	function maj_entrepot($source_id,$callback_progress="",$recover=false,$recover_env="") {
		return 0;
	}
}
?>