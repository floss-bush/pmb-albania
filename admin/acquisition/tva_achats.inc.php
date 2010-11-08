<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: tva_achats.inc.php,v 1.12 2009-05-16 11:11:54 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion des comptes de tva achats
require_once("$class_path/tva_achats.class.php");


function show_list_tva() {
	
	global $dbh, $msg, $charset;

	print "<table>
	<tr>
		<th>".htmlentities($msg[103], ENT_QUOTES, $charset)."</th>
		<th>".htmlentities($msg[acquisition_tva_taux], ENT_QUOTES, $charset)."</th>
		<th>".htmlentities($msg[acquisition_num_cp_compta], ENT_QUOTES, $charset)."</th>
	</tr>";

	$q = tva_achats::listTva();
	$res = mysql_query($q, $dbh);
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
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=acquisition&sub=tva&action=modif&id=$row->id_tva';\" ";
	        print "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td><i>".htmlentities($row->libelle, ENT_QUOTES, $charset)."</i></td><td><i>".$row->taux_tva."%</i></td><td><i>".$row->num_cp_compta."</i></td>";
			print "</tr>";
	}
	print "</table>
		<input class='bouton' type='button' value=' ".$msg[acquisition_ajout_tva]." ' onClick=\"document.location='./admin.php?categ=acquisition&sub=tva&action=add'\" />";

}


function show_tva_form($id=0) {
		
	global $msg, $charset;
	global $tva_form;
	global $ptab;

	
	$tva_form = str_replace('!!id!!', $id, $tva_form);
	
	if(!$id) {
		
		$tva_form = str_replace('!!form_title!!', htmlentities($msg[acquisition_ajout_tva],ENT_QUOTES,$charset), $tva_form);
		$tva_form = str_replace('!!libelle!!', '', $tva_form);
		$tva_form = str_replace('!!taux_tva!!', '', $tva_form);
		$tva_form = str_replace('!!cp_compta!!', '', $tva_form);

	} else {
		
		$tva = new tva_achats($id);
		$tva_form = str_replace('!!form_title!!', htmlentities($msg[acquisition_modif_tva],ENT_QUOTES, $charset), $tva_form);
		$tva_form = str_replace('!!libelle!!', htmlentities($tva->libelle, ENT_QUOTES, $charset), $tva_form);
		$tva_form = str_replace('!!taux_tva!!', $tva->taux_tva, $tva_form);
		$tva_form = str_replace('!!cp_compta!!', $tva->num_cp_compta, $tva_form);
		
		$ptab = str_replace('!!id!!', $id, $ptab);
		$ptab = str_replace('!!libelle_suppr!!', addslashes($tva->libelle), $ptab);
		$tva_form = str_replace('<!-- bouton_sup -->', $ptab, $tva_form);

	}
	
	print confirmation_delete("./admin.php?categ=acquisition&sub=tva&action=del&id=");
	print $tva_form;
	
}


$tva_form = "
<form class='form-".$current_module."' id='tvaform' name='tvaform' method='post' action=\"./admin.php?categ=acquisition&sub=tva&action=update&id=!!id!!\">
<h3>!!form_title!!</h3>
<!--    Contenu du form    -->
<div class='form-contenu'>

	<div class='row'>
		<label class='etiquette' for='libelle'>".htmlentities($msg[103],ENT_QUOTES,$charset)."</label>
	</div>
	<div class='row'>
		<input type=text id='libelle' name='libelle' value=\"!!libelle!!\" class='saisie-30em' />
	</div>

	<div class='row'>
		<label class='etiquette' for='taux_tva'>".htmlentities($msg[acquisition_tva_taux],ENT_QUOTES,$charset)."</label>
	</div>
	<div class='row'>
		<input type='text' id='taux_tva' name='taux_tva' value=\"!!taux_tva!!\" class='saisie-10em' />&nbsp;
		<label class='etiquette'>%</label>
	</div>

	<div class='row'>
		<label class='etiquette' for='cp_compta'>".htmlentities($msg[acquisition_num_cp_compta],ENT_QUOTES,$charset)."</label>
	</div>
	<div class='row'>
		<input type='text' id='cp_compta' name='cp_compta' value=\"!!cp_compta!!\" class='saisie-20em' />
	</div>

	<div class='row'></div>
</div>

<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=acquisition&sub=tva' \" />&nbsp;
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
	document.forms['tvaform'].elements['libelle'].focus();
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
		document.forms['tvaform'].elements['libelle'].focus();
		return false;	
	}
	return true;
}
</script>

<?php

//Traitement des actions
switch($action) {
	case 'add':
		show_tva_form();
		break;

		
	case 'modif':
		if (tva_achats::exists($id)) {
			show_tva_form($id);
		} else {
			show_list_tva();
		}
		break;

		
	case 'update':
		// vérification validité des données fournies.
		//Pas deux libelles de tva achats identiques 
		$nbr = tva_achats::existsLibelle($libelle, $id);
		if ( $nbr > 0 ) {
			error_form_message($libelle.$msg["acquisition_tva_already_used"]);
			break;
		}
		
		//Vérification du format du taux de tva
		$taux_tva = str_replace(',','.',$taux_tva);
		if ($taux_tva < 0.00 || $taux_tva >99.99) {
			error_form_message($libelle.$msg["acquisition_tva_error"]);
			break;
		}
		
		
		$tva = new tva_achats($id);
		$tva->libelle = $libelle;
		$tva->taux_tva = $taux_tva;
		$tva->num_cp_compta = $cp_compta;	
		$tva->save();
		show_list_tva();

		break;

		
	case 'del':
		if($id) {
			$total1 = tva_achats::hasTypesProduits($id);
			$total2 = tva_achats::hasFrais($id);
			if (($total1+$total2)==0) {
				tva_achats::delete($id);
				show_list_tva();
			} else {
				$msg_suppr_err = $msg[acquisition_tva_used] ;
				if ($total1) $msg_suppr_err .= "<br />- ".$msg[acquisition_tva_used_type] ;
				if ($total2) $msg_suppr_err .= "<br />- ".$msg[acquisition_tva_used_frais] ;

				error_message($msg[321], $msg_suppr_err, 1, 'admin.php?categ=acquisition&sub=tva');
			}
		} else {
			show_list_tva();
		}
		break;


	default:
		show_list_tva();
		break;
}

?>
