<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: integre.inc.php,v 1.8 2009-11-30 10:39:25 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//Recherche de la fonction auxiliaire d'intégration
if ($z3950_import_modele) {
	require_once($base_path."/catalog/z3950/".$z3950_import_modele);
} else require_once($base_path."/catalog/z3950/func_other.inc.php");


switch ($action) {
	case "record":
		$z=new z3950_notice("form");
		if($item) {
			$notice=entrepot_to_unimarc($item);
			if($notice) $z->notice = $notice;
		}		
		if (isset($notice_id))
			$ret=$z->update_in_database($notice_id);
		else
			$ret=$z->insert_in_database();
		if ($ret[0]) {
			if($z->bull_id && $z->perio_id){
				$notice_display=new serial_display($ret[1],6);
			} else $notice_display=new mono_display($ret[1],6);
			$retour = "
			<script src='javascript/tablist.js'></script>
			<br /><div class='erreur'></div>
			<div class='row'>
				<div class='colonne10'>
					<img src='./images/error.gif' align='left'>
				</div>
				<div class='colonne80'>
					<strong>".(isset($notice_id) ? $msg["notice_connecteur_remplaced_ok"] : $msg["z3950_integr_not_ok"])."</strong>
					".$notice_display->result."
				</div>
			</div>";
			if($z->bull_id && $z->perio_id)
				$url_view = "./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=$z->bull_id&art_to_show=$ret[1]";
			else $url_view = "./catalog.php?categ=isbd&id=".$ret[1];
			$retour .= "
				<div class='row'>
				<form class='form-$current_module' name='dummy' method=\"post\" action=\"catalog.php?categ=search&mode=7&sub=launch\">
					<input type='hidden' name='serialized_search' value='".htmlentities($sc->serialize_search(),ENT_QUOTES,$charset)."'/>
					<input type='hidden' name='page' value='".htmlentities($page,ENT_QUOTES,$charset)."'/>	
					<input type='submit' name='ok' class='bouton' value='".$msg["connecteurs_back_to_list"]."' />&nbsp;
					<input type='button' name='cancel' class='bouton' value='".$msg["z3950_integr_not_lavoir"]."' onClick=\"document.location='$url_view'\"/>
				</form>
				<script type='text/javascript'>
					document.forms['dummy'].elements['ok'].focus();
				</script>
				</div>
			";
			print $retour;
		} else if ($ret[1]){
			if($z->bull_id && $z->perio_id){
				$notice_display=new serial_display($ret[1],6);
			} else $notice_display=new mono_display($ret[1],6);
			$retour = "
			<script src='javascript/tablist.js'></script>
			<br /><div class='erreur'>$msg[540]</div>
			<div class='row'>
				<div class='colonne10'>
					<img src='./images/error.gif' align='left'>
				</div>
				<div class='colonne80'>
					<strong>".($msg["z3950_integr_not_existait"])."</strong><br /><br />
					".$notice_display->result."
				</div>
			</div>";
			if($z->bull_id && $z->perio_id)
				$url_view = "./catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=$z->bull_id&art_to_show=$ret[1]";
			else $url_view = "./catalog.php?categ=isbd&id=".$ret[1];
			$retour .= "
			<div class='row'>
			<form class='form-$current_module' name='dummy' method=\"post\" action=\"catalog.php?categ=search&mode=7&sub=launch\">
				<input type='hidden' name='serialized_search' value='".htmlentities($sc->serialize_search(),ENT_QUOTES,$charset)."'/>
				<input type='hidden' name='page' value='".htmlentities($page,ENT_QUOTES,$charset)."'/>	
				<input type='submit' name='ok' class='bouton' value='".$msg["connecteurs_back_to_list"]."' />&nbsp;
				<input type='button' name='cancel' class='bouton' value='".$msg["z3950_integr_not_lavoir"]."' onClick=\"document.location='$url_view'\"/>
			</form>
			<script type='text/javascript'>
				document.forms['dummy'].elements['ok'].focus();
			</script>
			</div>
			";
			print $retour;
		}
		else {
			$retour = "<script src='javascript/tablist.js'></script>";
			$retour .= form_error_message($msg["connecteurs_cant_integrate_title"], ($ret[1]?$msg["z3950_integr_not_existait"]:$msg["z3950_integr_not_newrate"]), $msg["connecteurs_back_to_list"], "catalog.php?categ=search&mode=7&sub=launch",array("serialized_search"=>$sc->serialize_search()));
			print $retour;
		}
		break;
	default:
		if (isset($notice_id))
			$notice_id_info = "&notice_id=".$notice_id;
		else
			$notice_id_info = "";

			//Construction de la notice UNIMARC
		$notice=entrepot_to_unimarc($item);
		if ($notice) {
			$z=new z3950_notice("unimarc",$notice);
			$z->libelle_form = isset($notice_id) ? $msg[notice_connecteur_remplace_catal] : '';
			if($z->bibliographic_level == "a" && $z->hierarchic_level=="2"){
				$form=$z->get_form("catalog.php?categ=search&mode=7&sub=integre&action=record".$notice_id_info."&item=$item",0,true,true);
			} else{
				$form=$z->get_form("catalog.php?categ=search&mode=7&sub=integre&action=record".$notice_id_info."&item=$item",0,true);
			}
			if (isset($notice_id)) {
				$form=str_replace("<!--!!form_title!!-->","<h3>".sprintf($msg["notice_replace_external_action"],$notice_id, $item)."</h3>",$form);
			}
			else 
				$form=str_replace("<!--!!form_title!!-->","<h3>".sprintf($msg["connecteurs_integrate"],$item)."</h3>",$form);
			$form=str_replace("<!--form_suite-->","<input type='hidden' name='serialized_search' value='".htmlentities($sc->serialize_search(),ENT_QUOTES,$charset)."'/><input type='hidden' name='page' value='".htmlentities($page,ENT_QUOTES,$charset)."'/>",$form);
			print $form;
		} else {
			error_message_history($msg["connecteurs_unable_to_convert_title"], $msg["connecteurs_unable_to_convert"], 1);
		}
		break;
}
?>
