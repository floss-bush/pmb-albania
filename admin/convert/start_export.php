<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: start_export.php,v 1.16 2009-09-01 13:43:33 mbertin Exp $

//Exécution de l'export
$base_path = "../..";
$base_auth = "ADMINISTRATION_AUTH";
$base_title = "\$msg[export_title_only]";
require ($base_path."/includes/init.inc.php");

require_once ("$include_path/parser.inc.php");
require_once ("$base_path/admin/convert/export.class.php");
require_once($class_path."/export_param.class.php");

//Récupération du chemin du fichier de paramétrage de l'import
function _item_($param) {
	global $export_type;
	global $i;
	global $param_path;
	global $export_type_l;

	if ($i == $export_type) {
		$param_path = $param['PATH'];
		$export_type_l = $param['NAME'];
	}
	$i ++;
}

//Récupération du paramètre d'import
function _output_($param) {
	global $output;
	global $output_type;
	global $output_params;

	$output = $param['IMPORTABLE'];
	$output_type = $param['TYPE'];
	$output_params = $param;
}

function _input_($param) {
	global $specialexport;
	
	if ($param["SPECIALEXPORT"]=="yes") {
		$specialexport=true; 
	} else $specialexport=false;
}

if (file_exists("imports/catalog_subst.xml"))
	$fic_catal = "imports/catalog_subst.xml";
else
	$fic_catal = "imports/catalog.xml";

//Initialisation si première fois
if ($first != 1) {
	//mysql_query("delete from import_marc");

	$origine=str_replace(" ","",microtime());
	$origine=str_replace("0.","",$origine);

	//Récupération du répertoire
	$i = 0;
	$param_path = "";
	_parser_($fic_catal, array("ITEM" => "_item_"), "CATALOG");

	//Lecture des paramètres
	_parser_("imports/".$param_path."/params.xml", array("OUTPUT" => "_output_","INPUT" => "_input_"), "PARAMS");

	//Si l'export est spécial, on charge la fonction d'export
	if ($specialexport) require_once("imports/".$param_path."/export.inc.php");

	//En fonction du type de fichier de sortie, inclusion du script de gestion des sorties
	switch ($output_type) {
		case "xml" :
			require_once ("imports/output_xml.inc.php");
			break;
		case "iso_2709" :
			require_once ("imports/output_iso_2709.inc.php");
			break;
		case "custom" :
			require_once ("imports/$param_path/".$output_params['SCRIPT']);
			break;
		case "txt":
			require_once ("imports/output_txt.inc.php");
			break;
		default :
			die($msg["export_cant_find_output_type"]);
	}

	//Création du fichier de sortie
	$file_out = "export".$origine.".".$output_params['SUFFIX']."~";
} else {
	//Récupération du répertoire
	$i = 0;
	$param_path == "";
	_parser_($fic_catal, array("ITEM" => "_item_"), "CATALOG");

	//Lecture des paramètres
	_parser_("imports/".$param_path."/params.xml", array("OUTPUT" => "_output_", "INPUT" => "_input_"), "PARAMS");
	
	//Si l'export est spécial, on charge la fonction d'export
	if ($specialexport) require_once("imports/".$param_path."/export.inc.php");
}

//Requête de sélection et de comptage des notices
if ($n_current == "")
	$n_current = 0;

$typdoc = "typdoc$lender";
$td = $$typdoc;
$statutdoc = "statut$lender";
$sd = $$statutdoc;

$requete = "select notice_id from notices";
$requete_count = "select count(distinct notice_id) from notices";

if (($lender != "x") || ($td != "") || ($sd != "")) {
	$requete.= " , exemplaires";
	$requete_count.= " , exemplaires";
	$requete_where.= " expl_notice=notice_id";
}

if ($lender != "x") {
	$requete_where.= " and expl_owner=$lender";
}

if ($td != "") {
	$l_td = implode(",", $td);
	$requete_where.= " and expl_typdoc in (".$l_td.")";

}

if ($sd != "") {
	$l_sd = implode(",", $sd);
	$requete_where.= " and expl_statut in (".$l_sd.")";
}

if ($requete_where != "") {
	$requete.= " where ".$requete_where;
	$requete_count.= " where ".$requete_where;
}

$requete.= " group by notice_id limit $n_current,200";

//Nombre de notices correspondantes aux critères
$resultat = mysql_query($requete_count);
$n_notices = mysql_result($resultat, 0, 0);

if ($first!=1) {
	$_SESSION["param_export"]["notice_exporte"]="";
	//On enregistre les variables postées dans la session
	export_param::init_session();
	$fo = fopen("$base_path/temp/".$file_out, "w+");
	//Entête
	@ fwrite($fo, _get_header_($output_params));
	fclose($fo);
}

if ($n_notices == 0) {
	error_message($msg["export_no_notice_found"], $msg["export_no_notice_for_criterias"], 1, "export.php");
	exit;
}

//Affichage de la progression
$percent = @ round(($n_current / $n_notices) * 100);
if ($percent == 0)
	$percent = 1;
echo "<center><h3>".$msg["export_running"]."</h3></center><br />\n";
echo "<table align=center width=100%><tr><td style=\"border-width:1px;border-style:solid;border-color:#FFFFFF;\" width=100%><img src=\"$base_path/images/jauge.png\" width=\"".$percent."%\" height=\"16\"></td></tr><tr><td ><center>".round($percent)."%</center></td></tr></table>\n";
echo "<center>".sprintf($msg["export_progress"],$n_current,$n_notices,($n_notices - $n_current))."</center>";

//Début d'export du lot
$resultat = mysql_query($requete);

//Recherche du no_notice le plus grand
$requete_max="select max(no_notice) from import_marc where origine='$origine'";
$resultat_max=mysql_query($requete_max);
$no_notice=mysql_result($resultat_max,0,0)*1+1;

$z = 0;
if($_SESSION["param_export"]["notice_exporte"]) $notice_exporte = $_SESSION["param_export"]["notice_exporte"]; 
else $notice_exporte=array();
if($_SESSION["param_export"]["bulletin_exporte"]) $bulletin_exporte = $_SESSION["param_export"]["bulletin_exporte"]; 
else $bulletin_exporte=array();
while (list ($id) = mysql_fetch_row($resultat)) {
	if (!$specialexport) {
		$e_notice=array();
		$param = new export_param(EXP_SESSION_CONTEXT);	
		$e = new export(array($id),$notice_exporte, $bulletin_exporte);		
		do {
			$nn=$e -> get_next_notice($lender, $td, $sd, $keep_expl, $param->get_parametres($param->context));
			if ($e->notice) $e_notice[]=$e->notice;
		} while ($nn);
		$notice_exporte=$e->notice_exporte;
		//Pour les exemplaires de bulletin
		do {
			$nn=$e -> get_next_bulletin($lender, $td, $sd, $keep_expl,$param->get_parametres($param->context));
			if ($e->notice) $e_notice[]=$e->notice;
		} while ($nn);		
		$bulletin_exporte=$e->bulletins_exporte;
	} else {
		$e_notice = _export_($id,$keep_expl);
	}
	if (!is_array($e_notice)) {
		$requete = "insert into import_marc (no_notice, notice, origine) values($no_notice,'".addslashes($e_notice)."', '$origine')";
		mysql_query($requete);
		$no_notice++;
		$z ++;
	} else {
		for($i=0; $i<sizeof($e_notice);$i++) {
			$requete = "insert into import_marc (no_notice, notice, origine) values($no_notice,'".addslashes($e_notice[$i])."', '$origine')";
			mysql_query($requete);
			$no_notice++;
		}
		$z ++;
	}
}


//Paramètres passés pour l'appel suivant
$query = "n_current=". ($n_current + $z);
for ($i = 0; $i < count($td); $i ++) {
	$query.= "&".$typdoc."[$i]=".$td[$i];
}
for ($i = 0; $i < count($sd); $i ++) {
	$query.= "&".$statutdoc."[$i]=".$sd[$i];
}
$query.= "&lender=$lender&export_type=".$export_type."&first=1&keep_expl=$keep_expl&origine=$origine";

if ($z < 200) {
	//Fin de l'export ??
	echo "<script>setTimeout(\"document.location='start_import.php?first=1&import_type=$export_type&file_in=export".$origine.".fic&noimport=1&origine=$origine'\",1000)</script>";
	$_SESSION["param_export"]["notice_exporte"]='';
	$_SESSION["param_export"]["bulletin_exporte"]='';
} else {
	$_SESSION["param_export"]["notice_exporte"]=$notice_exporte;
	$_SESSION["param_export"]["bulletin_exporte"]=$bulletin_exporte;
	//Lot suivant
	echo "<script>setTimeout(\"document.location='start_export.php?".$query."'\",1000);</script>";
}

?>