<?php

// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sel_word.tpl.php,v 1.2 2009-05-16 10:52:55 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");	

//template du sélecteur de mot pour le dictionnaire des synonymes

$sel_word="!!jscript!!
".$msg["select_word"]."<input type='button' class='bouton_small' value='$msg[word_add]' onClick=\"document.location='!!action!!&action=add';\" style='margin-left:10px;'/>
<hr />
!!lettres!!
<div class='row'>&nbsp;</div>
!!liste_mots!!
";

$add_word="
$msg[word_add]
<div class='row'>&nbsp;</div>
<form class='form-$current_module' id='saisie_form' name='saisie_form' method='post' action='!!action!!&action=modif'>\n
<div align='center'><input type='text' class='saisie-20em' name='f_word_add' value=''><div class='row'>&nbsp;</div>
<div class='row'><input type='submit' class='bouton_small' value='$msg[77]'>&nbsp;<input type='button' class='bouton_small' value='$msg[76]' onClick=\"document.location='!!action!!';\"></div></div>
</form>
<script type='text/javascript'>
	document.forms['saisie_form'].elements['f_word_add'].focus();
</script>";
?>