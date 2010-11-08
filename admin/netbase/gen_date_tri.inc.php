<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: gen_date_tri.inc.php,v 1.2 2009-05-16 11:11:53 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($base_path.'/classes/notice.class.php');

// la taille d'un paquet de notices
$lot = REINDEX_PAQUET_SIZE; // defini dans ./params.inc.php


// taille de la jauge pour affichage
$jauge_size = GAUGE_SIZE;
$jauge_size .= "px";

// initialisation de la borne de départ
if (!isset($start)) {
	$start=0;
}

$v_state=urldecode($v_state);

if (!$count) {
	$notices = mysql_query("SELECT count(1) FROM notices", $dbh);
	$count = mysql_result($notices, 0, 0);
}
	
print "<br /><br /><h2 align='center'>".htmlentities($msg["gen_date_tri_msg"], ENT_QUOTES, $charset)."</h2>";


$query = mysql_query("select notice_id, year, niveau_biblio, niveau_hierar from notices order by notice_id LIMIT $start, $lot");
if(mysql_num_rows($query)) {
		
	// définition de l'état de la jauge
	$state = floor($start / ($count / $jauge_size));
	$state .= "px";
	// mise à jour de l'affichage de la jauge
	print "<table border='0' align='center' width='$jauge_size' cellpadding='0'><tr><td class='jauge' width='100%'>";
	print "<img src='../../images/jauge.png' width='$state' height='16px'></td></tr></table>";
		
	// calcul pourcentage avancement
	$percent = floor(($start/$count)*100);
	
	// affichage du % d'avancement et de l'état
	print "<div align='center'>$percent%</div>";
	
	while($mesNotices = mysql_fetch_assoc($query)) {
		
		switch($mesNotices['niveau_biblio'].$mesNotices['niveau_hierar']){
			case 'a2': 
				//Si c'est un article, on récupère la date du bulletin associé
				$reqAnneeArticle = "SELECT date_date FROM bulletins, analysis WHERE analysis_bulletin=bulletin_id AND analysis_notice='".$mesNotices['notice_id']."'";
				$queryArt=mysql_query($reqAnneeArticle,$dbh);
				
				if(!mysql_num_rows($queryArt)) $dateArt = "";
				else $dateArt=mysql_result($queryArt,0,0);
							
				if($dateArt == '0000-00-00' || !isset($dateArt) || $dateArt == "") $annee_art_tmp = "";
					else $annee_art_tmp = substr($dateArt,0,4);

				//On met à jour, les notices avec la date de parution et l'année
				$reqMajArt = "UPDATE notices SET date_parution='".$dateArt."', year='".$annee_art_tmp."'
							WHERE notice_id='".$mesNotices['notice_id']."'";
		        mysql_query($reqMajArt, $dbh);
			    break;	
				
			case 'b2': 
				//Si c'est une notice de bulletin, on récupère la date pour connaitre l'année						
				$reqAnneeBulletin = "SELECT date_date FROM bulletins WHERE num_notice='".$mesNotices['notice_id']."'";
				$queryAnnee=mysql_query($reqAnneeBulletin,$dbh);
				
				if(!mysql_num_rows($queryAnnee)) $dateBulletin="";
				else $dateBulletin = mysql_result($queryAnnee,0,0);
				
				if($dateBulletin == '0000-00-00' || !isset($dateBulletin) || $dateBulletin == "") $annee_tmp = "";
				else $annee_tmp = substr($dateBulletin,0,4);
				
				//On met à jour date de parution et année
				$reqMajBull = "UPDATE notices SET date_parution='".$dateBulletin."', year='".$annee_tmp."'
						WHERE notice_id='".$mesNotices['notice_id']."'";
	    		mysql_query($reqMajBull, $dbh);
				
				break;
				
			default:
				// Mise à jour du champ date_parution des notices (monographie et pério)
				$date_parution = notice::get_date_parution($mesNotices['year']);
		    	$reqMaj = "UPDATE notices SET date_parution='".$date_parution."' WHERE notice_id='".$mesNotices['notice_id']."'";
		    	mysql_query($reqMaj, $dbh);
		    	break;
		}    	           		   	
	}
mysql_free_result($query);

$next = $start + $lot;
print "
	<form class='form-$current_module' name='current_state' action='./clean.php' method='post'>
	<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
	<input type='hidden' name='spec' value=\"$spec\">
	<input type='hidden' name='start' value=\"$next\">
	<input type='hidden' name='count' value=\"$count\">
	</form>
	<script type=\"text/javascript\"><!-- 
	setTimeout(\"document.forms['current_state'].submit()\",1000); 
	-->
	</script>";
} else {
	$spec = $spec - GEN_DATE_TRI;
	$not = mysql_query("SELECT count(1) FROM notices", $dbh);
	$compte = mysql_result($not, 0, 0);
	$v_state .= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg['gen_date_tri_msg'], ENT_QUOTES, $charset)." : ";
	$v_state .= $compte." ".htmlentities($msg['gen_date_tri_msg'], ENT_QUOTES, $charset);
	print "
		<form class='form-$current_module' name='process_state' action='./clean.php' method='post'>
		<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
		<input type='hidden' name='spec' value=\"$spec\">
		</form>
		<script type=\"text/javascript\"><!--
			document.forms['process_state'].submit();
			-->
		</script>";
}