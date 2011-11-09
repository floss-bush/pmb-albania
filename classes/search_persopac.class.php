<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: search_persopac.class.php,v 1.11.2.3 2011-06-14 14:02:31 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

// classes de gestion des recherches personnalisées

// inclusions principales
require_once("$include_path/templates/search_persopac.tpl.php");
require_once("$class_path/search.class.php");
require_once("$class_path/translation.class.php");
require_once("$class_path/XMLlist.class.php");
class search_persopac {
	var $name="";
	var $shortname="";
	var $query="";
	var $human="";
	var $directlink="";
	var $limitsearch="";
	var $empr_categ_restrict = array();	

// constructeur
function search_persopac($id=0) {
	global $search_persopac_link;
	// si id, allez chercher les infos dans la base
	if($id) {
		$this->id = $id;
		$this->fetch_data();
	}	
	if(!$search_persopac_link)$search_persopac_link=$this->get_link();
	return $this->id;
}
    
// récupération des infos en base
function fetch_data() {
	global $dbh;
	
	$myQuery = mysql_query("SELECT * FROM search_persopac WHERE search_id='".$this->id."' LIMIT 1", $dbh);
	$myreq= mysql_fetch_object($myQuery);
	
	$this->name=$myreq->search_name;
	$this->shortname=$myreq->search_shortname;
	$this->query=$myreq->search_query;
	$this->human=$myreq->search_human;
	$this->directlink=$myreq->search_directlink;
	$this->limitsearch=$myreq->search_limitsearch;
	$this->empr_categ_restrict = array();
	
	$req  = "select id_categ_empr from search_persopac_empr_categ where id_search_persopac = ".$this->id;
	$res = mysql_query($req);
	if(mysql_num_rows($res)){
		while ($obj = mysql_fetch_object($res)){
			$this->empr_categ_restrict[]=$obj->id_categ_empr;
		}
	}
}

function get_link() {
	global $dbh;	
	$myQuery = mysql_query("SELECT * FROM search_persopac order by search_name ", $dbh);
	$this->search_persopac_list=array();
	$link="";
	if(mysql_num_rows($myQuery)){
		$i=0;
		while(($r=mysql_fetch_object($myQuery))) {		
			$this->search_persopac_list[$i]->id=$r->search_id;
			$this->search_persopac_list[$i]->name=$r->search_name;
			$this->search_persopac_list[$i]->shortname=$r->search_shortname;
			$this->search_persopac_list[$i]->query=$r->search_query;
			$this->search_persopac_list[$i]->human=$r->search_human;
			$this->search_persopac_list[$i]->directlink=$r->search_directlink;	
			$this->search_persopac_list[$i]->limitsearch=$r->search_limitsearch;							
			$i++;			
		}	
	}
	return true;
}
// fonction de mise à jour ou de création 
function update($value) {	
	global $dbh,$msg,$search_persopac_link;
	$fields="";
	foreach($value as $key => $val) {
		if($key != "search_empr_restrict"){
			if($fields) $fields.=","; 
			$fields.=" $key='$val' ";	
		}
	}	
	if($this->id) {
		// modif
		$no_erreur=mysql_query("UPDATE search_persopac SET $fields WHERE search_id=".$this->id, $dbh);	
		if(!$no_erreur) {
			error_message($msg["search_persopac_form_edit"], $msg["search_persopac_form_add_error"],1);	
			exit;
		}
		
	} else {
		// create
		$no_erreur=mysql_query("INSERT INTO search_persopac SET $fields ", $dbh);
		$this->id = mysql_insert_id($dbh);
		if(!$no_erreur) {
			error_message($msg["search_persopac_form_add"], $msg["search_persopac_form_add_error"],1);
			exit;
		}
	}	
	//on s'occupe maintenant de la restriction par caégories de lecteur
	$req = "delete from search_persopac_empr_categ where id_search_persopac = ".$this->id;
	mysql_query($req);
	if(count($value->search_empr_restrict)>0){
		foreach($value->search_empr_restrict as $id_categ_empr){
			$req= "insert into search_persopac_empr_categ set id_search_persopac=".$this->id.", id_categ_empr=".$id_categ_empr;
			mysql_query($req);
		}
	}
	
	// rafraischissement des données
	$this->fetch_data();
	$search_persopac_link=$this->get_link();
	return $this->id;
}

function update_from_form() {
	global $name,$shortname,$query,$human,$directlink,$limitsearch,$thesaurus_liste_trad,$empr_restrict;
	
	$value->search_name=$name;
	$value->search_shortname=$shortname;
	$value->search_query=$query;
	$value->search_human=$human;
	$value->search_directlink=$directlink;
	$value->search_limitsearch=$limitsearch;
	$value->search_empr_restrict=$empr_restrict;
	
	$this->update($value); 	
	$trans= new translation($this->id,"search_persopac","search_name",$thesaurus_liste_trad);	
	$trans->update("name");
	$trans= new translation($this->id,"search_persopac","search_shortname",$thesaurus_liste_trad);	
	$trans->update("shortname");	
}

// fonction générant le form de saisie 
function do_form() {
	global $msg,$tpl_search_persopac_form,$charset;	
	global $thesaurus_liste_trad;
	// titre formulaire
	if($this->id) {
		$libelle=$msg["search_persopac_form_edit"];
		$link_delete="<input type='button' class='bouton' value='".$msg[63]."' onClick=\"confirm_delete();\" />";
		
	} else {
		$libelle=$msg["search_persopac_form_add"];
		$link_delete="";

		$my_search=new search();
		$this->query=$my_search->serialize_search();
		$this->human = $my_search->make_human_query();		
	}
	// Champ éditable
	$tpl_search_persopac_form = str_replace('!!id!!', htmlentities($this->id,ENT_QUOTES,$charset), $tpl_search_persopac_form);
	
	$trans= new translation($this->id,"search_persopac","search_name",$thesaurus_liste_trad);
	$field_name=$trans->get_form($msg["search_persopac_form_name"],"form_nom","name",$this->name,"saisie-80em");	
	$tpl_search_persopac_form = str_replace('!!name!!', $field_name, $tpl_search_persopac_form);

	$trans= new translation($this->id,"search_persopac","search_shortname",$thesaurus_liste_trad);
	$field_name=$trans->get_form($msg["search_persopac_form_shortname"],"shortname","shortname",$this->shortname,"saisie-80em");		
	$tpl_search_persopac_form = str_replace('!!shortname!!', $field_name, $tpl_search_persopac_form);
	$checked='';
	if($this->directlink) $checked= " checked='checked' ";
	$tpl_search_persopac_form = str_replace('!!directlink!!', $checked, $tpl_search_persopac_form);
	$checked='';
	if($this->limitsearch) $checked= " checked='checked' ";
	$tpl_search_persopac_form = str_replace('!!limitsearch!!', $checked, $tpl_search_persopac_form);
	
	$tpl_search_persopac_form = str_replace('!!query!!', htmlentities($this->query,ENT_QUOTES,$charset), $tpl_search_persopac_form);
	$tpl_search_persopac_form = str_replace('!!human!!', htmlentities($this->human,ENT_QUOTES,$charset), $tpl_search_persopac_form);
	
	$action="./admin.php?categ=opac&sub=search_persopac&section=liste&action=collstate_update&serial_id=".$this->serial_id."&id=".$this->id;
	$tpl_search_persopac_form = str_replace('!!action!!', $action, $tpl_search_persopac_form);
	$tpl_search_persopac_form = str_replace('!!delete!!', $link_delete, $tpl_search_persopac_form);
	$tpl_search_persopac_form = str_replace('!!libelle!!',htmlentities($libelle,ENT_QUOTES,$charset) , $tpl_search_persopac_form);
	
	$link_annul = "onClick=\"unload_off();history.go(-1);\"";
	$tpl_search_persopac_form = str_replace('!!annul!!', $link_annul, $tpl_search_persopac_form);
	
	//restriction aux catégories de lecteur
	$requete = "SELECT id_categ_empr, libelle FROM empr_categ ORDER BY libelle ";
	$res = mysql_query($requete);
	if(mysql_num_rows($res)>0){
		$categ = "
		<label for='empr_restrict'>".$msg['search_perso_form_user_restrict']."</label><br />
		<select id='empr_restrict' name='empr_restrict[]' multiple>";
		while($obj = mysql_fetch_object($res)){
			$categ.="
			<option value='".$obj->id_categ_empr."' ".(in_array($obj->id_categ_empr,$this->empr_categ_restrict) ? "selected=selected" : "") .">".$obj->libelle."</option>";
		}
		$categ.="
		</select>";
	}else $categ = "";
	$tpl_search_persopac_form = str_replace('!!categorie!!', $categ, $tpl_search_persopac_form);
	
	return $tpl_search_persopac_form;	
}


function do_list() {
	global $tpl_search_persopac_liste_tableau,$tpl_search_persopac_liste_tableau_ligne;	
		
	// liste des lien de recherche directe
	$liste="";
	// pour toute les recherche de l'utilisateur
	for($i=0;$i<count($this->search_persopac_list);$i++) {
		if ($i % 2) $pair_impair = "even"; else $pair_impair = "odd";
/*		
		//composer le formulaire de la recherche
		$my_search=new search();
		$my_search->unserialize_search($this->search_persopac_list[$i]->query);
		$forms_search.= $my_search->make_hidden_search_form("./catalog.php?categ=search&mode=6","search_form".$this->search_persopac_list[$i]->id);
*/		
		
        $td_javascript=" ";
        $tr_surbrillance = "onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='".$pair_impair."'\" ";

        $line = str_replace('!!td_javascript!!',$td_javascript , $tpl_search_persopac_liste_tableau_ligne);
        $line = str_replace('!!tr_surbrillance!!',$tr_surbrillance , $line);
        $line = str_replace('!!pair_impair!!',$pair_impair , $line);

		$line =str_replace('!!id!!', $this->search_persopac_list[$i]->id, $line);
		$line = str_replace('!!name!!', $this->search_persopac_list[$i]->name, $line);
		$line = str_replace('!!human!!', $this->search_persopac_list[$i]->human, $line);		
		$line = str_replace('!!shortname!!', $this->search_persopac_list[$i]->shortname, $line);
		if($this->search_persopac_list[$i]->directlink)
			$directlink="<img src='./images/tick.gif' border='0'  hspace='0' align='middle'  class='bouton-nav' value='=' />";
		else $directlink="";
		$line = str_replace('!!directlink!!', $directlink, $line);
		
		$liste.=$line;
	}
	$tpl_search_persopac_liste_tableau = str_replace('!!lignes_tableau!!',$liste , $tpl_search_persopac_liste_tableau);
	return $forms_search.$tpl_search_persopac_liste_tableau;	
}


function delete() {
	global $dbh,$search_persopac_link;
	
	if($this->id) {
		mysql_query("DELETE from search_persopac WHERE search_id='".$this->id."' ", $dbh);
		mysql_query("delete from search_persopac_empr_categ where id_search_persopac = ".$this->id);
	}
	$search_persopac_link=$this->get_link();	
}
function add_search(){
	global $include_path,$pmb_opac_url;
	global $lang,$msg,$base_path;

	$save_msg=$msg;
	// Recherche du fichier lang de l'opac
	$url=$pmb_opac_url."includes/messages/$lang.xml";
	$fichier_xml=$base_path."/temp/opac_lang.xml";
	
	$this->curl_load_file($url,$fichier_xml);	
	$messages = new XMLlist("$base_path/temp/opac_lang.xml", 0);
	$messages->analyser();
	$msg = $messages->table;
	
	$url=$pmb_opac_url."includes/search_queries/search_fields.xml";
	$fichier_xml="$base_path/temp/search_fields_opac.xml";
	
	$this->curl_load_file($url,$fichier_xml);
	$my_search=new search(false,"search_fields_opac","$base_path/temp/");
	$form= $my_search->show_form("./admin.php?categ=opac&sub=search_persopac&section=liste&action=build",
		"","","./admin.php?categ=opac&sub=search_persopac&section=liste&action=form");
	print $form;	
	$msg=$save_msg;
}
function continu_search(){
	
	$my_search=new search(false,"search_fields_opac");
	$form= $my_search->show_form("./admin.php?categ=opac&sub=search_persopac&section=liste&action=build",
		"","","./admin.php?categ=opac&sub=search_persopac&section=liste&action=form");
	print $form;
}

function curl_load_file($url, $filename) {
	global $opac_curl_available ;
	if (!$opac_curl_available) die("PHP Curl must be available");
	//Calcul du subst
	$url_subst=str_replace(".xml","_subst.xml",$url);
    $curl = curl_init();
    curl_setopt ($curl, CURLOPT_URL, $url_subst);
    $filename_subst=str_replace(".xml","_subst.xml",$filename);
 	$fp = fopen($filename_subst, "w+");    
	curl_setopt($curl, CURLOPT_FILE, $fp);
	
	if(curl_exec ($curl)) {
		fclose($fp);
		if (curl_getinfo($curl,CURLINFO_HTTP_CODE)=="404") {
			@unlink($filename_subst);
		}
		curl_setopt ($curl, CURLOPT_URL, $url);
		$fp = fopen($filename, "w+"); 
		curl_setopt($curl, CURLOPT_FILE, $fp);
	   	if(!curl_exec ($curl)) die("Le paramètre opac_url ( dans paramètres généraux ) doit avoir l'url complet de l'Opac: http:// ... ");
	} else die("Le paramètre opac_url ( dans paramètres généraux ) doit avoir l'url complet de l'Opac: http:// ... ");
    curl_close ($curl);
    fclose($fp);
}

} // fin définition classe
