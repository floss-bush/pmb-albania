<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: empr_list.inc.php,v 1.30 2010-12-02 14:39:16 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

include_once("$include_path/templates/empr.tpl.php");
require_once("./circ/empr/empr_func.inc.php");
require_once ("$class_path/emprunteur.class.php");

//Récupération des variables postées, on en aura besoin pour les liens
$page_url=$_SERVER[SCRIPT_NAME];

switch($dest) {
	case "TABLEAU":
		$fname = tempnam("./temp", "$fichier_temp_nom.xls");
		$workbook = &new writeexcel_workbook($fname);
		$worksheet = &$workbook->addworksheet();
		$worksheet->write(0,0,$titre_page);
		break;
	case "TABLEAUHTML":
		echo "<h1>".$titre_page."</h1>" ;  
		break;
	default:
		echo "<h1>".$titre_page."</h1>" ;
		break;
}

// nombre de références par pages
if ($nb_per_page_empr != "") 
	$nb_per_page = $nb_per_page_empr ;
else 
	$nb_per_page = 10;

// restriction localisation le cas échéant
if ($pmb_lecteurs_localises) {
	if ($empr_location_id=="") 
		$empr_location_id = $deflt2docs_location ;
	if ($empr_location_id!=0) 
		$restrict_localisation = " AND empr_location='$empr_location_id' ";
	else 
		$restrict_localisation = "";
}

// filtré par un statut sélectionné
if ($empr_statut_edit) {
	if ($empr_statut_edit!=0) 
		$restrict_statut = " AND empr_statut='$empr_statut_edit' ";
	else 
		$restrict_statut="";
} 


// on récupére le nombre de lignes 
if(!$nbr_lignes) {
	$requete = "SELECT COUNT(1) FROM empr, empr_statut where 1 ";
	$requete = $requete.$restrict_localisation.$restrict_statut." and ".$restrict;
	$requete .= " and empr_statut=idstatut";
	$res = mysql_query($requete, $dbh);
	$nbr_lignes = @mysql_result($res, 0, 0);
}

//Si aucune limite_page n'a été passée, valeur par défaut $nb_per_page
if (!$limite_page) 
	$limite_page = $nb_per_page;
else 
	$nb_per_page = $limite_page;

$nbpages= $nbr_lignes / $limite_page;
 
if(!$page) $page=1;

$debut =($page-1)*$nb_per_page;

if($nbr_lignes) {
	if ($statut_action=="modify") {
		$requete="UPDATE empr set empr_statut='$empr_chang_statut_edit' where 1 ".$restrict_localisation.$restrict_statut." and ".$restrict;
		$restrict_statut = " AND empr_statut='$empr_chang_statut_edit' ";
		@mysql_query($requete);
	} 
	// on lance la vraie requête
	$requete = "SELECT empr.*, date_format(empr_date_adhesion, '".$msg["format_date"]."') as aff_empr_date_adhesion, date_format(empr_date_expiration, '".$msg["format_date"]."') as aff_empr_date_expiration, statut_libelle  FROM empr, empr_statut ";
	$restrict_empr = " WHERE 1 ";
	$restrict_requete = $restrict_empr.$restrict_localisation.$restrict_statut." and ".$restrict;
	$requete .= $restrict_requete;
	$requete .= " and empr_statut=idstatut ";
	if (!isset($sortby))
		$sortby = 'empr_nom';

	$requete .= " ORDER BY $sortby ";
	
	switch($dest) {
		case "TABLEAU":
			$res = @mysql_query($requete, $dbh);
			for($i=0; $i < $nbr_lignes; $i++) {
				$row = mysql_fetch_row($res);
				$j=0;
				foreach($row as $dummykey=>$col) {
					if(!$col) $col=" ";
					$worksheet->write(($i+2),$j,$col);
					$j++;
				}
			}
			$workbook->close();
			$fh=fopen($fname, "rb");
			fpassthru($fh);
			unlink($fname);
			break;
		case "TABLEAUHTML":
			$res = @mysql_query($requete, $dbh);
			$empr_list = "<table>" ;
			while(($empr=mysql_fetch_object($res))) {
				$empr_list .= "<tr>";
				$empr_list .= "	<td>
							<strong>$empr->empr_cb</strong>
							</td>
						<td>
							$empr->empr_nom&nbsp;$empr->empr_prenom
							</td>
						<td>$empr->empr_adr1</td>
						<td>$empr->empr_adr2</td>
						<td>$empr->empr_ville</td>
						<td>$empr->empr_year</td>";
				$empr_list .= "<td>".$empr->aff_empr_date_expiration."</td>";
				$empr_list .= "<td>".$empr->statut_libelle."</td>";
				$empr_list .= "</tr>";
			}
			$empr_list .= "</table>" ;
			echo $empr_list ;
			break;
		default:
			$requete .= "LIMIT $debut,$nb_per_page ";
			$res = @mysql_query($requete, $dbh);
	
			$parity=1;
			while(($empr=mysql_fetch_object($res))) {
				if ($parity % 2) 
					$pair_impair = "even";
				else 
					$pair_impair = "odd";
				$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" ";
				$script="onclick=\"document.location='./circ.php?categ=pret&form_cb=".rawurlencode($empr->empr_cb)."';\"";
				$empr_list .= "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>";
				$empr_list .= "	<td $script>
							<strong>$empr->empr_cb</strong>
							</td>
						<td $script>
							$empr->empr_nom&nbsp;$empr->empr_prenom
							</td>
						<td $script>$empr->empr_adr1</td>
						<td $script>$empr->empr_ville</td>
						<td $script>$empr->empr_year</td>";
				$empr_list .= "<td $script>".$empr->aff_empr_date_expiration."</td>";
				$empr_list .= "<td $script>".$empr->statut_libelle."</td>";
				if ($sub!="encours") {
					$empr_list.="<td>";
					$action_relance_courrier = "onclick=\"openPopUp('./pdf.php?pdfdoc=lettre_relance_adhesion&id_empr=".$empr->id_empr."', 'lettre', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes'); return(false) \"";
					$empr_list .= "<a href=\"#\" ".$action_relance_courrier."><img src=\"./images/new.gif\" title=\"".$msg["param_pdflettreadhesion"]."\" alt=\"".$msg["param_pdflettreadhesion"]."\" border=\"0\"></a>";
					if ($empr->empr_mail) {
						$mail_click = "onclick=\"if (confirm('".$msg["mail_retard_confirm"]."')) {openPopUp('./mail.php?type_mail=mail_relance_adhesion&id_empr=".$empr->id_empr."', 'mail', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes, scrollbars=yes');} return(false) \"";
						$empr_list .= "&nbsp;<a href=\"#\" ".$mail_click."><img src=\"./images/mail.png\" title=\"".$msg["param_mailrelanceadhesion"]."\" alt=\"".$msg["param_mailrelanceadhesion"]."\" border=\"0\"></a>";
					}
					$empr_list.="</td>";
				}
				$empr_list .= "<td>"."</td>";
				$empr_list .= "</tr>";
				$parity += 1;
			}
			mysql_free_result($res);

			// constitution des liens
			$nbepages = ceil($nbr_lignes/$nb_per_page);
			$suivante = $page+1;
			$precedente = $page-1;
			// affichage du lien précédent si nécéssaire
			if($precedente > 0)
				$nav_bar .= "<a href='$PHP_SELF?categ=empr&sub=$sub&page=$precedente&nbr_lignes=$nbr_lignes&form_cb=".rawurlencode($form_cb)."&limite_page=$limite_page&empr_location_id=$empr_location_id&empr_statut_edit=$empr_statut_edit&sortby=$sortby'><img src='./images/left.gif' border='0' title='$msg[48]' alt='[$msg[48]]' hspace='3' align='middle'></a>";
			for($i = 1; $i <= $nbepages; $i++) {
				if($i==$page) 
					$nav_bar .= "<strong>page $i/$nbepages</strong>";
			}
			if($suivante<=$nbepages) 
				$nav_bar .= "<a href='$PHP_SELF?categ=empr&sub=$sub&page=$suivante&nbr_lignes=$nbr_lignes&form_cb=".rawurlencode($form_cb)."&limite_page=$limite_page&empr_location_id=$empr_location_id&empr_statut_edit=$empr_statut_edit&sortby=$sortby'><img src='./images/right.gif' border='0' title='$msg[49]' alt='[$msg[49]]' hspace='3' align='middle'></a>";

			// affichage du résultat
			echo "
				<form class='form-$current_module' id='form-$current_module-list' name='form-$current_module-list' action='$page_url?categ=$categ&sub=$sub&limite_page=$limite_page&numero_page=$numero_page' method=post>
			 	<div class='left'>
					$nav_bar $msg[circ_afficher] <input type=text name=limite_page value='$limite_page' class='saisie-5em'> $msg[1905] &nbsp;
				</div>
				<div class='right'>
					<img  src='./images/tableur.gif' border='0' align='top' onMouseOver ='survol(this);' onclick=\"start_export('TABLEAU');\" alt='Export tableau EXCEL' title='Export tableau EXCEL'/>&nbsp;&nbsp;
					<img  src='./images/tableur_html.gif' border='0' align='top' onMouseOver ='survol(this);' onclick=\"start_export('TABLEAUHTML');\" alt='Export tableau HTML' title='Export tableau HTML'/>&nbsp;&nbsp;
				</div>
				<script type='text/javascript'>
					function survol(obj){
						obj.style.cursor = 'pointer';
					}
					function start_export(type){
						document.forms['form-$current_module-list'].dest.value = type;
						document.forms['form-$current_module-list'].submit();
					}	
				</script>
			";
					
			if ($pmb_lecteurs_localises) echo docs_location::gen_combo_box_empr($empr_location_id);
			echo gen_liste("select idstatut, statut_libelle from empr_statut","idstatut","statut_libelle","empr_statut_edit","",$empr_statut_edit,-1,"",0,$msg["all_statuts_empr"]);
			$sort_params = array('empr_nom' => $msg['readerlist_name'], 'empr_cb' => $msg['readerlist_code'], 'empr_ville' => $msg['readerlist_ville'], 'empr_date_expiration' => $msg['readerlist_dateexpiration']);
			echo "&nbsp;Trier par:&nbsp;";
			echo '<select name="sortby">';
				foreach($sort_params as $id => $caption) {
					echo '<option '.($id == $sortby ? 'selected' : '').' value="'.$id.'">'.$caption.'</option>';
				}
			echo '</select>';
			echo "&nbsp;<input type='submit' class='bouton' value='".$msg['actualiser']."' onClick=\"this.form.dest.value='';\" />&nbsp;&nbsp;<input type='hidden' name='dest' value='' />";
			
			if ($empr_show_caddie) $bt_add_panier="&nbsp;&nbsp;<input type='button' class='bouton_small' value='".$msg["add_empr_cart"]."' onClick=\"openPopUp('./cart.php?object_type=EMPR&action=add_empr_$sub&empr_location_id=$empr_location_id', 'cart', 600, 700, -2, -2,'$selector_prop_ajout_caddie_empr'); return false;\">";
			else $bt_add_panier="";
			echo "
				<div class='row'></div></form><br />";
			
			print pmb_bidi("<table>".$empr_list."</table>");
			
			echo "
				<br /><form class='form-$current_module' id='form-$current_module-action' name='form-$current_module-action' action='$PHP_SELF?categ=empr&sub=$sub&page=$precedente&nbr_lignes=$nbr_lignes&form_cb=".rawurlencode($form_cb)."&limite_page=$limite_page&empr_location_id=$empr_location_id&empr_statut_edit=$empr_statut_edit&statut_action=modify' method='post'>
				<div class='left'>";
			if ($sub=="limite" || $sub=="depasse") echo "<input type='button' class='bouton_small' value='".htmlentities($msg["print_all_relances"],ENT_QUOTES,$charset)."' onclick='document.location=\"./edit.php?categ=$categ&sub=$sub&action=print_all\"'>&nbsp;";
			
			echo "$bt_add_panier
				</div>
				<div align='right'>
					".$msg["empr_chang_statut"]."&nbsp;
					".gen_liste("select idstatut, statut_libelle from empr_statut","idstatut","statut_libelle","empr_chang_statut_edit","","",0,"",0,"")."  
					&nbsp;<input type='submit' class='bouton_small' value='".$msg['empr_chang_statut_button']."' />
				</div>
				</form>"; 
			break;
	} //switch($dest)


} else {
	// la requête n'a produit aucun résultat
	switch($dest) {
		case "TABLEAU":
			break;
		case "TABLEAUHTML":
			break;
		default:
			error_message($msg[46], str_replace('!!form_cb!!', $form_cb, $msg['edit_lect_aucun_trouve']), 1, './edit.php?categ=empr&sub='.$sub);
	}
}
