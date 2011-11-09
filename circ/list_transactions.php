<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: list_transactions.php,v 1.4.12.1 2011-09-06 09:11:23 jpermanne Exp $

//Liste des trabsactions d'un compte
$base_path="..";
//$base_noheader=1;

$current_alert="circ";

require_once("../includes/init.inc.php");
require_once("$base_path/classes/comptes.class.php");

$cpte=new comptes($id_compte);
if ($cpte->error) {
	print $cpte->error_message;
	exit;
} 
switch ($show_transactions) {
	case "2":
		$t=$cpte->get_transactions("","",0,0);
		break;
	case "3":
		$date_debut_=extraitdate($date_debut);
		$t=$cpte->get_transactions($date_debut_,"",0,-1,0,"asc");
		break;
	case "1":
	default:
		$t=$cpte->get_transactions("","",0,-1, 10);
		break;
}
print "<form name='form_transactions' action='encaissement.php' method='post' onSubmit='return false'>
<input type='hidden' name='act' value=''/>
<input type='hidden' name='id_compte' value='".$id_compte."'/>
<input type='hidden' name='show_transactions' value='".$show_transactions."'/>
<input type='hidden' name='date_debut' value='".htmlentities($date_debut,ENT_QUOTES,$charset)."'/>
";
if (!count($t)) print $msg["finance_list_tr_no_tr"]; else {
	print "<table width=100%>";
	print "<tr>";
	print "<th>".$msg["finance_list_tr_date_enrgt"]."</th>";
	print "<th>&nbsp;</th>";
	print "<th>".$msg["finance_list_tr_comment"]."</th>";
	print "<th style='text-align:right'>".$msg["finance_montant"]."</th>";
	print "<th style='text-align:right'>".$msg["finance_list_tr_deb_cred"]."</th>";
	print "<th style='text-align:center'>".$msg["finance_list_tr_validee"]."</th>";
	print "<th>".$msg["finance_date_valid"]."</th>";
	print "<th>&nbsp;</th>";
	print "</tr>\n";
	for ($i=0; $i<count($t); $i++) {
		print "<tr>";
		print pmb_bidi("<td>".formatdate($t[$i]->date_enrgt)."</td>");
		print pmb_bidi("<td>".($t[$i]->encaissement?"*":"")."</td>");
		print pmb_bidi("<td>".$t[$i]->commentaire."</td>");
		print pmb_bidi("<td  style='text-align:right'>".($t[$i]->sens==-1? "<span class='erreur'>":"").comptes::format($t[$i]->montant).($t[$i]->sens==-1? "</span>":"")."</td>");
		print pmb_bidi("<td style='text-align:right'>".($t[$i]->sens==1 ? $msg["finance_form_empr_libelle_credit"]:$msg["finance_form_empr_libelle_debit"])."</td>");
		print pmb_bidi("<td style='text-align:center'>".($t[$i]->realisee ? "X":"")."</td>");
		print pmb_bidi("<td>".formatdate($t[$i]->date_effective)."</td>");
		print "<td>";
		if (!$t[$i]->realisee) {
			print "<input type='checkbox' value='1' name='trans[".$t[$i]->id_transaction."]' ";
			//$tans="trans_".$t[$i]->id_transaction;
			//if ($$trans) print "checked";
			print ">";
		}
		print "</td>";
		print "</tr>\n";
	}
	print "</table>\n";
}
print "</form>";
print "<script>parent.document.getElementById('selector_transaction_list').style.visibility='visible';
parent.document.getElementById('buttons_transaction_list').style.visibility='visible';
</script>";
?>