<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: integre.inc.php,v 1.10 2011-03-29 08:08:26 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//Recherche de la fonction auxiliaire d'intégration
if ($z3950_import_modele) {
	require_once($base_path."/catalog/z3950/".$z3950_import_modele);
} else require_once($base_path."/catalog/z3950/func_other.inc.php");

require_once($class_path."/notice_doublon.class.php");
switch ($action) {
	case "record":
		if($item) {
			$infos = entrepot_to_unimarc($item);
		}	
		//on regarde si la signature existe déjà..;
		$signature = "";
		if(!$force){
			if($pmb_notice_controle_doublons != 0){
				$sign = new notice_doublon(true);
				$signature = $sign->gen_signature();
				$requete="select signature, niveau_biblio ,niveau_hierar ,notice_id from notices where signature='$signature' limit 1";
				$res = mysql_query($requete);
				if(mysql_num_rows($res)){
					if (($r=mysql_fetch_object($res))) {
						//affichage de l'erreur, en passant tous les param postes (serialise) pour l'eventuel forcage 	
						$tab='';
						$tab->POST = $_POST;
						$tab->GET = $_GET;
						$force_url= htmlentities(serialize($tab), ENT_QUOTES,$charset);
						require_once("$class_path/mono_display.class.php");
					
						print "
						<br /><div class='erreur'>$msg[540]</div>
						<script type='text/javascript' src='./javascript/tablist.js'></script>
						<div class='row'>
							<div class='colonne10'>
								<img src='./images/error.gif' align='left'>
							</div>
							<div class='colonne80'>
								<strong>".$msg["gen_signature_erreur_similaire"]."</strong>
							</div>
						</div>
						<div class='row'>
							<form class='form-$current_module' name='dummy'  method='post' action='./catalog.php?categ=search&mode=7&sub=integre&action=record&item=$item&force=1'>
								<input type='hidden' name='forcage' value='1'>
								<input type='hidden' name='signature' value='$signature'>
								<input type='hidden' name='force_url' value='$force_url'>
								<input type='hidden' name='serialized_search' value='".htmlentities($sc->serialize_search(),ENT_QUOTES,$charset)."'/>
								<input type='button' name='ok' class='bouton' value='".$msg["connecteurs_back_to_list"]."' onClick='document.forms.dummy.action = \"./catalog.php?categ=search&mode=7&sub=launch\";document.forms.dummy.submit(); '>
								<input type='submit' class='bouton' name='bt_forcage' value=' ".htmlentities($msg["gen_signature_forcage"], ENT_QUOTES)." '>
							</form>
							
						</div>
						";
						if (($notice->niveau_biblio =='s' || $r->niveau_biblio =='a') && ($r->niveau_hierar== 1 || $r->niveau_hierar== 2)) {
							$link_serial = './catalog.php?categ=serials&sub=view&serial_id=!!id!!';
							$link_analysis = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!bul_id!!&art_to_show=!!id!!';
							$link_bulletin = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!id!!';
							$link_explnum = "./catalog.php?categ=serials&sub=analysis&action=explnum_form&bul_id=!!bul_id!!&analysis_id=!!analysis_id!!&explnum_id=!!explnum_id!!";
							$serial = new serial_display($r->notice_id, 6, $link_serial, $link_analysis, $link_bulletin, "", $link_explnum, 0, 0,1, 1);
							$notice_display =  pmb_bidi($serial->result);
						} elseif ($r->niveau_biblio=='m' && $r->niveau_hierar== 0) { 
							$link = './catalog.php?categ=isbd&id=!!id!!';
							$link_expl = './catalog.php?categ=edit_expl&id=!!notice_id!!&cb=!!expl_cb!!&expl_id=!!expl_id!!'; 
							$link_explnum = './catalog.php?categ=edit_explnum&id=!!notice_id!!&explnum_id=!!explnum_id!!'; 
							$display = new mono_display($r->notice_id, 6, $link, 1, $link_expl, '', $link_explnum,1, 0, 1, 1,"", 1, false, true);
							$notice_display = pmb_bidi($display->result);
				        } elseif ($r->niveau_biblio=='b' && $r->niveau_hierar==2) { // on est face à une notice de bulletin
				        	$requete_suite = "SELECT bulletin_id, bulletin_notice FROM bulletins where num_notice='".$r->notice_id."'";
				        	$result_suite = mysql_query($requete_suite, $dbh) or die("<br /><br />".mysql_error()."<br /><br />");
				        	$notice_suite = mysql_fetch_object($result_suite);
				        	$r->bulletin_id=$notice_suite->bulletin_id;
				        	$r->bulletin_notice=$notice_suite->bulletin_notice;
							$link_bulletin = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id='.$r->bulletin_id;
							$display = new mono_display($r->notice_id, 6, $link_bulletin, 1, $link_expl, '', $link_explnum,1, 0, 1, 1, "", 1);
							$notice_display = $display->result;
						}
				
						echo "
						<div class='row'>
						$notice_display
				 	    </div>
						<script>document.getElementById('el".$r->notice_id."Child').setAttribute('startOpen','Yes');</script>
						<script type='text/javascript'>document.forms['dummy'].elements['ok'].focus();</script>";	
						exit();
					}	
				}
			}
		}else{
			$tab= unserialize(stripslashes($force_url));
			foreach($tab->GET as $key => $val){
				if (get_magic_quotes_gpc())
					$GLOBALS[$key] = $val;
				else {
					add_sl($val);
					$GLOBALS[$key] = $val;
				}  
			}	
			foreach($tab->POST as $key => $val){
				if (get_magic_quotes_gpc())
					$GLOBALS[$key] = $val;
				else {
					add_sl($val);
					$GLOBALS[$key] = $val;
				}
			}
			
		}
		
		//on intègre...
		$z=new z3950_notice("form");
		//on reporte la signature de la notice calculée ou non...
		$z->signature = $signature;
		if($infos['notice']) $z->notice = $infos['notice'];
		if($infos['source_id']) $z->source_id = $infos['source_id'];

		if (isset($notice_id))
			$ret=$z->update_in_database($notice_id);
		else
			$ret=$z->insert_in_database();
		
		//on conserve la trace de l'origine de la notice...
		$id_notice = $ret[1];
		$rqt = "select recid from external_count where rid = '$item'";
		$res = mysql_query($rqt);
		if(mysql_num_rows($res)) $recid = mysql_result($res,0,0);
		$req= "insert into notices_externes set num_notice = '".$id_notice."', recid = '".$recid."'";
		mysql_query($req);
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
		$infos=entrepot_to_unimarc($item);
		if ($infos['notice']) {
			//regardons si on ne l'a pas déjà traité
			$rqt = "select recid from external_count where rid = '$item'";
			$res = mysql_query($rqt);
			if(mysql_num_rows($res)) $recid = mysql_result($res,0,0);
			$req = "select num_notice from notices_externes where recid like '$recid'";
			$res = mysql_query($req);
			if(mysql_num_rows($res)){
				$integrate = true;
				$id_notice = mysql_result($res,0,0);
				$requete = "SELECT * FROM notices where notice_id = '".$id_notice."'";
				$result = mysql_query($requete, $dbh);
				if(mysql_num_rows($result)){
					$notice = mysql_fetch_object($result);
					if (($notice->niveau_biblio =='s' || $notice->niveau_biblio =='a') && ($notice->niveau_hierar== 1 || $notice->niveau_hierar== 2)) {
						$link_serial = './catalog.php?categ=serials&sub=view&serial_id=!!id!!';
						$link_analysis = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!bul_id!!&art_to_show=!!id!!';
						$link_bulletin = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!id!!';
						$link_explnum = "./catalog.php?categ=serials&sub=analysis&action=explnum_form&bul_id=!!bul_id!!&analysis_id=!!analysis_id!!&explnum_id=!!explnum_id!!";
						$serial = new serial_display($notice, 6, $link_serial, $link_analysis, $link_bulletin, "", $link_explnum, 0, 0,1, 1);
						$notice_display =  pmb_bidi($serial->result);
					} elseif ($notice->niveau_biblio=='m' && $notice->niveau_hierar== 0) { 
						$link = './catalog.php?categ=isbd&id=!!id!!';
						$link_expl = './catalog.php?categ=edit_expl&id=!!notice_id!!&cb=!!expl_cb!!&expl_id=!!expl_id!!'; 
						$link_explnum = './catalog.php?categ=edit_explnum&id=!!notice_id!!&explnum_id=!!explnum_id!!'; 
						// function mono_display($id, $level=1, $action='', $expl=1, $expl_link='', $lien_suppr_cart="", $explnum_link='', $show_resa=0, $print=0, $show_explnum=1, $show_statut=0, $anti_loop='', $draggable=0, $no_link=false, $show_opac_hidden_fields=true ) {
						$display = new mono_display($notice, 6, $link, 1, $link_expl, '', $link_explnum,1, 0, 1, 1,"", 1, false, true);
						$notice_display = pmb_bidi($display->result);
			        } elseif ($notice->niveau_biblio=='b' && $notice->niveau_hierar==2) { // on est face à une notice de bulletin
			        	$requete_suite = "SELECT bulletin_id, bulletin_notice FROM bulletins where num_notice='".$notice->notice_id."'";
			        	$result_suite = mysql_query($requete_suite, $dbh) or die("<br /><br />".mysql_error()."<br /><br />");
			        	$notice_suite = mysql_fetch_object($result_suite);
			        	$notice->bulletin_id=$notice_suite->bulletin_id;
			        	$notice->bulletin_notice=$notice_suite->bulletin_notice;
						$link_bulletin = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id='.$notice->bulletin_id;
						$display = new mono_display($notice, 6, $link_bulletin, 1, $link_expl, '', $link_explnum,1, 0, 1, 1, "", 1);
						$notice_display = $display->result;
					}
				}
			}else $integrate = false;
			
			if($integrate == false || $force==1){
				$z=new z3950_notice("unimarc",$infos['notice'],$infos['source_id']);
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
			}else{
				$tab->POST = $_POST;
				$tab->GET = $_GET;
				$force_url= htmlentities(serialize($tab), ENT_QUOTES,$charset);
				
				print "<br /><br />
				<div class='erreur'>$msg[540]</div>
					<div class='row'>
						<div class='colonne10'>
							<img src='./images/error.gif' align='left'>
							</div>
						<div class='colonne80'>
							<strong>".$msg['external_notice_already_integrate']."</strong>
						</div>
					</div>
					<div class='row'>$notice_display</div>
					<script src='$javascript_path/tablist.js'></script>
					<div class='row'>
						<form class='form-$current_module' name='dummy' method='post' action='./catalog.php?categ=search&mode=7&sub=integre&item=$item&force=1'>
							<input type='hidden' name='serialized_search' value='".htmlentities($sc->serialize_search(),ENT_QUOTES,$charset)."'/>
							<input type='button' name='ok' class='bouton' value=\" ".$msg['external_integrate_back']." \" onClick='history.go(-1);'>
							<input type='submit' name='force_button' class='bouton' value=\" ".$msg['external_force_integration']." \">
						</form>
						<script type='text/javascript'>document.forms['dummy'].elements['ok'].focus();</script>
					</div>
				</div>";
			}
		} else {
			error_message_history($msg["connecteurs_unable_to_convert_title"], $msg["connecteurs_unable_to_convert"], 1);
		}
		break;
}
?>
