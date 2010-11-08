<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// | creator : Yves PRATTER                                                   |
// +-------------------------------------------------+
// $Id: explnum_doc.php,v 1.4 2010-02-23 16:27:22 kantin Exp $

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

$req_docnum = "SELECT explnum_doc_nomfichier, explnum_doc_mimetype, explnum_doc_data, explnum_doc_extfichier,explnum_doc_url as url
			FROM explnum_doc WHERE id_explnum_doc = '$explnumdoc_id' ";
$resultat = mysql_query($req_docnum, $dbh);
$nb_res = mysql_num_rows($resultat) ;

if (!$nb_res) {
	header("Location: images/mimetype/unknown.gif");
	exit ;
} 
	
$ligne = mysql_fetch_object($resultat);
if ($ligne->explnum_doc_data) {
	create_tableau_mimetype() ;
	$name=$_mimetypes_bymimetype_[$ligne->explnum_mimetype]["plugin"] ;
	if ($name) {
		$type = "" ;
		// width='700' height='525' 
		$name = " name='$name' ";
	} else $type="type='$ligne->explnum_mimetype'" ;
	if ($_mimetypes_bymimetype_[$ligne->explnum_mimetype]["embeded"]=="yes") {
		print "<html><body><EMBED src=\"./explnum_doc_data.php?explnumdoc_id=$explnumdoc_id\" $type $name controls='console' ></EMBED></body></html>" ;
		exit ;
	}
	
	$nomfichier="";
	if ($ligne->explnum_doc_nomfichier) {
		$nomfichier=$ligne->explnum_doc_nomfichier;
	}
	elseif ($ligne->explnum_doc_extfichier)
		$nomfichier="pmb".$ligne->explnum_id.".".$ligne->explnum_doc_extfichier;
	if ($nomfichier) header("Content-Disposition: inline; filename=".$nomfichier);
	
	header("Content-Type: ".$ligne->explnum_doc_mimetype);
	print $ligne->explnum_doc_data;
	exit ;
} 
if ($ligne->explnum_doc_mimetype=="URL") {
	if ($ligne->url) header("Location: $ligne->url");
	exit ;
}
	
?>