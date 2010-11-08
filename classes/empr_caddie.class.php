<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: empr_caddie.class.php,v 1.4 2008-03-23 11:29:49 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

// définition de la classe de gestion des paniers

define( 'CADDIE_ITEM_NULL', 0 );
define( 'CADDIE_ITEM_OK', 1 );
define( 'CADDIE_ITEM_DEJA', 1 ); // identique car on peut ajouter des liés avec l'item et non pas l'item saisi lui-même ...
define( 'CADDIE_ITEM_IMPOSSIBLE_BULLETIN', 2 );
define( 'CADDIE_ITEM_EXPL_PRET' , 3 );
define( 'CADDIE_ITEM_BULL_USED', 4) ;
define( 'CADDIE_ITEM_NOTI_USED', 5) ;
define( 'CADDIE_ITEM_SUPPR_BASE_OK', 6) ;
define( 'CADDIE_ITEM_INEXISTANT', 7 );

class empr_caddie {
// propriétés
var $idemprcaddie ;
var $name = ''			;	// nom de référence
var $comment = ""		;	// description du contenu du panier
var $nb_item = 0		;	// nombre d'enregistrements dans le panier
var $nb_item_pointe = 0		;	// nombre d'enregistrements pointés dans le panier
var $autorisations = ""		;	// autorisations accordées sur ce panier

// ---------------------------------------------------------------
//		empr_caddie($id) : constructeur
// ---------------------------------------------------------------
function empr_caddie($empr_caddie_id=0) {
	if($empr_caddie_id) {
		$this->idemprcaddie = $empr_caddie_id;
		$this->getData();
		} else {
			$this->idemprcaddie = 0;
			$this->getData();
			}
	}

// ---------------------------------------------------------------
//		getData() : récupération infos caddie
// ---------------------------------------------------------------
function getData() {
	global $dbh;
	if(!$this->idemprcaddie) {
		// pas d'identifiant.
		$this->name	= '';
		$this->comment	= '';
		$this->nb_item	= 0;
		$this->autorisations	= "";
	} else {
		$requete = "SELECT * FROM empr_caddie WHERE idemprcaddie='$this->idemprcaddie' ";
		$result = @mysql_query($requete, $dbh);
		if(mysql_num_rows($result)) {
			$temp = mysql_fetch_object($result);
			mysql_free_result($result);
			$this->idemprcaddie = $temp->idemprcaddie;
			$this->name = $temp->name;
			$this->comment = $temp->comment;
			$this->autorisations = $temp->autorisations;
		} else {
			// pas de caddie avec cet id
			$this->idemprcaddie = 0;
			$this->name = '';
			$this->comment = '';
			$this->autorisations = "";
		}
		$this->compte_items();
	}
}

// liste des paniers disponibles
function get_cart_list() {
	global $dbh, $PMBuserid;
	$cart_list=array();
	if ($PMBuserid!=1) $where=" where (autorisations='$PMBuserid' or autorisations like '$PMBuserid %' or autorisations like '% $PMBuserid %' or autorisations like '% $PMBuserid') ";
	$requete = "SELECT * FROM empr_caddie $where order by name ";
	$result = @mysql_query($requete, $dbh);
	if(mysql_num_rows($result)) {
		while ($temp = mysql_fetch_object($result)) {
			$nb_item = 0 ;
			$nb_item_pointe = 0 ;
			$rqt_nb_item="select count(1) from empr_caddie_content where empr_caddie_id='".$temp->idemprcaddie."' ";
			$nb_item = mysql_result(mysql_query($rqt_nb_item, $dbh), 0, 0);
			$rqt_nb_item_pointe = "select count(1) from empr_caddie_content where empr_caddie_id='".$temp->idemprcaddie."' and (flag is not null and flag!='') ";
			$nb_item_pointe = mysql_result(mysql_query($rqt_nb_item_pointe, $dbh), 0, 0);

			$cart_list[] = array( 
				'idemprcaddie' => $temp->idemprcaddie,
				'name' => $temp->name,
				'comment' => $temp->comment,
				'autorisations' => $temp->autorisations,
				'nb_item' => $nb_item,
				'nb_item_pointe' => $nb_item_pointe
				);
		}
	} 
	return $cart_list;
}

// création d'un panier vide
function create_cart() {
	global $dbh;
	$requete = "insert into empr_caddie set name='".$this->name."', comment='".$this->comment."', autorisations='".$this->autorisations."' ";
	$result = @mysql_query($requete, $dbh);
	$this->idemprcaddie = mysql_insert_id($dbh);
	$this->compte_items();
	}


// ajout d'un item
function add_item($item=0) {
	global $dbh;
	
	if (!$item) return CADDIE_ITEM_NULL ;
	
	$requete = "replace into empr_caddie_content set empr_caddie_id='".$this->idemprcaddie."', object_id='".$item."' ";
	$result = @mysql_query($requete, $dbh);
	return CADDIE_ITEM_OK ;
	}

// suppression d'un item
function del_item($item=0) {
	global $dbh;
	$requete = "delete FROM empr_caddie_content where empr_caddie_id='".$this->idemprcaddie."' and object_id='".$item."' ";
	$result = @mysql_query($requete, $dbh);
	$this->compte_items();
}

function del_item_base($item=0) {
	global $dbh;
	
	if (!$item) return CADDIE_ITEM_NULL ;
	
	if (!$this->verif_empr_item($item)) {
		emprunteur::del_empr($item);
		return CADDIE_ITEM_SUPPR_BASE_OK ;
	} else return CADDIE_ITEM_EXPL_PRET ;
				
}

// suppression d'un item de tous les caddies du même type le contenant
function del_item_all_caddies($item) {
	global $dbh;
	$requete = "select idemprcaddie FROM empr_caddie ";
	$result = mysql_query($requete, $dbh);
	for($i=0;$i<mysql_num_rows($result);$i++) {
		$temp=mysql_fetch_object($result);
		$requete_suppr = "delete from empr_caddie_content where empr_caddie_id='".$temp->idemprcaddie."' and object_id='".$item."' ";
		$result_suppr = mysql_query($requete_suppr, $dbh);
	}
}

function del_item_flag() {
	global $dbh;
	$requete = "delete FROM empr_caddie_content where empr_caddie_id='".$this->idemprcaddie."' and (flag is not null and flag!='') ";
	$result = @mysql_query($requete, $dbh);
	$this->compte_items();
}

function del_item_no_flag() {
	global $dbh;
	$requete = "delete FROM empr_caddie_content where empr_caddie_id='".$this->idemprcaddie."' and (flag is null or flag='') ";
	$result = @mysql_query($requete, $dbh);
	$this->compte_items();
}

// Dépointage de tous les items
function depointe_items() {
	global $dbh;
	$requete = "update empr_caddie_content set flag=null where empr_caddie_id='".$this->idemprcaddie."' ";
	$result = @mysql_query($requete, $dbh);
	$this->compte_items();
}	

function pointe_item($item=0) {
	global $dbh;
	$requete = "update empr_caddie_content set flag='1' where empr_caddie_id='".$this->idemprcaddie."' and object_id='".$item."' ";
	$result = @mysql_query($requete, $dbh);
	$this->compte_items();
	return CADDIE_ITEM_OK ;
}

// suppression d'un panier
function delete() {
	global $dbh;
	$requete = "delete FROM empr_caddie_content where empr_caddie_id='".$this->idemprcaddie."' ";
	$result = @mysql_query($requete, $dbh);
	$requete = "delete FROM empr_caddie where idemprcaddie='".$this->idemprcaddie."' ";
	$result = @mysql_query($requete, $dbh);
}

// sauvegarde du panier
function save_cart() {
	global $dbh;
	$requete = "update empr_caddie set name='".$this->name."', comment='".$this->comment."', autorisations='".$this->autorisations."' where idemprcaddie='".$this->idemprcaddie."'";
	$result = @mysql_query($requete, $dbh);
}


// get_cart() : ouvre un panier et récupère le contenu
function get_cart($flag="") {
	global $dbh;
	$cart_list=array();
	switch ($flag) {
		case "FLAG" :
			$requete = "SELECT * FROM empr_caddie_content where empr_caddie_id='".$this->idemprcaddie."' and (flag is not null and flag!='') ";
			break ;
		case "NOFLAG" :
			$requete = "SELECT * FROM empr_caddie_content where empr_caddie_id='".$this->idemprcaddie."' and (flag is null or flag='') ";
			break ;
		case "ALL" :
		default :
			$requete = "SELECT * FROM empr_caddie_content where empr_caddie_id='".$this->idemprcaddie."' ";
			break ;
		}
	$result = @mysql_query($requete, $dbh);
	if(mysql_num_rows($result)) {
		while ($temp = mysql_fetch_object($result)) {
			$cart_list[] = $temp->object_id;
		}
	} 
	return $cart_list;
}

// compte_items 
function compte_items() {
	global $dbh;
	$this->nb_item = 0 ;
	$this->nb_item_pointe = 0 ;
	$rqt_nb_item="select count(1) from empr_caddie_content where empr_caddie_id='".$this->idemprcaddie."' ";
	$this->nb_item = mysql_result(mysql_query($rqt_nb_item, $dbh), 0, 0);
	$rqt_nb_item_pointe = "select count(1) from empr_caddie_content where empr_caddie_id='".$this->idemprcaddie."' and (flag is not null and flag!='') ";
	$this->nb_item_pointe = mysql_result(mysql_query($rqt_nb_item_pointe, $dbh), 0, 0);
}

function verif_empr_item($id) {

	global $dbh;
	if ($id) {
		$query = "select count(1) from pret where pret_idempr=".$id." limit 1 ";
		$result = mysql_query($query, $dbh);
		if(mysql_result($result, 0, 0)) return 1 ;
		return 0 ;
		
	} else return 0 ;
}
	
} // fin de déclaration de la classe
  
