-- phpMyAdmin SQL Dump
-- version 2.6.1
-- http://www.phpmyadmin.net
-- 
-- Serveur: localhost
-- Généré le : Jeudi 05 Octobre 2006 à 17:52
-- Version du serveur: 4.1.9
-- Version de PHP: 4.3.10
-- 
-- Base de données: `bibli`
-- 
CREATE DATABASE `bibli` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE bibli;

-- --------------------------------------------------------

-- 
-- Structure de la table `actes`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `actes`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `admin_session`
-- 

CREATE TABLE `admin_session` (
  `userid` int(10) unsigned NOT NULL default '0',
  `session` blob,
  PRIMARY KEY  (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `admin_session`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `analysis`
-- 

CREATE TABLE `analysis` (
  `analysis_bulletin` int(8) unsigned NOT NULL default '0',
  `analysis_notice` int(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`analysis_bulletin`,`analysis_notice`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `analysis`
-- 

INSERT INTO `analysis` VALUES (1, 33);
INSERT INTO `analysis` VALUES (1, 35);
INSERT INTO `analysis` VALUES (1, 36);
INSERT INTO `analysis` VALUES (1, 37);
INSERT INTO `analysis` VALUES (1, 38);
INSERT INTO `analysis` VALUES (1, 39);
INSERT INTO `analysis` VALUES (1, 40);
INSERT INTO `analysis` VALUES (1, 41);
INSERT INTO `analysis` VALUES (2, 25);
INSERT INTO `analysis` VALUES (2, 26);
INSERT INTO `analysis` VALUES (2, 29);
INSERT INTO `analysis` VALUES (2, 30);
INSERT INTO `analysis` VALUES (2, 31);
INSERT INTO `analysis` VALUES (2, 32);

-- --------------------------------------------------------

-- 
-- Structure de la table `audit`
-- 

CREATE TABLE `audit` (
  `type_obj` int(1) NOT NULL default '0',
  `object_id` int(10) unsigned NOT NULL default '0',
  `user_id` int(8) unsigned NOT NULL default '0',
  `user_name` varchar(20) NOT NULL default '',
  `type_modif` int(1) NOT NULL default '1',
  `quand` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `audit`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `authors`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=64 ;

-- 
-- Contenu de la table `authors`
-- 

INSERT INTO `authors` VALUES (1, '70', 'Souton', 'Dominique', '', 0, '', ' souton dominique ', NULL);
INSERT INTO `authors` VALUES (2, '70', 'Herzhaft', 'Gérard', '', 0, 'http://perso.wanadoo.fr/cielj/charte/annuaire/herzhaft.htm', ' herzhaft gerard ', NULL);
INSERT INTO `authors` VALUES (3, '70', 'Oppel', 'Jean-Hugues', '1957-....', 0, 'http://www.ricochet-jeunes.org/auteur.asp?name=Oppel&surname=Jean-Hugues', ' oppel jean hugues ', NULL);
INSERT INTO `authors` VALUES (4, '70', 'Zimmermann', 'Daniel', '1935-2000', 0, 'http://www.humanite.presse.fr/journal/2000-12-08/2000-12-08-236014', ' zimmermann daniel ', NULL);
INSERT INTO `authors` VALUES (5, '70', 'Causse', 'Rolande', '1939-....', 0, 'http://www.ricochet-jeunes.org/auteur.asp?name=Causse&surname=Rolande', ' causse rolande ', NULL);
INSERT INTO `authors` VALUES (6, '70', 'Riou', 'Jean-Michel', '', 0, '', ' riou jean michel ', NULL);
INSERT INTO `authors` VALUES (7, '70', 'Taylor', 'Mildred D.', '', 0, 'http://falcon.jmu.edu/~ramseyil/taylor.htm', ' taylor mildred ', NULL);
INSERT INTO `authors` VALUES (8, '70', 'Vassallo-Villaneau', 'Rose-Marie', '1946-....', 0, '', ' vassallo villaneau rose marie ', NULL);
INSERT INTO `authors` VALUES (9, '70', 'Adamov', 'Philippe', '1956-....', 0, 'http://www.lambiek.net/fr/adamov_philippe.html', ' adamov philippe ', NULL);
INSERT INTO `authors` VALUES (10, '70', 'Lamquet', 'Chris', '1954-....', 0, '', ' lamquet chris ', NULL);
INSERT INTO `authors` VALUES (11, '70', 'Gallié', 'Mathieu', '', 0, '', ' gallie mathieu ', NULL);
INSERT INTO `authors` VALUES (12, '70', 'Andréaé', 'Jean-Baptiste', '', 0, '', ' andreae jean baptiste ', NULL);
INSERT INTO `authors` VALUES (13, '70', 'Geerts', 'André', '1955-....', 0, '', ' geerts andre ', NULL);
INSERT INTO `authors` VALUES (14, '70', 'Robin', 'Christian', '1961-....', 0, '', ' robin christian ', NULL);
INSERT INTO `authors` VALUES (15, '70', 'Got', 'Yves', '1939-....', 0, '', ' got yves ', NULL);
INSERT INTO `authors` VALUES (16, '70', 'Nuridsany', 'Claude', '1946-....', 0, '', ' nuridsany claude ', NULL);
INSERT INTO `authors` VALUES (17, '70', 'Pérennou', 'Marie', '1946-....', 0, '', ' perennou marie ', NULL);
INSERT INTO `authors` VALUES (18, '70', 'Overdulve', 'Cornelis Marinus', '1929-....', 0, '', ' overdulve cornelis marinus ', NULL);
INSERT INTO `authors` VALUES (19, '71', 'Editions Michelin', '', '', 0, '', ' editions michelin ', NULL);
INSERT INTO `authors` VALUES (20, '70', 'Brouwers', 'Pierre', '', 0, '', ' brouwers pierre ', NULL);
INSERT INTO `authors` VALUES (21, '70', 'Loyola', 'Annabel', '', 0, '', ' loyola annabel ', NULL);
INSERT INTO `authors` VALUES (22, '70', 'Brouwers', 'Magali', '', 0, '', ' brouwers magali ', NULL);
INSERT INTO `authors` VALUES (23, '70', 'Reuter', 'Philippe', '', 0, '', ' reuter philippe ', NULL);
INSERT INTO `authors` VALUES (24, '71', 'Conseil général du Maine-et-Loire (CG49)', '', '', 0, '', ' conseil general maine loire cg49 ', NULL);
INSERT INTO `authors` VALUES (25, '70', 'Prigent', 'Daniel', '', 0, '', ' prigent daniel ', NULL);
INSERT INTO `authors` VALUES (26, '70', 'Hunot', 'Jean-Yves', '', 0, '', ' hunot jean yves ', NULL);
INSERT INTO `authors` VALUES (27, '71', 'Altaïr productions multimédia', '', '', 0, '', ' altair productions multimedia ', NULL);
INSERT INTO `authors` VALUES (28, '70', 'Rezay', 'Guillaume de', '', 0, '', ' rezay guillaume ', NULL);
INSERT INTO `authors` VALUES (30, '71', 'sound-fishing.net', '', '', 0, '', ' sound fishing net ', NULL);
INSERT INTO `authors` VALUES (31, '70', 'Bride', 'Philip', '', 0, '', ' bride philip ', '');
INSERT INTO `authors` VALUES (32, '70', 'Marion', 'Alain', '', 0, '', ' marion alain ', NULL);
INSERT INTO `authors` VALUES (33, '70', 'Favulier', 'Jacques', '', 0, '', ' favulier jacques ', NULL);
INSERT INTO `authors` VALUES (34, '70', 'Cassini de Thury', 'César-François', '', 0, '', ' cassini thury cesar francois ', NULL);
INSERT INTO `authors` VALUES (35, '70', 'Fraxler', 'Hans', '', 0, '', ' fraxler hans ', NULL);
INSERT INTO `authors` VALUES (36, '70', 'Bourrier', 'Théodore', '', 0, '', ' bourrier theodore ', NULL);
INSERT INTO `authors` VALUES (37, '70', 'Robinson', 'Patrick', '', 0, '', ' robinson patrick ', NULL);
INSERT INTO `authors` VALUES (38, '70', 'Langlois-Chassaignon', 'Claudie', '1937-....', 0, '', ' langlois chassaignon claudie ', NULL);
INSERT INTO `authors` VALUES (39, '70', 'Orieux', 'Eugène', '1823-1901', 0, '', ' orieux eugene ', NULL);
INSERT INTO `authors` VALUES (40, '70', 'Pichard', 'Georges', '1920-2003', 0, '', ' pichard georges ', NULL);
INSERT INTO `authors` VALUES (41, '70', 'Zola', 'Émile', '1840-1902', 0, '', ' zola emile ', NULL);
INSERT INTO `authors` VALUES (63, '71', 'àºªàº¹àº™àº?àº²àº‡àºªàº°àº«àº°àºžàº±àº™àº?àº³àº¡àº°àºšàº²àº™àº¥àº²àº§', '', '05/10/2006', 0, '', '  ', '');
INSERT INTO `authors` VALUES (62, '71', 'àº„àº°àº™àº°àº­àº±àº?àºªàº­àº™àºªàº²àº” àº¡/àºŠ', '', '05/10/2006', 0, '', '  ', '');
INSERT INTO `authors` VALUES (61, '70', 'àºªàº¸à»€àº™àº” à»‚àºžàº—àº´àºªàº²àº™', '', '05/10/2006', 0, '', '  ', '');
INSERT INTO `authors` VALUES (60, '71', 'àºªàº°àº–àº²àºšàº±àº™àº„àº»àº™àº„àº§à»‰àº²àº§àº±àº”àº—àº°àº™àº°àº—àº³', '', '05/10/2006', 0, '', '  ', '');
INSERT INTO `authors` VALUES (59, '70', 'à»?àº?à»‰àº§', '', '', 0, '', '  ', '');
INSERT INTO `authors` VALUES (58, '70', 'CD', '', '', 0, '', ' cd ', '');
INSERT INTO `authors` VALUES (57, '70', '2 àº?àº°à»?àºŠàº” VDO', '', '', 0, '', ' 2 vdo ', '');
INSERT INTO `authors` VALUES (56, '70', '1 àº›àº·à»‰àº¡', '', '', 0, '', ' 1 ', '');
INSERT INTO `authors` VALUES (55, '70', 'à»€àº­àº?àº°àºªàº²àº™', '', '', 0, '', '  ', '');

-- --------------------------------------------------------

-- 
-- Structure de la table `bannette_abon`
-- 

CREATE TABLE `bannette_abon` (
  `num_bannette` int(9) unsigned NOT NULL default '0',
  `num_empr` int(9) unsigned NOT NULL default '0',
  `actif` int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`num_bannette`,`num_empr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `bannette_abon`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `bannette_contenu`
-- 

CREATE TABLE `bannette_contenu` (
  `num_bannette` int(9) unsigned NOT NULL default '0',
  `num_notice` int(9) unsigned NOT NULL default '0',
  PRIMARY KEY  (`num_bannette`,`num_notice`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `bannette_contenu`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `bannette_equation`
-- 

CREATE TABLE `bannette_equation` (
  `num_bannette` int(9) unsigned NOT NULL default '0',
  `num_equation` int(9) unsigned NOT NULL default '0',
  PRIMARY KEY  (`num_bannette`,`num_equation`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `bannette_equation`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `bannette_exports`
-- 

CREATE TABLE `bannette_exports` (
  `num_bannette` int(11) unsigned NOT NULL default '0',
  `export_format` int(3) NOT NULL default '0',
  `export_data` longblob NOT NULL,
  `export_nomfichier` varchar(255) default '',
  PRIMARY KEY  (`num_bannette`,`export_format`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `bannette_exports`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `bannettes`
-- 

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
  PRIMARY KEY  (`id_bannette`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `bannettes`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `budgets`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `budgets`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `bulletins`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Contenu de la table `bulletins`
-- 

INSERT INTO `bulletins` VALUES (1, '277', 24, '', '2004-08-04', NULL, NULL, NULL);
INSERT INTO `bulletins` VALUES (2, '278', 24, '', '2004-08-04', NULL, NULL, NULL);

-- --------------------------------------------------------

-- 
-- Structure de la table `caddie`
-- 

CREATE TABLE `caddie` (
  `idcaddie` int(8) unsigned NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `type` varchar(20) NOT NULL default 'NOTI',
  `comment` varchar(255) default NULL,
  `autorisations` mediumtext,
  PRIMARY KEY  (`idcaddie`),
  KEY `caddie_type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- 
-- Contenu de la table `caddie`
-- 

INSERT INTO `caddie` VALUES (1, 'Notices pour exposition', 'NOTI', 'Placer dans ce panier les notices de l''expo virtuelle', '1 2');
INSERT INTO `caddie` VALUES (2, 'Notices pour retour BDP', 'NOTI', 'Remplir ce panier à l''issue du pointage des exemplaires en retour', '1 2');
INSERT INTO `caddie` VALUES (3, 'Exemplaires pour retour BDP', 'EXPL', 'Placer dans ce panier les exemplaires de documents à rendre à la BDP', '1 2');
INSERT INTO `caddie` VALUES (4, 'Notices en doublons sur titre', 'NOTI', 'Doublons sur le premier titre', '1 2');
INSERT INTO `caddie` VALUES (8, 'Exemple de panier d''exemplaires', 'EXPL', '', '1 4 3 2');
INSERT INTO `caddie` VALUES (5, 'Loire - Notices pour thème du mois', 'NOTI', '', '1 4');
INSERT INTO `caddie` VALUES (6, 'Loire - Bulletins contenant des articles pour expo mois', 'BULL', '', '1 4');
INSERT INTO `caddie` VALUES (7, 'Cochon - notices pour exposition mois prochain', 'NOTI', '', '1');

-- --------------------------------------------------------

-- 
-- Structure de la table `caddie_content`
-- 

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
-- Contenu de la table `caddie_content`
-- 

INSERT INTO `caddie_content` VALUES (5, 17, NULL, NULL, NULL);
INSERT INTO `caddie_content` VALUES (5, 19, NULL, NULL, NULL);
INSERT INTO `caddie_content` VALUES (6, 1, NULL, NULL, NULL);
INSERT INTO `caddie_content` VALUES (6, 2, NULL, NULL, NULL);
INSERT INTO `caddie_content` VALUES (5, 42, NULL, NULL, NULL);
INSERT INTO `caddie_content` VALUES (5, 0, 0x33333730303030343531323937, 'EXPL_CB', NULL);
INSERT INTO `caddie_content` VALUES (5, 46, NULL, NULL, NULL);
INSERT INTO `caddie_content` VALUES (8, 0, 0x3130, 'EXPL_CB', '1');
INSERT INTO `caddie_content` VALUES (7, 44, NULL, NULL, NULL);
INSERT INTO `caddie_content` VALUES (7, 47, NULL, NULL, NULL);
INSERT INTO `caddie_content` VALUES (5, 41, NULL, NULL, NULL);
INSERT INTO `caddie_content` VALUES (5, 32, NULL, NULL, NULL);
INSERT INTO `caddie_content` VALUES (5, 49, NULL, NULL, NULL);
INSERT INTO `caddie_content` VALUES (7, 50, NULL, NULL, NULL);
INSERT INTO `caddie_content` VALUES (7, 48, NULL, NULL, NULL);
INSERT INTO `caddie_content` VALUES (7, 51, NULL, NULL, NULL);
INSERT INTO `caddie_content` VALUES (5, 25, NULL, NULL, NULL);
INSERT INTO `caddie_content` VALUES (8, 22, NULL, NULL, NULL);

-- --------------------------------------------------------

-- 
-- Structure de la table `caddie_procs`
-- 

CREATE TABLE `caddie_procs` (
  `idproc` smallint(5) unsigned NOT NULL auto_increment,
  `type` varchar(20) NOT NULL default 'SELECT',
  `name` varchar(255) NOT NULL default '',
  `requete` blob NOT NULL,
  `comment` tinytext NOT NULL,
  `autorisations` mediumtext,
  `parameters` text,
  PRIMARY KEY  (`idproc`),
  KEY `idproc` (`idproc`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- 
-- Contenu de la table `caddie_procs`
-- 

INSERT INTO `caddie_procs` VALUES (3, 'SELECT', 'EXPL par section / propriétaire', 0x73656c656374206578706c5f6964206173206f626a6563745f69642c20274558504c27206173206f626a6563745f747970652066726f6d206578656d706c6169726573207768657265206578706c5f73656374696f6e20696e2028212173656374696f6e21212920616e64206578706c5f6f776e65723d212170726f7072696f2121, 'Sélection d''exemplaires par section par propriétaire', '1 2', '<?xml version="1.0" encoding="iso-8859-1"?>\n<FIELDS>\n <FIELD NAME="section" MANDATORY="yes">\n  <ALIAS><![CDATA[Section]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR="query_list">\r\n <QUERY><![CDATA[select idsection, section_libelle from docs_section order by section_libelle]]></QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""><![CDATA[]]></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n <FIELD NAME="proprio" MANDATORY="yes">\n  <ALIAS><![CDATA[Propriétaire]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR="query_list">\r\n <QUERY>select idlender, lender_libelle from lenders order by lender_libelle</QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>');
INSERT INTO `caddie_procs` VALUES (4, 'SELECT', 'EXPL où cote commence par', 0x73656c656374206578706c5f6964206173206f626a6563745f69642c20274558504c27206173206f626a6563745f747970652066726f6d206578656d706c6169726573207768657265206578706c5f636f7465206c696b6520272121636f6d6d655f636f746521212527, 'Sélection d''exemplaire à partir du début de cote', '1 2', '<?xml version="1.0" encoding="iso-8859-1"?>\n<FIELDS>\n <FIELD NAME="comme_cote" MANDATORY="no">\n  <ALIAS><![CDATA[Début de la cote]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR="text">\r\n <SIZE>20</SIZE>\r\n <MAXSIZE>20</MAXSIZE>\r\n</OPTIONS> \n </FIELD>\n</FIELDS>');
INSERT INTO `caddie_procs` VALUES (6, 'ACTION', 'Retour BDP des exemplaires', 0x757064617465206578656d706c616972657320736574206578706c5f7374617475743d21216e6f75766561755f7374617475742121207768657265206578706c5f696420696e2028434144444945284558504c2929, 'Permet de changer le statut des exemplaires d''un panier', '1 2 3', '<?xml version="1.0" encoding="iso-8859-1"?>\n<FIELDS>\n <FIELD NAME="nouveau_statut" MANDATORY="yes">\n  <ALIAS><![CDATA[nouveau_statut]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR="query_list">\r\n <QUERY>SELECT idstatut, statut_libelle FROM docs_statut</QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>');
INSERT INTO `caddie_procs` VALUES (1, 'SELECT', 'Notices par auteur', 0x53454c454354206e6f746963655f6964206173206f626a6563745f69642c20274e4f544927206173206f626a6563745f747970652046524f4d206e6f74696365732c20617574686f72732c20726573706f6e736162696c69747920574845524520617574686f725f6e616d65206c696b652027252121637269746572652121252720414e4420617574686f725f69643d726573706f6e736162696c6974795f617574686f7220414e44206e6f746963655f69643d726573706f6e736162696c6974795f6e6f746963650d0a, 'Sélection des notices dont le nom de l''auteur contient certaines lettres', '1 2 3', '<?xml version="1.0" encoding="iso-8859-1"?>\n<FIELDS>\n <FIELD NAME="critere" MANDATORY="yes">\n  <ALIAS><![CDATA[Caractères contenus dans le nom]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR="text">\r\n <SIZE>25</SIZE>\r\n <MAXSIZE>25</MAXSIZE>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>');
INSERT INTO `caddie_procs` VALUES (2, 'SELECT', 'Notices en doublons', 0x6372656174652054454d504f52415259205441424c4520746d702053454c45435420746974312046524f4d206e6f74696365732047524f5550204259207469743120484156494e4720636f756e74282a293e310d0a53454c454354206e6f746963655f6964206173206f626a6563745f69642c20274e4f544927206173206f626a6563745f747970652046524f4d206e6f74696365732c20746d70207748455245206e6f74696365732e746974313d746d702e74697431, 'Sélection des notices en doublons sur le premier titre', '1 2 3', NULL);
INSERT INTO `caddie_procs` VALUES (7, 'SELECT', 'Jamais prêtés', 0x53454c454354206578706c5f6964206173206f626a6563745f69642c20274558504c27206173206f626a6563745f747970652c20636f6e63617428224c4956524520222c74697431292061732054697472652046524f4d206e6f7469636573206a6f696e206578656d706c6169726573206f6e206578706c5f6e6f746963653d6e6f746963655f6964204c454654204a4f494e20707265745f61726368697665204f4e206172635f6578706c5f6e6f74696365203d206e6f746963655f6964207768657265206172635f6578706c5f6964204953204e554c4c20414e44206578706c5f6964204953204e4f54204e554c4c20554e494f4e2053454c454354206578706c5f6964206173206f626a6563745f69642c20274558504c27206173206f626a6563745f747970652c20636f6e6361742822504552494f20222c746974312c2022204e756de9726f203a20222c62756c6c6574696e5f6e756d65726f292061732054697472652046524f4d202862756c6c6574696e7320494e4e4552204a4f494e206e6f7469636573204f4e2062756c6c6574696e732e62756c6c6574696e5f6e6f74696365203d206e6f74696365732e6e6f746963655f69642920494e4e4552204a4f494e206578656d706c6169726573206f6e206578706c5f62756c6c6574696e3d62756c6c6574696e5f6964204c454654204a4f494e20707265745f61726368697665204f4e206578706c5f6964203d206172635f6578706c5f696420574845524520707265745f617263686976652e6172635f6964204973204e756c6c, 'Ajoute dans un panier les exemplaires jamais prêtés', '1 2', NULL);
INSERT INTO `caddie_procs` VALUES (8, 'SELECT', 'Sélection d''exemplaires par statut', 0x73656c656374206578706c5f6964206173206f626a6563745f69642c20274558504c27206173206f626a6563745f747970652066726f6d206578656d706c6169726573207768657265206578706c5f73746174757420696e20282121737461747574212129, '', '1 2', '<?xml version="1.0" encoding="iso-8859-1"?>\n<FIELDS>\n <FIELD NAME="statut" MANDATORY="yes">\n  <ALIAS><![CDATA[statut]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR="query_list">\r\n <QUERY><![CDATA[select idstatut, statut_libelle from docs_statut]]></QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""><![CDATA[]]></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>');
INSERT INTO `caddie_procs` VALUES (9, 'SELECT', 'Sélection d''exemplaires par localisation, section, statut, propriétaire', 0x73656c656374206578706c5f6964206173206f626a6563745f69642c20274558504c27206173206f626a6563745f747970652066726f6d206578656d706c6169726573207768657265206578706c5f73656374696f6e20696e2028212173656374696f6e21212920616e64206578706c5f6c6f636174696f6e20696e202821216c6f636174696f6e21212920616e64206578706c5f73746174757420696e2028212173746174757421212920616e64206578706c5f6f776e65723d212170726f7072696f21212020, '', '1 2', '<?xml version="1.0" encoding="iso-8859-1"?>\n<FIELDS>\n <FIELD NAME="section" MANDATORY="yes">\n  <ALIAS><![CDATA[Section]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR="query_list">\r\n <QUERY><![CDATA[select idsection, section_libelle from docs_section order by 2]]></QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""><![CDATA[]]></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n <FIELD NAME="location" MANDATORY="yes">\n  <ALIAS><![CDATA[Localisation]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR="query_list">\r\n <QUERY><![CDATA[select idlocation, location_libelle from docs_location order by 2]]></QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""><![CDATA[]]></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n <FIELD NAME="statut" MANDATORY="yes">\n  <ALIAS><![CDATA[Statut]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR="query_list">\r\n <QUERY><![CDATA[select idstatut, statut_libelle from docs_statut order by 2]]></QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""><![CDATA[]]></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n <FIELD NAME="proprio" MANDATORY="yes">\n  <ALIAS><![CDATA[Propriétaire]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR="query_list">\r\n <QUERY><![CDATA[select idlender, lender_libelle from lenders order by 2]]></QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""><![CDATA[]]></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>');

-- --------------------------------------------------------

-- 
-- Structure de la table `categories`
-- 

CREATE TABLE `categories` (
  `num_noeud` int(9) unsigned NOT NULL default '0',
  `langue` varchar(5) NOT NULL default 'fr_FR',
  `libelle_categorie` text NOT NULL,
  `note_application` text NOT NULL,
  `comment_public` text NOT NULL,
  `comment_voir` text NOT NULL,
  `index_categorie` text NOT NULL,
  PRIMARY KEY  (`num_noeud`,`langue`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `categories`
-- 

INSERT INTO `categories` VALUES (2512, 'fr_FR', '~termes non classés', '', '', '', ' termes non classes ');
INSERT INTO `categories` VALUES (1378, 'fr_FR', 'art et culture', '', '', '', ' art culture ');
INSERT INTO `categories` VALUES (1379, 'fr_FR', 'Institutions et politique', '', '', '', ' institutions politique ');
INSERT INTO `categories` VALUES (1380, 'fr_FR', 'Jeunesse', '', '', '', ' jeunesse ');
INSERT INTO `categories` VALUES (1381, 'fr_FR', 'Sciences', '', '', '', ' sciences ');
INSERT INTO `categories` VALUES (1382, 'fr_FR', 'Sciences humaines', '', '', '', ' sciences humaines ');
INSERT INTO `categories` VALUES (1383, 'fr_FR', 'Société', '', '', '', ' societe ');
INSERT INTO `categories` VALUES (1384, 'fr_FR', 'Sports et loisirs', '', '', '', ' sports loisirs ');
INSERT INTO `categories` VALUES (1385, 'fr_FR', 'Techniques', '', '', '', ' techniques ');
INSERT INTO `categories` VALUES (1386, 'fr_FR', 'Vie pratique', '', '', '', ' vie pratique ');
INSERT INTO `categories` VALUES (1387, 'fr_FR', 'Zones géographiques', '', '', '', ' zones geographiques ');
INSERT INTO `categories` VALUES (1388, 'fr_FR', 'Essais', '', '', '', ' essais ');
INSERT INTO `categories` VALUES (1389, 'fr_FR', 'Illustration', '', '', '', ' illustration ');
INSERT INTO `categories` VALUES (1390, 'fr_FR', 'Architecture', '', '', '', ' architecture ');
INSERT INTO `categories` VALUES (1391, 'fr_FR', 'Bande dessinée', '', '', '', ' bande dessinee ');
INSERT INTO `categories` VALUES (1392, 'fr_FR', 'Cinéma', '', '', '', ' cinema ');
INSERT INTO `categories` VALUES (1393, 'fr_FR', 'Design', '', '', '', ' design ');
INSERT INTO `categories` VALUES (1394, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (1395, 'fr_FR', 'Livre', '', '', '', ' livre ');
INSERT INTO `categories` VALUES (1396, 'fr_FR', 'Mobilier et objets d''art', '', '', '', ' mobilier objets art ');
INSERT INTO `categories` VALUES (1397, 'fr_FR', 'Musées', '', '', '', ' musees ');
INSERT INTO `categories` VALUES (1398, 'fr_FR', 'Musique', '', '', '', ' musique ');
INSERT INTO `categories` VALUES (1399, 'fr_FR', 'Peinture', '', '', '', ' peinture ');
INSERT INTO `categories` VALUES (1400, 'fr_FR', 'Photographie', '', '', '', ' photographie ');
INSERT INTO `categories` VALUES (1401, 'fr_FR', 'Sculpture', '', '', '', ' sculpture ');
INSERT INTO `categories` VALUES (1402, 'fr_FR', 'Architectes', '', '', '', ' architectes ');
INSERT INTO `categories` VALUES (1403, 'fr_FR', 'Moyen-Age', '', '', '', ' moyen age ');
INSERT INTO `categories` VALUES (1404, 'fr_FR', 'Ecoles et styles', '', '', '', ' ecoles styles ');
INSERT INTO `categories` VALUES (1405, 'fr_FR', 'Edifices', '', '', '', ' edifices ');
INSERT INTO `categories` VALUES (1406, 'fr_FR', 'Géographie', '', '', '', ' geographie ');
INSERT INTO `categories` VALUES (1407, 'fr_FR', 'XIXème siècle', '', '', '', ' xixeme siecle ');
INSERT INTO `categories` VALUES (1408, 'fr_FR', 'Histoire', '', '', '', ' histoire ');
INSERT INTO `categories` VALUES (1409, 'fr_FR', 'XXème siècle', '', '', '', ' xxeme siecle ');
INSERT INTO `categories` VALUES (1410, 'fr_FR', 'Afrique', '', '', '', ' afrique ');
INSERT INTO `categories` VALUES (1411, 'fr_FR', 'Récit historique', '', '', '', ' recit historique ');
INSERT INTO `categories` VALUES (1412, 'fr_FR', 'Héroïc fantasy', '', '', '', ' heroic fantasy ');
INSERT INTO `categories` VALUES (1413, 'fr_FR', 'Fantastique', '', '', '', ' fantastique ');
INSERT INTO `categories` VALUES (1414, 'fr_FR', 'Science-fiction', '', '', '', ' science fiction ');
INSERT INTO `categories` VALUES (1415, 'fr_FR', 'Policier', '', '', '', ' policier ');
INSERT INTO `categories` VALUES (1416, 'fr_FR', 'Humour', '', '', '', ' humour ');
INSERT INTO `categories` VALUES (1417, 'fr_FR', 'Aventure', '', '', '', ' aventure ');
INSERT INTO `categories` VALUES (1418, 'fr_FR', 'Manga', '', '', '', ' manga ');
INSERT INTO `categories` VALUES (1419, 'fr_FR', 'Réalité', '', '', '', ' realite ');
INSERT INTO `categories` VALUES (1420, 'fr_FR', 'Par genre (sélection)', '', '', '', ' par genre selection ');
INSERT INTO `categories` VALUES (1421, 'fr_FR', 'Antiquité grecque et romaine', '', '', '', ' antiquite grecque romaine ');
INSERT INTO `categories` VALUES (1422, 'fr_FR', 'Moyen-Age', '', '', '', ' moyen age ');
INSERT INTO `categories` VALUES (1423, 'fr_FR', 'Renaissance', '', '', '', ' renaissance ');
INSERT INTO `categories` VALUES (1424, 'fr_FR', 'XVIIème siècle', '', '', '', ' xviieme siecle ');
INSERT INTO `categories` VALUES (1425, 'fr_FR', 'XVIIIème siècle', '', '', '', ' xviiieme siecle ');
INSERT INTO `categories` VALUES (1426, 'fr_FR', 'De 1800 à 1849', '', '', '', ' 1800 1849 ');
INSERT INTO `categories` VALUES (1427, 'fr_FR', 'De 1850 à 1899', '', '', '', ' 1850 1899 ');
INSERT INTO `categories` VALUES (1428, 'fr_FR', 'De 1900 à 1949', '', '', '', ' 1900 1949 ');
INSERT INTO `categories` VALUES (1429, 'fr_FR', 'De 1950 à 1999 (sélection)', '', '', '', ' 1950 1999 selection ');
INSERT INTO `categories` VALUES (1430, 'fr_FR', 'De 2000 à aujourd''hui (sélection)', '', '', '', ' 2000 aujourd hui selection ');
INSERT INTO `categories` VALUES (1431, 'fr_FR', 'Roman historique', '', '', '', ' roman historique ');
INSERT INTO `categories` VALUES (1432, 'fr_FR', 'Récits de voyage et d''exploration', '', '', '', ' recits voyage exploration ');
INSERT INTO `categories` VALUES (1433, 'fr_FR', 'Fantastique et merveilleux', '', '', '', ' fantastique merveilleux ');
INSERT INTO `categories` VALUES (1434, 'fr_FR', 'Roman policier', '', '', '', ' roman policier ');
INSERT INTO `categories` VALUES (1435, 'fr_FR', 'Science-fiction', '', '', '', ' science fiction ');
INSERT INTO `categories` VALUES (1436, 'fr_FR', 'Nouvelles', '', '', '', ' nouvelles ');
INSERT INTO `categories` VALUES (1437, 'fr_FR', 'Littérature en miettes', '', '', '', ' litterature miettes ');
INSERT INTO `categories` VALUES (1438, 'fr_FR', 'Distorsions', '', '', '', ' distorsions ');
INSERT INTO `categories` VALUES (1439, 'fr_FR', 'Mémoires et autobiographies', '', '', '', ' memoires autobiographies ');
INSERT INTO `categories` VALUES (1440, 'fr_FR', 'Journaux et carnets', '', '', '', ' journaux carnets ');
INSERT INTO `categories` VALUES (1441, 'fr_FR', 'Correspondance', '', '', '', ' correspondance ');
INSERT INTO `categories` VALUES (1442, 'fr_FR', 'Littérature asiatique', '', '', '', ' litterature asiatique ');
INSERT INTO `categories` VALUES (1443, 'fr_FR', 'L''écrivain et son oeuvre', '', '', '', ' ecrivain son oeuvre ');
INSERT INTO `categories` VALUES (1444, 'fr_FR', 'Le livre comme miroir', '', '', '', ' livre comme miroir ');
INSERT INTO `categories` VALUES (1445, 'fr_FR', 'Littérature américaine', '', '', '', ' litterature americaine ');
INSERT INTO `categories` VALUES (1446, 'fr_FR', 'Poésie', '', '', '', ' poesie ');
INSERT INTO `categories` VALUES (1447, 'fr_FR', 'Poésie', '', '', '', ' poesie ');
INSERT INTO `categories` VALUES (1448, 'fr_FR', 'Poésie', '', '', '', ' poesie ');
INSERT INTO `categories` VALUES (1449, 'fr_FR', 'Poésie', '', '', '', ' poesie ');
INSERT INTO `categories` VALUES (1450, 'fr_FR', 'Poésie', '', '', '', ' poesie ');
INSERT INTO `categories` VALUES (1451, 'fr_FR', 'Poésie', '', '', '', ' poesie ');
INSERT INTO `categories` VALUES (1452, 'fr_FR', 'Poésie', '', '', '', ' poesie ');
INSERT INTO `categories` VALUES (1453, 'fr_FR', 'Poésie', '', '', '', ' poesie ');
INSERT INTO `categories` VALUES (1454, 'fr_FR', 'Poésie', '', '', '', ' poesie ');
INSERT INTO `categories` VALUES (1455, 'fr_FR', 'Littérature hispano-américaine', '', '', '', ' litterature hispano americaine ');
INSERT INTO `categories` VALUES (1456, 'fr_FR', 'Théâtre', '', '', '', ' theatre ');
INSERT INTO `categories` VALUES (1457, 'fr_FR', 'Théâtre', '', '', '', ' theatre ');
INSERT INTO `categories` VALUES (1458, 'fr_FR', 'Théâtre', '', '', '', ' theatre ');
INSERT INTO `categories` VALUES (1459, 'fr_FR', 'Théâtre', '', '', '', ' theatre ');
INSERT INTO `categories` VALUES (1460, 'fr_FR', 'Théâtre', '', '', '', ' theatre ');
INSERT INTO `categories` VALUES (1461, 'fr_FR', 'Théâtre', '', '', '', ' theatre ');
INSERT INTO `categories` VALUES (1462, 'fr_FR', 'Théâtre', '', '', '', ' theatre ');
INSERT INTO `categories` VALUES (1463, 'fr_FR', 'Roman et nouvelle', '', '', '', ' roman nouvelle ');
INSERT INTO `categories` VALUES (1464, 'fr_FR', 'Théâtre', '', '', '', ' theatre ');
INSERT INTO `categories` VALUES (1465, 'fr_FR', 'Théâtre', '', '', '', ' theatre ');
INSERT INTO `categories` VALUES (1466, 'fr_FR', 'Littérature allemande', '', '', '', ' litterature allemande ');
INSERT INTO `categories` VALUES (1467, 'fr_FR', 'Critique littéraire - Biographies', '', '', '', ' critique litteraire biographies ');
INSERT INTO `categories` VALUES (1468, 'fr_FR', 'Critique littéraire - Biographies', '', '', '', ' critique litteraire biographies ');
INSERT INTO `categories` VALUES (1469, 'fr_FR', 'Critique littéraire - Biographies', '', '', '', ' critique litteraire biographies ');
INSERT INTO `categories` VALUES (1470, 'fr_FR', 'Critique littéraire - Biographies', '', '', '', ' critique litteraire biographies ');
INSERT INTO `categories` VALUES (1471, 'fr_FR', 'Critique littéraire - Biographies', '', '', '', ' critique litteraire biographies ');
INSERT INTO `categories` VALUES (1472, 'fr_FR', 'Critique littéraire - Biographies', '', '', '', ' critique litteraire biographies ');
INSERT INTO `categories` VALUES (1473, 'fr_FR', 'Critique littéraire - Biographies', '', '', '', ' critique litteraire biographies ');
INSERT INTO `categories` VALUES (1474, 'fr_FR', 'Critique littéraire - Biographies', '', '', '', ' critique litteraire biographies ');
INSERT INTO `categories` VALUES (1475, 'fr_FR', 'Critique littéraire - Biographies', '', '', '', ' critique litteraire biographies ');
INSERT INTO `categories` VALUES (1476, 'fr_FR', 'Roman et nouvelle', '', '', '', ' roman nouvelle ');
INSERT INTO `categories` VALUES (1477, 'fr_FR', 'Roman et nouvelle', '', '', '', ' roman nouvelle ');
INSERT INTO `categories` VALUES (1478, 'fr_FR', 'Roman et nouvelle', '', '', '', ' roman nouvelle ');
INSERT INTO `categories` VALUES (1479, 'fr_FR', 'Roman et nouvelle', '', '', '', ' roman nouvelle ');
INSERT INTO `categories` VALUES (1480, 'fr_FR', 'Roman et nouvelle', '', '', '', ' roman nouvelle ');
INSERT INTO `categories` VALUES (1481, 'fr_FR', 'Roman et nouvelle', '', '', '', ' roman nouvelle ');
INSERT INTO `categories` VALUES (1482, 'fr_FR', 'Roman et nouvelle', '', '', '', ' roman nouvelle ');
INSERT INTO `categories` VALUES (1483, 'fr_FR', 'Roman et nouvelle', '', '', '', ' roman nouvelle ');
INSERT INTO `categories` VALUES (1484, 'fr_FR', 'Antiquité', '', '', '', ' antiquite ');
INSERT INTO `categories` VALUES (1485, 'fr_FR', 'Moyen-Age', '', '', '', ' moyen age ');
INSERT INTO `categories` VALUES (1486, 'fr_FR', 'Renaissance', '', '', '', ' renaissance ');
INSERT INTO `categories` VALUES (1487, 'fr_FR', 'XVIIème siècle', '', '', '', ' xviieme siecle ');
INSERT INTO `categories` VALUES (1488, 'fr_FR', 'XVIIIème siècle', '', '', '', ' xviiieme siecle ');
INSERT INTO `categories` VALUES (1489, 'fr_FR', 'XIXème siècle', '', '', '', ' xixeme siecle ');
INSERT INTO `categories` VALUES (1490, 'fr_FR', 'XXème siècle', '', '', '', ' xxeme siecle ');
INSERT INTO `categories` VALUES (1491, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (1492, 'fr_FR', 'Par sujet', '', '', '', ' par sujet ');
INSERT INTO `categories` VALUES (1493, 'fr_FR', 'Biographies', '', '', '', ' biographies ');
INSERT INTO `categories` VALUES (1494, 'fr_FR', 'Essais politiques', '', '', '', ' essais politiques ');
INSERT INTO `categories` VALUES (1495, 'fr_FR', 'France', '', '', '', ' france ');
INSERT INTO `categories` VALUES (1496, 'fr_FR', 'Europe', '', '', '', ' europe ');
INSERT INTO `categories` VALUES (1497, 'fr_FR', 'Monde', '', '', '', ' monde ');
INSERT INTO `categories` VALUES (1498, 'fr_FR', 'Science politique', '', '', '', ' science politique ');
INSERT INTO `categories` VALUES (1499, 'fr_FR', 'Administration', '', '', '', ' administration ');
INSERT INTO `categories` VALUES (1500, 'fr_FR', 'Citoyenneté', '', '', '', ' citoyennete ');
INSERT INTO `categories` VALUES (1501, 'fr_FR', 'Président et gouvernement', '', '', '', ' president gouvernement ');
INSERT INTO `categories` VALUES (1502, 'fr_FR', 'Parlement', '', '', '', ' parlement ');
INSERT INTO `categories` VALUES (1503, 'fr_FR', 'Justice', '', '', '', ' justice ');
INSERT INTO `categories` VALUES (1504, 'fr_FR', 'Collectivités territoriales', '', '', '', ' collectivites territoriales ');
INSERT INTO `categories` VALUES (1505, 'fr_FR', 'Défense nationale - Sécurité publique', '', '', '', ' defense nationale securite publique ');
INSERT INTO `categories` VALUES (1506, 'fr_FR', 'Droit', '', '', '', ' droit ');
INSERT INTO `categories` VALUES (1507, 'fr_FR', 'Elections', '', '', '', ' elections ');
INSERT INTO `categories` VALUES (1508, 'fr_FR', 'Fiscalité', '', '', '', ' fiscalite ');
INSERT INTO `categories` VALUES (1509, 'fr_FR', 'Partis politiques', '', '', '', ' partis politiques ');
INSERT INTO `categories` VALUES (1510, 'fr_FR', 'Protection sociale', '', '', '', ' protection sociale ');
INSERT INTO `categories` VALUES (1511, 'fr_FR', 'Syndicats', '', '', '', ' syndicats ');
INSERT INTO `categories` VALUES (1512, 'fr_FR', 'Mondialisation', '', '', '', ' mondialisation ');
INSERT INTO `categories` VALUES (1513, 'fr_FR', 'Organisations internationales', '', '', '', ' organisations internationales ');
INSERT INTO `categories` VALUES (1514, 'fr_FR', 'Relations internationales', '', '', '', ' relations internationales ');
INSERT INTO `categories` VALUES (1515, 'fr_FR', 'Animaux', '', '', '', ' animaux ');
INSERT INTO `categories` VALUES (1516, 'fr_FR', 'Corps et santé', '', '', '', ' corps sante ');
INSERT INTO `categories` VALUES (1517, 'fr_FR', 'Découvrir le monde', '', '', '', ' decouvrir monde ');
INSERT INTO `categories` VALUES (1518, 'fr_FR', 'Eveil et apprentissage', '', '', '', ' eveil apprentissage ');
INSERT INTO `categories` VALUES (1519, 'fr_FR', 'Sports et loisirs', '', '', '', ' sports loisirs ');
INSERT INTO `categories` VALUES (1520, 'fr_FR', 'Nature et environnement', '', '', '', ' nature environnement ');
INSERT INTO `categories` VALUES (1521, 'fr_FR', 'Personnages extraordinaires et mondes imaginaires', '', '', '', ' personnages extraordinaires mondes imaginaires ');
INSERT INTO `categories` VALUES (1522, 'fr_FR', 'Art et culture', '', '', '', ' art culture ');
INSERT INTO `categories` VALUES (1523, 'fr_FR', 'Vivre ensemble', '', '', '', ' vivre ensemble ');
INSERT INTO `categories` VALUES (1524, 'fr_FR', 'Chat', '', '', '', ' chat ');
INSERT INTO `categories` VALUES (1525, 'fr_FR', 'Mammifères', '', '', '', ' mammiferes ');
INSERT INTO `categories` VALUES (1526, 'fr_FR', 'Par environnement', '', '', '', ' par environnement ');
INSERT INTO `categories` VALUES (1527, 'fr_FR', 'Champs', '', '', '', ' champs ');
INSERT INTO `categories` VALUES (1528, 'fr_FR', 'Forêt', '', '', '', ' foret ');
INSERT INTO `categories` VALUES (1529, 'fr_FR', 'Montagne', '', '', '', ' montagne ');
INSERT INTO `categories` VALUES (1530, 'fr_FR', 'Jungle', '', '', '', ' jungle ');
INSERT INTO `categories` VALUES (1531, 'fr_FR', 'Poissons', '', '', '', ' poissons ');
INSERT INTO `categories` VALUES (1532, 'fr_FR', 'Insectes', '', '', '', ' insectes ');
INSERT INTO `categories` VALUES (1533, 'fr_FR', 'Oiseaux', '', '', '', ' oiseaux ');
INSERT INTO `categories` VALUES (1534, 'fr_FR', 'Animaux menacés', '', '', '', ' animaux menaces ');
INSERT INTO `categories` VALUES (1535, 'fr_FR', 'Dinosaures', '', '', '', ' dinosaures ');
INSERT INTO `categories` VALUES (1536, 'fr_FR', 'Corps', '', '', '', ' corps ');
INSERT INTO `categories` VALUES (1537, 'fr_FR', 'Hygiène et santé', '', '', '', ' hygiene sante ');
INSERT INTO `categories` VALUES (1538, 'fr_FR', 'Nourriture', '', '', '', ' nourriture ');
INSERT INTO `categories` VALUES (1539, 'fr_FR', 'Vêtement et coiffure', '', '', '', ' vetement coiffure ');
INSERT INTO `categories` VALUES (1540, 'fr_FR', 'Avoir un bébé', '', '', '', ' avoir bebe ');
INSERT INTO `categories` VALUES (1541, 'fr_FR', 'Maladie', '', '', '', ' maladie ');
INSERT INTO `categories` VALUES (1542, 'fr_FR', 'Handicap', '', '', '', ' handicap ');
INSERT INTO `categories` VALUES (1543, 'fr_FR', 'Médecin et hôpital', '', '', '', ' medecin hopital ');
INSERT INTO `categories` VALUES (1544, 'fr_FR', 'Sexualité', '', '', '', ' sexualite ');
INSERT INTO `categories` VALUES (1545, 'fr_FR', 'Voyage', '', '', '', ' voyage ');
INSERT INTO `categories` VALUES (1546, 'fr_FR', 'Transports', '', '', '', ' transports ');
INSERT INTO `categories` VALUES (1547, 'fr_FR', 'Communiquer - S''informer', '', '', '', ' communiquer s informer ');
INSERT INTO `categories` VALUES (1548, 'fr_FR', 'Géographie', '', '', '', ' geographie ');
INSERT INTO `categories` VALUES (1549, 'fr_FR', 'Préhistoire', '', '', '', ' prehistoire ');
INSERT INTO `categories` VALUES (1550, 'fr_FR', 'Amérique', '', '', '', ' amerique ');
INSERT INTO `categories` VALUES (1551, 'fr_FR', 'Histoire', '', '', '', ' histoire ');
INSERT INTO `categories` VALUES (1552, 'fr_FR', 'Science et expériences', '', '', '', ' science experiences ');
INSERT INTO `categories` VALUES (1553, 'fr_FR', 'Espace - Planètes', '', '', '', ' espace planetes ');
INSERT INTO `categories` VALUES (1554, 'fr_FR', 'Premiers acquis', '', '', '', ' premiers acquis ');
INSERT INTO `categories` VALUES (1555, 'fr_FR', 'Autour du langage', '', '', '', ' autour langage ');
INSERT INTO `categories` VALUES (1556, 'fr_FR', 'Vers l''autonomie', '', '', '', ' vers autonomie ');
INSERT INTO `categories` VALUES (1557, 'fr_FR', 'Langues étrangères', '', '', '', ' langues etrangeres ');
INSERT INTO `categories` VALUES (1558, 'fr_FR', 'Informatique', '', '', '', ' informatique ');
INSERT INTO `categories` VALUES (1559, 'fr_FR', 'Activités manuelles', '', '', '', ' activites manuelles ');
INSERT INTO `categories` VALUES (1560, 'fr_FR', 'Cuisine', '', '', '', ' cuisine ');
INSERT INTO `categories` VALUES (1561, 'fr_FR', 'Jardinage', '', '', '', ' jardinage ');
INSERT INTO `categories` VALUES (1562, 'fr_FR', 'Sport', '', '', '', ' sport ');
INSERT INTO `categories` VALUES (1563, 'fr_FR', 'Jeux et jouets', '', '', '', ' jeux jouets ');
INSERT INTO `categories` VALUES (1564, 'fr_FR', 'Déguisements', '', '', '', ' deguisements ');
INSERT INTO `categories` VALUES (1565, 'fr_FR', 'Dessin et peinture', '', '', '', ' dessin peinture ');
INSERT INTO `categories` VALUES (1566, 'fr_FR', 'Enigmes et devinettes', '', '', '', ' enigmes devinettes ');
INSERT INTO `categories` VALUES (1567, 'fr_FR', 'Adjectif', '', '', '', ' adjectif ');
INSERT INTO `categories` VALUES (1568, 'fr_FR', 'Campagne', '', '', '', ' campagne ');
INSERT INTO `categories` VALUES (1569, 'fr_FR', 'Montagne', '', '', '', ' montagne ');
INSERT INTO `categories` VALUES (1570, 'fr_FR', 'Forêt', '', '', '', ' foret ');
INSERT INTO `categories` VALUES (1571, 'fr_FR', 'Mer et océan', '', '', '', ' mer ocean ');
INSERT INTO `categories` VALUES (1572, 'fr_FR', 'Désert', '', '', '', ' desert ');
INSERT INTO `categories` VALUES (1573, 'fr_FR', 'Ville', '', '', '', ' ville ');
INSERT INTO `categories` VALUES (1574, 'fr_FR', 'Air', '', '', '', ' air ');
INSERT INTO `categories` VALUES (1575, 'fr_FR', 'Eau', '', '', '', ' eau ');
INSERT INTO `categories` VALUES (1576, 'fr_FR', 'Feu', '', '', '', ' feu ');
INSERT INTO `categories` VALUES (1577, 'fr_FR', 'Saisons', '', '', '', ' saisons ');
INSERT INTO `categories` VALUES (1578, 'fr_FR', 'Arbres', '', '', '', ' arbres ');
INSERT INTO `categories` VALUES (1579, 'fr_FR', 'Fleurs', '', '', '', ' fleurs ');
INSERT INTO `categories` VALUES (1580, 'fr_FR', 'Météo', '', '', '', ' meteo ');
INSERT INTO `categories` VALUES (1581, 'fr_FR', 'Protéger l''environnement', '', '', '', ' proteger environnement ');
INSERT INTO `categories` VALUES (1582, 'fr_FR', 'Fée', '', '', '', ' fee ');
INSERT INTO `categories` VALUES (1583, 'fr_FR', 'Sorcière', '', '', '', ' sorciere ');
INSERT INTO `categories` VALUES (1584, 'fr_FR', 'Monstre et dragon', '', '', '', ' monstre dragon ');
INSERT INTO `categories` VALUES (1585, 'fr_FR', 'Prince, princesse, roi et reine', '', '', '', ' prince princesse roi reine ');
INSERT INTO `categories` VALUES (1586, 'fr_FR', 'Ogre', '', '', '', ' ogre ');
INSERT INTO `categories` VALUES (1587, 'fr_FR', 'Nain et géant', '', '', '', ' nain geant ');
INSERT INTO `categories` VALUES (1588, 'fr_FR', 'Fantôme', '', '', '', ' fantome ');
INSERT INTO `categories` VALUES (1589, 'fr_FR', 'Pirate et corsaire', '', '', '', ' pirate corsaire ');
INSERT INTO `categories` VALUES (1590, 'fr_FR', 'Extra-terrestre', '', '', '', ' extra terrestre ');
INSERT INTO `categories` VALUES (1591, 'fr_FR', 'Monde imaginaire', '', '', '', ' monde imaginaire ');
INSERT INTO `categories` VALUES (1592, 'fr_FR', 'Peinture et sculpture', '', '', '', ' peinture sculpture ');
INSERT INTO `categories` VALUES (1593, 'fr_FR', 'Roman', '', '', '', ' roman ');
INSERT INTO `categories` VALUES (1594, 'fr_FR', 'Théâtre', '', '', '', ' theatre ');
INSERT INTO `categories` VALUES (1595, 'fr_FR', 'Poésie', '', '', '', ' poesie ');
INSERT INTO `categories` VALUES (1596, 'fr_FR', 'Livre', '', '', '', ' livre ');
INSERT INTO `categories` VALUES (1597, 'fr_FR', 'Musique', '', '', '', ' musique ');
INSERT INTO `categories` VALUES (1598, 'fr_FR', 'Danse', '', '', '', ' danse ');
INSERT INTO `categories` VALUES (1599, 'fr_FR', 'Bande dessinée', '', '', '', ' bande dessinee ');
INSERT INTO `categories` VALUES (1600, 'fr_FR', 'Cinéma - Télévision', '', '', '', ' cinema television ');
INSERT INTO `categories` VALUES (1601, 'fr_FR', 'Photographie', '', '', '', ' photographie ');
INSERT INTO `categories` VALUES (1602, 'fr_FR', 'Architecture', '', '', '', ' architecture ');
INSERT INTO `categories` VALUES (1603, 'fr_FR', 'En famille', '', '', '', ' famille ');
INSERT INTO `categories` VALUES (1604, 'fr_FR', 'A l''école et au collège', '', '', '', ' ecole college ');
INSERT INTO `categories` VALUES (1605, 'fr_FR', 'En société', '', '', '', ' societe ');
INSERT INTO `categories` VALUES (1606, 'fr_FR', 'Amour et sentiments', '', '', '', ' amour sentiments ');
INSERT INTO `categories` VALUES (1607, 'fr_FR', 'Enfants', '', '', '', ' enfants ');
INSERT INTO `categories` VALUES (1608, 'fr_FR', 'Fêtes', '', '', '', ' fetes ');
INSERT INTO `categories` VALUES (1609, 'fr_FR', 'Métiers', '', '', '', ' metiers ');
INSERT INTO `categories` VALUES (1610, 'fr_FR', 'Religion', '', '', '', ' religion ');
INSERT INTO `categories` VALUES (1611, 'fr_FR', 'C''est compliqué', '', '', '', ' c est complique ');
INSERT INTO `categories` VALUES (1612, 'fr_FR', 'Astronomie', '', '', '', ' astronomie ');
INSERT INTO `categories` VALUES (1613, 'fr_FR', 'Plantes', '', '', '', ' plantes ');
INSERT INTO `categories` VALUES (1614, 'fr_FR', 'Chimie', '', '', '', ' chimie ');
INSERT INTO `categories` VALUES (1615, 'fr_FR', 'Ecologie', '', '', '', ' ecologie ');
INSERT INTO `categories` VALUES (1616, 'fr_FR', 'Génétique et évolution', '', '', '', ' genetique evolution ');
INSERT INTO `categories` VALUES (1617, 'fr_FR', 'Mathématiques', '', '', '', ' mathematiques ');
INSERT INTO `categories` VALUES (1618, 'fr_FR', 'Physique', '', '', '', ' physique ');
INSERT INTO `categories` VALUES (1619, 'fr_FR', 'Préhistoire', '', '', '', ' prehistoire ');
INSERT INTO `categories` VALUES (1620, 'fr_FR', 'Terre', '', '', '', ' terre ');
INSERT INTO `categories` VALUES (1621, 'fr_FR', 'Animaux', '', '', '', ' animaux ');
INSERT INTO `categories` VALUES (1622, 'fr_FR', 'Structure', '', '', '', ' structure ');
INSERT INTO `categories` VALUES (1623, 'fr_FR', 'Climatologie', '', '', '', ' climatologie ');
INSERT INTO `categories` VALUES (1624, 'fr_FR', 'Météorologie', '', '', '', ' meteorologie ');
INSERT INTO `categories` VALUES (1625, 'fr_FR', 'Océanographie - Hydrologie', '', '', '', ' oceanographie hydrologie ');
INSERT INTO `categories` VALUES (1626, 'fr_FR', 'Continents et relief', '', '', '', ' continents relief ');
INSERT INTO `categories` VALUES (1627, 'fr_FR', 'Roches et minéraux', '', '', '', ' roches mineraux ');
INSERT INTO `categories` VALUES (1628, 'fr_FR', 'Volcans', '', '', '', ' volcans ');
INSERT INTO `categories` VALUES (1629, 'fr_FR', 'Micro-organismes', '', '', '', ' micro organismes ');
INSERT INTO `categories` VALUES (1630, 'fr_FR', 'Invertébrés', '', '', '', ' invertebres ');
INSERT INTO `categories` VALUES (1631, 'fr_FR', 'Mollusques', '', '', '', ' mollusques ');
INSERT INTO `categories` VALUES (1632, 'fr_FR', 'Crustacés', '', '', '', ' crustaces ');
INSERT INTO `categories` VALUES (1633, 'fr_FR', 'Insectes', '', '', '', ' insectes ');
INSERT INTO `categories` VALUES (1634, 'fr_FR', 'Arachnides', '', '', '', ' arachnides ');
INSERT INTO `categories` VALUES (1635, 'fr_FR', 'Poissons', '', '', '', ' poissons ');
INSERT INTO `categories` VALUES (1636, 'fr_FR', 'Batraciens', '', '', '', ' batraciens ');
INSERT INTO `categories` VALUES (1637, 'fr_FR', 'Reptiles', '', '', '', ' reptiles ');
INSERT INTO `categories` VALUES (1638, 'fr_FR', 'Oiseaux', '', '', '', ' oiseaux ');
INSERT INTO `categories` VALUES (1639, 'fr_FR', 'Mammifères', '', '', '', ' mammiferes ');
INSERT INTO `categories` VALUES (1640, 'fr_FR', 'Marsupiaux', '', '', '', ' marsupiaux ');
INSERT INTO `categories` VALUES (1641, 'fr_FR', 'Chat', '', '', '', ' chat ');
INSERT INTO `categories` VALUES (1642, 'fr_FR', 'Primates', '', '', '', ' primates ');
INSERT INTO `categories` VALUES (1643, 'fr_FR', 'Chien', '', '', '', ' chien ');
INSERT INTO `categories` VALUES (1644, 'fr_FR', 'Félins', '', '', '', ' felins ');
INSERT INTO `categories` VALUES (1645, 'fr_FR', 'Rongeurs', '', '', '', ' rongeurs ');
INSERT INTO `categories` VALUES (1646, 'fr_FR', 'Archéologie', '', '', '', ' archeologie ');
INSERT INTO `categories` VALUES (1647, 'fr_FR', 'Communication et Information', '', '', '', ' communication information ');
INSERT INTO `categories` VALUES (1648, 'fr_FR', 'Economie', '', '', '', ' economie ');
INSERT INTO `categories` VALUES (1649, 'fr_FR', 'Démographie', '', '', '', ' demographie ');
INSERT INTO `categories` VALUES (1650, 'fr_FR', 'Géographie', '', '', '', ' geographie ');
INSERT INTO `categories` VALUES (1651, 'fr_FR', 'Histoire', '', '', '', ' histoire ');
INSERT INTO `categories` VALUES (1652, 'fr_FR', 'Langues - Linguistique', '', '', '', ' langues linguistique ');
INSERT INTO `categories` VALUES (1653, 'fr_FR', 'Médecine', '', '', '', ' medecine ');
INSERT INTO `categories` VALUES (1654, 'fr_FR', 'Philosophie', '', '', '', ' philosophie ');
INSERT INTO `categories` VALUES (1655, 'fr_FR', 'Psychologie - Psychanalyse', '', '', '', ' psychologie psychanalyse ');
INSERT INTO `categories` VALUES (1656, 'fr_FR', 'Sociologie', '', '', '', ' sociologie ');
INSERT INTO `categories` VALUES (1657, 'fr_FR', 'Ethnologie', '', '', '', ' ethnologie ');
INSERT INTO `categories` VALUES (1658, 'fr_FR', 'Bibliothéconomie', '', '', '', ' bibliotheconomie ');
INSERT INTO `categories` VALUES (1659, 'fr_FR', 'Société de l''information', '', '', '', ' societe information ');
INSERT INTO `categories` VALUES (1660, 'fr_FR', 'Journalisme', '', '', '', ' journalisme ');
INSERT INTO `categories` VALUES (1661, 'fr_FR', 'Edition', '', '', '', ' edition ');
INSERT INTO `categories` VALUES (1662, 'fr_FR', 'Communication', '', '', '', ' communication ');
INSERT INTO `categories` VALUES (1663, 'fr_FR', 'Antiquité et mondes anciens', '', '', '', ' antiquite mondes anciens ');
INSERT INTO `categories` VALUES (1664, 'fr_FR', 'Moyen-Age', '', '', '', ' moyen age ');
INSERT INTO `categories` VALUES (1665, 'fr_FR', 'Renaissance', '', '', '', ' renaissance ');
INSERT INTO `categories` VALUES (1666, 'fr_FR', 'XVIIème siècle (De 1600 à 1699)', '', '', '', ' xviieme siecle 1600 1699 ');
INSERT INTO `categories` VALUES (1667, 'fr_FR', 'XVIIIème siècle (De 1700 à 1799)', '', '', '', ' xviiieme siecle 1700 1799 ');
INSERT INTO `categories` VALUES (1668, 'fr_FR', 'XIXème siècle (De 1800 à 1899)', '', '', '', ' xixeme siecle 1800 1899 ');
INSERT INTO `categories` VALUES (1669, 'fr_FR', 'De 1900 à 1914', '', '', '', ' 1900 1914 ');
INSERT INTO `categories` VALUES (1670, 'fr_FR', 'Première Guerre Mondiale (1914-1918)', '', '', '', ' premiere guerre mondiale 1914 1918 ');
INSERT INTO `categories` VALUES (1671, 'fr_FR', 'Entre-deux guerres (1919-1938)', '', '', '', ' entre deux guerres 1919 1938 ');
INSERT INTO `categories` VALUES (1672, 'fr_FR', 'Deuxième Guerre Mondiale (1939-1945)', '', '', '', ' deuxieme guerre mondiale 1939 1945 ');
INSERT INTO `categories` VALUES (1673, 'fr_FR', 'De 1946 à 1999', '', '', '', ' 1946 1999 ');
INSERT INTO `categories` VALUES (1674, 'fr_FR', 'Depuis 2000', '', '', '', ' depuis 2000 ');
INSERT INTO `categories` VALUES (1675, 'fr_FR', 'Métaphysique', '', '', '', ' metaphysique ');
INSERT INTO `categories` VALUES (1676, 'fr_FR', 'Epistémologie', '', '', '', ' epistemologie ');
INSERT INTO `categories` VALUES (1677, 'fr_FR', 'Philosophie antique', '', '', '', ' philosophie antique ');
INSERT INTO `categories` VALUES (1678, 'fr_FR', 'Philosophie médiévale', '', '', '', ' philosophie medievale ');
INSERT INTO `categories` VALUES (1679, 'fr_FR', 'Philosophie orientale', '', '', '', ' philosophie orientale ');
INSERT INTO `categories` VALUES (1680, 'fr_FR', 'Philosophie occidentale moderne', '', '', '', ' philosophie occidentale moderne ');
INSERT INTO `categories` VALUES (1681, 'fr_FR', 'Amour', '', '', '', ' amour ');
INSERT INTO `categories` VALUES (1682, 'fr_FR', 'De l''enfant à l''âge adulte', '', '', '', ' enfant age adulte ');
INSERT INTO `categories` VALUES (1683, 'fr_FR', 'Problèmes et débats de société', '', '', '', ' problemes debats societe ');
INSERT INTO `categories` VALUES (1684, 'fr_FR', 'Vivre ensemble', '', '', '', ' vivre ensemble ');
INSERT INTO `categories` VALUES (1685, 'fr_FR', 'Criminalité', '', '', '', ' criminalite ');
INSERT INTO `categories` VALUES (1686, 'fr_FR', 'Emploi', '', '', '', ' emploi ');
INSERT INTO `categories` VALUES (1687, 'fr_FR', 'Enseignement et Formation', '', '', '', ' enseignement formation ');
INSERT INTO `categories` VALUES (1688, 'fr_FR', 'Couple', '', '', '', ' couple ');
INSERT INTO `categories` VALUES (1689, 'fr_FR', 'Famille', '', '', '', ' famille ');
INSERT INTO `categories` VALUES (1690, 'fr_FR', 'Environnement', '', '', '', ' environnement ');
INSERT INTO `categories` VALUES (1691, 'fr_FR', 'Handicap', '', '', '', ' handicap ');
INSERT INTO `categories` VALUES (1692, 'fr_FR', 'Illettrisme', '', '', '', ' illettrisme ');
INSERT INTO `categories` VALUES (1693, 'fr_FR', 'Migrations', '', '', '', ' migrations ');
INSERT INTO `categories` VALUES (1694, 'fr_FR', 'Mode et Costume', '', '', '', ' mode costume ');
INSERT INTO `categories` VALUES (1695, 'fr_FR', 'Urbanisme', '', '', '', ' urbanisme ');
INSERT INTO `categories` VALUES (1696, 'fr_FR', 'Religion', '', '', '', ' religion ');
INSERT INTO `categories` VALUES (1697, 'fr_FR', 'Esotérisme', '', '', '', ' esoterisme ');
INSERT INTO `categories` VALUES (1698, 'fr_FR', 'Sexualité', '', '', '', ' sexualite ');
INSERT INTO `categories` VALUES (1699, 'fr_FR', 'Solidarité et Action sociale', '', '', '', ' solidarite action sociale ');
INSERT INTO `categories` VALUES (1700, 'fr_FR', 'Logement', '', '', '', ' logement ');
INSERT INTO `categories` VALUES (1701, 'fr_FR', 'Vie associative', '', '', '', ' vie associative ');
INSERT INTO `categories` VALUES (1702, 'fr_FR', 'Enfants', '', '', '', ' enfants ');
INSERT INTO `categories` VALUES (1703, 'fr_FR', 'Jeunes', '', '', '', ' jeunes ');
INSERT INTO `categories` VALUES (1704, 'fr_FR', 'Femmes', '', '', '', ' femmes ');
INSERT INTO `categories` VALUES (1705, 'fr_FR', 'Hommes', '', '', '', ' hommes ');
INSERT INTO `categories` VALUES (1706, 'fr_FR', 'Troisième âge', '', '', '', ' troisieme age ');
INSERT INTO `categories` VALUES (1707, 'fr_FR', 'Conseil et Orientation', '', '', '', ' conseil orientation ');
INSERT INTO `categories` VALUES (1708, 'fr_FR', 'Enseignement primaire', '', '', '', ' enseignement primaire ');
INSERT INTO `categories` VALUES (1709, 'fr_FR', 'Enseignement secondaire', '', '', '', ' enseignement secondaire ');
INSERT INTO `categories` VALUES (1710, 'fr_FR', 'Enseignement supérieur', '', '', '', ' enseignement superieur ');
INSERT INTO `categories` VALUES (1711, 'fr_FR', 'Examens et Concours', '', '', '', ' examens concours ');
INSERT INTO `categories` VALUES (1712, 'fr_FR', 'Pédagogie', '', '', '', ' pedagogie ');
INSERT INTO `categories` VALUES (1713, 'fr_FR', 'Problèmes et Débats', '', '', '', ' problemes debats ');
INSERT INTO `categories` VALUES (1714, 'fr_FR', 'Drogue', '', '', '', ' drogue ');
INSERT INTO `categories` VALUES (1715, 'fr_FR', 'Bible', '', '', '', ' bible ');
INSERT INTO `categories` VALUES (1716, 'fr_FR', 'Christianisme', '', '', '', ' christianisme ');
INSERT INTO `categories` VALUES (1717, 'fr_FR', 'Sectes', '', '', '', ' sectes ');
INSERT INTO `categories` VALUES (1718, 'fr_FR', 'Bouddhisme', '', '', '', ' bouddhisme ');
INSERT INTO `categories` VALUES (1719, 'fr_FR', 'Hindouisme', '', '', '', ' hindouisme ');
INSERT INTO `categories` VALUES (1720, 'fr_FR', 'Judaïsme', '', '', '', ' judaisme ');
INSERT INTO `categories` VALUES (1721, 'fr_FR', 'Islam', '', '', '', ' islam ');
INSERT INTO `categories` VALUES (1722, 'fr_FR', 'Autres mouvements religieux', '', '', '', ' autres mouvements religieux ');
INSERT INTO `categories` VALUES (1723, 'fr_FR', 'Artisanat', '', '', '', ' artisanat ');
INSERT INTO `categories` VALUES (1724, 'fr_FR', 'Arts plastiques', '', '', '', ' arts plastiques ');
INSERT INTO `categories` VALUES (1725, 'fr_FR', 'Chasse et pêche', '', '', '', ' chasse peche ');
INSERT INTO `categories` VALUES (1726, 'fr_FR', 'Collections', '', '', '', ' collections ');
INSERT INTO `categories` VALUES (1727, 'fr_FR', 'Jardin', '', '', '', ' jardin ');
INSERT INTO `categories` VALUES (1728, 'fr_FR', 'Généalogie', '', '', '', ' genealogie ');
INSERT INTO `categories` VALUES (1729, 'fr_FR', 'Jeux et Jouets', '', '', '', ' jeux jouets ');
INSERT INTO `categories` VALUES (1730, 'fr_FR', 'Photographie', '', '', '', ' photographie ');
INSERT INTO `categories` VALUES (1731, 'fr_FR', 'Sports', '', '', '', ' sports ');
INSERT INTO `categories` VALUES (1733, 'fr_FR', 'Véhicules', '', '', '', ' vehicules ');
INSERT INTO `categories` VALUES (1734, 'fr_FR', 'Régions', '', '', '', ' regions ');
INSERT INTO `categories` VALUES (1735, 'fr_FR', 'Alsace', '', '', '', ' alsace ');
INSERT INTO `categories` VALUES (1736, 'fr_FR', 'Aquitaine', '', '', '', ' aquitaine ');
INSERT INTO `categories` VALUES (1737, 'fr_FR', 'Auvergne', '', '', '', ' auvergne ');
INSERT INTO `categories` VALUES (1738, 'fr_FR', 'Bourgogne', '', '', '', ' bourgogne ');
INSERT INTO `categories` VALUES (1739, 'fr_FR', 'Bretagne', '', '', '', ' bretagne ');
INSERT INTO `categories` VALUES (1740, 'fr_FR', 'Centre', '', '', '', ' centre ');
INSERT INTO `categories` VALUES (1741, 'fr_FR', 'Champagne-Ardenne', '', '', '', ' champagne ardenne ');
INSERT INTO `categories` VALUES (1742, 'fr_FR', 'Corse', '', '', '', ' corse ');
INSERT INTO `categories` VALUES (1743, 'fr_FR', 'Franche-Comté', '', '', '', ' franche comte ');
INSERT INTO `categories` VALUES (1744, 'fr_FR', 'Ile-de-France', '', '', '', ' ile france ');
INSERT INTO `categories` VALUES (1745, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (1746, 'fr_FR', 'Languedoc-Roussillon', '', '', '', ' languedoc roussillon ');
INSERT INTO `categories` VALUES (1747, 'fr_FR', 'Limousin', '', '', '', ' limousin ');
INSERT INTO `categories` VALUES (1748, 'fr_FR', 'Pays de la Loire', '', '', '', ' pays loire ');
INSERT INTO `categories` VALUES (1749, 'fr_FR', 'Lorraine', '', '', '', ' lorraine ');
INSERT INTO `categories` VALUES (1750, 'fr_FR', 'Midi-Pyrénées', '', '', '', ' midi pyrenees ');
INSERT INTO `categories` VALUES (1751, 'fr_FR', 'Nord-Pas-de-Calais', '', '', '', ' nord pas calais ');
INSERT INTO `categories` VALUES (1752, 'fr_FR', 'Normandie (Haute-)', '', '', '', ' normandie haute ');
INSERT INTO `categories` VALUES (1753, 'fr_FR', 'Normandie (Basse-)', '', '', '', ' normandie basse ');
INSERT INTO `categories` VALUES (1754, 'fr_FR', 'Picardie', '', '', '', ' picardie ');
INSERT INTO `categories` VALUES (1755, 'fr_FR', 'Poitou-Charentes', '', '', '', ' poitou charentes ');
INSERT INTO `categories` VALUES (1756, 'fr_FR', 'Provence-Alpes-Côte d''Azur', '', '', '', ' provence alpes cote azur ');
INSERT INTO `categories` VALUES (1757, 'fr_FR', 'Rhône-Alpes', '', '', '', ' rhone alpes ');
INSERT INTO `categories` VALUES (1758, 'fr_FR', 'Agriculture - Elevage', '', '', '', ' agriculture elevage ');
INSERT INTO `categories` VALUES (1759, 'fr_FR', 'Agroalimentaire', '', '', '', ' agroalimentaire ');
INSERT INTO `categories` VALUES (1760, 'fr_FR', 'Aviation - Aéronautique', '', '', '', ' aviation aeronautique ');
INSERT INTO `categories` VALUES (1761, 'fr_FR', 'Bâtiment et Travaux publics', '', '', '', ' batiment travaux publics ');
INSERT INTO `categories` VALUES (1762, 'fr_FR', 'Energie', '', '', '', ' energie ');
INSERT INTO `categories` VALUES (1763, 'fr_FR', 'Espace - Astronautique', '', '', '', ' espace astronautique ');
INSERT INTO `categories` VALUES (1764, 'fr_FR', 'Industries diverses', '', '', '', ' industries diverses ');
INSERT INTO `categories` VALUES (1765, 'fr_FR', 'Informatique', '', '', '', ' informatique ');
INSERT INTO `categories` VALUES (1766, 'fr_FR', 'Navigation', '', '', '', ' navigation ');
INSERT INTO `categories` VALUES (1767, 'fr_FR', 'Inventions', '', '', '', ' inventions ');
INSERT INTO `categories` VALUES (1768, 'fr_FR', 'Robots - Vie artificielle', '', '', '', ' robots vie artificielle ');
INSERT INTO `categories` VALUES (1769, 'fr_FR', 'Télécommunications', '', '', '', ' telecommunications ');
INSERT INTO `categories` VALUES (1770, 'fr_FR', 'Transports', '', '', '', ' transports ');
INSERT INTO `categories` VALUES (1771, 'fr_FR', 'Commerce', '', '', '', ' commerce ');
INSERT INTO `categories` VALUES (1772, 'fr_FR', 'Gestion de l''entreprise', '', '', '', ' gestion entreprise ');
INSERT INTO `categories` VALUES (1773, 'fr_FR', 'Programmation', '', '', '', ' programmation ');
INSERT INTO `categories` VALUES (1774, 'fr_FR', 'Bases de données', '', '', '', ' bases donnees ');
INSERT INTO `categories` VALUES (1775, 'fr_FR', 'Logiciels', '', '', '', ' logiciels ');
INSERT INTO `categories` VALUES (1776, 'fr_FR', 'Réseaux', '', '', '', ' reseaux ');
INSERT INTO `categories` VALUES (1777, 'fr_FR', 'Internet', '', '', '', ' internet ');
INSERT INTO `categories` VALUES (1778, 'fr_FR', 'Animaux domestiques', '', '', '', ' animaux domestiques ');
INSERT INTO `categories` VALUES (1779, 'fr_FR', 'Bricolage', '', '', '', ' bricolage ');
INSERT INTO `categories` VALUES (1780, 'fr_FR', 'Cuisine', '', '', '', ' cuisine ');
INSERT INTO `categories` VALUES (1781, 'fr_FR', 'Développement personnel', '', '', '', ' developpement personnel ');
INSERT INTO `categories` VALUES (1782, 'fr_FR', 'Droit pratique', '', '', '', ' droit pratique ');
INSERT INTO `categories` VALUES (1783, 'fr_FR', 'Ecologie pratique', '', '', '', ' ecologie pratique ');
INSERT INTO `categories` VALUES (1784, 'fr_FR', 'Maison et Décoration', '', '', '', ' maison decoration ');
INSERT INTO `categories` VALUES (1785, 'fr_FR', 'Puériculture', '', '', '', ' puericulture ');
INSERT INTO `categories` VALUES (1786, 'fr_FR', 'Parents et Enfants', '', '', '', ' parents enfants ');
INSERT INTO `categories` VALUES (1787, 'fr_FR', 'Santé', '', '', '', ' sante ');
INSERT INTO `categories` VALUES (1788, 'fr_FR', 'Afrique', '', '', '', ' afrique ');
INSERT INTO `categories` VALUES (1789, 'fr_FR', 'Amérique du Nord', '', '', '', ' amerique nord ');
INSERT INTO `categories` VALUES (1790, 'fr_FR', 'Amérique centrale', '', '', '', ' amerique centrale ');
INSERT INTO `categories` VALUES (1791, 'fr_FR', 'Amérique du Sud', '', '', '', ' amerique sud ');
INSERT INTO `categories` VALUES (1792, 'fr_FR', 'Europe', '', '', '', ' europe ');
INSERT INTO `categories` VALUES (1793, 'fr_FR', 'Asie', '', '', '', ' asie ');
INSERT INTO `categories` VALUES (1794, 'fr_FR', 'Océanie', '', '', '', ' oceanie ');
INSERT INTO `categories` VALUES (1795, 'fr_FR', 'Antarctique', '', '', '', ' antarctique ');
INSERT INTO `categories` VALUES (1796, 'fr_FR', 'Arctique', '', '', '', ' arctique ');
INSERT INTO `categories` VALUES (1797, 'fr_FR', 'Afrique du Nord', '', '', '', ' afrique nord ');
INSERT INTO `categories` VALUES (1798, 'fr_FR', 'Afrique de l''Ouest', '', '', '', ' afrique ouest ');
INSERT INTO `categories` VALUES (1799, 'fr_FR', 'Afrique centrale', '', '', '', ' afrique centrale ');
INSERT INTO `categories` VALUES (1800, 'fr_FR', 'Afrique de l''Est', '', '', '', ' afrique est ');
INSERT INTO `categories` VALUES (1801, 'fr_FR', 'Afrique équatoriale', '', '', '', ' afrique equatoriale ');
INSERT INTO `categories` VALUES (1802, 'fr_FR', 'Afrique du Sud', '', '', '', ' afrique sud ');
INSERT INTO `categories` VALUES (1803, 'fr_FR', 'Iles de l''océan indien', '', '', '', ' iles ocean indien ');
INSERT INTO `categories` VALUES (1804, 'fr_FR', 'Alaska', '', '', '', ' alaska ');
INSERT INTO `categories` VALUES (1805, 'fr_FR', 'Canada', '', '', '', ' canada ');
INSERT INTO `categories` VALUES (1806, 'fr_FR', 'Saint-Pierre-et-Miquelon', '', '', '', ' saint pierre miquelon ');
INSERT INTO `categories` VALUES (1807, 'fr_FR', 'Groenland', '', '', '', ' groenland ');
INSERT INTO `categories` VALUES (1808, 'fr_FR', 'Etats-Unis', '', '', '', ' etats unis ');
INSERT INTO `categories` VALUES (1809, 'fr_FR', 'Bermudes', '', '', '', ' bermudes ');
INSERT INTO `categories` VALUES (1810, 'fr_FR', 'Mexique', '', '', '', ' mexique ');
INSERT INTO `categories` VALUES (1811, 'fr_FR', 'Belize', '', '', '', ' belize ');
INSERT INTO `categories` VALUES (1812, 'fr_FR', 'Guatemala', '', '', '', ' guatemala ');
INSERT INTO `categories` VALUES (1813, 'fr_FR', 'Salvador', '', '', '', ' salvador ');
INSERT INTO `categories` VALUES (1814, 'fr_FR', 'Honduras', '', '', '', ' honduras ');
INSERT INTO `categories` VALUES (1815, 'fr_FR', 'Nicaragua', '', '', '', ' nicaragua ');
INSERT INTO `categories` VALUES (1816, 'fr_FR', 'Costa Rica', '', '', '', ' costa rica ');
INSERT INTO `categories` VALUES (1817, 'fr_FR', 'Panama', '', '', '', ' panama ');
INSERT INTO `categories` VALUES (1818, 'fr_FR', 'Antilles', '', '', '', ' antilles ');
INSERT INTO `categories` VALUES (1819, 'fr_FR', 'Colombie', '', '', '', ' colombie ');
INSERT INTO `categories` VALUES (1820, 'fr_FR', 'Vénézuela', '', '', '', ' venezuela ');
INSERT INTO `categories` VALUES (1821, 'fr_FR', 'Guyana', '', '', '', ' guyana ');
INSERT INTO `categories` VALUES (1822, 'fr_FR', 'Surinam', '', '', '', ' surinam ');
INSERT INTO `categories` VALUES (1823, 'fr_FR', 'Guyane française', '', '', '', ' guyane francaise ');
INSERT INTO `categories` VALUES (1824, 'fr_FR', 'Equateur', '', '', '', ' equateur ');
INSERT INTO `categories` VALUES (1825, 'fr_FR', 'Pérou', '', '', '', ' perou ');
INSERT INTO `categories` VALUES (1826, 'fr_FR', 'Brésil', '', '', '', ' bresil ');
INSERT INTO `categories` VALUES (1827, 'fr_FR', 'Bolivie', '', '', '', ' bolivie ');
INSERT INTO `categories` VALUES (1828, 'fr_FR', 'Chili', '', '', '', ' chili ');
INSERT INTO `categories` VALUES (1829, 'fr_FR', 'Paraguay', '', '', '', ' paraguay ');
INSERT INTO `categories` VALUES (1830, 'fr_FR', 'Argentine', '', '', '', ' argentine ');
INSERT INTO `categories` VALUES (1831, 'fr_FR', 'Uruguay', '', '', '', ' uruguay ');
INSERT INTO `categories` VALUES (1832, 'fr_FR', 'Europe du Nord', '', '', '', ' europe nord ');
INSERT INTO `categories` VALUES (1833, 'fr_FR', 'Europe de l''Ouest', '', '', '', ' europe ouest ');
INSERT INTO `categories` VALUES (1834, 'fr_FR', 'Europe centrale', '', '', '', ' europe centrale ');
INSERT INTO `categories` VALUES (1835, 'fr_FR', 'Europe de l''Est', '', '', '', ' europe est ');
INSERT INTO `categories` VALUES (1836, 'fr_FR', 'Europe du Sud', '', '', '', ' europe sud ');
INSERT INTO `categories` VALUES (1837, 'fr_FR', 'Balkans', '', '', '', ' balkans ');
INSERT INTO `categories` VALUES (1838, 'fr_FR', 'Moyen-Orient', '', '', '', ' moyen orient ');
INSERT INTO `categories` VALUES (1839, 'fr_FR', 'Transcaucasie', '', '', '', ' transcaucasie ');
INSERT INTO `categories` VALUES (1840, 'fr_FR', 'Asie centrale', '', '', '', ' asie centrale ');
INSERT INTO `categories` VALUES (1841, 'fr_FR', 'Asie de l''Est', '', '', '', ' asie est ');
INSERT INTO `categories` VALUES (1842, 'fr_FR', 'Asie du Sud', '', '', '', ' asie sud ');
INSERT INTO `categories` VALUES (1843, 'fr_FR', 'Asie du Sud-Est', '', '', '', ' asie sud est ');
INSERT INTO `categories` VALUES (1844, 'fr_FR', 'Australie', '', '', '', ' australie ');
INSERT INTO `categories` VALUES (1845, 'fr_FR', 'Mélanésie', '', '', '', ' melanesie ');
INSERT INTO `categories` VALUES (1846, 'fr_FR', 'Micronésie', '', '', '', ' micronesie ');
INSERT INTO `categories` VALUES (1847, 'fr_FR', 'Polynésie', '', '', '', ' polynesie ');
INSERT INTO `categories` VALUES (1848, 'fr_FR', 'Maroc', '', '', '', ' maroc ');
INSERT INTO `categories` VALUES (1849, 'fr_FR', 'Tunisie', '', '', '', ' tunisie ');
INSERT INTO `categories` VALUES (1850, 'fr_FR', 'Algérie', '', '', '', ' algerie ');
INSERT INTO `categories` VALUES (1851, 'fr_FR', 'Libye', '', '', '', ' libye ');
INSERT INTO `categories` VALUES (1852, 'fr_FR', 'Sierra Leone', '', '', '', ' sierra leone ');
INSERT INTO `categories` VALUES (1853, 'fr_FR', 'Mauritanie', '', '', '', ' mauritanie ');
INSERT INTO `categories` VALUES (1854, 'fr_FR', 'Mali', '', '', '', ' mali ');
INSERT INTO `categories` VALUES (1855, 'fr_FR', 'Sénégal', '', '', '', ' senegal ');
INSERT INTO `categories` VALUES (1856, 'fr_FR', 'Guinée', '', '', '', ' guinee ');
INSERT INTO `categories` VALUES (1857, 'fr_FR', 'Côte d''Ivoire', '', '', '', ' cote ivoire ');
INSERT INTO `categories` VALUES (1858, 'fr_FR', 'Gambie', '', '', '', ' gambie ');
INSERT INTO `categories` VALUES (1859, 'fr_FR', 'Guinée-Bissau', '', '', '', ' guinee bissau ');
INSERT INTO `categories` VALUES (1860, 'fr_FR', 'Liberia', '', '', '', ' liberia ');
INSERT INTO `categories` VALUES (1861, 'fr_FR', 'Burkina Faso', '', '', '', ' burkina faso ');
INSERT INTO `categories` VALUES (1862, 'fr_FR', 'Ghana', '', '', '', ' ghana ');
INSERT INTO `categories` VALUES (1863, 'fr_FR', 'Togo', '', '', '', ' togo ');
INSERT INTO `categories` VALUES (1864, 'fr_FR', 'Bénin', '', '', '', ' benin ');
INSERT INTO `categories` VALUES (1865, 'fr_FR', 'Iles du Cap-Vert', '', '', '', ' iles cap vert ');
INSERT INTO `categories` VALUES (1866, 'fr_FR', 'Tchad', '', '', '', ' tchad ');
INSERT INTO `categories` VALUES (1867, 'fr_FR', 'Niger', '', '', '', ' niger ');
INSERT INTO `categories` VALUES (1868, 'fr_FR', 'Nigeria', '', '', '', ' nigeria ');
INSERT INTO `categories` VALUES (1869, 'fr_FR', 'Cameroun', '', '', '', ' cameroun ');
INSERT INTO `categories` VALUES (1870, 'fr_FR', 'Centrafricaine (République)', '', '', '', ' centrafricaine republique ');
INSERT INTO `categories` VALUES (1871, 'fr_FR', 'Soudan', '', '', '', ' soudan ');
INSERT INTO `categories` VALUES (1872, 'fr_FR', 'Erythrée', '', '', '', ' erythree ');
INSERT INTO `categories` VALUES (1873, 'fr_FR', 'Ethiopie', '', '', '', ' ethiopie ');
INSERT INTO `categories` VALUES (1874, 'fr_FR', 'Somalie', '', '', '', ' somalie ');
INSERT INTO `categories` VALUES (1875, 'fr_FR', 'Kenya', '', '', '', ' kenya ');
INSERT INTO `categories` VALUES (1876, 'fr_FR', 'Tanzanie', '', '', '', ' tanzanie ');
INSERT INTO `categories` VALUES (1877, 'fr_FR', 'Djibouti', '', '', '', ' djibouti ');
INSERT INTO `categories` VALUES (1878, 'fr_FR', 'Ouganda', '', '', '', ' ouganda ');
INSERT INTO `categories` VALUES (1879, 'fr_FR', 'Congo', '', '', '', ' congo ');
INSERT INTO `categories` VALUES (1880, 'fr_FR', 'Congo (République démocratique du)', '', '', '', ' congo republique democratique ');
INSERT INTO `categories` VALUES (1881, 'fr_FR', 'Gabon', '', '', '', ' gabon ');
INSERT INTO `categories` VALUES (1882, 'fr_FR', 'Guinée équatoriale', '', '', '', ' guinee equatoriale ');
INSERT INTO `categories` VALUES (1883, 'fr_FR', 'Sao Tomé et Principe', '', '', '', ' sao tome principe ');
INSERT INTO `categories` VALUES (1884, 'fr_FR', 'Rwanda', '', '', '', ' rwanda ');
INSERT INTO `categories` VALUES (1885, 'fr_FR', 'Burundi', '', '', '', ' burundi ');
INSERT INTO `categories` VALUES (1886, 'fr_FR', 'Angola', '', '', '', ' angola ');
INSERT INTO `categories` VALUES (1887, 'fr_FR', 'Zambie', '', '', '', ' zambie ');
INSERT INTO `categories` VALUES (1888, 'fr_FR', 'Zimbabwe', '', '', '', ' zimbabwe ');
INSERT INTO `categories` VALUES (1889, 'fr_FR', 'Botswana', '', '', '', ' botswana ');
INSERT INTO `categories` VALUES (1890, 'fr_FR', 'Afrique du Sud', '', '', '', ' afrique sud ');
INSERT INTO `categories` VALUES (1891, 'fr_FR', 'Swaziland', '', '', '', ' swaziland ');
INSERT INTO `categories` VALUES (1892, 'fr_FR', 'Lesotho', '', '', '', ' lesotho ');
INSERT INTO `categories` VALUES (1893, 'fr_FR', 'Namibie', '', '', '', ' namibie ');
INSERT INTO `categories` VALUES (1894, 'fr_FR', 'Mozambique', '', '', '', ' mozambique ');
INSERT INTO `categories` VALUES (1895, 'fr_FR', 'Malawi', '', '', '', ' malawi ');
INSERT INTO `categories` VALUES (1896, 'fr_FR', 'Seychelles', '', '', '', ' seychelles ');
INSERT INTO `categories` VALUES (1897, 'fr_FR', 'Ile Maurice', '', '', '', ' ile maurice ');
INSERT INTO `categories` VALUES (1898, 'fr_FR', 'Réunion', '', '', '', ' reunion ');
INSERT INTO `categories` VALUES (1899, 'fr_FR', 'Madagascar', '', '', '', ' madagascar ');
INSERT INTO `categories` VALUES (1900, 'fr_FR', 'Comores', '', '', '', ' comores ');
INSERT INTO `categories` VALUES (1901, 'fr_FR', 'Mayotte', '', '', '', ' mayotte ');
INSERT INTO `categories` VALUES (1902, 'fr_FR', 'Bahamas', '', '', '', ' bahamas ');
INSERT INTO `categories` VALUES (1903, 'fr_FR', 'Cuba', '', '', '', ' cuba ');
INSERT INTO `categories` VALUES (1904, 'fr_FR', 'Jamaïque', '', '', '', ' jamaique ');
INSERT INTO `categories` VALUES (1905, 'fr_FR', 'Haïti', '', '', '', ' haiti ');
INSERT INTO `categories` VALUES (1906, 'fr_FR', 'Dominicaine (République)', '', '', '', ' dominicaine republique ');
INSERT INTO `categories` VALUES (1907, 'fr_FR', 'Porto Rico', '', '', '', ' porto rico ');
INSERT INTO `categories` VALUES (1908, 'fr_FR', 'Guadeloupe', '', '', '', ' guadeloupe ');
INSERT INTO `categories` VALUES (1909, 'fr_FR', 'Martinique', '', '', '', ' martinique ');
INSERT INTO `categories` VALUES (1910, 'fr_FR', 'Islande', '', '', '', ' islande ');
INSERT INTO `categories` VALUES (1911, 'fr_FR', 'Norvège', '', '', '', ' norvege ');
INSERT INTO `categories` VALUES (1912, 'fr_FR', 'Suède', '', '', '', ' suede ');
INSERT INTO `categories` VALUES (1913, 'fr_FR', 'Finlande', '', '', '', ' finlande ');
INSERT INTO `categories` VALUES (1914, 'fr_FR', 'Danemark', '', '', '', ' danemark ');
INSERT INTO `categories` VALUES (1915, 'fr_FR', 'Irlande', '', '', '', ' irlande ');
INSERT INTO `categories` VALUES (1916, 'fr_FR', 'Grande-Bretagne', '', '', '', ' grande bretagne ');
INSERT INTO `categories` VALUES (1917, 'fr_FR', 'France', '', '', '', ' france ');
INSERT INTO `categories` VALUES (1918, 'fr_FR', 'Pays-Bas', '', '', '', ' pays bas ');
INSERT INTO `categories` VALUES (1919, 'fr_FR', 'Belgique', '', '', '', ' belgique ');
INSERT INTO `categories` VALUES (1920, 'fr_FR', 'Luxembourg', '', '', '', ' luxembourg ');
INSERT INTO `categories` VALUES (1921, 'fr_FR', 'Suisse', '', '', '', ' suisse ');
INSERT INTO `categories` VALUES (1922, 'fr_FR', 'Allemagne', '', '', '', ' allemagne ');
INSERT INTO `categories` VALUES (1923, 'fr_FR', 'Tchèque (République)', '', '', '', ' tcheque republique ');
INSERT INTO `categories` VALUES (1924, 'fr_FR', 'Slovaquie', '', '', '', ' slovaquie ');
INSERT INTO `categories` VALUES (1925, 'fr_FR', 'Autriche', '', '', '', ' autriche ');
INSERT INTO `categories` VALUES (1926, 'fr_FR', 'Hongrie', '', '', '', ' hongrie ');
INSERT INTO `categories` VALUES (1927, 'fr_FR', 'Pologne', '', '', '', ' pologne ');
INSERT INTO `categories` VALUES (1928, 'fr_FR', 'Estonie', '', '', '', ' estonie ');
INSERT INTO `categories` VALUES (1929, 'fr_FR', 'Lettonie', '', '', '', ' lettonie ');
INSERT INTO `categories` VALUES (1930, 'fr_FR', 'Lituanie', '', '', '', ' lituanie ');
INSERT INTO `categories` VALUES (1931, 'fr_FR', 'Biélorussie', '', '', '', ' bielorussie ');
INSERT INTO `categories` VALUES (1932, 'fr_FR', 'Ukraine', '', '', '', ' ukraine ');
INSERT INTO `categories` VALUES (1933, 'fr_FR', 'Moldavie', '', '', '', ' moldavie ');
INSERT INTO `categories` VALUES (1934, 'fr_FR', 'Roumanie', '', '', '', ' roumanie ');
INSERT INTO `categories` VALUES (1935, 'fr_FR', 'Portugal', '', '', '', ' portugal ');
INSERT INTO `categories` VALUES (1936, 'fr_FR', 'Espagne', '', '', '', ' espagne ');
INSERT INTO `categories` VALUES (1937, 'fr_FR', 'Italie', '', '', '', ' italie ');
INSERT INTO `categories` VALUES (1938, 'fr_FR', 'Malte', '', '', '', ' malte ');
INSERT INTO `categories` VALUES (1939, 'fr_FR', 'Slovénie', '', '', '', ' slovenie ');
INSERT INTO `categories` VALUES (1940, 'fr_FR', 'Croatie', '', '', '', ' croatie ');
INSERT INTO `categories` VALUES (1941, 'fr_FR', 'Bosnie-Herzégovine', '', '', '', ' bosnie herzegovine ');
INSERT INTO `categories` VALUES (1942, 'fr_FR', 'Macédoine', '', '', '', ' macedoine ');
INSERT INTO `categories` VALUES (1943, 'fr_FR', 'Albanie', '', '', '', ' albanie ');
INSERT INTO `categories` VALUES (1944, 'fr_FR', 'Grèce', '', '', '', ' grece ');
INSERT INTO `categories` VALUES (1945, 'fr_FR', 'Bulgarie', '', '', '', ' bulgarie ');
INSERT INTO `categories` VALUES (1946, 'fr_FR', 'Serbie', '', '', '', ' serbie ');
INSERT INTO `categories` VALUES (1947, 'fr_FR', 'Monténégro', '', '', '', ' montenegro ');
INSERT INTO `categories` VALUES (1948, 'fr_FR', 'Proche-Orient', '', '', '', ' proche orient ');
INSERT INTO `categories` VALUES (1949, 'fr_FR', 'Jordanie', '', '', '', ' jordanie ');
INSERT INTO `categories` VALUES (1950, 'fr_FR', 'Irak', '', '', '', ' irak ');
INSERT INTO `categories` VALUES (1951, 'fr_FR', 'Arabie', '', '', '', ' arabie ');
INSERT INTO `categories` VALUES (1952, 'fr_FR', 'Chypre', '', '', '', ' chypre ');
INSERT INTO `categories` VALUES (1953, 'fr_FR', 'Egypte', '', '', '', ' egypte ');
INSERT INTO `categories` VALUES (1954, 'fr_FR', 'Israël', '', '', '', ' israel ');
INSERT INTO `categories` VALUES (1955, 'fr_FR', 'Liban', '', '', '', ' liban ');
INSERT INTO `categories` VALUES (1956, 'fr_FR', 'Syrie', '', '', '', ' syrie ');
INSERT INTO `categories` VALUES (1957, 'fr_FR', 'Palestine (Territoires autonomes de)', '', '', '', ' palestine territoires autonomes ');
INSERT INTO `categories` VALUES (1958, 'fr_FR', 'Turquie', '', '', '', ' turquie ');
INSERT INTO `categories` VALUES (1959, 'fr_FR', 'Arabie saoudite', '', '', '', ' arabie saoudite ');
INSERT INTO `categories` VALUES (1960, 'fr_FR', 'Bahrein', '', '', '', ' bahrein ');
INSERT INTO `categories` VALUES (1961, 'fr_FR', 'Emirats arabes unis', '', '', '', ' emirats arabes unis ');
INSERT INTO `categories` VALUES (1962, 'fr_FR', 'Koweït', '', '', '', ' koweit ');
INSERT INTO `categories` VALUES (1963, 'fr_FR', 'Oman', '', '', '', ' oman ');
INSERT INTO `categories` VALUES (1964, 'fr_FR', 'Qatar', '', '', '', ' qatar ');
INSERT INTO `categories` VALUES (1965, 'fr_FR', 'Yémen', '', '', '', ' yemen ');
INSERT INTO `categories` VALUES (1966, 'fr_FR', 'Géorgie', '', '', '', ' georgie ');
INSERT INTO `categories` VALUES (1967, 'fr_FR', 'Arménie', '', '', '', ' armenie ');
INSERT INTO `categories` VALUES (1968, 'fr_FR', 'Azerbaïdjan', '', '', '', ' azerbaidjan ');
INSERT INTO `categories` VALUES (1969, 'fr_FR', 'Russie (Fédération de)', '', '', '', ' russie federation ');
INSERT INTO `categories` VALUES (1970, 'fr_FR', 'Kazakhstan', '', '', '', ' kazakhstan ');
INSERT INTO `categories` VALUES (1971, 'fr_FR', 'Ouzbékistan', '', '', '', ' ouzbekistan ');
INSERT INTO `categories` VALUES (1972, 'fr_FR', 'Kirghizistan', '', '', '', ' kirghizistan ');
INSERT INTO `categories` VALUES (1973, 'fr_FR', 'Turkménistan', '', '', '', ' turkmenistan ');
INSERT INTO `categories` VALUES (1974, 'fr_FR', 'Tadjikistan', '', '', '', ' tadjikistan ');
INSERT INTO `categories` VALUES (1975, 'fr_FR', 'Iran', '', '', '', ' iran ');
INSERT INTO `categories` VALUES (1976, 'fr_FR', 'Afghanistan', '', '', '', ' afghanistan ');
INSERT INTO `categories` VALUES (1977, 'fr_FR', 'Pakistan', '', '', '', ' pakistan ');
INSERT INTO `categories` VALUES (1978, 'fr_FR', 'Mongolie', '', '', '', ' mongolie ');
INSERT INTO `categories` VALUES (1979, 'fr_FR', 'Corée du Nord', '', '', '', ' coree nord ');
INSERT INTO `categories` VALUES (1980, 'fr_FR', 'Corée du Sud', '', '', '', ' coree sud ');
INSERT INTO `categories` VALUES (1981, 'fr_FR', 'Japon', '', '', '', ' japon ');
INSERT INTO `categories` VALUES (1982, 'fr_FR', 'Chine', '', '', '', ' chine ');
INSERT INTO `categories` VALUES (1983, 'fr_FR', 'Taïwan', '', '', '', ' taiwan ');
INSERT INTO `categories` VALUES (1984, 'fr_FR', 'Inde', '', '', '', ' inde ');
INSERT INTO `categories` VALUES (1985, 'fr_FR', 'Népal', '', '', '', ' nepal ');
INSERT INTO `categories` VALUES (1986, 'fr_FR', 'Bhoutan', '', '', '', ' bhoutan ');
INSERT INTO `categories` VALUES (1987, 'fr_FR', 'Bangladesh', '', '', '', ' bangladesh ');
INSERT INTO `categories` VALUES (1988, 'fr_FR', 'Sri Lanka', '', '', '', ' sri lanka ');
INSERT INTO `categories` VALUES (1989, 'fr_FR', 'Maldives', '', '', '', ' maldives ');
INSERT INTO `categories` VALUES (1990, 'fr_FR', 'Birmanie', '', '', '', ' birmanie ');
INSERT INTO `categories` VALUES (1991, 'fr_FR', 'Brunei', '', '', '', ' brunei ');
INSERT INTO `categories` VALUES (1992, 'fr_FR', 'Cambodge', '', '', '', ' cambodge ');
INSERT INTO `categories` VALUES (1993, 'fr_FR', 'Indonésie', '', '', '', ' indonesie ');
INSERT INTO `categories` VALUES (1994, 'fr_FR', 'Laos', '', '', '', ' laos ');
INSERT INTO `categories` VALUES (1995, 'fr_FR', 'Malaisie', '', '', '', ' malaisie ');
INSERT INTO `categories` VALUES (1996, 'fr_FR', 'Philippines', '', '', '', ' philippines ');
INSERT INTO `categories` VALUES (1997, 'fr_FR', 'Singapour', '', '', '', ' singapour ');
INSERT INTO `categories` VALUES (1998, 'fr_FR', 'Thaïlande', '', '', '', ' thailande ');
INSERT INTO `categories` VALUES (1999, 'fr_FR', 'Viêt-Nam', '', '', '', ' viet nam ');
INSERT INTO `categories` VALUES (2000, 'fr_FR', 'Papouasie-Nouvelle-Guinée', '', '', '', ' papouasie nouvelle guinee ');
INSERT INTO `categories` VALUES (2001, 'fr_FR', 'Nouvelle-Calédonie', '', '', '', ' nouvelle caledonie ');
INSERT INTO `categories` VALUES (2002, 'fr_FR', 'Vanuatu', '', '', '', ' vanuatu ');
INSERT INTO `categories` VALUES (2003, 'fr_FR', 'Iles Fidji', '', '', '', ' iles fidji ');
INSERT INTO `categories` VALUES (2004, 'fr_FR', 'Iles Salomon', '', '', '', ' iles salomon ');
INSERT INTO `categories` VALUES (2005, 'fr_FR', 'Bismarck (archipel)', '', '', '', ' bismarck archipel ');
INSERT INTO `categories` VALUES (2006, 'fr_FR', 'Carolines', '', '', '', ' carolines ');
INSERT INTO `categories` VALUES (2007, 'fr_FR', 'Kiribati', '', '', '', ' kiribati ');
INSERT INTO `categories` VALUES (2008, 'fr_FR', 'Mariannes', '', '', '', ' mariannes ');
INSERT INTO `categories` VALUES (2009, 'fr_FR', 'Marshall', '', '', '', ' marshall ');
INSERT INTO `categories` VALUES (2010, 'fr_FR', 'Palau', '', '', '', ' palau ');
INSERT INTO `categories` VALUES (2011, 'fr_FR', 'Nouvelle-Zélande', '', '', '', ' nouvelle zelande ');
INSERT INTO `categories` VALUES (2012, 'fr_FR', 'Tonga', '', '', '', ' tonga ');
INSERT INTO `categories` VALUES (2013, 'fr_FR', 'Wallis-et-Futuna', '', '', '', ' wallis futuna ');
INSERT INTO `categories` VALUES (2014, 'fr_FR', 'Nauru', '', '', '', ' nauru ');
INSERT INTO `categories` VALUES (2015, 'fr_FR', 'Tuvalu', '', '', '', ' tuvalu ');
INSERT INTO `categories` VALUES (2016, 'fr_FR', 'Samoa occidentales', '', '', '', ' samoa occidentales ');
INSERT INTO `categories` VALUES (2017, 'fr_FR', 'Hawaii', '', '', '', ' hawaii ');
INSERT INTO `categories` VALUES (2018, 'fr_FR', 'Samoa orientales', '', '', '', ' samoa orientales ');
INSERT INTO `categories` VALUES (2019, 'fr_FR', 'Iles Cook', '', '', '', ' iles cook ');
INSERT INTO `categories` VALUES (2020, 'fr_FR', 'Polynésie française', '', '', '', ' polynesie francaise ');
INSERT INTO `categories` VALUES (2021, 'fr_FR', 'Ile de Pâques', '', '', '', ' ile paques ');
INSERT INTO `categories` VALUES (2022, 'fr_FR', 'Sciences de la vie', '', '', '', ' sciences vie ');
INSERT INTO `categories` VALUES (2023, 'fr_FR', 'Hommes', '', '', '', ' hommes ');
INSERT INTO `categories` VALUES (2024, 'fr_FR', 'Hommes', '', '', '', ' hommes ');
INSERT INTO `categories` VALUES (2025, 'fr_FR', 'Femmes', '', '', '', ' femmes ');
INSERT INTO `categories` VALUES (2026, 'fr_FR', 'Femmes', '', '', '', ' femmes ');
INSERT INTO `categories` VALUES (2027, 'fr_FR', 'Homosexualité', '', '', '', ' homosexualite ');
INSERT INTO `categories` VALUES (2028, 'fr_FR', 'Sommeil', '', '', '', ' sommeil ');
INSERT INTO `categories` VALUES (2029, 'fr_FR', 'Avocat', '', '', '', ' avocat ');
INSERT INTO `categories` VALUES (2030, 'fr_FR', 'Maladie mentale', '', '', '', ' maladie mentale ');
INSERT INTO `categories` VALUES (2031, 'fr_FR', 'Autisme', '', '', '', ' autisme ');
INSERT INTO `categories` VALUES (2032, 'fr_FR', 'Psychiatrie', '', '', '', ' psychiatrie ');
INSERT INTO `categories` VALUES (2034, 'fr_FR', 'Formes et tailles', '', '', '', ' formes tailles ');
INSERT INTO `categories` VALUES (2035, 'fr_FR', 'Littérature de Méditerranée orientale et Maghreb', '', '', '', ' litterature mediterranee orientale maghreb ');
INSERT INTO `categories` VALUES (2036, 'fr_FR', 'Littérature anglaise', '', '', '', ' litterature anglaise ');
INSERT INTO `categories` VALUES (2037, 'fr_FR', 'Angoisse', '', '', '', ' angoisse ');
INSERT INTO `categories` VALUES (2039, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2040, 'fr_FR', 'Roller', '', '', '', ' roller ');
INSERT INTO `categories` VALUES (2043, 'fr_FR', 'Fauteuil roulant', '', '', '', ' fauteuil roulant ');
INSERT INTO `categories` VALUES (2044, 'fr_FR', 'Essai et récit', '', '', '', ' essai recit ');
INSERT INTO `categories` VALUES (2045, 'fr_FR', 'Littérature française', '', '', '', ' litterature francaise ');
INSERT INTO `categories` VALUES (2046, 'fr_FR', 'Littérature étrangère (sélection)', '', '', '', ' litterature etrangere selection ');
INSERT INTO `categories` VALUES (2047, 'fr_FR', 'Littérature espagnole', '', '', '', ' litterature espagnole ');
INSERT INTO `categories` VALUES (2048, 'fr_FR', 'Littérature d''Europe centrale', '', '', '', ' litterature europe centrale ');
INSERT INTO `categories` VALUES (2049, 'fr_FR', 'Littérature italienne', '', '', '', ' litterature italienne ');
INSERT INTO `categories` VALUES (2050, 'fr_FR', 'Littérature lusitanienne (Portugal, Brésil)', '', '', '', ' litterature lusitanienne portugal bresil ');
INSERT INTO `categories` VALUES (2051, 'fr_FR', 'Littérature nordique', '', '', '', ' litterature nordique ');
INSERT INTO `categories` VALUES (2052, 'fr_FR', 'Littérature russe', '', '', '', ' litterature russe ');
INSERT INTO `categories` VALUES (2053, 'fr_FR', 'Littérature d''Afrique noire', '', '', '', ' litterature afrique noire ');
INSERT INTO `categories` VALUES (2054, 'fr_FR', 'Poésie', '', '', '', ' poesie ');
INSERT INTO `categories` VALUES (2055, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2056, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2057, 'fr_FR', 'Aventure', '', '', '', ' aventure ');
INSERT INTO `categories` VALUES (2058, 'fr_FR', 'Roman historique', '', '', '', ' roman historique ');
INSERT INTO `categories` VALUES (2059, 'fr_FR', 'Amour', '', '', '', ' amour ');
INSERT INTO `categories` VALUES (2060, 'fr_FR', 'Science-fiction', '', '', '', ' science fiction ');
INSERT INTO `categories` VALUES (2061, 'fr_FR', 'Humour', '', '', '', ' humour ');
INSERT INTO `categories` VALUES (2062, 'fr_FR', 'Policier', '', '', '', ' policier ');
INSERT INTO `categories` VALUES (2063, 'fr_FR', 'Horreur', '', '', '', ' horreur ');
INSERT INTO `categories` VALUES (2064, 'fr_FR', 'Fantastique', '', '', '', ' fantastique ');
INSERT INTO `categories` VALUES (2065, 'fr_FR', 'Vie quotidienne', '', '', '', ' vie quotidienne ');
INSERT INTO `categories` VALUES (2066, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2067, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2068, 'fr_FR', 'Roman et nouvelle', '', '', '', ' roman nouvelle ');
INSERT INTO `categories` VALUES (2069, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2070, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2071, 'fr_FR', 'Chiffres', '', '', '', ' chiffres ');
INSERT INTO `categories` VALUES (2072, 'fr_FR', 'Alphabet', '', '', '', ' alphabet ');
INSERT INTO `categories` VALUES (2074, 'fr_FR', 'Roman et nouvelle', '', '', '', ' roman nouvelle ');
INSERT INTO `categories` VALUES (2075, 'fr_FR', 'Anglais', '', '', '', ' anglais ');
INSERT INTO `categories` VALUES (2076, 'fr_FR', 'Café', '', '', '', ' cafe ');
INSERT INTO `categories` VALUES (2077, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2078, 'fr_FR', 'Roman et nouvelle', '', '', '', ' roman nouvelle ');
INSERT INTO `categories` VALUES (2079, 'fr_FR', 'Mayas', '', '', '', ' mayas ');
INSERT INTO `categories` VALUES (2080, 'fr_FR', 'Incas', '', '', '', ' incas ');
INSERT INTO `categories` VALUES (2081, 'fr_FR', 'Aztèques', '', '', '', ' azteques ');
INSERT INTO `categories` VALUES (2082, 'fr_FR', 'Civilisations précolombiennes', '', '', '', ' civilisations precolombiennes ');
INSERT INTO `categories` VALUES (2083, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2084, 'fr_FR', 'Roman et nouvelle', '', '', '', ' roman nouvelle ');
INSERT INTO `categories` VALUES (2085, 'fr_FR', 'Elèves', '', '', '', ' eleves ');
INSERT INTO `categories` VALUES (2086, 'fr_FR', 'Prison', '', '', '', ' prison ');
INSERT INTO `categories` VALUES (2087, 'fr_FR', 'Prisonniers', '', '', '', ' prisonniers ');
INSERT INTO `categories` VALUES (2088, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2089, 'fr_FR', 'Critique littéraire - Biographies', '', '', '', ' critique litteraire biographies ');
INSERT INTO `categories` VALUES (2090, 'fr_FR', 'Byron, George Gordon Noel', '', '', '', ' byron george gordon noel ');
INSERT INTO `categories` VALUES (2092, 'fr_FR', 'Documentaires', '', '', '', ' documentaires ');
INSERT INTO `categories` VALUES (2093, 'fr_FR', 'Athènes', '', '', '', ' athenes ');
INSERT INTO `categories` VALUES (2094, 'fr_FR', 'Epices', '', '', '', ' epices ');
INSERT INTO `categories` VALUES (2095, 'fr_FR', 'Essai et récit', '', '', '', ' essai recit ');
INSERT INTO `categories` VALUES (2096, 'fr_FR', 'Essai et récit', '', '', '', ' essai recit ');
INSERT INTO `categories` VALUES (2097, 'fr_FR', 'Essai et récit', '', '', '', ' essai recit ');
INSERT INTO `categories` VALUES (2098, 'fr_FR', 'Venise', '', '', '', ' venise ');
INSERT INTO `categories` VALUES (2099, 'fr_FR', 'Essai et récit', '', '', '', ' essai recit ');
INSERT INTO `categories` VALUES (2100, 'fr_FR', 'Documentaires', '', '', '', ' documentaires ');
INSERT INTO `categories` VALUES (2101, 'fr_FR', 'Aventure', '', '', '', ' aventure ');
INSERT INTO `categories` VALUES (2102, 'fr_FR', 'Humour', '', '', '', ' humour ');
INSERT INTO `categories` VALUES (2103, 'fr_FR', 'Policier', '', '', '', ' policier ');
INSERT INTO `categories` VALUES (2104, 'fr_FR', 'Science-fiction', '', '', '', ' science fiction ');
INSERT INTO `categories` VALUES (2105, 'fr_FR', 'Récit historique', '', '', '', ' recit historique ');
INSERT INTO `categories` VALUES (2106, 'fr_FR', 'Fantastique', '', '', '', ' fantastique ');
INSERT INTO `categories` VALUES (2107, 'fr_FR', 'Western', '', '', '', ' western ');
INSERT INTO `categories` VALUES (2108, 'fr_FR', 'Roman et nouvelle', '', '', '', ' roman nouvelle ');
INSERT INTO `categories` VALUES (2109, 'fr_FR', 'Amitié', '', '', '', ' amitie ');
INSERT INTO `categories` VALUES (2110, 'fr_FR', 'Roman et nouvelle', '', '', '', ' roman nouvelle ');
INSERT INTO `categories` VALUES (2111, 'fr_FR', 'Roman et nouvelle', '', '', '', ' roman nouvelle ');
INSERT INTO `categories` VALUES (2112, 'fr_FR', 'Roman d''aventure', '', '', '', ' roman aventure ');
INSERT INTO `categories` VALUES (2113, 'fr_FR', 'Théâtre', '', '', '', ' theatre ');
INSERT INTO `categories` VALUES (2114, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2115, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2116, 'fr_FR', 'Site web', '', '', '', ' site web ');
INSERT INTO `categories` VALUES (2117, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2118, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2119, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2120, 'fr_FR', 'Cultures et communautés', '', '', '', ' cultures communautes ');
INSERT INTO `categories` VALUES (2121, 'fr_FR', 'Gitans', '', '', '', ' gitans ');
INSERT INTO `categories` VALUES (2122, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2123, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2124, 'fr_FR', 'Roman et nouvelle', '', '', '', ' roman nouvelle ');
INSERT INTO `categories` VALUES (2125, 'fr_FR', 'Aliments', '', '', '', ' aliments ');
INSERT INTO `categories` VALUES (2126, 'fr_FR', 'Huître', '', '', '', ' huitre ');
INSERT INTO `categories` VALUES (2127, 'fr_FR', 'Moule', '', '', '', ' moule ');
INSERT INTO `categories` VALUES (2128, 'fr_FR', 'Coquillages', '', '', '', ' coquillages ');
INSERT INTO `categories` VALUES (2129, 'fr_FR', 'Conchyliculture', '', '', '', ' conchyliculture ');
INSERT INTO `categories` VALUES (2130, 'fr_FR', 'Ostréiculture', '', '', '', ' ostreiculture ');
INSERT INTO `categories` VALUES (2131, 'fr_FR', 'Mytiliculture', '', '', '', ' mytiliculture ');
INSERT INTO `categories` VALUES (2132, 'fr_FR', 'Lingerie', '', '', '', ' lingerie ');
INSERT INTO `categories` VALUES (2135, 'fr_FR', 'Par sujet', '', '', '', ' par sujet ');
INSERT INTO `categories` VALUES (2136, 'fr_FR', 'Atlantide', '', '', '', ' atlantide ');
INSERT INTO `categories` VALUES (2137, 'fr_FR', 'Fête foraine', '', '', '', ' fete foraine ');
INSERT INTO `categories` VALUES (2138, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2139, 'fr_FR', 'Cheval', '', '', '', ' cheval ');
INSERT INTO `categories` VALUES (2140, 'fr_FR', 'Equidés', '', '', '', ' equides ');
INSERT INTO `categories` VALUES (2141, 'fr_FR', 'Canidés', '', '', '', ' canides ');
INSERT INTO `categories` VALUES (2142, 'fr_FR', 'Musée de l''Homme', '', '', '', ' musee homme ');
INSERT INTO `categories` VALUES (2143, 'fr_FR', 'Histoire', '', '', '', ' histoire ');
INSERT INTO `categories` VALUES (2144, 'fr_FR', 'Paris', '', '', '', ' paris ');
INSERT INTO `categories` VALUES (2145, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2146, 'fr_FR', 'Surréalisme', '', '', '', ' surrealisme ');
INSERT INTO `categories` VALUES (2147, 'fr_FR', 'Histoire littéraire (ouvrages généraux)', '', '', '', ' histoire litteraire ouvrages generaux ');
INSERT INTO `categories` VALUES (2148, 'fr_FR', 'Dadaïsme', '', '', '', ' dadaisme ');
INSERT INTO `categories` VALUES (2150, 'fr_FR', 'Romantisme', '', '', '', ' romantisme ');
INSERT INTO `categories` VALUES (2151, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2152, 'fr_FR', 'Antiquité', '', '', '', ' antiquite ');
INSERT INTO `categories` VALUES (2153, 'fr_FR', 'Renaissance', '', '', '', ' renaissance ');
INSERT INTO `categories` VALUES (2154, 'fr_FR', 'Moyen-Age', '', '', '', ' moyen age ');
INSERT INTO `categories` VALUES (2155, 'fr_FR', 'XVIIème siècle', '', '', '', ' xviieme siecle ');
INSERT INTO `categories` VALUES (2156, 'fr_FR', 'XVIIIème siècle', '', '', '', ' xviiieme siecle ');
INSERT INTO `categories` VALUES (2157, 'fr_FR', 'XIXème siècle', '', '', '', ' xixeme siecle ');
INSERT INTO `categories` VALUES (2158, 'fr_FR', 'XXème siècle', '', '', '', ' xxeme siecle ');
INSERT INTO `categories` VALUES (2159, 'fr_FR', 'Géographie', '', '', '', ' geographie ');
INSERT INTO `categories` VALUES (2160, 'fr_FR', 'Histoire', '', '', '', ' histoire ');
INSERT INTO `categories` VALUES (2161, 'fr_FR', 'Aquarelle', '', '', '', ' aquarelle ');
INSERT INTO `categories` VALUES (2162, 'fr_FR', 'Mer', '', '', '', ' mer ');
INSERT INTO `categories` VALUES (2163, 'fr_FR', 'Rivière', '', '', '', ' riviere ');
INSERT INTO `categories` VALUES (2164, 'fr_FR', 'Araignées', '', '', '', ' araignees ');
INSERT INTO `categories` VALUES (2165, 'fr_FR', 'Reptiles', '', '', '', ' reptiles ');
INSERT INTO `categories` VALUES (2166, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2167, 'fr_FR', 'Paysage', '', '', '', ' paysage ');
INSERT INTO `categories` VALUES (2168, 'fr_FR', 'Eléments', '', '', '', ' elements ');
INSERT INTO `categories` VALUES (2169, 'fr_FR', 'Ciel', '', '', '', ' ciel ');
INSERT INTO `categories` VALUES (2170, 'fr_FR', 'Art du paysage', '', '', '', ' art paysage ');
INSERT INTO `categories` VALUES (2171, 'fr_FR', 'Mythes et légendes', '', '', '', ' mythes legendes ');
INSERT INTO `categories` VALUES (2172, 'fr_FR', 'Jardin', '', '', '', ' jardin ');
INSERT INTO `categories` VALUES (2173, 'fr_FR', 'Documentaires', '', '', '', ' documentaires ');
INSERT INTO `categories` VALUES (2174, 'fr_FR', 'Bali', '', '', '', ' bali ');
INSERT INTO `categories` VALUES (2175, 'fr_FR', 'Claudel, Camille', '', '', '', ' claudel camille ');
INSERT INTO `categories` VALUES (2177, 'fr_FR', 'Effel, Jean', '', '', '', ' effel jean ');
INSERT INTO `categories` VALUES (2179, 'fr_FR', 'Cubisme', '', '', '', ' cubisme ');
INSERT INTO `categories` VALUES (2180, 'fr_FR', 'Couleur', '', '', '', ' couleur ');
INSERT INTO `categories` VALUES (2181, 'fr_FR', 'Couleur', '', '', '', ' couleur ');
INSERT INTO `categories` VALUES (2182, 'fr_FR', 'Porte', '', '', '', ' porte ');
INSERT INTO `categories` VALUES (2183, 'fr_FR', 'Animaux menacés', '', '', '', ' animaux menaces ');
INSERT INTO `categories` VALUES (2184, 'fr_FR', 'Edifices industriels', '', '', '', ' edifices industriels ');
INSERT INTO `categories` VALUES (2185, 'fr_FR', 'Par sujet', '', '', '', ' par sujet ');
INSERT INTO `categories` VALUES (2186, 'fr_FR', 'Stern, Bert', '', '', '', ' stern bert ');
INSERT INTO `categories` VALUES (2188, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2189, 'fr_FR', 'Guides touristiques', '', '', '', ' guides touristiques ');
INSERT INTO `categories` VALUES (2190, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2191, 'fr_FR', 'Lune', '', '', '', ' lune ');
INSERT INTO `categories` VALUES (2192, 'fr_FR', 'Lune', '', '', '', ' lune ');
INSERT INTO `categories` VALUES (2193, 'fr_FR', 'Poésie', '', '', '', ' poesie ');
INSERT INTO `categories` VALUES (2194, 'fr_FR', 'Essais', '', '', '', ' essais ');
INSERT INTO `categories` VALUES (2196, 'fr_FR', 'Automobile', '', '', '', ' automobile ');
INSERT INTO `categories` VALUES (2197, 'fr_FR', 'Renault', '', '', '', ' renault ');
INSERT INTO `categories` VALUES (2198, 'fr_FR', '4 Chevaux', '', '', '', ' 4 chevaux ');
INSERT INTO `categories` VALUES (2200, 'fr_FR', 'Danse', '', '', '', ' danse ');
INSERT INTO `categories` VALUES (2201, 'fr_FR', 'Cunningham, Merce', '', '', '', ' cunningham merce ');
INSERT INTO `categories` VALUES (2203, 'fr_FR', 'Jardinage', '', '', '', ' jardinage ');
INSERT INTO `categories` VALUES (2204, 'fr_FR', 'Documentaires', '', '', '', ' documentaires ');
INSERT INTO `categories` VALUES (2205, 'fr_FR', 'Géographie', '', '', '', ' geographie ');
INSERT INTO `categories` VALUES (2206, 'fr_FR', 'Asie', '', '', '', ' asie ');
INSERT INTO `categories` VALUES (2207, 'fr_FR', 'Moyen-Orient', '', '', '', ' moyen orient ');
INSERT INTO `categories` VALUES (2208, 'fr_FR', 'Documentaires', '', '', '', ' documentaires ');
INSERT INTO `categories` VALUES (2209, 'fr_FR', 'Documentaires', '', '', '', ' documentaires ');
INSERT INTO `categories` VALUES (2210, 'fr_FR', 'Documentaires', '', '', '', ' documentaires ');
INSERT INTO `categories` VALUES (2211, 'fr_FR', 'Documentaires', '', '', '', ' documentaires ');
INSERT INTO `categories` VALUES (2212, 'fr_FR', 'Océanie', '', '', '', ' oceanie ');
INSERT INTO `categories` VALUES (2213, 'fr_FR', 'Documentaires', '', '', '', ' documentaires ');
INSERT INTO `categories` VALUES (2214, 'fr_FR', 'Fleurs', '', '', '', ' fleurs ');
INSERT INTO `categories` VALUES (2215, 'fr_FR', 'Tropiques', '', '', '', ' tropiques ');
INSERT INTO `categories` VALUES (2216, 'fr_FR', 'Orchidée', '', '', '', ' orchidee ');
INSERT INTO `categories` VALUES (2217, 'fr_FR', 'Tropiques', '', '', '', ' tropiques ');
INSERT INTO `categories` VALUES (2218, 'fr_FR', 'Tropiques', '', '', '', ' tropiques ');
INSERT INTO `categories` VALUES (2219, 'fr_FR', 'Par environnement', '', '', '', ' par environnement ');
INSERT INTO `categories` VALUES (2220, 'fr_FR', 'Tropiques', '', '', '', ' tropiques ');
INSERT INTO `categories` VALUES (2221, 'fr_FR', 'Mer', '', '', '', ' mer ');
INSERT INTO `categories` VALUES (2222, 'fr_FR', 'Mer tropicale', '', '', '', ' mer tropicale ');
INSERT INTO `categories` VALUES (2223, 'fr_FR', 'Coquillage', '', '', '', ' coquillage ');
INSERT INTO `categories` VALUES (2224, 'fr_FR', 'Fruits', '', '', '', ' fruits ');
INSERT INTO `categories` VALUES (2225, 'fr_FR', 'Légumes', '', '', '', ' legumes ');
INSERT INTO `categories` VALUES (2226, 'fr_FR', 'XXIème siècle', '', '', '', ' xxieme siecle ');
INSERT INTO `categories` VALUES (2227, 'fr_FR', 'Papier', '', '', '', ' papier ');
INSERT INTO `categories` VALUES (2228, 'fr_FR', 'Art nouveau', '', '', '', ' art nouveau ');
INSERT INTO `categories` VALUES (2229, 'fr_FR', 'Mucha, Alfons', '', '', '', ' mucha alfons ');
INSERT INTO `categories` VALUES (2231, 'fr_FR', 'Michel-Ange', '', '', '', ' michel ange ');
INSERT INTO `categories` VALUES (2232, 'fr_FR', 'Michel-Ange', '', '', '', ' michel ange ');
INSERT INTO `categories` VALUES (2233, 'fr_FR', 'Klee, Paul', '', '', '', ' klee paul ');
INSERT INTO `categories` VALUES (2235, 'fr_FR', 'Afrique', '', '', '', ' afrique ');
INSERT INTO `categories` VALUES (2236, 'fr_FR', 'Champignons', '', '', '', ' champignons ');
INSERT INTO `categories` VALUES (2237, 'fr_FR', 'Maison', '', '', '', ' maison ');
INSERT INTO `categories` VALUES (2238, 'fr_FR', 'Documentaires', '', '', '', ' documentaires ');
INSERT INTO `categories` VALUES (2239, 'fr_FR', 'Hôtels particuliers', '', '', '', ' hotels particuliers ');
INSERT INTO `categories` VALUES (2240, 'fr_FR', 'Nouvel, Jean', '', '', '', ' nouvel jean ');
INSERT INTO `categories` VALUES (2242, 'fr_FR', 'Modigliani, Amedeo', '', '', '', ' modigliani amedeo ');
INSERT INTO `categories` VALUES (2244, 'fr_FR', 'Documentaires', '', '', '', ' documentaires ');
INSERT INTO `categories` VALUES (2245, 'fr_FR', 'Art brut', '', '', '', ' art brut ');
INSERT INTO `categories` VALUES (2246, 'fr_FR', 'Rodin, Auguste', '', '', '', ' rodin auguste ');
INSERT INTO `categories` VALUES (2248, 'fr_FR', 'Duchamp, Marcel', '', '', '', ' duchamp marcel ');
INSERT INTO `categories` VALUES (2250, 'fr_FR', 'Picasso, Pablo', '', '', '', ' picasso pablo ');
INSERT INTO `categories` VALUES (2252, 'fr_FR', 'Cochon d''Inde', '', '', '', ' cochon inde ');
INSERT INTO `categories` VALUES (2253, 'fr_FR', 'Elevage', '', '', '', ' elevage ');
INSERT INTO `categories` VALUES (2254, 'fr_FR', 'Explorateurs', '', '', '', ' explorateurs ');
INSERT INTO `categories` VALUES (2255, 'fr_FR', 'Conquérants', '', '', '', ' conquerants ');
INSERT INTO `categories` VALUES (2256, 'fr_FR', 'Sociétés secrètes', '', '', '', ' societes secretes ');
INSERT INTO `categories` VALUES (2257, 'fr_FR', 'Gangs', '', '', '', ' gangs ');
INSERT INTO `categories` VALUES (2258, 'fr_FR', 'Mafia', '', '', '', ' mafia ');
INSERT INTO `categories` VALUES (2259, 'fr_FR', 'Fonds marins', '', '', '', ' fonds marins ');
INSERT INTO `categories` VALUES (2260, 'fr_FR', 'Monuments', '', '', '', ' monuments ');
INSERT INTO `categories` VALUES (2261, 'fr_FR', 'Esclavage', '', '', '', ' esclavage ');
INSERT INTO `categories` VALUES (2262, 'fr_FR', 'Voile', '', '', '', ' voile ');
INSERT INTO `categories` VALUES (2264, 'fr_FR', 'Equitation et sports équestres', '', '', '', ' equitation sports equestres ');
INSERT INTO `categories` VALUES (2265, 'fr_FR', 'France', '', '', '', ' france ');
INSERT INTO `categories` VALUES (2266, 'fr_FR', 'Rois', '', '', '', ' rois ');
INSERT INTO `categories` VALUES (2267, 'fr_FR', 'Mythologie', '', '', '', ' mythologie ');
INSERT INTO `categories` VALUES (2268, 'fr_FR', 'Chien', '', '', '', ' chien ');
INSERT INTO `categories` VALUES (2269, 'fr_FR', 'Cheval', '', '', '', ' cheval ');
INSERT INTO `categories` VALUES (2270, 'fr_FR', 'Poney', '', '', '', ' poney ');
INSERT INTO `categories` VALUES (2271, 'fr_FR', 'XXème siècle', '', '', '', ' xxeme siecle ');
INSERT INTO `categories` VALUES (2272, 'fr_FR', 'Personnages célèbres', '', '', '', ' personnages celebres ');
INSERT INTO `categories` VALUES (2273, 'fr_FR', 'Civilisation', '', '', '', ' civilisation ');
INSERT INTO `categories` VALUES (2274, 'fr_FR', 'Pharaons', '', '', '', ' pharaons ');
INSERT INTO `categories` VALUES (2275, 'fr_FR', 'Egypte', '', '', '', ' egypte ');
INSERT INTO `categories` VALUES (2276, 'fr_FR', 'Chien', '', '', '', ' chien ');
INSERT INTO `categories` VALUES (2277, 'fr_FR', 'Antiquité', '', '', '', ' antiquite ');
INSERT INTO `categories` VALUES (2278, 'fr_FR', 'Age de pierre', '', '', '', ' age pierre ');
INSERT INTO `categories` VALUES (2279, 'fr_FR', 'Football', '', '', '', ' football ');
INSERT INTO `categories` VALUES (2280, 'fr_FR', 'Avion', '', '', '', ' avion ');
INSERT INTO `categories` VALUES (2281, 'fr_FR', 'Bateau', '', '', '', ' bateau ');
INSERT INTO `categories` VALUES (2282, 'fr_FR', 'Train', '', '', '', ' train ');
INSERT INTO `categories` VALUES (2283, 'fr_FR', 'Océan', '', '', '', ' ocean ');
INSERT INTO `categories` VALUES (2284, 'fr_FR', 'Verbe', '', '', '', ' verbe ');
INSERT INTO `categories` VALUES (2285, 'fr_FR', 'Nom', '', '', '', ' nom ');
INSERT INTO `categories` VALUES (2286, 'fr_FR', 'Adverbe', '', '', '', ' adverbe ');
INSERT INTO `categories` VALUES (2287, 'fr_FR', 'Monastère', '', '', '', ' monastere ');
INSERT INTO `categories` VALUES (2288, 'fr_FR', 'Tibet', '', '', '', ' tibet ');
INSERT INTO `categories` VALUES (2289, 'fr_FR', 'Mare', '', '', '', ' mare ');
INSERT INTO `categories` VALUES (2290, 'fr_FR', 'Nain de jardin', '', '', '', ' nain jardin ');
INSERT INTO `categories` VALUES (2291, 'fr_FR', 'Pêche', '', '', '', ' peche ');
INSERT INTO `categories` VALUES (2292, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2293, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2294, 'fr_FR', 'Distillation', '', '', '', ' distillation ');
INSERT INTO `categories` VALUES (2295, 'fr_FR', 'Cadou, René Guy', '', '', '', ' cadou rene guy ');
INSERT INTO `categories` VALUES (2297, 'fr_FR', 'Mondialisation', '', '', '', ' mondialisation ');
INSERT INTO `categories` VALUES (2298, 'fr_FR', 'Lecture', '', '', '', ' lecture ');
INSERT INTO `categories` VALUES (2299, 'fr_FR', 'Jeux olympiques', '', '', '', ' jeux olympiques ');
INSERT INTO `categories` VALUES (2300, 'fr_FR', 'Balzac, Honoré de', '', '', '', ' balzac honore ');
INSERT INTO `categories` VALUES (2302, 'fr_FR', 'Musset, Alfred de', '', '', '', ' musset alfred ');
INSERT INTO `categories` VALUES (2303, 'fr_FR', 'Electroménager', '', '', '', ' electromenager ');
INSERT INTO `categories` VALUES (2304, 'fr_FR', 'Londres', '', '', '', ' londres ');
INSERT INTO `categories` VALUES (2306, 'fr_FR', 'Artisanat', '', '', '', ' artisanat ');
INSERT INTO `categories` VALUES (2307, 'fr_FR', 'Documentaires', '', '', '', ' documentaires ');
INSERT INTO `categories` VALUES (2308, 'fr_FR', 'Boissons', '', '', '', ' boissons ');
INSERT INTO `categories` VALUES (2309, 'fr_FR', 'Vin', '', '', '', ' vin ');
INSERT INTO `categories` VALUES (2310, 'fr_FR', 'Explorateurs', '', '', '', ' explorateurs ');
INSERT INTO `categories` VALUES (2311, 'fr_FR', 'SQL', '', '', '', ' sql ');
INSERT INTO `categories` VALUES (2312, 'fr_FR', 'Québec', '', '', '', ' quebec ');
INSERT INTO `categories` VALUES (2313, 'fr_FR', 'Méditerranée', '', '', '', ' mediterranee ');
INSERT INTO `categories` VALUES (2314, 'fr_FR', 'Rapport de stage', '', '', '', ' rapport stage ');
INSERT INTO `categories` VALUES (2315, 'fr_FR', 'Mémoire', '', '', '', ' memoire ');
INSERT INTO `categories` VALUES (2316, 'fr_FR', 'Analyse de texte', '', '', '', ' analyse texte ');
INSERT INTO `categories` VALUES (2317, 'fr_FR', 'Commentaire de texte', '', '', '', ' commentaire texte ');
INSERT INTO `categories` VALUES (2318, 'fr_FR', 'Traces', '', '', '', ' traces ');
INSERT INTO `categories` VALUES (2319, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2320, 'fr_FR', 'Classification', '', '', '', ' classification ');
INSERT INTO `categories` VALUES (2321, 'fr_FR', 'Dewey', '', '', '', ' dewey ');
INSERT INTO `categories` VALUES (2322, 'fr_FR', 'Dewey, Melvil', '', '', '', ' dewey melvil ');
INSERT INTO `categories` VALUES (2324, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2325, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2326, 'fr_FR', 'Paternité', '', '', '', ' paternite ');
INSERT INTO `categories` VALUES (2327, 'fr_FR', 'Imprimerie', '', '', '', ' imprimerie ');
INSERT INTO `categories` VALUES (2328, 'fr_FR', 'Union européenne', '', '', '', ' union europeenne ');
INSERT INTO `categories` VALUES (2329, 'fr_FR', 'Histoire', '', '', '', ' histoire ');
INSERT INTO `categories` VALUES (2330, 'fr_FR', 'Trouble obsessionnel compulsif', '', '', '', ' trouble obsessionnel compulsif ');
INSERT INTO `categories` VALUES (2332, 'fr_FR', 'ONU', '', '', '', ' onu ');
INSERT INTO `categories` VALUES (2335, 'fr_FR', 'Couple', '', '', '', ' couple ');
INSERT INTO `categories` VALUES (2336, 'fr_FR', 'Automobile', '', '', '', ' automobile ');
INSERT INTO `categories` VALUES (2337, 'fr_FR', 'Renault', '', '', '', ' renault ');
INSERT INTO `categories` VALUES (2338, 'fr_FR', 'Guerre', '', '', '', ' guerre ');
INSERT INTO `categories` VALUES (2339, 'fr_FR', 'Désert', '', '', '', ' desert ');
INSERT INTO `categories` VALUES (2340, 'fr_FR', 'Star', '', '', '', ' star ');
INSERT INTO `categories` VALUES (2341, 'fr_FR', 'Civilisations menacées', '', '', '', ' civilisations menacees ');
INSERT INTO `categories` VALUES (2342, 'fr_FR', 'Objet', '', '', '', ' objet ');
INSERT INTO `categories` VALUES (2343, 'fr_FR', 'OMC', '', '', '', ' omc ');
INSERT INTO `categories` VALUES (2345, 'fr_FR', 'Politique économique', '', '', '', ' politique economique ');
INSERT INTO `categories` VALUES (2346, 'fr_FR', 'Mondialisation culturelle', '', '', '', ' mondialisation culturelle ');
INSERT INTO `categories` VALUES (2347, 'fr_FR', 'Culture', '', '', '', ' culture ');
INSERT INTO `categories` VALUES (2348, 'fr_FR', 'Chômage', '', '', '', ' chomage ');
INSERT INTO `categories` VALUES (2349, 'fr_FR', 'Système monétaire international', '', '', '', ' systeme monetaire international ');
INSERT INTO `categories` VALUES (2350, 'fr_FR', 'Bière', '', '', '', ' biere ');
INSERT INTO `categories` VALUES (2352, 'fr_FR', 'Adolescents', '', '', '', ' adolescents ');
INSERT INTO `categories` VALUES (2353, 'fr_FR', 'Dubuffet, Jean', '', '', '', ' dubuffet jean ');
INSERT INTO `categories` VALUES (2354, 'fr_FR', 'Sauces', '', '', '', ' sauces ');
INSERT INTO `categories` VALUES (2356, 'fr_FR', 'Surréalisme', '', '', '', ' surrealisme ');
INSERT INTO `categories` VALUES (2358, 'fr_FR', 'Dali, Salvador', '', '', '', ' dali salvador ');
INSERT INTO `categories` VALUES (2359, 'fr_FR', 'Mouvements', '', '', '', ' mouvements ');
INSERT INTO `categories` VALUES (2361, 'fr_FR', 'Miel', '', '', '', ' miel ');
INSERT INTO `categories` VALUES (2362, 'fr_FR', 'Ernst, Max', '', '', '', ' ernst max ');
INSERT INTO `categories` VALUES (2364, 'fr_FR', 'Miro, Joan', '', '', '', ' miro joan ');
INSERT INTO `categories` VALUES (2366, 'fr_FR', 'Magritte, René', '', '', '', ' magritte rene ');
INSERT INTO `categories` VALUES (2368, 'fr_FR', 'Mouvements', '', '', '', ' mouvements ');
INSERT INTO `categories` VALUES (2369, 'fr_FR', 'Infusions', '', '', '', ' infusions ');
INSERT INTO `categories` VALUES (2371, 'fr_FR', 'Commerce international', '', '', '', ' commerce international ');
INSERT INTO `categories` VALUES (2372, 'fr_FR', 'Argumentation', '', '', '', ' argumentation ');
INSERT INTO `categories` VALUES (2373, 'fr_FR', 'Théories', '', '', '', ' theories ');
INSERT INTO `categories` VALUES (2374, 'fr_FR', 'Journalisme', '', '', '', ' journalisme ');
INSERT INTO `categories` VALUES (2375, 'fr_FR', 'Mariage', '', '', '', ' mariage ');
INSERT INTO `categories` VALUES (2376, 'fr_FR', 'Europe', '', '', '', ' europe ');
INSERT INTO `categories` VALUES (2377, 'fr_FR', 'Fromage', '', '', '', ' fromage ');
INSERT INTO `categories` VALUES (2378, 'fr_FR', 'Agriculteurs', '', '', '', ' agriculteurs ');
INSERT INTO `categories` VALUES (2380, 'fr_FR', 'Mouche', '', '', '', ' mouche ');
INSERT INTO `categories` VALUES (2381, 'fr_FR', 'Portrait', '', '', '', ' portrait ');
INSERT INTO `categories` VALUES (2382, 'fr_FR', 'Enfant', '', '', '', ' enfant ');
INSERT INTO `categories` VALUES (2383, 'fr_FR', 'Herbier', '', '', '', ' herbier ');
INSERT INTO `categories` VALUES (2384, 'fr_FR', 'France', '', '', '', ' france ');
INSERT INTO `categories` VALUES (2385, 'fr_FR', 'Paris', '', '', '', ' paris ');
INSERT INTO `categories` VALUES (2386, 'fr_FR', 'Amérique', '', '', '', ' amerique ');
INSERT INTO `categories` VALUES (2387, 'fr_FR', 'Tex mex', '', '', '', ' tex mex ');
INSERT INTO `categories` VALUES (2388, 'fr_FR', 'Jamaïque', '', '', '', ' jamaique ');
INSERT INTO `categories` VALUES (2389, 'fr_FR', 'Documentaires', '', '', '', ' documentaires ');
INSERT INTO `categories` VALUES (2390, 'fr_FR', 'Documentaires', '', '', '', ' documentaires ');
INSERT INTO `categories` VALUES (2391, 'fr_FR', 'Italie', '', '', '', ' italie ');
INSERT INTO `categories` VALUES (2392, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2393, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2394, 'fr_FR', 'Japonais', '', '', '', ' japonais ');
INSERT INTO `categories` VALUES (2395, 'fr_FR', 'Chinois', '', '', '', ' chinois ');
INSERT INTO `categories` VALUES (2396, 'fr_FR', 'Plantes aromatiques', '', '', '', ' plantes aromatiques ');
INSERT INTO `categories` VALUES (2397, 'fr_FR', 'Plat unique', '', '', '', ' plat unique ');
INSERT INTO `categories` VALUES (2398, 'fr_FR', 'Documentaires', '', '', '', ' documentaires ');
INSERT INTO `categories` VALUES (2399, 'fr_FR', 'Immigration', '', '', '', ' immigration ');
INSERT INTO `categories` VALUES (2400, 'fr_FR', 'Immigration clandestine', '', '', '', ' immigration clandestine ');
INSERT INTO `categories` VALUES (2402, 'fr_FR', 'Soufisme', '', '', '', ' soufisme ');
INSERT INTO `categories` VALUES (2403, 'fr_FR', 'Relaxation', '', '', '', ' relaxation ');
INSERT INTO `categories` VALUES (2404, 'fr_FR', 'Banlieue', '', '', '', ' banlieue ');
INSERT INTO `categories` VALUES (2405, 'fr_FR', 'Taï chi chuan', '', '', '', ' tai chi chuan ');
INSERT INTO `categories` VALUES (2406, 'fr_FR', 'Jouet', '', '', '', ' jouet ');
INSERT INTO `categories` VALUES (2407, 'fr_FR', 'Jouet', '', '', '', ' jouet ');
INSERT INTO `categories` VALUES (2408, 'fr_FR', 'Portugal', '', '', '', ' portugal ');
INSERT INTO `categories` VALUES (2409, 'fr_FR', 'Documentaires', '', '', '', ' documentaires ');
INSERT INTO `categories` VALUES (2410, 'fr_FR', 'Acteurs', '', '', '', ' acteurs ');
INSERT INTO `categories` VALUES (2411, 'fr_FR', 'Afrique', '', '', '', ' afrique ');
INSERT INTO `categories` VALUES (2412, 'fr_FR', 'Maroc', '', '', '', ' maroc ');
INSERT INTO `categories` VALUES (2413, 'fr_FR', 'Economique', '', '', '', ' economique ');
INSERT INTO `categories` VALUES (2414, 'fr_FR', 'Herbes aromatiques', '', '', '', ' herbes aromatiques ');
INSERT INTO `categories` VALUES (2416, 'fr_FR', 'Keynes, John Maynard', '', '', '', ' keynes john maynard ');
INSERT INTO `categories` VALUES (2418, 'fr_FR', 'Capitalisme', '', '', '', ' capitalisme ');
INSERT INTO `categories` VALUES (2420, 'fr_FR', 'OGM', '', '', '', ' ogm ');
INSERT INTO `categories` VALUES (2422, 'fr_FR', 'Rapide', '', '', '', ' rapide ');
INSERT INTO `categories` VALUES (2423, 'fr_FR', 'Pomme de terre', '', '', '', ' pomme terre ');
INSERT INTO `categories` VALUES (2424, 'fr_FR', 'Pomme', '', '', '', ' pomme ');
INSERT INTO `categories` VALUES (2425, 'fr_FR', 'Fête foraine', '', '', '', ' fete foraine ');
INSERT INTO `categories` VALUES (2426, 'fr_FR', 'Paris', '', '', '', ' paris ');
INSERT INTO `categories` VALUES (2427, 'fr_FR', 'Vin', '', '', '', ' vin ');
INSERT INTO `categories` VALUES (2428, 'fr_FR', 'Amérique', '', '', '', ' amerique ');
INSERT INTO `categories` VALUES (2429, 'fr_FR', 'Indiens', '', '', '', ' indiens ');
INSERT INTO `categories` VALUES (2430, 'fr_FR', 'Navajos', '', '', '', ' navajos ');
INSERT INTO `categories` VALUES (2431, 'fr_FR', 'Roman et nouvelle', '', '', '', ' roman nouvelle ');
INSERT INTO `categories` VALUES (2432, 'fr_FR', 'Publication Assistée par Ordinateur', '', '', '', ' publication assistee par ordinateur ');
INSERT INTO `categories` VALUES (2438, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2439, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2440, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2441, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2442, 'fr_FR', 'Théâtre', '', '', '', ' theatre ');
INSERT INTO `categories` VALUES (2443, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2444, 'fr_FR', 'Poésie', '', '', '', ' poesie ');
INSERT INTO `categories` VALUES (2445, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2446, 'fr_FR', 'Histoire', '', '', '', ' histoire ');
INSERT INTO `categories` VALUES (2447, 'fr_FR', 'Homme', '', '', '', ' homme ');
INSERT INTO `categories` VALUES (2448, 'fr_FR', 'Histoire', '', '', '', ' histoire ');
INSERT INTO `categories` VALUES (2449, 'fr_FR', 'ADN', '', '', '', ' adn ');
INSERT INTO `categories` VALUES (2451, 'fr_FR', 'Biographies', '', '', '', ' biographies ');
INSERT INTO `categories` VALUES (2452, 'fr_FR', 'Crick, Francis Harry Compton', '', '', '', ' crick francis harry compton ');
INSERT INTO `categories` VALUES (2454, 'fr_FR', 'Watson, James Dewey', '', '', '', ' watson james dewey ');
INSERT INTO `categories` VALUES (2456, 'fr_FR', 'Bibliographie', '', '', '', ' bibliographie ');
INSERT INTO `categories` VALUES (2457, 'fr_FR', 'Biographies', '', '', '', ' biographies ');
INSERT INTO `categories` VALUES (2458, 'fr_FR', 'Einstein, Albert', '', '', '', ' einstein albert ');
INSERT INTO `categories` VALUES (2460, 'fr_FR', 'Relativité', '', '', '', ' relativite ');
INSERT INTO `categories` VALUES (2461, 'fr_FR', 'Trou noir', '', '', '', ' trou noir ');
INSERT INTO `categories` VALUES (2462, 'fr_FR', 'Poésie', '', '', '', ' poesie ');
INSERT INTO `categories` VALUES (2463, 'fr_FR', 'Poésie', '', '', '', ' poesie ');
INSERT INTO `categories` VALUES (2464, 'fr_FR', 'Littérature', '', '', '', ' litterature ');
INSERT INTO `categories` VALUES (2465, 'fr_FR', 'Histoire', '', '', '', ' histoire ');
INSERT INTO `categories` VALUES (2466, 'fr_FR', 'Ordinateur', '', '', '', ' ordinateur ');
INSERT INTO `categories` VALUES (2467, 'fr_FR', 'Turing, Alan Mathison', '', '', '', ' turing alan mathison ');
INSERT INTO `categories` VALUES (2469, 'fr_FR', 'Oppenheimer, Julius Robert', '', '', '', ' oppenheimer julius robert ');
INSERT INTO `categories` VALUES (2471, 'fr_FR', 'Bombe atomique', '', '', '', ' bombe atomique ');
INSERT INTO `categories` VALUES (2472, 'fr_FR', 'Physique nucléaire', '', '', '', ' physique nucleaire ');
INSERT INTO `categories` VALUES (2473, 'fr_FR', 'Newton, Isaac', '', '', '', ' newton isaac ');
INSERT INTO `categories` VALUES (2475, 'fr_FR', 'Gravitation', '', '', '', ' gravitation ');
INSERT INTO `categories` VALUES (2476, 'fr_FR', 'Images pieuses', '', '', '', ' images pieuses ');
INSERT INTO `categories` VALUES (2477, 'fr_FR', 'Images', '', '', '', ' images ');
INSERT INTO `categories` VALUES (2478, 'fr_FR', 'Soins médicaux', '', '', '', ' soins medicaux ');
INSERT INTO `categories` VALUES (2479, 'fr_FR', 'Poésie', '', '', '', ' poesie ');
INSERT INTO `categories` VALUES (2480, 'fr_FR', 'Philatélie', '', '', '', ' philatelie ');
INSERT INTO `categories` VALUES (2481, 'fr_FR', 'Timbres', '', '', '', ' timbres ');
INSERT INTO `categories` VALUES (2482, 'fr_FR', 'Numismatie', '', '', '', ' numismatie ');
INSERT INTO `categories` VALUES (2483, 'fr_FR', 'Pièces', '', '', '', ' pieces ');
INSERT INTO `categories` VALUES (2484, 'fr_FR', '~termes orphelins', '', '', '', ' termes orphelins ');
INSERT INTO `categories` VALUES (2485, 'fr_FR', 'usine', '', '', '', ' usine ');
INSERT INTO `categories` VALUES (2486, 'fr_FR', 'usines', '', '', '', ' usines ');
INSERT INTO `categories` VALUES (2487, 'fr_FR', 'Armement', '', '', '', ' armement ');
INSERT INTO `categories` VALUES (2488, 'fr_FR', 'Histoire politique', '', '', '', ' histoire politique ');
INSERT INTO `categories` VALUES (2489, 'fr_FR', 'Guerre', '', '', '', ' guerre ');
INSERT INTO `categories` VALUES (2490, 'fr_FR', 'Stratégie', '', '', '', ' strategie ');
INSERT INTO `categories` VALUES (2491, 'fr_FR', 'Retraite', '', '', '', ' retraite ');
INSERT INTO `categories` VALUES (2492, 'fr_FR', 'Vieillesse', '', '', '', ' vieillesse ');
INSERT INTO `categories` VALUES (2493, 'fr_FR', 'Echecs', '', '', '', ' echecs ');
INSERT INTO `categories` VALUES (2494, 'fr_FR', 'Guerres mondiales', '', '', '', ' guerres mondiales ');
INSERT INTO `categories` VALUES (2495, 'fr_FR', 'Jeux de stratégie', '', '', '', ' jeux strategie ');
INSERT INTO `categories` VALUES (2496, 'fr_FR', 'Temps libre', '', '', '', ' temps libre ');
INSERT INTO `categories` VALUES (2497, 'fr_FR', 'Récupération du Temps de Travail (RTT)', '', '', '', ' recuperation temps travail rtt ');
INSERT INTO `categories` VALUES (2498, 'fr_FR', 'Ages de la vie', '', '', '', ' ages vie ');
INSERT INTO `categories` VALUES (2499, 'fr_FR', 'Troisième âge', '', '', '', ' troisieme age ');
INSERT INTO `categories` VALUES (2500, 'fr_FR', 'Quatrième âge', '', '', '', ' quatrieme age ');
INSERT INTO `categories` VALUES (2501, 'fr_FR', 'Signes du vieillissement', '', '', '', ' signes vieillissement ');
INSERT INTO `categories` VALUES (2502, 'fr_FR', 'Signes du vieillissement', '', '', '', ' signes vieillissement ');
INSERT INTO `categories` VALUES (2503, 'fr_FR', 'Esthétique', '', '', '', ' esthetique ');
INSERT INTO `categories` VALUES (2504, 'fr_FR', 'Calvitie', '', '', '', ' calvitie ');
INSERT INTO `categories` VALUES (2505, 'fr_FR', 'Chirurgie esthétique', '', '', '', ' chirurgie esthetique ');
INSERT INTO `categories` VALUES (2506, 'fr_FR', 'Chirurgie reconstructrice', '', '', '', ' chirurgie reconstructrice ');
INSERT INTO `categories` VALUES (2507, 'fr_FR', 'Implants', '', '', '', ' implants ');
INSERT INTO `categories` VALUES (2508, 'fr_FR', 'Fabrication', '', '', '', ' fabrication ');
INSERT INTO `categories` VALUES (2509, 'fr_FR', 'Silicone', '', '', '', ' silicone ');
INSERT INTO `categories` VALUES (2510, 'fr_FR', 'Cryptage', '', '', '', ' cryptage ');
INSERT INTO `categories` VALUES (2511, 'fr_FR', 'poilus', '', '', '', ' poilus ');
INSERT INTO `categories` VALUES (2515, 'fr_FR', 'à»?àº?à»‰àº§', '', '', '', '  ');
INSERT INTO `categories` VALUES (2513, 'fr_FR', 'à»?àº¥àº°', 'à»?àº¥àº°', '', '', '  ');
INSERT INTO `categories` VALUES (2514, 'fr_FR', 'àºªàº´àº™àº¥àº°àº›àº° à»?àº¥àº°àº§àº±àº”àº—àº°àº™àº°àº—àº³', 'àºªàº´àº™àº¥àº°àº›àº° à»?àº¥àº°àº§àº±àº”àº—àº°àº™àº°àº—àº³', '', '', '  ');
INSERT INTO `categories` VALUES (2516, 'fr_FR', 'à»?àº?à»‰àº§', '', '', '', '  ');

-- --------------------------------------------------------

-- 
-- Structure de la table `classements`
-- 

CREATE TABLE `classements` (
  `id_classement` int(8) unsigned NOT NULL auto_increment,
  `type_classement` char(3) NOT NULL default 'BAN',
  `nom_classement` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id_classement`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Contenu de la table `classements`
-- 

INSERT INTO `classements` VALUES (1, '', '_NON CLASSE_');
INSERT INTO `classements` VALUES (2, 'BAN', 'tester');
INSERT INTO `classements` VALUES (3, 'EQU', 'test');
INSERT INTO `classements` VALUES (4, 'EQU', 'à»?àº?à»‰àº§àº¡àº°àº™àºµ');

-- --------------------------------------------------------

-- 
-- Structure de la table `collections`
-- 

CREATE TABLE `collections` (
  `collection_id` mediumint(8) unsigned NOT NULL auto_increment,
  `collection_name` varchar(255) NOT NULL default '',
  `collection_parent` mediumint(8) unsigned NOT NULL default '0',
  `collection_issn` varchar(12) NOT NULL default '',
  `index_coll` text,
  PRIMARY KEY  (`collection_id`),
  KEY `collection_name` (`collection_name`),
  KEY `collection_parent` (`collection_parent`),
  KEY `collection_id` (`collection_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `collections`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `comptes`
-- 

CREATE TABLE `comptes` (
  `id_compte` int(8) unsigned NOT NULL auto_increment,
  `libelle` varchar(255) NOT NULL default '',
  `type_compte_id` int(10) unsigned NOT NULL default '0',
  `solde` decimal(16,2) default '0.00',
  `prepay_mnt` decimal(16,2) NOT NULL default '0.00',
  `proprio_id` int(10) unsigned NOT NULL default '0',
  `droits` text NOT NULL,
  PRIMARY KEY  (`id_compte`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `comptes`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `coordonnees`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `coordonnees`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `docs_codestat`
-- 

CREATE TABLE `docs_codestat` (
  `idcode` smallint(5) unsigned NOT NULL auto_increment,
  `codestat_libelle` varchar(255) default NULL,
  `statisdoc_codage_import` char(2) NOT NULL default '',
  `statisdoc_owner` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idcode`),
  KEY `idcode` (`idcode`),
  KEY `statisdoc_owner` (`statisdoc_owner`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- 
-- Contenu de la table `docs_codestat`
-- 

INSERT INTO `docs_codestat` VALUES (10, 'àºšà»€àºˆàº²àº°àºˆàº»àº‡', 'u', 0);
INSERT INTO `docs_codestat` VALUES (11, 'à»„àº§àº«àº¡à»ˆàº¹àº¡', 'j', 0);
INSERT INTO `docs_codestat` VALUES (12, 'àºœàº¹à»‰à»ƒàº«à»ˆàº½', 'a', 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `docs_location`
-- 

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
  `cp` varchar(15) NOT NULL default '',
  `town` varchar(100) NOT NULL default '',
  `state` varchar(100) NOT NULL default '',
  `country` varchar(100) NOT NULL default '',
  `phone` varchar(100) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `website` varchar(100) NOT NULL default '',
  `logo` varchar(255) NOT NULL default '',
  `logosmall` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`idlocation`),
  KEY `locdoc_owner` (`locdoc_owner`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- 
-- Contenu de la table `docs_location`
-- 

INSERT INTO `docs_location` VALUES (1, 'àº«àºªàº°àº«àº¡àº¸àº”à»€à»€àº«à»ˆàº‡àºŠàº²àº”', '', 2, 'images/site/bib_princ.jpg', 1, 'Bibliothèque test de PMB', 'PMB Services', '24 & 26, place des Halles', '72500', 'CHATEAU DU LOIR', '', 'France', '+85621 251 405', 'pmb@sigb.net', 'http://www.sigb.net/', 'logo_default.jpg', 'logo_default_small.jpg');
INSERT INTO `docs_location` VALUES (2, 'Réserve', '', 2, '', 0, 'Bibliothèque test de PMB', '', '', '', '', '', '', '', 'pmb@sigb.net', 'http://www.sigb.net', 'logo_default.jpg', 'logo_default_small.jpg');
INSERT INTO `docs_location` VALUES (7, 'àº«àºªàº°àº«àº¡àº¸àº”à»€àº„àº·à»ˆàº­àº™àº—àºµà»ˆ', '', 2, 'images/site/bibliobus.jpg', 1, 'Bibliothèque test de PMB', 'rue de la culture', '', '72500', 'Château du loir', '', 'France', '02 43 440 660', 'pmb@sigb.net', 'http://www.sigb.net', 'logo_default.jpg', 'logo_default_small.jpg');

-- --------------------------------------------------------

-- 
-- Structure de la table `docs_section`
-- 

CREATE TABLE `docs_section` (
  `idsection` smallint(5) unsigned NOT NULL auto_increment,
  `section_libelle` varchar(255) default NULL,
  `sdoc_codage_import` varchar(255) NOT NULL default '',
  `sdoc_owner` mediumint(8) unsigned NOT NULL default '0',
  `section_pic` varchar(255) NOT NULL default '',
  `section_visible_opac` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`idsection`),
  KEY `idcode` (`idsection`),
  KEY `sdoc_owner` (`sdoc_owner`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

-- 
-- Contenu de la table `docs_section`
-- 

INSERT INTO `docs_section` VALUES (10, 'à»€àº­àº?àº°àºªàº²àº™', '', 2, 'images/site/documentaire.jpg', 1);
INSERT INTO `docs_section` VALUES (11, 'Documentaires Enfants', '', 2, 'images/site/documentaire.jpg', 1);
INSERT INTO `docs_section` VALUES (12, 'Romans Enfants', '', 2, 'images/site/enfants.jpg', 1);
INSERT INTO `docs_section` VALUES (13, 'Romans Jeunes', '', 2, 'images/site/sec3.jpg', 1);
INSERT INTO `docs_section` VALUES (16, 'Bande-dessinée Adultes', '', 2, 'images/site/sec1.jpg', 1);
INSERT INTO `docs_section` VALUES (17, 'Bande-dessinée Enfants', '', 2, 'images/site/enfants.jpg', 1);
INSERT INTO `docs_section` VALUES (18, 'H (Histoire locale)', '', 2, 'images/site/histoire.jpg', 1);
INSERT INTO `docs_section` VALUES (19, 'FR (Fonds Régional)', '', 2, 'images/site/sec4.jpg', 1);
INSERT INTO `docs_section` VALUES (20, 'Bande-dessinée Jeunes', '', 2, 'images/site/sec3.jpg', 1);
INSERT INTO `docs_section` VALUES (21, 'Romans policiers', '', 2, 'images/site/sec1.jpg', 1);
INSERT INTO `docs_section` VALUES (23, 'Romans Large vue', '', 2, 'images/site/large_vue.jpg', 1);
INSERT INTO `docs_section` VALUES (24, 'Romans & Romans étrangers', '', 2, 'images/site/sec1.jpg', 1);
INSERT INTO `docs_section` VALUES (25, 'Documentaires Jeunes', '', 2, 'images/site/documentaire.jpg', 1);
INSERT INTO `docs_section` VALUES (26, 'Albums Enfants', '', 2, 'images/site/enfants.jpg', 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `docs_statut`
-- 

CREATE TABLE `docs_statut` (
  `idstatut` smallint(5) unsigned NOT NULL auto_increment,
  `statut_libelle` varchar(255) default NULL,
  `pret_flag` tinyint(4) NOT NULL default '1',
  `statusdoc_codage_import` char(2) NOT NULL default '',
  `statusdoc_owner` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idstatut`),
  KEY `idcode` (`idstatut`),
  KEY `statusdoc_owner` (`statusdoc_owner`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- 
-- Contenu de la table `docs_statut`
-- 

INSERT INTO `docs_statut` VALUES (1, 'Document en bon état', 1, '', 0);
INSERT INTO `docs_statut` VALUES (2, 'En cours d''import/saisie', 0, '', 0);
INSERT INTO `docs_statut` VALUES (11, 'Détérioré', 0, '', 0);
INSERT INTO `docs_statut` VALUES (12, 'Perdu', 0, '', 0);
INSERT INTO `docs_statut` VALUES (13, 'Consultation sur place', 0, '', 0);
INSERT INTO `docs_statut` VALUES (14, 'En dépôt', 0, '', 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `docs_type`
-- 

CREATE TABLE `docs_type` (
  `idtyp_doc` tinyint(3) unsigned NOT NULL auto_increment,
  `tdoc_libelle` varchar(255) character set utf8 collate utf8_unicode_ci default NULL,
  `duree_pret` smallint(6) NOT NULL default '31',
  `duree_resa` int(6) unsigned NOT NULL default '15',
  `tdoc_owner` mediumint(8) unsigned NOT NULL default '0',
  `tdoc_codage_import` varchar(255) NOT NULL default '',
  `tarif_pret` decimal(16,2) NOT NULL default '0.00',
  PRIMARY KEY  (`idtyp_doc`),
  KEY `idtyp_doc` (`idtyp_doc`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

-- 
-- Contenu de la table `docs_type`
-- 

INSERT INTO `docs_type` VALUES (1, 'àº›àº·à»‰àº¡àºàº°à»àºŠàº” VDO', 14, 15, 2, '', 0.00);
INSERT INTO `docs_type` VALUES (12, 'àºàº°à»àºŠàº” VDO', 14, 15, 2, '', 0.00);
INSERT INTO `docs_type` VALUES (13, 'CD audio', 14, 15, 2, '', 0.00);
INSERT INTO `docs_type` VALUES (14, 'DVD àº—àº±àº™àº§àº²', 5, 15, 2, '', 0.00);
INSERT INTO `docs_type` VALUES (15, 'Oeuvre d''art', 5, 15, 2, '', 0.00);
INSERT INTO `docs_type` VALUES (16, 'Cartes et plans', 31, 15, 2, '', 0.00);
INSERT INTO `docs_type` VALUES (17, 'Cédéroms', 10, 5, 2, '', 0.00);
INSERT INTO `docs_type` VALUES (18, 'Périodique', 8, 5, 0, '', 0.00);

-- --------------------------------------------------------

-- 
-- Structure de la table `docsloc_section`
-- 

CREATE TABLE `docsloc_section` (
  `num_section` int(5) unsigned NOT NULL default '0',
  `num_location` int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`num_section`,`num_location`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `docsloc_section`
-- 

INSERT INTO `docsloc_section` VALUES (10, 1);
INSERT INTO `docsloc_section` VALUES (10, 7);
INSERT INTO `docsloc_section` VALUES (11, 1);
INSERT INTO `docsloc_section` VALUES (11, 7);
INSERT INTO `docsloc_section` VALUES (12, 1);
INSERT INTO `docsloc_section` VALUES (12, 7);
INSERT INTO `docsloc_section` VALUES (13, 1);
INSERT INTO `docsloc_section` VALUES (13, 7);
INSERT INTO `docsloc_section` VALUES (16, 1);
INSERT INTO `docsloc_section` VALUES (16, 7);
INSERT INTO `docsloc_section` VALUES (17, 1);
INSERT INTO `docsloc_section` VALUES (17, 7);
INSERT INTO `docsloc_section` VALUES (18, 1);
INSERT INTO `docsloc_section` VALUES (18, 7);
INSERT INTO `docsloc_section` VALUES (19, 1);
INSERT INTO `docsloc_section` VALUES (19, 7);
INSERT INTO `docsloc_section` VALUES (20, 1);
INSERT INTO `docsloc_section` VALUES (20, 7);
INSERT INTO `docsloc_section` VALUES (21, 1);
INSERT INTO `docsloc_section` VALUES (21, 7);
INSERT INTO `docsloc_section` VALUES (23, 1);
INSERT INTO `docsloc_section` VALUES (23, 7);
INSERT INTO `docsloc_section` VALUES (24, 1);
INSERT INTO `docsloc_section` VALUES (24, 7);
INSERT INTO `docsloc_section` VALUES (25, 1);
INSERT INTO `docsloc_section` VALUES (25, 7);
INSERT INTO `docsloc_section` VALUES (26, 1);
INSERT INTO `docsloc_section` VALUES (26, 7);

-- --------------------------------------------------------

-- 
-- Structure de la table `empr`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- 
-- Contenu de la table `empr`
-- 

INSERT INTO `empr` VALUES (1, '100001', 'Chewbacca', '"Chewie"', 'Millenium Faucon', 'Deuxième tourelle gauche', '01000', 'StarwarsTown', '', '', '', '', 'Pilote', 1900, 1, 4, '2004-01-21', '2006-06-26', 1, '100001', '1', '2004-01-21', '2006-08-24', '', 'fr_FR', 0, 0, NULL, 1, NULL);
INSERT INTO `empr` VALUES (2, '100002', 'Solo', 'Han', 'Millenium Faucon', 'Cabine du commandant', '01000', 'StarWarsTown', '', '', '', '', '', 1960, 7, 6, '2004-01-21', '2006-06-26', 1, 'hsolo', 'hsolo', '2004-01-21', '2006-07-21', '', 'fr_FR', 0, 0, NULL, 1, NULL);
INSERT INTO `empr` VALUES (3, '100003', 'Skywalker', 'Luke', '', '', '01000', 'StarWarsTown', '', '', '', '', '', 1970, 7, 4, '2004-01-21', '2006-06-26', 1, 'lskywalker', 'lskywalker', '2004-01-21', '2006-07-30', '', 'fr_FR', 0, 0, NULL, 1, NULL);
INSERT INTO `empr` VALUES (4, '100004', 'Leia', 'Princess', '', '', '01000', 'StarWarsTown', '', '', '', '', '', 1972, 7, 6, '2004-01-21', '2006-06-26', 2, '100004', '4', '2004-01-21', '2006-10-20', '', 'fr_FR', 0, 0, NULL, 1, NULL);
INSERT INTO `empr` VALUES (5, '100005', 'Kenobi', 'Obiwan', '', '', '01000', 'StarWarsTown', '', '', '', '', '', 1930, 7, 6, '2004-01-21', '2005-08-10', 1, '100005', '5', '2004-01-21', '2006-09-20', '', 'fr_FR', 0, 0, NULL, 1, NULL);
INSERT INTO `empr` VALUES (6, '100000', 'ROBERT', 'Eric', 'Le Gué Luneau', '', '37370', 'BUEIL-EN-TOURAINE', '', 'erobert@sigb.net', '02 47 24 89 29', '', 'Développeur PMB', 0, 3, 3, '2004-01-21', '2006-06-26', 1, 'erobert', '676767', '2004-01-21', '2006-12-20', '', 'fr_FR', 0, 0, NULL, 1, NULL);
INSERT INTO `empr` VALUES (7, '100007', 'TETART', 'Florent', 'Le Gué Luneau', '', '37370', 'BUEIL-EN-TOURAINE', '', 'ftetart@sigb.net', '', '', 'Développeur PMB', 1972, 7, 3, '2004-01-21', '2006-06-26', 1, 'ftetart', '727272', '2004-01-21', '2007-03-20', '', 'fr_FR', 0, 0, NULL, 1, NULL);
INSERT INTO `empr` VALUES (8, '100008', 'MICHELIN', 'Gautier', '35 rue Cornet', '', '80000', 'Amiens', '', 'gmichelin@sigb.net', '', '', 'Développeur PMB', 1977, 7, 7, '2004-01-21', '2006-06-26', 1, 'gmichelin', '777777', '2004-01-21', '2006-11-20', '', 'fr_FR', 0, 0, '2005-08-10', 1, NULL);
INSERT INTO `empr` VALUES (9, '100009', 'VIXIANE', 'keomany', 'Ban Akat', '', '856', 'Vientiane', '', 'manouvelle@yahoo.fr', '7741235', 'fgnfgn', 'Programmeur', 13082007, 7, 4, '2006-08-24', '2006-08-24', 1, 'kvixiane', 'manouvelle', '2006-08-24', '2007-08-24', 'this is a very good leader', 'la_LA', 0, 0, '2006-08-24', 1, NULL);
INSERT INTO `empr` VALUES (10, '100010', 'MEME', 'ME', 'dsfsdzf', 'sdvdsvdzs', '856', 'Vientiane', 'sdvsdv', '', '', '', '', 0, 7, 2, '2006-08-24', '2006-08-24', 0, 'mmeme', '', '2006-08-24', '2007-08-24', '', 'fr_FR', 0, 0, '2006-08-24', 1, NULL);
INSERT INTO `empr` VALUES (11, '100011', 'à»?àº?à»‰àº§', 'à»?àº?à»‰àº§', '', '', '', '', '', '', '', '', '', 0, 7, 2, '2006-08-28', '2006-08-28', 0, '', '', '2006-08-28', '2007-08-28', '', 'fr_FR', 0, 0, '2006-09-04', 1, NULL);

-- --------------------------------------------------------

-- 
-- Structure de la table `empr_categ`
-- 

CREATE TABLE `empr_categ` (
  `id_categ_empr` smallint(5) unsigned NOT NULL auto_increment,
  `libelle` varchar(255) NOT NULL default '',
  `duree_adhesion` int(10) unsigned default '365',
  `tarif_abt` decimal(16,2) NOT NULL default '0.00',
  PRIMARY KEY  (`id_categ_empr`),
  KEY `idcode` (`id_categ_empr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- 
-- Contenu de la table `empr_categ`
-- 

INSERT INTO `empr_categ` VALUES (8, 'àºœàº¹à»‰à»ƒàº«à»ˆàº?', 365, 0.00);
INSERT INTO `empr_categ` VALUES (9, 'à»€àº”àº±àº?àº™à»‰àº­àº?', 365, 0.00);
INSERT INTO `empr_categ` VALUES (10, 'àºžàº°àº™àº±àº?àº‡àº²àº™', 365, 0.00);
INSERT INTO `empr_categ` VALUES (11, 'àºžàº°àº™àº±àº?àº‡àº²àº™àºšàº³àº™àº²àº™', 365, 0.00);
INSERT INTO `empr_categ` VALUES (12, 'àº„àº»àº™àº«àº§à»ˆàº²àº‡àº‡àº²àº™', 365, 0.00);

-- --------------------------------------------------------

-- 
-- Structure de la table `empr_codestat`
-- 

CREATE TABLE `empr_codestat` (
  `idcode` smallint(5) unsigned NOT NULL auto_increment,
  `libelle` varchar(50) NOT NULL default 'DEFAULT',
  PRIMARY KEY  (`idcode`),
  UNIQUE KEY `idcode` (`idcode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- 
-- Contenu de la table `empr_codestat`
-- 

INSERT INTO `empr_codestat` VALUES (2, 'Communauté de Communes');
INSERT INTO `empr_codestat` VALUES (3, 'Commune');
INSERT INTO `empr_codestat` VALUES (4, 'Département');
INSERT INTO `empr_codestat` VALUES (5, 'Europe');
INSERT INTO `empr_codestat` VALUES (6, 'Hors europe');
INSERT INTO `empr_codestat` VALUES (7, 'France');

-- --------------------------------------------------------

-- 
-- Structure de la table `empr_custom`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `empr_custom`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `empr_custom_lists`
-- 

CREATE TABLE `empr_custom_lists` (
  `empr_custom_champ` int(10) unsigned NOT NULL default '0',
  `empr_custom_list_value` varchar(255) default NULL,
  `empr_custom_list_lib` varchar(255) default NULL,
  `ordre` int(11) default NULL,
  KEY `empr_custom_champ` (`empr_custom_champ`),
  KEY `champ_list_value` (`empr_custom_champ`,`empr_custom_list_value`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `empr_custom_lists`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `empr_custom_values`
-- 

CREATE TABLE `empr_custom_values` (
  `empr_custom_champ` int(10) unsigned NOT NULL default '0',
  `empr_custom_origine` int(10) unsigned NOT NULL default '0',
  `empr_custom_small_text` varchar(255) default NULL,
  `empr_custom_text` text,
  `empr_custom_integer` int(11) default NULL,
  `empr_custom_date` date default NULL,
  `empr_custom_float` float default NULL,
  KEY `empr_custom_champ` (`empr_custom_champ`),
  KEY `empr_custom_origine` (`empr_custom_origine`),
  KEY `champ_origine` (`empr_custom_champ`,`empr_custom_origine`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `empr_custom_values`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `empr_groupe`
-- 

CREATE TABLE `empr_groupe` (
  `empr_id` int(6) unsigned NOT NULL default '0',
  `groupe_id` int(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (`empr_id`,`groupe_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `empr_groupe`
-- 

INSERT INTO `empr_groupe` VALUES (1, 1);
INSERT INTO `empr_groupe` VALUES (2, 1);
INSERT INTO `empr_groupe` VALUES (3, 1);
INSERT INTO `empr_groupe` VALUES (10, 0);
INSERT INTO `empr_groupe` VALUES (11, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `entites`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `entites`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `equations`
-- 

CREATE TABLE `equations` (
  `id_equation` int(9) unsigned NOT NULL auto_increment,
  `num_classement` int(8) unsigned NOT NULL default '1',
  `nom_equation` varchar(255) NOT NULL default '',
  `comment_equation` varchar(255) NOT NULL default '',
  `requete` blob NOT NULL,
  `proprio_equation` int(9) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_equation`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Contenu de la table `equations`
-- 

INSERT INTO `equations` VALUES (1, 1, 'keomany', 'àº—àº»àº”àºªàº­àºšà»€àºšàº´à»ˆàº‡', 0x613a323a7b733a363a22534541524348223b613a313a7b693a303b733a333a22665f31223b7d693a303b613a353a7b733a363a22534541524348223b733a333a22665f31223b733a323a224f50223b733a393a22535441525457495448223b733a353a224649454c44223b613a313a7b693a303b733a313a2261223b7d733a353a22494e544552223b4e3b733a383a224649454c44564152223b4e3b7d7d, 0);
INSERT INTO `equations` VALUES (2, 1, 'keo', 'tester', 0x613a323a7b733a363a22534541524348223b613a313a7b693a303b733a333a22665f32223b7d693a303b613a353a7b733a363a22534541524348223b733a333a22665f32223b733a323a224f50223b733a393a22535441525457495448223b733a353a224649454c44223b613a313a7b693a303b733a313a2262223b7d733a353a22494e544552223b4e3b733a383a224649454c44564152223b4e3b7d7d, 0);
INSERT INTO `equations` VALUES (3, 1, 'à»?àº?à»‰àº§', '', 0x613a323a7b733a363a22534541524348223b613a313a7b693a303b733a333a22665f32223b7d693a303b613a353a7b733a363a22534541524348223b733a333a22665f32223b733a323a224f50223b733a393a22535441525457495448223b733a353a224649454c44223b613a313a7b693a303b733a313a2262223b7d733a353a22494e544552223b4e3b733a383a224649454c44564152223b4e3b7d7d, 0);
INSERT INTO `equations` VALUES (4, 4, 'à»?àº¡à»ˆàº™àº«àº?àº±àº‡àº§àº°', 'àº?àº”à»€àº«àº?à»‰à»ˆàº?àº”à»‰à»€àº«àº?àº±àºžàº´à»€àº°àº³àºžà»‰àºžàº°àº²à»ˆàº³àºžà»€àº°à»„àºžà»ˆàº°àº´àºªàº?à»€àº?àº”à»€àº«àº?à»€àº?àº”à»€àº¶àº«à»‰', 0x613a323a7b733a363a22534541524348223b613a313a7b693a303b733a333a22665f33223b7d693a303b613a353a7b733a363a22534541524348223b733a333a22665f33223b733a323a224f50223b733a393a22535441525457495448223b733a353a224649454c44223b613a313a7b693a303b733a313a2261223b7d733a353a22494e544552223b4e3b733a383a224649454c44564152223b4e3b7d7d, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `error_log`
-- 

CREATE TABLE `error_log` (
  `error_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `error_origin` varchar(255) default NULL,
  `error_text` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `error_log`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `etagere`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Contenu de la table `etagere`
-- 

INSERT INTO `etagere` VALUES (3, 'Loire', 0x4578706f736974696f6e207669727475656c6c6520737572206c61204c6f697265, 1, '0000-00-00', '0000-00-00', 1, '1 4 3 2');

-- --------------------------------------------------------

-- 
-- Structure de la table `etagere_caddie`
-- 

CREATE TABLE `etagere_caddie` (
  `etagere_id` int(8) unsigned NOT NULL default '0',
  `caddie_id` int(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`etagere_id`,`caddie_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `etagere_caddie`
-- 

INSERT INTO `etagere_caddie` VALUES (3, 5);

-- --------------------------------------------------------

-- 
-- Structure de la table `exemplaires`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

-- 
-- Contenu de la table `exemplaires`
-- 

INSERT INTO `exemplaires` VALUES (1, '33700004388761', 1, 0, 1, 'JR SOU', 13, 1, 1, 11, '2004-08-03', '0000-00-00', '', '', 1, 0, NULL, '2005-01-01 00:00:00', '2005-06-22 23:15:28');
INSERT INTO `exemplaires` VALUES (2, '22020295067', 2, 0, 1, 'JR HER', 13, 1, 7, 11, '2004-08-03', '0000-00-00', '', '', 2, 0, NULL, '2005-01-01 00:00:00', '2005-06-22 23:15:28');
INSERT INTO `exemplaires` VALUES (3, '33700003413727', 3, 0, 1, 'ER OPP', 12, 1, 1, 11, '2004-08-03', '0000-00-00', '', '', 2, 6, NULL, '2005-01-01 00:00:00', '2005-06-22 23:15:28');
INSERT INTO `exemplaires` VALUES (4, '33700003470461', 4, 0, 1, 'R ZIM', 24, 1, 1, 12, '2004-08-03', '0000-00-00', '', '', 2, 0, NULL, '2005-01-01 00:00:00', '2005-08-10 22:25:04');
INSERT INTO `exemplaires` VALUES (5, '33700003719453', 5, 0, 1, 'JR CAU', 13, 1, 1, 11, '2004-08-04', '0000-00-00', '', '', 1, 0, NULL, '2005-01-01 00:00:00', '2005-08-10 22:44:44');
INSERT INTO `exemplaires` VALUES (6, '33700003550072', 6, 0, 1, 'RX RIO', 21, 11, 7, 12, '2004-08-04', '0000-00-00', 'Reliure bibliographique très abîmée. Comparer coût réparation et rééquipement. ', '', 1, 0, NULL, '2005-01-01 00:00:00', '2005-08-10 22:44:29');
INSERT INTO `exemplaires` VALUES (7, '33700004389030', 7, 0, 1, 'JRK TAY', 13, 1, 7, 11, '2004-08-04', '2004-08-04', '', 'EUR 3,8', 1, 0, NULL, '2005-01-01 00:00:00', '2005-08-10 22:44:44');
INSERT INTO `exemplaires` VALUES (8, '33700004389026', 7, 0, 1, 'JRK TAY', 13, 1, 1, 11, '2004-08-04', '0000-00-00', '', 'EUR 3,8', 1, 0, NULL, '2005-01-01 00:00:00', '2005-08-10 22:44:44');
INSERT INTO `exemplaires` VALUES (9, '33700003669468', 8, 0, 1, 'BD ADA', 16, 1, 7, 12, '2004-08-04', '2004-08-04', '', 'EUR 11,', 2, 0, NULL, '2005-01-01 00:00:00', '2005-08-10 22:25:04');
INSERT INTO `exemplaires` VALUES (10, '33700003669484', 9, 0, 1, 'BD ADA', 16, 1, 1, 12, '2004-08-04', '0000-00-00', '', '', 1, 0, NULL, '2005-01-01 00:00:00', '2005-08-10 22:44:29');
INSERT INTO `exemplaires` VALUES (11, '33700003669485', 10, 0, 1, 'BD ADA', 26, 12, 1, 12, '2004-08-04', '2004-08-04', '', '', 2, 0, NULL, '2005-01-01 00:00:00', '2005-06-22 23:15:28');
INSERT INTO `exemplaires` VALUES (12, '33700004243164', 11, 0, 1, 'JBD LAM', 17, 1, 1, 12, '2004-08-04', '2004-08-04', '', '10,40 €', 2, 0, NULL, '2005-01-01 00:00:00', '2005-08-10 22:44:29');
INSERT INTO `exemplaires` VALUES (13, '33700003868672', 12, 0, 1, 'JBD GAL', 20, 1, 1, 11, '2004-08-04', '0000-00-00', '', '', 2, 0, NULL, '2005-01-01 00:00:00', '2005-06-22 23:15:28');
INSERT INTO `exemplaires` VALUES (14, '33700004243347', 13, 0, 1, 'EBD GEE', 17, 1, 1, 11, '2004-08-04', '0000-00-00', '', '', 1, 0, NULL, '2005-01-01 00:00:00', '2005-08-10 22:44:44');
INSERT INTO `exemplaires` VALUES (15, '33700003453202', 14, 0, 1, 'J 808 PAR', 25, 1, 1, 11, '2004-08-04', '0000-00-00', '', '', 1, 0, NULL, '2005-01-01 00:00:00', '2005-08-10 22:44:44');
INSERT INTO `exemplaires` VALUES (16, '33700004118010', 15, 0, 1, '582 NUR', 10, 1, 1, 12, '2004-08-04', '0000-00-00', '', '', 2, 0, NULL, '2005-01-01 00:00:00', '2005-06-22 23:15:28');
INSERT INTO `exemplaires` VALUES (17, '33700003286792', 16, 0, 1, '967 OVE', 10, 1, 1, 12, '2004-08-04', '0000-00-00', '', '', 2, 0, NULL, '2005-01-01 00:00:00', '2005-08-10 22:44:29');
INSERT INTO `exemplaires` VALUES (18, '3370000451290', 18, 0, 14, 'DVD TF1', 10, 1, 1, 12, '2004-08-04', '0000-00-00', 'vérifier la présence du livret avant de prêter', '', 2, 0, NULL, '2005-01-01 00:00:00', '2005-06-22 23:15:28');
INSERT INTO `exemplaires` VALUES (19, '3370000451291', 17, 0, 16, '910 MIC', 10, 1, 1, 12, '2004-08-04', '0000-00-00', '', '', 2, 0, NULL, '2005-01-01 00:00:00', '2005-06-22 23:15:28');
INSERT INTO `exemplaires` VALUES (20, '3370000451292', 19, 0, 17, 'CDROM LOI', 10, 1, 1, 12, '2004-08-04', '0000-00-00', '', '', 2, 0, NULL, '2005-01-01 00:00:00', '2005-06-22 23:15:28');
INSERT INTO `exemplaires` VALUES (21, '3370000451294', 0, 2, 18, 'MAG GEO', 10, 1, 7, 12, '2004-08-04', '0000-00-00', '', '', 2, 11, '2006-09-04', '2005-01-01 00:00:00', '2006-09-04 16:43:20');
INSERT INTO `exemplaires` VALUES (22, '3370000451293', 0, 1, 18, 'MAG GEO', 10, 1, 1, 12, '2004-08-04', '0000-00-00', '', '', 2, 0, NULL, '2005-01-01 00:00:00', '2005-08-10 22:25:04');
INSERT INTO `exemplaires` VALUES (23, '3370000451295', 42, 0, 15, 'MANUSCRIPT', 18, 13, 1, 12, '2004-08-04', '2004-08-04', 'Document sorti du fond ancien', '', 2, 0, NULL, '2005-01-01 00:00:00', '2005-06-22 23:15:28');
INSERT INTO `exemplaires` VALUES (24, '3370000451296', 44, 0, 13, 'CD COC', 10, 13, 1, 12, '2004-08-04', '0000-00-00', '', '', 2, 0, '2006-08-28', '2005-01-01 00:00:00', '2006-08-28 14:35:57');
INSERT INTO `exemplaires` VALUES (25, '3370000451301', 46, 0, 13, '780 BRI', 10, 1, 7, 10, '2004-08-04', '0000-00-00', '', '', 1, 0, '2005-08-10', '2005-01-01 00:00:00', '2005-08-10 22:59:36');
INSERT INTO `exemplaires` VALUES (26, '3370000451298', 47, 0, 15, '590 RUS', 10, 13, 1, 12, '2004-08-05', '0000-00-00', '', '', 2, 0, NULL, '2005-01-01 00:00:00', '2005-06-22 23:15:28');
INSERT INTO `exemplaires` VALUES (27, '3370000451299', 48, 0, 15, '680 FAV', 10, 13, 1, 12, '2004-08-05', '0000-00-00', '', '', 2, 0, NULL, '2005-01-01 00:00:00', '2005-06-22 23:15:28');
INSERT INTO `exemplaires` VALUES (28, '3370000451300', 50, 0, 1, 'JR COC', 13, 1, 1, 12, '2004-08-05', '0000-00-00', '', '', 2, 0, NULL, '2005-01-01 00:00:00', '2005-06-22 23:15:28');
INSERT INTO `exemplaires` VALUES (29, '3370000451302', 51, 0, 1, '590 BOU', 10, 1, 1, 12, '2004-08-05', '2004-08-05', '', '', 2, 0, NULL, '2005-01-01 00:00:00', '2005-08-10 22:25:04');
INSERT INTO `exemplaires` VALUES (30, '33700004500167', 53, 0, 1, 'RK ROB', 10, 1, 1, 12, '2004-08-05', '2004-08-05', '', '', 2, 0, NULL, '2005-01-01 00:00:00', '2005-06-22 23:15:28');
INSERT INTO `exemplaires` VALUES (32, '6438646236', 2, 0, 1, 'R HER', 13, 1, 1, 12, '2004-09-13', '2004-09-13', '', '', 2, 0, NULL, '2005-01-01 00:00:00', '2005-06-22 23:15:28');
INSERT INTO `exemplaires` VALUES (33, '1005', 58, 0, 1, '1', 10, 1, 1, 12, '0000-00-00', '0000-00-00', 'tester ', '100', 2, 0, NULL, '2006-08-22 17:46:35', '2006-08-22 17:47:38');
INSERT INTO `exemplaires` VALUES (34, '11586-11592', 60, 0, 1, '000', 10, 1, 1, 12, '0000-00-00', '0000-00-00', 'à»€àº§àº»à»‰àº²àº?à»ˆàº½àº§àº?àº±àºšàºžàº»àº‡àºªàº²àº§àº°àº”àº²àº™', 'à»‘à»’à»’à»’', 2, 0, NULL, '2006-08-24 18:11:59', '2006-08-28 14:29:04');
INSERT INTO `exemplaires` VALUES (35, '11586-11593', 60, 0, 1, '009', 10, 1, 1, 12, '0000-00-00', '0000-00-00', 'gh,jhg,jdh', '100000', 2, 0, NULL, '2006-08-24 18:14:25', '2006-08-25 10:05:57');
INSERT INTO `exemplaires` VALUES (41, '00111', 59, 0, 1, '099', 10, 1, 1, 12, '0000-00-00', '0000-00-00', '', '', 2, 0, NULL, '2006-10-05 17:42:20', '2006-10-05 17:42:20');
INSERT INTO `exemplaires` VALUES (37, 'PE37', 63, 0, 1, '000', 10, 1, 1, 12, '0000-00-00', '0000-00-00', 'àº?àº²àº™à»ƒàº«à»‰àº¢àº·àº¡àº”à»ˆàº§àº™', '24000', 2, 0, '2006-08-24', '2005-01-01 00:00:00', '2006-10-05 17:32:32');
INSERT INTO `exemplaires` VALUES (38, 'PE38', 60, 0, 1, '', 10, 1, 1, 12, '0000-00-00', '0000-00-00', 'Prêt express', '', 0, 0, '2006-08-24', '2005-01-01 00:00:00', '2006-08-24 18:47:30');
INSERT INTO `exemplaires` VALUES (39, 'PE39', 64, 0, 1, '', 10, 1, 1, 12, '0000-00-00', '0000-00-00', 'Prêt express', '', 0, 0, '2006-08-24', '2005-01-01 00:00:00', '2006-08-24 18:54:00');
INSERT INTO `exemplaires` VALUES (40, '11602-03', 65, 0, 1, '001', 10, 1, 1, 12, '0000-00-00', '0000-00-00', '', '15000àº?àºµàºš', 2, 0, NULL, '2006-10-05 17:19:56', '2006-10-05 17:19:56');

-- --------------------------------------------------------

-- 
-- Structure de la table `exercices`
-- 

CREATE TABLE `exercices` (
  `id_exercice` int(8) unsigned NOT NULL auto_increment,
  `num_entite` int(5) unsigned NOT NULL default '0',
  `libelle` varchar(255) NOT NULL default '',
  `date_debut` date NOT NULL default '2006-01-01',
  `date_fin` date NOT NULL default '2006-01-01',
  `statut` int(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id_exercice`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `exercices`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `expl_custom`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `expl_custom`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `expl_custom_lists`
-- 

CREATE TABLE `expl_custom_lists` (
  `expl_custom_champ` int(10) unsigned NOT NULL default '0',
  `expl_custom_list_value` varchar(255) default NULL,
  `expl_custom_list_lib` varchar(255) default NULL,
  `ordre` int(11) default NULL,
  KEY `expl_custom_champ` (`expl_custom_champ`),
  KEY `expl_champ_list_value` (`expl_custom_champ`,`expl_custom_list_value`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `expl_custom_lists`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `expl_custom_values`
-- 

CREATE TABLE `expl_custom_values` (
  `expl_custom_champ` int(10) unsigned NOT NULL default '0',
  `expl_custom_origine` int(10) unsigned NOT NULL default '0',
  `expl_custom_small_text` varchar(255) default NULL,
  `expl_custom_text` text,
  `expl_custom_integer` int(11) default NULL,
  `expl_custom_date` date default NULL,
  `expl_custom_float` float default NULL,
  KEY `expl_custom_champ` (`expl_custom_champ`),
  KEY `expl_custom_origine` (`expl_custom_origine`),
  KEY `expl_champ_origine` (`expl_custom_champ`,`expl_custom_origine`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `expl_custom_values`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `explnum`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- 
-- Contenu de la table `explnum`
-- 

INSERT INTO `explnum` VALUES (1, 42, 0, 'Reproduction basse qualité', 'image/jpeg', '', 0xffd8ffe000104a46494600010201004800480000ffe11bd54578696600004d4d002a000000080007011200030000000100010000011a00050000000100000062011b0005000000010000006a01280003000000010002000001310002000000140000007201320002000000140000008687690004000000010000009c000000c80000004800000001000000480000000141646f62652050686f746f73686f7020372e3000323030343a30383a30342031383a33343a34340000000003a001000300000001ffff0000a002000400000001000001ffa003000400000001000001510000000000000006010300030000000100060000011a00050000000100000116011b0005000000010000011e012800030000000100020000020100040000000100000126020200040000000100001aa70000000000000048000000010000004800000001ffd8ffe000104a46494600010201004800480000ffed000c41646f62655f434d0002ffee000e41646f626500648000000001ffdb0084000c08080809080c09090c110b0a0b11150f0c0c0f1518131315131318110c0c0c0c0c0c110c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c010d0b0b0d0e0d100e0e10140e0e0e14140e0e0e0e14110c0c0c0c0c11110c0c0c0c0c0c110c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0cffc00011080054008003012200021101031101ffdd00040008ffc4013f0000010501010101010100000000000000030001020405060708090a0b0100010501010101010100000000000000010002030405060708090a0b1000010401030204020507060805030c33010002110304211231054151611322718132061491a1b14223241552c16233347282d14307259253f0e1f163733516a2b283264493546445c2a3743617d255e265f2b384c3d375e3f3462794a485b495c4d4e4f4a5b5c5d5e5f55666768696a6b6c6d6e6f637475767778797a7b7c7d7e7f711000202010204040304050607070605350100021103213112044151617122130532819114a1b14223c152d1f0332462e1728292435315637334f1250616a2b283072635c2d2449354a317644555367465e2f2b384c3d375e3f34694a485b495c4d4e4f4a5b5c5d5e5f55666768696a6b6c6d6e6f62737475767778797a7b7c7ffda000c03010002110311003f00d9cabbac3aa75782eaabb5d8f7161dad0ff541aebc57fe91cd6369dd6d8eb5fb6d7fabe97e8fd24276150fea7fb4271b2b3abb0b0dda7a9f652da5acc6b3d47dff0067cb67e9bf5bfd364fd8bfc27e9fd3acb9f664b6a15535dae716eaec52d658480efcfb7f9a63dfff006d3fd3ff0007bd3558b93692dbaccb755b9d373e8ade46a6db2a3b6bdf75576efb3fb19beaff008bfd22836f0672d8cacbb9b4bbecb532ebc6c02b6383e09733d4ded8a7f37d5f7b2dff008753b7271ddea3a8344d2459687581f0c639edcaaec3b58cc6db5d4f67da9cff00529bebbff57fd5bf4c9dd1e8652f0412cdefdbfaa90ea9d631ed63839946eb29c6a77d7bbf9bff0002b3c3039b8cec5caadc0d218edd8ee3eb5862c7e617ef66eb7f4594ff006d5e9efc8c8c8b1214a7472ac6d34b1efaa86ef716bdecb496d55b99616e6dbec0d733d56331f67f35fa5f57d5b549ecb699165b8f159335c8690c25fe8bdcfb6c6edf5daddff43d3fa7f67542badb696b715b43ad7d9e9861c57b9c693ef6c35d6b1af7b1cf7e4fa97b2ac4baab6efe6558b5adc46640a324d963064380f443ad24bafbb1ab1f68b2a7643f1ddfabd38eff00e7bd3ae8f599fcea4864fbfd26dce75b4faac6b61a03ec6b9c0b3d6ab7ee637d5d967e82b7bfd4f7fad67e8eb4ee0fa697b9d9589919b5b8d4d2f3e954ebda06eaeeadb77e8acd9ff71d957bff0049e97d9ecf4552657eaf59bde32e050c63db5369219bc875aec4c8a286b596fb3d3f4b2ff3ff00eb7b15ebfa87d9321d4baf3b1e4371c371f59ab653d47d4f66df4b1adb69b7d6ddefc6bbf41eafd9bd44129db6d755970b73687baabc5605b50ac3458ddf8f8748f5daeb1eff005a8fd2fa96bfd4f52bfd1ff821557578f4515ddd50586ba5b53ac79a66c74fa54e5bdb739eff00b558ff0066ef5ec6657f36fafd451bb36bf480391a376d82b143ac2ed62bdcd6ed7bb6dfb32dbb7d1b697d3fe8fe9c9ce6df5ec393b4e4bc47a743892496d75b99ea7aad67a794caeef51ffce3eafe6fd1495a2d7e5e064d36d56f51069b58fdfe8ecadbe9dac67e89f68a9fb5ff00432319f5dbf6d67af57f81b2a4b17a83f268c6bafccbabf505e3231df4368c83b8b998dbbd165bf64ba8ddea3fd3bbf48ff4b27d9fcd285390fc9a5d6e36596e46558eb1b7b71e5b67aa5b8f837be835fe6d0dc765b7fd3ab67eb5fa14f50aef7e259566657d8cb5b2eb2970366d75ad61b726ca98ef5ec7329c77b3fed4ff00e852284833d95dff00a5fb6edc3018320541e2cde76e4fe871e8dcff00e67f9df4fd37fe92da3f48a7f690fb8fe9720d6e6d6f6d9e6e2fdf4371eca7f44ead82bdf67ee59fa4fd22a94e3d5760b1ecb3232c7a1f68fb4b6b731ce6ed73aababa3d26b7d5752f65f5e36dfd25ecaff33f45641b8fe9e2d4e78cac87b7d335e47a840b458dc7a464edacb7f56b3d4f5edaedfd2d1e9e55891484afbf1adfd332ec8d8d7db35ba6b03d279aedaac6b6bf732a756f650c7fe9bd1ff8cb166d5917b2cc57befc83bfa96252e7e436059390cf4fecf536b65757e85bb1ff00cdfb2abaff00e7ff009db96e35a480f65b76f76c75a2ddac69687b9d6eeb5cc757e9bbf57b77ff003acf43fd1ac978c76e5e00734d6fb7a974f7d62d7ef76e17b5ae635a1fb596ec759efdbea3ebabfd1fe9121b8f35743e4fffd0dfca65d0d70afd42c8dada9de93a7ddc59a6edccdea3bc16dd5dadcc77acdf4cb9af801a48b7d4af7fd1b18ffd1bbfe0fd446bf3312ead85edb1cc96bbf498c6c12c0ff736eaacdd5db5eefe77e87e67f84544d9814d90eb49bf42eaa9a6eae7d28166cb1b66cfd56bb6dbfd1f5fd4bbd4f4ff0049b1417dd9dd4bb3b2463b9c1f99792ff55aea3d1de09b3d56377fe8d9b71587ecb974db5d9eafd0afd57aa194cba4b31df958ce2fbb1b05b4b1ae67a66a6d9bf7ec7bedc7d98ffa1733f5ba3f58c6f53f4b75688db3a71655ebb8dd7b7d3b31eaf59dedb9ad6e3d5b45b6d8fa7dd6ecf5aff5becfebfe9fd4b7d4517518f6d6eaab395736dacbeaa9d7d2ef55b51df56463dc7237ed7fa7eb539bfe0ffe8257d554ab6bbdf5578565b94fa72ed38b7fa6caa968a7209aff004bb2a635cda9ae66355633f58fb559ea7fc1ab59996cb71aacd2fcfbe97c38d7b2a6d9501eadbf6b73053eb6faedaaac7ff80f53d5548371ed6e35b60b6eaadb1976234d81a2cd83edd4df486bbd565adc573afd8cfd2555a3b8fad9018f65f5efb436b73ef2f76d90caed1bf65bea3bddea636ebbfe31e92a91e0bb2eca6d763e5e4d06dc52dc13654dd8eb9df6adac70be967af77f32ff00b36c67f33f9ffa650b32eebf31971b2fad9550db5b48a838b0b835dfd23d0b29b76d35e5606c67f4afd0ff00a565c9d97d471eac8bea7b0bb6bdad7bf61adee1918ae6b3283e9fd63d2bb22af477effd27e891e8a2eaabb71fd3cac87fa8e2e2eb99eb3666c66db3ed0d67bbfa37a7ea7abe9e3efb6af492b5535b1f329ad98e7ed1976d173ebf4dbb1aedad23ed3163ada9d76cc867e82cb77daf66ff00f05e9fa88a2cbeaa69b6cbf3adb686fe96e6d6d96eea9fead8cc3f498cc87fa95d7fab3996fd9ee7d1653fe16b4e6a6b4371a1e1d92e6d4ec7bad686be9bcbebc89c7f5dacdeec56e4d787bdbe9fa9f43f99b94b1b2f11b4d5d4e97e55d8973dbb2eb329be9b0175be27db8aec9a69a3f4bfa2b6eb6aff0007ea7a814cb7063bd2c7b335ef6b5d900b2aa9ac78d6bb3118ff004ebad9f6bb5fbff4ccf63ff49eafa5fa25360babb8beaab34b6d22c2c73ab23d7b1e0be29dfeef5b77e93fed3625946fa10ecadb6dc777af914530e79bb3e590d6d990edec96d153f6e5fa5b6dff00b494e3e455fe0d2bba4b2c227158eacd4d65991f69732e756c2726adb637db5d54e437df758db3ed5eaa4a59b7e6d6fae8b3132ee74b9f5dedb9d0761ae1d6d70cd8ff00d2fbebaff43ff712bf437d3446ec6ca77a3553d32df4dcf76f26d700d22ca8377b1a2cdd8af75f6d9e8fb365585fabfe8d593fb1854e2cba9ae926b75939efd82ab5a6b6dec7bb733dd5dbe9e355fcde47a9fce56a81c8e9d47b1d938353b1ec0035f68c8f5696535b36bdcfaabb29f4bd5aadb5d4ff00dc7a59e8feb7eaa4149e9e9768f5fd4c5a9ae0e70a9971f55be9b5eef4a0435cc75d45d77a3436dfd1bff42a8629c7bf27072a9355cf764613cb1ad0e76db1d8b67aceaed25dbebf57f9f6fd0b3fe1bd4561f97821f7fa671e9b70c331dd6538f63df57a57dadf4d8cb37d7653edba9abdbbf1f2bed167f8259b9f91d4f27aa74aba8ccbbec55f52c3ab270cd618c23ed0cd9966f6ff00394dd76cf471dff4ff00a533fc27a446f4a2743d5fffd1df633a97a74fd85bf67a1cfdb7d8d6b5cd0d732d736c76f0e7fb6d67d0aaaf52c7fa557f844b1dbb5acaeeeab01cf7d871ce33dad6d8fd68fb3b5db7d2a99efb3d3b1dfa2ff8340caac5f452cf49ceae4d34bf7ec71373365adaff007fd4d9fa37d7fa4fd17e851b1703abd96bcb71321b492c26cb1d539af0347fa54db635edf6fb9ffccfafff0018a00ce533325d554d76065e2dd59f4acb9afa9d51731f5d9eab83c967a96e4dbe8bb67f3945767bff00c1a85993856e05fd3f25b82e656c756dac39c00f45c3d3c77fb1ef73f17d1fd232977abf68a99fa1ad15d4dd8e6c6e6e666d8fbc35e2bad8c73ab136cd6e7d4cdbea3fd466cfe72caa8a995ffa4de338943bf458b65ccb997d22edf507b21e5991712edb5d5badc577f3acff00cfa9297cdbb11d757955bf103cbcb196cec3e85ae1916bac6b99fd2bd6ab17d4d9ff000aabbb3312caeda322ec334d8031ad6b1c0eebea355feb5d5d5b6ab2da1d6fd9afaff9cc74d915b06663d4db2dfb3bec71b00c6869aaca9b6521ae7d5b99e95cdb2b67fda8fd3fe97f9bad1319d4e0e3565992f01831a8ade6a73a5f639d898ccdee68f51f7bebfd61effd2d5e9fa9fa3fb524ae886cb3a7b2cf4eeab15ee0f6b9957a7a383d8eb1aea2c757fa4bf635d665ec67e869f4909f755353dd4537de010db4fe8c39963998f73bdcd7337d5555436babe9fa98ff00e0d5b7dac65c2a36d9537183da5cca1cd245acf46ab311cf63dd4bb17f4f5eff00d17beef7d767a4ad1cb6ec696df94f6b46d74d321ae66c1baedb5973adb1bee6fa9ecc9fd27fa3412e5d6c2e73f36c663328b1fbcfda2bfd2825b462dbb1f6efbacf56dabe97a3b1feb7fc1fbc8e6dcdd96d9760934308de6a000f58b31eeb3e932b6b3237534595ecb37d9e9fa4b56b7d4fc9198ecbbaf21be89a3d3747b4fa8e78a5a07a0f7b59e8fda6cfd1fbff009e4c2bb2ea0582ec873722a6cd566381617b017dbf68abd014d577beaabd3fe6acb31bfd2d6f495e0d1ba9bf716e4861a6eaed6bac38e4b5a1ada6a6e3e558d8f7dadc87fa1b19b3d2ff0088b3d411c4697bdae14640aecb1cf7b812c60a6cf59d8feabac66cbf11f47ad753ff005daab561f4e5db6595d9979032722bf56caec1efa5b61751fa2b1b506d75b5cdc8c767fdbffcdfa48bfb4f1a8add57af94fb430e43df4d6c05ce7b9d88fb09757e8b375fb327d957e8ecff0009e9fe8915206b2fadf54369a9eedc6c70a18ea9b5867da36e51b367a2cdd66faed67f84fd0fa7f4eb46bdd9eeaebaacce00c07b2ea6875561aecdcc867a41db3d567b1f6d7e8d947e8b2bd9fa3b14dbd61c1c1c1b92f2d21c2b731a20b8fbac7358d6eefb3bbf4b6ffab10864e4d98ecc5a1f9ed1ea6ff59cf697bc8bbed1655693e9daea6d631d4d56d5fcdd167a1e9fa490fb108d998ec6b29af2ec36d41a0dae6d27d81ffa48c8b7f9c7fdb1e1eef53fd27a97e4fa3ea2c7fd9eecdea3d22cb3a8d961c2cac4bcd018365f6fab531f99bff43fe0ff0033d1d8cdf67fc2fa9d058d6bd9b8b731bbdee7381b5b2c244d9708796d75ecfd13fd2ff0767f36a958e6b32f176bada272f16cdaf74b0b5f914d2da995d0ed8c66f7fe8ffedcb3d4f4d0075511a3ffd2d3675118f86c3e9d365ac2e7d02fdcdf7b1b3ea57757fcdbd95bb23dff00ce7b11f2ba8b9d459e956fc1aab63a9adecc93516d6f7b6fbada6e7fa75fad5b31fd5a6addfccfe8bf4352a65d4baba80731d55a26cf59a5c36ba036b647b7f35dfce7e67e9362bb4e0f47c91b5f4d3965cd0d7b3e909d93639f38f76ddbf459eefe63f3ff004aab8ab6c1d9bb858571787db8d7b1f3bdac6da1ed6b1cff00d1d6e1758d6d7e9e2ecb2faacfd233fc17aaaf5e76d8c632acb7bf73c18b6b6c5760df639ccf57d3f4b0ddb2bafd6fe61ffcd7a9fe173b25ceb2d190cc6a8b6697bac6deeaec165365b67a375ff42ea6ba9f4eca99f4ecfe77fc1a01c8c4148c5681535ac65a69a721f65e3612ca36fe99af757a3ffc3fd93fe36c45092eb0e4f503596e537ed22d646ea41ad8c612cd9ee6fa565b5b3f4166f7fa5bff004de9a9b03b28586cb7285363ac6e39736a96167a3f6aaf7b1aeaf7fa95fa18fedb7d3f472ff4ff00cdaa182e6e5e632bc619553297efc9b9f7b5f69a852f73e86b9b7bfd3f52eb2aaf653fe83d5c8bababd3f54d9b94eb721b8f8d45d5d38f46dc773ad2d6882f67a77817fd36b9957e977d96feb1fe8fd44949b2b27a9500bed75b04030fa2a70fdcd2ca98ff0051ff009d77e9acff0048a16e4d9452e638655d63aea6bcb865750a9ae6377e455b6b2efa57faaf6ff3d6fe97ecfe8d38fe9551b2cc96b68b2ba6d6d8c7c3c8bda59b0d5bac6d551b3d0c86fda4369b5b7b3f4b5bfed1f4ff009b3307db2c14166654ec865a4582e6bb6969adcf787b9ef75566ccaf531ff58fd0b2bb3d9fcca4a5cb30f29b58b465df654e16d16343584595968a2bb7d8cadbebe46cf43ed0cb3f49ff00055a97516dd5d1eb63d19366a0fa44b19e98616b9dea58cf736af4def6318df5fed1e97fc27a960aaa2d7dcdcba29bed75757a82af5c34b1e7d3aedabd3f536d76e352f7e53a8bbf9dc9aaaff8c51c81765dd5c332aaaea731cdfd29e2adfb1b60fb4eedd6fa8e65f6fd3f51ecb3d4fd054fad792bcd58750b729b5ddf686b25ed7d96bda0b03417bdaed96b98d6bdd5fbf7ff00c5ab38ceaac2d6e56364d1911532c607b76b5cf2f7baba9cc7b3d5fd233d1c9c8f459eab3d2b2efd0aa2cc6732aa716fc4bafaae6be8b6bb6ffd2eeb587d3c6ca636eb59533d1b6ca9f7fd3a6eaeb7ff0086fb4a469baccb6d0fc3b1b58bf7d8fb9ee76db0b3d5c7bab736dbab6faaebafa723f4ccd8929b8ec3b2ec469761bebcd9aedf4064ef60b697b6eaab658d71fd1647efd4dff8f511d3ab79be87e23dac15fe8db6e4efb2cac127d4750c0e7d55d963367aca385531f806eb3a73a8a726b7fa8c65d1b43c33d661f4fdcdb6bb6db77fa7fa767d9adb29fe7506e7df9363f26dc1a582c60addef93fa33be96d32ddae656f75f92fdff00cdfd0ff0a86aa62ec77bf67e84170730b7f4e7706d2e7594ff0038df773fa5ff00d52a938b033a75aeac35d99d4b09d417493e8d7954d543abdff47d4d973ff9747a69adc619158a723d0a45ef6bfed01db4d18557d1ce77aadf7e4b6adf8d55adf4f169f57d455b232b27edbd3db6d54d4fb3abf4fa9edc7743195b2eb5d89436bda7d9e9d3eaff0039f4ff0049fc8446b21e0532d227c9ffd3d3c6cf0e6e3971638b7739c6d8d1a0b59bb733dadfa0dfa7fe17f97623e1e307577647ece01cc2da1b58734b8b3d8d1bdd0cfd2574d74ddeef67e93d3fe7966f4db194b77dedaeaa4d5b3dc1cf6c87eedce702cfd13777f35fe99695115d7ea63578b5b858eb9cfdce82c69d96dd53b67accfd107d75edff0009fe0d576c5e8cf2edb83059f65baeb7736b6fa65a481b2cb2a7b1bbfd3f499437fc17d3f5bfeb88eee9f84db1ceb70cbc318c73329f702e049b1f7553ebb6ca3d37b9aff519ea7f3d6fa4a143598551f4b1c63b19556cacb320d4e1588becc72ddcd6e33596b3d2fa6fdf57a9e8fa7fa44e325ec16b5e72ed6d8f373272087b59632bfd06d6bd96d3f657eff4ff00f465c8a1ad5d75bb271cbf11de9db6dccb6e020ef6bbecb8ef7edb6bd9fa3a5bbffeda4d7e15a58d2ec19243d9587ded787389d585dbcfb2edbf4ad67e8bfa3a93763f2cd9e8da3d42f712db049db4fd96ac8f6dd56f77b6a7fd3ffc1514e2d4e05f938ceb6c7b69a5ee7587681716e3e56e0f2d6bbecd57e9effd07a77d7ea7a7fe95251475e11159f470dee2c6b9cd60b1ad05c40fd16f6deef4dcfb3dbea7f37529df8c5acb8e4600b61ada2a06d30f8fd335b7d6eb776cf5adf4fd46fe93fd2ff8345c7c26623858caec27d46da182d610d683587e03b7e486bbf9bb1f4ff3b4fe9d55cbc47e3e3b6aab1b272457b8d97b6e697becb763db6dd5fda37bbe97a79ef7ff00a17dde95b4a0a49554daf372b2062ddea7aa2d369c81b4bb6d5b76075b5b9bff0071fddfcefa7fe8d13d2af21f58af11d6359170bc6446ac70d95d81efdee7bbd6bbe9ff00a2ff008a5887172eb6ecaf1b756c69bbd275a4385cd3657e836c67abbfd4639afa9ef7fa4ff5b67f81b2a56ebc4c9c727d0aebb847b1af7b9b20c36c7399fcaffa8622426dbb4e3613af6bdf49aefb25b922eb5fb83b6baba47e89de964ef6eef7fad57a5fe0bfc221ba8a31cd6ec8c5ad9b5b37bbd7b1a37060d965105ef7fb9bff006a3f49551fe17fc22ad6576ef77a9454e6dafa9a1b2439b4ec0dc8171f4ff4fe8e4b7753ff000367f9e3fb335b4c9f4186b9739ed690d6bcb5db9ccaf77b5cfa83ff0045bbd47fbd2424ea1d43a68f4edc8a31df5bb617b6cbedd3d26dbe965d5757eeb1cdab637d8cf56afe7ffc17a8819397d26dc5b98dc6c7f42d0caed0d2ebac366df4db57ad655eef519f67ab17d44c71af21ff0066ba97dac16d6cac35840b9a006d3e939dea55e958c755ecbfd3f4ee447b5f88f79c487e43cee61b182bae92ef65b957c39fbf2bdbb29c3abd9456ff005ff43f69fd1ad13aa2fb433d7a88343f272dce764d4c63ac7bfd275947ecd63bd8cb317d7f52dc8bbfa2fe83f9efb3d986b2f2331a7a97d5ec4a9edd87ac63bec6b19b03ecaec6d6fc9f51ae7576ff003bb3ff0030572eb5d8ec35d5956beec873acccc9600db6c7173ec755bfe8d381fa5fe83e9fb3fe31633ec07eb1fd5ca8926c1d46a7d920800fad8ec0d6cfd246207105b23e92ff00ffd4d0e9b10df4bed5be0eeddf678fa47d3e7d9fcf7fd716e08fb38fb57a9b368f53d4f4b7469bbd5fcddfbff9cff845f3f24a03f4fab63ed7ddeffd9ff687fade9fafea37d6dfe86ef57637d09dff00e1becfe97a7fc8556cfd9d2dd9b7e937647a71ea6be9edff0086fa5e9af1249057dafb455fb3b6b3d3f4a3d41e9ff37fd225fe9eedbedfb47f3fb7d3f7ff00a64667ecef5e9db3ea7a777a3b63e87e83d6df3ecf5377a5e8edff0007f68ff07ea2f1149153edb95f6387edf5b769b7f9a89fcd8f57f97fbe963fa1fa29fb5efdac98f463d5d77447f83ddb17892497daa1f47dcb1fd48d7ed7c8db3f67db30777d1f728d7eb43e7d7d9b84eefb2c6ffcefedeefa6bc3d243ed51fa3ecd7fadf67b7ecfebfda647a3b3ec5b7747e8f77f82fe73e86ffcf46c9f4b7b67edfb3d41e846cdbbe0fa7b7f95b7d4dabc4d247edfdaaff15f6477d9f7b7fa44ed3e94fa11b27dfb3d6fd1fa6abe9b847dab6ec6cecfb0ed89d3f9bf7ecff8d5e46925f6fd55f63eb577a7b5fbbed33ecddb3ec9b635dbf4bdbf477ff23d6fe73deb3737f66ffcece85b3ed7bbed78fe86ff004fd39fb4d1bf7ff85f57fd37fd6bfc1af3749187cc16cfe57fffd9ffed3ea050686f746f73686f7020332e30003842494d0425000000000010000000000000000000000000000000003842494d03ea000000001da63c3f786d6c2076657273696f6e3d22312e302220656e636f64696e673d225554462d38223f3e0a3c21444f435459504520706c697374205055424c494320222d2f2f4170706c6520436f6d70757465722f2f44544420504c49535420312e302f2f454e222022687474703a2f2f7777772e6170706c652e636f6d2f445444732f50726f70657274794c6973742d312e302e647464223e0a3c706c6973742076657273696f6e3d22312e30223e0a3c646963743e0a093c6b65793e636f6d2e6170706c652e7072696e742e50616765466f726d61742e504d486f72697a6f6e74616c5265733c2f6b65793e0a093c646963743e0a09093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e63726561746f723c2f6b65793e0a09093c737472696e673e636f6d2e6170706c652e7072696e74696e676d616e616765723c2f737472696e673e0a09093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e6974656d41727261793c2f6b65793e0a09093c61727261793e0a0909093c646963743e0a090909093c6b65793e636f6d2e6170706c652e7072696e742e50616765466f726d61742e504d486f72697a6f6e74616c5265733c2f6b65793e0a090909093c7265616c3e37323c2f7265616c3e0a090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e636c69656e743c2f6b65793e0a090909093c737472696e673e636f6d2e6170706c652e7072696e74696e676d616e616765723c2f737472696e673e0a090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e6d6f64446174653c2f6b65793e0a090909093c646174653e323030342d30382d30345431363a33323a33305a3c2f646174653e0a090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e7374617465466c61673c2f6b65793e0a090909093c696e74656765723e303c2f696e74656765723e0a0909093c2f646963743e0a09093c2f61727261793e0a093c2f646963743e0a093c6b65793e636f6d2e6170706c652e7072696e742e50616765466f726d61742e504d4f7269656e746174696f6e3c2f6b65793e0a093c646963743e0a09093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e63726561746f723c2f6b65793e0a09093c737472696e673e636f6d2e6170706c652e7072696e74696e676d616e616765723c2f737472696e673e0a09093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e6974656d41727261793c2f6b65793e0a09093c61727261793e0a0909093c646963743e0a090909093c6b65793e636f6d2e6170706c652e7072696e742e50616765466f726d61742e504d4f7269656e746174696f6e3c2f6b65793e0a090909093c696e74656765723e313c2f696e74656765723e0a090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e636c69656e743c2f6b65793e0a090909093c737472696e673e636f6d2e6170706c652e7072696e74696e676d616e616765723c2f737472696e673e0a090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e6d6f64446174653c2f6b65793e0a090909093c646174653e323030342d30382d30345431363a33323a33305a3c2f646174653e0a090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e7374617465466c61673c2f6b65793e0a090909093c696e74656765723e303c2f696e74656765723e0a0909093c2f646963743e0a09093c2f61727261793e0a093c2f646963743e0a093c6b65793e636f6d2e6170706c652e7072696e742e50616765466f726d61742e504d5363616c696e673c2f6b65793e0a093c646963743e0a09093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e63726561746f723c2f6b65793e0a09093c737472696e673e636f6d2e6170706c652e7072696e74696e676d616e616765723c2f737472696e673e0a09093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e6974656d41727261793c2f6b65793e0a09093c61727261793e0a0909093c646963743e0a090909093c6b65793e636f6d2e6170706c652e7072696e742e50616765466f726d61742e504d5363616c696e673c2f6b65793e0a090909093c7265616c3e313c2f7265616c3e0a090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e636c69656e743c2f6b65793e0a090909093c737472696e673e636f6d2e6170706c652e7072696e74696e676d616e616765723c2f737472696e673e0a090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e6d6f64446174653c2f6b65793e0a090909093c646174653e323030342d30382d30345431363a33323a33305a3c2f646174653e0a090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e7374617465466c61673c2f6b65793e0a090909093c696e74656765723e303c2f696e74656765723e0a0909093c2f646963743e0a09093c2f61727261793e0a093c2f646963743e0a093c6b65793e636f6d2e6170706c652e7072696e742e50616765466f726d61742e504d566572746963616c5265733c2f6b65793e0a093c646963743e0a09093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e63726561746f723c2f6b65793e0a09093c737472696e673e636f6d2e6170706c652e7072696e74696e676d616e616765723c2f737472696e673e0a09093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e6974656d41727261793c2f6b65793e0a09093c61727261793e0a0909093c646963743e0a090909093c6b65793e636f6d2e6170706c652e7072696e742e50616765466f726d61742e504d566572746963616c5265733c2f6b65793e0a090909093c7265616c3e37323c2f7265616c3e0a090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e636c69656e743c2f6b65793e0a090909093c737472696e673e636f6d2e6170706c652e7072696e74696e676d616e616765723c2f737472696e673e0a090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e6d6f64446174653c2f6b65793e0a090909093c646174653e323030342d30382d30345431363a33323a33305a3c2f646174653e0a090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e7374617465466c61673c2f6b65793e0a090909093c696e74656765723e303c2f696e74656765723e0a0909093c2f646963743e0a09093c2f61727261793e0a093c2f646963743e0a093c6b65793e636f6d2e6170706c652e7072696e742e50616765466f726d61742e504d566572746963616c5363616c696e673c2f6b65793e0a093c646963743e0a09093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e63726561746f723c2f6b65793e0a09093c737472696e673e636f6d2e6170706c652e7072696e74696e676d616e616765723c2f737472696e673e0a09093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e6974656d41727261793c2f6b65793e0a09093c61727261793e0a0909093c646963743e0a090909093c6b65793e636f6d2e6170706c652e7072696e742e50616765466f726d61742e504d566572746963616c5363616c696e673c2f6b65793e0a090909093c7265616c3e313c2f7265616c3e0a090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e636c69656e743c2f6b65793e0a090909093c737472696e673e636f6d2e6170706c652e7072696e74696e676d616e616765723c2f737472696e673e0a090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e6d6f64446174653c2f6b65793e0a090909093c646174653e323030342d30382d30345431363a33323a33305a3c2f646174653e0a090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e7374617465466c61673c2f6b65793e0a090909093c696e74656765723e303c2f696e74656765723e0a0909093c2f646963743e0a09093c2f61727261793e0a093c2f646963743e0a093c6b65793e636f6d2e6170706c652e7072696e742e7375625469636b65742e70617065725f696e666f5f7469636b65743c2f6b65793e0a093c646963743e0a09093c6b65793e636f6d2e6170706c652e7072696e742e50616765466f726d61742e504d41646a757374656450616765526563743c2f6b65793e0a09093c646963743e0a0909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e63726561746f723c2f6b65793e0a0909093c737472696e673e636f6d2e6170706c652e7072696e74696e676d616e616765723c2f737472696e673e0a0909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e6974656d41727261793c2f6b65793e0a0909093c61727261793e0a090909093c646963743e0a09090909093c6b65793e636f6d2e6170706c652e7072696e742e50616765466f726d61742e504d41646a757374656450616765526563743c2f6b65793e0a09090909093c61727261793e0a0909090909093c7265616c3e302e303c2f7265616c3e0a0909090909093c7265616c3e302e303c2f7265616c3e0a0909090909093c7265616c3e3738333c2f7265616c3e0a0909090909093c7265616c3e3535393c2f7265616c3e0a09090909093c2f61727261793e0a09090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e636c69656e743c2f6b65793e0a09090909093c737472696e673e636f6d2e6170706c652e7072696e74696e676d616e616765723c2f737472696e673e0a09090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e6d6f64446174653c2f6b65793e0a09090909093c646174653e323030342d30382d30345431363a33323a33305a3c2f646174653e0a09090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e7374617465466c61673c2f6b65793e0a09090909093c696e74656765723e303c2f696e74656765723e0a090909093c2f646963743e0a0909093c2f61727261793e0a09093c2f646963743e0a09093c6b65793e636f6d2e6170706c652e7072696e742e50616765466f726d61742e504d41646a75737465645061706572526563743c2f6b65793e0a09093c646963743e0a0909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e63726561746f723c2f6b65793e0a0909093c737472696e673e636f6d2e6170706c652e7072696e74696e676d616e616765723c2f737472696e673e0a0909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e6974656d41727261793c2f6b65793e0a0909093c61727261793e0a090909093c646963743e0a09090909093c6b65793e636f6d2e6170706c652e7072696e742e50616765466f726d61742e504d41646a75737465645061706572526563743c2f6b65793e0a09090909093c61727261793e0a0909090909093c7265616c3e2d31383c2f7265616c3e0a0909090909093c7265616c3e2d31383c2f7265616c3e0a0909090909093c7265616c3e3832343c2f7265616c3e0a0909090909093c7265616c3e3537373c2f7265616c3e0a09090909093c2f61727261793e0a09090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e636c69656e743c2f6b65793e0a09090909093c737472696e673e636f6d2e6170706c652e7072696e74696e676d616e616765723c2f737472696e673e0a09090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e6d6f64446174653c2f6b65793e0a09090909093c646174653e323030342d30382d30345431363a33323a33305a3c2f646174653e0a09090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e7374617465466c61673c2f6b65793e0a09090909093c696e74656765723e303c2f696e74656765723e0a090909093c2f646963743e0a0909093c2f61727261793e0a09093c2f646963743e0a09093c6b65793e636f6d2e6170706c652e7072696e742e5061706572496e666f2e504d50617065724e616d653c2f6b65793e0a09093c646963743e0a0909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e63726561746f723c2f6b65793e0a0909093c737472696e673e636f6d2e6170706c652e7072696e742e706d2e506f73745363726970743c2f737472696e673e0a0909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e6974656d41727261793c2f6b65793e0a0909093c61727261793e0a090909093c646963743e0a09090909093c6b65793e636f6d2e6170706c652e7072696e742e5061706572496e666f2e504d50617065724e616d653c2f6b65793e0a09090909093c737472696e673e69736f2d61343c2f737472696e673e0a09090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e636c69656e743c2f6b65793e0a09090909093c737472696e673e636f6d2e6170706c652e7072696e742e706d2e506f73745363726970743c2f737472696e673e0a09090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e6d6f64446174653c2f6b65793e0a09090909093c646174653e323030332d30372d30315431373a34393a33365a3c2f646174653e0a09090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e7374617465466c61673c2f6b65793e0a09090909093c696e74656765723e313c2f696e74656765723e0a090909093c2f646963743e0a0909093c2f61727261793e0a09093c2f646963743e0a09093c6b65793e636f6d2e6170706c652e7072696e742e5061706572496e666f2e504d556e61646a757374656450616765526563743c2f6b65793e0a09093c646963743e0a0909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e63726561746f723c2f6b65793e0a0909093c737472696e673e636f6d2e6170706c652e7072696e742e706d2e506f73745363726970743c2f737472696e673e0a0909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e6974656d41727261793c2f6b65793e0a0909093c61727261793e0a090909093c646963743e0a09090909093c6b65793e636f6d2e6170706c652e7072696e742e5061706572496e666f2e504d556e61646a757374656450616765526563743c2f6b65793e0a09090909093c61727261793e0a0909090909093c7265616c3e302e303c2f7265616c3e0a0909090909093c7265616c3e302e303c2f7265616c3e0a0909090909093c7265616c3e3738333c2f7265616c3e0a0909090909093c7265616c3e3535393c2f7265616c3e0a09090909093c2f61727261793e0a09090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e636c69656e743c2f6b65793e0a09090909093c737472696e673e636f6d2e6170706c652e7072696e74696e676d616e616765723c2f737472696e673e0a09090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e6d6f64446174653c2f6b65793e0a09090909093c646174653e323030342d30382d30345431363a33323a33305a3c2f646174653e0a09090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e7374617465466c61673c2f6b65793e0a09090909093c696e74656765723e303c2f696e74656765723e0a090909093c2f646963743e0a0909093c2f61727261793e0a09093c2f646963743e0a09093c6b65793e636f6d2e6170706c652e7072696e742e5061706572496e666f2e504d556e61646a75737465645061706572526563743c2f6b65793e0a09093c646963743e0a0909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e63726561746f723c2f6b65793e0a0909093c737472696e673e636f6d2e6170706c652e7072696e742e706d2e506f73745363726970743c2f737472696e673e0a0909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e6974656d41727261793c2f6b65793e0a0909093c61727261793e0a090909093c646963743e0a09090909093c6b65793e636f6d2e6170706c652e7072696e742e5061706572496e666f2e504d556e61646a75737465645061706572526563743c2f6b65793e0a09090909093c61727261793e0a0909090909093c7265616c3e2d31383c2f7265616c3e0a0909090909093c7265616c3e2d31383c2f7265616c3e0a0909090909093c7265616c3e3832343c2f7265616c3e0a0909090909093c7265616c3e3537373c2f7265616c3e0a09090909093c2f61727261793e0a09090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e636c69656e743c2f6b65793e0a09090909093c737472696e673e636f6d2e6170706c652e7072696e74696e676d616e616765723c2f737472696e673e0a09090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e6d6f64446174653c2f6b65793e0a09090909093c646174653e323030342d30382d30345431363a33323a33305a3c2f646174653e0a09090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e7374617465466c61673c2f6b65793e0a09090909093c696e74656765723e303c2f696e74656765723e0a090909093c2f646963743e0a0909093c2f61727261793e0a09093c2f646963743e0a09093c6b65793e636f6d2e6170706c652e7072696e742e5061706572496e666f2e7070642e504d50617065724e616d653c2f6b65793e0a09093c646963743e0a0909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e63726561746f723c2f6b65793e0a0909093c737472696e673e636f6d2e6170706c652e7072696e742e706d2e506f73745363726970743c2f737472696e673e0a0909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e6974656d41727261793c2f6b65793e0a0909093c61727261793e0a090909093c646963743e0a09090909093c6b65793e636f6d2e6170706c652e7072696e742e5061706572496e666f2e7070642e504d50617065724e616d653c2f6b65793e0a09090909093c737472696e673e41343c2f737472696e673e0a09090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e636c69656e743c2f6b65793e0a09090909093c737472696e673e636f6d2e6170706c652e7072696e742e706d2e506f73745363726970743c2f737472696e673e0a09090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e6d6f64446174653c2f6b65793e0a09090909093c646174653e323030332d30372d30315431373a34393a33365a3c2f646174653e0a09090909093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e7374617465466c61673c2f6b65793e0a09090909093c696e74656765723e313c2f696e74656765723e0a090909093c2f646963743e0a0909093c2f61727261793e0a09093c2f646963743e0a09093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e41504956657273696f6e3c2f6b65793e0a09093c737472696e673e30302e32303c2f737472696e673e0a09093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e707269766174654c6f636b3c2f6b65793e0a09093c66616c73652f3e0a09093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e747970653c2f6b65793e0a09093c737472696e673e636f6d2e6170706c652e7072696e742e5061706572496e666f5469636b65743c2f737472696e673e0a093c2f646963743e0a093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e41504956657273696f6e3c2f6b65793e0a093c737472696e673e30302e32303c2f737472696e673e0a093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e707269766174654c6f636b3c2f6b65793e0a093c66616c73652f3e0a093c6b65793e636f6d2e6170706c652e7072696e742e7469636b65742e747970653c2f6b65793e0a093c737472696e673e636f6d2e6170706c652e7072696e742e50616765466f726d61745469636b65743c2f737472696e673e0a3c2f646963743e0a3c2f706c6973743e0a3842494d03e9000000000078000300000048004800000000030f022fffeeffee033802410367057b03e000020000004800480000000002d802280001000000640000000100030303000000017fff0001000100000000000000000000000068080019019000000000002000000000000000000000000000000000000000000000000000003842494d03ed000000000010004800000001000200480000000100023842494d042600000000000e000000000000000000003f8000003842494d040d0000000000040000001e3842494d04190000000000040000001e3842494d03f3000000000009000000000000000001003842494d040a00000000000100003842494d271000000000000a000100000000000000023842494d03f5000000000048002f66660001006c66660006000000000001002f6666000100a1999a0006000000000001003200000001005a00000006000000000001003500000001002d000000060000000000013842494d03f80000000000700000ffffffffffffffffffffffffffffffffffffffffffff03e800000000ffffffffffffffffffffffffffffffffffffffffffff03e800000000ffffffffffffffffffffffffffffffffffffffffffff03e800000000ffffffffffffffffffffffffffffffffffffffffffff03e800003842494d0408000000000010000000010000024000000240000000003842494d041e000000000004000000003842494d041a00000000034100000006000000000000000000000151000001ff000000060063006800610072007400650000000100000000000000000000000000000000000000010000000000000000000001ff0000015100000000000000000000000000000000010000000000000000000000000000000000000010000000010000000000006e756c6c0000000200000006626f756e64734f626a6300000001000000000000526374310000000400000000546f70206c6f6e6700000000000000004c6566746c6f6e67000000000000000042746f6d6c6f6e670000015100000000526768746c6f6e67000001ff00000006736c69636573566c4c73000000014f626a6300000001000000000005736c6963650000001200000007736c69636549446c6f6e67000000000000000767726f757049446c6f6e6700000000000000066f726967696e656e756d0000000c45536c6963654f726967696e0000000d6175746f47656e6572617465640000000054797065656e756d0000000a45536c6963655479706500000000496d672000000006626f756e64734f626a6300000001000000000000526374310000000400000000546f70206c6f6e6700000000000000004c6566746c6f6e67000000000000000042746f6d6c6f6e670000015100000000526768746c6f6e67000001ff0000000375726c54455854000000010000000000006e756c6c54455854000000010000000000004d7367655445585400000001000000000006616c74546167544558540000000100000000000e63656c6c54657874497348544d4c626f6f6c010000000863656c6c546578745445585400000001000000000009686f727a416c69676e656e756d0000000f45536c696365486f727a416c69676e0000000764656661756c740000000976657274416c69676e656e756d0000000f45536c69636556657274416c69676e0000000764656661756c740000000b6267436f6c6f7254797065656e756d0000001145536c6963654247436f6c6f7254797065000000004e6f6e6500000009746f704f75747365746c6f6e67000000000000000a6c6566744f75747365746c6f6e67000000000000000c626f74746f6d4f75747365746c6f6e67000000000000000b72696768744f75747365746c6f6e6700000000003842494d041100000000000101003842494d0414000000000004000000013842494d040c000000001ac30000000100000080000000540000018000007e0000001aa700180001ffd8ffe000104a46494600010201004800480000ffed000c41646f62655f434d0002ffee000e41646f626500648000000001ffdb0084000c08080809080c09090c110b0a0b11150f0c0c0f1518131315131318110c0c0c0c0c0c110c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c010d0b0b0d0e0d100e0e10140e0e0e14140e0e0e0e14110c0c0c0c0c11110c0c0c0c0c0c110c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0cffc00011080054008003012200021101031101ffdd00040008ffc4013f0000010501010101010100000000000000030001020405060708090a0b0100010501010101010100000000000000010002030405060708090a0b1000010401030204020507060805030c33010002110304211231054151611322718132061491a1b14223241552c16233347282d14307259253f0e1f163733516a2b283264493546445c2a3743617d255e265f2b384c3d375e3f3462794a485b495c4d4e4f4a5b5c5d5e5f55666768696a6b6c6d6e6f637475767778797a7b7c7d7e7f711000202010204040304050607070605350100021103213112044151617122130532819114a1b14223c152d1f0332462e1728292435315637334f1250616a2b283072635c2d2449354a317644555367465e2f2b384c3d375e3f34694a485b495c4d4e4f4a5b5c5d5e5f55666768696a6b6c6d6e6f62737475767778797a7b7c7ffda000c03010002110311003f00d9cabbac3aa75782eaabb5d8f7161dad0ff541aebc57fe91cd6369dd6d8eb5fb6d7fabe97e8fd24276150fea7fb4271b2b3abb0b0dda7a9f652da5acc6b3d47dff0067cb67e9bf5bfd364fd8bfc27e9fd3acb9f664b6a15535dae716eaec52d658480efcfb7f9a63dfff006d3fd3ff0007bd3558b93692dbaccb755b9d373e8ade46a6db2a3b6bdf75576efb3fb19beaff008bfd22836f0672d8cacbb9b4bbecb532ebc6c02b6383e09733d4ded8a7f37d5f7b2dff008753b7271ddea3a8344d2459687581f0c639edcaaec3b58cc6db5d4f67da9cff00529bebbff57fd5bf4c9dd1e8652f0412cdefdbfaa90ea9d631ed63839946eb29c6a77d7bbf9bff0002b3c3039b8cec5caadc0d218edd8ee3eb5862c7e617ef66eb7f4594ff006d5e9efc8c8c8b1214a7472ac6d34b1efaa86ef716bdecb496d55b99616e6dbec0d733d56331f67f35fa5f57d5b549ecb699165b8f159335c8690c25fe8bdcfb6c6edf5daddff43d3fa7f67542badb696b715b43ad7d9e9861c57b9c693ef6c35d6b1af7b1cf7e4fa97b2ac4baab6efe6558b5adc46640a324d963064380f443ad24bafbb1ab1f68b2a7643f1ddfabd38eff00e7bd3ae8f599fcea4864fbfd26dce75b4faac6b61a03ec6b9c0b3d6ab7ee637d5d967e82b7bfd4f7fad67e8eb4ee0fa697b9d9589919b5b8d4d2f3e954ebda06eaeeadb77e8acd9ff71d957bff0049e97d9ecf4552657eaf59bde32e050c63db5369219bc875aec4c8a286b596fb3d3f4b2ff3ff00eb7b15ebfa87d9321d4baf3b1e4371c371f59ab653d47d4f66df4b1adb69b7d6ddefc6bbf41eafd9bd44129db6d755970b73687baabc5605b50ac3458ddf8f8748f5daeb1eff005a8fd2fa96bfd4f52bfd1ff821557578f4515ddd50586ba5b53ac79a66c74fa54e5bdb739eff00b558ff0066ef5ec6657f36fafd451bb36bf480391a376d82b143ac2ed62bdcd6ed7bb6dfb32dbb7d1b697d3fe8fe9c9ce6df5ec393b4e4bc47a743892496d75b99ea7aad67a794caeef51ffce3eafe6fd1495a2d7e5e064d36d56f51069b58fdfe8ecadbe9dac67e89f68a9fb5ff00432319f5dbf6d67af57f81b2a4b17a83f268c6bafccbabf505e3231df4368c83b8b998dbbd165bf64ba8ddea3fd3bbf48ff4b27d9fcd285390fc9a5d6e36596e46558eb1b7b71e5b67aa5b8f837be835fe6d0dc765b7fd3ab67eb5fa14f50aef7e259566657d8cb5b2eb2970366d75ad61b726ca98ef5ec7329c77b3fed4ff00e852284833d95dff00a5fb6edc3018320541e2cde76e4fe871e8dcff00e67f9df4fd37fe92da3f48a7f690fb8fe9720d6e6d6f6d9e6e2fdf4371eca7f44ead82bdf67ee59fa4fd22a94e3d5760b1ecb3232c7a1f68fb4b6b731ce6ed73aababa3d26b7d5752f65f5e36dfd25ecaff33f45641b8fe9e2d4e78cac87b7d335e47a840b458dc7a464edacb7f56b3d4f5edaedfd2d1e9e55891484afbf1adfd332ec8d8d7db35ba6b03d279aedaac6b6bf732a756f650c7fe9bd1ff8cb166d5917b2cc57befc83bfa96252e7e436059390cf4fecf536b65757e85bb1ff00cdfb2abaff00e7ff009db96e35a480f65b76f76c75a2ddac69687b9d6eeb5cc757e9bbf57b77ff003acf43fd1ac978c76e5e00734d6fb7a974f7d62d7ef76e17b5ae635a1fb596ec759efdbea3ebabfd1fe9121b8f35743e4fffd0dfca65d0d70afd42c8dada9de93a7ddc59a6edccdea3bc16dd5dadcc77acdf4cb9af801a48b7d4af7fd1b18ffd1bbfe0fd446bf3312ead85edb1cc96bbf498c6c12c0ff736eaacdd5db5eefe77e87e67f84544d9814d90eb49bf42eaa9a6eae7d28166cb1b66cfd56bb6dbfd1f5fd4bbd4f4ff0049b1417dd9dd4bb3b2463b9c1f99792ff55aea3d1de09b3d56377fe8d9b71587ecb974db5d9eafd0afd57aa194cba4b31df958ce2fbb1b05b4b1ae67a66a6d9bf7ec7bedc7d98ffa1733f5ba3f58c6f53f4b75688db3a71655ebb8dd7b7d3b31eaf59dedb9ad6e3d5b45b6d8fa7dd6ecf5aff5becfebfe9fd4b7d4517518f6d6eaab395736dacbeaa9d7d2ef55b51df56463dc7237ed7fa7eb539bfe0ffe8257d554ab6bbdf5578565b94fa72ed38b7fa6caa968a7209aff004bb2a635cda9ae66355633f58fb559ea7fc1ab59996cb71aacd2fcfbe97c38d7b2a6d9501eadbf6b73053eb6faedaaac7ff80f53d5548371ed6e35b60b6eaadb1976234d81a2cd83edd4df486bbd565adc573afd8cfd2555a3b8fad9018f65f5efb436b73ef2f76d90caed1bf65bea3bddea636ebbfe31e92a91e0bb2eca6d763e5e4d06dc52dc13654dd8eb9df6adac70be967af77f32ff00b36c67f33f9ffa650b32eebf31971b2fad9550db5b48a838b0b835dfd23d0b29b76d35e5606c67f4afd0ff00a565c9d97d471eac8bea7b0bb6bdad7bf61adee1918ae6b3283e9fd63d2bb22af477effd27e891e8a2eaabb71fd3cac87fa8e2e2eb99eb3666c66db3ed0d67bbfa37a7ea7abe9e3efb6af492b5535b1f329ad98e7ed1976d173ebf4dbb1aedad23ed3163ada9d76cc867e82cb77daf66ff00f05e9fa88a2cbeaa69b6cbf3adb686fe96e6d6d96eea9fead8cc3f498cc87fa95d7fab3996fd9ee7d1653fe16b4e6a6b4371a1e1d92e6d4ec7bad686be9bcbebc89c7f5dacdeec56e4d787bdbe9fa9f43f99b94b1b2f11b4d5d4e97e55d8973dbb2eb329be9b0175be27db8aec9a69a3f4bfa2b6eb6aff0007ea7a814cb7063bd2c7b335ef6b5d900b2aa9ac78d6bb3118ff004ebad9f6bb5fbff4ccf63ff49eafa5fa25360babb8beaab34b6d22c2c73ab23d7b1e0be29dfeef5b77e93fed3625946fa10ecadb6dc777af914530e79bb3e590d6d990edec96d153f6e5fa5b6dff00b494e3e455fe0d2bba4b2c227158eacd4d65991f69732e756c2726adb637db5d54e437df758db3ed5eaa4a59b7e6d6fae8b3132ee74b9f5dedb9d0761ae1d6d70cd8ff00d2fbebaff43ff712bf437d3446ec6ca77a3553d32df4dcf76f26d700d22ca8377b1a2cdd8af75f6d9e8fb365585fabfe8d593fb1854e2cba9ae926b75939efd82ab5a6b6dec7bb733dd5dbe9e355fcde47a9fce56a81c8e9d47b1d938353b1ec0035f68c8f5696535b36bdcfaabb29f4bd5aadb5d4ff00dc7a59e8feb7eaa4149e9e9768f5fd4c5a9ae0e70a9971f55be9b5eef4a0435cc75d45d77a3436dfd1bff42a8629c7bf27072a9355cf764613cb1ad0e76db1d8b67aceaed25dbebf57f9f6fd0b3fe1bd4561f97821f7fa671e9b70c331dd6538f63df57a57dadf4d8cb37d7653edba9abdbbf1f2bed167f8259b9f91d4f27aa74aba8ccbbec55f52c3ab270cd618c23ed0cd9966f6ff00394dd76cf471dff4ff00a533fc27a446f4a2743d5fffd1df633a97a74fd85bf67a1cfdb7d8d6b5cd0d732d736c76f0e7fb6d67d0aaaf52c7fa557f844b1dbb5acaeeeab01cf7d871ce33dad6d8fd68fb3b5db7d2a99efb3d3b1dfa2ff8340caac5f452cf49ceae4d34bf7ec71373365adaff007fd4d9fa37d7fa4fd17e851b1703abd96bcb71321b492c26cb1d539af0347fa54db635edf6fb9ffccfafff0018a00ce533325d554d76065e2dd59f4acb9afa9d51731f5d9eab83c967a96e4dbe8bb67f3945767bff00c1a85993856e05fd3f25b82e656c756dac39c00f45c3d3c77fb1ef73f17d1fd232977abf68a99fa1ad15d4dd8e6c6e6e666d8fbc35e2bad8c73ab136cd6e7d4cdbea3fd466cfe72caa8a995ffa4de338943bf458b65ccb997d22edf507b21e5991712edb5d5badc577f3acff00cfa9297cdbb11d757955bf103cbcb196cec3e85ae1916bac6b99fd2bd6ab17d4d9ff000aabbb3312caeda322ec334d8031ad6b1c0eebea355feb5d5d5b6ab2da1d6fd9afaff9cc74d915b06663d4db2dfb3bec71b00c6869aaca9b6521ae7d5b99e95cdb2b67fda8fd3fe97f9bad1319d4e0e3565992f01831a8ade6a73a5f639d898ccdee68f51f7bebfd61effd2d5e9fa9fa3fb524ae886cb3a7b2cf4eeab15ee0f6b9957a7a383d8eb1aea2c757fa4bf635d665ec67e869f4909f755353dd4537de010db4fe8c39963998f73bdcd7337d5555436babe9fa98ff00e0d5b7dac65c2a36d9537183da5cca1cd245acf46ab311cf63dd4bb17f4f5eff00d17beef7d767a4ad1cb6ec696df94f6b46d74d321ae66c1baedb5973adb1bee6fa9ecc9fd27fa3412e5d6c2e73f36c663328b1fbcfda2bfd2825b462dbb1f6efbacf56dabe97a3b1feb7fc1fbc8e6dcdd96d9760934308de6a000f58b31eeb3e932b6b3237534595ecb37d9e9fa4b56b7d4fc9198ecbbaf21be89a3d3747b4fa8e78a5a07a0f7b59e8fda6cfd1fbff009e4c2bb2ea0582ec873722a6cd566381617b017dbf68abd014d577beaabd3fe6acb31bfd2d6f495e0d1ba9bf716e4861a6eaed6bac38e4b5a1ada6a6e3e558d8f7dadc87fa1b19b3d2ff0088b3d411c4697bdae14640aecb1cf7b812c60a6cf59d8feabac66cbf11f47ad753ff005daab561f4e5db6595d9979032722bf56caec1efa5b61751fa2b1b506d75b5cdc8c767fdbffcdfa48bfb4f1a8add57af94fb430e43df4d6c05ce7b9d88fb09757e8b375fb327d957e8ecff0009e9fe8915206b2fadf54369a9eedc6c70a18ea9b5867da36e51b367a2cdd66faed67f84fd0fa7f4eb46bdd9eeaebaacce00c07b2ea6875561aecdcc867a41db3d567b1f6d7e8d947e8b2bd9fa3b14dbd61c1c1c1b92f2d21c2b731a20b8fbac7358d6eefb3bbf4b6ffab10864e4d98ecc5a1f9ed1ea6ff59cf697bc8bbed1655693e9daea6d631d4d56d5fcdd167a1e9fa490fb108d998ec6b29af2ec36d41a0dae6d27d81ffa48c8b7f9c7fdb1e1eef53fd27a97e4fa3ea2c7fd9eecdea3d22cb3a8d961c2cac4bcd018365f6fab531f99bff43fe0ff0033d1d8cdf67fc2fa9d058d6bd9b8b731bbdee7381b5b2c244d9708796d75ecfd13fd2ff0767f36a958e6b32f176bada272f16cdaf74b0b5f914d2da995d0ed8c66f7fe8ffedcb3d4f4d0075511a3ffd2d3675118f86c3e9d365ac2e7d02fdcdf7b1b3ea57757fcdbd95bb23dff00ce7b11f2ba8b9d459e956fc1aab63a9adecc93516d6f7b6fbada6e7fa75fad5b31fd5a6addfccfe8bf4352a65d4baba80731d55a26cf59a5c36ba036b647b7f35dfce7e67e9362bb4e0f47c91b5f4d3965cd0d7b3e909d93639f38f76ddbf459eefe63f3ff004aab8ab6c1d9bb858571787db8d7b1f3bdac6da1ed6b1cff00d1d6e1758d6d7e9e2ecb2faacfd233fc17aaaf5e76d8c632acb7bf73c18b6b6c5760df639ccf57d3f4b0ddb2bafd6fe61ffcd7a9fe173b25ceb2d190cc6a8b6697bac6deeaec165365b67a375ff42ea6ba9f4eca99f4ecfe77fc1a01c8c4148c5681535ac65a69a721f65e3612ca36fe99af757a3ffc3fd93fe36c45092eb0e4f503596e537ed22d646ea41ad8c612cd9ee6fa565b5b3f4166f7fa5bff004de9a9b03b28586cb7285363ac6e39736a96167a3f6aaf7b1aeaf7fa95fa18fedb7d3f472ff4ff00cdaa182e6e5e632bc619553297efc9b9f7b5f69a852f73e86b9b7bfd3f52eb2aaf653fe83d5c8bababd3f54d9b94eb721b8f8d45d5d38f46dc773ad2d6882f67a77817fd36b9957e977d96feb1fe8fd44949b2b27a9500bed75b04030fa2a70fdcd2ca98ff0051ff009d77e9acff0048a16e4d9452e638655d63aea6bcb865750a9ae6377e455b6b2efa57faaf6ff3d6fe97ecfe8d38fe9551b2cc96b68b2ba6d6d8c7c3c8bda59b0d5bac6d551b3d0c86fda4369b5b7b3f4b5bfed1f4ff009b3307db2c14166654ec865a4582e6bb6969adcf787b9ef75566ccaf531ff58fd0b2bb3d9fcca4a5cb30f29b58b465df654e16d16343584595968a2bb7d8cadbebe46cf43ed0cb3f49ff00055a97516dd5d1eb63d19366a0fa44b19e98616b9dea58cf736af4def6318df5fed1e97fc27a960aaa2d7dcdcba29bed75757a82af5c34b1e7d3aedabd3f536d76e352f7e53a8bbf9dc9aaaff8c51c81765dd5c332aaaea731cdfd29e2adfb1b60fb4eedd6fa8e65f6fd3f51ecb3d4fd054fad792bcd58750b729b5ddf686b25ed7d96bda0b03417bdaed96b98d6bdd5fbf7ff00c5ab38ceaac2d6e56364d1911532c607b76b5cf2f7baba9cc7b3d5fd233d1c9c8f459eab3d2b2efd0aa2cc6732aa716fc4bafaae6be8b6bb6ffd2eeb587d3c6ca636eb59533d1b6ca9f7fd3a6eaeb7ff0086fb4a469baccb6d0fc3b1b58bf7d8fb9ee76db0b3d5c7bab736dbab6faaebafa723f4ccd8929b8ec3b2ec469761bebcd9aedf4064ef60b697b6eaab658d71fd1647efd4dff8f511d3ab79be87e23dac15fe8db6e4efb2cac127d4750c0e7d55d963367aca385531f806eb3a73a8a726b7fa8c65d1b43c33d661f4fdcdb6bb6db77fa7fa767d9adb29fe7506e7df9363f26dc1a582c60addef93fa33be96d32ddae656f75f92fdff00cdfd0ff0a86aa62ec77bf67e84170730b7f4e7706d2e7594ff0038df773fa5ff00d52a938b033a75aeac35d99d4b09d417493e8d7954d543abdff47d4d973ff9747a69adc619158a723d0a45ef6bfed01db4d18557d1ce77aadf7e4b6adf8d55adf4f169f57d455b232b27edbd3db6d54d4fb3abf4fa9edc7743195b2eb5d89436bda7d9e9d3eaff0039f4ff0049fc8446b21e0532d227c9ffd3d3c6cf0e6e3971638b7739c6d8d1a0b59bb733dadfa0dfa7fe17f97623e1e307577647ece01cc2da1b58734b8b3d8d1bdd0cfd2574d74ddeef67e93d3fe7966f4db194b77dedaeaa4d5b3dc1cf6c87eedce702cfd13777f35fe99695115d7ea63578b5b858eb9cfdce82c69d96dd53b67accfd107d75edff0009fe0d576c5e8cf2edb83059f65baeb7736b6fa65a481b2cb2a7b1bbfd3f499437fc17d3f5bfeb88eee9f84db1ceb70cbc318c73329f702e049b1f7553ebb6ca3d37b9aff519ea7f3d6fa4a143598551f4b1c63b19556cacb320d4e1588becc72ddcd6e33596b3d2fa6fdf57a9e8fa7fa44e325ec16b5e72ed6d8f373272087b59632bfd06d6bd96d3f657eff4ff00f465c8a1ad5d75bb271cbf11de9db6dccb6e020ef6bbecb8ef7edb6bd9fa3a5bbffeda4d7e15a58d2ec19243d9587ded787389d585dbcfb2edbf4ad67e8bfa3a93763f2cd9e8da3d42f712db049db4fd96ac8f6dd56f77b6a7fd3ffc1514e2d4e05f938ceb6c7b69a5ee7587681716e3e56e0f2d6bbecd57e9effd07a77d7ea7a7fe95251475e11159f470dee2c6b9cd60b1ad05c40fd16f6deef4dcfb3dbea7f37529df8c5acb8e4600b61ada2a06d30f8fd335b7d6eb776cf5adf4fd46fe93fd2ff8345c7c26623858caec27d46da182d610d683587e03b7e486bbf9bb1f4ff3b4fe9d55cbc47e3e3b6aab1b272457b8d97b6e697becb763db6dd5fda37bbe97a79ef7ff00a17dde95b4a0a49554daf372b2062ddea7aa2d369c81b4bb6d5b76075b5b9bff0071fddfcefa7fe8d13d2af21f58af11d6359170bc6446ac70d95d81efdee7bbd6bbe9ff00a2ff008a5887172eb6ecaf1b756c69bbd275a4385cd3657e836c67abbfd4639afa9ef7fa4ff5b67f81b2a56ebc4c9c727d0aebb847b1af7b9b20c36c7399fcaffa8622426dbb4e3613af6bdf49aefb25b922eb5fb83b6baba47e89de964ef6eef7fad57a5fe0bfc221ba8a31cd6ec8c5ad9b5b37bbd7b1a37060d965105ef7fb9bff006a3f49551fe17fc22ad6576ef77a9454e6dafa9a1b2439b4ec0dc8171f4ff4fe8e4b7753ff000367f9e3fb335b4c9f4186b9739ed690d6bcb5db9ccaf77b5cfa83ff0045bbd47fbd2424ea1d43a68f4edc8a31df5bb617b6cbedd3d26dbe965d5757eeb1cdab637d8cf56afe7ffc17a8819397d26dc5b98dc6c7f42d0caed0d2ebac366df4db57ad655eef519f67ab17d44c71af21ff0066ba97dac16d6cac35840b9a006d3e939dea55e958c755ecbfd3f4ee447b5f88f79c487e43cee61b182bae92ef65b957c39fbf2bdbb29c3abd9456ff005ff43f69fd1ad13aa2fb433d7a88343f272dce764d4c63ac7bfd275947ecd63bd8cb317d7f52dc8bbfa2fe83f9efb3d986b2f2331a7a97d5ec4a9edd87ac63bec6b19b03ecaec6d6fc9f51ae7576ff003bb3ff0030572eb5d8ec35d5956beec873acccc9600db6c7173ec755bfe8d381fa5fe83e9fb3fe31633ec07eb1fd5ca8926c1d46a7d920800fad8ec0d6cfd246207105b23e92ff00ffd4d0e9b10df4bed5be0eeddf678fa47d3e7d9fcf7fd716e08fb38fb57a9b368f53d4f4b7469bbd5fcddfbff9cff845f3f24a03f4fab63ed7ddeffd9ff687fade9fafea37d6dfe86ef57637d09dff00e1becfe97a7fc8556cfd9d2dd9b7e937647a71ea6be9edff0086fa5e9af1249057dafb455fb3b6b3d3f4a3d41e9ff37fd225fe9eedbedfb47f3fb7d3f7ff00a64667ecef5e9db3ea7a777a3b63e87e83d6df3ecf5377a5e8edff0007f68ff07ea2f1149153edb95f6387edf5b769b7f9a89fcd8f57f97fbe963fa1fa29fb5efdac98f463d5d77447f83ddb17892497daa1f47dcb1fd48d7ed7c8db3f67db30777d1f728d7eb43e7d7d9b84eefb2c6ffcefedeefa6bc3d243ed51fa3ecd7fadf67b7ecfebfda647a3b3ec5b7747e8f77f82fe73e86ffcf46c9f4b7b67edfb3d41e846cdbbe0fa7b7f95b7d4dabc4d247edfdaaff15f6477d9f7b7fa44ed3e94fa11b27dfb3d6fd1fa6abe9b847dab6ec6cecfb0ed89d3f9bf7ecff8d5e46925f6fd55f63eb577a7b5fbbed33ecddb3ec9b635dbf4bdbf477ff23d6fe73deb3737f66ffcece85b3ed7bbed78fe86ff004fd39fb4d1bf7ff85f57fd37fd6bfc1af3749187cc16cfe57fffd9003842494d042100000000005500000001010000000f00410064006f00620065002000500068006f0074006f00730068006f00700000001300410064006f00620065002000500068006f0074006f00730068006f007000200037002e003000000001003842494d0406000000000007fffc000000010100ffe11248687474703a2f2f6e732e61646f62652e636f6d2f7861702f312e302f003c3f787061636b657420626567696e3d27efbbbf272069643d2757354d304d7043656869487a7265537a4e54637a6b633964273f3e0a3c3f61646f62652d7861702d66696c74657273206573633d224352223f3e0a3c783a7861706d65746120786d6c6e733a783d2761646f62653a6e733a6d6574612f2720783a786170746b3d27584d5020746f6f6c6b697420322e382e322d33332c206672616d65776f726b20312e35273e0a3c7264663a52444620786d6c6e733a7264663d27687474703a2f2f7777772e77332e6f72672f313939392f30322f32322d7264662d73796e7461782d6e73232720786d6c6e733a69583d27687474703a2f2f6e732e61646f62652e636f6d2f69582f312e302f273e0a0a203c7264663a4465736372697074696f6e2061626f75743d27757569643a62643233313830652d653764362d313164382d626334622d656261316562306135393764270a2020786d6c6e733a7861704d4d3d27687474703a2f2f6e732e61646f62652e636f6d2f7861702f312e302f6d6d2f273e0a20203c7861704d4d3a446f63756d656e7449443e61646f62653a646f6369643a70686f746f73686f703a62643233313830632d653764362d313164382d626334622d6562613165623061353937643c2f7861704d4d3a446f63756d656e7449443e0a203c2f7264663a4465736372697074696f6e3e0a0a3c2f7264663a5244463e0a3c2f783a7861706d6574613e0a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020202020200a3c3f787061636b657420656e643d2777273f3effee000e41646f626500648000000001ffdb00840020212133243351303051422f2f2f42271c1c1c1c2722171717171722110c0c0c0c0c0c110c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0122333334263422181822140e0e0e14140e0e0e0e14110c0c0c0c0c11110c0c0c0c0c0c110c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0cffc0001108015101ff03012200021101031101ffdd00040020ffc4011b0000030101010101010101010000000000010002030405060708090a0b0101010101010101010101010100000000000102030405060708090a0b1000020201030203040706030306020135010002110321123104415122136171328191b142a105d1c114f05223723362e182f1433492a2b215d2532473c263068393e2f2a3445464253545162674365565b384c3d375e3f34694a485b495c4d4e4f4a5b5c5d5e5f55666768696a6b6c6d6e6f6110002020005010606010301030503062f0001110221033141125161718191221332f0a1b104c1d1e1f14252236272149233824324a2b23453446373c2d28393a354e2f205152506162635644555367465b384c3d375e3f34694a485b495c4d4e4f4a5b5c5d5e5f556667686ffda000c03010002110311003f00efad6d24538891533205b8366c8338c7934f043a8f535375fe10e5d4189ad3b207a832465ddf27a8c196677e33717aa5923b3506abf85be9a4258ea26da0f3b0f499a7acc9887d58c36802dc67ba24ede36ffcf77c36622fc110d004eaaba961442354b8472c8e4302340376e40da9145b5a408a257696a57c063048ce372f1947fe4b4856d2bb4b7616fc103220a975d072e733628300350db99908f28323d80a68345a73dde2d03a30156c940902bb8205f64b20d8bece23303748a744850d1cecb8472189a277c4ff00cc761389d500ea5b00a3da19f5e3bb6f740d40a49d5e5cd90e8003cb50b133cd1dbb5037a53a0b667330d00b4199a229809c59864ba7732ad49a7cde9b1cf1923b17ba50138d4f56835579a109c450e3fc4e921227444354d389de45717f6971d8344dd52068242c879b348c72c00e0ee6e586e448356896224837ac514dc1b6cbe79df09800f965ff0049eadb2f140d6e989e48c3e234f101233209effa3da60251da5008208e5abd1c238840e9ff0025b3117ca044247791da83d16e1e981ab718d6b6817b9cce4174b285b06009d5034bbd56d9300818c7b500934cddf099e3126760409f58124778a44ad1e9c51b0040267dadce394135e0e13c7fcc07b53afa6103532b4e126e5fd1279e5e509e98e933fe09fd496a1e87ffd0e94d584d25c1b39704658a34458ff0a32c44e408b7a71dd6be2da00bb154e1d3e0f462477917633a606512b0500edbee88c4d503c34271e0729b0100c011c940891ada998677de81a03a80757904e471efbf35ed97fca7ac96440004762886b46aed1289981469ce797d303fe4a2590c458614e8ad2adc768aa04b608bd51927b28c45ff001201f4ec56adc31edee88e4075b4ca607740e6ea8554876945d8445844c6f0c6fdb200f740d726312365bda027702c4b2c6268a02631f041d746cce3e2c1cb1f1404404452368eed19c7c5cb7097081d200ad1c0888340320ed94b5d14106409603710002400cce600bf0463c9b870d069c38cb1dce33f0dd19352ca2ac28c97cb01b2010592453863204e47b795a0ea416259221ca19b7824f8ed40dd69cbd41ed5965aec50350485dce5eafb0b9473eeba07ca81d4af3c324a409236d394334b346e1a140edba6647bf839094c72193399d004088f51bc4491accedfe97b43e7c70486dff0c8cffe53d20cfd881a6d1bb7376f3f9efb246ebd6a903433035798e4bc808e251ffa2eb2065c397a40578840de193747cdcb719c48a05e738fdae5e88b3481df6f3751c1ae7cbb510888a4c44b940d816b7070d0a88840b9658c793cb561e1cf8f890fb2773d1a520684b2580002ccf54099944264c4166500794c601850ce3613d34a277c47684dc7a92630241797a0f8337fe2b27fd0c8e91967ffd1eda4d3103635689706c07cae672c411ed598dce471448a40d65900a1e2d68f34b083dca764877281be9cb24092361229618e6051d5024c6311678748621f10ee99c255a2c632029027262b20844a200b27876112790c6488a22b940cb6c72007b372882ce00630008d5a9cc81a0d5a04484bfe8a4c43c98498d890efb9da39371a6034d83c13380972a67b05ac326e1680768f05f4c5825ce7d46c8995714d1c84d102d0194e3198881f1366209b23571ea21296c981ac64ee775f081a0af05a89e439033dd55a7f12329946b68b40d4c21e0f34cc61f0b73dfb741abca4cac03dd0348e58dd3d38c03c3cd18489bad1e88198d2980da9205323711ab809cb7ec3e1b9a0eb348d1cfcddd0370081a2400c0bad79644a7e1f7a06a6511a1718476c88f1f3232094ab4fb49df2fe160342c8e11bcd70b1248d45341a00e206dc87fc5499995d76667094b8ec4206fa7779f0c3d3b1daf747fd4ce4330637dcbd1107ba01a65894257a17296fdf11dbcdb903684f78b4f0cc711870519e27d2978d205d9ab0c8913cb84a120206f92377fc97718f69b4017ad26cb8c719194f84c464f4fa6103391a73ba16780ee63bc6ae7931ee8ed08131c8242da8cafdae5871f96a5cc7caf443181c20627251a21b1314e86219a03b204ca71e0b266229f2968c448708107c43065d8bb7bd043018197663798bd063a39a29cfd40328172e8079337fe2b27fd1c8f4e7979082f2f41f066ffc564ffa13768c33ffd2eda624e8cc9c1a39a53313c313c846a1e831b4ec08a4829bb68c077098e28f82048954c0ec43d21cc6388eda847a7e081b0892bb51e9d8d094c7111ded00100334de4c7b8391c721c1600446dd149b796266600deb7e9cbe97794643bb4090b4c9247cdb3120202744c480e3b2521ab51dc3940d6604e241ee8c560081eccd4cbb4606245f281721b85145d22519de9c399de3b20512e5972fa62dbb3e0e73fe6408010358ea1e4cb1b22bb3bcacc6bbbcfe7ba40ebc42f4759c4538463934a21d4895eb54c06a068f2e48d48487f4bae41290f2ff00a98da40d5a0a320e70c9bb5796024324a37fe3748c250d3440e96adc237dd3227b206ee7b81d072e7e6f16361bbee81d21a1ab8012e4ba1dc06881a20171fe67884c233dd64e880e51b87b41dcd836f2401f5262fc25177db2f140dbbb2403ca059d3bb10c663f11b40dac134b2af84f779361f5b75e847fd0773881e4a05111351eeedc079c6203551e6898ff00520684026d3278ba7c77000dd8f2ff00c97a3647840ad0b32318f25b8e3019cb8c4a245721024ed02c338e7b86a5ac34602c6aded03840806ce85ca13f52ef8bdaefc70e308984a5e04ee40d001c317212aecd92940c2648900120cc9d40a74946c82a488e8c06024492c9b3d9d08a27c1891f04522674d58e8e023ea81c1c73ff00a2e79a6622d1d04c98e627feef27fd19ba4659ffd3ef01121a2ee649b7268ce563848c83ba522901df15f50736a405d808aad0b0a6b1903a860ccfaa076a6e31111418388583dd03a0c80e5a05e3cb8b58ebdddbd3f6940da72a16e11c9b87fcd51ac5cfd1a1a584098c68107c773a816b3036d0e5e78890140a01c73129c84bec1749e51c384b05927f8be2739c28c0781fd1a0ea11bd6dd000f0cb19dc352f44604776035f52375dc38e7c844e35c13e6538f5bb58e1133a926bcc81d8257a5a24c7a22ef5b627849d016028eae51f28a6c60aee83843411692407338a37ddcf2c6cc48f140ef81eed979460891adfd2c753120c289addb6681db13e572915963a1a12f364065023bea8048b9090757970c4ca20bac71889bb28044fcfb6bb6edceb61c4e31a967d2bee503a146ac6cd281671e2db64940d8487169dcf1e2c5e697f53b8c3ada0013909ed3c11b9e87138c136c9c729706902a511bb7776c539fa37cda638444dea817745bbb71cb8c4c11de9618ee3aa039f48190e404c720db64f66bd31b769e1cf26202040f040d3d48934d8a1659db1205a2b9038401be301a36260b88d98cd78bb0aec812720513b6d0818489c7c6b65d35ad06ada940c65294459a0181bceb6082ed22242b964011143840c899479a4cb5903ef8b73db5e64000ea10130beea71f8ba81ddcfd51a9ec102684a3a3898876121c8d2d9b04a0739c71968edd374f1809d71284e3f729e5db01f8ffa24d4467fffd4eedb6821d2992e0d98905cee40bd12a1a1e59a40904a23908e5aa676940a964d34e523283cf2cd16820680898539046545989a6b43a9e50265944468e433994b69f63a4a3123567644eb480e4954491d971481883dc86f43a16638a11e02009e402423dcb3a48ebd91961fcc8cbb6b074f4c7740349b08db10bb223540c734f6ca20705db04c12589e389f31ecd74e232048d4206c33449a0def1e2f3471ed99fe176d070c061d54cc6208fe28acb28e1b9c778a2f34f15511e3ff0049a0d01b59d6913fd5ff0025c618aa47c0bd070c08d75a40e9811c31d446e27d9e6ff92c4f661da7bcbcb174204b52c0618672940197764d2444550e1cb263dc0d341a42a3a0492202cf0c8c408053386e818fb10284816ac3cb885c45f2ec23480cf2ed9088fb4d8983a5eae39204989f6a886d9140dc55e9cb5bc4742e71c746c35e9ebb9033cd2a008fe28bb7ab1068a0c2fb3964c2655e2103a7780c4b34622d023244e04c6900c32899e299cb90c6a8688c78c8e5bdb7c94098e61259651c6b6d80106a3a940cf1ce546c35ea5e806ae9d99847440ce404851e5ce2651240d62f4ed5da5038a3967e9997321b9dc4a445e898623127c25e67518ca06465248323cba1c7629ada81e7e289b946f504ff00ce74c31dd1164d87a8620244f8a618c46d03933c2e3a76224eb0c629e8a083410044723b31b04411d9d450e10482086039f151804c4396096d86d3c8324c2663f134a54b86ba73a4ff00a25ff451bac70de01f17f449a8cb3fffd5f4c164848d1925c1b06486e721023bdba4e6622fb2990a40cb6cbc5ce4257cba09dac79b4012240f6a89c8478481aead823b204c3212350a325f66e12df135a263140e7cc44a040f06e26a20bd3410620a0730ca0a7d41e2ebe9860e3407709b8ee94646f58ffd076d95c2ec408de3968ce2bb576a04cc89c0d7833d3c8c318a1fd6ea22eb8c0161027d68fcd9f50135dddc88b9188e503391205a3783cba7bd9a0802c5d5a4d572ba29039408ea7cdb0f68c9d2794002b82d5f8a9a281ce73441a1ddb84c1e7963243cc25e0ed102ed02a9c8e40ef76c988f040e72447e6d0986cc5ba25033f54147502e163c63f5bad5318e472dd700ed400725765f5e2623dbe57538af92cfa1a57b77c58518e4a146edb84f7766f6a76a2189cc232dbdeb7b964ea4440aeef41c2376e67262121c7081cf3cd2ad0357235a5ba1162bbba4450d5030899134742e79a32903101eadbe6ddfe969a0e58efaaa728cf26e23fe6bd43279f6fb373631f9b730a60259478244e7ddeca46d4439a529569cb3925238cff153d2621760281cb88f941becd4771eee9e9c623d8111db3171e1a00612fe24ec978b8f53122208ed28bb095774011848726d8cf03289ae5d0e503444f308f2800405351881c277822de58994a46aea980e9d1ce73035259f917ceeaf193a8bafe1683a67d54222eed9e8fa8331965da38e72ff009af8cfa3d07c19bff1593fe84dd41993ffd6ee8e50d7ad1794537b01706ce8396245164ca25c658cde9c236487b5035f27824981d0871958676c8a29d5700347086488b15dde78c67196b7b5d0889d750886f131e037711dcbcf194221adf1aba45378ce23bb3395fc2e519890baa09c52001bf128819e5d9115e21af57d8cc324728d78749ed88b409193d8c64c92bf28d12271ba6e5b751de903294b757884899ef4c74e2e009e5dc00810652f62c647bb8ce753007c32b77db6c04e426428310dd114ebb176b413ba453191ee83a3410308ca4672f01b76bb995721c6aa77fc4e928eed102a52d34e581323b22476b309eeaff1206a672ae171cccbe214e8740e7c9a4006730741a29cb2fe174d38eed530196f91ec9dd26e9ce79043e9da815299034e58c370891dee528b33c806adc651ee807d597804ce723f0bc919939689d2ff00f22f68a6831cf9651dbb7c7cee827359c44c5390c9523197d91140e8dc4bce3d4b3674768cc1048eccc67198be1033db226dd3f983c1b140a67963016585100b918e432b074761205bdc23a9e10398c2424247fa5adb3be5d6e32a20b0728a2476b442c09f8a4095ea5c31f517104f2420e620900da29d392264281671c364698196c5b97ed23be8d21b7a209b25c30e200181fb265ff21d3d6b711989248081d2200469810046a1e719e464635c34724f4f2a06b3108ea7b29119387512946375dd8225131f0281d42822d80255ed72db939b60349e410641b165ce70338d1e6daa2d07075701c8ff00535d07c19bff001593fe84ddf2c6a24c9c3a0f8337fe2b27fd0c8e91967fffd7e81175029014c9c1b354178a7291c800e2a4d132f140d32794191fb2e909dc41f17924653062792dc6330044f1481d123681a0d5c2267bf6d694ec6c0e101da25a8748c40621c70cef209ee3fc281d1402d02f365cc610dc03a472f8a038318028f63277300742e12cd18ea1da390485db00fa61e338cfaf678317b8105ce5b44859d5149f4a3e093000101b91111651b811a221c58318da09e63717a04422758c59d0201b16d059802d46006ac5b98ca09207640d678c48db97520ecf2f88750512a90a40cce3bab4ecaeeddd320825022586f52511c3406bc3a99766900015cb021a9a2cee3babb17524040e68448c8417a0c2acd9477dc9dc0b000932869cd386386e80ddcbbdd68f2e3951903d8f95a0d3d08fd2e831c7bb9c72f8e8e8257c201f4a3e0e82203cc32512094cf269a30a221fcd24f043b9c7126cf2e0335f213ea5b487401188d3bb9ec8966fbb11c9bb5a40daa2e7961131a01119db66566914991118dd6a927747696321a0d44d84410081a3962c6413ed2ec67ad244c200f4c0538c107c577b4257c20546029a18a21e7c86408dad7a92f040eb1009da1e133c80d8e1d06499e420692888cf7f6f8649cb9442066357191274728c64719811cee40e923d686bdda9477003c1e60662151e47fe45a329a06c234c98db373f637ab018120e818dec63854e42fbef63342747672d07075594ce55d83bf41f066ffc564ffa1379874d94f23fe53dfd160942196f9963c91ff9b37664ffd0e909e1621d84438367166b1721e0f563c562cba64840e85991b14114764625d800f280477675f140eb11a95f668d3c6652ae52244221d7410601e4c667c95265223b140bcd8b7c4c59313b7daebba5dc3049be1024c0782760f073cd97d38d8d577c8ea103a2236f0c9846f712e43291a11aacb258e103aa40498f4e2068e109ed88147474df68139a3bb1d7b944010ccb2e8c7a94290368c6b463d189e588e43267d424d52075802a99d917095f64c667c0a068601418c0edeeb13668b8c813944bd86281b902ec304072964227b7b56e6803cea81640e59a0917e099c0d2041d747280ab07c5d8581a85164f08120848883aa7d224e81dc031ec8190814ec2ea247b8a7106649f0fb2815b17626e69b97b102765229da0091ab909cce530d280dc804029d94e8233eea227ba07261c9bc9d3e196c7a48b72861d8646fe23b9a1b87348a130be5991da139a52862321f135529c6b4f304433a1cad696ea2328d46868de4dc23e5e5039e312d805d63ba31bee81bc5a0731c8413a70ec224b2714893afc55275919014101da51b5a8899e5192322282046cd576b4449818e43dc80231a0921222472e73132743a2040c87d431ed5b9dede796320eebd7e046d946cda069b6a464c92c024f75a23bb01a485b58056e1fe0939836ef847c5fd327488cffd1ea8bb820394426f5799b3127cf23fd2cce44501cdbace89608079680eeb644f5ae13b6211b4206a9a72a6a37481a1981a141901ab8cf11931e913288bb1681da676e665d9b96391d2251e8c8a06131b8109161d46123949c72ec819512901a9424225e7c1bbd312ff9481b6da4d2651918e9cb108c80d5001a648d1c0c48cc7db17a61191e5031c12328dfbddc0a631c368a89eed98cf4aa40d0069995d69ca3cc07b50188d4db4756499f806a24d6ba20652c7644876759c842265e0f3444bd590f645de5094c1078281a4089007c5a7384271881a6813b25e2c01b0a05b318902a5aad4bb140d9c673a35dd3526258e5b84bdf1451c53f505bb5396284a02b4eeef107bb484d31a034918a5bac9d1cf2c6c83fc27fe9206e0d39edfe66ff0066d46c3e2c98cbc50359e41016e5eadcb6b9cf1ca42ad031c8132f140d89d4333981e53dd98c4895f2c6d3299be2903a2637c0c7c43a633e501e7c38cd72ee0189d7508164a490396278b71bbd112c225c940d0af673d84774089f16034d5a1e2e423e25a910452044f354e311f6b77fcd76bf179bd18937e0eb5a53417be3ab847389f1c24e31f379f0c2b4fdfc9240de73005b31983aba6c0790bb078206648910c19c49a76d80206388375aa062408a0ce374ed2883cb2220301919c41a0ed8640eefe9938cc51b0d609599e9f624e9119fffd2eb12d19ee8f89d631a2e0d989b0500dba6435aae3f3c4487740c8ca9c6590dd0e1f4463b758e11dd03863ac6dd212006ba3da2111c06a820720a2e928888f9ba8a71c87768807d415a327238ec09f4e281aee2913676e8e18f198ce42cd7c5140eb33b0e3880111168e33e2911a40b21e7121757ab718106cb8cf1d9beff00c4819cc8f5478ed2f4c7879ce33776e8226b940d0000a6e9cc095fb1678ccf8e1036e05b9896e29ca3400178ba58104827540ef22b57113b9516cc0f36e27151dd7aa06b552dcd8938ed978a36cbc503a37b6656f2ec3e2dc6ea902c6412d424173f46b834c981bbb281d369b798c655cb60d040dd265434e5e6979b5e14474ab40db1e51923b9a30b79a18b60a04bd1115dd00d3948d27d3f36eb4980239e5031c7b88f372e8427d31a6ae7d4dfa676f2100eda369da794401944125aaaee80785c990423ba5c07237e2e59a24e32103a86507e6e52cdc81d9ca11dc0491b06e3fe240d865be54e403bb9085276206867a5b31ca248aec810ae102ce401129dc4807564c013ab5b42031cc2311bb9a4ee1d9683429007aae82768002e9d902653a2a3258ba494134c04999be193317ab776e790e880936d612374bfa24e24d024f01e7e8f36f39667ece39ffcddee9119ffd3de275778f2f30778c9c1b1c98ccc100d3711284685688f505d2fab1076f76142374787724d5f76373266d2030e59185c859f3332eaaa5b28a2268539485e407fd2817eb1e7b33ea5f8a645d0068331216ef1a2811b6e31d6d802481ca400750d4b1c65cb42206881cf1ca3ed795b2453471c7c14c41d1005803979e5216ef9211ae1e5f4a3e0881041e1672da2d63100d86862131aa295600b2e9020ea1c658bdba3946261a03f1206d1f34efb072898c499f67a063301cebf69c3d1f298de87cc81d435168da1e7a90d0160c09377aa074ed410f3f9bc5493dca06e422c034e50047b5a861f3137ca0744838ca4068d4a3ed2c6cbee8160da9e198e33e2dc31ede4da061394a240fe2460ddb7cc6cbd52c4246d1e84626c202345b6a5881739621480935cb23203c291628b14068815ea0f164cec23684088f0403135101b1212ee8d340ebb23e081340f0cce423a16f68edcb66225ca0631000f7204a25e9da811081cf6193301e9041349da1039cfc248f070c32918827525ef31b14f3e38ed1b0f2d219eed784991f07a7622830a631b3cb9c8c89aecf5ed676ea818c27ec2b394b4a6ce86914813232103e34c6327683ec76f639c23b452020c9cf52dc8a71032d7c18532cb03b08797a1850cd1ff00ca73ff00a337b329d1e4e84eb98ff827ff0045d232cfffd4b05d605c83634706caa16e063fccbf62c8905323adb0144907951bbc56ad3b6da52cea1c626503e21d252d95edf2b32c801da88328ca6400f41948694e98850b68ea80232a4c326eeda35114e82822131c808d18f5bc43a12c128a67932902e288e6e346eb770d9d052073e4ce2f82c6e75939140c466f353d6726d029e7005bd6684503139849ac7ac8cbc189e808ee5e9c70d90010223944c594c8862803c3468f640cb703ec4121dea27b312c40f640c59b174e9b698f4c03681a408068b50c8093ecf2a4631a13cb8c235220f04ef8a00ea2778e46275a6304ee009f076f4e2341c38c71d694d0756f091279f603cba301a47347c5bc9934b8eaf3c611f076846249d344030cb131e5360aec8f149a010396796374f3035909fe9daf7ca00f672947840c7d600d363206a429210204c587ac9ad5c087694c0223f68840e5c72db9257c1db28bafafe7da076dc99004db063e6dc114d4e53e0b29dc6c3264072b8f48a2180de2664069518bd51248b228a6d77204894eb8729896fdc3c36bba6ad80e79ca7b4f8b96d9900df34f708de8c6531c71f645000120752ce404f0e919834599341ce632241be13b6576da2d0332256b52ae5d37324b0a6132469dde88c4c2356e308894acf01e9cba0d1038f2f0f3f407fbdff8bc9ff466eb9ae8bcff00879f2e63ff0094b27fd1c8ed1967ffd5d034355db7c3511a3836498b0718253ea002da12b168a48148f4c3bc45b6622e910e538b71bb3a20e037b81d5ed18dd3600402d04c41140b1384b758772458632e618c59e180ce6670c7297da03cace3c9331048bb0ed296e14ce3f24447c10328ce766c68e91265da9d44c164c90064998c3cbf1387ac7b8d5d773144a065eb76a28df66a8bb6c2b40206465b352ea734643470cf6634f56285c40f6200c4448d905db24e812e87ca1e79c9030f56fb14faa3da964ca903512b53940444d86e205a061ead1a971fc4ea25090d0b190c41d511a081a4f2889011ba37cb5e53c849844f60819ee0787394c439e5dc630386258c1e4204890ab63d51c705db6f664c0040c864af6bd519888ef727284771a7a24413a2008e4dda5163266da4001d09a713aa0679734844d0d58198d034dc85e8c638988da7b2048cb66a8dba8993d98313b8177b40ccccd814b9efd58cbfa9b044b875d6b5e503965395d81a244f5e0ba966d033cc0ca26b94c4cd778ddb7bba0d10333195d86c6fef4ddb410044c8f2ce594e34471e5760132036eac035201e5cf194a341e9392c0a79e52d5a0201a018a90eed098bdbe09920671b1cb0624caef459cf66a884ccc5a01944caf571d63102f52f49f2879a037cfdcc29d50c7b40739d93ca72750079476601bd5a42331118178ff0ff008337fe2b27fd0c8f475263e992e1d07c19bff1593fe864748cb3ffd6da25bb7087b5d24370a799b24c0553a000873e120a29d10a091000ee79c4ef8489db487418ebceaa41f17204974e1008dd6e79b199c6bda1bbf62752819484afd8c46338fb9e8269932f620660cc9d5b9034cef3e0e5933980dde081b00472b2cdb7b390c864175407f6827b39c729323fe1689f639c4d9321c140b323934a2fa1bc41e781f2d3d13d103396605e3cf9081b87b1e99394c090a408f508ed6d48d8e35a5871aba6e408c44c22010d47298f62d09247081cd9c13b48ed26f7d762ea508847aba705d232dd1b50908a461ca662878c9dc4ec6a1c31476c88f6ee7a8d70c298427b89aeccce41bf8246491904b5ecd21388d02e425b272d3cbe57aa0402d486a81cdea83e2c7a9ec2ec421031dfaf05d4cb44b41039e19378b03fc3ff0025d0126c53b6d11e1cc6404d2072c0cb1c458d45bb99cc9ddc823e14cc5b78b48840c8ccf839c26657ec2f61a798406e2471240c4465ea6ead087a6e55c20c3bb6011ca073623394a40f20bd18f7ebbd111b726ef10f40e50328eed75ee8b9484a27fd2ea68125c6475b0c06277eda1d9689abe5d1a0356830c78a40995f2d79af5e1d645894a90329e3ddc96e10114c7559c8442073e589970f4e2c6231aeec47cdc372c9b298539b2435d176553b4e56e44de8d21c9d48a815e83e0cdff8ac9ff43237d6e9067a0f8337fe2b27fd09ba4659ffd7e88d3a44021e78e8ec348d8706c9db669b10019dd6dc64c06308687e6c0c74090f4e8198d1b0d2918e24014ebb663bb71d029368818e9cf2e729489a1c2528844b77645cbd8d9648d6914ce529f6a63246538189e4bd422d50ba40e3f4a645783404c07b4b35a2073d69ed72863318ed779111e5ba03540e7a3a7b0ba5cfb8604f70dde2f491a2073194bc13ad70eb48281cd1de52624d5f2ec840c769034740488d8e5b1c242067e64d91d9d16d0262493c2326f006d1fd4e9b944ec1f14081bb52ced91d7c1e903446e8c7940c6722224d6b4f2639c8401e013ff4df42401728e21b36a00b9783ac2723c84848603332913c2c8481d068e89b4539b21980682e394aaf9d1e822f96631db1a690ccce47b38d4f76ef63d54bb503984a4790e84c84410eb5a344d42ebb2073dc8f2191190ecef098c82c774d206173e29a1bc0741316227bfc2e9a20443711e60e6778901d9e805cf24ab56032c826740e22392200145e9de2813a5b6251694e68eead79586296eb25ebd1ca738c6814420e32072f364c6774757acc8394822808dbc394a3bb4f174320131f26b2602f68c63460c04b52e7972eed03719002902251ec1b8c231e588cbcdec594d038fae9dd04f41f066ffc564ffa191e6ea65ba4f4f41f066ffc564ffa13768c3d4fffd0224ee25a51794175dda38365d02d5539c7ccd6e1748194ee5288f6ba81212da1aa160f8378b59192059b70129deaf51636f740cb714ee3c535122434503ba0639666009aecc4721f2df777cb0df121bf4b83e0815095b8653e68900fc5e67ac2a0643202382a656eac48b01cd94d911f16e523c52320d416c0683963203cbe0f5c67b85b91801e60eb0d068818cb76eb1ff2149991c6af4daa072824685996e06c71fc2ef2d542067191ae1ade7c1bb6b440c84afb339377d97a400d0081cdad3118c8937ddeb2110ae50313298d2b47399ddc87a24e63940167697184e5674d29e8259884044e5e09333e0d834c995f081409218b9d70812741240c3119ca3b9d0efad1d31d4634d940e6139dea03d0256347321cc9a3ef40b18e4340747500eda05cac86a33d35409da63545009e0ba582cd840c7613206fe1b74af6b26403129d0b281641be4a4ea28b9898283240cf3441008e410dd0e4394f25306444c01c5341d3115aaca2246de7de68ff0085a84ec59e580dc81cb12a011206ac311f31b3c205e3813e62f4480ab93cf9a44ca35a47f8567235480211b3b8ff00a5b24071b948d0e1da3023528126547546e12e5cf24892c11a2071f51f168f4f41f066ff00c564ff00a1378b29b93dbd07c19bff001593fe84dd193fffd18029d08b1ab20b73200706cd21a06b60671481165d4cc7c903130038e5ac513dcba1906a26c202606aad26f6d77649ad7b3309ee2819c2328927c69d0ee23da1d00b29d101c46445c834337884eea663a72809ce00d45330ce26370ba2b38ef249f067a7158c30a68728e35739668c459bfa1d4e89d1a432b1216f3c0c86ebf17a8d3956a503394f4aa763294468183d83b65bda50204ac5b919c8fb9d40d128184775e8345c9132f17ac0d112081c76472386a13338d87722c539e18d46bc100899f07584c95da8c7a8d50319e5dfba03b370244402176ed993d887723440e4333e0c6fbe1db92c485206067274892ac1d059682f248e94cee915bb4dda04d96e3228a5b60348991743b8872069ade02056bddccc4c88f61dcdddbcd8b2932903d8a06e2fb9608275b6c90352a08a408da7c522c7259130743a3760206728826d240aa6ee2e6640708081109a058de084c68f080ec894ed0e58c18dfbca6739003b6e96d40a94051aee8c62e03c5b1b906f80102fe334eb422880d83579f26437e5407a998147c0b204b26ae3e9cf273e2f61128014d0513e90bf00e72cbb85b9ce467a17310a0c0512c9e1093c2079d93e27b7a0f8337fe2b27fd09bc393e22f7741f066ff00c564ff00a137664fffd2c8075e79407501c1b2a06b4ad1d0517100a896b410349630d01a317d8bb680204185e810316d24dbac4de8cccf6080c3516e5e9c81b8bb80005dc1001bad396099fb1b269ce53081719587306502401a7d96a1a05dc80779f0644cf70a0a94099ccf651236b769409dc6ee9d4e435c1603a5da04e307959c882001a7f1374a8140936f374d39ca167cc6e4f4590ce3f25fbf72018924d11499796a9bdcbbd033196fb140c9b49145d37237da020ee174e2729248a354ee254bb9038acc644d79683072890d1ee2017218e23845398131a14573c374080f5108a4439440c803689c642a8e96f588d0a4185868276e88f4cbad534580e7d86bdac0c65ea0b4818c624311c5b4923ed3d1b5042065963ba24231c4188b753aa814813b427684f0b680282d0099111167866441aaf140a003261e0c659547cbcb632764002259ca2c6bd8ee6f7ebc37a1e501dc2947906e28d070e73f3688139326ed7b39c71f7761a052500eda3616458259250249f33376920a2902468b39504ca2e33069038646cdbdfd07c19bff001593fe84df39f47a0f8337fe2b27fd09bb307fffd300ea1d6dc40913c1751197838366912e910038d4af86e8a06e289f73540b8c010e81029ccc412deab5dd009008a2f34718dc4bb9bb41b40cce3be1031d37aa7540cf691dd1b48eee967c1936819c81234d131891cb5647628b27b140ce502786b6909b978149991c828027024547429802051e541275a2bbcf8140204bc5352a5b3e0544cf8140c71ee94a449eeefaa002092072d6be0802cb24974a27b31b4a04d94590d105ca42481a6e29dce78c48f36dca041d2d00ee681eec889f6a4c480a40d84821cb69f6aed3ed40dad2c6d2b46b84026d4172a3ed451be0a074229cb5f05a3e081662cd3041f058c4df081a1a081419940f83cd3dc380503b3752ef08a35c2369f0409c930624289689d87c1769f04004eb6a4929a27b166a5ed40037f774a2c089f6a36cbda8140789411e0e7b4f8141c72f6a06b68e5cc4655a829113e05034ab60ea6823cde05769f02814683149d87c191091ec51425e7ca4005eddb43879334246274288796fa3d07c19bff1593fe83c3e94ff00865ff25f43a184c432d83ae2c9dbfc137660ffd4f712fc12a07dea1f83540fbd43f06a81f7aafc12a07deabf04a81f7897e09503ef15f83540fbc4bf04a81f7c82fc12a07de857e09503ef50fc1aa07dea1f83540fbd57e09580fbd57e09503ef024bf04ad07dea97e095853ef12fc12b487deabf04a81f78afc1aa07dea1f83540fbc4bf04a81f7a87e0d503ef55f82540fbd43f06a81f78afc1aa07de25f82540fbc4bf04a81f7aafc12a07dea1f83540fbd57e09503ef13d9f82540fbd51ddf82540fffd9, 0xffd8ffe000104a46494600010100000100010000fffe003e43524541544f523a2067642d6a7065672076312e3020287573696e6720494a47204a50454720763632292c2064656661756c74207175616c6974790affdb004300080606070605080707070909080a0c140d0c0b0b0c1912130f141d1a1f1e1d1a1c1c20242e2720222c231c1c2837292c30313434341f27393d38323c2e333432ffdb0043010909090c0b0c180d0d1832211c213232323232323232323232323232323232323232323232323232323232323232323232323232323232323232323232323232ffc00011080041006403012200021101031101ffc4001f0000010501010101010100000000000000000102030405060708090a0bffc400b5100002010303020403050504040000017d01020300041105122131410613516107227114328191a1082342b1c11552d1f02433627282090a161718191a25262728292a3435363738393a434445464748494a535455565758595a636465666768696a737475767778797a838485868788898a92939495969798999aa2a3a4a5a6a7a8a9aab2b3b4b5b6b7b8b9bac2c3c4c5c6c7c8c9cad2d3d4d5d6d7d8d9dae1e2e3e4e5e6e7e8e9eaf1f2f3f4f5f6f7f8f9faffc4001f0100030101010101010101010000000000000102030405060708090a0bffc400b51100020102040403040705040400010277000102031104052131061241510761711322328108144291a1b1c109233352f0156272d10a162434e125f11718191a262728292a35363738393a434445464748494a535455565758595a636465666768696a737475767778797a82838485868788898a92939495969798999aa2a3a4a5a6a7a8a9aab2b3b4b5b6b7b8b9bac2c3c4c5c6c7c8c9cad2d3d4d5d6d7d8d9dae2e3e4e5e6e7e8e9eaf2f3f4f5f6f7f8f9faffda000c03010002110311003f00e835356f29d65d625b46954aa847552328cb9193ead9faa8f6a9b4e960b65921b58fe492425532a42679c0c1c01df03d68ba8a26b976691fe55ca856033c7a1ad6d3e18a300489318900619287248248c75e3faf6ae4d8eb2845a8a1468a5dc496c09320105b2db47b81d88e98ebcd587ba1345911481072cc10038cf6fa633efef5567b64927b833aac9116565531a96c7424e38ce41f4e3d69d64a81a365b3796e0ca5d1308ac4a900b649e703247d07b5170b12cf3950f26d9c20ce4b98d7a719c673efd3fc292d47da73705ae5e36c6d8f746546073d71df3efc7d008b5a8c88658dad59b782b85d84e0f5e37739c7e9f4a9ef2682d74d8985aba4c9b0c71e10b866ca0240ce07279f427d2901686f8909115ccf90a3efc79e78e9c0f7c8f7a6214b78238163b8c05f2d54cc41e07aeefd7f5e9548493347e590e14851b8ec39071d4e7d49fc89a9164967b5d91db5c47b9030dbb37b64f208cf1f5f5a0065d4fe56a76b3fda563b74477bbdf3b06da178c1ce3009c9071ebeb973dfc4f0c5e537da2308189799b9dbd187079cae73ea2a4bc69c5acbe65adc14f2cf98a5622645dbd307afdee9ec7d795842432b23bb42420023648d3e55e00e39e33fafb8a06346aa92ca18440f183898919193d71e84564ea13efb4ff5263770a59565623b646e0391c7e22b42f278a2658d5edd9a66388de7c0e00c9c63d87e27dea8bb4735ac53fddf390840b29604e0b76e3a7e9ed4304753f0ee7fb669baa862ebe4dff9633ce73044d9e4647deff39a29df0e6d65b4d2352f3194996fd9f023c11fba8d704f7fbbd7e94575c3e14724fe26635e3da4f2656e95091d08207d795f6350dbc91470652e22993077384661c607b03c8e9d8e7d2a3bb4654d935fbc30c9180b0c2abb875cb0247071ee7a038abd6e6dbe53baea472dc49208cf53c77c74e33e82b90eb29369e932182368f62aa8121b32086c86e0f4cee5ddb71dc71cd36386379a159c40ad862316d82cbb4a9e3238f997a1f4f6ab91dda412496d32c336f506590c3c3b6ddb93f37b0e076fcea3bbb8b75293086349381f2c3d0771927d7140156ee186eef2da7b8823671f22ef801d830c79e48c76efcf4eb527d916ee6532fd9e5872e1818b0c4ee2caa39c60024608e829cf23490f982d95e40a428f282efea428e78e8393fe349e54920610db461412769451d491bb1f9f3f5a008a4d34c2248a2f2374cff2958b0433372fc9ea09ddcff5abd6d15a829248f0481816b792380aa85c0c1ceee7838ed903d39aa51e9972ec925cc3007d815235507d492a09e07cc7bf6a9c5a3c4d1bbc7c9611ee31a2ec0159b24e70075e7dfde901623b4b996d994c6e92b31c18d101e7a91b89e73cf24fe34a96eaf6ec67908396628e23041c9603ef75031d7d39aa91da4b731ba08c34837ed58e452b227207a7518f51ce39ab09a4cd1afcb6d1e77f5c273b882c4e73fde6fc8fa8a2c024ed0c77803dd4284332a966841d98eaa3e63d76f5c7f8d1bbd4acee608e4496392324bc787c10c57682028e7863cf6fe5b13417514436cee0ed6552046a4678c6411d3fc3bd645d487c8f25c5c3803e491046060703a6303ffad4d81b9f0b6f6ff50d13553a82429343a93c4046091b7ca888393c9ceecd14ef86ba72691a15f225cdc4ed717ef3c8f36d2dbca2023e50076a2baa29591cb2bdd9951daa5e49116bb0aae87700c03039ec0f6c67bf6fca768f4bb6945bc504d753aed749250857774ddc6391b49ac58edc5c4a9079dc700fcc0a8efcf7ef9fcab5ec6c6de0324923c6d2144080425f6e0f3c9c738c743f4ef5ca8ea3463d3ae2454fdcdb2c2ca448bf675057b03db03008efd476acbd474d0358865589018e365dab0127e7d99ef924051fad5e37e64870de592a71b4dab2283d79c9e4647b76aa56682f75093f7917caa44922a9da9c83dfa9e3fce28108cb11b60f07951a6c3b185ab1da724e703d327f10c7bd36dca194edfb39638f9d6c9895232c33dfb1fceac25ec53cad0c1b9e04f917716c8c0c727af38eb55a2fb3b46587991157218b3baee20ed040ea07b77f5c1340cbb6d24c114c36b1bc8a461c5b636f3d793cfaf5a2ed2d827953dbc485b01b6dbf071c6307b6303f0a86d6096de64899898416570aecd9c0e87d882323eb4cd49a6bbba520c4625c8cb233364e0023e6ff007bb771400a217092cd610411dc057485da204af52bdfd483f5a74d1ea7047349b44b228675586150c00ce028cf5e38f7aaaf7621b66df2ec8d2601d5a06519ce0739ee71ea318f5a9ac27124a1dbca0e6356626d4eeda48da0b67d7a0f638e9400ff0032f8c3f3c0ec15b7fca132dcf1dcfd69b777cfb37379a17032709cd327bcb7b69278608532f1aabc896adb0b0c679c9e3a631d81f7aca6b824aca6d18a47b6420478c127e553cf3ea7078c7bd2608ed7c09732c963a9c522c4ad05f18ce242324c5131cf1eac47e14554f85b20974fd79d5b729d54e0ff00dbbc1fd68aec82f751c936b999ccc7736f3c059e38b6ed0a5986ddbd0939f6ad4468e391231b50328fbb36ee98ce3f31d3d6b1f4afdedb88bcc4c3e092400470074c7b56f32bc04bef91fa700a9c73db3d07033f5e2b90eb238f468da3496eece70e790ad290578e0fdef7c1aa90c31c524df67b7903b065900971f30fba1b9ee0039c1eb93561eea40eccd6c8c5cf70ac481cf27eb9fceabc0c44871008c6360f2c0501463007e43fc28019e422def1c739cacc4f231db3f9ff00f5eb423b692220f94045d59cca77631f8f7c0fc6a386685a39229222042c0a9d8319c0208fcc73ed4c37b6f207843a29c8c858f9c7a75ee40fd680191cf6e97cd766794324617699f23a9ebeff00e38a9c496f2cbe6c6c00098dc93b0ce4e3eefae07ea6b13fb30bb305bf557c1fb881573c63839e9d3ad5d4823814986e9d57f89372e0fe9d29d90122cd691c71c31cf198b2719b860c071b4f3f43c73f5aaeda9c02cd91a36dc400e629e4cb6474078e39047f4a88468490672d81d0e0f040eb81cf4ebef50bc76c64f9812091939d99ff003f852019f6eb7bc8cc76b1ced865508649090e067073db18ce7dea09eed7ecc64cf9906570c11be727af2719f4fa2d2bc56f1c66de19594328595c487cc90003be78ce064f5acebb88ed0b0a058a0401230c4e157b7d28b05ceefe0bdc1bad035e999b716d6e7e718e047101c76e28aabf016412f83b56938f9f5895b8f78e234575ad8e47b9e907a0fa52ff000fe14515250dfe21521ebf80a28a006af6a07df3f4145149800ea7eb4dfe1fc68a29ad807f7a67f17e3451400f149e9f5a28a00920fb87ebfd0514515641ffd9, 'jpg', 'charte.jpg');
INSERT INTO `explnum` VALUES (2, 42, 0, 'Retranscription', 'text/plain', '', 0x53616368656e7420746f757a2070726573656e7a2065206176656e69722071756520656e206e6f74726520636f75727420656e2064726569742065737461626c69204775696c6c61756d65206465200d0a52657a6179206465206c61207061726f69737365206465204365617578207265636f6e6e757420656e2064726569742070617220646176616e74206e6f75732071756520696c20612076656e64752065200d0a6f6374726f696520657420656e636f7265732076656e74206574206f6374726f69652061206d6573746f757a74656d70732070657264757261626c656d656e7420612068657269746167652061200d0a4d6f6e736f7572205b5d206465205665726e65652063686576616c6965722c206120736573206865727320652061206365757820717569206f6e74206f7520656e206175726f6e74206361757365200d0a646520706172206c7569207365707420736f757a206520736569732064656e696572732064652063656e73206427616e6e75656c2072656e7465206465737175657578204761726e696572200d0a4d6f72696e206c69206465766f6974206520736f6c6569742072656e64726520747265697320736f757a2065204a6f7264616e205065726965722071756174726520736f757a20652073656973200d0a64656e69657273206368657363756e20616e20656e206c61206665737465206465206c616e676576696e6520737573207072657a2073757320746572726573206520737573207669676e657320717565200d0a6c65732064697a204761726e6965722065205b6d617274696e5d204a6f7264616e205b5d206f6e7420646f75646974204775696c6c61756d65206c65207369636f6d6d6520696c2064697373656974200d0a6c65737175656c65732063686f7573657320736f6e7420736973657320656e206c61207061726f69737365206465204365617578204465737175657578207365707420736f757a20652073656973200d0a64656e696572732064652063656e73206427616e6e75656c2072656e746520646520746f7574206c6520647265697420646520746f7574206c6520646573747265697420646520746f757465206c61200d0a70726f70726965746520706f7373657373696f6e206f6265697373616e6365206520736569676e6f72696520717565206c65206469742076656e64757220792061766f6974206520706f65742065200d0a6465766f69742061766f69722073656e7a207269656e73206e6574656e697220696c20656e206120666574206175206469742061636861746f75722065206120736573206865727320652061200d0a6365757820717569206f6e74206f75206175726f6e742063617573652064652070617220206c756920706c656e6965726520652070657264757261626c652063657373696f6e20706172206c61200d0a6261696c6c656520706172206c6120646f6e6569736f6e206520706172206c276f6374726f79206465206365737465732070726573656e746573206c65747472657320706f7572206c652070726973200d0a64652073656978616e74652065206465697a20736f7573206465206d6f6e6e61696520636f72616e746520717565206c652064697420766f6e646f757220657573742065207265637a7574200d0a646f756469742061636861746f757220736920636f6d6d6520696c207265636f6e6e757420656e2064726569742070617220646576616e74206e6f7573206520646f6e7a20696c2073652074696e74200d0a646f7520746f757420656e20746f75742020706f7572206269656e20706169657220652061206f626c696765202061756469742061636861746f7572206c652076656e646f7572206465736e6f6d6d65200d0a736f79206574207365732068657273206520746f757a20736573206269656e73206d6575626c6573206520696d6d6575626c65732070726573656e7a206574206176656e69722061206c69200d0a64656666656e647265206520676172656e745b69725d206573742063656c6c6520646974652072656e746520717569746520652064656c69767265206520657370656369616d6d656e7420206465200d0a746f757420646f61726520656e7665727320706572736f6e6e652073612066656d6d6520652067656e6572616d656e7420646520746f757a2061757472657320696d706564696d656e7a2065206465200d0a746f7574657320617574726573206f626c69676163696f6e7320636f6e74726169726573207665727320746f757a20206520636f6e74726520746f757a206520746f75746573205b7365676f6e745d200d0a64696374206574205b7365676f6e745d206365205b7365756d655d20646520746572726520656e2072656e64616e742061756469742076656e646f757220652061207365732068657273200d0a646f756469742061636861746f7572206368657363756e20616e20656e206c61206665737465206465204c616e676576696e6520756e65206d61696c6c65206465206672616e63206465766f6972200d0a706f757220746f757465207265646576616e63652065207265636f6e6e757420656e205b666f725d20746f7574206c65206469742076656e646f7572207175696c206465697420657420657374200d0a5b74656e757a5d5b7073736572655d2065205b6f756469745d206c6120646974652072656e74652073757220746f757a2073657320617574726573206269656e7320736920656e7375697374200d0a6176656e6f697420717565206c65732064697465732063686f7573657320737572206c65737175656c6c657320656c6c6520657320617373697365206e65205b736f666665736f69656e745d206574200d0a6e6f7573206c656469742076656e646f757220656e206e6f74726520636f7572742020656e2064726569742070726573656e74206520636f6e73656e74616e742072656e64616e74207175616e74200d0a656e20636573742061752072656e74652064652065736372697074206574206e6f6e2065736372697074206120746f75742070726976696c65676520646f7474657a20646f6e6e652065742061200d0a646f6e6e65206120746f7574657320636f7374756d6573206465207465727265206120746f757465205b6465636f757374756d655d3b20746f757465732061757472657320657863657063696f6e73200d0a5b6a756765726f6e5d206574205b20636f6e646570656d6e6f6e5d20706c65696e67656d656e74206465206e6f74726520636f75727420612063652074656e6972206520646f6e6e61206c6120666f79200d0a646520736f6e205b63656c5d20656e206e6f747265206d61696e206465206e6f6e2076656e697220656e20636f6e7472652063652066757420646f6e6e65206120416e676572732073617566200d0a6e6f74726520646974206472656974206c65206a6f65646920646576616e74206c61205361696e7420557262616e206c616e206465206772616365206d696c2043432071756174726576696e7a2065200d0a6465697a2065206e6f65662e, '', 'txt', 'charte.txt');
INSERT INTO `explnum` VALUES (3, 42, 0, 'Sceau', 'image/gif', '', 0x474946383961d800fd00f7ff000808081010101818182121212929293131313939394242424a4a4a4a42423931313129294231312118181810102918184a29295a3131291010180808946b636339315a31298c6b637b5a526b4a425a39315231294a29216342398c52426b524a634a424a31294229213118102910089c847b7b635aa57b6b5a4239ad8473946b5aad735a6b4231945a426b5a5263524a9c7b6b4a3931ce9c844231297b5a4a2918102110085a4a42ad8c7b8c6b5ade9c7b6b4a3994634a84523973635a524239f7bd9cce9473bd8463ad73528c7363bd947b735a4ae7b594b58c73ce9c7b9c735a8c634aad7b5ade9c737b5239734a319c8473bd9c84846b5af7c6a59c7b63d6a584f7bd94634a39b584635a42317b5a42d69c73ce946b422918cead94b5947be7bd9cad8c73a5846bc69c7b94735a8c6b5284634adea57ba57b5a735239f7ceadefc6a5deb594d6ad8cffcea5cea584f7c69cefbd94bd9473e7b58cdead84b58c6bffc694d6a57bad8463f7bd8cce9c73e7ad7b9c7352bd8c63b5845a8c63427b5231635a524239316b5a4a6352424a39294231212118105a524a524a42ffdebd7b6b5a393129debd9ca58c73d6b5949c846b312921f7cea5c6a584efc69cbd9c7b8c735ae7bd945a4a39b59473deb58c846b52d6ad84ffce9c7b634aa58463524231efbd8cbd946b947352dead7bb58c63ffc68c6b52398c6b4aad845a846342a57b52634a317b5a39946b42181008e7bd8c292118c69c6bd6b58cffd6a5deb584d6ad7bad8c638c6b42ffe7c6b59c7b6b5a42ffd69c6b5231a5947b84735affdeadad8c5a8c6b399c8c7394846b73634ab59c735a4a31ffe7bdffdea5423929cead735a5242ffe7b53931214a4231312918ffefc6ffefbd4a4229fff7cefff7c663635a52524affffde424239393931313129ffffd62929215252422121185252391818104a4a311010082931211018084a524a293129101810081008424a4a39424231393921292918212139424a424a522931394a4a5242394a4a424a42394231293129212942313900000000000000000000000000000000000000000021f90401000037002c00000000d800fd004008ff00c588c1f1a5e0974c512a557a3369d2a33610db3c7cf4a6cdac371527616cf326caa4310d1b16acf4250c123154c4d4c1a12b4ca62f452ac9a96851d3254a92dcb8e9048c562749942e81a14494129b236c34cdd2c49163c8281e2779043929a6cc4a0ddf8c1983318956ae726442a4c9e9121c4997da108d138ace1db773e6dca134854e953877e41c69266b459231a298e099a487461950f64280625141c306501a662c8814e2841b64d2aa0143560d59b368d1a4351bedb919b0d16a804da1cb140e1c5a5380b901060c0e915f967efd2202c311152a901c898114a67818df30c4385a3edc51982fba5a168724500c94eac505861198e93912e726c368ff94c8064c1c3671d43473a3865651383825c5291ab40d1b366ffecaf99809499d4f296da7924b722091492563c0a4d01872c8210a1e2ad551872875bcc1461b48695205279acc41071d9cd005871a7194788926a1c401041c730851871c7ef090010b1eb4c0030f622c11c30c1b84a04105156cb0c00044e253c41c92a8d1c992b475b29315250291222c2752a2c9879c1c41e51174c53147156c10404037ed7423e698671230c098f7dcb3e699658ad98e9a04c4794f3b6ed649669e630e80e79a6d0ed080000ea0334e000230f2c3a23f3cf30326981042c815925eb1cb20836430c82ebb18e16931c518d169313f2c40c03605a0bac036f69c53c0ab0414ff608facb4c64a80abe7b45a40acac9e132baef6dc4a40abb38a5940aeb4beaaabb2afd2ea6ab3d0462b6d01ecbc6ac0b5ec5c6b8002d76aa3edb7dab2c3adb6eb1860ec9cc1dad38eafb3127ba6aeb61eabae3df3e61a2fbdedc8daeab0c4b2aba600020430ce380228f0432237248289203f64b08bc3986eeae9209e1a11aac5a186bacb1582bcba8d360a6803f2b424976cf2c9d3b253edab2bab5cedcb2e530bb3cbe0966b40b90794abf33a3b1b9033cff8f07cc0d007ccb3cdb3b9e27aecabcf32dd2cb2c0c29ab4aeb33afd6cacb29e63ef00df08808ec0022c10c30fd91c9c0d2391fc2069a591427ac5db92664008a7576440e915cf141032c8228bffac80028c14a04db4db166040b62b53cbf2e1d71a1e6dcc8e1b8e38b5dab89cade10a089e2de39b6f7bb8ca37937b6d363e979e73d0d9e4fcf3d03e133db43a07c0de6c3b55b75b40becdaa4b6bbeb1e29eafefb7cf9eb5acb807efea9c03b813b0c0002870033309279cc83391c4f28cc39332730526cf438ac90fa080f24316e2871f0323a886ac77e67f4b0bb33d2a2b4eedacf1b02cb3fd2dcb3cb3e59f5bee3fe2fc435cb8b265b39ef12c743bd3d9cf78c640a1ad431d286356ee2248c168f98a4800f35a000030800b1c0712c420061188b0884d80021a8c48c15fb652894cdc4220bfa1021936e1092dec6207c788412166c03e41806c5be3fa9600ffb1c53f6c09516583d31ffc3867c4ce1dce66e00addb50a78409d65631d0b6c603daa8833060ecd8158c462d16237adfa15c08ccd426319cf08ad98c54f7ed352573b88d400e5054060f400033014311a6050420d70000318d4b00633c82114a1b0422768410b602cd2274e6a062f78b1a44eccc70ab5e0810f83a82dd249318a549ce2273f47aece55f193ebc0c7cd0eb045d5add28beb68a5eac438cb7acc721eb15307ecc4083b5dc28e68ef80dd3cd4a1b259b9b18d2c7b191c9b55ad245e2e7fafca5c33e5b70d31b9690019245400e8612141ae610d6a50432ec6990b6470821548a0c31e42319ad054431aa2198d694c03c950c0a21658f88136b2c1ffcfd119e08af8b8a210c9a533040af466506487ceea51baa0e1e38bab531d445d57b477106d98ae13a346c7a80e5ceaf20008f065476387d15eaac300d16407fce6974cc9c9ac6af653dc331570b9c6b52c731694559d3008b04205800114a08223bee01ff1bce1118f68c4853c11021998a62790dc4989e212875a7042167af00f1ed0300a3390cd9fa43b40e96aa64a2916d0005bbc992a632945579e4eac38cb862dbf48cb89fe92a214adab3063c7577a78340108189a456387c577702e5b2bad164ac5554c951e8e5a3445a9649b19d9c83ad173cbdcd505b199c13b0e6010448044700c54849070e4116c50c226a6c022107124ab61d1c3550a548917ffd6010f7558c50ecaca4f7c20c093e18aa2e84639c4a0a955ada92c5d18537945b8928e67a9231a2dc32856898a11977c8d5d60d511587ad07568d89d871139b7d2c32d9194f073194d25b7b92136715c318556b1d4c5d98085e38e37a0427258621083e0200ac3a002212091894cd401866200900cc9408618fea6c164288316d2f083bd95f56fdc825f104157aecdd9ccbd083c6eb9181ab4ea623168f3a0ae445bc9b31417148162bdd916eb318f047014013856073d12a0cbc0e2781e08986287fdc7b8cfd1d48d938b9f012a57390002f0b1f195efaedae18eae69701c0058000688400562508108c4b8c0087de00313b8601084980128de06374bede2ff14a770d82e3c41034f6c62136630032acca08a1d402b89248399e21877649a1150b807542e1645b98ee7ba0ebb3eeb7032255be46f31379780ddeeeb428a00f17e5880eded9cffe677b84283eea58dfd9c316955bbdb05ab4e7304d81d37080000e0430a52d84dae17518c4198601126c040310af1001368442b5e79c32c3841872470620e9c8876b43794172c688100a67a75bae8b52b56278b65ed829fedc0ad527b1439d138836b2a7f56d65799aa1b837240031ad00d4630e26fa8b29db80c10b4b92200b03c2e5a48332ddc9a0677bc88f3d6dfb2d5e4c13d5365eb9556d216e0ab34a9a91b00331496b10c00079840bf02118e403ed1602550411557ff40032a46b10a34f001175a5005195cf10455b4420bad68852a76ae0a5cfc6109ac88453762118b6fc4c21991e84637de94a6625d2d6b557b164c5306ad6dacca549180c6d089cef558d4e01b353884d80f110bb5fd000aa31015341e5083a31b021ad08844249c81740624c01c38ce348e117e2d4e36ae72dbaa164d23dee4cfc92f71d0e2d7bbe6b4742261b06be1e078ad6b2db006ec22cfa328832ab4c00a6518c2106be68062365008683823ee59277a3722b18db9cfddde339881324a2f77d6cb9d11cf5006236a4030056403148c183a99fc64a67b583c4da61293d2bf21a8433ca0ebb18046f4a5aff5ea479fe86b7f00db9f4ffde8570feed28fc4ff02fe963a1b037668e6df2e2901c8ad6c59b6fd943666e1f456adaa211e56bbb2c79de6e878e57556d691170072a7003170360bc000821003ce702ae2b700f6e68068d30d0e180931a0009331260bd028d920080cb028a0f001bb202990c22882508288f0028940311f7003371003350030d9500c52b00b098808a0a00dca00320ac07c03d00d37f0059d90074d3007565084716005b6a005d8877a71077759f7840f907571a77d4d887a0bb0007aa30dbc35570760635d88574473524df46480073a87e544ca5439cc242dadc27ff59541dff00d91870eddb00d0a2008722708cab02a82c008ab626f753803d5f30d43f70d872000e14088abb77ae4b00ddbff400eace7888de88891400edd700e22930d83f003f8200e09100888700dbcc60ccc8008075380bd76039e80094a170391700ed7500cce3000db900dd74043589007b4a019c0c00ba47006495055aaf084b5477771877452187ee9e32dff045c91365178e585d0a84b633850ed7570348352d6123f389578a6c25388380e87208702d00071f80ddb300cb0700571b881f7d687e1e00001100e04a0002f40083f508282382957f003e7c30882900d317088d5a430de8008df230ede4008d37008038008c5408af7280889f08988800896b0091c930884900dccf02833800f83409183000a25c80cbb8009cf303d05608adeb00978700776d009bc90076e510a65ffd0794c887a91f837d7a24ad9e0506c7550ffd43ad7720057145dd2f58cae6356526486436668e3b57e2aa54ccdb2539c852802100be618308422006bd20d0ed00e02696ff59880cad0350d100e65c20ecc2081578830898008cad0920b930d2fc00ccef00dd7e0037939972f200e897030a6a800d7e0029b70038c900de2800044500c678602f8c00c1c596189c00c02b900d343008830082fe099a0f00d064008d0c00e88700310c90774d009764007678006c72008d2547806b55ca9c340218633ab14511b05510ce451d52562a0245ca2a42dcc882ddc225f7d929588788887208fe51887dbb074f6a60d8220743f600803c091841003b1705f01d083f8ff9008d6190309c04f91e000dd400fbd968ff7c600635336de9001b388008149912f600d2fa0990fe30267360898e00cbe1729a0c00065d90dbe873e1f696f82508bdae088da606fcbb23e99b36451443a02b5686f8556ba7940a9e333b7098619e53a816545367300c67533c00545c4d944fdf3343be57fcd798e714800d519a1dbe043f6c67a7d1890022a0e8840088c5047e1800ea7820fcfd00df810083f100905100881799e8c609dde200efb740d19a00cdb8008a260090f330d28b0049b8008cc90977d389882800fca400e3e548947a335b7a235dd0653a8423295569c0975592ada688da6a2a2245d12058675554b5b144b39530f0cc533889a680cff559c8e3a4011a72e6a923ccb1300f2782801502661536156f780770838ddc0302619033e24900cc0000d60a903800f82b00007409e0620a5df700f67d30ef880082e8009d94008ad2a81b3720d27980198302400e327f7402fc4326e27b35e8e7559aae45008d4a82c7a400c75a8b1a4a8a92434aa330f18e5517f0551dc6aa8ac84a8a483a80e85a2e5a24a24f693fcd6ae2cda77ed530057987c4b97410ed0530e300184e200f2b67aacb7a3087a8725c808f8b0308c808080f33731b0b0ad076f0e19090d3000f680abc5f00c88900591f000c7d80586100221c0017e7885af8685f482851314af90452d4034789ef32d91d5aea1830ff5800f328ba868fff52d891a50886a4b0c2557e64a51ea9000b894007ef56863846333a6a870c5508bfa62c9d5a8a36400e6762d2b953bf6b000af764d8262afb376470303001b07b61b67a9f7ba002f4004ffd508e2440dd8c00dd1e0b64f450b56a00968b00163277612700812500335f00012f00023a0b1f7a6ac52c64cdab85882f7327e375c355340493bb3d7ba503c8b453cab3a3c8b5db6340fdee5579d56343cc6ad1d55a2eb965661949b45066a527b7fcc322cca322c72c27f32aa4df2386bdd9008c4f00593802153d00ceac1bb6aa00884941a94d00c60805463900962a00466c0295a202a665006a3a002646006c580378cf02ec87a32f6a74c896395af6246e3ffc2491fc6381d869b278aad3356a8d2855d19d5adbd5434266551eb605116d551d97a5605f5ae97c32cf45271d8664d04607c16677cd79441e8c0af0e2076f2d60087c0c00cfc9c62376f841809076b6f163c81d523770fd00ddac7276772aceeb2344d933b2d236eb4b232e99558f353951027b5c3a550d8b20e309c401c4abaa4eb451535465fa4bede4a52b92452c2d463bbc44f7cca6e4204a3beb274df306f63c7750c1c0bc4587b72778549a77ac277c54227741cac743dd82662524d582bafac8b85269b2a4f732cae722b55372d47066581c6bdd7a8a7372b3a330b2e62655c857aadd245ad5c28a239835d21a55d41cb5d41bb5d9c8663f6b95c5dff045de5722cedb09602d0c002f00d0f60744aa7c54ab78899bcc91c7c0f9c3c006d6226c6c26db673b5f9d72f67dc2ec83241ad367524b30e2db53f89533ff1236a8a053aa276444f345c0d84ad0da46ebb7900c1f440ea1046f44b52a1cb632105c442ec4b0f953300f56246242dd96675d87634575b4d4753a7d2822ada6832234c32e1bcc64be334154475ee834cb54c3386b3582fbb6f96f649a6643a5904cc76153be6e74b7cf5510377c83d06529d867762784a06042e35a5b296c35e89e3c62fa386de7bce100dd1aa9bceeb7c78153d40438458c3094505f53357a4adb194b414855dfa9c4b3fec4b9cc65df2d063cbcc5d08b056361b94a3643365ffd5a2c85953f1636ecdd28d18f62a80062ddd68323f1dd12833d191737f8c65d3725c563693b37cfa502cf6cc10f550b4b457bf14c847eb5d2e8dc838260f5ccdd5f20003a2d51c02621cbaf0050a1105ba5009515109ba30090a71d62d84d62de4d625311cd41106751043c4511c05612006b6200b3114af010c6ae006b4c01e9200076c300743c1256b10079790166fd00860300b2c1415d0510945d01d75f00575100663d04299d02007b2103291047250075bc11516c2148dcd259430079730071d420940e0257440096e600ba220075bd0207ac0074b20616990064ee00469900165800262a3013fc2024ff004405201cef000ee8008bbdb4798210dddfffd54a961d8b4b0d897502238e12597c05aaee1064481019e002a18f00b396009140003bfc165c0f160bff10b5d060941451d0e061c9f801220871230f4090a966054900390700202820359d11099a0074520dcad5d1118c1107270bca13d1c3134439660095d260696500665706776b6091860069e600677560c19e909ef6d04efed099b4208d0030220808f2cd828a45a82405e82d6d93728cb2a576875142726587b0edcdc2f55c32b668c2adcdc2c76e8d3401d32ed13d4dce22d68727c5eae7c6792c4e74071c977260b20cac817a7c7f76ed8140eaf30300e807bca50803bf4287533379c22679cb20ba0f2de7cbee7bb0002e8630f81736f55ae3751ff8e32233ca1e56c0f8e2865e34cd4c8a4b236cdaeffc4a7d105947cfaa7687500b3532c22ccbfc4d36d49b334b68335b3b2cab6a22b5703a7ffe23587e20006e30d978908e2b09892c22998d26b98a2eb147329bdde2919000a38d53723b33e29c33851d632fbe33e6fa43f32f558eb0c7f8646648893bf1dfa4a738550a163ad57c485580441c8122cdd263caccbcae5ee6da4fe6da94e35ae325fb1a64d876230df239fcf8036767e03dbb33d20308298000a8ee23d98700c98c00ac4eed318c63eea33380ead384dd6bdc8f4a2326595cd8ed0fab358ed9cd154c9d1a294a1c65545eab6c72cf6c79e6ecedf2641275fb8cea2ac843b75c5e226ee302896ff1a00ddf003f7c80c84000237800931000d87c0082af0094a300a28fe6620700559002928403ee613031b706f7f239b82b02d0c90397cd338060d682f73a786873f87739c51948d95fe931caf334193ad25f6d40b3457d3353433f63af3002d912ee992ee2b05dc5387b208df144e6b60d97ecf06a3600875002274500bb6400ab500229cb021b6600b7ad0201252077c800abb40ec0ab72dcc5856603f958f2a446a58708c8ba7ee7a945304cde512561e9d3391f6ed7915a8833a4bfb4c6e3235fb8f8378b57c78f663c22a253f6824477324a377d40d25e024923005a9811aa7c147a350044bc248ce7f1af0240db4214f8d441b56100ab280094b46b345ff095c507b70e886409fc6330ae561e2cfb4fd76ad206aad870aa88235a2aeef3a22354cba04645fa40ec1a44bf470522c0c1005d8096457b060010507070e5420b0400103040d14343051e0c4810614581c58c0deb90504da0d182040400007e3dc5da814a58d974660d6a851a3a8991a4daa6644a1464d5ab56ad2a4250326ad5933379ddcc08163258e265b49f4a0f9812f5b3603570de05b77a0de818a06d6850d0b76dd59b163c99ead67a0deba7ad90e783570001fdd035d0fc89dabf7eb817907d4095e1758b0e1b989e7aa9b3758f06075ea10488eacaeb0e0c7182b3aec18916047769e210a4c4850dbc3d00bd9959e680ff5c302e7621320f9cd64ffb8002863fca22226cc974c2edf7879d4a8111b2519d600eba4144e9c4b738e684a32e6cd98317232894222474e9d4fbb045dc58a152c3eb2e9c5ae67afbe22fab679f376ad5b176ed76c5dd7e537af37b1fffffa526c3004cc494c9d04223b0001cc063ce09d79302ac81e832afc6c218c1e9210a28508e2504203287c0db687ec2180b6064c32099d7114c020072a60f02d93dfc2f82d8a2874d9e40b4dae9b648c3a82ac230c24ea102313f0c4f864492aa840639553b4494fcacd28222bb4f6c24acdcab0d41a6b2cf9d25ae72efde23b4b3efde6122baffcc0b44fc0ae183b20c1c71cab733204ae4ca8b42d3fa488342c05d266b587f66c68b5d0128aff28a28e603380008f681bc03601701be79b62207164534d1d8182984f8958c4074c62c0c0124b36f965933236711503573dd9c4135a6565159532d210a4216d1a6a8844473382484bcd8a552f1baeeeaa082eb1944d33bfbde48b76af2bddab2fafbae6320c8104165b905b0422acd6a08ac8dd922274411c36340a277237508748fce8a173442ae9a400d0c96d814588f07754511729a6181f06798110439ce9e0940c76d9c588623c31c30c576735c3d6329450a20c557601c5b502b6f168646049546d34742b7c37bdb1dee26a2c68e142535b6c372be044d94c1e36bd69c1456032c07e4e60b22bb154285d09cf65c71e460f2dedc2464bbef966024c3cff71a4930400200000ba0e201062c2a68002228869f26c2acce0c004a9e418633b3da44a626e3dea50028d5b5219e5963a52f9e40f670620a0de130b377cde7340ced93590e595ba2045b3544b9bd3b6d9868048ba79e09b060e09e790571c0887111736716111d4512fc6051706b9e107f286fef967c966ff39e9942b4c0df7942f641435834a06f9237bda1149a491485a319dae9b07408062ceded8132d7a99669a635899a6102d5431031554cc50458b564e49e3fc34b448a398f34fe9651755543925926dba89a59b6fba596081481821e7c46e0ca01bc69bd7bcda313cd9306e710f199c89baf1c00742e37eb1a02005a1a1b948c44282df8845031e70ff885880f07e19ac20344ca8c11346827e06909dec6aa78e6569845ceee193bb98962119be266a244a5cd5003892e4096000295a5100c6919b0008408591584037a0110909c62273109ce203f9c73f2632428bce60a2e514200841dc80109860c40218a10d413ce3076a7c06236630834338a001091804080681096730e20782d0460c94c10850302279f720493b06d8449234a01b3fa08018d0700c0b4af18926a4a40a4d0845133ea08228842227fba7006dcca55ba34c009d148319821c2a359c3908caa4f64aa9c906792419803b5464927ca1c3010224c03618b18dccf1910199db8602cea88c6ed06f06db60c033c6b346503c6303cf78460c04b14515ffc68283db24c739bab10d6dfc321b88809d37c4313042bc4e19f8184431a2090a681c4200da480026c0c18c19c462000760863736f18935348328a630451ee200073a680216c1e88206a588c94b4e328a129ce8371102ca61c9252bd93a6580c0e2ad72857477232d174919153c8b90281ef49ae52d2b150b727c8383e4f8a6e5ce6839300a221ce9c88d006cf38d01e85380cccc9c4cf5773f0564839e8860063312f1834430e39ecc70063a0cb0884dbce005cc60840112618d1764c3079b18840b08910d17308310cad00633aee00c051c0c14bef406335ef08358acc31b094004153461075a540318bce80429aac009254c838b4b7ca2332ea9c2056828ff2ce8a9cf5ad6c4d18ece2c4e84410bcbba442c91ba2b21bb0359d4ae56cbaca1241c951280e892f8cd6e388000d7046601f021884838a0a70328c00d6260cd1425e00666fd4171afe90c335e6e9c57d006225ea00c0220a2183fd806221641058119610688784602ac11080c4cf7975115c40f0841dd0524c2b7d95823231071de0278e3078c286522a4a0042158a139b590851276918519405664a8794fb4ba92ac31eda7b2eb51d69af48298bebc05308039cb97d422b9187ac9b2a155cd4090e70e1027311c0d4851897d3aa96e34401bd90023131321880618608fb1c85a3b0c70036409429fd948c08b7bf54505a871060348800fc8f90c441c83115aff75c10d129188adfe80001f28c60b686082acce151f89f00626d64988172fc000cca05f3640f1833b6ec30083880101ae61dd2568410f76d8021a40d02b8d78764c66c90b57ea81e098bdc52d5df94b99262ca09931c64b7cae0858629825f5606922527ac8a01818c07bddc606e8f84638c2e100fb7dc39703a0ad369cd10df58291bdb0cbe7371c204405dc369cd9781d3404708e6b9e030181f8011a71bb0dea1d631aa07001115c20081778428da8300205cafa82b966239dcc004524d0c888487ce3008200a716b7718e9cc1123694deb0b596459ffd80253f3c83566633eb9834e9673f15b68f58dad4590d67699507211cd670a9afd5f2d298fddb9fff2f2d578032063906fad4c60dec4a88673c40b7e1284036ce78db6222c21b89e85536aada8e031899af5258c42e7c6b6d41cce0008110c73560f78c05803380032cdcd41a58af12851b58ad94f467cdb2ee0caf67de11563798e2ad2dbd3866308609ccd2cf348f0adfe72d68897a7d2c9c96f428ad44a72d491273e335214230b6ed5080171570765012e01e1587dd1a1720dc6704d81d2956913d0e808844d01a0431e0ad3c3e70036eff1294ecb8c65319419b03fab071e256a96adc15d20d7ba9c2f3610f3be83d7569c95bc288810c6014240f22980342ef788ca0b135ad3ea3c5d1e8f9527b24bd9189fcea21dbf848e112894b240680a75b1b870020ff188bfdc9741b82604019bbf1c5fa0d7101d9c840314cf0016774cea194a4be2144200243643ffb21e040210a118219cccb87b161fc0e61d390108116f27a764b5ab8d29eb69cc92f7979cb57d2d4a03a3de6408331bad5290b74f7f8133ed11960c999903891dab8a5d532a2dc1887ae1907e6f19a0528061c988435b8409a68865ca0066ce00645c88568400669b0024de08412d4843bc0834d702425180352288551d885f3c9821dd8015060008f10199d233fc62bbfd8439a927a34a14b8bd3630b8fa20ff980b003c10cc868c20791933e4bbdbb6093b678bff61896c6a1bd1cb407d78014dadb86fd39917b483112233101f0a005980114200619a882ff36d0043660834be084476883ecd08e4c50122568124bf00419b44150d880377a2367c8a319d0a2320a89fd51447b1099461c19b44388f4f30c2d41bfb30b0b0da11086700f7a530b2bac0750dcb33dfb3342b3bfbff00a77c38c79e0966e2190c9e8bcc1a0076e398bf78319aa138b7deb08702b9c99233107081d077080077880327c00cd49a414f94507380466149d607cc65f0446607c35c46b87311c4331349cc2b19ac3a91ae2a19702882c2e0496d2a290c6591a0be910dd6997c7933c2fc1120b8bc7f9a0ba0afb8a77903ffa9b3cc56090c5a08cc6680ccf8b0c24a4baf3580f7a1909cee934cf61c643e0a0117aa02552a173c81c8afc3d990a2aff8c24806ed0c65d3c072d6c87d9284071dbc11ef410ce2088b168bce03117cdb0bc7781c72174130b93b0a8fba8792442cc203dd2a330a86b429ffc493c8107da3993a0ebac81a8175ac21f017a20cec148fb11bb6e18c307c2c605a897c42149d9c899ac243f922c19add4ca922cbfb0d49d83e09d746147955919cba34779a3c741d30f75203dc820bd755010a0a18cca78c57eac0c7560bdf5008bf88bb47819cb11194cd830bfb00ccbc57b1cc4dcb9c3440d111188d24aa5551a1477b9082b81bd7273175a4cb0fb4bb05a4cb4c348809d6c3a80540759ac9d57c493d5eccbd66b8bf6430fd934b76aa9c4cd383b4291215f4988c6e14dc92c941e943dff92094b05584ca9c906de943d8de04dd7588df53337bf7c8f0b4330f84896bab88b5334c2773b007a180c3a59c579181ac99045049087f22c4ff2a41df39407ab5b8fc0bcb0d6538f75a008a6190b454108b21cccd0a04c74344c565a4c737c4ce0f108d0e00c4d5cc976298095021e74d444079d100875c9cffa2cf8fc929769994603c500098c75a04b23e4bca5ab8cc39807730897d99107793007145d4ff314ca9f91073170041b090347c8842fa8842f98844aa884499884379884368882497884471803219d0421ad84b719831ee5515dc8041ab1912f008e3010032b0d833c44021ba9834c60523df88236680336a084a4508366a085a4788e4b8083ff4b908429000330b88438e5043608533bad041b0d8e31c8513c450228d50e252d021d9d04ed00d437a80eb7a9824375c337d00430a08429a0844ba08450a8822398033aa8024c958ea5d08313a8022158013458013ef00033f8041ea80016d0023e30030698810808010d50d52778820aa88008081c790083a368066068066940865c6806648886100c5660f8553475034980034970034a50d339980235800337580a7f391b4868924d7104e090d1299d515d08031c18d72fe0521bad045da88422a01123a9522a808419150348108321199276050e7bdd51eb78c34b880337000633758329f0d5e788564a900435000649908935608337b0431fddd1498052ff3c75d72d4502dfe8d32260527e1d033df88e7dd58331a00336980e4d380230900e4a580367b5023888d468d5043a38022bd08150e5033e588516e0031ef00027005a25c0830c208019d00050c8825975820ab0000b08896eb08728d0afa300063740869e90866455033b500a98a5055a980238c0d494a584834ad3a570032970955ff8054ba0004be00d1880847ab5117add562ab00462a05bb3710423d956471083de085c48808118f10dbab55279d5572bf5561af98237988537e08446588368858e3980034a0083665dd323e08438bd844ba8d3889d054e18831498044e78834ab01b5420033210033c4003da7d9230c00e6308d93a10052c1083526002ff3c58053e40033cc0823a1803519005b7910351881b5bd003e8a583399883c3aa054ed0032ed8822de8032ef0033f90053dc08231b803efed0326f0de51608148088110b00016d8005028838c41834fd0983a78033690834fb8052af884265102b84595551998412084836186d729ae6c8881abb08a41b8024cb8822bc80023d885762a060b6e2723188441d88562200442e0e002a6b2b5dd850c60064c10234250ab14fe015030b3e272a69c42c46760860cb882f352a3102e6042306110366187691887a9e062180488a9601af0602d288667d01fcb01c3cba94a4504a65d8c2c0288ac988bb9c18114900009cb893930ec62cb8194c80a19331e9998738dffaadcc22f1c3037d6b90133e3d34088d340bb5e9144caa19cc763873c06a53c939203cc466d1c6442c699423e6400d246424a114f33220100a41850234100054c88601c669886d96087d9e40f186219741832da1fc59cbd578294903c631e62a0e14c4c7143ccd0b0cc9e3337b5280f6b211303489c2e0c43c231119ce3e56f0be32b4e9caae4e59060a0aa89141389622ebe62b51ba2d60a80091080a4822a66b0384cc88086c9648731620f869888f1e68831e153108490f1953af69592114e565e67c2f48c41398d8c38172a91cfd5a30f3261bd756b8bf8500cd8d8e29001b78738a0adbccaa9d94a48e1e282561cf243e882068900ba8d231280f2fa01ff870b2b4150ab0ce8e10d3ee26230026fe66888f1040c06858738a33efe15d98b63c21cd09269a59d038d023d4c3e469481d0c410e112cf7a3fbb988b29d42847930b7cb03f69090cd910e8c109688f00499031e5ad1468d800c97620e8c63191828614f143c0151987232a00711087e2c2b1fe712b4ce8e18cce6620b6604f06e22b0085d3600450a21c8c7a25987ecce0f110757c1c994ec77464a5dcd1ebd0c8b72e31baafb00f0bb30b7c4cba03489cd8e0e5dadb4aaa0647e2c1b991b1ea705b3c825ee8a981da330cc60970804850a3b6dba348980142c0e1d2b6e41ba864088e604b9660d70685b3736be23bbb8b02a5b33b0ddc5e14722340039092ff996e6586e0630d41948a90925e8965f798e78a288f8fe28afcb08afd8899a0ee8b740390b98801a4e642002519c62900a51e99ee1e1ef0f6eee10449d8b01ad4ca3d0580aa276b6f6462041418ebd24e6d101ea34a2eaee202054a7661d8169973fe9594ea1082101185a0ebcf481aa86150dca190b150992d99cff9d412082f88d6f344b688c99ac4c7a9ebd0bc08e8ed7eea824ee579216f55566cc526bf11a717e209205b3a8923da86a8ba0187c38444202329ba828e7698d3aee4fba664fdfe71502884432ca6b7de08dbee15dcb6c4dee610cc3cc9c84929061d8d8b28173fc9bac7c3c572ab70b288bf31e1f2e5e633cce3e9fbf34a1eece71211c9ff3207471231ef555e20a4442d7c01806e780102669d032e84104a043f1d9230008fbc2d039176982b3806fdfead42f82322cfb3b7161494e1080be16b076f14745c492bd19df9a4f0d46870b4a0f098c4ba0a973c31993cbd68cbb3b8c7b960e71e5c3ce364bc7b08a24a41a2126804227d042f98045dc0814dd90468f88131e084b9e184a8788337a89b3ac0834fc0032520838dd90433d88563e0233f46f2deee6de5d6924c7fceacab74f5cbb70b93d0f670cb04133acdd20fc2f68f33518cfb8390ca684c547fa50555d01ed448387fe6009087479089ca35539a30d34b788234a003e98d833c088550b804a70885e945c13b881b4e808550a885241885ff5ec3b33cee6daca04d09a74d7289f00b1bc2b5d0f29f4b8bfee00acd8a99766b3a6939253b918fc8688c818887775f297658a99817b70595f9957c0898d7c4333fef566f07105311aea987365858ab658a80050645a0066e98050a9004a5b05a5f05db4e3053a1385365055b5ab0822df0046bc1b771f96b8a8847a3499977e1f80afd920488cf788cbafbd08f98490ba8a3c907c90bcb80909cbc0cbc0f8c087125d89079c32cf0c2d4a11289d09324d0ad6b75e569f1dc20822950d80cac09458806108c0659c0003bc87cc0220a60050a60350a5f05065a508aa45806331084ada065d934ec09a5d021b4c2772cca64f90ae73e3a448330cea3998ef28fff7f9c8b706979da61c2c7980c43e9cf8e701a842891c844145f59f24b2c97e2b48775a0878d48fea5de1f42ba3d9408807b2881886d837b8ffca3500458a8800ed0813c587a69e086abbffa5f650e3740532bc0543dc0031a10047cc87f64a1acd904080306d60934806f5dbd8306ea0dc467b0e04088080dae3be8b05e368408191ea857715dc790f30e902c394f1e0204234196249960a4cb79f412a89339f3e5017a3549d233c0ce5e017642edb12b60cf27d1a0438b12fd5954a8d0a00a7c2a701ab440d5a405b69eb367ef5ebb0103040808f0204080179932e91a33c94b9b4760d4ac01d3e856880bd58035e3db2c192d609ddcd0ea04070e25587438ffddd153070f1f108232e2c397cd21be7a9a21361c48701d68cf042386066dda40c785070f2cd4bc51633d92f3401f1809b376cbd82ddfcd4380fb404d9af36822d8f9ee1d70d906885635fa949d82a006941a9d7eb50052a13e9f0a74ea13fb53ad460b1020d06d2c59076805302372c251984a5126bd61f3e851233072589de8db49b01d9dc411472873cca1098249c821471d756081c7277c6460d0650e91940d679c7d26d0680575189140ac7de4116504b9e6116a1a7504124b24ad735b49b4c9d6124cc2cda38e3ac21dc0124cf36cc7ce8f5b5d55d4564535171578da455595010a10351d94ec68131d914015700e01f788e50e596805b00d06961023ff46185fe832df246d34d2c81ab318e1856070c4410782557092c41872e499491d482cf8181e4a8c82894096196a4046dd85c861880a310a22900589c8e24115b1e6516c1f29242348b7b1948d8b2d92a40e6e372600dc4b37ead41248a4fa589092574d2794025501f99454056167dd54b5460ae451405e57c002429247007ae9a97783144450014399727c51897c51b4e18531343412c71c9c30d8a01c48acd5e75a62d42186b962e081070f3f087459410e35299d861976172c9004396aa9410c8194a288186583298b3bb2bae3a62e1aac304f39d694d24a2f26470f6b45290ad57351da439053534515a95491fa241078542139de560b9897ac030e8cd3ff8d11963c5b077c65ea62661451e8520606938c3146256b21218623e98a410515437ff289125490b1841969c4a08d36f6f6cae4c8198ef65377228366d1428d7e76c0419aad98918a2d8234306a1cae8321a62421279b4abed1e35b7032a953dc763f62fcf773817f3c2491802bf53191c612405e3b5c9225003ae80420c82f54101d060ebae882832fbe0c0305148b604288184c234d862564fc62c9269658b23aeb9b9441c6ec6568918501da6ca57b73c606eef7c8802b59afbe11a168da8e05b3b8fc8e203acf68f2a2928a237038ea84f7abbfda4a2f56f746b55d74b356d53bb0b40adbbbb15c8d87ace302a8374e003f5842010c3010430c1444e84fffc4229bb8f0420c7ee0896218a1180434420612980123ecc20834a0812722e8893294c10c5a00457432189df409c93adfdbd58f7cf221cee083350da1cd400cf6b6b8b54444268455d620a29081c526472f414002687280e2d80d5222cbdaaffc0695be81c76321bc95a266c5c1f1d8232c63094038bce4801fecaf8afa5b840f8ae18241bcc01033c80228b280892b5c6117094cc32e68b08b5d68218d66d8841966370a3308c24a5bd9861daf42a5221d093b44799250d68144e77da65226525e4674d392b059a748d8e95e865c388fdea42425c0a1644d24f2ab21de0a5805690af89028ac9101b23b1b3416505249007b20ab01e9f152001af00122fc42ff0a8b58842d8de0021314631083208421a0910133d06013c69c1deaa8f009313028134840421d1804a1415d690140e94a57ec718e250a292950690ae2b4632f11510635b9a14da284b480f37483000bd84601b6014f2131a96a0671490252422adf20e06ebd11642785272cc0cd6aa081d31de2a87332f55d8995da742297241700005054001fa8dcfe7eb1894564540aa788c52238f186245482318c7903276ca18795ae740c49608c2844d107556c63955e29cf2a0bd00e87ea742b56daa64f8d158f25ceea49a471dbbf30f41dae98e71bdf7065381c108e6d3ce3073fc84636ae3a350550ad50a3aa2402cc4193bded0d015409de1e3df8c8ad6c0f2bd7ffc15506b999be6d12a0a6e7c0523b90751ef705201d0180df3800a0009941026990808118207182c222ed1457a8432944510a3ca4a20e68f8c42a70f1073e8c0217ad50852a70810b57f0800f7f084137ba110946302212e7d1523bcb738e76ecf4aedbb4ad366f2b577a564758bc8be736d61989583c2016b1a8410d0e71081b28d706af60840b7ca0bf0b10830a14281aebcad0c0415c0114cfc8460ec55ac97c9a1586220b22ac3086358c69a328ed5dcac9520954566209a7e5716297264ad1fdc6c0123080c4b98a5607256821829b48c306b4300a3e444815ac48432b3e8b0a554038c25a50052acca08a3f2ce114ae556d6ac5d28d58385500eec82bff4edb71ac55e694b64cdc662af1688f9a120ba7a94dad71899be3588c98c4b138c40394fb8d43c482112f50450d8afb006840c3b8aa7d402498ac5a059017ac29d9cee13a79b8220d4e48857beb6e85c4e2141380b66219803b22dad7740080cd144d87323641865194a11569d0421aa6318d4280e2181bb8422f9eb00356f48215162884210ca18c678002147acec234c2c88a53a4c1c3c3356e24b6c108672c5a19a965ed79be318005d0b6cca536b536654b80fa9627c4246ec0378abbe31df738c7df30ee376a906be4e6b806c665729363d1e44c2b00bc396ca1c3bac699f30ad164d2814ea4fa2857afd843655902cb99c5f2b8b20040bf14454b540dff710c325e01137e2e04b08d0b6526f358b5998ec48719b10d728c38d39a662d2314c05a652803de3c8ec4210680154430e319cad8372316c008b0e435af60b16f965cdd80062c60064b7e4037a0b164614323ca1e1fee70d9adee1a681cd8c086f702a682cf1cb2bc24a4aa0d54141a738cc99ccbddc455e296d8c4860f202c69765cfbd0d2ed703c151dabfe466a5d1b8b33df781bae7d7a8f6ffdce7a4f7d00e7c82a227e49085f66001328b84236becb0c4c80620646f6040812a18c6f846300dbb80633c2dee8d866fbccf758c00b4e70862ac0c10a7490451a72fce1269b1cde4bcef88e650d6227176b2a886aa14b62a4c203bce36208cd9d7bdb5bd0ff81caca91d01e1607ef5ae633bb23596489dc37e0dd8d806b43103f108720b421cf6db41edfdbe886d3b72188486803f6b1ff0128181183671002ec37b0aa38304176efeebe1b6c278039ace18d036ca218846046220881096744c200c6dcc5349cf18d025c6310d920c73612915550888112c9e0c5fbed60052bd08217b5b804294631ec58c0bbe3fe37b9ff4519c7f19fbded0e8660d5e3b5c4a9b404a8b4843a24d110f9843d058f38258912c5dc6e999a137d43fb3cce2b0c8020cc9ea6659abce11e3c318232c893203c036b31400cc48020b0d687f99853b113fac913bca920d5c49e3674033b5c032124803824402014c30bbc0021bcc00f28000260ffc01530423768830b14c3f67d8301bc00388c5f202002216c82125c0230d0c25fd8811d28c61d68822d7c946b751cbc390334b8a1c9091bff2dc03934de04c6cbdb1c4003425e8ca08dab1011c6506038fd4d7b110e92e4dc9590c73d7089e3a0830084c38fbd82e40800896983bc69836b2980321c423af895970880a83955bb9960148660a691430130422c0840379083d5b5de0f10c20f78830ff48f0b200226c4823c30c335e4d0226c112688c30b6095201840205c8120ac5d007483207c8300acc32608c2391880375c03226cc21dd09f34004332d8411ed0412dd4c2281802ffb161c7c1619441836abd13bdbc8b8694086d884a8c280f723c4a0cb1ff43c530dbc5681996e11c91dc97b64514d13923590c400e4642cab9d60e32402c04405908c00010c094b81603c4603660c20d4ccd0b4e86373843db25c00b24c2108a0333fc402230033408000158c32ff880356c8238684302586322bc24332082f52542483e8336e0c320fcc0dbc9a23264830bc8620c6483205c03a86c8228d08129b8413374022de4812d8882125c813324ddc7a11c1e8110d88c46f32c0a86904dc1409ef224c74088d220b5cd26355be0b455fa94079a3d0e3a8cc3234a4e383c4e2b0e80f32d400c269c203423fca083586443dce14321844337fcd20d285f0c705536dcc0332cdd367843495ae6492a432c9c83201440023002c125ff80e8e4d03524c235fc423180422410400224823768df33744302148333c01a448e5f0228433d0cc23424803724422090011680631e58411ee8011a78c215f41bc821e43c815052b14658368fc8888840788459aa104b549e672491448c90bd08c4513449acf85496f8dc433a00594c5c00b44c2ca9962b69c36366253e3c6624a887fbdc4301248249de8033a8a701c4403750d90dcc4003dc4022245cb11d6136a85f221c1f22a04037b4433db0a40f10823224802010612210100a30023e206121d8c30b30832014cb0b3cc3900d804ebe400630023310022368032224820b6cc22acc81fce5812c8cc20ec480d9d9114430846b0c8c893484096d484870ff486c285249648acba546784ee90fe50ee6954c2309099684d8b685c3213c5538844303101dfffd58012065f3c580bc15a85345d17924c0330802170a1f22bc000a58e2645cd50f885f01580311bc8038648365be207fde80200402110c02266c83105ea3277011088c8e556102231826d96d83a042c30260c2334442289ec30f1402010c8311048212a8c216f0821d84822df0c1337095ee64c858becd3d250adc3c5e44e8614998d3c2b45088b4846f6c4d952a093b00944071d94231d5966cdb21e465383822590c19250e40eb59228fdd402104403628a1339483e470093b046a3640213e58e60f7443360c02ec216536808238dc0056f96624844301bcff0022fca6388883fa31027f2602066c8227c4dd4996e40fa4a037146c371440470e0052ca203918002228830098c3149e42060c426772500c4944778606887cc68610848ba4104ba88d0bc5e3a36cc8774e2944ec4ac814a2982d6b59b4a7b3fed50450e2372c402408407468da79005fbe6555c1ba52598c9a652840ec29c0b90600c326800f989f20e81bd266030c7ad7959c834f0a02c582822010c229e4c02620a16a32426b6615231440ec31c237b4de2134c099b65e086a5a742c8e36a18c974d09cb7a480a79c6c060c887882ca56027c3f46a8b440402621578daa3d58892eee8ce56ac4cfb485554352b991a97eec502da8e603704506032420330ff8220606b03b88c0344a165b4566b3940dbe2830b30c3021c009cc2aa201c2c211c432114c322d00e0d4cc30d8c421a9441d802502414402094a49afa656bd5c0005c43dcaeda2ab1a3b1c81337c5aaacc290d62895df7246033e9e8cf0e1c17088526dcaf38caff542c4f968e9ca98587a8c433904c037b8cc00b0a07342a1bec9e00a0a42f0cd28f1614221bcaf7a5a6283c65e364442b95a46ece1c30f745fdc61553610420624420ef0012114c2c1e18335ac1121c822e8c6a93cbd93b595476ed19736d5ed6e858f7782e75a1e158a248a44f80b947aaf6eb008768206917a8469d0f069802c6814eb51f130cd29227a3e644112c087b9d602a8cc02c8ff1e5b29803551ed8c06e6861242a7a2853bec6767568665b0dc4c4a683b801779587048ea9bb5ad8335dca4fa9de858f41c6dedd44e79455718451e791eb2062252dd30c822956854ca0c6b04f2b0c848d4c4cb511e72ecc48bccc6e42dc4470c446c1cd50b25eed62c15fa8e1e23ceec443924d23923eead9602c813e8ce68c26d4300c96764820203849d478222bc09803d982652a2ab123240375c8311fec0f031823b59e201208264daf299951a36e9561c8799f904d16814332171accadaf0bf44cfc2b0442047e94adc08a920076f18f28aec8866d8b0bf24b20eabec87c4d73ddcc303a0993b044039c7d2b6ade7119787ce2e4ead202d03c0930258ff46220802038028d53240243cc0e3e8ac4f0e020660002144823d20820ba866375853b1244056294038e7542a8d303735455304c5786a8d319f90227f8d6b94c64663673dbc433dac04498c348fd40620e34871d4c43aa4746fa8436994c6f17427a3704cdff40d90d80a223215b2985e25ff741439a2a8e1e002a09f6b1100e80e5f0c6ef287d5d53b59a22ade1abcb596a8b1960214823364752114c2066481575bc00c382f79dc160987993779d905a6d70f75880e2b2e5b7b066b6ccaf2086e943eb3c4bc5c8a78046614d2913ac45b972f7942b296ae985810ed2bfd3400c04f0094837a8e9841f25e70b99633e66c3698001444011ba8412e48831a50ffc21b50c1314043925d5cc931d9032c59693399336c03500d76093b92972d3174dcb4777e6c688888f83e0ac1c44866208f0d4f4f7204374c00f70c2ff23d693445d8cb0fbdf35a7150b1d8d8996d1b2cfd15fcec9775775b0330c005444123a8811a34432ee4023760037963033548032db8c1815c0225cc891c24e706bcc204d8c0042cd72190c021249933245cb1a4925cc5955b9d526c6347ad50a9de223287a4c644dcb645844449f76a0bfd31aae08870ecc4c82ad58187e72651479861d358cbe50010649744d15ffd55627f1b23604026b0c91a7cb722a8811b2842782b025f208322d0c214c40127680283988b127cc212a4c128d4812cd4c12d88ffc22df0419ec9dbb134d473a64c9845393d415b1089130f7f6cdb0c44a2b008da6484e4318cf4205ba8b40ac2203243948d473b4a7a2d07224a347934d4aab152960cc003b883616fdbe7ba400a14c124b0011b544112208826f83918f8b97ddcc724247a1888410569410690110a345a0c64c129ecc01544ba2dabccdd41f4e270d059f31179060b561cc51fc131b42d47bd684d416c860c9b8697b7486ccc0dabf4c80e2587c3ec8d6decc88d14c7855c8a74b67aa114c454508d90c09335798507138bca04574dadd379c01a5485c3691fd771159733800233188131b50ed81a93274090635d8157834221cc802164753a4699bb1df1b2cfd873c71350c0ff93e3beb3e34e47e3ee0acc8ecc296d5055548de3e1a1bcc44bdb280cc0ec2a5d7baf0a9dcaf4e0508eec1059c94659a5447127cf0c331243800cbc1b4596441c9d0f400360dc8775c3dd9d87c7bbd2e8aaae72b5adca4bc021a82eca2b57cb08400374bcc89b476add0388dddd3d44c23db0f39829b49574651e3549af14941e6947960dd2f070ac87a8794dd386e0ba063623cf83e3460ccbc81feb137190953ae8844c54d22277f48a7408061a055db583964c9c2ba57ccb7cfc693f4092ed3342e2d803a87ddd9bbcda3740cae3fdc4f55c38f7bc7d7d859c3bd44da9581b539bd97713130d45561c8e1d3905b000522775c75b876c5b93861d37b336c7ffcd4820c76d84f46cb80a20af74d7733d8e943e4a20cc8237cf6930052be1fcb3affd2bb46d03605a71b91bbca19ceeabdbad7503c6f93ef0a7d6ef3f34c4519b4375854d19bf95b05243c1715079053ce551ce759997c1d7f010ebc66279c8d634c5ffcb0d8bc6c1fc06d5534f4a3b8c34eb934a4bfcf7d3ea873cae33befcde3b19c83f1dfdab168fddbe63a796a88df3c6030401020b041238678fc03684f6ce9d2bf09080bd8712ed498c08b140bb8905242ee0f870234890ec0a282049f2a3c89305d8b534a080254b032dd91928606026ce9a3a71ae33b0ee80cf75f5d60135502f28519f077e163df0146850a9efd41da83a8f9e3aad07e655d58a00ff81ba79600f6433c0d4a7017c4571e22c40a05b83700d1ac43af420562cbc0fbaf1edd63712df587f07fbbd571031c2020e25a624c0d1e14787db4036a6281233e6c69a333fb4f91065679aececcd3ca97367db9ca355f7f4894f69b6a0b0db0a7dca35ea6daeef6e574570e0b7d6ad5e858355277b6dda75d9d2ce7cb8ade0bd6ed2efddf33b1dbbf481881f9fa31c12e3629191238b6fecf0716590e9456eee2cfa3de8f72d5992fc4cdfbee9d43df9e76cfef328a3d04a0b2addace2aa37ad12e8aab8e2c2b2caabac902a2a369fea0170b598e2db303e9456e21044f0380a4db4d03e04ad2593243acdb402e2419126d3f4e38946d5d812aa29a19c2a90ffb7a29c5a679ea8c4aa0ac221e729521db0c242409e24bf6a90b99eb2c1a7b52ab5294981973e73eb219840d2e6332f3b13b3b3ef363433c4341ff228b32b3123912405ec69e94a9b723229cb9a14c8b2ca2a2f546ba8b3649b90a89fb221eaa9230f3ce01da0ba4a94abb19254d2c9b09e04d047a0985bcbc69c78da29b59aeab3d33396ae5ce9b39126a22f2612e703d1550d4b8af5a1534f1a6dce395d72a94a769a4b4d81ff2cacedac9f2e44342aa092ed8d2be19e22f22aadba326e497994b45449a5280c3040b69ac2943fd27a552dd79974ad694e150d3857d759eb230d5e5d738d97b456e785b7a58af08b17348af03d375f76e211b7340c75fcf6ffe0b5a844789db5ea210a62881b3d72a87910a5e7007a10c06a48b114cd6aac79e4b196e491ad65120178989467281f0fd0d6476331d5f1c696d6a929469c750615dfd176a54f3f9a00f679689ff735dae7566f0d98e8d18aaaa9b94c67c6f442a772a43059a2de99e7c8771a850a4823a55547632719cc4a1d784656dbe491575e591e481c09038730c2c8e48b4a2a99248a4aa298e48d49da98e49136daf87b70c127f17b92c5a31843ef2fbec07bf230be10031231ee0ec3112490c8a49231449f44134d8e98028e4e8059dd0d38e0a004f535e0b88476dad9e0a470c107df5b8e30fede7b8cbc41af241339f46e7c8c49441f430ec193089d9337a437dcff744a5e473d8e2ae63883943be8f01e08383809e28d3b84a8828e15c610058d15944823831cc45082911836888005162aa820ff0d186044015f68c60091d10c6444431acd884635aa51400336430d105443eb2861bd3880e175b0034627dca0063840227398134326ec06babcedad717d8bc21bda30065da0506fc43be10b8917063188a10e9b83041560008930d4a10e9910620e3321ba2f8c81139a008324dc400b3734317571b844e9acc7864650020c6c78c4ed1af18847bc610c77e31de826a10b24588e84a1ab841c8e2807218a4e0e63a8831c923086242481136d281d1b68a78938c4e108978843284231873fc641136eb0421d44f13d2cac000d1eff28c3123cf004332c61095aa00128eab7810df06f074fc85f056630806e50001811246033aaa14069387075117ca22424613d49c48112719803259e08076000920a5420c60e6fe8431b86411768949ce4fa8683179a717279a361e57e28062adc706ed6a4022432314263083113400c5d125878890a761018cd681d305a27455cf2b29692b8441bd860b83148af71936023e4c2800420da8d78c5d3851be358473dc4d18de17c831e92a0093668620e47c0a5142931074d580f0e868cc314e2200bed0941164910020fd0e004277880054fe88113529004542c200415b0c0fe9ee004fd85201204188001dad0894e0c90800c94062d9ac14e0e3e9116b470ddff140d798923c0c18914840315885055226c738799b3e10d85e8883a606e6e763ba6e58aa08b2f6c4e73c51443586fa8b9129e55aca04b9ee1d8c006eb455011c0a0851a80618538c081964f54031629b1867ab6b08e4850dee78a504212daf0ab75f89cf120c74630ca018838c4ac28925085d21d010cf334242504f9d70c4a11758a8c031de4108455b4000d3cb0a9073ca085345c61109cd4804c3b90010b6c20068cd0a901bc1007370c909dc048203b9fe85338c481897f452444fd685ad7e5921296d8c4222c41040a10830a96a0000cb069371162759bd6244236ed96c3f4b695879afbaae6b62944f3de4d88cd7b8443690786bfa6961612ade04423ff5a4b3068829eb77b83312a01c6705261149bb8261e3e81073c0071a07094c341eb508a5b58d8c29f60a41ca4f7064ed02109b648c21dee700686ee1275b49b832d64c1852d784f16b208c21d765c0a0ff060087ec08210f8b001466cc0024fd880086280821980020354d0c31de0c00b3774220f58b6821542510b42fed1a274a84212364c6238d6a1089508621d3eb189626c02039bf805112c6109f006739b7580040cc840012afc8287597504e640584cf2e2b9d05b5d2b0eef164ccc9db50e0d66e12342ebd036446f8f8565031860f1063e225813794cde247ef805c041ee13d754c2273e810656a361d524ac4314e420063cb89ac29f50021a78ed6aff2aa0810f145602153e11865b103bd5c3be2a15c84067324cb20c65d0821130b00b2318811057000126b8fd83182c601b1ed1c6b8f12188712b60dce9d68699b6e12685bc1b3368fa0e4cd0bd894d0ca2186fb637a089f08b3a93a1ce75a6b39cfd2c676238420975b6b725c84005395be21765d8b7bd37e109371763172088331986fd09f83a9c0884b6263160104c86379ce2f62e8627f2ddf20c804210d9f8c10fb2f10c4108220631b8b92000c80006a09b11dfd90665c2ad90819c03310b403ad211031d831cdd204a87ce6218231eca54bd3b1b514879c65492925c694f57527701c0a40d98b8094c6dc986b9d9ae736de0a3dcdad8b9d9d1ad0d4630ff62dc777ffbb80b307470f79d2005093c5cda41807698720089ff46380420807138400189480422c0f1831b6022db84d07c0632b08b410c62179ecff7b5479f6f4f8022e98c3987d2a1befaab5b2625906188652ef396833c263de9418f78c2630f34a9a94d9df194ceda82339be40c27dd1088f2e1a2d3e62b5ff9edf8cbf4a1af7ca657bff9d9cf3ef4976fcabf0c4000e17040001ecf0871c8fc0782f80126ae70856c773ef446083dfc416fed62c83ff457888477c2ddf7a1f73d21b6c13b1aa221408232d824f740e220c8e32d6c4f017daf001d8231ae8ef7cae3f73ac433c6cd4ed2ae4f3ab0030f003106e0f91083fbb48f3b4e10054f30fa04e21cff44b01bbe21fc0280fc1c601b04a11062401c9441104001f3b2cdfd4ec1f33a8ff3ac4dfef0cfda6e8b1126a3ef008fe84a6232285001c543f7d824331ac2221e4202adae012f90ebd42434c4ce5370421bccc200c8d0036b8339cc02dcb88320062ff0986e3b9e0e3a9cae0d07a20d9dce0edb30f006af1bc02f1cc2611c02c001ea270640c1db18a107e72ff476a1f33e80f48c60e58a81068c601277e10ab4814df6441319a14bbce40239a300a8f03d24e23baa2e44bcc44bc44e4348421b58b126d2ee15abe4501aa60c95a32c687128980329a4c2f61a22eafa4fe9586ff590ce1e588f32ba63eac2cd3b0442000f42e91643001f8320564fa76070fcffc641000481db1281197e60066e6011436f10e4eff32031df30a0e5582edf2c3106b64113cf6d4fb084ee40824c802f33ea71438cef26dcc44e56831d60310cab842998e22c061240069229ea8139ca422a6e232234a21d2aa2f0eca1f032822229522028f2202a12222d12f73412222992230c2f240beff0044006c7010002401b668e1b9941109e41f376811032e0f3c61112f1ade5ae6d1c5dee191e0315bd0e56bc10f86885554ca455eae35dde2520a7c6359e322a72243918e52918a23b66ef22ae720119423c0e0232a4b001bd9218630ff7d443a71aef1a0540017ee0255fc01b31a1f36ab227ef0f128910e370f2fe3c61173829ecd0cd2f418454ff04e64deaa31595f24b5445553c642975a6150da031598357f6c346d6c22c060453d6422a9465210f441db8322364ef2bb370312ec32b1fa21db8aeea4ab33d3cb331ce52001c201005801166ee0612e1067e20126240f36ad2f3c4b1fe76e1fe74321c4f210b00c82fb5e1e782b2eb3c84245e443ef0845506333a41c34b4e624eecc43e642246d205543a302a3665400c32286ad14010e448be5223ae50221630221f4323b2903c34a20a3f13331890145d332507311b122011c421117e80111680fdd8cf0735cffd34ef037621411534f44ee10a8e4119da8dece80e1fe95129dd24334ea23a65452556312953053f56e33183e667f88366662328cc0247ff1ac628962537aa723419c33371af009fae2bb190f72a9021d06333121022900efc5e730617e0190cf119664e1972d3fdae8019ae6040dbcf079734f3daef0a4e21b8dc111ecf0eddc8ae56c0e426b8743a2dd4540a5324f0f154c0904b634227c48e0355831df28458a2243c8b052d16a62c0a322896e5483a33461ff02366f431b832352b702224f02a0b35251843a7dc4100d0610619211bb20114642e016220122281db9a94193081193615138ee118b8cd103995dbb8ed0a388913d3adeed2f430892f294742318f3231e5832616f33e7226677ca23b79055c6ee432c1052d70e265966347ac22033a23503943f7c6a32b616f234c912b2de3f6823425ff65932d5df23f192137b9ed069c141340a0494b350b4081074b1513789093e4311ec9aeee3ee35478824333d42464c543e6843a0df36760c24d1fd3573a056ad0b05b94a33f04e4275e262986e229a8822c71942216f02be7533dfe141453e2321062001a40481f4f016e80636df3362d35127e2003088126afa05ba9f4f244f55c8fe10740210b426006bcce2ff9e41db1044e1df34b97665eb4d339024624644454bab33564a24f70c44457b45870915398c2618862161f262914e500e8f33de9d36a4d1344e4731445423e5bf32451320056721b389619bca136ffd3191e8011127464ddcf543d1513d68f5ce716140a2166bbc4ec5a316f5935458e2631ff9dd368ec656976e65d86e6f88ccf667c25547ca45f57f46068a6655e347283244836a3341390ebdc034763af3edba33dca03e9eee162d1321043f60608a15339d61ba1e1107ec0de3c810618914ab92d54cf756e0bb16e67a02f19206fc72edd44b4f876c224de9457b5c43e74a5f878824fceb04a62066989753359f4425274178bb53c9d624f279673a3303c384473c7c37211a20f1b0f25c7611cbae1063ee005d4f70536351b9ca106ba0103c2e98682a90ccc4076db0f13c6b56e0b01149e6106dcf138dbd531df3479c59035807635da621569a21577e26624d3f8065627bc05697b824a88d56194051f98622191e53696054230833db677733d3747ffd5247c094074c977fc0060005ca0aa16e1171661f430c11948001f90400f262bb3860de024ae1832e041fb77068c734fcc2d4bd44d03db144e8956323f4569e8c446a28658faa3532c185ca44440800236767117cbd3601d2651d42133d6333eaa96592dd71e4753fa8214251d201d1a000a06270aeed811408808366103626111568c9cee480fc2a99b926d14cac013f4f214f6b710deb17755d52dd6b43167022011173546c23446f42784360c53e36666e279b7b80c8f820c1172429245845ba640a6d61ebbb08d37a4fb040063ff301d02a004c0600dc0a08b1e2185bca0125860068a800e92a816ee68c544e7a0e400b32e4cd8cae0b6044101945810ff1c5303d58279af3992fff50c05b679bfe528d402510a762097034f5f348421458c81025a38c439438456ec112144972ec857065d0097d5400d705911d4600dd6400de460032c810e06ba90069a0ecee0c4aa801338e10e9459148a801392400ff82003a0f9257eb74dbdd3447b1535a0e6938985451d172a7544299045599425333373475e541dd64138bc22482045525e99a645a29debd3f006c01d60106cc7a11ebc401290019f15a1191441a875391832400ee6c00e6ac10a9e48af92eaca6ae112fcca90fcaacbfae012c5b0893dd0673a106a760540fa755784c568377a4e511a47c6533357ba2183421da8e259e6fa4720e4436e3a5e4102af31e3a6ffebb55f3a03e90ecf1d30b6f1c21611c000af20a8758e80129a211790e1168ac00e9edaa7688183928183d249af58c7757cca0ab45aeeda35eda86461c830462a188bd3c22504b65f0dc682f3f427a6924292e2607f64a5ab172aa4764ff71441e2ba2185632548c45e1fe24504533ef0fa44da45566d1a0bedc13a2e9651c1f61e7080972668758a0a1814411ab8011896a011926ab3c13b9d12481aa46175367b7578a116f8009af18103491b435a63826fc48a3d798b9d57297a822b72442a4afa65284695531a6c2877511e853886e35918441d9c8325dac55ee48323eca1af9332b8f1e5c10d53204437f10a5b06e9780adc4012d4609fd5201714c1a8ff8d3a154ec00e7c4aaf3a811694ab1aa421c6c33bb99c4b0f320027ccc22c9c9640aeb8b585825330a5b5712694e99b4505dc2910a547a8126c563959e6c16b7edb2a14fc515c7a2ca6bca387173ff27a5f7a56564cc22d70e54d5ff52de022f1a29bfc02c01a366d0da6009f453c17a8211a72211796210de8200ff62014349b95ca5bc61308bb39a813e0c00a6a010da6840c3bd8830164613a707a1546b5377a453b9858cfe2c8259721c5b8a5e7da40a22248b6c2a5b96263a8e541bc020134661e5e4243403455c6ed34de04263e844bb4f326e84d562c42a746f73561d300706016eaea9ed7c0c41d3b1734a10236610f9a0008a4211aa8811a1808b9ff34fbc5afac1374890e5281100c609a239532a984693dd02774314af8a36a8c36a417924555d445cff97a9f2238387daed52101b6023812e5372c65aeef7d1e6ea25e60bd4c43245562bd1ee735de8ad1cc31d601761d8661200a0ca70d1a610d2448c49b01169e00147400189c5d1aa821da357b8304bd13ac600e1a7aa26fae0c4b5bc7ad59a3c1bd35acd82085e242924321a14236ccf92964e329cce1376c5e6ac522010ee0e78fe437a4f6297e9e2be005319506c26f0545f2f13ac93c342aa2fba23be165d007be0071bca0111a01b1f1d9b046a10b70001bcabb1996ebc557c789e040b560610e86590fea800f3081b4c9909c99f7ac5bdeb56b832dfff2d4366c3b3395a2d3353d37d68137204541a67c2bae5c378e846b82b66f4763d547a456699556c11c45e67526ea713d5978c3e9190012c1111c216f1e07092ae1e171b911e480156e600d802119ca5eaf56bc1356fc75a44813245a14eae0d6466117e69e200db2f8a2467aab8453e83b4060a36574b1205d66f0039c62aa22d4cdb3288884e8e97d38e45d520cc42b7262b8835b286715c2bb841ef9e4e9c77cccdbe31873bdf1fe90fce4970ad86b85e8ca8a1e410936e00b90e1e33768b29f6b0e00a296a64b736cd5d153a70e9e4fa3366132904d10c489d9f06533803123c66cebd6e1d3a8b123c87a060ee05b7791e40193070c885c6912e58175f5ff60ceb4b972dd4d9c30d5c1a4b7529d4fa1077cee5c370f23bba5ec0c302df0b480810250a33a852a752a3b0518a13ad526d55e5776540bd8a36a8fc0bd01ee04b80d10c0c10f29301c858952298a97477c1bad99d54a8a22606e0a777233254e9c399caa54499244949c4ca2ea88a9932ac3457c2bb3c5cc4672a4488cf5d6b93cedb2a369d53a6782a65933e7cdd63a69b65e39af27cedb2bdf1d98a70ef8d07909d421306e1c374ce018157855c0949d36b2549d2efd4a562bd5e9d2b54d770afe3ad7a965cbda3b4760c000b7e1d0c565b08808a4303832559a34694c1b308dc0544af3483370c40147287368c2091b6d243146127ac8214742758872d9ff273c6c96cd4517b57411482561b45a463481181244a61d505149289956da4c2672d45a8b35dd56db6cb21915d4700914751c02bfcda4536e458167cf75d155b555744f1d4996735931651d945851571e01e9b5f5165c036c42011527d8f5057e93b4d1465fa8505098157354a1091d6fc851c91871423819849689c1d04319aed65268259218226a4d29b59a482302599a4e0624aae24ef59436638d409ed46247f3b44654514309759c51aa05b78e3a575967dd94501549153b4552b754aa4642d79458642d059d93e59d95965adfb4e5003a028cd30021965001c909617cf14511794531c95e756c0284159a1cc14912106682ad7d722694091edee22106ff13aa44f4d144e642e7e1a01e2a15e5691da1c827442d79f4ee4940be265b4e3a7d64236dcaadb4e301f40057944d980ea954535155551574b2b61ae5754f82d7d5544e29e01c9565a1b7565b8708105700316c32ec9799205b892e3844c1ac2e9e447189267a1491896535672287186260abf3270be1c1041eab10a2cd47da6cd6dcbaa68948a253eb282c22bda971e4123e35b9c4114c1d7986626c9dd9c491bd2ecdb81c4e9c2610304c0814975446eaa6da95575e41a930574d41e55dc2ec667595c6549d93165b0dbc154e000b786209053a87c178189918a30bcb386c0283266fbc31891c61d4b1791d8e8841c5653a53413a1a7c8cc2c31512e19337ff46da1860b7ba4dcfceced2838e462f8bf1deebefa3365dba534b333dbaa26d3ff62414a73ee2d863026deb0da55252255937a949f6fd64916e6b2455797f07be9e5b02381040032f742946e35f44aecbfa2a5b924118727c81ede6788a0109e95490a133e8fb2b810a55686133de799d52a0932a03320d24b523546a0c7012d2c4ab3689cadabe6852b6ddb8ab252aaa944dd4511ce3746a47c941000292322885a9f048118b8ef4ac6224baa5102bde2b007a088025f105001d31401fe39085839545c1178e2006113091832ff84f7ffab38425c830ac27e6800c1420c328cca0851fc0ae8019390b75bc48aaed41ef3aa8599abb5493a81ab96478b5c90dff525c131a33aef1442b5a0edb4c789c1c714a54627c0a57a4c29d87b54a6ed261caeb9404ab5ad5b03c0bb052f840e69e712c000330489f231c8103220e030ac4e8e42230010a2990619496d8440e4ab98954aad20c9e30c326ca500632940115a7c098369c13b75495253ceb72cac3cab8343976248d599b17d778e2af5e9ea64f3209ca0110400f3c92f0999ca29892e6c6a4bbb56a29dc89ce212de6c2bbd1d07be7400f5bc4870ef209c0055480010c20014f4e1ad1883ed804210ab10b29a83295c528c62e7661847e7a62a09ea08127545906553ca300b7bce522b723258b8d452ce131638896161315355345c5ac914c3ca2ccaad9c63398ea11729eff799c103ead5d2ddce6aca4b4242431ec9a4c5a24e008d08e470a2092f89002e98841814ece93088bb0860b06f10365102203bb18c42e32c0d45d5c611769f8a7278a31d04db8b20c6650050a1e5a4325897529ab8ace4add9591342e9336feaa8d6a38b8a4be8147359c7997a6ccd1a9e00825841a4961f46835aa288d072c889418d4a6e724bfd9b00056ea864e03508e0074e303bfa00211804a84cc12c1078bf041315c400865c4e01859c0040aae7005a6a6f69f69a081168ae14a54980196a8488336b6e11c5b2df2a50acb4a55d8c5cb952eadaea539c96d8ad922d9d4e6226049a0010ed9149280f483268c66c150faccd1f4958cd7232bc57e0b35ff5aa5507ba61ae7adcec1d8f43c122e00088002a490592312d507f4eda70b3eb00b4330021357c8c2154e01d57f16030304a581815db98951c0d20c3128c036ce0256564d095606282b94841bb53362d078ef9a911b5d439e4512d64327b11a4a4d9880b56d6a1e081054c2b8ebb6156a0756129b9ef54855de5b51c591eb0dc03800d00d7666f6178b28f2673f6b844110c21091f840563d41602994816450b42219a8f0092a784b093c30c333b6b100073f18bd1086f0439ba42a54cd6a556204d4013cb8469e70902adb682c01b6b148d865e4221ee1d1da541c941e31b05dbd4597aaacc742aca0abc24ba2a82e01671ec0d9a31d8e0519c8c611000038ffe006bf9082a71771814514e303c5f0ec2032308307088b0c4a40c3273a570724d4a1089c68d018f4706b3988020b684803011670969b9a85cc6601ab7958852a8ac68e69b6b117bc20129b083aa7cedde8c6371a100b603b186367a9d57820e21911fed92878e4a3690635618b4d6f8662d5e556b4234ea8e876c78bb5e179d6b29e00800c2e995e8011e0abd95f6020958b20f226a6f1800b7062e163a8d61b167e075bdce10e9cb0059b34d1263dc8020baaa83301ced1c88f8b7cb1e835b6aa761bc36f6f50266b84a0eba8b200c75efb10e17040032221889ceb5c018ce079011450b40da578e83049311e19b8cd84314ca6d429d520b9a34b63439a00ff056847d571aa9e2c651a00ed7dc1653b49845f64d6126327c32e9c61823b8ce1d60db20564207e87ca48a814ba160513fee08c6ea4c74a566a473bf83e6932a317d2c6f61e759cc46c1475e880db0073b56331f3433860f28c60c60b0841880f0ce2f298c8c60f22229195ac2dc507288ed14dc8c7f0204985eb46f7cfa737a5c3b70a55604d4b5aa8f2f749e33b4b5ceffd0d800a0362508118f08484f08945862cb8600c92d14329d050073a95020fb7c0c32870818a51f0010ddc47832af21e89c6c77c00f7b052f9714a007b9c25fdea2f76b1ad5ee61aaa3fbc20692e98635eed07c4a20187e07f3824ef000a307099b50846141f9745044896018980ff367fb623a7074d83463112c54b10d330d98115607115e6a5630f862b7cd758033038e403003fc675032005902006efd43ffd836554205578c0073ec304a9a004b8a07dc2f004aa800bc2000869d00aaaf007d8870b7f300d8fd70d9110098de4586ac1777f2369e7d17ec1566c52287f54f147ded37891f078b1e0855ef80d871086874086e1100984e003990509b1360abbb001cee00c860087fa350333c000278647d52528321631884653b34243db6453c5063885088239153e70910ebdd77557a6337661199f104ba340059e700ca3a07dab8006b8d00b69900666800baad00a4fa00502a40a42a80a3c807dacd00db13000d6f60db1c08503f00deae1ff77224775e96716ed701e90761ee541780ea6850b007204c0855ed80dfaf785ad58030df00d6318868c000ad9f00986700835f0855e080ddb080d4ab88d0a806279188166a44d469230dff11508941def863752316f6581673b7653eac777f780886e413e8bc8880be0533aa382fbe309acc00aa79006ac804fd98789a8508a5ad00a41288a5a00919ed80a69a0055d750af89784adf80dd5a691afc87737f471b6871e82b758bf780e63761e21c79149f885d0d092ad988d35108bd8f6853f90063550030fb08d3ac98ddb288b91c008078057c5118e2dc63d4d818e15c85d2ee52a81b848f1008ce8d50e2049697e478b025038fac888017003a3c06ab5d5ff0ba7d00bd36001d3300dc7300dbb00910c3991a7c00a59209071399062299004d90aa7e00c4a188bd5460eddc008cac008e4b000b3488b7f379538557553396c1f376ce97543d53600902793301909da68992e998db12093d8f8859ce9929fd98dd09084e1a70d6aa336c8c12e50e38731141eac8781801875e6616ff6867ef63800888825eea19500900e70b10d9e004b0ba605bdb001ca300328700a66790cac700c1bb0018500872e1909ce300372589d86a00c85100370790c5c480e3f8973d39073912086da200e8cb00ddd400e9130002038921f770ebc7843b942008e656dcec899c8289d3db99f9909792de993db5899dcd80d61a600d98036e670ff5d44711c48c74b8ba64019216fb0531dbb541608f47a84781e7dd70df7806feab11eee50383ec688ede500e19005c55091589406c75008d6a90cd0109dd0a08cd556991c99738c00944bc80833d0a38c00a441aa84de7808fab60d89200ecfc08590f98a94767e2008824baa7ffc170bdd8099fb29a059da93b140a35ef800d8189a34ca8d3a1909182308a537740f587420242a87f65233b52ab0d76e4d099b1256858c6598b609a26de10e58c26f8c2800d7d60d66380dca609c8c600833e00c340a933fc9910b0066e8b9a427c98591800f8cf0710ba00c0ac0003aa70c79c708cee00002a00d37900da7260811c10839a70c82c0088e856f94e68456f2ff0020da00ddc00020900595a993971909642a8b5cfa00adf8005f5aac5aaa930fa084616611686a3619d412ea826ee5085e72d53d2fb581b5c75893a616e4b7a77caa755c1717b75aa5362a8bc9c8914ba884a369a343ba00a11a7e0ad0788c900d9840084af6033f80099d170319020a3f90053f100b02e097f8650457a0a30d50003f200e31000aad2a08dd808855d9a19019093fb00932d00973200a2cd092c1da8dbfca8dde289a5daa8c3bf999056a4bcd5a7acefa3b1f949ab0572baac25b2bf4a6ac32480ff5777c779beea01e83233efb061702b00055ba0db7e5970ae0aa055aa0ea6a8c8c30984a888cd65600d030000520083320af069008d9c0ff0c83b0794bb50b848009f96ab6ccf00164fb02c57005ce4073039000f7e5793ff00c5f865ef7e0b320ba0d18500749100a56500b71500b47600b5a30a0561a09fb99b8893bb23ed992895ba54bf87310c11928621369a33536611d18b39461a49a643453d4637855529b1d9a75023000fa16b4810a3203b0aa89f003aecaa9f2da730bf0ae503b98457b0f0cc073f5fa0c0af0aed99005827003cf90af37807920605a0c1b03cb1a1189500c19f0b546b00ba0d00dfca7002eb009839005b15000b0ab0d8c7034cd3b000c400447d009b460074d300776d00903120777d00adda8ac211b9a247bbf9fa97f9fc98506fa11a0011a3c317af93213ccf1144e67ffb3e13456722a757d879b39e416be120e03b00d27490edf7034cf2008e1db0de7a00d39b70d4b1a0b8d270889f00cf800bb39a7af756bb675ebaadb10a4ea598bcf780e088008d6800809606a57d0af98f70389b00846300d03eb0e05700d83c00c79570009f0025780019ad00ccd900cb4000c9d600753d009a17009a1c007cee085439ab87999a58c2bb598c9858dc750112a3cba01305a53123d718ed7c11ddd941dea7615d35116765a43e81582eaf10d028025ed110ee1d000e909943dca854a9b9e6116a440aa73ab0a0a31500839a70033a00c0cc008550a7964180be4b09eeb197ee2ab0d1a4caf88d0b50c7854cc7003cc800925dc4f8510c2dfb0ff00de3008c710033f800fe2100906e00dd04b0663400b51cc0b76900756100a77a00973d007433cb28bcb8d8bbaa5d2b98447eb3a062011f25212c7844c6f0cc7310448de3c2b7de8944c69537d774e3924c187900e02c07378969ee20bb5300cc340da78dec173f40c790d30c8cfa89eae68c1e4d0785bb80d1b0cc3f5596d46cc0c098008dec00c3ef0020fcd0c742b082f3008a0200803860949280005f002c77008ed507a2fb0094a20079d000cd2000cbcd00974000b41c006b59006dae88d4a28c6d0cca591fb6bd5e1211a322f046c30760513d6644d29043d2f067b9b9bb3e3841e5679ba6e118bed1100e100c84a1b09daa00c4b28080bf00d52fdff8c838c95513d00df898447eb0c9f2cbe91400e3a4a0e03700eb5f8ba88506ac5b009e040000c1808e29000a2660489f00211edaa88604f2f800991db0deeb00d887005dd4015e6700dbb300a74d009c920ccc67c0797400765c085214bd38b1a9d8bdbc17816a11fb2111c754c3892b935521cd6f11d481943447d580b93b38ab58bead1a7e8f40dd0f0c74e8dc60e96c9051003d37008ede52b3bf5c70de08caf18d0b60b744b4a9ae829bed9900888f00288c00ce2700e81b009a0c5cadb9000de1008dee00d8b90dd3fec0d88700398e00c094008d3f0c70480088430b0e7700d49b50dd9700ddee002781007bc000ccd000c56600567a009a4c007ca40ff0e9add8d79b9a88b0ba90cb540a801412cc735926230c5e4132ee157617463aded7a08cc81b8c716e784955d1da8a08c9e47fbaa1f2c08caf00d3be42b61ad1e2ba977eb0aa91fec798210091ced0df92a0e0cf8033b1e0beec00ed690dd9bf002cab0c4389c00442ed22f900d0f0d0eae0abd0b1508519e0d804d0811ed0c068008784d0477e0befd3dccb54007498006dd09c6333da4681ca127622e1ae12214ce1b34d21bc0f55c77ce45ae53c73fe75053714bd2512bcdb5318e54dce3330ee1100b34f70d32298b9f0a9435a0a98c80c9b1f00aaf5073ddb00dc81bc93ac75f98f00cdac0001f4cb735100e98ce0caa0c0ed02d0efa6a08e1f00df240ff05bf904a98609e8130dd836009d50bbdff5ab6a0c008e2a03aef4db74fae64ca9008d3800f3bfe029f2007a1600776100776100a67500aa3d0b6cf2cc6ddd8e01f6c2e1a42113b9d1130a1cd3ebd12100e1edf2481834658b1f3503c06b40e300e024073faf68ccf980d66300db79aa34a3b03453a01014075894008741b03ae28f0e71d03b67403bb000d04bb0dd760792ec00c89d0cb981003e4e0dea0b0c4706ddfe2600dd14d5419b0a937700397f703d68bd8f5ad0c1d2a0830bc00084008a9fac3c540d2766005361f0a5b20895730035c0ac6b68b6719c12f6e1e3620c5341c64233c514c3fc2340dd457ecf2349f4b61e2258865c1b339b443112cffc1be726791508bdee1f21b1c0b51ad6f57cb0cd3a80cafe00ed47d033fa0bc39d7c3117ba0318067b67e05b130ca12e1e5c5c00c9b50085d7b0d8880088bb00bca100b0490009de7c38cc0d1a080736b8b09d2dd79825dc2f64d04ab7007a69007d2be054ab00b9870d5a3b9db703e1137f1212e021e30825141b2133e514705937ad6613b1b4e377215ce71ba48eae5a73b74a285333ebe52a003bb00ae9ac81a1c09e4f30a01d00e0b8036f90a0d01600f3f500c037fde3340ae98be0d061008c5f00cd9e00d1bccc4499cbd44550c83c00809f00c4b7e039f85093df7de40d7f63afae4ca309881cad7449308ccd0fd8900bd00b1044d1c5a9d6a095192ff06d48c480bb615b0a74081018aeb281ac0b72e5bbd75190d74b448f1c0c78aeb381e587740a54a8b074eaaf47851e64c99ec28dabcc8aec04d763615ec2c10940081014505b80b20e0dba106e19226fdd6ad5bb801d9622870d64d5036418c0e091010aec0b6042fb2816210ab5da26d0ef131bb7a63d00d4604106d22c42c5122663ff826ea6a80189162cc04014e60ed859141579425223408d4b66b20b239b30baa8102668376f5ed96e00ab4028198bdc8b1ea0cad3c74f4f0d9118311a3020ab469bbe87164369419b3613430f2c06f031c4506e79872254be507de1d985731a7c5752167b2b3d8d3804d9c397506055f60683b7760050468f02d7d38ff014d050ce8162b1c811fcfb4398ba4609ba01fce1c8475a71b0230e94601426648e4861b0cd3e6064cb2c1e40a4c9c11c080408a7921910410f1269b6c1211279b480e70c10542b639409040883003835d9e11e786046e0045990408c1e4990512f821127c5ec0f18718bed9a6bf025e6026101a78a083163b423923952c1891c826dc641a099fe03eda48cbdd3ef24d38037e6369b995946b4e1de9d69429bbedbae32ece9bb62be03bf00868a7a8f20440e7bf6fda038b3df8ba11a08067041124926d6210e41e710421d4810006f8e600b3b2b9c1197bbc79e1072a15d86a2b140ce9e61a1798f1501c4c18b9e63454af59c405b6147c61934d06c1111fff717ee075064c778dc4804cd3fbe61ba20628809967ce31d10734b408c28e3ccec082106d6aa3893a3083ab8ecb6e41d208a58f5a42a95c33ab2b37253569028eddede67c97a23ae565c7363bc52b6a00b00270e09043fe0c47bd7fd58b6500411815849c6e6e50261c6d08112712a7deab8a516dc271071113b9e2954a6d6623ab98447445c410011271e1056faed9cb1b50ec4ae30623a4d8e48a1fc41127114c3051469b1772ec061f4f15e0eb03234e89a4006f20bda6d61d964842162304a9ad80dbdc3d2e383235a24e4b32c7e43a38944e6a0e2593d21c495b92ba65133b767bea499b9eea0c0fdf01f6e4f3dfb0920af8cf58c821f23641a0212011ff936f64a69043d0496a807b12b08a9111fb52069d02304c241b899ec9e6876c7605ec9b73cc79e1d46b1041241b4612b92291628ac19590183075b0106d7ee8ea9b021261e41bc822e9669b734c7366800406c104146d0838273cdcba9b29242dc7fc485c9478c3c83ae3ca24d7dcb20f50e7db6c69723b5e78e186bbee02dae946dff2f80e87bd71024047a90118695f1b05662b381b6516c8063308f10c67848371ef290057b481280564031a0108da357c000e8978ac737e2184219065806b09e205c5f881023e90814dd40a4943fb10cfb481b00229a31b0bd086380ac088ae30621bb3018f3dc612949f588d3b6d022245b4a42dea8c84241531a2d9caffb44495cc037c4eac4e4cc01612eb1cb18a3451804ed871ada09c632846d957380e118ea604601c03888500b611896f7c8c11da88c40062800941800a44042cd67f60a840052420018270803bf0a12bbf00661b1351462476d18a633c441c3ed884320eb0091464230369a00286e01289addca0633454401c0d2088a8140051b7d9462aedd1bc02b0f221e031c044d865c4357da91ebab1087128c291dff0065de162e27232324876fde68a58bb1207b768001e0aa57ddfd09703d071081bc4cf9af189c5027a270837c63111f5399833181183674422169212000116b840413c8311b1800682e0b23bc02cd08688c0d53136e00322d86a13ac60c02ef8b089ff45200249a08844024ef58344fa6f1bed4b803bb5914a2f2e807904d8a1fac03311dce8927ac5cc0d9834323dea75446cf530136fccb41c92a48d6db43ce64cae8413b9d9a66e5fd49779c4f8a762c5a21b9150543909d095fde5879b825086006e87a3587cc3016434a588d261806244c20103b84620c4c108d58d923fda00512240b1ba4550c1135a98c1074661864d708a108238043b32f7a848d0701b4dd59df086620fe181c795eaeba10faf4593645e04a65b4a49f4127ba6952e2725cad9884592d312ae5527b144c49a4c76f21d7b10e01e600c4bfd94a247a4ce403f0b50c0a166730f44e1838e0a88c10fe8188b07342029120904227e20b467d8ff2301a6c9063eb4a1ba14d9061184c8060ad2200715ec02131560012602918841e0ca73c8edca0d3f46008b0e65af04e8ac78c4ab438d5a29b3d9b82275d05b8f9682443ae542693d9e63267530a76c2961ef63bf262671654f6d58db22173b9ba7bb81051de318073ad0e194aa002f9563d1cf0d6df80c0ad795ab3720040a0a58bf01e84f818c08c70112e10dc3106d1a0d28c06fc5818f1b14a384c5a0418db8990d6b14232fc9db863d17b080480cc58badd468909d07bd208e2b5b2a01d3f5ca543db199c9892ba96ff81e6bdf8c4cd6b2e352db7f6be2a637dd0b4f7a32b001df7348e101d5a2c153c00266389ba16e15599e5bd084cef39ed94d441cffdbb00be462808fcbbcc7422f1007221661841be8ef860bb0476e1171030f09e2a1f96ac75e9b770ef2de29c85a9cd74eae231df79aaf6b673b9bb8ae9c2eefa1441df535db3a9e531db225c7d5f580b5b68c7365f1b1cd3bdf01ef67c91300a498675ff07980362d2a11be2a8001f85380e71875157c7c5301dd689c85167880156703111a2add0fba618f6ca859014d938d54b6618f6b0ce203e6ee919e06d08e76d8c3dd7e256fa575180f88cc8d3bf66813663992df599764dff84557acf77d9227aba3e0f33835f80cbe7095a094d5bf8c22dbb24c4589bf2b3be8d3214edf17008e0380e301b8c702d2830f658007cfa0da9f4362cb9518ac7c8520feff0f8a1bc23271242006ddb84724eec1bcc4bce0197b61883d266a806b6002770f75873b8a126956569a9542ae53bdb48813e811b15b1cc948addb8b5e98a46ddf2b8db2c29da88e77407125f10d75ba9213d9acafed8835c94978c0ebd9029f87711f4f4a03bad100bd77e363a81584691115476ee3839cf569a09062c1f7f88085000948440c70f302e455aa44d8dd718e19318f443c8301782eca3d760edee6bdbbd242fec916b3382fed70a938e3da7771126bc5b5b1c4e10d4fbbbaa0a370f0f51e7ceb58f893cd463693704bf66ed7f2dbe19543f17c79e301f0f89cdfc3f8a2b4e585da9c4dca31baf21fecb1e50c205403c0a2f4c7df204119f80101ff0cb008100a82bb0418f701ae7103477723d2ed26bdbc21029e7bf1df1eecf83fbaa13af3e1b4fc8ab8b5b1076dc908b50317b27122846b8e79780775b086794809843338976835cb32c08fc8af358929b8ab9b55b2877bc813f3f8b8f8490a49490aee6a88351290376204069008506104c3ab23db981dcf13b9ff888f718a810fba01d32aba772200a08a04710a8119c08cbd82081f0b8fd363befda39bffc337edc0b7225b1bcb42af213a227e9bb295c0b226520e54eb3d3304be79400033e41ef4aa0e37e416ab333236b9882b64a61c2a4102db978ff3b8ff7080ff18146d9a811dcb3c06880106c0146e62041dda1f631ba519909c9c7a8060039e7aff70014c989d48808648100108080150d8005000050808af3b5133a8abb7ff530077ab177cb343acb13a2ae2372c833dfc5a3274c13d9740b8824bb85d54077a3038948289ed8189e2f3c210249fd40baca0e82cba33b03d4c07e81b07079880a4103707dbb1b15823186294d958806ef8c39f8a8f865094bd4342688086588885249c8110e0001108814208c50dd080699801f08a422944458d62a6cc8297e21ba9e0a2b890e8ba94e03ae4d01ae1580e338c320c3c80357c2c87b3ac6fa148cc6a9b1f8a3aba61c627248a06c09bf380be909c1ff4103f22898406180002d09c02f846ee12b9cd18840b708428a0044d08835d30841a98c407808649ec494dff7406687086a01ccaa1148119e080452c45772b452a541f0004c0f0981bb9b908eb8093ab5b9b0fcc32893c89297a0993682254a3070c4c80539b0784a3070410467c3880051c1b3219268da043b88b258aa8c165148f126c9f9c1280ff380f4919490413cc57600ff1fb29e061bc18700122f8824768865ce0866ae0066aa80669900638c0830da8811a188111d84ccee4cc11788011e8499eec026840b4b160261dd2c73ab18d2ceaa11ec23759ba08da8445ac6bcb33b9250f2417615c09b6344833294b954080875407044080b2440027424e90983594284e4e7b3ddabb892b9c1ba0a89b579abba2f0c8f7e84b8e0b4ce80380f124cff20c800740840bff88022f68043550046e984c6ec006f804065e00023870033780032b100516a8810978051bf097432081cfdc4ca14ccd490b8a717b253cb493fe8388555c45662232229b43f6ba9e03f437253217e8500e0b84b2034800958832364c382152af2b432fe5a34bbccc4e4b3b87336b1ff1338f3f04cff91907f214cff174806cc0001c604f451052c884cf22a58668000637b0024ee08439b8045bc00251580525c00333d88011a02609a8016810014358c2555cd08778a506ddc7baa9c1da90ba4d53ac3141b2df602fac2348375c099512be26f2bd33f4bd327409ca42ace2a34ebaac1aabd1a806c5a8f7bb8719e54b69fa38c1144c00180746308130f08247ff00033540d266508366680645c8056aa0866600862485035bd0833118033dc88481520234a88324b8833bd0035128051e608563908d731837666450f22253a9eca1eb0c0f89a80dd8d42c76e93431399b2aab9ec85a29b4b9533c6d2cfb1ab5e3ab8ef2813bed40454a6ba555f22ea3f8135fbb3b8f332300e8860ff80230b8044ac85445d0d435c8d44d45063550036058034a60034e90833af80425208332308332d0021aa0022c40837d5502336085416c8ba1101e297c3a2103568d7c4a0184178ac08d810435de9c48ee213595788eb0bbd3530b9ffa82a2b1db53ab1b9be3dbc239811bfd633e7c0c0a44f3acbdec4e14fcb806880129388147a8823aff90034e98822980037595d73530da3560033658833788824ca08232b0044f48831db80242b8820c30021ad0825dd805568881fff1aeb923c1fd23afa80c8aa983caff4b1f2d8a87f4810896954e20da58801438105d89f912beb3c4d35d7ca285eb45e840b83d1d9bca9acee4ab4e3b614d56d2564a9bb466cc1740318f43188005b8020a98847b6d834b485a4d00034d7883cf7d834a18834a40822f08033110030ad8044f38850cb8025004854260c219880150384446d8b1b09534c60d8f3b9458fe13c0a9cbc8a97345008b17ea101f5e4a098f00cb0e5d09b30b51867b4812bd5b0cccc0adeb1ab06117b919c156a2a856c255f8138f182580bd2c9640ff09079e54c75860044c28062a400239a884a6fd02d3150348a0023218853258dd62488353b802140085253404fcc00f742447a9e82ee1b128f17a085e0d54a088252eaa97a0a0d01ea297b31dd69920a9f1e1b483acc873391774a9af115538e5049fb22ccb123dce938835d9632fd94bbeeb64cab9fb2eef42348bb2a8071800be1380af688f06a801be6b80493c84da6a804d6404da15451488902be800512c841858c2d94042a0faa907c0b97bb0870508b9f763ca778308d6ac4b58d234a0b837eed8ac2e834ae8f1c239ac4a9298ac93c83a50fb9e3afd3a3b858eb05bcee34c000c9c077a00c6e1e033e11048afa9a28958462f5a58a950c76ff02971ff2c5f2c5ebc06f88a31ea1701a5a61a18507f115007b0815710e5506e80486e081e031e471647a960651c6658f85b58960cd4d80cd6d864a68918d6bb8c251efa895bf6479918222dc14db1f98d61ba25115609b814cee94dce0448cee43c4ef049cbe2acb2fc62cbd7338eda04d331f6ae43ed8607600a4b06361ed6629cabad1e3e843f74004bee977e19d07e798576a6c698739c076807d1f32cd1d3e790f3622fde2b77e3aef0624d31a69bd40b400b561f2b044062bdc2e275178b880988ee885973e1e5358957f3cd2773228283b25d3ccbe30ce45303464046007998b52bf3c0393c686674dcf6a9ad749e6732e264a67880f5c52681f90a4be6bb01ff6d80a7f26975068b94948a9d1b3dddf5ae66343df1629e55d2d6d6cc34b3ad1bce421f01fcbf77811e9c501bacb6220e4c2c8a9edb5203cbe7085c914d38b2ee5bf0594306f41221328e558c373c23002dde697f69000918d09a5e5f787a003303aa7454472dfe6bdada49c6abe4721e8aee3a6c30de2b350b5bb26d1ee6916512ecd58998cd2a1cc1edc8227aa1882cb4d038365c598cb5642ebec962ac7a303834b1d3fa42ceb2a48704484b915e4e609ce65baa2c3edb12e2b88db12044474e8f4eae81bc5e474dbce2e136a7bcee86743c6e714c6556c6b9f275881dfbb15452b35562c987786ee67308bea2ee8c32d31a6426dcc04b45b64bd8ac17f0ffae4b8b15a22092cb2f4448956ab8c81a17f75e0ef95e09675e6de434ce6846cefd2ecec742175ad412662c5fee14bf9ee6692d46c29fe2b10527474afe66f5f8e608970a9d23eac6ee2cf2baa86df52b2003b2a5d470a78eea89e889007cd0aa1e719db0ea01249f381e97b8fca5af4c5956bbbddd6343283ab51546ceb13bce5f5c43fe960704784336993d9df0a21eb6a6f8a16b7f898ac34ce52b2687545e476cc2ab2567e5a24e6c3c53506ec5a8c716af1f1bdf278408c6dd720e2f530dbeb4a8a3d037a9d81567f136318e3a5e514e4366aee9501d37e13354071c47ce680e8eb5ee42eb60490230704b0ee743585f687821befea94d3c6eda427455ff5e3ca930ea854d253ce3f0a7db6e967cbaa7ebd5052df38db20901e4c713177102b4cb4daba25cfac0b4e196dd30a97189d6684deb84c3f33cdf7373f87104a08706b422f60240a2088775d6e922d63b9f9ac4084f6546d7e265970a2d761cfb3bea661c5fea6e3a324fea87255b20c3a88d8cb720bbc31367e96c8fbab41d40edf8a1cdf68e6b7dbbabfb3a94b62cc7aad3b3d9f1b15bc38634b83e56c3fdeeeac72209e2d0896df82c6269aa81878f8289e42a67eea16eeea3d6d628dc488dca74d60c321d42dc298cea4b33f788e5bf96a513028493220b099b70770ef41e236335fa7232b4def13ede6f91adf7e5ec71e645af2e3c225c2b5f6f6e76a9ff18140161ee43f52cdd8d25c8566ae693c26eef2210579f6dd77021a337e03d5b4cc3b402a0b7b6a57812fff65087dbeb405eb7833db249b58a7040e7a075de0bbe97eff13d276bebad2f2ea9872e64af15dd229383bff72bdfa1103d1d76c9e7d6e10ed7a86aa7c24eef2221c3f6beff743376d1f2ca229fc0e5d62332dbb48ef4923dae3b93d763acb04c615443356806e91e9fe61edf7191cda5b3814b5b5326889ff687d876f22abdf22a73be2a7c2e2afc7da4600db6b751af93dbf8a18f87c563ea8d93e71ee004cb0b54b8b15ff953abf7e3e4efcf57f96f3122ab8393a73773a7974aa9d7c8da9fd8ef80d0326dfad897d8783880eb175eeb6fdb71ff9f972e63e38cf7ec71299f5f42090bb4c087f3fa9498c0123597b23e4e79587978d8f3e3af77805887efc040030604663370e0a041830a0cb08ba8a000457605d819a078b140c68cec1458b4a89122488d2247a24ca97225cb962a154c0c69c0dec68d0f25626ca8f321449d3eebad6b18741dd003d9881e38b06e5d36a54a81d64b7a40ddd40454a75e458040ddd6ae5ae569ed4a55eb817a07cd065d68d6ac4e6d06315ef4687063cf8f21514e74a9772fdf932bed9d1469d123cd91110fe7ccd973a64f7c410d3a5edad0acd2c865a52e5d5756f3817949d551b52a55aa55ae5cc322001b961e68b204891ac8e698a1c1c70d15bbc5a8db2edc8b1137feff1e1cf2ae6fbe29039b6419bce64a8bdae00eaf7918a2eec5b7add7a67df0f1d0a34b37cfd32c5e7c67aaa0d5d19b07fa33e8d25b4fc3436d7aab397904099ac56700ed50ed3edfea945847d34504115dbc455418731525771c60cc11c7a06f882d48513c820d061c60bfc5559d4e9209f5e17f07391699424521e514549c95a79e795b4d15a33a091c10d66960cd771a02e6c08390404345d61f5b22eae79380d45d54925d4a1a8891822469949184ca0146d35d8255f45b4c25a1846570095e29a062d99159e663686d47de789d79b6667954a957237a326e458f585ea9f655586011d4907eb68d38e688d401c6254e1c1936d2830514f624458eea05a946dff66c58405e2a49c9e083192d5a118018dd34a8a8251aa0df42071de55452449137da67aebe3ad59da879351f5909f5c7d4404c09e49fa83e81142a48735df49c5d104d949745971a266985998e246581362dab6897c3b1b3a14c00fa046888b515b9df42ac027522acabb298d43c349ec75e8cefd908966a796e258f39e600652289dbf16b1b4680fe5bd75b4e2249a16ebe315665988f1e46a9450a3f9c6d61d9364cb1c4848964a5c2da22b6e1c6eb28c65daefd963a1451f9b2bace3beb84b7ea3b9e1df0b26670aaa39e8b369b57337aeac063af3cf2e6892357f20404003b, 0xffd8ffe000104a46494600010100000100010000fffe003e43524541544f523a2067642d6a7065672076312e3020287573696e6720494a47204a50454720763632292c2064656661756c74207175616c6974790affdb004300080606070605080707070909080a0c140d0c0b0b0c1912130f141d1a1f1e1d1a1c1c20242e2720222c231c1c2837292c30313434341f27393d38323c2e333432ffdb0043010909090c0b0c180d0d1832211c213232323232323232323232323232323232323232323232323232323232323232323232323232323232323232323232323232ffc00011080064005503012200021101031101ffc4001f0000010501010101010100000000000000000102030405060708090a0bffc400b5100002010303020403050504040000017d01020300041105122131410613516107227114328191a1082342b1c11552d1f02433627282090a161718191a25262728292a3435363738393a434445464748494a535455565758595a636465666768696a737475767778797a838485868788898a92939495969798999aa2a3a4a5a6a7a8a9aab2b3b4b5b6b7b8b9bac2c3c4c5c6c7c8c9cad2d3d4d5d6d7d8d9dae1e2e3e4e5e6e7e8e9eaf1f2f3f4f5f6f7f8f9faffc4001f0100030101010101010101010000000000000102030405060708090a0bffc400b51100020102040403040705040400010277000102031104052131061241510761711322328108144291a1b1c109233352f0156272d10a162434e125f11718191a262728292a35363738393a434445464748494a535455565758595a636465666768696a737475767778797a82838485868788898a92939495969798999aa2a3a4a5a6a7a8a9aab2b3b4b5b6b7b8b9bac2c3c4c5c6c7c8c9cad2d3d4d5d6d7d8d9dae2e3e4e5e6e7e8e9eaf2f3f4f5f6f7f8f9faffda000c03010002110311003f00c8b7f1edb4298ff48ddea14707f3a7bf8fecda3dad1dd7239c28ff001ae076003de8c0cff4acbd8c4d7db48eec7c42d36d9fcc7b6bc299f9b0aa4e3fefaaec21f897613a6c16171fbaf9431dbcf1ecdef5e157e07d99beb5d3daddc681df28142090b678db8eb9fc3ad67528c4d215a5d4ed359f1c5ba382d692832676edc763ce7a7ad667fc2c08445b45acc5b18dc48f5ae6b5499678e3278dac7359600e7bd10a316b514eb4d33d0e3f8a8628d23feca122afacb8cfe9595aa78e22d527598e94d132ff0076e783c83c82a7b8ae47031e94a1477156b0f4fb11f58a9dcd7d1b5efec6d62df50b7b5666b6766890cb8c0652a4138c9e09f4aecee3e306af70a42e9b6a83181f331c579c802a518040c64fb553a3096e8955a7dced93e296b9b768b3b0c0f557ff00e2a8ae3a35caf41ef452f614fb0fdbcfb99d8f6a31819ed4fc15383c9a55181eb5a99146f8661c57b55878234f8ecf4cd62d630d15d4312ce8e771db22ae0e4f56dde58fa66bc6af17f727a57b578335e9eebc0b6f6d6e8ad2c768b1c6ac719745000cf6e4573e22fcba1d187b5f522d5fe1699e0792d241190a48e7209f4e6bc9f69191cf15f47ea7aec4b6aa96bb5d4a8d9b73823b7e95e05ae4421d62f107197dd8c1fe2f9bfad67859b6da65e220ac9998572b9e4734e0bc6697818a729e7823f3aed39050bdb9a781f5a01eb4e46e981ef40585438ce73f9514d720b1e9d7bd15490729508e4d028ef4c279fc6a044738dcac3dabaef00ea5f67f0fce18ee31dc30507b0c038fcc9eb5c74d270726b47c09750477da8c13a93b91640401fc248c7fe3d59d55789b526d48ecd35d9ae1556389b11feefeef65e2b9bf12875bc499f20ca9dfd47ffac56e59f8aacf4e8a5102c285a5dc0483e61903d3df3585e29d5ceaff006460c8ed1ef56da31d76f5fcab9e9dd4f6d0e8a9670dcc9df903919fad4ca43107b01eb543795c06c7b1ec79ff00eb5385c08c75ea3a5765ce3b1a0ec11092718a6c0e091e9595733493a1e4a8f4aba6f62912d96ded0c72201bdb76779c7a638153ce528b344ed1fc1bbe828a5562541206ec73f5a2ad1267818e8298dc7f8d4b8c5324031da908cb989927d8beb83f9d585105afee941562092541e79e3f95538dda3bd5911d9640e0ab29c1047435a5b3ecf7305c0c1889f2725f9e7239e3dbf5a96521234590b058a4cb701b70cf4fa63deb56d6d9de48d658e248c9d98500e4f7c9e7b5456e91c52c4599984631bfdc8239ebdc67d38fa5598233237971c8e0c9291b97965e181383c7f0f1c7e950cb48af2dbf921a05b7e31f2ae30064f41e80704e464ee3c9c577d67e1dd06e3e195eded8d8a8bd485925965f9dc3aaeee09fbb9c8181ff00ebe0ae645c071248006e5412b9c301d7afafad6f691afdb58786b59b19240b2dc841046818e5b8c9cf4ee7afa1acaa2938e86b4da52d4e2bca3261477fd2afdac0b103d3774cd10a2a0e3ad4aa06720935d118d8e7722518c9cff3a29bf9515a226c3180e38a8a451834f63df02a091c1e0520b192ac90de65d4f1c8f4cd69f97f69558d9c85e70013d7b743ea73ce7f0acbbb4324a1630cce4e005ea4d7a17847e1b6b3aa471dd6b173169b644742733918fee9e17827af3c74aca728c7566b04dbb2395b5f9fcd8d2102e70bbb03ef900f3d7a77fcbdab4e4d51edcba103f74a4123e62cc7f2edfd7d6badf157853c1d6a8b6fa3eab3c7a8428079d24c1a3241e59f8c8ff8091db835e77323c5349134d04f83feb21ced63f56009a88c94d96e2e0ae460b37380839f957a0c9cd4f1c7d0f3f8531460679a953918c1fc6b7b183250bc8a7a0e7af5f5a6aaf39c5488b8e9d698808e48c671e9453d495c8028a02c539e411465b231599e6c92c994c051eb5a73c4258f6374aaa2d5547b1a9772934555f3a2b959a099a3954ee57524153ec4569aeb5ae48856e356ba98118c49296e3ea6a011aae38a314b95751f3be835b7b92cee589ea49a9157e53d29314e1d48fcf8aa25b1c39039f6a954718a8d411ffeaa7a8cf6fd281130ea39a913a919a8c0e477a99400714c07600eb8a29dec28a4052ebd7d3351bfdea28a621bb4722a3638c71d68a280428c6e031c50a78ce075a28a009900c53c7de3ec68a28024ce1aac20cb1fc28a29b01f451453b0cfffd9, 'GIF', 'chartesceau.GIF');
INSERT INTO `explnum` VALUES (4, 42, 0, 'contre-sceau', 'image/gif', '', 0x474946383961e4001001f7ff00ffffff0808081010101818182929293131313939394242424a4a4a5252525a5a5a6363636b6b6b7373738c8c8c949494a5a5a5adadad635a5a4a42424239392921214a3939211818312121181010211010523129945242634a425239314a31294229216b5a524a39314231297b5a4a5a39295231212110086b4a3973635a524239a57b63c68c6b312118ad9484efc6ad8c7363efbd9c735a4ab58c736b5242c694738c634a5239294a3121c6ad9cdebda59c8473bd9c84846b5af7c6a57b63529c7b63dead8c634a395a42317b5a42ce946b422918f7d6bdefceb5e7c6adc6a58cb5947be7bd9cad8c73a5846bc69c7b94735a8c6b5284634aa57b5a7352396b4a31ffd6b5f7ceaddeb594d6ad8cffcea5cea584f7c69cefbd94bd9473e7b58cdead84b58c6bad8463ce9c73c6946bcebdad635a52a59484d6bda58c7b6b847363b59c84635242735a424a3929423121211810ffefdebdad9c524a429c8c7be7ceb5dec6adceb59cc6ad94bda58cffdebd7b6b5af7d6b5736352ad947be7c6a5debd9ccead8cffd6ad312921f7cea5c6a584efc69cbd9c7b8c735ae7bd945a4a39b59473deb58c846b52ad8c6bd6ad847b634aa58463524231cea57bb58c636b52398c6b4a9c734a4a4239292118ffdeb5efcea5ffd6a5cead84efc694ffe7c6f7debde7cead8c7b639c8463846b4a84735a635239ffefd6f7e7cef7deb594846bc6ad84c6bdada59c8c635a4affe7bdbda57b4239297b7363ffefceb5a584ffe7b59c8c6b6b63524a4231312918292110ffefc6a59c84736b525a5239fff7d67b7352fff7cefffff7b5b5ad94948c84847bb5b5a563635a52524affffe7a5a5944a4a428484737b7b6bffffde4242396b6b5a31312929292173735a212118393929181810101008212110181808848c7b2129187b847b636b634a524a3139312129210810086b7373525a5a5a63634a5252424a4a3942423139395a6b6b29313139424a1821296b737b4a525a424a5229313963636b4a4a5239394231313942425218182129293910101842394a6b636b42394218101800000000000000000021f90401000037002c00000000e40010014008ff00870c51644a88a9826cd898a2c1b021c34a344c558248a39242530a231e5428c4a0c48a141d86ac74b0a410886c1a5284d8a60d953632da406a448408a49a375bcabc09a92724295248358a42b468512851a0400112c9d1a2279db64c7ab268d121a88cba78daca8593574e5a3c7549f485d1a347936a2cf2e2a5ea223153104119d5a45096bb8cb274ea8416eade42850e593db46449184733ac3e59bc7812df2c8cb0246294880923ca89265f068376d2a32c58ce5e6662c81317429224a9502d49918ad72a1429ea20db94af83b215fa12a2a8a3ecdfb261bbfecd86a0a28409312a3c8e1cb9f2e629533a7428430624193facffb85edd674fa08da40cff550a64d494484e668439542850a74081b6042ab4b8fdde4486ba70e92aa8bf20b0fb0528607e6445c6c45d7a01b6c516ab7492c5239131c2d9235860a157208b6df1c4211cd6b7a0864fc077d72af16dd1097d8c9998c524936cf148208c0441d9175fe04708170588600005df8cf08d08235020c290df5433a408b8e0a21a26b86022092e1e7820426a920029821b437ac09a945352a9486aaba9201070bdc9c69b6c1d74204446ca6144431b34f4525d2f90fcd0874e3d91f24352504c01c49f4038318a13843a82188785a8a8e121815c1808169b70b18926b5bc62692db568a28957ff69c1091786fc771a17345e666154f401f6442153f17516189149ff8685595930a1d92320aad8d7135ed0b745160a3efaa0ab105696992799c5d045174c70a105050618704eb4f3446bad01f3547b2db6d6a2d3edb6e05e4b8fb5051850ae01e87c13eeb5e5b64b4fbbe6460bafbae5aa1befb9f816a06f01f4eeeb6f01ea10000e381768904106d9e493031276d892c42ec1580af12fbbecf24a2a5a685aa97f8268f20a218630124671a90941c30f8df444945c4af5398aa17e86414618347bb1981761b095338b335362f3825e1c3209ad665dc648a98d82e1197ef96d72e33ed9464d6db6cf683b0f0507ec33b5b51468db75b4149c536db55f6b6b00d4d96e1ded01d8a6cdadb5639b7dadd5d46eabedb87283ebedb8edaaff43b0351908708d3d29e421071ea500c1c3a3f0610148225900322bafbcda9c4510417cd1450c6040a1c233164cf0cc0414944ec13ecf3c83b501073cb3cf0158430d6dd65def83f53cadb37d00dbac6b9df53ccf9cc3f63ee7740d6d01d0ca8dfbeb079c5335efd34e5b77b4c58b8dee39d2af3bbdb4d45febbcb6bc3fc3bdf5735b2b7ddeacaf9bbdf67aa32bef3705107001e0d95c3300267da4a006964db027df89897ad06730831f46c4a04665998c184cf10c4c4c60191310dd040e8089dcedee82186c5dea32c8c10e7a3083cfd89d390e908fdd21c01ccb58060ab9350f74b4307ae898960bddd7426959cf7ad5129bb7b285c31c7a0b7bf3105b0eff6be8adad59ed1c3b6ca1d9c656c4b6211189d47261b59ab8c36ba1635ce332973aec31006b084000d45081195c4146114843154be0010f56b185f6c0a713abea040f9e000bc25465064d70c428604004078e0e83bd6b9ef02e1844ec654d6c86145e3e8427bc75e463848b24e10146e848738cd01c257ce425276949739cd093969447b4bc65af51aaab9459b4173a9ae8be51a28b8aee83652bad284b57d2325eae2cd72a8b58c4531a405ddeaad72f87792ff849c31e5ebcc6352a80a42345030e1798830c5cc11036f8e237558ac51baa318211bc6110df7c432e06918b160ce21be283160552f74b6899cf00e28b96f8a4c73bf3396f90eb6b5eeb7037ff4811ea93911844c0011040504f2a316a69f35611b1b8c4b655d1a14a6c5b0db155c483526f8a4844a81267e8427a24145dd9ea2845d7e5c255462b8bad1cd7dee2c7c50b50831a1588694c07218d01c0c11a17c8a94b755a814c64e2023e056a4e0996096aec941aeaf01eb7922844b11d8087e7205ef38887bda8ae8391f958c75311390fad9e631df3c024092d995548aea3939a94474145493d85766f7b1365a5b45829d76ff1d200599ca15dad58be6d99d4a475a32b4967992e7311801afc80034ca591099936b6021780836433a0816c58f660d9d080354e600dc94a161b2798ec67e1008e52860b5ac1a35e557727bde8e510a08ddcdd22a57541e1ff919593bbbb2a592f990f8212d41b08d05e5da9584a7b959247d132ee30959b5c625eabb8ece3abb54a7957e60ef35cca2d4034ba084d98fa541a341d672624ab8bd09ee004d9c8007ab3b1d96c8016b4ba88af7c33a10b3810e01bebdc91f8f67b4ae4a2367db0abe7f842d8cf0f0a7292220425824fc8e011fa1601c0256c7425ec575c0ef35d163e97854f8ad7e656d8961bd6de3752695a61d6ab00f4801f01b87b016cb8b4028cad0006a871d34cb4c0a736f6697ce94b5f38604317f4d5450b80ac0b0dc477100698a03bd7563aed3d95b5ac9516ee5857db4c364fac9854e10196215050a2109397f4e4837d5b8ee0b6adaf769bf0bdca85617c8558ffcd4b7edbba50cabe13936b5bed02c7c114db6297be34a8d7c8402606416872b6e0d0371e72a2817c63211f9ac81890e7d5e0a93b78824d77bb0b2107b127d50b8e90936186b2a727c9602b6f52ccbe552b7027304321c2757b59cc22bf50acaf14bf8bd6f8bab5b99a782e3a7f98a44dc4e2754f0a2fbcb68b94bf7c1738f881d30b0c80c60378b64bf9710d0160a0d084fe26b6c9294e421f9a9c1840740ba2518078560d6bcdab16ec84c73adc65ebc02504a83e8348c2739410922334a4242df9497ee756939e54b55ae5510eb6bef5e07c4db1b9f6158d68042c1a04889fc3c9addd6af80b7efafaa58edefceb83efadb9c05cf3c2d575eb7f058c1a82ff1300c23200b8c009aeda2cf7ac67238b8d1f5323c840d6b17c874c0d02682f78ad0d1b3ca3da3c2933727cf894b725fd7960db82b2a09d4430bfcb4c7004141c0171d6d6faf0ac5d0244431a049086d8639a8b6b0fc2488ae8030ca2d00644c0c01144508408f485f11e99ab74e84cdd7ec5a735f1b5ced25c4b6e76ffd57581453bda820b473802c0f8c60740008c873ce3afd178c94bbef1949ffc35c2710dcb86169ae486df378e6b69b6a553efeb749d05374d607f3e1d018fc4e023618f002e7b431eb78770aabd51f072942301e54046020eb9bb42f2f37bef0a3b381e8b011917ba1adc1c81083031870e14670eae50812bf6d00335d041096bb8c3ff1166417e67244318ce484524d8a0ba75e6aef520cc1abbf445007bd8c3d9827b3ce62f1ff9c7579bf25ff445ca546d0278307b660d2de77201586dfae7788cc70feca00e6e462df2467c67734125c44119b86f19f87a05a5420c465003e77bbe278205477024f87be5300e87243cb8f354ee262deff22e01a30eec4000ec105318500016172414600198c023053002c8437d7d200b3bb0037ee0074b80062fc007a70000c93085bb60087e200373b72fb8340faf033cbfc4520310800ee88090178065187992e552d1f00d5ef74b3ff20c4832085fd770a3377aecb4656b6306cd6006d55001e03000f6e07295170e02800f80680fe01071e562352f9835c4fff33ab1c75bb9d549b4270f4f270fb3278208200f9cc88927c889efb0899e580eeef07be3c082f5965bb9c54842340ff4104cf4706b12a80e18408bf153003b580d678701d1800123a00273507d21900649780878f0079f700a7af007b4a00a6c100b67570dbd8801cd0759396580f9778662388698e75212e80dd08009e2087dd0f701d4c88b3b180df65001d1007dd40863d4d88b5e478d838001d7880fa56500e3d00cd5003fd110595f547963a872f6572e55a555922475b127752a640eefe06527940fbd158a04f548ef308a9d789104077cf29000e3e091b427491d285bbbf33a2cc4576e4678fad28b15170dbf48033db00374a04685b0044da006aeff200984e68e6587013f8530ff977993c7789c170000f87881c30fd4100d06800c63f40342e02344786ddd540d186095e8180d15900117100d16300256d953882563bc5801046001923002833084bac095fc300858c20dd3b00cdf4068d7988d8e2779ca840feac08518340160265b2339504b4756bee54965960009b089a258669f88828ae9911e998960d64996b85b8b444960055541343667e68a1d666c2ae992dbb707bdc00645228dbdc858d43000ffd77f03099b00888f04203cde300e6c3007e2680142e273e4460143a85d0da7953d470d5c093f56a9957f080e8838007fc80e5a490016c79b223008d4800f99a08fc3e00aed689ddb28900e880fecffe05185746fbbb50e5da6490f995500e749efe07b9c288a0437700f690ef2e00e1eb9821e390e9fd46f9df49f85790e97f455b85555ac183da3c44b45d42ea38757b1b8701017768c050e15807265687999f7458b977201e84504e00d098109c78124b1d087454200a92302aad98eed98091eea0acfa09ad2b00d7ef852f6f052cf060ed2400de526022a800bde890fed200b075097331a6dd6408892b778b01900e1600f2ea44f09565561e69e00da60955970efe00d0ef990bcb7a5ef498209e00ea7380e6a85000f599fbdd5608cc96fbd0566118956b70566c2f33a37844fd2823605a68ab2c561fa123088c89c488a0ff88080f8200038e545e0800b05ff300711717d8ac083d1f00c5a5900c88009ed380740d2701880a2c8c0a8ba380831450d88780114aa9514a002df100d138009179001f6b00e0fa006a3570d127a01fc907204688602f845ece097f7367b24545693649e9cf460b867751bc989f5200f5b8a829c588aa7b882a7b898baa789c0650ede005cc0f5605c3666a826668be96008b60c037541cbb0811884425a96aebba36518e43c80272fe8407801938803136dd4600ddb407122f006d1a0a234550d99c058db40682c5a68b9005ee4d470d5200d3ff20d73207714500d3c0a4fde200eb7007dd190095d640dca5494dca87f5f040e59b44119f4690936415776aea8f65bbb276662a68291a9981fffe97bc0578229887b3bdb7b10560ebc877b9cb8ad25587bde108a971982fcd69f10b9a6ffd96fa4865b80342dd5e24bee836b7f5a8338c89c87fa4503e067f7d79aced99a30450d14ea7504e075d2080e48258d34f575db800d03e06203a0801b7a86f8c078844aa8f6107159743599b67affb4aea05650508742049598670ab427540e612aa639ab9ffaf991639a00f260a6633a0ef8e90e1d69b91d597096ab829d0bad2758ba1779759bf889488bb40d469100077b1239ac41c43d9e594558f48ac64683ecc00e5c04079a855907a372caf47290a74c43d98046c98dda1800841a6d0393b6fb128b28b544fcf4645b15445ac34860f53a8ef4904c0b4a9dffe8b9627a9f9db8891f89b9e3a000a7a800fb59a6edebbe94ab98a548adbf27998a09b9be37bf29d87b389b82399bac3f7b42efe0bd858960b197489d993666b34bb8bb4aaf587213279cd1b06d3b990bdc540ddf640d4639bc649801c34b80d9f045e9950136f5b5eb78838a88bb133561e7832d55c53a252450af0b703c7bbafc2bbebea700ea4b0e3aacc3e99bbe3eccc3ea3bc4fbe991ec7bbe9249b9d55abf355bb3fb1bb9a0bbad56b7ade65066e10a6a0826a5ac985a6c933d533452a654af5f387ab3467f11ea750d27aa39054d3ae56cd6c00fcf664edc064e6fb04d17ac6d37064df5750115407765ec2f0dc72fddc64d7ae73c5b76c803b5ad8bff890c04057cf7ab82bec77bbdc77b0b50c9967cc9e4b0000a400ee420019c2cc40ae0c9430cca3ddcc3eb6bc4496cb3aadcc4f76bb9c8800cb7977b006cb89e049880b96590c441b33bbb5a93bdd1a235687667d6556b0b574c84476eead08e39327dc721037dd008888008a5d0088d803290e00a28e00120d002db340846f04d181cce1f100b1f30026e700322f001e35cc76f30021f002416103aafccc809207cac5cb33fac98fa50c9f1b000fddccf95ccc90bc0c39dccc99b7cd0085dca0930c4f9ecbea91c99370b7cc207b46a850c28288ad75a50bda5490275ae09c95acc035050834862f33517b53d282d2e1e2667e1d22e2a09c8fbe2a334800840ffa01e8b500861107703d11b28d0110b21042850111dd11195a0136d200544800243f0013460ad916906106db3ea7bb30ce0cff100d0fe7cc9f150d599bc009e4cd0422cc4400cc43e7cbe481cd54eac981df9b99e68ad6436660f2666f78681b14b9218245576aa69b5654352ea994be636dc42b5fc243529cd2edb8252f0322f3bb2d88ccdd8e80a41be350708c0c8a9c6894d7cd6a00bd1987cc903ddd9a19cc99fadc3a4ecc3533d0e3c6cbf675db36b0dba6bbd98f1597596189fa1f8a60555b8273441e43ad72a5b5b245d7c873444e9b655eb562de4200b814d5beec68ad9cb6edc132e0da5665cf83cd68269e60a7be90ad79e347b9dbbca94cbc49dff3bd09bacc99f0cde615dca61fd910cadbeed6bb3db5db9391bb9c97a7b8c3c7030fbb221a8b496c465ae97c5021a6f19b4a725193d86242d55332d5ddc9951b57575da4fc4f33cf554574044bbb6f4b7aa97e0b4433d22944227f4adb4f7491ccd7b8e1c99914c758d59ca266ecaa61cbfa8ace291d9daab6dad249e7b0445910f960fde90891b4d50c53a5019b890c3da4190e855018e3a09de855c78512d5c48c0ecc2d9923c704381dcf254edd6dcafe6995f1c9ad4123617f43acb6d48f7860013900fe99addbc8549c075a68bcc9863a656ac41268ab0d3cdb11118b111be501c65a208bb4126b1211b75be11cef1e718c11b17611b754e4d09c1102dffe10a30710b301113d63127440027324003ddf103a4e0138d2014d49c3243810849310a2fe3084b10477ab121b0001876f1054eb31f9fa2059ee2299c2008fbe1ea01b2159ef005b6921700d41eab001a7981178f10151f72083c40ec6de1084ed0048b90461cc220ed910522b2178dc2170e322c70e418ba0221869019891003fb810298001b61a224b0d1e6c07126e73e1cb111264eb2e77cde1bd7971c8a507dcf011d6dd21cd3c1108ceee830d10b3f60277d801dffde08d31ccd51200a508008a20004b640284e10094db0041ca2047ae12027c228d0de2890e203fe812982e0f1fd0116ae2e085ab0095ac005c892407931ed1cc2037fd12890e1eb99ff011ab372179fb1176da4200eb220a8d2461bc21eabe21e4ff020a1e12094211989301697811f5ff02d591467ec332ef4222fd795d826f7d2658c716bc62fa3573a22805f4322249830243f1aeeb0714dd74410875e1ddb8132048f08d0acf040b0147ee2048ea0ec78ef0780110880c0049b1003a172f221ff1f21af05b2ce055d102a5c401adb9eebc242156b7115ac72155171160872225131159f9120bc223394500393c0023e6322bf1219dc1eeca03119cb423434820586e02c37d2c5d1334fd6925a6bc33ee8d33d5ba766e1625ab6a44aebe2fbc6856cbe6fd8f86500c4f9b12a870c6550077600053cf0079a700557a029842008d72f08d58ffd1dffa3fd5a6008a5d1055f200692d07e3b825c60032d22800e4d6600b8305d20675ddb825f5dff4b290a4faaa377e11e8cfb0f106c90d170e52ac4bc7309e7cd3b70609e81730fcf1990f85022c5891417568cb810a2458a21174e9c88d0c0c98d2829a2dbe8f1214a8f06d04544c9129d4c99370dd0a3b7729e4e9e270d142840001c3f01022e642890224db1342a44cde0517555a0424f0e653df46452d72d8fb23cdab265d2933096e63c3b30a1e18167fb0eec732bf72dc384e7e89e3ba0f719c2887d05cf8bcb57ef5cc10ef3eac5db3061437307cce533f70ce2e5984275be640952a5c6cb34cfb1145a3261c8cb4261aa7e7952276ad2aa5da3fe295bb6ffce9be86ed2fb5614dc006bd7b209881622c4ad106faa0159b2e450a04059b0309a9e250ba32cd119254ac4844922b24fa6f83a8069d904f471e1be65efb87d6287f0f3f1cdf796af39be6febb78f1cf9000204f201d01c003533f0a66f6e9bcd3606357b0da7d76acb4da604619b90a59e1a4410c2dc2adc6d430f654b30c168c0b1c79a0caec96090390aeac0956a32f90108556c71e2c6486c1c451428102905911e1b69e4071964a0c1144524f9e6192609a3c000cbe0928b8284d89a08ae934ec34c30c3f8020ca2bed669681dff22cbab21cacc21504d04084440b6da16bc10273a6d9bf0a1d76e9a2842dd202289b5d15c0b7425d5fa84f0d00bfbccff93c3437b03e7376ba8a1461a4a2ba526d20a3219449a413aedf40d4f73a934137032c9245353a921c0b6d6fcf473b1c55e7d6c31c1d65188af75c4b4b53fc90e10b32f7372956ccd36db34a734d4ea4c16a7945603b4b6560d6d705a3865e24c596c0d9cb6c2d98aa266800b5235b5026a2ac8140e744fc840030dd4d5c01a0de068375e74d785178e0be0d005df020c7892c97d2c138aca8bfe728f2103fa622b31faeaf3f231b9c45453b2fa782d964d00e541401e73e49129236d0b75adb50919c44d286e4ff290db0755a6d680101f2c19c196d11971a7d9782b001c6bacb9809a4c46cdd45c6cb24937836c90ce00e9a48d3e019b1374911a5d6ce0c0ff069b4cfa65506028658be8992bf34a3823c3dcb30f21f7cea9b857fe26ee0f4004bc7933e5934fea29b7dcfa1d91b79d12ecb7a79efa15fc26add1293cc37e5b4e794198e914bcf19bfb564d70bf87da49f00a7acef7827035ada0854ca841d7d40ba6b6e60474ad8eba6aa95bdf57974ce0c8da5f0a9edcd6f681ffe25a36b3d5368cd7b7d49c40cd34035ca658b8dd04506e9ceb5494d1bb13a487f2a12a2c8037be8982797a9875d21a65f00fb41b6fc60d1c71c39ea4bf3cfb6fa68f66800c7aa6061b49eb9734386b606fa1745d80cea4f5f9cdcf6a52fb5f26aed6bfefedce36dc22099748329a06e2ea2db672609778a526352d033fbe1ad69aca81ffbccf08254e0de256f67446141416c07029149cf61ef732e72d4b35199acd830a70b3bf49af8504584a06d0253ace05f0021ab8c60506f10624762a1783c8452e30d082160c027450cc0414a938ae68d0ce32cfa000d82c438179749149f9b10b9aa4543630e5676d91719819dd162004b40d6e0828873734163705994c5a2aeccd0d59188d0200521d051864208932bd3e7eaf725a4b59826ad6b8cbc1ac72966be10d29e947164a6300c2c946367a068713f4ac67d7b886ecac68c5263a1195b968012ba1e84a5365228b27c145c24e72002849a48b97b1dd60ce0136f8e4658390614fd9821719631d201fcbe8a09be466c7b821c0767fea4bd744c6c240ff12201a04e0a6340850016d46439cd51881210b3082a18ce01b1440212529b04e2e6e114a5cf4d74b147892175212919824ca36d9c18e0a802329a4bc46000440ca0004201c012065d394d6c96c08e7a0d9d08027d3850dd7810e0396e416934e121728ed034ab5234cc2103397c2e8c596eca958c5fc939ffe50464073a4a93c34260f64942301f2f046027099c62cb5ea72d9ac00a7a461aea356a053d1188105e610821fdcc2153f608322d4d0841dd0610978e08326b0b0081a94d35feb5c1226d8f224be5009977c89920cedd61b7518c51ebf81df410b9a50bce635a102d06b5ff5cad785fa35b00c65283ec051c8ea796dad09238c4374c710331693ff30f74193f0d6c4cc5e11284d1dd3981dd76447b9694ca73af5e93ee6b10e86ec2322f379c9f48af24d73990b0383206735c8d9544ca8600e2a50012e28200211b8c2297468c21af08087246c0209a748060084918c5fbc2211a35084bf422a977de4c32d6fb11d51be7981baf695af06bdeb78f96a57521e3403e01d80a448450d704843529caa00200b508d686c0a5d9d3c6852ec6ad0a4f003bee020c0205983b67998563d7dd94f7be228977c144f836e9ae93231c6d98dedb41c77bca93c469b001027601cb5eacb397245c1d3fc041dd353478b5d5c60fb8e40044461e73a898209a79a4116a550832ad690833a1ce113bf08c62cf4f08755a8c117f8ff8d0693c5695f1446a30203f0ef5ef33ade00e0232906d57200f831800110e01b0930436e3121826f8ca01a811427359a1ccefc4ae3c9d1b0c7a61eb52acb788301c4804635e86a8d2a9f57d0f8986b81e9a1275b09b34c69da6cf194f78e77182b4d1ea6e33b3c5c8e0d637ac3efc87402ca318e4f8f63c4922113db26c6259370c86687264a225148000a6c73104d9631265cb18752a8c20f780805129270872694820db818a7a70691a90b00faa078c5b295f71ad8f30e001cdf70cb317c81095c88a0c9e704e49aab71ecf846030394dad45139150d394b39ddc7de5405e020006f0e8200de708019bea1024948a3674909c75d076b5e7bf4cb7782215eff1ccbf436636170406df2b037caa1269b5e1ad39cd6b4a7412cea7168f000cc3cf5afd826b62cdde61b3183193fd91c0d244a42068800021dfcb08352f4c217c61e44d042179c825e03da83cd6b41177a8df90960dadff0862bd8707449e0a21a2298f51ba4815f4f79531a41930636ac41297054601b469d54e8cab50d6c3459dbd57883b2ada18e033440144a1ac436822380852e14b07fb547968cc9360e12d36dc1d3201d1dde53876b1ad3def08639065f7111bb23e10a27de9a30b8f75cd107567949f5425ac6136c4639ce944a1570ce6b50d06739a1f8e072520670546bd8e31cc8c00426903107369c391a2220009b31d11bfccab95ff8e5d9000aff8009fc12401a8fca3a38a8618f6fb9d92805b0406e7131086a248500e32006b1b7397ca488ded9a41fbd3de0c1101463301fb97a3c9bcc1f4d885b7ac30c7f873922cd3148cb83d30970873b443c0e8db97f40e6a847c7161e69ca6813468ba338aa0f0334c08440ad85302d8bf00ccb6390c0198ad7b2870be0077cd0b22ac32b0c0c007b30bd0150077a28071920052950817d988311d8a6fb8a86dab100764aa18dd200755880d863a76ac08080aa007ba04070b80070c080da03be0298836a400a7ca88707800675b83ef6ea32bfba3276c00806ab8f029c0f467bbc49db1888132d8e913f2f043177c834d272078cc3bf8db12986b3a94863b88d99a3ff8e511ee4119035314001fc8f081b93c83240658243009980006908917a89437a357620007630911d04af2f03af0b280064e803495004457041fcaa2f1a2b80191b0a79c3800950017163be02383673311102c0c173ca2d0ac08441002f7a40006ee08558c0c46f983a14b92bc2ea39d013007ba0870453a675a0c238a4a9c73bc30eb3299db2a99d42433ac23479b0bffb53807108b104e8293a4a8065f43bc25bbf0f6a436e8c1b6812c668421e37b9ac85db3f9a5ab835698f87888b87089cecb99e577bade2b30731230a39fb36a39ab56f803a02c83ddbda14596241e0b3006d2b800340066f400664e8863378036a68a21600b4e1402866132f016007ffad0998f7d8c85ed138735c380101ad9dda9877083c4c73c6fb13354cf3b4953c46311c2d981cad9cda3063bcb488bbc987e3183a724337d198358426f30bcac75326f7c807930019d4001ce9691f148aab1d1c80d1133401d0320c14006ba84a301b80b9fa8d0b4036304b0a65d32bee1bcba4c0872f2bc416cb108548ad8680ac592931c93887c9603c74743c64ec429eb249311431962c07681c07054880bfe4cb941435c29446d2924610f3b0107bc964bcc965ac490fbba936f4064e4b9e6299a90069b06a528cd41019a5f4ae529cad4e6981d972a25ca8a2583215d889a5772ba8fec2ab6bb0abfe1280f8b1067031116fb22fbbc990977889867095ffe03c8d2ec108b93413c9001001d120655c4910133c89e3cb68844605a84ec0bc4ec1ac4eeb84c6fbeb4ec13ccc108bc6c64cccf17c4e8b2b8799fc209d72b8bcf42c705c1bfbb00fbdf8133bc119c2a91e6c9ab3fcfa944e89053768018222a888cab9a461170dc8173858178a8283fc41176b60d0f50a97daa324d77021bf891c11522df63007f4d0cc3b6a13b9113c98f434632c870520071425070540d1155d511565d116a5ceeb1c0772c038eb2c43eec4b8ee8c46fbfbb4fab338bd544ff9d3c27114c0c61b3fc1a80f84008c9468968db80992c199bb311cc4b11c9ea087b47421d77a2d43e42673593a45e0ad0e38baa32b0857e0ad33cb3635b32dff0c103720c400386d220c500e2b9aad1118040ca8067f690b04381e64c8b0c5dca9c604524d8bc64b5b00445d8063485444558063585144958047d54e4a5500093003c0cc4ec1a45109b8384f053133504c519d466420d573ec5300391ef4389e8d6bd5f75829b8f8a25b4a8d7b4219cb582795c1557cc2a7c719917392c01402a4fc82d3fc22270a500153908146800220b89145688251800252688312b8013770831bf00049b0d61bb8015c90040ff080219084481c0273ad027435017535010f9803cd1c556914cf508d8778e806464d547238d17ccdd71855d118a5d44cb551ed8c46f10c4fc524ad4b0b51d08aa6ce4a1e0a731bccfac397728c2e718cffa3c41dc6c2252a81928cd08910a12164a19649a2d04b323916ca2d5788029555594bb00129688336208222218236a8044598839b9d033f3cc7528dd74f15557da0d77b1d5a7290807d3dd1ea2cda4aadd41ab54ef024d8f1dc306994879d4206cae4c6fceb46e409100e2d4089151065e24c963a0c054bad87302db42d89a06ac08a405bc4a00830322d5b2a9ffab49b69a99d7d10a993a000d3720bb8d95900f9d30f05549f0d4c78c5d705305a05405414455aa37dd115a5d181e5ce1c85da824d4c9dc23f12ddb09e1ac9e4b1290cc318cd3452666a298a818cfd40ceb9b83b0d2d31d51229c330a97d00a38e08ce97080cec6acbbc258c25f51a8800a3ff300ab9bbf517b9600c8880dd86a029cd9429654a43789d4c0dc33418ad54c0a4ce8135cc4df5d4820d31fb835eca74b83324523874bc2b74bccca20cc8b80fce4c880d32315831ad88388d120b39fa5489faa54f45210da1fa0891511628259f3ef1cd90eb4c6481a932e1dafe1013f665139eda18b921c971f43080d5d1329c4ecabd38f1c4b468242d9714d1c0634f10c618cc3496e399cb36d120f4c5a06580cfd46d0ff57d8cbed0d059111bc0100d3fa9888fd012b1d15f877015e24c8d3f891590bb906a6a8c19361b2a3487153ebfa01c906cfcc6f643802856937248124510024530055ff0852c36053265031aa00136f0e2312ee3a33385391082ff32964449ac2a4954633306e3303e122f16e333be63320ee3a39b631a30923690813ffe639a95014880041280599825e4425e64481012476e84526884958582289882517002473884adc88e2728042f588242280b2c480443e0022d38652de00455f6042e20042e78654f20042d20044ff0842ed8824ea80147680247d8e543588443988e4e7882b2e80463de82502e044d3e841958842568022768822638041ed0e427e864e8900eec900e2cb80e2ca88e2c088b479804b1e8e62c480446f8022c300443d8042d08537ce32d1560633666830e500432350521988331456321d86749c4d9798667dd9ae73980bd83f605d8333a32ed00d8abaaa3eb800ea863ff57b0638b96818abe05232108422e121920018ff66848f8811f28e492fe8146e80121410444108529100520d865aa580564c665ada8e627b88aed608252160441d00441e084592604a2e68218706576e6822ef084ee606726b08e2c2804a90e0463fee6ed3067ebe866ace08140e8644d760e2fe001a9de824040e6e8e884ecc8824e2866a9ce0ab21e8b2db08e55108b745ee7ef30044e483ade928478e66b7c93c481ee6249d4677a96c4488c4479c604be2e6c36f085a373ec31c6672130052faee37cf605ca26533de6e3392e923f0ee93e28e9429601924669522005490612668d8448c0e4667e0eb4b68e4ea0ea64eee451266542f0694d006a4ef069ff4130ea527ee5572e65a64e842fa00ea88e6aad2884b3be8e476004e87e6ee8ce8eb018e6ac50e64298844298edd93eeb4ec0ee615e826b0ee5612666e746eb74e68e523eea2e10025c7003e09204e012816f25d7f92657451882fc066cfd366c2b5684f9a6efbd0ed3c27e637cbe622c9ee83136857b966c5fc0e2ca06e34a08e3090f6998ed05920e6992ee0196e6112880021e81e966ed6547786d4dee8447286bb3a0ea4e5805e9386a2ef0e957a80519af05a03e6543d082a0d6022ed884574e042c386eebd0ee2d3804e7c80a2fd86e1447f147c082ef980e73fee6b10884af98045cce8ed9d6ee2780056296eab58e6bb446ebe9600443000f46e069ff438801eee88244a0d05efd9e16ea0deff123fc44b344ea8d6f40b3406a53a24033e08a85190b7011a835f9fe566ce32d36d66f2cbe6232ae631ab88547e763982d64d4061298be74100782112f71e7b06667de8a5528042c008434df849f9ef15740f519d7ed5750651ee7024320044e2084323f6e16efea45f082b05eeb5530efb11865526702eca06bec58ebaef0821aa0044a2003655f842738e6b8eee64e6004e9a6f647e00e5266822ee80243b065e2fe8cdf7c191aaa1090b59cde7c21e971c75dd51a15e29ec49240787721767721097c274b12010310812e6abee69367de628383de638208e38d266d51b0056a5602733e73204f0436ef822f306eff30208b749e845c3f843038042ff8e52608f5b108034758845ccff82778566b2e84ebc8824e66722b3f8b5086055d27032f788231200332606eb1886aab6e726adf8e88f7846e0ef288eff6e1e682b68a08bd059988c0d82cd113dbf8a996990847ca16d928f7d7289c4b8ac0698173a168213777adae97b21e44910c10000bc80124d00151c0832bb8022bb0824f40f55d48f5b94f75a06efb5ab882dcfeed3050045dcdf33e87270bc085e67b867e0f78816003579081d02669441812498e0297950295850218708245700431880447d8f81ad0783230f94e2667b87e04d36784b55288cf0489b0b13b1142cad2c88c41a9a1db3d19a7679cddf05f93b12eff8fa5fa6bc219c1310aceb12b7e60803bc8811c28055bb8834d4804e8f8e6757e84edf60e4260824db8824d80ee619f844b50017ff12d8f1a98bdfd28dbf897f019ff27497f5d7d92c559f705127f5bfa9254fbdd90b8df8b08628d308930a209fb058873f30e183870ee9c0103f30c089c8770a142840a15ce5b0811e24403e81c6a4cd8d1a3c779f43c8e1c49521d386ad604b0b417828e8b35bd103959b268d1211e3c0e1d0a33e309d027851615ca3269cb96449ec04099f30cd3810907a05238b0efd9810358b3723d5095e1b383592988dd772eabd97d59cf1a3488f5ec33b31ecd1e64d8956b41735919827c08d2a3c484fb124a7cc617635f8f1b03ffffcdf8f12fe2c71a232744d7d1b20193951316b08cb940010217ac6513704d00853d6a9ab111616bc99242813a09ddf264cbec40591e4d7ad4294ba2445f1ef1bea4e8d9534c50b162daaa352bf2e778dbbe75db962b5bec5d0b1684db357bbe03080ee49b68917263cb152d23a4909020430a030f8354ef38e46687164156fcb810737a8a51a69966998d548001a015c00e351964609a3d73d0e0ca2d6f88b0845087041208168c64d1611659743249168c2462882184086248228c8031860c143c3381545ea955e35d6bad45d740070ce4908e396a27e45d7ae9b58c78e221608e61e635869f63fbdd9710461cf117514688d9371180fef1d725665dfe25ff6665f358464f49091200ce00199c86da2db7a410423518ecb045213c6cb8211622ea86c56f8c741248215b7452c8135e2c62c97151392a9f566a1199d58f779d951d79451e600e5be6e89595915c1939ea929e22848e5f199179e593124124a6998441b9aa425b4e3926ae8ae99a50811df51a8d34f6b4795a057398e18a2b668830480fb63471134f3c41bb48184d38e14424b600310a10a220d20824a66062c15307906b40555559b52e596a9d63185909c5e5e341980e995578072c139e9198ea15de32e62030f092bbd6f7a44661226cb0c11b5da62b80fd496692c21f519c30800a23c8f0999c1160cf05fc3c3880088acc71322e1564e20a223d2002ff032231f7d0880c8a603242c96c08a1880a9288e0811b6f0c328808cfc8780e05eeb9876e7b0e5d6ad0600809749645657105645b029167e492f9d47bc03aa10a2c30c1028e4926980f2f8c8e67536ad9d8db546e0671c31fad8ae0480a8ff44d01c18a9cc105d254337835d154608d06d188b0b80823104ef8d0d10c3d480518b430482e9467428001f0c2db18d2081154ef7c52bfd5a38e6a45c496d79b1a998fa7e19db58eeb63135cb001dffc5777ae112f7cb0c1e875841166df042859c2736796fb6607f2cafcf2094633da00175c9089ca99608081cad6c0a14b26e187df42f88364d202f91888bf7e2617e8720106e81a363f48ed2a049facf398d5ff965a406677906106c216b16c2a53393207ec62773b73208c56bf0b10631e28c1847c03409a69db5f2aa8bce76db06d9fa14701a2010e6b588f1ae1cb5e055476816c9ce00419f05e36ac710238c0e104d898e10c69a8431a628386178886f13a9790a5c9af4942244b58be2240a83dad2b02248c003b251e4f7505819ed20bc1c60618335d044a8e394895ead345e4c98d5564948cc35e05bc2e8df12f1703093df8068e0b540f85274ce1057478826c68c0851ac846063450c313f491862ecc400d75a8810a20286961c10a480c8334876ce57ef0598b25aff329ebb0ae80089c0015457545b2c963490890c7631cc8b05c5190839c7952da80b72b5a1548ff8396d12048be4110e36dac9509028d34da0407136e037be10307f6f4d8c23d66a399806461361269c81eb65087404c9a478c282fd0cde32b57e14b60c4d2c4b588b389fcda64a636998fdb21203d41cca5f254093de3d13388f44c103edfd94a7d9e919f8db96706f369cf7d8246257028a1348686815c64421a99a0a120f7080716ee9199364ca42e32aa518d32b2689d9b9fd2ee49bf67cce32d90040cd382942fb8c0056b05cc571537b524f29cb26cd0cb182b1354b181ee3377bce4676728880e04ddd3815df227f3781ad479e6ae3326b12701ac21d5616e031cb9900656b1478d1bea021b37c4460c97998daf72551727c8e859bb9a09207a6e9bf5ff339e119f23c4c05c729c933a071597311e4fedd5767ce56bd930d3ab5e7a64630540d393f8f6170481e69720e9e53b2f08c7c9fed3b0f8bc2c631fcb4be6f532841740e4412f804c9565ef021ad0450bc097898c7e0f0eab7de8f74e9b89d36a207cb58503238728c4dd5220b34b938f01fa1735b985ae89b43bcba7d0291e7e750d017a259b290756ca81c132790ac3e96639f38d036d378edea527512f6bdde26da6826c1b2f2e2d06bd38768c24b98ba306894a0f116660acd4b80035f25b4c640e808fae0d9f6bb16142d7c2a18702e6e1f77451e061c201889194d52aed525271d2c720adc32bedd6119ee316d093b1bb62edcc210fbe7a23ba68f4a0ff752f93370a5490a88ce55b6732db58040d159f96a1b1dde8d6b60ba283404ced71537337d4026c77bb093a5030a58a8deadd37bf26fcec35ae873ef6890f7c5dfd9efb34badaf76582b07f6992660c333ad261523f06216086cf6c9028e2cbc37b3d8886a748b0e9a272824f0aaa910d2b6305c1d8b2af44ea048527d4c96676b38abdb1f41a8448ef590fbfd8681f1caea1815cbc2173b9c885e5723165f46534b5a9edf4f9c0e760a5a14b690b714f930862150262676afba00ba73cd9e6eb888a3cfbb2a2850b38e79a0eac8b12c3ccee7697b7e5edf9970a722c638fddabdfc9334d16ebd8dea2bdb15b36b5006c729035aa97ed0b6003bf7030cde586ff2634712b54d396431ff9d03d65ed1540690851dd42de551001ba4b2ca873575a18522fda79386ba24a20ecd6093bbc265060ef386529e5210f1a3484220e1f9e6085ad91c352bce2209cb1824078643b07888b4f52233e4d82b7f7d258e317bf3609aba7ed01dcd74dd6c000e6263708a1090de6998b39a62f6d39cb55201aba350ba438b2107751603090128bd764972f20d18e5ff8da64d337b5ce51962a6c7c5538c2e5516254b267320cf10cf13e021a74f41934d15010da0b500d05b11841cf7867767f17b79d1e2c8844a6b68b33a32069ac44aa521d001cfc6e8d6b5c231bee7bad6ac977bef3b540d38d5f2dfa340dbe416ccc8848ac0b74de1596ffd11924b875f95a90dac2a9509df969d0fd2ba8ae88006f68bd1c5a673d4a7f37d06ff4391ad12000eea38181db879000673f3bdaf92682637f4304bd459008e08a9c0723ec9de1c5a78bf9467b05a9c3f6b8af407f4de32640ba89f0846fa60c13d942687aafb5addde846d7ca3c5ce876f91291d147c50c9dbd102480dcf1b739028645bd748e2b013325748dc7c008ccebb95eeb25009680135f9c0df515803ae01e38108034a4500a4d2001bc8105f40211d000cf8c8082241f9fb51bba18800864d36e498d0439df901d1968f0cd03ae0938d883b0e0433804800dde200e0ac00d9a860e06c035e0200e129e695c0369788f069c1635104066210c411cff8deac8db003551bd48cdec84d8878905d591cdc1918d378818d695833778430294c318fac8613c844404c6c5dd1eee0d0d564de0e560c0e088c01cb8c20f884223d0000da8821338cb2888421b78606325c4f0c94b5ccccf7c400d5fe00f7aa41deec1e000080b4b84c30fd66000f4600fdea025da2026fa6026722210de60e101d205a8436399d75f5c8a10bd1a43a4c541488a7680117271cdd8c0ce5a285002c9c33b28dc7441d7172a5c39902141540424edc5022a88e190560aa1cfe40c0ecee0421dba421b204b07cc811aa80210d8821fdc011f08821e048333fc4222448107209f8c2007bc40c75540072419d1375c85d9550038cc114b64c36984ff223ee6a328eaa0f701d210fae025e6a00ddee30fe24304be98971944559cc35518c63ec887ba181076c0a22721403e801200ca19d9009601ce59c20d0c19268048d20b3951cd597086035220f6604042d9deda5583078a800acc810ad4e4b8d0a12bf4c11eec8012dcc11d60810e1cc12cfc823014a53024c32b24022238858c44a4ad6d5260800604b2093e00a440de2327d6e00f7a1f3f9606b6e597804983930d82e4500e35b489833cc80f0ea4285e62241280297a19159e59449e03bfed8bbe1810bfc04ec0d44e16c9d988214039641d300ecc2eba5e3026803b846402accbaaa1055b1496033e22056e0fe11400ce880026d824e358800820832ca8ff411a9cc11a940112bcc011ec421c14653238833310022c34022eb41bedcd8554d088d1189b68e0c38304a44006244b08003eb4449bf0e00b5d4f05bca14bda1eede102ed4d9fe1f81eed2d032e50031c146770ea203f0ae70554803d28e1209e9a40244d57502492d054a608e002dd8e5e459760261c2f0e26c2219c48d6e748e20b45c2e242e89929628012fa9e1c1a00ce58002e50c008149f81e28c0a844008c8c20e34011d2c011e24c1279cc22f5ce82fbc001638010d388e3382c6f13d5601a8905a7a2227ba49705ee269ace869645bcf5583bee08208f8ccf0d91e068490eed5c920c0dc200c0e0650c3207cc33234c7011c433398412c60cf6838ff084bb0284bf083f57c677856853ae5e591084c3e9c6756b05316712918ca4302285c028c0398220318222663da678e5c0a4cd54561a18903a29d12da9e4baa9d810e4e01d06107e4a41aec802aac011ee8001f7cc22ccc422adc4113fc800af46835c8dc8e92963dc4e000a4283eb20428f6e6690cc00048c33968832bcc812f28422c144e34388eed9165cfbd010138cee444c3e090e5e4648f358083f19c8338b4822f005135a8c44a98860d86830e52e270da033b28a125219727050cecccd4460e4c899dd228015616251c62b21e02bc031986e43894c33824c05e2590ebd822a590d44268d053c169dac5658ee6a805608224cc814e36822af8811230ffc1117cc20b04c20eec8122e0a91cc66a26b41c0f5a2a285aa56fee233804e9012880199c8c0aa8ddf059402c48ceee198ee42454cfe95ee454acda15801c0e4e05d4173b84060124400a1806015403df316927b62c3f80833ad885d561a929d9e25fcdd93b8858001e5c308e583e289c3bd46730f6ac628ee9988ec3385051d8648db85e8a43a09891d1c3505d5cc54965ee0dc281d2a14ece6b4f02c21dc0822afcc01c34aaab4ece046642af1e6ca556ea0010c0373cc33240c31cb0810ad4e8bfbae4d0e01e5669eaa6f2cd2064d559fa5e74bae404464326b0c468480338980331b842352c4ec04e6a27fe6a0edac3627005ed844ad35151b45a91458eff0dcf422b0102e3d0966e622ea6b776ae39cc2cd894644304c6460059c8a164da9d2a1df6020ccceb1af84113a8821ab0412cb4ea1b8ae54a14acaf6aa76f66a20008cb00d0ea37280032f4010752002e5840e1a85dc6da9e34482056a50438b8eac54e600c2aeef766823da85df1b92a3504a72f6002c3220326fc6d30592a5606c026da203eb04344b46eed2ced28159c86a99e391ca6e9ca433062ab60f66ce9bac362bac3384cdde75e91cd7e4a9b52a1dc48ccf38457da99dd1b7cc01090802aec401aa8010d8880ed8d9b59c2018afe600faee527fae63564402634efdb1ec03850a3669af0d999b0070e0e9d12c0dfc6607ea52c59aaecdfca63a4e2ff97ee7dafe24403268800359486bea48331f8022ec4c256bd89fdbef00eda833a54c499bdcea8bcce927461882d49eb19b0011ba0e98a58626eabd18ec3008e52ed481d79045c79b69a432c4d231a9bd9fd710158800a586f8f2a94ca5083063c88a572b1765aa270f2a0172fc337ac8319cca4f16126a962c01cdcccab9a4f05acddf4080038500026d489439da50ca6c40068c0f962c0f956c3138b80fa3c4801b8c3300083e214c020acaff2ee2010dac348c49b858547be7c4a748d0dd9045c4d619d3cfcecb50e20ec18b07d1eed000e4cb8aed31425d75d385d7814c460c8451f17961f8790c72a94f6844f1ed517401e6f40b2f06908529b5843060cff8001ccedb8988c2458c008700f05ac9d0a78689036cee0cc700680c3047cc308d81ef9e0171ec5a0f528699e96cca27e9b35e40333288be454c07d0d0041fee65af24301a00ebe4cc08671c53a7129c1e4e2e8f2da1642ab744973b722ad6196d2d4fd2ccd2250027d6ec04970d4814d3357045063307a88dc2f51adc7ca233ff043762e75700ea700682a7272ce32cc81c9d0a407f656a92a0e2660a682f470cf65003b1cc3d89add045260a40acb0500714759b53c82753b40c3dbd91ef60d80523b3553db353bd083fe2c2d1e13f3b26e6434cfa7a78c18b6f2e22ec6a7018269b622ad4cb3133257f3630b9c00336b4c9d034c9d99be9514dc2cc423edffcf60a0e4edcd231d25750628354b0c273eac4406a476f3c2a519380129c8404d5aaf34440305bc41bb550342737535e4824657c054d3ed080c4d0bd8437ee11735d8434265c21363804c0ec205e0833a304037e4ea37fcad099976550261cbda4341e4c878880a92f4954cad9e730156ebdd8e1a87a434db276313f6b54e36325b512d7ed8ff8a31c12190d34ee19a912b18a51380cb0e44a0ab3abc60a446e200e0436a3fb554d151f362c22df08c665a000680c34b06e9560b2868540323f91eba380e5679a768692a382427099232267cc30c5bae3b3c802b10d937186e8330358d9f3601f408ab8dcdd6dc9aa764ee003ff3c1e9e27a0b395f0d2dd072ffab032ba6eb5dabc20df68f0779b46651021d5c4e9b8337ecb4eb1450a75c0abfad437e73ee18db311855468f515c8187063bcce33cca203e0cc0ac46c353a880221422abfe90ca2a0edf9c9d6e1b684683835836af5c1300050c1f2638a73494a2beb40333140e569d65aff22377172c3e1000aaf88f495b115f82eb3a04f05f95c33b582b1bd754ce96ae928b64d02e263298837c9ad288dd6c00fe152e2a6bd7d89acdd61a2849e45e6a4a87b51a491d0c4252df547a279b442af6488eabe6423454d53790aa346c03356c4331658e43493be5390e3514c01c24df086c0e562cc3321c832b88004cbec11bacd05ad6ef6fdae0f7fe858f4847914421a83cff1d02b9ce025539e926a6d17ea91a7b6bd69dd26032b32faa374ac70e952b905f4976d720d04c0d4ca62bbc72b10550f34a9e81100815f80b16aba4b6f93cba6aa38e167e95909fd369c88710c8f283db26001b6082533c03b8dfd7f98cc6705e6255ce7c00ccbc82d7bc411ed65c9ce443ee8f9ab56ed4413096cafa32efa27425c0e8ee7b92bb9e4892a199666b6246bd620f2debfd62b69618eb056335e7acc09bf7c1b967b396b7452ec9784fa495300f51c7581ca1dd03b2438237697092d06808003ff02dcb35afe2f25eeed1a9e45043cf1dba542df5256aa7769ec67046e241f207ad594a4c61d149cf31c383a43c2043937b837c563d6282a93c74ff2bbea7699a86a9486afed323c0181a60e887290220c318b213d6d594b5b2feedd8f4793bd7d80bcc96a20518a3c78d69dcc499f99ad0b50038c89b78df2552224b6480270aa110068083a87070dee30b57e5833c081d895605c0e5616906705585a430e457404d5c9c054c3dfebe8c7d359f12e5bf27e92b302f82291c83e93477abd1cebffc8fa17d82617daa7ee88ba1fe0344026fe5087a9327cf9bb70408cb1d3c8800a237881325423487c05c3e04cb265c3cf031df81731f0dec9b6700654a9405561a2850201a3b70e03265d265f3a62e6c70786ad090e167b613d93264cb262080806b4b0304b8e6b429d2a5d70458b336001cb50a15a2bd645980ff5e4a0a14509e3c60e0ec8167279f9da3b0f65c5b031446869c007219c40910e5214386200132790912102c2caf1ce07184192f1ea7e07182718e274b5ecc98b161cc84cb15e64c10346883a13b231cdcb0626204e5bc999367ce9b468c07cc655cf751645b91f3d09d441956254a745ed511203028da20e4ca95e71a94cbf99be783de54cf95e1a9d2a74d9d5e3baaf4a8770119805ea059419a71afdf849735f02c38ca732ae7fb9e776ea4c88f08f26534f7ce2284101868358210eb8cb00315804c01c9169c0c42c822bbccb1c608bb6c33cc0c6b88b3c23a0b4db082061c3131d8263a0023043a3247a4fc5cc42f38747e8b8f1e74bef1aaab02088826ffb9e4947b23baeadc184197a5b4a36abb6bc8238a28ef8a2a8a49abe018e002f3a419e42503645489a51bb534803df6529a0fbff9705be680bc2e9a8822d30c1c8833c4e421871c05ea5cd04e3c19d4731c331a842c013c036df042ca30acecc2cd3e646c30c21040462004e46173228d3462113790ce1a692403ea33c0b7f65462efabe170848947e4a2a9269a5830c00ebba38cc28eaa0ce0a8a9055c5bd895d75d75e9e9040de0b80003965c6ac958af50fa46ccb9ced9e72334f3c94b4588164ac05a043df34cb383ca59a04e0924c0f3983bf57cb0cf3fd53d26dd40093343b244339c37c3471f35e82f36f35a06cd09260829cd68fd55eb8079d4820fff25b2c80a6eacb99a4df846af2446b52b1e2d4e7510692ac8c49e0a72c180baeade104112491451a1640b4410610410303002835d31a8a69a0268ae66e6415ad0791022756941171056b6c08267303960028e1098c3afc0086b7a33c10463acc005ac26670109ae5e204f05acb693ceaef134f7dcc916b40c517913a5d0b3c1006348524a2dc2e82216ed0698d382d59ae72493409d0fd4f8664c36d9945ea227cbc44f55271a0c762c4004158498a303365cf90173ccfba0c1151a845044919547904ee75c7616391791abfbe00dd6df00c1b96a2c98c0afdab1bd96de45d9b69a77acc1e51a5c72c6e5fadc3d8b1774b10629630c5e0b1983943548fd1aa8ff22b9275aa63f4c33fd283fb4ce920bbeb83c055cf02d5b02d3d8638f6516acf5278669c768a45915725ce66043861f1041040a51440142145020411baa300437bca11a6f88c50862110b37c442046e7083083c50410a4a021737d0e00d7060041cb44e057eb196f3b815afcac4837729fc9af0b8e6bbb11daf78814a9e09e1c5368560a6517f715bbed8b4a66a41047b08d85ea69e251268f5ed24f7e11bdfce421fc13d7170eae392e15c22318bc1e425337b8908e6f0031800a1098710a3234601052154d00343188224d228892128e28d42f89c10d428041450018f784441093cb09711166a338022cc02ba9142df056f6b6233d763f0c4c804f869866aff5314823e341084fc45220499086b6a631114f96788b8c91b05445216ba782a25a14209c2a038a32fc52825ec098bb1c212a6f588e07d13cb91055c81082044620a536843256420052210810a6d680315883040650ef39954a001153a3007a641041937a497bcca110f14aa706be0d25af0ea7427b2096a500c425b854cb8adc5f46530de92dba4ac07917cf46719e6b8cbbfb807ad7d406b24f330c901f6710eb394443e1498874241f58c7db45295650955440d001c2aa6af8a84cba5e21266014c3c832cfb30c31cf631307ffd109b138114610885c36b7d0885de0467387d17b63cd9b46c6663a420d99919d064c64389518d2657434f1fd68d3f426491ff52b5c7a273684f3f43ecd47d9ed529b7902995811b13a84a5926fa90af0032325f4bccf795af9c6f2e6261cfc23e72523f3e8a9ed8e24ba3b6c921c3c8548587fc1dd6f85a4e3a99ad917b62e4f20e85a186d0f5340b91d4a41c8211a34e04a995126248284b378051f600ebc88f407103aa820af43e05c5cf5992b844509d842e2629e843370523f2b5524b11b5a84af6019f87264c3f1d910d360563918b0088ae8f811061dc91a178e8356bc8055bd7cab9a0453e88418e519e2429d9b685c829930ea12780d644ad8bc8a63ffdc14848924a1bf31e006fdbcbdb680d969fb614947b7adb146e0c264a871ad14527011c99dcb250fc303425b3e56aa6a0fff5b78f2c3545725b936b569321c768ab30e3f89dd79cab0009844d78c7132e74ff2449c356b72fd87a48bee84911d92cd82340cc8810f7a3a950ea4737ed7d465a3c554afaca651e6b418b7cd8b2df949c05a074790b7cbe075b540a0ea328e92759cc748ebb7c12451bb9485ed2b46239adb4518391083d0ff23b7029f2a61536de60dde5bcc51477210d8a6782447c112e430440094e6a822d65cf20a217bdfcd15448d611927f0a54b50613f49f57bbc4f93c74b50533286863bb44d53eebd14cd4b1e09668b0c085252cf6711667ffec2981d6e593fc39317f58145ebe18e46d6e632c02de7110739d53c32c95f536b37cad8a20e6d425967352578c62ff29dbed2eb5a14d7ef02cd5db700a5a1f29e88cf1a35ff9ec38700cf5f1427db3a52d397b2e05a5cb6b61cb6db43acb5333b68f7e00668e7cd6662f76a3cd6d52241154e3da8705b2e960855b6f085da6a51a5a94b71223a701350423d5eb248abf2becba81125321c1d448cc3112cdb6a82eeb6db641cbb41bbe6d15abcfd6eaa253d29bf98835ab65c9f82b85c39b67579b1e61196b68cb44d532e5a736a55eaa7f6a636a88344435937a47af2535ae3a0d3792f8ce37bdb47badb70d55a845a5d4a84f5cf314d94dd89abadb8189fd22ee11fb3e69898b994ac25f4f1b74ab174725f92e4ed5e05c55ab4e3c328c4caea56b87efea26192dc33f69378bc4ff06a9e2cdf56a583dde04830ef09f538429d8600a1ab4810689477ce2694078c2b3a1126c10822984507839ca5111733485e1d90079c67f9e0685b77c252a6178c233be12a097c1e1db200348c88008ae7f3d249ed9fa6342021244c0bd141ad18828f4fef751204514a0407c284c21128e98c1210a91854e4ce2095e5844210ad1092c2422065cd03e27b4a005ee779f0b84083f173cf185204c82118fa084231c01844b40010aa398412102c1882c3ca213f9dfc213f4df89428871090e61110490fd1c418c60e1099ea01026611202210bb0200bb28011ae0f0b20b002efef11ee6f0b32f00119c103bf201198200608410b864005de08f0320f741e8f0dff42eff41e8ff0262ff34c210541c717e008f01caff30acff15890074faff21c2ff14c21f55cc108574f0692300995890472eff5104f996800127e600a710f1280af1148a1f77c2f0af8e7f8926f000f610b3a21109e408ca8eff9b080090c810bbacf13bc8f13e250100441fbb4c013c64ffbca2f111861010b410103a1139ea0fe9c6f0c03e1111e01fab6e0ff00701116a100178107c4e81056e10f1d1002b32010b0c0fe243002237012b6800133f012293011ba800db940124e100701ef0615e1064dc117d84011d8c0177c61f3086ff06c10f04cb0154167087c41086a71f26cb1f3364ff46ed1168df1168790f15a5006a2f0190fef1961ef095da1f5ffda00f730c70aafb0f77e600b8d6f14222112c260099e60153eb10c25f1109e200bbe000b0c41fc048113e4b1fbbc4f0bb4cf10b80f1f4bb10b42900934d1f9fcaf1025d00335d1013910109f00160a01160450fa04701dcd31ff26121023b0139c2f01f56f0cf36f121e81ff3a3111f6700dd950114ae66454a00641e77e3aaf036a71f02a4f113ac014e640f0040ff054002503cf172a6716436f073baff27e5228136f28192f1aa53109ad107348200989800a95720b1b617ff8e77f56c0119a600620b113b6e020fdf00c2f320bd6900b0c410b04a116e650133821fcae40fc4cd11062002ebbc01d9900100c2102b16012fc30100281fe3cb01dd38f03ffb1602b0b4111a94f1da94f0111530930312003610bb24011f72f01b7801118d0100df1111821244b3111c8521230211553116546f364004f16e780066d710e6e50f04c107470f224711225e7c0158592f062b1f36a51f476d3188bb1f38832f16ec1285b4f09fb401b65a01794d21bbdb1f7a67214460108c2b109166109a86f2f315109f892075681fe32b033b980fbe4510ed3d210ca732ce151fcd8b01f2730111813329f8007c830ff381103d9d3feca7015fccf0c0f81070a8112f36f15ae1310d1501113900714720b12142c1fc10119e10b34d31398c01308a10b3c00174a668d4613654c50364107f3803117691007df68344b0627d58815575316ff69111663b1367db3f062b1371feff384930696104771cff5646f0aa3920bf9e739c591fd1a711d1d900c0d110d3be111b6e0fa126113e6f12c35a116ce720ee990fcbccf130cc107c8cf133c3011f02f209f6011bca0fa38d0fe3cd003c1803ec7704913933009932bb7524901b113986f0b0ec10b9e20410be110eb731341d014413011c88f037081644cb4823654050c15137212055312074d261571c15051c6645290364187163b408e76123759901839b5f02a47f412af736e540965e016f2e7078a13737a602a0308fe8e0f087ec909da6f061e92fe00712f097340b3001012c10708c12ca5740ea7b416e6510b48701e05614bd930243b11ff12548cfffaf34e0531033ba120ff201100f22eed6f0ca1cf3f0332ff123301c528103e7123b3a0f9d2cf3e43d213f6301140100c44001356065f45c050f59565a2816450523653d1354bc6443128820c15434bb2244d06367d312527e7065b522665117448b5057fd3289332093147065c2129bdb107fa4764ff271c81a000c3c0111621fa0e810c9794fac6b01016130b0841fcac604a5fa11634e11504a12cd5b21eb5cf07b4a03cbfe02f237049ef548ccc90ff12f4101f73023bf02e43d203cb154e9d0fff26c10bd6715715f0318db6413dd010d6b333b3940bba20fbc2648abe6204bc4204e68202f4d50044e01bc66265def66d7149742c8002f65504686604fff0d5824ac60342938d00b63457511150f31859703889b363dbc015981273a4922a23a156ffc76457c009b03200990f4e1b323bb9120bfe800936c10aa414675f21755f41138e750e398158bb4f10aec010a41602f77212d6d14ef5740c23104d018109da3303fff16905f2131fa1066a800cc24001f5d4ff8c545a7df7111ef41de5b2330de10bb24f0b926c54aa087dd0e74648e5585e2262caf77dba62476ae625d4176f0be01bea365fe3f7822c953473f1535bd0f414cff59a732aa10006a6607fa6000a2cd709c4603ac39048fb93fe98e00f0c81097c401e05414a8db575abf40eb920fb30185aa556230530009fe013fdb03b2ff2692130ff381023ffbdc00b96971228c10b5a384fd3f522af2ffdeecf77c14033e5f2fabec06ccbf3141786a2288a46e2e3aca428c0c002718e0571644962c6f77d5c6296488554da566ef7f61970e1199ec1a31ab5436392722c27042e277f7cb4f7e04f14a640146c0108e8e00fd5b03c4750fb78f61edbf01ee151fb44f24b7df7698195110e212ba52f6b0b210ca6cf793b113213141495345ba36f85f3f409c88005f80fff822013a5160c1e010ce6b5140d21081e61871d942cb58f10baaa948c4c25c6ea8964444cd0164c1e862580237156e2ac92ccacc6d77bbd448adc971ec2977cc1c461de1772e6365f696655aa610442200fee60159ae00eb0007813017899409aff0de192fb94867d5702fd7014a4b211e26f0a6e550c22610506390c9c600652964c3b01161c214f3f1184a12f4fc5880cdab9068a204f13d4f9daf592253033e1f541b5f59213213d699610f8cb6f8e4c2cdce3e49e288845e594972596d4871e4ea27dc444c0906c7d8cb87068447c2b4a6208a002c0e102ac813c326002f0a00e426114f0e013764113f4601682e1178261176afa15687a16766176af400f54b7751361114c215f29808bbf987338c763fba01126f78c8f0ff9d8b99c0f210cc4200c5498128289aa95161419901d0ff192197012ca15fd4a913dbbc013100646e263a252d994610be4d4e76148ee1b505970bc644bc464a36d597cf7ba8aff6e8400447aa4c5830272001474401474400fac806671568235a175f5204a056167e7314209a11608810c14c17df1162546809763c958988502e6f67ddf02134e1b1396e1b455808b3b940d3ac015fac05597ba1b7fa014bec80900e8566ff5808f0c2ed0827c3ac50058a495c8472e9e4d7c1c5ab95b89ae3b2e3ed8e3ae4525baa9c8464865ac58e2afc1c12aac414952000dece00e10a109b0800f36818109a174092124b16013d892101c9b1eb9e00bc0a0064641054400a414466edb766e476561d2eabf1ba67b59b97b23da9698056d992561047c0ec0ce2498c837da42a002478916cd371e1cc74ecbb4fa4bc393684c4c62023a5ca2465c88197ab9ff2fda88b9a400449a1f32603c2e401f7240c64b61071e100bf6d299bb15100e7903233314339311c6000a54e02d28601f8c7c2c14462d48492da0258b937c2cdc22c9c5672c6e4ba18cdc6d17eaa114866118c6c8098a7bde23b40eaa24aedc3db82e70dce2a0a1edaa0eaaecec03ce936d2ec40e548058881b1a8acacac8580203589c56ec41025c401994e1b667603bf9d201f75211d35512b9b2934159be2f610eb2d8a39ee12e66ec2d3e82c99b7c956a8bb5b2382dd662c6bec7c909eabddea3b6aacacab36d2dc66288e02ead8f4ca100e72dfee63e14fa3d4c3cd70167a112c61c664c70008cecb04d25febbad9b3b38be61967444a48b822a2aa00fff8a810ee880067a6073c3508cbc609097204f097312e62ffda08f016b801454608b0f00132600d4477d7b469dd9d46224defd23b218a04c9d60f6265366aca0723d2dd4c27bda8a45d682aaf8e6b879fdb428dce43c9cd210faecd28ee200ccc351b9ecb64ab9cfa13740e5e24dd9da2aea37fe9a1f5a9c3c282005d4200d7e00176c2100fd33329394fef6ef7883e069f7cf0b863cddfdc5a4daeac5948dd832852ea2eaea8828b3a0854592cdd24662a08a48bc680301fe1dec342eed8c8c7c421cea35cedb18deb94385ec62c9db90dd975f49cfbf622606c0c5ab62025240169a61080ac0090e01162e720ced742bbd7302dd1587e5f50bc4600e4efb2eff3862c666ecd2d91d37003f37020a3fe02baa362bbe48e2f073fe6e6a433e3ec5ea9f3ee4103ab89b7ee1bb67d1fe7bcdd36ee28343952c9ead9b9beb0ba00206c01ab46300e6c0152420052088db994fd10d51133d5014b1cf10368110caef03c9800d2c7def2fddde9106e7739ed90c3fe7710360127f8892bfd7c4cba07aa3ec7ac37c342db6ca424692e800146a2d76c3d3d0a1bfe03ccda3bfb4bec4fb35fce27923892e5efaa9ffe2273ae381631e8043fe7f63c5ed811f9442002ac01542200400e2d6a0684d0e2d3984a71396858c1865c9d269cb23468998c4d874c550a24793c8d0783661c28190cff61d3889f2c0b903fb56ae9cb712654b9534ff63ae3b99ef40be95396faaec99cfdc0104e6829a3b8a3468be7df360329d67a0a981a950a94e8d7aaeeabea8f32840dde7b5295303e7b8926d8995ac54a85fd7c2e4fa946bd5ab66cb96a55a95edd5b974af7e2b00eec2800c02328c0811a2cfad37d1763c8175e8e1c22c581c624994a5e164ca8f38d6b8a46802a66513489f0c6932a5ea93fb9eadcea9f380d0094265dbce47f42451a40864e326ba0c416fe1c2cfa18329155d54e5cbad1a600e5d2fd9bde8eeeab53bd538d5e35693d3e53e8ffb54e67cc93f3f2fde003df4dfcfff25006e40610103268470757f4434208778140a144827590402d1169d74f244805b147288170d2ee24824a4a800d2ff68239584214baec5a4d2391e92e56188e798c3614d319d93935047c9861487eb0485008c4509674e76d29d77557ae785878e727ced25575f6cb1c5dc5d3882d7e3783e26a91c92cf31b71c94d0e5d8a394cfd1f39e3d8311068e0aae7c69462c83c0d0c4414bf0c0c32a8530c8437f06cdd0449c4e34e184285034d2062e143c43c1497cb676000526cd84924b521d304f87277148e23a1e7678d34d45b138a94d3a1d152371e620706493e37d0a9da7a21e99e35c503ad7a491e341a51d7beb31f9247b9eca8ae3abe829f70d3aafd2530001040c20df350248e3e597aec492c91e40cc3967137ec4e9089da38c220ab5a2c0504a298d3422431b4388c0e7ff33e15e75c0331e6e58ee01d31929e2398ebad46159f18658db8a37b9e4e88a986e7ad479da9dcadea74ad21ab0957d1d5c9ec038e63855010a83dad7940b7fa31e3a1417c05c01bd82638f35195c9341352a20c3061b2abc418d2b30c0208a2ab60001051488704b832f6cf4d1870caed0c08629be4822891b6f1440c19e1418f0cc547f92b5e749f398abd655655190d5a385a2e5d2392dc534a96daa0535d46fbb3d476492ea5db5de54694735f0c2561df7e3945599bd6a7b4f1e97b6c475bb9df67a7d9f6dabc3caf10adf001e5f038e0873a8a00226225470010d3dfcf0c3e4325cceb30a220c8d092e2288104b2c2354f3c61b836030559f7d062a35ffd32d6d857577493765357259a9d594a2bea564a95134e2d62fa900e36a00c56edb8dbc5fa406cc30c4c517ecb6f1b14a0faaf1503a6c6bc304506338c8d288f0cd5fd50c028735b808a1880a407bfe79e8a40f62fa20f2e7327f2e990ca274d2061c7d30882891a52effa9c45c55335787fc272294742d278cd2576ff855a3e718072ab33ade8e3c45c1889ded2dd361cf5c4ce53629657056cca158934605b16fd82a6fba2a1ee1b464b80b5460201888460532610d380c241a3c8c86fc7e08c44164421a992862266e584402ecef68e7d05f5fec32c026c26b8aed4251886072a20348ea24425907891a3814a4f0c62ee1a1e0dc9cf3c433b2cd484e9acaff32ca323735360784a5e28a781226b0e18530470e3b5b343836800b50831a15a880340880814154200370d8c6118b58010c443213b99046245b80c44c1af102d1409a27e9329726b6a42425a249a14ea945a7c96b515ca3d48a6c8314d9100501ef200aff0c3615eab9876d79d4e0daec36c2e5a1313cdd01185da497247af88d97193b5b1f31a69e0244433019a086112d79430c5083917080c305b07101238a739c99a0c60574f14d6c78b393a9d32056a8262f7899285e8baa09a2b0081353d6865235011b0296b129a398e319c4ac52f3f4d8c1fe7d07856e2b28aa10b62362266f4a2894d8da98438f8c55807b1700c723b159486ae43087ddece600c0fff9cd0b5c000ee6a486375baa8b6e72526934ed9f019bd82182d6f394596460eaf29928b0d58b262ac2d46ef8c52981adeda2ee0492429fba308a364f6d0a0558f6144642ec29f52a7d3400012ea081404212923784dc09e090010d68201b69652437355052b86a809bde3c81062ee030fe3df51c7d72895a1045b5ff196026f2741a575eb20fa1a448272e5946502630cb8012472f65641b77a014b7b444e500c624cfa9e851c62611b38c4d31e3717c94c1b9ace783a225e179fcc642c0f52a908324e420c151018f062603d9f0186fb33157468e340323f56d06e80a077074f5476b54cb89cee521e4202a81f5cc1a16b5b8123116658b45398aefcc218fff5a9acb3cd03b957999072aa9aa778fb05223acb04aab510d0e6283a3187c7258dbdae6d69a2285c309b2c156000bf804ff253049b371026bfcb79bd8c046050ae0c48335b1757679d474b0b82e4b59715e3b69e58cf6a9d8a26e6a6c688c15c176b49dfee112bd074d5e734efcb05e62347bcb3cd5f6684b482282b49c6a552b6fd56a570027b8a4448e6b4995485395ecaf2a7a8d4a6ab2a2125659c7253bd96974b9984aedd6c643fae2cd3bc08b00fd3994be7b74b18c3f6542e9e52a974d32a1890de666361f3362148be0957289670314401ac06229351c894d6910519dfefded80858ce06cc0011b27c0862e74a14e46835389fa4b5a84e952e97866ff673aeabaae860f9845af75d79e2372e52c93cabc134aecbdb08a73de9ca9ccce9ecd7971be205dfcd623ebbd16cfba76a69ed563bc025cc01ab40587350b89ec4cc001ae1efb6f8079cbdb2113b9882c55363632d1c726ab4ac2594914286f17dd7cbecb8ab7c3a2bb445c147751aa37be9b2554f3fcee83e912def196772f55acbce7f54fcd73d677c338fae721567210d2905f26225d3e38281aad1a4830811bece8474b5c179978f4208c172e71f5a56849535714d3054529eed4363e29ac6cbc412994cb12a944f1863784e3722599b084cf73f3a9d2ccebe7ad27d85c2d9e9b9139678a093dcfc4ab79f8f23cf4a21fbd8f42c75801fe220d6b28bab6ff4334a247a9a168460b58c0fec546361adc685d9cd5d144d605fe8a4781790bbdc9529317b743a4a8acadc48058e6e2325232cbe0f4c61b9b7a79548b473c86d2a58f45fff5cef3edb0bff4fc61345f33a9a4074d8bf53b6d179b35e57f5dbc02b0c31ad6106447c14144644ba39b152f6981171de946a3fe042d88e9a3e14071763ee31b1dff24c6af92b46e670d4459d3dd175743d44bedcba8481d717811e00db561b4d7bdeecb2f075f795e61fed7d8ab7c576d7e24a0df1acf7df41bcf9f4e552c19fe79f021ae206f5bd6426662a515a77683bb19d306bb7ee2af7f3db6d95ee9ae5e857f6c096c94e1683526171b58164b2a121c48210fbc4114f220ff1ce5806a7eb14ccf5300cfd7307ab61e79d53013a87fd0447d1df85443578121f81c5d9578f4f64c98b7361358815f657e83144e99800130a86cb097092d608315477fe744718f8683bad07a38380805201afbf34907733445b33f88e227267161ab037cc0b71bc2113631b20cc1f11b238600f2e00df2c0809c922b27f477f2a56a16e32918b373c4f3741798514f623cbd2625abb650d5f73c64182b197574b8a22be850827a060e9c670de6840d7f460dd2605bbd354e14674e38480d8f064e45c483145744174000dfc03ffaa32af33615a4f621ed62658b221423826e4515596164545b0873e5202b6be84e83e35a7ad84247872592e77477e6423ea7ff42ca43738bf7778967315a653c3b377420a8672d840e5f2575708063fca5527000328ea80b8bb883d0587f81087b3c18699cb42745d8176d4884043413aea12e21476a23a71a33c22243c145237285b3c4807c5737e4556fb5528169a355d0f44bf4188f53a562ca21387c136315934bbd420d02d0792a2548c6165252a701302844e4244e3ca86c3f384e18b83f225716a7b215d32513850240547429e7082f2266144a014bc3978559e8804db58dcf872b17588189778bb378824ca7678a676ffee6782db98dfad64c14c855aea86705b048dca45254470d81710dd7000735688337684410b98312d7020ff960475869bc648456a31a5e513584021b8c85ff132a911a2c7277df4554ea085e5ad87208b015bc9417da883c87e730cf349721589777397df9b86f22486f83475575a9677fc40f8c644ecf384899600feb07323f0402a7930bb9d09437e88315d77a36389953893f4743507c82537c1540e9428eb0b11589022f5fa914fb041b29a22923768062241c5d980008500ec8f0961a242af3005af8487d58a281d27782d1d4735de59371293012558b2f197e7d813d89c72bd1600fc7650d28354803e04de0f0311ac098f28301f483014dc99435c8949259834ac47fd951404c919183d595e4764fb6735d92921360b31226a16ec0c31ba7799ac68792f29034c6044256457dbd487d1a43a0bc027efa5779d6e7ff6f42f27714b3360eaa3c11a8a01ac32b06fa2bc7b85282446c8178011f6376f03308b9f0068f19a221fa988f494991d994b1b77ffce37148939147532e06408e77514ff9e489033872d9f540fbc2452a421cc4e172c880463eb2690c453cb952a11a98864e47a079997979694ccd434115e45a72e61ec4c39b4b0a18f2c1791c850dc0c23d03202c2d003fa5633aa5333f40443f6d6a3f90594418d04796b6694ae67120274f1d162f556635242254a9547291d25d62f4a39b92800c580e5ac88050b00e84453ba5d5147063597da34c58c2a5ca3481067a8f8607a1b37630e0c136c65963ed914c6b98a91995a91aa80e1d830fc022188221a6172000b36affa64164abb6ba9ddb399545c44e343a156da9354e913ba4b928f199458ec2402f928e5ea48e70e745f839a82912145f767c899aa8c9f74b7ae34e13f81704aa31d1e0ade1faa41a2381de6a817a495553c58be357933bc72b2a44a004c00f85c179618a5686533e4909074d99ab259a0bdd0999feda0290199935b85cbfba177ce51a9e5416a3a93501488e3bb5587a4a54fc2250b9212334c2805d880067641d55f51c90d7adea00aeead02be02aaeddea73350998e0b7a0667630873787e3c19c17f31737dba5f49a43c3660dd7c0791910004a59831417998fd99d3608998f39959569990f6653eb4250599169510365a4e9215f191b8ac54f50965df619ff85ae9985f270adc8075e97f65434077eddfa0d04100d242b4d6c2b4d28ab319438b74743a04b7774b850531f784c502274b0186c6afb74770b1852a768d1e97500267549b970f3774eedd78315673f12f7983ea80bd240319646a7b5977b0b5b7b9ab621345a4a5bf440b601452cd2a352b872e6e00db5540ede70ad31773b6431a5f228ae04204d18c0b63c8401262bb7d5a03123509303dab2668baebf56791d38b741590024ab0eb9250020c356bf959402900dc2025767b56c44a67ab0c7523428954e09ae9886344c06a3344a50348a125ec136d305b1a5f9a34e93283f0141bf411b2cc785889a00628b0c01d43a207293deca43bee22b8340c0d100ffb7d25400d520b8372b743b47015d65bc09b5976d38a1c84ba0eaa00e04200d7d780de110bdd770bdd71000d11b00415b3e86db5b6c65b89c47600c467a137744fad73f53138e333abfcf25458f526160b44f7d8a2900655491d572f290002e77c447cc7635bca00a3cc087644982467091341022a0081e30b759fc74e083b3c9ab3f98686fc1787443a7c56fcbc1e0101fd62000e110b4414bc227fcc6259c94734cc7245cc75ce75fd5f8680fc68d99cbb0e4eb4427911dff831d9c6877580640e708b63472142e07bb5908bbb05b0ebaf716857c3b78f81ebde22b8286c696243fc02b02aea00adc0209dca202079a6fc38b34e5927b52e4ab1fb26902b3ff665fa887e1d3adbec2315a820ff830ab27eccb02e0cb01c0c6c14cccbf6ccce1100ed55bafdd040e04a0870cb3893d5c15d10c969f5813b4539202b522d925464171a85bd8bab4a485e5f00ee560ce626bce515bbe5101354e7431df4a439114839254ab8330028a20048d602792a30a8b000540d00622a031e023020620bc9458d0e2a2d0fba3717bc2579a49845721d0181c94050c39dce331b32a2cc5dcc6be0cc2c11cc7be1cd26f4cc221cc5b70f0605d55d0b8471745e3d225a199ac017cec9c5d610450b244142b511a42dc5d41ca80fc628ac860c40940d409f03a2fc1129f19284dc7bb3f9448836083898401d53002a1fc0332d003a4d00673d0ff0350b00371120858e004343002e45ad62fed2727d127ae61126c8d212edd73effcb6964448c336ab1f0dcc1d1dcc260ccc719c94416bc225dcd1799dd772cc5b920898fbd71a83952e48232e811228258112378db597023c90c52f8e2514bd81bac807bb474c2327c98009500e454dd4eaac591d6431ef81019254488aa4634f4dd598a008217039ae200334a00269d004b6b0033c00087ca0097a100ca920068aa0311450d046938d25a1344ac6279fface084c00ec600f22a5c625cdd1855dd8242dd87c2dc7762cd8e2cdd1253d0029dd55c6445048d84e54d315e7900077172f81a58eb04414c0e345593b230dc8b160a6b15b68c4a59d61a1fab17aa6ff0ed8845be91749e00aca2a3007ae503299c3063d9006aab0037ea0047fc007afa007b3900cc9500b4f00092b1d2e4c542e63c9572bd16f7fa10e686c0ff31ad87b7dc285cdd1df0dc27f0d324949181fb3c67a4de3c42c00f680bbe4fa449be66d4cc8573381b50f4b2ffc7229b9f1406073b187bab1bba1a8616bade67c355a1365e55215cd4b00652549ad2d3fd120a7544d010eae0873d001e983091dd0073fa00674a0043c8007779004a8300b1e0e00c2900cbbd0093f40217b329626310158db1afc3397213500f31ac7dc3de3205dd2733cab1f735cd099012ac5a181d1a1c715bd1ff3dd72fccb424ea19e3a5845131380b20fa9119fbe91139c2dff4b3c9d29c2514bb37e92613bd4fbbbb1f9bbbfa54dd4e39000bcc3c31c1998618ec68634433e04bc653d0298e0e02ae00b2a60019fa3026c90026ab0032e80078070073ac007bbf00b00d0e7c9f00bbbc009c77d348472007777d329d130d1c00e15f0e2f8b0d1915ede6f0ccc1a4de9c3360039067a04470d04303e0527681be55bb3dacb843deacd3cbe14535a2ce112a93196bb5377aff44f93726a09f84fc0c3df622bb6a4ed80b2f9721f4fda09300ec0ce4a1a1922b46be0b86c0fb955483ca431d580e6cede389830024553001d200b6a500c6b800768a0034770047a1007c9200c7eee0cb5100850a00240a2841fe91a5f5e3830cec63f5ee3bbccffddd55b4dfddecc680c1fd5bdc18794783d543c18306c8411bde07dc2bb3c00f6c00e43ce815881284f564a28910ff1a922c2776af56b0eb451cef98bce894a4bb5c485e65c0eee9000ee500ee3604a72171368a3311b8c01ec90e0bc4bf30550d5b8d0389ff31722e0e029d00c69b00374b0ed8090049fe00c499f0ccef00b84b0088d800b5b4c34b8f73f49a33137a6ef910ee9805dd2b31a48d950188d24686cbb433df4171a87099860c1e1830bde6006733008d8ede9203cccd14b6c1dc50e26bb3652abbef6644aba51770ff49afb921bfb02bb5a48d4628b7cb2999692fcebc09e13fb506533e113bc64a0f15a0072cae0003162840811982c101441ffa1c037157352f49145878e2d173af8e839954ca3b35a809adc1a5140a44803050c18a060605eca9204ecf113102066009a01f0c5c46753c04e01d766eebc90a9420111cf447e234881202611d1aa158836a89a859143bf1d58b6ccdb026673aa55087a815f86983baff9ccc0ef823d76ea48ce5349e1c0817cf9e6d63d8060c2847c08cce5fb6b6e82390408de159627af3082bae6123f969720b26479e51e5b4e9060dc38cde3ecae3b704eb4e875a2559ea437b2803ad6045c8b8cb6500452a40bbf15b080095908596954d1e191c7cea75db382bd7a954a892853509d3a2df0f4e84802e006080857537bcd9901ae792fcb6fc0806dd19e2d7b86eb990aff15b0075500276dd0a068d12a48931f0d83506ae409cc4506996eb891a59a4cfae36927edc2d9099f01c069eb1b7a4e3ac79cd000a3cb1c0b353c4043c036244c43c2ca09b143c7def1a61cc4122bc7b2161170b1457734d36cae7340132d341d0d38c700747c945024b7a261ad000262834a3a11467a0a93395ce9630fdf76c0e38e8b66294e1340682945926af4ab863e0c2a18d33502f0b32e83efbce38ebb981864b02707a901079d63ccc0840d159a7a8abea7aab920ba4cea9b6fbe2f31c0409a0a2ac804cd680c38800162a619449afeacd9299c6bb253b0c101ec718d9e096dec702e0b4df5cb5453457cc79c144bd4100179089b95c5175bf4a6b2ff16132887b3ccf2d9a7c37cce19f6aeb9e6816bc2094b2a2035d54642b4809044c240240a9e7c720f5580a0230f4036f924985df850c2963e4438b41a753178af026a2e80f7020d126cb3269f6abae02c01340067a8056e79464f743150b71a8231706a4c9146307810820b4098e02fdbed8f806f0cf0a61d33701b21136be0e0a9cdb204f8b42d7ae63900b4ba96e94bb0be846df9afc21c93b5b0bc6895e730c658ac2cb2772c4b6c46ce36f34cd853ed423ab41e4f3260c2859c7d5635fdea4bb200151451e4c91e60506589429298e5133eeef0431436d25577be31df1d80ac35d9a4a9ac35cb92c93b7eb6f96f9963d8c004138647c06020872b88aabeff44c7a48f0032a5a96fea8207c9e4024cc159681f6e6e89e69b41a8a1c61a6beeadbb3b99f801b5009493e63056c13c5ca666bf0ad3f97559157b3d315ecb49513116339b91c653e7b271b473949ee7c71f4f4287a4db9e25c0b9e6a3920a295c1481b2871dfc5022093e52f9230f5564c0a5d030e773971a38303d6bbb7ab77b936469be79061933d8600397f0a72268fca8f0bb0fbf46fb978a7c2a2015fad8c740d6c84006a4111f03c4a31998f88624de408d6c644a7d0bc2073bd0311a55adea54a913918696114211c94e44ba6a510a51783b5e25c02e1c52d5efe64281a5a1a62427b9580192c72cd5a8a3710893569efad00355f8010f7608052094ffe004609ccd50857a4fe432504199808e6ef6aa5bdcac018e472dc30cc850812f2cf0a5c7156c7ff281dca2a2f1864a354a518422dcf80a458d6b54e01bd4605c3798810b86c4a273e87bdb05f1a18e0d8666437319a15d4ad521049828307e09a1ac72f5ba9fe1ea45b62b47027a979946966a1d8129165dce812cb834cd864c2349b344051ba9496c045abb5e1e0281071e344114bd50811c1d968bfd5cc06d75bb5720dd14cc01508300e8f022305cc18625a92b2a0f435c34f063a90c0c800046b10f7f1e44b0f7044e04ef09939aac610f69d2e301b2c04534be79013890e527170c803d4c32bc617d509186a4cbaa5e18c9d9e52a6790015acf2aa3c9ffccd0689fc0b3d03d41a334a6c1e5782731c90d213a1224194e2a2260c30f54b183252c81077eb0050c5cf1cd42f1522899804305d3973ed1a9cf271718c016e1970057dc4206423888b4a231826f180c7a95928f34e0058e9e66d35203b880a2e6638f6a302c1ab118c120aa690d6a4842050de8c51c70211ff3a929902d950901788450e0e5d354cb70e43e09c348c4a44856968191376084bbc4a4289399e18c87600822b20aafa13e621a44715892e541c5b004b8280d7a200a5b2cc10f3b10c51ece260d36cea705076a67821414379acc8d260c1a0036b6280d4899610e7d68830adca0ce8221ec4b3f9da6bb2eb0c6f964a28d7804c705c0e131c6e5ff2f1a99c800a6a442d36578e5409e8b133ce3a6c1e18506341f5c55aa42e8c85879a392b76b110acbd12ac3ec6a930968a48862c8a10a05cf34e70dec601fdab4a81d698d439001227640871decc115e92c94ff0e34c52ac6e43b6b7adb150520b901bccf00e658862b4c819002a4b37045cdaffffa830dfcbc4b1a6ec4ed83ee9354758dc0291ebb063e20250e5698421290435f76b2a3be99d8e364c3cbd1214114c910ade39327ac2b656ca518dc1de6672d141a78fdf221a3c5f085a30c5668e6211a64a12459a53465b37848d1687c40083d48831a24fbcdfa08d05d20f36f4f387bc1efc4040e30152b568ee10a57b08729d54297b4dc533845d9033effd488cd9f2a55017bacc5cf84cb04019ef24a495420a68f22c730d820286a90a5b30158f176c231481eed63342f2c1560c21b221155083080a96b7679c622bf603293441b07cb3264340ca90ac63a62720d038b0e7494f247a9695a610d5b1f0b3cc9158a886a6d1515b9cf8df9d1596431687f34976eb0277fb3a94f358a721b31dd078fd1c8973dbe310269288e6df1eaf383aad16d86a14b0418a0c64e0c408e0800431ad1e91c76943b9370b8f81c34ec918e4e55de1b43b243373691617e96b35671d71c2432c73b2ad9a271dc7543fe9eb18980f74945c278782a7946297b543cc032ed1b1fd7b56189c2b00833ca7c74db6c4e6c626c926de33ad644ff8a019cc4063744c739b159c66c0ab0e77747671017108034968109691e881af6a086bbc683d468e4021c2a18017bc2078e9d10c01dc370c537f6176f9cd444e52bb7893a0e00a9e676a8bc8bccc708552771d5e5ce30277a5d0957e82bc6803230e2fdb4ca6c0c188b1f0059fbe0519339ce347a04296a6e718b73e6a328f85c001bbeec14b24357666bc4944ee33c0732fad690362ba41a94ab8fdfa2230d0c94e44b1520cb000af00c0fc347e98d1f0f4c179581026082026f4eb735d4d10e6dbcd63a408f345867324864e5883416576bc24918ab0fc128678c6995ea34b4f05e7586316f07e1dd3324a20f9963a1e6105ef8036f6b1cfe28875123d299ffd054beebbcf38adad909a648b6937924800d5a9d83226cbf737018e04bcf5812918833a89086eb208065c0050b38233a693c70e88f02b314da9803151884ebc08771308365f8863371c0f16829e1b30778583243ea341cd1106f583ee58324e513a8597987e7dbae175cb8de9991c5208c15e4101c4c9d52e1beb2128d24e391514a2fe341a51b6a96dbc89cfa700d4541109e58b19ef88904f1096bb83070388765d0135f60030be8290f5b8868608a85501791f892fec8007b38865c22944601073beb0fb6a900c5310f15c08458b0146bc08776808667d03a69d8864bc18ecd02417a0896e191b17c02911584119ab941167491c2009a5c019a141a9acdffb83ebf889d127a87be98194dbb3b50b28b1bc987112cc4d35009283325534c2f5412123eb3077c8009cd924501108f0c701070500704600345a0011948883938b7685008a23080690909ffab0f7e280068980381808a76b10707ecb3b5d00f02600a0b803ab1c0077a680033581269fa3d5ba49759cc2003081645ea41f11ab24f348cc2a807047847c800285da90c7790874a249ab82ba1bb1b217d5c4174aca77cb231e04196b12b258dfbab265b452873167588c657c4879b88c88894457cc09401c007d738873d800248e8805e4397a118810aa00000dcb69e1209c2e107029000faf94677c92ddd0a37c2b187db7b0a1588065bb4077780005720c005fff2409c689095cb899b10007678b1b2f28bef1baf1b4bb8c3e0be5671917f9a9514021a77a8be4a84241bc444e9abbbb8e3410ec1346139bbd228c4d008ac83bc38b2f3c1737806d410090260077068c3f1e08789b4c5b6d989b67110d738805ea80419c89a25190402089f412800a5708a6fa08030693a7b3800adfac6f7c82da4b38701003402988058a880f5c8048c9c877648016cdab326e489a25cb9d3ac13e069aebdf23eb663240d9915c2f0b1dc498ca794076fb89d8162b8a111b2c5b0c17ae0444e2c8c969999c1b09997e92063019e6738001a82947da021bf3b807d18c1b2c24ec28bcb687009578cbd6a4a20b58003ccac946f70051a500414ff600f862149888906aa780a112889f7289c095084a61884165814a1801732190a11d813c181a902f00607e8858160b40ba8200103a6fe228082c4ce7b4abb0e49a4b09c99525b91c4803e5b019a822a28ba03ceda014e794086d7214ebff086bed0b4f032955633abb24a32bab0b417bdbbc13815eb9c28d850872371bd77b18eb619007e8003ce01873ca10149f0005c20088871a66ab03d11e0a9908c36aa199f03018ba0f8ada8a080580050c8c9800a3800335086b311013778830a0033d081a7ef50c67988d184620c0b85a14fbb41c4a08ccc20d1dbe4319eb9abcd682159c19915598cd879414005ce46a49d155dc7bc78a18094d04f42240c799996ff51a452721a9168c81d6507f8a84b7b08b7011804f354810e10825cd22932898e475188f94092d9f812a25094dc229c68e0a2dc98c083a013b1230765708542790369f09c7732367b11007048cab2228c09d8b4d759b512721d5bd1b17f9aab0ef54d4d0aa8ec92ab179c1581338c7f924185db560c1d9114ec3eeff3900c2132bd923e4f0a45f2f391d4800721d951b984104fbd4cddb20d4c90b3e6d99c6e6b9cdb788ac6810a0ea493d8b00f75a080d0a300cd0107b1f206056086f600c7980a19e5f23a7b500795284443142fe9db10a7549d5a398ca924b86e9511ac44b586539110ad19da1154139a99d661d90b8d15894395453ac43a4d3be942b053ff79cb6760098f6396c23293ea6843eba08644a10fadcb1c5cc88542f92d6a689441a8acf9c80534f2a981a0860918a9740a3403901f0528069c8c8a5c102d950ab061f58901c85803788660910be0b927043396888b9510d51d98dd150ff515ce08a879d49dbf7d56d7a9993a851db9ea44e6f358d5595112b241ed9b8b673807eb444588ca54d69857ba8cc63834d8356c1caa5114ff5820c4928ac44cd809680f0af81b04f822334880626083d8f880a8b20698f0096162318c3d19733c96bbd8a7efa3d37c804abf5859da9c9ddbcc50156a38a2b99db9a21d15b18c1bcc4dc450911b1c5ec5a095990911ededbeb9b83eeab290d639571041ab43021e48ff810b773589d4b80dd6483fd798cbb5f0d76b0b935cc8844c008744898f02a20f562d9ccd695875784b03183a4cf08662f0056a68015da84027b458b00395869ace1965cd57bb91b978aeed85a4986959d9d41deabb2bc9b0ca838344eb221114cc4dbaaa191f8b95700d11e1ddc7e922a1b44ac1f00591f2b290572b3ee4411e914007b7b887d5c05c025087b9bc4c89048730e11cceb9cccbec0f50a1d5f83893e6193d0228886ab89868508f032926d3fc3ab003639900956559b2b15ab21c298df0fbbe0a51d7843a21a0315948ca9918c1caebda5babc42eedba155dc91d15798c9a89110cc55e9a6dc4b80b5742de47ec1c0d283b1e7aa03574282cccff55070c3062a38c3f9882a9f1a0cb3744b7293612ee84102e728a713b12a31b0ff99389594c90896c8b88e211e72495b2c2117daa6140a54dddacabcb70c1ecda95cd48a1ccd04df0c22e49ec193f468cc5f0276498a499a59d5c41c13ae534e23d54bf50bbbc4080f2cd8be63c9655449e1f3189e5493cd7800f7b40659fd889b4085673bec8d8e387f8e05441cb05ced989ce59d0904139cee2894f09e0c072cb7b833194604dec44444762240cb95b1809511494245cb19d9ef1d016a291bd958cccc8a4c878446400e6c8400064d01559e19567cd4dda315cd999d90badbb11c24eb8459993b0d4c06a161d6a489768427336e74e893f4c218b0c802938ff508bf1b8dfda1d8b041a3eb03bcdb1e007b5e8172296b2c00a16b74de9c8d591089de6c558513fa69dc8183587a6115ee10c77f015a151d9bbaae84cda1515c2d389964792852bab563ecbf0b1b792cdada4b196091ed038632673d71e5616219ecbeb98a229ca063551939e58a9ce3a8bc3b6974da9a2ff823f9dc0074f5d8b686ce5f56da858a36094a94e86ca112523961836d196d11d68c58c49ccaeae3e35be2da81086e8b366a1829a0c8a16a86546dcc570d992ce5eaed46094eea060818bb1538946be1809b918a8f13676d2059482030dd0003838810cd0806c80eec3bedddb9db72c3a6c9ea8bcfe24622489a86549af9420480a98878ce31173ff44dfe2bb991cd48b7d6aabd7de4dc47dc187a6916af515f9f6d0104606d5ce248cc6ae04b02b60ce95fc2e6115491149942b6f407064b6c1d63980034f95f3f5ed8e3b9ee3310974480dd658a04c688116c8855cd870fbbd5f5d10f1333b01e6d68013d0800c3801c6de8effd2177d1180f12c26fca062e1e6e11b7f4b1b79cb1ee171a6915cb828bb53115fba18d15003665e7166d5a6ef0f1d0705a8ef273fed16aa6fd6c60c3cad0c18f16f8b560c7f7a2bb9c21d983de6b5d20b19e220f44d898debb86f3689979e57d26d941690060f1f84a7ddf03ab7f30dcf846cf00ec4b6eeb388eeb348a004ca043b239c6a19096ea610a609daf422ef530c680afff55eda5e91bf0d69ac9e8c26df0c05480027cff4be4d59dff4d3879ef271706fb10e6117e1959ef1a78072bea9bebe98c10b0b3ee3d3a85c537264f47b0e28f2d537e07586e1f537d0803dd717c3be8640876ec186ee6cc0e40b5894d72089f4da074b23a58454748dcbeca84e15929687beb09db7b268aeb6d628d7744d0775d4b6ef73a76816c29db2fe6f044f111186de04470007779515cc0bb45aa4477f8601bef12134808fb30d8a72af9f9a7336aa860ecf855faf0623d005626729ef38f663bf86649ff8c02ef121955a29ce9c7fe76148e6788fbf9809e7f8f412686b96ae43b52bab5479275780966ff94c27774c1ff76afdd082d274bcea0c74ff97ef53e7158ed66867769dd9de34228351cd66696ebeb51b82e4a8b939429973aa7d831170834c90eeaaf70ee7c6fa147fee134771138783338b974c00a2c0ca21c1022cef4e2fb7041ef1354ee0e4f64c9aab160ae1147e790530832667794ed7fb71cf0c27c7f9bfbf79f96eb8747fe88a9e6824ffe8790fe97967d9ac10cbb282e546470959639a879a1009499e85c0f59f7adaa8cf85ebce86e9feaf6c58ee11d785afd7001157fde556ee8ff97ab1f7e67f4fa5e5b9d48bf9ee03d8f7039800b492d9c22851dac668049868c3970c4824070538fe962787e32787cd607ee4677998c774e97f793f6df2cef0d3ea4bed5017eb7b042f51bb8c65e634bcff985358439951eaf1f4554844470d807f16aa79984148f1403f6a0df0a5b4500bb1c7800dd7758088566d502e0c9932c14908e7428502061c3e8c5860a28187131d16a030efdc818e1d9721403021a4bc0426e5a13469b29cca722c5dca73a960a68499e466e2cc89d34c020566c6293039d30ccfa03ec725e06912a9caa6c89a2628f73481b772de9079bbea2d64d69008402e5b666ed901b21d9f797c76ee9981676e2bc28d0bf71b856f15ed7e2b90f75bde89d1fe02064c505a0569860d524b9c29d79b6a6f180f7a234204aec9226239ce954b570b5d9ca34dec5b6d74b5bf91ab796e816184054c14d03ec314362c3264086a9bc40d556556aa55bd2dff5040ee26b905c36fda3c2e5cc138a0cd9d0b6f3e33c173e72a9942751935ea4b98e510949387c05c48f25ef3993387de63c77c1dcf6d8cbff19c8179f5e5d737804ebf5c7a101ff675918003fa3711010756500101150c22d0372250a0c81c6cb021a1222a6062d908230cd2c2201814f01706230e34620b6f8cd0420b99b400c286235040c1475f7985400253eda61d4c28a1e49271c5fd781c71430e971339d0e104d44cce55c75473d441d9d2382f71f79d55e02120de77e58d37417aee7964ce0160e6738e9907d847df3cf6dd979f5c6cc655003d160968807f735284d144ea1480813a0b4a53800a129a42a12b32f4d1470aaeb852e11c73a820c286ff030d52cd88180c926941911184010828be81410b236434123223a964e36e4821c55249cdc59480710a2c605cadc50179ebad122057e472c42599137548050554944b65b79237267565658d081c10523ed09a3581471c1db0d63e67b675805af499390f05f4c9556e45184574aebafbe935a03a7f1500e1a08ab02103243ff4504a0f3ff42183bf3408a188086f444670c19c36f64663927e70d9088f19219905164c30c76db8e9d6d3b15135394ead1f831c1c3912ecca6b4dc0d2a4d3924a62b79bcb099474524c5c21b015976089b90c7ad6567b80b56682fbcc3e150d9da69be6c645975de9a29b97ba725d045a9f2102564dbc98744003be88c08088d7ff5020120524280cf1c18b0a2b1ccb8b6eb811cb641b58f6010e736fe0060e2080808308b5e5531b8e39363553371f1f1332e13f167e0cad8b2fa79319cc3d3e1d73495df7b2cb3523235555357b331b8de47de491596a1d6080b7e07a8b56456b55c456eb722d8d97017519b0f47fb7d7ced7805447d36069dfe0a282295a378208104038e18813534001492543488243db6e4cc6b6f592782089f6920c01fd10559450020e26cc3dc41cb6e9c69352814bbec0e0867f0c64718ddba4b29247f534f9c696b3549b4b366ee57f5eb1d978c4a49ead94853d66c9d65ab26500d6958e3e6c2117d26a4781da59502277d9e05c2a820ebdf4a52ff0f20b602612a1ff4a9000114e68c2120ee1053140a1119590c40da8a73d0fb80187d0dbde1014d1432100b11295a8c2f76eb00145a0ef6f2f630e52e06738f9cd2438353192ca96441d9eb48c722d019c8d6c43b301d60824d18256b4c6122df6bca72315299db7f6513ad5a9e57515bc6005cd65bba6ed2e6a031a01d61a813c4778610633088325aae00115f4b07b3e0462f7688082450a41886da0421b6c40042a54c10448bc98cb9e442c9374231e4eb415147372b22a6acc283dc14e509692452a71872af2c04acdb2240ff3880481e341c0b40ea0cb038889810e7c8f5ab6358fd2cd238e6a5aa6e9e084b439c1c599fb810b44f4944701498d2e739041231c31831a88ffc11244a8841050d08673461260e7ac040dda498336108108e78c67c0e670231c4d69951aab5c0244b9007fc2af70c2094e7014c0ab2a0ee5393339069458b53196f0e6463bda8acd288a4bb0d0e82be6f0c68cda8346d2a92e8d1c315d45c6f5c0a0d531a5484357682412a06b46430498d05a148010853634220a5288673c49b0539e5602055440010a84d08147d5e63659fa9b93b0e8d072383157a4141915ef172c4f6acc492cc18e4b5c152b98b1c42b2841c03b766956f5e0724ce9316047d6914634ed635bfb38e601dc089f6c95ae3ef039a901862697fdb0099a47239734a999277539044fd6bce60442f0831f44a1112490410714d10120b8e251ff13500148bc6231a5da72554b81e83862e2b1f7816c7eb61265fd74229d71442eabc66ae5cb5cf29492cc8c2a34f3062ec9e3dbf2a8a780c0f4c87af08a2d6c3d102d407bcf46e242c10756844d76112c3a041bcd0adac59a18ac139de8d4ae3c39042f1fcc6e497dd69191806424cf0a89e5b6531288ea0364a28c477c531b3f52220727c851527eb1ea246425c01d316bc9586dc3231e99433ceb0da379d62326f4e4832c651ad37bdce89e8d0ccd23737dc69adcd81636adc93e6cdaeb39e64ad207cad55cd3848b75a9895817cf4e5c742cef04d4ab5ef4cd324b3a7625b29802d1ebd4d770f1406d6a7925b25ed1ef7eac6a25761ceab298e0561ede10ff0f95176cd62b07175ac175305ed543610d03cd8d68ba2bb6d0144711b7a9b9dbeaeb5a8e1957b52413bae55a1aecd2a5c1683650c6f6e98848d01b9224d692244f299643f319b3ad027494b5a295146f5250d72e893993fba43b5ca6a3dcf40819e2d99c5829bacbb5f2524c5a1e4b84d9fae560bef55a23051a47ccc461d379584d715e5db8b2754c339d38d71e5c31d2d0d1973bc3652dae8b4bb4f6e125f52e38c1597a998f578294f8e66a38bad215ad8673cad64a5ad2c672323f5512b3db9cc49662ad517a6a342d5e9e271fd302135bbdfc65378a993df318dab8e073c1648aabaf7ecd4f8cd8dcccb8c25523a5d3c83e5ecd9f7239f3c5768e7132fff731e30ce7630262a9f17acfade3013785292981d938125da4fb469baac0b2eaca68659d6e73353c5201ab95364d12727ba5b7a0d3321985fbe0e1e2158dc73d5db6e00c9f6da5ce3ef290400447bcf334025d8209970bb9c89558a4a5112ef186d695cdbdcbb282072a4e16f04a6425edaf7f7da006cdaf7e15fa5a6d03d82495ae525462a272db8487ace31ecf2d7159d6e0ae95ad64c4797ab205a6b79e295b73dd969aa0bb2670a909ae69c2f531e7c36a1483cb8307677a5cf6d362bd1e5346ab536e2fc763f198aff5dcefdd6ad761c62396fc6856051dd9e2c8ae939bcc76db596d0a446b6f925ade483c0918b782152c2daabbc71c122fb7968fedffd1e36bb8aeb7d6b0c31d5862b568842d0eccf0bc0b2e677c9b49ae45f796b9d802629512ed1ce3728b5fd9134cf7786524062ca057b6939df02818f5422a92afea5f2424498789c3c26a4bbaf3929878c33b8cd55891c46fd11dfb4dcb3b9451018d45bb8d895b1d0004462047dc5aab095e891d17e3859905864b89e9d503ad8903c5c79801cd9a4057d3adce09ee477599cb999c899a9d098435d857c8205a25d8e8b98a778c5582419571980c71d80ae3a00cd9290975e88ffed09e76609cb8991e492818d6e958b94dcb0c42985a1d9b9799da04d695487504d0a1c59851c05c9558e3798b7dcc159a9ce0ea88e18619931aed557e44de1aa1d97595d4ff46c888bcb105d05118ba3d4b0d8a1e58b91f4a4c99824dd9ac2059fdd94f934449a12dc5e9a1c494a81c8f382195d59d78a48725e64359a99b0dcaa08355617b4c98ba31900bda5ab8ccc729ee55888160e11d5e74e98788c1472c1e5e7cecd50ac66174bde19c401334c1e05de5c77c7899ba4d21ba89e297dc60e640598f50a22d95c314b95eb631094ee85316695c5364c9e66c8920c65cdd9dc759edd2ba19233099511672049850a08685207d18139aa598899960e1a9895fa922d2a4c97cdc077d4c13af45d77e50103d4c177fa0c388d955b039debca1df267a895a51187a78054cc0cc7a51d9ea054b9240c76bad12ab6451ccf0c8ee55d932fadeffe791c7b98d5119b9c7b9a155c409237b7004bcb9959b0d1e9accdb9a68c4cf898bb820534de607f5899809be497e28de050d9e9bace09bb86345d0837df0e27dcc1b4cb2c9e0d1d51e4e8b97049f5aa9dbcb99deeef15e782c809255e4747c52b1f45853e01644f548cb0960552463014ae2141a600d86e4701950df3de042be0707922286b91ad0ac6273854bb011569b1c1c60c1473dc6220505e434c14973d5c750da0960f1a5e3211e031523f1b165def9d6cc1c58789047954155b6a94cfea80493f15816c552695a895574159485445911d0596d85588c87389a4730b5dbcd9d9fdf1197ced5e559ac852c46deb8d4255fa1209ab15aab994e1cde62ded345ded23d50ceb187e9a451e8ad9701e2d2ffa0a694c99d5895c3911c094532114562248091e64636e18199a78ebdc365861a3176e326061ffa79d48395e49888e2df8dc94ace554cc6554cb2e2bdfda207a2990986d84e86984698215fbaa2d128a5ac89d851ba627ec29b18d6550c021384f11248b0e57a52e27986a4dcc943774a876cedd3c6e4d31655c5ff4959dc65c595744556f896017e5adf3920308d1aaae5dc84eda699e028e3b5c5aaf5661aa2a0735dd773c95972051b5f1569921e6991eae3eaf45c5e1d6799a1da85e69d6c9a478bb25fb9ad55390404003b, 0xffd8ffe000104a46494600010100000100010000fffe003e43524541544f523a2067642d6a7065672076312e3020287573696e6720494a47204a50454720763632292c2064656661756c74207175616c6974790affdb004300080606070605080707070909080a0c140d0c0b0b0c1912130f141d1a1f1e1d1a1c1c20242e2720222c231c1c2837292c30313434341f27393d38323c2e333432ffdb0043010909090c0b0c180d0d1832211c213232323232323232323232323232323232323232323232323232323232323232323232323232323232323232323232323232ffc00011080064005303012200021101031101ffc4001f0000010501010101010100000000000000000102030405060708090a0bffc400b5100002010303020403050504040000017d01020300041105122131410613516107227114328191a1082342b1c11552d1f02433627282090a161718191a25262728292a3435363738393a434445464748494a535455565758595a636465666768696a737475767778797a838485868788898a92939495969798999aa2a3a4a5a6a7a8a9aab2b3b4b5b6b7b8b9bac2c3c4c5c6c7c8c9cad2d3d4d5d6d7d8d9dae1e2e3e4e5e6e7e8e9eaf1f2f3f4f5f6f7f8f9faffc4001f0100030101010101010101010000000000000102030405060708090a0bffc400b51100020102040403040705040400010277000102031104052131061241510761711322328108144291a1b1c109233352f0156272d10a162434e125f11718191a262728292a35363738393a434445464748494a535455565758595a636465666768696a737475767778797a82838485868788898a92939495969798999aa2a3a4a5a6a7a8a9aab2b3b4b5b6b7b8b9bac2c3c4c5c6c7c8c9cad2d3d4d5d6d7d8d9dae2e3e4e5e6e7e8e9eaf2f3f4f5f6f7f8f9faffda000c03010002110311003f00e112f82f0c1b0694df26f1f31f5e9d2a99c6703f3a318151ecd17ed19a2b7f01ead8fa8ab36f7b0330fdea8efcd62e001498149d243555a3b3d3deda42099d71d36eeaefb4636b33dba42e8760c9039af0dda3d2b7fe1cdecd61e30ba9e00bbbece22e4faba37f24358d4a1a5ee6b0adadac7beddf968a3f783918ae6d4c697324455173d80e86b4d754be9b76e58f18c9e79fff0057e06bc0bceb98643b2e668f07a248462b9e952e7ba37a9539126cf659122b694c80fdecf41e956a18a491c176207f2ed5e28353d495768d42e80ec3cd3fe35613c43ada001754b8c7a16c835abc34bb99fd663d8f7b4f3f60dbb36e38c93fe145787ffc261e231c7f6bcdff008eff0085153f539f745fd6a3d8e6a968cd213dabbcf3c5a4e680734668011bd3ae69345d60e8fa94d721739907e00647f5a56214126b19c6f8463ef1e4d4cb6b151dceeaefe24ea130658822a1e9ea2b14392d9f51cd73b1c59da08393c715d006c30ebc54538a5b1739396e3f903af152264ed3f5fc6a35e49ebd7bd4c80f15b5cc4928a519c734503b19d516eed8cfe3d69d3bf951eec558d22ce2bc908b891a356202edc0c127af3db19a96ec34ae40bd39c52f6ae8f5bf04eade1fb48af66884f6128cadc439214e7a3e47ca7f4f426b9c9b11a966e050a49aba0716b720bb7d96f21079da718ace842bbe3701b57fafff005aa2bdd47cd531c40e0f526aac6d7072caa483de9495c69d8d78631e7a0ebce6b582aeee7afa573da75dff00a5aa49c1ec6ba0539390288ab049dc917a9e6a45e42f279ef9a6c63e63e99eb52c63e41918c74ab2490103b9fce8a9474ffeb51480cab88834641c1a82099ada58c80308e08fceb41d3e5e40c67d6ab49176c1a1ab8d3b1e97e2bf112dcfc2ab1b627125c4e09f9b248193fe7e95e27a85fbce3c927853cf3d6b7ee6e6ee6d363b163be2858b463b8cd72b242e9295943039fceb3845c742e7252d4d0d374d5920fb5dc2feec1c2aff0078d5d2eb19015513690318e39abb1a7fc4a6cc83f2302320632703fad66c88c04add002a339ce3ad3dc43ae614bd89944291dd44a1d5d7e5122f7cfbf23f0a66937eef3fd9a6c671f29abfa7cab03c533c3bc79132b670c06e42a0f3d30c54d73f6995d5632a3077e3ad5444cec176824f1f854a9c2e081daa14048233530c90055105a1900727f2a280b903a51406855294c74c8e2ae98fd87b531a223b5032908f049aa577671be58a827d6b59a3e38aaf32e41ce280314ccd6b6e6277261ce549e76547e7c771942e003d79eb8f6abd7100914a301cd503a7c3bf95207b1a86ac522c4f7f0d9d948b0cdba6994215da30ab90c41cf7242ffdf3f9e6e936ef2de894fdc5e73ef55a3b5959f98d8b7500835b56933c6cb14919438e0e319a1340d3355324f5c55953900e38c567c5213260000fd6b42356d982300d5dc9242fcf5cd1471e828a34117dc019e950c87afad4cce08c66ab3bf6c8c9a432263eb55a4f4e31533b8c9c1fae2a076f97af7eb4ec08accbcf4a88818ab0ccb9eb519c73cd160202bd194e08e41ad4b4beb3b945b6d4e3dab8c0b8887ccbf51dea882368f5a6305a994132a32712c43a62441b7dedb4aa7ee18c6d38fc4935791704a8977a81c12b8acf8701c0f6abb13fcc69460d3bdca94eead627c81fc2a7f0a298b22e06473455d999e87207c63a89ff009636bff7cb7ff154c6f165fb758adffef96ff1a28a0067fc2537bff3cadffef96ff1a69f12de9eb1c1ff007c9ff1a28a06869f115d9ff96507fdf27fc693fe121bbff9e70ffdf27fc68a2800ff008486effe79c1ff007c9ff1a4fedfbbff009e70fe47fc68a2801cbe23bb539f2a03f553fe3522f8a2f973fbab7e7fd93fe34514c05ff84aafbfe795bffdf2dfe345145023ffd9, 'GIF', 'chartecontresceau.GIF');
INSERT INTO `explnum` VALUES (5, 46, 0, 'Adagio En Sol Mineur (Albinoni T.)', 'URL', 'http://multimedia.fnac.com/multimedia/asp/audio.asp?Z=L%27Adagio+d%27Albinoni&Y=346265&T=Adagio+En+Sol+Mineur+%28Albinoni+T%2E%29&N=Albinoni&P=Tomaso&M=Forlane&E=3399240165271&V=1&I=1&G=E&audio=/1/7/2/3399240165271A01.ra', '', '', '', '');
INSERT INTO `explnum` VALUES (6, 46, 0, 'Pochette', 'URL', 'http://multimedia.fnac.com/multimedia/images_produits/grandes/1/7/2/3399240165271.JPG', NULL, '', '', NULL);
INSERT INTO `explnum` VALUES (7, 46, 0, 'Canon En Re Majeur (Pachelbel J.)', 'URL', 'http://multimedia.fnac.com/multimedia/asp/audio.asp?Z=L%27Adagio+d%27Albinoni&Y=346265&T=Canon+En+Re+Majeur+%28Pachelbel+J%2E%29&N=Albinoni&P=Tomaso&M=Forlane&E=3399240165271&V=1&I=6&G=E&audio=/1/7/2/3399240165271A06.ra', NULL, '', '', NULL);
INSERT INTO `explnum` VALUES (8, 46, 0, 'Choral N 6 Tire De La Cantata Bwv 147 ''''Jesus Que Ma Joie Demeure'''' (Bach J.S.)', 'URL', 'http://multimedia.fnac.com/multimedia/asp/audio.asp?Z=L%27Adagio+d%27Albinoni&Y=346265&T=Choral+N+6+Tire+De+La+Cantata+Bwv+147+%27%27Jesus+Que+Ma+Joie+De&N=Albinoni&P=Tomaso&M=Forlane&E=3399240165271&V=1&I=5&G=E&audio=/1/7/2/3399240165271A05.ra', NULL, '', '', NULL);
INSERT INTO `explnum` VALUES (9, 47, 0, 'reproduction basse qualité', 'image/gif', '', 0x474946383961dd001101d50000f0b571d6b48b352c239e714cf9eaccecba8cd7ab72fdfcf8b49269f9d9aafae8b9aea391cf976aebc999cd8b56b48756925c37f9ddb8d1754ff4c378ebcca7f9cb98edd9b9f2dec8dac9abfbc788eed8aae79968edc889ddc197f9cda7d8a65afad79a998973cdbaa2e58c5abea274d8d5c9eba8639d8156eacdb7eed69afad888be693ce9bc9ecda083824123ae7f3ee54453fde8a0f9ceb6f0a37fe8e6daedd688c98941bf9f5cc1c2b6ebedea817b6eb6413fe1dcdf706b66efe5e6c6c6c721f90400000000002c00000000dd0011010006ffc08370982bfa8e1782d278502699b4a8d129ad228f53a89649ec7abfe0b0784c2e8fa94f853a6259b7d9e83885cdaadb1bf600a9deea3496665972256957568588565c8b818d8e678c9182878453189586499a706f7e229f9ea0220b0ba27d7a01a9aaaba708067ba445624e16187329147eb9b79c6f6c31bf09c11ac4c59bc7c84bca42ca71cecdcf9ab68983bebdd7c3c61eb7ba74a9bb1addc5b8e41ce6ab05e9e9afec06ebeaf078f205db772821212560b4b5fde5f2e3ac0914463060417ffe6c295c080a85c364d2100e94e810c3a787189181db58d1e03871f378d5c343679ac77ff1cea55419b2658591e6082cd0914f16918920801ddcc933a7cfff9e3f81720c05f224c0a32f5db6a4235429c77f508336352a55e984ab5801acdc9a149e02113d04f4e0d145e3d4b3688566a3ca362908986ee1aeddf6b66edcbb510b92b4abb62d5fbc190267c56aae70d7bbf0024410e1428000b237cdf69d4bb9b236cb9827ff25a759ef66bf9d33d31d4dd5b0e9d3560b034810c0f1d8b2fc4263134d7b76edcf9c73bb914d1a346fdf4f5373454c784284058e1fc3de7df27666e6d06d3fbfdc9cbaf4ea1475634fbb1da9dbe14e436a5ddc5879e4e8dbd1af557fbdfdf4f4eb71caeffe9b7e5ed482837b1fbc38f98fe5ec0115e080d9c4e65e7c08c297a07df571e7d97ef885172107fd3986038004f294e186072ee8ffa1861f3ad72083f71d061884e251889c8518bed7e18b308648a28c22c638e383dc4868228a18ac28c085e77168e390428e08e28bded5a8e4892de9725a0013de3193630bb4e82291582e39df919201e7a5833c26d6013a64ba03d7522224576590d81465dd9634fa62129c742a18676e788aa45f9e39ea28269989b5d30e3d4c76508b9a56fe32e69e60cad0e6442be9b7c99c7a0607e5472f659740931d35e924499f62dae9468b66440b28efa873c905abeec14eaa289a83429a5426aa002ef618284f14c730dad1409cf6069081942dc51615e05c3adbb09af2542a46b9a4c4cf4515a5da0a29d89ee0aa3baa246922ad028460eba6b98e8243421a1070ff11b57dee894f8ff6146a0c29e7f250c90f9810520ba8d392cbaabe3958243006bce25bc2b9ab0aac89bd1119a230ab221c5c0a94dfbc31530861f5a0310ea0b430430b7df82896002e6ccb2da199922aab8fe2b239170bc2ccd4c325940e40530f27101c22091a63ac6da4495ab0a2c64417ad43290a5d9aedbb9ba62b023ea4e03caba125c86c7408a5c8d0e3d150638cf3c5f86c5c4b0f2e682c16c58ab11a42726cbba683c720b760c1da6d875b80a0280b0b10b82dbbac282f17e870f6a860e14c93088ec23c2be179844c02040fdc0d5e3774d75d770f0bec8b81e06ceb3c87c8e11e7c30e796cfec03e896b77d2ee833970a56eac9617e771f9b5b3eff002b66e2e616df3635111fb35ee7a3355d1d2cf01f0d4f9fa03c02cb373f8004cf0ff00027d24b3ef925a8c32e0009fd90ee180a04cc2193eafa7a7fb93e956bdff9e96d674e75f6754f0cb2d6a997cc8aacf2f2ee7749b968b23c048a19cda244d00209ece080084ca00277e00209680d010b80000015e7a970bc4f7d6d8300cdd237965ba54b64a6bb00fc6247b0f461d005f8029dc144583ab6990e0faf40c108f3818adce52f397d63c6efc4678100480f6a8b511cf32e800f09acc0803058c10e8e784018ecc0894adcc10658c0001740a0310368d7a7ba01ba52840007376b5ff838680d1516c184dbfb01c642770fb114ad6d574b820a1512161722ff4c66119321b79e06bb2cd6306f103a946372e8bbe8a44d26cf63c0036ee70b061c71064e8481242749c94a4e5202238040eba854adf0d0808c0e03611bc8f88bf1ad2f70ed1382bdcc553cc5448c06a0cb63195527824fb60f5a16bc5f09ea983af79d0c314dc3c5577038aeb48960000360c00616c9bdf039b2894fb4a4342b7940001e612665d39c77a8e6bd10ea0c797624a20b93d0800faa2e0a26d401be2c5131f0e50084f50a481759c54bb11c6060ba20d325d4178268d970423d44d4fe98428010b8209923801e02b6e180153c0f02d1b4a402a5c940045c40952bca1c5348c5866e920560f76c9b433cba187376ae16538223d29236a66f981071ff2d4d05abce19cbb9b9026f7bac9a0b5987b85f6e7377021d283721d75085b68079472c6a13299954034694924b944121c7d78300868a242820e9c24a404a928aafa6b648e9e5fad9c355d48e6dbe5485ba682ad2a79d025097aaa70e7250cfedc5344a3101d79a84ea811620d3018005ac1107fb002f1a31a2986c45142779c0db35216b4d10dc17b51992b9edd467446b5b08c638ce7ebc937ca1305f724a36b55dbd94b23345ab29fde35656a02a6da934613fad87d7af9467af3a448f0fa51758a52e910172eba10c2079c919e461061280ea0a66a0840590d602f7245bce2c98ac9162309c59edec27427ad2851c6c8d2a8d962e4ce8be7880ab56e41dff85c958aa9879b22e17ff2c276a8e13d4dc46270444c5644225e80217ece00108084504fbeb5f087ee2a8124420238b30135e7037c0a81a966861f7c2ec92f05fdc55d3be823830d9864f59e4cde500c5e8dc9d8aa2551cfb46376956cf09baa3384e29f18fc685810134b0a80975e81157c04c0de06026c8dc01009379e001406e89ffbd28216409871c60cc7d1cb5c0842fa7d18b7433734d3880282196ae36dc4bb310f38338d12a62578af1ac6c23ab12c0485a57966790e1c341885505d0b5b268a08b410051050b58ee11e207f13497e8029639e4d56bc917e2471c100061725556ae614b6559a174029522cf7c1d2c1cc26870cf746a73b583eca427ff765ace00bcd96d868b9df108714ed1b5cd8ff33d0d0bef2c543df036a18055cc17dab00f290c01d0d170028051f1376ea6390aef9d9a2a0670b9bafe88c56e8b5afc386c2804dc9228a9f58f7cd108bbcc81f0663481a37875574e05f8481f78ae855f1db0cc3ea72d1fa21bc545a3eb4d83710c698a9ed65f93b99eb09467ae633cb50e4c918beb421903d7d501394b05d6985a849601b0c808c5e86cd8b5595e2930b77f684c4400f716b818589bc648d1dfccfda08eea9459d8ca66d17c97b587123c01e4f820e6b191cc35e09b95f920ccf02973528407c3e00b4561d9eee67356aaa3eee8b477b403f0e0ba69f6d5ae86866efbda66798a1c0170239eff31cc895ce1b4da58c6a48db9648c69a524c007328d2cbd664af9e66cfc1c04b0fb2caba15a781d7636d16a82daa10d0e9732134bd6064680ef5e0db25a867aec58a5b7b8d0572cffa975c86fdde772ea806b42fb5ae1c9a6b15d62ce223e8ba7131ab29ba3b27ded3a135c79c8fa3e811fb85b558b1ad6ee7d60899b0b8c603cdaaa0376629822cd78297efdd34a017c021a58bde7fae202944ffc518cf5f2f8006f72604a6e15b926f2fbc3aff22020bd15707f82cd0bb90bb01685a2799e5ea1ffa6064056f35a08c1f84d7dc142515947d68f178e583b2a09f6207cf86a8bd1fa270a99937fe505808cc64cc71756690732ff470a10b60700a80a5d1380ff10b480b2e55d04430202e710d5771ce5517553150c4a700207b567deb7026d867c69673c019331e26272c43770a2677b11771c2d27370cb0677a16319c1316f6471275f536ecc77fc4b66c71b37f444880af5781fbf77f37950acc870a1d1039b060607ba02d1418005d737cfbc747aa854f222457b6d0780021632018821fd184ec66000df5028cb41b86e2047e253d17714f86420f1fe60dfaf700a7703b4f8338ccb3011f33733dc846df6028af936611d73835e807116856f085847bb488aef284a2c610153340310589597876975057b630896352571ac5246f012e67482ce4222882f82aed7448f085387a445ddb90874104258c4645594800050060ff1fd36762b8890e013ae37707a9c07f30b47552c8105dd82d9be88cca463bf9d4881641897173880b188031e4698c682895a66aedf21da7e81a906175016118dc928e7aa08c1ca08ee99013bd18391f60035a010026708fe9c0871ec08b72c38712105c6d704cdb52072760389f4714b2e86f8e71503785848f08350ee990a3408043c884c7c87e7ab07c19c97f5c18816987354cb848caf357f9c74b65f35635742dac54299b417589a21af9618feac88a37a08cee788f38390232a948560533123003fc06071a5901ef680734203abb90070a882dd8023206787cc8e4800e8975588884c704925db8820ed83c15a88507c83c21197d53f9951b998030853bffed1411181776a9d82bb8120f310043298196c0f594ad08006b788f007602aef40d4e493bcd357fb9722a8b285ee2302d47a7101ec90ac4273f17c17f13538dabe00a04e48ccf289796a98bcba807dc703f298992524717a9f51a7c951f9b125f67d215ebf8711f80936cc8867c79545408721e1472dc63989ea294d67388c6789822049a5bc45218884f0c6198859998bce9245a788d6655969f692d774586f3428ec574175d9894a2222a28034873b09c6a35359ce64c21d093f30057e6799ebcf088998990f6709997c29ee5999c68e34f7c8837cf08576742311d2834d4599aa6b17f52b710d849949fe1577df00c43d99ec19907ef30931e4367f61088ff27a34fc8b82ecdd952c6f98eab88a1e6e5243c5994973228ee1853cde82a57953807e192e946416a70886fa223033a305a493079e4950baa9cf78996ae059f1e306c13da9cb453a2178a8d18198a95c9918b1871cd48805db97c8c461350c37d10a82d10a0766af72ce15817b3469ab5f68aa7a04514343c01da9d50883555e90a318aa339aaa3ee290e3ebaa365420a6d677c20197d23c97353287310666401d6855d03a52349349a047d3a50a527993cf8c53c803a30c1249a5a6a4a5c6a8ec1b08e7b900b5579846c7a8cd3388d18599871339ecd18899ada9b9b9aa9ebc90db9769e18d984385364756aa5da2299574a4097a7a4650a85aba9a7627953ffc9f43353e80a7d3a90cad21b1b05a9e528a98a62955444875f19917ae07107f85692189111188bc1faa94f799c0cca9eccb9ad5795aa90d88d59e97b44d87fec650a166aab9af384af97a1a1208d5a73a3f2f5146c3034bd8386c5668c007a917c20adce9a8cddfa97aa209129a99edabaa6fd8a9edbc98e1cdaa649e93a698aa544e1aede509fdefaa37f24399d1a2fc44a1005d59ffb132f1f16a68e12af1c4baa278bb2f399b2a979b10aab8915e08aba99a30f7b96f0a9a6f20997fe373b1739add25a26fa249d5d4637913a05cc819bde90231b15b143c1b22b1b9a11db7b37db9b7df5a59ef0271d8ab0cf82b59819293ef4279deaa0397bb52c690dff0740b46da95b86b9af0adab635abb26febb6710bb7c248b52a23039c999b5eebad7c1b9f7e0b2b18e9629f29b62e3bb0f01a341f0b79d59923a1aab5a72a30033bb5728bae52eb9895e95a1b5b8db362859a8a92aaeab8e8e9b9c5b3a73c47b8f899a301d6a88e0a073d78ac467bb4045a9e263bb74d8b4b4dcbb1a362bbb4eb092a23a0c51990f2fab2bc5baac4dbb07ddb93680328735b26380238670bb2c8ba18ddea683f85bb8fd23f97289c0829ba955b4304c72e169a736880949aa2105ef62fe78b2e94b52ea296997a68aab843ad26aa3b6af0bc62e1ba2e9767f2978baca1864fe9ac9c2bbf5e69814ce8a45cb9826009a8cd6aa73863a777eac0ffaf4a331369c075ca944c297cea65c11a8cc01beca406e84a1fd93cdc87750bec840b5c9336a4bac2a0043ad018af8105f8fabe47450f8f8354bcb56fd0ca95ced3763a5cc015889547289543c89cf25bc1ffb7761f298d5e633444834c3d20413107a597c7c445f37d1a33a83613a86b4795dce34307c5c359fcbfcefab3e19a17ac4b9d309cbf1e202611a067984ac49e0bc749dab0b3abbee67b0d6398bbdc3ac7de868811e35c071699f263c194fbb8b567119326b57ed9c574b8280690c7be5bbd3b72226c60bf2f8c056a7c3f3d7a3b6fdcadb465b7d8cb43fcf3a2bfb2bc649b9cb20bb318a3bdc96bc87279ae10fbb733db57beaa2a8aac89dd528fff40e31995dcba4e87c910d100afa00e0cc5c91f73ccab8acc8182ca93fbb0d99b2cb80cba43ea10ab3c7ce30ab4c62bbce8d0b5704564adac4f6daacb2e8a1bbce6cb993017cabc0d068acc6f8cb5db2cb748fbccceacb2daac945935709a29c7cf89cdb77cca9f6ba0d20cbf1cca27a2894a6513bd9c10b3e94c02f5c9cef6e9cec13bcb910ccd7a4bcf2e3b0d8793cf9d0ca7e04cb30ee7b8c2ec87012dbc659b296a40b493b02ce94ccbc6e5d0ca0cd1103aaf30dabbc39bca111d9ddc349e244ac661fbce230dba21cdbdffecb032edcc19f7b11af3baaaa8d033d0d40cd5d036f9a030edd38861bdbb7bd3c76bcbc44013faaccf3ffdd57e29d2d10cd6ff922c2b6cf075298dce2d2d4401f6d2183bd5c1c2b4f3ac9d571dce184a0187d3d5246dd11c6d9e1400d0d94cd67b6218e2a4494a2d194d4dcc2ccdb33c2bd5634b94563d1225abbb345dd158ed2497f7d798dbd738bb9efcccd95ffbd19fedd7ded22e2c94d44d70ced9d0d4330c859c1cc43ebbb18f8b9d7e2ca611a389c4d9cf904bd4c1a93c3240843d2db3f15b97da38daa12bd6a0bdd7a5d19d6ee403a9ad8a7420886faa85a23ab801dbd3a6f0c1d7dc981f692ed882c1439adcbc39310f08b4b22d9292e8da782ad0e8fad7c83dd45adbb145cd9f1873d86d22dd44363ce123409d19d970208b347862e1487d1bc681b46828e15066637d992224b8ff712c8db2a93cdc8b4ce28dcaf313dadf4cdadde8df7c712807fddc1011ddcbe487adfdb29e9c989fa645e8422d945bae445866cd28b532bd6e31be0eb9f30acc04e1cc593d20ed9e7d08df9eedbecb3d53a8addac3a08b390e60b12ddb3d0bc7cdcaa2f1f9b8953ac7db59778412e4bce994149eb3b4dc760beb9168dae3751b32ef2de657fbb442bb56e387d06bc002ac0d01c4bd98edbccfe8802d10b48fc483e66b3caf798be08add97ea30e536cdd800cb7d77fd9ee86d26f090e327cedb3467adb723ccc5cbdb3e25b4e5861c456ee48ac2da6be8e6970adbcdaad791abd1636bab7eea0a47cc3cbdaaea60d9c3adee71e9b87d0b658c6cf0000c291effc7c887b333e8a5f6e7bb89a61f9adef53cccfa59d26e014b24d3329aae87ac1de8af0dea71ce80a677803ff4957b79c3c47dc0268c4c893a925b2c92de8ec46bf77db7666b507c9b85d9a3dc675ce5b99777a3ebbd893794a9ce9143313d46e3c025e1549d99c29d3204dd06c8b1e6f67de477492843c4b754bed39509c4b2d984b25996df4d2f8d897cb71d99bb1dbe814c0a19f90908604512243711baee286aeefab89cf149dcb0e9cdeba60a3db6c93eea8666aebcf31d15b6843303df09c29cd88bbe59d0cd06622abefde04a7a8cb78a435040ff06481082d08009f9bb70736050483cbb2cf0f123db8b527ae5f0ce07be18a2861eb35b7e37861e01ff73f862eb08d467feceffde65fea6ec215e0c844e902dd7f36ddf25c4d2f473ef96cd9211b7a0a75604f224f10070bee7e5d4860cf0126cc86e82bff2002173eed086581fe98e14f86f0fd7bc8d1d344036e3e7dc77bfc63a6f07f820f7782f2779ff2b775ffae95bca7a9fe014c03cbb45e5610fd956bf8625aee88dec098bb486224f941210395dbe5cbfd8e8944fb6c6aef68db1269b9ff3893144a15fbe439ffaa46ffad08ffa2b1e5627c01a09fff5ecf0574788f2c22cf9f908391ed3500e4094915fef8bb4eedb6fb1192eda77e52608de342c0c6720bed4ecbef34e5727f82ffa1311fd5d020428a1654824762816d280826c06588d02e3f56020b0ff2d47d56ad0325a61566bd065402060a863e5986121e5ee5b620e1b90d0429edff73b3502058d403408687a5c0442720e682e2023232605c7f4a258b00e25393b093f41438d3c474b494f075331849630ff30f1a4c6aeb44ec4f660c31e201e2a3cf668bd846fd72002ee705f959d003d9252292d0e7b04167d1c1f510b668e9335514dc38bc4c7c1cdcfa18f944e969d93df655d2f7dc97a7de12fa13cc22248209edee513d82e5db404d22c50b3860ddcb66eb8bea11355909c448be59e0d71b220c4c0761e5f6129f00bdf1f122d3490e8a161594b8f144d555b984ddbc08817611aac786e534f73198dac4221b2653c57eee6f52983d268c93c62109c60ffd90164d13f39110a50c488a1d05096de8540e013e7cea06679fe54352aea4baa4d61216871d415ad0470f52d6b0b172fb3aa58272d90a9e340578c071d866544162de0b38d194756fb782f33bef36a812481e5d7095b0d64ccb29ce4e809b979063040fad6ea51c082ab116658739e8ca892213776ac00f7648aad581b8d009a24a60756e8e212b32f8eea7dc763c5bb737c40b215a92bccb8c7977b13c00744281220fb676259b617f7d69d3bad7a9d6b45ac8ada1d6094d04b576f4fb64fcd9b014bbd504e8de3ec9961055e36a062a4fca8fa8bb89cc01bacb0f296b3efa6b2de5b0f210c337c6caa7e98780897e1a2c202816d7661ea8b2ff6138d1e17ffda8822406086e1861b092090609b03c1b08baacd36436eb40773826dbc096962ec970a655cc0bd0d39d4b0bd0e8bd067be06bdf9cf962ba8738e8d2ea0e3a682297819698a188be36f3a37be40d38003eb688e8220e522a3bae09a29b21a17c84bf23df316942b4a2a099d72d00faf0cabb90042a3220d60fe032383371fa5f4330f52742d933a0d10e98cebb4eb548251af63600fe8e60ae038faf4d4aacf86c4884ed0270fed9056de705592945514582205a31028d319196ccb71a44fb533130ded1e80d11d7feeb4a48912cdb422811696cd2ecc04b5c3f4b3064f244d38ac0e808d4f247b03ab1b0bc49acdd05adfbd75108ef828d63946af2d2303e6ff841c530b460b58759219e290c799334c756886857964385c2abcac7498e4a4f8cec857751d0e190bc79237de8f73f5b81224368b2f0f365818ee47cf1606e001ecc69c225c5585a4a25f3da800f08d04d958a1e564b788c5d11e13bea5298bf7c45857854714eb14916d7d5a6aa84bb1b2a378feb3f1183474ace505333b5d35135ee642c08547d5f5bac635f49594ccaf8fd925b563d63e1a6957d1cd788a1993b890eaa8a70efc6fab817d81e833240ec68ad366615cee8d1bacc5443c165edc3c9b27e79253a3ed8610b61e7ac83bd754f69e67d6bf05073c64d47522bc2fe502d21c5fe17ea49c7329203f8d4901a3cb8e6e78bcab186917ce7537c3d2fff720360427594fddf9e61142428110a4c252dcf3ee7afdb2bc621e12900e4e4a406c5619a418a8830aba58f4d18d484c1fdb48785e7ed5e797577aeaf1b43b7f4cd224ce7bfaac2796faa4cd419d43df01a3512e09190f5783705fca64608b5b4d907ef5b31f68f0978f01b6a63b1adc1f6952659ae069ef7f233c5f4114189bf5fd896f070b81055707430a3aef578caa4c55fc62b00f76f07f560298f580b843139e301d11aa869318c8c2fd9c6486311c541333c689b764b077f2d8210e797834d660c7834114a2f0be83039970a5043c80153e9827c30a3a918d5034851332b81f0240691526cba1154bf845ef04205a5ed4e310c338c64698b1214cebff1b4adca8c639521003262bc7222501850bc4310a87c0c0028420bd3936b2871cbce225afd6453fe691883a31a20016b0c2064e425d1c53e41a5127941f71a4639094e22a3ca307842c801a3d80009f9657025672128f9edc1f144a561ca7fc314f088c86256592ca241aa4952e4c24c81849026282f21f2248e62d23a00376489297329189785e780452aed38bdabce128d9893dcf4573907e625f358752cb573e939f6e04df254e12508e88f36a9134843837624e5ffe47212eb8da06b308cf29c60d8bf104244c8c24cdc011b01ffa04d96ea0b89994d0499b9724a80823914c844aa197cb5bc0025a10020828643c1695282c261a5397d8148ca53c4446ffeb69cb55362a550030537a3c718c700c1596343c84aa026aa23a45e5a43a881f0182a54df15d8d04c49bcbbe72f69f5f12af8ee6e3c0682a5a2d8a8626a200f4e9337300d469464334d109403a49a1d427b20e4851bd4116a81758cf60928f30c3df9c8827a49df56800d5b0a3590392d67fb2e05b438cec4dbde2cc905dec1af66cdded441a99b82541a84d6de36941d9d7b849352a5a1aec008445815c36c06c725890bf004005c75a80996e05d68f78ba4e67fe0aae4622a367e94a8cb3dea6b4bd9a4f730bd54ff971c434992369405d1b87c112e73fc80410d820a5081da8f39896fda4143845de6d1ab394c42dee02919bdca4f82d120de854202469ff5afd2a400482f54c754bf45fd6662129b30d9b8d0c378c159c0d050ab8de136469deedc9e24422f8e145e5392e0c532485c58b2f2500450fbcd2f1570fc31753f70b8e9706c9b57472ad036871dbec394380289a5bb3acf04b34a46193ee8589ff36ecbf92beef193c9d4a3ceee6619a144444f689a968c1c70efb9e38c5d2edc42c592c601369a947449ef141087c8548c1585568d012087f8743c9c26e20777033cede8c4cba6d8c5ce6ea010f72c0ab55aeb2c9de156da7aaa3b20eecb9cabb8a9b96ff9be839c422065f068458c0b0b8b97506c62f3bd174802407ff5e57b56f0e281e52a50b4b6381cb7e1d30dd36e7980e87ae5c18802eb158740bfafff277094c980a22ad5c6807d316c0adedd2817151834234ba53a00e572e0d9068ba8d0a0cae7db1b2113de0d4d8a1d75b0ab39b596bed4e9f64cedd6e2f6f52988846b8da963e8e40b78d1dbf2347cf44d77b75f67aec6058d6f0afa4ae7615a2d3e86173e006770a5f040428526c11ad0ddd4501b645bdb84f0f78090ca7931da2ea695d7c3a56151f08b9c4c827562f394398eb0fe322c7a84bead4be0f220ba6493bc7fc9e7693a896cb5f133c317d1742d8c43debcc8733db4147a0399176c02fd3e988b3341390dfcb88a68a8ea58ab6d7a746544467394eba1368d70c4479425bb44986ac377313a426dbb7fe2d6f54989ab57cc3b90ace7ef6fe9e28ff57c15a5c1dc46b553d4357ec4c1f5991a5f763071efddb808b109f04a0ca518c41e12d83b132dd3e6d2e22b981b55e29f54e62ad78c33b57ff74f11c3270dbcc7380e69dbfcbcc83b36bfcfe57bb671be8f2e25389e1106b58848eaecd05ed95632625cd0f519d11a911d457a31b006fa870bd1b9ee8adbf85955926f70168cd2267aebcf89b7971cc3cbfef61a73d4029173d11e0e082d4909506de5c71c429ce6db28fdfe10412cbbdeffd72875359b30739c00fa8f1f4c2ccdfd02b3241ef83d6f06c0fb6d800d512f3f90c72ee817b70e765e2000dd8c4002660f3f605facc44f3f6a2dc528ed41c8a05a8a104848e9348cafca023dbf62fd19c8dd4a4ff4daaaa2d0bb6ed120ae5fd62c305e64ff0d80a19f06f29c0c4d93c8328fa6d510c8100c0233e5e6ae7348640ec0aa00e2d04c9cc043e4073c20f39fa0d0f1aadee068100306000bc2a00c68306f22c3e36e15a36a7743626d43ccd122c2e55f82788aa08774a68f532a4c302af05a30ec4ae60037e26687e866778a6d26a41fbf481cf9ac318f88966884218eecf08e970064680100b71040e911015b1f78c900bc24e27b2708ecca60796c71a7a2c05ea4eaf8664dd36d180820cc9926eb8d2508cc683d55a10f9de28d952031117660424e0105d3101b9c53fd020ef1400e7a0eab96e31078b4f0e162d1637c0064805115db10d62f115e1b01111704476ffd1fab0109212017404400436c9c1fe0d13696b76a804c84211ef32e9acc0911b3db1fd4c21858ee414b7a9123cae680c67d47c8e4b7e093b765197b011d24482c4706565a8cd1123ad1d7b017364c6061030dfe4ed927cd0fb24610166aa1ae8a8b210c3cbf46eb888847d726d570ec816a1e4bd54a8054500744aea418401c6122668f46549e400b6e47191a64c939ceb1177311237616fb6251cb5301c0db115b90000f2d107a1b0232a6f792029047a4912e604011e0c7c4cd09d2e4d2e7aadf6b8ad76d0cdc27af0fb564c2cbe0fe5a2eb9464a3110e20041681dc86454d7e8666d6e516f7a664140015d3b2e5783223770d925872c61a8e17a161e6bcffe038fe4a2789cd097c029320093c7ae0193b129554b22c8924c0222531c58acb0cf065144dbb68d0d9764cac26733161ab60960aae304090e84f070273a8648668564b1441c02f826933cecfd4446eb004a5b0be85cbce4dabae0f13412f66a083f340206c52ee20a9112190efa5a4823742c00530d01a65f3dcdea74426a70473c6de7c4d0e209306ed2d31cfaf3a1d53c0d80f12755004e889239de4f38aec0dbbec5f3a245022c50350123a8ac5d298430e54e3e7120c7ccc2c7e04450bb5312f71d35f3e00ed548003f092b4300001a8b12b472c7af8b0279faa2da2e19b94531800241888903a22533a39b01e805073f0d22edcf299304007ba93fe2eff313c0f871b38a3414aec06000671e60625616b24b4a4160b8017082460a62d84eaa403788c8f8eb301ba8be6b6e1030a60e63ea319819240d5b2f2d0d2d502544003b06274b4d8cee0ed6c4606cb80427d513219d34aa98ed498226a84f2434154f534262407e401d6ca3f1f450b81045326ee64e672808e4c1be552e49a491b179003bca0e66852254b6ad60201b63000f97ae0aa0c42a4b207f87ced39af74eacc4cd216f5242f53c0148d409ecd4cdf6ad7704021a4890d59e8707cee5abee74ebfcc1000941c9db0195fb2193032eda6af3f7baf3ff5f4092b61bc268f48bb0974d2672c10834024d433cc4c067ff532a9ee052295582d93324130320bffafefa0e9884e512dc3924416871883063c6f0e31a2d54981ec01210a140341fce8ccbd1669e6f85301a34f56857300aa91360941a984b2379de93646af465bebc59872427dd1319d13fdb09457598b5d6d8514a7f159e7e822efa2445831d20ce021f32d2c9be22963212988c45b5d8e1ea1a0d1ae52546bc05c7d141bbf270006350bd3210f42f654012e38ef62eaa64ad19c6fd11095f4ca0f35111359f315091d833b9df5140b168178cd534de5605dd2fa44f62529f2ee4ad5e61887251e3205dc8ec0fa732fa5ef1b1b490928513da8e25d77b6091d8c7a0a526e2acdd25e340e8c6a028e50e128b5066b30667955cb10f5674d15dc70169508969aa4ff35aa8070b5c2a71b866cbda6b686ea85251a946e27cb86c2e5574e2030c1253f51b4633d243c44005a5defaed288682761b6bef53ab114b6cc946c8f5018f2f265d3b66d45d773818f2061e29436354c79366dd77652ff8b12abcb5761d734d036d1d2e9ae5e0ed70a01186e540334175ffeea2e72cb70787776e4b46f031315bd8943efe2aee0351d8452abaeae57a5d3a880f44e736b6c39d7008ef0afba17e6bc971f55eb063ad774d3d04870606ed1477afc30433db05eed71cb6cb776950d3ce6023977361cefa07265945bf7c065f6932c776d6f0f6e2abf1341e172801b0c21700ee04af619684142a31345b1777bad5701b93783cb5783b9d77bfdffea7b3b7847cf174ca789b6ca8e7c170efcc035e2dc1742fba6c298cf3b9ea9b050183793af0ce4d328378950e71109845298ac717424292e638f3774c005086bc6648a7679e1c57ae757c8766c31b88337587b27a0f716308373eb036ce00304314f014360d357673b15104338fcfa8afc46d7f9a4218d855668f94bbbba570f0cb0897ff7572e892ef1eb021cd792783840c5859a78f2883b02059b5631474dd380248b2f78cebc587ba7f88ab198911f598acd6db8ba6f4fc6985347d5be2096836b248dd5760345e8008a0c60c93507edebfffaaf7d6b2d762d4790a19030b796170d7555a9221abcf2a176ee95b11448eb2d3df9ade704826cbd271f2aff198345152d4a402136195da0c1931d1994d3386c4499049d6f33e4e4253dd624f0d67b7f148ded76ccb2d3d504035ed98112be018e65f810bcf2238923f8b8e00061064f4b44016bcb4cc3a606b977944a539295597bbfa3991d8b8ccb3894ab599cb13958aaa3bbb2aa7632a7ba1ef9ac00ad0b78ef773c8574f746885dcd978a3325668789c4ce64a13011941424abb40a42d07a4b97dfe2d300cc8c6c86f084c58ff8ceb5509cd9a0ad350690726232f8d322474051cd57abab601c7a6e90b2746b3a3f03e006f0f89f7baa933b9633e86a15aab060572eefa6b6f2265000f8f062c18ca8b7a0d2f0906c79b583f16d634f927c9b9a45899a51b74415d9ff9ad13aa4d1f22ca775dabd7a9af7ae796691559ba5e2a8aff1a661b555b316c4f6741753f949812200f8a454b51ac418052ec1f20480695d966b4b79b5acb918e6629078619a2951b4adb54d7e4960b988072579e192a1e1fdf09a9349e793cf38d17e35586a3b975456b6d801f85a9555f9d35a8f018551d4839990b0c542041048250e74dd629b2ef3abbf662a89890ba64f52826d6001977009d11a99c8a652faadb423ce9e8149ac8c328c65e2998d47861f22a6fa4ac7766c582718d26e4cfdfe2a3f63b5b0cd32b897825aebf06bddc017789aa9b52ad9c2603117f817baf921f9acc8fe038943145ab88006afb8df3c7b788d41ad45109fd34074b1ff6e0284b2299f7a4321e10748786801944507529b412759e1173bdbe24979fb5caf95de307410935110bf1648a34f6f4da3ccce13abb931f6764eab3dc3c1e90aae551a597b8fc257c5c217b6e15af906d2e08c83d95c034baa9e7814cf3baf1d88787cc9f4821b329d733a5d7c4523cf720a3bcd152034459218e1d0cd6fbcbfabd5f3a26c73a394aaad71d07a0a053fa4349760b95909c931575fccfabb277c096b80c2b758c3151aa93f1ccb27523be50ab607a12321b3529658311ff5acb1eec6b8c0bef1bb06c417ce49bdd40d11397e1ba6a79c59daad87b327eff270d7425a6589d29721557397ab1dab8373adc5d22c38ab4897c530f8d133b42da5ffcb43735675a9c9bf305dcc852fc0387dbeadd91ed27c7707c0180d91bf4dddc6dfbc3f767720e9500c50b579d9d74ec19ad71caac7eca48e31d7053697585591a5ad45bbc977c2e7268591cdc36b96edb4f316e788a65257e8fec4337d75d5a72e4b7309da97d316a06ada013c3f1bcded4a7de2e7b01567f1d4cdd50f8311b44fc8a69d7461e7e4e15fc6c8b1ef5e2513e8380fdf6eb36c9d9ade1fd9e5c759013d7c7627a7ae7983a0931d4946760066d7d135bb4bbefb84f5194cea6d3b9e761fcaa0dbb93dce65be7bfe17191224ad3fa5677a9767b51a67c85ddfa2a20263f218f0f5c2fdd34c577e2f5cdea8803d85c1790234f79ec1c0e6ddb9c445ffa10308be4a7faf89e95bf192f2445605ebb64c3f388fba2b9e5b583110add8e8dd610eed700310f077c1b8aa0d163466b302fe9c4fe0d9b66f1d46c3bede5ededfc669ae7741b85118088d6aee010ce4517967cd31e0b71a815cd3311d744b4999ec123ec1209d013105db959ee92739d2d5718a719c10af231eddacd2d92f4fec63cd0d50fbae33c7a253586301deb7a5df7cf8deafb803600bf4cf9c82fdb3071ab3be4775120a5607d1171d4f88005afffa238ffb0124da3e588428bcaf8897010306f749fd8b25b901593b1e18f1268311086603c8a365386a220a4d279574321bca08a69948141ec3e28c3184808783d3176ce4680ddac9c4707b0502ff64f7e7d81992f2373ae2813074dcf5f4f931005e51202e112c128408402e1c4c4e322a2629c88d910dd89d109dfce585aa15eafd8d05bc8d121ab071c03a48488cd4da9acce0ea66f056f882005f22fa161403e46e245768911e4e453dc74d495f698a3930306f6e8399c1bebc7cacb5bdc5cd1db5a5a4f2ddb417b291f5b93b275e352e1d3c0ab848521e580ab3c6298e1c502454b532758ac49c720cfd3c00d0ebdb0a5ac96e21cb35f157b06152381a3b96711984017f027c9c860113342a8110a922e3c285492d61c4947101c1159a70e3dcc0517782e02b22ecdcb503844647a13456a0b05c940f92807e94005e12c1295ea03b0659a1529894d4aa37ff10254a8455d122c6641a8979880bb22bdc06768119739b050c9762d1ea5139b828c553a010681ac259e6b04e9d3c1d4624f720d03ab41d8c2645daeed5a7780e8a3cb5778f510faa562b3d63a9d5f35040086a2e14253b764ca47e2cf31a50b1adaeb71ce5e2a51b77c9dde07a5b5c33f45b01566a4fea8de2eb85a6366e9b1e1711076b5e03ccae3ef4a473845d9b099e91a6146de1823d11a55d54f5f76fbdfa0bab43f951a5491e4279fe91d2d10143b685f34a0642ecc69b6fcbd5d511714411e68b21df95749b5f2c01e65c84843d841311b0882452667bc0134e1edbe59702047ba073cc18498de70e1c25c606587dcfe820400ffcc8c79e3016dc77ffd641215c17db7ffe35832438b8bcc2c02c16e9528b46335c08921d0e26800011af1d22e184a56433e14acc0d9888865608780d1e2a5420511dec8009c07e7da038998a2c7e07e69b47403400885a0278c373372e22c23ef15d05e4a0eb59c3df905fc9081680f91d39065a6c6c90a05b52e6724c31bf85541c1673c82047689d16d045532f90d01e440858b04000a9619142872ed8a9829c3162d38eaf68990414afad3644e24347b9b3531b44e2e9868d514d41c002fbb8a0438fcf2a811c7ea6784588af4722f0ad1a7bbcea87a59e1e0881a617e9f529a8064225e0acfa0d9a2aaa4e513905921a2cb0c0a27675d8095ebb1eb3e79bc6061bca9f085036ff148c06bcf8adafe018f35ab3f4403518993a567bed8f1d2a74076c12831beeb8c736a59ca799b2c56ebb1bc10c152067bce6e3c02365d1c9957760f31990fe62cb500b74826020c131d6610378e47c6012077c4ea689787246cca2c9e3f4146e66d8d24a80a13b5aeb8fcd97806cf290231bb91050b38dd85b1bebf2e62e31e880eada64798c9d57170578c080512d3421b42c2704f66f0579c4c4b7d147873935a62496686b2a9b51cd2ac9014a744e8c5ca7e675b51d8b4d1fd9836c8b78672168ad59daad3d0cd6a670b7cc242c2076f91c03a97e88b75cf62233a19651f3c2c52c0314de9213a6dbb938e379b261194a99bf20f90d02de404ed5bf621eff9ec19c3fe379693d782c4c08a0841c4fe9259f025aeb5ad8e04094b06304b3dcc398e3420b3284e263c1bd49a96d9b00ac02002390809f300034583c473f0aabc12bd202c03c8d27820d944cf4e47010ca99258311a45cd6f684c01f75cf50f0015fe83a6781d710a97ce7535dfa58211ead794624037c922d62a738103108252cd8c0616015821ef82b5506bb084a3e61846c442419045c89ac14f0b4533de423cd9be0ff8a91341ba0eb790f6c8005a5768484296d832c324f33ea20aa8c89ce73230c1fd942b1893d906b45a9f3d61ccb42a9f46de14b331c602d66811107ceef096758c14c20c002f7f460871154221101d52e6c2c515ab36ac01ea091ff263fe96a71bd70dc78c2f89d5750265c14ab9c381e60301891b1597c5b94e858b2231db1315a5b49921c0651c75b66a68c301c03363ae58515d4d08ff10b64221ce1805455442787844f64a8a42a2516e101f6e35d17b4c51c6a38ab68005b834794b72b337e335c451bcfe36cb0a4a7bdc844a7dc55b03e3046348210073a8265a2084508fce82100d2c1e5e54624235ef6924a7e5b0b2d84798b509df1361b70400ffb76980f11232f5f92802f32d285153142304c28905c087117a3c94f4eed7327d47068a0831d21838570271cb148b53fb01463ac7402014ab023d0d5b33ef774542d51c74f89a5148f9c08e8065c044c83f69153bb62106890b91387ff3e3434c1e05d646c912a2d5020a3b3a202ab5629073fcc2eac21e5c59e7665caa44c112d31ba9e7846093198bee98333c54409aa45cf9cb61185dc7ae34fd506aea17e29172f386a4169a854a560894e573d2682062000436a6caa560c971f85e027e630a1554b081008a46617788d55ac158b5379ca0a1470c031a84e43275ccd68b8cefd802a61c32b1594b0d3bdae107d1734db6bcae5165feea6b07b2ceacb82a380d7e82d031250d772b7ea11c966818015e9216623a021317d952720156d5897b75872aaf568e040edc41ec0a176c6f5b9f0ace94cee8a9a1bb991af07d9675ffd7a5ba2fa12023b7812524780b3e048c1546d222e0165012beb0a47ffb45a181e4d80e335944c8320c7e2aedc3662da16c129bc183eed1c14e606efb8f6b550a8e93c673b1ff56a605b0a81d896e628163da8b824ba19e96f510506fee2d8bfc54dcb15c4d905461e36b0a8baea5ac010e06202ae5e30366568c5ca26ed8277b2e29213d31c6896228ce1696fdda05ce1690f1ec83607627eaf4e7dcadb39b9585c0ff1e9175ea5c7762d7786393e68a8a86497134c33764575df421b17582f1081ba67c89673b1d0012d782ba2a075329b9e4c30b97d07958c63601cda5192f35e30bd228e5698695bdbafe2f314d78321c976b909f6e1f7912e28ec9ca5d4c0b704c2102bed739f7b660b59fb2ed097e62209c6f4879a6e6868e6ff42e8768b9d567701923c4c8b6b1b52640065d581449b4ec254e01382127e4cafa2dcb699fbe92d4ec818a0355635ab730ca5e48aea015072998e83acc4649ad7d0d8dc6a1500b71f3f51d1d8fa1623bfcf0ac10daef5df5d8e1714f4f1bd58ea4b5b30c472a86d933ed48d21dcea56501675b3471c57d69902b4f34710a0930507b48a441122d4f0001079f1cbb9fa8909d62ea5efed2e12bc31370bba2ecc27922e15c5b5cdc44c7a902313031b43497063b7c355a13a0e8d408ab1f5b857860e2dc859ce34449509fccbe30640bd0505b67aaa445ee707f4b01868f243caab2b6ab0f2c2e52ff76694adb848f282e7947227697abc3cad1f3e02e8553863ff8a5fd34e791c3d7595de6919e0de74a77f47ddfc95babb8b9a816a3803394485550194f3bfb820c731959fe4e611fc841850123f9d387693d9be3f2a6b38da32f264cc3748319d3fe187369d96deb916df5d6a9bb72a2652c46bb229b7796a7f1222ac4508caa95bdb1900a0c1fc3d537ab88860de00500d164100ecf9cf5b100fa45ffb04831ff3e675d277e33dbdead5dabe557af99a3a98bd7b833e0c27de5e944417fc1b20c0e659ceba37faf7be31085a7ce35fc4b91d88fb44140bf8c1aaa90a4de44531f4d097405a31304c040087e49484daa1deb15d6088bc19f9f18f1bf4ca67e41f4a79d0044e207348cb0fd19ed8ec9d83894c674c0a597058ff7c6d43081e5e8818c381ec97612ddebb61442f158705854932b980c62d13ae54860e55cc65d9c81de04a4870a009c81d084a61fb50a1085aa1d9d800620c91690d9c8325c001a0c08ea4a0a79949fcadcd8b1004eff5c10c8e17d3d55888001f95615c520913fce44bfffddf070ad17755a0118840120440c1d8090ad4160a60406f4161222a6200da9a03cc589f4c2108cace16aa5e4c69d003459bf44d1b73fc5cfbb5121370d51a66c77f0cde2cb1a1e2099922c221ffb501e3f5d1ffe9d93179892ad09aec1061e5394913c6c54b48142102062350cffea81e8d45e2aa4052238e14f8a5de228ac33e2d5b88cdd51474220991d9041e9a288e42c399e2ff294a21bbfddefeb9dc72f9df7079c100dc1a1fd9d967d8d942715c5d7cca9ce8100e00c9782c000dfc4325fc40092cc0b9d0dae0bc40cfcca13f1623b3a555eb1dcc59a15a59d5dd7a5dc034629b359a613cfc93f96cc3a9e19a1b36e261852373cda1409d407f25df491c083065518318600bb457b5f8dcb6f89c08e0805680cd3ee88c8115634d16236ac90e065262af44da255ec926ea8327aad7354ae4d0448cf9f09ec4a54cad29c81baa1dd8e5200dcdc20c54c360fd9fe355defb84dc3b4e9516b41755ecc31181e5584242270c0e40d6e43fce214e5a2033aa6222f2e49e74a3260265893da45c64c2f9b09c28f25e45e2933a7ec01fad625b36c9ff0ae85754eed12ac9c255f24d2d3a890f8a5d9b7c9601161218f440d81d120490a5cfcd444dacc54d2ade78f99f39599e327e5326ba65b4fde3b7b4e1f4c05eb4d09e50be5f5e7acbccdc1f275c66ef9942aa81e32e045f41ae452119967ff54d2fc9994091a4cb280e8471511c1c4e37e80418682172504b65ce0475105468b6a176a2228d11a6f7e15c6a9612d89197b9dccf26c626359ed86c12657620045292416e2647cfbce513fe665b06e70e70646216e70c2cde621d1f56221651645e74b6d74e7c8663514b4ee0ca82e9c68342e83f82a061aa0b7d5aa0e909a378ce039f3c157a1e8a7a62852280da28c227b82965d31197cd85974eeed708cc84ffd6a94b00aea693f8e7ba80a4f055519b805d63b417d169e675ea0663e0ca6356878302a461ae2693dc2706b66522b6417dbe5d114820c17ddec6ac1119d2a6ee95a87cfa9eef18a76fba1c946a6070ed04cbb44c8540c02b969b9aa6e83bda8b0714c0001828338dd78f46a7900ee9c4c91a774a57925e289302eae9096a8686c99452296c7ee85d2219f5c98899811b4085265a3ae98aaa95c1ac4ce59124d8dd0e43598415b5d94225880eae8b13aa1b95e8d39cf65ecfddc47536e8673a6276b6618b14e4f6056aa04e0dc019eaa13242dec9260b3acc9ae1d3a342aa9ff9596fceea927a8133e90632756a0558d740e582ff7da6610a97d8ed2545c107ffaba6ea6375c310149283aec083aacb7ed24dda3de992d62a2b9ed25c4650ae32c8123c82b5296a19a60d5fb2612a561db14299268569b2c2a923f60e8da21fb4d628e31112612503e0784176984465de0485de29aa2a865afe636bb61dada62b789eab257ee09ebca63d4c8bbc62e9882e04fe59a424d62214fea3aca2abb9aa1526612a9fb14b642e8302aa2941e997ab4e87ae4900335407853e94184067b8b9aa77fe19cbfea9cbd24eb229e267582a4a7d2c73c8137c9c86fbe1e557ddd2a941a2975ee468a11e982ead0d0e505e04978e49403b021a30cd5054cec20e44e56418114a22689f2e46e189abdd52e152862dce656c06ea24a1bac27dd60eff5478218951edbc6649b0c8c656d011beaaab03daa069c2216076c19cd1c20d515218609c1fad400e7a43a924dd4085ab4d3097b73686ba04a952865c505d5d79d8aa2a75d76956aa8a0290b3fe4609ae470998860a0e9c2af4cbe2ea69f05aa83381ea6226a3c6f56b2a722a916a4a0154443b8a4167866be70260e756e8728102ea0ecff47ac83e48e775069e8016245a65ec0852184f88472586673645637b50c50ff0ae585917531c515f76277ec9d00ee2029da1eb609ae938f652c6f1cd82318661ae9ab40697c36ec19344a875f449b70e6fb1f16ddf329a9629a385005040cd2e734edbfbfac30a7284b44080ac688bd64ae84ac1a104e86772bac9fff9f5afff569d391dd56352eb621e4768a6a934e12c85ee069a7ae6843670ef855b016b9d06632c059713e4b8aeec4aee06f31de126416c41020f7c7013c8555e8a4041a8ee52e66f0ea285d3cac2d234e9a0fed8b811101124a640650311ac40174427ceeaa7f63e62a4aa5b10cb69b656a73906cb11237103fd4aec2edaa5d46725ddee1343420f9440fceea20c5897acc8a9e6deaf900d508b86044de664f729a969da9c0d3b090b1fd1aa61d99d76c328dfe9a9cd711da332e9b2ead1f52ce4526ad22a4fe91d2b78725947403160c8938e5031251822dfc54465d6c407aa630be72cd7b92c201d33ba1c1b8aaacae83ee6a60ccddf74ebb88ab28c811cff1da732aab9d9d5346f1f771ff7692c2d13e650d65d2ef7803df2f21317044d4cefd439e62cc4a8132a331fb3a2ad829d19a4711c184501dcc4624cb33620da146ee776cee55c7e73cb8233056732b20e6e01be9fa1b81718728d3e11adfe06e07419b38e22d1422bedab89e98154aa23a6e90c51a55d00210bf4f3284fec108383402768364762983431425358428f2f418a33808555ae9af39855e3d556b4b4de2c49a20b430d960de7284865e80bcbe5603d22fa4517e01c46dd2a0624ab5bb9c4f455efa6a4fe294ddbb4f7adec1485a73c97ebc83dde04e2c008ed7225a8332b4bd7e60a70fe02135911e0417fd30b2f4f33a3715d370c95a83429a774ff30a7a5565ff59ef64c810c2a3dd7b45d673062d3b2f89293d775e1b4e8b222c36922fc4e346daf50bbcfe43e6fda61032891d58531f6ec4221128132641e5a0109ad89126d8422a34d12b668cec84c7f75dfeeabfec5e5225aeb14f58268f4b465df0509b0325a06d36e2c52656580d3a64577e1346ec7dcf5b6a9a62247b76aed1ac276ca683512197734f54a32c3f26dbfeec696d2528bb13c3b2ba246b4bb0ef71bad5ba7542e51cbb5607a932c33e969eb02791a27c368ea6b0cad5236f0e281a64b578f367973697735d30e1169ba89926a97e43a88c185cf4ebf157d26ac45655c9f3d89a9dab76ddf77948cc77ee6c7167c4218b0f423b6336ddb5affc542840c974469fa768c2b3682bf2e4995d2d3ba818253d98819dc5aaf2785ff0d7c2f627c7f66c126df73e3f687c79cce1651219d2ede0a36dd061761a36557e9f48163f9d7daa769c754a0d2d4711db28f5bc047c1c5efe0ab19cb77c1165832ca78872fa36eafdb9dd9e95fe3c4d671370ddd73610b194cd0782c67b9e08213193775e0e61bfa59406954763ae3c5657b401ae05a2d761d8e92756737b7782bf9b9fa67c8c980c741c0654ef31b6f3712f52c006b6478d37382dbeaa325368ee337a18b567393469823aea36f605777b49b7ff5048b84da9a805fb4c0657aba8917b79e1774807e749f775726351ab2ffeda4362ded36d91220fa39878eff4337ba3a51138699abfc2c36b7dffa4177b920bf83649eaaa77febe461e4991bab571fb4aea82278e3b538d3ae8d73566849bbac2bba8231960766a4ca787b057bf8bf9f26a5df67cf60ee23dfd43e1f516f06acccae7bbccf2a599d70dcf531942a645ed35db46bc0b48bf9648615b1f2e63cdfa049cd8d385484538fe3fe1d334ef73af86503cd3ef2540762c33be5ca78677dfeb9098ceedb50bcdfceb2868a29bd63ee5cac92235045c77357e83e7a3da37a299c7090c9307223ad0459a0b0dd4e9933c653e1a2ad45aebcbf72aa332da69cd372bf00aa23b8b3ab6860ba135fbf0b0249cba12472be8b16d7d63cbbebfce8b6fc44081027b3b973affdff8566c36a6ddeb3e9e277011f640bf2d71f38cb4c40d1f47d7d2b39c33ba9da3ffb5c38c14e2b406cc2ef55d005b2d5baad6ffb002277e81f430da745187b3e921b4d67200338c8ed2e3678e4eafa9fa76bb86e808efe23592975829b8039b19e798371c75afee59f750468be709bf49ba1ec428bfcddcb22486fd27f7e0ac01a8db1dc8e96bb89dfd0847f3a86d3ac7a789953cb2f691c0aeef8abd5f3eebd63dff7871facd5bd65deb69b273900fd3cc54e1fbfdc7fbc29a5a8cb483e105432a6d7ca31036c56abd990b1346e00c30a40c29c669cc9db71f24d1aa6620604e290408c032fc0fd86bf3f1fb0c97ebfd7e37bfe647964b061e8032bccab1a5bff39c3db1899c373988b9c1c146ab8c4e4d0b45c1070112839103db0c8dc2c081c4462c4232c6ab3512b9891d8718992586d3422b49be533f9428b726ceb3d966bf55506761b596ca4eb13935bb9b5431b7180b82d8e647dc4968cb43c35d5d2e8fc0c1d2d45d762a968a194623e2cccfe451d419270d9c9450bd79f380f2a35037644973e6408edcd50224dcf0741fc86b88096655a9c2552d008e216301a38658fc8953b57e04b8a4e02408d2295529e039a06473e5c66e3c51822f56af19a956d441467493a3a5c15a75152a44d9932dda650a3159d5515d543926a599e64528604b3220b98a47074ca7a8167ce5ccb97ed64a6a287338cb39d26689ab506ff1022cfa558575d83e837b036c2721372152cacf0ae804930122e5c71a858816606bca0a9eda8336b52b5bc007c93a4384837a84d559b40c4a7b6a2dc158aa775eee83d14bd415cb16311a224420b7396a09957e02bc5afa8da6a08ced0e0d108f285d2bcb8c511ddb471e39754e815dd6d180f57b90d3c00d010f8967453d640389ae9e7a84597ce650f1e305fab0c103e33f2b11b1e71a0c62ba3d468e2172508fc0ea26c7083402f818ebace06f766538a39a2b659c48d5888c2ed39dceaa9ce857ab470c0ba5ace4089b911b41883c0af50c1e83cf524c10c39f61e18c334f838998f06983ac04f9f0a6c926d2fbaa0c8100e1668f9070bff94e84e97e8ff081b62ba2a018225c98d8c72c1ba0e05b46605690ac4ee0d9da47946cc95c8eb9209258082234637842843cb12cd7b319c090da8f18517d46b0fa51d5908a147fb820cb2460616cd4f8eaa682a4e49dc0022c63f0776d0cbc0e9a853a38d9f040c0aac38581c691b0767f127972a145175194524fc4a90cfae61913c1769d961c5b93278ae4a170608a68ac9d4546ac6fe245369d04d2c6889be43014454d1d38efc66971185814230225c5cf3cc3a58cd624350612b048cfd7a2396445a61c4a29030eb90955759beda20a40d844895275f1d20350d2684bda2cda766c46ad162254936610fce89a050019c752b61fc6c12353d09246465b922e0e4a8dd70ffe17c534022721db5dc39cd85f540a9c25a81d45bc17a77086fd9e45766346e4976844c4f8ea5666c9648a4381175218d950dcd402f094165224084f9d811054889f54b4c097e9371c6013fd99876b979cdc4ec5b90b56ed9648a4a9e085c73e7f5c8059f35144bc5299ed175bb7fc3d3526512696ad09b3fd3d8411748ab65c43831478b6769949a76a9b51ca49e7ad127f5795332e196089bde51cb7092a2ccdfc8cd9146302ba29e71ba3859395fcb061d1a405c6fc40c7e2a727568417e1ef78fbb62813d64daad68724ac219f9481b9202a5c01419cc89a0591f47819c218abbb67063ebc52075cc8230af4a7b74a308c7888b0dd2918a6958bdb71a23ffbed10f52bb3a957caae3e2ad4a5f27f4b48bbf3afeb3abcf334733c95ddcf324cf1520013249812518f7b01c3cab6bd3eb5a188c6724fc18807d141ac30451f386eca4ae15aaebc39d9a800493e9e1454a329f095512aeaf6db056a953610c6b7316f524a37eef3920080ec882fc986281108bda251e2839fecd705a67b920574a081fb49d50866fc840893c468825527189f59a6209ad68440096868bfbb18d017598c04b282002c96b00297a6028e8d1497a44accd00d733c2f52caa260483e21826b2c5a65449762fe4e39e94338d97051229f82b090d6b240fa405e80e9520630acc88013312c0920e73490345e103341aa801015804091a35c7071c6eff4f70214b1d69e31456eee14eba326443ce87272dca32915df42245dc16a04070211c8bebc00020d00304cc638d8d1380260fc0494b848191e433501d6fd71b833ce00c5dd0d3f05419c1582283227fc45a373f688f6fa6ec905c442412b5c91ecb908f7cec816403cc78020170a3071040261b97193dec91e00c2d200109eed2cbf458f3040878c04103b04d321c91a10e99da1ec7a2b56041d4a2e7ace853d0864e3c162d959028809f307404360c94040b93a70210d0a5cb74e913ac61e0289849b9909e21a19759949fa4294c9c16f3043f7de736bb90506cdae8a17534cd2392cacd382ab58672746a3a1ff954021a753d419daae82cf5a8fd50a2265cffb51c4251a90633607361f3c4c8005cea892e350e883438051401bad080f2721f76fc6940117a50b1da11a94005ec5eab9950c28a95af430d2c6191ea5777eeb5ae4bd52be9060bd46212d18e37c02c64e7888ac50ab6b27364a8a52e5bd8cc16566ba61568a81e415785a1f557dc60ab27f0a9cf9956908789eb644431a18133f2f602bd4d9cc2588001146880b8cad380a046b95c6ef2464884122573116540852d2fb79f54565a921549329a51b97b50dc9a560848e1b2288539e42d05f0294c97be34ad6e7d9613759bddf0a2d4bef71d2377f3ab5ff42a0ebffccd217d938bc6fdfa57261d00b076153c5f72311881068e8065b8d1569712f39831dd24ff3f1d6c600eeff6bf1f3ef08309dc61118398c4f54d707d37bce2129763926668af4b773080105c18c3fb1c6fa3b67b62132377c43d0e718b81ccb01f1bb7c83c0e7281e9bb64261339021708810b7ae0de2e41600136a6ad8e598c642e0fb9cb5f3e7298c1ace22697795967d66fd360dade1dac349ffa2c8196736ce6319399ce7666e29d855c673d2fd1c0083e80082e4c612bcf763e6e7dde017e20d069ce39c90bee7390b71ce9f2527acf92b6f49f27c99ab5ee7200c8ac31a2478103c7c6cf17938674aaf19c675457b19679f473a6c5eb6a5aeb79c0c5f5015bdc6b650ce800990b60cb8d811d14911210d60c91b5994fa3ea57371bbce48d75b459ff1d576a978bd90246ef93af8ccc2a238050a0fe01269f36ea16a07403a5b41ca896dbcc8862afda2809b03c955c810458101197a6ae25eaedee7cbf1b5ac97e3753dbede3f496c20207a7e46f157e812beb60cdff38037be7b30011d040dce376cd5c156483f159c5206cb8cc6455b1e377e8fb80eba137927702ef94c7bbd2e6b63776fbcde779e777ba4da52b359cdbc394e447043fc7c0cf812d681dd838adc2544395695cf1832f00a60fcba44c4500506baa610048823137ccb075189b96af840d2c094e00011a8f9dec6527ecd9c96e867a5ec6ed8905ead94380f69f5e46c212e6e9da85d958b0efd5ee211fac67df8ef6d1de74f05b03fbd8016bf6ff0937bed36c75693b915ef6001097142288723e8f09c465ca200086edaae4aa6941d2c325e487973b60a95ef7c31fdeb3e403a828bd2dfb627e3ee754b7fd5c73afd7d713f5a0bfeffbd7e7d1820110fff3a0ffec478f6a41c8f69ef7ceef6be06902d085c544d06f3ef47c6092616087c0fbc50436f5113cfea05ff7b8c50d3ad0d50f748a031dfdefc7f5c2e56fc9f9cb9fb8bfbdffc1f51ffffd2b3ce1bda5bf00ec3f841b3f66b100f73bc0f223bfe861c0062cb2058ca60a6a01cebaadcaa3241c28011cc0b287e9810dec011cd8be4dc23f14e0a1d52bbebedabde3d3abca9baba19bbaf05b80108841f08bbdee83c1f093c12b8bc11dfc3e1df0be1dffd4011df4c1effbc11ca4388aa3411a3cc2a97bc180623fde73c126bcc10080c1dbb34216b4c2ba62422c9c9a855a2821b2c250c33ea843a60ed4818a0bc134dca406e281128841332c3a22fc3e1d94c13acc410b2bba1e68b82004b6a15b421c00b64503c43e1cc41018c4eeabc33e3444193cc34434c22204421e94444794c4219cc3478cc4201cc24df4c138ecc04b644439ecc41a234538ec443c34453eecc305f881566c431a804535944535f481655aa6fa58434daa0f580c851280451f78c55a14465b9c459922c66244466552c6650c416074c61cd8c5683c4669e445687cc66b14c65fe40160f4c55774c51fe0010cccc056044772cc406b84c66e1aecc61c00c76474c77784c77894c779a4c77ab4c77bc4c77b0c02003b, 0xffd8ffe000104a46494600010100000100010000fffe003e43524541544f523a2067642d6a7065672076312e3020287573696e6720494a47204a50454720763632292c2064656661756c74207175616c6974790affdb004300080606070605080707070909080a0c140d0c0b0b0c1912130f141d1a1f1e1d1a1c1c20242e2720222c231c1c2837292c30313434341f27393d38323c2e333432ffdb0043010909090c0b0c180d0d1832211c213232323232323232323232323232323232323232323232323232323232323232323232323232323232323232323232323232ffc00011080064005003012200021101031101ffc4001f0000010501010101010100000000000000000102030405060708090a0bffc400b5100002010303020403050504040000017d01020300041105122131410613516107227114328191a1082342b1c11552d1f02433627282090a161718191a25262728292a3435363738393a434445464748494a535455565758595a636465666768696a737475767778797a838485868788898a92939495969798999aa2a3a4a5a6a7a8a9aab2b3b4b5b6b7b8b9bac2c3c4c5c6c7c8c9cad2d3d4d5d6d7d8d9dae1e2e3e4e5e6e7e8e9eaf1f2f3f4f5f6f7f8f9faffc4001f0100030101010101010101010000000000000102030405060708090a0bffc400b51100020102040403040705040400010277000102031104052131061241510761711322328108144291a1b1c109233352f0156272d10a162434e125f11718191a262728292a35363738393a434445464748494a535455565758595a636465666768696a737475767778797a82838485868788898a92939495969798999aa2a3a4a5a6a7a8a9aab2b3b4b5b6b7b8b9bac2c3c4c5c6c7c8c9cad2d3d4d5d6d7d8d9dae2e3e4e5e6e7e8e9eaf2f3f4f5f6f7f8f9faffda000c03010002110311003f00f5ed42686dc4b3dc390b129666c64a800927f4aad35f5b40c16697cac8c869385278e371e33cf4eb547c60677b510c31c8c1e52d26c5072aa09c7e7839f6f7ae627d1fce6864b88f7cb92858de06320c4982c02fcbf7500e98dfd4edc1f2dd9c9a3d0a718d9393d3c95ffc8ecd756b3697c98e72ee0646c39040c03f374c8c8e33919f7155753f14693a3bc6351bf16ed28ca07dd92063d3f0accb5b1d3b4dd21556dc9bb126f120712b07dc5530ea9c6720631d1981ce4e703548357d4fc4d677ba4b4f64f05a4aa970f0865573d01c82304752071cfa735149cacde82a892bf2fe27631f88f4994968f518e409035c9292eefdda9c33f07180411f85326f126911de59da9bd433deaab5b206ff005a18e148faf6f5af38b1d2ee96c0be9f10b4034b9f4bb8b7d498a3a6e66767560bf30cb1ec074039e9a92e9b7b1cfa70d3e48added56de296ed2e183cd044982af080518939232dd001c75aae48ecd99f34b7b1d4c3e34f0dccdb535589d8217c0e7e5552c4e3d8027f0a4bff18f8774a8e06bbbe58d2e22f3a01e5b31743d1b0a0e01e304e33cfa1c604f6924b2f89e75b9897fb5eda28a156986e52b118cf99e8371f7e9daa94365abe92c92e8f3d8c934b630db5c25c3b6d468900dc854e483c9c1c73cf7c54f240abc8ea3fb7f463ab7f66adc29bf55c988464363af2718f7c67356754792dce9d2c6ed1b7f685a2ee56c1dad322b0e3d54907d4135c04d61aa2ea92f8962d39a698ea01a3450c2e1ed4662da220b8e54e49dde8718e6bd03578db1a6a02c40d4ad0b0519c6274edfce92a6a35236fe994e6e507734b5f9d63b595fe7d987384c64fca7819e3ffd55930de4926028bddaa7f88c5cfd6aff00880bb5b31538508e48c91bb82307a7af6348ba33c4fc5d2ede78f2cfe7f7aa649b93b15169455cac55dfe7297419b01583c68dd381f29e71d79e33d2828d1c423f2a77dbc0ddb3818c7182062afa5bcc1b05a30b9ea14e71efcf4a4934e9d89097a8a339da6dc9e3fefaa6a2c9e64654ceb1c5309239c26c7df27ca768c73fc5e99c543f69612a27d92e86dfbc3cd8c7fecffcaaedde9f3416b70f24b14b1f967746d111b863901b771f5aa890dcc76c9847322c4a641904bb11c80464718e78e9d3349e9d0a8fbdd46c4644ca2d95db6eda4b34c9c60ffd74f5c7d691d567b644682f0320f9499f04fd487feb9e47bd48aec194b24ae1c6f55ee067f31818cfe00649a689646bb441091953c673b795cfa64727b0e47bd4b65288fb78123c1db3704b6d7998f5c7fb4723a71d3f5ab9aab6d6d2bafcda95b8c9ea06e07fa544b1b01c727a9cf4fa54fa998d2e7478dd1009750851727a1077f1ebf771f9d5d3f8d19d4f8596358e6060480be53e723b6dad465cb1e9f9e2b175f9d442555e374db2a3156c956039fc460fe54c875d4b9d5191448008d49551bb39cfcdc74182a7381ee69f324da60e2dc5346ded05cf73f5a52142f3c6067ad5482fc977f32d65508486c0de4138c74ea4839e338fcf191ae5ddc5f15b5b390c701037cac9c64738c75e3068e74b7128393b2347549e3fecbbb78de362b112769edcfe5d08fc0d633cb05ddc35ac5aa41e68e5ad9194c8a30386193c753caf71db20a5be8f6f35acf15b5dbcb2bc7b09915c267a018181c6eef923838ace7d254f89d6d0c63741e65fc6eb182ace6400f21810433723701f29c83922a6ea7d0b5785d1ae6c24919bcc9cb444f42bc81cf009e7041c1cf3c900807155ae2d99ef522338dcd13387c7dd3b86075c81c8efd81c823356a6ba943852d0a907e605173c75ce64ff00391d698ccc2eda7f290ca5362fcabd3aff00cf4fcea2c8ae67b9620b77493cc79cb1c103248efd7193cf1fa9e9d299af8637fe1a0b9e7588b3ee02487fa5321bd9771dfb31d3829c7fe44a5f123c91ddf86cc782ff00db70023a6415707f424fe15ad2f8d1954778967c4d11371184da10338618c0c123ff00af5cfcbae5b5d218e451725b2536c8cc0f1d3b8e8071f5ee4d6a78d157edd6e4f2c05c11f92d6259c71c5771be0e0050091ec01fe559cdda4d970d62933774c55bf8bcf16f108c36141e5c91f30ec00e4f4fe54f8eea580f93e7461010a803460b018e0f385ee075e8391ce70ad6792df4afb296d81e7224c1fe1d9ffeaff26b7ec6dacd2d84b22872413b8f451fe7f3ac79ddec6fecad1e662adc9b382dd3c8f22e3cf822d8194161bd5491c90402cdc0e704f426b461483ce9a754da99424718624e77903a11ebc138e7b639e9d614fb3dfdb4e5234c360b01f229efec30481ea0f726b645c413c12b40a7723b045231bf0323033c8e98ad632d2c63287538abf66922d3eea4c29b8b68e4656e7f8403c8ebd33ffd6c558974eb47902c22661b80dce320ee2c383bb3fc3dfda94e957b2a69f2dc5ca948a18d511620dfbb00100b7f3ce475ad1b1430c0914e8eaeaaae549e33b9fbf4f4fceb394aef4344acb5324d969b6c6393ed7215dc14218db2383d30a4f63fe719eb7541bf51f0f1de8abfda6adf3753fba9381eff00d335cccb134a517956f39594b0e3857ebed5d1ebb262ff00c36c79c6af1e4e7d639547ea456d41ae74615be128f8c59e46863099622518e31ce0573fe7b19161b72ad20c077c6557fc6b63c573ff00a5469c1675db8cf4e7927b63a0c1c7e9552c6d16d951a1859dce496ddd4e78007f175fa0f5acaa3b1bd3836bc874ba7ffc4a989901316662dd37601cfd0739fc2a5b1bc592c96ce7660b23ed56fe12c0823af519c647bd68dcc264b19226502478cab6d278247e5582d6e2ce2bab4102a3853346b1370a541e00e01e3239f4cf04e4e31773a934e36668db9f2ed2f44280806491c3206563b460f2720600e3031c60525bea9a5da68db3fb66c5a555722596ed4ef94609032dc1185e33c0233ea7021d66e2caea6b5912428acd1e060e30d8efd8e3a63358b3e88510185377de183807e65033e80fca33ff00d6ada2d2dcca70e6d133d086a36b6b66b04b26fda8233b4641c0c77f715143325c42c60575830400e06dcf24fe35069da2dbf94924c3cd7653d40dad9c60902b67cbdbb106d09dc67ebd3e95cf2696c3b230902ac922a874390551db201e7186e7d7ebc56bebc5bedfe1ec48155b58841c649638738fa77fc075cf15aea28dc3abec6c65766d18208ff0e3ff00d757b53816ef55f0e479e06aab2647aac32b7f4ae9c2bf7d1cd8856895b536593540c0876d98033c0193d31df02abc9716d088e68970c18832a16201e32300f249fe1efcf53c17c96f35dddcb244d6ed0491ec3b9fe6e49ce703dfd453a1d36ee37218dbaaeedcabe631c751d08e383db158caede874c1a8c6cc8e3d599f0d2ac6b1ed603032782064e09c0eb9e3e5c1c9a65dc715c3a7ee12e244de2442c57a8c601c71838cf7e3f03a0ba7b08023243db3891bae73c71ebce7d6963b3786d5920314785c005999477e9fcea6cd3ba43e687439cb3d0aceca3293dd2c93af32ee6c60e33d0e7b0cf3cf19cfa682258d93ab792c4a721990b053f31ce4e70060f3dbf2ababa5a2a042b1484670cd21e72393d3d38e98c7038a5934ac86d9b0396f33ccdec4eef5e7ae30319c81b578e0606e4f71f344af3eaca1d92275f918ac8e431f2c81939c74c6475e3f3c874fa9288e4fb3c324f280005c8500e33824f23b678e323a9e2a56d3402acaa8acaa07cb330276924671d70589f5c9cf5aaffd912095258e38b72028a7ce7e84e48e9dcf3dea794138914ee1b72ed0372e5b9f987a9fa7d2b5665c6b9e1ed8a0675024f6ff0096137f8d675d5acb6b0cb717571696f6bfc6ef2150a31819278ea7dba8fc72b46f15cbaff8f748b58ed121b38aeda48f7ab79a41b797696e70b9049c119e98c8049eac2c1f3a673621ae4763d16e7c2ba74fe618bcfb692420b490c9cfd30d918e0718ed559fc1f6d2b963a96a6ac0f559c0f7e9b714515e9ba70bec8f3fda4edbb1cbe14b5300125fea52eec6775c9c1cf5e0003f4a9d7c316ca462f6fb68fe132023b7b7b7eb4514bd9c3b073cbb917fc2256de584fed1d4b03bf9c39ffc7681e11b60c48d4752049cff00af1fcb14514bd9c3b0f9e5dc5ff844edb1817f7fd00e6453fcd69a3c2508e9aaea60f3c895075fa2e28a28f6705d107b493ea4371e06d3ef3cafb5de5edc989b7466731c9b7e9b90d4b61e08d0f4fd4e1d4628256b981e49212f336c8d9c618aa021067d851456918a4f426526d6acffd9, 'gif', 'scan.gif');
INSERT INTO `explnum` VALUES (10, 48, 0, 'photo', 'image/gif', '', 0x4749463839615a00aa00d50000ffffff622e2a904e2d9e6b52aa8a64d2b289d0a974fcfcfcb36a2aeed2a9fcfafbfbf9faaf9e8d71412bc39455d58935e8c798e2b472c47a2d71604af4e7d3d8cfc4a25c2cc19a67e2dad3fcfafc452c27e6a55983412dfefefec9bdb1925f41e7e4e1e09947d0a261f4deb9fafdfef0eae4fbf9f7f5f3f3fbfefff6f1e8fafbfbfcfbfafbfbf9fefffffdfcfdfefdfbfbfcfea87e48f7f9fafaf6f1fffefffffefefefefffdfdfdfffffefcfdfcfaf8f9e5be869d5423f0edecfefffef7f6f621f90400000000002c000000005a00aa000006ff408070482c1a8fc8d561c9542915d0a8744aad3e9dd66c72cbbd62bfdab0d835ee9acfe0b23abd46bbd9ed3859feae7be978bb3ecf87efff7d818082844d86831d5d2018148d3f7385917f1e0506063b1009238e30929e68159596979a10989b249f877788265294950e1711a509a70929280baebb7eaa88b0a322b5b115a625baacbfad50c11104a49ba60c130514b6a8abcb881822c231c3a5959a23e4998ec82cdbad10de9706030eb7d1e7e5e211e9eb83a2963be00599a091b347d056880accb431eb268b1434800c666d2a3871c40607f97ae9ab13cb5dbb6ab2ea118c45608005060a379ea930cb5d045a0ce291ca64aa63cc59206ea8d403e242cbff972f89d114d6b08087132d6af0daf9861fd05bf7faed78490065a2a54cddb07439f5d4355ba3444ed030c157562e968a3e1d58005cc572631bf4b8aaf12c97ad6abb0e8440e0edd4b81870d4b58b96eb86b57b1b992a3996ecd10c84cd50322ccc144d7bd4266826505506a4c84918729d9a36e8406a043c044e951434979e2e0f9fdae1f3c2655303acdad83dd8f5aa8eb2a1b69d85699aeedebe3f2bf1501bb1b9189c8b1e4fde85f968a89b2e4c1800dde774ea49ac5f1f688f4003eed073aa830cfe776ce7d28acfe2bc1a79fb1e4ec79bd3dbf6f1fd23a20913d66d3415a75a42caa5a4205d86e025a073967505d00b0826b8e0853a9021de7b524528ffe138c9fca75386f871282071a4a558492ef651574244d7f1d3dc80a220a44c7b05e4350f5122e4358b672d26e7e080e2d466a48c7305995c44d018799b9147d658218654d2c55c744e16d8902cd25d60a392be71d6dd91d8b403a5915f8a488407d08dd91d40a56c892503f5a949444c6e82235139c39da91e984ba257929e7bd226669b04fc09689882122ad1341f1c9ae8943756b92063919e59937991a647a959962691593c584a671207027c67a711200cb09da06a6d67010704b4b6ea16208cda9d9e039c37eb008ade2aaa6699ca53c007a8224b2b90c28646ec9b09c490ac00a8060baa85aeb9da6608b438a02cb51fa45969a8d8928b61af1f74eaffc061bda28aaaaad79a2baf190468a041030220f04f01f8ba1b4392cd22c180bdf6e21b8304cff49baf7fe3ceeb30861e104cf0791310a059b2b536fcf0c6af491cc0c704532300b2c052a831c72817d158c120177cea49b6c65b6eca9656e071cbf7be9cf1c934d33cf0cd1fa7fbb2b801ab0c740023bf0c6fd13780b0b2c4499f5a32d347447c73b2437fda7317f54e1cb4d0d4161033d5bc80d0c0d5e891cc2cd939f0d2b5c7429b14aed65b6ff133dc69a74a77dd493cfdb1af724fbd1edb3758cd32d2e8bdbb37dfacfa8db8dc23034c788677b71c37aa0c4f8ec1d9877300b9003b4fae4eaf37e72db8e8865bce1db2308b4ee2005e7f2d759d93a7feff77d4232fcd38df8e7bbe3ae8924f5ef9edbf5b40f4ee8c6f1efbe57af33c33f266c0feb7ec523f6f3df42be1fcf8ec0c625fb7f2d36f2f01c2217acfbbf6f91e16c2031bb038b8f9dd03327cfaeb6fc03e2e9dbc2f33fc77719e2c0212e096fdd857befdc56f715c68d7c82c608100aa8f7d1919d10111380970f180810014e0031e803f0352f00d257819061d38407c14f08308f2800547a8c18308c6753d10210049b8c11db4cd832844030316c8c207b68f3513cce11e9cc64306b26f7d1b1cc1d884c80c4e5d9085e3e3201329a5c222ce308a1058e21411e1442b8ecf86fcd35a05a8a5b02b12307f38dc6212cc53c62bfe308d6a0ccf04dcf5c42886ff8002278ca3fcdab8c124e6518f439ca305fb28453802b26aacc320124d08c326ec90871ac4e3a20ea98874f5300459342425c1f0c819aaef8d9adc24003060c90cbe04937f14e51b08d0c308704b92ec51a51d76e8c6538231949b24257a48683f46e2f2902639d8f81e58c85f025298762464048028cb3ab05298231ca6310ff9c815da517fcdd46103b6f93f4f3e629a71242537791845f781738b2a1ca71969974d3730409d0dfca21683d8cef5cc115fd11401562458cffe6df382010c6004e9d9cf27dc335f012d66239b51c63e66129b05dd021b018a44584234a26918233e11b0c8b51114a30a6ce0003b784e35be73810e84e0f5300a802a5274a4ffcc64a9b3507a443f96748b21a429b76a78539ca2cb881b9c21529cd7cf9f7a919d323d44bbfe5947099034a94778e7edf2994aa8f6e09e4c9d2146561ad10a8c13a15144aa559920d58dce506c3ddde241c12a50def073ac2828ebc8ec682db82ee7ab0935c045e11a528ef65189b1b4ab134eaa53f25535a9e22cac4205bb84bed674b18225ec4b37a8d785b6149f931ddf40e1aad17cc5737d003cde58735ac43aa2358c9ffa161d317881b43291745305a8048267570f702eb6a165ec17608bb4229ef6ad56f5eaf4c8b9d5bd26f56d98152a6ab56636dce676920595ab6f974b37e975f3b996b5ed54c78780ca5ad669dbe5ae58f91adef1b596a8fd943f6e3cb1abdbcde135a8c5d5ad0c429a3eee9e17b856952cfd66385ea8ea17834f146d707bebaed566aeb62dfbea057f2b589b796db5f165ecf0b65bd72e0401003b, 0xffd8ffe000104a46494600010100000100010000fffe003e43524541544f523a2067642d6a7065672076312e3020287573696e6720494a47204a50454720763632292c2064656661756c74207175616c6974790affdb004300080606070605080707070909080a0c140d0c0b0b0c1912130f141d1a1f1e1d1a1c1c20242e2720222c231c1c2837292c30313434341f27393d38323c2e333432ffdb0043010909090c0b0c180d0d1832211c213232323232323232323232323232323232323232323232323232323232323232323232323232323232323232323232323232ffc00011080064003403012200021101031101ffc4001f0000010501010101010100000000000000000102030405060708090a0bffc400b5100002010303020403050504040000017d01020300041105122131410613516107227114328191a1082342b1c11552d1f02433627282090a161718191a25262728292a3435363738393a434445464748494a535455565758595a636465666768696a737475767778797a838485868788898a92939495969798999aa2a3a4a5a6a7a8a9aab2b3b4b5b6b7b8b9bac2c3c4c5c6c7c8c9cad2d3d4d5d6d7d8d9dae1e2e3e4e5e6e7e8e9eaf1f2f3f4f5f6f7f8f9faffc4001f0100030101010101010101010000000000000102030405060708090a0bffc400b51100020102040403040705040400010277000102031104052131061241510761711322328108144291a1b1c109233352f0156272d10a162434e125f11718191a262728292a35363738393a434445464748494a535455565758595a636465666768696a737475767778797a82838485868788898a92939495969798999aa2a3a4a5a6a7a8a9aab2b3b4b5b6b7b8b9bac2c3c4c5c6c7c8c9cad2d3d4d5d6d7d8d9dae2e3e4e5e6e7e8e9eaf2f3f4f5f6f7f8f9faffda000c03010002110311003f00f7d1c0a5e940e94c9e58ede179a57091a02ccc7b0a00a5a96b16da6b469207796438444c13fe7fc29d6da8dbdcbbc6adb6446da5588ce6b8f651abdf79f772bc3217f323da5782380391e871d2896f2e4bd959da452c8d2cf1c3722de2690dba9eacef865047520d70c713294bdd5a1d4e8c631d773bce3147d2a2b6816dada3810b158d4282c724fd4d4bf5aee4731f30fed1bff250ac3fec151ffe8d968a5fda37fe4a169fff0060a8ff00f46cb45023e9bb8b88ad6079e7609120cb31ec2b8dd67589751550a8d1d9ab6464f2e7d4ff00855ef1f7da878703c0b218d2756b8d99e23c1c9207500ed3ff00eaae1af35810691636c3696b89f2aee76a05e84e7a0c1619e7a570e2a72bf22d8ebc3c13f7ba9d335aaeb0b05a594af0ca84b09a3c718c6739edd8fd6bb1b3b486cad9618940039660a0176eec71dcf7355747d1e2d22db62b99256fbf2118c9f41e833dab4ab7a147d9ad7732ab5399e9b0528e693a9a77e55b989f307ed1dff250ac3fec151ffe8d968a3f68d39f88561c7fcc2a3ffd1b2d1401f4cdc5b25ddacb6f28fddcc851c77c11835f32df1d5f4ad59bc39aac88cd6b2ed24f3db82ad8ce0820f3ea38afa7d7818f4af20f8d9a1858f4ff00105bc6a92249e44ee01c9046518fb021867dc0acaac1491bd09b8cac7a4786f555d67c3f677bbcb48630b36460f98386e3eb9ad6af31f851ac79a6eac77b6c9505c46a7180c301c67a9ea9f91af4e14e94b9a09b26b43926d077f7a5a4a70c639ad0c8f983f68dff00928561ff0060a8ff00f46cb451fb477fc942b0c7fd02a3ff00d1b2d1401f4ff359faee9a9ac6857fa6be3fd26078d4b0c85623838f6383f855fcfbfe94bf85034ecee784f821068d78ad297fb55a4b960fc10b9db20c7e3debdd1791904115e4be3af0fb693e248f52b63b2cefd8f98013f2ca4e589f66193f5cf1ebe9da4dc0b8d2edd836e611857c7660307f5ae1c3f342aca127e6766279674e3523e85dc71413ed4003bd1f88aee388f987f68dff00928561ff0060a8ff00f46cb451fb46ff00c942b0ff00b0547ffa365a2803e9ecf1d6819140e2979a00e6fc75a5dc6a9e1a93ec71892e6d9c5c471e092e541040c77c138ac2f86dac3df457293bfccca8d1ae4e38e0f5efcae7e95e83ec6bc56df514f0cf8eb5287c97564bc678d02f011893c67b156ae2c4a54e71acba6fe876e1ff00790952fb8f69f71464fad229e38229ddbfc2bb4e23e60fda37fe4a1587fd82a3ff00d1b2d147ed1dff00250ac3fec151ff00e8d968a00fa740a71e9d3349de97f1fd28010f5af32f8a762b0cfa46b02351e5bb412b648c83f328fa70ff009d7a70c9cf18e78ac8f136871788bc3d77a5cbf289d3e47fee38e54fe60567561cf07134a53e49a9153c1fabc7a9e925166599addbcb2e0e77291953f91c7fc06ba23ed5e39f0ab59fb15c4969347b16e9d63624e3638ddb7f3e47d715ec5ce2b3c34af4ecdeab434c4c396a3b6cf53e61fda3bfe4a1d87fd82a3ffd1b2d147ed1bff250ac3fec151ffe8d968ae839cfa79bad0b451400eef4dcf19a28a00f9cf539e7d1fe235fd95a4d22c2b7db80ce3beeed807049c57d150b168949ea4034515cd455aa4ade5fa9d788d69c3e7fa1f31fed1dff00250ac3fec151ff00e8d9a8a28ae9390fffd9, 'gif', 'scan2.gif');

-- --------------------------------------------------------

-- 
-- Structure de la table `frais`
-- 

CREATE TABLE `frais` (
  `id_frais` int(8) unsigned NOT NULL auto_increment,
  `libelle` varchar(255) NOT NULL default '',
  `condition_frais` text NOT NULL,
  `montant` float(8,2) unsigned NOT NULL default '0.00',
  `num_cp_compta` varchar(255) NOT NULL default '',
  `num_tva_achat` varchar(25) NOT NULL default '0',
  PRIMARY KEY  (`id_frais`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `frais`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `groupe`
-- 

CREATE TABLE `groupe` (
  `id_groupe` int(6) unsigned NOT NULL auto_increment,
  `libelle_groupe` varchar(50) NOT NULL default '',
  `resp_groupe` int(6) unsigned default '0',
  PRIMARY KEY  (`id_groupe`),
  UNIQUE KEY `libelle_groupe` (`libelle_groupe`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Contenu de la table `groupe`
-- 

INSERT INTO `groupe` VALUES (1, 'à»?àº?à»‰àº§', 1);
INSERT INTO `groupe` VALUES (2, 'try', 4);
INSERT INTO `groupe` VALUES (3, 'àº”àº«', 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `import_marc`
-- 

CREATE TABLE `import_marc` (
  `id_import` bigint(5) unsigned NOT NULL auto_increment,
  `notice` longblob NOT NULL,
  `origine` varchar(50) default '',
  `no_notice` int(10) unsigned default '0',
  PRIMARY KEY  (`id_import`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `import_marc`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `indexint`
-- 

CREATE TABLE `indexint` (
  `indexint_id` mediumint(8) unsigned NOT NULL auto_increment,
  `indexint_name` varchar(255) NOT NULL default '',
  `indexint_comment` text,
  `index_indexint` text,
  PRIMARY KEY  (`indexint_id`),
  UNIQUE KEY `indexint_name` (`indexint_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=101 ;

-- 
-- Contenu de la table `indexint`
-- 

INSERT INTO `indexint` VALUES (1, '000', 'àº‚à»?à»‰àº¡àº¹àº™ àº?àº²àº™àº•àº´àº”àº•à»?à»ˆàºŠàº·à»ˆàºªàº²àº™', ' 000 ');
INSERT INTO `indexint` VALUES (2, '010', 'Bibliographies Catalogues', ' 010 bibliographies catalogues ');
INSERT INTO `indexint` VALUES (3, '020', 'Bibliothèques - et lecture, documentation', ' 020 bibliotheques lecture documentation ');
INSERT INTO `indexint` VALUES (4, '030', 'Encyclopédies générales', ' 030 encyclopedies generales ');
INSERT INTO `indexint` VALUES (5, '040', 'X', ' 040 x ');
INSERT INTO `indexint` VALUES (6, '050', 'Périodiques généraux - annuaires', ' 050 periodiques generaux annuaires ');
INSERT INTO `indexint` VALUES (7, '060', 'Organisations générales - congrès', ' 060 organisations generales congres ');
INSERT INTO `indexint` VALUES (8, '070', 'Presse Edition', ' 070 presse edition ');
INSERT INTO `indexint` VALUES (9, '080', 'Recueils - mélanges, discours', ' 080 recueils melanges discours ');
INSERT INTO `indexint` VALUES (10, '090', 'Manuscrits Livres rares', ' 090 manuscrits livres rares ');
INSERT INTO `indexint` VALUES (11, '100', 'Philosophie', ' 100 philosophie ');
INSERT INTO `indexint` VALUES (12, '110', 'Métaphysique', ' 110 metaphysique ');
INSERT INTO `indexint` VALUES (13, '120', 'Connaissance', ' 120 connaissance ');
INSERT INTO `indexint` VALUES (14, '130', 'Parapsychologie - astrologie, graphologie', ' 130 parapsychologie astrologie graphologie ');
INSERT INTO `indexint` VALUES (15, '140', 'Systèmes philosophiques', ' 140 systemes philosophiques ');
INSERT INTO `indexint` VALUES (16, '150', 'Psychologie', ' 150 psychologie ');
INSERT INTO `indexint` VALUES (17, '160', 'Logique', ' 160 logique ');
INSERT INTO `indexint` VALUES (18, '170', 'Morale - ethique', ' 170 morale ethique ');
INSERT INTO `indexint` VALUES (19, '180', 'Philosophes anciens - et orientaux', ' 180 philosophes anciens orientaux ');
INSERT INTO `indexint` VALUES (20, '190', 'Philosophes modernes - (XVIe S. à nos jours)', ' 190 philosophes modernes xvie s nos jours ');
INSERT INTO `indexint` VALUES (21, '200', 'Religion', ' 200 religion ');
INSERT INTO `indexint` VALUES (22, '210', 'Religion naturelle', ' 210 religion naturelle ');
INSERT INTO `indexint` VALUES (23, '220', 'Bible Evangiles', ' 220 bible evangiles ');
INSERT INTO `indexint` VALUES (24, '230', 'Théologie doctrinale chrétienne - (dogme)', ' 230 theologie doctrinale chretienne dogme ');
INSERT INTO `indexint` VALUES (25, '240', 'Théologie spirituelle - vie religieuse', ' 240 theologie spirituelle vie religieuse ');
INSERT INTO `indexint` VALUES (26, '250', 'Théologie pastorale', ' 250 theologie pastorale ');
INSERT INTO `indexint` VALUES (27, '260', 'L''Eglise chrétienne et la société', ' 260 eglise chretienne societe ');
INSERT INTO `indexint` VALUES (28, '270', 'Histoire de l''Eglise chrétienne', ' 270 histoire eglise chretienne ');
INSERT INTO `indexint` VALUES (29, '280', 'Autres confessions chrétiennes', ' 280 autres confessions chretiennes ');
INSERT INTO `indexint` VALUES (30, '290', 'Autres religions et mythologies', ' 290 autres religions mythologies ');
INSERT INTO `indexint` VALUES (31, '300', 'Sciences sociales', ' 300 sciences sociales ');
INSERT INTO `indexint` VALUES (32, '310', 'Statistiques', ' 310 statistiques ');
INSERT INTO `indexint` VALUES (33, '320', 'Politique - l''Etat', ' 320 politique etat ');
INSERT INTO `indexint` VALUES (34, '330', 'Economie - finances, production, consommation', ' 330 economie finances production consommation ');
INSERT INTO `indexint` VALUES (35, '340', 'Droit - justice', ' 340 droit justice ');
INSERT INTO `indexint` VALUES (36, '350', 'Administration de l''Etat', ' 350 administration etat ');
INSERT INTO `indexint` VALUES (37, '360', 'Aide Assistance Secours', ' 360 aide assistance secours ');
INSERT INTO `indexint` VALUES (38, '370', 'Education - enseignement', ' 370 education enseignement ');
INSERT INTO `indexint` VALUES (39, '380', 'Commerce Transports Communication', ' 380 commerce transports communication ');
INSERT INTO `indexint` VALUES (40, '390', 'Costumes et folklore', ' 390 costumes folklore ');
INSERT INTO `indexint` VALUES (41, '400', 'Langage', ' 400 langage ');
INSERT INTO `indexint` VALUES (42, '410', 'Linguistique', ' 410 linguistique ');
INSERT INTO `indexint` VALUES (43, '420', 'Langue anglaise', ' 420 langue anglaise ');
INSERT INTO `indexint` VALUES (44, '430', 'Langue allemande', ' 430 langue allemande ');
INSERT INTO `indexint` VALUES (45, '440', 'Langue française - (dictionnaires, grammaire)', ' 440 langue francaise dictionnaires grammaire ');
INSERT INTO `indexint` VALUES (46, '450', 'Langue italienne', ' 450 langue italienne ');
INSERT INTO `indexint` VALUES (47, '460', 'Langue espagnole et portugaise', ' 460 langue espagnole portugaise ');
INSERT INTO `indexint` VALUES (48, '470', 'Langue latine', ' 470 langue latine ');
INSERT INTO `indexint` VALUES (49, '480', 'Langue grecque', ' 480 langue grecque ');
INSERT INTO `indexint` VALUES (50, '490', 'Autres langues - russe, arabe, …', ' 490 autres langues russe arabe ');
INSERT INTO `indexint` VALUES (51, '500', 'Sciences', ' 500 sciences ');
INSERT INTO `indexint` VALUES (52, '510', 'Mathématiques', ' 510 mathematiques ');
INSERT INTO `indexint` VALUES (53, '520', 'Astronomie', ' 520 astronomie ');
INSERT INTO `indexint` VALUES (54, '530', 'Physique', ' 530 physique ');
INSERT INTO `indexint` VALUES (55, '540', 'Chimie - minéralogie', ' 540 chimie mineralogie ');
INSERT INTO `indexint` VALUES (56, '550', 'Sciences de la Terre - géologie, météorologie', ' 550 sciences terre geologie meteorologie ');
INSERT INTO `indexint` VALUES (57, '560', 'Paléontologie - (les fossiles)', ' 560 paleontologie fossiles ');
INSERT INTO `indexint` VALUES (58, '570', 'Sciences de la vie - biologie, génétique', ' 570 sciences vie biologie genetique ');
INSERT INTO `indexint` VALUES (59, '580', 'Botanique - (les plantes)', ' 580 botanique plantes ');
INSERT INTO `indexint` VALUES (60, '590', 'Zoologie - (les animaux)', ' 590 zoologie animaux ');
INSERT INTO `indexint` VALUES (61, '600', 'Techniques', ' 600 techniques ');
INSERT INTO `indexint` VALUES (62, '610', 'Médecine - hygiène, santé', ' 610 medecine hygiene sante ');
INSERT INTO `indexint` VALUES (63, '620', 'Techniques industrielles - mécanique, électricité, radio, énergie…', ' 620 techniques industrielles mecanique electricite radio energie ');
INSERT INTO `indexint` VALUES (64, '630', 'Agriculture - forêt, élevage, pêche', ' 630 agriculture foret elevage peche ');
INSERT INTO `indexint` VALUES (65, '640', 'Arts ménagers - cuisine, coutûre, soins de beauté', ' 640 arts menagers cuisine couture soins beaute ');
INSERT INTO `indexint` VALUES (66, '650', 'Entreprise - travail de bureaux, vente, publicité', ' 650 entreprise travail bureaux vente publicite ');
INSERT INTO `indexint` VALUES (67, '660', 'Industries chimiques et alimentaires', ' 660 industries chimiques alimentaires ');
INSERT INTO `indexint` VALUES (68, '670', 'Fabrications industrielles - métallurgie, bois, textile', ' 670 fabrications industrielles metallurgie bois textile ');
INSERT INTO `indexint` VALUES (69, '680', 'Articles manufacturés', ' 680 articles manufactures ');
INSERT INTO `indexint` VALUES (70, '690', 'Bâtiment - construction', ' 690 batiment construction ');
INSERT INTO `indexint` VALUES (71, '700', 'Arts et loisirs', ' 700 arts loisirs ');
INSERT INTO `indexint` VALUES (72, '710', 'Urbanisme - art du paysage', ' 710 urbanisme art paysage ');
INSERT INTO `indexint` VALUES (73, '720', 'Architecture', ' 720 architecture ');
INSERT INTO `indexint` VALUES (74, '730', 'Sculpture', ' 730 sculpture ');
INSERT INTO `indexint` VALUES (75, '740', 'Dessin - arts décoratifs', ' 740 dessin arts decoratifs ');
INSERT INTO `indexint` VALUES (76, '750', 'Peinture', ' 750 peinture ');
INSERT INTO `indexint` VALUES (77, '760', 'Arts graphiques - graphisme', ' 760 arts graphiques graphisme ');
INSERT INTO `indexint` VALUES (78, '770', 'Photographie', ' 770 photographie ');
INSERT INTO `indexint` VALUES (79, '780', 'Musique', ' 780 musique ');
INSERT INTO `indexint` VALUES (80, '790', 'Loisirs - spectacles, jeux, sports', ' 790 loisirs spectacles jeux sports ');
INSERT INTO `indexint` VALUES (81, '800', 'Littérature', ' 800 litterature ');
INSERT INTO `indexint` VALUES (82, '810', 'Littérature américaine', ' 810 litterature americaine ');
INSERT INTO `indexint` VALUES (83, '820', 'Littérature anglaise', ' 820 litterature anglaise ');
INSERT INTO `indexint` VALUES (84, '830', 'Littérature allemande', ' 830 litterature allemande ');
INSERT INTO `indexint` VALUES (85, '840', 'Littérature française', ' 840 litterature francaise ');
INSERT INTO `indexint` VALUES (86, '850', 'Littérature italienne', ' 850 litterature italienne ');
INSERT INTO `indexint` VALUES (87, '860', 'Littérature espagnole et portugaise', ' 860 litterature espagnole portugaise ');
INSERT INTO `indexint` VALUES (88, '870', 'Littérature latine', ' 870 litterature latine ');
INSERT INTO `indexint` VALUES (89, '880', 'Littérature grecque', ' 880 litterature grecque ');
INSERT INTO `indexint` VALUES (90, '890', 'Autres littératures', ' 890 autres litteratures ');
INSERT INTO `indexint` VALUES (91, '900', 'Histoire géographie', ' 900 histoire geographie ');
INSERT INTO `indexint` VALUES (92, '910', 'Géographie - voyages', ' 910 geographie voyages ');
INSERT INTO `indexint` VALUES (93, '920', 'Biographies - vie d''un personnage, généalogie', ' 920 biographies vie personnage genealogie ');
INSERT INTO `indexint` VALUES (94, '930', 'Histoire ancienne', ' 930 histoire ancienne ');
INSERT INTO `indexint` VALUES (95, '940', 'Histoire de l''Europe', ' 940 histoire europe ');
INSERT INTO `indexint` VALUES (96, '950', 'Histoire de l''Asie', ' 950 histoire asie ');
INSERT INTO `indexint` VALUES (97, '960', 'Histoire de l''Afrique', ' 960 histoire afrique ');
INSERT INTO `indexint` VALUES (98, '970', 'Histoire de l''Amérique du Nord', ' 970 histoire amerique nord ');
INSERT INTO `indexint` VALUES (99, '980', 'Histoire de l''Amérique du Sud', ' 980 histoire amerique sud ');
INSERT INTO `indexint` VALUES (100, '990', 'Histoire de l''Océanie', ' 990 histoire oceanie ');

-- --------------------------------------------------------

-- 
-- Structure de la table `lenders`
-- 

CREATE TABLE `lenders` (
  `idlender` smallint(5) unsigned NOT NULL auto_increment,
  `lender_libelle` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`idlender`),
  KEY `idcode` (`idlender`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Contenu de la table `lenders`
-- 

INSERT INTO `lenders` VALUES (1, 'BDP');
INSERT INTO `lenders` VALUES (2, 'Fonds propre');

-- --------------------------------------------------------

-- 
-- Structure de la table `liens_actes`
-- 

CREATE TABLE `liens_actes` (
  `num_acte` int(8) unsigned NOT NULL default '0',
  `num_acte_lie` int(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`num_acte`,`num_acte_lie`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `liens_actes`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `lignes_actes`
-- 

CREATE TABLE `lignes_actes` (
  `id_ligne` int(15) unsigned NOT NULL auto_increment,
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `lignes_actes`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `noeuds`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=2517 ;

-- 
-- Contenu de la table `noeuds`
-- 

INSERT INTO `noeuds` VALUES (1, 'TOP', 0, 0, '0', 1);
INSERT INTO `noeuds` VALUES (2484, 'ORPHELINS', 1, 0, '0', 1);
INSERT INTO `noeuds` VALUES (1378, '1377', 1, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1379, '1378', 1, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1380, '1379', 1, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1381, '1380', 1, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1382, '1381', 1, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1383, '1382', 1, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1384, '1383', 1, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1385, '1384', 1, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1386, '1385', 1, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1387, '1386', 1, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1388, '1387', 1378, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1389, '1388', 1378, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1390, '1389', 1378, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1391, '1390', 1378, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1392, '1391', 1378, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1393, '1392', 1378, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1394, '1393', 1378, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1395, '1394', 1378, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1396, '1395', 1378, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1397, '1396', 1378, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1398, '1397', 1378, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1399, '1398', 1378, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1400, '1399', 1378, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1401, '1400', 1378, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1402, '1401', 1390, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1403, '1402', 1408, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1404, '1403', 1390, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1405, '1404', 1390, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1406, '1405', 1390, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1407, '1406', 1408, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1408, '1407', 1390, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1409, '1408', 1408, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1410, '1409', 1406, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1411, '1410', 1391, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1412, '1411', 1391, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1413, '1412', 1391, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1414, '1413', 1391, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1415, '1414', 1391, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1416, '1415', 1391, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1417, '1416', 1391, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1418, '1417', 1391, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1419, '1418', 1391, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1420, '1419', 1394, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1421, '1420', 2046, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1422, '1421', 2045, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1423, '1422', 2045, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1424, '1423', 2045, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1425, '1424', 2045, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1426, '1425', 2045, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1427, '1426', 2045, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1428, '1427', 2045, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1429, '1428', 2045, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1430, '1429', 2045, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1431, '1430', 1420, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1432, '1431', 1420, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1433, '1432', 1420, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1434, '1433', 1420, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1435, '1434', 1420, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1436, '1435', 1420, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1437, '1436', 1420, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1438, '1437', 1420, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1439, '1438', 1420, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1440, '1439', 1420, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1441, '1440', 1420, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1442, '1441', 2046, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1443, '1442', 1420, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1444, '1443', 1420, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1445, '1444', 2046, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1446, '1445', 1422, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1447, '1446', 1423, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1448, '1447', 1424, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1449, '1448', 1425, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1450, '1449', 1426, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1451, '1450', 1427, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1452, '1451', 1428, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1453, '1452', 1429, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1454, '1453', 1430, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1455, '1454', 2046, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1456, '1455', 1422, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1457, '1456', 1423, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1458, '1457', 1424, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1459, '1458', 1425, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1460, '1459', 1426, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1461, '1460', 1427, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1462, '1461', 1428, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1463, '1462', 1428, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1464, '1463', 1429, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1465, '1464', 1430, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1466, '1465', 2046, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1467, '1466', 1422, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1468, '1467', 1423, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1469, '1468', 1424, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1470, '1469', 1425, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1471, '1470', 1426, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1472, '1471', 1427, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1473, '1472', 1429, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1474, '1473', 1428, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1475, '1474', 1430, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1476, '1475', 1426, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1477, '1476', 1427, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1478, '1477', 1429, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1479, '1478', 1430, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1480, '1479', 1422, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1481, '1480', 1423, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1482, '1481', 1424, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1483, '1482', 1425, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1484, '1483', 2160, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1485, '1484', 2160, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1486, '1485', 2160, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1487, '1486', 2160, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1488, '1487', 2160, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1489, '1488', 2160, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1490, '1489', 2160, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1491, '1490', 1916, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1492, '1491', 1399, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1493, '1492', 1379, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1494, '1493', 1379, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1495, '1494', 1379, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1496, '1495', 1379, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1497, '1496', 1379, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1498, '1497', 1379, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1499, '1498', 1495, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1500, '1499', 1495, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1501, '1500', 1495, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1502, '1501', 1495, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1503, '1502', 1495, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1504, '1503', 1495, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1505, '1504', 1495, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1506, '1505', 1382, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1507, '1506', 1495, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1508, '1507', 1495, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1509, '1508', 1495, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1510, '1509', 1495, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1511, '1510', 1495, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1512, '1511', 1497, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1513, '1512', 1497, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1514, '1513', 1497, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1515, '1514', 1380, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1516, '1515', 1380, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1517, '1516', 1380, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1518, '1517', 1380, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1519, '1518', 1380, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1520, '1519', 1380, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1521, '1520', 1380, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1522, '1521', 1380, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1523, '1522', 1380, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1524, '1523', 1525, 1641, '1', 1);
INSERT INTO `noeuds` VALUES (1525, '1524', 1515, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1526, '1525', 1515, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1527, '1526', 1526, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1528, '1527', 1526, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1529, '1528', 1526, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1530, '1529', 1526, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1531, '1530', 1515, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1532, '1531', 1515, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1533, '1532', 1515, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1534, '1533', 1515, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1535, '1534', 1515, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1536, '1535', 1516, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1537, '1536', 1516, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1538, '1537', 1516, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1539, '1538', 1516, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1540, '1539', 1516, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1541, '1540', 1516, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1542, '1541', 1516, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1543, '1542', 1516, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1544, '1543', 1516, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1545, '1544', 1517, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1546, '1545', 1517, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1547, '1546', 1523, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1548, '1547', 1517, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1549, '1548', 1517, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1550, '1549', 1551, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1551, '1550', 1517, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1552, '1551', 1517, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1553, '1552', 1517, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1554, '1553', 1518, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1555, '1554', 1518, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1556, '1555', 1518, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1557, '1556', 1518, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1558, '1557', 1518, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1559, '1558', 1519, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1560, '1559', 1519, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1561, '1560', 1519, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1562, '1561', 1519, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1563, '1562', 1519, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1564, '1563', 1519, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1565, '1564', 1519, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1566, '1565', 1519, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1567, '1566', 1555, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1568, '1567', 2167, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1569, '1568', 2167, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1570, '1569', 2167, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1571, '1570', 2167, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1572, '1571', 2167, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1573, '1572', 2167, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1574, '1573', 2168, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1575, '1574', 2168, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1576, '1575', 2168, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1577, '1576', 1520, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1578, '1577', 1520, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1579, '1578', 1520, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1580, '1579', 1520, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1581, '1580', 1520, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1582, '1581', 1521, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1583, '1582', 1521, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1584, '1583', 1521, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1585, '1584', 1521, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1586, '1585', 1521, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1587, '1586', 1521, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1588, '1587', 1521, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1589, '1588', 1521, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1590, '1589', 1521, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1591, '1590', 1521, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1592, '1591', 1522, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1593, '1592', 2166, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1594, '1593', 2166, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1595, '1594', 2166, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1596, '1595', 1522, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1597, '1596', 1522, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1598, '1597', 1522, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1599, '1598', 1522, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1600, '1599', 1522, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1601, '1600', 1522, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1602, '1601', 1522, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1603, '1602', 1523, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1604, '1603', 1523, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1605, '1604', 1523, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1606, '1605', 1523, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1607, '1606', 1523, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1608, '1607', 1523, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1609, '1608', 1523, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1610, '1609', 1523, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1611, '1610', 1523, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1612, '1611', 1381, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1613, '1612', 2022, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1614, '1613', 1381, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1615, '1614', 2022, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1616, '1615', 2022, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1617, '1616', 1381, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1618, '1617', 1381, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1619, '1618', 1381, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1620, '1619', 1381, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1621, '1620', 2022, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1622, '1621', 1620, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1623, '1622', 1620, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1624, '1623', 1620, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1625, '1624', 1620, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1626, '1625', 1620, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1627, '1626', 1620, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1628, '1627', 1620, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1629, '1628', 1621, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1630, '1629', 1621, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1631, '1630', 1621, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1632, '1631', 1621, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1633, '1632', 1621, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1634, '1633', 1621, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1635, '1634', 1621, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1636, '1635', 1621, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1637, '1636', 1621, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1638, '1637', 1621, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1639, '1638', 1621, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1640, '1639', 1639, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1641, '1640', 1644, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1642, '1641', 1639, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1643, '1642', 2141, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1644, '1643', 1639, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1645, '1644', 1639, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1646, '1645', 1382, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1647, '1646', 1382, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1648, '1647', 1382, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1649, '1648', 1382, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1650, '1649', 1382, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1651, '1650', 1382, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1652, '1651', 1382, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1653, '1652', 1382, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1654, '1653', 1382, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1655, '1654', 1382, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1656, '1655', 1382, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1657, '1656', 1382, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1658, '1657', 1647, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1659, '1658', 1647, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1660, '1659', 1647, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1661, '1660', 1647, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1662, '1661', 1647, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1663, '1662', 1651, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1664, '1663', 1651, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1665, '1664', 1651, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1666, '1665', 1651, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1667, '1666', 1651, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1668, '1667', 1651, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1669, '1668', 1651, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1670, '1669', 1651, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1671, '1670', 1651, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1672, '1671', 1651, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1673, '1672', 1651, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1674, '1673', 1651, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1675, '1674', 1654, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1676, '1675', 1654, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1677, '1676', 1654, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1678, '1677', 1654, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1679, '1678', 1654, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1680, '1679', 1654, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1681, '1680', 1684, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1682, '1681', 1383, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1683, '1682', 1383, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1684, '1683', 1383, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1685, '1684', 1683, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1686, '1685', 1383, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1687, '1686', 1383, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1688, '1687', 1684, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1689, '1688', 1684, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1690, '1689', 1383, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1691, '1690', 1684, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1692, '1691', 1683, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1693, '1692', 1383, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1694, '1693', 1383, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1695, '1694', 1385, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1696, '1695', 1383, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1697, '1696', 1383, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1698, '1697', 1684, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1699, '1698', 1684, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1700, '1699', 1383, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1701, '1700', 1684, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1702, '1701', 1682, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1703, '1702', 1682, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1704, '1703', 1682, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1705, '1704', 1682, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1706, '1705', 1682, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1707, '1706', 1687, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1708, '1707', 1687, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1709, '1708', 1687, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1710, '1709', 1687, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1711, '1710', 1687, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1712, '1711', 1687, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1713, '1712', 1687, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1714, '1713', 1683, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1715, '1714', 1696, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1716, '1715', 1696, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1717, '1716', 1696, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1718, '1717', 1696, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1719, '1718', 1696, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1720, '1719', 1696, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1721, '1720', 1696, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1722, '1721', 1696, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1723, '1722', 1384, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1724, '1723', 1384, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1725, '1724', 1384, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1726, '1725', 1384, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1727, '1726', 2203, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1728, '1727', 1384, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1729, '1728', 1384, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1730, '1729', 1384, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1731, '1730', 1384, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1733, '1732', 1384, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1734, '1733', 1917, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1735, '1734', 1734, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1736, '1735', 1734, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1737, '1736', 1734, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1738, '1737', 1734, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1739, '1738', 1734, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1740, '1739', 1734, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1741, '1740', 1734, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1742, '1741', 1734, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1743, '1742', 1734, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1744, '1743', 1734, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1745, '1744', 1915, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1746, '1745', 1734, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1747, '1746', 1734, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1748, '1747', 1734, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1749, '1748', 1734, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1750, '1749', 1734, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1751, '1750', 1734, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1752, '1751', 1734, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1753, '1752', 1734, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1754, '1753', 1734, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1755, '1754', 1734, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1756, '1755', 1734, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1757, '1756', 1734, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1758, '1757', 1385, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1759, '1758', 1385, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1760, '1759', 1385, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1761, '1760', 1385, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1762, '1761', 1385, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1763, '1762', 1385, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1764, '1763', 1385, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1765, '1764', 1385, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1766, '1765', 1385, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1767, '1766', 1385, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1768, '1767', 1385, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1769, '1768', 1385, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1770, '1769', 1385, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1771, '1770', 1385, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1772, '1771', 1385, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1773, '1772', 1765, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1774, '1773', 1765, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1775, '1774', 1765, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1776, '1775', 1765, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1777, '1776', 1765, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1778, '1777', 1386, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1779, '1778', 1386, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1780, '1779', 1386, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1781, '1780', 1386, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1782, '1781', 1386, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1783, '1782', 1386, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1784, '1783', 1386, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1785, '1784', 1386, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1786, '1785', 1386, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1787, '1786', 1386, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1788, '1787', 1387, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1789, '1788', 1387, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1790, '1789', 1387, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1791, '1790', 1387, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1792, '1791', 1387, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1793, '1792', 1387, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1794, '1793', 1387, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1795, '1794', 1387, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1796, '1795', 1387, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1797, '1796', 1788, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1798, '1797', 1788, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1799, '1798', 1788, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1800, '1799', 1788, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1801, '1800', 1788, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1802, '1801', 1788, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1803, '1802', 1788, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1804, '1803', 1789, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1805, '1804', 1789, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1806, '1805', 1789, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1807, '1806', 1789, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1808, '1807', 1789, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1809, '1808', 1789, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1810, '1809', 1789, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1811, '1810', 1790, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1812, '1811', 1790, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1813, '1812', 1790, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1814, '1813', 1790, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1815, '1814', 1790, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1816, '1815', 1790, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1817, '1816', 1790, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1818, '1817', 1790, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1819, '1818', 1791, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1820, '1819', 1791, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1821, '1820', 1791, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1822, '1821', 1791, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1823, '1822', 1791, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1824, '1823', 1791, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1825, '1824', 1791, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1826, '1825', 1791, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1827, '1826', 1791, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1828, '1827', 1791, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1829, '1828', 1791, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1830, '1829', 1791, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1831, '1830', 1791, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1832, '1831', 1792, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1833, '1832', 1792, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1834, '1833', 1792, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1835, '1834', 1792, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1836, '1835', 1792, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1837, '1836', 1792, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1838, '1837', 1793, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1839, '1838', 1793, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1840, '1839', 1793, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1841, '1840', 1793, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1842, '1841', 1793, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1843, '1842', 1793, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1844, '1843', 1794, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1845, '1844', 1794, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1846, '1845', 1794, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1847, '1846', 1794, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1848, '1847', 1797, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1849, '1848', 1797, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1850, '1849', 1797, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1851, '1850', 1797, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1852, '1851', 1798, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1853, '1852', 1798, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1854, '1853', 1798, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1855, '1854', 1798, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1856, '1855', 1798, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1857, '1856', 1798, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1858, '1857', 1798, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1859, '1858', 1798, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1860, '1859', 1798, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1861, '1860', 1798, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1862, '1861', 1798, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1863, '1862', 1798, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1864, '1863', 1798, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1865, '1864', 1798, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1866, '1865', 1799, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1867, '1866', 1799, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1868, '1867', 1799, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1869, '1868', 1799, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1870, '1869', 1799, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1871, '1870', 1800, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1872, '1871', 1800, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1873, '1872', 1800, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1874, '1873', 1800, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1875, '1874', 1800, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1876, '1875', 1800, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1877, '1876', 1800, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1878, '1877', 1800, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1879, '1878', 1801, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1880, '1879', 1801, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1881, '1880', 1801, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1882, '1881', 1801, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1883, '1882', 1801, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1884, '1883', 1801, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1885, '1884', 1801, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1886, '1885', 1802, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1887, '1886', 1802, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1888, '1887', 1802, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1889, '1888', 1802, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1890, '1889', 1802, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1891, '1890', 1802, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1892, '1891', 1802, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1893, '1892', 1802, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1894, '1893', 1802, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1895, '1894', 1802, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1896, '1895', 1803, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1897, '1896', 1803, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1898, '1897', 1803, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1899, '1898', 1803, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1900, '1899', 1803, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1901, '1900', 1803, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1902, '1901', 1818, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1903, '1902', 1818, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1904, '1903', 1818, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1905, '1904', 1818, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1906, '1905', 1818, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1907, '1906', 1818, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1908, '1907', 1818, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1909, '1908', 1818, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1910, '1909', 1832, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1911, '1910', 1832, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1912, '1911', 1832, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1913, '1912', 1832, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1914, '1913', 1832, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1915, '1914', 1833, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1916, '1915', 1833, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1917, '1916', 1833, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1918, '1917', 1833, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1919, '1918', 1833, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1920, '1919', 1833, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1921, '1920', 1833, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1922, '1921', 1834, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1923, '1922', 1834, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1924, '1923', 1834, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1925, '1924', 1834, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1926, '1925', 1834, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1927, '1926', 1835, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1928, '1927', 1835, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1929, '1928', 1835, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1930, '1929', 1835, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1931, '1930', 1835, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1932, '1931', 1835, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1933, '1932', 1835, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1934, '1933', 1835, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1935, '1934', 1836, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1936, '1935', 1836, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1937, '1936', 1836, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1938, '1937', 1836, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1939, '1938', 1837, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1940, '1939', 1837, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1941, '1940', 1837, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1942, '1941', 1837, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1943, '1942', 1837, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1944, '1943', 1837, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1945, '1944', 1837, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1946, '1945', 1837, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1947, '1946', 1837, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1948, '1947', 1838, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1949, '1948', 1838, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1950, '1949', 1838, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1951, '1950', 1838, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1952, '1951', 1948, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1953, '1952', 1948, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1954, '1953', 1948, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1955, '1954', 1948, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1956, '1955', 1948, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1957, '1956', 1948, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1958, '1957', 1948, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1959, '1958', 1951, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1960, '1959', 1951, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1961, '1960', 1951, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1962, '1961', 1951, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1963, '1962', 1951, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1964, '1963', 1951, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1965, '1964', 1951, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1966, '1965', 1839, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1967, '1966', 1839, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1968, '1967', 1839, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1969, '1968', 1835, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1970, '1969', 1840, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1971, '1970', 1840, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1972, '1971', 1840, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1973, '1972', 1840, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1974, '1973', 1840, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1975, '1974', 1840, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1976, '1975', 1840, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1977, '1976', 1840, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1978, '1977', 1841, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1979, '1978', 1841, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1980, '1979', 1841, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1981, '1980', 1841, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1982, '1981', 1841, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1983, '1982', 1841, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1984, '1983', 1842, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1985, '1984', 1842, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1986, '1985', 1842, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1987, '1986', 1842, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1988, '1987', 1842, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1989, '1988', 1842, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1990, '1989', 1843, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1991, '1990', 1843, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1992, '1991', 1843, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1993, '1992', 1843, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1994, '1993', 1843, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1995, '1994', 1843, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1996, '1995', 1843, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1997, '1996', 1843, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1998, '1997', 1843, 0, '1', 1);
INSERT INTO `noeuds` VALUES (1999, '1998', 1843, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2000, '1999', 1845, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2001, '2000', 1845, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2002, '2001', 1845, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2003, '2002', 1845, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2004, '2003', 1845, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2005, '2004', 1845, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2006, '2005', 1846, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2007, '2006', 1846, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2008, '2007', 1846, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2009, '2008', 1846, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2010, '2009', 1846, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2011, '2010', 1847, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2012, '2011', 1847, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2013, '2012', 1847, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2014, '2013', 1847, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2015, '2014', 1847, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2016, '2015', 1847, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2017, '2016', 1847, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2018, '2017', 1847, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2019, '2018', 1847, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2020, '2019', 1847, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2021, '2020', 1847, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2022, '2021', 1381, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2023, '2022', 1698, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2024, '2023', 1787, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2025, '2024', 1698, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2026, '2025', 1787, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2027, '2026', 1698, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2028, '2027', 1787, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2029, '2028', 1503, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2030, '2029', 2032, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2031, '2030', 2032, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2032, '2031', 1653, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2034, '2033', 1554, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2035, '2034', 2046, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2036, '2035', 2046, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2037, '2036', 1787, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2039, '2038', 1937, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2040, '2039', 1731, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2043, '2042', 1691, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2044, '2043', 1424, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2045, '2044', 1394, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2046, '2045', 1394, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2047, '2046', 2046, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2048, '2047', 2046, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2049, '2048', 2046, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2050, '2049', 2046, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2051, '2050', 2046, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2052, '2051', 2046, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2053, '2052', 2046, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2054, '2053', 2049, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2055, '2054', 1969, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2056, '2055', 1912, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2057, '2056', 1593, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2058, '2057', 1593, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2059, '2058', 1593, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2060, '2059', 1593, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2061, '2060', 1593, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2062, '2061', 1593, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2063, '2062', 1593, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2064, '2063', 1593, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2065, '2064', 1593, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2066, '2065', 1982, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2067, '2066', 1830, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2068, '2067', 1455, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2069, '2068', 1936, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2070, '2069', 1945, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2071, '2070', 1554, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2072, '2071', 1554, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2074, '2073', 2051, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2075, '2074', 1652, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2076, '2075', 2125, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2077, '2076', 1984, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2078, '2077', 1442, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2079, '2078', 2082, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2080, '2079', 2082, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2081, '2080', 2082, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2082, '2081', 1550, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2083, '2082', 1954, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2084, '2083', 2035, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2085, '2084', 1708, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2086, '2085', 1503, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2087, '2086', 2086, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2088, '2087', 1808, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2089, '2088', 2036, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2090, '2089', 2089, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2092, '2091', 1984, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2093, '2092', 1944, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2094, '2093', 2125, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2095, '2094', 1425, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2096, '2095', 1426, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2097, '2096', 1427, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2098, '2097', 1937, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2099, '2098', 1428, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2100, '2099', 1915, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2101, '2100', 1599, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2102, '2101', 1599, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2103, '2102', 1599, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2104, '2103', 1599, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2105, '2104', 1599, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2106, '2105', 1599, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2107, '2106', 1599, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2108, '2107', 2036, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2109, '2108', 1606, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2110, '2109', 1445, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2111, '2110', 2049, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2112, '2111', 1420, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2113, '2112', 2051, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2114, '2113', 1911, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2115, '2114', 1914, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2116, '2115', 1777, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2117, '2116', 1810, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2118, '2117', 1981, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2119, '2118', 1922, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2120, '2119', 1383, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2121, '2120', 2120, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2122, '2121', 1944, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2123, '2122', 1934, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2124, '2123', 2048, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2125, '2124', 1780, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2126, '2125', 2128, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2127, '2126', 2128, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2128, '2127', 2125, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2129, '2128', 1758, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2130, '2129', 2129, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2131, '2130', 2129, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2132, '2131', 1694, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2135, '2134', 1378, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2136, '2135', 2135, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2137, '2136', 1608, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2138, '2137', 1975, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2139, '2138', 2140, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2140, '2139', 1639, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2141, '2140', 1639, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2142, '2141', 1397, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2143, '2142', 1917, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2144, '2143', 1917, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2145, '2144', 1913, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2146, '2145', 2158, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2147, '2146', 1394, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2148, '2147', 2158, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2150, '2149', 2157, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2151, '2150', 1919, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2152, '2151', 2147, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2153, '2152', 2147, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2154, '2153', 2147, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2155, '2154', 2147, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2156, '2155', 2147, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2157, '2156', 2147, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2158, '2157', 2147, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2159, '2158', 1399, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2160, '2159', 1399, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2161, '2160', 1399, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2162, '2161', 1526, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2163, '2162', 1526, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2164, '2163', 1515, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2165, '2164', 1515, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2166, '2165', 1522, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2167, '2166', 1520, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2168, '2167', 1520, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2169, '2168', 1520, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2170, '2169', 1378, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2171, '2170', 1522, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2172, '2171', 2170, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2173, '2172', 1981, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2174, '2173', 1993, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2175, '2174', 1401, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2177, '2176', 1389, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2179, '2178', 2359, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2180, '2179', 1618, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2181, '2180', 2135, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2182, '2181', 1405, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2183, '2182', 1621, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2184, '2183', 1405, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2185, '2184', 1400, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2186, '2185', 1400, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2188, '2187', 1825, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2189, '2188', 1805, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2190, '2189', 1983, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2191, '2190', 1612, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2192, '2191', 2185, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2193, '2192', 2047, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2194, '2193', 2049, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2196, '2195', 1733, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2197, '2196', 2196, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2198, '2197', 2197, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2200, '2199', 1378, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2201, '2200', 2200, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2203, '2202', 1386, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2204, '2203', 1999, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2205, '2204', 1780, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2206, '2205', 2205, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2207, '2206', 2205, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2208, '2207', 1954, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2209, '2208', 1982, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2210, '2209', 1998, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2211, '2210', 1844, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2212, '2211', 2205, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2213, '2212', 1993, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2214, '2213', 1613, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2215, '2214', 2214, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2216, '2215', 2214, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2217, '2216', 1635, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2218, '2217', 1638, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2219, '2218', 1621, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2220, '2219', 2219, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2221, '2220', 2219, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2222, '2221', 1615, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2223, '2222', 1631, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2224, '2223', 2125, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2225, '2224', 2125, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2226, '2225', 1408, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2227, '2226', 1764, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2228, '2227', 2368, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2229, '2228', 1489, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2231, '2230', 1486, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2232, '2231', 1401, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2233, '2232', 1490, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2235, '2234', 1396, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2236, '2235', 1613, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2237, '2236', 1405, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2238, '2237', 1850, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2239, '2238', 1405, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2240, '2239', 1402, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2242, '2241', 1490, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2244, '2243', 1848, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2245, '2244', 2359, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2246, '2245', 1401, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2248, '2247', 1490, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2250, '2249', 1490, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2252, '2251', 1525, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2253, '2252', 2252, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2254, '2253', 1545, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2255, '2254', 1551, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2256, '2255', 1605, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2257, '2256', 1611, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2258, '2257', 1611, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2259, '2258', 1571, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2260, '2259', 1405, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2261, '2260', 1683, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2262, '2261', 1766, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2264, '2263', 1731, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2265, '2264', 1551, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2266, '2265', 2265, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2267, '2266', 2171, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2268, '2267', 1525, 1643, '1', 1);
INSERT INTO `noeuds` VALUES (2269, '2268', 1525, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2270, '2269', 1525, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2271, '2270', 1551, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2272, '2271', 2271, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2273, '2272', 2275, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2274, '2273', 2275, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2275, '2274', 2277, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2276, '2275', 1778, 1643, '1', 1);
INSERT INTO `noeuds` VALUES (2277, '2276', 1551, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2278, '2277', 1549, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2279, '2278', 1731, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2280, '2279', 1546, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2281, '2280', 1546, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2282, '2281', 1546, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2283, '2282', 1571, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2284, '2283', 1555, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2285, '2284', 1555, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2286, '2285', 1555, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2287, '2286', 1718, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2288, '2287', 1982, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2289, '2288', 2203, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2290, '2289', 2172, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2291, '2290', 1725, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2292, '2291', 1850, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2293, '2292', 1805, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2294, '2293', 1759, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2295, '2294', 1474, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2297, '2296', 1648, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2298, '2297', 1656, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2299, '2298', 1731, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2300, '2299', 1471, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2302, '2301', 1471, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2303, '2302', 1764, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2304, '2303', 1916, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2306, '2305', 1396, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2307, '2306', 1917, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2308, '2307', 1780, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2309, '2308', 2308, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2310, '2309', 1650, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2311, '2310', 1774, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2312, '2311', 1805, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2313, '2312', 2205, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2314, '2313', 1711, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2315, '2314', 1711, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2316, '2315', 1711, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2317, '2316', 1711, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2318, '2317', 1621, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2319, '2318', 1854, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2320, '2319', 1658, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2321, '2320', 2320, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2322, '2321', 1658, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2324, '2323', 1879, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2325, '2324', 1894, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2326, '2325', 1785, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2327, '2326', 1764, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2328, '2327', 1496, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2329, '2328', 1496, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2330, '2329', 2032, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2332, '2331', 1513, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2335, '2334', 1655, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2336, '2335', 1770, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2337, '2336', 2336, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2338, '2337', 2185, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2339, '2338', 2185, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2340, '2339', 2185, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2341, '2340', 1657, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2342, '2341', 1396, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2343, '2342', 1513, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2345, '2344', 1495, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2346, '2345', 1388, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2347, '2346', 1656, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2348, '2347', 1686, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2349, '2348', 1648, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2350, '2349', 2308, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2352, '2351', 1703, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2353, '2352', 1490, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2354, '2353', 1780, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2356, '2355', 2359, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2358, '2357', 1490, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2359, '2358', 1490, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2361, '2360', 2125, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2362, '2361', 1490, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2364, '2363', 1490, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2366, '2365', 1490, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2368, '2367', 1489, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2369, '2368', 2308, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2371, '2370', 1771, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2372, '2371', 1662, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2373, '2372', 1648, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2374, '2373', 1656, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2375, '2374', 1688, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2376, '2375', 1650, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2377, '2376', 2125, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2378, '2377', 1758, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2380, '2379', 1633, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2381, '2380', 1400, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2382, '2381', 2185, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2383, '2382', 1613, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2384, '2383', 2205, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2385, '2384', 2384, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2386, '2385', 2205, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2387, '2386', 2386, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2388, '2387', 2386, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2389, '2388', 1904, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2390, '2389', 1810, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2391, '2390', 2205, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2392, '2391', 1899, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2393, '2392', 1861, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2394, '2393', 1652, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2395, '2394', 1652, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2396, '2395', 2203, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2397, '2396', 1780, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2398, '2397', 1976, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2399, '2398', 1693, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2400, '2399', 2399, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2402, '2401', 1721, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2403, '2402', 1787, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2404, '2403', 2120, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2405, '2404', 1731, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2406, '2405', 1656, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2407, '2406', 1729, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2408, '2407', 2205, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2409, '2408', 1935, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2410, '2409', 1392, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2411, '2410', 2205, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2412, '2411', 2411, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2413, '2412', 1780, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2414, '2413', 2125, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2416, '2415', 2373, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2418, '2417', 2373, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2420, '2419', 1616, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2422, '2421', 1780, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2423, '2422', 2125, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2424, '2423', 2125, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2425, '2424', 2185, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2426, '2425', 2185, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2427, '2426', 2185, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2428, '2427', 1657, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2429, '2428', 2428, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2430, '2429', 2429, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2431, '2430', 1466, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2432, '2431', 1765, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2438, '2437', 1923, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2439, '2438', 1925, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2440, '2439', 1921, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2441, '2440', 1903, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2442, '2441', 1466, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2443, '2442', 1819, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2444, '2443', 1466, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2445, '2444', 1831, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2446, '2445', 1620, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2447, '2446', 1616, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2448, '2447', 2447, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2449, '2448', 1616, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2451, '2450', 2022, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2452, '2451', 2451, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2454, '2453', 2451, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2456, '2455', 1394, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2457, '2456', 1618, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2458, '2457', 2457, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2460, '2459', 1618, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2461, '2460', 1612, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2462, '2461', 2036, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2463, '2462', 1455, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2464, '2463', 1828, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2465, '2464', 1765, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2466, '2465', 2465, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2467, '2466', 2465, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2469, '2468', 2457, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2471, '2470', 2472, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2472, '2471', 1618, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2473, '2472', 2457, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2475, '2474', 1618, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2476, '2475', 1389, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2477, '2476', 1716, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2478, '2477', 2086, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2479, '2478', 1420, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2480, '2479', 1726, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2481, '2480', 1726, 2480, '1', 1);
INSERT INTO `noeuds` VALUES (2482, '2481', 1726, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2483, '2482', 1726, 2482, '1', 1);
INSERT INTO `noeuds` VALUES (2485, '2484', 1764, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2486, '2485', 1764, 2485, '1', 1);
INSERT INTO `noeuds` VALUES (2487, '2486', 1764, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2488, '2487', 1379, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2489, '2488', 2488, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2490, '2489', 2489, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2491, '2490', 1686, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2492, '2491', 1684, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2493, '2492', 1729, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2494, '2493', 2488, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2495, '2494', 1729, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2496, '2495', 1686, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2497, '2496', 1686, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2498, '2497', 1684, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2499, '2498', 2498, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2500, '2499', 2498, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2501, '2500', 2492, 2502, '1', 1);
INSERT INTO `noeuds` VALUES (2502, '2501', 1787, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2503, '2502', 1787, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2504, '2503', 2502, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2505, '2504', 2503, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2506, '2505', 2503, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2507, '2506', 2505, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2508, '2507', 1765, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2509, '2508', 2508, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2510, '2509', 1767, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2511, '2510', 2484, 1670, '1', 1);
INSERT INTO `noeuds` VALUES (2512, 'NONCLASSES', 1, 0, '0', 1);
INSERT INTO `noeuds` VALUES (2513, '', 1, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2514, '', 1, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2515, '', 1, 0, '1', 1);
INSERT INTO `noeuds` VALUES (2516, '', 1, 0, '1', 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `notice_statut`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Contenu de la table `notice_statut`
-- 

INSERT INTO `notice_statut` VALUES (1, 'àºšà»€àºˆàº²àº°àºˆàº»àº‡àºªàº°àº–àº²àº™àº°àºžàº²àºš', '', 1, 1, 1, 'statutnot1', 0, 0, 1, 0);
INSERT INTO `notice_statut` VALUES (2, 'àº«à»‰àº²àº¡à»ƒàº«à»‰àº¢àº·àº¡', '', 0, 1, 1, 'statutnot2', 1, 0, 1, 0);
INSERT INTO `notice_statut` VALUES (3, 'àºªàº±à»ˆàº‡à»€àº‚àº»à»‰àº²àº¢àº¹à»ˆ', 'commandé', 1, 1, 1, 'statutnot4', 0, 0, 1, 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `notices`
-- 

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
  `lang_code` char(3) NOT NULL default '',
  `org_lang_code` char(3) NOT NULL default '',
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
  PRIMARY KEY  (`notice_id`),
  KEY `typdoc` (`typdoc`),
  KEY `tparent_id` (`tparent_id`),
  KEY `ed1_id` (`ed1_id`),
  KEY `ed2_id` (`ed2_id`),
  KEY `coll_id` (`coll_id`),
  KEY `subcoll_id` (`subcoll_id`),
  KEY `cb` (`code`),
  KEY `indexint` (`indexint`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=66 ;

-- 
-- Contenu de la table `notices`
-- 

INSERT INTO `notices` VALUES (1, 'a', 'Bac en poche', '', '', '', 0, '', 1, 0, 1, 0, '1997', '', '', '2-211-04459-X', '166 p.', 'couv. ill. en coul.', '19 cm', '', '', 'La terre est devenue plate comme on se l''imaginait au Moyen Age et il ne faut pas trop s''approcher du bord... -- Services Documentaires Multimédia, C''était, évidemment, une journée magnifique. Une journée pour le Catalogue de la Compagnie. A ce moment là, le bureau de Kin donnait sur un lagon frangé de palmiers. Des rouleaux se brisaient contre les récifs...', '', 'fre', '', '', '', '', 85, '  ', '  ', 'm', '0', 0, '', '  ', ' terre est devenue plate comme on se imaginait moyen age il ne faut pas trop s approcher bord services documentaires multimedia c etait evidemment journee magnifique journee pour catalogue compagnie ce moment bureau kin donnait sur lagon frange palmiers rouleaux se brisaient contre recifs ', '  ', ' bac poche ', ' Bac en poche   ', 1, '', '2005-01-01 00:00:00', '2005-06-22 23:15:26');
INSERT INTO `notices` VALUES (2, 'a', 'Catfish blues', '', '', '', 0, '', 2, 0, 2, 0, '1997', '', '', '2-02-029506-7', '154 p.', 'couv. ill. en coul.', '20 cm', '', 'Bibliogr., discogr. des oeuvres de l''auteur, 2 p.', 'Catfish Blues, 12 ans, n''a qu''une idée en tête: fuir la plantation de coton où travaillent ses frères et soeurs et suivre son oncle Eddie pour devenir chanteur de blues à Chicago. Chronique de la vie d''un petit Noir aux prises avec les escrocs de tout poil, y compris le Ku Klux Klan, ce roman vaut aussi par la vivacité de son écriture. Un beau portrait d''époque. --Laurence Liban, ©Lire', '', 'fre', '', '', '', 'coton blues musique racisme', 0, '  ', ' coton blues musique racisme ', 'm', '0', 0, '', ' bibliogr discogr oeuvres auteur 2 p ', ' catfish blues 12 ans n qu idee tete fuir plantation coton ou travaillent ses freres soeurs suivre son oncle eddie pour devenir chanteur blues chicago chronique vie petit noir prises avec escrocs tout poil y compris ku klux klan ce roman vaut aussi par vivacite son ecriture beau portrait epoque laurence liban lire ', '  ', ' catfish blues ', ' Catfish blues   ', 1, '', '2005-01-01 00:00:00', '2005-06-22 23:15:26');
INSERT INTO `notices` VALUES (3, 'a', 'Trois fêlés et un pendu', '', '', '', 0, '', 3, 0, 3, 0, '1998', '', '', '2-84146-554-3', '29 p.', 'couv. ill. en coul.', '18 cm', '', '', 'Lucien a trouvé un trésor dans une grange abandonnée. Lorsqu''il y retourne avec ses copains, un homme est pendu au centre du bâtiment. Subterfuge pour les faire fuir, le pendu n''est pas mort et le trésor est sien. Grâce à Dédé qui s''est sauvé chercher du secours, le criminel est écroué. -- Services Documentaires Multimédia', '', 'fre', '', '', '', 'copains policier', 81, '  ', ' copains policier ', 'm', '0', 0, '', '  ', ' lucien trouve tresor dans grange abandonnee lorsqu il y retourne avec ses copains homme est pendu centre batiment subterfuge pour faire fuir pendu n est pas mort tresor est sien grace dede qui s est sauve chercher secours criminel est ecroue services documentaires multimedia ', '  ', ' trois feles pendu ', ' Trois fêlés et un pendu   ', 1, '', '2005-01-01 00:00:00', '2005-06-22 23:15:26');
INSERT INTO `notices` VALUES (4, 'a', 'Le dieu devenu homme', '', '', '', 0, '', 4, 0, 4, 0, '1998', '', '', '2-86274-547-2', '125 p.', '', '21 cm', '', 'Recueil de nouvelles', '', 'Quatorze nouvelles dont l''intrigue, ayant un personnage célèbre comme actant, se déroule entre 1820 et 1867. On doit ajouter pour l''intérêt du lecteur que quelques-uns des personnages se nomment Hugo, Lamartine, Vigny, Musset, Balzac... -- Services Documentaires Multimédia', 'fre', '', '', '', 'roman historique fiction nouvelle nouvelles', 85, '  ', ' roman historique fiction nouvelle nouvelles ', 'm', '0', 0, '', ' recueil nouvelles ', '  ', ' quatorze nouvelles dont intrigue ayant personnage celebre comme actant se deroule entre 1820 1867 on doit ajouter pour interet lecteur que quelques uns personnages se nomment hugo lamartine vigny musset balzac services documentaires multimedia ', ' dieu devenu homme ', ' Le dieu devenu homme   ', 1, '', '2005-01-01 00:00:00', '2005-06-22 23:15:26');
INSERT INTO `notices` VALUES (5, 'a', 'Sarah de Cordoue', '', '', '', 0, '', 3, 0, 5, 0, '1997', '', '', '2-84146-452-0', '136 p.', 'couv. ill. en coul.', '21 cm', '', '', 'A défaut de fils, un vieux savant mal en point fait de sa fille aînée son disciple. La grande bibliothèque de Cordoue est interdite aux femmes. Pour accomplir les voeux de son père Sarah accepte de se travestir, de devenir Samuel. Elle reçoit un poème galant d''un jeune étudiant musulman. Qui, de Samuel ou de Sarah aime-t-il? Une belle histoire d''amour avec, en toile de fond, une fresque de Cordoue du 12e siècle. -- Services Documentaires Multimédia', '', 'fre', '', '', '', '', 85, '  ', '  ', 'm', '0', 0, '', '  ', ' defaut fils vieux savant mal point fait sa fille ainee son disciple grande bibliotheque cordoue est interdite femmes pour accomplir voeux son pere sarah accepte se travestir devenir samuel elle recoit poeme galant jeune etudiant musulman qui samuel ou sarah aime il belle histoire amour avec toile fond fresque cordoue 12e siecle services documentaires multimedia ', '  ', ' sarah cordoue ', ' Sarah de Cordoue   ', 1, '', '2005-01-01 00:00:00', '2005-06-22 23:15:26');
INSERT INTO `notices` VALUES (6, 'a', 'Le mille-pattes', '', '', 'roman', 0, '', 5, 0, 6, 0, '1998', '', '', '2-207-24700-7', '233 p.', '', '21 cm', '', '', 'Le titre crypté désigne les onze joueurs de l''équipe de France de football impliqués en ce récit dans une intrigue "alternative" par rapport à l''événement de 1998. -- Services Documentaires Multimédia', '', 'fre', '', '', '', 'bleus champions monde 1998 policier ballon rond', 0, '  ', ' bleus champions monde 1998 policier ballon rond ', 'm', '0', 0, '', '  ', ' titre crypte designe onze joueurs equipe france football impliques ce recit dans intrigue alternative par rapport evenement 1998 services documentaires multimedia ', '  ', ' mille pattes roman ', ' Le mille-pattes   roman', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (7, 'a', 'Le puits', '', '', '', 0, '', 6, 0, 7, 0, '1997', '', '', '2-08-164155-0', '110 p.', 'ill., couv. ill. en coul.', '17 cm', '', '', '', 'La sécheresse a tari tous les puits de la région. Tous sauf celui des Logan, famille de fermiers noirs aisée. Les Logan partagent cette eau précieuse de grand cœur, avec tous. Mais en 1910, dans le Sud américain, les rancœurs contre les Noirs sont tenaces... Tout au long de l''été, la tension monte, aussi accablante que la chaleur.', 'fre', 'ame', '', '', 'coton policier', 82, '  ', ' coton policier ', 'm', '0', 0, '', '  ', '  ', ' secheresse tari tous puits region tous sauf celui logan famille fermiers noirs aisee logan partagent cette eau precieuse grand c ur avec tous mais 1910 dans sud americain ranc urs contre noirs sont tenaces tout long ete tension monte aussi accablante que chaleur ', ' puits ', ' Le puits   ', 1, '', '2005-01-01 00:00:00', '2005-06-22 23:15:26');
INSERT INTO `notices` VALUES (8, 'a', 'Ghetto 9', '', '', '', 1, '1', 7, 0, 8, 0, '1993', '', '', '2-7234-1542-2', '47 p.', 'ill. en coul., couv. ill. en coul.', '33 cm', '', '', '', 'Dans une Afrique futuriste, un consortium exploite à outrance les ressources du continent. Et quand Dayak découvre que l''homme qui dirige cet empire technologique n''est autre que son propre frère, la lutte prend une dimension fascinante. Dayak possède l''énergie et la force des meilleures sagas de S.F. et confirme le talent visionnaire du dessin d''Adamov, qui signe là son premier scénario.', 'fre', '', '', '', '', 77, ' dayak ', '  ', 'm', '0', 0, '', '  ', '  ', ' dans afrique futuriste consortium exploite outrance ressources continent quand dayak decouvre que homme qui dirige cet empire technologique n est autre que son propre frere lutte prend dimension fascinante dayak possede energie force meilleures sagas s f confirme talent visionnaire dessin adamov qui signe son premier scenario ', ' dayak ghetto 9 ', 'Dayak Ghetto 9   ', 1, '', '2005-01-01 00:00:00', '2005-06-22 23:15:26');
INSERT INTO `notices` VALUES (9, 'a', 'Zaks', '', '', '', 1, '3', 7, 0, 8, 0, '1997', '', '', '2-7234-2083-3', '47 p.', 'ill. en coul., couv. ill. en coul.', '32 cm', '', '', '', 'Une fois la ville-mère détruite, implosée, Dayak a réussi à échapper aux intercepteurs.En compagnie de son fidèle P''tit Caille, le voilà au coeur du désert, prêt à se fondre dans cet univers inhospitalier, mais toujours suivi, à quelque distance, par son frère Simon, plus que jamais décidé à le rattraper. Or le Grand Désert est aussi le domaine des Zaks, les hommes-masques aux yeux sanguins. N''est-ce qu''un peuple de légende, où sont-ils vraiment là, cachés dans un dédale de galeries souterraines labyrinthiques ?Dayak ne tardera pas à être fixé . Bientôt résonneront les tambours des traditions humaines les plus anciennes, et les ombres démesurées des silhouettes primitives viendront s''allonger auprès de lui.Grâce à leur intervention, Dayak parviendra à triompher définitivement de son frère, si tant est que la mort, pour les gens de son espèce, soit vraiment une fin...Alors, qui sont au juste ces Zaks qui s''expriment en ces lieux désertiques ?Et si Dayak, d''une certaine façon, finissait par devenir l''un des leurs ?...', 'fre', '', '', '', '', 77, ' dayak ', '  ', 'm', '0', 0, '', '  ', '  ', ' fois ville mere detruite implosee dayak reussi echapper intercepteurs compagnie son fidele p tit caille voila coeur desert pret se fondre dans cet univers inhospitalier mais toujours suivi quelque distance par son frere simon plus que jamais decide rattraper or grand desert est aussi domaine zaks hommes masques yeux sanguins n est ce qu peuple legende ou sont ils vraiment caches dans dedale galeries souterraines labyrinthiques dayak ne tardera pas etre fixe bientot resonneront tambours traditions humaines plus anciennes ombres demesurees silhouettes primitives viendront s allonger aupres lui grace leur intervention dayak parviendra triompher definitivement son frere si tant est que mort pour gens son espece soit vraiment fin alors qui sont juste ces zaks qui s expriment ces lieux desertiques si dayak certaine facon finissait par devenir leurs ', ' dayak zaks ', 'Dayak Zaks   ', 1, '', '2005-01-01 00:00:00', '2005-06-22 23:15:26');
INSERT INTO `notices` VALUES (10, 'a', 'La chambre verte', '', '', '', 1, '2', 7, 0, 8, 0, '1994', '', '', '2-7234-1727-1', '47 p.', 'ill. en coul., couv. ill. en coul.', '32 cm', '', '', '', 'La nouvelle Addis Abeba est née !Fini le ghetto, sa misère et sa crasse... Simon Colbius, leader de la C.P.M., a fait surgir une mégalopole où les techniques les plus sophistiquées côtoient les traditions les plus anciennes.Un monde où P''tit Caille est devenu le valet de chambre du nouveau despote et Cori une courtisane que le nouveau comportement de Dayak trouble à peine.Car Dayak sait maintenant qu''il est le frère de Simon, il a retrouvé dans de lointains souvenirs la vision fugace de cette Chambre verte où, enfants, ils percevaient les formidables changements que leur père orchestrait.Devenu le clone de Simon, Dayak parviendra-t-il à briser les chaînes qui le relient à ce frère qui se rêve maître de l''Afrique et empereur d''un nouvel âge ?', 'fre', '', '', '', '', 77, ' dayak ', '  ', 'm', '0', 0, '', '  ', '  ', ' nouvelle addis abeba est nee fini ghetto sa misere sa crasse simon colbius leader c p m fait surgir megalopole ou techniques plus sophistiquees cotoient traditions plus anciennes monde ou p tit caille est devenu valet chambre nouveau despote cori courtisane que nouveau comportement dayak trouble peine car dayak sait maintenant qu il est frere simon il retrouve dans lointains souvenirs vision fugace cette chambre verte ou enfants ils percevaient formidables changements que leur pere orchestrait devenu clone simon dayak parviendra il briser chaines qui relient ce frere qui se reve maitre afrique empereur nouvel age ', ' dayak chambre verte ', 'Dayak La chambre verte   ', 1, '', '2005-01-01 00:00:00', '2005-06-22 23:15:26');
INSERT INTO `notices` VALUES (11, 'a', 'Le chirurgien hollandais', '', '', '', 2, '', 7, 0, 0, 0, '1996', '', '', '2-7234-2062-0', '47 p.', 'ill. en coul., couv. ill. en coul.', '32 cm', '', '', '', 'Ile de Java, région de Semarang en 1897. Un décor exotique pour une aventure riche en rebondissements. Ironie et drame se conjugent pour ce premier tome d''une nouvelle série qui confirme le talent de Christian Lamquet.\r\nUn chirurgien hollandais a découvert un crâne qu''il cherche à faire authentifier comme l''un des chaînons manquant de l''histoire de l''homme. Autour de lui, des personnages haut en couleurs : savants baroques, veuve éplorée, la guenon Lucy, un journaliste zélé, une femme qui ne rêve que de maternité. Et enfin : Pete, le crâne, lui-même personnage central de ce récit utilisant habilement théosophie, un mélange d''occultisme et de spiritisme, et anthropologie.\r\nParce qu''il est convaincu d''avoir mis à jour des fossiles du fameux "chaînon manquant", Jacob Boos, un chirurgien hollandais en garnison à Semarang, est bien décidé à remuer ciel, terre, océan et bonne conscience pour prouver au monde la réalité de sa découverte...\r\n" Pete " comme il le surnomme affectueusement, était-il vraiment ce petit "homme-singe debout" parcourant la savane voici deux millions d''années ?\r\nPour l''heure, ce qu''il a laissé de lui sommeille dans une valise : quelques bouts de vertèbres, un crâne, un morceau de mâchoire. Si Jacob y investit tous ses espoirs, d''autres vont y engluer les précieuses reliques dans les délires de leurs utopies, de leurs passions ou de leur bêtises...\r\n Et puis tous ces malheurs qui se succèdent...\r\nPete, du fond de sa valise, porterait-il la poisse ?  ', 'fre', '', '', '', 'bande dessinée', 77, ' pithecantrope dans valise ', ' bande dessinee ', 'm', '0', 0, '', '  ', '  ', ' ile java region semarang 1897 decor exotique pour aventure riche rebondissements ironie drame se conjugent pour ce premier tome nouvelle serie qui confirme talent christian lamquet chirurgien hollandais decouvert crane qu il cherche faire authentifier comme chainons manquant histoire homme autour lui personnages haut couleurs savants baroques veuve eploree guenon lucy journaliste zele femme qui ne reve que maternite enfin pete crane lui meme personnage central ce recit utilisant habilement theosophie melange occultisme spiritisme anthropologie parce qu il est convaincu avoir mis jour fossiles fameux chainon manquant jacob boos chirurgien hollandais garnison semarang est bien decide remuer ciel terre ocean bonne conscience pour prouver monde realite sa decouverte pete comme il surnomme affectueusement etait il vraiment ce petit homme singe debout parcourant savane voici deux millions annees pour heure ce qu il laisse lui sommeille dans valise quelques bouts vertebres crane morceau machoire si jacob y investit tous ses espoirs autres vont y engluer precieuses reliques dans delires leurs utopies leurs passions ou leur betises puis tous ces malheurs qui se succedent pete fond sa valise porterait il poisse ', ' pithecantrope dans valise chirurgien hollandais ', 'Le pithécantrope dans la valise Le chirurgien hollandais   ', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (12, 'a', 'La Chrysalide Diaprée', '', '', '', 3, '', 8, 0, 0, 0, '1997', '', '', '2-86967-551-8', 'Cartonné - 48 pages', '', '23 x 32', '', '', '', 'Pour sauver son grand-père qui se meurt, Benjamin doit ramener le Mangecoeur, un papillon funeste, avant l''aube. Poursuivi par des clowns cruels dans une fête foraine où seuls les adultes sont admis, il rencontre Rififi, seul allié dans sa fuite. Mais cet ami et son compagnon Pantagas utilisent Benjamin à des fins peu louables... Le rêve et la magie règnent en maîtres dans ce conte merveilleux peuplé de clowns dangereusement malicieux. Le trait brillant de Jean-Baptiste Andréae s''associe au scénario plus qu''efficace de Mathieu Gallié.  ', 'fre', '', '', '', 'bande dessinée', 0, ' mange coeur ', ' bande dessinee ', 'm', '0', 1, 'EUR 11,40', '  ', '  ', ' pour sauver son grand pere qui se meurt benjamin doit ramener mangecoeur papillon funeste avant aube poursuivi par clowns cruels dans fete foraine ou seuls adultes sont admis il rencontre rififi seul allie dans sa fuite mais cet ami son compagnon pantagas utilisent benjamin fins peu louables reve magie regnent maitres dans ce conte merveilleux peuple clowns dangereusement malicieux trait brillant jean baptiste andreae s associe scenario plus qu efficace mathieu gallie ', ' mange coeur chrysalide diapree ', 'Mange-coeur La Chrysalide Diaprée   ', 1, '', '2005-01-01 00:00:00', '2005-06-22 23:15:26');
INSERT INTO `notices` VALUES (13, 'a', 'Monsieur Je-sais-tout', '', '', '', 4, '8', 9, 0, 0, 0, '1998', '', '', '2-8001-2334-6', '48 p.', 'ill. en coul., couv. ill. en coul.', '30 cm', '', '', '', '', 'fre', '', '', '', '', 77, ' jojo ', '  ', 'm', '0', 0, '7,79 €', '  ', '  ', '  ', ' jojo monsieur je sais tout ', 'Jojo Monsieur Je-sais-tout   ', 1, '', '2005-01-01 00:00:00', '2005-06-22 23:15:26');
INSERT INTO `notices` VALUES (14, 'a', 'Paroles de bonheur', '', '', '', 0, '', 10, 0, 11, 0, '1997', '', '', '2-226-09017-7', 'Non paginé [59] p.', 'ill. en coul., couv. ill. en coul.', '22 cm', '', 'Bibliogr., 2 p.', 'Collection anthologique de textes de tous les temps et de tous les pays sur un thème donné. Présentation originale où les écrits sont enclavés dans les illustrations. -- Services Documentaires Multimédia', '', 'fre', '', '', '', '', 81, '  ', '  ', 'm', '0', 0, '9,50 €', ' bibliogr 2 p ', ' collection anthologique textes tous temps tous pays sur theme donne presentation originale ou ecrits sont enclaves dans illustrations services documentaires multimedia ', '  ', ' paroles bonheur ', ' Paroles de bonheur   ', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (15, 'a', 'La métamorphose des fleurs', '', '', '', 0, '', 11, 0, 0, 0, '1997', '', '', '2-7324-2351-3', '143 p. dont 4 dépl.', 'ill. en coul., jaquette ill. en coul.', '30 cm', '', '', '', 'Avec Microcosmos, des milliers de lecteurs se sont émerveillés devant les prodiges insoupçonnés d''un petit carré d''herbes folles. Dans la Métamorphose des Fleurs, Claude Nuridsany et Marie Pérennou sillonnent prairies et jardins et invitent nos yeux inattentifs à se poser sur un bouton gorgé de sève, un pétale qui s''entrouvre ou la chute d''une corolle. Autant de moments phares de la vie flamboyante d''une fleur, architecture de rêve construite et défaite en quelques jours. Transfigurées par la magie de la macrophotographie, une vingtaine d''espèces florales naissent, vivent et meurent sous nos yeux. Marie Pérennou et Claude Nuridsany ont inventé, pour notre plus grand plaisir, un nouveau regard sur la nature, à mi-chemin entre la biologie et la poésie.', 'fre', '', '', '', 'fleur pistil étamine macrophotographie ', 59, '  ', ' fleur pistil etamine macrophotographie ', 'm', '0', 0, '', '  ', '  ', ' avec microcosmos milliers lecteurs se sont emerveilles devant prodiges insoupconnes petit carre herbes folles dans metamorphose fleurs claude nuridsany marie perennou sillonnent prairies jardins invitent nos yeux inattentifs se poser sur bouton gorge seve petale qui s entrouvre ou chute corolle autant moments phares vie flamboyante fleur architecture reve construite defaite quelques jours transfigurees par magie macrophotographie vingtaine especes florales naissent vivent meurent sous nos yeux marie perennou claude nuridsany ont invente pour notre plus grand plaisir nouveau regard sur nature mi chemin entre biologie poesie ', ' metamorphose fleurs ', ' La métamorphose des fleurs   ', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (16, 'a', 'Rwanda', '', '', 'un peuple avec une histoire', 0, '', 12, 0, 0, 0, '1997', '', '', '2-7384-5292-2', '271 p.', '', '22 cm', '', 'En appendice, choix de documents', '', '', 'fre', '', '', '', 'afrique franchophone rwanda histoire colonisation', 97, '  ', ' afrique franchophone rwanda histoire colonisation ', 'm', '0', 0, '', ' appendice choix documents ', '  ', '  ', ' rwanda peuple avec histoire ', ' Rwanda   un peuple avec une histoire', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (17, 'e', 'Carte routière : Ardèche - Haute-Loire, N° 11331', '', '', 'Carte routière et touristique', 0, '', 13, 0, 12, 0, '2002', '331', '', '2-06-100392-3', '25 x 16', '', 'Carte 1/150000 13e édition', '', '', 'Présentation de l''éditeur\r\nLa nouvelle génération des cartes routières Michelin ! Toutes les informations pour découvrir, sur une seule carte, un, deux ou trois départements de France et se repérer facilement en voiture.\r\n\r\nDe nombreuses nouveautés ont été intégrées à la carte « LOCAL » :\r\n- une carte plus lisible grâce à une échelle agrandie : 1/150 000e ou 1/175 000e\r\n- un index complet des localités pour accéder rapidement à l''information\r\n- des plans de ville pour circuler dans les grandes agglomérations\r\n- une légende en image pour bien comprendre les symboles de la carte\r\n- une carte des terroirs\r\nUne nouvelle collection de cartes centrées sur le "LOCAL" pour vivre la route autrement !\r\npixel', '', 'fre', '', '', '', 'bas-vivarais boutières brisadois cévennes haut-vivarais margeriche velay', 92, '  ', ' bas vivarais boutieres brisadois cevennes haut vivarais margeriche velay ', 'm', '0', 1, '', '  ', ' presentation editeur nouvelle generation cartes routieres michelin toutes informations pour decouvrir sur seule carte deux ou trois departements france se reperer facilement voiture nombreuses nouveautes ont ete integrees carte local carte plus lisible grace echelle agrandie 1 150 000e ou 1 175 000e index complet localites pour acceder rapidement information plans ville pour circuler dans grandes agglomerations legende image pour bien comprendre symboles carte carte terroirs nouvelle collection cartes centrees sur local pour vivre route autrement pixel ', '  ', ' carte routiere ardeche haute loire n 11331 carte routiere touristique ', ' Carte routière : Ardèche - Haute-Loire, N° 11331   Carte routière et touristique', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (18, 'g', 'Chili', '', '', 'la glace et le feu', 0, '', 14, 0, 13, 0, '2002 (DL)', '', '', '', '1 DVD vidéo monoface zone 2 (1 h 21 min)', '4/3, coul. (PAL)', '', '1 brochure (14 p. : ill. ; 18 cm)', 'Contient aussi : making of, documentaires ("le monde vu du ciel", "sites du monde", "le monde en fêtes")', '', '', 'fre', '', '', '', 'Chili Pâques', 92, '  ', ' chili paques ', 'm', '0', 0, '', ' contient aussi making of documentaires monde vu ciel sites monde monde fetes ', '  ', '  ', ' chili glace feu ', ' Chili   la glace et le feu', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (19, 'm', 'La Loire Angevine au temps de Joachim du Bellay', '', '', '', 0, '', 15, 0, 0, 0, '2001', '', '', '', '', '', '', '1 disque optique numérique (CD-ROM) : coul., son. ; 12', '', 'Contient un index et un glossaire\r\nConfiguration(s) requise(s) :    \r\nPC ; 16 Mo de mémoire vive ; Windows 95 ou ultérieur ; lecteur de CD-ROM octuple vitesse ; affichage en 65 000 coul. ; carte son ; Macintosh ; 16 Mo de mémoire vive ; système 7.5 ou ultérieur ; lecteur de CD-ROM octuple vitesse ; affichage en 65 000 coul. ; carte son', '', 'fre', '', '', '', 'Du Bellay, Joachim - art de la Renaissance - architecture de la Renaissance - Anjou (France)', 0, '  ', ' bellay joachim art renaissance architecture renaissance anjou france ', 'm', '0', 1, '', '  ', ' contient index glossaire configuration s requise s pc 16 mo memoire vive windows 95 ou ulterieur lecteur cd rom octuple vitesse affichage 65 000 coul carte son macintosh 16 mo memoire vive systeme 7 5 ou ulterieur lecteur cd rom octuple vitesse affichage 65 000 coul carte son ', '  ', ' loire angevine temps joachim bellay ', ' La Loire Angevine au temps de Joachim du Bellay   ', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (26, 'a', 'Australie vue par J.-P. Ferrero', '', '', '', 0, '', 0, 0, 0, 0, '', '', '', '', 'p. 38', '', '', '', '', '', '', 'fre', '', '', '', 'australie aborigï¿½nes dï¿½sert', 92, '', ' australie aborig nes sert ', 'a', '2', 1, '', '  ', '  ', '  ', ' australie vue par j p ferrero ', 'Australie vue par J.-P. Ferrero   ', 1, '', '2005-01-01 00:00:00', '2006-08-28 14:33:55');
INSERT INTO `notices` VALUES (25, 'a', 'Crise en Argentine', '', '', '', 0, '', 0, 0, 0, 0, '', '', '', '', 'p. 18', '', '', '', '', '', '', 'fre', '', '', '', '', 34, '  ', '  ', 'a', '2', 1, '', '  ', '  ', '  ', ' crise argentine ', ' Crise en Argentine   ', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (24, 'a', 'Géo', '', '', 'Un nouveau monde : la Terre', 0, '', 16, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', 'fre', '', '', '', '', 92, '  ', '  ', 's', '1', 1, '', '  ', '  ', '  ', ' geo nouveau monde terre ', ' Géo   Un nouveau monde : la Terre', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (29, 'a', 'Bagnes à Madagascar', '', '', '', 0, '', 0, 0, 0, 0, '', '', '', '', 'p. 49', '', '', '', '', '', '', 'fre', '', '', '', '', 92, '  ', '  ', 'a', '2', 1, '', '  ', '  ', '  ', ' bagnes madagascar ', ' Bagnes à Madagascar   ', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (30, 'a', 'Tatars de Crimée', '', '', '', 0, '', 0, 0, 0, 0, '', '', '', '', '58', '', '', '', '', '', '', 'fre', '', '', '', '', 92, '  ', '  ', 'a', '2', 1, '', '  ', '  ', '  ', ' tatars crimee ', ' Tatars de Crimée   ', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (31, 'a', 'Marigot africain', '', '', '', 0, '', 0, 0, 0, 0, '', '', '', '', 'p. 70', '', '', '', '', '', '', 'fre', '', '', '', '', 0, '  ', '  ', 'a', '2', 1, '', '  ', '  ', '  ', ' marigot africain ', ' Marigot africain   ', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (32, 'a', 'Chateaux de la Loire (2)', '', '', '', 0, '', 0, 0, 0, 0, '', '', '', '', 'p. 126', '', '', '', '', '', '', 'fre', '', '', '', '', 92, '  ', '  ', 'a', '2', 1, '', '  ', '  ', '  ', ' chateaux loire 2 ', ' Chateaux de la Loire (2)   ', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (33, 'a', 'Paysages afghans', '', '', '', 0, '', 0, 0, 0, 0, '', '', '', '', 'p. 26', '', '', '', '', '', '', 'fre', '', '', '', '', 92, '  ', '  ', 'a', '2', 1, '', '  ', '  ', '  ', ' paysages afghans ', ' Paysages afghans   ', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (35, 'a', 'Peuples d''Afghanistan', '', '', '', 0, '', 0, 0, 0, 0, '', '', '', '', 'p. 102', '', '', '', '', '', '', 'fre', '', '', '', '', 92, '  ', '  ', 'a', '2', 1, '', '  ', '  ', '  ', ' peuples afghanistan ', ' Peuples d''Afghanistan   ', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (36, 'a', 'Tribus Pachtounes', '', '', '', 0, '', 0, 0, 0, 0, '', '', '', '', 'p. 72', '', '', '', '', '', '', 'fre', '', '', '', '', 92, '  ', '  ', 'a', '2', 1, '', '  ', '  ', '  ', ' tribus pachtounes ', ' Tribus Pachtounes   ', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (37, 'a', 'femmes afghanes', '', '', '', 0, '', 0, 0, 0, 0, '', '', '', '', 'p. 120', '', '', '', '', '', '', 'fre', '', '', '', '', 0, '  ', '  ', 'a', '2', 1, '', '  ', '  ', '  ', ' femmes afghanes ', ' femmes afghanes   ', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (38, 'a', 'Histoire de l''Afghanistan', '', '', '', 0, '', 0, 0, 0, 0, '', '', '', '', 'p. 64', '', '', '', '', '', '', 'fre', '', '', '', '', 92, '  ', '  ', 'a', '2', 1, '', '  ', '  ', '  ', ' histoire afghanistan ', ' Histoire de l''Afghanistan   ', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (39, 'a', 'Islam afghan', '', '', '', 0, '', 0, 0, 0, 0, '', '', '', '', 'p. 118', '', '', '', '', '', '', 'fre', '', '', '', '', 0, '  ', '  ', 'a', '2', 1, '', '  ', '  ', '  ', ' islam afghan ', ' Islam afghan   ', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (40, 'a', 'Famille Allix', '', '', '', 0, '', 0, 0, 0, 0, '', '', '', '', 'Famille Allix', '', '', '', '', '', '', 'fre', '', '', '', '', 0, '  ', '  ', 'a', '2', 1, '', '  ', '  ', '  ', ' famille allix ', ' Famille Allix   ', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (41, 'a', 'Chateaux de la Loire (1)', '', '', '', 0, '', 0, 0, 0, 0, '', '', '', '', 'p. 126', '', '', '', '', '', '', 'fre', '', '', '', 'chateau loire chenonceau chambord cheverny', 92, '  ', ' chateau loire chenonceau chambord cheverny ', 'a', '2', 1, '', '  ', '  ', '  ', ' chateaux loire 1 ', ' Chateaux de la Loire (1)   ', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (42, 'b', 'Charte du XIIIe siècle, par laquelle Guillaume de Rezay de la paroisse de Ceaux (Maine et Loire) vend à Messire de Vernée, chevalier, sept sous et six deniers de rente.', '', '', 'Acte passé en la cour d''Angers le jeudi avant la Saint Urbain l''an mille deux cent quatre vingt dix neuf.', 0, '', 0, 0, 0, 0, '', '', '', '', 'un feuillet manuscript', '', '', 'a conservé son sceau et son contre-sceau', 'excellent état de conservation', 'date en vieux style (V.ST.) - M. DU POUGET, archiviste-paléographe de l''Indre, a bien voulu attirer mon attention sur le fait que cette charte était datée du joedi devant la Saint Alban (Saint Aubin d''Angers, qui se fête le 1er mars - Pâques tombant en 1299 le 19 avril, il y a effectivement bien lieu de considérer que cette charte est du 25 février 1300, nouveau style (N.ST.)', '', 'fro', '', '', '', 'charte rente archive Ceaux paroisse cens Angers Maine-et-Loire', 95, '  ', ' charte rente archive ceaux paroisse cens angers maine loire ', 'm', '0', 1, '', ' excellent etat conservation ', ' date vieux style v st m pouget archiviste paleographe indre bien voulu attirer mon attention sur fait que cette charte etait datee joedi devant saint alban saint aubin angers qui se fete 1er mars paques tombant 1299 19 avril il y effectivement bien lieu considerer que cette charte est 25 fevrier 1300 nouveau style n st ', '  ', ' charte xiiie siecle par laquelle guillaume rezay paroisse ceaux maine loire vend messire vernee chevalier sept sous six deniers rente acte passe cour angers jeudi avant saint urbain an mille deux cent quatre vingt dix neuf ', ' Charte du XIIIe siècle, par laquelle Guillaume de Rezay de la paroisse de Ceaux (Maine et Loire) vend à Messire de Vernée, chevalier, sept sous et six deniers de rente.   Acte passé en la cour d''Angers le jeudi avant la Saint Urbain l''an mille deux cent quatre vingt dix neuf.', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (44, 'i', 'Bruit de cochon', '', '', '', 0, '', 17, 0, 0, 0, '2004', '', '', '', '2 pistes audios sur cd gravé', '', '12 x 12', '', 'Bruitage courts. Bonne qualité d''enregistrement.', '', '', '', '', 'http://soundfishing.jexiste.fr/bruitages/animaux/cochons%20SF.mp3', 'Fichier MP3', 'cochon porc truie verrat porcelet goret cochette suidés artiodactyles groin', 60, '  ', ' cochon porc truie verrat porcelet goret cochette suides artiodactyles groin ', 'm', '0', 1, '', ' bruitage courts bonne qualite enregistrement ', '  ', '  ', ' bruit cochon ', ' Bruit de cochon   ', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (48, 'r', 'Canne', '', '', 'à pommeau en forme de cochon', 0, '', 0, 0, 0, 0, '', '', '', '', '', '', '85 cm, pommeau de 13 cm', '', 'canne en bois précieux, bichromie, pommeau sculpté et peint', '', '', '', '', '', '', 'canne cochon pied porc pommeau argent ouvrage précieux sculpture\r\n', 69, '  ', ' canne cochon pied porc pommeau argent ouvrage precieux sculpture ', 'm', '0', 1, '', ' canne bois precieux bichromie pommeau sculpte peint ', '  ', '  ', ' canne pommeau forme cochon ', ' Canne   à pommeau en forme de cochon', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (46, 'j', 'L''adagio d''Albinoni', '', '', '', 0, '', 18, 0, 0, 0, '1999', '', '', '', '1 cd audio', '', '', 'livret', '', 'On connaît mal ce compositeur vénitien exactement contemporain de Vivaldi, mais une seule œuvre, pourtant, a assuré sa notoriété, l’Adagio pour cordes, extrait en fait du Concerto en ré majeur. Cette longue cantilène plaintive a servi au film Quatre mariages et un enterrement.', 'Canon de Pachelbel, Jésus que ma joie demeure de J.S. Bach, Andante pour mandoline de Vivaldi, Menuet de Mozart, Menuet de Boccherini', 'fre', '', '', '', '', 79, '  ', '  ', 'm', '0', 1, '', '  ', ' on connait mal ce compositeur venitien exactement contemporain vivaldi mais seule uvre pourtant assure sa notoriete adagio pour cordes extrait fait concerto re majeur cette longue cantilene plaintive servi film quatre mariages enterrement ', ' canon pachelbel jesus que ma joie demeure j s bach andante pour mandoline vivaldi menuet mozart menuet boccherini ', ' adagio albinoni ', ' L''adagio d''Albinoni   ', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (47, 'k', 'Couverture du magazine rustica', '', '', 'Ce que doit être le porc parfait', 0, '', 19, 0, 0, 0, '1953', '', '', '', 'une page fac-similé de la couverture', '', 'in-8', '', '', '', '" Ce que doit être le porc parfait " mentionné en couverture', 'fre', '', '', '', '', 60, '  ', '  ', 'm', '0', 1, '', '  ', '  ', ' ce que doit etre porc parfait mentionne couverture ', ' couverture magazine rustica ce que doit etre porc parfait ', ' Couverture du magazine rustica   Ce que doit être le porc parfait', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (49, 'f', 'Tours. N°65. Flle 78', '', '', '', 0, '', 20, 0, 0, 0, '1765', '', '', '', '1 carte', 'en couleur', '60 x 95 cm', '', 'Carte de Cassini', 'Cote : Ge FF 18595 (65) BNF Richelieu Cartes et Plans Reprod. Sc 96/614\r\n. - Carte levée entre 1760 et 1762 par Bottin, Langelay, vérifiée en 1763 et 1764 par La Briffe Ponsan. Lettre par Chambon. 78e feuille publiée.', '', 'fre', '', 'http://gallica.bnf.fr/scripts/ConsultationTout.exe?O=07711576', 'Images numérisées', 'Tours Indre-et-Loire France', 92, '  ', ' tours indre loire france ', 'm', '0', 1, '', ' carte cassini ', ' cote ge ff 18595 65 bnf richelieu cartes plans reprod sc 96 614 carte levee entre 1760 1762 par bottin langelay verifiee 1763 1764 par briffe ponsan lettre par chambon 78e feuille publiee ', '  ', ' tours n 65 flle 78 ', ' Tours. N°65. Flle 78   ', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (50, 'a', 'Le Cochon d''Hollywood', '', '', '', 0, '', 21, 0, 14, 0, '1985', '', '', '2-07-039125-6', '[62] p.', '', '', '', '', '', '', 'fre', '', '', '', 'cochon porc hollywood acteur studio cinéma', 0, '  ', ' cochon porc hollywood acteur studio cinema ', 'm', '0', 0, '', '  ', '  ', '  ', ' cochon hollywood ', ' Le Cochon d''Hollywood   ', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (51, 'a', 'Le Porc et les produits de la charcuterie, hygiène, inspection, règlementation, par Th. Bourrier,..', '', '', '', 0, '', 22, 0, 0, 0, '1887', '', '', '', 'In-18, XII-585 p.', '', '', '', '', 'Exemples illustrés, gravures représentant une ferme en Indre-et-Loire', '', 'fre', '', '', '', 'Indre-et-Loire ferme porc élevage verrat truie porcelet cochelle', 65, '  ', ' indre loire ferme porc elevage verrat truie porcelet cochelle ', 'm', '0', 0, '', '  ', ' exemples illustres gravures representant ferme indre loire ', '  ', ' porc produits charcuterie hygiene inspection reglementation par th bourrier ', ' Le Porc et les produits de la charcuterie, hygiène, inspection, règlementation, par Th. Bourrier,..   ', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (53, 'a', 'Nimitz', '', '', 'roman', 0, '', 10, 0, 0, 0, '1997', '', '', '2-226-09568-3', '443 p.', 'ill., couv. ill. en coul.', '24 cm', '', '', '', '', 'fre', '', '', '', '', 81, '  ', '  ', 'm', '0', 0, '', '  ', '  ', '  ', ' nimitz roman ', ' Nimitz   roman', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (54, 'a', 'Études archéologiques dans la Loire-Inférieure, ...', '', '', 'Arrondissements de Nantes et de Paimboeuf', 0, '', 23, 0, 0, 0, '1865', '', '', '', '140 p.', 'carte et pl.', 'In-8', '', '', '', '', 'fre', '', '', '', 'Loire-Atlantique', 92, '  ', ' loire atlantique ', 'm', '0', 0, '', '  ', '  ', '  ', ' etudes archeologiques dans loire inferieure arrondissements nantes paimboeuf ', ' Études archéologiques dans la Loire-Inférieure, ...   Arrondissements de Nantes et de Paimboeuf', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (57, 'a', 'Germinal', '', '', '', 0, '', 24, 0, 15, 0, '1995-', '', '', '', '', '', '18 cm', '', '', '', '', 'fre', '', '', '', '', 0, '  ', '  ', 'm', '0', 0, '', '  ', '  ', '  ', ' germinal ', ' Germinal   ', 1, '', '2005-01-01 00:00:00', '2006-06-27 03:17:59');
INSERT INTO `notices` VALUES (58, 'a', 'àºžàº»àº‡àºªàº²àº§àº°àº”àº²àº™àº¥àº²àº§ à»€àº–àº´àº‡ 1946', '', '', '', 0, '', 27, 0, 0, 0, '14/08/2001', '', '', '11586-11592', '277 à»?à»‰àº²', '', '', '', 'àº?à»ˆàº½àº§àº?àº±àºšàº›àº°àº«àº§àº±àº”àºªàº²àº”, à»†àº¥à»†', '', '', 'lao', 'lao', '', '', '', 0, '  ', '  ', 'm', '0', 1, '1200 àº?àºµàºš', '  ', '  ', '  ', ' 1946 ', ' àºžàº»àº‡àºªàº²àº§àº°àº”àº²àº™àº¥àº²àº§ à»€àº–àº´àº‡ 1946   ', 1, 'à»€àº­àº?àº°àºªàº²àº™àº¡àºµà»œà»‰àº­àº? àºšà»?à»ˆàºªàº²àº¡àº²àº”à»ƒàº«à»‰àº¢àº·àº¡à»„àº”à»‰', '2006-08-22 17:45:42', '2006-10-05 17:28:09');
INSERT INTO `notices` VALUES (59, 'a', 'àº—àº»àº”àº¥àº­àº‡', '', '', '', 0, '', 28, 0, 0, 0, '', '', '', '11', '', '', '', '', '', '', '', 'rus', '', '', '', '', 0, '  ', '  ', 'm', '0', 1, '', '  ', '  ', '  ', '  ', ' àº—àº»àº”àº¥àº­àº‡   ', 1, '', '2006-08-22 17:54:39', '2006-10-05 17:41:58');
INSERT INTO `notices` VALUES (60, 'a', 'àº?àº­àº‡àº›àº°àºŠàº¸àº¡àºªàº°àº«àº°àºžàº±àº™àº?àº³àº¡àº°àºšàº²àº™àº¥àº²àº§ IV', '', '', '', 0, '', 26, 0, 0, 0, '2001', '', '1', '11586-11592', '277', '', 'àº›àº·à»‰àº¡', 'DVD', 'àº?àº­àº‡àº›àº°àºŠàº¹àº¡', '', 'àºªàº°àº«àº¼àº¸àºšàºœàº»àº™àºªàº³à»€àº¥àº±àº”àº‚àº­àº‡àº?àº­àº‡àº›àº°àºŠàº¹àº¡', 'lao', 'lao', '', '', 'àº?à»ˆàº½àº§àº?àº±àºšàº?àº­àº‡àº›àº°àºŠàº¹àº¡', 1, '  ', '  ', 'm', '0', 1, '1000', '  ', '  ', '  ', ' iv ', ' àº?àº­àº‡àº›àº°àºŠàº¸àº¡àºªàº°àº«àº°àºžàº±àº™àº?àº³àº¡àº°àºšàº²àº™àº¥àº²àº§ IV   ', 1, 'àºšà»?à»ˆàº¡àºµàº¥àº²àº?àº¥àº°àº­àº½àº”', '2006-08-24 18:10:37', '2006-10-05 17:37:53');
INSERT INTO `notices` VALUES (61, 'a', 'àº§àº´àº¥àº°àº?àº³à»€àºˆàº»à»‰àº²àº­àº°àº™àº¸', '', '', '', 0, '', 28, 0, 0, 0, '', '', '', '', '', '', '', '', 'àº›àº°àº«àº§àº±àº”à»€àºˆàº»à»‰àº²àº­àº²àº™àº¸', '', '', 'lao', '', '', '', 'àº›àº°àº«àº§àº±àº”', 1, NULL, '  ', 's', '1', 1, '', '  ', NULL, ' bravo test reusi ', '  ', 'àº§àº´àº¥àº°àº?àº³à»€àºˆàº»à»‰àº²àº­àº°àº™àº¸   ', 1, 'àº„àº§àº²àº¡àº®àº¹à»‰àº?à»ˆàº½àº§àº?àº±àºšà»€àºˆàº»à»‰àº²àº­àº²àº™àº¸', '2006-08-24 18:22:24', '2006-10-05 17:27:09');
INSERT INTO `notices` VALUES (63, 'a', 'àº?àº²àºšà»€àº¡àº·àº­àº‡àºžàº§àº™', '', '', '', 0, '', 29, 0, 0, 0, '', '', '', '', '121à»œà»‰àº²', '', '', '', 'àº?àº²àºšàº?àº­àº™', '', '', 'lao', 'lao', '', '', '', 1, '  ', '  ', 'm', '0', 1, '24000àº?àºµàºš', '  ', '  ', '  ', '  ', ' àº?àº²àºšà»€àº¡àº·àº­àº‡àºžàº§àº™   ', 1, '', '2006-08-24 18:42:53', '2006-10-05 17:31:58');
INSERT INTO `notices` VALUES (64, 'a', 'àº‚à»?à»‰àº¡àº¹àº™àºªàº³àº®àº­àº‡', '', '', '', 0, '', 29, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, '  ', '  ', 'm', '0', 1, '45000', '  ', '  ', '  ', '  ', ' àº‚à»?à»‰àº¡àº¹àº™àºªàº³àº®àº­àº‡   ', 1, '', '2006-08-24 18:54:00', '2006-10-05 17:43:55');
INSERT INTO `notices` VALUES (65, 'a', 'àº„àº­àº‡à»?àºªàº™à»?àºªàºšàº¢à»ˆàº²àºŠàº³àº®àº­àº?', '', '', '', 0, '', 28, 0, 0, 0, '2001', '', '', '', '29à»œà»‰àº²', '', '', '', '', '', '', 'lao', 'lao', '', '', '', 0, '  ', '  ', 'm', '0', 1, '15000àº?àºµàºš', '  ', '  ', '  ', '  ', ' àº„àº­àº‡à»?àºªàº™à»?àºªàºšàº¢à»ˆàº²àºŠàº³àº®àº­àº?   ', 1, '', '2006-10-05 17:18:46', '2006-10-05 17:18:46');

-- --------------------------------------------------------

-- 
-- Structure de la table `notices_categories`
-- 

CREATE TABLE `notices_categories` (
  `notcateg_notice` int(9) unsigned NOT NULL default '0',
  `num_noeud` int(9) unsigned NOT NULL default '0',
  `num_vedette` int(3) unsigned NOT NULL default '0',
  `ordre_vedette` int(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`notcateg_notice`,`num_noeud`,`num_vedette`),
  KEY `num_noeud` (`num_noeud`),
  KEY `i_notcateg_notice` (`notcateg_notice`),
  KEY `i_num_noeud` (`num_noeud`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `notices_categories`
-- 

INSERT INTO `notices_categories` VALUES (1, 2112, 0, 1);
INSERT INTO `notices_categories` VALUES (2, 2045, 0, 1);
INSERT INTO `notices_categories` VALUES (3, 2045, 0, 1);
INSERT INTO `notices_categories` VALUES (4, 1436, 0, 1);
INSERT INTO `notices_categories` VALUES (5, 1936, 0, 1);
INSERT INTO `notices_categories` VALUES (6, 2045, 0, 1);
INSERT INTO `notices_categories` VALUES (6, 2279, 0, 1);
INSERT INTO `notices_categories` VALUES (7, 1445, 0, 1);
INSERT INTO `notices_categories` VALUES (8, 1414, 0, 1);
INSERT INTO `notices_categories` VALUES (9, 1414, 0, 1);
INSERT INTO `notices_categories` VALUES (10, 1414, 0, 1);
INSERT INTO `notices_categories` VALUES (11, 1391, 0, 1);
INSERT INTO `notices_categories` VALUES (12, 1391, 0, 1);
INSERT INTO `notices_categories` VALUES (13, 1599, 0, 1);
INSERT INTO `notices_categories` VALUES (14, 1655, 0, 1);
INSERT INTO `notices_categories` VALUES (15, 2214, 0, 1);
INSERT INTO `notices_categories` VALUES (16, 1884, 0, 1);
INSERT INTO `notices_categories` VALUES (17, 1748, 0, 1);
INSERT INTO `notices_categories` VALUES (18, 1828, 0, 1);
INSERT INTO `notices_categories` VALUES (19, 1423, 0, 1);
INSERT INTO `notices_categories` VALUES (19, 1447, 0, 1);
INSERT INTO `notices_categories` VALUES (24, 1406, 0, 1);
INSERT INTO `notices_categories` VALUES (25, 1648, 0, 1);
INSERT INTO `notices_categories` VALUES (25, 1830, 0, 1);
INSERT INTO `notices_categories` VALUES (25, 2297, 0, 1);
INSERT INTO `notices_categories` VALUES (26, 1844, 0, 1);
INSERT INTO `notices_categories` VALUES (29, 1899, 0, 1);
INSERT INTO `notices_categories` VALUES (30, 1545, 0, 1);
INSERT INTO `notices_categories` VALUES (31, 1410, 0, 1);
INSERT INTO `notices_categories` VALUES (32, 1748, 0, 1);
INSERT INTO `notices_categories` VALUES (33, 1976, 0, 1);
INSERT INTO `notices_categories` VALUES (35, 1976, 0, 1);
INSERT INTO `notices_categories` VALUES (36, 1976, 0, 1);
INSERT INTO `notices_categories` VALUES (37, 1976, 0, 1);
INSERT INTO `notices_categories` VALUES (38, 1976, 0, 1);
INSERT INTO `notices_categories` VALUES (39, 1721, 0, 1);
INSERT INTO `notices_categories` VALUES (39, 1976, 0, 1);
INSERT INTO `notices_categories` VALUES (41, 1545, 0, 1);
INSERT INTO `notices_categories` VALUES (41, 1748, 0, 1);
INSERT INTO `notices_categories` VALUES (42, 1748, 0, 1);
INSERT INTO `notices_categories` VALUES (44, 1525, 0, 1);
INSERT INTO `notices_categories` VALUES (47, 1525, 0, 1);
INSERT INTO `notices_categories` VALUES (47, 1639, 0, 1);
INSERT INTO `notices_categories` VALUES (48, 1401, 0, 1);
INSERT INTO `notices_categories` VALUES (49, 1740, 0, 1);
INSERT INTO `notices_categories` VALUES (50, 1596, 0, 1);
INSERT INTO `notices_categories` VALUES (51, 2125, 0, 1);
INSERT INTO `notices_categories` VALUES (53, 2110, 0, 1);
INSERT INTO `notices_categories` VALUES (54, 1748, 0, 1);
INSERT INTO `notices_categories` VALUES (58, 2514, 0, 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `notices_custom`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `notices_custom`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `notices_custom_lists`
-- 

CREATE TABLE `notices_custom_lists` (
  `notices_custom_champ` int(10) unsigned NOT NULL default '0',
  `notices_custom_list_value` varchar(255) default NULL,
  `notices_custom_list_lib` varchar(255) default NULL,
  `ordre` int(11) default NULL,
  KEY `notices_custom_champ` (`notices_custom_champ`),
  KEY `noti_champ_list_value` (`notices_custom_champ`,`notices_custom_list_value`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `notices_custom_lists`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `notices_custom_values`
-- 

CREATE TABLE `notices_custom_values` (
  `notices_custom_champ` int(10) unsigned NOT NULL default '0',
  `notices_custom_origine` int(10) unsigned NOT NULL default '0',
  `notices_custom_small_text` varchar(255) default NULL,
  `notices_custom_text` text,
  `notices_custom_integer` int(11) default NULL,
  `notices_custom_date` date default NULL,
  `notices_custom_float` float default NULL,
  KEY `notices_custom_champ` (`notices_custom_champ`),
  KEY `notices_custom_origine` (`notices_custom_origine`),
  KEY `noti_champ_origine` (`notices_custom_champ`,`notices_custom_origine`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `notices_custom_values`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `notices_global_index`
-- 

CREATE TABLE `notices_global_index` (
  `num_notice` mediumint(8) NOT NULL default '0',
  `no_index` mediumint(8) NOT NULL default '0',
  `infos_global` text NOT NULL,
  `index_infos_global` text NOT NULL,
  PRIMARY KEY  (`num_notice`,`no_index`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `notices_global_index`
-- 

INSERT INTO `notices_global_index` VALUES (1, 1, '   Bac en poche      La terre est devenue plate comme on se l''imaginait au Moyen Age et il ne faut pas trop s''approcher du bord... -- Services Documentaires Multimédia, C''était, évidemment, une journée magnifique. Une journée pour le Catalogue de la Compagnie. A ce moment là, le bureau de Kin donnait sur un lagon frangé de palmiers. Des rouleaux se brisaient contre les récifs...  Souton Dominique Roman d''aventure 840 Littérature française Médium L''École des loisirs ', '     bac poche         terre est devenue plate comme on se imaginait moyen age il ne faut pas trop s approcher bord services documentaires multimedia c etait evidemment journee magnifique journee pour catalogue compagnie ce moment bureau kin donnait sur lagon frange palmiers rouleaux se brisaient contre recifs      souton dominique   roman aventure   840 litterature francaise   medium   ecole loisirs  ');
INSERT INTO `notices_global_index` VALUES (2, 1, '   Catfish blues     Bibliogr., discogr. des oeuvres de l''auteur, 2 p. Catfish Blues, 12 ans, n''a qu''une idée en tête: fuir la plantation de coton où travaillent ses frères et soeurs et suivre son oncle Eddie pour devenir chanteur de blues à Chicago. Chronique de la vie d''un petit Noir aux prises avec les escrocs de tout poil, y compris le Ku Klux Klan, ce roman vaut aussi par la vivacité de son écriture. Un beau portrait d''époque. --Laurence Liban, ©Lire coton blues musique racisme Herzhaft Gérard Littérature française Collection dirigée par Claude Gutman Seuil ', '     catfish blues      bibliogr discogr oeuvres auteur 2 p   catfish blues 12 ans n qu idee tete fuir plantation coton ou travaillent ses freres soeurs suivre son oncle eddie pour devenir chanteur blues chicago chronique vie petit noir prises avec escrocs tout poil y compris ku klux klan ce roman vaut aussi par vivacite son ecriture beau portrait epoque laurence liban lire   coton blues musique racisme   herzhaft gerard   litterature francaise   collection dirigee par claude gutman   seuil  ');
INSERT INTO `notices_global_index` VALUES (3, 1, '   Trois fêlés et un pendu      Lucien a trouvé un trésor dans une grange abandonnée. Lorsqu''il y retourne avec ses copains, un homme est pendu au centre du bâtiment. Subterfuge pour les faire fuir, le pendu n''est pas mort et le trésor est sien. Grâce à Dédé qui s''est sauvé chercher du secours, le criminel est écroué. -- Services Documentaires Multimédia copains policier Oppel Jean-Hugues Littérature française 800 Littérature Mini souris noire Syros jeunesse ', '     trois feles pendu         lucien trouve tresor dans grange abandonnee lorsqu il y retourne avec ses copains homme est pendu centre batiment subterfuge pour faire fuir pendu n est pas mort tresor est sien grace dede qui s est sauve chercher secours criminel est ecroue services documentaires multimedia   copains policier   oppel jean hugues   litterature francaise   800 litterature   mini souris noire   syros jeunesse  ');
INSERT INTO `notices_global_index` VALUES (4, 1, '   Le dieu devenu homme    Quatorze nouvelles dont l''intrigue, ayant un personnage célèbre comme actant, se déroule entre 1820 et 1867. On doit ajouter pour l''intérêt du lecteur que quelques-uns des personnages se nomment Hugo, Lamartine, Vigny, Musset, Balzac... -- Services Documentaires Multimédia Recueil de nouvelles  roman historique fiction nouvelle nouvelles Zimmermann Daniel Nouvelles 840 Littérature française Nouvelles le Cherche-Midi éd. ', '     dieu devenu homme   quatorze nouvelles dont intrigue ayant personnage celebre comme actant se deroule entre 1820 1867 on doit ajouter pour interet lecteur que quelques uns personnages se nomment hugo lamartine vigny musset balzac services documentaires multimedia   recueil nouvelles      roman historique fiction nouvelle nouvelles   zimmermann daniel   nouvelles   840 litterature francaise   nouvelles   cherche midi ed  ');
INSERT INTO `notices_global_index` VALUES (5, 1, '   Sarah de Cordoue      A défaut de fils, un vieux savant mal en point fait de sa fille aînée son disciple. La grande bibliothèque de Cordoue est interdite aux femmes. Pour accomplir les voeux de son père Sarah accepte de se travestir, de devenir Samuel. Elle reçoit un poème galant d''un jeune étudiant musulman. Qui, de Samuel ou de Sarah aime-t-il? Une belle histoire d''amour avec, en toile de fond, une fresque de Cordoue du 12e siècle. -- Services Documentaires Multimédia  Causse Rolande Espagne 840 Littérature française Les |uns, les autres Syros jeunesse ', '     sarah cordoue         defaut fils vieux savant mal point fait sa fille ainee son disciple grande bibliotheque cordoue est interdite femmes pour accomplir voeux son pere sarah accepte se travestir devenir samuel elle recoit poeme galant jeune etudiant musulman qui samuel ou sarah aime il belle histoire amour avec toile fond fresque cordoue 12e siecle services documentaires multimedia      causse rolande   espagne   840 litterature francaise   uns autres   syros jeunesse  ');
INSERT INTO `notices_global_index` VALUES (6, 1, '   Le mille-pattes   roman   Le titre crypté désigne les onze joueurs de l''équipe de France de football impliqués en ce récit dans une intrigue "alternative" par rapport à l''événement de 1998. -- Services Documentaires Multimédia bleus champions monde 1998 policier ballon rond Riou Jean-Michel Littérature française Football Sueurs froides Denoël ', '     mille pattes roman         titre crypte designe onze joueurs equipe france football impliques ce recit dans intrigue "alternative" par rapport evenement 1998 services documentaires multimedia   bleus champions monde 1998 policier ballon rond   riou jean michel   litterature francaise   football   sueurs froides   denoel  ');
INSERT INTO `notices_global_index` VALUES (7, 1, '   Le puits    La sécheresse a tari tous les puits de la région. Tous sauf celui des Logan, famille de fermiers noirs aisée. Les Logan partagent cette eau précieuse de grand cœur, avec tous. Mais en 1910, dans le Sud américain, les rancœurs contre les Noirs sont tenaces... Tout au long de l''été, la tension monte, aussi accablante que la chaleur.   coton policier Vassallo-Villaneau Rose-Marie Taylor Mildred D. Littérature américaine 810 Littérature américaine Castor poche Flammarion ', '     puits   secheresse tari tous puits region tous sauf celui logan famille fermiers noirs aisee logan partagent cette eau precieuse grand c ur avec tous mais 1910 dans sud americain ranc urs contre noirs sont tenaces tout long ete tension monte aussi accablante que chaleur         coton policier   vassallo villaneau rose marie   taylor mildred   litterature americaine   810 litterature americaine   castor poche   flammarion  ');
INSERT INTO `notices_global_index` VALUES (8, 1, ' 1 Dayak Ghetto 9    Dans une Afrique futuriste, un consortium exploite à outrance les ressources du continent. Et quand Dayak découvre que l''homme qui dirige cet empire technologique n''est autre que son propre frère, la lutte prend une dimension fascinante. Dayak possède l''énergie et la force des meilleures sagas de S.F. et confirme le talent visionnaire du dessin d''Adamov, qui signe là son premier scénario.    Adamov Philippe Science-fiction 760 Arts graphiques - graphisme Dayak. Glénat ', '  dayak   dayak ghetto 9   dans afrique futuriste consortium exploite outrance ressources continent quand dayak decouvre que homme qui dirige cet empire technologique n est autre que son propre frere lutte prend dimension fascinante dayak possede energie force meilleures sagas s f confirme talent visionnaire dessin adamov qui signe son premier scenario            adamov philippe   science fiction   760 arts graphiques graphisme   dayak   glenat  ');
INSERT INTO `notices_global_index` VALUES (9, 1, ' 3 Dayak Zaks    Une fois la ville-mère détruite, implosée, Dayak a réussi à échapper aux intercepteurs.En compagnie de son fidèle P''tit Caille, le voilà au coeur du désert, prêt à se fondre dans cet univers inhospitalier, mais toujours suivi, à quelque distance, par son frère Simon, plus que jamais décidé à le rattraper. Or le Grand Désert est aussi le domaine des Zaks, les hommes-masques aux yeux sanguins. N''est-ce qu''un peuple de légende, où sont-ils vraiment là, cachés dans un dédale de galeries souterraines labyrinthiques ?Dayak ne tardera pas à être fixé . Bientôt résonneront les tambours des traditions humaines les plus anciennes, et les ombres démesurées des silhouettes primitives viendront s''allonger auprès de lui.Grâce à leur intervention, Dayak parviendra à triompher définitivement de son frère, si tant est que la mort, pour les gens de son espèce, soit vraiment une fin...Alors, qui sont au juste ces Zaks qui s''expriment en ces lieux désertiques ?Et si Dayak, d''une certaine façon, finissait par devenir l''un des leurs ?...    Adamov Philippe Science-fiction 760 Arts graphiques - graphisme Dayak. Glénat ', '  dayak   dayak zaks   fois ville mere detruite implosee dayak reussi echapper intercepteurs compagnie son fidele p tit caille voila coeur desert pret se fondre dans cet univers inhospitalier mais toujours suivi quelque distance par son frere simon plus que jamais decide rattraper or grand desert est aussi domaine zaks hommes masques yeux sanguins n est ce qu peuple legende ou sont ils vraiment caches dans dedale galeries souterraines labyrinthiques dayak ne tardera pas etre fixe bientot resonneront tambours traditions humaines plus anciennes ombres demesurees silhouettes primitives viendront s allonger aupres lui grace leur intervention dayak parviendra triompher definitivement son frere si tant est que mort pour gens son espece soit vraiment fin alors qui sont juste ces zaks qui s expriment ces lieux desertiques si dayak certaine facon finissait par devenir leurs            adamov philippe   science fiction   760 arts graphiques graphisme   dayak   glenat  ');
INSERT INTO `notices_global_index` VALUES (10, 1, ' 2 Dayak La chambre verte    La nouvelle Addis Abeba est née !Fini le ghetto, sa misère et sa crasse... Simon Colbius, leader de la C.P.M., a fait surgir une mégalopole où les techniques les plus sophistiquées côtoient les traditions les plus anciennes.Un monde où P''tit Caille est devenu le valet de chambre du nouveau despote et Cori une courtisane que le nouveau comportement de Dayak trouble à peine.Car Dayak sait maintenant qu''il est le frère de Simon, il a retrouvé dans de lointains souvenirs la vision fugace de cette Chambre verte où, enfants, ils percevaient les formidables changements que leur père orchestrait.Devenu le clone de Simon, Dayak parviendra-t-il à briser les chaînes qui le relient à ce frère qui se rêve maître de l''Afrique et empereur d''un nouvel âge ?    Adamov Philippe Science-fiction 760 Arts graphiques - graphisme Dayak. Glénat ', '  dayak   dayak chambre verte   nouvelle addis abeba est nee fini ghetto sa misere sa crasse simon colbius leader c p m fait surgir megalopole ou techniques plus sophistiquees cotoient traditions plus anciennes monde ou p tit caille est devenu valet chambre nouveau despote cori courtisane que nouveau comportement dayak trouble peine car dayak sait maintenant qu il est frere simon il retrouve dans lointains souvenirs vision fugace cette chambre verte ou enfants ils percevaient formidables changements que leur pere orchestrait devenu clone simon dayak parviendra il briser chaines qui relient ce frere qui se reve maitre afrique empereur nouvel age            adamov philippe   science fiction   760 arts graphiques graphisme   dayak   glenat  ');
INSERT INTO `notices_global_index` VALUES (11, 1, '  Le pithécantrope dans la valise Le chirurgien hollandais    Ile de Java, région de Semarang en 1897. Un décor exotique pour une aventure riche en rebondissements. Ironie et drame se conjugent pour ce premier tome d''une nouvelle série qui confirme le talent de Christian Lamquet.\r\nUn chirurgien hollandais a découvert un crâne qu''il cherche à faire authentifier comme l''un des chaînons manquant de l''histoire de l''homme. Autour de lui, des personnages haut en couleurs : savants baroques, veuve éplorée, la guenon Lucy, un journaliste zélé, une femme qui ne rêve que de maternité. Et enfin : Pete, le crâne, lui-même personnage central de ce récit utilisant habilement théosophie, un mélange d''occultisme et de spiritisme, et anthropologie.\r\nParce qu''il est convaincu d''avoir mis à jour des fossiles du fameux "chaînon manquant", Jacob Boos, un chirurgien hollandais en garnison à Semarang, est bien décidé à remuer ciel, terre, océan et bonne conscience pour prouver au monde la réalité de sa découverte...\r\n" Pete " comme il le surnomme affectueusement, était-il vraiment ce petit "homme-singe debout" parcourant la savane voici deux millions d''années ?\r\nPour l''heure, ce qu''il a laissé de lui sommeille dans une valise : quelques bouts de vertèbres, un crâne, un morceau de mâchoire. Si Jacob y investit tous ses espoirs, d''autres vont y engluer les précieuses reliques dans les délires de leurs utopies, de leurs passions ou de leur bêtises...\r\n Et puis tous ces malheurs qui se succèdent...\r\nPete, du fond de sa valise, porterait-il la poisse ?     bande dessinée Lamquet Chris Bande dessinée 760 Arts graphiques - graphisme Glénat ', '  pithecantrope dans valise   pithecantrope dans valise chirurgien hollandais   ile java region semarang 1897 decor exotique pour aventure riche rebondissements ironie drame se conjugent pour ce premier tome nouvelle serie qui confirme talent christian lamquet chirurgien hollandais decouvert crane qu il cherche faire authentifier comme chainons manquant histoire homme autour lui personnages haut couleurs savants baroques veuve eploree guenon lucy journaliste zele femme qui ne reve que maternite enfin pete crane lui meme personnage central ce recit utilisant habilement theosophie melange occultisme spiritisme anthropologie parce qu il est convaincu avoir mis jour fossiles fameux "chainon manquant" jacob boos chirurgien hollandais garnison semarang est bien decide remuer ciel terre ocean bonne conscience pour prouver monde realite sa decouverte " pete " comme il surnomme affectueusement etait il vraiment ce petit "homme singe debout" parcourant savane voici deux millions annees pour heure ce qu il laisse lui sommeille dans valise quelques bouts vertebres crane morceau machoire si jacob y investit tous ses espoirs autres vont y engluer precieuses reliques dans delires leurs utopies leurs passions ou leur betises puis tous ces malheurs qui se succedent pete fond sa valise porterait il poisse         bande dessinee   lamquet chris   bande dessinee   760 arts graphiques graphisme   glenat  ');
INSERT INTO `notices_global_index` VALUES (12, 1, '  Mange-coeur La Chrysalide Diaprée    Pour sauver son grand-père qui se meurt, Benjamin doit ramener le Mangecoeur, un papillon funeste, avant l''aube. Poursuivi par des clowns cruels dans une fête foraine où seuls les adultes sont admis, il rencontre Rififi, seul allié dans sa fuite. Mais cet ami et son compagnon Pantagas utilisent Benjamin à des fins peu louables... Le rêve et la magie règnent en maîtres dans ce conte merveilleux peuplé de clowns dangereusement malicieux. Le trait brillant de Jean-Baptiste Andréae s''associe au scénario plus qu''efficace de Mathieu Gallié.     bande dessinée Andréaé Jean-Baptiste Gallié Mathieu Bande dessinée Vents d''Ouest ', '  mange coeur   mange coeur chrysalide diapree   pour sauver son grand pere qui se meurt benjamin doit ramener mangecoeur papillon funeste avant aube poursuivi par clowns cruels dans fete foraine ou seuls adultes sont admis il rencontre rififi seul allie dans sa fuite mais cet ami son compagnon pantagas utilisent benjamin fins peu louables reve magie regnent maitres dans ce conte merveilleux peuple clowns dangereusement malicieux trait brillant jean baptiste andreae s associe scenario plus qu efficace mathieu gallie         bande dessinee   andreae jean baptiste   gallie mathieu   bande dessinee   vents ouest  ');
INSERT INTO `notices_global_index` VALUES (13, 1, ' 8 Jojo Monsieur Je-sais-tout        Geerts André Bande dessinée 760 Arts graphiques - graphisme Dupuis ', '  jojo   jojo monsieur je sais tout               geerts andre   bande dessinee   760 arts graphiques graphisme   dupuis  ');
INSERT INTO `notices_global_index` VALUES (14, 1, '   Paroles de bonheur     Bibliogr., 2 p. Collection anthologique de textes de tous les temps et de tous les pays sur un thème donné. Présentation originale où les écrits sont enclavés dans les illustrations. -- Services Documentaires Multimédia  Got Yves Robin Christian Psychologie - Psychanalyse 800 Littérature Paroles A. Michel ', '  jojo   paroles bonheur      bibliogr 2 p   collection anthologique textes tous temps tous pays sur theme donne presentation originale ou ecrits sont enclaves dans illustrations services documentaires multimedia      got yves   robin christian   psychologie psychanalyse   800 litterature   paroles   michel  ');
INSERT INTO `notices_global_index` VALUES (15, 1, '   La métamorphose des fleurs    Avec Microcosmos, des milliers de lecteurs se sont émerveillés devant les prodiges insoupçonnés d''un petit carré d''herbes folles. Dans la Métamorphose des Fleurs, Claude Nuridsany et Marie Pérennou sillonnent prairies et jardins et invitent nos yeux inattentifs à se poser sur un bouton gorgé de sève, un pétale qui s''entrouvre ou la chute d''une corolle. Autant de moments phares de la vie flamboyante d''une fleur, architecture de rêve construite et défaite en quelques jours. Transfigurées par la magie de la macrophotographie, une vingtaine d''espèces florales naissent, vivent et meurent sous nos yeux. Marie Pérennou et Claude Nuridsany ont inventé, pour notre plus grand plaisir, un nouveau regard sur la nature, à mi-chemin entre la biologie et la poésie.   fleur pistil étamine macrophotographie  Pérennou Marie Nuridsany Claude Fleurs 580 Botanique - (les plantes) La Martinière ', '  jojo   metamorphose fleurs   avec microcosmos milliers lecteurs se sont emerveilles devant prodiges insoupconnes petit carre herbes folles dans metamorphose fleurs claude nuridsany marie perennou sillonnent prairies jardins invitent nos yeux inattentifs se poser sur bouton gorge seve petale qui s entrouvre ou chute corolle autant moments phares vie flamboyante fleur architecture reve construite defaite quelques jours transfigurees par magie macrophotographie vingtaine especes florales naissent vivent meurent sous nos yeux marie perennou claude nuridsany ont invente pour notre plus grand plaisir nouveau regard sur nature mi chemin entre biologie poesie         fleur pistil etamine macrophotographie   perennou marie   nuridsany claude   fleurs   580 botanique plantes   martiniere  ');
INSERT INTO `notices_global_index` VALUES (16, 1, '   Rwanda   un peuple avec une histoire  En appendice, choix de documents  afrique franchophone rwanda histoire colonisation Overdulve Cornelis Marinus Rwanda 960 Histoire de l''Afrique l\\''Harmattan ', '  jojo   rwanda peuple avec histoire      appendice choix documents      afrique franchophone rwanda histoire colonisation   overdulve cornelis marinus   rwanda   960 histoire afrique   harmattan  ');
INSERT INTO `notices_global_index` VALUES (17, 1, '   Carte routière : Ardèche - Haute-Loire, N° 11331   Carte routière et touristique   Présentation de l''éditeur\r\nLa nouvelle génération des cartes routières Michelin ! Toutes les informations pour découvrir, sur une seule carte, un, deux ou trois départements de France et se repérer facilement en voiture.\r\n\r\nDe nombreuses nouveautés ont été intégrées à la carte « LOCAL » :\r\n- une carte plus lisible grâce à une échelle agrandie : 1/150 000e ou 1/175 000e\r\n- un index complet des localités pour accéder rapidement à l''information\r\n- des plans de ville pour circuler dans les grandes agglomérations\r\n- une légende en image pour bien comprendre les symboles de la carte\r\n- une carte des terroirs\r\nUne nouvelle collection de cartes centrées sur le "LOCAL" pour vivre la route autrement !\r\npixel bas-vivarais boutières brisadois cévennes haut-vivarais margeriche velay Editions Michelin  Pays de la Loire 910 Géographie - voyages LOCAL Michelin ', '  jojo   carte routiere ardeche haute loire n 11331 carte routiere touristique         presentation editeur nouvelle generation cartes routieres michelin toutes informations pour decouvrir sur seule carte deux ou trois departements france se reperer facilement voiture nombreuses nouveautes ont ete integrees carte local carte plus lisible grace echelle agrandie 1 150 000e ou 1 175 000e index complet localites pour acceder rapidement information plans ville pour circuler dans grandes agglomerations legende image pour bien comprendre symboles carte carte terroirs nouvelle collection cartes centrees sur "local" pour vivre route autrement pixel   bas vivarais boutieres brisadois cevennes haut vivarais margeriche velay   editions michelin   pays loire   910 geographie voyages   local   michelin  ');
INSERT INTO `notices_global_index` VALUES (18, 1, '   Chili   la glace et le feu  Contient aussi : making of, documentaires ("le monde vu du ciel", "sites du monde", "le monde en fêtes")  Chili Pâques Brouwers Pierre Loyola Annabel Brouwers Magali Reuter Philippe Chili 910 Géographie - voyages DVD guides TF1 vidéo éd. ', '  jojo   chili glace feu      contient aussi making of documentaires "monde vu ciel" "sites monde" "monde fetes"      chili paques   brouwers pierre   loyola annabel   brouwers magali   reuter philippe   chili   910 geographie voyages   dvd guides   tf1 video ed  ');
INSERT INTO `notices_global_index` VALUES (19, 1, '   La Loire Angevine au temps de Joachim du Bellay      Contient un index et un glossaire\r\nConfiguration(s) requise(s) :    \r\nPC ; 16 Mo de mémoire vive ; Windows 95 ou ultérieur ; lecteur de CD-ROM octuple vitesse ; affichage en 65 000 coul. ; carte son ; Macintosh ; 16 Mo de mémoire vive ; système 7.5 ou ultérieur ; lecteur de CD-ROM octuple vitesse ; affichage en 65 000 coul. ; carte son Du Bellay, Joachim - art de la Renaissance - architecture de la Renaissance - Anjou (France) Hunot Jean-Yves Altaïr productions multimédia  Prigent Daniel Conseil général du Maine-et-Loire (CG49)  Renaissance Poésie Conseil général du Maine-et-Loire (CG49) ', '  jojo   loire angevine temps joachim bellay         contient index glossaire configuration s requise s pc 16 mo memoire vive windows 95 ou ulterieur lecteur cd rom octuple vitesse affichage 65 000 coul carte son macintosh 16 mo memoire vive systeme 7 5 ou ulterieur lecteur cd rom octuple vitesse affichage 65 000 coul carte son   bellay joachim art renaissance architecture renaissance anjou france   hunot jean yves   altair productions multimedia   prigent daniel   conseil general maine loire cg49   renaissance   poesie   conseil general maine loire cg49  ');
INSERT INTO `notices_global_index` VALUES (26, 1, '   Australie vue par J.-P. Ferrero       australie aborigènes désert  Géo   Un nouveau monde : la Terre Australie 910 Géographie - voyages ', '  jojo   australie vue par j p ferrero            australie aborigenes desert   geo nouveau monde terre   australie   910 geographie voyages  ');
INSERT INTO `notices_global_index` VALUES (25, 1, '   Crise en Argentine         Géo   Un nouveau monde : la Terre Economie Argentine Mondialisation 330 Economie - finances, production, consommation ', '  jojo   crise argentine               geo nouveau monde terre   economie   argentine   mondialisation   330 economie finances production consommation  ');
INSERT INTO `notices_global_index` VALUES (24, 1, '   Géo   Un nouveau monde : la Terre     Géographie 910 Géographie - voyages Prisma Presse ', '  jojo   geo nouveau monde terre               geographie   910 geographie voyages   prisma presse  ');
INSERT INTO `notices_global_index` VALUES (29, 1, '   Bagnes à Madagascar         Géo   Un nouveau monde : la Terre Madagascar 910 Géographie - voyages ', '  jojo   bagnes madagascar               geo nouveau monde terre   madagascar   910 geographie voyages  ');
INSERT INTO `notices_global_index` VALUES (30, 1, '   Tatars de Crimée         Géo   Un nouveau monde : la Terre Voyage 910 Géographie - voyages ', '  jojo   tatars crimee               geo nouveau monde terre   voyage   910 geographie voyages  ');
INSERT INTO `notices_global_index` VALUES (31, 1, '   Marigot africain         Géo   Un nouveau monde : la Terre Afrique ', '  jojo   marigot africain               geo nouveau monde terre   afrique  ');
INSERT INTO `notices_global_index` VALUES (32, 1, '   Chateaux de la Loire (2)         Géo   Un nouveau monde : la Terre Pays de la Loire 910 Géographie - voyages ', '  jojo   chateaux loire 2               geo nouveau monde terre   pays loire   910 geographie voyages  ');
INSERT INTO `notices_global_index` VALUES (33, 1, '   Paysages afghans         Géo   Un nouveau monde : la Terre Afghanistan 910 Géographie - voyages ', '  jojo   paysages afghans               geo nouveau monde terre   afghanistan   910 geographie voyages  ');
INSERT INTO `notices_global_index` VALUES (35, 1, '   Peuples d''Afghanistan         Géo   Un nouveau monde : la Terre Afghanistan 910 Géographie - voyages ', '  jojo   peuples afghanistan               geo nouveau monde terre   afghanistan   910 geographie voyages  ');
INSERT INTO `notices_global_index` VALUES (36, 1, '   Tribus Pachtounes         Géo   Un nouveau monde : la Terre Afghanistan 910 Géographie - voyages ', '  jojo   tribus pachtounes               geo nouveau monde terre   afghanistan   910 geographie voyages  ');
INSERT INTO `notices_global_index` VALUES (37, 1, '   femmes afghanes         Géo   Un nouveau monde : la Terre Afghanistan ', '  jojo   femmes afghanes               geo nouveau monde terre   afghanistan  ');
INSERT INTO `notices_global_index` VALUES (38, 1, '   Histoire de l''Afghanistan         Géo   Un nouveau monde : la Terre Afghanistan 910 Géographie - voyages ', '  jojo   histoire afghanistan               geo nouveau monde terre   afghanistan   910 geographie voyages  ');
INSERT INTO `notices_global_index` VALUES (39, 1, '   Islam afghan         Géo   Un nouveau monde : la Terre Islam Afghanistan ', '  jojo   islam afghan               geo nouveau monde terre   islam   afghanistan  ');
INSERT INTO `notices_global_index` VALUES (40, 1, '   Famille Allix         Géo   Un nouveau monde : la Terre ', '  jojo   famille allix               geo nouveau monde terre  ');
INSERT INTO `notices_global_index` VALUES (41, 1, '   Chateaux de la Loire (1)       chateau loire chenonceau chambord cheverny  Géo   Un nouveau monde : la Terre Voyage Pays de la Loire 910 Géographie - voyages ', '  jojo   chateaux loire 1            chateau loire chenonceau chambord cheverny   geo nouveau monde terre   voyage   pays loire   910 geographie voyages  ');
INSERT INTO `notices_global_index` VALUES (42, 1, '   Charte du XIIIe siècle, par laquelle Guillaume de Rezay de la paroisse de Ceaux (Maine et Loire) vend à Messire de Vernée, chevalier, sept sous et six deniers de rente.   Acte passé en la cour d''Angers le jeudi avant la Saint Urbain l''an mille deux cent quatre vingt dix neuf.  excellent état de conservation date en vieux style (V.ST.) - M. DU POUGET, archiviste-paléographe de l''Indre, a bien voulu attirer mon attention sur le fait que cette charte était datée du joedi devant la Saint Alban (Saint Aubin d''Angers, qui se fête le 1er mars - Pâques tombant en 1299 le 19 avril, il y a effectivement bien lieu de considérer que cette charte est du 25 février 1300, nouveau style (N.ST.) charte rente archive Ceaux paroisse cens Angers Maine-et-Loire Rezay Guillaume de Pays de la Loire 940 Histoire de l''Europe ', '  jojo   charte xiiie siecle par laquelle guillaume rezay paroisse ceaux maine loire vend messire vernee chevalier sept sous six deniers rente acte passe cour angers jeudi avant saint urbain an mille deux cent quatre vingt dix neuf      excellent etat conservation   date vieux style v st m pouget archiviste paleographe indre bien voulu attirer mon attention sur fait que cette charte etait datee joedi devant saint alban saint aubin angers qui se fete 1er mars paques tombant 1299 19 avril il y effectivement bien lieu considerer que cette charte est 25 fevrier 1300 nouveau style n st   charte rente archive ceaux paroisse cens angers maine loire   rezay guillaume   pays loire   940 histoire europe  ');
INSERT INTO `notices_global_index` VALUES (44, 1, '   Bruit de cochon     Bruitage courts. Bonne qualité d''enregistrement.  cochon porc truie verrat porcelet goret cochette suidés artiodactyles groin sound-fishing.net  Mammifères 590 Zoologie - (les animaux) sound-fishing.net ', '  jojo   bruit cochon      bruitage courts bonne qualite enregistrement      cochon porc truie verrat porcelet goret cochette suides artiodactyles groin   sound fishing net   mammiferes   590 zoologie animaux   sound fishing net  ');
INSERT INTO `notices_global_index` VALUES (48, 1, '   Canne   à pommeau en forme de cochon  canne en bois précieux, bichromie, pommeau sculpté et peint  canne cochon pied porc pommeau argent ouvrage précieux sculpture\r\n Favulier Jacques Sculpture 680 Articles manufacturés ', '  jojo   canne pommeau forme cochon      canne bois precieux bichromie pommeau sculpte peint      canne cochon pied porc pommeau argent ouvrage precieux sculpture   favulier jacques   sculpture   680 articles manufactures  ');
INSERT INTO `notices_global_index` VALUES (46, 1, '   L''adagio d''Albinoni    Canon de Pachelbel, Jésus que ma joie demeure de J.S. Bach, Andante pour mandoline de Vivaldi, Menuet de Mozart, Menuet de Boccherini  On connaît mal ce compositeur vénitien exactement contemporain de Vivaldi, mais une seule œuvre, pourtant, a assuré sa notoriété, l’Adagio pour cordes, extrait en fait du Concerto en ré majeur. Cette longue cantilène plaintive a servi au film Quatre mariages et un enterrement.  Marion Alain Bride Philip 780 Musique Forlane ', '     adagio albinoni   canon pachelbel jesus que ma joie demeure j s bach andante pour mandoline vivaldi menuet mozart menuet boccherini      on connait mal ce compositeur venitien exactement contemporain vivaldi mais seule uvre pourtant assure sa notoriete adagio pour cordes extrait fait concerto re majeur cette longue cantilene plaintive servi film quatre mariages enterrement      marion alain   bride philip   780 musique   forlane  ');
INSERT INTO `notices_global_index` VALUES (47, 1, '   Couverture du magazine rustica   Ce que doit être le porc parfait " Ce que doit être le porc parfait " mentionné en couverture    Mammifères Mammifères 590 Zoologie - (les animaux) Rustica ', '  jojo   couverture magazine rustica ce que doit etre porc parfait   " ce que doit etre porc parfait " mentionne couverture            mammiferes   mammiferes   590 zoologie animaux   rustica  ');
INSERT INTO `notices_global_index` VALUES (49, 1, '   Tours. N°65. Flle 78     Carte de Cassini Cote : Ge FF 18595 (65) BNF Richelieu Cartes et Plans Reprod. Sc 96/614\r\n. - Carte levée entre 1760 et 1762 par Bottin, Langelay, vérifiée en 1763 et 1764 par La Briffe Ponsan. Lettre par Chambon. 78e feuille publiée. Tours Indre-et-Loire France Cassini de Thury César-François Centre 910 Géographie - voyages Dépôt de la Guerre ', '  jojo   tours n 65 flle 78      carte cassini   cote ge ff 18595 65 bnf richelieu cartes plans reprod sc 96 614 carte levee entre 1760 1762 par bottin langelay verifiee 1763 1764 par briffe ponsan lettre par chambon 78e feuille publiee   tours indre loire france   cassini thury cesar francois   centre   910 geographie voyages   depot guerre  ');
INSERT INTO `notices_global_index` VALUES (50, 1, '   Le Cochon d''Hollywood       cochon porc hollywood acteur studio cinéma Fraxler Hans Livre Collection Folio benjamin Gallimard ', '  jojo   cochon hollywood            cochon porc hollywood acteur studio cinema   fraxler hans   livre   collection folio benjamin   gallimard  ');
INSERT INTO `notices_global_index` VALUES (51, 1, '   Le Porc et les produits de la charcuterie, hygiène, inspection, règlementation, par Th. Bourrier,..      Exemples illustrés, gravures représentant une ferme en Indre-et-Loire Indre-et-Loire ferme porc élevage verrat truie porcelet cochelle Bourrier Théodore Aliments 640 Arts ménagers - cuisine, coutûre, soins de beauté Asselin et Houzeau ', '  jojo   porc produits charcuterie hygiene inspection reglementation par th bourrier         exemples illustres gravures representant ferme indre loire   indre loire ferme porc elevage verrat truie porcelet cochelle   bourrier theodore   aliments   640 arts menagers cuisine couture soins beaute   asselin houzeau  ');
INSERT INTO `notices_global_index` VALUES (53, 1, '   Nimitz   roman     Langlois-Chassaignon Claudie Robinson Patrick Roman et nouvelle 800 Littérature A. Michel ', '  jojo   nimitz roman               langlois chassaignon claudie   robinson patrick   roman nouvelle   800 litterature   michel  ');
INSERT INTO `notices_global_index` VALUES (54, 1, '   Études archéologiques dans la Loire-Inférieure, ...   Arrondissements de Nantes et de Paimboeuf    Loire-Atlantique Orieux Eugène Pays de la Loire 910 Géographie - voyages impr. de Mme Vve Mellinet ', '  jojo   etudes archeologiques dans loire inferieure arrondissements nantes paimboeuf            loire atlantique   orieux eugene   pays loire   910 geographie voyages   impr mme vve mellinet  ');
INSERT INTO `notices_global_index` VALUES (57, 1, '   Germinal        Pichard Georges Zola Émile BD adultes Média 1000 ', '  jojo   germinal               pichard georges   zola emile   bd adultes   media 1000  ');
INSERT INTO `notices_global_index` VALUES (58, 1, '   àºžàº»àº‡àºªàº²àº§àº°àº”àº²àº™àº¥àº²àº§ à»€àº–àº´àº‡ 1946     àº?à»ˆàº½àº§àº?àº±àºšàº›àº°àº«àº§àº±àº”àºªàº²àº”, à»†àº¥à»†   àºªàº´àº™àº¥àº°àº›àº° à»?àº¥àº°àº§àº±àº”àº—àº°àº™àº°àº—àº³ à»‚àº®àº‡àºžàº´àº¡àº¡àº±àº™àº—àº²àº•àº¸àº¥àº²àº” ', '     1946                    ');
INSERT INTO `notices_global_index` VALUES (65, 1, '   àº„àº­àº‡à»?àºªàº™à»?àºªàºšàº¢à»ˆàº²àºŠàº³àº®àº­àº?        àºªàº°àº–àº²àºšàº±àº™àº„àº»àº™àº„àº§à»‰àº²àº§àº±àº”àº—àº°àº™àº°àº—àº³  àºªàº°àº–àº²àºšàº±àº™ ', '                         ');
INSERT INTO `notices_global_index` VALUES (59, 1, '   àº—àº»àº”àº¥àº­àº‡        àºªàº¹àº™àº?àº²àº‡àºªàº°àº«àº°àºžàº±àº™àº?àº³àº¡àº°àºšàº²àº™àº¥àº²àº§  àºªàº°àº–àº²àºšàº±àº™ ', '                         ');
INSERT INTO `notices_global_index` VALUES (60, 1, '   àº?àº­àº‡àº›àº°àºŠàº¸àº¡àºªàº°àº«àº°àºžàº±àº™àº?àº³àº¡àº°àºšàº²àº™àº¥àº²àº§ IV    àºªàº°àº«àº¼àº¸àºšàºœàº»àº™àºªàº³à»€àº¥àº±àº”àº‚àº­àº‡àº?àº­àº‡àº›àº°àºŠàº¹àº¡ àº?àº­àº‡àº›àº°àºŠàº¹àº¡  àº?à»ˆàº½àº§àº?àº±àºšàº?àº­àº‡àº›àº°àºŠàº¹àº¡ àºªàº¹àº™àº?àº²àº‡àºªàº°àº«àº°àºžàº±àº™àº?àº³àº¡àº°àºšàº²àº™àº¥àº²àº§  000 àº‚à»?à»‰àº¡àº¹àº™ àº?àº²àº™àº•àº´àº”àº•à»?à»ˆàºŠàº·à»ˆàºªàº²àº™ àº™àº°àº„àº­àº™àº«àº¥àº§àº‡ ', '     iv                  000     ');
INSERT INTO `notices_global_index` VALUES (64, 1, '   àº‚à»?à»‰àº¡àº¹àº™àºªàº³àº®àº­àº‡        àº„àº°àº™àº°àº­àº±àº?àºªàº­àº™àºªàº²àº” àº¡/àºŠ  àº«à»?àºžàº´àºžàº´àº—àº°àºžàº±àº™ ', '                         ');
INSERT INTO `notices_global_index` VALUES (61, 1, '  àº§àº´àº¥àº°àº?àº³à»€àºˆàº»à»‰àº²àº­àº°àº™àº¸     àº›àº°àº«àº§àº±àº”à»€àºˆàº»à»‰àº²àº­àº²àº™àº¸  àº›àº°àº«àº§àº±àº” àºªàº¸à»€àº™àº” à»‚àºžàº—àº´àºªàº²àº™  000 àº‚à»?à»‰àº¡àº¹àº™ àº?àº²àº™àº•àº´àº”àº•à»?à»ˆàºŠàº·à»ˆàºªàº²àº™ àºªàº°àº–àº²àºšàº±àº™ ', '      bravo test reusi             000     ');
INSERT INTO `notices_global_index` VALUES (63, 1, '   àº?àº²àºšà»€àº¡àº·àº­àº‡àºžàº§àº™     àº?àº²àºšàº?àº­àº™   àº„àº°àº™àº°àº­àº±àº?àºªàº­àº™àºªàº²àº” àº¡/àºŠ  000 àº‚à»?à»‰àº¡àº¹àº™ àº?àº²àº™àº•àº´àº”àº•à»?à»ˆàºŠàº·à»ˆàºªàº²àº™ àº«à»?àºžàº´àºžàº´àº—àº°àºžàº±àº™ ', '                       000     ');

-- --------------------------------------------------------

-- 
-- Structure de la table `offres_remises`
-- 

CREATE TABLE `offres_remises` (
  `num_fournisseur` int(5) unsigned NOT NULL default '0',
  `num_produit` int(8) unsigned NOT NULL default '0',
  `remise` float(4,2) unsigned NOT NULL default '0.00',
  `condition_remise` text,
  PRIMARY KEY  (`num_fournisseur`,`num_produit`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `offres_remises`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `old_categories`
-- 

CREATE TABLE `old_categories` (
  `categ_id` mediumint(8) unsigned NOT NULL auto_increment,
  `categ_libelle` text NOT NULL,
  `categ_parent` mediumint(8) unsigned NOT NULL default '0',
  `categ_see` mediumint(8) unsigned NOT NULL default '0',
  `categ_comment` text NOT NULL,
  `index_categorie` text,
  PRIMARY KEY  (`categ_id`),
  KEY `categ_see` (`categ_see`),
  KEY `categ_parent` (`categ_parent`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `old_categories`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `old_notices_categories`
-- 

CREATE TABLE `old_notices_categories` (
  `notcateg_notice` int(8) unsigned NOT NULL default '0',
  `notcateg_categorie` int(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`notcateg_notice`,`notcateg_categorie`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `old_notices_categories`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `opac_sessions`
-- 

CREATE TABLE `opac_sessions` (
  `empr_id` int(10) unsigned NOT NULL default '0',
  `session` blob,
  `date_rec` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`empr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `opac_sessions`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `origine_notice`
-- 

CREATE TABLE `origine_notice` (
  `orinot_id` int(8) unsigned NOT NULL auto_increment,
  `orinot_nom` varchar(255) NOT NULL default '',
  `orinot_pays` varchar(255) NOT NULL default 'FR',
  `orinot_diffusion` int(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`orinot_id`),
  KEY `orinot_nom` (`orinot_nom`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Contenu de la table `origine_notice`
-- 

INSERT INTO `origine_notice` VALUES (1, 'Catalogage interne', 'FR', 1);
INSERT INTO `origine_notice` VALUES (2, 'BnF', 'FR', 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `ouvertures`
-- 

CREATE TABLE `ouvertures` (
  `date_ouverture` date NOT NULL default '0000-00-00',
  `ouvert` int(1) NOT NULL default '1',
  `commentaire` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`date_ouverture`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `ouvertures`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `paiements`
-- 

CREATE TABLE `paiements` (
  `id_paiement` int(8) unsigned NOT NULL auto_increment,
  `libelle` varchar(255) NOT NULL default '',
  `commentaire` text NOT NULL,
  PRIMARY KEY  (`id_paiement`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `paiements`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `parametres`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=376 ;

-- 
-- Contenu de la table `parametres`
-- 

INSERT INTO `parametres` VALUES (1, 'pmb', 'bdd_version', 'v4.21', 'Version de noyau de la base de données, à ne changer qu''en version inférieure si un paramètre était mal passé et relancer la mise à jour. En général, contactez plutôt la mailing liste pmb.user@sigb.net', '', 0);
INSERT INTO `parametres` VALUES (2, 'z3950', 'accessible', '1', 'Z3950 accessible ?\r\n 0 : non, menu inaccessible\r\n 1 : Oui, la librairie PHP_YAZ est activée, la recherche z3950 est possible', '', 0);
INSERT INTO `parametres` VALUES (3, 'pmb', 'nb_lastautorities', '10', 'Nombre de dernières autoritées affichées en gestion d''autorités', '', 0);
INSERT INTO `parametres` VALUES (4, 'pdflettreretard', '1before_list', '&#3725;&#3771;&#3713;&#3776;&#3751;&#3761;&#3785;&#3737;&#3714;&#3789;&#3785;&#3740;&#3764;&#3732;&#3742;&#3762;&#3732;&#3714;&#3757;&#3719;&#3735;&#3762;&#3719;&#3776;&#3758;&#3771;&#3762;, &#3735;&#3784;&#3762;&#3737;&#3745;&#3765;&#3754;&#3764;&#3732;&#3779;&#3737;&#3804;&#3766;&#3784;&#3719;&#3755;&#3772;&#3767;&#3755;&#3772;&#3762;&#3725;&#3776;&#3757;&#3713;&#3760;&#3754;&#3762;&#3737; &#3776;&#3722;&#3764;&#3784;&#3719;&#3780;&#3749;&#3725;&#3760;&#3776;&#3751;&#3749;&#3762;&#3714;&#3757;&#3719;&#3713;&#3762;&#3737;&#3779;&#3755;&#3785;&#3746;&#3767;&#3745;&#3777;&#3745;&#3784;&#3737;&#3780;&#3732;&#3785;&#3713;&#3762;&#3725;&#3713;&#3763;&#3737;&#3771;&#3732;&#3745;&#3767;&#3785;&#3737;&#3765;&#3785;', 'Texte apparaissant avant la liste des ouvrages en retard dans le courrier de relance de retard', '', 0);
INSERT INTO `parametres` VALUES (5, 'pdflettreretard', '1after_list', '&#3742;&#3751;&#3713;&#3776;&#3758;&#3771;&#3762;&#3714;&#3789;&#3714;&#3757;&#3738;&#3779;&#3720;&#3737;&#3763;&#3735;&#3784;&#3762;&#3737;&#3735;&#3765;&#3784;&#3720;&#3760;&#3733;&#3764;&#3732;&#3733;&#3789;&#3784;&#3742;&#3751;&#3713;&#3776;&#3758;&#3771;&#3762;&#3778;&#3732;&#3725;&#3735;&#3762;&#3719;&#3778;&#3735;&#3749;&#3760;&#3754;&#3761;&#3738; &#3804;&#3762;&#3725;&#3776;&#3749;&#3713; $biblio_phone &#3755;&#3772;&#3767; &#3778;&#3732;&#3725; email $biblio_email &#3776;&#3742;&#3767;&#3784;&#3757;&#3754;&#3766;&#3713;&#3754;&#3762;&#3716;&#3751;&#3762;&#3745;&#3776;&#3739;&#3761;&#3737;&#3780;&#3739;&#3780;&#3732;&#3785;&#3714;&#3757;&#3719;&#3713;&#3762;&#3737;&#3733;&#3789;&#3784;&#3776;&#3751;&#3749;&#3762;&#3713;&#3762;&#3737;&#3779;&#3755;&#3785;&#3746;&#3767;&#3745; &#3755;&#3772;&#3767;&#3754;&#3771;&#3784;&#3719;&#3776;&#3757;&#3713;&#3760;&#3754;&#3762;&#3737;&#3716;&#3767;&#3737;', 'Texte apparaissant après la liste des ouvrages en retard dans le courrier', '', 0);
INSERT INTO `parametres` VALUES (6, 'pdflettreretard', '1fdp', '&#3740;&#3769;&#3785;&#3758;&#3761;&#3738;&#3740;&#3764;&#3732;&#3722;&#3757;&#3738;.', 'Signataire de la lettre.', '', 0);
INSERT INTO `parametres` VALUES (7, 'pdflettreretard', '1madame_monsieur', '&#3735;&#3784;&#3762;&#3737;&#3725;&#3764;&#3719;, &#3735;&#3784;&#3762;&#3737;&#3722;&#3762;&#3725; ,', 'Entête de la lettre', '', 0);
INSERT INTO `parametres` VALUES (8, 'pdflettreretard', '1nb_par_page', '7', 'Nombre d''ouvrages en retard imprimé sur les pages suivantes.', '', 0);
INSERT INTO `parametres` VALUES (9, 'pdflettreretard', '1nb_1ere_page', '4', 'Nombre d''ouvrages en retard imprimé sur la première page', '', 0);
INSERT INTO `parametres` VALUES (10, 'pdflettreretard', '1taille_bloc_expl', '16', 'Taille d''un bloc (2 lignes) d''ouvrage en retard. Le début de chaque ouvrage en retard sera espacé de cette valeur sur la page', '', 0);
INSERT INTO `parametres` VALUES (11, 'pdflettreretard', '1debut_expl_1er_page', '160', 'Début de la liste des exemplaires sur la première page, en mm depuis le bord supérieur de la page. Doit être règlé en fonction du texte qui précède la liste des ouvrages, lequel peut être plus ou moins long.', '', 0);
INSERT INTO `parametres` VALUES (12, 'pdflettreretard', '1debut_expl_page', '15', 'Début de la liste des exemplaires sur les pages suivantes, en mm depuis le bord supérieur de la page.', '', 0);
INSERT INTO `parametres` VALUES (13, 'pdflettreretard', '1limite_after_list', '270', 'Position limite en bas de page. Si un élément imprimé tente de dépasser cette limite, il sera imprimé sur la page suivante.', '', 0);
INSERT INTO `parametres` VALUES (14, 'pdflettreretard', '1marge_page_gauche', '10', 'Marge de gauche en mm', '', 0);
INSERT INTO `parametres` VALUES (15, 'pdflettreretard', '1marge_page_droite', '10', 'Marge de droite en mm', '', 0);
INSERT INTO `parametres` VALUES (16, 'pdflettreretard', '1largeur_page', '210', 'Largeur de la page en mm', '', 0);
INSERT INTO `parametres` VALUES (17, 'pdflettreretard', '1hauteur_page', '297', 'Hauteur de la page en mm', '', 0);
INSERT INTO `parametres` VALUES (18, 'pdflettreretard', '1format_page', 'P', 'Format de la page : \r\n P : Portrait\r\n L : Landscape = paysage', '', 0);
INSERT INTO `parametres` VALUES (19, 'pdfcartelecteur', 'pos_h', '20', 'Position horizontale en mm à partir du bord gauche de la page', '', 0);
INSERT INTO `parametres` VALUES (20, 'pdfcartelecteur', 'pos_v', '20', 'Position verticale en mm à partir du bord supérieur de la page', '', 0);
INSERT INTO `parametres` VALUES (21, 'pdfcartelecteur', 'biblio_name', '$biblio_name', 'Nom de la bibliothèque ou du centre de ressources imprimé sur la carte de lecteur. Mettre $biblio_name pour reprendre le nom spécifié en localisation d''exemplaire ou bien mettre autre chose.', '', 0);
INSERT INTO `parametres` VALUES (22, 'pdfcartelecteur', 'largeur_nom', '80', 'Largeur accordée à l''impression du nom du lecteur en mm', '', 0);
INSERT INTO `parametres` VALUES (23, 'pdfcartelecteur', 'valabledu', '&#3779;&#3722;&#3785;&#3780;&#3732;&#3785;&#3751;&#3761;&#3737;&#3735;&#3765;&#3784;', '''Valable du'' dans "VALABLE DU ##/##/#### au ##/##/####"', '', 0);
INSERT INTO `parametres` VALUES (24, 'pdfcartelecteur', 'valableau', '&#3755;&#3762;', '''au'' dans "valable du ##/##/#### AU ##/##/####"', '', 0);
INSERT INTO `parametres` VALUES (25, 'pdfcartelecteur', 'carteno', '&#3776;&#3749;&#3713;&#3738;&#3761;&#3732; :', 'Mention précédant le numéro de la carte', '', 0);
INSERT INTO `parametres` VALUES (26, 'sauvegarde', 'cle_crypt1', '9b4a840d790eadc71b9064c9a843719b', '', '', 0);
INSERT INTO `parametres` VALUES (27, 'sauvegarde', 'cle_crypt2', '51580d4fd5f1ad2d981c91ddb04095ec', '', '', 0);
INSERT INTO `parametres` VALUES (28, 'pmb', 'resa_dispo', '1', 'Réservation de documents disponibles possible ?\r\n 0 : Non\r\n 1 : Oui', '', 0);
INSERT INTO `parametres` VALUES (29, 'mailretard', '1objet', '$biblio_name : &#3776;&#3757;&#3713;&#3760;&#3754;&#3762;&#3737;&#3754;&#3771;&#3784;&#3719;&#3722;&#3785;&#3762;', 'Objet du mail de relance de retard', '', 0);
INSERT INTO `parametres` VALUES (30, 'mailretard', '1before_list', '&#3725;&#3771;&#3713;&#3776;&#3751;&#3761;&#3785;&#3737;&#3714;&#3789;&#3785;&#3740;&#3764;&#3732;&#3742;&#3762;&#3732;&#3714;&#3757;&#3719;&#3735;&#3762;&#3719;&#3776;&#3758;&#3771;&#3762;, &#3735;&#3784;&#3762;&#3737;&#3745;&#3765;&#3754;&#3764;&#3732;&#3779;&#3737;&#3804;&#3766;&#3784;&#3719;&#3755;&#3772;&#3767;&#3755;&#3772;&#3762;&#3725;&#3776;&#3757;&#3713;&#3760;&#3754;&#3762;&#3737; &#3776;&#3722;&#3764;&#3784;&#3719;&#3780;&#3749;&#3725;&#3760;&#3776;&#3751;&#3749;&#3762;&#3714;&#3757;&#3719;&#3713;&#3762;&#3737;&#3779;&#3755;&#3785;&#3746;&#3767;&#3745;&#3777;&#3745;&#3784;&#3737;&#3780;&#3732;&#3785;&#3713;&#3762;&#3725;&#3713;&#3763;&#3737;&#3771;&#3732;&#3745;&#3767;&#3785;&#3737;&#3765;&#3785; :', 'Texte apparaissant avant la liste des ouvrages en retard dans le mail de relance de retard', '', 0);
INSERT INTO `parametres` VALUES (31, 'mailretard', '1after_list', '&#3742;&#3751;&#3713;&#3776;&#3758;&#3771;&#3762;&#3714;&#3789;&#3714;&#3757;&#3738;&#3779;&#3720;&#3737;&#3763;&#3735;&#3784;&#3762;&#3737;&#3735;&#3765;&#3784;&#3720;&#3760;&#3733;&#3764;&#3732;&#3733;&#3789;&#3784;&#3742;&#3751;&#3713;&#3776;&#3758;&#3771;&#3762;&#3778;&#3732;&#3725;&#3735;&#3762;&#3719;&#3778;&#3735;&#3749;&#3760;&#3754;&#3761;&#3738; &#3804;&#3762;&#3725;&#3776;&#3749;&#3713; $biblio_phone &#3755;&#3772;&#3767; &#3778;&#3732;&#3725; email $biblio_email &#3776;&#3742;&#3767;&#3784;&#3757;&#3754;&#3766;&#3713;&#3754;&#3762;&#3716;&#3751;&#3762;&#3745;&#3776;&#3739;&#3761;&#3737;&#3780;&#3739;&#3780;&#3732;&#3785;&#3714;&#3757;&#3719;&#3713;&#3762;&#3737;&#3733;&#3789;&#3784;&#3776;&#3751;&#3749;&#3762;&#3713;&#3762;&#3737;&#3779;&#3755;&#3785;&#3746;&#3767;&#3745; &#3755;&#3772;&#3767;&#3754;&#3771;&#3784;&#3719;&#3776;&#3757;&#3713;&#3760;&#3754;&#3762;&#3737;&#3716;&#3767;&#3737;.', 'Texte apparaissant après la liste des ouvrages en retard dans le mail', '', 0);
INSERT INTO `parametres` VALUES (32, 'mailretard', '1madame_monsieur', '&#3735;&#3784;&#3762;&#3737;&#3725;&#3764;&#3719;, &#3735;&#3784;&#3762;&#3737;&#3722;&#3762;&#3725;', 'Entête du mail', '', 0);
INSERT INTO `parametres` VALUES (33, 'mailretard', '1fdp', '&#3740;&#3769;&#3785;&#3758;&#3761;&#3738;&#3740;&#3764;&#3732;&#3722;&#3757;&#3738;.', 'Signataire du mail de relance de retard', '', 0);
INSERT INTO `parametres` VALUES (34, 'pmb', 'serial_link_article', '0', 'Préremplissage du lien des dépouillements avec le lien de la notice mère en catalogage des périodiques ?\r\n 0 : Non\r\n 1 : Oui', '', 0);
INSERT INTO `parametres` VALUES (35, 'pmb', 'num_carte_auto', '1', 'Numéro de carte de lecteur automatique ? \r\n 1 : Oui\r\n 0 : Non (si utilisation de cartes pré-imprimées)', '', 0);
INSERT INTO `parametres` VALUES (36, 'opac', 'modules_search_title', '2', 'Recherche simple dans les titres:\r\n 0 : interdite\r\n 1 : autorisée\r\n 2 : autorisée et validée par défaut', 'c_recherche', 0);
INSERT INTO `parametres` VALUES (37, 'opac', 'modules_search_author', '2', 'Recherche simple dans les auteurs:\r\n 0 : interdite\r\n 1 : autorisée\r\n 2 : autorisée et validée par défaut', 'c_recherche', 0);
INSERT INTO `parametres` VALUES (38, 'opac', 'modules_search_publisher', '1', 'Recherche simple dans les éditeurs:\r\n 0 : interdite\r\n 1 : autorisée\r\n 2 : autorisée et validée par défaut', 'c_recherche', 0);
INSERT INTO `parametres` VALUES (39, 'opac', 'modules_search_collection', '1', 'Recherche simple dans les collections:\r\n 0 : interdite\r\n 1 : autorisée\r\n 2 : autorisée et validée par défaut', 'c_recherche', 0);
INSERT INTO `parametres` VALUES (40, 'opac', 'modules_search_subcollection', '1', 'Recherche simple dans les sous-collections:\r\n 0 : interdite\r\n 1 : autorisée\r\n 2 : autorisée et validée par défaut', 'c_recherche', 0);
INSERT INTO `parametres` VALUES (41, 'opac', 'modules_search_category', '1', 'Recherche simple dans les catégories:\r\n 0 : interdite\r\n 1 : autorisée\r\n 2 : autorisée et validée par défaut', 'c_recherche', 0);
INSERT INTO `parametres` VALUES (42, 'opac', 'modules_search_keywords', '1', 'Recherche simple dans les indexations libres (mots clé):\r\n 0 : interdite\r\n 1 : autorisée\r\n 2 : autorisée et validée par défaut', 'c_recherche', 0);
INSERT INTO `parametres` VALUES (43, 'opac', 'modules_search_abstract', '1', 'Recherche simple dans le champ résumé :\r\n 0 : interdite\r\n 1 : autorisée\r\n 2 : autorisée et validée par défaut', 'c_recherche', 0);
INSERT INTO `parametres` VALUES (44, 'opac', 'modules_search_content', '0', 'Recherche simple dans les notes de contenu:\r\n 0 : interdite\r\n 1 : autorisée\r\n 2 : autorisée et validée par défaut\r\nINUTILISE POUR L''INSTANT', 'c_recherche', 0);
INSERT INTO `parametres` VALUES (45, 'opac', 'categories_categ_path_sep', '>', 'Séparateur pour les catégories', 'i_categories', 0);
INSERT INTO `parametres` VALUES (46, 'opac', 'categories_columns', '3', 'Nombre de colonnes du sommaire général des catégories', 'i_categories', 0);
INSERT INTO `parametres` VALUES (47, 'opac', 'categories_categ_rec_per_page', '6', 'Nombre de notices à afficher par page dans l''exploration des catégories', 'i_categories', 0);
INSERT INTO `parametres` VALUES (48, 'opac', 'categories_categ_sort_records', 'index_serie, tnvol, index_sew', 'Explorateur de catégories : mode de tri des notices :\r\n index_serie, tnvol, index_sew > par titre de série, numéro dans la série et index des titres\r\n rand() : aléatoire', 'i_categories', 0);
INSERT INTO `parametres` VALUES (49, 'opac', 'search_results_first_level', '4', 'Nombre de résulats affichés sur la première page', 'z_unused', 0);
INSERT INTO `parametres` VALUES (50, 'opac', 'search_results_per_page', '10', 'Nombre de résulats affichés sur les pages suivantes', 'd_aff_recherche', 0);
INSERT INTO `parametres` VALUES (51, 'opac', 'authors_aut_rec_per_page', '1', 'Nombre d''auteurs affichés par page', 'd_aff_recherche', 0);
INSERT INTO `parametres` VALUES (52, 'opac', 'categories_sub_display', '3', 'Nombre de sous-categories sur la première page', 'i_categories', 0);
INSERT INTO `parametres` VALUES (53, 'opac', 'categories_sub_mode', 'libelle_categorie', 'Mode affichage des sous-categories : \r\n rand() > aléatoire\r\n libelle_categorie > ordre alpha', 'i_categories', 0);
INSERT INTO `parametres` VALUES (54, 'opac', 'authors_aut_sort_records', 'index_serie, tnvol, index_sew', 'Visu auteurs : tri des notices', 'd_aff_recherche', 0);
INSERT INTO `parametres` VALUES (55, 'opac', 'default_lang', 'la_LA', 'Langue de l''opac : fr_FR ou en_US ou es_ES ou ar ou la_LA', 'a_general', 0);
INSERT INTO `parametres` VALUES (56, 'opac', 'show_categ_browser', '1', 'Affichage des catégories en page d''accueil OPAC 1: oui  ou 0: non', 'f_modules', 0);
INSERT INTO `parametres` VALUES (57, 'opac', 'show_book_pics', '1', 'Afficher les vignettes de livres dans les fiches ouvrages :\r\n 0 : Non\r\n 1 : Oui', 'e_aff_notice', 0);
INSERT INTO `parametres` VALUES (58, 'opac', 'resa', '1', 'Réservations possibles par l''OPAC 1: oui  ou 0: non', 'a_general', 0);
INSERT INTO `parametres` VALUES (59, 'opac', 'resa_dispo', '1', 'Réservations possibles de documents disponibles par l''OPAC \r\n 1: oui \r\n 0: non', 'a_general', 0);
INSERT INTO `parametres` VALUES (60, 'opac', 'show_meteo', '0', 'Affichage de la météo dans l''OPAC 1: oui  ou 0: non', 'f_modules', 0);
INSERT INTO `parametres` VALUES (61, 'opac', 'duration_session_auth', '1200', 'Durée de la session lecteur dans l''OPAC en secondes', 'a_general', 0);
INSERT INTO `parametres` VALUES (62, 'pmb', 'relance_adhesion', '31', 'Nombre de jours avant expiration adhésion pour relance', '', 0);
INSERT INTO `parametres` VALUES (63, 'pmb', 'pret_adhesion_depassee', '1', 'Prêts si adhésion dépassée : 0 INTERDIT incontournable, 1 POSSIBLE', '', 0);
INSERT INTO `parametres` VALUES (64, 'pdflettreadhesion', 'fdp', '&#3740;&#3769;&#3785;&#3758;&#3761;&#3738;&#3740;&#3764;&#3732;&#3722;&#3757;&#3738;.', 'Formule de politesse en bas de page', '', 0);
INSERT INTO `parametres` VALUES (65, 'pdflettreadhesion', 'madame_monsieur', '&#3735;&#3784;&#3762;&#3737;&#3725;&#3764;&#3719;, &#3735;&#3784;&#3762;&#3737;&#3722;&#3762;&#3725; ,', 'Civilité du destinataire', '', 0);
INSERT INTO `parametres` VALUES (66, 'pdflettreadhesion', 'texte', 'Votre abonnement arrive à échéance le !!date_fin_adhesion!!. Nous vous remercions de penser à le renouveller lors de votre prochaine visite.\r\n\r\nNous vous prions de recevoir, Madame, Monsieur, l''expression de nos meilleures salutations.\r\n\r\n\r\n', 'Phrase d''introduction de l''échéance de l''abonnement', '', 0);
INSERT INTO `parametres` VALUES (67, 'pdflettreadhesion', 'marge_page_gauche', '10', 'Marge gauche de la page en mm', '', 0);
INSERT INTO `parametres` VALUES (68, 'pdflettreadhesion', 'marge_page_droite', '10', 'Marge droite de la page en mm', '', 0);
INSERT INTO `parametres` VALUES (69, 'pdflettreadhesion', 'largeur_page', '210', 'Largeur de la page en mm', '', 0);
INSERT INTO `parametres` VALUES (70, 'pdflettreadhesion', 'hauteur_page', '297', 'Hauteur de la page en mm', '', 0);
INSERT INTO `parametres` VALUES (71, 'pdflettreadhesion', 'format_page', 'P', 'P pour Portrait, L pour paysage (Landscape)', '', 0);
INSERT INTO `parametres` VALUES (72, 'mailrelanceadhesion', 'objet', '$biblio_name : votre abonnement', 'Objet du courrier de relance d''adhésion. Utilisez biblio_name pour reprendre le nom précisé dans la localisation des exemplaires.', '', 0);
INSERT INTO `parametres` VALUES (73, 'mailrelanceadhesion', 'texte', 'Votre abonnement arrive à échéance le !!date_fin_adhesion!!. Nous vous remercions de penser à le renouveller lors de votre prochaine visite.\r\n\r\nCordialement,\r\n\r\n', 'Texte de la relance, !!date_fin_adhesion!! sera remplacé à l''édition par la date de fin d''adhésion du lecteur', '', 0);
INSERT INTO `parametres` VALUES (74, 'mailrelanceadhesion', 'madame_monsieur', 'Madame, Monsieur,', 'Entête du courrier de relance d''adhésion', '', 0);
INSERT INTO `parametres` VALUES (75, 'mailrelanceadhesion', 'fdp', 'Le responsable.', 'Formule de politesse en bas de page', '', 0);
INSERT INTO `parametres` VALUES (76, 'opac', 'show_marguerite_browser', '0', '0 ou 1 : marguerite des catégories', 'f_modules', 0);
INSERT INTO `parametres` VALUES (77, 'opac', 'show_100cases_browser', '0', '0 ou 1 : affichage de 100 catégories', 'f_modules', 0);
INSERT INTO `parametres` VALUES (78, 'pmb', 'indexint_decimal', '1', '0 ou 1 : l''indexation interne est-elle une cotation décimale type Dewey', '', 0);
INSERT INTO `parametres` VALUES (79, 'opac', 'modules_search_indexint', '1', 'Recherche simple dans les indexations internes:\r\n 0 : interdite\r\n 1 : autorisée\r\n 2 : autorisée et validée par défaut', 'c_recherche', 0);
INSERT INTO `parametres` VALUES (80, 'empr', 'birthdate_optional', '1', 'Année de naissance facultative : \r\n 0 > non:elle est obligatoire \r\n 1 Oui', '', 0);
INSERT INTO `parametres` VALUES (81, 'categories', 'show_empty_categ', '1', 'Affichage des catégories ne contenant aucune notice :\r\n0=non, 1=oui', '', 0);
INSERT INTO `parametres` VALUES (82, 'categories', 'term_search_n_per_page', '50', 'Nombre de termes affichés par page lors d''une recherche par terme dans les catégories', '', 0);
INSERT INTO `parametres` VALUES (83, 'opac', 'show_loginform', '1', 'Affichage du login lecteur dans l''OPAC \r\n 0 > non\r\n 1 Oui', 'f_modules', 0);
INSERT INTO `parametres` VALUES (84, 'opac', 'default_style', 'bueil', 'Style graphique de l''OPAC, 1 style par défaut, nomargin : sans affichage du bandeau de gauche', 'a_general', 0);
INSERT INTO `parametres` VALUES (85, 'opac', 'show_exemplaires', '1', 'Afficher les exemplaires dans l''OPAC\n 1 Oui,\n 0 : Non', 'e_aff_notice', 0);
INSERT INTO `parametres` VALUES (86, 'pmb', 'import_modele', 'func_bdp.inc.php', 'Quel script de fonctions d''import utiliser pour personnaliser l''import ?', '', 0);
INSERT INTO `parametres` VALUES (87, 'pmb', 'quotas_avances', '0', 'Quotas de prêts avancées ? \r\n 0 : Non\r\n 1 : Oui', '', 0);
INSERT INTO `parametres` VALUES (88, 'opac', 'logo', 'logo_default.jpg', 'Nom du fichier de l''image logo', 'z_unused', 0);
INSERT INTO `parametres` VALUES (89, 'opac', 'logosmall', 'images/site/livre.png', 'Nom du fichier de l''image petit logo', 'b_aff_general', 0);
INSERT INTO `parametres` VALUES (90, 'opac', 'show_bandeaugauche', '1', 'Affichage du bandeau de gauche ? \n 0 : Non\n 1 : Oui', 'f_modules', 0);
INSERT INTO `parametres` VALUES (91, 'opac', 'show_liensbas', '1', 'Affichage des liens(pmb, google, bibli) en bas de page ? \n 0 : Non\n 1 : Oui', 'f_modules', 0);
INSERT INTO `parametres` VALUES (92, 'opac', 'show_homeontop', '0', 'Affichage du lien HOME (retour accueil) sous le nom de la bibliothèque ou du centre de ressources (nécessaire si masquage bandeau gauche) ? \r\n 0 : Non\r\n 1 : Oui', 'f_modules', 0);
INSERT INTO `parametres` VALUES (93, 'pmb', 'resa_quota_pret_depasse', '1', 'Réservation possible même si quota de prêt dépassé ? \n 0 : Non\n 1 : Oui', '', 0);
INSERT INTO `parametres` VALUES (94, 'pmb', 'import_limit_read_file', '100', 'Limite de taille de lecture du fichier en import, en général 100 ou 200 doit fonctionner, si problème de time out : fixer plus bas, 50 par exemple.', '', 0);
INSERT INTO `parametres` VALUES (95, 'pmb', 'import_limit_record_load', '100', 'Limite de taille de traitement de notices en import, en général 100 ou 200 doit fonctionner, si problème de time out : fixer plus bas, 50 par exemple.', '', 0);
INSERT INTO `parametres` VALUES (96, 'opac', 'biblio_preamble_p1', '&#3755;&#3789;&#3754;&#3760;&#3805;&#3768;&#3732;&#3714;&#3757;&#3719;&#3713;&#3762;&#3737;&#3735;&#3771;&#3732;&#3754;&#3757;&#3738; PMB &#3754;&#3760;&#3776;&#3804;&#3765;&#3735;&#3784;&#3762;&#3737; 60 &#3776;&#3757;&#3713;&#3760;&#3754;&#3762;&#3737; &#3776;&#3742;&#3767;&#3784;&#3757;&#3735;&#3771;&#3732;&#3754;&#3757;&#3738;&#3749;&#3760;&#3738;&#3771;&#3738;, &#3804;&#3785;&#3762;&#3737;&#3765;&#3785;&#3754;&#3760;&#3776;&#3804;&#3765;&#3755;&#3772;&#3762;&#3725;&#3735;&#3762;&#3719;&#3776;&#3749;&#3767;&#3757;&#3713;&#3714;&#3757;&#3719;&#3713;&#3762;&#3737;&#3722;&#3757;&#3713; &#3777;&#3749;&#3760; &#3713;&#3762;&#3737; &#3776;&#3716;&#3767;&#3784;&#3757;&#3737;&#3735;&#3765;&#3784;&#3720;&#3762;&#3713;&#3804;&#3785;&#3762;&#3737;&#3765;&#3785;&#3755;&#3762;&#3804;&#3785;&#3762;&#3757;&#3767;&#3784;&#3737;, &#3754;&#3764;&#3784;&#3719;&#3776;&#3755;&#3772;&#3771;&#3784;&#3762;&#3737;&#3765;&#3785; &#3777;&#3745;&#3784;&#3737;&#3754;&#3762;&#3745;&#3762;&#3732;&#3732;&#3761;&#3732;&#3777;&#3739;&#3719;&#3780;&#3732;&#3785; .', 'Paragraphe 1 d''informations (par exemple, description du fonds)', 'b_aff_general', 0);
INSERT INTO `parametres` VALUES (97, 'opac', 'biblio_preamble_p2', '&#3713;&#3762;&#3737;&#3738;&#3789;&#3749;&#3764;&#3713;&#3762;&#3737; PMB &#3777;&#3745;&#3784;&#3737;&#3776;&#3739;&#3761;&#3737;&#3714;&#3757;&#3719;&#3735;&#3784;&#3762;&#3737;&#3777;&#3749;&#3785;&#3751; &#3776;&#3742;&#3767;&#3784;&#3757;&#3722;&#3784;&#3751;&#3725;&#3735;&#3784;&#3762;&#3737;&#3779;&#3737;&#3713;&#3762;&#3737;&#3732;&#3761;&#3732;&#3777;&#3713;&#3785; &#3755;&#3772;&#3767; &#3776;&#3758;&#3761;&#3732;&#3779;&#3755;&#3785;  PMB &#3714;&#3757;&#3719;&#3735;&#3784;&#3762;&#3737;&#3777;&#3735;&#3732;&#3776;&#3805;&#3762;&#3760;&#3713;&#3761;&#3738;&#3713;&#3762;&#3737;&#3737;&#3763;&#3779;&#3722;&#3785;.', 'Paragraphe 2 d''informations : accueil du public.', 'b_aff_general', 0);
INSERT INTO `parametres` VALUES (98, 'opac', 'biblio_quicksummary_p1', '', 'Paragraphe 1 de résumé, est masqué par défaut dans la feuille de style, voir id quickSummary.p1', 'z_unused', 0);
INSERT INTO `parametres` VALUES (99, 'opac', 'biblio_quicksummary_p2', '', 'Paragraphe 2 de résumé, est masqué par défaut dans la feuille de style, voir id quickSummary.p2', 'z_unused', 0);
INSERT INTO `parametres` VALUES (100, 'opac', 'show_dernieresnotices', '0', 'Affichage des dernières notices créées en bas de page ? \n 0 : Non\n 1 : Oui', 'f_modules', 0);
INSERT INTO `parametres` VALUES (101, 'opac', 'show_etageresaccueil', '1', 'Affichage des étagères dans la page d''accueil en bas de page ? \n 0 : Non\n 1 : Oui', 'f_modules', 0);
INSERT INTO `parametres` VALUES (102, 'opac', 'biblio_important_p1', '', 'Infos importantes 1, dans la feuille de style, voir id important.p1', 'b_aff_general', 0);
INSERT INTO `parametres` VALUES (103, 'opac', 'biblio_important_p2', '', 'Infos importantes, dans la feuille de style, voir id important.p2', 'b_aff_general', 0);
INSERT INTO `parametres` VALUES (104, 'opac', 'biblio_name', '&#3755;&#3789;&#3754;&#3760;&#3805;&#3768;&#3732; PMB &#3713;&#3762;&#3737;&#3738;&#3789;&#3749;&#3764;&#3713;&#3762;&#3737;', 'Nom de la bibliothèque ou du centre de ressources dans l''opac', 'b_aff_general', 0);
INSERT INTO `parametres` VALUES (105, 'opac', 'biblio_website', 'www.sigb.net', 'Site web de la bibliothèque ou du centre de ressources dans l''opac', 'b_aff_general', 0);
INSERT INTO `parametres` VALUES (106, 'opac', 'biblio_adr1', '&#3755;&#3789;&#3754;&#3760;&#3805;&#3768;&#3732;&#3777;&#3755;&#3784;&#3719;&#3722;&#3762;&#3732;\r\n            &#3734;&#3760;&#3804;&#3771;&#3737; &#3776;&#3754;&#3732;&#3734;&#3762;&#3735;&#3764;&#3749;&#3762;&#3732;', 'Adresse 1 de la bibliothèque ou du centre de ressources dans l''opac', 'b_aff_general', 0);
INSERT INTO `parametres` VALUES (107, 'opac', 'biblio_town', '&#3751;&#3773;&#3719;&#3720;&#3761;&#3737;', 'Ville dans l''opac', 'b_aff_general', 0);
INSERT INTO `parametres` VALUES (108, 'opac', 'biblio_cp', '&#3733;&#3769;&#3785; &#3739;.&#3737; 122 &#3738;&#3785;&#3762;&#3737;&#3722;&#3773;&#3719;&#3725;&#3767;&#3737;', 'Code postal dans l''opac', 'b_aff_general', 0);
INSERT INTO `parametres` VALUES (109, 'opac', 'biblio_country', '&#3754;&#3739;&#3739;&#3749;&#3762;&#3751; ', 'Pays dans l''opac', 'b_aff_general', 0);
INSERT INTO `parametres` VALUES (110, 'opac', 'biblio_phone', '(+85621) 251 405', 'Téléphone dans l''opac', 'b_aff_general', 0);
INSERT INTO `parametres` VALUES (111, 'opac', 'biblio_dep', '37', 'Département dans l''opac pour la météo', 'b_aff_general', 0);
INSERT INTO `parametres` VALUES (112, 'opac', 'biblio_email', 'pmb@sigb.net', 'Email de contact dans l''opac', 'b_aff_general', 0);
INSERT INTO `parametres` VALUES (113, 'opac', 'etagere_notices_order', 'index_serie, tnvol, index_sew', 'Ordre d''affichage des notices dans les étagères dans l''opac \n  index_serie, tit1 : tri par titre de série et titre \n rand()  : aléatoire', 'j_etagere', 0);
INSERT INTO `parametres` VALUES (114, 'opac', 'etagere_notices_format', '4', 'Format d''affichage des notices dans les étagères de l''écran d''accueil \r\n 1 : ISBD seul \r\n 2 : Public seul \r\n 4 : ISBD et Public \r\n 8 : Réduit (titre+auteurs) seul', 'j_etagere', 0);
INSERT INTO `parametres` VALUES (115, 'opac', 'etagere_notices_depliables', '1', 'Affichage dépliable des notices dans les étagères de l''écran d''accueil \r\n 0 : Non \r\n 1 : Oui', 'j_etagere', 0);
INSERT INTO `parametres` VALUES (116, 'opac', 'etagere_nbnotices_accueil', '5', 'Nombre de notices affichées dans les étagères de l''écran d''accueil \r\n 0 : Toutes \r\n -1 : Aucune \r\n x : x notices affichées au maximum', 'j_etagere', 0);
INSERT INTO `parametres` VALUES (117, 'opac', 'nb_aut_rec_per_page', '15', 'Nombre de notices affichées pour une autorité donnée', 'd_aff_recherche', 0);
INSERT INTO `parametres` VALUES (118, 'opac', 'notices_format', '4', 'Format d''affichage des notices dans les étagères de l''écran d''accueil \n 1 : ISBD seul \n 2 : Public seul \n 4 : ISBD et Public \n 5 : ISBD et Public avec ISBD en premier \n 8 : Réduit (titre+auteurs) seul', 'e_aff_notice', 0);
INSERT INTO `parametres` VALUES (119, 'opac', 'notices_depliable', '1', 'Affichage dépliable des notices en résultat de recherche  0 : Non  1 : Oui', 'e_aff_notice', 0);
INSERT INTO `parametres` VALUES (120, 'opac', 'term_search_n_per_page', '50', 'Nombre de termes affichés par page en recherche par terme', 'c_recherche', 0);
INSERT INTO `parametres` VALUES (121, 'opac', 'show_empty_categ', '1', 'En recherche par terme, affichage des catégories ne contenant aucun ouvrage :\r\n 0 : Non \r\n 1 : Oui', 'i_categories', 0);
INSERT INTO `parametres` VALUES (122, 'opac', 'allow_extended_search', '1', 'Autorisation ou non de la recherche avancée dans l''OPAC \n 0 : Non \n 1 : Oui', 'c_recherche', 0);
INSERT INTO `parametres` VALUES (123, 'opac', 'allow_term_search', '1', 'Autorisation ou non de la recherche par termes dans l''OPAC \n 0 : Non \n 1 : Oui', 'c_recherche', 0);
INSERT INTO `parametres` VALUES (124, 'opac', 'term_search_height', '350', 'Hauteur en pixels de la frame de recherche par termes (si pas précisé ou zéro : par défaut 200 pixels)', 'c_recherche', 0);
INSERT INTO `parametres` VALUES (125, 'opac', 'categories_nb_col_subcat', '3', 'Nombre de colonnes de sous-catégories en navigation dans les catégories \n 3 par défaut', 'i_categories', 0);
INSERT INTO `parametres` VALUES (126, 'opac', 'max_resa', '5', 'Nombre maximum de réservation sur un document \r\n 5 par défaut \r\n 0 pour illimité', 'a_general', 0);
INSERT INTO `parametres` VALUES (127, 'pmb', 'show_help', '1', 'Affichage de l''aide contextuelle dans PMB en partie gestion \r\n 1 Oui \r\n 0 Non', '', 0);
INSERT INTO `parametres` VALUES (128, 'opac', 'show_help', '1', 'Affichage de l''aide en ligne dans l''OPAC de PMB  \n 1 Oui \n 0 Non', 'f_modules', 0);
INSERT INTO `parametres` VALUES (129, 'opac', 'cart_allow', '1', 'Paniers possibles dans l''OPAC de PMB  \n 1 Oui \n 0 Non', 'f_modules', 0);
INSERT INTO `parametres` VALUES (130, 'opac', 'max_cart_items', '200', 'Nombre maximum de notices dans un panier utilisateur.', 'h_cart', 0);
INSERT INTO `parametres` VALUES (131, 'opac', 'show_section_browser', '1', 'Afficher le butineur de localisation et de sections ?\n 0 : Non\n 1 : Oui', 'f_modules', 0);
INSERT INTO `parametres` VALUES (132, 'opac', 'nb_localisations_per_line', '6', 'Nombre de localisations affichées par ligne en page d''accueil (si show_section_browser=1)', 'k_section', 0);
INSERT INTO `parametres` VALUES (133, 'opac', 'nb_sections_per_line', '6', 'Nombre de sections affichées par ligne en visualisation de localisation (si show_section_browser=1)', 'k_section', 0);
INSERT INTO `parametres` VALUES (134, 'opac', 'cart_only_for_subscriber', '1', 'Paniers de notices réservés aux adhérents de la bibliothèque ou du centre de ressources ?\r\n 1: Oui\r\n 0: Non, autorisé pour tout internaute', 'h_cart', 0);
INSERT INTO `parametres` VALUES (135, 'opac', 'notice_reduit_format', '0', 'Format d''affichage des réduits des notices :\r\n 0 normal = titre+auteurs principaux\r\n P 1,2,3: Perso. : tit+aut+champs persos id 1 2 3\r\n E 1,2,3: Perso. : tit+aut+édit+champs persos id 1 2 3 \r\n T : tit1+tit4', 'e_aff_notice', 0);
INSERT INTO `parametres` VALUES (136, 'pdflettreresa', 'before_list', 'Suite à votre demande de réservation, nous vous informons que le ou les ouvrages ci-dessous sont à votre disposition à la bibliothèque.', 'Texte apparaissant avant la liste des ouvrages en résa dans le courrier de confirmation de résa', '', 0);
INSERT INTO `parametres` VALUES (137, 'pdflettreresa', 'after_list', 'Passé le délai de réservation, ces ouvrages seront remis en circulation, vous priant de les retirer dans les meilleurs délais.', 'Texte apparaissant après la liste des ouvrages', '', 0);
INSERT INTO `parametres` VALUES (138, 'pdflettreresa', 'fdp', 'Le responsable.', 'Signataire de la lettre, utiliser $biblio_name pour reprendre le paramètre "biblio name" ou bien mettre autre chose.', '', 0);
INSERT INTO `parametres` VALUES (139, 'pdflettreresa', 'madame_monsieur', '&#3735;&#3784;&#3762;&#3737;&#3725;&#3764;&#3719;, &#3735;&#3784;&#3762;&#3737;&#3722;&#3762;&#3725; ', 'Entête de la lettre', '', 0);
INSERT INTO `parametres` VALUES (140, 'pdflettreresa', 'nb_par_page', '7', 'Nombre d''ouvrages en retard imprimé sur les pages suivantes.', '', 0);
INSERT INTO `parametres` VALUES (141, 'pdflettreresa', 'nb_1ere_page', '4', 'Nombre d''ouvrages en retard imprimé sur la première page', '', 0);
INSERT INTO `parametres` VALUES (142, 'pdflettreresa', 'taille_bloc_expl', '16', 'Taille d''un bloc (2 lignes) d''ouvrage en réservation. Le début de chaque ouvrage en résa sera espacé de cette valeur sur la page', '', 0);
INSERT INTO `parametres` VALUES (143, 'pdflettreresa', 'debut_expl_1er_page', '160', 'Début de la liste des ouvrages sur la première page, en mm depuis le bord supérieur de la page. Doit être règlé en fonction du texte qui précède la liste des ouvrages, lequel peut être plus ou moins long.', '', 0);
INSERT INTO `parametres` VALUES (144, 'pdflettreresa', 'debut_expl_page', '15', 'Début de la liste des ouvrages sur les pages suivantes, en mm depuis le bord supérieur de la page.', '', 0);
INSERT INTO `parametres` VALUES (145, 'pdflettreresa', 'limite_after_list', '270', 'Position limite en bas de page. Si un élément imprimé tente de dépasser cette limite, il sera imprimé sur la page suivante.', '', 0);
INSERT INTO `parametres` VALUES (146, 'pdflettreresa', 'marge_page_gauche', '10', 'Marge de gauche en mm', '', 0);
INSERT INTO `parametres` VALUES (147, 'pdflettreresa', 'marge_page_droite', '10', 'Marge de droite en mm', '', 0);
INSERT INTO `parametres` VALUES (148, 'pdflettreresa', 'largeur_page', '210', 'Largeur de la page en mm', '', 0);
INSERT INTO `parametres` VALUES (149, 'pdflettreresa', 'hauteur_page', '297', 'Hauteur de la page en mm', '', 0);
INSERT INTO `parametres` VALUES (150, 'pdflettreresa', 'format_page', 'P', 'Format de la page : \r\n P : Portrait\r\n L : Landscape = paysage', '', 0);
INSERT INTO `parametres` VALUES (151, 'opac', 'categories_max_display', '200', 'Pour la page d''accueil, nombre maximum de catégories principales affichées', 'i_categories', 0);
INSERT INTO `parametres` VALUES (152, 'opac', 'search_other_function', '', 'Fonction complémentaire pour les recherches en page d''accueil', 'c_recherche', 0);
INSERT INTO `parametres` VALUES (153, 'opac', 'lien_bas_supplementaire', '<a href=''http://www.sigb.net.com/poomble.php'' target=_blank>Lien vers autre site</a>', 'Lien supplémentaire en bas de page d''accueil, à renseigner complètement : a href= lien /a', 'b_aff_general', 0);
INSERT INTO `parametres` VALUES (154, 'z3950', 'import_modele', 'func_other.inc.php', 'Quel script de fonctions d''import utiliser pour personnaliser l''import en intégration z3950 ?', '', 0);
INSERT INTO `parametres` VALUES (155, 'ldap', 'server', 'chinon', 'Serveur LDAP, IP ou host', '', 0);
INSERT INTO `parametres` VALUES (156, 'ldap', 'basedn', '', 'Racine du nom de domaine LDAP', '', 0);
INSERT INTO `parametres` VALUES (157, 'ldap', 'port', '389', 'Port du serveur LDAP', '', 0);
INSERT INTO `parametres` VALUES (158, 'ldap', 'filter', '(&(objectclass=person)(gidnumber=GID))', 'Serveur LDAP, IP ou host', '', 0);
INSERT INTO `parametres` VALUES (159, 'ldap', 'fields', 'uid,gecos,departmentnumber', 'Champs du serveur LDAP', '', 0);
INSERT INTO `parametres` VALUES (160, 'ldap', 'lang', 'fr_FR', 'Langue du serveur LDAP', '', 0);
INSERT INTO `parametres` VALUES (161, 'ldap', 'groups', '', 'Groupes du serveur LDAP', '', 0);
INSERT INTO `parametres` VALUES (162, 'ldap', 'accessible', '0', 'LDAP accessible ?', '', 0);
INSERT INTO `parametres` VALUES (163, 'opac', 'categories_show_only_last', '0', 'Dans la fiche d''une notice : \n 0 tout afficher \n 1 : afficher uniquement la dernière feuille de l''arbre de la catégorie', 'i_categories', 0);
INSERT INTO `parametres` VALUES (164, 'categories', 'show_only_last', '0', 'Dans la fiche d''une notice : \n 0 tout afficher \n 1 : afficher uniquement la dernière feuille de l''arbre de la catégorie', '', 0);
INSERT INTO `parametres` VALUES (165, 'pmb', 'prefill_cote', 'custom_cote_02.inc.php', 'Script personnalisé de construction de la cote de l''exemplaire', '', 0);
INSERT INTO `parametres` VALUES (166, 'ldap', 'proto', '3', 'Version du protocole LDAP : 3 ou 2', '', 0);
INSERT INTO `parametres` VALUES (167, 'ldap', 'binddn', 'uid=UID,ou=People', 'Description de la liaison : construction de la chaine binddn pour lier l''authentification au serveur LDAP dans l''OPAC', '', 0);
INSERT INTO `parametres` VALUES (168, 'empr', 'corresp_import', '', 'Table de correspondances colonnes/champs en import de lecteurs à partir d''un fichier ASCII', '', 0);
INSERT INTO `parametres` VALUES (169, 'pmb', 'type_audit', '0', 'Gestion/affichage des dates de création/modification \n 0: Rien\n 1: Création et dernière modification\n 2: Création et toutes les dates de modification', '', 0);
INSERT INTO `parametres` VALUES (170, 'pmb', 'gestion_abonnement', '0', 'Utiliser la gestion des abonnements des lecteurs ? \n 0 : Non\n 1 : Oui, gestion simple, \n 2 : Oui, gestion avancée', '', 0);
INSERT INTO `parametres` VALUES (171, 'pmb', 'utiliser_calendrier', '0', 'Utiliser le calendrier des jours d''ouverture ? \n 0 : Non\n 1 : Oui', '', 0);
INSERT INTO `parametres` VALUES (172, 'pmb', 'gestion_financiere', '0', 'Utiliser le module gestion financière ? \n 0 : Non\n 1 : Oui', '', 0);
INSERT INTO `parametres` VALUES (173, 'pmb', 'gestion_tarif_prets', '0', 'Utiliser la gestion des tarifs de prêts ? \n 0 : Non\n 1 : Oui, gestion simple, \n 2 : Oui, gestion avancée', '', 0);
INSERT INTO `parametres` VALUES (174, 'pmb', 'gestion_amende', '0', 'Utiliser la gestion des amendes:\n 0 = Non\n 1 = Gestion simple\n 2 = Gestion avancée', '', 0);
INSERT INTO `parametres` VALUES (175, 'finance', 'amende_jour', '0.15', 'Amende par jour de retard pour tout type de document. Attention, le séparateur décimal est le point, pas la virgule', '', 1);
INSERT INTO `parametres` VALUES (176, 'finance', 'delai_avant_amende', '15', 'Délai avant déclenchement de l''amende, en jour', '', 1);
INSERT INTO `parametres` VALUES (177, 'finance', 'delai_recouvrement', '7', 'Délai entre 3eme relance et mise en recouvrement officiel de l''amende, en jour', '', 1);
INSERT INTO `parametres` VALUES (178, 'finance', 'amende_maximum', '0', 'Amende maximum, quel que soit le retard l''amende est plafonnée à ce montant. 0 pour désactiver ce plafonnement.', '', 1);
INSERT INTO `parametres` VALUES (179, 'pdflettreresa', 'priorite_email', '1', 'Priorité des lettres de confirmation de réservation par mail lors de la validation d''une réservation:\n 0 : Lettre seule \n 1 : Mail, à défaut lettre\n 2 : Mail ET lettre\n 3 : Aucune alerte', '', 0);
INSERT INTO `parametres` VALUES (180, 'pdflettreresa', 'priorite_email_manuel', '1', 'Priorité des lettres de confirmation de réservation par mail lors de l''impression à partir du bouton :\n 0 : Lettre seule \n 1 : Mail, à défaut lettre\n 2 : Mail ET lettre\n 3 : Aucune alerte', '', 0);
INSERT INTO `parametres` VALUES (181, 'finance', 'blocage_abt', '1', 'Blocage du prêt si le compte abonnement est débiteur\n 0 : pas de blocage \n 1 : blocage avec forçage possible  : blocage incontournable.', '', 1);
INSERT INTO `parametres` VALUES (182, 'finance', 'blocage_pret', '1', 'Blocage du prêt si le compte prêt est débiteur\n 0 : pas de blocage \n 1 : blocage avec forçage possible  : blocage incontournable.', '', 1);
INSERT INTO `parametres` VALUES (183, 'finance', 'blocage_amende', '1', 'Blocage du prêt si le compte amende est débiteur\n 0 : pas de blocage \n 1 : blocage avec forçage possible  : blocage incontournable.', '', 1);
INSERT INTO `parametres` VALUES (184, 'pmb', 'gestion_devise', '&euro;', 'Devise de la gestion financière, ce qui va être affiché en code HTML', '', 0);
INSERT INTO `parametres` VALUES (185, 'opac', 'book_pics_url', '', 'URL des vignettes des notices, dans le chemin fourni, !!isbn!! sera remplacé par le code ISBN ou EAN de la notice purgé de tous les tirets ou points. \n exemple : http://www.monsite/opac/images/vignettes/!!isbn!!.jpg', 'e_aff_notice', 0);
INSERT INTO `parametres` VALUES (186, 'opac', 'lien_moteur_recherche', '<a href=http://www.google.fr target=_blank>Faire une recherche avec Google</a>', 'Lien supplémentaire en bas de page d''accueil, à renseigner complètement : a href= lien /a', 'b_aff_general', 0);
INSERT INTO `parametres` VALUES (187, 'pmb', 'pret_express_statut', '2', 'Statut de notice à utiliser en création d''exemplaires en prêts express', '', 0);
INSERT INTO `parametres` VALUES (188, 'opac', 'notice_affichage_class', '', 'Nom de la classe d''affichage pour personnalisation de l''affichage des notices', 'e_aff_notice', 0);
INSERT INTO `parametres` VALUES (189, 'pmb', 'confirm_retour', '0', 'En retour de documents, le retour doit-il être confirmé ? \n 0 : Non, on peut passer les codes-barres les uns après les autres \n 1 : Oui, il faut valider le retour après chaque code-barre', '', 0);
INSERT INTO `parametres` VALUES (190, 'opac', 'show_meteo_url', '<img src="http://perso0.free.fr/cgi-bin/meteo.pl?dep=72" alt="" border="0" hspace=0>', 'URL de la météo affichée', 'f_modules', 0);
INSERT INTO `parametres` VALUES (191, 'pmb', 'limitation_dewey', '0', 'Nombre maximum de caractères dans la Dewey (676) en import : \n 0 aucune limitation \n 3 : limitation de 000 à 999 \n 5 (exemple) limitation 000.0 \n -1 : aucune importation', '', 0);
INSERT INTO `parametres` VALUES (192, 'finance', 'delai_1_2', '15', 'Délai entre 1ere et 2eme relance', '', 1);
INSERT INTO `parametres` VALUES (193, 'finance', 'delai_2_3', '15', 'Délai entre 2eme et 3eme relance', '', 1);
INSERT INTO `parametres` VALUES (194, 'pmb', 'lecteurs_localises', '0', 'Lecteurs localisés ? \n 0: Non \n 1: Oui', '', 0);
INSERT INTO `parametres` VALUES (195, 'dsi', 'active', '1', 'D.S.I activée ? \n 0: Non \n 1: Oui', '', 0);
INSERT INTO `parametres` VALUES (196, 'dsi', 'auto', '0', 'D.S.I automatique activée ? \n 0: Non \n 1: Oui', '', 0);
INSERT INTO `parametres` VALUES (197, 'dsi', 'insc_categ', '0', 'Inscription automatique dans les bannettes de la catégorie du lecteur en création ? \n 0: Non \n 1: Oui', '', 0);
INSERT INTO `parametres` VALUES (198, 'opac', 'allow_bannette_priv', '0', 'Possibilité pour les lecteurs de créer ou modifier leurs bannettes privées \n 0: Non \n 1: Oui', 'l_dsi', 0);
INSERT INTO `parametres` VALUES (199, 'opac', 'allow_resiliation', '0', 'Possibilité pour les lecteurs de résilier leur abonnement aux bannettes pro \n 0: Non \n 1: Oui', 'l_dsi', 0);
INSERT INTO `parametres` VALUES (200, 'opac', 'show_categ_bannette', '0', 'Affichage des bannettes de la catégorie du lecteur et possibilité de s''y abonner \n 0: Non \n 1: Oui', 'l_dsi', 0);
INSERT INTO `parametres` VALUES (201, 'opac', 'url_base', './', 'URL de base de l''opac : typiquement mettre l''url publique web http://monsite/opac/ ne pas oublier le / final', 'a_general', 0);
INSERT INTO `parametres` VALUES (202, 'finance', 'relance_1', '0.53', 'Frais de la première lettre de relance', '', 1);
INSERT INTO `parametres` VALUES (203, 'finance', 'relance_2', '0.53', 'Frais de la deuxième lettre de relance', '', 1);
INSERT INTO `parametres` VALUES (204, 'finance', 'relance_3', '2.50', 'Frais de la troisième lettre de relance', '', 1);
INSERT INTO `parametres` VALUES (205, 'finance', 'statut_perdu', '', 'Statut (d''exemplaire) perdu pour des ouvrages non rendus', '', 1);
INSERT INTO `parametres` VALUES (206, 'pdflettreretard', '2after_list', 'Nous vous remercions de prendre rapidement contact par téléphone au $biblio_phone ou par mail à $biblio_email pour étudier la possibilité de prolonger ces prêts ou de rapporter les ouvrages concernés.', 'Texte apparaissant après la liste des ouvrages en retard dans le courrier', '', 0);
INSERT INTO `parametres` VALUES (207, 'pdflettreretard', '2before_list', 'Sauf erreur de notre part, vous avez toujours en votre possession le ou les ouvrage(s) suivant(s) dont la durée de prêt est aujourd''hui dépassée :', 'Texte apparaissant avant la liste des ouvrages en retard dans le courrier de relance de retard', '', 0);
INSERT INTO `parametres` VALUES (208, 'pdflettreretard', '2debut_expl_1er_page', '160', 'Début de la liste des exemplaires sur la première page, en mm depuis le bord supérieur de la page. Doit être règlé en fonction du texte qui précède la liste des ouvrages, lequel peut être plus ou moins long.', '', 0);
INSERT INTO `parametres` VALUES (209, 'pdflettreretard', '2debut_expl_page', '15', 'Début de la liste des exemplaires sur les pages suivantes, en mm depuis le bord supérieur de la page.', '', 0);
INSERT INTO `parametres` VALUES (210, 'pdflettreretard', '2fdp', 'Le responsable.', 'Signataire de la lettre.', '', 0);
INSERT INTO `parametres` VALUES (211, 'pdflettreretard', '2format_page', 'P', 'Format de la page : \r\n P : Portrait\r\n L : Landscape = paysage', '', 0);
INSERT INTO `parametres` VALUES (212, 'pdflettreretard', '2hauteur_page', '297', 'Hauteur de la page en mm', '', 0);
INSERT INTO `parametres` VALUES (213, 'pdflettreretard', '2largeur_page', '210', 'Largeur de la page en mm', '', 0);
INSERT INTO `parametres` VALUES (214, 'pdflettreretard', '2limite_after_list', '270', 'Position limite en bas de page. Si un élément imprimé tente de dépasser cette limite, il sera imprimé sur la page suivante.', '', 0);
INSERT INTO `parametres` VALUES (215, 'pdflettreretard', '2madame_monsieur', 'Madame, Monsieur,', 'Entête de la lettre', '', 0);
INSERT INTO `parametres` VALUES (216, 'pdflettreretard', '2marge_page_droite', '10', 'Marge de droite en mm', '', 0);
INSERT INTO `parametres` VALUES (217, 'pdflettreretard', '2marge_page_gauche', '10', 'Marge de gauche en mm', '', 0);
INSERT INTO `parametres` VALUES (218, 'pdflettreretard', '2nb_1ere_page', '4', 'Nombre d''ouvrages en retard imprimé sur la première page', '', 0);
INSERT INTO `parametres` VALUES (219, 'pdflettreretard', '2nb_par_page', '7', 'Nombre d''ouvrages en retard imprimé sur les pages suivantes.', '', 0);
INSERT INTO `parametres` VALUES (220, 'pdflettreretard', '2taille_bloc_expl', '16', 'Taille d''un bloc (2 lignes) d''ouvrage en retard. Le début de chaque ouvrage en retard sera espacé de cette valeur sur la page', '', 0);
INSERT INTO `parametres` VALUES (221, 'pdflettreretard', '3after_list', 'Nous vous remercions de prendre rapidement contact par téléphone au $biblio_phone ou par mail à $biblio_email pour étudier la possibilité de prolonger ces prêts ou de rapporter les ouvrages concernés.', 'Texte apparaissant après la liste des ouvrages en retard dans le courrier', '', 0);
INSERT INTO `parametres` VALUES (222, 'pdflettreretard', '3before_list', 'Sauf erreur de notre part, vous avez toujours en votre possession le ou les ouvrage(s) suivant(s) dont la durée de prêt est aujourd''hui dépassée :', 'Texte apparaissant avant la liste des ouvrages en retard dans le courrier de relance de retard', '', 0);
INSERT INTO `parametres` VALUES (223, 'pdflettreretard', '3debut_expl_1er_page', '160', 'Début de la liste des exemplaires sur la première page, en mm depuis le bord supérieur de la page. Doit être règlé en fonction du texte qui précède la liste des ouvrages, lequel peut être plus ou moins long.', '', 0);
INSERT INTO `parametres` VALUES (224, 'pdflettreretard', '3debut_expl_page', '15', 'Début de la liste des exemplaires sur les pages suivantes, en mm depuis le bord supérieur de la page.', '', 0);
INSERT INTO `parametres` VALUES (225, 'pdflettreretard', '3fdp', 'Le responsable.', 'Signataire de la lettre.', '', 0);
INSERT INTO `parametres` VALUES (226, 'pdflettreretard', '3format_page', 'P', 'Format de la page : \r\n P : Portrait\r\n L : Landscape = paysage', '', 0);
INSERT INTO `parametres` VALUES (227, 'pdflettreretard', '3hauteur_page', '297', 'Hauteur de la page en mm', '', 0);
INSERT INTO `parametres` VALUES (228, 'pdflettreretard', '3largeur_page', '210', 'Largeur de la page en mm', '', 0);
INSERT INTO `parametres` VALUES (229, 'pdflettreretard', '3limite_after_list', '270', 'Position limite en bas de page. Si un élément imprimé tente de dépasser cette limite, il sera imprimé sur la page suivante.', '', 0);
INSERT INTO `parametres` VALUES (230, 'pdflettreretard', '3madame_monsieur', 'Madame, Monsieur,', 'Entête de la lettre', '', 0);
INSERT INTO `parametres` VALUES (231, 'pdflettreretard', '3marge_page_droite', '10', 'Marge de droite en mm', '', 0);
INSERT INTO `parametres` VALUES (232, 'pdflettreretard', '3marge_page_gauche', '10', 'Marge de gauche en mm', '', 0);
INSERT INTO `parametres` VALUES (233, 'pdflettreretard', '3nb_1ere_page', '4', 'Nombre d''ouvrages en retard imprimé sur la première page', '', 0);
INSERT INTO `parametres` VALUES (234, 'pdflettreretard', '3nb_par_page', '7', 'Nombre d''ouvrages en retard imprimé sur les pages suivantes.', '', 0);
INSERT INTO `parametres` VALUES (235, 'pdflettreretard', '3taille_bloc_expl', '16', 'Taille d''un bloc (2 lignes) d''ouvrage en retard. Le début de chaque ouvrage en retard sera espacé de cette valeur sur la page', '', 0);
INSERT INTO `parametres` VALUES (236, 'pdflettreretard', '3before_recouvrement', 'Sans nouvelles de votre part dans les sept jours, nous nous verrons contraints de déléguer au trésor public le recouvrement des ouvrages suivants :', 'Texte avant la liste des ouvrages en recouvrement', '', 0);
INSERT INTO `parametres` VALUES (237, 'opac', 'bannette_notices_order', ' index_serie, tnvol, index_sew ', 'Ordre d''affichage des notices dans les bannettes dans l''opac \n  index_serie, tnvol, index_sew : tri par titre de série et titre \n rand()  : aléatoire', 'l_dsi', 0);
INSERT INTO `parametres` VALUES (238, 'opac', 'bannette_notices_format', '8', 'Format d''affichage des notices dans les bannettes \n 1 : ISBD seul \n 2 : Public seul \n 4 : ISBD et Public \n 8 : Réduit (titre+auteurs) seul', 'l_dsi', 0);
INSERT INTO `parametres` VALUES (239, 'opac', 'bannette_notices_depliables', '1', 'Affichage dépliable des notices dans les bannettes \n 0 : Non \n 1 : Oui', 'l_dsi', 0);
INSERT INTO `parametres` VALUES (240, 'opac', 'bannette_nb_liste', '0', 'Nbre de notices par bannettes en affichage de la liste des bannettes \n 0 Toutes \n N : maxi N\n -1 : aucune', 'l_dsi', 0);
INSERT INTO `parametres` VALUES (241, 'opac', 'dsi_active', '0', 'DSI, bannettes accessibles par l''OPAC ? \n 0 : Non \n 1 : Oui', 'l_dsi', 0);
INSERT INTO `parametres` VALUES (242, 'mailretard', '2after_list', 'Nous vous remercions de prendre rapidement contact par téléphone au $biblio_phone ou par mail à $biblio_email pour étudier la possibilité de prolonger ces prêts ou de rapporter les ouvrages concernés.', 'Texte apparaissant après la liste des ouvrages en retard dans le mail', '', 0);
INSERT INTO `parametres` VALUES (243, 'mailretard', '2before_list', 'Sauf erreur de notre part, vous avez toujours en votre possession le ou les ouvrage(s) suivant(s) dont la durée de prêt est aujourd''hui dépassée :', 'Texte apparaissant avant la liste des ouvrages en retard dans le mail de relance de retard', '', 0);
INSERT INTO `parametres` VALUES (244, 'mailretard', '2fdp', 'Le responsable.', 'Signataire du mail de relance de retard', '', 0);
INSERT INTO `parametres` VALUES (245, 'mailretard', '2madame_monsieur', 'Madame, Monsieur', 'Entête du mail', '', 0);
INSERT INTO `parametres` VALUES (246, 'mailretard', '2objet', '$biblio_name : documents en retard', 'Objet du mail de relance de retard', '', 0);
INSERT INTO `parametres` VALUES (247, 'mailretard', '3after_list', 'Nous vous remercions de prendre rapidement contact par téléphone au $biblio_phone ou par mail à $biblio_email pour étudier la possibilité de prolonger ces prêts ou de rapporter les ouvrages concernés.', 'Texte apparaissant après la liste des ouvrages en retard dans le mail', '', 0);
INSERT INTO `parametres` VALUES (248, 'mailretard', '3before_list', 'Sauf erreur de notre part, vous avez toujours en votre possession le ou les ouvrage(s) suivant(s) dont la durée de prêt est aujourd''hui dépassée :', 'Texte apparaissant avant la liste des ouvrages en retard dans le mail de relance de retard', '', 0);
INSERT INTO `parametres` VALUES (249, 'mailretard', '3fdp', 'Le responsable.', 'Signataire du mail de relance de retard', '', 0);
INSERT INTO `parametres` VALUES (250, 'mailretard', '3madame_monsieur', 'Madame, Monsieur', 'Entête du mail', '', 0);
INSERT INTO `parametres` VALUES (251, 'mailretard', '3objet', '$biblio_name : documents en retard', 'Objet du mail de relance de retard', '', 0);
INSERT INTO `parametres` VALUES (252, 'mailretard', '3before_recouvrement', 'Sans nouvelles de votre part dans les sept jours, nous nous verrons contraints de déléguer au trésor public le recouvrement des ouvrages suivants :', 'Texte avant la liste des ouvrages en recouvrement', '', 0);
INSERT INTO `parametres` VALUES (253, 'mailretard', 'priorite_email', '1', 'Priorité des lettres de retard lors des relances :\n 0 : Lettre seule \n 1 : Mail, à défaut lettre\n 2 : Mail ET lettre', '', 0);
INSERT INTO `parametres` VALUES (254, 'pmb', 'import_modele_lecteur', '', 'Modèle d''import des lecteurs', '', 0);
INSERT INTO `parametres` VALUES (255, 'pmb', 'blocage_retard', '0', 'Bloquer le prêt d''une durée équivalente au retard ? 0=non, 1=oui', '', 0);
INSERT INTO `parametres` VALUES (256, 'pmb', 'blocage_delai', '7', 'Délai à partir duquel le retard est pris en compte', '', 0);
INSERT INTO `parametres` VALUES (257, 'pmb', 'blocage_max', '60', 'Nombre maximum de jours bloqués (0 = pas de limite)', '', 0);
INSERT INTO `parametres` VALUES (258, 'pmb', 'blocage_coef', '1', 'Coefficient de proportionnalité des jours de retard pour le blocage', '', 0);
INSERT INTO `parametres` VALUES (259, 'pmb', 'blocage_retard_force', '1', '1 = Le prêt peut-être forcé lors d''un blocage du compte, 2 = Pas de forçage possible', '', 0);
INSERT INTO `parametres` VALUES (260, 'opac', 'etagere_order', ' name ', 'Tri des étagères dans l''écran d''accueil, \n name = par nom\n name DESC = par nom décroissant', 'j_etagere', 0);
INSERT INTO `parametres` VALUES (261, 'pmb', 'book_pics_show', '0', 'Affichage des couvertures de livres en gestion\n 1: oui  \n 0: non', '', 0);
INSERT INTO `parametres` VALUES (262, 'pmb', 'book_pics_url', '', 'URL des vignettes des notices, dans le chemin fourni, !!isbn!! sera remplacé par le code ISBN ou EAN de la notice purgé de tous les tirets ou points. \r\n exemple : http://www.monsite/opac/images/vignettes/!!isbn!!.jpg', '', 0);
INSERT INTO `parametres` VALUES (263, 'pmb', 'opac_url', './opac_css/', 'URL de l''OPAC vu depuis la partie gestion, par défaut ./opac_css/', '', 0);
INSERT INTO `parametres` VALUES (264, 'opac', 'resa_popup', '1', 'Demande de connexion sous forme de popup ? :\n 0 : Non\n 1 : Oui', 'a_general', 0);
INSERT INTO `parametres` VALUES (265, 'pmb', 'vignette_x', '100', 'Largeur de la vignette créée pour un exemplaire numérique image', '', 0);
INSERT INTO `parametres` VALUES (266, 'pmb', 'vignette_y', '100', 'Hauteur de la vignette créée pour un exemplaire numérique image', '', 0);
INSERT INTO `parametres` VALUES (267, 'pmb', 'vignette_imagemagick', '', 'Chemin de l''exécutable ImageMagick (/usr/bin/imagemagick par exemple)', '', 0);
INSERT INTO `parametres` VALUES (268, 'opac', 'show_rss_browser', '0', 'Affichage des flux RSS du catalogue en page d''accueil OPAC 1: oui  ou 0: non', 'f_modules', 0);
INSERT INTO `parametres` VALUES (269, 'pmb', 'mail_methode', 'php', 'Méthode d''envoi des mails : \n php : fonction mail() de php\n smtp,hote:port,auth,user,pass : en smtp, mettre O ou 1 pour l''authentification...', '', 0);
INSERT INTO `parametres` VALUES (270, 'opac', 'mail_methode', 'php', 'Méthode d''envoi des mails dans l''opac : \n php : fonction mail() de php\n smtp,hote:port,auth,user,pass : en smtp, mettre O ou 1 pour l''authentification...', 'a_general', 0);
INSERT INTO `parametres` VALUES (271, 'opac', 'search_show_typdoc', '1', 'Affichage de la restriction par type de document pour les recherches en page d''accueil', 'c_recherche', 0);
INSERT INTO `parametres` VALUES (272, 'pmb', 'verif_on_line', '0', 'Dans le menu Administration > Outils > Maj Base : vérification d''une version plus récente de PMB en ligne ? \r\n0 : non : si vous n''êtes pas connecté à internet \r\n 1 : Oui : si vous avez une connexion à internet', '', 0);
INSERT INTO `parametres` VALUES (273, 'opac', 'show_languages', '1 fr_FR,it_IT,es_ES,ca_ES,en_UK,nl_NL,oc_FR,la_LA', 'Afficher la liste déroulante de sélection de la langue ?', 'a_general', 0);
INSERT INTO `parametres` VALUES (274, 'pmb', 'pdf_font', 'Helvetica', 'Police de caractères à chasse variable pour les éditions en pdf - Police Arial', '', 0);
INSERT INTO `parametres` VALUES (275, 'pmb', 'pdf_fontfixed', 'Courier', 'Police de caractères à chasse fixe pour les éditions en pdf - Police Courier', '', 0);
INSERT INTO `parametres` VALUES (276, 'z3950', 'debug', '0', 'Debugage (export fichier) des notices lues en Z3950 \r\n 0: Non \r\n 1: 0ui', '', 0);
INSERT INTO `parametres` VALUES (277, 'pmb', 'nb_lastnotices', '10', 'Nombre de dernières notices affichées en Catalogue - Dernières notices', '', 0);
INSERT INTO `parametres` VALUES (278, 'opac', 'show_dernieresnotices_nb', '10', 'Nombre de dernières notices affichées en Catalogue - Dernières notices', 'f_modules', 0);
INSERT INTO `parametres` VALUES (279, 'pmb', 'recouvrement_auto', '0', 'Par défaut passage en recouvrement proposé en gestion des relances si niveau=3 et devrait être en 4: \r\n 1: Oui, recouvrement proposé par défaut \r\n 0: Ne rien faire par défaut', '', 0);
INSERT INTO `parametres` VALUES (280, 'pmb', 'keyword_sep', ' ', 'Séparateur des mots clés dans la partie indexation libre, espace ou ; ou , ou ...', '', 0);
INSERT INTO `parametres` VALUES (281, 'thesaurus', 'mode_pmb', '0', 'Niveau d''utilisation des thésaurus.\n 0 : Un seul thésaurus par défaut.\n 1 : Choix du thésaurus possible.', '', 0);
INSERT INTO `parametres` VALUES (282, 'thesaurus', 'defaut', '1', 'Identifiant du thésaurus par défaut.', '', 0);
INSERT INTO `parametres` VALUES (283, 'thesaurus', 'liste_trad', 'la_LA', 'Liste des langues affichées dans les thésaurus.', '', 0);
INSERT INTO `parametres` VALUES (284, 'opac', 'thesaurus', '0', 'Niveau d''utilisation des thésaurus.\n 0 : Un seul thésaurus par défaut.\n 1 : Choix du thésaurus possible.', 'a_general', 0);
INSERT INTO `parametres` VALUES (285, 'acquisition', 'active', '0', 'Module acquisitions activé.\n 0 : Non.\n 1 : Oui.', '', 0);
INSERT INTO `parametres` VALUES (286, 'acquisition', 'gestion_tva', '0', 'Gestion de la TVA.\n 0 : Non.\n 1 : Oui.', '', 0);
INSERT INTO `parametres` VALUES (287, 'acquisition', 'poids_sugg', 'U=1.00,E=0.70,V=0.00', 'Pondération des suggestions par défaut en pourcentage.\n U=Utilisateurs, E=Emprunteurs, V=Visiteurs.\n ex : U=1.00,E=0.70,V=0.00 \n', '', 0);
INSERT INTO `parametres` VALUES (288, 'acquisition', 'format', '8,CA,DD,BL,FA', 'Taille du Numéro et Préfixes des actes d''achats.\nex : 8,CA,DD,BL,FA \n8 = Préfixe + 8 Chiffres\nCA=Commande Achat, DD=Demande de Devis,BL=Bon de Livraison, FA=Facture Achat \n', '', 0);
INSERT INTO `parametres` VALUES (289, 'acquisition', 'budget', '0', 'Utilisation d''un budget pour les commandes.\n 0:optionnel\n 1:obligatoire', '', 0);
INSERT INTO `parametres` VALUES (290, 'acquisition', 'pdfcde_format_page', '210x297', 'Largeur x Hauteur de la page en mm', 'pdfcde', 0);
INSERT INTO `parametres` VALUES (291, 'acquisition', 'pdfcde_orient_page', 'P', 'Orientation de la page: P=Portrait, L=Paysage', 'pdfcde', 0);
INSERT INTO `parametres` VALUES (292, 'acquisition', 'pdfcde_marges_page', '10,20,10,10', 'Marges de page en mm : Haut,Bas,Droite,Gauche', 'pdfcde', 0);
INSERT INTO `parametres` VALUES (293, 'acquisition', 'pdfcde_pos_logo', '10,10,20,20', 'Position du logo: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur', 'pdfcde', 0);
INSERT INTO `parametres` VALUES (294, 'acquisition', 'pdfcde_pos_raison', '35,10,100,10,16', 'Position Raison sociale: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police', 'pdfcde', 0);
INSERT INTO `parametres` VALUES (295, 'acquisition', 'pdfcde_pos_date', '150,10,0,6,8', 'Position Date: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police', 'pdfcde', 0);
INSERT INTO `parametres` VALUES (296, 'acquisition', 'pdfcde_pos_adr_fac', '10,35,60,5,10', 'Position Adresse de facturation: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police', 'pdfcde', 0);
INSERT INTO `parametres` VALUES (297, 'acquisition', 'pdfcde_pos_adr_liv', '10,75,60,5,10', 'Position Adresse de livraison: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police', 'pdfcde', 0);
INSERT INTO `parametres` VALUES (298, 'acquisition', 'pdfcde_pos_adr_fou', '100,55,100,6,14', 'Position Adresse fournisseur: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police', 'pdfcde', 0);
INSERT INTO `parametres` VALUES (299, 'acquisition', 'pdfcde_pos_num', '10,110,0,10,16', 'Position numéro de commande: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police', 'pdfcde', 0);
INSERT INTO `parametres` VALUES (300, 'acquisition', 'pdfcde_text_size', '10', 'Taille de la police texte', 'pdfcde', 0);
INSERT INTO `parametres` VALUES (301, 'acquisition', 'pdfcde_text_before', '', 'Texte avant le tableau de commande', 'pdfcde', 0);
INSERT INTO `parametres` VALUES (302, 'acquisition', 'pdfcde_text_after', '', 'Texte après le tableau de commande', 'pdfcde', 0);
INSERT INTO `parametres` VALUES (303, 'acquisition', 'pdfcde_tab_cde', '5,10', 'Table de commandes: Hauteur ligne,Taille police', 'pdfcde', 0);
INSERT INTO `parametres` VALUES (304, 'acquisition', 'pdfcde_pos_tot', '10,40,5,10', 'Position total de commande: Distance par rapport au bord gauche de la page, Largeur, Hauteur ligne,Taille police', 'pdfcde', 0);
INSERT INTO `parametres` VALUES (305, 'acquisition', 'pdfcde_pos_footer', '15,8', 'Position bas de page: Distance par rapport au bas de page, Taille police', 'pdfcde', 0);
INSERT INTO `parametres` VALUES (306, 'acquisition', 'pdfcde_pos_sign', '10,60,5,10', 'Position signature: Distance par rapport au bord gauche de la page, Largeur, Hauteur ligne,Taille police', 'pdfcde', 0);
INSERT INTO `parametres` VALUES (307, 'acquisition', 'pdfcde_text_sign', '&#3740;&#3769;&#3785;&#3758;&#3761;&#3738;&#3740;&#3764;&#3732;&#3722;&#3757;&#3738;&#3755;&#3789;&#3754;&#3760;&#3805;&#3768;&#3732;.', 'Texte signature', 'pdfcde', 0);
INSERT INTO `parametres` VALUES (308, 'acquisition', 'pdfdev_format_page', '210x297', 'Largeur x Hauteur de la page en mm', 'pdfdev', 0);
INSERT INTO `parametres` VALUES (309, 'acquisition', 'pdfdev_orient_page', 'P', 'Orientation de la page: P=Portrait, L=Paysage', 'pdfdev', 0);
INSERT INTO `parametres` VALUES (310, 'acquisition', 'pdfdev_marges_page', '10,20,10,10', 'Marges de page en mm : Haut,Bas,Droite,Gauche', 'pdfdev', 0);
INSERT INTO `parametres` VALUES (311, 'acquisition', 'pdfdev_pos_logo', '10,10,20,20', 'Position du logo: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur', 'pdfdev', 0);
INSERT INTO `parametres` VALUES (312, 'acquisition', 'pdfdev_pos_raison', '35,10,100,10,16', 'Position Raison sociale: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police', 'pdfdev', 0);
INSERT INTO `parametres` VALUES (313, 'acquisition', 'pdfdev_pos_date', '150,10,0,6,8', 'Position Date: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police', 'pdfdev', 0);
INSERT INTO `parametres` VALUES (314, 'acquisition', 'pdfdev_pos_adr_fac', '10,35,60,5,10', 'Position Adresse de facturation: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police', 'pdfdev', 0);
INSERT INTO `parametres` VALUES (315, 'acquisition', 'pdfdev_pos_adr_liv', '10,75,60,5,10', 'Position Adresse de livraison: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police', 'pdfdev', 0);
INSERT INTO `parametres` VALUES (316, 'acquisition', 'pdfdev_pos_adr_fou', '100,55,100,6,14', 'Position Adresse fournisseur: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police', 'pdfdev', 0);
INSERT INTO `parametres` VALUES (317, 'acquisition', 'pdfdev_pos_num', '10,110,0,10,16', 'Position numéro de commande: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police', 'pdfdev', 0);
INSERT INTO `parametres` VALUES (318, 'acquisition', 'pdfdev_text_size', '10', 'Taille de la police texte', 'pdfdev', 0);
INSERT INTO `parametres` VALUES (319, 'acquisition', 'pdfdev_text_before', '', 'Texte avant le tableau de commande', 'pdfdev', 0);
INSERT INTO `parametres` VALUES (320, 'acquisition', 'pdfdev_comment', '0', 'Affichage des commentaires : 0=non, 1=oui', 'pdfdev', 0);
INSERT INTO `parametres` VALUES (321, 'acquisition', 'pdfdev_text_after', '', 'Texte après le tableau de commande', 'pdfdev', 0);
INSERT INTO `parametres` VALUES (322, 'acquisition', 'pdfdev_tab_dev', '5,10', 'Table de commandes: Hauteur ligne,Taille police', 'pdfdev', 0);
INSERT INTO `parametres` VALUES (323, 'acquisition', 'pdfdev_pos_footer', '15,8', 'Position bas de page: Distance par rapport au bas de page, Taille police', 'pdfdev', 0);
INSERT INTO `parametres` VALUES (324, 'acquisition', 'pdfdev_pos_sign', '10,60,5,10', 'Position signature: Distance par rapport au bord gauche de la page, Largeur, Hauteur ligne,Taille police', 'pdfdev', 0);
INSERT INTO `parametres` VALUES (325, 'acquisition', 'pdfdev_text_sign', '&#3740;&#3769;&#3785;&#3758;&#3761;&#3738;&#3740;&#3764;&#3732;&#3722;&#3757;&#3738;&#3755;&#3789;&#3754;&#3760;&#3805;&#3768;&#3732;.', 'Texte signature', 'pdfdev', 0);
INSERT INTO `parametres` VALUES (326, 'opac', 'export_allow', '1', 'Export de notices à partir de l''opac : \n 0 : interdit \n 1 : pour tous \n 2 : pour les abonnés uniquement', 'a_general', 0);
INSERT INTO `parametres` VALUES (327, 'opac', 'resa_planning', '0', 'Utiliser un planning de réservation ? \n 0: Non \n 1: Oui', 'a_general', 0);
INSERT INTO `parametres` VALUES (328, 'opac', 'resa_contact', '<a href=''mailto:pmb@sigb.net''>pmb@sigb.net</a>', 'Code HTML d''information sur la personne à contacter par exemple en cas de problème de réservation.', 'a_general', 0);
INSERT INTO `parametres` VALUES (329, 'opac', 'default_operator', '0', 'Opérateur par défaut. 0 : OR, 1 : AND.', 'c_recherche', 0);
INSERT INTO `parametres` VALUES (330, 'opac', 'modules_search_all', '2', 'Recherche simple dans l''ensemble des champs :0 : interdite,  1 : autorisée,  2 : autorisée et validée par défaut', 'c_recherche', 0);
INSERT INTO `parametres` VALUES (331, 'acquisition', 'pdfliv_format_page', '210x297', 'Largeur x Hauteur de la page en mm', 'pdfliv', 0);
INSERT INTO `parametres` VALUES (332, 'acquisition', 'pdfliv_orient_page', 'P', 'Orientation de la page: P=Portrait, L=Paysage', 'pdfliv', 0);
INSERT INTO `parametres` VALUES (333, 'acquisition', 'pdfliv_marges_page', '10,20,10,10', 'Marges de page en mm : Haut,Bas,Droite,Gauche', 'pdfliv', 0);
INSERT INTO `parametres` VALUES (334, 'acquisition', 'pdfliv_pos_raison', '10,10,100,10,16', 'Position Raison sociale: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police', 'pdfliv', 0);
INSERT INTO `parametres` VALUES (335, 'acquisition', 'pdfliv_pos_adr_liv', '10,20,60,5,10', 'Position Adresse de livraison: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police', 'pdfliv', 0);
INSERT INTO `parametres` VALUES (336, 'acquisition', 'pdfliv_pos_adr_fou', '110,20,100,5,10', 'Position éléments fournisseur: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police', 'pdfliv', 0);
INSERT INTO `parametres` VALUES (337, 'acquisition', 'pdfliv_pos_num', '10,60,0,6,14', 'Position numéro Commande/Livraison: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police', 'pdfliv', 0);
INSERT INTO `parametres` VALUES (338, 'acquisition', 'pdfliv_tab_liv', '5,10', 'Table de livraisons: Hauteur ligne,Taille police', 'pdfliv', 0);
INSERT INTO `parametres` VALUES (339, 'acquisition', 'pdfliv_pos_footer', '15,8', 'Position bas de page: Distance par rapport au bas de page, Taille police', 'pdfliv', 0);
INSERT INTO `parametres` VALUES (340, 'pmb', 'default_operator', '0', 'Opérateur par défaut. \n 0 : OR, \n 1 : AND.', '', 0);
INSERT INTO `parametres` VALUES (341, 'mailretard', 'priorite_email_3', '0', 'Faire le troisième niveau de relance par mail :\n 0 : Non, lettre \n 1 : Oui, par mail', '', 0);
INSERT INTO `parametres` VALUES (342, 'opac', 'show_suggest', '0', 'Proposer de faire des suggestions dans l''OPAC.\n 0 : Non.\n 1 : Oui, avec authentification.\n 2 : Oui, sans authentification.', 'f_modules', 0);
INSERT INTO `parametres` VALUES (343, 'acquisition', 'email_sugg', '0', 'Information par email de l''évolution des suggestions.\n 0 : Non\n 1 : Oui', '', 0);
INSERT INTO `parametres` VALUES (344, 'acquisition', 'pdfliv_text_size', '10', 'Taille de la police texte', 'pdfliv', 0);
INSERT INTO `parametres` VALUES (345, 'acquisition', 'pdfliv_pos_date', '170,10,0,6,8', 'Position Date: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police', 'pdfliv', 0);
INSERT INTO `parametres` VALUES (346, 'acquisition', 'pdffac_text_size', '10', 'Taille de la police texte', 'pdffac', 0);
INSERT INTO `parametres` VALUES (347, 'acquisition', 'pdffac_format_page', '210x297', 'Largeur x Hauteur de la page en mm', 'pdffac', 0);
INSERT INTO `parametres` VALUES (348, 'acquisition', 'pdffac_orient_page', 'P', 'Orientation de la page: P=Portrait, L=Paysage', 'pdffac', 0);
INSERT INTO `parametres` VALUES (349, 'acquisition', 'pdffac_marges_page', '10,20,10,10', 'Marges de page en mm : Haut,Bas,Droite,Gauche', 'pdffac', 0);
INSERT INTO `parametres` VALUES (350, 'acquisition', 'pdffac_pos_raison', '10,10,100,10,16', 'Position Raison sociale: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police', 'pdffac', 0);
INSERT INTO `parametres` VALUES (351, 'acquisition', 'pdffac_pos_date', '170,10,0,6,8', 'Position Date: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police', 'pdffac', 0);
INSERT INTO `parametres` VALUES (352, 'acquisition', 'pdffac_pos_adr_fac', '10,20,60,5,10', 'Position Adresse de facturation: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police', 'pdffac', 0);
INSERT INTO `parametres` VALUES (353, 'acquisition', 'pdffac_pos_adr_fou', '110,20,100,5,10', 'Position éléments fournisseur: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police', 'pdffac', 0);
INSERT INTO `parametres` VALUES (354, 'acquisition', 'pdffac_pos_num', '10,60,0,6,14', 'Position numéro Commande/Facture: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police', 'pdffac', 0);
INSERT INTO `parametres` VALUES (355, 'acquisition', 'pdffac_tab_fac', '5,10', 'Table de facturation: Hauteur ligne,Taille police', 'pdffac', 0);
INSERT INTO `parametres` VALUES (356, 'acquisition', 'pdffac_pos_tot', '10,40,5,10', 'Position total de commande: Distance par rapport au bord gauche de la page, Largeur, Hauteur ligne,Taille police', 'pdffac', 0);
INSERT INTO `parametres` VALUES (357, 'acquisition', 'pdffac_pos_footer', '15,8', 'Position bas de page: Distance par rapport au bas de page, Taille police', 'pdffac', 0);
INSERT INTO `parametres` VALUES (358, 'acquisition', 'pdfsug_text_size', '8', 'Taille de la police texte', 'pdfsug', 0);
INSERT INTO `parametres` VALUES (359, 'acquisition', 'pdfsug_format_page', '210x297', 'Largeur x Hauteur de la page en mm', 'pdfsug', 0);
INSERT INTO `parametres` VALUES (360, 'acquisition', 'pdfsug_orient_page', 'P', 'Orientation de la page: P=Portrait, L=Paysage', 'pdfsug', 0);
INSERT INTO `parametres` VALUES (361, 'acquisition', 'pdfsug_marges_page', '10,20,10,10', 'Marges de page en mm : Haut,Bas,Droite,Gauche', 'pdfsug', 0);
INSERT INTO `parametres` VALUES (362, 'acquisition', 'pdfsug_pos_titre', '10,10,100,10,16', 'Position titre: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police', 'pdfsug', 0);
INSERT INTO `parametres` VALUES (363, 'acquisition', 'pdfsug_pos_date', '170,10,0,6,8', 'Position Date: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police', 'pdfsug', 0);
INSERT INTO `parametres` VALUES (364, 'acquisition', 'pdfsug_tab_sug', '5,10', 'Table de suggestions: Hauteur ligne,Taille police', 'pdfsug', 0);
INSERT INTO `parametres` VALUES (365, 'acquisition', 'pdfsug_pos_footer', '15,8', 'Position bas de page: Distance par rapport au bas de page, Taille police', 'pdfsug', 0);
INSERT INTO `parametres` VALUES (366, 'acquisition', 'mel_rej_obj', 'Rejet suggestion', 'Objet du mail de rejet de suggestion', 'mel', 0);
INSERT INTO `parametres` VALUES (367, 'acquisition', 'mel_rej_cor', 'Votre suggestion du !!date!! est rejetée.\n\n', 'Corps du mail de rejet de suggestion', 'mel', 0);
INSERT INTO `parametres` VALUES (368, 'acquisition', 'mel_con_obj', 'Confirmation suggestion', 'Objet du mail de confirmation de suggestion', 'mel', 0);
INSERT INTO `parametres` VALUES (369, 'acquisition', 'mel_con_cor', 'Votre suggestion du !!date!! est retenue pour un prochain achat.\n\n', 'Corps du mail de confirmation de suggestion', 'mel', 0);
INSERT INTO `parametres` VALUES (370, 'acquisition', 'mel_aba_obj', 'Abandon suggestion', 'Objet du mail d''abandon de suggestion', 'mel', 0);
INSERT INTO `parametres` VALUES (371, 'acquisition', 'mel_aba_cor', 'Votre suggestion du !!date!! n''est pas retenue ou n''est pas disponible à la vente.\n\n', 'Corps du mail d''abandon de suggestion', 'mel', 0);
INSERT INTO `parametres` VALUES (372, 'acquisition', 'mel_cde_obj', 'Commande suggestion', 'Objet du mail de commande de suggestion', 'mel', 0);
INSERT INTO `parametres` VALUES (373, 'acquisition', 'mel_cde_cor', 'Votre suggestion du !!date!! est en commande.\n\n', 'Corps du mail de commande de suggestion', 'mel', 0);
INSERT INTO `parametres` VALUES (374, 'acquisition', 'mel_rec_obj', 'Réception suggestion', 'Objet du mail de réception de suggestion', 'mel', 0);
INSERT INTO `parametres` VALUES (375, 'acquisition', 'mel_rec_cor', 'Votre suggestion du !!date!! a été reçue et sera bientôt disponible en réservation.\n\n', 'Corps du mail de réception de suggestion', 'mel', 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `pret`
-- 

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
-- Contenu de la table `pret`
-- 

INSERT INTO `pret` VALUES (9, 37, '2006-08-24 18:42:53', '2006-09-07', 1, 0, '0000-00-00', 0);
INSERT INTO `pret` VALUES (10, 38, '2006-08-24 18:47:30', '2006-09-07', 2, 0, '0000-00-00', 0);
INSERT INTO `pret` VALUES (10, 39, '2006-08-24 18:54:00', '2006-09-07', 3, 0, '0000-00-00', 0);
INSERT INTO `pret` VALUES (11, 24, '2006-08-28 14:35:57', '2006-09-11', 5, 0, '0000-00-00', 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `pret_archive`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- 
-- Contenu de la table `pret_archive`
-- 

INSERT INTO `pret_archive` VALUES (1, '2006-08-24 18:42:53', '2006-09-07 00:00:00', '856', 'Vientiane', 'Programmeur', 13082007, 7, 4, 1, 1, '', 1, 1, 12, 0, 10, 37, 63, 0, '');
INSERT INTO `pret_archive` VALUES (2, '2006-08-24 18:47:30', '2006-09-07 00:00:00', '856', 'Vientiane', '', 0, 7, 2, 0, 1, '', 1, 1, 12, 0, 10, 38, 60, 0, '');
INSERT INTO `pret_archive` VALUES (3, '2006-08-24 18:54:00', '2006-09-07 00:00:00', '856', 'Vientiane', '', 0, 7, 2, 0, 1, '', 1, 1, 12, 0, 10, 39, 64, 0, '');
INSERT INTO `pret_archive` VALUES (4, '2006-08-28 14:34:07', '2006-09-04 16:43:20', '', '', '', 0, 7, 2, 0, 18, 'MAG GEO', 1, 7, 12, 2, 10, 21, 0, 2, '');
INSERT INTO `pret_archive` VALUES (5, '2006-08-28 14:35:57', '2006-09-11 00:00:00', '', '', '', 0, 7, 2, 0, 13, 'CD COC', 13, 1, 12, 2, 10, 24, 44, 0, '');

-- --------------------------------------------------------

-- 
-- Structure de la table `procs`
-- 

CREATE TABLE `procs` (
  `idproc` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `requete` blob NOT NULL,
  `comment` tinytext NOT NULL,
  `autorisations` mediumtext,
  `parameters` text,
  PRIMARY KEY  (`idproc`),
  KEY `idproc` (`idproc`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

-- 
-- Contenu de la table `procs`
-- 

INSERT INTO `procs` VALUES (1, 'Liste expl/statut', 0x73656c656374206578706c5f636f74652c206578706c5f63622c20746974312066726f6d206578656d706c61697265732c206e6f7469636573207768657265206578706c5f7374617475743d2121706172616d31212120616e64206578706c5f6e6f746963653d6e6f746963655f6964206f72646572206279206578706c5f636f7465, 'Liste paramétrée d''exemplaires par statut ', '1 2', '<?xml version="1.0" encoding="iso-8859-1"?>\n<FIELDS>\n <FIELD NAME="param1" MANDATORY="yes">\n  <ALIAS><![CDATA[Statut]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR="query_list">\r\n <QUERY><![CDATA[select idstatut,statut_libelle from docs_statut]]></QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""><![CDATA[Choisissez un statut]]></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>');
INSERT INTO `procs` VALUES (2, 'Comptage expl /statut', 0x73656c656374207374617475745f6c6962656c6c652066726f6d206578656d706c61697265732c20646f63735f7374617475742c20636f756e74282a29206173204e6272652077686572652069647374617475743d6578706c5f7374617475742067726f7570206279207374617475745f6c6962656c6c65206f72646572206279206964737461747574, 'Nombre d''exemplaires par statut d''exmplaire', '1 2', NULL);
INSERT INTO `procs` VALUES (3, 'Comptage expl /prêteur', 0x73656c656374206c656e6465725f6c6962656c6c652c20636f756e74282a29206173204e6272652066726f6d206578656d706c61697265732c206c656e64657273207768657265206578706c5f6f776e65723d69646c656e6465722067726f7570206279206c656e6465725f6c6962656c6c65206f72646572206279206c656e6465725f6c6962656c6c6520, 'Nombre d''exemplaires par prêteur', '1 2', NULL);
INSERT INTO `procs` VALUES (4, 'Comptage  expl /prêteur /statut', 0x73656c656374206c656e6465725f6c6962656c6c652c2069647374617475742c207374617475745f6c6962656c6c65202c20636f756e74282a29206173204e6272652066726f6d206578656d706c61697265732c206c656e646572732c20646f63735f737461747574207768657265206578706c5f6f776e65723d69646c656e64657220616e64206578706c5f7374617475743d69647374617475742067726f7570206279206c656e6465725f6c6962656c6c652c7374617475745f6c6962656c6c65206f72646572206279206c656e6465725f6c6962656c6c652c7374617475745f6c6962656c6c6520, 'Nombre d''exemplaires par prêteur et par statut d''exmplaire', '1 2', NULL);
INSERT INTO `procs` VALUES (5, 'Liste expl d''un prêteur /statut', 0x73656c656374206c656e6465725f6c6962656c6c652c207374617475745f6c6962656c6c652c206578706c5f636f74652c206578706c5f63622c20746974312066726f6d206578656d706c61697265732c206e6f74696365732c20646f63735f7374617475742c206c656e64657273207768657265206578706c5f7374617475743d2121737461747574212120616e64206578706c5f6f776e65723d212150726f707269657461697265212120616e64206578706c5f6e6f746963653d6e6f746963655f696420616e64206578706c5f7374617475743d696473746174757420616e64206578706c5f6f776e65723d69646c656e646572206f72646572206279206c656e6465725f6c6962656c6c652c207374617475745f6c6962656c6c652c206578706c5f636f74652c206578706c5f636220, 'Liste d''exemplaires d''un propriétaire par statut, cote, code-barre, titre (pratique pour lister les documents non pointés après l''import)', '1 2', '<?xml version="1.0" encoding="iso-8859-1"?>\n<FIELDS>\n <FIELD NAME="statut" MANDATORY="yes">\n  <ALIAS><![CDATA[Statut]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR="query_list">\r\n <QUERY>select idstatut, statut_libelle from docs_statut</QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n <FIELD NAME="Proprietaire" MANDATORY="yes">\n  <ALIAS><![CDATA[Proprietaire]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR="query_list">\r\n <QUERY>select idlender, lender_libelle from lenders</QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>');
INSERT INTO `procs` VALUES (6, 'Comptage expl /section', 0x73656c65637420696473656374696f6e2c2073656374696f6e5f6c6962656c6c652c20636f756e74282a29206173204e6272652066726f6d206578656d706c61697265732c20646f63735f73656374696f6e20776865726520696473656374696f6e3d6578706c5f73656374696f6e2067726f757020627920696473656374696f6e2c2073656374696f6e5f6c6962656c6c65206f7264657220627920696473656374696f6e, 'Nombre d''exemplaires par section', '1 2', NULL);
INSERT INTO `procs` VALUES (7, 'Liste expl pour une ou plusieurs sections par prêteur', 0x73656c6563742073656374696f6e5f6c6962656c6c652c206578706c5f636f74652c206578706c5f63622c20746974312066726f6d206578656d706c61697265732c206e6f74696365732c20646f63735f73656374696f6e2c206c656e6465727320776865726520696473656374696f6e20696e2028212173656374696f6e7321212920616e64206578706c5f6f776e65723d212170726574657572212120616e64206578706c5f6e6f746963653d6e6f746963655f696420616e64206578706c5f73656374696f6e3d696473656374696f6e20616e64206578706c5f6f776e65723d69646c656e646572206f726465722062792073656374696f6e5f6c6962656c6c652c206578706c5f636f74652c206578706c5f636220, 'Liste des exemplaires ayant une ou plusieurs sections particulières pour un prêteur', '1 2', '<?xml version="1.0" encoding="iso-8859-1"?>\n<FIELDS>\n <FIELD NAME="sections" MANDATORY="yes">\n  <ALIAS><![CDATA[Section(s)]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR="query_list">\r\n <QUERY><![CDATA[select idsection, section_libelle from docs_section]]></QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""><![CDATA[]]></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n <FIELD NAME="preteur" MANDATORY="yes">\n  <ALIAS><![CDATA[Prêteur]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR="query_list">\r\n <QUERY><![CDATA[select idlender, lender_libelle from lenders order by idlender]]></QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""><![CDATA[Choisissez un prêteur]]></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>');
INSERT INTO `procs` VALUES (8, 'Stat : Compte expl /propriétaire', 0x73656c656374206c656e6465725f6c6962656c6c652061732050726f7072696f2c20636f756e74282a29206173204e6272652066726f6d206578656d706c61697265732c206c656e646572732077686572652069646c656e6465723d6578706c5f6f776e65722067726f7570206279206578706c5f6f776e65722c206c656e6465725f6c6962656c6c65, 'Nbre d''exemplaires par propriétaire d''exemplaire', '1 2', NULL);
INSERT INTO `procs` VALUES (9, 'Liste expl du fonds propre', 0x73656c656374207374617475745f6c6962656c6c652c206578706c5f636f74652c206578706c5f63622c20746974312066726f6d206578656d706c61697265732c206e6f74696365732c20646f63735f737461747574207768657265206578706c5f6f776e65723d3020616e64206578706c5f6e6f746963653d6e6f746963655f696420616e64206578706c5f7374617475743d6964737461747574206f72646572206279207374617475745f6c6962656c6c652c206578706c5f636f74652c206578706c5f636220, 'Liste des exemplaires du fonds propre par statut, cote, code-barre, titre', '1 2', NULL);
INSERT INTO `procs` VALUES (10, 'Liste expl pour un prêteur', 0x73656c656374206578706c5f636f74652c206578706c5f63622c20746974312066726f6d206578656d706c61697265732c206e6f74696365732c20646f63735f7374617475742c206c656e64657273207768657265206578706c5f6f776e65723d212170726f707269657461697265212120616e64206578706c5f6e6f746963653d6e6f746963655f696420616e64206578706c5f7374617475743d696473746174757420616e64206578706c5f6f776e65723d69646c656e646572206f7264657220627920206578706c5f636f74652c206578706c5f636220, 'Liste des exemplaires pour 1 propriétaire trié par cote et code-barre', '1 2', '<?xml version="1.0" encoding="iso-8859-1"?>\n<FIELDS>\n <FIELD NAME="proprietaire" MANDATORY="yes">\n  <ALIAS><![CDATA[Propriétaire]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR="query_list">\r\n <QUERY>select idlender, lender_libelle from lenders order by idlender</QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE="">Choisissez un prêteur</UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>');
INSERT INTO `procs` VALUES (11, 'Comptage lecteurs /categ', 0x73656c656374206c6962656c6c652c20636f756e74282a2920617320274e627265206c65637465757273272066726f6d20656d70722c20656d70725f63617465672077686572652069645f63617465675f656d70723d656d70725f63617465672067726f7570206279206c6962656c6c65206f72646572206279206c6962656c6c65, 'Nombre de lecteurs par catégorie', '1 2', NULL);
INSERT INTO `procs` VALUES (13, 'Liste lecteurs /catégories', 0x73656c656374206c6962656c6c6520617320436174e9676f7269652c20656d70725f6e6f6d206173204e6f6d2c20656d70725f7072656e6f6d206173205072e96e6f6d2c20656d70725f7965617220617320446174654e61697373616e63652066726f6d20656d70722c20656d70725f63617465672077686572652069645f63617465675f656d70723d656d70725f6361746567206f72646572206279206c6962656c6c652c20656d70725f6e6f6d2c20656d70725f7072656e6f6d, 'Liste des lecteurs par catégorie de lecteur, lecteur', '1 2', NULL);
INSERT INTO `procs` VALUES (14, 'Prêts par catégories', 0x53454c45435420656d70725f63617465672e6c6962656c6c6520617320436174e9676f7269652c20656d70722e656d70725f6e6f6d206173204e6f6d2c20656d70722e656d70725f7072656e6f6d206173205072e96e6f6d2c20656d70722e656d70725f6362206173204e756de9726f2c206578656d706c61697265732e6578706c5f636220617320436f646542617272652c206e6f74696365732e746974312061732054697472652046524f4d20707265742c656d70722c656d70725f63617465672c6578656d706c61697265732c6e6f746963657320574845524520656d70725f63617465672e69645f63617465675f656d707220696e2028212163617465676f72696521212920616e6420656d70722e656d70725f6361746567203d20656d70725f63617465672e69645f63617465675f656d707220616e6420707265742e707265745f6964656d7072203d20656d70722e69645f656d707220616e6420707265742e707265745f69646578706c203d206578656d706c61697265732e6578706c5f696420616e64206578656d706c61697265732e6578706c5f6e6f74696365203d206e6f74696365732e6e6f746963655f6964206f7264657220627920312c322c332c36, 'Liste des exemplaires en prêt pour une ou plusieurs catégories de lecteurs', '1 2', '<?xml version="1.0" encoding="iso-8859-1"?>\n<FIELDS>\n <FIELD NAME="categorie" MANDATORY="yes">\n  <ALIAS><![CDATA[categorie]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR="query_list">\r\n <QUERY><![CDATA[select id_categ_empr, libelle from empr_categ order by libelle]]></QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""><![CDATA[]]></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>');
INSERT INTO `procs` VALUES (20, 'Liste fonds propre / statut', 0x73656c656374207374617475745f6c6962656c6c652c206578706c5f636f74652c206578706c5f63622c20746974312066726f6d206578656d706c61697265732c206e6f74696365732c20646f63735f737461747574207768657265206578706c5f6f776e65723d3020616e64206578706c5f6e6f746963653d6e6f746963655f696420616e64206578706c5f7374617475743d6964737461747574206f72646572206279207374617475745f6c6962656c6c652c206578706c5f636f74652c206578706c5f636220, 'Pointage fonds propre', '1 2', NULL);
INSERT INTO `procs` VALUES (21, 'Stat : Compte lecteurs /age', 0x53454c45435420636f756e74282a292c2043415345205748454e2020282121706172616d312121202d20656d70725f7965617229203c3d203133205448454e20274a757371756520313320616e7327205748454e20282121706172616d312121202d20656d70725f7965617229203e313320616e6420282121706172616d312121202d20656d70725f79656172293c3d3234205448454e2027313420e020323420616e7327205748454e20282121706172616d312121202d20656d70725f79656172293e323420616e6420282121706172616d312121202d20656d70725f79656172293c3d3539205448454e2027323520e020323920616e7327205748454e20282121706172616d312121202d20656d70725f79656172293e3539205448454e2027363020616e7320657420706c7573272020454c5345202765727265757220737572206167652720454e442061732063617465675f6167652066726f6d20656d707220776865726520656d70725f636174656720696e2028212163617465676f72696521212920616e6420287965617228656d70725f646174655f65787069726174696f6e293d2121706172616d312121206f72207965617228656d70725f646174655f6164686573696f6e293d2121706172616d312121292067726f75702062792063617465675f616765, 'Nbre de lecteurs par tranche d''age pour une année', '1 2', '<?xml version="1.0" encoding="iso-8859-1"?>\n<FIELDS>\n <FIELD NAME="param1" MANDATORY="yes">\n  <ALIAS><![CDATA[Année de calcul]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR="text">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS> \n </FIELD>\n <FIELD NAME="categorie" MANDATORY="yes">\n  <ALIAS><![CDATA[Catégorie]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR="query_list">\r\n <QUERY>select id_categ_empr, libelle from empr_categ order by libelle</QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>');
INSERT INTO `procs` VALUES (22, 'Stat : Compte lecteurs /sexe /age', 0x53454c45435420636f756e74282a292c2063617365207768656e20656d70725f736578653d273127207468656e2027486f6d6d657327207768656e20656d70725f736578653d273227207468656e202746656d6d65732720656c736520276572726575722073757220736578652720656e6420617320536578652c2043415345205748454e2020282121706172616d312121202d20656d70725f7965617229203c3d203133205448454e20274a757371756520313320616e7327205748454e20282121706172616d312121202d20656d70725f7965617229203e313320616e6420282121706172616d312121202d20656d70725f7965617229203c3d203234205448454e2027313420e020323420616e7327205748454e20282121706172616d312121202d20656d70725f7965617229203e323420616e6420282121706172616d312121202d20656d70725f7965617229203c3d203539205448454e2027323520e020353920616e7327205748454e20282121706172616d312121202d20656d70725f7965617229203e3539205448454e2027363020616e7320657420706c7573272020454c5345202765727265757220737572206167652720454e442061732063617465675f6167652066726f6d20656d707220776865726520656d70725f636174656720696e2028212163617465676f72696521212920616e6420287965617228656d70725f646174655f65787069726174696f6e293d2121706172616d312121206f72207965617228656d70725f646174655f6164686573696f6e293d2121706172616d312121292067726f757020627920736578652c2063617465675f616765, 'Nbre de lecteurs par sexe et tranche d''age pour une année', '1 2', '<?xml version="1.0" encoding="iso-8859-1"?>\n<FIELDS>\n <FIELD NAME="param1" MANDATORY="yes">\n  <ALIAS><![CDATA[Année de calcul]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR="text">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS> \n </FIELD>\n <FIELD NAME="categorie" MANDATORY="yes">\n  <ALIAS><![CDATA[Catégorie]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR="query_list">\r\n <QUERY>select id_categ_empr, libelle from empr_categ order by libelle</QUERY>\r\n <MULTIPLE>no</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>');
INSERT INTO `procs` VALUES (23, 'Stat : Compte lecteurs /ville /catégorie', 0x73656c65637420656d70725f76696c6c652061732056696c6c652c20636f756e74282a29206173204e6272652066726f6d20656d707220776865726520656d70725f636174656720696e2028212163617465676f72696521212920616e6420287965617228656d70725f646174655f65787069726174696f6e293d2121616e6e65652121206f72207965617228656d70725f646174655f6164686573696f6e293d2121616e6e65652121292067726f757020627920656d70725f76696c6c65206f7264657220627920656d70725f76696c6c65, 'Nbre de lecteurs par ville de résidence pour une ou plusieurs catégorie', '1 2', '<?xml version="1.0" encoding="iso-8859-1"?>\n<FIELDS>\n <FIELD NAME="categorie" MANDATORY="yes">\n  <ALIAS><![CDATA[Catégorie]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR="query_list">\r\n <QUERY>select id_categ_empr, libelle from empr_categ order by libelle</QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n <FIELD NAME="annee" MANDATORY="yes">\n  <ALIAS><![CDATA[Année de calcul]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR="text">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>');
INSERT INTO `procs` VALUES (24, 'Stat : Compte élèves', 0x53454c45435420636f756e74282a29206173206e6272655f656c6576652066726f6d20656d707220776865726520656d70725f636174656720696e2028212163617465676f72696521212920616e6420616e6420287965617228656d70725f646174655f65787069726174696f6e293d2121616e6e65652121206f72207965617228656d70725f646174655f6164686573696f6e293d2121616e6e6565212129, 'Nbre de lecteurs ''Elève'' = catégorie à sélectionner ', '1 2', '<?xml version="1.0" encoding="iso-8859-1"?>\n<FIELDS>\n <FIELD NAME="categorie" MANDATORY="yes">\n  <ALIAS><![CDATA[Catégorie de lecteurs]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR="query_list">\r\n <QUERY><![CDATA[select id_categ_empr, libelle from empr_categ order by libelle]]></QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""><![CDATA[]]></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n <FIELD NAME="annee" MANDATORY="yes">\n  <ALIAS><![CDATA[Année de calcul]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR="text">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>');
INSERT INTO `procs` VALUES (25, 'Stat : Compte prêts pour élève ou profs', 0x53454c45435420636f756e74282a29206173206e6272655f707265745f656c6576652066726f6d20707265745f61726368697665207768657265206172635f656d70725f636174656720696e2028212163617465676f72696521212920616e642079656172286172635f646562757429203d20272121706172616d312121270d0a, 'Nbre de prêts pour les élèves de l''école ou pour les profs (prêts pour la classe) pour une année', '1 2', '<?xml version="1.0" encoding="iso-8859-1"?>\n<FIELDS>\n <FIELD NAME="categorie" MANDATORY="yes">\n  <ALIAS><![CDATA[Catégorie]]></ALIAS>\n  <TYPE>query_list</TYPE>\n<OPTIONS FOR="query_list">\r\n <QUERY><![CDATA[select id_categ_empr, libelle from empr_categ order by libelle]]></QUERY>\r\n <MULTIPLE>yes</MULTIPLE>\r\n <UNSELECT_ITEM VALUE=""><![CDATA[]]></UNSELECT_ITEM>\r\n</OPTIONS>\n </FIELD>\n <FIELD NAME="param1" MANDATORY="yes">\n  <ALIAS><![CDATA[Année de calcul]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR="text">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS>\n </FIELD>\n</FIELDS>');
INSERT INTO `procs` VALUES (26, 'Stat : Compte prêts Documentaires E', 0x53454c4543542079656172286172635f64656275742920617320616e6e65652c206d6f6e746820286172635f646562757429206173206d6f69732c20636f756e74282a29206e625f707265745f446f63755f452046524f4d20707265745f6172636869766520776865726520286c65667420286172635f6578706c5f636f74652c32293d27452027206f72206c65667420286172635f6578706c5f636f74652c33293d2745422027206f72206c65667420286172635f6578706c5f636f74652c32293d27452e2729616e642079656172286172635f646562757429203d20272121706172616d312121272067726f757020627920616e6e65652c206d6f6973206f7264657220627920616e6e65652c206d6f6973, 'Nbre de prêts de documentaires Enfants pour une année', '1 2', '<?xml version="1.0" encoding="iso-8859-1"?>\n<FIELDS>\n <FIELD NAME="param1" MANDATORY="yes">\n  <ALIAS><![CDATA[Année de calcul]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR="text">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS> \n </FIELD>\n</FIELDS>');
INSERT INTO `procs` VALUES (27, 'Stat : Compte prêts Fictions E', 0x53454c4543542079656172286172635f64656275742920617320616e6e65652c206d6f6e746820286172635f646562757429206173206d6f69732c20636f756e74282a29206e625f70726574735f66696374696f6e5f452046524f4d20707265745f6172636869766520776865726520286c65667420286172635f6578706c5f636f74652c33293d2745412027206f72206c65667420286172635f6578706c5f636f74652c33293d2745424427206f72206c65667420286172635f6578706c5f636f74652c33293d2745432027206f72206c65667420286172635f6578706c5f636f74652c33293d27455220272920616e642079656172286172635f646562757429203d20272121706172616d312121272067726f757020627920616e6e65652c206d6f6973206f7264657220627920616e6e65652c206d6f6973, 'Nbre de prêts de fictions Enfants pour une année', '1 2', '<?xml version="1.0" encoding="iso-8859-1"?>\n<FIELDS>\n <FIELD NAME="param1" MANDATORY="yes">\n  <ALIAS><![CDATA[Année de calcul]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR="text">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS> \n </FIELD>\n</FIELDS>');
INSERT INTO `procs` VALUES (28, 'Stat : Compte prêts Fictions A', 0x53454c4543542079656172286172635f64656275742920617320616e6e65652c206d6f6e746820286172635f646562757429206173206d6f69732c20636f756e74282a29206e625f70726574735f66696374696f6e5f412046524f4d20707265745f6172636869766520776865726520286c65667420286172635f6578706c5f636f74652c31293d275227206f72206c65667420286172635f6578706c5f636f74652c33293d2742442027206f72206c65667420286172635f6578706c5f636f74652c32293d274a5227206f72206c65667420286172635f6578706c5f636f74652c33293d274a4244272920616e64206c65667420286172635f6578706c5f636f74652c33293c3e275245202720616e642079656172286172635f646562757429203d20272121706172616d312121272067726f757020627920616e6e65652c206d6f6973206f7264657220627920616e6e65652c206d6f6973, 'Nbre de prêts de fictions Jeunes ou Adultes pour une année', '1 2', '<?xml version="1.0" encoding="iso-8859-1"?>\n<FIELDS>\n <FIELD NAME="param1" MANDATORY="yes">\n  <ALIAS><![CDATA[Année de calcul]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR="text">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS> \n </FIELD>\n</FIELDS>');
INSERT INTO `procs` VALUES (29, 'Stat : Compte prêts Documentaires A & J', 0x53454c4543542079656172286172635f64656275742920617320616e6e65652c206d6f6e746820286172635f646562757429206173206d6f69732c20636f756e74282a29206e625f70726574735f446f63755f412046524f4d20707265745f6172636869766520776865726520286c65667420286172635f6578706c5f636f74652c32293d27482027206f72206c65667420286172635f6578706c5f636f74652c32293d27422027206f72206c65667420286172635f6578706c5f636f74652c33293d2746522027206f72206c65667420286172635f6578706c5f636f74652c32293d274a2027206f72206c65667420286172635f6578706c5f636f74652c32293d274a2e27206f72206c656674286172635f6578706c5f636f74652c3129206265747765656e2027302720616e64202739272920616e642079656172286172635f646562757429203d20272121706172616d312121272067726f757020627920616e6e65652c206d6f6973206f7264657220627920616e6e65652c206d6f6973, 'Nbre de prêts de documentaires Jeunes ou Adultes pour une année', '1 2', '<?xml version="1.0" encoding="iso-8859-1"?>\n<FIELDS>\n <FIELD NAME="param1" MANDATORY="yes">\n  <ALIAS><![CDATA[Année de calcul]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR="text">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS> \n </FIELD>\n</FIELDS>');
INSERT INTO `procs` VALUES (30, 'Stat : Compte prêts TOTAL (hors Pério)', 0x53454c4543542079656172286172635f64656275742920617320616e6e65652c206d6f6e746820286172635f646562757429206173206d6f69732c20636f756e74282a29206e625f70726574735f544f54414c2046524f4d20707265745f61726368697665207768657265206172635f6578706c5f636f7465206e6f74206c696b6520275020252720616e642079656172286172635f646562757429203d20272121706172616d312121272067726f757020627920616e6e65652c206d6f6973206f7264657220627920616e6e65652c206d6f6973, 'Nbre total de prêts hors périodiques pour une année', '1 2', '<?xml version="1.0" encoding="iso-8859-1"?>\n<FIELDS>\n <FIELD NAME="param1" MANDATORY="yes">\n  <ALIAS><![CDATA[Année de calcul]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR="text">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS> \n </FIELD>\n</FIELDS>');
INSERT INTO `procs` VALUES (31, 'Stat : Compte prêts Périodiques', 0x53454c4543542079656172286172635f64656275742920617320616e6e65652c206d6f6e746820286172635f646562757429206173206d6f69732c20636f756e74282a29206e625f70726574735f544f54414c2046524f4d20707265745f61726368697665207768657265206172635f6578706c5f636f7465206c696b6520275020252720616e642079656172286172635f646562757429203d20272121706172616d312121272067726f757020627920616e6e65652c206d6f6973206f7264657220627920616e6e65652c206d6f6973, 'Nbre de prêts de périodiques pour une année', '1 2', '<?xml version="1.0" encoding="iso-8859-1"?>\n<FIELDS>\n <FIELD NAME="param1" MANDATORY="yes">\n  <ALIAS><![CDATA[Année de calcul]]></ALIAS>\n  <TYPE>text</TYPE>\n<OPTIONS FOR="text">\r\n <SIZE>5</SIZE>\r\n <MAXSIZE>4</MAXSIZE>\r\n</OPTIONS> \n </FIELD>\n</FIELDS>');

-- --------------------------------------------------------

-- 
-- Structure de la table `publishers`
-- 

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
  KEY `ed_id` (`ed_id`),
  KEY `ed_name` (`ed_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

-- 
-- Contenu de la table `publishers`
-- 

INSERT INTO `publishers` VALUES (1, 'L''École des loisirs', '', '', '', 'Paris', '', '', ' ecole loisirs ', NULL);
INSERT INTO `publishers` VALUES (2, 'Seuil', '', '', '', 'Paris', '', '', ' seuil ', NULL);
INSERT INTO `publishers` VALUES (3, 'Syros jeunesse', '', '', '', 'Paris', '', '', ' syros jeunesse ', NULL);
INSERT INTO `publishers` VALUES (4, 'le Cherche-Midi éd.', '', '', '', 'Paris', '', '', ' cherche midi ed ', NULL);
INSERT INTO `publishers` VALUES (5, 'Denoël', '', '', '', 'Paris', '', '', ' denoel ', NULL);
INSERT INTO `publishers` VALUES (6, 'Flammarion', '', '', '', '[Paris]', '', '', ' flammarion ', NULL);
INSERT INTO `publishers` VALUES (7, 'Glénat', '', '', '', 'Grenoble', '', '', ' glenat ', NULL);
INSERT INTO `publishers` VALUES (8, 'Vents d''Ouest', '31, rue Ernest-Renan', '', '92130', 'Issy-les-Moulineaux', 'France', 'http://www.ventsdouest.com', ' vents ouest ', NULL);
INSERT INTO `publishers` VALUES (9, 'Dupuis', '', '', '', '[Paris]', '', '', ' dupuis ', NULL);
INSERT INTO `publishers` VALUES (10, 'A. Michel', '', '', '', 'Paris', '', '', ' michel ', NULL);
INSERT INTO `publishers` VALUES (11, 'La Martinière', '', '', '', 'Paris', '', '', ' martiniere ', NULL);
INSERT INTO `publishers` VALUES (12, 'l\\''Harmattan', '', '', '', 'Paris', '', '', ' harmattan ', NULL);
INSERT INTO `publishers` VALUES (13, 'Michelin', '', '', '', 'Paris', 'France', 'www.michelin.fr', ' michelin ', NULL);
INSERT INTO `publishers` VALUES (14, 'TF1 vidéo éd.', '', '', '', 'Boulogne-Billancourt', '', '', ' tf1 video ed ', NULL);
INSERT INTO `publishers` VALUES (15, 'Conseil général du Maine-et-Loire (CG49)', 'place Michel Debré', '', '49100', 'Angers', 'France', 'http://www.cg49.fr', ' conseil general maine loire cg49 ', NULL);
INSERT INTO `publishers` VALUES (16, 'Prisma Presse', '', '', '', 'Paris', 'France', 'http://www.geomagazine.fr', ' prisma presse ', NULL);
INSERT INTO `publishers` VALUES (17, 'sound-fishing.net', '', '', '', 'Paris', 'France', 'http://www.sound-fishing.net', ' sound fishing net ', NULL);
INSERT INTO `publishers` VALUES (18, 'Forlane', '', '', '', 'Paris', 'France', '', ' forlane ', NULL);
INSERT INTO `publishers` VALUES (19, 'Rustica', '', '', '', 'Paris', 'France', '', ' rustica ', NULL);
INSERT INTO `publishers` VALUES (20, 'Dépôt de la Guerre', '', '', '', 'Paris', 'France', '', ' depot guerre ', NULL);
INSERT INTO `publishers` VALUES (21, 'Gallimard', '', '', '', '[Paris]', '', '', ' gallimard ', NULL);
INSERT INTO `publishers` VALUES (22, 'Asselin et Houzeau', '', '', '', 'Paris', '', '', ' asselin houzeau ', NULL);
INSERT INTO `publishers` VALUES (23, 'impr. de Mme Vve Mellinet', '', '', '', 'Nantes', '', '', ' impr mme vve mellinet ', NULL);
INSERT INTO `publishers` VALUES (24, 'Média 1000', '', '', '', 'Paris', '', '', ' media 1000 ', NULL);
INSERT INTO `publishers` VALUES (25, 'à»‚àº®àº‡àºžàº´àº¡àº¡àº±àº™àº—àº²àº•àº¸àº¥àº²àº”', '', '', '', '', '', '', '  ', '');
INSERT INTO `publishers` VALUES (26, 'àº™àº°àº„àº­àº™àº«àº¥àº§àº‡', '', '', '', '', '', '', '  ', '');
INSERT INTO `publishers` VALUES (27, 'à»‚àº®àº‡àºžàº´àº¡àº¡àº±àº™àº—àº²àº•àº¸àº¥àº²àº”', '', '', '856', 'àº§àº½àº‡àºˆàº±àº™', 'àº¥àº²àº§', '', '  ', '');
INSERT INTO `publishers` VALUES (28, 'àºªàº°àº–àº²àºšàº±àº™', '', '', '856', 'àº?àº³à»?àºžàº‡àº™àº°àº„àº­àº™', 'àºªàº›àº›àº¥àº²àº§', '', '  ', '');
INSERT INTO `publishers` VALUES (29, 'àº«à»?àºžàº´àºžàº´àº—àº°àºžàº±àº™', '', '', '856', 'àº?àº³à»?àºžàº‡àº™àº°àº„àº­àº™', 'àºªàº›àº›àº¥àº²àº§', '', '  ', '');

-- --------------------------------------------------------

-- 
-- Structure de la table `quotas`
-- 

CREATE TABLE `quotas` (
  `quota_type` int(10) unsigned NOT NULL default '0',
  `constraint_type` varchar(255) NOT NULL default '',
  `elements` int(10) unsigned NOT NULL default '0',
  `value` float default NULL,
  PRIMARY KEY  (`quota_type`,`constraint_type`,`elements`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `quotas`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `quotas_finance`
-- 

CREATE TABLE `quotas_finance` (
  `quota_type` int(10) unsigned NOT NULL default '0',
  `constraint_type` varchar(255) NOT NULL default '',
  `elements` int(10) unsigned NOT NULL default '0',
  `value` float default NULL,
  PRIMARY KEY  (`quota_type`,`constraint_type`,`elements`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `quotas_finance`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `recouvrements`
-- 

CREATE TABLE `recouvrements` (
  `recouvr_id` int(16) unsigned NOT NULL auto_increment,
  `empr_id` int(10) unsigned NOT NULL default '0',
  `id_expl` int(10) unsigned NOT NULL default '0',
  `date_rec` date NOT NULL default '0000-00-00',
  `libelle` varchar(255) default NULL,
  `montant` decimal(16,2) default '0.00',
  PRIMARY KEY  (`recouvr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `recouvrements`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `resa`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Contenu de la table `resa`
-- 

INSERT INTO `resa` VALUES (1, 9, 60, 0, '2006-08-24 18:40:11', '0000-00-00', '0000-00-00', '', 0);
INSERT INTO `resa` VALUES (2, 10, 60, 0, '2006-08-24 18:46:32', '0000-00-00', '0000-00-00', '', 0);
INSERT INTO `resa` VALUES (3, 10, 0, 3, '2006-08-24 18:53:20', '0000-00-00', '0000-00-00', '', 0);
INSERT INTO `resa` VALUES (4, 11, 46, 0, '2006-08-28 14:32:23', '0000-00-00', '0000-00-00', '', 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `resa_ranger`
-- 

CREATE TABLE `resa_ranger` (
  `resa_cb` varchar(14) NOT NULL default '',
  PRIMARY KEY  (`resa_cb`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `resa_ranger`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `responsability`
-- 

CREATE TABLE `responsability` (
  `responsability_author` mediumint(8) unsigned NOT NULL default '0',
  `responsability_notice` mediumint(8) unsigned NOT NULL default '0',
  `responsability_fonction` char(3) NOT NULL default '',
  `responsability_type` mediumint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`responsability_author`,`responsability_notice`,`responsability_fonction`),
  KEY `responsability_author` (`responsability_author`),
  KEY `responsability_notice` (`responsability_notice`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `responsability`
-- 

INSERT INTO `responsability` VALUES (1, 1, '070', 0);
INSERT INTO `responsability` VALUES (2, 2, '070', 0);
INSERT INTO `responsability` VALUES (3, 3, '070', 0);
INSERT INTO `responsability` VALUES (4, 4, '070', 0);
INSERT INTO `responsability` VALUES (5, 5, '070', 0);
INSERT INTO `responsability` VALUES (6, 6, '070', 0);
INSERT INTO `responsability` VALUES (8, 7, '068', 2);
INSERT INTO `responsability` VALUES (7, 7, '070', 0);
INSERT INTO `responsability` VALUES (9, 8, '070', 0);
INSERT INTO `responsability` VALUES (9, 9, '070', 0);
INSERT INTO `responsability` VALUES (9, 10, '070', 0);
INSERT INTO `responsability` VALUES (10, 11, '070', 0);
INSERT INTO `responsability` VALUES (12, 12, '440', 1);
INSERT INTO `responsability` VALUES (11, 12, '070', 0);
INSERT INTO `responsability` VALUES (13, 13, '070', 0);
INSERT INTO `responsability` VALUES (15, 14, '044', 2);
INSERT INTO `responsability` VALUES (14, 14, '340', 2);
INSERT INTO `responsability` VALUES (17, 15, '007', 1);
INSERT INTO `responsability` VALUES (16, 15, '070', 0);
INSERT INTO `responsability` VALUES (18, 16, '070', 0);
INSERT INTO `responsability` VALUES (19, 17, '650', 0);
INSERT INTO `responsability` VALUES (20, 18, '061', 2);
INSERT INTO `responsability` VALUES (21, 18, '017', 2);
INSERT INTO `responsability` VALUES (22, 18, '017', 2);
INSERT INTO `responsability` VALUES (23, 18, '017', 2);
INSERT INTO `responsability` VALUES (26, 19, '070', 2);
INSERT INTO `responsability` VALUES (27, 19, '', 1);
INSERT INTO `responsability` VALUES (25, 19, '070', 1);
INSERT INTO `responsability` VALUES (24, 19, '723', 0);
INSERT INTO `responsability` VALUES (28, 42, '720', 0);
INSERT INTO `responsability` VALUES (30, 44, '370', 0);
INSERT INTO `responsability` VALUES (32, 46, '545', 2);
INSERT INTO `responsability` VALUES (31, 46, '250', 0);
INSERT INTO `responsability` VALUES (33, 48, '705', 0);
INSERT INTO `responsability` VALUES (34, 49, '180', 0);
INSERT INTO `responsability` VALUES (35, 50, '070', 0);
INSERT INTO `responsability` VALUES (36, 51, '070', 0);
INSERT INTO `responsability` VALUES (38, 53, '068', 2);
INSERT INTO `responsability` VALUES (37, 53, '070', 0);
INSERT INTO `responsability` VALUES (39, 54, '070', 0);
INSERT INTO `responsability` VALUES (40, 57, '070', 0);
INSERT INTO `responsability` VALUES (41, 57, '007', 2);
INSERT INTO `responsability` VALUES (63, 60, '070', 0);
INSERT INTO `responsability` VALUES (63, 59, '070', 0);
INSERT INTO `responsability` VALUES (62, 63, '070', 0);
INSERT INTO `responsability` VALUES (60, 65, '070', 0);
INSERT INTO `responsability` VALUES (61, 61, '070', 0);
INSERT INTO `responsability` VALUES (62, 64, '070', 0);

-- --------------------------------------------------------

-- 
-- Structure de la table `rss_content`
-- 

CREATE TABLE `rss_content` (
  `rss_id` int(10) unsigned NOT NULL default '0',
  `rss_content` longblob NOT NULL,
  `rss_last` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`rss_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `rss_content`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `rss_flux`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `rss_flux`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `rss_flux_content`
-- 

CREATE TABLE `rss_flux_content` (
  `num_rss_flux` int(9) unsigned NOT NULL default '0',
  `type_contenant` char(3) NOT NULL default 'BAN',
  `num_contenant` int(9) unsigned NOT NULL default '0',
  PRIMARY KEY  (`num_rss_flux`,`type_contenant`,`num_contenant`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `rss_flux_content`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `rubriques`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `rubriques`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `sauv_lieux`
-- 

CREATE TABLE `sauv_lieux` (
  `sauv_lieu_id` int(10) unsigned NOT NULL auto_increment,
  `sauv_lieu_nom` varchar(50) default NULL,
  `sauv_lieu_url` varchar(255) default NULL,
  `sauv_lieu_protocol` varchar(10) default 'file',
  `sauv_lieu_host` varchar(255) default NULL,
  `sauv_lieu_login` varchar(20) default NULL,
  `sauv_lieu_password` varchar(20) default NULL,
  PRIMARY KEY  (`sauv_lieu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `sauv_lieux`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `sauv_log`
-- 

CREATE TABLE `sauv_log` (
  `sauv_log_id` int(10) unsigned NOT NULL auto_increment,
  `sauv_log_start_date` date default NULL,
  `sauv_log_file` varchar(255) default NULL,
  `sauv_log_succeed` int(11) default '0',
  `sauv_log_messages` mediumtext,
  `sauv_log_userid` int(11) default NULL,
  PRIMARY KEY  (`sauv_log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `sauv_log`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `sauv_sauvegardes`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Contenu de la table `sauv_sauvegardes`
-- 

INSERT INTO `sauv_sauvegardes` VALUES (1, 'tout', 'bibli', '7', '', '1,3', 0, 'internal::', 0, '', '');

-- --------------------------------------------------------

-- 
-- Structure de la table `sauv_tables`
-- 

CREATE TABLE `sauv_tables` (
  `sauv_table_id` int(10) unsigned NOT NULL auto_increment,
  `sauv_table_nom` varchar(50) default NULL,
  `sauv_table_tables` text,
  PRIMARY KEY  (`sauv_table_id`),
  UNIQUE KEY `sauv_table_nom` (`sauv_table_nom`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- 
-- Contenu de la table `sauv_tables`
-- 

INSERT INTO `sauv_tables` VALUES (1, 'Biblio', 'analysis,bulletins,docs_codestat,docs_location,docs_section,docs_statut,docs_type,exemplaires,notices,etagere_caddie,notices_custom,notices_custom_lists,notices_custom_values');
INSERT INTO `sauv_tables` VALUES (2, 'Autorités', 'authors,categories,collections,noeuds,publishers,responsability,series,sub_collections,thesaurus,voir_aussi');
INSERT INTO `sauv_tables` VALUES (3, 'Aucune utilité', 'error_log,import_marc,old_categories,old_notices_categories,sessions');
INSERT INTO `sauv_tables` VALUES (4, 'Z3950', 'z_attr,z_bib,z_notices,z_query');
INSERT INTO `sauv_tables` VALUES (5, 'Emprunteurs', 'empr,empr_categ,empr_codestat,empr_custom,empr_custom_lists,empr_custom_values,empr_groupe,expl_custom_values,groupe,pret,pret_archive,resa');
INSERT INTO `sauv_tables` VALUES (6, 'Application', 'categories,lenders,parametres,procs,sauv_lieux,sauv_log,sauv_sauvegardes,sauv_tables,users,explnum,indexint,notices_categories,origine_notice,quotas,etagere,resa_ranger,admin_session,opac_sessions,audit,notice_statut,ouvertures');
INSERT INTO `sauv_tables` VALUES (7, 'TOUT', 'actes,admin_session,analysis,audit,authors,bannette_abon,bannette_contenu,bannette_equation,bannette_exports,bannettes,budgets,bulletins,caddie,caddie_content,caddie_procs,categories,classements,collections,comptes,coordonnees,docs_codestat,docs_location,docs_section,docs_statut,docs_type,docsloc_section,empr,empr_categ,empr_codestat,empr_custom,empr_custom_lists,empr_custom_values,empr_groupe,entites,equations,error_log,etagere,etagere_caddie,exemplaires,exercices,expl_custom,expl_custom_lists,expl_custom_values,explnum,frais,groupe,import_marc,indexint,lenders,liens_actes,lignes_actes,noeuds,notice_statut,notices,notices_categories,notices_custom,notices_custom_lists,notices_custom_values,notices_global_index,offres_remises,opac_sessions,origine_notice,ouvertures,paiements,parametres,pret,pret_archive,procs,publishers,quotas,quotas_finance,recouvrements,resa,resa_ranger,responsability,rss_content,rss_flux,rss_flux_content,rubriques,sauv_lieux,sauv_log,sauv_sauvegardes,sauv_tables,series,sessions,sub_collections,suggestions,suggestions_origine,thesaurus,transactions,tva_achats,type_abts,type_comptes,types_produits,users,voir_aussi,z_attr,z_bib,z_notices,z_query');
INSERT INTO `sauv_tables` VALUES (9, 'Caddies', 'caddie_procs,caddie,caddie_content');
INSERT INTO `sauv_tables` VALUES (10, 'DSI', 'bannette_abon,bannette_contenu,bannette_equation,bannettes,classements,equations,rss_content,rss_flux,rss_flux_content');
INSERT INTO `sauv_tables` VALUES (11, 'Finance', 'comptes,quotas_finance,recouvrements,transactions,type_abts,type_comptes');
INSERT INTO `sauv_tables` VALUES (12, '', NULL);

-- --------------------------------------------------------

-- 
-- Structure de la table `series`
-- 

CREATE TABLE `series` (
  `serie_id` mediumint(8) unsigned NOT NULL auto_increment,
  `serie_name` varchar(255) NOT NULL default '',
  `serie_index` text,
  PRIMARY KEY  (`serie_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- 
-- Contenu de la table `series`
-- 

INSERT INTO `series` VALUES (1, 'Dayak', ' dayak ');
INSERT INTO `series` VALUES (2, 'Le pithécantrope dans la valise', ' pithecantrope dans valise ');
INSERT INTO `series` VALUES (3, 'Mange-coeur', ' mange coeur ');
INSERT INTO `series` VALUES (4, 'Jojo', ' jojo ');
INSERT INTO `series` VALUES (5, 'à»?àº?à»‰àº§', '  ');

-- --------------------------------------------------------

-- 
-- Structure de la table `sessions`
-- 

CREATE TABLE `sessions` (
  `SESSID` varchar(12) NOT NULL default '',
  `login` varchar(20) NOT NULL default '',
  `IP` varchar(20) NOT NULL default '',
  `SESSstart` varchar(12) NOT NULL default '',
  `LastOn` varchar(12) NOT NULL default '',
  `SESSNAME` varchar(25) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `sessions`
-- 

INSERT INTO `sessions` VALUES ('1361292615', 'admin', '127.0.0.1', '1160021845', '1160045444', 'PhpMyBibli');
INSERT INTO `sessions` VALUES ('1375682995', 'admin', '127.0.0.1', '1160014229', '1160014229', 'PhpMyBibli');
INSERT INTO `sessions` VALUES ('1326124109', 'admin', '127.0.0.1', '1160014322', '1160015553', 'PhpMyBibli');
INSERT INTO `sessions` VALUES ('1183644898', 'admin', '127.0.0.1', '1160016307', '1160022144', 'PhpMyBibli');
INSERT INTO `sessions` VALUES ('1344355326', 'admin', '127.0.0.1', '1159971414', '1159971414', 'PhpMyBibli');
INSERT INTO `sessions` VALUES ('1290940553', 'admin', '127.0.0.1', '1159971175', '1159971204', 'PhpMyBibli');

-- --------------------------------------------------------

-- 
-- Structure de la table `sub_collections`
-- 

CREATE TABLE `sub_collections` (
  `sub_coll_id` mediumint(8) unsigned NOT NULL auto_increment,
  `sub_coll_name` varchar(255) NOT NULL default '',
  `sub_coll_parent` mediumint(9) unsigned NOT NULL default '0',
  `sub_coll_issn` varchar(12) NOT NULL default '',
  `index_sub_coll` text,
  PRIMARY KEY  (`sub_coll_id`),
  KEY `sub_coll_id` (`sub_coll_id`,`sub_coll_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `sub_collections`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `suggestions`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `suggestions`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `suggestions_origine`
-- 

CREATE TABLE `suggestions_origine` (
  `origine` varchar(100) NOT NULL default '',
  `num_suggestion` int(12) unsigned NOT NULL default '0',
  `type_origine` int(3) unsigned NOT NULL default '0',
  `date_suggestion` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`origine`,`num_suggestion`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `suggestions_origine`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `thesaurus`
-- 

CREATE TABLE `thesaurus` (
  `id_thesaurus` int(3) unsigned NOT NULL auto_increment,
  `libelle_thesaurus` varchar(255) NOT NULL default '',
  `langue_defaut` varchar(5) NOT NULL default 'fr_FR',
  `active` char(1) NOT NULL default '1',
  `opac_active` char(1) NOT NULL default '1',
  `num_noeud_racine` int(9) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_thesaurus`),
  UNIQUE KEY `libelle_thesaurus` (`libelle_thesaurus`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Contenu de la table `thesaurus`
-- 

INSERT INTO `thesaurus` VALUES (1, 'Agneaux', 'fr_FR', '1', '1', 1);

-- --------------------------------------------------------

-- 
-- Structure de la table `transactions`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `transactions`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `tva_achats`
-- 

CREATE TABLE `tva_achats` (
  `id_tva` int(8) unsigned NOT NULL auto_increment,
  `libelle` varchar(255) NOT NULL default '',
  `taux_tva` float(4,2) unsigned NOT NULL default '0.00',
  `num_cp_compta` varchar(25) NOT NULL default '0',
  PRIMARY KEY  (`id_tva`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `tva_achats`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `type_abts`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `type_abts`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `type_comptes`
-- 

CREATE TABLE `type_comptes` (
  `id_type_compte` int(8) unsigned NOT NULL auto_increment,
  `libelle` varchar(255) NOT NULL default '',
  `type_acces` int(8) unsigned NOT NULL default '0',
  `acces_id` text NOT NULL,
  PRIMARY KEY  (`id_type_compte`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `type_comptes`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `types_produits`
-- 

CREATE TABLE `types_produits` (
  `id_produit` int(8) unsigned NOT NULL auto_increment,
  `libelle` varchar(255) NOT NULL default '',
  `num_cp_compta` varchar(25) NOT NULL default '0',
  `num_tva_achat` varchar(25) NOT NULL default '0',
  PRIMARY KEY  (`id_produit`),
  KEY `libelle` (`libelle`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `types_produits`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `users`
-- 

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
  PRIMARY KEY  (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Contenu de la table `users`
-- 

INSERT INTO `users` VALUES (1, '2002-07-28', '2006-10-04', 'admin', '43e9a4ab75570f5b', 'Super User', '', 255, 'la_LA', 20, 10, 20, 0, 1, 0, 1, 1, 2, 'couleurs_onglets', 1, 12, 'fre', '070', 1, 10, 'admin', 'pmb@sigb.net', 1, 1, 1, '');
INSERT INTO `users` VALUES (2, '2004-01-21', '2005-08-10', 'circ', '3f3df3af7d72f2fb', 'Agent de prêt', '', 1, 'fr_FR', 10, 10, 20, 0, 1, 0, 1, 1, 1, 'vert_et_parme', 1, 10, 'fre', '070', 1, 26, 'circu', '', 0, 1, 1, '');
INSERT INTO `users` VALUES (3, '2004-01-21', '2005-08-10', 'cat', '7b4ed80e2270250a', 'Bibliothécaire-adjoint', '', 7, 'fr_FR', 10, 10, 20, 0, 1, 0, 1, 1, 1, 'default', 1, 10, 'fre', '070', 1, 26, 'catal', '', 0, 1, 1, '');
INSERT INTO `users` VALUES (4, '2004-01-21', '2005-08-10', 'bib', '7c99ea71225fa75a', 'Bibliothécaire', '', 23, 'fr_FR', 10, 10, 20, 0, 1, 0, 1, 1, 1, 'default', 13, 12, 'fre', '070', 7, 26, 'circu', '', 0, 1, 1, '');

-- --------------------------------------------------------

-- 
-- Structure de la table `voir_aussi`
-- 

CREATE TABLE `voir_aussi` (
  `num_noeud_orig` int(9) unsigned NOT NULL default '0',
  `num_noeud_dest` int(9) unsigned NOT NULL default '0',
  `langue` varchar(5) NOT NULL default '',
  `comment_voir_aussi` text NOT NULL,
  PRIMARY KEY  (`num_noeud_orig`,`num_noeud_dest`,`langue`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `voir_aussi`
-- 

INSERT INTO `voir_aussi` VALUES (1390, 1602, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1391, 1599, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1392, 1600, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1394, 2166, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1395, 1596, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1398, 1597, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1399, 1592, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1400, 1601, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1401, 1592, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1411, 2105, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1413, 2106, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1414, 2104, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1415, 2103, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1416, 2102, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1417, 2101, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1431, 2058, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1435, 2060, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1545, 2491, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1553, 1612, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1563, 2493, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1592, 1399, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1592, 1401, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1595, 2479, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1596, 1395, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1597, 1398, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1598, 2200, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1599, 1391, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1600, 1392, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1601, 1400, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1602, 1390, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1607, 2407, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1612, 1553, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1623, 1795, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1623, 1796, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1628, 1737, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1670, 2494, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1672, 2494, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1726, 2491, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1729, 2496, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1737, 1628, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1760, 2280, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1795, 1623, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (1796, 1623, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2057, 2112, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2058, 1431, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2060, 1435, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2101, 1417, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2102, 1416, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2103, 1415, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2104, 1414, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2105, 1411, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2106, 1413, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2112, 2057, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2166, 1394, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2184, 2485, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2184, 2486, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2200, 1598, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2280, 1760, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2407, 1607, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2467, 2510, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2479, 1595, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2485, 2184, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2486, 2184, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2490, 2495, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2491, 1545, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2491, 1726, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2491, 2496, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2491, 2499, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2491, 2500, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2492, 2491, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2493, 2490, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2493, 2495, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2494, 1670, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2494, 1672, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2494, 2490, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2495, 2493, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2496, 2491, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2496, 2497, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2497, 2496, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2499, 1689, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2499, 2491, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2499, 2496, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2500, 1689, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2500, 2491, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2500, 2492, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2502, 2492, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2504, 2503, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2507, 2509, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2508, 1764, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2509, 2507, 'fr_FR', '');
INSERT INTO `voir_aussi` VALUES (2510, 1672, 'fr_FR', '');

-- --------------------------------------------------------

-- 
-- Structure de la table `z_attr`
-- 

CREATE TABLE `z_attr` (
  `attr_bib_id` int(6) unsigned NOT NULL default '0',
  `attr_libelle` varchar(250) NOT NULL default '',
  `attr_attr` varchar(250) default NULL,
  PRIMARY KEY  (`attr_bib_id`,`attr_libelle`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `z_attr`
-- 

INSERT INTO `z_attr` VALUES (2, 'sujet', '21');
INSERT INTO `z_attr` VALUES (2, 'titre', '4');
INSERT INTO `z_attr` VALUES (2, 'auteur', '1003');
INSERT INTO `z_attr` VALUES (2, 'isbn', '7');
INSERT INTO `z_attr` VALUES (3, 'sujet', '21');
INSERT INTO `z_attr` VALUES (3, 'titre', '4');
INSERT INTO `z_attr` VALUES (3, 'isbn', '7');
INSERT INTO `z_attr` VALUES (3, 'auteur', '1003');
INSERT INTO `z_attr` VALUES (5, 'auteur', '1004');
INSERT INTO `z_attr` VALUES (5, 'titre', '4');
INSERT INTO `z_attr` VALUES (5, 'isbn', '7');
INSERT INTO `z_attr` VALUES (5, 'sujet', '21');
INSERT INTO `z_attr` VALUES (7, 'isbn', '7');
INSERT INTO `z_attr` VALUES (7, 'auteur', '1003');
INSERT INTO `z_attr` VALUES (7, 'titre', '4');
INSERT INTO `z_attr` VALUES (7, 'sujet', '21');
INSERT INTO `z_attr` VALUES (8, 'auteur', '1');
INSERT INTO `z_attr` VALUES (8, 'titre', '4');
INSERT INTO `z_attr` VALUES (8, 'isbn', '7');
INSERT INTO `z_attr` VALUES (8, 'sujet', '21');
INSERT INTO `z_attr` VALUES (8, 'mots', '1016');
INSERT INTO `z_attr` VALUES (10, 'auteur', '1003');
INSERT INTO `z_attr` VALUES (10, 'titre', '4');
INSERT INTO `z_attr` VALUES (10, 'isbn', '7');
INSERT INTO `z_attr` VALUES (10, 'sujet', '21');
INSERT INTO `z_attr` VALUES (12, 'sujet', '21');
INSERT INTO `z_attr` VALUES (12, 'auteur', '1003');
INSERT INTO `z_attr` VALUES (12, 'titre', '4');
INSERT INTO `z_attr` VALUES (12, 'isbn', '7');
INSERT INTO `z_attr` VALUES (11, 'sujet', '21');
INSERT INTO `z_attr` VALUES (11, 'auteur', '1003');
INSERT INTO `z_attr` VALUES (11, 'isbn', '7');
INSERT INTO `z_attr` VALUES (11, 'titre', '4');
INSERT INTO `z_attr` VALUES (15, 'auteur', '1003');
INSERT INTO `z_attr` VALUES (15, 'titre', '4');
INSERT INTO `z_attr` VALUES (15, 'isbn', '7');
INSERT INTO `z_attr` VALUES (15, 'sujet', '21');
INSERT INTO `z_attr` VALUES (17, 'sujet', '21');
INSERT INTO `z_attr` VALUES (17, 'auteur', '1003');
INSERT INTO `z_attr` VALUES (17, 'isbn', '7');
INSERT INTO `z_attr` VALUES (17, 'titre', '4');
INSERT INTO `z_attr` VALUES (21, 'sujet', '21');
INSERT INTO `z_attr` VALUES (21, 'auteur', '1003');
INSERT INTO `z_attr` VALUES (21, 'isbn', '7');
INSERT INTO `z_attr` VALUES (21, 'titre', '4');

-- --------------------------------------------------------

-- 
-- Structure de la table `z_bib`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

-- 
-- Contenu de la table `z_bib`
-- 

INSERT INTO `z_bib` VALUES (2, 'ENS Cachan', 'CATALOG', '138.231.48.2', '21210', 'ADVANCE', 'unimarc', '', '', '', '');
INSERT INTO `z_bib` VALUES (3, 'BN France', 'CATALOG', 'z3950.bnf.fr', '2211', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1456', 'UNIMARC', 'Z3950', 'Z3950_BNF', '', '');
INSERT INTO `z_bib` VALUES (5, 'Univ Lyon 2 SCD', 'CATALOG', 'scdinf.univ-lyon2.fr', '21210', 'ouvrages', 'unimarc', '', '', '', '');
INSERT INTO `z_bib` VALUES (7, 'Univ Oxford', 'CATALOG', 'library.ox.ac.uk', '210', 'ADVANCE', 'usmarc', '', '', '', '');
INSERT INTO `z_bib` VALUES (10, 'Univ Laval (QC)', 'CATALOG', 'ariane2.ulaval.ca', '2200', 'UNICORN', 'USMARC', '', '', '', '');
INSERT INTO `z_bib` VALUES (11, 'Univ Lib Edinburgh', 'CATALOG', 'catalogue.lib.ed.ac.uk', '7090', 'voyager', 'USMARC', '', '', '', '');
INSERT INTO `z_bib` VALUES (12, 'Library Of Congress', 'CATALOG', 'z3950.loc.gov', '7090', 'Voyager', 'USMARC', '', '', '', '');
INSERT INTO `z_bib` VALUES (15, 'ENS Paris', 'CATALOG', 'halley.ens.fr', '210', 'INNOPAC', 'UNIMARC', '', '', '', '');
INSERT INTO `z_bib` VALUES (17, 'Polytechnique Montréal', 'CATALOG', 'advance.biblio.polymtl.ca', '210', 'ADVANCE', 'USMARC', '', '', '', '');
INSERT INTO `z_bib` VALUES (21, 'SUDOC', 'CATALOG', 'carmin.sudoc.abes.fr', '210', 'ABES-Z39-PUBLIC', 'UNIMARC', '', '', '', '');
INSERT INTO `z_bib` VALUES (8, 'Univ Valenciennes', 'CATALOG', '195.221.187.151', '210', 'INNOPAC', 'UNIMARC', '', '', '', '');

-- --------------------------------------------------------

-- 
-- Structure de la table `z_notices`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `z_notices`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `z_query`
-- 

CREATE TABLE `z_query` (
  `zquery_id` int(11) unsigned NOT NULL auto_increment,
  `search_attr` varchar(255) default NULL,
  `zquery_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`zquery_id`),
  KEY `zquery_date` (`zquery_date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Contenu de la table `z_query`
-- 

