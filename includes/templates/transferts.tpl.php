<?php
// +-------------------------------------------------+
// Â© 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: transferts.tpl.php,v 1.9 2010-02-22 13:40:49 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

//*******************************************************************
// Définition des templates pour les listes en edition
//*******************************************************************
$transferts_edition_tableau = "
	<script type='text/javascript' src='./javascript/sorttable.js'></script>
	<form class='form-edit' action='./edit.php?categ=transferts&sub=!!sub!!' method='post'>
	!!filtres_edition!!
	<input type='submit' class='bouton' value='".$msg["actualiser"]."' />
	</form>
	<br />
	<table class='sortable'>
	<tr>
		<th>".$msg["transferts_edition_tableau_titre"]."</th>
		<th>".$msg["transferts_edition_tableau_section"]."</th>
		<th>".$msg["transferts_edition_tableau_cote"]."</th>
		<th>".$msg["transferts_edition_tableau_expl"]."</th>
		!!colonnes_variables!!
	</tr>
	!!lignes_tableau!!
	</table>
	";

$transferts_edition_ligne = "
	<tr class='!!class_ligne!!' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='!!class_ligne!!'\">
		<td><a href='./catalog.php?categ=isbd&id=!!val_id_notice!!'>!!val_titre!!</a></td>
		<td>!!val_section!!</td>
		<td>!!val_cote!!</td>
		<td><a href='./circ.php?categ=visu_ex&form_cb_expl=!!val_expl!!'>!!val_expl!!</a></td>
		!!colonnes_variables!!
	</tr>
	";

$transferts_edition_titre_destination = "<th>".$msg["transferts_edition_tableau_destination"]."</th>";

$transferts_edition_titre_source = "<th>".$msg["transferts_edition_tableau_source"]."</th>";

$transferts_edition_ligne_destination = "<td>!!val_dest!!</td>";

$transferts_edition_ligne_source = "<td>!!val_source!!</td>";

$transferts_edition_filtre_source = $msg["transferts_edition_filtre_origine"]."&nbsp;<select name='site_origine'>!!liste_sites_origine!!</select>&nbsp;";

$transferts_edition_filtre_destination = $msg["transferts_edition_filtre_destination"]."&nbsp;<select name='site_destination'>!!liste_sites_destination!!</select>&nbsp;";

//*******************************************************************
// Définition des templates pour le popup de demande de transfert
//*******************************************************************

$transferts_popup_global = "
		<script type='text/javascript' src='./javascript/sorttable.js'></script>
		<form class='form-catalog' name='transferts' method='post' action='".$base_path."/catalog/transferts/transferts_popup.php?action=enregistre'>
		<h3>".$msg["transferts_popup_lib_titre"]."</h3>
		<div class='form-contenu'>
			".$msg["transferts_popup_lib_exemplaire"]."
			<table border='0' class='sortable'>		
			<tr>
				<th align='left'>".$msg[293]."</th>
				<th align='left'>".$msg[296]."</th>
				<th align='left'>".$msg[298]."</th>
				<th align='left'>".$msg[295]."</th>
				<th align='left'>".$msg[294]."</th>
				<th align='left'>".$msg[651]."</th>
			</tr>
			!!liste_exemplaires!!
			</table>
			<div class='row'>
				<label class='etiquette'>".$msg["transferts_popup_lib_destination"]."</label> <b>!!dest_localisation!!</b>
				<input type='hidden' name='dest_id' value='!!loc_id!!'>
			</div>
			<div class='row'>&nbsp;</div>		
			<div class='row'>		
				<label class='etiquette'>".$msg["transferts_popup_motif"]."</label><br />
				<textarea name='motif' cols=40 rows=5></textarea>
			</div>
			<div class='row'>&nbsp;</div>		
			<div class='row'>		
				<label class='etiquette'>".$msg["transferts_popup_date_retour"]."</label>
				<input type='button' class='bouton' name='bt_date_retour' value='!!date_retour!!' onClick=\"var reg=new RegExp('(-)', 'g'); openPopUp('".$base_path."/select.php?what=calendrier&caller=transferts&date_caller='+transferts.date_retour.value.replace(reg,'')+'&param1=date_retour&param2=bt_date_retour&auto_submit=NO&date_anterieure=YES', 'date_adhesion', 250, 320, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\">
				<input type='hidden' name='date_retour' value='!!date_retour_mysql!!'>
			</div>
		</div>
		<input type='submit' class='bouton_small' name='".$msg["transferts_popup_btValider"]."' value='".$msg["transferts_popup_btValider"]."'>
		&nbsp;
		<input type='button' class='bouton_small' name='".$msg["transferts_popup_btAnnuler"]."' value='".$msg["transferts_popup_btAnnuler"]."' onclick='window.close();'>
		<input type='hidden' name='expl_ids' value='!!expl_ids!!'>
		</form>
		";

$transferts_popup_ligne_tableau = "
		<tr class='!!class_ligne!!'>
			<td>!!expl_cb!!</td>
			<td>!!expl_cote!!</td>
			<td>!!location_libelle!!</td>
			<td>!!section_libelle!!</td>
			<td>!!tdoc_libelle!!</td>
			<td>!!lender_libelle!!</td>
		</tr>
		";

$transferts_popup_enregistre_demande = "
		<script>
			window.close();
		</script>
		";

//*******************************************************************
// Définition des templates pour le parcours des listes de transfert
// en circulation
//*******************************************************************

$transferts_parcours_nombre_resultats = "
		<div class='row'>
			<div class='left'>
				<input type='text' size=2 name='nb_per_page' value='!!nb_res!!'>&nbsp;".$msg["transferts_parcours_nb_resultats"]."&nbsp;
				!!autres_filtres!!
				<input type='submit' class='bouton' name='".$msg["transferts_parcours_bt_actualiser"]."' value='".$msg["transferts_parcours_bt_actualiser"]."'>
			</div>
			<div class='right'>!!lien_edition!!</div>
		</div>
		<div class='row'>&nbsp;</div>
		";

$transferts_liste_localisations = "<select name='!!nom_liste'>!!liste_localisations!!</select>&nbsp;";

$transferts_liste_localisations_tous = "<select name='!!nom_liste!!'><option value=0>".$msg["all_location"]."</option>!!liste_localisations!!</select>&nbsp;";

$transferts_script_case_a_cocher = "
		<script language='javascript'>
			var val_sel = false;
			function SelAll(formToCheck) {
				var nb;
				val_sel = !val_sel;
				nb = formToCheck.elements.length;
				for (var i=0;i<nb;i++) {
					var e = formToCheck.elements[i];

					if ((e.type == 'checkbox')&&(e.name.substr(0,4)=='sel_')) {
						e.checked = val_sel;
					}
				}
			}
			
			function check(cac) {
				cac.checked=!cac.checked;
			}
			
			function verifChk(formToCheck,valAction) {
				nb = formToCheck.elements.length;
				res = false;
				for (var i=0;i<nb;i++) {
					var e = formToCheck.elements[i];
					if ((e.type == 'checkbox')&&(e.name.substr(0,4)=='sel_'))
						if (e.checked == true) {
							res = true;
							break;
						}
				}
				if (res==true) {
					formToCheck.action.value = valAction;
					formToCheck.submit();
				} else {
					alert('".$msg["transferts_circ_pas_de_selection"]."');
				}
			}
		</script>
		";

//*******************************************************************
// Définition des templates pour l'interface de validation
//*******************************************************************

$transferts_validation_form_global = "
		<br />
		<form name='form_circ_trans' class='form-circ' method='post' action='!!action_formulaire!!'>
		<h3>".$msg["transferts_circ_validation_lot"]."</h3>
		<div class='form-contenu' >
		".$transferts_parcours_nombre_resultats."
		
		!!corps_liste_transfert!!
		</div>
		!!boutons_action!!
		<input type='hidden' name='action'>
		!!parcours_liste!!
		</form>
		".$transferts_script_case_a_cocher;

$transferts_validation_tableau_definition = "
		<table>
		<tr>
			<th align='left'>".$msg["233"]."</th>
			<th align='left'>".$msg["232"]."</th>
			<th align='left'>".$msg["transferts_circ_destination"]."</th>
			<th align='left'>".$msg["651"]."</th>
			<th align='left'>".$msg["transferts_circ_date_creation"]."</th>
			<th align='left'>".$msg["transferts_circ_motif"]."</th>
			<th><div align='center'><input type='button' class='bouton' name='+' onclick='SelAll(document.form_circ_trans);' value='+'></div></th>
		</tr>
		!!liste_lignes!!
		</table>
		";

$transferts_validation_tableau_ligne = "
		<tr class='!!class_ligne!!' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='!!class_ligne!!'\">
			<td>!!val_titre!!</td>
			<td>!!val_ex!!</td>
			<td>!!val_dest!!</td>
			<td>!!lender_libelle!!</td>
			<td>!!val_date_creation!!</td>
			<td>!!val_motif!!</td>
			<td><div align='center'><input type='checkbox' name='sel_!!val_id!!' value='1'></div></td>
		</tr>
		";

$transferts_validation_acceptation_erreur = "
		<div align='center' class='erreur'>
			<img src='./images/warning.gif'><b>&nbsp;".$msg["transferts_circ_validation_erreur_acceptation"]."</b>
		</div>
		";

$transferts_validation_acceptation_OK = "
		<div align='center'>
			<b>".$msg["transferts_circ_validation_accepte"]."</b>
		</div>
		";

$transferts_validation_boutons_action = "
		<input type='button' class='bouton' name='".$msg["transferts_circ_btValider"]."' value='".$msg["transferts_circ_btValider"]."' onclick='verifChk(document.form_circ_trans,\"aff_val\")'>
		&nbsp;
		<input type='button' class='bouton' name='".$msg["transferts_circ_btRefuser"]."' value='".$msg["transferts_circ_btRefuser"]."' onclick='verifChk(document.form_circ_trans,\"aff_refus\")'>
		";

$transferts_validation_pas_de_resultats = "<br /><strong style='text-align: center;display:block;'>".$msg["transferts_validation_liste_vide"]."</strong>";

$transferts_validation_liste_valide = "
		<script type='text/javascript' src='./javascript/sorttable.js'></script>
		<form name='form_circ_trans' class='form-circ' method='post' action='!!action_formulaire!!&action=val'>
		<h3>".$msg["transferts_circ_validation_valide"]."</h3>
		<div class='form-contenu'>
		<table class='sortable'>
		<tr>
			<th align='left'>".$msg["233"]."</th>
			<th align='left'>".$msg["232"]."</th>
			<th align='left'>".$msg["transferts_circ_destination"]."</th>
			<th align='left'>".$msg["651"]."</th>
			<th align='left'>".$msg["transferts_circ_date_creation"]."</th>
			<th align='left'>".$msg["transferts_circ_motif"]."</th>
		</tr>
		!!liste_transferts!!		
		</table>
		</div>
		<input type='submit' class='bouton' name='".$msg["89"]."' value='".$msg["89"]."'>
		&nbsp;
		<input type='button' class='bouton' name='".$msg["76"]."' value='".$msg["76"]."' onclick='document.location=\"!!action_formulaire!!\";'>
		<input type='hidden' name='liste_transfert' value='!!liste_id!!'>
		</form>
		";

$transferts_validation_liste_refus = "
		<script type='text/javascript' src='./javascript/sorttable.js'></script>
		<form name='form_circ_trans' class='form-circ' method='post' action='!!action_formulaire!!&action=refus'>
		<h3>".$msg["transferts_circ_validation_refus"]."</h3>
		<div class='form-contenu'>
		<table class='sortable'>
		<tr>
			<th align='left'>".$msg["233"]."</th>
			<th align='left'>".$msg["232"]."</th>
			<th align='left'>".$msg["transferts_circ_destination"]."</th>
			<th align='left'>".$msg["651"]."</th>
			<th align='left'>".$msg["transferts_circ_date_creation"]."</th>
			<th align='left'>".$msg["transferts_circ_motif"]."</th>
		</tr>
		!!liste_transferts!!		
		</table>
		<hr />
		".$msg["transferts_circ_validation_refus_motif"]."<br />
		<textarea name='motif_refus' cols=60></textarea>
		</div>
		<input type='submit' class='bouton' name='".$msg["89"]."' value='".$msg["89"]."'>
		&nbsp;
		<input type='button' class='bouton' name='".$msg["76"]."' value='".$msg["76"]."' onclick='document.location=\"!!action_formulaire!!\"'>
		<input type='hidden' name='liste_transfert' value='!!liste_id!!'>
		</form>
		";

$transferts_validation_liste_valide_ligne = "
		<tr class='!!class_ligne!!'>
			<td>!!val_titre!!</td>
			<td>!!val_ex!!</td>
			<td>!!val_dest!!</td>
			<td>!!lender_libelle!!</td>
			<td>!!val_date_creation!!</td>
			<td>!!val_motif!!</td>
		</tr>
		";

//*******************************************************************
// Définition des templates pour l'interface d'envoi
//*******************************************************************

if ($transferts_envoi_lot=="1")
	$transferts_envoi_form_global = "
		<br />
		<form name='form_circ_trans' class='form-circ' method='post' action='!!action_formulaire!!'>
		<h3>".$msg["transferts_circ_envoi_lot"]."</h3>
		<div class='form-contenu' >
		".$transferts_parcours_nombre_resultats."
		!!corps_liste_transfert!!
		</div>
		!!boutons_action!!
		<input type='hidden' name='action'>
		!!parcours_liste!!
		</form>
		".$transferts_script_case_a_cocher;
else
	$transferts_envoi_form_global = "
		<br />
		<form name='form_circ_trans' class='form-circ' method='post' action='!!action_formulaire!!'>
		<h3>".$msg["transferts_circ_lib_liste"]."</h3>
		<div class='form-contenu' >
		".$transferts_parcours_nombre_resultats."
		!!corps_liste_transfert!!
		</div>
		<input type='hidden' name='action'>
		!!parcours_liste!!
		</form>";
	
$transferts_envoi_tableau_definition = "
		<script type='text/javascript' src='./javascript/sorttable.js'></script>
		<table class='sortable'>
		<tr>
			<th align='left'>".$msg["233"]."</th>
			<th align='left'>".$msg["232"]."</th>
			<th align='left'>".$msg["transferts_circ_destination"]."</th>
			<th align='left'>".$msg["651"]."</th>
			<th align='left'>".$msg["transferts_circ_date_creation"]."</th>
			<th align='left'>".$msg["transferts_circ_date_validation"]."</th>";
if ($transferts_envoi_lot=="1")
	$transferts_envoi_tableau_definition .= "
			<th><div align='center'><input type='button' class='bouton' name='+' onclick='SelAll(document.form_circ_trans);' value='+'></div></th>";

$transferts_envoi_tableau_definition .= "
		</tr>
		!!liste_lignes!!
		</table>
		";

$transferts_envoi_tableau_ligne = "
		<tr class='!!class_ligne!!' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='!!class_ligne!!'\">
			<td>!!val_titre!!</td>
			<td>!!val_ex!!</td>
			<td>!!val_dest!!</td>
			<td>!!lender_libelle!!</td>
			<td>!!val_date_creation!!</td>
			<td>!!val_date_accepte!!</td>";

if ($transferts_envoi_lot=="1")
	$transferts_envoi_tableau_ligne .= "
			<td><div align='center'><input type='checkbox' name='sel_!!val_id!!' value='1'></div></td>";

$transferts_envoi_tableau_ligne .= "
		</tr>";

$transferts_envoi_erreur = "
		<div align='center' class='erreur'>
			<img src='./images/warning.gif'><b>&nbsp;".$msg["transferts_circ_envoi_erreur"]."</b>
		</div>
		";

$transferts_envoi_OK = "
		<div align='center'>
			<b>".$msg["transferts_circ_envoi_accepte"]."</b>
		</div>
		";

if ($transferts_validation_actif=="1")
	$transferts_envoi_boutons_action = "
			<input type='button' class='bouton' name='".$msg["transferts_circ_btEnvoyer"]."' value='".$msg["transferts_circ_btEnvoyer"]."' onclick='verifChk(document.form_circ_trans,\"aff_env\")'>
			";
else
	$transferts_envoi_boutons_action = "
			<input type='button' class='bouton' name='".$msg["transferts_circ_btEnvoyer"]."' value='".$msg["transferts_circ_btEnvoyer"]."' onclick='verifChk(document.form_circ_trans,\"aff_env\")'>
			&nbsp;
			<input type='button' class='bouton' name='".$msg["transferts_circ_btRefuser"]."' value='".$msg["transferts_circ_btRefuser"]."' onclick='verifChk(document.form_circ_trans,\"aff_refus\")'>
			";

$transferts_envoi_pas_de_resultats = "<br /><strong style='text-align: center;display:block;'>".$msg["transferts_envoi_liste_vide"]."</strong>";

$transferts_envoi_liste_valide_envoi = "
		<script type='text/javascript' src='./javascript/sorttable.js'></script>
		<form name='form_circ_trans' class='form-circ' method='post' action='!!action_formulaire!!&action=env'>
		<h3>".$msg["transferts_circ_envoi_valide_liste"]."</h3>
		<div class='form-contenu'>
		<table class='sortable'>
		<tr>
			<th align='left'>".$msg["233"]."</th>
			<th align='left'>".$msg["232"]."</th>
			<th align='left'>".$msg["transferts_circ_destination"]."</th>
			<th align='left'>".$msg["651"]."</th>
			<th align='left'>".$msg["transferts_circ_date_creation"]."</th>
			<th align='left'>".$msg["transferts_circ_date_validation"]."</th>
		</tr>
		!!liste_transferts!!		
		</table>
		</div>
		<input type='submit' class='bouton' name='".$msg["89"]."' value='".$msg["89"]."'>
		&nbsp;
		<input type='button' class='bouton' name='".$msg["76"]."' value='".$msg["76"]."' onclick='document.location=\"!!action_formulaire!!\"'>
		<input type='hidden' name='liste_transfert' value='!!liste_id!!'>
		</form>
		";

$transferts_envoi_liste_valide_envoi_ligne = "
		<tr class='!!class_ligne!!'>
			<td>!!val_titre!!</td>
			<td>!!val_ex!!</td>
			<td>!!val_dest!!</td>
			<td>!!lender_libelle!!</td>
			<td>!!val_date_creation!!</td>
			<td>!!val_date_accepte!!</td>
		</tr>
		";

//*******************************************************************
// Définition des templates pour l'interface de reception
//*******************************************************************

if ($transferts_reception_lot=="1")
	$transferts_reception_form_global = "
		<br />
		<form name='form_circ_trans' class='form-circ' method='post' action='!!action_formulaire!!'>
		<h3>".$msg["transferts_circ_reception_lot"]."</h3>
		<div class='form-contenu' >
		".$transferts_parcours_nombre_resultats."
		!!corps_liste_transfert!!
		</div>
		!!boutons_action!!
		<input type='hidden' name='action'>
		!!parcours_liste!!
		</form>
		".$transferts_script_case_a_cocher;
else
	$transferts_reception_form_global = "
		<br />
		<form name='form_circ_trans' class='form-circ' method='post' action='!!action_formulaire!!'>
		<h3>".$msg["transferts_circ_lib_liste"]."</h3>
		<div class='form-contenu' >
		".$transferts_parcours_nombre_resultats."
		!!corps_liste_transfert!!
		</div>
		<input type='hidden' name='action'>
		!!parcours_liste!!
		</form>";

$transferts_reception_tableau_definition = "
		<script type='text/javascript' src='./javascript/sorttable.js'></script>
		<table class='sortable'>
		<tr>
			<th align='left'>".$msg["233"]."</th>
			<th align='left'>".$msg["232"]."</th>
			<th align='left'>".$msg["transferts_circ_source"]."</th>
			<th align='left'>".$msg["651"]."</th>
			<th align='left'>".$msg["transferts_circ_date_creation"]."</th>
			<th align='left'>".$msg["transferts_circ_date_envoi"]."</th>";
if ($transferts_reception_lot=="1")
	$transferts_reception_tableau_definition .= "
			<th><div align='center'><input type='button' class='bouton' name='+' onclick='SelAll(document.form_circ_trans);' value='+'></div></th>";

$transferts_reception_tableau_definition .= "
		</tr>
		!!liste_lignes!!
		</table>
		";

$transferts_reception_tableau_ligne = "
		<tr class='!!class_ligne!!' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='!!class_ligne!!'\">
			<td>!!val_titre!!</td>
			<td>!!val_ex!!</td>
			<td>!!val_source!!</td>
			<td>!!lender_libelle!!</td>
			<td>!!val_date_creation!!</td>
			<td>!!val_date_envoi!!</td>";

if ($transferts_reception_lot=="1")
	$transferts_reception_tableau_ligne .= "
			<td><div align='center'><input type='checkbox' name='sel_!!val_id!!' value='1'></div></td>";

$transferts_reception_tableau_ligne .= "
			</tr>";

$transferts_reception_erreur = "
		<div align='center' class='erreur'>
			<img src='./images/warning.gif'><b>&nbsp;".$msg["transferts_circ_reception_erreur"]."</b>
		</div>
		";

$transferts_reception_OK = "
		<div align='center' class='row'>
			<b>".$msg["transferts_circ_reception_accepte"]."</b>
		</div>
		";

$transferts_reception_avertissement_retour = "
		<img src='./images/warning.gif' border=0> ".$msg["transfert_reception_avertissement_retour"]."<select>!!liste_statut_origine!!</select>
		<br />";

$transferts_reception_boutons_action = "
		<input type='button' class='bouton' name='".$msg["transferts_circ_btReception"]."' value='".$msg["transferts_circ_btReception"]."' onclick='verifChk(document.form_circ_trans,\"aff_recep\")'>
		";

$transferts_reception_pas_de_resultats = "<br /><strong style='text-align: center;display:block;'>".$msg["transferts_reception_liste_vide"]."</strong>";

$transferts_reception_liste_valide_reception = "
		<script type='text/javascript' src='./javascript/sorttable.js'></script>
		<form name='form_circ_trans' class='form-circ' method='post' action='!!action_formulaire!!&action=recep'>
		<h3>".$msg["transferts_circ_reception_valide_liste"]."</h3>
		<div class='form-contenu'>
		<table class='sortable'>
		<tr>
			<th align='left'>".$msg["233"]."</th>
			<th align='left'>".$msg["232"]."</th>
			<th align='left'>".$msg["transferts_circ_source"]."</th>
			<th align='left'>".$msg["651"]."</th>
			<th align='left'>".$msg["transferts_circ_date_creation"]."</th>
			<th align='left'>".$msg["transferts_circ_date_envoi"]."</th>
			<th align='left' class='sorttable_nosort'>".$msg["transferts_circ_reception_section"].
				"<br /><select name='val_section_globale' onchange='sel_sections(this)'><option value=0>".$msg["grp_liste"]."</option>!!liste_sections!!</select></th>
			</tr>
		!!liste_transferts!!		
		</table>
		<hr />
		<div class='row'>
			<label class='etiquette' for='form_cb_expl'>".$msg["transferts_circ_reception_lbl_statuts"]."</label>
		</div>
		<div class='row'>
				<select name='statut_reception'>!!liste_statuts!!</select>
		</div>
		</div>
		<input type='submit' class='bouton' name='".$msg["89"]."' value='".$msg["89"]."'>
		&nbsp;
		<input type='button' class='bouton' name='".$msg["76"]."' value='".$msg["76"]."' onclick='document.location=\"!!action_formulaire!!\"'>
		<input type='hidden' name='liste_transfert' value='!!liste_id!!'>
		<input type='hidden' name='liste_section' value='toto'>
		<script type='text/javascript'>
			function sel_sections(listeM) {
				if (listeM.selectedIndex>0) {
					liste_sel = document.form_circ_trans.liste_transfert.value.split(',');
					nb = liste_sel.length;
					for(i=0;i<nb;i++)
						document.form_circ_trans['section_'+liste_sel[i]].selectedIndex = listeM.selectedIndex-1;
				}
			}
			function gen_liste_section() {
				liste_sel = document.form_circ_trans.liste_transfert.value.split(',');
				nb = liste_sel.length;
				frm_liste =	document.form_circ_trans.liste_section;
				frm_liste.value = '';
				for(i=0;i<nb;i++) {
					sel_en_cours = document.form_circ_trans['section_'+liste_sel[i]];
					//alert(sel_en_cours.options[sel_en_cours.selectedIndex].value);
					frm_liste.value = frm_liste.value + sel_en_cours.options[sel_en_cours.selectedIndex].value + ',';
				}
				frm_liste.value = frm_liste.value.substr(0,frm_liste.value.length-1);
			}
			gen_liste_section();
		</script>
		</form>
		";

$transferts_reception_liste_valide_reception_ligne = "
		<tr class='!!class_ligne!!'>
			<td>!!val_titre!!</td>
			<td>!!val_ex!!</td>
			<td>!!val_dest!!</td>
			<td>!!lender_libelle!!</td>
			<td>!!val_date_creation!!</td>
			<td>!!val_date_accepte!!</td>
			<td><select name='section_!!val_id!!'>!!val_section!!</select></td>
		</tr>
		";

//*******************************************************************
// Définition des templates pour l'interface de retour
//*******************************************************************

if ($transferts_retour_lot=="1")
	$transferts_retour_form_global = "
		<br />
		<form name='form_circ_trans' class='form-circ' method='post' action='!!action_formulaire!!'>
		<h3>".$msg["transferts_circ_retours_lot"]."</h3>
		<div class='form-contenu' >
		".$transferts_parcours_nombre_resultats."
		!!corps_liste_transfert!!
		</div>
		!!boutons_action!!
		<input type='hidden' name='action'>
		!!parcours_liste!!
		</form>
		<script>
			function chgDate(dt,idTrans) {
				var url= './ajax.php?module=circ&categ=transferts&action=date_retour&id=' + idTrans + '&dt=' + dt;
				var maj_date = new http_request();
				if(maj_date.request(url)){
					// Il y a une erreur. Afficher le message retourné
					alert ( '" . $msg["540"] . " : ' + maj_date.get_text() );			
				}
			}
		</script>
		".$transferts_script_case_a_cocher;
else
	$transferts_retour_form_global = "
		<br />
		<form name='form_circ_trans' class='form-circ' method='post' action='!!action_formulaire!!'>
		<h3>".$msg["transferts_circ_lib_liste"]."</h3>
		<div class='form-contenu' >
		".$transferts_parcours_nombre_resultats."
		!!corps_liste_transfert!!
		</div>
		<input type='hidden' name='action'>
		!!parcours_liste!!
		</form>
		<script type='text/javascript' src='".$javascript_path."/http_request.js'></script>
		<script>
			function chgDate(dt,idTrans) {
				var url= './ajax.php?module=circ&categ=transferts&action=date_retour&id=' + idTrans + '&dt=' + dt;
				var maj_date = new http_request();
				if(maj_date.request(url)){
					// Il y a une erreur. Afficher le message retourné
					alert ( '" . $msg["540"] . " : ' + maj_date.get_text() );			
				}
			}
		</script>
		".$transferts_script_case_a_cocher;

$transferts_retour_filtre_etat = "
		&nbsp;".$msg["transferts_circ_retour_filtre_etat"]."&nbsp;
		<select name='f_etat_date'>
			<option value=0 !!sel_0!!>" . $msg["transferts_circ_retour_filtre_etat_tous"] . "</option>
			<option value=1 !!sel_1!!>" . $msg["transferts_circ_retour_filtre_etat_proche"] . "</option>
			<option value=2 !!sel_2!!>" . $msg["transferts_circ_retour_filtre_etat_depasse"] . "</option>
		</select>
		";

$transferts_retour_tableau_definition = "
		<table class='expl-list'>
		<tr>
			<th align='left'>".$msg["233"]."</th>
			<th align='left'>".$msg["232"]."</th>
			<th align='left'>".$msg["transferts_circ_destination"]."</th>
			<th align='left'>".$msg["651"]."</th>
			<th align='left'>".$msg["transferts_circ_date_reception"]."</th>
			<th align='left'>".$msg["transferts_circ_date_retour"]."</th>";
if ($transferts_retour_lot=="1")
	$transferts_retour_tableau_definition .= "
			<th><div align='center'><input type='button' class='bouton' name='+' onclick='SelAll(document.form_circ_trans);' value='+'></div></th>";
	
$transferts_retour_tableau_definition .= "
		</tr>
		!!liste_lignes!!
		</table>
		";

$transferts_retour_tableau_ligne = "
		<tr class='!!class_ligne!!' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='!!class_ligne!!'\">
			<td>!!val_titre!!</td>
			<td>!!val_ex!!</td>
			<td>!!val_dest!!</td>
			<td>!!lender_libelle!!</td>
			<td>!!val_date_reception!!</td>
			<td>
				<input type='button' class='bouton' name='bt_date_retour_!!val_id!!' value='!!val_date_retour!!' onClick=\"var reg=new RegExp('(-)', 'g'); openPopUp('".$base_path."/select.php?what=calendrier&caller=form_circ_trans&date_caller='+form_circ_trans.date_retour_!!val_id!!.value.replace(reg,'')+'&param1=date_retour_!!val_id!!&param2=bt_date_retour_!!val_id!!&auto_submit=NO&date_anterieure=YES&after=chgDate%28id_value,!!val_id!!%29', 'date_retour', 250, 320, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\">
				<input type='hidden' name='date_retour_!!val_id!!' value='!!val_date_retour_mysql!!'>
			</td>";
if ($transferts_retour_lot=="1")
	$transferts_retour_tableau_ligne .= "
			<td><div align='center'><input type='checkbox' name='sel_!!val_id!!' value='1'></div></td>";

$transferts_retour_tableau_ligne .= "
	</tr>
		";

$transferts_retour_acceptation_erreur = "
		<div align='center' class='erreur'>
			<img src='./images/warning.gif'><b>&nbsp;".$msg["transferts_circ_validation_erreur_acceptation"]."</b>
		</div>
		";

$transferts_retour_acceptation_OK = "
		<div align='center'>
			<b>".$msg["transferts_circ_retour_accepte"]."</b>
		</div>
		";

$transferts_retour_boutons_action = "
		<input type='button' class='bouton' name='".$msg["transferts_circ_btRetour"]."' value='".$msg["transferts_circ_btRetour"]."' onclick='verifChk(document.form_circ_trans,\"aff_ret\")'>
		";

$transferts_retour_pas_de_resultats = "<br /><br /><br /><strong style='text-align: center;display:block;'>".$msg["transferts_retour_liste_vide"]."</strong>";

$transferts_retour_liste_valide = "
		<script type='text/javascript' src='./javascript/sorttable.js'></script>
		<form name='form_circ_trans' class='form-circ' method='post' action='!!action_formulaire!!&action=ret'>
		<h3>".$msg["transferts_circ_retour_valide_liste"]."</h3>
		<div class='form-contenu'>
		<table class='sortable'>
		<tr>
			<th align='left'>".$msg["233"]."</th>
			<th align='left'>".$msg["232"]."</th>
			<th align='left'>".$msg["transferts_circ_destination"]."</th>
			<th align='left'>".$msg["651"]."</th>
			<th align='left'>".$msg["transferts_circ_date_reception"]."</th>
			<th align='left'>".$msg["transferts_circ_date_retour"]."</th>
		</tr>
		!!liste_transferts!!		
		</table>
		</div>
		<input type='submit' class='bouton' name='".$msg["89"]."' value='".$msg["89"]."'>
		&nbsp;
		<input type='button' class='bouton' name='".$msg["76"]."' value='".$msg["76"]."' onclick='document.location=\"!!action_formulaire!!\"'>
		<input type='hidden' name='liste_transfert' value='!!liste_id!!'>
		</form>
		";

$transferts_retour_liste_valide_ligne = "
		<tr class='!!class_ligne!!'>
			<td>!!val_titre!!</td>
			<td>!!val_ex!!</td>
			<td>!!val_dest!!</td>
			<td>!!lender_libelle!!</td>
			<td>!!val_date_reception!!</td>
			<td>!!val_date_retour!!</td>
		</tr>
		";


//*******************************************************************
// Définition des templates pour l'interface des refus
//*******************************************************************
$transferts_refus_form_global = "
		<br />
		<form name='form_circ_trans' class='form-circ' method='post' action='!!action_formulaire!!'>
		<h3>".$msg["transferts_circ_retours_lot"]."</h3>
		<div class='form-contenu' >
		".$transferts_parcours_nombre_resultats."
		!!corps_liste_transfert!!
		</div>
		!!boutons_action!!
		<input type='hidden' name='action'>
		!!parcours_liste!!
		</form>
		".$transferts_script_case_a_cocher;

$transferts_refus_tableau_definition = "
		<table class='expl-list'>
		<tr>
			<th align='left'>".$msg["233"]."</th>
			<th align='left'>".$msg["232"]."</th>
			<th align='left'>".$msg["transferts_circ_source"]."</th>
			<th align='left'>".$msg["651"]."</th>
			<th align='left'>".$msg["transferts_circ_date_creation"]."</th>
			<th align='left'>".$msg["transferts_circ_date_refus"]."</th>
			<th align='left'>".$msg["transferts_circ_motif_refus"]."</th>
			<th></th>
			<th><div align='center'><input type='button' class='bouton' name='+' onclick='SelAll(document.form_circ_trans);' value='+'></div></th>
		</tr>
		!!liste_lignes!!
		</table>
		";

$transferts_refus_tableau_ligne = "
		<tr class='!!class_ligne!!' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='!!class_ligne!!'\">
			<td>!!val_titre!!</td>
			<td>!!val_ex!!</td>
			<td>!!val_source!!</td>
			<td>!!lender_libelle!!</td>
			<td>!!val_date_creation!!</td>
			<td>!!val_date_refus!!</td>
			<td>!!val_refusMotif!!</td>
			<td><input type='button' class='bouton' value='".$msg["transferts_circ_btRelancer"]."' onclick='document.location=\"!!action_formulaire!!&action=aff_redem&transid=!!val_id!!\"'></td>
			<td><div align='center'><input type='checkbox' name='sel_!!val_id!!' value='1'></div></td>
		</tr>";

$transferts_refus_boutons_action = "
		<input type='button' class='bouton' name='".$msg["transferts_circ_btSupprimer"]."' value='".$msg["transferts_circ_btSupprimer"]."' onclick='verifChk(document.form_circ_trans,\"aff_supp\")'>
		";

$transferts_refus_pas_de_resultats = "<br /><br /><br /><strong style='text-align: center;display:block;'>".$msg["transferts_refuse_liste_vide"]."</strong>";

$transferts_refus_liste_valide = "
		<script type='text/javascript' src='./javascript/sorttable.js'></script>
		<form name='form_circ_trans' class='form-circ' method='post' action='!!action_formulaire!!&action=supp'>
		<h3>".$msg["transferts_circ_refus_valide_liste"]."</h3>
		<div class='form-contenu'>
		<table class='sortable'>
		<tr>
			<th align='left'>".$msg["233"]."</th>
			<th align='left'>".$msg["232"]."</th>
			<th align='left'>".$msg["transferts_circ_source"]."</th>
			<th align='left'>".$msg["651"]."</th>
			<th align='left'>".$msg["transferts_circ_date_creation"]."</th>
			<th align='left'>".$msg["transferts_circ_date_refus"]."</th>
			<th align='left'>".$msg["transferts_circ_motif_refus"]."</th>
		</tr>
		!!liste_transferts!!		
		</table>
		</div>
		<input type='submit' class='bouton' name='".$msg["89"]."' value='".$msg["89"]."'>
		&nbsp;
		<input type='button' class='bouton' name='".$msg["76"]."' value='".$msg["76"]."' onclick='document.location=\"!!action_formulaire!!\"'>
		<input type='hidden' name='liste_transfert' value='!!liste_id!!'>
		</form>
		";

$transferts_refus_liste_valide_ligne = "
		<tr class='!!class_ligne!!'>
			<td>!!val_titre!!</td>
			<td>!!val_ex!!</td>
			<td>!!val_source!!</td>
			<td>!!lender_libelle!!</td>
			<td>!!val_date_creation!!</td>
			<td>!!val_date_refus!!</td>
			<td>!!val_refusMotif!!</td>
		</tr>
		";

$transferts_refus_redemande_global = "
		<br />
		<form name='form_circ_trans' class='form-circ' method='post' action='!!action_formulaire!!&action=redem'>
		<h3>".$msg["transferts_circ_refus_relance"]."</h3>
		<div class='form-contenu' >
			!!detail_notice!!
			<div class='row'>&nbsp;</div>		
			<div class='row'>
				<label class='etiquette'>".$msg["transferts_circ_refus_relance_apartir"]."</label>
				<select name='source'>!!liste_sites!!</select></div>
			<div class='row'>&nbsp;</div>		
			<div class='row'>		
				<label class='etiquette'>".$msg["transferts_circ_refus_relance_motif"]."</label><br />
				<textarea name='motif' cols=40 rows=5></textarea>
			</div>
			<div class='row'>&nbsp;</div>		
			<div class='row'>		
				<label class='etiquette'>".$msg["transferts_circ_refus_relance_retour"]."</label>
				<input type='button' class='bouton' name='bt_date_retour' value='!!date_retour!!' onClick=\"var reg=new RegExp('(-)', 'g'); openPopUp('".$base_path."/select.php?what=calendrier&caller=form_circ_trans&date_caller='+form_circ_trans.date_retour.value.replace(reg,'')+'&param1=date_retour&param2=bt_date_retour&auto_submit=NO&date_anterieure=YES', 'date_adhesion', 250, 320, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\">
				<input type='hidden' name='date_retour' value='!!date_retour_mysql!!'>
			</div>
		</div>
		<input type='submit' class='bouton' name='".$msg["89"]."' value='".$msg["89"]."'>
		&nbsp;
		<input type='button' class='bouton' name='".$msg["76"]."' value='".$msg["76"]."' onclick='document.location=\"!!action_formulaire!!\"'>
		<input type='hidden' name='transid' value='!!trans_id!!'>
		</form>
		";

//*******************************************************************
// Définition des templates pour l'administration des transferts
//*******************************************************************

$transferts_admin_tableau_affiche = "
		<table>
		<tr>
			<th>".$msg["admin_tranferts_titre_tableau_param"]."</th>
			<th>".$msg["admin_tranferts_titre_tableau_valeur"]."</th>
		</tr>
			!!lignes_aff!!
		</table>
		<div class='row'><input type='button' class='bouton' value='".$msg["admin_transferts_modifier"]."' onClick=\"document.location='./admin.php?categ=transferts&sub=!!sub!!&action=modif';\"></div>
";

$transferts_admin_ligne_affiche = "
			<tr id='lg_!!nom_champ!!' class='!!class_ligne!!'>
				<td><i>!!lib_param!!</i></td><td>!!val_param!!</td>
			</tr>
		";

$transferts_admin_ligne_separateur = "
			<tr>
				<th colspan=2>!!lib_separateur!!</th>
			</tr>
		";

$transferts_admin_tableau_modif = "
		<form class='form-admin' name='modifParam' method='post' action='./admin.php?categ=transferts&sub=!!sub!!&action=enregistre'>
		<h3>!!titre!!</h3>
		<div class='form-contenu'>
		<table>
		<tr>
			<th>".$msg["admin_tranferts_titre_tableau_param"]."</th>
			<th>".$msg["admin_tranferts_titre_tableau_valeur"]."</th>
		</tr>
		!!liste_lignes!!
		</table>
		</div>
		<div class='left'>
			<!--<input type='button' class='bouton' value='".$msg["admin_transferts_annuler"]."' onClick=\"document.location='./admin.php?categ=transferts&sub=!!sub!!';\">&nbsp;-->
			<input type='submit' class='bouton' value='".$msg["admin_transferts_enregistrer"]."'>
			<input type='hidden' name='form_actif' value='1'>
		</div>
		<div class='row'></div>
		</form>
		<script language='javascript'>
				//affiche ou cache la liste des sites fixe
				function affLigne(objSel,val,id_lg) {
					//test du navigateur pour l'affichage de la ligne
					if (navigator.appName=='Netscape')
						aff = 'table-row';
					else
						aff = 'inline';
					if (objSel[objSel.selectedIndex].value==val) {
						document.getElementById(id_lg).style.display=aff;
					} else {
						document.getElementById(id_lg).style.display='none';
					}
					
				}
		</script>
";

$transferts_admin_ligne_modif = "
			<tr id='lg_!!nom_champ!!' class='!!class_ligne!!'>
				<td><i>!!lib_param!!</i></td><td>!!input_param!!</td>
			</tr>
";

$transferts_admin_modif_ordre_loc = "
		<form class='form-admin' name='modifOrdre' method='post' action='./admin.php?categ=transferts&sub=ordreloc&action=enregistre'>
		<h3>".$msg["admin_tranferts_ordre_localisation"]."</h3>
		<div class='form-contenu'>
		<table>
			!!liste_sites!!
		</table>
		</div>
		<input type='hidden' name='sens'>
		<input type='hidden' name='idLoc'>
		<div class='row'></div>
		</form>
		<script language='javascript'>
				function chgOrdre(id,dir) {
					document.modifOrdre.sens.value=dir;
					document.modifOrdre.idLoc.value=id;
					document.modifOrdre.submit();
				}
		</script>
		";

$transferts_admin_modif_ordre_loc_ligne = "
		<tr class='!!class_ligne!!'  onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='!!class_ligne!!'\">
			<td><i>!!lib_site!!</i></td>
			<td>!!fl_haut!!</td>
			<td>!!fl_bas!!</td>
			</tr>
		";
		
$transferts_admin_modif_ordre_loc_ligne_flBas = "<a href='javascript:chgOrdre(!!idSite!!,1);' style='cursor:hand'><img src=\"".$base_path."/images/arrow_down.png\"  alt=\"".$msg["admin_transferts_lib_descend"]."\"></a>";

$transferts_admin_modif_ordre_loc_ligne_flHaut = "<a href='javascript:chgOrdre(!!idSite!!,-1);' style='cursor:hand'><img src='".$base_path."/images/arrow_up.png' alt=\"".$msg["admin_transferts_lib_monte"]."\"'></a>";

$transferts_admin_statuts_loc_liste = "
		<table>
		<tr>
			<th align='left'>".$msg["admin_transferts_statutsDef_site"]."</th>
			<th align='left'>".$msg["admin_transferts_statutsDef_statuts"]."</th>
		</tr>
		!!liste_sites!!
		</table>
		<script language='javascript'>
			function modif(id) {
				document.location = './admin.php?categ=transferts&sub=statutsdef&action=modif&id='+id;
			}
		</script>
		";

$transferts_admin_statuts_loc_ligne = "
		<tr class='!!class_ligne!!' onclick='modif(!!id_site!!);' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='!!class_ligne!!'\"  style='cursor: pointer'>
			<td>!!nom_site!!</td>
			<td>!!nom_statut!!</td>
		</tr>";

$transferts_admin_statuts_loc_modif = "
		<form class='form-admin' name='modifStatutDef' method='post' action='./admin.php?categ=transferts&sub=statutsdef&action=enregistre'>
		<h3>!!nom_site!!</h3>
		<div class='form-contenu'>
		".$msg["admin_transferts_statutsDef_statuts"]." : <select name='statutDef'>!!liste_statuts!!</select>
		</div>
		<div class='left'>
			<input type='button' class='bouton' value='".$msg["admin_transferts_annuler"]."' onClick=\"document.location='./admin.php?categ=transferts&sub=statutsdef';\">&nbsp;&nbsp;&nbsp;
			<input type='submit' class='bouton' value='".$msg["admin_transferts_enregistrer"]."'>
		</div>
		<div class='row'></div>
		<input type='hidden' name='id' value='!!id_site!!'>
		</form>
		<script language='javascript'>
			selOpt(document.modifStatutDef.statutDef,'!!selStatut!!');
			
			//function qui selectionne l'option dans une liste en cherchant la bonne valeur
			function selOpt(objSel,valueOpt) {
				for(i=0;i<(objSel.length);i++) {
					if (objSel[i].value==valueOpt)
							objSel.selectedIndex = i;
							objSel[i].selected == true;
				}
			}

		</script>
		";

$transferts_admin_purge_defaut = "
		<div class='row'>		
			<label class='etiquette'>!!message_purge!!</label>
		</div>
		<form class='form-admin' name='transferts' method='post' action='".$base_path."/admin.php?categ=transferts&sub=purge&action=purge'>
		<h3>".$msg["admin_transferts_titre_purge"]."</h3>
		<div class='form-contenu'>
			<div class='row'>&nbsp;</div>
			<div class='erreur'>
				".$msg["admin_transferts_avertissement_purge"]."
			</div>
			<div class='row'>&nbsp;</div>
			<div class='row'>		
				<label class='etiquette'>".$msg["admin_transferts_date_purge"]."</label>
				<input type='button' class='bouton' name='bt_date_purge' value='!!date_purge!!' onClick=\"var reg=new RegExp('(-)', 'g'); openPopUp('".$base_path."/select.php?what=calendrier&caller=transferts&date_caller='+transferts.date_purge.value.replace(reg,'')+'&param1=date_purge&param2=bt_date_purge&auto_submit=NO&date_anterieure=YES', 'date_purge', 250, 320, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\">
				<input type='hidden' name='date_purge' value='!!date_purge_mysql!!'>
			</div>
			<div class='row'>&nbsp;</div>
		</div>
		<input type='button' class='bouton' name='".$msg["admin_transferts_purger"]."' value='".$msg["admin_transferts_purger"]."' onclick=\"if (confirm('".$msg["admin_transferts_avertissement_purge_confirm"]."'+transferts.bt_date_purge.value+'".$msg["admin_transferts_avertissement_purge_confirm_suite"]."')) transferts.submit();\">
		</form>
		";
		
$transferts_admin_purge_message_ok = "
		<div class='row'>".$msg["admin_transferts_message_purge"]."</div>
		";
//*******************************************************************
?>
