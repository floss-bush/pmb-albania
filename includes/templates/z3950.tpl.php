<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: z3950.tpl.php,v 1.6 2007-03-14 16:51:33 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

$z3950_search_tpl= "
<form class='form-$current_module' id='z3950_search' method='post' action='./catalog.php?categ=z3950&action=search' name='recherche'>
<!--	Contenu du form	-->
<div class='form-contenu'>
<div class='row'>
	<h3>$msg[z3950_select_bib]</h3>
	</div>
<div class='row'>
	<blockquote>!!liste_bib!! </blockquote>
	</div>
<div class='row'>
	<h3>$msg[z3950_crit_rech]</h3>
	</div>
<div class='row'>
	<blockquote>
		!!crit1!! &nbsp; = &nbsp; <input id='val1' type='text' class='saisie-20em' name='val1' value='!!isbn!!'>
		<blockquote>
			<select name='bool1'>
			<option value='ET'>$msg[z3950_bool_et]</option>
			<option value='OU'>$msg[z3950_bool_ou]</option>
			<option value='SAUF'>$msg[z3950_bool_sauf]</option>
			</select>
			</blockquote>
		!!crit2!! &nbsp; = &nbsp; <input type='text' class='saisie-20em' name='val2' >
		</blockquote>
	</div>
<div class='row'>
	<h3>$msg[z3950_limit_rech]</h3>
	</div>
<div class='row'>
	<blockquote>
		$msg[z3950_limit_rech_txt]
		<select name='limite'>
		<option value='50'>50</option>
		<option value='100' selected>100</option>
		<option value='150'>150</option>
		<option value='200'>200</option>
		</select>
	</div>
</div>
<input type='hidden' name='id_notice' value='!!id_notice!!'>
<input type='submit' name='rechercher' class='bouton' value='$msg[142]'>
</form>

<script type='text/javascript'>
	document.forms['z3950_search'].elements['val1'].focus();
</script>

</center>";
