<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: recouvr_liste.inc.php,v 1.4 2010-09-03 07:11:30 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//Gestion des recouvrements

require_once("$class_path/comptes.class.php");

//Recherche des emprunteurs en recouvrement
$requete="select empr_id,empr_cb, concat(empr_prenom,' ',empr_nom) as empr_name,empr_adr1,empr_adr2,empr_cp,empr_ville,empr_pays,empr_mail,empr_tel1,empr_tel2, sum(id_expl!=0) as nb_ouvrages, sum(montant) as somme from recouvrements, empr where id_empr=empr_id group by empr_id";
$resultat=mysql_query($requete);
print"
<script type='text/javascript' src='./javascript/sorttable.js'></script>
<table class='sortable'>
<tr><th>".$msg["relance_recouvrement_cb"]."</th><th>".$msg["relance_recouvrement_name"]."</th><th>".$msg["relance_recouvrement_nb_ouvrages"]."</th><th>".$msg["relance_recouvrement_somme_totale"]."</th></tr>\n";
$pair=false;
while ($r=mysql_fetch_object($resultat)) {
	if (!$pair) $class="odd"; else $class="even";
	$pair=!$pair;
	print "<tr class='$class' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$class'\" onmousedown=\"document.location='./circ.php?categ=relance&sub=recouvr&act=recouvr_reader&id_empr=".$r->empr_id."';\"  style='cursor: pointer'>";
	print pmb_bidi("<td>".$r->empr_cb."</td>");
	print pmb_bidi("<td>".$r->empr_name."</td>");
	print pmb_bidi("<td style='text-align:center'>".$r->nb_ouvrages."</td>");
	print pmb_bidi("<td style='text-align:right'>".comptes::format_simple($r->somme)."</td>");
	print "</tr>";
}
print "</table>";
?>