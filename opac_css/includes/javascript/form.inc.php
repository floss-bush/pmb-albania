<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: form.inc.php,v 1.5 2007-03-10 10:05:51 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// fonctions javascript de gestion de formulaire

if ( ! defined( 'FORM_INC' ) ) {
define( 'FORM_INC', 1 );

// ----------------------------------------------------
// Constantes
// ----------------------------------------------------

define(ALLOW_ALL,       0);
define(DENY_EMPTY,      1);
define(DENY_ALPHA,      2);
define(DENY_NUM,        4);
define(SUPPRESS_SPACES, 8);

define(GREATER,         0);
define(GREATER_STRICT,  1);
define(LESSER,          2);
define(LESSER_STRICT,   3);
define(EQUAL,           4);
define(DIFFERENT,       5);

// ----------------------------------------------------
// Squelette de la fonction test_form
// ----------------------------------------------------

$script_test_form = "
<script type='text/javascript'>
<!--
	function test_form(form)
	{
!!tests!!
		return true;
	}
-->
</script>";

// ----------------------------------------------------
// Fonction de création de test d'un champs contre une valeur
// ----------------------------------------------------
function test_field_value_comp($form, $el1, $condition, $val, $message) {
	$script = "";
	switch ($condition) {
		case GREATER:
			$symbol = ">";
			break;
		case GREATER_EQUAL:
			$symbol = ">=";
			break;
		case LESSER:
			$symbol = "<";
			break;
		case LESSER_EQUAL:
			$symbol = "<=";
			break;
		case EQUAL:
			$symbol = "==";
			break;
		case DIFFERENT:
			$symbol = "!=";
			break;
		default:
			return "";
	}

	$script = "
		if ($form.$el1.value $symbol $val)
		{
			alert(\"$message\");
			$form.$el1.focus();
			return false;
		}";

	return $script;
}

// ----------------------------------------------------
// Fonction de création de test de deux champs de formulaire
// ----------------------------------------------------
function test_field_el_comp($form, $el1, $condition, $el2, $message) {
	$script = "";
	switch ($condition) {
		case GREATER:
			$symbol = ">";
			break;
		case GREATER_EQUAL:
			$symbol = ">=";
			break;
		case LESSER:
			$symbol = "<";
			break;
		case LESSER_EQUAL:
			$symbol = "<=";
			break;
		case EQUAL:
			$symbol = "==";
			break;
		case DIFFERENT:
			$symbol = "!=";
			break;
		default:
			return "";
	}

	$script = "
		if ($form.$el1.value $symbol form.$el2.value)
		{
			alert(\"$msg\");
			$form.$el2.focus();
		}";

	return $script;
}

// ----------------------------------------------------
// Fonction de création de test d'un champ de formulaire
// ----------------------------------------------------
function test_field($form, $element, $field_name, $restrictions=ALLOW_ALL) {
	$script = "";
	if ($restrictions & DENY_EMPTY)
	{
		$script .= "
		if ($form.$element.value.length == 0)
		{
			alert(\"Vous devez saisir quelque chose pour le champ $field_name\");
			$form.$element.focus();
			return false;
		}";
	}

	if ($restrictions & DENY_ALPHA)
	{
		$script .= "
		var exp = new RegExp('[a-zA-Z]','g');
		if(exp.test($form.$element.value))
		{
			alert(\"Vous ne pouvez pas entrer de caractères alphabétiques pour le champ $field_name\");
			$form.$element.focus();
			return false;
		}";
	}

	if ($restrictions & DENY_NUM)
	{
		$script .= "
		var exp = new RegExp('[0-9]','g');
		if(exp.test($form.$element.value))
		{
			alert(\"Vous ne pouvez pas entrer de caractères numériques pour le champ $field_name\");
			$form.$element.focus();
			return false;
		}";
	}

	if ($restrictions & SUPPPRESS_SPACES)
	{
		$script .= "
		$form.$element.value = $form.$element.value.replace(/ /g, '');";

	}

	return $script;
}

function form_focus($form, $element)
{
	$script =
"<script type='text/javascript'>
<!--
	document.$form.$element.focus();
-->
</script>
";
	return $script;
}



}	// fin de définition
