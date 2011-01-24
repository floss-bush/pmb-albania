<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: aut_link.class.php,v 1.3 2010-12-06 15:53:22 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");
// gestion des liens entre autorités

require_once("$class_path/marc_table.class.php");
require_once("$class_path/author.class.php");
require_once("$class_path/editor.class.php");
require_once("$class_path/collection.class.php");
require_once("$class_path/subcollection.class.php");
require_once("$class_path/indexint.class.php");
require_once("$class_path/serie.class.php");
require_once("$class_path/category.class.php");
require_once("$class_path/titre_uniforme.class.php");

require_once($include_path."/templates/aut_link.tpl.php");

define('AUT_TABLE_AUTHORS',1);
define('AUT_TABLE_CATEG',2);
define('AUT_TABLE_PUBLISHERS',3);
define('AUT_TABLE_COLLECTIONS',4);
define('AUT_TABLE_SUB_COLLECTIONS',5);
define('AUT_TABLE_SERIES',6);
define('AUT_TABLE_TITRES_UNIFORMES',7);
define('AUT_TABLE_INDEXINT',8);

$aut_table_name_list=array(
	AUT_TABLE_AUTHORS => 'authors',
	AUT_TABLE_CATEG => 'categ',
	AUT_TABLE_PUBLISHERS=> 'publishers',
	AUT_TABLE_COLLECTIONS => 'collection',
	AUT_TABLE_SUB_COLLECTIONS => 'sub_collections',
	AUT_TABLE_SERIES => 'series',
	AUT_TABLE_TITRES_UNIFORMES => 'titres_uniformes',
	AUT_TABLE_INDEXINT => 'indexint'
); 

// définition de la classe de gestion des liens entre autorités
class aut_link {

	function aut_link($aut_table,$id) {
		$this->aut_table = $aut_table;
		$this->id = $id;
		$this->getdata();
	}	

	function getdata() {
		global $dbh,$msg;
		global $aut_table_name_list;
		$this->aut_table_name = $aut_table_name_list[$this->aut_table];
		$this->aut_list=array();		
			
		$rqt="select * from aut_link where (aut_link_from='".$this->aut_table."'	and aut_link_from_num='".$this->id."' )
		or ( aut_link_to='".$this->aut_table."' and aut_link_to_num='".$this->id."' and aut_link_reciproc=1 )
		order by aut_link_type ";
		
		$aut_res=mysql_query($rqt, $dbh);
		$i=0;
		while($row = mysql_fetch_object($aut_res)){
			$i++;
			$this->aut_list[$i]["to"]=$row->aut_link_to;
			$this->aut_list[$i]["to_num"]=$row->aut_link_to_num;				
			$this->aut_list[$i]["type"]=$row->aut_link_type;						
			$this->aut_list[$i]["reciproc"]=$row->aut_link_reciproc;					
			$this->aut_list[$i]["comment"]=$row->aut_link_comment;	
						
			if(($this->aut_table==$row->aut_link_to ) and ($this->id == $row->aut_link_to_num)) {
				$this->aut_list[$i]["flag_reciproc"]=1;							
				$this->aut_list[$i]["to"]=$row->aut_link_from;
				$this->aut_list[$i]["to_num"]=$row->aut_link_from_num;				
			}	
			else $this->aut_list[$i]["flag_reciproc"]=0;
			
			switch($this->aut_list[$i]["to"]){
				case AUT_TABLE_AUTHORS :
					$auteur = new auteur($this->aut_list[$i]["to_num"]);
					$this->aut_list[$i]["isbd_entry"]=$auteur->isbd_entry; 
					$this->aut_list[$i]["libelle"]="[".$msg[133]."] ".$auteur->isbd_entry; 
				break;
				case AUT_TABLE_CATEG :
					$categ = new category($this->aut_list[$i]["to_num"]);
					$this->aut_list[$i]["isbd_entry"]=$categ->libelle;
					$this->aut_list[$i]["libelle"]="[".$msg[134]."] ".$categ->libelle;		
				break;
				case AUT_TABLE_PUBLISHERS :					
					$ed = new editeur($this->aut_list[$i]["to_num"]) ;
					$this->aut_list[$i]["isbd_entry"]=$ed->isbd_entry;	
					$this->aut_list[$i]["libelle"]="[".$msg[135]."] ".$ed->isbd_entry;			
				break;
				case AUT_TABLE_COLLECTIONS :
					$subcollection = new collection($this->aut_list[$i]["to_num"]);
					$this->aut_list[$i]["isbd_entry"]=$subcollection->isbd_entry;
					$this->aut_list[$i]["libelle"]="[".$msg[136]."] ".$subcollection->isbd_entry;
				break;
				case AUT_TABLE_SUB_COLLECTIONS :
					$collection = new subcollection($this->aut_list[$i]["to_num"]);
					$this->aut_list[$i]["isbd_entry"]=$collection->isbd_entry;
					$this->aut_list[$i]["libelle"]="[".$msg[137]."] ".$collection->isbd_entry;
				break;
				case AUT_TABLE_SERIES :
					$serie = new serie($this->aut_list[$i]["to_num"]);
					$this->aut_list[$i]["isbd_entry"]=$serie->name;
					$this->aut_list[$i]["libelle"]="[".$msg[333]."] ".$serie->name;
				break;
				case AUT_TABLE_TITRES_UNIFORMES :
					$tu = new titre_uniforme($this->aut_list[$i]["to_num"]);
					$this->aut_list[$i]["isbd_entry"]=$tu->name;	
					$this->aut_list[$i]["libelle"]="[".$msg["aut_menu_titre_uniforme"]."] ".$tu->name;					
				break;
				case AUT_TABLE_INDEXINT :
					$indexint = new indexint($this->aut_list[$i]["to_num"]);
					$this->aut_list[$i]["isbd_entry"]=$indexint->display;
					$this->aut_list[$i]["libelle"]="[".$msg["indexint_menu"]."] ".$indexint->display;				
				break;
			}
		}		
	}

	function get_form($caller="categ_form") {
		global $msg,$add_aut_link,$aut_link0,$aut_link1,$form_aut_link;
		
		$form=$add_aut_link;
		$js_aut_link_table_list="
		var aut_link_table_select=Array();
		aut_link_table_select[".AUT_TABLE_AUTHORS."]='./select.php?what=auteur&caller=$caller&dyn=2&param1=';		
		aut_link_table_select[".AUT_TABLE_CATEG."]='./select.php?what=categorie&caller=$caller&dyn=2&parent=1&p1=';
		aut_link_table_select[".AUT_TABLE_PUBLISHERS."]='./select.php?what=editeur&caller=$caller&dyn=2&p1=';
		aut_link_table_select[".AUT_TABLE_COLLECTIONS."]='./select.php?what=collection&caller=$caller&dyn=2&p1=';
		aut_link_table_select[".AUT_TABLE_SUB_COLLECTIONS."]='./select.php?what=subcollection&caller=$caller&dyn=2&p1=';
		aut_link_table_select[".AUT_TABLE_SERIES."]='./select.php?what=serie&caller=$caller&dyn=2&param1=';
		aut_link_table_select[".AUT_TABLE_TITRES_UNIFORMES."]='./select.php?what=titre_uniforme&caller=$caller&dyn=2&param1=';
		aut_link_table_select[".AUT_TABLE_INDEXINT."]='./select.php?what=indexint&caller=$caller&dyn=2&param1=';
		";
		$aut_table_list="
		<select id='f_aut_link_table_list' name='f_aut_link_table_list'>
			<option value='".AUT_TABLE_AUTHORS."' selected='selected'>".$msg[133]."</option>
			<option value='".AUT_TABLE_CATEG."'>".$msg[134]."</option>
			<option value='".AUT_TABLE_PUBLISHERS."'>".$msg[135]."</option>
			<option value='".AUT_TABLE_COLLECTIONS."'>".$msg[136]."</option>
			<option value='".AUT_TABLE_SUB_COLLECTIONS."'>".$msg[137]."</option>
			<option value='".AUT_TABLE_SERIES."'>".$msg[333]."</option>
			<option value='".AUT_TABLE_TITRES_UNIFORMES."'>".$msg["aut_menu_titre_uniforme"]."</option>
			<option value='".AUT_TABLE_INDEXINT."'>".$msg["indexint_menu"]."</option>
		</select>";
		
		$i=0;
		if(!count($this->aut_list)){		
			// pas d'enregistrement	
			$form.=$aut_link0;
			
			$liste_type_relation=new marc_select("relationtype_aut","f_aut_link_type$i", $aut["type"]);	
			$form=str_replace("!!aut_link_type!!",$liste_type_relation->display,$form);				
			$form=str_replace("!!aut_link_reciproc!!","unchecked='unchecked'",$form);	
			$form=str_replace("!!aut_link!!",$i,$form);	
			$form=str_replace("!!aut_link_libelle!!","",$form);
			$form=str_replace("!!aut_link_table!!","",$form);
			$form=str_replace("!!aut_link_id!!","",$form);	
			$form=str_replace("!!aut_link_comment!!","",$form);
			$i++;
		} else{			
			foreach ($this->aut_list as $aut) {	
				// Construction de chaque ligne du formulaire	
				if($i) $form_suivant=$aut_link1; else $form_suivant=$aut_link0;		
				if($aut["flag_reciproc"]){
					$liste_type_relation=new marc_select("relationtype_autup","f_aut_link_type$i", $aut["type"]);
				}else {
					$liste_type_relation=new marc_select("relationtype_aut","f_aut_link_type$i", $aut["type"]);
				}
				$form_suivant=str_replace("!!aut_link_type!!",$liste_type_relation->display,$form_suivant);
				if($aut["reciproc"]) $check="checked='checked'"; else $check="";
				$form_suivant=str_replace("!!aut_link_reciproc!!",$check,$form_suivant);	
				$form_suivant=str_replace("!!aut_link!!",$i,$form_suivant);
				$form_suivant=str_replace("!!aut_link_libelle!!",$aut["libelle"],$form_suivant);
				$form_suivant=str_replace("!!aut_link_table!!",$aut["to"],$form_suivant);
				$form_suivant=str_replace("!!aut_link_id!!",$aut["to_num"],$form_suivant);
				$form_suivant=str_replace("!!aut_link_comment!!",$aut["comment"],$form_suivant);
				$form.=$form_suivant;		
				$i++;		
			}				
		}
		$form=str_replace("!!max_aut_link!!",$i,$form);
		$form=str_replace("!!js_aut_link_table_list!!",$js_aut_link_table_list,$form);
		$form=str_replace("!!aut_table_list!!",$aut_table_list,$form);
		$form = str_replace("!!aut_link_contens!!", $form , $form_aut_link);
		return $form;
	}
	
	function save_form() {
		global $dbh;
		//max_aut_link
		//f_aut_link_typexxx
		//f_aut_link_tablexxx
		//f_aut_link_idxxx
		global $max_aut_link;
		if(!$this->aut_table && !$this->id) return;
		$this->delete_link();
		for($i=0;$i<$max_aut_link;$i++){
			eval("global \$f_aut_link_table".$i.";\$f_aut_link_table= \$f_aut_link_table$i;"); 
			eval("global \$f_aut_link_id".$i.";\$f_aut_link_id= \$f_aut_link_id$i;"); 
			eval("global \$f_aut_link_type".$i.";\$f_aut_link_type= \$f_aut_link_type$i;"); 
			eval("global \$f_aut_link_reciproc".$i.";\$f_aut_link_reciproc= \$f_aut_link_reciproc$i;"); 
			eval("global \$f_aut_link_comment".$i.";\$f_aut_link_comment= \$f_aut_link_comment$i;"); 
			if($f_aut_link_reciproc)$f_aut_link_reciproc=1;
			if($f_aut_link_id && $f_aut_link_table && $f_aut_link_id && $f_aut_link_type) {
	 			$requete="INSERT INTO aut_link (aut_link_from, aut_link_from_num, aut_link_to,aut_link_to_num , aut_link_type, aut_link_reciproc, aut_link_comment) 
	 			VALUES ('".$this->aut_table."', '".$this->id."','".$f_aut_link_table."', '".$f_aut_link_id."', '".$f_aut_link_type."', '".$f_aut_link_reciproc."','".$f_aut_link_comment."')";
				mysql_query($requete);				
			}	
		}
	}
			
	// delete tous les liens (from vers to) de cette autorité 
	function delete_link() {
		global $dbh;
		if(!$this->aut_table && !$this->id) return;
		$requete="DELETE FROM aut_link WHERE aut_link_from='".$this->aut_table."' and aut_link_from_num='".$this->id."' ";
		mysql_query($requete, $dbh);
		$requete="DELETE FROM aut_link WHERE aut_link_to='".$this->aut_table."' and aut_link_to_num='".$this->id."' and aut_link_reciproc=1 ";
		mysql_query($requete, $dbh);
	}		
	
	// delete tous les liens (from et to) de cette autorité 
	function delete() {
		global $dbh;
		if(!$this->aut_table && !$this->id) return;
		$requete="DELETE FROM aut_link WHERE aut_link_from='".$this->aut_table."' and aut_link_from_num='".$this->id."' ";
		mysql_query($requete, $dbh);
		$requete="DELETE FROM aut_link WHERE aut_link_to='".$this->aut_table."' and aut_link_to_num='".$this->id."' ";
		mysql_query($requete, $dbh);
	}	
	
	// copie les liens from et to par une autre autorité
	function add_link_to($copy_table,$copy_num) {
		global $dbh;
		if(!$this->aut_table && !$this->id && !$copy_link_to && !$copy_link_to_num) return;
		
		foreach ($this->aut_list as $aut) {		
			if($aut["flag_reciproc"]){
		 		$requete="INSERT INTO aut_link (aut_link_from, aut_link_from_num, aut_link_to,aut_link_to_num , aut_link_type, aut_link_reciproc, aut_link_comment) 
		 		VALUES ('".$aut["to"]."', '".$aut["to_num"]."','".$copy_table."', '".$copy_num."', '".$aut["type"]."', '".$aut["reciproc"]."','".$aut["comment"]."')";					
			}else {
		 		$requete="INSERT INTO aut_link (aut_link_from, aut_link_from_num, aut_link_to,aut_link_to_num , aut_link_type, aut_link_reciproc, aut_link_comment) 
		 		VALUES ('".$copy_table."', '".$copy_num."','".$aut["to"]."', '".$aut["to_num"]."', '".$aut["type"]."', '".$aut["reciproc"]."','".$aut["comment"]."')";							
			}
			@mysql_query($requete);
		}		
	}
// fin class
}