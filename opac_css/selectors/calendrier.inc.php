<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: calendrier.inc.php,v 1.3 2010-11-04 15:33:03 ngantier Exp $


print $popup_header;
// deux parametres ajoutés avec initialisation de façon à ne pas perturber la prolongation du prêt
// ajouter un param auto_submit Oui ou non automatique...
if ($auto_submit=="") $auto_submit="YES"; 
// ajouter un param date_anterieure Oui ou non pour préciser si les dates antérieures à celle fournie ($date) sont autorisées
if ($date_anterieure=="") $date_anterieure="NO"; 

// la variable $caller, passée par l'URL, contient le nom du form appelant
$base_url = "./select.php?what=calendrier&caller=$caller&date_caller=$date_caller&param1=$param1&param2=$param2&after=$after&auto_submit=$auto_submit&date_anterieure=$date_anterieure";
$date_caller=str_replace('-','',$date_caller);
if (($date_caller=="")||($date_caller=="00000000")||($date_caller=="00000000 00:00:00")) $date_caller = date ("Ymd", time());
if ($date=="") $date=$date_caller;

echo "
<script type='text/javascript'>
<!--
function set_parent(f_caller, id_value, libelle_value)
{
	window.opener.document.forms[f_caller].elements['$param1'].value = id_value;
	window.opener.document.forms[f_caller].elements['$param2'].value = libelle_value;
	
	var after = new String('$after');
	if (after.length != 0 ) window.opener.eval('$after');
	" ;
	if ($auto_submit=="YES") echo "	window.opener.document.forms[f_caller].submit();";
	echo "
	window.close();
}
-->
</script>
";

// issu de la saisie directe de la date.
if($act == "calc_date"){
	// Mettre le parent appelant à jour, et sortir.
	$mysql_date= extraitdate($date);
	print"<script type='text/javascript'>set_parent('$caller','$mysql_date','".$date."')</script>";
}else {		
	
	$params['calendar_id'] = 1 ; 				
	$params['calendar_columns'] = 7 ; 			
	$params['show_day'] = 1 ; 				
	$params['show_month'] = 1 ; 				
	$params['nav_link'] = 1 ; 				
	$params['link_after_date'] = 1 ; 			
	if ($date_anterieure=="YES") $params['link_before_date'] = 1 ; 	else  $params['link_before_date'] = 0 ; 
	$params['link_on_day'] = $base_url ; 		
	$params['font_face'] = "Verdana, Arial, Helvetica" ; 	
	$params['font_size'] = 10 ; 				
	$params['bg_color'] = "#FFFFFF" ; 			
	$params['today_bg_color'] = "#FF0000" ; 		
	$params['font_today_color'] = "#000000" ; 		
	$params['font_color'] = "#000000" ; 			
	$params['font_nav_bg_color'] = "#AAAAAA" ; 		
	$params['font_nav_color'] = "#000000" ; 		
	$params['font_header_color'] = "#00FF00" ; 		
	$params['border_color'] = "#000000" ; 			
	$params['use_img'] = 1 ; 
	
	echo "<div class='row'>";
	echo calendar($date);	
	$form_action ="./select.php?what=calendrier&caller=$caller&date_caller=$date_caller&param1=$param1&param2=$param2&after=$after&date_anterieure=$date_anterieure&auto_submit=$auto_submit";
	$date= formatdate($date_caller);
	$form_directe_date=date_directe($date,$form_action);
	echo "$form_directe_date";
	echo "</div>";
}

function date_directe($date,$post_url){
	global $link_on_day, $params, $base_url, $caller, $msg, $date_caller;
	global $dbh ;
	global $msg ;

$calend= <<<ENDOFTEXT
	<script type='text/javascript' src='./includes/javascript/http_request.js'></script>
	<script language="JavaScript">

	function CheckDataAjax() {
		var DirectDate = document.Cal.DirectDate.value;
		var url= "./ajax.php?module=ajax&categ=misc&fname=verifdate&p1=" + DirectDate;
		var test_date = new http_request();
		if(test_date.request(url)) alert ( test_date.get_text() );
		else { 
			document.getElementById('date_directe').value = DirectDate;
			return 1;	
		}
	}
	</script>
		
	<form name="Cal" id="Cal" method='post' action='!!post_url!!'><center>
	<input type='text' name='DirectDate' size=10 value='!!date_caller!!'>
	<input type='hidden' name='act' value='calc_date'>
	<input type='hidden' id='date_directe' name='date' value='!!date_caller!!'>	
	<input type='submit' class="bouton_small" value="!!commit_button!!" style="font-weight: bold" onClick="if(CheckDataAjax()) submit();">
	</center></form>	
ENDOFTEXT;
	
	$calend = str_replace("!!commit_button!!" ,$msg["calendrier_date_submit"], $calend);	
	$calend = str_replace("!!date_caller!!" , $date, $calend);	
	$calend = str_replace("!!post_url!!" , $post_url, $calend);		
	return $calend;
}

/* ce sélecteur est basé sur le calendrier dont la description et 
   l'auteur initial sont mentionnés ci-dessous.
   Il a été modifié afin d'être utilisable dans notre application */
/***************************************************************************
             ____  _   _ ____  _              _     _  _   _   _
            |  _ \| | | |  _ \| |_ ___   ___ | |___| || | | | | |
            | |_) | |_| | |_) | __/ _ \ / _ \| / __| || |_| | | |
            |  __/|  _  |  __/| || (_) | (_) | \__ \__   _| |_| |
            |_|   |_| |_|_|    \__\___/ \___/|_|___/  |_|  \___/
            
                       calendrier.php  -  A calendar
                             -------------------
    begin                : June 2002
    copyright            : (C) 2002 PHPtools4U.com - Mathieu LESNIAK
    email                : support@phptools4u.com

***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
/* 
- $params['calendar_id'] :  Par défaut à 1, incrémenter cette valeur pour utiliser plusieurs calendriers sur la même page.  
- $params['calendar_columns'] :  Par défaut à 7, modifier ce nombre pour diminuer / augmenter le nombres de colonnes. 
- $params['show_day'] :  Par défaut à 1, permet d'afficher les jours (L M M J V S D) 
- $params['show_month'] :  Par défaut à 1, permet d'afficher le nom du mois et l'année en haut 
- $params['nav_link'] :  Par défaut à 1, affiche les liens pour les jours et mois précédents / suivants 
- $params['link_after_date'] :  Par défaut à 0, si activé, affiche les liens de la navigation (cf ci-dessus) pour les dates supérieures au jour en cours 
- $params['link_on_day'] :  Lien à attribuer sur les jours du calendrier. A chaque lien est rajouté la date en argument. Prévoir de mettre '?argument=' en fin de lien 
- $params['font_face'] :  Police a utiliser (par défaut : 'Verdana, Arial, Helvetica') 
- $params['font_size'] :  Taille de la police moyenne en pixels (10 par défaut) 
- $params['bg_color'] :  Couleur du fond des cases des jours (blanc - #FFFFFF par défaut) 
- $params['today_bg_color'] :  Couleur de fond de la case du jour en cours 
- $params['font_today_color'] :  Couleur de la police pour le jour en cours 
- $params['font_color'] :  Couleur de la police 
- $params['font_nav_bg_color'] :  Couleur de fond pour la barre des jours (L M M J V S D) 
- $params['font_nav_color'] :  Couleur de la police pour la barre des jours (L M M J V S D) 
- $params['font_header_color'] :  Couleur de la police pour le nom du mois 
- $params['border_color'] :  Couleur pour les séparation des cases et des bordures 
- $params['use_img'] :  Utilise des fichiers gif à côté du nom du mois et pour la barre de navigation en bas. Si défini à '0', affiche les liens textes. 
*/

function calendar($date = '') {
	global $link_on_day, $params, $base_url, $caller, $msg, $date_caller,$PHP_SELF;
	global $dbh ;
	global $msg ;

	// Default Params
	$param_d['calendar_id']		= 1; // Calendar ID
	$param_d['calendar_columns'] 	= 5; // Nb of columns
	$param_d['show_day'] 		= 1; // Show the day bar
	$param_d['show_month']		= 1; // Show the month bar
	$param_d['nav_link']		= 1; // Add a nav bar below
	$param_d['link_after_date']	= 0; // Enable link on days after the current day
	$param_d['link_before_date']	= 0; // Enable link on days before the current day
	$param_d['link_on_day']		= $PHP_SELF.'?date='; // Link to put on each day
	$param_d['font_face']		= 'Verdana, Arial, Helvetica'; // Default font to use
	$param_d['font_size']		= 10; // Font size in px
	$param_d['bg_color']		= '#FFFFFF'; 
	$param_d['today_bg_color']	= '#A0C0C0';
	$param_d['font_today_color']	= '#990000';
	$param_d['font_color']		= '#000000';
	$param_d['font_nav_bg_color']	= '#A9B4B3';
	$param_d['font_nav_color']	= '#FFFFFF';
	$param_d['font_header_color']	= '#FFFFFF';
	$param_d['border_color']	= '#3f6551';
	$param_d['use_img']		= 1; // Use gif for nav bar on the bottom
	
	// Params
	$monthes_name = array('',$msg[1006],$msg[1007],$msg[1008],$msg[1009],$msg[1010],$msg[1011],$msg[1012],$msg[1013],$msg[1014],$msg[1015],$msg[1016],$msg[1017]);
	$days_name = array('',$msg[1018],$msg[1019],$msg[1020],$msg[1021],$msg[1022],$msg[1023],$msg[1024]);
	
	while (list($key, $val) = each($param_d)) {
		if (isset($params[$key])) $param[$key] = $params[$key];
		else $param[$key] = $param_d[$key];
	}
	$param['calendar_columns'] = ($param['show_day']) ? 7 : $param['calendar_columns'];
	
	if ($date == '') {
		$date_MySQL = " CURDATE() ";
	} else {
		$month 		= substr($date, 4 ,2);
		$day 		= substr($date, 6, 2);
		$year		= substr($date, 0 ,4);
		$date_MySQL = "'$year-$month-$day'";
	}
	$rqt_date = "select date_format(".$date_MySQL.", '%d') as current_day, date_format(".$date_MySQL.", '%m') as current_month_2, date_format(".$date_MySQL.", '%c') as current_month, date_format(".$date_MySQL.", '%Y') as current_year " ;
	$resultatdate=mysql_query($rqt_date);
	$resdate=mysql_fetch_object($resultatdate);
	
	$current_day 		= $resdate->current_day;
	$current_month 		= $resdate->current_month;
	$current_month_2	= $resdate->current_month_2;
	$current_year 		= $resdate->current_year;
	
	$date_MySQL_firstday = "'$year-$current_month_2-01'";
	$rqt_date = "select date_format(".$date_MySQL_firstday.", '%w') as first_day_pos,
				date_format(DATE_SUB(DATE_ADD(".$date_MySQL_firstday.", INTERVAL 1 MONTH),INTERVAL 1 DAY), '%d') as nb_days_month " ;
	$resultatdate=mysql_query($rqt_date);
	$resdate=mysql_fetch_object($resultatdate);
	$first_day_pos 		= $resdate->first_day_pos;
	$first_day_pos 		= ($first_day_pos == 0) ? 7 : $first_day_pos;

	$nb_days_month 		= $resdate->nb_days_month ;
	
	$current_month_name = $monthes_name[$current_month];
	
	/* Ajout ER : détection si date en cours du calendrier correspond ou pas à la date de l'appelant 
		Sans ce test, le lien sur tous les jours identiques d'un autre mois n'étaient pas affichés, exemple :
			appelant avec date au 04/10/2003 >> lien du 04/11/2003 absent */
	$date_MySQL_caller = "'".substr($date_caller, 0 ,4)."-".substr($date_caller, 4 ,2)."-".substr($date_caller, 6 ,2)."'";
	$rqt_date = "select date_format(".$date_MySQL_caller.", '%d') as current_day, date_format(".$date_MySQL_caller.", '%c') as current_month, date_format(".$date_MySQL_caller.", '%Y') as current_year ";
	$resultatdate=mysql_query($rqt_date);
	$resdate=mysql_fetch_object($resultatdate);
	
	$caller_day 		= $resdate->current_day;
	$caller_month 		= $resdate->current_month;
	$caller_year 		= $resdate->current_year;
	
	if (($caller_month==$current_month) && ($caller_year==$current_year) && ($caller_day==$current_day)) $same_date=1; else $same_date=0;
	
	$output = '<style type="text/css">
		<!--
		.calendarNav'.$param['calendar_id'].' 	{  font-family: '.$param['font_face'].'; font-size: '.($param['font_size']-1).'px; font-style: normal; background-color: '.$param['border_color'].'}
		.calendarTop'.$param['calendar_id'].' 	{  font-family: '.$param['font_face'].'; font-size: '.($param['font_size']+1).'px; font-style: normal; color: '.$param['font_header_color'].'; font-weight: bold;  background-color: '.$param['border_color'].'}
		.calendarToday'.$param['calendar_id'].' {  font-family: '.$param['font_face'].'; font-size: '.$param['font_size'].'px; font-weight: bold; color: '.$param['font_today_color'].'; background-color: '.$param_d['today_bg_color'].';}
		.calendarDays'.$param['calendar_id'].' 	{  font-family: '.$param['font_face'].'; font-size: '.$param['font_size'].'px; font-style: normal; color: '.$param['font_color'].'; background-color: '.$param['bg_color'].'; text-align: center}
		.calendarHeader'.$param['calendar_id'].'{  font-family: '.$param['font_face'].'; font-size: '.($param['font_size']-1).'px; background-color: '.$param['font_nav_bg_color'].'; color: '.$param['font_nav_color'].';}
		.calendarTable'.$param['calendar_id'].' {  background-color: '.$param['border_color'].'; border: 1px '.$param['border_color'].' solid}
		-->
		</style>';
	$output .= '<TABLE border="0" width="180" class="calendarTable'.$param['calendar_id'].'" cellpadding="2" cellspacing="1">'."\n";
	$output .= '!!fleches!!' ;
	
	// Displaying the current month/year
	if ($param['show_month'] == 1) {
		$output .= '<TR>'."\n";
		$output .= '	<TD colspan="'.$param['calendar_columns'].'" align="center" class="calendarTop'.$param['calendar_id'].'">'."\n";
		### Insert an img at will
		if ($param['use_img'] ) {
			$output .= '<IMG src="./images/mois.gif">';
		}
		$output .= '		'.$current_month_name.' '.$current_year."\n";
		$output .= '	</TD>'."\n";
		$output .= '</TR>'."\n";
	}
		
	// Building the table row with the days
	if ($param['show_day'] == 1) {
		$output .= '<TR align="center">'."\n";
		$output .= '	<TD class="calendarHeader'.$param['calendar_id'].'"><B>'.$msg[1018].'</B></TD>'."\n";
		$output .= '	<TD class="calendarHeader'.$param['calendar_id'].'"><B>'.$msg[1019].'</B></TD>'."\n";
		$output .= '	<TD class="calendarHeader'.$param['calendar_id'].'"><B>'.$msg[1020].'</B></TD>'."\n";
		$output .= '	<TD class="calendarHeader'.$param['calendar_id'].'"><B>'.$msg[1021].'</B></TD>'."\n";
		$output .= '	<TD class="calendarHeader'.$param['calendar_id'].'"><B>'.$msg[1022].'</B></TD>'."\n";
		$output .= '	<TD class="calendarHeader'.$param['calendar_id'].'"><B>'.$msg[1023].'</B></TD>'."\n";
		$output .= '	<TD class="calendarHeader'.$param['calendar_id'].'"><B>'.$msg[1024].'</B></TD>'."\n";
		$output .= '</TR>'."\n";	
	}else {
		$first_day_pos = 1;	
	}
	
	$output .= '<TR align="center">';
	$int_counter = 0;
	for ($i = 1; $i < $first_day_pos; $i++) {
		$output .= '<TD class="calendarDays'.$param['calendar_id'].'">&nbsp;</TD>'."\n";
		$int_counter++;
	}
	// Building the table
	for ($i = 1; $i <= $nb_days_month; $i++) {
		$i_2 = ($i < 10) ? '0'.$i : $i;		
		### Row start
		if ((($i + $first_day_pos-1) % $param['calendar_columns']) == 1 && $i != 1) {
			$output .= '<TR align="center">'."\n";
			$int_counter = 0;
		}
		if (($i == $current_day) && ($same_date == 1)) {
			$output .= '<TD class="calendarToday'.$param['calendar_id'].'" align="center"><A href="#" onclick="set_parent(\''.$caller.'\', \''.$current_year.'-'.$current_month_2.'-'.$i_2.'\', \''.formatdate($current_year.'-'.$current_month_2.'-'.$i_2).'\')">'.$i.'</A></TD>'."\n";
		}elseif ($param['link_on_day'] != '') {
				
			$date_MySQL_loop = "'".$current_year."-".$current_month."-".$i."'";
			
			$rqt_date = "select case when CURDATE() < ".$date_MySQL_loop." then 1 ELSE 0 END as test_loop ";
			$resultatdate=mysql_query($rqt_date);
			$resdate=mysql_fetch_object($resultatdate);
			
			$test_loop = $resdate->test_loop;
			
			if ($test_loop){
				if ($param['link_after_date'] == 0) {
					$output .= '<TD class="calendarDays'.$param['calendar_id'].'">'.$i.'</TD>'."\n";
				}else {
					$output .= '<TD class="calendarDays'.$param['calendar_id'].'"><A href="#" onclick="set_parent(\''.$caller.'\', \''.$current_year.'-'.$current_month_2.'-'.$i_2.'\', \''.formatdate($current_year.'-'.$current_month_2.'-'.$i_2).'\')">'.$i.'</A></TD>'."\n";
				}
			}else {
				if  ($param['link_before_date'] == 0) {
						$output .= '<TD class="calendarDays'.$param['calendar_id'].'">'.$i.'</TD>'."\n";
				}else {
					$output .= '<TD class="calendarDays'.$param['calendar_id'].'"><A href="#" onclick="set_parent(\''.$caller.'\', \''.$current_year.'-'.$current_month_2.'-'.$i_2.'\', \''.formatdate($current_year.'-'.$current_month_2.'-'.$i_2).'\')">'.$i.'</A></TD>'."\n";
				}
			}
		} else {
			$output .= '<TD class="calendarDays'.$param['calendar_id'].'">'.$i.'</TD>'."\n";
		}	
		$int_counter++;	
		// Row end
		if ( (($i+$first_day_pos-1) % $param['calendar_columns']) == 0 ) {
			$output .= '</TR>'."\n";	
		}
	}
	$cell_missing = $param['calendar_columns'] - $int_counter;
	
	for ($i = 0; $i < $cell_missing; $i++) {
		$output .= '<TD class="calendarDays'.$param['calendar_id'].'">&nbsp;</TD>'."\n";
	}
	$output .= '</TR>'."\n";
	// Display the nav links on the bottom of the table
	if ($param['nav_link'] == 1) {	
		$date_MySQL = "'$current_year-$current_month-$current_day'";
		$rqt_date = "select 
			date_format(DATE_SUB(".$date_MySQL.", INTERVAL 1 YEAR),'%Y%m%d') as previous_month, 
			date_format(DATE_SUB(".$date_MySQL.", INTERVAL 1 MONTH),'%Y%m%d') as previous_day, 
			date_format(DATE_ADD(".$date_MySQL.", INTERVAL 1 YEAR),'%Y%m%d') as next_month, 
			date_format(DATE_ADD(".$date_MySQL.", INTERVAL 1 MONTH),'%Y%m%d') as next_day, 
			case when CURDATE() < date_format(DATE_ADD(".$date_MySQL.", INTERVAL 1 YEAR),'%Y%m%d') then 1 else 0 END as test_next_year, 
			case when CURDATE() < date_format(DATE_ADD(".$date_MySQL.", INTERVAL 1 MONTH),'%Y%m%d') then 1 else 0 END as test_next_month ";
		$resultatdate=mysql_query($rqt_date);
		$resdate=mysql_fetch_object($resultatdate);
	
		$previous_month	= $resdate->previous_month;
		$next_month    	= $resdate->next_month;
		$previous_day  	= $resdate->previous_day;
		$next_day      	= $resdate->next_day;
		$test_next_month = $resdate->test_next_month;
		$test_next_year  = $resdate->test_next_year;	
		if ($param['use_img']) {
			$g 	= '<IMG src="./images/g.gif" border="0" title="'.$msg['calendrier_mois_prececent'].'">';
			$gg = '<IMG src="./images/gg.gif" border="0" title="'.$msg['calendrier_annee_prececente'].'">';
			$d 	= '<IMG src="./images/d.gif" border="0" title="'.$msg['calendrier_mois_suivant'].'">';
			$dd = '<IMG src="./images/dd.gif" border="0" title="'.$msg['calendrier_annee_suivante'].'">';
		}else {
			$g 	= '&lt;';
			$gg = '&lt;&lt;';
			$d = '&gt;';
			$dd = '&gt;&gt;';
		}
		if ( ($param['link_after_date'] == 0) && ($test_next_month) ) {
			$next_day_link = '&nbsp;';
		}else {
			$next_day_link 		= '<A href="'.$base_url.'&date='.$next_day.'">'.$d.'</A>'."\n";
		}
		if ( ($param['link_after_date'] == 0) && ($test_next_year) ) {
			$next_month_link = '&nbsp;';		
		}else {
			$next_month_link 	= '<A href="'.$base_url.'&date='.$next_month.'">'.$dd.'</A>'."\n";
		}
		
		$output_fleches  = '<TR>'."\n";
		$output_fleches .= '	<TD colspan="'.$param['calendar_columns'].'" class="calendarDays'.$param['calendar_id'].'">'."\n";
		$output_fleches .= "
			<div class='row'>
				<div class='colonne4'>
					<A href='".$base_url."&date=".$previous_month."'>".$gg."</A>
					</div>
				<div class='colonne4'>
					<A href='".$base_url."&date=".$previous_day."'>".$g."</A>
					</div>
				<div class='colonne4'>
					$next_day_link
					</div>
				<div class='colonne4'>
					$next_month_link
					</div>
			</div>";
		$output_fleches .= '	</TD>'."\n";
		$output_fleches .= '</TR>'."\n";		
	}	
	$output.= '</TABLE>'."\n";
	$output = str_replace("!!fleches!!",$output_fleches,$output);

	return $output;
}
