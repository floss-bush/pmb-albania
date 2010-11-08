<?php
// +----------------------------------------------------------------------------------------+
// © 2002-2006 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +----------------------------------------------------------------------------------------+
// $Id: index.php,v 1.7 2006-12-04 16:24:31 touraine37 Exp $

//enregistrement des variables get et post en variables globales
error_reporting(E_ERROR);
require_once ("../includes/global_vars.inc.php");  
//définition du frameset
echo "<HTML>
<HEAD>
	<TITLE>Documentation PMB</TITLE>
</HEAD>";

//affichage ou non : pas de traduction dans la langue désirée
if ($lang=="fr_FR") {
	echo "
		<FRAMESET ROWS='0%,*' border=0 frameborder=0 framespacing=0>
			<FRAME>
		";
	//il n'y a qu'un répertoire pour la doc (uniquement francais)
	$doc_directory="documentation/fr_FR";
} else {
	//il n'y a qu'un répertoire pour la doc
	//$doc_directory="documentation/fr_FR";
	if ($lang=="en_UK") $lang="en_US";
	$doc_directory="documentation/".$lang;
	if (!is_dir($doc_directory)) $lang="fr_FR";
	echo "<HTML>
			<HEAD>
			<TITLE>Documentation PMB</TITLE>
			</HEAD>
			<FRAMESET ROWS='40,*'>
			<FRAME SRC='missing_trans.html'>";
	}
if(!is_dir($doc_directory)) {
	print "	<FRAME SRC='doc_install.html"; 
} else {
	//affichage de la page de doc correspondante
	print "	<FRAME SRC='".$doc_directory."/";
	$doc_correspondance="documentation/$lang/correspondance.php";
	include($doc_correspondance);			
}

//fin du frame affichant la doc correspondant aux infos postées
echo "' NAME='main'>";        
//fermeture du frameset
echo "
	</FRAMESET>
	</HTML>";
        
?>