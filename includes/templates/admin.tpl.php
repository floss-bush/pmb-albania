<?php
// +-------------------------------------------------+
// ï¿½ 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: admin.tpl.php,v 1.155 2011-01-20 13:25:15 trenon Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

// ---------------------------------------------------------------------------
//	$admin_menu_new : Menu vertical de l'administration
// ---------------------------------------------------------------------------
$admin_menu_new = "
<div id='menu'>
<h3 onclick='menuHide(this,event)'>$msg[7]</h3>
<ul>
	<li><a href='./admin.php?categ=docs'>".$msg['admin_menu_exemplaires']."</a></li>
	<li><a href='./admin.php?categ=notices'>".$msg['admin_menu_notices']."</a></li>
	<li><a href='./admin.php?categ=docnum'>".$msg["admin_menu_upload_docnum"]."</a></li>
	<li><a href='./admin.php?categ=collstate'>".$msg['admin_etats_collections']."</a></li>
	<li><a href='./admin.php?categ=abonnements'>".$msg['admin_menu_abonnements']."</a></li>
	<li><a href='./admin.php?categ=empr'>$msg[22]</a></li>
	<li><a href='./admin.php?categ=users'>$msg[25]</a></li>
</ul>
<h3 onclick='menuHide(this,event)'>$msg[opac_admin_menu]</h3>
<ul>
	<li><a href='./admin.php?categ=infopages'>".$msg["infopages_admin_menu"]."</a></li>
	<li><a href='./admin.php?categ=opac&sub=search_persopac&section=liste'>".$msg["search_persopac_list_title"]."</a></li>
	<li><a href='./admin.php?categ=opac&sub=navigopac&action='>".$msg["exemplaire_admin_navigopac"]."</a></li>
	".($pmb_logs_activate?"<li><a href='./admin.php?categ=opac&sub=stat&section=view_list'>".$msg["stat_opac_menu"]."</a></li>":"")."
	".($opac_visionneuse_allow?"<li><a href='./admin.php?categ=visionneuse'>".$msg["visionneuse_admin_menu"]."</a></li>":"")."
</ul>
<h3 onclick='menuHide(this,event)'>$msg[admin_menu_act]</h3>
<ul>
	<li><a href='./admin.php?categ=proc&sub=proc&action='>".$msg['admin_menu_act_perso']."</a></li>
	<li><a href='./admin.php?categ=proc&sub=clas&action='>".$msg['admin_menu_act_perso_clas']."</a></li>
</ul>
<h3 onclick='menuHide(this,event)'>$msg[admin_menu_modules]</h3>
<ul>
";

if ($pmb_quotas_avances) $admin_menu_new.="<li><a href='./admin.php?categ=quotas'>".$msg["admin_quotas"]."</a></li>";

if ($pmb_utiliser_calendrier) $admin_menu_new.="<li><a href='./admin.php?categ=calendrier'>".$msg["admin_calendrier"]."</a></li>";

if (($pmb_gestion_financiere)&&(($pmb_gestion_abonnement==2)||($pmb_gestion_tarif_prets==2)||($pmb_gestion_amende))) $admin_menu_new.="<li><a href='./admin.php?categ=finance'>".$msg["admin_gestion_financiere"]."</a></li>";

$admin_menu_new.="</ul>
<ul>
	<li><a href='./admin.php?categ=import'>$msg[519]</a></li>
	<li><a href='./admin.php?categ=convert'>".$msg["admin_conversion"]."</a></li>
</ul>
<ul>
	<li><a href='./admin.php?categ=misc'>$msg[27]</a></li>
</ul>
<ul>
	<li><a href='./admin.php?categ=z3950'>Z39.50</a></li>
	<li><a href='./admin.php?categ=external_services'>".$msg["es_admin_menu"]."</li>
	".($pmb_allow_external_search?"<li><a href='./admin.php?categ=connecteurs'>".$msg["admin_connecteurs_menu"]."</a></li>":"")."
	".($pmb_selfservice_allow?"<li><a href='./admin.php?categ=selfservice'>".$msg["selfservice_admin_menu"]."</a></li>":"")."
</ul>
<ul>
	<li><a href='./admin.php?categ=sauvegarde'>$msg[28]</a></li>
</ul>";

if ($acquisition_active) $admin_menu_new.="\n<ul><li><a href='./admin.php?categ=acquisition'>".$msg["admin_acquisition"]."</a></li></ul>";

//pour les tranferts
if ($pmb_transferts_actif) 
	$admin_menu_new.="\n<ul><li><a href='./admin.php?categ=transferts'>".$msg[admin_menu_transferts]."</a></li></ul>";

if ($gestion_acces_active==1) {
	$admin_menu_new.="\n<ul><li><a href='./admin.php?categ=acces'>".$msg["admin_menu_acces"]."</a></li></ul>";
}

if ($pmb_javascript_office_editor) $admin_menu_new.="\n<ul><li><a href='./admin.php?categ=html_editor'>".$msg["admin_html_editor"]."</a></li></ul>";

if($demandes_active) $admin_menu_new.="\n<ul><li><a href='./admin.php?categ=demandes'>".$msg["admin_demandes"]."</a></li></ul>";

$admin_menu_new.="</div>";


// ---------------------------------------------------------------------------
//		Menus horizontaux : sous-onglets
// ---------------------------------------------------------------------------
// $admin_menu_docs : menu Exemplaires
$admin_menu_docs = "
<h1>$msg[admin_menu_exemplaires] <span>> !!menu_sous_rub!!</span></h1>
<div class='hmenu'>
	<span".ongletSelect("categ=docs&sub=typdoc").">
		<a title='$msg[724]' href='./admin.php?categ=docs&sub=typdoc&action='>
			$msg[admin_menu_docs_type]
		</a>
	</span>
	<span".ongletSelect("categ=docs&sub=location").">
		<a title='$msg[728]' href='./admin.php?categ=docs&sub=location&action='>
			$msg[21]
		</a>
	</span>
	<span".ongletSelect("categ=docs&sub=section").">
		<a title='$msg[726]' href='./admin.php?categ=docs&sub=section&action='>
			$msg[19]
		</a>
	</span>
	<span".ongletSelect("categ=docs&sub=statut").">
		<a title='$msg[727]' href='./admin.php?categ=docs&sub=statut&action='>
			$msg[20]
		</a>
	</span>
	<span".ongletSelect("categ=docs&sub=codstat").">
		<a title='$msg[725]' href='./admin.php?categ=docs&sub=codstat&action='>
			$msg[24]
		</a>
	</span>
	<span".ongletSelect("categ=docs&sub=lenders").">
		<a title='$msg[732]' href='./admin.php?categ=docs&sub=lenders&action='>
			$msg[554]
		</a>
	</span>
	<span".ongletSelect("categ=docs&sub=perso").">
		<a title='$msg[admin_menu_docs_perso]' href='./admin.php?categ=docs&sub=perso&action='>
			$msg[admin_menu_docs_perso]
		</a>
	</span>
</div>
";

// $admin_menu_notices : menu Notices
$admin_menu_notices = "
<h1>$msg[admin_menu_notices] <span>> !!menu_sous_rub!!</span></h1>
<div class='hmenu'>
	<span".ongletSelect("categ=notices&sub=orinot").">
		<a title='$msg[orinot_origine]' href='./admin.php?categ=notices&sub=orinot&action='>
			$msg[orinot_origine_short]
		</a>
	</span>
	<span".ongletSelect("categ=notices&sub=statut").">
		<a title='$msg[admin_menu_noti_statut]' href='./admin.php?categ=notices&sub=statut&action='>
			$msg[admin_menu_noti_statut]
		</a>
	</span>
	<span".ongletSelect("categ=notices&sub=perso").">
		<a title='$msg[admin_menu_noti_perso]' href='./admin.php?categ=notices&sub=perso&action='>
			$msg[admin_menu_noti_perso]
		</a>
	</span>
</div>
";

// $admin_menu_notices : menu Etats des collections
$admin_menu_collstate = "
<h1>".$msg["admin_menu_collstate"]." <span>> !!menu_sous_rub!!</span></h1>
<div class='hmenu'>
	<span".ongletSelect("categ=notices&sub=emplacement").">
		<a title='".$msg["admin_menu_collstate_emplacement"]."' href='./admin.php?categ=collstate&sub=emplacement&action='>
			".$msg["admin_menu_collstate_emplacement"]."
		</a>
	</span>
	<span".ongletSelect("categ=notices&sub=support").">
		<a title='".$msg["admin_menu_collstate_support"]."' href='./admin.php?categ=collstate&sub=support&action='>
			".$msg["admin_menu_collstate_support"]."
		</a>
	</span>
	<span".ongletSelect("categ=notices&sub=statut").">
		<a title='".$msg["admin_menu_collstate_statut"]."' href='./admin.php?categ=collstate&sub=statut&action='>
			".$msg["admin_menu_collstate_statut"]."
		</a>
	</span>
	<span".ongletSelect("categ=notices&sub=perso").">
		<a title='$msg[admin_menu_collstate_perso]' href='./admin.php?categ=collstate&sub=perso&action='>
			".$msg["admin_collstate_collstate_perso"]."
		</a>
	</span>
</div>
";
		
$admin_menu_search_persopac = "
<h1>".$msg["admin_menu_search_persopac"]." <span>> !!menu_sous_rub!!</span></h1>
<div class='hmenu'>
	<span".ongletSelect("categ=search_persopac&sub=liste").">
		<a title='".$msg["search_persopac_list_title"]."' href='./admin.php?categ=search_persopac&sub=liste&action='>
			".$msg["search_persopac_list_title"]."
		</a>
	</span>

</div>
";

//Menu opac en gestion
$admin_menu_opac = "
<h1><span>".$msg['admin_menu_opac']." > !!menu_sous_rub!!</span></h1>";

// $admin_menu_abonnements : menu Abonnements
$admin_menu_abonnements = "
<h1>$msg[admin_menu_abonnements] <span>> !!menu_sous_rub!!</span></h1>
<div class='hmenu'>
	<span".ongletSelect("categ=abonnements&sub=periodicite").">
		<a title='$msg[admin_menu_abonnements_periodicite]' href='./admin.php?categ=abonnements&sub=periodicite&action='>
			$msg[admin_menu_abonnements_periodicite]
		</a>
	</span>
</div>
";

// $admin_menu_empr : menu Lecteurs
// show ldap_import only if $ldap_accessible=1
$admin_menu_empr = "
<h1>$msg[22] <span>> !!menu_sous_rub!!</span></h1>
<div class='hmenu'>
	<span".ongletSelect("categ=empr&sub=categ&action").">
		<a title='$msg[729]' href='./admin.php?categ=empr&sub=categ&action='>
			".$msg[lecteurs_categories]."
		</a>
	</span>
	<span".ongletSelect("categ=empr&sub=statut&action").">
		<a title='$msg[empr_statut_menu]' href='./admin.php?categ=empr&sub=statut&action='>
			$msg[empr_statut_menu]
		</a>
	</span>
	<span".ongletSelect("categ=empr&sub=codstat&action").">
		<a title='$msg[730]' href='./admin.php?categ=empr&sub=codstat&action='>
			$msg[24]
		</a>
	</span>
	<span".ongletSelect("categ=empr&sub=implec").">
		<a title='$msg[import_lec_alt]' href='./admin.php?categ=empr&sub=implec&action='>
			$msg[import_lec_lien]
		</a>
	</span>
";
if ($ldap_accessible) $admin_menu_empr .= "
	<span".ongletSelect("categ=empr&sub=ldap").">
		<a title='$msg[import_ldap]' href='./admin.php?categ=empr&sub=ldap&action='>
			$msg[import_ldap]
		</a>
	</span>";
if ($ldap_accessible) $admin_menu_empr .= "
	<span".ongletSelect("categ=empr&sub=exldap").">
		<a title='$msg[menu_suppr_exldap]' href='./admin.php?categ=empr&sub=exldap&action='>
			$msg[menu_suppr_exldap]
		</a>
	</span>";
$admin_menu_empr .= "
	<span".ongletSelect("categ=empr&sub=parperso").">
		<a title='$msg[parametres_perso_lec_alt]' href='./admin.php?categ=empr&sub=parperso&action='>
			$msg[parametres_perso_lec_lien]
		</a>
	</span>
</div>";

// $admin_menu_users : menu Utilisateurs
$admin_menu_users = "
<h1>$msg[25] <span>> !!menu_sous_rub!!</span></h1>
<div class='hmenu'>
	<span".ongletSelect("categ=users&sub=users").">
		<a title='$msg[731]' href='./admin.php?categ=users&sub=users&action='>
			$msg[26]
		</a>
	</span>
	<span".ongletSelect("categ=users&sub=groups").">
		<a title='$msg[731]' href='./admin.php?categ=users&sub=groups&action='>
			$msg[admin_usr_grp_ges]
		</a>
	</span>
	</div>
";

// $admin_menu_import : menu Imports
$admin_menu_import = "
<h1>$msg[519] <span>> !!menu_sous_rub!!</span></h1>
<div class='hmenu'>
	<span".ongletSelect("categ=import&sub=import").">
		<a title='$msg[733]' href='./admin.php?categ=import&sub=import&action='>
			$msg[500]
		</a>
	</span>
	<span".ongletSelect("categ=import&sub=import_expl").">
		<a title='$msg[734]' href='./admin.php?categ=import&sub=import_expl&action='>
			$msg[520]
		</a>
	</span>
	<span".ongletSelect("categ=import&sub=pointage_expl").">
		<a href='./admin.php?categ=import&sub=pointage_expl&action='>
			".$msg[569]."
		</a>
	</span>
</div>
";

// $admin_menu_convert : menu Outils de Conversion/Export de formats
$admin_menu_convert = "
<h1>$msg[admin_conversion] <span>> !!menu_sous_rub!!</span></h1>
<div class='hmenu'>
	<span".ongletSelect("categ=convert&sub=import").">
		<a title='$msg[admin_convExterne]' href='./admin.php?categ=convert&sub=import&action='>
			$msg[admin_convExterne]
		</a>
	</span>
	<span".ongletSelect("categ=convert&sub=export").">
		<a title='$msg[admin_ExportPMB]' href='./admin.php?categ=convert&sub=export&action='>
			$msg[admin_ExportPMB]
		</a>
	</span>
	<span".ongletSelect("categ=convert&sub=paramgestion").">
		<a title='".htmlentities($msg['admin_param_export_gestion'],ENT_QUOTES,$charset)."' href='./admin.php?categ=convert&sub=paramgestion&action='>
			$msg[admin_param_export_gestion]
		</a>
	</span>
	<span".ongletSelect("categ=convert&sub=paramopac").">
		<a title='".htmlentities($msg['admin_param_export_opac'],ENT_QUOTES,$charset)."' href='./admin.php?categ=convert&sub=paramopac&action='>
			$msg[admin_param_export_opac]
		</a>
	</span>
</div>
";

// $admin_menu_misc : menu Outils
$admin_menu_misc = "
<h1>$msg[27] <span>> !!menu_sous_rub!!</span></h1>
<div class='hmenu'>
	<span".ongletSelect("categ=netbase").">
		<a title='$msg[735]' href='./admin.php?categ=netbase'>
			$msg[329]
		</a>
	</span>
	<span".ongletSelect("categ=chklnk").">
		<a title='".$msg['chklnk_titre']."' href='./admin.php?categ=chklnk'>
			".$msg['chklnk_titre']."
		</a>
	</span>
	<span".ongletSelect("categ=alter&sub=").">
		<a title='$msg[740]' href='./admin.php?categ=alter&sub='>
			$msg[1801]
		</a>
	</span>
	<span".ongletSelect("categ=misc&sub=tables").">
		<a title='$msg[740]' href='./admin.php?categ=misc&sub=tables'>
			$msg[31]
		</a>
	</span>
	<span".ongletSelect("categ=misc&sub=mysql").">
		<a title='$msg[741]' href='./admin.php?categ=misc&sub=mysql&action='>
			$msg[32]
		</a>
	</span>
	<span".ongletSelect("categ=param").">
		<a title='$msg[1600]' href='./admin.php?categ=param&action='>
			$msg[1600]
		</a>
	</span>
</div>
";

// $admin_menu_z3950 : menu z39.50
$admin_menu_z3950 = "
<h1>Z39.50 <span>> !!menu_sous_rub!!</span></h1>
<div class='hmenu'>
	<span".ongletSelect("categ=z3950&sub=zbib").">
		<a title='".$msg["z3950_menu_admin_title"]."' href='./admin.php?categ=z3950&sub=zbib'>
			".$msg["z3950_serveurs"]."
		</a>
	</span>
</div>
";

// $admin_menu_sauvegarde : menu Sauvegarde
$admin_menu_sauvegarde = "
<h1>$msg[28] <span>> !!menu_sous_rub!!</span></h1>
<div class='hmenu'>
	<span".ongletSelect("categ=sauvegarde&sub=lieux").">
		<a title='$msg[sauv_menu_lieux_c]' href='./admin.php?categ=sauvegarde&sub=lieux'>
			$msg[sauv_menu_lieux]
		</a>
	</span>
	<span".ongletSelect("categ=sauvegarde&sub=tables").">
		<a title='$msg[sauv_menu_tables_c]' href='./admin.php?categ=sauvegarde&sub=tables'>
			$msg[sauv_menu_tables]
		</a>
	</span>
	<span".ongletSelect("categ=sauvegarde&sub=gestsauv").">
		<a title='$msg[sauv_menu_jeux_c]' href='./admin.php?categ=sauvegarde&sub=gestsauv'>
			$msg[sauv_menu_jeux]
		</a>
	</span>
	<span".ongletSelect("categ=sauvegarde&sub=launch").">
		<a title='$msg[sauv_menu_launch_c]' href='./admin.php?categ=sauvegarde&sub=launch'>
			$msg[sauv_menu_launch]
		</a>
	</span>
	<span".ongletSelect("categ=sauvegarde&sub=list").">
		<a title='$msg[sauv_menu_liste_c]' href='./admin.php?categ=sauvegarde&sub=list'>
			$msg[sauv_menu_liste]
		</a>
	</span>
</div>
";

// $admin_menu_calendrier : menu Calendrier
$admin_menu_calendrier = "
<h1>".$msg["admin_calendrier"]." <span>> !!menu_sous_rub!!</span></h1>";

// $admin_menu_finance : menu Gestion financiere
$admin_menu_finance = "
<h1>".$msg["admin_gestion_financiere"]." <span>> !!menu_sous_rub!!</span></h1>
<div class='hmenu'>";
if (($pmb_gestion_financiere)&&($pmb_gestion_abonnement==2)) 
	$admin_menu_finance.="
		<span".ongletSelect("categ=finance&sub=abts").">
			<a title='".$msg["finance_abts"]."' href='./admin.php?categ=finance&sub=abts'>
				".$msg["finance_abts"]."
			</a>
		</span>
	";
if (($pmb_gestion_financiere)&&($pmb_gestion_tarif_prets==2)) 
	$admin_menu_finance.="
	<span".ongletSelect("categ=finance&sub=prets").">
		<a title='".$msg["finance_prets"]."' href='./admin.php?categ=finance&sub=prets'>
			".$msg["finance_prets"]."
		</a>
	</span>
	";
if (($pmb_gestion_financiere)&&($pmb_gestion_amende))
	$admin_menu_finance.="
	<span".ongletSelect("categ=finance&sub=amendes").">
		<a title='".$msg["finance_amendes"]."' href='./admin.php?categ=finance&sub=amendes'>
			".$msg["finance_amendes"]."
		</a>
	</span>
	<span".ongletSelect("categ=finance&sub=amendes_relance").">
		<a title='".$msg["finance_amendes_relances"]."' href='./admin.php?categ=finance&sub=amendes_relance'>
			".$msg["finance_amendes_relances"]."
		</a>
	</span>
";
$admin_menu_finance.="
	<span".ongletSelect("categ=finance&sub=blocage").">
		<a title='".$msg["finance_blocage"]."' href='./admin.php?categ=finance&sub=blocage'>
			".$msg["finance_blocage"]."
		</a>
	</span>
</div>";


// $admin_menu_acquisition : menu Acquisition
$admin_menu_acquisition = "
<h1>$msg[acquisition_menu] <span>> !!menu_sous_rub!!</span></h1>
<div class='hmenu'>
	<span".ongletSelect("categ=acquisition&sub=entite").">
		<a title='$msg[acquisition_menu_ref_entite]' href='./admin.php?categ=acquisition&sub=entite'>
			$msg[acquisition_menu_ref_entite]
		</a>
	</span>
	<span".ongletSelect("categ=acquisition&sub=compta").">
		<a title='$msg[acquisition_menu_ref_compta]' href='./admin.php?categ=acquisition&sub=compta'>
			$msg[acquisition_menu_ref_compta]
		</a>
	</span>
";

//Pas d'affichage de la tva sur achats si on ne la gere pas 
if ($acquisition_gestion_tva) $admin_menu_acquisition.= "
	<span".ongletSelect("categ=acquisition&sub=tva").">
		<a title='$msg[acquisition_menu_ref_tva]' href='./admin.php?categ=acquisition&sub=tva'>
			$msg[acquisition_menu_ref_tva]
		</a>
	</span>
";
$admin_menu_acquisition.= "
	<span".ongletSelect("categ=acquisition&sub=type").">
		<a title='$msg[acquisition_menu_ref_type]' href='./admin.php?categ=acquisition&sub=type'>
			$msg[acquisition_menu_ref_type]
		</a>
	</span>
	<span".ongletSelect("categ=acquisition&sub=frais").">
		<a title='$msg[acquisition_menu_ref_frais]' href='./admin.php?categ=acquisition&sub=frais'>
			$msg[acquisition_menu_ref_frais]
		</a>
	</span>
	<span".ongletSelect("categ=acquisition&sub=mode").">
		<a title='$msg[acquisition_menu_ref_mode]' href='./admin.php?categ=acquisition&sub=mode'>
			$msg[acquisition_menu_ref_mode]
		</a>
	</span>
	<span".ongletSelect("categ=acquisition&sub=budget").">
		<a title='$msg[acquisition_menu_ref_budget]' href='./admin.php?categ=acquisition&sub=budget'>
			$msg[acquisition_menu_ref_budget]
		</a>
	</span>
";
if($acquisition_sugg_categ=='1') $admin_menu_acquisition.= "
	<span".ongletSelect("categ=acquisition&sub=categ").">
		<a title='$msg[acquisition_menu_ref_categ]' href='./admin.php?categ=acquisition&sub=categ'>
			$msg[acquisition_menu_ref_categ]
		</a>
	</span>";
$admin_menu_acquisition.= "
	<span".ongletSelect("categ=acquisition&sub=src").">
		<a title='$msg[acquisition_menu_ref_src]' href='./admin.php?categ=acquisition&sub=src'>
			$msg[acquisition_menu_ref_src]
		</a>
	</span>";
$admin_menu_acquisition.= "
</div>
";

//Services Externes
$admin_menu_external_services = "
<h1>".$msg["es_admin_menu"]." <span>> !!menu_sous_rub!!</span></h1>
<div class='hmenu'>
	<span".ongletSelect("categ=external_services&sub=general").">
		<a title='".$msg["es_admin_general"]."' href='./admin.php?categ=external_services&sub=general'>
			".$msg["es_admin_general"]."
		</a>
	</span>
	<span".ongletSelect("categ=external_services&sub=peruser").">
		<a title='".$msg["es_admin_peruser"]."' href='./admin.php?categ=external_services&sub=peruser'>
			".$msg["es_admin_peruser"]."
		</a>
	</span>
	<span".ongletSelect("categ=external_services&sub=esusers").">
		<a title='".$msg["es_admin_esusers"]."' href='./admin.php?categ=external_services&sub=esusers'>
			".$msg["es_admin_esusers"]."
		</a>
	</span>
	<span".ongletSelect("categ=external_services&sub=esusergroups").">
		<a title='".$msg["es_admin_esusergroups"]."' href='./admin.php?categ=external_services&sub=esusergroups'>
			".$msg["es_admin_esusergroups"]."
		</a>
	</span>
	<!--<span".ongletSelect("categ=external_services&sub=es_tests").">
		<a title='Tests' href='./admin.php?categ=external_services&sub=es_tests'>
			Tests
		</a>
	</span>-->
</div>";

//Connecteurs pour web services
$admin_menu_connecteurs = "
<h1>".$msg["admin_menu_connecteurs"]." <span>> !!menu_sous_rub!!</span></h1>
<div class='hmenu'>
	<span".ongletSelect("categ=connecteurs&sub=in").">
		<a title='".$msg["admin_connecteurs_in"]."' href='./admin.php?categ=connecteurs&sub=in'>
			".$msg["admin_connecteurs_in"]."
		</a>
	</span>
	<span".ongletSelect("categ=connecteurs&sub=categ").">
		<a title='".$msg["admin_connecteurs_categ"]."' href='./admin.php?categ=connecteurs&sub=categ'>
			".$msg["admin_connecteurs_categ"]."
		</a>
	</span>
	<span".ongletSelect("categ=connecteurs&sub=out").">
		<a title='".$msg["admin_connecteurs_out"]."' href='./admin.php?categ=connecteurs&sub=out'>
			".$msg["admin_connecteurs_out"]."
		</a>
	</span>
	<span".ongletSelect("categ=connecteurs&sub=out_auth").">
		<a title='".$msg["admin_connecteurs_outauth"]."' href='./admin.php?categ=connecteurs&sub=out_auth'>
			".$msg["admin_connecteurs_outauth"]."
		</a>
	</span>
	<span".ongletSelect("categ=connecteurs&sub=out_sets").">
		<a title='".$msg["admin_connecteurs_sets"]."' href='./admin.php?categ=connecteurs&sub=out_sets'>
			".$msg["admin_connecteurs_sets"]."
		</a>
	</span>
	<span".ongletSelect("categ=connecteurs&sub=categout_sets").">
		<a title='".$msg["admin_connecteurs_categsets"]."' href='./admin.php?categ=connecteurs&sub=categout_sets'>
			".$msg["admin_connecteurs_categsets"]."
		</a>
	</span>
</div>";

//Borne de prêt 
$admin_menu_selfservice = "
<h1>".$msg["selfservice_admin_menu"]." <span>> !!menu_sous_rub!!</span></h1>
<div class='hmenu'>
	<span".ongletSelect("categ=selfservice&sub=pret").">
		<a title='".$msg["selfservice_admin_pret"]."' href='./admin.php?categ=selfservice&sub=pret'>
			".$msg["selfservice_admin_pret"]."
		</a>
	</span>
	<span".ongletSelect("categ=selfservice&sub=retour").">
		<a title='".$msg["selfservice_admin_retour"]."' href='./admin.php?categ=selfservice&sub=retour'>
			".$msg["selfservice_admin_retour"]."
		</a>
	</span>
</div>";

//Visionneuse
$admin_menu_visionneuse = "
<h1>".$msg["admin_menu_opac"]." > ".$msg["visionneuse_admin_menu"]."<span> > !!menu_sous_rub!!</span></h1>
<div class='hmenu'>
	<span".ongletSelect("categ=visionneuse&sub=class").">
		<a title='".$msg["visionneuse_admin_class"]."' href='./admin.php?categ=visionneuse&sub=class'>
			".$msg["visionneuse_admin_class"]."
		</a>
	</span>
	<span".ongletSelect("categ=visionneuse&sub=mimetype").">
		<a title='".$msg["visionneuse_admin_mimetype"]."' href='./admin.php?categ=visionneuse&sub=mimetype'>
			".$msg["visionneuse_admin_mimetype"]."
		</a>
	</span>
</div>";

// Menus pour actions perso
$admin_menu_act = "
<h1>".$msg["admin_menu_act"]." > !!menu_sous_rub!!</h1>";



//Menus pour les transferts
$admin_menu_transferts = "
<h1>".$msg["admin_menu_transferts"]." <span>> !!menu_sous_rub!!</span></h1>
<div class=\"hmenu\">
	<span".ongletSelect("categ=transferts&sub=general").">
		<a title='".$msg["admin_tranferts_general"]."' href='./admin.php?categ=transferts&sub=general'>
			".$msg["admin_tranferts_general"]."
		</a>
	</span>
	<span".ongletSelect("categ=transferts&sub=circ").">
		<a title='".$msg["admin_tranferts_circ"]."' href='./admin.php?categ=transferts&sub=circ'>
			".$msg["admin_tranferts_circ"]."
		</a>
	</span>
	<span".ongletSelect("categ=transferts&sub=opac").">
		<a title='".$msg["admin_tranferts_opac"]."' href='./admin.php?categ=transferts&sub=opac'>
			".$msg["admin_tranferts_opac"]."
		</a>
	</span>
	<span".ongletSelect("categ=transferts&sub=ordreloc").">
		<a title='".$msg["admin_tranferts_ordre_localisation"]."' href='./admin.php?categ=transferts&sub=ordreloc'>
			".$msg["admin_tranferts_ordre_localisation"]."
		</a>
	</span>
	<span".ongletSelect("categ=transferts&sub=statutsdef").">
		<a title='".$msg["admin_tranferts_statuts_defaut"]."' href='./admin.php?categ=transferts&sub=statutsdef'>
			".$msg["admin_tranferts_statuts_defaut"]."
		</a>
	</span>
	<span".ongletSelect("categ=transferts&sub=purge").">
		<a title='".$msg["admin_tranferts_purge"]."' href='./admin.php?categ=transferts&sub=purge'>
		".$msg["admin_tranferts_purge"]."
		</a>
	</span>
</div>
";

//$admin_menu_upload_docnum = upload des documents numériques
$admin_menu_upload_docnum ="
<h1>".$msg["admin_menu_upload_docnum"]." <span>> !!menu_sous_rub!!</span></h1>
<div class=\"hmenu\">
	<span".ongletSelect("categ=docnum&sub=rep").">
		<a title='".htmlentities($msg["upload_repertoire"],ENT_QUOTES,$charset)."' href='./admin.php?categ=docnum&sub=rep'>
			".$msg["upload_repertoire"]."
		</a>
	</span>
</div>";

//$admin_menu_demandes = demandes de recherche
$admin_menu_demandes ="
<h1>".$msg["admin_demandes"]." <span>> !!menu_sous_rub!!</span></h1>
<div class=\"hmenu\">
	<span".ongletSelect("categ=demandes&sub=theme").">
		<a title='".htmlentities($msg["demandes_theme"],ENT_QUOTES,$charset)."' href='./admin.php?categ=demandes&sub=theme'>
			".$msg["demandes_theme"]."
		</a>
	</span>
	<span".ongletSelect("categ=demandes&sub=type").">
		<a title='".htmlentities($msg["demandes_type"],ENT_QUOTES,$charset)."' href='./admin.php?categ=demandes&sub=type'>
			".$msg["demandes_type"]."
		</a>
	</span>
</div>";


//    ----------------------------------
// $admin_layout : layout page administration
$admin_layout = "
<!-- conteneur -->
<div id='conteneur'  class='$current_module'>
$admin_menu_new
<!-- contenu -->
<div id='contenu'>
!!menu_contextuel!!
";

// $admin_layout_end : layout page administration (fin)
$admin_layout_end = '
</div>
<!-- /conteneur -->
</div>
';


// $admin_user_Javascript : scripts pour la gestion des utilisateurs
$admin_user_javascript = "
<script type='text/javascript'>
	function test_pwd(form, status)
	{
		if(form.form_pwd.value.length == 0)
		{
				alert(\"$msg[79]\");
				return false;
		}
		if(form.form_pwd.value != form.form_pwd2.value)
		{
				alert(\"$msg[80]\");
				return false;
		}

		return true;
	}

	function test_form_create(form, status)
	{
		if(form.form_login.value.length == 0)
		{
				alert(\"$msg[81]\");
				return false;
		}

		if(!form.form_admin.checked && !form.form_catal.checked && !form.form_circ.checked && !form.form_extensions.checked)
		{
				alert(\"$msg[84]\");
				return false;
		}

		if(status == 1) {
				if(form.form_pwd.value.length == 0)
				{
					alert(\"$msg[82]\");
					return false;
				}
				if(form.form_pwd.value != form.form_pwd2.value)
				{
					alert(\"$msg[83]\");
					return false;
				}

		}

		return true;
	}
</script>
";

// $admin_npass_form : template form changement password
$admin_npass_form = "
<form class='form-$current_module' id='userform' name='userform' method='post' action='./admin.php?categ=users&sub=users&action=pwd&id=!!id!!'>
<h3><span onclick='menuHide(this,event)'>$msg[86] !!myUser!!</span></h3>
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_pwd'>$msg[87]</label>
		<input class='saisie-20em' id='form_pwd' type='password' name='form_pwd' />
		</div>
	<div class='row'>
		<label class='etiquette' for='form_pwd2'>$msg[88]</label>
		<input class='saisie-20em' id='form_pwd2' type='password' name='form_pwd2' />
		</div>
	</div>
<div class='row'>
	<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=users&sub=users'\" />&nbsp;
	<input class='bouton' type='submit' value=' $msg[77] ' onClick=\"return test_pwd(this.form)\" />
	</div>
</form>
";

// $admin_user_form : template form user
$admin_user_form = "
<script type=\"text/javascript\">
<!--
function setValue(f_element, factor) {
    var maxv = 50;
    var minv = 1;

    var vl = document.forms['account_form'].elements[f_element].value;
    if((vl < maxv) && (factor == 1))
       vl++;
    if((vl > minv) && (factor == -1))
        vl--;
    document.forms['account_form'].elements[f_element].value = vl;
}
function test_pwd(form, status) {
	if(form.passw.value.length != 0) {
		if(form.passw.value != form.passw2.value) {
			alert(\"$msg[80]\");
			return false;
		}
    }
	return true;
}

function account_calcule_section(selectBox) {
	for (i=0; i<selectBox.options.length; i++) {
		id=selectBox.options[i].value;
	    list=document.getElementById(\"docloc_section\"+id);
	    list.style.display=\"none\";
	}

	id=selectBox.options[selectBox.selectedIndex].value;
	list=document.getElementById(\"docloc_section\"+id);
	list.style.display=\"block\";
}
-->
</script>
<form class='form-$current_module' name='userform' method='post' action='./admin.php?categ=users&sub=users&action=update&id=!!id!!'>
<h3><span onclick='menuHide(this,event)'>!!title!!</span></h3>
<div class='form-contenu'>
	<div class='row'>
		<div class='colonne3'>
			<label class='etiquette'>$msg[91] &nbsp;</label><br />
			<input type='text' class='saisie-20em' name='form_login' value='!!login!!' />
		</div>
		<div class='colonne3'>
			<label class='etiquette'>$msg[67] &nbsp;</label><br />
			<input type='text' class='saisie-20em' name='form_nom' value='!!nom!!' />
		</div>
		<div class='colonne_suite'>
			<label class='etiquette'>$msg[68] &nbsp;</label><br />
			<input type='text' class='saisie-20em' name='form_prenom' value='!!prenom!!' />
		</div>
	</div>

	<div class='row'>
		<div class='colonne3'>
			<label class='etiquette'>$msg[user_langue] &nbsp;</label><br />
			!!select_lang!!
		</div>
		<div class='colonne_suite'>
			<!-- sel_group -->
		</div>
	</div>

	<div class='row'>
		<div class='colonne3'>
			<label class='etiquette'>".$msg['email']." &nbsp;</label><br />
			<input type='text' class='saisie-20em' name='form_user_email' value='!!user_email!!' />
		</div>
		<div class='colonne_suite'>
			<br />
			<input type='checkbox' class='checkbox' !!alter_resa_mail!! value='1' name='form_user_alert_resamail' />
			<label class='etiquette'>".$msg['alert_resa_user_mail']." &nbsp;</label>
		</div>
	</div>
<div class='row'><hr /></div>
	
	!!password_field!!

<div class='row'>
	<div class='row'>
		<label class='etiquette' for='form_nb_per_page_search'>$msg[nb_enreg_par_page]</label>
	</div>
	<div class='colonne4'>
	<!--	Nombre d'enregistrements par page en recherche	-->
		<label class='etiquette' for='form_nb_per_page_search'>$msg[900]</label><br />
		<input type='text' class='saisie-10em' name='form_nb_per_page_search' value='!!nb_per_page_search!!' size='4' />
	</div>
	<div class='colonne4'>
	<!--	Nombre d'enregistrements par page en sï¿½lection d'autoritï¿½s	-->
		<label class='etiquette'>${msg[901]}</label><br />
		<input class='saisie-10em' type='text' id='form_nb_per_page_select' name='form_nb_per_page_select' value='!!nb_per_page_select!!' size='4' />
	</div>	
	<div class='colonne_suite'>
		<label class='etiquette' for='form_nb_per_page_gestion'>${msg[902]}</label><br />
		<input type='text' class='saisie-10em' id='form_nb_per_page_gestion' name='form_nb_per_page_gestion' value='!!nb_per_page_gestion!!' size='4' />
	</div>
</div>

<div class='row'><hr /></div>
<div class='row'>
	<div class='row'><label class='etiquette'>$msg[92]</label></div>

<div class='colonne4'>
		<input type='checkbox' class='checkbox' !!circ_flg!! value='1' id='form_circ' name='form_circ' /><label for='form_circ'>$msg[5]</label><br />\n
		<input type='checkbox' class='checkbox' !!restrictcirc_flg!! value='1' id='form_restrictcirc' name='form_restrictcirc' /><label for='form_restrictcirc'><i>".$msg["restrictcirc_auth"]."</i></label><br />
		<input type='checkbox' class='checkbox' !!admin_flg!! value='1' id='form_admin' name='form_admin' /><label for='form_admin'>$msg[7]</label>\n
	</div>
<div class='colonne4'>
		<input type='checkbox' class='checkbox' !!catal_flg!! value='1' id='form_catal' name='form_catal' /><label for='form_catal'>$msg[93]</label><br />\n
		<input type='checkbox' class='checkbox' !!edit_flg!! value='1' id='form_edit' name='form_edit' /><label for='form_edit'>$msg[1100]</label><br />\n
		<input type='checkbox' class='checkbox' !!sauv_flg!! value='1' id='form_sauv' name='form_sauv' /><label for='form_sauv'>$msg[28]</label>\n	
	</div>
<div class='colonne4'>
	<input type='checkbox' class='checkbox' !!auth_flg!! value='1' id='form_auth' name='form_auth' /><label for='form_auth'>$msg[132]</label><br />\n";
if ($dsi_active) $admin_user_form .= "<input type='checkbox' class='checkbox' !!dsi_flg!! value='1' id='form_dsi' name='form_dsi' /><label for='form_dsi'>".$msg["dsi_droit"]."</label><br />\n";
	else $admin_user_form .= "<br />\n";
$admin_user_form .= "<input type='checkbox' class='checkbox' !!pref_flg!! value='1' id='form_pref' name='form_pref' /><label for='form_pref'>$msg[933]</label>\n
	</div>
<div class='colonne_suite'>
	<input type='checkbox' class='checkbox' !!thesaurus_flg!! value='1' id='form_thesaurus' name='form_thesaurus' /><label for='form_thesaurus'>$msg[thesaurus_auth]</label><br />\n";
if ($acquisition_active) $admin_user_form .= "<input type='checkbox' class='checkbox' !!acquisition_flg!! value='1' id='form_acquisition' name='form_acquisition' /><label for='form_acquisition'>".$msg["acquisition_droit"]."</label><br />\n";
	else $admin_user_form .= "<br />\n";
if ($pmb_transferts_actif) $admin_user_form .= "<input type='checkbox' class='checkbox' !!transferts_flg!! value='1' id='form_transferts' name='form_transferts' /><label for='form_transferts'>".$msg["transferts_droit"]."</label><br />\n";
	else $admin_user_form .= "<br />\n";
if ($pmb_extension_tab) $admin_user_form .= "<input type='checkbox' class='checkbox' !!extensions_flg!! value='1' id='form_extensions' name='form_extensions' /><label for='form_extensions'>".$msg["extensions_droit"]."</label><br />\n";
	else $admin_user_form .= "<br />\n";
if ($demandes_active) $admin_user_form .= "<input type='checkbox' class='checkbox' !!demandes_flg!! value='1' id='form_demandes' name='form_demandes' /><label for='form_demandes'>".$msg["demandes_droit"]."</label><br />\n";
	else $admin_user_form .= "<br />\n";
	
$admin_user_form .= "
	</div>
</div>
<div class='row'>
	!!form_param_default!!
</div>
<div class='row'></div>
</div>
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=users&sub=users'\" />&nbsp;
		<input class='bouton' type='submit' value=' $msg[77] ' onClick=\"return test_form_create(this.form, !!form_type!!)\" />
		<input type='hidden' name='form_actif' value='1'>
		</div>
	<div class='right'>
		!!bouton_suppression!!
		</div>
	</div>
<div class='row'>&nbsp;</div>
</form>
";


$user_acquisition_adr_form = "
<div class='row'>
	<div class='child'>
		<div class='colonne2'>".htmlentities($msg['acquisition_adr_liv'], ENT_QUOTES, $charset)."</div>
		<div class='colonne2'>".htmlentities($msg['acquisition_adr_fac'], ENT_QUOTES, $charset)."</div>
	</div>
</div>
<div class='row'>
	<div class='child'>
		<div class='colonne2'>
			<div class='colonne' >					
				<input type='hidden' id='id_adr_liv[!!id_bibli!!]' name='id_adr_liv[!!id_bibli!!]' value='!!id_adr_liv!!' />
				<textarea  id='adr_liv[!!id_bibli!!]' name='adr_liv[!!id_bibli!!]' class='saisie-30emr' readonly='readonly' cols='50' rows='6' wrap='virtual'>!!adr_liv!!</textarea>&nbsp;
			</div>
			<div class='colonne_suite' >
				<input type='button' class='bouton_small' tabindex='1' value='".$msg['parcourir']."' onclick=\"openPopUp('./select.php?what=coord&caller=!!form_name!!&param1=id_adr_liv[!!id_bibli!!]&param2=adr_liv[!!id_bibli!!]&id_bibli=!!id_bibli!!', 'select_adresse', 400, 400, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes'); \" />&nbsp;
				<input type='button' class='bouton_small' tabindex='1' value='X' onclick=\"document.getElementById('id_adr_liv[!!id_bibli!!]').value='0';document.getElementById('adr_liv[!!id_bibli!!]').value='';\" />
			</div>
		</div>
		<div class='colonne2'>
			<div class='colonne'>
				<input type='hidden' id='id_adr_fac[!!id_bibli!!]' name='id_adr_fac[!!id_bibli!!]' value='!!id_adr_fac!!' />
				<textarea id='adr_fac[!!id_bibli!!]' name='adr_fac[!!id_bibli!!]'  class='saisie-30emr' readonly='readonly' cols='50' rows='6' wrap='virtual'>!!adr_fac!!</textarea>&nbsp;
			</div>
			<div class='colonne_suite'>
				<input type='button' class='bouton_small' tabindex='1' value='".$msg['parcourir']."' onclick=\"openPopUp('./select.php?what=coord&caller=!!form_name!!&param1=id_adr_fac[!!id_bibli!!]&param2=adr_fac[!!id_bibli!!]&id_bibli=!!id_bibli!!', 'select_adresse', 400, 400, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes'); \" />&nbsp;
				<input type='button' class='bouton_small' tabindex='1' value='X' onclick=\"document.getElementById('id_adr_fac[!!id_bibli!!]').value='0';document.getElementById('adr_fac[!!id_bibli!!]').value='';\" />
			</div>
		</div>
	</div>
</div>
";

$admin_param_form = "
<form class='form-$current_module' name='paramform' method='post' action='./admin.php?categ=param&action=update&id_param=!!id_param!!#justmodified'>
<h3><span onclick='menuHide(this,event)'>!!form_title!!</span></h3>
<div class='form-contenu'>
	<div class='row'>
		<div class='colonne5' align='right'>
				<label class='etiquette'>$msg[1602] &nbsp;</label>
				</div>
		<div class='colonne_suite'>
				!!type_param!! <input type='hidden' name='form_type_param' value='!!type_param!!' />
				</div>
		</div>
	<div class='row'>&nbsp;</div>
	<div class='row'>
		<div class='colonne5' align='right'>
				<label class='etiquette'>$msg[1603] &nbsp;</label>
				</div>
		<div class='colonne_suite'>
				!!sstype_param!! <input type='hidden' name='form_sstype_param' value='!!sstype_param!!' />
				</div>
		</div>
	<div class='row'>&nbsp;</div>
	<div class='row'>
		<div class='colonne5' align='right'>
				<label class='etiquette'>$msg[1604] &nbsp;</label>
				</div>
		<div class='colonne_suite'>
				<textarea name='form_valeur_param' rows='10' cols='90' wrap='virtual'>!!valeur_param!!</textarea>
				</div>
		</div>
	<div class='row'>&nbsp;</div>
	<div class='row'>
		<div class='colonne5' align='right'>
				<label class='etiquette'>".$msg['param_explication']." &nbsp;</label>
				</div>
		<div class='colonne_suite'>
				<textarea name='comment_param' rows='10' cols='90' wrap='virtual'>!!comment_param!!</textarea>
				</div>
		</div>
	<div class='row'> </div>
	</div>
	<div class='row'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=param'\">
		<input class='bouton' type='submit' value=' $msg[77] ' />
		<input type='hidden' class='text' name='form_id_param' value='!!id_param!!' readonly />
			</div>
</form>
<script type='text/javascript'>document.forms['paramform'].elements['form_valeur_param'].focus();</script>
";


$password_field = "
<div class='row'>
	<div class='colonne4'>
		<label class='etiquette'>$msg[2]</label><br />
		<input type='password' name='form_pwd'>
		</div>
	<div class='colonne_suite'>
		<label class='etiquette'>$msg[88]</label><br />
		<input type='password' name='form_pwd2'>
		</div>
	</div>
<div class='row'>&nbsp;</div>
<hr />
";

// $admin_user_list : template liste utilisateurs
$admin_user_list = "
<div class='row'>&nbsp;</div>
<div class='row'>
	<div class='colonne4'>
		<label class='etiquette'>!!user_name!! (!!user_login!!)</label>
		</div>
	<div class='colonne_suite'>
		!!user_link!!
		</div>
	</div>
<div class='row'>
	<table class='brd'>
		<tr >
			<td class='brd'>!!nusercirc!!$msg[5]</td>
			<td class='brd'>!!nusercatal!!$msg[93]</td>
			<td class='brd'>!!nuserauth!!$msg[132]</td>
			<td class='brd'>!!nuserthesaurus!!".$msg["thesaurus_auth"]."</td>
		</tr><tr>
			<td class='brd'>!!nuserrestrictcirc!!<i>".$msg["restrictcirc_auth"]."</i></td>
			<td class='brd'>!!nuseredit!!$msg[1100]</td>
			<td class='brd'>";
					if ($dsi_active) $admin_user_list .= "!!nuserdsi!!$msg[dsi_droit]</td>";
					else $admin_user_list .= "&nbsp;</td>";
$admin_user_list .= "<td class='brd'>";
					if ($acquisition_active) $admin_user_list .= "!!nuseracquisition!!$msg[acquisition_droit]</td>";
					else $admin_user_list .= "&nbsp;</td>";
$admin_user_list .= "				
		</tr><tr>
			<td class='brd'>!!nuseradmin!!$msg[7]</td>
			<td class='brd'>!!nusersauv!!$msg[28]</td>
			<td class='brd'>!!nuserpref!!$msg[933]</td>
			<td class='brd'>";
				if ($pmb_transferts_actif)
					$admin_user_list .= "!!nusertransferts!!$msg[transferts_droit]</td>";
				else $admin_user_list .= "&nbsp;</td>";
$admin_user_list .= "
		</tr>";

$admin_user_list .= "<tr>
		<td class='brd'>";
			if ($pmb_extension_tab) $admin_user_list .="!!nuserextensions!!$msg[extensions_droit]</td>";
			else $admin_user_list .= "&nbsp;</td>";
		$admin_user_list .= "<td class='brd'>";
			if ($demandes_active) 
				$admin_user_list .= "!!nuserdemandes!!$msg[demandes_droit]</td>";
			else $admin_user_list .= "&nbsp;</td>";
	$admin_user_list .= "<td class='brd'>&nbsp;</td>";
	$admin_user_list .= "<td class='brd'>&nbsp;</td>";
$admin_user_list .= "</tr>";

$admin_user_list .= "<tr>
				<td colspan=4 class='brd'>
				!!user_alert_resamail!! &nbsp;
				</td>
		</tr>
	</table>
</div>
<div class='row'>&nbsp;</div>
<hr />
";

$admin_user_link1 = "
	<input class='bouton' type='button' value=' $msg[62] ' onClick=\"document.location='./admin.php?categ=users&sub=users&action=modif&id=!!nuserid!!'\">&nbsp;
	<input class='bouton' type='button' value=' $msg[mot_de_passe] ' onClick=\"document.location='./admin.php?categ=users&sub=users&action=pwd&id=!!nuserid!!'\">
	";
	
// commented because now use the confirmation_delete function used also from the other submodules
// so we show also the name we want to delete - Marco Vaninetti


// $admin_codstat_form : template form code stat
$admin_codstat_form = "
<form class='form-$current_module' name=typdocform method=post action=\"./admin.php?categ=docs&sub=codstat&action=update&id=!!id!!\">
<h3><span onclick='menuHide(this,event)'>!!form_title!!</span></h3>
<!--    Contenu du form    -->
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_cb'>$msg[103]</label>
		</div>
	<div class='row'>
		<input type=text name=form_libelle value='!!libelle!!' class='saisie-50em' />
		</div>
	<div class='row'>
		<label class='etiquette'>$msg[proprio_codage_interne]</label>
		</div>
	<div class='row'>
		<input type='text' name='form_statisdoc_codage_import' value='!!statisdoc_codage_import!!' class='saisie-20em' />
		</div>
	<div class='row'>
		<label class='etiquette'>$msg[proprio_codage_proprio]</label>
		</div>
	<div class='row'>
		!!lender!!
		</div>
	</div>

<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=docs&sub=codstat'\" />&nbsp;
		<input class='bouton' type='submit' value=' $msg[77] ' onClick=\"return test_form(this.form)\" />
		</div>
	<div class='right'>
		<input class='bouton' type='button' value='$msg[supprimer]' onClick=\"javascript:confirmation_delete(!!id!!,'!!libelle_suppr!!')\" />
		</div>
	</div>
<div class='row'></div>
</form>
<script type='text/javascript'>document.forms['typdocform'].elements['form_libelle'].focus();</script>
";

// $admin_location_form : template form des localisations
$admin_location_form = "
<form class='form-$current_module' name=typdocform method=post action=\"./admin.php?categ=docs&sub=location&action=update&id=!!id!!\">
<h3><span onclick='menuHide(this,event)'>!!form_title!!</span></h3>
<!--    Contenu du form    -->
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_cb'>$msg[103]</label>
		</div>
	<div class='row'>
		<input type=text name='form_libelle' value=\"!!libelle!!\" class='saisie-50em' />
		</div>
	<div class='row'>
		<label class='etiquette' >$msg[docs_location_pic]</label>
		</div>
	<div class='row'>
		<input type=text name='form_location_pic' value=\"!!location_pic!!\" class='saisie-50em' />
		</div>
	<div class='row'>
		<div class='colonne4'>
			<label class='etiquette' >$msg[opac_object_visible]</label>
			<input type=checkbox name='form_location_visible_opac' value='1' !!checkbox!! class='checkbox' />
		</div>
		<div class='colonne4'>
			<label class='etiquette' >CSS</label>
			<input type=text name='form_css_style' value='!!css_style!!' />
		</div>
		<div class='colonne_suite'>
			<label class='etiquette' >$msg[location_infopage_assoc]</label>
			!!loc_infopage!!
		</div>
		</div>
	<div class='row'>
		<label class='etiquette'>$msg[proprio_codage_interne]</label>
		</div>
	<div class='row'>
		<input type='text' name='form_locdoc_codage_import' value='!!locdoc_codage_import!!' class='saisie-20em' />
		</div>
	<div class='row'>
		<label class='etiquette'>$msg[proprio_codage_proprio]</label>
		</div>
	<div class='row'>
		!!lender!!
		</div>
<br />
<hr />
<br />
<div class='row'></div>
<div class='row'><label class='etiquette'>$msg[location_details_name]</label></div><div class='row'><input type='text' name='form_locdoc_name', ' value='!!loc_name!!' class='saisie-50em' /></div>
<div class='row'><label class='etiquette'>$msg[location_details_adr1]</label></div><div class='row'><input type='text' name='form_locdoc_adr1', ' value='!!loc_adr1!!' class='saisie-50em' /></div>
<div class='row'><label class='etiquette'>$msg[location_details_adr2]</label></div><div class='row'><input type='text' name='form_locdoc_adr2', ' value='!!loc_adr2!!' class='saisie-50em' /></div>
<div class='row'><label class='etiquette'>$msg[location_details_cp] / $msg[location_details_town]</label></div>
	<div class='row'>
		<div class='colonne4'>
			<input type='text' name='form_locdoc_cp', ' value='!!loc_cp!!' maxlength='15' class='saisie-10em' />
			</div>
		<div class='colonne_suite'>
			<input type='text' name='form_locdoc_town', ' value='!!loc_town!!'' class='saisie-50em' />
			</div>
		</div>

<div class='row'><label class='etiquette'>$msg[location_details_state] / $msg[location_details_country]</label></div>
	<div class='row'>
		<div class='colonne3'>
			<input type='text' name='form_locdoc_state',' value='!!loc_state!!' class='saisie-20em' />
			</div>
		<div class='colonne_suite'>
			<input type='text' name='form_locdoc_country' value='!!loc_country!!' class='saisie-20em' />
			</div>
		</div>
<div class='row'><label class='etiquette'>$msg[location_details_phone]</label></div><div class='row'><input type='text' name='form_locdoc_phone' value='!!loc_phone!!' maxlength='100' class='saisie-20em' /></div>
<div class='row'><label class='etiquette'>$msg[location_details_email]</label></div><div class='row'><input type='text' name='form_locdoc_email' value='!!loc_email!!' maxlength='100' class='saisie-20em' /></div>
<div class='row'><label class='etiquette'>$msg[location_details_website]</label></div><div class='row'><input type='text' name='form_locdoc_website' value='!!loc_website!!' maxlength='100' class='saisie-50em' /></div>
<div class='row'><label class='etiquette'>$msg[location_details_logo]</label></div><div class='row'><input type='text' name='form_locdoc_logo', ' value='!!loc_logo!!' maxlength='255' class='saisie-50em' /></div>
<div class='row'><label class='etiquette'>$msg[location_details_commentaire]</label></div><div class='row'><textarea class='saisie-50em' name='form_locdoc_commentaire' id='form_locdoc_commentaire' cols='55' rows='5'>!!loc_commentaire!!</textarea></div>
	</div>
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=docs&sub=location'\" />&nbsp;
		<input class='bouton' type='submit' value=' $msg[77] ' onClick=\"return test_form(this.form)\" />
		<input type='hidden' name='form_actif' value='1'>
		</div>
	<div class='right'>
		<input class='bouton' type='button' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete(!!id!!,'!!libelle_suppr!!')\" />
		</div>
</div>
<div class='row'></div>
</form>
<script type='text/javascript'>document.forms['typdocform'].elements['form_libelle'].focus();</script>
";

// $admin_section_form : template form section
$admin_section_form = "
<form class='form-$current_module' name=typdocform method=post action=\"./admin.php?categ=docs&sub=section&action=update&id=!!id!!\">
<h3><span onclick='menuHide(this,event)'>!!form_title!!</span></h3>
<!--    Contenu du form    -->
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_cb'>$msg[103]</label>
		</div>
	<div class='row'>
		<input type=text name='form_libelle' value='!!libelle!!' class='saisie-50em' />
		</div>
	<div class='row'>
		<label class='etiquette' >$msg[docs_section_pic]</label>
		</div>
	<div class='row'>
		<input type=text name='form_section_pic' value='!!section_pic!!' maxlength='255' class='saisie-50em' />
		</div>
	<div class='row'>
		<label class='etiquette' >$msg[opac_object_visible]</label>
		<input type=checkbox name='form_section_visible_opac' value='1' !!checkbox!! class='checkbox' />
		</div>
<div class='row'>
	<div class='colonne2'>
		<div class='row'>
			<label class='etiquette'>$msg[proprio_codage_interne]</label>
			</div>
		<div class='row'>
			<input type='text' name='form_sdoc_codage_import' value='!!sdoc_codage_import!!' class='saisie-20em' />
			</div>
		<div class='row'>
			<label class='etiquette'>$msg[proprio_codage_proprio]</label>
			</div>
		<div class='row'>
			!!lender!!
			</div>
		</div>
	<div class='colonne_suite'>
		<div class='row'>
			<label class='etiquette'>$msg[section_visible_loc]</label>
			</div>
		<div class='row'>
			!!num_locations!!
			</div>
		</div>
	</div>
<div class='row'>&nbsp;</div>
</div>
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=docs&sub=section'\" />&nbsp;
		<input class='bouton' type='submit' value=' $msg[77] ' onClick=\"return test_form(this.form)\" />
		</div>
	<div class='right'>
		<input class='bouton' type='button' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete(!!id!!,'!!libelle_suppr!!')\" />
		</div>
	</div>
<div class='row'></div>
</form>
<script type='text/javascript'>document.forms['typdocform'].elements['form_libelle'].focus();</script>
";

// $admin_statut_form : template form statuts
$admin_statut_form = "
<form class='form-$current_module' name=typdocform method=post action=\"./admin.php?categ=docs&sub=statut&action=update&id=!!id!!\">
<h3><span onclick='menuHide(this,event)'>!!form_title!!</span></h3>
<!--    Contenu du form    -->
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_cb'>$msg[103]</label>
		</div>
	<div class='row'>
		<input type=text name='form_libelle' value='!!libelle!!' class='saisie-50em' />
		</div>
	<div class='row'>
		<label class='etiquette' for='form_pret'>$msg[117]</label>
		<input type=checkbox name=form_pret value='!!pret!!' !!checkbox!! class='checkbox' onClick=\"test_check(this.form)\" />
		</div>";
if ($pmb_transferts_actif=="1")
	$admin_statut_form .= "
	<div class='row'>
		<label class='etiquette' for='form_trans'>".$msg["transferts_statut_lib_transferable"]."</label>
		<input type=checkbox name=form_trans value='!!trans!!' !!checkbox_trans!! class='checkbox' onClick=\"test_check_trans(this.form)\" />
		</div>";
$admin_statut_form .= "
	<div class='row'>
		<label class='etiquette'>$msg[proprio_codage_interne]</label>
		</div>
	<div class='row'>
		<input type='text' name='form_statusdoc_codage_import' value='!!statusdoc_codage_import!!' class='saisie-20em' />
		</div>
	<div class='row'>
		<label class='etiquette'>$msg[proprio_codage_proprio]</label>
		</div>
	<div class='row'>
		!!lender!!
		</div>
	</div>
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=docs&sub=statut'\">&nbsp;
		<input class='bouton' type='submit' value=' $msg[77] ' onClick=\"return test_form(this.form)\">
		</div>
	<div class='right'>
		<input class='bouton' type='button' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete(!!id!!,'!!libelle_suppr!!')\" />
		</div>
	</div>
<div class='row'></div>
</form>
<script type='text/javascript'>document.forms['typdocform'].elements['form_libelle'].focus();</script>
";

// $admin_orinot_form : template form origine notice
$admin_orinot_form = "
<form class='form-$current_module' name=orinotform method=post action=\"./admin.php?categ=notices&sub=orinot&action=update&id=!!id!!\">
<h3><span onclick='menuHide(this,event)'>!!form_title!!</span></h3>
<!--    Contenu du form    -->
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' >$msg[orinot_nom]</label>
		</div>
	<div class='row'>
		<input type=text name='form_nom' value='!!nom!!' class='saisie-50em' />
		</div>
	<div class='row'>
		<label class='etiquette' >$msg[orinot_pays]</label>
		</div>
	<div class='row'>
		<input type=text name='form_pays' value='!!pays!!' class='saisie-50em' />
		</div>
	<div class='row'>
		<label class='etiquette' >$msg[orinot_diffusable]</label>
		<input type=checkbox name=form_diffusion value='1' !!checkbox!! class='checkbox' />
		</div>
	</div>
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=notices&sub=orinot'\">&nbsp;
		<input class='bouton' type='submit' value=' $msg[77] ' onClick=\"return test_form(this.form)\">
		</div>
	<div class='right'>
		<input class='bouton' type='button' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete(!!id!!,'!!nom_suppr!!')\" />
		</div>
	</div>
<div class='row'></div>
</form>
<script type='text/javascript'>document.forms['orinotform'].elements['form_nom'].focus();</script>
";

// $admin_typdoc_form : template form types doc
if ($pmb_quotas_avances) $display="style='display:none'"; else $display="";
$admin_typdoc_form = "
<form class='form-$current_module' name=typdocform method=post action=\"./admin.php?categ=docs&sub=typdoc&action=update&id=!!id!!\">
<h3><span onclick='menuHide(this,event)'>!!form_title!!</span></h3>
<!--    Contenu du form    -->
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_cb'>$msg[103]</label>
		</div>
	<div class='row'>
		<input type='text' name='form_libelle' value='!!libelle!!' class='saisie-50em' />
		</div>
	<div class='row' $display>
		<label class='etiquette' for='form_pret'>$msg[123]</label>
		</div>
	<div class='row' $display>
		<input type='text' name='form_pret' value='!!pret!!' maxlength='10' class='saisie-10em' />
		</div>
	<div class='row' $display>
		<label class='etiquette' for='form_resa'>$msg[duree_resa]</label>
		</div>
	<div class='row' $display>
		<input type='text' name='form_resa' value='!!resa!!' maxlength='10' class='saisie-10em' />
		</div>
	!!tarif_pret!!
	<div class='row'>
		<label class='etiquette'>$msg[proprio_codage_interne]</label>
		</div>
	<div class='row'>
		<input type='text' name='form_tdoc_codage_import' value='!!tdoc_codage_import!!' class='saisie-20em' />
		</div>
	<div class='row'>
		<label class='etiquette'>$msg[proprio_codage_proprio]</label>
		</div>
	<div class='row'>
		!!lender!!
		</div>
	</div>
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=docs&sub=typdoc'\" />&nbsp;
		<input class='bouton' type='submit' value=' $msg[77] ' onClick=\"return test_form(this.form)\" />&nbsp;
		</div>
	<div class='right'>
		<input class='bouton' type='button' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete(!!id!!,'!!libelle_suppr!!')\" />
		</div>
	</div>
<div class='row'></div>
</form>
<script type='text/javascript'>document.forms['typdocform'].elements['form_libelle'].focus();</script>
";

// $admin_lender_form : template form lenders
$admin_lender_form = "
<form class='form-$current_module' name='lenderform' method='post' action=\"./admin.php?categ=docs&sub=lenders&action=update&id=!!id!!\">
<h3><span onclick='menuHide(this,event)'>!!form_title!!</span></h3>
<!--    Contenu du form    -->
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_libelle'>$msg[558]</label>
	</div>
	<div class='row'>
		<input type='text' id='form_libelle' name='form_libelle' value='!!libelle!!' class='saisie-50em' />
	</div>
</div>
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=docs&sub=lenders'\" />&nbsp;
		<input class='bouton' type='submit' value=' $msg[77] ' onClick=\"return test_form(this.form)\" />
		</div>
	<div class='right'>
		<input class='bouton' type='button' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete(!!id!!,'!!libelle_suppr!!')\" />
		</div>
	</div>
<div class='row'></div>
</form>
<script type='text/javascript'>document.forms['lenderform'].elements['form_libelle'].focus();</script>
";
// $admin_support_form : template form supports
$admin_support_form = "
<form class='form-$current_module' name='supportform' method='post' action=\"./admin.php?categ=collstate&sub=support&action=update&id=!!id!!\">
<h3><span onclick='menuHide(this,event)'>!!form_title!!</span></h3>
<!--    Contenu du form    -->
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_libelle'>".$msg["admin_collstate_support_nom"]."</label>
	</div>
	<div class='row'>
		<input type='text' id='form_libelle' name='form_libelle' value='!!libelle!!' class='saisie-50em' />
	</div>
</div>
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=collstate&sub=support'\" />&nbsp;
		<input class='bouton' type='submit' value=' $msg[77] ' onClick=\"return test_form(this.form)\" />
		</div>
	<div class='right'>
	!!supprimer!!
		</div>
	</div>
<div class='row'></div>
</form>
<script type='text/javascript'>document.forms['supportform'].elements['form_libelle'].focus();</script>
";
// $admin_emplacement_form : template form emplacements
$admin_emplacement_form = "
<form class='form-$current_module' name='emplacementform' method='post' action=\"./admin.php?categ=collstate&sub=emplacement&action=update&id=!!id!!\">
<h3><span onclick='menuHide(this,event)'>!!form_title!!</span></h3>
<!--    Contenu du form    -->
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_libelle'>".$msg["admin_collstate_emplacement_nom"]."</label>
	</div>
	<div class='row'>
		<input type='text' id='form_libelle' name='form_libelle' value='!!libelle!!' class='saisie-50em' />
	</div>
</div>
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=collstate&sub=emplacement'\" />&nbsp;
		<input class='bouton' type='submit' value=' $msg[77] ' onClick=\"return test_form(this.form)\" />
		</div>
	<div class='right'>
		!!supprimer!!
		</div>
	</div>
<div class='row'></div>
</form>
<script type='text/javascript'>document.forms['emplacementform'].elements['form_libelle'].focus();</script>
";

// $admin_categlec_form : template form categ lecteurs
$admin_categlec_form = "
<form class='form-$current_module' name='typdocform' method='post' action=\"./admin.php?categ=empr&sub=categ&action=update&id=!!id!!\">
<h3><span onclick='menuHide(this,event)'>!!form_title!!</span></h3>
<!--    Contenu du form    -->
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_cb'>$msg[103]</label>
		</div>
	<div class='row'>
		<input type=text name='form_libelle' value='!!libelle!!' class='saisie-50em' />
		</div>
	<div class='row'>
		<label class='etiquette' for='form_duree_adhesion'>$msg[1400]</label>
		</div>
	<div class='row'>
		<input type=text name='form_duree_adhesion' value='!!duree_adhesion!!' maxlength='10' class='saisie-5em' />
		</div>
	!!tarif_adhesion!!
	</div>
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=empr&sub=categ'\">&nbsp;
		<input class='bouton' type='submit' value=' $msg[77] ' onClick=\"return test_form(this.form)\" />
		</div>
	<div class='right'>
		<input class='bouton' type='button' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete(!!id!!,'!!libelle_suppr!!')\" />
		</div>
	</div>
<div class='row'></div>
</form>
<script type='text/javascript'>document.forms['typdocform'].elements['form_libelle'].focus();</script>
";

// $admin_statlec_form : template form codestat lecteurs
$admin_statlec_form = "
<form class='form-$current_module' name='typdocform' method='post' action=\"./admin.php?categ=empr&sub=codstat&action=update&id=!!id!!\">
<h3><span onclick='menuHide(this,event)'>!!form_title!!</span></h3>
<!--    Contenu du form    -->
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_cb'>$msg[103]</label>
		</div>
	<div class='row'>
		<input type='text' name='form_libelle' value='!!libelle!!' class='saisie-50em' />
		</div>
	</div>
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=empr&sub=codstat'\" />&nbsp;
		<input class='bouton' type='submit' value=' $msg[77] ' onClick=\"return test_form(this.form)\" />
		</div>
	<div class='right'>
		<input class='bouton' type='button' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete(!!id!!,'!!libelle_suppr!!')\" />
		</div>
	</div>
<div class='row'></div>
</form>
<script type='text/javascript'>document.forms['typdocform'].elements['form_libelle'].focus();</script>
";

// $admin_empr_statut_form : template formulaire statuts emprunteurs
$admin_empr_statut_form = "
<form class='form-$current_module' name=statutform method=post action=\"./admin.php?categ=empr&sub=statut&action=update&id=!!id!!\">
<h3><span onclick='menuHide(this,event)'>!!form_title!!</span></h3>
<!--    Contenu du form    -->
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_cb'>$msg[103]</label>
		</div>
	<div class='row'>
		<input type=text name='statut_libelle' value='!!libelle!!' class='saisie-50em' />
		</div>
	<div class='row'>
		<input type=checkbox name=allow_loan value='1' id=allow_loan !!checkbox_loan!! class='checkbox' />
		<label class='etiquette' for='allow_loan'>".$msg['empr_allow_loan']."</label>
		</div>
	<div class='row'>
		<input type=checkbox name=allow_loan_hist value='1' id=allow_loan_hist !!checkbox_loan_hist!! class='checkbox'/>
		<label class='etiquette' for='allow_loan_hist'>".$msg['empr_allow_loan_hist']."</label>
		</div>
	<div class='row'>
		<input type=checkbox name=allow_book value='1' id=allow_book !!checkbox_book!! class='checkbox' />
		<label class='etiquette' for='allow_book'>".$msg['empr_allow_book']."</label>
		</div>
	<div class='row'>
		<input type=checkbox name=allow_opac value='1' id=allow_opac !!checkbox_opac!! class='checkbox' />
		<label class='etiquette' for='allow_opac'>".$msg['empr_allow_opac']."</label>
		</div>
	<div class='row'>
		<input type=checkbox name=allow_dsi value='1' id=allow_dsi !!checkbox_dsi!! class='checkbox' />
		<label class='etiquette' for='allow_dsi'>".$msg['empr_allow_dsi']."</label>
		</div>
	<div class='row'>
		<input type=checkbox name=allow_dsi_priv value='1' id=allow_dsi_priv !!checkbox_dsi_priv!! class='checkbox' />
		<label class='etiquette' for='allow_dsi_priv'>".$msg['empr_allow_dsi_priv']."</label>
		</div>
	<div class='row'>
		<input type=checkbox name=allow_sugg value='1' id=allow_sugg !!checkbox_sugg!! class='checkbox' />
		<label class='etiquette' for='allow_sugg'>".$msg['empr_allow_sugg']."</label>
		</div>
	<div class='row'>
		<input type=checkbox name=allow_dema value='1' id=allow_dema !!checkbox_dema!! class='checkbox' />
		<label class='etiquette' for='allow_dema'>".$msg['empr_allow_dema']."</label>
		</div>
	<div class='row'>
		<input type=checkbox name=allow_liste_lecture value='1' id=allow_liste_lecture !!checkbox_liste_lecture!! class='checkbox' />
		<label class='etiquette' for='allow_liste_lecture'>".$msg['empr_allow_liste_lecture']."</label>
		</div>
	<div class='row'>
		<input type=checkbox name=allow_prol value='1' id=allow_prol !!checkbox_prol!! class='checkbox' />
		<label class='etiquette' for='allow_prol'>".$msg['empr_allow_prol']."</label>
		</div>
	<div class='row'>
		<input type=checkbox name=allow_avis value='1' id=allow_avis !!checkbox_avis!! class='checkbox' />
		<label class='etiquette' for='allow_avis'>".$msg['empr_allow_avis']."</label>
		</div>
	<div class='row'>
		<input type=checkbox name=allow_tag value='1' id=allow_tag !!checkbox_tag!! class='checkbox' />
		<label class='etiquette' for='allow_tag'>".$msg['empr_allow_tag']."</label>
		</div>
	<div class='row'>
		<input type=checkbox name=allow_pwd value='1' id=allow_pwd !!checkbox_pwd!! class='checkbox' />
		<label class='etiquette' for='allow_pwd'>".$msg['empr_allow_pwd']."</label>
		</div>
	<div class='row'>
		<input type=checkbox name=allow_self_checkout value='1' id=allow_self_checkout !!allow_self_checkout!! class='checkbox' />
		<label class='etiquette' for='allow_self_checkout'>".$msg['empr_allow_self_checkout']."</label>
		</div>
	<div class='row'>
		<input type=checkbox name=allow_self_checkin value='1' id=allow_self_checkin !!allow_self_checkin!! class='checkbox' />
		<label class='etiquette' for='allow_self_checkin'>".$msg['empr_allow_self_checkin']."</label>
		</div>
</div>
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=empr&sub=statut'\">&nbsp;
		<input class='bouton' type='submit' value=' $msg[77] ' onClick=\"return test_form(this.form)\">
		</div>
	<div class='right'>
		<input class='bouton' type='button' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete(!!id!!,'!!libelle_suppr!!')\" />
		</div>
	</div>
<div class='row'></div>
</form>
<script type='text/javascript'>document.forms['statutform'].elements['statut_libelle'].focus();</script>
";

// $admin_proc_form : template form procï¿½dures stockï¿½es
$admin_proc_form = "
<form class='form-$current_module' name='maj_proc' method='post' action='!!action!!'>
<h3><span onclick='menuHide(this,event)'>>!!form_title!!</span></h3>
<!--    Contenu du form    -->
<div class='form-contenu'>
	<div class=colonne2>
		<div class='row'>
		<label class='etiquette' for='form_name'>$msg[705]</label>
		</div>
		<div class='row'>
		<input type='text' name='f_proc_name' value='!!name!!' maxlength='255' class='saisie-50em' />
		</div>
	</div>
	<div class=colonne_suite>
		<div class='row'>
		<label class='etiquette' for='form_classement'>$msg[proc_clas_proc]</label>
		</div>
		<div class='row'>
		!!classement!!
		</div>
	</div>
	<div class='row'>
		<label class='etiquette' for='form_code'>$msg[706]</label>
		</div>
	<div class='row'>
		<textarea cols='80' rows='8' name='f_proc_code'>!!code!!</textarea>
		</div>
	<div class='row'>
		<label class='etiquette' for='form_comment'>$msg[707]</label>
		</div>
	<div class='row'>
		<input type='text' name='f_proc_comment' value='!!comment!!' maxlength='255' class='saisie-50em' />
		</div>
	<div class='row'>
		<label class='etiquette' for='form_notice_tpl'>".$msg['notice_tpl_notice_id']."</label>
		</div>
	<div class='row'>
		!!notice_tpl!!
		</div>
	<div class='row'>
		<label class='etiquette' for='form_comment'>$msg[procs_autorisations]</label>
		<input type='button' class='bouton_small' value='".$msg['tout_cocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,1);' align='middle'>
		<input type='button' class='bouton_small' value='".$msg['tout_decocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,0);' align='middle'>
		</div>
	<div class='row'>
		!!autorisations_users!!
		</div>
	</div>
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input type='button' class='bouton' value='$msg[76]' onClick='document.location=\"./admin.php?categ=proc&sub=proc\"' />&nbsp;
		<input type='submit' class='bouton' value='$msg[77]' onClick=\"return test_form(this.form)\" />&nbsp;
		<input type='button' class='bouton' value=' $msg[708] ' onClick=\"document.location='./admin.php?categ=proc&sub=proc&action=execute&id=!!id!!'\" />&nbsp;
		</div>
	<div class='right'>
		<input type='button' class='bouton' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete(!!id!!,'!!name_suppr!!')\" />
	</div>
</div>
<div class='row'></div>
</form>
<script type='text/javascript'>document.forms['maj_proc'].elements['f_proc_name'].focus();</script>";

// $admin_proc_form : template form procï¿½dures stockï¿½es
$admin_proc_view_remote = "
<h3><span onclick='menuHide(this,event)'>>!!form_title!!</span></h3>
<!--    Contenu du form    -->
<div class='form-contenu'>
	<div class='row'>
	!!additional_information!!
	</div>
	<div class=colonne2>
		<div class='row'>
		<label class='etiquette' for='form_name'>$msg[remote_procedures_procedure_name]</label>
		</div>
		<div class='row'>
		<input type='text' readonly name='f_proc_name' value='!!name!!' maxlength='255' class='saisie-50em' />
		</div>
	</div>
	<div class='row'>
		<label class='etiquette' for='form_code'>$msg[remote_procedures_procedure_sql]</label>
		</div>
	<div class='row'>
		<textarea cols='80' readonly rows='8' name='f_proc_code'>!!code!!</textarea>
		</div>
	<div class='row'>
		<label class='etiquette' for='form_comment'>$msg[remote_procedures_procedure_comment]</label>
		</div>
	<div class='row'>
		<input type='text' readonly name='f_proc_comment' value='!!comment!!' maxlength='255' class='saisie-50em' />
	</div>
	<div class='row'>
		!!parameters_title!!
	</div>
	<div class='row'>
		!!parameters_content!!
	</div>
</div>
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input type='button' class='bouton' value='".$msg["remote_procedures_back"]."' onClick='document.location=\"./admin.php?categ=proc&sub=proc\"' />&nbsp;
		<input class='bouton' type='button' value=\"".$msg["remote_procedures_import"]."\" onClick=\"document.location='./admin.php?categ=proc&sub=proc&action=import_remote&id=!!id!!'\" />
		</div>
</div>
<div class='row'></div>
<script type='text/javascript'>document.forms['maj_proc'].elements['f_proc_name'].focus();</script>";

// $admin_zbib_form : template form zbib
$admin_zbib_form = "
<form class='form-$current_module' name=zbibform method=post action=\"./admin.php?categ=z3950&sub=zbib&action=update&id=!!id!!\">
<h3><span onclick='menuHide(this,event)'>!!form_title!!</span></h3>
<div class='form-contenu'>
	<div class='row'>
		<div class='colonne4' align='right'>
				<label class='etiquette'>$msg[admin_Nom] &nbsp;</label>
				</div>
		<div class='colonne_suite'>
				<input type=text name=form_nom value='!!nom!!' size=50 />
				</div>
		</div>
	<div class='row'>&nbsp;</div>
	<div class='row'>
		<div class='colonne4' align='right'>
				<label class='etiquette'>$msg[admin_Utilisation] &nbsp;</label>
				</div>
		<div class='colonne_suite'>
				<input type=text name=form_search_type value='!!search_type!!' size=50/>
				</div>
		</div>
	<div class='row'>&nbsp;</div>
	<div class='row'>
		<div class='colonne4' align='right'>
				<label class='etiquette'>$msg[admin_Base] &nbsp;</label>
				</div>
		<div class='colonne_suite'>
				<input type=text name=form_base value='!!base!!' size=50 />
				</div>
		</div>
	<div class='row'>&nbsp;</div>
	<div class='row'>
		<div class='colonne4' align='right'>
				<label class='etiquette'>$msg[admin_URL] &nbsp;</label>
				</div>
		<div class='colonne_suite'>
				<input type=text name=form_url value='!!url!!' size=50>
				</div>
		</div>
	<div class='row'>&nbsp;</div>
	<div class='row'>
		<div class='colonne4' align='right'>
				<label class='etiquette'>$msg[admin_NumPort] &nbsp;</label>
				</div>
		<div class='colonne_suite'>
				<input type=text name=form_port value='!!port!!' size='10' />
				</div>
		</div>
	<div class='row'>&nbsp;</div>
	<div class='row'>
		<div class='colonne4' align='right'>
				<label class='etiquette'>$msg[admin_Format] &nbsp;</label>
				</div>
		<div class='colonne_suite'>
				<input type=text name=form_format value='!!format!!' size='50' />
				</div>
		</div>
	<div class='row'>&nbsp;</div>
	<div class='row'>
		<div class='colonne4' align='right'>
				<label class='etiquette'>$msg[z3950_sutrs] &nbsp;</label>
				</div>
		<div class='colonne_suite'>
				<input type=text name=form_sutrs value='!!sutrs!!' size=50>
				</div>
		</div>
	<div class='row'>&nbsp;</div>
	<div class='row'>
		<div class='colonne4' align='right'>
				<label class='etiquette'>$msg[admin_user] &nbsp;</label>
				</div>
		<div class='colonne_suite'>
				<input type=text name=form_user value='!!user!!' size='50' />
				</div>
		</div>
	<div class='row'>&nbsp;</div>
	<div class='row'>
		<div class='colonne4' align='right'>
				<label class='etiquette'>$msg[admin_password] &nbsp;</label>
				</div>
		<div class='colonne_suite'>
				<input type=text name=form_password value='!!password!!' size=50>
				</div>
		</div>
	<div class='row'>&nbsp;</div>
	<div class='row'>
		<div class='colonne4' align='right'>
				<label class='etiquette'>$msg[zbib_zfunc] &nbsp;</label>
				</div>
		<div class='colonne_suite'>
				<input type=text name=form_zfunc value='!!zfunc!!' size=50>
				</div>
		</div>
	<div class='row'> </div>
	</div>
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value='$msg[76]'  onClick=\"document.location='./admin.php?categ=z3950&sub=zbib'\">&nbsp;
		<input class='bouton' type='button' value='$msg[admin_Attributs]' onClick=\"document.location='./admin.php?categ=z3950&sub=zattr&action=edit&bib_id=!!id!!'\">&nbsp;
		<input class='bouton' type='submit' value='$msg[77]' onClick=\"return test_form(this.form)\">
		</div>
	<div class='right'>
		<input class='bouton' type='button' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete('!!id!!','!!nom!!')\" />
		</div>
	</div>
<div class='row'></div>
</form><script type='text/javascript'>document.forms['zbibform'].elements['form_nom'].focus();</script>
";

// $admin_zattr_form : template form attributs zbib - changed by martizva
$admin_zattr_form = "
<form class='form-$current_module' name=zattrform method=post action=\"./admin.php?categ=z3950&sub=zattr&action=update&bib_id=!!bib_id!!\">
<h3><span onclick='menuHide(this,event)'>!!form_title!!</span></h3>
<div class='form-contenu'>
!!code!!

	<div class='row'>&nbsp;</div>
	<div class='row'>
		<div class='colonne4' align='right'>
				<label class='etiquette'>$msg[admin_Attributs] &nbsp;</label>
				</div>
		<div class='colonne_suite'>
				<input type=text name=form_attr_attr value='!!attr_attr!!' size=25>
				<input type=hidden name=form_attr_bib_id value='!!attr_bib_id!!'>
				</div>
		</div>
	<div class='row'> </div>
	

</div>
	<div class='row'>
		<div class='left'>
			<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=z3950&sub=zattr&bib_id=!!attr_bib_id!!'\" />&nbsp;
			<input class='bouton' type='submit' value=' $msg[77] ' onClick=\"return test_form(this.form)\" />&nbsp;
			</div>
		<div class='right'>
			<input class='bouton' type='button' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete('bib_id=!!attr_bib_id!!&attr_libelle=!!attr_libelle!!','!!local_attr_libelle!!')\" />
		</div>
	</div>
<div class='row'></div>
</form><script type='text/javascript'>document.forms['zattrform'].elements['form_attr_libelle'].focus();</script>
";

// $admin_convert_end form - FIX MaxMan
$admin_convert_end = "
</center><br /><br />
<form class='form-$current_module' action=\"folow_import.php\" method=\"post\">
<h3><span onclick='menuHide(this,event)'>".$msg["admin_conversion_end11"]."</span></h3>
<div class='form-contenu'>
	<div class='row'>";

if (($output=="yes")&&(!$noimport)) {
	$admin_convert_end .= "
		<input type=\"radio\" name=\"deliver\" value=\"1\" checked>&nbsp;".$msg["admin_conversion_end5"]."<br />
		<input type=\"radio\" name=\"deliver\" value=\"2\" checked>&nbsp;".$msg["admin_conversion_end6"]."<br />";
}
$admin_convert_end .= "
		<input type=\"radio\" name=\"deliver\" value=\"3\" checked>&nbsp;".$msg["admin_conversion_end7"]."<br />
		<input type=\"hidden\" name=\"file_in\" value=\"$file_in\">
		<input type=\"hidden\" name=\"suffix\" value=\"$suffix\">
		<input type=\"hidden\" name=\"mimetype\" value=\"$mimetype\">
		</div>
	</div>
<div class='row'>
	<input type=\"submit\" class='bouton' value=\"".$msg["admin_conversion_end8"]."\"
	</div>
</form>
<br />
<div class='row'>
<center><b>".$msg["admin_conversion_end9"]."</b></center>";

if ($n_errors==0) {
	$admin_convert_end .= "<center><b>".$msg["admin_conversion_end10"]."</b></center>";
} else {
	$admin_convert_end .= "  $errors_msg  </div> ";
}

// $admin_calendrier_form : template form calendrier des jours d'ouverture
$admin_calendrier_form = "
<form class='form-$current_module' id='calendrier' name='calendrier' method='post' action='./admin.php?categ=calendrier'>
<h3><span onclick='menuHide(this,event)'>$msg[calendrier_titre_form]";
$admin_calendrier_form .= " - ".$biblio_name ."!!localisation!!";
$admin_calendrier_form .= "</span></h3>
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='date_deb'>$msg[calendrier_date_debut]</label>
		<input class='saisie-10em' id='date_deb' type='text' name='date_deb' />
		&nbsp;
		<label class='etiquette' for='date_fin'>$msg[calendrier_date_fin]</label>
		<input class='saisie-10em' id='date_fin' type='text' name='date_fin' />
		</div>
	<div class='row'>
		<label class='etiquette' >$msg[calendrier_jours_concernes]</label>
		$msg[1018]<input type='checkbox' name='j2' value=1 />&nbsp;
		$msg[1019]<input type='checkbox' name='j3' value=1 />&nbsp;
		$msg[1020]<input type='checkbox' name='j4' value=1 />&nbsp;
		$msg[1021]<input type='checkbox' name='j5' value=1 />&nbsp;
		$msg[1022]<input type='checkbox' name='j6' value=1 />&nbsp;
		$msg[1023]<input type='checkbox' name='j7' value=1 />&nbsp;
		$msg[1024]<input type='checkbox' name='j1' value=1 />&nbsp;
		</div>
	<div class='row'>
		<label class='etiquette' for='commentaire'>$msg[calendrier_commentaire]</label>
		<input class='saisie-30em' id='commentaire' type='text' name='commentaire' />
		</div>
	</div>
<div class='row'>
	<input class='bouton' type='submit' value=' $msg[calendrier_ouvrir] ' onClick=\"this.form.faire.value='ouvrir'\" />&nbsp;
	<input class='bouton' type='submit' value=' $msg[calendrier_fermer] ' onClick=\"this.form.faire.value='fermer'\" />&nbsp;
	<input type='hidden' name='faire' value='' />
	</div>
</form>
";

// $admin_calendrier_form : template form calendrier pour un mois pour les commentaires par jour
$admin_calendrier_form_mois_start = "
<form class='form-$current_module' id='calendrier' name='calendrier' method='post' action='./admin.php?categ=calendrier'>
<h3><span onclick='menuHide(this,event)'>$msg[calendrier_titre_form_commentaire]</span></h3>
<div class='form-contenu'>";

$admin_calendrier_form_mois_commentaire = " <input class='saisie-5em' id='commentaire' type='text' name='!!name!!' value='!!commentaire!!' />" ;
$admin_calendrier_form_mois_commentaire = " <textarea name='!!name!!' class='saisie-5em' rows='4' wrap='virtual'>!!commentaire!!</textarea>";
				
//	<input class='bouton' type='submit' value=' $msg[calendrier_ouvrir] ' onClick=\"this.form.faire.value='ouvrir'\" />&nbsp;
//	<input class='bouton' type='submit' value=' $msg[calendrier_fermer] ' onClick=\"this.form.faire.value='fermer'\" />&nbsp;

$admin_calendrier_form_mois_end = "	</div>
<div class='row'>
	<input class='bouton' type='button' value='$msg[76]' onClick=\"document.location='./admin.php?categ=calendrier'\">&nbsp;
	<input class='bouton' type='submit' value='$msg[77]' onClick=\"this.form.faire.value='commentaire'\">
	<input type='hidden' name='faire' value='' />
	<input type='hidden' name='annee_mois' value='!!annee_mois!!' />
	</div>
</form>
";

// $admin_notice_statut_form : template form statuts de notices
$admin_notice_statut_form = "
<form class='form-$current_module' method=post action=\"./admin.php?categ=notices&sub=statut&action=update&id=!!id!!\">
<h3><span onclick='menuHide(this,event)'>!!form_title!!</span></h3>
<!--    Contenu du form    -->
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' ><strong>$msg[noti_statut_gestion]</strong></label>
		</div>
	<div class='row'>
		<label class='etiquette' for='form_libelle'>$msg[noti_statut_libelle]</label>
		</div>
	<div class='row'>
		<input type=text name='form_gestion_libelle' value='!!gestion_libelle!!' class='saisie-50em' />
		</div>
	<div class='row'>
		<label class='etiquette' for='form_visible_gestion'>$msg[noti_statut_visu_gestion]</label>
		<input type=checkbox name=form_visible_gestion value='1' !!checkbox_visible_gestion!! class='checkbox' />&nbsp;
		</div>
	<div class='row'>
		<div class='colonne5'>
			<label class='etiquette' for='form_class_html'>$msg[noti_statut_class_html]</label>
		</div>
		<div class='colonne_suite'>
			!!class_html!!
		</div>
		</div>
	<div class='row'>&nbsp;</div>
	<div class='row'>
		<label class='etiquette' ><strong>$msg[noti_statut_opac]</strong></label>
		</div>
	<div class='row'>
		<label class='etiquette' for='form_libelle'>$msg[noti_statut_libelle]</label>
		</div>
	<div class='row'>
		<input type=text name='form_opac_libelle' value='!!opac_libelle!!' class='saisie-50em' />
		</div>
	<div class='row'>&nbsp;</div>
	<div class='colonne2'>
		<label class='etiquette'>$msg[notice_statut_visibilite_generale]</label>
		</div>
	<div class='colonne_suite'>
		<label class='etiquette'>$msg[notice_statut_visibilite_restrict]</label>
		</div>
	<div class='colonne2'>
		<label class='etiquette' for='form_visible_opac'>$msg[noti_statut_visu_opac_form]</label>
		<input type=checkbox name=form_visible_opac value='1' !!checkbox_visible_opac!! class='checkbox' />
		</div>
	<div class='colonne_suite'>
		<label class='etiquette' for='form_visu_abon'>$msg[noti_statut_visible_opac_abon]</label>
		<input type=checkbox name=form_visu_abon value='1' !!checkbox_visu_abon!! class='checkbox' />
		</div>
	<div class='row'>&nbsp;</div>
	<div class='colonne2'>
		<label class='etiquette' for='form_expl_visu_expl'>$msg[noti_statut_visu_expl]</label>
		<input type=checkbox name=form_visu_expl value='1' !!checkbox_visu_expl!! class='checkbox' />
		</div>
	<div class='colonne_suite'>
		<label class='etiquette' for='form_expl_visu_abon'>$msg[noti_statut_expl_visible_opac_abon]</label>
		<input type=checkbox name=form_expl_visu_abon value='1' !!checkbox_expl_visu_abon!! class='checkbox' />
		</div>
	<div class='row'>&nbsp;</div>
	<div class='colonne2'>
		<label class='etiquette' for='form_explnum_visu_expl'>$msg[noti_statut_visu_explnum]</label>
		<input type=checkbox name=form_explnum_visu value='1' !!checkbox_explnum_visu!! class='checkbox' />
		</div>
	<div class='colonne_suite'>
		<label class='etiquette' for='form_expl_visu_abon'>$msg[noti_statut_explnum_visible_opac_abon]</label>
		<input type=checkbox name=form_explnum_visu_abon value='1' !!checkbox_explnum_visu_abon!! class='checkbox' />
		</div>
	<div class='row'></div>
	</div>
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=notices&sub=statut'\">&nbsp;
		<input class='bouton' type='submit' value=' $msg[77] ' onClick=\"return test_form(this.form)\">
		</div>
	<div class='right'>
		!!bouton_supprimer!!
		</div>
	</div>
<div class='row'></div>
</form>
<script type='text/javascript'>document.forms['typdocform'].elements['form_libelle'].focus();</script>
";

// $admin_notice_statut_form : template form statuts des etats de collections
$admin_collstate_statut_form = "
<form class='form-$current_module' name='admin' method=post action=\"./admin.php?categ=collstate&sub=statut&action=update&id=!!id!!\">
<h3><span onclick='menuHide(this,event)'>!!form_title!!</span></h3>
<!--    Contenu du form    -->
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' ><strong>".$msg["collstate_statut_gestion"]."</strong></label>
		</div>
	<div class='row'>
		<label class='etiquette' for='form_gestion_libelle'>".$msg["collstate_statut_libelle"]."</label>
		</div>
	<div class='row'>
		<input type=text name='form_gestion_libelle' value='!!gestion_libelle!!' class='saisie-50em' />
		</div>
	<div class='row'>
		<div class='colonne5'>
			<label class='etiquette' for='form_class_html'>".$msg["collstate_statut_class_html"]."</label>
		</div>
		<div class='colonne_suite'>
			!!class_html!!
		</div>
		</div>
	<div class='row'>&nbsp;</div>
	<div class='row'>
		<label class='etiquette' ><strong>".$msg["collstate_statut_opac"]."</strong></label>
		</div>
	<div class='row'>
		<label class='etiquette' for='form_opac_libelle'>".$msg["collstate_statut_libelle"]."</label>
		</div>
	<div class='row'>
		<input type=text name='form_opac_libelle' value='!!opac_libelle!!' class='saisie-50em' />
		</div>
	<div class='row'>&nbsp;</div>
	<div class='colonne2'>
		<label class='etiquette'>".$msg["collstate_statut_visibilite_generale"]."</label>
		</div>
	<div class='colonne_suite'>
		<label class='etiquette'>".$msg["collstate_statut_visibilite_restrict"]."</label>
		</div>
	<div class='colonne2'>
		<label class='etiquette' for='form_visible_opac'>".$msg["collstate_statut_visu_opac_form"]."</label>
		<input type=checkbox name=form_visible_opac value='1' !!checkbox_visible_opac!! class='checkbox' />
		</div>
	<div class='colonne_suite'>
		<label class='etiquette' for='form_visu_abon'>".$msg["collstate_statut_visible_opac_abon"]."</label>
		<input type=checkbox name=form_visu_abon value='1' !!checkbox_visu_abon!! class='checkbox' />
		</div>
	<div class='row'></div>
	</div>
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=collstate&sub=statut'\">&nbsp;
		<input class='bouton' type='submit' value=' $msg[77] ' onClick=\"return test_form(this.form)\">
		</div>
	<div class='right'>
		!!bouton_supprimer!!
		</div>
	</div>
<div class='row'></div>
</form>
<script type='text/javascript'>document.forms['admin'].elements['form_gestion_libelle'].focus();</script>
";

$admin_abonnements_periodicite_form = "
<form class='form-$current_module' method=post action=\"./admin.php?categ=abonnements&sub=periodicite&action=update&id=!!id!!\">
<h3><span onclick='menuHide(this,event)'>!!form_title!!</span></h3>
<!--    Contenu du form    -->
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='libelle'>$msg[abonnements_periodicite_libelle]</label>
		</div>
	<div class='row'>
		<input type=text name='libelle' value='!!libelle!!' class='saisie-50em' />
		</div>
	
	<div class='row'>
		<label class='etiquette' for='duree'>$msg[abonnements_periodicite_duree]</label>
		</div>
	<div class='row'>
		<input type=text name='duree' value='!!duree!!' class='saisie-50em' />
		</div>
				
	<div class='row'>
		<label class='etiquette' for='unite'>$msg[abonnements_periodicite_unite]</label>
		</div>
	<div class='row'>
		!!unite!!
		</div>
				
	<div class='row'>
		<label class='etiquette' for='seuil_periodicite'>$msg[seuil_periodicite]</label>
		</div>
	<div class='row'>
		<input type=text name='seuil_periodicite' value='!!seuil_periodicite!!' class='saisie-50em' />
		</div>
	
	<div class='row'>
		<label class='etiquette' for='retard_periodicite'>$msg[retard_periodicite]</label>
		</div>
	<div class='row'>
		<input type=text name='retard_periodicite' value='!!retard_periodicite!!' class='saisie-50em' />
		</div>
		
	</div>
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=abonnements&sub=periodicite'\">&nbsp;
		<input class='bouton' type='submit' value=' $msg[77] ' onClick=\"return test_form(this.form)\">
		</div>
	<div class='right'>
		!!bouton_supprimer!!
		</div>
	</div>
<div class='row'></div>
</form>
<script type='text/javascript'>document.forms['typdocform'].elements['form_libelle'].focus();</script>
";

// $admin_procs_clas_form : template form classements de procï¿½dures
$admin_procs_clas_form = "
<form class='form-$current_module' name='proc_clas_form' method=post action=\"./admin.php?categ=proc&sub=clas&action=update&idproc_classement=!!idproc_classement!!\">
<h3><span onclick='menuHide(this,event)'>!!form_title!!</span></h3>
<!--    Contenu du form    -->
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_libproc_classement'>$msg[proc_clas_lib]</label>
		</div>
	<div class='row'>
		<input type=text name=form_libproc_classement value='!!libelle!!' class='saisie-50em' />
		</div>
	</div>

<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=proc&sub=clas'\" />&nbsp;
		<input class='bouton' type='submit' value=' $msg[77] ' onClick=\"return test_form(this.form)\" />
		</div>
	<div class='right'>
		<input class='bouton' type='button' value='$msg[supprimer]' onClick=\"javascript:confirmation_delete(!!idproc_classement!!,'!!libelle_suppr!!')\" />
		</div>
	</div>
<div class='row'></div>
</form>
<script type='text/javascript'>document.forms['proc_clas_form'].elements['form_libproc_classement'].focus();</script>
";

// $admin_chklnk_form : template form choix paniers nettoyage liens cassés
$admin_chklnk_form = "
	<form class='form-$current_module' id='login' method='post' action='./admin.php'>
	<h3>".$msg['chklnk_titre']."</h3>
	<div class='form-contenu'>
		<div class='row'>
			<input type='checkbox' checked name='chkrestrict' value='1'>&nbsp;<label class='etiquette' >".$msg['chklnk_restrict']."</label>
			<blockquote>";
			$requetenoti = "SELECT idcaddie, name FROM caddie where type='NOTI' and (autorisations='$PMBuserid' or autorisations like '$PMBuserid %' or autorisations like '% $PMBuserid %' or autorisations like '% $PMBuserid') order by name ";
			$requetebull = "SELECT idcaddie, name FROM caddie where type='BULL' and (autorisations='$PMBuserid' or autorisations like '$PMBuserid %' or autorisations like '% $PMBuserid %' or autorisations like '% $PMBuserid') order by name ";
			$requeteexpl = "SELECT idcaddie, name FROM caddie where type='EXPL' and (autorisations='$PMBuserid' or autorisations like '$PMBuserid %' or autorisations like '% $PMBuserid %' or autorisations like '% $PMBuserid') order by name ";
$admin_chklnk_form .= "
				<div class='colonne3'>
					<label class='etiquette' >".$msg['chklnk_restrict_by_basket_noti']."</label><br />
					".gen_liste ($requetenoti, "idcaddie", "name", "idcaddienoti[]", "", "", "", "","","",1,"style='width:100%;'")."
				</div>
				<div class='colonne3'>
					<label class='etiquette' >".$msg['chklnk_restrict_by_basket_bull']."</label><br />
					".gen_liste ($requetebull, "idcaddie", "name", "idcaddiebull[]", "", "", "", "","","",1,"style='width:100%;'")."
				</div>
				<div class='colonne3'>
					<label class='etiquette' >".$msg['chklnk_restrict_by_basket_expl']."</label><br />
					".gen_liste ($requeteexpl, "idcaddie", "name", "idcaddieexpl[]", "", "", "", "","","",1,"style='width:100%;'")."
				</div>
			</blockquote>			
		</div>
		<h3>".$msg['chklnk_titre_notice']."</h3>
		<div class='row'>
			<input type='checkbox' checked name='chknoti' value='1'>&nbsp;<label class='etiquette' >".$msg['chklnk_chk_noti']."</label>&nbsp;
		<blockquote>
			<input type='checkbox' name='ajtnoti' value='1'>&nbsp;".$msg['chklnk_choix_caddie_noti']."
	        ";
			$requetetmpcad = "SELECT idcaddie, name FROM caddie where type='NOTI' and (autorisations='$PMBuserid' or autorisations like '$PMBuserid %' or autorisations like '% $PMBuserid %' or autorisations like '% $PMBuserid') order by name ";
$admin_chklnk_form .= gen_liste ($requetetmpcad, "idcaddie", "name", "idcaddienot", "", "", "", "","","",0);
$admin_chklnk_form .= "</blockquote>
		</div>
		<div class='row'>
			<input type='checkbox' checked name='chkenum' value='1'>&nbsp;<label class='etiquette' >".$msg['chklnk_chk_enum']."</label>&nbsp;
		<blockquote>
			<input type='checkbox' name='ajtenum' value='1'>&nbsp;".$msg['chklnk_choix_caddie_enum']."
	        ";
			$requetetmpcad = "SELECT idcaddie, name FROM caddie where type='NOTI' and (autorisations='$PMBuserid' or autorisations like '$PMBuserid %' or autorisations like '% $PMBuserid %' or autorisations like '% $PMBuserid') order by name ";
$admin_chklnk_form .= gen_liste ($requetetmpcad, "idcaddie", "name", "idcaddielnk", "", "", "", "","","",0);
$admin_chklnk_form .= "</blockquote>
		</div>
		<div class='row'>
			<input type='checkbox' checked name='chkbull' value='1'>&nbsp;<label class='etiquette' >".$msg['chklnk_chk_bull']."</label>&nbsp;
		<blockquote>
			<input type='checkbox' name='ajtbull' value='1'>&nbsp;".$msg['chklnk_choix_caddie_bull']."
	        ";
			$requetetmpcad = "SELECT idcaddie, name FROM caddie where type='BULL' and (autorisations='$PMBuserid' or autorisations like '$PMBuserid %' or autorisations like '% $PMBuserid %' or autorisations like '% $PMBuserid') order by name ";
$admin_chklnk_form .= gen_liste ($requetetmpcad, "idcaddie", "name", "idcaddiebul", "", "", "", "","","",0);
$admin_chklnk_form .= "</blockquote>
		</div>
		<h3>".$msg['chklnk_titre_autorites']."</h3>
		<div class='row'>
			<input type='checkbox' checked name='chkautaut' value='1'>&nbsp;<label class='etiquette' >".$msg['chklnk_chk_autaut']."</label>
		</div>
		<div class='row'>
			<input type='checkbox' checked name='chkautpub' value='1'>&nbsp;<label class='etiquette' >".$msg['chklnk_chk_autpub']."</label>
		</div>
		<div class='row'>
			<input type='checkbox' checked name='chkautcol' value='1'>&nbsp;<label class='etiquette' >".$msg['chklnk_chk_autcol']."</label>
		</div>
		<div class='row'>
			<input type='checkbox' checked name='chkautsco' value='1'>&nbsp;<label class='etiquette' >".$msg['chklnk_chk_autsco']."</label>
		</div>
		</div>

	<!--	Bouton d'envoi	-->
	<div class='row'>
		<input type='hidden' name='suite' value='OK' />
		<input type='hidden' name='categ' value='chklnk' />
		<input type='submit' class='bouton' value=\"".$msg['chklnk_bt_lancer']."\" />
	</div>
	</form>
	";

$admin_menu_infopages = "
<h1><span>".$msg['admin_menu_opac']." > !!menu_sous_rub!!</span></h1>
";

// $admin_infopages_form : template form des pages d'info
$admin_infopages_form = "
<form class='form-$current_module' name='infopagesform' method=post action=\"./admin.php?categ=infopages&sub=infopages&action=update&id=!!id!!\">
<h3><span onclick='menuHide(this,event)'>!!form_title!!</span></h3>
<!--    Contenu du form    -->
<div class='form-contenu'>
	<div class='row'>
	<div class='colonne2'>
		<div class='row'
			<label class='etiquette' for='form_title_infopage'>".$msg['infopage_title_infopage']."</label>
			</div>
		<div class='row'>
			<input type=text name='form_title_infopage' value=\"!!title_infopage!!\" class='saisie-50em' />
			</div>
	</div>
	<div class='colonne_suite'>
		<div class='row'>
			<label class='etiquette' for='form_valid_infopage'>".$msg['infopage_valid_infopage']."</label>
			<input type=checkbox name='form_valid_infopage' value='1' !!checkbox!! class='checkbox' />
		</div>
	</div>
	<div class='row'>
		<label class='etiquette' for='form_content_infopage'>".$msg['infopages_content_infopage']."</label>
		</div>
	<div class='row'>
		<textarea id='form_content_infopage' name='form_content_infopage' cols='120' rows='40'>!!content_infopage!!</textarea>
		</div>

	</div>
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=infopages&sub=infopages'\" />&nbsp;
		<input class='bouton' type='submit' value=' $msg[77] ' onClick=\"return test_form(this.form)\" />
		</div>
	<div class='right'>
		<input class='bouton' type='button' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete(!!id!!,'!!libelle_suppr!!')\" />
		</div>
</div>
<div class='row'></div>
</form>
<script type='text/javascript'>document.forms['infopagesform'].elements['form_title_infopage'].focus();</script>
";


// $admin_group_form : template groupe
$admin_group_form = "
<form class='form-$current_module' name='groupform' method=post action=\"./admin.php?categ=users&sub=groups&action=update&id=!!id!!\">
<h3><span onclick='menuHide(this,event)'>!!form_title!!</span></h3>
<!--    Contenu du form    -->
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_libelle'>".$msg['admin_usr_grp_lib']."</label>
		</div>
	<div class='row'>
		<input type=text id='form_libelle' name='form_libelle' value='!!libelle!!' class='saisie-50em' />
		</div>
	</div>
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=users&sub=groups'\" />&nbsp;
		<input class='bouton' type='submit' value=' $msg[77] ' onClick=\"return test_form(this.form)\" />
	</div>
	<div class='right'>
		<input class='bouton' type='button' value='".$msg['supprimer']."' onClick=\"javascript:confirmation_delete(!!id!!,'!!libelle_suppr!!')\" />
	</div>
</div>
<div class='row'></div>
</form>
<script type='text/javascript'>document.forms['groupform'].elements['form_libelle'].focus();</script>
";


?>
