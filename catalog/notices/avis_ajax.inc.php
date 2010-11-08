<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: avis_ajax.inc.php,v 1.3 2009-06-18 19:03:12 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

switch($quoifaire){
	
	case 'show_form':
		show_form($id);	
	break;
	
	case 'update_avis':
		update_avis($id);
	break;
}


function show_form($id){
	global $dbh, $msg, $charset;
	
	$req = "select sujet, commentaire from avis where id_avis='".$id."'";
	$res = mysql_query($req,$dbh);
	while(($avis = mysql_fetch_object($res))){
		$sujet = $avis->sujet;
		$desc = $avis->commentaire;		
	}
	
	$display .= "<div class='row'><label class='etiquette'>$msg[avis_sujet]</label> <input type='texte' class='saisie-20em' name='field_sujet_$id' id='field_sujet_$id' value='".htmlentities($sujet,ENT_QUOTES,$charset)."' /></div>
				<div class='row'><label class='etiquette' >$msg[avis_comm]</label> <textarea style='vertical-align:top' id='avis_desc_$id' name='avis_desc_$id' cols='50' rows='4'>".htmlentities($desc,ENT_QUOTES,$charset)."</textarea></div>
				<input type='button' class='bouton_small' name='save_avis_$id' id='save_avis_$id' value='$msg[avis_save]' />
				";
	print $display;
}


function update_avis($id){
	global $dbh,$desc, $sujet, $msg, $charset;
	
	$req = "update avis set sujet='".$sujet."', commentaire='".$desc."' where id_avis='".$id."'";
	mysql_query($req,$dbh);
	
	$requete = "select avis.note, avis.sujet, avis.commentaire, avis.id_avis, DATE_FORMAT(avis.dateAjout,'".$msg[format_date]."') as ladate, ";
	$requete.= "empr_login, empr_nom, empr_prenom, ";
	$requete.= "niveau_biblio, niveau_biblio, valide, notice_id ";
	$requete.= "from avis "; 
	$requete.= "left join empr on empr.id_empr=avis.num_empr "; 
	$requete.= "left join notices on notices.notice_id=avis.num_notice ";
	$requete.= "where id_avis='".$id."'"; 
	$requete.= "order by index_serie, tnvol, index_sew ,dateAjout desc ";
	$res = mysql_query($requete,$dbh);
	while(($loc = mysql_fetch_object($res))){
		$display = "<div class='left'>" ;
		if (!$loc->valide) $display .=  "<font color='#CC0000'>".$msg[gestion_avis_note]." $loc->note <b>".htmlentities($loc->sujet,ENT_QUOTES, $charset)."</b></span></font>";
		else $display .=  "<font color='#00BB00'>".$msg[gestion_avis_note]." <span >".htmlentities($loc->note,ENT_QUOTES,$charset)." <b>".htmlentities($loc->sujet,ENT_QUOTES,$charset)."</b></span></font>";
		$display .=  ", ".htmlentities($loc->ladate,ENT_QUOTES,$charset)." ".htmlentities($loc->empr_prenom." ".$loc->empr_nom,ENT_QUOTES,$charset)." </div>
				    <div class='right'>
				    	<input type='checkbox' name='valid_id_avis[]' id='valid_id_avis[]' value='$loc->id_avis' onClick=\"stop_evenement(event);\" />
				    	</div>
					<div class='row'>".htmlentities($loc->commentaire,ENT_QUOTES,$charset)."						
						</span></div>
		";
	}
	
	print $display;
}
?>