<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: serie.class.php,v 1.37 2010-12-06 15:53:23 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

// définition de la classe de gestion des 'titres de séries'
if ( ! defined( 'SERIE_CLASS' ) ) {
  define( 'SERIE_CLASS', 1 );

require_once($class_path."/notice.class.php");
require_once("$class_path/aut_link.class.php");

class serie {

// ---------------------------------------------------------------
//		propriétés de la classe
// ---------------------------------------------------------------
	var $s_id=0;			// MySQL s_id in table 'series'
	var	$name='';			// nom de la série
	var	$index='';			// forme pour l'index
	var $isbd_entry_lien_gestion ; // lien sur le nom vers la gestion


// ---------------------------------------------------------------
//		série($s_id) : constructeur
// ---------------------------------------------------------------
function serie($id=0) {
	if($id) {
		// on cherche à atteindre une notice existante
		$this->s_id = $id;
		$this->getData();
		} else {
		// la notice n'existe pas
		$this->s_id = 0;
		$this->getData();
		}
	}

// ---------------------------------------------------------------
//		getData() : récupération infos du titre
// ---------------------------------------------------------------
function getData() {
	global $dbh;

	if(!$this->s_id) {
		// pas d'identifiant. on retourne un tableau vide
		$this->s_id			=	0;
		$this->name			=	'';
		$this->index		=	'';
		} else {
			$requete = "SELECT serie_id,serie_name,serie_index FROM series WHERE serie_id='".$this->s_id."' " ;
			$result = mysql_query($requete, $dbh) or die ($requete."<br />".mysql_error());
			if(mysql_num_rows($result)) {
				$temp = mysql_fetch_object($result);
				$this->s_id		= $temp->serie_id;
				$this->name		= $temp->serie_name;
				$this->index	= $temp->serie_index;
				// Ajoute un lien sur la fiche série si l'utilisateur à accès aux autorités
				if (SESSrights & AUTORITES_AUTH) $this->isbd_entry_lien_gestion = "<a href='./autorites.php?categ=series&sub=serie_form&id=".$this->s_id."' class='lien_gestion'>".$this->name."</a>";
					else $this->isbd_entry_lien_gestion = $this->name;
				} else {
					// pas de titre avec cette clé
					$this->s_id			=	0;
					$this->name			=	'';
					$this->index			=	'';
					}
			
			}
	}

// ---------------------------------------------------------------
//		show_form : affichage du formulaire de saisie
// ---------------------------------------------------------------
function show_form() {

	global $msg;
	global $charset;
	global $serie_form;

	if($this->s_id) {
		$action = "./autorites.php?categ=series&sub=update&id=$this->s_id";
		$libelle = $msg[337];
		$button_remplace = "<input type='button' class='bouton' value='$msg[158]' ";
		$button_remplace .= "onclick='unload_off();document.location=\"./autorites.php?categ=series&sub=replace&id=$this->s_id\"'>";
		$button_delete = "<input type='button' class='bouton' value='$msg[63]' ";
		$button_delete .= "onClick=\"confirm_delete();\">";
		$button_voir = "<input type='button' class='bouton' value='$msg[voir_notices_assoc]' ";
		$button_voir .= "onclick='unload_off();document.location=\"./catalog.php?categ=search&mode=10&etat=aut_search&aut_type=tit_serie&aut_id=$this->s_id\"'>";
	} else {
		$action = './autorites.php?categ=series&sub=update&id=';
		$libelle = $msg[336];
		$button_remplace = '';
		$button_delete ='';
		$button_voir="" ;
	}
	$aut_link= new aut_link(AUT_TABLE_SERIES,$this->s_id);
	$serie_form = str_replace('<!-- aut_link -->', $aut_link->get_form('saisie_serie') , $serie_form);
	
	$serie_form = str_replace('!!id!!', $this->s_id, $serie_form);
	$serie_form = str_replace('!!libelle!!', $libelle, $serie_form);
	$serie_form = str_replace('!!action!!', $action, $serie_form);
	$serie_form = str_replace('!!id!!', $this->s_id, $serie_form);
	$serie_form = str_replace('!!serie_nom!!', htmlentities($this->name,ENT_QUOTES, $charset), $serie_form);
	$serie_form = str_replace('!!remplace!!', $button_remplace,  $serie_form);
	$serie_form = str_replace('!!voir_notices!!', $button_voir,  $serie_form);
	$serie_form = str_replace('!!delete!!', $button_delete,  $serie_form);
	// pour retour à la bonne page en gestion d'autorités
	// &user_input=".rawurlencode(stripslashes($user_input))."&nbr_lignes=$nbr_lignes&page=$page
	global $user_input, $nbr_lignes, $page ;
	$serie_form = str_replace('!!user_input_url!!',		rawurlencode(stripslashes($user_input)),			$serie_form);
	$serie_form = str_replace('!!user_input!!',			htmlentities($user_input,ENT_QUOTES, $charset),		$serie_form);
	$serie_form = str_replace('!!nbr_lignes!!',			$nbr_lignes,										$serie_form);
	$serie_form = str_replace('!!page!!',				$page,												$serie_form);
	print $serie_form;
	}

// ---------------------------------------------------------------
//		replace_form : affichage du formulaire de remplacement
// ---------------------------------------------------------------
function replace_form() {
	global $serie_replace;
	global $msg;
	global $include_path;
	
	if(!$this->s_id || !$this->name) {
		require_once("$include_path/user_error.inc.php");
		error_message($msg[161], $msg[162], 1, './autorites.php?categ=series&sub=&id=');
		return false;
		}

	$serie_replace=str_replace('!!id!!', $this->s_id, $serie_replace);
	$serie_replace=str_replace('!!serie_name!!', $this->name, $serie_replace);
	print $serie_replace;
	}


// ---------------------------------------------------------------
//		delete() : suppression du titre de série
// ---------------------------------------------------------------
function delete() {
	global $dbh;
	global $msg;
	
	if(!$this->s_id)
		// impossible d'accéder à cette notice de titre de série
		return $msg[409];

	// récupération du nombre de notices affectées
	$requete = "SELECT COUNT(1) AS qte FROM notices WHERE tparent_id=".$this->s_id;
	$res = mysql_query($requete, $dbh);
	$nbr_lignes = mysql_result($res, 0, 0);

	if(!$nbr_lignes) {
		// titre de série non-utilisé dans des notices : Suppression OK
		// effacement dans la table des titres de série
		$requete = "DELETE FROM series WHERE serie_id=".$this->s_id;
		$result = mysql_query($requete, $dbh);
		// liens entre autorités
		$aut_link= new aut_link(AUT_TABLE_SERIES,$this->s_id);
		$aut_link->delete();
		return false;
		} else {
			// Ce titre de série est utilisé dans des notices, impossible de le supprimer
			return '<strong>'.$this->name."</strong><br />${msg[410]}";
			}
	}

// ---------------------------------------------------------------
//		replace($by) : remplacement du titre
// ---------------------------------------------------------------
function replace($by,$link_save=0) {

	// à compléter
	global $msg;
	global $dbh;

	if(!$by) {
		// pas de valeur de remplacement !!!
		return "serious error occured, please contact admin...";
	}
	if (($this->s_id == $by) || (!$this->s_id))  {
		// impossible de remplacer une autorité par elle-même
		return $msg[411];
	}
	
	$aut_link= new aut_link(AUT_TABLE_SERIES,$this->s_id);
	// "Conserver les liens entre autorités" est demandé
	if($link_save) {
		// liens entre autorités
		$aut_link->add_link_to(AUT_TABLE_SERIES,$by);		
	}
	$aut_link->delete();
	
	// a) remplacement dans les notices
	$requete = "UPDATE notices SET tparent_id=$by WHERE tparent_id=".$this->s_id;
	$res = mysql_query($requete, $dbh);
	
	$rqt_notice="select notice_id,tit1,tit2,tit3,tit4 from notices where tparent_id=".$by;
	$r_notice=mysql_query($rqt_notice);
	while ($r=mysql_fetch_object($r_notice)) {
		$rq_serie="update notices, series set notices.index_serie=serie_index, notices.index_wew=concat(serie_name,' ',tit1,' ',tit2,' ',tit3,' ',tit4),notices.index_sew=concat(' ',serie_index,' ','".addslashes(strip_empty_words($r->tit1." ".$r->tit2." ".$r->tit3." ".$r->tit4))."',' ') where notice_id=".$r->notice_id." and serie_id=tparent_id";
		mysql_query($rq_serie);
		}
	
	// b) suppression du titre de série à remplacer
	$requete = "DELETE FROM series WHERE serie_id=".$this->s_id;
	$res = mysql_query($requete, $dbh);

	serie::update_index($by);

	return FALSE;
	}

// ---------------------------------------------------------------
//		update($value) : mise à jour du titre de série
// ---------------------------------------------------------------
function update($value) {

	global $dbh;
	global $msg;
	global $include_path;
	
	if(!$value)
		return false;

	// nettoyage de la chaîne en entrée
	$value = clean_string($value);

	$requete = "SET serie_name='".$value."', ";
	$requete .= "serie_index=' ".strip_empty_words($value)." '";

	if($this->s_id) {
		// update
		$requete = 'UPDATE series '.$requete;
		$requete .= ' WHERE serie_id='.$this->s_id.' LIMIT 1;';
		if(mysql_query($requete, $dbh)) {
			$rqt_notice="select notice_id,tit1,tit2,tit3,tit4 from notices where tparent_id=".$this->s_id;
			$r_notice=mysql_query($rqt_notice);
			while ($r=mysql_fetch_object($r_notice)) {
				$rq_serie="update notices, series set notices.index_serie=serie_index, notices.index_wew=concat(serie_name,' ',tit1,' ',tit2,' ',tit3,' ',tit4),notices.index_sew=concat(' ',serie_index,' ','".addslashes(strip_empty_words($r->tit1." ".$r->tit2." ".$r->tit3." ".$r->tit4))."',' ') where notice_id=".$r->notice_id." and serie_id=tparent_id";
				mysql_query($rq_serie);
			}
			serie::update_index($this->s_id);
			$aut_link= new aut_link(AUT_TABLE_SERIES,$this->s_id);
			$aut_link->save_form();
			return TRUE;
		} else {
			require_once("$include_path/user_error.inc.php");
			warning($msg[337], $msg[341]);
			return FALSE;
		}
	} else {
		// création : s'assurer que le titre n'existe pas déjà
		$dummy = "SELECT * FROM series WHERE serie_name REGEXP '^$value$' LIMIT 1 ";
		$check = mysql_query($dummy, $dbh);
		if(mysql_num_rows($check)) {
			require_once("$include_path/user_error.inc.php");
			warning($msg[336], $msg[340]);
			return FALSE;
		}
		$requete = 'INSERT INTO series '.$requete.';';
		if(mysql_query($requete, $dbh)) {
			$this->s_id=mysql_insert_id();
			$aut_link= new aut_link(AUT_TABLE_SERIES,$this->s_id);
			$aut_link->save_form();			
			return TRUE;
		} else {
			require_once("$include_path/user_error.inc.php");
			warning($msg[336], $msg[342]);
			return FALSE;
		}
	}
}

// ---------------------------------------------------------------
//		import() : import d'un titre de série
// ---------------------------------------------------------------
// fonction d'import de notice auteur (membre de la classe 'author');
function import($title) {

	global $dbh;

	// check sur la variable passée en paramètre
	if(!$title) {
		return 0;
	}

	// tentative de récupérer l'id associée dans la base (implique que l'autorité existe)
	// préparation de la requête
	$key = addslashes($title);

	$query = "SELECT serie_id FROM series WHERE serie_name='".rtrim(substr($key,0,255))."' LIMIT 1 ";
	$result = @mysql_query($query, $dbh);
	if(!$result) die("can't SELECT series ".$query);
	// résultat

	// récupération du résultat de la recherche
	$tserie  = mysql_fetch_object($result);
	// du résultat et récupération éventuelle de l'id
	if($tserie->serie_id)
		return $tserie->serie_id;

	// id non-récupérée, il faut créer la forme.
	$index = addslashes(strip_empty_words($title));
	
	$query = "INSERT INTO series SET serie_name='$key', serie_index=' $index '";

	$result = @mysql_query($query, $dbh);
	if(!$result) die("can't INSERT into series".$query);

	return mysql_insert_id($dbh);
}

// ---------------------------------------------------------------
//		search_form() : affichage du form de recherche
// ---------------------------------------------------------------
function search_form() {
	global $user_query, $user_input;
	global $msg, $charset;

	$user_query = str_replace ('!!user_query_title!!', $msg[357]." : ".$msg[333] , $user_query);
	$user_query = str_replace ('!!action!!', './autorites.php?categ=series&sub=reach&id=', $user_query);
	$user_query = str_replace ('!!add_auth_msg!!', $msg[339] , $user_query);
	$user_query = str_replace ('!!add_auth_act!!', './autorites.php?categ=series&sub=serie_form', $user_query);
	$user_query = str_replace ('<!-- lien_derniers -->', "<a href='./autorites.php?categ=series&sub=serie_last'>$msg[1314]</a>", $user_query);
	$user_query = str_replace("!!user_input!!",htmlentities(stripslashes($user_input),ENT_QUOTES, $charset),$user_query);
	print pmb_bidi($user_query) ;
//	print "<br />
//		<input class='bouton' type='button' value='$msg[339]' onClick=\"document.location='./autorites.php?categ=series&sub=serie_form'\" />
//		";
	}

//---------------------------------------------------------------
// update_index($id) : maj des n-uplets la table notice_global_index en rapport avec cet série
//---------------------------------------------------------------
function update_index($id) {
	global $dbh;
	// On cherche tous les n-uplet de la table notice correspondant à cet auteur.
	$found = mysql_query("select distinct(notice_id) from notices where tparent_id='".$id."'",$dbh);
	// Pour chaque n-uplet trouvés on met a jour la table notice_global_index avec l'auteur modifié :
	while(($mesNotices = mysql_fetch_object($found))) {
		$notice_id = $mesNotices->notice_id;
		notice::majNoticesGlobalIndex($notice_id);
		notice::majNoticesMotsGlobalIndex($notice_id,'serie');
	}
}

} # fin de définition de la classe serie

} # fin de délaration

