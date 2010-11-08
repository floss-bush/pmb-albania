<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: alter_v1.inc.php,v 1.11 2009-05-16 11:11:52 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

settype ($action,"string");

switch ($action) {
	case "lancement":
		switch ($version_pmb_bdd) {
			case "v1.0":
				$maj_a_faire = "v1.1";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.1":
				$maj_a_faire = "v1.2";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.2":
				$maj_a_faire = "v1.3";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.3":
				$maj_a_faire = "v1.4";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.4":
				$maj_a_faire = "v1.5";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.5":
				$maj_a_faire = "v1.6";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.6":
				$maj_a_faire = "v1.7";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.7":
				$maj_a_faire = "v1.8";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.8":
				$maj_a_faire = "v1.9";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.9":
				$maj_a_faire = "v1.10";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.10":
				$maj_a_faire = "v1.11";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.11":
				$maj_a_faire = "v1.12";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.12":
				$maj_a_faire = "v1.13";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.13":
				$maj_a_faire = "v1.20";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.20":
				$maj_a_faire = "v1.21";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.21":
				$maj_a_faire = "v1.22";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.22":
				$maj_a_faire = "v1.23";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.23":
				$maj_a_faire = "v1.24";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.24":
				$maj_a_faire = "v1.25";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.25":
				$maj_a_faire = "v1.26";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.26":
				$maj_a_faire = "v1.27";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.27":
				$maj_a_faire = "v1.28";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.28":
				$maj_a_faire = "v1.29";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.29":
				$maj_a_faire = "v1.30";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.30":
				$maj_a_faire = "v1.31";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.31":
				$maj_a_faire = "v1.40";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.40":
				$maj_a_faire = "v1.41";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.41":
				$maj_a_faire = "v1.42";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.42":
				$maj_a_faire = "v1.43";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.43":
				$maj_a_faire = "v1.44";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.44":
				$maj_a_faire = "v1.45";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.45":
				$maj_a_faire = "v1.50";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.50":
				$maj_a_faire = "v1.51";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.51":
				$maj_a_faire = "v1.52";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.52":
				$maj_a_faire = "v1.53";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.53":
				$maj_a_faire = "v1.54";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.54":
				$maj_a_faire = "v1.55";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.55":
				$maj_a_faire = "v1.56";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.56":
				$maj_a_faire = "v1.57";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.57":
				$maj_a_faire = "v1.58";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v1.58":
				$maj_a_faire = "v2.00";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			default:
				echo "<strong><font color='#FF0000'>".$msg[1806].$version_pmb_bdd." !</font></strong><br />";
				break;
			}
		break;	

	case "v1.1":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		/* ajout du propriétaire de l'exemplaire */
		$rqt = "ALTER TABLE exemplaires ADD expl_owner MEDIUMINT( 8 ) UNSIGNED DEFAULT '0' NOT NULL " ;
		echo traite_rqt($rqt,"expl_owner");
		$rqt = "alter table exemplaires drop index expl_owner ";
		echo traite_rqt($rqt,"expl_owner DEL INDEX");
		$rqt = "ALTER TABLE exemplaires ADD INDEX expl_owner ( expl_owner )  " ;
		echo traite_rqt($rqt,"expl_owner INDEX");
		$rqt = "ALTER TABLE empr ADD empr_login VARCHAR( 15 ) DEFAULT '' NOT NULL  " ;
		echo traite_rqt($rqt,"empr_login");
		$rqt = "ALTER TABLE empr ADD empr_password VARCHAR( 10 )  DEFAULT '' NOT NULL  " ;
		echo traite_rqt($rqt,"empr_password");
		$rqt = "ALTER TABLE docs_type ADD tdoc_owner MEDIUMINT( 8 ) UNSIGNED DEFAULT '0' NOT NULL after duree_pret " ;
		echo traite_rqt($rqt,"tdoc_owner");
		$rqt = "ALTER TABLE docs_type ADD tdoc_codage_import CHAR( 2 ) DEFAULT '' NOT NULL after tdoc_owner" ;
		echo traite_rqt($rqt,"tdoc_codage_import");
		
		// +-------------------------------------------------+
		// $Id: alter_v1.inc.php,v 1.11 2009-05-16 11:11:52 dbellamy Exp $
		$rqt = "ALTER TABLE exemplaires ADD expl_id MEDIUMINT( 8 ) UNSIGNED AUTO_INCREMENT PRIMARY KEY FIRST " ;
		echo traite_rqt($rqt,"expl_id");
		$rqt = "alter table exemplaires drop index expl_id ";
		echo traite_rqt($rqt,"expl_id DEL INDEX");
		$rqt = "ALTER TABLE exemplaires ADD INDEX ( expl_id )  " ;
		echo traite_rqt($rqt,"expl_id INDEX");
		
		// +-------------------------------------------------+
		// ajout de la langue du user
		$rqt = "ALTER TABLE users ADD user_lang VARCHAR( 5 ) DEFAULT 'fr_FR' NOT NULL  " ;
		echo traite_rqt($rqt,"user_lang");
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.2");
		break;	

	case "v1.2":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		// Création du FULLTEXT sur notices pour utilisation des match against :
		$rqt = "alter table notices add (FULLTEXT (tit1,tit2,tit3,tit4,n_contenu)) " ;
		echo traite_rqt($rqt,"FULLTEXT (tit1,tit2,tit3,tit4,n_contenu)");
		
		// ajout du propriétaire et du code correspondant de section de document
		$rqt = "ALTER TABLE docs_section ADD sdoc_codage_import CHAR( 2 ) default '' NOT NULL , ADD sdoc_owner MEDIUMINT( 8 ) UNSIGNED DEFAULT '0' NOT NULL  " ;
		echo traite_rqt($rqt,"sdoc_codage_import, sdoc_owner");
		$rqt = "alter table docs_section drop index sdoc_owner ";
		echo traite_rqt($rqt,"sdoc_owner DEL INDEX");
		$rqt = "ALTER TABLE docs_section ADD INDEX sdoc_owner ( sdoc_owner )  " ;
		echo traite_rqt($rqt,"sdoc_owner INDEX");
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.3");
		break;	

	case "v1.3":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		// ajout du propriétaire et du code correspondant de statistique de document
		$rqt = "ALTER TABLE docs_codestat ADD statisdoc_codage_import CHAR( 2 ) default '' NOT NULL , ADD statisdoc_owner MEDIUMINT( 8 ) UNSIGNED DEFAULT '0' NOT NULL  " ;
		echo traite_rqt($rqt,"statisdoc_codage_import, statisdoc_owner");
		
		$rqt = "alter table docs_codestat drop index statisdoc_owner ";
		echo traite_rqt($rqt,"statisdoc_owner DEL INDEX");
		$rqt = "ALTER TABLE docs_codestat ADD INDEX statisdoc_owner ( statisdoc_owner )  " ;
		echo traite_rqt($rqt,"statisdoc_owner INDEX");
		
		// ajout du propriétaire et du code correspondant aux localisations de documents
		$rqt = "ALTER TABLE docs_location ADD locdoc_codage_import CHAR( 2 ) default '' NOT NULL , ADD locdoc_owner MEDIUMINT( 8 ) UNSIGNED DEFAULT '0' NOT NULL  " ;
		echo traite_rqt($rqt,"locdoc_codage_import, locdoc_owner");
		
		$rqt = "alter table docs_location drop index locdoc_owner ";
		echo traite_rqt($rqt,"locdoc_owner DEL INDEX");
		$rqt = "ALTER TABLE docs_location ADD INDEX ( locdoc_owner )  " ;
		echo traite_rqt($rqt,"locdoc_owner INDEX");
		
		// ajout du propriétaire et du code correspondant de statut de document
		$rqt = "ALTER TABLE docs_statut ADD statusdoc_codage_import CHAR( 2 ) default '' NOT NULL , ADD statusdoc_owner MEDIUMINT( 8 ) UNSIGNED DEFAULT '0' NOT NULL  " ;
		echo traite_rqt($rqt,"statusdoc_codage_import, statusdoc_owner");
		
		$rqt = "ALTER TABLE exemplaires DROP INDEX statusdoc_owner  " ;
		echo traite_rqt($rqt,"statusdoc_owner DROP INDEX");
		$rqt = "ALTER TABLE docs_statut ADD INDEX statusdoc_owner ( statusdoc_owner )  " ;
		echo traite_rqt($rqt,"statusdoc_owner INDEX");
		
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE exemplaires DROP INDEX expl_id  " ;
		echo traite_rqt($rqt,"expl_id DROP INDEX");
		$rqt = "ALTER TABLE exemplaires ADD UNIQUE expl_id (expl_id)  " ;
		echo traite_rqt($rqt,"expl_id UNIQUE");
		
		$rqt = "ALTER TABLE exemplaires DROP INDEX expl_cb  " ;
		echo traite_rqt($rqt,"expl_cb DROP INDEX");
		$rqt = "ALTER TABLE exemplaires ADD UNIQUE expl_cb (expl_cb)  " ;
		echo traite_rqt($rqt,"expl_cb UNIQUE");
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.4");
		break;	

	case "v1.4":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		// suppression du champ 'index_titre'
		$rqt = "ALTER TABLE notices DROP index_titre " ;
		echo traite_rqt($rqt,"index_titre DROP");
		
		// ajout du champ 'index_serie'
		$rqt = "ALTER TABLE notices ADD index_serie TINYTEXT  " ;
		echo traite_rqt($rqt,"index_serie");
		
		// ajout des champs 'index_tit1', 'index_tit2', 'index_tit3', 'index_tit4'
		$rqt = "ALTER TABLE notices ADD index_tit1 TINYTEXT " ;
		echo traite_rqt($rqt,"index_tit1");
		$rqt = "ALTER TABLE notices ADD index_tit2 TINYTEXT " ;
		echo traite_rqt($rqt,"index_tit2");
		$rqt = "ALTER TABLE notices ADD index_tit3 TINYTEXT " ;
		echo traite_rqt($rqt,"index_tit3");
		$rqt = "ALTER TABLE notices ADD index_tit4 TINYTEXT " ;
		echo traite_rqt($rqt,"index_tit4");
		
		// suppression des index sur 'tit1', 'tit2', 'tit3', 'tit4'
		$rqt = "ALTER TABLE notices DROP INDEX tit1   " ;
		echo traite_rqt($rqt,"tit1 DROP INDEX");
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.5");
		break;	

	case "v1.5":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// Création du FULLTEXT sur notices pour utilisation des match against :
		$rqt = "ALTER TABLE notices DROP INDEX index_serie " ;
		echo traite_rqt($rqt,"index_serie DROP INDEX");
		$rqt = "alter table notices add (FULLTEXT index_serie (index_serie,index_tit1,index_tit2,index_tit3,index_tit4)) " ;
		echo traite_rqt($rqt,"FULLTEXT (index_serie,index_tit1,index_tit2,index_tit3,index_tit4)");
		
		// ajout du pseudo index des matières libres
		$rqt = "ALTER TABLE notices ADD index_matieres TINYTEXT  " ;
		echo traite_rqt($rqt,"index_matieres");

		// création du FULLTEXT sur les matières libres
		$rqt = "ALTER TABLE notices DROP INDEX index_matieres " ;
		echo traite_rqt($rqt,"index_matieres DROP INDEX");
		$rqt = "alter table notices add (FULLTEXT index_matieres (index_matieres)) " ;
		echo traite_rqt($rqt,"FULLTEXT (index_matieres)");
		
		// Structure de la table error_log
		$rqt = "CREATE TABLE if not exists error_log (
		  error_date timestamp(14) NOT NULL,
		  error_origin varchar(255) default NULL,
		  error_text text default NULL
		)  " ;
		echo traite_rqt($rqt,"error_log CREATE");
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.6");
		break;	

	case "v1.6":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		// rétablissement du champ index_l
		$rqt = "ALTER TABLE notices MODIFY index_l TINYTEXT " ;
		echo traite_rqt($rqt,"index_l MODIFY");
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.7");
		break;	

	case "v1.7":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		// modification taille year et tnvol et nocoll
		$rqt = "ALTER TABLE notices modify tnvol VARCHAR( 16 ) NULL default '' " ;
		echo traite_rqt($rqt,"tnvol MODIFY");
		$rqt = "ALTER TABLE notices modify year VARCHAR( 16 ) NULL default '' " ;
		echo traite_rqt($rqt,"year MODIFY");
		$rqt = "ALTER TABLE notices modify nocoll VARCHAR( 16 ) NULL default '' " ;
		echo traite_rqt($rqt,"nocoll MODIFY");
		// #
		// Structure de la table pret
		// #
		$rqt = "create table if not exists pret(pret_idempr smallint(6) unsigned not null default '0',
		     pret_idexpl smallint(6) unsigned not null default '0',
		     pret_date date not null default '0000-00-00',
		     pret_retour date default NULL,
		     primary key (pret_idempr,
		     pret_idexpl,
		     pret_date)) type=MyISAM " ;
		echo traite_rqt($rqt,"pret CREATE");
		#
		// Structure de la table pret_archive
		#
		$rqt = "create table if not exists pret_archive(arc_id int(9) unsigned default NULL auto_increment,
		     arc_debut date default '0000-00-00',
		     arc_fin date default '0000-00-00',
		     arc_empr_cp varchar(5) default '                                                                                               ',
		     arc_empr_ville varchar(40) default '                                                                                          ',
		     arc_empr_prof varchar(50) default '                                                  ',
		     arc_empr_year int(4) unsigned default '0',
		     arc_empr_categ smallint(5) unsigned default '0',
		     arc_empr_codestat smallint(5) unsigned default '0',
		     arc_empr_sexe tinyint(3) unsigned default '0',
		     arc_expl_typdoc tinyint(3) unsigned default '0',
		     arc_expl_cote varchar(20) default '',
		     arc_expl_statut smallint(5) unsigned default '0',
		     arc_expl_location smallint(5) unsigned default '0',
		     arc_expl_codestat smallint(5) unsigned default '0',
		     arc_expl_owner mediumint(8) unsigned default '0',
		     primary key (arc_id)) type=MyISAM " ;
		echo traite_rqt($rqt,"pret_archive CREATE");
		
		// +-------------------------------------------------+
		$rqt = "create table if not exists resa (
		     id_resa mediumint(8) unsigned not null auto_increment,
		     resa_idempr mediumint(8) unsigned not null default '0',
		     resa_idnotice mediumint(8) unsigned not null default '0',
		     resa_date date not null default '0000-00-00',
		     primary key (id_resa)) type=MyISAM " ;
		echo traite_rqt($rqt,"resa CREATE");
		
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE resa CHANGE resa_date resa_date TIMESTAMP NOT NULL  " ;
		echo traite_rqt($rqt,"resa_date CHANGE");
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.8");
		break;	

	case "v1.8":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "ALTER TABLE exemplaires CHANGE expl_cb expl_cb VARCHAR( 14 ) NOT NULL  " ;
		echo traite_rqt($rqt,"expl_cb CHANGE");
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.9");
		break;	

	case "v1.9":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "ALTER TABLE notices DROP INDEX i_contenu_resume " ;
		echo traite_rqt($rqt,"i_contenu_resume DROP INDEX");
		$rqt = "ALTER TABLE notices ADD FULLTEXT KEY i_contenu_resume( n_resume, n_contenu )  " ;
		echo traite_rqt($rqt,"FULLTEXT KEY i_contenu_resume( n_resume, n_contenu )");
		
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE users 
			ADD nb_per_page_search INT UNSIGNED DEFAULT '4' NOT NULL ,
			ADD nb_per_page_select INT UNSIGNED DEFAULT '10' NOT NULL ,
			ADD nb_per_page_gestion INT UNSIGNED DEFAULT '20' NOT NULL 
			 " ;
		echo traite_rqt($rqt,"nb_per_page_search, nb_per_page_select, nb_per_page_gestion ADD");
		
		// +-------------------------------------------------+
		$rqt = "UPDATE users SET rights = '31' WHERE userid = '1'  " ;
		echo traite_rqt($rqt,"rights 31 for user 1");
		
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE empr_categ ADD duree_adhesion INT UNSIGNED DEFAULT '365' " ;
		echo traite_rqt($rqt,"duree_adhesion ADD in empr_categ");
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.10");
		break;	

	case "v1.10":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		$rqt = "ALTER TABLE empr ADD empr_date_adhesion DATE, ADD empr_date_expiration DATE " ;
		echo traite_rqt($rqt,"empr_date_adhesion ADD");
		
		// +-------------------------------------------------+
		$rqt = "update empr set empr_date_adhesion=CURRENT_DATE() where empr_date_adhesion is null ";
		echo traite_rqt($rqt,"empr_date_adhesion=CURRENT_DATE() where NULL");
		$rqt = "update empr set empr_date_expiration=CURRENT_DATE() where empr_date_expiration is null " ;
		echo traite_rqt($rqt,"empr_date_expiration=CURRENT_DATE() where NULL");
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.11");
		break;	

	case "v1.11":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "ALTER TABLE notices DROP INDEX empr_cb " ;
		echo traite_rqt($rqt,"empr_cb DROP INDEX");
		$rqt = "ALTER TABLE empr ADD UNIQUE empr_cb (empr_cb)  " ;
		echo traite_rqt($rqt,"empr_cb UNIQUE");
		
		// +-------------------------------------------------+
		$rqt = "CREATE TABLE if not exists lenders (
		  idlender smallint(5) unsigned NOT NULL auto_increment,
		  lender_libelle varchar(30) NOT NULL default '',
		  PRIMARY KEY  (idlender),
		  KEY idcode (idlender)
		) TYPE=MyISAM " ;
		echo traite_rqt($rqt,"lenders CREATE");
		if (mysql_result(mysql_query("select count(1) from lenders where idlender= 0"), 0, 0)==0){
			$rqt = "INSERT INTO lenders VALUES (999, 'Fonds propre') " ;
			echo traite_rqt($rqt,"lender 999");
			$rqt = "update lenders set idlender = 0 where idlender=999 " ;
			echo traite_rqt($rqt,"lender 999 > 0");
			}
		if (mysql_result(mysql_query("select count(1) from lenders where idlender= 1"), 0, 0)==0){
			$rqt = "INSERT INTO lenders VALUES (1, 'Prêteur N°1') " ;
			echo traite_rqt($rqt,"lender 2");
			}
		
		// +-------------------------------------------------+
		$rqt = "CREATE TABLE if not exists groupe (
			id_groupe INT( 6 ) UNSIGNED NOT NULL AUTO_INCREMENT,
			libelle_groupe VARCHAR( 50 ) NOT NULL ,
			resp_groupe INT( 6 ) UNSIGNED DEFAULT '0',
			PRIMARY KEY ( id_groupe ) ,
			UNIQUE ( libelle_groupe ) 
			) " ;
		echo traite_rqt($rqt,"groupe CREATE");
		$rqt = "CREATE TABLE if not exists empr_groupe (
			empr_id INT( 6 ) UNSIGNED NOT NULL ,
			groupe_id INT( 6 ) UNSIGNED NOT NULL ,
			PRIMARY KEY ( empr_id , groupe_id ) ) " ;
		echo traite_rqt($rqt,"empr_groupe CREATE");
		
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE users ADD param_popup_ticket SMALLINT( 1 ) UNSIGNED DEFAULT '0' NOT NULL  " ;
		echo traite_rqt($rqt,"param_popup_ticket ADD in users");
		
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE users ADD deflt_docs_type INT( 6 ) UNSIGNED DEFAULT '1' NOT NULL  " ;
		echo traite_rqt($rqt,"deflt_docs_type ADD in users");
		
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE users ADD deflt_lenders INT( 6 ) UNSIGNED DEFAULT '0' NOT NULL   " ;
		echo traite_rqt($rqt,"deflt_lenders ADD in users");
		
		// +-------------------------------------------------+
		$rqt = "CREATE TABLE if not exists parametres ( 
		id_param INT( 6 ) UNSIGNED NOT NULL AUTO_INCREMENT,
		type_param VARCHAR( 20 ) ,
		sstype_param VARCHAR( 20 ) ,
		valeur_param VARCHAR( 255 ) ,
		PRIMARY KEY ( id_param ) ,
		INDEX ( type_param , sstype_param ) 
		) " ;
		echo traite_rqt($rqt,"parametres CREATE");
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'biblio' and sstype_param='name' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param) VALUES (0, 'biblio', 'name', 'Bibliothèque Intercommunale de Bueil/Villebourg') " ;
			echo traite_rqt($rqt,"insert biblio,name into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'biblio' and sstype_param='adr1' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param) VALUES (0, 'biblio', 'adr1', '9, rue de la Mairie') " ;
			echo traite_rqt($rqt,"insert biblio,adr1 into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'biblio' and sstype_param='cp' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param) VALUES (0, 'biblio', 'cp', '37370') " ;
			echo traite_rqt($rqt,"insert biblio,cp into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'biblio' and sstype_param='town' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param) VALUES (0, 'biblio', 'town', 'BUEIL EN TOURAINE') " ;
			echo traite_rqt($rqt,"insert biblio,town into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'biblio' and sstype_param='phone' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param) VALUES (0, 'biblio', 'phone', '02 47 24 47 54') " ;
			echo traite_rqt($rqt,"insert biblio,phone into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'biblio' and sstype_param='email' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param) VALUES (0, 'biblio', 'email', 'mybibli@wanadoo.fr') " ;
			echo traite_rqt($rqt,"insert biblio,email into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'biblio' and sstype_param='logo' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param) VALUES (0, 'biblio', 'logo', 'logo_bueil.jpg') " ;
			echo traite_rqt($rqt,"insert biblio,logo into parametres");
			}
			
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE users ADD deflt_styles INT( 6 ) UNSIGNED DEFAULT '1' ";
		echo traite_rqt($rqt,"deflt_styles ADD in users");
		$rqt = "ALTER TABLE users CHANGE deflt_styles deflt_styles VARCHAR( 20 ) DEFAULT 'seabreeze.css' NOT NULL ";
		echo traite_rqt($rqt,"deflt_styles ADD in users");
		$rqt = "update users set deflt_styles='seabreeze.css' where deflt_styles in ('0','1','2','3','4') ";
		echo traite_rqt($rqt,"deflt_styles=seabreeze.css in users");
		$rqt = "drop table if exists styles ";
		echo traite_rqt($rqt,"styles DROP");
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.12");
		break;	

	case "v1.12":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'biblio' and sstype_param='logosmall' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param) VALUES (0, 'biblio', 'logosmall', 'logo_small.gif') " ;
			echo traite_rqt($rqt,"insert biblio,logosmall into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'biblio' and sstype_param='logoaccueil' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param) VALUES (0, 'biblio', 'logoaccueil', 'logo_acceuil.gif') " ;
			echo traite_rqt($rqt,"insert biblio,logoaccueil into parametres");
			}
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.13");
		break;

	case "v1.13":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'biblio' and sstype_param='preamble_p1' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param) VALUES (0, 'biblio', 'preamble_p1', 'La bibliothèque de Bueil/Villebourg vous propose plus de 1800 ouvrages pour tous publics.') " ;
			echo traite_rqt($rqt,"insert biblio,preamble_p1 into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'biblio' and sstype_param='preamble_p2' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param) VALUES (0, 'biblio', 'preamble_p2', 'La bibliothèque est ouverte les mercredi, vendredi et samedi.') " ;
			echo traite_rqt($rqt,"insert biblio,preamble_p2 into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'biblio' and sstype_param='website' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param) VALUES (0, 'biblio', 'website', 'http://www.sigb.net/') " ;
			echo traite_rqt($rqt,"insert biblio,preamble_p2 into parametres");
			}
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.20");
		break;

	case "v1.20":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		// -- Modification de la table notices
		$rqt = "alter table notices add niveau_biblio char(1) default 'm' not null ";
		echo traite_rqt($rqt,"alter notices add niveau_biblio ");
		$rqt = "alter table notices add niveau_hierar char(1) default '0' not null ";
		echo traite_rqt($rqt,"alter notices add niveau_hierar ");
		
		// -- --------------------------------------------
		// -- Création de la table bulletins
		$rqt = "create table if not exists bulletins (
			bulletin_id int(8) unsigned not null auto_increment,
			bulletin_numero varchar(20) not null default '',
			bulletin_notice int(8) not null default '0',
			mention_date varchar (50) not null default '',
			date_date date not null default '0000-00-00',
			PRIMARY KEY  (bulletin_id),
			key (bulletin_numero),
			key (bulletin_notice),
			KEY date_date (date_date)
			) TYPE=MyISAM ";
		echo traite_rqt($rqt,"Création table bulletins ");
		
		// -- --------------------------------------------
		// -- Création de la table dépouillements
		$rqt = "create table if not exists analysis (
			analysis_bulletin int(8) unsigned not null default '0',
			analysis_notice int(8) unsigned not null default '0',
			PRIMARY KEY  (analysis_bulletin, analysis_notice)
			) TYPE=MyISAM ";
		echo traite_rqt($rqt,"création table analysis ");
		
		// -- --------------------------------------------
		// -- Modification de la table expl
		$rqt = "alter table exemplaires add expl_bulletin int(8) unsigned default '0' not null after expl_notice ";
		echo traite_rqt($rqt,"alter expl add expl_bulletin ");
		$rqt = "ALTER TABLE exemplaires DROP INDEX expl_bulletin " ;
		echo traite_rqt($rqt,"expl_bulletin DROP INDEX");
		$rqt = "alter table exemplaires add index expl_bulletin (expl_bulletin) ";
		echo traite_rqt($rqt,"alter expl index expl_bulletin ");
		
		$rqt = "ALTER TABLE empr DROP INDEX empr_nom " ;
		echo traite_rqt($rqt,"empr_nom DROP INDEX");
		$rqt = "ALTER TABLE empr ADD INDEX empr_nom (empr_nom) ";
		echo traite_rqt($rqt,"empr_nom ADD INDEX");
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.21");
		break;
	
	case "v1.21":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		// -- Création des tables z_
		$rqt = "drop table if exists z_attr ";
		echo traite_rqt($rqt,"z_attr DROP");
		$rqt = "drop table if exists z_bib ";
		echo traite_rqt($rqt,"z_bib DROP");
		$rqt = "drop table if exists z_notices ";
		echo traite_rqt($rqt,"z_notices DROP");
		$rqt = "drop table if exists z_query ";
		echo traite_rqt($rqt,"z_query DROP");
		$rqt = "CREATE TABLE if not exists z_attr (
			attr_bib_id int(6) unsigned NOT NULL default '0',
			attr_libelle varchar(250) NOT NULL default '',
			attr_attr varchar(250) default NULL,
			PRIMARY KEY  (attr_bib_id,attr_libelle)
			) TYPE=MyISAM ";
		echo traite_rqt($rqt,"z_attr CREATE");
		$rqt = "CREATE TABLE if not exists z_bib (
			bib_id int(6) unsigned NOT NULL auto_increment,
			bib_nom varchar(250) default NULL,
			search_type varchar(20) default NULL,
			url varchar(250) default NULL,
			port varchar(6) default NULL,
			base varchar(250) default NULL,
			format varchar(250) default NULL,
			auth_user varchar(250) NOT NULL default '',
			auth_pass varchar(250) NOT NULL default '',
			PRIMARY KEY  (bib_id)
			) TYPE=MyISAM ";
		echo traite_rqt($rqt,"z_bib CREATE");
		$rqt = "CREATE TABLE if not exists z_notices (
			znotices_id int(11) unsigned NOT NULL auto_increment,
			znotices_query_id int(11) default NULL,
			znotices_bib_id int(6) unsigned default '0',
			isbd text,
			isbn varchar(250) default NULL,
			titre varchar(250) default NULL,
			auteur varchar(250) default NULL,
			z_marc longblob NOT NULL,
			PRIMARY KEY  (znotices_id),
			KEY idx_z_notices_idq (znotices_query_id),
			KEY idx_z_notices_isbn (isbn),
			KEY idx_z_notices_titre (titre),
			KEY idx_z_notices_auteur (auteur)
			) TYPE=MyISAM ";
		echo traite_rqt($rqt,"z_notices CREATE");
		$rqt = "CREATE TABLE if not exists z_query (
			zquery_id int(11) unsigned NOT NULL auto_increment,
			search_attr varchar(255) default NULL,
			PRIMARY KEY  (zquery_id)
			) TYPE=MyISAM ";
		echo traite_rqt($rqt,"z_query CREATE");
		
		// +-------------------------------------------------+
		// -- Remplissage des tables z_
		$rqt = "INSERT INTO z_attr VALUES (1, 'sujet', '21'),
			(1, 'titre', '4'),
			(1, 'auteur', '1003'),
			(2, 'sujet', '21'),
			(2, 'titre', '4'),
			(2, 'auteur', '1003'),
			(4, 'sujet', '21'),
			(4, 'titre', '4'),
			(4, 'auteur', '1003'),
			(2, 'isbn', '7'),
			(4, 'isbn', '7'),
			(1, 'isbn', '7'),
			(3, 'sujet', '21'),
			(3, 'titre', '4'),
			(3, 'isbn', '7'),
			(3, 'auteur', '1003'),
			(5, 'auteur', '1004'),
			(5, 'titre', '4'),
			(5, 'isbn', '7'),
			(5, 'sujet', '21'),
			(6, 'auteur', '1003'),
			(6, 'titre', '4'),
			(6, 'sujet', '21'),
			(6, 'isbn', '7'),
			(7, 'isbn', '7'),
			(7, 'auteur', '1003'),
			(7, 'titre', '4'),
			(7, 'sujet', '21'),
			(8, 'auteur', '1003'),
			(8, 'titre', '4'),
			(8, 'isbn', '7'),
			(8, 'sujet', '21'),
			(8, 'mots', '1016'),
			(8, 'resume', '62'),
			(8, 'type_doc', '1031') ";
		echo traite_rqt($rqt,"z_attr FEED");
		$rqt = "INSERT INTO z_bib VALUES (1, 'reims', 'CATALOG', 'scd.univ-reims.fr', '8002', 'scdreims', 'usmarc', '', ''),
			(2, 'cachan', 'CATALOG', '138.231.48.2', '21210', 'ADVANCE', 'unimarc', '', ''),
			(4, 'lyon_3', 'CATALOG', '193.52.199.5', '21210', 'ADVANCE', 'unimarc', '', ''),
			(3, 'bnf', 'CATALOG', 'z3950.bnf.fr', '2211', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1456', 'unimarc', 'Z3950', 'Z3950_BNF'),
			(5, 'lyon_2', 'CATALOG', 'scdinf.univ-lyon2.fr', '21210', 'ouvrages', 'unimarc', '', ''),
			(6, 'grenoble_bm', 'CATALOG', 'www.bm-grenoble.fr', '2100', 'Z3950S', 'unimarc', '', ''),
			(7, 'val_d_oise', 'CATALOG', '194.167.203.72', '210', 'CDA', 'usmarc', 'Anonymous', ''),
			(8, 'u_valenciennes', 'CATALOG', '193.50.192.20', '210', 'BU', 'usmarc', '', '') ";
		echo traite_rqt($rqt,"z_bib FEED");
		
		// +-------------------------------------------------+
		// -- Paramêtre z3950_accessible
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'z3950' and sstype_param='accessible' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param) VALUES (0, 'z3950', 'accessible', '1') " ;
			echo traite_rqt($rqt,"insert z3950,accessible,1 into parametres");
			}
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.22");
		break;
		
	case "v1.22":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'biblio' and sstype_param='country' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param) VALUES (0, 'biblio', 'country', 'France')";
			echo traite_rqt($rqt,"insert biblio,country into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'biblio' and sstype_param='departement' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param) VALUES (0, 'biblio', 'departement', '37')";
			echo traite_rqt($rqt,"insert biblio,departement into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'biblio' and sstype_param='state' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param) VALUES (0, 'biblio', 'state', '')";
			echo traite_rqt($rqt,"insert biblio,state into parametres");
			}
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.23");
		break;
		
	case "v1.23":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "ALTER TABLE users CHANGE deflt_styles deflt_styles VARCHAR( 20 ) DEFAULT 'default' NOT NULL ";
		echo traite_rqt($rqt,"users.deflt_styles 'default'");
		$rqt = "ALTER TABLE empr CHANGE id_empr id_empr SMALLINT( 6 ) NOT NULL ";
		echo traite_rqt($rqt,"EMPR : id !auto_increment");
		$rqt = "ALTER TABLE empr DROP PRIMARY KEY , ADD PRIMARY KEY ( id_empr ) ";
		echo traite_rqt($rqt,"EMPR : id,cb !primaray key - id primary key");
		$rqt = "ALTER TABLE empr CHANGE id_empr id_empr SMALLINT( 6 ) DEFAULT '0' NOT NULL AUTO_INCREMENT ";
		echo traite_rqt($rqt,"EMPR : id auto_increment");
		$rqt = "ALTER TABLE empr DROP INDEX empr_cb ";
		echo traite_rqt($rqt,"EMPR : drop index empr_cb");
		$rqt = "ALTER TABLE empr ADD UNIQUE (empr_cb) ";
		echo traite_rqt($rqt,"EMPR : cb unique");

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='nb_lastautorities' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param) VALUES (0, 'pmb', 'nb_lastautorities', '20')";
			echo traite_rqt($rqt,"insert pmb,nb_lastautorities into parametres");
			}
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.24");
		break;
		
	case "v1.24":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "ALTER TABLE z_query ADD zquery_date TIMESTAMP NOT NULL ";
		echo traite_rqt($rqt,"Z_QUERY : add zquery_date timestamp");
		$rqt = "ALTER TABLE z_query DROP INDEX zquery_date ";
		echo traite_rqt($rqt,"Z_QUERY : drop index zquery_date");
		$rqt = "ALTER TABLE z_query ADD INDEX (zquery_date) ";
		echo traite_rqt($rqt,"Z_QUERY : index (zquery_date)");
			
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.25");
		break;
	
	case "v1.25":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='1before_list' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('pdflettreretard', '1before_list', 'Sauf erreur de notre part, vous avez toujours en votre possession le ou les ouvrage(s) suivant(s) dont la durée de prêt est aujourd\'hui dépassée :') " ;
			echo traite_rqt($rqt,"insert pdflettreretard,1before_list into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='1after_list' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('pdflettreretard', '1after_list', 'Nous vous remercions de prendre rapidement contact par téléphone au $biblio_phone ou par mail à $biblio_email pour étudier la possibilité de prolonger ces prêts ou de ramener les ouvrages concernés.') " ;
			echo traite_rqt($rqt,"insert pdflettreretard,1after_list into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='1fdp' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('pdflettreretard', '1fdp', 'Le responsable de la $biblio_name.') " ;
			echo traite_rqt($rqt,"insert pdflettreretard,1fdp into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='1madame_monsieur' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('pdflettreretard', '1madame_monsieur', 'Madame, Monsieur,') " ;
			echo traite_rqt($rqt,"insert pdflettreretard,1madame_monsieur into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='1nb_par_page' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('pdflettreretard', '1nb_par_page', '7') " ;
			echo traite_rqt($rqt,"insert pdflettreretard,1nb_par_page into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='1nb_1ere_page' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('pdflettreretard', '1nb_1ere_page', '5') " ;
			echo traite_rqt($rqt,"insert pdflettreretard,1nb_1ere_page into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='1taille_bloc_expl' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('pdflettreretard', '1taille_bloc_expl', '20') " ;
			echo traite_rqt($rqt,"insert pdflettreretard,1taille_bloc_expl into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='1debut_expl_1er_page' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('pdflettreretard', '1debut_expl_1er_page', '160') " ;
			echo traite_rqt($rqt,"insert pdflettreretard,1debut_expl_1er_page into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='1debut_expl_page' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('pdflettreretard', '1debut_expl_page', '15') " ;
			echo traite_rqt($rqt,"insert pdflettreretard,1debut_expl_page into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='1limite_after_list' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('pdflettreretard', '1limite_after_list', '250') " ;
			echo traite_rqt($rqt,"insert pdflettreretard,1limite_after_list into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='1marge_page_gauche' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('pdflettreretard', '1marge_page_gauche', '10') " ;
			echo traite_rqt($rqt,"insert pdflettreretard,1marge_page_gauche into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='1marge_page_droite' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('pdflettreretard', '1marge_page_droite', '10') " ;
			echo traite_rqt($rqt,"insert pdflettreretard,1marge_page_droite into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='1largeur_page' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('pdflettreretard', '1largeur_page', '210') " ;
			echo traite_rqt($rqt,"insert pdflettreretard,1largeur_page into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='1hauteur_page' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('pdflettreretard', '1hauteur_page', '297') " ;
			echo traite_rqt($rqt,"insert pdflettreretard,1hauteur_page into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='1format_page' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('pdflettreretard', '1format_page', 'P') " ;
			echo traite_rqt($rqt,"insert pdflettreretard,1format_page into parametres");
			}

		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.26");
		break;

	case "v1.26":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "ALTER TABLE procs ADD autorisations MEDIUMTEXT " ;
		echo traite_rqt($rqt,"procs add autorisations ");
		$rqt = "update procs set autorisations='1' where autorisations is null or autorisations='' " ;
		echo traite_rqt($rqt,"update procs.autorisations where NULL");
		$rqt = "CREATE TABLE if not exists sauv_lieux (
			sauv_lieu_id int(10) unsigned NOT NULL auto_increment,
			sauv_lieu_nom varchar(50) default NULL,
			sauv_lieu_url varchar(255) default NULL,
			sauv_lieu_protocol varchar(10) default 'file',
			sauv_lieu_host varchar(255) default NULL,
			sauv_lieu_login varchar(20) default NULL,
			sauv_lieu_password varchar(20) default NULL,
			PRIMARY KEY  (sauv_lieu_id)
			) ";
		echo traite_rqt($rqt,"create table sauv_lieux");
			
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.27");
		break;

	case "v1.27":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "ALTER TABLE parametres DROP INDEX typ_sstyp ";
		echo traite_rqt($rqt,"parametres drop index typ_sstyp");
		$rqt = "ALTER TABLE parametres ADD UNIQUE typ_sstyp (type_param, sstype_param) ";
		echo traite_rqt($rqt,"parametres UNIQUE (type_param, sstype_param)");
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfcartelecteur' and sstype_param='pos_h' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('pdfcartelecteur', 'pos_h', '20') " ;
			echo traite_rqt($rqt,"insert pdfcartelecteur,pos_h into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfcartelecteur' and sstype_param='pos_v' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('pdfcartelecteur', 'pos_v', '20') " ;
			echo traite_rqt($rqt,"insert pdfcartelecteur,pos_v into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfcartelecteur' and sstype_param='biblio_name' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('pdfcartelecteur', 'biblio_name', '\$biblio_name') " ;
			echo traite_rqt($rqt,"insert pdfcartelecteur,biblio_name into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfcartelecteur' and sstype_param='largeur_nom' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('pdfcartelecteur', 'largeur_nom', '70') " ;
			echo traite_rqt($rqt,"insert pdfcartelecteur,largeur_nom into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfcartelecteur' and sstype_param='valabledu' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('pdfcartelecteur', 'valabledu', 'Valable du') " ;
			echo traite_rqt($rqt,"insert pdfcartelecteur,valabledu into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfcartelecteur' and sstype_param='valableau' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('pdfcartelecteur', 'valableau', 'au') " ;
			echo traite_rqt($rqt,"insert pdfcartelecteur,valableau into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfcartelecteur' and sstype_param='carteno' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('pdfcartelecteur', 'carteno', 'Carte N° :') " ;
			echo traite_rqt($rqt,"insert pdfcartelecteur,carteno into parametres");
			}
				
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.28");
		break;

	case "v1.28":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "ALTER TABLE procs ADD autorisations MEDIUMTEXT " ;
		echo traite_rqt($rqt,"procs add autorisations ");
		$rqt = "update procs set autorisations='1' where autorisations is null or autorisations='' " ;
		echo traite_rqt($rqt,"update procs.autorisations where NULL");
		$rqt = "CREATE TABLE if not exists sauv_tables (
			 sauv_table_id int(10) unsigned NOT NULL auto_increment,
			 sauv_table_nom varchar(50) default NULL,
			 sauv_table_tables text,
			 PRIMARY KEY (sauv_table_id),
			 UNIQUE KEY sauv_table_nom (sauv_table_nom)
			) TYPE=MyISAM ";
		echo traite_rqt($rqt,"create table sauv_tables");
		$rqt = "INSERT INTO sauv_tables (sauv_table_id, sauv_table_nom, sauv_table_tables) VALUES (0, 'Biblio', 'analysis,bulletins,docs_codestat,docs_location,docs_section,docs_statut,docs_type,exemplaires,notices')"; 
		echo traite_rqt($rqt,"insert sauv_tables Biblio");
		$rqt = "INSERT INTO sauv_tables (sauv_table_id, sauv_table_nom, sauv_table_tables) VALUES (0, 'Autorités', 'authors,collections,publishers,series,sub_collections')"; 
		echo traite_rqt($rqt,"insert sauv_tables Autorités");
		$rqt = "INSERT INTO sauv_tables (sauv_table_id, sauv_table_nom, sauv_table_tables) VALUES (0, 'Aucune utilité', 'error_log,import_marc,sessions')"; 
		echo traite_rqt($rqt,"insert sauv_tables Aucune utilité");
		$rqt = "INSERT INTO sauv_tables (sauv_table_id, sauv_table_nom, sauv_table_tables) VALUES (0, 'Z3950', 'z_attr,z_bib,z_notices,z_query')"; 
		echo traite_rqt($rqt,"insert sauv_tables Z3950");
		$rqt = "INSERT INTO sauv_tables (sauv_table_id, sauv_table_nom, sauv_table_tables) VALUES (0, 'Emprunteurs', 'empr,empr_categ,empr_codestat,empr_groupe,groupe,pret,pret_archive,resa')"; 
		echo traite_rqt($rqt,"insert sauv_tables Emprunteurs");
		$rqt = "INSERT INTO sauv_tables (sauv_table_id, sauv_table_nom, sauv_table_tables) VALUES (0, 'Application', 'categories,lenders,parametres,procs,sauv_lieux,sauv_tables,users')"; 
		echo traite_rqt($rqt,"insert sauv_tables Application");
		$rqt = "INSERT INTO sauv_tables (sauv_table_id, sauv_table_nom, sauv_table_tables) VALUES (0, 'TOUT', 'analysis,authors,bulletins,categories,collections,docs_codestat,docs_location,docs_section,docs_statut,docs_type,empr,empr_categ,empr_codestat,empr_groupe,error_log,exemplaires,groupe,import_marc,lenders,notices,parametres,pret,pret_archive,procs,publishers,resa,sauv_lieux,sauv_tables,series,sessions,sub_collections,users,z_attr,z_bib,z_notices,z_query')";
		echo traite_rqt($rqt,"insert sauv_tables TOUT");

		$rqt = "CREATE TABLE if not exists sauv_log (
			sauv_log_id int(10) unsigned NOT NULL auto_increment,
			sauv_log_start_date date default NULL,
			sauv_log_file varchar(255) default NULL,
			sauv_log_succeed int(11) default '0',
			sauv_log_messages mediumtext,
			sauv_log_userid int(11) default NULL,
			PRIMARY KEY  (sauv_log_id)
			) TYPE=MyISAM ";
		echo traite_rqt($rqt,"create table sauv_log");

		$rqt = "CREATE TABLE if not exists sauv_sauvegardes (
			sauv_sauvegarde_id int(10) unsigned NOT NULL auto_increment,
			sauv_sauvegarde_nom varchar(50) default NULL,
			sauv_sauvegarde_file_prefix varchar(20) default NULL,
			sauv_sauvegarde_tables mediumtext,
			sauv_sauvegarde_lieux mediumtext,
			sauv_sauvegarde_users mediumtext,
			sauv_sauvegarde_compress int(11) default '0',
			sauv_sauvegarde_compress_command mediumtext,
			sauv_sauvegarde_crypt int(11) default '0',
			sauv_sauvegarde_key1 varchar(32) default NULL,
			sauv_sauvegarde_key2 varchar(32) default NULL,
			PRIMARY KEY  (sauv_sauvegarde_id)
			) TYPE=MyISAM ";
		echo traite_rqt($rqt,"create table sauv_sauvegardes");

		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.29");
		break;

	case "v1.29":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		
		// -- --------------------------------------------
		// -- Modification de la table resa
		$rqt = "alter table resa add resa_idbulletin int(8) unsigned default '0' not null after resa_idnotice ";
		echo traite_rqt($rqt,"alter resa add resa_idbulletin ");
			
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.30");
		break;

	case "v1.30":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='resa_dispo' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('pmb', 'resa_dispo', '0') " ;
			echo traite_rqt($rqt,"insert pmb, resa_dispo,0 into parametres");
			}
				
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.31");
		break;

	case "v1.31":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		
		$rqt = "ALTER TABLE empr ADD empr_msg TINYTEXT null default '' AFTER empr_date_expiration " ;
		echo traite_rqt($rqt,"Alter table empr add empr_msg");
		
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'mailretard' and sstype_param='1objet' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('mailretard', '1objet', 'Documents en retard') " ;
			echo traite_rqt($rqt,"insert mailretard, 1objet... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'mailretard' and sstype_param='1before_list' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('mailretard', '1before_list', 'Sauf erreur de notre part, vous avez toujours en votre possession le ou les ouvrage(s) suivant(s) dont la durée de prêt est aujourd\'hui dépassée :') " ;
			echo traite_rqt($rqt,"insert mailretard, 1before_list... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'mailretard' and sstype_param='1after_list' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('mailretard', '1after_list', 'Nous vous remercions de prendre rapidement contact par téléphone au 00 00 00 00 00 ou de nous répondre par mail à mail@mail.mail pour étudier la possibilité de prolonger ces prêts ou de ramener les ouvrages concernés.') " ;
			echo traite_rqt($rqt,"insert mailretard, 1after_list... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'mailretard' and sstype_param='1madame_monsieur' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('mailretard', '1madame_monsieur', 'Madame, Monsieur') " ;
			echo traite_rqt($rqt,"insert mailretard, 1madame_monsieur... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'mailretard' and sstype_param='1fdp' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('mailretard', '1fdp', 'Le responsable de la Bibliothèque test PMB.') " ;
			echo traite_rqt($rqt,"insert mailretard, 1fdp... into parametres");
			}
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.40");
		break;

	case "v1.40":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "ALTER TABLE pret_archive ADD arc_expl_id INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL ";
		echo traite_rqt($rqt,"pret_archive add arc_expl_id int 10 unsigned ");
		$rqt = "ALTER TABLE pret_archive ADD arc_expl_notice INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL  ";
		echo traite_rqt($rqt,"pret_archive add arc_expl_notice int 10 unsigned ");
		$rqt = "ALTER TABLE pret_archive ADD arc_expl_bulletin INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL  ";
		echo traite_rqt($rqt,"pret_archive add arc_expl_bulletin int 10 unsigned ");
		
		// Création du FULLTEXT sur notices.n_gen pour utilisation des match against :
		$rqt = "ALTER TABLE notices DROP INDEX i_n_gen " ;
		echo traite_rqt($rqt,"i_n_gen DROP INDEX");
		$rqt = "alter table notices add (FULLTEXT i_n_gen (n_gen)) " ;
		echo traite_rqt($rqt,"FULLTEXT i_n_gen (n_gen)");
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.41");
		break;

	case "v1.41":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = " ALTER TABLE categories DROP INDEX categ_id ";
		echo traite_rqt($rqt,"categories drop index categ_id");
		$rqt = " ALTER TABLE categories drop INDEX categ_parent ";
		echo traite_rqt($rqt,"categories drop index categ_parent");
		$rqt = " ALTER TABLE categories ADD INDEX categ_parent ( categ_parent ) ";
		echo traite_rqt($rqt,"categories add index categ_parent");
		$rqt = " ALTER TABLE categories ADD categ_comment text NOT NULL default '' ";
		echo traite_rqt($rqt,"categories add categ_comment");
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.42");
		break;

	case "v1.42":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = " ALTER TABLE notices DROP INDEX categ1 ";
		echo traite_rqt($rqt,"notices drop index categ1");
		$rqt = " ALTER TABLE notices ADD INDEX categ1 ( categ1 ) ";
		echo traite_rqt($rqt,"notices add index categ1");
		$rqt = " ALTER TABLE notices DROP INDEX categ2 ";
		echo traite_rqt($rqt,"notices drop index categ2");
		$rqt = " ALTER TABLE notices ADD INDEX categ2 ( categ2 ) ";
		echo traite_rqt($rqt,"notices add index categ2");
		$rqt = " ALTER TABLE notices DROP INDEX categ3 ";
		echo traite_rqt($rqt,"notices drop index categ3");
		$rqt = " ALTER TABLE notices ADD INDEX categ3 ( categ3 ) ";
		echo traite_rqt($rqt,"notices add index categ3");
		$rqt = " ALTER TABLE notices DROP INDEX categ4 ";
		echo traite_rqt($rqt,"notices drop index categ4");
		$rqt = " ALTER TABLE notices ADD INDEX categ4 ( categ4 ) ";
		echo traite_rqt($rqt,"notices add index categ4");

		$rqt = " ALTER TABLE publishers CHANGE ed_cp ed_cp VARCHAR( 10 ) NOT NULL ";
		echo traite_rqt($rqt,"publishers change ed_cp varchar(10) ");
		
		$rqt = " ALTER TABLE procs CHANGE name name VARCHAR( 255 ) NOT NULL ";
		echo traite_rqt($rqt,"procs change name varchar(255) ");

		$rqt = " ALTER TABLE empr CHANGE empr_cp empr_cp VARCHAR( 10 ) NOT NULL ";
		echo traite_rqt($rqt,"empr change empr_cp varchar(10) ");

		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.43");
		break;

	case "v1.43":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "ALTER TABLE empr ADD empr_lang VARCHAR( 10 ) DEFAULT 'fr_FR' NOT NULL ";
		echo traite_rqt($rqt,"alter empr add empr_lang varchar(10) default fr_FR ");
		
		$rqt = "ALTER TABLE authors ADD author_web VARCHAR( 255 ) DEFAULT '' NOT NULL ";
		echo traite_rqt($rqt,"alter author add author_web varchar(255) default '' ");
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.44");
		break;

	case "v1.44":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='serial_link_article' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('pmb', 'serial_link_article', '0') " ;
			echo traite_rqt($rqt,"insert pmb, serial_link_article... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='num_carte_auto' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param) VALUES ('pmb', 'num_carte_auto', '0') " ;
			echo traite_rqt($rqt,"insert pmb, num_carte_auto... into parametres");
			}
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.45");
		break;

	case "v1.45":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		
		error_reporting (E_ALL);
		
		$rqt = "ALTER TABLE parametres add comment_param VARCHAR( 255 ) DEFAULT '' ";
		echo traite_rqt($rqt,"alter parametres add comment_param varchar(255) default '' ");
		$rqt = "ALTER TABLE parametres CHANGE sstype_param sstype_param VARCHAR( 255 ) DEFAULT null ";
		echo traite_rqt($rqt,"alter parametres change sstype_param varchar(255) default NULL ");
		
		$rqt = "ALTER TABLE import_marc ADD origine VARCHAR( 20 ) default '' ";
		echo traite_rqt($rqt,"alter import_marc add origine varchar(20) ");
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='modules_search_title' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'modules_search_title', '1', 'Chercher dans le champs titre') " ;
			echo traite_rqt($rqt,"insert opac, modules_search_title... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='modules_search_author' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'modules_search_author', '1', 'Chercher dans les champs Auteur') " ;
			echo traite_rqt($rqt,"insert opac, modules_search_author... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='modules_search_publisher' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'modules_search_publisher', '1', 'Chercher dans les champs éditeur') " ;
			echo traite_rqt($rqt,"insert opac, modules_search_publisher... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='modules_search_collection' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'modules_search_collection', '1', 'Chercher dans le champ collection') " ;
			echo traite_rqt($rqt,"insert opac, modules_search_collection... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='modules_search_subcollection' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'modules_search_subcollection', '1', 'Chercher dans le champ Sous collection') " ;
			echo traite_rqt($rqt,"insert opac, modules_search_subcollection... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='modules_search_category' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'modules_search_category', '1', 'Chercher dans les champs catégories') " ;
			echo traite_rqt($rqt,"insert opac, modules_search_category... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='modules_search_keywords' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'modules_search_keywords', '1', 'Chercher dans le champ Mots clés') " ;
			echo traite_rqt($rqt,"insert opac, modules_search_keywords... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='modules_search_abstract' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'modules_search_abstract', '1', 'Chercher dans le champ résumé') " ;
			echo traite_rqt($rqt,"insert opac, modules_search_abstract... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='modules_search_content' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'modules_search_content', '1', 'Chercher dans le champ Note de contenu') " ;
			echo traite_rqt($rqt,"insert opac, modules_search_content... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='categories_categ_path_sep' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'categories_categ_path_sep', '>', 'Séparateur pour les catégories') " ;
			echo traite_rqt($rqt,"insert opac, categories_categ_path_sep... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='categories_columns' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'categories_columns', '2', 'Nombre de colonnes du sommaire général des catégories') " ;
			echo traite_rqt($rqt,"insert opac, categories_columns... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='categories_nb_col_subcat' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'categories_nb_col_subcat', '3', 'Nombre de colonnes pour les sous catégories d\'une catégorie (see ./includes/categ_see.inc.php)') " ;
			echo traite_rqt($rqt,"insert opac, categories_nb_col_subcat... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='categories_categ_rec_per_page' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'categories_categ_rec_per_page', '6', 'Nombre de notices à afficher par page dans l\'exploration des catégories') " ;
			echo traite_rqt($rqt,"insert opac, categories_categ_rec_per_page... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='categories_categ_sort_records' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'categories_categ_sort_records', 'index_serie, index_tit1', 'Explorateur de catégories : mode de tri des notices') " ;
			echo traite_rqt($rqt,"insert opac, categories_categ_sort_records... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='search_results_first_level' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'search_results_first_level', '4', 'Nombre de résulats affichés sur la première page') " ;
			echo traite_rqt($rqt,"insert opac, search_results_first_level... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='search_results_per_page' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'search_results_per_page', '10', 'Nombre de résulats affichés sur les pages suivantes') " ;
			echo traite_rqt($rqt,"insert opac, search_results_per_page... into parametres");
			}
			
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='authors_aut_rec_per_page' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'authors_aut_rec_per_page', '1', 'Nombre d\'auteurs affichés par page') " ;
			echo traite_rqt($rqt,"insert opac, authors_aut_rec_per_page... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='categories_sub_display' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'categories_sub_display', '8', 'Nombre de sous-categories sur la première page') " ;
			echo traite_rqt($rqt,"insert opac, categories_sub_display... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='categories_sub_mode' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'categories_sub_mode', 'categ_libelle', 'Mode affichage des sous-categories : rand > aléatoire, categ_libelle > ordre alpha') " ;
			echo traite_rqt($rqt,"insert opac, categories_sub_mode... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='authors_aut_sort_records' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'authors_aut_sort_records', 'index_serie, index_tit1', 'Visu auteurs : tri des notices') " ;
			echo traite_rqt($rqt,"insert opac, authors_aut_sort_records... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='default_lang' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'default_lang', 'fr_FR', 'Langue de l\'opac : fr_FR ou en_US ou es_ES ou ar') " ;
			echo traite_rqt($rqt,"insert opac, default_lang... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_categ_browser' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'show_categ_browser', '1', 'Affichage des catégories en page d\'accueil OPAC 1: oui  ou 0: non') " ;
			echo traite_rqt($rqt,"insert opac, show_categ_browser... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='resa' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'resa', '0', 'Réservations possibles par l\'OPAC 1: oui  ou 0: non') " ;
			echo traite_rqt($rqt,"insert opac, resa... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='resa_dispo' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'resa_dispo', '0', 'Réservations possibles de documents disponibles par l\'OPAC 1: oui  ou 0: non') " ;
			echo traite_rqt($rqt,"insert opac, resa_dispo... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_book_pics' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'show_book_pics', '1', 'Affichage des couvertures de livres dans l\'OPAC 1: oui  ou 0: non') " ;
			echo traite_rqt($rqt,"insert opac, show_book_pics... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_meteo' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'show_meteo', '1', 'Affichage de la météo dans l\'OPAC 1: oui  ou 0: non') " ;
			echo traite_rqt($rqt,"insert opac, show_meteo... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='duration_session_auth' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'duration_session_auth', '1200', 'Durée de la session lecteur dans l\'OPAC en secondes') " ;
			echo traite_rqt($rqt,"insert opac, duration_session_auth... into parametres");
			}

		$rqt = "select * from lenders where idlender=0";
		$res = mysql_query($rqt, $dbh);
		if (mysql_num_rows($res)==1) {
		
			$rqt = "select max(idlender) as max_id from lenders ";
			$res = mysql_query($rqt, $dbh);
			$max_lender = mysql_fetch_object($res);
			$new_max_lender =  $max_lender->max_id+1;
                	
			$rqt = "update lenders set idlender='$new_max_lender' where idlender=0";
			echo traite_rqt($rqt,"update lenders idlender id = 0");
			
			$rqt = "update docs_codestat set statisdoc_owner = '$new_max_lender' where statisdoc_owner=0";
			echo traite_rqt($rqt,"update docs_codestat owner");
			$rqt = "update docs_location set locdoc_owner = '$new_max_lender' where locdoc_owner=0";
			echo traite_rqt($rqt,"update docs_location owner");
			$rqt = "update docs_section set sdoc_owner = '$new_max_lender' where sdoc_owner=0";
			echo traite_rqt($rqt,"update docs_section owner");
			$rqt = "update docs_statut set statusdoc_owner = '$new_max_lender' where statusdoc_owner=0";
			echo traite_rqt($rqt,"update docs_statut owner");
			$rqt = "update docs_type set tdoc_owner = '$new_max_lender' where tdoc_owner=0";
			echo traite_rqt($rqt,"update docs_type owner");
			$rqt = "update exemplaires set expl_owner = '$new_max_lender' where expl_owner=0";
			echo traite_rqt($rqt,"update exemplaire owner");
			}
		
		$rqt = "ALTER TABLE lenders drop fonds_flag ";
		echo traite_rqt($rqt,"alter lenders drop fonds_flag ");
			
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.50");
		break;

	case "v1.50":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		
		error_reporting (E_ALL);
		$rqt = "ALTER TABLE notices ADD indexint INT( 8 ) UNSIGNED DEFAULT '0' NOT NULL AFTER index_l ";
		echo traite_rqt($rqt,"alter notices add indexint ");
		
		$rqt = "alter table notices drop index indexint ";
		echo traite_rqt($rqt,"notices drop index indexint");
		$rqt = "ALTER TABLE notices ADD INDEX indexint ( indexint ) ";
		echo traite_rqt($rqt,"alter notices add index indexint ");
		
		$rqt = "CREATE TABLE if not exists indexint (indexint_id mediumint(8) unsigned NOT NULL auto_increment, indexint_name varchar(255) NOT NULL default '',indexint_comment varchar(255) NOT NULL default '', PRIMARY KEY  (indexint_id), UNIQUE KEY indexint_name (indexint_name)) TYPE=MyISAM ";
		echo traite_rqt($rqt,"create table indexint ");
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.51");
		break;

	case "v1.51":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "ALTER TABLE empr modify empr_cb varchar ( 20 ) ";
		echo traite_rqt($rqt,"alter empr, empr_cb varchar(20) ");
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.52");
		break;

	case "v1.52":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "drop TABLE if exists caddie ";
		echo traite_rqt($rqt,"Drop table caddie ");
		$rqt = "drop TABLE if exists caddie_content";
		echo traite_rqt($rqt,"Drop table caddie_content");
		$rqt = "CREATE TABLE if not exists caddie ( idcaddie int(8) unsigned NOT NULL auto_increment, name varchar(100) default NULL, type varchar(20) NOT NULL default 'NOTI', comment varchar(255) default NULL, autorisations mediumtext, PRIMARY KEY  (idcaddie), KEY caddie_type (type) ) TYPE=MyISAM ";
		echo traite_rqt($rqt,"Création table caddie ");
		$rqt = "CREATE TABLE if not exists caddie_content (caddie_id int(8) unsigned NOT NULL default '0', object_id int(10) unsigned NOT NULL default '0', content blob, blob_type varchar(10) default NULL, flag varchar(10) default NULL, KEY (caddie_id,object_id), KEY object_id (object_id) ) TYPE=MyISAM " ;
		echo traite_rqt($rqt,"Création table caddie_content ");
		$rqt = "CREATE TABLE if not exists caddie_procs ( idproc smallint(5) unsigned NOT NULL auto_increment, type varchar(20) NOT NULL default 'SELECT', name varchar(255) NOT NULL default '', requete blob NOT NULL, comment tinytext NOT NULL, autorisations mediumtext, PRIMARY KEY  (idproc), KEY idproc (idproc) ) TYPE=MyISAM ";
		echo traite_rqt($rqt,"Création table caddie_procs ");
		$rqt = "ALTER TABLE caddie_procs ADD parameters TEXT ";
		echo traite_rqt($rqt,"Table caddie_procs, add parameters ") ;

		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.53");
		break;

	case "v1.53":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		
		$rqt = "ALTER TABLE users ADD param_sounds SMALLINT(1) UNSIGNED DEFAULT '1' NOT NULL AFTER param_popup_ticket ";
		echo traite_rqt($rqt,"paramètre user pour les sons ");
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='relance_adhesion' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'relance_adhesion', '31', 'Nombre de jours avant expiration adhésion pour relance') " ;
			echo traite_rqt($rqt,"insert pmb, relance_adhesion... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='pret_adhesion_depassee' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'pret_adhesion_depassee', '1', 'Prêts si adhésion dépassée : 0 INTERDIT incontournable, 1 POSSIBLE') " ;
			echo traite_rqt($rqt,"insert pmb, pret_adhesion_depassee... into parametres");
			}
		$rqt = "ALTER TABLE parametres CHANGE valeur_param valeur_param TEXT " ;
		echo traite_rqt($rqt,"alter parametres valeur_param TEXT");
		// paramètres de relance adhésion		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreadhesion' and sstype_param='fdp' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('pdflettreadhesion', 'fdp', 'Le responsable de la $biblio_name.', 'Formule de politesse en bas de page')";
			echo traite_rqt($rqt,"insert pmb, pret_adhesion_depassee... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreadhesion' and sstype_param='madame_monsieur' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('pdflettreadhesion', 'madame_monsieur', 'Madame, Monsieur,', 'Civilité du destinataire')";
			echo traite_rqt($rqt,"insert pdf et mail , adhesion depassée... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreadhesion' and sstype_param='texte' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('pdflettreadhesion', 'texte', 'Votre abonnement à la bibliothèque municipale arrive à échéance le !!date_fin_adhesion!!. Nous vous remercions de penser à le renouveller lors de votre prochaine visite.\r\n\r\nNous vous prions de recevoir, Madame, Monsieur, l\'expression de nos meilleures salutations.\r\n\r\n\r\n', 'Phrase d\'introduction de l\'échéance de l\'abonnement')";
			echo traite_rqt($rqt,"insert pdf et mail , adhesion depassée... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreadhesion' and sstype_param='marge_page_gauche' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('pdflettreadhesion', 'marge_page_gauche', '10', '')";
			echo traite_rqt($rqt,"insert pdf et mail , adhesion depassée... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreadhesion' and sstype_param='marge_page_droite' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('pdflettreadhesion', 'marge_page_droite', '10', '')";
			echo traite_rqt($rqt,"insert pdf et mail , adhesion depassée... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreadhesion' and sstype_param='largeur_page' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('pdflettreadhesion', 'largeur_page', '210', '')";
			echo traite_rqt($rqt,"insert pdf et mail , adhesion depassée... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreadhesion' and sstype_param='hauteur_page' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('pdflettreadhesion', 'hauteur_page', '297', '')";
			echo traite_rqt($rqt,"insert pdf et mail , adhesion depassée... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreadhesion' and sstype_param='format_page' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('pdflettreadhesion', 'format_page', 'P', 'P pour Portrait, L pour paysage (Landscape)')";
			echo traite_rqt($rqt,"insert pdf et mail , adhesion depassée... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'mailrelanceadhesion' and sstype_param='objet' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('mailrelanceadhesion', 'objet', 'Bibliothèque : votre abonnement', '')";
			echo traite_rqt($rqt,"insert pdf et mail , adhesion depassée... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'mailrelanceadhesion' and sstype_param='texte' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('mailrelanceadhesion', 'texte', 'Votre abonnement à la bibliothèque municipale arrive à échéance le !!date_fin_adhesion!!. Nous vous remercions de penser à le renouveller lors de votre prochaine visite.\r\n\r\nCordialement,\r\n\r\n', '')";
			echo traite_rqt($rqt,"insert pdf et mail , adhesion depassée... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'mailrelanceadhesion' and sstype_param='madame_monsieur' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('mailrelanceadhesion', 'madame_monsieur', 'Madame, Monsieur,', '')";
			echo traite_rqt($rqt,"insert pdf et mail , adhesion depassée... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'mailrelanceadhesion' and sstype_param='fdp' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('mailrelanceadhesion', 'fdp', 'Le responsable de la $biblio_name.', 'Formule de politesse en bas de page') ";
			echo traite_rqt($rqt,"insert pdf et mail , adhesion depassée... into parametres");
			}
		$rqt = "CREATE TABLE if not exists empr_custom (idchamp int(10) unsigned NOT NULL auto_increment, name varchar(255) NOT NULL default '', titre varchar(255) default NULL, type varchar(10) NOT NULL default 'text', datatype varchar(10) NOT NULL default '', options text, multiple int(11) NOT NULL default '0', obligatoire int(11) NOT NULL default '0', ordre int(11) default NULL, PRIMARY KEY  (idchamp)) TYPE=MyISAM " ;
		echo traite_rqt($rqt,"Create table empr_custom");
		$rqt = "CREATE TABLE if not exists empr_custom_values ( idcustomvalue int(10) unsigned NOT NULL auto_increment, empr_custom_champ int(10) unsigned NOT NULL default '0', empr_custom_empr int(10) unsigned NOT NULL default '0', empr_custom_small_text varchar(255) default NULL, empr_custom_text text, empr_custom_integer int(11) default NULL, empr_custom_date date default NULL, empr_custom_float float default NULL, PRIMARY KEY  (idcustomvalue) ) TYPE=MyISAM" ;
		echo traite_rqt($rqt,"Create table empr_custom_values");

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_marguerite_browser' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('opac', 'show_marguerite_browser', '0', '0 ou 1 : marguerite des catégories') ";
			echo traite_rqt($rqt,"insert pdf et mail , adhesion depassée... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_100cases_browser' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('opac', 'show_100cases_browser', '0', '0 ou 1 : affichage de 100 catégories') ";
			echo traite_rqt($rqt,"insert pdf et mail , adhesion depassée... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='indexint_decimal' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('pmb', 'indexint_decimal', '1', '0 ou 1 : l\'indexation interne est-elle une cotation décimale type Dewey') ";
			echo traite_rqt($rqt,"insert pdf et mail , adhesion depassée... into parametres");
			}

		$rqt = "ALTER TABLE indexint CHANGE indexint_comment indexint_comment TEXT ";		
		echo traite_rqt($rqt,"change indexint_comment TEXT");
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.54");
		break;

	case "v1.54":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		
		$rqt = "ALTER TABLE exemplaires ADD expl_lastempr INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL  ";
		echo traite_rqt($rqt,"add exemplaires expl_lastempr");

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='modules_search_indexint' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'modules_search_indexint', '1', 'Chercher dans les indexations internes') " ;
			echo traite_rqt($rqt,"insert opac, modules_search_category... into parametres");
			}
		$rqt = "CREATE TABLE if not exists empr_custom_lists (idlist int(10) unsigned NOT NULL auto_increment, empr_custom_champ int(10) unsigned NOT NULL default '0', empr_custom_list_value varchar(255) default NULL, empr_custom_list_lib varchar(255) default NULL, ordre int(11) default NULL, PRIMARY KEY  (idlist)) TYPE=MyISAM ";
		echo traite_rqt($rqt,"CREATE TABLE empr_custom_lists");
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.55");
		break;

	case "v1.55":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "update procs set requete = replace (requete, '!!1!!', '!!param1!!') " ;
		echo traite_rqt($rqt,"update param procs 1");
		$rqt = "update procs set requete = replace (requete, '!!2!!', '!!param2!!') " ;
		echo traite_rqt($rqt,"update param procs 2");
		$rqt = "update procs set requete = replace (requete, '!!3!!', '!!param3!!') " ;
		echo traite_rqt($rqt,"update param procs 3");
		$rqt = "ALTER TABLE procs ADD parameters TEXT ";
		echo traite_rqt($rqt,"Table procs, add parameters ") ;

		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.56");
		break;

	case "v1.56":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "ALTER TABLE users ADD deflt_docs_statut INT( 6 ) UNSIGNED DEFAULT '0' ";
		echo traite_rqt($rqt,"Default docs_statut user ") ;
		$rqt = "ALTER TABLE users ADD deflt_docs_codestat INT( 6 ) UNSIGNED DEFAULT '0' ";
		echo traite_rqt($rqt,"Default docs_codestat user ") ;
		
		$rqt = "ALTER TABLE users ADD value_deflt_lang varchar( 20 ) default 'fre' ";
		echo traite_rqt($rqt,"Default value_deflt_lang user ") ;
		$rqt = "ALTER TABLE users ADD value_deflt_fonction varchar( 20 ) default '070' ";
		echo traite_rqt($rqt,"Default value_deflt_fonction user ") ;
		
		$rqt = "ALTER TABLE users ADD deflt_docs_location INT( 6 ) UNSIGNED DEFAULT '0' ";
		echo traite_rqt($rqt,"Default docs_location user ") ;
		$rqt = "ALTER TABLE users ADD deflt_docs_section INT( 6 ) UNSIGNED DEFAULT '0' ";
		echo traite_rqt($rqt,"Default docs_section user ") ;
		
		$rqt = "ALTER TABLE exemplaires drop INDEX date_creation ";
		echo traite_rqt($rqt,"table exemplaires drop index date_creation ") ;
		$rqt = "ALTER TABLE exemplaires ADD INDEX date_creation (expl_creation) ";
		echo traite_rqt($rqt,"table exemplaires add index date_creation ") ;
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.57");
		break;

	case "v1.57":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "ALTER TABLE exemplaires CHANGE expl_cote expl_cote varchar(20) NOT NULL default '' ";		
		echo traite_rqt($rqt,"change expl_cote varchar(20)");
		$rqt = "ALTER TABLE pret_archive CHANGE arc_expl_cote arc_expl_cote varchar(20) NOT NULL default '' ";		
		echo traite_rqt($rqt,"change arc_expl_cote varchar(20)");
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v1.58");
		break;

	case "v1.58":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "ALTER TABLE empr CHANGE empr_ville empr_ville VARCHAR( 255 ) NOT NULL ";
		echo traite_rqt($rqt,"ville varchar(255)");
		$rqt = "ALTER TABLE empr CHANGE empr_adr1 empr_adr1 VARCHAR( 255 ) NOT NULL ";
		echo traite_rqt($rqt,"adr1 varchar(255)");
		$rqt = "ALTER TABLE empr CHANGE empr_adr2 empr_adr2 VARCHAR( 255 ) NOT NULL ";
		echo traite_rqt($rqt,"adr2 varchar(255)");
		$rqt = "ALTER TABLE empr CHANGE empr_tel1 empr_tel1 VARCHAR( 255 ) NOT NULL ";
		echo traite_rqt($rqt,"tel1 varchar(255)");
		$rqt = "ALTER TABLE empr CHANGE empr_tel2 empr_tel2 VARCHAR( 255 ) NOT NULL ";
		echo traite_rqt($rqt,"tel2 varchar(255)");
		$rqt = "ALTER TABLE empr CHANGE empr_nom empr_nom VARCHAR( 255 ) NOT NULL ";
		echo traite_rqt($rqt,"nom varchar(255)");
		$rqt = "ALTER TABLE empr CHANGE empr_prenom empr_prenom VARCHAR( 255 ) NOT NULL ";
		echo traite_rqt($rqt,"prenom varchar(255)");
		$rqt = "ALTER TABLE empr ADD empr_pays VARCHAR( 255 ) NOT NULL default '' AFTER empr_ville ";
		echo traite_rqt($rqt,"ADD pays varchar(255)");
		
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v2.00");
		break;

	default:
		include("$include_path/messages/help/$lang/alter.txt");
		break;
	}
