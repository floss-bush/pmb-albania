<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: suggestions_categ.inc.php,v 1.6 2009-05-16 11:11:54 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion des listes de suggestions
require_once("$class_path/suggestions_categ.class.php");


function show_list_categ() {
	
	global $dbh;
	global $msg;
	global $charset;

	print "<table>
	<tr>
		<th>".htmlentities($msg[103], ENT_QUOTES, $charset)."</th>
	</tr>";

	$tab_categ = suggestions_categ::getCategList();

	$parity=1;
	foreach($tab_categ as $id_categ=>$lib_categ) {
			if ($parity % 2) {
				$pair_impair = "even";
			} else {
				$pair_impair = "odd";
			}
			$parity += 1;
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=acquisition&sub=categ&action=modif&id=$id_categ';\" ";
	        print "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td><i>".htmlentities($lib_categ, ENT_QUOTES, $charset)."</i></td>";
			print "</tr>";
	}
	print "</table>
		<input class='bouton' type='button' value=' ".$msg[acquisition_ajout_categ]." ' onClick=\"document.location='./admin.php?categ=acquisition&sub=categ&action=add'\" />";

}


function show_categ_form($id=0) {
		
	global $msg;
	global $charset;
	global $categ_form;
	global $ptab;
	
	$categ_form = str_replace('!!id!!', $id, $categ_form);
	
	if(!$id) {
		
		$categ_form = str_replace('!!form_title!!', htmlentities($msg[acquisition_ajout_categ],ENT_QUOTES,$charset), $categ_form);
		$categ_form = str_replace('!!libelle!!', '', $categ_form);
		$categ_form = str_replace('!!commentaire!!', '', $categ_form);

	} else {
		
		$categ = new suggestions_categ($id);
		$categ_form = str_replace('!!form_title!!', htmlentities($msg[acquisition_modif_categ],ENT_QUOTES,$charset), $categ_form);
		$categ_form = str_replace('!!libelle!!', htmlentities($categ->libelle_categ,ENT_QUOTES,$charset), $categ_form);
		
		$ptab = str_replace('!!id!!', $id, $ptab);
		$ptab = str_replace('!!libelle_suppr!!', addslashes($categ->libelle_categ), $ptab);
		$categ_form = str_replace('<!-- bouton_sup -->', $ptab, $categ_form);

	}
	
	print confirmation_delete("./admin.php?categ=acquisition&sub=categ&action=del&id=");
	print $categ_form;
	
}


$categ_form = "
<form class='form-".$current_module."' id='categform' name='categform' method='post' action=\"./admin.php?categ=acquisition&sub=categ&action=update&id=!!id!!\">
<h3>!!form_title!!</h3>
<!--    Contenu du form    -->
<div class='form-contenu'>

	<div class='row'>
		<label class='etiquette' for='libelle'>".htmlentities($msg[103],ENT_QUOTES,$charset)."</label>
	</div>
	<div class='row'>
		<input type=text id='libelle' name='libelle' value=\"!!libelle!!\" class='saisie-60em' />
	</div>

	<div class='row'></div>
</div>

<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=acquisition&sub=categ' \" />&nbsp;
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
	document.forms['categform'].elements['libelle'].focus();
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
		document.forms['categform'].elements['libelle'].focus();
		return false;	
	}
	return true;
}
</script>

<?php

//Traitement des actions
switch($action) {
	case 'add':
		show_categ_form();
		break;

		
	case 'modif':
		if (suggestions_categ::exists($id)) {
			show_categ_form($id);
		} else {
			show_list_categ();
		}
		break;

		
	case 'update':
		// vérification validité des données fournies.
		//Pas deux libelles de categories de suggestions identiques
		$nbr = suggestions_categ::existsLibelle($libelle, $id);
		if ( $nbr > 0 ) {
			error_form_message($libelle.$msg["acquisition_categ_already_used"]);
			break;
		}
		$categ = new suggestions_categ($id);
		$categ->libelle_categ = $libelle;
		$categ->save();
		show_list_categ();
		break;

		
	case 'del':
	
		if($id) {
			if ($id=='1') {	//categorie avec id=1 non supprimable
				$msg_suppr_err = $msg['acquisition_categ_used'] ;
				error_message($msg[321], $msg_suppr_err, 1, 'admin.php?categ=acquisition&sub=categ');
			} else {
				$total1 = suggestions_categ::hasSuggestions($id);
				if ($total1==0) {
					suggestions_categ::delete($id);
				} else {
					$msg_suppr_err = $msg['acquisition_categ_used'] ;
					if ($total1) $msg_suppr_err .= "<br />- ".$msg['acquisition_categ_used_sugg'] ;
					error_message($msg[321], $msg_suppr_err, 1, 'admin.php?categ=acquisition&sub=categ');
				}
			}
		} else {
			show_list_categ();
		}
		break;


	default:
		show_list_categ();
		break;
}

?>
