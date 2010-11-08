<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// | creator : Yves PRATTER                                                   |
// +-------------------------------------------------+
// $Id: doc_num.php,v 1.12 2009-10-16 15:51:30 gueluneau Exp $

// définition du minimum nécéssaire 
$base_path     = ".";                            
$base_auth     = ""; //"CIRCULATION_AUTH";  
$base_title    = "";    
$base_noheader = 1;
$base_nocheck  = 1;
$base_nobody   = 1;
$base_nosession   = 1;
require_once ("$base_path/includes/init.inc.php");  
require_once ("$include_path/explnum.inc.php");  
require_once ($class_path."/upload_folder.class.php"); 

$resultat = mysql_query("SELECT explnum_id, explnum_notice, explnum_bulletin, explnum_nom, explnum_nomfichier, explnum_mimetype, explnum_url, 
			explnum_data, explnum_extfichier, explnum_path, concat(repertoire_path,explnum_path,explnum_nomfichier) as path, repertoire_id
			FROM explnum left join upload_repertoire on repertoire_id=explnum_repertoire WHERE explnum_id = '$explnum_id' ", $dbh);
$nb_res = mysql_num_rows($resultat) ;

if (!$nb_res) {
	header("Location: images/mimetype/unknown.gif");
	exit ;
	} 
	
$ligne = mysql_fetch_object($resultat);
if (($ligne->explnum_data)||($ligne->explnum_path)) {
	
	if ($ligne->explnum_path) {
		$up = new upload_folder($ligne->repertoire_id);
		$path = str_replace("//","/",$ligne->path);
		$path=$up->encoder_chaine($path);
		$fo = fopen($path,'rb');
		$ligne->explnum_data=fread($fo,filesize($path));
		fclose($fo);
	}
	
	create_tableau_mimetype() ;
	$name=$_mimetypes_bymimetype_[$ligne->explnum_mimetype]["plugin"] ;
	if ($name) {
		$type = "" ;
		// width='700' height='525' 
		$name = " name='$name' ";
	} else $type="type='$ligne->explnum_mimetype'" ;
	if ($_mimetypes_bymimetype_[$ligne->explnum_mimetype]["embeded"]=="yes") {
		print "<html><body><EMBED src=\"./doc_num_data.php?explnum_id=$explnum_id\" $type $name controls='console' ></EMBED></body></html>" ;
		exit ;
	}
	
	$nomfichier="";
	if ($ligne->explnum_nomfichier) {
		$nomfichier=$ligne->explnum_nomfichier;
	}
	elseif ($ligne->explnum_extfichier)
		$nomfichier="pmb".$ligne->explnum_id.".".$ligne->explnum_extfichier;
	if ($nomfichier) header("Content-Disposition: inline; filename=".$nomfichier);
	
	header("Content-Type: ".$ligne->explnum_mimetype);
	print $ligne->explnum_data;
	exit ;
}
	
if ($ligne->explnum_mimetype=="URL") {
	if ($ligne->explnum_url) header("Location: $ligne->explnum_url");
	exit ;
}

//if($ligne->explnum_path){
//	$up = new upload_folder($ligne->repertoire_id);
//	$path = str_replace("//","/",$ligne->path);
//	$path=$up->encoder_chaine($path);
//	$fo = fopen($path,'rb');
//	header("Content-Type: ".$ligne->explnum_mimetype);
//	fpassthru($fo);
//	exit;
	
//}
