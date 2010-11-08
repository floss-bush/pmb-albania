<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: abts_pointage.tpl.php,v 1.8 2008-07-01 10:34:41 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

$pointage_list ="
<script type='text/javascript' src='./javascript/sorttable.js'></script>
<a href='javascript:expandAll()'><img src='./images/expand_all.gif' border='0' id='expandall'></a>
<a href='javascript:collapseAll()'><img src='./images/collapse_all.gif' border='0' id='collapseall'></a>
	!!a_recevoir!!
	!!prochain_numero!!
	!!en_retard!!
	!!en_alerte!!
	!!alerte_fin_abonnement!!
	!!alerte_abonnement_depasse!!
";

$pointage_form = "
<script type='text/javascript' src='./javascript/tablist.js'></script>
<h1>".$msg["4000"]." : ".$msg["pointage_libelle_form"]."</h3>
<form class='form-$current_module' id='form_pointage' name='form_pointage' method='post' action=!!action!!>
	<h3>".$msg["4000"].":".$msg["pointage_libelle_form"]."</h3>
	<div class='form-contenu'>
		<input type='hidden' name='num_notice' id='num_notice' value='!!num_notice!!'/>
		<div class='colonne2'>
			<div class='row'>
				<label for='form_pointage' class='etiquette'>".$msg["pointage_titre_filtre"]."</label>
			</div>
			<div class='row'>
				".$msg["pointage_label_localisation"]." : !!localisation!!
			</div>
			<div class='row'>
				&nbsp	
			</div>
		</div>
		<div class='row'>
			
		</div>
		<div class='colonne2'>
			<div class='row'>
				<label for='abonnement_name' class='etiquette'>".$msg["pointage_titre_abonnements_liste"]."</label>
			</div>
		</div>		
		<div class='row'>
			!!bultinage!!		
		</div>
		<!-- Fin du contenu -->
		<div class='row'>
			&nbsp	
		</div>
		<div class='row'>
		<input type='hidden' id='act' name='act' value='' />
		<div class='left'><input type=\"submit\" class='bouton' value='".$msg["actualiser"]."' onClick=\"document.getElementById('act').value='';if(test_form(this.form)==true) this.form.submit();else return false;\"/>&nbsp;
		!!imprimer!!
		</div>			
	</div>
	<div class='row'></div>
</form>
";			
?>
