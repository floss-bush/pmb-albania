<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice.inc.php,v 1.11 2009-05-16 11:11:53 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if($item) {
	print "<h1>".$msg["400"]."</h1>";
	$notice = new mono_display($item,1);
	print pmb_bidi('<strong>'.$notice->header.'</strong><br />');
 }
switch($action) {
	case 'add_item':
		// cas du click sur le lien du panier
		if($idcaddie)$caddie[0]=$idcaddie;
		// Pour tous les paniers cochés
		foreach($caddie  as $idcaddie) {
			$myCart = new caddie($idcaddie);
			if($include_child) {					
				$tab_list_child=notice::get_list_child($item);
				if(count($tab_list_child))
				foreach ($tab_list_child as $notice_id) {
					$myCart->add_item($notice_id,"NOTI");					
				}		
			} else	$myCart->add_item($item,"NOTI");
			$myCart->compte_items();
		}
		print "<script type='text/javascript'>window.close();</script>"; 
	break;
	case 'new_cart':
	 	$select_cart="
		<select name='cart_type'>
			<option value='NOTI' selected>$msg[caddie_de_NOTI]</option>
			<option value='EXPL'>$msg[caddie_de_EXPL]</option>
			<option value='BULL'>$msg[caddie_de_BULL]</option>
		</select>
		<input type='hidden' name='current_print' value='$current_print'/>";
	 	$c_form=str_replace('!!cart_type_select!!', $select_cart, $cart_form);
		print $c_form;
	break;
	case 'del_cart':
	case 'valid_new_cart':		
	default:
		if($current_print) {
			$action="print_prepare";
			require_once("./print_cart.php");
			
		} else {
			print pmb_bidi($notice->header);
			aff_paniers($item, "NOTI", "./cart.php?", "add_item", $msg["caddie_add_EXPL"], "", 0, 1, 1);
		}	
	break;
}
