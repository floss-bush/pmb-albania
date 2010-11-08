<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: bulletin.inc.php,v 1.12 2008-12-15 14:26:50 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if($item) {
	$bull=new bulletinage_display($item);
	$aff_bull=$bull->header;
	
	print pmb_bidi($aff_bull);
	switch($action) {
		case 'add_item':
			if($idcaddie)$caddie[0]=$idcaddie;
			foreach($caddie  as $idcaddie) {
				$myCart = new caddie($idcaddie);
				if($include_child) {					
					$tab_list_child=notice::get_list_child($item);
					if(count($tab_list_child))
					foreach ($tab_list_child as $notice_id) {
						$myCart->add_item($notice_id,"BULL",$what);					
					}		
				} else	$myCart->add_item($item,"BULL",$what);
				$myCart->compte_items();
			}	
			print "<script type='text/javascript'>window.close();</script>"; 
			break;
		case 'new_cart':
			$select_cart="
			<select name='cart_type'>
				<option value='NOTI'>$msg[caddie_de_NOTI]</option>
				<option value='EXPL'>$msg[caddie_de_EXPL]</option>
				<option value='BULL' selected>$msg[caddie_de_BULL]</option>
			</select>";
		 	$c_form=str_replace('!!cart_type_select!!', $select_cart, $cart_form);
			print $c_form;
			break;
		case 'del_cart':
		case 'valid_new_cart':		
		default:
			print $aff;
			aff_paniers($item, "BULL", "./cart.php?&what=$what", "add_item", $msg["caddie_add_BULL"], "", 0, 1, 1);
			break;
		}
} else {
	print "<h1>".$msg["fonct_no_accessible"]."</h1>";
}

