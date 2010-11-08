<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: recouvr_reader.inc.php,v 1.8 2010-09-21 14:14:09 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//Affichage des recouvrements pour un lecteur

require_once($class_path."/emprunteur.class.php");
require_once($class_path."/comptes.class.php");
require_once($class_path."/mono_display.class.php");
require_once($class_path."/serial_display.class.php");

$empr=new emprunteur($id_empr,'', FALSE, 0);
print "
<script src='./javascript/dynamic_element.js' type='text/javascript'></script>
<form class='form-circ' name='recouvr_reader_form' method='post' action='./circ.php?categ=relance&sub=recouvr&act=recouvr_reader&id_empr=$id_empr'>";
print pmb_bidi("<h3><a href='./circ.php?categ=pret&id_empr=$id_empr'>".$empr->prenom." ".$empr->nom."</a></h3>
<div class='form-contenu'>
	<div class='row'>
		<div class='colonne3'>
			<div class='row'>".$empr->adr1."</div>
			<div class='row'>".$empr->adr2."</div>
			<div class='row'>".$empr->cp." ".$empr->ville."</div>
			<div class='row'>".$empr->mail."</div>
		</div>
		<div class='colonne_suite'>
			<div class='row'>".$empr->tel1."</div>
			<div class='row'>".$empr->tel2."</div>
		</div>
	</div>
	<input type='hidden' name='act_line' value=''/>
	<input type='hidden' name='recouvr_id' value=''/>
	");
	
function show_lines_list() {
	global $id_empr,$msg,$charset;
	//Liste des recouvrements
	print "
	<div class='row'>	
	<script type='text/javascript' src='./javascript/sorttable.js'></script>
	<table class='sortable'>\n
		<tr>
			<th>".htmlentities($msg["relance_recouvrement_date"],ENT_QUOTES,$charset)."</th>
			<th>".htmlentities($msg["relance_recouvrement_type"],ENT_QUOTES,$charset)."</th>
			<th>".htmlentities($msg["relance_recouvrement_titre"],ENT_QUOTES,$charset)."</th>
			<th>".htmlentities($msg["relance_recouvrement_cb"],ENT_QUOTES,$charset)."</th>
			<th>".htmlentities($msg["relance_recouvrement_cote"],ENT_QUOTES,$charset)."</th>
			<th>".htmlentities($msg["relance_recouvrement_pret_date"],ENT_QUOTES,$charset)."</th>
			<th>".htmlentities($msg["relance_recouvrement_relance_date1"],ENT_QUOTES,$charset)."</th>
			<th>".htmlentities($msg["relance_recouvrement_relance_date2"],ENT_QUOTES,$charset)."</th>
			<th>".htmlentities($msg["relance_recouvrement_relance_date3"],ENT_QUOTES,$charset)."</th>
			<th>".htmlentities($msg["relance_recouvrement_prix_calcul"],ENT_QUOTES,$charset)."</th>
			<th>".htmlentities($msg["relance_recouvrement_montant"],ENT_QUOTES,$charset)."</th>
			<th></th>		
		</tr>";
			
	$requete="select recouvr_id,id_expl,date_rec,libelle,montant, expl_notice,expl_bulletin, recouvr_type, date_pret,date_relance1,date_relance2,date_relance3, expl_cote ,	expl_cb
	from recouvrements left join exemplaires on expl_id=id_expl where empr_id=$id_empr order by date_rec,recouvr_id";
	$resultat=mysql_query($requete);
	$pair=false;
	while ($r=mysql_fetch_object($resultat)) {
		if (!$pair) $class="odd"; else $class="even";
		$pair=!$pair;
		if ($r->id_expl) {
			if ($r->expl_notice) $notice=new mono_display($r->expl_notice);
			elseif ($r->expl_bulletin) {
				$req="select bulletin_notice from bulletins where bulletin_id=$r->expl_bulletin";
				$res=mysql_query($req);
				$id_bull_notice=mysql_result($res,0,0);
				$notice = new serial_display($id_bull_notice);
			}
			$libelle=strip_tags(html_entity_decode($notice->header,ENT_QUOTES,$charset));
		} else $libelle=$r->libelle;
		
		if(!$r->recouvr_type) {
			print pmb_bidi("<tr class='$class'>
				<td>".format_date($r->date_rec)."</td>
				<td>".htmlentities($msg["relance_recouvrement_amende"],ENT_QUOTES,$charset)."</td>
				<td>".htmlentities($libelle,ENT_QUOTES,$charset)."</td>
				<td><a href='./circ.php?categ=visu_ex&form_cb_expl=".$r->expl_cb."'>".$r->expl_cb."</a></td>
				<td>".htmlentities($r->expl_cote,ENT_QUOTES,$charset)."</td>
				<td>".format_date($r->date_pret)."</td>
				<td>".format_date($r->date_relance1)."</td>
				<td>".format_date($r->date_relance2)."</td>
				<td>".format_date($r->date_relance3)."</td>
				<td></td>
				<td style='text-align:right'><span dynamics='circ,recouvr_prix' dynamics_params='text' id='prix_".$r->recouvr_id."'>".comptes::format_simple($r->montant)."</span></td>
				<td style='text-align:center'><input type='checkbox' name='recouvr_ligne[]' value='".$r->recouvr_id."'></td>
				<td>");
				if (!$r->id_expl) print "<input type='button' value='...' class='bouton' onClick=\"this.form.act_line.value='update_line'; this.form.recouvr_id.value='".$r->recouvr_id."'; this.form.submit();\"/>"; else print "&nbsp;";
				print "</td>";
			print "</tr>";			
		}elseif ($r->id_expl) {
			$requete="select expl_prix, prix from exemplaires, notices where (notice_id=expl_notice or notice_id=expl_bulletin) and expl_id =".$r->id_expl;
			//http://localhost/~ngantier/pmb/circ.php?categ=visu_ex&form_cb_expl=p
			$res_prix=mysql_query($requete);
			$comment_prix='';
			if($r_prix=mysql_fetch_object($res_prix)) {				
				if(!$comment_prix=$r_prix->expl_prix)$comment_prix=$r_prix->prix;
			}
			
			print pmb_bidi("<tr class='$class'>
				<td>".format_date($r->date_rec)."</td>
				<td>".htmlentities($msg["relance_recouvrement_prix"],ENT_QUOTES,$charset)."</td>
				<td>".htmlentities($libelle,ENT_QUOTES,$charset)."</td>
				<td><a href='./circ.php?categ=visu_ex&form_cb_expl=".$r->expl_cb."'>".$r->expl_cb."</a></td>
				<td>".htmlentities($r->expl_cote,ENT_QUOTES,$charset)."</td>			
				<td>".format_date($r->date_pret)."</td>
				<td>".format_date($r->date_relance1)."</td>
				<td>".format_date($r->date_relance2)."</td>
				<td>".format_date($r->date_relance3)."</td>
				<td>".htmlentities($comment_prix,ENT_QUOTES,$charset)."</td>
				<td style='text-align:right'><span dynamics='circ,recouvr_prix' dynamics_params='text' id='prix_".$r->recouvr_id."'>".comptes::format_simple($r->montant)."</span></td>
				<td style='text-align:center'><input type='checkbox' name='recouvr_ligne[]' value='".$r->recouvr_id."'></td>
				<td>");
				print "</td>";
			print "</tr>";			
		}
		
	}
	print "</table></div>";
	print "
		<div class='row'></div>
	</div>
	<!--boutons -->
	<div class='row'>
		<input type='button' value='".$msg["relance_recouvrement_del_all_lines"]."' class='bouton' onClick=\"if (confirm('".$msg["relance_recouvrement_confirm_del"]."')) { this.form.act_line.value='del_line'; this.form.submit(); }\"/>
		<input type='button' value='".$msg["relance_recouvrement_add_line"]."' class='bouton' onClick=\"this.form.act_line.value='update_line'; this.form.recouvr_id.value=''; this.form.submit();\"/>
		<input type='button' value='".$msg["relance_recouvrement_export_tableur"]."' class='bouton' onClick=\"document.location='./circ/relance/recouvr_reader_excel.php?id_empr=$id_empr';\"/>
		<input type='button' value='".$msg["relance_recouvrement_solder"]."' class='bouton' onClick=\"if (confirm('".$msg["relance_recouvrement_confirm_solder"]."')) { this.form.act_line.value='solde'; this.form.submit(); }\"/>
		<input type='button' value='".$msg["76"]."' class='bouton' onClick=\"document.location='./circ.php?categ=relance&sub=recouvr&act=recouvr_liste'\"/>
	</div>";
}

function show_recouvr_form($recouvr_id) {
	global $msg,$charset;
	
	if ($recouvr_id) {
		$requete="select libelle,montant from recouvrements where recouvr_id=$recouvr_id";
		$resultat=mysql_query($requete);
		if (mysql_num_rows($resultat)) {
			$r=mysql_fetch_object($resultat);
			$libelle=$r->libelle;
			$montant=$r->montant;
		}
	}
	print "<div class='row'></div>
	<div class='row'>";
	print "<div class='row'>
		<label for='libelle'>".$msg["relance_recouvrement_libelle"]."</label>
	</div>
	<div class='row'>
		<textarea rows='5' cols='30' wrap='virtual' name='libelle' id='libelle'>".htmlentities($r->libelle,ENT_QUOTES,$charset)."</textarea>
	</div>";
	print "<div class='row'>
		<label for='montant'>".$msg["relance_recouvrement_montant"]."</label>
	</div>
	<div class='row'>
		<input name='montant' value='".$montant."' class='saisie-10em' id='montant'/>
	</div>	
		";
	print "</div>";
	print "
		<div class='row'></div>
	</div>
	<!--boutons -->
	<div class='row'>
		<input type='submit' value='".$msg["77"]."' class='bouton' onClick=\"this.form.act_line.value='rec_update_line'; this.form.recouvr_id.value='".$recouvr_id."'\"/>
		<input type='button' value='".$msg["76"]."' class='bouton' onClick=\"this.form.submit();\"/>
	</div>";
	
}

switch ($act_line) {
	case "update_line":
		show_recouvr_form($recouvr_id);
		break;
	case "rec_update_line":
		if ($recouvr_id) {
			$requete="update recouvrements set libelle='".$libelle."', montant='".$montant."' where recouvr_id=$recouvr_id";
			mysql_query($requete);
		} else {
			$requete="insert into recouvrements (empr_id, date_rec, libelle, montant) values($id_empr,now(),'".$libelle."','".$montant."')";
			mysql_query($requete);
		}
		show_lines_list();
		break;
	case "del_line":
		for ($i=0; $i<count($recouvr_ligne); $i++) {
			$requete="delete from recouvrements where recouvr_id=".$recouvr_ligne[$i];
			mysql_query($requete);
		}
		//Vérification qu'il reste des lignes
		$requete="select count(*) from recouvrements where empr_id='$id_empr'";
		$resultat=mysql_query($requete);
		if (mysql_result($resultat,0,0))
			show_lines_list();
		else
			print "<script>document.location='./circ.php?categ=relance&sub=recouvr&act=recouvr_liste';</script>";
		break;
	case "solde":
		$requete="select sum(montant) from recouvrements where empr_id='$id_empr'";
		$resultat=mysql_query($requete);
		$solde=@mysql_result($resultat,0,0);
		if ($solde) {
			//Crédit du compte lecteur
			$compte_id=comptes::get_compte_id_from_empr($id_empr,2);
			if ($compte_id) {
				$cpte=new comptes($compte_id);
				$id_transaction=$cpte->record_transaction("",$solde,1,$comment=$msg["relance_recouvrement_solde_recouvr"],$encaissement=0);
				if ($id_transaction) {
					$cpte->validate_transaction($id_transaction);
					
					//Débit du compte bibliothèque
					$requete="insert into transactions (compte_id,user_id,user_name,machine,date_enrgt,date_prevue,date_effective,montant,sens,realisee,commentaire,encaissement) 
					values(
						0,$PMBuserid,'".$PMBusername."','".$_SERVER["REMOTE_ADDR"]."',now(),now(),now(),
						$solde,-1,1,'".sprintf($msg["relance_recouvrement_solde_recouvr_bibli"],$id_empr)."',0)";
				}
			}
		}
		mysql_query("delete from recouvrements where empr_id='".$id_empr."'");
		print "<script>document.location='./circ.php?categ=relance&sub=recouvr&act=recouvr_liste';</script>";
		break;
	default:
		show_lines_list();
		break;
}
print "
</form>
<script type='text/javascript'>parse_dynamic_elts();</script>
";

?>