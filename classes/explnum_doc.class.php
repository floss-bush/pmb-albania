<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: explnum_doc.class.php,v 1.4 2010-02-08 11:28:09 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($include_path.'/explnum.inc.php');

class explnum_doc{
	
	var $explnum_doc_id = 0;
	var $explnum_doc_nomfichier = '';
	var $explnum_doc_contenu = '';
	var $explnum_doc_mime = '';
	var $explnum_doc_extfichier = '';
	var $explnum_doc_file=array();
	var $explnum_doc_url ='';
	
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
			$this->explnum_doc_url = '';
		} else {
			$req = "select * from explnum_doc where id_explnum_doc='".$this->explnum_doc_id."'";
			$res=mysql_query($req,$dbh);
			if(mysql_num_rows($res)){
				$expl = mysql_fetch_object($res);
				$this->explnum_doc_nomfichier = $expl->explnum_doc_nomfichier;
	 			$this->explnum_doc_contenu = $expl->explnum_doc_data;
	 			$this->explnum_doc_mime = $expl->explnum_doc_mimetype;
				$this->explnum_doc_extfichier = $expl->explnum_doc_extfichier;
				$this->explnum_doc_url = $expl->explnum_doc_url;
			} else{
				$this->explnum_doc_nomfichier = '';
	 			$this->explnum_doc_contenu = '';
	 			$this->explnum_doc_mime = '';
				$this->explnum_doc_extfichier = '';
				$this->explnum_doc_url = '';
			}
		}
		
	}
	
	/*
	 * Suppression
	 */
	function delete(){
		global $dbh;

		$req = "delete from explnum_doc where id_explnum_doc='".$this->explnum_doc_id."'";
		mysql_query($req,$dbh);
		
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
					 explnum_doc_data='".addslashes($this->explnum_doc_contenu)."',
					 explnum_doc_url='".addslashes($this->explnum_doc_url)."'
					 ";
			mysql_query($req,$dbh);
			$this->explnum_doc_id = mysql_insert_id();
					 
		} else{
			//Modification
			$req = "update explnum_doc set  
					 explnum_doc_nomfichier='".addslashes($this->explnum_doc_nomfichier)."',
					 explnum_doc_mimetype='".addslashes($this->explnum_doc_mime)."',
					 explnum_doc_extfichier='".addslashes($this->explnum_doc_extfichier)."',
					 explnum_doc_data='".addslashes($this->explnum_doc_contenu)."',
					 explnum_doc_url='".addslashes($this->explnum_doc_url)."'
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
	
	/*
	 * Affecte un nom de fichier si il a été défini
	 */
	function setName($nom=''){
		if($nom)
			$this->explnum_doc_nomfichier = stripslashes($nom);
	}
	
	/*
	 * Affiche les documents numériques dans un tableau
	 */
	function show_docnum_table($docnum_tab=array(),$action){
		global $charset;
		
		create_tableau_mimetype();
		$display = "";
		if($docnum_tab){
			$nb_doc = 0;
			$display .= "<table>
							<tbody>";
			for($i=0;$i<count($docnum_tab);$i++){
				$nb_doc++;
				if($nb_doc == 1) $display .= "<tr>";
				$alt = htmlentities($docnum_tab[$i]['explnum_doc_nomfichier'],ENT_QUOTES,$charset).' - '.htmlentities($docnum_tab[$i]['explnum_doc_mimetype'],ENT_QUOTES,$charset);
				$display .= "<td class='docnum' style='width:25%;border:1px solid #CCCCCC;padding : 5px 5px'>
						<a target='_blank' alt='$alt' title='$alt' href=\"./explnum_doc.php?explnumdoc_id=".$docnum_tab[$i]['id_explnum_doc']."\">
							<img src='./images/mimetype/".icone_mimetype($docnum_tab[$i]['explnum_doc_mimetype'],$docnum_tab[$i]['explnum_doc_extfichier'])."' alt='$alt' title='$alt' >
						</a>
						<br />
						<a href='$action&explnumdoc_id=".$docnum_tab[$i]['id_explnum_doc']."'>".htmlentities($docnum_tab[$i]['explnum_doc_nomfichier'],ENT_QUOTES,$charset)."
						</a>
						<div class='explnum_type'>".$docnum_tab[$i]['explnum_doc_mimetype']."</div>
						</td>
					";
				if($nb_doc == 4) {
					$display .= "</tr>";
					$nb_doc=0;
				}
			}
			$display .= "</tbody></table>";
		}
		
		return $display;
	}
	
}
?>