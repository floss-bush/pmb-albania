<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: show_group.inc.php,v 1.13 2009-05-16 11:12:03 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// affichage de la liste des membres d'un groupe
// récupération des infos du groupe

$myGroup = new group($groupID);

print pmb_bidi("
<div class='row'>
	<a href=\"./circ.php?categ=groups\">${msg[929]}</a>&nbsp;
	</div>
<div class='row'>
	<h3>$msg[919]&nbsp;$groupID&nbsp;: ".$myGroup->libelle."&nbsp;
		<input type='submit' class='bouton' value='$msg[62]' onClick=\"document.location='./circ.php?categ=groups&action=modify&groupID=$groupID'\" />
		&nbsp;<input type='button' name='imprimerlistedocs' class='bouton' value='$msg[imprimer_liste_pret]' onClick=\"openPopUp('./pdf.php?pdfdoc=liste_pret_groupe&id_groupe=$groupID', 'print_PDF', 600, 500, -2, -2, '$PDF_win_prop');\" />	
	</h3>
");

if($myGroup->libelle_resp && $myGroup->id_resp)
	print pmb_bidi("
		<br />$msg[913]&nbsp;: 
			<a href='./circ.php?categ=pret&form_cb=".rawurlencode($myGroup->cb_resp)."&groupID=$groupID'>".$myGroup->libelle_resp."</a>
			");
	

print "
	</div>
<div class='row'>";

if($myGroup->nb_members) {
	print "<table >";
	$parity=1;
	while(list($cle, $membre) = each($myGroup->members)) {
		if ($parity % 2) {
			$pair_impair = "even";
			} else {
				$pair_impair = "odd";
				}
		$parity += 1;
	        $tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./circ.php?categ=pret&form_cb=".rawurlencode($membre['cb'])."&groupID=$groupID';\" ";
		print pmb_bidi("<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>
			<td><a href=\"./circ.php?categ=pret&form_cb=".rawurlencode($membre['cb'])."&groupID=$groupID\">".$membre['nom']);
		if($membre['prenom'])print pmb_bidi(", ${membre['prenom']}");
		if(($nb_pret=get_nombre_pret($membre['id']))) print pmb_bidi(" ($nb_pret)");
		print pmb_bidi("
			</a></td>
			<td>${membre['cb']}</td>
			<td><a href=\"./circ.php?categ=groups&action=delmember&groupID=$groupID&memberID=${membre['id']}\">
				<img src=\"./images/trash.gif\" title=\"${msg[928]}\" border=\"0\" /></a>
				</td>
			</tr>");
		}
	print '</table><br />';
	} else {
		print "<p>$msg[922]</p>";
		}
// pour que le formulaire soit OK juste après la création du groupe 
$group_form_add_membre = str_replace("!!groupID!!", $groupID, $group_form_add_membre);
print $group_form_add_membre ;

function get_nombre_pret($id_empr) {
	$requete = "SELECT count( pret_idempr ) as nb_pret FROM pret where pret_idempr = $id_empr";
	$res_pret = mysql_query($requete);
	if (mysql_num_rows($res_pret)) {
		$rpret=mysql_fetch_object($res_pret);
		$nb_pret=$rpret->nb_pret;	
	}	
	return $nb_pret;
}	