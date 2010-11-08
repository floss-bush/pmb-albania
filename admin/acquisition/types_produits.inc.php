<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: types_produits.inc.php,v 1.14 2009-05-16 11:11:54 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion des types de produits achetés
require_once("$class_path/types_produits.class.php");
require_once("$class_path/tva_achats.class.php");


function show_list_type() {
	
	global $dbh, $msg, $charset;
	global $acquisition_gestion_tva;

	$aff='';
	$aff.="<table>
	<tr>
		<th>".htmlentities($msg[103], ENT_QUOTES, $charset)."</th>
		<th>".htmlentities($msg['acquisition_num_cp_compta'], ENT_QUOTES, $charset)."</th>";
	if ($acquisition_gestion_tva) {
		$aff.="<th>".htmlentities($msg['acquisition_num_tva_achat'], ENT_QUOTES, $charset)."</th>";
	}
	$aff.= "</tr>";

	$q = types_produits::listTypes();
	$res = mysql_query($q, $dbh);
	$nbr = mysql_num_rows($res);
	$tab_tva=array();
	if ($acquisition_gestion_tva) {
		$q2 = tva_achats::listTva();
		$r2 = mysql_query($q2, $dbh);
		while($row=mysql_fetch_object($r2)) {
			$tab_tva[$row->id_tva]=$row->libelle;
		}
	}
	$parity=1;
	for($i=0;$i<$nbr;$i++) {
		$row=mysql_fetch_object($res);
			if ($parity % 2) {
				$pair_impair = "even";
			} else {
				$pair_impair = "odd";
			}
			$parity += 1;
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=acquisition&sub=type&action=modif&id=$row->id_produit';\" ";
			$aff.="<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>";
	        $aff.="<td><i>".htmlentities($row->libelle, ENT_QUOTES, $charset)."</i></td>";
	        $aff.="<td><i>".htmlentities($row->num_cp_compta, ENT_QUOTES, $charset)."</i></td>";
			if ($acquisition_gestion_tva) {
				$aff.="<td><i>".htmlentities($tab_tva[$row->num_tva_achat], ENT_QUOTES, $charset)."</i></td>";
			}       
			$aff.="</tr>";
	}
	$aff.="</table>
		<input class='bouton' type='button' value=' ".$msg[acquisition_ajout_type]." ' onClick=\"document.location='./admin.php?categ=acquisition&sub=type&action=add'\" />";
	print $aff;
}


function show_type_form($id=0) {
		
	global $dbh, $msg, $charset;
	global $type_form;
	global $ptab;
	global $acquisition_gestion_tva;
	
	$type_form = str_replace('!!id!!', $id, $type_form);
	
	if(!$id) {
		
		$type_form = str_replace('!!form_title!!', htmlentities($msg[acquisition_ajout_type],ENT_QUOTES,$charset), $type_form);
		$type_form = str_replace('!!libelle!!', '', $type_form);
		$type_form = str_replace('!!cp_compta!!', '', $type_form);

	} else {
		
		$type = new types_produits($id);
		$type_form = str_replace('!!form_title!!', htmlentities($msg[acquisition_modif_type],ENT_QUOTES,$charset), $type_form);
		$type_form = str_replace('!!libelle!!', htmlentities($type->libelle,ENT_QUOTES,$charset), $type_form);
		$type_form = str_replace('!!cp_compta!!', $type->num_cp_compta, $type_form);
		
		$ptab = str_replace('!!id!!', $id, $ptab);
		$ptab = str_replace('!!libelle_suppr!!', addslashes($type->libelle), $ptab);
		$type_form = str_replace('<!-- bouton_sup -->', $ptab, $type_form);

	}
	
	if ($acquisition_gestion_tva) {
		$form_tva = "<select id='tva_achat' name ='tva_achat' >";
		$q = tva_achats::listTva();
		$res = mysql_query($q, $dbh);
		while ($row=mysql_fetch_object($res)) {
			$form_tva.="<option value='".$row->id_tva."' ";
			if ($id ) {
				if ($type->num_tva_achat == $row->id_tva) $form_tva.="selected ";
			} 
			$form_tva.=">".$row->libelle." - ".$row->taux_tva." %</option>";
		}
		$form_tva.="</select>";
		$type_form = str_replace('!!tva_achat!!', $form_tva, $type_form);
	}
	
	print confirmation_delete("./admin.php?categ=acquisition&sub=type&action=del&id=");
	print $type_form;
	
}


$type_form = "
<form class='form-".$current_module."' id='typeform' name='typeform' method='post' action=\"./admin.php?categ=acquisition&sub=type&action=update&id=!!id!!\">
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
		<label class='etiquette' for='cp_compta'>".htmlentities($msg[acquisition_num_cp_compta],ENT_QUOTES,$charset)."</label>
	</div>
	<div class='row'>
		<input type='text' id='cp_compta' name='cp_compta' value=\"!!cp_compta!!\" class='saisie-20em' />
	</div>
";

if ($acquisition_gestion_tva) {
$type_form.="	
	<div class='row'>
		<label class='etiquette'>".htmlentities($msg[acquisition_num_tva_achat],ENT_QUOTES,$charset)."</label>
	</div>
	<div class='row'>
		!!tva_achat!!
	</div>
";
}


$type_form.= "
	<div class='row'></div>
</div>

<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=acquisition&sub=type' \" />&nbsp;
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
	document.forms['typeform'].elements['libelle'].focus();
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
		document.forms['typeform'].elements['libelle'].focus();
		return false;	
	}
	return true;
}
</script>

<?php



//Gestion de la tva
if ($acquisition_gestion_tva) {
	$nbr = tva_achats::countTva();
	
	//Gestion de TVA et pas de taux de tva définis
	if (!$nbr) {
		$error_msg.= htmlentities($msg["acquisition_err_tva"],ENT_QUOTES, $charset)."<div class='row'></div>";	
		error_message($msg[321], $error_msg.htmlentities($msg["acquisition_err_par"],ENT_QUOTES, $charset), '1', './admin.php?categ=acquisition');
		die;
	}
}



//traitement des actions
switch($action) {
	case 'add':
		show_type_form();
		break;

		
	case 'modif':
		if (types_produits::exists($id)) {
			show_type_form($id);
		} else {
			show_list_type();
		}
		break;

		
	case 'update':
		// vérification validité des données fournies.
		//Pas deux libelles de types de produits identiques 
		$nbr = types_produits::existsLibelle($libelle, $id);
		if ( $nbr > 0 ) {
			error_form_message($libelle.$msg["acquisition_type_already_used"]);
			break;
		}
		$type = new types_produits($id);
		$type->libelle = $libelle;
		$type->num_cp_compta = $cp_compta;
		$type->num_tva_achat = $tva_achat;	
		$type->save();
		show_list_type();

		break;

		
	case 'del':
		if($id) {
			$total1 = types_produits::hasOffres_remises($id);
			$total2 = types_produits::hasSuggestions($id);
			if (($total1+$total2)==0) {
				types_produits::delete($id);
				show_list_type();
			} else {
				$msg_suppr_err = $msg[acquisition_type_used] ;
				if ($total1) $msg_suppr_err .= "<br />- ".$msg[acquisition_type_used_off] ;
				if ($total2) $msg_suppr_err .= "<br />- ".$msg[acquisition_type_used_sug] ;
				error_message($msg[321], $msg_suppr_err, 1, 'admin.php?categ=acquisition&sub=type');
			}
		} else {
			show_list_type();
		}
		break;


	default:
		show_list_type();
		break;
}

?>
