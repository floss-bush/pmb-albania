<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: comptabilite.tpl.php,v 1.4 2009-05-16 11:19:55 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

//Template du formulaire d'exercices
$exer_form = "
<form class='form-".$current_module."' id='exerform' name='exerform' method='post' action=\"./admin.php?categ=acquisition&sub=compta&action=update&ent=!!id_entite!!&id=!!id_exer!!\">
<h3>!!form_title!!</h3>
<!--    Contenu du form    -->
<div class='form-contenu'>

	<input type='hidden' id='id' name='id' value=\"!!id_exer!!\" />

	<div class='row'>
		<label class='etiquette' for='libelle'>".htmlentities($msg[103],ENT_QUOTES,$charset)."</label>
	</div>
	
	<div class='row'>
		<input type='text' id='libelle' name='libelle' value=\"!!libelle!!\" class='saisie-80em' />
	</div>

	<div class='row'>&nbsp;</div>

	<div class='row'>
		<div class='colonne5'>
			<label class='etiquette'>".htmlentities($msg[calendrier_date_debut],ENT_QUOTES,$charset)."</label>
		</div>
		<div class='colonne5'>
			!!date_deb!!
		</div>
	</div>

	<div class='row'>
		<div class='colonne5'>
			<label class='etiquette'>".htmlentities($msg[calendrier_date_fin],ENT_QUOTES,$charset)."</label>
		</div>
		<div class='colonne5'>
			!!date_fin!!
		</div>
	</div>

	<br />
	<div class='row'>
		<div class='colonne5'>
			<label class='etiquette'>".htmlentities($msg[acquisition_statut],ENT_QUOTES,$charset)."</label>
		</div>
		<div class='colonne5'>
			!!statut!!
			<!-- case_def -->
		</div>
	</div>


	<div class='row'></div>
</div>

<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onClick=\"document.location='./admin.php?categ=acquisition&sub=compta&action=list&ent=!!id_entite!!' \" />&nbsp;
		<input class='bouton' type='submit' value=' $msg[77] ' onClick=\"return test_form(this.form)\" />
	</div>
	<div class='right'>
		<!-- bouton_clot -->
		<!-- bouton_sup -->
	</div>
</div>
<div class='row'>
</div>
</form>
<script type='text/javascript'>
	document.forms['exerform'].elements['libelle'].focus();
</script>

";

$ptab[0] = "<input class='bouton' type='button' value=' ".$msg['acquisition_compta_clot']." ' onClick=\"javascript:confirmation_cloture('!!id!!', '!!libelle_suppr!!')\" />&nbsp;&nbsp;";
$ptab[1] = "<input class='bouton' type='button' value=' ".$msg['supprimer']." ' onClick=\"javascript:confirmation_suppression('!!id!!', '!!libelle_suppr!!')\" />";
$ptab[2] = "&nbsp;&nbsp;<input type='checkbox' id='def' name='def' value='1' />".htmlentities($msg['acquisition_statut_ca_def'], ENT_QUOTES, $charset);

$date_deb_mod = "<input type='text' id='date_deb' name='date_deb' value=\"!!date_deb!!\" class='saisie-10em' />";
$date_fin_mod = "<input type='text' id='date_fin' name='date_fin' value=\"!!date_fin!!\" class='saisie-10em' />";

