<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: interpreter.inc.php,v 1.11 2008-08-25 12:55:06 ngantier Exp $
require_once ($include_path . "/misc.inc.php");

$func_format['romain']= aff_romain;
$func_format['roman']= aff_romain;
$func_format['date']= aff_date;
$func_format['day']= aff_day;
$func_format['dayofweek']= aff_dayofweek;
$func_format['month']= aff_month;
$func_format['week']= aff_week;
$func_format['year']= aff_year;
$func_format['str_day']= aff_str_day;
$func_format['str_month']= aff_str_month;
$func_format['str_short_day']= aff_str_short_day;
$func_format['str_short_month']= aff_str_short_month;
$func_format['season']= aff_season;
$func_format['str_season']= aff_str_season;
$func_format['seasonS']= aff_seasonS;
$func_format['str_seasonS']= aff_str_seasonS;
$func_format['sql']= aff_sql;
$func_format['+']= aff_add;
$func_format['-']= aff_sub;
$func_format['*']= aff_mux;
$func_format['%']= aff_mod;
$func_format['/']= aff_div;
$func_format['+day']= aff_add_day;
$func_format['+month']= aff_add_month;
$func_format['+year']= aff_add_year;
$func_format['-day']= aff_sub_day;
$func_format['-month']= aff_sub_month;
$func_format['-year']= aff_sub_year;
$func_format['quarter']= aff_quarter;
$func_format['dayofyear']= aff_dayofyear;
$func_format['curdate']= aff_curdate;
$func_format['0day']= aff_0day;
$func_format['0month']= aff_0month;
//     passer en majuscule la première lettre, 
$func_format['1ucase']=aff_ucase_firstletter;
//    passer le tout en majuscules,
$func_format['ucase']=aff_ucase;
//    passer les premières lettres de chaque mot en majuscule.
$func_format['1ucasew']=aff_ucase_firstletter_word;

//$func_format['']= ;

$var_format = array();

function func_test($f_name){
	global $func_format;
	if($func_format[$f_name]) return 1;
return 0;
}

function aff_romain($param) {
	$nombre_arab=$param[0];
	
	$nb_b10 = array (
		'I',
		'X',
		'C',
		'M'
	);
	$nb_b5 = array (
		'V',
		'L',
		'D'
	);
	$nbrom = '';
	$nombre = $nombre_arab;
	if ($nombre >= 0 && $nombre < 4000) {
		for ($i = 3; $i >= 0; $i--) {
			$chiffre = floor($nombre / pow(10, $i));
			if ($chiffre >= 1) {
				$nombre = $nombre - $chiffre * pow(10, $i);
				if ($chiffre <= 3) {
					for ($j = $chiffre; $j >= 1; $j--) {
						$nbrom = $nbrom . $nb_b10[$i];
					}
				}
				elseif ($chiffre == 9) {
					$nbrom = $nbrom . $nb_b10[$i] . $nb_b10[$i +1];
				}
				elseif ($chiffre == 4) {
					$nbrom = $nbrom . $nb_b10[$i] . $nb_b5[$i];
				} else {
					$nbrom = $nbrom . $nb_b5[$i];
					for ($j = $chiffre -5; $j >= 1; $j--) {
						$nbrom = $nbrom . $nb_b10[$i];
					}
				}
			}
		}
	} else {
		//Valeur Hors Limite;
		return $nombre_arab;
	}
	return $nbrom;
}

function aff_date($param) {
	$date=$param[0];
	return format_date($date);
}
function aff_day($param) {
	$date=$param[0];
	return sql_value("SELECT DAYOFMONTH('$date')");	// 1 à 31
}
function aff_0day($param) {
	$date=$param[0];
	return sql_value("SELECT right(concat('0',DAYOFMONTH('$date')),2)");	//01 à 31 
}
function aff_dayofweek($param) {
	$date=$param[0];	
	$sunday_mode=$param[1];	
	if($sunday_mode)
		return sql_value("SELECT DAYOFWEEK('$date')");
	else
		return ((sql_value("SELECT DAYOFWEEK('$date')")+5)%7)+1;
}
function aff_month($param) {
	$date=$param[0];
	return sql_value("SELECT MONTH('$date')");	//1 à 12 
}
function aff_0month($param) {
	$date=$param[0];
	return sql_value("SELECT right(concat('0',MONTH('$date')),2)");	//01 à 12 
}
function aff_week($param) {
	$date=$param[0];
	return sql_value("SELECT WEEK('$date',5)") + 1;//0 ... 53
}
function aff_year($param) {
	$date=$param[0];
	return sql_value("SELECT YEAR('$date')");
}

function aff_str_month($param) {
	global $msg;
	$date=$param[0];	
	// param optionnel pour afficher le message dans une autre langue
	$langue=$param[1];
	$local_msg=$msg;
	if($langue)$local_msg=load_lang($langue);

	$month=sql_value("SELECT MONTH('$date')")+1005;//1 à 12 
	return $local_msg{$month};
}
function aff_str_day($param) {
	global $msg;
	$date=$param[0];	
	$langue=$param[1];
	$local_msg=$msg;
	if($langue)$local_msg=load_lang($langue);
	
	$day=((sql_value("SELECT DAYOFWEEK('$date')")+5)%7)+1;
	return $local_msg{"week_days_".$day};
}
function aff_str_short_month($param) {
	global $msg;
	$date=$param[0];	
	// param optionnel pour afficher le message dans une autre langue
	$langue=$param[1];
	$local_msg=$msg;
	if($langue)$local_msg=load_lang($langue);

	$month=sql_value("SELECT MONTH('$date')")+1005;//1 à 12 
	return $local_msg{$month};
}

function aff_str_short_day($param) {
	global $msg;
	$date=$param[0];	
	// param optionnel pour afficher le message dans une autre langue
	$langue=$param[1];
	$local_msg=$msg;
	if($langue)$local_msg=load_lang($langue);
	
	$day=((sql_value("SELECT DAYOFWEEK('$date')")+5)%7)+1;
	return $local_msg{"week_days_short_".$day};
}

function load_lang($lang){
	// localisation (fichier XML)
	global $include_path;
	global $msg_lang;
	//Permet de charger le fichier de lange 1 seule fois
	if(!$msg_lang[$lang]){
		$messages = new XMLlist("$include_path/messages/$lang.xml", 0);
		$messages->analyser();
		$msg_lang[$lang] = $messages->table;
	}
	return 	$msg_lang[$lang];
}

function aff_add($param) {
	return $param[0]+$param[1];
}
function aff_sub($param) {
	return $param[0]-$param[1];
}
function aff_mux($param) {
	return $param[0]*$param[1];
}
function aff_mod($param) {
	return $param[0]%$param[1];
}
function aff_div($param) {
	if($param[1]==0)return 0;
	return abs($param[0]/$param[1]);
}
function aff_sql($param) {
	$rqt=$param[0];
	return sql_value($rqt);
}
function aff_season($param) {
	$date=$param[0];
	$month=sql_value("SELECT MONTH('$date')");
	$days=sql_value("SELECT DAYOFMONTH('$date')");
	$date=sprintf("%d%02d",$month,$days);
	if( ($date >= 321) && ($date < 621) ) return 1;//Printemps
	if( ($date >= 621) && ($date < 923) ) return 2;//Eté
	if( ($date >= 923) && ($date < 1222) ) return 3;//Automne
	return 4;//Hivers
}
function aff_str_season($param) {
	global $msg;
	$langue=$param[1];
	$local_msg=$msg;
	if($langue)$local_msg=load_lang($langue);
	$season=aff_season($param);
	return $local_msg{"season_".$season};	
		
}
function aff_seasonS($param) {
	$season=aff_season($param);	
	if( $season == 1) return 3;//Automne
	if( $season == 2) return 4;//Hivers
	if( $season == 3) return 1;//Printemps
	return 2;//Eté
}
function aff_str_seasonS($param) {
	global $msg;
	$langue=$param[1];
	$local_msg=$msg;
	if($langue)$local_msg=load_lang($langue);
	$season = aff_seasonS($param);
	return $local_msg{"season_".$season};	
}
function aff_add_day($param) {
	global $msg;
	$date=$param[0];
	$nb=$param[1];
	return sql_value("SELECT DATE_ADD('" .$date. "', INTERVAL " .$nb. " DAY)");
}
function aff_add_month($param) {
	global $msg;
	$date=$param[0];
	$nb=$param[1];
	return sql_value("SELECT DATE_ADD('" .$date. "', INTERVAL " .$nb. " MONTH)");
}
function aff_add_year($param) {
	global $msg;
	$date=$param[0];
	$nb=$param[1];
	return sql_value("SELECT DATE_ADD('" .$date. "', INTERVAL " .$nb. " YEAR)");
}
function aff_sub_day($param) {
	global $msg;
	$date=$param[0];
	$nb=$param[1];
	return sql_value("SELECT DATE_SUB('" .$date. "', INTERVAL " .$nb. " DAY)");
}
function aff_sub_month($param) {
	global $msg;
	$date=$param[0];
	$nb=$param[1];
	return sql_value("SELECT DATE_SUB('" .$date. "', INTERVAL " .$nb. " MONTH)");
}
function aff_sub_year($param) {
	global $msg;
	$date=$param[0];
	$nb=$param[1];
	return sql_value("SELECT DATE_SUB('" .$date. "', INTERVAL " .$nb. " YEAR)");
}
function aff_quarter($param) {
	global $msg;
	$date=$param[0];
	return sql_value("SELECT QUARTER('" .$date. "')");
}
function aff_dayofyear($param) {
	global $msg;
	$date=$param[0];
	return sql_value("SELECT DAYOFYEAR('" .$date. "')");
}
function aff_curdate($param) {
	global $msg;
	return sql_value("SELECT CURDATE()");
}
function aff_ucase_firstletter($param) {
	return ucfirst($param);
}
function aff_ucase($param) {
	return strtoupper($param);
}
function aff_ucase_firstletter_word($param) {
	return ucwords($param);
}
