<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: search_perso.class.php,v 1.1 2009-03-25 13:15:48 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

// classes de gestion des recherches personnalisées

// inclusions principales
require_once("$include_path/templates/search_perso.tpl.php");
require_once("$class_path/search.class.php");

class search_perso {

// constructeur
function search_perso($id=0) {
	global $PMBuserid,$search_perso_user;
	// si id, allez chercher les infos dans la base
	if($id) {
		$this->id = $id;
		$this->fetch_data();
	}	
	$this->user = $PMBuserid;
	if(!$search_perso_user) {
		$search_perso_user=$this->get_link_user();
	}
	return $this->id;
}
    
// récupération des infos en base
function fetch_data() {
	global $dbh;
	
	$myQuery = mysql_query("SELECT * FROM search_perso WHERE search_id='".$this->id."' LIMIT 1", $dbh);
	$myreq= mysql_fetch_object($myQuery);
	
	$this->user=$myreq->num_user;
	$this->name=$myreq->search_name;
	$this->shortname=$myreq->search_shortname;
	$this->query=$myreq->search_query;
	$this->human=$myreq->search_human;
	$this->directlink=$myreq->search_directlink;
}

function get_link_user() {
	global $dbh,$PMBuserid;	
	$myQuery = mysql_query("SELECT * FROM search_perso WHERE num_user='".$PMBuserid."' order by search_name ", $dbh);
	$this->search_perso_user=array();
	$link="";
	if(mysql_num_rows($myQuery)){
		$i=0;
		while(($r=mysql_fetch_object($myQuery))) {
			if($r->search_directlink) {				
				if($r->search_shortname)$libelle=$r->search_shortname;
				else $libelle=$r->search_name;
				$link.="
					<span>
						<a href=\"javascript:document.forms['search_form".$r->search_id."'].submit();\">$libelle</a>
					</span>
				";
			}		
			$this->search_perso_user[$i]->id=$r->search_id;
			$this->search_perso_user[$i]->name=$r->search_name;
			$this->search_perso_user[$i]->shortname=$r->search_shortname;
			$this->search_perso_user[$i]->query=$r->search_query;
			$this->search_perso_user[$i]->human=$r->search_human;
			$this->search_perso_user[$i]->directlink=$r->search_directlink;					
			$i++;			
		}	
	}	
	$this->directlink_user=$link;
	return true;
}
// fonction de mise à jour ou de création 
function update($value) {	
	global $dbh,$msg,$search_perso_user;
	$fields="";
	foreach($value as $key => $val) {
		if($fields) $fields.=","; 
		$fields.=" $key='$val' ";	
	}		
	if($this->id) {
		// modif
		$no_erreur=mysql_query("UPDATE search_perso SET $fields WHERE search_id=".$this->id, $dbh);	
		if(!$no_erreur) {
			error_message($msg["search_perso_form_edit"], $msg["search_perso_form_add_error"],1);	
			exit;
		}
		
	} else {
		// create
		$no_erreur=mysql_query("INSERT INTO search_perso SET $fields ", $dbh);
		$this->id = mysql_insert_id($dbh);
		if(!$no_erreur) {
			error_message($msg["search_perso_form_add"], $msg["search_perso_form_add_error"],1);
			exit;
		}
	}	
	// rafraischissement des données
	$this->fetch_data();
	$search_perso_user=$this->get_link_user();
	return $this->id;
}

function update_from_form() {
	global $PMBuserid,$name,$shortname,$query,$human,$directlink;
	
	$value->num_user=$PMBuserid;
	$value->search_name=$name;
	$value->search_shortname=$shortname;
	$value->search_query=$query;
	$value->search_human=$human;
	$value->search_directlink=$directlink;
	
	$this->update($value); 	
}

// fonction générant le form de saisie 
function do_form() {
	global $msg,$tpl_search_perso_form,$charset;	
	
	// titre formulaire
	if($this->id) {
		$libelle=$msg["search_perso_form_edit"];
		$link_delete="<input type='button' class='bouton' value='".$msg[63]."' onClick=\"confirm_delete();\" />";
		
	} else {
		$libelle=$msg["search_perso_form_add"];
		$link_delete="";
		/*
		foreach($_POST as $key =>$val) {
			if($val) {
				if(is_array($val)) {
					foreach($val as $val_array) {
						$memo_search.= "<input type='hidden' name='".$key."[]' value='$val_array'/>";
					}
				}
				else $memo_search.="<input type='hidden' name='$key' value='$val'/>";
			}		
		}		
		$this->query=$memo_search;
		
		global $search;  	
    	for ($i=0; $i<count($search); $i++) {
    		$op="op_".$i."_".$search[$i];
    		global $$op;
     		$field_="field_".$i."_".$search[$i];
    		global $$field_;
     	}	*/	
		$my_search=new search();
		$this->query=$my_search->serialize_search();
		$this->human = $my_search->make_human_query();		
	}
	// Champ éditable
	$tpl_search_perso_form = str_replace('!!id!!', htmlentities($this->id,ENT_QUOTES,$charset), $tpl_search_perso_form);
	$tpl_search_perso_form = str_replace('!!name!!', htmlentities($this->name,ENT_QUOTES,$charset), $tpl_search_perso_form);
	$tpl_search_perso_form = str_replace('!!shortname!!', htmlentities($this->shortname,ENT_QUOTES,$charset), $tpl_search_perso_form);
	if($this->directlink) $checked= " checked='checked' ";
	$tpl_search_perso_form = str_replace('!!directlink!!', $checked, $tpl_search_perso_form);


	$tpl_search_perso_form = str_replace('!!query!!', htmlentities($this->query,ENT_QUOTES,$charset), $tpl_search_perso_form);
	$tpl_search_perso_form = str_replace('!!human!!', htmlentities($this->human,ENT_QUOTES,$charset), $tpl_search_perso_form);
	
	$action="./catalog.php?categ=serials&sub=collstate_update&serial_id=".$this->serial_id."&id=".$this->id;
	$tpl_search_perso_form = str_replace('!!action!!', $action, $tpl_search_perso_form);
	$tpl_search_perso_form = str_replace('!!delete!!', $link_delete, $tpl_search_perso_form);
	$tpl_search_perso_form = str_replace('!!libelle!!',htmlentities($libelle,ENT_QUOTES,$charset) , $tpl_search_perso_form);
	
	$link_annul = "onClick=\"unload_off();history.go(-1);\"";
	$tpl_search_perso_form = str_replace('!!annul!!', $link_annul, $tpl_search_perso_form);
	
	return $tpl_search_perso_form;	
}


// fonction générant le form de saisie 
function do_list() {
	global $tpl_search_perso_liste_tableau,$tpl_search_perso_liste_tableau_ligne;	
		
	// liste des lien de recherche directe
	$tpl_search_perso_liste_tableau = str_replace('!!preflink!!',$this->directlink_user , $tpl_search_perso_liste_tableau);
	$liste="";
	// pour toute les recherche de l'utilisateur
	for($i=0;$i<count($this->search_perso_user);$i++) {
		if ($i % 2) $pair_impair = "even"; else $pair_impair = "odd";
		
		//composer le formulaire de la recherche
		$my_search=new search();
		$my_search->unserialize_search($this->search_perso_user[$i]->query);
		$forms_search.= $my_search->make_hidden_search_form("./catalog.php?categ=search&mode=6","search_form".$this->search_perso_user[$i]->id);
		
		
        $td_javascript="  onmousedown=\"document.forms['search_form".$this->search_perso_user[$i]->id."'].submit();\" ";
        $tr_surbrillance = "onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='".$pair_impair."'\" ";

        $line = str_replace('!!td_javascript!!',$td_javascript , $tpl_search_perso_liste_tableau_ligne);
        $line = str_replace('!!tr_surbrillance!!',$tr_surbrillance , $line);
        $line = str_replace('!!pair_impair!!',$pair_impair , $line);

		$line =str_replace('!!id!!', $this->search_perso_user[$i]->id, $line);
		$line = str_replace('!!name!!', $this->search_perso_user[$i]->name, $line);
		$line = str_replace('!!human!!', $this->search_perso_user[$i]->human, $line);		
		$line = str_replace('!!shortname!!', $this->search_perso_user[$i]->shortname, $line);
		if($this->search_perso_user[$i]->directlink)
			$directlink="<img src='./images/tick.gif' border='0'  hspace='0' align='middle'  class='bouton-nav' value='=' />";
		else $directlink="";
		$line = str_replace('!!directlink!!', $directlink, $line);
		
		$liste.=$line;
	}
	$tpl_search_perso_liste_tableau = str_replace('!!lignes_tableau!!',$liste , $tpl_search_perso_liste_tableau);
	return $forms_search.$tpl_search_perso_liste_tableau;	
}

// suppression d'une collection ou de toute les collections d'un périodique
function delete() {
	global $dbh,$search_perso_user;
	
	if($this->id) {
		mysql_query("DELETE from search_perso WHERE search_id='".$this->id."' ", $dbh);
	}
	$search_perso_user=$this->get_link_user();	
}

} // fin définition classe
