<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: modes_paiements.inc.php,v 1.11 2009-05-16 11:11:54 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion des modes de paiement
require_once("$class_path/paiements.class.php");

function show_list_mode() {
	
	global $dbh;
	global $msg;
	global $charset;

	print "<table>
	<tr>
		<th>".htmlentities($msg[103], ENT_QUOTES, $charset)."</th>
	</tr>";

	$res = paiements::listPaiements();
	$nbr = mysql_num_rows($res);

	$parity=1;
	for($i=0;$i<$nbr;$i++) {
		$row=mysql_fetch_object($res);
			if ($parity % 2) {
				$pair_impair = "even";
			} else {
				$pair_impair = "odd";
			}
			$parity += 1;
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=acquisition&sub=mode&action=modif&id=$row->id_paiement';\" ";
	        print "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td><i>".htmlentities($row->libelle, ENT_QUOTES, $charset)."</i></td>";
			print "</tr>";
	}
	print "</table>
		<input class='bouton' type='button' value=' ".$msg[acquisition_ajout_mode]." ' onClick=\"document.location='./admin.php?categ=acquisition&sub=mode&action=add'\" />";

}

function show_mode_form($id=0) {
		
	global $msg;
	global $charset;
	global $mode_form;
	global $ptab;
	global $acquisition_gestion_tva;
	
	$mode_form = str_replace('!!id!!', $id, $mode_form);
	
	if(!$id) {
		
		$mode_form = str_replace('!!form_title!!', htmlentities($msg[acquisition_ajout_mode],ENT_QUOTES,$charset), $mode_form);
		$mode_form = str_replace('!!libelle!!', '', $mode_form);
		$mode_form = str_replace('!!commentaire!!', '', $mode_form);

	} else {
		
		$mode = new paiements($id);
		$mode_form = str_replace('!!form_title!!', htmlentities($msg[acquisition_modif_mode],ENT_QUOTES,$charset), $mode_form);
		$mode_form = str_replace('!!libelle!!', htmlentities($mode->libelle,ENT_QUOTES,$charset), $mode_form);
		$mode_form = str_replace('!!commentaire!!', $mode->commentaire, $mode_form);
		
		$ptab = str_replace('!!id!!', $id, $ptab);
		$ptab = str_replace('!!libelle_suppr!!', addslashes($mode->libelle), $ptab);
		$mode_form = str_replace('<!-- bouton_sup -->', $ptab, $mode_form);

	}
	
	print confirmation_delete("./admin.php?categ=acquisition&sub=mode&action=del&id=");
	print $mode_form;
	
}


$mode_form = "
<form class='form-".$current_module."' id='modeform' name='modeform' method='post' action=\"./admin.php?categ=acquisition&sub=mode&action=update&id=!!id!!\">
<h3>!!form_title!!</h3>
<!--    Contenu du form    -->
<div class='form-contenu'>

	<div class='row'>
		<label class='etiquette' for='libelle'>".htmlentities($msg[103],ENT_QUOTES,$charset)."</label>
	</div>
	<div class='row'>
		<input type=text id='libelle' name='libelle' value=\"!!libelle!!\" class='saisie-60em' />
	</div>

	<div class='row'>
		<label class='etiquette' for='comment'>".htmlentities($msg[acquisition_mode_comment],ENT_QUOTES,$charset)."</label>
	</div>
	<div class='row'>
		<textarea id='comment' name='comment' class='saisie-80em' cols='62' rows='6' wrap='virtual'>!!commentaire!!</textarea>
	</div>
	<div class='row'></div>
</div>

<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=acquisition&sub=mode' \" />&nbsp;
		<input class='bouton' type='submit' value=' $msg[77] ' onClick=\"return test_form(this.form)\" />
	</div>
	<div class='right'>
		<!-- bouton_sup -->
	</div>
</div>
<div class='row'>
</div>
</form>
<script type='text/javascript'>
	document.forms['modeform'].elements['libelle'].focus();
</script>

";

$ptab = "<input class='bouton' type='button' value=' ".$msg[supprimer]." ' onClick=\"javascript:confirmation_delete('!!id!!', '!!libelle_suppr!!')\" />";

?>

<script type='text/javascript'>
function test_form(form)
{
	if(form.libelle.value.length == 0)
	{
		alert("<?php echo $msg[98]; ?>");
		document.forms['modeform'].elements['libelle'].focus();
		return false;	
	}
	return true;
}
</script>

<?php

//Traitement des actions
switch($action) {
	case 'add':
		show_mode_form();
		break;

		
	case 'modif':
		if (paiements::exists($id)) {
			show_mode_form($id);
		} else {
			show_list_mode();
		}
		break;

		
	case 'update':
		// vérification validité des données fournies.
		//Pas deux libelles de modes de paiement
		$nbr = paiements::existsLibelle($libelle, $id);
		if ( $nbr > 0 ) {
			error_form_message($libelle.$msg["acquisition_mode_already_used"]);
			break;
		}
		$mode = new paiements($id);
		$mode->libelle = $libelle;
		$mode->commentaire = $comment;
		$mode->save();
		show_list_mode();

		break;

		
	case 'del':
		if($id) {
			$total1 = paiements::hasFournisseurs($id);
			if ($total1==0) {
				paiements::delete($id);
			} else {
				$msg_suppr_err = $msg[acquisition_mode_used] ;
				if ($total1) $msg_suppr_err .= "<br />- ".$msg[acquisition_mode_used_fou] ;
				error_message($msg[321], $msg_suppr_err, 1, 'admin.php?categ=acquisition&sub=mode');
			}
		} else {
			show_list_mode();
		}
		break;


	default:
		show_list_mode();
		break;
}

?>
