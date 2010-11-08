<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: alter_v3.inc.php,v 1.125 2009-05-16 11:11:52 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

settype ($action,"string");

switch ($action) {
	case "lancement":
		switch ($version_pmb_bdd) {
			case "v2.24":
				$maj_a_faire = "v3.00";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.00":
				$maj_a_faire = "v3.01";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.01":
				$maj_a_faire = "v3.02";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.02":
				$maj_a_faire = "v3.03";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.03":
				$maj_a_faire = "v3.04";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.04":
				$maj_a_faire = "v3.05";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.05":
				$maj_a_faire = "v3.06";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.06":
				$maj_a_faire = "v3.07";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.07":
				$maj_a_faire = "v3.08";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.08":
				$maj_a_faire = "v3.09";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.09":
				$maj_a_faire = "v3.10";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.10":
				$maj_a_faire = "v3.11";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.11":
				$maj_a_faire = "v3.12";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.12":
				$maj_a_faire = "v3.13";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.13":
				$maj_a_faire = "v3.14";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.14":
				$maj_a_faire = "v3.15";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.15":
				$maj_a_faire = "v3.16";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.16":
				$maj_a_faire = "v3.17";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.17":
				$maj_a_faire = "v3.18";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.18":
				$maj_a_faire = "v3.19";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.19":
				$maj_a_faire = "v3.20";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.20":
				$maj_a_faire = "v3.21";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.21":
				$maj_a_faire = "v3.22";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.22":
				$maj_a_faire = "v3.23";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.23":
				$maj_a_faire = "v3.24";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.24":
				$maj_a_faire = "v3.25";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.25":
				$maj_a_faire = "v3.26";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.26":
				$maj_a_faire = "v3.27";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.27":
				$maj_a_faire = "v3.28";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.28":
				$maj_a_faire = "v3.29";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.29":
				$maj_a_faire = "v3.30";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.30":
				$maj_a_faire = "v3.31";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.31":
				$maj_a_faire = "v3.32";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.32":
				$maj_a_faire = "v3.33";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.33":
				$maj_a_faire = "v3.34";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.34":
				$maj_a_faire = "v3.35";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.35":
				$maj_a_faire = "v3.36";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.36":
				$maj_a_faire = "v3.37";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.37":
				$maj_a_faire = "v3.38";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.38":
				$maj_a_faire = "v3.39";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.39":
				$maj_a_faire = "v3.40";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.40":
				$maj_a_faire = "v3.41";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.41":
				$maj_a_faire = "v3.42";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.42":
				$maj_a_faire = "v3.43";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.43":
				$maj_a_faire = "v3.44";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.44":
				$maj_a_faire = "v3.45";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.45":
				$maj_a_faire = "v3.46";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.46":
				$maj_a_faire = "v3.47";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.47":
				$maj_a_faire = "v3.48";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.48":
				$maj_a_faire = "v3.49";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v3.49":
			case "v3.50":
			case "v3.51":
				$maj_a_faire = "v4.00";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			default:
				echo "<strong><font color='#FF0000'>".$msg[1806].$version_pmb_bdd." !</font></strong><br />";
				break;
			}
		break;	
	
	case "v3.00":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = " ALTER TABLE bulletins ADD bulletin_titre TEXT, ADD index_titre TEXT  " ;
		echo traite_rqt($rqt,"TABLE BULLETIN ADD bulletin_titre & index_titre") ;
		$rqt = " ALTER TABLE bulletins drop index i_index_titre " ;
		echo traite_rqt($rqt,"drop index i_index_titre");
		$rqt = " ALTER TABLE bulletins ADD FULLTEXT KEY i_index_titre (index_titre)  " ;
		echo traite_rqt($rqt,"FULL TEXT KEY on bulletins.index_titre");
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.01");
		break;	
	
	case "v3.01":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = " ALTER TABLE bulletins ADD bulletin_cb varchar(30) " ;
		echo traite_rqt($rqt,"TABLE BULLETIN ADD bulletin_cb ") ;

		// reprise de la table notices
		$rqt = " ALTER TABLE notices DROP INDEX notice_id " ;
		echo traite_rqt($rqt,"TABLE notices DROP index notice_id ") ;
		$rqt = " ALTER TABLE notices ADD index_sew TEXT, ADD index_wew TEXT " ;
		echo traite_rqt($rqt,"ALTER notices add index_sew & index_wew TEXT ") ;

		// nouveaux index fulltext
		$rqt = " alter table notices drop index index_wew " ;
		echo traite_rqt($rqt,"ALTER notices DROP FULLTEXT INDEX index_wew ") ;

		// nettoyage des champs superflus
		$rqt = " alter table notices drop index_tit1 " ;
		echo traite_rqt($rqt,"ALTER notices DROP index_tit1 ") ;
		$rqt = " alter table notices drop index_tit2 " ;
		echo traite_rqt($rqt,"ALTER notices DROP index_tit2 ") ;
		$rqt = " alter table notices drop index_tit3 " ;
		echo traite_rqt($rqt,"ALTER notices DROP index_tit3 ") ;
		$rqt = " alter table notices drop index_tit4 " ;
		echo traite_rqt($rqt,"ALTER notices DROP index_tit4 ") ;

		// suppression du fulltext index_serie
		$rqt = " alter table notices drop index index_serie " ;
		echo traite_rqt($rqt,"ALTER notices DROP FULLTEXT index_serie ") ;

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.02");
		break;	
	
	case "v3.02":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='show_help' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'show_help', '1', 'Affichage de l\'aide en ligne dans PMB en partie gestion \n 1 Oui \n 0 Non')";
			echo traite_rqt($rqt,"insert pmb_show_help='1' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_help' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'show_help', '1', 'Affichage de l\'aide en ligne dans l\'OPAC de PMB  \n 1 Oui \n 0 Non')";
			echo traite_rqt($rqt,"insert opac_show_help='1' into parametres");
			}
		
		// Passage en TEXT
		$rqt = " alter table series change serie_index serie_index TEXT " ;
		echo traite_rqt($rqt,"ALTER series serie_index TEXT ") ;

		// Info de réindexation
		$rqt = " select 1 " ;
		echo traite_rqt($rqt,"<b><u>VOUS DEVEZ REINDEXER / YOU MUST REINDEX : Admin > Outils > Nettoyage de base</u></b> ") ;
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.03");
		break;	
	
	case "v3.03":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='cart_allow' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'cart_allow', '1', 'Paniers possibles dans l\'OPAC de PMB  \n 1 Oui \n 0 Non')";
			echo traite_rqt($rqt,"insert opac_cart_allow='1' into parametres");
			}
		$rqt = "update parametres set valeur_param='index_serie, tnvol, index_sew' where type_param='opac' and sstype_param='categories_categ_sort_records' " ;
		echo traite_rqt($rqt,"Param opac_categories_categ_sort_records = index_serie, tnvol, index_sew");
		$rqt = "update parametres set valeur_param='index_serie, tnvol, index_sew' where type_param='opac' and sstype_param='authors_aut_sort_records' " ;
		echo traite_rqt($rqt,"Param opac_authors_aut_sort_records = index_serie, tnvol, index_sew");
		$rqt = "update parametres set valeur_param='index_serie, tnvol, index_sew' where type_param='opac' and sstype_param='etagere_notices_order' " ;
		echo traite_rqt($rqt,"Param opac_etagere_notices_order = index_serie, tnvol, index_sew");
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='max_cart_items' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'max_cart_items', '200', 'Nombre maximum de notices dans un panier utilisateur.')";
			echo traite_rqt($rqt,"insert opac_max_cart_items='200' into parametres");
			}

		$rqt = "CREATE TABLE opac_sessions (empr_id int(10) unsigned NOT NULL default '0', session blob, date_rec timestamp(14) NOT NULL, PRIMARY KEY  (empr_id)) " ;
		echo traite_rqt($rqt,"create table opac_sessions ");
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.04");
		break;	
	
	case "v3.04":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_section_browser' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'show_section_browser', '1', 'Afficher le butineur de localisation et de sections ?\n 0 : Non\n 1 : Oui')";
			echo traite_rqt($rqt,"insert opac_show_section_browser='1' into parametres");
			}
		$rqt = " ALTER TABLE docs_location ADD location_pic varchar(255) not null default '', ADD location_visible_opac tinyint(1) NOT NULL default 1 " ;
		echo traite_rqt($rqt,"TABLE docs_loaction ADD location_pic, visible_opac") ;

		$rqt = " ALTER TABLE docs_section ADD section_pic varchar(255) not null default '', ADD section_visible_opac tinyint(1) NOT NULL default '1' " ;
		echo traite_rqt($rqt,"TABLE docs_section ADD section_pic, visible_opac") ;

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.05");
		break;	
	
	case "v3.05":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='nb_localisations_per_line' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'nb_localisations_per_line', '6', 'Nombre de localisations affichées par ligne en page d\'accueil (si show_section_browser=1)')";
			echo traite_rqt($rqt,"insert opac_nb_localisations_per_line='6' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='nb_sections_per_line' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'nb_sections_per_line', '6', 'Nombre de sections affichées par ligne en visualisation de localisation (si show_section_browser=1)')";
			echo traite_rqt($rqt,"insert opac_nb_sections_per_line='6' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='cart_only_for_subscriber' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'opac', 'cart_only_for_subscriber', '1', 'Paniers de notices réservés aux adhérents de la bibliothèque ?\n 1: Oui\n 0: Non, autorisé pour tout internaute')";
			echo traite_rqt($rqt,"insert opac_cart_only_for_subscriber='1' into parametres");
			}
		$rqt = "ALTER TABLE parametres ADD section_param varchar(255) not null default ''" ;
		echo traite_rqt($rqt,"TABLE parametres ADD section_param") ;

		$rqt = "update parametres set section_param='a_general'	where type_param='opac' and sstype_param in ('default_lang','default_style','duration_session_auth','max_resa','resa','resa_dispo')" ;
		echo traite_rqt($rqt,"UPDATE parametres SET section_param -1") ;
		
		$rqt = "update parametres set section_param='b_aff_general' where type_param='opac' and sstype_param in ('biblio_adr1','biblio_country','biblio_cp','biblio_dep','biblio_email','biblio_important_p1','biblio_important_p2','biblio_name','biblio_phone','biblio_preamble_p1','biblio_preamble_p2','biblio_town','biblio_website','logosmall')";
		echo traite_rqt($rqt,"UPDATE parametres SET section_param -2") ;
		
		$rqt = "update parametres set section_param='c_recherche' where type_param='opac' and sstype_param in ('allow_extended_search','allow_term_search','modules_search_abstract','modules_search_author','modules_search_category','modules_search_collection','modules_search_content','modules_search_indexint','modules_search_keywords','modules_search_publisher','modules_search_subcollection','modules_search_title','term_search_height','term_search_n_per_page')";
		echo traite_rqt($rqt,"UPDATE parametres SET section_param -3") ;

		$rqt = "update parametres set section_param='d_aff_recherche' where type_param='opac' and sstype_param in ('authors_aut_rec_per_page','authors_aut_sort_records','nb_aut_rec_per_page','search_results_per_page')" ;
		echo traite_rqt($rqt,"UPDATE parametres SET section_param -4") ;

		$rqt = "update parametres set section_param='e_aff_notice' where type_param='opac' and sstype_param in ('notices_depliable','notices_format','show_book_pics','show_exemplaires')" ;
		echo traite_rqt($rqt,"UPDATE parametres SET section_param -5") ;
		
		$rqt = "update parametres set section_param='f_modules' where type_param='opac' and sstype_param in ('cart_allow','show_100cases_browser','show_bandeaugauche','show_categ_browser','show_dernieresnotices','show_etageresaccueil','show_help','show_homeontop','show_liensbas','show_loginform','show_marguerite_browser','show_meteo','show_section_browser')" ; 
		echo traite_rqt($rqt,"UPDATE parametres SET section_param -6") ;
		
		$rqt = "update parametres set section_param='h_cart' where type_param='opac' and sstype_param in ('cart_only_for_subscriber','max_cart_items')" ; 
		echo traite_rqt($rqt,"UPDATE parametres SET section_param -7") ;

		$rqt = "update parametres set section_param='i_categories' where type_param='opac' and sstype_param in ('categories_categ_path_sep','categories_categ_rec_per_page','categories_categ_sort_records','categories_columns','categories_nb_col_subcat','categories_sub_display','categories_sub_mode','show_empty_categ')";
		echo traite_rqt($rqt,"UPDATE parametres SET section_param -8") ;

		$rqt = "update parametres set section_param='j_etagere' where type_param='opac' and sstype_param in ('etagere_nbnotices_accueil','etagere_notices_depliables','etagere_notices_format','etagere_notices_order')" ;
		echo traite_rqt($rqt,"UPDATE parametres SET section_param -9") ;

		$rqt = "update parametres set section_param='k_section'where type_param='opac' and sstype_param in ('nb_localisations_per_line','nb_sections_per_line')";
		echo traite_rqt($rqt,"UPDATE parametres SET section_param -10") ;

		$rqt = "update parametres set section_param='z_unused' where type_param='opac' and sstype_param in ('logo','search_results_first_level','biblio_quicksummary_p1','biblio_quicksummary_p2')";
		echo traite_rqt($rqt,"UPDATE parametres SET section_param -11") ;
		
		$rqt = "update parametres set section_param='z_unused' where type_param='opac' and (section_param = '' or section_param is null ) ";
		echo traite_rqt($rqt,"UPDATE parametres SET section_param -12") ;
		
		$rqt = "alter table parametres drop index type_param ";
		echo traite_rqt($rqt,"ALTER parametres DROP index type_param") ;
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.06");
		break;	
	
	case "v3.06":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "CREATE TABLE admin_session (userid int(10) unsigned NOT NULL default '0', session blob,  PRIMARY KEY  (userid))";
		echo traite_rqt($rqt,"CREATE TABLE admin_sessions") ;
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.07");
		break;	
	
	case "v3.07":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE exemplaires CHANGE expl_cote expl_cote VARCHAR( 50 ) NOT NULL default '' ";
		echo traite_rqt($rqt,"ALTER exemplaires expl_cote varchar(50)") ;
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.08");
		break;	
	
	case "v3.08":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='notice_reduit_format' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'opac', 'notice_reduit_format', '0', 'Format d\'affichage des réduits des notices :\n 0 normal = titre+auteurs principaux\n P 1,2,3: Perso. : tit+aut+champs persos id 1 2 3\n E 1,2,3: Perso. : tit+aut+édit+champs persos id 1 2 3','e_aff_notice')";
			echo traite_rqt($rqt,"insert opac_notice_reduit_format='0' into parametres");
			}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.09");
		break;	
	
	case "v3.09":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreresa' and sstype_param='before_list' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('pdflettreresa', 'before_list', 'Suite à votre demande de réservation, nous vous informons que le ou les ouvrages ci-dessous sont à votre disposition à la bibliothèque.', 'Texte apparaissant avant la liste des ouvrages en résa dans le courrier de confirmation de résa') " ;
			echo traite_rqt($rqt,"insert pdflettreresa,before_list into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreresa' and sstype_param='after_list' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('pdflettreresa', 'after_list', 'Passé le délai de réservation, ces ouvrages seront remis en circulation, vous priant de les retirer dans les meilleurs délais.', 'Texte apparaissant après la liste des ouvrages') " ;
			echo traite_rqt($rqt,"insert pdflettreresa,after_list into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreresa' and sstype_param='fdp' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('pdflettreresa', 'fdp', 'Le responsable de la \$biblio_name.', 'Signataire de la lettre, utiliser \$biblio_name pour reprendre le paramètre \"biblio name\" ou bien mettre autre chose.') " ;
			echo traite_rqt($rqt,"insert pdflettreresa,fdp into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreresa' and sstype_param='madame_monsieur' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('pdflettreresa', 'madame_monsieur', 'Madame, Monsieur,', 'Entête de la lettre') " ;
			echo traite_rqt($rqt,"insert pdflettreresa,madame_monsieur into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreresa' and sstype_param='nb_par_page' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('pdflettreresa', 'nb_par_page', '7', 'Nombre d\'ouvrages réservés imprimés sur les pages suivantes.') " ;
			echo traite_rqt($rqt,"insert pdflettreresa,nb_par_page into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreresa' and sstype_param='nb_1ere_page' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('pdflettreresa', 'nb_1ere_page', '4', 'Nombre d\'ouvrages réservés imprimés sur la première page') " ;
			echo traite_rqt($rqt,"insert pdflettreresa,nb_1ere_page into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreresa' and sstype_param='taille_bloc_expl' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('pdflettreresa', 'taille_bloc_expl', '16', 'Taille d\'un bloc (2 lignes) d\'ouvrage en réservation. Le début de chaque ouvrage en résa sera espacé de cette valeur sur la page') " ;
			echo traite_rqt($rqt,"insert pdflettreresa,taille_bloc_expl into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreresa' and sstype_param='debut_expl_1er_page' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('pdflettreresa', 'debut_expl_1er_page', '160', 'Début de la liste des ouvrages sur la première page, en mm depuis le bord supérieur de la page. Doit être règlé en fonction du texte qui précède la liste des ouvrages, lequel peut être plus ou moins long.') " ;
			echo traite_rqt($rqt,"insert pdflettreresa,debut_expl_1er_page into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreresa' and sstype_param='debut_expl_page' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('pdflettreresa', 'debut_expl_page', '15', 'Début de la liste des ouvrages sur les pages suivantes, en mm depuis le bord supérieur de la page.') " ;
			echo traite_rqt($rqt,"insert pdflettreresa,debut_expl_page into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreresa' and sstype_param='limite_after_list' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('pdflettreresa', 'limite_after_list', '270', 'Position limite en bas de page. Si un élément imprimé tente de dépasser cette limite, il sera imprimé sur la page suivante.') " ;
			echo traite_rqt($rqt,"insert pdflettreresa,limite_after_list into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreresa' and sstype_param='marge_page_gauche' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('pdflettreresa', 'marge_page_gauche', '10', 'Marge de gauche en mm') " ;
			echo traite_rqt($rqt,"insert pdflettreresa,marge_page_gauche into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreresa' and sstype_param='marge_page_droite' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('pdflettreresa', 'marge_page_droite', '10', 'Marge de droite en mm') " ;
			echo traite_rqt($rqt,"insert pdflettreresa,marge_page_droite into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreresa' and sstype_param='largeur_page' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('pdflettreresa', 'largeur_page', '210', 'Largeur de la page en mm') " ;
			echo traite_rqt($rqt,"insert pdflettreresa,largeur_page into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreresa' and sstype_param='hauteur_page' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('pdflettreresa', 'hauteur_page', '297', 'Hauteur de la page en mm') " ;
			echo traite_rqt($rqt,"insert pdflettreresa,hauteur_page into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreresa' and sstype_param='format_page' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('pdflettreresa', 'format_page', 'P', 'Format de la page : \r\n P : Portrait\r\n L : Landscape = paysage') " ;
			echo traite_rqt($rqt,"insert pdflettreresa,format_page into parametres");
			}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.10");
		break;	
	
	case "v3.10":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='categories_max_display' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'opac', 'categories_max_display', '200', 'Pour la page d\'accueil, nombre maximum de catégories principales affichées','i_categories')";
			echo traite_rqt($rqt,"insert opac_categories_max_display='200' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='search_other_function' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'opac', 'search_other_function', '', 'Fonction complémentaire pour les recherches en page d\'accueil','c_recherche')";
			echo traite_rqt($rqt,"insert opac_search_other_functiony='' into parametres");
			}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.11");
		break;	
	
	case "v3.11":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "alter table docs_section change section_libelle section_libelle varchar(255)";
		echo traite_rqt($rqt,"alter docs_section section_libelle varchar(255)");
		$rqt = "alter table docs_type change tdoc_libelle tdoc_libelle varchar(255)";
		echo traite_rqt($rqt,"alter docs_type tdoc_libelle varchar(255)");
		$rqt = "alter table docs_statut change statut_libelle statut_libelle varchar(255)";
		echo traite_rqt($rqt,"alter docs_statut statut_libelle varchar(255)");
		$rqt = "alter table docs_location change location_libelle location_libelle varchar(255)";
		echo traite_rqt($rqt,"alter docs_location location_libelle varchar(255)");
		$rqt = "alter table docs_codestat change codestat_libelle codestat_libelle varchar(255)";
		echo traite_rqt($rqt,"alter docs_codestat codestat_libelle varchar(255)");
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='lien_bas_supplementaire' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'opac', 'lien_bas_supplementaire', '', 'Lien supplémentaire en bas de page d\'accueil, à renseigner complètement : a href= lien /a','b_aff_general')";
			echo traite_rqt($rqt,"insert opac_lien_bas_supplementaire='' into parametres");
			}

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.12");
		break;	
	
	case "v3.12":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'z3950' and sstype_param='import_modele' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'z3950', 'import_modele', 'func_other.inc.php', 'Quel script de fonctions d\'import utiliser pour personnaliser l\'import en intégration z3950 ?','')";
			echo traite_rqt($rqt,"insert z3950_import_modele='' into parametres");
			}
		$rqt =  "ALTER TABLE empr ADD empr_ldap TINYINT(1) UNSIGNED DEFAULT '0'";
		echo traite_rqt($rqt,"TABLE EMPR ADD empr_ldap") ;
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.13");
		break;	
	
	case "v3.13":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'ldap' and sstype_param='server' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'ldap', 'server', '', 'Serveur LDAP, IP ou host')";
			echo traite_rqt($rqt,"insert ldap_server='' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'ldap' and sstype_param='basedn' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'ldap', 'basedn', '', 'Racine du nom de domaine LDAP')";
			echo traite_rqt($rqt,"insert ldap_basedn='' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'ldap' and sstype_param='port' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'ldap', 'port', '389', 'Port du serveur LDAP')";
			echo traite_rqt($rqt,"insert ldap_port='389' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'ldap' and sstype_param='filter' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'ldap', 'filter', '(&(objectclass=person)(gidnumber=GID))', 'Filtre')";
			echo traite_rqt($rqt,"insert ldap_filter='' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'ldap' and sstype_param='fields' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'ldap', 'fields', 'uid,gecos,departmentnumber', 'Champs du serveur LDAP')";
			echo traite_rqt($rqt,"insert ldap_fields='uid,gecos,departmentnumber' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'ldap' and sstype_param='lang' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'ldap', 'lang', 'fr_FR', 'Langue du serveur LDAP')";
			echo traite_rqt($rqt,"insert ldap_lang='fr_FR' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'ldap' and sstype_param='groups' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'ldap', 'groups', '', 'Groupes du serveur LDAP')";
			echo traite_rqt($rqt,"insert ldap_groups='' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'ldap' and sstype_param='accessible' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'ldap', 'accessible', '0', 'LDAP accessible ?')";
			echo traite_rqt($rqt,"insert ldap_accessible='0' into parametres");
			}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.14");
		break;	
	
	case "v3.14":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt =  "ALTER TABLE users ADD value_deflt_module varchar(30) DEFAULT 'circu'";
		echo traite_rqt($rqt," TABLE USERS ADD value_deflt_module ") ;
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.15");
		break;	
	
	case "v3.15":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE docs_location ADD name VARCHAR( 255 ) NOT NULL  default '' , ADD adr1 VARCHAR( 255 ) NOT NULL default '' , ADD adr2 VARCHAR( 255 ) NOT NULL  default '' , ADD cp VARCHAR( 15 ) NOT NULL  default '' , ADD town VARCHAR( 100 ) NOT NULL  default '' , ADD state VARCHAR( 100 ) NOT NULL  default '' , ADD country VARCHAR( 100 ) NOT NULL  default '' , ADD phone VARCHAR( 100 ) NOT NULL  default '' , ADD email VARCHAR( 100 ) NOT NULL  default '' , ADD website VARCHAR( 100 ) NOT NULL  default '' , ADD logo VARCHAR( 255 ) NOT NULL  default '', ADD logosmall VARCHAR( 255 ) NOT NULL  default ''";
		echo traite_rqt($rqt,"ALTER TABLE docs_location add addresses, town, phone, etc..."); 
		if (mysql_num_rows(mysql_query("select 1 from docs_location where name!='' "))==0){
			$requete_param = "SELECT type_param, sstype_param, valeur_param FROM parametres where type_param='biblio' ";
			$res_param = mysql_query($requete_param, $dbh);
			while ($field_values = mysql_fetch_row ( $res_param )) {
				$field = "old_".$field_values[1] ;
				$$field = $field_values[2];
				}
			$rqt = "update docs_location set name='".addslashes($old_name)."',adr1='".addslashes($old_adr1)."',adr2='".addslashes($old_adr2)."',cp='".addslashes($old_cp)."',town='".addslashes($old_town)."',state='".addslashes($old_state)."',country='".addslashes($old_country)."',phone='".addslashes($old_phone)."',email='".addslashes($old_email)."',website='".addslashes($old_website)."',logo='".addslashes($old_logo)."',logosmall='".addslashes($old_logosmall)."'"  ;
			echo traite_rqt($rqt,"UPDATE TABLE docs_location feed addresses, town, phone, etc..."); 
			}
		$rqt = "delete from parametres where type_param='biblio' ";
		echo traite_rqt($rqt,"Suppr/del parametres biblio_*");
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.16");
		break;	
	
	case "v3.16":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='prefill_cote' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'prefill_cote', 'custom_cote_00.inc.php', 'Script personnalisé de construction de la cote de l\'exemplaire')";
			echo traite_rqt($rqt,"insert pmb_prefill_cote='custom_cote_00.inc.php' into parametres");
			}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.17");
		break;	
	
	case "v3.17":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'ldap' and sstype_param='proto' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'ldap', 'proto', '3', 'Version du protocole LDAP : 3 ou 2 ')";
			echo traite_rqt($rqt,"insert ldap_proto='3' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'ldap' and sstype_param='binddn' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'ldap', 'binddn', 'uid=UID,ou=People', 'Description de la liaison : construction de la chaine binddn pour lier l\'authentification au serveur LDAP dans l\'OPAC ')";
			echo traite_rqt($rqt,"insert ldap_binddn='uid=UID,ou=People' into parametres");
			}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.18");
		break;	
	
	case "v3.18":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='categories_show_only_last' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'opac', 'categories_show_only_last', '0', 'Dans la fiche d\'une notice : \n 0 tout afficher \n 1 : afficher uniquement la dernière feuille de l\'arbre de la catégorie','i_categories')";
			echo traite_rqt($rqt,"insert opac_categories_show_only_last='0' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'categories' and sstype_param='show_only_last' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'categories', 'show_only_last', '0', 'Dans la fiche d\'une notice : \n 0 tout afficher \n 1 : afficher uniquement la dernière feuille de l\'arbre de la catégorie','')";
			echo traite_rqt($rqt,"insert categories_show_only_last='0' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'empr' and sstype_param='corresp_import' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'empr', 'corresp_import', '', 'Table de correspondances colonnes/champs en import de lecteurs à partir d\'un fichier ASCII','')";
			echo traite_rqt($rqt,"insert empr_corresp_import='...' into parametres");
			}
		$rqt = "ALTER TABLE users ADD user_email VARCHAR(255) default '', ADD user_alert_resamail INT(1) UNSIGNED DEFAULT '0' NOT NULL ";
		echo traite_rqt($rqt,"ALTER TABLE users add resa management's fields");

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='type_audit' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'pmb', 'type_audit', '0', 'Gestion/affichage des dates de création/modification \n 0: Rien\n 1: Création et dernière modification\n 2: Création et toutes les dates de modification','')";
			echo traite_rqt($rqt,"insert pmb_type_audit='0' into parametres");
			}
		$rqt = "CREATE TABLE audit (type_obj int(1) NOT NULL default 0, object_id int(10) unsigned NOT NULL default 0, user_id int(8) unsigned NOT NULL default 0, user_name varchar(20) not null default '', type_modif int(1) not null default 1, quand timestamp ) ";
		echo traite_rqt($rqt,"CREATE TABLE audit");
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.19");
		break;	
	
	case "v3.19":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
 		$rqt = "ALTER TABLE exemplaires CHANGE expl_creation expl_date_depot DATE DEFAULT '0000-00-00' NOT NULL";
		echo traite_rqt($rqt,"ALTER TABLE exemplaires expl_date_depot"); 
		$rqt = "ALTER TABLE exemplaires CHANGE expl_modif expl_date_retour DATE DEFAULT '0000-00-00' NOT NULL";
		echo traite_rqt($rqt,"ALTER TABLE exemplaires expl_date_retour"); 
		$rqt = "ALTER TABLE exemplaires drop key date_creation ";
		echo traite_rqt($rqt,"ALTER TABLE exemplaires drop key date_creation ");
		$rqt = "ALTER TABLE exemplaires DROP KEY expl_id ";
		echo traite_rqt($rqt,"ALTER TABLE exemplaires DROP KEY expl_id ");
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.20");
		break;	
	
	case "v3.20":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "create table type_comptes (id_type_compte integer(8) unsigned not null auto_increment,libelle varchar(255) not null default '', type_acces integer(8) unsigned not null default 0, acces_id text not null default '',primary key id_type_compte (id_type_compte))";
		echo traite_rqt($rqt,"CREATE TABLE type_comptes"); 
		$rqt = "create table comptes (id_compte integer(8) unsigned not null auto_increment,libelle varchar(255) not null default '',type_compte_id integer unsigned not null default 0,solde decimal(16,2) default 0, proprio_id integer unsigned not null default 0, droits text not null default '',primary key id_compte (id_compte))";
		echo traite_rqt($rqt,"CREATE TABLE comptes"); 
		$rqt = "create table transactions (id_transaction integer(10) unsigned not null auto_increment,compte_id integer(8) unsigned not null,user_id integer unsigned not null,user_name varchar(255) not null default '',machine varchar(255) not null default '',date_enrgt datetime not null,date_prevue date,date_effective date,montant decimal(16,2) not null default 0,sens integer(1) not null,realisee integer(1) not null default 0,primary key (id_transaction))";
		echo traite_rqt($rqt,"CREATE TABLE transactions"); 

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='gestion_calendrier' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'pmb', 'gestion_calendrier', '0', 'Gestion du calendrier des dates d\'ouverture \n 0: Non, aucun calcul des dates de retour sur ce calendrier \1: Oui','')";
			echo traite_rqt($rqt,"insert pmb_gestion_calendrier=0 into parametres");
			}
		$rqt = "create table ouvertures (date_ouverture date not null default '0000-00-00', ouvert integer(1) not null default 1, commentaire varchar (255) not null default '', primary key (date_ouverture))";
		echo traite_rqt($rqt,"CREATE TABLE ouvertures"); 
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.21");
		break;	
	
	case "v3.21":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "delete from parametres where type_param= 'z3950' and sstype_param='sutrs_lang' " ;
		echo traite_rqt($rqt,"delete wrong parameter from parametres");
		$rqt = "ALTER TABLE z_bib ADD sutrs_lang VARCHAR(10) not null default '' ";
		echo traite_rqt($rqt,"ALTER TABLE z_bib add sutrs_lang ");
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.22");
		break;	
	
	case "v3.22":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
 		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='utiliser_calendrier' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'utiliser_calendrier', '0', 'Utiliser le calendrier des jours d\'ouverture ? \n 0 : Non\n 1 : Oui') " ;
			echo traite_rqt($rqt,"insert pmb_utiliser_calendrier = 0 into parametres");
			}
 		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='gestion_financiere' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'gestion_financiere', '0', 'Utiliser le module gestion financière ? \n 0 : Non\n 1 : Oui') " ;
			echo traite_rqt($rqt,"insert pmb_gestion_financiere = 0 into parametres");
			}
		$rqt = "delete from parametres where type_param= 'pmb' and sstype_param='gestion_calendrier' " ;
		echo traite_rqt($rqt,"delete wrong parameter from parametres");
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.23");
		break;	
	
	case "v3.23":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "alter table notices add mention_edition varchar(255) not null default '' after nocoll " ;
		echo traite_rqt($rqt,"alter notices ADD mention_edition ");
		
		$rqt = "CREATE TABLE type_abts (id_type_abt int(5) unsigned NOT NULL auto_increment,type_abt_libelle varchar(255) default NULL,prepay int(1) unsigned NOT NULL default 0,prepay_deflt_mnt decimal(16,2) NOT NULL default '0',tarif decimal(16,2) NOT NULL default '0',commentaire text NOT NULL default '',caution decimal(16,2) NOT NULL default '0',PRIMARY KEY  (id_type_abt) )" ; 
		echo traite_rqt($rqt,"create table type_abts ");
		
        $rqt = "alter table comptes add prepay_mnt decimal(16,2) not null default 0 after solde " ;
		echo traite_rqt($rqt,"alter comptes ADD prepay_mnt ");
		$rqt = "ALTER TABLE empr ADD type_abt INT(1) DEFAULT '0' NOT NULL " ;
		echo traite_rqt($rqt,"alter empr add type_abt");
		$rqt = "ALTER TABLE empr_categ ADD tarif_abt DECIMAL( 16, 2 ) DEFAULT '0' NOT NULL " ;
		echo traite_rqt($rqt,"alter empr_categ add tarif_abt");
		$rqt = "ALTER TABLE docs_type ADD tarif_pret DECIMAL(16, 2) DEFAULT '0' NOT NULL " ;
		echo traite_rqt($rqt,"alter docs_type add tarif_pret");
		
 		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='gestion_abonnement' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'gestion_abonnement', '0', 'Utiliser la gestion des abonnements des lecteurs ? \n 0 : Non\n 1 : Oui, gestion simple, \n 2 : Oui, gestion avancée') " ;
			echo traite_rqt($rqt,"insert pmb_gestion_abonnement = 0 into parametres");
			}
 		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='gestion_tarif_prets' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'gestion_tarif_prets', '0', 'Utiliser la gestion des tarifs de prêts ? \n 0 : Non\n 1 : Oui, gestion simple, \n 2 : Oui, gestion avancée') " ;
			echo traite_rqt($rqt,"insert pmb_gestion_tarif_prets = 0 into parametres");
			}
 		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='gestion_amende' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'gestion_amende', '0', 'Utiliser la gestion des amendes:\n 0 = Non\n 1 = Gestion simple\n 2 = Gestion avancée') " ;
			echo traite_rqt($rqt,"insert pmb_gestion_amende = 0 into parametres");
			}

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.24");
		break;	
	
	case "v3.24":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		
		$rqt = "CREATE TABLE notice_statut (id_notice_statut smallint(5) unsigned NOT NULL auto_increment, gestion_libelle varchar(255) default NULL, opac_libelle varchar(255) default NULL, notice_visible_opac tinyint(1) NOT NULL default '1', notice_visible_gestion tinyint(1) NOT NULL default '1', expl_visible_opac tinyint(1) NOT NULL default '1', PRIMARY KEY  (id_notice_statut))" ;
		echo traite_rqt($rqt,"create table notice_statut");
		$rqt = "insert into notice_statut SET id_notice_statut=1, gestion_libelle='Sans statut particulier', notice_visible_gestion='1',opac_libelle='', notice_visible_opac='1', expl_visible_opac='1' ";
		echo traite_rqt($rqt,"insert minimum into notice_statut");
		$rqt = "ALTER TABLE notices ADD statut int(5) DEFAULT '1' NOT NULL " ;
		echo traite_rqt($rqt,"alter notices add statut");
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.25");
		break;	
	
	case "v3.25":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE type_abts ADD localisations varchar(255) DEFAULT '' NOT NULL " ;
		echo traite_rqt($rqt,"alter type_abts ADD localisations ");
		$rqt = "ALTER TABLE users ADD deflt_notice_statut INT(6) UNSIGNED DEFAULT '1' NOT NULL AFTER param_licence " ;
		echo traite_rqt($rqt,"ALTER users ADD deflt_notice_statut ");
		$rqt = "ALTER TABLE notices ADD commentaire_gestion TEXT DEFAULT '' NOT NULL " ;
		echo traite_rqt($rqt,"alter notices add commentaire_gestion");
		$rqt = "ALTER TABLE parametres ADD gestion int(1) DEFAULT 0 NOT NULL " ;
		echo traite_rqt($rqt,"alter parametres add gestion");
 		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'finance' and sstype_param='amende_jour' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, gestion) VALUES (0, 'finance', 'amende_jour', '0.15', 'Amende par jour de retard pour tout type de document. Attention, le séparateur décimal est le point, pas la virgule',1) " ;
			echo traite_rqt($rqt,"insert finance_amende_jour = 0.15 into parametres");
			}
 		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'finance' and sstype_param='delai_avant_amende' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, gestion) VALUES (0, 'finance', 'delai_avant_amende', '15', 'Délai avant déclenchement de l\'amende, en jour',1) " ;
			echo traite_rqt($rqt,"insert finance_delai_avant_amende = 15 into parametres");
			}
 		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'finance' and sstype_param='delai_recouvrement' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, gestion) VALUES (0, 'finance', 'delai_recouvrement', '7', 'Délai entre 3eme relance et mise en recouvrement officiel de l\'amende, en jour',1) " ;
			echo traite_rqt($rqt,"insert finance_delai_recouvrement = 7 into parametres");
			}
 		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'finance' and sstype_param='amende_maximum' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, gestion) VALUES (0, 'finance', 'amende_maximum', '0', 'Amende maximum, quel que soit le retard l\'amende est plafonnée à ce montant. 0 pour désactiver ce plafonnement.',1) " ;
			echo traite_rqt($rqt,"insert finance_amende_maximum = 0 into parametres");
			}
		$rqt = "ALTER TABLE notice_statut ADD class_html VARCHAR( 255 ) DEFAULT '' NOT NULL" ;
		echo traite_rqt($rqt,"alter notice_statut add class_html"); 
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.26");
		break;	
	
	case "v3.26":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
 		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreresa' and sstype_param='priorite_email' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, gestion) VALUES (0, 'pdflettreresa', 'priorite_email', '1', 'Priorité des lettres de confirmation de réservation par mail lors de la validation d\'une réservation:\n 0 : Lettre seule \n 1 : Mail, à défaut lettre\n 2 : Mail ET lettre\n 3 : Aucune alerte',0) " ;
			echo traite_rqt($rqt,"insert pdflettreresa_priorite_email = 1 into parametres");
			}
 		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreresa' and sstype_param='priorite_email_manuel' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, gestion) VALUES (0, 'pdflettreresa', 'priorite_email_manuel', '1', 'Priorité des lettres de confirmation de réservation par mail lors de l\'impression à partir du bouton :\n 0 : Lettre seule \n 1 : Mail, à défaut lettre\n 2 : Mail ET lettre\n 3 : Aucune alerte',0) " ;
			echo traite_rqt($rqt,"insert pdflettreresa_priorite_email_manuel = 1 into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'finance' and sstype_param='blocage_abt' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, gestion) VALUES (0, 'finance', 'blocage_abt', '1', 'Blocage du prêt si le compte abonnement est débiteur\n 0 : pas de blocage \n 1 : blocage avec forçage possible \2 : blocage incontournable.',1) " ;
			echo traite_rqt($rqt,"insert finance_blocage_abt = 1 into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'finance' and sstype_param='blocage_pret' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, gestion) VALUES (0, 'finance', 'blocage_pret', '1', 'Blocage du prêt si le compte prêt est débiteur\n 0 : pas de blocage \n 1 : blocage avec forçage possible \2 : blocage incontournable.',1) " ;
			echo traite_rqt($rqt,"insert finance_blocage_pret = 1 into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'finance' and sstype_param='blocage_amende' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, gestion) VALUES (0, 'finance', 'blocage_amende', '1', 'Blocage du prêt si le compte amende est débiteur\n 0 : pas de blocage \n 1 : blocage avec forçage possible \2 : blocage incontournable.',1) " ;
			echo traite_rqt($rqt,"insert finance_blocage_amende = 1 into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='gestion_devise' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, gestion) VALUES (0, 'pmb', 'gestion_devise', '&euro;', 'Devise de la gestion financière, ce qui va être affiché en code HTML',0) " ;
			echo traite_rqt($rqt,"insert pmb_gestion_devise = &euro; into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='book_pics_url' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'opac', 'book_pics_url', '', 'URL des vignettes des notices, dans le chemin fourni, !!isbn!! sera remplacé par le code ISBN ou EAN de la notice purgé de tous les tirets ou points. \n exemple : http://www.monsite/opac/images/vignettes/!!isbn!!.jpg','e_aff_notice')";
			echo traite_rqt($rqt,"insert opac_book_pics_url='' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='lien_moteur_recherche' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'opac', 'lien_moteur_recherche', '<a href=http://www.google.fr target=_blank>Faire une recherche avec Google</a>', 'Lien supplémentaire en bas de page d\'accueil, à renseigner complètement : a href= lien /a','b_aff_general')";
			echo traite_rqt($rqt,"insert opac_lien_moteur_recherche='google.fr' into parametres");
			}

		$rqt = "ALTER TABLE users CHANGE pwd pwd VARCHAR(50) NOT NULL default '' ";
		echo traite_rqt($rqt,"ALTER user pwd varchar(50)") ;

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='pret_express_statut' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'pret_express_statut', '2', 'Statut de notice à utiliser en création d\'exemplaires en prêts express')";
			echo traite_rqt($rqt,"insert pmb_pret_express_statut='2' into parametres");
			}

		$rqt = "alter TABLE notice_statut add notice_visible_opac_abon tinyint(1) NOT NULL default '0' " ;
		echo traite_rqt($rqt,"alter TABLE notice_statut add notice_visible_opac_abon");
		if (mysql_num_rows(mysql_query("select 1 from notice_statut where id_notice_statut=2 "))==0){
			$rqt = "insert into notice_statut SET id_notice_statut=2, gestion_libelle='Prêt express', notice_visible_gestion='1',opac_libelle='', notice_visible_opac='0', expl_visible_opac='1', notice_visible_opac_abon='1' ";
			echo traite_rqt($rqt,"insert 'Fast loan' into notice_statut");
			}
		$rqt = "update notice_statut set class_html='statutnot1' where class_html is null or class_html='' " ;
		echo traite_rqt($rqt,"update notice_statut set class_html ");
		$rqt = "update notice_statut set class_html='statutnot2' where id_notice_statut=2 and (class_html is null or class_html='') " ;
		echo traite_rqt($rqt,"update notice_statut set class_html where id=2 ");

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='notice_affichage_class' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'opac', 'notice_affichage_class', '', 'Nom de la classe d\'affichage pour personnalisation de l\'affichage des notices','e_aff_notice')";
			echo traite_rqt($rqt,"insert opac_notice_affichage_class='' into parametres");
			}
		$rqt = "CREATE TABLE quotas_finance (quota_type int(10) unsigned NOT NULL default '0', constraint_type varchar(255) NOT NULL default '', elements int(10) unsigned NOT NULL default '0', value float default NULL, PRIMARY KEY  (quota_type,constraint_type,elements)) TYPE=MyISAM " ;
		echo traite_rqt($rqt,"create table quotas_finance ");
		$rqt = "alter table transactions add commentaire text, add encaissement int(1) default 0 not null" ;
		echo traite_rqt($rqt,"alter table transactions add commentaire & encaissement ");
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.27");
		break;	
	
	case "v3.27": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE empr ADD last_loan_date DATE DEFAULT NULL  " ;
		echo traite_rqt($rqt,"alter table empr add last_loan_date ");

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='confirm_retour' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'confirm_retour', '0', 'En retour de documents, le retour doit-il être confirmé ? \n 0 : Non, on peut passer les codes-barres les uns après les autres \n 1 : Oui, il faut valider le retour après chaque code-barre')";
			echo traite_rqt($rqt,"insert pmb_confirm_retour='0' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_meteo_url' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'opac', 'show_meteo_url', '<img src=\"http://perso0.free.fr/cgi-bin/meteo.pl?dep=".$opac_biblio_dep."\" alt=\"\" border=\"0\" hspace=0>', 'URL de la météo affichée','f_modules')";
			echo traite_rqt($rqt,"insert opac_show_meteo_url=### into parametres");
			}

		$rqt = "ALTER TABLE notices ADD create_date DATETIME DEFAULT '2005-01-01' NOT NULL , ADD update_date TIMESTAMP NOT NULL  " ;
		echo traite_rqt($rqt,"alter table notice add create_date & update_date ");

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.28");
		break;	
	
	case "v3.28": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE exemplaires ADD last_loan_date DATE default NULL, ADD create_date DATETIME DEFAULT '2005-01-01' NOT NULL , ADD update_date TIMESTAMP NOT NULL " ;
		echo traite_rqt($rqt,"alter table exemplaires add last_loan_date, create_date & update_date ");

		$rqt = "ALTER TABLE empr CHANGE last_loan_date last_loan_date DATE DEFAULT NULL  " ;
		echo traite_rqt($rqt,"alter table empr last_loan_date default null ");
		$rqt = "update empr set last_loan_date = null where last_loan_date='2005-01-01' or last_loan_date='0000-00-00' " ;
		echo traite_rqt($rqt,"update empr last_loan_date default null ");

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='limitation_dewey' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'limitation_dewey', '0', 'Nombre maximum de caractères dans la Dewey (676) en import : \n 0 aucune limitation \n 3 : limitation de 000 à 999 \n 5 (exemple) limitation 000.0 \n -1 : aucune importation')";
			echo traite_rqt($rqt,"insert pmb_limitation_dewey=0 into parametres");
			}
		$rqt = "update parametres set comment_param='Format d\'affichage des notices dans les étagères de l\'écran d\'accueil \n 1 : ISBD seul \n 2 : Public seul \n 4 : ISBD et Public \n 5 : ISBD et Public avec ISBD en premier \n 8 : Réduit (titre+auteurs) seul' where type_param='opac' and sstype_param='notices_format'" ;
		echo traite_rqt($rqt,"UPDATE parametres SET comment_param for opac_notices_format") ;
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'finance' and sstype_param='delai_1_2' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, gestion) VALUES (0, 'finance', 'delai_1_2', '15', 'Délai entre 1ere et 2eme relance',1) " ;
			echo traite_rqt($rqt,"insert finance_delai_1_2 = 15 into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'finance' and sstype_param='delai_2_3' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, gestion) VALUES (0, 'finance', 'delai_2_3', '15', 'Délai entre 2eme et 3eme relance',1) " ;
			echo traite_rqt($rqt,"insert finance_delai_2_3 = 15 into parametres");
			}
		
		$rqt = "ALTER TABLE users ADD deflt2docs_location INT( 6 ) UNSIGNED DEFAULT '0' NOT NULL  " ;
		echo traite_rqt($rqt,"deflt2docs_location ADD in users ");
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='lecteurs_localises' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'lecteurs_localises', '0', 'Lecteurs localisés ? \n 0: Non \n 1: Oui')";
			echo traite_rqt($rqt,"insert pmb_lecteurs_localises=0 into parametres");
			}
		// Info de localisation des 
		$rqt = " select 1 " ;
		echo traite_rqt($rqt,"<br /><br /><b>!! STOP!! </b><br />VOUS DEVEZ RENSEIGNER : <b>".$msg[deflt2docs_location]."</b> <br />YOU MUST GIVE :<b>".$msg[deflt2docs_location]."</b><br />Préférences :  <a href=../../account.php target=_blank>cliquez ici / click here</a>") ;
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.29");
		break;	
	
	case "v3.29": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (!$deflt2docs_location) die ("<br /><br /><b>!! STOP!! </b><br />VOUS DEVEZ RENSEIGNER : <b>".$msg[deflt2docs_location]."</b> <br />YOU MUST GIVE :<b>".$msg[deflt2docs_location]."</b><br />Préférences :  <a href=../../account.php target=_blank>cliquez ici / click here</a>") ;
		// 
		
		$rqt = "ALTER TABLE notices CHANGE typdoc typdoc VARCHAR( 2 ) DEFAULT 'a' NOT NULL " ;
		echo traite_rqt($rqt,"alter notices change typdoc varchar(2) ");

		$rqt = "CREATE TABLE bannettes (id_bannette INT( 9 ) UNSIGNED NOT NULL AUTO_INCREMENT ,nom_bannette VARCHAR( 255 ) NOT NULL default '',comment_gestion VARCHAR( 255 ) NOT NULL default '',comment_public VARCHAR( 255 ) NOT NULL default '',date_last_remplissage DATETIME NOT NULL ,date_last_envoi DATETIME NOT NULL ,proprio_bannette INT( 9 ) UNSIGNED NOT NULL default 0,bannette_auto INT( 1 ) UNSIGNED NOT NULL default 0,periodicite INT( 3 ) UNSIGNED NOT NULL default 7,diffusion_email INT( 1 ) UNSIGNED NOT NULL default 0,categorie_lecteurs BLOB NOT NULL default '',PRIMARY KEY ( id_bannette )) TYPE = MYISAM " ;
		echo traite_rqt($rqt,"create table bannettes");
		$rqt = "CREATE TABLE equations (id_equation INT( 9 ) UNSIGNED NOT NULL AUTO_INCREMENT ,nom_equation VARCHAR( 255 ) NOT NULL default '',comment_equation VARCHAR( 255 ) NOT NULL default '',requete BLOB NOT NULL default '',proprio_equation INT( 9 ) UNSIGNED NOT NULL default 0,PRIMARY KEY ( id_equation )) TYPE = MYISAM " ;
		echo traite_rqt($rqt,"create table equations");
		$rqt = "CREATE TABLE bannette_equation (num_bannette INT( 9 ) UNSIGNED DEFAULT 0 NOT NULL ,num_equation INT( 9 ) UNSIGNED DEFAULT 0 NOT NULL ,PRIMARY KEY  (num_bannette, num_equation)) TYPE = MYISAM " ;
		echo traite_rqt($rqt,"create table bannette_equation");
		$rqt = "CREATE TABLE bannette_contenu (num_bannette INT( 9 ) UNSIGNED DEFAULT 0 NOT NULL ,num_notice INT( 9 ) UNSIGNED DEFAULT 0 NOT NULL ,PRIMARY KEY  (num_bannette, num_notice)) TYPE = MYISAM " ;
		echo traite_rqt($rqt,"create table bannette_contenu");
		$rqt = "CREATE TABLE bannette_abon (num_bannette INT( 9 ) UNSIGNED DEFAULT 0 NOT NULL ,num_empr INT( 9 ) UNSIGNED DEFAULT 0 NOT NULL ,actif INT( 1 ) UNSIGNED NOT NULL default 0,PRIMARY KEY  (num_bannette, num_empr)) TYPE = MYISAM " ;
		echo traite_rqt($rqt,"create table bannette_abon");
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'dsi' and sstype_param='active' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'dsi', 'active', '0', 'D.S.I activée ? \n 0: Non \n 1: Oui')";
			echo traite_rqt($rqt,"insert dsi_active=0 into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'dsi' and sstype_param='auto' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'dsi', 'auto', '0', 'D.S.I automatique activée ? \n 0: Non \n 1: Oui')";
			echo traite_rqt($rqt,"insert dsi_auto=0 into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'dsi' and sstype_param='insc_categ' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'dsi', 'insc_categ', '0', 'Inscription automatique dans les bannettes de la catégorie du lecteur en création ? \n 0: Non \n 1: Oui')";
			echo traite_rqt($rqt,"insert dsi_insc_categ=0 into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='allow_bannette_priv' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'opac', 'allow_bannette_priv', '0', 'Possibilité pour les lecteurs de créer ou modifier leurs bannettes privées \n 0: Non \n 1: Oui','l_dsi')";
			echo traite_rqt($rqt,"insert opac_allow_bannette_priv=0 into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='allow_resiliation' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'opac', 'allow_resiliation', '0', 'Possibilité pour les lecteurs de résilier leur abonnement aux bannettes pro \n 0: Non \n 1: Oui','l_dsi')";
			echo traite_rqt($rqt,"insert opac_allow_resiliation=0 into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_categ_bannette' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'opac', 'show_categ_bannette', '0', 'Affichage des bannettes de la catégorie du lecteur et possibilité de s\'y abonner \n 0: Non \n 1: Oui','l_dsi')";
			echo traite_rqt($rqt,"insert opac_show_categ_bannette=0 into parametres");
			}
		$rqt="ALTER TABLE bannettes ADD num_classement INT( 8 ) UNSIGNED DEFAULT 1 NOT NULL AFTER id_bannette ";
		echo traite_rqt($rqt,"alter table bannettes add classement");
		$rqt="ALTER TABLE equations ADD num_classement INT( 8 ) UNSIGNED DEFAULT 1 NOT NULL AFTER id_equation ";
		echo traite_rqt($rqt,"alter table equations add classement");
		$rqt="CREATE TABLE classements ( id_classement int(8) unsigned NOT NULL auto_increment,  type_classement varchar(3) not null default 'BAN',  nom_classement varchar(255) NOT NULL default '',  PRIMARY KEY  (id_classement)) TYPE=MyISAM ";
		echo traite_rqt($rqt,"create table classement"); 
		$rqt="INSERT INTO classements ( id_classement, type_classement, nom_classement) VALUES (1, '', '_NON CLASSE_')";
		echo traite_rqt($rqt,"insert _NON_CLASSE_ into classement"); 
		$rqt="ALTER TABLE bannettes CHANGE categorie_lecteurs categorie_lecteurs INT(8) UNSIGNED DEFAULT 0 NOT NULL ";
		echo traite_rqt($rqt,"alter table bannettes change categories_lecteurs ");
		
		$rqt="ALTER TABLE resa ADD resa_confirmee INT( 1 ) UNSIGNED DEFAULT 0 NOT NULL ";
		echo traite_rqt($rqt,"alter table resa add resa_confirmee "); 

		$rqt="ALTER TABLE pret_archive ADD arc_groupe varchar(255) DEFAULT '' NOT NULL ";
		echo traite_rqt($rqt,"alter table pret_archive add arc_groupe ");
		
		if (!$deflt2docs_location) {
			$req="select idlocation from docs_location limit 1";
			$result = mysql_query($req, $dbh) ;
			$res_loc = mysql_fetch_object($result);
			$deflt2docs_location = $res_loc->idlocation;
			} 
		$rqt="ALTER TABLE empr ADD empr_location INT( 6 ) UNSIGNED DEFAULT '$deflt2docs_location' NOT NULL "; 
 		echo traite_rqt($rqt,"alter table empr add empr_location ");
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.30");
		break;	
	
	case "v3.30": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='url_base' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'opac', 'url_base', './opac_css/', 'URL de base de l\'opac : typiquement mettre l\'url publique web http://monsite/opac/ ne pas oublier le / final','a_general')";
			echo traite_rqt($rqt,"insert opac_url_base=./opac_css/ into parametres");
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'finance' and sstype_param='relance_1' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'finance','relance_1','0.53','Frais de la première lettre de relance','',1)" ;
			echo traite_rqt($rqt,"insert finance into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'finance' and sstype_param='relance_2' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'finance','relance_2','0.53','Frais de la deuxième lettre de relance','',1)" ;
			echo traite_rqt($rqt,"insert finance into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'finance' and sstype_param='relance_3' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'finance','relance_3','2.50','Frais de la troisième lettre de relance','',1)" ;
			echo traite_rqt($rqt,"insert finance into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'finance' and sstype_param='statut_perdu' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'finance','statut_perdu','','Statut (d\'exemplaire) perdu pour des ouvrages non rendus','',1)" ;
			echo traite_rqt($rqt,"insert finance into parametres") ;
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='2after_list' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','2after_list','Nous vous remercions de prendre rapidement contact par téléphone au $biblio_phone ou par mail à $biblio_email pour étudier la possibilité de prolonger ces prêts ou de ramener les ouvrages concernés.','Texte apparaissant après la liste des ouvrages en retard dans le courrier','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard2 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='2before_list' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','2before_list','Sauf erreur de notre part, vous avez toujours en votre possession le ou les ouvrage(s) suivant(s) dont la durée de prêt est aujourd\'hui dépassée :','Texte apparaissant avant la liste des ouvrages en retard dans le courrier de relance de retard','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard2 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='2debut_expl_1er_page' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','2debut_expl_1er_page','160','Début de la liste des exemplaires sur la première page, en mm depuis le bord supérieur de la page. Doit être règlé en fonction du texte qui précède la liste des ouvrages, lequel peut être plus ou moins long.','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard2 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='2debut_expl_page' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','2debut_expl_page','15','Début de la liste des exemplaires sur les pages suivantes, en mm depuis le bord supérieur de la page.','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard2 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='2fdp' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','2fdp','Le responsable de la $biblio_name.','Signataire de la lettre, utiliser $biblio_name pour reprendre le paramètre \"biblio name\" ou bien mettre autre chose.','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard2 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='2format_page' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','2format_page','P','Format de la page : \r\n P : Portrait\r\n L : Landscape = paysage','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard2 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='2hauteur_page' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','2hauteur_page','297','Hauteur de la page en mm','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard2 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='2largeur_page' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','2largeur_page','210','Largeur de la page en mm','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard2 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='2limite_after_list' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','2limite_after_list','270','Position limite en bas de page. Si un élément imprimé tente de dépasser cette limite, il sera imprimé sur la page suivante.','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard2 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='2madame_monsieur' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','2madame_monsieur','Madame, Monsieur,','Entête de la lettre','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard2 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='2marge_page_droite' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','2marge_page_droite','10','Marge de droite en mm','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard2 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='2marge_page_gauche' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','2marge_page_gauche','10','Marge de gauche en mm','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard2 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='2nb_1ere_page' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','2nb_1ere_page','4','Nombre d\'ouvrages en retard imprimé sur la première page','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard2 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='2nb_par_page' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','2nb_par_page','7','Nombre d\'ouvrages en retard imprimé sur les pages suivantes.','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard2 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='2taille_bloc_expl' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','2taille_bloc_expl','16','Taille d\'un bloc (2 lignes) d\'ouvrage en retard. Le début de chaque ouvrage en retard sera espacé de cette valeur sur la page','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard2 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='3after_list' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','3after_list','Nous vous remercions de prendre rapidement contact par téléphone au $biblio_phone ou par mail à $biblio_email pour étudier la possibilité de prolonger ces prêts ou de ramener les ouvrages concernés.','Texte apparaissant après la liste des ouvrages en retard dans le courrier','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard3 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='3before_list' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','3before_list','Sauf erreur de notre part, vous avez toujours en votre possession le ou les ouvrage(s) suivant(s) dont la durée de prêt est aujourd\'hui dépassée :','Texte apparaissant avant la liste des ouvrages en retard dans le courrier de relance de retard','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard3 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='3debut_expl_1er_page' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','3debut_expl_1er_page','160','Début de la liste des exemplaires sur la première page, en mm depuis le bord supérieur de la page. Doit être règlé en fonction du texte qui précède la liste des ouvrages, lequel peut être plus ou moins long.','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard3 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='3debut_expl_page' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','3debut_expl_page','15','Début de la liste des exemplaires sur les pages suivantes, en mm depuis le bord supérieur de la page.','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard3 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='3fdp' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','3fdp','Le responsable de la $biblio_name.','Signataire de la lettre, utiliser $biblio_name pour reprendre le paramètre \"biblio name\" ou bien mettre autre chose.','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard3 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='3format_page' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','3format_page','P','Format de la page : \r\n P : Portrait\r\n L : Landscape = paysage','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard3 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='3hauteur_page' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','3hauteur_page','297','Hauteur de la page en mm','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard3 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='3largeur_page' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','3largeur_page','210','Largeur de la page en mm','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard3 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='3limite_after_list' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','3limite_after_list','270','Position limite en bas de page. Si un élément imprimé tente de dépasser cette limite, il sera imprimé sur la page suivante.','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard3 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='3madame_monsieur' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','3madame_monsieur','Madame, Monsieur,','Entête de la lettre','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard3 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='3marge_page_droite' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','3marge_page_droite','10','Marge de droite en mm','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard3 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='3marge_page_gauche' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','3marge_page_gauche','10','Marge de gauche en mm','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard3 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='3nb_1ere_page' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','3nb_1ere_page','4','Nombre d\'ouvrages en retard imprimé sur la première page','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard3 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='3nb_par_page' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','3nb_par_page','7','Nombre d\'ouvrages en retard imprimé sur les pages suivantes.','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard3 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='3taille_bloc_expl' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','3taille_bloc_expl','16','Taille d\'un bloc (2 lignes) d\'ouvrage en retard. Le début de chaque ouvrage en retard sera espacé de cette valeur sur la page','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard3 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='3before_recouvrement' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pdflettreretard','3before_recouvrement','Sans nouvelles de votre part dans les sept jours, nous nous verrons contraints de déléguer au trésor public le recouvrement des ouvrages suivants :','Texte avant la liste des ouvrages en recouvrement','',0)" ;
			echo traite_rqt($rqt,"insert pdflettreretard3 into parametres") ;
			}
		$rqt = "ALTER TABLE pret ADD pret_arc_id INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL " ;
		echo traite_rqt($rqt,"ALTER TABLE pret ADD pret_arc_id ") ;
		$rqt = "ALTER TABLE pret_archive CHANGE arc_fin arc_fin DATETIME " ;
		echo traite_rqt($rqt,"ALTER TABLE pret_archive CHANGE arc_fin DATETIME  ") ;
		$rqt = "ALTER TABLE pret_archive CHANGE arc_id arc_id INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT " ;
		echo traite_rqt($rqt,"ALTER TABLE pret_archive arc_id integer(10) ") ;
		
		$rqt = "ALTER TABLE bannettes ADD nb_notices_diff INT(4) UNSIGNED DEFAULT 0 NOT NULL " ;
		echo traite_rqt($rqt,"ALTER TABLE bannettes ADD nb_notices_diff ") ;

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='bannette_notices_order' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param) VALUES (0, 'opac', 'bannette_notices_order', ' index_serie, tnvol, index_sew ', 'Ordre d\'affichage des notices dans les bannettes dans l\'opac \n  index_serie, tnvol, index_sew : tri par titre de série et titre \n rand()  : aléatoire ', 'l_dsi')";
			echo traite_rqt($rqt,"insert opac_bannette_notices_order=' index_serie, tnvol, index_sew ' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='bannette_notices_format' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param) VALUES (0, 'opac', 'bannette_notices_format', '8', 'Format d\'affichage des notices dans les bannettes \n 1 : ISBD seul \n 2 : Public seul \n 4 : ISBD et Public \n 8 : Réduit (titre+auteurs) seul', 'l_dsi')";
			echo traite_rqt($rqt,"insert opac_bannette_notices_format='8' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='bannette_notices_depliables' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param) VALUES (0, 'opac', 'bannette_notices_depliables', '1', 'Affichage dépliable des notices dans les bannettes \n 0 : Non \n 1 : Oui', 'l_dsi')";
			echo traite_rqt($rqt,"insert opac_bannette_notices_depliables='1' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='bannette_nb_liste' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param) VALUES (0, 'opac', 'bannette_nb_liste', '0', 'Nbre de notices par bannettes en affichage de la liste des bannettes \n 0 Toutes \n N : maxi N\n -1 : aucune', 'l_dsi')";
			echo traite_rqt($rqt,"insert opac_bannette_nb_liste='0' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='dsi_active' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param) VALUES (0, 'opac', 'dsi_active', '0', 'DSI, bannettes accessibles par l\'OPAC ? \n 0 : Non \n 1 : Oui', 'l_dsi')";
			echo traite_rqt($rqt,"insert opac_dsi_active='0' into parametres");
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'mailretard' and sstype_param='2after_list' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'mailretard','2after_list','Nous vous remercions de prendre rapidement contact par téléphone au 00 00 00 00 00 ou de nous répondre par mail à mail@mail.mail pour étudier la possibilité de prolonger ces prêts ou de ramener les ouvrages concernés.','Texte apparaissant après la liste des ouvrages en retard dans le mail','',0)" ;
			echo traite_rqt($rqt,"insert mailretard2 into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'mailretard' and sstype_param='2before_list' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'mailretard','2before_list','Sauf erreur de notre part, vous avez toujours en votre possession le ou les ouvrage(s) suivant(s) dont la durée de prêt est aujourd\'hui dépassée :','Texte apparaissant avant la liste des ouvrages en retard dans le mail de relance de retard','',0)" ;
			echo traite_rqt($rqt,"insert mailretard2 into parametres") ;
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'mailretard' and sstype_param='2fdp' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'mailretard','2fdp','Le responsable de la Bibliothèque $biblio_name.','Signataire du mail de relance de retard','',0)" ;
			echo traite_rqt($rqt,"insert mailretard2 into parametres") ;
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'mailretard' and sstype_param='2madame_monsieur' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'mailretard','2madame_monsieur','Madame, Monsieur','Entête du mail','',0)" ;
			echo traite_rqt($rqt,"insert mailretard2 into parametres") ;
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'mailretard' and sstype_param='2objet' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'mailretard','2objet','Documents en retard','Objet du mail de relance de retard','',0)" ;
			echo traite_rqt($rqt,"insert mailretard2 into parametres") ;
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'mailretard' and sstype_param='3after_list' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'mailretard','3after_list','Nous vous remercions de prendre rapidement contact par téléphone au 00 00 00 00 00 ou de nous répondre par mail à mail@mail.mail pour étudier la possibilité de prolonger ces prêts ou de ramener les ouvrages concernés.','Texte apparaissant après la liste des ouvrages en retard dans le mail','',0)" ;
			echo traite_rqt($rqt,"insert mailretard3 into parametres") ;
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'mailretard' and sstype_param='3before_list' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'mailretard','3before_list','Sauf erreur de notre part, vous avez toujours en votre possession le ou les ouvrage(s) suivant(s) dont la durée de prêt est aujourd\'hui dépassée :','Texte apparaissant avant la liste des ouvrages en retard dans le mail de relance de retard','',0)" ;
			echo traite_rqt($rqt,"insert mailretard3 into parametres") ;
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'mailretard' and sstype_param='3fdp' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'mailretard','3fdp','Le responsable de la Bibliothèque $biblio_name.','Signataire du mail de relance de retard','',0)" ;
			echo traite_rqt($rqt,"insert mailretard3 into parametres") ;
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'mailretard' and sstype_param='3madame_monsieur' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'mailretard','3madame_monsieur','Madame, Monsieur','Entête du mail','',0)" ;
			echo traite_rqt($rqt,"insert mailretard3 into parametres") ;
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'mailretard' and sstype_param='3objet' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'mailretard','3objet','Documents en retard','Objet du mail de relance de retard','',0)" ;
			echo traite_rqt($rqt,"insert mailretard3 into parametres") ;
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'mailretard' and sstype_param='3before_recouvrement' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'mailretard','3before_recouvrement','Sans nouvelles de votre part dans les sept jours, nous nous verrons contraints de déléguer au trésor public le recouvrement des ouvrages suivants :','Texte avant la liste des ouvrages en recouvrement','',0)" ;
			echo traite_rqt($rqt,"insert mailretard3 into parametres") ;
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'mailretard' and sstype_param='priorite_email' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'mailretard','priorite_email','1','Priorité des lettres de retard lors des relances :\n 0 : Lettre seule \n 1 : Mail, à défaut lettre\n 2 : Mail ET lettre','',0)" ;
			echo traite_rqt($rqt,"insert mailretard_priorite_email into parametres") ;
			}

		$rqt = "CREATE TABLE recouvrements ( empr_id int(10) unsigned NOT NULL default 0, id_expl int(10) unsigned NOT NULL default 0, date_rec date NOT NULL default '0000-00-00', libelle varchar(255) default NULL, montant decimal(16,2) default '0.00') " ; 
		echo traite_rqt($rqt,"CREATE TABLE recouvrements") ;

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.31");
		break;	
	
	case "v3.31": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE recouvrements ADD recouvr_id INT( 16 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST " ;
		echo traite_rqt($rqt,"ALTER TABLE recouvrements ADD recouvr_id PK") ;
		
		$rqt = "ALTER TABLE empr ADD date_fin_blocage date " ;
		echo traite_rqt($rqt,"ALTER TABLE empr ADD date_fin_blocage date") ;
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='import_modele_lecteur' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pmb','import_modele_lecteur','','Modèle d\'import des lecteurs','',0)" ;
			echo traite_rqt($rqt,"insert pmb_import_modele_lecteur into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='blocage_retard' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pmb','blocage_retard','0','Bloquer le prêt d\'une durée équivalente au retard ? 0=non, 1=oui','',0)" ;
			echo traite_rqt($rqt,"insert pmb_blocage_retard into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='blocage_delai' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pmb','blocage_delai','7','Délai à partir duquel le retard est pris en compte','',0)" ;
			echo traite_rqt($rqt,"insert pmb_blocage_delai into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='blocage_max' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pmb','blocage_max','60','Nombre maximum de jours bloqués (0 = pas de limite)','',0)" ;
			echo traite_rqt($rqt,"insert pmb_blocage_max into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='blocage_coef' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pmb','blocage_coef','1','Coefficient de proportionnalité des jours de retard pour le blocage','',0)" ;
			echo traite_rqt($rqt,"insert pmb_blocage_coef into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='blocage_retard_force' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'pmb','blocage_retard_force','1','1 = Le prêt peut-être forcé lors d\'un blocage du compte, 2 = Pas de forçage possible','',0)" ;
			echo traite_rqt($rqt,"insert pmb_blocage_retard_force into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='etagere_order' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'opac','etagere_order',' name ','Tri des étagères dans l\'écran d\'accueil, \n name = par nom\n name DESC = par nom décroissant ','j_etagere',0)" ;
			echo traite_rqt($rqt,"insert opac_etagere_order into parametres");
			}
		$rqt = "ALTER TABLE notice_statut ADD expl_visible_opac_abon INT(0) UNSIGNED DEFAULT '0' NOT NULL , ADD explnum_visible_opac INT(1) UNSIGNED DEFAULT '1' NOT NULL , ADD explnum_visible_opac_abon INT(1) UNSIGNED DEFAULT '0' NOT NULL " ;
		echo traite_rqt($rqt,"ALTER TABLE notice_statut ADD visible...");
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.32");
		break;	
	
	case "v3.32": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "alter table pret add niveau_relance integer(1) not null default 0 " ;
		echo traite_rqt($rqt,"alter table pret add niveau_relance");
		$rqt = "alter table pret add date_relance date default '0000-00-00' " ;
		echo traite_rqt($rqt,"alter table pret add date_relance ");
		$rqt = "alter table pret add printed integer(1) not null default 0 " ;
		echo traite_rqt($rqt,"alter table pret add printed ");

		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.33");
		break;	
	
	case "v3.33": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE empr CHANGE empr_year empr_year VARCHAR( 255 ) NOT NULL default '' ";
		echo traite_rqt($rqt,"alter table empr empr_year varchar 255 ");
		$rqt = "ALTER TABLE empr_categ CHANGE libelle libelle VARCHAR( 255 ) NOT NULL default '' ";
		echo traite_rqt($rqt,"alter table empr_categ libelle varchar 255 ");
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.34");
		break;	
	
	case "v3.34": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='book_pics_show' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'book_pics_show', '0', 'Affichage des couvertures de livres en gestion\n 1: oui  \n 0: non') " ;
			echo traite_rqt($rqt,"insert pmb_book_pics_show... into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='book_pics_url' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'book_pics_url', '', 'URL des vignettes des notices, dans le chemin fourni, !!isbn!! sera remplacé par le code ISBN ou EAN de la notice purgé de tous les tirets ou points. \n exemple : http://www.monsite/opac/images/vignettes/!!isbn!!.jpg')";
			echo traite_rqt($rqt,"insert pmb_book_pics_url='' into parametres");
			}
		$rqt = "ALTER TABLE empr CHANGE empr_year empr_year int( 4 ) unsigned NOT NULL default 0 ";
		echo traite_rqt($rqt,"alter table empr empr_year int 4 ");
		
		$rqt = "ALTER TABLE bannettes ADD entete_mail TEXT NOT NULL default '' AFTER comment_public ";
		echo traite_rqt($rqt,"ALTER TABLE bannettes ADD entete_mail TEXT"); 

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='opac_url' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'opac_url', './opac_css/', 'URL de l\'OPAC vu depuis la partie gestion, par défaut ./opac_css/')";
			echo traite_rqt($rqt,"insert pmb_opac_url='./opac_css/' into parametres");
			}
		$rqt = "alter table notices change niveau_hierar niveau_hierar char(1) not null default 'O' ";
		echo traite_rqt($rqt,"alter table notices change niveau_hierar ");
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.35");
		break;	
	
	case "v3.35": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "update users set deflt2docs_location='".$deflt2docs_location."' where deflt2docs_location='0' ";
		echo traite_rqt($rqt,"update users deflt2docs_location where 0 ");
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.36");
		break;	
	
	case "v3.36": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "update caddie_procs set type='SELECT' WHERE type='POINTA' ";
		echo traite_rqt($rqt,"update caddie_procs set type=select where pointa ");
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='resa_popup' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0, 'opac', 'resa_popup', '1', 'Demande de connexion sous forme de popup ? :\n 0 : Non\n 1 : Oui','a_general',0)";
			echo traite_rqt($rqt,"insert opac_resa_popup='1' into parametres");
			}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.37");
		break;	
	
	case "v3.37": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE empr ADD INDEX empr_date_adhesion ( empr_date_adhesion )";
		echo traite_rqt($rqt,"alter empr add index empr_date_adhesion");
		$rqt = "ALTER TABLE empr ADD INDEX empr_date_expiration ( empr_date_expiration )";
		echo traite_rqt($rqt,"alter empr add index empr_date_expiration");
		$rqt = "ALTER TABLE pret DROP PRIMARY KEY ";
		echo traite_rqt($rqt,"alter pret drop primary key "); 
		$rqt = "ALTER TABLE pret ADD PRIMARY KEY ( pret_idexpl )";
		echo traite_rqt($rqt,"alter pret change primary key pret_idexpl"); 
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.38");
		break;	
	
	case "v3.38": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='vignette_x' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'vignette_x', '100', 'Largeur de la vignette créée pour un exemplaire numérique image')";
			echo traite_rqt($rqt,"insert pmb_vignette_x='100' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='vignette_y' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'vignette_y', '100', 'Hauteur de la vignette créée pour un exemplaire numérique image')";
			echo traite_rqt($rqt,"insert pmb_vignette_y='100' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='vignette_imagemagick' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'vignette_imagemagick', '', 'Chemin de l\'exécutable ImageMagick (/usr/bin/imagemagick par exemple)')";
			echo traite_rqt($rqt,"insert pmb_vignette_imagemagick='' into parametres");
			}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.39");
		break;	
	
	case "v3.39": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_rss_browser' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'opac', 'show_rss_browser', '0', 'Affichage des flux RSS du catalogue en page d''accueil OPAC 1: oui  ou 0: non', 'f_modules', 0)";
			echo traite_rqt($rqt,"insert opac_show_rss_browser='0' into parametres");
			}
		$rqt = "CREATE TABLE rss_content (  rss_id int(10) unsigned NOT NULL default '0',  rss_content longblob not null default'',  rss_last timestamp(14) NOT NULL, PRIMARY KEY  (rss_id))" ; 
		echo traite_rqt($rqt,"create table rss_content");

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.40");
		break;	
	
	case "v3.40": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='mail_methode' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'mail_methode', 'php', 'Méthode d\'envoi des mails : \n php : fonction mail() de php\n smtp,hote:port,auth,user,pass : en smtp, mettre O ou 1 pour l\'authentification... ')";
			echo traite_rqt($rqt,"insert pmb_mail_methode='php' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='mail_methode' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'opac', 'mail_methode', 'php', 'Méthode d''envoi des mails dans l''opac : \n php : fonction mail() de php\n smtp,hote:port,auth,user,pass : en smtp, mettre O ou 1 pour l''authentification...', 'a_general', 0)";
			echo traite_rqt($rqt,"insert opac_mail_methode='php' into parametres");
			}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.41");
		break;	
	
	case "v3.41": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE empr CHANGE empr_cb empr_cb VARCHAR( 255 ) NULL DEFAULT NULL ";
		echo traite_rqt($rqt,"empr_cb varchar 255");
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.42");
		break;	
	
	case "v3.42": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "update users set rights=rights+128 where rights<128";
		echo traite_rqt($rqt,"update users rights+128");
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.43");
		break;	
	
	case "v3.43": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='search_show_typdoc' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'opac', 'search_show_typdoc', '1', 'Affichage de la restriction par type de document pour les recherches en page d\'accueil','c_recherche')";
			echo traite_rqt($rqt,"insert opac_search_show_typdoc='1' into parametres");
			}
		$rqt = "ALTER TABLE notices CHANGE n_gen n_gen TEXT NOT NULL default '' ";
		echo traite_rqt($rqt,"alter notices n_gen TEXT ");
		$rqt = "ALTER TABLE notices CHANGE n_contenu n_contenu TEXT NOT NULL default '' ";
		echo traite_rqt($rqt,"alter notices n_contenu TEXT ");
		$rqt = "ALTER TABLE notices CHANGE n_resume n_resume TEXT NOT NULL default '' ";
		echo traite_rqt($rqt,"alter notices n_resume TEXT ");
		$rqt = "ALTER TABLE notices CHANGE index_l index_l TEXT NOT NULL default '' ";
		echo traite_rqt($rqt,"alter notices index_l TEXT ");
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.44");
		break;	
	
	case "v3.44": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE notices CHANGE index_matieres index_matieres TEXT NOT NULL default '' ";
		echo traite_rqt($rqt,"alter notices index_matieres TEXT ");
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='verif_on_line' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'verif_on_line', '0', 'Dans le menu Administration > Outils > Maj Base : vérification d\'une version plus récente de PMB en ligne ? \n0 : non : si vous n\'êtes pas connecté à internet \n 1 : Oui : si vous avez une connexion à internet')";
			echo traite_rqt($rqt,"insert pmb_verif_on_line='0' into parametres");
			}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.45");
		break;	
	
	case "v3.45": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE pret_archive ADD arc_expl_section INT( 5 ) UNSIGNED DEFAULT '0' NOT NULL AFTER arc_expl_owner " ;
		echo traite_rqt($rqt,"alter pret_archive add arc_expl_section ");
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_languages' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'opac', 'show_languages', '1 fr_FR,it_IT,en_UK,nl_NL,oc_FR', 'Afficher la liste déroulante de sélection de la langue ? \n 0 : Non \n 1 : Oui \nFaire suivre d\'un espace et des codes des langues possibles séparées par des virgules : fr_FR,it_IT,en_UK,nl_NL,oc_FR', 'a_general', 0)";
			echo traite_rqt($rqt,"insert opac_show_languages='1 fr_FR,it_IT,en_UK,nl_NL,oc_FR' into parametres");
			}
			// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.46");
		break;	
	case "v3.46": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='pdf_font' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'pmb', 'pdf_font', 'Helvetica', 'Police de caractères à chasse variable pour les éditions en pdf - Police Arial', '', 0)";
			echo traite_rqt($rqt,"insert pmb_pdf_font='Helvetica' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='pdf_fontfixed' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'pmb', 'pdf_fontfixed', 'Courier', 'Police de caractères à chasse fixe pour les éditions en pdf - Police Courier', '', 0)";
			echo traite_rqt($rqt,"insert pmb_pdf_fontfixed='Courier' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'z3950' and sstype_param='debug' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'z3950', 'debug', '0', 'Debugage (export fichier) des notices lues en Z3950 \n 0: Non \n 1: 0ui', '', 0)";
			echo traite_rqt($rqt,"insert z3950_debug=0 into parametres");
			}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.47");
		break;	
	case "v3.47": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE empr CHANGE empr_prof empr_prof VARCHAR( 255 ) not NULL DEFAULT '' ";
		echo traite_rqt($rqt,"empr_prof varchar 255");
		$rqt = "ALTER TABLE exemplaires CHANGE expl_cb expl_cb VARCHAR( 50 ) not NULL DEFAULT '' ";
		echo traite_rqt($rqt,"expl_cb varchar 50 ");
		$rqt = "update exemplaires set expl_cote=trim(expl_cote)  ";
		echo traite_rqt($rqt,"trim(expl_cote) ");
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.48");
		break;	
	case "v3.48": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='nb_lastnotices' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, gestion) VALUES (0, 'pmb', 'nb_lastnotices', '10', 'Nombre de dernières notices affichées en Catalogue - Dernières notices', 0)";
			echo traite_rqt($rqt,"insert pmb_nb_lastnotices='10' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='recouvrement_auto' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, gestion) VALUES (0, 'pmb', 'recouvrement_auto', '1', 'Par défaut passage en recouvrement proposé en gestion des relances si niveau=3 et devrait être en 4: \n 1: Oui, recouvrement proposé par défaut \n 0: Ne rien faire par défaut ', 0)";
			echo traite_rqt($rqt,"insert pmb_recouvrement_auto='1' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_dernieresnotices_nb' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'opac', 'show_dernieresnotices_nb', '10', 'Nombre de dernières notices affichées en OPAC lors de l\'activation du paramètre show_dernieresnotices', 'f_modules', 0)";
			echo traite_rqt($rqt,"insert opac_show_dernieresnotices_nb='10' into parametres");
			}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v3.49");
		break;	
	case "v3.49": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='keyword_sep' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'keyword_sep', ' ', 'Séparateur des mots clés dans la partie indexation libre, espace ou ; ou , ou ...')";
			echo traite_rqt($rqt,"insert pmb_keyword_sep=' ' into parametres");
			}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.00");
		break;
		
	default:
		include("$include_path/messages/help/$lang/alter.txt");
		break;
	}
