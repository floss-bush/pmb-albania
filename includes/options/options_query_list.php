<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: options_query_list.php,v 1.10 2009-05-16 11:05:14 dbellamy Exp $

//Gestion des otpions de type query_list

$base_path="../..";
$base_auth = "CATALOGAGE_AUTH|ADMINISTRATION_AUTH";
include($base_path."/includes/init.inc.php");

require_once("$include_path/parser.inc.php");
require_once("$include_path/fields.inc.php");

$options=stripslashes($options);

if ($first==1) {
	$param["FOR"]="query_list";
	
	$param[QUERY][0][value]="<![CDATA[".stripslashes($REQUETE)."]]>";

	if ($MULTIPLE=="yes")
		$param[MULTIPLE][0][value]="yes";
	else
		$param[MULTIPLE][0][value]="no";
		
	$param[UNSELECT_ITEM][0][VALUE]=stripslashes($UNSELECT_ITEM_VALUE);
	$param[UNSELECT_ITEM][0][value]="<![CDATA[".stripslashes($UNSELECT_ITEM_LIB)."]]>";	
	$options=array_to_xml($param,"OPTIONS");
?>
<script>
opener.document.formulaire.<?php echo $name; ?>_options.value="<?php echo str_replace("\n","\\n",addslashes($options)); ?>";
opener.document.formulaire.<?php echo $name; ?>_for.value="query_list";
self.close();
</script>
<?php
} else {
?>
<h3><?php echo $msg[procs_options_param].$name; ?></h3><hr />

<?php
if (!$first) {
	$param=_parser_text_no_function_("<?xml version='1.0' encoding='".$charset."'?>\n".$options,"OPTIONS");
	if ($param["FOR"]!="query_list")  {
		$param=array();
		$param["FOR"]="query_list";
	}
	$MULTIPLE=$param[MULTIPLE][0][value];
	$UNSELECT_ITEM_VALUE=$param[UNSELECT_ITEM][0][VALUE];
	$UNSELECT_ITEM_LIB=$param[UNSELECT_ITEM][0][value];
	$REQUETE=$param[QUERY][0][value];
} else {
	$UNSELECT_ITEM_VALUE=stripslashes($UNSELECT_ITEM_VALUE);
	$UNSELECT_ITEM_LIB=stripslashes($UNSELECT_ITEM_LIB);
	$REQUETE=stripslashes($REQUETE);
}
?>
<form class='form-<?php echo $current_module ?>' name="formulaire" action="options_query_list.php" method="post">
<h3><?php echo $type_list[$type]; ?></h3>
<div class='form-contenu'>
<input type="hidden" name="first" value="0">
<input type="hidden" name="name" value="<?php echo $name; ?>">
<input type="hidden" name="type" value="<?php echo $type; ?>">
<table class='table-no-border' width=100%>
<tr><td><?php echo $msg[procs_options_liste_multi]; ?></td><td><input type="checkbox" value="yes" name="MULTIPLE" <?php if ($MULTIPLE=="yes") echo "checked"; ?>></td></tr>
<tr><td><?php echo $msg[procs_options_choix_vide]; ?></td><td><?php echo $msg[procs_options_value]; ?> : <input class='saisie-10em' type="text" name="UNSELECT_ITEM_VALUE" value="<?php echo htmlentities($UNSELECT_ITEM_VALUE,ENT_QUOTES,$charset); ?>">&nbsp;<?php echo $msg[procs_options_label]; ?> : <input class='saisie-20em' type="text" name="UNSELECT_ITEM_LIB" value="<?php echo htmlentities($UNSELECT_ITEM_LIB,ENT_QUOTES,$charset); ?>"></td></tr>
<tr><td><?php echo $msg[procs_options_requete]; ?></td><td><textarea cols=50 rows=5 wrap=virtual name="REQUETE"><?php echo htmlentities($REQUETE,ENT_QUOTES, $charset); ?></textarea></td></tr>
</table>
</div>
<input class="bouton" type="submit" value="<?php echo $msg[procs_options_tester_requete]; ?>" onClick="this.form.first.value=2">&nbsp;
<input class="bouton" type="submit" value="<?php echo $msg[77]; ?>" onClick="this.form.first.value=1">
</form><hr />
<?php
if ($first==2) {
	$resultat=mysql_query($REQUETE);
	if (!$resultat) {
		echo "<center>$msg[procs_options_echec_requete] <br />".mysql_error()."</center>";
	} else {
		echo "<center><b>$msg[procs_options_reponse_requete]</b></center>";
		echo "<table width=100% border=1>\n";
		while ($r=mysql_fetch_row($resultat)) {
			echo "<tr><td>".htmlentities($r[0],ENT_QUOTES,$charset)."</td><td>".htmlentities($r[1],ENT_QUOTES,$charset)."</td></tr>\n";
		}
		echo "</table>";
	}
}
}
?>
</body>
</html>