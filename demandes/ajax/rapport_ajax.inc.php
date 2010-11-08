<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: rapport_ajax.inc.php,v 1.5 2010-02-23 16:27:22 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

switch($quoifaire){
	
	case 'add_note';
		add_note($idnote);
	break;
	case 'up_order':
		update_order();	
	break;
	case 'show_addcom':
		show_addcom($id);	
	break;
	case 'update_com':
		update_contenu($id);
	break;
	case 'del_item':
		del_item($id_item);
	break;
}

/*
 * Ajout d'une note au rapport
 */
function add_note($idnote=0){
	global $dbh, $base_path, $idtype, $idobject, $comment, $ordre_cible;
	
	$iddemande = $idobject;
	$commentaire = trim($comment);
	
	if($ordre_cible){
		$ordre = $ordre_cible;
	} else {
		$req = "select max(ordre)+1 from rapport_demandes where num_demande='".$iddemande."'";
		$res = mysql_query($req,$dbh);
		$ordre = mysql_result($res,0,0);
	}
	
	$req = "insert into rapport_demandes set 
		contenu='".$commentaire."',
		num_demande='".$iddemande."',
		num_note='".$idnote."',
		ordre = '".$ordre."',
		type='".$idtype."'
		";
	mysql_query($req,$dbh);
	
	if($ordre_cible) update_order(mysql_insert_id());
	
	$req = "select rd.id_item, rd.contenu, rd.ordre, rd.type, rd.num_note, sujet_action from rapport_demandes rd left join demandes_notes on num_note=id_note left join demandes_actions on num_action=id_action where rd.num_demande='".$iddemande."' order by ordre";
	$res = mysql_query($req,$dbh);
	$display = "";
	while(($item = mysql_fetch_object($res))){
		$titre = substr($item->contenu,0,15)."...";	
		$style="";
		if(!$item->num_note){
			//Ajout manuel
			switch ($item->type) {
				case '1':
					//Titre
					$style = "style='background-color:#DECDEC' titre='yes'";
					$content= $item->contenu;
				break;
				case '0':
					//Commmentaire
					$content= "* ".$item->contenu;
				break;
			}
		} else $content= $item->contenu;	
		$ordre = $item->ordre;
		
		if($item->sujet_action)
			$contenu = "<u>".$item->sujet_action."</u> : ".$content;
		else $contenu = $content;
			
		$drag = "<span id=\"rap_handle_$item->id_item\" style='padding-left:7px'  ><img src=\"".$base_path."/images/notice_drag.png\" /></span>";
		$del = "<span id=\"rap_del_$item->id_item\" style='padding-left:7px' onclick='delete_item($item->id_item);' ><img src=\"".$base_path."/images/cross.png\" style='cursor:pointer;width:10px;vertical-align:middle;' /></span>";
		$modif = "<span id=\"rap_modif_$item->id_item\" style='padding-left:7px;' onclick='modif_item($item->id_item);' ><img src=\"".$base_path."/images/b_edit.png\" style='cursor:pointer;width:10px;vertical-align:middle;'/></span>";
		$display .= "
					<div class='row' $style id='rap_drag_$item->id_item' draggable=\"yes\" dragtype=\"rapport\" dragtext=\"$titre\" dragicon=\"".$base_path."/images/icone_drag_notice.png\"
						handler=\"rap_handle_$item->id_item\" recepttype=\"rapport\" recept=\"yes\" highlight=\"rap_highlight\" downlight=\"rap_downlight\" iditem='$item->id_item' order='$ordre'>".$contenu.$drag.$modif.$del."</div>			
				";
	}

	ajax_http_send_response($display);
}


/*
 * Mise à jour de l'ordre des notes
 */
function update_order($idinsert=0){
	
	global $dbh,$idsource,$ordre_source,$ordre_cible;

	if(($ordre_source > $ordre_cible) && !$idinsert) {
		$req = "update rapport_demandes set ordre='".$ordre_cible."' where id_item='".$idsource."'";
		mysql_query($req,$dbh);
		$req = "update rapport_demandes set ordre=ordre+1 where (ordre <= '".$ordre_source."' and ordre >='".$ordre_cible."') and id_item!='".$idsource."' ";
		mysql_query($req,$dbh);
	} else if(($ordre_source < $ordre_cible) && !$idinsert){
		$req = "update rapport_demandes set ordre='".($ordre_cible-1)."' where id_item='".$idsource."'";
		mysql_query($req,$dbh);
		$req = "update rapport_demandes set ordre=ordre-1 where (ordre >= '".$ordre_source."' and ordre <='".($ordre_cible-1)."') and id_item!='".$idsource."' ";
		mysql_query($req,$dbh);
	} else if($idinsert && $ordre_cible && $idinsert){
		//Insertion d'un élément nouveau dans la liste donc on a idinsert et pas idsource
		$req = "update rapport_demandes set ordre='".$ordre_cible."' where id_item='".$idinsert."'";
		mysql_query($req,$dbh);
		$req = "update rapport_demandes set ordre=ordre+1 where ordre >='".$ordre_cible."' and id_item!='".$idinsert."' ";
		mysql_query($req,$dbh);
	}
	
}

/*
 * Affiche le formulaire de saisie d'un titre ou d'une note
 */
function show_addcom($id=0){
	global $msg, $dbh;
	
	$contenu="";
	if($id){
		$req = "select contenu from rapport_demandes where id_item='".$id."'";
		$res = mysql_query($req,$dbh);
		$rap = mysql_fetch_object($res);
		
		$contenu = $rap->contenu;
	}
	
	$display = "
		<div class='row'>
			<textarea name='comment' id='comment' rows='3' cols='50'/>".$contenu."</textarea>
		</div>
		<div class='row'>
			<input type='button'  id='cancel_com' name='cancel_com' class='bouton' value='".$msg[76]."' />
			<input type='button' id='save_com' name='save_com' class='bouton' value='".$msg[77]."' />
		</div>
	";
	
	ajax_http_send_response($display);
}

/*
 * Suppression d'un élément du rapport
 */
function del_item($id=0){
	global $dbh;
	
	if($id){
		$req = "delete from rapport_demandes where id_item='".$id."'";
		mysql_query($req,$dbh); 
	}
}

/*
 * Mis à jour de la note du rapport
 */
function update_contenu($id){
	global $dbh, $comment, $idobject, $base_path, $charset;
	
	if($id){
		$req = "update rapport_demandes set contenu='".$comment."' where id_item='".$id."'";
		mysql_query($req,$dbh);
	
		//$req = "select id_item, contenu, ordre, type, num_note from rapport_demandes where num_demande='".$idobject."' order by ordre";
		$req = "select rd.id_item, rd.contenu, rd.ordre, rd.type, rd.num_note, sujet_action from rapport_demandes rd left join demandes_notes on num_note=id_note left join demandes_actions on num_action=id_action where rd.num_demande='".$idobject."' order by ordre";
		$res = mysql_query($req,$dbh);
		$display = "";
		while(($item = mysql_fetch_object($res))){
			$titre = substr($item->contenu,0,15)."...";	
			$style="";
			if(!$item->num_note){
				//Ajout manuel
				switch ($item->type) {
					case '1':
						//Titre
						$style = "style='background-color:#DECDEC' titre='yes'";
						$content= $item->contenu;
					break;
					case '0':
						//Commmentaire
						$content= "* ".$item->contenu;
					break;
				}
			} else $content= $item->contenu;	
			$ordre = $item->ordre;
			
			if($item->sujet_action)
				$contenu = "<u>".$item->sujet_action."</u> : ".$content;
			else $contenu = $content;
			
			$drag = "<span id=\"rap_handle_$item->id_item\" style='padding-left:7px'  ><img src=\"".$base_path."/images/notice_drag.png\" /></span>";
			$del = "<span id=\"rap_del_$item->id_item\" style='padding-left:7px' onclick='delete_item($item->id_item);' ><img src=\"".$base_path."/images/cross.png\" style='cursor:pointer;width:10px;vertical-align:middle;' /></span>";
			$modif = "<span id=\"rap_modif_$item->id_item\" style='padding-left:7px;' onclick='modif_item($item->id_item);' ><img src=\"".$base_path."/images/b_edit.png\" style='cursor:pointer;width:10px;vertical-align:middle;'/></span>";
			$display .= "
						<div class='row' $style id='rap_drag_$item->id_item' draggable=\"yes\" dragtype=\"rapport\" dragtext=\"$titre\" dragicon=\"".$base_path."/images/icone_drag_notice.png\"
							handler=\"rap_handle_$item->id_item\" recepttype=\"rapport\" recept=\"yes\" highlight=\"rap_highlight\" downlight=\"rap_downlight\" iditem='$item->id_item' order='$ordre'>".$contenu.$drag.$modif.$del."</div>			
					";
		}
		
		ajax_http_send_response($display);
	}				
}
		
?>