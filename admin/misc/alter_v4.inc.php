<?php
// +-------------------------------------------------+
//  2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: alter_v4.inc.php,v 1.561.2.5 2011-09-15 21:52:00 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

settype ($action,"string");

mysql_query("set names latin1 ", $dbh);

switch ($action) {
	case "lancement":
		switch ($version_pmb_bdd) {
			case "v3.49":
			case "v3.50":
			case "v3.51":
				$maj_a_faire = "v4.00";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v4.00":
				$maj_a_faire = "v4.01";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v4.01":
				$maj_a_faire = "v4.02";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v4.02":
				$maj_a_faire = "v4.03";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v4.03":
				$maj_a_faire = "v4.04";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v4.04":
				$maj_a_faire = "v4.05";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v4.05":
				$maj_a_faire = "v4.06";
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v4.06":
				$maj_a_faire = "v4.07";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v4.07":
				$maj_a_faire = "v4.08";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;
			case "v4.08":
				$maj_a_faire = "v4.09";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.09":
				$maj_a_faire = "v4.10";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.10":
				$maj_a_faire = "v4.11";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.11":
				$maj_a_faire = "v4.12";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.12":
				$maj_a_faire = "v4.13";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.13":
				$maj_a_faire = "v4.14";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.14":
				$maj_a_faire = "v4.15";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.15":
				$maj_a_faire = "v4.16";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.16":
				$maj_a_faire = "v4.17";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.17":
				$maj_a_faire = "v4.18";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.18":
				$maj_a_faire = "v4.19";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.19":
				$maj_a_faire = "v4.20";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.20":
				$maj_a_faire = "v4.21";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.21":
				$maj_a_faire = "v4.22";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.22":
				$maj_a_faire = "v4.23";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.23":
				$maj_a_faire = "v4.24";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.24":
				$maj_a_faire = "v4.25";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.25":
				$maj_a_faire = "v4.26";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.26":
				$maj_a_faire = "v4.27";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.27":
				$maj_a_faire = "v4.28";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.28":
				$maj_a_faire = "v4.29";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.29":
				$maj_a_faire = "v4.30";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.30":
				$maj_a_faire = "v4.31";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.31":
				$maj_a_faire = "v4.32";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.32":
				$maj_a_faire = "v4.33";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.33":
				$maj_a_faire = "v4.34";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.34":
				$maj_a_faire = "v4.35";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.35":
				$maj_a_faire = "v4.36";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.36":
				$maj_a_faire = "v4.37";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.37":
				$maj_a_faire = "v4.38";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.38":
				$maj_a_faire = "v4.39";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.39":
				$maj_a_faire = "v4.40";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.40":
				$maj_a_faire = "v4.41";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.41":
				$maj_a_faire = "v4.42";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.42":
				$maj_a_faire = "v4.43";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.43":
				$maj_a_faire = "v4.44";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.44":
				$maj_a_faire = "v4.45";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.45":
				$maj_a_faire = "v4.46";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.46":
				$maj_a_faire = "v4.47";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.47":
				$maj_a_faire = "v4.48";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.48":
				$maj_a_faire = "v4.49";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.49":
				$maj_a_faire = "v4.50";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.50":
				$maj_a_faire = "v4.51";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.51":
				$maj_a_faire = "v4.52";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.52":
				$maj_a_faire = "v4.53";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.53":
				$maj_a_faire = "v4.54";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.54":
				$maj_a_faire = "v4.55";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.55":
				$maj_a_faire = "v4.56";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.56":
				$maj_a_faire = "v4.57";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.57":
				$maj_a_faire = "v4.58";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.58":
				$maj_a_faire = "v4.59";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.59":
				$maj_a_faire = "v4.60";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.60":
				$maj_a_faire = "v4.61";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.61":
				$maj_a_faire = "v4.62";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.62":
				$maj_a_faire = "v4.63";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.63":
				$maj_a_faire = "v4.64";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.64":
				$maj_a_faire = "v4.65";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.65":
				$maj_a_faire = "v4.66";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.66":
				$maj_a_faire = "v4.67";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.67":
				$maj_a_faire = "v4.68";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.68":
				$maj_a_faire = "v4.69";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.69":
				$maj_a_faire = "v4.70";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.70":
				$maj_a_faire = "v4.71";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.71":
				$maj_a_faire = "v4.72";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.72":
				$maj_a_faire = "v4.73";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.73":
				$maj_a_faire = "v4.74";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.74":
				$maj_a_faire = "v4.75";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.75":
				$maj_a_faire = "v4.76";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.76":
				$maj_a_faire = "v4.77";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.77":
				$maj_a_faire = "v4.78";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.78":
				$maj_a_faire = "v4.79";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.79":
				$maj_a_faire = "v4.80";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.80":
				$maj_a_faire = "v4.81";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.81":
				$maj_a_faire = "v4.82";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.82":
				$maj_a_faire = "v4.83";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.83":
				$maj_a_faire = "v4.84";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.84":
				$maj_a_faire = "v4.85";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.85":
				$maj_a_faire = "v4.86";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.86":
			case "v4.87":
			case "v4.88":
			case "v4.89":
				$maj_a_faire = "v4.90";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.90":
				$maj_a_faire = "v4.91";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.91":
				$maj_a_faire = "v4.92";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.92":
				$maj_a_faire = "v4.93";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.93":
				$maj_a_faire = "v4.94";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.94":
				$maj_a_faire = "v4.95";			
				echo "<strong><font color='#FF0000'>".$msg[1804]."$maj_a_faire !</font></strong><br />";
				echo form_relance ($maj_a_faire);
				break;				
			case "v4.95":
				echo "<strong><font color='#FF0000'>".$msg[1805].$version_pmb_bdd." !</font></strong><br />";
				break;
			
			default:
				echo "<strong><font color='#FF0000'>".$msg[1806].$version_pmb_bdd." !</font></strong><br />";
				break;
			}
		break;	

	
	case "v4.00": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "CREATE TABLE thesaurus (
				id_thesaurus INT( 3 ) UNSIGNED NOT NULL AUTO_INCREMENT,
				libelle_thesaurus VARCHAR( 255 ) DEFAULT '' NOT NULL,
				langue_defaut VARCHAR (5) NOT NULL DEFAULT 'fr_FR',
				active CHAR( 1 ) DEFAULT '1' NOT NULL,
				opac_active CHAR( 1 ) DEFAULT '1' NOT NULL,
				num_noeud_racine INT( 9 ) UNSIGNED DEFAULT 0 NOT NULL,
				PRIMARY KEY ( id_thesaurus ),
				UNIQUE ( libelle_thesaurus )
				)  ";	
		echo traite_rqt($rqt, "create table thesaurus");
		
		$rqt = "CREATE TABLE noeuds (
				id_noeud INT( 9 ) UNSIGNED NOT NULL AUTO_INCREMENT,
				autorite VARCHAR( 255 ) DEFAULT '' NOT NULL ,
				num_parent INT( 9 ) UNSIGNED DEFAULT 0 NOT NULL ,
				num_renvoi_voir INT( 9 ) UNSIGNED DEFAULT 0 NOT NULL ,
				visible CHAR( 1 ) DEFAULT '1' NOT NULL ,
				num_thesaurus INT( 3 ) UNSIGNED DEFAULT 0 NOT NULL ,
				PRIMARY KEY ( id_noeud ) ,
				KEY num_parent ( num_parent ),
				KEY num_thesaurus ( num_thesaurus ),
				KEY autorite (autorite)
				
				)  ";		
		echo traite_rqt($rqt, "create table noeuds");

		$rqt = "CREATE TABLE voir_aussi (
				num_noeud_orig INT( 9 ) UNSIGNED DEFAULT '0' NOT NULL ,
				num_noeud_dest INT( 9 ) UNSIGNED DEFAULT '0' NOT NULL ,
				langue VARCHAR( 5 ) DEFAULT '' NOT NULL ,
				comment_voir_aussi TEXT NOT NULL ,
				PRIMARY KEY ( num_noeud_orig , num_noeud_dest , langue )
				)  ";
		echo traite_rqt($rqt, "create table voir_aussi");
		
		$rqt = "ALTER TABLE categories RENAME old_categories ";
		echo traite_rqt($rqt, "rename categories old_categories");

		$rqt = "DROP TABLE IF EXISTS categories";
		echo traite_rqt($rqt, "drop table categories");	
		$rqt = "CREATE TABLE categories (
				num_noeud INT( 9 ) UNSIGNED DEFAULT '0' NOT NULL ,
				langue VARCHAR( 5 ) DEFAULT 'fr_FR' NOT NULL ,
				libelle_categorie TEXT NOT NULL ,
				note_application TEXT NOT NULL ,
				comment_public TEXT NOT NULL ,
				comment_voir TEXT NOT NULL ,
				index_categorie TEXT NOT NULL ,
				PRIMARY KEY ( num_noeud , langue ) 
				)  ";
		echo traite_rqt($rqt, "create table categories");	

		$rqt = "ALTER TABLE notices_categories RENAME old_notices_categories ";
		echo traite_rqt($rqt, "rename notices_categories old_notices_categories");

		$rqt = "DROP TABLE IF EXISTS notices_categories";
		echo traite_rqt($rqt, "drop table notices_categories");	
		$rqt = "CREATE TABLE notices_categories (
				notcateg_notice INT( 9 ) UNSIGNED DEFAULT '0' NOT NULL ,
				num_noeud INT( 9 ) UNSIGNED DEFAULT '0' NOT NULL ,
				num_vedette INT( 3 ) UNSIGNED DEFAULT '0' NOT NULL ,
				ordre_vedette INT( 3 ) UNSIGNED DEFAULT '1' NOT NULL ,
				PRIMARY KEY ( notcateg_notice , num_noeud , num_vedette ),
				KEY num_noeud (num_noeud)
				)  ";
		echo traite_rqt($rqt, "create table notices_categories");
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.01");
		break;
	
	case "v4.01": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "INSERT INTO thesaurus SET  
				id_thesaurus = '1', 
				libelle_thesaurus = 'Thesaurus n°1',
				langue_defaut = '".$lang."',
				active = '1',
				opac_active = '1',
				num_noeud_racine = '1'";
		echo traite_rqt($rqt, "insert default thesaurus");

		$rqt = "INSERT INTO noeuds SET
				id_noeud = '1', 
				autorite = 'TOP',
				num_parent = '0',
				num_renvoi_voir = '0',
				visible = '0',
				num_thesaurus = '1'";
		echo traite_rqt($rqt, "insert default thesaurus root node");

		$rqt = "INSERT INTO noeuds (
					id_noeud,
    				autorite,
    				num_parent,
    				num_renvoi_voir,
    				visible,
    				num_thesaurus) 
  				SELECT 	
    				categ_id+1,
    				IF (LEFT(categ_libelle, 1) = '~', 'ORPHELINS', categ_id), 
    				categ_parent+1,
    				IF (categ_see = '0', '0', categ_see+1),
    				IF (LEFT(categ_libelle, 1) = '~', '0', '1'),
    				'1'
				FROM old_categories";	
		echo traite_rqt($rqt, "insert thesaurus nodes");
		
		//Vérifie si un thésaurus était présent, sinon, crée les éléments nécessaires
		$q = "select count(1) from noeuds where autorite = 'ORPHELINS'";
		if (mysql_result(mysql_query($q, $dbh), 0, 0) == 0) {
		$rqt = "INSERT INTO noeuds SET
				autorite = 'ORPHELINS',
				num_parent = '1',
				num_renvoi_voir = '0',
				visible = '0',
				num_thesaurus = '1'";
				echo traite_rqt($rqt, "insert orphans thesaurus node");				
				
		$rqt = "INSERT INTO categories (
					num_noeud,
					langue,
					libelle_categorie,
					index_categorie )
				SELECT
					MAX(noeuds.id_noeud),
					'".$lang."',
					'~termes orphelins',
					' termes orphelins '
				FROM noeuds ";
		echo traite_rqt($rqt, "insert orphans thesaurus category");
				
		}
			
		$q = "select count(1) from noeuds where autorite = 'NONCLASSES'";
		if (mysql_result(mysql_query($q, $dbh), 0, 0) == 0) {
			$rqt = "INSERT INTO noeuds SET
					autorite = 'NONCLASSES',
					num_parent = '1',
					num_renvoi_voir = '0',
					visible = '0',
					num_thesaurus = '1'";
			echo traite_rqt($rqt, "insert not classifieds thesaurus node");
			
			$rqt = "INSERT INTO categories (
						num_noeud,
						langue,
						libelle_categorie,
						index_categorie )
					SELECT
						MAX(noeuds.id_noeud),
						'".$lang."',
						'~termes non classés',
						' termes non classes '
					FROM noeuds ";
			echo traite_rqt($rqt, "insert not classifieds thesaurus category");
		}
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.02");
		break;
	
	case "v4.02": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "INSERT INTO voir_aussi (
    				num_noeud_orig,
    				num_noeud_dest,
    				langue,
    				comment_voir_aussi)
  				SELECT			
    				categ_assoc_categid+1 ,
    				categ_assoc_categassoc+1,
    				'".$lang."',
    				''
				  FROM categ_assoc";
		echo traite_rqt($rqt, "insert records in voir_aussi");
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.03");
		break;
		
	case "v4.03": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "INSERT INTO categories (
    				num_noeud,
    				langue,
    				libelle_categorie,
  				  	note_application,
    				comment_public,
    				comment_voir,
    				index_categorie)
  				SELECT
    				categ_id+1,
    				'".$lang."',
    				categ_libelle,  
    				categ_comment,
   					'',
    				'',
    				index_categorie
  				FROM old_categories";
		echo traite_rqt($rqt, "insert categories");
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.04");
		break;
		
	case "v4.04": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "INSERT INTO notices_categories (
					notcateg_notice,
					num_noeud,
					num_vedette,
					ordre_vedette )
				SELECT
					old_notices_categories.notcateg_notice,
					old_categories.categ_id+1,
					'',
					'1'
				FROM old_notices_categories, old_categories
				WHERE old_notices_categories.notcateg_categorie = old_categories.categ_id";
		echo traite_rqt($rqt, "insert records in notices_categories");
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.05");
		break;
		
	case "v4.05": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		echo traite_rqt(" select 1 from users", "Old tables from catégories are achived, they should be dropped later");
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.06");
		break;

	case "v4.06": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'thesaurus' and sstype_param='mode_pmb' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'thesaurus', 'mode_pmb', '0', 'Niveau d\'utilisation des thésaurus.\n 0 : Un seul thésaurus par défaut.\n 1 : Choix du thésaurus possible.', '',0) ";
			echo traite_rqt($rqt, "insert thesaurus_mode_pmb=0 into parameters");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'thesaurus' and sstype_param='defaut' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'thesaurus', 'defaut', '1', 'Identifiant du thésaurus par défaut.', '',0) ";
			echo traite_rqt($rqt, "insert thesaurus_defaut=0 into parameters");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'thesaurus' and sstype_param='liste_trad' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'thesaurus', 'liste_trad', '".$lang."', 'Liste des langues affichées dans les thésaurus.\n(ex : fr_FR,en_UK,...,ar)', '',0) ";
			echo traite_rqt($rqt, "insert thesaurus_liste_trad=fr_FR into parameters");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='thesaurus' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'opac', 'thesaurus', '0', 'Niveau d\'utilisation des thésaurus.\n 0 : Un seul thésaurus par défaut.\n 1 : Choix du thésaurus possible.', 'm_thesaurus',0) ";
			echo traite_rqt($rqt, "insert opac_thesaurus=0 into parameters");
			}

		$rqt = "ALTER TABLE users ADD deflt_thesaurus INT(3) UNSIGNED DEFAULT '1' NOT NULL ";
		echo traite_rqt($rqt, "add deflt_thesaurus in table users");
		
		$rqt = "ALTER TABLE bannettes ADD num_panier INT( 8 ) UNSIGNED NOT NULL DEFAULT 0 ";
		echo traite_rqt($rqt, "alter bannettes add num_panier ");
		
		$rqt = "CREATE TABLE docsloc_section (num_section int(5) unsigned NOT NULL default 0, num_location int(5) unsigned NOT NULL default 0, PRIMARY KEY  (num_section, num_location)) ";
		echo traite_rqt($rqt, "create table docsloc_section ");
		
		$rqt = "replace into docsloc_section (num_section, num_location) (select expl_section, expl_location from exemplaires group by expl_location, expl_section) " ;
		echo traite_rqt($rqt, "ajout visibilité des sections par localisation, vérifiez toutes vos sections ");
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.07");
		break;

	case "v4.07": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE users ADD value_prefix_cote tinyblob NOT NULL DEFAULT '' " ;
		echo traite_rqt($rqt, "add user param deflt cote");
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.08");
		break;

	case "v4.08": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='active' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'acquisition', 'active', '0', 'Module acquisitions activé.\n 0 : Non.\n 1 : Oui.', '',0) ";
			echo traite_rqt($rqt, "insert acquisition_active=0 into parameters");
		}
		
		$rqt = "ALTER TABLE users CHANGE rights rights INT(8) UNSIGNED DEFAULT '0' NOT NULL ";
		echo traite_rqt($rqt, "modify user rights size");

		$rqt = "CREATE TABLE entites (
 					id_entite int(5) unsigned NOT NULL auto_increment,
 				 	type enum('0','1') NOT NULL default '0',
					num_bibli int(5) unsigned NOT NULL default '0',
				 	raison_sociale varchar(255) NOT NULL default '',
				 	commentaires text,
 				 	siret varchar(25) NOT NULL default '',
				 	naf varchar(5) NOT NULL default '',
  					rcs varchar(25) NOT NULL default '',
  					tva varchar(25) NOT NULL default '',
  					num_cp_client varchar(25) NOT NULL default '',
				  	num_cp_compta varchar(25) NOT NULL default '',
				  	site_web varchar(100) NOT NULL default '',
				 	logo varchar(255) NOT NULL default '',
					autorisations mediumtext NOT NULL default '',
					PRIMARY KEY  (id_entite),
					KEY raison_sociale (raison_sociale)
					)  ";
		echo traite_rqt($rqt, "create table entites");

		$rqt = "CREATE TABLE coordonnees (
  				id_contact int(8) unsigned NOT NULL auto_increment,
				type int(1) unsigned NOT NULL default '0',
  				num_entite int(5) unsigned NOT NULL default '0',
  				contact varchar(255) NOT NULL default '',
  				adr1 varchar(255) NOT NULL default '',
  				adr2 varchar(255) NOT NULL default '',
  				cp varchar(15) NOT NULL default '',
  				ville varchar(100) NOT NULL default '',
  				etat varchar(100) NOT NULL default '',
  				pays varchar(100) NOT NULL default '',
  				tel1 varchar(100) NOT NULL default '',
  				tel2 varchar(100) NOT NULL default '',
  				fax varchar(100) NOT NULL default '',
  				email varchar(100) NOT NULL default '',
  				commentaires text,
  				PRIMARY KEY  (id_contact)
				) ";
		echo traite_rqt($rqt, "create table coordonnees");

		$rqt = "CREATE TABLE types_produits (
  				id_produit int(8) unsigned NOT NULL auto_increment,
  				libelle varchar(255) NOT NULL default '',
  				num_cp_compta varchar(25) NOT NULL default '0',
   				num_tva_achat varchar(25) NOT NULL default '0',
  				PRIMARY KEY  (id_produit),
  				KEY libelle (libelle)
				)  ";
		echo traite_rqt($rqt, "create table types_produits");

		$rqt = "CREATE TABLE offres_remises (
  				num_fournisseur int(5) unsigned NOT NULL default '0',
  				num_produit int(8) unsigned NOT NULL default '0',
  				remise float(4,2) unsigned NOT NULL default '00.00',
  				condition_remise text,
  				PRIMARY KEY  (num_fournisseur,num_produit)
				)  ";
        
		echo traite_rqt($rqt, "create table offres_remises");

		$rqt = "CREATE TABLE tva_achats (
  				id_tva int(8) unsigned NOT NULL auto_increment,
  				taux_tva float(4,2) unsigned NOT NULL default '00.00',
  				num_cp_compta varchar(25) NOT NULL default '0',
  				PRIMARY KEY  (id_tva)
				)  ";
		echo traite_rqt($rqt, "create table tva_achats");

		$rqt = "CREATE TABLE exercices (
  				id_exercice int(8) unsigned NOT NULL auto_increment,
  				num_entite int(5) unsigned NOT NULL default '0',
 			 	libelle varchar(255) NOT NULL default '',
  				date_debut date NOT NULL default '2006-01-01',
  				date_fin date NOT NULL default '2006-01-01',
  				PRIMARY KEY  (id_exercice)
				)  ";
		echo traite_rqt($rqt, "create table exercices");

		$rqt = "CREATE TABLE paiements (
  				id_paiement int(8) unsigned NOT NULL auto_increment,
  				libelle varchar(255) NOT NULL default '',
  				PRIMARY KEY  (id_paiement)
				)  ";
		echo traite_rqt($rqt, "create table paiements");

		$rqt = "CREATE TABLE frais (
  				id_frais int(8) unsigned NOT NULL auto_increment,
  				libelle varchar(255) NOT NULL default '',
				condition_frais text NOT NULL,
  				montant float(8,2) unsigned NOT NULL default '000000.00',
  				num_cp_compta varchar(25) NOT NULL default '0',
  				num_tva_achat varchar(25) NOT NULL default '0',
  				PRIMARY KEY  (id_frais)
				)  ";
		echo traite_rqt($rqt, "create table frais");
		
		$rqt = "CREATE TABLE budgets (
  				id_budget int(8) unsigned NOT NULL auto_increment,
  				num_entite int(5) unsigned NOT NULL default '0',
  				num_exercice int(8) unsigned NOT NULL default '0',
  				libelle varchar(255) NOT NULL default '',
  				commentaires text,
 			 	montant_global float(8,2) unsigned NOT NULL default '000000.00',
  				seuil_alerte int(3) unsigned NOT NULL default '100',
  				statut enum('0','1','2') NOT NULL default '0',
  				PRIMARY KEY  (id_budget)
				)  ";
		echo traite_rqt($rqt, "create table budgets");
		
		$rqt = "CREATE TABLE rubriques (
  				id_rubrique int(8) unsigned NOT NULL auto_increment,
  				num_budget int(8) unsigned NOT NULL default '0',
  				num_parent int(8) unsigned NOT NULL default '0',
  				libelle varchar(255) NOT NULL default '',
  				commentaires text NOT NULL,
  				montant float(8,2) unsigned NOT NULL default '000000.00',
  				num_cp_compta varchar(25) NOT NULL default '',
  				PRIMARY KEY  (id_rubrique)
				)  ";
		echo traite_rqt($rqt, "create table rubriques");
		
		$rqt = "CREATE TABLE suggestions (
  				id_suggestion int(12) unsigned NOT NULL auto_increment,
  				titre tinytext NOT NULL,
  				editeur varchar(255) NOT NULL default '',
  				auteur varchar(255) NOT NULL default '',
  				code varchar(255) NOT NULL default '',
  				prix float(8,2) unsigned NOT NULL default '0.00',
  				commentaires text,
  				statut enum('0','1','2','3','4') NOT NULL default '0',
  				num_produit int(8) NOT NULL default 0,
  				num_entite int(5) NOT NULL default 0,
  				PRIMARY KEY  (id_suggestion)
				)  ";
		echo traite_rqt($rqt, "create table suggestions");
		
		$rqt = "CREATE TABLE auteurs_suggestions (
  				id_auteur varchar(100) NOT NULL default '',
  				num_suggestion int(12) unsigned NOT NULL default '0',
  				type enum('0','1','2') NOT NULL default '0',
  				date timestamp(14) NULL default NULL,
  				PRIMARY KEY  (id_auteur,num_suggestion)
				)  ";
		echo traite_rqt($rqt, "create table auteurs_suggestions");

		$rqt = "CREATE TABLE acquisitions (
  				id_acquisition int(12) unsigned NOT NULL default '0',
  				titre tinytext NOT NULL,
  				editeur varchar(255) NOT NULL default '',
  				auteur varchar(255) NOT NULL default '',
  				code varchar(255) NOT NULL default '',
			  	prix float(8,2) unsigned NOT NULL default '000000.00',
			  	tva float(8,2) unsigned NOT NULL default '000000.00',
			  	nb int(5) unsigned NOT NULL default '1',
			  	commentaires text,
			  	date_transfert date NOT NULL default '0000-00-00',
			  	date_decision date NOT NULL default '0000-00-00',
			  	statut enum('0','1','2','3','4','5','6','7') NOT NULL default '0',
			  	date_acquisition date NOT NULL default '0000-00-00',
			  	num_produit int(8) unsigned NOT NULL default '0',
			  	num_entite int(5) unsigned NOT NULL default '0',
			  	num_rubrique int(8) unsigned NOT NULL default '0',
			  	num_fournisseur int(5) unsigned NOT NULL default '0',
				num_suggestion int(12) unsigned NOT NULL default '0',
				num_notice int(8) unsigned NOT NULL default '0',
				PRIMARY KEY  (id_acquisition),
				KEY num_rubrique (num_rubrique),
				KEY num_fournisseur (num_fournisseur),
				KEY num_produit (num_produit),
				KEY num_entite (num_entite)
				)  ";
		echo traite_rqt($rqt, "create table acquisitions");

		$rqt = "CREATE TABLE actes (
  				id_acte int(8) unsigned NOT NULL auto_increment,
  				date date NOT NULL default '0000-00-00',
 				type enum('0','1','2','3') NOT NULL default '0',
 				statut enum('0','1','2','4','8','16') NOT NULL default '0',
  				date_paiement date NOT NULL default '0000-00-00',
 				num_paiement varchar(255) NOT NULL default '',
 				num_entite int(5) unsigned NOT NULL default '0',
 				num_fournisseur int(5) unsigned NOT NULL default '0',
 				num_contact_livr int(8) unsigned NOT NULL default '0',
 				num_contact_fact int(8) unsigned NOT NULL default '0',
  				commentaires text NOT NULL,
 				PRIMARY KEY  (id_acte),
  				KEY num_fournisseur (num_fournisseur),
  				KEY date (date),
  				KEY num_entite (num_entite)
				)  ";
		echo traite_rqt($rqt, "create table actes");

		$rqt = "CREATE TABLE lignes_actes (
  				id_ligne int(15) unsigned NOT NULL auto_increment,
  				num_acte int(8) unsigned NOT NULL default '0',
  				num_acquisition int(12) unsigned NOT NULL default '0',
  				titre tinytext NOT NULL,
  				editeur varchar(255) NOT NULL default '',
  				auteur varchar(255) NOT NULL default '',
  				code varchar(255) NOT NULL default '',
  				prix float(8,2) unsigned NOT NULL default '000000.00',
  				tva float(8,2) unsigned NOT NULL default '000000.00',
  				nb int(5) unsigned NOT NULL default '1',
  				commentaires text,
  				date_ech date NOT NULL default '0000-00-00',
  				date_cre date NOT NULL default '0000-00-00',
  				statut enum('0','2','4','8','16') NOT NULL default '0',
  				PRIMARY KEY  (id_ligne)
				)  ";
		echo traite_rqt($rqt, "create table lignes_actes");

		$rqt = "CREATE TABLE liens_actes (
  				num_acte int(8) unsigned NOT NULL default '0',
  				num_acte_lie int(8) unsigned NOT NULL default '0',
  				PRIMARY KEY  (num_acte,num_acte_lie)
				)  ";
		echo traite_rqt($rqt, "create table liens_actes");

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='gestion_tva' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'acquisition', 'gestion_tva', '0', 'Gestion de la TVA.\n 0 : Non.\n 1 : Oui.', '',0) ";
			echo traite_rqt($rqt, "insert acquisition_gestion_tva=0 into parameters");
		}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='poids_sugg' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES (0, 'acquisition', 'poids_sugg', 'U=1.00,E=0.70,V=0.00', 
					'Pondération des suggestions par défaut en pourcentage.\n U=Utilisateurs, E=Emprunteurs, V=Visiteurs.\n ex : U=1.00,E=0.70,V=0.00 \n',
					'',0) ";
			echo traite_rqt($rqt, "insert acquisition_poids_sugg=0 into parameters");
		}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='keyword_sep' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'keyword_sep', ' ', 'Séparateur des mots clés dans la partie indexation libre, espace ou ; ou , ou ...')";
			echo traite_rqt($rqt,"insert pmb_keyword_sep=' ' into parametres");
			}
		$rqt = "ALTER TABLE z_bib ADD fichier_func VARCHAR(255) NOT NULL default '' ";
		echo traite_rqt($rqt,"alter z_bib ADD fichier_func ");
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.09");
		break;

	case "v4.09": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE lignes_actes ADD num_rubrique INT(8) UNSIGNED DEFAULT '0' NOT NULL AFTER num_acquisition " ;
		echo traite_rqt($rqt, "add rubrique link into lignes_actes");
		
		$rqt = "ALTER TABLE actes ADD num_exercice INT(8) UNSIGNED DEFAULT '0' NOT NULL AFTER num_contact_fact " ;
		echo traite_rqt($rqt, "add exercice link into actes");
		
		$rqt = "ALTER TABLE exercices ADD statut ENUM('0','1') DEFAULT '1' NOT NULL ";
		echo traite_rqt($rqt, "add exercice statut");
		
		$rqt = "ALTER TABLE tva_achats ADD libelle VARCHAR(255) NOT NULL AFTER id_tva ";
		echo traite_rqt($rqt, "add libelle tva achats");
		
		$rqt = "ALTER TABLE paiements ADD commentaire TEXT NOT NULL ";
		echo traite_rqt($rqt, "add commentaire to mode_paiement"); 
		
		$rqt = "ALTER TABLE entites ADD num_frais INT(8) UNSIGNED DEFAULT '0' NOT NULL, ADD num_paiement INT(8) UNSIGNED DEFAULT '0' NOT NULL ";
		echo traite_rqt($rqt, "add frais, mode paiement to entite");
		
		$rqt = "ALTER TABLE entites ADD index_entite TEXT NOT NULL ";
		echo traite_rqt($rqt, "add index_entite to entites");
		
		$rqt = "ALTER TABLE actes CHANGE statut statut INT(3) UNSIGNED NOT NULL DEFAULT '0'";
		echo traite_rqt($rqt, "modify statut acte");
		
		$rqt = "ALTER TABLE lignes_actes CHANGE statut statut INT(3) UNSIGNED NOT NULL DEFAULT '0'";
		echo traite_rqt($rqt, "modify statut lignes_actes");
		
		$rqt = "ALTER TABLE actes ADD numero VARCHAR(25) NOT NULL AFTER date, ADD INDEX numero(numero) ";
		echo traite_rqt($rqt, "add numero acte");

		$rqt = "ALTER TABLE actes ADD index_acte TEXT NOT NULL ";
		echo traite_rqt($rqt, "add index_acte to actes");
		
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='format' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES (0, 'acquisition', 'format', '8,CA,DD,BL,FA', 
					'Taille du Numéro et Préfixes des actes d\'achats.\nex : 8,CA,DD,BL,FA \n8 = Préfixe + 8 Chiffres\nCA=Commande Achat, DD=Demande de Devis,BL=Bon de Livraison, FA=Facture Achat \n',
					'',0) ";
			echo traite_rqt($rqt, "insert acquisition_format into parameters");
		}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.10");
		break;

	case "v4.10": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE lignes_actes DROP titre ,DROP editeur ,DROP auteur, DROP commentaires " ;
		echo traite_rqt($rqt, "drop titre, editeur, auteur, commentaires from lignes_actes");

		$rqt = "ALTER TABLE lignes_actes ADD num_produit INT(8) UNSIGNED DEFAULT '0' NOT NULL AFTER num_rubrique,
				ADD num_type INT(8) UNSIGNED DEFAULT '0' NOT NULL AFTER num_produit,
				ADD libelle TEXT NOT NULL AFTER num_type  ";
		echo traite_rqt($rqt, "add num_produit, num_type, libelle to lignes_actes");

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='budget' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES (0, 'acquisition', 'budget', '0', 'Utilisation d\'un budget pour les commandes.\n 0:optionnel\n 1:obligatoire','',0) ";
			echo traite_rqt($rqt, "insert acquisition_budget into parameters");
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfcde' and sstype_param='format_page' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfcde','format_page','210x297','Largeur x Hauteur de la page en mm','',0)" ;
			echo traite_rqt($rqt,"insert pdfcde_format_page into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfcde' and sstype_param='orient_page' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfcde','orient_page','P','Orientation de la page: P=Portrait, L=Paysage','',0)" ;
			echo traite_rqt($rqt,"insert pdfcde_orient_page into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfcde' and sstype_param='marges_page' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfcde','marges_page','10,20,10,10','Marges de page en mm : Haut,Bas,Droite,Gauche','',0)" ;
			echo traite_rqt($rqt,"insert pdfcde_marges_page into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfcde' and sstype_param='pos_logo' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfcde','pos_logo','10,10,20,20','Position du logo: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur','',0)" ;
			echo traite_rqt($rqt,"insert pdfcde_pos_logo into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfcde' and sstype_param='pos_raison' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfcde','pos_raison','35,10,100,10,16','Position Raison sociale: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','',0)" ;
			echo traite_rqt($rqt,"insert pdfcde_pos_raison into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfcde' and sstype_param='pos_date' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfcde','pos_date','150,10,0,6,8','Position Date: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','',0)" ;
			echo traite_rqt($rqt,"insert pdfcde_pos_date into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfcde' and sstype_param='pos_adr_fac' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfcde','pos_adr_fac','10,35,60,5,10','Position Adresse de facturation: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','',0)" ;
			echo traite_rqt($rqt,"insert pdfcde_pos_adr_fac into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfcde' and sstype_param='pos_adr_liv' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfcde','pos_adr_liv','10,75,60,5,10','Position Adresse de livraison: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','',0)" ;
			echo traite_rqt($rqt,"insert pdfcde_pos_adr_liv into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfcde' and sstype_param='pos_adr_fou' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfcde','pos_adr_fou','100,55,100,6,14','Position Adresse fournisseur: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','',0)" ;
			echo traite_rqt($rqt,"insert pdfcde_pos_adr_fou into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfcde' and sstype_param='pos_num' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfcde','pos_num','10,110,0,10,16','Position numéro de commande: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','',0)" ;
			echo traite_rqt($rqt,"insert pdfcde_pos_num into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfcde' and sstype_param='text_size' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfcde','text_size','10','Taille de la police texte','',0)" ;
			echo traite_rqt($rqt,"insert pdfcde_text_size into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfcde' and sstype_param='text_before' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfcde','text_before','','Texte avant le tableau de commande','',0)" ;
			echo traite_rqt($rqt,"insert pdfcde_text_before into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfcde' and sstype_param='text_after' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfcde','text_after','','Texte après le tableau de commande','',0)" ;
			echo traite_rqt($rqt,"insert pdfcde_text_after into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfcde' and sstype_param='tab_cde' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfcde','tab_cde','5,10','Table de commandes: Hauteur ligne,Taille police','',0)" ;
			echo traite_rqt($rqt,"insert pdfcde_tab_cde into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfcde' and sstype_param='pos_tot' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfcde','pos_tot','10,40,5,10','Position total de commande: Distance par rapport au bord gauche de la page, Largeur, Hauteur ligne,Taille police','',0)" ;
			echo traite_rqt($rqt,"insert pdfcde_pos_tot into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfcde' and sstype_param='pos_footer' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfcde','pos_footer','15,8','Position bas de page: Distance par rapport au bas de page, Taille police','',0)" ;
			echo traite_rqt($rqt,"insert pdfcde_pos_footer into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfcde' and sstype_param='pos_sign' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfcde','pos_sign','10,60,5,10','Position signature: Distance par rapport au bord gauche de la page, Largeur, Hauteur ligne,Taille police','',0)" ;
			echo traite_rqt($rqt,"insert pdfcde_pos_sign into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfcde' and sstype_param='text_sign' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfcde','text_sign','Le responsable de la bibliothèque.','Texte signature','',0)" ;
			echo traite_rqt($rqt,"insert pdfcde_text_sign into parametres") ;
		}

		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.11");
		break;

	case "v4.11": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE empr CHANGE empr_login empr_login VARCHAR( 255 ) default '' NOT NULL";
		echo traite_rqt($rqt,"ALTER TABLE empr CHANGE empr_login varchar(255) "); 
		$rqt = "ALTER TABLE bulletins CHANGE bulletin_numero bulletin_numero VARCHAR( 255 ) NOT NULL default ''";
		echo traite_rqt($rqt,"ALTER TABLE bulletins CHANGE bulletin_numero VARCHAR( 255 )"); 

		$rqt = "ALTER TABLE actes ADD reference VARCHAR(255) DEFAULT '' NOT NULL AFTER commentaires ";
		echo traite_rqt($rqt, "add reference to actes");
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfdev' and sstype_param='format_page' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfdev','format_page','210x297','Largeur x Hauteur de la page en mm','',0)" ;
			echo traite_rqt($rqt,"insert pdfdev_format_page into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfdev' and sstype_param='orient_page' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfdev','orient_page','P','Orientation de la page: P=Portrait, L=Paysage','',0)" ;
			echo traite_rqt($rqt,"insert pdfdev_orient_page into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfdev' and sstype_param='marges_page' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfdev','marges_page','10,20,10,10','Marges de page en mm : Haut,Bas,Droite,Gauche','',0)" ;
			echo traite_rqt($rqt,"insert pdfdev_marges_page into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfdev' and sstype_param='pos_logo' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfdev','pos_logo','10,10,20,20','Position du logo: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur','',0)" ;
			echo traite_rqt($rqt,"insert pdfdev_pos_logo into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfdev' and sstype_param='pos_raison' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfdev','pos_raison','35,10,100,10,16','Position Raison sociale: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','',0)" ;
			echo traite_rqt($rqt,"insert pdfdev_pos_raison into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfdev' and sstype_param='pos_date' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfdev','pos_date','150,10,0,6,8','Position Date: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','',0)" ;
			echo traite_rqt($rqt,"insert pdfdev_pos_date into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfdev' and sstype_param='pos_adr_fac' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfdev','pos_adr_fac','10,35,60,5,10','Position Adresse de facturation: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','',0)" ;
			echo traite_rqt($rqt,"insert pdfdev_pos_adr_fac into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfdev' and sstype_param='pos_adr_liv' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfdev','pos_adr_liv','10,75,60,5,10','Position Adresse de livraison: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','',0)" ;
			echo traite_rqt($rqt,"insert pdfdev_pos_adr_liv into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfdev' and sstype_param='pos_adr_fou' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfdev','pos_adr_fou','100,55,100,6,14','Position Adresse fournisseur: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','',0)" ;
			echo traite_rqt($rqt,"insert pdfdev_pos_adr_fou into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfdev' and sstype_param='pos_num' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfdev','pos_num','10,110,0,10,16','Position numéro de commande: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','',0)" ;
			echo traite_rqt($rqt,"insert pdfdev_pos_num into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfdev' and sstype_param='text_size' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfdev','text_size','10','Taille de la police texte','',0)" ;
			echo traite_rqt($rqt,"insert pdfdev_text_size into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfdev' and sstype_param='text_before' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfdev','text_before','','Texte avant le tableau de commande','',0)" ;
			echo traite_rqt($rqt,"insert pdfdev_text_before into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfdev' and sstype_param='comment' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfdev','comment','0','Affichage des commentaires : 0=non, 1=oui','',0)" ;
			echo traite_rqt($rqt,"insert pdfdev_comment into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfdev' and sstype_param='text_after' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfdev','text_after','','Texte après le tableau de commande','',0)" ;
			echo traite_rqt($rqt,"insert pdfdev_text_after into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfdev' and sstype_param='tab_dev' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfdev','tab_dev','5,10','Table de commandes: Hauteur ligne,Taille police','',0)" ;
			echo traite_rqt($rqt,"insert pdfdev_tab_cde into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfdev' and sstype_param='pos_footer' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfdev','pos_footer','15,8','Position bas de page: Distance par rapport au bas de page, Taille police','',0)" ;
			echo traite_rqt($rqt,"insert pdfdev_pos_footer into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfdev' and sstype_param='pos_sign' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfdev','pos_sign','10,60,5,10','Position signature: Distance par rapport au bord gauche de la page, Largeur, Hauteur ligne,Taille police','',0)" ;
			echo traite_rqt($rqt,"insert pdfdev_pos_sign into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdfdev' and sstype_param='text_sign' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pdfdev','text_sign','Le responsable de la bibliothèque.','Texte signature','',0)" ;
			echo traite_rqt($rqt,"insert pdfdev_text_sign into parametres") ;
		}

		$rqt = "ALTER TABLE lignes_actes ADD lig_ref INT(15) UNSIGNED DEFAULT '0' NOT NULL AFTER num_acte ";
		echo traite_rqt($rqt, "add lig_ref to lignes_actes");
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.12");
		break;

	case "v4.12": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "update parametres set sstype_param=concat(type_param,'_',sstype_param), type_param='acquisition', section_param='pdfcde' where type_param = 'pdfcde'";
		echo traite_rqt($rqt, "update pdfcde to acquisition parameters");

		$rqt = "update parametres set sstype_param=concat(type_param,'_',sstype_param), type_param='acquisition', section_param='pdfdev' where type_param = 'pdfdev'";
		echo traite_rqt($rqt, "update pdfdev to acquisition parameters");
						
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.13");
		break;

	case "v4.13": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE coordonnees ADD libelle VARCHAR(255) NOT NULL AFTER num_entite ";
		echo traite_rqt($rqt, "add libelle to coordonnees"); 
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.14");
		break;

	case "v4.14": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "CREATE TABLE rss_flux (id_rss_flux int(9) unsigned NOT NULL auto_increment, nom_rss_flux varchar(255) NOT NULL default '', link_rss_flux blob NOT NULL default '', descr_rss_flux blob NOT NULL default '', lang_rss_flux varchar(255) not null default 'fr', copy_rss_flux blob NOT NULL default '', editor_rss_flux varchar(255) NOT NULL default '', webmaster_rss_flux varchar(255) NOT NULL default '', ttl_rss_flux int(9) unsigned not null default 60, img_url_rss_flux blob NOT NULL default '', img_title_rss_flux blob NOT NULL default '', img_link_rss_flux blob NOT NULL default '', format_flux blob NOT NULL default '', PRIMARY KEY  (id_rss_flux)) " ;
		echo traite_rqt($rqt,"create table rss_flux");
		$rqt = "CREATE TABLE rss_flux_content (num_rss_flux INT( 9 ) UNSIGNED DEFAULT 0 NOT NULL, type_contenant char(3) default 'BAN' not null, num_contenant INT( 9 ) UNSIGNED DEFAULT 0 NOT NULL , PRIMARY KEY  (num_rss_flux, type_contenant, num_contenant)) " ;
		echo traite_rqt($rqt,"create table rss_flux_content");

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='export_allow' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'opac', 'export_allow', '1', 'Export de notices à partir de l\'opac : \n 0 : interdit \n 1 : pour tous \n 2 : pour les abonnés uniquement', 'a_general', 0)";
			echo traite_rqt($rqt,"insert opac_export_allow='1' into parametres");
			}
		$rqt = "update parametres set valeur_param='libelle_categorie' where type_param='opac' and sstype_param='categories_sub_mode' and valeur_param='categ_libelle' ";
		echo traite_rqt($rqt,"update parametres valeur_param=libelle_categ where valeur_param='categ_libelle' ");
		$rqt = "update parametres set comment_param=REPLACE(comment_param,'categ_libelle','libelle_categorie') where type_param='opac' and sstype_param='categories_sub_mode' ";
		echo traite_rqt($rqt,"update parametres comment_param=REPLACE(... ");
		
		$rqt = "CREATE TABLE bannette_exports ( num_bannette int(11) unsigned NOT NULL default 0, export_format int(3) NOT NULL default 0, export_data longblob NOT NULL default '', export_nomfichier varchar(255) default '', PRIMARY KEY  (num_bannette,export_format)) ";
		echo traite_rqt($rqt,"create table bannette_exports ");
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='resa_planning' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'opac', 'resa_planning', '0', 'Utiliser un planning de réservation ? \n 0: Non \n 1: Oui', 'a_general', 0)";
			echo traite_rqt($rqt,"insert opac_resa_planning='0' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='resa_contact' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'opac', 'resa_contact', '<a href=\'mailto:pmb@sigb.net\'>pmb@sigb.net</a>', 'Code HTML d\'information sur la personne à contacter par exemple en cas de problème de réservation.', 'a_general', 0)";
			echo traite_rqt($rqt,"insert opac_resa_contact='pmb@sigb.net' into parametres");
			}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.15");		
		break;

	case "v4.15": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE rubriques ADD autorisations MEDIUMTEXT DEFAULT '' NOT NULL ";
		echo traite_rqt($rqt, "add autorisations to rubriques");
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.16");
		break;

	case "v4.16": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE auteurs_suggestions RENAME suggestions_origine ";
		echo traite_rqt($rqt, "ALTER TABLE auteurs_suggestions RENAME suggestions_origine");
		$rqt = "ALTER TABLE suggestions_origine CHANGE id_auteur origine VARCHAR(100) NOT NULL DEFAULT '' ";
 		echo traite_rqt($rqt, "ALTER TABLE suggestions_origine CHANGE id_auteur origine");
		$rqt = "ALTER TABLE suggestions_origine CHANGE type type_origine INT(3) UNSIGNED NOT NULL DEFAULT '0' ";
		echo traite_rqt($rqt, "ALTER TABLE suggestions_origine CHANGE type type_origine INT(3)");
		$rqt = "ALTER TABLE suggestions_origine CHANGE date date_suggestion DATE NOT NULL DEFAULT '0000-00-00' ";
		echo traite_rqt($rqt, "ALTER TABLE suggestions_origine CHANGE date date_suggestion ");
		
		//Table coordonnees renommage du champ type
		$rqt = "ALTER TABLE coordonnees CHANGE type type_coord INT(1) UNSIGNED NOT NULL DEFAULT '0' ";
 		echo traite_rqt($rqt, "ALTER TABLE coordonnees CHANGE type type_coord");

		//Table actes conversion enum vers int sans perte de données et renommage du champ type
		$rqt = "ALTER TABLE actes CHANGE type type_acte VARCHAR(255) ";
		echo traite_rqt($rqt, "ALTER TABLE actes CHANGE type type_acte VARCHAR(255)");
		$rqt = "ALTER TABLE actes CHANGE type_acte type_acte INT(3) UNSIGNED NOT NULL DEFAULT '0' ";
		echo traite_rqt($rqt, "ALTER TABLE actes CHANGE type_acte INT(3)");

		//Table actes renommage du champ date
		$rqt = "ALTER TABLE actes CHANGE date date_acte DATE NOT NULL DEFAULT '0000-00-00' ";
 		echo traite_rqt($rqt, "ALTER TABLE actes CHANGE date date_acte");

		//Table offres_remises renommage du champ conditions si non fait
		$rqt = "ALTER TABLE offres_remises CHANGE conditions condition_remise TEXT ";
 		echo traite_rqt($rqt, "offres_remises CHANGE conditions condition_remise, IN CASE OF ERROR IGNORE IT.");
		
		//Conversion enum vers int sans perte de données
		$rqt = "ALTER TABLE budgets CHANGE statut statut VARCHAR(255) ";
		echo traite_rqt($rqt, "ALTER TABLE budgets CHANGE statut VARCHAR(255)");
		$rqt = "ALTER TABLE budgets CHANGE statut statut INT(3) UNSIGNED NOT NULL DEFAULT '0' ";
		echo traite_rqt($rqt, "ALTER TABLE budgets CHANGE statut INT(3)");
		
		//Conversion enum vers int sans perte de données et renommage du champ type
		$rqt = "ALTER TABLE entites CHANGE type type_entite VARCHAR(255) ";
		echo traite_rqt($rqt, "ALTER TABLE entites CHANGE type type_entite VARCHAR(255)");
		$rqt = "ALTER TABLE entites CHANGE type_entite type_entite INT(3) UNSIGNED NOT NULL DEFAULT '0' ";
		echo traite_rqt($rqt, "ALTER TABLE entites CHANGE type_entite INT(3)");
		
		//Conversion enum vers int sans perte de données
		$rqt = "ALTER TABLE exercices CHANGE statut statut VARCHAR(255) ";
		echo traite_rqt($rqt, "ALTER TABLE exercices CHANGE statut VARCHAR(255)");
		$rqt = "ALTER TABLE exercices CHANGE statut statut INT(3) UNSIGNED NOT NULL DEFAULT '1' ";
		echo traite_rqt($rqt, "ALTER TABLE exercices CHANGE statut INT(3)");
		
		$rqt = "ALTER TABLE suggestions CHANGE statut statut INT(3) UNSIGNED NOT NULL DEFAULT '0' ";
		echo traite_rqt($rqt, "ALTER TABLE suggestions CHANGE statut INT(3)");		 
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.17");
		break;

	case "v4.17": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE lignes_actes ADD remise float(8,2) NOT NULL DEFAULT '000000.00' ";
		echo traite_rqt($rqt, "ALTER TABLE lignes_actes ADD remise");
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='default_operator' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'opac', 'default_operator', '0', 'Opérateur par défaut. 0 : OR, 1 : AND.', 'c_recherche', 0) ";
			echo traite_rqt($rqt, "insert opac_default_operator into parameters"); 
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='modules_search_all' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'opac', 'modules_search_all', '2', 'Recherche simple dans l\'ensemble des champs :0 : interdite,  1 : autorisée,  2 : autorisée et validée par défaut', 'c_recherche', 0) ";
			echo traite_rqt($rqt, "insert opac_modules_search_all into parameters");
			} 
		$rqt = "create table notices_global_index ( num_notice mediumint(8) not null default 0, no_index mediumint(8) not null default 0, infos_global text not null default '', index_infos_global text not null default '', primary key (num_notice, no_index) ) ";
		echo traite_rqt($rqt, "create table notices_global_index");
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.18");
		break;

	case "v4.18": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdfliv_format_page' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdfliv_format_page','210x297','Largeur x Hauteur de la page en mm','pdfliv',0)" ;
			echo traite_rqt($rqt,"insert pdfliv_format_page into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdfliv_orient_page' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdfliv_orient_page','P','Orientation de la page: P=Portrait, L=Paysage','pdfliv',0)" ;
			echo traite_rqt($rqt,"insert pdfliv_orient_page into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdfliv_marges_page' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdfliv_marges_page','10,20,10,10','Marges de page en mm : Haut,Bas,Droite,Gauche','pdfliv',0)" ;
			echo traite_rqt($rqt,"insert pdfliv_marges_page into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdfliv_pos_raison' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdfliv_pos_raison','10,10,100,10,16','Position Raison sociale: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfliv',0)" ;
			echo traite_rqt($rqt,"insert pdfliv_pos_raison into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdfliv_pos_adr_liv' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdfliv_pos_adr_liv','10,20,60,5,10','Position Adresse de livraison: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfliv',0)" ;
			echo traite_rqt($rqt,"insert pdfliv_pos_adr_liv into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdfliv_pos_adr_fou' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdfliv_pos_adr_fou','110,20,100,5,10','Position éléments fournisseur: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfliv',0)" ;
			echo traite_rqt($rqt,"insert pdfliv_pos_adr_fou into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdfliv_pos_num' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdfliv_pos_num','10,60,0,6,14','Position numéro Commande/Livraison: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfliv',0)" ;
			echo traite_rqt($rqt,"insert pdfliv_pos_num into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdfliv_tab_liv' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdfliv_tab_liv','5,10','Table de livraisons: Hauteur ligne,Taille police','pdfliv',0)" ;
			echo traite_rqt($rqt,"insert pdfliv_tab_liv into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdfliv_pos_footer' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdfliv_pos_footer','15,8','Position bas de page: Distance par rapport au bas de page, Taille police','pdfliv',0)" ;
			echo traite_rqt($rqt,"insert pdfliv_pos_footer into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='default_operator' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'pmb', 'default_operator', '0', 'Opérateur par défaut. \n 0 : OR, \n 1 : AND.', '', 0) ";
			echo traite_rqt($rqt, "insert pmb_default_operator into parameters"); 
			}
		$rqt = "ALTER TABLE notices_categories drop index i_notcateg_notice " ;
		echo traite_rqt($rqt,"drop index i_notcateg_notice");
		$rqt = "ALTER TABLE notices_categories drop index i_num_noeud " ;
		echo traite_rqt($rqt,"drop index i_num_noeud");
		
		$rqt = "ALTER TABLE users DROP poids_sugg" ;
		echo traite_rqt($rqt,"drop users.poids_sugg ");
		$rqt = "ALTER TABLE empr DROP poids_sugg" ;
		echo traite_rqt($rqt,"drop empr.poids_sugg ");
		 
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'mailretard' and sstype_param='priorite_email_3' "))==0){
			$rqt = "INSERT INTO parametres VALUES (0,'mailretard','priorite_email_3','0','Faire le troisième niveau de relance par mail :\n 0 : Non, lettre \n 1 : Oui, par mail','',0)" ;
			echo traite_rqt($rqt,"insert mailretard_priorite_email_3 into parametres") ;
			}

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.19");
		break;

	case "v4.19": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE suggestions ADD index_suggestion TEXT NOT NULL ";
		echo traite_rqt($rqt, "add index_suggestion to suggestions");
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.20");
		break;

	case "v4.20": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "DROP TABLE IF EXISTS acquisitions ";		
		echo traite_rqt($rqt, "drop table acquisitions");
		$rqt = "ALTER TABLE suggestions ADD nb INT(5) UNSIGNED NOT NULL DEFAULT '1'"; 
		echo traite_rqt($rqt, "add field nb to suggestions");	
		$rqt="ALTER TABLE suggestions ADD date_creation DATE NOT NULL ";
		echo traite_rqt($rqt, "add field date_creation to suggestions");
		$rqt ="ALTER TABLE suggestions ADD date_decision DATE NOT NULL ";
		echo traite_rqt($rqt, "add field date_decision to suggestions");
		$rqt = "ALTER TABLE suggestions ADD num_rubrique INT(8) UNSIGNED NOT NULL DEFAULT '0' ";
		echo traite_rqt($rqt, "add field num_rubrique to suggestions");
		$rqt = "ALTER TABLE suggestions ADD num_fournisseur INT(5) UNSIGNED NOT NULL DEFAULT '0' ";
		echo traite_rqt($rqt, "add field num_fournisseur to suggestions");
		$rqt = "ALTER TABLE suggestions ADD num_notice INT(8) UNSIGNED NOT NULL DEFAULT '0' ";
		echo traite_rqt($rqt, "add field num_notice to suggestions");
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_suggest' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'opac','show_suggest','0','Proposer de faire des suggestions dans l\'OPAC.\n 0 : Non.\n 1 : Oui, avec authentification.\n 2 : Oui, sans authentification.','suggestion',0)" ;
			echo traite_rqt($rqt,"insert opac_show_suggest into parametres") ;
		}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.21");
		break;

	case "v4.21": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		//Ajout d'un champ permettant de definir un type de budget dans la table budget
		$rqt = "ALTER TABLE budgets ADD type_budget INT(3) UNSIGNED NOT NULL DEFAULT '0' "; 
		echo traite_rqt($rqt, "add field type_budget to budgets");	

		//Ajout d'un champ devise dans la table actes
		$rqt = "ALTER TABLE actes ADD devise VARCHAR(25) NOT NULL DEFAULT '' "; 
		echo traite_rqt($rqt, "add field devise to actes");	

		//Ajout d'un champ commentaires imprimables dans la table actes
		$rqt = "ALTER TABLE actes ADD commentaires_i TEXT NOT NULL DEFAULT '' "; 
		echo traite_rqt($rqt, "add field commentaires_i to actes");	

		//Ajout d'un champ date de validation dans la table actes
		$rqt = "ALTER TABLE actes ADD date_valid DATE NOT NULL DEFAULT '0000-00-00' "; 
		echo traite_rqt($rqt, "add field date_valid to actes");	

		//Ajout d'un champ URL dans la table suggestions	
		$rqt = "ALTER TABLE suggestions ADD url_suggestion vARCHAR(255) NOT NULL DEFAULT '' "; 
		echo traite_rqt($rqt, "add field url_suggestion to suggestions");		

		//Ajout d'un parametre permettant de préciser si l'on informe par email de l'évolution des suggestions 
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='email_sugg' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES (0, 'acquisition', 'email_sugg', '0', 
					'Information par email de l\'évolution des suggestions.\n 0 : Non\n 1 : Oui',
					'',0) ";
			echo traite_rqt($rqt, "insert acquisition_email_sugg into parameters");
		}
		
		//Passage du parametre show_suggest dans la section modules affichés dans l'OPAC
		$rqt = "UPDATE parametres SET section_param = 'f_modules' WHERE type_param = 'opac' and sstype_param = 'show_suggest' LIMIT 1 "; 
		echo traite_rqt($rqt, "move param opac_show_suggest to opac f_modules");
		//Passage du parametre thesaurus dans la section options générales de fonctionnement de l'OPAC
		$rqt = "UPDATE parametres SET section_param = 'a_general' WHERE type_param = 'opac' and sstype_param = 'thesaurus' LIMIT 1 "; 
		echo traite_rqt($rqt, "move param opac_thesaurus to opac a_general");
		
		$rqt = "ALTER TABLE authors ADD author_comment TEXT "; 
		echo traite_rqt($rqt, "ALTER TABLE authors ADD author_comment");
		$rqt = "ALTER TABLE publishers ADD ed_comment TEXT "; 
		echo traite_rqt($rqt, "ALTER TABLE publishers ADD ed_comment");

		//Modification longueur des champs numeros comptables
		$rqt = "ALTER TABLE entites CHANGE num_cp_compta num_cp_compta VARCHAR(255) NOT NULL ";
		echo traite_rqt($rqt, "modify length num_cp_compta in table entites");
		$rqt = "ALTER TABLE frais CHANGE num_cp_compta num_cp_compta VARCHAR(255) NOT NULL ";
		echo traite_rqt($rqt, "modify length num_cp_compta in table frais");
		$rqt = "ALTER TABLE rubriques CHANGE num_cp_compta num_cp_compta VARCHAR(255) NOT NULL ";
		echo traite_rqt($rqt, "modify length num_cp_compta in table rubriques");

		//Paramètres de mise en page BL (suite)
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdfliv_text_size' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdfliv_text_size','10','Taille de la police texte','pdfliv',0)" ;
			echo traite_rqt($rqt,"insert pdfliv_pos_footer into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdfliv_pos_date' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdfliv_pos_date','170,10,0,6,8','Position Date: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfliv',0)" ;
			echo traite_rqt($rqt,"insert pdfliv_pos_date into parametres") ;
		}

		//Paramètres de mise en page facture 
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdffac_text_size' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdffac_text_size','10','Taille de la police texte','pdffac',0)" ;
			echo traite_rqt($rqt,"insert pdffac_text_size into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdffac_format_page' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdffac_format_page','210x297','Largeur x Hauteur de la page en mm','pdffac',0)" ;
			echo traite_rqt($rqt,"insert pdffac_format_page into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdffac_orient_page' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdffac_orient_page','P','Orientation de la page: P=Portrait, L=Paysage','pdffac',0)" ;
			echo traite_rqt($rqt,"insert pdffac_orient_page into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdffac_marges_page' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdffac_marges_page','10,20,10,10','Marges de page en mm : Haut,Bas,Droite,Gauche','pdffac',0)" ;
			echo traite_rqt($rqt,"insert pdffac_marges_page into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdffac_pos_raison' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdffac_pos_raison','10,10,100,10,16','Position Raison sociale: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdffac',0)" ;
			echo traite_rqt($rqt,"insert pdffac_pos_raison into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdffac_pos_date' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdffac_pos_date','170,10,0,6,8','Position Date: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdffac',0)" ;
			echo traite_rqt($rqt,"insert pdffac_pos_date into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdffac_pos_adr_fac' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdffac_pos_adr_fac','10,20,60,5,10','Position Adresse de facturation: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdffac',0)" ;
			echo traite_rqt($rqt,"insert pdffac_pos_adr_fac into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdffac_pos_adr_fou' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdffac_pos_adr_fou','110,20,100,5,10','Position éléments fournisseur: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdffac',0)" ;
			echo traite_rqt($rqt,"insert pdffac_pos_adr_fou into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdffac_pos_num' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdffac_pos_num','10,60,0,6,14','Position numéro Commande/Facture: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdffac',0)" ;
			echo traite_rqt($rqt,"insert pdffac_pos_num into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdffac_tab_fac' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdffac_tab_fac','5,10','Table de facturation: Hauteur ligne,Taille police','pdffac',0)" ;
			echo traite_rqt($rqt,"insert pdffac_tab_fac into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdffac_pos_tot' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdffac_pos_tot','10,40,5,10','Position total de commande: Distance par rapport au bord gauche de la page, Largeur, Hauteur ligne,Taille police','pdffac',0)" ;
			echo traite_rqt($rqt,"insert pdffac_pos_tot into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdffac_pos_footer' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdffac_pos_footer','15,8','Position bas de page: Distance par rapport au bas de page, Taille police','pdffac',0)" ;
			echo traite_rqt($rqt,"insert pdffac_pos_footer into parametres") ;
		}

		//Paramètres de mise en page listes de suggestion
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdfsug_text_size' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdfsug_text_size','8','Taille de la police texte','pdfsug',0)" ;
			echo traite_rqt($rqt,"insert pdfsug_text_size into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdfsug_format_page' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdfsug_format_page','210x297','Largeur x Hauteur de la page en mm','pdfsug',0)" ;
			echo traite_rqt($rqt,"insert pdfsug_format_page into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdfsug_orient_page' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdfsug_orient_page','P','Orientation de la page: P=Portrait, L=Paysage','pdfsug',0)" ;
			echo traite_rqt($rqt,"insert pdfsug_orient_page into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdfsug_marges_page' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdfsug_marges_page','10,20,10,10','Marges de page en mm : Haut,Bas,Droite,Gauche','pdfsug',0)" ;
			echo traite_rqt($rqt,"insert pdfsug_marges_page into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdfsug_pos_titre' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdfsug_pos_titre','10,10,100,10,16','Position titre: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfsug',0)" ;
			echo traite_rqt($rqt,"insert pdfsug_pos_titre into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdfsug_pos_date' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdfsug_pos_date','170,10,0,6,8','Position Date: Distance par rapport au bord gauche de la page,Distance par rapport au haut de la page,Largeur,Hauteur,Taille police','pdfsug',0)" ;
			echo traite_rqt($rqt,"insert pdfsug_pos_date into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdfsug_tab_sug' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdfsug_tab_sug','5,10','Table de suggestions: Hauteur ligne,Taille police','pdfsug',0)" ;
			echo traite_rqt($rqt,"insert pdfsug_tab_sug into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdfsug_pos_footer' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdfsug_pos_footer','15,8','Position bas de page: Distance par rapport au bas de page, Taille police','pdfsug',0)" ;
			echo traite_rqt($rqt,"insert pdfsug_pos_footer into parametres") ;
		}

		//Paramètres envoi de mails des suggestions 
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='mel_rej_obj' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','mel_rej_obj','Rejet suggestion','Objet du mail de rejet de suggestion','mel',0)" ;
			echo traite_rqt($rqt,"insert mel_rej_obj into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='mel_rej_cor' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','mel_rej_cor','Votre suggestion du !!date!! est rejetée.\n\n','Corps du mail de rejet de suggestion','mel',0)" ;
			echo traite_rqt($rqt,"insert mel_rej_cor into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='mel_con_obj' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','mel_con_obj','Confirmation suggestion','Objet du mail de confirmation de suggestion','mel',0)" ;
			echo traite_rqt($rqt,"insert mel_con_obj into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='mel_con_cor' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','mel_con_cor','Votre suggestion du !!date!! est retenue pour un prochain achat.\n\n','Corps du mail de confirmation de suggestion','mel',0)" ;
			echo traite_rqt($rqt,"insert mel_con_cor into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='mel_aba_obj' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','mel_aba_obj','Abandon suggestion','Objet du mail d\'abandon de suggestion','mel',0)" ;
			echo traite_rqt($rqt,"insert mel_aba_obj into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='mel_aba_cor' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','mel_aba_cor','Votre suggestion du !!date!! n\'est pas retenue ou n\'est pas disponible à la vente.\n\n','Corps du mail d\'abandon de suggestion','mel',0)" ;
			echo traite_rqt($rqt,"insert mel_aba_cor into parametres") ;
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='mel_cde_obj' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','mel_cde_obj','Commande suggestion','Objet du mail de commande de suggestion','mel',0)" ;
			echo traite_rqt($rqt,"insert mel_cde_obj into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='mel_cde_cor' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','mel_cde_cor','Votre suggestion du !!date!! est en commande.\n\n','Corps du mail de commande de suggestion','mel',0)" ;
			echo traite_rqt($rqt,"insert mel_cde_cor into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='mel_rec_obj' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','mel_rec_obj','Réception suggestion','Objet du mail de réception de suggestion','mel',0)" ;
			echo traite_rqt($rqt,"insert mel_rec_obj into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='mel_rec_cor' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','mel_rec_cor','Votre suggestion du !!date!! a été reçue et sera bientôt disponible en réservation.\n\n','Corps du mail de réception de suggestion','mel',0)" ;
			echo traite_rqt($rqt,"insert mel_rec_cor into parametres") ;
		}

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.22");
		break;

	case "v4.22": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE analysis DROP INDEX analysis_bulletin ";
		echo traite_rqt($rqt,"DROP INDEX analysis_bulletin ") ;
		$rqt = "ALTER TABLE analysis DROP INDEX analysis_notice ";
		echo traite_rqt($rqt,"DROP INDEX analysis_notice ") ;
		$rqt = "ALTER TABLE analysis ADD INDEX analysis_notice (analysis_notice) ";
		echo traite_rqt($rqt,"ADD INDEX analysis_notice ") ;

		$rqt = "ALTER TABLE notices_categories drop index i_notcateg_notice " ;
		echo traite_rqt($rqt,"drop index i_notcateg_notice");
		$rqt = "ALTER TABLE notices_categories drop index i_num_noeud " ;
		echo traite_rqt($rqt,"drop index i_num_noeud");
		$rqt = "ALTER TABLE notices_categories drop index num_noeud " ;
		echo traite_rqt($rqt,"drop index num_noeud");
		$rqt = "ALTER TABLE notices_categories ADD INDEX num_noeud (num_noeud) ";
		echo traite_rqt($rqt,"ALTER TABLE notices_categories ADD INDEX num_noeud") ;
		
		$rqt = "ALTER TABLE analysis DROP INDEX analysis_bulletin " ;
		echo traite_rqt($rqt,"DROP USELESS INDEX");
		$rqt = "ALTER TABLE expl_custom_values DROP INDEX expl_champ_origine " ;
		echo traite_rqt($rqt,"DROP USELESS INDEX");
		$rqt = "ALTER TABLE caddie_procs DROP INDEX idproc  " ;
		echo traite_rqt($rqt,"DROP USELESS INDEX");
		$rqt = "ALTER TABLE docs_codestat DROP INDEX idcode " ;
		echo traite_rqt($rqt,"DROP USELESS INDEX");
		$rqt = "ALTER TABLE docs_section DROP INDEX idcode " ;
		echo traite_rqt($rqt,"DROP USELESS INDEX");
		$rqt = "ALTER TABLE docs_statut DROP INDEX idcode " ;
		echo traite_rqt($rqt,"DROP USELESS INDEX");
		$rqt = "ALTER TABLE docs_type DROP INDEX idtyp_doc " ;
		echo traite_rqt($rqt,"DROP USELESS INDEX");
		$rqt = "ALTER TABLE empr_categ DROP INDEX idcode " ;
		echo traite_rqt($rqt,"DROP USELESS INDEX");
		$rqt = "ALTER TABLE empr_custom_values DROP INDEX champ_origine " ;
		echo traite_rqt($rqt,"DROP USELESS INDEX");
		$rqt = "ALTER TABLE lenders DROP INDEX idcode " ;
		echo traite_rqt($rqt,"DROP USELESS INDEX");
		$rqt = "ALTER TABLE notices_categories DROP INDEX i_notcateg_notice " ;
		echo traite_rqt($rqt,"DROP USELESS INDEX");
		$rqt = "ALTER TABLE notices_custom_values DROP INDEX noti_champ_origine " ;
		echo traite_rqt($rqt,"DROP USELESS INDEX");
		$rqt = "ALTER TABLE publishers DROP INDEX ed_id " ;
		echo traite_rqt($rqt,"DROP USELESS INDEX");
		$rqt = "ALTER TABLE responsability DROP INDEX responsability_author " ;
		echo traite_rqt($rqt,"DROP USELESS INDEX");
		$rqt = "ALTER TABLE sub_collections DROP INDEX sub_coll_id " ;
		echo traite_rqt($rqt,"DROP USELESS INDEX");

		$rqt = "ALTER TABLE publishers DROP INDEX ed_ville " ;
		echo traite_rqt($rqt,"DROP USELESS INDEX");
		$rqt = "ALTER TABLE publishers ADD INDEX ed_ville (ed_ville) " ;
		echo traite_rqt($rqt,"ADD INDEX ed_ville ");
		$rqt = "ALTER TABLE sub_collections DROP INDEX sub_coll_name " ;
		echo traite_rqt($rqt,"DROP USELESS INDEX");
		$rqt = "ALTER TABLE sub_collections ADD INDEX sub_coll_name (sub_coll_name) " ;
		echo traite_rqt($rqt,"ADD INDEX sub_coll_name ");

		$rqt = "ALTER TABLE voir_aussi DROP INDEX num_noeud_dest " ;
		echo traite_rqt($rqt,"DROP USELESS INDEX");
		$rqt = "ALTER TABLE voir_aussi ADD INDEX num_noeud_dest (num_noeud_dest)"; 
		echo traite_rqt($rqt,"ADD INDEX num_noeud_dest ");
		
		$rqt = "ALTER TABLE categories DROP INDEX categ_langue " ;
		echo traite_rqt($rqt,"DROP USELESS INDEX");
		$rqt = "ALTER TABLE categories ADD INDEX categ_langue (langue)"; 
		echo traite_rqt($rqt,"ADD INDEX categ_langue ");
		
		$rqt = "ALTER TABLE collections DROP INDEX collection_id ";
		echo traite_rqt($rqt,"DROP USELESS INDEX");
		$rqt = "ALTER TABLE empr_codestat DROP INDEX idcode ";
		echo traite_rqt($rqt,"DROP USELESS INDEX");
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.23");
		break;

	case "v4.23": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='allow_tags_search' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'opac', 'allow_tags_search', '0', 'Recherche par tag (mots clés utilisateurs) \n 1 = oui \n 0 = non', 'c_recherche', 0)" ;
			echo traite_rqt($rqt,"insert opac_allow_tags_search into parametres") ;
			}		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='allow_add_tag' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'opac', 'allow_add_tag', '0', 'Permettre aux utilisateurs d\'ajouter un tag à une notice.\n 0 : non\n 1 : oui\n 2 : identification obligatoire pour ajouter ', 'a_general', 0) ";
			echo traite_rqt($rqt,"insert opac_allow_add_tag into parametres") ;
			}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='avis_allow' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'opac', 'avis_allow', '0', 'Permet de consulter/ajouter un avis pour les notices \n 0 : non \n 1 : sans être identifié : consultation possible, ajout impossible \n 2 : identification obligatoire pour consulter et ajouter ', 'a_general', 0) ";
			echo traite_rqt($rqt,"insert opac_avis_allow into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='avis_nb_max' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES (0, 'opac', 'avis_nb_max', '30', 'Nombre maximal de commentaires conservé par notice. Les plus vieux sont effacés au profit des plus récents quand ce nombre est atteint.', 'a_general', 0) ";
			echo traite_rqt($rqt,"insert opac_avis_nb_max into parametres") ;
			}
		
		$rqt = "CREATE TABLE tags (
				id_tag mediumint(8) NOT NULL auto_increment,
				libelle varchar(200) NOT NULL default '',
				num_notice mediumint(8) NOT NULL default 0,
				user_code varchar(50) NOT NULL default '',
				dateajout timestamp NOT NULL , 
				PRIMARY KEY (id_tag) ) ";
		echo traite_rqt($rqt,"CREATE TABLE tags") ;
		
		$rqt = "CREATE TABLE avis (
				id_avis mediumint(8) NOT NULL auto_increment,
				num_empr mediumint(8) NOT NULL,
				num_notice mediumint(8) NOT NULL,
				note integer(3) default NULL, 
				sujet text,
				commentaire text,
				dateajout timestamp NOT NULL,
				valide integer(1) unsigned NOT NULL default 0,
				PRIMARY KEY  (id_avis))";
		echo traite_rqt($rqt,"CREATE TABLE avis") ;
		
		$rqt = "ALTER TABLE docs_location ADD commentaire TEXT NOT NULL default '' ";
		echo traite_rqt($rqt,"ALTER docs_location ADD commentaire") ;
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.24");
		break;

	case "v4.24": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "CREATE TABLE avis (
				id_avis mediumint(8) NOT NULL auto_increment,
				num_empr mediumint(8) NOT NULL,
				num_notice mediumint(8) NOT NULL,
				note integer(3) default NULL, 
				sujet text,
				commentaire text,
				dateajout timestamp NOT NULL,
				valide integer(1) unsigned NOT NULL default 0,
				PRIMARY KEY  (id_avis))";
		echo traite_rqt($rqt,"CREATE TABLE avis ") ;
		$rqt = "DROP TABLE IF EXISTS categ_assoc";
		echo traite_rqt($rqt, "drop categ_assoc");
		$rqt = "DROP TABLE IF EXISTS old_notices_categories";
		echo traite_rqt($rqt, "drop old_notices_categories");
		$rqt = "DROP TABLE IF EXISTS old_categories";
		echo traite_rqt($rqt, "drop old_categories");
		$rqt = "ALTER TABLE lignes_actes ADD type_ligne INT(3) UNSIGNED NOT NULL DEFAULT '0' AFTER id_ligne ";
		echo traite_rqt($rqt,"add type_ligne to lignes_actes") ;
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='show_rtl' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'pmb', 'show_rtl', '0', 'Affichage possible de droite a gauche \n 0 non \n 1 oui', '', 0)" ;
			echo traite_rqt($rqt,"insert pmb_show_rtl into parametres") ;
			}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.25");
		break;

	case "v4.25": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='avis_show_writer' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'opac', 'avis_show_writer', '0', 'Afficher le rédacteur de l\'avis \n 0 : non \n 1 : Prénom NOM \n 2 : login OPAC uniquement ', 'a_general', 0) ";
			echo traite_rqt($rqt,"insert opac_avis_show_writer into parametres") ;
			}
		$rqt = "CREATE TABLE grilles (
				grille_typdoc char(2) not null default 'a',
				grille_niveau_biblio char(1) not null default 'm',
				grille_localisation mediumint(8) NOT NULL default 0,
				descr_format LONGTEXT,
				PRIMARY KEY  (grille_typdoc,grille_niveau_biblio,grille_localisation))";
		echo traite_rqt($rqt,"CREATE TABLE grilles ") ;
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='form_editables' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'pmb', 'form_editables', '0', 'Grilles de notices éditables \n 0 non \n 1 oui', '', 0)" ;
			echo traite_rqt($rqt,"insert pmb_form_editables into parametres") ;
			}
		$rqt = "ALTER TABLE users ADD xmlta_doctype varchar(2) NOT NULL DEFAULT 'a' " ;
		echo traite_rqt($rqt, "add user param xmlta_doctype");

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='sugg_to_cde' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'acquisition', 'sugg_to_cde', '0', 'Transfert des suggestions en commande.\n 0 : Non.\n 1 : Oui.', '',0) ";
			echo traite_rqt($rqt, "insert sugg_to_cde=0 into parametres");
			}

		$rqt = "ALTER TABLE bannette_contenu ADD date_ajout timestamp ";
		echo traite_rqt($rqt, "ALTER bannette_contenu ADD date_ajout");
		$rqt = "update bannette_contenu set date_ajout=sysdate() ";
		echo traite_rqt($rqt, "update bannette_contenu set date_ajout=sysdate");
		$rqt = "ALTER TABLE bannette_contenu DROP INDEX date_ajout ";
		echo traite_rqt($rqt, "ALTER bannette_contenu DROP INDEX date_ajout");
		$rqt = "ALTER TABLE bannette_contenu ADD INDEX date_ajout ( date_ajout ) ";
		echo traite_rqt($rqt, "ALTER bannette_contenu ADD INDEX date_ajout");
		$rqt = "ALTER TABLE bannettes ADD limite_type VARCHAR( 1 ) NOT NULL DEFAULT '' ";
		echo traite_rqt($rqt, "ALTER TABLE bannettes ADD limite_type ");
		$rqt = "ALTER TABLE bannettes ADD limite_nombre integer(6) not null default 0 ";
		echo traite_rqt($rqt, "ALTER TABLE bannettes ADD limite_nombre ");

		$rqt = "ALTER TABLE notices ADD notice_parent INT( 9 ) unsigned NOT NULL DEFAULT 0 ";
		echo traite_rqt($rqt, "ALTER TABLE notices ADD notice_parent ");
		$rqt = "ALTER TABLE notices DROP INDEX notice_parent ";
		echo traite_rqt($rqt, "ALTER TABLE notices DROP INDEX notice_parent");
		$rqt = "ALTER TABLE notices ADD INDEX ( notice_parent ) ";
		echo traite_rqt($rqt, "ALTER TABLE notices ADD INDEX ( notice_parent ) ");
		$rqt = "ALTER TABLE notices ADD relation_type char(1) NOT NULL DEFAULT 'a' ";
		echo traite_rqt($rqt, "ALTER TABLE notices ADD relation_type ");

		$rqt = "CREATE TABLE notices_langues (
				num_notice INT( 8 ) UNSIGNED NOT NULL default 0,
				type_langue int (1) UNSIGNED NOT NULL DEFAULT 0,
				code_langue CHAR( 3 ) DEFAULT '' NOT NULL
				)  ";	
		echo traite_rqt($rqt, "create table notices_langues ");
		$sql_migr = "select notice_id, lang_code from notices where lang_code!='' " ;
		$res_migr = mysql_query($sql_migr,$dbh);
		while ($obj_migr=mysql_fetch_object($res_migr)) {
			@mysql_query("insert into notices_langues (num_notice, type_langue , code_langue) values ($obj_migr->notice_id,0,'$obj_migr->lang_code')") ;
			}
		$sql_migr = "select notice_id, org_lang_code from notices where org_lang_code!='' " ;
		$res_migr = mysql_query($sql_migr,$dbh);
		while ($obj_migr=mysql_fetch_object($res_migr)) {
			@mysql_query("insert into notices_langues (num_notice, type_langue , code_langue) values ($obj_migr->notice_id,1,'$obj_migr->org_lang_code')") ;
			}
		$rqt = "ALTER TABLE notices_langues ADD PRIMARY KEY (num_notice,type_langue,code_langue)" ;
		echo traite_rqt($rqt, "alter table notices_langues add primary key");

		$rqt = "ALTER TABLE notices drop lang_code" ;
		echo traite_rqt($rqt, "alter table notices drop lang_code ");
		$rqt = "ALTER TABLE notices drop org_lang_code " ;
		echo traite_rqt($rqt, "alter table notices drop org_lang_code ");

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'categories' and sstype_param='categ_in_line' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'categories', 'categ_in_line', '0', 'Affichage des catégories en ligne.\n 0 : Non.\n 1 : Oui.', '',0) ";
			echo traite_rqt($rqt, "insert categories_categ_in_line=0 into parametres");
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='categories_categ_in_line' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'opac', 'categories_categ_in_line', '0', 'Affichage des catégories en ligne.\n 0 : Non.\n 1 : Oui.', 'i_categories',0) ";
			echo traite_rqt($rqt, "insert opac_categories_categ_in_line=0 into parametres");
			}

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.26");
		break;

	case "v4.26": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='label_construct_script' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'pmb', 'label_construct_script', '', 'Script de construction d\'étiquette de cote', '', 0)" ;
			echo traite_rqt($rqt,"insert pmb_label_construct_script into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'dsi' and sstype_param='func_after_diff' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'dsi', 'func_after_diff', '', 'Script à exécuter après diffusion d\'une bannette', '', 0)" ;
			echo traite_rqt($rqt,"insert dsi_func_after_diff into parametres") ;
			}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.27");
		break;

	case "v4.27": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		@set_time_limit(0);
		$sql_corr = "select s.typdoc as typdoc_s, a.notice_id as notice_id_dep from notices as s, analysis, bulletins, notices as a where a.niveau_biblio='a' and s.niveau_biblio='s' and s.notice_id=bulletin_notice and analysis_bulletin=bulletin_id and analysis_notice=a.notice_id " ;
		$res_corr = mysql_query($sql_corr,$dbh);
		while ($obj_corr=mysql_fetch_object($res_corr)) {
			@mysql_query("update notices set typdoc='".$obj_corr->typdoc_s."' where notice_id='".$obj_corr->notice_id_dep."'") ;
			}
		echo traite_rqt("select 1 from users","update analysis notices doctype with serial doctype ") ;

		$rqt="CREATE TABLE resa_planning (
  					id_resa mediumint(8) unsigned NOT NULL auto_increment,
  					resa_idempr mediumint(8) unsigned NOT NULL default '0',
  					resa_idnotice mediumint(8) unsigned NOT NULL default '0',
  					resa_date datetime default NULL,
  					resa_date_debut date NOT NULL default '0000-00-00',
  					resa_date_fin date NOT NULL default '0000-00-00',
  					resa_validee int(1) unsigned NOT NULL default '0',
  					resa_confirmee int(1) unsigned NOT NULL default '0',
  					PRIMARY KEY  (id_resa),
  					KEY resa_date_fin (resa_date_fin),
  					KEY resa_date (resa_date) 
				)";
 		echo traite_rqt($rqt,"create table resa_planning") ;
 		
 		$rqt="ALTER TABLE ouvertures ADD num_location INT( 3 ) UNSIGNED NOT NULL DEFAULT 1";
 		echo traite_rqt($rqt,"alter ouvertures add location") ;
 		$rqt="update ouvertures set num_location='".$deflt2docs_location."'";
 		echo traite_rqt($rqt,"update ouvertures set num_location") ;
 		
 		$rqt="update parametres set comment_param='Nombre de résultats affichés sur les pages suivantes' where type_param='opac' and sstype_param='search_results_per_page' ";
 		echo traite_rqt($rqt,"update opac search_results_per_page comments in parametres") ;
 		
 		$rqt="update parametres set comment_param='Quotas de prêts avancés ?\n 0 : Non\n 1 : Oui' where type_param='pmb' and sstype_param='quotas_avances' ";
 		echo traite_rqt($rqt,"update pmb quotas_avances comments in parametres") ;
 		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.28");
		break;

	case "v4.28": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='notice_groupe_fonction' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'opac', 'notice_groupe_fonction', '', 'Quel fichier/fonction inclure pour la présentation des resultats si toutes les notices d\'une recherche sont parmi les types mentionnés \n exemple : a,b text;c,d music;k photo fera include(text.inc.php) et appel à la fonction text()', 'd_aff_recherche', 0) ";
			echo traite_rqt($rqt,"insert opac_notice_groupe_fonction into parametres") ;
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='photo_mean_size_x' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'opac', 'photo_mean_size_x', '', 'Taille X de la photo format \'moyen\', si vide, pas de redimensionnement ', 'm_photo', 0) ";
			echo traite_rqt($rqt,"insert opac_photo_mean_size_x into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='photo_mean_size_y' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'opac', 'photo_mean_size_y', '', 'Taille Y de la photo format \'moyen\', si vide, pas de redimensionnement ', 'm_photo', 0) ";
			echo traite_rqt($rqt,"insert opac_photo_mean_size_y into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='photo_watermark' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'opac', 'photo_watermark', '', 'Watermark à ajouter sur les photos, si vide, pas de watermark', 'm_photo', 0) ";
			echo traite_rqt($rqt,"insert opac_photo_watermark into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='photo_show_form' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'opac', 'photo_show_form', '', 'Afficher le formulaire de commande de photo ? \n 0: Non \n 1:Oui', 'm_photo', 0) ";
			echo traite_rqt($rqt,"insert opac_photo_show_form into parametres") ;
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='photo_email_form' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'opac', 'photo_email_form', '', 'Emails des destinataires des commandes de photo à séparer par des espaces si multiples.', 'm_photo', 0) ";
			echo traite_rqt($rqt,"insert opac_photo_email_form into parametres") ;
			}

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.29");
		break;

	case "v4.29": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='photo_watermark_transparency' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'opac', 'photo_watermark_transparency', '50', 'Transparence du watermark de 0 à 100 en % ', 'm_photo', 0) ";
			echo traite_rqt($rqt,"insert opac_photo_watermark_transparency=50 into parametres") ;
			}

		// $opac_show_onglet_empr
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_onglet_empr' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'opac', 'show_onglet_empr', '0', 'Afficher l\'onglet de compte emprunteur avec les onglets de recherche ? \n 0: Non \n 1: Oui ', 'f_modules', 0) ";
			echo traite_rqt($rqt,"insert opac_show_onglet_empr=0 into parametres") ;
			}

		//Modification taille pret_idexpl dans la table pret et autres
		$rqt= 'ALTER TABLE pret CHANGE pret_idexpl pret_idexpl INT UNSIGNED NOT NULL DEFAULT 0, CHANGE pret_idempr pret_idempr INT UNSIGNED NOT NULL DEFAULT 0  ';
		echo traite_rqt($rqt,"alter pret change pret_idexpl, idempr INT");
		$rqt= 'ALTER TABLE empr CHANGE id_empr id_empr INT UNSIGNED NOT NULL auto_increment ';
		echo traite_rqt($rqt,"alter empr change id_empr, id_empr INT");
		$rqt= 'alter table exemplaires change expl_id expl_id INT UNSIGNED NOT NULL AUTO_INCREMENT, CHANGE expl_notice expl_notice INT UNSIGNED NOT NULL default 0, CHANGE expl_bulletin expl_bulletin INT UNSIGNED NOT NULL default 0';
		echo traite_rqt($rqt,"alter exemplaires change INTEGERS "); 
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.30");
		break;

	case "v4.30": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='url_base' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'pmb', 'url_base', 'http://SERVER/DIRECTORY/', 'URL de base de la gestion : typiquement mettre l\'url http://monserveur/pmb/ ne pas oublier le / final','')";
			echo traite_rqt($rqt,"insert pmb_url_base=http://SERVER/DIRECTORY/ into parametres");
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_empr' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'opac', 'show_empr', '0', 'Afficher l\'emprunteur actuel dans la liste des exemplaires ?\n 0 : non\n 1 : pour les abonnés\n 2 : pour tout le monde ', 'a_general', 0) ";
			echo traite_rqt($rqt,"insert opac_show_empr=0 into parametres") ;
			}
		
		//Ajout d'index sur les catégories pour accélérer les recherches
		$rqt = "ALTER TABLE categories DROP INDEX num_noeud ";
		echo traite_rqt($rqt,"drop index num_noeud on categories") ;
		$rqt = "ALTER TABLE categories ADD INDEX num_noeud (num_noeud) "; 
		echo traite_rqt($rqt,"add index num_noeud on categories") ;
		
		$rqt = "ALTER TABLE categories DROP INDEX libelle_categorie ";
		echo traite_rqt($rqt,"drop index libelle_categorie on categories") ;
		$rqt = "ALTER TABLE categories ADD INDEX libelle_categorie ( libelle_categorie(5) ) ";
		echo traite_rqt($rqt,"add index libelle_categorie on categories") ;

		// $opac_show_login_form_next
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_login_form_next' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'opac', 'show_login_form_next', '', 'Après connexion de l\'emprunteur se diriger vers quel module ? \n Vide = Compte emprunteur \n index.php = Retour en accueil', 'f_modules', 0) ";
			echo traite_rqt($rqt,"insert opac_show_login_form_next='' into parametres") ;
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='allow_term_troncat_search' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'opac', 'allow_term_troncat_search', '0', 'Troncature automatique à droite \n 1 = oui \n 0 = non', 'c_recherche', 0)" ;
			echo traite_rqt($rqt,"insert opac_allow_term_troncat_search=0 into parametres") ;
			}		

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.31");
		break;

	case "v4.31": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		// Affichage de résultats sur la première page
		if(mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_results_first_page' "))==0){
			$rqt = "INSERT INTO parametres ( id_param , type_param , sstype_param , valeur_param , comment_param , section_param , gestion ) 		 
				VALUES (0 , 'opac', 'show_results_first_page', '0', 'Affichage de résultats sur la première page lors d\'une recherche pour tous les champs \n 0=non \n 1=oui.', 'd_aff_recherche', '0')";
			echo traite_rqt($rqt, "insert opac_show_results_first_page=0 into parametres");
			}
		// Nombre de résultats
		if(mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='nb_results_first_page' "))==0){
			$rqt = "INSERT INTO parametres ( id_param , type_param , sstype_param , valeur_param , comment_param , section_param , gestion ) 
				VALUES (0 , 'opac', 'nb_results_first_page', '10', 'Nombres de notices à afficher lors d\'une recherche pour le critère Tous les champs.', 'd_aff_recherche', '0')";
			echo traite_rqt($rqt, "insert opac_nb_results_first_page=10 into parametres");
			}
		// Ajout d'un paramètre pour l'affichage ou non des infobulles dans les catégories
		if(mysql_num_rows(mysql_query("select 1 from parametres where type_param='opac' and sstype_param='show_infobulles_categ'"))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
				VALUES (0, 'opac', 'show_infobulles_categ', '0', 'Affichage des infobulles sur les libellés des catégories. \n 0=non \n 1=oui', 'i_categories', '0')";
			echo traite_rqt($rqt, " insert opac_show_infobulles_categ=0 into parametres");
			}
		$rqt = "ALTER TABLE resa CHANGE resa_cb resa_cb VARCHAR( 255 ) NOT NULL";
		echo traite_rqt($rqt, " alter table resa change resa_cb varchar (255) "); 

		$rqt = "ALTER TABLE resa_ranger CHANGE resa_cb resa_cb VARCHAR( 255 ) NOT NULL default '' ";
		echo traite_rqt($rqt, " alter table resa_ranger change resa_cb varchar (255) "); 

		// Info de réindexation
		$rqt = " select 1 " ;
		echo traite_rqt($rqt,"<b><a href='".$base_path."/admin.php?categ=netbase' target=_blank>VOUS DEVEZ REINDEXER / YOU MUST REINDEX : Admin > Outils > Nettoyage de base</a></b> ") ;
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.32");
		break;

	case "v4.32": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		//Ajout d'un parametre d'affichage personnalise des suggestions (gestion)
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='sugg_display' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES (0, 'acquisition', 'sugg_display', '', 
					'Nom de la fonction personnalisée d\'affichage des suggestions',	'',0) ";
			echo traite_rqt($rqt, "insert acquisition_sugg_display into parameters");
		}

		//Ajout d'un parametre pour affecter les suggestions a une categorie de suggestions cote gestion
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='sugg_categ' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES (0, 'acquisition', 'sugg_categ', '0', 
					'Affectation des suggestions à une catégorie de suggestions.\n 0 : Non\n 1 : Oui',
					'',0) ";
			echo traite_rqt($rqt, "insert acquisition_sugg_categ into parameters");
		}

		//Categorie de suggestions par defaut cote gestion
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='sugg_categ_default' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES (0, 'acquisition', 'sugg_categ_default', '1', 
					'Identifiant de la catégorie de suggestions par défaut.',
					'',0) ";
			echo traite_rqt($rqt, "insert acquisition_sugg_categ_default=1 into parameters");
		}
		
		//Ajout d'un parametre pour affecter les suggestions a une categorie cote OPAC
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='sugg_categ' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'opac','sugg_categ','0','Affectation des suggestions à une catégorie de suggestions.\n 0 : Non.\n 1 : Oui.','a_general',0)" ;
			echo traite_rqt($rqt,"insert opac_sugg_categ into parametres") ;
		}

		//Categorie de suggestions par defaut cote OPAC
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='sugg_categ_default' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES (0, 'opac', 'sugg_categ_default', '1', 
					'Identifiant de la catégorie de suggestions par défaut.',
					'a_general',0) ";
			echo traite_rqt($rqt, "insert opac_sugg_categ_default=1 into parameters");
		}

		//Creation d'une table categories de suggestions
		$rqt = "CREATE TABLE suggestions_categ ( id_categ INT(12) NOT NULL AUTO_INCREMENT PRIMARY KEY, libelle_categ VARCHAR(255) NOT NULL ) "; 
		echo traite_rqt($rqt, "create table suggestions_categ");

		//Creation d'une categorie de suggestions par defaut
		$rqt = "INSERT INTO suggestions_categ ( id_categ, libelle_categ ) VALUES ( '1', 'catégorie par défaut' ) ";
		echo traite_rqt($rqt, "create default sugg_categ");

		//Attribution des suggestions a la categorie par defaut
		$rqt = "ALTER TABLE suggestions ADD num_categ INT(12) NOT NULL DEFAULT '1' ";
		echo traite_rqt($rqt, "alter table suggestions add default num_categ");  

		//Parametre script de substitution pour impression listes de suggestions
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdfsug_print' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdfsug_print','','Quel script utiliser pour personnaliser l\'impression des listes de suggestions ?','pdfsug',0)" ;
			echo traite_rqt($rqt,"insert pdfsug_print into parametres") ;
		}
		//Parametre script de substitution pour impression devis
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdfdev_print' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdfdev_print','','Quel script utiliser pour personnaliser l\'impression des devis ?','pdfdev',0)" ;
			echo traite_rqt($rqt,"insert pdfdev_print into parametres") ;
		}
		//Parametre script de substitution pour impression commande
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdfcde_print' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdfcde_print','','Quel script utiliser pour personnaliser l\'impression des commandes ?','pdfcde',0)" ;
			echo traite_rqt($rqt,"insert pdfsug_print into parametres") ;
		}
		//Parametre script de substitution pour impression bon de livraison
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdfliv_print' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdfliv_print','','Quel script utiliser pour personnaliser l\'impression des bons de livraison ?','pdfliv',0)" ;
			echo traite_rqt($rqt,"insert pdfliv_print into parametres") ;
		}
		//Parametre script de substitution pour impression facture
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='pdffac_print' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','pdffac_print','','Quel script utiliser pour personnaliser l\'impression des factures ?','pdffac',0)" ;
			echo traite_rqt($rqt,"insert pdffac_print into parametres") ;
		}
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.33");
		break;

	case "v4.33": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		// Correction tnvol = NULL sur création de périos
		$rqt = "update notices set tnvol='' where tnvol is null "; 
		echo traite_rqt($rqt, "update notice set tnvol not null ");
		$rqt = "alter table notices change tnvol tnvol varchar(100) not null default '' "; 
		echo traite_rqt($rqt, "update notice set tnvol not null ");

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.34");
		break;

	case "v4.34": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		//Parametre format d'affichage du reduit de notices cote Gestion 
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='notice_reduit_format' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'pmb','notice_reduit_format', '0','Format d\'affichage des réduits des notices : \n 0 = titre+auteur principal\n 1 = titre+auteur principal+date édition\n','',0)" ;
			echo traite_rqt($rqt,"insert notice_reduit_format into parametres") ;
			}
		
		//Parametre resa planifiees cote Gestion
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='resa_planning' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'pmb', 'resa_planning', '0', 'Utiliser un planning de réservation ? \n 0: Non \n 1: Oui', '', 0)";
			echo traite_rqt($rqt,"insert resa_planning='0' into parametres");
			}

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.35");
		break;

	case "v4.35": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='antivol' "))==0) {
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'antivol', '0', 'Système magnétique antivol à télécommander ? \n 1 Oui \n 0 Non')";
			echo traite_rqt($rqt,"insert pmb_antivol='0' into parametres");
			}
		$rqt = "ALTER TABLE exemplaires ADD type_antivol INT( 1 ) UNSIGNED NOT NULL DEFAULT '0'" ;
		echo traite_rqt($rqt, "alter exemplaires add type_antivol ");
		// si type_anti_vol=0 >> NE PAS MAGNETISER
		//                 =1 >> MAGNETISER normalement
		//                 >1 : autres traitements


		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='custom_calc_numero' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'acquisition','custom_calc_numero','','Fonction personnalisée de numérotation des actes d\'achats.','',0)" ;
			echo traite_rqt($rqt,"insert acquisition_custom_calc_numero into parametres") ;
			}

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.36");
		break;

	case "v4.36": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE bannettes ADD update_type CHAR( 1 ) DEFAULT 'C' NOT NULL " ;
		echo traite_rqt($rqt, "alter bannettes add update_type ");
		
		$rqt = "ALTER TABLE notices CHANGE code code VARCHAR(50) NOT NULL default '' ";
		echo traite_rqt($rqt, "alter notice change CODE varchar(50) ");

		$rqt = "ALTER TABLE users CHANGE username username VARCHAR(100) NOT NULL default '' ";
		echo traite_rqt($rqt, "alter users change USERNAME varchar(100) ");

		$rqt = "ALTER TABLE users ADD speci_coordonnees_etab mediumtext not null default '' ";
		echo traite_rqt($rqt,"alter table users add speci_coordonnees_etab ") ;

		//Ajout d'un index pour recherche dans les lignes d'actes
		$rqt = "ALTER TABLE lignes_actes ADD index_ligne TEXT NOT NULL ;";
		echo traite_rqt($rqt,"insert index_ligne into lignes_actes") ;

		//Info de Reindexation des acquisitions
		$rqt = " select 1 " ;
		echo traite_rqt($rqt,"<b><a href='".$base_path."/admin.php?categ=netbase' target=_blank>VOUS DEVEZ REINDEXER / YOU MUST REINDEX : Admin > Outils > Nettoyage de base</a></b> ") ;

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.37");
		break;

	case "v4.37": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE parametres CHANGE comment_param comment_param LONGTEXT DEFAULT ''";
		echo traite_rqt($rqt, "alter parametres change comment_param LONGTEXT ");

		$rqt = "CREATE TABLE notices_relations (
			num_notice bigint(20) unsigned NOT NULL default 0,
			linked_notice bigint(20) unsigned NOT NULL default 0,
			relation_type char(1) not null default '',
			rank int(11) not null default 0,
			PRIMARY KEY  (num_notice,linked_notice),
			KEY linked_notice (linked_notice),
			KEY relation_type (relation_type) )"; 
		echo traite_rqt($rqt,"CREATE TABLE notices_relations ") ;

		$rqt = "insert into notices_relations select notice_id, notice_parent,relation_type,0 from notices where notice_parent>0";
		echo traite_rqt($rqt,"insert into notices_relations old relations ") ;
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='numero_exemplaire_auto' "))==0){
			$rqt = "INSERT INTO parametres (type_param ,sstype_param ,valeur_param ,comment_param) VALUES ('pmb','numero_exemplaire_auto','0','Autorise la numérotation automatique d\'exemplaire ? \r\n1 Oui 0 Non')" ;
			echo traite_rqt($rqt,"insert pmb_numero_exemplaire_auto into parametres") ;
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='numero_exemplaire_auto_script' "))==0){
			$rqt = "INSERT INTO parametres (type_param ,sstype_param ,valeur_param ,comment_param) VALUES ('pmb','numero_exemplaire_auto_script','gen_code/gen_code_exemplaire.php','Nom du fichier de Script php pour la génération des codes d\'exemplaires en automatique')" ;
			echo traite_rqt($rqt,"insert pmb_numero_exemplaire_auto_script into parametres") ;
			}

		$rqt = "CREATE TABLE exemplaires_temp (cb VARCHAR( 50 ) NOT NULL ,sess VARCHAR( 12 ) NOT NULL ,UNIQUE (cb))";
		echo traite_rqt($rqt,"create table exemplaires_temp ") ;

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'empr' and sstype_param='lecteur_controle_doublons' "))==0){
			$rqt = "INSERT INTO parametres (type_param ,sstype_param ,valeur_param ,comment_param) VALUES ('empr','lecteur_controle_doublons','0','Contrôle sur les doublons de lecteurs:\r\n0 : pas de controle sur les doublons, en saisie de fiche de lecteur. \r\n1,empr_nom,empr_prenom,... : recherche doublons sur les champs \'empr\', \r\n2,empr_nom,empr_prenom,... : recherche doublons sur les champs \'empr\', et champ personnalisables.\r\n3,empr_nom, empr_prenom ,... : idem, en rajoutant le test sur le groupe.')" ;
			echo traite_rqt($rqt,"insert empr_lecteur_controle_doublons into parametres") ;
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='pret_nombre_prolongation' "))==0){
			$rqt = "INSERT INTO parametres (id_param ,type_param ,sstype_param ,valeur_param ,comment_param ,section_param ,gestion) VALUES (NULL , 'pmb', 'pret_nombre_prolongation', '3', 'Nombre de prolongations autorisées', '', '0')" ;
			echo traite_rqt($rqt,"insert pmb_pret_nombre_prolongation into parametres") ;
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='pret_restriction_prolongation' "))==0){
			$rqt = "INSERT INTO parametres (id_param ,type_param ,sstype_param ,valeur_param ,comment_param ,section_param ,gestion) VALUES (NULL , 'pmb', 'pret_restriction_prolongation', '0', '0 : pas de restriction\r\n1 : prolongation limitée au paramètre pret_nombre_prolongation \r\n2 : prolongation gérée par les quotas ', '', '0')" ;
			echo traite_rqt($rqt,"insert pmb_pret_restriction_prolongation into parametres") ;
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='pret_prolongation' "))==0){
			$rqt = "INSERT INTO parametres (id_param ,type_param ,sstype_param ,valeur_param ,comment_param ,section_param ,gestion)VALUES (NULL , 'opac','pret_prolongation', '0', '0 : pas de prolongation\r\n1 : prolongation autorisée', 'a_general', '0')" ;
			echo traite_rqt($rqt,"insert opac_pret_prolongation into parametres") ;
			}

		$rqt = "ALTER TABLE bannettes ADD update_type CHAR( 1 ) DEFAULT 'C' NOT NULL " ;
		echo traite_rqt($rqt, "alter bannettes add update_type ");

		$rqt = "create table abts_modeles (
					modele_id integer unsigned not null auto_increment,
					modele_name varchar(255) not null default '',
					num_notice integer unsigned not null default 0,
					num_periodicite integer unsigned not null default 0,
					duree_abonnement integer not null default 0,
					date_debut date,
					date_fin date,
					days varchar(7) not null default '1111111',
					day_month varchar(31) not null default '1111111111111111111111111111111',
					week_month varchar(6) not null default '111111',
					week_year varchar(54) not null default '111111111111111111111111111111111111111111111111111111',
					month_year varchar(12) not null default '111111111111',
					primary key (modele_id),
					index num_notice (num_notice),
					index num_periodicite (num_periodicite))";
		echo traite_rqt($rqt, "create table abts_modeles ");

		$rqt = "create table abts_abts (
					abt_id integer unsigned not null auto_increment,
					abt_name varchar(255) not null default '',
					base_modele_name varchar(255) not null default '',
					base_modele_id integer not null default 0,
					primary key (abt_id)) ";
		echo traite_rqt($rqt, "create table abts_abts");

		$rqt = "create table abts_periodicites (
					periodicite_id integer unsigned not null auto_increment,
					libelle varchar(255) not null default '',
					duree integer not null default 0,
					primary key(periodicite_id)) ";
		echo traite_rqt($rqt, "create table abts_periodicites");

		$rqt = "create table abts_grille_abt (
					num_abt integer unsigned not null default 0,
					date_parution date not null,
					primary key(num_abt,date_parution),
					index num_abt (num_abt))";
		echo traite_rqt($rqt, "create table abts_grille_abt");

		$rqt = "create table abts_grille_modele (
					num_modele integer unsigned not null default 0,
					date_parution date not null,
					primary key(num_modele,date_parution),
					index num_modele (num_modele))";
		echo traite_rqt($rqt, "create table abts_grille_modele ");

		$rqt = "ALTER TABLE pret ADD retour_initial DATE NULL DEFAULT '0000-00-00', ADD cpt_prolongation INT( 1 ) NOT NULL DEFAULT '0'" ;
		echo traite_rqt($rqt, "alter exemplaires add retour_initial & cpt_prolongation ");

		$rqt = "ALTER TABLE notices CHANGE code code VARCHAR(50) NOT NULL default '' ";
		echo traite_rqt($rqt, "alter notice change CODE varchar(50) ");

		$rqt = "ALTER TABLE users CHANGE username username VARCHAR(100) NOT NULL default '' ";
		echo traite_rqt($rqt, "alter users change USERNAME varchar(100) ");

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.38");
		break;

	case "v4.38": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "CREATE TABLE empr_statut (  idstatut smallint(5) unsigned NOT NULL auto_increment,  statut_libelle varchar(255) not null default '',  allow_loan tinyint(4) NOT NULL default '1',  allow_book tinyint(4) NOT NULL default '1',  allow_opac tinyint(4) NOT NULL default '1',  allow_dsi tinyint(4) NOT NULL default '1',  allow_dsi_priv tinyint(4) NOT NULL default '1',  PRIMARY KEY  (idstatut)) ";
		echo traite_rqt($rqt, "create table empr_statut ");

		$rqt = "ALTER TABLE empr_statut ADD allow_sugg tinyint(4) NOT NULL default '1', ADD allow_prol tinyint(4) NOT NULL default '1' ";
		echo traite_rqt($rqt, "ALTER TABLE empr_statut ADD allow_sugg, allow_prol ");

		if (mysql_num_rows(mysql_query("select 1 from empr_statut where idstatut=1 "))==0){
			$rqt = "INSERT INTO empr_statut (idstatut ,statut_libelle) VALUES (1, 'Actif')" ;
			echo traite_rqt($rqt,"insert Actif into empr_statut into parametres") ;
			}

		if (mysql_num_rows(mysql_query("select 1 from empr_statut where idstatut=2 "))==0){
			$rqt = "INSERT INTO empr_statut (idstatut, statut_libelle, allow_loan, allow_book, allow_opac, allow_dsi, allow_dsi_priv, allow_sugg, allow_prol) VALUES (2, 'Interdit', 0, 0, 0, 0, 0, 0, 0)" ;
			echo traite_rqt($rqt,"insert Interdit into empr_statut into parametres") ;
			}

		$rqt = "ALTER TABLE empr ADD total_loans BIGINT UNSIGNED DEFAULT 0 NOT NULL, ADD empr_statut BIGINT UNSIGNED DEFAULT 1 NOT NULL " ;
		echo traite_rqt($rqt, "alter empr add empr_statut, total_loans ");

		$rqt = "ALTER TABLE users ADD deflt_empr_statut BIGINT UNSIGNED DEFAULT '1' NOT NULL AFTER deflt2docs_location ";
		echo traite_rqt($rqt, "add deflt_empr_statut in table users");
		
		$rqt = "update users set rights=rights+2048 where rights<2048 ";
		echo traite_rqt($rqt, "update users add thesaurus rights ");
		
		$rqt = "INSERT ignore INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0,'mailretard','1after_list_group','Nous vous remercions de prendre rapidement contact par téléphone au \$biblio_phone ou par mail à \$biblio_email pour étudier la possibilité de prolonger les emprunts de votre groupe ou de rapporter les ouvrages concernés.','Texte apparaissant après la liste des ouvrages en retard dans le mail','',0)";
		echo traite_rqt($rqt, "INSERT INTO parametres mailretard_1after_list_group");
		
		$rqt = "INSERT ignore INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0,'mailretard','1before_list_group','Sauf erreur de notre part, les emprunteurs de votre groupe ont toujours en leur possession le ou les ouvrage(s) suivant(s) dont la durée de prêt est aujourd\'hui dépassée :','Texte apparaissant avant la liste des ouvrages en retard dans le mail de relance de retard','',0)";
		echo traite_rqt($rqt, "INSERT INTO parametres mailretard_1before_list_group");
		
		$rqt = "INSERT ignore INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0,'mailretard','1fdp_group','Le responsable.','Signataire du mail de relance de retard','',0)";
		echo traite_rqt($rqt, "INSERT INTO parametres mailretard_1fdp_group");
		
		$rqt = "INSERT ignore INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0,'mailretard','1madame_monsieur_group','Madame, Monsieur','Entête du mail','',0)";
		echo traite_rqt($rqt, "INSERT INTO parametres mailretard_1madame_monsieur_group");
		
		$rqt = "INSERT ignore INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0,'mailretard','1objet_group','\$biblio_name : documents en retard','Objet du mail de relance de retard','',0)";
		echo traite_rqt($rqt, "INSERT INTO parametres mailretard_1objet_group");
		
		// TRAITEMENT DES BULLETINS
		$rqt = " select 1 " ;
		echo traite_rqt($rqt,"<b><font color=red size=+1>Création de nouvelles notices liées aux bulletins / Creation of new records linked to issues</font></b> ") ;
		
		$rqt = "alter table bulletins add num_notice integer unsigned not null default 0";
		echo traite_rqt($rqt, "alter table bulletins add num_notice ");
		
		//Recherche des bulletins
		/*$requete="select bulletin_id,bulletin_numero,bulletin_notice,mention_date,date_date,bulletin_titre,index_titre,bulletin_cb from bulletins where num_notice=0";
		$resultat=mysql_query($requete);
		
		while ($r=mysql_fetch_object($resultat)) {
			//Recherche de la notice mère
			$requete="select * from notices where notice_id=".$r->bulletin_notice;
			$res_perio=mysql_query($requete);
			$r_p=mysql_fetch_object($res_perio);
			//Création de la notice 
			
			if ($r->bulletin_titre) 
				$titre=$r->bulletin_titre; 
			else {
				$titre=$r_p->tit1." - ".$r->bulletin_numero." ".(trim($r->mention_date)?trim($r->mention_date):formatdate($r->date_date));
				print $r->date_date." ".formatdate($r->date_date)."\n";
			}
			$requete="insert into notices (typdoc,tit1,index_wew,index_sew,niveau_biblio,niveau_hierar) values('".$r_p->typdoc."',
			'".addslashes($titre)."','".addslashes($titre)."',
			' ".addslashes(strip_empty_words($titre))." ','b',2)";
			mysql_query($requete);
			$id_bull=mysql_insert_id();
			$requete="update bulletins set num_notice=".$id_bull." where bulletin_id=".$r->bulletin_id;
			mysql_query($requete);  
			//Mise à jour des liens bulletin -> notice mère
			$requete="insert into notices_relations (num_notice,linked_notice,relation_type,rank) values($id_bull,$r_p->notice_id,'b',1)";
			mysql_query($requete);
			//Recherche des articles
			$requete="select analysis_notice from analysis where analysis_bulletin=".$r->bulletin_id;
			$resultat_analysis=mysql_query($requete);
			$n=1;
			while ($r_a=mysql_fetch_object($resultat_analysis)) {
				$requete="insert into notices_relations (num_notice,linked_notice,relation_type,rank) values(".$r_a->analysis_notice.",$id_bull,'a',$n)";
				mysql_query($requete);
				$n++;
			}
		}*/
		$rqt = "ALTER TABLE notices DROP relation_type ";
		echo traite_rqt($rqt,"ALTER notices DROP relation_type") ;
		$rqt = "ALTER TABLE notices DROP notice_parent ";
		echo traite_rqt($rqt,"ALTER notices DROP notice_parent") ;
		
		$rqt = " select 1 " ;
		echo traite_rqt($rqt,"<b><font color=blue size=+1>Nouvelles notices liées aux bulletins : FIN / New notices linked to issues : END</font></b> ") ;

		$rqt = "ALTER TABLE abts_grille_modele ADD type_serie INT NOT NULL DEFAULT 0, ADD numero VARCHAR(50) not NULL default ''" ;
		echo traite_rqt($rqt,"ALTER TABLE abts_grille_modele ADD type_serie, numero ") ;

		$rqt = "ALTER TABLE abts_grille_modele DROP PRIMARY KEY, ADD PRIMARY KEY( num_modele , date_parution , type_serie )" ;
		echo traite_rqt($rqt,"ALTER TABLE abts_grille_modele change PRIMARY KEY") ;

		$rqt = "ALTER TABLE abts_periodicites ADD unite INT NOT NULL DEFAULT 0" ;
		echo traite_rqt($rqt,"ALTER TABLE abts_periodicites ADD unite") ;

		$rqt = "ALTER TABLE empr CHANGE empr_mail empr_mail VARCHAR(255) NOT NULL default '' " ;
		echo traite_rqt($rqt,"ALTER TABLE empr CHANGE empr_mail varchar(255)") ;

		$rqt = "ALTER TABLE users ADD value_email_bcc VARCHAR( 255 ) DEFAULT '' NOT NULL " ;
		echo traite_rqt($rqt,"ALTER TABLE user ADD email_bcc varchar(255)") ;

		$rqt = "INSERT ignore INTO parametres (id_param ,type_param ,sstype_param, valeur_param, comment_param, section_param ,gestion) VALUES (NULL , 'opac','pret_duree_prolongation', '15', 'Nombre de jours de prolongation autorisé', 'a_general', '0')";
		echo traite_rqt($rqt, "INSERT parametre opac_pret_duree_prolongation ");

		$rqt = "ALTER TABLE abts_modeles ADD num_cycle INT NOT NULL DEFAULT '0',
				ADD num_combien INT NOT NULL DEFAULT '0',
				ADD num_depart INT NOT NULL DEFAULT '0',
				ADD vol_actif INT NOT NULL DEFAULT '0',
				ADD vol_increment INT NOT NULL DEFAULT '0',
				ADD vol_date_unite INT NOT NULL DEFAULT '0',
				ADD vol_increment_numero INT NOT NULL DEFAULT '0',
				ADD vol_increment_date INT NOT NULL DEFAULT '0',
				ADD vol_cycle INT NOT NULL DEFAULT '0',
				ADD vol_combien INT NOT NULL DEFAULT '0',
				ADD vol_depart INT NOT NULL DEFAULT '0',
				ADD tom_actif INT NOT NULL DEFAULT '0',
				ADD tom_increment INT NOT NULL DEFAULT '0',
				ADD tom_date_unite INT NOT NULL DEFAULT '0',
				ADD tom_increment_numero INT NOT NULL DEFAULT '0',
				ADD tom_increment_date INT NOT NULL DEFAULT '0',
				ADD tom_cycle INT NOT NULL DEFAULT '0',
				ADD tom_combien INT NOT NULL DEFAULT '0',
				ADD tom_depart INT NOT NULL DEFAULT '0',
				ADD format_aff VARCHAR( 255 ) NOT NULL default ''";
		echo traite_rqt($rqt, "ALTER TABLE abts_modeles ADD ... ");

		$rqt = "ALTER TABLE abts_grille_modele ADD nombre_recu INT NOT NULL DEFAULT '1'";
		echo traite_rqt($rqt, "ALTER TABLE abts_grille_modele ADD nombre_recu ");

		$rqt = "CREATE TABLE notices_mots_global_index (
				id_notice mediumint(8) NOT NULL default '0',
				code_champ int(2) NOT NULL default '0',
				mot varchar(100) NOT NULL default '',
				nbr_mot int(5) NOT NULL default '0',
				PRIMARY KEY  (id_notice,code_champ,mot),
				KEY code_champ (code_champ),
				KEY mot (mot)) ";
		echo traite_rqt($rqt, "CREATE TABLE notices_mots_global_index");

		$rqt = "ALTER TABLE abts_periodicites add retard_periodicite INT(4) DEFAULT 0";
		echo traite_rqt($rqt, "ALTER TABLE abts_periodicites add retard_periodicite");

		$rqt = "ALTER TABLE abts_periodicites add seuil_periodicite INT(4) DEFAULT 0";
		echo traite_rqt($rqt, "ALTER TABLE abts_periodicites add seuil_periodicite");
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='surlignage' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'opac', 'surlignage', '0', 'Surligner les mots recherchés :\n0 : pas de surlignage\n1 : surlignage obligatoire\n2 : surlignage activable\n3 : surlignage désactivable', 'd_aff_recherche', 0) ";
			echo traite_rqt($rqt,"insert opac_surlignage into parametres") ;
			}

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.39");
		break;

	case "v4.39": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE abts_abts ADD num_notice INT NOT NULL DEFAULT '0' ,ADD date_debut DATE NOT NULL default '0000-00-00',ADD date_fin DATE NOT NULL default '0000-00-00', ADD fournisseur INT NOT NULL DEFAULT '0' , ADD destinataire VARCHAR( 255 ) NOT NULL default '', ADD cote VARCHAR( 255 ) NOT NULL default '', ADD typdoc_id INT NOT NULL DEFAULT '0', ADD exemp_auto INT NOT NULL DEFAULT '0', ADD location_id INT NOT NULL DEFAULT '0', ADD section_id INT NOT NULL DEFAULT '0',ADD lender_id INT NOT NULL DEFAULT '0',ADD statut_id INT NOT NULL DEFAULT '0',ADD codestat_id INT NOT NULL DEFAULT '0',ADD type_antivol INT NOT NULL DEFAULT '0'";
		echo traite_rqt($rqt, "ALTER TABLE abts_abts ADD num_notice , etc.");

		$rqt = "CREATE TABLE abts_abts_modeles (modele_id INT NOT NULL DEFAULT '0' ,abt_id INT NOT NULL DEFAULT '0' ,num INT NOT NULL DEFAULT '0' ,vol INT NOT NULL DEFAULT '0' ,tome INT NOT NULL DEFAULT '0' ,delais INT NOT NULL DEFAULT '0' ,critique INT NOT NULL  DEFAULT '0',PRIMARY KEY ( modele_id , abt_id )) ";
		echo traite_rqt($rqt, "CREATE TABLE abts_abts_modeles ");

		$rqt = "ALTER TABLE abts_grille_abt ADD modele_id INT NOT NULL DEFAULT '0', ADD type INT NOT NULL DEFAULT '0', ADD nombre INT NOT NULL DEFAULT '0', ADD numero INT NOT NULL DEFAULT '0'";
		echo traite_rqt($rqt, "ALTER TABLE abts_grille_abt ADD modele_id, etc.");

		$rqt = "ALTER TABLE abts_grille_abt DROP PRIMARY KEY, ADD PRIMARY KEY ( num_abt , date_parution , modele_id , type )";
		echo traite_rqt($rqt, "ALTER TABLE abts_grille_abt PRIMARY KEY ( num_abt , date_parution , modele_id , type )");

		$rqt = "INSERT ignore INTO parametres (id_param ,type_param ,sstype_param ,valeur_param,comment_param ,section_param ,gestion) VALUES (0 , 'pmb', 'first_week_day_format', '0', 'Format de la semaine: \n 0, la semaine commence le lundi \n 1 la semaine commence le dimanche', '', '0')";
		echo traite_rqt($rqt, "INSERT parametre pmb_first_week_day_format");

		// il faut créer l'origine de catalogage id=1 libelle = INTERNE
		$rqt_verif=mysql_query("select orinot_id, orinot_nom from origine_notice where orinot_id=1 ");
		if (mysql_num_rows($rqt_verif)==0){
			$rqt_id_interne = mysql_query("select orinot_id from origine_notice where orinot_nom='INTERNE' ");
			if (mysql_num_rows($rqt_id_interne)) {
				// Id 1 n'existe pas mais libellé 'INTERNE' existe, on lui met l'id 1
				$id_interne=mysql_fetch_object($rqt_id_interne) ;
				$idinterne=$id_interne->orinot_id;
				$rqt = "update origine_notice set orinot_id=1 where orinot_id=$idinterne" ;
				echo traite_rqt($rqt,"UPDATE origine_notice set id=1 where lable=INTERNE") ;
				} else {
					$rqt = "INSERT INTO origine_notice (orinot_id,orinot_nom,orinot_pays,orinot_diffusion) VALUES (1, 'INTERNE', 'FR', '1')" ;
					echo traite_rqt($rqt,"INSERT interne into origine_notice") ;
					}
			} else {
				$verif=mysql_fetch_object($rqt_verif) ;
				if ($verif->orinot_nom!='INTERNE') {
					// existe bien avec Id 1 mais libellé pas 'INTERNE'
					$rqt_origine = mysql_query("SELECT max(orinot_id) as maxid FROM origine_notice ");
					$id_max = mysql_fetch_object($rqt_origine);
					$maxid=$id_max->maxid+1;
					mysql_query("update origine_notice set orinot_id=$maxid where orinot_id=1 ");
					$rqt = "INSERT INTO origine_notice (orinot_id,orinot_nom,orinot_pays,orinot_diffusion) VALUES (1, 'INTERNE', 'FR', '1')" ;
					echo traite_rqt($rqt,"INSERT interne into origine_notice") ;
					}
				}
		$rqt = "update notices set origine_catalogage=1 where origine_catalogage=0 or origine_catalogage is null" ;
		echo traite_rqt($rqt,"update notices set origine_catalogage") ;
			
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='export_allow_expl' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'opac', 'export_allow_expl', '0', 'Exporter les exemplaires avec les notices : \n 0 : Non \n 1 : Oui', 'a_general', 0)";
			echo traite_rqt($rqt,"insert opac_export_allow_expl='0' into parametres");
			}

		$rqt = "ALTER TABLE abts_grille_abt ADD ordre INT NOT NULL DEFAULT '0', ADD state INT NOT NULL DEFAULT '0'";
		echo traite_rqt($rqt, "ALTER TABLE abts_grille_abt ADD ordre, state");

		$rqt = "ALTER TABLE abts_grille_abt DROP PRIMARY KEY";
		echo traite_rqt($rqt, "ALTER TABLE abts_grille_abt DROP PRIMARY KEY");

		$rqt = "ALTER TABLE abts_grille_abt ADD id_bull INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
		echo traite_rqt($rqt, "ALTER TABLE abts_grille_abt ADD id_bull INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST");

		$rqt = "ALTER TABLE abts_abts ADD duree_abonnement int(11) NOT NULL DEFAULT '0' ";
		echo traite_rqt($rqt, "ALTER TABLE abts_abts ADD duree_abonnement ");

		$rqt = "CREATE TABLE tris (id_tri int(4) NOT NULL auto_increment, tri_par varchar(100) NOT NULL default '', nom_tri varchar(100) NOT NULL default '', tri_par_texte varchar(100) NOT NULL default '',  PRIMARY KEY  (id_tri))";
		echo traite_rqt($rqt, "CREATE TABLE tris "); 
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='nb_max_tri' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'opac', 'nb_max_tri', '50', 'Nombre maximum de notices pour lesquelles le tri est autorisé.', 'c_recherche', 0) ";
			echo traite_rqt($rqt,"insert opac_nb_max_tri into parametres") ;
			}
			
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='nb_max_tri' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'pmb', 'nb_max_tri', '50', 'Nombre maximum de notices pour lesquelles le tri est autorisé.', '', 0) ";
			echo traite_rqt($rqt,"insert pmb_nb_max_tri into parametres") ;
			}
			
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.40");
		break;

	case "v4.40": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE empr CHANGE date_fin_blocage date_fin_blocage DATE DEFAULT '0000-00-00' NOT NULL";
		echo traite_rqt($rqt, "alter table empr change date_fin_blocage not null default 0000-00-00 "); 
		
		$rqt = "ALTER TABLE abts_modeles ADD num_increment INT NOT NULL default '0' AFTER num_combien ,ADD num_date_unite INT NOT NULL default '0' AFTER num_increment ,ADD num_increment_date INT NOT NULL default '0' AFTER num_date_unite";
		echo traite_rqt($rqt, "ALTER TABLE abts_modeles ADD num_increment, num_date_unite, num_increment_date "); 

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='nb_max_criteres_tri' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'opac', 'nb_max_criteres_tri', '3', 'Nombre maximum de critères de tri à afficher.', 'c_recherche', 0) ";
			echo traite_rqt($rqt,"insert opac_nb_max_criteres_tri into parametres") ;
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'empr' and sstype_param='show_caddie' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'empr', 'show_caddie', '0', 'Afficher le module de paniers de lecteurs: \n 0: Non \n 1: Oui', '', 0) ";
			echo traite_rqt($rqt,"insert empr_show_caddie into parametres") ;
			}
		$rqt = "CREATE TABLE empr_caddie ( idemprcaddie int(8) unsigned NOT NULL auto_increment, name varchar(100) default NULL, comment varchar(255) default NULL, autorisations mediumtext, PRIMARY KEY  (idemprcaddie) ) ";
		echo traite_rqt($rqt,"create table empr_caddie ");
		$rqt = "CREATE TABLE empr_caddie_content (empr_caddie_id int(8) unsigned NOT NULL default '0', object_id int(10) unsigned NOT NULL default '0', flag varchar(10) default NULL, KEY (empr_caddie_id,object_id)) " ;
		echo traite_rqt($rqt,"create table empr_caddie_content ");
		$rqt = "CREATE TABLE empr_caddie_procs ( idproc smallint(5) unsigned NOT NULL auto_increment, type varchar(20) NOT NULL default 'SELECT', name varchar(255) NOT NULL default '', requete blob NOT NULL, comment tinytext NOT NULL, autorisations mediumtext, parameters TEXT, PRIMARY KEY  (idproc)) ";
		echo traite_rqt($rqt,"create table empr_caddie_procs ");
		
		$rqt = "ALTER TABLE abts_modeles ADD format_periode VARCHAR( 255 ) NOT NULL default '' ";
		echo traite_rqt($rqt,"ALTER TABLE abts_modeles ADD format_periode ");

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'empr' and sstype_param='pics_url' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'empr', 'pics_url', '', 'URL des photos des emprunteurs, dans le chemin fourni, !!num_carte!! sera remplacé par le numéro de carte du lecteur. \n exemple : http://www.monsite/photos/lecteurs/!!num_carte!!.jpg')";
			echo traite_rqt($rqt,"insert empr_pics_url='' into parametres");
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'empr' and sstype_param='pics_max_size' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'empr', 'pics_max_size', '100', 'Taille maximale des photos des emprunteurs, en largeur ou en hauteur')";
			echo traite_rqt($rqt,"insert empr_pics_max_size='100' into parametres");
			}

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.41");
		break;

	case "v4.41": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		//Acceleration recherche par terme
		$rqt = "ALTER TABLE categories ADD num_thesaurus INT(3) UNSIGNED NOT NULL DEFAULT '1' FIRST ";
		echo traite_rqt($rqt,"ALTER TABLE categories ADD num_thesaurus ");
		$rqt = "UPDATE noeuds, categories SET categories.num_thesaurus = noeuds.num_thesaurus WHERE num_noeud = id_noeud ";
		echo traite_rqt($rqt,"UPDATE TABLE categories SET num_thesaurus ");

		$rqt = "update parametres set section_param='thesaurus' where type_param='thesaurus' and (section_param='' or section_param is null)";
		echo traite_rqt($rqt,"update parametres for thesaurus section param ");

		$rqt = "update parametres set section_param='categories' where type_param='categories' and (section_param='' or section_param is null)";
		echo traite_rqt($rqt,"update parametres for categories section param ");
		$rqt = "update parametres set type_param='thesaurus', sstype_param=concat('categories_',sstype_param) where type_param='categories' and section_param='categories'";
		echo traite_rqt($rqt,"update parametres for categories type_param ");

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'thesaurus' and sstype_param='classement_mode_pmb' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param,comment_param, section_param, gestion) VALUES ('thesaurus', 'classement_mode_pmb', '0', 'Niveau d\'utilisation des plans de classement des indexations. \n 0 : Un seul plan de classement. \n 1 : Choix du plan de classement possible.', 'classement', '0')";
			echo traite_rqt($rqt,"insert thesaurus_classement_mode_pmb='0' into parametres");
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'thesaurus' and sstype_param='classement_defaut' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param,comment_param, section_param, gestion) VALUES ('thesaurus', 'classement_defaut', '1', 'Identifiant du plan de classement par défaut.', 'classement', '0')";
			echo traite_rqt($rqt,"insert thesaurus_classement_defaut='1' into parametres");
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'empr' and sstype_param='electronic_loan_ticket' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param,comment_param, section_param, gestion) VALUES ('empr', 'electronic_loan_ticket', '0', 'Envoyer un ticket de prêt électronique ? \n 0: Non, \n 1: Oui', '', '0')";
			echo traite_rqt($rqt,"insert empr_electronic_loan_ticket='0' into parametres");
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'empr' and sstype_param='electronic_loan_ticket_obj' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param,comment_param, section_param, gestion) VALUES ('empr', 'electronic_loan_ticket_obj', '!!biblio_name!! : emprunt(s) du !!date!!', 'Objet du mail de ticket électronique de prêt', '', '0')";
			echo traite_rqt($rqt,"insert empr_electronic_loan_ticket_obj into parametres");
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'empr' and sstype_param='electronic_loan_ticket_msg' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param,comment_param, section_param, gestion) VALUES ('empr', 'electronic_loan_ticket_msg', 'Bonjour, <br />Voici la liste de vos emprunts et/ou réservations en date du !!date!! :<br /><br />!!all_loans!! !!all_reservations!!<br />Retrouvez toutes ces informations sur votre compte à l\'adresse <a href=!!biblio_website!!>!!biblio_website!!</a>.', 'Corps du mail de ticket électronique de prêt', '', '0')";
			echo traite_rqt($rqt,"insert empr_electronic_loan_ticket_msg into parametres");
			}

		$rqt = "CREATE TABLE pclassement (id_pclass INT UNSIGNED NOT NULL AUTO_INCREMENT, name_pclass VARCHAR( 255 ) NOT NULL, typedoc VARCHAR( 255 ) NOT NULL, PRIMARY KEY ( id_pclass ) )";
		echo traite_rqt($rqt,"CREATE TABLE pclassement  ");
		
		$rqt = "INSERT INTO pclassement (id_pclass,name_pclass,typedoc) VALUES ('1','Plan de classement N°1','abcdefgijklmr')";
		echo traite_rqt($rqt,"INSERT first class arrangement INTO pclassement ");

		$rqt = "ALTER TABLE indexint ADD num_pclass INT NOT NULL DEFAULT '1'";
		echo traite_rqt($rqt,"ALTER TABLE indexint ADD num_pclass ");
		$rqt = "ALTER TABLE indexint DROP INDEX indexint_name , ADD UNIQUE indexint_name ( indexint_name , num_pclass )";
		echo traite_rqt($rqt,"ALTER TABLE indexint index ( indexint_name , num_pclass ) ");

		$rqt = "ALTER TABLE ouvertures DROP PRIMARY KEY ";
		echo traite_rqt($rqt,"ALTER TABLE ouvertures DROP PRIMARY KEY");
		$rqt = "ALTER TABLE ouvertures ADD PRIMARY KEY ( date_ouverture, num_location ) ";
		echo traite_rqt($rqt,"ALTER TABLE ouvertures ADD PRIMARY KEY ( date_ouverture, num_location ) ");

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.42");
		break;

	case "v4.42": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		// visibilité des exemplaires
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='droits_explr_localises' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param,comment_param, section_param, gestion) VALUES ('pmb', 'droits_explr_localises', '0', 'Les droits de gestion des exemplaires sont-ils localisés ? \n 0: Non \n 1: Oui', '', '0')";
			echo traite_rqt($rqt,"insert pmb_droits_explr_localises into parametres");
			}
		
		$rqt = "ALTER TABLE users ADD explr_invisible varchar( 255 ) default '0' ";
		echo traite_rqt($rqt,"ALTER TABLE users ADD explr_invisible ");
		
		$rqt = "ALTER TABLE users ADD explr_visible_mod varchar( 255 ) default '0'";
		echo traite_rqt($rqt,"ALTER TABLE users ADD explr_visible_mod ");
		
		$rqt = "ALTER TABLE users ADD explr_visible_unmod varchar( 255 ) default '0'";
		echo traite_rqt($rqt,"ALTER TABLE users ADD explr_visible_unmod ");
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'empr' and sstype_param='fiche_depliee' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('empr','fiche_depliee', '1', 'La fiche emprunteur sera automatiquement : \n 0 : pliée \n 1 : dépliée', '', '0')";
			echo traite_rqt($rqt,"insert empr_fiche_depliee into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'empr' and sstype_param='statut_adhes_depassee' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('empr','statut_adhes_depassee', '2', 'id du statut pour lequel les emprunteurs dont la date d\'adhesion est dépassée n\'apparaissent pas en zone d\'alerte', '', '0')";
			echo traite_rqt($rqt,"insert empr_statut_adhes_depassee into parametres");
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='authorized_styles' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('opac','authorized_styles', '$opac_default_style', 'Styles de l\'OPAC autorisés, séparés par une virgule', 'a_general', '0')";
			echo traite_rqt($rqt,"insert opac_authorized_styles into parametres");
			}

		$rqt = "ALTER TABLE actes DROP INDEX index_acte ";
		echo traite_rqt($rqt,"ALTER TABLE drop full text indexes ");
		
		$rqt = "ALTER TABLE bulletins DROP INDEX i_index_titre";
		echo traite_rqt($rqt,"ALTER TABLE drop full text indexes ");
		
		$rqt = "ALTER TABLE categories DROP INDEX index_categorie";
		echo traite_rqt($rqt,"ALTER TABLE drop full text indexes ");
		
		$rqt = "ALTER TABLE categories DROP INDEX num_noeud";
		echo traite_rqt($rqt,"ALTER TABLE drop full text indexes ");
		
		$rqt = "ALTER TABLE entites DROP INDEX index_entite";
		echo traite_rqt($rqt,"ALTER TABLE drop full text indexes ");
		
		$rqt = "ALTER TABLE notices DROP INDEX index_matieres";
		echo traite_rqt($rqt,"ALTER TABLE drop full text indexes ");
		
		$rqt = "ALTER TABLE notices DROP INDEX i_contenu_resume";
		echo traite_rqt($rqt,"ALTER TABLE drop full text indexes ");
		
		$rqt = "ALTER TABLE notices DROP INDEX i_n_gen";
		echo traite_rqt($rqt,"ALTER TABLE drop full text indexes ");
		
		$rqt = "ALTER TABLE series DROP INDEX serie_index";
		echo traite_rqt($rqt,"ALTER TABLE drop full text indexes ");
		
		$rqt = "ALTER TABLE suggestions DROP INDEX index_suggestion";
		echo traite_rqt($rqt,"ALTER TABLE drop full text indexes ");

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.43");
		break;

	case "v4.43": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+

		// re-correction des typedoc de dépouillements
		$sql_corr = "select s.typdoc as typdoc_s, a.notice_id as notice_id_dep from notices as s, analysis, bulletins, notices as a where a.niveau_biblio='a' and s.niveau_biblio='s' and s.notice_id=bulletin_notice and analysis_bulletin=bulletin_id and analysis_notice=a.notice_id " ;
		$res_corr = mysql_query($sql_corr,$dbh);
		while ($obj_corr=mysql_fetch_object($res_corr)) {
			@mysql_query("update notices set typdoc='".$obj_corr->typdoc_s."' where notice_id='".$obj_corr->notice_id_dep."'") ;
			}
		echo traite_rqt("select 1 from users","update analysis notices doctype with serial doctype ") ;

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'empr' and sstype_param='relance_adhesion' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param,comment_param, section_param, gestion) VALUES ('empr','relance_adhesion', '0', 'Les relances d\'adhésion sont envoyées : \n 0 : exclusivement par lettre \n 1 : mail, à défaut par lettre', '', '0')";
			echo traite_rqt($rqt,"insert empr_relance_adhesion=0 into parametres");
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'empr' and sstype_param='show_rows' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param,comment_param, section_param, gestion) VALUES ('empr','show_rows', 'b,n,a,v,y,s,1', 'Colonnes affichées en liste de lecteurs, saisir les colonnes séparées par des virgules. Les colonnes disponibles pour l\'affichage de la liste des emprunteurs sont : \n n: nom+prénom \n a: adresse \n b: code-barre \n c: catégories \n g: groupes \n l: localisation \n s: statut \n cp: code postal \n v: ville \n y: année de naissance \n #n : id des champs personnalisés \n 1: icône panier', '','0')";
			echo traite_rqt($rqt,"insert empr_show_rows=b,n,a,v,y,s,1 into parametres");
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'empr' and sstype_param='sort_rows' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param,comment_param, section_param, gestion) VALUES ('empr','sort_rows', 'n,c,l,s', 'Colonnes qui seront disponibles pour le tri des emprunteurs. Les colonnes possibles sont : \n n: nom+prénom \n c: catégories \n g: groupes \n l: localisation \n s: statut \n cp: code postal \n v: ville \n y: année de naissance \n #n : id des champs personnalisés','', '0')";
			echo traite_rqt($rqt,"insert empr_sort_rows=n,c,l,s into parametres");
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'empr' and sstype_param='filter_rows' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param,comment_param, section_param, gestion) VALUES ('empr','filter_rows', 'cp,v,y,s', 'Colonnes qui seront disponibles en filtres de la liste des emprunteurs. Les colonnes possibles sont : \n cp: code postal \n c: catégories \n g: groupes \n l: localisation \n s: statut \n v: ville \n y: année de naissance \n #n : id des champs personnalisés', '', '0')";
			echo traite_rqt($rqt,"insert empr_filter_rows=cp,v,y,s into parametres");
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'empr' and sstype_param='header_format' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('empr','header_format', '', 'Champs personnalisés qui seront affichés dans l\'entête de la fiche emprunteur. Saisir les ids séparés par des virgules', '', '0')";
			echo traite_rqt($rqt,"insert empr_header_format= into parametres");
			}

		$rqt = "ALTER TABLE pret_archive ADD arc_empr_statut INT( 10 ) UNSIGNED DEFAULT '1' NOT NULL AFTER arc_empr_sexe";
		echo traite_rqt($rqt,"alter table pret_archive add empr_statut");

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'empr' and sstype_param='archivage_prets' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('empr','archivage_prets', '0', 'Archiver les prêts des emprunteurs ? \n 0: Non \n 1: Oui\nATTENTION pour la France: nous attirons votre attention sur l\'obligation de déclarer votre traitement à la CNIL (www.cnil.fr) si vous activez cette fonctionnalité.', '', '0')";
			echo traite_rqt($rqt,"insert empr_archivage_prets='0' into parametres");
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'empr' and sstype_param='archivage_prets_purge' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('empr','archivage_prets_purge', '0', 'Nombre de jours maximum pendant lesquels doivent être conservées les archives nominatives de prêts : \n0: illimité \nN: N jours', '', '0')";
			echo traite_rqt($rqt,"insert empr_archivage_prets_purge='0' into parametres");
			}

		$rqt = "ALTER TABLE pret_archive ADD arc_id_empr INT( 10 ) UNSIGNED DEFAULT 0 NOT NULL AFTER arc_fin";
		echo traite_rqt($rqt,"alter table pret_archive add arc_id_empr");

		$rqt = "ALTER TABLE pret_archive ADD arc_id_empr INT( 10 ) UNSIGNED DEFAULT 0 NOT NULL AFTER arc_fin";
		echo traite_rqt($rqt,"alter table pret_archive add arc_id_empr");

		$rqt = "ALTER TABLE empr_statut ADD allow_loan_hist TINYINT(4) UNSIGNED DEFAULT 0 NOT NULL AFTER allow_loan";
		echo traite_rqt($rqt,"alter table empr_statut add allow_loan_hist=0 ");
		
		$rqt = "ALTER TABLE empr_statut ADD allow_avis TINYINT(4) UNSIGNED DEFAULT 1 NOT NULL ";
		echo traite_rqt($rqt,"alter table empr_statut add allow_avis=1 ");
		
		$rqt = "ALTER TABLE empr_statut ADD allow_tag TINYINT(4) UNSIGNED DEFAULT 1 NOT NULL ";
		echo traite_rqt($rqt,"alter table empr_statut add allow_tag=1 ");
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='autres_lectures' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('opac','autres_lectures', '0', 'Afficher les emprunts des autres lecteurs du document courant ? \n 0: Non \n 1: Oui', 'f_modules', '0')";
			echo traite_rqt($rqt,"insert opac_autres_lectures='0' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='autres_lectures_tri' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('opac','autres_lectures_tri', 'rand()', 'Tri des autres lectures proposées : \n rand(): aléatoire \n tit: par Titre', 'f_modules', '0')";
			echo traite_rqt($rqt,"insert opac_autres_lectures_tri='rand()' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='autres_lectures_nb_mini_emprunts' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('opac','autres_lectures_nb_mini_emprunts', '100', 'Nombre minimum d\'emprunts pour être comptabilisés \n 1: un seul emprunt suffit pour proposer la notice comme lecture associée \n N: N emprunts minimum nécessaires ', 'f_modules', '0')";
			echo traite_rqt($rqt,"insert opac_autres_lectures_nb_mini_emprunts='100' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='autres_lectures_nb_maxi' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('opac','autres_lectures_nb_maxi', '0', 'Nombre maximum de lectures associées proposées', 'f_modules', '0')";
			echo traite_rqt($rqt,"insert opac_autres_lectures_nb_maxi='0' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='autres_lectures_nb_jours_maxi' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('opac','autres_lectures_nb_jours_maxi', '1', 'Délai en jours au delà duquel les emprunts ne sont pas comptabilisés, 0 pour illimité', 'f_modules', '0')";
			echo traite_rqt($rqt,"insert opac_autres_lectures_nb_jours_maxi='1' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='empr_hist_nb_max' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('opac','empr_hist_nb_max', '0', 'Nombre maximum de prêts précédents à afficher, 0 pour illimité', 'a_general', '0')";
			echo traite_rqt($rqt,"insert opac_empr_hist_nb_max='0' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='empr_hist_nb_jour_max' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('opac','empr_hist_nb_jour_max', '1', 'Délai en jours au delà duquel les prêts précédents ne sont pas affichés, 0 pour illimité', 'a_general', '0')";
			echo traite_rqt($rqt,"insert opac_empr_hist_nb_jour_max='1' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='allow_tags_search_min_occ' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('opac','allow_tags_search_min_occ', '1', 'Nombre mini d\'occurrences d\'un tag pour être affiché, 1 pour tous', 'c_recherche', '0')";
			echo traite_rqt($rqt,"insert opac_allow_tags_search_min_occ='1' into parametres");
			}
		
		$rqt = "CREATE TABLE collections_state (id_serial mediumint(8) unsigned NOT NULL default 0, location_id smallint(5) unsigned NOT NULL default 0, state_collections text not null default '', PRIMARY KEY  (id_serial,location_id))" ;
		echo traite_rqt($rqt,"create table collections_state");
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='etat_collections_localise' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('pmb','etat_collections_localise', '0', 'L\'état des collections est-il localisé ? \n 0 : non \n 1 : oui', '', '0')" ;
			echo traite_rqt($rqt,"insert pmb_etat_collections_localise='0' into parametres");
			}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='clean_nb_elements' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('pmb','clean_nb_elements', '100', 'Nombre d\'éléments traités par passe en nettoyage de base', '', '0')" ;
			echo traite_rqt($rqt,"insert pmb_clean_nb_elements='100' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='rfid_activate' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('pmb','rfid_activate', '0', 'Enregistrements des prêts par platine RFID ? \n 0: Non \n 1: Oui', '', '0')" ;
			echo traite_rqt($rqt,"insert pmb_rfid_activate='0' into parametres");
			}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='bull_results_per_page' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('opac','bull_results_per_page', '12', 'Nombre de bulletins affichés par page dans l\'affichage d\'un périodique','e_aff_notice','0')" ;
			echo traite_rqt($rqt,"insert opac_bull_results_per_page='12' into parametres");
			}
		
		$rqt = "drop TABLE if exists pointage_pref ";
		echo traite_rqt($rqt, "drop TABLE pointage_pref ");

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.44");
		break;

	case "v4.44": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		
		$rqt = "CREATE TABLE connectors (
				connector_id varchar(20) NOT NULL default '',
				parameters text NOT NULL,
				repository int(11) NOT NULL default '0',
				timeout int(11) NOT NULL default '5',
				retry int(11) NOT NULL default '3',
				ttl int(11) NOT NULL default '1440',
				PRIMARY KEY  (connector_id)
				)";
		echo traite_rqt($rqt, "CREATE TABLE connectors ");

		$rqt = "CREATE TABLE connectors_sources (
				source_id int(10) unsigned NOT NULL auto_increment,
				id_connector varchar(20) NOT NULL default '',
				parameters text NOT NULL,
				comment varchar(255) NOT NULL default '',
				name varchar(255) NOT NULL default '',
				repository int(11) NOT NULL default '0',
				timeout int(11) NOT NULL default '5',
				retry int(11) NOT NULL default '3',
				ttl int(11) NOT NULL default '1440',
				PRIMARY KEY  (source_id)
				)";
		echo traite_rqt($rqt, "CREATE TABLE connectors_sources ");

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='rfid_serveur_url' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('pmb','rfid_serveur_url', '', 'URL du serveur de webservices RFID', '', '0')" ;
			echo traite_rqt($rqt,"insert pmb_rfid_serveur_url='' into parametres");
			}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='authorized_information_pages' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'opac', 'authorized_information_pages', '1', 'Pages \"includable\" dans la page de l\'opac ./index.php?lvl=information&askedpage= : \n Mettre les noms des fichiers séparés par une virgule', 'a_general', 0)";
			echo traite_rqt($rqt,"insert opac_authorized_information_pages='' into parametres");
			}

		$rqt = "ALTER TABLE entites CHANGE siret siret VARCHAR(255) NOT NULL ";
		echo traite_rqt($rqt, "ALTER TABLE entites CHANGE siret VARCHAR(255) ");

		$rqt = "ALTER TABLE entites CHANGE naf naf VARCHAR(255) NOT NULL ";
		echo traite_rqt($rqt, "ALTER TABLE entites CHANGE naf VARCHAR(255) ");

		$rqt = "ALTER TABLE entites CHANGE rcs rcs VARCHAR(255) NOT NULL ";
		echo traite_rqt($rqt, "ALTER TABLE entites CHANGE rcs VARCHAR(255) ");

		$rqt = "ALTER TABLE entites CHANGE tva tva VARCHAR(255) NOT NULL ";
		echo traite_rqt($rqt, "ALTER TABLE entites CHANGE tva VARCHAR(255) ");

		$rqt = "ALTER TABLE entites CHANGE site_web site_web VARCHAR(255) NOT NULL ";
		echo traite_rqt($rqt, "ALTER TABLE entites CHANGE site_web VARCHAR(255) ");

		$rqt = "ALTER TABLE entites CHANGE num_cp_client num_cp_client VARCHAR(255) NOT NULL ";
		echo traite_rqt($rqt, "ALTER TABLE entites CHANGE num_cp_client VARCHAR(255) ");

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='notice_controle_doublons' "))==0){
			$rqt = "INSERT INTO parametres (type_param ,sstype_param ,valeur_param,comment_param ,section_param ,gestion) VALUES ('pmb','notice_controle_doublons', '0', 'Contrôle sur les doublons en saisie de la notice \n 0: Pas de contrôle sur les doublons, \n 1,tit1,tit2, ... : Recherche par méthode _exacte_ de doublons sur des champs, défini dans le fichier notice.xml  \n 2,tit1,tit2, ... : Recherche par _similitude_ ', '', '0')";
			echo traite_rqt($rqt,"insert pmb_notice_controle_doublons=0 into parametres");
			}

		$rqt = "ALTER TABLE notices ADD signature VARCHAR( 255 ) NOT NULL default '' ";
		echo traite_rqt($rqt, "ALTER TABLE notices ADD signature  ");

		$rqt = "ALTER TABLE notices drop INDEX sig_index ";
		echo traite_rqt($rqt, "ALTER TABLE notices drop index(signature)");

		$rqt = "ALTER TABLE notices ADD INDEX sig_index (signature) ";
		echo traite_rqt($rqt, "ALTER TABLE notices ADD index(signature)");

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='title_ponderation' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'opac', 'title_ponderation', '0.5', 'Majoration de la pondération des mots du titre \n   mettre 0 (zero) pour interdire la majoration \n ATTENTION utiliser le point décimal ', 'c_recherche', 0) ";
			echo traite_rqt($rqt, "insert opac_title_ponderation=0.5 into parameters"); 
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='title_ponderation' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'pmb', 'title_ponderation', '0.5', 'Majoration de la pondération des mots du titre \n   mettre 0 (zero) pour interdire la majoration \n ATTENTION utiliser le point décimal ', '', 0) ";
			echo traite_rqt($rqt, "insert pmb_title_ponderation=0.5 into parameters"); 
			}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='param_etiq_codes_barres' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'pmb', 'param_etiq_codes_barres', '', 'Paramètres de sauvegarde des paramètres d\'édition d\'étiquettes codes-barres', '', 1) ";
			echo traite_rqt($rqt, "insert pmb_param_etiq_codes_barres='' into parameters"); 
			}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='javascript_office_editor' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'pmb', 'javascript_office_editor', '', 'Code HTML à insérer pour remplacer les textarea par un éditeur Office javascript', '', 0)";
			echo traite_rqt($rqt, "insert pmb_javascript_office_editor='' into parameters"); 
			}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='biblio_post_adress' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'opac', 'biblio_post_adress', '', 'Bloc d\'information après le bloc adresse, dans la feuille de style, voir id post_adress', 'b_aff_general', 0)";
			echo traite_rqt($rqt, "insert opac_biblio_post_adress='' into parameters"); 
			}
		
		$rqt = "update parametres set gestion=1 where (type_param='empr' and sstype_param='corresp_import')";
		echo traite_rqt($rqt, "Hide parameter empr_corresp_import");

		$rqt = "CREATE TABLE entrepots (
				connector_id varchar(20) NOT NULL default '',
				source_id int(11) unsigned NOT NULL default '0',
				ref varchar(220) NOT NULL default '',
				date_import datetime NOT NULL default '0000-00-00 00:00:00',
				ufield char(3) NOT NULL default '',
				usubfield char(1) NOT NULL default '',
				field_order int(10) unsigned NOT NULL default '0',
				subfield_order int(10) unsigned NOT NULL default '0',
				value text NOT NULL,
				i_value text NOT NULL,
				recid varchar(255) NOT NULL default '',
				search_id varchar(32) NOT NULL default '',
				PRIMARY KEY  (connector_id,source_id,ref,ufield,usubfield,field_order,subfield_order,search_id),
				KEY usubfield (usubfield),
				KEY ufield_2 (ufield,usubfield),
				KEY recid_2 (recid,ufield,usubfield),
				KEY source_id (source_id)
				)";
		echo traite_rqt($rqt, "CREATE TABLE entrepots ");
		
		$rqt = "ALTER TABLE empr_statut ADD allow_pwd TINYINT(4) UNSIGNED DEFAULT 1 NOT NULL ";
		echo traite_rqt($rqt,"alter table empr_statut add allow_pwd=1 ");
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='allow_external_search' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'opac', 'allow_external_search', '0', 'Autorisation ou non de la recherche par connecteurs externes dans l\'OPAC \n 0 : Non \n 1 : Oui','c_recherche',0)";
			echo traite_rqt($rqt,"insert opac_external_term_search='0' into parametres");
			}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.45");
		break;

	case "v4.45": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE groupe ADD lettre_rappel INT( 1 ) UNSIGNED DEFAULT 0 NOT NULL ";
		echo traite_rqt($rqt,"ALTER TABLE groupe ADD lettre_rappel ");
		
		$rqt = "create table empty_words_calculs (id_calcul int(9) unsigned not null auto_increment PRIMARY KEY,date_calcul DATE not null DEFAULT '0000-00-00',php_empty_words TEXT not null DEFAULT '',nb_notices_calcul mediumint(8) unsigned not null default 0,archive_calcul tinyint(1) not null default 0)";
		echo traite_rqt($rqt,"create table empty_words_calculs ");
		
		$rqt = "create table mots(id_mot mediumint(8) unsigned primary key auto_increment, mot varchar(100) not null default '', unique index(mot))";
		echo traite_rqt($rqt,"create table mots ");
		
		$rqt = "create table linked_mots (num_mot mediumint(8) unsigned not null default 0,num_linked_mot mediumint(8) unsigned not null default 0,type_lien tinyint(1) not null default 1,ponderation float(2) not null default 1,primary key (num_mot, num_linked_mot, type_lien))";
		echo traite_rqt($rqt,"create table linked_mots ");
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='nb_noti_calc_empty_words' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param,comment_param, section_param, gestion) VALUES ('pmb','nb_noti_calc_empty_words', '50', 'Un mot sera considéré comme vide s\'il apparaît dans un nombre de notices minimum. Saisir le pourcentage par rapport au nombre de notices total.', '', '1')";
			echo traite_rqt($rqt,"insert pmb_nb_noti_calc_empty_words='0' into parametres");
			}

		$rqt = "CREATE TABLE source_sync (source_id int(10) unsigned NOT NULL default 0, nrecu varchar(255) not null default '', ntotal varchar(255) not null default '', message varchar(255) not null default '', date_sync datetime not null default '0000-00-00 00:00:00', percent int(10) unsigned NOT NULL default 0, env text not null default '', cancel int(10) unsigned NOT NULL default 0, PRIMARY KEY (source_id))";
		echo traite_rqt($rqt,"CREATE TABLE source_sync ");
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='fonction_affichage_liste_bull' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'opac','fonction_affichage_liste_bull', '','Fonction d\'affichage des listes de bulletins','e_aff_notice',0)" ;
			echo traite_rqt($rqt,"insert opac_fonction_affichage_liste_bull into parametres") ;
			}

		$rqt = "alter table entrepots add search_id varchar(255) not null default 'rep'";
		echo traite_rqt($rqt,"alter table entrepots add search_id ");
		
		$rqt = "alter table entrepots drop primary key";
		echo traite_rqt($rqt,"alter table entrepots drop primary key");
		
		$rqt = "alter table entrepots add primary key (connector_id,source_id,ref,ufield,usubfield,field_order,subfield_order,search_id)";
		echo traite_rqt($rqt,"alter table entrepots add primary key (...)");
		
		$rqt = "ALTER TABLE sauv_lieux CHANGE sauv_lieu_login sauv_lieu_login VARCHAR( 255 ) DEFAULT NULL ";
		echo traite_rqt($rqt,"ALTER TABLE sauv_lieux sauv_lieu_login VARCHAR( 255 )");
		
		$rqt = "ALTER TABLE sauv_lieux CHANGE sauv_lieu_password sauv_lieu_password VARCHAR( 255 ) DEFAULT NULL ";
		echo traite_rqt($rqt,"ALTER TABLE sauv_lieux sauv_lieu_password VARCHAR( 255 )");
		
		$rqt = "ALTER TABLE pret_archive CHANGE arc_empr_cp arc_empr_cp VARCHAR( 10 ) not null default ''";
		echo traite_rqt($rqt,"ALTER TABLE pret_archive arc_empr_cp VARCHAR( 10 )");
		
		$rqt = "ALTER TABLE pret_archive CHANGE arc_empr_ville arc_empr_ville VARCHAR( 255 ) not null default ''";
		echo traite_rqt($rqt,"ALTER TABLE pret_archive arc_empr_ville VARCHAR( 255 )");
		
		$rqt = "ALTER TABLE pret_archive CHANGE arc_empr_prof arc_empr_prof VARCHAR( 255 ) not null default ''";
		echo traite_rqt($rqt,"ALTER TABLE pret_archive arc_empr_prof VARCHAR( 255 )");
		
		$rqt = "ALTER TABLE pret_archive CHANGE arc_expl_cote arc_expl_cote VARCHAR( 255 ) not null default ''";
		echo traite_rqt($rqt,"ALTER TABLE pret_archive arc_expl_cote VARCHAR( 255 )");
		
		$rqt = "delete FROM notices_langues WHERE num_notice not in (select notice_id from notices)";
		echo traite_rqt($rqt,"clean notices_langues 1");
		
		$rqt = "delete FROM notices_langues WHERE code_langue = ''";
		echo traite_rqt($rqt,"clean notices_langues 2");

		$rqt = "ALTER TABLE entrepots drop INDEX recid ";
		echo traite_rqt($rqt, "ALTER TABLE entrepots drop INDEX recid");

		$rqt = "ALTER TABLE entrepots drop INDEX ufield ";
		echo traite_rqt($rqt, "ALTER TABLE entrepots drop INDEX ufield");

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.46");
		break;

	case "v4.46": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE entrepots DROP INDEX source_id";
		echo traite_rqt($rqt,"ALTER TABLE entrepots DROP INDEX source_id");

		$rqt = "ALTER TABLE entrepots ADD INDEX source_id (source_id) ";
		echo traite_rqt($rqt,"ALTER TABLE entrepots ADD INDEX source_id");
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.47");
		break;

	case "v4.47": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		@set_time_limit(0);
		$rqt = "alter TABLE connectors change connector_id connector_id varchar(20) NOT NULL default ''";
		echo traite_rqt($rqt,"ALTER TABLE connectors connector_id varchar(20)");

		$rqt = "alter TABLE connectors_sources change id_connector id_connector varchar(20) NOT NULL default ''";
		echo traite_rqt($rqt,"ALTER TABLE connectors_sources id_connector varchar(20)");

		$rqt = "alter TABLE entrepots change connector_id connector_id varchar(20) NOT NULL default ''";
		echo traite_rqt($rqt,"ALTER TABLE entrepots connector_id varchar(20)");

		$rqt = "alter TABLE entrepots change ref ref varchar(220) NOT NULL default ''";
		echo traite_rqt($rqt,"ALTER TABLE entrepots ref varchar(220)");

		$rqt = "alter TABLE entrepots change search_id search_id varchar(32) NOT NULL default ''";
		echo traite_rqt($rqt,"ALTER TABLE entrepots search_id varchar(32)");

		$rqt = "ALTER TABLE entrepots DROP INDEX recid";
		echo traite_rqt($rqt,"ALTER TABLE entrepots DROP INDEX recid");

		$rqt = "ALTER TABLE entrepots DROP INDEX ufield";
		echo traite_rqt($rqt,"ALTER TABLE entrepots DROP INDEX ufield");

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='allow_external_search' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'pmb', 'allow_external_search', '0', 'Autorisation ou non de la recherche par connecteurs externes (masque également le menu Administration-Connecteurs) \n 0 : Non \n 1 : Oui','',0)";
			echo traite_rqt($rqt,"insert pmb_external_term_search='0' into parametres");
			}

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.48");
		break;

	case "v4.48": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+

		$rqt = "CREATE TABLE transferts_demandes (
				id_transfert int(10) unsigned NOT NULL auto_increment,
				num_location_source int(10) unsigned NOT NULL DEFAULT 0,
				num_location_dest int(10) unsigned NOT NULL DEFAULT 0,
				num_expl int(10) unsigned NOT NULL DEFAULT 0,
				date_creation datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				etat_transfert smallint(1) unsigned NOT NULL DEFAULT 0,
				date_confirmation datetime NOT NULL default '0000-00-00 00:00:00',
				num_precedent int(10) unsigned NOT NULL DEFAULT 0,
				motif longtext NOT NULL,
				motif_refus longtext NOT NULL,
				accuse_reception tinyint(1) unsigned NOT NULL DEFAULT 0,
				origine varchar(50) NOT NULL DEFAULT '',
				type_objet varchar(15) NOT NULL DEFAULT '',
				expl_ancien_statut smallint(5) unsigned NOT NULL DEFAULT 0,
				PRIMARY KEY (id_transfert),
				UNIQUE (num_location_source, num_location_dest, num_expl, date_creation)
				)";
		echo traite_rqt($rqt,"CREATE TABLE transferts_demandes ");

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'transferts' and sstype_param='gestion_transferts' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param, section_param) VALUES ('transferts', 'gestion_transferts', '0', 'Activation de la gestion des transferts\n 0: Non \n 1: Oui', 'transferts')";
			echo traite_rqt($rqt,"insert transferts_gestion_transferts='0' into parametres");
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'transferts' and sstype_param='transfert_statut' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param, section_param) VALUES ('transferts', 'transfert_statut', '0', 'Id du statut dans lequel sont placés les documents en cours de transfert', 'transferts')";
			echo traite_rqt($rqt,"insert transferts_transfert_statut='0' into parametres");
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'dsi' and sstype_param='bannette_notices_order' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param) VALUES ('dsi', 'bannette_notices_order', 'index_serie, tnvol, index_sew', 'Ordre des notices au sein de la bannette: \n index_serie, tnvol, index_sew : par titre \n create_date desc : par date de saisie décroissante \n rand() : aléatoire')";
			echo traite_rqt($rqt,"insert dsi_bannette_notices_order='index_serie, tnvol, index_sew' into parametres");
		}

		$rqt="alter table rss_flux add rss_flux_content longblob NOT NULL default '', add rss_flux_last timestamp NOT NULL default '0000-00-00 00:00:00'";
		echo traite_rqt($rqt,"alter table rss_flux add rss_flux_content, add rss_flux_last ");
  
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='websubscribe_show' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('opac','websubscribe_show', '0', 'Afficher la possibilité de s\'inscrire en ligne ? \n 0: Non \n 1: Oui', 'f_modules', '0')";
			echo traite_rqt($rqt,"insert opac_websubscribe_show='0' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='websubscribe_empr_status' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('opac','websubscribe_empr_status', '2,1', 'Id des statuts des inscrits séparés par une virgule: en attente de validation, validés', 'f_modules', '0')";
			echo traite_rqt($rqt,"insert opac_websubscribe_empr_status='2,1' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='websubscribe_empr_categ' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('opac','websubscribe_empr_categ', '0', 'Id de la catégorie des inscrits par le web non adhérents complets', 'f_modules', '0')";
			echo traite_rqt($rqt,"insert opac_websubscribe_empr_categ='0' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='websubscribe_empr_stat' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('opac','websubscribe_empr_stat', '0', 'Id du code statistique des inscrits par le web non adhérents complets', 'f_modules', '0')";
			echo traite_rqt($rqt,"insert opac_websubscribe_empr_stat='0' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='websubscribe_valid_limit' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('opac','websubscribe_valid_limit', '24', 'Durée maximum des inscriptions en attente de validation', 'f_modules', '0')";
			echo traite_rqt($rqt,"insert opac_websubscribe_valid_limit='24' into parametres");
			}
		$rqt = "update parametres set comment_param=concat(comment_param,' \n 3 : consultation et ajout anonymes possibles') where type_param='opac' and sstype_param='avis_allow'";
		echo traite_rqt($rqt,"update comment_param on opac_avis_allow");

		$rqt = "ALTER TABLE bulletins drop index i_num_notice " ;
		echo traite_rqt($rqt,"drop index i_num_notice");
		$rqt = "ALTER TABLE bulletins ADD INDEX i_num_notice (num_notice) ";
		echo traite_rqt($rqt,"ADD INDEX bulltins(num_notice) ") ;
		
		$rqt = "DELETE FROM explnum WHERE explnum_bulletin ='0' AND explnum_notice NOT IN (SELECT notice_id FROM notices) ";
		echo traite_rqt($rqt,"drop explnum without notice");
		$rqt = "DELETE FROM explnum WHERE explnum_notice ='0' AND explnum_bulletin NOT IN (SELECT bulletin_id FROM bulletins) ";
		echo traite_rqt($rqt,"drop explnum without bulletin");

		$rqt = "ALTER TABLE caddie_content CHANGE blob_type blob_type VARCHAR(100) default ''";
		echo traite_rqt($rqt,"ALTER TABLE caddie_content CHANGE blob_type VARCHAR(100)");
		
		$rqt = "ALTER TABLE caddie_content CHANGE content content VARCHAR( 100 ) NOT NULL default ''";
		echo traite_rqt($rqt,"ALTER TABLE caddie_content CHANGE content VARCHAR( 100 )");
		
		$rqt = "ALTER TABLE caddie_content DROP PRIMARY KEY ";
		echo traite_rqt($rqt,"ALTER TABLE caddie_content DROP PRIMARY KEY ");
		$rqt = "ALTER TABLE caddie_content ADD PRIMARY KEY pk_caddie_content ( caddie_id , object_id , content )";
		echo traite_rqt($rqt,"ALTER TABLE caddie_content ADD PRIMARY KEY ( caddie_id , object_id , content ) : <font color=red>ATTENTION, Cette requête DOIT fonctionner, en cas d'échec, vider vos paniers et recommencer ! <br />This query MUST work, if it doesn't, empty your baskets and retry !</font>");
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.49");
		break;

	case "v4.49": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='mail_html_format' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'mail_html_format', '1', 'Format d\'envoi des mails : \n 0: Texte brut\n 1: HTML \nAttention, ne fonctionne qu\'en mode d\'envoi smtp !')";
			echo traite_rqt($rqt,"insert pmb_mail_html_format='1' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='mail_html_format' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'opac', 'mail_html_format', '1', 'Format d\'envoi des mails à partir de l\'opac: \n 0: Texte brut\n 1: HTML \nAttention, ne fonctionne qu\'en mode d\'envoi smtp !', 'a_general', 0)";
			echo traite_rqt($rqt,"insert opac_mail_html_format='1' into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='websubscribe_empr_location' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('opac','websubscribe_empr_location', '0', 'Id de la localisation des inscrits par le web non adhérents complets', 'f_modules', '0')";
			echo traite_rqt($rqt,"insert opac_websubscribe_empr_location='0' into parametres");
			}
			
		$rqt = "ALTER TABLE empr ADD cle_validation VARCHAR( 255 ) DEFAULT '' NOT NULL ";
		echo traite_rqt($rqt,"alter table empr add cle_validation");
		$rqt = "ALTER TABLE empr CHANGE empr_creation empr_creation DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL ";
		echo traite_rqt($rqt,"alter table empr CHANGE empr_creation DATETIME ");
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.50");
		break;

	case "v4.50": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+

		$rqt = "ALTER TABLE bannettes ADD typeexport VARCHAR( 20 ) NOT NULL default '' ";
		echo traite_rqt($rqt,"ALTER TABLE bannettes ADD typeexport ");
		$rqt = "ALTER TABLE bannettes ADD prefixe_fichier VARCHAR( 50 ) NOT NULL default '' ";
		echo traite_rqt($rqt,"ALTER TABLE bannettes ADD prefixe_fichier ");
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='allow_bannette_export' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'opac', 'allow_bannette_export', '0', 'Possibilité pour les lecteurs de recevoir les notices de leurs bannettes privées en pièce jointe au mail ?\n 0: Non \n 1: Oui','l_dsi')";
			echo traite_rqt($rqt,"insert opac_allow_bannette_export=0 into parametres");
			}

		$rqt = "UPDATE parametres SET comment_param = 'Numéro de carte de lecteur automatique ?\n 0: Non (si utilisation de cartes pré-imprimées)\n 1: Oui, entièrement numérique\n 2,a,b,c: Oui avec préfixe: a=longueur du préfixe, b=nombre de chiffres de la partie numérique, c=préfixe fixé (facultatif)' WHERE type_param='pmb' and sstype_param='num_carte_auto' " ;
		echo traite_rqt($rqt,"UPDATE parametres SET comment_param =... WHERE type_param='pmb' and sstype_param='num_carte_auto'");

		$rqt = "ALTER TABLE users ADD value_deflt_antivol VARCHAR( 50 ) NOT NULL default '0' AFTER value_email_bcc" ;		
		echo traite_rqt($rqt,"ALTER TABLE users ADD value_deflt_antivol");

		$rqt = "ALTER TABLE connectors_sources ADD opac_allowed INT( 3 ) UNSIGNED DEFAULT 0 NOT NULL";
		echo traite_rqt($rqt,"ALTER TABLE connectors_sources ADD opac_allowed");
		
		$rqt = "ALTER TABLE caddie_content DROP INDEX caddie_id";
		echo traite_rqt($rqt,"ALTER TABLE caddie_content DROP INDEX caddie_id");
		$rqt = "ALTER TABLE caddie_content CHANGE content content VARCHAR( 100 ) NOT NULL default ''";
		echo traite_rqt($rqt,"ALTER TABLE caddie_content CHANGE content VARCHAR( 100 )");
		$rqt = "ALTER TABLE caddie_content DROP PRIMARY KEY ";
		echo traite_rqt($rqt,"ALTER TABLE caddie_content DROP PRIMARY KEY ");
		$rqt = "ALTER TABLE caddie_content ADD PRIMARY KEY pk_caddie_content ( caddie_id , object_id , content )";
		echo traite_rqt($rqt,"ALTER TABLE caddie_content ADD PRIMARY KEY ( caddie_id , object_id , content ) : <font color=red>ATTENTION, Cette requête DOIT fonctionner, en cas d'échec, vider vos paniers et recommencer ! <br />This query MUST work, if it doesn't, empty your baskets and retry !</font>");

		$rqt = "CREATE TABLE procs_classements (idproc_classement smallint(5) unsigned NOT NULL auto_increment,libproc_classement varchar(255) NOT NULL default '', PRIMARY KEY (idproc_classement) )";
		echo traite_rqt($rqt,"CREATE TABLE procs_classements ");
		$rqt = "ALTER TABLE procs ADD num_classement INT( 5 ) UNSIGNED DEFAULT 0 NOT NULL";
		echo traite_rqt($rqt,"ALTER TABLE procs ADD num_classement");
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.51");
		break;

	case "v4.51": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE responsability CHANGE responsability_fonction responsability_fonction VARCHAR( 4 ) NOT NULL default '' ";
		echo traite_rqt($rqt,"ALTER TABLE responsability CHANGE responsability_fonction VARCHAR( 4 )");
		
		$rqt = "ALTER TABLE bulletins CHANGE num_notice num_notice INT( 10 ) UNSIGNED not null DEFAULT 0 ";
		echo traite_rqt($rqt,"ALTER TABLE bulletins CHANGE num_notice ");
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.52");
		break;

	case "v4.52": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE empr_caddie_content DROP PRIMARY KEY ";
		echo traite_rqt($rqt,"ALTER TABLE empr_caddie_content DROP PRIMARY KEY ");
		$rqt = "ALTER TABLE empr_caddie_content ADD PRIMARY KEY empr_caddie_id (empr_caddie_id,object_id)";
		echo traite_rqt($rqt,"ALTER TABLE empr_caddie_content ADD PRIMARY KEY empr_caddie_id (empr_caddie_id,object_id) : <font color=red>ATTENTION, Cette requête DOIT fonctionner, en cas d'échec, vider vos paniers d'emprunteurs et recommencer ! <br />This query MUST work, if it doesn't, empty your borrower's baskets and retry !</font>");
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.53");
		break;

	case "v4.53": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='expl_data' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'opac', 'expl_data', 'expl_cb,expl_cote,tdoc_libelle,location_libelle,section_libelle', 'Colonne des exemplaires, dans l\'ordre donné, séparé par des virgules : expl_cb,expl_cote,tdoc_libelle,location_libelle,section_libelle','e_aff_notice')";
			echo traite_rqt($rqt,"insert opac_expl_data=expl_cb,expl_cote,tdoc_libelle,location_libelle,section_libelle into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='expl_order' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'opac', 'expl_order', 'location_libelle,section_libelle,expl_cote,tdoc_libelle', 'Ordre d\'affichage des exemplaires, dans l\'ordre donné, séparé par des virgules : location_libelle,section_libelle,expl_cote,tdoc_libelle','e_aff_notice')";
			echo traite_rqt($rqt,"insert opac_expl_order=location_libelle,section_libelle,expl_cote,tdoc_libelle into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='curl_available' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'opac', 'curl_available', '1', 'La librairie cURL est-elle disponible pour les interrogations RSS notamment ? \n 0: Non \n 1: Oui','a_general')";
			echo traite_rqt($rqt,"insert opac_curl_available=1 into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='curl_available' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'curl_available', '1', 'La librairie cURL est-elle disponible pour les interrogations RSS notamment ? \n 0: Non \n 1: Oui')";
			echo traite_rqt($rqt,"insert pmb_curl_available=1 into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param='opac' and sstype_param='thesaurus_defaut' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, gestion, section_param) VALUES (0, 'opac', 'thesaurus_defaut', '1', 'Identifiant du thésaurus par défaut.', 0, 'i_categories') ";
			echo traite_rqt($rqt, "insert opac_thesaurus_defaut=1 into parameters");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param='opac' and sstype_param='recherches_pliables' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, gestion, section_param) VALUES (0, 'opac', 'recherches_pliables', '0', 'Les cases à cocher de la recherche simple sont-elles pliées ? \n 0: Non \n 1: Oui et pliée par défaut \n 2: Oui et dépliée par défaut', 0, 'c_recherche') ";
			echo traite_rqt($rqt, "insert opac_recherches_pliables=0 into parameters");
			}
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.54");
		break;

	case "v4.54": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param='pmb' and sstype_param='rfid_ip_port' "))==0){
			$rqt = "INSERT INTO parametres (type_param ,sstype_param ,valeur_param, comment_param, gestion) VALUES ('pmb', 'rfid_ip_port', '192.168.0.10,SerialPort=10;', 'Association ip du poste de prêt et Numéro du port utilisé par le serveur RFID. Ex: 192.168.0.10,SerialPort=10; IpPosteClient,SerialPort=NumPortPlatine; séparer par des points-virgules pour désigner tous les postes' , '0')";
			echo traite_rqt($rqt, "insert pmb_rfid_ip_port into parameters");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param='pmb' and sstype_param='pret_timeout_temp' "))==0){
			$rqt = "INSERT INTO parametres (type_param ,sstype_param ,valeur_param,comment_param ) VALUES ('pmb', 'pret_timeout_temp', '15', 'Temps en minutes, après lequel un prêt temporaire est effacé' )";
			echo traite_rqt($rqt, "insert pmb_pret_timeout_temp into parameters");
			}
		$rqt = "ALTER TABLE pret ADD pret_temp VARCHAR( 50 ) NOT NULL default ''";
		echo traite_rqt($rqt,"ALTER TABLE pret ADD pret_temp VARCHAR( 50 ) NOT NULL default ''");

		$rqt = "create table external_count (rid bigint unsigned not null auto_increment, recid varchar(255) not null default '', index(recid), primary key(rid))";
		echo traite_rqt($rqt,"create table external_count ");

		$rqt = "insert into external_count (recid) select distinct recid from entrepots";
		echo traite_rqt($rqt,"insert into external_count ... ");

		$rqt = "update external_count, entrepots set entrepots.recid=rid where external_count.recid=entrepots.recid";
		echo traite_rqt($rqt,"update external_count, entrepots set ... ");

		$rqt = "alter table entrepots modify recid bigint unsigned not null default 0";
		echo traite_rqt($rqt,"alter table entrepots modify recid bigint not null default 0 ");

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.55");
		break;

	case "v4.55": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "alter table entrepots modify recid bigint unsigned not null default 0";
		echo traite_rqt($rqt,"alter table entrepots modify recid bigint not null default 0 ");

		$rqt = "ALTER TABLE entrepots drop index i_recid_source_id " ;
		echo traite_rqt($rqt,"drop index i_recid_source_id");
		$rqt = "ALTER TABLE entrepots ADD INDEX i_recid_source_id (recid,source_id) ";
		echo traite_rqt($rqt,"ADD INDEX i_recid_source_id ") ;
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.56");
		break;

	case "v4.56": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='permalink' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'opac', 'permalink', '0', 'Afficher l\'Id de la notice avec un lien permanent ? \n 0: Non \n 1: Oui','e_aff_notice')";
			echo traite_rqt($rqt,"insert opac_permalink=0 into parametres");
			}
			
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='3after_recouvrement' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param, comment_param, section_param, gestion) VALUES ('pdflettreretard', '3after_recouvrement', 'Sans nouvelles de votre part dans les sept jours, nous nous verrons contraints de déléguer au Trésor Public le recouvrement des ouvrages ci-dessus.', 'Texte apparaissant après la liste des ouvrages en recouvrement s\'il n\'y a pas d\'autres ouvrages en niveau 1 et 2', '', '0')";
			echo traite_rqt($rqt,"insert pdflettreretard_3after_recouvrement=... into parametres");
			}
			
		$rqt = "ALTER TABLE notices DROP INDEX i_notice_n_biblio ";
		echo traite_rqt($rqt,"DROP INDEX i_notice_n_biblio ") ;
		$rqt = "ALTER TABLE notices ADD INDEX i_notice_n_biblio (niveau_biblio) ";
		echo traite_rqt($rqt,"ADD INDEX i_notice_n_biblio ") ;
		$rqt = "ALTER TABLE notices DROP INDEX i_notice_n_hierar ";
		echo traite_rqt($rqt,"DROP INDEX i_notice_n_hierar ") ;
		$rqt = "ALTER TABLE notices ADD INDEX i_notice_n_hierar (niveau_hierar) ";
		echo traite_rqt($rqt,"ADD INDEX i_notice_n_hierar ") ;

		$rqt = "ALTER TABLE pret_archive DROP INDEX i_pa_idempr ";
		echo traite_rqt($rqt,"DROP INDEX i_pa_idempr ") ;
		$rqt = "ALTER TABLE pret_archive ADD INDEX i_pa_idempr (arc_id_empr) ";
		echo traite_rqt($rqt,"ADD INDEX i_pa_idempr ") ;

		$rqt = "ALTER TABLE pret_archive DROP INDEX i_pa_expl_notice ";
		echo traite_rqt($rqt,"DROP INDEX i_pa_expl_notice ") ;
		$rqt = "ALTER TABLE pret_archive ADD INDEX i_pa_expl_notice (arc_expl_notice) ";
		echo traite_rqt($rqt,"ADD INDEX i_pa_expl_notice ") ;

		$rqt = "ALTER TABLE pret_archive DROP INDEX i_pa_expl_bulletin ";
		echo traite_rqt($rqt,"DROP INDEX i_pa_expl_bulletin ") ;
		$rqt = "ALTER TABLE pret_archive ADD INDEX i_pa_expl_bulletin (arc_expl_bulletin) ";
		echo traite_rqt($rqt,"ADD INDEX i_pa_expl_bulletin ") ;

		$rqt = "ALTER TABLE pret_archive DROP INDEX i_pa_arc_fin ";
		echo traite_rqt($rqt,"DROP INDEX i_pa_arc_fin ") ;
		$rqt = "ALTER TABLE pret_archive ADD INDEX i_pa_arc_fin (arc_fin) ";
		echo traite_rqt($rqt,"ADD INDEX i_pa_arc_fin ") ;

		$rqt = "ALTER TABLE pret_archive DROP INDEX i_pa_arc_expl_id ";
		echo traite_rqt($rqt,"DROP INDEX i_pa_expl_id ") ;
		$rqt = "ALTER TABLE pret_archive ADD INDEX i_pa_expl_id (arc_expl_id) ";
		echo traite_rqt($rqt,"ADD INDEX i_pa_expl_id ") ;

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.57");
		break;

	case "v4.57": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pdflettreretard' and sstype_param='impression_tri' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param, comment_param, section_param, gestion) VALUES ('pdflettreretard', 'impression_tri', 'empr_cp,empr_ville,empr_nom,empr_prenom', 'Tri pour l\'impression des lettres de relances ? Les champs sont ceux de la table empr séparés par des virgules. Exemple: empr_nom, empr_prenom', '', '0')";
			echo traite_rqt($rqt,"insert pdflettreretard_impression_tri=cp,ville,nom,prenom into parametres");
			}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='pret_date_retour_adhesion_depassee' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param, comment_param, section_param, gestion) VALUES ('pmb', 'pret_date_retour_adhesion_depassee', '0', 'La date de retour peut-elle dépasser la date de fin d\'adhésion ? \n 0: Non: la date de retour sera calculée pour ne pas dépasser la date de fin d\'adhésion. \n 1: Oui, la date de retour du prêt sera indépendante de la date de fin d\'adhésion.', '', '0')";
			echo traite_rqt($rqt,"insert pmb_pret_date_retour_adhesion_depassee=0 into parametres");
			}
		$rqt = "ALTER TABLE collections ADD collection_web VARCHAR( 255 ) NOT NULL default '' ";
		echo traite_rqt($rqt,"ALTER TABLE collections ADD collection_web VARCHAR(255) ") ;

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.58");
		break;

	case "v4.58": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE notices_custom ADD search INT(1) unsigned NOT NULL DEFAULT 0";
		echo traite_rqt($rqt,"ALTER TABLE notices_custom ADD search ") ;
		$rqt = "ALTER TABLE empr_custom ADD search INT(1) unsigned NOT NULL DEFAULT 0";
		echo traite_rqt($rqt,"ALTER TABLE empr_custom ADD search ") ;
		$rqt = "ALTER TABLE expl_custom ADD search INT(1) unsigned NOT NULL DEFAULT 0";
		echo traite_rqt($rqt,"ALTER TABLE expl_custom ADD search ") ;

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.59");
		break;

	case "v4.59": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE abts_abts_modeles ADD num_statut_general SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT 0";
		echo traite_rqt($rqt,"ALTER TABLE abts_abts_modeles ADD num_statut_general") ;
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.60");
		break;

	case "v4.60": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='extended_search_auto' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'opac', 'extended_search_auto', '1', 'En recherche multicritères, la sélection d\'un champ ajoute celui-ci automatiquement sans avoir besoin de cliquer sur le bouton Ajouter ? \n 0: Non \n 1: Oui', 'c_recherche', 0) ";
			echo traite_rqt($rqt,"insert opac_extended_search_auto=1 into parametres") ;
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='extended_search_auto' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param)
					VALUES (0, 'pmb', 'extended_search_auto', '1', 'En recherche multicritères, la sélection d\'un champ ajoute celui-ci automatiquement sans avoir besoin de cliquer sur le bouton Ajouter ? \n 0: Non \n 1: Oui') ";
			echo traite_rqt($rqt,"insert pmb_extended_search_auto=1 into parametres") ;
		}
		
		$rqt = "ALTER TABLE tris ADD tri_reference VARCHAR( 40 ) NOT NULL DEFAULT 'notices';";
		echo traite_rqt($rqt,"ALTER TABLE tris ADD tri_reference") ;
		$rqt = "ALTER TABLE tris DROP tri_par_texte;";
		echo traite_rqt($rqt,"ALTER TABLE tris DROP tri_par_texte") ;
		
		$rqt = "ALTER TABLE responsability ADD responsability_ordre smallint(2) UNSIGNED NOT NULL DEFAULT 0";
		echo traite_rqt($rqt,"ALTER TABLE responsability ADD responsability_ordre") ;

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'thesaurus' and sstype_param='categories_affichage_ordre'"))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param)
					VALUES (0, 'thesaurus', 'categories_affichage_ordre', '0', 'Paramétrage de l\'ordre d\'affichage des catégories d\'une notice.\nPar ordre alphabétique: 0(par défaut)\nPar ordre de saisie: 1','categories') ";
			echo traite_rqt($rqt,"insert thesaurus_categories_affichage_ordre=0 into parametres") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='categories_affichage_ordre'"))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param)
					VALUES (0, 'opac', 'categories_affichage_ordre', '0', 'Paramétrage de l\'ordre d\'affichage des catégories d\'une notice.\nPar ordre alphabétique: 0(par défaut)\nPar ordre de saisie: 1', 'i_categories') ";
			echo traite_rqt($rqt,"insert opac_categories_affichage_ordre=0 into parametres") ;
		}

		$rqt = "ALTER TABLE notices_categories ADD ordre_categorie smallint(2) UNSIGNED NOT NULL DEFAULT 0";
		echo traite_rqt($rqt,"ALTER TABLE notices_categories ADD ordre_categorie") ;

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='rfid_driver' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('pmb','rfid_driver', '', 'Driver du pilote RFID : le nom du répertoire contenant les javascripts propres au matériel en place.', '', '0')" ;
			echo traite_rqt($rqt,"insert pmb_rfid_driver='' into parametres");
			}

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.61");
		break;

	case "v4.61": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE notices CHANGE year year VARCHAR( 50 )";
		echo traite_rqt($rqt,"ALTER TABLE notices CHANGE year VARCHAR( 50 )") ;

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='scan_pmbws_client_url'"))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param)
					VALUES (0, 'pmb', 'scan_pmbws_client_url', '', 'URL de l\'interface de numérisation (client du webservice)','') ";
			echo traite_rqt($rqt,"insert pmb_scan_pmbws_client_url into parametres") ;
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='scan_pmbws_url'"))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param)
					VALUES (0, 'pmb', 'scan_pmbws_url', '', 'URL du webservice de pilotage du scanner','') ";
			echo traite_rqt($rqt,"insert pmb_scan_pmbws_url into parametres") ;
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='biblio_main_header'"))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param)
					VALUES (0, 'opac', 'biblio_main_header', '', 'Texte pouvant apparaitre dans le bloc principal, au dessus de tous les autres, nécessaire pour certaines mises en page particulières.','b_aff_general') ";
			echo traite_rqt($rqt,"insert opac_biblio_main_header into parametres") ;
		}
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.62");
		break;

	case "v4.62": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='sugg_localises'"))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES ( 'opac', 'sugg_localises', '0', 'Activer la localisation des suggestions des lecteurs ? \n 0: Pas de localisation possible.\n 1: Localisation au choix du lecteur.\n 2: Localisation restreinte à la localisation du lecteur.', 'a_general', '0')";
			echo traite_rqt($rqt,"insert opac_sugg_localises into parametres") ;
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'acquisition' and sstype_param='sugg_localises'"))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES ( 'acquisition', 'sugg_localises', '0', 'Activer la localisation des suggestions ? \n 0: Pas de localisation possible. \n 1: Localisation activée.', '', '0')";
			echo traite_rqt($rqt,"insert acquisition_sugg_localises into parametres") ;
		}
		
		$rqt = "ALTER TABLE suggestions ADD sugg_location SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT 0";
		echo traite_rqt($rqt,"ALTER TABLE suggestions ADD sugg_location") ;

		// suppression des modeles    
		$rqt = "DELETE FROM abts_modeles WHERE num_notice not in (select notice_id from notices where niveau_biblio='s')";
		mysql_query($rqt, $dbh);    

		// suppression des abonnements    
		$rqt = "DELETE FROM abts_abts WHERE num_notice not in (select notice_id from notices where niveau_biblio='s')";
		mysql_query($rqt, $dbh);    

	    // vide la grille d'abonnement
    	$rqt = "DELETE FROM abts_grille_abt WHERE num_abt not in (select abt_id from abts_abts)";
    	mysql_query($rqt, $dbh);        
		
	    // elimine les liens entre modele et abonnements
	    $rqt = "DELETE FROM abts_abts_modeles WHERE modele_id not in (select modele_id from abts_modeles)";
	    mysql_query($rqt, $dbh);                
    	
	    // vide la grille de modele
	    $rqt = "DELETE FROM abts_grille_modele WHERE num_modele not in (select modele_id from abts_modeles) ";
	    mysql_query($rqt, $dbh);        
	    
		//pour jointures avec la table acte
		$rqt = "ALTER TABLE lignes_actes DROP INDEX num_acte ";
		echo traite_rqt($rqt,"DROP INDEX num_acte ") ;
		$rqt = "ALTER TABLE lignes_actes ADD INDEX num_acte (num_acte) ";
		echo traite_rqt($rqt,"ADD INDEX num_acte  ") ;

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.63");
		break;

	case "v4.63": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE notices CHANGE nocoll nocoll VARCHAR(255),
			CHANGE npages npages VARCHAR(255), CHANGE ill ill VARCHAR(255),
			CHANGE size size VARCHAR(255), CHANGE accomp accomp VARCHAR(255)";
		echo traite_rqt($rqt,"ALTER TABLE notices CHANGE nocoll and coll size") ;	

		$rqt = "ALTER TABLE empr drop index i_empr_categ " ;
		echo traite_rqt($rqt,"drop index i_empr_categ");
		$rqt = "ALTER TABLE empr ADD INDEX i_empr_categ (empr_categ) " ;
		echo traite_rqt($rqt,"create index i_empr_categ");
		
		$rqt = "ALTER TABLE empr drop index i_empr_codestat " ;
		echo traite_rqt($rqt,"drop index i_empr_codestat");
		$rqt = "ALTER TABLE empr ADD INDEX i_empr_codestat (empr_codestat) " ;
		echo traite_rqt($rqt,"create index i_empr_codestat");
		
		$rqt = "ALTER TABLE empr drop index i_empr_location " ;
		echo traite_rqt($rqt,"drop index i_empr_location");
		$rqt = "ALTER TABLE empr ADD INDEX i_empr_location (empr_location) " ;
		echo traite_rqt($rqt,"create index i_empr_location");
		
		$rqt = "ALTER TABLE empr drop index i_empr_statut " ;
		echo traite_rqt($rqt,"drop index i_empr_statut");
		$rqt = "ALTER TABLE empr ADD INDEX i_empr_statut (empr_statut) " ;
		echo traite_rqt($rqt,"create index i_empr_statut");
		
		$rqt = "ALTER TABLE empr drop index i_empr_typabt " ;
		echo traite_rqt($rqt,"drop index i_empr_typabt");
		$rqt = "ALTER TABLE empr ADD INDEX i_empr_typabt (type_abt) " ;
		echo traite_rqt($rqt,"create index i_empr_typabt");
		
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'opac' and sstype_param='categories_nav_max_display' "))==0){
			$rqt = "INSERT INTO parametres (type_param ,sstype_param ,valeur_param ,comment_param ,section_param ,gestion)
					VALUES ('opac', 'categories_nav_max_display', '200', 'Limiter l\'affichage des catégories en navigation dans les sous-catégories. 0: Pas de limitation. >0: Nombre max de catégories à afficher', 'i_categories','0') ";
			echo traite_rqt($rqt,"INSERT opac_categories_nav_max_display INTO parametres") ;
		}

 		$rqt = "ALTER TABLE exemplaires drop index i_expl_location " ;
		echo traite_rqt($rqt,"drop index i_expl_location");
		$rqt = "ALTER TABLE exemplaires ADD INDEX i_expl_location (expl_location) " ;
		echo traite_rqt($rqt,"create index i_expl_location");
		
		$rqt = "ALTER TABLE exemplaires drop index i_expl_section " ;
		echo traite_rqt($rqt,"drop index i_expl_section");
		$rqt = "ALTER TABLE exemplaires ADD INDEX i_expl_section (expl_section) " ;
		echo traite_rqt($rqt,"create index i_expl_section");
		
		$rqt = "ALTER TABLE exemplaires drop index i_expl_statut " ;
		echo traite_rqt($rqt,"drop index i_expl_statut");
		$rqt = "ALTER TABLE exemplaires ADD INDEX i_expl_statut (expl_statut) " ;
		echo traite_rqt($rqt,"create index i_expl_statut");
		
		$rqt = "ALTER TABLE exemplaires drop index i_expl_lastempr " ;
		echo traite_rqt($rqt,"drop index i_expl_lastempr");
		$rqt = "ALTER TABLE exemplaires ADD INDEX i_expl_lastempr (expl_lastempr) " ;
		echo traite_rqt($rqt,"create index i_expl_lastempr");

		$rqt = "ALTER TABLE exemplaires drop index i_pret_idempr " ;
		echo traite_rqt($rqt,"drop index i_pret_idempr");
		$rqt = "ALTER TABLE pret ADD INDEX i_pret_idempr (pret_idempr) " ;
		echo traite_rqt($rqt,"create index i_pret_idempr");
		
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'pmb' and sstype_param='pret_aff_limitation' "))==0){
			$rqt = "INSERT INTO parametres (type_param ,sstype_param ,valeur_param,comment_param ,section_param ,gestion) 
				VALUES ( 'pmb', 'pret_aff_limitation', '0', 'Activer la limitation de l\'affichage de la liste des prêts dans la fiche lecteur ? \n 0: Inactif. \n 1: Limitation activée', '','0')";
			echo traite_rqt($rqt,"INSERT pmb_pret_aff_limitation INTO parametres") ;
		}

		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'pmb' and sstype_param='pret_aff_nombre' "))==0){
			$rqt = "INSERT INTO parametres (type_param ,sstype_param ,valeur_param,comment_param ,section_param ,gestion)
				VALUES ( 'pmb', 'pret_aff_nombre', '10', 'Nombre de prêts à afficher si le paramètre pret_aff_limitation est actif. \n 0: tout voir, illimité. \n ## Nombre de prêts à afficher sur la première page', '','0')";
			echo traite_rqt($rqt,"INSERT pmb_pret_aff_nombre INTO parametres") ;
		}

		$rqt = "ALTER TABLE notices CHANGE nocoll nocoll VARCHAR(255),
			CHANGE npages npages VARCHAR(255), CHANGE ill ill VARCHAR(255),
			CHANGE size size VARCHAR(255), CHANGE accomp accomp VARCHAR(255)";
		echo traite_rqt($rqt,"ALTER TABLE notices CHANGE nocoll and coll size") ;	

		$rqt = "ALTER TABLE comptes drop index i_cpt_proprio_id " ;
		echo traite_rqt($rqt,"drop index i_cpt_proprio_id");
		$rqt = "ALTER TABLE comptes ADD INDEX i_cpt_proprio_id (proprio_id) " ;
		echo traite_rqt($rqt,"create index i_cpt_proprio_id");
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.64");
		break;

	case "v4.64": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='printer_ticket_url'"))==0){
			$rqt = "INSERT INTO parametres (type_param ,sstype_param ,valeur_param,comment_param ,section_param ,gestion) VALUES ( 'pmb', 'printer_ticket_url', '', 'Permet d\'utiliser une imprimante de ticket, connectée en local sur le poste de prêt client. Vide : pas d\'imprimante. Url (http://localhost/printer/bixolon_srp350.php ) : imprimante active.', '','0')";
			echo traite_rqt($rqt,"insert pmb_printer_ticket_url into parametres") ;
		}

	    //pour les transferts
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'transferts' and sstype_param='gestion_transferts' "))==1){
			$rqt = "DELETE FROM parametres WHERE type_param='transferts' AND sstype_param='gestion_transferts'";
			echo traite_rqt($rqt,"DELETE gestion_transferts INTO parametres") ;
		}
		
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres where type_param= 'transferts' and sstype_param='transfert_statut' "))==1){
			$rqt = "DELETE FROM parametres WHERE type_param='transferts' AND sstype_param='transfert_statut'";
			echo traite_rqt($rqt,"DELETE transfert_statut INTO parametres") ;
		}

		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'pmb' and sstype_param='transferts_actif' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param)
					VALUES (0, 'pmb', 'transferts_actif', '0', 'Active le systeme de transferts d\'exemplaires entre sites\n 0: Non \n 1: Oui') ";
			echo traite_rqt($rqt,"INSERT transferts_actif INTO parametres") ;
		}

		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'transferts' and sstype_param='statut_validation' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'transferts', 'statut_validation', '0', '1', 'id du statut dans lequel seront placés les documents dont le transfert est validé') ";
			echo traite_rqt($rqt,"INSERT statut_validation INTO parametres") ;
		}
		
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'transferts' and sstype_param='statut_transferts' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'transferts', 'statut_transferts', '0', '1', 'id du statut dans lequel seront placés les documents en cours de transit') ";
			echo traite_rqt($rqt,"INSERT satut_transferts INTO parametres") ;
		}

		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'transferts' and sstype_param='validation_actif' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'transferts', 'validation_actif', '1', '1', 'Active la validation des transferts\n 0: Non \n 1: Oui') ";
			echo traite_rqt($rqt,"INSERT validation_actif INTO parametres") ;
		}

		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'transferts' and sstype_param='nb_jours_pret_defaut' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'transferts', 'nb_jours_pret_defaut', '30', '1', 'Nombre de jours de pret par defaut') ";
			echo traite_rqt($rqt,"INSERT nb_jours_pret_defaut INTO parametres") ;
		}

		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'transferts' and sstype_param='nb_jours_alerte' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'transferts', 'nb_jours_alerte', '7', '1', 'Nombre de jours avant la fin du pret ou l\'alerte s\'affiche') ";
			echo traite_rqt($rqt,"INSERT nb_jours_alerte INTO parametres") ;
		}

		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'transferts' and sstype_param='transfert_transfere_actif' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'transferts', 'transfert_transfere_actif', '0', '1', 'Autorise le transfert d\'exemplaire deja transferer') ";
			echo traite_rqt($rqt,"INSERT transfert_transfere_actif INTO parametres") ;
		}


		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'transferts' and sstype_param='tableau_nb_lignes' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'transferts', 'tableau_nb_lignes', '10', '1', 'Nombre de transferts affichés dans les tableaux') ";
			echo traite_rqt($rqt,"INSERT tableau_nb_lignes into parametres") ;
		}
		
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'transferts' and sstype_param='envoi_lot' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'transferts', 'envoi_lot', '0', '1', 'traitement par lot possible en envoi') ";
			echo traite_rqt($rqt,"INSERT envoi_lot into parametres") ;
		}
		
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'transferts' and sstype_param='reception_lot' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'transferts', 'reception_lot', '0', '1', 'traitement par lot possible en reception') ";
			echo traite_rqt($rqt,"INSERT reception_lot into parametres") ;
		}
		
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'transferts' and sstype_param='retour_lot' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'transferts', 'retour_lot', '0', '1', 'traitement par lot possible en retour') ";
			echo traite_rqt($rqt,"INSERT retour_lot into parametres") ;
		}

		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'transferts' and sstype_param='retour_origine' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'transferts', 'retour_origine', '0', '1', 'Force le retour de l\'exemplaire dans son lieu d\'origine\n 0: Non \n 1: Oui') ";
			echo traite_rqt($rqt,"INSERT retour_origine INTO parametres") ;
		}

		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'transferts' and sstype_param='retour_origine_force' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'transferts', 'retour_origine_force', '1', '1', 'Permet de forcer le retour de l\'exemplaire\n 0: Non \n 1: Oui') ";
			echo traite_rqt($rqt,"INSERT retour_origine_force INTO parametres") ;
		}

		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'transferts' and sstype_param='retour_action_defaut' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'transferts', 'retour_action_defaut', '1', '1', 'Action par defaut lors du retour d\'un emprunt\n 0: change localisation \n 1: genere transfert') ";
			echo traite_rqt($rqt,"INSERT retour_action_defaut INTO parametres") ;
		}

		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'transferts' and sstype_param='retour_action_autorise_autre' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'transferts', 'retour_action_autorise_autre', '1', '1', 'Autorise une autre action lors du retour de l\'exemplaire\n 0: Non\n 1: Oui') ";
			echo traite_rqt($rqt,"INSERT retour_action_autorise_autre INTO parametres") ;
		}

		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'transferts' and sstype_param='retour_change_localisation' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'transferts', 'retour_change_localisation', '1', '1', 'Sauvegarde de la localisation lors du changement\n 0: Non \n 1: Oui') ";
			echo traite_rqt($rqt,"INSERT retour_change_localisation INTO parametres") ;
		}

		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'transferts' and sstype_param='retour_etat_transfert' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'transferts', 'retour_etat_transfert', '1', '1', 'Etat du transfert lors de sa generation auto\n 0: creer \n 1: envoyer') ";
			echo traite_rqt($rqt,"INSERT retour_etat_transfert INTO parametres") ;
		}

		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'transferts' and sstype_param='retour_motif_transfert' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'transferts', 'retour_motif_transfert', 'Transfert suite au retour de l\'exemplaire sur notre site', '1', 'Motif du transfert lors de sa generation auto') ";
			echo traite_rqt($rqt,"INSERT retour_motif_transfert INTO parametres") ;
		}


		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'transferts' and sstype_param='choix_lieu_opac' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'transferts', 'choix_lieu_opac', '0', '1', '0 pour pas de choix et obligatoirement dans la localisation ou est enregistré l\'utilisateur, 1 pour n\'importe quelle localisation au choix, 2 pour un lieu fixe précisé, 3 pour le lieu de l\'exemplaire') ";
			echo traite_rqt($rqt,"INSERT choix_lieu_opac INTO parametres") ;
		}
		
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'transferts' and sstype_param='site_fixe' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'transferts', 'site_fixe', '1', '1', 'id du site pour le retrait des livres si choix_lieu_opac=2') ";
			echo traite_rqt($rqt,"INSERT retour_origine INTO parametres") ;
		}

		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'transferts' and sstype_param='resa_motif_transfert' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'transferts', 'resa_motif_transfert', 'Transfert suite à une réservation', '1', 'Motif du transfert lors de sa generation auto pour une réservation') ";
			echo traite_rqt($rqt,"INSERT resa_motif_transfert INTO parametres") ;
		}
		
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'transferts' and sstype_param='resa_etat_transfert' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'transferts', 'resa_etat_transfert', '1', '1', 'Etat du transfert lors de sa generation auto\n 0: creer \n 1: envoyer') ";
			echo traite_rqt($rqt,"INSERT resa_etat_transfert INTO parametres") ;
		}

		$rqt = "DROP TABLE IF EXISTS transferts_demandes";
		echo traite_rqt($rqt,"DROP TABLE transferts_demandes") ;

		$rqt = "ALTER TABLE docs_location ADD transfert_ordre smallint(2) UNSIGNED NOT NULL DEFAULT 9999";
		echo traite_rqt($rqt,"ALTER TABLE docs_location ADD transfert_ordre") ;

		$rqt = "ALTER TABLE docs_location ADD transfert_statut_defaut smallint(5) UNSIGNED NOT NULL DEFAULT 0";
		echo traite_rqt($rqt,"ALTER TABLE docs_location ADD transfert_statut_defaut") ;

		$rqt = "ALTER TABLE exemplaires ADD transfert_location_origine SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0";
		echo traite_rqt($rqt,"ALTER TABLE exemplaires ADD transfert_location_origine") ;

		$rqt = "ALTER TABLE exemplaires ADD transfert_statut_origine SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0";
		echo traite_rqt($rqt,"ALTER TABLE exemplaires ADD transfert_statut_origine") ;

		$rqt = "ALTER TABLE resa ADD resa_loc_retrait SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0";
		echo traite_rqt($rqt,"ALTER TABLE resa ADD resa_loc_retrait") ;

		$rqt = "ALTER TABLE docs_statut ADD transfert_flag TINYINT( 4 ) UNSIGNED NOT NULL DEFAULT 1";
		echo traite_rqt($rqt,"ALTER TABLE docs_statut ADD transfert_flag") ;
		
		$rqt = "CREATE TABLE transferts (
			id_transfert INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			num_notice INT UNSIGNED NOT NULL default 0,
			num_bulletin INT UNSIGNED NOT NULL default 0,
			date_creation DATE NOT NULL ,
			type_transfert TINYINT UNSIGNED NOT NULL DEFAULT 0 ,
			etat_transfert TINYINT UNSIGNED NOT NULL DEFAULT 0 ,
			origine TINYINT UNSIGNED NOT NULL default 0,
			origine_comp VARCHAR(255) NOT NULL default '',
			source SMALLINT(5) UNSIGNED NULL,
			destinations VARCHAR(255) NULL,
			date_retour DATE,
			motif VARCHAR(255) NOT NULL default '',
			KEY etat_transfert (etat_transfert)
			)"; 
		echo traite_rqt($rqt,"CREATE TABLE 'transferts'") ;

 		$rqt = "CREATE TABLE transferts_demande (
			id_transfert_demande INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			num_transfert INT UNSIGNED NOT NULL default 0,
			date_creation DATE NOT NULL ,
			sens_transfert TINYINT UNSIGNED NOT NULL DEFAULT 0,
			num_location_source SMALLINT(5) UNSIGNED NOT NULL default 0,
			num_location_dest SMALLINT(5) UNSIGNED NOT NULL default 0,
			num_expl INT UNSIGNED NOT NULL default 0,
			etat_demande TINYINT UNSIGNED NOT NULL DEFAULT 0,
			date_visualisee DATE NULL,
			date_envoyee DATE NULL,
			date_reception DATE NULL,
			motif_refus VARCHAR(255) NOT NULL default '',
			KEY num_transfert (num_transfert),
			KEY num_location_source (num_location_source),
			KEY num_location_dest (num_location_dest),
			KEY num_expl (num_expl)
			)"; 
		echo traite_rqt($rqt,"CREATE TABLE 'transferts_demande'") ;
	       
		$rqt = "ALTER TABLE transferts_demande ADD statut_origine INT(10) UNSIGNED NOT NULL DEFAULT 0";
		echo traite_rqt($rqt,"ALTER TABLE transferts_demande ADD statut_origine") ;
	
		$rqt = "ALTER TABLE transferts_demande ADD section_origine INT(10) UNSIGNED NOT NULL DEFAULT 0";
		echo traite_rqt($rqt,"ALTER TABLE transferts_demande ADD section_origine") ;
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.65");
		break;

	case "v4.65": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		
		@set_time_limit(0);
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='recherche_ajax_mode'"))==0){
			$rqt = "INSERT INTO parametres (id_param ,type_param ,sstype_param ,valeur_param,comment_param ,section_param ,gestion)
					VALUES ( 0 , 'pmb', 'recherche_ajax_mode', '1', 'Affichage accéléré des résultats de recherche: \"réduit\" uniquement, la suite est chargée lors du click sur le \"+\". \n 0: Inactif \n 1: Actif', '', '0')";
			echo traite_rqt($rqt,"insert pmb_recherche_ajax_mode into parametres") ;
		}

		//Parametres utilisateur pour acquisitions
		$rqt = "ALTER TABLE users ADD deflt3bibli int(5) unsigned not null default '0' ";		
		echo traite_rqt($rqt,"ALTER TABLE users ADD default bibli");
		$rqt = "ALTER TABLE users ADD deflt3exercice int(8) unsigned not null default '0' ";		
		echo traite_rqt($rqt,"ALTER TABLE users ADD default exercice");
		$rqt = "ALTER TABLE users ADD deflt3rubrique int(8) unsigned not null default '0' ";		
		echo traite_rqt($rqt,"ALTER TABLE users ADD default rubrique");
		$rqt = "ALTER TABLE users ADD deflt3dev_statut int(3) not null default '-1' ";
		echo traite_rqt($rqt,"ALTER TABLE users ADD default dev state");
		$rqt = "ALTER TABLE users ADD deflt3cde_statut int(3) not null default '-1' ";
		echo traite_rqt($rqt,"ALTER TABLE users ADD default cde state");
		$rqt = "ALTER TABLE users ADD deflt3liv_statut int(3) not null default '-1' ";
		echo traite_rqt($rqt,"ALTER TABLE users ADD default liv state");
		$rqt = "ALTER TABLE users ADD deflt3fac_statut int(3) not null default '-1' ";
		echo traite_rqt($rqt,"ALTER TABLE users ADD default fac state");
		$rqt = "ALTER TABLE users ADD deflt3sug_statut int(3) not null default '-1' ";
		echo traite_rqt($rqt,"ALTER TABLE users ADD default sug state");

		//Modification de la table external_count
		$sql_alter_external_count = "ALTER TABLE external_count ADD source_id INT NOT NULL ";
		echo traite_rqt($sql_alter_external_count,"Modification de la table external_count 1");
		$sql_alter_external_count = "UPDATE external_count, entrepots SET external_count.source_id = entrepots.source_id WHERE entrepots.recid = external_count.rid ";
		echo traite_rqt($sql_alter_external_count,"Modification de la table external_count 2");

		//Récupération de la liste des sources
		$sql_liste_sources = "SELECT source_id FROM connectors_sources ";
		$res_liste_sources = mysql_query($sql_liste_sources, $dbh) or die(mysql_error());

		//Pour chaque source
		while ($row=mysql_fetch_row($res_liste_sources)) {
			//On créer la table
			$sql_create_table = "CREATE TABLE entrepot_source_".$row[0]." (
							  connector_id varchar(20) NOT NULL default '',
							  source_id int(11) unsigned NOT NULL default 0,
							  ref varchar(220) NOT NULL default '',
							  date_import datetime NOT NULL default '0000-00-00 00:00:00',
							  ufield char(3) NOT NULL default '',
							  usubfield char(1) NOT NULL default '',
							  field_order int(10) unsigned NOT NULL default 0,
							  subfield_order int(10) unsigned NOT NULL default 0,
							  value text NOT NULL,
							  i_value text NOT NULL,
							  recid bigint(20) unsigned NOT NULL default 0,
							  search_id varchar(32) NOT NULL default '',
							  PRIMARY KEY  (connector_id,source_id,ref,ufield,usubfield,field_order,subfield_order,search_id),
							  KEY usubfield (usubfield),
							  KEY ufield_2 (ufield,usubfield),
							  KEY recid_2 (recid,ufield,usubfield),
							  KEY source_id (source_id),
							  KEY i_recid_source_id (recid,source_id)
							) ";
			echo traite_rqt($sql_create_table, "CREATE TABLE entrepot_source_".$row[0]);
			
			//On copie les éléments de la source dans sa nouvelle table
			$sql_transfer = "INSERT INTO entrepot_source_".$row[0]." (SELECT * FROM entrepots WHERE source_id = ".$row[0].")";
			echo traite_rqt($sql_transfer, "INSERT INTO entrepot_source_".$row[0]);
		}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.66");
		break;

	case "v4.66": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='expl_title_display_format'"))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param,comment_param ,section_param ,gestion)
			VALUES ('pmb', 'expl_title_display_format', 'expl_location,expl_section,expl_cote,expl_cb','Format d\'affichage du titre de l\'exemplaire en recherche multi-critères d\'exemplaires. Les libellés des champs correspondent aux champs de la table exemplaires, ou aux id de champs personnalisés. Séparés par une virgule. Les champs disposant d\'un libellé seront remplacés par le libellé correspondant. Exemple: expl_location,expl_section,expl_cote,expl_cb', '', '0')";
			echo traite_rqt($rqt,"insert pmb_expl_title_display_format into parametres") ;
		}

		$rqt = "DROP TABLE entrepots"; //La requête violente
		echo traite_rqt($rqt, "DROP TABLE entrepots");

		$rqt = "ALTER TABLE exemplaires ADD expl_comment VARCHAR(255) NOT NULL default ''"; 
		echo traite_rqt($rqt, "ALTER TABLE exemplaires ADD expl_comment");

		//Ajout d'une date d'echeance dans les actes
		$rqt = "ALTER TABLE actes ADD date_ech DATE NOT NULL DEFAULT '0000-00-00' ";
		echo traite_rqt($rqt,"ALTER TABLE actes ADD date_ech ");

		//Modification du parametre gestion tva
		$rqt = "UPDATE parametres SET comment_param = 'Gestion de la TVA.\n 0 : Non.\n 1 : Oui, avec saisie des prix HT.\n 2 : Oui, avec saisie des prix TTC.' WHERE type_param='acquisition' and sstype_param='gestion_tva' ";
		echo traite_rqt($rqt,"UPDATE parametres set gestion_tva = 0,1,2");

		//Ajout d'un ordre dans les lignes d'acte
		$rqt = "ALTER TABLE lignes_actes ADD ligne_ordre SMALLINT(2) UNSIGNED NOT NULL DEFAULT '0' ";
		echo traite_rqt($rqt,"ALTER TABLE lignes_actes ADD ligne_ordre");

		$rqt = "ALTER TABLE rss_content ADD rss_content_parse LONGBLOB NOT NULL default '' AFTER rss_content ";
		echo traite_rqt($rqt,"ALTER TABLE rss_content ADD rss_content_parse");

		$rqt = "ALTER TABLE notices drop index notice_eformat" ;
		echo traite_rqt($rqt,"drop index notice_eformat");
		$rqt = "ALTER TABLE notices ADD INDEX notice_eformat (eformat)" ;
		echo traite_rqt($rqt,"ALTER TABLE notices ADD INDEX notice_eformat");
		
		$rqt = "ALTER TABLE users ADD environnement MEDIUMBLOB NOT NULL default ''" ;
		echo traite_rqt($rqt,"ALTER TABLE users ADD environnement ");
		
		$rqt = "ALTER TABLE notices ADD thumbnail_url MEDIUMBLOB NOT NULL default '' " ;
		echo traite_rqt($rqt,"ALTER TABLE notices ADD thumbnail_url ");

		$rqt = "CREATE TABLE connectors_categ (
			  connectors_categ_id smallint(5) NOT NULL auto_increment,
			  connectors_categ_name varchar(64) NOT NULL default '',
			  opac_expanded smallint(6) NOT NULL default 0,
			  PRIMARY KEY  (connectors_categ_id)
			  )" ;
		echo traite_rqt($rqt,"CREATE TABLE connectors_categ ");

		$rqt = "CREATE TABLE connectors_categ_sources (
			  num_categ smallint(6) NOT NULL default 0,
			  num_source smallint(6) NOT NULL default 0,
			  PRIMARY KEY  (num_categ,num_source),
			  index i_num_source (num_source)
			  )" ;
		echo traite_rqt($rqt,"CREATE TABLE connectors_categ_sources");

		$rqt = "ALTER TABLE connectors_sources CHANGE parameters parameters MEDIUMTEXT NOT NULL default '' " ;
		echo traite_rqt($rqt,"ALTER TABLE connectors_sources CHANGE parameters MEDIUMTEXT ");
		
		$rqt = "ALTER TABLE empr CHANGE empr_password empr_password VARCHAR( 255 ) NOT NULL  default '' " ;
		echo traite_rqt($rqt,"ALTER TABLE empr CHANGE empr_password VARCHAR( 255 )");
		
		$rqt = "ALTER TABLE users ADD value_deflt_relation VARCHAR( 20 ) NOT NULL DEFAULT 'a' AFTER value_deflt_fonction " ;
		echo traite_rqt($rqt,"ALTER TABLE users ADD value_deflt_relation");

		$rqt = "CREATE TABLE entrepots_localisations (
			   loc_id int(11) NOT NULL auto_increment,
			   loc_code varchar(255) NOT NULL default '',
			   loc_libelle varchar(255) NOT NULL default '',
			   loc_visible tinyint(1) UNSIGNED NOT NULL default 1,
			   PRIMARY KEY  (loc_id),
			   UNIQUE KEY loc_code (loc_code)
				) " ;
		echo traite_rqt($rqt,"CREATE TABLE entrepots_localisations ");

		$rqt = "update parametres set comment_param=concat(comment_param,' \n 2: n\'afficher l\'onglet empr que lorsque l\'utilisateur est authentifié (et dans ce cas le clic sur l\'onglet mène vers empr.php)') where type_param='opac' and sstype_param='show_onglet_empr' and comment_param not like '%ne vers empr.php)%' " ;
		echo traite_rqt($rqt,"update parametres set comment_param=... where type_param='opac' and sstype_param='show_onglet_empr' ") ;

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='empr_code_info'"))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param,comment_param ,section_param ,gestion)
			VALUES ('opac', 'empr_code_info', '','Code HTML affiché au dessus des boutons dans la fiche emprunteur.', 'a_general', '0')";
			echo traite_rqt($rqt,"insert opac_empr_code_info into parametres") ;
		}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='term_search_height_bottom'"))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param,comment_param ,section_param ,gestion)
			VALUES ('opac', 'term_search_height_bottom', '120','Hauteur de la partie supérieure de la frame de recherche par termes (en px)', 'c_recherche', '0')";
			echo traite_rqt($rqt,"insert opac_term_search_height_bottom into parametres") ;
		}

		$rqt = "ALTER TABLE docs_location DROP logosmall" ;
		echo traite_rqt($rqt,"ALTER TABLE docs_location DROP logosmall") ;
		
		$rqt = "ALTER TABLE empr_caddie_content DROP INDEX empr_caddie_id" ;
		echo traite_rqt($rqt,"ALTER TABLE empr_caddie_content DROP INDEX empr_caddie_id") ;
		$rqt = "ALTER TABLE empr_caddie_content ADD INDEX object_id (object_id)" ;
		echo traite_rqt($rqt,"ALTER TABLE empr_caddie_content ADD INDEX object_id (object_id)") ;
		
		$rqt = "ALTER TABLE notices change lien lien text not null default '' " ;
		echo traite_rqt($rqt,"ALTER TABLE notices change lien TEXT") ;
		
		$rqt = "CREATE TABLE infopages (
				id_infopage INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
				content_infopage BLOB NOT NULL default '',
				title_infopage VARCHAR( 255 ) NOT NULL default '' ,
				valid_infopage TINYINT( 1 ) NOT NULL DEFAULT '1',
				PRIMARY KEY ( id_infopage )
				)  " ;
		echo traite_rqt($rqt,"CREATE TABLE infopages ") ;
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='rfid_library_code'"))==0){
			$rqt = "INSERT INTO parametres ( type_param, sstype_param, valeur_param,comment_param, section_param, gestion ) 
			VALUES ( 'pmb','rfid_library_code', '' , 'Code numérique d\'identification de la bibliothèque propriétaire des exemplaires (10 caractères)', '', '0')";
			echo traite_rqt($rqt,"insert pmb_rfid_library_code into parametres") ;
		}

		// $opac_show_infopages_id
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_infopages_id' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'opac', 'show_infopages_id', '', 'Id des infopages à afficher sous la recherche simple, séparées par des virgules.', 'f_modules', 0) ";
			echo traite_rqt($rqt,"insert opac_show_infopages_id=0 into parametres") ;
		}
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.67");
		break;

	case "v4.67": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		
		$rqt = "ALTER TABLE rss_flux ADD export_court_flux TINYINT(1) UNSIGNED NOT NULL DEFAULT 0" ;
		echo traite_rqt($rqt,"ALTER TABLE rss_flux ADD export_court_flux") ;

		$rqt = "ALTER TABLE noeuds ADD path TEXT NOT NULL" ;
		echo traite_rqt($rqt,"ALTER TABLE noeuds ADD path") ;

		$rqt = "ALTER TABLE noeuds DROP INDEX key_path" ;
		echo traite_rqt($rqt,"ALTER TABLE noeuds DROP INDEX key_path") ;
		$rqt = "ALTER TABLE noeuds ADD INDEX key_path ( path ( 1000 ) )" ;
		echo traite_rqt($rqt,"ALTER TABLE noeuds ADD INDEX key_path") ;

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'thesaurus' and sstype_param='auto_postage_montant' "))==0) {
			$rqt = "INSERT INTO parametres ( type_param, sstype_param, valeur_param,comment_param, section_param, gestion) 
				VALUES ( 'thesaurus','auto_postage_montant', '0', 'Activer la recherche des notices des catégories mères ? \n  0 non, \n 1 oui', 'i_categories', 0)" ;
			echo traite_rqt($rqt,"insert into parameters thesaurus_auto_postage_montant") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'thesaurus' and sstype_param='auto_postage_descendant' "))==0) {
			$rqt = "INSERT INTO parametres ( type_param, sstype_param, valeur_param,comment_param, section_param, gestion) 
				VALUES ( 'thesaurus','auto_postage_descendant', '0', 'Activer la recherche des notices des catégories filles. \n 0 non, \n 1 oui', 'i_categories', 0)" ;
			echo traite_rqt($rqt,"insert into parameters thesaurus_auto_postage_descendant") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'thesaurus' and sstype_param='auto_postage_nb_descendant' "))==0) {
			$rqt = "INSERT INTO parametres ( type_param, sstype_param, valeur_param,comment_param, section_param, gestion) 
				VALUES ( 'thesaurus','auto_postage_nb_descendant', '0', 'Nombre de niveaux de recherche de notices dans les catégories filles. \n *: illimité, \n n: nombre de niveaux', 'i_categories', 0)" ;
			echo traite_rqt($rqt,"insert into parameters thesaurus_auto_postage_nb_descendant") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'thesaurus' and sstype_param='auto_postage_nb_montant' "))==0) {
			$rqt = "INSERT INTO parametres ( type_param, sstype_param, valeur_param,comment_param, section_param, gestion) 
				VALUES ( 'thesaurus','auto_postage_nb_montant', '0', 'Nombre de niveaux de recherche de notices dans les catégories mères. \n *: illimité, \n n: nombre de niveaux', 'i_categories', 0)" ;
			echo traite_rqt($rqt,"insert into parameters thesaurus_auto_postage_nb_montant") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'thesaurus' and sstype_param='auto_postage_etendre_recherche' "))==0) {
			$rqt = "INSERT INTO parametres ( type_param, sstype_param, valeur_param,comment_param, section_param, gestion) 
				VALUES ( 'thesaurus', 'auto_postage_etendre_recherche', '0', 'Proposer la possibilité d\'étendre la recherche dans les catégories mères ou filles. \n 0: non, \n 1: Exclusivement dans les catégories filles, \n 2: Etendre dans les catégories mères et filles, \n 3: Exclusivement dans les catégories mères. ', 'i_categories', 0)" ;
			echo traite_rqt($rqt,"insert into parameters thesaurus_auto_postage_etendre_recherche") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='auto_postage_montant' "))==0) {
		$rqt = "INSERT INTO parametres ( type_param, sstype_param, valeur_param,comment_param, section_param, gestion) 
				VALUES ( 'opac','auto_postage_montant', '0', 'Activer la recherche des notices des catégories mères. \n 0 non, \n 1 oui', 'i_categories', 0)" ;
		echo traite_rqt($rqt,"insert into parameters opac_auto_postage_montant") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='auto_postage_descendant' "))==0) {
			$rqt = "INSERT INTO parametres ( type_param, sstype_param, valeur_param,comment_param, section_param, gestion) 
				VALUES ( 'opac', 'auto_postage_descendant', '0', 'Activer la recherche des notices des catégories filles. \n 0 non, \n 1 oui', 'i_categories', 0)" ;
			echo traite_rqt($rqt,"insert into parameters opac_auto_postage_descendant") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='auto_postage_nb_descendant' "))==0) {
			$rqt = "INSERT INTO parametres ( type_param, sstype_param, valeur_param,comment_param, section_param, gestion)
				VALUES ( 'opac', 'auto_postage_nb_descendant', '0', 'Nombre de niveaux de recherche de notices dans les catégories filles. \n *: illimité, \n n: nombre de niveaux', 'i_categories', 0)" ;
			echo traite_rqt($rqt,"insert into parameters opac_auto_postage_nb_descendant") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='auto_postage_nb_montant' "))==0) {
			$rqt = "INSERT INTO parametres ( type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
				VALUES ( 'opac', 'auto_postage_nb_montant', '0', 'Nombre de niveaux de recherche de notices dans les catégories mères. \n *: illimité, \n n: nombre de niveaux', 'i_categories', 0)" ;
			echo traite_rqt($rqt,"insert into parameters opac_auto_postage_nb_montant") ;
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='auto_postage_etendre_recherche' "))==0) {
			$rqt = "INSERT INTO parametres ( type_param, sstype_param, valeur_param,comment_param, section_param, gestion) 
				VALUES ( 'opac','auto_postage_etendre_recherche', '0', 'Proposer la possibilité d\'étendre la recherche dans les catégories mères ou filles. \n 0: non, \n 1: Exclusivement dans les catégories filles, \n 2: Etendre dans les catégories mères et filles, \n 3: Exclusivement dans les catégories mères. ', 'i_categories', 0)" ;
			echo traite_rqt($rqt,"insert into parameters opac_auto_postage_etendre_recherche") ;
		}
		$rqt = "ALTER TABLE users ADD param_allloc INT(1) UNSIGNED DEFAULT '0' NOT NULL ";
		echo traite_rqt($rqt, "add param_allloc in table users");
		
		//parametre general d'activation de la gestion droits acces 
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'gestion_acces' and sstype_param='active' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'gestion_acces', 'active', '0', 'Module gestion des droits d\'accès activé ?\n 0 : Non.\n 1 : Oui.', '',0) ";
			echo traite_rqt($rqt, "insert gestion_acces=0 into parameters");
		}

		//parametres activation gestion droits acces utilisateurs - notices 
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'gestion_acces' and sstype_param='user_notice' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'gestion_acces', 'user_notice', '0', 'Gestion des droits d\'accès des utilisateurs aux notices \n 0 : Non.\n 1 : Oui.', '',0) ";
			echo traite_rqt($rqt, "insert gestion_acces_user_notice=0 into parameters");
		}

		//table profils
		$rqt = "CREATE TABLE acces_profiles (
				prf_id INT(2) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				prf_type INT(1) UNSIGNED NOT NULL DEFAULT '1',
				prf_name VARCHAR(255) NOT NULL,
				prf_rule BLOB NOT NULL,
				prf_hrule TEXT NOT NULL,
				prf_used  INT(2) UNSIGNED NOT NULL DEFAULT '0', 
				dom_num INT(2) UNSIGNED NOT NULL DEFAULT '0',
				INDEX prf_type (prf_type), 
				INDEX prf_name (prf_name),
				INDEX dom_num (dom_num)
				)";
		echo traite_rqt($rqt, "CREATE TABLE acces_profiles");

		//table droits
		$rqt = "CREATE TABLE acces_rights (
 				dom_num int(2) unsigned NOT NULL default '0',
  				usr_prf_num int(2) unsigned NOT NULL default '0',
  				res_prf_num int(2) unsigned NOT NULL default '0',
  				dom_rights varbinary(1) NOT NULL,
  				PRIMARY KEY  (dom_num, usr_prf_num, res_prf_num),
				KEY dom_num (dom_num), 
  				KEY usr_prf_num (usr_prf_num),
  				KEY res_prf_num (res_prf_num)
				)";
		echo traite_rqt($rqt, "CREATE TABLE acces_rights");
	

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.68");
		break;

	case "v4.68": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE docs_location ADD num_infopage INT( 6 ) UNSIGNED NOT NULL DEFAULT 0";
		echo traite_rqt($rqt, "ALTER TABLE docs_location ADD num_infopage");

		$rqt = "ALTER TABLE users CHANGE explr_invisible explr_invisible varchar( 255 ) default '0' ";
		echo traite_rqt($rqt,"ALTER TABLE users CHANGE explr_invisible default '0'  ");
		
		$rqt = "ALTER TABLE users CHANGE explr_visible_mod explr_visible_mod varchar( 255 ) default '0'";
		echo traite_rqt($rqt,"ALTER TABLE users CHANGE explr_visible_mod default '0'");
		
		$rqt = "ALTER TABLE users CHANGE explr_visible_unmod explr_visible_unmod varchar( 255 ) default '0'";
		echo traite_rqt($rqt,"ALTER TABLE users CHANGE explr_visible_unmod default '0'");
		
		$rqt = "UPDATE users set explr_invisible='0' where explr_invisible='' ";
		echo traite_rqt($rqt,"UPDATE users set explr_invisible='0' where explr_invisible='' ");
		
		$rqt = "UPDATE users set explr_visible_mod='0' where explr_visible_mod=''";
		echo traite_rqt($rqt,"UPDATE users set explr_visible_mod='0' where explr_visible_mod=''");
		
		$rqt = "UPDATE users set explr_visible_unmod='0' where explr_visible_unmod=''";
		echo traite_rqt($rqt,"UPDATE users set explr_visible_unmod='0' where explr_visible_unmod=''");
		
		$rqt = "ALTER TABLE sub_collections ADD subcollection_web TEXT NOT NULL default '' ";
		echo traite_rqt($rqt,"ALTER TABLE sub_collections ADD subcollection_web TEXT ");
				
		$rqt = "ALTER TABLE collections CHANGE collection_web collection_web TEXT NOT NULL default '' ";
		echo traite_rqt($rqt,"ALTER TABLE collections CHANGE collection_web TEXT ");

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='abt_end_delay' "))==0){
			$rqt = "INSERT INTO parametres ( type_param, sstype_param, valeur_param, comment_param, section_param, gestion ) 
				VALUES ( 'pmb', 'abt_end_delay', '30' , 'Délais d\'alerte d\'avertissement des abonnements arrivant à échéance (en jours)', '', '0')";
			echo traite_rqt($rqt, "insert pmb_abt_end_delay=30 into parameters");
		}
		

		$rqt = "ALTER TABLE pret_archive ADD arc_niveau_relance INT( 1 ) UNSIGNED DEFAULT 0 ";
		echo traite_rqt($rqt,"ALTER TABLE pret_archive ADD arc_niveau_relance");
		
		$rqt = "ALTER TABLE pret_archive ADD arc_date_relance DATE not NULL default '0000-00-00'";
		echo traite_rqt($rqt,"ALTER TABLE pret_archive ADD arc_date_relance DATE");
		
		$rqt = "ALTER TABLE pret_archive ADD arc_printed INT( 1 ) UNSIGNED DEFAULT 0";
		echo traite_rqt($rqt,"ALTER TABLE pret_archive ADD arc_printed INT");
		
		$rqt = "ALTER TABLE pret_archive ADD arc_cpt_prolongation INT( 1 ) UNSIGNED DEFAULT 0 ";
		echo traite_rqt($rqt,"ALTER TABLE pret_archive ADD arc_cpt_prolongation INT");
		
		$rqt = "ALTER TABLE authors ADD author_lieu VARCHAR(255) NOT NULL default ''";
		echo traite_rqt($rqt,"ALTER TABLE authors ADD author_lieu VARCHAR(255) ");

		$rqt = "ALTER TABLE authors ADD author_ville VARCHAR(255) NOT NULL default ''";
		echo traite_rqt($rqt,"ALTER TABLE authors ADD author_ville VARCHAR(255) ");

		$rqt = "ALTER TABLE authors ADD author_pays VARCHAR(255) NOT NULL default ''";
		echo traite_rqt($rqt,"ALTER TABLE authors ADD author_pays VARCHAR(255) ");

		$rqt = "ALTER TABLE authors ADD author_subdivision VARCHAR(255) NOT NULL default ''";
		echo traite_rqt($rqt,"ALTER TABLE authors ADD author_subdivision VARCHAR(255) ");

		$rqt = "ALTER TABLE authors ADD author_numero VARCHAR(50) NOT NULL default ''";
		echo traite_rqt($rqt,"ALTER TABLE authors ADD author_numero VARCHAR(255) ");

		$rqt = "ALTER TABLE authors CHANGE author_type author_type ENUM( '70', '71', '72' ) NOT NULL DEFAULT '70' ";
		echo traite_rqt($rqt,"ALTER TABLE authors CHANGE author_type author_type ENUM( '70', '71', '72' ) ");
		
		//Table de stockage des groupes d'utilisateurs
		$rqt = "CREATE TABLE users_groups (
					grp_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
					grp_name VARCHAR(255) NOT NULL default '',
					PRIMARY KEY (grp_id),
					KEY i_users_groups_grp_name(grp_name))";
		echo traite_rqt($rqt, "CREATE TABLE users_groups");

		//Lien avec la table users
		$rqt = "ALTER TABLE users ADD grp_num INT UNSIGNED DEFAULT 0 ";
		echo traite_rqt($rqt, "ALTER TABLE users ADD grp_num");

		// export des champs persos de notices ?
		$rqt = "ALTER TABLE notices_custom ADD export INT( 1 ) UNSIGNED NOT NULL DEFAULT 0";
		echo traite_rqt($rqt, "ALTER TABLE notices_custom ADD export");

		// export des champs persos d'exemplaires' ?
		$rqt = "ALTER TABLE expl_custom ADD export INT( 1 ) UNSIGNED NOT NULL DEFAULT 0";
		echo traite_rqt($rqt, "ALTER TABLE expl_custom ADD export");

		// export des champs persos d'exemplaires' ?
		$rqt = "ALTER TABLE empr_custom ADD export INT( 1 ) UNSIGNED NOT NULL DEFAULT 0";
		echo traite_rqt($rqt, "ALTER TABLE empr_custom ADD export");

		// Ajout Autorités Titres uniformes
		$rqt = "CREATE TABLE titres_uniformes (
	        tu_id INT( 9 ) unsigned NOT NULL AUTO_INCREMENT,
	        tu_name VARCHAR( 255 ) DEFAULT '' NOT NULL,        
	        tu_tonalite VARCHAR( 255 ) DEFAULT '' NOT NULL ,
	        tu_comment TEXT NOT NULL ,
	        index_tu TEXT NOT NULL ,
	        PRIMARY KEY ( tu_id )
		)";
		echo traite_rqt($rqt, "CREATE TABLE titres_uniformes");	
		
		$rqt = "CREATE TABLE tu_distrib (
	        distrib_num_tu INT( 9 ) unsigned NOT NULL default 0,
	        distrib_name VARCHAR( 255 ) DEFAULT '' NOT NULL,
	        distrib_ordre smallint(5) unsigned NOT NULL default 0,
	        PRIMARY KEY (distrib_num_tu, distrib_ordre)	
		)";
		echo traite_rqt($rqt, "CREATE TABLE tu_distrib");	
		
		$rqt = "CREATE TABLE tu_ref (
	        ref_num_tu INT( 9 ) unsigned NOT NULL default 0,
	        ref_name VARCHAR( 255 ) DEFAULT '' NOT NULL,
	        ref_ordre smallint(5) unsigned NOT NULL default 0,
	        PRIMARY KEY (ref_num_tu, ref_ordre)	
		)";
		echo traite_rqt($rqt, "CREATE TABLE tu_ref");		
		
		$rqt = "CREATE TABLE tu_subdiv (
	        subdiv_num_tu INT( 9 ) unsigned NOT NULL default 0,
	        subdiv_name VARCHAR( 255 ) DEFAULT '' NOT NULL,
	        subdiv_ordre smallint(5) unsigned NOT NULL default 0,
	        PRIMARY KEY (subdiv_num_tu, subdiv_ordre)		
		)";
		echo traite_rqt($rqt, "CREATE TABLE tu_subdiv");		
		
		$rqt = "CREATE TABLE notices_titres_uniformes (
	        ntu_num_notice INT( 9 ) unsigned NOT NULL default 0,
	        ntu_num_tu INT( 9 ) unsigned NOT NULL default 0,
	        ntu_titre VARCHAR( 255 ) DEFAULT '' NOT NULL,
	        ntu_date VARCHAR( 255 ) DEFAULT '' NOT NULL,
	        ntu_sous_vedette VARCHAR( 255 ) DEFAULT '' NOT NULL ,
	        ntu_langue VARCHAR( 255 ) DEFAULT '' NOT NULL ,
	        ntu_version VARCHAR( 255 ) DEFAULT '' NOT NULL ,
	        ntu_mention VARCHAR( 255 ) DEFAULT '' NOT NULL ,
	        ntu_ordre smallint(5) unsigned NOT NULL default 0,
	        PRIMARY KEY (ntu_num_notice, ntu_num_tu)	
		)";
		echo traite_rqt($rqt, "CREATE TABLE notices_titres_uniformes");						
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='set_time_limit' "))==0){
			$rqt = "INSERT INTO parametres ( type_param, sstype_param, valeur_param, comment_param, section_param, gestion ) 
				VALUES ( 'pmb', 'set_time_limit', '1200' , 'max_execution_time de certaines opérations (export d\'actions personnalisées, envoi DSI, export, etc.) \nAttention, peut être sans effet si l\'hébergement ne l\'autorise pas (free.fr par exemple)\n 0 : illimité (déconseillé) \n ###: ### secondes', '', '0')";
			echo traite_rqt($rqt, "insert pmb_set_time_limit=1200 into parameters");
		}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='expl_list_display_comments' "))==0){
			$rqt = "INSERT INTO parametres ( type_param, sstype_param, valeur_param, comment_param, section_param, gestion ) 
				VALUES ( 'pmb', 'expl_list_display_comments', '0' , 'Afficher les commentaires des exemplaires en liste d\'exemplaires : \n 0 : non \n 1 : commentaire bloquant \n 2 : commentaire non bloquant \n 3 : les deux commentaires', '', '0')";
			echo traite_rqt($rqt, "insert pmb_expl_list_display_comments=0 into parameters");
		}

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.69");
		break;

	case "v4.69": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt = "ALTER TABLE lenders CHANGE lender_libelle lender_libelle VARCHAR(255) NOT NULL default ''";
		echo traite_rqt($rqt, "ALTER TABLE lenders CHANGE lender_libelle VARCHAR(255)");		
		
		$rqt = "alter table audit drop index type_obj";
		echo traite_rqt($rqt, "ALTER TABLE audit DROP INDEX");
		$rqt = "alter table audit drop index object_id";
		echo traite_rqt($rqt, "ALTER TABLE audit DROP INDEX");
		$rqt = "alter table audit drop index user_id";
		echo traite_rqt($rqt, "ALTER TABLE audit DROP INDEX");
		$rqt = "alter table audit drop index type_modif";
		echo traite_rqt($rqt, "ALTER TABLE audit DROP INDEX");
		$rqt = "alter table audit add index type_obj (type_obj)";
		echo traite_rqt($rqt, "ALTER TABLE audit ADD INDEX");
		$rqt = "alter table audit add index object_id (object_id)";
		echo traite_rqt($rqt, "ALTER TABLE audit ADD INDEX");
		$rqt = "alter table audit add index user_id (user_id)";
		echo traite_rqt($rqt, "ALTER TABLE audit ADD INDEX");
		$rqt = "alter table audit add index type_modif (type_modif)";
		echo traite_rqt($rqt, "ALTER TABLE audit ADD INDEX");
		
		//Ajout du paramètre insert pmb_confirm_delete_from_caddie (voir mantis://0000588)
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='confirm_delete_from_caddie' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES
					('pmb', 'confirm_delete_from_caddie', '0', 'Action à réaliser lors de la suppression d''une notice située dans un panier. \r\n0 : Interdire \r\n1 : Supprimer sans confirmation \r\n2 : Demander une confirmation de suppression ', '', 0)";
			echo traite_rqt($rqt, "insert pmb_confirm_delete_from_caddie=0 into parameters");
		}

		$rqt = "ALTER TABLE notices ADD date_parution DATE NOT NULL DEFAULT '0000-00-00'";
		echo traite_rqt($rqt, "ALTER TABLE notices ADD date_parution");
		$rqt = "alter table notices drop index i_date_parution";
		echo traite_rqt($rqt, "ALTER TABLE notices DROP INDEX i_date_parution");
		$rqt = "ALTER TABLE notices ADD INDEX i_date_parution (date_parution) ;";
		echo traite_rqt($rqt, "ALTER TABLE notices ADD INDEX i_date_parution");
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='flux_rss_notices_order' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param) 
					VALUES (0, 'opac', 'flux_rss_notices_order', ' index_serie, tnvol, index_sew ', 'Ordre d\'affichage des notices dans les flux sortants dans l\'opac \n  index_serie, tnvol, index_sew : tri par titre de série et titre \n rand()  : aléatoire \n notice_id desc par ordre décroissant de création de notice', 'l_dsi')";
			echo traite_rqt($rqt,"insert opac_flux_rss_notices_order=' index_serie, tnvol, index_sew ' into parametres");
		}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='modules_search_titre_uniforme' "))==0) {
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
				VALUES (0, 'opac', 'modules_search_titre_uniforme', '1', 'Recherche dans les titres uniformes : \n 0 : interdite, \n 1 : autorisée, \n 2 : autorisée et validée par défaut', 'c_recherche', 0) ";
			echo traite_rqt($rqt, "insert opac_modules_search_titre_uniforme into parameters");
		}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='congres_affichage_mode' "))==0) {
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param,valeur_param, comment_param, section_param, gestion) 
				VALUES (0, 'opac', 'congres_affichage_mode', '0', 'Mode d\'affichage des congrès: \n 0 : Comme pour les auteurs, \n 1 : ajout d\'un navigateur de congrès', 'd_aff_recherche', 0) ";
			echo traite_rqt($rqt, "insert opac_congres_affichage_mode into parameters");
		}
			
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_suggest_notice' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'opac','show_suggest_notice','0','Afficher le lien de proposition de suggestion sur une notice existante.\n 0 : Non.\n 1 : Oui, avec authentification.\n 2 : Oui, sans authentification.','f_modules',0)" ;
			echo traite_rqt($rqt,"insert opac_show_suggest_notice into parametres") ;
		}
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.70");
		break;

	case "v4.70": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		//ajout statut/flag exemplaire numerique
		$rqt = "ALTER TABLE explnum ADD explnum_statut INT(5) UNSIGNED NOT NULL DEFAULT 0 ";
		echo traite_rqt($rqt, "alter table explnum add explnum_statut");

		//ajout parametre pour gerer statut spécifique sur les exemplaires numeriques
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='explnum_statut' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) 
						VALUES ('pmb','explnum_statut', '0', 'Utiliser un statut sur les documents numériques \n 0: non \n 1: oui', '', '0')" ;
			echo traite_rqt($rqt,"insert pmb_explnum_statut=0 into parametres");
		}
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.71");
		break;

	case "v4.71": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='modules_search_titre_uniforme' "))==0) {
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
				VALUES (0, 'opac', 'modules_search_titre_uniforme', '1', 'Recherche dans les titres uniformes : \n 0 : interdite, \n 1 : autorisée, \n 2 : autorisée et validée par défaut', 'c_recherche', 0) ";
			echo traite_rqt($rqt, "insert opac_modules_search_titre_uniforme into parameters");
		}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='congres_affichage_mode' "))==0) {
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param,valeur_param, comment_param, section_param, gestion) 
				VALUES (0, 'opac', 'congres_affichage_mode', '0', 'Mode d\'affichage des congrès: \n 0 : Comme pour les auteurs, \n 1 : ajout d\'un navigateur de congrès', 'd_aff_recherche', 0) ";
			echo traite_rqt($rqt, "insert opac_congres_affichage_mode into parameters");
		}
			
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_empty_items_block' "))==0) {
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param,valeur_param, comment_param, section_param, gestion) 
				VALUES (0, 'opac', 'show_empty_items_block', '1', 'Afficher le bloc exemplaires même si aucun exemplaire sur la notice ? : \n 0 : Non, \n 1 : Oui', 'd_aff_recherche', 0) ";
			echo traite_rqt($rqt, "insert opac_show_empty_items_block=1 into parameters");
		}

		$rqt = "ALTER TABLE avis DROP INDEX avis_num_notice";
		echo traite_rqt($rqt, "ALTER TABLE avis DROP INDEX");
		$rqt = "ALTER TABLE avis ADD INDEX avis_num_notice (num_notice)";
		echo traite_rqt($rqt, "ALTER TABLE avis ADD INDEX avis_num_notice");
		 
		$rqt = "ALTER TABLE avis DROP INDEX avis_num_empr";
		echo traite_rqt($rqt, "ALTER TABLE avis DROP INDEX");
		$rqt = "ALTER TABLE avis ADD INDEX avis_num_empr (num_empr)";
		echo traite_rqt($rqt, "ALTER TABLE avis ADD INDEX avis_num_empr");

		$rqt = "ALTER TABLE avis DROP INDEX avis_note";
		echo traite_rqt($rqt, "ALTER TABLE avis DROP INDEX");
		$rqt = "ALTER TABLE avis ADD INDEX avis_note (note)";
		echo traite_rqt($rqt, "ALTER TABLE avis ADD INDEX avis_note"); 
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.72");
		break;

	case "v4.72": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+

		if (!substr($opac_show_languages,2)) {
			// si la liste des langues possibles n'est pas affichée elle doit quand même contenir la langue par défaut.
			$rqt="update parametres set valeur_param='".substr($opac_show_languages,0,1)." ".$opac_default_lang."' where type_param='opac' and sstype_param='show_languages'" ;
			echo traite_rqt($rqt, "Update opac_show_languages, opac_default_lang must be set even if opac_show_languages is set to 0");
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='printer_ticket_script' "))==0) {
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param,valeur_param, comment_param, section_param, gestion) 
					VALUES (NULL,'pmb', 'printer_ticket_script', '', 'Script permettant de personaliser l\'impression du ticket de prêt. Le répertoire du script est à paramétrer à partir de la racine de PMB.\nSi vide PMB utilise ./circ/ticket-pret.inc.php', '', '0')";
			echo traite_rqt($rqt, "insert pmb_printer_ticket_script='' into parameters");
		}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='curl_proxy' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) 
					VALUES (0, 'opac', 'curl_proxy', '', 'Paramétrage de proxy de cURL, vide si aucun proxy, sinon\nhost,port,user,password;2nd_host et ainsi de suite','a_general')";
			echo traite_rqt($rqt,"insert opac_curl_proxy='' into parametres");
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='curl_proxy' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) 
					VALUES (0, 'pmb', 'curl_proxy', '', 'Paramétrage de proxy de cURL, vide si aucun proxy, sinon\nhost,port,user,password;2nd_host et ainsi de suite','')";
			echo traite_rqt($rqt,"insert pmb_curl_proxy='' into parametres");
		}
		
		//suppression parametre impression commentaires devis obsolete
		$rqt = "delete from parametres where type_param='acquisition' and sstype_param='pdfdev_comment' " ;
		echo traite_rqt($rqt,"delete acquisition_pdfdev_comment from parametres") ;

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='latest_order' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) 
					VALUES (0, 'pmb', 'latest_order', 'notice_id desc', 'Tri des dernières notices ? \n notice_id desc : par id de notice décroissant: idéal mais peut être problématique après une migration ou un import \n create_date desc: par la colonne date de création.','')";
			echo traite_rqt($rqt,"insert pmb_latest_order='notice_id desc' into parametres");
		}

		// sert à rendre facultatif les champs perso normalement obligatoires sur la création des notices de bulletin
		$rqt = "ALTER TABLE notices_custom ADD exclusion_obligatoire INT(1) UNSIGNED NOT NULL DEFAULT 0" ;
		echo traite_rqt($rqt,"ALTER TABLE notices_custom ADD exclusion_obligatoire ") ;
		// les tables notices_custom, empr_custom et expl_custom sont gérées par la même classe, donc champs identiques :
		$rqt = "ALTER TABLE expl_custom ADD exclusion_obligatoire INT(1) UNSIGNED NOT NULL DEFAULT 0" ;
		echo traite_rqt($rqt,"ALTER TABLE expl_custom ADD exclusion_obligatoire ") ;
		$rqt = "ALTER TABLE empr_custom ADD exclusion_obligatoire INT(1) UNSIGNED NOT NULL DEFAULT 0" ;
		echo traite_rqt($rqt,"ALTER TABLE empr_custom ADD exclusion_obligatoire ") ;
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param='opac' and sstype_param='password_forgotten_show' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) 
					VALUES ('opac','password_forgotten_show', '1', 'Afficher le lien  \"Mot de passe oublié ?\" \n 0: Non \n 1: Oui', 'f_modules', '0')";
			echo traite_rqt($rqt,"insert opac_password_forgotten_show='1' into parametres");
		}

		$rqt = "update parametres set comment_param=concat(comment_param,' \n 3: invisibles') where type_param='opac' and sstype_param='recherches_pliables' and comment_param not like '% 3:%'";
		echo traite_rqt($rqt,"change opac_recherches_pliables's comment ") ;
		
		//Gestion des Etats de collections
		$rqt = "ALTER TABLE collections_state DROP PRIMARY KEY ";
		echo traite_rqt($rqt,"ALTER TABLE collections_state DROP PRIMARY KEY ") ;
		$rqt = "ALTER TABLE collections_state ADD collstate_id INT( 8 ) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST ";    
		echo traite_rqt($rqt,"ALTER TABLE collections_state ADD collstate_id") ;        
		$rqt = "ALTER TABLE collections_state 
		    ADD collstate_emplacement INT( 8 ) UNSIGNED NOT NULL DEFAULT 0,
		    ADD collstate_type INT( 8 ) UNSIGNED NOT NULL DEFAULT 0,
		    ADD collstate_origine VARCHAR( 255 ) NOT NULL default '',
		    ADD collstate_cote VARCHAR( 255 ) NOT NULL default '',
		    ADD collstate_archive INT( 8 ) UNSIGNED NOT NULL DEFAULT 0,
		    ADD collstate_statut INT( 8 ) UNSIGNED NOT NULL DEFAULT 0,
		    ADD collstate_lacune TEXT NOT NULL default '',
		    ADD collstate_note TEXT NOT NULL default '' ";        
		echo traite_rqt($rqt,"ALTER TABLE collections_state") ;        

		$rqt = "CREATE TABLE arch_emplacement (
		    archempla_id INT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		    archempla_libelle VARCHAR( 255 ) NOT NULL default '')";        
		echo traite_rqt($rqt,"CREATE TABLE arch_emplacement ") ;        

		$rqt = "CREATE TABLE arch_type (
		    archtype_id INT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		    archtype_libelle VARCHAR( 255 ) NOT NULL default '')";        
		echo traite_rqt($rqt,"CREATE TABLE arch_type") ;        

		$rqt = "CREATE TABLE arch_statut (
		    archstatut_id INT( 8 ) NOT NULL auto_increment ,
		    archstatut_gestion_libelle VARCHAR( 255 ) NOT NULL default '',
		    archstatut_opac_libelle VARCHAR( 255 ) NOT NULL ,
		    archstatut_visible_opac TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT 1,
		    archstatut_visible_opac_abon TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT 1,
		    archstatut_visible_gestion TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT 1,
		    archstatut_class_html VARCHAR( 255 ) NOT NULL default '',
		    PRIMARY KEY ( archstatut_id ))";        
		echo traite_rqt($rqt,"CREATE TABLE arch_statut") ;        

		$rqt = "ALTER TABLE notices ADD opac_visible_bulletinage TINYINT UNSIGNED NOT NULL DEFAULT 1";        
		echo traite_rqt($rqt,"ALTER TABLE notices ADD opac_visible_bulletinage") ;        

		$rqt = "CREATE TABLE collstate_custom (
		    idchamp int(10) unsigned NOT NULL auto_increment,
		    name varchar(255) NOT NULL default '',
		    titre varchar(255) not NULL default '',
		    type varchar(10) NOT NULL default 'text',
		    datatype varchar(10) NOT NULL default '',
		    options text,
		    multiple int(11) NOT NULL default 0,
		    obligatoire int(11) NOT NULL default 0,
		    ordre int(11) not NULL default 0,
		    search int(11) NOT NULL default 0,
		    export int(1) unsigned NOT NULL default 0,
		    exclusion_obligatoire int(1) unsigned NOT NULL default 0,
		    PRIMARY KEY  (idchamp))";        

		echo traite_rqt($rqt,"CREATE TABLE collstate_custom") ;        
		$rqt = "CREATE TABLE collstate_custom_lists (
		    collstate_custom_champ int(10) unsigned NOT NULL default 0,
		    collstate_custom_list_value varchar(255) NOT NULL default '',
		    collstate_custom_list_lib varchar(255) NOT NULL default '',
		    ordre int(11)  NOT NULL default 0,
		    KEY collstate_custom_champ (collstate_custom_champ),
		    KEY collstate_champ_list_value (collstate_custom_champ,collstate_custom_list_value))";        
		echo traite_rqt($rqt,"CREATE TABLE collstate_custom_lists ") ;
		
		$rqt = "CREATE TABLE collstate_custom_values (
		    collstate_custom_champ int(10) unsigned NOT NULL default 0,
		    collstate_custom_origine int(10) unsigned NOT NULL default 0,
		    collstate_custom_small_text varchar(255) default NULL,
		    collstate_custom_text text,
		    collstate_custom_integer int(11) default NULL,
		    collstate_custom_date date default NULL,
		    collstate_custom_float float default NULL,
		    KEY collstate_custom_champ (collstate_custom_champ),
		    KEY collstate_custom_origine (collstate_custom_origine) )";        
		echo traite_rqt($rqt,"CREATE TABLE collstate_custom_values") ;
		        
		$rqt = "ALTER TABLE users ADD deflt_arch_statut INT( 6 ) UNSIGNED DEFAULT 0 NOT NULL ";        
		echo traite_rqt($rqt,"ALTER TABLE users ADD deflt_arch_statut") ;        
		$rqt = "ALTER TABLE users ADD deflt_arch_emplacement INT( 6 ) UNSIGNED DEFAULT 0 NOT NULL ";        
		echo traite_rqt($rqt,"ALTER TABLE users ADD deflt_arch_emplacement ") ;        
		$rqt = "ALTER TABLE users ADD deflt_arch_type INT( 6 ) UNSIGNED DEFAULT 0 NOT NULL ";        
		echo traite_rqt($rqt,"ALTER TABLE users ADD deflt_arch_type INT( 6 )") ;        
		
        if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='aff_expl_localises' "))==0) {
            $rqt = "INSERT INTO parametres ( type_param, sstype_param, valeur_param, comment_param,section_param) 
            	VALUES ( 'opac','aff_expl_localises', '0', 'Activer l\'affichage des exemplaires localisés par onglet.\n 0 : désactivé \n 1: premier onglet affiche les exemplaires de la localisation du lecteur, le deuxieme affiche tous les exemplaires','e_aff_notice')";
            echo traite_rqt($rqt,"insert opac_aff_expl_localises=0");
        }
		
		//parametres activation gestion droits acces emprunteurs - notices 
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'gestion_acces' and sstype_param='empr_notice' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
				VALUES (0, 'gestion_acces', 'empr_notice', '0', 'Gestion des droits d\'accès des emprunteurs aux notices \n 0 : Non.\n 1 : Oui.', '',0) ";
			echo traite_rqt($rqt, "insert gestion_acces_empr_notice=0 into parameters");
		}
		
		@set_time_limit(0);
		//modification structure table de stockage des droits ressources/utilisateurs
		$rqt = "describe acces_rights dom_rights ";
		$res = mysql_query($rqt, $dbh);
		$typ = mysql_result($res,0,1);
		if ($typ && substr($typ,0,3)!='int') {
			$rqt= "create temporary table acces_rights_tmp as select * from acces_rights ";
			echo traite_rqt($rqt,"create temporary table acces_rights_tmp") ;
			$rqt= "alter table acces_rights modify dom_rights int(2) unsigned not null default 0 ";
			echo traite_rqt($rqt,"alter table acces_rights modify dom_rights to integer") ;
			$rqt= "update acces_rights set dom_rights = (select conv(reverse(lpad(conv(ord(dom_rights),10,2),8,'0')),2,10) from acces_rights_tmp where 
			acces_rights_tmp.dom_num=acces_rights.dom_num and 
			acces_rights_tmp.usr_prf_num=acces_rights.usr_prf_num and acces_rights_tmp.res_prf_num=acces_rights.res_prf_num) ";
			echo traite_rqt($rqt,"update acces_rights") ;
		}
		
		//modification structure table de stockage des droits ressources/utilisateurs (domaine user_notice)
		$rqt = "describe acces_res_1 res_rights ";
		$res = mysql_query($rqt, $dbh);
		if(!mysql_errno()) {
			$typ = mysql_result($res,0,1);
			if ($typ && substr($typ,0,3)!='int') {
				$rqt= "create temporary table acces_res_1_tmp as select * from acces_res_1 ";
				echo traite_rqt($rqt,"create temporary table acces_res_1_tmp");
				$rqt = "update acces_res_1_tmp set res_mask=res_rights where res_mask='' ";
				echo traite_rqt($rqt, "update res_mask in table acces_res_1_tmp");
				flush();
				
				$rqt= "truncate table acces_res_1 ";
				echo traite_rqt($rqt,"truncate table acces_res_1");
				$rqt= "alter table acces_res_1 change prf_num res_prf_num int(2) unsigned not null default 0 ";
				echo traite_rqt($rqt,"alter table acces_res_1 modify prf_num res_prf_num") ;			
				$rqt= "alter table acces_res_1 add usr_prf_num int(2) unsigned not null default 0 after res_prf_num";
				echo traite_rqt($rqt,"alter table acces_res_1 add usr_prf_num");
				$rqt= "alter table acces_res_1 modify res_rights int(2) unsigned not null default 0, modify res_mask int(2) unsigned not null default 0 ";
				echo traite_rqt($rqt,"alter table acces_res_1 modify res_rights, res_mask to integer") ;
				$rqt= "alter table acces_res_1 drop primary key, drop index res_rights, drop index res_mask ";
				echo traite_rqt($rqt,"alter table acces_res_1 drop keys ");
				$rqt = "alter table acces_res_1 add primary key (res_num, usr_prf_num) ";
				echo traite_rqt($rqt,"alter table acces_res_1 add primary key ") ;
				flush();
				$rqt= "SELECT distinct usr_prf_num FROM acces_rights where dom_num=1 order by 1 ";
				$res= mysql_query($rqt, $dbh);
				$pos=1;
				while(($row=mysql_fetch_object($res))) {
					$rqt = "insert into acces_res_1 (select res_num, prf_num, ".$row->usr_prf_num.", conv(reverse(lpad(conv(ord(mid(res_rights,".$pos.",1)),10,2),8,'0')),2,10) , ( (conv(reverse(lpad(conv(ord(mid(res_rights,".$pos.",1)),10,2),8,'0')),2,10)) xor (conv(reverse(lpad(conv(ord(mid(res_mask ,".$pos.",1)),10,2),8,'0')),2,10)) ) from acces_res_1_tmp ) ";
					echo traite_rqt($rqt,"update acces_res_1 values for user profile=".($pos)) ;
					flush();
					$pos++;
				}
			}
		}

		// $opac_show_infopages_id_top
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_infopages_id_top' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'opac', 'show_infopages_id_top', '', 'Id des infopages à afficher SUR la recherche simple, séparées par des virgules.', 'f_modules', 0) ";
			echo traite_rqt($rqt,"insert opac_show_infopages_id_top=0 into parametres") ;
		}
		
		// $opac_show_search_title
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_search_title' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion)
					VALUES (0, 'opac', 'show_search_title', '0', 'Afficher le titre du bloc de recherche : \n 0 : Non, \n 1 : Oui', 'f_modules', 0) ";
			echo traite_rqt($rqt,"insert opac_show_search_title=0 into parametres") ;
		}
		
		//Gestion des recherche personalisée
		$rqt = "CREATE TABLE search_perso (
		    search_id INT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		   	num_user INT( 8 ) UNSIGNED NOT NULL default 0 ,
		    search_name VARCHAR( 255 ) NOT NULL default '',
		    search_shortname VARCHAR( 50 ) NOT NULL default '',
		    search_query text NOT NULL default '',
		    search_human text NOT NULL default '',
		    search_directlink TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT 0
		   )";    
		echo traite_rqt($rqt,"CREATE TABLE search_perso ") ;        
		//Gestion des recherche personalisée
		$rqt = "CREATE TABLE search_persopac (
		    search_id INT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		   	num_empr INT( 8 ) UNSIGNED NOT NULL default 0 ,
		    search_name VARCHAR( 255 ) NOT NULL default '',
		    search_shortname VARCHAR( 50 ) NOT NULL default '',
		    search_query text NOT NULL default '',
		    search_human text NOT NULL default '',
		    search_directlink TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT 0
		   )";    
		echo traite_rqt($rqt,"CREATE TABLE search_persopac ") ;        
		
		//Gestion de traduction des libellés
		$rqt = "CREATE TABLE translation (
		    trans_table VARCHAR( 100 ) NOT NULL default '',
		    trans_field VARCHAR( 100 ) NOT NULL default '',
		    trans_lang VARCHAR( 5 ) NOT NULL default '',
		   	trans_num INT( 8 ) UNSIGNED NOT NULL default 0 ,
		    trans_text VARCHAR( 255 ) NOT NULL default '',
		    PRIMARY KEY trans (trans_table,trans_field,trans_lang,trans_num),
		    index i_lang(trans_lang)
		   )";    
		echo traite_rqt($rqt,"CREATE TABLE translation ") ;  
		// paramètre d'activation de l'onglet 'Recherches préférées' en Opac	
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='allow_personal_search' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			VALUES (0, 'opac', 'allow_personal_search', '0', 'Activer l\'affichage de l\'onglet des recherches personalisées \n 0 : Non.\n 1 : Oui.', 'c_recherche',0) ";
			echo traite_rqt($rqt, "insert pmb_liste_trad into parameters");
		}			
		
		// Passage de int(8) en varchar du numéro d'archive des états de collections
		$rqt = " ALTER TABLE collections_state CHANGE collstate_archive collstate_archive VARCHAR( 255 ) NOT NULL default '' ";
		echo traite_rqt($rqt,"ALTER TABLE collections_state CHANGE collstate_archive ");     
		   
		$rqt = "ALTER TABLE collections_state DROP INDEX i_colls_arc";
		echo traite_rqt($rqt, "ALTER TABLE collections_state DROP INDEX i_colls_arc");
		$rqt = "ALTER TABLE collections_state ADD INDEX i_colls_arc (collstate_archive)";
		echo traite_rqt($rqt, "ALTER TABLE collections_state ADD INDEX i_colls_arc");

		$rqt = "ALTER TABLE collections_state DROP INDEX i_colls_empl";
		echo traite_rqt($rqt, "ALTER TABLE collections_state DROP INDEX i_colls_empl");
		$rqt = "ALTER TABLE collections_state ADD INDEX i_colls_empl (collstate_emplacement)";
		echo traite_rqt($rqt, "ALTER TABLE collections_state ADD INDEX i_colls_empl");

		$rqt = "ALTER TABLE collections_state DROP INDEX i_colls_type";
		echo traite_rqt($rqt, "ALTER TABLE collections_state DROP INDEX i_colls_type");
		$rqt = "ALTER TABLE collections_state ADD INDEX i_colls_type (collstate_type)";
		echo traite_rqt($rqt, "ALTER TABLE collections_state ADD INDEX i_colls_type");
		
		$rqt = "ALTER TABLE collections_state DROP INDEX i_colls_orig";
		echo traite_rqt($rqt, "ALTER TABLE collections_state DROP INDEX i_colls_orig");
		$rqt = "ALTER TABLE collections_state ADD INDEX i_colls_orig (collstate_origine)";
		echo traite_rqt($rqt, "ALTER TABLE collections_state ADD INDEX i_colls_orig");
		
		$rqt = "ALTER TABLE collections_state DROP INDEX i_colls_cote";
		echo traite_rqt($rqt, "ALTER TABLE collections_state DROP INDEX i_colls_cote");
		$rqt = "ALTER TABLE collections_state ADD INDEX i_colls_cote (collstate_cote)";
		echo traite_rqt($rqt, "ALTER TABLE collections_state ADD INDEX i_colls_cote");

		$rqt = "ALTER TABLE collections_state DROP INDEX i_colls_stat";
		echo traite_rqt($rqt, "ALTER TABLE collections_state DROP INDEX i_colls_stat");
		$rqt = "ALTER TABLE collections_state ADD INDEX i_colls_stat (collstate_statut)";
		echo traite_rqt($rqt, "ALTER TABLE collections_state ADD INDEX i_colls_stat");

		$rqt = "ALTER TABLE search_persopac ADD search_limitsearch TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT 0";
		echo traite_rqt($rqt, "ALTER TABLE search_persopac ADD search_limitsearch");
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.73");
		break;

	case "v4.73": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+

		// paramètre LDAP en OPAC seulement	
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'ldap' and sstype_param='opac_only' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			VALUES (0,'ldap','opac_only','0','Ne pas utiliser l\'authentification LDAP en gestion: \n 0: Non \n 1 : Oui, en OPAC uniquement','',0) ";
			echo traite_rqt($rqt, "insert ldap_opac_only=0 into parameters");
		}			
		
		//Opérateur de recherche pour les recherches sur plusieurs valeurs
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='multi_search_operator' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'pmb', 'multi_search_operator', 'or', 'Type d\'opérateur de recherche pour les listes avec plusieurs valeurs: \n or : pour le OU \n and : pour le ET', '', '0')";
			echo traite_rqt($rqt,"insert multi_search_operator='or' into parametres ");
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='multi_search_operator' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'opac', 'multi_search_operator', 'or', 'Type d\'opérateur de recherche pour les listes avec plusieurs valeurs: \n or : pour le OU \n and : pour le ET', 'c_recherche', '0')";
			echo traite_rqt($rqt,"insert opac_multi_search_operator='or' into parametres ");
		}
	
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.74");
		break;

	case "v4.74": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		//Ajout index Pour amélioration recherche sur les états de collection
		$rqt = "ALTER TABLE collections_state DROP INDEX i_colls_serial";
		echo traite_rqt($rqt, "ALTER TABLE collections_state DROP INDEX i_colls_serial");
		$rqt = "ALTER TABLE collections_state ADD INDEX i_colls_serial (id_serial)";
		echo traite_rqt($rqt, "ALTER TABLE collections_state ADD INDEX i_colls_serial");
 
		$rqt = "ALTER TABLE collections_state DROP INDEX i_colls_loc";
		echo traite_rqt($rqt, "ALTER TABLE collections_state DROP INDEX i_colls_loc");
		$rqt = "ALTER TABLE collections_state ADD INDEX i_colls_loc (location_id)";
		echo traite_rqt($rqt, "ALTER TABLE collections_state ADD INDEX i_colls_loc");
		
		$rqt = "update authors set author_name='' where author_name is null ";
		echo traite_rqt($rqt, "update authors set author_name='' where author_name is null ");  
		$rqt = "update authors set author_rejete='' where author_rejete is null ";
		echo traite_rqt($rqt, "update authors set author_rejete='' where author_rejete is null ");  
		$rqt = "update indexint set indexint_comment='' where indexint_comment is null ";
		echo traite_rqt($rqt, "update indexint set indexint_comment='' where indexint_comment is null ");  
		
		$rqt = "ALTER TABLE authors CHANGE author_name author_name VARCHAR( 255 ) NOT NULL default ''";
		echo traite_rqt($rqt, "ALTER TABLE authors CHANGE author_name NOT NULL default ''");  
		$rqt = "ALTER TABLE authors CHANGE author_rejete author_rejete VARCHAR( 255 ) NOT NULL default ''";
		echo traite_rqt($rqt, "ALTER TABLE authors CHANGE author_rejete NOT NULL default ''");  
		$rqt = "ALTER TABLE indexint CHANGE indexint_comment indexint_comment TEXT NOT NULL default ''";
		echo traite_rqt($rqt, "ALTER TABLE indexint CHANGE indexint_comment NOT NULL default ''");  
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.75");
		break;

	case "v4.75": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		// transfert
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'transferts' and sstype_param='pret_statut_transfert' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'transferts', 'pret_statut_transfert', '0', '1', 'Autoriser le prêt lorsque l\'exemplaire est en transfert') ";
			echo traite_rqt($rqt,"INSERT pret_statut_transfert INTO parametres") ;
		}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.76");
		break;

	case "v4.76": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		// Pour import/export des notices liées 
		//    sert à définir les différents paramètres pour l'export des notices liées en gestion
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'exportparam' and sstype_param='generer_liens' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'exportparam', 'generer_liens', '0', 'Générer les liens entre les notices pour l\'export', '', '1')";
			echo traite_rqt($rqt,"insert exportparam_generer_liens='0' into parametres ");
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'exportparam' and sstype_param='export_mere' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'exportparam', 'export_mere', '0', 'Exporter les notices liées mères', '', '1')";
			echo traite_rqt($rqt,"insert exportparam_export_mere='0' into parametres ");
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'exportparam' and sstype_param='export_fille' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'exportparam', 'export_fille', '0', 'Exporter les notices liées filles', '', '1')";
			echo traite_rqt($rqt,"insert exportparam_export_fille='0' into parametres ");
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'exportparam' and sstype_param='export_bull_link' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'exportparam', 'export_bull_link', '1', 'Exporter les liens vers les bulletins pour les notices d\'article', '', '1')";
			echo traite_rqt($rqt,"insert exportparam_export_bull_link='1' into parametres ");
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'exportparam' and sstype_param='export_perio_link' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'exportparam', 'export_perio_link', '1', 'Exporter les liens vers les périodiques pour les notices d\'article', '', '1')";
			echo traite_rqt($rqt,"insert exportparam_export_perio_link='1' into parametres ");
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'exportparam' and sstype_param='export_art_link' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'exportparam', 'export_art_link', '1', 'Exporter les liens vers les articles pour les notices de périodique', '', '1')";
			echo traite_rqt($rqt,"insert exportparam_export_art_link='1' into parametres ");
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'exportparam' and sstype_param='export_bulletinage' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'exportparam', 'export_bulletinage', '0', 'Générer le bulletinage pour les notices de périodiques', '', '1')";
			echo traite_rqt($rqt,"insert exportparam_export_bulletinage='0' into parametres ");
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'exportparam' and sstype_param='export_notice_perio_link' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'exportparam', 'export_notice_perio_link', '0', 'Exporter les notices liées de périodique', '', '1')";
			echo traite_rqt($rqt,"insert exportparam_export_notice_perio_link='0' into parametres ");
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'exportparam' and sstype_param='export_notice_art_link' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'exportparam', 'export_notice_art_link', '0', 'Exporter les notices liées d\'article', '', '1')";
			echo traite_rqt($rqt,"insert exportparam_export_notice_art_link='0' into parametres ");
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'exportparam' and sstype_param='export_notice_mere_link' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'exportparam', 'export_notice_mere_link', '0', 'Exporter les notices mères liées', '', '1')";
			echo traite_rqt($rqt,"insert exportparam_export_notice_mere_link='0' into parametres ");
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'exportparam' and sstype_param='export_notice_fille_link' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'exportparam', 'export_notice_fille_link', '0', 'Exporter les notices filles liées', '', '1')";
			echo traite_rqt($rqt,"insert exportparam_export_notice_fille_link='0' into parametres ");
		}
	
		//    sert à définir les différents paramètres pour l'export des notices liées en OPAC
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='exp_generer_liens' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'opac', 'exp_generer_liens', '0', 'Générer les liens entre les notices pour l\'export', '', '1')";
			echo traite_rqt($rqt,"insert opac_exp_generer_liens='0' into parametres ");
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='exp_export_mere' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'opac', 'exp_export_mere', '0', 'Exporter les notices liées mères', '', '1')";
			echo traite_rqt($rqt,"insert opac_exp_export_mere='0' into parametres ");
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='exp_export_fille' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'opac', 'exp_export_fille', '0', 'Exporter les notices liées filles', '', '1')";
			echo traite_rqt($rqt,"insert opac_exp_export_fille='0' into parametres ");
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='exp_export_bull_link' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'opac', 'exp_export_bull_link', '1', 'Exporter les liens vers les bulletins pour les notices d\'article', '', '1')";
			echo traite_rqt($rqt,"insert opac_exp_export_bull_link='1' into parametres ");
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='exp_export_perio_link' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'opac', 'exp_export_perio_link', '1', 'Exporter les liens vers les périodiques pour les notices d\'article', '', '1')";
			echo traite_rqt($rqt,"insert opac_exp_export_perio_link='1' into parametres ");
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='exp_export_art_link' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'opac', 'exp_export_art_link', '1', 'Exporter les liens vers les articles pour les notices de périodique', '', '1')";
			echo traite_rqt($rqt,"insert opac_exp_export_art_link='1' into parametres ");
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='exp_export_bulletinage' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'opac', 'exp_export_bulletinage', '0', 'Générer le bulletinage pour les notices de périodiques', '', '1')";
			echo traite_rqt($rqt,"insert opac_exp_export_bulletinage='0' into parametres ");
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='exp_export_notice_perio_link' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'opac', 'exp_export_notice_perio_link', '0', 'Exporter les notices liées de périodique', '', '1')";
			echo traite_rqt($rqt,"insert opac_exp_export_notice_perio_link='0' into parametres ");
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='exp_export_notice_art_link' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'opac', 'exp_export_notice_art_link', '0', 'Exporter les notices liées d\'article', '', '1')";
			echo traite_rqt($rqt,"insert opac_exp_export_notice_art_link='0' into parametres ");
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='exp_export_notice_mere_link' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'opac', 'exp_export_notice_mere_link', '0', 'Exporter les notices mères liées', '', '1')";
			echo traite_rqt($rqt,"insert opac_exp_export_notice_mere_link='0' into parametres ");
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='exp_export_notice_fille_link' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'opac', 'exp_export_notice_fille_link', '0', 'Exporter les notices filles liées', '', '1')";
			echo traite_rqt($rqt,"insert opac_exp_export_notice_fille_link='0' into parametres ");
		}
		
		//   Ajout d'un champ parametre d'export en DSI
		$rqt="ALTER TABLE bannettes ADD param_export BLOB NOT NULL DEFAULT '' ";
		echo traite_rqt($rqt,"ALTER TABLE bannettes ADD param_export ");

		//STATISTIQUEs DE L'OPAC

		//Paramètres pour les statistiques de l'OPAC
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='perio_vidage_log' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'pmb', 'perio_vidage_log', '1', 'Périodicité de vidage de la table de logs (en jours)', '', '0')";
			echo traite_rqt($rqt,"insert perio_vidage_log='1' into parametres ");
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='perio_vidage_stat' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'pmb', 'perio_vidage_stat', '1,30', 'Périodicité de vidage de la table de logs (en jours) : mode,jours \n 1,x : vider tous les x jours \n 2,x : vider tout ce qui a plus de x jours \n 0 : ne rien faire', '', '0')";
			echo traite_rqt($rqt,"insert perio_vidage_stat='1,30' into parametres ");
		}
		
		// suppr param en trop
		mysql_query("delete from parametres where type_param= 'opac' and sstype_param='logs_activate' ");
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='logs_activate' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'pmb', 'logs_activate', '0', 'Activer les statistiques pour l\'OPAC: \n 0 : non activé \n 1 : activé', '', '0')";
			echo traite_rqt($rqt,"insert logs_activate='0' into parametres ");
		}
		
		//Création des tables pour la gestion des stats de l'OPAC
		$rqt = "CREATE TABLE logopac(
			id_log INT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			date_log TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
			url_demandee VARCHAR( 255 )  NOT NULL default '',
			url_referente VARCHAR( 255 ) NOT NULL default '',
			get_log BLOB NOT NULL default '',
			post_log BLOB NOT NULL default '',
			num_session VARCHAR( 255 ) NOT NULL default '',
			server_log BLOB NOT NULL default '',
			empr_carac BLOB NOT NULL default '',
			empr_doc BLOB NOT NULL default '',
			empr_expl BLOB NOT NULL default '',
			nb_result BLOB NOT NULL default ''
			)";
		echo traite_rqt($rqt,"CREATE TABLE logopac ") ; 
		
		$rqt = "CREATE TABLE statopac(
			id_log INT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			date_log TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
			url_demandee VARCHAR( 255 )  NOT NULL default '',
			url_referente VARCHAR( 255 ) NOT NULL default '',
			get_log BLOB NOT NULL default '',
			post_log BLOB NOT NULL default '',
			num_session VARCHAR( 255 ) NOT NULL default 0,
			server_log BLOB NOT NULL default '',
			empr_carac BLOB NOT NULL default '',
			empr_doc BLOB NOT NULL default '',
			empr_expl BLOB NOT NULL default '',
			nb_result BLOB NOT NULL default ''
			)";
		echo traite_rqt($rqt,"CREATE TABLE statopac ") ; 
		
		$rqt = "CREATE TABLE statopac_request(
			idproc INT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			name VARCHAR( 255 )  NOT NULL default '',
			requete BLOB NOT NULL default '',
			comment TINYTEXT NOT NULL default '',
			parameters TEXT NOT NULL default '',
			num_vue MEDIUMINT( 8 ) NOT NULL default 0,
			autorisations MEDIUMTEXT NOT NULL default ''
			)";
		echo traite_rqt($rqt,"CREATE TABLE statopac_request ") ;
		
		$rqt = "CREATE TABLE statopac_vues(
			id_vue INT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			date_consolidation datetime NOT NULL DEFAULT '0000-000-00 00:00:00',
			nom_vue VARCHAR( 255 )  NOT NULL default '',
			comment TINYTEXT NOT NULL default ''
			)";
		echo traite_rqt($rqt,"CREATE TABLE statopac_vues ") ;
		
		$rqt = "CREATE TABLE statopac_vues_col(
			id_col INT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			nom_col VARCHAR( 255 )  NOT NULL default '',
			expression VARCHAR( 255 )  NOT NULL default '',
			num_vue MEDIUMINT( 8 ) NOT NULL default 0,
			ordre MEDIUMINT( 8 ) NOT NULL default 0,
			filtre VARCHAR( 255 )  NOT NULL default '',
			datatype VARCHAR( 10 )  NOT NULL default '',
			maj_flag INT( 1 ) NOT NULL default 0
			)";
		echo traite_rqt($rqt,"CREATE TABLE statopac_vues_col ") ;

	    //Listes de lecture partagées
		$rqt = "CREATE TABLE abo_liste_lecture(
			num_empr INT( 8 ) UNSIGNED NOT NULL default 0,
			num_liste INT( 8 ) UNSIGNED NOT NULL  default 0,
			PRIMARY KEY  (num_empr,num_liste)
			)";
		echo traite_rqt($rqt,"CREATE TABLE abo_liste_lecture ") ;
		
		$rqt = "CREATE TABLE opac_liste_lecture(
			id_liste INT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			nom_liste VARCHAR( 255 )  NOT NULL default '',
			description VARCHAR( 255 )  NOT NULL default '',
			notices_associees BLOB NOT NULL default '',
			public INT( 1 ) NOT NULL default 0,
			num_empr INT( 8 ) UNSIGNED NOT NULL  default 0
			)";
		echo traite_rqt($rqt,"CREATE TABLE opac_liste_lecture ") ;
		
		//param	
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param='opac' and sstype_param='shared_lists' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'opac', 'shared_lists', '0', 'Activer les listes de lecture partagées \n 0 : non activées \n 1 : activées', 'a_general', '0')";
			echo traite_rqt($rqt,"insert opac_shared_lists='0' into parametres ");
		}
		
		// Indexation des docs numériques	
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='indexation_docnum' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'pmb', 'indexation_docnum', '0', 'Activer l\'indexation des documents numériques \n 0 : non activée \n 1 : activée', '', '0')";
			echo traite_rqt($rqt,"insert indexation_docnum='0' into parametres ");
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='indexation_docnum_allfields' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'pmb', 'indexation_docnum_allfields', '0', 'Activer par défaut la recherche dans les documents numériques pour la recherche \"Tous les champs\" \n 0 : non activée \n 1 : activée', '', '0')";
			echo traite_rqt($rqt,"insert indexation_docnum_allfields='0' into parametres ");
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='indexation_docnum_allfields' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'opac', 'indexation_docnum_allfields', '0', 'Activer par défaut la recherche dans les documents numériques pour la recherche \"Tous les champs\" \n 0 : non activée \n 1 : activée', 'c_recherche', '0')";
			echo traite_rqt($rqt,"insert opac_indexation_docnum_allfields='0' into parametres ");
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='modules_search_docnum' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'opac', 'modules_search_docnum', '0', 'Recherche simple dans les documents numériques \n 0 : interdite \n 1 : autorisée \n 2 : autorisée et validée par défault', 'c_recherche', '0')";
			echo traite_rqt($rqt,"insert opac_modules_search_docnum='0' into parametres ");
		}
		$rqt="ALTER TABLE explnum ADD explnum_index_sew TEXT NOT NULL DEFAULT '', ADD explnum_index_wew TEXT NOT NULL DEFAULT '' ";
		echo traite_rqt($rqt,"ALTER TABLE explnum ADD explnum_index_sew, explnum_index_wew ");

		// localisation des réservations
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'pmb' and sstype_param='location_reservation' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'pmb', 'location_reservation', '0', '0', 'Utiliser la gestion de la réservation localisée?\n 0: Non\n 1: Oui') ";
			echo traite_rqt($rqt,"INSERT location_reservation INTO parametres") ;
		}
		$rqt = "CREATE TABLE resa_loc (
		   	resa_loc INT( 8 ) UNSIGNED NOT NULL default 0 ,
		   	resa_emprloc INT( 8 ) UNSIGNED NOT NULL default 0 ,
		   	PRIMARY KEY resa (resa_loc,resa_emprloc)
		   )";    
		echo traite_rqt($rqt,"CREATE TABLE resa_loc ") ; 

		$rqt = "ALTER TABLE resa_loc DROP INDEX i_resa_emprloc";
		echo traite_rqt($rqt, "ALTER TABLE resa_loc DROP INDEX i_resa_emprloc");
		$rqt = "ALTER TABLE resa_loc ADD INDEX i_resa_emprloc (resa_emprloc)";
		echo traite_rqt($rqt, "ALTER TABLE resa_loc ADD INDEX i_resa_emprloc (resa_emprloc)");
		
		// Extensions
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='extension_tab' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'pmb', 'extension_tab', '0', 'Afficher l\'onglet Extension ? \n 0 : Non \n 1 : Oui', '', '0')";
			echo traite_rqt($rqt,"insert pmb_extension_tab='0' into parametres ");
		}


		$rqt = "alter TABLE docs_type change idtyp_doc idtyp_doc int(5) unsigned NOT NULL auto_increment";
		echo traite_rqt($rqt, "alter TABLE docs_type change idtyp_doc idtyp_doc int(5)");
		
		$rqt = "alter TABLE exemplaires change expl_typdoc expl_typdoc int(5) unsigned NOT NULL default 0";
		echo traite_rqt($rqt, "alter TABLE exemplaires change expl_typdoc expl_typdoc int(5) ");
		
		$rqt = "alter TABLE pret_archive change arc_expl_typdoc arc_expl_typdoc int(5) unsigned default 0";
		echo traite_rqt($rqt, "alter TABLE pret_archive change arc_expl_typdoc arc_expl_typdoc int(5)");
		
		$rqt = "alter TABLE transferts change type_transfert type_transfert int(5) unsigned NOT NULL default 0";
		echo traite_rqt($rqt, "alter TABLE transferts change type_transfert type_transfert int(5)");
		
		$rqt = "alter TABLE transferts change origine origine int(5) unsigned NOT NULL default 0";
		echo traite_rqt($rqt, "alter TABLE transferts change origine origine int(5)");

		$rqt = "ALTER TABLE docs_location ADD css_style VARCHAR( 100 ) NOT NULL DEFAULT ''";
		echo traite_rqt($rqt, "ALTER TABLE docs_location ADD css_style ");

		// Upload des documents numériques
		$rqt = "CREATE TABLE upload_repertoire (
			repertoire_id INT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			repertoire_nom VARCHAR( 255 )  NOT NULL default '',
			repertoire_url TEXT  NOT NULL default '',
			repertoire_path TEXT  NOT NULL default '',
			repertoire_navigation INT( 1 ) NOT NULL default 0,
			repertoire_hachage INT( 1 ) NOT NULL default 0,
			repertoire_subfolder INT( 8 ) NOT NULL default 0,
			repertoire_utf8 INT( 1 ) NOT NULL default 0
			)";
		echo traite_rqt($rqt,"CREATE TABLE upload_repertoire ") ;

		$rqt = "ALTER TABLE explnum ADD explnum_repertoire INT( 8 ) NOT NULL default 0, ADD explnum_path TEXT NOT NULL default '' ";
		echo traite_rqt($rqt,"ALTER TABLE explnum ") ;
		
		$rqt = "ALTER TABLE users ADD deflt_upload_repertoire INT( 8 ) NOT NULL default 0 ";
		echo traite_rqt($rqt,"ALTER TABLE users ") ;

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='indexation_docnum_default' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'pmb', 'indexation_docnum_default', '0', 'Indexer le document numérique par défaut ? \n 0 : Non \n 1 : Oui', '', '0')";
			echo traite_rqt($rqt,"insert pmb_indexation_docnum_default='0' into parametres ");
		}
		
		// Modification sur les listes de lecture
		$rqt = "ALTER TABLE opac_liste_lecture ADD read_only INT( 1 ) NOT NULL default 0, ADD confidential INT( 1 ) NOT NULL default 0 ";
		echo traite_rqt($rqt,"ALTER TABLE opac_liste_lecture ") ;
		
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param='opac' and sstype_param='shared_lists_readonly' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'opac', 'shared_lists_readonly', '0', 'Listes de lecture partagées en lecture seule \n 0 : non activées \n 1 : activées', 'a_general', '0')";
			echo traite_rqt($rqt,"insert opac_shared_lists_readonly='0' into parametres ");
		}
		
		$rqt = "ALTER TABLE abo_liste_lecture ADD etat INT(1) UNSIGNED NOT NULL  default 0, ADD commentaire TEXT NOT NULL default ''";
		echo traite_rqt($rqt,"ALTER TABLE abo_liste_lecture") ;
		
		// Paramètres pour la connexion auto
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param='opac' and sstype_param='connexion_phrase' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'opac', 'connexion_phrase', '', 'Phrase permettant l\'encodage de la connexion automatique à partir d\'un mail', 'a_general', '0')";
			echo traite_rqt($rqt,"insert opac_connexion_phrase='' into parametres ");
		}
		
		// Afficher le numéro du lecteur sous l'adresse dans les différentes lettres
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='afficher_numero_lecteur_lettres' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
				VALUES ('pmb', 'afficher_numero_lecteur_lettres', '1', 'Afficher le numéro du lecteur sous l''adresse dans les différentes lettres.\r\n0: non\r\n1: oui', '', 0) ";
			echo traite_rqt($rqt, "insert afficher_numero_lecteur_lettres into parameters");
		}

		// Place le bloc d'adresse selon des coordonnées absolues
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='lettres_bloc_adresse_position_absolue' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
				VALUES ('pmb', 'lettres_bloc_adresse_position_absolue', '0 100 40', 'Place le bloc d''adresse selon des coordonnées absolues.\nactivé x y\nactivé : activer cette fonction (valeurs: 0/1)\nx : Position horizontale\ny : Position verticale', '', 0)";
			echo traite_rqt($rqt, "insert lettres_bloc_adresse_position_absolue into parameters");
		}


		// CONNECTEURS SORTANTS
		// Durée de vie des recherches dans le cache, pour les services externes, en secondes.
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='external_service_search_cache' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
				VALUES ('pmb', 'external_service_search_cache', '3600', 'Durée de vie des recherches dans le cache, pour les services externes, en secondes.', '', 0)";
			echo traite_rqt($rqt, "insert afficher_numero_lecteur_lettres into parameters");
		}

		// Durée de vie des sessions pour les services externes, en secondes..
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='external_service_session_duration' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
				VALUES ('pmb', 'external_service_session_duration', '600', 'Durée de vie des sessions pour les services externes, en secondes.', '', 0)";
			echo traite_rqt($rqt, "insert afficher_numero_lecteur_lettres into parameters");
		}

		$rqt = "CREATE TABLE connectors_out (
 					connectors_out_id int(11) NOT NULL auto_increment,
  					connectors_out_config longblob NOT NULL DEFAULT '',
  					PRIMARY KEY  (connectors_out_id)
				)";
		echo traite_rqt($rqt,"CREATE TABLE connectors_out") ;

		$rqt = "CREATE TABLE connectors_out_oai_tokens (
 					connectors_out_oai_token_token varchar(32) NOT NULL,
  					connectors_out_oai_token_environnement text NOT NULL DEFAULT '',
  					connectors_out_oai_token_expirationdate datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  					PRIMARY KEY  (connectors_out_oai_token_token)
				)";
		echo traite_rqt($rqt,"CREATE TABLE connectors_out_oai_tokens ") ;

		$rqt = "CREATE TABLE connectors_out_setcaches (
  					connectors_out_setcache_id int(11) NOT NULL auto_increment,
  					connectors_out_setcache_setnum int(11) NOT NULL DEFAULT 0,
  					connectors_out_setcache_lifeduration int(4) NOT NULL DEFAULT 0,
  					connectors_out_setcache_lifeduration_unit enum('seconds','minutes','hours','days','weeks','months')  NOT NULL DEFAULT 'seconds',
  					connectors_out_setcache_lastupdatedate datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  					PRIMARY KEY  (connectors_out_setcache_id),
  					UNIQUE KEY connectors_out_setcache_setnum (connectors_out_setcache_setnum)
				)";
		echo traite_rqt($rqt,"CREATE TABLE connectors_out_setcaches") ;

		$rqt = "CREATE TABLE connectors_out_setcache_values (
  					connectors_out_setcache_values_cachenum int(11) NOT NULL default 0,
  					connectors_out_setcache_values_value int(11) NOT NULL DEFAULT 0,
  					PRIMARY KEY (connectors_out_setcache_values_cachenum,connectors_out_setcache_values_value)
				)";
		echo traite_rqt($rqt,"CREATE TABLE connectors_out_setcache_values") ;

		$rqt = "CREATE TABLE connectors_out_setcategs (
  					connectors_out_setcateg_id int(11) NOT NULL auto_increment,
  					connectors_out_setcateg_name varchar(100) NOT NULL DEFAULT '',
  					PRIMARY KEY  (connectors_out_setcateg_id),
  					UNIQUE KEY connectors_out_setcateg_name (connectors_out_setcateg_name)
				)";
		echo traite_rqt($rqt,"CREATE TABLE connectors_out_setcategs") ;

		$rqt = "CREATE TABLE connectors_out_setcateg_sets (
  					connectors_out_setcategset_setnum int(11) NOT NULL,
  					connectors_out_setcategset_categnum int(11) NOT NULL DEFAULT 0,
  					PRIMARY KEY  (connectors_out_setcategset_setnum,connectors_out_setcategset_categnum)
				)";
		echo traite_rqt($rqt,"CREATE TABLE connectors_out_setcateg_sets") ;

		$rqt = "CREATE TABLE connectors_out_sets (
  					connector_out_set_id int(11) NOT NULL auto_increment,
  					connector_out_set_caption varchar(100) NOT NULL DEFAULT '',
  					connector_out_set_type int(4) NOT NULL DEFAULT 0,
  					connector_out_set_config longblob NOT NULL DEFAULT '',
  					PRIMARY KEY  (connector_out_set_id),
  					UNIQUE KEY connector_out_set_caption (connector_out_set_caption)
				)";
		echo traite_rqt($rqt,"CREATE TABLE connectors_out_sets") ;

		$rqt = "CREATE TABLE connectors_out_sources (
  					connectors_out_source_id int(11) NOT NULL auto_increment,
  					connectors_out_sources_connectornum int(11) NOT NULL DEFAULT 0,
  					connectors_out_source_name varchar(100) NOT NULL DEFAULT '',
  					connectors_out_source_comment varchar(200) NOT NULL DEFAULT '',
  					connectors_out_source_config longblob NOT NULL DEFAULT '',
  					PRIMARY KEY  (connectors_out_source_id)
				)";
		echo traite_rqt($rqt,"CREATE TABLE connectors_out_sources") ;

		$rqt = "CREATE TABLE connectors_out_sources_esgroups (
  					connectors_out_source_esgroup_sourcenum int(11) NOT NULL default 0,
  					connectors_out_source_esgroup_esgroupnum int(11) NOT NULL DEFAULT 0,
  					PRIMARY KEY  (connectors_out_source_esgroup_sourcenum,connectors_out_source_esgroup_esgroupnum)
				)";
		echo traite_rqt($rqt,"CREATE TABLE connectors_out_sources_esgroups") ;

		$rqt = "CREATE TABLE es_cache (
  					escache_groupname varchar(100)  NOT NULL DEFAULT '',
  					escache_unique_id varchar(100)  NOT NULL DEFAULT '',
  					escache_value int(11) NOT NULL DEFAULT 0,
  					PRIMARY KEY  (escache_groupname,escache_unique_id,escache_value)
				)";
		echo traite_rqt($rqt,"CREATE TABLE es_cache ") ;

		$rqt = "CREATE TABLE es_converted_cache (
  					es_converted_cache_objecttype int(11) NOT NULL DEFAULT 0,
	  				es_converted_cache_objectref int(11) NOT NULL DEFAULT 0,
  					es_converted_cache_format varchar(50) NOT NULL DEFAULT '',
  					es_converted_cache_value text NOT NULL DEFAULT '',
  					es_converted_cache_bestbefore datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  					PRIMARY KEY  (es_converted_cache_objecttype,es_converted_cache_objectref,es_converted_cache_format)
				)";
		echo traite_rqt($rqt,"CREATE TABLE es_converted_cache") ;

		$rqt = "CREATE TABLE es_esgroups (
  					esgroup_id int(11) NOT NULL auto_increment,
  					esgroup_name varchar(100) NOT NULL DEFAULT '',
  					esgroup_fullname varchar(255) NOT NULL DEFAULT '',
  					esgroup_pmbusernum int(5) NOT NULL DEFAULT 0,
  					PRIMARY KEY  (esgroup_id),
  					UNIQUE KEY esgroup_name (esgroup_name)
				)";
		echo traite_rqt($rqt,"CREATE TABLE es_esgroups") ;

		$rqt = "CREATE TABLE es_esgroup_esusers (
  					esgroupuser_groupnum int(11) NOT NULL DEFAULT 0,
  					esgroupuser_usertype int(4) NOT NULL DEFAULT 0,
  					esgroupuser_usernum int(11) NOT NULL DEFAULT 0,
  					PRIMARY KEY  (esgroupuser_usernum,esgroupuser_groupnum,esgroupuser_usertype)
				)";
		echo traite_rqt($rqt,"CREATE TABLE es_esgroup_esusers") ;

		$rqt = "CREATE TABLE es_esusers (
  					esuser_id int(11) NOT NULL auto_increment,
  					esuser_username varchar(100) NOT NULL DEFAULT '',
  					esuser_password varchar(100) NOT NULL DEFAULT '',
  					esuser_fullname varchar(255) NOT NULL DEFAULT '',
  					esuser_groupnum int(11) NOT NULL default 0,
  					PRIMARY KEY  (esuser_id),
  					UNIQUE KEY esuser_username (esuser_username)
				)";
		echo traite_rqt($rqt,"CREATE TABLE es_esusers") ;

		$rqt = "CREATE TABLE es_methods (
  					id_method int(10) unsigned NOT NULL auto_increment,
  					groupe varchar(255) NOT NULL DEFAULT '',
  					method varchar(255) NOT NULL DEFAULT '',
  					available smallint(5) unsigned NOT NULL DEFAULT 1,
  					PRIMARY KEY  (id_method)
				)";
		echo traite_rqt($rqt,"CREATE TABLE es_methods") ;

		$rqt = "CREATE TABLE es_methods_users (
  					num_method int(10) unsigned NOT NULL DEFAULT 0,
  					num_user int(10) unsigned NOT NULL DEFAULT 0,
  					anonymous smallint(6) DEFAULT '0',
  					PRIMARY KEY  (num_method,num_user)
				)";
		echo traite_rqt($rqt,"CREATE TABLE es_methods_users ") ;

		$rqt = "CREATE TABLE es_searchcache (
  					es_searchcache_searchid varchar(100) NOT NULL default '',
  					es_searchcache_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  					es_searchcache_serializedsearch text NOT NULL DEFAULT '',
  					PRIMARY KEY  (es_searchcache_searchid)
				)";
		echo traite_rqt($rqt,"CREATE TABLE es_searchcache") ;

		$rqt = "CREATE TABLE es_searchsessions (
 				es_searchsession_id varchar(100) NOT NULL default '',
  				es_searchsession_searchnum varchar(100) NOT NULL DEFAULT '',
  				es_searchsession_searchrealm varchar(100) NOT NULL DEFAULT '',
  				es_searchsession_pmbuserid int(11) NOT NULL DEFAULT -1,
  				es_searchsession_opacemprid int(11) NOT NULL DEFAULT -1,
  				es_searchsession_lastseendate datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  				PRIMARY KEY  (es_searchsession_id)
				)";
		echo traite_rqt($rqt,"CREATE TABLE es_searchsessions") ;

		// Gestion des parties d'un exemplaire en RFID
		$rqt = "ALTER TABLE exemplaires ADD expl_nbparts INT( 8 ) unsigned NOT NULL default 1 ";
		echo traite_rqt($rqt,"ALTER TABLE exemplaires ") ;

			
		//Suggestions multiples
	
		$rqt = "ALTER TABLE suggestions ADD sugg_source INT( 8 ) NOT NULL default 0, 
					ADD date_publication varchar(255) NOT NULL default '' ";
		echo traite_rqt($rqt,"ALTER TABLE suggestions ") ;
	
		$rqt = "CREATE TABLE suggestions_source (
	 				id_source INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
	  				libelle_source varchar(255) NOT NULL DEFAULT '',
					PRIMARY KEY (id_source))";	
		echo traite_rqt($rqt,"CREATE TABLE suggestions_source") ;
	
		$rqt = "CREATE TABLE explnum_doc (
	 				id_explnum_doc INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
	  				num_doc INT(8) NOT NULL DEFAULT 0,
					type_doc varchar(3) NOT NULL DEFAULT 'sug',
					explnum_doc_nomfichier text NOT NULL DEFAULT '',
					explnum_doc_mimetype varchar(255) NOT NULL DEFAULT '',
					explnum_doc_data blob NOT NULL DEFAULT '',
					explnum_doc_extfichier varchar(20) NOT NULL DEFAULT '',
					PRIMARY KEY (id_explnum_doc))";	
		echo traite_rqt($rqt,"CREATE TABLE explnum_doc") ;
	
		$rqt = "ALTER TABLE suggestions_origine ADD INDEX i_origine (origine,type_origine)";
		echo traite_rqt($rqt, "ADD INDEX i_origine to suggestions_origine");
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param='opac' and sstype_param='allow_multiple_sugg' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'opac', 'allow_multiple_sugg', '0', 'Autoriser les suggestions multiples.\r\n0: non\r\n1: oui', 'a_general', '0')";
			echo traite_rqt($rqt,"insert opac_allow_multiple_sugg='0' into parametres ");
		}
		
		//Caches services externes
	
		$rqt = "CREATE TABLE es_cache_blob (
				  es_cache_objectref varchar(100) NOT NULL DEFAULT '',
				  es_cache_objecttype int(11) NOT NULL DEFAULT 0,
				  es_cache_objectformat varchar(100) NOT NULL DEFAULT '',
				  es_cache_owner varchar(100) NOT NULL DEFAULT '',
				  es_cache_creationdate datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				  es_cache_expirationdate datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				  es_cache_content mediumblob NOT NULL DEFAULT '',
				  PRIMARY KEY  (es_cache_objectref,es_cache_objecttype,es_cache_objectformat,es_cache_owner))";	
		echo traite_rqt($rqt,"CREATE TABLE es_cache_blob") ;
	
		$rqt = "CREATE TABLE es_cache_int (
				  es_cache_objectref varchar(100) NOT NULL DEFAULT '',
				  es_cache_objecttype int(11) NOT NULL DEFAULT 0,
				  es_cache_objectformat varchar(100) NOT NULL DEFAULT '',
				  es_cache_owner varchar(100) NOT NULL DEFAULT '',
				  es_cache_creationdate datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				  es_cache_expirationdate datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				  es_cache_content int NOT NULL DEFAULT 0,
				  PRIMARY KEY  (es_cache_objectref,es_cache_objecttype,es_cache_objectformat,es_cache_owner))";
		echo traite_rqt($rqt,"CREATE TABLE es_cache_int");
			
		// Template de notice
		$rqt = "CREATE TABLE notice_tpl (
 				notpl_id int(10) unsigned NOT NULL auto_increment,
  				notpl_name varchar(256) NOT NULL DEFAULT '',
  				notpl_code text NOT NULL DEFAULT '',
				notpl_comment varchar(256) NOT NULL DEFAULT '',
 				notpl_id_test int(10) unsigned  NOT NULL DEFAULT 0,
  				PRIMARY KEY  (notpl_id)
				)";
		echo traite_rqt($rqt,"CREATE TABLE notice_tpl") ;
		
		$rqt = "CREATE TABLE notice_tplcode(
 				num_notpl int(10) unsigned  NOT NULL DEFAULT 0,
 				notplcode_localisation mediumint(8) NOT NULL default 0,
  				notplcode_typdoc char(2) not null default 'a',
				notplcode_niveau_biblio char(1) not null default 'm',
				notplcode_niveau_hierar char(1) not null default '0',				
				nottplcode_code text NOT NULL DEFAULT '',
				PRIMARY KEY  (num_notpl,notplcode_localisation,notplcode_typdoc,notplcode_niveau_biblio)
				)";
		echo traite_rqt($rqt,"CREATE TABLE notice_tplcode") ;
		
		$rqt = "ALTER TABLE bannettes ADD piedpage_mail text NOT NULL DEFAULT '' ";
		echo traite_rqt($rqt, "ALTER TABLE bannettes ADD piedpage_mail ");
		
		$rqt = "ALTER TABLE bannettes ADD notice_tpl int(10) unsigned  NOT NULL DEFAULT 0 ";		
		echo traite_rqt($rqt, "ALTER TABLE bannettes ADD notice_tpl ");
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='bannette_notices_template' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
				VALUES ('opac', 'bannette_notices_template', '0', 'Id du template de notice utilisé par défaut en diffusion de bannettes. Si vide ou à 0, le template classique est utilisé.', 'l_dsi', 0)";
			echo traite_rqt($rqt, "insert bannette_notices_template into parameters");
		}
		
		$rqt = "ALTER TABLE bannettes ADD group_pperso int(10) unsigned NOT NULL DEFAULT 0 ";		
		echo traite_rqt($rqt, "ALTER TABLE bannettes ADD group_pperso ");
		
		$rqt = "ALTER TABLE abts_abts drop index index_num_notice " ;
		echo traite_rqt($rqt,"drop index index_num_notice");
		$rqt = "ALTER TABLE abts_abts ADD INDEX index_num_notice (num_notice)" ;
		echo traite_rqt($rqt,"ALTER TABLE abts_abts ADD INDEX index_num_notice");
		
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.77");
		break;

	case "v4.77": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		
		$rqt = "ALTER TABLE import_marc DROP INDEX i_nonot_orig " ;
		echo traite_rqt($rqt,"DROP INDEX i_nonot_orig");
		$rqt = "ALTER TABLE import_marc ADD INDEX i_nonot_orig(no_notice,origine)" ;
		echo traite_rqt($rqt,"ALTER TABLE import_marc ADD INDEX i_nonot_orig");
		
		$rqt = "ALTER TABLE resa ADD resa_arc int(10) unsigned  NOT NULL DEFAULT 0 ";		
		echo traite_rqt($rqt, "ALTER TABLE resa ADD resa_arc ");
				
		$rqt = "CREATE TABLE resa_archive (
			resarc_id int(10) unsigned NOT NULL auto_increment,
			resarc_date datetime NOT NULL default '0000-00-00 00:00:00',
			resarc_debut date NOT NULL default'0000-00-00',
			resarc_fin date NOT NULL default'0000-00-00',			
			resarc_idnotice int(10) unsigned NOT NULL default '0',
			resarc_idbulletin int(10) unsigned NOT NULL default '0',			
			resarc_confirmee int(1) unsigned default '0',
			resarc_cb varchar(14) NOT NULL default '',
			resarc_loc_retrait smallint(5) unsigned default '0',		
			resarc_from_opac int(1) unsigned default '0',
			resarc_anulee int(1) unsigned default '0',
			resarc_pretee int(1) unsigned default '0',
			resarc_arcpretid int(10) unsigned NOT NULL default '0',			
			resarc_id_empr int(10) unsigned NOT NULL default '0',
			resarc_empr_cp varchar(10) NOT NULL default '',
			resarc_empr_ville varchar(255) NOT NULL default '',
			resarc_empr_prof varchar(255) NOT NULL default '',
			resarc_empr_year int(4) unsigned default '0',
			resarc_empr_categ smallint(5) unsigned default '0',
			resarc_empr_codestat smallint(5) unsigned default '0',
			resarc_empr_sexe tinyint(3) unsigned default '0',
			resarc_empr_location int(6) unsigned NOT NULL default '1',
			resarc_expl_nb int(5) unsigned default '0',
			resarc_expl_typdoc int(5) unsigned default '0',
			resarc_expl_cote varchar(255) NOT NULL default '',
			resarc_expl_statut smallint(5) unsigned default '0',
			resarc_expl_location smallint(5) unsigned default '0',
			resarc_expl_codestat smallint(5) unsigned default '0',
			resarc_expl_owner mediumint(8) unsigned default '0',
			resarc_expl_section int(5) unsigned NOT NULL default '0',	
			PRIMARY KEY(resarc_id),			
			KEY i_pa_idempr (resarc_id_empr),
			KEY i_pa_notice (resarc_idnotice),
			KEY i_pa_bulletin (resarc_idbulletin),
			KEY i_pa_resarc_date (resarc_date)
		) ";
		echo traite_rqt($rqt,"CREATE TABLE resa_archive");
		
		@set_time_limit(0);
		$rqt = "ALTER TABLE empr_custom_values DROP INDEX i_ecv_st " ;
		echo traite_rqt($rqt,"DROP INDEX i_ecv_st");
		$rqt = "ALTER TABLE empr_custom_values ADD INDEX i_ecv_st(empr_custom_small_text)" ;
		echo traite_rqt($rqt,"ALTER TABLE empr_custom_values ADD INDEX i_ecv_st");
		
		$rqt = "ALTER TABLE empr_custom_values DROP INDEX i_ecv_t " ;
		echo traite_rqt($rqt,"DROP INDEX i_ecv_t");
		$rqt = "ALTER TABLE empr_custom_values ADD INDEX i_ecv_t(empr_custom_text(255))" ;
		echo traite_rqt($rqt,"ALTER TABLE empr_custom_values ADD INDEX i_ecv_t");
		
		$rqt = "ALTER TABLE empr_custom_values DROP INDEX i_ecv_i " ;
		echo traite_rqt($rqt,"DROP INDEX i_ecv_i");
		$rqt = "ALTER TABLE empr_custom_values ADD INDEX i_ecv_i(empr_custom_integer)" ;
		echo traite_rqt($rqt,"ALTER TABLE empr_custom_values ADD INDEX i_ecv_i");

		$rqt = "ALTER TABLE empr_custom_values DROP INDEX i_ecv_d " ;
		echo traite_rqt($rqt,"DROP INDEX i_ecv_d");
		$rqt = "ALTER TABLE empr_custom_values ADD INDEX i_ecv_d(empr_custom_date)" ;
		echo traite_rqt($rqt,"ALTER TABLE empr_custom_values ADD INDEX i_ecv_d");
		
		$rqt = "ALTER TABLE empr_custom_values DROP INDEX i_ecv_f " ;
		echo traite_rqt($rqt,"DROP INDEX i_ecv_f");
		$rqt = "ALTER TABLE empr_custom_values ADD INDEX i_ecv_f(empr_custom_float)" ;
		echo traite_rqt($rqt,"ALTER TABLE empr_custom_values ADD INDEX i_ecv_f");
		
		$rqt = "ALTER TABLE empr_custom_lists DROP INDEX champ_list_value  " ;
		echo traite_rqt($rqt,"DROP INDEX champ_list_value ");
		$rqt = "ALTER TABLE empr_custom_lists DROP INDEX i_ecl_lv  " ;
		echo traite_rqt($rqt,"DROP INDEX i_ecl_lv ");
		$rqt = "ALTER TABLE empr_custom_lists ADD INDEX i_ecl_lv(empr_custom_list_value)" ;
		echo traite_rqt($rqt,"ALTER TABLE empr_custom_lists ADD INDEX i_ecl_lv");
		

		$rqt = "ALTER TABLE collstate_custom_values DROP INDEX i_ccv_st " ;
		echo traite_rqt($rqt,"DROP INDEX i_ccv_st");
		$rqt = "ALTER TABLE collstate_custom_values ADD INDEX i_ccv_st(collstate_custom_small_text)" ;
		echo traite_rqt($rqt,"ALTER TABLE collstate_custom_values ADD INDEX i_ccv_st");
		
		$rqt = "ALTER TABLE collstate_custom_values DROP INDEX i_ccv_t " ;
		echo traite_rqt($rqt,"DROP INDEX i_ccv_t");
		$rqt = "ALTER TABLE collstate_custom_values ADD INDEX i_ccv_t(collstate_custom_text(255))" ;
		echo traite_rqt($rqt,"ALTER TABLE collstate_custom_values ADD INDEX i_ccv_t");
		
		$rqt = "ALTER TABLE collstate_custom_values DROP INDEX i_ccv_i " ;
		echo traite_rqt($rqt,"DROP INDEX i_ccv_i");
		$rqt = "ALTER TABLE collstate_custom_values ADD INDEX i_ccv_i(collstate_custom_integer)" ;
		echo traite_rqt($rqt,"ALTER TABLE collstate_custom_values ADD INDEX i_ccv_i");

		$rqt = "ALTER TABLE collstate_custom_values DROP INDEX i_ccv_d " ;
		echo traite_rqt($rqt,"DROP INDEX i_ccv_d");
		$rqt = "ALTER TABLE collstate_custom_values ADD INDEX i_ccv_d(collstate_custom_date)" ;
		echo traite_rqt($rqt,"ALTER TABLE collstate_custom_values ADD INDEX i_ccv_d");
		
		$rqt = "ALTER TABLE collstate_custom_values DROP INDEX i_ccv_f " ;
		echo traite_rqt($rqt,"DROP INDEX i_vcv_f");
		$rqt = "ALTER TABLE collstate_custom_values ADD INDEX i_ccv_f(collstate_custom_float)" ;
		echo traite_rqt($rqt,"ALTER TABLE collstate_custom_values ADD INDEX i_ccv_f");
		
		$rqt = "ALTER TABLE collstate_custom_lists DROP INDEX collstate_champ_list_value  " ;
		echo traite_rqt($rqt,"DROP INDEX collstate_champ_list_value ");
		$rqt = "ALTER TABLE collstate_custom_lists DROP INDEX i_ccl_lv  " ;
		echo traite_rqt($rqt,"DROP INDEX i_ccl_lv ");
		$rqt = "ALTER TABLE collstate_custom_lists ADD INDEX i_ccl_lv(collstate_custom_list_value)" ;
		echo traite_rqt($rqt,"ALTER TABLE collstate_custom_lists ADD INDEX i_ccl_lv");
		

		$rqt = "ALTER TABLE expl_custom_values DROP INDEX i_excv_st " ;
		echo traite_rqt($rqt,"DROP INDEX i_excv_st");
		$rqt = "ALTER TABLE expl_custom_values ADD INDEX i_excv_st(expl_custom_small_text)" ;
		echo traite_rqt($rqt,"ALTER TABLE expl_custom_values ADD INDEX i_excv_st");
		
		$rqt = "ALTER TABLE expl_custom_values DROP INDEX i_excv_t " ;
		echo traite_rqt($rqt,"DROP INDEX i_excv_t");
		$rqt = "ALTER TABLE expl_custom_values ADD INDEX i_excv_t(expl_custom_text(255))" ;
		echo traite_rqt($rqt,"ALTER TABLE expl_custom_values ADD INDEX i_excv_t");
		
		$rqt = "ALTER TABLE expl_custom_values DROP INDEX i_excv_i " ;
		echo traite_rqt($rqt,"DROP INDEX i_excv_i");
		$rqt = "ALTER TABLE expl_custom_values ADD INDEX i_excv_i(expl_custom_integer)" ;
		echo traite_rqt($rqt,"ALTER TABLE expl_custom_values ADD INDEX i_excv_i");

		$rqt = "ALTER TABLE expl_custom_values DROP INDEX i_excv_d " ;
		echo traite_rqt($rqt,"DROP INDEX i_excv_d");
		$rqt = "ALTER TABLE expl_custom_values ADD INDEX i_excv_d(expl_custom_date)" ;
		echo traite_rqt($rqt,"ALTER TABLE expl_custom_values ADD INDEX i_excv_d");
		
		$rqt = "ALTER TABLE expl_custom_values DROP INDEX i_excv_f " ;
		echo traite_rqt($rqt,"DROP INDEX i_excv_f");
		$rqt = "ALTER TABLE expl_custom_values ADD INDEX i_excv_f(expl_custom_float)" ;
		echo traite_rqt($rqt,"ALTER TABLE expl_custom_values ADD INDEX i_excv_f");
		
		$rqt = "ALTER TABLE expl_custom_lists DROP INDEX expl_champ_list_value  " ;
		echo traite_rqt($rqt,"DROP INDEX expl_champ_list_value ");
		$rqt = "ALTER TABLE expl_custom_lists DROP INDEX i_excl_lv " ;
		echo traite_rqt($rqt,"DROP INDEX i_excl_lv");
		$rqt = "ALTER TABLE expl_custom_lists ADD INDEX i_excl_lv(expl_custom_list_value)" ;
		echo traite_rqt($rqt,"ALTER TABLE expl_custom_lists ADD INDEX i_evcl_lv");
		

		$rqt = "ALTER TABLE notices_custom_values DROP INDEX i_ncv_st " ;
		echo traite_rqt($rqt,"DROP INDEX i_ncv_st");
		$rqt = "ALTER TABLE notices_custom_values ADD INDEX i_ncv_st(notices_custom_small_text)" ;
		echo traite_rqt($rqt,"ALTER TABLE notices_custom_values ADD INDEX i_ncv_st");
		
		$rqt = "ALTER TABLE notices_custom_values DROP INDEX i_ncv_t " ;
		echo traite_rqt($rqt,"DROP INDEX i_ncv_t");
		$rqt = "ALTER TABLE notices_custom_values ADD INDEX i_ncv_t(notices_custom_text(255))" ;
		echo traite_rqt($rqt,"ALTER TABLE notices_custom_values ADD INDEX i_ncv_t");
		
		$rqt = "ALTER TABLE notices_custom_values DROP INDEX i_ncv_i " ;
		echo traite_rqt($rqt,"DROP INDEX i_ncv_i");
		$rqt = "ALTER TABLE notices_custom_values ADD INDEX i_ncv_i(notices_custom_integer)" ;
		echo traite_rqt($rqt,"ALTER TABLE notices_custom_values ADD INDEX i_ncv_i");

		$rqt = "ALTER TABLE notices_custom_values DROP INDEX i_ncv_d " ;
		echo traite_rqt($rqt,"DROP INDEX i_ncv_d");
		$rqt = "ALTER TABLE notices_custom_values ADD INDEX i_ncv_d(notices_custom_date)" ;
		echo traite_rqt($rqt,"ALTER TABLE notices_custom_values ADD INDEX i_ncv_d");
		
		$rqt = "ALTER TABLE notices_custom_values DROP INDEX i_ncv_f " ;
		echo traite_rqt($rqt,"DROP INDEX i_ncv_f");
		$rqt = "ALTER TABLE notices_custom_values ADD INDEX i_ncv_f(notices_custom_float)" ;
		echo traite_rqt($rqt,"ALTER TABLE notices_custom_values ADD INDEX i_ncv_f");
		
		$rqt = "ALTER TABLE notices_custom_lists DROP INDEX noti_champ_list_value  " ;
		echo traite_rqt($rqt,"DROP INDEX noti_champ_list_value ");
		$rqt = "ALTER TABLE notices_custom_lists DROP INDEX i_ncl_lv" ;
		echo traite_rqt($rqt,"DROP INDEX i_ncl_lv");
		$rqt = "ALTER TABLE notices_custom_lists ADD INDEX i_ncl_lv(notices_custom_list_value)" ;
		echo traite_rqt($rqt,"ALTER TABLE notices_custom_lists ADD INDEX i_ncl_lv");
			
	     //Modification de la taille des champs tit1 à tit4
        $rqt="alter table notices change tit1 tit1 text";
        echo traite_rqt($rqt,"alter table notices change tit1 format to text");
        $rqt="alter table notices change tit2 tit2 text"; 
        echo traite_rqt($rqt,"alter table notices change tit2 format to text");
        $rqt="alter table notices change tit3 tit3 text"; 
        echo traite_rqt($rqt,"alter table notices change tit3 format to text");
        $rqt="alter table notices change tit4 tit4 text";  
        echo traite_rqt($rqt,"alter table notices change tit4 format to text");
			
		//Documents numériques des suggestions
		$rqt = " CREATE TABLE explnum_doc_sugg(
			num_explnum_doc int(10) NOT NULL default 0,
			num_suggestion int(10) NOT NULL default 0,
			PRIMARY KEY(num_explnum_doc,num_suggestion)
		)";
		echo traite_rqt($rqt,"CREATE TABLE explnum_doc_sugg") ;
		
		$rqt = "select id_explnum_doc, num_doc from explnum_doc where type_doc='sug'";
		$res = mysql_query($rqt);
		if($res){
			while($explnum_sug = mysql_fetch_object($res)){
				$req = "insert into explnum_doc_sugg set num_explnum_doc='".$explnum_sug->id_explnum_doc."', num_suggestion='".$explnum_sug->num_doc."'";
				mysql_query($rqt);
			}
		}
		echo traite_rqt("select 1","insert into explnum_doc_sugg");
		
		$rqt = "ALTER TABLE explnum_doc DROP num_doc";
		echo traite_rqt($rqt,"ALTER TABLE explnum_doc DROP num_doc") ;
	
		$rqt = "ALTER TABLE explnum_doc DROP type_doc";
		echo traite_rqt($rqt,"ALTER TABLE explnum_doc DROP type_doc") ;
		
		$rqt = "ALTER TABLE users ADD param_rfid_activate INT(1) NOT NULL default '1' AFTER param_sounds" ;		
		echo traite_rqt($rqt,"ALTER TABLE users ADD param_rfid_activate");
		
		
		//Module des demandes 
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'demandes' and sstype_param='active' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'demandes', 'active', '0', 'Module demandes activé.\n 0 : Non.\n 1 : Oui.', '',0) ";
			echo traite_rqt($rqt, "insert demandes_active=0 into parameters");
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'demandes' and sstype_param='demandes_statut_notice' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'demandes', 'statut_notice', '0', 'Id du statut de notice pour la notice de demandes.', '',0) ";
			echo traite_rqt($rqt, "insert demandes_statut_notice=0 into parameters");
		}
	
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='demandes_active' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'opac', 'demandes_active', '0', 'Activer les demandes pour l\'OPAC.\n 0 : Non.\n 1 : Oui.', 'a_general',0) ";
			echo traite_rqt($rqt, "insert opac_demandes_active=0 into parameters");
		}
		
		$rqt = "CREATE TABLE demandes(
				id_demande int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				num_demandeur mediumint(8) NOT NULL default 0,
				theme_demande int(3) not null default 0,
				type_demande int(3) not null default 0,
				etat_demande int(3) not null default 0,
				date_demande DATE NOT NULL DEFAULT '0000-000-00',
				date_prevue DATE NOT NULL DEFAULT '0000-000-00',	
				deadline_demande DATE NOT NULL DEFAULT '0000-000-00',
				titre_demande varchar(255) NOT NULL default '',				
				sujet_demande text NOT NULL DEFAULT '',
				progression mediumint(3) NOT NULL default 0,
				num_user_cloture mediumint(3) NOT NULL default 0,
				num_notice int(10) not null default 0,
				PRIMARY KEY  (id_demande),
				KEY i_num_demandeur(num_demandeur),
				KEY i_date_demande(date_demande),
				KEY i_deadline_demande(deadline_demande)
				)";
		echo traite_rqt($rqt,"CREATE TABLE demandes") ;
	
		$rqt = "CREATE TABLE demandes_actions(
				id_action int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				type_action int(3) NOT NULL default 0,
				statut_action int(3) NOT NULL default 0,
				sujet_action varchar(255) not null default '',
				detail_action text NOT NULL DEFAULT '',
				date_action DATE NOT NULL DEFAULT '0000-000-00',
				deadline_action DATE NOT NULL DEFAULT '0000-000-00',				
				temps_passe mediumint(8) NOT NULL DEFAULT 0,
				cout mediumint(3) NOT NULL default 0,
				progression_action mediumint(3) NOT NULL default 0,
				prive_action int(1) not null default 0,
				num_demande	int(10) not null default 0,
				PRIMARY KEY  (id_action),
				KEY i_date_action(date_action),
				KEY i_deadline_action(deadline_action),
				KEY i_num_demande(num_demande)
				)";
		echo traite_rqt($rqt,"CREATE TABLE demandes_actions") ;
	
		$rqt = "CREATE TABLE demandes_notes(
				id_note int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				prive int(1) NOT NULL default 0,
				rapport int(1) NOT NULL default 0,
				contenu text NOT NULL default '',
				date_note DATE NOT NULL DEFAULT '0000-000-00',
				num_action	int(10) not null default 0,
				num_note_parent	int(10) not null default 0,
				PRIMARY KEY  (id_note),
				KEY i_date_note(date_note),
				KEY i_num_action(num_action),
				KEY i_num_note_parent(num_note_parent)
				)";
		echo traite_rqt($rqt,"CREATE TABLE demandes_notes") ;
	
		$rqt = " CREATE TABLE demandes_theme(
			id_theme int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			libelle_theme varchar(255) NOT NULL default '',
	        PRIMARY KEY  (id_theme)
			)";
		echo traite_rqt($rqt,"CREATE TABLE demandes_theme") ;
		
	
		$rqt = " CREATE TABLE demandes_type(
			id_type int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			libelle_type varchar(255) NOT NULL default '',
	        PRIMARY KEY  (id_type)
			)";
		echo traite_rqt($rqt,"CREATE TABLE demandes_type") ;
		
	
		$rqt = " CREATE TABLE demandes_users(
			num_user int(10) not null default 0,
			num_demande int(10) not null default 0,
			date_creation date not null default '0000-00-00',
			users_statut int(1) not null default 0,
			PRIMARY KEY (num_user,num_demande)
			)";
		echo traite_rqt($rqt,"CREATE TABLE demandes_users") ;
		
		$rqt = " CREATE TABLE explnum_doc_actions(
			num_explnum_doc int(10) NOT NULL default 0,
			num_action int(10) NOT NULL default 0,
			prive int(1) NOT NULL default 0,
			rapport int(1) NOT NULL default 0,
			num_explnum int(10) NOT NULL default 0,
			PRIMARY KEY(num_explnum_doc,num_action)
			)";
		echo traite_rqt($rqt,"CREATE TABLE explnum_doc_actions") ;
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.78");
		break;

	case "v4.78": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		
		$rqt = "CREATE TABLE rapport_demandes(
			id_item int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			contenu text not null default '',
			num_note int(10) not null default 0,
			num_demande int(10) not null default 0,
			ordre mediumint(3)  not null default 0,
			type mediumint(2) not null default 0,
			PRIMARY KEY(id_item))";
		echo traite_rqt($rqt,"CREATE TABLE rapport_demandes") ;
		
		$rqt="ALTER TABLE empr_statut ADD allow_dema TINYINT( 4 ) UNSIGNED NOT NULL DEFAULT 1 AFTER allow_sugg" ;
		echo traite_rqt($rqt,"ALTER TABLE empr_statut ADD allow_dema") ;
		
		// paramètre de gestion des titres uniformes
		// on initialise à 1 si $pmb_form_editables est à 1
		$pmb_use_uniform_title=$pmb_form_editables;
		$resnbtu=mysql_query("SELECT * FROM titres_uniformes");
		if (mysql_num_rows($resnbtu)) $pmb_use_uniform_title=1;
		$resnbgrilles=mysql_query("SELECT * FROM grilles");
		if (mysql_num_rows($resnbgrilles)) $pmb_use_uniform_title=1;
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='use_uniform_title' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'pmb', 'use_uniform_title', '".$pmb_use_uniform_title."', 'Utiliser les titres uniformes ? \n 0 : Non.\n 1 : Oui.', '',0) ";
			echo traite_rqt($rqt, "insert pmb_use_uniform_title=$pmb_use_uniform_title into parameters");
		}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='print_expl_default' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'opac', 'print_expl_default', '0', 'En impression de panier, imprimer les exemplaires est coché par défaut \n 0 : Non \n 1 : Oui', 'a_general', 0)";
			echo traite_rqt($rqt,"insert opac_print_expl_default='0' into parametres");
		}
		
		//Paramètres d'inclusion auto des notes dans le rapport
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'demandes' and sstype_param='include_note' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'demandes', 'include_note', '0', 'Inclure automatiquement les notes dans le rapport documentaire.', '',0) ";
			echo traite_rqt($rqt, "insert demandes_include_note=0 into parameters");
		}
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.79");
		break;

	case "v4.79": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		
		//	Ajout d'un champ notice unimarc dans les suggestions	
		$rqt="ALTER TABLE suggestions ADD notice_unimarc BLOB NOT NULL DEFAULT ''";
	 	echo traite_rqt($rqt,"alter suggestions add notice_unimarc") ;
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.80");
		break;

	case "v4.80": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='ie_reload_on_resize' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'opac', 'ie_reload_on_resize', '0', 'Recharger la page si l\'utilisateur redimensionne son navigateur (pb de CSS avec IE) ? \n 0: Non \n 1: Oui','a_general')";
			echo traite_rqt($rqt,"insert opac_ie_reload_on_resize=0 into parametres");
		}
		
		// Permet de mémoriser les exemplaires non traités lors d'un retour de prêt.(transfert et résa)
		$rqt="ALTER TABLE exemplaires ADD expl_retloc smallint(5) UNSIGNED NOT NULL DEFAULT 0 " ;
		echo traite_rqt($rqt,"ALTER TABLE exemplaires ADD expl_retloc") ;
		
		//Modification du type du champ explnum_data de explnum_doc
		$rqt="ALTER TABLE explnum_doc CHANGE explnum_doc_data explnum_doc_data mediumblob NOT NULL DEFAULT '' " ;
		echo traite_rqt($rqt,"ALTER TABLE explnum_doc CHANGE explnum_doc_data mediumblob") ;
	 	
 		//Parametre affichage des dates de creation et modification exemplaires
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='expl_show_dates' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'pmb', 'expl_show_dates', '0', 'Afficher les dates de création et de modification des exemplaires ? \n 0 : Non.\n 1 : Oui.', '',0) ";
			echo traite_rqt($rqt, "insert expl_show_dates=0 into parameters");
		}

		//parametres valeurs par defaut en modification de notice pour la gestion des droits d'acces utilisateurs - notices 
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'gestion_acces' and sstype_param='user_notice_def' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
				VALUES (0, 'gestion_acces', 'user_notice_def', '0', 'Valeur par défaut en modification de notice pour les droits d\'accès utilisateurs - notices \n 0 : Recalculer.\n 1 : Choisir.', '',0) ";
			echo traite_rqt($rqt, "insert gestion_acces_user_notice_def=0 into parameters");
		}

		//parametres valeur par defaut en modification de notice pour la gestion des droits d'acces emprunteurs - notices 
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'gestion_acces' and sstype_param='empr_notice_def' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
				VALUES (0, 'gestion_acces', 'empr_notice_def', '0', 'Valeur par défaut en modification de notice pour les droits d\'accès emprunteurs - notices \n 0 : Recalculer.\n 1 : Choisir.', '',0) ";
			echo traite_rqt($rqt, "insert gestion_acces_empr_notice_def=0 into parameters");
		}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_exemplaires_analysis' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'opac', 'show_exemplaires_analysis', '0', 'Afficher les exemplaires du bulletin sous l\'article affiché ? \n 0: Non \n 1: Oui','e_aff_notice')";
			echo traite_rqt($rqt,"insert opac_show_exemplaires_analysis=0 into parametres");
		}
		
		//paramètres pour afficher l'id de la notice dans le detail de la notice
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='show_notice_id' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'pmb', 'show_notice_id', '0', 'Afficher l\'identifiant de la notice dans le descriptif ? \n 0 : Non.\n 1 : Oui. \n 1,X : Oui avec préfixe X', '',0) ";
			echo traite_rqt($rqt, "insert pmb_show_notice_id=0 into parameters");
		}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='section_notices_order' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) VALUES (0, 'opac', 'section_notices_order', ' index_serie, tnvol, index_sew ', 'Ordre d\'affichage des notices dans les sections dans l\'opac \n  index_serie, tnvol, index_sew : tri par titre de série et titre ','k_section')";
			echo traite_rqt($rqt,"insert opac_section_notices_order=' index_serie, tnvol, index_sew ' into parametres");
		}
		
		//Parametre pour l'affichage d'un onglet aide et d'un lien dans la barre de navigation
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_onglet_help' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,section_param) 
					VALUES (0, 'opac', 'show_onglet_help', '0', 'Afficher l\'onglet HELP avec les onglets de recherche affichant l\'infopage et un lien vers l\'infopage dans la barre de navigation \n 0 : Non.\n ## : id de l\'infopage. \n','f_modules')";
			echo traite_rqt($rqt,"insert opac_show_onglet_help='0' into parametres");
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='navig_empr' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'opac', 'navig_empr', '0', 'Afficher l\'onglet \"Votre compte\" dans la barre de navigation de l\'Opac ? \n 0 : Non \n 1 : Oui', '', '0')";
			echo traite_rqt($rqt,"insert opac_navig_empr='0' into parametres ");
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='confirm_resa' "))==0){
			$rqt="INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
			  		VALUES (NULL, 'opac', 'confirm_resa', '0', 'Demander la confirmation sur la réservation d\'un exemplaire en Opac ? \n 0 : Non \n 1 : Oui', '', '0')";
			echo traite_rqt($rqt,"insert opac_confirm_resa='0' into parametres ");
		}
		
		//Ajout d'une colonne générique dans statopac et logopac
		$rqt="ALTER TABLE logopac ADD gen_stat BLOB NOT NULL DEFAULT '' " ;
		echo traite_rqt($rqt,"ALTER TABLE logopac ADD gen_stat") ;
		
		$rqt="ALTER TABLE statopac ADD gen_stat BLOB NOT NULL DEFAULT '' " ;
		echo traite_rqt($rqt,"ALTER TABLE statopac ADD gen_stat") ;
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.81");
		break;

	case "v4.81": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		
		//Index connecteurs extérieurs
		$rqt="ALTER TABLE es_cache_int drop index cache_index";
		echo traite_rqt($rqt,"ALTER TABLE es_cache_int drop index cache_index") ;
		$rqt="alter table es_cache_int add index cache_index(es_cache_owner,es_cache_objectformat,es_cache_objecttype); " ;
		echo traite_rqt($rqt,"alter table es_cache_int add index cache_index") ;
		
		//Création de la cote en ajax
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='prefill_cote_ajax' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'prefill_cote_ajax', '', 'Script personnalisé de construction de la cote de l\'exemplaire en ajax')";
			echo traite_rqt($rqt,"insert pmb_prefill_cote_ajax='' into parametres");
		}
		
		//Masquer les infos de localisation dans l'entête
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='hide_biblioinfo_letter' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'hide_biblioinfo_letter', '0', 'Masquer les informations de localisation dans l\'entête des lettres (pour les bibliothèques possédant du papier à entête)')";
			echo traite_rqt($rqt,"insert pmb_hide_biblioinfo_letter=0 into parametres");
		}
		
		//Code lecteur + email en position absolue
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='lettres_code_mail_position_absolue' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'lettres_code_mail_position_absolue', '0 100 6', 'Placer le code lecteur et le mail selon des coordonnées absolues.\n activé x y \n activé : activer cette fonction (valeurs: 0/1) \n x : Position horizontale \n y : Position verticale')";
			echo traite_rqt($rqt,"insert pmb_lettres_code_mail_position_absolue='0 100 6' into parametres");
		}
		
		//Ajout d'un statut d'emprunteur pour les listes de lecture
		$rqt = "ALTER TABLE empr_statut ADD allow_liste_lecture TINYINT(4) UNSIGNED DEFAULT 0 NOT NULL ";
		echo traite_rqt($rqt,"alter table empr_statut add allow_liste_lecture=0 ");
		
		//Modification de la valeur du paramètre pour la taille du bloc d'exemplaire pour les lettres de résa (conséquence de l'ajout d'infos d'exemplaire)
		$rqt = "UPDATE parametres set valeur_param=20 where type_param='pdflettreresa' and sstype_param='taille_bloc_expl' and valeur_param='16' ";
		echo traite_rqt($rqt,"UPDATE parametres set valeur_param=20 where type_param='pdflettreresa' and sstype_param='taille_bloc_expl'");
		
		//Paramètres pour définir un statut permettant de restreindre les droits d'origine d'un emprunteur dont l'abonnement est dépassé
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='adhesion_expired_status' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param) VALUES (0, 'opac', 'adhesion_expired_status','0','Id du statut permettant de restreindre les droits des emprunteurs dont l\'abonnement est dépassé. \n\rPMB fera un AND logique avec les droits d\'origine.','a_general')";
			echo traite_rqt($rqt,"insert opac_adhesion_expired_status=0 into parametres");
		}
		
		//On réaffecte les paramètres mal classés de l'OPAC à la bonne section		
		$rqt="UPDATE parametres set section_param='a_general' where type_param='opac' and sstype_param='navig_empr'";
		echo traite_rqt($rqt,"UPDATE parametres set section_param='a_general' where type_param='opac' and sstype_param='navig_empr' ");
		$rqt="UPDATE parametres set section_param='a_general' where type_param='opac' and sstype_param='confirm_resa'";
		echo traite_rqt($rqt,"UPDATE parametres set section_param='a_general' where type_param='opac' and sstype_param='confirm_resa' ");
		$rqt="UPDATE parametres set section_param='a_general' where type_param='opac' and sstype_param='adhesion_expired_status'";
		echo traite_rqt($rqt,"UPDATE parametres set section_param='a_general' where type_param='opac' and sstype_param='adhesion_expired_status' ");
		
				
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.82");
		break;

	case "v4.82": 
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		
		// on initialise à 1 si $pmb_form_editables est à 1
		if ($pmb_form_editables) {
			$rqt = "update parametres set valeur_param='1' where type_param='pmb' and sstype_param='use_uniform_title' ";
			echo traite_rqt($rqt, "update pmb_use_uniform_title=1 if pmb_use_uniform_title=1 ");
		}
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.83");
		break;

	case "v4.83":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		//Paramètres pour définir l'action par défaut à effectuer lors d'un retour si il y a demande de résa
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='resa_retour_action_defaut' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param) VALUES (0, 'pmb', 'resa_retour_action_defaut','0','Définit l\'action par défaut à effectuer lors d\'un retour si le document est réservé.\n0, Valider la réservation.\n1, A traiter plus tard.','')";
			echo traite_rqt($rqt,"insert pmb_resa_retour_action_defaut=0 into parametres");
		}
		
		//Paramètres pour définir le format d'affichage des notices filles
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='notice_fille_format' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param) VALUES (0, 'pmb', 'notice_fille_format','0','Affichage des notices filles \n 0: avec leurs détails (notice dépliable avec un plus) \n 1: Juste l\'entête','')";
			echo traite_rqt($rqt,"insert pmb_notice_fille_format=0 into parametres");
		}
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.84");
		break;

	case "v4.84":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+

		//Ajout des champs d'origine pour les actions et les notes
		$rqt = "ALTER TABLE demandes_actions ADD actions_num_user TINYINT(4) UNSIGNED DEFAULT 0 NOT NULL ";
		echo traite_rqt($rqt,"alter table demandes_actions add actions_num_user default 0 ");
		$rqt = "ALTER TABLE demandes_actions ADD actions_type_user TINYINT(4) UNSIGNED DEFAULT 0 NOT NULL ";
		echo traite_rqt($rqt,"alter table demandes_actions add actions_type_user default 0 ");

		$rqt = "ALTER TABLE demandes_notes ADD notes_num_user TINYINT(4) UNSIGNED DEFAULT 0 NOT NULL ";
		echo traite_rqt($rqt,"alter table demandes_notes add notes_num_user default 0 ");
		$rqt = "ALTER TABLE demandes_notes ADD notes_type_user TINYINT(4) UNSIGNED DEFAULT 0 NOT NULL ";
		echo traite_rqt($rqt,"alter table demandes_notes add notes_type_user default 0 ");
		
		$rqt="alter table demandes_actions drop index i_actions_user" ;
		echo traite_rqt($rqt,"alter table demandes_actions drop index i_actions_user") ;
		$rqt="alter table demandes_actions add index i_actions_user(actions_num_user,actions_type_user) " ;
		echo traite_rqt($rqt,"alter table demandes_actions add index i_actions_user") ;
		
		$rqt="alter table demandes_notes drop index i_notes_user " ;
		echo traite_rqt($rqt,"alter table demandes_notes drop index i_notes_user") ;
		$rqt="alter table demandes_notes add index i_notes_user(notes_num_user,notes_type_user) " ;
		echo traite_rqt($rqt,"alter table demandes_notes add index i_notes_user") ;
		
		$rqt = "ALTER TABLE demandes_actions MODIFY temps_passe FLOAT ";
		echo traite_rqt($rqt,"alter table demandes_actions MODIFY temps_passe FLOAT ");
		
		$rqt = "ALTER TABLE demandes_actions ADD actions_read int(1) not null default 0 ";
		echo traite_rqt($rqt,"alter table demandes_actions ADD actions_read ");
		
		$rqt = "ALTER TABLE explnum_doc ADD explnum_doc_url TEXT not null default '' ";
		echo traite_rqt($rqt,"ALTER TABLE explnum_doc ADD explnum_doc_url ");
		
		$rqt = "alter table users change speci_coordonnees_etab speci_coordonnees_etab mediumtext not null default '' ";
		echo traite_rqt($rqt,"alter table users change speci_coordonnees_etab default '' ");
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.85");
		break;


	case "v4.85":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		
		//Ajout du champ resa_trans pour associer un transfert à une résa
		$rqt = "ALTER TABLE transferts_demande ADD resa_trans int(8) UNSIGNED NOT NULL DEFAULT 0 ";
		echo traite_rqt($rqt,"alter table transferts_demande add resa_trans ");	
		
		$rqt = "ALTER TABLE suggestions_origine DROP PRIMARY KEY, ADD PRIMARY KEY(origine,num_suggestion,type_origine)";
		echo traite_rqt($rqt,"ALTER TABLE suggestions_origine DROP PRIMARY KEY, ADD PRIMARY KEY(origine,num_suggestion,type_origine)");
		
		//Masquer le message d'erreur en retour de prêt d'un document issu d'une autre localisation
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='hide_retdoc_loc_error' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'hide_retdoc_loc_error', '0', 'Masquer le message d\'erreur en retour de prêt d\'un document issu d\'une autre localisation')";
			echo traite_rqt($rqt,"insert pmb_hide_retdoc_loc_error=0 into parametres");
		}

		$rqt = "alter table pret_archive drop index i_pa_arc_empr_categ";
		echo traite_rqt($rqt,"alter table pret_archive drop index i_pa_arc_empr_categ");
		$rqt = "alter table pret_archive add index i_pa_arc_empr_categ(arc_empr_categ)";
		echo traite_rqt($rqt,"alter table pret_archive add index i_pa_arc_empr_categ");

		$rqt = "alter table pret_archive drop index i_pa_arc_expl_location";
		echo traite_rqt($rqt,"alter table pret_archive drop index i_pa_arc_expl_location");
		$rqt = "alter table pret_archive add index i_pa_arc_expl_location(arc_expl_location)";
		echo traite_rqt($rqt,"alter table pret_archive add index i_pa_arc_expl_location");

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.86");
		break;

	case "v4.86":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+

		//Activation de la gestion de borne de prêt
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='selfservice_allow' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) 
					VALUES (0, 'pmb', 'selfservice_allow', '0', 'Activer de la gestion de la borne de prêt?\n0 : Non. \n1 : Oui.')";
			echo traite_rqt($rqt,"insert pmb_selfservice_allow=0 into parametres");
		}
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'selfservice' and sstype_param='loc_autre_todo' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'selfservice', 'loc_autre_todo', '0', '1', 'Action à effectuer si le document est issu d\'une autre localisation') ";
			echo traite_rqt($rqt,"INSERT selfservice_loc_autre_todo INTO parametres") ;
		}
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'selfservice' and sstype_param='loc_autre_todo_msg' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'selfservice', 'loc_autre_todo_msg', '', '1', 'Message si le document est réservé sur une autre localisation') ";
			echo traite_rqt($rqt,"INSERT selfservice_loc_autre_todo_msg INTO parametres") ;
		}		
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'selfservice' and sstype_param='resa_ici_todo' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'selfservice', 'resa_ici_todo', '0', '1', 'Action à effectuer si le document est réservé sur cette localisation') ";
			echo traite_rqt($rqt,"INSERT selfservice_resa_ici_todo INTO parametres") ;
		}
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'selfservice' and sstype_param='resa_ici_todo_msg' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'selfservice', 'resa_ici_todo_msg', '', '1', 'Message si le document est réservé sur cette localisation') ";
			echo traite_rqt($rqt,"INSERT selfservice_resa_ici_todo_msg INTO parametres") ;
		}
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'selfservice' and sstype_param='resa_loc_todo' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'selfservice', 'resa_loc_todo', '0', '1', 'Action à effectuer si le document est réservé sur une autre localisation') ";
			echo traite_rqt($rqt,"INSERT selfservice_resa_loc_todo INTO parametres") ;
		}		
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'selfservice' and sstype_param='resa_loc_todo_msg' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'selfservice', 'resa_loc_todo_msg', '', '1', 'Message si le document est réservé sur une autre localisation') ";
			echo traite_rqt($rqt,"INSERT selfservice_resa_loc_todo_msg INTO parametres") ;
		}		
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'selfservice' and sstype_param='retour_retard_msg' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'selfservice', 'retour_retard_msg', '', '1', 'Message si le document est rendu en retard') ";
			echo traite_rqt($rqt,"INSERT selfservice_retour_retard_msg INTO parametres") ;
		}		
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'selfservice' and sstype_param='retour_blocage_msg' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'selfservice', 'retour_blocage_msg', '', '1', 'Message si le document est rendu en retard avec blocage') ";
			echo traite_rqt($rqt,"INSERT selfservice_retour_blocage_msg INTO parametres") ;
		}		
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'selfservice' and sstype_param='retour_amende_msg' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'selfservice', 'retour_amende_msg', '', '1', 'Message si le document est rendu en retard avec amende') ";
			echo traite_rqt($rqt,"INSERT selfservice_retour_amende_msg INTO parametres") ;
		}
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'selfservice' and sstype_param='pret_carte_invalide_msg' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'selfservice', 'pret_carte_invalide_msg', 'Votre carte n\'est pas valide !', '1', 'Message borne de prêt: Votre carte n\'est pas valide !') ";
			echo traite_rqt($rqt,"INSERT selfservice_pret_carte_invalide_msg INTO parametres") ;
		}		
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'selfservice' and sstype_param='pret_pret_interdit_msg' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'selfservice', 'pret_pret_interdit_msg', 'Vous n\'êtes pas autorisé à emprunter !', '1', 'Message borne de prêt: Vous n\'êtes pas autorisé à emprunter !') ";
			echo traite_rqt($rqt,"INSERT selfservice_pret_pret_interdit_msg INTO parametres") ;
		}
		
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'selfservice' and sstype_param='pret_deja_prete_msg' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'selfservice', 'pret_deja_prete_msg', 'Document déjà prêté ! allez le signaler !', '1', 'Message borne de prêt: Document déjà prêté ! allez le signaler !') ";
			echo traite_rqt($rqt,"INSERT selfservice_pret_deja_prete_msg INTO parametres") ;
		}
		
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'selfservice' and sstype_param='pret_deja_prete_msg' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'selfservice', 'pret_deja_prete_msg', 'Document déjà prêté ! allez le signaler !', '1', 'Message borne de prêt: Document déjà prêté ! allez le signaler !') ";
			echo traite_rqt($rqt,"INSERT selfservice_pret_deja_prete_msg INTO parametres") ;
		}
		
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'selfservice' and sstype_param='pret_deja_reserve_msg' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'selfservice', 'pret_deja_reserve_msg', 'Vous ne pouvez pas emprunter ce document', '1', 'Message borne de prêt: Vous ne pouvez pas emprunter ce document') ";
			echo traite_rqt($rqt,"INSERT selfservice_pret_deja_reserve_msg INTO parametres") ;
		}
		
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'selfservice' and sstype_param='pret_quota_bloc_msg' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'selfservice', 'pret_quota_bloc_msg', 'Vous ne pouvez pas emprunter ce document', '1', 'Message borne de prêt: Vous ne pouvez pas emprunter ce document') ";
			echo traite_rqt($rqt,"INSERT selfservice_pret_quota_bloc_msg INTO parametres") ;
		}
		
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'selfservice' and sstype_param='pret_non_pretable_msg' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'selfservice', 'pret_non_pretable_msg', 'Ce document n\'est pas prêtable', '1', 'Message borne de prêt: Ce document n\'est pas prêtable') ";
			echo traite_rqt($rqt,"INSERT selfservice_pret_non_pretable_msg INTO parametres") ;
		}
		
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'selfservice' and sstype_param='pret_expl_inconnu_msg' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'selfservice', 'pret_expl_inconnu_msg', 'Ce document est inconnu', '1', 'Message borne de prêt: Ce document est inconnu') ";
			echo traite_rqt($rqt,"INSERT selfservice_pret_expl_inconnu_msg INTO parametres") ;
		}
		
		if (mysql_num_rows(mysql_query("SELECT 1 FROM parametres WHERE type_param= 'selfservice' and sstype_param='pret_prolonge_non_msg' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, gestion, comment_param)
					VALUES (0, 'selfservice', 'pret_prolonge_non_msg', 'Le prêt ne peut être prolongé', '1', 'Message borne de prêt: Le prêt ne peut être prolongé') ";
			echo traite_rqt($rqt,"INSERT selfservice_pret_prolonge_non_msg INTO parametres") ;
		}
		
		//Paramètres pour afficher les résultats en mode phototèque
		//on supprime cette mise à jour, on la vire dans la version 4.88, la visionneuse la remplace....
		//		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='photo_result_to_phototheque' "))==0) {
		//			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param) 
		//				VALUES (0,'opac', 'photo_result_to_phototheque','0','Afficher le résultat d\'une recherche (liste des documents numériques associés aux notices résultats) en mode photothèque','m_photo')";
		//			echo traite_rqt($rqt,"insert opac_photo_result_to_phototheque=0 into parametres");
		//		}
		//Paramètres pour filtrer le type de documents à afficher en mode phototèque
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='photo_filtre_mimetype' "))==0) {
			$rqt = "INSERT INTO parametres (id_param, type_param,sstype_param, valeur_param, comment_param, section_param) 
				VALUES (0,'opac', 'photo_filtre_mimetype','','Liste des mimetypes utilisés pour l\'affichage des résultats en mode photothèque séparés par une virgule et entre cotes (ex:\'application/pdf\',\'image/png\')','m_photo')";
			echo traite_rqt($rqt,"insert opac_photo_filtre_mimetype='' into parametres");
		}

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.89");
		break;


	case "v4.90":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
	
		$rqt = "alter table bannette_contenu drop KEY i_num_notice ";
		echo traite_rqt($rqt,"drop index bannette_contenu i_num_notice ") ; 
		$rqt = "alter table bannette_contenu add KEY i_num_notice (num_notice) ";
		echo traite_rqt($rqt,"create index bannette_contenu i_num_notice ") ; 

		$rqt = "alter table es_cache_blob drop index cache_index ";
		echo traite_rqt($rqt,"alter table es_cache_blob drop index cache_index ") ; 
		$rqt = "alter table es_cache_blob add index cache_index (es_cache_owner,es_cache_objectformat,es_cache_objecttype) ";
		echo traite_rqt($rqt,"alter table es_cache_blob add index cache_index ") ; 
		
		// Gestion sms
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'empr' and sstype_param='sms_activation' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) 
					VALUES (0, 'empr', 'sms_activation', '0', 'Activation de l\'envoi de sms. \n 0: Inactif \n 1: Actif')";
			echo traite_rqt($rqt,"insert empr_sms_activation='0' into parametres");
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'empr' and sstype_param='sms_config' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) 
					VALUES (0, 'empr', 'sms_config', '', 'Paramétrage de l\'envoi de sms. \nUsage:\n class_name=nom_de_la_classe;param_connection;\nExemple:\n class_name=smstrend;login=xxxx@sigb.net;password=xxxx;tpoa=xxxx;')";
			echo traite_rqt($rqt,"insert empr_sms_config='' into parametres");
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'empr' and sstype_param='sms_msg_resa_dispo' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) 
					VALUES (0, 'empr', 'sms_msg_resa_dispo', 'Bonjour,\nUn document réservé est disponible.\nConsultez votre compte!', 'Texte du sms envoyé lors de la validation d\'une réservation')";
			echo traite_rqt($rqt,"insert empr_sms_msg_resa_dispo into parametres");
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'empr' and sstype_param='sms_msg_resa_suppr' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) 
					VALUES (0, 'empr', 'sms_msg_resa_suppr', 'Bonjour,\nUne réservation est supprimée.\nConsultez votre compte!', 'Texte du sms envoyé lors de la suppression d\'une réservation')";
			echo traite_rqt($rqt,"insert empr_sms_msg_resa_suppr into parametres");
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'empr' and sstype_param='sms_msg_retard' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) 
					VALUES (0, 'empr', 'sms_msg_retard', 'Bonjour,\nVous avez un ou plusieurs document(s) en retard.\nConsultez votre compte!', 'Texte du sms envoyé lors de la suppression d\'une réservation')";
			echo traite_rqt($rqt,"insert empr_sms_msg_retard into parametres");
		}		
		$rqt = "ALTER TABLE empr ADD empr_sms INT(1) unsigned NOT NULL DEFAULT 0";
		echo traite_rqt($rqt,"ALTER TABLE empr ADD empr_sms ") ;

		//Création des tables pour la gestion de l'historique des relances
		$rqt = "CREATE TABLE log_retard(
			id_log INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			date_log TIMESTAMP NOT NULL,
			niveau_reel INT(1) NOT NULL default 0,
			niveau_suppose INT(1) NOT NULL default 0,
			amende_totale decimal(16,2) NOT NULL default 0,	
			frais decimal(16,2) NOT NULL default 0,
			idempr INT(11) NOT NULL default 0 )";
		echo traite_rqt($rqt,"CREATE TABLE log_retard ") ; 
		$rqt = "CREATE TABLE log_expl_retard(
			id_log INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			date_log TIMESTAMP NOT NULL ,
			titre VARCHAR(255) NOT NULL default '',
			expl_id INT(11) NOT NULL default 0,
			expl_cb VARCHAR(255) NOT NULL default '',
			date_pret date NOT NULL default '0000-00-00',
			date_retour date NOT NULL default '0000-00-00',
			amende decimal(16,2) NOT NULL default 0,	
			num_log_retard INT(11) NOT NULL default 0 )";
		echo traite_rqt($rqt,"CREATE TABLE log_expl_retard ") ;
	
		//Client du serveur de procédures externes:
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='procedure_server_address' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) 
					VALUES (0, 'pmb', 'procedure_server_address', '', 'Adresse du serveur de procédures distances.')";
			echo traite_rqt($rqt,"insert procedure_server_address='' into parametres");
		}

		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='procedure_server_credentials' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) 
					VALUES (0, 'pmb', 'procedure_server_credentials', '', 'Autentification sur le serveur de procédures distantes.\n1ère ligne: email\n2ème ligne: mot de passe.')";
			echo traite_rqt($rqt,"insert procedure_server_credentials='' into parametres");
		}

 		$rqt = "ALTER TABLE docsloc_section ADD num_pclass int(10) not null default 0";
		echo traite_rqt($rqt,"alter table docsloc_section ADD num_pclass ");
		$requete="SELECT id_pclass FROM pclassement";
		$res=mysql_query($requete,$dbh);
		if(mysql_num_rows($res) == 1) {
			$requete="UPDATE docsloc_section SET num_pclass='".mysql_result($res,0,0)."' WHERE num_pclass='0'";
			mysql_query($requete,$dbh);
		} elseif (!$thesaurus_classement_mode_pmb) {
			$requete="UPDATE docsloc_section SET num_pclass='".$thesaurus_classement_defaut."' WHERE num_pclass='0'";
			mysql_query($requete,$dbh);
		}
		
		$rqt = " CREATE TABLE explnum_location(
			num_explnum int(10) NOT NULL default 0,
			num_location int(10) NOT NULL default 0,
			PRIMARY KEY(num_explnum,num_location)
			)";
		echo traite_rqt($rqt,"CREATE TABLE explnum_location") ;
		
		// Ajout d'un 2eme mode de prêt RFID
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='rfid_pret_mode' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) 
					VALUES (0, 'pmb', 'rfid_pret_mode', '0', 'Mode de fonctionnement du prêt:\n 0: Un document retiré de la platine est retiré du prêt.\n 1: Un document retiré de la platine est conservé pour faciliter le prêt de nombreux documents. ')";
			echo traite_rqt($rqt,"insert pmb_rfid_pret_mode into parametres");
		}	

		// Création des liens entre les autorités
		$rqt = "CREATE TABLE aut_link(
			aut_link_from INT( 2 ) NOT NULL default 0 ,
			aut_link_from_num INT( 11 ) NOT NULL default 0 ,
			aut_link_to INT( 2 ) NOT NULL default 0 ,
			aut_link_to_num INT( 11 ) NOT NULL default 0 ,
			aut_link_type INT(2) NOT NULL default 0,
			aut_link_reciproc INT(1) NOT NULL default 0,
			aut_link_comment VARCHAR(255) NOT NULL default '',
			PRIMARY KEY(aut_link_from, aut_link_from_num, aut_link_to, aut_link_to_num, aut_link_type) )";
		echo traite_rqt($rqt,"CREATE TABLE aut_link "); 
	
		//Module fiches 
		$rqt = "update parametres set type_param='fiches' where type_param='fichier' and sstype_param='active' ";
		echo traite_rqt($rqt, "update fiches_active into parameters (previous error)");
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'fiches' and sstype_param='active' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'fiches', 'active', '0', 'Module \'fiches\' activé.\n 0 : Non.\n 1 : Oui.', '',0) ";
			echo traite_rqt($rqt, "insert fiches_active=0 into parameters");
		}
		$rqt = "CREATE TABLE fiche(
			id_fiche int(10) unsigned NOT NULL auto_increment, 
			infos_global text NOT NULL,
			index_infos_global text NOT NULL,
			PRIMARY KEY (id_fiche)
		)";
		echo traite_rqt($rqt,"create table fiche ");
		
		//Création des champs persos de l'onglet fichier
		$rqt = "CREATE TABLE gestfic0_custom (
			idchamp int(10) unsigned NOT NULL auto_increment, 
			name varchar(255) NOT NULL default '', 
			titre varchar(255) default NULL, 
			type varchar(10) NOT NULL default 'text', 			
			datatype varchar(10) NOT NULL default '', 			
			options text, multiple int(11) NOT NULL default '0', 
			obligatoire int(11) NOT NULL default '0', 
			ordre int(11) default NULL, 
			search INT(1) unsigned NOT NULL DEFAULT 0,
			export INT(1) unsigned NOT NULL DEFAULT 0,
			exclusion_obligatoire INT(1) unsigned NOT NULL DEFAULT 0,
			PRIMARY KEY  (idchamp)) ";
		echo traite_rqt($rqt,"create table gestfic0_custom ");
		$rqt = "CREATE TABLE gestfic0_custom_lists (
			gestfic0_custom_champ int(10) unsigned NOT NULL default '0',
			gestfic0_custom_list_value varchar(255) default NULL, 
			gestfic0_custom_list_lib varchar(255) default NULL, 
			ordre int(11) default NULL, 
			KEY gestfic0_custom_champ (gestfic0_custom_champ), 
			KEY gestfic0_champ_list_value (gestfic0_custom_champ,gestfic0_custom_list_value)) " ;
		echo traite_rqt($rqt,"create table gestfic0_custom_lists ");
		$rqt = "CREATE TABLE gestfic0_custom_values (
			gestfic0_custom_champ int(10) unsigned NOT NULL default '0', 
			gestfic0_custom_origine int(10) unsigned NOT NULL default '0', 
			gestfic0_custom_small_text varchar(255) default NULL, 
			gestfic0_custom_text text, 
			gestfic0_custom_integer int(11) default NULL, 
			gestfic0_custom_date date default NULL, 
			gestfic0_custom_float float default NULL, 
			KEY gestfic0_custom_champ (gestfic0_custom_champ), 
			KEY gestfic0_custom_origine (gestfic0_custom_origine)) " ;
		echo traite_rqt($rqt,"create table gestfic0_custom_values ");
		
		//Module Visionneuse
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='visionneuse_allow' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'opac', 'visionneuse_allow', '0', 'Visionneuse activée.\n 0 : Non.\n 1 : Oui.', 'm_photo',0) ";
			echo traite_rqt($rqt, "insert visionneuse_allows=0 into parameters");
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='photo_result_to_phototheque' "))){
			$rqt = "DELETE FROM parametres WHERE type_param= 'opac' and sstype_param='photo_result_to_phototheque' ";
			echo traite_rqt($rqt, "delete phototheque from parameters");
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='visionneuse_params' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'opac', 'visionneuse_params', '', 'tableau de correspondance mimetype=>class','m_photo',1) ";
			echo traite_rqt($rqt, "insert visionneuse_params into parameters");
		}

	 	//suppression parametres obsoletes opac_authors_aut_sort_records & opac_authors_rec_per_page
		$rqt = "delete from parametres where type_param='opac' and sstype_param='authors_aut_sort_records' " ;
		echo traite_rqt($rqt,"delete opac_authors_aut_sort_records from parametres") ;
		$rqt = "delete from parametres where type_param='opac' and sstype_param='opac_authors_rec_per_page' " ;
		echo traite_rqt($rqt,"delete opac_authors_rec_per_page from parametres") ;
		//correction libelle parametre pmb_resa_retour_action_defaut
		$rqt = "update parametres set comment_param='Définit l\'action par défaut à effectuer lors d\'un retour si le document est réservé.\n0, A traiter plus tard.\n1, Valider la réservation.' where type_param='pmb' and sstype_param='resa_retour_action_defaut' ";
		echo traite_rqt($rqt,"update parametre pmb_resa_retour_action_defaut");
		//correction libelle parametre opac_avis_nb_max
		$rqt = "update parametres set comment_param='Nombre maximal de commentaires conservés par notice. Les plus vieux sont effacés au profit des plus récents quand ce nombre est atteint.' where type_param='opac' and sstype_param='avis_nb_max' ";
		echo traite_rqt($rqt,"update parametre opac_avis_nb_max");
		//correction libelle parametre opac_modules_search_abstract
		$rqt = "update parametres set comment_param='Recherche simple dans le champ résumé :\n 0 : interdite\n 1 : autorisée\n 2 : autorisée et validée par défaut\n -1 : également interdite en recherche multi-critères' where type_param='opac' and sstype_param='modules_search_abstract' ";
		echo traite_rqt($rqt,"update parametre opac_modules_search_abstract");
		//correction libelle parametre opac_modules_search_all
		$rqt = "update parametres set comment_param='Recherche simple dans l\'ensemble des champs :\n 0 : interdite\n 1 : autorisée\n 2 : autorisée et validée par défaut\n -1 : également interdite en recherche multi-critères' where type_param='opac' and sstype_param='modules_search_all' ";
		echo traite_rqt($rqt,"update parametre opac_modules_search_all");	 
		//correction libelle parametre opac_modules_search_author
		$rqt = "update parametres set comment_param='Recherche simple dans les auteurs :\n 0 : interdite\n 1 : autorisée\n 2 : autorisée et validée par défaut\n -1 : également interdite en recherche multi-critères' where type_param='opac' and sstype_param='modules_search_author' ";
		echo traite_rqt($rqt,"update parametre opac_modules_search_author");
		//correction libelle parametre opac_modules_search_category
		$rqt = "update parametres set comment_param='Recherche simple dans les catégories :\n 0 : interdite\n 1 : autorisée\n 2 : autorisée et validée par défaut\n -1 : également interdite en recherche multi-critères' where type_param='opac' and sstype_param='modules_search_category' ";
		echo traite_rqt($rqt,"update parametre opac_modules_search_category");
		//correction libelle parametre opac_modules_search_collection
		$rqt = "update parametres set comment_param='Recherche simple dans les collections :\n 0 : interdite\n 1 : autorisée\n 2 : autorisée et validée par défaut\n -1 : également interdite en recherche multi-critères' where type_param='opac' and sstype_param='modules_search_collection' ";
		echo traite_rqt($rqt,"update parametre opac_modules_search_collection");
		//correction libelle parametre opac_modules_search_indexint
		$rqt = "update parametres set comment_param='Recherche simple dans les indexations décimales :\n 0 : interdite\n 1 : autorisée\n 2 : autorisée et validée par défaut\n -1 : également interdite en recherche multi-critères' where type_param='opac' and sstype_param='modules_search_indexint' ";
		echo traite_rqt($rqt,"update parametre opac_modules_search_indexint");
		//correction libelle parametre opac_modules_search_keywords
		$rqt = "update parametres set comment_param='Recherche simple dans les indexations libres (mots-clés) :\n 0 : interdite\n 1 : autorisée\n 2 : autorisée et validée par défaut\n -1 : également interdite en recherche multi-critères' where type_param='opac' and sstype_param='modules_search_keywords' ";
		echo traite_rqt($rqt,"update parametre opac_modules_search_keywords");
		//correction libelle parametre opac_modules_search_publisher
		$rqt = "update parametres set comment_param='Recherche simple dans les éditeurs :\n 0 : interdite\n 1 : autorisée\n 2 : autorisée et validée par défaut\n -1 : également interdite en recherche multi-critères' where type_param='opac' and sstype_param='modules_search_publisher' ";
		echo traite_rqt($rqt,"update parametre opac_modules_search_publisher");	  
		//correction libelle parametre opac_modules_search_subcollection
		$rqt = "update parametres set comment_param='Recherche simple dans les sous-collections :\n 0 : interdite\n 1 : autorisée\n 2 : autorisée et validée par défaut\n -1 : également interdite en recherche multi-critères' where type_param='opac' and sstype_param='modules_search_subcollection' ";
		echo traite_rqt($rqt,"update parametre opac_modules_search_subcollection");
		//correction libelle parametre opac_modules_search_title
		$rqt = "update parametres set comment_param='Recherche simple dans les titres :\n 0 : interdite\n 1 : autorisée\n 2 : autorisée et validée par défaut\n -1 : également interdite en recherche multi-critères' where type_param='opac' and sstype_param='modules_search_title' ";
		echo traite_rqt($rqt,"update parametre opac_modules_search_title");
		//correction libelle parametre opac_modules_search_titre_uniforme
		$rqt = "update parametres set comment_param='Recherche simple dans les titres uniformes :\n 0 : interdite\n 1 : autorisée\n 2 : autorisée et validée par défaut\n -1 : également interdite en recherche multi-critères' where type_param='opac' and sstype_param='modules_search_titre_uniforme' ";
		echo traite_rqt($rqt,"update parametre opac_modules_search_titre_uniforme");
		
		//nouvelle table pour l'enregistrement des paramètres spécifiques à une classe d'affichage
		$rqt = "CREATE TABLE visionneuse_params (
			visionneuse_params_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			visionneuse_params_class VARCHAR( 255 ) NOT NULL DEFAULT '',
			visionneuse_params_parameters TEXT NOT NULL ,
			UNIQUE (
				visionneuse_params_class
			)
		)";
		echo traite_rqt($rqt,"create table visionneuse_params");
			
		$rqt = "ALTER TABLE procs ADD proc_notice_tpl int(2) unsigned  NOT NULL DEFAULT 0 ";		
		echo traite_rqt($rqt, "ALTER TABLE procs ADD proc_notice_tpl ");
		$rqt = "ALTER TABLE procs ADD proc_notice_tpl_field VARCHAR(255) NOT NULL default '' ";		
		echo traite_rqt($rqt, "ALTER TABLE procs ADD proc_notice_tpl_field ");
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='allow_self_checkout' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'opac','allow_self_checkout','0','Proposer de faire du prêt autonome dans l\'OPAC.\n 0 : Non.\n 1 : Autorise le prêt de document.\n 2 : Autorise le retour de document.\n 3 : Autorise le prêt et le retour de document.\n','a_general',0)" ;
			echo traite_rqt($rqt,"insert opac_allow_self_checkout into parametres") ;
		}
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='self_checkout_url_connector' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) 
					VALUES(0,'opac','self_checkout_url_connector','','URL du connecteur en gestion permettant d\'effectuer le prêt autonome.','a_general',0)" ;
			echo traite_rqt($rqt,"insert opac_self_checkout_url_connector into parametres") ;
		}
		
		$rqt = "ALTER TABLE empr_statut ADD allow_self_checkout TINYINT(4) UNSIGNED DEFAULT 0 NOT NULL ";
		echo traite_rqt($rqt,"alter table empr_statut add allow_self_checkout=0 ");
		$rqt = "ALTER TABLE empr_statut ADD allow_self_checkin TINYINT(4) UNSIGNED DEFAULT 0 NOT NULL ";
		echo traite_rqt($rqt,"alter table empr_statut add allow_self_checkin=0 ");
		
		//Ajout du recouvr_type (0: amende, 1: prix de l'exemplaire)
		$rqt = "ALTER TABLE recouvrements ADD recouvr_type int(2) UNSIGNED NOT NULL DEFAULT 0 ";
		echo traite_rqt($rqt,"alter table recouvrements add recouvr_type ");
		
		///Ajout de date_pret 
		$rqt = "ALTER TABLE recouvrements ADD date_pret datetime NOT NULL default '0000-00-00 00:00:00'";
		echo traite_rqt($rqt,"alter table recouvrements add date_pret ");
		//Ajout de date_relance1 
		$rqt = "ALTER TABLE recouvrements ADD date_relance1  datetime NOT NULL default '0000-00-00 00:00:00'";
		echo traite_rqt($rqt,"alter table recouvrements add date_relance1 ");
		//Ajout de date_relance2 
		$rqt = "ALTER TABLE recouvrements ADD date_relance2 datetime NOT NULL default '0000-00-00 00:00:00'";
		echo traite_rqt($rqt,"alter table recouvrements add date_relance2 ");
		//Ajout de date_relance3 
		$rqt = "ALTER TABLE recouvrements ADD date_relance3  datetime NOT NULL default '0000-00-00 00:00:00'";
		echo traite_rqt($rqt,"alter table recouvrements add date_relance3 ");
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'finance' and sstype_param='recouvrement_lecteur_statut' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,gestion) 
					VALUES (0, 'finance', 'recouvrement_lecteur_statut', '0', 'Mémorise le statut que prennent les lecteurs lors du passage en recouvrememnt', 1)";
			echo traite_rqt($rqt,"insert finance_recouvrement_lecteur_statut into parametres");
		}	
		$rqt = "CREATE TABLE cache_amendes (
			id_empr int(10) unsigned NOT NULL default 0,
			cache_date date not null default '0000-00-00', 
			data_amendes blob NOT NULL DEFAULT '',	
			key id_empr(id_empr) )" ;
		echo traite_rqt($rqt,"create table cache_amendes ");
		
		$rqt = "ALTER TABLE log_retard ADD log_printed int(1) unsigned NOT NULL default 0";
		echo traite_rqt($rqt,"alter table log_retard add log_printed ");		
		
		$rqt = "ALTER TABLE log_retard ADD log_mail int(1) unsigned NOT NULL default 0";
		echo traite_rqt($rqt,"alter table log_retard add log_mail ");		
				
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'internal' and sstype_param='emptylogstatopac' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param,gestion) 
					VALUES (0, 'internal', 'emptylogstatopac', '0', 'Paramètre interne, ne pas modifier\r\n =1 si vidage des logs en cours', 0)";
			echo traite_rqt($rqt,"insert internal_emptylogstatopac=0 into parametres");
		}	

		//Module fichier 
		$rqt = "update parametres set type_param='fiches' where type_param='fichier' and sstype_param='active' ";
		echo traite_rqt($rqt, "update fiches_active into parameters");
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'fiches' and sstype_param='active' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'fiches', 'active', '0', 'Module \'fiches\' activé.\n 0 : Non.\n 1 : Oui.', '',0) ";
			echo traite_rqt($rqt, "insert fiches_active=0 into parameters");
		}
				
		$rqt = "ALTER TABLE notice_tpl ADD notpl_show_opac int(1) unsigned NOT NULL default 0";
		echo traite_rqt($rqt,"alter table notice_tpl add notpl_show_opac ");		
				
		// Recherche autopostage
		
		$rqt = "ALTER TABLE categories ADD path_word_categ TEXT NOT NULL ";
		echo traite_rqt($rqt,"alter table categories add path_word_categ ");
		$rqt = "ALTER TABLE categories ADD index_path_word_categ TEXT NOT NULL ";
		echo traite_rqt($rqt,"alter table categories add index_path_word_categ ");		
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'thesaurus' and sstype_param='auto_postage_search' "))==0){
			$rqt = "INSERT INTO parametres ( type_param, sstype_param, valeur_param, comment_param,section_param,gestion) 
					VALUES ( 'thesaurus', 'auto_postage_search', '0', 'Activer l\'indexation des catégories mères et filles pour la recherche de notices. \n 0 non, \n 1 oui', 'i_categories', 0)";
			echo traite_rqt($rqt,"insert thesaurus_auto_postage_search=0 into parametres");			
		}	
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'thesaurus' and sstype_param='auto_postage_search_nb_descendant' "))==0){
			$rqt = "INSERT INTO parametres ( type_param, sstype_param, valeur_param, comment_param,section_param,gestion) 
					VALUES ( 'thesaurus', 'auto_postage_search_nb_descendant', '0', 'Nombre de niveaux de recherche de notices dans les catégories filles. \n *: illimité, \n n: nombre de niveaux', 'i_categories', 0)";
			echo traite_rqt($rqt,"insert thesaurus_auto_postage_search_nb_descendant=0 into parametres");
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'thesaurus' and sstype_param='auto_postage_search_nb_montant' "))==0){
			$rqt = "INSERT INTO parametres ( type_param, sstype_param, valeur_param, comment_param,section_param,gestion) 
					VALUES ( 'thesaurus', 'auto_postage_search_nb_montant', '0', 'Nombre de niveaux de recherche de notices dans les catégories mères. \n *: illimité, \n n: nombre de niveaux', 'i_categories', 0)";
			echo traite_rqt($rqt,"insert thesaurus_auto_postage_search_nb_montant=0 into parametres");
		}								
		
		// Agrandir les champs d'indexation des documents numériques
		$rqt="ALTER TABLE explnum CHANGE explnum_index_sew explnum_index_sew MEDIUMTEXT NOT NULL";  
		echo traite_rqt($rqt,"ALTER TABLE explnum CHANGE explnum_index_sew explnum_index_sew MEDIUMTEXT");
		
		$rqt="ALTER TABLE explnum CHANGE explnum_index_wew explnum_index_wew MEDIUMTEXT NOT NULL";  
		echo traite_rqt($rqt,"ALTER TABLE explnum CHANGE explnum_index_wew explnum_index_wew MEDIUMTEXT");
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.91");
		break;


	case "v4.91":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		// Recherche autopostage
		
		$rqt = "ALTER TABLE categories ADD path_word_categ TEXT NOT NULL ";
		echo traite_rqt($rqt,"alter table categories add path_word_categ ");
		$rqt = "ALTER TABLE categories ADD index_path_word_categ TEXT NOT NULL ";
		echo traite_rqt($rqt,"alter table categories add index_path_word_categ ");		
		
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'thesaurus' and sstype_param='auto_postage_search' "))==0){
			$rqt = "INSERT INTO parametres ( type_param, sstype_param, valeur_param, comment_param,section_param,gestion) 
					VALUES ( 'thesaurus', 'auto_postage_search', '0', 'Activer l\'indexation des catégories mères et filles pour la recherche de notices. \n 0 non, \n 1 oui', 'i_categories', 0)";
			echo traite_rqt($rqt,"insert thesaurus_auto_postage_search=0 into parametres");			
		}	
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'thesaurus' and sstype_param='auto_postage_search_nb_descendant' "))==0){
			$rqt = "INSERT INTO parametres ( type_param, sstype_param, valeur_param, comment_param,section_param,gestion) 
					VALUES ( 'thesaurus', 'auto_postage_search_nb_descendant', '0', 'Nombre de niveaux de recherche de notices dans les catégories filles. \n *: illimité, \n n: nombre de niveaux', 'i_categories', 0)";
			echo traite_rqt($rqt,"insert thesaurus_auto_postage_search_nb_descendant=0 into parametres");
		}
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'thesaurus' and sstype_param='auto_postage_search_nb_montant' "))==0){
			$rqt = "INSERT INTO parametres ( type_param, sstype_param, valeur_param, comment_param,section_param,gestion) 
					VALUES ( 'thesaurus', 'auto_postage_search_nb_montant', '0', 'Nombre de niveaux de recherche de notices dans les catégories mères. \n *: illimité, \n n: nombre de niveaux', 'i_categories', 0)";
			echo traite_rqt($rqt,"insert thesaurus_auto_postage_search_nb_montant=0 into parametres");
		}								
		
		// Agrandir les champs d'indexation des documents numériques
		$rqt="ALTER TABLE explnum CHANGE explnum_index_sew explnum_index_sew MEDIUMTEXT NOT NULL";  
		echo traite_rqt($rqt,"ALTER TABLE explnum CHANGE explnum_index_sew explnum_index_sew MEDIUMTEXT");
		
		$rqt="ALTER TABLE explnum CHANGE explnum_index_wew explnum_index_wew MEDIUMTEXT NOT NULL";  
		echo traite_rqt($rqt,"ALTER TABLE explnum CHANGE explnum_index_wew explnum_index_wew MEDIUMTEXT");
		
		//Ajout de la TVA dans les lignes d'acte
		$rqt = "ALTER TABLE lignes_actes ADD debit_tva SMALLINT(2) UNSIGNED NOT NULL DEFAULT 0 ";
		echo traite_rqt($rqt,"ALTER TABLE lignes_actes ADD debit_tva");		
 
		//Possibilité de saisir un montant négatif dans une facture
		$rqt = "ALTER TABLE lignes_actes CHANGE prix prix FLOAT( 8, 2 ) NOT NULL DEFAULT 0.00 ";
		echo traite_rqt($rqt,"ALTER TABLE lignes_actes CHANGE prix signed");
		
		$rqt = "ALTER TABLE collections ADD collection_comment TEXT NOT NULL "; 
		echo traite_rqt($rqt, "ALTER TABLE collections ADD collection_comment");		
		$rqt = "ALTER TABLE sub_collections ADD subcollection_comment TEXT NOT NULL "; 
		echo traite_rqt($rqt, "ALTER TABLE sub_collections ADD sub_collection_comment");
				
		//insertion d'un champ "charset" dans la table d'import
		$rqt="ALTER TABLE import_marc ADD encoding VARCHAR(50) NOT NULL default '' ";
		echo traite_rqt($rqt,"ALTER TABLE import_marc ADD encoding VARCHAR(50)");
		
		// navigation bulletins
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_bulletin_nav' "))==0){
			$rqt = "INSERT INTO parametres ( type_param, sstype_param, valeur_param, comment_param,section_param,gestion) 
					VALUES ( 'opac', 'show_bulletin_nav', '0', 'Affichage d\'un navigateur dans les bulletins d\'un périodique. \n 0 non \n 1 oui','f_modules', 0)";
			echo traite_rqt($rqt,"insert opac_show_bulletin_nav=0 into parametres");
		}	
		// Jouer l'alerte sonore si le prêt et le retour se passe sans erreur
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='play_pret_sound' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param, gestion) VALUES (0, 'pmb', 'play_pret_sound', '1', 'Jouer l\'alerte sonore si le prêt et le retour se passe sans erreur ? \n 0 : Non.\n 1 : Oui.', '',0) ";
			echo traite_rqt($rqt, "insert pmb_play_pret_sound=1 into parameters");
		}
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.92");
		break;


	case "v4.92":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		$rqt="ALTER TABLE logopac drop INDEX lopac_date_log" ;
		echo traite_rqt($rqt,"ALTER TABLE logopac drop INDEX lopac_date_log") ;
		$rqt="ALTER TABLE statopac drop INDEX sopac_date_log" ;
		echo traite_rqt($rqt,"ALTER TABLE statopac drop INDEX sopac_date_log") ;
		
		
		$rqt="ALTER TABLE logopac ADD INDEX lopac_date_log(date_log)" ;
		echo traite_rqt($rqt,"ALTER TABLE logopac ADD index lopac_date_log") ;
		$rqt="ALTER TABLE statopac ADD INDEX sopac_date_log(date_log)" ;
		echo traite_rqt($rqt,"ALTER TABLE statopac ADD index sopac_date_log") ;
		
		// modification de l'explication de pmb_hide_retdoc_loc_error
		$rqt = "update parametres set comment_param='Gestion du retour de prêt d\'un document issu d\'une autre localisation:\n 0 : Rendu, sans message d\'erreur\n 1 : Non rendu, avec message d\'erreur\n 2 : Rendu, avec message d\'erreur' where type_param='pmb' and sstype_param='hide_retdoc_loc_error' ";
		echo traite_rqt($rqt,"update parametre pmb_hide_retdoc_loc_error");	 
		
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.93");
		break;


	case "v4.93":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		// modification de l'explication de pmb_hide_retdoc_loc_error
		$rqt = "update parametres set comment_param='Gestion du retour de prêt d\'un document issu d\'une autre localisation:\n 0 : Rendu, sans message d\'erreur\n 1 : Non rendu, avec message d\'erreur\n 2 : Rendu, avec message d\'erreur' where type_param='pmb' and sstype_param='hide_retdoc_loc_error' ";
		echo traite_rqt($rqt,"update parametre pmb_hide_retdoc_loc_error");	 
		
		//Modification commentaire parametre pmb_numero_exemplaire_auto 
		$rqt = "update parametres set comment_param='Autorise la numérotation automatique d\'exemplaire ? \n 0 : non\n 1 : Oui, pour monographies et bulletins\n 2 : Oui, pour monographies seules\n 3 : Oui, pour bulletins seuls' where type_param='pmb' and sstype_param='numero_exemplaire_auto' ";
		echo traite_rqt($rqt,"update parametre pmb_numero_exemplaire_auto ");	 
		
		//Augmentation de la taille des libelles de codes statistiques de lecteurs
		$rqt = "ALTER TABLE empr_codestat CHANGE libelle libelle VARCHAR(255) NOT NULL DEFAULT 'DEFAULT' ";
		echo traite_rqt($rqt,"alter table empr_codestat resize field libelle");	 
		
		//script de vérification de saisie d'une notice perso
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='catalog_verif_js' "))==0){
			$rqt = "INSERT INTO parametres ( type_param, sstype_param, valeur_param, comment_param,section_param,gestion) 
					VALUES ( 'pmb', 'catalog_verif_js', '', 'Script de vérification de saisie de notice','', 0)";
			echo traite_rqt($rqt,"insert catalog_verif_js into parametres");
		}		
		//restrictions des recherches prédéfinies par catégories de lecteurs		
		$rqt = "create table if not exists search_persopac_empr_categ(
			id_categ_empr int not null default 0,
			id_search_persopac int not null default 0, 	
			index i_id_s_persopac(id_search_persopac),
			index i_id_categ_empr(id_categ_empr)
		)" ;
		echo traite_rqt($rqt,"create table search_persopac_empr_categ");	
				
		//tri sur une étagère...
		$rqt = "ALTER TABLE etagere ADD id_tri INT NOT NULL, ADD INDEX i_id_tri (id_tri )";
		echo traite_rqt($rqt,"alter table etagere add id_tri");

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.94");
		break;

	case "v4.94":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		// CSS add on
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='default_style_addon' "))==0){
			$rqt = "INSERT INTO parametres ( type_param, sstype_param, valeur_param, comment_param,section_param,gestion) 
					VALUES ( 'opac', 'default_style_addon', '', 'Ajout de styles CSS aux feuilles déjà incluses ?\n Ne mettre que le code CSS, exemple:  body {background-color: #FF0000;}','a_general', 0)";
			echo traite_rqt($rqt,"insert opac_default_style_addon into parametres");
		}	
		
		//assocation d'un répertoire d'upload à une source dans les connecteurs
		$rqt = "ALTER TABLE connectors_sources ADD rep_upload INT NOT NULL default 0";
		echo traite_rqt($rqt,"alter table connectors_sources add rep_upload");
		
		//ajout de l'indicateur dans les entrepots...
		$rqt = "select source_id from connectors_sources";
		$res = mysql_query($rqt);
		$rqt= array();
		if(mysql_num_rows($res)){
			while ($r= mysql_fetch_object($res)){
				mysql_query("alter table entrepot_source_".$r->source_id." add field_ind char(2) not null default '  ' after ufield");
			}
		}
		echo traite_rqt("select 1 ","alter table entrepot_source add field_ind");
			
		// rfid
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='rfid_gates_server_url' "))==0){
			$rqt = "INSERT INTO parametres (type_param, sstype_param,valeur_param,comment_param, section_param, gestion) VALUES ('pmb','rfid_gates_server_url', '', 'URL du serveur des portiques RFID', '', '0')" ;
			echo traite_rqt($rqt,"insert pmb_rfid_gates_server_url='' into parametres");
		}
		// Upload des documents numériques lors de l'intégration de notice
		$rqt = "ALTER TABLE connectors_sources ADD upload_doc_num INT NOT NULL default 1";
		echo traite_rqt($rqt,"alter table connectors_sources add upload_doc_num");
		
		//Separateur de valeurs de champs perso 
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='perso_sep' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'perso_sep', '/', 'Séparateur des valeurs de champ perso, espace ou ; ou , ou ...')";
			echo traite_rqt($rqt,"insert pmb_perso_sep='/' into parametres");
		}
	
		//Modification du commentaire du paramètre opac_notice_reduit_format
		$rqt = "update parametres set comment_param = 'Format d\'affichage des réduits de notices :\n 0 = titre+auteur principal\n 1 = titre+auteur principal+date édition\n 2 = titre+auteur principal+date édition + ISBN\n P 1,2,3 = tit+aut+champs persos id 1 2 3\n E 1,2,3 = tit+aut+édit+champs persos id 1 2 3\n T = tit1+tit4' where type_param='opac' and sstype_param='notice_reduit_format'";
		echo traite_rqt($rqt,"update parametre opac_notice_reduit_format");
		
		$rqt = "update parametres set comment_param = 'Possibilité pour les lecteurs de créer ou modifier leurs bannettes privées\n 0: Non\n 1: Oui\n 2: Oui et le bouton de création s\'affiche en permanence en recherche multicritères' where type_param='opac' and sstype_param='allow_bannette_priv'";
		echo traite_rqt($rqt,"update parametre opac_allow_bannette_priv");
		
		//Modification du commentaire du paramètre pmb_notice_reduit_format
		$rqt = "update parametres set comment_param = 'Format d\'affichage des réduits de notices :\n 0 = titre+auteur principal\n 1 = titre+auteur principal+date édition\n 2 = titre+auteur principal+date édition + ISBN' where type_param='pmb' and sstype_param='notice_reduit_format'";
		echo traite_rqt($rqt,"update parametre pmb_notice_reduit_format");
		
		//on conserve la référence de la source d'origine
		$rqt = "create table if not exists notices_externes(
			num_notice int not null default 0,
			recid varchar(255) not null default '',
			primary key(num_notice),
			index i_recid(recid),
			index i_notice_recid (num_notice, recid))" ;
		echo traite_rqt($rqt,"create table notices_externes");	
		
		$rqt="ALTER TABLE explnum drop INDEX i_f_explnumwew" ;
		echo traite_rqt($rqt,"ALTER TABLE explnum drop INDEX i_f_explnumwew") ;
		$rqt="ALTER TABLE explnum ADD FULLTEXT i_f_explnumwew (explnum_index_wew)" ;
		echo traite_rqt($rqt,"ALTER TABLE explnum ADD FULLTEXT i_f_explnumwew ") ;
	
		// Type de recherche sur documents numériques 
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='search_full_text' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param) VALUES (0, 'pmb', 'search_full_text', '0', 'Utiliser un index MySQL FULLTEXT pour la recherche sur les documents numériques \n 0: Non \n 1: Oui')";
			echo traite_rqt($rqt,"insert pmb_search_full_text='0' into parametres");
		}
		
		// Restriction d'une infopage aux abonnés uniquement 
		$rqt = "alter table infopages add restrict_infopage int not null default 0";
		echo traite_rqt($rqt,"alter table infopages add restrict_infopage");
		
		// Parser HTML OPAC
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='parse_html' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param) VALUES (0, 'opac', 'parse_html', '0', 'Activer le parse HTML des pages OPAC \n 0: Non \n 1: Oui','a_general')";
			echo traite_rqt($rqt,"insert opac_parse_html='0' into parametres");
		}

		//on précise si une source peut enrichir ou non des notices
		$rqt = "ALTER TABLE connectors_sources ADD enrichment INT NOT NULL default 0";
		echo traite_rqt($rqt,"alter table connectors_sources add enrichment");
		
		//stockage des enrichissements de notices
		$rqt = "create table if not exists sources_enrichment(
			source_enrichment_num int not null default 0,
			source_enrichment_typnotice varchar(2) not null default '',
			source_enrichment_typdoc varchar(2) not null default '',
			source_enrichment_params text not null default '',
			primary key (source_enrichment_num, source_enrichment_typnotice, source_enrichment_typdoc),
			index i_s_enrichment_typnoti(source_enrichment_typnotice),
			index i_s_enrichment_typdoc(source_enrichment_typdoc))" ;
		echo traite_rqt($rqt,"create table sources_enrichment");	
		
		// Enrichissement OPAC
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='notice_enrichment' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param) VALUES (0, 'opac', 'notice_enrichment', '0', 'Activer l\'enrichissement des notices\n 0: Non \n 1: Oui','e_aff_notice')";
			echo traite_rqt($rqt,"insert opac_notice_enrichment='0' into parametres");
		}	
		
		// Social Network
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='show_social_network' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param) VALUES (0, 'opac', 'show_social_network', '0', 'Activer les partages sur les réseaux sociaux \n 0: Non \n 1: Oui','e_aff_notice')";
			echo traite_rqt($rqt,"insert show_social_network='0' into parametres");
		}

		// Favicon
		if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'opac' and sstype_param='faviconurl' "))==0){
			$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, section_param) VALUES (0, 'opac', 'faviconurl', '', 'URL du favicon, si vide favicon=celui de PMB','a_general')";
			echo traite_rqt($rqt,"insert opac_faviconurl='' into parametres");
		}
		
		// valeur par défaut restrict infopages
		$rqt = "ALTER TABLE infopages CHANGE restrict_infopage restrict_infopage INT( 11 ) NOT NULL DEFAULT 0";
		echo traite_rqt($rqt,"ALTER TABLE infopages CHANGE restrict_infopage DEFAULT 0");
		
		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		echo form_relance ("v4.95");
		break;

	case "v4.95":
		echo "<table ><tr><th>".$msg['admin_misc_action']."</th><th>".$msg['admin_misc_resultat']."</th></tr>";
		// +-------------------------------------------------+
		
		// ajout dans les bannettes la possibilité de ne pas tenir compte du statut des notices
		$rqt = "ALTER TABLE bannettes ADD statut_not_account INT( 1 ) UNSIGNED NOT NULL default 0";
		echo traite_rqt($rqt,"ALTER TABLE bannettes ADD statut_not_account INT( 1 ) UNSIGNED NOT NULL default 0");
		
$action="v4.94";		
		// FUTUR : echo form_relance ("v5.00");

		// +-------------------------------------------------+
		echo "</table>";
		$rqt = "update parametres set valeur_param='".$action."' where type_param='pmb' and sstype_param='bdd_version' " ;
		$res = mysql_query($rqt, $dbh) ;
		echo "<strong><font color='#FF0000'>".$msg[1807].$action." !</font></strong><br />";
		break;
				
	default:
		include("$include_path/messages/help/$lang/alter.txt");
		break;
	}
	
/*	
	A mettre en 5.00
	

	//Précision affichage amendes
	if (mysql_num_rows(mysql_query("select 1 from parametres where type_param= 'pmb' and sstype_param='precision' "))==0){
		$rqt = "INSERT INTO parametres (id_param, type_param, sstype_param, valeur_param, comment_param, gestion) VALUES (0, 'pmb', 'precision', '2', 'Nombre de décimales pour l\'affichage des amendes',1)";
		echo traite_rqt($rqt,"insert precision=2 into parametres");
	}

	//maj valeurs possibles pour empr_filter_rows
	$rqt = "update parametres set comment_param='Colonnes disponibles pour filtrer la liste des emprunteurs : \n v: ville\n l: localisation\n c: catégorie\n s: statut\n g: groupe\n y: année de naissance\n cp: code postal\n cs : code statistique\n #n : id des champs personnalisés' where type_param= 'empr' and sstype_param='filter_rows' ";
	echo traite_rqt($rqt,"update empr_filter_rows into parametres");

	
			
**/