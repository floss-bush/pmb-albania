<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: cart.php,v 1.23 2008-05-13 15:41:02 ngantier Exp $

// définition du minimum nécéssaire 
$base_path=".";                            
$base_auth = "";  

@error_reporting (E_ERROR | E_PARSE | E_WARNING);
switch ($object_type) {
	case "EXPL":
		$base_title = "\$msg[expl_carts]";
		break;
	case "EMPR":
		$base_title = "\$msg[empr_carts]";
		break;
	case "BULL":
		$base_title = "\$msg[bull_carts]";
		break;
	case "NOTI":
	default:
		$base_title = "\$msg[396]";
		break;
	}

require_once ("$base_path/includes/init.inc.php");  

// modules propres à cart.php ou à ses sous-modules
include_once("$include_path/cart.inc.php");
include_once("$include_path/templates/cart.tpl.php");
include_once("$include_path/isbn.inc.php");
include_once("$include_path/expl_info.inc.php");
include_once("$include_path/bull_info.inc.php");
include_once("$include_path/notice_authors.inc.php");
include_once("$include_path/notice_categories.inc.php");
include_once("$include_path/explnum.inc.php");
include_once("$class_path/cart.class.php");
include_once("$class_path/caddie.class.php");
include_once("$class_path/author.class.php");
include_once("$class_path/collection.class.php");
include_once("$class_path/subcollection.class.php");
include_once("$class_path/mono_display.class.php");
include_once("$class_path/serie.class.php");
include_once("$class_path/serial_display.class.php");
include_once("$class_path/serials.class.php");
include_once("$class_path/editor.class.php");
require_once("$class_path/emprunteur.class.php");
require_once("$javascript_path/misc.inc.php");
include_once("$class_path/empr_caddie.class.php");
if (!$empr_show_caddie && $object_type=="EMPR") die();
print $expand_result;

print "<div id='contenu-frame'>";

// ne pas afficher les liens d'ajout aux caddies
$cart_link_non=1;

// afin de vérifier les droits sur le caddie :
$myCartTemp=new caddie($idcaddie) ;
if (!$myCartTemp->idcaddie) $idcaddie=0;

// gestion id de notice fille, concaténé avec l'id de la mère
if (($pos=strpos($item, "_p"))) {	
	$item=substr($item,0,$pos);   	 
}
// constante pour afficher le lien de suppr du panier
switch ($action) {
	case 'new_cart':
		$cart_form = str_replace('!!autorisations_users!!', aff_form_autorisations("",1), $cart_form);
		$cart_form = str_replace('!!formulaire_action!!', "./cart.php?action=valid_new_cart&object_type=$object_type&item=$item", $cart_form);
	break;
	case 'del_cart':
		if($object_type=="EMPR") {
			$myCart = new empr_caddie($idcaddie);
		} else {			
			$myCart = new caddie($idcaddie);
		}
		$myCart->delete();
	break;
	case 'valid_new_cart':
		
		if($object_type=="EMPR") {
			$myCart = new empr_caddie(0);
		} else {			
			$myCart = new caddie(0);
		}
		$myCart->name = preg_replace('/\"|\'/', ' ', stripslashes($cart_name));
		$myCart->type = $cart_type;
		$myCart->comment = preg_replace('/\"|\'/', ' ', stripslashes($cart_comment));
		if (is_array($cart_autorisations)) $autorisations=implode(" ",$cart_autorisations);
				else $autorisations="";
		$myCart->autorisations = $autorisations;
		$myCart->create_cart();
	break;
}

switch ($object_type) {
	case "EXPL":
		require_once ("carts/exemplaire.inc.php");
		break;
	case "EMPR":
		require_once ("carts/empr.inc.php");
		break;
	case "BULL":
		require_once ("carts/bulletin.inc.php");
		break;
	case "NOTI":
	default:
		require_once ("carts/notice.inc.php");
		break;
}

print "<script>self.focus();</script>";

print $footer;
mysql_close($dbh);
