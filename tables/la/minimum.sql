-- MySQL dump 10.9
--
-- Host: localhost    Database: bibli
-- ------------------------------------------------------
-- Server version	4.1.9-max

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES latin1 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;

--
-- Table structure for table `actes`
--

DROP TABLE IF EXISTS `actes`;
CREATE TABLE `actes` (
  `id_acte` int(8) unsigned NOT NULL auto_increment,
  `date_acte` date NOT NULL default '0000-00-00',
  `numero` varchar(25) NOT NULL default '',
  `type_acte` int(3) unsigned NOT NULL default '0',
  `statut` int(3) unsigned NOT NULL default '0',
  `date_paiement` date NOT NULL default '0000-00-00',
  `num_paiement` varchar(255) NOT NULL default '',
  `num_entite` int(5) unsigned NOT NULL default '0',
  `num_fournisseur` int(5) unsigned NOT NULL default '0',
  `num_contact_livr` int(8) unsigned NOT NULL default '0',
  `num_contact_fact` int(8) unsigned NOT NULL default '0',
  `num_exercice` int(8) unsigned NOT NULL default '0',
  `commentaires` text NOT NULL,
  `reference` varchar(255) NOT NULL default '',
  `index_acte` text NOT NULL,
  `devise` varchar(25) NOT NULL default '',
  `commentaires_i` text NOT NULL,
  `date_valid` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`id_acte`),
  KEY `num_fournisseur` (`num_fournisseur`),
  KEY `date` (`date_acte`),
  KEY `num_entite` (`num_entite`),
  KEY `numero` (`numero`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `actes`
--


/*!40000 ALTER TABLE `actes` DISABLE KEYS */;
LOCK TABLES `actes` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `actes` ENABLE KEYS */;

--
-- Table structure for table `admin_session`
--

DROP TABLE IF EXISTS `admin_session`;
CREATE TABLE `admin_session` (
  `userid` int(10) unsigned NOT NULL default '0',
  `session` blob,
  PRIMARY KEY  (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_session`
--


/*!40000 ALTER TABLE `admin_session` DISABLE KEYS */;
LOCK TABLES `admin_session` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `admin_session` ENABLE KEYS */;

--
-- Table structure for table `analysis`
--

DROP TABLE IF EXISTS `analysis`;
CREATE TABLE `analysis` (
  `analysis_bulletin` int(8) unsigned NOT NULL default '0',
  `analysis_notice` int(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`analysis_bulletin`,`analysis_notice`),
  KEY `analysis_notice` (`analysis_notice`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `analysis`
--


/*!40000 ALTER TABLE `analysis` DISABLE KEYS */;
LOCK TABLES `analysis` WRITE;
INSERT INTO `analysis` VALUES (1,21),(1,33),(1,35),(1,36),(1,37),(1,38),(1,39),(1,40),(1,41),(2,25),(2,26),(2,29),(2,30),(2,31),(2,32);
UNLOCK TABLES;
/*!40000 ALTER TABLE `analysis` ENABLE KEYS */;

--
-- Table structure for table `audit`
--

DROP TABLE IF EXISTS `audit`;
CREATE TABLE `audit` (
  `type_obj` int(1) NOT NULL default '0',
  `object_id` int(10) unsigned NOT NULL default '0',
  `user_id` int(8) unsigned NOT NULL default '0',
  `user_name` varchar(20) NOT NULL default '',
  `type_modif` int(1) NOT NULL default '1',
  `quand` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `audit`
--


/*!40000 ALTER TABLE `audit` DISABLE KEYS */;
LOCK TABLES `audit` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `audit` ENABLE KEYS */;

--
-- Table structure for table `authors`
--

DROP TABLE IF EXISTS `authors`;
CREATE TABLE `authors` (
  `author_id` mediumint(8) unsigned NOT NULL auto_increment,
  `author_type` enum('70','71') NOT NULL default '70',
  `author_name` varchar(255) default NULL,
  `author_rejete` varchar(255) default NULL,
  `author_date` varchar(255) NOT NULL default '',
  `author_see` mediumint(8) unsigned NOT NULL default '0',
  `author_web` varchar(255) NOT NULL default '',
  `index_author` text,
  `author_comment` text,
  PRIMARY KEY  (`author_id`),
  KEY `author_see` (`author_see`),
  KEY `author_name` (`author_name`),
  KEY `author_rejete` (`author_rejete`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `authors`
--


/*!40000 ALTER TABLE `authors` DISABLE KEYS */;
LOCK TABLES `authors` WRITE;
INSERT INTO `authors` VALUES (1,'70','àº„àº°àº™àº°àº­àº±àºàºªàº­àº™àºªàº²àº” àº¡/àºŠ','','13102006',0,'',' àº„àº°àº™àº°àº­àº±àºàºªàº­àº™àºªàº²àº” àº¡/àºŠ ',''),(2,'70','àºªàºµàº¥àº² àº§àº´àº¥àº°àº§àº»àº‡','','',0,'',' àºªàºµàº¥àº² àº§àº´àº¥àº°àº§àº»àº‡ ',''),(3,'70','àº”àº³àº”àº§àº™ àºàº»àº¡àº”àº§àº‡àºªàºµ','','13102006',0,'',' àº”àº³àº”àº§àº™ àºàº»àº¡àº”àº§àº‡àºªàºµ ',''),(4,'70','àº›àº°àº—àº´àºš àºŠàº¸àº¡àºàº»àº™','','13102006',0,'',' àº›àº°àº—àº´àºš àºŠàº¸àº¡àºàº»àº™ ',''),(5,'71','àºªàº°àº–àº²àºšàº±àº™àº„àº»àº™àº„àº§à»‰àº²àº§àº±àº”àº—àº°àº™àº°àº—àº³','','13102006',0,'',' àºªàº°àº–àº²àºšàº±àº™àº„àº»àº™àº„àº§à»‰àº²àº§àº±àº”àº—àº°àº™àº°àº—àº³ ',''),(6,'70','àºªàº¸à»€àº™àº” à»‚àºàº—àº´àºªàº²àº™','','13102006',0,'',' àºªàº¸à»€àº™àº” à»‚àºàº—àº´àºªàº²àº™ ',''),(7,'70','àºªàº¹àº™àºàº²àº‡àºªàº°àº«àº°àºàº±àº™àºàº³àº¡àº°àºšàº²àº™àº¥àº²àº§','','13102006',0,'',' àºªàº¹àº™àºàº²àº‡àºªàº°àº«àº°àºàº±àº™àºàº³àº¡àº°àºšàº²àº™àº¥àº²àº§ ',''),(8,'70','àºªàº¸àºˆàº´àº” àº§àº»àº‡à»€àº—àºš','','13102006',0,'',' àºªàº¸àºˆàº´àº” àº§àº»àº‡à»€àº—àºš ',''),(9,'70','àºšàº¸àº™àºªàºµ àºšàº¹àº¥àº»àº¡','','13102006',0,'',' àºšàº¸àº™àºªàºµ àºšàº¹àº¥àº»àº¡ ',''),(10,'70','àºšàº»àº§à»„àº‚ à»€àºàº±àº‡àºàº°àºˆàº±àº™','','13102006',0,'',' àºšàº»àº§à»„àº‚ à»€àºàº±àº‡àºàº°àºˆàº±àº™ ',''),(11,'70','à»‚àº„àºˆàº­àº™ à»àºà»‰àº§àº¡àº°àº™àºµàº§àº»àº‡','','13102006',0,'',' à»‚àº„àºˆàº­àº™ à»àºà»‰àº§àº¡àº°àº™àºµàº§àº»àº‡ ',''),(12,'71','àºàº»àº¡àºàº²àº™à»€àº¡àº·àº­àº‡ à»àº¥àº° àºàº²àº™àº›àº»àºàº„àº­àº‡','','13102006',0,'',' àºàº»àº¡àºàº²àº™à»€àº¡àº·àº­àº‡ à»àº¥àº° àºàº²àº™àº›àº»àºàº„àº­àº‡ ',''),(13,'71','àºàº»àº¡àº›à»ˆàº²à»„àº¡à»‰','','13102006',0,'',' àºàº»àº¡àº›à»ˆàº²à»„àº¡à»‰ ',''),(14,'70','àºšàº¸àº™àº¡àºµ à»€àº—àºšàºªàºµà»€àº¡àº·àº­àº‡','','13102006',0,'',' àºšàº¸àº™àº¡àºµ à»€àº—àºšàºªàºµà»€àº¡àº·àº­àº‡ ',''),(15,'70','àºàº²àºàº§àº´àºŠàº²àºàº²àºªàº²àº¥àº²àº§-àº§àº±àº™àº™àº°àº„àº°àº”àºµ','','13102006',0,'',' àºàº²àºàº§àº´àºŠàº²àºàº²àºªàº²àº¥àº²àº§-àº§àº±àº™àº™àº°àº„àº°àº”àºµ ',''),(16,'70','àºªàº³àº¥àº´àº” àºšàº»àº§àºªàºµàºªàº°àº«àº§àº±àº”','','13102006',0,'',' àºªàº³àº¥àº´àº” àºšàº»àº§àºªàºµàºªàº°àº«àº§àº±àº” ',''),(17,'71','àº­àº»àº‡àºàº²àº™àº­àº°àº™àº²à»„àº¡à»‚àº¥àº','','13102006',0,'',' àº­àº»àº‡àºàº²àº™àº­àº°àº™àº²à»„àº¡à»‚àº¥àº ',''),(18,'71','àº¡àº¹àº™àº™àº´àº—àº´àºŠàº²àºŠàº²àºàº²àº§àº² à»€àºàº·à»ˆàº­àºªàº±àº™àº•àº´àºàº²àºš','','13102006',0,'',' àº¡àº¹àº™àº™àº´àº—àº´àºŠàº²àºŠàº²àºàº²àº§àº² à»€àºàº·à»ˆàº­àºªàº±àº™àº•àº´àºàº²àºš ',''),(19,'71','àº„àº°àº™àº°àºˆàº±àº”àº•àº±àº‡àºªàº¹àº™àºàº²àº‡àºàº±àº','','13102006',0,'',' àº„àº°àº™àº°àºˆàº±àº”àº•àº±àº‡àºªàº¹àº™àºàº²àº‡àºàº±àº ',''),(20,'70','àº„àº³àºœàº²àº àºšàº¸àºšàºœàº²','','13102006',0,'',' àº„àº³àºœàº²àº àºšàº¸àºšàºœàº² ',''),(21,'70','àº—àº­àº‡àº¡àº²àº¥àºµ àºªàº¸àº¥àº²àº”','','13102006',0,'',' àº—àº­àº‡àº¡àº²àº¥àºµ àºªàº¸àº¥àº²àº” ',''),(22,'70','à»ƒàºŠàºàº­àº™ àºªàº´àº—àº²àº¥àº²àº”','','13102006',0,'',' à»ƒàºŠàºàº­àº™ àºªàº´àº—àº²àº¥àº²àº” ','');
UNLOCK TABLES;
/*!40000 ALTER TABLE `authors` ENABLE KEYS */;

--
-- Table structure for table `avis`
--

DROP TABLE IF EXISTS `avis`;
CREATE TABLE `avis` (
  `id_avis` mediumint(8) NOT NULL auto_increment,
  `num_empr` mediumint(8) NOT NULL default '0',
  `num_notice` mediumint(8) NOT NULL default '0',
  `note` int(3) default NULL,
  `sujet` text,
  `commentaire` text,
  `dateajout` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `valide` int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_avis`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `avis`
--


/*!40000 ALTER TABLE `avis` DISABLE KEYS */;
LOCK TABLES `avis` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `avis` ENABLE KEYS */;

--
-- Table structure for table `bannette_abon`
--

DROP TABLE IF EXISTS `bannette_abon`;
CREATE TABLE `bannette_abon` (
  `num_bannette` int(9) unsigned NOT NULL default '0',
  `num_empr` int(9) unsigned NOT NULL default '0',
  `actif` int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`num_bannette`,`num_empr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bannette_abon`
--


/*!40000 ALTER TABLE `bannette_abon` DISABLE KEYS */;
LOCK TABLES `bannette_abon` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `bannette_abon` ENABLE KEYS */;

--
-- Table structure for table `bannette_contenu`
--

DROP TABLE IF EXISTS `bannette_contenu`;
CREATE TABLE `bannette_contenu` (
  `num_bannette` int(9) unsigned NOT NULL default '0',
  `num_notice` int(9) unsigned NOT NULL default '0',
  `date_ajout` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`num_bannette`,`num_notice`),
  KEY `date_ajout` (`date_ajout`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bannette_contenu`
--


/*!40000 ALTER TABLE `bannette_contenu` DISABLE KEYS */;
LOCK TABLES `bannette_contenu` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `bannette_contenu` ENABLE KEYS */;

--
-- Table structure for table `bannette_equation`
--

DROP TABLE IF EXISTS `bannette_equation`;
CREATE TABLE `bannette_equation` (
  `num_bannette` int(9) unsigned NOT NULL default '0',
  `num_equation` int(9) unsigned NOT NULL default '0',
  PRIMARY KEY  (`num_bannette`,`num_equation`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bannette_equation`
--


/*!40000 ALTER TABLE `bannette_equation` DISABLE KEYS */;
LOCK TABLES `bannette_equation` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `bannette_equation` ENABLE KEYS */;

--
-- Table structure for table `bannette_exports`
--

DROP TABLE IF EXISTS `bannette_exports`;
CREATE TABLE `bannette_exports` (
  `num_bannette` int(11) unsigned NOT NULL default '0',
  `export_format` int(3) NOT NULL default '0',
  `export_data` longblob NOT NULL,
  `export_nomfichier` varchar(255) default '',
  PRIMARY KEY  (`num_bannette`,`export_format`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bannette_exports`
--


/*!40000 ALTER TABLE `bannette_exports` DISABLE KEYS */;
LOCK TABLES `bannette_exports` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `bannette_exports` ENABLE KEYS */;

--
-- Table structure for table `bannettes`
--

DROP TABLE IF EXISTS `bannettes`;
CREATE TABLE `bannettes` (
  `id_bannette` int(9) unsigned NOT NULL auto_increment,
  `num_classement` int(8) unsigned NOT NULL default '1',
  `nom_bannette` varchar(255) NOT NULL default '',
  `comment_gestion` varchar(255) NOT NULL default '',
  `comment_public` varchar(255) NOT NULL default '',
  `entete_mail` text NOT NULL,
  `date_last_remplissage` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_last_envoi` datetime NOT NULL default '0000-00-00 00:00:00',
  `proprio_bannette` int(9) unsigned NOT NULL default '0',
  `bannette_auto` int(1) unsigned NOT NULL default '0',
  `periodicite` int(3) unsigned NOT NULL default '7',
  `diffusion_email` int(1) unsigned NOT NULL default '0',
  `categorie_lecteurs` int(8) unsigned NOT NULL default '0',
  `nb_notices_diff` int(4) unsigned NOT NULL default '0',
  `num_panier` int(8) unsigned NOT NULL default '0',
  `limite_type` char(1) NOT NULL default '',
  `limite_nombre` int(6) NOT NULL default '0',
  PRIMARY KEY  (`id_bannette`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bannettes`
--


/*!40000 ALTER TABLE `bannettes` DISABLE KEYS */;
LOCK TABLES `bannettes` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `bannettes` ENABLE KEYS */;

--
-- Table structure for table `budgets`
--

DROP TABLE IF EXISTS `budgets`;
CREATE TABLE `budgets` (
  `id_budget` int(8) unsigned NOT NULL auto_increment,
  `num_entite` int(5) unsigned NOT NULL default '0',
  `num_exercice` int(8) unsigned NOT NULL default '0',
  `libelle` varchar(255) NOT NULL default '',
  `commentaires` text,
  `montant_global` float(8,2) unsigned NOT NULL default '0.00',
  `seuil_alerte` int(3) unsigned NOT NULL default '100',
  `statut` int(3) unsigned NOT NULL default '0',
  `type_budget` int(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_budget`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `budgets`
--


/*!40000 ALTER TABLE `budgets` DISABLE KEYS */;
LOCK TABLES `budgets` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `budgets` ENABLE KEYS */;

--
-- Table structure for table `bulletins`
--

DROP TABLE IF EXISTS `bulletins`;
CREATE TABLE `bulletins` (
  `bulletin_id` int(8) unsigned NOT NULL auto_increment,
  `bulletin_numero` varchar(255) NOT NULL default '',
  `bulletin_notice` int(8) NOT NULL default '0',
  `mention_date` varchar(50) NOT NULL default '',
  `date_date` date NOT NULL default '0000-00-00',
  `bulletin_titre` text,
  `index_titre` text,
  `bulletin_cb` varchar(30) default NULL,
  PRIMARY KEY  (`bulletin_id`),
  KEY `bulletin_numero` (`bulletin_numero`),
  KEY `bulletin_notice` (`bulletin_notice`),
  KEY `date_date` (`date_date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bulletins`
--


/*!40000 ALTER TABLE `bulletins` DISABLE KEYS */;
LOCK TABLES `bulletins` WRITE;
INSERT INTO `bulletins` VALUES (1,'001',20,'àº¥àº²àº§àº­àº±àºšà»€àº”àº”','2006-10-13','àº„àº§àº²àº¡àºªàº²àº¡àº±àºàº„àºµ ','  ',''),(2,'002',23,'àº¥àº²àº§àºà»‰àº²àº§à»œà»‰àº²','2006-10-13','àº”àº»àº™àº•àºµàºàº·à»‰àº™à»€àº¡àº·àº­àº‡àº‚àº­àº‡àº¥àº²àº§','  ',''),(3,'003',24,'à»€àºàº·à»ˆàº­àº—àº³àº¡àº°àºŠàº²àº”','2006-10-13','àº®àº±àºàº›à»ˆàº²','  ','');
UNLOCK TABLES;
/*!40000 ALTER TABLE `bulletins` ENABLE KEYS */;

--
-- Table structure for table `caddie`
--

DROP TABLE IF EXISTS `caddie`;
CREATE TABLE `caddie` (
  `idcaddie` int(8) unsigned NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `type` varchar(20) NOT NULL default 'NOTI',
  `comment` varchar(255) default NULL,
  `autorisations` mediumtext,
  PRIMARY KEY  (`idcaddie`),
  KEY `caddie_type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `caddie`
--


/*!40000 ALTER TABLE `caddie` DISABLE KEYS */;
LOCK TABLES `caddie` WRITE;
INSERT INTO `caddie` VALUES (1,'Notices pour exposition','NOTI','Placer dans ce panier les notices de l\'expo virtuelle','1 2'),(2,'Notices pour retour BDP','NOTI','Remplir ce panier à l\'issue du pointage des exemplaires en retour','1 2'),(3,'Exemplaires pour retour BDP','EXPL','Placer dans ce panier les exemplaires de documents à rendre à la BDP','1 2'),(4,'Notices en doublons sur titre','NOTI','Doublons sur le premier titre','1 2'),(8,'Exemple de panier d\'exemplaires','EXPL','','1 4 3 2'),(5,'Loire - Notices pour thème du mois','NOTI','','1 4'),(6,'Loire - Bulletins contenant des articles pour expo mois','BULL','','1 4'),(7,'Cochon - notices pour exposition mois prochain','NOTI','','1');
UNLOCK TABLES;
/*!40000 ALTER TABLE `caddie` ENABLE KEYS */;

--
-- Table structure for table `caddie_content`
--

DROP TABLE IF EXISTS `caddie_content`;
CREATE TABLE `caddie_content` (
  `caddie_id` int(8) unsigned NOT NULL default '0',
  `object_id` int(10) unsigned NOT NULL default '0',
  `content` blob,
  `blob_type` varchar(10) default NULL,
  `flag` varchar(10) default NULL,
  KEY `caddie_id` (`caddie_id`,`object_id`),
  KEY `object_id` (`object_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `caddie_content`
--


/*!40000 ALTER TABLE `caddie_content` DISABLE KEYS */;
LOCK TABLES `caddie_content` WRITE;
INSERT INTO `caddie_content` VALUES (5,17,NULL,NULL,NULL),(5,19,NULL,NULL,NULL),(6,1,NULL,NULL,NULL),(6,2,NULL,NULL,NULL),(5,42,NULL,NULL,NULL),(5,0,'3370000451297','EXPL_CB',NULL),(5,46,NULL,NULL,NULL),(8,0,'10','EXPL_CB','1'),(7,44,NULL,NULL,NULL),(7,47,NULL,NULL,NULL),(5,41,NULL,NULL,NULL),(5,32,NULL,NULL,NULL),(5,49,NULL,NULL,NULL),(7,50,NULL,NULL,NULL),(7,48,NULL,NULL,NULL),(7,51,NULL,NULL,NULL),(5,25,NULL,NULL,NULL),(8,22,NULL,NULL,NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `caddie_content` ENABLE KEYS */;

--
-- Table structure for table `caddie_procs`
--

DROP TABLE IF EXISTS `caddie_procs`;
CREATE TABLE `caddie_procs` (
  `idproc` smallint(5) unsigned NOT NULL auto_increment,
  `type` varchar(20) NOT NULL default 'SELECT',
  `name` varchar(255) NOT NULL default '',
  `requete` blob NOT NULL,
  `comment` tinytext NOT NULL,
  `autorisations` mediumtext,
  `parameters` text,
  PRIMARY KEY  (`idproc`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `caddie_procs`
--


/*!40000 ALTER TABLE `caddie_procs` DISABLE KEYS */;
LOCK TABLES `caddie_procs` WRITE;
INSERT INTO `caddie_procs` VALUES (3,'SELECT','EXPL par section / propriétaire','select expl_id as object_id, \'EXPL\' as object_type from exemplaires where expl_section in (!!section!!) and expl_owner=!!proprio!!','Sélection d\'exemplaires par section par propriétaire','1 2','<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<FIELDS>\n <FIELD NAME=\"section\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Section]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR=\"query_list\">\r\n <QUERY><![CDATA[select idsection, section_libelle from docs_section order by section_libelle]]></QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=\"\"><![CDATA[]]></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n <FIELD NAME=\"proprio\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Propriétaire]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR=\"query_list\">\r\n <QUERY>select idlender, lender_libelle from lenders order by lender_libelle</QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=\"\"></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>'),(4,'SELECT','EXPL où cote commence par','select expl_id as object_id, \'EXPL\' as object_type from exemplaires where expl_cote like \'!!comme_cote!!%\'','Sélection d\'exemplaire à partir du début de cote','1 2','<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<FIELDS>\n <FIELD NAME=\"comme_cote\" MANDATORY=\"no\">\n  <ALIAS><![CDATA[Début de la cote]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR=\"text\">\r\n <SIZE>20</SIZE>\r\n <MAXSIZE>20</MAXSIZE>\r\n</OPTIONS> \n </FIELD>\n</FIELDS>'),(6,'ACTION','Retour BDP des exemplaires','update exemplaires set expl_statut=!!nouveau_statut!! where expl_id in (CADDIE(EXPL))','Permet de changer le statut des exemplaires d\'un panier','1 2 3','<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<FIELDS>\n <FIELD NAME=\"nouveau_statut\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[nouveau_statut]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR=\"query_list\">\r\n <QUERY>SELECT idstatut, statut_libelle FROM docs_statut</QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=\"\"></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>'),(1,'SELECT','Notices par auteur','SELECT notice_id as object_id, \'NOTI\' as object_type FROM notices, authors, responsability WHERE author_name like \'%!!critere!!%\' AND author_id=responsability_author AND notice_id=responsability_notice\r\n','Sélection des notices dont le nom de l\'auteur contient certaines lettres','1 2 3','<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<FIELDS>\n <FIELD NAME=\"critere\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Caractères contenus dans le nom]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR=\"text\">\r\n <SIZE>25</SIZE>\r\n <MAXSIZE>25</MAXSIZE>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>'),(2,'SELECT','Notices en doublons','create TEMPORARY TABLE tmp SELECT tit1 FROM notices GROUP BY tit1 HAVING count(*)>1\r\nSELECT notice_id as object_id, \'NOTI\' as object_type FROM notices, tmp wHERE notices.tit1=tmp.tit1','Sélection des notices en doublons sur le premier titre','1 2 3',NULL),(7,'SELECT','Jamais prêtés','SELECT expl_id as object_id, \'EXPL\' as object_type, concat(\"LIVRE \",tit1) as Titre FROM notices join exemplaires on expl_notice=notice_id LEFT JOIN pret_archive ON arc_expl_notice = notice_id where arc_expl_id IS NULL AND expl_id IS NOT NULL UNION SELECT expl_id as object_id, \'EXPL\' as object_type, concat(\"PERIO \",tit1, \" Numéro : \",bulletin_numero) as Titre FROM (bulletins INNER JOIN notices ON bulletins.bulletin_notice = notices.notice_id) INNER JOIN exemplaires on expl_bulletin=bulletin_id LEFT JOIN pret_archive ON expl_id = arc_expl_id WHERE pret_archive.arc_id Is Null','Ajoute dans un panier les exemplaires jamais prêtés','1 2',NULL),(8,'SELECT','Sélection d\'exemplaires par statut','select expl_id as object_id, \'EXPL\' as object_type from exemplaires where expl_statut in (!!statut!!)','','1 2','<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<FIELDS>\n <FIELD NAME=\"statut\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[statut]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR=\"query_list\">\r\n <QUERY><![CDATA[select idstatut, statut_libelle from docs_statut]]></QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=\"\"><![CDATA[]]></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>'),(9,'SELECT','Sélection d\'exemplaires par localisation, section, statut, propriétaire','select expl_id as object_id, \'EXPL\' as object_type from exemplaires where expl_section in (!!section!!) and expl_location in (!!location!!) and expl_statut in (!!statut!!) and expl_owner=!!proprio!!  ','','1 2','<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<FIELDS>\n <FIELD NAME=\"section\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Section]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR=\"query_list\">\r\n <QUERY><![CDATA[select idsection, section_libelle from docs_section order by 2]]></QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=\"\"><![CDATA[]]></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n <FIELD NAME=\"location\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Localisation]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR=\"query_list\">\r\n <QUERY><![CDATA[select idlocation, location_libelle from docs_location order by 2]]></QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=\"\"><![CDATA[]]></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n <FIELD NAME=\"statut\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Statut]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR=\"query_list\">\r\n <QUERY><![CDATA[select idstatut, statut_libelle from docs_statut order by 2]]></QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=\"\"><![CDATA[]]></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n <FIELD NAME=\"proprio\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Propriétaire]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR=\"query_list\">\r\n <QUERY><![CDATA[select idlender, lender_libelle from lenders order by 2]]></QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=\"\"><![CDATA[]]></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>');
UNLOCK TABLES;
/*!40000 ALTER TABLE `caddie_procs` ENABLE KEYS */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `num_noeud` int(9) unsigned NOT NULL default '0',
  `langue` varchar(5) NOT NULL default 'fr_FR',
  `libelle_categorie` text NOT NULL,
  `note_application` text NOT NULL,
  `comment_public` text NOT NULL,
  `comment_voir` text NOT NULL,
  `index_categorie` text NOT NULL,
  PRIMARY KEY  (`num_noeud`,`langue`),
  KEY `categ_langue` (`langue`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--


/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
LOCK TABLES `categories` WRITE;
INSERT INTO `categories` VALUES (2539,'la_LA','àº›à»ˆàº²à»„àº¡à»‰','àº›à»ˆàº²à»„àº¡à»‰','','','  '),(2539,'fr_FR','àº›à»ˆàº²à»„àº¡à»‰','àº›à»ˆàº²à»„àº¡à»‰','','','  '),(2538,'la_LA','àºàº¹àº¡àºªàº²àº” àº•à»ˆàº²àº‡àº›àº°à»€àº—àº”','àºàº¹àº¡àºªàº²àº” àº•à»ˆàº²àº‡àº›àº°à»€àº—àº”','','','  '),(2538,'fr_FR','àºàº¹àº¡àºªàº²àº” àº•à»ˆàº²àº‡àº›àº°à»€àº—àº”','àºàº¹àº¡àºªàº²àº” àº•à»ˆàº²àº‡àº›àº°à»€àº—àº”','','','  '),(2537,'la_LA','àºàº¹àº¡àºªàº²àº” àº¥àº²àº§','àºàº¹àº¡àºªàº²àº” àº¥àº²àº§','','','  '),(2537,'fr_FR','àºàº¹àº¡àºªàº²àº” àº¥àº²àº§','àºàº¹àº¡àºªàº²àº” àº¥àº²àº§','','','  '),(2536,'la_LA','àºàº¹àº¡àºªàº²àº”','àºàº¹àº¡àºªàº²àº”','','','  '),(2536,'fr_FR','àºàº¹àº¡àºªàº²àº”','àºàº¹àº¡àºªàº²àº”','','','  '),(2535,'la_LA','àº›àº°àº«àº§àº±àº”àºªàº²àº” àº•à»ˆàº²àº‡àº›àº°à»€àº—àº”','àº›àº°àº«àº§àº±àº”àºªàº²àº” àº•à»ˆàº²àº‡àº›àº°à»€àº—àº”','','','  '),(2535,'fr_FR','àº›àº°àº«àº§àº±àº”àºªàº²àº” àº•à»ˆàº²àº‡àº›àº°à»€àº—àº”','àº›àº°àº«àº§àº±àº”àºªàº²àº” àº•à»ˆàº²àº‡àº›àº°à»€àº—àº”','','','  '),(2534,'la_LA','àº›àº°àº«àº§àº±àº”àºªàº²àº” àº¥àº²àº§','àº›àº°àº«àº§àº±àº”àºªàº²àº” àº¥àº²àº§','','','  '),(2534,'fr_FR','àº›àº°àº«àº§àº±àº”àºªàº²àº” àº¥àº²àº§','àº›àº°àº«àº§àº±àº”àºªàº²àº” àº¥àº²àº§','','','  '),(2533,'la_LA','àº›àº°àº«àº§àº±àº”àºªàº²àº”','àº›àº°àº«àº§àº±àº”àºªàº²àº”','','','  '),(2533,'fr_FR','àº›àº°àº«àº§àº±àº”àºªàº²àº”','àº›àº°àº«àº§àº±àº”àºªàº²àº”','','','  '),(2532,'la_LA','àº”à»‰àº²àº™àº„àº­àº¡àºàºµàº§à»€àº•àºµà»‰','àº”à»‰àº²àº™àº„àº­àº¡àºàºµàº§à»€àº•àºµà»‰','','','  '),(2532,'fr_FR','àº”à»‰àº²àº™àº„àº­àº¡àºàºµàº§à»€àº•àºµà»‰','àº”à»‰àº²àº™àº„àº­àº¡àºàºµàº§à»€àº•àºµà»‰','','','  '),(2531,'la_LA','àº”à»‰àº²àº™àºàº²àº™à»àºàº”','àº”à»‰àº²àº™àºàº²àº™à»àºàº”','','','  '),(2531,'fr_FR','àº”à»‰àº²àº™àºàº²àº™à»àºàº”','àº”à»‰àº²àº™àºàº²àº™à»àºàº”','','','  '),(2520,'fr_FR','àº§àº±àº™àº™àº°àº„àº°àº”àºµ','àº§àº±àº™àº™àº°àº„àº°àº”àºµ','','','  '),(2520,'la_LA','àº§àº±àº™àº™àº°àº„àº°àº”àºµ','','','','  '),(2521,'fr_FR','àº§àº´àº—àº°àºàº²àºªàº²àº”','àº§àº´àº—àº°àºàº²àºªàº²àº”','','','  '),(2521,'la_LA','àº§àº´àº—àº°àºàº²àºªàº²àº”','','','','  '),(2522,'fr_FR','àº—àº³àº¡àº°àºŠàº²àº”','àº—àº³àº¡àº°àºŠàº²àº”','','','  '),(2522,'la_LA','àº—àº³àº¡àº°àºŠàº²àº”','','','','  '),(2523,'fr_FR','à»àº®à»ˆàº—àº²àº”àº•à»ˆàº²àº‡à»†','','','','  '),(2523,'la_LA','à»àº®à»ˆàº—àº²àº”àº•à»ˆàº²àº‡à»†','','','','  '),(2524,'fr_FR','àº§àº±àº™àº™àº°àº„àº°àº”àºµàº¥àº²àº§','àº§àº±àº™àº™àº°àº„àº°àº”àºµàº¥àº²àº§','','','  '),(2524,'la_LA','àº§àº±àº™àº™àº°àº„àº°àº”àºµàº¥àº²àº§','','','','  '),(2525,'fr_FR','àº§àº±àº™àº™àº°àº„àº°àº”àºµàº•à»ˆàº²àº‡àº›àº°à»€àº—àº”','àº§àº±àº™àº™àº°àº„àº°àº”àºµàº•à»ˆàº²àº‡àº›àº°à»€àº—àº”','','','  '),(2525,'la_LA','àº§àº±àº™àº™àº°àº„àº°àº”àºµàº•à»ˆàº²àº‡àº›àº°à»€àº—àº”','','','','  '),(2526,'fr_FR','àºàº»àº”à»œàº²àº','àºàº»àº”à»œàº²àº','','','  '),(2526,'la_LA','àºàº»àº”à»œàº²àº','','','','  '),(2527,'fr_FR','àºàº»àº”à»œàº²àº àº­àº²àºàº²','àºàº»àº”à»œàº²àº àº­àº²àºàº²','','','  '),(2527,'la_LA','àºàº»àº”à»œàº²àº àº­àº²àºàº²','','','','  '),(2528,'fr_FR','àºàº»àº”à»œàº²àº à»àºà»ˆàº‡','àºàº»àº”à»œàº²àº à»àºà»ˆàº‡','','','  '),(2528,'la_LA','àºàº»àº”à»œàº²àº à»àºà»ˆàº‡','àºàº»àº”à»œàº²àº à»àºà»ˆàº‡','','','  '),(2529,'fr_FR','àºàº»àº”à»œàº²àº àº¥àº²àº§','àºàº»àº”à»œàº²àº àº¥àº²àº§','','','  '),(2529,'la_LA','àºàº»àº”à»œàº²àº àº¥àº²àº§','','','','  '),(2530,'fr_FR','àºàº»àº”à»œàº²àº àº•à»ˆàº²àº‡àº›àº°à»€àº—àº”','àºàº»àº”à»œàº²àº àº•à»ˆàº²àº‡àº›àº°à»€àº—àº”','','','  '),(2530,'la_LA','àºàº»àº”à»œàº²àº àº•à»ˆàº²àº‡àº›àº°à»€àº—àº”','àºàº»àº”à»œàº²àº àº•à»ˆàº²àº‡àº›àº°à»€àº—àº”','','','  ');
UNLOCK TABLES;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

--
-- Table structure for table `classements`
--

DROP TABLE IF EXISTS `classements`;
CREATE TABLE `classements` (
  `id_classement` int(8) unsigned NOT NULL auto_increment,
  `type_classement` char(3) NOT NULL default 'BAN',
  `nom_classement` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id_classement`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `classements`
--


/*!40000 ALTER TABLE `classements` DISABLE KEYS */;
LOCK TABLES `classements` WRITE;
INSERT INTO `classements` VALUES (1,'','_NON CLASSE_'),(2,'BAN','àº—àº»àº”àº¥àº­àº‡'),(3,'EQU','à»€àº„àº¡àºµ'),(4,'EQU','àºŸàºµàºŠàº´àº');
UNLOCK TABLES;
/*!40000 ALTER TABLE `classements` ENABLE KEYS */;

--
-- Table structure for table `collections`
--

DROP TABLE IF EXISTS `collections`;
CREATE TABLE `collections` (
  `collection_id` mediumint(8) unsigned NOT NULL auto_increment,
  `collection_name` varchar(255) NOT NULL default '',
  `collection_parent` mediumint(8) unsigned NOT NULL default '0',
  `collection_issn` varchar(12) NOT NULL default '',
  `index_coll` text,
  PRIMARY KEY  (`collection_id`),
  KEY `collection_name` (`collection_name`),
  KEY `collection_parent` (`collection_parent`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `collections`
--


/*!40000 ALTER TABLE `collections` DISABLE KEYS */;
LOCK TABLES `collections` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `collections` ENABLE KEYS */;

--
-- Table structure for table `comptes`
--

DROP TABLE IF EXISTS `comptes`;
CREATE TABLE `comptes` (
  `id_compte` int(8) unsigned NOT NULL auto_increment,
  `libelle` varchar(255) NOT NULL default '',
  `type_compte_id` int(10) unsigned NOT NULL default '0',
  `solde` decimal(16,2) default '0.00',
  `prepay_mnt` decimal(16,2) NOT NULL default '0.00',
  `proprio_id` int(10) unsigned NOT NULL default '0',
  `droits` text NOT NULL,
  PRIMARY KEY  (`id_compte`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comptes`
--


/*!40000 ALTER TABLE `comptes` DISABLE KEYS */;
LOCK TABLES `comptes` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `comptes` ENABLE KEYS */;

--
-- Table structure for table `coordonnees`
--

DROP TABLE IF EXISTS `coordonnees`;
CREATE TABLE `coordonnees` (
  `id_contact` int(8) unsigned NOT NULL auto_increment,
  `type_coord` int(1) unsigned NOT NULL default '0',
  `num_entite` int(5) unsigned NOT NULL default '0',
  `libelle` varchar(255) NOT NULL default '',
  `contact` varchar(255) NOT NULL default '',
  `adr1` varchar(255) NOT NULL default '',
  `adr2` varchar(255) NOT NULL default '',
  `cp` varchar(15) NOT NULL default '',
  `ville` varchar(100) NOT NULL default '',
  `etat` varchar(100) NOT NULL default '',
  `pays` varchar(100) NOT NULL default '',
  `tel1` varchar(100) NOT NULL default '',
  `tel2` varchar(100) NOT NULL default '',
  `fax` varchar(100) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `commentaires` text,
  PRIMARY KEY  (`id_contact`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `coordonnees`
--


/*!40000 ALTER TABLE `coordonnees` DISABLE KEYS */;
LOCK TABLES `coordonnees` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `coordonnees` ENABLE KEYS */;

--
-- Table structure for table `docs_codestat`
--

DROP TABLE IF EXISTS `docs_codestat`;
CREATE TABLE `docs_codestat` (
  `idcode` smallint(5) unsigned NOT NULL auto_increment,
  `codestat_libelle` varchar(255) default NULL,
  `statisdoc_codage_import` char(2) NOT NULL default '',
  `statisdoc_owner` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idcode`),
  KEY `statisdoc_owner` (`statisdoc_owner`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `docs_codestat`
--


/*!40000 ALTER TABLE `docs_codestat` DISABLE KEYS */;
LOCK TABLES `docs_codestat` WRITE;
INSERT INTO `docs_codestat` VALUES (10,'àºšà»à»ˆà»€àºˆàº²àº°àºˆàº»àº‡','u',0),(11,'à»„àº§à»œà»ˆàº¸àº¡','j',0),(12,'àºœàº¹à»‰à»ƒàº«à»ˆàº½','a',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `docs_codestat` ENABLE KEYS */;

--
-- Table structure for table `docs_location`
--

DROP TABLE IF EXISTS `docs_location`;
CREATE TABLE `docs_location` (
  `idlocation` smallint(5) unsigned NOT NULL auto_increment,
  `location_libelle` varchar(255) default NULL,
  `locdoc_codage_import` varchar(255) NOT NULL default '',
  `locdoc_owner` mediumint(8) unsigned NOT NULL default '0',
  `location_pic` varchar(255) NOT NULL default '',
  `location_visible_opac` tinyint(1) NOT NULL default '1',
  `name` varchar(255) NOT NULL default '',
  `adr1` varchar(255) NOT NULL default '',
  `adr2` varchar(255) NOT NULL default '',
  `cp` varchar(50) NOT NULL default '',
  `town` varchar(100) NOT NULL default '',
  `state` varchar(100) NOT NULL default '',
  `country` varchar(100) NOT NULL default '',
  `phone` varchar(100) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `website` varchar(100) NOT NULL default '',
  `logo` varchar(255) NOT NULL default '',
  `logosmall` varchar(255) NOT NULL default '',
  `commentaire` text NOT NULL,
  PRIMARY KEY  (`idlocation`),
  KEY `locdoc_owner` (`locdoc_owner`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `docs_location`
--


/*!40000 ALTER TABLE `docs_location` DISABLE KEYS */;
LOCK TABLES `docs_location` WRITE;
INSERT INTO `docs_location` VALUES (1,'àº«à»àºªàº°àº«àº¡àº¸àº”à»€à»€àº«à»ˆàº‡àºŠàº²àº”','',2,'images/site/bib_princ.jpg',1,'àº«à»àºªàº°àº«àº¡àº¸àº”à»€à»€àº«à»ˆàº‡àºŠàº²àº”','àº–àº°à»œàº»àº™à»€àºªàº”àº–àº²àº—àº´àº¥àº²àº”','àºšà»‰àº²àº™àºŠàº½àº‡àºàº·àº™','àº•àº¹à»‰ àº›.àº™ 122','àº§àº½àº‡àºˆàº±àº™','','àºª.àº›.àº›.àº¥àº²àº§','+85621 251 405','bnl@laosky.com','http://www.bnlaos.org/','logo_default.jpg','logo_default_small.jpg',''),(2,'àºªàº°àº«àº‡àº§àº™à»„àº§à»‰','',2,'',0,'àº«à»àºªàº°àº«àº¡àº¸àº”àº—àº»àº”àº¥àº­àº‡àº‚àº­àº‡â€‹PMB','','','','','','','','pmb@sigb.net','http://www.sigb.net','logo_default.jpg','logo_default_small.jpg',''),(7,'àº«à»àºªàº°àº«àº¡àº¸àº”à»€àº„àº·à»ˆàº­àº™àº—àºµà»ˆ','',2,'images/site/bibliobus.jpg',1,'àº«à»àºªàº°àº«àº¡àº¸àº”àº—àº»àº”àº¥àº­àº‡àº‚àº­àº‡ PMB','','','72500','','','','','pmb@sigb.net','http://www.sigb.net','logo_default.jpg','logo_default_small.jpg','');
UNLOCK TABLES;
/*!40000 ALTER TABLE `docs_location` ENABLE KEYS */;

--
-- Table structure for table `docs_section`
--

DROP TABLE IF EXISTS `docs_section`;
CREATE TABLE `docs_section` (
  `idsection` smallint(5) unsigned NOT NULL auto_increment,
  `section_libelle` varchar(255) default NULL,
  `sdoc_codage_import` varchar(255) NOT NULL default '',
  `sdoc_owner` mediumint(8) unsigned NOT NULL default '0',
  `section_pic` varchar(255) NOT NULL default '',
  `section_visible_opac` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`idsection`),
  KEY `sdoc_owner` (`sdoc_owner`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `docs_section`
--


/*!40000 ALTER TABLE `docs_section` DISABLE KEYS */;
LOCK TABLES `docs_section` WRITE;
INSERT INTO `docs_section` VALUES (10,'à»€àº­àºàº°àºªàº²àº™','',2,'images/site/documentaire.jpg',1),(11,'à»€àº­àºàº°àºªàº²àº™àºªàº³àº¥àº±àºšà»€àº”àº±àºàº™à»‰àº­àº','',2,'images/site/documentaire.jpg',1),(12,'àº™àº°àº§àº°àº™àº´àºàº²àºà»€àº”àº±àº','',2,'images/site/enfants.jpg',1),(13,'àº™àº°àº§àº°àº™àº´àºàº²àº','',2,'images/site/sec3.jpg',1),(16,'àº›àº°àº«àº§àº±àº”àºªàº²àº”','',2,'images/site/sec1.jpg',1),(17,'àº™àº°àº§àº°àº™àº´àºàº²àºàºà»ˆàº½àº§àºàº±àºšàº•àº³àº«àº¼àº§àº”','',2,'images/site/enfants.jpg',1),(18,'àº™àº°àº§àº°àº™àº´àºàº²àºàº•à»ˆàº²àº‡àº›àº°à»€àº—àº”','',2,'images/site/histoire.jpg',1),(20,'à»€àº­àºàº°àºªàº²àº™àºªàº³àº¥àº±àºšà»„àº§à»à»ˆàº¹àº¡','',2,'images/site/sec3.jpg',1),(21,'àº›àº·à»‰àº¡àº®àº¹àºšà»€àº”àº±àºàº™à»‰àº­àº','',2,'images/site/sec1.jpg',1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `docs_section` ENABLE KEYS */;

--
-- Table structure for table `docs_statut`
--

DROP TABLE IF EXISTS `docs_statut`;
CREATE TABLE `docs_statut` (
  `idstatut` smallint(5) unsigned NOT NULL auto_increment,
  `statut_libelle` varchar(255) default NULL,
  `pret_flag` tinyint(4) NOT NULL default '1',
  `statusdoc_codage_import` char(2) NOT NULL default '',
  `statusdoc_owner` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idstatut`),
  KEY `statusdoc_owner` (`statusdoc_owner`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `docs_statut`
--


/*!40000 ALTER TABLE `docs_statut` DISABLE KEYS */;
LOCK TABLES `docs_statut` WRITE;
INSERT INTO `docs_statut` VALUES (1,'àº¢à»ˆàº¹à»ƒàº™àºªàº°àºàº²àºšàº”àºµ',1,'',0),(2,'àºàº³àº¥àº±àº‡àº™àº³à»€àº‚àº»à»‰àº²',0,'',0),(11,'à»ƒàºŠà»‰àºàº²àº™àºšà»à»ˆà»„àº”à»‰',0,'',0),(12,'àºªàº¹àº™àº«àº²àº',0,'',0),(13,'à»ƒàº«à»‰àº­à»ˆàº²àº™à»€àºšàº´à»ˆàº‡àº¢à»ˆàº¹àº«à»àºªàº°à»àº¸àº”à»€àº—àº»à»ˆàº²àº™àº±à»‰àº™',0,'',0),(14,'àº¢àº¹à»ˆà»ƒàº™àºªàº²àº‡',0,'',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `docs_statut` ENABLE KEYS */;

--
-- Table structure for table `docs_type`
--

DROP TABLE IF EXISTS `docs_type`;
CREATE TABLE `docs_type` (
  `idtyp_doc` tinyint(3) unsigned NOT NULL auto_increment,
  `tdoc_libelle` varchar(255) default NULL,
  `duree_pret` smallint(6) NOT NULL default '31',
  `duree_resa` int(6) unsigned NOT NULL default '15',
  `tdoc_owner` mediumint(8) unsigned NOT NULL default '0',
  `tdoc_codage_import` varchar(255) NOT NULL default '',
  `tarif_pret` decimal(16,2) NOT NULL default '0.00',
  PRIMARY KEY  (`idtyp_doc`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `docs_type`
--


/*!40000 ALTER TABLE `docs_type` DISABLE KEYS */;
LOCK TABLES `docs_type` WRITE;
INSERT INTO `docs_type` VALUES (1,'àº›àº·à»‰àº¡',14,15,2,'','0.00'),(12,'àºàº°à»àºŠàº±àº”àº§àºµàº”àºµà»‚àº­',14,15,2,'','0.00'),(13,'àºŠàºµàº”àºµàº•à»ˆàº²àº‡à»†',14,15,2,'','0.00'),(14,'àº§àºµàºŠàºµàº”àºµ',5,15,2,'','0.00'),(15,'àº‡àº²àº™àºªàº´àº™àº¥àº°àº›àº°',5,15,2,'','0.00'),(16,'àºšàº±àº” à»àº¥àº° à»àºœàº™àº—àºµà»ˆ',31,15,2,'','0.00'),(17,'àºŠàºµàº”àºµàº£à»‹àº­àº¡',10,5,2,'','0.00'),(18,'àº§àº²àº¥àº°àºªàº²àº™',8,5,0,'','0.00');
UNLOCK TABLES;
/*!40000 ALTER TABLE `docs_type` ENABLE KEYS */;

--
-- Table structure for table `docsloc_section`
--

DROP TABLE IF EXISTS `docsloc_section`;
CREATE TABLE `docsloc_section` (
  `num_section` int(5) unsigned NOT NULL default '0',
  `num_location` int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`num_section`,`num_location`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `docsloc_section`
--


/*!40000 ALTER TABLE `docsloc_section` DISABLE KEYS */;
LOCK TABLES `docsloc_section` WRITE;
INSERT INTO `docsloc_section` VALUES (10,1),(10,7),(11,1),(11,7),(12,1),(12,7),(13,1),(13,7),(16,1),(16,7),(17,1),(17,7),(18,1),(18,7),(19,1),(19,7),(20,1),(20,7),(21,1),(21,7),(23,1),(23,7),(24,1),(24,7),(25,1),(25,7),(26,1),(26,7);
UNLOCK TABLES;
/*!40000 ALTER TABLE `docsloc_section` ENABLE KEYS */;

--
-- Table structure for table `empr`
--

DROP TABLE IF EXISTS `empr`;
CREATE TABLE `empr` (
  `id_empr` smallint(6) NOT NULL auto_increment,
  `empr_cb` varchar(255) default NULL,
  `empr_nom` varchar(255) NOT NULL default '',
  `empr_prenom` varchar(255) NOT NULL default '',
  `empr_adr1` varchar(255) NOT NULL default '',
  `empr_adr2` varchar(255) NOT NULL default '',
  `empr_cp` varchar(10) NOT NULL default '',
  `empr_ville` varchar(255) NOT NULL default '',
  `empr_pays` varchar(255) NOT NULL default '',
  `empr_mail` varchar(50) NOT NULL default '',
  `empr_tel1` varchar(255) NOT NULL default '',
  `empr_tel2` varchar(255) NOT NULL default '',
  `empr_prof` varchar(255) NOT NULL default '',
  `empr_year` int(4) unsigned NOT NULL default '0',
  `empr_categ` smallint(5) unsigned NOT NULL default '0',
  `empr_codestat` smallint(5) unsigned NOT NULL default '0',
  `empr_creation` date NOT NULL default '0000-00-00',
  `empr_modif` date NOT NULL default '0000-00-00',
  `empr_sexe` tinyint(3) unsigned NOT NULL default '0',
  `empr_login` varchar(255) NOT NULL default '',
  `empr_password` varchar(10) NOT NULL default '',
  `empr_date_adhesion` date default NULL,
  `empr_date_expiration` date default NULL,
  `empr_msg` tinytext,
  `empr_lang` varchar(10) NOT NULL default 'fr_FR',
  `empr_ldap` tinyint(1) unsigned default '0',
  `type_abt` int(1) NOT NULL default '0',
  `last_loan_date` date default NULL,
  `empr_location` int(6) unsigned NOT NULL default '1',
  `date_fin_blocage` date default NULL,
  PRIMARY KEY  (`id_empr`),
  UNIQUE KEY `empr_cb` (`empr_cb`),
  KEY `empr_nom` (`empr_nom`),
  KEY `empr_date_adhesion` (`empr_date_adhesion`),
  KEY `empr_date_expiration` (`empr_date_expiration`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `empr`
--


/*!40000 ALTER TABLE `empr` DISABLE KEYS */;
LOCK TABLES `empr` WRITE;
INSERT INTO `empr` VALUES (1,'1','àº§àº´àºŠà»ˆàº½àº™','à»àºà»‰àº§àº¡àº°àº™àºµ','àºšà»‰àº²àº™àº™àº²à»àº®à»ˆ 04/49','à»€àº¡àº·àº­àº‡àºªàºµà»‚àº„àº” à»àº‚àº§àº‡àºàº³à»àºàº‡àº™àº°àº„àº­àº™','856','àºªàºµà»‚àº„àº”','àº¥àº²àº§','keomany2002@hotmailo.com','020 7 74 12 35','','àº™àº±àºàº‚àº½àº™à»‚àº›à»àºàº¡',13081981,10,7,'2006-10-13','2006-10-13',1,'       1','13/08/1981','2006-10-13','2007-10-13','à»€àº›àº±àº™àº™àº±àºàº­à»ˆàº²àº™àº›àº°àºˆàº³ àº—àºµà»ˆà»€àº‚àº»à»‰àº²àº¡àº²àº«à»àºªàº°à»àº¸àº” àº¢à»ˆàº²àº‡à»œà»‰àº­àº 1 àº„àº±à»‰àº‡/àº­àº²àº—àº´àº”','la_LA',0,0,'2006-10-16',1,NULL),(3,'3','àº§àº´à»„àº¥àº—àº­àº‡','àº§àº»àº‡àº—àº°àºªàº­àº™','àºšà»‰àº²àº™àº—àº²àº”àº‚àº²àº§','àº—àº°à»àº»àº™àº—à»ˆàº²à»€àº”àº·à»ˆàº­ à»€àº¡àº·àº­àº‡àºªàºµàºªàº°àº•àº°àº™àº²àº” àºàº³à»àºàº‡àº™àº°àº„àº­àº™','856','àºªàºµàºªàº°àº•àº°àº™àº²àº”','','vthasone@hotmail.com','020 59 19 571','','àº™àº±àºàº‚àº½àº™à»‚àº›à»àºàº¡',2101978,10,7,'2006-10-13','2006-10-13',1,'        12','02101978','2006-06-13','2007-06-13','àºŠàº°àº¡àº²àºŠàº´àº àº—àºµà»ˆàº¡àº²à»€àº›àº±àº™àº›àº°àºˆàº³','la_LA',0,0,'2006-10-14',1,NULL),(2,'2','àºˆàº´àº™àº™àº°àº¥àº²àº”','àº„àº³àºªàº´àº™','àºšà»‰àº²àº™àº”àº»àº‡àº™àº²à»‚àºŠàº','à»€àº¡àº·àº­àº‡àºªàºµà»‚àº„àº” à»àº‚àº§àº‡àºàº³à»àºàº‡àº™àº°àº„àº­àº™','856','àºªàºµà»‚àº„àº”','àº¥àº²àº§','touy_chinnalath@yahoo.com','020 7 60 78 07','','àº™àº±àºàº‚àº½àº™à»‚àº›à»àºàº¡',25111981,10,7,'2006-10-13','2006-10-13',1,'         1','25 11 1981','2005-07-13','2007-07-13','àºŠàº°àº¡àº²àºŠàº´àºà»€àºàº»à»ˆàº²','la_LA',0,0,'2006-10-13',1,NULL),(4,'4','à»„àºŠàºàº°àºªàº¸àº','àº—àº²àº¥àº»àº¡','àºšà»‰àº²àº™àº­àº²àºàº²àº”','àº—àº°à»àº»àº™àº«àº¼àº§àº‡àºàº°àºšàº²àº‡ à»€àº¡àº·àº­àº‡àºªàºµà»‚àº„àº” àºàº³à»àºàº‡àº™àº°àº„àº­àº™','856','àºªàºµà»‚àº„àº”','àº¥àº²àº§','','020 5123456','','àº­àº­àºà»àºšàºš',2071983,10,7,'2006-10-13','2006-10-13',2,'        1','02071983','2005-11-10','2006-11-10','àºŠàº°àº¡àº²àºŠàº´àºàº›àº°àºˆàº³','fr_FR',0,0,NULL,1,NULL),(5,'5','àº‚àº±àº™àº—àº°àº§àºµàº§àº±àº™','àºªàº»àº™à»„àº¥','àºšà»‰àº²àº™àº”àº­àº™àºàº­àº','àºàº³à»àºàº‡àº™àº°àº„àº­àº™','002','àº™àº²àºŠàº²àºàº—àº­àº‡','àº¥àº²àº§','ksonlay@yahoo.com','020 78 73 573','','àº™àº±àºàº‚àº½àº™à»‚àº›à»àºàº¡',5031980,10,4,'2006-10-13','2006-10-13',1,'           1','05031980','2005-10-01','2007-10-01','àºŠàº°àº¡àº²àºŠàº´àºàº›àº°àºˆàº³','fr_FR',0,0,'2006-10-13',1,NULL),(6,'6','à»àºªàº‡àºˆàº±àº™àº”àº²àº§àº»àº‡','à»‚àºà»„àºŠàºªàºµ','àºšà»‰àº²àº™à»œàº­àº‡àº”à»‰àº§àº‡','à»€àº¡àº·àº­àº‡àºªàºµà»‚àº„àº” à»àº‚àº§àº‡àºàº³à»àºàº‡àº™àº°àº„àº­àº™','856','àºªàºµà»‚àº„àº”','àº¥àº²àº§','abrun@hotmail.com','020 78 33 876','','àº™àº±àºàº‚àº½àº™à»‚àº›à»àºàº¡',7121981,10,4,'2006-10-13','2006-10-13',1,'','07121981','2006-10-13','2007-10-13','àºŠàº°àº¡àº²àºŠàº´àºàº›àº°àºˆàº³','la_LA',0,0,'2006-10-13',1,NULL),(7,'11586-11592','àºàº´àº”àº•àº´àºàº±àº™','àº„àº³àº«àº¼à»‰àº²','àºšà»‰àº²àº™àº—àº»à»ˆàº‡àº›àº»à»ˆàº‡','à»€àº¡àº·àº­àº‡àºªàºµà»‚àº„àº” à»àº‚àº§àº‡àºàº³à»àºàº‡àº™àº°àº„àº­àº™','856','àºªàºµà»‚àº„àº”','àº¥àº²àº§','ktpkhamla@wfp.org','020 55 21 293','','àº™àº±àºàº‚àº½àº™à»‚àº›à»àºàº¡',19061980,10,7,'2006-10-13','2006-11-08',1,'         12','19061980','2005-12-07','2006-12-07','àºŠàº°àº¡àº²àºŠàº´àºàº›àº°àºˆàº³','la_LA',0,0,'2006-10-13',1,NULL),(8,'8','àºˆàº±àº™àº—àº°àº¥àº±àº‡àºªàºµ','àºªàº¸àº¥àº´àº§àº»àº‡','àºšà»‰àº²àº™àºŠàº°àºàº±àº‡à»œà»à»‰','à»€àº¡àº·àº­àº‡à»„àºŠàº—àº²àº™àºµ  àºàº³à»àºàº‡àº™àº°àº„àº­àº™','001','à»„àºŠàº—àº²àº™àºµ','àº¥àº²àº§','soulivongch@ifmt.org','020 57 06 549','','àºœàº¹à»‰àº„àº¹àº¡à»€àº„àº·àº­àº‚à»ˆàº²àº àº„àº­àº¡àºàºµàº§à»€àº•àºµà»‰',26031978,10,7,'2006-10-13','2006-10-13',1,'           12','26031978','2006-10-13','2007-10-13','àº™àº±àºàº­à»ˆàº²àº™àº›àº°àº³','la_LA',0,0,NULL,1,NULL),(9,'9','àºàº»àº¡àº¡àº°àº§àº»àº‡','àºˆàº±àº™àº—àº°àº¥àº²','àºšà»‰àº²àº™à»œàº­àº‡à»àº•à»ˆàº‡','à»€àº¡àº·àº­àº‡àºªàºµà»‚àº„àº” à»àº‚àº§àº‡àºàº³à»àºàº‡àº™àº°àº„àº­àº™','856','àºªàºµà»‚àº„àº”','àº¥àº²àº§','','020 71 32  567','','àº™àº±àºàº‚à»ˆàº²àº§',5071981,10,4,'2006-10-13','2006-10-13',1,'         123','05071981','2006-10-13','2007-10-13','','la_LA',0,0,'2006-11-08',1,NULL),(10,'10','àº›àº²àº™à»€àºàº±àº”','àº—àº­àº‡àº­àº´àº™','àºšà»‰àº²àº™àºªàº²àºàº¥àº»àº¡','à»€àº¡àº·àº­àº‡à»„àºŠàº—àº²àº™àºµ  àºàº³à»àºàº‡àº™àº°àº„àº­àº™','001','à»„àºŠàº—àº²àº™àºµ','','','020 77 84 612','','àº™àº±àºàºªàº´àºàºªàº²',15081987,8,4,'2006-10-13','2006-10-13',2,'        123','15081987','2006-10-13','2007-10-13','àº™àº±àºàºªàº´àºàºªàº²àº¡àº°àº«àº²àº§àº´àº—àº°àºàº²à»„àº¥à»àº«à»ˆàº‡àºŠàº²àº”','la_LA',0,0,'2006-11-08',1,NULL),(11,'11','àº—àº­àº‡àº”àº³','àº”àº³','àºšà»‰àº²àº™àºŠàº°àºàº±àº‡à»œà»à»‰','','003','àºªàº±àº‡àº—àº­àº‡','','','','','àºàº°àº™àº±àºàº‡àº²àº™',13071985,12,4,'2006-10-16','2006-10-16',1,'      1','13071985','2006-10-02','2007-10-02','','fr_FR',0,0,NULL,1,NULL),(12,'12','àº—àº­àº‡à»àº”àº‡','àº¡àº°àº™àºµàº§àº±àº™','àºšà»‰àº²àº™ àº§àº±àº”à»„àº•àº™à»‰àº­àº','àºàº³à»àºàº‡àº™àº°àº„àº­àº™','235','àºªàºµà»‚àº„àº”','àº¥àº²àº§','','020 7 829859','','àºàº°àº™àº±àºàº‡àº²àº™',19121975,10,7,'2006-10-23','2006-10-23',1,'       12','19121975','2006-08-08','2007-08-08','','la_LA',0,0,NULL,1,NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `empr` ENABLE KEYS */;

--
-- Table structure for table `empr_categ`
--

DROP TABLE IF EXISTS `empr_categ`;
CREATE TABLE `empr_categ` (
  `id_categ_empr` smallint(5) unsigned NOT NULL auto_increment,
  `libelle` varchar(255) NOT NULL default '',
  `duree_adhesion` int(10) unsigned default '365',
  `tarif_abt` decimal(16,2) NOT NULL default '0.00',
  PRIMARY KEY  (`id_categ_empr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `empr_categ`
--


/*!40000 ALTER TABLE `empr_categ` DISABLE KEYS */;
LOCK TABLES `empr_categ` WRITE;
INSERT INTO `empr_categ` VALUES (8,'àºœàº¹à»‰à»ƒàº«à»ˆàº',365,'0.00'),(9,'à»€àº”àº±àºàº™à»‰àº­àº',365,'0.00'),(10,'àºàº°àº™àº±àºàº‡àº²àº™',365,'0.00'),(11,'àºàº°àº™àº±àºàº‡àº²àº™àºšàº³àº™àº²àº™',365,'0.00'),(12,'àº„àº»àº™àº«àº§à»ˆàº²àº‡àº‡àº²àº™',365,'0.00');
UNLOCK TABLES;
/*!40000 ALTER TABLE `empr_categ` ENABLE KEYS */;

--
-- Table structure for table `empr_codestat`
--

DROP TABLE IF EXISTS `empr_codestat`;
CREATE TABLE `empr_codestat` (
  `idcode` smallint(5) unsigned NOT NULL auto_increment,
  `libelle` varchar(50) NOT NULL default 'DEFAULT',
  PRIMARY KEY  (`idcode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `empr_codestat`
--


/*!40000 ALTER TABLE `empr_codestat` DISABLE KEYS */;
LOCK TABLES `empr_codestat` WRITE;
INSERT INTO `empr_codestat` VALUES (4,'àºàº²àºàº§àº´àºŠàº²'),(6,'àº­àº²àºŠàºµ'),(7,'àº¥àº²àº§');
UNLOCK TABLES;
/*!40000 ALTER TABLE `empr_codestat` ENABLE KEYS */;

--
-- Table structure for table `empr_custom`
--

DROP TABLE IF EXISTS `empr_custom`;
CREATE TABLE `empr_custom` (
  `idchamp` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `titre` varchar(255) default NULL,
  `type` varchar(10) NOT NULL default 'text',
  `datatype` varchar(10) NOT NULL default '',
  `options` text,
  `multiple` int(11) NOT NULL default '0',
  `obligatoire` int(11) NOT NULL default '0',
  `ordre` int(11) default NULL,
  PRIMARY KEY  (`idchamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `empr_custom`
--


/*!40000 ALTER TABLE `empr_custom` DISABLE KEYS */;
LOCK TABLES `empr_custom` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `empr_custom` ENABLE KEYS */;

--
-- Table structure for table `empr_custom_lists`
--

DROP TABLE IF EXISTS `empr_custom_lists`;
CREATE TABLE `empr_custom_lists` (
  `empr_custom_champ` int(10) unsigned NOT NULL default '0',
  `empr_custom_list_value` varchar(255) default NULL,
  `empr_custom_list_lib` varchar(255) default NULL,
  `ordre` int(11) default NULL,
  KEY `empr_custom_champ` (`empr_custom_champ`),
  KEY `champ_list_value` (`empr_custom_champ`,`empr_custom_list_value`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `empr_custom_lists`
--


/*!40000 ALTER TABLE `empr_custom_lists` DISABLE KEYS */;
LOCK TABLES `empr_custom_lists` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `empr_custom_lists` ENABLE KEYS */;

--
-- Table structure for table `empr_custom_values`
--

DROP TABLE IF EXISTS `empr_custom_values`;
CREATE TABLE `empr_custom_values` (
  `empr_custom_champ` int(10) unsigned NOT NULL default '0',
  `empr_custom_origine` int(10) unsigned NOT NULL default '0',
  `empr_custom_small_text` varchar(255) default NULL,
  `empr_custom_text` text,
  `empr_custom_integer` int(11) default NULL,
  `empr_custom_date` date default NULL,
  `empr_custom_float` float default NULL,
  KEY `empr_custom_champ` (`empr_custom_champ`),
  KEY `empr_custom_origine` (`empr_custom_origine`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `empr_custom_values`
--


/*!40000 ALTER TABLE `empr_custom_values` DISABLE KEYS */;
LOCK TABLES `empr_custom_values` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `empr_custom_values` ENABLE KEYS */;

--
-- Table structure for table `empr_groupe`
--

DROP TABLE IF EXISTS `empr_groupe`;
CREATE TABLE `empr_groupe` (
  `empr_id` int(6) unsigned NOT NULL default '0',
  `groupe_id` int(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (`empr_id`,`groupe_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `empr_groupe`
--


/*!40000 ALTER TABLE `empr_groupe` DISABLE KEYS */;
LOCK TABLES `empr_groupe` WRITE;
INSERT INTO `empr_groupe` VALUES (1,1),(1,2),(2,1),(2,2),(3,1),(4,1),(5,1),(10,0),(11,0),(12,0),(12,3);
UNLOCK TABLES;
/*!40000 ALTER TABLE `empr_groupe` ENABLE KEYS */;

--
-- Table structure for table `entites`
--

DROP TABLE IF EXISTS `entites`;
CREATE TABLE `entites` (
  `id_entite` int(5) unsigned NOT NULL auto_increment,
  `type_entite` int(3) unsigned NOT NULL default '0',
  `num_bibli` int(5) unsigned NOT NULL default '0',
  `raison_sociale` varchar(255) NOT NULL default '',
  `commentaires` text,
  `siret` varchar(25) NOT NULL default '',
  `naf` varchar(5) NOT NULL default '',
  `rcs` varchar(25) NOT NULL default '',
  `tva` varchar(25) NOT NULL default '',
  `num_cp_client` varchar(25) NOT NULL default '',
  `num_cp_compta` varchar(255) NOT NULL default '',
  `site_web` varchar(100) NOT NULL default '',
  `logo` varchar(255) NOT NULL default '',
  `autorisations` mediumtext NOT NULL,
  `num_frais` int(8) unsigned NOT NULL default '0',
  `num_paiement` int(8) unsigned NOT NULL default '0',
  `index_entite` text NOT NULL,
  PRIMARY KEY  (`id_entite`),
  KEY `raison_sociale` (`raison_sociale`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `entites`
--


/*!40000 ALTER TABLE `entites` DISABLE KEYS */;
LOCK TABLES `entites` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `entites` ENABLE KEYS */;

--
-- Table structure for table `equations`
--

DROP TABLE IF EXISTS `equations`;
CREATE TABLE `equations` (
  `id_equation` int(9) unsigned NOT NULL auto_increment,
  `num_classement` int(8) unsigned NOT NULL default '1',
  `nom_equation` varchar(255) NOT NULL default '',
  `comment_equation` varchar(255) NOT NULL default '',
  `requete` blob NOT NULL,
  `proprio_equation` int(9) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_equation`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `equations`
--


/*!40000 ALTER TABLE `equations` DISABLE KEYS */;
LOCK TABLES `equations` WRITE;
INSERT INTO `equations` VALUES (1,1,'keomany','àº—àº»àº”àºªàº­àºšà»€àºšàº´à»ˆàº‡','a:2:{s:6:\"SEARCH\";a:1:{i:0;s:3:\"f_1\";}i:0;a:5:{s:6:\"SEARCH\";s:3:\"f_1\";s:2:\"OP\";s:9:\"STARTWITH\";s:5:\"FIELD\";a:1:{i:0;s:1:\"a\";}s:5:\"INTER\";N;s:8:\"FIELDVAR\";N;}}',0),(2,1,'keo','tester','a:2:{s:6:\"SEARCH\";a:1:{i:0;s:3:\"f_2\";}i:0;a:5:{s:6:\"SEARCH\";s:3:\"f_2\";s:2:\"OP\";s:9:\"STARTWITH\";s:5:\"FIELD\";a:1:{i:0;s:1:\"b\";}s:5:\"INTER\";N;s:8:\"FIELDVAR\";N;}}',0),(3,1,'à»àºà»‰àº§','','a:2:{s:6:\"SEARCH\";a:1:{i:0;s:3:\"f_2\";}i:0;a:5:{s:6:\"SEARCH\";s:3:\"f_2\";s:2:\"OP\";s:9:\"STARTWITH\";s:5:\"FIELD\";a:1:{i:0;s:1:\"b\";}s:5:\"INTER\";N;s:8:\"FIELDVAR\";N;}}',0),(4,4,'à»àº¡à»ˆàº™àº«àºàº±àº‡àº§àº°','àºàº”à»€àº«àºà»‰à»ˆàºàº”à»‰à»€àº«àºàº±àºàº´à»€àº°àº³àºà»‰àºàº°àº²à»ˆàº³àºà»€àº°à»„àºà»ˆàº°àº´àºªàºà»€àºàº”à»€àº«àºà»€àºàº”à»€àº¶àº«à»‰','a:2:{s:6:\"SEARCH\";a:1:{i:0;s:3:\"f_3\";}i:0;a:5:{s:6:\"SEARCH\";s:3:\"f_3\";s:2:\"OP\";s:9:\"STARTWITH\";s:5:\"FIELD\";a:1:{i:0;s:1:\"a\";}s:5:\"INTER\";N;s:8:\"FIELDVAR\";N;}}',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `equations` ENABLE KEYS */;

--
-- Table structure for table `error_log`
--

DROP TABLE IF EXISTS `error_log`;
CREATE TABLE `error_log` (
  `error_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `error_origin` varchar(255) default NULL,
  `error_text` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `error_log`
--


/*!40000 ALTER TABLE `error_log` DISABLE KEYS */;
LOCK TABLES `error_log` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `error_log` ENABLE KEYS */;

--
-- Table structure for table `etagere`
--

DROP TABLE IF EXISTS `etagere`;
CREATE TABLE `etagere` (
  `idetagere` int(8) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `comment` blob NOT NULL,
  `validite` int(1) unsigned NOT NULL default '0',
  `validite_date_deb` date NOT NULL default '0000-00-00',
  `validite_date_fin` date NOT NULL default '0000-00-00',
  `visible_accueil` int(1) unsigned NOT NULL default '1',
  `autorisations` mediumtext,
  PRIMARY KEY  (`idetagere`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `etagere`
--


/*!40000 ALTER TABLE `etagere` DISABLE KEYS */;
LOCK TABLES `etagere` WRITE;
INSERT INTO `etagere` VALUES (3,'Loire','Exposition virtuelle sur la Loire',1,'0000-00-00','0000-00-00',1,'1 4 3 2');
UNLOCK TABLES;
/*!40000 ALTER TABLE `etagere` ENABLE KEYS */;

--
-- Table structure for table `etagere_caddie`
--

DROP TABLE IF EXISTS `etagere_caddie`;
CREATE TABLE `etagere_caddie` (
  `etagere_id` int(8) unsigned NOT NULL default '0',
  `caddie_id` int(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`etagere_id`,`caddie_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `etagere_caddie`
--


/*!40000 ALTER TABLE `etagere_caddie` DISABLE KEYS */;
LOCK TABLES `etagere_caddie` WRITE;
INSERT INTO `etagere_caddie` VALUES (3,5);
UNLOCK TABLES;
/*!40000 ALTER TABLE `etagere_caddie` ENABLE KEYS */;

--
-- Table structure for table `exemplaires`
--

DROP TABLE IF EXISTS `exemplaires`;
CREATE TABLE `exemplaires` (
  `expl_id` mediumint(8) unsigned NOT NULL auto_increment,
  `expl_cb` varchar(50) NOT NULL default '',
  `expl_notice` mediumint(8) unsigned NOT NULL default '0',
  `expl_bulletin` int(8) unsigned NOT NULL default '0',
  `expl_typdoc` tinyint(3) unsigned NOT NULL default '0',
  `expl_cote` varchar(50) NOT NULL default '',
  `expl_section` smallint(5) unsigned NOT NULL default '0',
  `expl_statut` smallint(5) unsigned NOT NULL default '0',
  `expl_location` smallint(5) unsigned NOT NULL default '0',
  `expl_codestat` smallint(5) unsigned NOT NULL default '0',
  `expl_date_depot` date NOT NULL default '0000-00-00',
  `expl_date_retour` date NOT NULL default '0000-00-00',
  `expl_note` tinytext NOT NULL,
  `expl_prix` varchar(255) NOT NULL default '',
  `expl_owner` mediumint(8) unsigned NOT NULL default '0',
  `expl_lastempr` int(10) unsigned NOT NULL default '0',
  `last_loan_date` date default NULL,
  `create_date` datetime NOT NULL default '2005-01-01 00:00:00',
  `update_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`expl_id`),
  UNIQUE KEY `expl_cb` (`expl_cb`),
  KEY `expl_typdoc` (`expl_typdoc`),
  KEY `expl_cote` (`expl_cote`),
  KEY `expl_notice` (`expl_notice`),
  KEY `expl_codestat` (`expl_codestat`),
  KEY `expl_owner` (`expl_owner`),
  KEY `expl_bulletin` (`expl_bulletin`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `exemplaires`
--


/*!40000 ALTER TABLE `exemplaires` DISABLE KEYS */;
LOCK TABLES `exemplaires` WRITE;
INSERT INTO `exemplaires` VALUES (1,'000001',1,0,1,'050',10,1,1,12,'0000-00-00','0000-00-00','','7000 àºàºµàºš',2,0,'2006-10-13','2006-10-13 15:16:43','2006-10-13 15:19:51'),(2,'000002',1,0,1,'050',10,1,1,12,'0000-00-00','0000-00-00','','7000 àºàºµàºš',2,7,'2006-10-13','2006-10-13 15:17:14','2006-10-13 15:21:35'),(3,'000003',1,0,1,'050',10,1,1,12,'0000-00-00','0000-00-00','','7000 àºàºµàºš',2,0,NULL,'2006-10-13 15:18:21','2006-10-13 15:18:21'),(4,'000004',1,0,1,'050',10,1,1,10,'0000-00-00','0000-00-00','','',2,0,NULL,'2006-10-13 15:18:50','2006-10-13 15:18:50'),(5,'000005',1,0,1,'050',10,1,1,12,'0000-00-00','0000-00-00','','',2,0,NULL,'2006-10-13 15:19:11','2006-10-13 15:19:11'),(6,'000011',2,0,1,'001',10,1,1,10,'0000-00-00','0000-00-00','','9600àºàºµàºš',2,0,'2006-10-13','2006-10-13 15:29:24','2006-10-13 15:35:07'),(7,'000012',2,0,1,'001',10,1,1,10,'0000-00-00','0000-00-00','','9600àºàºµàºš',2,0,NULL,'2006-10-13 15:30:02','2006-10-13 15:30:02'),(8,'000021',3,0,1,'000',10,1,1,10,'0000-00-00','0000-00-00','','96000àºàºµàºš',2,0,'2006-10-13','2006-10-13 15:33:14','2006-10-13 15:35:23'),(9,'000022',3,0,1,'000',10,1,1,10,'0000-00-00','0000-00-00','','96000àºàºµàºš',2,0,'2006-10-13','2006-10-13 15:33:52','2006-10-13 15:38:51'),(10,'000023',3,0,1,'000',10,1,1,10,'0000-00-00','0000-00-00','','96000àºàºµàºš',2,0,NULL,'2006-10-13 15:34:28','2006-10-13 15:34:28'),(11,'000031',4,0,1,'500',10,1,1,10,'0000-00-00','0000-00-00','','82000 àºàºµàºš',2,0,NULL,'2006-10-13 15:47:56','2006-10-13 15:48:56'),(12,'000041',5,0,1,'800',10,1,1,10,'0000-00-00','0000-00-00','','170000àºàºµàºš',2,0,NULL,'2006-10-13 15:52:20','2006-10-13 15:52:20'),(13,'000051',6,0,1,'002',10,1,1,10,'0000-00-00','0000-00-00','','13000àºàºµàºš',2,0,NULL,'2006-10-13 15:54:42','2006-10-13 15:54:42'),(14,'000061',7,0,1,'110',10,1,1,10,'0000-00-00','0000-00-00','','7500 àºàºµàºš',2,0,NULL,'2006-10-13 15:58:02','2006-10-13 15:58:02'),(15,'000071',8,0,1,'009',10,1,1,10,'0000-00-00','0000-00-00','','5000 àºàºµàºš',2,3,'2006-10-14','2006-10-13 16:00:17','2006-10-14 08:18:56'),(16,'000081',9,0,1,'789',10,1,1,10,'0000-00-00','0000-00-00','','200000 àºàºµàºš',2,0,NULL,'2006-10-13 16:02:18','2006-10-13 16:02:18'),(17,'000080',10,0,1,'808',10,1,1,10,'0000-00-00','0000-00-00','','20000àºàºµàºš',2,0,NULL,'2006-10-13 16:06:42','2006-10-13 16:06:42'),(18,'000091',11,0,1,'870',10,1,1,10,'0000-00-00','0000-00-00','','700000àºàºµàºš',2,0,NULL,'2006-10-13 16:10:26','2006-10-13 16:10:26'),(19,'0001001',12,0,1,'890',10,1,1,10,'0000-00-00','0000-00-00','','5800àºàºµàºš',2,0,NULL,'2006-10-13 16:13:07','2006-10-13 16:13:07'),(20,'0001002',13,0,1,'120',10,1,1,10,'0000-00-00','0000-00-00','','8000àºàºµàºš',2,0,NULL,'2006-10-13 16:14:47','2006-10-13 16:14:47'),(21,'00001003',14,0,1,'450',10,1,1,10,'0000-00-00','0000-00-00','','78000àºàºµàºš',2,0,NULL,'2006-10-13 16:18:14','2006-10-13 16:18:14'),(22,'0001003',15,0,1,'560',10,1,1,10,'0000-00-00','0000-00-00','','34000àºàºµàºš',2,0,NULL,'2006-10-13 16:20:37','2006-10-13 16:20:37'),(23,'0001004',16,0,1,'870',10,1,1,10,'0000-00-00','0000-00-00','','12500àºàºµàºš',2,0,NULL,'2006-10-13 16:23:11','2006-10-13 16:23:11'),(24,'0001006',17,0,1,'730',10,1,1,10,'0000-00-00','0000-00-00','','73000àºàºµàºš',2,0,NULL,'2006-10-13 16:25:42','2006-10-13 16:25:42'),(25,'000123',0,2,1,'500',16,1,7,10,'0000-00-00','0000-00-00','','10000àºàºµàºš',2,0,NULL,'2006-10-13 16:43:08','2006-10-13 16:43:08'),(26,'000124',0,3,1,'500',10,1,7,10,'0000-00-00','0000-00-00','','25000àºàºµàºš',2,0,NULL,'2006-10-13 16:45:58','2006-10-13 16:46:27'),(27,'AQ3',25,0,1,'000',10,1,1,10,'0000-00-00','0000-00-00','','',2,1,'2006-10-16','2006-10-14 09:10:09','2006-10-16 16:59:54'),(28,'3370000451300',50,0,1,'JR COC',13,1,1,12,'2004-08-05','0000-00-00','','',2,0,NULL,'2005-01-01 00:00:00','2005-06-22 23:15:28'),(29,'3370000451302',51,0,1,'590 BOU',10,1,1,12,'2004-08-05','2004-08-05','','',2,0,NULL,'2005-01-01 00:00:00','2005-08-10 22:25:04'),(30,'33700004500167',53,0,1,'RK ROB',10,1,1,12,'2004-08-05','2004-08-05','','',2,0,NULL,'2005-01-01 00:00:00','2005-06-22 23:15:28'),(32,'6438646236',2,0,1,'R HER',13,1,1,12,'2004-09-13','2004-09-13','','',2,0,NULL,'2005-01-01 00:00:00','2005-06-22 23:15:28'),(33,'1005',58,0,1,'1',10,1,1,12,'0000-00-00','0000-00-00','tester ','100',2,0,NULL,'2006-08-22 17:46:35','2006-08-22 17:47:38'),(34,'11586-11592',60,0,1,'000',10,1,1,12,'0000-00-00','0000-00-00','Ã Â»â‚¬Ã ÂºÂ§Ã ÂºÂ»Ã Â»â€°Ã ÂºÂ²Ã Âº?Ã Â»Ë†Ã ÂºÂ½Ã ÂºÂ§Ã Âº?Ã ÂºÂ±Ã ÂºÅ¡Ã ÂºÅ¾Ã ÂºÂ»Ã Âºâ€¡Ã ÂºÂªÃ ÂºÂ²Ã ÂºÂ§Ã ÂºÂ°Ã Âºâ€Ã ÂºÂ²Ã Âºâ„¢','Ã Â»â€˜Ã Â»â€™Ã Â»â€™Ã Â»â€™',2,0,NULL,'2006-08-24 18:11:59','2006-08-28 14:29:04'),(35,'11586-11593',60,0,1,'009',10,1,1,12,'0000-00-00','0000-00-00','gh,jhg,jdh','100000',2,0,NULL,'2006-08-24 18:14:25','2006-08-25 10:05:57'),(41,'00111',59,0,1,'099',10,1,1,12,'0000-00-00','0000-00-00','','',2,0,NULL,'2006-10-05 17:42:20','2006-10-05 17:42:20'),(37,'PE37',63,0,1,'000',10,1,1,12,'0000-00-00','0000-00-00','Ã Âº?Ã ÂºÂ²Ã Âºâ„¢Ã Â»Æ’Ã ÂºÂ«Ã Â»â€°Ã ÂºÂ¢Ã ÂºÂ·Ã ÂºÂ¡Ã Âºâ€Ã Â»Ë†Ã ÂºÂ§Ã Âºâ„¢','24000',2,9,'2006-11-08','2005-01-01 00:00:00','2006-11-08 15:50:29'),(40,'11602-03',65,0,1,'001',10,1,1,12,'0000-00-00','0000-00-00','','15000Ã Âº?Ã ÂºÂµÃ ÂºÅ¡',2,0,NULL,'2006-10-05 17:19:56','2006-10-05 17:19:56');
UNLOCK TABLES;
/*!40000 ALTER TABLE `exemplaires` ENABLE KEYS */;

--
-- Table structure for table `exercices`
--

DROP TABLE IF EXISTS `exercices`;
CREATE TABLE `exercices` (
  `id_exercice` int(8) unsigned NOT NULL auto_increment,
  `num_entite` int(5) unsigned NOT NULL default '0',
  `libelle` varchar(255) NOT NULL default '',
  `date_debut` date NOT NULL default '2006-01-01',
  `date_fin` date NOT NULL default '2006-01-01',
  `statut` int(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id_exercice`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `exercices`
--


/*!40000 ALTER TABLE `exercices` DISABLE KEYS */;
LOCK TABLES `exercices` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `exercices` ENABLE KEYS */;

--
-- Table structure for table `expl_custom`
--

DROP TABLE IF EXISTS `expl_custom`;
CREATE TABLE `expl_custom` (
  `idchamp` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `titre` varchar(255) default NULL,
  `type` varchar(10) NOT NULL default 'text',
  `datatype` varchar(10) NOT NULL default '',
  `options` text,
  `multiple` int(11) NOT NULL default '0',
  `obligatoire` int(11) NOT NULL default '0',
  `ordre` int(11) default NULL,
  PRIMARY KEY  (`idchamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `expl_custom`
--


/*!40000 ALTER TABLE `expl_custom` DISABLE KEYS */;
LOCK TABLES `expl_custom` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `expl_custom` ENABLE KEYS */;

--
-- Table structure for table `expl_custom_lists`
--

DROP TABLE IF EXISTS `expl_custom_lists`;
CREATE TABLE `expl_custom_lists` (
  `expl_custom_champ` int(10) unsigned NOT NULL default '0',
  `expl_custom_list_value` varchar(255) default NULL,
  `expl_custom_list_lib` varchar(255) default NULL,
  `ordre` int(11) default NULL,
  KEY `expl_custom_champ` (`expl_custom_champ`),
  KEY `expl_champ_list_value` (`expl_custom_champ`,`expl_custom_list_value`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `expl_custom_lists`
--


/*!40000 ALTER TABLE `expl_custom_lists` DISABLE KEYS */;
LOCK TABLES `expl_custom_lists` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `expl_custom_lists` ENABLE KEYS */;

--
-- Table structure for table `expl_custom_values`
--

DROP TABLE IF EXISTS `expl_custom_values`;
CREATE TABLE `expl_custom_values` (
  `expl_custom_champ` int(10) unsigned NOT NULL default '0',
  `expl_custom_origine` int(10) unsigned NOT NULL default '0',
  `expl_custom_small_text` varchar(255) default NULL,
  `expl_custom_text` text,
  `expl_custom_integer` int(11) default NULL,
  `expl_custom_date` date default NULL,
  `expl_custom_float` float default NULL,
  KEY `expl_custom_champ` (`expl_custom_champ`),
  KEY `expl_custom_origine` (`expl_custom_origine`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `expl_custom_values`
--


/*!40000 ALTER TABLE `expl_custom_values` DISABLE KEYS */;
LOCK TABLES `expl_custom_values` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `expl_custom_values` ENABLE KEYS */;

--
-- Table structure for table `explnum`
--

DROP TABLE IF EXISTS `explnum`;
CREATE TABLE `explnum` (
  `explnum_id` int(11) unsigned NOT NULL auto_increment,
  `explnum_notice` mediumint(8) unsigned NOT NULL default '0',
  `explnum_bulletin` int(8) unsigned NOT NULL default '0',
  `explnum_nom` varchar(255) NOT NULL default '',
  `explnum_mimetype` varchar(255) NOT NULL default '',
  `explnum_url` text NOT NULL,
  `explnum_data` mediumblob,
  `explnum_vignette` mediumblob,
  `explnum_extfichier` varchar(20) default '',
  `explnum_nomfichier` text,
  PRIMARY KEY  (`explnum_id`),
  KEY `explnum_notice` (`explnum_notice`),
  KEY `explnum_bulletin` (`explnum_bulletin`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `explnum`
--


/*!40000 ALTER TABLE `explnum` DISABLE KEYS */;
LOCK TABLES `explnum` WRITE;
INSERT INTO `explnum` VALUES (1,42,0,'Reproduction basse qualitÃ©','image/jpeg','','ÿØÿà\0JFIF\0\0H\0H\0\0ÿáÕExif\0\0MM\0*\0\0\0\0\0\0\0\0\0\0\0\Z\0\0\0\0\0\0\0b\0\0\0\0\0\0\0j(\0\0\0\0\0\0\01\0\0\0\0\0\0\0r2\0\0\0\0\0\0\0†‡i\0\0\0\0\0\0\0œ\0\0\0È\0\0\0H\0\0\0\0\0\0H\0\0\0Adobe Photoshop 7.0\02004:08:04 18:34:44\0\0\0\0 \0\0\0\0ÿÿ\0\0 \0\0\0\0\0\0ÿ \0\0\0\0\0\0Q\0\0\0\0\0\0\0\0\0\0\0\0\0\0\Z\0\0\0\0\0\0\0\0\0\0\0\0(\0\0\0\0\0\0\0\0\0\0\0\0\0&\0\0\0\0\0\0\Z§\0\0\0\0\0\0\0H\0\0\0\0\0\0H\0\0\0ÿØÿà\0JFIF\0\0H\0H\0\0ÿí\0Adobe_CM\0ÿî\0Adobe\0d€\0\0\0ÿÛ\0„\0			\n\r\r\rÿÀ\0\0T\0€\"\0ÿİ\0\0ÿÄ?\0\0\0\0\0\0\0\0\0\0	\n\0\0\0\0\0\0\0\0\0	\n\03\0!1AQa\"q2‘¡±B#$RÁb34r‚ÑC%’Sğáñcs5¢²ƒ&D“TdEÂ£t6ÒUâeò³„ÃÓuãóF\'”¤…´•ÄÔäô¥µÅÕåõVfv†–¦¶ÆÖæö7GWgw‡—§·Ç×ç÷\05\0!1AQaq\"2‘¡±B#ÁRÑğ3$bár‚’CScs4ñ%¢²ƒ&5ÂÒD“T£dEU6teâò³„ÃÓuãóF”¤…´•ÄÔäô¥µÅÕåõVfv†–¦¶ÆÖæö\'7GWgw‡—§·ÇÿÚ\0\0\0?\0ÙÊ»¬:§W‚ê«µØ÷­õA®¼Wş‘Íciİmµûm«é~ÒBvê´\'+:»\rÚzŸe-¥¬Æ³Ô}ÿ\0gËgé¿[ı6OØ¿Â~ŸÓ¬¹öd¶¡U5ÚçêìRÖXHüûšcßÿ\0m?Óÿ\0½5X¹6’Û¬Ëu[7>ŠŞF¦Û*;kßuWnû?±›êÿ\0‹ı\"ƒorØÊË¹´»ìµ2ëÆÀ+cƒà—3ÔŞØ§ó}_{-ÿ\0‡S·\'Ş£¨4M$YhuğÆ9íÊ®ÃµŒÆÛ]OgÚœÿ\0R›ë¿õÕ¿LÑèe/ÍïÛú©©Ö1ícƒ™Fë)Æ§}{¿›ÿ\0³Ã›Œì\\ªÜ\r!İãëXbÇæïfëE”ÿ\0m^üŒŒ‹§G*ÆÓKú¨n÷½ì´–Õ[™anm¾À×3Õc1ö5ú_WÕµIì¶™[“5Èi%ş‹Üûlnß]­ßô=?§öuBºÛikq[C­}˜aÅ{œi>öÃ]k\Z÷±Ï~O©{*Äº«nşeXµ­Äf@£$ÙcC€ôC­$ºû±«h²§d?ß«Óÿ\0ç½:èõ™üêHdûı&Üç[OªÆ¶\Zìkœ=j·îc}]–~‚·¿Ô÷úÖ~´î¦—¹ÙX™µ¸ÔÒóéTëÚêî­·~ŠÍŸ÷•{ÿ\0Ié}ÏERe~¯Y½ã.cÛSi!›Èu®ÄÈ¢†µ–û=?K/óÿ\0ë{ëú‡Ù2K¯;CqÃqõš¶SÔ}OfßK\ZÛi·ÖİïÆ»ô¯Ù½D¶×U—sh{ª¼Vµ\nÃEß‡HõÚëÿ\0ZÒú–¿Ôõ+ıø!UuxôQ]İPXk¥µ:ÇšfÇO¥N[Ûsÿ\0µXÿ\0fï^Æe6úıE³kô€9\Z7m‚±C¬.Ö+ÜÖí{¶ß³-»}i}?èşœœæß^Ã“´ä¼G§C‰$–×[™êz­g§”ÊîõüãêşoÑIZ-~^M6ÕoQ›XışÊÛéÚÆ~‰öŠŸµÿ\0C#õÛöÖzõ²¤±zƒòhÆºüË«õã#ô6Œƒ¸¹˜Û½[öK¨İê?Ó»ôô²}ŸÍ(Süš]n6YnFU±··[gª[ƒ{è5şm\rÇe·ı:¶~µúõ\nï~%•fe}Œµ²ë)p6mu­a·&Ê˜ï^Ç2œw³şÔÿ\0èR(H3Ù]ÿ\0¥ûnÜ02AâÍçnOèqèÜÿ\0æôı7ş’Ú?H§öûér\rnmomn/ßCqì§ôN­‚½ö~åŸ¤ı\"©N=W`±ì³#,zhûKksæísªºº=&·Õu/eõãmı%ì¯ó?EdéâÔçŒ¬‡·Ó5äz„EÇ¤dí¬·õk=O^Úíı-U‰„¯¿\ZßÓ2ì}³[¦°=\'šíªÆ¶¿s*uoeé½øËmY²ÌW¾üƒ¿©bRçä6“ÏOìõ6¶WWè[±ÿ\0Íû*ºÿ\0çÿ\0¹n5¤€ö[v÷lu¢İ¬ih{në\\ÇWé»õ{wÿ\0:ÏCı\ZÉxÇn^\0sMo·©t÷Ö-~÷nµ®cZµ–ìuı¾£ë«ıé5t>OÿĞßÊeĞ×\nıBÈÚÚé:}ÜY¦íÌŞ£¼İ]­Ìw¬ßL¹¯€\ZH·Ô¯Ñ±ı¿àıDkó1.­…í±Ì–»ô˜ÆÁ,÷6ê¬İ]µîşwè~gøEDÙMëI¿Bê©¦êçÒfËfÏÕk¶Ûı_Ô»Ôôÿ\0I±A}ÙİK³²F;œ™y/õZê=à›=V7èÙ·‡ì¹tÛ]¯Ğ¯Õz¡”Ë¤³ùXÎ/»´±®g¦jm›÷ì{íÇÙú3õº?XÆõ?Kuh³§Uë¸İ{};1êõí¹­n=[E¶Øú}Öìõ¯õ¾ÏëşŸÔ·ÔQuöÖê«9W6ÚËê©×ÒïUµõdcÜr7í§ëS›şş‚WÕT«k½õW…e¹O§.Ó‹¦Ê©h§ šÿ\0K²¦5Í©®f5V3õµYêÁ«Y™l·\Z¬Òüûé|8×²¦ÙP­¿ks>¶úíª¬øSÕTƒqín5¶nªÛv#M¢ÍƒíÔßHk½VZÜW:ıŒı%U£¸úÙeõï´6·>ò÷mÊíö[ê;İêcn»ş1é*‘à».Êmv>^MÜRÜeMØëö­¬p¾–z÷2ÿ\0³lgó?Ÿúe2î¿1—/­•PÛ[H¨8°¸5ßÒ=)·m5å`lgô¯Ğÿ\0¥eÉÙ}G¬‹ê{¶½­{ö\ZŞá‘Šæ³(>ŸÖ=+²*ôwïı\'è‘è¢ê«·ÓÊÈ¨ââë™ë6fÆm³í\rg»ú7§êz¾>ûjô’µS[2šÙ~Ñ—m>¿M»\Zí­#í1c­©×lÈgè,·}¯fÿ\0ğ^Ÿ¨Š,¾ªi¶Ëó­¶†ş–æÖÙnêŸêØÌ?IŒÈ©]«9–ıçÑe?ákNjkCq¡áÙ.mNÇºÖ†¾›ËëÈœ]¬ŞìVä×‡½¾Ÿ©ô?™¹K/´ÕÔé~UØ—=».³)¾›u¾\'ÛŠìši£ô¿¢¶ëjÿ\0êzL·;ÒÇ³5ïk]*©¬xÖ»1ÿ\0NºÙö»_¿ôÌö?ô¯¥ú%6«¸¾ª³Km\"ÂÇ:²={âşï[wé?í6%”o¡ÊÛmÇw¯‘E0ç›³åÖÙíì–ÑSöåú[mÿ\0´”ãäUş\r+ºK,\"qXêÍMe™is.ul\'&­¶7Û]Tä7ßu³í^ªJY·æÖúè³.çKŸ]í¹Ğv\ZáÖ×Øÿ\0Òûë¯ô?÷¿C}4Fìl§z5SÓ-ôÜ÷o&×\0Ò,¨7{\Z,İŠ÷_m³eX_«şY?±…N,ºšé&·Y9ïØ*µ¦¶ŞÇ»s=ÕÛéãUüŞG©üåjÈéÔ{“ƒS±ì\05öŒV–S[6½Ïª»)ô½Z­µÔÿ\0ÜzYèş·ê¤—hõıLZšàç\n™qõ[éµîô C\\Ç]E×z46ßÑ¿ô*†)Ç¿\'*“UÏvFË\ZĞçm±Ø¶zÎ®Ò]¾¿Wùöı?á½Eaùx!÷úg›pÃ1İe8ö=õzWÚßMŒ³}vSíºš½»ñò¾Ñgø%›Ÿ‘Ôòz§JºŒË¾Å_RÃ«\'ÖÂ>ĞÍ™föÿ\09M×lôqßôÿ\0¥3ü\'¤Fô¢t=_ÿÑßc:—§OØ[özı·ØÖµÍ\rs-slvğçûmgĞª¯RÇúU„K»ZÊîê°÷ØqÎ3ÚÖØıhû;]·Ò©û=;ú/ø4ªÅôRÏIÎ®M4¿~Çs6ZÚÿ\0ÔÙú7×úOÑ~…«ÙkËq2I,&ËSšğ4¥M¶5íöûŸüÏ¯ÿ\0 å32]UMv^-ÕŸJËšúQs]«ƒÉg©nM¾‹¶9Ev{ÿ\0Á¨Y“…nı?%¸.elum¬9ÀEÃÓÇ±ïsñ}Ò2—z¿h©Ÿ¡­Ôİlnnfm¼5âºØÇ:±6Ín}LÛê?ÔfÏç,ªŠ™_úMã8”;ôX¶\\Ë™}\"íõ²Y‘q.Û][­Åwó¬ÿ\0Ï©)|Û±uyU¿<¼±–ÎÃèZá‘k¬k™ı+Ö«ÔÙÿ\0\n«»3Êí£\"ì3M€1­këê5_ë]][j²ÚoÙ¯¯ùÌtÙ°fcÔÛ-û;ìq°hiªÊ›e!®}[™é\\Û+gı¨ı?é›­ÔàãVY’ğ1¨­æ§:_c‰ŒÍîhõ{ëıaïı-^Ÿ©ú?µ$®ˆl³§²ÏNê±^àö¹•zz8=±®¢ÇWúKö5ÖeìgèiôŸuSSİE7Ş\r´şŒ9–9˜÷;Ü×3}UUCk«éú˜ÿ\0àÕ·ÚÆ\\*6ÙSqƒÚ\\ÊÒE¬ôj³ÏcİK±O^ÿ\0Ñ{î÷×g¤­¶ìimùOkF×M2\Zæl®ÛYs­±¾æúÌŸÒ£A.]l.sólf3(±ûÏÚ+ı(%´bÛ±öïºÏVÚ¾—£±ş·ü¼mÍÙm—`“CŞj\0X³ë>“+k27SE•ì³}Ÿ¤µk}OÉËºòèš=7G´úx¥ z{Yèı¦ÏÑûÿ\0L+²ê‚ì‡7\"¦ÍVca{}¿h«ĞÕw¾ª½?æ¬³ı-oI^\r©¿qnHa¦êík¬8äµ¡­¦¦ãåXØ÷ÚÜ‡ú³Òÿ\0ˆ³ÔÄi{ÚáF@®Ë÷¸Æ\nlõêºÆl¿ôz×Sÿ\0]ªµaôåÛe•Ù—2r+õl®Áï¥¶Qú+PmuµÍÈÇgı¿üß¤‹ûO\ZŠİW¯”ûCCßMlÎ{ˆû	u~‹7_³\'ÙWèìÿ\0	éş‰ k/­õCi©îÜlp¡©µ†}£nQ³g¢ÍÖo®Ö„ı§ôëF½Ùî®º¬Î\0À{.¦‡Ua®ÍÌ†zAÛ=V{m~”~‹+Ùú;ÛÖ’òÒ+s\Z ¸û¬sXÖîû;¿Koú±däÙÌZÑêoõœö—¼‹¾ÑeV“éÚêmcMVÕüİzŸ¤û™Æ²šòì6Ô\Z\r®m\'ØúHÈ·ùÇı±áîõ?Òz—äú>¢ÇıìŞ£Ò,³¨ÙaÂÊÄ¼Ğ6_o«S™¿ô?àÿ\03ÑØÍöÂúkÙ¸·1»Şç8[,$M—ymuìıı/ğv6©Xæ³/k­¢rñlÚ÷K_‘M-©•ĞíŒf÷şşÜ³ÔôĞU£ÿÒÓgQøl>6ZÂçĞ/Üß{>¥wWüÛÙ[²=ÿ\0Î{òº‹E•oÁª¶:šŞÌ“Qmo{oºÚn§_­[1ıZjİüÏè¿CR¦]K«¨1ÕZ&ÏY¥Ãk 6¶G·ó]üçæ~“b»NGÉ_M9eÍ\r{>“cŸ8÷mÛôYîşcóÿ\0J«Š¶ÁÙ»……qx}¸×±ó½¬m¡íkÿ\0ÑÖáum~.Ë/ªÏÒ3üª¯^vØÆ2¬·¿sÁ‹klW`ßcœÏWÓô°İ²ºıoæü×©ş;%Î²ÑÌj‹f—ºÆŞêìSe¶z7_ô.¦ºŸNÊ™ôìşwü\ZÈÄŒVSZÆZi§!ö^6Ê6ş™¯uz?ü?Ù?ãlE	.°äõYnS~Ò-dn¤\ZØÆÍæúV[[?Af÷ú[ÿ\0Mé©°;(Xl·(Sc¬n9sj–z?j¯{\Zê÷ú•úşÛ}?G/ôÿ\0Íª.n^c+ÆU2—ïÉ¹÷µöš…/sèk›{ı?Rë*¯e?è=\\‹««ÓõM›”ërEÕÓFÜw:ÒÖˆ/g§xı6¹•~—}–ş±şÔII²²z•\0¾×[¢§ÜÒÊ˜ÿ\0Qÿ\0wé¬ÿ\0H¡nM”Ræ8e]c®¦¼¸eu\nšæ7~E[k.úWú¯oóÖş—ìş8ş•Q²Ì–¶‹+¦ÖØÇÃÈ½¥›\r[¬mU=†ı¤6›[{?K[şÑôÿ\0›3Û,fTì†ZE‚æ»ii­Ïx{÷UfÌ¯SõĞ²»=ŸÌ¤¥Ë0ò›X´eßeNÑcCXE•–Š+·ØÊÛëälô>ĞË?Iÿ\0Z—QmÕÑëcÑ“f úD±˜akêXÏsjôŞö1õşÑéÂz–\nª-}ÍË¢›íuuz‚¯\\4±çÓ®Ú½?SmvãR÷å:‹¿Éª¯øÅv]ÕÃ2ª®§1Íı)â­û`ûNíÖúeöı?Qì³ÔıO­y+ÍXur›]ßhk%í}–½ °4½®Ùk˜Ö½Õû÷ÿ\0Å«8ÎªÂÖåcdÑ‘2Æ·k\\ò÷ººœÇ³Õı#=œE«=+.ı\n¢Ìg2ªqoÄºú®kè¶»oı.ëX}<l¦6ëYS=l©÷ı:n®·ÿ\0†ûJF›¬ËmÃ±µ‹÷Øûçm°³ÕÇº·6Û«oªë¯§#ôÌØ’›Ã²ìF—a¾¼Ù®ß@dï`¶—¶ê«eqıGïÔßøõÓ«y¾‡â=¬ş¶äï²ÊÁ\'Ôu}UÙc6zÊ8U1øë:s¨§&·úŒeÑ´<3ÖaôıÍ¶»m·§úv}šÛ)şuçß“còmÁ¥‚Æ\nİï“ú3¾–Ó-ÚæV÷_’ıÿ\0Íığ¨j¦.Ç{ö~„0·ôçpm.u”ÿ\08ßw?¥ÿ\0Õ*“‹:u®¬5ÙK	ÔI>yTÕC«ßô}M—?ùtzi­ÆŠr=\nEïkşĞ´Ñ…WÑÎwªß~KjßU­ôñiõ}E[#+\'í½=¶ÕMO³«ôúÜwC[.µØ”6½§ÙéÓêÿ\09ôÿ\0Iü„F²2Ò\'ÉÿÓÓÆÏn9qc‹w9ÆØÑ µ›·3Úß ß§şùv#áãWvGìàÂÚXsK‹=İı%t×MŞïgé=?ç–oM±”·}í®ªM[=ÁÏl‡îÜçÏÑ7wó_é–•]~¦5xµ¸XëœıÎ‚Æ–İS¶zÌı}uíÿ\0	ş\rWl^Œòí¸0Yö[®·sko¦ZH,²§±»ı?I”7üÓõ¿ëˆîéøM±Î·¼1Œs2Ÿp.›uSë¶Ê=7¹¯õê=o¤¡CY…Qô±Æ;Ul¬³ ÔáX‹ìÇ-ÜÖã5–³ÒúoßW©èú¤N2^Ák^rím72r{Yc+ıÖ½–ÓöWïôÿ\0ôeÈ¡­]u»\'¿Ş¶ÜËnö»ì¸ï~ÛkÙú:[¿şÚM~¥.Á’CÙX}íxs‰Õ…ÛÏ²í¿JÖ~‹ú:“v?,ÙèÚ=B÷Û´ı–¬mÕow¶§ı?üâÔà_“Œël{i¥îu‡hãån-k¾ÍWéïı§}~§§ş•%uáYôpŞâÆ¹Í`±­ÄÑomîôÜû=¾§óu)ßŒZËF\0¶\ZÚ*ÓÓ5·Öëwlõ­ôıFş“ı/ø4\\|&b8XÊì\'Ôm¡‚ÖÖƒX~·ä†»ù»Oó´şUËÄ~>;j«\'$W¸Ù{ni{ì·cÛmÕı£{¾—§÷ÿ\0¡}Ş•´ ¤•TÚór²-Ş§ª-6œ´»m[v[[›ÿ\0qıßÎúèÑ=*òX¯Ö5‘p¼dF¬pÙ]ïŞç»Ö»éÿ\0¢ÿ\0ŠX‡.¶ì¯uli»Òu¤8\\Óe~ƒlg«¿Ôcšú÷úOõ¶²¥n¼Lœr}\në¸G±¯{› Ãls™ü¯ú†\"Bm»N6¯kßI®û%¹\"ë_¸;k«¤~‰Ş–Nöî÷úÕz_à¿Â!ºŠ1ÍnÈÅ­›[7»×±£p`Ùe^÷û›ÿ\0j?IUáÂ*ÖWn÷z”TæÚúš$9´ì\rÈOôşKwSÿ\0gùãû3[LŸA†¹sÖÖ¼µÛœÊ÷{\\úƒÿ\0E»Ô½$$êC¦NÜŠ1ß[¶¶ËíÓÒm¾–]WWî±Í«c}Œõjşü¨“—ÒmÅ¹ÆÇô-®ĞÒë¬6môÛW­e^ïQŸg«ÔLq¯!ÿ\0fº—ÚÁml¬5„š\0m>“êUéXÇUì¿ÓôîD{_ˆ÷œH~CÎæ+®’ïe¹WÃŸ¿+Û²œ:½”Vÿ\0_ô?iı\ZÑ:¢ûC=zˆ4?\'-ÎvMLc¬{ı\'YGìÖ;ØË1}RÜ‹¿¢şƒùï³Ù†²ò3\Zz—ÕìJİ‡¬c¾Æ±›ì®ÆÖüŸQ®uvÿ\0;³ÿ\00W.µØì5Õ•kîÈs¬ÌÉ`\r¶Ç>ÇU¿èÓú_è>Ÿ³ş1c>À~±ı\\¨’lF§Ù €­ÀÖÏÒF q²>’ÿ\0ÿÔĞé±\rô¾Õ¾íßg¤}>}ŸÏ×à³µz›6SÔô·F›½_Íß¿ùÏøEóòJôú¶>×İïıŸö‡úŞŸ¯ê7Ößènõv7Ğÿ\0á¾ÏézÈUlı-Ù·é7dzqêkéíÿ\0†ú^šñ$WÚûE_³¶³Óô£ÔŸóÒ%şí¾ß´?·Ó÷ÿ\0¦Fgìï^³êzwz;cè~ƒÖß>ÏSw¥èíÿ\0öğ~¢ñ‘Sí¹_c‡íõ·i·ù¨ŸÍWù¾–?¡ú)û^ı¬˜ôcÕ×tGø=Û‰$—Ú¡ô}ËÔ~×ÈÛ?gÛ0w}r~´>}}›„îû,oüïíîúkÃÒCíQú>Í­ö{~Ïëı¦G£³ì[wGè÷‚şsèoüôlŸK{gíû=AèFÍ»àú{•·ÔÚ¼M$~ßÚ¯ñ_dwÙ÷·úDí>”ú²}û=oÑúj¾›„}«nÆÎÏ°í‰Óù¿~ÏøÕäi%öıUö>µw§µû¾Ó>ÍÛ>É¶5Ûô½¿Gò=oç=ë77öoüìè[>×»íxş†ÿ\0OÓŸ´Ñ¿ø_Wı7ıkü\Zót‘‡ÌÏåÿÙÿí> Photoshop 3.0\08BIM%\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\08BIMê\0\0\0\0¦<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<!DOCTYPE plist PUBLIC \"-//Apple Computer//DTD PLIST 1.0//EN\" \"http://www.apple.com/DTDs/PropertyList-1.0.dtd\">\n<plist version=\"1.0\">\n<dict>\n	<key>com.apple.print.PageFormat.PMHorizontalRes</key>\n	<dict>\n		<key>com.apple.print.ticket.creator</key>\n		<string>com.apple.printingmanager</string>\n		<key>com.apple.print.ticket.itemArray</key>\n		<array>\n			<dict>\n				<key>com.apple.print.PageFormat.PMHorizontalRes</key>\n				<real>72</real>\n				<key>com.apple.print.ticket.client</key>\n				<string>com.apple.printingmanager</string>\n				<key>com.apple.print.ticket.modDate</key>\n				<date>2004-08-04T16:32:30Z</date>\n				<key>com.apple.print.ticket.stateFlag</key>\n				<integer>0</integer>\n			</dict>\n		</array>\n	</dict>\n	<key>com.apple.print.PageFormat.PMOrientation</key>\n	<dict>\n		<key>com.apple.print.ticket.creator</key>\n		<string>com.apple.printingmanager</string>\n		<key>com.apple.print.ticket.itemArray</key>\n		<array>\n			<dict>\n				<key>com.apple.print.PageFormat.PMOrientation</key>\n				<integer>1</integer>\n				<key>com.apple.print.ticket.client</key>\n				<string>com.apple.printingmanager</string>\n				<key>com.apple.print.ticket.modDate</key>\n				<date>2004-08-04T16:32:30Z</date>\n				<key>com.apple.print.ticket.stateFlag</key>\n				<integer>0</integer>\n			</dict>\n		</array>\n	</dict>\n	<key>com.apple.print.PageFormat.PMScaling</key>\n	<dict>\n		<key>com.apple.print.ticket.creator</key>\n		<string>com.apple.printingmanager</string>\n		<key>com.apple.print.ticket.itemArray</key>\n		<array>\n			<dict>\n				<key>com.apple.print.PageFormat.PMScaling</key>\n				<real>1</real>\n				<key>com.apple.print.ticket.client</key>\n				<string>com.apple.printingmanager</string>\n				<key>com.apple.print.ticket.modDate</key>\n				<date>2004-08-04T16:32:30Z</date>\n				<key>com.apple.print.ticket.stateFlag</key>\n				<integer>0</integer>\n			</dict>\n		</array>\n	</dict>\n	<key>com.apple.print.PageFormat.PMVerticalRes</key>\n	<dict>\n		<key>com.apple.print.ticket.creator</key>\n		<string>com.apple.printingmanager</string>\n		<key>com.apple.print.ticket.itemArray</key>\n		<array>\n			<dict>\n				<key>com.apple.print.PageFormat.PMVerticalRes</key>\n				<real>72</real>\n				<key>com.apple.print.ticket.client</key>\n				<string>com.apple.printingmanager</string>\n				<key>com.apple.print.ticket.modDate</key>\n				<date>2004-08-04T16:32:30Z</date>\n				<key>com.apple.print.ticket.stateFlag</key>\n				<integer>0</integer>\n			</dict>\n		</array>\n	</dict>\n	<key>com.apple.print.PageFormat.PMVerticalScaling</key>\n	<dict>\n		<key>com.apple.print.ticket.creator</key>\n		<string>com.apple.printingmanager</string>\n		<key>com.apple.print.ticket.itemArray</key>\n		<array>\n			<dict>\n				<key>com.apple.print.PageFormat.PMVerticalScaling</key>\n				<real>1</real>\n				<key>com.apple.print.ticket.client</key>\n				<string>com.apple.printingmanager</string>\n				<key>com.apple.print.ticket.modDate</key>\n				<date>2004-08-04T16:32:30Z</date>\n				<key>com.apple.print.ticket.stateFlag</key>\n				<integer>0</integer>\n			</dict>\n		</array>\n	</dict>\n	<key>com.apple.print.subTicket.paper_info_ticket</key>\n	<dict>\n		<key>com.apple.print.PageFormat.PMAdjustedPageRect</key>\n		<dict>\n			<key>com.apple.print.ticket.creator</key>\n			<string>com.apple.printingmanager</string>\n			<key>com.apple.print.ticket.itemArray</key>\n			<array>\n				<dict>\n					<key>com.apple.print.PageFormat.PMAdjustedPageRect</key>\n					<array>\n						<real>0.0</real>\n						<real>0.0</real>\n						<real>783</real>\n						<real>559</real>\n					</array>\n					<key>com.apple.print.ticket.client</key>\n					<string>com.apple.printingmanager</string>\n					<key>com.apple.print.ticket.modDate</key>\n					<date>2004-08-04T16:32:30Z</date>\n					<key>com.apple.print.ticket.stateFlag</key>\n					<integer>0</integer>\n				</dict>\n			</array>\n		</dict>\n		<key>com.apple.print.PageFormat.PMAdjustedPaperRect</key>\n		<dict>\n			<key>com.apple.print.ticket.creator</key>\n			<string>com.apple.printingmanager</string>\n			<key>com.apple.print.ticket.itemArray</key>\n			<array>\n				<dict>\n					<key>com.apple.print.PageFormat.PMAdjustedPaperRect</key>\n					<array>\n						<real>-18</real>\n						<real>-18</real>\n						<real>824</real>\n						<real>577</real>\n					</array>\n					<key>com.apple.print.ticket.client</key>\n					<string>com.apple.printingmanager</string>\n					<key>com.apple.print.ticket.modDate</key>\n					<date>2004-08-04T16:32:30Z</date>\n					<key>com.apple.print.ticket.stateFlag</key>\n					<integer>0</integer>\n				</dict>\n			</array>\n		</dict>\n		<key>com.apple.print.PaperInfo.PMPaperName</key>\n		<dict>\n			<key>com.apple.print.ticket.creator</key>\n			<string>com.apple.print.pm.PostScript</string>\n			<key>com.apple.print.ticket.itemArray</key>\n			<array>\n				<dict>\n					<key>com.apple.print.PaperInfo.PMPaperName</key>\n					<string>iso-a4</string>\n					<key>com.apple.print.ticket.client</key>\n					<string>com.apple.print.pm.PostScript</string>\n					<key>com.apple.print.ticket.modDate</key>\n					<date>2003-07-01T17:49:36Z</date>\n					<key>com.apple.print.ticket.stateFlag</key>\n					<integer>1</integer>\n				</dict>\n			</array>\n		</dict>\n		<key>com.apple.print.PaperInfo.PMUnadjustedPageRect</key>\n		<dict>\n			<key>com.apple.print.ticket.creator</key>\n			<string>com.apple.print.pm.PostScript</string>\n			<key>com.apple.print.ticket.itemArray</key>\n			<array>\n				<dict>\n					<key>com.apple.print.PaperInfo.PMUnadjustedPageRect</key>\n					<array>\n						<real>0.0</real>\n						<real>0.0</real>\n						<real>783</real>\n						<real>559</real>\n					</array>\n					<key>com.apple.print.ticket.client</key>\n					<string>com.apple.printingmanager</string>\n					<key>com.apple.print.ticket.modDate</key>\n					<date>2004-08-04T16:32:30Z</date>\n					<key>com.apple.print.ticket.stateFlag</key>\n					<integer>0</integer>\n				</dict>\n			</array>\n		</dict>\n		<key>com.apple.print.PaperInfo.PMUnadjustedPaperRect</key>\n		<dict>\n			<key>com.apple.print.ticket.creator</key>\n			<string>com.apple.print.pm.PostScript</string>\n			<key>com.apple.print.ticket.itemArray</key>\n			<array>\n				<dict>\n					<key>com.apple.print.PaperInfo.PMUnadjustedPaperRect</key>\n					<array>\n						<real>-18</real>\n						<real>-18</real>\n						<real>824</real>\n						<real>577</real>\n					</array>\n					<key>com.apple.print.ticket.client</key>\n					<string>com.apple.printingmanager</string>\n					<key>com.apple.print.ticket.modDate</key>\n					<date>2004-08-04T16:32:30Z</date>\n					<key>com.apple.print.ticket.stateFlag</key>\n					<integer>0</integer>\n				</dict>\n			</array>\n		</dict>\n		<key>com.apple.print.PaperInfo.ppd.PMPaperName</key>\n		<dict>\n			<key>com.apple.print.ticket.creator</key>\n			<string>com.apple.print.pm.PostScript</string>\n			<key>com.apple.print.ticket.itemArray</key>\n			<array>\n				<dict>\n					<key>com.apple.print.PaperInfo.ppd.PMPaperName</key>\n					<string>A4</string>\n					<key>com.apple.print.ticket.client</key>\n					<string>com.apple.print.pm.PostScript</string>\n					<key>com.apple.print.ticket.modDate</key>\n					<date>2003-07-01T17:49:36Z</date>\n					<key>com.apple.print.ticket.stateFlag</key>\n					<integer>1</integer>\n				</dict>\n			</array>\n		</dict>\n		<key>com.apple.print.ticket.APIVersion</key>\n		<string>00.20</string>\n		<key>com.apple.print.ticket.privateLock</key>\n		<false/>\n		<key>com.apple.print.ticket.type</key>\n		<string>com.apple.print.PaperInfoTicket</string>\n	</dict>\n	<key>com.apple.print.ticket.APIVersion</key>\n	<string>00.20</string>\n	<key>com.apple.print.ticket.privateLock</key>\n	<false/>\n	<key>com.apple.print.ticket.type</key>\n	<string>com.apple.print.PageFormatTicket</string>\n</dict>\n</plist>\n8BIMé\0\0\0\0\0x\0\0\0\0H\0H\0\0\0\0/ÿîÿî8Ag{à\0\0\0\0H\0H\0\0\0\0Ø(\0\0\0\0d\0\0\0\0\0\0\0ÿ\0\0\0\0\0\0\0\0\0\0\0\0\0\0h\0\0\0\0\0\0 \0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\08BIMí\0\0\0\0\0\0H\0\0\0\0\0H\0\0\0\08BIM&\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0?€\0\08BIM\r\0\0\0\0\0\0\0\08BIM\0\0\0\0\0\0\0\08BIMó\0\0\0\0\0	\0\0\0\0\0\0\0\0\08BIM\n\0\0\0\0\0\0\08BIM\'\0\0\0\0\0\n\0\0\0\0\0\0\0\08BIMõ\0\0\0\0\0H\0/ff\0\0lff\0\0\0\0\0\0\0/ff\0\0¡™š\0\0\0\0\0\0\02\0\0\0\0Z\0\0\0\0\0\0\0\0\05\0\0\0\0-\0\0\0\0\0\0\0\08BIMø\0\0\0\0\0p\0\0ÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿè\0\0\0\0ÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿè\0\0\0\0ÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿè\0\0\0\0ÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿÿè\0\08BIM\0\0\0\0\0\0\0\0\0\0@\0\0@\0\0\0\08BIM\0\0\0\0\0\0\0\0\08BIM\Z\0\0\0\0A\0\0\0\0\0\0\0\0\0\0\0\0\0Q\0\0ÿ\0\0\0\0c\0h\0a\0r\0t\0e\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0ÿ\0\0Q\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0null\0\0\0\0\0\0boundsObjc\0\0\0\0\0\0\0\0\0Rct1\0\0\0\0\0\0\0Top long\0\0\0\0\0\0\0\0Leftlong\0\0\0\0\0\0\0\0Btomlong\0\0Q\0\0\0\0Rghtlong\0\0ÿ\0\0\0slicesVlLs\0\0\0Objc\0\0\0\0\0\0\0\0slice\0\0\0\0\0\0sliceIDlong\0\0\0\0\0\0\0groupIDlong\0\0\0\0\0\0\0originenum\0\0\0ESliceOrigin\0\0\0\rautoGenerated\0\0\0\0Typeenum\0\0\0\nESliceType\0\0\0\0Img \0\0\0boundsObjc\0\0\0\0\0\0\0\0\0Rct1\0\0\0\0\0\0\0Top long\0\0\0\0\0\0\0\0Leftlong\0\0\0\0\0\0\0\0Btomlong\0\0Q\0\0\0\0Rghtlong\0\0ÿ\0\0\0urlTEXT\0\0\0\0\0\0\0\0\0nullTEXT\0\0\0\0\0\0\0\0\0MsgeTEXT\0\0\0\0\0\0\0\0altTagTEXT\0\0\0\0\0\0\0\0cellTextIsHTMLbool\0\0\0cellTextTEXT\0\0\0\0\0\0\0\0	horzAlignenum\0\0\0ESliceHorzAlign\0\0\0default\0\0\0	vertAlignenum\0\0\0ESliceVertAlign\0\0\0default\0\0\0bgColorTypeenum\0\0\0ESliceBGColorType\0\0\0\0None\0\0\0	topOutsetlong\0\0\0\0\0\0\0\nleftOutsetlong\0\0\0\0\0\0\0bottomOutsetlong\0\0\0\0\0\0\0rightOutsetlong\0\0\0\0\08BIM\0\0\0\0\0\08BIM\0\0\0\0\0\0\0\08BIM\0\0\0\0\ZÃ\0\0\0\0\0\0€\0\0\0T\0\0€\0\0~\0\0\0\Z§\0\0ÿØÿà\0JFIF\0\0H\0H\0\0ÿí\0Adobe_CM\0ÿî\0Adobe\0d€\0\0\0ÿÛ\0„\0			\n\r\r\rÿÀ\0\0T\0€\"\0ÿİ\0\0ÿÄ?\0\0\0\0\0\0\0\0\0\0	\n\0\0\0\0\0\0\0\0\0	\n\03\0!1AQa\"q2‘¡±B#$RÁb34r‚ÑC%’Sğáñcs5¢²ƒ&D“TdEÂ£t6ÒUâeò³„ÃÓuãóF\'”¤…´•ÄÔäô¥µÅÕåõVfv†–¦¶ÆÖæö7GWgw‡—§·Ç×ç÷\05\0!1AQaq\"2‘¡±B#ÁRÑğ3$bár‚’CScs4ñ%¢²ƒ&5ÂÒD“T£dEU6teâò³„ÃÓuãóF”¤…´•ÄÔäô¥µÅÕåõVfv†–¦¶ÆÖæö\'7GWgw‡—§·ÇÿÚ\0\0\0?\0ÙÊ»¬:§W‚ê«µØ÷­õA®¼Wş‘Íciİmµûm«é~ÒBvê´\'+:»\rÚzŸe-¥¬Æ³Ô}ÿ\0gËgé¿[ı6OØ¿Â~ŸÓ¬¹öd¶¡U5ÚçêìRÖXHüûšcßÿ\0m?Óÿ\0½5X¹6’Û¬Ëu[7>ŠŞF¦Û*;kßuWnû?±›êÿ\0‹ı\"ƒorØÊË¹´»ìµ2ëÆÀ+cƒà—3ÔŞØ§ó}_{-ÿ\0‡S·\'Ş£¨4M$YhuğÆ9íÊ®ÃµŒÆÛ]OgÚœÿ\0R›ë¿õÕ¿LÑèe/ÍïÛú©©Ö1ícƒ™Fë)Æ§}{¿›ÿ\0³Ã›Œì\\ªÜ\r!İãëXbÇæïfëE”ÿ\0m^üŒŒ‹§G*ÆÓKú¨n÷½ì´–Õ[™anm¾À×3Õc1ö5ú_WÕµIì¶™[“5Èi%ş‹Üûlnß]­ßô=?§öuBºÛikq[C­}˜aÅ{œi>öÃ]k\Z÷±Ï~O©{*Äº«nşeXµ­Äf@£$ÙcC€ôC­$ºû±«h²§d?ß«Óÿ\0ç½:èõ™üêHdûı&Üç[OªÆ¶\Zìkœ=j·îc}]–~‚·¿Ô÷úÖ~´î¦—¹ÙX™µ¸ÔÒóéTëÚêî­·~ŠÍŸ÷•{ÿ\0Ié}ÏERe~¯Y½ã.cÛSi!›Èu®ÄÈ¢†µ–û=?K/óÿ\0ë{ëú‡Ù2K¯;CqÃqõš¶SÔ}OfßK\ZÛi·ÖİïÆ»ô¯Ù½D¶×U—sh{ª¼Vµ\nÃEß‡HõÚëÿ\0ZÒú–¿Ôõ+ıø!UuxôQ]İPXk¥µ:ÇšfÇO¥N[Ûsÿ\0µXÿ\0fï^Æe6úıE³kô€9\Z7m‚±C¬.Ö+ÜÖí{¶ß³-»}i}?èşœœæß^Ã“´ä¼G§C‰$–×[™êz­g§”ÊîõüãêşoÑIZ-~^M6ÕoQ›XışÊÛéÚÆ~‰öŠŸµÿ\0C#õÛöÖzõ²¤±zƒòhÆºüË«õã#ô6Œƒ¸¹˜Û½[öK¨İê?Ó»ôô²}ŸÍ(Süš]n6YnFU±··[gª[ƒ{è5şm\rÇe·ı:¶~µúõ\nï~%•fe}Œµ²ë)p6mu­a·&Ê˜ï^Ç2œw³şÔÿ\0èR(H3Ù]ÿ\0¥ûnÜ02AâÍçnOèqèÜÿ\0æôı7ş’Ú?H§öûér\rnmomn/ßCqì§ôN­‚½ö~åŸ¤ı\"©N=W`±ì³#,zhûKksæísªºº=&·Õu/eõãmı%ì¯ó?EdéâÔçŒ¬‡·Ó5äz„EÇ¤dí¬·õk=O^Úíı-U‰„¯¿\ZßÓ2ì}³[¦°=\'šíªÆ¶¿s*uoeé½øËmY²ÌW¾üƒ¿©bRçä6“ÏOìõ6¶WWè[±ÿ\0Íû*ºÿ\0çÿ\0¹n5¤€ö[v÷lu¢İ¬ih{në\\ÇWé»õ{wÿ\0:ÏCı\ZÉxÇn^\0sMo·©t÷Ö-~÷nµ®cZµ–ìuı¾£ë«ıé5t>OÿĞßÊeĞ×\nıBÈÚÚé:}ÜY¦íÌŞ£¼İ]­Ìw¬ßL¹¯€\ZH·Ô¯Ñ±ı¿àıDkó1.­…í±Ì–»ô˜ÆÁ,÷6ê¬İ]µîşwè~gøEDÙMëI¿Bê©¦êçÒfËfÏÕk¶Ûı_Ô»Ôôÿ\0I±A}ÙİK³²F;œ™y/õZê=à›=V7èÙ·‡ì¹tÛ]¯Ğ¯Õz¡”Ë¤³ùXÎ/»´±®g¦jm›÷ì{íÇÙú3õº?XÆõ?Kuh³§Uë¸İ{};1êõí¹­n=[E¶Øú}Öìõ¯õ¾ÏëşŸÔ·ÔQuöÖê«9W6ÚËê©×ÒïUµõdcÜr7í§ëS›şş‚WÕT«k½õW…e¹O§.Ó‹¦Ê©h§ šÿ\0K²¦5Í©®f5V3õµYêÁ«Y™l·\Z¬Òüûé|8×²¦ÙP­¿ks>¶úíª¬øSÕTƒqín5¶nªÛv#M¢ÍƒíÔßHk½VZÜW:ıŒı%U£¸úÙeõï´6·>ò÷mÊíö[ê;İêcn»ş1é*‘à».Êmv>^MÜRÜeMØëö­¬p¾–z÷2ÿ\0³lgó?Ÿúe2î¿1—/­•PÛ[H¨8°¸5ßÒ=)·m5å`lgô¯Ğÿ\0¥eÉÙ}G¬‹ê{¶½­{ö\ZŞá‘Šæ³(>ŸÖ=+²*ôwïı\'è‘è¢ê«·ÓÊÈ¨ââë™ë6fÆm³í\rg»ú7§êz¾>ûjô’µS[2šÙ~Ñ—m>¿M»\Zí­#í1c­©×lÈgè,·}¯fÿ\0ğ^Ÿ¨Š,¾ªi¶Ëó­¶†ş–æÖÙnêŸêØÌ?IŒÈ©]«9–ıçÑe?ákNjkCq¡áÙ.mNÇºÖ†¾›ËëÈœ]¬ŞìVä×‡½¾Ÿ©ô?™¹K/´ÕÔé~UØ—=».³)¾›u¾\'ÛŠìši£ô¿¢¶ëjÿ\0êzL·;ÒÇ³5ïk]*©¬xÖ»1ÿ\0NºÙö»_¿ôÌö?ô¯¥ú%6«¸¾ª³Km\"ÂÇ:²={âşï[wé?í6%”o¡ÊÛmÇw¯‘E0ç›³åÖÙíì–ÑSöåú[mÿ\0´”ãäUş\r+ºK,\"qXêÍMe™is.ul\'&­¶7Û]Tä7ßu³í^ªJY·æÖúè³.çKŸ]í¹Ğv\ZáÖ×Øÿ\0Òûë¯ô?÷¿C}4Fìl§z5SÓ-ôÜ÷o&×\0Ò,¨7{\Z,İŠ÷_m³eX_«şY?±…N,ºšé&·Y9ïØ*µ¦¶ŞÇ»s=ÕÛéãUüŞG©üåjÈéÔ{“ƒS±ì\05öŒV–S[6½Ïª»)ô½Z­µÔÿ\0ÜzYèş·ê¤—hõıLZšàç\n™qõ[éµîô C\\Ç]E×z46ßÑ¿ô*†)Ç¿\'*“UÏvFË\ZĞçm±Ø¶zÎ®Ò]¾¿Wùöı?á½Eaùx!÷úg›pÃ1İe8ö=õzWÚßMŒ³}vSíºš½»ñò¾Ñgø%›Ÿ‘Ôòz§JºŒË¾Å_RÃ«\'ÖÂ>ĞÍ™föÿ\09M×lôqßôÿ\0¥3ü\'¤Fô¢t=_ÿÑßc:—§OØ[özı·ØÖµÍ\rs-slvğçûmgĞª¯RÇúU„K»ZÊîê°÷ØqÎ3ÚÖØıhû;]·Ò©û=;ú/ø4ªÅôRÏIÎ®M4¿~Çs6ZÚÿ\0ÔÙú7×úOÑ~…«ÙkËq2I,&ËSšğ4¥M¶5íöûŸüÏ¯ÿ\0 å32]UMv^-ÕŸJËšúQs]«ƒÉg©nM¾‹¶9Ev{ÿ\0Á¨Y“…nı?%¸.elum¬9ÀEÃÓÇ±ïsñ}Ò2—z¿h©Ÿ¡­Ôİlnnfm¼5âºØÇ:±6Ín}LÛê?ÔfÏç,ªŠ™_úMã8”;ôX¶\\Ë™}\"íõ²Y‘q.Û][­Åwó¬ÿ\0Ï©)|Û±uyU¿<¼±–ÎÃèZá‘k¬k™ı+Ö«ÔÙÿ\0\n«»3Êí£\"ì3M€1­këê5_ë]][j²ÚoÙ¯¯ùÌtÙ°fcÔÛ-û;ìq°hiªÊ›e!®}[™é\\Û+gı¨ı?é›­ÔàãVY’ğ1¨­æ§:_c‰ŒÍîhõ{ëıaïı-^Ÿ©ú?µ$®ˆl³§²ÏNê±^àö¹•zz8=±®¢ÇWúKö5ÖeìgèiôŸuSSİE7Ş\r´şŒ9–9˜÷;Ü×3}UUCk«éú˜ÿ\0àÕ·ÚÆ\\*6ÙSqƒÚ\\ÊÒE¬ôj³ÏcİK±O^ÿ\0Ñ{î÷×g¤­¶ìimùOkF×M2\Zæl®ÛYs­±¾æúÌŸÒ£A.]l.sólf3(±ûÏÚ+ı(%´bÛ±öïºÏVÚ¾—£±ş·ü¼mÍÙm—`“CŞj\0X³ë>“+k27SE•ì³}Ÿ¤µk}OÉËºòèš=7G´úx¥ z{Yèı¦ÏÑûÿ\0L+²ê‚ì‡7\"¦ÍVca{}¿h«ĞÕw¾ª½?æ¬³ı-oI^\r©¿qnHa¦êík¬8äµ¡­¦¦ãåXØ÷ÚÜ‡ú³Òÿ\0ˆ³ÔÄi{ÚáF@®Ë÷¸Æ\nlõêºÆl¿ôz×Sÿ\0]ªµaôåÛe•Ù—2r+õl®Áï¥¶Qú+PmuµÍÈÇgı¿üß¤‹ûO\ZŠİW¯”ûCCßMlÎ{ˆû	u~‹7_³\'ÙWèìÿ\0	éş‰ k/­õCi©îÜlp¡©µ†}£nQ³g¢ÍÖo®Ö„ı§ôëF½Ùî®º¬Î\0À{.¦‡Ua®ÍÌ†zAÛ=V{m~”~‹+Ùú;ÛÖ’òÒ+s\Z ¸û¬sXÖîû;¿Koú±däÙÌZÑêoõœö—¼‹¾ÑeV“éÚêmcMVÕüİzŸ¤û™Æ²šòì6Ô\Z\r®m\'ØúHÈ·ùÇı±áîõ?Òz—äú>¢ÇıìŞ£Ò,³¨ÙaÂÊÄ¼Ğ6_o«S™¿ô?àÿ\03ÑØÍöÂúkÙ¸·1»Şç8[,$M—ymuìıı/ğv6©Xæ³/k­¢rñlÚ÷K_‘M-©•ĞíŒf÷şşÜ³ÔôĞU£ÿÒÓgQøl>6ZÂçĞ/Üß{>¥wWüÛÙ[²=ÿ\0Î{òº‹E•oÁª¶:šŞÌ“Qmo{oºÚn§_­[1ıZjİüÏè¿CR¦]K«¨1ÕZ&ÏY¥Ãk 6¶G·ó]üçæ~“b»NGÉ_M9eÍ\r{>“cŸ8÷mÛôYîşcóÿ\0J«Š¶ÁÙ»……qx}¸×±ó½¬m¡íkÿ\0ÑÖáum~.Ë/ªÏÒ3üª¯^vØÆ2¬·¿sÁ‹klW`ßcœÏWÓô°İ²ºıoæü×©ş;%Î²ÑÌj‹f—ºÆŞêìSe¶z7_ô.¦ºŸNÊ™ôìşwü\ZÈÄŒVSZÆZi§!ö^6Ê6ş™¯uz?ü?Ù?ãlE	.°äõYnS~Ò-dn¤\ZØÆÍæúV[[?Af÷ú[ÿ\0Mé©°;(Xl·(Sc¬n9sj–z?j¯{\Zê÷ú•úşÛ}?G/ôÿ\0Íª.n^c+ÆU2—ïÉ¹÷µöš…/sèk›{ı?Rë*¯e?è=\\‹««ÓõM›”ërEÕÓFÜw:ÒÖˆ/g§xı6¹•~—}–ş±şÔII²²z•\0¾×[¢§ÜÒÊ˜ÿ\0Qÿ\0wé¬ÿ\0H¡nM”Ræ8e]c®¦¼¸eu\nšæ7~E[k.úWú¯oóÖş—ìş8ş•Q²Ì–¶‹+¦ÖØÇÃÈ½¥›\r[¬mU=†ı¤6›[{?K[şÑôÿ\0›3Û,fTì†ZE‚æ»ii­Ïx{÷UfÌ¯SõĞ²»=ŸÌ¤¥Ë0ò›X´eßeNÑcCXE•–Š+·ØÊÛëälô>ĞË?Iÿ\0Z—QmÕÑëcÑ“f úD±˜akêXÏsjôŞö1õşÑéÂz–\nª-}ÍË¢›íuuz‚¯\\4±çÓ®Ú½?SmvãR÷å:‹¿Éª¯øÅv]ÕÃ2ª®§1Íı)â­û`ûNíÖúeöı?Qì³ÔıO­y+ÍXur›]ßhk%í}–½ °4½®Ùk˜Ö½Õû÷ÿ\0Å«8ÎªÂÖåcdÑ‘2Æ·k\\ò÷ººœÇ³Õı#=œE«=+.ı\n¢Ìg2ªqoÄºú®kè¶»oı.ëX}<l¦6ëYS=l©÷ı:n®·ÿ\0†ûJF›¬ËmÃ±µ‹÷Øûçm°³ÕÇº·6Û«oªë¯§#ôÌØ’›Ã²ìF—a¾¼Ù®ß@dï`¶—¶ê«eqıGïÔßøõÓ«y¾‡â=¬ş¶äï²ÊÁ\'Ôu}UÙc6zÊ8U1øë:s¨§&·úŒeÑ´<3ÖaôıÍ¶»m·§úv}šÛ)şuçß“còmÁ¥‚Æ\nİï“ú3¾–Ó-ÚæV÷_’ıÿ\0Íığ¨j¦.Ç{ö~„0·ôçpm.u”ÿ\08ßw?¥ÿ\0Õ*“‹:u®¬5ÙK	ÔI>yTÕC«ßô}M—?ùtzi­ÆŠr=\nEïkşĞ´Ñ…WÑÎwªß~KjßU­ôñiõ}E[#+\'í½=¶ÕMO³«ôúÜwC[.µØ”6½§ÙéÓêÿ\09ôÿ\0Iü„F²2Ò\'ÉÿÓÓÆÏn9qc‹w9ÆØÑ µ›·3Úß ß§şùv#áãWvGìàÂÚXsK‹=İı%t×MŞïgé=?ç–oM±”·}í®ªM[=ÁÏl‡îÜçÏÑ7wó_é–•]~¦5xµ¸XëœıÎ‚Æ–İS¶zÌı}uíÿ\0	ş\rWl^Œòí¸0Yö[®·sko¦ZH,²§±»ı?I”7üÓõ¿ëˆîéøM±Î·¼1Œs2Ÿp.›uSë¶Ê=7¹¯õê=o¤¡CY…Qô±Æ;Ul¬³ ÔáX‹ìÇ-ÜÖã5–³ÒúoßW©èú¤N2^Ák^rím72r{Yc+ıÖ½–ÓöWïôÿ\0ôeÈ¡­]u»\'¿Ş¶ÜËnö»ì¸ï~ÛkÙú:[¿şÚM~¥.Á’CÙX}íxs‰Õ…ÛÏ²í¿JÖ~‹ú:“v?,ÙèÚ=B÷Û´ı–¬mÕow¶§ı?üâÔà_“Œël{i¥îu‡hãån-k¾ÍWéïı§}~§§ş•%uáYôpŞâÆ¹Í`±­ÄÑomîôÜû=¾§óu)ßŒZËF\0¶\ZÚ*ÓÓ5·Öëwlõ­ôıFş“ı/ø4\\|&b8XÊì\'Ôm¡‚ÖÖƒX~·ä†»ù»Oó´şUËÄ~>;j«\'$W¸Ù{ni{ì·cÛmÕı£{¾—§÷ÿ\0¡}Ş•´ ¤•TÚór²-Ş§ª-6œ´»m[v[[›ÿ\0qıßÎúèÑ=*òX¯Ö5‘p¼dF¬pÙ]ïŞç»Ö»éÿ\0¢ÿ\0ŠX‡.¶ì¯uli»Òu¤8\\Óe~ƒlg«¿Ôcšú÷úOõ¶²¥n¼Lœr}\në¸G±¯{› Ãls™ü¯ú†\"Bm»N6¯kßI®û%¹\"ë_¸;k«¤~‰Ş–Nöî÷úÕz_à¿Â!ºŠ1ÍnÈÅ­›[7»×±£p`Ùe^÷û›ÿ\0j?IUáÂ*ÖWn÷z”TæÚúš$9´ì\rÈOôşKwSÿ\0gùãû3[LŸA†¹sÖÖ¼µÛœÊ÷{\\úƒÿ\0E»Ô½$$êC¦NÜŠ1ß[¶¶ËíÓÒm¾–]WWî±Í«c}Œõjşü¨“—ÒmÅ¹ÆÇô-®ĞÒë¬6môÛW­e^ïQŸg«ÔLq¯!ÿ\0fº—ÚÁml¬5„š\0m>“êUéXÇUì¿ÓôîD{_ˆ÷œH~CÎæ+®’ïe¹WÃŸ¿+Û²œ:½”Vÿ\0_ô?iı\ZÑ:¢ûC=zˆ4?\'-ÎvMLc¬{ı\'YGìÖ;ØË1}RÜ‹¿¢şƒùï³Ù†²ò3\Zz—ÕìJİ‡¬c¾Æ±›ì®ÆÖüŸQ®uvÿ\0;³ÿ\00W.µØì5Õ•kîÈs¬ÌÉ`\r¶Ç>ÇU¿èÓú_è>Ÿ³ş1c>À~±ı\\¨’lF§Ù €­ÀÖÏÒF q²>’ÿ\0ÿÔĞé±\rô¾Õ¾íßg¤}>}ŸÏ×à³µz›6SÔô·F›½_Íß¿ùÏøEóòJôú¶>×İïıŸö‡úŞŸ¯ê7Ößènõv7Ğÿ\0á¾ÏézÈUlı-Ù·é7dzqêkéíÿ\0†ú^šñ$WÚûE_³¶³Óô£ÔŸóÒ%şí¾ß´?·Ó÷ÿ\0¦Fgìï^³êzwz;cè~ƒÖß>ÏSw¥èíÿ\0öğ~¢ñ‘Sí¹_c‡íõ·i·ù¨ŸÍWù¾–?¡ú)û^ı¬˜ôcÕ×tGø=Û‰$—Ú¡ô}ËÔ~×ÈÛ?gÛ0w}r~´>}}›„îû,oüïíîúkÃÒCíQú>Í­ö{~Ïëı¦G£³ì[wGè÷‚şsèoüôlŸK{gíû=AèFÍ»àú{•·ÔÚ¼M$~ßÚ¯ñ_dwÙ÷·úDí>”ú²}û=oÑúj¾›„}«nÆÎÏ°í‰Óù¿~ÏøÕäi%öıUö>µw§µû¾Ó>ÍÛ>É¶5Ûô½¿Gò=oç=ë77öoüìè[>×»íxş†ÿ\0OÓŸ´Ñ¿ø_Wı7ıkü\Zót‘‡ÌÏåÿÙ\08BIM!\0\0\0\0\0U\0\0\0\0\0\0\0A\0d\0o\0b\0e\0 \0P\0h\0o\0t\0o\0s\0h\0o\0p\0\0\0\0A\0d\0o\0b\0e\0 \0P\0h\0o\0t\0o\0s\0h\0o\0p\0 \07\0.\00\0\0\0\08BIM\0\0\0\0\0ÿü\0\0\0\0ÿáHhttp://ns.adobe.com/xap/1.0/\0<?xpacket begin=\'ï»¿\' id=\'W5M0MpCehiHzreSzNTczkc9d\'?>\n<?adobe-xap-filters esc=\"CR\"?>\n<x:xapmeta xmlns:x=\'adobe:ns:meta/\' x:xaptk=\'XMP toolkit 2.8.2-33, framework 1.5\'>\n<rdf:RDF xmlns:rdf=\'http://www.w3.org/1999/02/22-rdf-syntax-ns#\' xmlns:iX=\'http://ns.adobe.com/iX/1.0/\'>\n\n <rdf:Description about=\'uuid:bd23180e-e7d6-11d8-bc4b-eba1eb0a597d\'\n  xmlns:xapMM=\'http://ns.adobe.com/xap/1.0/mm/\'>\n  <xapMM:DocumentID>adobe:docid:photoshop:bd23180c-e7d6-11d8-bc4b-eba1eb0a597d</xapMM:DocumentID>\n </rdf:Description>\n\n</rdf:RDF>\n</x:xapmeta>\n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                                                                    \n                                                       \n<?xpacket end=\'w\'?>ÿî\0Adobe\0d€\0\0\0ÿÛ\0„\0 !!3$3Q00QB///B\'\'\"\"\"334&4\"\"ÿÀ\0Qÿ\"\0ÿİ\0\0 ÿÄ\0\0\0\0\0\0\0\0	\n\0\0\0\0\0	\n\05\0!1AQ\"aq2‘±B¡ÑÁğR#r3bá‚ñC4’¢²ÒS$sÂcƒ“âò£DTd%5E&t6Ue³„ÃÓuãóF”¤…´•ÄÔäô¥µÅÕåõVfv†–¦¶ÆÖæö\0\0/\0!1AQaq‘\"2ğ¡±ÁÑáñBR#br’3‚C$¢²4SDcsÂÒƒ“£Tâò%&5dEU6te³„ÃÓuãóF”¤…´•ÄÔäô¥µÅÕåõVfv†ÿÚ\0\0\0?\0ï­m$Sˆ‘S2¸6lƒ8Ç“O:SSuşåÔšÓ²¨2F]ß\'¨Á–g~3qz¥’;5«ø[é¤%¢m ó°ô™§¬Éˆ}XÃhÜgº$íãoü÷|6b/ÁĞê«©aD#T¸G,C4vä\r©[Z@Š%v–¥|0HÎ7/GşKHVÒ»Kvü2 ©uĞrç3bƒ\05\r¹™òƒ#Ø\nh4ZsİâĞ:0l”	»‚öK Ø¾Î#07H§D…\rì¸G!‰¢wÄÿ\0Ìv‰Õ\0ê[\0£Úõã»ot\r@¤^\\Ù€ËP±3Í»P7¥:fs0ĞA™¢)€œY†K§s*Ôš|Ş›ñ’;ºPOVƒUy¡	ÄPãüN’\'DCTÓ‰ŞEqiqØ4MÕ h$,‡›4ŒrÀæå†äH5h–\"H7¬QMÁ¶Ëçğ˜\0ùeÿ\0IêÛ/\rn˜HÃâ4ñ#2	ïú=¦QÚP Z½#ˆ@éÿ\0%³| D$w‘ÚƒÑn˜\Z·Ö¶{œÎAt²…°`	ÕK½VÙ0Çµ\0“Lİğ™ãg`@ŸXGx¤JÑéÅ\0@&}­Î9A5àá<Ìµ:úaS+Nn_Ñ\'^P˜é3ş	ıIj‡ÿĞéMXM%Á³—eŠ4Eğ£,Dä·§Ö¾- ±TáÓàôbGyc:`e°PÛîˆÄÕÃBqàr›\0ÀÉ@‰\ZÚ™†wŞ :€uyäqï¿5í—ü§¬–D\0v(†´jí‰˜iÎy}0?ä¢YE†è­*ÜvŠ K`‹Õ\'²ŒEÿ\0ôìV­ÃŞè@u´Ê`w@æê…T‡iEØDXDÆğÆı²\0÷@×&1#e½ \'p,K,bh &1ğA×FÌãâÁË@DR6íÇÅËp— \nÑÀˆƒ@2ÙK]d	`7\0$\0Ìæ\0¿cÉ¸pĞiÃŒ±Üã?\rÑ“RÊ*ÂŒ—Ë²’E8c NG·• êAbY\"¡›x$øí@İiËÔÕ–ZìP5…Üåêû”sîºÊÔ¯<2J@’6Ó”3K4n\ZÛ¦d{ø9	Lr3™ĞQ¼D‘¬Îßé{CçÇ†ßğÈÏşSÒıˆ\Zm·7o?û$n½j43W˜ä¼€%ú.²\\9z@Wˆ@Ş7GÍËqœH ^sÚåè‹4ßo7QÁ®|»Qˆ¤ÄK”\rkppĞ¨ˆ@¹eŒy<µaáÏ‰²w=\ZR„²X\0Ìõ@™”BdÄe\0yL`PÎ6ÓJ\'|GhMÇ©&0$— ø3â²ĞÈégÿÑí¤Ó65h—À|®g,AÕ˜ÜäqDŠ@ÖY\0¡âÖ4°ƒÜ§d‡r¾œ²@’6)a`QÕLcg‡Hbî™ÂU¢Æ2\'&+ „J \'‡aydˆ¢+”¶Ç ³rˆ,à0\0ZœÈ\Z\rZHKşŠLCÉ„˜Øï¹Ú97\Z`4Ø<8	r¦{¬2n€vôÅ‚\\çÔl‰•qM„ÑĞN1˜ˆf ›#W¢–É¬dîw_\Z\nğZ‰ä9=ÕZ2™F¶‹@ÔÂ4Ìağ·=ût\Z¼¤ÊÀ=Ğ4Xİ=8À<<Ñ„‰ºÑè˜Ò˜\r© S#q\Z¸	Ë~Ãá¹ ë4üİĞ7\0¢@­ydJ~z¦Q\ZGlˆñó# ”«O´òşBÈáÍp±$E4\Z\0âÜ‡üT™™]vfp”¸ìBúwyğÃÓ±Ú÷GıLä37Ü½º¦X”%z)oßÛÍ¹hOx´ğÌqpQ\'Ò— ]š°È‘<¸Jù#wü—qi´zÒl¸ÇO„Ädôúa9\Zsºxæ;Æ®y1îĞ1È$-¨Êı®Xqùj\\ÇÊôC brQ¢èb ; L§&b)ò–ŒD‡|C]‹·½0vcy‹Ğc£š)ÏÔ(.€y3â²ÑÈôç—‚òôoüVOúvŒ3ÿÒí¦$èÌœ\Z9¥3ÃÈF¡è1´ì¤‚›¶Œp˜â‚‰TÀìCÒÆ8Ú„z~’»QéØĞ”Çí\04ŞL{ƒ‘Ç!Á`Fİ›ybf`\rë~œ¾—yFC»@´É$|Û1  \'DÄ€ã²R\ZµÃ”\rfâAîŒV\0ìÍL»F$_(!¸QEÒ%Şœ9ã²å—/¦-»>sşdXêL±²+³¼¬Æ»¼ş{¤¼BôuœE8F94¢H•ëTÀj.HÔ„‡ôºäòÿ\0©¤\rZ\n2pÉ»W–C$£ãtŒ%\r4@éjÜ#}Ó\"{ nç¸.~o6¾è!¡«€äºÀh¢qşgˆL#=ÖNˆQ¸{AÜØ6ò@Rbü%}²ñ@Û»$Ê;±f?´\r¬K*øOw“aõ·^„Ğw8ä Q5îÜœbUh˜ÿ\0R„m2xº|w\0\rØòÿ\0Éz6G„\nĞ³#ò[0ËŒJ$W!NĞ,3{†¥¬4`,jŞĞ8@€lè\\¡?Rï‹ÚïÇ0‰„¥àNä\r\01r®Í’”&H Ì@§IFÈ*HŒD’É³ÙĞŠ\'Á‰R&tÕ>¨sÿ\0¢çšf\"ÑĞL˜æ\'şï\'ıºFYÿÓï\Z.æI·&Œåc„ŒƒºR)ßõ6¤ØªĞ°¦±:†Ïªjn18…ƒİ ÈZãË‹XëİÛÓö”\r§*á›‡üÕ\ZÅÏÑ¡¥„	Œh|w:k06Ğåçˆ\nÇ1)ÈKìIå8K’‹âsœ(ÀxÑ ê½mĞ\0ğËÜ5/D`Gv_R7]Ã|„N5Á>e8õ»Xá:’kÌØ%zZ$Ç¢.õ¶\'„`(êå(¦Æ\nîƒ„4i$3Š7İÏ,lÄøí—”`‰\ZßÒÇS(šİ¶h±>W)–:\ZódP#¾¨‹uypÄÊ ºÇ‰»(OÏ¶»nÜëaÄã\Z–}+îP:jÆÍ(qâÛd”\r„‡Ï,^iS¸Ã­ 9	í<¹èq8Á6ÉÇ)pi¥»wvÅ9ú7Í¦8DMêwE»·¸ÄÁéaãªŸH@Lr\r¶Of½1·iáÏ&  @ğ@ÓÔ‰4Ø¡e± Z+8@ã£b`¸˜Íx»\nì\' Q;mHœ|keÓZĞjÚ”e)DY Î¶.Ò\"B¹dC„‰”y¤ËYï‹sÛ^d\0¡0¾êqøºİÏÕ\ZÁhJ:8˜‡a!ÈÒÙ°J9ÇhíÓtñ€q(N?r]°ú$ÔFÿÔîÛh!Ò™.\r˜\\î@½¡¡åš@J#Z¦v”\n–M4å#(<òÍ‚€‰…9eE˜škC©åe”Dhä3™Kiö:J1#VvDëHITI—ˆ=ÈoC¡f8¡\0@$#Ü³¤½‘–ÌŒ»kOLw@4›Û»\"5@Ç4öÊ p]°LX8Ÿ1ì×N# HÔ l3Dš\rï/4qí™şmTÌbş(¬²œwŠ/4ñUãÿ\0I ĞYÖ‘?Õÿ\0%ÆªGÀ½×Z@é1ÔFâ}où,Of§¼¼±t KRÀa†r”—vM$DU²cÜ\r4\ZB£ I\"ÏŒ@€S8n±„jÃËˆ\\Eòì#HòíˆûM‰ƒ¥êã’˜Ÿjˆm‘@ÜUéË[Ät.qÇFÃ^»3Í*\0â‹·«h Âû9dÂe^!§xK4b-2DàLi\0Ã(™â™ËÆ¨hŒxÈå½·É@˜æYekm€j:”ñÎTl5ê^€jéÙ˜GDä…\\âe@Ö/NÕÚP8£–~™—2Ä¤Eè˜b1\'Â^gQŒ de$ƒ#Ë¡ÇbšÚçâ‰¹Fõÿ\0ÎtÃÑM‡¨bDø¦Äm“<.:v\"N°Æ)è ƒA\0Dr;1°DÙÔPá‚`9ñQ€LC–	m†ÓÈ2L&cñ4¥K†ºs¤ÿ\0¢_ôQºÇ\ràôI¨Ë?ÿÕôÁd„%Á°d†ç!;Û¤æb/²™\n@Ël¼\\ä%|º	ÚÇ›@$j‰ÈG„®­‚; L25\n2_fá-ñ5¢c|ÄJâj ½4b\n0Ê\n}Aâëé†4p›éFFõım•Âì@ã–Œâ»WjÌ‰À×ƒ=<Œ1ŠÖê\"ëŒa}hüÙõ5İÜˆ¹P3‘ Z7ƒË§½šÅÕ¤Õrº)”ê|ÛhÉÒy@¸-_Šš(ç4A¡İ¸Lyc$<Â^Ñí©Èä÷l˜rD~m	†Ì[¢P3õAGP.<cõºÕ1G-×\0í@%v_^&#Ûåu8¯’Ï¡¥{wÅ…ä¡Fí¸Owfö§j!‰Ì#-½ë{–N¤D\nîô#værbpÏ<Ò­W#Z[¡+»¤E\rP0‰‘4t.y£)­¾mßéi åúªrŒòn#şkÔ2yöû761ù·0¦YG‚Dçİì¤mD9¥)Vœ³’R8ÏñSÒb`(¸”ìÔwîœb=Û1qá ş$ì—‹S\"í(»	WtHrmŒğ2‰®]P4Dóò€5Âw‚-å‰”¤jê˜ç05%Ÿ‘|î¯:‹¯áh:gÕB\"îÙèúƒ1–]£rÿ\0šøÏ£Ğ|¿ñY?èMÔ“ÿÖîP×­”S{plè9bEL¢\\eŒŞœ#d‡µ_\'‚IĞ‡XglŠ)ÕpGdˆ±]ŞxÆq–·µĞˆu†ñ1à7qËÏB!­ñ«¤SxÎ#»3•ü.Q˜º œR\0ñ(]‘â\Zõ}ŒÃ$rxtØ‹@‘“ØÆL’¿(Ñ\"qºn[ué)Kux„‰ôÇN.\0]Àe/bÆG»ŒçS\0|2·}¶ÀNBd(1\rÑë±v´ºE1‘îƒ£AÊFrğv»™W!ÆªwüN’í*RÓNX#²$v³	î¯ñ jg*áqÌËâèt|š@sA¢œ²şM8îÕ0o‘ìÒnœçCéÚR™NXÃp‰îR‹3È­ÆQî€}YxÎr?É“–‰Òÿ\0ò/h¦ƒùe»||î‚sYÄLSÉR1—Ù@èÜKÎ=K6tvŒÁÌÆq˜¾3Û\"mÓùƒÁ±@¦yce…‘C+Ga [Ü#©á˜ÂBBGúZÛ;åÖã* °rŠ$v´BÀŸŠ@•ê\\1õO$ æ \r¢9\"d(qÃdi–Å¹~Ò;èÒz ›%Ã û&_ò=kq‰$€Ò i\0F¡çädc\\4rOO*³§²‘8u”cuİ‚%Ô(\"ØUírÛ“›`4AA±eÎp3mª-WÈÿ\0S]Á›ÿ\0“ş„İòÆ¢Lœ:ƒ7ş+\'ı‘–ÿ×èuÉÁ³TŠr‘È\0â¤Ñ2ñ@Ó\'”²é	ÜAñy$e0by-Æ3\0DñH6 ÕÂ&{öÖ”ìl¢Z‡HÄ!Çï ãü(Ğ/6\\ÆÜ¤rø 81€(ö2w0Bá,Ñ¡Ú9…Û\0úaã8Ï¯gƒ¸\\å´HYÕŸJ>	0\0‘Q¸¢!ÅƒÚ	æ7 D\"uŒYĞ ĞY€-F\0jÅ¹Œ ’d\rgŒHÛ—RÏ/ˆu©\n@Ìãº´ì®íİ2%%†õ%Ã@kÃ©—f\0Ë\Zš,î;«±u$hDŒ„ Â¬ÙG}ÉÜ\0	2†œÓ†8n€İË½Ö.9Q=• ÓĞÒè1Ç»œrøèè%| J>‚ <Ã%	Lòi£\n\"Í$ğC¹Çlòà3_!>¥´‡@ˆÓ»È–o»É»Z@Ú¢ç–1 ÛfVi™İj’wGic! ÔM„A\0£–,dí.ÆzÒDÂ\0ôÀSŒ|W{BWÂFš¢|†@­z’ğ@ë	Ú<€ØáĞd™ä i(ˆÏo†IË”BcWtrŒdq˜Îä’=hkİ©GpÁæbGşE£) l#L˜Û7?cz°ìc…NBûïc4\'Gg-U”ÎUØ;ôoüVOúy‡M”ò?å=ı	Bo™cÉù³vdÿĞé	áb„Cƒgk!àõcÅbË¦H@èY‘±AvF%Ø\0ò€Gvuñ@ë©_f<fR®R$B!×AäÆgÉRe\";Í‹|LY1;}®»¥Ü0I¾$Àx\'`ğsÍ—ÓW|¡¢#o˜F÷ä2‘¡\Z¬²Xáª@IN há	íˆttßhš;±×¹DË.Œz”)hÆ´cÑ‰åˆä2gÔ$Õ u€*™Ù	_dÆgÀ h`Àíî±6h¸È”KØb¹ì0@r–B\'·µnhÎ¨@åš	à™ÀÒtr€«ÅØX\Z…OHˆ:§Ò$èÀ1ìNÂê${ŠqdŸ²[bni¹{\'e\" 	\Z¹	Ìå0Ò€Ü€@)ÙN‚3î¢\'º&›ÉÓá–Ç¤‹r††Fş#¹¡¸sH¡0¾Y‘ÚšR†#!ñ5RœkO0D3¡ÊÖ–ê#(ÔhhŞMÂ>^P9ãØÖ;£î¼Z1ÈA:pì\"K\'‰:üU\'YAÚQµ¨‰Q’2\"‚lÕv´D˜ä=È1 ’\"G.s\'C¢‡Ô1í[íç–2ë×àFÙFÍ i¶¤dÉ,Ou¢;°\ZH[Xnà“˜6ï„|_Ó\'HŒÿÑê‹¸ 9D&õy›1\'Ï#ı,ÎDPÛ¬è––€î¶Dõ®¶!B©§*j7H\Z¡A\Z¸Ï“‘2ˆ»Úgnf]›–9\"QèÈ a1¸‘aÔa#”œrì•\Z”$\"^|½1/ùHm¤ÒeéËŒ€Õ\0\ZdHÌ}±zaP1Á#(ß½Ü\nc6Š‰îÙŒôª@Ğ™•Öœ£ÌµˆÔÛGVIŸ€j$Öº e,vD‡gYÈB&^4D½Yd]å	Lx(\Z@‰\0|Zs„\'¦;%âÀ\n³*Z­K±@ÙÆs£]ÓRbXå¸KßQÅ?P[µ9b„ +Nîñ»HM1 4‘Š[¬òÆÈ?Âé n\r9íşfÿ\0fÔl>,˜ËÅYäåêÜ¶¹Ï¤*Ğ1È/\r‰Ô39å=ÙŒH•òÆÓ)›â:&7ÀÇÄ:c>P|8Írî‰×Pd¤9bx·½,\"\\”\r\nösØGtŸMZ.B>%©E DóTãö·Ív¿›Ñ‰7àëZSA{ã«„s‰ñÂN1óyğÂ´ıü’@Şs\0[1˜:ºl»‚d‰ÁœI§m€ cˆ7Z b@Šãtí(ƒË\" 0Ä\ZØdïé“ŒÅ\r`•™éö$éŸÿÒëÑèøc\Z.\r˜›\0Û¦CZ®?<D‡tŒ©ÆY\rĞáôF;uİ†:Æİ! º=¢¨ r\n.’ˆˆùºŠqÈwh€}AZ2r8ì	ôâ®â‘6vèáŒä,×Å³;8€hã>)¤!çW«qËŒñÙ¾ÿ\0ÄœÈõGÒôÇ‡œã7vè\"k”\r\0\0¦éÌ	_±gŒÏ6à[˜–âœ£@xºXH\'Tò+W;•Ì6âqQİz kU-Í‰8í—Š6ËÅ£{fVòì>-Æê,dÔ$?F¸4É»²Ói·˜ÆUË`Ğ@İ&T4åæ—›^GJ´\r±å#¹£y¡‹` KÑ]Ğ\r9HÒ}?6ëI€#P1Ç¸7.„\'Ó\ZjçÔß¦vòÚ6§”@DZªî€x\\™#º\\#~.Y¢N2:†P~nRÍÈœ¡ÀIãş$\r†[åN@;¹Rv hg¥³¢H®È®,äÄ€udÀ«[BÂ1¹¤î–ƒBªè\'h\0.&S¢£%‹¤”L™›á“1z·vçè€“ma#t¿¢N$Ğ$ğ~6ó–gìãŸüİî‘ÿÓŞ\'WxòóxÉÁ±ÉŒÌ\r7(F…hP]/«v÷aB7G‡rM_v72fÒY\\…Ÿ32êª[(¢&…9H^@Ò~±ç³>¥ø¦EĞƒ1!nñ¢n1ÖØH¤\0u\rKeËB hÏ£íy[$SG|Ä€9yå!nù!\Záåô£àˆAág-¢Ö1\0Øhb\Z¢•`. êe‹Û£”ba ?ÑóNû(˜Ä™öz3Î¿iÃÑò˜Ş‡ÌÔ5¡ç©\r`À“wªNÔóù¼T“Ü nB,NP{Z†17ÊDƒŒ¤J>ÒÆËî`Ú3âÜ1íäÚ”¢@ş$`İ·Ìl½RÄ$m„bl #E¶¥ˆ9b€“\\²2Â‘b‹ˆêLì#h@ˆğ@15î4»#à4ÎB:öÜ¶b%Ê1\0r¢^¨ö3éIÚ9ÏÂHğpÃ)‚u%ï1±O>8í-!íxI‘ğzv\"ƒ\nc<¹ÈÈšìõígn¨Â~Â³”´¦Î†‘H#!ãLc\'h>Çocœ#´Rœõ-È§2×Á…2Ë°‡—¡…Ñÿ\0Êsÿ\0£7³)ÑäèN¹ø\'ÿ\0EÒ2ÏÿÔ°]`\\ƒcGÊ¡n?Ì¿bÈS#­°I•¼V­;m¥,êbeâ%-•íò³,€¨ƒ(Êd\0ôHiN˜…hê€#*L2ní£Qè(\"€õ¼C¡,Šg“)âˆæãFëw\r säÎ/‚ÆçY9Fo5=g&Ğ)ç\0[ÖhE˜I¬zÈËÁ‰èî^œpÙ\0\"9DÅ”È†(ÃFd·ìA!Ş¢{1,@ö@Å›N›iLh\Z@€hµ€“ìò¤c\ZËŒ#R ğNø ¢wF\'Zcî\0ŸoN#AÃŒqÖ”Ğuo	yöË£¤sGÅ¼™4¸êóÆğv„bIÓD±1å6\nìš–yctóY	ş¯| g)G„}`\r62¤)!Å‡¬šÕÀ‡iL#öˆ@åÇ-¹%|²‹¯¯çÚmÉÛ>mÁÔå>)Ül2d+H¢\râf@iQ‹ÕH²(¦×r‰N¸r˜–ıÃÃkºjØyÊ{O‹–Ù\róOpèÆSqöE\0 u,äğéƒE™4æ2$á;evÚ-2%kR®]72K\na2FŞˆÄÂ5n0ˆ”¬ğœº\r8òğóôûßø¼Ÿôfëšè¼ÿ\0‡Ÿ.cÿ\0”²ÑÈígÿÕĞ45]·ÃQ\Z86I‹%> Ú±h¤HôÃ¼E¶b.‘S‹q»: à7¸^ÑÓ`ĞLA„·Xw$Xc.aŒYá€ÎfpÇ)} <¬ãÉ3H»Ò–áLãòDGÁ(ÎvlhéeÚDÁdÉ\0d™Œ<¿‡¬{]w1D eëv¢öj‹¶Â´F[5.§4d4pÏf4õb…Äb\0ÄD]²N.‡ÊyÉVûú£Ú–L©Q+S”DØn Z­\Z—Äê%	\rAÕ ¤òˆº7Ë^SÈI„O`àxs”Ä9åÜc†%ŒB‰\n¶=QÇÛofL\0@Èd¯kÕˆ÷\'(Gq§¢D¢\0MÚQc&m¤\0	§ªysHDĞÕ˜Ğ4Ü…èÆ8˜§²Œ¶jº‰“Ùƒ¸{@ÌÌØ¹ïÕŒ¿©°D¸uÖµå–S•Ø\Z$O^©fĞ3Ì¢k”ÄÍwÛ{º\r31•ØloïMÛA\0DÈòÎYN4GW` 6êÀ5 \\ñ”£Aé9,\nyå-Z ©í	‹Ûà™ g°bL®ôYÏf¨„ÌÅ DÊõqÖ1õ/Iò‡š|ıÌ)Õ{@s“Êru\0yGf½ZB3ğÿ\0ƒ7ş+\'ıGRcé’áĞ|¿ñY?èdtŒ³ÿÖÚ%»p‡µÒCp§™²LS \0‡>\n)Ñ\n	\0îyÄï„‰ÛHtëÎªAñr—Nİny±™Æ½¡»ö\'R”„¯ØÄc8û‚i“/b`Ì[4Îóàå“9€İà°r²Í·³ÈdTö‚{9Ç)2?áhŸcœM“!Á@³#“J/¡¼AçòÓÑ=3–`^<ù‡±é“”À¤õímHØãZXq«¦äÄL\"\rG)bĞ’GÙÁ;Hí&÷×bêPˆG«§Ò2İPŠF¦b‡ŒÄìj1GlˆöîzpÂ˜B{‰®ÌÎA¿‚FIµìÒˆĞ.B[\'-<¾WªÔ†¨Ş¨>,zÂìB1ß¯ÔËD´9á“x°?Ãÿ\0%ĞlS¶ÑÆ@M rÀËEE»™ÌÜ‚>Ì[x´ˆ@ÈÌø9ÂfWì/a§˜@n$q$Deên­znUÂ;¶ sb3”¤ ½÷ë½rnñ@å(î×^è¹HJ\'ı.¦%ÆG[\'~Ú–‰«åÑ 5h0ÇŠ@™_-y¯^dX”©)ãİÉnLuYÈD så‰—N,b1®ìGÍÃrÉ²˜S›$5ÑvU;NVäMèÒH¨è>ßø¬Ÿô27Öézƒ7ş+\'ı	ºFYÿ×è:Dxèì4‡ÉÛf›ÖÜdÀch~lt	N˜Ñ°Ò‘$N»f;·“hœòç)Hš%(„Kwd\\½–HÖ‘Lå)ö¦2FS‰ä½B-Pº@ãô¦Ex4À{K5¢=iír†3íw‘åºTz:{¥Ï¸`Opİâô‘¢1”¼­pëH(ÑŞRbM_.È@ÇiG@H[$ gæM‘ÙÑmbI<#&ğÑıN›”NÁñ@»RÎÙ|4FèÇ”g\"\"MkO&9È@?ôßB@(â6 —ƒ¬\'#ÈHH`32‘<,„Ğhè›E9²€h.9J¯‚/–c±¦ÌÎG³OvïcÕKµ˜JGèL„Aµ£DÔ.» sÜ!‘Îğ˜È,wM asâšÀt\"{ü.š D7ægx€\\òJµ`2È&t\"9\"\0^â:[bQiNhî­yXb–ë%ëÑÊsŒhB2/6LgtuzÌƒ”‚(ÛÃ”£»OC &²`/hÆ4`ÀKRç—.íq\"QìŒ#XŒ¼ŞÅ”Ğ8úéİôoüVOún¦[¤ôôoüVOúvŒ=OÿĞ\"NâZQyAuİ£ƒeĞ-U9ÇÌÖát”îRˆöº!-¡ªƒxµ‘’›pêõ6÷@ËqNãÅ5$4P; c–f\0šìÄr-÷wË\rñ!¿KƒàP•¸e>hÅæzÂ d28*en¬H°ÙM‘å#Å# ÔÀh9c <¾\\g¸[‘€`ë\rˆËvëò™‘Æ¯Mª($hY–àlqü.òÕBq‘®\ZŞ|¶´@ÈJû3“wÙz@\r\0Í­1È“}Ş²\nå)+G9Ü‡¢Nc”giq„ågM)è%˜„N^	3>\rƒL™_	!‹p\'A$1Ê;úÑÓF4Ù@æêĞ%cG2É£ï@±C@tu\0í \\¬†£=5@¦5E\0¥‚Í„v oá·Jö²d(A¾JN¢‹˜˜(2@Ï4A\0A\rĞä9O%0dDÀSAÓZ¬¢$mçŞhÿ\0…¨NÅX\rÈ±*¬11³Âã>bôH\n¹<ù¤L£ZGøVr5H³¸ÿ\0¥²@q¹HĞáÚ0#R&TuFá.\\òH’Á\Z qõOAğfÿ\0Ådÿ\0¡7‹)¹=½Á›ÿ\0“ş„İ?ÿÑ€)Ğ‹\Z²s Í! k`g]LÇÉ\08å¬Q=Ë¡j&Â`j­&ö×vI­{3	î(Â2‰\'Æâ=¡Ğ)ÑÄdEÈ43x„î¦c§(	Î\0ÔS0Î&7¢³òIğg§Œ0¦‡(ãW9fŒE›úN‰Ñ¤2±!o<†ëñz9V¥9OJ§c)Dh=ƒ¶[ÚP JÅ¹Èû@Ñ(Guè4\\‘2ñzÀÑvG#†¡38Øw\"Å9áF¼™ğu„É]¨Ç¨Õåßº³p$DnÙ“Ø‡r4@ä3>o¾¹,HRrt‰*ÁĞYh/$”Îé»MÚÙn2(¥¶H™C¸‡ i­à V½ÜÌLˆöÍİ¼Ø²“)Ø n/¹`‚u¶ÉR Š@§Å\"Ç%‘0t:7` g(‚m$\n¦î.f@p€šà„ÆÈ”íXÁûÊg9\0;n–Ô\n”\ZîŒbà<[o€/ã4ëB(€Ø5yòd7å@z™GÀ²²jãéÏ\'>/a€ĞQ>¿\0ç,»…¹ÎFz1\nÉá	< yÙ>\'· ø3â²Ğ›Ã“â/wAğfÿ\0Ådÿ\0¡7fOÿÒÈ^y@uÁ² kJÑĞQq\0¨–´4–0Ğ\Z1}‹¶€ A…è1m$Û¬MèÌÏ`€ÃQn^œ¸»€\0]Á\0­9`™û&œå0q•‡0e@\Z}–¡ ]ÈyğdL÷\n\n”	œÏe6·i@ÆîNC\\¥Úã•œˆ §ñ7J@“o7M9Ê|ÆäôYãò_¿r‰$Ñ™yj›Ü»Ğ3o±@É´‘EÓr7Úátâr’H£Tî%K¹ŠÌdMyh0r‰\rârâ8E91¡EsÃtQ¤C”@Èhœd*–õˆĞ¤Xh\'nˆôË­SE€çØkÚÀÆ^ ´ŒbCÅ´’>ÓÑµ e–;¢B1Ä‹u:¨;Bv„ğ¶€(-\0™g†dAªñ@ &e•GËËc\'d\0\"YÊ,kØîo~¼7¡åÜ)Gn(Ğpç?6ˆ“&í{9Çwa RPÚ6E‚Y%Ió3v’\n)F‹9PL¢ã0i†FÍ½ıÁ›ÿ\0“ş„ß9ôzƒ7ş+\'ı	»0ÿÓ\0êmÄ	Áu—ƒƒf‘.‘\08Ô¯†è n(ŸsTŒœÌA-êµİ\0¢óGÜK¹»A´Î;á7ªu@ÏiÑ´î–|6œ#M‘ËVGb‹\'±@ÎP\'†¶›—I™‚€\'EGB˜Tu¢»Ï@ KÅ5*[>DÏ@ÇéJDîú 	 rÖ¾Ë$—J\'³JÙEÑÊBH\Zn)ÜçŒHómÊ-\0îhìˆŸjLH\n@ØH!Ëiö®Óí@ÚÒÆÒ´k„mAr£íEà t\"œµğZ>bÍ0AğXÄß\Z\Z”ƒÍ=Ã€P;7Rï£\\#iğ@œ“$(–‰Ø|iğ@ëjI)¢{j^Ô\07÷t¢À‰ö£l½¨‰A{OAÇ/j¶\\ÄeZ‚‘àP4«`êh#ÍàWiğ(h1IØ|‘ìQB^|¤\0^İ´8y3BF\'Bˆyo£Ğ|¿ñY?è<>”ÿ\0†_ò_C¡„Ä2Ø:âÉÛüv`ÿÔ÷ü }êƒT½Cğj÷ªü }ê¿¨x—à•ïø5@ûÄ¿¨|‚ü }èWà•ïPü\Z }êƒT½Wà•€ûÕ~	P>ğ$¿­Ş©~	XSïü´‡Ş«ğJ÷Šü\Z }êƒT¼KğJ÷¨~\rP>õ_‚T½Cğj÷Šü\Z }â_‚T¼KğJ÷ªü }êƒT½Wà•ïÙø%@ûÕß‚TÿÙ','ÿØÿà\0JFIF\0\0\0\0\0\0ÿş\0>CREATOR: gd-jpeg v1.0 (using IJG JPEG v62), default quality\nÿÛ\0C\0		\n\r\Z\Z $.\' \",#(7),01444\'9=82<.342ÿÛ\0C			\r\r2!!22222222222222222222222222222222222222222222222222ÿÀ\0\0A\0d\"\0ÿÄ\0\0\0\0\0\0\0\0\0\0\0	\nÿÄ\0µ\0\0\0}\0!1AQa\"q2‘¡#B±ÁRÑğ$3br‚	\n\Z%&\'()*456789:CDEFGHIJSTUVWXYZcdefghijstuvwxyzƒ„…†‡ˆ‰Š’“”•–—˜™š¢£¤¥¦§¨©ª²³´µ¶·¸¹ºÂÃÄÅÆÇÈÉÊÒÓÔÕÖ×ØÙÚáâãäåæçèéêñòóôõö÷øùúÿÄ\0\0\0\0\0\0\0\0	\nÿÄ\0µ\0\0w\0!1AQaq\"2B‘¡±Á	#3RğbrÑ\n$4á%ñ\Z&\'()*56789:CDEFGHIJSTUVWXYZcdefghijstuvwxyz‚ƒ„…†‡ˆ‰Š’“”•–—˜™š¢£¤¥¦§¨©ª²³´µ¶·¸¹ºÂÃÄÅÆÇÈÉÊÒÓÔÕÖ×ØÙÚâãäåæçèéêòóôõö÷øùúÿÚ\0\0\0?\0è55o)Ö]b[F•J¨GU#(Ë‘“êÙú¨ö©´é`¶Y!µä’BU2¤&yÀÁÀğ=hºŠ&¹viå\\¨V<z\ZÖÓáŠ0‰1‰\0a’‡$‚HÇ^?¯jäØë(E¨¡FŠ]Ä–À“ [-´{Ø˜ëÍX{¡4YHrÌŒöúc>şõV{d’{ƒ:¬‘VU1©lt$ãŒäN=iÖJ£e³yn¥Ñ0ŠÄ©\0¶Iç$}µÏ9Pòmœ ÎK˜×§ÆsïÓü)-GÚspZåãlmteF=qß>ü}\0‹ZŒˆe­Y·‚¸]„àõãw9Çéô©ï&‚×M‰…«¤É°Ç¸fÊ@Î\'ŸB})ho‰	\\Ï£ïÇxéÀ÷È÷¦!Kx#c¸À_-TÌAàzîı^•HI3GåáHQ¸ì9N}Iü‰©IgµÙµÄ{0Û³{dòÏ_Z\0eÔşV§k?ÚV;tG{½ó°m¡xÁÎ0	É¾¹sßÄğÅå7Ú#—™¹ÛÑ‡œ®sê*KÆœZËæZÜòÏ˜¥b&EÛÓ¯Şéì}yXBC+#»BB\0#d>Uà9ã?¯¸ cFª’ÊD8˜‘‘“×„VN¡>ûOõ&7p¥•eb;dn‘Çâ+Bòx¢e^İšf8çÀàœcØ~\'Ş¨»G5¬Sıß9@²–à·n:~Ô0GSğî¶iº¨bëäßùc<ç0DÙäd}ïóš)ßme´Ò5/1”™oÙğ#ÁºpO»×éEuÃáG$ş&c^=¤òen•	 }y_cPÛÉpe.\"™0w8FaÆ°<}*;´eM“_¼0É*»‡\\°$pqîzŠ½nm¾SºêG-Ä’ÏSÇ|tã>‚¹²“ié2#hö*¨2l†àôÎåİ·ÇÓc†7šœ@­†#Ø,»J28ù—¡ôö«‘İ¤Im2Ã6õY<;mÛ“ó{oÎ£»¸·R“cI8,=q’}q@ná†îòÚ{ˆ#g\"ï€ƒyäŒvïÏNµ\'Ùîe2ıXrá‹NâÊ£œ`Fè)Ï#I˜-•ä\nB(.ş¤(çƒ“ş4T’ÛFv”QÔ‘»Ÿ?Z\0ŠM4Â$Š/#tÏò•‹37/Éê	İÏõ«ÖÑZ‚’HğH·’8\n¨\\îçƒÙÓš¥™rì’\\Ã\0}R5P}I*	à|Ç¿jœZ<M¼|–î1¢ìY²Np^}ıéb;K™m™Ln’³Ñç©‰ç<òOãJ–êöìgƒ–b#–ïu}9ª‘ÚKs Œ4ƒ~ÕE+\"r§QQÎ9«	¤Í\Zü¶Ñç\\\';ˆ,Nsıæü¨¢À$íw€=Ô(C2©f„˜ê£æ=võÇøÑ»Ô¬î`D–9#$¼x|Åv‚xcÏoå±4QD6ÎàíeRjFxÆA?Ã½d]H|%ÅÃ€>IF¦0?úÔØŸooõ\rU:‚B“C©<@F	|¨ˆ9<œîÍï†ºri\Zò%ÍÄíq~óÈóm-¼¢>Pj+ª)Y²½Ù•ª^Ik°ªèw\0À09ìlg¿oÊvK¶”[Å×S®×I%WwMÜc‘´šÅÜ\\JyÜpÌ\nü÷ïŸÊµìlmà2I#ÆÒ@€B_n<œsŒt?NõÊ£F=:âEOÜÛ,,¤H¿gPW°=°0ïÔv¬½GMX†U‰6]«\'çÙù$­^7æHpŞY*q´Ú²(=yÉäd{vªVh/u	?yÊ¤I\"©Úœƒß©ãüâË¶•\Zl;Z±ÚrNp=2Ç½6Ü¡”íû9cl˜•#,3ß±üêÂ^Å<­ùqlŒrzóµZ/³´e‡™W!‹;®âĞ@ê·\\@Ë¶ÒLL6±¼ŠF[co=y<úõ¢í-‚ySÛÄ…°m¿c¶0?\n†Ö	mæH™‰„W\nìÙÀè}ˆ##ëLÔšk»¥ Äb\\Œ²36N\0#æÿ\0{·q@\n!p’ÍaÜt…Ú Jõ+ßÔƒõ§M§sI´K\"†uXaPÀà(Ï^8÷ª¯v!¶mòì&Õ eÎ9îqê1ZšÂq$¡ÛÊcVbmNí¤ ¶}zc”\0ÿ\02øÃóÀì·ü¡2ÜñÜıi·wÏ³sy¡p2pœÓ\'¼·¶’x`…2ñª¼‰jÛgœ:c÷¬¦¸$¬¦ÑŠG¶BxÁ\'åSÏ>§Œ{Ò`×À—2Éc©Å\"Ä­ñŒâB2LQ1Ï¬GáETø[ —O×[rTàÿ\0Û¼ÖŠì‚÷QÉ6¹™ÌÇso<8¶í\nY†İ½	9ö­Dhã‘#P2»6î˜Î?1ÓÖ±ô¯ŞÛˆ¼ÄÃà’@pL{Vó+ÀKï‘úp\nœsÛ=?^+ë#F£Inìçy\nÒWŞ÷ÁªÃRMö{y°e	qóºàœ¹5aî¤ÌÖÈÅÏp¬Hò~¹üê¼D‡Æ6,F0ä?Â€ä\"ŞñÇ9ÊÌO#³ùÿ\0õëB;i\" ù@EÕœÊwc|Æ£†hZ9\"’\",\nƒÀ ÌsíL7¶ò„:)ÈÈXùÇ§^äÖ€öé|×fyC$av™ò:¾ÿ\0ãŠœIo,¾ll\0	É;äãîúà~¦±?³³¿U|¸W<cƒ:ÕÔ‚8˜nWø“ràş)Ù\"ÍiqÃñ˜²q›†O?CÇ?Z®ÚœÍ‘£mÄ\0æ)äËdt9JˆF„g-Ğàğ@ëÏN¾õÇldù ‘“™ÿ\0?… öë{ÈÌv±ÎØeP†I	psÛÎ}ê	î×ìÆLùepÁç\'¯\'ôú-+ÅofŞYC(Y\\H|É\0¾xÎOZÎ»ˆí\nŠ#N{}(°\\îşÜ­^™›qmn~qqÇn(ª¿dø;V“ŸX•¸÷#Eu­G¹é úRÿ\0áE%\rş!R¿€¢Š\0jö }óôQI€§ëMşÆŠ)­€zgñ~4Q@ŸZ( 	 û‡ëıQVAÿÙ','jpg','charte.jpg'),(2,42,0,'Retranscription','text/plain','','Sachent touz presenz e avenir que en notre court en dreit establi Guillaume de \r\nRezay de la paroisse de Ceaux reconnut en dreit par davant nous que il a vendu e \r\noctroie et encores vent et octroie a mestouztemps perdurablement a heritage a \r\nMonsour [] de Vernee chevalier, a ses hers e a ceux qui ont ou en auront cause \r\nde par lui sept souz e seis deniers de cens d\'annuel rente desqueux Garnier \r\nMorin li devoit e soleit rendre treis souz e Jordan Perier quatre souz e seis \r\ndeniers chescun an en la feste de langevine sus prez sus terres e sus vignes que \r\nles diz Garnier e [martin] Jordan [] ont doudit Guillaume le sicomme il disseit \r\nlesqueles chouses sont sises en la paroisse de Ceaux Desqueux sept souz e seis \r\ndeniers de cens d\'annuel rente de tout le dreit de tout le destreit de toute la \r\npropriete possession obeissance e seignorie que le dit vendur y avoit e poet e \r\ndevoit avoir senz riens netenir il en a fet au dit achatour e a ses hers e a \r\nceux qui ont ou auront cause de par  lui pleniere e perdurable cession par la \r\nbaillee par la doneison e par l\'octroy de cestes presentes lettres pour le pris \r\nde seixante e deiz sous de monnaie corante que le dit vondour eust e reczut \r\ndoudit achatour si comme il reconnut en dreit par devant nous e donz il se tint \r\ndou tout en tout  pour bien paier e a oblige  audit achatour le vendour desnomme \r\nsoy et ses hers e touz ses biens meubles e immeubles presenz et avenir a li \r\ndeffendre e garent[ir] est celle dite rente quite e delivre e especiamment  de \r\ntout doare envers personne sa femme e generament de touz autres impedimenz e de \r\ntoutes autres obligacions contraires vers touz  e contre touz e toutes [segont] \r\ndict et [segont] ce [seume] de terre en rendant audit vendour e a ses hers \r\ndoudit achatour chescun an en la feste de Langevine une maille de franc devoir \r\npour toute redevance e reconnut en [for] tout le dit vendour quil deit et est \r\n[tenuz][pssere] e [oudit] la dite rente sur touz ses autres biens si ensuist \r\navenoit que les dites chouses sur lesquelles elle es assise ne [soffesoient] et \r\nnous ledit vendour en notre court  en dreit present e consentant rendant quant \r\nen cest au rente de escript et non escript a tout privilege dottez donne et a \r\ndonne a toutes costumes de terre a toute [decoustume]; toutes autres excepcions \r\n[jugeron] et [ condepemnon] pleingement de notre court a ce tenir e donna la foy \r\nde son [cel] en notre main de non venir en contre ce fut donne a Angers sauf \r\nnotre dit dreit le joedi devant la Saint Urban lan de grace mil CC quatrevinz e \r\ndeiz e noef.','','txt','charte.txt'),(3,42,0,'Sceau','image/gif','','GIF89aØ\0ı\0÷ÿ\0!!!)))111999BBBJJJJBB9111))B11!)J))Z11)”kcc91Z1)Œkc{ZRkJBZ91R1)J)!cB9ŒRBkRJcJBJ1)B)!1)œ„{{cZ¥{kZB9­„s”kZ­sZkB1”ZBkZRcRJœ{kJ91Îœ„B1){ZJ)!ZJB­Œ{ŒkZŞœ{kJ9”cJ„R9scZRB9÷½œÎ”s½„c­sRŒsc½”{sZJçµ”µŒsÎœ{œsZŒcJ­{ZŞœs{R9sJ1œ„s½œ„„kZ÷Æ¥œ{cÖ¥„÷½”cJ9µ„cZB1{ZBÖœsÎ”kB)Î­”µ”{ç½œ­Œs¥„kÆœ{”sZŒkR„cJŞ¥{¥{ZsR9÷Î­ïÆ¥Şµ”Ö­ŒÿÎ¥Î¥„÷Æœï½”½”sçµŒŞ­„µŒkÿÆ”Ö¥{­„c÷½ŒÎœsç­{œsR½Œcµ„ZŒcB{R1cZRB91kZJcRBJ9)B1!!ZRJRJBÿŞ½{kZ91)Ş½œ¥ŒsÖµ”œ„k1)!÷Î¥Æ¥„ïÆœ½œ{ŒsZç½”ZJ9µ”sŞµŒ„kRÖ­„ÿÎœ{cJ¥„cRB1ï½Œ½”k”sRŞ­{µŒcÿÆŒkR9ŒkJ­„Z„cB¥{RcJ1{Z9”kBç½Œ)!ÆœkÖµŒÿÖ¥Şµ„Ö­{­ŒcŒkBÿçÆµœ{kZBÿÖœkR1¥”{„sZÿŞ­­ŒZŒk9œŒs”„kscJµœsZJ1ÿç½ÿŞ¥B9)Î­sZRBÿçµ91!JB11)ÿïÆÿï½JB)ÿ÷Îÿ÷ÆccZRRJÿÿŞBB999111)ÿÿÖ))!RRB!!RR9JJ1)1!JRJ)1)BJJ9BB199!))!!9BJBJR)19JJRB9JJBJB9B1)1)!)B19\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0!ù\0\07\0,\0\0\0\0Ø\0ı\0@ÿ\0ÅˆÁñ¥à—LQ*Uz3iÒ£6Û<|ô¦Í¬7\'aló&Ê¤1\r¬ô%1TÄÔÁ¡+L¦/E*É©hQÓ%J’Ü¸éŒV\'I”.¡D”›#l4ÍÒÄ‘cÈ(\'y9)¦ÌJ\rßŒƒ1‰V®rdB¤ÉéI—ÚŠÎ·sæÜ¡4…N•8wäi&kE’1¢˜à™¤‡FPöB€bQAÃP\Zf,ˆâ„dÒªCV\rY³hÑ¤5í¹°Ñj€M¡ËZS€¹‘_–~ı\"Ã*‰¦xß0Ä8Z>ÜQ˜/ºZ‡$P”êÅ†˜é9ç&Ãhÿ”ÈL6qÔ4s£†VQ88%Å)\Z´\r6oşÊù˜	IO)m§’Kr ‘I%cÀ¤ĞrÈ!\n*ÕQ‡(u¼ÁFHiR\'šÌAœĞ‡\Zq”x‰&¡ÄsQ‡~ğ´Àb,Ã„ Al°À\0DâSÄ’¨ÑÉ’´u²“%‘\",\'R¢É‡œAåtÅ1Gl@@7ít#æ˜g0À˜÷Ü³æ™eŠÙšÄyO;nÖIfc€çšmĞ€\0 3N\00òÃ¢?<ó&˜BÈ’^±Ë ƒd0È.»ái1ÅÑi1?,@À6 ºÀ6öœSÀ«ÿ`¬´ÆJ€«ç´Z@¬¬+®öÜJ@«³ŠY@®´¾ª«²¯Òêj³ĞF+mì¼jÀµì\\k€×j£í·Ú²Ã­¶ë`ìœÁÚÓ¯³{¦®¶«®=óæ\Z/½íÈÚê°Ä²«¦\00Î8(ğC\"7$‚‰ ?d°‹Ã˜nêé \ZªÅ¡†ºË‚¼º6\nhò´$—lòÉÓ²Sí«+«\\íË.S³Ëà–k@¹”«ó:;3Ïøğ|ÀĞÌ³Í³¹âzì«Ï2İ,²ÀÂš´®³:ıl¬²cï\0ß€À,ÃÙœ\r#‘ü i¥‘BzÅÛ’f@§Wd@éÏ2È\"‹ÿ¬€Œ M´Û`@¶+SËòá×\ZmÌ8µÚ¸œ­á\n-ã›o{¸Ê7“{m6>—sĞÙäüóĞ>=´:ÀŞl;U·[@¾ÍªKk¾±â¯ï·Ïµ¬¸ïêœ¸°À\0(p3	\'œÈ3‘ÄòŒÃ“2s&ÏCŠÉ €òCâ‡#¨†¬wæK³=*+Ní¬ñ°,³ı-Ë<³åŸ[î?âüC\\¸²e³ñ,t;ÓÙÏxÆ@¡­C(cVî\"HÁhùŠH\0óZ\0\00€Ä ˆ°ˆM€\ZŒHÁ_¶R‰LÜB ¿¡6á	-ìbÇˆA!fÀ>A€l[ãú–\0ÿ±Å?l	QeƒÓü8gÄÎÎfà\nİµ\nx@ecl`=ªˆ3ÍXÄbÑb7­úÀŒÍBcÏ­˜ÅO~ÓRW;ˆÔ\0å@`ô\001\Z`PB\rp\0Ô°3È!¡°B\'hA`,Ò\'Nj/x±¤NÌÇ\nµàƒ¨-ÒI1ŠTœâ\'?G®ÎUñ“ëÀÇÍ°EÕ­Ò‹ëh¥êÄ8ËzÌr±SìÄ;]Âhï€İ<Ô¡²Y¹±,{›U­$^.¯Ê\\3å·\r1¹i\0$T\0èa!A®a\rjPC.Æ™dp‚H ÃB1šĞTC\Z¢iLÉPÀ¢Xø6²ÁÿÏÑàŠø¸¢É¥3\nôfPd‡ÎêQº áã‹«SD]W´wm˜®£FÇ¨\\êò\0ğeGc‡Ñ^ªÃ\0Ñdüæ—LÉÉ¬jöSÜ3p¹Æµ,s”U0°B€ ‚#¾àñ¼áhÄ…<˜¦\'ÜI‰â‡ZpBzğĞ0\n3ÍŸ¤;@éj¦J)Ğ\0[¼™*c)EWN¬8Ë†-¿HË‰ş’¢­«0cÇWzx4šEc‡Åwp.[+­JÅUL•Z4E©d›ÙÈ:ÑsËÜÕ±™Á;`D€DpT„pälPÂ&¦À\"q$«aÑÃU\nT‰ÿÖuXÅÊÊO| À“áŠ¢èF9Ä ©U­©,]SyE¸’g©#\Z-Ã(V‰Š—|]`ÕXzĞuhØ‡9·ÒÃ-‘”ğsM%·¹!6q\\1…V±ÔÅÙ€…ã7 BrXbƒà \nÃ ! ‘‰LÔ†b\0É@†ş¦Ád(ƒÒğƒ½•õoÜ‚_AW®ÍÙÌ½<n¹\Z´êb1hó ®D[É³b½Ùë1p8V= ËÀâx˜b‡ıÇ¸ÏÑÔ“‹Ÿ*W9\0ğ±ñ•ï®Úá®ip\0X\0ˆ@bPÄ¸À}à¸`„˜(Ş7Kíâÿ§pØ.<AOlbf0*Ì Š@+‰$ƒ™âwdšP¸T.E¹çº»>ëp2%[äo17—€İîëBŠ\0ñ~X€ííœÿæw¸Bƒî¥ıœ1iU»Û«NsØ7\0\0àC\nRØM®QŒA˜`&À@1\nñ\0hD+^yÃ,8A‡$pbœˆv´7”,h\0¦zuºèµ+V\'‹eí‚ŸíÀ­R{9Ñ8ƒk*VÖW™ªƒr@\ZĞ\rF0âo¨²¸´¹\"\0°<.ZH3-Üšw¼ˆóÖß²ÕäÁ=Seë•VÒà«4©©\03–±\0˜@¿@>Ñ`%PAWÿ@*F±\n4ğZP\\ñU´B­h…*v®\n\\üa	¬ˆE7b‹oÄÂ‘èF7Ş”¦b]-kU{LS­m¬ÊT‘€ÆĞ‰ÎõXÔà58„Øµı\0\n£4Pƒ£\ZĞˆD$œt$À8Î4~-N6®rÛªM#ŞäÏÉ/qĞâ×»æ´t\"a°káàx­k-°ì\"Ï£(ƒ*´À\neÂkæ€b6Ph8#îY\'z7\"±¹ÏİŞ3˜2J/wÖËÏP#j@0dŒ:™üd¦{X<M¦“Ò¿!¨C< ë±€Fô¥¯õêGŸèk\0ÛŸOıèWîÒÄÿş–:vhæß.)È­lY¶ı”6fáôV­ª!V»²ÇæèxåuVÖ‘\0r§\01p6À\0‚Îp*â·\0öæ€hÓ\r	1 \0“1&Ğ(Ù °( ğ» )Â(‚P‚ˆğ‰@1p75\00ÙPR°	ˆ  \rÊ\02\nÀ|Ğ\r7ğM0VP„q`¶ Ø‡zqwY÷„uq§}Mˆz°\0z£\r¼5W`c]ˆWDsRMôd€:‡åDÊT9Ì$-­Âõ•Aßğ\r‘‡İ°\r\n r\'Ê°*‚À«bou8Õó\rC÷\r‡ \0á@ˆ«·zä°\rÛÿ@¬çˆèˆ‘@İp\"“\rƒğø 	ˆp\r¼ÆÌ€S€½v€	J‘p×PÎ0\0Û\r×@CX´ ÀÀ¤pIPUªğ„µGwq‡tR~éã-ÿ\\‘6Qxå…Ğ¨Kc8Píup4ƒRÖ?8•x¦ÂSˆ8‡ ‡Ğ\0qø\rÛ0°pq¸÷Ö‡áà\0 \0/@?P‚‚8)WğçÃ‚\r1pˆÕ¤0Ş€ß#Ş@Óp€Å@Š÷(‰ğ‰ˆ€–°	“„\rÌğ(3€ƒ@‘ƒ\0\n%È»€	Ï0=`ŠŞ°	xpvĞ	¼nQ\neÿĞyLˆz‘ø7×¢JÙàPluPÿÔ:×r\0W]ÒõŒ®cVRd†Cfhãµ~*¥LÍ²Sœ…(æ0„\"\0kÒ\rĞioõ˜€ÊĞ5\reÂÌ Wˆ0‰€ÊĞ’“\r/ÀÎğ\r×ày9—/ ‰p0¦¨\0×à›pŒ\râ€\0DPg†øÀYa‰À¹\0ÓC\0ˆ0/à™ ğ\r@ĞÀˆpÉtĞ	v@g€Ç ÒTxµ\\©Ã@!†3«QQäQÕ%b $\\¢¤-Ìˆ-Ü\"_}’•ˆxˆ‡ å‡Û°tö¦\r‚ t?`À‘„±p_ĞƒøÿÖ	ÀO‘à\0İ@½–÷Æ\0cS6Ş³ˆ\0I‘/`\r/ ™ãg6˜à¾) À\0eÙ\r¾‡>io‚P‹ÚàˆÚ`oË²>™³dQD:µho…Vºy@©ã3·	†å:eE6s\0Æu3ÀEÄÙDıó4;åÍyqH\0Õ¡ÛàCöÆz}*ˆ@ŒPGá€§‚ÏĞ\rø?	yŒ`Ş ût\r Û€¢`	3\r(°›€Ì—}8˜‚€Ê@>T‰G£5·¢5İS¨B2•Vœ	uY*Úh¦¢¢$]†uUK[K9SÅ3ˆšhÿUœ:@§.j’<Ë\0òx(P&aSaV÷€w8İÀ0&>$À\0\r`©€‚°\0@ ¥ßpgÓø€.€	Ù@­*³r\r\'˜˜0$\0ã\'÷@/Ä2n\'³^uYªäPÔ¨,z@u¨±¤¨©$4ª3åQQÜj¨¬„¨¤ƒ¨…¢å¢J$ö“üÖ®,ÚwíS\0W˜|K—AĞS0„â\0ò¶z¬·£z‡%Èø°0Œ€€€ó71°°­o	\r0\0ö€«Åğˆ‘ğ\0ÇØ†!À~x…¯†…ô‚…¯E-@4xó-‘Õ®¡ƒõ€2‹¨hÿõ-‰\ZPˆjK%WæJQê\0¸”\0~õhc„c3¦¨pÅP‹úbÉÕ¨£d\0æv-+•;ö°\0¯vM‚b¯³vG\0¶g©÷º\0/@ÿÕâD\rØÀ\rÑà¶OEV 	h°c\'vpP5ğ\0ğ\0# ±÷¦¬RÆLÚ¸X‚÷2~7\\5S@I;³×ºP<‹E<«:<‹]¶4ŞåWV4<Æ­U¢ë–Va”›EjR{Ì2,Ê2,rÂ2ªMò8kİÄğ“€!SĞêÁ»j „”\Z”Ğ`€Tc	b fÀ)Z *fP£ d`Å€7Œğ.Èz2ö§L‰c•¯bFãÿÂIÆ8†›\'Š­3V¨Ò…]Õ­½T4&eQë`QÕQÙzVõ®—Ã,ôRqØfM`|g|×”AèÀ¯ vòÖ\0‡ÀÀüœb7o„	ko<Õ#wĞ\rÚÇ\'gr¬î²4M“;-#n´²2é•XóS•\'µÃ¥PØ²0œ@Jº¤ëE5F_¤¾ŞJR¹$RÂÔc»ÄO|ÊnB£¾²tß0ocÇuÄX{rw…I§zÂwÅB\'t¬t=Ø&bRMX+¯¬‹…&›*Os,®r+U7-GeÆ½×¨§7+:3.be\\…z­ÒE­\\(¢9ƒ]!¥]AË]A»]œ†cö¹\\]ÿ]år,í°–ĞÀğ\r`tJ§ÅJ·ˆ™¼É|œ<\0mb&ÆÂm¶sµù×/gÜ.È2A­6u$³-µ?‰S?ñ#jŠ:¢vDO4\\\r„­\r¤n»y\0Áô@êFôKR¡Ëc!ÄBìK•3\0õbF$-ÙfuØv4W[MGS§Ò‚*Úh2#L2á¼ÆKã4DuîƒLµL3†³X/»o–öI¦d:YÌv;æçK|õQwÈ=R†wbxJ.5¥²–Ã^‰ãÆ/£†Ş{Î\rÑª›Îë|x=@C„XÃ	Eõ3W¤­±”´…]úœK?ìKœÆ]òĞcËÌ]°V6”£d3eÿÕ¢ÈYSñcnÍÒö*€-İh2?Ñ(3Ñ‘sŒeÓr\\V6“³|úP,öÌõP´´W¿ÈGë].È8&\\ÍÕò\0¢Õbºğ\nºP	QQ	º0	\nqÖ-„Ö-äÖ%1ÔuCÄQa ¶ 1¯jà´À’\0l0CÁ%k—oĞ`0,ĞQ	EĞuğucĞB™Ğ ²2‘rP[ÁÂÍ%”0—0B	@à%t@	n`¢ [Ğ zÀK aiNàie€b£?ÂOğ@RÎğ\0î€»ÛG˜!\rİÿıT©aØ´°Ø—P\"8á%—ÀZ®áD\0*ğ9`	\0¿ÁeÀñ`¿ñ]	AEŸ€ ‡0ô	\n–`Tp‚YÑ™ E Ü­]Árp¼¡=14C–`	]&–Pepgv¶	``wVé	ïmïí	›BĞ €,Ø(¤Z‚@^‚ÖÙ7(Ë*Whu\'&X{ÜÜ/UÃ+fŒ*ÜÜ,vèÓ@2íÔÜâ-hr|^®|g’Äç@qÉw& ÊÈ§Ç÷nØ¯00€{ÊP€;ô(u37œ\"gœ² òŞ|¾ç»\0ècsoU®7Qÿ2#<¡ål(eãLÔÈ¤²6Í®ÿÄ§Ñ”|ú§hu\0³S,\"Ì¿ÄÓmI³4¶ƒ5³²Ê¶¢+W§ÿâ5‡â\0ã\r—‰â°˜’Â)˜Òk˜¢ës)½Ş)\0\n8Õ7#³>)Ã8QÖ2ûã>o¤?2õXë†Fdˆ“¿úJs…P¡c­WÄ…XAÈ,İ&<¬ËÊåîm¤şm©N5®2_±¦M‡b0ß#ŸÏ€6v~Û³= 0‚˜\0\nâ=˜p˜À\nÄîÓÆ>ê38­8MÖ½Èô¢2e•ÍĞú³XíœÑTÉÑ¢”¡ÆUEê¶Ç,öÇnÎß&A\'_¸Î¢¬„;uÅâ&î0(–ÿ\Z\0İğ÷È„\07€	1\0\r‡À*ğ	J0\n(şf pY\0)(@>æpo#›‚°-9|Ó8\rh/s§†‡?‡sœQ”•ş“¯3A“­%öÔ4WÓ543ö:ó\0-‘.é’î+ÜS‡²ßNk`Ù~Ï£`u\0\"tP¶@\nµ\0\"œ°!¶`zĞ R|€\n»@ì\n·-ÌXV`?•*DjXpŒ‹§îz”SÍåV3‘öíy¨ƒ:KûLn25ûƒxµ|xöcÂ*%?h$Gs$£wÔ\r%à$’0©\Z§ÁG£PKÂHÎ\Zğ$\r´!ODV\n²€	KF³Eÿ	\\P{pè†@ŸÆ3\nåaâÏ´ıv­ j­‡\n¨‚5¢®ï:\"5Lºd_¤Á¤KôpR,Ø	dW°`T °@\r40QàÄXXÀŞ¹Ú\r @@\0ãÜ]¨¥—F`Ö¨Q£¨™\ZMªfD¡FMZµjÒ¤%&­Y37ÜÀc%&[Iô ù/[6W\rà[w ŞŠÖ…\rvİY±cÉ­g ŞºzÙx5p\0İ]È«÷ëyÔ	^X°á¹‰çª›7Xğ`uêH¬®°àÇ+:ì‘`Gv!\nLHPÛÃĞÙ•hõÃçb ùÍdÿ¸\0(cü¢\"&Ì—L.ßxyÔ¨%Ö\0ë¤NœKshJ2æÍ˜1r2‰B\"GNO»]ÅŠ,>²éÅ®g¯¾\"ú¶yóv­[n×l]×å7¯7±ÿÿúRl0ÌIL\";\0Ì<ày0*Èƒ*ül!Œ’¢…âPB(|\r¶‡ì!€¶L2	qÀ *`ğ-“ßÂø-Š(tÙäM®›dŒ:‚¬#$ê#ğÄødI*¨@c•S´IOÊÍ(\"+´öÂJÍÊ°Ô\Zk,ùÒZç.ıâ;K>ıæ+¯üÀ´OÀ®; ÁÇ«s2®L¨´-?¤ˆ4,Òfµ‡ölhµĞŠÿ(¢`3€\0hÀ6pç›b qdSM‚˜O‰XÄLbÀÀK6ùe“26qW=ÙÄZee•2Ò¤!m\ZjˆDG3‚HKÍŠU/®îª.±”M3¿½ä‹v¯+İ«/¯ºæ2[[\"¬Ö ŠÈİ’\"tA64\n\'r7P‡Hüè¡sD*é¤\0ĞÉmEˆğwTQ)¦yCœéà”vÙÅˆb<1ÃWg5ÃÖ2”P¢UvÅµ¶ñhd`ITm4t+|7½±Şâj,háBS[l7+àDÙL6½iÁE`2À~N`²+±T(]	ÏeÇF-íÂFK¾ùfL<ÿq¤“\0 \0\0º bÂ¦€\"ˆiòl*ÌàÀ©äc;=¤Jbn=êP[Rå–:Rùäg  Ş7|Şs@ÎÙ5å•º E³TK›Ó¶Ù†€Hºyà›	çW‡6qaÔQ/Æ¹áò†şùgÉfÿ9é”+L\r÷”/d5ƒJù#{ÚI¤‘HZ1®›@€bÎŞØ-z™fšcX™¦-T1TÌPE‹VNIãü4´H£˜óOéeUT9%’mº‰¥›oºY`H!çÄn Æ›×¼Ú1<Ù0nqœ‰ºñÀBã~±  ¡¡¹HÄB‚ßˆEpÿˆX€ğ~¬ 4L¨ÁF‚~ìj§ei„\\îá“»˜–!¾&j$J\\Õ\08’ä	`\0)ZQ\0Æ‘›\0@…‘X@7 		Æ\"sœâùÇ?&2B‹Î`¢å AÜ€˜`Ä¡\rA<ãj|#f0ƒC8 		g0â‚ĞF”ÁP0\"y÷ I;ØD’4 ? €ĞpJñ‰&¤¤\nME> ‚(„\"\'û§\0mÌ¥[£L\0ƒ‚*5œ9Ê¤öJ©Éy$€;Td’|¡Ã$À6±Ìñ‘™Û†Î¨ŒnĞoÛ`À3Æ³FP<cÏxF±EÿÆ‚ƒÛ$Ç9º±\rmü2ˆ€7Ä10B¼NøD1¢	\nhB\0ÚH\0&ÀÁŒÄb\0`†76ñ‰54ƒ(¦0Eâ\0:hÁè‚¥ˆÉKN2Šœè7ÊaÉ%+Ù:e€Àâ­r…tw#-I<‹(ôšå-+r|ƒƒäø¦åÎh90\n\"éÈ\0lóèS€ÌÌœLõw?dƒˆ`3ñƒD0ãÌp:°ˆM¼àÌ`„adÃ›„‘\r0ƒÊĞ3®à¾ô3^ğƒX¬Ã	@4aZT¼è)ªÀ	%Lƒ‹K|¢3.©Âh(ÿ,è©ÏZÖÄÑÎ,N„AËºD,‘º+!»YÔ®VË¬¡$•€è’øÍn8€\0×fğ!ˆH8 §(À\rb`Í%àfıAq¯é3^nœWĞ\"^  ¢?Ø\"AaˆxF¬L÷—QÄAİ$Â·ÙX##qŞxãŒ(e\"¤ !X¡9µ…v‘…@Vd¨yO´º’¬1í§²ëQÖšô‚˜¾¼0€9Ë—Ô\"¹zÉ²¡UÍ@ç\'1\rHQ‰}:©n4@Ù\0#!ˆ`±ÈZ;pd	BŸÙHÀ‹{õE¨qH€ÈùDƒZÿuÁ\r‘ˆ­ş€\0(Æh`‚¬Î‰ğ&ÖIˆ/À\0Ì _6@ñƒ;nÃ\0ƒˆ®aİ%hAvØ\Z@Ğ+xvLfÉWêà˜½Å-]ùK™&, ™1ÆK|®Xb˜%õ`i\"RzÈ À{İÆèøF8Âá\0û}Ã— ­6œÑ\rõ‚‘½°Ëç7 DÜ6œÙx4pkø\Zq»\rêc\Z p\\ xB¨0Êú‚¹f#Ì\0E$ĞÈˆH|ã\0‚\0§·qœÁ6”Ş°µ–EŸı€%?<ƒVf3ë˜4ég?¶XÚÔY\rgi•!Öp©¯ÕòÒ˜ıÛŸÿ/-W€29úÔÆ\rìJˆg<@·á(@6ÎxÛb\"Â‰èU6ªÚ™¯RXÄ.|kmAÌà\0Ç5`÷Œ€3€,ÜÔ\ZX¯…X­”ôgÍ²î¯gŞV7˜â­-½8f0†	ÌÒÏ4\nßç-h‰z},œ–ô(­D§-Isã5!B0¶íP€pvPà‡İ\Z ÜgØ)V‘=€ˆDĞ\Z1à­<>pnÿ”ì¸ÆSA›ú°qâV©jÜÒ\r{©Âóa;è=uiÉ[Âˆ`$\"˜BïxŒ ±5­>£ÅÑèùR{$½‘‰üê!ÛøHá‰K$€§[‡\0 ÿ‹ıÉt‚`@»ñÅú\rqÙÈ@1LğgtÎ¡”¤¾!D Cd?û!à@!\n‚ÌË‡±aüaÓòzvKZ¸Ò¶œÉ/yyËWÒÔ :=æ@ƒ1ºÕ)t÷ø>Ñ`É™8‘Ú¸¥Õ2¢Ü‡®æñš(˜„5¸@šh†\\ làEÈ…h@i°Mà„Ô„;ÀƒMp$%R(…QØ…óÉ‚ØP`\0#?Æ+¿ØCš’z4¡K‹Óc¢ù€°ÁÈhÂ‘“>K½»`“¶x¿ö–Æ¡½´×€ÚÛ†ı9‘{H1#1ğ ˜ ¨‚ÿ6Ğ6`ƒKà„GhƒìĞLP%hKğ´APØ€7z#gÈ£Ğ¢2\n‰ıQD{™F´Cˆôó-A¿³\r¡†pzS+¬PÜ³=û3B³¿¿ğ\nwÃŒyà–n!Éè¼Á n9‹÷ƒª‹}ëp+œ™#1p€x€2|\0ÍI¤ùE8f`|Æ_F`|5Äk‡1C14œÂ±šÃ©\Zâ¡—ˆ,.–Ò¢ÆY\Zéİi—Ç“</Á‹Çù º\nûŠw?ú›<Å`Å ŒÆhÏ‹$¤ºóXz	Îé4ÏaÆCà z %R¡sÈŠü=™\n*ÿŒ$€nĞÆ]<-l‡Ù(@qÛÁôÎ ˆ±h¼à1Í°¼wÇ!t“°¨û¨y$BÌ =Ò£0¨kBŸüI<Ú9“ ë¬¨ZÂz ÎÁHû»nÃÂÆ¨—Ä!IÙÈ™¬$?’,­ÔÊ’,¿°ÔƒàtaG•YË£Gy£ÇAÓu =È ½uP ¡ŒÊxÅ~¬u`½õ\0‹ø‹´xËLØ0¿°ËÅ{ÄÜ¹ÃD\rˆÒJ¥U\Zw¹+½rsZL°ûK°ZL´ÃH€l:€TY¬WÄ“ÕìËÖk‹öCÙ4·j©ÄÍ8;B‘!_IˆÆáMÉ,””=ÿ’	KXL©ÉŞ”=àM×Xõ37¿|C0øH–º¸‹S4Âw;\0z:YÅy\ZÉE‡ò,Oò¤ó”«[À¼°ÖSu ¦EA²ÌĞ Lt4LVZLs|LàñĞàM\\Év)€•tÔDuÉÏú,øü’—i™FÅ\0	Œu K#ä¼¥«ŒÃ˜s—Ù‘y0]OóÊŸ‘1p	GÈ„/¨„/˜„J¨„I˜„7˜„6ˆ‚Ix„G!!­„·ƒåQ]È\Z±‘/\00+\rƒ<D©ƒL`R=ø‚6h6 „¤Pƒf …¤xK€ƒÿK„)\00¸„8å6S;­\r1ÈQ<E(Õ%-í\0Ô7¨·©‚CuÃ7Ğ0 „) „K „P¨‚#˜:¨L•¥Ğƒ¨!X4X>ğ\03ø¨\0Ğ>0˜\rPÕ\'x‚\n¨€y\0ƒ£h`hi@†\\hdˆ†V`øU4uI€IpJPÓ9˜5€7X\n9Hh’MqàÑ)Q]×/àR­]¨„\" #©R*€„Hƒ!’v{İQëxÃKˆ7\03uƒ)ğÕçˆVJ5\0I‰5`ƒ7°CİÑI€Rÿ<u×-EßèÓ\"`R~=ø}Õƒ1 6˜M80JXgµ8ˆÔhÕ:8+ĞPå>X…àğ\0\'\0Z%Àƒ €Ğ\0PÈ‚Yu‚\n°\0‰n°‡(Ğ¯£\07@††dU;P\n˜¥Z˜8ÀÔ”¥„ƒJÓ¥p)p•_øK \0Kà\r€„zµzİV*°b [³q#ÙVGƒŞ\\H€ñ\rºµRyÕW+õV\Zù‚7˜…7à„FXƒh…9€J\0ƒf]Ó#à„8½„K¨ÓˆNƒ˜NxƒJ°T 2<@Ú}’0ÀcÙ:,ƒR`ÿ<X>@<À‚:Q·‘Qˆ[Ğè¥ƒ9˜ƒÃªNĞ.Ø‚-è.ğ?=À‚1¸ïí&ğŞQ`H°\0Ø\0P(ƒŒAƒOĞ˜:x6ƒO¸*ø„&Q¸E•U˜A „ƒa†×)®lˆ«°ŠA¸L¸‚+È\0#Ø…v*n\'#„AØ…b Bàà¦²µİ…`L#BP«şP0³âr¦œBÄg`†¸‚óR£.`B0a6a‡i‡©àbˆ©`\Zğ`-(†gĞËÃË©JE¦]Œ,ˆ¬˜‹¹Á\0	Ë‰90ìbË”È\n3™˜sÿªÜÂ/07Ö¹3ãÓ@ˆÓ@»^‘DÊ¡œÇc‡<¥<“’ÌFmdBÆ™B>d\0ÒFBJO3\"\0¤P#A\0Lˆ`f˜†Ù`‡Ùäbt2ÚÅœ½W‚”<cb áLLqCÌĞ°Ì37µ(k!Hœ.CÂ1œãåoã+Nœªäå` ª‰‰b.¾bµ¢Ö\n€	€¤‚*f°8LÈ€†Éd‡1b†˜ˆñæˆ1áS„ñ•:ö•’NV^gÂôŒA9Œ8*‘ÏÕ£2a½uk‹øPØØâ·‡8 ­¼Ê©ÙJHáâ‚VòCè‚‰\0º#€òúÿ‡+AP«èá\r>âb0oæhˆñ…‡8£>şÙ‹cÂĞ’i¥=L>F”ĞÄáÏz?»˜‹)Ô(G“|°?i	ÙèÁ	h\0I1å­hØ\0Év èÆ1‘‚†ñCÀ‡#*\0q‡âÂ±şq+LèáŒÎf ¶`Oâ+\0…Ó`P¢Œz%˜~Ìàñu|™NÇtd¥ÜÑëĞÈ·.1º¯°³|LºHœØàåÚÛJªGâÁ¹‘±êp[<‚^è©Ú3Æ	p€HP£¶Û£H˜BÀáÒ¶ä¨d`K–`×…³skâ;»‹¥³;\rÜ^r#@’ÿ™ne†àc\rA”Š’^‰e÷˜çŠ(âŠü°Šıˆ™ î‹t¹ˆ¤æB\0%Æ)\0¥™îğöîáIØ°\ZÔÊ=€ª\'kodbëÒNm£J.®âJvaØ™sş•”ê‚… ëÏH\Z¨aPÜ¡±P™-™ÏùÔ/ˆÖóD¶ˆÉšÄÇ©ëĞ¼èí~ê‚Nåy!oUVlÅ&¿§â	 [:‰#Ú†¨º‡Ã„D #)º‚v˜Ó®äû¦dışqP(„C,¦·ŞÛîÜ¶ÄŞæÌ<ÉÈI)‹(?ÉºÇÃÅr«p²ˆ¿1áòåæ3ÌãéûóJìçÉÿ2G1ïU^ ¤D-|€nxf.„J?’0\0¼-‘v˜+8ış­Bø#\"Ï³·”áákot\\I+Ñù¤ğÔhp´ ğ˜Äº\n—<1™<½hË³¸Ç¹`ç\\<ãd¼{¢JA¢h\"}/˜]ÀMÙhø1à„¹á„¨xƒ7¨›:ÀƒOÀ% ƒÙ3Ø…cà#?FòŞîmåÖ’LÎ¬«tõË·“ĞöpË:ÍÒÂö3QŒûƒÊhLT¥UĞÔH8æ\0‡G‰Ê5Sš0ÓKx‚4 éƒ<…P¸§…éEÁ;ˆN€…P¨…$…ÿ^Ã³<îm¬ M	§Mr‰ğÂµĞòŸK‹şà\nÍŠ™vk:i9%;‘ÈhŒˆ‡w_)vX©˜·•ù•|˜×Ä3?ïVoS®©‡6XX«eŠ€E n˜\n¥°Z_ÛN0S¡8Se[Z°‚-ğkÁ·qùkŠˆG£I™wáø\nı’ˆÏxŒºûĞ˜I¨£ÉÉË€œ¼¼Œq%ØyÃ,ğÂÔ¡‰Ğ“$Ğ­kuåiñÜ ‚)PØ¬	EˆŒYÀ\0;È|À\"\n`\n`5\n_ZPŠ¤X3„­ eÙ4ì	¥Ğ!´Âw,Êdù\nç>:Dƒ0Î£™òÿœ‹piyÚaÂÇ˜CéÏp\Z„(‘ÈD_YòK,—â´‡u ‡Hş¥ŞBº=”€{(ˆmƒ{ü£PX¨€Ğ<Xzià†«¿ú_e7@S+ÀT=À\Z|Èd¡¬ÙÖ	4€o]½ƒê\rÄg°à@ˆ\r®;è°^6„¨Wq]Çó,9O#A–$™`¤Ëyô¨“9óåz5IÒ3ÀÎ^vBí±+`Ï\'Ñ C‹ıYT¨Ğ \n|*p\Z´@Õ¤¶³gï^»ğ @€™2é\Z3ÉK›G`Ô¬ÓèVˆÕ€5ãÛ,-`ÜĞê%Xt8ÿİÑS‚2âÃ—Í!¾zš!6HphÏ#†mÚ@Ç…,Ô¼Qc=’ó@	³vËØ-ßÍC€û@Mšóh\"ØùîpÙˆV5ú”‚ ”\Z~µ\0R¡>Ÿ\ntêûS­F Ğm,Yh0#rÂQ˜JQ&½aóèQ#0rXèÛI°ÄG(sÌ¡	‚IÈ!Gu`Ç\'|d`Ğe‘”\rgœ}&Ğhu‘@¬}äe¹æj\ZuK$­s[I´ÉÖLÂÍ£:ÂÀLólÇÎ[]UÔVE5xÚEU•\n5”ìh‘@p÷ˆåYh°\r–#ÿF_è2ß$m4ÒÈ\Z³á…`pÄA‚Up’Ärä™IH,øJŒ‚‰@–j@Fİ…Èaˆ\n1\n\"‰ÈâA±æQl)$#H·±”‹-’¤n7&\0ÜK7êÔH¤úX’WM\'”Uù”TagİTµF\näQ@^WÀB’G\0zé©wƒDPC™r|Q‰|Q´á…144Çœ0Ø H¬ÕçZbÔ!†¹bà?tYA5)†v,9j©A”¢ˆeƒ)‹;²ºã¦.\Z¬0O9Ö”ÒJ/&GkE)\nÕsQÚCSSE©T‘ú$xT!9ŞV˜—¬ŒÓÿ–<[|eêbfQèR“Œ1F%k!!†#éŠACò‰T±„iÄ 6ööÊäÈöSw\"ƒfÑB~vÀAš­˜‘Š-‚40j®ƒ!¦$!\'›J¾Ñã[p2©SÜv?bü÷s<$‘€+õ1‘Æ@^;\\’%\0:è È/Tºè‚ƒ/¾‹`BˆL#M†%dübÉ&–X²:ë›”AÆìeh‘…Úl¥{sÆî÷È€+Y¯¾¡hÚ³¸ü :Ïhò¢’Š#p8ê„÷«¿ÚJ/V÷Fµ]t³VÕ;°´\nÛ»±\\‡¬ã¨7N\0?XB0CDèOÿÄ\"›¸ğB~à‰b¡4B˜#ìÂ4 \'\"è‰2”ÁZ\0Et2ô	É:ßÛÕ|ò!Îàƒ5\r¡Í@ö¶¸µDD&„UÖ ¢Å&G/A@hr€âØ\rR\"ËÚ¯ü•¾Çc!¼•¢fÅÁñØ#,c	@8¼ä€ì¯Šú[„Šá‚A¼À3È(²€‰+\\a	LÃ.h°‹]h!fØ„f7\n3ÂJ[Ù†¯B¥\"	;Dy’PÖDç}¦R&R^FtÓ’°Y§HØé^†\\8Ş¤$%À¡dM$ò«!Ş\nXi\nø(¬‘²;4PRI\0{ «éñR\0\Zğ\"üBÿ\n‹X„-àcƒ „! ‘3Ğ`Æœê¨ğ	10(H@B¡A]i@éJWìq%\n))Pi\nâ´c/Q5¹¡M¢„´€ótƒ\0ØF¶O!1©jqIRB*ß àn½d\'…\',ÀÍj Óâ¨s2õ]‰•Út\"—$\0\0PT\0¨Üş~±‰EdT\n§ˆÅ\"8ñ†$T‚1Œy\'l¡‡•®tI`Œ(DÑUlc•^)Ï*Ğ‡êt+VÚ¦O%ÎêI¤qÛ¿0ô®˜çßpe8m<ã?ÈF6®:5P­P£ª$ÌA“½í\rT	Ş=øÈ­l+×ÿÁU¹™¾m ¦çÀR;u÷ €ß8\0 \0™Ai€ q‚Â\"íW¨C)DQ\n<¤¢høÄ*pñ>Œ­P…*pWğ€A7º	F0\"çÑR;Ësvìô®Û´­6o+WzVGX¼‹ç6Ö‰X< ±¨A\rq(×¯`„| ¿ƒ\n(\ZëÊĞÀA\\ÏÈFÅZÉ|š†\"\"¬0†5Œi£(í]ÊÉR	TVb	§åqb—&JÑıÆÀ0€Ä¹ŠV%h!‚›HÃ´0\n>DH¬HC+>‹\nU@8ÂZP*Ì Š?,á®UmjÅÒX8U\0îÈ+ÿNÛq¬Uæ”¶LÜf*ñhš§©M­q‰›ãXŒ˜Ä±8Ä”ûCÄ‚/PE\rŠû\0h@Ã¸ª}@$˜¬Z¬)ÙÎá:y¸\"\rNH…{ën…Äâ€¶b€;\"Ú×t\0€ÍM‡26A†Q”¡iĞB\Z¦1B€â¸B/°Vô‚(„!¡Œg€zÎÂ4ÂÈŠS¤ÁÃÃ5n$¶Ág,Z©eíy¾1€Ğ¶Ì¥6µ6eK€ú–\'Ä$nÀ7Š»ã÷8Çß0î7jkäæ¸Æer“cÑäL+\0¼9l¡ÃºÆ™ó\nÑdÒN¤ú(W¯ØCeYË™Åò¸²\0@¿EKT\rÿq2^~.°e&óXµ™Ä‡±\rrŒ8Óšf-#ÀZe(Ş<Ä!€D0ãÊØ7#À°ä5¯`±o–\\İ€,`K~@7 ±daC#ÊîpÙ­î\ZhØÀ†÷¦‚Ï²¼$¤ª\rT\ZsŒÉœËİÄUâ–ØÄ† ,iv\\ûĞÒíp<«şFj]‹3ßx®}zoıÎzO}\0çÈ*\"~I_f\0(¸B6¾ËL€bFö¡Œo„c\0Û¸3ÂŞèØfûÌ÷XÀNp†*ÀÁ\ntE\Zrüá&›ŞKÎøe\rb\'k*ˆj¡Kb¤Â¼ãbÍ{Û[ĞÿÊÊ‘ĞïZæ3»#Yd‰Ü7àİ€kC?‡ ´!Ïm´ßÛè†Ó·!ˆHhö±ÿ(ƒgì7°ª80Avïî¾l\'€9¬ál¢„`F\"	gDÂ\0ÆÜÅ4œñ\\cÙ Ç6‘UPˆÉàÅûí`+Ğ‚µ¸)F1ìXÀ»ãş7¹ÿEÇñŸ½í†`ÕãµÄ©´¨´„:$Ñù„=8%‰ÅÜn™š}Cû<Î+€ Ì¦eš¼á<1‚2È“ <k1@Ä€ °Ö‡ù˜S±úÉ¼© ÕÄ6t;\\!$€8$@ Ã¼\0!¼À(\0`ÿÀ0B7hƒÃö}ƒ¼\08Œ_  !l‚\\0ĞÂ_Ø(Æh‚-|”ku¼94¸¡É	ÿ-À94ŞÆËÛ@B^Œ «ÆP`8ıM{’äÜ•Ç=p‰ã ƒ\0„Ã½‚ä\0‰iƒ¼iƒk)€2B:ø•—€¨9U»™`†`¦‘C0B,@7ƒÕµŞÂxƒô &Ä‚<0Ã5äĞ\"l&ˆÃ`• @ \\ ¬]\0tƒ |ƒ\0¬Ã&Â9€7\\\"lÂĞŸ4\0C2ØAĞA-ÔÂ(ÿ±aÇÁa”Aƒj½½¼‹†”mˆJŒ(r<J±ÿCÅ0ÛÅh–á‘Ü—¶EÑ9#Y@FBÊ¹Ö2@,@YÀ\0À”¸Ä`6`Â\rLÍN†78CÛ%À$ÂŠ3ü@\"04\0XÃ/ø€5l‚8hCXc\"¼$3 ‚õ%BH>ƒ6àÃ üÀÛÉ¢2dƒÈbdƒ \\¨l‚(Ğ)¸A3t-ä-ˆ‚\\3$İÇ¡ØŒFó,\n†MÁ@ò$Ç@ˆÒ µÍ&5[à´Uú”š=:ŒÃ#JN8<N+€ó-@&œ 4#ü ƒXdCÜáC!„C7üÒ\r(_pU6ÜÀ3,İ6xCIZæI*C,œƒ @0Á%ÿ€èäĞ5$Â5üB1€B$@$‚7hß3tCƒ3À\ZD_(C=Â4$€7$B €cXAè\ZxÂôÈ!ä<PR±FX6Èˆˆ@x„YªKTg$‘DŒ½ÄQ4I¬øT–øÜC:\0YL\\\0´L,©–+iÃcf%><f$¨‡ûÜC$‚IŞ€3¨§Ä@7PÙ\rÌ@Ü@\"$\\±a6¨_\"\" @7´C=°¤‚2$€ a\"\n0> a!ØÃ0ƒ Ë<Ã\r€N¾@03#h\"$‚lÂ*Ìüå,ŒÂÄ€ÙÙD0„kŒ‰4„	mHHpÿHl(RIdŠË¥FxNéåæ•L#		–„Ø¶…Ã!<U8„CÿıX eóÅ€¼¨SEÑy$À3\n\"¼\0\nXâd\\Õˆ_X¼€8dƒe¾ Ş€ &lƒ^£\'pŒUa#&Ùmƒ BÃ`Â3DB(Ãƒ‚¨Âğ‚„‚-ğÁ3p•îdÈX¾Í=%\nÜ<^DèaI˜ÓÂ´Pˆ´„olM•*	;\0”@qÙB1Õ–lÛ!äe88\"Y%@ëY\"İ@!@6(¡3”ƒäp	;j6@!>XætC6ì!e6€‚8Ü\0Vùf$„C¼ÿ\0\"ü¦8ˆƒú1&l‚\'ÄİI–ä¤ 7l7@G\0RÊ 9\0\"(ƒ\0˜ÃBBgrPIDw†ˆ|Æ†„‹¤K¨Åã£lÈwN)DìJÈ¢˜-kY´§³şÕPâ7,@$@thÚy\0_¾eUÁºRYŒše(@ì)À¹\0Ã&€˜Ÿ èÒfz×•œƒO\nÅ‚‚ Â)äÀ& ¡j2Bkf#@ì1Â7´Ş!4À™¶^jZt,6¡Œ—M	ËzH\nyÆÀ`È‡ˆ,¥`\'Ãôj‹DbxÚ£Õˆ’îèÎV¬LûHUT5+™\Z—îÅÚ`7P`2B0ÿ‚ `k¸ŒD¡e´Vk9@Ûâƒ0Ã\0œÂª ,!C!Ã\"Ğ\rLÃ\rŒB\Z”AØP$@ ”¤šúekÕÀ\0\\CÜ®Ú*±£±È7Åª¬ÂÖ(•ßrF>ŒğáÁpˆRmÊóŒ¯õBÄùhéÊ˜XzŒC9À7¸Ì\0° sB¡¾Éà\n\nBğÍ(ñaB!¼¯zZbƒÆ^6DB¹ZFìáÃt_ÜaU6B$Bğ!ÂÁáƒ5¬!È\"èÆ©<½“µ•GnÑ—6Õín…w‚çZŠ$ŠDø”z¯n°v‚‘z„iĞği€,hëQñ0Í)\"z>dAÀ‡¹Ö¨ÌÈÿ[)€5QíŒæ†B§¢…;ìggV†e°ÜLJh;€yXpHê›µ­ƒ5Ü¤úèXômíÔNyEWEy²\"Rİ0È\"•hTÊkò°ÈHÔÄËQrìÄ‹ÌÆä-ÄGDlÕ%îÖ,ú#ÎìD9$Ò9#î­–ÈèÎhÂmC\0Égd‚„G‚\"¼	€=˜&R¢«2@7\\ƒşÀğ1‚;Yâ ‚dÚò™•\Z6éV‡™ùÑh3!q¬ÊÚğ¿DÏÂ°D GéJÜ© oòŠìˆfØ°¿$²«ì‡Ä×=ÜÃ ™;@9ÇÒ¶­ç—‡Î.N­ -À“XÿF\"€(Õ2@$<Àãè¬O`\0!D‚= ‚¨f7XS±$@V)@8çT*075ESÅxj1Ÿ\"k”ÆFcg=¼C=¬IŒ4Ô ãHqÔÄ:¤to¨Ci”Æñt\'£pLßô\rØ\n\"2²˜^%ÿt9¢¨áà Ÿk\0è_nò‡ÕÕ;Y¢*Ş\Z¼µ–¨±–‚3du!ÂdW[À8/yÜ	‡™7yÙ¦×uˆ+.[{klÊòn”>³Ä¼\\ŠxfÒ‘:Ä[—/yB²–®˜Xí+ı4\0ÀO\0”ƒz˜Aò^p¹–3æl6˜\0D¨A.Hƒ\ZPÿÂPÁ1@C’]\\É1Ù,Yi3™3lP\rv	;’—-1tÜ´w~lhˆˆø>\nÁÄHf \rOOr7L\0÷/ò=i4EØË½óZqP±ØØ™m,ıüì—uw[0ÀDA#¨\Z4C.ä7`yc5H-¸Á\\%Ì‰$ç¼ÂØÀ,×!À!$™3$\\±¤’\\Å•[RlcG­P©Ş\"2‡¤ÆDÜ¶E„DI÷jı1ªàˆpìÄÈ*Õ‡ç&QG˜aÓXËå\0d—DÑ_ıUb#`@&°É\Z|·\"¨(Bx+_ ƒ\"ĞÂÄ\'hƒ˜‹|Â¤Á(Ô,ÔÁ-ˆÿÂ-ğAÉÛ±4Ôs¦L˜E9=A[‰lÛD¢°Úd„ä1Œô [¨´\nÂ 2C”G;Jz-\"J4y4Ôª±R–À¸ƒaoÛçº@\nÁ$°TA ˆ&ø9ø¹}ÜÇ$$zˆAiA\n4ZdÁ)ìÀDº-«ÌİAôâpĞYóyVÅÁ1´-G½hMAl†›†—·HlÌ\r«ôÈ%‡ÃìmìÈÇ…\\Št¶z¡ÄTPÀ“5y…‹ÊWM­ÓyÀ\ZT…Ãi×q—3€31µØ\Z“\'@c]WƒB!Ì€!du:F™»ñ²ÏØsÇPÀÿ“ã¾³ãNGãî\nÌÌ)mPUTãá¡¼ÄKÛ(Àì*]{¯\nÊôàPìYÉFY¥Dq\'Ï3C€¼E–D@`Ü‡uÃİ‡Ç»Òèª®rµ­ÊKÀ!¨.Ê+WË@t¼È›Gjİˆİİ=DÂ=°ó˜)´•te5I¯”iG–\rÒğp¬‡¨yMÓ†àº6#ÏƒãFËÈëq•:è„LTÒ\"wôŠt\Z]µƒ–Lœ+¥|Ë|üi?@’í3BâØ¨}İ›¼Ú7@ÊãıÄõ\\8÷¼}}…œ;ÔM©XS›Ùw\rEV9°\0R\'uÇ[‡l[“†7³6ÇÿÍH Çm„ôl¸\n ¯t×s=”>J Ì‚7Ïi0+áü³¯ı+´m`Zq¹¼¡œî«Û­uÆù>ğ§Öï?4ÄQ›Cu…M¿•°RCÁqPy<åQÎu™—Á×ğëÆbyÈÖ4ÅÿË\r‹ÆÁüÕSOJ;Œ4ë“JKü÷Óê‡<®3¾üŞ;È?ı«İ¾c§–¨óÆ8gÀ6„öÎ+ğ€½‡íIŒ±@»‰$.àøp#Hì\n( Iò£È“Øµ4 €%K-Ù(``&Îš:q®3°î€ÏuõÖ5P/(QŸ~=ğhP©ïÔ¨::­æUÕŠ\0ÿºy`d3ÀÔ§|Eqâ,@ [ƒp\r\ZÄ:ô V,¼ºñíÖ7ßXû½W1Â%¦$ÀÑáG‡Û@6¦(3æÆš3?´ùegšììÍ<©sgÛœ£U÷ô‰Oi¶ °Û\n}Ê5êm®ïnWEpà·Ö­^…ƒU\'{mÚuÙÒÎ|¸­à½nÒïİó;»ôˆŸ£ãb‘‘#‹oìğqeéEnî,ú=è÷-Y’üLß¾éÔ=ùçlşó(£ĞJ*İ¬âª7­èª¸âÂ²Ê«¬**6Ÿêpµ˜âÛ0>”VâDğ8\nM´Ğ>­%“$:Í´âA‘&Óôã‰FÕØª)¡œ*ÿ·¢œZg¨Äª\nÂ!ç)R°ÂB@$¿j¹²Á§µ*µ)I—>së!˜@Òæ3/;³³ï643Ä4ò(³+1#‘$ìiéJ›r2)ËšÈ²Ê*/Tk¨³d›¨Ÿ²!ê©#<à ºJ”«±’TÒÉ°ĞG ˜[ËÆœxÚ)µšê³Ó3–®\\é³‘&¢/&çÑU\rKŠõ¡SO\ZmÎ9]r©JvšKMÿ,¬í¬Ÿ.D4* ’í+á\"ò*­º2nIy”´TI¥(0@¶šÂ”?ÒzU-×™t­iN\r8W×Yë#\r^]s—´Vç…·¥Šğ‹4Šğ=7_vâ·4uüöÿàµ¨Dxµê!\nbˆ=r¨y¥ç\0zÀjH±Íj¬yä±–ä‘­ex˜”g(ĞÖGc1ÕñÆ–Ö©)FœußÑv¥O?š\0öyhŸ÷5ÚçVo\r˜èÑŠª©¹LgÆôB§r¤0Y¢Ş™çÈw\Z…\nH#¥UGc\'ÌJxFVÛä‘W^YH	‡0ÂÈä‹J*™$ŠJ¢˜äIÚ˜ä‘6Úø{pÁ\'ñ{’Å£Cï/¾À{ò0¾1îÃ$È¤’1DŸDM˜N€Yİ\r8à õ5à¸„vÚÙà¤pÁß[0şŞ{Œ¼A¯$9ôn|ŒIDCÁ““7¤7ÜÿtJ^G=*æ8ƒ”;èğ88	â;„¨‚Æ”H#ƒÄP‚‘6ˆ€*¨ ÿ\r`D_hÆ\0‘ÑdDC\ZÍˆF5ªQ@6C\rTCë(a½8€áu°F\'Ü 8@\"s˜C&ìº¼í­q}‹ÂÚ0] PoÄ;á‰1ˆ¡›ƒ`\0‰0Ô¡™b3!º/Œš\0ƒ$Ü@741uq¸Dé¬Ç†FPlxÄí\ZñˆG¼awãè&¡$X„¡«„(!ŠNc¨ƒ’0†$$m(h§‰8Äá—ˆC(B1‡?ÆAn°BDñ=,¬\0\rÿ(Ã<ğ3,a	Z (ê·\rğoOÈ_f0€nP\0$`3ª¡@i8pu|¢$$a=IÄq˜%`\0’\nT ÆoèC†Ah”œäú†ƒšqry£aå~(*ÜpnÖ¤$21Bc1@]Xx‰\nvÍh0Z\'E\\ò²–’¸DØ`¸1H¯q“`#äÂ€ ÚxÅÓ…ãXG=ÄÑá|ƒ’ 	6hbGÀ¥)1MX†ŒÃâ í	AIĞà\'x€OèRT, °Àşàı… €ÚĞ‰N€”-šÁN>‘´pİÿ\ry‰#ÀÁ‰„ˆPU\"ls‡™³á\r…èˆ:`nnv;¦åŠ ‹/lNsÅCXo¨¹U¬ KáØÀëEPÀ …\Z€a…8À–OT)±†z¶°HPŞçŠPBÚğ«uøœñ ÇF0Êˆ8Ä¬(’P…Òó4$%ù×JuŠŒä„U´\0\r<°©< …4\\aœÔ€L;l ŒĞ©¼7ÀH ;ŸèS8Ä‰E$DıhZ×å’–ØÄ\",A\nƒ\n– \0°i7bu›Ö$B6í–Ãô¶•‡šûªæ¶)DóŞMˆÍ{„Ci†¿¦–­àD#ÿZK0h‚·{ƒ1*ÆpRa›¸&><\0q p”ÃAëPŠ[XØÂŸ`¤¤÷NĞ!	¶HÂîp†îu´›ƒ-dÁ…-xO²Âv\\\nğ`~À‚ø°FlÀOØ€b€‚€TĞÃàÀ7t\"X¶‚BQBşÑ¢t¨B6Lb8Ö¡•b>±‰bl›ø,a	ğs›u€È@*ü‚‡Yuæ@XLòâ¹Ğ[]+ïLÌµ\rfá#BëĞ6Do…e`ñ>\"XyLŞ$~øÀAî×TÂ\'>V£aÕ$¬Cä <¸šÂŸP\Zxíjÿ* V>†[;ÕÃ¾*È@g2L²eĞ‚0°#W\0&¸ıƒ,`ÑÆ¸ñ!ˆq+`ÜéÖ†™¶á&…¼3húLĞ½‰M¢o¶7 ‰ğ‹:“¡Îu¦³œı,gb8B	u¶·%È@9[âeØ·½7á	7c ˆ3†ı	ø:œ„¶&1`L†7œâö.†\'òİò€BÙøÁ²ñA\"1¸¹ \0È\0 ›ßÙeÂ­œ1@:Òƒİ J‡Îb#ÊT½;QHyÆT’’\\iOWRwÀ¤\r˜¸	LmÉ†¹Ù®smà£ÜÚØ¹ÙÑ­\rF0ÿbÜwû¸0tp÷ 	<\\ÚA€v˜r\0‰ÿF8 €q8@‰H\"Àñƒ`\"Û„Ğ|2°‹AbÏ÷µGŸoO€\"éŒ9‡Ò¡¾ú«[&%aˆe.ó–ƒ<&=éAxÂc4©©Mñ”ÎÚ‚3›ä\'İˆòá¢Óæ+_ùíøËô¡¯|¦W¿ùÙÏ>ô—oÊ¿@\0áp@\0ÏqÈü‚ø&®p…lw>ôF=üAoíbÈ?ôWˆ„wÂİ÷¡÷=!¶Á;\Z¢!@‚2Ø$÷@â Èã-lO}¯\0‚1®÷Êã÷:Ä3ÆÍNÒ®O:°\01àùƒû´;NO0úâÿD°¾!ü€ü`¡b@”A@ó²ÍıNÁó:ó¬MşğÏÚn‹&£ï\0èJb2(PÅC÷Ø$3\ZÂ\"B­®/ëÔ$4ÄÎSpBÌÂ\0ÈĞkƒ9ÌÜ¸ƒ /ğ˜n;:œ®\r¢\rÎÛ0ğ¯À/ÂaÀê\'@ÁÛ¡ç/ôv¡ó>€ôŒ`åŠŒ`wá\n´MöD¡K¼ä9£\0¨ğ=$â;ª.D¼ÄKÄNCHBX±&Òî«äP\Z¦•£,hq(˜)¤Âö\Z\"êúOéXoõÎX2ºcêÂÍ;B\0BéC\0ƒ VO§`püÿÆA\0Û~`n`Coäïó 1ß0 åX.ß,1¶AÏmO°„î@‚L€/3êqCŒï&ÜÄNVƒ`1«„)˜â,@’)ê9ÊB*n#\"4¢*¢ğì¡ğ2‚\")R (ò *\"-÷4\")’#/$ïğ@Ç\0@f™AAóv2àóÆñ­å®m]î½V¼øh…UL¤Uêã]Ş% §Æ52*r$9å)¢;fï\"®rB<2¤°½’c÷ÔC§\Zï\Z@~à%_À1¡ój²\'ï‰ãpòş<a8)ìĞÍ/A„TÿæMê£•òKTEU<d)u¦\r 1YƒWöÃFÖÂ,SÖB*”e!D¸2#dï+³p1.Ã+¢¸®êJ³=<³1ÎR\0 €fîá~ b@ójÒóÄ±şváşt2O!\0È/µáç‚²ë<„$^D>ğ„U3:AÃKNbNìÄ>d\"FÒT:0*6e@2(jÑ@äH¾R#®P\"0\"C#²<4¢\n?3]3%1 Ä!~€€ıØÏ5Ïı4ïv!A4ôNá\nAÚìèéQ)İ$3N¢:eE%V1)S?Vã1ƒægøƒff#(ÌGÿ\ZÆ(–%7ªr4Ã3q¯\0Ÿ®+±÷*!Ğc3\"ü^sàñfNrÓı®€®`@ÛÏ—4óÚï\nN!¸ÜÏİÈ®VÀä&¸t:-ÔT\nS$ğñTÀKcB\'ÄUƒò„X¢$<‹-¦,\n2(–åH:3Fğ#fô1¸25+p\"$ğ*5%C§ÜA\0Ğa!²d.b \"Ûš”06á¸Í9•Û¸í\n8‰Ó­îÒô0‰/)GB121åƒ&ó>r&g|¢;y\\nä2Á-pâe–cG¬\":#P9C÷Æ£+ao#L‘+-ãö‚4%ÿe“-]ò?!7¹íœ@ IK5@Kx“ä1É®î>ãTx‚C3Ô$dÅCæ„:\róg`ÂMÓW:jĞ°[”£?ä\'^&)†â)¨‚,q”\"ğ+çS=şSâ2b\0\Z@HOn€cmó6-5~ &¯ [©ôòDõ\\á@!B`¼Î/ùä±NóK—f^´Ó9F$dDTº³5d¢OpÄDW´Xp‘S˜Âaˆb&)å\0èó=éÓjMDäsEB>[ó$Q2\0Vr8–¼¡6ÿÓ€tdİÏT=Ö\\ç\n!f»ÄìZ1oY5E&1ÿÓhìeivæ]†æøŒÏf|%T|¤_Wô`h¦e^4rƒ$H6£4ëÜGc¯>Û£=ÊéîábÑ2Cö¡S9Ö¡á~ÀŞ<‘J¹-TÏun±ng / oÇ.İD´øvÂ$Ş”WµÄ>t¥øx‚OÎ°Jbi‰u3YôBRt‹µ<bO\'–s£0<8DsÇÃr¢%Çaºá>àÔ÷65œ¡ºÂé†‚©Ì@vÛÆµnaÜñ8ÛÕ1ß4yÅ5€v5Úbi¢wâf$ÓøV\'¼i{‚JˆÕa”˜b!‘å6–B0ƒ=¶ws=7GÿÕ$|	@tÉwü\0`\0\\ ªáaô0ÁH\0@&+³†\rà$®2àAûwŒsOÌ-KÔMÛN‰V2?EièÄF¢†Xú£S,\\¤D@€6vqËÓ`&QÔ!3Ö3>ª–Y-×GSú‚% \Z\0\n\'\nîØ@ˆ6abaVŒœîHÂ©›’mÊÀôòö·Ş±wUÕ-Ö´1g 5FÂ4Fô\'„6Sãffây·¸‚rB’E„[¦@¦Ö»°7¤û\0cÿ0 À`\rÀ ‹!…¼ X`Š€’¨îhÅDç ä\0³.LØÊà¶A”XÿSÕ‚y¯9’ÿõ¶y¿å(ÔQ\nv —O_4„!EŒZ8Ä9C„Vì!D—.ÈW]\0—Õ@\rpYÔ`\rÖ@\rä`,ºšÎàÄª€8á”YŠ€’@ø  ù%~·M½ÓD{5 æ“‰…E*uD)EY”%33sG^TÖA8¼\"H ER^™¦E¢ëÓğÀ`lÇ¡¼@Ÿ¡A¨u92@æÀjÁ\nH¯’êÊjáüÊüªËúàÅ°‰=Ğg:jv@úuW„Åh7zNQ\ZGÆS3Wº!ƒB¨âYæúG äCn:^A¯1ã¦ÿëµ_:éÏ0¶ñÂÀ\0¯ ¨u€š!áŠÀÚ§hƒ’ƒÒI¯XÇu|Ê\n´ZîÚ5í¨daÈ0F*‹ÓÂ%¶_\rÆ‚óô\'¦’B’â`d¥«*¤vO÷Aâº!…c%HÄ^âES>ğúDÚEVm\ZíÁ:.–QÁöp€—&huŠ\nA\Z¸– ’j³Á;H\Z¤au6{ux¡ø\0šñICZc‚oÄŠ=y‹W)z‚+rD*Júe(F•S\Zl(wQ…8†ãYDœƒ%ÚÅ^äƒ#ì¡¯“2¸ñåÁ\rS D7ñ\n[éx\nÜ@Ô`ŸÕ Á¨ÿ:NÀ|J¯:”«\Z¤!ÆÃ;¹œK2\0\'ÌÂ,œ–@®¸µ…‚S0¥µq&”é›EÜ)¥G¨lV9YæÁk~Û*üQ\\z,¦¼£‡?òz_zVVLÂ-påM_õ-à\"ñ¢›üÀ\Z6m\r¦\0ŸE<¨!\Zr!–!\rè ö 4›•Ê[Æ»9¨àÀ\nj\r¦„;Øƒda:pzFµ7zE;˜XÏâÈ%—!Å¸¥çÚ@¢\"H¶Â¥¹bc¨åA¼4f^BC@4UÆí4Ş&>„K´ó&èMV,B§F÷5aÓ\0p`êê×ÀÄ;4¡6aš\0¤!\Z¨\Z¹ÿ4ûÅ¯¬t‰R`š#•2©„i=Ğ\'t1Jø£jŒ6¤’EUÔEÏùzŸ\"88}®Õ!¶8å7,e®ï}n¢^`½LC$Ub½ç5ŞŠÑÌ1Öv†a \n§\r\Za\r$HÄ›\0t\0œ]\Z¨!Ú5{ƒ½¬`\Zz¢o®K[Ç­Y£Á½5¬Ø …âB’C!¡B6Ìù)dã)Ìá7l^jÅ\"àçä7¤ö)~+à1•ÂoEòñ:É<4*¢û¢;áeĞ¾\0q¼ \Z±ñÙ°F¡p\0Ê»–ëÅWÇ‰à@µ`a†Yê€0´Éœ™÷¬[Şµkƒ-ÿòÔ6l;3•¢Ó5=7Ö7 EA¦|+®\\7„k‚¶oGcÕG¤Vi•VÁEæu&êq=YxÃé\0Á!o	*ááq¹ä€n`\r€!Ê^¯V¼Vüu¤H$ZêàÖFaæ \r²ø¢Fz«„Sè;@`£et± ]fğœbª\"ÔÍ³(ˆ„èé}8ä]RÄ+rb¸ƒ[(gÂ»„ùäéÇ|ÌÛãs½ñşüä—\nØk…èÊŠA	6àáã7h²Ÿk\0¢–¦KslÕÑS§O£6a2MÄ‰Ùğe3€1#ÆlëÖáÓ¨±#Èzà[w‘ä“ˆ\\iåuõÿ`Î´¹rİMœ0ÕÁ¤·RO¡|î\\7#»¥ì0-ğ´€P£:…*u*;¡:Õ&Õ^WvTØ£jÀ½î¸\rÀÁ)0…‰R)Š—G|­™ÕJŠ\"`n\nwr3%Nœ9œªTI’D”œL¢êˆ©“*ÃE|+³ÅÌFr¤HŒõÖ¹<í²£iÕ:g‚¦Y3çÍÖ:i¶^9¯\'ÎÛ+ß˜§øĞy	Ô!0n7LàxUÀ”6²T.ıJV+ÕéÒµMw\nş:×©eËÚ;G`À\0·áĞÅe°ˆ¤082Uš4iL0ÀTJóH3pÄG(shÂ	m$1FzÈ!GBuˆrÙÿ\'<l–ÍEµtH%a´ZF4D¦PQI(™VÚL&rÔZ‹5İVÛl²Ôp	u¿Í¤SnEgÏuÑUµUtOI–sY1e”XQWéµõ\\lB\'Øõ~“´ÑF_¨PP˜sT¡	oÈQÉqB8„–‰ÁĞC®ÖRh%’\"jM)µšH#YšN$ªâNõ”6c@ÔbGó´FTQC	uœQª·:WYgİ”PI;ER·TªFB×”Xd-“å•–Zß´å\0:ŒÓ\0!–PÉ	a|ñEyE1É^ul„šÁIf‚­}r&”	Şâ!ÿªDôÑDæBçá *åi¡È\'D-yôîI@¾&[N:}d#mÊ­´ãô\0W”M˜©TSQUUt²¶\ZåuO‚×ÕTN)à•e¡·V[‡W\01l2ì—™ [‰.8DÁ¬.Dq‰&z‘‰e5g\"‡b`«ó\'áÁ«¢ÍGÚlÖÜº¦‰H¢Së(,\"½©qä>5¹ÄLy†blÙÄ‘½.Í¸Nœ&0L—TFê¦Ú•W^A©0WMAå]Âìfu•ÆT“[\r¼N\0xb	:‡Áx™£Ë8lƒ&o¼1‰aÔ±yˆAÅe:SA:\Z|ŒÂÃá“7ÿFÚ`·ºMÏÎÎÒƒF/‹ñŞëï£6]ºSK3=º¢m?ö$§>âØcmë\r¥RR%Y7©Iöıd‘nk$Uy¾[8@/t)Fã_D®Ëú*[’Ar|íæxŠ	éT¡3èû+\nUha3ŞyR “*2\r$µ#TjpÒÄ«6‰ÊÚ¾hR¶İ¸«%*ª”MÔQãtjGÉA\0’2(…©ğH‹ô¬b$º¥+Ş+\0z€%ñ\01@ã…ƒ•EÁ 0‘ƒ/øOú³„%È0¬\'æ€ Ã(Ì …À®€9u¼HªíAï:¨Yš»T“¨\Z¹dxµÉ\rÿR\\\Z3®ñD+ZÛLxœqJTb|\nW¤Â‡µJnÒaÊë”«ZÕ°<°Rø@æq,\00HŸ#\"\nÄèä\"0\n)a”–ØDJ¹‰TªÒ0Ã&ÊP2”§À˜6œ·T•%<ërÊÃÊ¸49v$Y›×xâ¯^¦O2	Ê@<’ğ™œ¢˜’æÆ¤»µj)Ü‰Î!-æÂ»ÑĞ{ç@[Ä‡ò	ÀT€ ON\ZÑˆ>Ø!\n±)¨2•Å(Æ.va„~zb  \'TYU<£\0·¼å\"·#%‹E,á1cˆ–5SEÅ¬‘L<¢ÌªÙÆ3˜êrÿyœ>­]-Üæ¬¤´$$1ìšLZ$àĞG\n ’øéˆANÎ“‹°†ñe\"»Ä.2ÀÔ]\\aiø§\'Š1ĞM¸²fP\nZC%‰u)«ŠÎJİ•‘4.“6şªj8¸¤¾G5œy—¦ÌÑ©à%„\ZIaôh5ª(,ˆ”Ô¦ç$¿Ù°\0Vê†NP\0tã¿ €J„ÌÁ‹ğA1\\@eÄàYÀ\n®p¦¦öŸi ŠáJT˜–¨Hƒ6¶á[-ò¥\nËJUØÅË•.­®¥9ÉmŠÙ\"ÙÔæ\"`I Ù’€ôƒ&ŒfÁPúÌÑô•Œ×#+Å~5ÿZ¥P{¦\Zç­ÎÁØô<.\0€¤Y#Õôí§>°C0WÈÂNÕ¥]¹‰QÀÒ1(À6ÎVVM	V(+”„µ3bĞxïš‘]CEÖC\'±\ZJM˜€µmjTÂ¸ë¶jV›õHUŞ[QÅ‘ë\rÀ8\0Ğ\rvfö‹(òg?k„AÂ‘ø@V=A`)”dP´\"¨ğ	*xK	<0Ã3¶±\0?½†ğC›¤*TÍjUbÔ<¸Fp*Ûh,¶±HØeä\"áÑÚT”1°]½E—ª¬ÇB¬ «ÂK¢¨.gÀÙ£ÈÆ\0\08ÿà¿‚§qEãÅğì 20ƒ‹J@Ã\':W$Ô¡œhĞôpk9ˆhHp–›š…Ìf«yX…*ŠÆi¶±¼ ›:§ÎİèÆ7\Z`;cg©Õx âşÙ(xä£i5a‹Mo†bÕåV´#N¨èvÇ‹µáyÖ²\0€.™^€à«Ù_` •‹ ò&¦ñ€pbác¨Ö~[Üáœ°›4Ñ&=Èª¨3ÎÑÈ‹|±è5¶ªvÃooP&k„ ë¨²\0Ç^ûáp@\"!ˆœë\\ŒàyP´\r¥xè0I1¸Í„1L¦Ô)Õ ¹£KcCš\0ÿhGÕqª,e\Z\0í}Áe;I„_dÖc\'Ã.œa‚;ŒáÖ\r²d ~‡ÊH¨ºşàŒn¤ÇJVjG;ø>i2£ÒÆöuœÄluè€Û\0sµc1óC8`òŒ`ÆAˆâò˜ÈÆ\"\"‘•¬-Å(ÑMÈÇğ I…ëF÷Ï§7¥Ã·\nU`MKZ¨ò÷Iã;K\\ïı\r€\nbPğ„„ğ‰E†,¸`’ÑC)ĞP:•·ÀÃ(pŠQğ\rÜGƒ*ò‰ÆÇ|\0÷°RùqJ\0{œ%ıê/v±­^æ\Zª?¼ i.˜c^íÄ¢‡à8$ï\0\n0p™µF—EH–‰€ÿ6¶#§MƒF1ÅKÓ0Ù`qæ¥c†+|×X08ä\0?Æu  ïÔ?ıƒeT UxÀ>Ã© ¸ }Âğª€Â\0iĞ\nªğØ‡0\r×\r‘	äXjÁw#içÑ~ÁVlR(TñGŞÓx‘ğx±à…^ø\r‡†‡@†á	„à™	±6\n»°Îà†\0‡ú53À\0\'†GÕ%(21ˆFS³BCÛdSÅ8…‚9>p‘½×uW¦3vaŸK£@p£ }«€¸Ğif€ªĞ\nO ¤\nB¨\n<€}¬Ğ\r±0\0Öö\r±À…ğ\rêáÿw\"GuégípvåAx¦…\0rÀ…^Ø\rú÷…­X\rğ\rc†Œ\0\nÙğ	†p5ğ…^\rÛ\rJ¸\n€byf¤MF’0ßñ”ï†7R1oeg;vSêÇw÷€ˆnA>‹ÈˆàS:£‚ûã	¬À\n§¬€OÙ‡‰¨PŠZĞ\nA(ŠZ\0‘Ø\ni ]u\nø—„­ø\rÕ¦‘¯Èw7ôq¶‡‚·X¿xcv!Ç‘Iø…ĞĞ’­˜5‹Øö…?5P°:ÉÛ(‹‘À€WÅ-Æ=MÈ].å*¸Hñ\0ŒèÕ Ii~G‹P8úÈˆp£ÀjµÕÿ§ĞÓ`Ó0\rÇ0\r»\0‘9‘§À\nY q9b)Ù\n§àJ‹ÕFİÀÊÀä°\0³H‹7•8UuS9l7léuCÕ6\0\'“0	Úh™.™± “Øø…œé’ŸÙĞ„á§\rj£6ÈÁ.Pã‡1¬‡€uæaoö†~ö8\0ˆˆ%î¡•\0p±\r\0K¦½°Ê0(p\nfy¬p°…\0‡.	Î0rX† …py\\H?‰sÓs‘ †Ú Œ°\rİ@‘0\0 8’w¼xC¹B\0emÎÈ™È(=¹Ÿ™	y-é“ÛX™ÜØ\ra¦\0Ù€6æpÿ]DqHÇK‹¦@!o°S»Tôz„x}×\r÷€oê±îP8>Æˆíå\0áÅP‘X”ÇPÖ©ĞĞ ŒÕV™™sŒ\0”KÈ3Ğ£Œ\0¤Aª„Şxú¶\r‰ ÏÀ…ùŠ”v~ ‚Kªüİ€™û) YÚ“±@£^ø\0Øš4Ê:	#¥7tXt $*‡öR3µ*°×nM	›V…Œe˜¶	¢máXÂoŒ(\0×Ö\rf8\rÊ`œŒ`3à4\n“?É‘\0fè¹¤\'É…‘€Œğq \nÀ\0:§yÇÎà\0 \r7\r§&Á9§‚À…o”æ„Vòÿ\0 Ú\0İÀ\0 •©“—	d*‹\\ú\0­ø\0_Z¬Zª“ „afhj6Ôê‚nå^rÕ=/µµÇX“¦ä·§|ªu\\·Z¥6*‹ÉÈ‘K¨„£i£Cº\0¡\Z~\nĞxŒ\r˜@Jö?€	\n??à—øeW £\rP\0? 1\0\n­*İ€ˆUÙ¡	?°	2Ğ	s \n,Ğ’ÁÚ¿ÊŞ(š]ªŒ;ù™jKÍZzÎú;”š°W+ªÂ[+ô¦¬2Hõw|w›î ƒ#>û°\0Uº\r·å—\nàªZ êjŒŒ0˜JˆŒÖV\0Ğ0\0 3 ¯ÙÀÿƒ°yKµ„€	ùj¶ÌğdûÅpÎ@s\0÷åy?ğ_†^÷à³ º\rPI\nVPqPG`Z0 V\Z	û™¸‰;²>Ù’‰[¥KøsÁ(bi£56a³”a¤šd4SÔcxUR›šu0\0ú´\n2°ª‰ğ®Ê©òÚsğ®P;˜E{Àsõú\nğ®Ù‚pÏ¯7€y `ZË\Z‰PğµF° Ğ\rü§\0.°	ƒ±P\0°«\rŒp4Í;\0@GĞ	´`M0vĞ	wĞ\nİ¨¬!š${¿Ÿ©ŸÉ…ú \Z<1zù2ÌñNgÿ³á4Vr*u}‡›9ä¾°\r\'Ißp4Ï áÛ\rç \r9·\rK\Z\'‰ğø\0»9§¯uk¶uëªÛ¤êY‹Ïx€Ö€	`jWĞ¯˜÷‰°F0\rëp\rƒÀyW\0	ğW€šĞÍ´\0`SĞ	¡p	¡ÀÎà…Cš¸y™¥Œ+µ˜É…ÇP*<º0ZS=q×Áİ”êvÓQvZCè‚êñ\r€%íáĞ\0é	”=Ê…J›a¤@ªs«\n\n1P9§\03 ÀU\nydä°ë~â«\r\ZL¯ˆĞµxTÌpÌ€	%ÜO…Âß°ÿ\0Ş0Ç?€â	à\rĞKc@QÌvV\nw 	sĞC<²‹Ë‹º¥Ò¹„Gë: òRÇ„LoÇ1HŞ<+}è”LiS}wN9$Á‡Àsx–âµ0Ã@ÚxŞÁsôy\r0ÈÏ¨®hÁäĞx[¸\rÃõYmFÌ	€ŞÀ>ğÍt+/0  †	I(\0ğÇpíPz/°	J \0Ò\0¼Ğ	t\0AÀµÚèJ(ÆĞÌ¥‘ûkÕá!\Z2/l0vÖdM)=/{››³ã„Vyºn‹í\0á\0ÈJ	Ú K(ğ\rRıÿŒƒŒ•Q=\0ß‰„GëŸ,¾‘@:JpµøºˆPjÅ°	à@\0â\0¢f‰ğíªˆ`O/€	‘Û\rî°\rˆpİ@æp\r»0\ntĞ	É ÌÆ|—@eÀ…!KÓ‹\Z‹ÛÁx¡²uL8’¹5RÖñHCD}X“³Šµ‹êÑ§èô\rĞğÇNÆ–ÉÓpíå+;õÇ\ràŒ¯Ğ¶tKJšè)¾ÙˆğˆÀâp°	 ÅÊÛ\0ŞŞà\r‹İ?ì\rˆp˜à	@ÓğÇ€„0°çp\rIµ\rÙp\rŞàx¼\0Í\0V`g 	¤ÀÊ@ÿšİy¹¨‹©µ@¨A,Ç5’b0Åä.áWatc­ízÌ¸Çç„•]¨ ŒGûª,Êğ\r;ä+a­+©wë\n©ìy‚	í\rù*ø;îÀÖİ›ğÊ°Ä8œ\0D.Ò/\r\r®\n½Q\r€Mí€xMwà¾ı=Ìµ@I€İ	Æ3=¤h¡\'b.\Zá\"Î4ÒÀõ\\wÎE®SÇ?çPSqKÒQ+Íµ1TÜã3á4÷\r2)‹Ÿ\n”5 ©Œ€É±ğ\n¯Psİ°\rÈÉ:Ç_˜ğÚÀ\0L·5˜ÎªĞ-újáğ\rò@ÿ¿J˜`0İƒ`	Õ½ÿZ¶ Àâ :ïM·O®dÊÓ€;şŸ ¡`vv\ngP\n£Ğ¶Ï,ÆİØàl.\ZB;0¡Í>½ß$ƒFX±óP<´0@súöŒÏ˜\rf0\r·š£J;E:@u‰@t®(ğç¶t»\0\r»\r×`y.À‰ĞË˜äàŞ °Äpmßâ`\rÑMT°©7p—÷Ö‹Øõ­*0¼\0@©úÃÅ@Òv`6\n[ ‰W0\\\nÆ¶‹gÁ/n6 Å4d#<QL?Â4\rÔWìò4ŸKaâ%ˆeÁ³9´C,ÿÁ¾rg‘P‹ŞáòQ­oWËÓ¨¯àÔ}? ¼9×Ã{ 1€g¶~±0ÊáåÅÀ›P]{\rˆ€‹°Ê\0çÃŒÀÑ €sk‹	Òİy‚]ÂöM«p¦Ò¾J°˜pÕ£¹Ûp>7ñ!.0‚QA²>QG“zÖa;N7rÎqºHêå§;t¢…3>¾R »\0®šÈ\Z	äó\nĞ€6ù\n\r`?PŞ3@®˜¾\rÅğÙà\rÌÄIœ½DUƒÀ	ğK~Ÿ…	=÷Ş@×ö:úäÊ0˜Ê×D“ÌĞı‰\0½\0±MZj	Q’ÿÔŒH¶°§@Šë(\ZÀ·.[½u\rt´HñÀÇŠë8Xw@¥J‹NªôxQæL™ì(Ú¼È®ÀMv6ì,”\0E¸ àÛ¡á’&ıÖ­[¸Ùb(pÖMP6AŒ	®À¶/²b«]¢mñ1»zcĞ\rFm\"Ä,Q\"f?ø&êj€‘bÌN`í…‘AW”%\"4Ô¶k ²9³ªfƒvõí–à\n´˜½È±ê­<tôğÙƒ£\n´i»èqd6”³a40òÀoEç˜r%KåŞ˜W1§Åu!g²³ØÓ€Mœ9u_`h;w`hğ-}8ÿMè+Ï´9‹¤`› Î„u§0éFBfHä†ÓæL²Áä\nLœÀ€@Šy!‘ñ&›l\'›HpÁB¶9@@ˆ0ƒ]ç†n\0E™Áä™ø!|^Àñ‡¾Ù¦¿^`&\Zx ƒ;B9#•,‘È&Üd\Z	Ÿà>ÚHËİ>òM8~ci¹•”kNéÖ”)»íºã.Î›¶+à;ğh§¨ò@ç¿oÚ‹=øº €g$’mbäq!Ô\0øæ\0³²¹Á{¼yá*Øj+éæ\Z˜ñPL¹æ4T¯YÄ¶|a“MÁÿq~àuLwÄ€LÓûæ¢(€™gÎ1Ñ4´Â<ÎÀ‚mj£‰:0ƒ«ËnAÒ¥ZB©\\3«+7%5iİíæ|—¢:åeÇ6;Å+j\0°pàCşG½Õ‹e\0A„œnnP&m\'§Ş«ŠQmÂq¹â•Jmf#«˜DtEÄqáo®ÙËPìJã#¤ØäŠÄ\'L0QF›rìOàë#N‰¤\0o ½¦Ö–HB#©­€ÛÜ=.825¢NK2Çä:8”Nj%“ÒI[’ºe;v{êI›êßöäóß°’\nøÏXÈ!ò6A ! ÿ“od¦CĞIj€{°Š‘ûR0L$‰Éæ‡lvì›sÌyáÔkA$F¹\"‘bŠÁ•0u°m~èê›aäÈ\"éf›sLsf€Ám8\'<Üº›)$-ÇüH\\”xÃÈ:ãÊ$×Ü²PçÛlir;^xá†»îÚéFßòø‡½q@G©i_f+8eÈ3ñg„ƒqï)\0W´(d\ZÚ5|\0‰x¬s~!„!e€k	âÅø>MÔ\nICûÏ´°)£Ğ†8\nÀˆ®0b³=Æ”ŸX;m\"E´¤-êŒ„$1¢ÙÊÿ´D•Ì|N¬NLÀë±Š4Q€NØq­ œc(FÙW8¦`ˆ…\0¶‰o|ŒÚˆÄ\0b€	A€\nD,Ö`¨@$ ‚p€;ğ¡+¿\0fQF$vÑŠc<D>Ø„2°	d#i †à‰­Ü c4T@\r ˆ¨\0Q·ÙF*íÑ¼°ò!à1ÀDØeÄ5}©º±q(Â‘ßğ]ábâr22HvıæŠX»·h\0\n¥}ßĞ—ĞqÄÏšñ‰Åz\'7Æ1õ9˜3ƒgD\"’\0¸@A<ƒ±€‚à²;À,Ğ†ˆÀÕ16à\"Øj¬`À.ø°‰ÿE I ˆDNõƒDúoíK€;µ‘J/.€yØ¡úÀ3Üè’zÅÌ\r˜42=êuDlõ0oÌ´’¤m´<æL®„¹Ù¦n_Ô—yÄø§bÅ¢‘PT9	Ğ•ıå‡›‚P†\0n‡£X|Ãd4¥ˆÒa€bDÂ¸F ÄÁÕ’?Ú\0Q\"@±ºEPÁZ˜ÁFa†MpŠ‚8;2÷¨HĞpMÕğ†báÇ•êë¡¯E“d^¦[JIô{¦•.\'%ÊÙˆE’Ó®U\'±DÄšLvò{à`Kı”¢G¤Î@?PÀ¡fsDáƒ\nˆÁè‹4 )	\"~ ´gØÿ#¦É>´¡ºÙ„È\nÒ ì`&‘ˆAàÊsÈíÊ\r?F\0‹e¯è¬xÄ«CZ)³Ù¸\"uĞ[–‚D:åBi=c&u0§l)aïc¿&&qeOmXÛ\";›§»ã:Ğá”ª\0/•cÑÏ\rmø\n×•«7 \n\nX¿èOŒÇá\rÃm\Z\r(ÀoÅ£„Å A¸™\rk#/ÉÛ†=°€HÅ‹­Ôh½ +[*ÓõÊT=±™É‰+©oøkßŒLÖ²ãRÛkâ¦7İOz2°ßsHáÕ¢ÁSÀf8›¡nY[Ğ„ÎóÙMDÿÛ°äb€Ë¼ÇB/\"a„èï†°Gnq	â¡ùjÇ^›wòŞ)ÈZœ×N®#÷š¯kg;›¸®œ.ï¡Dõ5Û:S²%ÇÕõ€µ¶Œseñ±Í;ßïgÉ\0¤˜g_ğy€6-*¾*€øS€çu||Sİhœ…x€g\Z*İºal¨YM“T¶akâæî‘ĞvØÃİ~%o¥uˆÌ;öhf9’ßY—dßøEW¬÷}’\'«£àó85ø¾p• ”Õ¿Œ\"Û²LE‰¿+;èÓ!Nß\0€ã¸ÇÒƒe€Ï ÚŸCbË•¬|… şÿŠÂ2q$ İ¸G$îÁ¼Ä¼à{aˆ=&j€k`wu‡;ŠiVVš•B®S½´ˆè±[ÉH­Û‹^˜¤mß+²Â¨w@q%ñ\ruº’Ù¬¯íˆ5ÉIxÀëÙŸ‡qOJºÑ\0½wãc¨„iGnãƒœõi bÁ÷ø€…\0	HDpóäUªDØİq1D<ƒx.Ê=vŞæ½»ÒBşÉ³8/íp©8ãÚwqkÅµ±Äá\rO»º £pğõ|ëXø“ÍF6“pKön×òÛá•Cñ|yãğøœßÃø¢´å…ÚœMÊ1ºòì±å TÀ¢ôÇß Aøÿ°\n‚»÷®qGw#Òí&½¼!{ñßìø?º¡:óá´üŠ¸µ±mÉµ²q\"„kyxu°†yH	„38—h5Ë2ÀÈ¯5‰)¸«›U²‡{Èóø¸øI\nII\nîjˆ57bPaÃ«#Û˜Ï¹ÿˆqŠºÓ*ºw\"\0 Šq\nÀŒ½‚Óc¾ı£›ÿÃ7íÀ·\"[ËB¯!:\"~›²•À²&RTë=3¾y@\03äôª7ä«326¹ˆ+d¦*AÛ—ó¸ÿp€ÿmšË<ˆÀnbÚc¥œœz€`zÿpL˜H€†HPØ\0P\0¯;Q3¨«·ÿS\0w«|³C¬±:*â7,ƒ=üZ2tÁ=—@¸‚K¸]Tz08”‚‰í‰âóÂ$ŸÔ¬ è,º3°=Lè˜€¤7Û±±X#b”ÙX€nøÃŸŠ†P”½CBh€†Xˆ…$œà\0BÅ\rĞ€i˜ğŠB)DEb¦Ì‚—â©à¢¸èº”à:äĞ\ZáX3Œ2<€5|,‡³¬o¡HÌj›Š:ºaÆ\'$ŠÀ›ó€¾œô?\"‰„\0ĞœøFî¹Í„p„( Mƒ]0„\Z˜Ä€†IìIMÿthp† Ê¡à€E,Ew+E*T\0Àğ˜¹¹ë€“«[›Ì2‰<‰)z	“h\"T£L€S›„£F|8€2& C¸‹%Š¨ÁelŸœ€ÿ8IIÌW`ñû)àa¼p\"ø‚Gh†\\à†jàj¨i8Àƒ\r¨\ZØLÎäÌx€èIìh@´±`&ÒÇ:±,ê¡Â7YºÚ„E¬kË3¹%$a\\	¶4H3)K•@€‡T@€²D\0\'BN˜5”(NN{=Ú»‰+œ ¨›Wš»¢ğÈ÷èKLè€ñ$Ïò€@„ÿˆ/h5Pn˜LnÀø^\08p7€+¨	xğ—C ÏÜL¡LÍIŠq{%<´“şƒˆU\\Ef\"2\"›Cöºô7%2èP„²H\0•ˆ26L8!R¯+C/å£K¼ÌNK;‡3kñ3?ÏùòÏñt€lÀ\0`OERÈ„Ï\"¥†h\07°Nà„9¸[ÀQX%À3Ø€ &	¨hCXÂU\\Ğ‡x¥İÇº©ÁÚºMS¬1A²ß`/¬#H7\\	•¾&ò½3ô½2t	ÊB¬â£Nº¬\Z«Ñ¨Å¨÷»‡åKiú8ÁL\0F00ğ‚Gÿ\05@ÒfPƒfhEÈj †f\0†$…[Ğƒ1=È„R4¨ƒ$¸ƒ;ĞQ(`…cs7fdPò\"S©ì¡ë‰¨\rØÔ,vé419›*«ÈZ)´¹S<m,û\Zµã«ò;í@EJk¥Uò.£ø_»;3#\0è†ø0¸JÈTEĞÔ5ÈÔME5P`XJ`Nƒ:ø% ƒ20ƒ2Ğ\Z ,@ƒ}U3`…Al‹¡)|:!V|J„ŠÀ5ŞœHî!5•x°»ÓSŸú‚¢±ÛS«›ãÛÂ9ıc>|\nDó¬½ìNü¸ˆ)8G¨‚:ÿN˜‚)€u•×50Ú5`6Xƒ7ˆ‚L ‚2°OHƒ¸B¸‚0\ZĞ‚]ØVˆÿñ®¹#Áı#¯¨Š©ƒÊÿK-Š‡ô–•N ÚX€8]‰ù¾³ÄÓ]|¢…ëEè@¸=›ÊšÎä«N;aMVÒVJ›´fÌ@1C€¸\n˜„{mƒKHZM\0MxƒÏ}ƒJƒJ@‚/1\nØO8…¸P…B`ÂˆP8DFØ±°•4Æ\r;”XşÀ©ËÈ©sE\0‹ê^J	\0Ë]	³Q†{H½[ÌÀ­ë\Z°a¹ÁV¢¨VÂUø%€½,–@ÿ	TÇX`L(*@9¨„¦ıÓH 2…2XİbHƒS¸\0…%4üÀt$G©è.á±(ñz^\rT ˆ%.ª—  Ğ¢—³Ö™ ©ñá´ƒ¬Ès9t©¯U8åŸ²,Ë=Î“ˆ5Ùc/ÙK¾ëdÊ¹û.ïB4‹²¨\0¾€¯h¨¾k€I<„Új€MdÚEˆ+è\0Q,„XÂÙ@B ú©À¹{°‡¹÷cÊwƒÖ¬KXÒ4 ¸7îØ¬.ƒJèñÂ9¬J’˜¬“È:Pû:ı:;…°[ÎãL\0œz\0Æáà3áH¯©¢‰XF/ZX©PÇoğ)qÿ,_,^¼øŠ1ê¥¦\ZPP°WåPn€HnGG©`efXø[X–ÔØÖØd¦‰Ö»Œ%ú‰[öG™\"-ÁM±ùaº%V	¸ÎéMÎHÎä<NğIËâ¬²übË×3ÚÓ1ö®Cí†`\nK6Öbœ«­>„?t\0Kî—~Ğ~y…v¦Æ˜sœhÑó,ÑÓçób/Ş+wã®ğbM1¦›Ô@V+@b½Ââu‹ˆ	ˆîˆYsáå5‰WóÍ\'s\"‚ƒ²]<ËãäSF@F\0y˜µ+óÀ9<hftÜö©­tg2âd¦x€õÅ&ù\nKæ»ÿm€§òiu‹””Š=İõ®f4=ñbUÒÖÖÌ4³­ÎBü¿wœP¬¶\"L,ŠÛRËç\\‘M8²î[ğYCô!2UŒ7<#\0-Şii\0	Ğš^_xz\03ªtTG-şkÚÚIÆ«ärŠî:l0Ş+5[²mæ‘eìÕ‰˜Í*ÁíÈ\"z¡ˆ,´Ğ86\\YŒµd.¾Éb¬z084±ÓúBÎ²¤‡HK‘^N`œæ[ª,>Ûâ¸± DGNN®¼^GM¼âá6§¼î†t<nqLeVÆ¹òuˆû±TR³UbÉ‡xnæs¾¢îŒ2Ó\Zd&ÜÀKE¶KØ¬ğÿ®K‹¢ ’Ë/DH•j¸È\Z÷^ù^	g^mä4ÎhFÎı.ÎÇBZÔf,_î¿æi-FÂŸâ±\'GJşfõøæ—\n#êÆî,òº¨mõ+ ²¥Ôp§ê‰è‰\0|Ğªq°ê$Ÿ8—¸ü¥¯LYV»½İcC(:µFÎ±;Î_\\Cş–xC6™=ğ¢¶¦ø¡k‰ŠÃLå+&‡T^GlÂ«%gå¢Nl<SPnÅ¨Ç¯ß\'„Æİr/S\r¾´¨£Ğ7©Øgñ61:^QNCf®éP7á3TGÎhµîBë`I0pKçCX_hx!¾ş©M<nÚBtUÿ^<©0ê…M%<ãğ§Ûn–|º§ëÕ-ó²	äÇq´ËM«¢\\úÀ´á–İ0©q‰ÖhMë„Ãó<ßssøq ‡´\"ö@¢‡uÖé\"Ö;ŸšÄOeF×âe—\n-vû;êf_ên:2Oê‡%[ Ã¨Œ· »Ãgélº´@íø¡Íök}»«û:”¶,ÇªÓ³Ùñ±[Ã†4¸>VÃıîêÇ\"	âĞ‰mø,biª‡‚‰ä*gî¡nî£ÖÖ(ÜHÊtÖ2BÜ)ŒêK3÷ˆå¿–¥„“\"	›pwô#c5úr2´Şñ>Şo‘­÷åìqæE¯.<\"\\+_onv©ÿaîCõ,İ%ÈVjæ“Ânï\"WŸm×p!£7à=[LÃ´ ·¶¥xÿöP‡Ûë@^·ƒ=²IµŠp@ç uŞ¾—ïñ=\'kë­/.©‡.d¯İ\"“ƒ¿÷+ß¡=vÉçÖá×¨j§ÂNï\"!Ãö¾ÿt3vÑòÊ\"ŸÀåÖ#2Û´ô’=®;“×c¬°LaTC5héŸæßq‘Í¥³K[S&ˆŸö‡Øvò*½ò*s¾*|.*ü}¤`\r¶·Q¯“Ûø¡‡Åcê“çàËT¸±_ùS«÷ãäïÏWùo1\"«ƒ“§7s§—J©×ÈÚŸØï€Ğ2múØ—Øx8€ë^ëoÛqÿŸ—.cãŒ÷ìq)Ÿ_B	´À‡óú”˜À5—²>NyXyxØóã¯w€X‡ïÀ@f3pà Aƒ\n°‹¨ \0EvØ x±@ÆŒìX´¨‘\"H\"G¢L©r%Ë–*LiÀŞÆ%bl¨ó!D>ë­ktĞÙˆ8°n]6¥JÖKz@İÔT§^E€@İÖ®ZåiíJUëzÍ]hÖ¬Nm1^ôhpcÏ!QNt©w/ß“+íiÑ#Í‘çÌÙs¦O|A\r:^ÚĞ¬ÒÈe¥.]WVóyIÕQµ*UªU®\\Ã\"\0–h²‰\ZÈæ˜¡ÁÇ\r»Å¨Û.Ü‹7şÿò®o¾)›d¼æJ‹Úà¯y¢îÅ·­×¦}ğñĞ£K7ÏÓ,^|gª ÕÑ›ú3èÒ[OÃCmz«9y	šÅg\0íPí>ßê”XGÓE]¼ETs%w`ÌÇ oˆ-HQ<‚\r`¿ÅUN’	õá9™BE!åTœ•§y[M£:	Öi`Íw\ZæÀƒ@CEÖ[\"êç“€Ô]T’]J\Zˆ‘‚$i”‘„ÊFÓ]‚Uô[L%¡„ep	^) bÙ‘YæchmGŞxy¶fyT©W#z2nEX^©öUX`Ô~¶8æˆÔÆ%N6Òƒö$Eê©FßölX@^*IÉàƒ-Z€İ4¨¨%\Z ßBåTRD‘7Úg®¾:Õ¨y5Y	õÇÔ@L	äŸ¨>*Hs]ôœ]M”—E—\Z&i…™$e6-«h—Ã±³¡L\0úhˆµ¹ßB¬u\"¬«²˜Ô<4Ç^ŒïÙ–jyn%9æ\0e\"‰ÛñkF€ş[×[N\"I¡n¾1Ve˜F©E\n?œmaÙ6L±Ä„‰d¥ÂÚ\"¶áÆë(Æ]®ı–:Qù²ºÎ;ë„·ê;ğ²fpª£‹6›W3zêÀc¯<òæ‰#Wò\0;','ÿØÿà\0JFIF\0\0\0\0\0\0ÿş\0>CREATOR: gd-jpeg v1.0 (using IJG JPEG v62), default quality\nÿÛ\0C\0		\n\r\Z\Z $.\' \",#(7),01444\'9=82<.342ÿÛ\0C			\r\r2!!22222222222222222222222222222222222222222222222222ÿÀ\0\0d\0U\"\0ÿÄ\0\0\0\0\0\0\0\0\0\0\0	\nÿÄ\0µ\0\0\0}\0!1AQa\"q2‘¡#B±ÁRÑğ$3br‚	\n\Z%&\'()*456789:CDEFGHIJSTUVWXYZcdefghijstuvwxyzƒ„…†‡ˆ‰Š’“”•–—˜™š¢£¤¥¦§¨©ª²³´µ¶·¸¹ºÂÃÄÅÆÇÈÉÊÒÓÔÕÖ×ØÙÚáâãäåæçèéêñòóôõö÷øùúÿÄ\0\0\0\0\0\0\0\0	\nÿÄ\0µ\0\0w\0!1AQaq\"2B‘¡±Á	#3RğbrÑ\n$4á%ñ\Z&\'()*56789:CDEFGHIJSTUVWXYZcdefghijstuvwxyz‚ƒ„…†‡ˆ‰Š’“”•–—˜™š¢£¤¥¦§¨©ª²³´µ¶·¸¹ºÂÃÄÅÆÇÈÉÊÒÓÔÕÖ×ØÙÚâãäåæçèéêòóôõö÷øùúÿÚ\0\0\0?\0È·ñí´)ôŞ¡Gó§¿ìÚ=­×#œ(ÿ\0\Zàv\0=èÀÏô¬½ŒM}´ì|BÓmŸÌ{kÂ™ù°ªN?ïªì!ø—a:lºùC¼ñìŞõáWà}™¾µÓÚİÆß( ¶xÛ¹ü:Öu(ÄÒ¥Ôí5Ÿ[£‚Ö’ƒ&víÇcÎzzÖgü,D[E¬Å±ÄZæµI–xã\'¬sY`{Ñ\n1kQN´Ó=?Š†(Ò?ì¡\"¯¬¸ÏéYZ§\"Õ\'Y”Ñ2ÿ\0vçƒÈ<‚§¸®G”¡GqV°ôûõŠÍ}^şÆÖ-õ{Vf¶vhËŒR¤Œ	ô®Îãã¯p¤.›jƒ3Wœ€*Q€@ÆOµS£	n‰U§Üí“â–¹·h³°ÀõWÿ\0â¨®:5ÊôôRöûÛÏ¹j1Ôü8<šUµ©‘FøfWµXx#OÏLÖ-c\rÔ1,èçqÛ\"®OVİå¦kÆ¯÷\'¥{Wƒ5éî¼omnŠÒÇh±Æ¬q—E\0öäW>\"üº{_R-_ái’ÒA\nHç ŸNkÉö‘‘Ïô~§®Ä¶ª–»]J›s‚;~•àZäBbñ}ØÁş/›úÖxY¶Úeâ ¬™˜W+G4à¼f—Šrx#ó®ÓP½¹§õ ´än˜ô…CŒç?•× ±é×½I)PMôÂyüjG8Ü¬=«®ğ¥öÎî1Ü0P{ÌµÇM\'&´|	uwÚŒ©;‘dü$ŒãÕUx›RmHìÓ]šáUc‰±îşïeâ¹¿‡[Ä™ò©ßÔúÅnYøªÏNŠQÂ…¥ÜƒæÓß5…â\\êÿ\0d`Èíõm£võü«ÔöĞè©g\rÌù‘Ÿ­L¤1°µCy\\Ç±ìyÿ\0ëS…ÀŒuê:WeÎ;\ZÁ’qŠl	••s4“¡ä¨ô«¦ö)Ùmír ÛvwœzcSÎR‹4NÑü¾‚ŠUbTìsõ¢­g‚˜ÜKŒS$©Ë˜™\'Ø¾¸?XQ¯î”b	%Aç?•SÚ;Õ‘–@à«)Át5¥³ì÷0\\‰òr_r9ãÛõ©e!#E°X¤ËppÏO¦=ëVÖÙŞHÖXâHÉÙ…\0ä÷ÉçµEn‘Å,E™˜F1¿Ü‚9ëÜgÓ¥Y‚3#yqÈàÉ)—–^ƒÇğñÇéPËH¯-¿’\Z·ã*ã\0dô€pNFNãÉÅwÖ~Ğn>^ŞØØ¨½HY%–_ÃªîàŸ»œÿ\0ëà®d\\H\0nT¹Ã×¯¯­oi\Zıµ‡†µ›$-ÈAhå¸ÉÏNç¯¡¬ª)8èkM¥-N+Ê2aGÒ¯ÚÀ±ÓwLÑ\n*:Ôªr	5ÑØçr%ÉÏó¢›ùQZ\"l1€ãŠŠE4ö=ğ*	 ±’¬ŞeÔñÈôÍiùiUœ…ç\0×·CêsÎ\nË»C$¡cÎN\0^¤×¡xGá¶³ªGÖ±s›dGBs9şéáx\'¯<t¬§(ÇVkÛ²9[_ŸÍ!ç»ï=zwü½«NMQíË¡÷JA#æ,Çòíı}k­ñW…<j‹o£ê³Ç¨B€yÒL\Z2AåŸŒø	¸5çs#Å4‘4ĞOƒş²ícõ`	¨Œ”Ùn.\nä`³s€ƒŸ•zœÔñÇĞóøS`gš•9Áük{2P¼Šzzõõ¦ªóœTˆ¸éÖ˜€HÆqéE=I\\€( ,SAe²1Yl’É”ÀQëZsÄ%ctª¢ÕT{\Z—r“EUó¢¹Y ™£•Nåu$>ÄVšëZäˆVãVº˜ŒI)n>¦ ªãŠ1K•u;è5·¹,îX¤š‘Wå=)1NHüøª%±Ã9ö©TqŠAşªzŒöı(0ê9©©¨Àäw©”\0qL`¸¢ì(¤.½}3Q¿Ş¢Šb´r*68Çh¢€BŒnP§Œàu¢Š\0™\0Å<}ãìh¢€$Î\Z¬ ËÂŠ)°ES°ÏÿÙ','GIF','chartesceau.GIF'),(4,42,0,'contre-sceau','image/gif','','GIF89aä\0÷ÿ\0ÿÿÿ)))111999BBBJJJRRRZZZccckkksssŒŒŒ”””¥¥¥­­­cZZJBBB99)!!J99!1!!!R1)”RBcJBR91J1)B)!kZRJ91B1){ZJZ9)R1!!kJ9scZRB9¥{cÆŒk1!­”„ïÆ­Œscï½œsZJµŒskRBÆ”sŒcJR9)J1!Æ­œŞ½¥œ„s½œ„„kZ÷Æ¥{cRœ{cŞ­ŒcJ9ZB1{ZBÎ”kB)÷Ö½ïÎµçÆ­Æ¥Œµ”{ç½œ­Œs¥„kÆœ{”sZŒkR„cJ¥{ZsR9kJ1ÿÖµ÷Î­Şµ”Ö­ŒÿÎ¥Î¥„÷Æœï½”½”sçµŒŞ­„µŒk­„cÎœsÆ”kÎ½­cZR¥”„Ö½¥Œ{k„scµœ„cRBsZBJ9)B1!!ÿïŞ½­œRJBœŒ{çÎµŞÆ­ÎµœÆ­”½¥ŒÿŞ½{kZ÷ÖµscR­”{çÆ¥Ş½œÎ­ŒÿÖ­1)!÷Î¥Æ¥„ïÆœ½œ{ŒsZç½”ZJ9µ”sŞµŒ„kR­ŒkÖ­„{cJ¥„cRB1Î¥{µŒckR9ŒkJœsJJB9)!ÿŞµïÎ¥ÿÖ¥Î­„ïÆ”ÿçÆ÷Ş½çÎ­Œ{cœ„c„kJ„sZcR9ÿïÖ÷çÎ÷Şµ”„kÆ­„Æ½­¥œŒcZJÿç½½¥{B9){scÿïÎµ¥„ÿçµœŒkkcRJB11))!ÿïÆ¥œ„skRZR9ÿ÷Ö{sRÿ÷Îÿÿ÷µµ­””Œ„„{µµ¥ccZRRJÿÿç¥¥”JJB„„s{{kÿÿŞBB9kkZ11)))!ssZ!!99)!!„Œ{!){„{ckcJRJ191!)!kssRZZZccJRRBJJ9BB199Zkk)119BJ!)ks{JRZBJR)19cckJJR99B119BBR!))9B9JkckB9B\0\0\0\0\0\0\0\0\0!ù\0\07\0,\0\0\0\0ä\0@ÿ\0‡QdJˆ©‚lØ˜¢Á°!ÃJ4LU‚H£’BS\n#T(Ä ÄŠ†¬t°¤ˆl\ZR„Ø¦\r•62Ú@jD„¤š7[Ê¼	©\'$)RH5ŠB´hQ(Q @ÉÑ¢\'¶Lz²hÑ!¨ŒºxÚÊ…“WNZ<uIô…Ñ£G“j,òâ¥ê\"1SAÕ¤P–»Œ²tê„êŞB…Y=´dIG3¬>Y¼xß,Œ°$b”ˆ	#Ê‰&_ƒvÒ£,XÎ^fbÈB’$©P-I‘Š×*)ê Û”¯ƒ²ú¢¨£ìß²a»şÍ† ¢„	1*<¹òæ)S:t(C$?¬ÿ¸^İgO ¤ÿU\ndÔ”HNf„9T(P§@¶*´¸ıŞD†ºpé*¨¿ °û(`~dEÆÄ]z¶Å«t’Å#‘1ÂÙ#X`¡W ‹mñÄ!Ö· †OÀw×*ñmÑ	}Œ™˜Å$“lñH ŒAÙ_àGˆ`\0ßŒğ#P ÂßT3¤¸à¢\Z&¸`\"	.x Bj’\0)‚CzÀš”SR©Hj«© p½ÉÆ›lt DFÊaDC4ôR]/üĞ‡N=‘òCRPLÄŸ@81Š„:‚‡…¨¨á!\\›p±‰&µ¼bi-µh¢‰WÿiÁ	†üw\Z4^faTôöD!Sñu‘Iÿ†…YY0¡Ù# ªØ×^Ğ·E\n>ú «V–™\'™ÅĞELp¡pN´óDk­óT{-¶Ö¢Óí¶à^KµP®è|îµå¶KO»æF¯ºåªï¹ø oôîëoê\08hAÙä“vØ’Ä.ÁX\nñ/»ìòJ*ZhZ©‚hò\n!†0Fq©	AÃôD”\\Jõ9Š¡~†AF4{±˜a°•3‹3Sbó‚^2	­f]ÆH©‚á~ùmrã>ÙFMm¶Ïh;ì3µµhÛu´œSmµ_kk\0ÔÙníØ¦Í­µc›}­ÕÔn«í¸rƒëí¸íªÿC°5p=)ä!¥\0ÁÃ£ğaH\"Y\02+¯¼ÚœEA|ÑE`@¡Â3LğÌ”NÁ>Ï<ƒµ<³ÏXC\rmÖ]ïƒõ<­³}\0Û¬kõ<ÏœÃö>çt\rmĞÊûëœS5ïÓN[w´Å‹î9Ò¯;½´Ô_ë¼¶¼?Ã½õs[+}Ş¬¯›½öz£+ï7pàÙ\\3\0&}¤ –M°\'ß‰‰zĞg0ƒFÄ Fe™ŒLñLL`İ€‰Üíî‚l]ê2ÈÁz0ƒÏØ9İ!ÀËX\n¹5t´0zè˜–İ×BiYÏzÕ›·²…Ãz{ó[ÿkè­­Yí;l¡ÙÆVÄ¶!‰Ôraµš¸Ãk¡c\\ã2—:ì1\0k@\0ÔP\\AFHCKàV±…öÀ§«ê\0ÂTeMpÄ(`@ƒ½kğ.DìeMl†^>„\'¼uäc„‹$áFèHsŒĞ%|ä%\'iIsœĞ“–”G´¼e¯Qª«”Y´:šè¾Q¢‹Šîƒe+­(KWÒ2^®,×*‹XÄS\Z@]Şª×/‡y/øIÃ^¼Æ5*€¤#E˜ƒ\\Á6øâ7UŠÅª1‚¼aß|C.‘‹ââƒR÷Kh™Ï\0â‹–ø¤Ç;ó9oëk^ëp7ÿHê“‘DÀ@PO*1jióV±¸Ä¶UÑ¡Jl[\r±UÄƒRoŠHD¨gèBz$]Ùê(E×åÂUF+‹­×ŞâÇÅPƒ\ZˆiL!ÀÁ\ZÈ©KuZLdâ>jN	–	jì”\Zêğ·’(D±€‡ç ^óˆ‡½¨®ƒ‘ùXÇS9­cóÀ$	-™UH®£“š”GAEI=…vo{e¥´X)×oñÒ\0Yœ¡]­X¾m™Ô¤u£+Ig™.s€\Zü€L¥‘	™6¶€ƒd3 lXö`ÙĞ€5N`\rÉJ\'˜ìgá\0R†ZÁ£^Uw\'½èå Üİ\"¥uAáÿ‘•“»»*Y/™‚ÔĞ^]©XJ{•’GÑ2î0•›\\b^«¸ìã«µJyWæó\\Ê-@4ºM˜úT\Z4g&$«‹ĞàÙÈ\0z³±Ùl€´ºˆ¯|3¡8àëÜ‘øö{Jä¢6}°«çøBØÏ\nr’\"%‚OÈàúÀ%lt%ìW\\ó]>—…OŠ×æVØ–ÖŞ7RiZaÖ«\0ô€¸{l¸´Œ­\0¨qÓL´À§6öi|éK_8`CôÕE€¬\rÄw˜ ;×V:í=•µ¬•îXWÛL6O¬˜Tá–!PP¢“—ôäƒ}[à¶­¯v›ğ½Ê…a|…XÿÍK~ÛºPÊ¾“k[íÇÁÛb—¾4¨×È@&Ahr¶àĞ7r¢|c!šÈçÕà©;x‚Mw»!±\'Õ“a†²§\'É`+oRÌ¾U+p\'0C!Âu{YÌ\"¿P¬¯¿‹Öøºµ¹šx.:˜¤MÄâuO\n/¼¶‹”¿|8øÓ€Æx¶Kùq\r` Ğ„ş&¶É)NBšœ@t¢Q€xV\rkÍ«ì„Ç:ÜeëÀ%¨>ƒHÂs”’#4¤$-ùI~çV“TµZåQ¶¾õà|M±¹öh,\ZˆŸÃÉ­İjø~úú¥Şüëƒï­¹À\\óÂÕuëŒ\Z‚ÿ\0Â2\0¸À	®Ú,÷¬g#‹S#È@Ö±|‡L\rh/x­\r<£Ú<)3r|ø”·%ıy`Û‚² D0¿ËLpqÖÖúğ¬]DC\Z†Øcš‹kÂHŠè¢ĞDÀÀDP„ô…ñ™«tèLİ~Å§5ñµÎÒ\\KnvÿÕuE;Ú‚G8ÀøÆ@\0Œ‡<ã¯ÑxÉK¾ñ”Ÿü5Âq\rË†šä†ß7ki¶¥Sïët7M`>Äà#a\0.{C·‡pª½Qğr”#å@F¹»Bòó{ï\n;8‹º\ZÜ01‡g®P+öĞ5ĞA	k¸ÃÿfA~g$CÎHE$Ø ºuæ®õ Ì\Z»ôE\0{ØÃÙ‚{<æ/ùÇW›ò_ôEÊTmx0{f\r-çrXmúçxŒÇì nF-òF|gsA%ÄA¸oøz¥BFPç{¾\'‚Gp$ø{å0‡$<¸óTî&-ïò.£ì@\0ìSP\0$`˜À#0ÈC}} ;°~àK€/À§\0\0É0…»`~ s·/¸4¯<¿ÄR€è€€ey’åRÑğ\r^÷K?òH2_×p£7zì´ekcÍ`ÕPà0\0öàr•€€hàqåb5/˜5Äÿó:±Ç[¹ÕI´\'O\'³\'‚ œÈ‰\'È‰ï°‰Xîğ{ãÀ‚õ–[¹ÅHB4ôLôpk¨@‹ñS\0;X\rg‡Ñ€# sP}!IxxğŸp\nzğ´ \nlgW\r½ˆÍY9e€ùw†b8†˜çRè\rĞ€	â}Ğ÷ÔÈ‹;\röPÑ\0}ÔcÔØ‹^Gƒ€×ˆ¥e\0ãĞÕ\0?ÑY_Tyc¨röW.U¥U’$u±\'u*dïàe\'”½ŠõHï0Šx‘|ò\0ãà‘´\'I([»ó:,ÄWnFxúÒ‹\r¿H=°t F…°M ®ÿ 	„æe‡?…0ÿ—y“Çxœ\0\0øxÃÔ\r€côBà#DxmİT\r`•è\r\r0VÙSˆ%c¼X`’0ƒ0„ºÀ•ü0XÂ\rÓ°ß@h×˜\'yÊ„êÀ…4`&[#9PKGV¾åIe–\0	°‰¢XfŸˆ‚Šé‘™‰`ÖI–¸[‹DI`UA46gæŠfl*é’Û·½ÀE\"½ÈXÔ0\0ÿ×	›\0ˆ <Ş0l0âhBâsäFC¨]\r§•=G\r\\	?V©•ˆ8\0ÈZI\0Ç›\"0Ô€™ Ãà\níhÛ(ˆìÿàQ…to»µ]¦I™U\0çIïà{œ(Š7piòà¹‚9ŸÔoôŸ…y—ôU¸UU¬=£ÄKEÔ.£‡W±¸pvŒ€rehy™÷E‹—rèEà\r		Ç$±Ğ‡EB\0©#ªÙí˜	ê\nÏ šÒ°\r~øRöğRÏÒ@\rå&*€Ş‰í P—3\ZmÖ@ˆ’·x°\0á`.¤O	VUaæ\0Ú`•Ypïà\rù¼·¥ïI‚	à§8j…\0YŸ½Õ`ŒÉo½f‰V·fÂó:7„OÒ‚6¦Š²Åaú0ˆÈœHŠø€€ø \08åEà€ÿ0q}ŠÀƒÑğZY\0È€	í8@Òp€¢ÈÀ¨º81E\rˆxª• ß\r€	ö° £W\rzürh†øEìà—÷6{$TV“dœô`¸guÉ‰õ [Š‚œXŠ§¸‚§¸˜º§‰ÀeŞ\0\\Àõ`\\6f¨&f‹é`¶uAË°„BZ–®»£eä<€\'/è@x“ˆmÔ`\rÛ@q\"ğÑ ¢4U\r™ÀXÛ@h,Zh¹\0^äÔpÕ \r?ò\rs wP\r<\nOŞ ·\0}Ñ	]d\rÊT”Ü¨_Y´Aôi	6AWv®¨ö[»\'fb¦‚‘©˜ÿé{ÀW‚)ˆ{;Û{V¼‡{œ¸­%X{ŞŠ—‚üÖŸ¹¦ÿÙo¤†[€4-ÕâKîƒkZƒ8Èœ‡úEàg÷×šÎÙš0E\rêuàuÒH%4õuÛ€\ràb €z†øÀx„J¨öqYt5™¶zÿ´® VPP‡B•˜g\n´\'Ta*¦9«Ÿúù‘cš\0ò`¦c:øéi¹Yp–«‚­\'Xºyu›ø‰H‹´\rF‘\0{9¬AÄ=YEXôŠÆFƒìÀ\\š…Y£rÊôr§LCÙ€FÉÚ\0„\Zm“¶û‹(µDüôd[DZÃH`õ:ôLJÿè¹bzŸ¸‰‰¹ã \0§¨\0ûY¦íë¾”«˜¥H­¿\'™Š	¹¾7¿)Ø{8›‚9›¬?{Bïà½…‰`±—H™6f³K¸»J¯Xr\'œÑ°m;™ÜT\rßd\rF9¼d˜ÃK€ÙğEé•6õµëxƒŠˆ»5açƒ-UÅ:%$P¯p<{ºü+¾¾§\0êK:¬Ãé›¾>ÌÃê;Äûé‘ì{¾’I¹ÕZ¿5[³û¹ »­V·­æPfá\nj&¥¬˜Zl“=S4R¦T¯_8z³Fêu\r\'ª9M:ålÖÀÏfNÜNo°M¬m7Mõu@weì/\rÇ/İÆMzç<[vÈµ­‹ÿ‰|÷«‚¾Ç{½Ç{PÉ–|Éä°\0\n@ä œ,Ä\nàÉCÊ=ÜÃëkÄIl³ªÜÄ÷k¹È€·—{\0l¸˜€¹eÄA³;»Z“½Ñ¢5hvgÖUkWL„GnêĞ92}Ç!}Ğˆ€¥Ğ€2à\n(à ĞÛ4FğMÎ0np\"ğã\\Ço0\0$:¯ÌÈ	 |¬\\³?¬˜úPÉñ°\0ıÜÏ•ÌÉÀÃÌÉ›|Ğ]Ê	0Äùì¾©™7|Â´j…((Š×ZP½¥Iu®	ÉZÌPPƒHbó5µ=(-.&gáÒ.*	Èûâ£4€@ÿ ‹PawÑ(Ğ!(PÑ• m D€Cğ4`­‘im³ê{³àÏñ\0Ğş|ÉñPÕ™¼\0LĞB,Ä@Ä>|¾HÕN¬˜ù¹h­d6f&f÷†±K’$UvªiµeCRê™Kæ6ÜBµü$5)Í.Û‚Rğ2/;²ØŒÍØè\nA¾5ÀÈ©Æ‰M|Ö Ñ˜|ÉİÙ¡œÉŸ­Ã¤ìÃS=<l¿g]³k\rºk½˜ñYu–Ÿ¡ø¦U¸\'4Aä:×*[[$]|‡4Dé¶UëV-ä M[îÆŠÙËnÜ.\r¥f\\ø<Ö‚iæ\n{é\n×4{»Ê”ËÄÿ;Ğ›¬ÉŸŞa]Êaı‘­¾ík³Û]¹9¹Éz{Œ<p0û²!¨´–Äe®—Å\Zo´§%=†$-U3-]Ü™QµuuÚOÄó<õTW@D»¶ô·ª—à´C=\"”B\'ô­´÷IÍ{™‘LuYÊ&nÊ¦¿¨¬â‘ÙÚ«m­${E‘–Ş‰MPÅ:P¸ÃÚAèU:	Ş…\\xQ-\\HÀìÂÙ’<pCÜòTíÖÜ¯æ™_šÔ6ô:ËmH÷†\0éšİ¼…IÀu¦‹Ì˜c¦V¬A&Š°ÓÍ±±¾Pe¢»A&±!u¾ÎñçÁauNM	Á-ÿá\n0q0Ö1\'D\0\'2@İñ¤à Ôœ2CI1\n/ãKGz±!°\0vñN³Ÿ¢â)œ ûáê²ğ¶’\0Ô«\0\Zyr<@ìmáNĞ‹FÂ í‘\"²Â2,päº!†‰û˜\0a¢$°ÑæÀq&ç>±&N²ç|Ş×—ŠP}ÏmÒÓÁŒîè0Ñ?`\'}€ÿŞÓÍQ \nP€¢\0¶@(N	M°¢zá \'Â(ĞŞ(âş)‚àñı®.Z°	ZÀÈ’@y1íÂÑ(áë™ÿ\Z³rŸ±m¤ ² ¨ÒFÂ«âOğ ¡á ”!‰0—_ğ-Ygì3.ô\"/×•Ø&÷ÒeŒqkÆ/£W:\"€_C\"$˜0$?\Zî°qM×D‡^Û2Ğ¬ğ@°~â ìxï€€À›¡rò!ÿ!¯²Î]*\\@\ZÛëÂBkq¬rQqr\"Q1Ÿ‘ ¼\"3”P“À>c\"¿Üì 1ËB44‚†à,7ÒÅÑ3OÖ’ZkÃ>èÓ=[§fábZ¶¤JëâûÆ…l¾oØøe\0Äù±*‡ePv\0<ğšpW )„ ×/Õıÿ£ıZ`¥Ñ_ ’Ğ~;‚\\`-\"€Mf\0¸0] g]Û‚_]ÿK)\nOª£wáŒûlÑpå*Ä¼s	çÍ;p`sÏøP\"Å‰VŒ¸¢EŠ!NœˆĞÀÉ()¢Ûèñ!JĞEDÉL™7\rĞ£·rN\'\r(@\0?.d(\"M±4*DÍàQuU BOe=ôdR×-²<Ú²eÒ“0–æ<;0¡ágûìs+÷-Ã„çè; ÷Âˆ}Ï‹ËWï\\ÁóêÅÛ0aCsÌå3÷âå˜Bu¾d	R¥ÆË4Ï±Z2aÈËBaª~yR\'jÒª]£ş)[¶ÿÎ›ènÒûVÜ\0k×²	ˆ\"Ä­oªY²äP @Y°0š%£,Ñ%JÄ„I\"²O¦ø:€iÙôqá¾eï¸}b‡ğóñÍ÷–¯9¾oë·ù\0òĞ\053ğ¦on›Í65{\r§×jËM¦a›¥\ZDÂÜ*ÜmCeK0ÁhÀ±Çš®É`9\nêÀ•j2ùUlqâÆHlE()‘iäd ÁE$ùæ&	£À\0Ëà’‹‚„Øš®“NÃL0Ãø¢¾Öihÿ\"Ë«!ÊÌ!PMD@¶Ú¼\':m›ğ¡×nš(Bİ \"‰µÑ\\t%Õú„ğĞûÌÿ“ÃC{ç7k¨¡F\ZJ+¥&Ò\n2DšA:íô\rOs©4p2É$SS©!À¶Öüôs±Å^}l1ÁÖQˆ¯uÄ´µ?É³/sr•lÍ6Û4§4ÔêL§”V´¶V\rmpZ8eâLYl\rœ¶ÂÙŠ¢f€R5µj*ÈtOÈ@\rÔÕÀ\Z\ràh7^t×…àĞßx’É},ŠÊ‹şr!úb+1úêóò1¹ÄTS²úx-–M\0åA@sä‘)#mu­µ	ÄM(nOòÛU¦Ö€,Á–Ñq§Ùx+\0k¬¹€šLFÍÔ\\l²I7ƒlÎ\0é¤>›t‘\Z]làÀÿ›LúeP`(e‹è™+óJ8#ÃÜ³!÷Î©¸Wş&î@¼y3å“Oê)·Üú‘·ì·§úü&­Ñ)<Ã~[NyA˜é¼ñ›ûVMp¿‡ÚIğ\nzÎ÷‚p5­ …L¨A×Ô¦¶æt­ºj©[ßW—LàÈÚ_\nÜÖöÿâZ6³Õ6Œ×·Ôœ@Í4\\¦X¸İPnœëT”Ñ»¤‡ò¡*,€7¾‰‚yz˜uÒ\Zeğ´oÆ\rqÃ¤¿<ûo¦f€z¦Ië—48k`o¡t]€Î¤õùÍÏjRû_&®Ö¿ïíÎ6Ü\"	—H2šâê-¶r`—x¥&5-?¾\ZÖšÊÿ¼Ï%N\râVötFÀp)œö÷2ç-K5šÍƒ\np³¿I¯…XJĞ%:Îğ\Z¸Æñ$v*ƒÈE.0Ğ‚tPÌ©8®hĞÎ2Ï \0Ø,Cyt‘Iù±š¤T60ågm‘q˜İ ´\rn(‡747™LZ*ìÍ\rY\0Rd ‰2½>~¯rZKY‚jÖ¸ËÁ¬r–ká\r)éGJc\0ÂÉF6z‡ô¬g×¸†ì¬hÅ&:•¹h+¡èJSe\"‹\'ÁEÂNr\0(I¤‹—±İ`Î6øäeƒaOÙ‚c Ëè ›äfÇ¸!ÀvêK×DÆÂ@ÿ \Zà¦4PmFCœÕ!0‚¡Œà@!%)°N.nJ\\ô×Kx’R‘˜$Ê6ÙÁ\n€#)¤¼F\0@Ê\0  eÓ”ÖÉlç ÙĞ€\'Ó…\r×–ä“N(íJµ#LÂ3—ÂèÅ–ì©XÅü“ŸşPF@s¤©<4&d”#òğFp™Æ,µêrÙ¬\0§¤a®£V SÑæ‚ÜÂ?`ƒ\"ÔĞ„Ğa	xàƒ&°°\Z”Ó_ë\\&Øò$¾P	—|‰’íÖuÅ¿ßAšP¼æ5¡Ğk_õÊ×…ú5°e(>ÀQÈêym­	#ŒCtÇ3“ÿ0÷A“ğÖÄÌ^(MÓ˜×dG¹iL§:õé>æ±†ì#\"óyÉôŠòMs™ƒ g5ÈÙTL¨`*P.( ¸Â)thÂ\Zğ€‡$l	§H\0„‘Œ_¼\"£P„¿B*—}äÃ-o±Q¾yºö•¯½ëxùjWR4à€¤HE\rpHCRœª\0 Phl\n]<hRìjĞ¤ğ¾à À Yƒ¶y˜V=}ÙO{â(—|Oƒnšé21ÆÙí´w¼©<F›\0\'`µêË9rEÁÓüÓSG‹]\\`û@Daç:‰‚	§šA¥Pƒ*Öƒ:á¿Æ,ôğ‡U¨Áøÿ“Åi_F£ğï^ó:Ş\0à#)Õr\0ø1€à	0Cn1!‚oŒ \Z\'5šÎüJãÉÑ°Ç¦µ*ËxƒÄ€F5èj*ŸWĞø˜ké¡\'[	³LiÚlñ”÷w+M¦ã;<\\\rczÃïÈtÊ1OcÄ’!Û&Æ%“pÈf‡&J\"QH\0\nlsM–1&\\±‡R¨Âx’p‡&”‚\r¸§§‘©\0ú xÅ²•÷\ZØó\0ßpË1|	\\ˆ Éçäš«qìøF”ÚÔQ9\r9K9İÇŞTà \0o‚\0Şp€¾¡IH£gI	Ç]k^{ôËw‚!^ÿËô6cap@mò°7Ê¡&›^\ZÓœÖ´§A,êqhğ\0Ì<õ¯Ø&¶,İæ1ƒ?Ù\r$JBˆ\0ü°ƒRôÂÆDĞBœ‚^ÚƒÍkAzù	`Úßğ†+ØptIà¢\Z\"˜õ¤_OyS\ZA“6¬A)pT`FTèÊµ\rl4YÛÕxƒ²­¡4@J\ZÄ6‚#€….°µG–ŒÉ6ÓmÁÓ ŞS‡k\ZÓŞğ†9_q»#á\n\'Şš0¸÷\\ÑVyIõBZÆlF9Î”JpÎkPĞg9¡øàrRpTkØãÈÀ&16œ9\Z\" \0›1ÑüÊ¹_øåÙ\0\nÿ€	ü@\ZÊ:8¨ao¹Ù(°@nq1j$…\0ã ±·9|¤ˆŞÙ¤½=àÁc0¹z<›ÌMˆ[zÃ‡9\"Í1HËƒÓ	p‡;D<¹@æ¨GÇiÊhF‹£8ª4À„@­…0-‹ğËcÀŠ×²‡à|Ğ²*Ã+\0{0½Pz( )P}˜ƒØ¦ûŠ†Ú±\0vJ¡Ò\0uX€Øc§jÀ€€ª\0{ @p¸\0pÀ€Ú¾˜ƒj@\n|¨‡€u¸>öê2¿º2vÀ«œF{¼IÛˆ-‘?/1wÈ4ÒrŒÃ¿±)†³©Hc¸™£ÿQä51@ü“È2@e‚C\0™€\0i‘z‰Cz5v \0v0‘¯/¯(\0dèIPEpAüª/\Z+€\nyÃ€	Pqc¾86s1ÀÁsÊ-\nÀ„A\0/z@\0nà…XÀÄo˜:¹+Âê9Ğ\0{ ‡S¦u Â8¤©Ç;Ã³)²©BC:Â4y°¿ûS€q±è):J€eô;Â[¿jCnŒhÆhB7¹¬…Û?šZ¸5i‡ˆ‹‡œì¹W{­â³1#\n9û6£šµo€:È=ÛÚYbAà³\0m+€@o@dè†3xjh¢\0´á@(f/`ÿ­	˜÷ØÈ^Ñ8s\\8­Ú˜w<LsÆû5Ló´•<F1-˜­œÚ0c¼´ˆ»É‡ã:rC7Ñ˜5„&óÊÇS&÷È“\0Ô\0éiŠ«€Ñ4Ğ2\0k¨J0€¹ú@60K\neÓ+îË¤À‡/+ÄË…H­†€¬Y)1É8‡É`<tt<dìB²I11–,hH€¿äË”5Â”FÒ’Fó°{Éd¼Ée¬I»©6ôNKb™©\0i°jRŒÔ¥ô®Rœ­NiÙr¢\\¨¢X2Ø‰¥w+¨şÂ«k°«ş€ø±p1o²/»É—x‰†p•ÿà<.Á¹4É\0Ñ e\\I<‰ãËh„F¨NÀ¼NÁ¬Në„ÆûëNÁ<Ì‹ÆÆLÌñ|N‹+‡™ü r¸¼ô,p\\û°½ø;ÁÂ©lš³üú”N‰7h‚\"¨ˆÊ¹¤a\rÈ8XŠ‚ƒüAk`Ğõ\n—Ú£$×p!¿‰R-ö0ôĞÌ;j¹<˜ô4c,‡ %@Ñ]QeÑ¥ÎërÀ8ë,CîÄ¸îŒFûû´ú³8½TOùÓÂqÀÆ?Á¨„\0Œ”h–¸	’Á™»1Ä± ‡´t!×z-Cä&sY:Eà­8º£+Wà­3Ë65³-ÿ7 Ä\08m\"P+š­¨i8dÈ°ÅÜ©ÆRM‹ÆK[\0D]€cHTDU€cXQD•€GÕNJU\0	0ÀÌNÁ¤Q	¸8O13PLQFd ÕsìS\09ô8kÕ÷X)¸ø¢[J{BËX\'•ÁU|Â§Ç‘s’À¤ü‚Óü\"\'\nPSF€ ¸‘Eh‚Q€Rhƒ¸7pƒğ\0I°Ö¸\\ğ€!„Hs­t5u5˜ÍUiÏP‡xèFMTr8Ñ|Í×UÑ¥ÔLµQíŒFñOÅ$­KQĞŠ¦ÎJ\nsÌúÃ—rŒ.qŒÿ£ÄÆÂ%*’ŒĞ‰¡!d¡–I¢ĞK29Ê-Wˆ•UYK°)hƒ6 ‚\"!‚6¨E˜ƒ›?<ÇR×OU} ×{Zr€}=Ñê,ÚJ­Ô\ZµNğ$ØñÜ0i”‡BÊäÆüëFä	-@‰eâL–:K­‡0-´-‰ jÀŠ@[Ä 02-[*Ÿú´›i©}©“ \0Ór¸ÙY\0ùÓTŸ\rLxÅ×0Z@TEZ£}Ñ¥ÑåÎ…Ú‚MLÂ?İ°\ZÉä±)ÃÍ4Rfj)ŠŒı@Î¹¸;\r-1Õ)Ã0©}\0£Î—ìjË¼%Œ%õ\Zˆ\0£ÿ0\n¹»õ¹`ˆ€İ† )Í”)eJCxL\rÃ4­TÀ¤Î5ÌMõÔ‚\r1ûƒ^Êt¸3$R8t¼+t¼Ì¢È¸ÎLˆ\r21X1­ˆ89úT‰ú¥OE!\r¡ú‘Q(%Ÿ>ñÍëLd©2áÚşöeÚ¹!Éqô0€ÕÑ2œNÊ½8ñÄ´h$-—ÑÀcOÆÌ4–ã™Ë6Ñ ôÅ e€ÏÔmõ}Œ¾ĞĞYÀ\r?©ˆĞ±Ñ_‡pâL?‰»jjŒ6*4‡>¿ lüÆöC€(V“rHEE0_ğ…,62e\Z 6ğâ1.ã£3…9‚ÿ2–DI¬*ITc3ã0>/ã3¾c2ã£›c\Z0’6?şcš•H€€Y˜%äB^dHGn„Rh„•…‚(˜‚QpG8„­È\'(/X‚B(,HCà-8e-àUö. .xeO - Oğ„.Ø‚N¨GhGØåCX„C˜Nx‚²ècŞ‚P.M>„X„%h\'h‚&8Ğä\'èdèì,¸,¨,‹G˜±èæ,HFø,0CØ-S|ã-`c6fƒP25!˜ƒ1Ec!ØgIÄÙy†gİšç9€½ƒöØ3:2í\0Ø«ª£ë€¨cÿW°c‹–Š¾#!B. öhHø(ä’şFè!AD…) ØeªXdÆe­¨æ\'¸Ší`‚RAĞAà„Y&¢æ‚pevæ‚.ğ„î`g&°,(©cşæí0gëèf¬à@èdMv/à©Ş‚@@æèè„ìÈ‚N(f©Î\n²‹-°U‹t^çï0NH:Ş’„xæk|“ÄîbIÔgz–ÄHŒDyÆ¾.l6ğ…£sì1Æg!0/®ã|öÊ&S=æã9.’?é>(éB–’FiR If„HÀäf~´¶N êdîäQ&eBğiM\0jNğiÿA0êR~åW.e¦N„/ ¨j­(„³¾G`è~nèÎ°æ¬PæB˜„B˜íÙ>ëNÀîa^‚kåa&fçFëtæR>ê.\\pà’ào%×ù&WE‚ülı6l+V„ù¦ï½ÓÂ~c|¾b,è16…{–l_ÀâÊãJã	i˜í’i’î–æ(€éfíeGxmMî„G(k³ êNXé8j.ğéW¨¯ >eCĞ‚ Ö.Ø„WN,8nëĞî-8çÈ\n/ØnGñGÀ‚ï˜sşæ±„¯˜\\ÎÙÖî\'€b–êµk´Fëé`C\0FàiÿCˆîè‚D Ğ^ıê\rïñ#üD³Dêo@³@jS¢@3àŠ…p¨5ùşVlã-6Öo,¾b2®c\Z¸…Gçc˜-dÔ˜¾t‚/qç°fgŞŠU(,\0„4ß„ŸñW@õ×íWPeçC N „2?nïêEğ‚°^ëU0ï±eRgì kìXë®ğ‚\Z J e_„\'8æ¸îæN`é¦öGàRf‚.èC°eâşŒß|\ZªµœŞ|!éqÇ]Õ\ZâÄ’@xw!vw!	|\'K.j¾æ“gŞbƒƒŞc‚ã&mQ°jVs>s O6ï‚/0nÿ0 ‹t„\\?„08/øå&õ±GX„\\Ïø\'xVk.„ëÈ‚Nfr+?‹P†]\'/x‚1 2`n±ˆj«nrjßˆ÷„nòˆïöáæ‚¶Š½™ˆÀØ,ÑÛø©–™GÊÙ(÷×(œKŠÀis¡h!7w­®—²D‘\0È$ĞQÀƒ+¸+°‚O@õ]Hõ¹Ou nûZ¸‚Üşí0P]Íó>‡\'À…æ{†~x`WĞ&iDI—••…p‚Ep1ˆGØø\ZĞx20ùN&g¸~Óg„µRˆÏ‰°±;BÊÒÈŒA©¡Û=§gœİğ_“±.ÿ¥úkÂÁ1\nÎ±+~`€;È([¸ƒMHèøæu~„íöB`‚M¸‚M€îaŸ„KPñ-\Z˜½ı(Ûø—ğÿ\'I]}’ÅY÷[ú’Tûİ¸ß‹b0‰0¢	ûˆsó8pîœóœ‡p¡B„\nÎ[âDèjLØÑ£Çyô<IR8jÖ°´‚‹5½9Y²hÑ!<\n3ã	Ğ\'…Ê2iË–DÀ@™óÓ	 R8°ïÙX³r=P•á³ƒY)ˆİw.«Ù}YÏ\Z4ˆõì3³ÍdØ•kAsY‚|Ò£Ä„ûJ|Æc_ÿÿÍøñ/âÇ\Z#\'D×Ñ²“•°Œ¹@¬epM\0…=jš±akÉ’B:	İòdËì@YMzÔ)K¢D_ñ¾¤èÙSLP±bÚª5+òçxÛ¾uÛ–+[ì]„Û5{¾ä›h‘rcË-#¤ C\nƒTï8äf‡AVü¸szŠQ¦™f™T€ À5d`š=sĞàÊ-oˆ°„P‡ŒdÑaYt2IŒ$bˆ!„bH\"Œ€1†<3T^©Uã]k­E×@ä9j\'ä]zéµŒxâ!`aæ5†Ÿcûİ—FñQFˆÙ7€şñ×%f]ş%ÿfeóXFOI	\0Î\0œ†Ú-·¤B5ì°E!<l¸!\"ê†ÅoŒtH![tRÈ^,bÉqQ9*ŸVj™Õw•yE`[æè••‘\\9ê’\"„_‘yå“A$¦™„A¹ªB[N9&®ŠéšPõ\Z4ö´yZs˜áŠ+fˆ0H¶4qO<A»HM8áD$¶\01\n¢ Ò$¦`bÁSk@UUYµ.YjcY	ÅåãA˜™Ux,‘˜êŞ2æ 0ğ’»Ö÷¤Fa\"l°Á]¦+€ıIf’ÂQœ0€\n#Èğ™œ`Ïü<8€ŠÌq2.dâ\n\"= ÿ\"1÷ĞˆŠ`2BÉl¡ˆ\n’ˆào2ˆÏÈxî¹‡n{]jĞ`	t–Eeqd[‘gä’ùÔ{À:¡\n,0ÁI&˜/ŒgSjÙØÛTnqÃ­ŠàH\nôMÁŠœÁÒT3x5ÑT`Ñˆ°¸#NøĞÑ=H´0H.”gB€ğÂÛÒTï|R¿Õ£jEÄ–×›\Z™§áµëc\\°ßüWw®/|°Áèu„fß(YÂsg–ûfòÊüò	F3Ú\0\\‰Ê™`€ÊÖÀ¡K&á‡ßBøƒdÒùˆ¿~&èrè\Z6?Hí*Ÿ¬ó˜Õÿ–Z@fwaÂ±l*S92ìbw;s ŒV¿c(Á„|@šiÛ_*¨¼çm°mŸ¡G¢kX\ZáË^Tvlœàğ^6¬q8ÀáØ˜ái¨C\Zbƒ†ˆ†ñ:—¥É¯IB$KX¾\"@¨=­+$Œ\0;%OuÒÁÆ3]J9H•êÓEäÉUd”ŒÃ^¼.ñ/	=øT…\'Látx‚lhÀ…\ZÈF4PÃô‘†.Ì@\ru¨\n (iaÁ\nHƒ4‡lå~ğY‹%¯ó)ë°®€œ\0EuE²ÉcIÇcÈ°\\QƒœyRÚ€·+ZHÿƒ–Ñ H¾Aãm¬•	4Ún{áöôØÂ=f£™€da6iÈ¶P‡@LšGŒ(/ĞÍã+WáK`ÄÒÄµˆ³‰üÚd¦6™Û! =AÌ¥òT	=ãÑ3ˆôL>ßÙJ}‘Ÿ¹góiÏ}‚F%p(¡4††\\dB\Z™ ¡ ÷î‘™6L¤.2ªQ2²h›ŸÒîI¿gÌã-Ó‚”/¸ÀkÌW7µ$òœ²lĞË+T±î3w¼ägg(ˆİÓ]ò\'óx\ZÔyæ®3&±\'¬!Õan¹V±Gê7ÄF—™¯rU\'ÈèY»š	 zn›õÿ3Ÿ#ÄÀ\\rœ“:—1OíÕv|åkÙ0Ó«^zdc@Ó“øöæ— éå;/ÇÉşÓ°ø¼,cËKæõ2„@äA/€L•eï\ZĞEÀ—‰Œ~«}è÷N›‰Ój |µ…#‡(ÄİR ³K“ú5¹…®‰´;Ë§Ğ)~u\rz%›)VÊÁ2y\nÃéf9óm7Ş¥\'Q/kİâm¦‚l/.-½8vŒ$¹‹£‰Jf`¬Ô¸\05ò[Ld€®\rŸk±aB×Â¡‡æá÷tQàaÂˆ‘”Õ*íRRqÒÇ ­Ã+íÖãĞ“±»bíÌ!¾z#ºhô ÿu/“7\nT¨Œå[g2ÛX\rŸ–¡±İèÖ¶¢ƒ@LíqSs7Ôlw»	:P0¥Šêİ7¿&üì5®‡>ö‰|]ıû4ºÚ÷e‚°i’f3:ÒaR?!`†Ïl(âËÃ{=ˆ†§H°é¢r‚O\nª‘\r+cÁØ²¯Dê…\'ÔÉfv³Š½±ô\Z„HïY¿Øh®¡\\¼!s¹È…år1eôe4µ©íôùÀç`¥¡KiqO“bbgjû §<ÙæëˆŠ<û²¢…8çš¬‹ÃÌîv—·åíù—\nr,cİ«ßÉ3MëØŞ¢½±[6µ\0lr5ª—í`¿p0Íå†ÿ&4q+TÓ–CùĞ=eí@iQİBŞUºK,¨sWZR/Úy8k¢J ìÖ	;¼&P`ï8e)å!\Z4„\"`…­‘ÃR¼â œ±‚@xd;ˆ‹OR#>M‚·÷ÒXã¿6	«§íÜ×MÖÀ\0æ&7¡	\ræ™‹9¦/m9ËU \Zº5¤8²wQ`0‹×d—/ Ñ_øÚdÓ7µÎQ–*l|U8ÂåQbT²g2ññ>\Ztô4ÑPÚP\r±AÏxgv·,ˆD¦¶‹3£ i¬DªR\0ünk\\#î{­jÉw¾óµ@Ó_-ú4\r¾AlÌˆH¬tŞ–ÿÑ$¸uùZÚÂ©PùiĞı+¨®ˆ\0oh½Zg=J7Ğoô9\ZÑ \0î£Û‡\0g?;Úù&‚cC½EàŠœ#ìáÅ§‹ùF{©Ãö¸¯@Mã&@º‰ğ„o¦ÙBhz¯µ­İèF×Ê<\\èvù‘ÑGÅ½$€Üñ·9†E½t+3%tÇÀÌë¹^ë%\0–€_œ\rõ€:à8€4¤P\nM ¼ôĞ\0ÏŒ€‚$Ÿµº€dÓnI9ßhğÍ®	8Øƒ°àC8€\rŞ \nÀ\rš†À5à i\\ixœ5@f!AÿêÈÛ\05Q½HÍì„Ø‡‰Õ‘ÍÁ‘7ˆÖ•ƒ7xC”ÃúÈa<„DÆÅİî\r\rVMàå`ÀàˆÀ¸ÂˆB#Ğ\0\r¨‚8Ë(ˆBx`c%ÄğÉK\\ÌÏ|@\r_àz¤îÁà\0K„ÃÖ`\0ô`Ş %Ú &ú`&r\"Ş`áÒ¨Cc™×_\\Š½\ZC¤ÅAHŠv€rqÍØÀÎZ(PÉÃ;(ÜtA×*\\9!AT$íÅ*ˆáV\n¡ÏäÎàBºB KÌ\Z¨Ø‚Ü‚ƒ3üB\"D ŸŒ ¼@ÇU@$Ñ7\\…ÙU\08ÌKdÃi„ÿ\">æ£(ê ÷Òúà%æ \rŞãâC¾˜—DUœÃUÆ>È‡ºvÀ¢\'!@>€\0ÊÙ\0–ÎYÂ\r&€HÒ9QÍYp†R ö`@BÙŞÚUƒŠ€\nÌ\nÔä¸Ğ¡+ôÁì€ÜÁ`Á,ü‚0¥0$Ã+$\"8…ŒD¤­mR`€²	>\0¤@Ş#\'Öàz?–¶å—€Iƒ“\r‚äP5´‰ƒ<È¤(^b$€)zYD¿í‹¾¿ÀNÀÔNÉÙˆ!@9d0Ì.º^0&€;„d¬Ëª¡[–>\"ná\0Îˆ\0&Ø$ãX€ ƒ,¨ÿA\ZœÁ\Z”¼ÀìBe28ƒ3,4.´íÍ…TĞˆÑ›hàÃƒ¤@$K\0>´D›ğà]O¼¡KÚíáíMŸáøí-.Pgpê ?\nçT€=(á š@$MWP$’ĞT¦àİ^E—`&/&Â!œHÖçHâEÂâBè™)b€ú\Z\0ÎX\0.PÀŸâŒ\n„@ÈÂ4,$Á\'œÂ/\\è/¼\08\r83‚Æñ=V¨Zz\"\'ºIp^âi¬èid[ÏUƒ¾à‚øÌğÙ„îÕÉ ÀÜ PÃ |Ã24ÇC3˜A,`Ïh8ÿK°(Kğƒõ|gxV…:åå‘L>œgV°Sq)ÊC(\\Œ˜\"\"&cÚg\\\nLÕEa¡‰¢ÚKªNĞaä¤\Zì€*¬è\0|Â,ÌB*ÜAü€\nôh5ÈÜ’–=Äà\0¤(>²(öæiÀ\0HÃ9hƒ+Ì/(B,N48í‘eÏ½8ÎäDÃàåäd5€ƒñœƒ8´‚/\0Q5¨ÄJ˜†\r†ƒRâpÚ;(¡%!—\'ìÌÔFL‰Ò(V%b²¼†ä8”Ã8$À^%ëØ\"¥ÔBhĞSÁiÚÅeæ¨`‚$ÌN6‚*ø0ÿÁ|ÂÂì\"à©Æj&´Z*(Z¥oî#8é(€œŒ\n¨İğY@,HÎîä$TÏé^äT¬Ú€NÔ;„$@\nTß1i\'¶,?€ƒ:Ø…Õa©)Ùâ_ÍÙ;ˆX\0\\0X>(œ;Ôg0ö¬bé˜Ã8PQØd¸^ŠC ˜‘ÑÃP]\\ÅIeî\rÂÒ¡NÎkOÂÀ‚*üÀ4ª«NÎfB¯l¥Vê\0À7<Ã2@Ã°\nÔè¿ºäĞàViê¦òÍ dÕYú^tºäFC&°ÄhH8˜1¸B5,NÀNj\'şjÚÃbpí„JÓQQ´Z‘Eÿ\rÏB+ãĞ–nb.¦·v®9Ì,Ø”dCÆF\0YÈ¡dÚ*öÌë\ZøA¨‚\Z°A,´êŠåJ¬¯j§of¢\0Ë\0Ğê7(\02ôR\0.X@á¨]ÆÚ4H V¥8¸êÅN`*î÷f‚=¨]ñ¹*5§/`Ã\"&üm0Y*VÀ&Ú >°CD´ní,í(œ†©9¦éÊC0b«`öléºÃbºÃ8Lİç^‘Í~J›R¡ÜHÌó„WÚ™İ|À€*ì@\Z¨\rˆ€í›YÂŠş`®å\'úæ5d@&4ïÛÀ8P£fšğÙ™°ÀßÆ`~¥,YªìßÊc¤âÿ—î}¯âD&ˆ\05”†¾¤ƒ1ø.ÄÂV½‰ı¾ğÚƒ:TÄ™½Î¨¼Î’taˆ-Ië° éŠXbn«ÑÃ\0RíHy\\y¶šC,M#\Z›ÙıqX€\nXo*”ÊPƒ<ˆ¥r±vZ¢pò /Ã7¬ƒÌ¤ña&©bÀÜÌ«šO¬İô\08P\0&Ô‰C¥¦Ä\0hÀùbÀùVÃ‹€ú<H¸Ã0\0ƒâÀ ¬¯òî ÚÃHÄ›……G¾|Jt\rÙ\\Ma<üìµ ì°}í\0L¸®Ó%×]8]xÄ`ÈE–‡Ç*”ö„OÕ@o@²ğiR›XCÿ€Ìí¸˜Œ$XÀp¬\nxh6ÎàÌp€Ã|ÃØùàÅ õ(i–Ì¢~›5ä3(‹äTÀ}\r\0AşæZòC ¾LÀ†qÅ:q)ÁäâèòÚB«tIs·\"­a–ÒÔı,Í\"P}nÀIpÔM3WPc0zˆÜ/Q­ÇÊ#?ğCv.up§\0h*rrÎ2ÌÉĞ¤öV©*&`¦‚ôpÏe\0;ÃØšİR`¤\nË\0qGYµ<‚u;@ÃÛÙö\r€R;5SÛ5;Ğƒş,-ó²nd4Ï§§Œ¶òâ.Æ§‚i¶\"­L³2Wócœ\03kLL™¾•Ü,Ä#íÿÏ` äíÍ#%u(5K\'>¬D¤vóÂ¥8)È@MZ¯4D¼A»UBsu5ä‚FWÀTÓíMØC~á5ØCBeÂc€LÂàƒ:0@7äê7ü­	™vUaËÚCAäÈxˆ\n’ô•L­sVëİ\Z‡¤4Û\'cöµN62[Q-~ØÿŠ1Á!ÓNáš‘+¥€ËD «:¼`¤Fâ\0àCj?µTÑQóbÂ-ğŒfZ\0€ÃKéV(hT#ùº8Vy§hi*8$\'	’2&|Ã[®;<€+Ù7nƒ05Ÿ6ô«ÍÖÜš§dî\0?óÁéâz9_\r-Ğrÿ«+¦ë]«Â\röy´fQ\\N›ƒ7ì´ëP§\\\n¿­C~sîÛ1UFQ\\‡;Ìã<Ê >À¬FÃS¨€\"\"«şÊ*ßœnhFƒƒX6¯\\\0&8§4”¢¾´3Ve¯ò#w,>\0ªøI[_‚ë:ğ_•Ã;X+×TÎ–®’‹dĞ.&2˜ƒ|šÒˆİl\0ş.*k×ØšÍÖ\Z(Iä^jJ‡µ\ZIBRßTz\'›D*öH«æB4TÕ7ª4l5lC1eCI;å95À$ßlV,Ã2ƒ+ˆ\0L¾Á¬ĞZÖïoÚà÷ş…HG‘D!¨<ÿ¹ÎU9é&¦Ñ~©\Z{kÖÒ`2³/ª7JÇ•+_Iv× ĞL\rL¦+¼r±PóJø«¤¶ù<ºj£~•ŸÓiÈ‡ÈòƒÛ&\0`‚S<¸ß×ùŒÆp^bUÎ|\0Ì¼‚×¼AÖ\\œäCîšµnÔA0–Êú2ï¢t%Àèî{’»H’¡™fkbF½b-ëıb¶–ëc5ç¬À›÷Á¹g³–·E.ÉxO¤•0QÇX¡İ²C‚7ip’Ğh\0?ğ-Ë5¯âò^îÑ©äPCÏºT-õ%j§vÆpFâAò­YJLaÑIÏ1Ãƒ¤< C“{ƒ|V=b‚©<tÿ+¾§iš†©HjşÓ#À\Z`è‡) Ã²ÖÕ”µ²şíØôy;×ØÌ–¢£ÇiÜÄ™ùšĞµ\08È›xß%R\"Kd€\'\n¡€ƒ¨ppŞãWåƒ<‰VÀåaipU…¤0äW@M\\œL=ş¾Œ}5Ÿå¿\'é+0/‚)ƒé4w«ÑÎ¿ü¡}‚a}ª~è‹¡şDoåz“\'Ï›·Ë<ˆ\0¢7ˆ%B4‡À\\>Ë&\\<ğ1ßs\rì›g\0eJ”V\Z(P \Z;pà2eÒeó¦.lpxjĞág¶Ù2dË& €€kK¸æ´)Ò¥×X³6\0µ\n¢½dY€ÿ^J\nP<`àìg\'Ÿ£°ö\\[F†œ\0rÄ	å!C† 2y	,,¯àq„/§àq‚q\'K^Ì˜±aÌ„ËæL4hƒ¡;#Ü°bbå¼™“gÎ›FŒÌe\\÷Qd[‘óĞDV%Jt^Õ 0(Ú äÊ•ç\Z”Ëù›çƒŞTÏ•á©Ò§M^;ªô¨w€^ YAšq¯ß„—5ğ,8Ês*çûwn¤Èòe4÷Î\"„h5‚ëŒ°€LÉœBÈ\"»Ì±Æ»l3Ìkˆ³Â:M°‚11Ø&:\0#:2G¤ü\\Ä/8t~‹t¾ñª«ˆ&ÿ¹ä”{#ºêÜA—¥´£j»kÈ#Š(ïŠ*ŠI«ààó¤ä%dT‰¥µ4€=öRš¿ùp[æ€¼.šˆ\"Óˆ3Ää!‡ê\\ĞN<Ôs3\Z„,<mğBÊ0¬ìÂÍ>dl0Â@F äas\"4b7Î\Zi$ê3À·öTbï«áp„‰Gä¢©&šX0À»£ŒÂªà¨©\\[Ø•×]uéé\rà¸\0–\\jÉX¯PúFÌ¹ÎÙç#4óÉKEˆJÀZ=óL³ƒÊY N	$Àó˜;õ|°Ï?Õ=&İ@	3C²D3œ7ÃG5è/6óZÍ	&)ÍhıUë€yÔ‚ÿ%²È\nn¬¹šMøF¯$Fµ+-Nui*ÈÄ\nrÁ€ºêŞAIQ¡dDa00ƒ]1¨¦šh®fæAZĞy\"uiAV¶À‚g09`˜Ã¯Àkz3Ác¬À¬&g	®^ O¬¶“Î®ñ4÷ÜÉ´Qy¥Ğ³Á\0cHRJ-Âè\"í˜Ó‚Õšç$“@ÔøfL6Ù”^¢\'ËÄOU\'\Zv,@„˜£6\\ùsÌû Á\Z„PD‘•GNç\\v9‘«ûà\rÖß\0Á¹j,˜À¯Ú±½–ŞEÙ¶šw¬Áå\Z\\rÆåúÜ=‹t±)c^ƒ”5Hı\Z¨ÿ\"¹\'Z¦?L3ı(?´Î’¾¸<\\ğ-[ÓØce¬õ\'†iÇh¤Yr\\æ`C†A\nQDBP Aª07¼¡\ZoˆÅb7ÄBnpƒ<PA\nJ7Ğà\rp`´N~±–ó¸¯ÊÄƒw)üšğ¸æ»±¯xJ	áÅ6…`¦Qq[¾Ø´¦jA{Ø^¦%hõí$÷áßÎBÁ=qpêã’á\\\"1‹Áä%3{‰æğ\0¡	‡£#F!TĞC‚$Ò(‰!(âBøœÔ(PxDA	<°—j3€\"Ìº‘Bßokb3×cğÄÈøi†jÿS‚>4„üE\"™kjcùgˆ¸ÉDRºx*%¡B	Â 8£/Å(%ì	‹±Â¦õˆà}Ë‘\\ Db\nShC%d \"\nmhˆ0@eó™T :0¦A7¤—¼ÊªpkàÒZğêt\'²	jPB[…L¸­Åôe0Ş’Û¤¬‘|ôgæ¸Ë¿¸­}@k$ó0Éöq³”D>˜‡BAõŒ}´R•e	UD\r\0*¦¯Š„Ë¥âfL<ƒ,û0Ãö10ı›a…Ãk}…Şg8}¶<Ù´lfc¤ Ù™ĞdÆC‰Q&WCOÖ?Bd‘ÿRµÇ¢shO?CìÔ}Õ)·)•¨JY&ú¯\022_KÌ÷•¯œo.baÏÂ>rR?>ŠØâK£¶É!ÃÈT…‡üÖøZN:™­‘{bäò…¡†Ğõ4‘Ô¤‚£N©•bH(K7€Qö\0ëÈ@qª‚\nô>ÅÏY’¸DP„.&)èC7#òµRKµ¨JöŸ‡&L?‘\r6c‘‹\0ˆ®aÜ‘¡xè5kÈ[×Ê¹ E>ˆAQ$)Ù¶…È)“¡\'€ÖD­‹È¦?ıÁHH’Jó\0oÛËÛh\r–Ÿ¶”{zÛn&J‡\ZÑE\'™Ü²Pü04%³åj¦ ÿõ·,5Er[“kV“!Çh«0ãø×œ«\0	„MxÇ.tÿ$IÃV·/ØzH¾èIÙ,Ø#@Ìˆ÷£©PêG7í}FZ<UJúÊekA‹|Ø²ß”œ ty|¾[T\n£(é\'YÌt»|E¹H^Ò´b9­´Qƒ‘=ò;p)ò¦6Ş`İå¼Åw!\rŠg‚D|.C@	Nj‚-eÏ ¢½üÑTHÖ’\nTµôŸW»Äù<tµ3(hc»DÕ>ëÑLÔ±à–h°À…%,öqgÿì)Öå“ü91X^¾ämnc,ŞqsSÃ,•õ6³|­Š æÔ%–sRWŒbÿ)Ûí.µ¡M~ğ,ÕÛp\nZ)èŒñ£_ùì8põñB}³¥-9{.¥ËkaËm´:ËS3¶~\0f|Öf/v£ÍmR$TãÚ‡²é`…[o]¦¥\ZZ”·#§5#Õë$Š¿+ìºS!ÁÔHÌ1Í¶¨.ëm¶AË´¾m«ÏÖê¢SÒ›ùˆ5«eÉø+…Ã›gW›akhËDÕ2å§6¥^ªjcjˆ4D5“zG¯%5®:\r7’øÎ7½´{­·\rU¨E¥Ô¨O\\óÙMØšºÛ‰ı\"îû>i‰‹™JÂ_Ot«G%ù.NÕà\\U«N<2ŒL®¥k‡ïê&-Ã?i7‹Äÿ©âÍõjX=ŞƒğŸS„)Ø`\n\Z´‰G|âi@xÂ³¡l‚)„Px9ÊQs4…áÙ\0yÆ…·|%*axÂ3¾ —ÁáÛ HÈ€®=$ÙúcBDÀ½\ZÑˆ(ôş÷Q E @|(L!˜Á!\n‘…NLâ	^XD!\nÑ	,$\"\\Ğ>\'´ îwŸ„?<ñ… L‚ „#„K@\n£˜A!Áˆ,<¢ùßÂôß‰Bˆq	aıAŒ`á	 &a!° ²€® °ïïîo2ğÁ¿ ˜ A†@Şğ2t\rÿBïôğ&/óL!AÇàğ¯ó\nÏñXO¯ò/ñL!õ\\ÁWO’0	•‰rïõO™h\0~`\nq€¯H¡÷|/\nøçø’o\0a:!@Œ¨ïù°€	ºÏ¼âPAû´ÀÆOûÊ/aA¡ şœoáú¶àÿ\0p¡\0ÄèVá³ °Àş$0#p¶€3ğ)0º€\r¹@NïáMÁØ@ØÀ|aóoğlğL°Ag|Ajqòl±ó6OônÑñ‡ñZP¢ğïaï	]¡õÿÚ\0÷0Ç\n¯°÷~`o\"!Â`	`>±%ñ ¾\0Aüä±û¼O´Ï¸K±B	4Ñùü¯%Ğ5Ñ9Ÿ\0\nPúpÍ1ÿ&#°œ/õoóoÿ:1öp\rÙPJædT Aç~:¯jqğ*O:Àæ@ğğT\0%Ï*gCo;¯ò~R(o(/\Z¥1	­sH 	‰€\n•raøçVÀš` ±¶à ığ/2ÖA¡æP8!ü®@üLÑb\0.»À™\0!±`ü0ş<°Óÿ±`+A©O©OS	01 a²@÷/·€Ğ\rñ!$K1ÈR0!SeFód\0Oç€mqnPğLtpò$q%çÀ…’ğb±ójQôvÓ‹±óˆ2ñnÁ([O	û@e ”Ò½±÷¦rFÂ±	a	¨o/1Q	ø’Vş2°3¹€ûäQÓÒÊs,áQüØ°\'02Ÿ€È0ÿ8ÙÓşÊpüÏ\nóo®ÑPr,Áá4Ó˜À¡<\0JfFeLP6Aó€1ißh4K\'ÕˆWSÿic±6}³ğb±7ïó„“–GqÏõdo\n£’ùç9Å‘ı\Zq\r\r;á¶àúaæñ,5¡Îréü¼ÏÁÈÏ<0ğ/ Ÿ`¼ ú8Ğş<ĞÁ€>ÇpI“0	“+·RI±˜oÁ AáësAĞA0ÈpdL´‚6TrSM&qÁPQÆdR6A‡;@v7Y9µğ*Gô¯snT	eàòçŠsz`*ş~É	Úo’ş\0q/	s@³\0ÁÁ,¥t§´æQHpaKÙ0$;ÿTŒÿúóN1;¡ ÿ \0ò.ío¡Ï?2ÿ3Å(>q#³ ùÒÏ>CÒö0@D\0V_EÀPõ•e¢dPR6SÑ5KÆD1(‚CK²$M6}1%\'ç[R&etHµÓ(“2	1G\\!)½±úGdÿ\' \0ÃÀ!ú—”úÆ°Aü¬`J_¡4á¡,Õ²µÏ´ <¿à/#pIïTŒÌÿôs;ğ.CÒËNÿ&ÁÖqWğ1¶A=ĞÖ³3³”º ûÂdŠ¾b¼Bæ‚ôÕ\0DàÆbeŞömqIt,€öUhfÿğÕ‚JÆB“\0¶4WQPóYp8‰³cÛÀ˜s¤’*#¡VÿÇdWÀ	°2\0™N2;¹ş€	6Á\n¤g_!u_Au9X»O®À¤÷rÖÑNõt#M	Ú3ÿñiò¡j€Â@õÔÿŒTZ}÷ôå²3\rá²O’lTª}ĞçFHåX^\"bÊ÷}ºbGjæ%Ôoàê6_ã÷‚,•4sñS[ĞôÏõšs*¡\0¦`¦\0\n,×	Ä`:ÃHû“ş˜à	|@AJµu«ô¹ û0Z¥V#0\0Ÿàı°;/òi!0ÿ8#ÿ½À–—(ÁZ8OÓõ\"¯/ıîÏwÁ@3åòú¾ÀlËó†¢(ŠFâã¬¤(ÀÀqqdIbÆ÷}\\b–H…TÚVn÷öpáÁ£\ZµCc’r,\'.\'|´÷àO¦@lèàÕ°<GPûxöÛğáQûDòK}÷i•!+¥/k!¦Ïy;2•4[£o…óô	È€øÿ‚ ¥æµ\r!a‡”,µºª”ŒL%Æê‰dDLĞL†%€#qVâ¬’Ì¬Æ×{½DŠÜ—Â—|ÁÄaŞræ6_ifUªaB î`šà°\0xx™@šÿ\rá’û”†}Wıp¤²âo\nnU\"a9œ`R–L;!O?„¡/OÅˆÚ¹Š OÔùÚõ’%03áõAµõ’!=i–øËoL,Üãä(ˆEå”—%–Ô‡N¢}ÄDÀl}Œ¸phD|+Jb Àá¬<2`ğ BağàvAô`‚á‚ajúhzvav¯@T·uaL!_)€‹¿˜s8Çcû &÷ŒùØ¹œ!Ä T˜‚‰ª•ñ’pÊıJ‘=»ÀFâc¢RÙ”aäÔçaHîPYp¼dKÄd£mY|÷ºŠÿn„\0Dz¤Åƒr\0t@t@¬€fqV‚5¡uõ Jagç1B	¡Á}ñ%F€—cÉX˜…æö}ßN–á´U€‹;”\r:ÀúÀU—º ¾È	\0èVoõ€.Ğ‚|:Å\0X¤•ÈG.M|Z¹[‰®;.>Øã®E%º©ÈFHe¬Xâ¯ÁÁ*¬AIR\0\rìà¡	°€6	¡t	!$±`Ø’›¹àÀ FAD\0¤FnÛvnGeaÒê¿¦{Y¹{#Ú–˜m™%a|ÀÎ$˜È7ÚB G‰Í7ÇNË´úKÃ“hLLb:\\¢F\\ˆz¹ÿ/Úˆ¹¤\0Dš2`<.@r@ÆKaöÒ™»y#33“Æ\0\nTà-(`Œ|,F-HI- %‹“|,Ü\"ÉÅg,nK¡ŒÜmê¡†aÆÈ	Š{Ş#´ª$®Ü=¸.pÜâ ¡íªªììÎ“m.ÄT€Xˆ\ZŠÊÊÈXXœVìA\\@”á¶g`;ùÒ÷RÓU¹²“AY¾/a²Ø£á.fì->‚É›|•j‹µ²8-ÖbÆ¾ÇÉ	ê½Ş£¶ªÊÊ³m-Æbˆà.­L¡\0ç-şæ>ú=L<×g¡ÆfLp\0Œì°M%ş»­›;8¾a–tD¤‹‚** ÿŠè€z`sÃPŒ¼`— O	sæ/ı k€T`‹\0&\0ÔG}{FÙÔb$Şı#² L`ö&Sf¬ r=-ÔÂ{ÚŠEÖ‚ªøæ¸yı´(Üä<œÒúìÒâ\0ÌÃQ¹ì¶J¹Ï¡7@åâMÙÚ*ê7şšZœ<( Ô \r~\0l!\0ı32“”şöïxƒài÷Ï†<İıÅ¤ÚêÅ”Ø2….¢êêˆ(³ …E’ÍÒFb ŠH¼hşì4.íŒŒ|Bê5ÎÛŞ¹C…ìbÉÛİ—_IÏ¿b&ÀÅ«bR@ša\nÀ	.rít+½sİ‡åõÄ`Nû.ÿ8bÆfìÒÙ7\0?7\n?à+ª6+¾HâğsşnjC>>ÅêŸ>ä:¸›~á»gÑş{ÍÓnâƒC•,­››ë À\Z´c\0æÀ$  ˆÛ™OÑ\rQ=P±Ï6ÊïÉ€\r,}ï/İŞ‘çsÙ?çq`ˆ’¿×ÄË z£ìzÃ|4-¶ÊBF’è\0j-vÃÓĞ¡¿à<Í£¿´¾Äû5üây#‰.^ú©ÿâ\':ãc€CşcÅí”B\0*ÀB \0âÖ hM-9„§–…ŒeÉÒiË#F‰˜ÄØtÅP¢G“ÈĞx6aÂÏö8‰òÀ¹ûV®œ·eK•4ÿc®;™ï@¾•9oªì™ÏÜæ‚š;Š4h¾}ó`2g ©©P©Nz®ê¾¨ó(@İçµ)Sç¸’m‰•¬T¨_×Âäú”kÕ«fË–¥Z•íÕ¹t¯~+\0îÂ€2Œ¢Ï­7Ñv<uèáÂ,XbI”¥ádÊ8Ö¸¤h¦eHŸi2¥ê“û­Î©ó€Ğ	BeÛÎGô$Q¤dã&ºAoáÂÏ¡ƒ)]TåË­\Z`]/Ù½èîêµ;Õ8ÕãV“Óå>ûTæ|É??/Ş\0=ôßÏÿ%\0n@a&„puD4 ‡x\nH\'YÑtòD€[rˆ\r.âH$¤¨\0Òÿh#•„!K®Å¤Ò9’åaˆç˜ÃaM1““PGÉ†‡ë…\0ŒE	gNvÒwUzç…‡r|í%W_l±ÅÜ]8‚×ãx>&©’Ï1·”ĞåØ£”ÏÑó=ƒ\n®|iF,ƒÀĞÄAKğÀÃ*…0ÈCÍĞDœN4á„(P4Ò.<CÁI|¶v\0&Í„’KR0O‡\'qHâ:vxÓME±8©M:#qæ pd“ã}\n§¢™ã\\P:×¤‘ãA¥{ë1ù${ÊŠã«è)÷\r:¯ÒS\0 ß5Hãå—®Ä’É@Ì9g~Äé£Œ\"\nµ¢ÀPJ)4\"CCˆÀçÿ3á^uÀ3nXîÓ)â9ºÔaYñ†XÛŠ7¹äèŠ˜nzÔyÚÊŞ§JÒ\Z°•}\\À8æ8U\nƒÚ×”£:À\\½‚c5\\“A5* Ã*¼A+0À Š*¶\0ˆpKƒ/lôÑ‡®ĞÀ†)¾H\"‰o@ÁğÌT’µçIó˜«ÖUeQÕ£…¢åÒ9-Å4©mª5Ôo»=Gd’ê]µŞTiG5ğÂV÷ã”U™½j{O—¶Äu»öz}Ÿm«ÃÊñ\nß\0_s¨ &\"Tp\r=üğÃä2\\Î³\n\"	.\"ˆK,#TóÆƒ`0UŸ}*5ÿÓ-m…uwI7e5rY©Õ”¢¾¥d©Q4âÖ/©\0ãj\0ÅnÛ¼_¤Ì0ÄÅì¶ñ±JªñP:lkÃPc8ÈÒˆğÍ_Õ‡5¸¡ˆ\n@{şyè¤bú òç2.™¢tÒ}0ˆ(‘¥.ÿ©Ä\\U3W‡ü\'\"”t-\'ŒÒWoøU£ç*³:Ş<EÁˆí-ÓaÏ\\Lå6)epVÌ¡X“F±oØ*oº*á´d¸T` ˆF2a\r8$\Z<Œ†ü~ÄAdB\Z™(b&nXDìïhçĞ__ì2À&ÂkŠíBQˆ`r¢Hê$BY‰\Z8¤ğÆ.á¡àÜœóÄ3²ÍHNšÊÿ2Ê2756„¥âŠx&°á…0G;[486€Pƒ\Z¨€4€AT pØÆ‹XD2¹F$[€ÄL\ZñÑ@š\'é2—&¶¤$%¢I¡N©E§ÉkQ\\£ÔŠlƒÙï \nÿ6ê¹‡myÔàÚì6Âå¡1<İ]¤—$zø—;[1¦DC0 †-yCPƒ‘p€Ã°q#Šsœ™ ÆtñMlx³“©Ó V¨&/x™(^‹ª	¢°SÖ†R5–±)£˜ãÄ¬RóôØÁş}…n+(ª¶#b&oJ(”ØÚ˜CŒU€{\0Ç#±YHjä0‡İìæ\0ÀÿùÍ\\\0æ¤†7[ª‹nrRi4íŸ›Ø!‚Öó”Yd`êò™(°Õ‹&*ÂÔnøÅ)­í¢î’BŸº0Š6Om\nXöFBì)õ*}4\0. @B’7„Ü	à\rh ie$75PR¸j€›Ş<.à0ş=õ}r‰ZEµÿ`&òt\ZW^²¡¤H\'.YFP&0Ë€G/edw ·´Då\0Æ$Ï©èQÆ&³ŒM1ãq|”Á¹¬çƒ¢%áyüÆBÀõ*ƒ$ä ÁQ&Ùğo³1WF4#õmè\nptõGkTË‰Îå!ä *õÌ\Zµ¸1e‹E9ŠïÌ!ÿZšË<Ğ;•y™*©ªw°R#¬°J«Q\rbƒ£|rXÛÚæÖš\"…Ã	²ÁV\0øÿ%0I³qkü·›ØÀF\nàÄƒ5±uvyÔt°¸.KYq^;iåŒö©Ø¢njlhŒÁv´şá½M^sNü°^b4{Ë<ÕöhKH\"‚´œjU+oÕjW\0\'¸¤DkI•HS•ì¯*zJj²¢VYÇ%;Ùit¹˜JíÖÆCúâÍ;À‹\0ı9”¾{t±Œ?eBéå*—M2¡‰\ræf63b‹à•r‰g@\ZÀb)5‰MiQşıí€…ŒàlÀ\'À†.t¡NFƒS‰úKZ„éRéxfÿg:êº®†˜E¯u×#rå,“Ê¼Jì½°ŠsŞœ©ÌÎÍyq¾ ]üÖ#ë½Ïºv¦Õc¼\\À\Z´‡5‰ìLÀ®ûo€yËÛ!¹ˆ,U662ÑÇ&«JÂYI(oİ|¾ËŠ·Ã¢»D\\wQª7¾›%TóüîƒéŞñ–w/U¬¼çõOÍsÖwÃ8úç!VrÒ_&\"]>8(\Z­\ZH0ìèGK\\™xô Œ.qõ¥hISWÓE)îÔ6>)¬l¼A)”Ë©Dñ†7„ãr%™°„Ïsó©ÒÌëç­\'Ø\\-›‘9gŠ	=ÏÄ«yøò<ô¢½BÇXş\"\rk(º¶ÿC4¢G©¡hFXÀşÅF6\ZÜh]œÕÑDÖşŠGy½ÉR“·C¤¨¬­Ä€Xæâ2R2ËàôÆ›zyT‹G<†Ò¥EÿõÎóí°¿ôüa4_3©¤M‹õ;m›5å]¼°Ã\ZÖdGÁADdK£›/iéF£ş-ˆé£á@qv>ãÿ$Æ¯’´ng\rDYÓİWCÔKíË¨Hqxà\rµa´×½îË/_y^aş×Ø«|Wm~$ ß\ZÏ}ôÏŸNU,şyğ!® o[ÖBfb¥§vƒ»Ó»~â¯=¶Ù^é®^…l	l”áh5&XK*H!¼Aò ÿå€j~±LÏS\0Ï×0z¶yÕ0¨ĞD}øTCW!ø]•xôöL˜·6X_e~ƒN™€0¨l°—	-`ƒGçDq†ƒºĞz88 \ZûóIs4E³?ˆâ\'&qa«|À·Â61²Áñ#†\0òà\ròÀ€œ’+\'ôwò¥jã)³sÄót˜QOb<½&%«¶PÕ÷<d+ut¸¢+èP‚zœg\ræ„\rF\rÒ`[½5NgN8H\rNEÄƒWD@\0ßÀ?ú£*ó6¤ö!íbe‹\"#‚nEYadT[så +kèNƒãZzØBG‡%’çtwæB>§ÿBÊCs‹÷w‰g1Ze<;7t ¨g-„_%up€cü¥Rp\02¨‹¸ƒĞX{<iœ´\'EØmH„4®¡.!Gj#§\Z3Â\"CÁE#r…³Ä€|W7äUoµRi£UĞôKôS¥bÊ!8|c“K½B\rĞy*%HÆRR§0(Dä$N<¨l?8N¸?\"W§²Ó%…@Tt)ç/\"fJKÃ—…Yè€MµÏ‡+X‰w‹³x‚L§gŠgoşæx-¹úÖLÈU®¨g°HÜ¤RTG\rq\r×\05hƒ7hD¹ƒ×ù`GXi¼d„V£\Z^Q5„Œ…ÿ*‘\Z,rwßETê^ZØr°¼”Úˆ<‡ç0Ï4—!X—w9}ù¸o\"HoƒGUu©gÄŒdNÏ8H™`ë2?§“¹Ğ”7èƒ×z68™S‰?GCP|‚S|@éB°±‰/_©û)¢)#v€b$]˜\0PÈğ–\Z$*ó\0ZøH}X¢Òw‚ÑÔs]å“q)0U‹/~}=‰Ç+Ñ`Çe\r(5HàMàğ1\ZÀ˜òƒôƒMÉ”5È”’YƒJÄÙQ@L‘‘ƒÕ•ävO¶s]’’`³&¡nÀÃ§yšÆ‡’ò4ÆBVE}½H}\ZC ¼~úWyÖçÿoBòw³6ª<¨ \ZÃ+ú+Ç¸R‚Dlxcvğ3¹ğ¢!ú˜II‘Ù”±·üãqH“‘GS.@wQOùä‰8rÙõ@ûÂE*BÄárÈ€F>²iE<¹R¡\Z˜†NG y™yyiLÍCAäZræÄÃ›K\nòÁy…\rÀÂ= ,-\0?¥c:¥3?@D?mj?YDĞG–¶iJæq \'O/Uf5$\"T©Tr‘Ò]bô£›’€XZÈ€P°„E;¥ÕpcY}£LXÂ¥Ê4z†¡³v0àÁ6ÆYcí‘Lk˜©•©\Z¨ƒÀ\"‚!¦ \0³jÿ¦Ad«¶ºÛ9•EÄN4:m©5N‘;¤¹(ñ™EÂ@/’^¤pçEø9¨)_v|‰š¨É÷KzãNøª1Ñà­áú¤\Z#ŞjzIUSÅ‹ãW“;Ç+*D À…ÁyaŠV†S>I	M™«%šİ	™şÚ™5¸\\¿º|å\ZT£©5H;µXzJTü\"P¹!#4Â€]ˆ\0gdUõ×­ê\0®êĞ+à*®İês5	˜à· fv0‡7‡ãÁœó7Û¥ôšCÃf\r×Ày\0JYƒ™Ù6™9••i™fSëBPY‘iQe¤é!_ŠÅOP–]öÿ…®™…òp­È^—öT4~İú\r\r$+Ml+M(«1”8·GC Kwt¸PSxLP\"t°ljûtwR§hÑéu\0&uI¹pówNí×ƒg?÷˜>¨Ò@1–F§µ—{[{š¶!4ZJ[ô@¶E,Ò£R¸ræà\rµTŞp­1w;d1¥ò(® MÀ¶<„&+·Õ 1#P“Ú²f‹®¿Vy8·AY\0$«¹%\0 ÃV¿•”\rÂWgµlD¦z°ÇR4(•N	®˜†4L£4JP4Š^Á6Ó±¥ù£N“(?A¿A,Ç…ˆš\0b‹Ô: r“ŞÊC¾â+ƒ@ÀÑ\0ÿ·ÒT\0Õ ¸7+t;G]e¼	µ—m8¡ÈK ê  \r}x\rá½×p½×\0Ñ\0A[>†Û[le¸œG`FzwDú×?S3:¿Ï%ERa`´O}Š)\0eT‘Õrò\0.wÄGÌv5¼ \n<À‡dI‚Fp‘4\" 0·Yütàƒ³É«?˜hoÁxtC§ÅoËÁàÖ \0á´AKÂ\'üÆ%œ”sLÇ$\\Ç\\ç_ÕøhÆ™Ë°äëD\'‘ÿƒœhwX@ç¶4r.»Y»°[º÷…|;xø½â+‚†Æ–$?À+® \nÜ	Ü¢šoÃ‹4å’{Rä«²i³ÿf_¨‡áÓ­¾Â1Z‚ø0«\'ìËàËÀÆÁLÌ¿lÌáÕ[¯İ ‡³‰=\\Ñ–ŸX´S’µ\"Ù%FAq¨[Øº´¤…åğå`ÎbkÎQ[¾Q5Nt1ßJC‘ƒ’T«ƒ0Š `\'’£\n‹\0@Ğ\" 1à# ¼”XĞâ¢Ğû£q{ÂWšI„W!Ğ”9Üã1³*,ÅÜÆ¾ÂÁÇ¾ÒoLÂ!Ì[pğ`]UĞ¸GEãÒ%¡™¬|ìœ]aP²D+Q\ZBÜ]AÊ€übŠÈ`Ä	@Ô	ğ:/ÁŸ(MÇ»?”Hƒ`ƒ‰„Õ0¡ü2Ğ¤ĞsĞÿP°qXà40äZÖ/í\'\'Ñ\'®al!.İsïü¶–DHÃ6«\rÌÌ&Ìqœ”AkÂ%ÜÑy×rÌ[’˜û×\Zƒ•.H#.(%7µ—<Å/%½ºÈ»GL#\'É€	PEMÔê¬Yd1ï’THŠ¤cOMÕ˜ !p9® 4 iĞ¶°<\0| 	z© Š 1PĞF“%¡4JÆ\'ŸúÎL\0ì`\"¥Æ%ÍÑ…]Ø$-Ø|-Çv,ØâÍÑ%=\0)İUÆDPHØNTÓç\0w/¥°DÀãEY;#\rÈ±`¦±[hÄ¥a¡ú±z¦ÿØ„[éIà\nÊ*0®P2™Ã=ª°~ À¯ ³ÉPO\0	+.LT.cÉW+Ño¡hló\ZØ{}Â…ÍÑß\rÂ\r2II³ÆzMãÄ,\0ö€»äúD›æmLÈW3µK/ür)¹ñ@`s±‡º±»¡¨ak­æ|5ZeåRÍK\0e%I­-?Ñ §TM®sĞéƒ	Ğ? t <€w¨0\0Â»Ğ	?@!{2–&1XÛ\Zü3—!5\0ó\ZÇÜ=ã ]Òs<«s\\Ğ™*Å¡Ñ¡Ç½óİrüËBN¡:XE€²©Ÿ¾‘œ-ÿK<)ÂQK³~’a;Ôû»±ù»¿¥MÔã\0¼ÃÃ˜aÆ†4C>¼e=˜àà*à*`Ÿ£lj°.€€p:À»ğ\0ĞçÉğ»À	Ç}4„r\0wwÓ)Ñ0ÑÀğâø°Ñ‘^ŞoÌ\ZMéÃ6\09zG\r0>\'hå[³ÚË„=êÍ<¾SZ,á©1–»Sw¯ôO“rj	øOÀÃßb+¶¤í€²ùrOÚ	0ÀÎJ\Z\"´kà¸l¹UH<¤1Õ€æÎŞ8˜0ES\0 jPk€h GpzÉ ~îµP @¢„é\Z_^80ÎÆ?^ã»ÌÿİÕ[MıŞÌhÕ½Á‡”x=T<0l„½à}Â»<\0öÀCÎX(OVJ(‘ñ©\"Âwjõk´QÎù‹Î‰JKµÄ…æ\\î\0îPã`Jrh£1Œìà¼KóPÕ¸Ğ8Ÿó\"àà)Ği°t°í€ŸàIŸÎğ„°€[L4¸÷?I£17¦ï‘é€]Ò³\ZHÙP$hl»C=ô\Z‡	˜`ÁáƒŞ`s0Øíé <ÌÑKlÅ&»6R«¾ödJºQwôšû’û»ZHÔb‹|²™–’üëÀûPe3á¼d ñZ\0rÊà\01b„˜,Aÿ¡À7sRô‘E‡-:øè9•L£³Z€šÜ\ZQ@¤H ``^Ê’ìñ f\0šğÅÄgSÀN×fî¼©BÏD~#H &Ñªˆ6¨š…‘C¿X¶ÌÛfsªUz_†˜;¯ùÌÀï‚=vêHÎSIáÀ|ùæÖ=€`Â„|Ìåûkn‚9Ş–\'¯0‚ºæ?–— ²dyå[N`Ü8Íãì®;pN´èu¢U¤7²€:Ö\\‹Œ¶PR¤¿°€	YYiTÑá‘ÇÎ§]³‚½z•J‰(SP:-ğôèHàWS{Í™®y/ËoÀ€mÑ-{†ë™\nÿ°U\0\'mĞ hÑ*H“\rƒPjä	ÌE™n¸‘¥šLúãi\'íÂÙ	ŸÀiëzN:ÇœĞ\0£Ë5<@CÀ6$LCÂÊ	±CÇŞñ¦Ä+Ç²p±Ew4Ól®s@-4\r8Ç\0t|”P$·¢a­\0bƒJ:Fz\n“9\\écßvÀã‹f)N@h)E’jô«†>*Ó5ğ³.ƒï¼ã»˜d°\'©cÌÀ„\ršzŠ¾§ª¹ ºLê›o¾/1À@š\n*ÈÍh8€b¦Dšş¬Ù)œk²S°Áìq	mìp.MõËTSE|ÇœKÔy›•Å[ô¦²ÿ(‡³ÌòÙ§Ã|Îö®¹ækÂ	K* 5ÕFB´€DÂ@$\n|rU€ #@6ù$˜]øPÂ–>D8´\Zu1x¯j.€÷\rl³&Ÿjºà,4\0g¨nyFOt1P·\Z‚1pjL‘F0x‚@˜à/Ûí€oğ¦3p!kàà©Í²ø´-zæ9\0´º–éK°¾„mù¯Â“µ°¼h•ç0ÆX¬,²w,KlFÎ6óLØSíB:´O2`Â…œ}V5ıêK²\0QäÉ`Pe‰B’˜å>îğC6ÒUw¾1ß€¬5Ù¤©¬5Ë’É;~¶ùo™cØÀ†GÀ` ‡+ˆª¾ÿDÇ¤\02¥©oê‚ÉäLÁYhnn‰æ›A¨¡Æ\Zkî­»;™øµ\0”“æ0VÁ<\\¦f¿\nÓùuY{=1^ËIQ13›‘ÆSç²q´s”çÇOB‡¤Û%À¹æ£’\n)\\²‡üP\"	>Rù#UdÀ¥Ğ0çs—\Z80=k»z·{“di¾y3Ø`—ğ§\"hü¨ğ»¿Fû—Š|* úØÇ@ÖÈ@¤Ä£˜ø†$Ş@ldJ}Â;Ğ1\ZU­êT©‘†–BÉNDºjQ\nQx;^%À.RÕïæB¥¡¦$\'¹X’Ç,Õ¨£q“VúĞUøv ”ÿà`œÍP…zOä2PA™€nöª[Ü¬G-ÃÈP/,ğ¥ÇlòÜ¢¢ñ†J5JQ„\"Üø\nEkTàÔ`\\7˜†Ä¢sè{Ûñ¡\r†fCs¡]JÕ!˜(0~	¡¬rõºŸáêE¶+Gz—™F–j)]Î,¸4Í†L#I³D©IlZ»^4A½P–‹ı\\Àmu»W İÌPƒ\0èğ\"0\\Á†%©+*C\\4ğc©€\0F±D°÷Nï	“š¬aiÒã²ÀE4¾y8å\'€=L2¼a}P‘†¤Ëª^ÉÙå*gZÏ*£ÉÿÌĞhŸÀ³Ğ=A£4¦Áåx\'1É\r!:$N*\"`ÃT±ƒ%,~°\\ñÍBñR(™€CÓ—>Ñ©Ï\'Àá—\0WÜBB8ˆ´¢1‚oz•’4àfÓR¸€¢æcj0,\Z±Á ªi\rjHB\rèÅp!ó©)-•	x„PàåÓTËpä>	ÃHÄ¤HV–‘7`„»Ä¤(“™áŒ‡`\"²\n¯¡>b\ZDqX’åAÅ°¸(\rz \n[,Á;ÅÎ&\r6Î§jg‚7šÌ&\Z\06¶(\rH™a}hƒ\nÜ Î‚!ìK?¦».°Æùd¢xÇÀá1Æåÿ/\Z™È\0¦¤BÓexå@‹<ã¦Áá…4\\UªBèÈXy£’·k\nËÑ*Ãìj“	h¤ˆbÈ¡\nÏ4ç\rì`Ú´¨iC\"v@‡ìÁé,”ÿ4Å*Æä;kzÛ ¹¼Ï\0æX†+L¤³pEÍ¯ÿúƒ\rü¼K\ZnÄíƒî“TuÀ)»> %V˜BC_v²£¾™ØãdÃËÑ!AÉ­ã“\'¬+el¥Üæg-\Zxıò!£Åğ…£Vhæ!\Zd¡$Y¥4e³xHÑh|@=Hƒ\Z$ûÍúĞ] óoO8{ÁïÄ0+Vá\nW°‡)ÕB—´ÜS8EÙ>ÿÔˆÍŸ*U{¬ÅÏ„ËòJIT ¦\"Ç0Ø (j¥³XñvÂ1Híc4/,`Â\"U0€©kvyÆ\"¿`2“DË2d4©\nÆ:br\r‹t”òG©iZa\r[<ÉŠˆjm¹ÏùÑYd1h4—n°\'³©O5Šr1İÑÈ—=¾1i(mñêóƒªÑm†¡K ÆN@\0C\ZÑév”;“p¸ø4ì‘NUŞC²C76‘a~–³Vq×$2Ç;*Ù¢qÜuCş±‰€÷IEÂxx*yF){T<À2í×µa‰Â°3Ê|tÛlNlbl’mã:ÖDÿŠœÄ7DÇ9±YÆl\n°çwGg€4–	iˆ\Zö †»ÆƒÔhä*{ÂÀÃpÅ7öoœÔDå+·‰:\0©æv¨¼‹ÌÇU\'qÕåÎ0\'z]	Wè+Æ€20âı´Êl‹\0YûàQ“9Î4z)jnq‹sæ£(ø\\\0¾ì²CWfkÄ”Nã<2úÖ6+¤\Z”«ß¢#\r”äK Ë\0\nğÃGéL•`‚oN·5ÔÑm¼Ö:@4Xg2Hdåˆ4WkÂI«Á(gŒi•ê4´ğ^u†1oáİ3$¢™c¡æ^øokş(‡Q#Ò™ÿĞT¾ë¼óŠÚÙ	¦H¶“y$€\rZƒ\"l¿spàKÏX‘ˆ3¨†ë €eÀ8#:i<pè³Ú˜„ëÀ‡q0ƒeø†3qÀñh)á³xX2Cê4ÑoX>åƒ$å¨Yy‡çÛ®\\¸Ş™‘Å ŒäLRá¾²$ã‘QJ/ãA¥j–ÛÈœúp\rEAX±ø‰ñ	k¸0p8‡eĞ_`è)[ˆh`Š…P‘ø’şÈ\0{8†\\\"”F;ë¶©\0Å1À„X°kÀ‡v€†gĞ:iØ†KÁÍAz–á‘±|‘„š¹At‘Â\0š\\š\ZšÍÿ¸>¿ˆz‡¾˜M»;P²‹É‡,ÄÓP	(3%SL/T>³|€	Í’EppP`E Hˆ98·hP¢0€i		ÿ«~(\0h˜€Šv±ì³µĞ`\n€:±Àzh\03Xiú=[¤—YÌ EêAñ\Z²O4ŒÂ¨xGÈ\0(]©w‡J$š¸+¡»!}\\At¬§|²1àA–±+%û«&[E(suˆÆWÄ‡›ˆÈˆ”E|À”À×8‡=€Hè€^C—¡\n \0\0Ü¶	Âá\0úùFwÉ-İ\n7Â±‡Û{\nˆ[´w€\0W Àÿò@œh•Ë‰›\0vx±²ò‹ï¯K¸Ãà¾Vq‘š•\Zw¨¾J„$ÄDé«»¸ãAÁ4a9»Ò(ÄĞ¬ƒ¼8²óÁsxÔ	`phÃñà‡‰´Å¶Ù‰¶q×8€^¨Èš%ŸA(\0¥pŠo €0i:{8\0­úÆ÷È-¤³‡\04˜€X¨€õÈŒœ‡vHlÚ³&ä‰¢\\¹Ó¬ài®½ò>¶c$\r™Âğ±ÜIŒ§”o¸b¸¡²Å°ÁzàDN,Œ–™™Á°™—é cg8\0\Z‚”} !¿;€}Á²ÂNÂ‹Ëhp	WŒ½jJ µ€Ì¬”op\ZPÿ`†!Iˆ‰ªx\n(‰÷(œ	P„¦„X¡€2\nØÁ©ğè…`´¨ ¦ş\"€‚ÄÎ{J»I¤°œ™R[‘Ä€>[š‚*(ºÎÚNy@†×!N¿ğ†¾Ğ´ğ2•V3«²J2º°´½»Á8ëœ(ØP‡#q½w±¶\0~€Î‡<¡Iğ\0\\ ˆq¦j°=à©Œ6ªŸ‹ ø­¨ €X\0PÈÉ€\n8\03P†³7xƒ\n\03Ğ§ïPÆyˆÑ„b…¡O»AÄ ŒÌ ÑÛä1¹«Íh!YÁ™YŒØyA@ÎF¤]Ç¼x¡€”ĞOB$y™–ÿQ¤Rr\Z‘hÈeø¨K{·óT‚\\Ò)2‰GQˆù@’Ùø¢P”Ü\"œhà¢Ü˜Àƒ ±#ep…Byiğœw26{\0pHÊ²\"Œ	Ø´×Yµr[Ñ±š«õMM\n¨ì’«œ3Œ’A…ÛV‘ì>ïó!2½’>O\nEòó‘Ô€!ÙQ¹„O½Lİ²\rL³æÙœnkœÛxŠÆ\n¤“Ø°u €Ğ£\0Í±ò`†ö\0Ç˜\nåò:{P•(DC/éÛ§TZ9Œ©$¸n•¬Dµ†S‘­ÚTš™ÖaÙ‰C•E:Ä:M;éB°SÿyËg`	c–Â2“êhCë †D¡­Ë\\È…Bù-jh”A¨¬ùÈ4ò© †	©t\n4(œŒŠ\\-•\n°aõ‰ÈXx†`‘à¹\'3–ˆ‹•Õ˜İõÎ¨yÔ¿}V×©™:…¹êDæóXÕYQ²Aí›‹g8ëDEˆÊTÖ˜WºŒÆ84Ø5lªQÿX Ä’ŠÄLØ	h\nøø\"3H€b`ƒØø€¨²˜ğ	ab1Œ=s<–»Ø§ï£Ó|€J¿XYÚœÛÌPj8¢¹¹¢±ŒÌMÄP‘^Å •™	íí¾¹¸>ê²Ö9WA«CHÿw5‰Ô¸\rÖH?×˜Ëµğ×k“\\È„L\0‡D‰¢V-œÍiXuxK:Lğ†bğjh]¨@\'´X°•†šÎeÍW»‘¹x®í…¤˜iYÙÔê»+É°ÊƒƒDë\"ÌMºª‹•p\ráİÇé\"¡´JÁğ‘ò²W+>äA‘@·¸‡ÕÀ\\P‡¹¼L‰‡0áÎ¹ÌËìP¡Õø8“æ=(ˆj¸˜hP)&Óü:°c™\0•eY²±Z²)ğû¾\nQ×„:! 1YHÊ™ÁÊëÚ[«Ä.íº]ÉyŒš‰Å^šmÄ¸WBŞGì\r(;z 5t(,ÌÿU0b£Œ?˜‚©ñ Ë7D·)6î„.rŠq;£ù“‰YL‰l‹ˆâç$•²Â}ªa@¥Mİ¬«ËpÁìÚ•ÍH¡ÌĞMğÂ.Iì?FŒÅğ\'d˜¤™¥\\AÁ:å4â=T¿P»¼@€òÍ‹æ<–UD1‰åI<×€{@eŸØ‰´Vs¾ÈØã‡øàTAËÎÙ‰ÎYĞA9Îâ‰O	àÀrË{ƒ1”`MìDDGb$¹[	Q”$\\±ñĞ¢‘½•ŒÌÈ¤ÈxDd\0æÈ@\0dĞYá•gÍMÚ1\\Ù™Ù­»ÂN¸E™“°ÔÀjjH—hBs6çN‰?L!‹€)8ÿP‹ñ¸ßÚ‹\Z>°;Í±àµè\"–²À\n·MéÈÕ‘æÅXQ?¦È5‡¦^áwğ¡QÙ»ªèLÚÂÓ‰–G’…+«V>Ëğ±·’Í­¤±–	Ğ8c&s×V!Ëë˜¢)Ê5Q“X©Î:‹Ã¶—M©¢ÿ‚?ÀO]‹hlåõm¨X£`”©N†Ê%#–6Ñ–ÑhÅŒIÌ®®>5¾-¨†è³f¡‚šŠ¨eFÜÅpÙ’Î^®Ô`”î `‹±S‰F¾	¹¨ñ6vÒ”‚\rĞ\088Ğ€l€îÃ¾İÛ·,:l¨¼ş$b$‰¨eI¯” H\n˜‡ŒãsÿDßâ»™Ô‹}j«×ŞMÄ}Á‡¦‘jõùöĞFÕÎ$ŒÆ®°+`Î•ü.aII”+o@pd¶ÁÖ9€O•óõí;ã1	tH\rÖX LhÈ…\\Øpû½_]ñ3;æÖ€Ğ€8ÆŞÿÒ}€ñ,&ü báæáKyËáq¦‘\\¸(»S_ºÑPf^qfÕ¦ï¨ï\'?íªoÖÆ<­ño‹Vz+¹Â˜=æµÒâ ôM‰ë¸o6‰—WÒm”„§İğ:·ó\rÏ„lğÄ¶î³ˆî³H Ê;#œj	n¦¦	Úô\"ïSh\nÿõ^Ú^‘¿\ri¬Œ&ßH\0\'Ïô¾MYßôÓ‡òqpo±aá•ñ§€r¾©¾¾˜Á>ãÓ¨\\Srdô{(òÕ7àu†áõ7Ğ€=×Ã¾†@‡nÁ†îlÀäX”× ‰ôÚK#¥„TtËì¨N’–‡¾°·²h®¶Ö(×tMuÔ¶ïs§hÂ²şoO†ŞG\0w•Ì´Z¤G†¾ñ!4€³\rŠr¯Ÿšs6ª†Ï…_¯#Ğbg)ï8öc¿†dŸøÀ.ñ!•Z)ÎœçaHæx¿˜	çøôhk–®Cµ+«Ty\'W€–oùL\'wL÷jıĞ‚Òt¼êtÿ—ïSçÖhgvÙŞ4\"ƒQÍfin¾µ‚ä¨¹9B™sª}ƒpƒLîª÷çÆúîGq‡ƒ3‹—L\0¢ÀÊ!Á,ïN/·ñ5NàäöLš«\ná~y0ƒ&gyN×ûqÏ\'Çù¿¿yùn¸tèŠh$ÿèyéygÙ¬Ë²‚åFG	Ycš‡š	I…ÀõŸzÚ¨Ï…ëÎ†éş¯lXî×…¯×\0WıåVîùz±÷æO¥å¹Ô‹ùîØ÷˜\0´’ÙÂ(QÚÆh˜hÃ—H$8ş–\'‡ã\'‡Í`~ägy˜Çtéy?mòÎğÓêKíPë{/Q»Œeæ4¼ÿ˜SXC™QêñôUHDG\r€ªy˜AHñ@?j\rğ¥´P±Ç€\r×u€ˆVmP.™2ÁIçB…>ŒX`¢‡ 0ïÜ—!@0!¤¼&å¡4i²œÊr,]Ês©`¦„™äfâÌ‰ÓLfÆ)09ÓÏ >Ç%ài©Ê¦Èš&(÷4·rŞy»ê-dÖ@.[fnÙ²Ÿy|vî™gn+Â÷…oí~+÷[Ş‰ÑşLPZi†\rRKœ)×›joz#B®É\"b9Î•KW]œ£Mì[mtµ¿‘«yna„LĞ>Ã6,2dj›Ä\rUeVªU½-ÿP@î&¹ÃoÚ<.\\Á8 Ío>3Ásç*™Bu5êK˜å”“‡À\\Hò^ó™3‡ŞcÇ|ÏmŒ¿ñœyõå×7€N¿\\zöu‘€ú7VP\"Ğ7\"P Èl°!¡\"*`bÙ#ÒÂ ğ#4boŒĞB™´\0Â†#P@ÁG_y…@Sí¦L(¡ä’qÅıxqC—9ĞáÔLÎUÇTsÔAÙÒ8/q÷Uà! Şwå7AzîydÎ`æs™ØGß<öİ—Ÿ\\lÆU\0=	h€sR„ÑDê€:JS€\nšB¡+2ôÑG\n®¸Rás¨ Â†ÿ\rRÍˆ’iA‘„(¾A#d42#©dãnH!ÅRIÍÅ”€q\n,`\\­Åyë­ WärÄ%™uHT”Ke·’7&ueeR>Ğš5G°Ö>g¶u€Zô™9ôÉUnEEt®ºûé5 :\0á Š°!$?ôPJ?ô!ƒ¿4¡ˆoDFpÁœ6öFc’~pÙ!™L0Çm¸éÖÓ±Q59N­ƒ9ìÊkMÀÒ¤Ó’Jb·›Ë	”tRL\\!°—`‰¹zÖV{€µf‚ûÌ>\r¦›æÆE—]é¢›—ºr]ZŸ!VM¼˜t@¾ˆÀ€ˆ×ÿP $(ñÁ‹\n+Ë‹n¸ËdXösoà €€ƒµåS965S72á?~­‹/§“Ì=>sI]÷²Ë5##UU5{3ä}ä‘Yj`€·àz‹VEkUÄVër-—u°ô·×Î×€TGÓ`ißà¢‚)Z7‚@8áˆS@I%CH‚CÛnLÆ¶õ’x ‰ö’ıU”P&Ì=Ä¶éÆ“RK¾Àà†dqÛ¤²’Gõ4ùÆ–³T›K6nå^±ÙxÄ¤­”…=fÉÖZ²e\0Ö•>l!ÒjGÚYP\"wÙà\\*‚½ô¥/ğò`&¡ÿJ\0NhÂá1@¡•Ä\r¨§=¸‡ĞÛŞÑC!\0±•¨Â÷n°E ïo/cRàg8ùÍ$851’Ê–D´Œr-œlC³Ö$Ñ‚V´Æ-ö¼§#)·öQ:Õ©åu¼`Íe»¦í.j\ZÖ\Z<Gxa3ƒ%ªàô°{>b÷h€‚E\nAˆm Bl@*TÁH¼˜ËD,“t#N´sr²*jÌ(=ÁNP–’E*q‡*òÀJÍ²$óˆãAÀ´ Ëˆ‰|Z¶5ÒÍ#jZ¦éà„´9ÁÅ™ûDô”GI.sA#1ƒ\ZˆÿÁD¨„PĞ†sF`ç¬\rÚIƒ6çŒgÀæp#Mi•\Z«\\D¹\0Â¯pÂ	NpÀ«*å939”Xµ1–ğæF;ÚŠÍ(ŠK°Ğè+æğÆŒÚƒFÒ©.1]EÆõÀ Õ1¥HCWh$ kFC˜ĞZ€…64\"\nRˆg<I°SVT@\n„ĞGÕæ6Yú›“°èĞr81W¤ï,OjÌI,ÁK\\+˜±Ä+(AÀ;viVõàrLé1`GÖ‘F4íc[û8æÜŸl•®>ğ9©†&—ı°	šG#—4©™\'u9OÖ¼æBğƒD¡$AÑ ¸âQÿPH¼b1¥ÚrUKè8bâ±÷l~¶eıt\"qD.«ÆjåË\\ò”’ÌŒ*4ó.ÉãÛò¨§€ÀôÈzğŠ-l=-@{ÏFâBÁV„Mv,:Í\nÚÅš¬èÔ®<9/ÌnI}Ö‘‘€d$Ï\n‰å¶Sˆêd¢ŒG|S?R\"\'ÈQR~±ê$d%À1kÉXmÃ#™C<ë\r£yÖ#&ôäƒ,e\ZÓ{ÜèÍ#s}ÆšÜØ6­É>lÚë9æJÒÊÕ\\Ó„‹u©‰XÏN\\t,ïÔ«^ôÍ2K:v%²˜ÑëÔ×pñ@mjy%²^Ñï~¬j%vê²˜àVŞÿ•lÖ+ZÁu0^ÕCa\rÍhº+¶ĞG·©¹ÛêëZWµ$ºåZ\ZìÒ¥Áh6PÆöéˆHĞ’$Ö’$O)–Có³­t”µ¢•oRP×.‰9“û¤;\\¦£ÜôâÙœX)ºËµòRLZK„Ùúå`¾õZ#\ZGÌÄaÓyXMq^]¸²uL38×\\1ÒĞÑ—;Ãe-®‹K´öá%õ.8ÁYz™W‚”øæj8ºÒ­†sÊÖJZÒÆr2?U³ÛœÄ–b­Qzj4-^\'Ó[½üe7Š™=óÚ¸àsÁdŠ«¯~ÍOŒØÜÌ¸ÂU#¥ÓÈ>^ÍŸr9óÅvq2ÿ÷1ãçcb©ñzÏ­ã7…))Ù8]¤ûF›ªÀ²êÊhens5<R«•6Mr{¥· Ó2…ûàáâÇ=]¶àŸm¥Î>ò@G¼ó4]‚	—¹È•X¤¥.ñ†Ö•Í½Ë²‚*NğJd%í¯} Í¯~úZmØ$•®RTb¢rÛ„‡¬ãÏ-qYÖà®•­dÄyz²¦·)[sİ–š »&p©	®iÂõ1çÃjƒËƒgz\\öÓb½SF«Sn/Çcñ˜¯õÜïİj×aÆ#–ühVÙâÈ®“›ÌvÛYm\nDko’ZŞH<	·‚,-ª»Ç/·–íÿÑãk¸®·Ö°ÃXbµh„-Ìğ¼.g|›I®E÷–¹Øb•íãr‹_ÙL÷xe$, W¶“ğ(õB*’¯ê_$$I‡‰ÃÂjKºó’˜xÃ;ŒÕX‘ÄoÑûMË;”QE»‰[\0F GÜZ«	^‰ã…™†K‰éÕ­‰ÅÇ˜Íš@WÓ­Î	îGu™Ë™œ‰š	„5ØWÈ Z%Øè¹ŠwŒU‚A•q˜qØ\nã Ù)	uèşĞv`œ¸™I(ÖéX¹MËB˜Z›—™ÚÖ•HuĞ¡Å˜QÀ\\•Xãy‹}Ìšœàêˆá†“\ZíU~DŞ\Z¡Ùu•ÔÿFÈˆ¼±ĞQº=K\rŠX¹JL™‚MÙ¬ YıÙO“DI¡-Åé¡Ä”¨8!•Õx¤‡%æCY©›\rÊ ƒUa{L˜º1ÚZ¸ÌÇ)îUˆ`á^té‡ˆÁG,^|ìÕ\nÆat½áœ@4Áà]åÇ|x™ºM!º‰â—Ü`æ@YP¢-•Ã¹^¶1	NèSi\\SdÉæl‰ Æ\\İÇYíÒº#0™Qr˜P †… }š¥˜‰™`á©‰_©\"Ò¤É|Ü}L¯E×~P=L ÃˆÙU°9Ş¼¡ß&z‰ZQzxLÀÌzQÙêK’@Çk­«dQÌğÈîUÙ2úŞÿç‘Ç¹Q¹Ç¹¡UÄ	#{p¼¹•›\ršÌÛšhÄÏ‰‹¸ SMæõ‰˜	¾I~(Ş\r›¬à›¸cEĞƒ}ğâ}ÌL²ÉàÑÕN‹—ŸZ©ÛË™Şîñ^x,€’Uät|R±ôXSàDõHË	`U$cJâ\Z`\r†äpPß=àB¾’\"†¹\ZĞ¬bs…K°V›`ÁG=Æ\"ä4ÁIsÕÇPÚ	`ñ¥ã!#ñ±eŞùÖÌXxG•AU¶©Lş¨“ñXÅRiZ‰Ut”…DYĞYm…XŒ‡8šG0µÛÍŸß—ÎÕåY¬…,FŞ¸Ô%_¡ š±Z«™NŞbŞÓEŞÒ=PÎ±‡é¤Qè­—âÒÿ ¦”ÉX•Ã‘	E2Eb$€‘æF6á™§½Ãe†\Z1vã&úyÔƒ•ä˜ˆâßÉJÎULÆUL²â½ı¢¢™	†ØN†˜F˜!_º¢Ñ(¥¬‰ØQºb~Â›ÖU„ñH°åzRây†¤ÜÉCwJ‡líÓÆäÓUÅÿIYÜeÅ•tEVø–~Zß9 0\ZªåÜ„í¦™à(ãµÅªõf\Z¢ s]×sÉYr_i’i‘êãêô\\^g™¡Ú…ælšG‹²_¹­U9\0;','ÿØÿà\0JFIF\0\0\0\0\0\0ÿş\0>CREATOR: gd-jpeg v1.0 (using IJG JPEG v62), default quality\nÿÛ\0C\0		\n\r\Z\Z $.\' \",#(7),01444\'9=82<.342ÿÛ\0C			\r\r2!!22222222222222222222222222222222222222222222222222ÿÀ\0\0d\0S\"\0ÿÄ\0\0\0\0\0\0\0\0\0\0\0	\nÿÄ\0µ\0\0\0}\0!1AQa\"q2‘¡#B±ÁRÑğ$3br‚	\n\Z%&\'()*456789:CDEFGHIJSTUVWXYZcdefghijstuvwxyzƒ„…†‡ˆ‰Š’“”•–—˜™š¢£¤¥¦§¨©ª²³´µ¶·¸¹ºÂÃÄÅÆÇÈÉÊÒÓÔÕÖ×ØÙÚáâãäåæçèéêñòóôõö÷øùúÿÄ\0\0\0\0\0\0\0\0	\nÿÄ\0µ\0\0w\0!1AQaq\"2B‘¡±Á	#3RğbrÑ\n$4á%ñ\Z&\'()*56789:CDEFGHIJSTUVWXYZcdefghijstuvwxyz‚ƒ„…†‡ˆ‰Š’“”•–—˜™š¢£¤¥¦§¨©ª²³´µ¶·¸¹ºÂÃÄÅÆÇÈÉÊÒÓÔÕÖ×ØÙÚâãäåæçèéêòóôõö÷øùúÿÚ\0\0\0?\0áø/”ß&ñó^*™Æp?:1QìÑ~Ñš+êØúŠ³o{0ıêüÖ.\0˜$5U£³ÓŞÚB	qÓnêï´ck3Û¤.‡`Éšğİ£Ò·şŞÍaã©à»ìâ.O«£$5J\Z^æ°­­¬{íß–Š?x9®mLis$EQsØ†´×T¾›våÉçŸÿ\0WàkÀ¼ë˜d;.f¢HF+•.{£z•9löY+iL€ıìô•j¤‘Áv .ÕâƒSÔ•vBèÃÍ?ãVÄ:Ú\0T¸Ç¡lƒZ¼4»™ıf={O?`Û³n8É?áExü&#kÍÿ\0ÿ\0…?SŸt_Ö£Øæ©hÍ!=«¼óÅ¤æ€sFhÓ®i4]`èú”×!s™àGõ¥bkÆøF>ñäÔËkÎêïâN¡0eˆ\"¡éê+9-ŸQÍs±Å ƒ“ÇĞÃ¼TSŠ[99n?:ñR&NÓõüj5ä½{ÔÈµÌI(¥Ç4P;ÕîØÏãÖ;ùQîÅXÒ,â¼‹‰\Z5bíÀÁ\'¯=±š–ì4®@½9Å/jèõ¿êŞ´Šöh„öŒ­Ä9!Nz>GÊOBkœ›©fàP¤šº· »}–òyÚqŠÎ„+¾7µ¯ÿ\0Z¢½Ô|Õ1ÄRj¬mprÊ¤ƒŞ”•Æxcz¼æµ‚®îzúW=§]ÿ\0¥ªIÁìk S“(Š°IÜ‘zjEä/\'ù¦Æ>céµ,cäÇJ²I¹üè©GOşµ€Ê¸ˆ4d\Z‚	šÚXÈàÎ´>^@Æ}j´‘vÁ¡«;—â¿-ÏÂ«bq%ÄàŸ›$“ş~•âz…ûÎ<’xSÏ=k~ææîm6;;â…‹F;Œ×+$.’•”09üë8EÇBç%-M\r7MY û]Âşì*ÿ\0xÕÒëUiãš»\ZÄ¦Ìƒò0# c\'úÖlˆÀJİ\0*3œã­=Ä:æ½‰”B‘İD¡Õ×å/|ûò?\nf“~ï?Ù¦Æqòš¿§Ê°<S<;Ç‘2¶pÀnB óÓT×?i•Õc*0wã­TDÎÁv‚O…Jœ.ª‚3SQ¡\'ò¢€¹¥…R”ÇL*éØ{S\Z\";P2š¥wgåŠ‚}kY£ãŠ¯2äâ€1LÍknbw&åIçeGçÇq”.\0=yëj½q\0‘J0Õ§Ã¿• {\Z†¬R,O\r”‹Û¦™BÚ0«Ä÷$/ıóùæé6ï-è”ıÅç>õZ;YYù‹u\0ƒ[V“<l±IC1š@Ó5S$õÅYS8ÅgÅ!2`\0Ö´#VÙ‚0\r]É$/Ï\\ÑG‚Š4}ÀéPÈzúÔÌàŒf«;öÈÉ¤2&>µZON1S;Œœ®*o—¯~´ì¬ËÏJˆŠ°Ì¹ëQœsÍ½Nä\ZÔ´¾³¹E¶ÔãÚ¸À¸ˆ|Ëõêˆ#hõ¦0Z™A2£\'Ä:bD}í´ª~áŒm8üI5y¨—zÁ+ŠÏ‡Àö«±?ÌiF\r;Ü©NêÖ\'ÈÂ§ğ¢˜².G4UÙ™èrÆ:‰ÿ\0–6¿÷ËñTÆñeûuŠßşùoñ¢Š\0gü%7¿óÊßşùoñ¦ŸŞ±Áÿ\0|Ÿñ¢Š†Ÿ]ŸùeıòÆ“ş¿ùçıòÆŠ(\0ÿ\0„†ïşyÁÿ\0|Ÿñ¤şß»ÿ\0pşGüh¢€¾#»SŸ*õSş5\"ø¢ùsû«~Ù?ãEÀ_øJ¯¿ç•¿ıòßãEP#ÿÙ','GIF','chartecontresceau.GIF'),(5,46,0,'Adagio En Sol Mineur (Albinoni T.)','URL','http://multimedia.fnac.com/multimedia/asp/audio.asp?Z=L%27Adagio+d%27Albinoni&Y=346265&T=Adagio+En+Sol+Mineur+%28Albinoni+T%2E%29&N=Albinoni&P=Tomaso&M=Forlane&E=3399240165271&V=1&I=1&G=E&audio=/1/7/2/3399240165271A01.ra','','','',''),(6,46,0,'Pochette','URL','http://multimedia.fnac.com/multimedia/images_produits/grandes/1/7/2/3399240165271.JPG',NULL,'','',NULL),(7,46,0,'Canon En Re Majeur (Pachelbel J.)','URL','http://multimedia.fnac.com/multimedia/asp/audio.asp?Z=L%27Adagio+d%27Albinoni&Y=346265&T=Canon+En+Re+Majeur+%28Pachelbel+J%2E%29&N=Albinoni&P=Tomaso&M=Forlane&E=3399240165271&V=1&I=6&G=E&audio=/1/7/2/3399240165271A06.ra',NULL,'','',NULL),(8,46,0,'Choral N 6 Tire De La Cantata Bwv 147 \'\'Jesus Que Ma Joie Demeure\'\' (Bach J.S.)','URL','http://multimedia.fnac.com/multimedia/asp/audio.asp?Z=L%27Adagio+d%27Albinoni&Y=346265&T=Choral+N+6+Tire+De+La+Cantata+Bwv+147+%27%27Jesus+Que+Ma+Joie+De&N=Albinoni&P=Tomaso&M=Forlane&E=3399240165271&V=1&I=5&G=E&audio=/1/7/2/3399240165271A05.ra',NULL,'','',NULL),(9,47,0,'reproduction basse qualitÃ©','image/gif','','GIF89aİ\0Õ\0\0ğµqÖ´‹5,#qLùêÌìºŒ×«rıüø´’iùÙªúè¹®£‘Ï—jëÉ™Í‹V´‡V’\\7ùİ¸ÑuOôÃxëÌ§ùË˜íÙ¹òŞÈÚÉ«ûÇˆîØªç™híÈ‰İÁ—ùÍ§Ø¦Zú×š™‰sÍº¢åŒZ¾¢tØÕÉë¨cVêÍ·îÖšúØˆ¾i<é¼Í ƒ‚A#®>åDSıè ùÎ¶ğ£èæÚíÖˆÉ‰A¿Ÿ\\ÁÂ¶ëíê{n¶A?áÜßpkfïåæÆÆÇ!ù\0\0\0\0\0,\0\0\0\0İ\0\0ÿÀƒp˜+ú‚ÒxP&™´¨Ñ)­\"S¨–Iìz¿à°xL.©O…:bY·Ùè8…ÍªÛö\0©Şê4–fYr%iWV…ˆV\\‹gŒ‘‚‡„S•†Išpo~\"Ÿ \"¢}z©ª«§{¤EbNs)~¹·œol1¿	Á\ZÄÅ›ÇÈKÊBÊqÎÍÏš¶‰ƒ¾½×ÃÆ·ºt©»\ZİÅ¸äæ«éé¯ìëêğxòÛw(!!%`´µıåòã¬	F0`Aşl)\\\n…ÃdÒ”èÃ§‡‘ÛXÑà8qóxÕÃCgšÇñÎ¥T²e…‘æ,Ğ‘O‘‰ €ÜÉ3§Ïÿ?rò$À£/]¶¤#T)ÇPƒ65*Ué„«X¬Üš=ôàÑEãÔ³h…f£Ê6)˜ná®İö¶nÜ»Q’´«¶-_¼gÅj®p×»ğDáB€\0²7ÍöK¹²6Ë˜\'ÿ%§Yïf¿3ÓMÕ°éÓVHÀñØ²üBcM{víÏœs»‘M\Z4oßOSsELxB„ÃŞ}òvfæĞm?¿ÜœºôêucO»©ÛáNCj]ÜXyäèÛÑ¯U½ıôôëqÊïş›~^Ô‚ƒ{¼8ùåìà€ÙÄæ^|Â— }õqçÙ~ø…!ı9†€ò”á†.èÿ¡†:× ƒ÷„âQˆœ…¾×á‹0†H¢Œ\"Æ8ãƒÜHh\"Š¬(À…çqhãBâ‹ŞÕ¨ä‰-érZ\0Ş1“c´è\"‘X.9ß‘’ç¥ƒ<&Ö:dº×R\"$WeØeİ–4úbœt*gnxŠ¤_9ê(&™‰µÓ=LvP‹šVş2æ`ÊĞæD+é·ÉœzåG/e—@“5é$IŸbÚéF‹fD(ï¨sÉ«îÁNª(šƒBšT&ª\0.ö(OÇ0ÚÑ@œö”-Åà\\:Û°šòT*F¹¤ÄÏE¥Ú\n)Øàª;ª$i\"­„`ë¦¹‚CB\Zpÿµ}î‰Oöj)çòPÉ˜R¨Ó’Ëª¾9X$0¼â[Â¹«\n¬‰½¢0«\"\\\n”ß¼1Saõ 1 ´0C}ø(–\0.lË-¡™’*«â²9ÂÌÔÃ%”@S\'\"	\Zc¬m¤IZ°¢ÆD­C)\n]ší»›¦+>¤à<«¡%Èlt¥ÈĞãÑPcŒóÅøl\\K.h,ÅŠ±\ZBrl»¦ƒÇ ·`ÁÚm‡[€ (¸-»¬(/èpö¨`áL“Â<+áy„LÜ\r^7t×]wì‹àlë<‡Èá|0ç–Ïìè–·}.è3—\nVêÉa~w›[>ÿ\0+fâæß65³^ç£5],ğ\rOŸ <Ë7?€Ïğ\0\'ÒK>ù%¨Ã.\0	ıî\nÌ!“êúz¹>•kßùémgNuöuO²Ö©—ÌŠ¬òòîwI¹h²<ŠÍ¢DĞ	ìà€L wà	h\r€\0\0ç©p¼O}mƒ\0ÍÒ7–[¥Kd¦»\0übG°ôaĞøÁDX:¶™¯@ÁóŠÜå/9}cÆïÄg\0Hj‹Qó.€	¬À€0XÁx@ìÀ‰JÜÁXÀ\0@ 1h×§ººR„\07k_ø8h\rÁ„ÛûÆBw±­mWK‚\n\"ÿLf“!·»,Ö0o:”crè»è¤M&ÏcÀnçqN„$\'IÉJNR#€@ë¨T­ğĞ€ŒaÈø‹ñ­/pí‚½ÌU<ÅDŒ ËcU\'‚O¶Z¼_	ê˜:÷1MÃÅWp8®´‰`\0`ÀÉ½ğ9²‰O´¤4+y@\0a&eÓœw¨æ½êyv$¢“Ğ€ª.\n&Ô¾,Q1ğå\0„õ\nHYÅK±``º Ó%Ô‚hÙpB=DÔş˜B€¸ ™#€¶á€<Ñ´¤¥É@\\@•+ÊSHÅ†n’`÷l›C<ºsv®S‚#Ò’6¦o˜qÿ-M«ÎË¹¹o{¬šY‡¸_nsw(7!×P…¶€yG,j)™TF”’K”A!Ç×ƒ\0†Š$( éÂJ@J’Š¯¦¶HéåúÙÃUÔm¾T…ºh*Ò§P—ª§rPÏíÅ4J1×š„ê Ó€¬û\0/\Z1¢˜lE\'yÀÛ5!kMÜµ’¹íÔgDk[Æ8Î~¼“|¡0_rJ6µ]½”²3E«)ıãVV *m©4a?­‡×¯”g¯:D¥X¥.‘rë¡ yÉäa€ê\nf „Ö÷$[Î,˜¬‘b0œYíì\'BzÒ…l*–.Lè¾x€«Väÿ…ÉXª˜y².ÿ,\'jÔÜF\'DÅdB%èìàEûë_~â¨D #‹0^p7À¨\Z–ha÷Âì’ğ_ÜUÓ¾‚80Ù†OYäÍå\0ÅèÜŠ¢UûF7iVÏ	º£8N)ñÆ…4°¨	uèWÀL\rà`&ÈÜ\0“yà@n‰ÿ½(!d	‡`Ì}µÀ„/§Ñ‹t3sM8€(!–®6ÜK³óƒ8Ñ*bWŠñ¬l#«ÀHZW–gáÃAˆUĞµ²h ‹A\0QXîâñ4—è–9äÕkÉâG\0arUV®aKeY¡t•\"Ï|,ÂhpÏtjsµƒì¤\'ÿvZÎ\0¼Ùm†‹ñqNÑµÍó=\rï,T=ğ6¡€UÌÚ°)ĞÑp€Qñ7n¦9\nïš*p¹ºşˆÅn‹Zü8l(Ü’(©õ|Ñ»Ìğf4£xuWNøHx®…_°Ì>§-¢ÅE£ëMƒqiŠÖ_“¹°”g®c<µL‘‹ëB×Õ9KÖ˜Z„–°ÈÅèlØµY^)0·hLD\0÷¸X›ÆHÑßÌı ê”YØÊfÑ|—µ‡<äø æ±‘Ì5à›•ù Ìğ)sR„ÃàEaÙîæsVª£îè´w´ğàºiöÕ®††nûÚfyŠp#ÿ1Ì‰\\á´ÚXÆ¤¹dŒi¥$À2,½fJùælü°û,«¡Zxv6Ñj‚Ú¡\r—2KÖF€ï^\r²Z†zìX¥·¸ĞW,ÿ©uÈoİçrê€kBûZáÉ¦±]bÎ\">‹§\Z²›£²}í:\\yÈú>¸[U‹\ZÖî}`‰›Œ`<Úªvb˜\"Íx)~ıÓJ|\ZX½çúâ”OüQŒõòø\0or`Jn¹&òûÃ¯ò  ½p‚Í¹°…¢y^¡ÿ¦@VóZÁøM}ÁBQYGÖX;*	ö |øj‹Ñú\'\n™“å€ŒÆLÇVi2ÿG\n¶\0¨\n]€ÿ´€²å]CçÕwåQuSJpµgŞ·m†|ig<“1âbrÄ7p¢g{w-\'7°gz1œöGuõ6ìÇÄ¶lq³DH€¯Wû÷7•\nÌ‡\n9°``{ -\0]s|ûÇGª…O\"$W¶Ğx\0!c ‚Ñ„ìf\0\rõŒ´†â~%=qO†Bæ\rú÷\0§p;Oƒ8Ì³3s=ÈFß`(¯“f×85èhVğ…„{´ˆ®ò„¢Æ3@1‰Yxv—PW¶0‰cRW\ZÅ$o.gH,ä\"(‚ø*ítHğ…8zD]Û‡A%ŒFEYH\0\0`ÿÓgb¸‰:ãw©À0´uRÈ]Ø-›èŒÊF;ùÔˆA‰qsˆ€1äiŒh(•¦jíò§è\ZauaÜ’z Œ é½9`Z\0&péÀ‡À‹rÃ‡\\mpLÛR\'`8ŸG²èoqP7…„5é£@€CÈ„ÇÈ~z°|É\\i‡5L¸HÊóWùÇKeóV5t-¬T)›Au‰¢\ZùaêÈŠ7 Œîx892©HV30ü\ZYïh4 :»\nˆ-Ø2x|Èä€‰uXˆ„Ç’]¸‚Ø<¨…È<!}Sù•™€0…;ÿív©Ø+¸1\0C)–Àõ”­\0kx\0v®ô\rNI;Í5¹r*‹(^â0-G§É\nÄ\'?ÁS«à\näŒÏ(—–©‹Ë¨Üp?)‰’RG©õ\Z|•›_gÒëøq€“lÈ†|yTTrrÜc˜¢”ÖsˆÆx˜\"š[ÄRˆOa˜…™˜¼é$ZxfU–Ÿi-wE†óBÅt]˜”¢\"*(Hs°œj55œæL!Ğ“ó\0Wæy¼ğˆ™‰öp™—Âå™œhãO|ˆ7ÏWgB1(4ÔYš¦±R·ØI”ŸáW}ğCÙÁ™ï0“Cgöˆÿ\'£OÈ¸.ÍÙRÆù«ˆ¡æå$<Y”—2(îSÍè*W•8á’éFAjpˆo¢#:0ZI0yä•ªœ÷‰–®Ÿ0lÚœ´S¢ŠŠ•É‘‹qÍH€]¹|ŒFPÃ}¨- vj÷,áX³FšµöŠ§ E4<ÚPˆ5Ué\n1Š£9ª£î)>º£eB\nmg| }#ÉsS(sfdÖ…]¥#I4š}:P¥\'™<øÅ<€:0Á$šZjJ\\jÁ°{Uy„lzŒÓ8Y˜q3Í‰šÚ››š©ëÉ\r¹vÙ„8Sduj¥Ú\"™WJ@—§¤e\n…«©§bySÿÉô3Sè\n}:ÊÒ©å(©Šb•TD‡_‘zàqøV’‘‹Áú©OyœÊÌ¹­W•ªØYé{DØìe\nj«šó„¯—¡¡ Zs£òõl04½ƒ†ÅfŒ\0z‘| ­ÎšŒİú—ª ‘)©Úº¦ıŠÛÉÚ¦Ié:iŠ¥Dá®ŞPŸŞú£$9\Z/ÄJÕŸû/¦¯Kª\'‹²ó™²©y±\n«‰àŠº™£{–ğ©¦ò	—ş7;9­ÒZ&ú$]F7‘:Ì›Ş#±CÁ²+šÛ{7Û›}õ¥ğ\'Š°Ï‚µ˜)>ô\'ê 9{µ,i\rÿ@´m©[†¹¯\nÚ¶5«²oë¶q·ÂHµ*#œ™›^ë­|Ÿ~+ébŸ)¶.;°ğ\Z4yÕ™#¡ªµ§*0;µr‹®Rë˜•éZ[³b…šŠ’ªê¸èé¹Å³§<G¸ø™£Ö¨\n=x¬F{´Z&;·M‹KMË±£b»´ë	*# Åòú²¼[ªÄÛ°}Û“h(s[&88g²Èºİêh?…»Ò?—(œ)º•[CÇ.šsh€”š¢^ö/ç‹.”µ.¢–™zhª¸C­&ª;jğ¼báº.—gò—‹¬¡†Oé¬œ+¿^iLè¤\\¹‚`	¨Íj§8c§wêÀÿ¯J3iÀuÊ”L)|êeÁ\ZŒÀì¤èJÙ<Ü‡uì„\\“6¤ºÂ :Ğ¯øú¾GEƒT¼µoĞÊ•ÎÓv:\\Àˆ•G(•CÈœò[Áÿ·v)^c4DƒL= A1¥—ÇÄEó}\Z3¨6¨kG•ÜãCÅÃYü¿Îú³áš¬K0œ¿ & g˜JÄÇIÚ°³«¾æ{\rc˜»Ü:ÇŞ†ˆã\\™òcÁ”û¸µg“&µ~ÙÅt¸(Ç¾[½;r\"l`¿/Œj|?=z;oÜ­´e·ØËCüó¢¿²¼d›œ²³£½ÉkÈry®û·3ÛW¾ª*Š¬‰İRÿ@ã•ÜºN‡ÉÑ\0¯ ÅÉsÌ«ŠÌ‚Ê“û°Ù›,¸ºCê«<|ã\n´Æ+¼èĞµpEd­¬OmªË.Š¼æË™0Ê¼\rŠÌoŒµÛ,·HûÌÎ¬²Ú¬”Y5pš)ÇÏ‰Í·|ÊŸk Ò¿Ê\'¢‰Je½œ³éLõÉÎöéÎÁ;Ë‘ÍzKÏ.;\r‡“Ï§àL³ç¸Âì‡-¼e›)j@´“°,éLËÆåĞÊÑ:¯0Ú»Ã›ÊÜ4$JÆaûÎ#\rº!Í½ÿì°2íÌ÷±\Zóºª¨Ğ3ĞÔÕĞ6ù 0íÓˆa½»{ÓÇkËÄ@ú¬Ï?ıÕ~)ÒÑÖÿ’,+lğu)Î--DöÒ;ÕÁÂ´ó¬WÎJ‡ÓÕ$mÑm\0ĞÙLÖ{bâ¤IJ-MMÌ,Í³<+ÕcK”V=%«»4]ÑXí$—÷×˜Û×8»üÌÙ_ûÑŸí×ŞÒ.,”ÔMpÎÙĞÔ3…œÄ>»±‹~,¦£‰ÄÙÏKÔÁ©<2@„=-³ñ[—Ú8Ú¡+Ö ½×¥Ñnä©­Št ˆoª…¢:¸ÛÓ¦ğÁ×Ü˜i.Ø‚ÁCšÜ¼91´²-’’èÚx*Ğèú×È=ÔZÛ±EÍŸsØm\"İD6<á#@Ùp ‹4xbáH}Æ´h(áPfc}™\"$¸ÿq,²©<Ü‹LâÊóÚßLÚİèß|q(ıÜİËä‡­ı²œ˜Ÿ¦EèB-”[®DXfÍ(µ2½n1¾¹ó\nÌáÌY= í}ßí¾Ë=S¨­ÚÃ ‹9`±-Û=ÇÍÊ¢ñù¸•:ÇÛYw„ä¼é”³´Üvë‘hÚãu2ï-æWû´B»Vã‡ĞkÀ¬\rÄ½˜í¼Ïè€-´Äƒæk<¯y‹àŠİ—ê0å6ÍØ\0Ë}wıèm&ğã\'ÎÛ4g­·#ÌÅËÛ>%´å†EnäŠÂÚkèæ—\nÛÍª×‘«Ñck«~ê\nGÌ<½ªê`ÙÃ­îqé¸}eŒlğ\0)ÿÇÈ‡³3è¥öç»‰¦šŞõ<ÌúYÒnK$Ó2š®‡¬è¯\rêqÎ€¦w€?ô•{yÃÄ}À&ŒL‰:’[,’ŞÄk÷}·fkP|›…Ù£Üg\\å¹—w£ë½‰7”©Î‘C1=FãÀ%áT™Â2İÈ±æö}äwI(CÄ·T¾Ó•	Ä²Ù„²Y–ßM/‰|·™»¾L\nù	`E$7ºî(jîú¸œñIÜ°éÍë¦\n=¶É>ê†f®¼ó¶„3ß	ÂœØ‹¾YĞÍb*¾ıàJzŒ·ŠCP@ÿH‚Ğ€	ù»ps`PH<»,ğñ#Û‹RzåğÎ¾¢†³[~7†ÿsøbëÔgşÎÿŞeş¦ì!^„N-×ómß%ÄÒôsï–Í’· §Vò$ñ\0p¾çåÔ†ğlÈn‚¿ò\0!sîĞ†Xéøo×¼4@6ãçÜw¿Æ:oø ÷x/\'yÿ+w_úé[ÊzŸàÀ<»EåaÙV¿†%®èì	‹´†\"O”9]¾\\¿Øè”O¶Æ®ö±&›Ÿó‰1D¡_¾CŸú¤oúĞú+V\'À\Z	ÿõìğWGˆòÂ,ùù9ÓP@”‘_ï‹´îÛo±.Úwå&Ş4,g ¾Ôì¾óNW\'ø/úı](¡eH$v(Ò€‚lXãõ` °ÿ-GÕjĞ2ZaVkĞe@ `¨cå˜a!åî[bĞBß÷;5@4hz\\Brh. ##&Çô¢X°%9;	?AC<GKIOS1„–0ÿ0ñ¤Æ®´NÄö`Ã *<öh½„o× îp_•\0=’R)-{}Qf“5QMÃ‹ÄÇÁÍÏ¡”N–“ße]/}Éz}á/¡<Â\"H ŞåØ.]´Ò,P³†\rÜ¶n¸¾¡UœD‹å\rq² ÄÀv_a)ğß-4è¡aYKMU[˜MÛÀˆa\Z¬xnSOs¬B!²e<Wîæõ)ƒÒhÉ<bœ`ÿÙdÑ?9\nPÄˆ¡ĞP–Ş…@àçÎ fyşT5*êKªMa!hqÔ­põ-k/³ªX\'-©ã@WŒ†eD-à³GVûx/3¾ój$å×	[\rdÌ²œäè	¹y0@úÖêQÀ‚«fXsŒ¨’!7v¬\0÷dŠ­X\0š$¦Vèâ³/ê}ÇcÅ»s|@²©+Ì¸Ç—{ÀD( ûgbY¶÷Ö;­zkE¬ŠÚ`”ĞKWoO¶OÍ›K½PNãì™a^6 b¤ü¨ú‹¸œÀ¬°ò–³ï¦²Ş[!3|lª~˜x—á¢Âmvaê‹/öÿÚˆ\"@`†á†	 `›Á°‹ªÍ6Cn´s‚m¼	ibì—\ne\\À½\r9Ô°½‹Ğg¾½ùÏ–+¨s. ã¦‚)xiŠ‹ão:7¾@Ó€ëh‚ å\"£ºàš)²\ZÈKò=ó”+J*	rĞ¯«¹\0B£\"\r`ş#ƒ7¥ô3Rt-“:\réŒë´ëT‚Q¯c`èæ\nà8úôÔªÏ†ÄˆNĞ\'íVŞpU’”UX\"£(ÓlËq¤Oµ3\rí€Ñî´¤‰Í´\"–Í.ÌµÃô³O$M8¬€O${«ÄšÍĞZß½uø(Ö9F¯-#æÿ„SFXu’âÇ™3Luh†…yd8\\*¼¬t˜ä¤øÎÈWuÇ’7Şsõ¸$6‹/6XîGÏàìÆœ\"\\U…¤¢_=¨\0ğÙX¡åd·ˆÅÑ¾¥)‹÷ÄXW…Gë‘m}Zj¨K±²£xş³ñ4t¬å3;]5^æBÀ…GÕõºÆ5ô•”Ì¯Ù%µcÖ>\ZiWÑÍxŠ“¸ê¨§üo«}è3$ÆŠÓfa\\î¬ÅD<^Ü<›\'ç’S£í†¶zÈ;×TögÖ¿<dÔu\"¼/åÒ_á~¤œs) ?I£Ënx¼«iÎu7ÃÒÿ÷ 6\'YOİùæBB¤ÂRÜóîzı²¼bNJ@lV¤¨ƒ\nºXôÑHLÛHx^~Õç—Wzêñ´;LÒ$Î{ú¬\'–ú¤ÍACß£Q.	Wƒp_Êd`‹[M~õ³hğ—¶¦;\ZÜiRešàiï#<_A›õı‰oWC\n:ïWŒªLUüb°vğV˜õ€¸C0ª†“ÈÂıœd†1T3Æ‰·d°wòØ!yx4Ö`ÇƒA¢ğ¾ƒ™p¥<€>˜\'Ã\n:‘P4…2¸@i&Ë¡KøEï Z^ÔãÃ8ÆF˜±!LëÿJÜ¨Æ9R&+Ç\"%…Ä1\n‡ÀÀ„ ½96²‡¼â%¯ÖE?æ‘ˆ:1¢\0°ÂNB]Sä\ZQ\'”q¤c”â*<£„,€\Z=€\0Ÿ–WVrÜJV§ü1OŒ†%e’Ê$\Z¤•.L$ÈIb‚ò\"Hæ-# vH’—2‘‰x^xR®Ó‹Ú¼á(Ù‰=ÏEs~b_5‡RËW>“Ÿnß%NPˆój‘4„87bN_şG!.¸Ú³Ï)Æ\r‹ñ$LŒ$ÍÀ°úÙn ¸™”ĞI›—$¨#‘L„J¡—Ë[ÀZ(d<•(,&\ZS—ØŒ¥<DFÿëiËU6*U\00Sz<qŒp–4<„ªj¢:Eå¤:ˆ‚¥Mñ]Ä›Ë¾röŸ_¯æãÀh*Z-Š†&¢\0ôé3s\0ÔiFC4Ñ	@:I¡Ô\'²HQ½A¨XÏ`’0Ãßœˆ\'¤õh\0Õ°£Y’Ö²à[CŒìM½âÌ]ì\ZölİíD\Z™¸%A¨MmãiAÙ×¸I5*Z\Zì\0„E\\6ÀlrX¿\0@ÇZ€™nÖxºNgş\n®F\"£géJŒ³Ş¦´½šOsÕOùqÄ4™#i@]‡Áç?ÈØ ¥¨ó˜–ı¤8EŞm\Z³”Ä-î‘›Ü¤ø-\rèT $iÿZı*@‚õLuKô_Öf!)³\r›7Œœ\r\n¸ŞdiŞíÉâD\"øáEå9.S$…Å‹/%\0E¼ÒñWÃS÷—Éµtr­hqÛì9C€(š[³¬ğK4¤a“î…‰ÿ6ì¿’¾ï<J<îæašDDö‰©hÁÇû8ÅÒíÄ,Y,`i©GDñA|…HÁXUhĞ‡CÉÂn wp3ÎŞŒLºmŒ\\æêrÀ«U®²ÉŞm§ª£²ì¹Ê»Š›–ÿ›è9Ä\"_„XÀ°¸¹uÆ/;Ñt€$ÿ^Wµo(R¥KcË~0İ6ç˜‡®\\€.±Xtúÿòw	L˜\n\"­\\hÓÀ­íÒqQƒB4ºS W.\rhº\n®}±²=àÔØ¡×[\n³›YkíNŸdÎİn/oR˜ˆF¸Ú–>@·¿#GÏD×{uözì`XÖğ¯¤®v¢Óèasàw\n_(Rl­\rİE¶E½¸Ox	§“¢êi]|:V¹ÄÈ\'V/9C˜ëã\"Ç¨KêÔ¾\"¦I;Çüv“¨–Ë_<1}BØÄ=ëÌ‡3ÛAG 9‘vÀ/Óéˆ³4ßËˆ¦Š¥Š¶×§FTDg9Nºh×DyB[´I†¬7s¤&Û·ş-oT˜šµ|Ã¹\nÎ~öş(ÿWÁZ\\ÄkU=CWìLY‘¥÷cıÛ€‹Ÿ ÊQŒAá-ƒ±2İ>m.\"¹µ^)õNb­xÃ;Wÿtñ2pÛÌs€æ¿ËÌƒ³küşW»gèòâS‰ákX„®Íí•c&%ÍQ©ÔW£\0o¨p½è­¿…•Y&÷hÍ\"g®¼ø›yqÌ<¿ïa§=@)=àà‚Ô•Ş\\qÄ)Îm²ßáË½ïır‡SY³9À¨ñôÂÌßĞ+2AïƒÖğl¶Ø\0Õóùrî{pçeâ\0\rØÄ\0&`óöúÌDóö¢ÜRÔŠ¨¡„“HÊü #Ûö/ÑœÔ¤ÿMªª-¶í\nåıbÃæOğØ\nğo)ÀÄÙ<ƒ(úmQ\0À#>^jç4†@ì\n -ÉÌ>@sÂ9ú\r\Z­î\00`\0¼*\0Æƒò,>6áZ6§t6&Ô<Í,.Uø\'ˆªwJhõ2¤Ã¯£Ä®`~&h~†gx¦ÒjAûôÏšÃø‰fˆBîÏépF€q‘±÷ŒÂN\'²pÌ¦–Ç\Zz,êN¯†dİ6Ñ€‚É’n¸ÒPŒÆƒÕZùŞ(ÙRf$à]1¹Å?Ğ ï\0ç ê¹n1‹O-7ÀH]±\rbñá°pDvÿÑú°’t@6ÉÁş\rikv¨ÈBï2é¬À‘=±ıL!…ä·©<®hgÔ|K~	;vQ—°ÒD‚Äpee¨Í#­{sdÆ0ßäí’|Ğû$afª\Zè¨²ÃËôn¸ˆ„}rmWÈ¡ä½T¨E\0tJêA„Æ&hôeIä\0¶äq‘¦L“œëw17ao¶%µ0\r±¹\0\0òÑ¡°#*oy )zIæ|LĞ.M.z­ö¸­vĞÍÂzğûVL,¾å¢ë”d£ Ü†EM~†fÖå÷¦d\0Ó²åx2#w\r’XrÆ\Z¡aæ¼ÿà8şJ\'‰Í	|“ 	<zà;•T²,‰$À\"%1ÅŠËğeM»hĞÙvL¬&s1a«`–\n®0@èOs¨d†hVKAÀ/‚i3ÎÏÔDn°¥°¾…ËÎM«®A/f ƒó@ lRî ©!ï¥¤‚7BÀ0Ğ\ZeóÜŞ§D&§sÆŞ|M “í-1Ï¯:SÀØuPè‰#äóŠì\r»ì_:$P\"ÅP:ŠÅÒ˜CTãç|Ì,~Eµ1/qÓ_>\0íT€ğ’´0\0¨±+G,zø°\'Ÿª-¢á›”S\0$ˆ:\"S:9°€PsğÒ.Üò™0@º“ş.ÿ1<‡8£AJì\0qæ%ak$´¤€$`¦-„ê¤xŒ³º‹æ¶á\n`æ>£’@Õ²òĞÒÕT@°bt´ØÎàílFË€B}Q2ÓJ©Ô˜\"j„òCATõ4&$äÖÊ?ES&îdær€LåRäšI¼ æhR%KjÖ¶0\0ùzàªB¤²ø|í9¯têÌLÒõ$/SÀ@ÍLßj×p@!¤‰\rYèp|îZ¾çN¿Ì\0”°_²02í¦¯?{¯?õô	+a¼&H»	tÒg,ƒ@$Ô3ÌLõ2©î\"•X-“2A02ÿ¯ï éˆNQ-Ã’D‡ƒ<o1¢ÕIì!\nAüèÌ½iæøS£OV…s\0ª‘6	A©„²7é6F¯F[ëÅ˜rB}Ñ1ı°”WY‹]m…§ñYçè\"ï¢DX1Òà!ó-,›â)c!)ˆÄ[]¡ Ñ®RTkÀ\\}¿\'\05Ó!BöT.8ïbê¦JÑœoÑ•ôÊ5Yó	ƒ;õxÍSMå`]ÒúDö%)òîJÕæ‡%2ÜÀús/¥ïI	(Q=¨â]w¶	Œz\nRn*ÍÒ^4ŒjPá(µk0fyUËõgMÜp•–š¤ÿ5ª€pµÂ§†l½¦¶†ê…%\Z”n\'Ë†ÂåWN 0Á%?Q´c=$<D\0Z]ï®Òˆh\'a¶¾õ:±¶Ì”lPòòeÓ¶mE×s aâ”65Ly6m×vRÿ‹«ËWa×4Ğ6ÑÒé®^×\nnT4_şê.rËpxwvä´o½‰Cïâ®à5„R«®®W¥Ó¨€ôNskl9×\0ğ¯ºæ¼—Uë:×tÓĞHp`nÑGzü0C=°^íqËl·v•\r<æ9w6ï re”[÷Àeö“,wmon*¿Aár€!pàJöhAB£E±w{­W¹7ƒËWƒ¹×{ıÿê{;xGÏL§‰¶Ê|üÀ5âÜBû¦Â˜Ï;©°P7“¯äÓ(7‰Pç	„R˜¬qt$).c7tÀkÆdŠvyáÅzçWÈvl1¸ƒ7X{\' ÷0ƒsëlà1OC`ÓWg;C8üúŠüF×ù¤!…VhùK»ºW°‰÷W.‰.ñë×’x8@Å…šxòˆ;›V1GMÓ€$‹/xÎ¼X{§øŠ±˜‘YŠÍm¸ºoOÆ˜SGÕ¾ –ƒk$ÕvEè\0Š`É5íëÿú¯}k-v-G¡0·–\ruU©\"\Z¼ò¡vî•±Hë-=ù­ç‚l½\'*ÿƒE-J@!6] Á“”Ó8lD™o3ää%=Ö$ğÖ{ívÌ²ÓÕ^Ù¾eø¼ò#‰#ø¸à\0aOKDkËLÃ¦¹w”JS’•Y{¿£™‹ŒË8”«Yœ±9Xª£»²ªv2§ºù¬\0­xïw<…t÷Fˆ]Í—Š3%f‡‰ÄÎd¡0”$«´\nBĞzK—ßâÓ\0ÌŒl†ğ„ÅøÎµPœÙ ­5rb2øÓ\"G@QÍW««`zn²tk:?àğøŸ{ª“;–3èjª°`W.ï¦¶ò&P\0øğbÁŒ¨· ÒğlyµƒñmcO’|›šE‰šQ·DÙÿšÑ:¤Ñò,§uÚ½zš÷®yf‘U›¥â¨¯ñ¦aµU³ÄötSùI\"\0ø¤Tµ\ZÄ.Áò€i]–kKyµ¬¹æbxaš)Q´­µM~I`¹ˆ%yá’¡áığš“Iç“Ï8Ñ~5Xj;—TV¶ØøZ•UùÓZ…QÔƒ™°ÅBH%tİb›.ó«¿f*‰‰¦OR‚m`—p	Ñ\Z™È¦Rú­´#ÎI¬Œ2Œeâ™G†\"¦úJÇvlX\'ÒnLış*?cµ°Í2¸—‚ZëğkİÀxš©µ*ÙÂ`1øºù!ù¬Èş‰CZ¸€¯¸ß<{xA­EŸÓ@t±ÿn„²)ŸzC!áHxh”ER›A\'Yá;ÛâIyû\\¯•Ş0t“Q¿H£OoM£ÌÎ«¹1övN«=ÃÁé\n®U\ZY{ÂWÅÂ¶áZùÒàŒƒÙ\\KªxÏ;¯ˆx|Éô‚2s:]|E#Ïr\n;Í 4E’áĞÍo¼¿«Õó¢ls£”ª­qĞz\n?¤4—`¹Y	É1W_Ìú»\'|	k€Â·XÃ\Z©?Ë\'R;å\n¶¡#!³R–X1õ¬±îÆ¸À¾ñ»ÄÎI½Ô\r9~¦§œYÚ­‡³\'ïòp×BZe‰Ò—!Us—««ƒs­ÅÒ,8«H—Å0øÑ3´-¥ÿËCsVu©É¿0]Ì…/À8}¾­ÙÒ|wÀ\r‘¿MİÆß¼?vw éPPµyÙ×NÁš×ªÇì¤1×6—XU‘¥­E»ÉwÂç&…‘ÍÃk–í´óçˆ¦RWèşÄ3}uÕ§.Ks	Ú—Ó jÚ<?ÍíJ}âç°gñÔÍÕƒ´OÈ¦taçäá_ÆÈ±ï^%è8ßn³lšŞÙåÇY=|v\'§®yƒ “IFv\0f×Ñ5»K¾û„õLêm;vÊ Û¹=Îe¾{ş$­?¥gz—gµ\ZgÈ]ß¢¢còğõÂıÓLW~/\\Ş¨€=…Áy4÷ÁÀæİ¹ÄEÿ¡¾J¯‰é[ñ’òDVë¶L?8º+[X1­ØèİaípğwÁ¸ª\r4f³şœOàÙ¶oFÃ¾Ş^ŞßÆi®wA¸QjîäQygÍ1à·\Z\\Ó1tKI™ì>Á 1Û•é\'9ÒÕqŠqœ¯#İ¬ÒÙ/OìcÍ\rPû®3Ç¢SXcŞ·¥ß|øŞ¯¸`ôÏœ‚ı³\Z³¾Gu\nVÑOˆ\0Zÿú#û$Ú>X„(¼¯ˆ—÷Iı‹%¹Y;ñ&ƒfÈ£e8j\"\nM\'•t2Ê¦™HÃâŒ1„€‡ƒÓläh\rÚÉÄp{ÿd÷çØ’ò7:â0tÜõôù1\0^Q .,„@.LN2*&)È‘\rØüå…ªêı¼\Z°qÀ:HHŒÔÚšÌàêfğVø‚\0_\"úän$Wh‘NE=ÇMI_iŠ900onƒ™Á¾¼|¬µ½ÅÍµ¥¤òİ´²‘õ¹;\'^5.<\n¸HRX\n³Æ)P$Tµ2uŠÄœrı<\0ĞëÛ\nZÉn!Ë5ñW°aR8\Z;–q„|œ†4*©\"ãÂ…I-aÄ”qÁšpãÜÀQw‚à+\"ìÜµ„FG¡4V °\\”’€~”\0^Á)^ ;Y¡R˜”Ôª7ÿ%J„UÑ\"Æd\Z‰yˆ²+Üvs›—bÑêQ9¸(ÅS h\ZÂYæ°N<F$÷ Ğ:´Œ&EÚîÕ§xŠ<µwQªV+=c©ÕóP@j.%;vL¤~,ó\ZP±­®·åâ¥wÉİàz[\\3ô[VjOêâë…¦6n›k^Ì®>ô¤s„]›	‘¦má‚=¥]Tõ÷o½ú«CùQ¥IByş‘ÒÑC¶…óJBìÆ›oËÕÕqDæ‹!ß•t›_,æ\\„„=„°ˆ$Rf{ÀNÛå—{ sÌIç%ÆX}Ïè @üÈÇ0ÜwÿÖA!\\Ûş5ƒ$8¸¼ÂÀ,éR‹F3\\’&€\0¯\"á„¥d3áJÌ\r˜ˆ†Vx\r*T Qì€	À~} 8™Š,~æ›G@4\0ˆZxÃs7.\"Â>ñ]ä ëYÃß_É€ù9Zll [RærL1¿…TsÈ GhĞES/ĞDX°@\0©a‘B‡.Ø©‚œ1bÓ¯h™¯­6DâCG¹³SDâé†QMAÀû¸ CÏ*~¦xEˆ¯G\"ğ­\Z{¼ê‡¥¦éõ)¨B%à¬ú\rš*ªNQ9’\Z,°À¢vuØ	^»³ç›ÆÊŸP6ÿŒ¼ø­¯àóZ³ô@5™:V{í*tlƒî¸Ç6¥œ§™²Ån»Á g¼æãÀ#eÑÉ•w`óşbËPt‚` Á1Öaxä|`|N¦‰xrFÌ¢ÉãônfØÒJ€¡;ZëÍ—€lò#¹P³Ø[ëòæ.1è€êÚdyŒWxÀ€Q-4!´,\'öoyÄÄ·ÑG‡95¦$–hk*›QÍ*ÉJtNŒ\\§æuµ‹MÙƒl‹xg!h­YÚ­=Ö¦p·Ì$, vù©~ˆ·\\ö\"3¡–QóÂÅ,Ş’¦Û¹8ãy²aJ™¿ ù\rŞ@NÕ¿bÿÁœ?ãyi=x,L „Oé%ŸZëZØà@”°c³ÜÃ˜ãB2„âcÁ½I©m›\0¬\0#€Ÿ0\04X<G?\n«Á+ÒÀ<\'‚\r”LôäpÊ™%ƒ¤\\Öö„ÀuÏPğ_è:g×©|çS]úX!­yF$|’-b§81%,ØÀa`‚ø+U»J>a„lD$\\‰¬ğ´S=ä#Í›àÿŠ‘4 ëyl€¥v„„)mƒ,2O3ê ªŒ‰Îs#ÙB±‰=kE©óÖËB©ômáK3`-fÎï	gXÁL À÷ô`‡T\"Õ.l,QZ³jÀ ‘ÿ&?éjq½pÜxÂøWP&\\«œ8`0‘±Y|[”èX²#±1Z[I’QÇ[f¦Œ06:å…ÔĞñd\"á€TUD\'‡„Od¨¤*%áöã]´Åj8«h\0[ƒG”·+3~3\\EÏãl°¤§½ÈD§ÜU°>0F4‚:‚e¢Eüè!\0ÒÁååF$#^ö’J~[-„y‹Pñ6p@ûv˜#/_’€/2Ò…1B0L(\\q£ÉONís\'Ôph ƒ!ƒ…p\'±Hµ?°c¬tJ°#ĞÕ³>÷tT-QÇO‰¥œè\\Lƒö‘S»bh¹‡ÿ>44Áà]dl‘*-P £³¢«V)?Ì.¬!åÅveÊ¤L-1ºxF	1˜¾éƒ3ÅD	ªEÏœ¶…ÜzãOÕ®¡~)/8jAi¨T¥`‰NW=&‚ \0CjlªV—…à\'æ0¡UK¤fxU¬‹SyÊ\npÀ1¨NC\'\\Íh¸Îı€*aÃ+”°Ó½®}4ÛkÊå_î¦°{,êË‚£€×è-P×r·êÉf€é!f# !1}•\' mX—·Xrªõhà@íÄÀ¡vÆõ¹ğ¬éLîŠš¹‘¯Ùg_ız[¢ú;xRG€³àHÁTm\".e+ë\nGÿ´ZM€ã5”Lƒ Çâ®Ü6bÚÁ)¼>íæï¸öµP¨é<g;õj`[\nØ–æ(=¨¸$ºéoQşâØ¿ÅMËÄÙF6°¨ºêZÀàb®^06ehÅÊ&í‚w²â’Óh–\"Œáioİ \\áiÈ6b~¯N}ÊÛ9¹X\\ñé^¥Çv-w†9>h¨¨d—L3vEußBX/ºgÈ–s±Ğ-x+¢ u2›L0¹}•Œc`ÚQ’ó^0½\"V˜i[Û¯âó×ƒ!Év¹	öá÷‘.(ìœ¥ÔÀ·Â+ísŸ{fYû.Ğ—æ\"	Æô‡šnhhæÿBèv‹Vw’<L‹kRd\0eÕD›NÂTà‚~L¯¢Ü¶™ûé-NÈ 5V5«s¥äŠêPr™ƒ¬Ädš×ĞØÜj\0·?QÑØú#¿Ï\nÁ\r®õß]ôñ½XêK[0Är¨m“>Ô!ÜêVPu³GWÖ™´óG “´ŠD\"Ôğ\0yñË¹ú‰	Ö.¥ïí.¼17º.Ì\'’.ÅµÍÄLz#CIpc·ÃU¡:@Š±õ¸W†-ÈYÎ4D•	üËã@½¶zªD^çô°hòCÊ«+j°òÂå/÷f”­¸Hò‚ç”r\'iz¼<­>èU8cÿŠ_ÓNy=u•ŞiàŞt§Gİü•º»‹šj89D…U”ó¿¸ Ç1•Ÿäæü„P?8v“Ù¾?*k8Ú2òdÌ7H1?á‡6–Ş¹ß]j›·*&RÄk²)·yj\"¬EÊ©[Û\0 Áü=Sz¸ˆ`Ş\0P\rA\0ìùÏ[¤_ûƒóæuÒwã=½êÕÚ¾Uzùš:˜½{ƒ>\'Ş^”Dü ÀæYÎº7ú÷¾1Z|ã_Ä¹ˆûDøÁª©\nMäE1ôĞ—@Z10L\0‡ä”„Ú¡Ş±]`ˆ¼ùñôÊgäJyĞN sHËÑØìƒ‰LgL\nYpXÿ|mC^ˆÃì—a-Ş»aD/‡…I2¹€Æ-®T†UÌeÙÈàJHp 	ÈJaûP¡Z¡ÙØ\0b‘i\rœƒ%À À¤ §™Iü­Í‹ïõÁÓÕXˆ\0•a\\R	üäKÿıß\nÑwU ˆ@@ÁØ	\nÔ\n`@oAa\"*b\0ÚšÌXŸL!ÊÎª^LiĞE›ôMsü\\ûµpÕ\ZfÇŞ,±¡â	™\"Â!ÿµãõÑÿéÙ1y‰*Ğšìaå9IÆÅKH!#PÏş¨Eâª@R#ø¥Ş\"ŠÃ>-[ˆÍÕt\"	‘Ùš(BÃ™âÿ)J!»ıŞş¹ÜrùßpyÁ\0Ü\ZÙÙgØÙBq\\]|Êœè\0Éx,\0\rüC%ü@	,À¹ĞÚà¼@ÏÌ¡?#³¥UëÌY¡ZYÕİz]À4b›5ša<ü“ùlÃ©áš6âa…#sÍ¡@@%ßI0eQƒ`´WµøÜ¶øœà€V€Í>èŒcM#jÉRb¯DÚ%^É&êƒ\'ª×5JäĞDŒùğÄ¥L­)ÈªØå \rÍÂTÃ`ıŸãUŞû„Ü;N•´UìÃåXBB\'@Öä?Î!NZ 3ªb\"òät£&e‰=¤\\dÂù°œ(ò^Eâ“:~À­b[6Éÿ\nèWTîÑ*ÉÂUòM-:‰Š]›|–ô@Ø¥ÏÍDM¬ÅM*ŞxùŸ9Y2~S&ºe´ıã·´áôÀ^´ĞP¾_^zËÌÜ\'\\fï™Bªã._A®E!–õM/É™@‘¤Ë(„qQN7èh!rPKeÎuTh¶¡v¢\"¦÷á\\j–Ø‘—¹ÜÏ&Æ&5Ølev R’An&GÏ¼åşf[çpdbç,ŞbV\"Qd^t¶×N|†cQKNàÊ‚éÆƒBè?‚ aª}Z é	£xÎŸ<zŠzb…\"€Ú(Â\'¸)eÓ—Í…—Nî×Ì„ÿÖ©K\0®¦“øçº€¤ğUQ›€]c´ÑiæuêcàÊcV‡ƒ¤a®&“Ü\'¶e\"¶A}¾]H Á}ŞÆ¬Ò¦î•¨|úï§oº”j`píË´L…@À+–›š¦è;Ú‹À\0(3×F§éÄÉ\ZwJW’^(“êé	j††É”R)l~è]\"õÉˆ™@…&Z:éŠª•Á¬Lå‘$ØİCY„µÙB%ˆ®‹ª•èÓœö^ÏİÄu6èg:bv¶a‹äöj N\rÀê¡2BŞÉ&:ÌšáÓ£BªŸùYoÎê’z3é2ujX×@å‚ÿ}¦a\n—Øí%EÁÿ«¦êcuÃ’ƒ®ÀƒªË~ÒMÚ=é’Ö*+Ò\\FP®2È<‚µ)j¦\r_²a*V±B™&…i²Â©#ö¢´Ö(ãa%àxAv˜DeŞ…Ş)ª*†Zşck¶­¦+x«%~à¼¦=L‹¼béˆ.şY¤$Ö\"ş£¬¢«¹ª&a*Ÿ±Kd.ƒª)Aé—«N‡®I\03T…>”@g¸¹ªwşËş©ËÒN²)âgX*J},sÈ|œ†ûáåWİÒ©A¢—^äh¡˜.­\rP^—I@;\Z0ÍPTÎÂDådJ\"hŸ.Fá‰«İRáR†-Îelê$¡ºÂ}ÖÿTx!‰Qí¼fI°ÈÆVĞ¾ª«Ú iÂ!`vÁœÑÂ\rQR`œ­@zC©$İ@…«M0—·6†º©R†\\P]]yØª*u×iVªŠ³şF	®G	˜†\nœ*ôËâêiğZ¨3êb&£Æõk*r*‘jJTD;ŠAg†kç`çVèrêÏôzÈ>Hçu€$ZeìROˆG%†g6Ec{PÅğ®XYSQ_v\'~ÉĞâ¡ë`šé8öRÆñÍ‚1†a®š´—ÃnÁ“D¨uôI·o±ñmß2š–)£…\0P@Í.sNÛûúÃ\nr„´@€¬h‹ÖJèJÁ¡ègrºÉÿùõ¯ÿV9ÕcRëbGh¦©4á,…îšzæ„6pï…[kc,—ä¸®ìJîóá&AlA|pÈU^Š@A¨îRæo¢…ÓÊÂÒ4é şØ¸$¦@e¬@D\'Îê§ö>b¤ª[Ëi¶V§9Ë#qıJì.Ú¥Ôg%İîCB”@üî¢X—¬È©æŞ¯\rP‹†Mæd÷)©iÚœ\r;	ÑªaÙvÃ(ßé©Íq£2é²êÑõ,äRjÒ*Oé+xrYG@1`È“P1%\"ßÅDeÖÄªcç,×¹, 3ºŠªÊè>æ¦Íßtë¸Š²Œÿ§2ª¹ÙÕ4ow÷i,-æPÖ].÷€=òòMLïÔ9æ,Ä¨*3³¢­‚¤qEÜÄbL³6 ÚnçvÎå\\~sË‚3g2²n¾Ÿ¡¸r>­şàt³\"ÑB+í«‰éTª#¦éQ¥]\0!ôó(Oìƒƒ@\'h6Gb˜41BSXB/AŠ3€…U®šó˜UãÕV´´Ş,I¢C\r–\rç(HeèËå`=\"úEàFİ*$«[¹ÄôUï¦¤ş)MÛ´÷­ì…§<—ëÈ=ŞâÀír%¨3+K×æ\npşYàAÓ/O3£q]7•¨4)§tÿ0§¥V_õöL*=×´]g0bÓ²ø’“×uá´è²\"Ãi\"üN4m¯P»Ïä>oÚa(‘Õ…1öìB!2dZ	­‰m„\"£M¶hÎÈLußî«şÅå\"Zëõ‚hô´eß	°2ZÓn,Ree€Ó¦Ewá4nÇÜõ¶©¦\"G·jí\ZÂvÊh5w4õJ2Ãòm¿îÆ–ÒR‹±<;+¢F´»÷­[§T.QËµ`z“,3éiëy\Z\'Ãhêk­R6ğâ¦KW6ysiw5Óiº‰’j—ä:ˆÁ…ÏN¿}&¬Ee\\Ÿ=‰©Ú·mßw”ŒÇ~æÇ|B°ô#¶3mÛZÿÅB„—DiúvŒ+6‚¿.I•ÒÓº‚SÙˆÜZ¯\'…ÿ\r|/b|fÁ&ßsãö‡ÇœÎQ!.Ş\n6İa£eWéôcù×Ú§iÇT ÒÔq²[ÀGÁÅïà«ËwÁX2Êx‡/£n¯ÛÙé_ãÄÖq7\rİsaLĞx,g¹à‚7uàæúY@iTv:ãÅe{@\ZàZ-v’ug7·x+ù¹úgÈÉ€ÇAÀeNóo7õ,\0kdxÓs‚Ûê£%6ã7¡‹Vs“F˜#®£o`Ww´›õ‹„Úš€_´Àezº‰·t€~tŸwW&5\Z²ÿí¤6-í6Ù ú9‡ÿC7º:Q†™«ü,6·ßúAw¹ ¿ƒdª§ëäaä™«W´®¨\"xãµ8Ó®sVhI»¬+º‚1–f¤Êx{{ø¿Ÿ&¥ßgÏ`î#ßÔ>Qo¬Ì®{¼Ï*YpÜõ1”*d^Ó]´kÀ´‹ùd†±òæ<ß IÍ8T„Sãş3N÷:øeÍ>òTbÃ;åÊxg}ş¹	ŒîÛP¼ßÎ²†Š)½cî\\¬’#PEÇsWè>z=£z)œpÉ0r#­Y °İN™3ÆSá¢­E®¼¿rª3-¦œÓr¿\0ª#¸³«h`º_¿IË¡$r¾‹×Ö<»ëüè¶üD\'³¹s¯ıÿ…fÃjmŞ³éâwdò×8ËL@Ñô}}+9Ã;©Ú?û\\8ÁN+@lÂïUĞ²Õº­oû\0\"wèC\r§E{>’Mg 8Èí.6xäêúŸ§k¸n€ş#Y)u‚›€9±yƒqÇZşåŸuh¾p›ô›¡ìB‹üİË\"HoÒ~\nÀ\Z±Ü–»‰ßĞ„:†Ó¬zx™SË/i\nîø«Õóî½cß÷‡¬Õ½eŞ¶›\'9\0ı<ÅN¿Ü¼)¥¨ËH>T2¦×Ê1lV«Ù±4n\0Ã\n@ÂœfœÉÛqòM\Z¦bâ@Œ/Àı†¿?°É~¿×ã{şdyd°aè+Ì«\Z[ÿ9ÃÛ™Ãs˜‹œj¸ÄäĞ´\\p(9=°ÈÜ,DbÄ#,j³Q+˜‘Øq‰’Xm4\"´›å3ùB‹rlë=–kõUvYl¤ë“[¹µCq€¸-d}Ä–Œ´<5ÕÒèü-E×b©h¡”b>,ÌşEA’pÙÉE×Ÿ8*5vD—>díÍP\"MÏAü†¸€–eZœ%RĞâ0\Z8eÈ•;WàKŠN@\"•RšG>\\fãÅ\"õjñš•mDgI::\\§QR¤M™2İ¦P£UÕC’jYdR†³\"˜¤ptÊzgÎ\\Ë—íd¦¢‡3Œ³&hšµÿ\"Ï¥XW]ƒè7°6Ârr,¬ğ®€I0.\\q¨Xf¼ ©í¨3kRµ¼\0|“¤8H7¨MU›@Ä§¶¢ÜŠ§uîè=½A\\±c¢$Bs– ™Wà+Å¯¨ÚjÎĞàÑò…Ò¼¸Åİ´qã—TèİmW¹\r<\0Ğø–tSÖ@8šéç¨E—Îe0_«>3ò±q Æ+£Ôhâ%ü¢lpƒ@/ºÎ÷fSŠ9¢¶YÄXˆÂí9Üê©Î…z´pÀºZÎ@‰¹´ƒÀ¯PÁè<õ$Á9öÃ4ø8™˜:ÀOŸ\nl’m/º Èhùÿ”èN—èÿbº*‚%ÉŒrÁº´fi\nÄî\r¤yFÌ•Èë’	%€‚#F7„(CËÍ{1œ	\r¨ñ…Ôk¥Y¡Gû‚²FÍOªh*NIÜ\0\"Æ?vĞËÀé¨S£Ÿ\n¬8Xigñ\'—*QuE$üJÏ®a‘<iÙaÅ¹2x®J¦ŠÉÔTjÆş$SiĞM,h‰¾CDTÑÓüf—…B0\"\\\\óÌ:XÍbCPa+Œız#–DZaÄ¢0ë•WY¾Ú ¤\r„H•\'_ 5\r&„½¢Í§fÄjÑb%I6aÎ‰ Pœu+aül5=	$de¹\"àä¨İpÿá|S@\"rµÜ9Í…õ@©ÂZÔ[ÁzwoÙäWf4nIv„LO¥fl–H¤8u!•\rÍ@/	Ae\"@„ùØH‰õKL	~“qÆ?Ù˜v¹yÍÄì[µnÙdŠJ\\sçõÈŸ5KÅ)Ñu»ÃÓReijĞ›?ÓØAH«eÄ81G‹gi”šv©µ¤zÑ\'õyS2á–›ŞQËp’¢ÌßÈÍ‘F0+¢qº8Y9_Ë\Z@\\oÄ~*ruhA~÷»b=dÚ­hrJÂùH’¥ÀÌ‰ YGœ!Š»¶pcëÅ uÌ‚0¯J{t£Çˆ‹\rÒ‘ŠiX½·\Z#ÿ¾ÑR»:•|ªãâ­J_\'ô´‹¿:ş³«Ï3G3É]Üó$Ï 2I%÷°<«kÓëZŒg$ü€}\ZÃQó†ì¤®ªëÃš€“éáEJ2Ÿ	U®¯m°V©Saksõ$£~ï9 È‚ü˜b‹Ú%(9şÍpZg¹ WJ´P†oÈ@‰<Fˆ%Rq‰õšb	­hD\0–†‹û±u˜ÀK( Ék\0)z`(èÑIzD¬Í\0×3Âõ,ª&ƒâ&²Å¦TIv/äã”3—)ø+	\rk$¤è• c\nÌˆ3À’sIEá4\Z¨X	\Z5ÇnÿOp!KiãVîáNº2dCÎ‡\'-Ê2‘]ô\"EÜ @p!‹ëÀ\0 ĞÌc€&ÀIK„‘ä3Po×ƒ<à]ĞÓğTÁX\"ƒ\"ÄZ7?ho¦ì\\D$µÉË|ìdÌxp£@&—=ì‘à- 	îÒËôXóxÀA°M2‘¡™ÚÇ¢µ`AÔ¢ç¬èSĞ†N<-•(€Ÿ0t6”“§Ğ¥Ëté¬aà(˜I¹!¡—Y”Ÿ¤)Lœó?}ç6»PlÚè¡u4Í#’ÊÍ8*µ†rtj:ùT\Zu=Aªè,õ¨ıP¢&\\ÿµBQ©3`saóÄÈ\0\\ê‰.5ˆ48ºĞ€òrvüi@zP±Ú©@ì^«™PÂŠ•¯C\r,a‘êWwîµ®KÕ+éÔbÑ7À,dçˆŠÅ\n¶²sd¨¥.[ØÌVk¦h¨AW…¡õWÜ`«\'ğ©Ï™V‡‰ëdD1¡3òö½MœÂX€h€¸ÊÓ€ F¹\\nòFH„%se@…-/·ŸTVZ’I2šQ¹{PÜšVHá²(…9ä-ğ)L—¾4­n}–u›İğ¢Ô¾÷#wó«_ô*¿üÍ!}“‹ÆıúW&\0°v<_r1€e¸ÑV—ó˜1İ$ÿ?l`ïö¿>ğƒ	Üaƒ˜ÄõMp}7¼â—c’fh¯Kw0€\\Ãûo£¶{b#wÄ=q‹Ì°·È<ré»d&9zàŞ.A`6¦­YŒd.¹Ë_>r˜Á¬â&—yYgÖoÓ`ÚŞ¬4Ÿú,–slæ1“™Îvfâ…\\g=/ÑÀ>€.La+Ïv>n}Ş~ ĞiÎ9Éîs·éòRzÏ’¶ôŸ\'Éšµîr\0È¬1¢GÇÆÏ“†tªñœgTW±–yôs¦ÅëjZëyÀÅõ[Ükeè\0™`Ë‘Ö‘µ™O£êW7¼äu´YÿWj—‹ÙFï“¯ŒÌ*#€P ş&Ÿ6ê t¥´¨–ÛÌˆb¯Ú(	°<•\\X—¦®%êíî|¿ZÉ~7SÛíãô–Â§äo~+ë`Íÿ8{ç³\0Ğ@ÜãvÍ\\dƒñYÅ l¸ÌdU±ãwèû€ë¡7’wï”Ç»Òæ¶7vûÍçyçwºM¥+5œÛÃ”äG?ÇÀÏ-hØ8­ÂTC•i\\ñƒ/\0¦Ë¤LE\0Pkªa\0H‚17Ì°u›–¯„\r,	N\0\Zìe\'ìÙÉn†z^Æí‰êÙC€öŸ^FÂæéÚ…ÙX°ïÕî!¬gßöÑŞtğ[ûØköÿ	7¾Ólui;‘^ö\0—\"ˆr>	ÄeÊ \0†íªäªiAÒÃ%ä‡—;`©^÷ÃŞ³ä¨(½-ûb~>çT·ı\\s¯××õ ¿ïû×çÑ‚ÿó ÿìGjAÈö÷ÎïkàiĞ…ÅDĞo>ô|`’a`‡ÀûÅ6õ<ş _÷¸Å\r:ĞÕtŠıïÇõÂåoÉùËŸ¸¿½ÿÁõÿı+<á½¥¿\0ì?„?f±\0÷;Àò#¿èaÀ,²Œ¦\njÎº­Ê£$(À²‡é\rìØ¾MÂ?à¡Õ+¾¾Ú½ãÓ«Ê›«¡›ºğ[€ˆAğ‹½îƒÁğ“Á+‹Áü>ğ¾ÿÔôÁïûÁ¤8Š£A\Z<Â©{Á€b?ŞsÁ&¼Á\0€ÁÛ³B´ÂºbB,œš…Z(!²ÂPÃ>¨C¦ÔŠÁ4Ü¤âˆA3,:\"ü>”Á:ÌA+ºh¸ ¶¡[B\0¶EÄ>ÄÄî«Ã>4D<ÃD4Â\"B”DG”Ä!œÃGŒÄ ÂMôÁ8ìÀKdD9ìÄ\Z#E8ìD<4E>ìÃøVlC\Z€E5”E5ôeZ¦úXCMªX…€ExÅZF[œE™\"ÆbDFeRÆeA`tÆØÅh<FiäEh|ÆkÆ_ä`ôÅWtÅàÌÀVGrÌ@k„Æn\ZìÆ\0ÇdtÇw„Çx”Çy¤Çz´Ç{ÄÇ{\0;','ÿØÿà\0JFIF\0\0\0\0\0\0ÿş\0>CREATOR: gd-jpeg v1.0 (using IJG JPEG v62), default quality\nÿÛ\0C\0		\n\r\Z\Z $.\' \",#(7),01444\'9=82<.342ÿÛ\0C			\r\r2!!22222222222222222222222222222222222222222222222222ÿÀ\0\0d\0P\"\0ÿÄ\0\0\0\0\0\0\0\0\0\0\0	\nÿÄ\0µ\0\0\0}\0!1AQa\"q2‘¡#B±ÁRÑğ$3br‚	\n\Z%&\'()*456789:CDEFGHIJSTUVWXYZcdefghijstuvwxyzƒ„…†‡ˆ‰Š’“”•–—˜™š¢£¤¥¦§¨©ª²³´µ¶·¸¹ºÂÃÄÅÆÇÈÉÊÒÓÔÕÖ×ØÙÚáâãäåæçèéêñòóôõö÷øùúÿÄ\0\0\0\0\0\0\0\0	\nÿÄ\0µ\0\0w\0!1AQaq\"2B‘¡±Á	#3RğbrÑ\n$4á%ñ\Z&\'()*56789:CDEFGHIJSTUVWXYZcdefghijstuvwxyz‚ƒ„…†‡ˆ‰Š’“”•–—˜™š¢£¤¥¦§¨©ª²³´µ¶·¸¹ºÂÃÄÅÆÇÈÉÊÒÓÔÕÖ×ØÙÚâãäåæçèéêòóôõö÷øùúÿÚ\0\0\0?\0õíBhmÄ³Ü9–fÆJ€	\'ôªÓ_[@Áf—ÊÈÈi8Rxãqã<ôëT|`g{Q1ÈÁå-&Å* œ~x9ö÷®b}Îhd¸|¹(XŞ2I‚À/Ë÷P˜ßÔíÁòİœš=\nq““ÓÉ_üÍuk6—ÉrîFÃ@À?7LŒ3‘ŸqUu?i:;Æ5ñnÒŒ }Ù cÓğ¬Ë[;MÒVÜ›±&ñ q+ÜU0êœg cÎNp5H5}OÄÖwºKOdğZJ©pğ†UsĞ‚0GRúsQIÊÍè*‰+òş\'cˆô™Ihõä	\\’’îıÚœ3ğq€A…2oiŞYÚ›Ô3Şªµ²ÿ\0ZáHúöõ¯8±Òî–À¾Ÿ´KŸK¸·Ô˜£¦æggVó±ì@9é©.›{úpÓäŠİíVŞ)nÒáƒÍI‚¯“’2İ\0uªäÍ™óK{L>4ğÜÍµ5X‚ÀçåU,N=€\'ğ¤¿ñ‡t¨àk»å.\"ó [1t=\nãã<ú`Oi$²øu¹‰µí¢Ši†å+Œùƒq÷éÚ©Ce«é,’èóØÉ4¶0Û\\%Ã¶Ôh\rÈTäƒÉÁÇ<÷ÅO$\n¼£ûF:·öjÜ)¿UÉˆFCc¯\'÷ÆsVuG’ÜéÒÆíhZ.ål­2+=T}A5ÀMaª.©/‰bÓši \Z4PÂáíFbÚ\"TäŞ‡æ½W±¦ ,@Ô­Æ\'NßÎ’¦£R6ş™NnPw4µùÖ;Y_çÙ‡8Ldü§?ıU“\rä’`(½Ú§øŒ\\ıjÿ\0ˆµ³8PHÉ¸#§¯cHº3Äü].ŞxòÏç÷ªd›“±QiE\\¬Ußç)t°ƒÆÓòq×3Ò‚B?*wÛÀİ³Œq‚*ú[Ì£¡NqïÏJI4éØ—¨£9ÚmÉãşú¦¢ÉæFTÎ±Å0’9Âl}ò|§hÇ?ÅéœT?ia*\'Ù.†ß¼<ØÇşÏüªíŞŸ4·$±K–wFÑ¸cwZ¨ÜÇl˜G2,Jd»ÈdqxéÓ4\n½ÔlFDÊ-•ÛnÚK4ÉÆıtõÇÖ‘Õg¶Dh/ ùIŸıHëG½H®Á”²JáÆõ^àgóÏàI¦‰dk´A	SÆs·•Ï¦G\'°ä{Ô¶Rˆûx<³pKmy˜õÇûG#§?Z¹ª¶ÖÒºüÚ•¸Éêà¥D±°rzœôúTú™.txİ	uQrzwñë÷qùÕÓøÑO…–5``Hå>r;m­F\\±éùâ±uùÔBU^7M²£l•`9üFåL‡]KQ‘D€Ô•Q»9ÏÍÇA‚§8æŸ2M¦-Å4míÏsõ¥!BóÆzÕH/Éwó-eP„†ÀŞA8ÇN¤ƒ3Ï\ZåİÅñ[[9p7ÊÉÆG8Ç^0hçKq(9;#GT?ì»·ãb±vÜş]ü\rc<°]Ü5¬Z¤hå­‘”È£†<u<¯qÛ ¥¾o5¬ñ[]¼²¼{	‘\\&zÆîù#ƒŠÎ}%O‰ÖĞÆ7Aæ_Æë*Îd\0òC7#p)È9\"¦ê}W…Ñ®l$‘›Ìœ´Dô+Èğ	çóÉ\0€qU®-™ïR#8ÜÑ3‡Çİ;†\\ÈïØ‚3V¦º”8RĞ©æ<uÎdÿ\09iŒÌ.Ú)¥6/Ê½:ÿ\0ÏOÎ¢È®g¹bwI<Çœ±Á$ıq“Ï©éÒ™¯†7ş\Zuˆ³îH¥2Ùwû1Ó‚œäJ_<‘İølÇ‚ÿ\0Ûp:dpBOáZÒøÑ•Gx–|M7„Ú3†ÀÁ#ÿ\0¯\\üºåµÒäQr[%6ÈÌ;€qõîMjxÑWíÖäòÀ\\ù-bYÇWq¾\0P	ÀåYÍÚM—\rb“7tÅ[ø¼ñoŒ6\\‘óÀOOåOêX“çF¨F8^àuè9ç\nÖy-ô¯²–Ør$ÁşŸş¯òk~ÆÚÍ-„²(rA;Eçó¬yİìoì­f*Ü›8-ÓÈò.<ø\"ØAa½T‘ÉÍÀçô&´aH<é§TÚ™BGbNw:ëÁ8ç¶9éÖû=ı´å#L6ò)ïì0H ÷&¶EÄÁ+@§r;R1¿#<˜­c-,c(u8«öi\"Óî¤Â›‹häen„ÈëÓ?ılU‰tëG,\"f€Üã î,8;³ü=ı©N•{*iòÜ\\©H¡Q ß»\0<äu­0À‘N®ª®T3¹ûôôüë9JïCD¬µ2M–›lc“írÜ!²8=0¤ö?çëuA¿QğñŞŠ¿Újß7Sû©8ÿ\0Ó5ÌËJQyVó•”°ã…~¾ÕÑë²bÿ\0ÃlyÆ¯N}c•GêEmA®ta[á(øÅF†0™b%ãàW?ç±‘a·*Ò|eWükcÅsÿ\0¥FœuÛŒôç’{c ÁÇéU,mÙQ¡…ÎImİNx\0_ õ¬ª;ÓƒkÈtºüJ˜™1fbİ7`ı9ü*[Å’Élçf#íVşÀ‚:õÆG½hÜÂd±’&P$xÊ¶Òx$~U‚ÖâÎ+«A£…3F±7\nT\0à29ôÏäãs©4ãfhÛŸ.ÒôB€€d‘Ã ec´`òr\0ã`R[êš]¦³ûfÅ¥Ur%–íNùF	-Á^3À#>§fâÊêkYBŠÍ0ØïØã¦3X³è…SwŞ8æP3èÊ3ÿ\0Ö­¢ÒÜÊpæÑ3Ğ†£kkf°K&ı¨#;FAÀÇqQC2\\BÆuƒ\0àmÏ$ş5¢ÛùI$ÃÍvSÔ\r­œ`+gËÛ±ĞÆ~½>•Ï&–Ã²0*É\"¨t9QÛ q†ç×ëÅkëÅ¾ßáìH[X„d–8s§ÀuÏ®¢Ã«ìlevm ğãÿ\0×Wµ8ïUğäyàj«&GªÃ+JéÂ¿}Ø…h•µ6Y5@À‡m˜<“Óğ*¼—Ğˆæ‰pÁˆ2¡bã#\0òIşüõ<Éo5İÜ²DÖí‘ì;ŸæäœçßÔS¡ÓnãrÛªîÜ«æ1ÇQĞ8=±XÊíètÁ¨ÆÌ=YŸ\r*Æ±í`02x dàœ¹ãåÁÉ¦]ÇÃ§îâDŞ$BÅzŒ`qƒŒ÷ãğ:§°€#$=³‰®sÇ¼çÖ–;7†Õ’x\\\0Y™G~ŸÎ¦Í;¤>ht9Ë=\nÎÊ2“İ,“¯2îl`ã={óÏÏ¦‚%“«y,Jr°Sóäç\0`óÛò««¥¢ B±HFpÍ!ç#“ÓÓ˜ÇŠY4¬†Ù°9o3ÌŞÄîõç®01œµxà`nOqóD¯>¬¡Ù\"uù¬C,“œtÆG^?<‡O©(O³Ã$ò€\0\\…\0ã8$ò;g2:*VÓ@*Ê¨¬ªË3v’Fq×‰õÉÏZ¯ı‘ •%8· (§Î~„äÏ=êyA8‘Ná·.Ğ7.[Ÿ˜zŸ§Òµf\\kØ gP$öÿ\0–g]ZËk·W–ö¿Æï!P£\'§Û¨ür´oË¯ø÷HµÑ!³Ší¤z·šA·—inp¹œ˜È¬,:g6!®GcÑn|+§Oæ¼ûi$ ´ÉÏÓ\r‘íUŸÁöÒ¹c©jjÀõYÀ÷é·Q^›§ì?ÚNÛ±ËáKS\0_êRîÆw\\œõà\0?J|1l¤böûhş #···ëE½œ;<»‘Â%må„şÑÔ°;ùÃŸüvá`ÄGRœÿ\0¯ËQKÙÃ°ùåÜ_øDí±ĞdSüÖš<%éªê`óÈ•_¢âŠ(öp]{I>¤7Óï<¯µŞ^Ü˜›tfs›~›Ô¶ĞôıNF(%k˜I!/3lœaŠ !}…V‘ŠOBe&Ö¬ÿÙ','gif','scan.gif'),(10,48,0,'photo','image/gif','','GIF89aZ\0ª\0Õ\0\0ÿÿÿb.*N-kRªŠdÒ²‰Ğ©tüüü³j*îÒ©üúûûùú¯qA+Ã”UÕ‰5èÇ˜â´rÄz-q`JôçÓØÏÄ¢\\,ÁšgâÚÓüúüE,\'æ¥YƒA-şşşÉ½±’_Açäáà™GĞ¢aôŞ¹úışğêäûù÷õóóûşÿöñèúûûüûúûûùşÿÿıüışıûûüş¨~H÷ùúúöñÿşÿÿşşşşÿıııÿÿşüıüúøùå¾†T#ğíìşÿş÷öö!ù\0\0\0\0\0,\0\0\0\0Z\0ª\0\0ÿ@€pH,\ZÈÕaÉT)Ğ¨tJ­>ÖlrË½b¿Ú°Ø5îšÏà²:½F»Ùí8Yş®{éx»>Ï‡ïÿ}€‚„M†ƒ] ?s…‘;	#0’h•–—š˜›$Ÿ‡wˆ&R”•¥	§	)(®»~ªˆ°£\"µ±¦%º¬¿­PÁ¤›¦¶¨«Ëˆ\"Â1Ã¥•š#ä™È,Û­Ş—·Ñçåâéëƒ¢–;à™ ‘³GĞVˆ\nÌ´1ë&‹4€fm*8qÄùzé«Ë]»j²êŒE`€\n7©0Ë]Zâ‘ÊdªcÌY n¨ÔâBËÿ—/‰ÑÖ°€‡-jğÚù†Ğ[÷úíxI\0e¢¥Lİ°t9õÔ5[£DNĞ0ÁWV.–Š>X\0\\Årcô¸ªñ,—­j»„@àíÔ¸pÔµ‹–ë†µ{™*9–ìÑ„ÍP2,ÌM{Ô&h&PU¤ÈIrš6è@j<N•4—.ŸÚáóÂeS¬ÚØ=Øõª²¡¶…išîŞ¾?+ñP±¹œ‹OŞ…ùh¨›.L\0İçtêI¬_h@îĞsªƒşwlçÒŠÏâ¼\ZyûNÇ›ÓÛöñı#¢	Öm4§ZBÊ¥¤ ]†à% s–uĞ&¸à…:!Ş{RE(ÿá8Éü§S†øq( q¤¥XI.öQWBD×ñÓÜ€¢ ¤L{ä5Q\"ä5‹g-&çà€âÔf¤Œs™\\DĞy›‘GÖX!†TÒÅ\\tNØ,Ò]`£’¾qÖİ‘Ø´¥‘_ŠH„ĞÙ@¥l‰%õ©IDLn‚#Q9Ã©˜K¢W’{Ò&f›ü	h˜‚*Ñ4šè”7V¹ c‘Y“y‘¦G©Y–&‘Y<XJg|g§ ° jmg´¶ê ŒÚœ7ë\0ŠŞ*ªf™ÊSÀ¨\"K+Â†Fì›	Ä¬\0¨ª…®¹Úf´8 ,µ¤Yi¨Ø’‹a¯têÿÀa½¢Šªª×š+¯h A ğOøºC’Í\"Á€½öâƒÏô›¯ãÎë0†Lğy Y²µ6üğÆ¯IÀÇS#\0²ÀR¨1Ç(ÑXÁ |êI¶Æ[nÊ–VàqË÷¾œñÉ4Ó<ğÍ§û²¸«t\0#¿oÑ7€°²ÄIŸZ2ÓGD|s²CÚsõN´ĞÔ3Õ¼€ĞÀÕè‘Ì,Ù9ğÒµÇB›®Ö[oñ3Üi§JwİI<ı±¯rO½Û7XÍ2Òè½»7ß¬ú¸Ü#Lx†w·7ªOÁÙ‡s\0¹\0;O®N¯7ç-¸è†[Î²0‹Nâ\0^-u“§şÿwÔ#/Í8ß{¾:è’O^ùí¿[@ôîŒoûåzó<3òfÀş·ìR?o=ô+áüøìb_·òÓo/Â!zÏ»öùÂ°8¸ùİ2|úëoÀ>.¼/3üwq,à–ıØW¾ıÅoq\\h×È,`\0ª}Ñ8	pñ€\0à€?Rğ\r%x8@|ğƒò€G¨ÁƒÆu=!\0I¸Á´Íƒ(DÈÂ¶5ÌáœÆC²o}ÁØ„ÈN]…ãã )¥Â\"Î0ŠXâáD+Ï†üÓZ¨¥°+08ÜbÌSÆ+ş0jÏÜõÄ(†ÿ€\'Œ£üÚ¸Á$æQCœ£û(E8²j¬Ã MÃ&ì‡\ZÄã¢©ˆtõ0Y4$%ÁğÈªïšÜ$\00`É¾“åĞÃpK’ìQ¥vèÆS‚1”›$%zHh?Fâò&9ØøXÈ_R˜v$d€(Ë:°R˜#¦1ùÈÚQÍÔa¶ù?O>bšq$%7yE÷s‹*§i—M70@\rü¢ƒØÎõÌ_ÑV$XÏşmó‚`éÙÏ\'Ü3_-f#›QÆ>f›İŠDXB4¢i#>°Èµ£\nlà\0;xN5¾s„àõ0\n€*Rt¤ÿÌd©³PzD?–t‹!¤)·jxSœ¢Ëˆœ!Rœ×ÏŸz‘2=D»şYG	4©Gxçíò™J¨öàL!FV\ZÑ\nŒ¡QDªU™ ÕÎPl=İâAÁ*PŞğs¬((ëÈìh-¸.ç«	5ÀEá\ZRöQ‰±´«NªSòU5©â,¬B»„¾Öt±‚%ìK7¨×…¶Ÿ“ß@áªÑ|Ås}\0<ŞXsZÄ:¢5ŒŸú1x´2‘tS¨‚gWp.¶¡eì`‹´\"ö­VõêôÈ¹Õ½&õm˜*jµf6Üæv’•«o—K7éuó¹–µíTÇ‡€ÊZÖiÛå®Xù\ZŞñµ–¨ı”?n<±«ÛÍá5¨ÅÕ­Bš>î¸V•,ıf8^¨êƒOmp{ë®Õf®¶-ûê+X›ymµñeìğ¶[×.\0;','ÿØÿà\0JFIF\0\0\0\0\0\0ÿş\0>CREATOR: gd-jpeg v1.0 (using IJG JPEG v62), default quality\nÿÛ\0C\0		\n\r\Z\Z $.\' \",#(7),01444\'9=82<.342ÿÛ\0C			\r\r2!!22222222222222222222222222222222222222222222222222ÿÀ\0\0d\04\"\0ÿÄ\0\0\0\0\0\0\0\0\0\0\0	\nÿÄ\0µ\0\0\0}\0!1AQa\"q2‘¡#B±ÁRÑğ$3br‚	\n\Z%&\'()*456789:CDEFGHIJSTUVWXYZcdefghijstuvwxyzƒ„…†‡ˆ‰Š’“”•–—˜™š¢£¤¥¦§¨©ª²³´µ¶·¸¹ºÂÃÄÅÆÇÈÉÊÒÓÔÕÖ×ØÙÚáâãäåæçèéêñòóôõö÷øùúÿÄ\0\0\0\0\0\0\0\0	\nÿÄ\0µ\0\0w\0!1AQaq\"2B‘¡±Á	#3RğbrÑ\n$4á%ñ\Z&\'()*56789:CDEFGHIJSTUVWXYZcdefghijstuvwxyz‚ƒ„…†‡ˆ‰Š’“”•–—˜™š¢£¤¥¦§¨©ª²³´µ¶·¸¹ºÂÃÄÅÆÇÈÉÊÒÓÔÕÖ×ØÙÚâãäåæçèéêòóôõö÷øùúÿÚ\0\0\0?\0÷ÑÀ¥é@éLXíáy¥p‘ ,Ì{\n\0¥©kÚkF’yd8DLşÂm¨ÛÜ»Æ­¶Dm¥XŒæ¸öQ«ßy÷r¼2ó#ÚW‚8‘èqÒ‰o.KÙYÚE,,ñÃr-âi\rº¬ï†PGR\rpÇ)KİZNŒcw;Î1GÒ¢¶m­£B‚Ç$ıMKõ®äs0şÑ¿òP¬?ìş–Š_Ú7şJŸÿ\0`¨ÿ\0ôl´P#é»‹ˆ­`yç`‘ Ë1ì+Öu‰uP¨ÑÙ«ddòçÔÿ\0…^ñ÷Ú‡‡À²Òuk™â<’PÓÿ\0ê®\ZóXi6Ãik‰ò®çjèNzç¥pâ§+ò-¼<÷º3Z®°°ZYJğÊ„°š<qŒg9íØık±³´†ÊÙa‰@–` nìqÜ÷5WGÑâÒ-¶+™%o¿!Éôƒ=«J·¡GÙ­w2«S™é°Ri:šwå[˜Ÿ0~ÑßòP¬?ìş–Š?hÓŸˆVÌ*?ı-ôÍÅ²]ÚËo(ıÜÈQÇ|ƒ_2ß_JÕ›Ãš¬ˆÍk.ÒO=¸*ØÎ óê8¯§×JòšXôÿ\0[Æ©\"IäNàFQ°!†}À¬ªÁIĞ›Œ¬zG†õUÖ|?g{¼´†0³d`ùƒ†ãëšÖ¯1øQ¬y¦êÇ{l•Äjq€ÃÆz©ù\ZôáN”¹ ›&´9&Ğw÷¥¤§cšĞÈùƒöÿ\0’…aÿ\0`¨ÿ\0ôl´QûGÉB°Çı£ÿ\0Ñ²Ñ@OóYúîššÆ…¦¾?Ò`xÔ°ÈV#ƒcƒøUüûş”¿…NÎç„ø!x­)µZK–Á²~=ëİ‘Aä¾:ğûi>$R¶;,ïØù€òÊNXŸf?\\ñëéÚMÀ¸ÒíØ6æ…|v`0ZáÃóB¬¡\'ævbygN5#è]Çí@½ˆ®ãˆù‡öÿ\0’…aÿ\0`¨ÿ\0ôl´QûFÿ\0ÉB°ÿ\0°Tú6Z(éìñÖ‘@â—š\0æüu¥Üj\Z“ìq‰.mœ\\G	.T@Ç|ŠÂøm¬=ôW);üÌ¨Ñ®N8àõïÊçé^ƒìkÅmõğÏµ(|—VKÆxĞ/“Æ{jâÄ¥Nq¬ºoèváÿ\0y	Rûi÷dúÒ)ã‚)İ¿Â»N#æÚ7şJ‡ı‚£ÿ\0Ñ²ÑGíÿ\0%\nÃşÁQÿ\0èÙh §@§3IŞ—ñı(Zó/Šv+úF°#Qå»A+dŒƒó(úpÿ\0zpÉÏçŠÈñ6‡ˆ¼=w¥Ëò‰Óäî8åOægVğq4¥>I©<«Ç©é%e™­ÛË.w)SùÀk¢>ÕãŸ\nµŸ±\\Ii4{éÖ6$ãcÛ>G×ì\\â³ÃJôìŞ«CLL9j;lõ>aı£¿ä¡ØØ*?ı-~Ñ¿òP¬?ìş–Šè9Ï§›­E\0îôÜñš( œõ9çÑş#_ÙZM\"Â·Û€Î;îí€pIÅ}‰Iê@4Q\\ÔUªJŞ_©×ˆÖœ>¡óíÿ\0%\nÃşÁQÿ\0èÙ¨¢Šé9ÿÙ','gif','scan2.gif');
UNLOCK TABLES;
/*!40000 ALTER TABLE `explnum` ENABLE KEYS */;

--
-- Table structure for table `frais`
--

DROP TABLE IF EXISTS `frais`;
CREATE TABLE `frais` (
  `id_frais` int(8) unsigned NOT NULL auto_increment,
  `libelle` varchar(255) NOT NULL default '',
  `condition_frais` text NOT NULL,
  `montant` float(8,2) unsigned NOT NULL default '0.00',
  `num_cp_compta` varchar(255) NOT NULL default '',
  `num_tva_achat` varchar(25) NOT NULL default '0',
  PRIMARY KEY  (`id_frais`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `frais`
--


/*!40000 ALTER TABLE `frais` DISABLE KEYS */;
LOCK TABLES `frais` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `frais` ENABLE KEYS */;

--
-- Table structure for table `grilles`
--

DROP TABLE IF EXISTS `grilles`;
CREATE TABLE `grilles` (
  `grille_typdoc` char(2) NOT NULL default 'a',
  `grille_niveau_biblio` char(1) NOT NULL default 'm',
  `grille_localisation` mediumint(8) NOT NULL default '0',
  `descr_format` longtext,
  PRIMARY KEY  (`grille_typdoc`,`grille_niveau_biblio`,`grille_localisation`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `grilles`
--


/*!40000 ALTER TABLE `grilles` DISABLE KEYS */;
LOCK TABLES `grilles` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `grilles` ENABLE KEYS */;

--
-- Table structure for table `groupe`
--

DROP TABLE IF EXISTS `groupe`;
CREATE TABLE `groupe` (
  `id_groupe` int(6) unsigned NOT NULL auto_increment,
  `libelle_groupe` varchar(50) NOT NULL default '',
  `resp_groupe` int(6) unsigned default '0',
  PRIMARY KEY  (`id_groupe`),
  UNIQUE KEY `libelle_groupe` (`libelle_groupe`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groupe`
--


/*!40000 ALTER TABLE `groupe` DISABLE KEYS */;
LOCK TABLES `groupe` WRITE;
INSERT INTO `groupe` VALUES (1,'àº™àº±àºàºªàº¶àºàºªàº²',7),(2,'àºàº°àº™àº±àºàº‡àº²àº™',0),(3,'àº™àº±àºàº§àº´à»„àºˆ',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `groupe` ENABLE KEYS */;

--
-- Table structure for table `import_marc`
--

DROP TABLE IF EXISTS `import_marc`;
CREATE TABLE `import_marc` (
  `id_import` bigint(5) unsigned NOT NULL auto_increment,
  `notice` longblob NOT NULL,
  `origine` varchar(50) default '',
  `no_notice` int(10) unsigned default '0',
  PRIMARY KEY  (`id_import`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `import_marc`
--


/*!40000 ALTER TABLE `import_marc` DISABLE KEYS */;
LOCK TABLES `import_marc` WRITE;
INSERT INTO `import_marc` VALUES (45,'<notice>\n  <rs>n</rs>\n  <dt>a</dt>\n  <bl>m</bl>\n  <hl>*</hl>\n  <el>1</el>\n  <ru>i</ru>\n  <f c=\"001\" >26</f>\n  <f c=\"100\" ind=\"  \">\n    <s c=\"a\">20061024u        u  u0frey0103    ba</s>\n  </f>\n  <f c=\"200\" ind=\"1 \">\n    <s c=\"a\">àº§àº±àº”àºªàºµàºªàº°à»€àºàº”</s>\n    <s c=\"d\">Wat Sysakhet</s>\n  </f>\n  <f c=\"010\" ind=\"  \">\n    <s c=\"d\">650000àºàºµàºš</s>\n  </f>\n  <f c=\"101\" ind=\"0 \">\n    <s c=\"a\">lao</s>\n  </f>\n  <f c=\"215\" ind=\"  \">\n    <s c=\"a\">220 à»œà»‰àº²</s>\n    <s c=\"c\">àº¡àºµàºàº²àºšàº›àº°àºàº­àºš</s>\n  </f>\n  <f c=\"210\" ind=\"  \">\n    <s c=\"c\">àºªàº°àºàº²àº™àº—àº­àº‡àºàº²àº™àºàº´àº¡</s>\n    <s c=\"a\">àºàº³à»àºàº‡àº™àº°àº„àº­àº™</s>\n    <s c=\"d\">1985</s>\n  </f>\n</notice>\n','005472001161679380',19),(44,'<notice>\n  <rs>n</rs>\n  <dt>a</dt>\n  <bl>m</bl>\n  <hl>*</hl>\n  <el>1</el>\n  <ru>i</ru>\n  <f c=\"001\" >25</f>\n  <f c=\"100\" ind=\"  \">\n    <s c=\"a\">20061024u        u  u0frey0103    ba</s>\n  </f>\n  <f c=\"200\" ind=\"1 \">\n    <s c=\"a\">àº›àº·à»‰àº¡àº—àº»à»ˆàº§à»„àº›</s>\n  </f>\n  <f c=\"101\" ind=\"0 \">\n    <s c=\"a\">lao</s>\n  </f>\n</notice>\n','005472001161679380',18),(43,'<notice>\n  <rs>n</rs>\n  <dt>a</dt>\n  <bl>m</bl>\n  <hl>*</hl>\n  <el>1</el>\n  <ru>i</ru>\n  <f c=\"001\" >17</f>\n  <f c=\"100\" ind=\"  \">\n    <s c=\"a\">20061024u        u  u0frey0103    ba</s>\n  </f>\n  <f c=\"200\" ind=\"1 \">\n    <s c=\"a\">àº§àº´àº—àºµàº®àº±àºàºªàº²àº„àº§àº²àº¡àº‡àº²àº¡</s>\n  </f>\n  <f c=\"010\" ind=\"  \">\n    <s c=\"d\">73000àºàºµàºš</s>\n  </f>\n  <f c=\"101\" ind=\"0 \">\n    <s c=\"a\">lao</s>\n  </f>\n  <f c=\"215\" ind=\"  \">\n    <s c=\"c\">64à»œà»‰àº²</s>\n  </f>\n  <f c=\"300\" ind=\"  \">\n    <s c=\"a\">àºàº²àº™àº®àº±àºàºªàº²àº„àº§àº²àº¡àº‡àº²àº¡</s>\n  </f>\n  <f c=\"700\" ind=\" 1\">\n    <s c=\"a\">àºšàº»àº§à»„àº‚ à»€àºàº±àº‡àºàº°àºˆàº±àº™</s>\n    <s c=\"4\">070</s>\n    <s c=\"f\">13102006</s>\n  </f>\n  <f c=\"210\" ind=\"  \">\n    <s c=\"c\">àº›àº²àºàº›àº²àºªàº±àºàºàº²àº™àºàº´àº¡</s>\n    <s c=\"a\">àºàº³à»àºàº‡àº™àº°àº„àº­àº™</s>\n  </f>\n</notice>\n','005472001161679380',17),(42,'<notice>\n  <rs>n</rs>\n  <dt>a</dt>\n  <bl>m</bl>\n  <hl>*</hl>\n  <el>1</el>\n  <ru>i</ru>\n  <f c=\"001\" >16</f>\n  <f c=\"100\" ind=\"  \">\n    <s c=\"a\">20061024u        u  u0frey0103    ba</s>\n  </f>\n  <f c=\"200\" ind=\"1 \">\n    <s c=\"a\">àº•àº³àº¥àº²àº¢àº²àºàº·àº™à»€àº¡àº·àº­àº‡</s>\n  </f>\n  <f c=\"010\" ind=\"  \">\n    <s c=\"d\">12500àºàºµàºš</s>\n  </f>\n  <f c=\"101\" ind=\"0 \">\n    <s c=\"a\">lao</s>\n  </f>\n  <f c=\"215\" ind=\"  \">\n    <s c=\"a\">125à»œà»‰àº²</s>\n  </f>\n  <f c=\"300\" ind=\"  \">\n    <s c=\"a\">àº•àº³àº¥àº²àº¢àº²àºàº·àº™à»€àº¡àº·àº­àº‡ àº—àºµà»ˆàº¡àºµàº„àº¸àº™àº›àº°à»‚àº«àºàº”àº—àº²àº‡àºàº²àº™à»àºàº”</s>\n  </f>\n  <f c=\"700\" ind=\" 1\">\n    <s c=\"a\">àºšàº¸àº™àºªàºµ àºšàº¹àº¥àº»àº¡</s>\n    <s c=\"4\">070</s>\n    <s c=\"f\">13102006</s>\n  </f>\n  <f c=\"210\" ind=\"  \">\n    <s c=\"c\">àº™àº°àº„àº­àº™àº«àº¥àº§àº‡</s>\n    <s c=\"a\">àºàº³à»àºàº‡àº™àº°àº„àº­àº™</s>\n    <s c=\"d\">2000</s>\n  </f>\n</notice>\n','005472001161679380',16),(41,'<notice>\n  <rs>n</rs>\n  <dt>a</dt>\n  <bl>m</bl>\n  <hl>*</hl>\n  <el>1</el>\n  <ru>i</ru>\n  <f c=\"001\" >15</f>\n  <f c=\"100\" ind=\"  \">\n    <s c=\"a\">20061024u        u  u0frey0103    ba</s>\n  </f>\n  <f c=\"200\" ind=\"1 \">\n    <s c=\"a\">àº®àº´àº”àº„àº­àº‡àº›àº°à»€àºàº™àºµàº¥àº²àº§ 2</s>\n  </f>\n  <f c=\"010\" ind=\"  \">\n    <s c=\"d\">34000àºàºµàºš</s>\n  </f>\n  <f c=\"101\" ind=\"0 \">\n    <s c=\"a\">lao</s>\n  </f>\n  <f c=\"215\" ind=\"  \">\n    <s c=\"a\">35à»œà»‰àº²</s>\n  </f>\n  <f c=\"330\" ind=\"  \">\n    <s c=\"a\">àº®àº´àº”àº„àº­àº‡àº›àº°à»€àºàº™àºµàº¥àº²àº§ </s>\n  </f>\n  <f c=\"700\" ind=\" 1\">\n    <s c=\"a\">àº„àº°àº™àº°àº­àº±àºàºªàº­àº™àºªàº²àº” àº¡/àºŠ</s>\n    <s c=\"4\">070</s>\n    <s c=\"f\">13102006</s>\n  </f>\n  <f c=\"210\" ind=\"  \">\n    <s c=\"c\">àºªàº³àº™àº±àºàºàº´àº¡à»àº¥àº°àºˆàº³à»œà»ˆàº²àºàº›àº·àº¡</s>\n    <s c=\"a\">àºàº³à»àºàº‡àº™àº°àº„àº­àº™</s>\n  </f>\n</notice>\n','005472001161679380',15),(40,'<notice>\n  <rs>n</rs>\n  <dt>a</dt>\n  <bl>m</bl>\n  <hl>*</hl>\n  <el>1</el>\n  <ru>i</ru>\n  <f c=\"001\" >14</f>\n  <f c=\"100\" ind=\"  \">\n    <s c=\"a\">20061024u        u  u0frey0103    ba</s>\n  </f>\n  <f c=\"200\" ind=\"1 \">\n    <s c=\"a\">àº„àº»àº™àº„àº§à»‰àº²àº§àº´àº—àº°àºàº²àºªàº²àº”àº—àº²àº‡àº”à»‰àº²àº™àº§àº´àºŠàº²àºàº²àº™à»àºàº”</s>\n  </f>\n  <f c=\"010\" ind=\"  \">\n    <s c=\"d\">78000àºàºµàºš</s>\n  </f>\n  <f c=\"101\" ind=\"1 \">\n    <s c=\"a\">lao</s>\n    <s c=\"c\">lao</s>\n  </f>\n  <f c=\"215\" ind=\"  \">\n    <s c=\"a\">785à»œà»‰àº²</s>\n  </f>\n  <f c=\"700\" ind=\" 1\">\n    <s c=\"a\">àº„àº³àºœàº²àº àºšàº¸àºšàºœàº²</s>\n    <s c=\"4\">070</s>\n    <s c=\"f\">13102006</s>\n  </f>\n  <f c=\"210\" ind=\"  \">\n    <s c=\"c\">àº‚àº­àº™à»àºà»ˆàº™</s>\n    <s c=\"a\">àº‚àº­àº™à»àºà»ˆàº™</s>\n  </f>\n</notice>\n','005472001161679380',14),(39,'<notice>\n  <rs>n</rs>\n  <dt>a</dt>\n  <bl>m</bl>\n  <hl>*</hl>\n  <el>1</el>\n  <ru>i</ru>\n  <f c=\"001\" >13</f>\n  <f c=\"100\" ind=\"  \">\n    <s c=\"a\">20061024u        u  u0frey0103    ba</s>\n  </f>\n  <f c=\"200\" ind=\"1 \">\n    <s c=\"a\">à»àº™àº§àº—àº²àº‡àºàº²àº™àº”àº³à»€àº™àºµàº™àº‡àº²àº™àºªàº³àº¥àº±àºšàº„àº°àº™àº°àºàº³àº¡àº°àºàº²àº™</s>\n  </f>\n  <f c=\"010\" ind=\"  \">\n    <s c=\"d\">8000 àºàºµàºš</s>\n  </f>\n  <f c=\"101\" ind=\"1 \">\n    <s c=\"a\">lao</s>\n    <s c=\"c\">lao</s>\n  </f>\n  <f c=\"215\" ind=\"  \">\n    <s c=\"a\">96à»œà»‰àº²</s>\n  </f>\n  <f c=\"710\" ind=\" 1\">\n    <s c=\"a\">àº­àº»àº‡àºàº²àº™àº­àº°àº™àº²à»„àº¡à»‚àº¥àº</s>\n    <s c=\"4\">070</s>\n    <s c=\"f\">13102006</s>\n  </f>\n  <f c=\"210\" ind=\"  \">\n    <s c=\"c\">àºªàº°àºàº²àº™àº—àº­àº‡àºàº²àº™àºàº´àº¡</s>\n    <s c=\"a\">àºàº³à»àºàº‡àº™àº°àº„àº­àº™</s>\n  </f>\n</notice>\n','005472001161679380',13),(38,'<notice>\n  <rs>n</rs>\n  <dt>a</dt>\n  <bl>m</bl>\n  <hl>*</hl>\n  <el>1</el>\n  <ru>i</ru>\n  <f c=\"001\" >12</f>\n  <f c=\"100\" ind=\"  \">\n    <s c=\"a\">20061024u        u  u0frey0103    ba</s>\n  </f>\n  <f c=\"200\" ind=\"1 \">\n    <s c=\"a\">àº®àº´àº”àº„àº­àº‡àº›àº°à»€àºàº™àºµàº¥àº²àº§</s>\n  </f>\n  <f c=\"010\" ind=\"  \">\n    <s c=\"d\">5800àºàºµàºš</s>\n  </f>\n  <f c=\"101\" ind=\"0 \">\n    <s c=\"a\">lao</s>\n  </f>\n  <f c=\"215\" ind=\"  \">\n    <s c=\"a\">67à»œà»‰àº²</s>\n  </f>\n  <f c=\"700\" ind=\" 1\">\n    <s c=\"a\">àºªàº¸à»€àº™àº” à»‚àºàº—àº´àºªàº²àº™</s>\n    <s c=\"4\">070</s>\n    <s c=\"f\">13102006</s>\n  </f>\n  <f c=\"210\" ind=\"  \">\n    <s c=\"c\">à»‚àº®àº‡àºàº´àº¡àºªàº¶àºàºªàº²</s>\n    <s c=\"a\">àºªàºµàºªàº°àº•àº°àº™àº²àº”</s>\n  </f>\n</notice>\n','005472001161679380',12),(37,'<notice>\n  <rs>n</rs>\n  <dt>a</dt>\n  <bl>m</bl>\n  <hl>*</hl>\n  <el>1</el>\n  <ru>i</ru>\n  <f c=\"001\" >11</f>\n  <f c=\"100\" ind=\"  \">\n    <s c=\"a\">20061024u        u  u0frey0103    ba</s>\n  </f>\n  <f c=\"200\" ind=\"1 \">\n    <s c=\"a\">àºàº»àº”à»àº²àºàº›à»ˆàº²à»„àº¡à»‰</s>\n  </f>\n  <f c=\"010\" ind=\"  \">\n    <s c=\"d\">700000àºàºµàºš</s>\n  </f>\n  <f c=\"101\" ind=\"1 \">\n    <s c=\"a\">lao</s>\n    <s c=\"c\">lao</s>\n  </f>\n  <f c=\"215\" ind=\"  \">\n    <s c=\"a\">156à»œà»‰àº²</s>\n  </f>\n  <f c=\"710\" ind=\" 1\">\n    <s c=\"a\">àºàº»àº¡àº›à»ˆàº²à»„àº¡à»‰</s>\n    <s c=\"4\">070</s>\n    <s c=\"f\">13102006</s>\n  </f>\n  <f c=\"210\" ind=\"  \">\n    <s c=\"c\">àº™àº°àº„àº­àº™àº«àº¥àº§àº‡</s>\n    <s c=\"a\">àºàº³à»àºàº‡àº™àº°àº„àº­àº™</s>\n  </f>\n</notice>\n','005472001161679380',11),(36,'<notice>\n  <rs>n</rs>\n  <dt>a</dt>\n  <bl>m</bl>\n  <hl>*</hl>\n  <el>1</el>\n  <ru>i</ru>\n  <f c=\"001\" >10</f>\n  <f c=\"100\" ind=\"  \">\n    <s c=\"a\">20061024u        u  u0frey0103    ba</s>\n  </f>\n  <f c=\"200\" ind=\"1 \">\n    <s c=\"a\">àºàº²àº™àº›àº½àºšàº—àº½àºšàºœàº»àº™àºªàº»àº¡àº—àº²àº‡àº”à»‰àº²àº™àº„àº°àº™àº´àº”àºªàº²àº”</s>\n  </f>\n  <f c=\"010\" ind=\"  \">\n    <s c=\"d\">20000 àºàºµàºš</s>\n  </f>\n  <f c=\"101\" ind=\"1 \">\n    <s c=\"a\">lao</s>\n    <s c=\"c\">lao</s>\n  </f>\n  <f c=\"215\" ind=\"  \">\n    <s c=\"a\">65à»œà»‰àº²</s>\n  </f>\n  <f c=\"700\" ind=\" 1\">\n    <s c=\"a\">àºšàº¸àº™àºªàºµ àºšàº¹àº¥àº»àº¡</s>\n    <s c=\"4\">070</s>\n    <s c=\"f\">13102006</s>\n  </f>\n  <f c=\"210\" ind=\"  \">\n    <s c=\"c\">àºªàº³àº™àº±àºàºàº´àº¡à»àº¥àº°àºˆàº³à»œà»ˆàº²àºàº›àº·àº¡</s>\n    <s c=\"a\">àºàº³à»àºàº‡àº™àº°àº„àº­àº™</s>\n  </f>\n</notice>\n','005472001161679380',10),(35,'<notice>\n  <rs>n</rs>\n  <dt>a</dt>\n  <bl>m</bl>\n  <hl>*</hl>\n  <el>1</el>\n  <ru>i</ru>\n  <f c=\"001\" >9</f>\n  <f c=\"100\" ind=\"  \">\n    <s c=\"a\">20061024u        u  u0frey0103    ba</s>\n  </f>\n  <f c=\"200\" ind=\"1 \">\n    <s c=\"a\">àº›àº°àº«àº§àº±àº”àºªàº²àº”àº¥àº²àº§ 1946</s>\n  </f>\n  <f c=\"010\" ind=\"  \">\n    <s c=\"d\">200000 àºàºµàºš</s>\n  </f>\n  <f c=\"101\" ind=\"1 \">\n    <s c=\"a\">lao</s>\n    <s c=\"c\">lao</s>\n  </f>\n  <f c=\"215\" ind=\"  \">\n    <s c=\"a\">852à»œà»‰àº²</s>\n    <s c=\"c\">àº¡àºµàºàº²àºšàº›àº°àºàº­àºš</s>\n  </f>\n  <f c=\"700\" ind=\" 1\">\n    <s c=\"a\">àºªàº¸àºˆàº´àº” àº§àº»àº‡à»€àº—àºš</s>\n    <s c=\"4\">070</s>\n    <s c=\"f\">13102006</s>\n  </f>\n  <f c=\"210\" ind=\"  \">\n    <s c=\"c\">àº­àº»àº‡àºàº²àº™àº­àº°àº™àº²à»„àº¡à»‚àº¥àº</s>\n    <s c=\"a\">àºàº³à»àºàº‡àº™àº°àº„àº­àº™</s>\n  </f>\n</notice>\n','005472001161679380',9),(34,'<notice>\n  <rs>n</rs>\n  <dt>a</dt>\n  <bl>m</bl>\n  <hl>*</hl>\n  <el>1</el>\n  <ru>i</ru>\n  <f c=\"001\" >8</f>\n  <f c=\"100\" ind=\"  \">\n    <s c=\"a\">20061024u        u  u0frey0103    ba</s>\n  </f>\n  <f c=\"200\" ind=\"1 \">\n    <s c=\"a\">àº—à»‰àº²àº§àºªàº¸àº£àº°àº™àº²àº¥àºµ àºšàº²àº‡àº—àº±àº”àºªàº°àº™àº°àº‚àº­àº‡àº„àº»àº™à»„àº—</s>\n  </f>\n  <f c=\"010\" ind=\"  \">\n    <s c=\"d\">5000 àºàºµàºš</s>\n  </f>\n  <f c=\"101\" ind=\"1 \">\n    <s c=\"a\">lao</s>\n    <s c=\"c\">lao</s>\n  </f>\n  <f c=\"215\" ind=\"  \">\n    <s c=\"a\">68à»œà»‰àº²</s>\n    <s c=\"c\">àº¡àºµàºàº²àºšàº›àº°àºàº­àºš</s>\n  </f>\n  <f c=\"700\" ind=\" 1\">\n    <s c=\"a\">àº„àº³àºœàº²àº àºšàº¸àºšàºœàº²</s>\n    <s c=\"4\">070</s>\n    <s c=\"f\">13102006</s>\n  </f>\n  <f c=\"210\" ind=\"  \">\n    <s c=\"c\">àº›àº²àºàº›àº²àºªàº±àºàºàº²àº™àºàº´àº¡</s>\n    <s c=\"a\">àºàº³à»àºàº‡àº™àº°àº„àº­àº™</s>\n  </f>\n</notice>\n','005472001161679380',8),(33,'<notice>\n  <rs>n</rs>\n  <dt>a</dt>\n  <bl>m</bl>\n  <hl>*</hl>\n  <el>1</el>\n  <ru>i</ru>\n  <f c=\"001\" >7</f>\n  <f c=\"100\" ind=\"  \">\n    <s c=\"a\">20061024u        u  u0frey0103    ba</s>\n  </f>\n  <f c=\"200\" ind=\"1 \">\n    <s c=\"a\">àºªàº°àºàº¸àº™àº•àº»à»‰àº™àº”àº­àºà»€àºœàº´à»‰àº‡àº‚àº­àº‡àº›àº°à»€àº—àº”à»„àº—,àº¥àº²àº§</s>\n  </f>\n  <f c=\"010\" ind=\"  \">\n    <s c=\"d\">7500 àºàºµàºš</s>\n  </f>\n  <f c=\"101\" ind=\"1 \">\n    <s c=\"a\">lao</s>\n    <s c=\"c\">lao</s>\n  </f>\n  <f c=\"215\" ind=\"  \">\n    <s c=\"a\">450à»œà»‰àº²</s>\n    <s c=\"c\">àº¡àºµàºàº²àºšàº›àº°àºàº­àºš</s>\n  </f>\n  <f c=\"700\" ind=\" 1\">\n    <s c=\"a\">àºšàº¸àº™àº¡àºµ à»€àº—àºšàºªàºµà»€àº¡àº·àº­àº‡</s>\n    <s c=\"4\">070</s>\n    <s c=\"f\">13102006</s>\n  </f>\n  <f c=\"210\" ind=\"  \">\n    <s c=\"c\">àºàº¸àº‡à»€àº—àºš</s>\n    <s c=\"a\">àºàº¸àº‡à»€àº—àºš</s>\n    <s c=\"d\">20004</s>\n  </f>\n</notice>\n','005472001161679380',7),(32,'<notice>\n  <rs>n</rs>\n  <dt>a</dt>\n  <bl>m</bl>\n  <hl>*</hl>\n  <el>1</el>\n  <ru>i</ru>\n  <f c=\"001\" >6</f>\n  <f c=\"100\" ind=\"  \">\n    <s c=\"a\">20061024u        u  u0frey0103    ba</s>\n  </f>\n  <f c=\"200\" ind=\"1 \">\n    <s c=\"a\">àºàº²àºšà»€àº¡àº·àº­àº‡àºàº§àº™</s>\n  </f>\n  <f c=\"010\" ind=\"  \">\n    <s c=\"d\">13000àºàºµàºš</s>\n  </f>\n  <f c=\"101\" ind=\"1 \">\n    <s c=\"a\">lao</s>\n    <s c=\"c\">lao</s>\n  </f>\n  <f c=\"215\" ind=\"  \">\n    <s c=\"a\">51à»œà»‰àº²</s>\n    <s c=\"c\">àº¡àºµàºàº²àºšàº›àº°àºàº­àºš</s>\n  </f>\n  <f c=\"700\" ind=\" 1\">\n    <s c=\"a\">àºšàº»àº§à»„àº‚ à»€àºàº±àº‡àºàº°àºˆàº±àº™</s>\n    <s c=\"4\">070</s>\n    <s c=\"f\">13102006</s>\n  </f>\n  <f c=\"210\" ind=\"  \">\n    <s c=\"c\">àºªàºµàºªàº°àº«àº§àº²àº”àºàº²àº™àºàº´àº¡</s>\n    <s c=\"a\">àºàº³à»àºàº‡àº™àº°àº„àº­àº™</s>\n  </f>\n</notice>\n','005472001161679380',6),(31,'<notice>\n  <rs>n</rs>\n  <dt>a</dt>\n  <bl>m</bl>\n  <hl>*</hl>\n  <el>1</el>\n  <ru>i</ru>\n  <f c=\"001\" >5</f>\n  <f c=\"100\" ind=\"  \">\n    <s c=\"a\">20061024u        u  u0frey0103    ba</s>\n  </f>\n  <f c=\"200\" ind=\"1 \">\n    <s c=\"a\">àº§àº´àº¥àº°àºàº³à»€àºˆàº»à»‰àº²àº­àº²àº™àº¸</s>\n  </f>\n  <f c=\"010\" ind=\"  \">\n    <s c=\"d\">170000àºàºµàºš</s>\n  </f>\n  <f c=\"101\" ind=\"1 \">\n    <s c=\"a\">lao</s>\n    <s c=\"c\">lao</s>\n  </f>\n  <f c=\"215\" ind=\"  \">\n    <s c=\"a\">900à»œà»‰àº²</s>\n    <s c=\"c\">àº¡àºµàºàº²àºšàº›àº°àºàº­àºš</s>\n  </f>\n  <f c=\"700\" ind=\" 1\">\n    <s c=\"a\">àºªàº¸à»€àº™àº” à»‚àºàº—àº´àºªàº²àº™</s>\n    <s c=\"4\">070</s>\n    <s c=\"f\">13102006</s>\n  </f>\n  <f c=\"210\" ind=\"  \">\n    <s c=\"c\">àºªàº°àº–àº²àºšàº±àº™</s>\n    <s c=\"a\">àºàº³à»àºàº‡àº™àº°àº„àº­àº™</s>\n  </f>\n</notice>\n','005472001161679380',5),(30,'<notice>\n  <rs>n</rs>\n  <dt>a</dt>\n  <bl>m</bl>\n  <hl>*</hl>\n  <el>1</el>\n  <ru>i</ru>\n  <f c=\"001\" >4</f>\n  <f c=\"100\" ind=\"  \">\n    <s c=\"a\">20061024u        u  u0frey0103    ba</s>\n  </f>\n  <f c=\"200\" ind=\"1 \">\n    <s c=\"a\">àº„àº­àº‡à»àºªàº™à»àºªàºšàº¢à»ˆàº²àºŠà»à»‰àº²àº®àº­àº</s>\n  </f>\n  <f c=\"010\" ind=\"  \">\n    <s c=\"d\">82000 àºàºµàºš</s>\n  </f>\n  <f c=\"101\" ind=\"1 \">\n    <s c=\"a\">lao</s>\n    <s c=\"c\">lao</s>\n  </f>\n  <f c=\"215\" ind=\"  \">\n    <s c=\"a\">53à»œà»‰àº²</s>\n    <s c=\"c\">àº¡àºµàºàº²àºšàº›àº°àºàº­àºš</s>\n  </f>\n  <f c=\"710\" ind=\" 1\">\n    <s c=\"a\">àºªàº°àº–àº²àºšàº±àº™àº„àº»àº™àº„àº§à»‰àº²àº§àº±àº”àº—àº°àº™àº°àº—àº³</s>\n    <s c=\"4\">070</s>\n    <s c=\"f\">13102006</s>\n  </f>\n  <f c=\"210\" ind=\"  \">\n    <s c=\"c\">àºªàº°àº–àº²àºšàº±àº™</s>\n    <s c=\"a\">àºàº³à»àºàº‡àº™àº°àº„àº­àº™</s>\n    <s c=\"d\">2000</s>\n  </f>\n</notice>\n','005472001161679380',4),(29,'<notice>\n  <rs>n</rs>\n  <dt>a</dt>\n  <bl>m</bl>\n  <hl>*</hl>\n  <el>1</el>\n  <ru>i</ru>\n  <f c=\"001\" >3</f>\n  <f c=\"100\" ind=\"  \">\n    <s c=\"a\">20061024u        u  u0frey0103    ba</s>\n  </f>\n  <f c=\"200\" ind=\"1 \">\n    <s c=\"a\">à»€àº¡àº·à»ˆàº­àº‚à»‰àº­àºàº›àº´àº”àºªàº°à»àº¸àº”àºšàº±àº™àº—àº¶àº</s>\n  </f>\n  <f c=\"010\" ind=\"  \">\n    <s c=\"d\">96000àºàºµàºš</s>\n  </f>\n  <f c=\"101\" ind=\"1 \">\n    <s c=\"a\">lao</s>\n    <s c=\"c\">lao</s>\n  </f>\n  <f c=\"215\" ind=\"  \">\n    <s c=\"a\">277 à»à»‰àº²</s>\n  </f>\n  <f c=\"700\" ind=\" 1\">\n    <s c=\"a\">àº”àº³àº”àº§àº™ àºàº»àº¡àº”àº§àº‡àºªàºµ</s>\n    <s c=\"4\">070</s>\n    <s c=\"f\">13102006</s>\n  </f>\n  <f c=\"210\" ind=\"  \">\n    <s c=\"c\">à»‚àº®àº‡àºàº´àº¡à»àº«à»ˆàº‡àº¥àº±àº”</s>\n    <s c=\"a\">àºªàºµà»‚àº„àº”àº•àº°àºšàº­àº‡</s>\n    <s c=\"d\">2002</s>\n  </f>\n</notice>\n','005472001161679380',3),(28,'<notice>\n  <rs>n</rs>\n  <dt>a</dt>\n  <bl>m</bl>\n  <hl>*</hl>\n  <el>1</el>\n  <ru>i</ru>\n  <f c=\"001\" >2</f>\n  <f c=\"100\" ind=\"  \">\n    <s c=\"a\">20061024u        u  u0frey0103    ba</s>\n  </f>\n  <f c=\"200\" ind=\"1 \">\n    <s c=\"a\">àºàº»àº‡àºªàº²àº§àº°àº”àº²àº™àº¥àº²àº§ à»€àº–àº´àº‡ 1946</s>\n  </f>\n  <f c=\"010\" ind=\"  \">\n    <s c=\"d\">9600àºàºµàºš</s>\n  </f>\n  <f c=\"101\" ind=\"1 \">\n    <s c=\"a\">lao</s>\n    <s c=\"c\">lao</s>\n  </f>\n  <f c=\"215\" ind=\"  \">\n    <s c=\"a\">83 à»à»‰àº²</s>\n  </f>\n  <f c=\"700\" ind=\" 1\">\n    <s c=\"a\">àºªàºµàº¥àº² àº§àº´àº¥àº°àº§àº»àº‡</s>\n    <s c=\"4\">070</s>\n  </f>\n  <f c=\"210\" ind=\"  \">\n    <s c=\"c\">à»‚àº®àº‡àºàº´àº¡àº¡àº±àº™àº—àº²àº•àº¸àº¥àº²àº”</s>\n    <s c=\"a\">àºàº³à»àºàº‡àº™àº°àº„àº­àº™</s>\n    <s c=\"d\">2001</s>\n  </f>\n</notice>\n','005472001161679380',2),(27,'<notice>\n  <rs>n</rs>\n  <dt>a</dt>\n  <bl>m</bl>\n  <hl>*</hl>\n  <el>1</el>\n  <ru>i</ru>\n  <f c=\"001\" >1</f>\n  <f c=\"100\" ind=\"  \">\n    <s c=\"a\">20061024u        u  u0frey0103    ba</s>\n  </f>\n  <f c=\"200\" ind=\"1 \">\n    <s c=\"a\">àºŠàºµàº§àº´àº” à»àº¥àº° àºœàº»àº™àº‡àº²àº™àº‚àº­àº‡àºàº£àº°àº¡àº°àº«àº²à»€àº–àº£àº°5 àº­àº»àº‡</s>\n  </f>\n  <f c=\"010\" ind=\"  \">\n    <s c=\"d\">7000 àºàºµàºš</s>\n  </f>\n  <f c=\"101\" ind=\"1 \">\n    <s c=\"a\">lao</s>\n    <s c=\"c\">lao</s>\n  </f>\n  <f c=\"215\" ind=\"  \">\n    <s c=\"a\">52 à»œà»‰àº²</s>\n  </f>\n  <f c=\"700\" ind=\" 1\">\n    <s c=\"a\">àº„àº°àº™àº°àº­àº±àºàºªàº­àº™àºªàº²àº” àº¡/àºŠ</s>\n    <s c=\"4\">070</s>\n    <s c=\"f\">13102006</s>\n  </f>\n  <f c=\"210\" ind=\"  \">\n    <s c=\"c\">àº™àº°àº„àº­àº™àº«àº¥àº§àº‡</s>\n    <s c=\"a\">àºàº³à»àºàº‡àº™àº°àº„àº­àº™</s>\n    <s c=\"d\">2001</s>\n  </f>\n  <f c=\"676\" ind=\"  \">\n    <s c=\"a\">050</s>\n  </f>\n</notice>\n','005472001161679380',1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `import_marc` ENABLE KEYS */;

--
-- Table structure for table `indexint`
--

DROP TABLE IF EXISTS `indexint`;
CREATE TABLE `indexint` (
  `indexint_id` mediumint(8) unsigned NOT NULL auto_increment,
  `indexint_name` varchar(255) NOT NULL default '',
  `indexint_comment` text,
  `index_indexint` text,
  PRIMARY KEY  (`indexint_id`),
  UNIQUE KEY `indexint_name` (`indexint_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `indexint`
--


/*!40000 ALTER TABLE `indexint` DISABLE KEYS */;
LOCK TABLES `indexint` WRITE;
INSERT INTO `indexint` VALUES (1,'000','àº‚à»à»‰àº¡àº¹àº™ àºàº²àº™àº•àº´àº”àº•à»à»ˆàºŠàº·à»ˆàºªàº²àº™',' 000 '),(2,'010','àº„àº§àº²àº¡àº®àº¹à»‰àºà»ˆàº½àº§àºàº±àºšàº«à»àºªàº°à»àº¸àº”',' 010 àº„àº§àº²àº¡àº®àº¹à»‰àºà»ˆàº½àº§àºàº±àºšàº«à»àºªàº°à»àº¸àº” '),(3,'020','àº«à»àºªàº°à»àº¸àº” - à»àº¥àº°àºœàº¹à»‰àº­à»ˆàº²àº™, à»€àº­àºàº°àºªàº²àº™',' 020 àº«à»àºªàº°à»àº¸àº” - à»àº¥àº°àºœàº¹à»‰àº­à»ˆàº²àº™, à»€àº­àºàº°àºªàº²àº™ '),(6,'050','àº§àº²àº¥àº°àºªàº²àº™àº—àº»à»ˆàº§à»„àº› - àº¥àº²àºàº›àºµ',' 050 àº§àº²àº¥àº°àºªàº²àº™àº—àº»à»ˆàº§à»„àº› - àº¥àº²àºàº›àºµ '),(11,'100','àº›àº±àº”àºŠàº°àºàº²',' 100 àº›àº±àº”àºŠàº°àºàº² '),(31,'300','àº§àº´àº—àº°àºàº²àºªàº²àº”àºªàº±àº‡àº„àº»àº¡',' 300 àº§àº´àº—àº°àºàº²àºªàº²àº”àºªàº±àº‡àº„àº»àº¡ '),(32,'310','àºªàº°àº–àº´àº•àº´',' 310 àºªàº°àº–àº´àº•àº´ '),(33,'320','àºàº²àº™à»€àº¡àº·àº­àº‡',' 320 àºàº²àº™à»€àº¡àº·àº­àº‡ '),(35,'340','àºàº»àº”à»œàº²àº',' 340 àºàº»àº”à»œàº²àº '),(36,'350','àºàº²àº™àº„àº¹à»‰àº¡àº„àº­àº‡',' 350 àºàº²àº™àº„àº¹à»‰àº¡àº„àº­àº‡ '),(38,'370','àºàº²àº™àºªàº¶àºàºªàº²',' 370 àºàº²àº™àºªàº¶àºàºªàº² '),(41,'400','àºàº²àºªàº²',' 400 àºàº²àºªàº² '),(43,'420','àºàº²àºªàº² àº­àº±àº‡àºàº´àº”',' 420 àºàº²àºªàº² àº­àº±àº‡àºàº´àº” '),(44,'430','àºàº²àºªàº² à»€àº¢àºàº¥àº°àº¡àº±àº™',' 430 àºàº²àºªàº² à»€àº¢àºàº¥àº°àº¡àº±àº™ '),(45,'440','àºàº²àºªàº²àºàº£àº±à»ˆàº‡ - (àº§àº±àº”àºˆàº°àº™àº²àº™àº¸àºàº»àº¡, à»„àº§àºàº²àºàº­àº™)',' 440 àºàº²àºªàº²àºàº£àº±à»ˆàº‡ - (àº§àº±àº”àºˆàº°àº™àº²àº™àº¸àºàº»àº¡, à»„àº§àºàº²àºàº­àº™) '),(46,'450','àºàº²àºªàº² àº­àºµà»ˆàº•à»ˆàº²àº¥àºµà»‰',' 450 àºàº²àºªàº² àº­àºµà»ˆàº•à»ˆàº²àº¥àºµà»‰ '),(47,'460','àºàº²àºªàº² à»àº­àº±àº”àºªàº°àº›à»ˆàº²àº ',' 460 àºàº²àºªàº² à»àº­àº±àº”àºªàº°àº›à»ˆàº²àº  '),(48,'470','àºàº²àºªàº² àº¥à»ˆàº²à»àº•à»ˆàº‡',' 470 àºàº²àºªàº² àº¥à»ˆàº²à»àº•à»ˆàº‡ '),(49,'480','àºàº²àºªàº² àºàº°à»€àº¥àº±àº',' 480 àºàº²àºªàº² àºàº°à»€àº¥àº±àº '),(51,'500','àº§àº´àº—àº°àºàº²àºªàº²àº”',' 500 àº§àº´àº—àº°àºàº²àºªàº²àº” '),(52,'510','à»€àº¥àº',' 510 à»€àº¥àº '),(54,'530','àºŸàºµàºŠàº´àº',' 530 àºŸàºµàºŠàº´àº '),(61,'600','à»€àº•àº±àºàº™àº´àº\r\n',' 600 à»€àº•àº±àºàº™àº´àº\r\n '),(4,'030','EncyclopÃ©dies gÃ©nÃ©rales',' 030 encyclopedies generales '),(5,'040','X',' 040 x '),(7,'060','Organisations gÃ©nÃ©rales - congrÃ¨s',' 060 organisations generales congres '),(8,'070','Presse Edition',' 070 presse edition '),(9,'080','Recueils - mÃ©langes, discours',' 080 recueils melanges discours '),(10,'090','Manuscrits Livres rares',' 090 manuscrits livres rares '),(12,'110','MÃ©taphysique',' 110 metaphysique '),(13,'120','Connaissance',' 120 connaissance '),(14,'130','Parapsychologie - astrologie, graphologie',' 130 parapsychologie astrologie graphologie '),(15,'140','SystÃ¨mes philosophiques',' 140 systemes philosophiques '),(16,'150','Psychologie',' 150 psychologie '),(17,'160','Logique',' 160 logique '),(18,'170','Morale - ethique',' 170 morale ethique '),(19,'180','Philosophes anciens - et orientaux',' 180 philosophes anciens orientaux '),(20,'190','Philosophes modernes - (XVIe S. Ã  nos jours)',' 190 philosophes modernes xvie s nos jours '),(21,'200','Religion',' 200 religion '),(22,'210','Religion naturelle',' 210 religion naturelle '),(23,'220','Bible Evangiles',' 220 bible evangiles '),(24,'230','ThÃ©ologie doctrinale chrÃ©tienne - (dogme)',' 230 theologie doctrinale chretienne dogme '),(25,'240','ThÃ©ologie spirituelle - vie religieuse',' 240 theologie spirituelle vie religieuse '),(26,'250','ThÃ©ologie pastorale',' 250 theologie pastorale '),(27,'260','L\'Eglise chrÃ©tienne et la sociÃ©tÃ©',' 260 eglise chretienne societe '),(28,'270','Histoire de l\'Eglise chrÃ©tienne',' 270 histoire eglise chretienne '),(29,'280','Autres confessions chrÃ©tiennes',' 280 autres confessions chretiennes '),(30,'290','Autres religions et mythologies',' 290 autres religions mythologies '),(34,'330','Economie - finances, production, consommation',' 330 economie finances production consommation '),(37,'360','Aide Assistance Secours',' 360 aide assistance secours '),(39,'380','Commerce Transports Communication',' 380 commerce transports communication '),(40,'390','Costumes et folklore',' 390 costumes folklore '),(42,'410','Linguistique',' 410 linguistique '),(50,'490','Autres langues - russe, arabe, â€¦',' 490 autres langues russe arabe '),(53,'520','Astronomie',' 520 astronomie '),(55,'540','Chimie - minÃ©ralogie',' 540 chimie mineralogie '),(56,'550','Sciences de la Terre - gÃ©ologie, mÃ©tÃ©orologie',' 550 sciences terre geologie meteorologie '),(57,'560','PalÃ©ontologie - (les fossiles)',' 560 paleontologie fossiles '),(58,'570','Sciences de la vie - biologie, gÃ©nÃ©tique',' 570 sciences vie biologie genetique '),(59,'580','Botanique - (les plantes)',' 580 botanique plantes '),(60,'590','Zoologie - (les animaux)',' 590 zoologie animaux '),(62,'610','MÃ©decine - hygiÃ¨ne, santÃ©',' 610 medecine hygiene sante '),(63,'620','Techniques industrielles - mÃ©canique, Ã©lectricitÃ©, radio, Ã©nergieâ€¦',' 620 techniques industrielles mecanique electricite radio energie '),(64,'630','Agriculture - forÃªt, Ã©levage, pÃªche',' 630 agriculture foret elevage peche '),(65,'640','Arts mÃ©nagers - cuisine, coutÃ»re, soins de beautÃ©',' 640 arts menagers cuisine couture soins beaute '),(66,'650','Entreprise - travail de bureaux, vente, publicitÃ©',' 650 entreprise travail bureaux vente publicite '),(67,'660','Industries chimiques et alimentaires',' 660 industries chimiques alimentaires '),(68,'670','Fabrications industrielles - mÃ©tallurgie, bois, textile',' 670 fabrications industrielles metallurgie bois textile '),(69,'680','Articles manufacturÃ©s',' 680 articles manufactures '),(70,'690','BÃ¢timent - construction',' 690 batiment construction '),(71,'700','Arts et loisirs',' 700 arts loisirs '),(72,'710','Urbanisme - art du paysage',' 710 urbanisme art paysage '),(73,'720','Architecture',' 720 architecture '),(74,'730','Sculpture',' 730 sculpture '),(75,'740','Dessin - arts dÃ©coratifs',' 740 dessin arts decoratifs '),(76,'750','Peinture',' 750 peinture '),(77,'760','Arts graphiques - graphisme',' 760 arts graphiques graphisme '),(78,'770','Photographie',' 770 photographie '),(79,'780','Musique',' 780 musique '),(80,'790','Loisirs - spectacles, jeux, sports',' 790 loisirs spectacles jeux sports '),(81,'800','LittÃ©rature',' 800 litterature '),(82,'810','LittÃ©rature amÃ©ricaine',' 810 litterature americaine '),(83,'820','LittÃ©rature anglaise',' 820 litterature anglaise '),(84,'830','LittÃ©rature allemande',' 830 litterature allemande '),(85,'840','LittÃ©rature franÃ§aise',' 840 litterature francaise '),(86,'850','LittÃ©rature italienne',' 850 litterature italienne '),(87,'860','LittÃ©rature espagnole et portugaise',' 860 litterature espagnole portugaise '),(88,'870','LittÃ©rature latine',' 870 litterature latine '),(89,'880','LittÃ©rature grecque',' 880 litterature grecque '),(90,'890','Autres littÃ©ratures',' 890 autres litteratures '),(91,'900','Histoire gÃ©ographie',' 900 histoire geographie '),(92,'910','GÃ©ographie - voyages',' 910 geographie voyages '),(93,'920','Biographies - vie d\'un personnage, gÃ©nÃ©alogie',' 920 biographies vie personnage genealogie '),(94,'930','Histoire ancienne',' 930 histoire ancienne '),(95,'940','Histoire de l\'Europe',' 940 histoire europe '),(96,'950','Histoire de l\'Asie',' 950 histoire asie '),(97,'960','Histoire de l\'Afrique',' 960 histoire afrique '),(98,'970','Histoire de l\'AmÃ©rique du Nord',' 970 histoire amerique nord '),(99,'980','Histoire de l\'AmÃ©rique du Sud',' 980 histoire amerique sud '),(100,'990','Histoire de l\'OcÃ©anie',' 990 histoire oceanie ');
UNLOCK TABLES;
/*!40000 ALTER TABLE `indexint` ENABLE KEYS */;

--
-- Table structure for table `lenders`
--

DROP TABLE IF EXISTS `lenders`;
CREATE TABLE `lenders` (
  `idlender` smallint(5) unsigned NOT NULL auto_increment,
  `lender_libelle` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`idlender`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lenders`
--


/*!40000 ALTER TABLE `lenders` DISABLE KEYS */;
LOCK TABLES `lenders` WRITE;
INSERT INTO `lenders` VALUES (1,'à»€àº›àº±àº™àº‚àº­àº‡àº«à»‰àº­àº‡àºªàº°à»àº¸àº”'),(2,'à»€àº›àº±àº™àº‚àº­àº‡àº«à»‰àº­àº‡àºªàº°à»àº¸àº”àº—à»‰àº­àº‡àº–àº´à»ˆàº™');
UNLOCK TABLES;
/*!40000 ALTER TABLE `lenders` ENABLE KEYS */;

--
-- Table structure for table `liens_actes`
--

DROP TABLE IF EXISTS `liens_actes`;
CREATE TABLE `liens_actes` (
  `num_acte` int(8) unsigned NOT NULL default '0',
  `num_acte_lie` int(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`num_acte`,`num_acte_lie`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `liens_actes`
--


/*!40000 ALTER TABLE `liens_actes` DISABLE KEYS */;
LOCK TABLES `liens_actes` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `liens_actes` ENABLE KEYS */;

--
-- Table structure for table `lignes_actes`
--

DROP TABLE IF EXISTS `lignes_actes`;
CREATE TABLE `lignes_actes` (
  `id_ligne` int(15) unsigned NOT NULL auto_increment,
  `type_ligne` int(3) unsigned NOT NULL default '0',
  `num_acte` int(8) unsigned NOT NULL default '0',
  `lig_ref` int(15) unsigned NOT NULL default '0',
  `num_acquisition` int(12) unsigned NOT NULL default '0',
  `num_rubrique` int(8) unsigned NOT NULL default '0',
  `num_produit` int(8) unsigned NOT NULL default '0',
  `num_type` int(8) unsigned NOT NULL default '0',
  `libelle` text NOT NULL,
  `code` varchar(255) NOT NULL default '',
  `prix` float(8,2) unsigned NOT NULL default '0.00',
  `tva` float(8,2) unsigned NOT NULL default '0.00',
  `nb` int(5) unsigned NOT NULL default '1',
  `date_ech` date NOT NULL default '0000-00-00',
  `date_cre` date NOT NULL default '0000-00-00',
  `statut` int(3) unsigned NOT NULL default '0',
  `remise` float(8,2) NOT NULL default '0.00',
  PRIMARY KEY  (`id_ligne`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lignes_actes`
--


/*!40000 ALTER TABLE `lignes_actes` DISABLE KEYS */;
LOCK TABLES `lignes_actes` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `lignes_actes` ENABLE KEYS */;

--
-- Table structure for table `noeuds`
--

DROP TABLE IF EXISTS `noeuds`;
CREATE TABLE `noeuds` (
  `id_noeud` int(9) unsigned NOT NULL auto_increment,
  `autorite` varchar(255) NOT NULL default '',
  `num_parent` int(9) unsigned NOT NULL default '0',
  `num_renvoi_voir` int(9) unsigned NOT NULL default '0',
  `visible` char(1) NOT NULL default '1',
  `num_thesaurus` int(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_noeud`),
  KEY `num_parent` (`num_parent`),
  KEY `num_thesaurus` (`num_thesaurus`),
  KEY `autorite` (`autorite`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `noeuds`
--


/*!40000 ALTER TABLE `noeuds` DISABLE KEYS */;
LOCK TABLES `noeuds` WRITE;
INSERT INTO `noeuds` VALUES (1,'TOP',0,0,'0',1),(2484,'ORPHELINS',1,0,'0',1),(1378,'1377',1,0,'1',1),(1379,'1378',1,0,'1',1),(1380,'1379',1,0,'1',1),(1381,'1380',1,0,'1',1),(1382,'1381',1,0,'1',1),(1383,'1382',1,0,'1',1),(1384,'1383',1,0,'1',1),(1385,'1384',1,0,'1',1),(1386,'1385',1,0,'1',1),(1387,'1386',1,0,'1',1),(1388,'1387',1378,0,'1',1),(1389,'1388',1378,0,'1',1),(1390,'1389',1378,0,'1',1),(1391,'1390',1378,0,'1',1),(1392,'1391',1378,0,'1',1),(1393,'1392',1378,0,'1',1),(1394,'1393',1378,0,'1',1),(1395,'1394',1378,0,'1',1),(1396,'1395',1378,0,'1',1),(1397,'1396',1378,0,'1',1),(1398,'1397',1378,0,'1',1),(1399,'1398',1378,0,'1',1),(1400,'1399',1378,0,'1',1),(1401,'1400',1378,0,'1',1),(1402,'1401',1390,0,'1',1),(1403,'1402',1408,0,'1',1),(1404,'1403',1390,0,'1',1),(1405,'1404',1390,0,'1',1),(1406,'1405',1390,0,'1',1),(1407,'1406',1408,0,'1',1),(1408,'1407',1390,0,'1',1),(1409,'1408',1408,0,'1',1),(1410,'1409',1406,0,'1',1),(1411,'1410',1391,0,'1',1),(1412,'1411',1391,0,'1',1),(1413,'1412',1391,0,'1',1),(1414,'1413',1391,0,'1',1),(1415,'1414',1391,0,'1',1),(1416,'1415',1391,0,'1',1),(1417,'1416',1391,0,'1',1),(1418,'1417',1391,0,'1',1),(1419,'1418',1391,0,'1',1),(1420,'1419',1394,0,'1',1),(1421,'1420',2046,0,'1',1),(1422,'1421',2045,0,'1',1),(1423,'1422',2045,0,'1',1),(1424,'1423',2045,0,'1',1),(1425,'1424',2045,0,'1',1),(1426,'1425',2045,0,'1',1),(1427,'1426',2045,0,'1',1),(1428,'1427',2045,0,'1',1),(1429,'1428',2045,0,'1',1),(1430,'1429',2045,0,'1',1),(1431,'1430',1420,0,'1',1),(1432,'1431',1420,0,'1',1),(1433,'1432',1420,0,'1',1),(1434,'1433',1420,0,'1',1),(1435,'1434',1420,0,'1',1),(1436,'1435',1420,0,'1',1),(1437,'1436',1420,0,'1',1),(1438,'1437',1420,0,'1',1),(1439,'1438',1420,0,'1',1),(1440,'1439',1420,0,'1',1),(1441,'1440',1420,0,'1',1),(1442,'1441',2046,0,'1',1),(1443,'1442',1420,0,'1',1),(1444,'1443',1420,0,'1',1),(1445,'1444',2046,0,'1',1),(1446,'1445',1422,0,'1',1),(1447,'1446',1423,0,'1',1),(1448,'1447',1424,0,'1',1),(1449,'1448',1425,0,'1',1),(1450,'1449',1426,0,'1',1),(1451,'1450',1427,0,'1',1),(1452,'1451',1428,0,'1',1),(1453,'1452',1429,0,'1',1),(1454,'1453',1430,0,'1',1),(1455,'1454',2046,0,'1',1),(1456,'1455',1422,0,'1',1),(1457,'1456',1423,0,'1',1),(1458,'1457',1424,0,'1',1),(1459,'1458',1425,0,'1',1),(1460,'1459',1426,0,'1',1),(1461,'1460',1427,0,'1',1),(1462,'1461',1428,0,'1',1),(1463,'1462',1428,0,'1',1),(1464,'1463',1429,0,'1',1),(1465,'1464',1430,0,'1',1),(1466,'1465',2046,0,'1',1),(1467,'1466',1422,0,'1',1),(1468,'1467',1423,0,'1',1),(1469,'1468',1424,0,'1',1),(1470,'1469',1425,0,'1',1),(1471,'1470',1426,0,'1',1),(1472,'1471',1427,0,'1',1),(1473,'1472',1429,0,'1',1),(1474,'1473',1428,0,'1',1),(1475,'1474',1430,0,'1',1),(1476,'1475',1426,0,'1',1),(1477,'1476',1427,0,'1',1),(1478,'1477',1429,0,'1',1),(1479,'1478',1430,0,'1',1),(1480,'1479',1422,0,'1',1),(1481,'1480',1423,0,'1',1),(1482,'1481',1424,0,'1',1),(1483,'1482',1425,0,'1',1),(1484,'1483',2160,0,'1',1),(1485,'1484',2160,0,'1',1),(1486,'1485',2160,0,'1',1),(1487,'1486',2160,0,'1',1),(1488,'1487',2160,0,'1',1),(1489,'1488',2160,0,'1',1),(1490,'1489',2160,0,'1',1),(1491,'1490',1916,0,'1',1),(1492,'1491',1399,0,'1',1),(1493,'1492',1379,0,'1',1),(1494,'1493',1379,0,'1',1),(1495,'1494',1379,0,'1',1),(1496,'1495',1379,0,'1',1),(1497,'1496',1379,0,'1',1),(1498,'1497',1379,0,'1',1),(1499,'1498',1495,0,'1',1),(1500,'1499',1495,0,'1',1),(1501,'1500',1495,0,'1',1),(1502,'1501',1495,0,'1',1),(1503,'1502',1495,0,'1',1),(1504,'1503',1495,0,'1',1),(1505,'1504',1495,0,'1',1),(1506,'1505',1382,0,'1',1),(1507,'1506',1495,0,'1',1),(1508,'1507',1495,0,'1',1),(1509,'1508',1495,0,'1',1),(1510,'1509',1495,0,'1',1),(1511,'1510',1495,0,'1',1),(1512,'1511',1497,0,'1',1),(1513,'1512',1497,0,'1',1),(1514,'1513',1497,0,'1',1),(1515,'1514',1380,0,'1',1),(1516,'1515',1380,0,'1',1),(1517,'1516',1380,0,'1',1),(1518,'1517',1380,0,'1',1),(1519,'1518',1380,0,'1',1),(1520,'1519',1380,0,'1',1),(1521,'1520',1380,0,'1',1),(1522,'1521',1380,0,'1',1),(1523,'1522',1380,0,'1',1),(1524,'1523',1525,1641,'1',1),(1525,'1524',1515,0,'1',1),(1526,'1525',1515,0,'1',1),(1527,'1526',1526,0,'1',1),(1528,'1527',1526,0,'1',1),(1529,'1528',1526,0,'1',1),(1530,'1529',1526,0,'1',1),(1531,'1530',1515,0,'1',1),(1532,'1531',1515,0,'1',1),(1533,'1532',1515,0,'1',1),(1534,'1533',1515,0,'1',1),(1535,'1534',1515,0,'1',1),(1536,'1535',1516,0,'1',1),(1537,'1536',1516,0,'1',1),(1538,'1537',1516,0,'1',1),(1539,'1538',1516,0,'1',1),(1540,'1539',1516,0,'1',1),(1541,'1540',1516,0,'1',1),(1542,'1541',1516,0,'1',1),(1543,'1542',1516,0,'1',1),(1544,'1543',1516,0,'1',1),(1545,'1544',1517,0,'1',1),(1546,'1545',1517,0,'1',1),(1547,'1546',1523,0,'1',1),(1548,'1547',1517,0,'1',1),(1549,'1548',1517,0,'1',1),(1550,'1549',1551,0,'1',1),(1551,'1550',1517,0,'1',1),(1552,'1551',1517,0,'1',1),(1553,'1552',1517,0,'1',1),(1554,'1553',1518,0,'1',1),(1555,'1554',1518,0,'1',1),(1556,'1555',1518,0,'1',1),(1557,'1556',1518,0,'1',1),(1558,'1557',1518,0,'1',1),(1559,'1558',1519,0,'1',1),(1560,'1559',1519,0,'1',1),(1561,'1560',1519,0,'1',1),(1562,'1561',1519,0,'1',1),(1563,'1562',1519,0,'1',1),(1564,'1563',1519,0,'1',1),(1565,'1564',1519,0,'1',1),(1566,'1565',1519,0,'1',1),(1567,'1566',1555,0,'1',1),(1568,'1567',2167,0,'1',1),(1569,'1568',2167,0,'1',1),(1570,'1569',2167,0,'1',1),(1571,'1570',2167,0,'1',1),(1572,'1571',2167,0,'1',1),(1573,'1572',2167,0,'1',1),(1574,'1573',2168,0,'1',1),(1575,'1574',2168,0,'1',1),(1576,'1575',2168,0,'1',1),(1577,'1576',1520,0,'1',1),(1578,'1577',1520,0,'1',1),(1579,'1578',1520,0,'1',1),(1580,'1579',1520,0,'1',1),(1581,'1580',1520,0,'1',1),(1582,'1581',1521,0,'1',1),(1583,'1582',1521,0,'1',1),(1584,'1583',1521,0,'1',1),(1585,'1584',1521,0,'1',1),(1586,'1585',1521,0,'1',1),(1587,'1586',1521,0,'1',1),(1588,'1587',1521,0,'1',1),(1589,'1588',1521,0,'1',1),(1590,'1589',1521,0,'1',1),(1591,'1590',1521,0,'1',1),(1592,'1591',1522,0,'1',1),(1593,'1592',2166,0,'1',1),(1594,'1593',2166,0,'1',1),(1595,'1594',2166,0,'1',1),(1596,'1595',1522,0,'1',1),(1597,'1596',1522,0,'1',1),(1598,'1597',1522,0,'1',1),(1599,'1598',1522,0,'1',1),(1600,'1599',1522,0,'1',1),(1601,'1600',1522,0,'1',1),(1602,'1601',1522,0,'1',1),(1603,'1602',1523,0,'1',1),(1604,'1603',1523,0,'1',1),(1605,'1604',1523,0,'1',1),(1606,'1605',1523,0,'1',1),(1607,'1606',1523,0,'1',1),(1608,'1607',1523,0,'1',1),(1609,'1608',1523,0,'1',1),(1610,'1609',1523,0,'1',1),(1611,'1610',1523,0,'1',1),(1612,'1611',1381,0,'1',1),(1613,'1612',2022,0,'1',1),(1614,'1613',1381,0,'1',1),(1615,'1614',2022,0,'1',1),(1616,'1615',2022,0,'1',1),(1617,'1616',1381,0,'1',1),(1618,'1617',1381,0,'1',1),(1619,'1618',1381,0,'1',1),(1620,'1619',1381,0,'1',1),(1621,'1620',2022,0,'1',1),(1622,'1621',1620,0,'1',1),(1623,'1622',1620,0,'1',1),(1624,'1623',1620,0,'1',1),(1625,'1624',1620,0,'1',1),(1626,'1625',1620,0,'1',1),(1627,'1626',1620,0,'1',1),(1628,'1627',1620,0,'1',1),(1629,'1628',1621,0,'1',1),(1630,'1629',1621,0,'1',1),(1631,'1630',1621,0,'1',1),(1632,'1631',1621,0,'1',1),(1633,'1632',1621,0,'1',1),(1634,'1633',1621,0,'1',1),(1635,'1634',1621,0,'1',1),(1636,'1635',1621,0,'1',1),(1637,'1636',1621,0,'1',1),(1638,'1637',1621,0,'1',1),(1639,'1638',1621,0,'1',1),(1640,'1639',1639,0,'1',1),(1641,'1640',1644,0,'1',1),(1642,'1641',1639,0,'1',1),(1643,'1642',2141,0,'1',1),(1644,'1643',1639,0,'1',1),(1645,'1644',1639,0,'1',1),(1646,'1645',1382,0,'1',1),(1647,'1646',1382,0,'1',1),(1648,'1647',1382,0,'1',1),(1649,'1648',1382,0,'1',1),(1650,'1649',1382,0,'1',1),(1651,'1650',1382,0,'1',1),(1652,'1651',1382,0,'1',1),(1653,'1652',1382,0,'1',1),(1654,'1653',1382,0,'1',1),(1655,'1654',1382,0,'1',1),(1656,'1655',1382,0,'1',1),(1657,'1656',1382,0,'1',1),(1658,'1657',1647,0,'1',1),(1659,'1658',1647,0,'1',1),(1660,'1659',1647,0,'1',1),(1661,'1660',1647,0,'1',1),(1662,'1661',1647,0,'1',1),(1663,'1662',1651,0,'1',1),(1664,'1663',1651,0,'1',1),(1665,'1664',1651,0,'1',1),(1666,'1665',1651,0,'1',1),(1667,'1666',1651,0,'1',1),(1668,'1667',1651,0,'1',1),(1669,'1668',1651,0,'1',1),(1670,'1669',1651,0,'1',1),(1671,'1670',1651,0,'1',1),(1672,'1671',1651,0,'1',1),(1673,'1672',1651,0,'1',1),(1674,'1673',1651,0,'1',1),(1675,'1674',1654,0,'1',1),(1676,'1675',1654,0,'1',1),(1677,'1676',1654,0,'1',1),(1678,'1677',1654,0,'1',1),(1679,'1678',1654,0,'1',1),(1680,'1679',1654,0,'1',1),(1681,'1680',1684,0,'1',1),(1682,'1681',1383,0,'1',1),(1683,'1682',1383,0,'1',1),(1684,'1683',1383,0,'1',1),(1685,'1684',1683,0,'1',1),(1686,'1685',1383,0,'1',1),(1687,'1686',1383,0,'1',1),(1688,'1687',1684,0,'1',1),(1689,'1688',1684,0,'1',1),(1690,'1689',1383,0,'1',1),(1691,'1690',1684,0,'1',1),(1692,'1691',1683,0,'1',1),(1693,'1692',1383,0,'1',1),(1694,'1693',1383,0,'1',1),(1695,'1694',1385,0,'1',1),(1696,'1695',1383,0,'1',1),(1697,'1696',1383,0,'1',1),(1698,'1697',1684,0,'1',1),(1699,'1698',1684,0,'1',1),(1700,'1699',1383,0,'1',1),(1701,'1700',1684,0,'1',1),(1702,'1701',1682,0,'1',1),(1703,'1702',1682,0,'1',1),(1704,'1703',1682,0,'1',1),(1705,'1704',1682,0,'1',1),(1706,'1705',1682,0,'1',1),(1707,'1706',1687,0,'1',1),(1708,'1707',1687,0,'1',1),(1709,'1708',1687,0,'1',1),(1710,'1709',1687,0,'1',1),(1711,'1710',1687,0,'1',1),(1712,'1711',1687,0,'1',1),(1713,'1712',1687,0,'1',1),(1714,'1713',1683,0,'1',1),(1715,'1714',1696,0,'1',1),(1716,'1715',1696,0,'1',1),(1717,'1716',1696,0,'1',1),(1718,'1717',1696,0,'1',1),(1719,'1718',1696,0,'1',1),(1720,'1719',1696,0,'1',1),(1721,'1720',1696,0,'1',1),(1722,'1721',1696,0,'1',1),(1723,'1722',1384,0,'1',1),(1724,'1723',1384,0,'1',1),(1725,'1724',1384,0,'1',1),(1726,'1725',1384,0,'1',1),(1727,'1726',2203,0,'1',1),(1728,'1727',1384,0,'1',1),(1729,'1728',1384,0,'1',1),(1730,'1729',1384,0,'1',1),(1731,'1730',1384,0,'1',1),(1733,'1732',1384,0,'1',1),(1734,'1733',1917,0,'1',1),(1735,'1734',1734,0,'1',1),(1736,'1735',1734,0,'1',1),(1737,'1736',1734,0,'1',1),(1738,'1737',1734,0,'1',1),(1739,'1738',1734,0,'1',1),(1740,'1739',1734,0,'1',1),(1741,'1740',1734,0,'1',1),(1742,'1741',1734,0,'1',1),(1743,'1742',1734,0,'1',1),(1744,'1743',1734,0,'1',1),(1745,'1744',1915,0,'1',1),(1746,'1745',1734,0,'1',1),(1747,'1746',1734,0,'1',1),(1748,'1747',1734,0,'1',1),(1749,'1748',1734,0,'1',1),(1750,'1749',1734,0,'1',1),(1751,'1750',1734,0,'1',1),(1752,'1751',1734,0,'1',1),(1753,'1752',1734,0,'1',1),(1754,'1753',1734,0,'1',1),(1755,'1754',1734,0,'1',1),(1756,'1755',1734,0,'1',1),(1757,'1756',1734,0,'1',1),(1758,'1757',1385,0,'1',1),(1759,'1758',1385,0,'1',1),(1760,'1759',1385,0,'1',1),(1761,'1760',1385,0,'1',1),(1762,'1761',1385,0,'1',1),(1763,'1762',1385,0,'1',1),(1764,'1763',1385,0,'1',1),(1765,'1764',1385,0,'1',1),(1766,'1765',1385,0,'1',1),(1767,'1766',1385,0,'1',1),(1768,'1767',1385,0,'1',1),(1769,'1768',1385,0,'1',1),(1770,'1769',1385,0,'1',1),(1771,'1770',1385,0,'1',1),(1772,'1771',1385,0,'1',1),(1773,'1772',1765,0,'1',1),(1774,'1773',1765,0,'1',1),(1775,'1774',1765,0,'1',1),(1776,'1775',1765,0,'1',1),(1777,'1776',1765,0,'1',1),(1778,'1777',1386,0,'1',1),(1779,'1778',1386,0,'1',1),(1780,'1779',1386,0,'1',1),(1781,'1780',1386,0,'1',1),(1782,'1781',1386,0,'1',1),(1783,'1782',1386,0,'1',1),(1784,'1783',1386,0,'1',1),(1785,'1784',1386,0,'1',1),(1786,'1785',1386,0,'1',1),(1787,'1786',1386,0,'1',1),(1788,'1787',1387,0,'1',1),(1789,'1788',1387,0,'1',1),(1790,'1789',1387,0,'1',1),(1791,'1790',1387,0,'1',1),(1792,'1791',1387,0,'1',1),(1793,'1792',1387,0,'1',1),(1794,'1793',1387,0,'1',1),(1795,'1794',1387,0,'1',1),(1796,'1795',1387,0,'1',1),(1797,'1796',1788,0,'1',1),(1798,'1797',1788,0,'1',1),(1799,'1798',1788,0,'1',1),(1800,'1799',1788,0,'1',1),(1801,'1800',1788,0,'1',1),(1802,'1801',1788,0,'1',1),(1803,'1802',1788,0,'1',1),(1804,'1803',1789,0,'1',1),(1805,'1804',1789,0,'1',1),(1806,'1805',1789,0,'1',1),(1807,'1806',1789,0,'1',1),(1808,'1807',1789,0,'1',1),(1809,'1808',1789,0,'1',1),(1810,'1809',1789,0,'1',1),(1811,'1810',1790,0,'1',1),(1812,'1811',1790,0,'1',1),(1813,'1812',1790,0,'1',1),(1814,'1813',1790,0,'1',1),(1815,'1814',1790,0,'1',1),(1816,'1815',1790,0,'1',1),(1817,'1816',1790,0,'1',1),(1818,'1817',1790,0,'1',1),(1819,'1818',1791,0,'1',1),(1820,'1819',1791,0,'1',1),(1821,'1820',1791,0,'1',1),(1822,'1821',1791,0,'1',1),(1823,'1822',1791,0,'1',1),(1824,'1823',1791,0,'1',1),(1825,'1824',1791,0,'1',1),(1826,'1825',1791,0,'1',1),(1827,'1826',1791,0,'1',1),(1828,'1827',1791,0,'1',1),(1829,'1828',1791,0,'1',1),(1830,'1829',1791,0,'1',1),(1831,'1830',1791,0,'1',1),(1832,'1831',1792,0,'1',1),(1833,'1832',1792,0,'1',1),(1834,'1833',1792,0,'1',1),(1835,'1834',1792,0,'1',1),(1836,'1835',1792,0,'1',1),(1837,'1836',1792,0,'1',1),(1838,'1837',1793,0,'1',1),(1839,'1838',1793,0,'1',1),(1840,'1839',1793,0,'1',1),(1841,'1840',1793,0,'1',1),(1842,'1841',1793,0,'1',1),(1843,'1842',1793,0,'1',1),(1844,'1843',1794,0,'1',1),(1845,'1844',1794,0,'1',1),(1846,'1845',1794,0,'1',1),(1847,'1846',1794,0,'1',1),(1848,'1847',1797,0,'1',1),(1849,'1848',1797,0,'1',1),(1850,'1849',1797,0,'1',1),(1851,'1850',1797,0,'1',1),(1852,'1851',1798,0,'1',1),(1853,'1852',1798,0,'1',1),(1854,'1853',1798,0,'1',1),(1855,'1854',1798,0,'1',1),(1856,'1855',1798,0,'1',1),(1857,'1856',1798,0,'1',1),(1858,'1857',1798,0,'1',1),(1859,'1858',1798,0,'1',1),(1860,'1859',1798,0,'1',1),(1861,'1860',1798,0,'1',1),(1862,'1861',1798,0,'1',1),(1863,'1862',1798,0,'1',1),(1864,'1863',1798,0,'1',1),(1865,'1864',1798,0,'1',1),(1866,'1865',1799,0,'1',1),(1867,'1866',1799,0,'1',1),(1868,'1867',1799,0,'1',1),(1869,'1868',1799,0,'1',1),(1870,'1869',1799,0,'1',1),(1871,'1870',1800,0,'1',1),(1872,'1871',1800,0,'1',1),(1873,'1872',1800,0,'1',1),(1874,'1873',1800,0,'1',1),(1875,'1874',1800,0,'1',1),(1876,'1875',1800,0,'1',1),(1877,'1876',1800,0,'1',1),(1878,'1877',1800,0,'1',1),(1879,'1878',1801,0,'1',1),(1880,'1879',1801,0,'1',1),(1881,'1880',1801,0,'1',1),(1882,'1881',1801,0,'1',1),(1883,'1882',1801,0,'1',1),(1884,'1883',1801,0,'1',1),(1885,'1884',1801,0,'1',1),(1886,'1885',1802,0,'1',1),(1887,'1886',1802,0,'1',1),(1888,'1887',1802,0,'1',1),(1889,'1888',1802,0,'1',1),(1890,'1889',1802,0,'1',1),(1891,'1890',1802,0,'1',1),(1892,'1891',1802,0,'1',1),(1893,'1892',1802,0,'1',1),(1894,'1893',1802,0,'1',1),(1895,'1894',1802,0,'1',1),(1896,'1895',1803,0,'1',1),(1897,'1896',1803,0,'1',1),(1898,'1897',1803,0,'1',1),(1899,'1898',1803,0,'1',1),(1900,'1899',1803,0,'1',1),(1901,'1900',1803,0,'1',1),(1902,'1901',1818,0,'1',1),(1903,'1902',1818,0,'1',1),(1904,'1903',1818,0,'1',1),(1905,'1904',1818,0,'1',1),(1906,'1905',1818,0,'1',1),(1907,'1906',1818,0,'1',1),(1908,'1907',1818,0,'1',1),(1909,'1908',1818,0,'1',1),(1910,'1909',1832,0,'1',1),(1911,'1910',1832,0,'1',1),(1912,'1911',1832,0,'1',1),(1913,'1912',1832,0,'1',1),(1914,'1913',1832,0,'1',1),(1915,'1914',1833,0,'1',1),(1916,'1915',1833,0,'1',1),(1917,'1916',1833,0,'1',1),(1918,'1917',1833,0,'1',1),(1919,'1918',1833,0,'1',1),(1920,'1919',1833,0,'1',1),(1921,'1920',1833,0,'1',1),(1922,'1921',1834,0,'1',1),(1923,'1922',1834,0,'1',1),(1924,'1923',1834,0,'1',1),(1925,'1924',1834,0,'1',1),(1926,'1925',1834,0,'1',1),(1927,'1926',1835,0,'1',1),(1928,'1927',1835,0,'1',1),(1929,'1928',1835,0,'1',1),(1930,'1929',1835,0,'1',1),(1931,'1930',1835,0,'1',1),(1932,'1931',1835,0,'1',1),(1933,'1932',1835,0,'1',1),(1934,'1933',1835,0,'1',1),(1935,'1934',1836,0,'1',1),(1936,'1935',1836,0,'1',1),(1937,'1936',1836,0,'1',1),(1938,'1937',1836,0,'1',1),(1939,'1938',1837,0,'1',1),(1940,'1939',1837,0,'1',1),(1941,'1940',1837,0,'1',1),(1942,'1941',1837,0,'1',1),(1943,'1942',1837,0,'1',1),(1944,'1943',1837,0,'1',1),(1945,'1944',1837,0,'1',1),(1946,'1945',1837,0,'1',1),(1947,'1946',1837,0,'1',1),(1948,'1947',1838,0,'1',1),(1949,'1948',1838,0,'1',1),(1950,'1949',1838,0,'1',1),(1951,'1950',1838,0,'1',1),(1952,'1951',1948,0,'1',1),(1953,'1952',1948,0,'1',1),(1954,'1953',1948,0,'1',1),(1955,'1954',1948,0,'1',1),(1956,'1955',1948,0,'1',1),(1957,'1956',1948,0,'1',1),(1958,'1957',1948,0,'1',1),(1959,'1958',1951,0,'1',1),(1960,'1959',1951,0,'1',1),(1961,'1960',1951,0,'1',1),(1962,'1961',1951,0,'1',1),(1963,'1962',1951,0,'1',1),(1964,'1963',1951,0,'1',1),(1965,'1964',1951,0,'1',1),(1966,'1965',1839,0,'1',1),(1967,'1966',1839,0,'1',1),(1968,'1967',1839,0,'1',1),(1969,'1968',1835,0,'1',1),(1970,'1969',1840,0,'1',1),(1971,'1970',1840,0,'1',1),(1972,'1971',1840,0,'1',1),(1973,'1972',1840,0,'1',1),(1974,'1973',1840,0,'1',1),(1975,'1974',1840,0,'1',1),(1976,'1975',1840,0,'1',1),(1977,'1976',1840,0,'1',1),(1978,'1977',1841,0,'1',1),(1979,'1978',1841,0,'1',1),(1980,'1979',1841,0,'1',1),(1981,'1980',1841,0,'1',1),(1982,'1981',1841,0,'1',1),(1983,'1982',1841,0,'1',1),(1984,'1983',1842,0,'1',1),(1985,'1984',1842,0,'1',1),(1986,'1985',1842,0,'1',1),(1987,'1986',1842,0,'1',1),(1988,'1987',1842,0,'1',1),(1989,'1988',1842,0,'1',1),(1990,'1989',1843,0,'1',1),(1991,'1990',1843,0,'1',1),(1992,'1991',1843,0,'1',1),(1993,'1992',1843,0,'1',1),(1994,'1993',1843,0,'1',1),(1995,'1994',1843,0,'1',1),(1996,'1995',1843,0,'1',1),(1997,'1996',1843,0,'1',1),(1998,'1997',1843,0,'1',1),(1999,'1998',1843,0,'1',1),(2000,'1999',1845,0,'1',1),(2001,'2000',1845,0,'1',1),(2002,'2001',1845,0,'1',1),(2003,'2002',1845,0,'1',1),(2004,'2003',1845,0,'1',1),(2005,'2004',1845,0,'1',1),(2006,'2005',1846,0,'1',1),(2007,'2006',1846,0,'1',1),(2008,'2007',1846,0,'1',1),(2009,'2008',1846,0,'1',1),(2010,'2009',1846,0,'1',1),(2011,'2010',1847,0,'1',1),(2012,'2011',1847,0,'1',1),(2013,'2012',1847,0,'1',1),(2014,'2013',1847,0,'1',1),(2015,'2014',1847,0,'1',1),(2016,'2015',1847,0,'1',1),(2017,'2016',1847,0,'1',1),(2018,'2017',1847,0,'1',1),(2019,'2018',1847,0,'1',1),(2020,'2019',1847,0,'1',1),(2021,'2020',1847,0,'1',1),(2022,'2021',1381,0,'1',1),(2023,'2022',1698,0,'1',1),(2024,'2023',1787,0,'1',1),(2025,'2024',1698,0,'1',1),(2026,'2025',1787,0,'1',1),(2027,'2026',1698,0,'1',1),(2028,'2027',1787,0,'1',1),(2029,'2028',1503,0,'1',1),(2030,'2029',2032,0,'1',1),(2031,'2030',2032,0,'1',1),(2032,'2031',1653,0,'1',1),(2034,'2033',1554,0,'1',1),(2035,'2034',2046,0,'1',1),(2036,'2035',2046,0,'1',1),(2037,'2036',1787,0,'1',1),(2039,'2038',1937,0,'1',1),(2040,'2039',1731,0,'1',1),(2043,'2042',1691,0,'1',1),(2044,'2043',1424,0,'1',1),(2045,'2044',1394,0,'1',1),(2046,'2045',1394,0,'1',1),(2047,'2046',2046,0,'1',1),(2048,'2047',2046,0,'1',1),(2049,'2048',2046,0,'1',1),(2050,'2049',2046,0,'1',1),(2051,'2050',2046,0,'1',1),(2052,'2051',2046,0,'1',1),(2053,'2052',2046,0,'1',1),(2054,'2053',2049,0,'1',1),(2055,'2054',1969,0,'1',1),(2056,'2055',1912,0,'1',1),(2057,'2056',1593,0,'1',1),(2058,'2057',1593,0,'1',1),(2059,'2058',1593,0,'1',1),(2060,'2059',1593,0,'1',1),(2061,'2060',1593,0,'1',1),(2062,'2061',1593,0,'1',1),(2063,'2062',1593,0,'1',1),(2064,'2063',1593,0,'1',1),(2065,'2064',1593,0,'1',1),(2066,'2065',1982,0,'1',1),(2067,'2066',1830,0,'1',1),(2068,'2067',1455,0,'1',1),(2069,'2068',1936,0,'1',1),(2070,'2069',1945,0,'1',1),(2071,'2070',1554,0,'1',1),(2072,'2071',1554,0,'1',1),(2074,'2073',2051,0,'1',1),(2075,'2074',1652,0,'1',1),(2076,'2075',2125,0,'1',1),(2077,'2076',1984,0,'1',1),(2078,'2077',1442,0,'1',1),(2079,'2078',2082,0,'1',1),(2080,'2079',2082,0,'1',1),(2081,'2080',2082,0,'1',1),(2082,'2081',1550,0,'1',1),(2083,'2082',1954,0,'1',1),(2084,'2083',2035,0,'1',1),(2085,'2084',1708,0,'1',1),(2086,'2085',1503,0,'1',1),(2087,'2086',2086,0,'1',1),(2088,'2087',1808,0,'1',1),(2089,'2088',2036,0,'1',1),(2090,'2089',2089,0,'1',1),(2092,'2091',1984,0,'1',1),(2093,'2092',1944,0,'1',1),(2094,'2093',2125,0,'1',1),(2095,'2094',1425,0,'1',1),(2096,'2095',1426,0,'1',1),(2097,'2096',1427,0,'1',1),(2098,'2097',1937,0,'1',1),(2099,'2098',1428,0,'1',1),(2100,'2099',1915,0,'1',1),(2101,'2100',1599,0,'1',1),(2102,'2101',1599,0,'1',1),(2103,'2102',1599,0,'1',1),(2104,'2103',1599,0,'1',1),(2105,'2104',1599,0,'1',1),(2106,'2105',1599,0,'1',1),(2107,'2106',1599,0,'1',1),(2108,'2107',2036,0,'1',1),(2109,'2108',1606,0,'1',1),(2110,'2109',1445,0,'1',1),(2111,'2110',2049,0,'1',1),(2112,'2111',1420,0,'1',1),(2113,'2112',2051,0,'1',1),(2114,'2113',1911,0,'1',1),(2115,'2114',1914,0,'1',1),(2116,'2115',1777,0,'1',1),(2117,'2116',1810,0,'1',1),(2118,'2117',1981,0,'1',1),(2119,'2118',1922,0,'1',1),(2120,'2119',1383,0,'1',1),(2121,'2120',2120,0,'1',1),(2122,'2121',1944,0,'1',1),(2123,'2122',1934,0,'1',1),(2124,'2123',2048,0,'1',1),(2125,'2124',1780,0,'1',1),(2126,'2125',2128,0,'1',1),(2127,'2126',2128,0,'1',1),(2128,'2127',2125,0,'1',1),(2129,'2128',1758,0,'1',1),(2130,'2129',2129,0,'1',1),(2131,'2130',2129,0,'1',1),(2132,'2131',1694,0,'1',1),(2135,'2134',1378,0,'1',1),(2136,'2135',2135,0,'1',1),(2137,'2136',1608,0,'1',1),(2138,'2137',1975,0,'1',1),(2139,'2138',2140,0,'1',1),(2140,'2139',1639,0,'1',1),(2141,'2140',1639,0,'1',1),(2142,'2141',1397,0,'1',1),(2143,'2142',1917,0,'1',1),(2144,'2143',1917,0,'1',1),(2145,'2144',1913,0,'1',1),(2146,'2145',2158,0,'1',1),(2147,'2146',1394,0,'1',1),(2148,'2147',2158,0,'1',1),(2150,'2149',2157,0,'1',1),(2151,'2150',1919,0,'1',1),(2152,'2151',2147,0,'1',1),(2153,'2152',2147,0,'1',1),(2154,'2153',2147,0,'1',1),(2155,'2154',2147,0,'1',1),(2156,'2155',2147,0,'1',1),(2157,'2156',2147,0,'1',1),(2158,'2157',2147,0,'1',1),(2159,'2158',1399,0,'1',1),(2160,'2159',1399,0,'1',1),(2161,'2160',1399,0,'1',1),(2162,'2161',1526,0,'1',1),(2163,'2162',1526,0,'1',1),(2164,'2163',1515,0,'1',1),(2165,'2164',1515,0,'1',1),(2166,'2165',1522,0,'1',1),(2167,'2166',1520,0,'1',1),(2168,'2167',1520,0,'1',1),(2169,'2168',1520,0,'1',1),(2170,'2169',1378,0,'1',1),(2171,'2170',1522,0,'1',1),(2172,'2171',2170,0,'1',1),(2173,'2172',1981,0,'1',1),(2174,'2173',1993,0,'1',1),(2175,'2174',1401,0,'1',1),(2177,'2176',1389,0,'1',1),(2179,'2178',2359,0,'1',1),(2180,'2179',1618,0,'1',1),(2181,'2180',2135,0,'1',1),(2182,'2181',1405,0,'1',1),(2183,'2182',1621,0,'1',1),(2184,'2183',1405,0,'1',1),(2185,'2184',1400,0,'1',1),(2186,'2185',1400,0,'1',1),(2188,'2187',1825,0,'1',1),(2189,'2188',1805,0,'1',1),(2190,'2189',1983,0,'1',1),(2191,'2190',1612,0,'1',1),(2192,'2191',2185,0,'1',1),(2193,'2192',2047,0,'1',1),(2194,'2193',2049,0,'1',1),(2196,'2195',1733,0,'1',1),(2197,'2196',2196,0,'1',1),(2198,'2197',2197,0,'1',1),(2200,'2199',1378,0,'1',1),(2201,'2200',2200,0,'1',1),(2203,'2202',1386,0,'1',1),(2204,'2203',1999,0,'1',1),(2205,'2204',1780,0,'1',1),(2206,'2205',2205,0,'1',1),(2207,'2206',2205,0,'1',1),(2208,'2207',1954,0,'1',1),(2209,'2208',1982,0,'1',1),(2210,'2209',1998,0,'1',1),(2211,'2210',1844,0,'1',1),(2212,'2211',2205,0,'1',1),(2213,'2212',1993,0,'1',1),(2214,'2213',1613,0,'1',1),(2215,'2214',2214,0,'1',1),(2216,'2215',2214,0,'1',1),(2217,'2216',1635,0,'1',1),(2218,'2217',1638,0,'1',1),(2219,'2218',1621,0,'1',1),(2220,'2219',2219,0,'1',1),(2221,'2220',2219,0,'1',1),(2222,'2221',1615,0,'1',1),(2223,'2222',1631,0,'1',1),(2224,'2223',2125,0,'1',1),(2225,'2224',2125,0,'1',1),(2226,'2225',1408,0,'1',1),(2227,'2226',1764,0,'1',1),(2228,'2227',2368,0,'1',1),(2229,'2228',1489,0,'1',1),(2231,'2230',1486,0,'1',1),(2232,'2231',1401,0,'1',1),(2233,'2232',1490,0,'1',1),(2235,'2234',1396,0,'1',1),(2236,'2235',1613,0,'1',1),(2237,'2236',1405,0,'1',1),(2238,'2237',1850,0,'1',1),(2239,'2238',1405,0,'1',1),(2240,'2239',1402,0,'1',1),(2242,'2241',1490,0,'1',1),(2244,'2243',1848,0,'1',1),(2245,'2244',2359,0,'1',1),(2246,'2245',1401,0,'1',1),(2248,'2247',1490,0,'1',1),(2250,'2249',1490,0,'1',1),(2252,'2251',1525,0,'1',1),(2253,'2252',2252,0,'1',1),(2254,'2253',1545,0,'1',1),(2255,'2254',1551,0,'1',1),(2256,'2255',1605,0,'1',1),(2257,'2256',1611,0,'1',1),(2258,'2257',1611,0,'1',1),(2259,'2258',1571,0,'1',1),(2260,'2259',1405,0,'1',1),(2261,'2260',1683,0,'1',1),(2262,'2261',1766,0,'1',1),(2264,'2263',1731,0,'1',1),(2265,'2264',1551,0,'1',1),(2266,'2265',2265,0,'1',1),(2267,'2266',2171,0,'1',1),(2268,'2267',1525,1643,'1',1),(2269,'2268',1525,0,'1',1),(2270,'2269',1525,0,'1',1),(2271,'2270',1551,0,'1',1),(2272,'2271',2271,0,'1',1),(2273,'2272',2275,0,'1',1),(2274,'2273',2275,0,'1',1),(2275,'2274',2277,0,'1',1),(2276,'2275',1778,1643,'1',1),(2277,'2276',1551,0,'1',1),(2278,'2277',1549,0,'1',1),(2279,'2278',1731,0,'1',1),(2280,'2279',1546,0,'1',1),(2281,'2280',1546,0,'1',1),(2282,'2281',1546,0,'1',1),(2283,'2282',1571,0,'1',1),(2284,'2283',1555,0,'1',1),(2285,'2284',1555,0,'1',1),(2286,'2285',1555,0,'1',1),(2287,'2286',1718,0,'1',1),(2288,'2287',1982,0,'1',1),(2289,'2288',2203,0,'1',1),(2290,'2289',2172,0,'1',1),(2291,'2290',1725,0,'1',1),(2292,'2291',1850,0,'1',1),(2293,'2292',1805,0,'1',1),(2294,'2293',1759,0,'1',1),(2295,'2294',1474,0,'1',1),(2297,'2296',1648,0,'1',1),(2298,'2297',1656,0,'1',1),(2299,'2298',1731,0,'1',1),(2300,'2299',1471,0,'1',1),(2302,'2301',1471,0,'1',1),(2303,'2302',1764,0,'1',1),(2304,'2303',1916,0,'1',1),(2306,'2305',1396,0,'1',1),(2307,'2306',1917,0,'1',1),(2308,'2307',1780,0,'1',1),(2309,'2308',2308,0,'1',1),(2310,'2309',1650,0,'1',1),(2311,'2310',1774,0,'1',1),(2312,'2311',1805,0,'1',1),(2313,'2312',2205,0,'1',1),(2314,'2313',1711,0,'1',1),(2315,'2314',1711,0,'1',1),(2316,'2315',1711,0,'1',1),(2317,'2316',1711,0,'1',1),(2318,'2317',1621,0,'1',1),(2319,'2318',1854,0,'1',1),(2320,'2319',1658,0,'1',1),(2321,'2320',2320,0,'1',1),(2322,'2321',1658,0,'1',1),(2324,'2323',1879,0,'1',1),(2325,'2324',1894,0,'1',1),(2326,'2325',1785,0,'1',1),(2327,'2326',1764,0,'1',1),(2328,'2327',1496,0,'1',1),(2329,'2328',1496,0,'1',1),(2330,'2329',2032,0,'1',1),(2332,'2331',1513,0,'1',1),(2335,'2334',1655,0,'1',1),(2336,'2335',1770,0,'1',1),(2337,'2336',2336,0,'1',1),(2338,'2337',2185,0,'1',1),(2339,'2338',2185,0,'1',1),(2340,'2339',2185,0,'1',1),(2341,'2340',1657,0,'1',1),(2342,'2341',1396,0,'1',1),(2343,'2342',1513,0,'1',1),(2345,'2344',1495,0,'1',1),(2346,'2345',1388,0,'1',1),(2347,'2346',1656,0,'1',1),(2348,'2347',1686,0,'1',1),(2349,'2348',1648,0,'1',1),(2350,'2349',2308,0,'1',1),(2352,'2351',1703,0,'1',1),(2353,'2352',1490,0,'1',1),(2354,'2353',1780,0,'1',1),(2356,'2355',2359,0,'1',1),(2358,'2357',1490,0,'1',1),(2359,'2358',1490,0,'1',1),(2361,'2360',2125,0,'1',1),(2362,'2361',1490,0,'1',1),(2364,'2363',1490,0,'1',1),(2366,'2365',1490,0,'1',1),(2368,'2367',1489,0,'1',1),(2369,'2368',2308,0,'1',1),(2371,'2370',1771,0,'1',1),(2372,'2371',1662,0,'1',1),(2373,'2372',1648,0,'1',1),(2374,'2373',1656,0,'1',1),(2375,'2374',1688,0,'1',1),(2376,'2375',1650,0,'1',1),(2377,'2376',2125,0,'1',1),(2378,'2377',1758,0,'1',1),(2380,'2379',1633,0,'1',1),(2381,'2380',1400,0,'1',1),(2382,'2381',2185,0,'1',1),(2383,'2382',1613,0,'1',1),(2384,'2383',2205,0,'1',1),(2385,'2384',2384,0,'1',1),(2386,'2385',2205,0,'1',1),(2387,'2386',2386,0,'1',1),(2388,'2387',2386,0,'1',1),(2389,'2388',1904,0,'1',1),(2390,'2389',1810,0,'1',1),(2391,'2390',2205,0,'1',1),(2392,'2391',1899,0,'1',1),(2393,'2392',1861,0,'1',1),(2394,'2393',1652,0,'1',1),(2395,'2394',1652,0,'1',1),(2396,'2395',2203,0,'1',1),(2397,'2396',1780,0,'1',1),(2398,'2397',1976,0,'1',1),(2399,'2398',1693,0,'1',1),(2400,'2399',2399,0,'1',1),(2402,'2401',1721,0,'1',1),(2403,'2402',1787,0,'1',1),(2404,'2403',2120,0,'1',1),(2405,'2404',1731,0,'1',1),(2406,'2405',1656,0,'1',1),(2407,'2406',1729,0,'1',1),(2408,'2407',2205,0,'1',1),(2409,'2408',1935,0,'1',1),(2410,'2409',1392,0,'1',1),(2411,'2410',2205,0,'1',1),(2412,'2411',2411,0,'1',1),(2413,'2412',1780,0,'1',1),(2414,'2413',2125,0,'1',1),(2416,'2415',2373,0,'1',1),(2418,'2417',2373,0,'1',1),(2420,'2419',1616,0,'1',1),(2422,'2421',1780,0,'1',1),(2423,'2422',2125,0,'1',1),(2424,'2423',2125,0,'1',1),(2425,'2424',2185,0,'1',1),(2426,'2425',2185,0,'1',1),(2427,'2426',2185,0,'1',1),(2428,'2427',1657,0,'1',1),(2429,'2428',2428,0,'1',1),(2430,'2429',2429,0,'1',1),(2431,'2430',1466,0,'1',1),(2432,'2431',1765,0,'1',1),(2438,'2437',1923,0,'1',1),(2439,'2438',1925,0,'1',1),(2440,'2439',1921,0,'1',1),(2441,'2440',1903,0,'1',1),(2442,'2441',1466,0,'1',1),(2443,'2442',1819,0,'1',1),(2444,'2443',1466,0,'1',1),(2445,'2444',1831,0,'1',1),(2446,'2445',1620,0,'1',1),(2447,'2446',1616,0,'1',1),(2448,'2447',2447,0,'1',1),(2449,'2448',1616,0,'1',1),(2451,'2450',2022,0,'1',1),(2452,'2451',2451,0,'1',1),(2454,'2453',2451,0,'1',1),(2456,'2455',1394,0,'1',1),(2457,'2456',1618,0,'1',1),(2458,'2457',2457,0,'1',1),(2460,'2459',1618,0,'1',1),(2461,'2460',1612,0,'1',1),(2462,'2461',2036,0,'1',1),(2463,'2462',1455,0,'1',1),(2464,'2463',1828,0,'1',1),(2465,'2464',1765,0,'1',1),(2466,'2465',2465,0,'1',1),(2467,'2466',2465,0,'1',1),(2469,'2468',2457,0,'1',1),(2471,'2470',2472,0,'1',1),(2472,'2471',1618,0,'1',1),(2473,'2472',2457,0,'1',1),(2475,'2474',1618,0,'1',1),(2476,'2475',1389,0,'1',1),(2477,'2476',1716,0,'1',1),(2478,'2477',2086,0,'1',1),(2479,'2478',1420,0,'1',1),(2480,'2479',1726,0,'1',1),(2481,'2480',1726,2480,'1',1),(2482,'2481',1726,0,'1',1),(2483,'2482',1726,2482,'1',1),(2485,'2484',1764,0,'1',1),(2486,'2485',1764,2485,'1',1),(2487,'2486',1764,0,'1',1),(2488,'2487',1379,0,'1',1),(2489,'2488',2488,0,'1',1),(2490,'2489',2489,0,'1',1),(2491,'2490',1686,0,'1',1),(2492,'2491',1684,0,'1',1),(2493,'2492',1729,0,'1',1),(2494,'2493',2488,0,'1',1),(2495,'2494',1729,0,'1',1),(2496,'2495',1686,0,'1',1),(2497,'2496',1686,0,'1',1),(2498,'2497',1684,0,'1',1),(2499,'2498',2498,0,'1',1),(2500,'2499',2498,0,'1',1),(2501,'2500',2492,2502,'1',1),(2502,'2501',1787,0,'1',1),(2503,'2502',1787,0,'1',1),(2504,'2503',2502,0,'1',1),(2505,'2504',2503,0,'1',1),(2506,'2505',2503,0,'1',1),(2507,'2506',2505,0,'1',1),(2508,'2507',1765,0,'1',1),(2509,'2508',2508,0,'1',1),(2510,'2509',1767,0,'1',1),(2511,'2510',2484,1670,'1',1),(2512,'NONCLASSES',1,0,'0',1),(2513,'',1,0,'1',1),(2514,'',1,0,'1',1),(2515,'',1,0,'1',1),(2516,'',1,0,'1',1),(2517,'',1,0,'1',1),(2518,'',1,0,'1',1),(2519,'',1,0,'1',1),(2520,'',1,0,'1',1),(2521,'',1,0,'1',1),(2522,'',1,0,'1',1),(2523,'',2522,0,'1',1),(2524,'',2520,0,'1',1),(2525,'',2520,0,'1',1),(2526,'',1,0,'1',1),(2527,'',2526,0,'1',1),(2528,'',2526,0,'1',1),(2529,'',2526,0,'1',1),(2530,'',2526,0,'1',1),(2531,'',2521,0,'1',1),(2532,'',2521,0,'1',1),(2533,'',1,0,'1',1),(2534,'',2533,0,'1',1),(2535,'',2533,0,'1',1),(2536,'',1,0,'1',1),(2537,'',2536,0,'1',1),(2538,'',2536,0,'1',1),(2539,'',2522,0,'1',1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `noeuds` ENABLE KEYS */;

--
-- Table structure for table `notice_statut`
--

DROP TABLE IF EXISTS `notice_statut`;
CREATE TABLE `notice_statut` (
  `id_notice_statut` smallint(5) unsigned NOT NULL auto_increment,
  `gestion_libelle` varchar(255) default NULL,
  `opac_libelle` varchar(255) default NULL,
  `notice_visible_opac` tinyint(1) NOT NULL default '1',
  `notice_visible_gestion` tinyint(1) NOT NULL default '1',
  `expl_visible_opac` tinyint(1) NOT NULL default '1',
  `class_html` varchar(255) NOT NULL default '',
  `notice_visible_opac_abon` tinyint(1) NOT NULL default '0',
  `expl_visible_opac_abon` int(10) unsigned NOT NULL default '0',
  `explnum_visible_opac` int(1) unsigned NOT NULL default '1',
  `explnum_visible_opac_abon` int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_notice_statut`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notice_statut`
--


/*!40000 ALTER TABLE `notice_statut` DISABLE KEYS */;
LOCK TABLES `notice_statut` WRITE;
INSERT INTO `notice_statut` VALUES (1,'àºšà»à»ˆà»€àºˆàº²àº°àºˆàº»àº‡àºªàº°àº–àº²àº™àº°àºàº²àºš','',1,1,1,'statutnot1',0,0,1,0),(2,'àº«à»‰àº²àº¡à»ƒàº«à»‰àº¢àº·àº¡','',0,1,1,'statutnot2',0,0,1,0),(3,'àºªàº±à»ˆàº‡à»€àº‚àº»à»‰àº²àº¢àº¹à»ˆ','',1,1,1,'statutnot4',0,0,1,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `notice_statut` ENABLE KEYS */;

--
-- Table structure for table `notices`
--

DROP TABLE IF EXISTS `notices`;
CREATE TABLE `notices` (
  `notice_id` mediumint(8) unsigned NOT NULL auto_increment,
  `typdoc` char(2) NOT NULL default 'a',
  `tit1` tinytext NOT NULL,
  `tit2` tinytext NOT NULL,
  `tit3` tinytext NOT NULL,
  `tit4` tinytext NOT NULL,
  `tparent_id` mediumint(8) unsigned NOT NULL default '0',
  `tnvol` varchar(16) default '',
  `ed1_id` mediumint(8) unsigned NOT NULL default '0',
  `ed2_id` mediumint(8) unsigned NOT NULL default '0',
  `coll_id` mediumint(8) unsigned NOT NULL default '0',
  `subcoll_id` mediumint(8) unsigned NOT NULL default '0',
  `year` varchar(16) default '',
  `nocoll` varchar(16) default '',
  `mention_edition` varchar(255) NOT NULL default '',
  `code` varchar(16) NOT NULL default '',
  `npages` varchar(54) NOT NULL default '',
  `ill` varchar(54) NOT NULL default '',
  `size` varchar(54) NOT NULL default '',
  `accomp` varchar(54) NOT NULL default '',
  `n_gen` text NOT NULL,
  `n_contenu` text NOT NULL,
  `n_resume` text NOT NULL,
  `lien` tinytext NOT NULL,
  `eformat` varchar(255) NOT NULL default '',
  `index_l` text NOT NULL,
  `indexint` int(8) unsigned NOT NULL default '0',
  `index_serie` tinytext,
  `index_matieres` text NOT NULL,
  `niveau_biblio` char(1) NOT NULL default 'm',
  `niveau_hierar` char(1) NOT NULL default '0',
  `origine_catalogage` int(8) unsigned NOT NULL default '1',
  `prix` varchar(255) NOT NULL default '',
  `index_n_gen` text,
  `index_n_contenu` text,
  `index_n_resume` text,
  `index_sew` text,
  `index_wew` text,
  `statut` int(5) NOT NULL default '1',
  `commentaire_gestion` text NOT NULL,
  `create_date` datetime NOT NULL default '2005-01-01 00:00:00',
  `update_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `notice_parent` int(9) unsigned NOT NULL default '0',
  `relation_type` char(1) NOT NULL default 'a',
  PRIMARY KEY  (`notice_id`),
  KEY `typdoc` (`typdoc`),
  KEY `tparent_id` (`tparent_id`),
  KEY `ed1_id` (`ed1_id`),
  KEY `ed2_id` (`ed2_id`),
  KEY `coll_id` (`coll_id`),
  KEY `subcoll_id` (`subcoll_id`),
  KEY `cb` (`code`),
  KEY `indexint` (`indexint`),
  KEY `notice_parent` (`notice_parent`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notices`
--


/*!40000 ALTER TABLE `notices` DISABLE KEYS */;
LOCK TABLES `notices` WRITE;
INSERT INTO `notices` VALUES (1,'a','àºŠàºµàº§àº´àº” à»àº¥àº° àºœàº»àº™àº‡àº²àº™àº‚àº­àº‡àºàº£àº°àº¡àº°àº«àº²à»€àº–àº£àº°5 àº­àº»àº‡','','','',0,'',8,0,0,0,'2001','','1','','52 à»œà»‰àº²','','','','','','','','','',6,'  ','  ','m','0',1,'7000 àºàºµàºš','  ','  ','  ',' 5 ',' àºŠàºµàº§àº´àº” à»àº¥àº° àºœàº»àº™àº‡àº²àº™àº‚àº­àº‡àºàº£àº°àº¡àº°àº«àº²à»€àº–àº£àº°5 àº­àº»àº‡   ',1,'','2006-10-13 15:15:24','2006-10-13 15:15:24',0,'a'),(2,'a','àºàº»àº‡àºªàº²àº§àº°àº”àº²àº™àº¥àº²àº§ à»€àº–àº´àº‡ 1946','','','',0,'',10,0,0,0,'2001','','1','','83 à»à»‰àº²','','','','','','','','','',0,'  ','  ','m','0',1,'9600àºàºµàºš','  ','  ','  ',' 1946 ',' àºàº»àº‡àºªàº²àº§àº°àº”àº²àº™àº¥àº²àº§ à»€àº–àº´àº‡ 1946   ',1,'','2006-10-13 15:28:56','2006-10-13 15:28:56',0,'a'),(3,'a','à»€àº¡àº·à»ˆàº­àº‚à»‰àº­àºà»€àº›àº´àº”àºªàº°à»àº¸àº”àºšàº±àº™àº—àº¶àº','','','',0,'',9,0,0,0,'2002','','1','','277 à»à»‰àº²','','','','','','','','','',0,'  ','  ','m','0',1,'96000àºàºµàºš','  ','  ','  ',' à»€àº¡àº·à»ˆàº­àº‚à»‰àº­àºà»€àº›àº´àº”àºªàº°à»àº¸àº”àºšàº±àº™àº—àº¶àº ',' à»€àº¡àº·à»ˆàº­àº‚à»‰àº­àºà»€àº›àº´àº”àºªàº°à»àº¸àº”àºšàº±àº™àº—àº¶àº   ',1,'','2006-10-13 15:32:56','2006-11-09 13:40:19',0,'a'),(4,'a','àº„àº­àº‡à»àºªàº™à»àºªàºšàº¢à»ˆàº²àºŠà»à»‰àº²àº®àº­àº','','','',0,'',6,0,0,0,'2000','','1','','53à»œà»‰àº²','àº¡àºµàºàº²àºšàº›àº°àºàº­àºš','','','','','','','','',0,'  ','  ','m','0',1,'82000 àºàºµàºš','  ','  ','  ','  ',' àº„àº­àº‡à»àºªàº™à»àºªàºšàº¢à»ˆàº²àºŠà»à»‰àº²àº®àº­àº   ',1,'','2006-10-13 15:47:40','2006-10-13 15:47:40',0,'a'),(5,'a','àº§àº´àº¥àº°àºàº³à»€àºˆàº»à»‰àº²àº­àº²àº™àº¸','','','',0,'',6,0,0,0,'','','','','900à»œà»‰àº²','àº¡àºµàºàº²àºšàº›àº°àºàº­àºš','','','','','','','','',0,'  ','  ','m','0',1,'170000àºàºµàºš','  ','  ','  ','  ',' àº§àº´àº¥àº°àºàº³à»€àºˆàº»à»‰àº²àº­àº²àº™àº¸   ',1,'','2006-10-13 15:52:00','2006-10-13 15:52:00',0,'a'),(6,'a','àºàº²àºšà»€àº¡àº·àº­àº‡àºàº§àº™','','','',0,'',13,0,0,0,'','','','','51à»œà»‰àº²','àº¡àºµàºàº²àºšàº›àº°àºàº­àºš','','','','','','','','',0,'  ','  ','m','0',1,'13000àºàºµàºš','  ','  ','  ','  ',' àºàº²àºšà»€àº¡àº·àº­àº‡àºàº§àº™   ',1,'','2006-10-13 15:54:24','2006-10-13 15:54:24',0,'a'),(7,'a','àºªàº°àºàº¸àº™àº•àº»à»‰àº™àº”àº­àºà»€àºœàº´à»‰àº‡àº‚àº­àº‡àº›àº°à»€àº—àº”à»„àº—,àº¥àº²àº§','','','',0,'',17,0,0,0,'20004','','1','','450à»œà»‰àº²','àº¡àºµàºàº²àºšàº›àº°àºàº­àºš','','','','','','','','',0,'  ','  ','m','0',1,'7500 àºàºµàºš','  ','  ','  ','  ',' àºªàº°àºàº¸àº™àº•àº»à»‰àº™àº”àº­àºà»€àºœàº´à»‰àº‡àº‚àº­àº‡àº›àº°à»€àº—àº”à»„àº—,àº¥àº²àº§   ',1,'','2006-10-13 15:57:46','2006-10-13 15:57:46',0,'a'),(8,'a','àº—à»‰àº²àº§àºªàº¸àº£àº°àº™àº²àº¥àºµ àºšàº²àº‡àº—àº±àº”àºªàº°àº™àº°àº‚àº­àº‡àº„àº»àº™à»„àº—','','','',0,'',16,0,0,0,'','','','','68à»œà»‰àº²','àº¡àºµàºàº²àºšàº›àº°àºàº­àºš','','','','','','','','',0,'  ','  ','m','0',1,'5000 àºàºµàºš','  ','  ','  ','  ',' àº—à»‰àº²àº§àºªàº¸àº£àº°àº™àº²àº¥àºµ àºšàº²àº‡àº—àº±àº”àºªàº°àº™àº°àº‚àº­àº‡àº„àº»àº™à»„àº—   ',1,'','2006-10-13 15:59:50','2006-10-13 15:59:50',0,'a'),(9,'a','àº›àº°àº«àº§àº±àº”àºªàº²àº”àº¥àº²àº§ 1946','','','',0,'',14,0,0,0,'','','','','852à»œà»‰àº²','àº¡àºµàºàº²àºšàº›àº°àºàº­àºš','','','','','','','','',0,'  ','  ','m','0',1,'200000 àºàºµàºš','  ','  ','  ',' 1946 ',' àº›àº°àº«àº§àº±àº”àºªàº²àº”àº¥àº²àº§ 1946   ',1,'','2006-10-13 16:02:02','2006-10-13 16:02:02',0,'a'),(10,'a','àºàº²àº™àº›àº½àºšàº—àº½àºšàºœàº»àº™àºªàº»àº¡àº—àº²àº‡àº”à»‰àº²àº™àº„àº°àº™àº´àº”àºªàº²àº”','','','',0,'',20,0,0,0,'','','','','65à»œà»‰àº²','','','','','','','','','',0,'  ','  ','m','0',1,'20000 àºàºµàºš','  ','  ','  ','  ',' àºàº²àº™àº›àº½àºšàº—àº½àºšàºœàº»àº™àºªàº»àº¡àº—àº²àº‡àº”à»‰àº²àº™àº„àº°àº™àº´àº”àºªàº²àº”   ',1,'','2006-10-13 16:06:11','2006-10-13 16:06:11',0,'a'),(11,'a','àºàº»àº”à»àº²àºàº›à»ˆàº²à»„àº¡à»‰','','','',0,'',8,0,0,0,'','','','','156à»œà»‰àº²','','','','','','','','','',0,'  ','  ','m','0',1,'700000àºàºµàºš','  ','  ','  ','  ',' àºàº»àº”à»àº²àºàº›à»ˆàº²à»„àº¡à»‰   ',1,'','2006-10-13 16:09:57','2006-10-13 16:09:57',0,'a'),(12,'a','àº®àº´àº”àº„àº­àº‡àº›àº°à»€àºàº™àºµàº¥àº²àº§','','','',0,'',2,0,0,0,'','','','','67à»œà»‰àº²','','','','','','','','','',0,'  ','  ','m','0',1,'5800àºàºµàºš','  ','  ','  ','  ',' àº®àº´àº”àº„àº­àº‡àº›àº°à»€àºàº™àºµàº¥àº²àº§   ',1,'','2006-10-13 16:12:44','2006-10-13 16:12:44',0,'a'),(13,'a','à»àº™àº§àº—àº²àº‡àºàº²àº™àº”àº³à»€àº™àºµàº™àº‡àº²àº™àºªàº³àº¥àº±àºšàº„àº°àº™àº°àºàº³àº¡àº°àºàº²àº™','','','',0,'',19,0,0,0,'','','','','96à»œà»‰àº²','','','','','','','','','',0,'  ','  ','m','0',1,'8000 àºàºµàºš','  ','  ','  ','  ',' à»àº™àº§àº—àº²àº‡àºàº²àº™àº”àº³à»€àº™àºµàº™àº‡àº²àº™àºªàº³àº¥àº±àºšàº„àº°àº™àº°àºàº³àº¡àº°àºàº²àº™   ',1,'','2006-10-13 16:14:28','2006-10-13 16:14:28',0,'a'),(14,'a','àº„àº»àº™àº„àº§à»‰àº²àº§àº´àº—àº°àºàº²àºªàº²àº”àº—àº²àº‡àº”à»‰àº²àº™àº§àº´àºŠàº²àºàº²àº™à»àºàº”','','','',0,'',18,0,0,0,'','','','','785à»œà»‰àº²','','','','','','','','','',0,'  ','  ','m','0',1,'78000àºàºµàºš','  ','  ','  ','  ',' àº„àº»àº™àº„àº§à»‰àº²àº§àº´àº—àº°àºàº²àºªàº²àº”àº—àº²àº‡àº”à»‰àº²àº™àº§àº´àºŠàº²àºàº²àº™à»àºàº”   ',1,'','2006-10-13 16:18:02','2006-10-13 16:18:02',0,'a'),(15,'a','àº®àº´àº”àº„àº­àº‡àº›àº°à»€àºàº™àºµàº¥àº²àº§ 2','','','',0,'',20,0,0,0,'','','','','35à»œà»‰àº²','','','','','','àº®àº´àº”àº„àº­àº‡àº›àº°à»€àºàº™àºµàº¥àº²àº§ ','','','',0,'  ','  ','m','0',1,'34000àºàºµàºš','  ','  ','  ',' 2 ',' àº®àº´àº”àº„àº­àº‡àº›àº°à»€àºàº™àºµàº¥àº²àº§ 2   ',1,'','2006-10-13 16:20:06','2006-10-13 16:20:06',0,'a'),(16,'a','àº•àº³àº¥àº²àº¢àº²àºàº·àº™à»€àº¡àº·àº­àº‡','','','',0,'',8,0,0,0,'2000','','6','','125à»œà»‰àº²','','','','àº•àº³àº¥àº²àº¢àº²àºàº·àº™à»€àº¡àº·àº­àº‡ àº—àºµà»ˆàº¡àºµàº„àº¸àº™àº›àº°à»‚àº«àºàº”àº—àº²àº‡àºàº²àº™à»àºàº”','','','','','',0,'  ','  ','m','0',1,'12500àºàºµàºš','  ','  ','  ','  ',' àº•àº³àº¥àº²àº¢àº²àºàº·àº™à»€àº¡àº·àº­àº‡   ',1,'','2006-10-13 16:21:42','2006-10-13 16:22:48',0,'a'),(17,'a','àº§àº´àº—àºµàº®àº±àºàºªàº²àº„àº§àº²àº¡àº‡àº²àº¡','','','',0,'',16,0,0,0,'','','','','','64à»œà»‰àº²','','','àºàº²àº™àº®àº±àºàºªàº²àº„àº§àº²àº¡àº‡àº²àº¡','','','','','',0,'  ','  ','m','0',1,'73000àºàºµàºš','  ','  ','  ','  ',' àº§àº´àº—àºµàº®àº±àºàºªàº²àº„àº§àº²àº¡àº‡àº²àº¡   ',1,'','2006-10-13 16:25:15','2006-10-13 16:25:15',0,'a'),(18,'a','àºŠàºµàº§àº´àº” à»àº¥àº° àºœàº»àº™àº‡àº²àº™','','','',0,'',6,0,0,0,'','','','','','','','','','','','','','',0,NULL,'','s','1',1,'',NULL,NULL,NULL,'  ','àºŠàºµàº§àº´àº” à»àº¥àº° àºœàº»àº™àº‡àº²àº™   ',1,'','2006-10-13 16:27:45','2006-10-13 16:27:45',0,'a'),(19,'a','àº„àº¹à»ˆàº¡àº·àºªàº³àº¥àº±àºšàº„àº¹à»ˆàºªàº­àº™','','','',0,'',2,0,0,0,'','','','','','','','','','','','','','',0,NULL,'','s','1',1,'',NULL,NULL,NULL,'  ','àº„àº¹à»ˆàº¡àº·àºªàº³àº¥àº±àºšàº„àº¹à»ˆàºªàº­àº™   ',1,'','2006-10-13 16:31:07','2006-10-13 16:31:07',0,'a'),(20,'a','à»€àº­àºàº°àºªàº²àº™à»€àºàºµà»ˆàº¡àº—àº°àº§àºµàº„àº§àº²àº¡àºªàº²àº¡àº±àºàº„àºµ','','','',0,'',16,0,0,0,'','','','','','','','','','','','','','',0,NULL,'','s','1',1,'',NULL,NULL,NULL,' à»€àº­àºàº°àºªàº²àº™à»€àºàºµà»ˆàº¡àº—àº°àº§àºµàº„àº§àº²àº¡àºªàº²àº¡àº±àºàº„àºµ    ','à»€àº­àºàº°àºªàº²àº™à»€àºàºµà»ˆàº¡àº—àº°àº§àºµàº„àº§àº²àº¡àºªàº²àº¡àº±àºàº„àºµ   ',1,'','2006-10-13 16:34:39','2006-10-14 16:36:47',0,'a'),(21,'a','à»€àº­àºàº°àºªàº²àº™à»€àºàºµà»ˆàº¡àº—àº°àº§àºµàº„àº§àº²àº¡àºªàº²àº¡àº±àºàº„àºµ','','','',0,'',0,0,0,0,'','','','','','','','','','','','','','',35,'','  ','a','2',1,'','  ','  ','  ','  ','à»€àº­àºàº°àºªàº²àº™à»€àºàºµà»ˆàº¡àº—àº°àº§àºµàº„àº§àº²àº¡àºªàº²àº¡àº±àºàº„àºµ   ',1,'','2006-10-13 16:37:54','2006-10-13 16:37:54',0,'a'),(22,'a','àºàº¹àº¡àº›àº±àº™àºàº²àºšàº¹àº®àº²àº™àº¥àº²àº§','','','',0,'',12,0,0,0,'','','','','','','','','','','','','','',33,NULL,'','s','1',1,'',NULL,NULL,NULL,'  ','àºàº¹àº¡àº›àº±àº™àºàº²àºšàº¹àº®àº²àº™àº¥àº²àº§   ',1,'','2006-10-13 16:39:48','2006-10-13 16:39:48',0,'a'),(23,'a','à»àº„àº™ à»àº¥àº° àºªàº½àº‡à»àº„àº™','','','',0,'',13,0,0,0,'','','','','','','','','','','','','','',0,NULL,'','s','1',1,'',NULL,NULL,NULL,'  ','à»àº„àº™ à»àº¥àº° àºªàº½àº‡à»àº„àº™   ',1,'','2006-10-13 16:41:12','2006-10-13 16:41:12',0,'a'),(24,'a','àºšàº»àº”àº¥àº²àºàº‡àº²àº™àºªàº°àºàº²àºšà»àº§àº”àº¥à»‰àº­àº¡ àºªàº›àº› àº¥àº²àº§','','','',0,'',15,0,0,0,'','','','','','','','','','','','','','',0,NULL,'','s','1',1,'',NULL,NULL,NULL,'  ','àºšàº»àº”àº¥àº²àºàº‡àº²àº™àºªàº°àºàº²àºšà»àº§àº”àº¥à»‰àº­àº¡ àºªàº›àº› àº¥àº²àº§   ',1,'','2006-10-13 16:44:23','2006-10-13 16:44:23',0,'a'),(25,'a','àº›àº·à»‰àº¡àº—àº»à»ˆàº§à»„àº›','','','',0,'',0,0,0,0,'','','','','','','','','','','','','','',0,'  ','  ','m','0',1,'','  ','  ','  ','  àº›àº·à»‰àº¡àº—àº»à»ˆàº§à»„àº›    ',' àº›àº·à»‰àº¡àº—àº»à»ˆàº§à»„àº›   ',1,'','2006-10-14 09:09:03','2006-10-16 07:23:21',0,'a'),(27,'a','àºšàº»àº”àºªàº°à»€à»œàºµàºà»ˆàº½àº§àºàº±àºšàº§àº´àº—àº°àºàº²àºªàº²àº”àºªàº´à»ˆàº‡à»àº§àº”àº¥à»‰àº­àº¡','','','',0,'',12,0,0,0,'','','','','121à»œà»‰àº²','àº¡àºµàºàº²àºšàº›àº°àºàº­àºš','','','','','','','','',2,'  ','  ','m','0',1,'12500àºàºµàºš','  ','  ','  ','  àºšàº»àº”àºªàº°à»€à»œàºµàºà»ˆàº½àº§àºàº±àºšàº§àº´àº—àº°àºàº²àºªàº²àº”àºªàº´à»ˆàº‡à»àº§àº”àº¥à»‰àº­àº¡    ',' àºšàº»àº”àºªàº°à»€à»œàºµàºà»ˆàº½àº§àºàº±àºšàº§àº´àº—àº°àºàº²àºªàº²àº”àºªàº´à»ˆàº‡à»àº§àº”àº¥à»‰àº­àº¡   ',1,'','2006-10-27 15:39:29','2006-10-27 15:39:29',0,'a'),(28,'a','àºàº²àº™','','','',0,'',0,0,0,0,'','','','','','','','','','','','','','',0,'  ','  ','m','0',1,'','  ','  ','  ','  àºàº²àº™    ',' àºàº²àº™   ',1,'','2006-11-03 18:59:28','2006-11-03 18:59:28',0,'a');
UNLOCK TABLES;
/*!40000 ALTER TABLE `notices` ENABLE KEYS */;

--
-- Table structure for table `notices_categories`
--

DROP TABLE IF EXISTS `notices_categories`;
CREATE TABLE `notices_categories` (
  `notcateg_notice` int(9) unsigned NOT NULL default '0',
  `num_noeud` int(9) unsigned NOT NULL default '0',
  `num_vedette` int(3) unsigned NOT NULL default '0',
  `ordre_vedette` int(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`notcateg_notice`,`num_noeud`,`num_vedette`),
  KEY `num_noeud` (`num_noeud`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notices_categories`
--


/*!40000 ALTER TABLE `notices_categories` DISABLE KEYS */;
LOCK TABLES `notices_categories` WRITE;
INSERT INTO `notices_categories` VALUES (1,2520,0,1),(2,2533,0,1),(3,2045,0,1),(4,2533,0,1),(5,2533,0,1),(6,2520,0,1),(7,2522,0,1),(8,2520,0,1),(9,2533,0,1),(10,2520,0,1),(11,2522,0,1),(12,2521,0,1),(14,2526,0,1),(15,2520,0,1),(18,2520,0,1),(19,2520,0,1),(19,2524,0,1),(20,2529,0,1),(21,2534,0,1),(22,2533,0,1),(23,2521,0,1),(24,2539,0,1),(1,2112,0,1),(2,2045,0,1),(3,2520,0,1),(4,1436,0,1),(5,1936,0,1),(6,2045,0,1),(6,2279,0,1),(7,1445,0,1),(8,1414,0,1),(9,1414,0,1),(10,1414,0,1),(11,1391,0,1),(12,1391,0,1),(13,1599,0,1),(14,1655,0,1),(15,2214,0,1),(16,1884,0,1),(17,1748,0,1),(18,1828,0,1),(19,1423,0,1),(19,1447,0,1),(24,1406,0,1),(25,1648,0,1),(25,1830,0,1),(25,2297,0,1),(26,1844,0,1),(29,1899,0,1),(30,1545,0,1),(31,1410,0,1),(32,1748,0,1),(33,1976,0,1),(35,1976,0,1),(36,1976,0,1),(37,1976,0,1),(38,1976,0,1),(39,1721,0,1),(39,1976,0,1),(41,1545,0,1),(41,1748,0,1),(42,1748,0,1),(44,1525,0,1),(47,1525,0,1),(47,1639,0,1),(48,1401,0,1),(49,1740,0,1),(50,1596,0,1),(51,2125,0,1),(53,2110,0,1),(54,1748,0,1),(58,2514,0,1),(27,2520,0,1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `notices_categories` ENABLE KEYS */;

--
-- Table structure for table `notices_custom`
--

DROP TABLE IF EXISTS `notices_custom`;
CREATE TABLE `notices_custom` (
  `idchamp` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `titre` varchar(255) default NULL,
  `type` varchar(10) NOT NULL default 'text',
  `datatype` varchar(10) NOT NULL default '',
  `options` text,
  `multiple` int(11) NOT NULL default '0',
  `obligatoire` int(11) NOT NULL default '0',
  `ordre` int(11) default NULL,
  PRIMARY KEY  (`idchamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notices_custom`
--


/*!40000 ALTER TABLE `notices_custom` DISABLE KEYS */;
LOCK TABLES `notices_custom` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `notices_custom` ENABLE KEYS */;

--
-- Table structure for table `notices_custom_lists`
--

DROP TABLE IF EXISTS `notices_custom_lists`;
CREATE TABLE `notices_custom_lists` (
  `notices_custom_champ` int(10) unsigned NOT NULL default '0',
  `notices_custom_list_value` varchar(255) default NULL,
  `notices_custom_list_lib` varchar(255) default NULL,
  `ordre` int(11) default NULL,
  KEY `notices_custom_champ` (`notices_custom_champ`),
  KEY `noti_champ_list_value` (`notices_custom_champ`,`notices_custom_list_value`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notices_custom_lists`
--


/*!40000 ALTER TABLE `notices_custom_lists` DISABLE KEYS */;
LOCK TABLES `notices_custom_lists` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `notices_custom_lists` ENABLE KEYS */;

--
-- Table structure for table `notices_custom_values`
--

DROP TABLE IF EXISTS `notices_custom_values`;
CREATE TABLE `notices_custom_values` (
  `notices_custom_champ` int(10) unsigned NOT NULL default '0',
  `notices_custom_origine` int(10) unsigned NOT NULL default '0',
  `notices_custom_small_text` varchar(255) default NULL,
  `notices_custom_text` text,
  `notices_custom_integer` int(11) default NULL,
  `notices_custom_date` date default NULL,
  `notices_custom_float` float default NULL,
  KEY `notices_custom_champ` (`notices_custom_champ`),
  KEY `notices_custom_origine` (`notices_custom_origine`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notices_custom_values`
--


/*!40000 ALTER TABLE `notices_custom_values` DISABLE KEYS */;
LOCK TABLES `notices_custom_values` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `notices_custom_values` ENABLE KEYS */;

--
-- Table structure for table `notices_global_index`
--

DROP TABLE IF EXISTS `notices_global_index`;
CREATE TABLE `notices_global_index` (
  `num_notice` mediumint(8) NOT NULL default '0',
  `no_index` mediumint(8) NOT NULL default '0',
  `infos_global` text NOT NULL,
  `index_infos_global` text NOT NULL,
  PRIMARY KEY  (`num_notice`,`no_index`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notices_global_index`
--


/*!40000 ALTER TABLE `notices_global_index` DISABLE KEYS */;
LOCK TABLES `notices_global_index` WRITE;
INSERT INTO `notices_global_index` VALUES (1,1,'   àºŠàºµàº§àº´àº” à»àº¥àº° àºœàº»àº™àº‡àº²àº™àº‚àº­àº‡àºàº£àº°àº¡àº°àº«àº²à»€àº–àº£àº°5 àº­àº»àº‡        àº„àº°àº™àº°àº­àº±àºàºªàº­àº™àºªàº²àº” àº¡/àºŠ  àº§àº±àº™àº™àº°àº„àº°àº”àºµ 050 àº§àº²àº¥àº°àºªàº²àº™àº—àº»à»ˆàº§à»„àº› - àº¥àº²àºàº›àºµ àº™àº°àº„àº­àº™àº«àº¥àº§àº‡ ','     5               àº„àº°àº™àº°àº­àº±àºàºªàº­àº™àºªàº²àº” àº¡/àºŠ      050 àº§àº²àº¥àº°àºªàº²àº™àº—àº»à»ˆàº§à»„àº› - àº¥àº²àºàº›àºµ   àº™àº°àº„àº­àº™àº«àº¥àº§àº‡  '),(2,1,'   àºàº»àº‡àºªàº²àº§àº°àº”àº²àº™àº¥àº²àº§ à»€àº–àº´àº‡ 1946        àºªàºµàº¥àº² àº§àº´àº¥àº°àº§àº»àº‡  àº›àº°àº«àº§àº±àº”àºªàº²àº” à»‚àº®àº‡àºàº´àº¡àº¡àº±àº™àº—àº²àº•àº¸àº¥àº²àº” ','     1946               àºªàºµàº¥àº² àº§àº´àº¥àº°àº§àº»àº‡      à»‚àº®àº‡àºàº´àº¡àº¡àº±àº™àº—àº²àº•àº¸àº¥àº²àº”  '),(3,1,'   à»€àº¡àº·à»ˆàº­àº‚à»‰àº­àºà»€àº›àº´àº”àºªàº°à»àº¸àº”àºšàº±àº™àº—àº¶àº        àº”àº³àº”àº§àº™ àºàº»àº¡àº”àº§àº‡àºªàºµ  àº§àº±àº™àº™àº°àº„àº°àº”àºµ à»‚àº®àº‡àºàº´àº¡à»àº«à»ˆàº‡àº¥àº±àº” ','     à»€àº¡àº·à»ˆàº­àº‚à»‰àº­àºà»€àº›àº´àº”àºªàº°à»àº¸àº”àºšàº±àº™àº—àº¶àº               àº”àº³àº”àº§àº™ àºàº»àº¡àº”àº§àº‡àºªàºµ      à»‚àº®àº‡àºàº´àº¡à»àº«à»ˆàº‡àº¥àº±àº”  '),(4,1,'   àº„àº­àº‡à»àºªàº™à»àºªàºšàº¢à»ˆàº²àºŠà»à»‰àº²àº®àº­àº        àºªàº°àº–àº²àºšàº±àº™àº„àº»àº™àº„àº§à»‰àº²àº§àº±àº”àº—àº°àº™àº°àº—àº³  àº›àº°àº«àº§àº±àº”àºªàº²àº” àºªàº°àº–àº²àºšàº±àº™ ','                    àºªàº°àº–àº²àºšàº±àº™àº„àº»àº™àº„àº§à»‰àº²àº§àº±àº”àº—àº°àº™àº°àº—àº³      àºªàº°àº–àº²àºšàº±àº™  '),(5,1,'   àº§àº´àº¥àº°àºàº³à»€àºˆàº»à»‰àº²àº­àº²àº™àº¸        àºªàº¸à»€àº™àº” à»‚àºàº—àº´àºªàº²àº™  àº›àº°àº«àº§àº±àº”àºªàº²àº” àºªàº°àº–àº²àºšàº±àº™ ','                    àºªàº¸à»€àº™àº” à»‚àºàº—àº´àºªàº²àº™      àºªàº°àº–àº²àºšàº±àº™  '),(6,1,'   àºàº²àºšà»€àº¡àº·àº­àº‡àºàº§àº™        àºšàº»àº§à»„àº‚ à»€àºàº±àº‡àºàº°àºˆàº±àº™  àº§àº±àº™àº™àº°àº„àº°àº”àºµ àºªàºµàºªàº°àº«àº§àº²àº”àºàº²àº™àºàº´àº¡ ','                    àºšàº»àº§à»„àº‚ à»€àºàº±àº‡àºàº°àºˆàº±àº™      àºªàºµàºªàº°àº«àº§àº²àº”àºàº²àº™àºàº´àº¡  '),(7,1,'   àºªàº°àºàº¸àº™àº•àº»à»‰àº™àº”àº­àºà»€àºœàº´à»‰àº‡àº‚àº­àº‡àº›àº°à»€àº—àº”à»„àº—,àº¥àº²àº§        àºšàº¸àº™àº¡àºµ à»€àº—àºšàºªàºµà»€àº¡àº·àº­àº‡  àº—àº³àº¡àº°àºŠàº²àº” àºàº¸àº‡à»€àº—àºš ','                    àºšàº¸àº™àº¡àºµ à»€àº—àºšàºªàºµà»€àº¡àº·àº­àº‡      àºàº¸àº‡à»€àº—àºš  '),(8,1,'   àº—à»‰àº²àº§àºªàº¸àº£àº°àº™àº²àº¥àºµ àºšàº²àº‡àº—àº±àº”àºªàº°àº™àº°àº‚àº­àº‡àº„àº»àº™à»„àº—        àº„àº³àºœàº²àº àºšàº¸àºšàºœàº²  àº§àº±àº™àº™àº°àº„àº°àº”àºµ àº›àº²àºàº›àº²àºªàº±àºàºàº²àº™àºàº´àº¡ ','                    àº„àº³àºœàº²àº àºšàº¸àºšàºœàº²      àº›àº²àºàº›àº²àºªàº±àºàºàº²àº™àºàº´àº¡  '),(9,1,'   àº›àº°àº«àº§àº±àº”àºªàº²àº”àº¥àº²àº§ 1946        àºªàº¸àºˆàº´àº” àº§àº»àº‡à»€àº—àºš  àº›àº°àº«àº§àº±àº”àºªàº²àº” àº­àº»àº‡àºàº²àº™àº­àº°àº™àº²à»„àº¡à»‚àº¥àº ','     1946               àºªàº¸àºˆàº´àº” àº§àº»àº‡à»€àº—àºš      àº­àº»àº‡àºàº²àº™àº­àº°àº™àº²à»„àº¡à»‚àº¥àº  '),(10,1,'   àºàº²àº™àº›àº½àºšàº—àº½àºšàºœàº»àº™àºªàº»àº¡àº—àº²àº‡àº”à»‰àº²àº™àº„àº°àº™àº´àº”àºªàº²àº”        àºšàº¸àº™àºªàºµ àºšàº¹àº¥àº»àº¡  àº§àº±àº™àº™àº°àº„àº°àº”àºµ àºªàº³àº™àº±àºàºàº´àº¡à»àº¥àº°àºˆàº³à»œà»ˆàº²àºàº›àº·àº¡ ','                    àºšàº¸àº™àºªàºµ àºšàº¹àº¥àº»àº¡      àºªàº³àº™àº±àºàºàº´àº¡à»àº¥àº°àºˆàº³à»œà»ˆàº²àºàº›àº·àº¡  '),(11,1,'   àºàº»àº”à»àº²àºàº›à»ˆàº²à»„àº¡à»‰        àºàº»àº¡àº›à»ˆàº²à»„àº¡à»‰  àº—àº³àº¡àº°àºŠàº²àº” àº™àº°àº„àº­àº™àº«àº¥àº§àº‡ ','                    àºàº»àº¡àº›à»ˆàº²à»„àº¡à»‰      àº™àº°àº„àº­àº™àº«àº¥àº§àº‡  '),(12,1,'   àº®àº´àº”àº„àº­àº‡àº›àº°à»€àºàº™àºµàº¥àº²àº§        àºªàº¸à»€àº™àº” à»‚àºàº—àº´àºªàº²àº™  àºàº»àº¡àºàº²àº™à»€àº¡àº·àº­àº‡ à»àº¥àº° àºàº²àº™àº›àº»àºàº„àº­àº‡  à»‚àº„àºˆàº­àº™ à»àºà»‰àº§àº¡àº°àº™àºµàº§àº»àº‡  àº§àº´àº—àº°àºàº²àºªàº²àº” à»‚àº®àº‡àºàº´àº¡àºªàº¶àºàºªàº² ','                    àºªàº¸à»€àº™àº” à»‚àºàº—àº´àºªàº²àº™   àºàº»àº¡àºàº²àº™à»€àº¡àº·àº­àº‡ à»àº¥àº° àºàº²àº™àº›àº»àºàº„àº­àº‡   à»‚àº„àºˆàº­àº™ à»àºà»‰àº§àº¡àº°àº™àºµàº§àº»àº‡      à»‚àº®àº‡àºàº´àº¡àºªàº¶àºàºªàº²  '),(13,1,'   à»àº™àº§àº—àº²àº‡àºàº²àº™àº”àº³à»€àº™àºµàº™àº‡àº²àº™àºªàº³àº¥àº±àºšàº„àº°àº™àº°àºàº³àº¡àº°àºàº²àº™        àº­àº»àº‡àºàº²àº™àº­àº°àº™àº²à»„àº¡à»‚àº¥àº  àºªàº°àºàº²àº™àº—àº­àº‡àºàº²àº™àºàº´àº¡ ','                    àº­àº»àº‡àºàº²àº™àº­àº°àº™àº²à»„àº¡à»‚àº¥àº   àºªàº°àºàº²àº™àº—àº­àº‡àºàº²àº™àºàº´àº¡  '),(14,1,'   àº„àº»àº™àº„àº§à»‰àº²àº§àº´àº—àº°àºàº²àºªàº²àº”àº—àº²àº‡àº”à»‰àº²àº™àº§àº´àºŠàº²àºàº²àº™à»àºàº”        àº„àº³àºœàº²àº àºšàº¸àºšàºœàº²  àºàº»àº”à»œàº²àº àº‚àº­àº™à»àºà»ˆàº™ ','                    àº„àº³àºœàº²àº àºšàº¸àºšàºœàº²      àº‚àº­àº™à»àºà»ˆàº™  '),(15,1,'   àº®àº´àº”àº„àº­àº‡àº›àº°à»€àºàº™àºµàº¥àº²àº§ 2    àº®àº´àº”àº„àº­àº‡àº›àº°à»€àºàº™àºµàº¥àº²àº§     àº„àº°àº™àº°àº­àº±àºàºªàº­àº™àºªàº²àº” àº¡/àºŠ  àº§àº±àº™àº™àº°àº„àº°àº”àºµ àºªàº³àº™àº±àºàºàº´àº¡à»àº¥àº°àºˆàº³à»œà»ˆàº²àºàº›àº·àº¡ ','     2               àº„àº°àº™àº°àº­àº±àºàºªàº­àº™àºªàº²àº” àº¡/àºŠ      àºªàº³àº™àº±àºàºàº´àº¡à»àº¥àº°àºˆàº³à»œà»ˆàº²àºàº›àº·àº¡  '),(16,1,'   àº•àº³àº¥àº²àº¢àº²àºàº·àº™à»€àº¡àº·àº­àº‡     àº•àº³àº¥àº²àº¢àº²àºàº·àº™à»€àº¡àº·àº­àº‡ àº—àºµà»ˆàº¡àºµàº„àº¸àº™àº›àº°à»‚àº«àºàº”àº—àº²àº‡àºàº²àº™à»àºàº”   àºšàº¸àº™àºªàºµ àºšàº¹àº¥àº»àº¡  àº™àº°àº„àº­àº™àº«àº¥àº§àº‡ ','                    àºšàº¸àº™àºªàºµ àºšàº¹àº¥àº»àº¡   àº™àº°àº„àº­àº™àº«àº¥àº§àº‡  '),(17,1,'   àº§àº´àº—àºµàº®àº±àºàºªàº²àº„àº§àº²àº¡àº‡àº²àº¡     àºàº²àº™àº®àº±àºàºªàº²àº„àº§àº²àº¡àº‡àº²àº¡   àºšàº»àº§à»„àº‚ à»€àºàº±àº‡àºàº°àºˆàº±àº™  àº›àº²àºàº›àº²àºªàº±àºàºàº²àº™àºàº´àº¡ ','                    àºšàº»àº§à»„àº‚ à»€àºàº±àº‡àºàº°àºˆàº±àº™   àº›àº²àºàº›àº²àºªàº±àºàºàº²àº™àºàº´àº¡  '),(18,1,'  àºŠàºµàº§àº´àº” à»àº¥àº° àºœàº»àº™àº‡àº²àº™        àºªàºµàº¥àº² àº§àº´àº¥àº°àº§àº»àº‡  àº§àº±àº™àº™àº°àº„àº°àº”àºµ àºªàº°àº–àº²àºšàº±àº™ ','          àºªàºµàº¥àº² àº§àº´àº¥àº°àº§àº»àº‡      àºªàº°àº–àº²àºšàº±àº™  '),(19,1,'  àº„àº¹à»ˆàº¡àº·àºªàº³àº¥àº±àºšàº„àº¹à»ˆàºªàº­àº™        àº¡àº¹àº™àº™àº´àº—àº´àºŠàº²àºŠàº²àºàº²àº§àº² à»€àºàº·à»ˆàº­àºªàº±àº™àº•àº´àºàº²àºš  àº§àº±àº™àº™àº°àº„àº°àº”àºµ àº§àº±àº™àº™àº°àº„àº°àº”àºµàº¥àº²àº§ à»‚àº®àº‡àºàº´àº¡àºªàº¶àºàºªàº² ','          àº¡àº¹àº™àº™àº´àº—àº´àºŠàº²àºŠàº²àºàº²àº§àº² à»€àºàº·à»ˆàº­àºªàº±àº™àº•àº´àºàº²àºš         à»‚àº®àº‡àºàº´àº¡àºªàº¶àºàºªàº²  '),(20,1,'  à»€àº­àºàº°àºªàº²àº™à»€àºàºµà»ˆàº¡àº—àº°àº§àºµàº„àº§àº²àº¡àºªàº²àº¡àº±àºàº„àºµ        àº”àº³àº”àº§àº™ àºàº»àº¡àº”àº§àº‡àºªàºµ  àºàº»àº”à»œàº²àº àº¥àº²àº§ àº›àº²àºàº›àº²àºªàº±àºàºàº²àº™àºàº´àº¡ ','   à»€àº­àºàº°àºªàº²àº™à»€àºàºµà»ˆàº¡àº—àº°àº§àºµàº„àº§àº²àº¡àºªàº²àº¡àº±àºàº„àºµ          àº”àº³àº”àº§àº™ àºàº»àº¡àº”àº§àº‡àºªàºµ      àº›àº²àºàº›àº²àºªàº±àºàºàº²àº™àºàº´àº¡  '),(22,1,'  àºàº¹àº¡àº›àº±àº™àºàº²àºšàº¹àº®àº²àº™àº¥àº²àº§        àº„àº°àº™àº°àºˆàº±àº”àº•àº±àº‡àºªàº¹àº™àºàº²àº‡àºàº±àº  àº›àº°àº«àº§àº±àº”àºªàº²àº” 320 àºàº²àº™à»€àº¡àº·àº­àº‡ àºàº²àº™àº›àº»àºàº„àº­àº‡ ','          àº„àº°àº™àº°àºˆàº±àº”àº•àº±àº‡àºªàº¹àº™àºàº²àº‡àºàº±àº      320 àºàº²àº™à»€àº¡àº·àº­àº‡   àºàº²àº™àº›àº»àºàº„àº­àº‡  '),(23,1,'  à»àº„àº™ à»àº¥àº° àºªàº½àº‡à»àº„àº™        àº—àº­àº‡àº¡àº²àº¥àºµ àºªàº¸àº¥àº²àº”  àº§àº´àº—àº°àºàº²àºªàº²àº” àºªàºµàºªàº°àº«àº§àº²àº”àºàº²àº™àºàº´àº¡ ','          àº—àº­àº‡àº¡àº²àº¥àºµ àºªàº¸àº¥àº²àº”      àºªàºµàºªàº°àº«àº§àº²àº”àºàº²àº™àºàº´àº¡  '),(26,1,'   dfhsdfh        ','      dfhsdfh                 '),(25,1,'   àº›àº·à»‰àº¡àº—àº»à»ˆàº§à»„àº›        ','      àº›àº·à»‰àº¡àº—àº»à»ˆàº§à»„àº›                 '),(24,1,'  àºšàº»àº”àº¥àº²àºàº‡àº²àº™àºªàº°àºàº²àºšà»àº§àº”àº¥à»‰àº­àº¡ àºªàº›àº› àº¥àº²àº§        àºàº»àº¡àº›à»ˆàº²à»„àº¡à»‰  àº›à»ˆàº²à»„àº¡à»‰ àº¡àº¹àº™àº™àº´àº—àº´àºŠàº²àºŠàº²àºàº²àº§àº² ','          àºàº»àº¡àº›à»ˆàº²à»„àº¡à»‰      àº¡àº¹àº™àº™àº´àº—àº´àºŠàº²àºŠàº²àºàº²àº§àº²  '),(29,1,'   Bagnes à Madagascar         Géo   Un nouveau monde : la Terre Madagascar 910 Géographie - voyages ','  jojo   bagnes madagascar               geo nouveau monde terre   madagascar   910 geographie voyages  '),(30,1,'   Tatars de Crimée         Géo   Un nouveau monde : la Terre Voyage 910 Géographie - voyages ','  jojo   tatars crimee               geo nouveau monde terre   voyage   910 geographie voyages  '),(31,1,'   Marigot africain         Géo   Un nouveau monde : la Terre Afrique ','  jojo   marigot africain               geo nouveau monde terre   afrique  '),(32,1,'   Chateaux de la Loire (2)         Géo   Un nouveau monde : la Terre Pays de la Loire 910 Géographie - voyages ','  jojo   chateaux loire 2               geo nouveau monde terre   pays loire   910 geographie voyages  '),(33,1,'   Paysages afghans         Géo   Un nouveau monde : la Terre Afghanistan 910 Géographie - voyages ','  jojo   paysages afghans               geo nouveau monde terre   afghanistan   910 geographie voyages  '),(35,1,'   Peuples d\'Afghanistan         Géo   Un nouveau monde : la Terre Afghanistan 910 Géographie - voyages ','  jojo   peuples afghanistan               geo nouveau monde terre   afghanistan   910 geographie voyages  '),(36,1,'   Tribus Pachtounes         Géo   Un nouveau monde : la Terre Afghanistan 910 Géographie - voyages ','  jojo   tribus pachtounes               geo nouveau monde terre   afghanistan   910 geographie voyages  '),(37,1,'   femmes afghanes         Géo   Un nouveau monde : la Terre Afghanistan ','  jojo   femmes afghanes               geo nouveau monde terre   afghanistan  '),(38,1,'   Histoire de l\'Afghanistan         Géo   Un nouveau monde : la Terre Afghanistan 910 Géographie - voyages ','  jojo   histoire afghanistan               geo nouveau monde terre   afghanistan   910 geographie voyages  '),(39,1,'   Islam afghan         Géo   Un nouveau monde : la Terre Islam Afghanistan ','  jojo   islam afghan               geo nouveau monde terre   islam   afghanistan  '),(40,1,'   Famille Allix         Géo   Un nouveau monde : la Terre ','  jojo   famille allix               geo nouveau monde terre  '),(41,1,'   Chateaux de la Loire (1)       chateau loire chenonceau chambord cheverny  Géo   Un nouveau monde : la Terre Voyage Pays de la Loire 910 Géographie - voyages ','  jojo   chateaux loire 1            chateau loire chenonceau chambord cheverny   geo nouveau monde terre   voyage   pays loire   910 geographie voyages  '),(42,1,'   Charte du XIIIe siècle, par laquelle Guillaume de Rezay de la paroisse de Ceaux (Maine et Loire) vend à Messire de Vernée, chevalier, sept sous et six deniers de rente.   Acte passé en la cour d\'Angers le jeudi avant la Saint Urbain l\'an mille deux cent quatre vingt dix neuf.  excellent état de conservation date en vieux style (V.ST.) - M. DU POUGET, archiviste-paléographe de l\'Indre, a bien voulu attirer mon attention sur le fait que cette charte était datée du joedi devant la Saint Alban (Saint Aubin d\'Angers, qui se fête le 1er mars - Pâques tombant en 1299 le 19 avril, il y a effectivement bien lieu de considérer que cette charte est du 25 février 1300, nouveau style (N.ST.) charte rente archive Ceaux paroisse cens Angers Maine-et-Loire Rezay Guillaume de Pays de la Loire 940 Histoire de l\'Europe ','  jojo   charte xiiie siecle par laquelle guillaume rezay paroisse ceaux maine loire vend messire vernee chevalier sept sous six deniers rente acte passe cour angers jeudi avant saint urbain an mille deux cent quatre vingt dix neuf      excellent etat conservation   date vieux style v st m pouget archiviste paleographe indre bien voulu attirer mon attention sur fait que cette charte etait datee joedi devant saint alban saint aubin angers qui se fete 1er mars paques tombant 1299 19 avril il y effectivement bien lieu considerer que cette charte est 25 fevrier 1300 nouveau style n st   charte rente archive ceaux paroisse cens angers maine loire   rezay guillaume   pays loire   940 histoire europe  '),(44,1,'   Bruit de cochon     Bruitage courts. Bonne qualité d\'enregistrement.  cochon porc truie verrat porcelet goret cochette suidés artiodactyles groin sound-fishing.net  Mammifères 590 Zoologie - (les animaux) sound-fishing.net ','  jojo   bruit cochon      bruitage courts bonne qualite enregistrement      cochon porc truie verrat porcelet goret cochette suides artiodactyles groin   sound fishing net   mammiferes   590 zoologie animaux   sound fishing net  '),(48,1,'   Canne   à pommeau en forme de cochon  canne en bois précieux, bichromie, pommeau sculpté et peint  canne cochon pied porc pommeau argent ouvrage précieux sculpture\r\n Favulier Jacques Sculpture 680 Articles manufacturés ','  jojo   canne pommeau forme cochon      canne bois precieux bichromie pommeau sculpte peint      canne cochon pied porc pommeau argent ouvrage precieux sculpture   favulier jacques   sculpture   680 articles manufactures  '),(46,1,'   L\'adagio d\'Albinoni    Canon de Pachelbel, Jésus que ma joie demeure de J.S. Bach, Andante pour mandoline de Vivaldi, Menuet de Mozart, Menuet de Boccherini  On connaît mal ce compositeur vénitien exactement contemporain de Vivaldi, mais une seule œuvre, pourtant, a assuré sa notoriété, l’Adagio pour cordes, extrait en fait du Concerto en ré majeur. Cette longue cantilène plaintive a servi au film Quatre mariages et un enterrement.  Marion Alain Bride Philip 780 Musique Forlane ','     adagio albinoni   canon pachelbel jesus que ma joie demeure j s bach andante pour mandoline vivaldi menuet mozart menuet boccherini      on connait mal ce compositeur venitien exactement contemporain vivaldi mais seule uvre pourtant assure sa notoriete adagio pour cordes extrait fait concerto re majeur cette longue cantilene plaintive servi film quatre mariages enterrement      marion alain   bride philip   780 musique   forlane  '),(47,1,'   Couverture du magazine rustica   Ce que doit être le porc parfait \" Ce que doit être le porc parfait \" mentionné en couverture    Mammifères Mammifères 590 Zoologie - (les animaux) Rustica ','  jojo   couverture magazine rustica ce que doit etre porc parfait   \" ce que doit etre porc parfait \" mentionne couverture            mammiferes   mammiferes   590 zoologie animaux   rustica  '),(49,1,'   Tours. N°65. Flle 78     Carte de Cassini Cote : Ge FF 18595 (65) BNF Richelieu Cartes et Plans Reprod. Sc 96/614\r\n. - Carte levée entre 1760 et 1762 par Bottin, Langelay, vérifiée en 1763 et 1764 par La Briffe Ponsan. Lettre par Chambon. 78e feuille publiée. Tours Indre-et-Loire France Cassini de Thury César-François Centre 910 Géographie - voyages Dépôt de la Guerre ','  jojo   tours n 65 flle 78      carte cassini   cote ge ff 18595 65 bnf richelieu cartes plans reprod sc 96 614 carte levee entre 1760 1762 par bottin langelay verifiee 1763 1764 par briffe ponsan lettre par chambon 78e feuille publiee   tours indre loire france   cassini thury cesar francois   centre   910 geographie voyages   depot guerre  '),(50,1,'   Le Cochon d\'Hollywood       cochon porc hollywood acteur studio cinéma Fraxler Hans Livre Collection Folio benjamin Gallimard ','  jojo   cochon hollywood            cochon porc hollywood acteur studio cinema   fraxler hans   livre   collection folio benjamin   gallimard  '),(51,1,'   Le Porc et les produits de la charcuterie, hygiène, inspection, règlementation, par Th. Bourrier,..      Exemples illustrés, gravures représentant une ferme en Indre-et-Loire Indre-et-Loire ferme porc élevage verrat truie porcelet cochelle Bourrier Théodore Aliments 640 Arts ménagers - cuisine, coutûre, soins de beauté Asselin et Houzeau ','  jojo   porc produits charcuterie hygiene inspection reglementation par th bourrier         exemples illustres gravures representant ferme indre loire   indre loire ferme porc elevage verrat truie porcelet cochelle   bourrier theodore   aliments   640 arts menagers cuisine couture soins beaute   asselin houzeau  '),(53,1,'   Nimitz   roman     Langlois-Chassaignon Claudie Robinson Patrick Roman et nouvelle 800 Littérature A. Michel ','  jojo   nimitz roman               langlois chassaignon claudie   robinson patrick   roman nouvelle   800 litterature   michel  '),(54,1,'   Études archéologiques dans la Loire-Inférieure, ...   Arrondissements de Nantes et de Paimboeuf    Loire-Atlantique Orieux Eugène Pays de la Loire 910 Géographie - voyages impr. de Mme Vve Mellinet ','  jojo   etudes archeologiques dans loire inferieure arrondissements nantes paimboeuf            loire atlantique   orieux eugene   pays loire   910 geographie voyages   impr mme vve mellinet  '),(57,1,'   Germinal        Pichard Georges Zola Émile BD adultes Média 1000 ','  jojo   germinal               pichard georges   zola emile   bd adultes   media 1000  '),(58,1,'   àºàº»àº‡àºªàº²àº§àº°àº”àº²àº™àº¥àº²àº§ à»€àº–àº´àº‡ 1946     àºà»ˆàº½àº§àºàº±àºšàº›àº°àº«àº§àº±àº”àºªàº²àº”, à»†àº¥à»†   àºªàº´àº™àº¥àº°àº›àº° à»àº¥àº°àº§àº±àº”àº—àº°àº™àº°àº—àº³ à»‚àº®àº‡àºàº´àº¡àº¡àº±àº™àº—àº²àº•àº¸àº¥àº²àº” ','     1946                    '),(65,1,'   àº„àº­àº‡à»àºªàº™à»àºªàºšàº¢à»ˆàº²àºŠàº³àº®àº­àº        àºªàº°àº–àº²àºšàº±àº™àº„àº»àº™àº„àº§à»‰àº²àº§àº±àº”àº—àº°àº™àº°àº—àº³  àºªàº°àº–àº²àºšàº±àº™ ','                         '),(59,1,'   àº—àº»àº”àº¥àº­àº‡        àºªàº¹àº™àºàº²àº‡àºªàº°àº«àº°àºàº±àº™àºàº³àº¡àº°àºšàº²àº™àº¥àº²àº§  àºªàº°àº–àº²àºšàº±àº™ ','                         '),(60,1,'   àºàº­àº‡àº›àº°àºŠàº¸àº¡àºªàº°àº«àº°àºàº±àº™àºàº³àº¡àº°àºšàº²àº™àº¥àº²àº§ IV    àºªàº°àº«àº¼àº¸àºšàºœàº»àº™àºªàº³à»€àº¥àº±àº”àº‚àº­àº‡àºàº­àº‡àº›àº°àºŠàº¹àº¡ àºàº­àº‡àº›àº°àºŠàº¹àº¡  àºà»ˆàº½àº§àºàº±àºšàºàº­àº‡àº›àº°àºŠàº¹àº¡ àºªàº¹àº™àºàº²àº‡àºªàº°àº«àº°àºàº±àº™àºàº³àº¡àº°àºšàº²àº™àº¥àº²àº§  000 àº‚à»à»‰àº¡àº¹àº™ àºàº²àº™àº•àº´àº”àº•à»à»ˆàºŠàº·à»ˆàºªàº²àº™ àº™àº°àº„àº­àº™àº«àº¥àº§àº‡ ','     iv                  000     '),(64,1,'   àº‚à»à»‰àº¡àº¹àº™àºªàº³àº®àº­àº‡        àº„àº°àº™àº°àº­àº±àºàºªàº­àº™àºªàº²àº” àº¡/àºŠ  àº«à»àºàº´àºàº´àº—àº°àºàº±àº™ ','                         '),(61,1,'  àº§àº´àº¥àº°àºàº³à»€àºˆàº»à»‰àº²àº­àº°àº™àº¸     àº›àº°àº«àº§àº±àº”à»€àºˆàº»à»‰àº²àº­àº²àº™àº¸  àº›àº°àº«àº§àº±àº” àºªàº¸à»€àº™àº” à»‚àºàº—àº´àºªàº²àº™  000 àº‚à»à»‰àº¡àº¹àº™ àºàº²àº™àº•àº´àº”àº•à»à»ˆàºŠàº·à»ˆàºªàº²àº™ àºªàº°àº–àº²àºšàº±àº™ ','      bravo test reusi             000     '),(63,1,'   àºàº²àºšà»€àº¡àº·àº­àº‡àºàº§àº™     àºàº²àºšàºàº­àº™   àº„àº°àº™àº°àº­àº±àºàºªàº­àº™àºªàº²àº” àº¡/àºŠ  000 àº‚à»à»‰àº¡àº¹àº™ àºàº²àº™àº•àº´àº”àº•à»à»ˆàºŠàº·à»ˆàºªàº²àº™ àº«à»àºàº´àºàº´àº—àº°àºàº±àº™ ','                       000     '),(27,1,'   àºšàº»àº”àºªàº°à»€à»œàºµàºà»ˆàº½àº§àºàº±àºšàº§àº´àº—àº°àºàº²àºªàº²àº”àºªàº´à»ˆàº‡à»àº§àº”àº¥à»‰àº­àº¡        àº„àº³àºœàº²àº àºšàº¸àºšàºœàº²  àº§àº±àº™àº™àº°àº„àº°àº”àºµ 010 àº„àº§àº²àº¡àº®àº¹à»‰àºà»ˆàº½àº§àºàº±àºšàº«à»àºªàº°à»àº¸àº” àºàº²àº™àº›àº»àºàº„àº­àº‡ ','      àºšàº»àº”àºªàº°à»€à»œàºµàºà»ˆàº½àº§àºàº±àºšàº§àº´àº—àº°àºàº²àºªàº²àº”àºªàº´à»ˆàº‡à»àº§àº”àº¥à»‰àº­àº¡                  àº„àº³àºœàº²àº àºšàº¸àºšàºœàº²      010 àº„àº§àº²àº¡àº®àº¹à»‰àºà»ˆàº½àº§àºàº±àºšàº«à»àºªàº°à»àº¸àº”   àºàº²àº™àº›àº»àºàº„àº­àº‡  '),(28,1,'   àºàº²àº™        ','      àºàº²àº™                 ');
UNLOCK TABLES;
/*!40000 ALTER TABLE `notices_global_index` ENABLE KEYS */;

--
-- Table structure for table `notices_langues`
--

DROP TABLE IF EXISTS `notices_langues`;
CREATE TABLE `notices_langues` (
  `num_notice` int(8) unsigned NOT NULL default '0',
  `type_langue` int(1) unsigned NOT NULL default '0',
  `code_langue` char(3) NOT NULL default '',
  PRIMARY KEY  (`num_notice`,`type_langue`,`code_langue`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notices_langues`
--


/*!40000 ALTER TABLE `notices_langues` DISABLE KEYS */;
LOCK TABLES `notices_langues` WRITE;
INSERT INTO `notices_langues` VALUES (1,0,'lao'),(1,1,'lao'),(2,0,'lao'),(2,1,'lao'),(3,0,'lao'),(3,1,'lao'),(4,0,'lao'),(4,1,'lao'),(5,0,'lao'),(5,1,'lao'),(6,0,'lao'),(6,1,'lao'),(7,0,'lao'),(7,1,'lao'),(8,0,'lao'),(8,1,'lao'),(9,0,'lao'),(9,1,'lao'),(10,0,'lao'),(10,1,'lao'),(11,0,'lao'),(11,1,'lao'),(12,0,'lao'),(13,0,'lao'),(13,1,'lao'),(14,0,'lao'),(14,1,'lao'),(15,0,'lao'),(16,0,'lao'),(17,0,'lao'),(18,0,'lao'),(19,0,'lao'),(20,0,'lao'),(21,0,'lao'),(21,1,'lao'),(22,0,'lao'),(23,0,'lao'),(24,0,'lao'),(25,0,'lao'),(27,0,'lao'),(27,1,'lao'),(28,0,'lao');
UNLOCK TABLES;
/*!40000 ALTER TABLE `notices_langues` ENABLE KEYS */;

--
-- Table structure for table `offres_remises`
--

DROP TABLE IF EXISTS `offres_remises`;
CREATE TABLE `offres_remises` (
  `num_fournisseur` int(5) unsigned NOT NULL default '0',
  `num_produit` int(8) unsigned NOT NULL default '0',
  `remise` float(4,2) unsigned NOT NULL default '0.00',
  `condition_remise` text,
  PRIMARY KEY  (`num_fournisseur`,`num_produit`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `offres_remises`
--


/*!40000 ALTER TABLE `offres_remises` DISABLE KEYS */;
LOCK TABLES `offres_remises` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `offres_remises` ENABLE KEYS */;

--
-- Table structure for table `opac_sessions`
--

DROP TABLE IF EXISTS `opac_sessions`;
CREATE TABLE `opac_sessions` (
  `empr_id` int(10) unsigned NOT NULL default '0',
  `session` blob,
  `date_rec` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`empr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `opac_sessions`
--


/*!40000 ALTER TABLE `opac_sessions` DISABLE KEYS */;
LOCK TABLES `opac_sessions` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `opac_sessions` ENABLE KEYS */;

--
-- Table structure for table `origine_notice`
--

DROP TABLE IF EXISTS `origine_notice`;
CREATE TABLE `origine_notice` (
  `orinot_id` int(8) unsigned NOT NULL auto_increment,
  `orinot_nom` varchar(255) NOT NULL default '',
  `orinot_pays` varchar(255) NOT NULL default 'FR',
  `orinot_diffusion` int(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`orinot_id`),
  KEY `orinot_nom` (`orinot_nom`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `origine_notice`
--


/*!40000 ALTER TABLE `origine_notice` DISABLE KEYS */;
LOCK TABLES `origine_notice` WRITE;
INSERT INTO `origine_notice` VALUES (1,'Catalogage interne','FR',1),(2,'BnF','FR',1),(3,'àºàº°àºŠàº§àº‡àºªàº¶àºàºªàº²àº—àº´àºàº²àº™','LA',1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `origine_notice` ENABLE KEYS */;

--
-- Table structure for table `ouvertures`
--

DROP TABLE IF EXISTS `ouvertures`;
CREATE TABLE `ouvertures` (
  `date_ouverture` date NOT NULL default '0000-00-00',
  `ouvert` int(1) NOT NULL default '1',
  `commentaire` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`date_ouverture`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ouvertures`
--


/*!40000 ALTER TABLE `ouvertures` DISABLE KEYS */;
LOCK TABLES `ouvertures` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `ouvertures` ENABLE KEYS */;

--
-- Table structure for table `paiements`
--

DROP TABLE IF EXISTS `paiements`;
CREATE TABLE `paiements` (
  `id_paiement` int(8) unsigned NOT NULL auto_increment,
  `libelle` varchar(255) NOT NULL default '',
  `commentaire` text NOT NULL,
  PRIMARY KEY  (`id_paiement`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `paiements`
--


/*!40000 ALTER TABLE `paiements` DISABLE KEYS */;
LOCK TABLES `paiements` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `paiements` ENABLE KEYS */;

--
-- Table structure for table `parametres`
--

DROP TABLE IF EXISTS `parametres`;
CREATE TABLE `parametres` (
  `id_param` int(6) unsigned NOT NULL auto_increment,
  `type_param` varchar(20) default NULL,
  `sstype_param` varchar(255) default NULL,
  `valeur_param` text,
  `comment_param` varchar(255) default NULL,
  `section_param` varchar(255) NOT NULL default '',
  `gestion` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id_param`),
  UNIQUE KEY `typ_sstyp` (`type_param`,`sstype_param`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `parametres`
--


/*!40000 ALTER TABLE `parametres` DISABLE KEYS */;
LOCK TABLES `parametres` WRITE;
INSERT INTO `parametres` VALUES (1,'pmb','bdd_version','v4.26','Version de noyau de la base de données, à ne changer qu\'en version inférieure si un paramètre était mal passé et relancer la mise à jour. En général, contactez plutôt la mailing liste pmb.user@sigb.net','',0),(2,'z3950','accessible','1','Z3950 accessible ?\r\n 0 : non, menu inaccessible\r\n 1 : Oui, la librairie PHP_YAZ est activée, la recherche z3950 est possible','',0),(3,'pmb','nb_lastautorities','10','Nombre de dernières autoritées affichées en gestion d\'autorités','',0),(4,'pdflettreretard','1before_list','àºàº»àºà»€àº§àº±à»‰àº™àº‚à»à»‰àºœàº´àº”àºàº²àº”àº‚àº­àº‡àº—àº²àº‡à»€àº®àº»àº², àº—à»ˆàº²àº™àº¡àºµàºªàº´àº”à»ƒàº™à»œàº¶à»ˆàº‡àº«àº¼àº·àº«àº¼àº²àºà»€àº­àºàº°àºªàº²àº™ à»€àºŠàº´à»ˆàº‡à»„àº¥àºàº°à»€àº§àº¥àº²àº‚àº­àº‡àºàº²àº™à»ƒàº«à»‰àº¢àº·àº¡à»àº¡à»ˆàº™à»„àº”à»‰àºàº²àºàºàº³àº™àº»àº”àº¡àº·à»‰àº™àºµà»‰','Texte apparaissant avant la liste des ouvrages en retard dans le courrier de relance de retard','',0),(5,'pdflettreretard','1after_list','àºàº§àºà»€àº®àº»àº²àº‚à»àº‚àº­àºšà»ƒàºˆàº™àº³àº—à»ˆàº²àº™àº—àºµà»ˆàºˆàº°àº•àº´àº”àº•à»à»ˆàºàº§àºà»€àº®àº»àº²à»‚àº”àºàº—àº²àº‡à»‚àº—àº¥àº°àºªàº±àºš à»œàº²àºà»€àº¥àº $biblio_phone àº«àº¼àº· à»‚àº”àº email $biblio_email à»€àºàº·à»ˆàº­àºªàº¶àºàºªàº²àº„àº§àº²àº¡à»€àº›àº±àº™à»„àº›à»„àº”à»‰àº‚àº­àº‡àºàº²àº™àº•à»à»ˆà»€àº§àº¥àº²àºàº²àº™à»ƒàº«à»‰àº¢àº·àº¡ àº«àº¼àº·àºªàº»à»ˆàº‡à»€àº­àºàº°àºªàº²àº™àº„àº·àº™','Texte apparaissant aprï¿½s la liste des ouvrages en retard dans le courrier','',0),(6,'pdflettreretard','1fdp','àºœàº¹à»‰àº®àº±àºšàºœàº´àº”àºŠàº­àºš.','Signataire de la lettre.','',0),(7,'pdflettreretard','1madame_monsieur','àº—à»ˆàº²àº™àºàº´àº‡, àº—à»ˆàº²àº™àºŠàº²àº ,','Entï¿½te de la lettre','',0),(8,'pdflettreretard','1nb_par_page','7','Nombre d\'ouvrages en retard imprimé sur les pages suivantes.','',0),(9,'pdflettreretard','1nb_1ere_page','4','Nombre d\'ouvrages en retard imprimé sur la première page','',0),(10,'pdflettreretard','1taille_bloc_expl','16','Taille d\'un bloc (2 lignes) d\'ouvrage en retard. Le début de chaque ouvrage en retard sera espacé de cette valeur sur la page','',0),(11,'pdflettreretard','1debut_expl_1er_page','160','Début de la liste des exemplaires sur la première page, en mm depuis le bord supérieur de la page. Doit être règlé en fonction du texte qui précède la liste des ouvrages, lequel peut être plus ou moins long.','',0),(12,'pdflettreretard','1debut_expl_page','15','Début de la liste des exemplaires sur les pages suivantes, en mm depuis le bord supérieur de la page.','',0),(13,'pdflettreretard','1limite_after_list','270','Position limite en bas de page. Si un élément imprimé tente de dépasser cette limite, il sera imprimé sur la page suivante.','',0),(14,'pdflettreretard','1marge_page_gauche','10','Marge de gauche en mm','',0),(15,'pdflettreretard','1marge_page_droite','10','Marge de droite en mm','',0),(16,'pdflettreretard','1largeur_page','210','Largeur de la page en mm','',0),(17,'pdflettreretard','1hauteur_page','297','Hauteur de la page en mm','',0),(18,'pdflettreretard','1format_page','P','Format de la page : \r\n P : Portrait\r\n L : Landscape = paysage','',0),(19,'pdfcartelecteur','pos_h','20','Position horizontale en mm à partir du bord gauche de la page','',0),(20,'pdfcartelecteur','pos_v','20','Position verticale en mm à partir du bord supérieur de la page','',0),(21,'pdfcartelecteur','biblio_name','$biblio_name','Nom de la bibliothèque ou du centre de ressources imprimé sur la carte de lecteur. Mettre $biblio_name pour reprendre le nom spécifié en localisation d\'exemplaire ou bien mettre autre chose.','',0),(22,'pdfcartelecteur','largeur_nom','80','Largeur accordée à l\'impression du nom du lecteur en mm','',0),(23,'pdfcartelecteur','valabledu','à»ƒàºŠà»‰à»„àº”à»‰àº§àº±àº™àº—àºµà»ˆ','\'Valable du\' dans \"VALABLE DU ##/##/#### au ##/##/####\"','',0),(24,'pdfcartelecteur','valableau','àº«àº²','\'au\' dans \"valable du ##/##/#### AU ##/##/####\"','',0),(25,'pdfcartelecteur','carteno','à»€àº¥àºàºšàº±àº” :','Mention prï¿½cï¿½dant le numï¿½ro de la carte','',0),(26,'sauvegarde','cle_crypt1','9b4a840d790eadc71b9064c9a843719b','','',0),(27,'sauvegarde','cle_crypt2','51580d4fd5f1ad2d981c91ddb04095ec','','',0),(28,'pmb','resa_dispo','1','Réservation de documents disponibles possible ?\r\n 0 : Non\r\n 1 : Oui','',0),(29,'mailretard','1objet','$biblio_name : à»€àº­àºàº°àºªàº²àº™àºªàº»à»ˆàº‡àºŠà»‰àº²','Objet du mail de relance de retard','',0),(30,'mailretard','1before_list','àºàº»àºà»€àº§àº±à»‰àº™àº‚à»à»‰àºœàº´àº”àºàº²àº”àº‚àº­àº‡àº—àº²àº‡à»€àº®àº»àº², àº—à»ˆàº²àº™àº¡àºµàºªàº´àº”à»ƒàº™à»œàº¶à»ˆàº‡àº«àº¼àº·àº«àº¼àº²àºà»€àº­àºàº°àºªàº²àº™ à»€àºŠàº´à»ˆàº‡à»„àº¥àºàº°à»€àº§àº¥àº²àº‚àº­àº‡àºàº²àº™à»ƒàº«à»‰àº¢àº·àº¡à»àº¡à»ˆàº™à»„àº”à»‰àºàº²àºàºàº³àº™àº»àº”àº¡àº·à»‰àº™àºµà»‰ :','Texte apparaissant avant la liste des ouvrages en retard dans le mail de relance de retard','',0),(31,'mailretard','1after_list','àºàº§àºà»€àº®àº»àº²àº‚à»àº‚àº­àºšà»ƒàºˆàº™àº³àº—à»ˆàº²àº™àº—àºµà»ˆàºˆàº°àº•àº´àº”àº•à»à»ˆàºàº§àºà»€àº®àº»àº²à»‚àº”àºàº—àº²àº‡à»‚àº—àº¥àº°àºªàº±àºš à»œàº²àºà»€àº¥àº $biblio_phone àº«àº¼àº· à»‚àº”àº email $biblio_email à»€àºàº·à»ˆàº­àºªàº¶àºàºªàº²àº„àº§àº²àº¡à»€àº›àº±àº™à»„àº›à»„àº”à»‰àº‚àº­àº‡àºàº²àº™àº•à»à»ˆà»€àº§àº¥àº²àºàº²àº™à»ƒàº«à»‰àº¢àº·àº¡ àº«àº¼àº·àºªàº»à»ˆàº‡à»€àº­àºàº°àºªàº²àº™àº„àº·àº™.','Texte apparaissant aprï¿½s la liste des ouvrages en retard dans le mail','',0),(32,'mailretard','1madame_monsieur','àº—à»ˆàº²àº™àºàº´àº‡, àº—à»ˆàº²àº™àºŠàº²àº','Entï¿½te du mail','',0),(33,'mailretard','1fdp','àºœàº¹à»‰àº®àº±àºšàºœàº´àº”àºŠàº­àºš.','Signataire du mail de relance de retard','',0),(34,'pmb','serial_link_article','0','Préremplissage du lien des dépouillements avec le lien de la notice mère en catalogage des périodiques ?\r\n 0 : Non\r\n 1 : Oui','',0),(35,'pmb','num_carte_auto','1','Numéro de carte de lecteur automatique ? \r\n 1 : Oui\r\n 0 : Non (si utilisation de cartes pré-imprimées)','',0),(36,'opac','modules_search_title','2','Recherche simple dans les titres:\r\n 0 : interdite\r\n 1 : autorisée\r\n 2 : autorisée et validée par défaut','c_recherche',0),(37,'opac','modules_search_author','2','Recherche simple dans les auteurs:\r\n 0 : interdite\r\n 1 : autorisée\r\n 2 : autorisée et validée par défaut','c_recherche',0),(38,'opac','modules_search_publisher','1','Recherche simple dans les éditeurs:\r\n 0 : interdite\r\n 1 : autorisée\r\n 2 : autorisée et validée par défaut','c_recherche',0),(39,'opac','modules_search_collection','1','Recherche simple dans les collections:\r\n 0 : interdite\r\n 1 : autorisée\r\n 2 : autorisée et validée par défaut','c_recherche',0),(40,'opac','modules_search_subcollection','1','Recherche simple dans les sous-collections:\r\n 0 : interdite\r\n 1 : autorisée\r\n 2 : autorisée et validée par défaut','c_recherche',0),(41,'opac','modules_search_category','1','Recherche simple dans les catégories:\r\n 0 : interdite\r\n 1 : autorisée\r\n 2 : autorisée et validée par défaut','c_recherche',0),(42,'opac','modules_search_keywords','1','Recherche simple dans les indexations libres (mots clé):\r\n 0 : interdite\r\n 1 : autorisée\r\n 2 : autorisée et validée par défaut','c_recherche',0),(43,'opac','modules_search_abstract','1','Recherche simple dans le champ résumé :\r\n 0 : interdite\r\n 1 : autorisée\r\n 2 : autorisée et validée par défaut','c_recherche',0),(44,'opac','modules_search_content','0','Recherche simple dans les notes de contenu:\r\n 0 : interdite\r\n 1 : autorisée\r\n 2 : autorisée et validée par défaut\r\nINUTILISE POUR L\'INSTANT','c_recherche',0),(45,'opac','categories_categ_path_sep','>','Séparateur pour les catégories','i_categories',0),(46,'opac','categories_columns','3','Nombre de colonnes du sommaire général des catégories','i_categories',0),(47,'opac','categories_categ_rec_per_page','6','Nombre de notices à afficher par page dans l\'exploration des catégories','i_categories',0),(48,'opac','categories_categ_sort_records','index_serie, tnvol, index_sew','Explorateur de catégories : mode de tri des notices :\r\n index_serie, tnvol, index_sew > par titre de série, numéro dans la série et index des titres\r\n rand() : aléatoire','i_categories',0),(49,'opac','search_results_first_level','4','Nombre de résulats affichés sur la première page','z_unused',0),(50,'opac','search_results_per_page','10','Nombre de résulats affichés sur les pages suivantes','d_aff_recherche',0),(51,'opac','authors_aut_rec_per_page','1','Nombre d\'auteurs affichés par page','d_aff_recherche',0),(52,'opac','categories_sub_display','3','Nombre de sous-categories sur la première page','i_categories',0),(53,'opac','categories_sub_mode','libelle_categorie','Mode affichage des sous-categories : \r\n rand() > aléatoire\r\n libelle_categorie > ordre alpha','i_categories',0),(54,'opac','authors_aut_sort_records','index_serie, tnvol, index_sew','Visu auteurs : tri des notices','d_aff_recherche',0),(55,'opac','default_lang','la_LA','Langue de l\'opac : fr_FR ou en_US ou es_ES ou ar ou la_LA','a_general',0),(56,'opac','show_categ_browser','1','Affichage des catégories en page d\'accueil OPAC 1: oui  ou 0: non','f_modules',0),(57,'opac','show_book_pics','1','Afficher les vignettes de livres dans les fiches ouvrages :\r\n 0 : Non\r\n 1 : Oui','e_aff_notice',0),(58,'opac','resa','1','Réservations possibles par l\'OPAC 1: oui  ou 0: non','a_general',0),(59,'opac','resa_dispo','1','Réservations possibles de documents disponibles par l\'OPAC \r\n 1: oui \r\n 0: non','a_general',0),(60,'opac','show_meteo','0','Affichage de la météo dans l\'OPAC 1: oui  ou 0: non','f_modules',0),(61,'opac','duration_session_auth','1200','Durée de la session lecteur dans l\'OPAC en secondes','a_general',0),(62,'pmb','relance_adhesion','31','Nombre de jours avant expiration adhésion pour relance','',0),(63,'pmb','pret_adhesion_depassee','1','Prêts si adhésion dépassée : 0 INTERDIT incontournable, 1 POSSIBLE','',0),(64,'pdflettreadhesion','fdp','àºœàº¹à»‰àº®àº±àºšàºœàº´àº”àºŠàº­àºš.','Formule de politesse en bas de page','',0),(65,'pdflettreadhesion','madame_monsieur','àº—à»ˆàº²àº™àºàº´àº‡, àº—à»ˆàº²àº™àºŠàº²àº ,','Civilitï¿½ du destinataire','',0),(66,'pdflettreadhesion','texte','Votre abonnement arrive à échéance le !!date_fin_adhesion!!. Nous vous remercions de penser à le renouveller lors de votre prochaine visite.\r\n\r\nNous vous prions de recevoir, Madame, Monsieur, l\'expression de nos meilleures salutations.\r\n\r\n\r\n','Phrase d\'introduction de l\'échéance de l\'abonnement','',0),(67,'pdflettreadhesion','marge_page_gauche','10','Marge gauche de la page en mm','',0),(68,'pdflettreadhesion','marge_page_droite','10','Marge droite de la page en mm','',0),(69,'pdflettreadhesion','largeur_page','210','Largeur de la page en mm','',0),(70,'pdflettreadhesion','hauteur_page','297','Hauteur de la page en mm','',0),(71,'pdflettreadhesion','format_page','P','P pour Portrait, L pour paysage (Landscape)','',0),(72,'mailrelanceadhesion','objet','$biblio_name : àºàº²àº™à»€àº›àº±àº™àºŠàº°àº¡àº²àºŠàº´àºàº‚àº­àº‡àº—à»ˆàº²àº™','Objet du courrier de relance d\'adhï¿½sion. Utilisez biblio_name pour reprendre le nom prï¿½cisï¿½ dans la localisation des exemplaires.','',0),(73,'mailrelanceadhesion','texte','àºàº²àº™à»€àº›àº±àº™àºŠàº°àº¡àº²àºŠàº´àºàº‚àº­àº‡àº—à»ˆàº²àº™àºˆàº°à»œàº»àº”àºàº³àº™àº»àº”àº§àº±àº™àº—àºµà»ˆ !!date_fin_adhesion!!. àºàº§àºà»€àº®àº»àº²àºˆàº°àº‚àº­àºšà»ƒàºˆàº—à»ˆàº²àº™àº«àº¼àº²àºà»† àº—àºµà»ˆ àº—à»ˆàº²àº™àºˆàº°à»€àº‚àº»à»‰àº²àº¡àº²àº•à»à»ˆàºšàº±àº”àºŠàº°àº¡àº²àºŠàº´àºàº‚àº­àº‡àº—à»ˆàº²àº™.\r\n\r\nàº”à»‰àº§àºàº„àº§àº²àº¡àº™àº±àºšàº–àº·,\r\n\r\n','Texte de la relance, !!date_fin_adhesion!! sera remplacï¿½ ï¿½ l\'ï¿½dition par la date de fin d\'adhï¿½sion du lecteur','',0),(74,'mailrelanceadhesion','madame_monsieur','àº—à»ˆàº²àº™àºàº´àº‡, àº—à»ˆàº²àº™àºŠàº²àº,','Entï¿½te du courrier de relance d\'adhï¿½sion','',0),(75,'mailrelanceadhesion','fdp','àº”à»‰àº§àºàº„àº§àº²àº¡à»€àº„àº»àº²àº¥àº»àºš','Formule de politesse en bas de page','',0),(76,'opac','show_marguerite_browser','0','0 ou 1 : marguerite des catégories','f_modules',0),(77,'opac','show_100cases_browser','0','0 ou 1 : affichage de 100 catégories','f_modules',0),(78,'pmb','indexint_decimal','1','0 ou 1 : l\'indexation interne est-elle une cotation décimale type Dewey','',0),(79,'opac','modules_search_indexint','1','Recherche simple dans les indexations internes:\r\n 0 : interdite\r\n 1 : autorisée\r\n 2 : autorisée et validée par défaut','c_recherche',0),(80,'empr','birthdate_optional','1','Année de naissance facultative : \r\n 0 > non:elle est obligatoire \r\n 1 Oui','',0),(81,'categories','show_empty_categ','1','Affichage des catégories ne contenant aucune notice :\r\n0=non, 1=oui','',0),(82,'categories','term_search_n_per_page','50','Nombre de termes affichés par page lors d\'une recherche par terme dans les catégories','',0),(83,'opac','show_loginform','1','Affichage du login lecteur dans l\'OPAC \r\n 0 > non\r\n 1 Oui','f_modules',0),(84,'opac','default_style','bueil','Style graphique de l\'OPAC, 1 style par défaut, nomargin : sans affichage du bandeau de gauche','a_general',0),(85,'opac','show_exemplaires','1','Afficher les exemplaires dans l\'OPAC\n 1 Oui,\n 0 : Non','e_aff_notice',0),(86,'pmb','import_modele','func_bdp.inc.php','Quel script de fonctions d\'import utiliser pour personnaliser l\'import ?','',0),(87,'pmb','quotas_avances','0','Quotas de prêts avancées ? \r\n 0 : Non\r\n 1 : Oui','',0),(88,'opac','logo','logo_default.jpg','Nom du fichier de l\'image logo','z_unused',0),(89,'opac','logosmall','images/site/livre.png','Nom du fichier de l\'image petit logo','b_aff_general',0),(90,'opac','show_bandeaugauche','1','Affichage du bandeau de gauche ? \n 0 : Non\n 1 : Oui','f_modules',0),(91,'opac','show_liensbas','1','Affichage des liens(pmb, google, bibli) en bas de page ? \n 0 : Non\n 1 : Oui','f_modules',0),(92,'opac','show_homeontop','0','Affichage du lien HOME (retour accueil) sous le nom de la bibliothèque ou du centre de ressources (nécessaire si masquage bandeau gauche) ? \r\n 0 : Non\r\n 1 : Oui','f_modules',0),(93,'pmb','resa_quota_pret_depasse','1','Réservation possible même si quota de prêt dépassé ? \n 0 : Non\n 1 : Oui','',0),(94,'pmb','import_limit_read_file','100','Limite de taille de lecture du fichier en import, en général 100 ou 200 doit fonctionner, si problème de time out : fixer plus bas, 50 par exemple.','',0),(95,'pmb','import_limit_record_load','100','Limite de taille de traitement de notices en import, en général 100 ou 200 doit fonctionner, si problème de time out : fixer plus bas, 50 par exemple.','',0),(96,'opac','biblio_preamble_p1','àº«à»àºªàº°à»àº¸àº”àº‚àº­àº‡àºàº²àº™àº—àº»àº”àºªàº­àºš PMB àºªàº°à»€à»œàºµàº—à»ˆàº²àº™ 60 à»€àº­àºàº°àºªàº²àº™ à»€àºàº·à»ˆàº­àº—àº»àº”àºªàº­àºšàº¥àº°àºšàº»àºš, à»œà»‰àº²àº™àºµà»‰àºªàº°à»€à»œàºµàº«àº¼àº²àºàº—àº²àº‡à»€àº¥àº·àº­àºàº‚àº­àº‡àºàº²àº™àºŠàº­àº à»àº¥àº° àºàº²àº™ à»€àº„àº·à»ˆàº­àº™àº—àºµà»ˆàºˆàº²àºà»œà»‰àº²àº™àºµà»‰àº«àº²à»œà»‰àº²àº­àº·à»ˆàº™, àºªàº´à»ˆàº‡à»€àº«àº¼àº»à»ˆàº²àº™àºµà»‰ à»àº¡à»ˆàº™àºªàº²àº¡àº²àº”àº”àº±àº”à»àº›àº‡à»„àº”à»‰ .','Paragraphe 1 d\'informations (par exemple, description du fonds)','b_aff_general',0),(97,'opac','biblio_preamble_p2','àºàº²àº™àºšà»àº¥àº´àºàº²àº™ PMB à»àº¡à»ˆàº™à»€àº›àº±àº™àº‚àº­àº‡àº—à»ˆàº²àº™à»àº¥à»‰àº§ à»€àºàº·à»ˆàº­àºŠà»ˆàº§àºàº—à»ˆàº²àº™à»ƒàº™àºàº²àº™àº”àº±àº”à»àºà»‰ àº«àº¼àº· à»€àº®àº±àº”à»ƒàº«à»‰  PMB àº‚àº­àº‡àº—à»ˆàº²àº™à»àº—àº”à»€à»àº²àº°àºàº±àºšàºàº²àº™àº™àº³à»ƒàºŠà»‰.','Paragraphe 2 d\'informations : accueil du public.','b_aff_general',0),(98,'opac','biblio_quicksummary_p1','','Paragraphe 1 de résumé, est masqué par défaut dans la feuille de style, voir id quickSummary.p1','z_unused',0),(99,'opac','biblio_quicksummary_p2','','Paragraphe 2 de résumé, est masqué par défaut dans la feuille de style, voir id quickSummary.p2','z_unused',0),(100,'opac','show_dernieresnotices','0','Affichage des dernières notices créées en bas de page ? \n 0 : Non\n 1 : Oui','f_modules',0),(101,'opac','show_etageresaccueil','1','Affichage des étagères dans la page d\'accueil en bas de page ? \n 0 : Non\n 1 : Oui','f_modules',0),(102,'opac','biblio_important_p1','','Infos importantes 1, dans la feuille de style, voir id important.p1','b_aff_general',0),(103,'opac','biblio_important_p2','','Infos importantes, dans la feuille de style, voir id important.p2','b_aff_general',0),(104,'opac','biblio_name','àº«à»àºªàº°à»àº¸àº”à»àº«à»ˆàº‡àºŠàº²àº”','Nom de la bibliothï¿½que ou du centre de ressources dans l\'opac','b_aff_general',0),(105,'opac','biblio_website','www.bnlaos.org','Site web de la bibliothï¿½que ou du centre de ressources dans l\'opac','b_aff_general',0),(106,'opac','biblio_adr1','àº–àº°à»œàº»àº™ à»€àºªàº”àº–àº²àº—àº´àº¥àº²àº”','Adresse 1 de la bibliothï¿½que ou du centre de ressources dans l\'opac','b_aff_general',0),(107,'opac','biblio_town','àº§àº½àº‡àºˆàº±àº™','Ville dans l\'opac','b_aff_general',0),(108,'opac','biblio_cp','àº•àº¹à»‰ àº›.àº™ 122 àºšà»‰àº²àº™àºŠàº½àº‡àºàº·àº™','Code postal dans l\'opac','b_aff_general',0),(109,'opac','biblio_country','àºªàº›àº›àº¥àº²àº§ ','Pays dans l\'opac','b_aff_general',0),(110,'opac','biblio_phone','(+85621) 251 405','Téléphone dans l\'opac','b_aff_general',0),(111,'opac','biblio_dep','37','Département dans l\'opac pour la météo','b_aff_general',0),(112,'opac','biblio_email','bnl@laosky.com','Email de contact dans l\'opac','b_aff_general',0),(113,'opac','etagere_notices_order','index_serie, tnvol, index_sew','Ordre d\'affichage des notices dans les étagères dans l\'opac \n  index_serie, tit1 : tri par titre de série et titre \n rand()  : aléatoire','j_etagere',0),(114,'opac','etagere_notices_format','4','Format d\'affichage des notices dans les étagères de l\'écran d\'accueil \r\n 1 : ISBD seul \r\n 2 : Public seul \r\n 4 : ISBD et Public \r\n 8 : Réduit (titre+auteurs) seul','j_etagere',0),(115,'opac','etagere_notices_depliables','1','Affichage dépliable des notices dans les étagères de l\'écran d\'accueil \r\n 0 : Non \r\n 1 : Oui','j_etagere',0),(116,'opac','etagere_nbnotices_accueil','5','Nombre de notices affichées dans les étagères de l\'écran d\'accueil \r\n 0 : Toutes \r\n -1 : Aucune \r\n x : x notices affichées au maximum','j_etagere',0),(117,'opac','nb_aut_rec_per_page','15','Nombre de notices affichées pour une autorité donnée','d_aff_recherche',0),(118,'opac','notices_format','4','Format d\'affichage des notices dans les étagères de l\'écran d\'accueil \n 1 : ISBD seul \n 2 : Public seul \n 4 : ISBD et Public \n 5 : ISBD et Public avec ISBD en premier \n 8 : Réduit (titre+auteurs) seul','e_aff_notice',0),(119,'opac','notices_depliable','1','Affichage dépliable des notices en résultat de recherche  0 : Non  1 : Oui','e_aff_notice',0),(120,'opac','term_search_n_per_page','50','Nombre de termes affichés par page en recherche par terme','c_recherche',0),(121,'opac','show_empty_categ','1','En recherche par terme, affichage des catégories ne contenant aucun ouvrage :\r\n 0 : Non \r\n 1 : Oui','i_categories',0),(122,'opac','allow_extended_search','1','Autorisation ou non de la recherche avancée dans l\'OPAC \n 0 : Non \n 1 : Oui','c_recherche',0),(123,'opac','allow_term_search','1','Autorisation ou non de la recherche par termes dans l\'OPAC \n 0 : Non \n 1 : Oui','c_recherche',0),(124,'opac','term_search_height','350','Hauteur en pixels de la frame de recherche par termes (si pas précisé ou zéro : par défaut 200 pixels)','c_recherche',0),(125,'opac','categories_nb_col_subcat','3','Nombre de colonnes de sous-catégories en navigation dans les catégories \n 3 par défaut','i_categories',0),(126,'opac','max_resa','5','Nombre maximum de réservation sur un document \r\n 5 par défaut \r\n 0 pour illimité','a_general',0),(127,'pmb','show_help','1','Affichage de l\'aide contextuelle dans PMB en partie gestion \r\n 1 Oui \r\n 0 Non','',0),(128,'opac','show_help','1','Affichage de l\'aide en ligne dans l\'OPAC de PMB  \n 1 Oui \n 0 Non','f_modules',0),(129,'opac','cart_allow','1','Paniers possibles dans l\'OPAC de PMB  \n 1 Oui \n 0 Non','f_modules',0),(130,'opac','max_cart_items','200','Nombre maximum de notices dans un panier utilisateur.','h_cart',0),(131,'opac','show_section_browser','1','Afficher le butineur de localisation et de sections ?\n 0 : Non\n 1 : Oui','f_modules',0),(132,'opac','nb_localisations_per_line','6','Nombre de localisations affichées par ligne en page d\'accueil (si show_section_browser=1)','k_section',0),(133,'opac','nb_sections_per_line','6','Nombre de sections affichées par ligne en visualisation de localisation (si show_section_browser=1)','k_section',0),(134,'opac','cart_only_for_subscriber','1','Paniers de notices réservés aux adhérents de la bibliothèque ou du centre de ressources ?\r\n 1: Oui\r\n 0: Non, autorisé pour tout internaute','h_cart',0),(135,'opac','notice_reduit_format','0','Format d\'affichage des réduits des notices :\r\n 0 normal = titre+auteurs principaux\r\n P 1,2,3: Perso. : tit+aut+champs persos id 1 2 3\r\n E 1,2,3: Perso. : tit+aut+édit+champs persos id 1 2 3 \r\n T : tit1+tit4','e_aff_notice',0),(136,'pdflettreresa','before_list','Suite à votre demande de réservation, nous vous informons que le ou les ouvrages ci-dessous sont à votre disposition à la bibliothèque.','Texte apparaissant avant la liste des ouvrages en résa dans le courrier de confirmation de résa','',0),(137,'pdflettreresa','after_list','Passé le délai de réservation, ces ouvrages seront remis en circulation, vous priant de les retirer dans les meilleurs délais.','Texte apparaissant après la liste des ouvrages','',0),(138,'pdflettreresa','fdp','Le responsable.','Signataire de la lettre, utiliser $biblio_name pour reprendre le paramètre \"biblio name\" ou bien mettre autre chose.','',0),(139,'pdflettreresa','madame_monsieur','àº—à»ˆàº²àº™àºàº´àº‡, àº—à»ˆàº²àº™àºŠàº²àº ','Entï¿½te de la lettre','',0),(140,'pdflettreresa','nb_par_page','7','Nombre d\'ouvrages en retard imprimé sur les pages suivantes.','',0),(141,'pdflettreresa','nb_1ere_page','4','Nombre d\'ouvrages en retard imprimé sur la première page','',0),(142,'pdflettreresa','taille_bloc_expl','16','Taille d\'un bloc (2 lignes) d\'ouvrage en réservation. Le début de chaque ouvrage en résa sera espacé de cette valeur sur la page','',0),(143,'pdflettreresa','debut_expl_1er_page','160','Début de la liste des ouvrages sur la première page, en mm depuis le bord supérieur de la page. Doit être règlé en fonction du texte qui précède la liste des ouvrages, lequel peut être plus ou moins long.','',0),(144,'pdflettreresa','debut_expl_page','15','Début de la liste des ouvrages sur les pages suivantes, en mm depuis le bord supérieur de la page.','',0),(145,'pdflettreresa','limite_after_list','270','Position limite en bas de page. Si un élément imprimé tente de dépasser cette limite, il sera imprimé sur la page suivante.','',0),(146,'pdflettreresa','marge_page_gauche','10','Marge de gauche en mm','',0),(147,'pdflettreresa','marge_page_droite','10','Marge de droite en mm','',0),(148,'pdflettreresa','largeur_page','210','Largeur de la page en mm','',0),(149,'pdflettreresa','hauteur_page','297','Hauteur de la page en mm','',0),(150,'pdflettreresa','format_page','P','Format de la page : \r\n P : Portrait\r\n L : Landscape = paysage','',0),(151,'opac','categories_max_display','200','Pour la page d\'accueil, nombre maximum de catégories principales affichées','i_categories',0),(152,'opac','search_other_function','','Fonction complémentaire pour les recherches en page d\'accueil','c_recherche',0),(153,'opac','lien_bas_supplementaire','<a href=\'http://www.sigb.net.com/poomble.php\' target=_blank>àº¥àº´à»‰àº‡àº•à»à»ˆàº«àº²à»€àº§àº±àºšàº­àº·à»ˆàº™\r\n</a>','Lien supplï¿½mentaire en bas de page d\'accueil, ï¿½ renseigner complï¿½tement : a href= lien /a','b_aff_general',0),(154,'z3950','import_modele','func_other.inc.php','Quel script de fonctions d\'import utiliser pour personnaliser l\'import en intégration z3950 ?','',0),(155,'ldap','server','chinon','Serveur LDAP, IP ou host','',0),(156,'ldap','basedn','','Racine du nom de domaine LDAP','',0),(157,'ldap','port','389','Port du serveur LDAP','',0),(158,'ldap','filter','(&(objectclass=person)(gidnumber=GID))','Serveur LDAP, IP ou host','',0),(159,'ldap','fields','uid,gecos,departmentnumber','Champs du serveur LDAP','',0),(160,'ldap','lang','fr_FR','Langue du serveur LDAP','',0),(161,'ldap','groups','','Groupes du serveur LDAP','',0),(162,'ldap','accessible','0','LDAP accessible ?','',0),(163,'opac','categories_show_only_last','0','Dans la fiche d\'une notice : \n 0 tout afficher \n 1 : afficher uniquement la dernière feuille de l\'arbre de la catégorie','i_categories',0),(164,'categories','show_only_last','0','Dans la fiche d\'une notice : \n 0 tout afficher \n 1 : afficher uniquement la dernière feuille de l\'arbre de la catégorie','',0),(165,'pmb','prefill_cote','custom_cote_02.inc.php','Script personnalisé de construction de la cote de l\'exemplaire','',0),(166,'ldap','proto','3','Version du protocole LDAP : 3 ou 2','',0),(167,'ldap','binddn','uid=UID,ou=People','Description de la liaison : construction de la chaine binddn pour lier l\'authentification au serveur LDAP dans l\'OPAC','',0),(168,'empr','corresp_import','','Table de correspondances colonnes/champs en import de lecteurs à partir d\'un fichier ASCII','',0),(169,'pmb','type_audit','0','Gestion/affichage des dates de création/modification \n 0: Rien\n 1: Création et dernière modification\n 2: Création et toutes les dates de modification','',0),(170,'pmb','gestion_abonnement','0','Utiliser la gestion des abonnements des lecteurs ? \n 0 : Non\n 1 : Oui, gestion simple, \n 2 : Oui, gestion avancée','',0),(171,'pmb','utiliser_calendrier','0','Utiliser le calendrier des jours d\'ouverture ? \n 0 : Non\n 1 : Oui','',0),(172,'pmb','gestion_financiere','0','Utiliser le module gestion financière ? \n 0 : Non\n 1 : Oui','',0),(173,'pmb','gestion_tarif_prets','0','Utiliser la gestion des tarifs de prêts ? \n 0 : Non\n 1 : Oui, gestion simple, \n 2 : Oui, gestion avancée','',0),(174,'pmb','gestion_amende','0','Utiliser la gestion des amendes:\n 0 = Non\n 1 = Gestion simple\n 2 = Gestion avancée','',0),(175,'finance','amende_jour','0.15','Amende par jour de retard pour tout type de document. Attention, le séparateur décimal est le point, pas la virgule','',1),(176,'finance','delai_avant_amende','15','Délai avant déclenchement de l\'amende, en jour','',1),(177,'finance','delai_recouvrement','7','Délai entre 3eme relance et mise en recouvrement officiel de l\'amende, en jour','',1),(178,'finance','amende_maximum','0','Amende maximum, quel que soit le retard l\'amende est plafonnée à ce montant. 0 pour désactiver ce plafonnement.','',1),(179,'pdflettreresa','priorite_email','1','Priorité des lettres de confirmation de réservation par mail lors de la validation d\'une réservation:\n 0 : Lettre seule \n 1 : Mail, à défaut lettre\n 2 : Mail ET lettre\n 3 : Aucune alerte','',0),(180,'pdflettreresa','priorite_email_manuel','1','Priorité des lettres de confirmation de réservation par mail lors de l\'impression à partir du bouton :\n 0 : Lettre seule \n 1 : Mail, à défaut lettre\n 2 : Mail ET lettre\n 3 : Aucune alerte','',0),(181,'finance','blocage_abt','1','Blocage du prêt si le compte abonnement est débiteur\n 0 : pas de blocage \n 1 : blocage avec forçage possible  : blocage incontournable.','',1),(182,'finance','blocage_pret','1','Blocage du prêt si le compte prêt est débiteur\n 0 : pas de blocage \n 1 : blocage avec forçage possible  : blocage incontournable.','',1),(183,'finance','blocage_amende','1','Blocage du prêt si le compte amende est débiteur\n 0 : pas de blocage \n 1 : blocage avec forçage possible  : blocage incontournable.','',1),(184,'pmb','gestion_devise','&euro;','Devise de la gestion financière, ce qui va être affiché en code HTML','',0),(185,'opac','book_pics_url','','URL des vignettes des notices, dans le chemin fourni, !!isbn!! sera remplacé par le code ISBN ou EAN de la notice purgé de tous les tirets ou points. \n exemple : http://www.monsite/opac/images/vignettes/!!isbn!!.jpg','e_aff_notice',0),(186,'opac','lien_moteur_recherche','<a href=http://www.google.fr target=_blank>&#3735;&#3763;&#3713;&#3762;&#3737;&#3722;&#3757;&#3713;&#3713;&#3761;&#3738;&#3776;&#3751;&#3761;&#3738; &#3713;&#3769;&#3784;&#3778;&#3713;&#3785;  </a>','Lien supplémentaire en bas de page d\'accueil, à renseigner complètement : a href= lien /a','b_aff_general',0),(187,'pmb','pret_express_statut','2','Statut de notice à utiliser en création d\'exemplaires en prêts express','',0),(188,'opac','notice_affichage_class','','Nom de la classe d\'affichage pour personnalisation de l\'affichage des notices','e_aff_notice',0),(189,'pmb','confirm_retour','0','En retour de documents, le retour doit-il être confirmé ? \n 0 : Non, on peut passer les codes-barres les uns après les autres \n 1 : Oui, il faut valider le retour après chaque code-barre','',0),(190,'opac','show_meteo_url','<img src=\"http://perso0.free.fr/cgi-bin/meteo.pl?dep=72\" alt=\"\" border=\"0\" hspace=0>','URL de la météo affichée','f_modules',0),(191,'pmb','limitation_dewey','0','Nombre maximum de caractères dans la Dewey (676) en import : \n 0 aucune limitation \n 3 : limitation de 000 à 999 \n 5 (exemple) limitation 000.0 \n -1 : aucune importation','',0),(192,'finance','delai_1_2','15','Délai entre 1ere et 2eme relance','',1),(193,'finance','delai_2_3','15','Délai entre 2eme et 3eme relance','',1),(194,'pmb','lecteurs_localises','0','Lecteurs localisés ? \n 0: Non \n 1: Oui','',0),(195,'dsi','active','1','D.S.I activée ? \n 0: Non \n 1: Oui','',0),(196,'dsi','auto','0','D.S.I automatique activée ? \n 0: Non \n 1: Oui','',0),(197,'dsi','insc_categ','0','Inscription automatique dans les bannettes de la catégorie du lecteur en création ? \n 0: Non \n 1: Oui','',0),(198,'opac','allow_bannette_priv','0','Possibilité pour les lecteurs de créer ou modifier leurs bannettes privées \n 0: Non \n 1: Oui','l_dsi',0),(199,'opac','allow_resiliation','0','Possibilité pour les lecteurs de résilier leur abonnement aux bannettes pro \n 0: Non \n 1: Oui','l_dsi',0),(200,'opac','show_categ_bannette','0','Affichage des bannettes de la catégorie du lecteur et possibilité de s\'y abonner \n 0: Non \n 1: Oui','l_dsi',0),(201,'opac','url_base','./','URL de base de l\'opac : typiquement mettre l\'url publique web http://monsite/opac/ ne pas oublier le / final','a_general',0),(202,'finance','relance_1','0.53','Frais de la première lettre de relance','',1),(203,'finance','relance_2','0.53','Frais de la deuxième lettre de relance','',1),(204,'finance','relance_3','2.50','Frais de la troisième lettre de relance','',1),(205,'finance','statut_perdu','','Statut (d\'exemplaire) perdu pour des ouvrages non rendus','',1),(206,'pdflettreretard','2after_list','àºàº§àºà»€àº®àº»àº²àº‚à»àº‚àº­àºšà»ƒàºˆàº™àº³àº—à»ˆàº²àº™àº—àºµà»ˆàºˆàº°àº•àº´àº”àº•à»à»ˆàºàº§àºà»€àº®àº»àº²à»‚àº”àºàº—àº²àº‡à»‚àº—àº¥àº°àºªàº±àºš à»œàº²àºà»€àº¥àº $biblio_phone àº«àº¼àº· à»‚àº”àº email $biblio_email à»€àºàº·à»ˆàº­àºªàº¶àºàºªàº²àº„àº§àº²àº¡à»€àº›àº±àº™à»„àº›à»„àº”à»‰àº‚àº­àº‡àºàº²àº™àº•à»à»ˆà»€àº§àº¥àº²àºàº²àº™à»ƒàº«à»‰àº¢àº·àº¡ àº«àº¼àº·àºªàº»à»ˆàº‡à»€àº­àºàº°àºªàº²àº™àº„àº·àº™.','Texte apparaissant aprï¿½s la liste des ouvrages en retard dans le courrier','',0),(207,'pdflettreretard','2before_list','àºàº»àºà»€àº§àº±à»‰àº™àº‚à»à»‰àºœàº´àº”àºàº²àº”àº‚àº­àº‡àº—àº²àº‡à»€àº®àº»àº², àº—à»ˆàº²àº™àº¡àºµàºªàº´àº”à»ƒàº™à»œàº¶à»ˆàº‡àº«àº¼àº·àº«àº¼àº²àºà»€àº­àºàº°àºªàº²àº™ à»€àºŠàº´à»ˆàº‡à»„àº¥àºàº°à»€àº§àº¥àº²àº‚àº­àº‡àºàº²àº™à»ƒàº«à»‰àº¢àº·àº¡à»àº¡à»ˆàº™à»„àº”à»‰àºàº²àºàºàº³àº™àº»àº”àº¡àº·à»‰àº™àºµà»‰','Texte apparaissant avant la liste des ouvrages en retard dans le courrier de relance de retard','',0),(208,'pdflettreretard','2debut_expl_1er_page','160','Début de la liste des exemplaires sur la première page, en mm depuis le bord supérieur de la page. Doit être règlé en fonction du texte qui précède la liste des ouvrages, lequel peut être plus ou moins long.','',0),(209,'pdflettreretard','2debut_expl_page','15','Début de la liste des exemplaires sur les pages suivantes, en mm depuis le bord supérieur de la page.','',0),(210,'pdflettreretard','2fdp','àºœàº¹à»‰àº®àº±àºšàºœàº´àº”àºŠàº­àºš.','Signataire de la lettre.','',0),(211,'pdflettreretard','2format_page','P','Format de la page : \r\n P : Portrait\r\n L : Landscape = paysage','',0),(212,'pdflettreretard','2hauteur_page','297','Hauteur de la page en mm','',0),(213,'pdflettreretard','2largeur_page','210','Largeur de la page en mm','',0),(214,'pdflettreretard','2limite_after_list','270','Position limite en bas de page. Si un élément imprimé tente de dépasser cette limite, il sera imprimé sur la page suivante.','',0),(215,'pdflettreretard','2madame_monsieur','àº—à»ˆàº²àº™àºàº´àº‡, àº—à»ˆàº²àº™àºŠàº²àº,','Entï¿½te de la lettre','',0),(216,'pdflettreretard','2marge_page_droite','10','Marge de droite en mm','',0),(217,'pdflettreretard','2marge_page_gauche','10','Marge de gauche en mm','',0),(218,'pdflettreretard','2nb_1ere_page','4','Nombre d\'ouvrages en retard imprimé sur la première page','',0),(219,'pdflettreretard','2nb_par_page','7','Nombre d\'ouvrages en retard imprimé sur les pages suivantes.','',0),(220,'pdflettreretard','2taille_bloc_expl','16','Taille d\'un bloc (2 lignes) d\'ouvrage en retard. Le début de chaque ouvrage en retard sera espacé de cette valeur sur la page','',0),(221,'pdflettreretard','3after_list','àºàº§àºà»€àº®àº»àº²àº‚à»àº‚àº­àºšà»ƒàºˆàº™àº³àº—à»ˆàº²àº™àº—àºµà»ˆàºˆàº°àº•àº´àº”àº•à»à»ˆàºàº§àºà»€àº®àº»àº²à»‚àº”àºàº—àº²àº‡à»‚àº—àº¥àº°àºªàº±àºš à»œàº²àºà»€àº¥àº $biblio_phone àº«àº¼àº· à»‚àº”àº email $biblio_email à»€àºàº·à»ˆàº­àºªàº¶àºàºªàº²àº„àº§àº²àº¡à»€àº›àº±àº™à»„àº›à»„àº”à»‰àº‚àº­àº‡àºàº²àº™àº•à»à»ˆà»€àº§àº¥àº²àºàº²àº™à»ƒàº«à»‰àº¢àº·àº¡ àº«àº¼àº·àºªàº»à»ˆàº‡à»€àº­àºàº°àºªàº²àº™àº„àº·àº™.','Texte apparaissant aprï¿½s la liste des ouvrages en retard dans le courrier','',0),(222,'pdflettreretard','3before_list','àºàº»àºà»€àº§àº±à»‰àº™àº‚à»à»‰àºœàº´àº”àºàº²àº”àº‚àº­àº‡àº—àº²àº‡à»€àº®àº»àº², àº—à»ˆàº²àº™àº¡àºµàºªàº´àº”à»ƒàº™à»œàº¶à»ˆàº‡àº«àº¼àº·àº«àº¼àº²àºà»€àº­àºàº°àºªàº²àº™ à»€àºŠàº´à»ˆàº‡à»„àº¥àºàº°à»€àº§àº¥àº²àº‚àº­àº‡àºàº²àº™à»ƒàº«à»‰àº¢àº·àº¡à»àº¡à»ˆàº™à»„àº”à»‰àºàº²àºàºàº³àº™àº»àº”àº¡àº·à»‰àº™àºµà»‰:','Texte apparaissant avant la liste des ouvrages en retard dans le courrier de relance de retard','',0),(223,'pdflettreretard','3debut_expl_1er_page','160','Début de la liste des exemplaires sur la première page, en mm depuis le bord supérieur de la page. Doit être règlé en fonction du texte qui précède la liste des ouvrages, lequel peut être plus ou moins long.','',0),(224,'pdflettreretard','3debut_expl_page','15','Début de la liste des exemplaires sur les pages suivantes, en mm depuis le bord supérieur de la page.','',0),(225,'pdflettreretard','3fdp','àºœàº¹à»‰àº®àº±àºšàºœàº´àº”àºŠàº­àºš.','Signataire de la lettre.','',0),(226,'pdflettreretard','3format_page','P','Format de la page : \r\n P : Portrait\r\n L : Landscape = paysage','',0),(227,'pdflettreretard','3hauteur_page','297','Hauteur de la page en mm','',0),(228,'pdflettreretard','3largeur_page','210','Largeur de la page en mm','',0),(229,'pdflettreretard','3limite_after_list','270','Position limite en bas de page. Si un élément imprimé tente de dépasser cette limite, il sera imprimé sur la page suivante.','',0),(230,'pdflettreretard','3madame_monsieur','àº—à»ˆàº²àº™àºàº´àº‡, àº—à»ˆàº²àº™àºŠàº²àº,','Entï¿½te de la lettre','',0),(231,'pdflettreretard','3marge_page_droite','10','Marge de droite en mm','',0),(232,'pdflettreretard','3marge_page_gauche','10','Marge de gauche en mm','',0),(233,'pdflettreretard','3nb_1ere_page','4','Nombre d\'ouvrages en retard imprimé sur la première page','',0),(234,'pdflettreretard','3nb_par_page','7','Nombre d\'ouvrages en retard imprimé sur les pages suivantes.','',0),(235,'pdflettreretard','3taille_bloc_expl','16','Taille d\'un bloc (2 lignes) d\'ouvrage en retard. Le début de chaque ouvrage en retard sera espacé de cette valeur sur la page','',0),(236,'pdflettreretard','3before_recouvrement','Sans nouvelles de votre part dans les sept jours, nous nous verrons contraints de déléguer au trésor public le recouvrement des ouvrages suivants :','Texte avant la liste des ouvrages en recouvrement','',0),(237,'opac','bannette_notices_order',' index_serie, tnvol, index_sew ','Ordre d\'affichage des notices dans les bannettes dans l\'opac \n  index_serie, tnvol, index_sew : tri par titre de série et titre \n rand()  : aléatoire','l_dsi',0),(238,'opac','bannette_notices_format','8','Format d\'affichage des notices dans les bannettes \n 1 : ISBD seul \n 2 : Public seul \n 4 : ISBD et Public \n 8 : Réduit (titre+auteurs) seul','l_dsi',0),(239,'opac','bannette_notices_depliables','1','Affichage dépliable des notices dans les bannettes \n 0 : Non \n 1 : Oui','l_dsi',0),(240,'opac','bannette_nb_liste','0','Nbre de notices par bannettes en affichage de la liste des bannettes \n 0 Toutes \n N : maxi N\n -1 : aucune','l_dsi',0),(241,'opac','dsi_active','0','DSI, bannettes accessibles par l\'OPAC ? \n 0 : Non \n 1 : Oui','l_dsi',0),(242,'mailretard','2after_list','àºàº§àºà»€àº®àº»àº²àº‚à»àº‚àº­àºšà»ƒàºˆàº™àº³àº—à»ˆàº²àº™àº—àºµà»ˆàºˆàº°àº•àº´àº”àº•à»à»ˆàºàº§àºà»€àº®àº»àº²à»‚àº”àºàº—àº²àº‡à»‚àº—àº¥àº°àºªàº±àºš à»œàº²àºà»€àº¥àº $biblio_phone àº«àº¼àº· à»‚àº”àº email $biblio_email à»€àºàº·à»ˆàº­àºªàº¶àºàºªàº²àº„àº§àº²àº¡à»€àº›àº±àº™à»„àº›à»„àº”à»‰àº‚àº­àº‡àºàº²àº™àº•à»à»ˆà»€àº§àº¥àº²àºàº²àº™à»ƒàº«à»‰àº¢àº·àº¡ àº«àº¼àº·àºªàº»à»ˆàº‡à»€àº­àºàº°àºªàº²àº™àº„àº·àº™.','Texte apparaissant aprï¿½s la liste des ouvrages en retard dans le mail','',0),(243,'mailretard','2before_list','àºàº»àºà»€àº§àº±à»‰àº™àº‚à»à»‰àºœàº´àº”àºàº²àº”àº‚àº­àº‡àº—àº²àº‡à»€àº®àº»àº², àº—à»ˆàº²àº™àº¡àºµàºªàº´àº”à»ƒàº™à»œàº¶à»ˆàº‡àº«àº¼àº·àº«àº¼àº²àºà»€àº­àºàº°àºªàº²àº™ à»€àºŠàº´à»ˆàº‡à»„àº¥àºàº°à»€àº§àº¥àº²àº‚àº­àº‡àºàº²àº™à»ƒàº«à»‰àº¢àº·àº¡à»àº¡à»ˆàº™à»„àº”à»‰àºàº²àºàºàº³àº™àº»àº”àº¡àº·à»‰àº™àºµà»‰ :','Texte apparaissant avant la liste des ouvrages en retard dans le mail de relance de retard','',0),(244,'mailretard','2fdp','àºœàº¹à»‰àº®àº±àºšàºœàº´àº”àºŠàº­àºš.','Signataire du mail de relance de retard','',0),(245,'mailretard','2madame_monsieur','àº—à»ˆàº²àº™àºàº´àº‡, àº—à»ˆàº²àº™àºŠàº²àº,','Entï¿½te du mail','',0),(246,'mailretard','2objet','$biblio_name : à»€àº­àºàº°àºªàº²àº™àºàº²àºàºàº³àº™àº»àº”àºªàº»à»ˆàº‡','Objet du mail de relance de retard','',0),(247,'mailretard','3after_list','àºàº§àºà»€àº®àº»àº²àº‚à»àº‚àº­àºšà»ƒàºˆàº™àº³àº—à»ˆàº²àº™àº—àºµà»ˆàºˆàº°àº•àº´àº”àº•à»à»ˆàºàº§àºà»€àº®àº»àº²à»‚àº”àºàº—àº²àº‡à»‚àº—àº¥àº°àºªàº±àºš à»œàº²àºà»€àº¥àº $biblio_phone àº«àº¼àº· à»‚àº”àº email $biblio_email à»€àºàº·à»ˆàº­àºªàº¶àºàºªàº²àº„àº§àº²àº¡à»€àº›àº±àº™à»„àº›à»„àº”à»‰àº‚àº­àº‡àºàº²àº™àº•à»à»ˆà»€àº§àº¥àº²àºàº²àº™à»ƒàº«à»‰àº¢àº·àº¡ àº«àº¼àº·àºªàº»à»ˆàº‡à»€àº­àºàº°àºªàº²àº™àº„àº·àº™.','Texte apparaissant aprï¿½s la liste des ouvrages en retard dans le mail','',0),(248,'mailretard','3before_list','àºàº»àºà»€àº§àº±à»‰àº™àº‚à»à»‰àºœàº´àº”àºàº²àº”àº‚àº­àº‡àº—àº²àº‡à»€àº®àº»àº², àº—à»ˆàº²àº™àº¡àºµàºªàº´àº”à»ƒàº™à»œàº¶à»ˆàº‡àº«àº¼àº·àº«àº¼àº²àºà»€àº­àºàº°àºªàº²àº™ à»€àºŠàº´à»ˆàº‡à»„àº¥àºàº°à»€àº§àº¥àº²àº‚àº­àº‡àºàº²àº™à»ƒàº«à»‰àº¢àº·àº¡à»àº¡à»ˆàº™à»„àº”à»‰àºàº²àºàºàº³àº™àº»àº”àº¡àº·à»‰àº™àºµà»‰ :','Texte apparaissant avant la liste des ouvrages en retard dans le mail de relance de retard','',0),(249,'mailretard','3fdp','àºœàº¹à»‰àº®àº±àºšàºœàº´àº”àºŠàº­àºš.','Signataire du mail de relance de retard','',0),(250,'mailretard','3madame_monsieur','àº—à»ˆàº²àº™àºàº´àº‡, àº—à»ˆàº²àº™àºŠàº²àº,','Entï¿½te du mail','',0),(251,'mailretard','3objet','$biblio_name : à»€àº­àºàº°àºªàº²àº™àºàº²àº™àºàº³àº™àº»àº”àºªàº»à»ˆàº‡','Objet du mail de relance de retard','',0),(252,'mailretard','3before_recouvrement','Sans nouvelles de votre part dans les sept jours, nous nous verrons contraints de déléguer au trésor public le recouvrement des ouvrages suivants :','Texte avant la liste des ouvrages en recouvrement','',0),(253,'mailretard','priorite_email','1','Priorité des lettres de retard lors des relances :\n 0 : Lettre seule \n 1 : Mail, à défaut lettre\n 2 : Mail ET lettre','',0),(254,'pmb','import_modele_lecteur','','Modèle d\'import des lecteurs','',0),(255,'pmb','blocage_retard','0','Bloquer le prêt d\'une durée équivalente au retard ? 0=non, 1=oui','',0),(256,'pmb','blocage_delai','7','Délai à partir duquel le retard est pris en compte','',0),(257,'pmb','blocage_max','60','Nombre maximum de jours bloqués (0 = pas de limite)','',0),(258,'pmb','blocage_coef','1','Coefficient de proportionnalité des jours de retard pour le blocage','',0),(259,'pmb','blocage_retard_force','1','1 = Le prêt peut-être forcé lors d\'un blocage du compte, 2 = Pas de forçage possible','',0),(260,'opac','etagere_order',' name ','Tri des étagères dans l\'écran d\'accueil, \n name = par nom\n name DESC = par nom décroissant','j_etagere',0),(261,'pmb','book_pics_show','0','Affichage des couvertures de livres en gestion\n 1: oui  \n 0: non','',0),(262,'pmb','book_pics_url','','URL des vignettes des notices, dans le chemin fourni, !!isbn!! sera remplacé par le code ISBN ou EAN de la notice purgé de tous les tirets ou points. \r\n exemple : http://www.monsite/opac/images/vignettes/!!isbn!!.jpg','',0),(263,'pmb','opac_url','./opac_css/','URL de l\'OPAC vu depuis la partie gestion, par défaut ./opac_css/','',0),(264,'opac','resa_popup','1','Demande de connexion sous forme de popup ? :\n 0 : Non\n 1 : Oui','a_general',0),(265,'pmb','vignette_x','100','Largeur de la vignette créée pour un exemplaire numérique image','',0),(266,'pmb','vignette_y','100','Hauteur de la vignette créée pour un exemplaire numérique image','',0),(267,'pmb','vignette_imagemagick','','Chemin de l\'exécutable ImageMagick (/usr/bin/imagemagick par exemple)','',0),(268,'opac','show_rss_browser','0','Affichage des flux RSS du catalogue en page d\'accueil OPAC 1: oui  ou 0: non','f_modules',0),(269,'pmb','mail_methode','php','Méthode d\'envoi des mails : \n php : fonction mail() de php\n smtp,hote:port,auth,user,pass : en smtp, mettre O ou 1 pour l\'authentification...','',0),(270,'opac','mail_methode','php','Méthode d\'envoi des mails dans l\'opac : \n php : fonction mail() de php\n smtp,hote:port,auth,user,pass : en smtp, mettre O ou 1 pour l\'authentification...','a_general',0),(271,'opac','search_show_typdoc','1','Affichage de la restriction par type de document pour les recherches en page d\'accueil','c_recherche',0),(272,'pmb','verif_on_line','0','Dans le menu Administration > Outils > Maj Base : vérification d\'une version plus récente de PMB en ligne ? \r\n0 : non : si vous n\'êtes pas connecté à internet \r\n 1 : Oui : si vous avez une connexion à internet','',0),(273,'opac','show_languages','1 fr_FR,it_IT,es_ES,ca_ES,en_UK,nl_NL,oc_FR,la_LA','Afficher la liste déroulante de sélection de la langue ?','a_general',0),(274,'pmb','pdf_font','Saysettha','Police de caractï¿½res ï¿½ chasse variable pour les ï¿½ditions en pdf - Police Arial','',0),(275,'pmb','pdf_fontfixed','Courier','Police de caractï¿½res ï¿½ chasse fixe pour les ï¿½ditions en pdf - Police Courier','',0),(276,'z3950','debug','0','Debugage (export fichier) des notices lues en Z3950 \r\n 0: Non \r\n 1: 0ui','',0),(277,'pmb','nb_lastnotices','10','Nombre de dernières notices affichées en Catalogue - Dernières notices','',0),(278,'opac','show_dernieresnotices_nb','10','Nombre de dernières notices affichées en Catalogue - Dernières notices','f_modules',0),(279,'pmb','recouvrement_auto','0','Par défaut passage en recouvrement proposé en gestion des relances si niveau=3 et devrait être en 4: \r\n 1: Oui, recouvrement proposé par défaut \r\n 0: Ne rien faire par défaut','',0),(280,'pmb','keyword_sep',' ','Séparateur des mots clés dans la partie indexation libre, espace ou ; ou , ou ...','',0),(281,'thesaurus','mode_pmb','0','Niveau d\'utilisation des thésaurus.\n 0 : Un seul thésaurus par défaut.\n 1 : Choix du thésaurus possible.','',0),(282,'thesaurus','defaut','1','Identifiant du thésaurus par défaut.','',0),(283,'thesaurus','liste_trad','la_LA','Liste des langues affichées dans les thésaurus.','',0),(284,'opac','thesaurus','0','Niveau d\'utilisation des thésaurus.\n 0 : Un seul thésaurus par défaut.\n 1 : Choix du thésaurus possible.','a_general',0),(285,'acquisition','active','0','Module acquisitions activé.\n 0 : Non.\n 1 : Oui.','',0),(286,'acquisition','gestion_tva','0','Gestion de la TVA.\n 0 : Non.\n 1 : Oui.','',0),(287,'acquisition','poids_sugg','U=1.00,E=0.70,V=0.00','Pondération des suggestions par défaut en pourcentage.\n U=Utilisateurs, E=Emprunteurs, V=Visiteurs.\n ex : U=1.00,E=0.70,V=0.00 \n','',0),(288,'acquisition','format','8,CA,DD,BL,FA','Taille du Numéro et Préfixes des actes d\'achats.\nex : 8,CA,DD,BL,FA \n8 = Préfixe + 8 Chiffres\nCA=Commande Achat, DD=Demande de Devis,BL=Bon de Livraison, FA=Facture Achat \n','',0),(289,'acquisition','budget','0','Utilisation d\'un budget pour les commandes.\n 0:optionnel\n 1:obligatoire','',0),(290,'acquisition','pdfcde_format_page','210x297','Largeur x Hauteur de la page en mm','pdfcde',0),(291,'acquisition','pdfcde_orient_page','P','Orientation de la page: P=Portrait, L=Paysage','pdfcde',0),(292,'acquisition','pdfcde_marges_page','10,20,10,10','Marges de page en mm : Haut,Bas,Droite,Gauche','pdfcde',0),(293,'acquisition','pdfcde_pos_logo','10,10,20,20','Position du logo: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur','pdfcde',0),(294,'acquisition','pdfcde_pos_raison','35,10,100,10,16','Position Raison sociale: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfcde',0),(295,'acquisition','pdfcde_pos_date','150,10,0,6,8','Position Date: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfcde',0),(296,'acquisition','pdfcde_pos_adr_fac','10,35,60,5,10','Position Adresse de facturation: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfcde',0),(297,'acquisition','pdfcde_pos_adr_liv','10,75,60,5,10','Position Adresse de livraison: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfcde',0),(298,'acquisition','pdfcde_pos_adr_fou','100,55,100,6,14','Position Adresse fournisseur: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfcde',0),(299,'acquisition','pdfcde_pos_num','10,110,0,10,16','Position numéro de commande: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfcde',0),(300,'acquisition','pdfcde_text_size','10','Taille de la police texte','pdfcde',0),(301,'acquisition','pdfcde_text_before','','Texte avant le tableau de commande','pdfcde',0),(302,'acquisition','pdfcde_text_after','','Texte après le tableau de commande','pdfcde',0),(303,'acquisition','pdfcde_tab_cde','5,10','Table de commandes: Hauteur ligne,Taille police','pdfcde',0),(304,'acquisition','pdfcde_pos_tot','10,40,5,10','Position total de commande: Distance par rapport au bord gauche de la page, Largeur, Hauteur ligne,Taille police','pdfcde',0),(305,'acquisition','pdfcde_pos_footer','15,8','Position bas de page: Distance par rapport au bas de page, Taille police','pdfcde',0),(306,'acquisition','pdfcde_pos_sign','10,60,5,10','Position signature: Distance par rapport au bord gauche de la page, Largeur, Hauteur ligne,Taille police','pdfcde',0),(307,'acquisition','pdfcde_text_sign','àºœàº¹à»‰àº®àº±àºšàºœàº´àº”àºŠàº­àºšàº«à»àºªàº°à»àº¸àº”.','Texte signature','pdfcde',0),(308,'acquisition','pdfdev_format_page','210x297','Largeur x Hauteur de la page en mm','pdfdev',0),(309,'acquisition','pdfdev_orient_page','P','Orientation de la page: P=Portrait, L=Paysage','pdfdev',0),(310,'acquisition','pdfdev_marges_page','10,20,10,10','Marges de page en mm : Haut,Bas,Droite,Gauche','pdfdev',0),(311,'acquisition','pdfdev_pos_logo','10,10,20,20','Position du logo: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur','pdfdev',0),(312,'acquisition','pdfdev_pos_raison','35,10,100,10,16','Position Raison sociale: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfdev',0),(313,'acquisition','pdfdev_pos_date','150,10,0,6,8','Position Date: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfdev',0),(314,'acquisition','pdfdev_pos_adr_fac','10,35,60,5,10','Position Adresse de facturation: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfdev',0),(315,'acquisition','pdfdev_pos_adr_liv','10,75,60,5,10','Position Adresse de livraison: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfdev',0),(316,'acquisition','pdfdev_pos_adr_fou','100,55,100,6,14','Position Adresse fournisseur: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfdev',0),(317,'acquisition','pdfdev_pos_num','10,110,0,10,16','Position numéro de commande: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfdev',0),(318,'acquisition','pdfdev_text_size','10','Taille de la police texte','pdfdev',0),(319,'acquisition','pdfdev_text_before','','Texte avant le tableau de commande','pdfdev',0),(320,'acquisition','pdfdev_comment','0','Affichage des commentaires : 0=non, 1=oui','pdfdev',0),(321,'acquisition','pdfdev_text_after','','Texte après le tableau de commande','pdfdev',0),(322,'acquisition','pdfdev_tab_dev','5,10','Table de commandes: Hauteur ligne,Taille police','pdfdev',0),(323,'acquisition','pdfdev_pos_footer','15,8','Position bas de page: Distance par rapport au bas de page, Taille police','pdfdev',0),(324,'acquisition','pdfdev_pos_sign','10,60,5,10','Position signature: Distance par rapport au bord gauche de la page, Largeur, Hauteur ligne,Taille police','pdfdev',0),(325,'acquisition','pdfdev_text_sign','àºœàº¹à»‰àº®àº±àºšàºœàº´àº”àºŠàº­àºšàº«à»àºªàº°à»àº¸àº”.','Texte signature','pdfdev',0),(326,'opac','export_allow','1','Export de notices à partir de l\'opac : \n 0 : interdit \n 1 : pour tous \n 2 : pour les abonnés uniquement','a_general',0),(327,'opac','resa_planning','0','Utiliser un planning de réservation ? \n 0: Non \n 1: Oui','a_general',0),(328,'opac','resa_contact','<a href=\'mailto:pmb@sigb.net\'>bnl@laosky.com</a>','Code HTML d\'information sur la personne ï¿½ contacter par exemple en cas de problï¿½me de rï¿½servation.','a_general',0),(329,'opac','default_operator','0','Opérateur par défaut. 0 : OR, 1 : AND.','c_recherche',0),(330,'opac','modules_search_all','2','Recherche simple dans l\'ensemble des champs :0 : interdite,  1 : autorisée,  2 : autorisée et validée par défaut','c_recherche',0),(331,'acquisition','pdfliv_format_page','210x297','Largeur x Hauteur de la page en mm','pdfliv',0),(332,'acquisition','pdfliv_orient_page','P','Orientation de la page: P=Portrait, L=Paysage','pdfliv',0),(333,'acquisition','pdfliv_marges_page','10,20,10,10','Marges de page en mm : Haut,Bas,Droite,Gauche','pdfliv',0),(334,'acquisition','pdfliv_pos_raison','10,10,100,10,16','Position Raison sociale: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfliv',0),(335,'acquisition','pdfliv_pos_adr_liv','10,20,60,5,10','Position Adresse de livraison: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfliv',0),(336,'acquisition','pdfliv_pos_adr_fou','110,20,100,5,10','Position éléments fournisseur: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfliv',0),(337,'acquisition','pdfliv_pos_num','10,60,0,6,14','Position numéro Commande/Livraison: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfliv',0),(338,'acquisition','pdfliv_tab_liv','5,10','Table de livraisons: Hauteur ligne,Taille police','pdfliv',0),(339,'acquisition','pdfliv_pos_footer','15,8','Position bas de page: Distance par rapport au bas de page, Taille police','pdfliv',0),(340,'pmb','default_operator','0','Opérateur par défaut. \n 0 : OR, \n 1 : AND.','',0),(341,'mailretard','priorite_email_3','0','Faire le troisième niveau de relance par mail :\n 0 : Non, lettre \n 1 : Oui, par mail','',0),(342,'opac','show_suggest','0','Proposer de faire des suggestions dans l\'OPAC.\n 0 : Non.\n 1 : Oui, avec authentification.\n 2 : Oui, sans authentification.','f_modules',0),(343,'acquisition','email_sugg','0','Information par email de l\'évolution des suggestions.\n 0 : Non\n 1 : Oui','',0),(344,'acquisition','pdfliv_text_size','10','Taille de la police texte','pdfliv',0),(345,'acquisition','pdfliv_pos_date','170,10,0,6,8','Position Date: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfliv',0),(346,'acquisition','pdffac_text_size','10','Taille de la police texte','pdffac',0),(347,'acquisition','pdffac_format_page','210x297','Largeur x Hauteur de la page en mm','pdffac',0),(348,'acquisition','pdffac_orient_page','P','Orientation de la page: P=Portrait, L=Paysage','pdffac',0),(349,'acquisition','pdffac_marges_page','10,20,10,10','Marges de page en mm : Haut,Bas,Droite,Gauche','pdffac',0),(350,'acquisition','pdffac_pos_raison','10,10,100,10,16','Position Raison sociale: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdffac',0),(351,'acquisition','pdffac_pos_date','170,10,0,6,8','Position Date: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdffac',0),(352,'acquisition','pdffac_pos_adr_fac','10,20,60,5,10','Position Adresse de facturation: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdffac',0),(353,'acquisition','pdffac_pos_adr_fou','110,20,100,5,10','Position éléments fournisseur: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdffac',0),(354,'acquisition','pdffac_pos_num','10,60,0,6,14','Position numéro Commande/Facture: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdffac',0),(355,'acquisition','pdffac_tab_fac','5,10','Table de facturation: Hauteur ligne,Taille police','pdffac',0),(356,'acquisition','pdffac_pos_tot','10,40,5,10','Position total de commande: Distance par rapport au bord gauche de la page, Largeur, Hauteur ligne,Taille police','pdffac',0),(357,'acquisition','pdffac_pos_footer','15,8','Position bas de page: Distance par rapport au bas de page, Taille police','pdffac',0),(358,'acquisition','pdfsug_text_size','8','Taille de la police texte','pdfsug',0),(359,'acquisition','pdfsug_format_page','210x297','Largeur x Hauteur de la page en mm','pdfsug',0),(360,'acquisition','pdfsug_orient_page','P','Orientation de la page: P=Portrait, L=Paysage','pdfsug',0),(361,'acquisition','pdfsug_marges_page','10,20,10,10','Marges de page en mm : Haut,Bas,Droite,Gauche','pdfsug',0),(362,'acquisition','pdfsug_pos_titre','10,10,100,10,16','Position titre: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfsug',0),(363,'acquisition','pdfsug_pos_date','170,10,0,6,8','Position Date: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfsug',0),(364,'acquisition','pdfsug_tab_sug','5,10','Table de suggestions: Hauteur ligne,Taille police','pdfsug',0),(365,'acquisition','pdfsug_pos_footer','15,8','Position bas de page: Distance par rapport au bas de page, Taille police','pdfsug',0),(366,'acquisition','mel_rej_obj','Rejet suggestion','Objet du mail de rejet de suggestion','mel',0),(367,'acquisition','mel_rej_cor','Votre suggestion du !!date!! est rejetée.\n\n','Corps du mail de rejet de suggestion','mel',0),(368,'acquisition','mel_con_obj','Confirmation suggestion','Objet du mail de confirmation de suggestion','mel',0),(369,'acquisition','mel_con_cor','Votre suggestion du !!date!! est retenue pour un prochain achat.\n\n','Corps du mail de confirmation de suggestion','mel',0),(370,'acquisition','mel_aba_obj','Abandon suggestion','Objet du mail d\'abandon de suggestion','mel',0),(371,'acquisition','mel_aba_cor','Votre suggestion du !!date!! n\'est pas retenue ou n\'est pas disponible à la vente.\n\n','Corps du mail d\'abandon de suggestion','mel',0),(372,'acquisition','mel_cde_obj','Commande suggestion','Objet du mail de commande de suggestion','mel',0),(373,'acquisition','mel_cde_cor','Votre suggestion du !!date!! est en commande.\n\n','Corps du mail de commande de suggestion','mel',0),(374,'acquisition','mel_rec_obj','Réception suggestion','Objet du mail de réception de suggestion','mel',0),(375,'acquisition','mel_rec_cor','Votre suggestion du !!date!! a été reçue et sera bientôt disponible en réservation.\n\n','Corps du mail de réception de suggestion','mel',0),(376,'opac','allow_tags_search','0','Recherche par tag (mots clés utilisateurs) \n 1 = oui \n 0 = non','c_recherche',0),(377,'opac','allow_add_tag','0','Permettre aux utilisateurs d\'ajouter un tag à une notice.\n 0 : non\n 1 : oui\n 2 : identification obligatoire pour ajouter','a_general',0),(378,'opac','avis_allow','0','Permet de consulter/ajouter un avis pour les notices \n 0 : non \n 1 : sans être identifié : consultation possible, ajout impossible \n 2 : identification obligatoire pour consulter et ajouter','a_general',0),(379,'opac','avis_nb_max','30','Nombre maximal de commentaires conservé par notice. Les plus vieux sont effacés au profit des plus récent quand ce nombre est atteint.','a_general',0),(380,'pmb','show_rtl','0','Affichage possible de droite a gauche \n 0 non \n 1 oui','',0),(381,'opac','avis_show_writer','0','Afficher le rédacteur de l\'avis \n 0 : non \n 1 : Prénom NOM \n 2 : login OPAC uniquement','a_general',0),(382,'pmb','form_editables','0','Grilles de notices éditables \n 0 non \n 1 oui','',0),(383,'acquisition','sugg_to_cde','0','Transfert des suggestions en commande.\n 0 : Non.\n 1 : Oui.','',0),(384,'categories','categ_in_line','0','Affichage des catégories en ligne.\n 0 : Non.\n 1 : Oui.','',0),(385,'opac','categories_categ_in_line','0','Affichage des catégories en ligne.\n 0 : Non.\n 1 : Oui.','i_categories',0),(386,'pmb','label_construct_script','','Script de construction d\'étiquette de cote','',0),(387,'dsi','func_after_diff','','Script à exécuter après diffusion d\'une bannette','',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `parametres` ENABLE KEYS */;

--
-- Table structure for table `pret`
--

DROP TABLE IF EXISTS `pret`;
CREATE TABLE `pret` (
  `pret_idempr` smallint(6) unsigned NOT NULL default '0',
  `pret_idexpl` smallint(6) unsigned NOT NULL default '0',
  `pret_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `pret_retour` date default NULL,
  `pret_arc_id` int(10) unsigned NOT NULL default '0',
  `niveau_relance` int(1) NOT NULL default '0',
  `date_relance` date default '0000-00-00',
  `printed` int(1) NOT NULL default '0',
  PRIMARY KEY  (`pret_idexpl`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pret`
--


/*!40000 ALTER TABLE `pret` DISABLE KEYS */;
LOCK TABLES `pret` WRITE;
INSERT INTO `pret` VALUES (7,1,'2006-10-13 15:19:51','2006-10-27',1,0,'0000-00-00',0),(2,2,'2006-10-13 15:25:18','2006-10-27',3,0,'0000-00-00',0),(5,6,'2006-10-13 15:35:07','2006-10-27',4,0,'0000-00-00',0),(5,8,'2006-10-13 15:35:23','2006-10-27',5,0,'0000-00-00',0),(6,9,'2006-10-13 15:38:51','2006-10-27',6,0,'0000-00-00',0),(11,24,'2006-08-28 14:35:57','2006-09-11',5,0,'0000-00-00',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `pret` ENABLE KEYS */;

--
-- Table structure for table `pret_archive`
--

DROP TABLE IF EXISTS `pret_archive`;
CREATE TABLE `pret_archive` (
  `arc_id` int(10) unsigned NOT NULL auto_increment,
  `arc_debut` datetime default '0000-00-00 00:00:00',
  `arc_fin` datetime default NULL,
  `arc_empr_cp` varchar(5) default '',
  `arc_empr_ville` varchar(40) default '',
  `arc_empr_prof` varchar(50) default '',
  `arc_empr_year` int(4) unsigned default '0',
  `arc_empr_categ` smallint(5) unsigned default '0',
  `arc_empr_codestat` smallint(5) unsigned default '0',
  `arc_empr_sexe` tinyint(3) unsigned default '0',
  `arc_expl_typdoc` tinyint(3) unsigned default '0',
  `arc_expl_cote` varchar(20) NOT NULL default '',
  `arc_expl_statut` smallint(5) unsigned default '0',
  `arc_expl_location` smallint(5) unsigned default '0',
  `arc_expl_codestat` smallint(5) unsigned default '0',
  `arc_expl_owner` mediumint(8) unsigned default '0',
  `arc_expl_section` int(5) unsigned NOT NULL default '0',
  `arc_expl_id` int(10) unsigned NOT NULL default '0',
  `arc_expl_notice` int(10) unsigned NOT NULL default '0',
  `arc_expl_bulletin` int(10) unsigned NOT NULL default '0',
  `arc_groupe` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`arc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pret_archive`
--


/*!40000 ALTER TABLE `pret_archive` DISABLE KEYS */;
LOCK TABLES `pret_archive` WRITE;
INSERT INTO `pret_archive` VALUES (1,'2006-08-24 18:42:53','2006-11-08 15:50:29','856','àºªàºµà»‚àº„àº”','àº™àº±àºàº‚à»ˆàº²àº§',5071981,10,4,1,1,'000',1,1,12,2,10,37,63,0,''),(2,'2006-08-24 18:47:30','2006-11-08 15:51:52','001','à»„àºŠàº—àº²àº™àºµ','àº™àº±àºàºªàº´àºàºªàº²',15081987,8,4,2,1,'',1,1,12,0,10,38,60,0,''),(3,'2006-08-24 18:54:00','2006-11-08 15:54:41','001','à»„àºŠàº—àº²àº™àºµ','àº™àº±àºàºªàº´àºàºªàº²',15081987,8,4,2,1,'',1,1,12,0,10,39,64,0,''),(4,'2006-10-13 15:35:07','2006-10-27 00:00:00','002','àº™àº²àºŠàº²àºàº—àº­àº‡','àº™àº±àºàº‚àº½àº™à»‚àº›à»àºàº¡',5031980,10,4,1,1,'001',1,1,10,2,10,6,2,0,''),(5,'2006-10-13 15:35:23','2006-10-27 00:00:00','002','àº™àº²àºŠàº²àºàº—àº­àº‡','àº™àº±àºàº‚àº½àº™à»‚àº›à»àºàº¡',5031980,10,4,1,1,'000',1,1,10,2,10,8,3,0,''),(6,'2006-10-13 15:38:51','2006-10-27 00:00:00','856','àºªàºµà»‚àº„àº”','àº™àº±àºàº‚àº½àº™à»‚àº›à»àºàº¡',7121981,10,4,1,1,'000',1,1,10,2,10,9,3,0,''),(7,'2006-10-14 08:17:42','2006-10-14 08:18:56','856','àºªàºµàºªàº°àº•àº°àº™àº²àº”','àº™àº±àºàº‚àº½àº™à»‚àº›à»àºàº¡',2101978,10,7,1,1,'009',1,1,10,2,10,15,8,0,''),(8,'2006-10-14 09:10:37','2006-10-14 09:13:35','001','à»„àºŠàº—àº²àº™àºµ','àº™àº±àºàºªàº´àºàºªàº²',15081987,8,4,2,1,'000',1,1,10,2,10,27,25,0,''),(9,'2006-10-14 09:14:21','2006-10-16 16:59:54','856','àºªàºµà»‚àº„àº”','àº™àº±àºàº‚àº½àº™à»‚àº›à»àºàº¡',13081981,10,7,1,1,'000',1,1,10,2,10,27,25,0,''),(10,'2006-10-27 15:48:04','2006-10-27 15:51:09','001','à»„àºŠàº—àº²àº™àºµ','àº™àº±àºàºªàº´àºàºªàº²',15081987,8,4,2,1,'010',1,1,10,2,13,29,27,0,'');
UNLOCK TABLES;
/*!40000 ALTER TABLE `pret_archive` ENABLE KEYS */;

--
-- Table structure for table `procs`
--

DROP TABLE IF EXISTS `procs`;
CREATE TABLE `procs` (
  `idproc` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `requete` blob NOT NULL,
  `comment` tinytext NOT NULL,
  `autorisations` mediumtext,
  `parameters` text,
  PRIMARY KEY  (`idproc`),
  KEY `idproc` (`idproc`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `procs`
--


/*!40000 ALTER TABLE `procs` DISABLE KEYS */;
LOCK TABLES `procs` WRITE;
INSERT INTO `procs` VALUES (1,'Liste expl/statut','select expl_cote, expl_cb, tit1 from exemplaires, notices where expl_statut=!!param1!! and expl_notice=notice_id order by expl_cote','Liste paramétrée d\'exemplaires par statut ','1 2','<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<FIELDS>\n <FIELD NAME=\"param1\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Statut]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR=\"query_list\">\r\n <QUERY><![CDATA[select idstatut,statut_libelle from docs_statut]]></QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=\"\"><![CDATA[Choisissez un statut]]></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>'),(2,'Comptage expl /statut','select statut_libelle from exemplaires, docs_statut, count(*) as Nbre where idstatut=expl_statut group by statut_libelle order by idstatut','Nombre d\'exemplaires par statut d\'exmplaire','1 2',NULL),(3,'Comptage expl /prêteur','select lender_libelle, count(*) as Nbre from exemplaires, lenders where expl_owner=idlender group by lender_libelle order by lender_libelle ','Nombre d\'exemplaires par prêteur','1 2',NULL),(4,'Comptage  expl /prêteur /statut','select lender_libelle, idstatut, statut_libelle , count(*) as Nbre from exemplaires, lenders, docs_statut where expl_owner=idlender and expl_statut=idstatut group by lender_libelle,statut_libelle order by lender_libelle,statut_libelle ','Nombre d\'exemplaires par prêteur et par statut d\'exmplaire','1 2',NULL),(5,'Liste expl d\'un prêteur /statut','select lender_libelle, statut_libelle, expl_cote, expl_cb, tit1 from exemplaires, notices, docs_statut, lenders where expl_statut=!!statut!! and expl_owner=!!Proprietaire!! and expl_notice=notice_id and expl_statut=idstatut and expl_owner=idlender order by lender_libelle, statut_libelle, expl_cote, expl_cb ','Liste d\'exemplaires d\'un propriétaire par statut, cote, code-barre, titre (pratique pour lister les documents non pointés après l\'import)','1 2','<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<FIELDS>\n <FIELD NAME=\"statut\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Statut]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR=\"query_list\">\r\n <QUERY>select idstatut, statut_libelle from docs_statut</QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=\"\"></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n <FIELD NAME=\"Proprietaire\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Proprietaire]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR=\"query_list\">\r\n <QUERY>select idlender, lender_libelle from lenders</QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=\"\"></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>'),(6,'Comptage expl /section','select idsection, section_libelle, count(*) as Nbre from exemplaires, docs_section where idsection=expl_section group by idsection, section_libelle order by idsection','Nombre d\'exemplaires par section','1 2',NULL),(7,'Liste expl pour une ou plusieurs sections par prêteur','select section_libelle, expl_cote, expl_cb, tit1 from exemplaires, notices, docs_section, lenders where idsection in (!!sections!!) and expl_owner=!!preteur!! and expl_notice=notice_id and expl_section=idsection and expl_owner=idlender order by section_libelle, expl_cote, expl_cb ','Liste des exemplaires ayant une ou plusieurs sections particulières pour un prêteur','1 2','<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<FIELDS>\n <FIELD NAME=\"sections\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Section(s)]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR=\"query_list\">\r\n <QUERY><![CDATA[select idsection, section_libelle from docs_section]]></QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=\"\"><![CDATA[]]></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n <FIELD NAME=\"preteur\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Prêteur]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR=\"query_list\">\r\n <QUERY><![CDATA[select idlender, lender_libelle from lenders order by idlender]]></QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=\"\"><![CDATA[Choisissez un prêteur]]></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>'),(8,'Stat : Compte expl /propriétaire','select lender_libelle as Proprio, count(*) as Nbre from exemplaires, lenders where idlender=expl_owner group by expl_owner, lender_libelle','Nbre d\'exemplaires par propriétaire d\'exemplaire','1 2',NULL),(9,'Liste expl du fonds propre','select statut_libelle, expl_cote, expl_cb, tit1 from exemplaires, notices, docs_statut where expl_owner=0 and expl_notice=notice_id and expl_statut=idstatut order by statut_libelle, expl_cote, expl_cb ','Liste des exemplaires du fonds propre par statut, cote, code-barre, titre','1 2',NULL),(10,'Liste expl pour un prêteur','select expl_cote, expl_cb, tit1 from exemplaires, notices, docs_statut, lenders where expl_owner=!!proprietaire!! and expl_notice=notice_id and expl_statut=idstatut and expl_owner=idlender order by  expl_cote, expl_cb ','Liste des exemplaires pour 1 propriétaire trié par cote et code-barre','1 2','<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<FIELDS>\n <FIELD NAME=\"proprietaire\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Propriétaire]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR=\"query_list\">\r\n <QUERY>select idlender, lender_libelle from lenders order by idlender</QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=\"\">Choisissez un prêteur</UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>'),(11,'Comptage lecteurs /categ','select libelle, count(*) as \'Nbre lecteurs\' from empr, empr_categ where id_categ_empr=empr_categ group by libelle order by libelle','Nombre de lecteurs par catégorie','1 2',NULL),(13,'Liste lecteurs /catégories','select libelle as Catégorie, empr_nom as Nom, empr_prenom as Prénom, empr_year as DateNaissance from empr, empr_categ where id_categ_empr=empr_categ order by libelle, empr_nom, empr_prenom','Liste des lecteurs par catégorie de lecteur, lecteur','1 2',NULL),(14,'Prêts par catégories','SELECT empr_categ.libelle as Catégorie, empr.empr_nom as Nom, empr.empr_prenom as Prénom, empr.empr_cb as Numéro, exemplaires.expl_cb as CodeBarre, notices.tit1 as Titre FROM pret,empr,empr_categ,exemplaires,notices WHERE empr_categ.id_categ_empr in (!!categorie!!) and empr.empr_categ = empr_categ.id_categ_empr and pret.pret_idempr = empr.id_empr and pret.pret_idexpl = exemplaires.expl_id and exemplaires.expl_notice = notices.notice_id order by 1,2,3,6','Liste des exemplaires en prêt pour une ou plusieurs catégories de lecteurs','1 2','<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<FIELDS>\n <FIELD NAME=\"categorie\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[categorie]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR=\"query_list\">\r\n <QUERY><![CDATA[select id_categ_empr, libelle from empr_categ order by libelle]]></QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=\"\"><![CDATA[]]></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>'),(20,'Liste fonds propre / statut','select statut_libelle, expl_cote, expl_cb, tit1 from exemplaires, notices, docs_statut where expl_owner=0 and expl_notice=notice_id and expl_statut=idstatut order by statut_libelle, expl_cote, expl_cb ','Pointage fonds propre','1 2',NULL),(21,'Stat : Compte lecteurs /age','SELECT count(*), CASE WHEN  (!!param1!! - empr_year) <= 13 THEN \'Jusque 13 ans\' WHEN (!!param1!! - empr_year) >13 and (!!param1!! - empr_year)<=24 THEN \'14 à 24 ans\' WHEN (!!param1!! - empr_year)>24 and (!!param1!! - empr_year)<=59 THEN \'25 à 29 ans\' WHEN (!!param1!! - empr_year)>59 THEN \'60 ans et plus\'  ELSE \'erreur sur age\' END as categ_age from empr where empr_categ in (!!categorie!!) and (year(empr_date_expiration)=!!param1!! or year(empr_date_adhesion)=!!param1!!) group by categ_age','Nbre de lecteurs par tranche d\'age pour une année','1 2','<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<FIELDS>\n <FIELD NAME=\"param1\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Année de calcul]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR=\"text\">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS> \n </FIELD>\n <FIELD NAME=\"categorie\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Catégorie]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR=\"query_list\">\r\n <QUERY>select id_categ_empr, libelle from empr_categ order by libelle</QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=\"\"></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>'),(22,'Stat : Compte lecteurs /sexe /age','SELECT count(*), case when empr_sexe=\'1\' then \'Hommes\' when empr_sexe=\'2\' then \'Femmes\' else \'erreur sur sexe\' end as Sexe, CASE WHEN  (!!param1!! - empr_year) <= 13 THEN \'Jusque 13 ans\' WHEN (!!param1!! - empr_year) >13 and (!!param1!! - empr_year) <= 24 THEN \'14 à 24 ans\' WHEN (!!param1!! - empr_year) >24 and (!!param1!! - empr_year) <= 59 THEN \'25 à 59 ans\' WHEN (!!param1!! - empr_year) >59 THEN \'60 ans et plus\'  ELSE \'erreur sur age\' END as categ_age from empr where empr_categ in (!!categorie!!) and (year(empr_date_expiration)=!!param1!! or year(empr_date_adhesion)=!!param1!!) group by sexe, categ_age','Nbre de lecteurs par sexe et tranche d\'age pour une année','1 2','<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<FIELDS>\n <FIELD NAME=\"param1\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Année de calcul]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR=\"text\">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS> \n </FIELD>\n <FIELD NAME=\"categorie\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Catégorie]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR=\"query_list\">\r\n <QUERY>select id_categ_empr, libelle from empr_categ order by libelle</QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=\"\"></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>'),(23,'Stat : Compte lecteurs /ville /catégorie','select empr_ville as Ville, count(*) as Nbre from empr where empr_categ in (!!categorie!!) and (year(empr_date_expiration)=!!annee!! or year(empr_date_adhesion)=!!annee!!) group by empr_ville order by empr_ville','Nbre de lecteurs par ville de résidence pour une ou plusieurs catégorie','1 2','<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<FIELDS>\n <FIELD NAME=\"categorie\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Catégorie]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR=\"query_list\">\r\n <QUERY>select id_categ_empr, libelle from empr_categ order by libelle</QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=\"\"></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n <FIELD NAME=\"annee\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Année de calcul]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR=\"text\">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>'),(24,'Stat : Compte élèves','SELECT count(*) as nbre_eleve from empr where empr_categ in (!!categorie!!) and and (year(empr_date_expiration)=!!annee!! or year(empr_date_adhesion)=!!annee!!)','Nbre de lecteurs \'Elève\' = catégorie à sélectionner ','1 2','<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<FIELDS>\n <FIELD NAME=\"categorie\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Catégorie de lecteurs]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR=\"query_list\">\r\n <QUERY><![CDATA[select id_categ_empr, libelle from empr_categ order by libelle]]></QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=\"\"><![CDATA[]]></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n <FIELD NAME=\"annee\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Année de calcul]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR=\"text\">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>'),(25,'Stat : Compte prêts pour élève ou profs','SELECT count(*) as nbre_pret_eleve from pret_archive where arc_empr_categ in (!!categorie!!) and year(arc_debut) = \'!!param1!!\'\r\n','Nbre de prêts pour les élèves de l\'école ou pour les profs (prêts pour la classe) pour une année','1 2','<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<FIELDS>\n <FIELD NAME=\"categorie\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Catégorie]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR=\"query_list\">\r\n <QUERY><![CDATA[select id_categ_empr, libelle from empr_categ order by libelle]]></QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=\"\"><![CDATA[]]></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n <FIELD NAME=\"param1\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Année de calcul]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR=\"text\">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>'),(26,'Stat : Compte prêts Documentaires E','SELECT year(arc_debut) as annee, month (arc_debut) as mois, count(*) nb_pret_Docu_E FROM pret_archive where (left (arc_expl_cote,2)=\'E \' or left (arc_expl_cote,3)=\'EB \' or left (arc_expl_cote,2)=\'E.\')and year(arc_debut) = \'!!param1!!\' group by annee, mois order by annee, mois','Nbre de prêts de documentaires Enfants pour une année','1 2','<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<FIELDS>\n <FIELD NAME=\"param1\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Année de calcul]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR=\"text\">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS> \n </FIELD>\n</FIELDS>'),(27,'Stat : Compte prêts Fictions E','SELECT year(arc_debut) as annee, month (arc_debut) as mois, count(*) nb_prets_fiction_E FROM pret_archive where (left (arc_expl_cote,3)=\'EA \' or left (arc_expl_cote,3)=\'EBD\' or left (arc_expl_cote,3)=\'EC \' or left (arc_expl_cote,3)=\'ER \') and year(arc_debut) = \'!!param1!!\' group by annee, mois order by annee, mois','Nbre de prêts de fictions Enfants pour une année','1 2','<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<FIELDS>\n <FIELD NAME=\"param1\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Année de calcul]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR=\"text\">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS> \n </FIELD>\n</FIELDS>'),(28,'Stat : Compte prêts Fictions A','SELECT year(arc_debut) as annee, month (arc_debut) as mois, count(*) nb_prets_fiction_A FROM pret_archive where (left (arc_expl_cote,1)=\'R\' or left (arc_expl_cote,3)=\'BD \' or left (arc_expl_cote,2)=\'JR\' or left (arc_expl_cote,3)=\'JBD\') and left (arc_expl_cote,3)<>\'RE \' and year(arc_debut) = \'!!param1!!\' group by annee, mois order by annee, mois','Nbre de prêts de fictions Jeunes ou Adultes pour une année','1 2','<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<FIELDS>\n <FIELD NAME=\"param1\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Année de calcul]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR=\"text\">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS> \n </FIELD>\n</FIELDS>'),(29,'Stat : Compte prêts Documentaires A & J','SELECT year(arc_debut) as annee, month (arc_debut) as mois, count(*) nb_prets_Docu_A FROM pret_archive where (left (arc_expl_cote,2)=\'H \' or left (arc_expl_cote,2)=\'B \' or left (arc_expl_cote,3)=\'FR \' or left (arc_expl_cote,2)=\'J \' or left (arc_expl_cote,2)=\'J.\' or left(arc_expl_cote,1) between \'0\' and \'9\') and year(arc_debut) = \'!!param1!!\' group by annee, mois order by annee, mois','Nbre de prêts de documentaires Jeunes ou Adultes pour une année','1 2','<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<FIELDS>\n <FIELD NAME=\"param1\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Année de calcul]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR=\"text\">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS> \n </FIELD>\n</FIELDS>'),(30,'Stat : Compte prêts TOTAL (hors Pério)','SELECT year(arc_debut) as annee, month (arc_debut) as mois, count(*) nb_prets_TOTAL FROM pret_archive where arc_expl_cote not like \'P %\' and year(arc_debut) = \'!!param1!!\' group by annee, mois order by annee, mois','Nbre total de prêts hors périodiques pour une année','1 2','<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<FIELDS>\n <FIELD NAME=\"param1\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Année de calcul]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR=\"text\">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS> \n </FIELD>\n</FIELDS>'),(31,'Stat : Compte prêts Périodiques','SELECT year(arc_debut) as annee, month (arc_debut) as mois, count(*) nb_prets_TOTAL FROM pret_archive where arc_expl_cote like \'P %\' and year(arc_debut) = \'!!param1!!\' group by annee, mois order by annee, mois','Nbre de prêts de périodiques pour une année','1 2','<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<FIELDS>\n <FIELD NAME=\"param1\" MANDATORY=\"yes\">\n  <ALIAS><![CDATA[Année de calcul]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR=\"text\">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS> \n </FIELD>\n</FIELDS>');
UNLOCK TABLES;
/*!40000 ALTER TABLE `procs` ENABLE KEYS */;

--
-- Table structure for table `publishers`
--

DROP TABLE IF EXISTS `publishers`;
CREATE TABLE `publishers` (
  `ed_id` mediumint(8) unsigned NOT NULL auto_increment,
  `ed_name` varchar(255) NOT NULL default '',
  `ed_adr1` varchar(255) NOT NULL default '',
  `ed_adr2` varchar(255) NOT NULL default '',
  `ed_cp` varchar(10) NOT NULL default '',
  `ed_ville` varchar(96) NOT NULL default '',
  `ed_pays` varchar(96) NOT NULL default '',
  `ed_web` varchar(255) NOT NULL default '',
  `index_publisher` text,
  `ed_comment` text,
  PRIMARY KEY  (`ed_id`),
  KEY `ed_name` (`ed_name`),
  KEY `ed_ville` (`ed_ville`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `publishers`
--


/*!40000 ALTER TABLE `publishers` DISABLE KEYS */;
LOCK TABLES `publishers` WRITE;
INSERT INTO `publishers` VALUES (1,'àº«àº»à»ˆàº‡àºà»ˆàº²àº¥àºµà»‰ àº¡à»ˆàº²àº','909 third Avenue','Newyork NY 10022','01','New York','àº­àº²à»€àº¡àº¥àº´àºàº²','www.hungryminds.com',' àº«àº»à»ˆàº‡àºà»ˆàº²àº¥àºµà»‰ àº¡à»ˆàº²àº ',''),(2,'à»‚àº®àº‡àºàº´àº¡àºªàº¶àºàºªàº²','àºàº³à»àºàº‡àº™àº°àº„àº­àº™','','123','àºªàºµàºªàº°àº•àº°àº™àº²àº”','àº¥àº²àº§','',' à»‚àº®àº‡àºàº´àº¡àºªàº¶àºàºªàº² ',''),(3,'à»‚àº®àº‡àºàº´àº¡àº¡àº±àº™àº—àº²àº•àº¸àº¥àº²àº”','àºàº³à»àºàº‡àº™àº°àº„àº­àº™','','125','àºªàºµà»‚àº„àº”àº•àº°àºšàº­àº‡','àº¥àº²àº§','',' à»‚àº®àº‡àºàº´àº¡àº¡àº±àº™àº—àº²àº•àº¸àº¥àº²àº” ',''),(4,'à»‚àº®àº‡àºàº´àº¡àº”àº²àº§àº§àº´à»„àº¥','àºàº³à»àºàº‡àº™àº°àº„àº­àº™','','1024','àºªàº±àº‡àº—àº­àº‡','àº¥àº²àº§','',' à»‚àº®àº‡àºàº´àº¡àº”àº²àº§àº§àº´à»„àº¥ ',''),(5,'àº­àº°àº”àº´àº”','àº«àº¼àº§àº‡àºàº°àºšàº²àº‡','','','àº«àº¼àº§àº‡àºàº°àºšàº²àº‡','àº¥àº²àº§','',' àº­àº°àº”àº´àº” ',''),(6,'àºªàº°àº–àº²àºšàº±àº™','àºàº³à»àºàº‡àº™àº°àº„àº­àº™','','','àºàº³à»àºàº‡àº™àº°àº„àº­àº™','àº¥àº²àº§','',' àºªàº°àº–àº²àºšàº±àº™ ',''),(7,'àº«à»àºàº´àºàº´àº—àº°àºàº±àº™','àºàº³à»àºàº‡àº™àº°àº„àº­àº™','','','àºàº³à»àºàº‡àº™àº°àº„àº­àº™','àº¥àº²àº§','',' àº«à»àºàº´àºàº´àº—àº°àºàº±àº™ ',''),(8,'àº™àº°àº„àº­àº™àº«àº¥àº§àº‡','àºàº³à»àºàº‡àº™àº°àº„àº­àº™','','','àºàº³à»àºàº‡àº™àº°àº„àº­àº™','àº¥àº²àº§','',' àº™àº°àº„àº­àº™àº«àº¥àº§àº‡ ',''),(9,'à»‚àº®àº‡àºàº´àº¡à»àº«à»ˆàº‡àº¥àº±àº”','àºàº³à»àºàº‡àº™àº°àº„àº­àº™','','','àºªàºµà»‚àº„àº”àº•àº°àºšàº­àº‡','àºªàº›àº›àº¥àº²àº§','',' à»‚àº®àº‡àºàº´àº¡à»àº«à»ˆàº‡àº¥àº±àº” ',''),(10,'à»‚àº®àº‡àºàº´àº¡àº¡àº±àº™àº—àº²àº•àº¸àº¥àº²àº”','','','','àºàº³à»àºàº‡àº™àº°àº„àº­àº™','àº¥àº²àº§','',' à»‚àº®àº‡àºàº´àº¡àº¡àº±àº™àº—àº²àº•àº¸àº¥àº²àº” ',''),(11,'àºªàº¹àº™àºàº¶àºàº›à»ˆàº²à»„àº¡à»‰','','','','àºàº³à»àºàº‡àº™àº°àº„àº­àº™','àºªàº›àº›àº¥àº²àº§','',' àºªàº¹àº™àºàº¶àºàº›à»ˆàº²à»„àº¡à»‰ ',''),(12,'àºàº²àº™àº›àº»àºàº„àº­àº‡','','','','àºàº³à»àºàº‡àº™àº°àº„àº­àº™','àº¥àº²àº§','',' àºàº²àº™àº›àº»àºàº„àº­àº‡ ',''),(13,'àºªàºµàºªàº°àº«àº§àº²àº”àºàº²àº™àºàº´àº¡','','','','àºàº³à»àºàº‡àº™àº°àº„àº­àº™','àºªàº›àº›àº¥àº²àº§','',' àºªàºµàºªàº°àº«àº§àº²àº”àºàº²àº™àºàº´àº¡ ',''),(14,'àº­àº»àº‡àºàº²àº™àº­àº°àº™àº²à»„àº¡à»‚àº¥àº','','','','àºàº³à»àºàº‡àº™àº°àº„àº­àº™','àºªàº›àº›àº¥àº²àº§','',' àº­àº»àº‡àºàº²àº™àº­àº°àº™àº²à»„àº¡à»‚àº¥àº ',''),(15,'àº¡àº¹àº™àº™àº´àº—àº´àºŠàº²àºŠàº²àºàº²àº§àº²','','','','àºàº³à»àºàº‡àº™àº°àº„àº­àº™','àºªàº›àº›àº¥àº²àº§','',' àº¡àº¹àº™àº™àº´àº—àº´àºŠàº²àºŠàº²àºàº²àº§àº² ',''),(16,'àº›àº²àºàº›àº²àºªàº±àºàºàº²àº™àºàº´àº¡','','','','àºàº³à»àºàº‡àº™àº°àº„àº­àº™','àºªàº›àº›àº¥àº²àº§','',' àº›àº²àºàº›àº²àºªàº±àºàºàº²àº™àºàº´àº¡ ',''),(17,'àºàº¸àº‡à»€àº—àºš','','','','àºàº¸àº‡à»€àº—àºš','à»„àº—','',' àºàº¸àº‡à»€àº—àºš ',''),(18,'àº‚àº­àº™à»àºà»ˆàº™','','','','àº‚àº­àº™à»àºà»ˆàº™','à»„àº—','',' àº‚àº­àº™à»àºà»ˆàº™ ',''),(19,'àºªàº°àºàº²àº™àº—àº­àº‡àºàº²àº™àºàº´àº¡','','','','àºàº³à»àºàº‡àº™àº°àº„àº­àº™','àºªàº›àº›àº¥àº²àº§','',' àºªàº°àºàº²àº™àº—àº­àº‡àºàº²àº™àºàº´àº¡ ',''),(20,'àºªàº³àº™àº±àºàºàº´àº¡à»àº¥àº°àºˆàº³à»œà»ˆàº²àºàº›àº·àº¡','','','','àºàº³à»àºàº‡àº™àº°àº„àº­àº™','àºªàº›àº›àº¥àº²àº§','',' àºªàº³àº™àº±àºàºàº´àº¡à»àº¥àº°àºˆàº³à»œà»ˆàº²àºàº›àº·àº¡ ','');
UNLOCK TABLES;
/*!40000 ALTER TABLE `publishers` ENABLE KEYS */;

--
-- Table structure for table `quotas`
--

DROP TABLE IF EXISTS `quotas`;
CREATE TABLE `quotas` (
  `quota_type` int(10) unsigned NOT NULL default '0',
  `constraint_type` varchar(255) NOT NULL default '',
  `elements` int(10) unsigned NOT NULL default '0',
  `value` float default NULL,
  PRIMARY KEY  (`quota_type`,`constraint_type`,`elements`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quotas`
--


/*!40000 ALTER TABLE `quotas` DISABLE KEYS */;
LOCK TABLES `quotas` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `quotas` ENABLE KEYS */;

--
-- Table structure for table `quotas_finance`
--

DROP TABLE IF EXISTS `quotas_finance`;
CREATE TABLE `quotas_finance` (
  `quota_type` int(10) unsigned NOT NULL default '0',
  `constraint_type` varchar(255) NOT NULL default '',
  `elements` int(10) unsigned NOT NULL default '0',
  `value` float default NULL,
  PRIMARY KEY  (`quota_type`,`constraint_type`,`elements`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quotas_finance`
--


/*!40000 ALTER TABLE `quotas_finance` DISABLE KEYS */;
LOCK TABLES `quotas_finance` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `quotas_finance` ENABLE KEYS */;

--
-- Table structure for table `recouvrements`
--

DROP TABLE IF EXISTS `recouvrements`;
CREATE TABLE `recouvrements` (
  `recouvr_id` int(16) unsigned NOT NULL auto_increment,
  `empr_id` int(10) unsigned NOT NULL default '0',
  `id_expl` int(10) unsigned NOT NULL default '0',
  `date_rec` date NOT NULL default '0000-00-00',
  `libelle` varchar(255) default NULL,
  `montant` decimal(16,2) default '0.00',
  PRIMARY KEY  (`recouvr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `recouvrements`
--


/*!40000 ALTER TABLE `recouvrements` DISABLE KEYS */;
LOCK TABLES `recouvrements` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `recouvrements` ENABLE KEYS */;

--
-- Table structure for table `resa`
--

DROP TABLE IF EXISTS `resa`;
CREATE TABLE `resa` (
  `id_resa` mediumint(8) unsigned NOT NULL auto_increment,
  `resa_idempr` mediumint(8) unsigned NOT NULL default '0',
  `resa_idnotice` mediumint(8) unsigned NOT NULL default '0',
  `resa_idbulletin` int(8) unsigned NOT NULL default '0',
  `resa_date` datetime default NULL,
  `resa_date_debut` date NOT NULL default '0000-00-00',
  `resa_date_fin` date NOT NULL default '0000-00-00',
  `resa_cb` varchar(14) NOT NULL default '',
  `resa_confirmee` int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_resa`),
  KEY `resa_date_fin` (`resa_date_fin`),
  KEY `resa_date` (`resa_date`),
  KEY `resa_cb` (`resa_cb`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `resa`
--


/*!40000 ALTER TABLE `resa` DISABLE KEYS */;
LOCK TABLES `resa` WRITE;
INSERT INTO `resa` VALUES (3,4,3,0,'2006-10-14 09:39:39','0000-00-00','0000-00-00','',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `resa` ENABLE KEYS */;

--
-- Table structure for table `resa_ranger`
--

DROP TABLE IF EXISTS `resa_ranger`;
CREATE TABLE `resa_ranger` (
  `resa_cb` varchar(14) NOT NULL default '',
  PRIMARY KEY  (`resa_cb`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `resa_ranger`
--


/*!40000 ALTER TABLE `resa_ranger` DISABLE KEYS */;
LOCK TABLES `resa_ranger` WRITE;
INSERT INTO `resa_ranger` VALUES ('PE38');
UNLOCK TABLES;
/*!40000 ALTER TABLE `resa_ranger` ENABLE KEYS */;

--
-- Table structure for table `responsability`
--

DROP TABLE IF EXISTS `responsability`;
CREATE TABLE `responsability` (
  `responsability_author` mediumint(8) unsigned NOT NULL default '0',
  `responsability_notice` mediumint(8) unsigned NOT NULL default '0',
  `responsability_fonction` char(3) NOT NULL default '',
  `responsability_type` mediumint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`responsability_author`,`responsability_notice`,`responsability_fonction`),
  KEY `responsability_notice` (`responsability_notice`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `responsability`
--


/*!40000 ALTER TABLE `responsability` DISABLE KEYS */;
LOCK TABLES `responsability` WRITE;
INSERT INTO `responsability` VALUES (1,1,'070',0),(2,2,'070',0),(3,3,'070',0),(5,4,'070',0),(6,5,'070',0),(10,6,'070',0),(14,7,'070',0),(20,8,'070',0),(8,9,'070',0),(9,10,'070',0),(13,11,'070',0),(6,12,'070',0),(17,13,'070',0),(20,14,'070',0),(1,15,'070',0),(9,16,'070',0),(10,17,'070',0),(2,18,'070',0),(18,19,'070',0),(3,20,'070',0),(20,21,'070',0),(19,22,'070',0),(21,23,'070',0),(13,24,'070',0),(4,4,'070',0),(5,5,'070',0),(6,6,'070',0),(8,7,'068',2),(7,7,'070',0),(9,8,'070',0),(9,9,'070',0),(10,11,'070',0),(12,12,'440',1),(11,12,'070',0),(13,13,'070',0),(15,14,'044',2),(14,14,'340',2),(17,15,'007',1),(16,15,'070',0),(18,16,'070',0),(19,17,'650',0),(20,18,'061',2),(21,18,'017',2),(22,18,'017',2),(23,18,'017',2),(26,19,'070',2),(27,19,'',1),(25,19,'070',1),(24,19,'723',0),(28,42,'720',0),(30,44,'370',0),(32,46,'545',2),(31,46,'250',0),(33,48,'705',0),(34,49,'180',0),(35,50,'070',0),(36,51,'070',0),(38,53,'068',2),(37,53,'070',0),(39,54,'070',0),(40,57,'070',0),(41,57,'007',2),(63,60,'070',0),(63,59,'070',0),(62,63,'070',0),(60,65,'070',0),(61,61,'070',0),(62,64,'070',0),(20,27,'160',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `responsability` ENABLE KEYS */;

--
-- Table structure for table `rss_content`
--

DROP TABLE IF EXISTS `rss_content`;
CREATE TABLE `rss_content` (
  `rss_id` int(10) unsigned NOT NULL default '0',
  `rss_content` longblob NOT NULL,
  `rss_last` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`rss_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rss_content`
--


/*!40000 ALTER TABLE `rss_content` DISABLE KEYS */;
LOCK TABLES `rss_content` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `rss_content` ENABLE KEYS */;

--
-- Table structure for table `rss_flux`
--

DROP TABLE IF EXISTS `rss_flux`;
CREATE TABLE `rss_flux` (
  `id_rss_flux` int(9) unsigned NOT NULL auto_increment,
  `nom_rss_flux` varchar(255) NOT NULL default '',
  `link_rss_flux` blob NOT NULL,
  `descr_rss_flux` blob NOT NULL,
  `lang_rss_flux` varchar(255) NOT NULL default 'fr',
  `copy_rss_flux` blob NOT NULL,
  `editor_rss_flux` varchar(255) NOT NULL default '',
  `webmaster_rss_flux` varchar(255) NOT NULL default '',
  `ttl_rss_flux` int(9) unsigned NOT NULL default '60',
  `img_url_rss_flux` blob NOT NULL,
  `img_title_rss_flux` blob NOT NULL,
  `img_link_rss_flux` blob NOT NULL,
  `format_flux` blob NOT NULL,
  PRIMARY KEY  (`id_rss_flux`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rss_flux`
--


/*!40000 ALTER TABLE `rss_flux` DISABLE KEYS */;
LOCK TABLES `rss_flux` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `rss_flux` ENABLE KEYS */;

--
-- Table structure for table `rss_flux_content`
--

DROP TABLE IF EXISTS `rss_flux_content`;
CREATE TABLE `rss_flux_content` (
  `num_rss_flux` int(9) unsigned NOT NULL default '0',
  `type_contenant` char(3) NOT NULL default 'BAN',
  `num_contenant` int(9) unsigned NOT NULL default '0',
  PRIMARY KEY  (`num_rss_flux`,`type_contenant`,`num_contenant`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rss_flux_content`
--


/*!40000 ALTER TABLE `rss_flux_content` DISABLE KEYS */;
LOCK TABLES `rss_flux_content` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `rss_flux_content` ENABLE KEYS */;

--
-- Table structure for table `rubriques`
--

DROP TABLE IF EXISTS `rubriques`;
CREATE TABLE `rubriques` (
  `id_rubrique` int(8) unsigned NOT NULL auto_increment,
  `num_budget` int(8) unsigned NOT NULL default '0',
  `num_parent` int(8) unsigned NOT NULL default '0',
  `libelle` varchar(255) NOT NULL default '',
  `commentaires` text NOT NULL,
  `montant` float(8,2) unsigned NOT NULL default '0.00',
  `num_cp_compta` varchar(255) NOT NULL default '',
  `autorisations` mediumtext NOT NULL,
  PRIMARY KEY  (`id_rubrique`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rubriques`
--


/*!40000 ALTER TABLE `rubriques` DISABLE KEYS */;
LOCK TABLES `rubriques` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `rubriques` ENABLE KEYS */;

--
-- Table structure for table `sauv_lieux`
--

DROP TABLE IF EXISTS `sauv_lieux`;
CREATE TABLE `sauv_lieux` (
  `sauv_lieu_id` int(10) unsigned NOT NULL auto_increment,
  `sauv_lieu_nom` varchar(50) default NULL,
  `sauv_lieu_url` varchar(255) default NULL,
  `sauv_lieu_protocol` varchar(10) default 'file',
  `sauv_lieu_host` varchar(255) default NULL,
  `sauv_lieu_login` varchar(20) default NULL,
  `sauv_lieu_password` varchar(20) default NULL,
  PRIMARY KEY  (`sauv_lieu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sauv_lieux`
--


/*!40000 ALTER TABLE `sauv_lieux` DISABLE KEYS */;
LOCK TABLES `sauv_lieux` WRITE;
INSERT INTO `sauv_lieux` VALUES (1,'sauvegarde','d:\\temp\\','file','','','');
UNLOCK TABLES;
/*!40000 ALTER TABLE `sauv_lieux` ENABLE KEYS */;

--
-- Table structure for table `sauv_log`
--

DROP TABLE IF EXISTS `sauv_log`;
CREATE TABLE `sauv_log` (
  `sauv_log_id` int(10) unsigned NOT NULL auto_increment,
  `sauv_log_start_date` date default NULL,
  `sauv_log_file` varchar(255) default NULL,
  `sauv_log_succeed` int(11) default '0',
  `sauv_log_messages` mediumtext,
  `sauv_log_userid` int(11) default NULL,
  PRIMARY KEY  (`sauv_log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sauv_log`
--


/*!40000 ALTER TABLE `sauv_log` DISABLE KEYS */;
LOCK TABLES `sauv_log` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `sauv_log` ENABLE KEYS */;

--
-- Table structure for table `sauv_sauvegardes`
--

DROP TABLE IF EXISTS `sauv_sauvegardes`;
CREATE TABLE `sauv_sauvegardes` (
  `sauv_sauvegarde_id` int(10) unsigned NOT NULL auto_increment,
  `sauv_sauvegarde_nom` varchar(50) default NULL,
  `sauv_sauvegarde_file_prefix` varchar(20) default NULL,
  `sauv_sauvegarde_tables` mediumtext,
  `sauv_sauvegarde_lieux` mediumtext,
  `sauv_sauvegarde_users` mediumtext,
  `sauv_sauvegarde_compress` int(11) default '0',
  `sauv_sauvegarde_compress_command` mediumtext,
  `sauv_sauvegarde_crypt` int(11) default '0',
  `sauv_sauvegarde_key1` varchar(32) default NULL,
  `sauv_sauvegarde_key2` varchar(32) default NULL,
  PRIMARY KEY  (`sauv_sauvegarde_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sauv_sauvegardes`
--


/*!40000 ALTER TABLE `sauv_sauvegardes` DISABLE KEYS */;
LOCK TABLES `sauv_sauvegardes` WRITE;
INSERT INTO `sauv_sauvegardes` VALUES (1,'tout','bibli','7','','1,3',0,'internal::',0,'',''),(2,'notice','bibli','5','','1',0,'internal::',0,'','');
UNLOCK TABLES;
/*!40000 ALTER TABLE `sauv_sauvegardes` ENABLE KEYS */;

--
-- Table structure for table `sauv_tables`
--

DROP TABLE IF EXISTS `sauv_tables`;
CREATE TABLE `sauv_tables` (
  `sauv_table_id` int(10) unsigned NOT NULL auto_increment,
  `sauv_table_nom` varchar(50) default NULL,
  `sauv_table_tables` text,
  PRIMARY KEY  (`sauv_table_id`),
  UNIQUE KEY `sauv_table_nom` (`sauv_table_nom`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sauv_tables`
--


/*!40000 ALTER TABLE `sauv_tables` DISABLE KEYS */;
LOCK TABLES `sauv_tables` WRITE;
INSERT INTO `sauv_tables` VALUES (1,'Biblio','analysis,bulletins,docs_codestat,docs_location,docs_section,docs_statut,docs_type,exemplaires,notices,etagere_caddie,notices_custom,notices_custom_lists,notices_custom_values'),(2,'Autorités','authors,categories,collections,noeuds,publishers,responsability,series,sub_collections,thesaurus,voir_aussi'),(3,'Aucune utilité','error_log,import_marc,old_categories,old_notices_categories,sessions'),(4,'Z3950','z_attr,z_bib,z_notices,z_query'),(5,'Emprunteurs','empr,empr_categ,empr_codestat,empr_custom,empr_custom_lists,empr_custom_values,empr_groupe,expl_custom_values,groupe,pret,pret_archive,resa'),(6,'Application','categories,lenders,parametres,procs,sauv_lieux,sauv_log,sauv_sauvegardes,sauv_tables,users,explnum,indexint,notices_categories,origine_notice,quotas,etagere,resa_ranger,admin_session,opac_sessions,audit,notice_statut,ouvertures'),(7,'TOUT','actes,admin_session,analysis,audit,authors,bannette_abon,bannette_contenu,bannette_equation,bannette_exports,bannettes,budgets,bulletins,caddie,caddie_content,caddie_procs,categories,classements,collections,comptes,coordonnees,docs_codestat,docs_location,docs_section,docs_statut,docs_type,docsloc_section,empr,empr_categ,empr_codestat,empr_custom,empr_custom_lists,empr_custom_values,empr_groupe,entites,equations,error_log,etagere,etagere_caddie,exemplaires,exercices,expl_custom,expl_custom_lists,expl_custom_values,explnum,frais,groupe,import_marc,indexint,lenders,liens_actes,lignes_actes,noeuds,notice_statut,notices,notices_categories,notices_custom,notices_custom_lists,notices_custom_values,notices_global_index,offres_remises,opac_sessions,origine_notice,ouvertures,paiements,parametres,pret,pret_archive,procs,publishers,quotas,quotas_finance,recouvrements,resa,resa_ranger,responsability,rss_content,rss_flux,rss_flux_content,rubriques,sauv_lieux,sauv_log,sauv_sauvegardes,sauv_tables,series,sessions,sub_collections,suggestions,suggestions_origine,thesaurus,transactions,tva_achats,type_abts,type_comptes,types_produits,users,voir_aussi,z_attr,z_bib,z_notices,z_query'),(9,'Caddies','caddie_procs,caddie,caddie_content'),(10,'DSI','bannette_abon,bannette_contenu,bannette_equation,bannettes,classements,equations,rss_content,rss_flux,rss_flux_content'),(11,'Finance','comptes,quotas_finance,recouvrements,transactions,type_abts,type_comptes'),(12,'',NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `sauv_tables` ENABLE KEYS */;

--
-- Table structure for table `series`
--

DROP TABLE IF EXISTS `series`;
CREATE TABLE `series` (
  `serie_id` mediumint(8) unsigned NOT NULL auto_increment,
  `serie_name` varchar(255) NOT NULL default '',
  `serie_index` text,
  PRIMARY KEY  (`serie_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `series`
--


/*!40000 ALTER TABLE `series` DISABLE KEYS */;
LOCK TABLES `series` WRITE;
INSERT INTO `series` VALUES (1,'Dayak',' dayak '),(2,'Le pithÃ©cantrope dans la valise',' pithecantrope dans valise '),(3,'Mange-coeur',' mange coeur '),(4,'Jojo',' jojo '),(5,'Ã Â»?Ã Âº?Ã Â»â€°Ã ÂºÂ§','  ');
UNLOCK TABLES;
/*!40000 ALTER TABLE `series` ENABLE KEYS */;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `SESSID` varchar(12) NOT NULL default '',
  `login` varchar(20) NOT NULL default '',
  `IP` varchar(20) NOT NULL default '',
  `SESSstart` varchar(12) NOT NULL default '',
  `LastOn` varchar(12) NOT NULL default '',
  `SESSNAME` varchar(25) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sessions`
--


/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
LOCK TABLES `sessions` WRITE;
INSERT INTO `sessions` VALUES ('1179204990','admin','127.0.0.1','1163749428','1163753279','PhpMyBibli'),('1216318863','admin','127.0.0.1','1163669698','1163670482','PhpMyBibli');
UNLOCK TABLES;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;

--
-- Table structure for table `sub_collections`
--

DROP TABLE IF EXISTS `sub_collections`;
CREATE TABLE `sub_collections` (
  `sub_coll_id` mediumint(8) unsigned NOT NULL auto_increment,
  `sub_coll_name` varchar(255) NOT NULL default '',
  `sub_coll_parent` mediumint(9) unsigned NOT NULL default '0',
  `sub_coll_issn` varchar(12) NOT NULL default '',
  `index_sub_coll` text,
  PRIMARY KEY  (`sub_coll_id`),
  KEY `sub_coll_name` (`sub_coll_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_collections`
--


/*!40000 ALTER TABLE `sub_collections` DISABLE KEYS */;
LOCK TABLES `sub_collections` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `sub_collections` ENABLE KEYS */;

--
-- Table structure for table `suggestions`
--

DROP TABLE IF EXISTS `suggestions`;
CREATE TABLE `suggestions` (
  `id_suggestion` int(12) unsigned NOT NULL auto_increment,
  `titre` tinytext NOT NULL,
  `editeur` varchar(255) NOT NULL default '',
  `auteur` varchar(255) NOT NULL default '',
  `code` varchar(255) NOT NULL default '',
  `prix` float(8,2) unsigned NOT NULL default '0.00',
  `commentaires` text,
  `statut` int(3) unsigned NOT NULL default '0',
  `num_produit` int(8) NOT NULL default '0',
  `num_entite` int(5) NOT NULL default '0',
  `index_suggestion` text NOT NULL,
  `nb` int(5) unsigned NOT NULL default '1',
  `date_creation` date NOT NULL default '0000-00-00',
  `date_decision` date NOT NULL default '0000-00-00',
  `num_rubrique` int(8) unsigned NOT NULL default '0',
  `num_fournisseur` int(5) unsigned NOT NULL default '0',
  `num_notice` int(8) unsigned NOT NULL default '0',
  `url_suggestion` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id_suggestion`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `suggestions`
--


/*!40000 ALTER TABLE `suggestions` DISABLE KEYS */;
LOCK TABLES `suggestions` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `suggestions` ENABLE KEYS */;

--
-- Table structure for table `suggestions_origine`
--

DROP TABLE IF EXISTS `suggestions_origine`;
CREATE TABLE `suggestions_origine` (
  `origine` varchar(100) NOT NULL default '',
  `num_suggestion` int(12) unsigned NOT NULL default '0',
  `type_origine` int(3) unsigned NOT NULL default '0',
  `date_suggestion` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`origine`,`num_suggestion`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `suggestions_origine`
--


/*!40000 ALTER TABLE `suggestions_origine` DISABLE KEYS */;
LOCK TABLES `suggestions_origine` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `suggestions_origine` ENABLE KEYS */;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id_tag` mediumint(8) NOT NULL auto_increment,
  `libelle` varchar(200) NOT NULL default '',
  `num_notice` mediumint(8) NOT NULL default '0',
  `user_code` varchar(50) NOT NULL default '',
  `dateajout` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id_tag`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tags`
--


/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
LOCK TABLES `tags` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;

--
-- Table structure for table `thesaurus`
--

DROP TABLE IF EXISTS `thesaurus`;
CREATE TABLE `thesaurus` (
  `id_thesaurus` int(3) unsigned NOT NULL auto_increment,
  `libelle_thesaurus` varchar(255) NOT NULL default '',
  `langue_defaut` varchar(5) NOT NULL default 'fr_FR',
  `active` char(1) NOT NULL default '1',
  `opac_active` char(1) NOT NULL default '1',
  `num_noeud_racine` int(9) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_thesaurus`),
  UNIQUE KEY `libelle_thesaurus` (`libelle_thesaurus`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `thesaurus`
--


/*!40000 ALTER TABLE `thesaurus` DISABLE KEYS */;
LOCK TABLES `thesaurus` WRITE;
INSERT INTO `thesaurus` VALUES (1,'Agneaux','fr_FR','1','1',1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `thesaurus` ENABLE KEYS */;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `id_transaction` int(10) unsigned NOT NULL auto_increment,
  `compte_id` int(8) unsigned NOT NULL default '0',
  `user_id` int(10) unsigned NOT NULL default '0',
  `user_name` varchar(255) NOT NULL default '',
  `machine` varchar(255) NOT NULL default '',
  `date_enrgt` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_prevue` date default NULL,
  `date_effective` date default NULL,
  `montant` decimal(16,2) NOT NULL default '0.00',
  `sens` int(1) NOT NULL default '0',
  `realisee` int(1) NOT NULL default '0',
  `commentaire` text,
  `encaissement` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id_transaction`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transactions`
--


/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
LOCK TABLES `transactions` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;

--
-- Table structure for table `tva_achats`
--

DROP TABLE IF EXISTS `tva_achats`;
CREATE TABLE `tva_achats` (
  `id_tva` int(8) unsigned NOT NULL auto_increment,
  `libelle` varchar(255) NOT NULL default '',
  `taux_tva` float(4,2) unsigned NOT NULL default '0.00',
  `num_cp_compta` varchar(25) NOT NULL default '0',
  PRIMARY KEY  (`id_tva`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tva_achats`
--


/*!40000 ALTER TABLE `tva_achats` DISABLE KEYS */;
LOCK TABLES `tva_achats` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `tva_achats` ENABLE KEYS */;

--
-- Table structure for table `type_abts`
--

DROP TABLE IF EXISTS `type_abts`;
CREATE TABLE `type_abts` (
  `id_type_abt` int(5) unsigned NOT NULL auto_increment,
  `type_abt_libelle` varchar(255) default NULL,
  `prepay` int(1) unsigned NOT NULL default '0',
  `prepay_deflt_mnt` decimal(16,2) NOT NULL default '0.00',
  `tarif` decimal(16,2) NOT NULL default '0.00',
  `commentaire` text NOT NULL,
  `caution` decimal(16,2) NOT NULL default '0.00',
  `localisations` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id_type_abt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `type_abts`
--


/*!40000 ALTER TABLE `type_abts` DISABLE KEYS */;
LOCK TABLES `type_abts` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `type_abts` ENABLE KEYS */;

--
-- Table structure for table `type_comptes`
--

DROP TABLE IF EXISTS `type_comptes`;
CREATE TABLE `type_comptes` (
  `id_type_compte` int(8) unsigned NOT NULL auto_increment,
  `libelle` varchar(255) NOT NULL default '',
  `type_acces` int(8) unsigned NOT NULL default '0',
  `acces_id` text NOT NULL,
  PRIMARY KEY  (`id_type_compte`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `type_comptes`
--


/*!40000 ALTER TABLE `type_comptes` DISABLE KEYS */;
LOCK TABLES `type_comptes` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `type_comptes` ENABLE KEYS */;

--
-- Table structure for table `types_produits`
--

DROP TABLE IF EXISTS `types_produits`;
CREATE TABLE `types_produits` (
  `id_produit` int(8) unsigned NOT NULL auto_increment,
  `libelle` varchar(255) NOT NULL default '',
  `num_cp_compta` varchar(25) NOT NULL default '0',
  `num_tva_achat` varchar(25) NOT NULL default '0',
  PRIMARY KEY  (`id_produit`),
  KEY `libelle` (`libelle`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `types_produits`
--


/*!40000 ALTER TABLE `types_produits` DISABLE KEYS */;
LOCK TABLES `types_produits` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `types_produits` ENABLE KEYS */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `userid` int(5) NOT NULL auto_increment,
  `create_dt` date NOT NULL default '0000-00-00',
  `last_updated_dt` date NOT NULL default '0000-00-00',
  `username` varchar(20) NOT NULL default '',
  `pwd` varchar(50) NOT NULL default '',
  `nom` varchar(30) NOT NULL default '',
  `prenom` varchar(30) default NULL,
  `rights` int(8) unsigned NOT NULL default '0',
  `user_lang` varchar(5) NOT NULL default 'fr_FR',
  `nb_per_page_search` int(10) unsigned NOT NULL default '4',
  `nb_per_page_select` int(10) unsigned NOT NULL default '10',
  `nb_per_page_gestion` int(10) unsigned NOT NULL default '20',
  `param_popup_ticket` smallint(1) unsigned NOT NULL default '0',
  `param_sounds` smallint(1) unsigned NOT NULL default '1',
  `param_licence` int(1) unsigned NOT NULL default '0',
  `deflt_notice_statut` int(6) unsigned NOT NULL default '1',
  `deflt_docs_type` int(6) unsigned NOT NULL default '1',
  `deflt_lenders` int(6) unsigned NOT NULL default '0',
  `deflt_styles` varchar(20) NOT NULL default 'default',
  `deflt_docs_statut` int(6) unsigned default '0',
  `deflt_docs_codestat` int(6) unsigned default '0',
  `value_deflt_lang` varchar(20) default 'fre',
  `value_deflt_fonction` varchar(20) default '070',
  `deflt_docs_location` int(6) unsigned default '0',
  `deflt_docs_section` int(6) unsigned default '0',
  `value_deflt_module` varchar(30) default 'circu',
  `user_email` varchar(255) default '',
  `user_alert_resamail` int(1) unsigned NOT NULL default '0',
  `deflt2docs_location` int(6) unsigned NOT NULL default '0',
  `deflt_thesaurus` int(3) unsigned NOT NULL default '1',
  `value_prefix_cote` tinyblob NOT NULL,
  `xmlta_doctype` char(2) NOT NULL default 'a',
  PRIMARY KEY  (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--


/*!40000 ALTER TABLE `users` DISABLE KEYS */;
LOCK TABLES `users` WRITE;
INSERT INTO `users` VALUES (1,'2002-07-28','2006-11-15','admin','43e9a4ab75570f5b','Super User','',255,'la_LA',20,10,20,0,1,1,1,1,2,'couleurs_onglets',1,10,'lao','070',1,10,'admin','pmb@sigb.net',1,1,1,'','a'),(2,'2004-01-21','2006-10-16','circ','3f3df3af7d72f2fb','Agent de prÃªt','',1,'fr_FR',10,10,20,0,1,0,1,1,1,'vert_et_parme',1,10,'fre','070',1,13,'circu','',0,1,1,'','a'),(3,'2004-01-21','2006-10-16','cat','7b4ed80e2270250a','BibliothÃ¨caire-adjoint','',7,'fr_FR',10,10,20,0,1,0,1,1,1,'default',1,10,'fre','070',1,13,'catal','',0,1,1,'','a'),(4,'2004-01-21','2006-10-16','bib','7c99ea71225fa75a','BibliothÃ¨caire','',23,'fr_FR',10,10,20,0,1,0,1,1,1,'default',13,12,'fre','070',7,13,'circu','',0,1,1,'','a');
UNLOCK TABLES;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

--
-- Table structure for table `voir_aussi`
--

DROP TABLE IF EXISTS `voir_aussi`;
CREATE TABLE `voir_aussi` (
  `num_noeud_orig` int(9) unsigned NOT NULL default '0',
  `num_noeud_dest` int(9) unsigned NOT NULL default '0',
  `langue` varchar(5) NOT NULL default '',
  `comment_voir_aussi` text NOT NULL,
  PRIMARY KEY  (`num_noeud_orig`,`num_noeud_dest`,`langue`),
  KEY `num_noeud_dest` (`num_noeud_dest`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `voir_aussi`
--


/*!40000 ALTER TABLE `voir_aussi` DISABLE KEYS */;
LOCK TABLES `voir_aussi` WRITE;
INSERT INTO `voir_aussi` VALUES (1390,1602,'fr_FR',''),(1391,1599,'fr_FR',''),(1392,1600,'fr_FR',''),(1394,2166,'fr_FR',''),(1395,1596,'fr_FR',''),(1398,1597,'fr_FR',''),(1399,1592,'fr_FR',''),(1400,1601,'fr_FR',''),(1401,1592,'fr_FR',''),(1411,2105,'fr_FR',''),(1413,2106,'fr_FR',''),(1414,2104,'fr_FR',''),(1415,2103,'fr_FR',''),(1416,2102,'fr_FR',''),(1417,2101,'fr_FR',''),(1431,2058,'fr_FR',''),(1435,2060,'fr_FR',''),(1545,2491,'fr_FR',''),(1553,1612,'fr_FR',''),(1563,2493,'fr_FR',''),(1592,1399,'fr_FR',''),(1592,1401,'fr_FR',''),(1595,2479,'fr_FR',''),(1596,1395,'fr_FR',''),(1597,1398,'fr_FR',''),(1598,2200,'fr_FR',''),(1599,1391,'fr_FR',''),(1600,1392,'fr_FR',''),(1601,1400,'fr_FR',''),(1602,1390,'fr_FR',''),(1607,2407,'fr_FR',''),(1612,1553,'fr_FR',''),(1623,1795,'fr_FR',''),(1623,1796,'fr_FR',''),(1628,1737,'fr_FR',''),(1670,2494,'fr_FR',''),(1672,2494,'fr_FR',''),(1726,2491,'fr_FR',''),(1729,2496,'fr_FR',''),(1737,1628,'fr_FR',''),(1760,2280,'fr_FR',''),(1795,1623,'fr_FR',''),(1796,1623,'fr_FR',''),(2057,2112,'fr_FR',''),(2058,1431,'fr_FR',''),(2060,1435,'fr_FR',''),(2101,1417,'fr_FR',''),(2102,1416,'fr_FR',''),(2103,1415,'fr_FR',''),(2104,1414,'fr_FR',''),(2105,1411,'fr_FR',''),(2106,1413,'fr_FR',''),(2112,2057,'fr_FR',''),(2166,1394,'fr_FR',''),(2184,2485,'fr_FR',''),(2184,2486,'fr_FR',''),(2200,1598,'fr_FR',''),(2280,1760,'fr_FR',''),(2407,1607,'fr_FR',''),(2467,2510,'fr_FR',''),(2479,1595,'fr_FR',''),(2485,2184,'fr_FR',''),(2486,2184,'fr_FR',''),(2490,2495,'fr_FR',''),(2491,1545,'fr_FR',''),(2491,1726,'fr_FR',''),(2491,2496,'fr_FR',''),(2491,2499,'fr_FR',''),(2491,2500,'fr_FR',''),(2492,2491,'fr_FR',''),(2493,2490,'fr_FR',''),(2493,2495,'fr_FR',''),(2494,1670,'fr_FR',''),(2494,1672,'fr_FR',''),(2494,2490,'fr_FR',''),(2495,2493,'fr_FR',''),(2496,2491,'fr_FR',''),(2496,2497,'fr_FR',''),(2497,2496,'fr_FR',''),(2499,1689,'fr_FR',''),(2499,2491,'fr_FR',''),(2499,2496,'fr_FR',''),(2500,1689,'fr_FR',''),(2500,2491,'fr_FR',''),(2500,2492,'fr_FR',''),(2502,2492,'fr_FR',''),(2504,2503,'fr_FR',''),(2507,2509,'fr_FR',''),(2508,1764,'fr_FR',''),(2509,2507,'fr_FR',''),(2510,1672,'fr_FR','');
UNLOCK TABLES;
/*!40000 ALTER TABLE `voir_aussi` ENABLE KEYS */;

--
-- Table structure for table `z_attr`
--

DROP TABLE IF EXISTS `z_attr`;
CREATE TABLE `z_attr` (
  `attr_bib_id` int(6) unsigned NOT NULL default '0',
  `attr_libelle` varchar(250) NOT NULL default '',
  `attr_attr` varchar(250) default NULL,
  PRIMARY KEY  (`attr_bib_id`,`attr_libelle`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `z_attr`
--


/*!40000 ALTER TABLE `z_attr` DISABLE KEYS */;
LOCK TABLES `z_attr` WRITE;
INSERT INTO `z_attr` VALUES (2,'sujet','21'),(2,'titre','4'),(2,'auteur','1003'),(2,'isbn','7'),(3,'sujet','21'),(3,'titre','4'),(3,'isbn','7'),(3,'auteur','1003'),(5,'auteur','1004'),(5,'titre','4'),(5,'isbn','7'),(5,'sujet','21'),(7,'isbn','7'),(7,'auteur','1003'),(7,'titre','4'),(7,'sujet','21'),(8,'auteur','1'),(8,'titre','4'),(8,'isbn','7'),(8,'sujet','21'),(8,'mots','1016'),(10,'auteur','1003'),(10,'titre','4'),(10,'isbn','7'),(10,'sujet','21'),(12,'sujet','21'),(12,'auteur','1003'),(12,'titre','4'),(12,'isbn','7'),(11,'sujet','21'),(11,'auteur','1003'),(11,'isbn','7'),(11,'titre','4'),(15,'auteur','1003'),(15,'titre','4'),(15,'isbn','7'),(15,'sujet','21'),(17,'sujet','21'),(17,'auteur','1003'),(17,'isbn','7'),(17,'titre','4'),(21,'sujet','21'),(21,'auteur','1003'),(21,'isbn','7'),(21,'titre','4');
UNLOCK TABLES;
/*!40000 ALTER TABLE `z_attr` ENABLE KEYS */;

--
-- Table structure for table `z_bib`
--

DROP TABLE IF EXISTS `z_bib`;
CREATE TABLE `z_bib` (
  `bib_id` int(6) unsigned NOT NULL auto_increment,
  `bib_nom` varchar(250) default NULL,
  `search_type` varchar(20) default NULL,
  `url` varchar(250) default NULL,
  `port` varchar(6) default NULL,
  `base` varchar(250) default NULL,
  `format` varchar(250) default NULL,
  `auth_user` varchar(250) NOT NULL default '',
  `auth_pass` varchar(250) NOT NULL default '',
  `sutrs_lang` varchar(10) NOT NULL default '',
  `fichier_func` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`bib_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `z_bib`
--


/*!40000 ALTER TABLE `z_bib` DISABLE KEYS */;
LOCK TABLES `z_bib` WRITE;
INSERT INTO `z_bib` VALUES (2,'ENS Cachan','CATALOG','138.231.48.2','21210','ADVANCE','unimarc','','','',''),(3,'BN France','CATALOG','z3950.bnf.fr','2211','ABCDEFGHIJKLMNOPQRSTUVWXYZ1456','UNIMARC','Z3950','Z3950_BNF','',''),(5,'Univ Lyon 2 SCD','CATALOG','scdinf.univ-lyon2.fr','21210','ouvrages','unimarc','','','',''),(7,'Univ Oxford','CATALOG','library.ox.ac.uk','210','ADVANCE','usmarc','','','',''),(10,'Univ Laval (QC)','CATALOG','ariane2.ulaval.ca','2200','UNICORN','USMARC','','','',''),(11,'Univ Lib Edinburgh','CATALOG','catalogue.lib.ed.ac.uk','7090','voyager','USMARC','','','',''),(12,'Library Of Congress','CATALOG','z3950.loc.gov','7090','Voyager','USMARC','','','',''),(15,'ENS Paris','CATALOG','halley.ens.fr','210','INNOPAC','UNIMARC','','','',''),(17,'Polytechnique Montréal','CATALOG','advance.biblio.polymtl.ca','210','ADVANCE','USMARC','','','',''),(21,'SUDOC','CATALOG','carmin.sudoc.abes.fr','210','ABES-Z39-PUBLIC','UNIMARC','','','',''),(8,'Univ Valenciennes','CATALOG','195.221.187.151','210','INNOPAC','UNIMARC','','','','');
UNLOCK TABLES;
/*!40000 ALTER TABLE `z_bib` ENABLE KEYS */;

--
-- Table structure for table `z_notices`
--

DROP TABLE IF EXISTS `z_notices`;
CREATE TABLE `z_notices` (
  `znotices_id` int(11) unsigned NOT NULL auto_increment,
  `znotices_query_id` int(11) default NULL,
  `znotices_bib_id` int(6) unsigned default '0',
  `isbd` text,
  `isbn` varchar(250) default NULL,
  `titre` varchar(250) default NULL,
  `auteur` varchar(250) default NULL,
  `z_marc` longblob NOT NULL,
  PRIMARY KEY  (`znotices_id`),
  KEY `idx_z_notices_idq` (`znotices_query_id`),
  KEY `idx_z_notices_isbn` (`isbn`),
  KEY `idx_z_notices_titre` (`titre`),
  KEY `idx_z_notices_auteur` (`auteur`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `z_notices`
--


/*!40000 ALTER TABLE `z_notices` DISABLE KEYS */;
LOCK TABLES `z_notices` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `z_notices` ENABLE KEYS */;

--
-- Table structure for table `z_query`
--

DROP TABLE IF EXISTS `z_query`;
CREATE TABLE `z_query` (
  `zquery_id` int(11) unsigned NOT NULL auto_increment,
  `search_attr` varchar(255) default NULL,
  `zquery_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`zquery_id`),
  KEY `zquery_date` (`zquery_date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `z_query`
--


/*!40000 ALTER TABLE `z_query` DISABLE KEYS */;
LOCK TABLES `z_query` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `z_query` ENABLE KEYS */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

