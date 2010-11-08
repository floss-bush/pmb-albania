<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: dataref.inc.php,v 1.4 2010-05-06 10:01:36 mbertin Exp $

// references des index sur les tables

// prevents direct script access
if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// PMB version : 3.2.6.5 
// PMB database version v4.72

// generated from DATABASE bibli 2009-04-14 18:20:39

//  ###################### abo_liste_lecture 
$tabindexref["abo_liste_lecture"]["PRIMARY"][]="num_empr";
$tabindexref["abo_liste_lecture"]["PRIMARY"][]="num_liste";

//  ###################### abts_abts 
$tabindexref["abts_abts"]["PRIMARY"][]="abt_id";
$tabindexref["abts_abts"]["index_num_notice"][]="num_notice";


//  ###################### abts_abts_modeles 
$tabindexref["abts_abts_modeles"]["PRIMARY"][]="modele_id";
$tabindexref["abts_abts_modeles"]["PRIMARY"][]="abt_id";


//  ###################### abts_grille_abt 
$tabindexref["abts_grille_abt"]["PRIMARY"][]="id_bull";
$tabindexref["abts_grille_abt"]["num_abt"][]="num_abt";


//  ###################### abts_grille_modele 
$tabindexref["abts_grille_modele"]["PRIMARY"][]="num_modele";
$tabindexref["abts_grille_modele"]["PRIMARY"][]="date_parution";
$tabindexref["abts_grille_modele"]["PRIMARY"][]="type_serie";
$tabindexref["abts_grille_modele"]["num_modele"][]="num_modele";


//  ###################### abts_modeles 
$tabindexref["abts_modeles"]["PRIMARY"][]="modele_id";
$tabindexref["abts_modeles"]["num_notice"][]="num_notice";
$tabindexref["abts_modeles"]["num_periodicite"][]="num_periodicite";


//  ###################### abts_periodicites 
$tabindexref["abts_periodicites"]["PRIMARY"][]="periodicite_id";


//  ###################### acces_profiles 
$tabindexref["acces_profiles"]["PRIMARY"][]="prf_id";
$tabindexref["acces_profiles"]["prf_type"][]="prf_type";
$tabindexref["acces_profiles"]["prf_name"][]="prf_name";
$tabindexref["acces_profiles"]["dom_num"][]="dom_num";


//  ###################### acces_rights 
$tabindexref["acces_rights"]["PRIMARY"][]="dom_num";
$tabindexref["acces_rights"]["PRIMARY"][]="usr_prf_num";
$tabindexref["acces_rights"]["PRIMARY"][]="res_prf_num";
$tabindexref["acces_rights"]["dom_num"][]="dom_num";
$tabindexref["acces_rights"]["usr_prf_num"][]="usr_prf_num";
$tabindexref["acces_rights"]["res_prf_num"][]="res_prf_num";


//  ###################### actes 
$tabindexref["actes"]["PRIMARY"][]="id_acte";
$tabindexref["actes"]["num_fournisseur"][]="num_fournisseur";
$tabindexref["actes"]["date"][]="date_acte";
$tabindexref["actes"]["num_entite"][]="num_entite";
$tabindexref["actes"]["numero"][]="numero";


//  ###################### admin_session 
$tabindexref["admin_session"]["PRIMARY"][]="userid";


//  ###################### analysis 
$tabindexref["analysis"]["PRIMARY"][]="analysis_bulletin";
$tabindexref["analysis"]["PRIMARY"][]="analysis_notice";
$tabindexref["analysis"]["analysis_notice"][]="analysis_notice";


//  ###################### arch_emplacement 
$tabindexref["arch_emplacement"]["PRIMARY"][]="archempla_id";


//  ###################### arch_statut 
$tabindexref["arch_statut"]["PRIMARY"][]="archstatut_id";


//  ###################### arch_type 
$tabindexref["arch_type"]["PRIMARY"][]="archtype_id";


//  ###################### audit 
$tabindexref["audit"]["type_obj"][]="type_obj";
$tabindexref["audit"]["object_id"][]="object_id";
$tabindexref["audit"]["user_id"][]="user_id";
$tabindexref["audit"]["type_modif"][]="type_modif";


//  ###################### authors 
$tabindexref["authors"]["PRIMARY"][]="author_id";
$tabindexref["authors"]["author_see"][]="author_see";
$tabindexref["authors"]["author_name"][]="author_name";
$tabindexref["authors"]["author_rejete"][]="author_rejete";


//  ###################### avis 
$tabindexref["avis"]["PRIMARY"][]="id_avis";
$tabindexref["avis"]["avis_num_notice"][]="num_notice";
$tabindexref["avis"]["avis_num_empr"][]="num_empr";
$tabindexref["avis"]["avis_note"][]="note";


//  ###################### bannettes
$tabindexref["bannettes"]["PRIMARY"][]="id_bannette";


//  ###################### bannette_abon 
$tabindexref["bannette_abon"]["PRIMARY"][]="num_bannette";
$tabindexref["bannette_abon"]["PRIMARY"][]="num_empr";


//  ###################### bannette_contenu 
$tabindexref["bannette_contenu"]["PRIMARY"][]="num_bannette";
$tabindexref["bannette_contenu"]["PRIMARY"][]="num_notice";
$tabindexref["bannette_contenu"]["date_ajout"][]="date_ajout";


//  ###################### bannette_equation 
$tabindexref["bannette_equation"]["PRIMARY"][]="num_bannette";
$tabindexref["bannette_equation"]["PRIMARY"][]="num_equation";


//  ###################### bannette_exports 
$tabindexref["bannette_exports"]["PRIMARY"][]="num_bannette";
$tabindexref["bannette_exports"]["PRIMARY"][]="export_format";


//  ###################### bannettes 
$tabindexref["bannettes"]["PRIMARY"][]="id_bannette";


//  ###################### budgets 
$tabindexref["budgets"]["PRIMARY"][]="id_budget";


//  ###################### bulletins 
$tabindexref["bulletins"]["PRIMARY"][]="bulletin_id";
$tabindexref["bulletins"]["bulletin_numero"][]="bulletin_numero";
$tabindexref["bulletins"]["bulletin_notice"][]="bulletin_notice";
$tabindexref["bulletins"]["date_date"][]="date_date";
$tabindexref["bulletins"]["i_num_notice"][]="num_notice";


//  ###################### caddie 
$tabindexref["caddie"]["PRIMARY"][]="idcaddie";
$tabindexref["caddie"]["caddie_type"][]="type";


//  ###################### caddie_content 
$tabindexref["caddie_content"]["PRIMARY"][]="caddie_id";
$tabindexref["caddie_content"]["PRIMARY"][]="object_id";
$tabindexref["caddie_content"]["PRIMARY"][]="content";
$tabindexref["caddie_content"]["object_id"][]="object_id";


//  ###################### caddie_procs 
$tabindexref["caddie_procs"]["PRIMARY"][]="idproc";


//  ###################### categories 
$tabindexref["categories"]["PRIMARY"][]="num_noeud";
$tabindexref["categories"]["PRIMARY"][]="langue";
$tabindexref["categories"]["categ_langue"][]="langue";
$tabindexref["categories"]["libelle_categorie"][]="libelle_categorie";


//  ###################### classements 
$tabindexref["classements"]["PRIMARY"][]="id_classement";


//  ###################### collections 
$tabindexref["collections"]["PRIMARY"][]="collection_id";
$tabindexref["collections"]["collection_name"][]="collection_name";
$tabindexref["collections"]["collection_parent"][]="collection_parent";


//  ###################### collections_state 
$tabindexref["collections_state"]["PRIMARY"][]="collstate_id";
$tabindexref["collections_state"]["i_colls_arc"][]="collstate_archive";
$tabindexref["collections_state"]["i_colls_empl"][]="collstate_emplacement";
$tabindexref["collections_state"]["i_colls_type"][]="collstate_type";
$tabindexref["collections_state"]["i_colls_orig"][]="collstate_origine";
$tabindexref["collections_state"]["i_colls_cote"][]="collstate_cote";
$tabindexref["collections_state"]["i_colls_stat"][]="collstate_statut";
$tabindexref["collections_state"]["i_colls_serial"][]="id_serial";
$tabindexref["collections_state"]["i_colls_loc"][]="location_id";


//  ###################### collstate_custom 
$tabindexref["collstate_custom"]["PRIMARY"][]="idchamp";


//  ###################### collstate_custom_lists 
$tabindexref["collstate_custom_lists"]["collstate_custom_champ"][]="collstate_custom_champ";
$tabindexref["collstate_custom_lists"]["i_ccl_lv"][]="collstate_custom_list_value";

//  ###################### collstate_custom_values 
$tabindexref["collstate_custom_values"]["collstate_custom_champ"][]="collstate_custom_champ";
$tabindexref["collstate_custom_values"]["collstate_custom_origine"][]="collstate_custom_origine";
$tabindexref["collstate_custom_values"]["i_ccv_st"][]="collstate_custom_small_text";
$tabindexref["collstate_custom_values"]["i_ccv_t"][]="collstate_custom_text";
$tabindexref["collstate_custom_values"]["i_ccv_i"][]="collstate_custom_integer";
$tabindexref["collstate_custom_values"]["i_ccv_d"][]="collstate_custom_date";
$tabindexref["collstate_custom_values"]["i_ccv_f"][]="collstate_custom_float";


//  ###################### comptes 
$tabindexref["comptes"]["PRIMARY"][]="id_compte";
$tabindexref["comptes"]["i_cpt_proprio_id"][]="proprio_id";


//  ###################### connectors 
$tabindexref["connectors"]["PRIMARY"][]="connector_id";


//  ###################### connectors_categ 
$tabindexref["connectors_categ"]["PRIMARY"][]="connectors_categ_id";


//  ###################### connectors_categ_sources 
$tabindexref["connectors_categ_sources"]["PRIMARY"][]="num_categ";
$tabindexref["connectors_categ_sources"]["PRIMARY"][]="num_source";
$tabindexref["connectors_categ_sources"]["i_num_source"][]="num_source";


//  ###################### connectors_out 
$tabindexref["connectors_out"]["PRIMARY"][]="connectors_out_id";


//  ###################### connectors_out_oai_tokens 
$tabindexref["connectors_out_oai_tokens"]["PRIMARY"][]="connectors_out_oai_token_token";


//  ###################### connectors_out_setcaches 
$tabindexref["connectors_out_setcaches"]["PRIMARY"][]="connectors_out_setcache_id";
$tabindexref["connectors_out_setcaches"]["connectors_out_setcache_setnum"][]="connectors_out_setcache_setnum";


//  ###################### connectors_out_setcache_values 
$tabindexref["connectors_out_setcache_values"]["PRIMARY"][]="connectors_out_setcache_values_cachenum";
$tabindexref["connectors_out_setcache_values"]["PRIMARY"][]="connectors_out_setcache_values_value";


//  ###################### connectors_out_setcategs 
$tabindexref["connectors_out_setcategs"]["PRIMARY"][]="connectors_out_setcateg_id";
$tabindexref["connectors_out_setcategs"]["connectors_out_setcateg_name"][]="connectors_out_setcateg_name";


//  ###################### connectors_out_setcateg_sets 
$tabindexref["connectors_out_setcateg_sets"]["PRIMARY"][]="connectors_out_setcategset_setnum";
$tabindexref["connectors_out_setcateg_sets"]["PRIMARY"][]="connectors_out_setcategset_categnum";


//  ###################### connectors_out_sets 
$tabindexref["connectors_out_sets"]["PRIMARY"][]="connector_out_set_id";
$tabindexref["connectors_out_sets"]["connector_out_set_caption"][]="connector_out_set_caption";


//  ###################### connectors_out_sources 
$tabindexref["connectors_out_sources"]["PRIMARY"][]="connectors_out_source_id";


//  ###################### connectors_out_sources_esgroups 
$tabindexref["connectors_out_sources_esgroups"]["PRIMARY"][]="connectors_out_source_esgroup_sourcenum";
$tabindexref["connectors_out_sources_esgroups"]["PRIMARY"][]="connectors_out_source_esgroup_esgroupnum";


//  ###################### connectors_sources 
$tabindexref["connectors_sources"]["PRIMARY"][]="source_id";


//  ###################### coordonnees 
$tabindexref["coordonnees"]["PRIMARY"][]="id_contact";


//  ###################### demandes 
$tabindexref["demandes"]["PRIMARY"][]="id_demande";
$tabindexref["demandes"]["i_num_demandeur"][]="num_demandeur";
$tabindexref["demandes"]["i_date_demande"][]="date_demande";
$tabindexref["demandes"]["i_deadline_demande"][]="deadline_demande";


//  ###################### demandes_actions 
$tabindexref["demandes_actions"]["PRIMARY"][]="id_action";
$tabindexref["demandes_actions"]["i_date_action"][]="date_action";
$tabindexref["demandes_actions"]["i_deadline_action"][]="deadline_action";
$tabindexref["demandes_actions"]["i_num_demande"][]="num_demande";
$tabindexref["demandes_actions"]["i_actions_user"][]="actions_num_user";
$tabindexref["demandes_actions"]["i_actions_user"][]="actions_type_user";


//  ###################### demandes_notes 
$tabindexref["demandes_notes"]["PRIMARY"][]="id_note";
$tabindexref["demandes_notes"]["i_date_note"][]="date_note";
$tabindexref["demandes_notes"]["i_num_action"][]="num_action";
$tabindexref["demandes_notes"]["i_num_note_parent"][]="num_note_parent";
$tabindexref["demandes_notes"]["i_notes_user"][]="notes_num_user";
$tabindexref["demandes_notes"]["i_notes_user"][]="notes_type_user";


//  ###################### demandes_theme
$tabindexref["demandes_theme"]["PRIMARY"][]="id_theme";


//  ###################### demandes_type
$tabindexref["demandes_type"]["PRIMARY"][]="id_type";


//  ###################### demandes_users
$tabindexref["demandes_users"]["PRIMARY"][]="num_user";
$tabindexref["demandes_users"]["PRIMARY"][]="num_demande";


//  ###################### docsloc_section 
$tabindexref["docsloc_section"]["PRIMARY"][]="num_section";
$tabindexref["docsloc_section"]["PRIMARY"][]="num_location";


//  ###################### docs_codestat 
$tabindexref["docs_codestat"]["PRIMARY"][]="idcode";
$tabindexref["docs_codestat"]["statisdoc_owner"][]="statisdoc_owner";


//  ###################### docs_location 
$tabindexref["docs_location"]["PRIMARY"][]="idlocation";
$tabindexref["docs_location"]["locdoc_owner"][]="locdoc_owner";


//  ###################### docs_section 
$tabindexref["docs_section"]["PRIMARY"][]="idsection";
$tabindexref["docs_section"]["sdoc_owner"][]="sdoc_owner";


//  ###################### docs_statut 
$tabindexref["docs_statut"]["PRIMARY"][]="idstatut";
$tabindexref["docs_statut"]["statusdoc_owner"][]="statusdoc_owner";


//  ###################### docs_type 
$tabindexref["docs_type"]["PRIMARY"][]="idtyp_doc";


//  ###################### empr 
$tabindexref["empr"]["PRIMARY"][]="id_empr";
$tabindexref["empr"]["empr_cb"][]="empr_cb";
$tabindexref["empr"]["empr_nom"][]="empr_nom";
$tabindexref["empr"]["empr_date_adhesion"][]="empr_date_adhesion";
$tabindexref["empr"]["empr_date_expiration"][]="empr_date_expiration";
$tabindexref["empr"]["i_empr_categ"][]="empr_categ";
$tabindexref["empr"]["i_empr_codestat"][]="empr_codestat";
$tabindexref["empr"]["i_empr_location"][]="empr_location";
$tabindexref["empr"]["i_empr_statut"][]="empr_statut";
$tabindexref["empr"]["i_empr_typabt"][]="type_abt";


//  ###################### empr_caddie 
$tabindexref["empr_caddie"]["PRIMARY"][]="idemprcaddie";


//  ###################### empr_caddie_content 
$tabindexref["empr_caddie_content"]["PRIMARY"][]="empr_caddie_id";
$tabindexref["empr_caddie_content"]["PRIMARY"][]="object_id";
$tabindexref["empr_caddie_content"]["object_id"][]="object_id";


//  ###################### empr_caddie_procs 
$tabindexref["empr_caddie_procs"]["PRIMARY"][]="idproc";


//  ###################### empr_categ 
$tabindexref["empr_categ"]["PRIMARY"][]="id_categ_empr";


//  ###################### empr_codestat 
$tabindexref["empr_codestat"]["PRIMARY"][]="idcode";


//  ###################### empr_custom 
$tabindexref["empr_custom"]["PRIMARY"][]="idchamp";


//  ###################### empr_custom_lists 
$tabindexref["empr_custom_lists"]["empr_custom_champ"][]="empr_custom_champ";
$tabindexref["empr_custom_lists"]["i_ecl_lv"][]="empr_custom_list_value";

//  ###################### empr_custom_values 
$tabindexref["empr_custom_values"]["empr_custom_champ"][]="empr_custom_champ";
$tabindexref["empr_custom_values"]["empr_custom_origine"][]="empr_custom_origine";
$tabindexref["empr_custom_values"]["i_ecv_st"][]="empr_custom_small_text";
$tabindexref["empr_custom_values"]["i_ecv_t"][]="empr_custom_text";
$tabindexref["empr_custom_values"]["i_ecv_i"][]="empr_custom_integer";
$tabindexref["empr_custom_values"]["i_ecv_d"][]="empr_custom_date";
$tabindexref["empr_custom_values"]["i_ecv_f"][]="empr_custom_float";


//  ###################### empr_groupe 
$tabindexref["empr_groupe"]["PRIMARY"][]="empr_id";
$tabindexref["empr_groupe"]["PRIMARY"][]="groupe_id";


//  ###################### empr_statut 
$tabindexref["empr_statut"]["PRIMARY"][]="idstatut";


//  ###################### empty_words_calculs 
$tabindexref["empty_words_calculs"]["PRIMARY"][]="id_calcul";


//  ###################### entites 
$tabindexref["entites"]["PRIMARY"][]="id_entite";
$tabindexref["entites"]["raison_sociale"][]="raison_sociale";


//  ###################### entrepots_localisations 
$tabindexref["entrepots_localisations"]["PRIMARY"][]="loc_id";
$tabindexref["entrepots_localisations"]["loc_code"][]="loc_code";


//  ###################### equations 
$tabindexref["equations"]["PRIMARY"][]="id_equation";


//  ###################### error_log 


//  ###################### es_cache
$tabindexref["es_cache"]["PRIMARY"][]="escache_groupname";
$tabindexref["es_cache"]["PRIMARY"][]="escache_unique_id";
$tabindexref["es_cache"]["PRIMARY"][]="escache_value";


//  ###################### es_cache_blob 
$tabindexref["es_cache_blob"]["PRIMARY"][]="es_cache_objectref";
$tabindexref["es_cache_blob"]["PRIMARY"][]="es_cache_objecttype";
$tabindexref["es_cache_blob"]["PRIMARY"][]="es_cache_objectformat";
$tabindexref["es_cache_blob"]["PRIMARY"][]="es_cache_owner";


//  ###################### es_cache_int 
$tabindexref["es_cache_int"]["PRIMARY"][]="es_cache_objectref";
$tabindexref["es_cache_int"]["PRIMARY"][]="es_cache_objecttype";
$tabindexref["es_cache_int"]["PRIMARY"][]="es_cache_objectformat";
$tabindexref["es_cache_int"]["PRIMARY"][]="es_cache_owner";
$tabindexref["es_cache_int"]["cache_index"][]="es_cache_owner";
$tabindexref["es_cache_int"]["cache_index"][]="es_cache_objectformat";
$tabindexref["es_cache_int"]["cache_index"][]="es_cache_objecttype";


//  ###################### es_converted_cache
$tabindexref["es_converted_cache"]["PRIMARY"][]="es_converted_cache_objecttype";
$tabindexref["es_converted_cache"]["PRIMARY"][]="es_converted_cache_objectref";
$tabindexref["es_converted_cache"]["PRIMARY"][]="es_converted_cache_format";

//  ###################### es_esgroups
$tabindexref["es_esgroups"]["PRIMARY"][]="esgroup_id";
$tabindexref["es_esgroups"]["esgroup_name"][]="esgroup_name";


//  ###################### es_esgroup_esusers
$tabindexref["es_esgroup_esusers"]["PRIMARY"][]="esgroupuser_usernum";
$tabindexref["es_esgroup_esusers"]["PRIMARY"][]="esgroupuser_groupnum";
$tabindexref["es_esgroup_esusers"]["PRIMARY"][]="esgroupuser_usertype";


//  ###################### es_esusers
$tabindexref["es_esusers"]["PRIMARY"][]="esuser_id";
$tabindexref["es_esusers"]["esuser_username"][]="esuser_username";


//  ###################### es_methods
$tabindexref["es_methods"]["PRIMARY"][]="id_method";


//  ###################### es_methods_users
$tabindexref["es_methods_users"]["PRIMARY"][]="num_method";
$tabindexref["es_methods_users"]["PRIMARY"][]="num_user";


//  ###################### es_searchcache
$tabindexref["es_searchcache"]["PRIMARY"][]="es_searchcache_searchid";


//  ###################### es_searchsessions
$tabindexref["es_searchsessions"]["PRIMARY"][]="es_searchsession_id";


//  ###################### etagere 
$tabindexref["etagere"]["PRIMARY"][]="idetagere";


//  ###################### etagere_caddie 
$tabindexref["etagere_caddie"]["PRIMARY"][]="etagere_id";
$tabindexref["etagere_caddie"]["PRIMARY"][]="caddie_id";


//  ###################### exemplaires 
$tabindexref["exemplaires"]["PRIMARY"][]="expl_id";
$tabindexref["exemplaires"]["expl_cb"][]="expl_cb";
$tabindexref["exemplaires"]["expl_typdoc"][]="expl_typdoc";
$tabindexref["exemplaires"]["expl_cote"][]="expl_cote";
$tabindexref["exemplaires"]["expl_notice"][]="expl_notice";
$tabindexref["exemplaires"]["expl_codestat"][]="expl_codestat";
$tabindexref["exemplaires"]["expl_owner"][]="expl_owner";
$tabindexref["exemplaires"]["expl_bulletin"][]="expl_bulletin";
$tabindexref["exemplaires"]["i_expl_location"][]="expl_location";
$tabindexref["exemplaires"]["i_expl_section"][]="expl_section";
$tabindexref["exemplaires"]["i_expl_statut"][]="expl_statut";
$tabindexref["exemplaires"]["i_expl_lastempr"][]="expl_lastempr";


//  ###################### exemplaires_temp 
$tabindexref["exemplaires_temp"]["cb"][]="cb";


//  ###################### exercices 
$tabindexref["exercices"]["PRIMARY"][]="id_exercice";


//  ###################### explnum 
$tabindexref["explnum"]["PRIMARY"][]="explnum_id";
$tabindexref["explnum"]["explnum_notice"][]="explnum_notice";
$tabindexref["explnum"]["explnum_bulletin"][]="explnum_bulletin";


//  ###################### explnum_doc 
$tabindexref["explnum_doc"]["PRIMARY"][]="id_explnum_doc";


//  ###################### explnum_doc_actions 
$tabindexref["explnum_doc_actions"]["PRIMARY"][]="num_explnum_doc";
$tabindexref["explnum_doc_actions"]["PRIMARY"][]="num_action";


//  ###################### explnum_doc_sugg 
$tabindexref["explnum_doc_sugg"]["PRIMARY"][]="num_explnum_doc";
$tabindexref["explnum_doc_sugg"]["PRIMARY"][]="num_suggestion";


//  ###################### expl_custom 
$tabindexref["expl_custom"]["PRIMARY"][]="idchamp";


//  ###################### expl_custom_lists 
$tabindexref["expl_custom_lists"]["expl_custom_champ"][]="expl_custom_champ";
$tabindexref["expl_custom_lists"]["i_excl_lv"][]="expl_custom_list_value";


//  ###################### expl_custom_values 
$tabindexref["expl_custom_values"]["expl_custom_champ"][]="expl_custom_champ";
$tabindexref["expl_custom_values"]["expl_custom_origine"][]="expl_custom_origine";
$tabindexref["expl_custom_values"]["i_excv_st"][]="expl_custom_small_text";
$tabindexref["expl_custom_values"]["i_excv_t"][]="expl_custom_text";
$tabindexref["expl_custom_values"]["i_excv_i"][]="expl_custom_integer";
$tabindexref["expl_custom_values"]["i_excv_d"][]="expl_custom_date";
$tabindexref["expl_custom_values"]["i_excv_f"][]="expl_custom_float";


//  ###################### external_count 
$tabindexref["external_count"]["PRIMARY"][]="rid";
$tabindexref["external_count"]["recid"][]="recid";


//  ###################### frais 
$tabindexref["frais"]["PRIMARY"][]="id_frais";


//  ###################### grilles 
$tabindexref["grilles"]["PRIMARY"][]="grille_typdoc";
$tabindexref["grilles"]["PRIMARY"][]="grille_niveau_biblio";
$tabindexref["grilles"]["PRIMARY"][]="grille_localisation";


//  ###################### groupe 
$tabindexref["groupe"]["PRIMARY"][]="id_groupe";
$tabindexref["groupe"]["libelle_groupe"][]="libelle_groupe";


//  ###################### import_marc 
$tabindexref["import_marc"]["PRIMARY"][]="id_import";
$tabindexref["import_marc"]["i_nonot_orig"][]="no_notice";
$tabindexref["import_marc"]["i_nonot_orig"][]="origine";


//  ###################### indexint 
$tabindexref["indexint"]["PRIMARY"][]="indexint_id";
$tabindexref["indexint"]["indexint_name"][]="indexint_name";
$tabindexref["indexint"]["indexint_name"][]="num_pclass";


//  ###################### infopages 
$tabindexref["infopages"]["PRIMARY"][]="id_infopage";


//  ###################### lenders 
$tabindexref["lenders"]["PRIMARY"][]="idlender";


//  ###################### liens_actes 
$tabindexref["liens_actes"]["PRIMARY"][]="num_acte";
$tabindexref["liens_actes"]["PRIMARY"][]="num_acte_lie";


//  ###################### lignes_actes 
$tabindexref["lignes_actes"]["PRIMARY"][]="id_ligne";
$tabindexref["lignes_actes"]["num_acte"][]="num_acte";


//  ###################### linked_mots 
$tabindexref["linked_mots"]["PRIMARY"][]="num_mot";
$tabindexref["linked_mots"]["PRIMARY"][]="num_linked_mot";
$tabindexref["linked_mots"]["PRIMARY"][]="type_lien";


//  ###################### logopac 
$tabindexref["logopac"]["PRIMARY"][]="id_log";


//  ###################### log_expl_retard 
$tabindexref["log_expl_retard"]["PRIMARY"][]="id_log";


//  ###################### log_retard 
$tabindexref["log_retard"]["PRIMARY"][]="id_log";


//  ###################### mots 
$tabindexref["mots"]["PRIMARY"][]="id_mot";
$tabindexref["mots"]["mot"][]="mot";


//  ###################### noeuds 
$tabindexref["noeuds"]["PRIMARY"][]="id_noeud";
$tabindexref["noeuds"]["num_parent"][]="num_parent";
$tabindexref["noeuds"]["num_thesaurus"][]="num_thesaurus";
$tabindexref["noeuds"]["autorite"][]="autorite";
$tabindexref["noeuds"]["key_path"][]="path";


//  ###################### notices 
$tabindexref["notices"]["PRIMARY"][]="notice_id";
$tabindexref["notices"]["typdoc"][]="typdoc";
$tabindexref["notices"]["tparent_id"][]="tparent_id";
$tabindexref["notices"]["ed1_id"][]="ed1_id";
$tabindexref["notices"]["ed2_id"][]="ed2_id";
$tabindexref["notices"]["coll_id"][]="coll_id";
$tabindexref["notices"]["subcoll_id"][]="subcoll_id";
$tabindexref["notices"]["cb"][]="code";
$tabindexref["notices"]["indexint"][]="indexint";
$tabindexref["notices"]["sig_index"][]="signature";
$tabindexref["notices"]["i_notice_n_biblio"][]="niveau_biblio";
$tabindexref["notices"]["i_notice_n_hierar"][]="niveau_hierar";
$tabindexref["notices"]["notice_eformat"][]="eformat";
$tabindexref["notices"]["i_date_parution"][]="date_parution";


//  ###################### notices_categories 
$tabindexref["notices_categories"]["PRIMARY"][]="notcateg_notice";
$tabindexref["notices_categories"]["PRIMARY"][]="num_noeud";
$tabindexref["notices_categories"]["PRIMARY"][]="num_vedette";
$tabindexref["notices_categories"]["num_noeud"][]="num_noeud";


//  ###################### notices_custom 
$tabindexref["notices_custom"]["PRIMARY"][]="idchamp";


//  ###################### notices_custom_lists 
$tabindexref["notices_custom_lists"]["notices_custom_champ"][]="notices_custom_champ";
$tabindexref["notices_custom_lists"]["i_ncl_lv"][]="notices_custom_list_value";


//  ###################### notices_custom_values 
$tabindexref["notices_custom_values"]["notices_custom_champ"][]="notices_custom_champ";
$tabindexref["notices_custom_values"]["notices_custom_origine"][]="notices_custom_origine";
$tabindexref["notices_custom_values"]["i_ncv_st"][]="notices_custom_small_text";
$tabindexref["notices_custom_values"]["i_ncv_t"][]="notices_custom_text";
$tabindexref["notices_custom_values"]["i_ncv_i"][]="notices_custom_integer";
$tabindexref["notices_custom_values"]["i_ncv_d"][]="notices_custom_date";
$tabindexref["notices_custom_values"]["i_ncv_f"][]="notices_custom_float";


//  ###################### notices_global_index 
$tabindexref["notices_global_index"]["PRIMARY"][]="num_notice";
$tabindexref["notices_global_index"]["PRIMARY"][]="no_index";


//  ###################### notices_langues 
$tabindexref["notices_langues"]["PRIMARY"][]="num_notice";
$tabindexref["notices_langues"]["PRIMARY"][]="type_langue";
$tabindexref["notices_langues"]["PRIMARY"][]="code_langue";

//  ###################### notices_mots_global_index 
$tabindexref["notices_mots_global_index"]["PRIMARY"][]="id_notice";
$tabindexref["notices_mots_global_index"]["PRIMARY"][]="code_champ";
$tabindexref["notices_mots_global_index"]["PRIMARY"][]="mot";
$tabindexref["notices_mots_global_index"]["code_champ"][]="code_champ";
$tabindexref["notices_mots_global_index"]["mot"][]="mot";


//  ###################### notices_relations 
$tabindexref["notices_relations"]["PRIMARY"][]="num_notice";
$tabindexref["notices_relations"]["PRIMARY"][]="linked_notice";
$tabindexref["notices_relations"]["linked_notice"][]="linked_notice";
$tabindexref["notices_relations"]["relation_type"][]="relation_type";


//  ###################### notices_titres_uniformes 
$tabindexref["notices_titres_uniformes"]["PRIMARY"][]="ntu_num_notice";
$tabindexref["notices_titres_uniformes"]["PRIMARY"][]="ntu_num_tu";


//  ###################### notice_statut 
$tabindexref["notice_statut"]["PRIMARY"][]="id_notice_statut";


//  ###################### notice_tpl 
$tabindexref["notice_tpl"]["PRIMARY"][]="notpl_id";


//  ###################### notice_tplcode 
$tabindexref["notice_tplcode"]["PRIMARY"][]="num_notpl";
$tabindexref["notice_tplcode"]["PRIMARY"][]="notplcode_localisation";
$tabindexref["notice_tplcode"]["PRIMARY"][]="notplcode_typdoc";
$tabindexref["notice_tplcode"]["PRIMARY"][]="notplcode_niveau_biblio";



//  ###################### offres_remises 
$tabindexref["offres_remises"]["PRIMARY"][]="num_fournisseur";
$tabindexref["offres_remises"]["PRIMARY"][]="num_produit";


//  ###################### opac_liste_lecture 
$tabindexref["opac_liste_lecture"]["PRIMARY"][]="id_liste";


//  ###################### opac_sessions 
$tabindexref["opac_sessions"]["PRIMARY"][]="empr_id";


//  ###################### origine_notice 
$tabindexref["origine_notice"]["PRIMARY"][]="orinot_id";
$tabindexref["origine_notice"]["orinot_nom"][]="orinot_nom";


//  ###################### ouvertures 
$tabindexref["ouvertures"]["PRIMARY"][]="date_ouverture";
$tabindexref["ouvertures"]["PRIMARY"][]="num_location";


//  ###################### paiements 
$tabindexref["paiements"]["PRIMARY"][]="id_paiement";


//  ###################### parametres 
$tabindexref["parametres"]["PRIMARY"][]="id_param";
$tabindexref["parametres"]["typ_sstyp"][]="type_param";
$tabindexref["parametres"]["typ_sstyp"][]="sstype_param";


//  ###################### pclassement 
$tabindexref["pclassement"]["PRIMARY"][]="id_pclass";


//  ###################### pret 
$tabindexref["pret"]["PRIMARY"][]="pret_idexpl";
$tabindexref["pret"]["i_pret_idempr"][]="pret_idempr";


//  ###################### pret_archive 
$tabindexref["pret_archive"]["PRIMARY"][]="arc_id";
$tabindexref["pret_archive"]["i_pa_expl_id"][]="arc_expl_id";
$tabindexref["pret_archive"]["i_pa_idempr"][]="arc_id_empr";
$tabindexref["pret_archive"]["i_pa_expl_notice"][]="arc_expl_notice";
$tabindexref["pret_archive"]["i_pa_expl_bulletin"][]="arc_expl_bulletin";
$tabindexref["pret_archive"]["i_pa_arc_fin"][]="arc_fin";
$tabindexref["pret_archive"]["i_pa_arc_empr_categ"][]="arc_empr_categ";
$tabindexref["pret_archive"]["i_pa_arc_expl_location"][]="arc_expl_location";


//  ###################### procs 
$tabindexref["procs"]["PRIMARY"][]="idproc";
$tabindexref["procs"]["idproc"][]="idproc";


//  ###################### procs_classements 
$tabindexref["procs_classements"]["PRIMARY"][]="idproc_classement";


//  ###################### publishers 
$tabindexref["publishers"]["PRIMARY"][]="ed_id";
$tabindexref["publishers"]["ed_name"][]="ed_name";
$tabindexref["publishers"]["ed_ville"][]="ed_ville";


//  ###################### quotas 
$tabindexref["quotas"]["PRIMARY"][]="quota_type";
$tabindexref["quotas"]["PRIMARY"][]="constraint_type";
$tabindexref["quotas"]["PRIMARY"][]="elements";


//  ###################### quotas_finance 
$tabindexref["quotas_finance"]["PRIMARY"][]="quota_type";
$tabindexref["quotas_finance"]["PRIMARY"][]="constraint_type";
$tabindexref["quotas_finance"]["PRIMARY"][]="elements";


//  ###################### rapport_demandes 
$tabindexref["rapport_demandes"]["PRIMARY"][]="id_item";


//  ###################### recouvrements 
$tabindexref["recouvrements"]["PRIMARY"][]="recouvr_id";


//  ###################### resa 
$tabindexref["resa"]["PRIMARY"][]="id_resa";
$tabindexref["resa"]["resa_date_fin"][]="resa_date_fin";
$tabindexref["resa"]["resa_date"][]="resa_date";
$tabindexref["resa"]["resa_cb"][]="resa_cb";


//  ###################### resa_archive 
$tabindexref["resa_archive"]["PRIMARY"][]="resarc_id";
$tabindexref["resa_archive"]["i_pa_idempr"][]="resarc_id_empr";
$tabindexref["resa_archive"]["i_pa_notice"][]="resarc_idnotice";
$tabindexref["resa_archive"]["i_pa_bulletin"][]="resarc_idbulletin";
$tabindexref["resa_archive"]["i_pa_resarc_date"][]="resarc_date";


//  ###################### resa_loc 
$tabindexref["resa_loc"]["PRIMARY"][]="resa_loc";
$tabindexref["resa_loc"]["PRIMARY"][]="resa_emprloc";
$tabindexref["resa_loc"]["i_resa_emprloc"][]="resa_emprloc";


//  ###################### resa_planning 
$tabindexref["resa_planning"]["PRIMARY"][]="id_resa";
$tabindexref["resa_planning"]["resa_date_fin"][]="resa_date_fin";
$tabindexref["resa_planning"]["resa_date"][]="resa_date";


//  ###################### resa_ranger 
$tabindexref["resa_ranger"]["PRIMARY"][]="resa_cb";


//  ###################### responsability 
$tabindexref["responsability"]["PRIMARY"][]="responsability_author";
$tabindexref["responsability"]["PRIMARY"][]="responsability_notice";
$tabindexref["responsability"]["PRIMARY"][]="responsability_fonction";
$tabindexref["responsability"]["responsability_notice"][]="responsability_notice";


//  ###################### rss_content 
$tabindexref["rss_content"]["PRIMARY"][]="rss_id";


//  ###################### rss_flux 
$tabindexref["rss_flux"]["PRIMARY"][]="id_rss_flux";


//  ###################### rss_flux_content 
$tabindexref["rss_flux_content"]["PRIMARY"][]="num_rss_flux";
$tabindexref["rss_flux_content"]["PRIMARY"][]="type_contenant";
$tabindexref["rss_flux_content"]["PRIMARY"][]="num_contenant";


//  ###################### rubriques 
$tabindexref["rubriques"]["PRIMARY"][]="id_rubrique";


//  ###################### sauv_lieux 
$tabindexref["sauv_lieux"]["PRIMARY"][]="sauv_lieu_id";


//  ###################### sauv_log 
$tabindexref["sauv_log"]["PRIMARY"][]="sauv_log_id";


//  ###################### sauv_sauvegardes 
$tabindexref["sauv_sauvegardes"]["PRIMARY"][]="sauv_sauvegarde_id";


//  ###################### sauv_tables 
$tabindexref["sauv_tables"]["PRIMARY"][]="sauv_table_id";
$tabindexref["sauv_tables"]["sauv_table_nom"][]="sauv_table_nom";


//  ###################### search_perso 
$tabindexref["search_perso"]["PRIMARY"][]="search_id";


//  ###################### search_persopac 
$tabindexref["search_persopac"]["PRIMARY"][]="search_id";


//  ###################### series 
$tabindexref["series"]["PRIMARY"][]="serie_id";


//  ###################### sessions 


//  ###################### source_sync 
$tabindexref["source_sync"]["PRIMARY"][]="source_id";


//  ###################### statopac 
$tabindexref["statopac"]["PRIMARY"][]="id_log";


//  ###################### statopac_request 
$tabindexref["statopac_request"]["PRIMARY"][]="idproc";


//  ###################### statopac_vues 
$tabindexref["statopac_vues"]["PRIMARY"][]="id_vue";


//  ###################### statopac_vues_col 
$tabindexref["statopac_vues_col"]["PRIMARY"][]="id_col";


//  ###################### sub_collections 
$tabindexref["sub_collections"]["PRIMARY"][]="sub_coll_id";
$tabindexref["sub_collections"]["sub_coll_name"][]="sub_coll_name";


//  ###################### suggestions 
$tabindexref["suggestions"]["PRIMARY"][]="id_suggestion";


//  ###################### suggestions_categ 
$tabindexref["suggestions_categ"]["PRIMARY"][]="id_categ";


//  ###################### suggestions_origine 
$tabindexref["suggestions_origine"]["PRIMARY"][]="origine";
$tabindexref["suggestions_origine"]["PRIMARY"][]="num_suggestion";
$tabindexref["suggestions_origine"]["PRIMARY"][]="type_origine";
$tabindexref["suggestions_origine"]["i_origine"][]="origine";
$tabindexref["suggestions_origine"]["i_origine"][]="type_origine";


//  ###################### suggestions_source 
$tabindexref["suggestions_source"]["PRIMARY"][]="id_source";


//  ###################### tags 
$tabindexref["tags"]["PRIMARY"][]="id_tag";


//  ###################### thesaurus 
$tabindexref["thesaurus"]["PRIMARY"][]="id_thesaurus";
$tabindexref["thesaurus"]["libelle_thesaurus"][]="libelle_thesaurus";


//  ###################### titres_uniformes 
$tabindexref["titres_uniformes"]["PRIMARY"][]="tu_id";


//  ###################### transactions 
$tabindexref["transactions"]["PRIMARY"][]="id_transaction";


//  ###################### transferts 
$tabindexref["transferts"]["PRIMARY"][]="id_transfert";
$tabindexref["transferts"]["etat_transfert"][]="etat_transfert";


//  ###################### transferts_demande 
$tabindexref["transferts_demande"]["PRIMARY"][]="id_transfert_demande";
$tabindexref["transferts_demande"]["num_transfert"][]="num_transfert";
$tabindexref["transferts_demande"]["num_location_source"][]="num_location_source";
$tabindexref["transferts_demande"]["num_location_dest"][]="num_location_dest";
$tabindexref["transferts_demande"]["num_expl"][]="num_expl";


//  ###################### translation 
$tabindexref["translation"]["PRIMARY"][]="trans_table";
$tabindexref["translation"]["PRIMARY"][]="trans_field";
$tabindexref["translation"]["PRIMARY"][]="trans_lang";
$tabindexref["translation"]["PRIMARY"][]="trans_num";
$tabindexref["translation"]["i_lang"][]="trans_lang";


//  ###################### tris 
$tabindexref["tris"]["PRIMARY"][]="id_tri";


//  ###################### tu_distrib 
$tabindexref["tu_distrib"]["PRIMARY"][]="distrib_num_tu";
$tabindexref["tu_distrib"]["PRIMARY"][]="distrib_ordre";


//  ###################### tu_ref 
$tabindexref["tu_ref"]["PRIMARY"][]="ref_num_tu";
$tabindexref["tu_ref"]["PRIMARY"][]="ref_ordre";


//  ###################### tu_subdiv 
$tabindexref["tu_subdiv"]["PRIMARY"][]="subdiv_num_tu";
$tabindexref["tu_subdiv"]["PRIMARY"][]="subdiv_ordre";


//  ###################### tva_achats 
$tabindexref["tva_achats"]["PRIMARY"][]="id_tva";


//  ###################### types_produits 
$tabindexref["types_produits"]["PRIMARY"][]="id_produit";
$tabindexref["types_produits"]["libelle"][]="libelle";


//  ###################### type_abts 
$tabindexref["type_abts"]["PRIMARY"][]="id_type_abt";


//  ###################### type_comptes 
$tabindexref["type_comptes"]["PRIMARY"][]="id_type_compte";


//  ###################### upload_repertoire 
$tabindexref["upload_repertoire"]["PRIMARY"][]="repertoire_id";


//  ###################### users 
$tabindexref["users"]["PRIMARY"][]="userid";


//  ###################### users_groups 
$tabindexref["users_groups"]["PRIMARY"][]="grp_id";
$tabindexref["users_groups"]["i_users_groups_grp_name"][]="grp_name";


//  ###################### voir_aussi 
$tabindexref["voir_aussi"]["PRIMARY"][]="num_noeud_orig";
$tabindexref["voir_aussi"]["PRIMARY"][]="num_noeud_dest";
$tabindexref["voir_aussi"]["PRIMARY"][]="langue";
$tabindexref["voir_aussi"]["num_noeud_dest"][]="num_noeud_dest";


//  ###################### z_attr 
$tabindexref["z_attr"]["PRIMARY"][]="attr_bib_id";
$tabindexref["z_attr"]["PRIMARY"][]="attr_libelle";


//  ###################### z_bib 
$tabindexref["z_bib"]["PRIMARY"][]="bib_id";


//  ###################### z_notices 
$tabindexref["z_notices"]["PRIMARY"][]="znotices_id";
$tabindexref["z_notices"]["idx_z_notices_idq"][]="znotices_query_id";
$tabindexref["z_notices"]["idx_z_notices_isbn"][]="isbn";
$tabindexref["z_notices"]["idx_z_notices_titre"][]="titre";
$tabindexref["z_notices"]["idx_z_notices_auteur"][]="auteur";


//  ###################### z_query 
$tabindexref["z_query"]["PRIMARY"][]="zquery_id";
$tabindexref["z_query"]["zquery_date"][]="zquery_date";
