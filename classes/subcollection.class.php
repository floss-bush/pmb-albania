<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: subcollection.class.php,v 1.33 2010-12-06 15:53:23 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

// définition de la classe de gestion des 'sous-collections'

if ( ! defined( 'SUB_COLLECTION_CLASS' ) ) {
  define( 'SUB_COLLECTION_CLASS', 1 );

require_once($class_path."/notice.class.php");
require_once("$class_path/aut_link.class.php");

class subcollection {

// ---------------------------------------------------------------
//		propriétés de la classe
// ---------------------------------------------------------------
var $id;				// MySQL id in table 'collections'
var $name;				// collection name
var $parent;			// MySQL id of parent collection
var $parent_libelle;	// name of parent collection
var $editeur;			// MySQL id of publisher
var $editeur_libelle;	// name of parent publisher
var $editor_isbd;		// isbd form of publisher
var $display;			// usable form for displaying	( _collection_. _name_ (_editeur_) )
var $isbd_entry;		// ISBD form ( _collection_. _name_ )
var $issn;				// ISSN of sub collection
var $isbd_entry_lien_gestion ; // lien sur le nom vers la gestion
var $subcollection_web;			// web de sous-collection
var $subcollection_web_link;	// lien web de sous-collection

// ---------------------------------------------------------------
//		subcollection($id) : constructeur
// ---------------------------------------------------------------
function subcollection($id=0) {
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
//		getData() : récupération infos sous collection
// ---------------------------------------------------------------
function getData() {
	global $dbh;
	if(!$this->id) {
		// pas d'identifiant. on retourne un tableau vide
		$this->id = 0;
		$this->name				=	'';
		$this->parent			=	0;
		$this->parent_libelle	=	'';
		$this->editeur			=	0;
		$this->editeur_libelle	=	'';
		$this->display			=	'';
		$this->isbd_entry		=	'';
		$this->issn				=	'';
		$this->subcollection_web	= '';
		$this->subcollection_web_link = "" ;
		$this->comment = "" ;
	} else {
		$requete = "SELECT * FROM sub_collections WHERE sub_coll_id=$this->id LIMIT 1 ";
		$result = @mysql_query($requete, $dbh);
		if(mysql_num_rows($result)) {
			$temp = mysql_fetch_object($result);
			mysql_free_result($result);
			$this->id= $temp->sub_coll_id;
			$this->name= $temp->sub_coll_name;
			$this->parent= $temp->sub_coll_parent;
			$this->issn= $temp->sub_coll_issn;
			$this->subcollection_web	= $temp->subcollection_web;
			$this->comment	= $temp->subcollection_comment;
			if($temp->subcollection_web) $this->subcollection_web_link = " <a href='$temp->subcollection_web' target=_blank><img src='./images/globe.gif' border=0 /></a>";
			else $this->subcollection_web_link = "" ;
			$parent = new collection($temp->sub_coll_parent);
			$this->parent_libelle = $parent->name;
			$parent_libelle_lien_gestion = $parent->isbd_entry_lien_gestion ;
			$this->editeur = $parent->parent;
			$editeur = new editeur($parent->parent);
			$this->editeur_libelle = $editeur->name;
			$this->editor_isbd = $editeur->isbd_entry;
			$this->issn ? $this->isbd_entry = $this->parent_libelle.'.&nbsp;'.$this->name.', ISSN '.$this->issn : $this->isbd_entry = $this->parent_libelle.'.&nbsp;'.$this->name ;
			$this->display = $this->parent_libelle.'.&nbsp;'.$this->name.'&nbsp;('.$this->editeur_libelle.')';
			// Ajoute un lien sur la fiche sous-collection si l'utilisateur à accès aux autorités
			if (SESSrights & AUTORITES_AUTH) {
				if ($this->issn) 
					$lien_lib = $this->name.', ISSN '.$this->issn ;
				else 
					$lien_lib = $this->name ;
				$this->isbd_entry_lien_gestion = $parent_libelle_lien_gestion.".&nbsp;<a href='./autorites.php?categ=souscollections&sub=collection_form&id=".$this->id."' class='lien_gestion'>".$lien_lib."</a>";
			} else 
				$this->isbd_entry_lien_gestion = $this->isbd_entry;
				
		} else {
			// pas de sous-collection avec cette clé
			$this->id = 0;
			$this->name				=	'';
			$this->parent			=	0;
			$this->parent_libelle	=	'';
			$this->editeur			=	0;
			$this->editeur_libelle	=	'';
			$this->display			=	'';
			$this->isbd_entry		=	'';
			$this->issn				=	'';
			$this->subcollection_web = '';
			$this->comment = '';
			$this->subcollection_web_link = "" ;
		}
	}
}

// ---------------------------------------------------------------
//		delete() : suppression de la sous collection
// ---------------------------------------------------------------
function delete() {
	global $dbh;
	global $msg;
	
	if(!$this->id)
		// impossible d'accéder à cette notice de sous-collection
		return $msg[406];

	// récupération du nombre de notices affectées
	$requete = "SELECT COUNT(1) FROM notices WHERE ";
	$requete .= "subcoll_id=".$this->id;
	$res = mysql_query($requete, $dbh);
	$nbr_lignes = mysql_result($res, 0, 0);
	if(!$nbr_lignes) {
		// sous collection non-utilisée dans des notices : Suppression OK
		// effacement dans la table des collections
		$requete = "DELETE FROM sub_collections WHERE sub_coll_id=".$this->id;
		$result = mysql_query($requete, $dbh);
		// liens entre autorités
		$aut_link= new aut_link(AUT_TABLE_SUB_COLLECTIONS,$this->id);
		$aut_link->delete();
		return false;
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

	$n_collection = new subcollection($by);
	if(!$n_collection->parent) {
		// la nouvelle collection est foireuse
		return $msg[406];
	}
	
	$aut_link= new aut_link(AUT_TABLE_SUB_COLLECTIONS,$this->id);
	// "Conserver les liens entre autorités" est demandé
	if($link_save) {
		// liens entre autorités
		$aut_link->add_link_to(AUT_TABLE_SUB_COLLECTIONS,$by);		
	}
	$aut_link->delete();
	
	$requete = "UPDATE notices SET ed1_id=".$n_collection->editeur;
	$requete .= ", coll_id=".$n_collection->parent;
	$requete .= ", subcoll_id=$by WHERE subcoll_id=".$this->id;
	$res = mysql_query($requete, $dbh);

	// b) suppression de la collection
	$requete = "DELETE FROM sub_collections WHERE sub_coll_id=".$this->id;
	$res = mysql_query($requete, $dbh);

	subcollection::update_index($by);

	return FALSE;
}


// ---------------------------------------------------------------
//		show_form : affichage du formulaire de saisie
// ---------------------------------------------------------------
function show_form() {

	global $msg;
	global $sub_collection_form;
	global $charset;

	if($this->id) {
		$action = "./autorites.php?categ=souscollections&sub=update&id=$this->id";
		$libelle = $msg[178];
		$button_replace = "<input type='button' class='bouton' value='$msg[158]' ";
		$button_replace .= "onClick=\"unload_off();document.location='./autorites.php?categ=souscollections&sub=replace&id=$this->id';\">";

		$button_voir = "<input type='button' class='bouton' value='$msg[voir_notices_assoc]' ";
		$button_voir .= "onclick='unload_off();document.location=\"./catalog.php?categ=search&mode=2&etat=aut_search&aut_type=subcoll&aut_id=$this->id\"'>";

		$button_delete = "<input type='button' class='bouton' value='$msg[63]' ";
		$button_delete .= "onClick=\"confirm_delete();\">";
	} else {
		$action = './autorites.php?categ=souscollections&sub=update&id=';
		$libelle = $msg[177];
		$button_replace = '';
		$button_delete ='';
	}
	$aut_link= new aut_link(AUT_TABLE_SUB_COLLECTIONS,$this->id);
	$sub_collection_form = str_replace('<!-- aut_link -->', $aut_link->get_form('saisie_sub_collection') , $sub_collection_form);
	
	$sub_collection_form = str_replace('!!id!!', $this->id, $sub_collection_form);
	$sub_collection_form = str_replace('!!libelle!!', $libelle, $sub_collection_form);
	$sub_collection_form = str_replace('!!action!!', $action, $sub_collection_form);
	$sub_collection_form = str_replace('!!collection_nom!!', htmlentities($this->name,ENT_QUOTES, $charset), $sub_collection_form);
	$sub_collection_form = str_replace('!!coll_id!!', $this->parent, $sub_collection_form);
	$sub_collection_form = str_replace('!!coll_libelle!!', htmlentities($this->parent_libelle,ENT_QUOTES, $charset), $sub_collection_form);
	$sub_collection_form = str_replace('!!ed_libelle!!', htmlentities($this->editeur_libelle,ENT_QUOTES, $charset), $sub_collection_form);
	$sub_collection_form = str_replace('!!ed_id!!', $this->editeur, $sub_collection_form);
	$sub_collection_form = str_replace('!!issn!!', $this->issn, $sub_collection_form);
	$sub_collection_form = str_replace('!!delete!!', $button_delete, $sub_collection_form);
	$sub_collection_form = str_replace('!!remplace!!', $button_replace, $sub_collection_form);
	$sub_collection_form = str_replace('!!voir_notices!!', $button_voir, $sub_collection_form);
	$sub_collection_form = str_replace('!!subcollection_web!!',		htmlentities($this->subcollection_web,ENT_QUOTES, $charset),	$sub_collection_form);
	$sub_collection_form = str_replace('!!comment!!',		htmlentities($this->comment,ENT_QUOTES, $charset),	$sub_collection_form);
	// pour retour à la bonne page en gestion d'autorités
	// &user_input=".rawurlencode(stripslashes($user_input))."&nbr_lignes=$nbr_lignes&page=$page
	global $user_input, $nbr_lignes, $page ;
	$sub_collection_form = str_replace('!!user_input_url!!',		rawurlencode(stripslashes($user_input)),			$sub_collection_form);
	$sub_collection_form = str_replace('!!user_input!!',			htmlentities($user_input,ENT_QUOTES, $charset),		$sub_collection_form);
	$sub_collection_form = str_replace('!!nbr_lignes!!',			$nbr_lignes,										$sub_collection_form);
	$sub_collection_form = str_replace('!!page!!',					$page,												$sub_collection_form);
	print $sub_collection_form;
}

// ---------------------------------------------------------------
//		replace_form : affichage du formulaire de remplacement
// ---------------------------------------------------------------
function replace_form() {
	global $sub_coll_rep_form;
	global $msg;
	global $include_path;
	
	if(!$this->id || !$this->name) {
		require_once("$include_path/user_error.inc.php");
		error_message($msg[161], $msg[162], 1, './autorites.php?categ=collections&sub=&id=');
		return false;
	}

	$sub_coll_rep_form=str_replace('!!id!!', $this->id, $sub_coll_rep_form);
	$sub_coll_rep_form=str_replace('!!subcoll_name!!', $this->display, $sub_coll_rep_form);
	print $sub_coll_rep_form;
}


// ---------------------------------------------------------------
//		?? update($value) : mise à jour de la collection
// ---------------------------------------------------------------
function update($value) {

	global $dbh;
	global $msg;
	global $include_path;
	
	if(!$value['name'] || !$value['parent'])
		return false;

	// nettoyage des valeurs en entrée
	$value['name'] = clean_string($value['name']);

	// construction de la requête
	$requete = "SET sub_coll_name='$value[name]', ";
	$requete .= "sub_coll_parent='$value[parent]', ";
	$requete .= "sub_coll_issn='$value[issn]', ";
	$requete .= "subcollection_web='$value[subcollection_web]', ";
	$requete .= "subcollection_comment='$value[comment]', ";
	$requete .= "index_sub_coll=' ".strip_empty_words($value[name])." ".strip_empty_words($value["issn"])." '";

	if($this->id) {
		// update
		$requete = 'UPDATE sub_collections '.$requete;
		$requete .= ' WHERE sub_coll_id='.$this->id.' ';
		if(mysql_query($requete, $dbh)) {
			$requete = "select collection_parent from collections WHERE collection_id='".$value[parent]."' ";
			$res = mysql_query($requete, $dbh) ;
			$ed_parent = mysql_result($res, 0, 0);
			$requete = "update notices set ed1_id='$ed_parent', coll_id='".$value[parent]."' WHERE subcoll_id='".$this->id."' ";
			$res = mysql_query($requete, $dbh) ;
			subcollection::update_index($this->id);
			$aut_link= new aut_link(AUT_TABLE_SUB_COLLECTIONS,$this->id);
			$aut_link->save_form();
			return TRUE;
		} else {
			require_once("$include_path/user_error.inc.php");
			warning($msg[178], $msg[182]);
			return FALSE;
		}
	} else {
		// création : s'assurer que la collection n'existe pas déjà
		$dummy = "SELECT * FROM sub_collections WHERE sub_coll_name REGEXP '^${value[name]}$' AND sub_coll_parent='$value[parent]' LIMIT 1 ";
		$check = mysql_query($dummy, $dbh);
		if(mysql_num_rows($check)) {
			require_once("$include_path/user_error.inc.php");
			warning($msg[177], $msg[219]);
			return FALSE;
		}
		$requete = 'INSERT INTO sub_collections '.$requete.';';
		if(mysql_query($requete, $dbh)) {
			$this->id=mysql_insert_id();
			$aut_link= new aut_link(AUT_TABLE_SUB_COLLECTIONS,$this->id);
			$aut_link->save_form();
			return TRUE;
		} else {
			require_once("$include_path/user_error.inc.php");
			warning($msg[177], $msg[182]);
			return FALSE;
		}
	}
}

// ---------------------------------------------------------------
//		import() : import d'une sous-collection
// ---------------------------------------------------------------
// fonction d'import de sous-collection (membre de la classe 'subcollection');
function import($data) {

	// cette méthode prend en entrée un tableau constitué des informations éditeurs suivantes :
	//	$data['name'] 	Nom de la collection
	//	$data['coll_parent']	id de l'éditeur parent de la collection
	//	$data['issn']	numéro ISSN de la collection

	global $dbh;

	// check sur le type de  la variable passée en paramètre
	if(!sizeof($data) || !is_array($data)) {
		// si ce n'est pas un tableau ou un tableau vide, on retourne 0
		return 0;
	}

	// check sur les éléments du tableau (data['name'] est requis).
	
	$long_maxi_name = mysql_field_len(mysql_query("SELECT sub_coll_name FROM sub_collections limit 1"),0);
	$data['name'] = rtrim(substr(preg_replace('/\[|\]/', '', rtrim(ltrim($data['name']))),0,$long_maxi_name));

	if($data['name']=="" || $data['coll_parent']==0) /* il nous faut impérativement une collection parente */
		return 0;

	// préparation de la requête
	$key0 = addslashes($data['name']);
	$key1 = $data['coll_parent'];
	$key2 = addslashes($data['issn']);
	
	/* vérification que la collection existe bien ! */
	$query = "SELECT collection_id FROM collections WHERE collection_id='${key1}' LIMIT 1 ";
	$result = @mysql_query($query, $dbh);
	if(!$result) die("can't SELECT colections ".$query);
	if (mysql_num_rows($result)==0) 
		return 0;

	/* vérification que la sous-collection existe */
	$query = "SELECT sub_coll_id FROM sub_collections WHERE sub_coll_name='${key0}' AND sub_coll_parent='${key1}' LIMIT 1 ";
	$result = @mysql_query($query, $dbh);
	if(!$result) die("can't SELECT sub_collections ".$query);
	$subcollection  = mysql_fetch_object($result);

	/* la sous-collection existe, on retourne l'ID */
	if($subcollection->sub_coll_id)
		return $subcollection->sub_coll_id;

	// id non-récupérée, il faut créer la forme.
	$query = "INSERT INTO sub_collections SET sub_coll_name='$key0', ";
	$query .= "sub_coll_parent='$key1', ";
	$query .= "sub_coll_issn='$key2', ";
	$query .= "index_sub_coll=' ".strip_empty_words($key0)." ".strip_empty_words($key2)." ' ";
	$result = @mysql_query($query, $dbh);
	if(!$result) die("can't INSERT into sub_collections".$query);

	return mysql_insert_id($dbh);
}
	
// ---------------------------------------------------------------
//		search_form() : affichage du form de recherche
// ---------------------------------------------------------------
function search_form() {
	global $user_query, $user_input;
	global $msg, $charset;

	$user_query = str_replace ('!!user_query_title!!', $msg[357]." : ".$msg[137] , $user_query);
	$user_query = str_replace ('!!action!!', './autorites.php?categ=souscollections&sub=reach&id=', $user_query);
	$user_query = str_replace ('!!add_auth_msg!!', $msg[176] , $user_query);
	$user_query = str_replace ('!!add_auth_act!!', './autorites.php?categ=souscollections&sub=collection_form', $user_query);
	$user_query = str_replace ('<!-- lien_derniers -->', "<a href='./autorites.php?categ=souscollections&sub=collection_last'>$msg[1313]</a>", $user_query);
	$user_query = str_replace("!!user_input!!",htmlentities(stripslashes($user_input),ENT_QUOTES, $charset),$user_query);
	print pmb_bidi($user_query) ;
}

//---------------------------------------------------------------
// update_index($id) : maj des n-uplets la table notice_global_index en rapport avec cet sous collection	
//---------------------------------------------------------------
function update_index($id) {
	global $dbh;
	// On cherche tous les n-uplet de la table notice correspondant à cet auteur.
	$found = mysql_query("select distinct notice_id from notices where subcoll_id='".$id."'",$dbh);
	// Pour chaque n-uplet trouvés on met a jour la table notice_global_index avec l'auteur modifié :
	while(($mesNotices = mysql_fetch_object($found))) {
		$notice_id = $mesNotices->notice_id;
		notice::majNoticesGlobalIndex($notice_id);
		notice::majNoticesMotsGlobalIndex($notice_id,'subcollection');
	}
}
} # fin de définition de la classe collection

} # fin de délaration
