<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: start_import.php,v 1.20 2010-11-04 11:13:28 arenou Exp $

//Execution de l'import
$base_path = "../..";
$base_auth = "ADMINISTRATION_AUTH|CATALOGAGE_AUTH";
$base_title = "\$msg[ie_import_running]";
require ($base_path."/includes/init.inc.php");
require_once ("$include_path/parser.inc.php");

//Récupération du chemin du fichier de paramétrage de l'import
function _item_($param) {
	global $import_type;
	global $i;
	global $param_path;
	global $import_type_l;

	if (($i == $import_type) || ($import_type == $param['PATH'])) {
		$param_path = $param['PATH'];
		$import_type_l = $param['NAME'];
	}
	$i ++;
}

//Récupération du nom de l'import
function _import_name_($param) {
	global $import_name;

	$import_name = $param['value'];
}

//Récupération du nombre de notices à traiter par passe
function _n_per_pass_($param) {
	global $n_per_pass;

	$n_per_pass = $param['value'];
}

//Récupération du type d'entrée
function _input_($param) {
	global $input_type;
	global $input_params;

	$input_type = $param['TYPE'];
	$input_params = $param;
}

//Récupération des étapes de conversion
function _step_($param) {
	global $step;

	$step[] = $param;
}

//Récupération du paramètre d'import
function _output_($param) {
	global $output;
	global $output_type;
	global $output_params;

	$output = $param['IMPORTABLE'];
	$output_type = $param['TYPE'];
	$output_params=$param;
}

//Lecture des paramètres d'import

//Récupération du répertoire
$i = 0;
$param_path = "";

if (file_exists("imports/catalog_subst.xml"))
	$fic_catal = "imports/catalog_subst.xml";
else
	$fic_catal = "imports/catalog.xml";

_parser_($fic_catal, array("ITEM" => "_item_"), "CATALOG");

//Lecture des paramètres
_parser_("imports/".$param_path."/params.xml", array("IMPORTNAME" => "_import_name_", "NPERPASS" => "_n_per_pass_", "INPUT" => "_input_", "STEP" => "_step_", "OUTPUT" => "_output_"), "PARAMS");

//Inclusion des librairies éventuelles
for ($i = 0; $i < count($step); $i ++) {
	if ($step[$i]['TYPE'] == "custom") {
		//echo "imports/".$param_path."/".$step[$i][SCRIPT][0][value];
		require_once ("imports/".$param_path."/".$step[$i]['SCRIPT'][0]['value']);
	}
}

require_once ("xmltransform.php");

//En fonction du type de fichier d'entrée, inclusion du script de gestion des entrées
switch ($input_type) {
	case "xml" :
		require_once ("imports/input_xml.inc.php");
		break;
	case "iso_2709" :
		require_once ("imports/input_iso_2709.inc.php");
		break;
	case "text" :
		require_once("imports/input_text.inc.php");
		break;
	case "custom" :
		require_once ("imports/$param_path/".$input_params['SCRIPT']);
		break;
	default :
		die($msg["ie_import_entry_not_valid"]);
}

//En fonction du type de fichier de sortie, inclusion du script de gestion des sorties
switch ($output_type) {
	case "xml" :
		require_once ("imports/output_xml.inc.php");
		break;
	case "txt" :
		require_once ("imports/output_txt.inc.php");
		break;
	case "iso_2709" :
		require_once("imports/output_iso_2709.inc.php");
		break;
	case "custom" :
		require_once ("imports/$param_path/".$output_params['SCRIPT']);
		break;
	default :
		die($msg["ie_output_type_not_valid"]);
}

//Si premier accès
if (!$first) {
	$origine=str_replace(" ","",microtime());
	$origine=str_replace("0.","",$origine);
	
	//Copie du fichier dans le répertoire temporaire
	if ($_FILES['import_file']['name']) {
		if (!@ copy($_FILES['import_file']['tmp_name'], "$base_path/temp/".$origine.$_FILES['import_file']['name'])) {
				error_message_history($msg["ie_tranfert_error"], $msg["ie_transfert_error_detail"], 1);
				exit;
		} else	$file_in = $origine.$_FILES['import_file']['name'];
	} else if ($file_in && file_exists($base_path."/temp/".$file_in)){
		
	} else {
		if (!$file_in) $file_in = "convert".(defined("LOCATION")?"_".constant("LOCATION"):"").".fic";
		if (!file_exists($base_path."/temp/convert".(defined("LOCATION")?"_".constant("LOCATION"):"").".fic")) {
				error_message_history($msg["ie_file_not_found"], sprintf($msg["ie_file_not_found_detail"],$file_in), 1);
				exit;
		}
	}

	//Première notice = 0
	$n_current = 0;

	//Nombre d'erreurs = 0;
	$n_errors = 0;

	//Création du fichier de sortie
	$f = explode(".", $file_in);
	if (count($f) > 1) {
		unset($f[count($f) - 1]);
	}
	$file_out = implode(".", $f).".".$output_params['SUFFIX']."~";

	$fo = fopen("$base_path/temp/".$file_out, "w+");

	//Ouverture du fichier d'origine
	$fi = fopen("$base_path/temp/".$file_in, "r");

	//Récupération du nombre de notices et enregistrement dans la base de données des notices
	$index = _get_n_notices_($fi, "$base_path/temp/".$file_in, $input_params,$origine);
	if (count($index) == 0) {
		error_message_history($msg["ie_empty_file"], sprintf($msg["ie_empty_file_detail"],$import_type_l), 1);
		exit;
	}

	//Entête
	@ fwrite($fo, _get_header_($output_params));
	fclose($fo);

	//Vidage de la table de log
	//mysql_query("delete from error_log where error_origin='convert.log'");
}

function convert_notice($notice,$encoding) {
	global $step;
	global $param_path;
	global $n_errors;
	global $message_convert;
	global $n_current;
	global $z;

	for ($i = 0; $i < count($step); $i ++) {
		$s = $step[$i];
		//si on a un encodage sur la notice, on le rajoute aux parametres
		if($encoding) $s['ENCODING'] = $encoding;
		$islast=($i==count($step)-1);
		$isfirst=($i==0);
		switch ($s[TYPE]) {
				case "xmltransform" :
					$r = perform_xslt($notice, $s, $islast, $isfirst, $param_path);
					break;
				case "toiso" :
					$r = toiso($notice, $s, $islast, $isfirst, $param_path);
					break;
				case "isotoxml" :
					$r = isotoxml($notice, $s, $islast, $isfirst, $param_path);
					break;
				case "texttoxml":
					$r = texttoxml($notice, $s, $islast, $isfirst, $param_path);
					break;
				case "custom" :
					eval("\$r=".$s['CALLBACK'][0][value]."(\$notice, \$s, \$islast, \$isfirst, \$param_path);");
					break;
		}
		if (!$r['VALID']) {
				$n_errors ++;
				$message_convert.= "<b>Notice ". ($n_current + $z)." : </b>".$r['ERROR']."<br />\n";
				$notice = "";
				break;
		} else {
				$notice = $r['DATA'];
				if($r['WARNING']){
					$n_errors ++;
					$message_convert.= "<b>Notice ". ($n_current + $z)." : </b>".$r['WARNING']."<br />\n";
				}
		}
	}
	return $notice;
}

$requete="select count(1) from import_marc where origine='$origine'";
$resultat=mysql_query($requete);
$n_notices=mysql_result($resultat,0,0);

$percent = @ round(($n_current / $n_notices) * 100);
if ($percent == 0)
	$percent = 1;
echo "<center><h3>".$msg["conversion_en_cours"]."</h3></center><br />\n";

echo "<table align=center width=100%><tr><td style=\"border-width:1px;border-style:solid;border-color:#FFFFFF;\" width=100%><img src=\"$base_path/images/jauge.png\" width=\"".$percent."%\" height=\"16\"></td></tr><tr><td ><center>".round($percent)."%</center></td></tr></table>\n";

echo "<center>".sprintf($msg["ie_processed_notices"],$n_current,$n_notices,($n_notices - $n_current))."</center>";
$z = 0;

//Ouverture du fichier final
$f = explode(".", $file_in);
if (count($f) > 1) {
	unset($f[count($f) - 1]);
}
$file_out = implode(".", $f).".".$output_params['SUFFIX']."~";

$fo = fopen("$base_path/temp/".$file_out, "r+");

//Positionnement a la fin du fichier
fseek($fo, filesize("$base_path/temp/".$file_out));

for ($i = $n_current; $i < $n_current + $n_per_pass; $i ++) {
	$requete="select notice, encoding from import_marc where no_notice=".($i+1)." and origine='$origine'";
	$resultat=mysql_query($requete);

	if (mysql_num_rows($resultat)!=0) {
		//Si la notice existe début de conversion
		$obj=mysql_fetch_object($resultat);
		$notice_ = convert_notice($obj->notice,$obj->encoding);
		@fwrite($fo, $notice_);
		$z ++;
	} else
		break;
}

//Y-a-t-il eu des erreurs ?
if ($message_convert != "") {
	$requete="insert into error_log (error_date,error_origin, error_text) values(now(),'convert.log ".$origine."','".addslashes($message_convert)."')";
	mysql_query($requete);
	echo mysql_error();
}

//Fin du fichier de notice ?
if ($z < $n_per_pass) {
	$n_current = $n_current + $z;
	@ fwrite($fo, _get_footer_($output_params));
	fclose($fo);
	$requete="delete from import_marc where origine='$origine'";
	mysql_query($requete);
	if ($redirect) {
		$redirect .= "&file_in=".rawurlencode($file_in)."&suffix=".$output_params['SUFFIX']."&origine=".$origine."&import_type=$import_type&outputtype=".$output_params["TYPE"]."&converted=1";
		$location = "parent.document.location='".$redirect."'";
	}
	else
		$location = "document.location='end_import.php?file_in=".rawurlencode($file_in)."&first=1&n_current=".$n_current."&import_type=".$import_type."&n_total=".count($index)."&n_errors=$n_errors&output=$output&suffix=".$output_params['SUFFIX']."&import_type_l=".rawurlencode($import_type_l)."&noimport=$noimport".$redirect_string."&mimetype=".rawurlencode($output_params['MIMETYPE'])."&origine=".$origine."'"; 
	echo "<script>setTimeout(\"$location\",1000);</script>";
} else {
	//Si pas fin, recharegement de la page pour les $n_per_pass_suivants
	$n_current = $n_current + $n_per_pass;
	fclose($fo);
	
	if ($redirect)
		$redirect_string = "&redirect=".urlencode($redirect);
	else 
		$redirect_string = "";
			 
	echo "<script>setTimeout(\"document.location='start_import.php?file_in=".rawurlencode($file_in)."&first=1&n_current=".$n_current."&import_type=".$import_type."&n_errors=$n_errors&noimport=$noimport".$redirect_string."&origine=".$origine."'\",1000);</script>";
}
?>