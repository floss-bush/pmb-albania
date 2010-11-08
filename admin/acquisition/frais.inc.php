<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: frais.inc.php,v 1.17 2009-05-16 11:11:54 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion des frais annexes
require_once("$class_path/frais.class.php");
require_once("$class_path/tva_achats.class.php");


function show_list_frais() {
	
	global $dbh;
	global $msg;
	global $charset;
	global $pmb_gestion_devise;
	
	print "<table>
	<tr>
		<th>".htmlentities($msg[103], ENT_QUOTES, $charset)."</th>
		<th>".htmlentities($msg[acquisition_frais_montant], ENT_QUOTES, $charset)."</th>
	</tr>";

	$res = frais::listFrais();
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
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=acquisition&sub=frais&action=modif&id=$row->id_frais';\" ";
	        print "<tr class='$pair_impair' $tr_javascript style='cursor: pointer' ><td><i>".htmlentities($row->libelle, ENT_QUOTES, $charset)."</i></td><td><i>".htmlentities($row->montant, ENT_QUOTES, $charset)." ".$pmb_gestion_devise."</i></td>";
			print "</tr>";
	}
	print "</table>
		<input class='bouton' type='button' value=' ".$msg[acquisition_ajout_frais]." ' onClick=\"document.location='./admin.php?categ=acquisition&sub=frais&action=add'\" />";

}


function show_frais_form($id=0) {
		
	global $dbh, $msg;
	global $charset;
	global $frais_form;
	global $ptab;
	global $acquisition_gestion_tva;
	
	$frais_form = str_replace('!!id!!', $id, $frais_form);
	
	if(!$id) {
		
		$frais_form = str_replace('!!form_title!!', htmlentities($msg[acquisition_ajout_frais],ENT_QUOTES,$charset), $frais_form);
		$frais_form = str_replace('!!libelle!!', '', $frais_form);
		$frais_form = str_replace('!!condition!!', '', $frais_form);
		$frais_form = str_replace('!!montant!!', '', $frais_form);
		$frais_form = str_replace('!!cp_compta!!', '', $frais_form);

	} else {
		
		$frais = new frais($id);
		$frais_form = str_replace('!!form_title!!', htmlentities($msg[acquisition_modif_frais],ENT_QUOTES,$charset), $frais_form);
		$frais_form = str_replace('!!libelle!!', htmlentities($frais->libelle,ENT_QUOTES,$charset), $frais_form);
		$frais_form = str_replace('!!condition!!', htmlentities($frais->condition_frais,ENT_QUOTES,$charset), $frais_form);
		$frais_form = str_replace('!!montant!!', $frais->montant, $frais_form);
		$frais_form = str_replace('!!cp_compta!!', $frais->num_cp_compta, $frais_form);
		
		$ptab = str_replace('!!id!!', $id, $ptab);
		$ptab = str_replace('!!libelle_suppr!!', addslashes($frais->libelle), $ptab);
		$frais_form = str_replace('<!-- bouton_sup -->', $ptab, $frais_form);

	}

	if ($acquisition_gestion_tva) {
		$form_tva = "<select id='tva_achat' name ='tva_achat' >";
		$q = tva_achats::listTva();
		$res = mysql_query($q, $dbh);
		while ($row=mysql_fetch_object($res)) {
			$form_tva.="<option value='".$row->id_tva."' ";
			if ($id ) {
				if ($frais->num_tva_achat == $row->id_tva) $form_tva.="selected ";
			} 
			$form_tva.=">".$row->libelle." - ".$row->taux_tva." %</option>";
		}
		$form_tva.="</select>";
		$frais_form = str_replace('!!tva_achat!!', $form_tva, $frais_form);
	}
	
	print confirmation_delete("./admin.php?categ=acquisition&sub=frais&action=del&id=");
	print $frais_form;
	
}


$frais_form = "
<form class='form-".$current_module."' id='fraisform' name='fraisform' method='post' action=\"./admin.php?categ=acquisition&sub=frais&action=update&id=!!id!!\">
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
		<label class='etiquette' for='condition'>".htmlentities($msg[acquisition_frais_cond],ENT_QUOTES,$charset)."</label>
	</div>
	<div class='row'>
		<textarea id='condition' name='condition' class='saisie-80em' cols='62' rows='6' wrap='virtual'>!!condition!!</textarea>
	</div>

	<div class='row'>
		<label class='etiquette' for='montant'>".htmlentities($msg[acquisition_frais_montant],ENT_QUOTES,$charset)."</label>
	</div>
	<div class='row'>
		<input type=text id='montant' name='montant' value=\"!!montant!!\" class='saisie-10em' />
		<label class='etiquette'>&nbsp;".$pmb_gestion_devise."</label>
	</div>

	<div class='row'>
		<label class='etiquette' for='cp_compta'>".htmlentities($msg[acquisition_num_cp_compta],ENT_QUOTES,$charset)."</label>
	</div>
	<div class='row'>
		<input type='text' id='cp_compta' name='cp_compta' value=\"!!cp_compta!!\" class='saisie-20em' />
	</div>
";

if ($acquisition_gestion_tva) {
$frais_form.="	
	<div class='row'>
		<label class='etiquette'>".htmlentities($msg[acquisition_num_tva_achat],ENT_QUOTES,$charset)."</label>
	</div>
	<div class='row'>
		!!tva_achat!!
	</div>
";
}


$frais_form.= "
	<div class='row'></div>
</div>

<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=acquisition&sub=frais' \" />&nbsp;
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
	document.forms['fraisform'].elements['libelle'].focus();
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
		document.forms['fraisform'].elements['libelle'].focus();
		return false;	
	}
	return true;
}
</script>

<?php

//Traitement des actions
switch($action) {
	case 'add':
		show_frais_form();
		break;

		
	case 'modif':
		if (frais::exists($id)) {
			show_frais_form($id);
		} else {
			show_list_frais();
		}
		break;

		
	case 'update':
		// vérification validité des données fournies.
		//Pas deux libelles de frais identiques 
		$nbr = frais::existsLibelle($libelle, $id);
		if ( $nbr > 0 ) {
			error_form_message($libelle.$msg["acquisition_frais_already_used"]);
			break;
		}
		
		//Vérification du format du montant
		$montant = str_replace(',','.',$montant);
		if (!is_numeric($montant) || $montant < 0.00 || $montant >999999.99 ) {
			error_form_message($libelle.$msg["acquisition_frais_error"]);
			break;
		}
		
		$frais = new frais($id);
		$frais->libelle = $libelle;
		$frais->condition_frais = $condition;
		$frais->montant = $montant;
		$frais->num_cp_compta = $cp_compta;
		$frais->num_tva_achat = $tva_achat;	
		$frais->save();
		show_list_frais();

		break;

		
	case 'del':
		if($id) {
			$total1 = frais::hasFournisseurs($id);
			if ($total1==0) {
				frais::delete($id);
			} else {
				$msg_suppr_err = $msg[acquisition_frais_used] ;
				if ($total1) $msg_suppr_err .= "<br />- ".$msg[acquisition_frais_used_fou] ;
				error_message($msg[321], $msg_suppr_err, 1, 'admin.php?categ=acquisition&sub=frais');
			}
		} else {
			show_list_frais();
		}
		break;


	default:
		show_list_frais();
		break;
}



?>