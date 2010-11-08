<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: start_export_caddie.php,v 1.20 2009-12-09 13:22:57 mbertin Exp $

//Exécution de l'export
$base_path = "../..";
$base_auth = "CATALOGAGE_AUTH";
$base_title = "\$msg[export_title]";
require ($base_path."/includes/init.inc.php");

require_once ("$include_path/parser.inc.php");
require_once ("$base_path/admin/convert/export.class.php");
require_once("$class_path/caddie.class.php");
require_once("$class_path/export_param.class.php");

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

//Initialisation si première fois
if (file_exists("imports/catalog_subst.xml"))
	$fic_catal = "imports/catalog_subst.xml";
else
	$fic_catal = "imports/catalog.xml";

if ($first != 1) {
	//mysql_query("delete from import_marc");

	$origine=str_replace(" ","",microtime());
	$origine=str_replace("0.","",$origine);

	//Récupération du répertoire
	$i = 0;
	$param_path == "";
	
	_parser_($fic_catal, array("ITEM" => "_item_"), "CATALOG");

	//Lecture des paramètres
	_parser_("imports/".$param_path."/params.xml", array("OUTPUT" => "_output_", "INPUT" => "_input_"), "PARAMS");
	
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
		case "txt":
			require_once ("imports/output_txt.inc.php");
			break;
		case "custom" :
			require_once ("imports/$param_path/".$output_params['SCRIPT']);
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

//Récupération des notices
$n_notices=0;
$myCart=new caddie($idcaddie);
//Pour le cas ou on a un panier d'exemplaire avec des exemplaires de bulletin
$bulletin_a_exporter=array();
switch ($myCart->type) {
	case "NOTI" :
		$liste_flag=array();
		$liste_no_flag=array();
		if ($elt_flag) {
			$liste_flag=$myCart->get_cart("FLAG");
		}
		if ($elt_no_flag) {
			$liste_no_flag=$myCart->get_cart("NOFLAG");
		}
		$liste=$liste_flag;
		for ($i=0; $i<count($liste_no_flag); $i++) {
			$liste[]=$liste_no_flag[$i];
		}
		break;
	case "EXPL" :
		$liste_flag=array();
		$liste_no_flag=array();
		if ($elt_flag) {
			$liste_flag=$myCart->get_cart("FLAG");
		}
		if ($elt_no_flag) {
			$liste_no_flag=$myCart->get_cart("NOFLAG");
		}
		$liste=$liste_flag;
		for ($i=0; $i<count($liste_no_flag); $i++) {
			$liste[]=$liste_no_flag[$i];
		}
		$requete="create temporary table expl_cart_id (id integer) ENGINE=MyISAM ";
		mysql_query($requete);
		for ($i=0; $i<count($liste); $i++) {
			$requete="insert into expl_cart_id (id) values($liste[$i])";
			mysql_query($requete);
		}
		//Récupération des id notices
		$requete="select expl_notice from exemplaires, expl_cart_id where expl_notice!=0 and expl_id=id group by expl_notice";
		$resultat=mysql_query($requete);
		$liste=array();
		while (list($id)=mysql_fetch_row($resultat)) {
			$liste[]=$id;
		}
		if($keep_expl && $_SESSION["param_export"]["genere_lien"] ){
			//Récupération des id de bulletin si on exporte les exemplaires
			$requete="select expl_bulletin from exemplaires, expl_cart_id where expl_bulletin!=0 and expl_id=id group by expl_bulletin";
			$resultat=mysql_query($requete);
			while (list($id)=mysql_fetch_row($resultat)) {
				$bulletin_a_exporter[]=$id;
			}
			if(!count($liste)){
				//Il faut au moin une notice de monographie pour que l'export des exemplaires de bulletin soit réalisé
				$liste[]=0;
			}
		}
		break;
	case "BULL" :
		$liste=array();
		$liste_flag=array();
		$liste_no_flag=array();
		if ($elt_flag) {
			$liste_flag=$myCart->get_cart("FLAG");
		}
		if ($elt_no_flag) {
			$liste_no_flag=$myCart->get_cart("NOFLAG");
		}
		$liste=$liste_flag;
		for ($i=0; $i<count($liste_no_flag); $i++) {
			$liste[]=$liste_no_flag[$i];
		}
		$requete="create temporary table bull_cart_id (id integer) ENGINE=MyISAM ";
		mysql_query($requete);
		for ($i=0; $i<count($liste); $i++) {
			$requete="insert into bull_cart_id (id) values($liste[$i])";
			mysql_query($requete);
		}
		//Récupération des id notices
		$requete="select analysis_notice from analysis, bull_cart_id  where analysis_bulletin=id group by analysis_notice";
		$resultat=mysql_query($requete);
		$liste=array();
		while (list($id)=mysql_fetch_row($resultat)) {
			$liste[]=$id;
		}
		break;
}
$n_notices=count($liste);

if ($first!=1) {
	$_SESSION["param_export"]["notice_exporte"]="";
	$_SESSION["param_export"]["bulletin_exporte"]="";
	//On enregistre les variables postées dans la session
	export_param::init_session();
	
	$fo = fopen("$base_path/temp/".$file_out, "w+");
	//Entête
	@ fwrite($fo, _get_header_($output_params));
	fclose($fo);
} 

if ($n_notices == 0) {
	error_message_history($msg["export_no_notice_found"], $msg["export_no_notice_for_criterias"], 1);
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
//Recherche du no_notice le plus grand
$requete="select max(no_notice) from import_marc where origine='$origine'";
$resultat=mysql_query($requete);
$no_notice=mysql_result($resultat,0,0)*1+1;

$z = 0;
if($_SESSION["param_export"]["notice_exporte"]) $notice_exporte = $_SESSION["param_export"]["notice_exporte"]; 
else $notice_exporte=array();
if($_SESSION["param_export"]["bulletin_exporte"]) $bulletin_exporte = $_SESSION["param_export"]["bulletin_exporte"]; 
else $bulletin_exporte=array();
while (($z<200)&&(($n_current+$z)<count($liste))) {
	$id=$liste[$n_current+$z];
	if (!$specialexport) {
		$e_notice=array();
		$param = new export_param(EXP_SESSION_CONTEXT);	
		$e = new export(array($id),$notice_exporte, $bulletin_exporte);
		//Pour le cas ou on exporte les exemplaires et que l'on avait un panier d'exemplaire avec des bulletins
		if(count($bulletin_a_exporter)){
			for($b=0;$b<count($bulletin_a_exporter);$b++){
				if(array_search($bulletin_a_exporter[$b],$bulletin_exporte)===false){
					//Si le bulletin ne fait pas partie de ceux déjà exporté
					$e->expl_bulletin_a_exporter[]=$bulletin_a_exporter[$b];
				}
			}
		}
		if($id){//Pour éviter des erreurs si on export des exemplaires de bulletin sans monographie a partir d'un panier d'exemplaire
			do {
				$nn=$e -> get_next_notice($lender, $td, $sd, $keep_expl,$param->get_parametres($param->context) );
				if ($e->notice) $e_notice[]=$e->notice;
			} while ($nn);
			$notice_exporte=$e->notice_exporte;
		}
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
$query.="&elt_flag=$elt_flag&elt_no_flag=$elt_no_flag&idcaddie=$idcaddie";
$query.= "&export_type=".$export_type."&first=1&keep_expl=$keep_expl&origine=$origine";

if ($z < 200) {
	//Fin de l'export ??
	echo "<script>setTimeout(\"document.location='start_import.php?first=1&import_type=$export_type&file_in=export".$origine.".fic&noimport=1&origine=$origine'\",1000)</script>";
	$_SESSION["param_export"]["notice_exporte"]='';
	$_SESSION["param_export"]["bulletin_exporte"]='';
} else {
	//Lot suivant
	$_SESSION["param_export"]["notice_exporte"]=$notice_exporte;
	$_SESSION["param_export"]["bulletin_exporte"]=$bulletin_exporte;
	echo "<script>setTimeout(\"document.location='start_export_caddie.php?".$query."'\",1000);</script>";
}

?>