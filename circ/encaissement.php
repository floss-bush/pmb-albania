<?php
// +-------------------------------------------------+
// � 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: encaissement.php,v 1.10 2009-08-19 05:30:55 touraine37 Exp $

//Liste des trabsactions d'un compte
$base_path="..";

$current_alert="circ";

require_once("../includes/init.inc.php");
require_once("$base_path/classes/comptes.class.php");

$cpte=new comptes($id_compte);
if ($cpte->error) {
	print pmb_bidi($cpte->error_message);
	exit;
} 

function back_to_list() {
	global $show_transactions, $date_debut,$id_compte;
	
	print "<script>document.location=\"list_transactions.php?show_transactions=$show_transactions&date_debut=".rawurlencode(stripslashes($date_debut))."&id_compte=$id_compte\";</script>";
}

function back_to_main() {
		global $show_transactions, $date_debut,$id_compte,$cpte;
	
		print "<script>parent.document.location=\"../circ.php?categ=pret&sub=compte&id=".$cpte->get_empr_from_compte_id()."&typ_compte=".$cpte->typ_compte."&show_transactions=$show_transactions&date_debut=".rawurlencode(stripslashes($date_debut))."\";</script>";
}

function encaisse_form($with_validated=false) {
	global $id_compte,$solde,$date_debut,$val_transactions,$somme,$cpte,$charset;
	global $pmb_gestion_devise,$msg,$charset;
	
	$solde=$cpte->get_solde();
	if ($solde<0) {
		print "<table>";
		print "<tr><td style='text-align:right'>".$msg["finance_enc_montant_valide"]." : </td><td style='text-align:right'>".comptes::format($somme*(-1))."</td></tr>";
		if ($solde<=0) print "<tr class='erreur'><td style='text-align:right'>".$msg["finance_enc_montant_a_enc"]." : </td><td style='text-align:right'>"; else if ($solde>0) print "<td>".$msg["finance_enc_compte_cred"]." : </td><td style='text-align:right'>";
		print comptes::format($solde*(-1));		
		print "</td></tr></table>";
		print "<script>function check_somme(f) {
			message='';
			if (isNaN(f.somme.value)) {
				message='".addslashes($msg["finance_enc_nan"])."';
			} else {
				if (f.somme.value<=0)
					message='".addslashes($msg["finance_enc_mnt_neg"])."';
			}
			if (message) {
				alert(message);
				return false;
			} else return true;
		}
		</script>";
		print "<form name='form_encaissement' action='encaissement.php?id_compte=$id_compte&show_transactions=$show_transactions&date_debut=".rawurlencode(stripslashes($date_debut))."' method='post'>
		<input type='hidden' name='act' value='enc'/>
		<input type='hidden' name='val_transactions' value=\"".htmlentities($val_transactions,ENT_QUOTES,$charset)."\"/>".
		htmlentities($msg['finance_mnt_percu'], ENT_QUOTES, $charset)."&nbsp;<input type='text' value='".$solde*(-1)."' name='somme' class='saisie-5em' style='text-align:right'>&nbsp;".$pmb_gestion_devise."
		<input type='submit' value='".$msg["finance_but_enc"]."' class='bouton' onClick=\"return check_somme(this.form)\"/>&nbsp;<input type='button' value='".$msg["76"]."' class='bouton' onClick=\"document.form_encaissement.act.value=''; document.form_encaissement.submit();\"/>
		</form>
		";
	} else {
		back_to_main();
	}
}

function special_form() {
	global $id_compte,$solde,$date_debut,$val_transactions,$somme,$cpte,$charset,$msg, $pmb_gestion_devise;
	print "<h3>".$msg["finance_but_cred"]."</h3>";
	print "<script>function check_somme(f) {
		message='';
		if (isNaN(f.somme.value)) {
			message='".addslashes($msg["finance_enc_nan"])."';
		} else {
			if (f.somme.value<=0)
				message='".addslashes($msg["finance_enc_mnt_neg"])."';
		}
		if (message) {
			alert(message);
			return false;
		} else return true;
	}
	</script>";
	print "<form name='form_special' action='encaissement.php?id_compte=$id_compte&show_transactions=$show_transactions&date_debut=".rawurlencode(stripslashes($date_debut))."' method='post'>
		<input type='hidden' name='act' value='enc_special'/>
		".$msg["finance_montant"]." <input type='text' value='' name='somme' class='saisie-5em' style='text-align:right'>&nbsp;".$pmb_gestion_devise."<br />
		<input type='radio' value='1' name='typ_special' id='typ_special_1' checked>&nbsp;<label for='typ_special_1'>".$msg["finance_enc_spe_crediter"]."&nbsp;<input type='checkbox' name='credit_perte' value='1'>&nbsp;".$msg["finance_enc_spe_perte"]."</label><br /><input type='radio' value='2' name='typ_special' id='typ_special_2'>&nbsp;<label for='typ_special_2'>".$msg["finance_enc_debiter"]."</label><br />
		<input type='radio' value='3' name='typ_special' id='typ_special_3'>&nbsp;<label for='typ_special_3'>".$msg["finance_enc_crediter_enc"]."</label><br /><input type='radio' value='4' name='typ_special' id='typ_special_4'>&nbsp;<label for='typ_special_4'>".$msg["finance_enc_debiter_enc"]." <input type='checkbox' name='dec_perte' value='1'>&nbsp;".$msg["finance_enc_spe_perte"]."</label><br />
		".$msg["finance_enc_raison"]."<br />
		<textarea cols='80' rows='2' wrap='virtual' name='commentaire'></textarea><br />
		<input type='submit' value='".$msg["finance_enc_valider"]."' class='bouton' onClick=\"return check_somme(this.form)\"/>&nbsp;<input type='button' value='".$msg["76"]."' class='bouton' onClick=\"document.form_special.act.value=''; document.form_special.submit();\"/>
		</form>
		";
}

switch ($act) {
	case "valenc":
		//Validation de ce qui n'est pas valide
		$t=$cpte->get_transactions("","",0,0);
		$somme=0;
		$val_transactions="";
		for ($i=0; $i<count($t); $i++) {
			if ($cpte->validate_transaction($t[$i]->id_transaction)) {
				$somme+=$t[$i]->montant*$t[$i]->sens;
				$val_transactions.=" #".$t[$i]->id_transaction."#";
			}
		}
		if ($val_transactions!="") $val_transactions=$msg["finance_enc_tr_lib_valider"]." : ".$val_transactions."\n";
		$solde_avant=$cpte->get_solde();
		if ($solde_avant!=0) $val_transactions.=$msg["finance_enc_tr_lib_etat_compte"]." : ".$solde_avant;
		$cpte->update_solde();
		encaisse_form(true);
		break;
	case "enc":
		if ($somme*1>0) {
			//Generation de la transaction
			if ($id_transaction=$cpte->record_transaction("",$somme,1,$val_transactions,1)) {
				$cpte->validate_transaction($id_transaction);
				$cpte->update_solde();
			}
			back_to_main();
		} else {
			back_to_list();
		}
		break;
	case "val":
		if (!isset($trans)) $trans=array();
		foreach ($trans as $key=>$value){
			$cpte->validate_transaction($key);
		}
		$cpte->update_solde();
		back_to_main();
		break;
	case "supr":
		if (!isset($trans)) $trans=array();
		foreach ($trans as $key=>$value){
			$cpte->delete_transaction($key);
		}
		$cpte->update_solde();
		back_to_main();
		break;
	case "encnoval":
		$solde=$cpte->get_solde();
		$val_transactions.=$msg["finance_enc_tr_lib_etat_compte"]." : ".$solde;
		encaisse_form();
		break;
	case "special":
		special_form();
		break;
	case "enc_special":
		if ($somme*1>0) {
			switch ($typ_special) {
				case "1":
					//Crediter
					$signe=1;
					$caisse=0;
					break;
				case "2":
					//Debiter
					$signe=-1;
					$caisse=0;
					break;
				case "3":
					//Crediter et encaisser
					$signe=1;
					$caisse=1;
					break;
				case "4":
					//Debiter et decaisser
					$signe=-1;
					$caisse=1;
					break;
			}
			if ($id_transaction=$cpte->record_transaction("",$somme,$signe,stripslashes($commentaire),$caisse)) {
				$cpte->validate_transaction($id_transaction);
				//Traitement du passage en perte
				//Credit
				if (($typ_special==1)&&($credit_perte)) {
					//Ajout d'un transaction debit pour le compte 0
					$requete="insert into transactions (compte_id,user_id,user_name,machine,date_enrgt,date_prevue,date_effective,montant,sens,realisee,commentaire,encaissement) values(0,$PMBuserid,'".$PMBusername."','".$_SERVER["REMOTE_ADDR"]."', now(), now(), now(), ".($somme*1).", -1, 1,'#".$id_transaction."# : ".$commentaire."',0)";
					mysql_query($requete);
				}
				//Decaissement
				if (($typ_special==4)&&($dec_perte)) {
					//Credit sur le compte du lecteur
					if ($id_transaction_1=$cpte->record_transaction("",$somme,1,sprintf($msg["finance_enc_tr_lib_lost"]," #".$id_transaction."#")." : ".stripslashes($commentaire),0)) {
						$cpte->validate_transaction($id_transaction_1);
						//Debit sur le compte 0
						$requete="insert into transactions (compte_id,user_id,user_name,machine,date_enrgt,date_prevue,date_effective,montant,sens,realisee,commentaire,encaissement) values(0,$PMBuserid,'".$PMBusername."','".$_SERVER["REMOTE_ADDR"]."', now(), now(), now(), ".($somme*1).", -1, 1,'#".$id_transaction_1."# : ".sprintf($msg["finance_enc_tr_lib_lost"],"#".$id_transaction."#")." : ".stripslashes($commentaire)."',0)";
						mysql_query($requete);
					}
				}
				$cpte->update_solde();
			}
			back_to_main();
		} else {
			back_to_list();
		}
	default:
		back_to_list();
		break;
}

print "<script>parent.document.getElementById('selector_transaction_list').style.visibility='hidden';
parent.document.getElementById('buttons_transaction_list').style.visibility='hidden';
</script>";
?>