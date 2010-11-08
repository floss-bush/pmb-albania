<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: calendar.class.php,v 1.7 2007-03-10 09:25:49 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

class calendar {

    function calendar() {
    }
    
    function get_open_days($dd,$md,$yd,$df,$mf,$yf) {
    	global $utiliser_calendrier;
    	global $deflt2docs_location ;
    	$ndays=0;
    	
	   	if ($utiliser_calendrier) {
	   		$requete="select count(date_ouverture) from ouvertures where ouvert=1 and num_location=$deflt2docs_location and date_ouverture>='".$yd."-".$md."-".$dd."' and date_ouverture<='".$yf."-".$mf."-".$df."'";
	   		$resultat=mysql_query($requete);
	   		
	   		if (@mysql_num_rows($resultat)) {
	   			$ndays=mysql_result($resultat,0,0);
	   		} else $ndays=(mktime(0,0,0,$mf,$df,$yf)-mktime(0,0,0,$md,$dd,$yd))/86400;
    	} else {
    		$ndays=(mktime(0,0,0,$mf,$df,$yf)-mktime(0,0,0,$md,$dd,$yd))/86400;
    	}
    	
    	return $ndays;
    }
    
    function add_days($dd,$md,$yd,$days) {
    	global $utiliser_calendrier;
    	global $deflt2docs_location;
    	if ($utiliser_calendrier) {
 		   	$requete="select min(date_ouverture) from ouvertures where ouvert=1 and num_location=$deflt2docs_location and date_ouverture>=adddate('".$yd."-".$md."-".$dd."', interval $days day)";
   		 	$resultat=mysql_query($requete) or die ($requete." ".mysql_error());;
   		 	if (!@mysql_num_rows($resultat)) {
   		 		$requete="select adddate('".$yd."-".$md."-".$dd."', interval $days day)";
    			$resultat=mysql_query($requete) or die ($requete." ".mysql_error());;
   		 	}
    	} else {
    		$requete="select adddate('".$yd."-".$md."-".$dd."', interval $days day)";
    		$resultat=mysql_query($requete) or die ($requete." ".mysql_error());
    	} 
    	
    	$date=mysql_result($resultat,0,0);
    	return $date;	
    }
 
 	function maketime($mysql_date) {
 		$t_date=explode("-",$mysql_date);
 		return mktime(0,0,0,$t_date[1],$t_date[2],$t_date[0]);
 	}
}
?>