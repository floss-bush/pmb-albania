<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: setcb.php,v 1.16 2009-05-16 11:12:04 dbellamy Exp $
// popup de saisie d'un code barre

require_once ("../includes/error_report.inc.php") ;
require_once ("../includes/global_vars.inc.php") ;
require_once ("../includes/config.inc.php");

$include_path      = "../".$include_path; 
$class_path        = "../".$class_path;
$javascript_path   = "../".$javascript_path;
$styles_path       = "../".$styles_path;

require("$include_path/db_param.inc.php");
require("$include_path/mysql_connect.inc.php");
// connection MySQL
$dbh = connection_mysql();

include("$include_path/error_handler.inc.php");
include("$include_path/sessions.inc.php");
include("$include_path/misc.inc.php");
include("$include_path/isbn.inc.php");
include("$class_path/XMLlist.class.php");

if(!checkUser('PhpMyBibli')) {
	// localisation (fichier XML) (valeur par défaut)
	$messages = new XMLlist("$include_path/messages/$lang.xml", 0);
	$messages->analyser();
	$msg = $messages->table;
	print '<html><head><link rel=\"stylesheet\" type=\"text/css\" href=\"../../styles/$stylesheet; ?>\"></head><body>';
	require_once("$include_path/user_error.inc.php");
	error_message($msg[11], $msg[12], 1);
	print '</body></html>';
	exit;
	}


if(SESSlang) {
	$lang=SESSlang;
	$helpdir = $lang;
	}

// localisation (fichier XML)
$messages = new XMLlist("$include_path/messages/$lang.xml", 0);
$messages->analyser();
$msg = $messages->table;

require("$include_path/templates/common.tpl.php");

header ("Content-Type: text/html; charset=".$charset);

print "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN'
 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' lang='$msg[1002]' charset='".$charset."'>
	<meta http-equiv='Pragma' content='no-cache'>
		<meta http-equiv='Cache-Control' content='no-cache'>";
print link_styles($stylesheet) ;
print "	<title>$msg[4014]</title></head><body>";
	
if (!$formulaire_appelant) $formulaire_appelant="notice" ;
if (!$objet_appelant) $objet_appelant="f_cb" ;

// traitement de la soumission
if ($suite) { // un CB a été soumis
	if ($cb) {
		if(isEAN($cb)) {
			// la saisie est un EAN -> on tente de le formater en ISBN
			$code = EANtoISBN($cb);
			// si échec, on prend l'EAN comme il vient
			if(!$code) $code = $cb;
			} else {
				if(isISBN($cb)) {
					// si la saisie est un ISBN
					$code = formatISBN($cb,13);
					// si échec, ISBN erroné on le prend sous cette forme
					if(!$code) $code = $cb;
					} else {
						// ce n'est rien de tout ça, on prend la saisie telle quelle
						$code = $cb;
						}
				}
		$code_temp = $code;
		}
	if ($code_temp) {
		if ($bulletin) {
			if ($notice_id) $and_clause = " and bulletin_id!='".$notice_id."'" ;
				else $and_clause = "" ;
			$rqt_verif_code = "select count(1) from bulletins where bulletin_cb='".$code_temp."'".$and_clause ;
			} else {
				if ($notice_id) $and_clause = " and notice_id!='".$notice_id."'" ;
					else $and_clause = "" ;
				$rqt_verif_code = "select count(1) from notices where code ='".$code_temp."'".$and_clause ;
				}
		$res_verif_code = mysql_query($rqt_verif_code, $dbh);
		$nbr_verif_code = mysql_result($res_verif_code, 0, 0);
		if ($nbr_verif_code > 0) $alerte_code_double = 1 ;
			else $alerte_code_double = 0 ;
		}
	} 

if ($alerte_code_double) {
	?>
		<script type="text/javascript">
			if (confirm("<?php echo $msg[isbn_duplicate_raz]; ?>")) {
				window.opener.document.forms['<?php echo $formulaire_appelant; ?>'].elements['<?php echo $objet_appelant; ?>'].value = '<?php echo $code_temp; ?>';
				window.close();
				}
			</script>
		<?php
	} elseif ($suite) {
		?>
			<script type="text/javascript">
			window.opener.document.forms['<?php echo $formulaire_appelant; ?>'].elements['<?php echo $objet_appelant; ?>'].value = '<?php echo $code_temp; ?>';
			window.close();
			</script>
		<?php
		}
			

?>
<div align='center'>
	<form class='form-$current_module' name='setcb' action='./setcb.php' >
		<small><?php echo $msg[4056]; ?></small><br />
		<input type='text' name='cb' value=''>
		<input type='hidden' name='notice_id' value='<?php echo $notice_id; ?>'>
		<input type='hidden' name='formulaire_appelant' value='<?php echo $formulaire_appelant; ?>'>
		<input type='hidden' name='objet_appelant' value='<?php echo $objet_appelant; ?>'>
		<input type='hidden' name='bulletin' value='<?php echo $bulletin; ?>'>
		<input type='hidden' name='suite' value='1'>
		<p>
			<input type='button' class='bouton' name='bouton' value='<?php echo $msg[76]; ?>' onClick='window.close();'>
			<input type='submit' class='bouton' name='save' value='<?php echo $msg[77]; ?>' />
		</p>
	</form>
<script type="text/javascript">
	self.focus();
		document.forms['setcb'].elements['cb'].focus();
</script>
</div>
</body>
</html>
