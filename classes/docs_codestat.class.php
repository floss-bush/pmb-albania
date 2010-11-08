<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: docs_codestat.class.php,v 1.5 2007-03-10 09:25:49 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

// définition de la classe de gestion des 'docs_codestat'

if ( ! defined( 'DOCSCODESTAT_CLASS' ) ) {
  define( 'DOCSCODESTAT_CLASS', 1 );

class docs_codestat {

/* ---------------------------------------------------------------
		propriétés de la classe
   --------------------------------------------------------------- */

var $id=0;
var $libelle='';
var $statisdoc_codage_import="";
var $statisdoc_owner=0;

/* ---------------------------------------------------------------
		docs_codestat($id) : constructeur
   --------------------------------------------------------------- */

function docs_codestat($id=0) {
	if($id) {
		/* on cherche à atteindre un code statistique existant */
		$this->id = $id;
		$this->getData();
		} else {
			$this->id = 0;
			$this->getData();
			}
	}

/* ---------------------------------------------------------------
		getData() : récupération des propriétés
   --------------------------------------------------------------- */

function getData() {
	global $dbh;

	if(!$this->id) return;

	/* récupération des informations du code statistique */

	$requete = 'SELECT * FROM docs_codestat WHERE idcode='.$this->id.' LIMIT 1;';
	$result = @mysql_query($requete, $dbh);
	if(!mysql_num_rows($result)) return;
		
	$data = mysql_fetch_object($result);
	$this->id = $data->idscodestat;		
	$this->libelle = $data->codestat_libelle;
	$this->statisdoc_codage_import = $data->statisdoc_codage_import;
	$this->statisdoc_owner = $data->statisdoc_owner;

	}

// ---------------------------------------------------------------
//		import() : import d'un code statistique de document
// ---------------------------------------------------------------
function import($data) {

	// cette méthode prend en entrée un tableau constitué des informations suivantes :
	//	$data['codestat_libelle'] 	
	//	$data['statisdoc_codage_import']
	//	$data['statisdoc_owner']

	global $dbh;

	// check sur le type de la variable passée en paramètre
	if(!sizeof($data) || !is_array($data)) {
		// si ce n'est pas un tableau ou un tableau vide, on retourne 0
		return 0;
		}
	// check sur les éléments du tableau
	
	$long_maxi = mysql_field_len(mysql_query("SELECT codestat_libelle FROM docs_codestat limit 1"),0);
	$data['codestat_libelle'] = rtrim(substr(preg_replace('/\[|\]/', '', rtrim(ltrim($data['codestat_libelle']))),0,$long_maxi));
	$long_maxi = mysql_field_len(mysql_query("SELECT statisdoc_codage_import FROM docs_codestat limit 1"),0);
	$data['statisdoc_codage_import'] = rtrim(substr(preg_replace('/\[|\]/', '', rtrim(ltrim($data['statisdoc_codage_import']))),0,$long_maxi));

	if($data['statisdoc_owner']=="") $data['statisdoc_owner'] = 0;
	if($data['codestat_libelle']=="") return 0;
	/* statisdoc_codage_import est obligatoire si statisdoc_owner != 0 */
	// commenté depuis le choix de quel codage rec995 on récupère if(($data['statisdoc_owner']!=0) && ($data['statisdoc_codage_import']=="")) return 0;
	
	// préparation de la requête
	$key0 = addslashes($data['codestat_libelle']);
	$key1 = addslashes($data['statisdoc_codage_import']);
	$key2 = $data['statisdoc_owner'];
	
	/* vérification que le code statistique existe */
	$query = "SELECT idcode FROM docs_codestat WHERE statisdoc_codage_import='${key1}' and statisdoc_owner = '${key2}' LIMIT 1 ";
	$result = @mysql_query($query, $dbh);
	if(!$result) die("can't SELECT docs_codestat ".$query);
	$docs_codestat  = mysql_fetch_object($result);

	/* le code statistique de doc existe, on retourne l'ID */
	if($docs_codestat->idcode) return $docs_codestat->idcode;

	// id non-récupérée, il faut créer la forme.
	
	$query  = "INSERT INTO docs_codestat SET ";
	$query .= "codestat_libelle='".$key0."', ";
	$query .= "statisdoc_codage_import='".$key1."', ";
	$query .= "statisdoc_owner='".$key2."' ";
	$result = @mysql_query($query, $dbh);
	if(!$result) die("can't INSERT into docs_codestat ".$query);

	return mysql_insert_id($dbh);

	} /* fin méthode import */



} /* fin de définition de la classe */

} /* fin de délaration */


