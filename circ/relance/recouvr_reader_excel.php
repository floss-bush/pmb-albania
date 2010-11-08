<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: recouvr_reader_excel.php,v 1.5 2010-09-03 07:11:30 ngantier Exp $

//Affichage des recouvrements pour un lecteur, format Excel HTML

// définition du minimum nécéssaire 
$base_path="../..";                            
$base_auth = "CIRCULATION_AUTH";  
$base_noheader=1;
$base_nosession=1;
//$base_nocheck = 1 ;
error_reporting (E_ERROR | E_PARSE | E_WARNING);
require_once ("$base_path/includes/init.inc.php");  
header("Content-Type: application/download\n");
header("Content-Disposition: atachement; filename=\"tableau.xls\"");

require_once($class_path."/emprunteur.class.php");
require_once($class_path."/comptes.class.php");
require_once($class_path."/mono_display.class.php");
require_once($class_path."/serial_display.class.php");

$empr=new emprunteur($id_empr,'', FALSE, 0);

print "<html><head>" .
'<meta http-equiv=Content-Type content="text/html; charset='.$charset.'" />'.
"</head><body>";
print "<table>";
print pmb_bidi("<tr><td>".$empr->prenom." ".$empr->nom."</td></tr>
<tr><td>".$empr->adr1."</td></tr>
<tr><td>".$empr->adr2."</td></tr>
<tr><td>".$empr->cp." ".$empr->ville."</td></tr>
<tr><td>".$empr->mail."</td></tr>
<tr><td>".$empr->tel1."</td></tr>
<tr><td>".$empr->tel2."</td></tr>
</table>");

$requete="select recouvr_id,id_expl,date_rec,libelle,montant, expl_notice,expl_bulletin, recouvr_type, date_pret,date_relance1,date_relance2,date_relance3 from recouvrements left join exemplaires on expl_id=id_expl where empr_id=$id_empr order by date_rec";
$resultat=mysql_query($requete);
print "<table>
<tr>
<th>".""."</th><th>".$msg["relance_recouvrement_type"]."</th><th>".$msg["relance_recouvrement_titre"]."</th><th>".$msg["relance_recouvrement_pret_date"]."</th><th>".$msg["relance_recouvrement_relance_date1"]."</th>
<th>".$msg["relance_recouvrement_relance_date2"]."</th><th>".$msg["relance_recouvrement_relance_date3"]."</th><th>".$msg["relance_recouvrement_montant"]."</th>
</tr>";
while ($r=mysql_fetch_object($resultat)) {
	if ($r->id_expl) {
		if ($r->expl_notice) {
			$notice=new mono_display($r->expl_notice);
			$libelle=$notice->header;
		} else if ($r->expl_bulletin) {
			$bulletin=new bulletinage_display($r->expl_bulletin);
			$libelle=$bulletin->display;
		}
	} else $libelle=$r->libelle;
	if($r->recouvr_type)$type=$msg["relance_recouvrement_prix"];
	else $type=$msg["relance_recouvrement_amende"];
	print pmb_bidi("<tr>
		<td>".format_date($r->date_rec)."</td>
		<td>".htmlentities($type,ENT_QUOTES,$charset)."</td>
		<td>".htmlentities($libelle,ENT_QUOTES,$charset)."</td>
		<td>".format_date($r->date_pret)."</td>
		<td>".format_date($r->date_relance1)."</td>
		<td>".format_date($r->date_relance2)."</td>
		<td>".format_date($r->date_relance3)."</td>
		<td style='text-align:right'>".$r->montant."</td>
	</tr>");
}
print "</table>";
?>