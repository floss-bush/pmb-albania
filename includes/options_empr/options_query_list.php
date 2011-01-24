<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: options_query_list.php,v 1.16 2011-01-20 16:14:54 arenou Exp $

//Gestion des otpions de type query_list

$base_path="../..";
$base_auth = "CATALOGAGE_AUTH|ADMINISTRATION_AUTH";
include($base_path."/includes/init.inc.php");

require_once("$include_path/parser.inc.php");
require_once("$include_path/fields_empr.inc.php");

$options=stripslashes($options);

if ($first==1) {
	$param["FOR"]="query_list";
	
	$param[QUERY][0][value]="<![CDATA[".stripslashes($REQUETE)."]]>";

	if ($MULTIPLE=="yes")
		$param[MULTIPLE][0][value]="yes";
	else
		$param[MULTIPLE][0][value]="no";

	if ($AUTORITE=="yes")
		$param[AUTORITE][0][value]="yes";
	else
		$param[AUTORITE][0][value]="no";	
	if ($CHECKBOX=="yes")
		$param[CHECKBOX][0][value]="yes";	
	else
		$param[CHECKBOX][0][value]="no";		
	if ($INSERTAUTHORIZED=="yes")
		$param["INSERTAUTHORIZED"][0][value]="yes";
	else
		$param["INSERTAUTHORIZED"][0][value]="no";
			
	if ($OPTIMIZE_QUERY=="yes")
		$param["OPTIMIZE_QUERY"][0][value]="yes";
	else
		$param["OPTIMIZE_QUERY"][0][value]="no";	
		
	$param[UNSELECT_ITEM][0][VALUE]=stripslashes($UNSELECT_ITEM_VALUE);
	$param[UNSELECT_ITEM][0][value]="<![CDATA[".stripslashes($UNSELECT_ITEM_LIB)."]]>";
	$param[CHECKBOX_NB_ON_LINE][0][value]=stripslashes($CHECKBOX_NB_ON_LINE);

	$param["FIELD0"][0][value]=stripslashes($FIELD0);
	$param["FIELD1"][0][value]=stripslashes($FIELD1);
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
	if($options){
		$param=_parser_text_no_function_("<?xml version='1.0' encoding='".$charset."'?>\n".$options,"OPTIONS");
	}
	if ($param["FOR"]!="query_list")  {
		$param=array();
		$param["FOR"]="query_list";
	}
	$MULTIPLE=$param[MULTIPLE][0][value];
	$AUTORITE=$param[AUTORITE][0][value];
	$CHECKBOX=$param[CHECKBOX][0][value];
	$CHECKBOX_NB_ON_LINE=$param[CHECKBOX_NB_ON_LINE][0][value];
	$INSERTAUTHORIZED=$param["INSERTAUTHORIZED"][0][value];
	$UNSELECT_ITEM_VALUE=$param[UNSELECT_ITEM][0][VALUE];
	$UNSELECT_ITEM_LIB=$param[UNSELECT_ITEM][0][value];
	$REQUETE=$param[QUERY][0][value];
	$OPTIMIZE_QUERY=$param["OPTIMIZE_QUERY"][0][value];
	$FIELD0=$param["FIELD0"][0]["value"];
	$FIELD1=$param["FIELD1"][0]["value"];
} else {
	$CHECKBOX_NB_ON_LINE=stripslashes($CHECKBOX_NB_ON_LINE);
	$UNSELECT_ITEM_VALUE=stripslashes($UNSELECT_ITEM_VALUE);
	$UNSELECT_ITEM_LIB=stripslashes($UNSELECT_ITEM_LIB);
	$REQUETE=stripslashes($REQUETE);
	$FIELD0=stripslashes($FIELD0);
	$FIELD1=stripslashes($FIELD1);
}
if ($first==2) {
	$resultat=mysql_query($REQUETE);
	if ($resultat) {
		$FIELD0=mysql_field_name($resultat,0);
		$FIELD1=mysql_field_name($resultat,1);
	}
}
?>
<form class='form-<?php echo $current_module ?>' name="formulaire" action="options_query_list.php" method="post">
<h3><?php echo $type_list_empr[$type]; ?></h3>
<div class='form-contenu'>
<input type="hidden" name="first" value="0">
<input type="hidden" name="name" value="<?php echo $name; ?>">
<input type="hidden" name="type" value="<?php echo $type; ?>">
<input type="hidden" name="FIELD0" value="<?php echo htmlentities($FIELD0,ENT_QUOTES,$charset)?>">
<input type="hidden" name="FIELD1" value="<?php echo htmlentities($FIELD1,ENT_QUOTES,$charset)?>">
<table class='table-no-border' width=100%>
	<tr>
		<td><?php echo $msg[procs_options_liste_multi]; ?></td>
		<td><input type="checkbox" value="yes" name="MULTIPLE" <?php if ($MULTIPLE=="yes") echo "checked"; ?>></td>
	</tr>
	<tr>
		<td><?php echo $msg[pprocs_options_liste_authorities]; ?></td>
		<td><input type="checkbox" value="yes" name="AUTORITE" <?php if ($AUTORITE=="yes") echo "checked"; ?>></td>
	</tr>
	<tr>
		<td><?php echo $msg[pprocs_options_liste_authorities_new_value]; ?></td>
		<td><input type="checkbox" value="yes" name="INSERTAUTHORIZED" <?php if ($INSERTAUTHORIZED=="yes") echo "checked"; ?>></td>
	</tr>
	<tr>
		<td><?php echo $msg[pprocs_options_liste_checkbox]; ?></td>
		<td>
			<input type="checkbox" value="yes" name="CHECKBOX" <?php if ($CHECKBOX=="yes") echo "checked"; ?>/>
			&nbsp;<?php echo $msg[pprocs_options_liste_checkbox_nb_on_line]; ?><input class='saisie-2em' type="text" name="CHECKBOX_NB_ON_LINE" value="<?php echo htmlentities($CHECKBOX_NB_ON_LINE,ENT_QUOTES,$charset); ?>"/>
		</td>
	</tr>	
	<tr>
		<td><?php echo $msg[procs_options_choix_vide]; ?></td>
		<td><?php echo $msg[procs_options_value]; ?> : <input class='saisie-10em' type="text" name="UNSELECT_ITEM_VALUE" value="<?php echo htmlentities($UNSELECT_ITEM_VALUE,ENT_QUOTES,$charset); ?>">&nbsp;<?php echo $msg[procs_options_label]; ?> : <input class='saisie-20em' type="text" name="UNSELECT_ITEM_LIB" value="<?php echo htmlentities($UNSELECT_ITEM_LIB,ENT_QUOTES,$charset); ?>"></td>
	</tr>
	<tr>
		<td colspan="2" >
			<table>
				<tr>
					<td><?php echo $msg[procs_options_requete]; ?></td>
					<td><textarea cols=50 rows=5 wrap=virtual name="REQUETE"><?php echo htmlentities($REQUETE,ENT_QUOTES, $charset); ?></textarea></td>
				</tr>
			</table>
		</td>
	<tr>
		<td><?php echo $msg[pprocs_options_liste_optimize_req]; ?></td>
		<td><input type="checkbox" value="yes" name="OPTIMIZE_QUERY" <?php if ($OPTIMIZE_QUERY=="yes") echo "checked"; ?>></td>
	</tr>
</table>
</div>
<input class="bouton" type="submit" value="<?php echo $msg[procs_options_tester_requete]; ?>" onClick="this.form.first.value=2">&nbsp;
<input class="bouton" type="submit" value="<?php echo $msg[77]; ?>" onClick="this.form.first.value=1">
</form><hr />
<?php
if ($first==2) {
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