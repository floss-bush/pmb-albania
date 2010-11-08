<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: load_notice_pos.php,v 1.16 2009-10-06 21:17:07 touraine37 Exp $

$base_path=".";
$base_noheader=1;
$base_nobody=1;
$base_auth = "CATALOGAGE_AUTH";
require_once("includes/init.inc.php");

header("Content-Type: text/xml");
print "<?xml version='1.0' encoding='iso-8859-1'?>\n";

if (!$grille_typdoc) $grille_typdoc='a' ;
if (!$grille_niveau_biblio) $grille_niveau_biblio='m' ;

$requete = "select name from notices_custom order by ordre ";
$res = mysql_query($requete, $dbh) or die(mysql_error()."<br />$requete");

if ($grille_niveau_biblio=='m') {
		$grille_standard="<formpage relative='yes'>
		  <etirable id='el0Child' visible='yes' order='1' />
		  <etirable id='el1Child' visible='yes' order='2' />
		  <etirable id='el2Child' visible='yes' order='3' />
		  <etirable id='el3Child' visible='yes' order='4' />
		  <etirable id='el4Child' visible='yes' order='5' />
		  <etirable id='el5Child' visible='yes' order='6' />
		  <etirable id='el6Child' visible='yes' order='7' />
		  <etirable id='el230Child' visible='yes'  order='8' />
		  <etirable id='el7Child' visible='yes' order='9' />
		  <etirable id='el8Child' visible='yes' order='10' />".
		  (mysql_num_rows($res)?"<etirable id='el9Child' visible='yes' order='11' />":"")."
		  <etirable id='el11Child' visible='yes' order='12' />
		  <etirable id='el10Child' visible='yes' order='13' />
		  <movable id='el0Child_0' visible='yes' parent='el0Child'/>
		  <movable id='el0Child_1' visible='yes' parent='el0Child'/>
		  <movable id='el0Child_2' visible='yes' parent='el0Child'/>
		  <movable id='el0Child_3' visible='yes' parent='el0Child'/>
		  <movable id='el0Child_4' visible='yes' parent='el0Child'/>
		  <movable id='el1Child_0' visible='yes' parent='el1Child'/>
		  <movable id='el1Child_2' visible='yes' parent='el1Child'/>
		  <movable id='el1Child_3' visible='yes' parent='el1Child'/>
		  <movable id='el2Child_0' visible='yes' parent='el2Child'/>
		  <movable id='el2Child_1' visible='yes' parent='el2Child'/>
		  <movable id='el2Child_3' visible='yes' parent='el2Child'/>
		  <movable id='el2Child_4' visible='yes' parent='el2Child'/>
		  <movable id='el2Child_7' visible='yes' parent='el2Child'/>
		  <movable id='el3Child_0' visible='yes' parent='el3Child'/>
		  <movable id='el4Child_0' visible='yes' parent='el4Child'/>
		  <movable id='el4Child_1' visible='yes' parent='el4Child'/>
		  <movable id='el4Child_2' visible='yes' parent='el4Child'/>
		  <movable id='el4Child_3' visible='yes' parent='el4Child'/>
		  <movable id='el4Child_4' visible='yes' parent='el4Child'/>
		  <movable id='el5Child_0' visible='yes' parent='el5Child'/>
		  <movable id='el5Child_1' visible='yes' parent='el5Child'/>
		  <movable id='el5Child_2' visible='yes' parent='el5Child'/>
		  <movable id='el6Child_0' visible='yes' parent='el6Child'/>
		  <movable id='el6Child_1' visible='yes' parent='el6Child'/>
		  <movable id='el6Child_2' visible='yes' parent='el6Child'/>";
	if ($pmb_use_uniform_title)	$grille_standard.="<movable id='el230Child_0' visible='yes' parent='el230Child'/>";
		$grille_standard.="<movable id='el7Child_0' visible='yes' parent='el7Child'/>
		  <movable id='el7Child_1' visible='yes' parent='el7Child'/>
		  <movable id='el8Child_0' visible='yes' parent='el8Child'/>
		  <movable id='el8Child_1' visible='yes' parent='el8Child'/>";
		while ($champ=mysql_fetch_object($res)) 
			$grille_standard.="  <movable id='move_".$champ->name."' visible='yes' parent='el9Child'/>\n";
		$grille_standard.="
		  <movable id='el11Child_0' visible='yes' parent='el11Child'/>
		  <movable id='el10Child_0' visible='yes' parent='el10Child'/>
		  <movable id='el10Child_1' visible='yes' parent='el10Child'/>
		  <movable id='el10Child_2' visible='yes' parent='el10Child'/>
		  <movable id='el10Child_3' visible='yes' parent='el10Child'/>
		</formpage>";
	}

if ($grille_niveau_biblio=='s') {
		$grille_standard="		<formpage relative='yes'>
		  <etirable id='el0Child' visible='yes' order='1' />
		  <etirable id='el1Child' visible='yes' order='2' />
		  <etirable id='el2Child' visible='yes' order='3' />
		  <etirable id='el30Child' visible='yes' order='4' />
		  <etirable id='el5Child' visible='yes' order='5' />
		  <etirable id='el6Child' visible='yes' order='6' />
		  <etirable id='el7Child' visible='yes' order='7' />
		  <etirable id='el8Child' visible='yes' order='8' />".
		  (mysql_num_rows($res)?"<etirable id='el9Child' visible='yes' order='10' />":"")."
		  <etirable id='el11Child' visible='yes' order='9' />
		  <etirable id='el10Child' visible='yes' order='10' />
		  <movable id='el0Child_0' visible='yes' parent='el0Child'/>
		  <movable id='el0Child_1' visible='yes' parent='el0Child'/>
		  <movable id='el0Child_2' visible='yes' parent='el0Child'/>
		  <movable id='el1Child_0' visible='yes' parent='el1Child'/>
		  <movable id='el1Child_2' visible='yes' parent='el1Child'/>
		  <movable id='el1Child_3' visible='yes' parent='el1Child'/>
		  <movable id='el2Child_0' visible='yes' parent='el2Child'/>
		  <movable id='el2Child_4' visible='yes' parent='el2Child'/>
		  <movable id='el2Child_7' visible='yes' parent='el2Child'/>
		  <movable id='el30Child_0' visible='yes' parent='el30Child'/>
		  <movable id='el5Child_0' visible='yes' parent='el5Child'/>
		  <movable id='el5Child_2' visible='yes' parent='el5Child'/>
		  <movable id='el6Child_0' visible='yes' parent='el6Child'/>
		  <movable id='el6Child_1' visible='yes' parent='el6Child'/>
		  <movable id='el6Child_2' visible='yes' parent='el6Child'/>
		  <movable id='el7Child_0' visible='yes' parent='el7Child'/>
		  <movable id='el7Child_1' visible='yes' parent='el7Child'/>
		  <movable id='el11Child_0' visible='yes' parent='el11Child'/>
		  <movable id='el8Child_0' visible='yes' parent='el8Child'/>
		  <movable id='el8Child_1' visible='yes' parent='el8Child'/>";
		while ($champ=mysql_fetch_object($res)) 
			$grille_standard.="  <movable id='move_".$champ->name."' visible='yes' parent='el9Child'/>\n";
		$grille_standard.="  <movable id='el10Child_0' visible='yes' parent='el10Child'/>
		  <movable id='el10Child_1' visible='yes' parent='el10Child'/>
		  <movable id='el10Child_2' visible='yes' parent='el10Child'/>
		  <movable id='el10Child_3' visible='yes' parent='el10Child'/>
		  <movable id='el10Child_4' visible='yes' parent='el10Child'/>
		</formpage>";
	}

if ($grille_niveau_biblio=='a') {
		$grille_standard="		<formpage relative='yes'>
		  <etirable id='el0Child' visible='yes' order='1' />
		  <etirable id='el1Child' visible='yes' order='2' />
		  <etirable id='el2Child' visible='yes' order='3' />
		  <etirable id='el5Child' visible='yes' order='4' />
		  <etirable id='el6Child' visible='yes' order='5' />
		  <etirable id='el7Child' visible='yes' order='6' />
		  <etirable id='el8Child' visible='yes' order='7' />".
		  (mysql_num_rows($res)?"<etirable id='el9Child' visible='yes' order='8' />":"")."
		  <etirable id='el10Child' visible='yes' order='9' />
		<movable id='el0Child_0' visible='yes' parent='el0Child'/>
		<movable id='el0Child_1' visible='yes' parent='el0Child'/>
		<movable id='el0Child_2' visible='yes' parent='el0Child'/>
		<movable id='el1Child_0' visible='yes' parent='el1Child'/>
		<movable id='el1Child_2' visible='yes' parent='el1Child'/>
		<movable id='el1Child_3' visible='yes' parent='el1Child'/>
		<movable id='el2Child_0' visible='yes' parent='el2Child'/>
		<movable id='el5Child_0' visible='yes' parent='el5Child'/>
		<movable id='el5Child_2' visible='yes' parent='el5Child'/>
		<movable id='el6Child_0' visible='yes' parent='el6Child'/>
		<movable id='el6Child_1' visible='yes' parent='el6Child'/>
		<movable id='el6Child_2' visible='yes' parent='el6Child'/>
		<movable id='el7Child_0' visible='yes' parent='el7Child'/>
		<movable id='el7Child_1' visible='yes' parent='el7Child'/>
		<movable id='el8Child_0' visible='yes' parent='el8Child'/>
		<movable id='el8Child_1' visible='yes' parent='el8Child'/>";
		while ($champ=mysql_fetch_object($res)) 
			$grille_standard.="  <movable id='move_".$champ->name."' visible='yes' parent='el9Child'/>\n";
		$grille_standard.="  <movable id='el10Child_0' visible='yes' parent='el10Child'/>
		  <movable id='el10Child_1' visible='yes' parent='el10Child'/>
		  <movable id='el10Child_2' visible='yes' parent='el10Child'/>
		  <movable id='el10Child_4' visible='yes' parent='el10Child'/>
		</formpage>";
	}

if ($grille_niveau_biblio=='b') {
		$grille_standard="		<formpage relative='yes'>
		  <etirable id='el5Child' visible='yes' order='4' />
		  <etirable id='el6Child' visible='yes' order='5' />
		  <etirable id='el41Child' visible='yes' order='5' />
		  <etirable id='el7Child' visible='yes' order='6' />
		  <etirable id='el8Child' visible='yes' order='7' />".
		  (mysql_num_rows($res)?"<etirable id='el9Child' visible='yes' order='8' />":"")."
		  <etirable id='el10Child' visible='yes' order='9' />
		<movable id='el5Child_0' visible='yes' parent='el5Child'/>
		<movable id='el5Child_1' visible='yes' parent='el5Child'/>
		<movable id='el5Child_2' visible='yes' parent='el5Child'/>
		<movable id='el6Child_0' visible='yes' parent='el6Child'/>
		<movable id='el6Child_1' visible='yes' parent='el6Child'/>
		<movable id='el6Child_2' visible='yes' parent='el6Child'/>
		<movable id='el41Child_0' visible='yes' parent='el41Child'/>
		<movable id='el41Child_1' visible='yes' parent='el41Child'/>
		<movable id='el41Child_2' visible='yes' parent='el41Child'/>
		<movable id='el41Child_3' visible='yes' parent='el41Child'/>
		<movable id='el41Child_4' visible='yes' parent='el41Child'/>
		<movable id='el7Child_0' visible='yes' parent='el7Child'/>
		<movable id='el7Child_1' visible='yes' parent='el7Child'/>
		<movable id='el8Child_0' visible='yes' parent='el8Child'/>
		<movable id='el8Child_1' visible='yes' parent='el8Child'/>";
		while ($champ=mysql_fetch_object($res)) 
			$grille_standard.="  <movable id='move_".$champ->name."' visible='yes' parent='el9Child'/>\n";
		$grille_standard.="  <movable id='el10Child_0' visible='yes' parent='el10Child'/>
		  <movable id='el10Child_1' visible='yes' parent='el10Child'/>
		  <movable id='el10Child_2' visible='yes' parent='el10Child'/>
		  <movable id='el10Child_4' visible='yes' parent='el10Child'/>
		</formpage>";
	}

$requete = "select grille_typdoc, grille_niveau_biblio, grille_localisation, descr_format from grilles where grille_niveau_biblio='$grille_niveau_biblio' and grille_typdoc='$grille_typdoc' ";
$res = mysql_query($requete, $dbh) or die(mysql_error()."<br />$requete");
if (!$grille_location) $grille_location=$deflt_docs_location;
while ($grille=mysql_fetch_object($res)) {
	if (($grille->grille_localisation==$grille_location)&&($grille->descr_format)) {
		$descr_format=$grille->descr_format;
		break;
	} else if (($grille->grille_localisation==0)&&($grille->descr_format)) {
		$descr_format=$grille->descr_format;
	}
}
if ($descr_format) {
	print $descr_format;
} else print $grille_standard;

		
