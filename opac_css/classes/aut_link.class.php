<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: aut_link.class.php,v 1.1 2010-06-16 12:13:48 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");
// gestion des liens entre autorités

require_once("$class_path/marc_table.class.php");
require_once("$class_path/author.class.php");
require_once("$class_path/publisher.class.php");
require_once("$class_path/collection.class.php");
require_once("$class_path/subcollection.class.php");
require_once("$class_path/indexint.class.php");
require_once("$class_path/serie.class.php");
require_once("$class_path/category.class.php");
require_once("$class_path/titre_uniforme.class.php");

//require_once($include_path."/templates/aut_link.tpl.php");

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
			
		$rqt="select * from aut_link where (aut_link_from='".$this->aut_table."' and aut_link_from_num='".$this->id."') or ( aut_link_to='".$this->aut_table."' and aut_link_to_num='".$this->id."' and aut_link_reciproc=1)
		order by aut_link_type ";
		$aut_res=mysql_query($rqt, $dbh);
		$i=0;
		while($row = mysql_fetch_object($aut_res)){
			$i++;
			if($row->aut_link_to==$this->aut_table && $row->aut_link_to_num==$this->id  ) {
				$this->aut_list[$i]["to"]=$row->aut_link_from;
				$this->aut_list[$i]["to_num"]=$row->aut_link_from_num;		
				$this->aut_list[$i]["reciproc"]=1;
			} else{
				$this->aut_list[$i]["to"]=$row->aut_link_to;
				$this->aut_list[$i]["to_num"]=$row->aut_link_to_num;		
				$this->aut_list[$i]["reciproc"]=0;
			}	
				$this->aut_list[$i]["type"]=$row->aut_link_type;					
				//$this->aut_list[$i]["reciproc"]=$row->aut_link_reciproc;						
				$this->aut_list[$i]["comment"]=$row->aut_link_comment;
				
			switch($this->aut_list[$i]["to"]){
				case AUT_TABLE_AUTHORS :
					$auteur = new auteur($this->aut_list[$i]["to_num"]);
					$this->aut_list[$i]["isbd_entry"]=$auteur->isbd_entry; 
					$this->aut_list[$i]["libelle"]="[".$msg["author"]."] ".$auteur->isbd_entry; 
				break;
				case AUT_TABLE_CATEG :
					$categ = new category($this->aut_list[$i]["to_num"]);
					$this->aut_list[$i]["isbd_entry"]=$categ->libelle;
					$this->aut_list[$i]["libelle"]="[".$msg[110]."] ".$categ->libelle;		
				break;
				case AUT_TABLE_PUBLISHERS :					
					$ed = new publisher($this->aut_list[$i]["to_num"]) ;
					$this->aut_list[$i]["isbd_entry"]=$ed->isbd_entry;	
					$this->aut_list[$i]["libelle"]="[".$msg["collection_tpl_publisher"]."] ".$ed->isbd_entry;			
				break;
				case AUT_TABLE_COLLECTIONS :
					$subcollection = new collection($this->aut_list[$i]["to_num"]);
					$this->aut_list[$i]["isbd_entry"]=$subcollection->isbd_entry;
					$this->aut_list[$i]["libelle"]="[".$msg["coll_search"]."] ".$subcollection->isbd_entry;
				break;
				case AUT_TABLE_SUB_COLLECTIONS :
					$collection = new subcollection($this->aut_list[$i]["to_num"]);
					$this->aut_list[$i]["isbd_entry"]=$collection->isbd_entry;
					$this->aut_list[$i]["libelle"]="[".$msg["subcoll_search"]."] ".$collection->isbd_entry;
				break;
				case AUT_TABLE_SERIES :
					$serie = new serie($this->aut_list[$i]["to_num"]);
					$this->aut_list[$i]["name"]=$serie->name;
					$this->aut_list[$i]["libelle"]="[".$msg["serie_query"]."] ".$serie->name;
				break;
				case AUT_TABLE_TITRES_UNIFORMES :
					$tu = new titre_uniforme($this->aut_list[$i]["to_num"]);
					$this->aut_list[$i]["isbd_entry"]=$tu->name;	
					$this->aut_list[$i]["libelle"]="[".$msg["titre_uniforme_search"]."] ".$tu->name;					
				break;
				case AUT_TABLE_INDEXINT :
					$indexint = new indexint($this->aut_list[$i]["to_num"]);
					$this->aut_list[$i]["isbd_entry"]=$indexint->display;
					$this->aut_list[$i]["libelle"]="[".$msg["indexint_search"]."] ".$indexint->display;				
				break;
			}
		}		
	}

	function get_display($caller="categ_form") {
		global $msg;
		
		if(!count($this->aut_list)) return"";

		$aut_link_table_select[AUT_TABLE_AUTHORS]='./index.php?lvl=author_see&id=!!to_num!!';		
		$aut_link_table_select[AUT_TABLE_CATEG]='./index.php?lvl=categ_see&id=!!to_num!!';
		$aut_link_table_select[AUT_TABLE_PUBLISHERS]='./index.php?lvl=publisher_see&id=!!to_num!!';
		$aut_link_table_select[AUT_TABLE_COLLECTIONS]='./index.php?lvl=coll_see&id=!!to_num!!';
		$aut_link_table_select[AUT_TABLE_SUB_COLLECTIONS]='./index.php?lvl=subcoll_see&id=!!to_num!!';
		$aut_link_table_select[AUT_TABLE_SERIES]='./index.php?lvl=serie_see&id=!!to_num!!';
		$aut_link_table_select[AUT_TABLE_TITRES_UNIFORMES]='./index.php?lvl=titre_uniforme_see&id=!!to_num!!';
		$aut_link_table_select[AUT_TABLE_INDEXINT]='./index.php?lvl=indexint_see&id=!!to_num!!';

		$marc_table=new marc_list("relationtype_aut");
		$liste_type_relation = $marc_table->table;
		$marc_tableup=new marc_list("relationtype_autup");
		$liste_type_relationup = $marc_tableup->table;
		
		$aff="<br />";
		foreach ($this->aut_list as $aut) {				
			//print"<pre>";print_r($aut);print"</pre>";
			if($aut["reciproc"])	$aff.=$liste_type_relationup[$aut["type"]]." : ";
			else	$aff.=$liste_type_relation[$aut["type"]]." : ";
			$link=str_replace("!!to_num!!",$aut["to_num"],$aut_link_table_select[$aut["to"]]);
			$aff.=" <a href=".$link.">".$aut["libelle"]."</a>";
			if($aut["comment"]) {
				$aff.=" (".$aut["comment"].")";
			}	
			$aff.="<br />";	
		}				
		return $aff;
	}
	
	
// fin class
}