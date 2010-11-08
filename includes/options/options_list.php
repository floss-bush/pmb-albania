<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: options_list.php,v 1.10 2009-05-16 11:05:14 dbellamy Exp $

//Gestion des options de type list
$base_path="../..";
$base_auth = "CATALOGAGE_AUTH|ADMINISTRATION_AUTH";
include($base_path."/includes/init.inc.php");

require_once("$include_path/parser.inc.php");
require_once("$include_path/fields.inc.php");
$options=stripslashes($options);

if ($first==1) {
	$param["FOR"]="list";
	if ($MULTIPLE=="yes")
		$param[MULTIPLE][0][value]="yes";
	else
		$param[MULTIPLE][0][value]="no";
	
	for ($i=0; $i<count($ITEM); $i++) {
		$param[ITEMS][0][ITEM][$i][VALUE]=stripslashes($VALUE[$i]);
		$param[ITEMS][0][ITEM][$i][value]="<![CDATA[".stripslashes($ITEM[$i])."]]>";
	}
	
	$param[UNSELECT_ITEM][0][VALUE]=stripslashes($UNSELECT_ITEM_VALUE);
	$param[UNSELECT_ITEM][0][value]="<![CDATA[".stripslashes($UNSELECT_ITEM_LIB)."]]>";	
	
	$options=array_to_xml($param,"OPTIONS");
?>
<script>
opener.document.formulaire.<?php echo $name; ?>_options.value="<?php echo str_replace("\n","\\n",addslashes($options)); ?>";
opener.document.formulaire.<?php echo $name; ?>_for.value="list";
self.close();
</script>
<?php
} else {
?>
<h3><?php echo $msg[procs_options_param].$name; ?></h3><hr />
<?php
$param=_parser_text_no_function_("<?xml version='1.0' encoding='".$charset."'?>\n".$options,"OPTIONS");
if (!$first) {
	if ($param["FOR"]!="list")  {
		$param=array();
		$param["FOR"]="list";
	}
	$MULTIPLE=$param[MULTIPLE][0][value];
	$UNSELECT_ITEM_VALUE=$param[UNSELECT_ITEM][0][VALUE];
	$UNSELECT_ITEM_LIB=$param[UNSELECT_ITEM][0][value];
	for ($i=0; $i<count($param[ITEMS][0][ITEM]); $i++) {
		$ITEM[$i]=$param[ITEMS][0][ITEM][$i][value];
		$VALUE[$i]=$param[ITEMS][0][ITEM][$i][VALUE];
	}
} else {
	$UNSELECT_ITEM_VALUE=stripslashes($UNSELECT_ITEM_VALUE);
	$UNSELECT_ITEM_LIB=stripslashes($UNSELECT_ITEM_LIB);
	for ($i=0; $i<count($ITEM); $i++) {
		$ITEM[$i]=stripslashes($ITEM[$i]);
		$VAL[$i]=stripslashes($VAL[$i]);
	}
	if ($first==2) {
			$ITEM[count($ITEM)]="";
			$VALUE[count($ITEM)]="";
	}
}
?>
<form class='form-<?php echo $current_module ?>' name="formulaire" action="options_list.php" method="post">
<h3><?php echo $type_list[$type]; ?></h3>
<div class='form-contenu'>
<input type="hidden" name="first" value="0">
<input type="hidden" name="name" value="<?php echo $name; ?>">
<input type="hidden" name="type" value="<?php echo $type; ?>">
<table class='table-no-border' width=100%>
<tr><td><?php echo $msg[procs_options_liste_multi]; ?></td><td><input type="checkbox" value="yes" name="MULTIPLE" <?php if ($MULTIPLE=="yes") echo "checked"; ?>></td></tr>
<tr><td><?php echo $msg[procs_options_choix_vide]; ?></td><td><?php echo $msg[procs_options_value]; ?> : <input type="text" size="5" name="UNSELECT_ITEM_VALUE" value="<?php echo htmlentities($UNSELECT_ITEM_VALUE,ENT_QUOTES,$charset); ?>">&nbsp;<?php echo $msg[procs_options_label]; ?> : <input type="text" name="UNSELECT_ITEM_LIB" value="<?php echo htmlentities($UNSELECT_ITEM_LIB,ENT_QUOTES,$charset); ?>"></td></tr>
</table>
<hr /><?php echo $msg[procs_options_liste_options]; ?><br />
<table width=100% border=1>
<?php
echo "<tr><td></td><td><b>".$msg["parperso_options_list_value"]."</b></td><td><b>".$msg["parperso_options_list_lib"]."</b></td></tr>\n";
$n=0;
for ($i=0; $i<count($ITEM); $i++) {
	if($DEL[$i]!=1) { 
		echo "<tr><td><input type=\"checkbox\" name=\"DEL[$n]\" value=\"1\"></td>
			<td><input class='saisie-10em' type=\"text\" value=\"".htmlentities($VALUE[$i],ENT_QUOTES,$charset)."\" name=\"VALUE[]\"></td>
			<td><input class='saisie-20em' type=\"text\" value=\"".htmlentities($ITEM[$i],ENT_QUOTES,$charset)."\" name=\"ITEM[]\"></td></tr>";
		$n++;
	}
}
?>
</table>
<input class="bouton" type="submit" value="<?php echo $msg[ajouter]; ?>" onClick="this.form.first.value=2">&nbsp;
</div>
<input class="bouton" type="submit" value="<?php echo $msg[procs_options_suppr_options_coche]; ?>" onClick="this.form.first.value=3">&nbsp;
<input class="bouton" type="submit" value="<?php echo $msg[77]; ?>" onClick="this.form.first.value=1">
</form>
<?php
}
?>
</body>
</html>