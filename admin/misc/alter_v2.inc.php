<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: alter_v2.inc.php,v 1.71 2009-05-16 11:11:52 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

settype ($action,"string");

switch ($action) {
	case "lancement":
		switch ($version_pmb_bdd) {
			case "v1.58":
				$maj_a_faire = "v2.00";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.00":
				$maj_a_faire = "v2.01";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.01":
				$maj_a_faire = "v2.02";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.02":
				$maj_a_faire = "v2.03";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.03":
				$maj_a_faire = "v2.04";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.04":
				$maj_a_faire = "v2.05";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.05":
				$maj_a_faire = "v2.06";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.06":
				$maj_a_faire = "v2.07";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.07":
				$maj_a_faire = "v2.08";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.08":
				$maj_a_faire = "v2.09";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.09":
				$maj_a_faire = "v2.10";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.09":
				$maj_a_faire = "v2.10";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.10":
				$maj_a_faire = "v2.11";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.11":
				$maj_a_faire = "v2.12";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.12":
				$maj_a_faire = "v2.13";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.13":
				$maj_a_faire = "v2.14";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.14":
				$maj_a_faire = "v2.15";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.15":
				$maj_a_faire = "v2.16";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.16":
				$maj_a_faire = "v2.17";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.17":
				$maj_a_faire = "v2.18";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.18":
				$maj_a_faire = "v2.19";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.19":
				$maj_a_faire = "v2.20";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.20":
				$maj_a_faire = "v2.21";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.21":
				$maj_a_faire = "v2.22";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.22":
				$maj_a_faire = "v2.23";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.23":
				$maj_a_faire = "v2.24";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v2.24":
				$maj_a_faire = "v3.00";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			default:
				echo "<strong><font color='#FF0000'>".$msg[1806].$version_pmb_bdd." !</font></strong><br />";
				break;
			}
		break;	
	
	case "v2.00":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'empr' and sstype_param='birthdate_optional' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'empr', 'birthdate_optional', '0', 'Année de naissance facultative : \n 0 > non:elle est obligatoire \n 1 Oui') " ;
			echo traite_rqt($rqt,"insert empr, birthdate_optional... into parametres");
			}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v2.01");
		break;	
	

	case "v2.01":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "CREATE TABLE if not exists responsability (responsability_author mediumint(8) unsigned NOT NULL default 0, responsability_notice mediumint(8) unsigned NOT NULL default 0, responsability_fonction char(3) NOT NULL default '', responsability_type mediumint(1) unsigned NOT NULL default 0, PRIMARY KEY  (responsability_author, responsability_notice, responsability_fonction), KEY responsability_author (responsability_author), KEY responsability_notice (responsability_notice)) TYPE=MyISAM " ;
		echo traite_rqt($rqt,"CREATE TABLE responsability ");

		$rqt_notices = "select notice_id, aut1_id, f1_code, aut2_id, f2_code , aut3_id, f3_code , aut4_id, f4_code from notices ";
		$res_notices = @mysql_query($rqt_notices, $dbh);
		$nbr_notices = @mysql_num_rows($res_notices);
		for($i=0; $i<$nbr_notices; $i++) {
			$notice=mysql_fetch_object($res_notices);
                	// si auteur 1 seul >> primaire 
                	if (!$notice->aut2_id && $notice->aut1_id) {
                		$rqt_ins = "insert into responsability (responsability_author, responsability_notice, responsability_fonction, responsability_type) ";
                		$rqt_ins.= "values ('$notice->aut1_id', '$notice->notice_id', '$notice->f1_code', 0) " ;
                		$res_insert = @mysql_query($rqt_ins, $dbh);
                		}
			// si auteur 1 & 2 >> alternatif 
                	if ($notice->aut2_id && $notice->aut1_id) {
                		$rqt_ins = "insert into responsability (responsability_author, responsability_notice, responsability_fonction, responsability_type) ";
                		$rqt_ins.= "values ('$notice->aut1_id', '$notice->notice_id', '$notice->f1_code', 1) " ;
                		$res_insert = @mysql_query($rqt_ins, $dbh);
                		$rqt_ins = "insert into responsability (responsability_author, responsability_notice, responsability_fonction, responsability_type) ";
                		$rqt_ins.= "values ('$notice->aut2_id', '$notice->notice_id', '$notice->f2_code', 1) " ;
                		$res_insert = @mysql_query($rqt_ins, $dbh);
                		}
                	// auteur 3 et 4 >> secondaires 
                	if ($notice->aut3_id) {
                		$rqt_ins = "insert into responsability (responsability_author, responsability_notice, responsability_fonction, responsability_type) ";
                		$rqt_ins.= "values ('$notice->aut3_id', '$notice->notice_id', '$notice->f3_code', 2) " ;
                		$res_insert = @mysql_query($rqt_ins, $dbh);
                		}
                	if ($notice->aut4_id) {
                		$rqt_ins = "insert into responsability (responsability_author, responsability_notice, responsability_fonction, responsability_type) ";
                		$rqt_ins.= "values ('$notice->aut4_id', '$notice->notice_id', '$notice->f4_code', 2) " ;
                		$res_insert = @mysql_query($rqt_ins, $dbh);
                		}
                	}
		$rqt = "ALTER TABLE notices DROP aut1_id, DROP aut2_id, DROP aut3_id, DROP aut4_id, DROP f1_code, DROP f2_code, DROP f3_code, DROP f4_code " ;
		echo traite_rqt($rqt,"drop authors from notices ");
				
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v2.02");
		break;	
	
	case "v2.02":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "ALTER TABLE categories ADD INDEX ( categ_libelle ) ";
		echo traite_rqt($rqt,"index on categ.categ_libelle ");
		$rqt = "CREATE TABLE if not exists categ_assoc ( categ_assoc_categid INT( 8 ) UNSIGNED DEFAULT 0 NOT NULL , categ_assoc_categassoc INT( 8 ) UNSIGNED DEFAULT 0 NOT NULL , PRIMARY KEY ( categ_assoc_categid , categ_assoc_categassoc ) ) ";
		echo traite_rqt($rqt,"create table categ_assoc ");

		$rqt = "CREATE TABLE if not exists notices_categories ( notcateg_notice INT( 8 ) UNSIGNED DEFAULT 0 NOT NULL , notcateg_categorie INT( 8 ) UNSIGNED DEFAULT 0 NOT NULL , PRIMARY KEY ( notcateg_notice , notcateg_categorie ) ) ";
		echo traite_rqt($rqt,"create table notices_categories ");
		
		$rqt_notices = "select notice_id, categ1, categ2, categ3, categ4 from notices ";
		$res_notices = @mysql_query($rqt_notices, $dbh);
		$nbr_notices = @mysql_num_rows($res_notices);
		for($i=0; $i<$nbr_notices; $i++) {
			$notice=mysql_fetch_object($res_notices);
                	if ($notice->categ1) {
                		$rqt_ins = "insert into notices_categories (notcateg_notice, notcateg_categorie) ";
                		$rqt_ins.= "values ('$notice->notice_id', '$notice->categ1') " ;
                		$res_insert = @mysql_query($rqt_ins, $dbh);
                		}
                	if ($notice->categ2) {
                		$rqt_ins = "insert into notices_categories (notcateg_notice, notcateg_categorie) ";
                		$rqt_ins.= "values ('$notice->notice_id', '$notice->categ2') " ;
                		$res_insert = @mysql_query($rqt_ins, $dbh);
                		}
                	if ($notice->categ3) {
                		$rqt_ins = "insert into notices_categories (notcateg_notice, notcateg_categorie) ";
                		$rqt_ins.= "values ('$notice->notice_id', '$notice->categ3') " ;
                		$res_insert = @mysql_query($rqt_ins, $dbh);
                		}
                	if ($notice->categ4) {
                		$rqt_ins = "insert into notices_categories (notcateg_notice, notcateg_categorie) ";
                		$rqt_ins.= "values ('$notice->notice_id', '$notice->categ4') " ;
                		$res_insert = @mysql_query($rqt_ins, $dbh);
                		}
                	}
		$rqt = "ALTER TABLE notices DROP categ1, DROP categ2, DROP categ3, DROP categ4 " ;
		echo traite_rqt($rqt,"drop categ from notices ");
		
		$rqt = " CREATE TABLE if not exists explnum (explnum_id int(11) unsigned NOT NULL auto_increment, explnum_notice mediumint(8) unsigned NOT NULL default 0, explnum_bulletin int(8) unsigned NOT NULL default 0, explnum_nom varchar(255) not null default'', explnum_mimetype varchar(255) NOT NULL default '', explnum_url TEXT NOT NULL default '', explnum_data mediumblob default null, explnum_vignette mediumblob default null, explnum_extfichier varchar(20) default '', explnum_nomfichier text default '', PRIMARY KEY  (explnum_id), KEY explnum_notice (explnum_notice), KEY explnum_bulletin (explnum_bulletin) )" ;
		echo traite_rqt($rqt,"create table explnum ");
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v2.03");
		break;	
	
	case "v2.03":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'categories' and sstype_param='show_empty_categ' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0,'categories','show_empty_categ','1','Affichage des catégories ne contenant aucune notice :\r\n0=non, 1=oui') " ;
			echo traite_rqt($rqt,"insert categories, show_empty_categ... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'categories' and sstype_param='term_search_n_per_page' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0,'categories','term_search_n_per_page','200','Nombre de termes affichés par page lors d\'une recherche par terme dans les catégories') " ;
			echo traite_rqt($rqt,"insert categories, show_empty_categ... into parametres");
			}

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v2.04");
		break;	
	
	case "v2.04":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "ALTER TABLE categories drop INDEX categ_libelle_2 " ;
		echo traite_rqt($rqt,"alter table categories drop index categ_libelle_2 redundant");
		$rqt = "ALTER TABLE categories drop INDEX categ_libelle_3 " ;
		echo traite_rqt($rqt,"alter table categories drop index categ_libelle_3 redundant");
		$rqt = "ALTER TABLE categories drop categ_assoc " ;
		echo traite_rqt($rqt,"alter table categories drop categ_assoc uselesss ");
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v2.05");
		break;	
	
	case "v2.05":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "CREATE TABLE if not exists origine_notice (orinot_id INT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT , orinot_nom VARCHAR( 255 ) NOT NULL default '', orinot_pays VARCHAR( 255 ) NOT NULL default 'FR', orinot_diffusion int(1) unsigned not null default 1, PRIMARY KEY ( orinot_id ) , INDEX ( orinot_nom ) ) " ;
		echo traite_rqt($rqt,"create table origine_notice ");
		if (mysql_num_rows(mysql_query("select 1 from origine_notice where orinot_id=1 "))==0){
			$rqt = "INSERT INTO origine_notice (orinot_id, orinot_nom) values (1,'Catalogage BM') " ;
			echo traite_rqt($rqt,"insert 1, 'catalogage BM' into origine_notice");
			}
		$rqt = "ALTER TABLE notices ADD origine_catalogage INT( 8 ) UNSIGNED DEFAULT 1 NOT NULL ";
		echo traite_rqt($rqt,"notices add origine_catalogage ");

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v2.06");
		break;	
	
	case "v2.06":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_loginform' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'show_loginform', '1', 'Affichage du login lecteur dans l\'OPAC \n 0 > non\n 1 Oui') " ;
			echo traite_rqt($rqt,"insert opac, show_login_form... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='default_style' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'default_style', '1', 'Style graphique de l\'OPAC, 1 style par défaut, nomargin : sans affichage du bandeau de gauche') " ;
			echo traite_rqt($rqt,"insert opac, default_style... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_exemplaires' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'show_exemplaires', '1', 'Afficher les exemplaires dans l\'OPAC\n 1 Oui,\n 0 : Non') " ;
			echo traite_rqt($rqt,"insert opac, show_exemplaires... into parametres");
			}
		$rqt = "ALTER TABLE authors CHANGE author_date1 author_date VARCHAR(255) NOT NULL" ;
		echo traite_rqt($rqt,"alter authors change date1 date varchar(255)");
		$rqt = "update authors set author_date = concat(author_date,'-', author_date2) where author_date2 <> '' " ;
		echo traite_rqt($rqt,"Authors : concat date & date 2");
		$rqt = "ALTER TABLE authors DROP author_date2 " ;
		echo traite_rqt($rqt,"Authors drop date2");
		
		$rqt = "ALTER TABLE notices ADD prix VARCHAR( 255 ) DEFAULT '' NOT NULL " ;
		echo traite_rqt($rqt,"notices add prix ");
		
		$rqt = "ALTER TABLE docs_location CHANGE locdoc_codage_import locdoc_codage_import varchar( 255 ) NOT NULL default '' " ;
		echo traite_rqt($rqt,"docs_location.locdoc_codage_import varchar 255");
		$rqt = "ALTER TABLE docs_section CHANGE sdoc_codage_import sdoc_codage_import varchar( 255 ) NOT NULL default '' " ;
		echo traite_rqt($rqt,"docs_section.sdoc_codage_import varchar 255");
		$rqt = "ALTER TABLE docs_type CHANGE tdoc_codage_import tdoc_codage_import varchar( 255 ) NOT NULL default '' " ;
		echo traite_rqt($rqt,"docs_type.tdoc_codage_import varchar 255");
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='import_modele' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'import_modele', 'func_bdp.inc.php', 'Quel script de fonctions d\'import utiliser pour personnaliser l\'import ?') " ;
			echo traite_rqt($rqt,"insert pmb, import_modele = func_bdp.inc.php... into parametres");
			}
		
		$rqt = "CREATE TABLE if not exists quotas (quota_type int(10) unsigned NOT NULL default '0', constraint_type varchar(255) NOT NULL default '', elements int(10) unsigned NOT NULL default '0', value float default NULL, PRIMARY KEY  (quota_type,constraint_type,elements) ) TYPE=MyISAM " ;
		echo traite_rqt($rqt,"create table quotas");
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='quotas_avances' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'quotas_avances', '0', 'Quotas de prêts avancées ? \n 0 : Non\n 1 : Oui') " ;
			echo traite_rqt($rqt,"insert pmb_quotas_avances = 0 into parametres");
			}
		
		$rqt = "ALTER TABLE docs_type ADD duree_resa INT( 6 ) UNSIGNED DEFAULT 15 NOT NULL AFTER duree_pret " ;
		echo traite_rqt($rqt,"alter table docs_type add duree_resa");
		
		$rqt = "ALTER TABLE resa CHANGE resa_date resa_date DATETIME DEFAULT NULL " ;
		echo traite_rqt($rqt,"alter table resa change date_resa datetime ");
		$rqt = "ALTER TABLE resa ADD resa_date_fin DATE NOT NULL " ;
		echo traite_rqt($rqt,"alter table resa add resa_date_fin");
		$rqt = "ALTER TABLE resa drop INDEX resa_date_fin " ;
		echo traite_rqt($rqt,"alter table resa drop index resa_date_fin");
		$rqt = "ALTER TABLE resa ADD INDEX resa_date_fin ( resa_date_fin )  " ;
		echo traite_rqt($rqt,"alter table resa add index resa_date_fin");
		
		$rqt = "ALTER TABLE resa drop INDEX resa_date " ;
		echo traite_rqt($rqt,"alter table resa drop index resa_date");
		$rqt = "ALTER TABLE resa ADD INDEX resa_date ( resa_date )  " ;
		echo traite_rqt($rqt,"alter table resa add index resa_date");
		
		$rqt = "update resa set resa_date_fin=DATE_ADD(resa_date, INTERVAL 15 DAY) " ;
		echo traite_rqt($rqt,"update resa resa_date_fin=resa_date + 15 DAYS");
		
		$rqt = "ALTER TABLE resa ADD resa_cb VARCHAR(14) NOT NULL default '' ";
		echo traite_rqt($rqt,"alter table resa add resa_cb ");
		$rqt = "ALTER TABLE resa drop INDEX resa_cb " ;
		echo traite_rqt($rqt,"alter table resa drop index resa_cb");
		$rqt = "ALTER TABLE resa ADD INDEX resa_cb ( resa_cb )  " ;
		echo traite_rqt($rqt,"alter table resa add index resa_cb");
		
		$rqt = "ALTER TABLE exemplaires CHANGE expl_prix expl_prix VARCHAR( 255 ) NOT NULL ";
		echo traite_rqt($rqt,"alter exemplaires prix varchar 255");

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v2.07");
		break;	
	
	case "v2.07":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "ALTER TABLE notices CHANGE eformat eformat VARCHAR( 255 ) NOT NULL default '' ";
		echo traite_rqt($rqt,"alter notices eformat varchar 255");
		$rqt = "CREATE TABLE if not exists resa_ranger (resa_cb varchar(14) NOT NULL default '', PRIMARY KEY (resa_cb)) ";
		echo traite_rqt($rqt,"create table resa_ranger ");
		
		$rqt = "CREATE TABLE if not exists etagere (idetagere int(8) unsigned NOT NULL auto_increment, name varchar(100) not NULL default '', comment blob not NULL default '', validite int(1) unsigned not null default 0, validite_date_deb date not null default '', validite_date_fin date not null default '', visible_accueil int(1) unsigned not null default 1, autorisations mediumtext, PRIMARY KEY  (idetagere)) ";
		echo traite_rqt($rqt,"create table etagere ");
		$rqt = "CREATE TABLE if not exists etagere_caddie (etagere_id int(8) unsigned NOT NULL default 0, caddie_id int(8) unsigned NOT NULL default 0, PRIMARY KEY (etagere_id, caddie_id)) ";
		echo traite_rqt($rqt,"create table etagere_caddie ");

		$rqt = "CREATE TABLE if not exists notices_custom (idchamp int(10) unsigned NOT NULL auto_increment, name varchar(255) NOT NULL default '', titre varchar(255) default NULL, type varchar(10) NOT NULL default 'text', datatype varchar(10) NOT NULL default '', options text, multiple int(11) NOT NULL default '0', obligatoire int(11) NOT NULL default '0', ordre int(11) default NULL, PRIMARY KEY  (idchamp)) ";
		echo traite_rqt($rqt,"create table notices_custom ");
		$rqt = "CREATE TABLE if not exists notices_custom_lists (notices_custom_champ int(10) unsigned NOT NULL default '0', notices_custom_list_value varchar(255) default NULL, notices_custom_list_lib varchar(255) default NULL, ordre int(11) default NULL, KEY notices_custom_champ (notices_custom_champ), KEY noti_champ_list_value (notices_custom_champ,notices_custom_list_value)) " ;
		echo traite_rqt($rqt,"create table notices_custom_lists ");
		$rqt = "CREATE TABLE if not exists notices_custom_values (notices_custom_champ int(10) unsigned NOT NULL default '0', notices_custom_origine int(10) unsigned NOT NULL default '0', notices_custom_small_text varchar(255) default NULL, notices_custom_text text, notices_custom_integer int(11) default NULL, notices_custom_date date default NULL, notices_custom_float float default NULL, KEY notices_custom_champ (notices_custom_champ), KEY notices_custom_origine (notices_custom_origine), KEY noti_champ_origine (notices_custom_champ,notices_custom_origine)) " ;
		echo traite_rqt($rqt,"create table notices_custom_values ");
		$rqt = "CREATE TABLE if not exists expl_custom (idchamp int(10) unsigned NOT NULL auto_increment, name varchar(255) NOT NULL default '', titre varchar(255) default NULL, type varchar(10) NOT NULL default 'text', datatype varchar(10) NOT NULL default '', options text, multiple int(11) NOT NULL default '0', obligatoire int(11) NOT NULL default '0', ordre int(11) default NULL, PRIMARY KEY  (idchamp)) " ;
		echo traite_rqt($rqt,"create table expl_custom ");
		$rqt = "CREATE TABLE if not exists expl_custom_lists (expl_custom_champ int(10) unsigned NOT NULL default '0', expl_custom_list_value varchar(255) default NULL, expl_custom_list_lib varchar(255) default NULL, ordre int(11) default NULL, KEY expl_custom_champ (expl_custom_champ), KEY expl_champ_list_value (expl_custom_champ,expl_custom_list_value)) " ;
		echo traite_rqt($rqt,"create table expl_custom_lists ");
		$rqt = "CREATE TABLE if not exists expl_custom_values (expl_custom_champ int(10) unsigned NOT NULL default '0', expl_custom_origine int(10) unsigned NOT NULL default '0', expl_custom_small_text varchar(255) default NULL, expl_custom_text text, expl_custom_integer int(11) default NULL, expl_custom_date date default NULL, expl_custom_float float default NULL, KEY expl_custom_champ (expl_custom_champ), KEY expl_custom_origine (expl_custom_origine), KEY expl_champ_origine (expl_custom_champ,expl_custom_origine)) " ;
		echo traite_rqt($rqt,"create table expl_custom_values ");

		// drop id sur empr_custom_*
		$rqt = "ALTER TABLE empr_custom_values DROP idcustomvalue  " ;
		echo traite_rqt($rqt,"alter table empr_custom_values drop idcustumvalue");
		$rqt = "ALTER TABLE empr_custom_lists DROP idlist  " ;
		echo traite_rqt($rqt,"alter table empr_custom_lists drop idlist");
		// modif sur empr_custom_*
		$rqt = "ALTER TABLE empr_custom_values CHANGE empr_custom_empr empr_custom_origine INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL ";
		echo traite_rqt($rqt,"alter table empr_custom_values rename empr_custom_empr > empr_custom_origine");
		// index sur empr_custom_*
		$rqt = "ALTER TABLE empr_custom_lists drop INDEX empr_custom_champ " ;
		echo traite_rqt($rqt,"alter table empr_custom_lists drop index empr_custom_champ");
		$rqt = "ALTER TABLE empr_custom_lists add INDEX empr_custom_champ ( empr_custom_champ )  " ;
		echo traite_rqt($rqt,"alter table empr_custom_lists add index empr_custom_champ");
		$rqt = "ALTER TABLE empr_custom_lists drop INDEX champ_list_value " ;
		echo traite_rqt($rqt,"alter table empr_custom_lists drop index champ_list_value");
		$rqt = "ALTER TABLE empr_custom_lists add INDEX champ_list_value ( empr_custom_champ, empr_custom_list_value )  " ;
		echo traite_rqt($rqt,"alter table empr_custom_lists add index champ_list_value");
		$rqt = "ALTER TABLE empr_custom_values drop INDEX empr_custom_champ " ;
		echo traite_rqt($rqt,"alter table empr_custom_values drop index empr_custom_champ");
		$rqt = "ALTER TABLE empr_custom_values add INDEX empr_custom_champ ( empr_custom_champ )  " ;
		echo traite_rqt($rqt,"alter table empr_custom_values add index empr_custom_champ");
		$rqt = "ALTER TABLE empr_custom_values drop INDEX empr_custom_origine " ;
		echo traite_rqt($rqt,"alter table empr_custom_values drop index empr_custom_origine");
		$rqt = "ALTER TABLE empr_custom_values add INDEX empr_custom_origine ( empr_custom_origine )  " ;
		echo traite_rqt($rqt,"alter table empr_custom_values add index empr_custom_origine");
		$rqt = "ALTER TABLE empr_custom_values drop INDEX champ_origine " ;
		echo traite_rqt($rqt,"alter table empr_custom_values drop index champ_origine");
		$rqt = "ALTER TABLE empr_custom_values add INDEX champ_origine ( empr_custom_champ, empr_custom_origine ) " ;
		echo traite_rqt($rqt,"alter table empr_custom_values add index champ_origine");
		
		$rqt = "ALTER TABLE sessions DROP hash " ;
		echo traite_rqt($rqt,$rqt);
		$rqt = "ALTER TABLE sessions DROP id " ;
		echo traite_rqt($rqt,$rqt);
		
		$rqt = "ALTER TABLE categories DROP INDEX categ_libelle " ;
		echo traite_rqt($rqt,$rqt);
		$rqt = "ALTER TABLE categories CHANGE categ_libelle categ_libelle TEXT NOT NULL default '' " ;
		echo traite_rqt($rqt,"ALTER TABLE categories CHANGE categ_libelle TEXT ");
		$rqt = "ALTER TABLE categories ADD FULLTEXT categ_libelle (categ_libelle) " ;
		echo traite_rqt($rqt,"ALTER TABLE categories add index categ_libelle ");
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='logo' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'logo', 'logo_default.jpg', 'Nom du fichier de l\'image logo') " ;
			echo traite_rqt($rqt,"insert opac_logo= logo_default.jpg into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='logosmall' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'logosmall', 'logo_default_small.jpg', 'Nom du fichier de l\'image petit logo') " ;
			echo traite_rqt($rqt,"insert opac_logosmall= logo_default_small.jpg into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_bandeaugauche' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'show_bandeaugauche', '1', 'Affichage du bandeau de gauche ? \n 0 : Non\n 1 : Oui') " ;
			echo traite_rqt($rqt,"insert opac_show_bandeaugauche=1 into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_liensbas' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'show_liensbas', '1', 'Affichage des liens(pmb, google, bibli) en bas de page ? \n 0 : Non\n 1 : Oui') " ;
			echo traite_rqt($rqt,"insert opac_show_liensbas=1 into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_homeontop' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'show_homeontop', '1', 'Affichage du lien HOME (retour accueil) sous le nom de la bibliothèque (nécessaire si masquage bandeau gauche) ? \n 0 : Non\n 1 : Oui') " ;
			echo traite_rqt($rqt,"insert opac_show_homeontop=0 into parametres");
			}
			
		$rqt = "alter table caddie change name name varchar(255) " ;
		echo traite_rqt($rqt,"ALTER TABLE caddie CHANGE name VARCHAR(255) ");
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='resa_quota_pret_depasse' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'resa_quota_pret_depasse', '1', 'Réservation possible même si quota de prêt dépassé ? \n 0 : Non\n 1 : Oui') " ;
			echo traite_rqt($rqt,"insert pmb_resa_quota_pret_depasse=1 into parametres");
			}
		// passage en paramètres des limites d'import
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='import_limit_read_file' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'import_limit_read_file', '100', 'Limite de taille de lecture du fichier en import, en général 100 ou 200 doit fonctionner, si problème de time out : fixer plus bas, 50 par exemple.') " ;
			echo traite_rqt($rqt,"insert pmb_import_limit_read_file=100 into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='import_limit_record_load' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'import_limit_record_load', '100', 'Limite de taille de traitement de notices en import, en général 100 ou 200 doit fonctionner, si problème de time out : fixer plus bas, 50 par exemple.') " ;
			echo traite_rqt($rqt,"insert pmb_import_limit_record_load=100 into parametres");
			}
			
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_dernieresnotices' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'show_dernieresnotices', '0', 'Affichage des dernières notices créées en bas de page ? \n 0 : Non\n 1 : Oui') " ;
			echo traite_rqt($rqt,"insert opac_show_dernieresnotices=0 into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_etageresaccueil' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'show_etageresaccueil', '1', 'Affichage des étagères dans la page d\'accueil en bas de page ? \n 0 : Non\n 1 : Oui') " ;
			echo traite_rqt($rqt,"insert opac_show_etageresaccueil=1 into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='biblio_preamble_p1' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'biblio_preamble_p1', '', 'Paragraphe 1 d\'informations sur la bibliothèque (par exemple, description du fonds)')";
			echo traite_rqt($rqt,"insert opac_biblio_preamble_p1='some text' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='biblio_preamble_p2' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'biblio_preamble_p2', '', 'Paragraphe 2 d\'informations sur la bibliothèque : accueil du public.')";
			echo traite_rqt($rqt,"insert opac_biblio_preamble_p2='some text' into parametres");
			}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='biblio_important_p1' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'biblio_important_p1', '', 'Infos importantes 1 sur la bibliothèque, est masqué par défaut dans la feuille de style, voir id important.p1')";
			echo traite_rqt($rqt,"insert opac_biblio_important_p1='some text' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='biblio_important_p2' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'biblio_important_p2', '', 'Infos importantes sur la bibliothèque, est masqué par défaut dans la feuille de style, voir id important.p2')";
			echo traite_rqt($rqt,"insert opac_biblio_important_p2='some text' into parametres");
			}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v2.08");
		break;	
	
	case "v2.08":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='biblio_name' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'biblio_name', 'Nom de la bibliothèque', 'Nom de la bibliothèque dans l\'opac')";
			echo traite_rqt($rqt,"insert opac_biblio_name='Nom de la bibliothèque' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='biblio_website' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'biblio_website', 'www.sigb.net', 'Site web de la bibliothèque dans l\'opac')";
			echo traite_rqt($rqt,"insert opac_biblio_website='www.sigb.net' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='biblio_adr1' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'biblio_adr1', 'rue...', 'Adresse 1 de la bibliothèque dans l\'opac')";
			echo traite_rqt($rqt,"insert opac_biblio_adr1='rue...' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='biblio_town' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'biblio_town', 'VILLE', 'Ville de la bibliothèque dans l\'opac')";
			echo traite_rqt($rqt,"insert opac_biblio_town='VILLE' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='biblio_cp' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'biblio_cp', '37000', 'Code postal de la bibliothèque dans l\'opac')";
			echo traite_rqt($rqt,"insert opac_biblio_cp='37000' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='biblio_country' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'biblio_country', 'France', 'Pays de la bibliothèque dans l\'opac')";
			echo traite_rqt($rqt,"insert opac_biblio_country='France' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='biblio_phone' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'biblio_phone', '02 47 24 89 29', 'Téléphone de la bibliothèque dans l\'opac')";
			echo traite_rqt($rqt,"insert opac_biblio_phone='02 47 24 89 29' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='biblio_dep' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'biblio_dep', '37', 'Département de la bibliothèque dans l\'opac pour la météo')";
			echo traite_rqt($rqt,"insert opac_biblio_dep='37' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='biblio_email' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'biblio_email', 'pmb@sigb.net', 'Email de la bibliothèque dans l\'opac')";
			echo traite_rqt($rqt,"insert opac_biblio_email='pmb@sigb.net' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='etagere_notices_order' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'etagere_notices_order', ' index_serie, tit1 ', 'Ordre d\'affichage des notices dans les étagères dans l\'opac \n  index_serie, tit1 : tri par titre de série et titre \n rand()  : aléatoire ')";
			echo traite_rqt($rqt,"insert opac_etagere_notices_order=' index_serie, tit1 ' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='etagere_notices_format' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'etagere_notices_format', '8', 'Format d\'affichage des notices dans les étagères de l\'écran d\'accueil \n 1 : ISBD seul \n 2 : Public seul \n 4 : ISBD et Public \n 8 : Réduit (titre+auteurs) seul')";
			echo traite_rqt($rqt,"insert opac_etagere_notices_format='8' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='etagere_notices_depliables' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'etagere_notices_depliables', '1', 'Affichage dépliable des notices dans les étagères de l\'écran d\'accueil \n 0 : Non \n 1 : Oui')";
			echo traite_rqt($rqt,"insert opac_etagere_notices_depliables='1' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='etagere_nbnotices_accueil' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'etagere_nbnotices_accueil', '5', 'Nombre de notices affichées dans les étagères de l\'écran d\'accueil \n 0 : Toutes \n -1 : Aucune \n x : x notices affichées au maximum')";
			echo traite_rqt($rqt,"insert opac_etagere_nbnotices_accueil='0' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='nb_aut_rec_per_page' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'nb_aut_rec_per_page', '15', 'Nombre de notices affichées pour une autorité donnée')";
			echo traite_rqt($rqt,"insert opac_nb_aut_rec_per_page='15' into parametres");
			}		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='term_search_n_per_page' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'term_search_n_per_page', '200', 'Nombre de termes affichées par page en recherche par terme')";
			echo traite_rqt($rqt,"insert opac_term_search_n_per_page='200' into parametres");
			}		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_empty_categ' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'show_empty_categ', '0', 'En recherche par terme, affichage des catégories ne contenant aucun ouvrage :\n 0 : Non \n 1 : Oui')";
			echo traite_rqt($rqt,"insert opac_show_empty_categ='0' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='allow_extended_search' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'allow_extended_search', '1', 'Autorisation ou non de la recherche avancée dans l\'OPAC \n 0 : Non \n 1 : Oui')";
			echo traite_rqt($rqt,"insert opac_allow_extended_search='1' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='allow_term_search' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'allow_term_search', '1', 'Autorisation ou non de la recherche par termes dans l\'OPAC \n 0 : Non \n 1 : Oui')";
			echo traite_rqt($rqt,"insert opac_allow_term_search='1' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='term_search_height' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'term_search_height', '200', 'Hauteur en pixels de la frame de recherche par termes (si pas précisé ou zéro : par défaut 200 pixels)')";
			echo traite_rqt($rqt,"insert opac_term_search_height='200' into parametres");
			}

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v2.09");
		break;	
	
	case "v2.09":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		
		$rqt = "alter table authors change author_name author_name varchar(255)";
		echo traite_rqt($rqt,"Author_name varchar 255 ");
		
		$rqt = "ALTER TABLE authors DROP INDEX author";
		echo traite_rqt($rqt,"drop index author");
		$rqt = "ALTER TABLE authors drop INDEX author_name ";
		echo traite_rqt($rqt,"drop index author_name");
		$rqt = "ALTER TABLE authors drop INDEX author_rejete ";
		echo traite_rqt($rqt,"drop index author_rejete");

		$rqt = "ALTER TABLE authors ADD INDEX author_name ( author_name ) ";
		echo traite_rqt($rqt,"add index author_name");
		$rqt = "ALTER TABLE authors ADD INDEX author_rejete ( author_rejete ) ";
		echo traite_rqt($rqt,"add index author_rejete");
		
		$rqt = "alter table authors change author_rejete author_rejete varchar(255)";
		echo traite_rqt($rqt,"author_rejete varchar 255 ");
		
		$rqt = "ALTER TABLE collections CHANGE collection_name collection_name VARCHAR( 255 ) NOT NULL ";
		echo traite_rqt($rqt,"coll_name varchar 255");
		$rqt = "ALTER TABLE publishers CHANGE ed_name ed_name VARCHAR( 255 ) NOT NULL ";
		echo traite_rqt($rqt,"ed_name varchar 255");
		$rqt = "ALTER TABLE publishers CHANGE ed_adr1 ed_adr1 VARCHAR( 255 ) NOT NULL ";
		echo traite_rqt($rqt,"ed_adr1 varchar 255");
		$rqt = "ALTER TABLE publishers CHANGE ed_adr2 ed_adr2 VARCHAR( 255 ) NOT NULL ";
		echo traite_rqt($rqt,"ed_adr2 varchar 255");
		$rqt = "ALTER TABLE publishers CHANGE ed_web ed_web VARCHAR( 255 ) NOT NULL ";
		echo traite_rqt($rqt,"ed_web varchar 255");
		$rqt = "ALTER TABLE sub_collections CHANGE sub_coll_name sub_coll_name VARCHAR( 255 ) NOT NULL ";
		echo traite_rqt($rqt,"subcoll_name varchar 255");

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v2.10");
		break;	
	
	case "v2.10":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+

		$rqt = "ALTER TABLE authors ADD index_author TEXT ";
		echo traite_rqt($rqt,"index_author");
		$rqt_maj = "select author_id as id, concat(author_name,' ',author_rejete) as auteur from authors " ;
		$res_maj = mysql_query($rqt_maj) ; 
		while ($obj=mysql_fetch_object($res_maj)) {
			$rqt_maj = "update authors set index_author = '".strip_empty_words($obj->auteur)."' where author_id=".$obj->id ;
			@mysql_query($rqt_maj);
			}
		echo "<tr><td><font size='1'>MAJ index_author</font></td><td><font size='1'>OK</font></td></tr>";
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v2.11");
		break;	
	
	case "v2.11":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "ALTER TABLE collections ADD index_coll TEXT ";
		echo traite_rqt($rqt,"index_collection");
		$rqt_maj = "select collection_id as id, collection_name as coll from collections " ;
		$res_maj = mysql_query($rqt_maj) ; 
		while ($obj=mysql_fetch_object($res_maj)) {
			$rqt_maj = "update collections set index_coll = '".strip_empty_words($obj->coll)."' where collection_id=".$obj->id ;
			@mysql_query($rqt_maj);
			}
		echo "<tr><td><font size='1'>MAJ index_collections</font></td><td><font size='1'>OK</font></td></tr>";
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v2.12");
		break;	
	
	case "v2.12":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "ALTER TABLE sub_collections ADD index_sub_coll TEXT ";
		echo traite_rqt($rqt,"index_sub_collection");
		$rqt_maj = "select sub_coll_id as id, sub_coll_name as sub_coll from sub_collections " ;
		$res_maj = mysql_query($rqt_maj) ; 
		while ($obj=mysql_fetch_object($res_maj)) {
			$rqt_maj = "update sub_collections set index_sub_coll = '".strip_empty_words($obj->sub_coll)."' where sub_coll_id=".$obj->id ;
			@mysql_query($rqt_maj);
			}
		echo "<tr><td><font size='1'>MAJ index_sub_collections</font></td><td><font size='1'>OK</font></td></tr>";
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v2.13");
		break;	
	
	case "v2.13":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "ALTER TABLE publishers ADD index_publisher TEXT ";
		echo traite_rqt($rqt,"index_publisher");
		$rqt_maj = "select ed_id as id, ed_name as publisher from publishers " ;
		$res_maj = mysql_query($rqt_maj) ; 
		while ($obj=mysql_fetch_object($res_maj)) {
			$rqt_maj = "update publishers set index_publisher = '".strip_empty_words($obj->publisher)."' where ed_id=".$obj->id ;
			@mysql_query($rqt_maj);
			}
		echo "<tr><td><font size='1'>MAJ index_publisher</font></td><td><font size='1'>OK</font></td></tr>";
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v2.14");
		break;	
	
	case "v2.14":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+

		$rqt = "ALTER TABLE categories ADD index_categorie TEXT ";
		echo traite_rqt($rqt,"index_categorie");
		$rqt_maj = "select categ_id as id, categ_libelle as categorie from categories " ;
		$res_maj = mysql_query($rqt_maj) ; 
		while ($obj=mysql_fetch_object($res_maj)) {
			$rqt_maj = "update categories set index_categorie = '".strip_empty_words($obj->categorie)."' where categ_id=".$obj->id ;
			@mysql_query($rqt_maj);
			}
		echo "<tr><td><font size='1'>MAJ index_categorie</font></td><td><font size='1'>OK</font></td></tr>";
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v2.15");
		break;	
	
	case "v2.15":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "ALTER TABLE indexint ADD index_indexint TEXT ";
		echo traite_rqt($rqt,"index_indexint");
		$rqt_maj = "select indexint_id as id, concat(indexint_name,' ',indexint_comment) as dewey from indexint " ;
		$res_maj = mysql_query($rqt_maj) ; 
		while ($obj=mysql_fetch_object($res_maj)) {
			$rqt_maj = "update indexint set index_indexint = '".strip_empty_words($obj->dewey)."' where indexint_id=".$obj->id ;
			@mysql_query($rqt_maj);
			}
		echo "<tr><td><font size='1'>MAJ index_int</font></td><td><font size='1'>OK</font></td></tr>";
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v2.16");
		break;	
	
	case "v2.16":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "ALTER TABLE notices ADD index_n_gen TEXT ";
		echo traite_rqt($rqt,"index_n_gen");
		$rqt_maj = "select notice_id as id, n_gen from notices where n_gen is not null or n_gen<>'' " ;
		$res_maj = mysql_query($rqt_maj) ; 
		while ($obj=mysql_fetch_object($res_maj)) {
			$rqt_maj = "update notices set index_n_gen = '".strip_empty_words($obj->n_gen)."' where notice_id=".$obj->id ;
			@mysql_query($rqt_maj);
			}
		echo "<tr><td><font size='1'>MAJ index_n_gen</font></td><td><font size='1'>OK</font></td></tr>";
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v2.17");
		break;	
	
	case "v2.17":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "ALTER TABLE notices ADD index_n_contenu TEXT ";
		echo traite_rqt($rqt,"index_n_contenu");
		$rqt_maj = "select notice_id as id, n_contenu from notices where n_contenu is not null or n_contenu<>'' " ;
		$res_maj = mysql_query($rqt_maj) ; 
		while ($obj=mysql_fetch_object($res_maj)) {
			$rqt_maj = "update notices set index_n_contenu = '".strip_empty_words($obj->n_contenu)."' where notice_id=".$obj->id ;
			@mysql_query($rqt_maj);
			}
		echo "<tr><td><font size='1'>MAJ index_n_contenu</font></td><td><font size='1'>OK</font></td></tr>";
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v2.18");
		break;	
	
	case "v2.18":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		$rqt = "ALTER TABLE notices ADD index_n_resume TEXT ";
		echo traite_rqt($rqt,"index_n_resume");
		$rqt_maj = "select notice_id as id, n_resume from notices where n_resume is not null or n_resume<>'' " ;
		$res_maj = mysql_query($rqt_maj) ; 
		while ($obj=mysql_fetch_object($res_maj)) {
			$rqt_maj = "update notices set index_n_resume = '".strip_empty_words($obj->n_resume)."' where notice_id=".$obj->id ;
			@mysql_query($rqt_maj);
			}
		echo "<tr><td><font size='1'>MAJ index_n_resume</font></td><td><font size='1'>OK</font></td></tr>";
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v2.19");
		break;	
	
	case "v2.19":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='notices_format' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'notices_format', '4', 'Format d\'affichage des notices en résultat de recherche \n 1 : ISBD seul \n 2 : Public seul \n 4 : ISBD et Public \n 8 : Réduit (titre+auteurs) seul')";
			echo traite_rqt($rqt,"insert opac_notices_format='4' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='notices_depliable' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'notices_depliable', '1', 'Affichage dépliable des notices en résultat de recherche \n 0 : Non \n 1 : Oui')";
			echo traite_rqt($rqt,"insert opac_notices_depliable='1' into parametres");
			}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v2.20");
		break;	
	
	case "v2.20":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +--------------------------------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='categories_nb_col_subcat' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'categories_nb_col_subcat', '3', 'Nombre de colonnes de sous-catégories en navigation dans les catégories \n 3 par défaut')";
			echo traite_rqt($rqt,"insert opac_categories_nb_col_subcat='3' into parametres");
			}
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v2.21");
		break;	
	
	case "v2.21":
		echo "<table ><tr><th>Action</th><th>Resultat</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE resa ADD resa_date_debut DATE DEFAULT '0000-00-00' NOT NULL AFTER resa_date" ;
		echo traite_rqt($rqt,"TABLE resa ADD resa_date_debut");
		$rqt = "ALTER TABLE quotas CHANGE constraint_type constraint_type VARCHAR( 255 ) NOT NULL" ;
		echo traite_rqt($rqt,"TABLE quotas constraint_type varchar 255"); 
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='max_resa' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'max_resa', '5', 'Nombre maximum de réservation sur un document \n 5 par défaut \n 0 pour illimité')";
			echo traite_rqt($rqt,"insert opac_max_resa='5' into parametres");
			}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v2.22");
		break;	
	
	case "v2.22":
		echo "<table ><tr><th>Action</th><th>Resultat</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE users ADD param_licence INT( 1 ) UNSIGNED DEFAULT 0 NOT NULL AFTER param_sounds ";
		echo traite_rqt($rqt,"TABLE users add licence "); 
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v2.23");
		break;	
	
	case "v2.23":
		echo "<table ><tr><th>Action</th><th>Resultat</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE pret CHANGE pret_date pret_date DATETIME DEFAULT '0000-00-00' NOT NULL ";
		echo traite_rqt($rqt,"TABLE pret change pret_date DATETIME ");
		$rqt = "ALTER TABLE pret_archive CHANGE arc_debut arc_debut DATETIME DEFAULT '0000-00-00' ";
		echo traite_rqt($rqt,"TABLE pret_archive change arc_debut DATETIME ");  
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v2.24");
		break;	
	
	case "v2.24":
		echo "<table ><tr><th>Action</th><th>Resultat</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE import_marc CHANGE origine origine varchar(50) DEFAULT '' ";
		echo traite_rqt($rqt,"TABLE import_marc change origine varchar(50) ");  
		
		$rqt = "ALTER TABLE import_marc ADD no_notice integer(10) UNSIGNED DEFAULT 0 ";
		echo traite_rqt($rqt,"TABLE import_marc ADD no_notice ");  
		
		// +-------------------------------------------------+
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.00");
		break;

	default:
		include("$include_path/messages/help/$lang/alter.txt");
		break;
	}

 
