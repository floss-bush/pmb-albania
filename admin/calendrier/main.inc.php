<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.10 2009-05-16 11:12:02 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

include "./admin/calendrier/calendrier_func.inc.php" ;

if ($faire=="ouvrir" || $faire=="fermer") {
	$date_deb = extraitdate($date_deb); 
	$date_fin = extraitdate($date_fin);
	if ($faire=="ouvrir") $ouverture=1 ;
		else $ouverture=0 ; 

	$rqt_date = "select if(TO_DAYS('".$date_fin."')>=TO_DAYS('".$date_deb."'),1,0) as OK";
	$resultatdate=mysql_query($rqt_date);
	$res=mysql_fetch_object($resultatdate) ;
	$date_courante = $date_deb ; 
	while ($res->OK) { 
		$rqt_date = "select dayofweek('".$date_courante."') as jour";
		$resultatdate=mysql_query($rqt_date);
		$res=mysql_fetch_object($resultatdate) ;
		$jour = "j".$res->jour ;
		// OK : traitement
		if ($$jour) {
			$rqt_date = "update ouvertures set ouvert=$ouverture, commentaire='$commentaire' where date_ouverture='$date_courante' and num_location=$deflt2docs_location ";
			$resultatdate=mysql_query($rqt_date);
			if (!mysql_affected_rows()) {
				$rqt_date = "insert into ouvertures set ouvert=$ouverture, date_ouverture='$date_courante', commentaire='$commentaire', num_location=$deflt2docs_location ";
				$resultatdate=mysql_query($rqt_date);
				if (!mysql_affected_rows()) die ("insert into ouvertures failes") ;	
				}
			}
		$rqt_date = "select if(to_days(date_add('".$date_courante."', INTERVAL 1 DAY))<=TO_DAYS('".$date_fin."'),1,0) as OK, date_add('".$date_courante."', INTERVAL 1 DAY) as date_courante, dayofweek(date_add('".$date_courante."', INTERVAL 1 DAY)) as jour";
		$resultatdate=mysql_query($rqt_date);
		$res=mysql_fetch_object($resultatdate) ;
		$date_courante=$res->date_courante ;
		}
	}

if ($faire=="commentaire" && $annee_mois) {
	for ($i=1; $i<=31; $i++) {
		$i_2 = substr("0".$i, -2) ;
		$var_jour_comment = "comment_".$i_2 ;
		$commentaire = $$var_jour_comment ; 
		if ($commentaire) {
			$date_courante = $annee_mois."-".$i_2;
			$rqt_date = "update ouvertures set commentaire='$commentaire' where date_ouverture='$date_courante' and num_location=$deflt2docs_location";
			$resultatdate=mysql_query($rqt_date);
			if (!mysql_affected_rows()) {
				$rqt_date = "insert into ouvertures set ouvert=0, date_ouverture='$date_courante', commentaire='$commentaire', num_location=$deflt2docs_location ";
				$resultatdate=mysql_query($rqt_date);
				if (!mysql_affected_rows()) die ("insert into ouvertures failed") ;	
				}
			}
		}
	}

if (($action=="O" || $action=="F") && $date) {
	$rqt_date = "update ouvertures set ouvert=if(ouvert=0, 1, 0) where date_ouverture='$date' and num_location=$deflt2docs_location ";
	$resultatdate=mysql_query($rqt_date);
	if (!mysql_affected_rows()) {
		$rqt_date = "insert into ouvertures set ouvert=if('".$action."'='O', 1, 0), date_ouverture='$date', commentaire='', num_location=$deflt2docs_location ";
		$resultatdate=mysql_query($rqt_date);
		if (!mysql_affected_rows()) die ("insert into ouvertures failes") ;	
		}
	
	}
	
switch ($sub) {
	case "edition":
		$params['link_on_day'] = "" ; 		
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["calendrier_edition"], $admin_layout);
		print $admin_layout;
		echo pmb_bidi(calendar_gestion($date, 0, "", "", 1));
		break;
	case "consulter":
	default:
		$params['link_on_day'] = $base_url ; 		
		$admin_layout = str_replace('!!menu_sous_rub!!', $msg["calendrier_consulter"], $admin_layout);
		print $admin_layout;
		
		$result=mysql_query("select location_libelle from docs_location where idlocation=$deflt2docs_location",$dbh);
		if(mysql_num_rows($result))
			$admin_calendrier_form=str_replace('!!localisation!!','<br />Localisation : '.mysql_result($result,0,0),$admin_calendrier_form);
		else $admin_calendrier_form=str_replace('!!localisation!!',' ',$admin_calendrier_form);
		
		echo pmb_bidi($admin_calendrier_form) ;	
		if (!$annee) {
			if (!$date) {
				$rqt_date = "select date_format(CURDATE(),'%Y') as annee ";
				$resultatdate=mysql_query($rqt_date);
				$resdate=mysql_fetch_object($resultatdate);
				$annee = $resdate->annee ;
			} else $annee = substr($date, 0,4);
		} 
		$gg = '<IMG src="./images/gg.gif" border="0" title="'.$msg["calendrier_annee_prececente"].'">';
		$dd = '<IMG src="./images/dd.gif" border="0" title="'.$msg["calendrier_annee_suivante"].'">';
		
		echo "<div class='colonne3'><A href='".$base_url."&annee=".($annee-1)."' >".$gg."</A></div><div class='colonne3' align='right'><A href='".$base_url."&annee=".($annee+1)."'>".$dd."</A></div>\n";
		
		echo "<div id='calendrier_tab' style='width:99%'>" ;
		for ($i=1; $i<=12; $i++) {
			$mois = substr("0".$i, -2);
			$date = $annee.$mois."01" ;
			if ($i==1 || $i==3 || $i==5 || $i==7 || $i==9 || $i==11 ) echo "<div class='row' style='padding-top: 10px'><div class='colonne3'>";
				else echo "<div class='colonne3' style='padding-left: 10px'>";
			echo pmb_bidi(calendar_gestion($date, 0, $base_url, $base_url_mois));
			echo "</div>\n";
			if ($i==2 || $i==4 || $i==6 || $i==8 || $i==10 || $i==12 ) echo "</div>";
			}
		echo "</div>\n";
		break;
	}
