<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: collection.class.php,v 1.33 2010-12-06 15:53:23 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

// définition de la classe de gestion des collections

if ( ! defined( 'COLLECTION_CLASS' ) ) {
  define( 'COLLECTION_CLASS', 1 );

require_once($class_path."/notice.class.php");
require_once("$class_path/aut_link.class.php");

class collection {

// ---------------------------------------------------------------
//		propriétés de la classe
// ---------------------------------------------------------------

var $id;		// MySQL id in table 'collections'
var $name;		// collection name
var $parent;	// MySQL id of parent publisher
var $editeur;	// name of parent publisher
var $editor_isbd; // isbd form of publisher
var $display;	// usable form for displaying	( _name_ (_editeur_) )
var $isbd_entry = ''; // isbd form
var $issn;		// ISSN of collection
var $isbd_entry_lien_gestion ; // lien sur le nom vers la gestion
var $collection_web;		// web de collection
var $collection_web_link;	// lien web de collection
// ---------------------------------------------------------------
//		collection($id) : constructeur
// ---------------------------------------------------------------
function collection($id=0) {
	if($id) {
		// on cherche à atteindre une notice existante
		$this->id = $id;
		$this->getData();
	} else {
		// la notice n'existe pas
		$this->id = 0;
		$this->getData();
	}
}

// ---------------------------------------------------------------
//		getData() : récupération infos collection
// ---------------------------------------------------------------
function getData() {
	global $dbh;
	if(!$this->id) {
		// pas d'identifiant. on retourne un tableau vide
		$this->id		= 0;
		$this->name		=	'';
		$this->parent	=	0;
		$this->editeur	=	'';
		$this->editor_isbd = '';
		$this->display	=	'';
		$this->issn		=	'';
		$this->collection_web	= '';
		$this->comment	= '';
	} else {
		$requete = "SELECT * FROM collections WHERE collection_id=$this->id LIMIT 1 ";
		$result = @mysql_query($requete, $dbh);
		if(mysql_num_rows($result)) {
			$temp = mysql_fetch_object($result);
			mysql_free_result($result);
			$this->id = $temp->collection_id;
			$this->name = $temp->collection_name;
			$this->parent = $temp->collection_parent;
			$this->issn = $temp->collection_issn;
			$this->collection_web	= $temp->collection_web;
			$this->comment	= $temp->collection_comment;
			if($temp->collection_web) 
				$this->collection_web_link = " <a href='$temp->collection_web' target=_blank><img src='./images/globe.gif' border=0 /></a>";
			else 
				$this->collection_web_link = "" ;
			
			$editeur = new editeur($temp->collection_parent);
			$this->editor_isbd = $editeur->isbd_entry;
			$this->issn ? $this->isbd_entry = $this->name.', ISSN '.$this->issn : $this->isbd_entry = $this->name;
			$this->editeur = $editeur->name;
			$this->display = $this->name.' ('.$this->editeur.')';
			// Ajoute un lien sur la fiche collection si l'utilisateur à accès aux autorités
			if (SESSrights & AUTORITES_AUTH) 
				$this->isbd_entry_lien_gestion = "<a href='./autorites.php?categ=collections&sub=collection_form&id=".$this->id."' class='lien_gestion'>".$this->name."</a>";
			else 
				$this->isbd_entry_lien_gestion = $this->name;
		} else {
			// pas de collection avec cette clé
			$this->id		=	0;
			$this->name		=	'';
			$this->parent	=	0;
			$this->editeur	=	'';
			$this->editor_isbd = '';
			$this->display	=	'';
			$this->issn		=	'';
			$this->collection_web = '';
			$this->collection_web_link = "" ;
			$this->comment = "" ;
		}
	}
}

// ---------------------------------------------------------------
//		delete() : suppression de la collection
// ---------------------------------------------------------------
function delete() {
	global $dbh;
	global $msg;

	if(!$this->id)
		// impossible d'accéder à cette notice de collection
		return $msg[406];

	// récupération du nombre de notices affectées
	$requete = "SELECT COUNT(1) FROM notices WHERE ";
	$requete .= "coll_id=$this->id";
	$res = mysql_query($requete, $dbh);
	$nbr_lignes = mysql_result($res, 0, 0);
	if(!$nbr_lignes) {
		// on regarde si la collection a des collections enfants 
		$requete = "SELECT COUNT(1) FROM sub_collections WHERE ";
		$requete .= "sub_coll_parent=".$this->id;
		$res = mysql_query($requete, $dbh);
		$nbr_lignes = mysql_result($res, 0, 0);
		if(!$nbr_lignes) {
			// effacement dans la table des collections
			$requete = "DELETE FROM collections WHERE collection_id=".$this->id;
			$result = mysql_query($requete, $dbh);
			// liens entre autorités
			$aut_link= new aut_link(AUT_TABLE_COLLECTIONS,$this->id);
			$aut_link->delete();
			return false;
		} else {
			// Cet collection a des sous-collections, impossible de la supprimer
			return '<strong>'.$this->display."</strong><br />${msg[408]}";
		}
	} else {
		// Cette collection est utilisé dans des notices, impossible de la supprimer
		return '<strong>'.$this->display."</strong><br />${msg[407]}";
	}
}

// ---------------------------------------------------------------
//		replace($by) : remplacement de la collection
// ---------------------------------------------------------------
function replace($by,$link_save=0) {

	global $msg;
	global $dbh;

	if(!$by) {
		// pas de valeur de remplacement !!!
		return "serious error occured, please contact admin...";
	}

	if (($this->id == $by) || (!$this->id))  {
		// impossible de remplacer une collection par elle-même
		return $msg[226];
	}
	// a) remplacement dans les notices
	// on obtient les infos de la nouvelle collection
	$n_collection = new collection($by);
	if(!$n_collection->parent) {
		// la nouvelle collection est foireuse
		return $msg[406];
	}
	
	$aut_link= new aut_link(AUT_TABLE_COLLECTIONS,$this->id);
	// "Conserver les liens entre autorités" est demandé
	if($link_save) {
		// liens entre autorités
		$aut_link->add_link_to(AUT_TABLE_COLLECTIONS,$by);		
	}
	$aut_link->delete();

	$requete = "UPDATE notices SET ed1_id=".$n_collection->parent.", coll_id=$by WHERE coll_id=".$this->id;
	$res = mysql_query($requete, $dbh);

	// b) remplacement dans la table des sous-collections
	$requete = "UPDATE collections SET sub_coll_parent=$by WHERE sub_coll_parent=".$this->id;
	$res = mysql_query($requete, $dbh);

	// c) suppression de la collection
	$requete = "DELETE FROM collections WHERE collection_id=".$this->id;
	$res = mysql_query($requete, $dbh);
	
	collection::update_index($by);
	return FALSE;

}

// ---------------------------------------------------------------
//		show_form : affichage du formulaire de saisie
// ---------------------------------------------------------------
function show_form() {

	global $msg;
	global $collection_form;
 	global $charset;

	if($this->id) {
		$action = "./autorites.php?categ=collections&sub=update&id=$this->id";
		$libelle = $msg[168];
		$button_remplace = "<input type='button' class='bouton' value='$msg[158]' ";
		$button_remplace .= "onclick='unload_off();document.location=\"./autorites.php?categ=collections&sub=replace&id=$this->id\"'>";

		$button_voir = "<input type='button' class='bouton' value='$msg[voir_notices_assoc]' ";
		$button_voir .= "onclick='unload_off();document.location=\"./catalog.php?categ=search&mode=2&etat=aut_search&aut_type=collection&aut_id=$this->id\"'>";

		$button_delete = "<input type='button' class='bouton' value='$msg[63]' ";
		$button_delete .= "onClick=\"confirm_delete();\">";
	} else {
		$action = './autorites.php?categ=collections&sub=update&id=';
		$libelle = $msg[167];
		$button_remplace = '';
		$button_delete ='';
	}
	
	$aut_link= new aut_link(AUT_TABLE_COLLECTIONS,$this->id);
	$collection_form = str_replace('<!-- aut_link -->', $aut_link->get_form('saisie_collection') , $collection_form);
	
	$collection_form = str_replace('!!id!!', 					$this->id, 											$collection_form);
	$collection_form = str_replace('!!libelle!!', 				$libelle, 											$collection_form);
	$collection_form = str_replace('!!action!!', 				$action, $collection_form);
 	$collection_form = str_replace('!!collection_nom!!', 		htmlentities($this->name,ENT_QUOTES, $charset), 	$collection_form);
 	$collection_form = str_replace('!!ed_libelle!!', 			htmlentities($this->editeur,ENT_QUOTES, $charset), 	$collection_form);
	$collection_form = str_replace('!!ed_id!!', 				$this->parent, 										$collection_form);
	$collection_form = str_replace('!!issn!!', 					$this->issn, 										$collection_form);
	$collection_form = str_replace('!!delete!!', 				$button_delete, 									$collection_form);
	$collection_form = str_replace('!!remplace!!', 				$button_remplace, 									$collection_form);
	$collection_form = str_replace('!!voir_notices!!', 			$button_voir, 										$collection_form);
	$collection_form = str_replace('!!collection_web!!',		htmlentities($this->collection_web,ENT_QUOTES, $charset),	$collection_form);
	$collection_form = str_replace('!!comment!!',				htmlentities($this->comment,ENT_QUOTES, $charset),	$collection_form);
	// pour retour à la bonne page en gestion d'autorités
	// &user_input=".rawurlencode(stripslashes($user_input))."&nbr_lignes=$nbr_lignes&page=$page
	global $user_input, $nbr_lignes, $page ;
	$collection_form = str_replace('!!user_input_url!!',		rawurlencode(stripslashes($user_input)),			$collection_form);
	$collection_form = str_replace('!!user_input!!',			htmlentities($user_input,ENT_QUOTES, $charset),		$collection_form);
	$collection_form = str_replace('!!nbr_lignes!!',			$nbr_lignes,										$collection_form);
	$collection_form = str_replace('!!page!!',					$page,												$collection_form);
	print $collection_form;
}

// ---------------------------------------------------------------
//		replace_form : affichage du formulaire de remplacement
// ---------------------------------------------------------------
function replace_form()	{
	global $collection_replace_form;
	global $msg;
	global $include_path;

	if(!$this->id || !$this->name) {
		require_once("$include_path/user_error.inc.php"); 
		error_message($msg[161], $msg[162], 1, './autorites.php?categ=collections&sub=&id=');
		return false;
	}

	$collection_replace_form=str_replace('!!id!!', $this->id, $collection_replace_form);
	$collection_replace_form=str_replace('!!coll_name!!', $this->name, $collection_replace_form);
	$collection_replace_form=str_replace('!!coll_editeur!!', $this->editeur, $collection_replace_form);
	print $collection_replace_form;
}


// ---------------------------------------------------------------
//		update($value) : mise à jour de la collection
// ---------------------------------------------------------------
function update($value) {

	global $dbh;
	global $msg;
	global $include_path;
	
	// nettoyage des valeurs en entrée
	$value['name'] = clean_string($value['name']);
	$value['issn'] = clean_string($value['issn']);

	if ((!$value['name']) || (!$value['parent'])) 
		return false;
	
	// construction de la requête
	$requete = "SET collection_name='$value[name]', ";
	$requete .= "collection_parent='$value[parent]', ";
	$requete .= "collection_issn='$value[issn]', ";
	$requete .= "collection_web='$value[collection_web]', ";
	$requete .= "collection_comment='$value[comment]', ";
	$requete .= "index_coll=' ".strip_empty_words($value[name])." ".strip_empty_words($value["issn"])." '";

	if($this->id) {
		// update
		$requete = 'UPDATE collections '.$requete;
		$requete .= ' WHERE collection_id='.$this->id.' ;';
		if(mysql_query($requete, $dbh)) {
			$requete = "update notices set ed1_id='".$value[parent]."' WHERE coll_id='".$this->id."' ";
			$res = mysql_query($requete, $dbh) ;
			collection::update_index($this->id);
			// liens entre autorités
			$aut_link= new aut_link(AUT_TABLE_COLLECTIONS,$this->id);
			$aut_link->save_form();
			return TRUE;
		} else {
			require_once("$include_path/user_error.inc.php");
			warning($msg[167], $msg[169]);
			return FALSE;
		}
	} else {
		// création : s'assurer que la collection n'existe pas déjà
		$dummy = "SELECT * FROM collections WHERE collection_name REGEXP '^${value[name]}$' AND collection_parent='$value[parent]' LIMIT 1 ";
		$check = mysql_query($dummy, $dbh);
		if(mysql_num_rows($check)) {
			require_once("$include_path/user_error.inc.php");
			warning($msg[167], $msg[171]);
			return FALSE;
		}
		$requete = 'INSERT INTO collections '.$requete.';';
		if(mysql_query($requete, $dbh)) {
			$this->id=mysql_insert_id();
			// liens entre autorités
			$aut_link= new aut_link(AUT_TABLE_COLLECTIONS,$this->id);
			$aut_link->save_form();
			return TRUE;
		} else {
			require_once("$include_path/user_error.inc.php");
			warning($msg[167], $msg[170]);
			return FALSE;
		}
	}
}

// ---------------------------------------------------------------
//		import() : import d'une collection
// ---------------------------------------------------------------

// fonction d'import de collection (membre de la classe 'collection');

function import($data) {

	// cette méthode prend en entrée un tableau constitué des informations éditeurs suivantes :
	//	$data['name'] 	Nom de la collection
	//	$data['parent']	id de l'éditeur parent de la collection
	//	$data['issn']	numéro ISSN de la collection

	global $dbh;

	// check sur le type de  la variable passée en paramètre
	if(!sizeof($data) || !is_array($data)) {
		// si ce n'est pas un tableau ou un tableau vide, on retourne 0
		return 0;
	}

	// check sur les éléments du tableau (data['name'] est requis).
	
	$long_maxi_name = mysql_field_len(mysql_query("SELECT collection_name FROM collections limit 1"),0);
	$data['name'] = rtrim(substr(preg_replace('/\[|\]/', '', rtrim(ltrim($data['name']))),0,$long_maxi_name));


	if(($data['name']=="") || ($data['parent']==0)) /* il nous faut impérativement un éditeur */
		return 0;


	// préparation de la requête
	$key0 = addslashes($data['name']);
	$key1 = $data['parent'];
	$key2 = addslashes($data['issn']);
	
	/* vérification que l'éditeur existe bien ! */
	$query = "SELECT ed_id FROM publishers WHERE ed_id='${key1}' LIMIT 1 ";
	$result = @mysql_query($query, $dbh);
	if(!$result) 
		die("can't SELECT publishers ".$query);
	if (mysql_num_rows($result)==0) 
		return 0;

	/* vérification que la collection existe */
	$query = "SELECT collection_id FROM collections WHERE collection_name='${key0}' AND collection_parent='${key1}' LIMIT 1 ";
	$result = @mysql_query($query, $dbh);
	if(!$result) die("can't SELECT collections ".$query);
	$collection  = mysql_fetch_object($result);

	/* la collection existe, on retourne l'ID */
	if($collection->collection_id)
		return $collection->collection_id;

	// id non-récupérée, il faut créer la forme.
	$query = "INSERT INTO collections SET collection_name='$key0', ";
	$query .= "collection_parent='$key1', ";
	$query .= "collection_issn='$key2', ";
	$query .= "index_coll=' ".strip_empty_words($key0)." ".strip_empty_words($key2)." ' ";
	$result = @mysql_query($query, $dbh);
	if(!$result) die("can't INSERT into database");

	return mysql_insert_id($dbh);
}
	
// ---------------------------------------------------------------
//		search_form() : affichage du form de recherche
// ---------------------------------------------------------------

function search_form() {
	global $user_query, $user_input;
	global $msg,$charset;

	$user_query = str_replace ('!!user_query_title!!', $msg[357]." : ".$msg[136] , $user_query);
	$user_query = str_replace ('!!action!!', './autorites.php?categ=collections&sub=reach&id=', $user_query);
	$user_query = str_replace ('!!add_auth_msg!!', $msg[163] , $user_query);
	$user_query = str_replace ('!!add_auth_act!!', './autorites.php?categ=collections&sub=collection_form', $user_query);
	$user_query = str_replace ('<!-- lien_derniers -->', "<a href='./autorites.php?categ=collections&sub=collection_last'>$msg[1312]</a>", $user_query);
	$user_query = str_replace("!!user_input!!",htmlentities(stripslashes($user_input),ENT_QUOTES, $charset),$user_query);
	print pmb_bidi($user_query) ;
}

//---------------------------------------------------------------
// update_index($id) : maj des n-uplets la table notice_global_index en rapport avec cet collection	
//---------------------------------------------------------------
function update_index($id) {
	global $dbh;
	// On cherche tous les n-uplet de la table notice correspondant à cet auteur.
	$found = mysql_query("select distinct notice_id from notices where coll_id='".$id."'",$dbh);
	// Pour chaque n-uplet trouvés on met a jour la table notice_global_index avec l'auteur modifié :
	while($mesNotices = mysql_fetch_object($found)) {
		$notice_id = $mesNotices->notice_id;
		notice::majNoticesGlobalIndex($notice_id);
		notice::majNoticesMotsGlobalIndex($notice_id,'collection');
	}
}
} # fin de définition de la classe collection

} # fin de délaration
