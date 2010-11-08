<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: export_csv.php,v 1.4 2005-08-24 10:31:30 touraine37 Exp $

header('Content-Type: text/x-csv; charset=utf-8');
//header('Content-Type: text/html")');
header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Content-Disposition: inline; filename="toto.csv"');
//header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');

include('../../../classes/XMLlist.class.php');

// on définit les langues existantes

$languages = new XMLlist("languages_csv.xml", 0);
$languages->analyser();
$avail_lang = $languages->table;

$nb_lang = 0;
$messages_list=array();
while(list($cle, $valeur) = each($avail_lang)) {
	// Dans un tableau des codages, la valeur de codage est stockée
	$codage[$nb_lang]=$valeur;
	$lang_name[$nb_lang]=$cle;
	$obj_lang = new XMLlist("../$cle.xml", 0);
	$obj_lang->analyser();
	$lang = $obj_lang->table;
	while (list($key,$val) = each($lang)) {
		$messages_list[$key][$nb_lang]=$val;
	}
	$nb_lang++;
}

while (list($cle,$valeur)=each($messages_list)) {
	echo $cle.";";
	// La première langue est supposée en utf-8 alors que la suivante est en iso
	//$valeur[0]=utf8_encode($valeur[0]); 

	for ($i=0; $i<$nb_lang; $i++) {
	// Si le codage de la langue n'est pas en utf-8 alors on encode
		if ($codage[$i]!="utf-8") {$valeur[$i]=utf8_encode($valeur[$i]);}
		// la fonction de détection résultat=source est désactivée, faute de pouvoir mettre en relief
		// l'égalité
		//		$valeur1=$valeur;
		//		$valeur1[$i]="";
		// $as=array_search($valeur[$i],$valeur1);
		// if (($as!==null)&&($as!==false)) $color="#dddddd"; else $color="#ffffff";
		
		//Si la langue choisie est l'arabe, alors il faut inverser les caractères à l'affichage
		
		// if($lang_name[$i]!="ar") {print "<SPAN DIR='RTL'>";} else {print "<span dir='LTR'>";} 
		
		if ($valeur[$i]!="") echo $valeur[$i]; else 
		{
			if ($codage[$i]!="utf-8") 
				{ echo utf8_encode("PLEASE TRANSLATE");} 
			else echo "PLEASE TRANSLATE";
		}
		// print "</SPAN>";
		echo ";";
	}
	echo "\n";
}

?>
