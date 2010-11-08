<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: explnum_doc.class.php,v 1.2 2009-09-24 13:18:58 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($include_path.'/explnum.inc.php');

class explnum_doc{
	
	var $explnum_doc_id = 0;
	var $explnum_doc_nomfichier = '';
	var $explnum_doc_contenu = '';
	var $explnum_doc_mime = '';
	var $explnum_doc_extfichier = '';
	var $explnum_doc_file=array();
	
	/*
	 * Constructeur
	 */
	function explnum_doc($id_expl=0){
		global $dbh;
		
		$this->explnum_doc_id = $id_expl;
		if(!$this->explnum_doc_id){
			$this->explnum_doc_nomfichier = '';
	 		$this->explnum_doc_contenu = '';
	 		$this->explnum_doc_mime = '';
			$this->explnum_doc_extfichier = '';
		} else {
			$req = "select * from explnum_doc where id_explnum_doc='".$this->explnum_doc_id."'";
			$res=mysql_query($req,$dbh);
			if(mysql_num_rows($res)){
				$expl = mysql_fetch_object($res);
				$this->explnum_doc_nomfichier = $expl->explnum_doc_nomfichier;
	 			$this->explnum_doc_contenu = $expl->explnum_doc_data;
	 			$this->explnum_doc_mime = $expl->explnum_doc_mimetype;
				$this->explnum_doc_extfichier = $expl->explnum_doc_extfichier;
			} else{
				$this->explnum_doc_nomfichier = '';
	 			$this->explnum_doc_contenu = '';
	 			$this->explnum_doc_mime = '';
				$this->explnum_doc_extfichier = '';
			}
		}
		
	}
	
	/*
	 * Enregistrement
	 */
	function save(){
		global $dbh;
		
		if(!$this->explnum_doc_id){
			//Création
			$req = "insert into explnum_doc set  
					 explnum_doc_nomfichier='".addslashes($this->explnum_doc_nomfichier)."',
					 explnum_doc_mimetype='".addslashes($this->explnum_doc_mime)."',
					 explnum_doc_extfichier='".addslashes($this->explnum_doc_extfichier)."',
					 explnum_doc_data='".addslashes($this->explnum_doc_contenu)."'";
			mysql_query($req,$dbh);
			$this->explnum_doc_id = mysql_insert_id();
					 
		} else{
			//Modification
			$req = "update explnum_doc set  
					 explnum_doc_nomfichier='".addslashes($this->explnum_doc_nomfichier)."',
					 explnum_doc_mimetype='".addslashes($this->explnum_doc_mime)."',
					 explnum_doc_extfichier='".addslashes($this->explnum_doc_extfichier)."',
					 explnum_doc_data='".addslashes($this->explnum_doc_contenu)."'
					 where id_explnum_doc='".$this->explnum_doc_id."'";
			mysql_query($req,$dbh);
		}
	}
	
	/*
	 * Charge le fichier
	 */
	function load_file($file_info=array()){
		
		if($file_info){
			$this->explnum_doc_file = $file_info;
		}
	}	
	
	
	/*
	 * Analyse du fichier pour en récupérer le contenu et les infos
	 */
	
	function analyse_file(){
		
		if($this->explnum_doc_file){
			
			create_tableau_mimetype();
			$userfile_name = $this->explnum_doc_file['name'] ;
			$userfile_temp = $this->explnum_doc_file['tmp_name'] ;
			$userfile_moved = basename($userfile_temp);
			$userfile_name = preg_replace("/ |'|\\|\"|\//m", "_", $userfile_name);
			$userfile_ext = '';
			if ($userfile_name) {
				$userfile_ext = extension_fichier($userfile_name);
			}		
			move_uploaded_file($userfile_temp,"./temp/".$userfile_moved);
			$file_name = "./temp/".$userfile_moved;
			$fp = fopen($file_name , "r" ) ;
			$contenu = fread ($fp, filesize($file_name));
			fclose ($fp) ;
			$mime = trouve_mimetype($userfile_moved,$userfile_ext) ;
			if (!$mime) $mime="application/data";
			
			$this->explnum_doc_mime = $mime;
			$this->explnum_doc_nomfichier = $userfile_name;
			$this->explnum_doc_extfichier = $userfile_ext;
			$this->explnum_doc_contenu = $contenu;
			
			unlink($file_name);
		}
	}
}
?>