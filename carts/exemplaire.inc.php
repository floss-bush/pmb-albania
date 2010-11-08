<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: exemplaire.inc.php,v 1.13 2009-05-16 11:11:53 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if($item) {
	$requete = "SELECT expl_notice, expl_bulletin FROM exemplaires WHERE expl_id='$item' ";
	$result = @mysql_query($requete, $dbh);
	if(mysql_num_rows($result)) {
		$temp = mysql_fetch_object($result);
		$expl = get_expl_info($item,0);
		$aff_reduit = $msg[376]."&nbsp;".$expl->expl_cb." ".$expl->aff_reduit ;
		if  ($temp->expl_notice) {
			$notice = new mono_display($temp->expl_notice, 1, '', 0);
			$aff = $notice->isbd;
			} else {
				$bl = new bulletinage_display($temp->expl_bulletin);
				$aff = $bl->display;
				}
		} else {
			$aff = $msg["info_ex_introuvables"];
			$aff_reduit = $msg["info_ex_introuvables"];
			}
	$expl = get_expl_info($item);
	// informations de localisation
	$aff.= "<div class=\"row\">";
	$aff.= "<u>".$msg[298]."</u>&nbsp;:&nbsp;".$expl->location_libelle.'<br />';
	$aff.= "<u>".$msg[295]."</u>&nbsp;:&nbsp;".$expl->section_libelle.'<br />';
	$aff.= "<u>".$msg[296]."</u>&nbsp;:&nbsp;".$expl->expl_cote.'<br />';
	$aff.= "<u>".$msg[297]."</u>&nbsp;:&nbsp;".$expl->statut_libelle;
	$aff.= "</div>";

	print '<strong>'.pmb_bidi($aff_reduit).'</strong><br />';
	switch($action) {
		case 'add_item':
			if($idcaddie)$caddie[0]=$idcaddie;		
			foreach($caddie  as $idcaddie) {
				$myCart = new caddie($idcaddie);
				if($include_child) {					
				$tab_list_child=notice::get_list_child($item);
				if(count($tab_list_child))
					foreach ($tab_list_child as $notice_id) {
						$myCart->add_item($notice_id,"EXPL");					
					}		
				} else	$myCart->add_item($item,"EXPL");
				$myCart->compte_items();
			}	
			print "<script type='text/javascript'>window.close();</script>"; 
			break;
		case 'new_cart':
			$select_cart="
			<select name='cart_type'>
				<option value='NOTI'>$msg[caddie_de_NOTI]</option>
				<option value='EXPL'selected >$msg[caddie_de_EXPL]</option>
				<option value='BULL'>$msg[caddie_de_BULL]</option>
			</select>";
		 	$c_form=str_replace('!!cart_type_select!!', $select_cart, $cart_form);
			print $c_form;
			break;
		case 'del_cart':
		case 'valid_new_cart':		
		default:
			print pmb_bidi($aff);
			aff_paniers($item, "EXPL", "./cart.php?", "add_item", $msg["caddie_add_EXPL"], "", 0, 1, 1);
			break;
		}
	} else {
		print "<h1>".$msg["fonct_no_accessible"]."</h1>";
		}

