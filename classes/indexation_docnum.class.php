<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: indexation_docnum.class.php,v 1.19 2010-12-01 16:04:30 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/XMLlist.class.php");
require_once("$base_path/catalog/explnum/index_docnum/index_pdf.class.php");
require_once("$base_path/catalog/explnum/index_docnum/index_html.class.php");
require_once("$base_path/catalog/explnum/index_docnum/index_txt.class.php");
require_once("$base_path/catalog/explnum/index_docnum/index_oo.class.php");
require_once("$base_path/catalog/explnum/index_docnum/index_bnf.class.php");
require_once("$class_path/curl.class.php");
require_once("$class_path/upload_folder.class.php");
require_once("$include_path/explnum.inc.php");

/**
 * Classe de gestion de l'indexation des documents numériques
 */
class indexation_docnum {
	
	var $id_explnum;
	var $fichier='';
	var $file_content='';
	var $file_url='';
	var $mimetype='';
	var $explnum_nomfichier='';
	var $os='';
	var $class_associee='';
	var $texte='';
	var $vignette='';
	
	/**
	 * Constructeur
	 */
	function indexation_docnum($id, $texte=''){
		
		$this->id_explnum = $id;
		if(!$texte){
			$this->fetch_data();
			$this->run_index();
		} else {
			$this->texte = $texte;
		}
		
	}
	
	/**
	 * Parcours des données de la table explnum
	 */
	function fetch_data(){
		global $dbh;
		
		$rqt_expl = "select explnum_mimetype, explnum_nomfichier, explnum_data, explnum_url, concat(repertoire_path,explnum_path) as path, explnum_repertoire from explnum left join upload_repertoire on repertoire_id=explnum_repertoire where explnum_id='".$this->id_explnum."'";
		$result_expl = mysql_query($rqt_expl,$dbh);
		while(($explnum = mysql_fetch_object($result_expl))){
			if($explnum->explnum_data)
				//le fichier est en base
				$this->file_content = $explnum->explnum_data;
			else {
				//le fichier est en upload		
				$up = new upload_folder($explnum->explnum_repertoire);
				$path = str_replace('//','/',$explnum->path.$explnum->explnum_nomfichier);
				if($path){
					$path = $up->encoder_chaine($path);
					if(file_exists($path)){
						$fp = fopen($path , "r" ) ;
						$this->file_content = fread ($fp, filesize($path));
						fclose ($fp) ;
					} else $this->file_content = "";
				}
			} 
			$this->file_url = $explnum->explnum_url;
			$this->mimetype = $explnum->explnum_mimetype;
			$this->explnum_nomfichier = $explnum->explnum_nomfichier;
		}	
	}
	
	/**
	 * Exécution du processus d'indexation
	 */
	function run_index(){
		if($this->mimetype == 'URL'){
			//récupération par cURL
			$this->get_file_from_curl($this->file_url);
			create_tableau_mimetype();
			$this->mimetype = trouve_mimetype($this->fichier);
			if(!$this->mimetype){
				//Test sur l'extension du fichier
				$ext = extension_fichier($this->file_url);
				$this->mimetype = trouve_mimetype($this->file_url,$ext);
			}
			if(!$this->mimetype && $this->explnum_nomfichier){
				//Test sur l'extension du fichier
				$ext = extension_fichier($this->explnum_nomfichier);
				$this->mimetype = trouve_mimetype($this->file_url,$ext);
			}
			global $prefix_url_image ;
			if ($prefix_url_image) $tmpprefix_url_image = $prefix_url_image; 
				else $tmpprefix_url_image = "./" ;
			$this->vignette = construire_vignette('',$tmpprefix_url_image."images/mimetype/".icone_mimetype($this->mimetype, $ext));
		} else {
			//récupération dans la base
			$this->get_file($this->file_content);
			create_tableau_mimetype();
			if(!$this->mimetype) $this->mimetype = trouve_mimetype($this->fichier);
			if(!$this->mimetype && $this->explnum_nomfichier){
				//Test sur l'extension du fichier
				$ext = extension_fichier($this->explnum_nomfichier);
				$this->mimetype = trouve_mimetype($this->fichier,$ext);
			}
		}
		//On parse le XML pour recupérer le nom de la classe
		$this->parse_xml();
		//On choisit la classe correspondant au traitement du type MIME
		$this->choose_class($this->class_associee);
	}
	
	/**
	 * On récupère le nom de la classe de traitement en fonction du  mimetype
	 */
	function parse_xml(){
		global $base_path;
		
		$parse = new XMLlist("$base_path/catalog/explnum/index_docnum/index_doc.xml");	
		$parse->analyser();
		if($this->mimetype)
			$class = $parse->table[$this->mimetype];
		if($class)
			$this->class_associee = $class;
		else 
			$this->class_associee = '';

	}
	
	/**
	 * On récupère le texte du document numérique grâce à la bonne classe
	 */
	function choose_class($class_name){
		if($class_name){
			$index_class = new $class_name($this->fichier);
			$this->texte = $index_class->get_text($this->fichier);
		}
	}
	
	/**
	 * On récupère le contenu du fichier qui est en base
	 */
	function get_file($filecontent){
		global $base_path;
		
		//On définit un nom unique dans le dossier temporaire
		$nom_temp = session_id().microtime();
		$nom_temp = str_replace(' ','_',$nom_temp);
		$nom_temp = str_replace('.','_',$nom_temp);
		
		//On écrit le contenu dans le fichier
		$fd = fopen("$base_path/temp/".$nom_temp,"w");
		fwrite($fd,$filecontent);
		fclose($fd);	
		$this->fichier = "$base_path/temp/".$nom_temp;	
	}
	
	/**
	 * On récupère le contenu du fichier à distance
	 */
	function get_file_from_curl($f_url){
		global $base_path;
		
		//On définit un nom unique dans le dossier temporaire
		$nom_temp = session_id().microtime();
		$nom_temp = str_replace(' ','_',$nom_temp);
		$nom_temp = str_replace('.','_',$nom_temp);
		$this->fichier = "$base_path/temp/".$nom_temp;
		$aCurl = new Curl();
		$aCurl->save_file_name=$this->fichier; 
		$aCurl->get($f_url);	
		
	}
	
	/**
	 * On indexe le document numérique
	 */
	function indexer(){
		global $dbh;
		
		$rqt = " update explnum set explnum_index_sew=' ".addslashes(strip_empty_words($this->texte))." ', explnum_index_wew='".addslashes($this->texte)."' where explnum_id='".$this->id_explnum."'";
		mysql_query($rqt,$dbh);	
		if ($this->fichier) unlink($this->fichier);	
	}
	
	/**
	 * On supprime l'index du document numérique
	 */
	function desindexer(){
		global $dbh;
		
		$rqt = " update explnum set explnum_index_sew='', explnum_index_wew='' where explnum_id='".$this->id_explnum."'";
		mysql_query($rqt,$dbh);	

	}
}
?>