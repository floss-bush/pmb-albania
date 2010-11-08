<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: douchette_ajax.inc.php,v 1.1 2008-01-25 15:00:25 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if($idcaddie) {
	$myCart = new caddie($idcaddie);
	switch ($action) {
		case 'add_item':
			$param->form_cb_expl=$form_cb_expl;
			if($form_cb_expl) {
				$expl_ajout_ok = 1 ;
				$query = "select expl_id from exemplaires where expl_cb='$form_cb_expl'";
				$result = mysql_query($query, $dbh);
				if(!mysql_num_rows($result)) {
					// exemplaire inconnu
					$param->message_ajout_expl =  $msg[367];
					$expl_ajout_ok = 0 ;
				} else {
					$expl_trouve = mysql_fetch_object($result);
					$item = $expl_trouve->expl_id;
					$param->expl_id=$expl_trouve->expl_id;
					
					if($stuff = get_expl_info($item)) {
						$param->expl_notice=$stuff->expl_notice;
						$param->titre=$stuff->titre;
						$stuff = check_pret($stuff);
					} else {
						$param->message_ajout_expl = $msg[395];
						$expl_ajout_ok = 0 ;
					}
				}
			}
			$res_ajout = $myCart->pointe_item($item,"EXPL", $form_cb_expl, "EXPL_CB" );
			
			// form de saisie cb exemplaire
			if ($expl_ajout_ok) {
				if ($res_ajout==CADDIE_ITEM_OK) {
					$param->message_ajout_expl = $msg["caddie_".$myCart->type."_pointe"];					
				}
				if ($res_ajout==CADDIE_ITEM_NULL) {
					$param->message_ajout_expl = $msg[caddie_item_null];
				}
				if ($res_ajout==CADDIE_ITEM_IMPOSSIBLE_BULLETIN) {
					$param->message_ajout_expl = $msg[caddie_pointe_item_impossible_bulletin];
				}	
				if ($res_ajout==CADDIE_ITEM_INEXISTANT) {
					$param->message_ajout_expl = $msg[caddie_pointe_inconnu_panier];
				}	
			} 			
		break;
		default:
		break;
	}
} 
$param->nb_item=$myCart->nb_item;
$param->nb_item_pointe=$myCart->nb_item_pointe;
$param->nb_item_base=$myCart->nb_item_base;
$param->nb_item_base_pointe=$myCart->nb_item_base_pointe;
$param->nb_item_blob=$myCart->nb_item_blob;
$param->nb_item_blob_pointe=$myCart->nb_item_blob_pointe;

$array[0]=$param;
$buf_xml = array2xml($array);		
ajax_http_send_response("$buf_xml","text/xml");
