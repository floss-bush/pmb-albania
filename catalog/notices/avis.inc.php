<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: avis.inc.php,v 1.19 2009-11-30 10:39:25 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion des avis laisses par les lecteurs sur les notices

echo "<h1>".$msg[titre_avis]."</h1>";

if (!$montrerquoi) $montrerquoi='novalid' ;
if (!$nb_per_page) $nb_per_page=10;


//droits d'acces utilisateur/notice
$acces_jm='';
$acces_jl='';
if ($gestion_acces_active==1 && $gestion_acces_user_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom_1= $ac->setDomain(1);
	$acces_jm = $dom_1->getJoin($PMBuserid,8,'num_notice');	//modification
	$acces_jl = $dom_1->getJoin($PMBuserid,4,'num_notice');	//lecture
}


//action = VALIDER l'avis
switch ($quoifaire) {
	case 'valider':
		for ($i=0 ; $i < sizeof($valid_id_avis) ; $i++) {
			$acces_m=1;
			if ($acces_jm) {
				$q = "select count(1) from avis $acces_jm where id_avis=".$valid_id_avis[$i];
				$r = mysql_query($q, $dbh);
				if(mysql_result($r,0,0)==0) {
					$acces_m=0;
				}
			}
			if ($acces_m!=0) {
				$rqt = "update avis set valide=1 where id_avis='".$valid_id_avis[$i]."' ";
				mysql_query ($rqt, $dbh) ;
			}
		}	
		break;
	case 'invalider':
		for ($i=0 ; $i < sizeof($valid_id_avis) ; $i++) {
			$acces_m=1;
			if ($acces_jm) {
				$q = "select count(1) from avis $acces_jm where id_avis=".$valid_id_avis[$i];
				$r = mysql_query($q, $dbh);
				if(mysql_result($r,0,0)==0) {
					$acces_m=0;
				}
			}
			if ($acces_m!=0) {
				$rqt = "update avis set valide=0 where id_avis='".$valid_id_avis[$i]."' ";
				mysql_query ($rqt, $dbh) ;
			}
		}	
		break;
	case 'supprimer' :
		for ($i=0 ; $i < sizeof($valid_id_avis) ; $i++) {
			$acces_m=1;
			if ($acces_jm) {
				$q = "select count(1) from avis $acces_jm where id_avis=".$valid_id_avis[$i];
				$r = mysql_query($q, $dbh);
				if(mysql_result($r,0,0)==0) {
					$acces_m=0;
				}
			}
			if ($acces_m!=0) {
				$rqt = "delete from avis where id_avis='".$valid_id_avis[$i]."' ";
				mysql_query ($rqt, $dbh) ;
			}
		}	
		break;
	default:
		break;
}


echo "<form class='form-catalog' method='post' id='validation_avis' name='validation_avis' >
		<h3>".$msg[avis_titre_form]."</h3>
		<div class='form-contenu'>";

$aff_final .= "<div class='row'><span class='usercheckbox'><input type='radio' name='montrerquoi' value='novalid' id='novalid' onclick='this.form.submit();' ";
if ($montrerquoi=='novalid') $aff_final .= "checked" ;
$aff_final .= " /><label for='novalid'>".$msg['avis_show_novalid']."</label></span>&nbsp;<span class='usercheckbox'><input type='radio' name='montrerquoi' value='valid' id='valid' onclick='this.form.submit();' ";
if ($montrerquoi=='valid') $aff_final .= "checked" ;
$aff_final .= " /><label for='valid'>".$msg['avis_show_valid']."</label></span>&nbsp;<span class='usercheckbox'><input type='radio' name='montrerquoi' value='all' id='all' onclick='this.form.submit();' ";
if ($montrerquoi=='all') $aff_final .= "checked" ;
$aff_final .= " /><label for='all'>".$msg['avis_show_all']."</label></span></div>";

print $aff_final ;	


//variables
if(!$page) $page=1;
$debut =($page-1)*$nb_per_page;
$url_base = "./catalog.php?categ=avis&montrerquoi=$montrerquoi";

switch ($montrerquoi) {
	case 'all':
		$restrict = " 1 " ;
		break;
	case 'valid' :
		$restrict = " valide='1' " ;
		break;
	default:
	case 'novalid' :
		$restrict = " valide='0' " ;
	break;
}


//requete d'affichage des notices.titre et des commentaires
$requete = "select avis.note, avis.sujet, avis.commentaire, avis.id_avis, DATE_FORMAT(avis.dateAjout,'".$msg[format_date]."') as ladate, ";
$requete.= "empr_login, empr_nom, empr_prenom, ";
$requete.= "niveau_biblio, niveau_biblio, valide, notice_id ";
$requete.= "from avis "; 
$requete.= "left join empr on empr.id_empr=avis.num_empr "; 
$requete.= "left join notices on notices.notice_id=avis.num_notice ";
$requete.= "$acces_jl ";
$requete.= "where $restrict "; 
$requete.= "order by index_serie, tnvol, index_sew ,dateAjout desc ";
if(!$nbr_lignes) {
	$r = mysql_query($requete, $dbh) or die (mysql_error()." <br /><br />".$requete);
	$nbr_lignes=mysql_num_rows($r);
}
$requete.= "limit $debut, $nb_per_page";

$r = mysql_query($requete, $dbh) or die (mysql_error()." <br /><br />".$requete);
if (mysql_num_rows($r)) {	
	//affichage des notices
	print $begin_result_liste;
	$res_final = "";
	$notice_id=0;
	while ($loc = mysql_fetch_object($r)) {
		if ($notice_id!=$loc->notice_id) {
			if ($notice_id!=0) $res_final .=  "</ul><br />" ;
			$notice_id=$loc->notice_id;
			$deb = 1 ;
			if($loc->niveau_biblio != 's' && $loc->niveau_biblio != 'a') {
				// notice de monographie
				$link = './catalog.php?categ=isbd&id=!!id!!';
				$link_expl = './catalog.php?categ=edit_expl&id=!!notice_id!!&cb=!!expl_cb!!&expl_id=!!expl_id!!'; 
				$link_explnum = './catalog.php?categ=edit_explnum&id=!!notice_id!!&explnum_id=!!explnum_id!!'; 
				$display = new mono_display($loc->notice_id, 6, $link, 1, $link_expl, '', $link_explnum,1, 0, 1, 1);
				$res_final .= pmb_bidi($display->result);
			} else {
				// on a affaire à un périodique
				$link_serial = './catalog.php?categ=serials&sub=view&serial_id=!!id!!';
				$link_analysis = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!bul_id!!&art_to_show=!!id!!';
				$link_bulletin = './catalog.php?categ=serials&sub=bulletinage&action=view&bul_id=!!id!!';
				$link_explnum = "./catalog.php?categ=serials&sub=analysis&action=explnum_form&bul_id=!!bul_id!!&analysis_id=!!analysis_id!!&explnum_id=!!explnum_id!!";
				$serial = new serial_display($loc->notice_id, 6, $link_serial, $link_analysis, $link_bulletin, "", $link_explnum_serial, 0, 0, 1, 1, true, 1 );
				$res_final .= pmb_bidi($serial->result);
			}
			$res_final .=  "<ul>" ;
		} else {
			$deb = 0 ;
		}
		
		$res_final .= "<script type=\"text/javascript\" src='./javascript/dyn_form.js'></script>";   
		$res_final .= "<script type=\"text/javascript\" src='./javascript/http_request.js'></script>";  
		$res_final .=  "<li ><div id='avis_$loc->id_avis' onclick=\"make_form('".$loc->id_avis."');\"><div class='left'>" ;
		if (!$loc->valide) $res_final .=  "<font color='#CC0000'>".$msg[gestion_avis_note]." $loc->note <b>$loc->sujet</b></span></font>";
			else $res_final .=  "<font color='#00BB00'>".$msg[gestion_avis_note]." <span >$loc->note <b>$loc->sujet</b></span></font>";
		$res_final .=  ", ".$loc->ladate."  $loc->empr_prenom $loc->empr_nom </div>
			    <div class='right'>
			    	<input type='checkbox' name='valid_id_avis[]' id='valid_id_avis[]' value='$loc->id_avis' onClick=\"stop_evenement(event);\"/>
			    	</div>
				<div class='row'>
					$loc->commentaire
					</span></div></div><div id='update_$loc->id_avis'></div></li>";
	}
	$res_final .=  "</ul><br />" ;
	print $res_final ;
}

print aff_pagination ($url_base, $nbr_lignes, $nb_per_page, $page, 10, false, true) ;
echo "</div>";
echo "
		<div class='row'>
			<div class='left'>
				<input type='hidden' name='quoifaire' value='' />
				<input type='button' class='bouton' name='valider' value='".$msg[avis_bt_valider]."' onclick='this.form.quoifaire.value=\"valider\"; this.form.submit()' />&nbsp;
				<input type='button' class='bouton' name='invalider' value='".$msg[avis_bt_invalider]."' onclick='this.form.quoifaire.value=\"invalider\"; this.form.submit()' />&nbsp;
				<input type='button' class='bouton' name='supprimer' value='".$msg[avis_bt_supprimer]."' onclick='this.form.quoifaire.value=\"supprimer\"; this.form.submit()' />&nbsp;
				</div>
			<div class='right'>
				<input type='button' class='bouton' name='selectionner' value='".$msg[avis_bt_selectionner]."' onClick=\"setCheckboxes('validation_avis', 'valid_id_avis', true); return false;\" />&nbsp;
				</div>
			</div>
<div class='row'></div>
			</form>";
jscript_checkbox() ;
?>
		