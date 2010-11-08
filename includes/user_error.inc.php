<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: user_error.inc.php,v 1.13 2009-05-16 11:20:27 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// fonctions d'affichage des messages d'erreur

function error_form_message($error_message) {
	
	echo "<script>alert(\"$error_message\"); history.go(-1);</script>";
	exit();
	
	}

function error_message($error_title, $error_message, $back_button=0, $ret_adr='') {

	global $msg;
	global $current_module ;
	/*
		paramètres : -->
		$error_title	:	titre du message
		$error_message	: 	texte du message
		$back_button	:	flag pour affichage du bouton (TRUE=oui ; FALSE=non)
		$ret_adr		:	adresse de retour. si non précisé : history.go(-1)
	*/

	//affichage
	print "<br /><div class='erreur'>$msg[540]</div>
		<div class='row'>
		<div class='colonne10'>
			<img src='./images/error.gif' align='left'>
			</div>
		<div class='colonne80'>
			<strong>$error_message</strong>
			</div>
		</div>";

	if($back_button) {
		if($ret_adr) print "
					<div class='row'>
					<form class='form-$current_module' name='dummy'>
					<input type='button' name='ok' class='bouton' value=' $msg[89] ' onClick='document.location=\"$ret_adr\"'>
					</form>
					<script type='text/javascript'>document.forms['dummy'].elements['ok'].focus();</script>
					</div>";
			else  print "
					<div class='row'>
					<form class='form-$current_module' name='dummy'>
					<input type='button' name='ok' class='bouton' value=' $msg[89] ' onClick='history.go(-1);'>
					</form>
					<script type='text/javascript'>document.forms['dummy'].elements['ok'].focus();</script>
					</div>";
		}
	}

function return_error_message($error_title, $error_message, $back_button=0, $ret_adr='', $ret_url='') {

	global $msg;
	global $current_module ;
	/*	la même que error_message mais return au lieu de print
		paramètres : -->
		$error_title	:	titre du message
		$error_message	: 	texte du message
		$back_button	:	flag pour affichage du bouton (TRUE=oui ; FALSE=non)
		$ret_adr		:	adresse de retour. si non précisé : $default_ret_adr est utilisé
	*/

	$default_ret_adr = './main.php';
	//affichage

	$retour = "
	<br /><div class='erreur'>$msg[540]</div>
	<div class='row'>
		<div class='colonne10'>
			<img src='./images/error.gif' align='left'>
			</div>
		<div class='colonne80'>
			<strong>$error_message</strong>
			</div>
		</div>
		
		";

	if($back_button) {
		if(!$ret_adr) $ret_adr = $default_ret_adr;
		$retour .= "
			<div class='row'>
				<form class='form-$current_module' name='dummy' method=\"post\" action=\"".urldecode($ret_adr)."\">
				<input type=hidden name=ret_url value=\"".addslashes($ret_url)."\">
				<input type='submit' name='ok' class='bouton' value=' $msg[89] ' >
				</form>
				<script type='text/javascript'>
					document.forms['dummy'].elements['ok'].focus();
				</script>
				</div>
				";
	}
	return $retour ;
}


function error_message_history($error_title, $error_message, $back_button=0) {

	global $msg;
	global $base_path;
	global $current_module ;
	/*
		paramètres : -->
		$error_title	:	titre du message
		$error_message	: 	texte du message
		$back_button	:	flag pour affichage du bouton (TRUE=oui ; FALSE=non)
	*/

	//affichage
	print "
	<br /><div class='erreur'>$msg[540]</div>
	<div class='row'>
		<div class='colonne10'>
			<img src='$base_path/images/error.gif' align='left'>
			</div>
		<div class='colonne80'>
			<strong>$error_message</strong>
			</div>
		</div>
		";

	if($back_button) {
		print "
			<div class='row'>
				<form class='form-$current_module' name='dummy'>
				<input type='button' name='ok' class='bouton' value=' $msg[89] ' onClick='history.go(-1);'>
				</form>
				<script type='text/javascript'>
					document.forms['dummy'].elements['ok'].focus();
				</script>
				</div>
				";
	}

	print "";
}

function choice_message($error_title, $error_message, $back_button=0, $ret_adr='', $cancel_button=0, $cancel_adr='') {

	global $msg;
	global $current_module ;
	/*
		paramètres : -->
		$error_title	:	titre du message
		$error_message	: 	texte du message
		$back_button	:	flag pour affichage du bouton (TRUE=oui ; FALSE=non)
		$ret_adr	:	adresse de retour. si non précisé : $default_ret_adr est utilisé
		$cancel_button	:	flag pour affichage du bouton annulation (TRUE=oui ; FALSE=non)
		$cancel_adr	:	adresse d'annulation. si non précisé : $default_ret_adr est utilisé
	*/

	$default_ret_adr = './main.php';
	//affichage

	print "
		<br />
		<table border='0' align='center' bgcolor='#e0e0e0' class='fiche-lecteur' cellpadding='0' width='350'>
			<tr>
				<td class='error-header' colspan='2'>
					$msg[540] <!--$msg[1001] : $error_title-->
				</td>
			</tr>
				<td align='left'><br />
					<img src='./images/error.gif' align='left'>
				</td>
				<td><br />
					<p class='error'>$error_message</p>
				</td>
			</tr>";

	if($back_button) {
		if(!$ret_adr) $ret_adr = $default_ret_adr;
		print "<tr>
			<td align='center' colspan='2'><br />
			<form class='form-$current_module' name='dummy'>
			<input type='button' name='ok' class='button' value=' $msg[89] ' onClick='document.location=\"$ret_adr\"'>
			</form>
			<script type='text/javascript'>
				document.forms['dummy'].elements['ok'].focus();
				</script>
			</td>
			</tr>";
		}
	if($cancel_button) {
		if(!$cancel_adr) $cancel_adr = $default_ret_adr;
		print "<tr>
			<td align='center' colspan='2'><br />
			<form class='form-$current_module' name='dummy2'>
			<input type='button' name='ok' class='button' value=' $msg[76] ' onClick='document.location=\"$cancel_adr\"'>
			</form>
			</td>
			</tr>";
		}

	print "</table>";
}

function form_error_message($error_title, $error_message, $libelle, $ret_adr='', $vars) {

	global $msg;
	global $current_module,$charset ;
	/*	la même que error_message mais return au lieu de print
		paramètres : -->
		$error_title	:	titre du message
		$error_message	: 	texte du message
		$back_button	:	flag pour affichage du bouton (TRUE=oui ; FALSE=non)
		$ret_adr		:	adresse de retour. si non précisé : $default_ret_adr est utilisé
	*/

	$default_ret_adr = './main.php';
	//affichage

	$retour = "
	<br /><div class='erreur'>$msg[540]</div>
	<div class='row'>
		<div class='colonne10'>
			<img src='./images/error.gif' align='left'>
			</div>
		<div class='colonne80'>
			<strong>$error_message</strong>
			</div>
		</div>
		
		";

	if(!$ret_adr) $ret_adr = $default_ret_adr;
	$retour .= "
		<div class='row'>
			<form class='form-$current_module' name='dummy' method=\"post\" action=\"".urldecode($ret_adr)."\">
	";
	foreach($vars as $key=>$values) {
		$retour.="<input type='hidden' name='".htmlentities($key,ENT_QUOTES,$charset)."' value='".htmlentities($values,ENT_QUOTES,$charset)."'/>\n";
	}
	$retour .="		
			<input type='submit' name='ok' class='bouton' value='".$libelle."' >
			</form>
			<script type='text/javascript'>
				document.forms['dummy'].elements['ok'].focus();
			</script>
			</div>
	";

	return $retour ;
}

function information_message($error_title, $error_message, $back_button=0, $ret_adr='', $cancel_button=0, $cancel_adr='') {

	global $msg;
	global $current_module ;
	/*
		paramètres : -->
		$error_title	:	titre du message
		$error_message	: 	texte du message
		$back_button	:	flag pour affichage du bouton (TRUE=oui ; FALSE=non)
		$ret_adr	:	adresse de retour. si non précisé : $default_ret_adr est utilisé
		$cancel_button	:	flag pour affichage du bouton annulation (TRUE=oui ; FALSE=non)
		$cancel_adr	:	adresse d'annulation. si non précisé : $default_ret_adr est utilisé
	*/

	$default_ret_adr = './main.php';
	//affichage

	print "
		<br />
		<table border='0' align='center' bgcolor='#e0e0e0' class='fiche-lecteur' cellpadding='0' width='350'>
			<tr>
				<td class='error-header' colspan='2'>
					$msg[540]<!--$msg[1001] : $error_title-->
				</td>
			</tr>
				<td align='left'><br />
					<img src='./images/idea.gif' align='left'>
				</td>
				<td><br />
					<p class='error'>$error_message</p>
				</td>
			</tr>";

	if($back_button) {
		if(!$ret_adr) $ret_adr = $default_ret_adr;
		print "<tr>
			<td align='center' colspan='2'><br />
			<form class='form-$current_module' name='dummy'>
			<input type='button' name='ok' class='button' value=' $msg[89] ' onClick='document.location=\"$ret_adr\"'>
			</form>
			<script type='text/javascript'>
				document.forms['dummy'].elements['ok'].focus();
				</script>
			</td>
			</tr>";
		}
	if($cancel_button) {
		if(!$cancel_adr) $cancel_adr = $default_ret_adr;
		print "<tr>
			<td align='center' colspan='2'><br />
			<form class='form-$current_module' name='dummy2'>
			<input type='button' name='ok' class='button' value=' $msg[76] ' onClick='document.location=\"$cancel_adr\"'>
			</form>
			</td>
			</tr>";
		}

	print "</table>";
}

function warning($error_title, $error_message)  {
global $base_path;
	
print "
<table border='0' align='center' class='warning'>
<tr>
	<td valign='top' width='33'><img src='$base_path/images/error.gif'></td>
	<td valign='top'><strong>$error_title</strong><br />
	$error_message</td>
</tr>
</table>";

}


