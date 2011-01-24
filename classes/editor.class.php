<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: editor.class.php,v 1.39 2010-12-06 15:53:23 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

// definition de la classe de gestion des 'editeurs'
if ( ! defined( 'PUBLISHER_CLASS' ) ) {
  define( 'PUBLISHER_CLASS', 1 );

require_once($class_path."/notice.class.php");
require_once("$class_path/aut_link.class.php");

class editeur {

// ---------------------------------------------------------------
//		proprietes de la classe
// ---------------------------------------------------------------

	var $id;			// MySQL id in table 'publishers'
	var	$name;			// publisher name
	var	$adr1;			// adress line 1
	var	$adr2;			// adress line 2
	var	$cp;			// zip code
	var	$ville;			// city
	var	$pays;			// country
	var	$web;			// url of web site
	var	$link;			// url of web site (clickable)
	var	$display;		// usable form for displaying ( _name_ (_ville_) or just _name_ )
	var	$isbd_entry;		// isbd like version ( _ville_ (_country ?_) : _name_ )
	var $isbd_entry_lien_gestion ; // lien sur le nom vers la gestion
	var $ed_comment ; // Commentaire, peut contenir du HTML

// ---------------------------------------------------------------
//		editeur($id) : constructeur
// ---------------------------------------------------------------
function editeur($id=0) {
	if($id) {
		// on cherche a atteindre une notice existante
		$this->id = $id;
		$this->getData();
		} else {
			// la notice n'existe pas
			$this->id = 0;
			$this->getData();
			}
	}

// ---------------------------------------------------------------
//		getData() : recuperation infos editeurs
// ---------------------------------------------------------------
function getData() {
	global $dbh;
	if(!$this->id) {
		// pas d'identifiant. on retourne un tableau vide
		$this->id			=	0;
		$this->name			=	'';
		$this->adr1			=	'';
		$this->adr2			=	'';
		$this->cp			=	'';
		$this->ville			=	'';
		$this->pays			=	'';
		$this->web			=	'';
		$this->link			=	'';
		$this->display			=	'';
		$this->isbd_entry		=	'';
		$this->ed_comment	= '';
	} else {
		$requete = "SELECT * FROM publishers WHERE ed_id=$this->id LIMIT 1 ";
		$result = @mysql_query($requete, $dbh);
		if(mysql_num_rows($result)) {
			$temp = mysql_fetch_object($result);
			mysql_free_result($result);
			$this->id		= $temp->ed_id;
			$this->name		= $temp->ed_name;
			$this->adr1		= $temp->ed_adr1;
			$this->adr2		= $temp->ed_adr2;
			$this->cp		= $temp->ed_cp;
			$this->ville	= $temp->ed_ville;
			$this->pays		= $temp->ed_pays;
			$this->web		= $temp->ed_web;
			$this->ed_comment= $temp->ed_comment	;
			if($temp->ed_web) {
				$this->link = "<a href='$temp->ed_web' target='_new'>$temp->ed_web</a>";
			} else {
				$this->link = '';
			}
			// Determine le lieu de publication
			$l = '';
			if ($this->adr1)  $l = $this->adr1;
			if ($this->adr2)  $l = ($l=='') ? $this->adr2 : $l.', '.$this->adr2;
			if ($this->cp)    $l = ($l=='') ? $this->cp   : $l.', '.$this->cp;
			if ($this->pays)  $l = ($l=='') ? $this->pays : $l.', '.$this->pays;
			if ($this->ville) $l = ($l=='') ? $this->ville : $this->ville.' ('.$l.')';
			if ($l=='')       $l = '[S.l.]';
				
			// Determine le nom de l'editeur
			if ($this->name) $n = $this->name; else $n = '[S.n.]';
				
			// Constitue l'ISBD pour le coupe lieu/editeur
			if ($l == '[S.l.]' AND $n == '[S.n.]') $this->isbd_entry = '[S.l.&nbsp;: s.n.]';
			else $this->isbd_entry = $l.'&nbsp;: '.$n;
				
			if ($this->ville) {
				if ($this->pays) $this->display = "$this->ville [$this->pays] : $this->name";
				else $this->display = "$this->ville : $this->name";
			} else {
				if ($this->pays) $this->display = "[$this->pays] : $this->name";
				else $this->display = $this->name;
			}
			// Ajoute un lien sur la fiche editeur si l'utilisateur a acces aux autorites
			if (SESSrights & AUTORITES_AUTH) $this->isbd_entry_lien_gestion = "<a href='./autorites.php?categ=editeurs&sub=editeur_form&id=".$this->id."' class='lien_gestion'>".$this->display."</a>";
			else $this->isbd_entry_lien_gestion = $this->display; 
				
		} else {
			// pas d'editeur avec cette cle
			$this->id			=	0;
			$this->name			=	'';
			$this->adr1			=	'';
			$this->adr2			=	'';
			$this->cp			=	'';
			$this->ville		=	'';
			$this->pays			=	'';
			$this->web			=	'';
			$this->link			=	'';
			$this->display		=	'';
			$this->isbd_entry	=	'';
			$this->ed_comment	=	'';
		}
	}
	}

// ---------------------------------------------------------------
//		show_form : affichage du formulaire de saisie
// ---------------------------------------------------------------
function show_form() {

	global $msg;
	global $publisher_form;
 	global $charset;

	if($this->id) {
		$action = "./autorites.php?categ=editeurs&sub=update&id=$this->id";
		$libelle = $msg[148];
		$button_remplace = "<input type='button' class='bouton' value='$msg[158]' ";
		$button_remplace .= "onclick='unload_off();document.location=\"./autorites.php?categ=editeurs&sub=replace&id=$this->id\"'>";

		$button_voir = "<input type='button' class='bouton' value='$msg[voir_notices_assoc]' ";
		$button_voir .= "onclick='unload_off();document.location=\"./catalog.php?categ=search&mode=2&etat=aut_search&aut_type=publisher&aut_id=$this->id\"'>";

		$button_delete = "<input type='button' class='bouton' value='$msg[63]' ";
		$button_delete .= "onClick=\"confirm_delete();\">";
		} else {
			$action = './autorites.php?categ=editeurs&sub=update&id=';
			$libelle = $msg[145];
			$button_remplace = '';
			$button_delete ='';
			}
	$aut_link= new aut_link(AUT_TABLE_PUBLISHERS,$this->id);
	$publisher_form = str_replace('<!-- aut_link -->', $aut_link->get_form('saisie_editeur') , $publisher_form);
	
	$publisher_form = str_replace('!!libelle!!', $libelle, $publisher_form);
	$publisher_form = str_replace('!!action!!', $action, $publisher_form);
	$publisher_form = str_replace('!!id!!', $this->id, $publisher_form);
 	$publisher_form = str_replace('!!ed_nom!!', htmlentities($this->name,ENT_QUOTES, $charset), $publisher_form);
 	$publisher_form = str_replace('!!ed_adr1!!', htmlentities($this->adr1,ENT_QUOTES, $charset), $publisher_form);
 	$publisher_form = str_replace('!!ed_adr2!!', htmlentities($this->adr2,ENT_QUOTES, $charset), $publisher_form);
 	$publisher_form = str_replace('!!ed_cp!!', htmlentities($this->cp,ENT_QUOTES, $charset), $publisher_form);
 	$publisher_form = str_replace('!!ed_ville!!', htmlentities($this->ville,ENT_QUOTES, $charset), $publisher_form);
 	$publisher_form = str_replace('!!ed_pays!!', htmlentities($this->pays,ENT_QUOTES, $charset), $publisher_form);
 	$publisher_form = str_replace('!!ed_web!!', htmlentities($this->web,ENT_QUOTES, $charset), $publisher_form);
	$publisher_form = str_replace('!!remplace!!', $button_remplace,  $publisher_form);
	$publisher_form = str_replace('!!voir_notices!!', $button_voir, $publisher_form );
	$publisher_form = str_replace('!!delete!!', $button_delete, $publisher_form);
	// pour retour a la bonne page en gestion d'autorites
	// &user_input=".rawurlencode(stripslashes($user_input))."&nbr_lignes=$nbr_lignes&page=$page
	global $user_input, $nbr_lignes, $page ;
	$publisher_form = str_replace('!!user_input_url!!',		rawurlencode(stripslashes($user_input)),			$publisher_form);
	$publisher_form = str_replace('!!user_input!!',			htmlentities($user_input,ENT_QUOTES, $charset),		$publisher_form);
	$publisher_form = str_replace('!!nbr_lignes!!',			$nbr_lignes,										$publisher_form);
	$publisher_form = str_replace('!!page!!',				$page,												$publisher_form);
	$publisher_form = str_replace('!!ed_comment!!',		$this->ed_comment,									$publisher_form);
	print $publisher_form;
	}

// ---------------------------------------------------------------
//		replace_form : affichage du formulaire de remplacement
// ---------------------------------------------------------------
function replace_form() {
	global $publisher_replace;
	global $msg;
	global $include_path;
	
	if(!$this->id || !$this->name) {
		require_once("$include_path/user_error.inc.php");
		error_message($msg[161], $msg[162], 1, './autorites.php?categ=editeurs&sub=&id=');
		return false;
		}

	$publisher_replace=str_replace('!!id!!', $this->id, $publisher_replace);
	$publisher_replace=str_replace('!!ed_name!!', $this->name, $publisher_replace);
	print $publisher_replace;
	}

// ---------------------------------------------------------------
//		delete() : suppression de l'editeur
// ---------------------------------------------------------------
function delete() {
	global $dbh;
	global $msg;
	
	if(!$this->id)
		// impossible d'acceder a cette notice auteur
		return $msg[403]; 

	// effacement dans les notices
	// recuperation du nombre de notices affectees
	$requete = "SELECT COUNT(1) FROM notices WHERE ";
	$requete .= "ed1_id=$this->id OR ";
	$requete .= "ed2_id=$this->id";
	$res = mysql_query($requete, $dbh);
	$nbr_lignes = mysql_result($res, 0, 0);
	if(!$nbr_lignes) {
		// on regarde si l'editeur a des collections enfants 
		$requete = "SELECT COUNT(1) FROM collections WHERE ";
		$requete .= "collection_parent=".$this->id;
		$res = mysql_query($requete, $dbh);
		$nbr_lignes = mysql_result($res, 0, 0);
		if(!$nbr_lignes) {
			// effacement dans la table des editeurs
			$requete = "DELETE FROM publishers WHERE ed_id=".$this->id;
			$result = mysql_query($requete, $dbh);
			// liens entre autorités
			$aut_link= new aut_link(AUT_TABLE_PUBLISHERS,$this->id);
			$aut_link->delete();
			return false;
			} else {
				// Cet editeur a des collections, impossible de le supprimer
				return '<strong>'.$this->name."</strong><br />${msg[405]}";
				}
		} else {
			// Cet editeur est utilise dans des notices, impossible de le supprimer
			return '<strong>'.$this->name."</strong><br />${msg[404]}";
			}
	}

// ---------------------------------------------------------------
//		replace($by) : remplacement de l'editeur
// ---------------------------------------------------------------
function replace($by,$link_save=0) {

	global $msg;
	global $dbh;

	if((!$by)||(!$this->id)) {
		// pas de valeur de remplacement !!!
		return "L'identifiant editeur est vide ou l'editeur de remplacement est meme que celui d'origine !";
	}

	if($this->id == $by) {
		// impossible de remplacer un editeur par lui-meme
		return $msg[228];
	}
		
	$aut_link= new aut_link(AUT_TABLE_PUBLISHERS,$this->id);
	// "Conserver les liens entre autorités" est demandé
	if($link_save) {
		// liens entre autorités
		$aut_link->add_link_to(AUT_TABLE_PUBLISHERS,$by);		
	}	
	$aut_link->delete();
	
	// a) remplacement dans les notices
	$requete = "UPDATE notices SET ed1_id=$by WHERE ed1_id=".$this->id;
	$res = mysql_query($requete, $dbh);
	$requete = "UPDATE notices SET ed2_id=$by WHERE ed2_id=".$this->id;
	$res = mysql_query($requete, $dbh);

	// b) remplacement dans la table des collections
	$requete = "UPDATE collections SET collection_parent=$by WHERE collection_parent=".$this->id;
	$res = mysql_query($requete, $dbh);

	// c) suppression de l'editeur a remplacer
	$requete = "DELETE FROM publishers WHERE ed_id=".$this->id;
	$res = mysql_query($requete, $dbh);

	editeur::update_index($by);

	return FALSE;
	}

// ---------------------------------------------------------------
//		update($value) : mise a jour de l'editeur
// ---------------------------------------------------------------
function update($value) {

	global $dbh;
	global $msg;
	global $include_path;
	
	if(!$value['name'])
		return false;

	// nettoyage des valeurs en entree
	$value[name] = clean_string($value[name]); 
	$value[adr1] = clean_string($value[adr1]);
	$value[adr2] = clean_string($value[adr2]);
	$value[cp]   = clean_string($value[cp]);
	$value[ville] = clean_string($value[ville]);
	$value[pays]  = clean_string($value[pays]);
	$value[web]   = clean_string($value[web]);
							
	// construction de la requete
	$requete = "SET ed_name='$value[name]', ";
	$requete .= "ed_adr1='$value[adr1]', ";
	$requete .= "ed_adr2='$value[adr2]', ";
	$requete .= "ed_cp='$value[cp]', ";
	$requete .= "ed_ville='$value[ville]', ";
	$requete .= "ed_pays='$value[pays]', ";
	$requete .= "ed_web='$value[web]', ";
	$requete .= "ed_comment='$value[ed_comment]', ";
	$requete .= "index_publisher=' ".strip_empty_chars($value[name]." ".$value[ville]." ".$value[pays])." '";
	if($this->id) {
		// update
		$requete = 'UPDATE publishers '.$requete;
		$requete .= ' WHERE ed_id='.$this->id.' LIMIT 1;';
		if(mysql_query($requete, $dbh)) {
			editeur::update_index($this->id);
			$aut_link= new aut_link(AUT_TABLE_PUBLISHERS,$this->id);
			$aut_link->save_form();
			return TRUE;
		}else {
				require_once("$include_path/user_error.inc.php");
				warning($msg[145], $msg[150]);
				return FALSE;
				}
		} else {
			// s'assurer que l'editeur n'existe pas deja
			// on teste sur nom seulement. voir a l'usage si necessaire de tester plus
			$dummy = "SELECT * FROM publishers WHERE ed_name='^${value[name]}$' and ed_ville='^${value[ville]}$' ";
			$check = mysql_query($dummy, $dbh);
			if(mysql_num_rows($check)) {
				require_once("$include_path/user_error.inc.php");
				warning($msg[145], $msg[149]." (${value['name']}).");
				return FALSE;
				}
			$requete = 'INSERT INTO publishers '.$requete.';';
			if(mysql_query($requete, $dbh)) {
				$this->id=mysql_insert_id();
				$aut_link= new aut_link(AUT_TABLE_PUBLISHERS,$this->id);
				$aut_link->save_form();
				return TRUE;
			} else {
				require_once("$include_path/user_error.inc.php");
				warning($msg[145], $msg[151]);
				return FALSE;
			}
			}
	}
	
// ---------------------------------------------------------------
//		import($value) : import editeur
// ---------------------------------------------------------------
function import($data) {
	global $dbh;

	// check sur le type de  la variable passee en parametre
	if(!sizeof($data) || !is_array($data)) {
		// si ce n'est pas un tableau ou un tableau vide, on retourne 0
		return 0;
		}

	// tentative de recuperer l'id associee dans la base (implique que l'autorite existe)
	// preparation de la requeªte
	$long_maxi = mysql_field_len(mysql_query("SELECT ed_name FROM publishers limit 1"),0);
	
	$key = addslashes(rtrim(substr(preg_replace('/\[|\]/', '', rtrim(ltrim($data['name']))),0,$long_maxi)));
	$ville=addslashes(trim($data['ville']));
	$adr=addslashes(trim($data['adr']));
	$ed_comment=addslashes(trim($data['ed_comment']));
	
	if ($key=="") return 0; /* on laisse tomber les editeurs sans nom !!! exact. FL*/

	$query = "SELECT ed_id FROM publishers WHERE ed_name='${key}' and ed_ville = '${ville}' ";
	$result = @mysql_query($query, $dbh);
	if(!$result) die("can't SELECT publisher ".$query);
	// resultat

	// recuperation du resultat de la recherche
	$tediteur  = mysql_fetch_object($result);
	// et recuperation eventuelle de l'id
	if($tediteur->ed_id)
		return $tediteur->ed_id;

	// id non-recuperee, il faut creer la forme.

	$query = "INSERT INTO publishers SET ed_name='${key}', ed_ville = '${ville}', ed_adr1 = '${adr}', ed_comment='".$ed_comment."', index_publisher=' ".strip_empty_chars($key)." ' ";

	$result = @mysql_query($query, $dbh);
	if(!$result) die("can't INSERT into publisher : ".$query);

	return mysql_insert_id($dbh);
	}

// ---------------------------------------------------------------
//		search_form() : affichage du form de recherche
// ---------------------------------------------------------------
function search_form() {
	global $user_query, $user_input;
	global $msg, $charset;
	
	$user_query = str_replace ('!!user_query_title!!', $msg[357]." : ".$msg[135] , $user_query);
	$user_query = str_replace ('!!action!!', './autorites.php?categ=editeurs&sub=reach&id=', $user_query);
	$user_query = str_replace ('!!add_auth_msg!!', $msg[143] , $user_query);
	$user_query = str_replace ('!!add_auth_act!!', './autorites.php?categ=editeurs&sub=editeur_form', $user_query);
	$user_query = str_replace ('<!-- lien_derniers -->', "<a href='./autorites.php?categ=editeurs&sub=editeur_last'>$msg[1311]</a>", $user_query);
	$user_query = str_replace("!!user_input!!",htmlentities(stripslashes($user_input),ENT_QUOTES, $charset),$user_query);
	
	print pmb_bidi($user_query) ;
//	print "<br />
//		<input class='bouton' type='button' value='$msg[143]' onClick=\"document.location='./autorites.php?categ=editeurs&sub=editeur_form'\" />
//		";
	}
//---------------------------------------------------------------
// update_index($id) : maj des n-uplets la table notice_global_index en rapport avec cet editeur	
//---------------------------------------------------------------
function update_index($id) {
	global $dbh;
	// On cherche tous les n-uplet de la table notice correspondant a cet auteur.
	$found = mysql_query("select distinct notice_id from notices where ed1_id='".$id."' OR ed2_id='".$id."'",$dbh);
	// Pour chaque n-uplet trouves on met a jour la table notice_global_index avec l'auteur modifie :
	while($mesNotices = mysql_fetch_object($found)) {
		$notice_id = $mesNotices->notice_id;
		notice::majNoticesGlobalIndex($notice_id);
		notice::majNoticesMotsGlobalIndex($notice_id,'publisher');
	}
}
} # fin de definition de la classe editeur

} # fin de declaration

