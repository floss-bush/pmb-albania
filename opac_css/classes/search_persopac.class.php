<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: search_persopac.class.php,v 1.5 2010-06-18 15:27:41 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

// classes de gestion des recherches personnalisées

// inclusions principales
require_once("$include_path/templates/search_persopac.tpl.php");
require_once("$class_path/search.class.php");
require_once("$class_path/translation.class.php");
class search_persopac {

// constructeur
function search_persopac($id=0) {	
	// si id, allez chercher les infos dans la base
	if($id) {
		$this->id = $id;
		$this->fetch_data();
	}else $this->get_link();
	return $this->id;
}
    
// récupération des infos en base
function fetch_data() {
	global $dbh;	
	$myQuery = mysql_query("SELECT * FROM search_persopac WHERE search_id='".$this->id."' LIMIT 1", $dbh);
	$myreq= mysql_fetch_object($myQuery);
	$this->name=translation::get_text($this->id,"search_persopac","search_name",$myreq->search_name);
	$this->shortname=translation::get_text($this->id,"search_persopac","search_shortname",$myreq->search_shortname);	
	$this->query=$myreq->search_query;
	$this->human=$myreq->search_human;
	$this->directlink=$myreq->search_directlink;
	$this->limitsearch=$myreq->search_limitsearch;
}

function get_link() {
	global $dbh,$onglet_persopac;	
	$myQuery = mysql_query("SELECT * FROM search_persopac order by search_name ", $dbh);
	
	$this->search_persopac_list=array();
	$link="";
	if(mysql_num_rows($myQuery)){
		$i=0;
		while(($r=mysql_fetch_object($myQuery))) {	
			$name=translation::get_text($r->search_id,"search_persopac","search_name",$r->search_name);	
			$shortname=translation::get_text($r->search_id,"search_persopac","search_shortname",$r->search_shortname);				
			if($r->search_directlink) {					
				if($shortname)$libelle=$shortname;
				else $libelle=$name;
				$my_search=new search();
				$backup_search=$my_search->serialize_search();
				$my_search->unserialize_search($r->search_query);
				$forms_search.= "\n".$my_search->make_hidden_search_form("./index.php?search_type_asked=extended_search&onglet_persopac=".$r->search_id."&limitsearch=".$r->search_limitsearch,"search_form".$r->search_id)."\n";				
				$my_search->unserialize_search($backup_search);
				if($onglet_persopac==$r->search_id) {
					$li_id=" id='current' ";
					$lien=$libelle;
				} else {
					$li_id="";
					$lien="<a href=\"javascript:document.forms['search_form".$r->search_id."'].submit();\">".$libelle."</a>";
				}
				$link.="
					<li $li_id >
						$lien	
					</li>";
			}		
			$this->search_persopac_list[$i]->id=$r->search_id;
			$this->search_persopac_list[$i]->name=$name;
			$this->search_persopac_list[$i]->shortname=$shortname;
			$this->search_persopac_list[$i]->query=$r->search_query;
			$this->search_persopac_list[$i]->human=$r->search_human;
			$this->search_persopac_list[$i]->directlink=$r->search_directlink;	
			$this->search_persopac_list[$i]->limitsearch=$r->search_limitsearch;				
			$i++;						
		}	
	}
	$this->directlink_user=$link;
	$this->directlink_user_form=$forms_search;
	return true;
}

// fonction générant le form de saisie 
function do_list() {
	global $tpl_search_persopac_liste_tableau,$tpl_search_persopac_liste_tableau_ligne;
	// liste des lien de recherche directe
	$liste="";
	// pour toute les recherche de l'utilisateur
	for($i=0;$i<count($this->search_persopac_list);$i++) {
		if ($i % 2) $pair_impair = "even"; else $pair_impair = "odd";      
		//composer le formulaire de la recherche
		$my_search=new search();
		$my_search->unserialize_search($this->search_persopac_list[$i]->query);
		$forms_search.= $my_search->make_hidden_search_form("./index.php?search_type_asked=extended_search&limitsearch=".$this->search_persopac_list[$i]->limitsearch,"search_form".$this->search_persopac_list[$i]->id);
		
        $td_javascript="  onmousedown=\"javascript:document.forms['search_form".$this->search_persopac_list[$i]->id."'].submit();\" ";	
        $tr_surbrillance = "onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='".$pair_impair."'\" ";

        $line = str_replace('!!td_javascript!!',$td_javascript , $tpl_search_persopac_liste_tableau_ligne);
        $line = str_replace('!!tr_surbrillance!!',$tr_surbrillance , $line);
        $line = str_replace('!!pair_impair!!',$pair_impair , $line);

		$line =str_replace('!!id!!', $this->search_persopac_list[$i]->id, $line);
		$line = str_replace('!!name!!', $this->search_persopac_list[$i]->name, $line);
		$line = str_replace('!!human!!', $this->search_persopac_list[$i]->human, $line);		
		$line = str_replace('!!shortname!!', $this->search_persopac_list[$i]->shortname, $line);
		
		$liste.=$line;
	}
	 
	$tpl_search_persopac_liste_tableau = str_replace('!!lignes_tableau!!',$liste , $tpl_search_persopac_liste_tableau);
	return $forms_search.$tpl_search_persopac_liste_tableau;	
}


} // fin définition classe
