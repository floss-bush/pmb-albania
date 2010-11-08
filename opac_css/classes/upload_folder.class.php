<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: upload_folder.class.php,v 1.1 2009-07-03 09:35:44 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

class upload_folder {
	
	var $repertoire_id=0;
	var $action='';
	var $nb_enregistrement=0;
	var $repertoire_nom='';
	var $repertoire_url='';
	var $repertoire_path='';
	var $repertoire_navigation=0;
	var $repertoire_hachage=0;
	var $repertoire_subfolder=20;
	var $repertoire_utf8=0;
	
	function upload_folder($id=0, $action=''){
		global $dbh;
		
		$this->repertoire_id = $id;
		$this->action = $action;	
		
		if($this->repertoire_id){
			//Modification
			$req="select repertoire_nom, repertoire_url, repertoire_path, repertoire_navigation, repertoire_hachage, repertoire_subfolder, repertoire_utf8 from upload_repertoire where repertoire_id='".$this->repertoire_id."'";
			$res=mysql_query($req,$dbh);
			if(mysql_num_rows($res)){
				$item = mysql_fetch_object($res);
				$this->repertoire_nom=$item->repertoire_nom;
				$this->repertoire_url=$item->repertoire_url;
				$this->repertoire_path=$item->repertoire_path;
				$this->repertoire_navigation=$item->repertoire_navigation;
				$this->repertoire_hachage=$item->repertoire_hachage;
				$this->repertoire_subfolder=$item->repertoire_subfolder;
				$this->repertoire_utf8=$item->repertoire_utf8;
			} else {
				$this->repertoire_nom='';
				$this->repertoire_url='';
				$this->repertoire_path='';
				$this->repertoire_navigation=0;
				$this->repertoire_hachage=0;
				$this->repertoire_subfolder=20;
				$this->repertoire_utf8=0;
			}
		} else {
			//Création
			$this->repertoire_nom='';
			$this->repertoire_url='';
			$this->repertoire_path='';
			$this->repertoire_navigation=0;
			$this->repertoire_hachage=0;
			$this->repertoire_subfolder=20;
			$this->repertoire_utf8=0;
		}
	}
	
	
	/*
	 * Retourne si le repertoire est haché
	 */
	function isHashing(){
		return $this->repertoire_hachage;
	}
	
	/*
	 * Retourne si le repertoire est en utf8
	 */
	function isUtf8(){
		return $this->repertoire_utf8;
	}
	
	/*
	 * Hache le nom de fichier pour le classer
	 */
	function hachage($nom_fichier){
								
		$chemin= $this->repertoire_path;
		$nb_dossier = $this->repertoire_subfolder;
		$total=0;
		for($i=0;$i<strlen($nom_fichier);$i++){				
			$total += ord($nom_fichier[$i]);
		}		
		$total = $total % $nb_dossier;		
		$rep_hash = $chemin.$total."/";
		$rep_hash = str_replace("//","/",$rep_hash);
		
		return $rep_hash;
	}
	
	/*
	 * décode la chaine dans le bon charset
	 */
	function decoder_chaine($chaine){
		global $charset;
		
		if($charset != "utf-8" && $this->isUtf8())
			return utf8_decode($chaine);
		
		return $chaine;
	}
	
	/*
 	 * encode la chaine dans le bon charset
	 */
	function encoder_chaine($chaine){
		global $charset;
		
		if($charset != "utf-8" && $this->isUtf8())
			return utf8_encode($chaine);
		
		return $chaine;
	}
	
	
}
?>