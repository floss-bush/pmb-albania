-- Tables descriptions for PMB 
-- 2010-Sep-21 17:57:26
-- $Id: bibli.sql,v 1.68 2010-09-21 15:58:20 touraine37 Exp $
set names 'utf8';

--
-- Table structure for table abo_liste_lecture
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE abo_liste_lecture (
  num_empr int(8) unsigned NOT NULL DEFAULT '0',
  num_liste int(8) unsigned NOT NULL DEFAULT '0',
  etat int(1) unsigned NOT NULL DEFAULT '0',
  commentaire text NOT NULL,
  PRIMARY KEY (num_empr,num_liste)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table abts_abts
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE abts_abts (
  abt_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  abt_name varchar(255) NOT NULL DEFAULT '',
  base_modele_name varchar(255) NOT NULL DEFAULT '',
  base_modele_id int(11) NOT NULL DEFAULT '0',
  num_notice int(11) NOT NULL DEFAULT '0',
  date_debut date NOT NULL DEFAULT '0000-00-00',
  date_fin date NOT NULL DEFAULT '0000-00-00',
  fournisseur int(11) NOT NULL DEFAULT '0',
  destinataire varchar(255) NOT NULL DEFAULT '',
  cote varchar(255) NOT NULL DEFAULT '',
  typdoc_id int(11) NOT NULL DEFAULT '0',
  exemp_auto int(11) NOT NULL DEFAULT '0',
  location_id int(11) NOT NULL DEFAULT '0',
  section_id int(11) NOT NULL DEFAULT '0',
  lender_id int(11) NOT NULL DEFAULT '0',
  statut_id int(11) NOT NULL DEFAULT '0',
  codestat_id int(11) NOT NULL DEFAULT '0',
  type_antivol int(11) NOT NULL DEFAULT '0',
  duree_abonnement int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (abt_id),
  KEY index_num_notice (num_notice)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table abts_abts_modeles
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE abts_abts_modeles (
  modele_id int(11) NOT NULL DEFAULT '0',
  abt_id int(11) NOT NULL DEFAULT '0',
  num int(11) NOT NULL DEFAULT '0',
  vol int(11) NOT NULL DEFAULT '0',
  tome int(11) NOT NULL DEFAULT '0',
  delais int(11) NOT NULL DEFAULT '0',
  critique int(11) NOT NULL DEFAULT '0',
  num_statut_general smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (modele_id,abt_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table abts_grille_abt
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE abts_grille_abt (
  id_bull int(11) NOT NULL AUTO_INCREMENT,
  num_abt int(10) unsigned NOT NULL DEFAULT '0',
  date_parution date NOT NULL DEFAULT '0000-00-00',
  modele_id int(11) NOT NULL DEFAULT '0',
  type int(11) NOT NULL DEFAULT '0',
  nombre int(11) NOT NULL DEFAULT '0',
  numero int(11) NOT NULL DEFAULT '0',
  ordre int(11) NOT NULL DEFAULT '0',
  state int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (id_bull),
  KEY num_abt (num_abt)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table abts_grille_modele
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE abts_grille_modele (
  num_modele int(10) unsigned NOT NULL DEFAULT '0',
  date_parution date NOT NULL DEFAULT '0000-00-00',
  type_serie int(11) NOT NULL DEFAULT '0',
  numero varchar(50) NOT NULL DEFAULT '',
  nombre_recu int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (num_modele,date_parution,type_serie),
  KEY num_modele (num_modele)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table abts_modeles
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE abts_modeles (
  modele_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  modele_name varchar(255) NOT NULL DEFAULT '',
  num_notice int(10) unsigned NOT NULL DEFAULT '0',
  num_periodicite int(10) unsigned NOT NULL DEFAULT '0',
  duree_abonnement int(11) NOT NULL DEFAULT '0',
  date_debut date DEFAULT NULL,
  date_fin date DEFAULT NULL,
  days varchar(7) NOT NULL DEFAULT '1111111',
  day_month varchar(31) NOT NULL DEFAULT '1111111111111111111111111111111',
  week_month varchar(6) NOT NULL DEFAULT '111111',
  week_year varchar(54) NOT NULL DEFAULT '111111111111111111111111111111111111111111111111111111',
  month_year varchar(12) NOT NULL DEFAULT '111111111111',
  num_cycle int(11) NOT NULL DEFAULT '0',
  num_combien int(11) NOT NULL DEFAULT '0',
  num_increment int(11) NOT NULL DEFAULT '0',
  num_date_unite int(11) NOT NULL DEFAULT '0',
  num_increment_date int(11) NOT NULL DEFAULT '0',
  num_depart int(11) NOT NULL DEFAULT '0',
  vol_actif int(11) NOT NULL DEFAULT '0',
  vol_increment int(11) NOT NULL DEFAULT '0',
  vol_date_unite int(11) NOT NULL DEFAULT '0',
  vol_increment_numero int(11) NOT NULL DEFAULT '0',
  vol_increment_date int(11) NOT NULL DEFAULT '0',
  vol_cycle int(11) NOT NULL DEFAULT '0',
  vol_combien int(11) NOT NULL DEFAULT '0',
  vol_depart int(11) NOT NULL DEFAULT '0',
  tom_actif int(11) NOT NULL DEFAULT '0',
  tom_increment int(11) NOT NULL DEFAULT '0',
  tom_date_unite int(11) NOT NULL DEFAULT '0',
  tom_increment_numero int(11) NOT NULL DEFAULT '0',
  tom_increment_date int(11) NOT NULL DEFAULT '0',
  tom_cycle int(11) NOT NULL DEFAULT '0',
  tom_combien int(11) NOT NULL DEFAULT '0',
  tom_depart int(11) NOT NULL DEFAULT '0',
  format_aff varchar(255) NOT NULL DEFAULT '',
  format_periode varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (modele_id),
  KEY num_notice (num_notice),
  KEY num_periodicite (num_periodicite)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table abts_periodicites
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE abts_periodicites (
  periodicite_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  libelle varchar(255) NOT NULL DEFAULT '',
  duree int(11) NOT NULL DEFAULT '0',
  unite int(11) NOT NULL DEFAULT '0',
  retard_periodicite int(4) DEFAULT '0',
  seuil_periodicite int(4) DEFAULT '0',
  PRIMARY KEY (periodicite_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table acces_profiles
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE acces_profiles (
  prf_id int(2) unsigned NOT NULL AUTO_INCREMENT,
  prf_type int(1) unsigned NOT NULL DEFAULT '1',
  prf_name varchar(255) NOT NULL,
  prf_rule blob NOT NULL,
  prf_hrule text NOT NULL,
  prf_used int(2) unsigned NOT NULL DEFAULT '0',
  dom_num int(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (prf_id),
  KEY prf_type (prf_type),
  KEY prf_name (prf_name),
  KEY dom_num (dom_num)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table acces_rights
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE acces_rights (
  dom_num int(2) unsigned NOT NULL DEFAULT '0',
  usr_prf_num int(2) unsigned NOT NULL DEFAULT '0',
  res_prf_num int(2) unsigned NOT NULL DEFAULT '0',
  dom_rights int(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (dom_num,usr_prf_num,res_prf_num),
  KEY dom_num (dom_num),
  KEY usr_prf_num (usr_prf_num),
  KEY res_prf_num (res_prf_num)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table actes
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE actes (
  id_acte int(8) unsigned NOT NULL AUTO_INCREMENT,
  date_acte date NOT NULL DEFAULT '0000-00-00',
  numero varchar(25) NOT NULL DEFAULT '',
  type_acte int(3) unsigned NOT NULL DEFAULT '0',
  statut int(3) unsigned NOT NULL DEFAULT '0',
  date_paiement date NOT NULL DEFAULT '0000-00-00',
  num_paiement varchar(255) NOT NULL DEFAULT '',
  num_entite int(5) unsigned NOT NULL DEFAULT '0',
  num_fournisseur int(5) unsigned NOT NULL DEFAULT '0',
  num_contact_livr int(8) unsigned NOT NULL DEFAULT '0',
  num_contact_fact int(8) unsigned NOT NULL DEFAULT '0',
  num_exercice int(8) unsigned NOT NULL DEFAULT '0',
  commentaires text NOT NULL,
  reference varchar(255) NOT NULL DEFAULT '',
  index_acte text NOT NULL,
  devise varchar(25) NOT NULL DEFAULT '',
  commentaires_i text NOT NULL,
  date_valid date NOT NULL DEFAULT '0000-00-00',
  date_ech date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (id_acte),
  KEY num_fournisseur (num_fournisseur),
  KEY date (date_acte),
  KEY num_entite (num_entite),
  KEY numero (numero)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table admin_session
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE admin_session (
  userid int(10) unsigned NOT NULL DEFAULT '0',
  session blob,
  PRIMARY KEY (userid)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table analysis
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE analysis (
  analysis_bulletin int(8) unsigned NOT NULL DEFAULT '0',
  analysis_notice int(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (analysis_bulletin,analysis_notice),
  KEY analysis_notice (analysis_notice)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table arch_emplacement
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE arch_emplacement (
  archempla_id int(8) unsigned NOT NULL AUTO_INCREMENT,
  archempla_libelle varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (archempla_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table arch_statut
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE arch_statut (
  archstatut_id int(8) NOT NULL AUTO_INCREMENT,
  archstatut_gestion_libelle varchar(255) NOT NULL DEFAULT '',
  archstatut_opac_libelle varchar(255) NOT NULL,
  archstatut_visible_opac tinyint(1) unsigned NOT NULL DEFAULT '1',
  archstatut_visible_opac_abon tinyint(1) unsigned NOT NULL DEFAULT '1',
  archstatut_visible_gestion tinyint(1) unsigned NOT NULL DEFAULT '1',
  archstatut_class_html varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (archstatut_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table arch_type
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE arch_type (
  archtype_id int(8) unsigned NOT NULL AUTO_INCREMENT,
  archtype_libelle varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (archtype_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table audit
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE audit (
  type_obj int(1) NOT NULL DEFAULT '0',
  object_id int(10) unsigned NOT NULL DEFAULT '0',
  user_id int(8) unsigned NOT NULL DEFAULT '0',
  user_name varchar(20) NOT NULL DEFAULT '',
  type_modif int(1) NOT NULL DEFAULT '1',
  quand timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY type_obj (type_obj),
  KEY object_id (object_id),
  KEY user_id (user_id),
  KEY type_modif (type_modif)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table aut_link
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE aut_link (
  aut_link_from int(2) NOT NULL DEFAULT '0',
  aut_link_from_num int(11) NOT NULL DEFAULT '0',
  aut_link_to int(2) NOT NULL DEFAULT '0',
  aut_link_to_num int(11) NOT NULL DEFAULT '0',
  aut_link_type int(2) NOT NULL DEFAULT '0',
  aut_link_reciproc int(1) NOT NULL DEFAULT '0',
  aut_link_comment varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (aut_link_from,aut_link_from_num,aut_link_to,aut_link_to_num,aut_link_type)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table authors
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE authors (
  author_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  author_type enum('70','71','72') NOT NULL DEFAULT '70',
  author_name varchar(255) NOT NULL DEFAULT '',
  author_rejete varchar(255) NOT NULL DEFAULT '',
  author_date varchar(255) NOT NULL DEFAULT '',
  author_see mediumint(8) unsigned NOT NULL DEFAULT '0',
  author_web varchar(255) NOT NULL DEFAULT '',
  index_author text,
  author_comment text,
  author_lieu varchar(255) NOT NULL DEFAULT '',
  author_ville varchar(255) NOT NULL DEFAULT '',
  author_pays varchar(255) NOT NULL DEFAULT '',
  author_subdivision varchar(255) NOT NULL DEFAULT '',
  author_numero varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (author_id),
  KEY author_see (author_see),
  KEY author_name (author_name),
  KEY author_rejete (author_rejete)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table avis
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE avis (
  id_avis mediumint(8) NOT NULL AUTO_INCREMENT,
  num_empr mediumint(8) NOT NULL DEFAULT '0',
  num_notice mediumint(8) NOT NULL DEFAULT '0',
  note int(3) DEFAULT NULL,
  sujet text,
  commentaire text,
  dateajout timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  valide int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id_avis),
  KEY avis_num_notice (num_notice),
  KEY avis_num_empr (num_empr),
  KEY avis_note (note)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table bannette_abon
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE bannette_abon (
  num_bannette int(9) unsigned NOT NULL DEFAULT '0',
  num_empr int(9) unsigned NOT NULL DEFAULT '0',
  actif int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (num_bannette,num_empr)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table bannette_contenu
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE bannette_contenu (
  num_bannette int(9) unsigned NOT NULL DEFAULT '0',
  num_notice int(9) unsigned NOT NULL DEFAULT '0',
  date_ajout timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (num_bannette,num_notice),
  KEY date_ajout (date_ajout),
  KEY i_num_notice (num_notice)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table bannette_equation
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE bannette_equation (
  num_bannette int(9) unsigned NOT NULL DEFAULT '0',
  num_equation int(9) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (num_bannette,num_equation)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table bannette_exports
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE bannette_exports (
  num_bannette int(11) unsigned NOT NULL DEFAULT '0',
  export_format int(3) NOT NULL DEFAULT '0',
  export_data longblob NOT NULL,
  export_nomfichier varchar(255) DEFAULT '',
  PRIMARY KEY (num_bannette,export_format)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table bannettes
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE bannettes (
  id_bannette int(9) unsigned NOT NULL AUTO_INCREMENT,
  num_classement int(8) unsigned NOT NULL DEFAULT '1',
  nom_bannette varchar(255) NOT NULL DEFAULT '',
  comment_gestion varchar(255) NOT NULL DEFAULT '',
  comment_public varchar(255) NOT NULL DEFAULT '',
  entete_mail text NOT NULL,
  date_last_remplissage datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  date_last_envoi datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  proprio_bannette int(9) unsigned NOT NULL DEFAULT '0',
  bannette_auto int(1) unsigned NOT NULL DEFAULT '0',
  periodicite int(3) unsigned NOT NULL DEFAULT '7',
  diffusion_email int(1) unsigned NOT NULL DEFAULT '0',
  categorie_lecteurs int(8) unsigned NOT NULL DEFAULT '0',
  nb_notices_diff int(4) unsigned NOT NULL DEFAULT '0',
  num_panier int(8) unsigned NOT NULL DEFAULT '0',
  limite_type char(1) NOT NULL DEFAULT '',
  limite_nombre int(6) NOT NULL DEFAULT '0',
  update_type char(1) NOT NULL DEFAULT 'C',
  typeexport varchar(20) NOT NULL DEFAULT '',
  prefixe_fichier varchar(50) NOT NULL DEFAULT '',
  param_export blob NOT NULL,
  piedpage_mail text NOT NULL,
  notice_tpl int(10) unsigned NOT NULL DEFAULT '0',
  group_pperso int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id_bannette)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table budgets
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE budgets (
  id_budget int(8) unsigned NOT NULL AUTO_INCREMENT,
  num_entite int(5) unsigned NOT NULL DEFAULT '0',
  num_exercice int(8) unsigned NOT NULL DEFAULT '0',
  libelle varchar(255) NOT NULL DEFAULT '',
  commentaires text,
  montant_global float(8,2) unsigned NOT NULL DEFAULT '0.00',
  seuil_alerte int(3) unsigned NOT NULL DEFAULT '100',
  statut int(3) unsigned NOT NULL DEFAULT '0',
  type_budget int(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id_budget)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table bulletins
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE bulletins (
  bulletin_id int(8) unsigned NOT NULL AUTO_INCREMENT,
  bulletin_numero varchar(255) NOT NULL DEFAULT '',
  bulletin_notice int(8) NOT NULL DEFAULT '0',
  mention_date varchar(50) NOT NULL DEFAULT '',
  date_date date NOT NULL DEFAULT '0000-00-00',
  bulletin_titre text,
  index_titre text,
  bulletin_cb varchar(30) DEFAULT NULL,
  num_notice int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (bulletin_id),
  KEY bulletin_numero (bulletin_numero),
  KEY bulletin_notice (bulletin_notice),
  KEY date_date (date_date),
  KEY i_num_notice (num_notice)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table cache_amendes
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE cache_amendes (
  id_empr int(10) unsigned NOT NULL DEFAULT '0',
  cache_date date NOT NULL DEFAULT '0000-00-00',
  data_amendes blob NOT NULL,
  KEY id_empr (id_empr)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table caddie
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE caddie (
  idcaddie int(8) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(255) DEFAULT NULL,
  type varchar(20) NOT NULL DEFAULT 'NOTI',
  comment varchar(255) DEFAULT NULL,
  autorisations mediumtext,
  PRIMARY KEY (idcaddie),
  KEY caddie_type (type)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table caddie_content
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE caddie_content (
  caddie_id int(8) unsigned NOT NULL DEFAULT '0',
  object_id int(10) unsigned NOT NULL DEFAULT '0',
  content varchar(100) NOT NULL DEFAULT '',
  blob_type varchar(100) DEFAULT '',
  flag varchar(10) DEFAULT NULL,
  PRIMARY KEY (caddie_id,object_id,content),
  KEY object_id (object_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table caddie_procs
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE caddie_procs (
  idproc smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  type varchar(20) NOT NULL DEFAULT 'SELECT',
  name varchar(255) NOT NULL DEFAULT '',
  requete blob NOT NULL,
  comment tinytext NOT NULL,
  autorisations mediumtext,
  parameters text,
  PRIMARY KEY (idproc)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table categories
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE categories (
  num_thesaurus int(3) unsigned NOT NULL DEFAULT '1',
  num_noeud int(9) unsigned NOT NULL DEFAULT '0',
  langue varchar(5) NOT NULL DEFAULT 'fr_FR',
  libelle_categorie text NOT NULL,
  note_application text NOT NULL,
  comment_public text NOT NULL,
  comment_voir text NOT NULL,
  index_categorie text NOT NULL,
  PRIMARY KEY (num_noeud,langue),
  KEY categ_langue (langue),
  KEY libelle_categorie (libelle_categorie(5))
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table classements
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE classements (
  id_classement int(8) unsigned NOT NULL AUTO_INCREMENT,
  type_classement char(3) NOT NULL DEFAULT 'BAN',
  nom_classement varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (id_classement)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table collections
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE collections (
  collection_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  collection_name varchar(255) NOT NULL DEFAULT '',
  collection_parent mediumint(8) unsigned NOT NULL DEFAULT '0',
  collection_issn varchar(12) NOT NULL DEFAULT '',
  index_coll text,
  collection_web text NOT NULL,
  PRIMARY KEY (collection_id),
  KEY collection_name (collection_name),
  KEY collection_parent (collection_parent)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table collections_state
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE collections_state (
  collstate_id int(8) NOT NULL AUTO_INCREMENT,
  id_serial mediumint(8) unsigned NOT NULL DEFAULT '0',
  location_id smallint(5) unsigned NOT NULL DEFAULT '0',
  state_collections text NOT NULL,
  collstate_emplacement int(8) unsigned NOT NULL DEFAULT '0',
  collstate_type int(8) unsigned NOT NULL DEFAULT '0',
  collstate_origine varchar(255) NOT NULL DEFAULT '',
  collstate_cote varchar(255) NOT NULL DEFAULT '',
  collstate_archive varchar(255) NOT NULL DEFAULT '',
  collstate_statut int(8) unsigned NOT NULL DEFAULT '0',
  collstate_lacune text NOT NULL,
  collstate_note text NOT NULL,
  PRIMARY KEY (collstate_id),
  KEY i_colls_arc (collstate_archive),
  KEY i_colls_empl (collstate_emplacement),
  KEY i_colls_type (collstate_type),
  KEY i_colls_orig (collstate_origine),
  KEY i_colls_cote (collstate_cote),
  KEY i_colls_stat (collstate_statut),
  KEY i_colls_serial (id_serial),
  KEY i_colls_loc (location_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table collstate_custom
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE collstate_custom (
  idchamp int(10) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL DEFAULT '',
  titre varchar(255) NOT NULL DEFAULT '',
  type varchar(10) NOT NULL DEFAULT 'text',
  datatype varchar(10) NOT NULL DEFAULT '',
  options text,
  multiple int(11) NOT NULL DEFAULT '0',
  obligatoire int(11) NOT NULL DEFAULT '0',
  ordre int(11) NOT NULL DEFAULT '0',
  search int(11) NOT NULL DEFAULT '0',
  export int(1) unsigned NOT NULL DEFAULT '0',
  exclusion_obligatoire int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (idchamp)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table collstate_custom_lists
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE collstate_custom_lists (
  collstate_custom_champ int(10) unsigned NOT NULL DEFAULT '0',
  collstate_custom_list_value varchar(255) NOT NULL DEFAULT '',
  collstate_custom_list_lib varchar(255) NOT NULL DEFAULT '',
  ordre int(11) NOT NULL DEFAULT '0',
  KEY collstate_custom_champ (collstate_custom_champ),
  KEY i_ccl_lv (collstate_custom_list_value)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table collstate_custom_values
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE collstate_custom_values (
  collstate_custom_champ int(10) unsigned NOT NULL DEFAULT '0',
  collstate_custom_origine int(10) unsigned NOT NULL DEFAULT '0',
  collstate_custom_small_text varchar(255) DEFAULT NULL,
  collstate_custom_text text,
  collstate_custom_integer int(11) DEFAULT NULL,
  collstate_custom_date date DEFAULT NULL,
  collstate_custom_float float DEFAULT NULL,
  KEY collstate_custom_champ (collstate_custom_champ),
  KEY collstate_custom_origine (collstate_custom_origine),
  KEY i_ccv_st (collstate_custom_small_text),
  KEY i_ccv_t (collstate_custom_text(255)),
  KEY i_ccv_i (collstate_custom_integer),
  KEY i_ccv_d (collstate_custom_date),
  KEY i_ccv_f (collstate_custom_float)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table comptes
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE comptes (
  id_compte int(8) unsigned NOT NULL AUTO_INCREMENT,
  libelle varchar(255) NOT NULL DEFAULT '',
  type_compte_id int(10) unsigned NOT NULL DEFAULT '0',
  solde decimal(16,2) DEFAULT '0.00',
  prepay_mnt decimal(16,2) NOT NULL DEFAULT '0.00',
  proprio_id int(10) unsigned NOT NULL DEFAULT '0',
  droits text NOT NULL,
  PRIMARY KEY (id_compte),
  KEY i_cpt_proprio_id (proprio_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table connectors
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE connectors (
  connector_id varchar(20) NOT NULL DEFAULT '',
  parameters text NOT NULL,
  repository int(11) NOT NULL DEFAULT '0',
  timeout int(11) NOT NULL DEFAULT '5',
  retry int(11) NOT NULL DEFAULT '3',
  ttl int(11) NOT NULL DEFAULT '1440',
  PRIMARY KEY (connector_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table connectors_categ
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE connectors_categ (
  connectors_categ_id smallint(5) NOT NULL AUTO_INCREMENT,
  connectors_categ_name varchar(64) NOT NULL DEFAULT '',
  opac_expanded smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (connectors_categ_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table connectors_categ_sources
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE connectors_categ_sources (
  num_categ smallint(6) NOT NULL DEFAULT '0',
  num_source smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (num_categ,num_source),
  KEY i_num_source (num_source)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table connectors_out
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE connectors_out (
  connectors_out_id int(11) NOT NULL AUTO_INCREMENT,
  connectors_out_config longblob NOT NULL,
  PRIMARY KEY (connectors_out_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table connectors_out_oai_tokens
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE connectors_out_oai_tokens (
  connectors_out_oai_token_token varchar(32) NOT NULL,
  connectors_out_oai_token_environnement text NOT NULL,
  connectors_out_oai_token_expirationdate datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (connectors_out_oai_token_token)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table connectors_out_setcache_values
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE connectors_out_setcache_values (
  connectors_out_setcache_values_cachenum int(11) NOT NULL DEFAULT '0',
  connectors_out_setcache_values_value int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (connectors_out_setcache_values_cachenum,connectors_out_setcache_values_value)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table connectors_out_setcaches
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE connectors_out_setcaches (
  connectors_out_setcache_id int(11) NOT NULL AUTO_INCREMENT,
  connectors_out_setcache_setnum int(11) NOT NULL DEFAULT '0',
  connectors_out_setcache_lifeduration int(4) NOT NULL DEFAULT '0',
  connectors_out_setcache_lifeduration_unit enum('seconds','minutes','hours','days','weeks','months') NOT NULL DEFAULT 'seconds',
  connectors_out_setcache_lastupdatedate datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (connectors_out_setcache_id),
  UNIQUE KEY connectors_out_setcache_setnum (connectors_out_setcache_setnum)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table connectors_out_setcateg_sets
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE connectors_out_setcateg_sets (
  connectors_out_setcategset_setnum int(11) NOT NULL,
  connectors_out_setcategset_categnum int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (connectors_out_setcategset_setnum,connectors_out_setcategset_categnum)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table connectors_out_setcategs
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE connectors_out_setcategs (
  connectors_out_setcateg_id int(11) NOT NULL AUTO_INCREMENT,
  connectors_out_setcateg_name varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (connectors_out_setcateg_id),
  UNIQUE KEY connectors_out_setcateg_name (connectors_out_setcateg_name)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table connectors_out_sets
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE connectors_out_sets (
  connector_out_set_id int(11) NOT NULL AUTO_INCREMENT,
  connector_out_set_caption varchar(100) NOT NULL DEFAULT '',
  connector_out_set_type int(4) NOT NULL DEFAULT '0',
  connector_out_set_config longblob NOT NULL,
  PRIMARY KEY (connector_out_set_id),
  UNIQUE KEY connector_out_set_caption (connector_out_set_caption)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table connectors_out_sources
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE connectors_out_sources (
  connectors_out_source_id int(11) NOT NULL AUTO_INCREMENT,
  connectors_out_sources_connectornum int(11) NOT NULL DEFAULT '0',
  connectors_out_source_name varchar(100) NOT NULL DEFAULT '',
  connectors_out_source_comment varchar(200) NOT NULL DEFAULT '',
  connectors_out_source_config longblob NOT NULL,
  PRIMARY KEY (connectors_out_source_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table connectors_out_sources_esgroups
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE connectors_out_sources_esgroups (
  connectors_out_source_esgroup_sourcenum int(11) NOT NULL DEFAULT '0',
  connectors_out_source_esgroup_esgroupnum int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (connectors_out_source_esgroup_sourcenum,connectors_out_source_esgroup_esgroupnum)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table connectors_sources
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE connectors_sources (
  source_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  id_connector varchar(20) NOT NULL DEFAULT '',
  parameters mediumtext NOT NULL,
  comment varchar(255) NOT NULL DEFAULT '',
  name varchar(255) NOT NULL DEFAULT '',
  repository int(11) NOT NULL DEFAULT '0',
  timeout int(11) NOT NULL DEFAULT '5',
  retry int(11) NOT NULL DEFAULT '3',
  ttl int(11) NOT NULL DEFAULT '1440',
  opac_allowed int(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (source_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table coordonnees
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE coordonnees (
  id_contact int(8) unsigned NOT NULL AUTO_INCREMENT,
  type_coord int(1) unsigned NOT NULL DEFAULT '0',
  num_entite int(5) unsigned NOT NULL DEFAULT '0',
  libelle varchar(255) NOT NULL DEFAULT '',
  contact varchar(255) NOT NULL DEFAULT '',
  adr1 varchar(255) NOT NULL DEFAULT '',
  adr2 varchar(255) NOT NULL DEFAULT '',
  cp varchar(15) NOT NULL DEFAULT '',
  ville varchar(100) NOT NULL DEFAULT '',
  etat varchar(100) NOT NULL DEFAULT '',
  pays varchar(100) NOT NULL DEFAULT '',
  tel1 varchar(100) NOT NULL DEFAULT '',
  tel2 varchar(100) NOT NULL DEFAULT '',
  fax varchar(100) NOT NULL DEFAULT '',
  email varchar(100) NOT NULL DEFAULT '',
  commentaires text,
  PRIMARY KEY (id_contact)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table demandes
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE demandes (
  id_demande int(10) unsigned NOT NULL AUTO_INCREMENT,
  num_demandeur mediumint(8) NOT NULL DEFAULT '0',
  theme_demande int(3) NOT NULL DEFAULT '0',
  type_demande int(3) NOT NULL DEFAULT '0',
  etat_demande int(3) NOT NULL DEFAULT '0',
  date_demande date NOT NULL DEFAULT '0000-00-00',
  date_prevue date NOT NULL DEFAULT '0000-00-00',
  deadline_demande date NOT NULL DEFAULT '0000-00-00',
  titre_demande varchar(255) NOT NULL DEFAULT '',
  sujet_demande text NOT NULL,
  progression mediumint(3) NOT NULL DEFAULT '0',
  num_user_cloture mediumint(3) NOT NULL DEFAULT '0',
  num_notice int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (id_demande),
  KEY i_num_demandeur (num_demandeur),
  KEY i_date_demande (date_demande),
  KEY i_deadline_demande (deadline_demande)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table demandes_actions
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE demandes_actions (
  id_action int(10) unsigned NOT NULL AUTO_INCREMENT,
  type_action int(3) NOT NULL DEFAULT '0',
  statut_action int(3) NOT NULL DEFAULT '0',
  sujet_action varchar(255) NOT NULL DEFAULT '',
  detail_action text NOT NULL,
  date_action date NOT NULL DEFAULT '0000-00-00',
  deadline_action date NOT NULL DEFAULT '0000-00-00',
  temps_passe float DEFAULT NULL,
  cout mediumint(3) NOT NULL DEFAULT '0',
  progression_action mediumint(3) NOT NULL DEFAULT '0',
  prive_action int(1) NOT NULL DEFAULT '0',
  num_demande int(10) NOT NULL DEFAULT '0',
  actions_num_user tinyint(4) unsigned NOT NULL DEFAULT '0',
  actions_type_user tinyint(4) unsigned NOT NULL DEFAULT '0',
  actions_read int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id_action),
  KEY i_date_action (date_action),
  KEY i_deadline_action (deadline_action),
  KEY i_num_demande (num_demande),
  KEY i_actions_user (actions_num_user,actions_type_user)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table demandes_notes
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE demandes_notes (
  id_note int(10) unsigned NOT NULL AUTO_INCREMENT,
  prive int(1) NOT NULL DEFAULT '0',
  rapport int(1) NOT NULL DEFAULT '0',
  contenu text NOT NULL,
  date_note date NOT NULL DEFAULT '0000-00-00',
  num_action int(10) NOT NULL DEFAULT '0',
  num_note_parent int(10) NOT NULL DEFAULT '0',
  notes_num_user tinyint(4) unsigned NOT NULL DEFAULT '0',
  notes_type_user tinyint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id_note),
  KEY i_date_note (date_note),
  KEY i_num_action (num_action),
  KEY i_num_note_parent (num_note_parent),
  KEY i_notes_user (notes_num_user,notes_type_user)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table demandes_theme
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE demandes_theme (
  id_theme int(10) unsigned NOT NULL AUTO_INCREMENT,
  libelle_theme varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (id_theme)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table demandes_type
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE demandes_type (
  id_type int(10) unsigned NOT NULL AUTO_INCREMENT,
  libelle_type varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (id_type)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table demandes_users
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE demandes_users (
  num_user int(10) NOT NULL DEFAULT '0',
  num_demande int(10) NOT NULL DEFAULT '0',
  date_creation date NOT NULL DEFAULT '0000-00-00',
  users_statut int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (num_user,num_demande)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table docs_codestat
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE docs_codestat (
  idcode smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  codestat_libelle varchar(255) DEFAULT NULL,
  statisdoc_codage_import char(2) NOT NULL DEFAULT '',
  statisdoc_owner mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (idcode),
  KEY statisdoc_owner (statisdoc_owner)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table docs_location
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE docs_location (
  idlocation smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  location_libelle varchar(255) DEFAULT NULL,
  locdoc_codage_import varchar(255) NOT NULL DEFAULT '',
  locdoc_owner mediumint(8) unsigned NOT NULL DEFAULT '0',
  location_pic varchar(255) NOT NULL DEFAULT '',
  location_visible_opac tinyint(1) NOT NULL DEFAULT '1',
  name varchar(255) NOT NULL DEFAULT '',
  adr1 varchar(255) NOT NULL DEFAULT '',
  adr2 varchar(255) NOT NULL DEFAULT '',
  cp varchar(15) NOT NULL DEFAULT '',
  town varchar(100) NOT NULL DEFAULT '',
  state varchar(100) NOT NULL DEFAULT '',
  country varchar(100) NOT NULL DEFAULT '',
  phone varchar(100) NOT NULL DEFAULT '',
  email varchar(100) NOT NULL DEFAULT '',
  website varchar(100) NOT NULL DEFAULT '',
  logo varchar(255) NOT NULL DEFAULT '',
  commentaire text NOT NULL,
  transfert_ordre smallint(2) unsigned NOT NULL DEFAULT '9999',
  transfert_statut_defaut smallint(5) unsigned NOT NULL DEFAULT '0',
  num_infopage int(6) unsigned NOT NULL DEFAULT '0',
  css_style varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (idlocation),
  KEY locdoc_owner (locdoc_owner)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table docs_section
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE docs_section (
  idsection smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  section_libelle varchar(255) DEFAULT NULL,
  sdoc_codage_import varchar(255) NOT NULL DEFAULT '',
  sdoc_owner mediumint(8) unsigned NOT NULL DEFAULT '0',
  section_pic varchar(255) NOT NULL DEFAULT '',
  section_visible_opac tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (idsection),
  KEY sdoc_owner (sdoc_owner)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table docs_statut
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE docs_statut (
  idstatut smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  statut_libelle varchar(255) DEFAULT NULL,
  pret_flag tinyint(4) NOT NULL DEFAULT '1',
  statusdoc_codage_import char(2) NOT NULL DEFAULT '',
  statusdoc_owner mediumint(8) unsigned NOT NULL DEFAULT '0',
  transfert_flag tinyint(4) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (idstatut),
  KEY statusdoc_owner (statusdoc_owner)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table docs_type
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE docs_type (
  idtyp_doc int(5) unsigned NOT NULL AUTO_INCREMENT,
  tdoc_libelle varchar(255) DEFAULT NULL,
  duree_pret smallint(6) NOT NULL DEFAULT '31',
  duree_resa int(6) unsigned NOT NULL DEFAULT '15',
  tdoc_owner mediumint(8) unsigned NOT NULL DEFAULT '0',
  tdoc_codage_import varchar(255) NOT NULL DEFAULT '',
  tarif_pret decimal(16,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (idtyp_doc)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table docsloc_section
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE docsloc_section (
  num_section int(5) unsigned NOT NULL DEFAULT '0',
  num_location int(5) unsigned NOT NULL DEFAULT '0',
  num_pclass int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (num_section,num_location)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table empr
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE empr (
  id_empr int(10) unsigned NOT NULL AUTO_INCREMENT,
  empr_cb varchar(255) DEFAULT NULL,
  empr_nom varchar(255) NOT NULL DEFAULT '',
  empr_prenom varchar(255) NOT NULL DEFAULT '',
  empr_adr1 varchar(255) NOT NULL DEFAULT '',
  empr_adr2 varchar(255) NOT NULL DEFAULT '',
  empr_cp varchar(10) NOT NULL DEFAULT '',
  empr_ville varchar(255) NOT NULL DEFAULT '',
  empr_pays varchar(255) NOT NULL DEFAULT '',
  empr_mail varchar(255) NOT NULL DEFAULT '',
  empr_tel1 varchar(255) NOT NULL DEFAULT '',
  empr_tel2 varchar(255) NOT NULL DEFAULT '',
  empr_prof varchar(255) NOT NULL DEFAULT '',
  empr_year int(4) unsigned NOT NULL DEFAULT '0',
  empr_categ smallint(5) unsigned NOT NULL DEFAULT '0',
  empr_codestat smallint(5) unsigned NOT NULL DEFAULT '0',
  empr_creation datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  empr_modif date NOT NULL DEFAULT '0000-00-00',
  empr_sexe tinyint(3) unsigned NOT NULL DEFAULT '0',
  empr_login varchar(255) NOT NULL DEFAULT '',
  empr_password varchar(255) NOT NULL DEFAULT '',
  empr_date_adhesion date DEFAULT NULL,
  empr_date_expiration date DEFAULT NULL,
  empr_msg tinytext,
  empr_lang varchar(10) NOT NULL DEFAULT 'fr_FR',
  empr_ldap tinyint(1) unsigned DEFAULT '0',
  type_abt int(1) NOT NULL DEFAULT '0',
  last_loan_date date DEFAULT NULL,
  empr_location int(6) unsigned NOT NULL DEFAULT '1',
  date_fin_blocage date NOT NULL DEFAULT '0000-00-00',
  total_loans bigint(20) unsigned NOT NULL DEFAULT '0',
  empr_statut bigint(20) unsigned NOT NULL DEFAULT '1',
  cle_validation varchar(255) NOT NULL DEFAULT '',
  empr_sms int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id_empr),
  UNIQUE KEY empr_cb (empr_cb),
  KEY empr_nom (empr_nom),
  KEY empr_date_adhesion (empr_date_adhesion),
  KEY empr_date_expiration (empr_date_expiration),
  KEY i_empr_categ (empr_categ),
  KEY i_empr_codestat (empr_codestat),
  KEY i_empr_location (empr_location),
  KEY i_empr_statut (empr_statut),
  KEY i_empr_typabt (type_abt)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table empr_caddie
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE empr_caddie (
  idemprcaddie int(8) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(100) DEFAULT NULL,
  comment varchar(255) DEFAULT NULL,
  autorisations mediumtext,
  PRIMARY KEY (idemprcaddie)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table empr_caddie_content
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE empr_caddie_content (
  empr_caddie_id int(8) unsigned NOT NULL DEFAULT '0',
  object_id int(10) unsigned NOT NULL DEFAULT '0',
  flag varchar(10) DEFAULT NULL,
  PRIMARY KEY (empr_caddie_id,object_id),
  KEY object_id (object_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table empr_caddie_procs
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE empr_caddie_procs (
  idproc smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  type varchar(20) NOT NULL DEFAULT 'SELECT',
  name varchar(255) NOT NULL DEFAULT '',
  requete blob NOT NULL,
  comment tinytext NOT NULL,
  autorisations mediumtext,
  parameters text,
  PRIMARY KEY (idproc)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table empr_categ
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE empr_categ (
  id_categ_empr smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  libelle varchar(255) NOT NULL DEFAULT '',
  duree_adhesion int(10) unsigned DEFAULT '365',
  tarif_abt decimal(16,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (id_categ_empr)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table empr_codestat
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE empr_codestat (
  idcode smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  libelle varchar(50) NOT NULL DEFAULT 'DEFAULT',
  PRIMARY KEY (idcode)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table empr_custom
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE empr_custom (
  idchamp int(10) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL DEFAULT '',
  titre varchar(255) DEFAULT NULL,
  type varchar(10) NOT NULL DEFAULT 'text',
  datatype varchar(10) NOT NULL DEFAULT '',
  options text,
  multiple int(11) NOT NULL DEFAULT '0',
  obligatoire int(11) NOT NULL DEFAULT '0',
  ordre int(11) DEFAULT NULL,
  search int(1) unsigned NOT NULL DEFAULT '0',
  export int(1) unsigned NOT NULL DEFAULT '0',
  exclusion_obligatoire int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (idchamp)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table empr_custom_lists
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE empr_custom_lists (
  empr_custom_champ int(10) unsigned NOT NULL DEFAULT '0',
  empr_custom_list_value varchar(255) DEFAULT NULL,
  empr_custom_list_lib varchar(255) DEFAULT NULL,
  ordre int(11) DEFAULT NULL,
  KEY empr_custom_champ (empr_custom_champ),
  KEY i_ecl_lv (empr_custom_list_value)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table empr_custom_values
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE empr_custom_values (
  empr_custom_champ int(10) unsigned NOT NULL DEFAULT '0',
  empr_custom_origine int(10) unsigned NOT NULL DEFAULT '0',
  empr_custom_small_text varchar(255) DEFAULT NULL,
  empr_custom_text text,
  empr_custom_integer int(11) DEFAULT NULL,
  empr_custom_date date DEFAULT NULL,
  empr_custom_float float DEFAULT NULL,
  KEY empr_custom_champ (empr_custom_champ),
  KEY empr_custom_origine (empr_custom_origine),
  KEY i_ecv_st (empr_custom_small_text),
  KEY i_ecv_t (empr_custom_text(255)),
  KEY i_ecv_i (empr_custom_integer),
  KEY i_ecv_d (empr_custom_date),
  KEY i_ecv_f (empr_custom_float)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table empr_groupe
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE empr_groupe (
  empr_id int(6) unsigned NOT NULL DEFAULT '0',
  groupe_id int(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (empr_id,groupe_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table empr_statut
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE empr_statut (
  idstatut smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  statut_libelle varchar(255) NOT NULL DEFAULT '',
  allow_loan tinyint(4) NOT NULL DEFAULT '1',
  allow_loan_hist tinyint(4) unsigned NOT NULL DEFAULT '0',
  allow_book tinyint(4) NOT NULL DEFAULT '1',
  allow_opac tinyint(4) NOT NULL DEFAULT '1',
  allow_dsi tinyint(4) NOT NULL DEFAULT '1',
  allow_dsi_priv tinyint(4) NOT NULL DEFAULT '1',
  allow_sugg tinyint(4) NOT NULL DEFAULT '1',
  allow_dema tinyint(4) unsigned NOT NULL DEFAULT '1',
  allow_prol tinyint(4) NOT NULL DEFAULT '1',
  allow_avis tinyint(4) unsigned NOT NULL DEFAULT '1',
  allow_tag tinyint(4) unsigned NOT NULL DEFAULT '1',
  allow_pwd tinyint(4) unsigned NOT NULL DEFAULT '1',
  allow_liste_lecture tinyint(4) unsigned NOT NULL DEFAULT '0',
  allow_self_checkout tinyint(4) unsigned NOT NULL DEFAULT '0',
  allow_self_checkin tinyint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (idstatut)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table empty_words_calculs
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE empty_words_calculs (
  id_calcul int(9) unsigned NOT NULL AUTO_INCREMENT,
  date_calcul date NOT NULL DEFAULT '0000-00-00',
  php_empty_words text NOT NULL,
  nb_notices_calcul mediumint(8) unsigned NOT NULL DEFAULT '0',
  archive_calcul tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id_calcul)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table entites
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE entites (
  id_entite int(5) unsigned NOT NULL AUTO_INCREMENT,
  type_entite int(3) unsigned NOT NULL DEFAULT '0',
  num_bibli int(5) unsigned NOT NULL DEFAULT '0',
  raison_sociale varchar(255) NOT NULL DEFAULT '',
  commentaires text,
  siret varchar(255) NOT NULL DEFAULT '',
  naf varchar(255) NOT NULL DEFAULT '',
  rcs varchar(255) NOT NULL DEFAULT '',
  tva varchar(255) NOT NULL DEFAULT '',
  num_cp_client varchar(255) NOT NULL DEFAULT '',
  num_cp_compta varchar(255) NOT NULL DEFAULT '',
  site_web varchar(255) NOT NULL DEFAULT '',
  logo varchar(255) NOT NULL DEFAULT '',
  autorisations mediumtext NOT NULL,
  num_frais int(8) unsigned NOT NULL DEFAULT '0',
  num_paiement int(8) unsigned NOT NULL DEFAULT '0',
  index_entite text NOT NULL,
  PRIMARY KEY (id_entite),
  KEY raison_sociale (raison_sociale)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table entrepots_localisations
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE entrepots_localisations (
  loc_id int(11) NOT NULL AUTO_INCREMENT,
  loc_code varchar(255) NOT NULL DEFAULT '',
  loc_libelle varchar(255) NOT NULL DEFAULT '',
  loc_visible tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (loc_id),
  UNIQUE KEY loc_code (loc_code)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table equations
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE equations (
  id_equation int(9) unsigned NOT NULL AUTO_INCREMENT,
  num_classement int(8) unsigned NOT NULL DEFAULT '1',
  nom_equation varchar(255) NOT NULL DEFAULT '',
  comment_equation varchar(255) NOT NULL DEFAULT '',
  requete blob NOT NULL,
  proprio_equation int(9) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id_equation)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table error_log
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE error_log (
  error_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  error_origin varchar(255) DEFAULT NULL,
  error_text text
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table es_cache
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE es_cache (
  escache_groupname varchar(100) NOT NULL DEFAULT '',
  escache_unique_id varchar(100) NOT NULL DEFAULT '',
  escache_value int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (escache_groupname,escache_unique_id,escache_value)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table es_cache_blob
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE es_cache_blob (
  es_cache_objectref varchar(100) NOT NULL DEFAULT '',
  es_cache_objecttype int(11) NOT NULL DEFAULT '0',
  es_cache_objectformat varchar(100) NOT NULL DEFAULT '',
  es_cache_owner varchar(100) NOT NULL DEFAULT '',
  es_cache_creationdate datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  es_cache_expirationdate datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  es_cache_content mediumblob NOT NULL,
  PRIMARY KEY (es_cache_objectref,es_cache_objecttype,es_cache_objectformat,es_cache_owner),
  KEY cache_index (es_cache_owner,es_cache_objectformat,es_cache_objecttype)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table es_cache_int
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE es_cache_int (
  es_cache_objectref varchar(100) NOT NULL DEFAULT '',
  es_cache_objecttype int(11) NOT NULL DEFAULT '0',
  es_cache_objectformat varchar(100) NOT NULL DEFAULT '',
  es_cache_owner varchar(100) NOT NULL DEFAULT '',
  es_cache_creationdate datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  es_cache_expirationdate datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  es_cache_content int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (es_cache_objectref,es_cache_objecttype,es_cache_objectformat,es_cache_owner),
  KEY cache_index (es_cache_owner,es_cache_objectformat,es_cache_objecttype)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table es_converted_cache
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE es_converted_cache (
  es_converted_cache_objecttype int(11) NOT NULL DEFAULT '0',
  es_converted_cache_objectref int(11) NOT NULL DEFAULT '0',
  es_converted_cache_format varchar(50) NOT NULL DEFAULT '',
  es_converted_cache_value text NOT NULL,
  es_converted_cache_bestbefore datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (es_converted_cache_objecttype,es_converted_cache_objectref,es_converted_cache_format)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table es_esgroup_esusers
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE es_esgroup_esusers (
  esgroupuser_groupnum int(11) NOT NULL DEFAULT '0',
  esgroupuser_usertype int(4) NOT NULL DEFAULT '0',
  esgroupuser_usernum int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (esgroupuser_usernum,esgroupuser_groupnum,esgroupuser_usertype)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table es_esgroups
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE es_esgroups (
  esgroup_id int(11) NOT NULL AUTO_INCREMENT,
  esgroup_name varchar(100) NOT NULL DEFAULT '',
  esgroup_fullname varchar(255) NOT NULL DEFAULT '',
  esgroup_pmbusernum int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (esgroup_id),
  UNIQUE KEY esgroup_name (esgroup_name)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table es_esusers
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE es_esusers (
  esuser_id int(11) NOT NULL AUTO_INCREMENT,
  esuser_username varchar(100) NOT NULL DEFAULT '',
  esuser_password varchar(100) NOT NULL DEFAULT '',
  esuser_fullname varchar(255) NOT NULL DEFAULT '',
  esuser_groupnum int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (esuser_id),
  UNIQUE KEY esuser_username (esuser_username)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table es_methods
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE es_methods (
  id_method int(10) unsigned NOT NULL AUTO_INCREMENT,
  groupe varchar(255) NOT NULL DEFAULT '',
  method varchar(255) NOT NULL DEFAULT '',
  available smallint(5) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (id_method)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table es_methods_users
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE es_methods_users (
  num_method int(10) unsigned NOT NULL DEFAULT '0',
  num_user int(10) unsigned NOT NULL DEFAULT '0',
  anonymous smallint(6) DEFAULT '0',
  PRIMARY KEY (num_method,num_user)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table es_searchcache
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE es_searchcache (
  es_searchcache_searchid varchar(100) NOT NULL DEFAULT '',
  es_searchcache_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  es_searchcache_serializedsearch text NOT NULL,
  PRIMARY KEY (es_searchcache_searchid)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table es_searchsessions
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE es_searchsessions (
  es_searchsession_id varchar(100) NOT NULL DEFAULT '',
  es_searchsession_searchnum varchar(100) NOT NULL DEFAULT '',
  es_searchsession_searchrealm varchar(100) NOT NULL DEFAULT '',
  es_searchsession_pmbuserid int(11) NOT NULL DEFAULT '-1',
  es_searchsession_opacemprid int(11) NOT NULL DEFAULT '-1',
  es_searchsession_lastseendate datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (es_searchsession_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table etagere
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE etagere (
  idetagere int(8) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(100) NOT NULL DEFAULT '',
  comment blob NOT NULL,
  validite int(1) unsigned NOT NULL DEFAULT '0',
  validite_date_deb date NOT NULL DEFAULT '0000-00-00',
  validite_date_fin date NOT NULL DEFAULT '0000-00-00',
  visible_accueil int(1) unsigned NOT NULL DEFAULT '1',
  autorisations mediumtext,
  PRIMARY KEY (idetagere)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table etagere_caddie
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE etagere_caddie (
  etagere_id int(8) unsigned NOT NULL DEFAULT '0',
  caddie_id int(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (etagere_id,caddie_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table exemplaires
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE exemplaires (
  expl_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  expl_cb varchar(50) NOT NULL DEFAULT '',
  expl_notice int(10) unsigned NOT NULL DEFAULT '0',
  expl_bulletin int(10) unsigned NOT NULL DEFAULT '0',
  expl_typdoc int(5) unsigned NOT NULL DEFAULT '0',
  expl_cote varchar(50) NOT NULL DEFAULT '',
  expl_section smallint(5) unsigned NOT NULL DEFAULT '0',
  expl_statut smallint(5) unsigned NOT NULL DEFAULT '0',
  expl_location smallint(5) unsigned NOT NULL DEFAULT '0',
  expl_codestat smallint(5) unsigned NOT NULL DEFAULT '0',
  expl_date_depot date NOT NULL DEFAULT '0000-00-00',
  expl_date_retour date NOT NULL DEFAULT '0000-00-00',
  expl_note tinytext NOT NULL,
  expl_prix varchar(255) NOT NULL DEFAULT '',
  expl_owner mediumint(8) unsigned NOT NULL DEFAULT '0',
  expl_lastempr int(10) unsigned NOT NULL DEFAULT '0',
  last_loan_date date DEFAULT NULL,
  create_date datetime NOT NULL DEFAULT '2005-01-01 00:00:00',
  update_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  type_antivol int(1) unsigned NOT NULL DEFAULT '0',
  transfert_location_origine smallint(5) unsigned NOT NULL DEFAULT '0',
  transfert_statut_origine smallint(5) unsigned NOT NULL DEFAULT '0',
  expl_comment varchar(255) NOT NULL DEFAULT '',
  expl_nbparts int(8) unsigned NOT NULL DEFAULT '1',
  expl_retloc smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (expl_id),
  UNIQUE KEY expl_cb (expl_cb),
  KEY expl_typdoc (expl_typdoc),
  KEY expl_cote (expl_cote),
  KEY expl_notice (expl_notice),
  KEY expl_codestat (expl_codestat),
  KEY expl_owner (expl_owner),
  KEY expl_bulletin (expl_bulletin),
  KEY i_expl_location (expl_location),
  KEY i_expl_section (expl_section),
  KEY i_expl_statut (expl_statut),
  KEY i_expl_lastempr (expl_lastempr)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table exemplaires_temp
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE exemplaires_temp (
  cb varchar(50) NOT NULL DEFAULT '',
  sess varchar(12) NOT NULL DEFAULT '',
  UNIQUE KEY cb (cb)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table exercices
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE exercices (
  id_exercice int(8) unsigned NOT NULL AUTO_INCREMENT,
  num_entite int(5) unsigned NOT NULL DEFAULT '0',
  libelle varchar(255) NOT NULL DEFAULT '',
  date_debut date NOT NULL DEFAULT '2006-01-01',
  date_fin date NOT NULL DEFAULT '2006-01-01',
  statut int(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (id_exercice)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table expl_custom
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE expl_custom (
  idchamp int(10) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL DEFAULT '',
  titre varchar(255) DEFAULT NULL,
  type varchar(10) NOT NULL DEFAULT 'text',
  datatype varchar(10) NOT NULL DEFAULT '',
  options text,
  multiple int(11) NOT NULL DEFAULT '0',
  obligatoire int(11) NOT NULL DEFAULT '0',
  ordre int(11) DEFAULT NULL,
  search int(1) unsigned NOT NULL DEFAULT '0',
  export int(1) unsigned NOT NULL DEFAULT '0',
  exclusion_obligatoire int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (idchamp)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table expl_custom_lists
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE expl_custom_lists (
  expl_custom_champ int(10) unsigned NOT NULL DEFAULT '0',
  expl_custom_list_value varchar(255) DEFAULT NULL,
  expl_custom_list_lib varchar(255) DEFAULT NULL,
  ordre int(11) DEFAULT NULL,
  KEY expl_custom_champ (expl_custom_champ),
  KEY i_excl_lv (expl_custom_list_value)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table expl_custom_values
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE expl_custom_values (
  expl_custom_champ int(10) unsigned NOT NULL DEFAULT '0',
  expl_custom_origine int(10) unsigned NOT NULL DEFAULT '0',
  expl_custom_small_text varchar(255) DEFAULT NULL,
  expl_custom_text text,
  expl_custom_integer int(11) DEFAULT NULL,
  expl_custom_date date DEFAULT NULL,
  expl_custom_float float DEFAULT NULL,
  KEY expl_custom_champ (expl_custom_champ),
  KEY expl_custom_origine (expl_custom_origine),
  KEY i_excv_st (expl_custom_small_text),
  KEY i_excv_t (expl_custom_text(255)),
  KEY i_excv_i (expl_custom_integer),
  KEY i_excv_d (expl_custom_date),
  KEY i_excv_f (expl_custom_float)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table explnum
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE explnum (
  explnum_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  explnum_notice mediumint(8) unsigned NOT NULL DEFAULT '0',
  explnum_bulletin int(8) unsigned NOT NULL DEFAULT '0',
  explnum_nom varchar(255) NOT NULL DEFAULT '',
  explnum_mimetype varchar(255) NOT NULL DEFAULT '',
  explnum_url text NOT NULL,
  explnum_data mediumblob,
  explnum_vignette mediumblob,
  explnum_extfichier varchar(20) DEFAULT '',
  explnum_nomfichier text,
  explnum_statut int(5) unsigned NOT NULL DEFAULT '0',
  explnum_index_sew text NOT NULL,
  explnum_index_wew text NOT NULL,
  explnum_repertoire int(8) NOT NULL DEFAULT '0',
  explnum_path text NOT NULL,
  PRIMARY KEY (explnum_id),
  KEY explnum_notice (explnum_notice),
  KEY explnum_bulletin (explnum_bulletin)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table explnum_doc
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE explnum_doc (
  id_explnum_doc int(8) unsigned NOT NULL AUTO_INCREMENT,
  explnum_doc_nomfichier text NOT NULL,
  explnum_doc_mimetype varchar(255) NOT NULL DEFAULT '',
  explnum_doc_data mediumblob NOT NULL,
  explnum_doc_extfichier varchar(20) NOT NULL DEFAULT '',
  explnum_doc_url text NOT NULL,
  PRIMARY KEY (id_explnum_doc)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table explnum_doc_actions
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE explnum_doc_actions (
  num_explnum_doc int(10) NOT NULL DEFAULT '0',
  num_action int(10) NOT NULL DEFAULT '0',
  prive int(1) NOT NULL DEFAULT '0',
  rapport int(1) NOT NULL DEFAULT '0',
  num_explnum int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (num_explnum_doc,num_action)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table explnum_doc_sugg
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE explnum_doc_sugg (
  num_explnum_doc int(10) NOT NULL DEFAULT '0',
  num_suggestion int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (num_explnum_doc,num_suggestion)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table explnum_location
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE explnum_location (
  num_explnum int(10) NOT NULL DEFAULT '0',
  num_location int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (num_explnum,num_location)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table external_count
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE external_count (
  rid bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  recid varchar(255) NOT NULL DEFAULT '',
  source_id int(11) NOT NULL,
  PRIMARY KEY (rid),
  KEY recid (recid)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table fiche
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE fiche (
  id_fiche int(10) unsigned NOT NULL AUTO_INCREMENT,
  infos_global text NOT NULL,
  index_infos_global text NOT NULL,
  PRIMARY KEY (id_fiche)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table frais
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE frais (
  id_frais int(8) unsigned NOT NULL AUTO_INCREMENT,
  libelle varchar(255) NOT NULL DEFAULT '',
  condition_frais text NOT NULL,
  montant float(8,2) unsigned NOT NULL DEFAULT '0.00',
  num_cp_compta varchar(255) NOT NULL DEFAULT '',
  num_tva_achat varchar(25) NOT NULL DEFAULT '0',
  PRIMARY KEY (id_frais)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table gestfic0_custom
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE gestfic0_custom (
  idchamp int(10) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL DEFAULT '',
  titre varchar(255) DEFAULT NULL,
  type varchar(10) NOT NULL DEFAULT 'text',
  datatype varchar(10) NOT NULL DEFAULT '',
  options text,
  multiple int(11) NOT NULL DEFAULT '0',
  obligatoire int(11) NOT NULL DEFAULT '0',
  ordre int(11) DEFAULT NULL,
  search int(1) unsigned NOT NULL DEFAULT '0',
  export int(1) unsigned NOT NULL DEFAULT '0',
  exclusion_obligatoire int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (idchamp)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table gestfic0_custom_lists
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE gestfic0_custom_lists (
  gestfic0_custom_champ int(10) unsigned NOT NULL DEFAULT '0',
  gestfic0_custom_list_value varchar(255) DEFAULT NULL,
  gestfic0_custom_list_lib varchar(255) DEFAULT NULL,
  ordre int(11) DEFAULT NULL,
  KEY gestfic0_custom_champ (gestfic0_custom_champ),
  KEY gestfic0_champ_list_value (gestfic0_custom_champ,gestfic0_custom_list_value)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table gestfic0_custom_values
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE gestfic0_custom_values (
  gestfic0_custom_champ int(10) unsigned NOT NULL DEFAULT '0',
  gestfic0_custom_origine int(10) unsigned NOT NULL DEFAULT '0',
  gestfic0_custom_small_text varchar(255) DEFAULT NULL,
  gestfic0_custom_text text,
  gestfic0_custom_integer int(11) DEFAULT NULL,
  gestfic0_custom_date date DEFAULT NULL,
  gestfic0_custom_float float DEFAULT NULL,
  KEY gestfic0_custom_champ (gestfic0_custom_champ),
  KEY gestfic0_custom_origine (gestfic0_custom_origine)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table grilles
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE grilles (
  grille_typdoc char(2) NOT NULL DEFAULT 'a',
  grille_niveau_biblio char(1) NOT NULL DEFAULT 'm',
  grille_localisation mediumint(8) NOT NULL DEFAULT '0',
  descr_format longtext,
  PRIMARY KEY (grille_typdoc,grille_niveau_biblio,grille_localisation)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table groupe
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE groupe (
  id_groupe int(6) unsigned NOT NULL AUTO_INCREMENT,
  libelle_groupe varchar(50) NOT NULL DEFAULT '',
  resp_groupe int(6) unsigned DEFAULT '0',
  lettre_rappel int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id_groupe),
  UNIQUE KEY libelle_groupe (libelle_groupe)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table import_marc
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE import_marc (
  id_import bigint(5) unsigned NOT NULL AUTO_INCREMENT,
  notice longblob NOT NULL,
  origine varchar(50) DEFAULT '',
  no_notice int(10) unsigned DEFAULT '0',
  PRIMARY KEY (id_import),
  KEY i_nonot_orig (no_notice,origine)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table indexint
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE indexint (
  indexint_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  indexint_name varchar(255) NOT NULL DEFAULT '',
  indexint_comment text NOT NULL,
  index_indexint text,
  num_pclass int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (indexint_id),
  UNIQUE KEY indexint_name (indexint_name,num_pclass)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table infopages
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE infopages (
  id_infopage int(10) unsigned NOT NULL AUTO_INCREMENT,
  content_infopage blob NOT NULL,
  title_infopage varchar(255) NOT NULL DEFAULT '',
  valid_infopage tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (id_infopage)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table lenders
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE lenders (
  idlender smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  lender_libelle varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (idlender)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table liens_actes
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE liens_actes (
  num_acte int(8) unsigned NOT NULL DEFAULT '0',
  num_acte_lie int(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (num_acte,num_acte_lie)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table lignes_actes
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE lignes_actes (
  id_ligne int(15) unsigned NOT NULL AUTO_INCREMENT,
  type_ligne int(3) unsigned NOT NULL DEFAULT '0',
  num_acte int(8) unsigned NOT NULL DEFAULT '0',
  lig_ref int(15) unsigned NOT NULL DEFAULT '0',
  num_acquisition int(12) unsigned NOT NULL DEFAULT '0',
  num_rubrique int(8) unsigned NOT NULL DEFAULT '0',
  num_produit int(8) unsigned NOT NULL DEFAULT '0',
  num_type int(8) unsigned NOT NULL DEFAULT '0',
  libelle text NOT NULL,
  code varchar(255) NOT NULL DEFAULT '',
  prix float(8,2) unsigned NOT NULL DEFAULT '0.00',
  tva float(8,2) unsigned NOT NULL DEFAULT '0.00',
  nb int(5) unsigned NOT NULL DEFAULT '1',
  date_ech date NOT NULL DEFAULT '0000-00-00',
  date_cre date NOT NULL DEFAULT '0000-00-00',
  statut int(3) unsigned NOT NULL DEFAULT '0',
  remise float(8,2) NOT NULL DEFAULT '0.00',
  index_ligne text NOT NULL,
  ligne_ordre smallint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id_ligne),
  KEY num_acte (num_acte)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table linked_mots
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE linked_mots (
  num_mot mediumint(8) unsigned NOT NULL DEFAULT '0',
  num_linked_mot mediumint(8) unsigned NOT NULL DEFAULT '0',
  type_lien tinyint(1) NOT NULL DEFAULT '1',
  ponderation float NOT NULL DEFAULT '1',
  PRIMARY KEY (num_mot,num_linked_mot,type_lien)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table log_expl_retard
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE log_expl_retard (
  id_log int(11) unsigned NOT NULL AUTO_INCREMENT,
  date_log timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  titre varchar(255) NOT NULL DEFAULT '',
  expl_id int(11) NOT NULL DEFAULT '0',
  expl_cb varchar(255) NOT NULL DEFAULT '',
  date_pret date NOT NULL DEFAULT '0000-00-00',
  date_retour date NOT NULL DEFAULT '0000-00-00',
  amende decimal(16,2) NOT NULL DEFAULT '0.00',
  num_log_retard int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (id_log)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table log_retard
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE log_retard (
  id_log int(11) unsigned NOT NULL AUTO_INCREMENT,
  date_log timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  niveau_reel int(1) NOT NULL DEFAULT '0',
  niveau_suppose int(1) NOT NULL DEFAULT '0',
  amende_totale decimal(16,2) NOT NULL DEFAULT '0.00',
  frais decimal(16,2) NOT NULL DEFAULT '0.00',
  idempr int(11) NOT NULL DEFAULT '0',
  log_printed int(1) unsigned NOT NULL DEFAULT '0',
  log_mail int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id_log)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table logopac
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE logopac (
  id_log int(8) unsigned NOT NULL AUTO_INCREMENT,
  date_log timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  url_demandee varchar(255) NOT NULL DEFAULT '',
  url_referente varchar(255) NOT NULL DEFAULT '',
  get_log blob NOT NULL,
  post_log blob NOT NULL,
  num_session varchar(255) NOT NULL DEFAULT '',
  server_log blob NOT NULL,
  empr_carac blob NOT NULL,
  empr_doc blob NOT NULL,
  empr_expl blob NOT NULL,
  nb_result blob NOT NULL,
  gen_stat blob NOT NULL,
  PRIMARY KEY (id_log)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table mots
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE mots (
  id_mot mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  mot varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (id_mot),
  UNIQUE KEY mot (mot)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table noeuds
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE noeuds (
  id_noeud int(9) unsigned NOT NULL AUTO_INCREMENT,
  autorite varchar(255) NOT NULL DEFAULT '',
  num_parent int(9) unsigned NOT NULL DEFAULT '0',
  num_renvoi_voir int(9) unsigned NOT NULL DEFAULT '0',
  visible char(1) NOT NULL DEFAULT '1',
  num_thesaurus int(3) unsigned NOT NULL DEFAULT '0',
  path text NOT NULL,
  PRIMARY KEY (id_noeud),
  KEY num_parent (num_parent),
  KEY num_thesaurus (num_thesaurus),
  KEY autorite (autorite),
  KEY key_path (path(1000))
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table notice_statut
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE notice_statut (
  id_notice_statut smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  gestion_libelle varchar(255) DEFAULT NULL,
  opac_libelle varchar(255) DEFAULT NULL,
  notice_visible_opac tinyint(1) NOT NULL DEFAULT '1',
  notice_visible_gestion tinyint(1) NOT NULL DEFAULT '1',
  expl_visible_opac tinyint(1) NOT NULL DEFAULT '1',
  class_html varchar(255) NOT NULL DEFAULT '',
  notice_visible_opac_abon tinyint(1) NOT NULL DEFAULT '0',
  expl_visible_opac_abon int(10) unsigned NOT NULL DEFAULT '0',
  explnum_visible_opac int(1) unsigned NOT NULL DEFAULT '1',
  explnum_visible_opac_abon int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id_notice_statut)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table notice_tpl
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE notice_tpl (
  notpl_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  notpl_name varchar(256) NOT NULL DEFAULT '',
  notpl_code text NOT NULL,
  notpl_comment varchar(256) NOT NULL DEFAULT '',
  notpl_id_test int(10) unsigned NOT NULL DEFAULT '0',
  notpl_show_opac int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (notpl_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table notice_tplcode
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE notice_tplcode (
  num_notpl int(10) unsigned NOT NULL DEFAULT '0',
  notplcode_localisation mediumint(8) NOT NULL DEFAULT '0',
  notplcode_typdoc char(2) NOT NULL DEFAULT 'a',
  notplcode_niveau_biblio char(1) NOT NULL DEFAULT 'm',
  notplcode_niveau_hierar char(1) NOT NULL DEFAULT '0',
  nottplcode_code text NOT NULL,
  PRIMARY KEY (num_notpl,notplcode_localisation,notplcode_typdoc,notplcode_niveau_biblio)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table notices
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE notices (
  notice_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  typdoc char(2) NOT NULL DEFAULT 'a',
  tit1 text,
  tit2 text,
  tit3 text,
  tit4 text,
  tparent_id mediumint(8) unsigned NOT NULL DEFAULT '0',
  tnvol varchar(100) NOT NULL DEFAULT '',
  ed1_id mediumint(8) unsigned NOT NULL DEFAULT '0',
  ed2_id mediumint(8) unsigned NOT NULL DEFAULT '0',
  coll_id mediumint(8) unsigned NOT NULL DEFAULT '0',
  subcoll_id mediumint(8) unsigned NOT NULL DEFAULT '0',
  year varchar(50) DEFAULT NULL,
  nocoll varchar(255) DEFAULT NULL,
  mention_edition varchar(255) NOT NULL DEFAULT '',
  code varchar(50) NOT NULL DEFAULT '',
  npages varchar(255) DEFAULT NULL,
  ill varchar(255) DEFAULT NULL,
  size varchar(255) DEFAULT NULL,
  accomp varchar(255) DEFAULT NULL,
  n_gen text NOT NULL,
  n_contenu text NOT NULL,
  n_resume text NOT NULL,
  lien text NOT NULL,
  eformat varchar(255) NOT NULL DEFAULT '',
  index_l text NOT NULL,
  indexint int(8) unsigned NOT NULL DEFAULT '0',
  index_serie tinytext,
  index_matieres text NOT NULL,
  niveau_biblio char(1) NOT NULL DEFAULT 'm',
  niveau_hierar char(1) NOT NULL DEFAULT '0',
  origine_catalogage int(8) unsigned NOT NULL DEFAULT '1',
  prix varchar(255) NOT NULL DEFAULT '',
  index_n_gen text,
  index_n_contenu text,
  index_n_resume text,
  index_sew text,
  index_wew text,
  statut int(5) NOT NULL DEFAULT '1',
  commentaire_gestion text NOT NULL,
  create_date datetime NOT NULL DEFAULT '2005-01-01 00:00:00',
  update_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  signature varchar(255) NOT NULL DEFAULT '',
  thumbnail_url mediumblob NOT NULL,
  date_parution date NOT NULL DEFAULT '0000-00-00',
  opac_visible_bulletinage tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (notice_id),
  KEY typdoc (typdoc),
  KEY tparent_id (tparent_id),
  KEY ed1_id (ed1_id),
  KEY ed2_id (ed2_id),
  KEY coll_id (coll_id),
  KEY subcoll_id (subcoll_id),
  KEY cb (code),
  KEY indexint (indexint),
  KEY sig_index (signature),
  KEY i_notice_n_biblio (niveau_biblio),
  KEY i_notice_n_hierar (niveau_hierar),
  KEY notice_eformat (eformat),
  KEY i_date_parution (date_parution)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table notices_categories
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE notices_categories (
  notcateg_notice int(9) unsigned NOT NULL DEFAULT '0',
  num_noeud int(9) unsigned NOT NULL DEFAULT '0',
  num_vedette int(3) unsigned NOT NULL DEFAULT '0',
  ordre_vedette int(3) unsigned NOT NULL DEFAULT '1',
  ordre_categorie smallint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (notcateg_notice,num_noeud,num_vedette),
  KEY num_noeud (num_noeud)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table notices_custom
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE notices_custom (
  idchamp int(10) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL DEFAULT '',
  titre varchar(255) DEFAULT NULL,
  type varchar(10) NOT NULL DEFAULT 'text',
  datatype varchar(10) NOT NULL DEFAULT '',
  options text,
  multiple int(11) NOT NULL DEFAULT '0',
  obligatoire int(11) NOT NULL DEFAULT '0',
  ordre int(11) DEFAULT NULL,
  search int(1) unsigned NOT NULL DEFAULT '0',
  export int(1) unsigned NOT NULL DEFAULT '0',
  exclusion_obligatoire int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (idchamp)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table notices_custom_lists
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE notices_custom_lists (
  notices_custom_champ int(10) unsigned NOT NULL DEFAULT '0',
  notices_custom_list_value varchar(255) DEFAULT NULL,
  notices_custom_list_lib varchar(255) DEFAULT NULL,
  ordre int(11) DEFAULT NULL,
  KEY notices_custom_champ (notices_custom_champ),
  KEY i_ncl_lv (notices_custom_list_value)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table notices_custom_values
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE notices_custom_values (
  notices_custom_champ int(10) unsigned NOT NULL DEFAULT '0',
  notices_custom_origine int(10) unsigned NOT NULL DEFAULT '0',
  notices_custom_small_text varchar(255) DEFAULT NULL,
  notices_custom_text text,
  notices_custom_integer int(11) DEFAULT NULL,
  notices_custom_date date DEFAULT NULL,
  notices_custom_float float DEFAULT NULL,
  KEY notices_custom_champ (notices_custom_champ),
  KEY notices_custom_origine (notices_custom_origine),
  KEY i_ncv_st (notices_custom_small_text),
  KEY i_ncv_t (notices_custom_text(255)),
  KEY i_ncv_i (notices_custom_integer),
  KEY i_ncv_d (notices_custom_date),
  KEY i_ncv_f (notices_custom_float)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table notices_global_index
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE notices_global_index (
  num_notice mediumint(8) NOT NULL DEFAULT '0',
  no_index mediumint(8) NOT NULL DEFAULT '0',
  infos_global text NOT NULL,
  index_infos_global text NOT NULL,
  PRIMARY KEY (num_notice,no_index)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table notices_langues
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE notices_langues (
  num_notice int(8) unsigned NOT NULL DEFAULT '0',
  type_langue int(1) unsigned NOT NULL DEFAULT '0',
  code_langue char(3) NOT NULL DEFAULT '',
  PRIMARY KEY (num_notice,type_langue,code_langue)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table notices_mots_global_index
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE notices_mots_global_index (
  id_notice mediumint(8) NOT NULL DEFAULT '0',
  code_champ int(2) NOT NULL DEFAULT '0',
  mot varchar(100) NOT NULL DEFAULT '',
  nbr_mot int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (id_notice,code_champ,mot),
  KEY code_champ (code_champ),
  KEY mot (mot)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table notices_relations
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE notices_relations (
  num_notice bigint(20) unsigned NOT NULL DEFAULT '0',
  linked_notice bigint(20) unsigned NOT NULL DEFAULT '0',
  relation_type char(1) NOT NULL DEFAULT '',
  rank int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (num_notice,linked_notice),
  KEY linked_notice (linked_notice),
  KEY relation_type (relation_type)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table notices_titres_uniformes
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE notices_titres_uniformes (
  ntu_num_notice int(9) unsigned NOT NULL DEFAULT '0',
  ntu_num_tu int(9) unsigned NOT NULL DEFAULT '0',
  ntu_titre varchar(255) NOT NULL DEFAULT '',
  ntu_date varchar(255) NOT NULL DEFAULT '',
  ntu_sous_vedette varchar(255) NOT NULL DEFAULT '',
  ntu_langue varchar(255) NOT NULL DEFAULT '',
  ntu_version varchar(255) NOT NULL DEFAULT '',
  ntu_mention varchar(255) NOT NULL DEFAULT '',
  ntu_ordre smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (ntu_num_notice,ntu_num_tu)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table offres_remises
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE offres_remises (
  num_fournisseur int(5) unsigned NOT NULL DEFAULT '0',
  num_produit int(8) unsigned NOT NULL DEFAULT '0',
  remise float(4,2) unsigned NOT NULL DEFAULT '0.00',
  condition_remise text,
  PRIMARY KEY (num_fournisseur,num_produit)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table opac_liste_lecture
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE opac_liste_lecture (
  id_liste int(8) unsigned NOT NULL AUTO_INCREMENT,
  nom_liste varchar(255) NOT NULL DEFAULT '',
  description varchar(255) NOT NULL DEFAULT '',
  notices_associees blob NOT NULL,
  public int(1) NOT NULL DEFAULT '0',
  num_empr int(8) unsigned NOT NULL DEFAULT '0',
  read_only int(1) NOT NULL DEFAULT '0',
  confidential int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id_liste)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table opac_sessions
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE opac_sessions (
  empr_id int(10) unsigned NOT NULL DEFAULT '0',
  session blob,
  date_rec timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (empr_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table origine_notice
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE origine_notice (
  orinot_id int(8) unsigned NOT NULL AUTO_INCREMENT,
  orinot_nom varchar(255) NOT NULL DEFAULT '',
  orinot_pays varchar(255) NOT NULL DEFAULT 'FR',
  orinot_diffusion int(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (orinot_id),
  KEY orinot_nom (orinot_nom)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table ouvertures
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE ouvertures (
  date_ouverture date NOT NULL DEFAULT '0000-00-00',
  ouvert int(1) NOT NULL DEFAULT '1',
  commentaire varchar(255) NOT NULL DEFAULT '',
  num_location int(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (date_ouverture,num_location)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table paiements
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE paiements (
  id_paiement int(8) unsigned NOT NULL AUTO_INCREMENT,
  libelle varchar(255) NOT NULL DEFAULT '',
  commentaire text NOT NULL,
  PRIMARY KEY (id_paiement)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table parametres
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE parametres (
  id_param int(6) unsigned NOT NULL AUTO_INCREMENT,
  type_param varchar(20) DEFAULT NULL,
  sstype_param varchar(255) DEFAULT NULL,
  valeur_param text,
  comment_param longtext,
  section_param varchar(255) NOT NULL DEFAULT '',
  gestion int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id_param),
  UNIQUE KEY typ_sstyp (type_param,sstype_param)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table pclassement
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE pclassement (
  id_pclass int(10) unsigned NOT NULL AUTO_INCREMENT,
  name_pclass varchar(255) NOT NULL DEFAULT '',
  typedoc varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (id_pclass)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table pret
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE pret (
  pret_idempr int(10) unsigned NOT NULL DEFAULT '0',
  pret_idexpl int(10) unsigned NOT NULL DEFAULT '0',
  pret_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  pret_retour date DEFAULT NULL,
  pret_arc_id int(10) unsigned NOT NULL DEFAULT '0',
  niveau_relance int(1) NOT NULL DEFAULT '0',
  date_relance date DEFAULT '0000-00-00',
  printed int(1) NOT NULL DEFAULT '0',
  retour_initial date DEFAULT '0000-00-00',
  cpt_prolongation int(1) NOT NULL DEFAULT '0',
  pret_temp varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (pret_idexpl),
  KEY i_pret_idempr (pret_idempr)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table pret_archive
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE pret_archive (
  arc_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  arc_debut datetime DEFAULT '0000-00-00 00:00:00',
  arc_fin datetime DEFAULT NULL,
  arc_id_empr int(10) unsigned NOT NULL DEFAULT '0',
  arc_empr_cp varchar(10) NOT NULL DEFAULT '',
  arc_empr_ville varchar(255) NOT NULL DEFAULT '',
  arc_empr_prof varchar(255) NOT NULL DEFAULT '',
  arc_empr_year int(4) unsigned DEFAULT '0',
  arc_empr_categ smallint(5) unsigned DEFAULT '0',
  arc_empr_codestat smallint(5) unsigned DEFAULT '0',
  arc_empr_sexe tinyint(3) unsigned DEFAULT '0',
  arc_empr_statut int(10) unsigned NOT NULL DEFAULT '1',
  arc_expl_typdoc int(5) unsigned DEFAULT '0',
  arc_expl_cote varchar(255) NOT NULL DEFAULT '',
  arc_expl_statut smallint(5) unsigned DEFAULT '0',
  arc_expl_location smallint(5) unsigned DEFAULT '0',
  arc_expl_codestat smallint(5) unsigned DEFAULT '0',
  arc_expl_owner mediumint(8) unsigned DEFAULT '0',
  arc_expl_section int(5) unsigned NOT NULL DEFAULT '0',
  arc_expl_id int(10) unsigned NOT NULL DEFAULT '0',
  arc_expl_notice int(10) unsigned NOT NULL DEFAULT '0',
  arc_expl_bulletin int(10) unsigned NOT NULL DEFAULT '0',
  arc_groupe varchar(255) NOT NULL DEFAULT '',
  arc_niveau_relance int(1) unsigned DEFAULT '0',
  arc_date_relance date NOT NULL DEFAULT '0000-00-00',
  arc_printed int(1) unsigned DEFAULT '0',
  arc_cpt_prolongation int(1) unsigned DEFAULT '0',
  PRIMARY KEY (arc_id),
  KEY i_pa_expl_id (arc_expl_id),
  KEY i_pa_idempr (arc_id_empr),
  KEY i_pa_expl_notice (arc_expl_notice),
  KEY i_pa_expl_bulletin (arc_expl_bulletin),
  KEY i_pa_arc_fin (arc_fin),
  KEY i_pa_arc_empr_categ (arc_empr_categ),
  KEY i_pa_arc_expl_location (arc_expl_location)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table procs
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE procs (
  idproc smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL DEFAULT '',
  requete blob NOT NULL,
  comment tinytext NOT NULL,
  autorisations mediumtext,
  parameters text,
  num_classement int(5) unsigned NOT NULL DEFAULT '0',
  proc_notice_tpl int(2) unsigned NOT NULL DEFAULT '0',
  proc_notice_tpl_field varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (idproc),
  KEY idproc (idproc)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table procs_classements
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE procs_classements (
  idproc_classement smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  libproc_classement varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (idproc_classement)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table publishers
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE publishers (
  ed_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  ed_name varchar(255) NOT NULL DEFAULT '',
  ed_adr1 varchar(255) NOT NULL DEFAULT '',
  ed_adr2 varchar(255) NOT NULL DEFAULT '',
  ed_cp varchar(10) NOT NULL DEFAULT '',
  ed_ville varchar(96) NOT NULL DEFAULT '',
  ed_pays varchar(96) NOT NULL DEFAULT '',
  ed_web varchar(255) NOT NULL DEFAULT '',
  index_publisher text,
  ed_comment text,
  PRIMARY KEY (ed_id),
  KEY ed_name (ed_name),
  KEY ed_ville (ed_ville)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table quotas
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE quotas (
  quota_type int(10) unsigned NOT NULL DEFAULT '0',
  constraint_type varchar(255) NOT NULL DEFAULT '',
  elements int(10) unsigned NOT NULL DEFAULT '0',
  value float DEFAULT NULL,
  PRIMARY KEY (quota_type,constraint_type,elements)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table quotas_finance
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE quotas_finance (
  quota_type int(10) unsigned NOT NULL DEFAULT '0',
  constraint_type varchar(255) NOT NULL DEFAULT '',
  elements int(10) unsigned NOT NULL DEFAULT '0',
  value float DEFAULT NULL,
  PRIMARY KEY (quota_type,constraint_type,elements)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table rapport_demandes
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE rapport_demandes (
  id_item int(10) unsigned NOT NULL AUTO_INCREMENT,
  contenu text NOT NULL,
  num_note int(10) NOT NULL DEFAULT '0',
  num_demande int(10) NOT NULL DEFAULT '0',
  ordre mediumint(3) NOT NULL DEFAULT '0',
  type mediumint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (id_item)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table recouvrements
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE recouvrements (
  recouvr_id int(16) unsigned NOT NULL AUTO_INCREMENT,
  empr_id int(10) unsigned NOT NULL DEFAULT '0',
  id_expl int(10) unsigned NOT NULL DEFAULT '0',
  date_rec date NOT NULL DEFAULT '0000-00-00',
  libelle varchar(255) DEFAULT NULL,
  montant decimal(16,2) DEFAULT '0.00',
  recouvr_type int(2) unsigned NOT NULL DEFAULT '0',
  date_pret datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  date_relance1 datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  date_relance2 datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  date_relance3 datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (recouvr_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table resa
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE resa (
  id_resa mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  resa_idempr mediumint(8) unsigned NOT NULL DEFAULT '0',
  resa_idnotice mediumint(8) unsigned NOT NULL DEFAULT '0',
  resa_idbulletin int(8) unsigned NOT NULL DEFAULT '0',
  resa_date datetime DEFAULT NULL,
  resa_date_debut date NOT NULL DEFAULT '0000-00-00',
  resa_date_fin date NOT NULL DEFAULT '0000-00-00',
  resa_cb varchar(255) NOT NULL DEFAULT '',
  resa_confirmee int(1) unsigned NOT NULL DEFAULT '0',
  resa_loc_retrait smallint(5) unsigned NOT NULL DEFAULT '0',
  resa_arc int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id_resa),
  KEY resa_date_fin (resa_date_fin),
  KEY resa_date (resa_date),
  KEY resa_cb (resa_cb)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table resa_archive
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE resa_archive (
  resarc_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  resarc_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  resarc_debut date NOT NULL DEFAULT '0000-00-00',
  resarc_fin date NOT NULL DEFAULT '0000-00-00',
  resarc_idnotice int(10) unsigned NOT NULL DEFAULT '0',
  resarc_idbulletin int(10) unsigned NOT NULL DEFAULT '0',
  resarc_confirmee int(1) unsigned DEFAULT '0',
  resarc_cb varchar(14) NOT NULL DEFAULT '',
  resarc_loc_retrait smallint(5) unsigned DEFAULT '0',
  resarc_from_opac int(1) unsigned DEFAULT '0',
  resarc_anulee int(1) unsigned DEFAULT '0',
  resarc_pretee int(1) unsigned DEFAULT '0',
  resarc_arcpretid int(10) unsigned NOT NULL DEFAULT '0',
  resarc_id_empr int(10) unsigned NOT NULL DEFAULT '0',
  resarc_empr_cp varchar(10) NOT NULL DEFAULT '',
  resarc_empr_ville varchar(255) NOT NULL DEFAULT '',
  resarc_empr_prof varchar(255) NOT NULL DEFAULT '',
  resarc_empr_year int(4) unsigned DEFAULT '0',
  resarc_empr_categ smallint(5) unsigned DEFAULT '0',
  resarc_empr_codestat smallint(5) unsigned DEFAULT '0',
  resarc_empr_sexe tinyint(3) unsigned DEFAULT '0',
  resarc_empr_location int(6) unsigned NOT NULL DEFAULT '1',
  resarc_expl_nb int(5) unsigned DEFAULT '0',
  resarc_expl_typdoc int(5) unsigned DEFAULT '0',
  resarc_expl_cote varchar(255) NOT NULL DEFAULT '',
  resarc_expl_statut smallint(5) unsigned DEFAULT '0',
  resarc_expl_location smallint(5) unsigned DEFAULT '0',
  resarc_expl_codestat smallint(5) unsigned DEFAULT '0',
  resarc_expl_owner mediumint(8) unsigned DEFAULT '0',
  resarc_expl_section int(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (resarc_id),
  KEY i_pa_idempr (resarc_id_empr),
  KEY i_pa_notice (resarc_idnotice),
  KEY i_pa_bulletin (resarc_idbulletin),
  KEY i_pa_resarc_date (resarc_date)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table resa_loc
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE resa_loc (
  resa_loc int(8) unsigned NOT NULL DEFAULT '0',
  resa_emprloc int(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (resa_loc,resa_emprloc),
  KEY i_resa_emprloc (resa_emprloc)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table resa_planning
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE resa_planning (
  id_resa mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  resa_idempr mediumint(8) unsigned NOT NULL DEFAULT '0',
  resa_idnotice mediumint(8) unsigned NOT NULL DEFAULT '0',
  resa_date datetime DEFAULT NULL,
  resa_date_debut date NOT NULL DEFAULT '0000-00-00',
  resa_date_fin date NOT NULL DEFAULT '0000-00-00',
  resa_validee int(1) unsigned NOT NULL DEFAULT '0',
  resa_confirmee int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id_resa),
  KEY resa_date_fin (resa_date_fin),
  KEY resa_date (resa_date)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table resa_ranger
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE resa_ranger (
  resa_cb varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (resa_cb)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table responsability
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE responsability (
  responsability_author mediumint(8) unsigned NOT NULL DEFAULT '0',
  responsability_notice mediumint(8) unsigned NOT NULL DEFAULT '0',
  responsability_fonction varchar(4) NOT NULL DEFAULT '',
  responsability_type mediumint(1) unsigned NOT NULL DEFAULT '0',
  responsability_ordre smallint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (responsability_author,responsability_notice,responsability_fonction),
  KEY responsability_notice (responsability_notice)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table rss_content
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE rss_content (
  rss_id int(10) unsigned NOT NULL DEFAULT '0',
  rss_content longblob NOT NULL,
  rss_content_parse longblob NOT NULL,
  rss_last timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (rss_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table rss_flux
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE rss_flux (
  id_rss_flux int(9) unsigned NOT NULL AUTO_INCREMENT,
  nom_rss_flux varchar(255) NOT NULL DEFAULT '',
  link_rss_flux blob NOT NULL,
  descr_rss_flux blob NOT NULL,
  lang_rss_flux varchar(255) NOT NULL DEFAULT 'fr',
  copy_rss_flux blob NOT NULL,
  editor_rss_flux varchar(255) NOT NULL DEFAULT '',
  webmaster_rss_flux varchar(255) NOT NULL DEFAULT '',
  ttl_rss_flux int(9) unsigned NOT NULL DEFAULT '60',
  img_url_rss_flux blob NOT NULL,
  img_title_rss_flux blob NOT NULL,
  img_link_rss_flux blob NOT NULL,
  format_flux blob NOT NULL,
  rss_flux_content longblob NOT NULL,
  rss_flux_last timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  export_court_flux tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id_rss_flux)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table rss_flux_content
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE rss_flux_content (
  num_rss_flux int(9) unsigned NOT NULL DEFAULT '0',
  type_contenant char(3) NOT NULL DEFAULT 'BAN',
  num_contenant int(9) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (num_rss_flux,type_contenant,num_contenant)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table rubriques
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE rubriques (
  id_rubrique int(8) unsigned NOT NULL AUTO_INCREMENT,
  num_budget int(8) unsigned NOT NULL DEFAULT '0',
  num_parent int(8) unsigned NOT NULL DEFAULT '0',
  libelle varchar(255) NOT NULL DEFAULT '',
  commentaires text NOT NULL,
  montant float(8,2) unsigned NOT NULL DEFAULT '0.00',
  num_cp_compta varchar(255) NOT NULL DEFAULT '',
  autorisations mediumtext NOT NULL,
  PRIMARY KEY (id_rubrique)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table sauv_lieux
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE sauv_lieux (
  sauv_lieu_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  sauv_lieu_nom varchar(50) DEFAULT NULL,
  sauv_lieu_url varchar(255) DEFAULT NULL,
  sauv_lieu_protocol varchar(10) DEFAULT 'file',
  sauv_lieu_host varchar(255) DEFAULT NULL,
  sauv_lieu_login varchar(255) DEFAULT NULL,
  sauv_lieu_password varchar(255) DEFAULT NULL,
  PRIMARY KEY (sauv_lieu_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table sauv_log
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE sauv_log (
  sauv_log_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  sauv_log_start_date date DEFAULT NULL,
  sauv_log_file varchar(255) DEFAULT NULL,
  sauv_log_succeed int(11) DEFAULT '0',
  sauv_log_messages mediumtext,
  sauv_log_userid int(11) DEFAULT NULL,
  PRIMARY KEY (sauv_log_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table sauv_sauvegardes
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE sauv_sauvegardes (
  sauv_sauvegarde_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  sauv_sauvegarde_nom varchar(50) DEFAULT NULL,
  sauv_sauvegarde_file_prefix varchar(20) DEFAULT NULL,
  sauv_sauvegarde_tables mediumtext,
  sauv_sauvegarde_lieux mediumtext,
  sauv_sauvegarde_users mediumtext,
  sauv_sauvegarde_compress int(11) DEFAULT '0',
  sauv_sauvegarde_compress_command mediumtext,
  sauv_sauvegarde_crypt int(11) DEFAULT '0',
  sauv_sauvegarde_key1 varchar(32) DEFAULT NULL,
  sauv_sauvegarde_key2 varchar(32) DEFAULT NULL,
  PRIMARY KEY (sauv_sauvegarde_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table sauv_tables
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE sauv_tables (
  sauv_table_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  sauv_table_nom varchar(50) DEFAULT NULL,
  sauv_table_tables text,
  PRIMARY KEY (sauv_table_id),
  UNIQUE KEY sauv_table_nom (sauv_table_nom)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table search_perso
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE search_perso (
  search_id int(8) unsigned NOT NULL AUTO_INCREMENT,
  num_user int(8) unsigned NOT NULL DEFAULT '0',
  search_name varchar(255) NOT NULL DEFAULT '',
  search_shortname varchar(50) NOT NULL DEFAULT '',
  search_query text NOT NULL,
  search_human text NOT NULL,
  search_directlink tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (search_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table search_persopac
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE search_persopac (
  search_id int(8) unsigned NOT NULL AUTO_INCREMENT,
  num_empr int(8) unsigned NOT NULL DEFAULT '0',
  search_name varchar(255) NOT NULL DEFAULT '',
  search_shortname varchar(50) NOT NULL DEFAULT '',
  search_query text NOT NULL,
  search_human text NOT NULL,
  search_directlink tinyint(1) unsigned NOT NULL DEFAULT '0',
  search_limitsearch tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (search_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table series
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE series (
  serie_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  serie_name varchar(255) NOT NULL DEFAULT '',
  serie_index text,
  PRIMARY KEY (serie_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table sessions
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE sessions (
  SESSID varchar(12) NOT NULL DEFAULT '',
  login varchar(20) NOT NULL DEFAULT '',
  IP varchar(20) NOT NULL DEFAULT '',
  SESSstart varchar(12) NOT NULL DEFAULT '',
  LastOn varchar(12) NOT NULL DEFAULT '',
  SESSNAME varchar(25) NOT NULL DEFAULT ''
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table source_sync
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE source_sync (
  source_id int(10) unsigned NOT NULL DEFAULT '0',
  nrecu varchar(255) NOT NULL DEFAULT '',
  ntotal varchar(255) NOT NULL DEFAULT '',
  message varchar(255) NOT NULL DEFAULT '',
  date_sync datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  percent int(10) unsigned NOT NULL DEFAULT '0',
  env text NOT NULL,
  cancel int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (source_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table statopac
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE statopac (
  id_log int(8) unsigned NOT NULL AUTO_INCREMENT,
  date_log timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  url_demandee varchar(255) NOT NULL DEFAULT '',
  url_referente varchar(255) NOT NULL DEFAULT '',
  get_log blob NOT NULL,
  post_log blob NOT NULL,
  num_session varchar(255) NOT NULL DEFAULT '0',
  server_log blob NOT NULL,
  empr_carac blob NOT NULL,
  empr_doc blob NOT NULL,
  empr_expl blob NOT NULL,
  nb_result blob NOT NULL,
  gen_stat blob NOT NULL,
  PRIMARY KEY (id_log)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table statopac_request
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE statopac_request (
  idproc int(8) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL DEFAULT '',
  requete blob NOT NULL,
  comment tinytext NOT NULL,
  parameters text NOT NULL,
  num_vue mediumint(8) NOT NULL DEFAULT '0',
  autorisations mediumtext NOT NULL,
  PRIMARY KEY (idproc)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table statopac_vues
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE statopac_vues (
  id_vue int(8) unsigned NOT NULL AUTO_INCREMENT,
  date_consolidation datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  nom_vue varchar(255) NOT NULL DEFAULT '',
  comment tinytext NOT NULL,
  PRIMARY KEY (id_vue)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table statopac_vues_col
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE statopac_vues_col (
  id_col int(8) unsigned NOT NULL AUTO_INCREMENT,
  nom_col varchar(255) NOT NULL DEFAULT '',
  expression varchar(255) NOT NULL DEFAULT '',
  num_vue mediumint(8) NOT NULL DEFAULT '0',
  ordre mediumint(8) NOT NULL DEFAULT '0',
  filtre varchar(255) NOT NULL DEFAULT '',
  datatype varchar(10) NOT NULL DEFAULT '',
  maj_flag int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id_col)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table sub_collections
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE sub_collections (
  sub_coll_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  sub_coll_name varchar(255) NOT NULL DEFAULT '',
  sub_coll_parent mediumint(9) unsigned NOT NULL DEFAULT '0',
  sub_coll_issn varchar(12) NOT NULL DEFAULT '',
  index_sub_coll text,
  subcollection_web text NOT NULL,
  PRIMARY KEY (sub_coll_id),
  KEY sub_coll_name (sub_coll_name)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table suggestions
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE suggestions (
  id_suggestion int(12) unsigned NOT NULL AUTO_INCREMENT,
  titre tinytext NOT NULL,
  editeur varchar(255) NOT NULL DEFAULT '',
  auteur varchar(255) NOT NULL DEFAULT '',
  code varchar(255) NOT NULL DEFAULT '',
  prix float(8,2) unsigned NOT NULL DEFAULT '0.00',
  commentaires text,
  statut int(3) unsigned NOT NULL DEFAULT '0',
  num_produit int(8) NOT NULL DEFAULT '0',
  num_entite int(5) NOT NULL DEFAULT '0',
  index_suggestion text NOT NULL,
  nb int(5) unsigned NOT NULL DEFAULT '1',
  date_creation date NOT NULL DEFAULT '0000-00-00',
  date_decision date NOT NULL DEFAULT '0000-00-00',
  num_rubrique int(8) unsigned NOT NULL DEFAULT '0',
  num_fournisseur int(5) unsigned NOT NULL DEFAULT '0',
  num_notice int(8) unsigned NOT NULL DEFAULT '0',
  url_suggestion varchar(255) NOT NULL DEFAULT '',
  num_categ int(12) NOT NULL DEFAULT '1',
  sugg_location smallint(5) unsigned NOT NULL DEFAULT '0',
  sugg_source int(8) NOT NULL DEFAULT '0',
  date_publication varchar(255) NOT NULL DEFAULT '',
  notice_unimarc blob NOT NULL,
  PRIMARY KEY (id_suggestion)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table suggestions_categ
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE suggestions_categ (
  id_categ int(12) NOT NULL AUTO_INCREMENT,
  libelle_categ varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (id_categ)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table suggestions_origine
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE suggestions_origine (
  origine varchar(100) NOT NULL DEFAULT '',
  num_suggestion int(12) unsigned NOT NULL DEFAULT '0',
  type_origine int(3) unsigned NOT NULL DEFAULT '0',
  date_suggestion date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (origine,num_suggestion,type_origine),
  KEY i_origine (origine,type_origine)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table suggestions_source
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE suggestions_source (
  id_source int(8) unsigned NOT NULL AUTO_INCREMENT,
  libelle_source varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (id_source)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table tags
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE tags (
  id_tag mediumint(8) NOT NULL AUTO_INCREMENT,
  libelle varchar(200) NOT NULL DEFAULT '',
  num_notice mediumint(8) NOT NULL DEFAULT '0',
  user_code varchar(50) NOT NULL DEFAULT '',
  dateajout timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id_tag)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table thesaurus
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE thesaurus (
  id_thesaurus int(3) unsigned NOT NULL AUTO_INCREMENT,
  libelle_thesaurus varchar(255) NOT NULL DEFAULT '',
  langue_defaut varchar(5) NOT NULL DEFAULT 'fr_FR',
  active char(1) NOT NULL DEFAULT '1',
  opac_active char(1) NOT NULL DEFAULT '1',
  num_noeud_racine int(9) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id_thesaurus),
  UNIQUE KEY libelle_thesaurus (libelle_thesaurus)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table titres_uniformes
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE titres_uniformes (
  tu_id int(9) unsigned NOT NULL AUTO_INCREMENT,
  tu_name varchar(255) NOT NULL DEFAULT '',
  tu_tonalite varchar(255) NOT NULL DEFAULT '',
  tu_comment text NOT NULL,
  index_tu text NOT NULL,
  PRIMARY KEY (tu_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table transactions
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE transactions (
  id_transaction int(10) unsigned NOT NULL AUTO_INCREMENT,
  compte_id int(8) unsigned NOT NULL DEFAULT '0',
  user_id int(10) unsigned NOT NULL DEFAULT '0',
  user_name varchar(255) NOT NULL DEFAULT '',
  machine varchar(255) NOT NULL DEFAULT '',
  date_enrgt datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  date_prevue date DEFAULT NULL,
  date_effective date DEFAULT NULL,
  montant decimal(16,2) NOT NULL DEFAULT '0.00',
  sens int(1) NOT NULL DEFAULT '0',
  realisee int(1) NOT NULL DEFAULT '0',
  commentaire text,
  encaissement int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id_transaction)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table transferts
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE transferts (
  id_transfert int(10) unsigned NOT NULL AUTO_INCREMENT,
  num_notice int(10) unsigned NOT NULL DEFAULT '0',
  num_bulletin int(10) unsigned NOT NULL DEFAULT '0',
  date_creation date NOT NULL,
  type_transfert int(5) unsigned NOT NULL DEFAULT '0',
  etat_transfert tinyint(3) unsigned NOT NULL DEFAULT '0',
  origine int(5) unsigned NOT NULL DEFAULT '0',
  origine_comp varchar(255) NOT NULL DEFAULT '',
  source smallint(5) unsigned DEFAULT NULL,
  destinations varchar(255) DEFAULT NULL,
  date_retour date DEFAULT NULL,
  motif varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (id_transfert),
  KEY etat_transfert (etat_transfert)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table transferts_demande
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE transferts_demande (
  id_transfert_demande int(10) unsigned NOT NULL AUTO_INCREMENT,
  num_transfert int(10) unsigned NOT NULL DEFAULT '0',
  date_creation date NOT NULL,
  sens_transfert tinyint(3) unsigned NOT NULL DEFAULT '0',
  num_location_source smallint(5) unsigned NOT NULL DEFAULT '0',
  num_location_dest smallint(5) unsigned NOT NULL DEFAULT '0',
  num_expl int(10) unsigned NOT NULL DEFAULT '0',
  etat_demande tinyint(3) unsigned NOT NULL DEFAULT '0',
  date_visualisee date DEFAULT NULL,
  date_envoyee date DEFAULT NULL,
  date_reception date DEFAULT NULL,
  motif_refus varchar(255) NOT NULL DEFAULT '',
  statut_origine int(10) unsigned NOT NULL DEFAULT '0',
  section_origine int(10) unsigned NOT NULL DEFAULT '0',
  resa_trans int(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id_transfert_demande),
  KEY num_transfert (num_transfert),
  KEY num_location_source (num_location_source),
  KEY num_location_dest (num_location_dest),
  KEY num_expl (num_expl)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table translation
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE translation (
  trans_table varchar(100) NOT NULL DEFAULT '',
  trans_field varchar(100) NOT NULL DEFAULT '',
  trans_lang varchar(5) NOT NULL DEFAULT '',
  trans_num int(8) unsigned NOT NULL DEFAULT '0',
  trans_text varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (trans_table,trans_field,trans_lang,trans_num),
  KEY i_lang (trans_lang)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table tris
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE tris (
  id_tri int(4) NOT NULL AUTO_INCREMENT,
  tri_par varchar(100) NOT NULL DEFAULT '',
  nom_tri varchar(100) NOT NULL DEFAULT '',
  tri_reference varchar(40) NOT NULL DEFAULT 'notices',
  PRIMARY KEY (id_tri)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table tu_distrib
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE tu_distrib (
  distrib_num_tu int(9) unsigned NOT NULL DEFAULT '0',
  distrib_name varchar(255) NOT NULL DEFAULT '',
  distrib_ordre smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (distrib_num_tu,distrib_ordre)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table tu_ref
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE tu_ref (
  ref_num_tu int(9) unsigned NOT NULL DEFAULT '0',
  ref_name varchar(255) NOT NULL DEFAULT '',
  ref_ordre smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (ref_num_tu,ref_ordre)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table tu_subdiv
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE tu_subdiv (
  subdiv_num_tu int(9) unsigned NOT NULL DEFAULT '0',
  subdiv_name varchar(255) NOT NULL DEFAULT '',
  subdiv_ordre smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (subdiv_num_tu,subdiv_ordre)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table tva_achats
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE tva_achats (
  id_tva int(8) unsigned NOT NULL AUTO_INCREMENT,
  libelle varchar(255) NOT NULL DEFAULT '',
  taux_tva float(4,2) unsigned NOT NULL DEFAULT '0.00',
  num_cp_compta varchar(25) NOT NULL DEFAULT '0',
  PRIMARY KEY (id_tva)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table type_abts
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE type_abts (
  id_type_abt int(5) unsigned NOT NULL AUTO_INCREMENT,
  type_abt_libelle varchar(255) DEFAULT NULL,
  prepay int(1) unsigned NOT NULL DEFAULT '0',
  prepay_deflt_mnt decimal(16,2) NOT NULL DEFAULT '0.00',
  tarif decimal(16,2) NOT NULL DEFAULT '0.00',
  commentaire text NOT NULL,
  caution decimal(16,2) NOT NULL DEFAULT '0.00',
  localisations varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (id_type_abt)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table type_comptes
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE type_comptes (
  id_type_compte int(8) unsigned NOT NULL AUTO_INCREMENT,
  libelle varchar(255) NOT NULL DEFAULT '',
  type_acces int(8) unsigned NOT NULL DEFAULT '0',
  acces_id text NOT NULL,
  PRIMARY KEY (id_type_compte)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table types_produits
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE types_produits (
  id_produit int(8) unsigned NOT NULL AUTO_INCREMENT,
  libelle varchar(255) NOT NULL DEFAULT '',
  num_cp_compta varchar(25) NOT NULL DEFAULT '0',
  num_tva_achat varchar(25) NOT NULL DEFAULT '0',
  PRIMARY KEY (id_produit),
  KEY libelle (libelle)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table upload_repertoire
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE upload_repertoire (
  repertoire_id int(8) unsigned NOT NULL AUTO_INCREMENT,
  repertoire_nom varchar(255) NOT NULL DEFAULT '',
  repertoire_url text NOT NULL,
  repertoire_path text NOT NULL,
  repertoire_navigation int(1) NOT NULL DEFAULT '0',
  repertoire_hachage int(1) NOT NULL DEFAULT '0',
  repertoire_subfolder int(8) NOT NULL DEFAULT '0',
  repertoire_utf8 int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (repertoire_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table users
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE users (
  userid int(5) NOT NULL AUTO_INCREMENT,
  create_dt date NOT NULL DEFAULT '0000-00-00',
  last_updated_dt date NOT NULL DEFAULT '0000-00-00',
  username varchar(100) NOT NULL DEFAULT '',
  pwd varchar(50) NOT NULL DEFAULT '',
  nom varchar(30) NOT NULL DEFAULT '',
  prenom varchar(30) DEFAULT NULL,
  rights int(8) unsigned NOT NULL DEFAULT '0',
  user_lang varchar(5) NOT NULL DEFAULT 'fr_FR',
  nb_per_page_search int(10) unsigned NOT NULL DEFAULT '4',
  nb_per_page_select int(10) unsigned NOT NULL DEFAULT '10',
  nb_per_page_gestion int(10) unsigned NOT NULL DEFAULT '20',
  param_popup_ticket smallint(1) unsigned NOT NULL DEFAULT '0',
  param_sounds smallint(1) unsigned NOT NULL DEFAULT '1',
  param_rfid_activate int(1) NOT NULL DEFAULT '1',
  param_licence int(1) unsigned NOT NULL DEFAULT '0',
  deflt_notice_statut int(6) unsigned NOT NULL DEFAULT '1',
  deflt_docs_type int(6) unsigned NOT NULL DEFAULT '1',
  deflt_lenders int(6) unsigned NOT NULL DEFAULT '0',
  deflt_styles varchar(20) NOT NULL DEFAULT 'default',
  deflt_docs_statut int(6) unsigned DEFAULT '0',
  deflt_docs_codestat int(6) unsigned DEFAULT '0',
  value_deflt_lang varchar(20) DEFAULT 'fre',
  value_deflt_fonction varchar(20) DEFAULT '070',
  value_deflt_relation varchar(20) NOT NULL DEFAULT 'a',
  deflt_docs_location int(6) unsigned DEFAULT '0',
  deflt_docs_section int(6) unsigned DEFAULT '0',
  value_deflt_module varchar(30) DEFAULT 'circu',
  user_email varchar(255) DEFAULT '',
  user_alert_resamail int(1) unsigned NOT NULL DEFAULT '0',
  deflt2docs_location int(6) unsigned NOT NULL DEFAULT '0',
  deflt_empr_statut bigint(20) unsigned NOT NULL DEFAULT '1',
  deflt_thesaurus int(3) unsigned NOT NULL DEFAULT '1',
  value_prefix_cote tinyblob NOT NULL,
  xmlta_doctype char(2) NOT NULL DEFAULT 'a',
  speci_coordonnees_etab mediumtext NOT NULL,
  value_email_bcc varchar(255) NOT NULL DEFAULT '',
  value_deflt_antivol varchar(50) NOT NULL DEFAULT '0',
  explr_invisible varchar(255) DEFAULT '0',
  explr_visible_mod varchar(255) DEFAULT '0',
  explr_visible_unmod varchar(255) DEFAULT '0',
  deflt3bibli int(5) unsigned NOT NULL DEFAULT '0',
  deflt3exercice int(8) unsigned NOT NULL DEFAULT '0',
  deflt3rubrique int(8) unsigned NOT NULL DEFAULT '0',
  deflt3dev_statut int(3) NOT NULL DEFAULT '-1',
  deflt3cde_statut int(3) NOT NULL DEFAULT '-1',
  deflt3liv_statut int(3) NOT NULL DEFAULT '-1',
  deflt3fac_statut int(3) NOT NULL DEFAULT '-1',
  deflt3sug_statut int(3) NOT NULL DEFAULT '-1',
  environnement mediumblob NOT NULL,
  param_allloc int(1) unsigned NOT NULL DEFAULT '0',
  grp_num int(10) unsigned DEFAULT '0',
  deflt_arch_statut int(6) unsigned NOT NULL DEFAULT '0',
  deflt_arch_emplacement int(6) unsigned NOT NULL DEFAULT '0',
  deflt_arch_type int(6) unsigned NOT NULL DEFAULT '0',
  deflt_upload_repertoire int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (userid)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table users_groups
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE users_groups (
  grp_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  grp_name varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (grp_id),
  KEY i_users_groups_grp_name (grp_name)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table visionneuse_params
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE visionneuse_params (
  visionneuse_params_id int(11) NOT NULL AUTO_INCREMENT,
  visionneuse_params_class varchar(255) NOT NULL DEFAULT '',
  visionneuse_params_parameters text NOT NULL,
  PRIMARY KEY (visionneuse_params_id),
  UNIQUE KEY visionneuse_params_class (visionneuse_params_class)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table voir_aussi
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE voir_aussi (
  num_noeud_orig int(9) unsigned NOT NULL DEFAULT '0',
  num_noeud_dest int(9) unsigned NOT NULL DEFAULT '0',
  langue varchar(5) NOT NULL DEFAULT '',
  comment_voir_aussi text NOT NULL,
  PRIMARY KEY (num_noeud_orig,num_noeud_dest,langue),
  KEY num_noeud_dest (num_noeud_dest)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table z_attr
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE z_attr (
  attr_bib_id int(6) unsigned NOT NULL DEFAULT '0',
  attr_libelle varchar(250) NOT NULL DEFAULT '',
  attr_attr varchar(250) DEFAULT NULL,
  PRIMARY KEY (attr_bib_id,attr_libelle)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table z_bib
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE z_bib (
  bib_id int(6) unsigned NOT NULL AUTO_INCREMENT,
  bib_nom varchar(250) DEFAULT NULL,
  search_type varchar(20) DEFAULT NULL,
  url varchar(250) DEFAULT NULL,
  port varchar(6) DEFAULT NULL,
  base varchar(250) DEFAULT NULL,
  format varchar(250) DEFAULT NULL,
  auth_user varchar(250) NOT NULL DEFAULT '',
  auth_pass varchar(250) NOT NULL DEFAULT '',
  sutrs_lang varchar(10) NOT NULL DEFAULT '',
  fichier_func varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (bib_id)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table z_notices
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE z_notices (
  znotices_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  znotices_query_id int(11) DEFAULT NULL,
  znotices_bib_id int(6) unsigned DEFAULT '0',
  isbd text,
  isbn varchar(250) DEFAULT NULL,
  titre varchar(250) DEFAULT NULL,
  auteur varchar(250) DEFAULT NULL,
  z_marc longblob NOT NULL,
  PRIMARY KEY (znotices_id),
  KEY idx_z_notices_idq (znotices_query_id),
  KEY idx_z_notices_isbn (isbn),
  KEY idx_z_notices_titre (titre),
  KEY idx_z_notices_auteur (auteur)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table z_query
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE z_query (
  zquery_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  search_attr varchar(255) DEFAULT NULL,
  zquery_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (zquery_id),
  KEY zquery_date (zquery_date)
);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- END OF BIBLI.SQL
--
