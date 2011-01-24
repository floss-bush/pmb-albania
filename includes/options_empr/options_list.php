<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: options_list.php,v 1.29 2011-01-20 16:14:54 arenou Exp $

//Gestion des options de type list
$base_path="../..";
$base_auth = "CATALOGAGE_AUTH|ADMINISTRATION_AUTH";
include($base_path."/includes/init.inc.php");

require_once("$include_path/parser.inc.php");
require_once("$include_path/fields_empr.inc.php");

function tonum($n) {
	return $n*1;
}

$options=stripslashes($options);

if ($first==1) {
	$param["FOR"]="list";
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
	if ($NUM_AUTO=="yes")
		$param[NUM_AUTO][0][value]="yes";
	else
		$param[NUM_AUTO][0][value]="no";
	/*
	 * On regarde si il n'y a pas un doubon dans les valeurs
	 */
	 //On enlève les valeurs vide
	foreach ( $VALUE as $key => $value ) {
       if($value === ""){
       		unset($VALUE[$key]);
			unset($ITEM[$key]);
			unset($ORDRE[$key]);
       }
	}	
	//Pour que les clés se suivent
	$VALUE=array_merge($VALUE);
	$ITEM=array_merge($ITEM);
	$ORDRE=array_merge($ORDRE);
	//Pour tester si il y a des doublons
	$temp=array_flip($VALUE);
	if(is_array($VALUE) && (count($temp) != count($VALUE))){
		?>
		<script>
		alert("<?php echo $msg["parperso_valeur_existe_liste"];?>");
		history.go(-1);
		</script>
		<?php
		exit();
	}
	
	$requete="delete from ".$_custom_prefixe_."_custom_lists where ".$_custom_prefixe_."_custom_champ=".$idchamp;
	mysql_query($requete);
	$requete="SELECT datatype FROM ".$_custom_prefixe_."_custom WHERE idchamp = $idchamp";
	$resultat = mysql_query($requete);
	$dtype = mysql_result($resultat,0,0);
	for ($i=0; $i<count($ITEM); $i++) {
		if($VALUE[$i] !== "") {
			/* On ne met pas a jour car on ne peut modifier que les valeurs qui ne sont pas utilisées*/
			/*
			$requete="UPDATE ".$_custom_prefixe_."_custom_values SET ".$_custom_prefixe_."_custom_".$dtype." = '".$VALUE[$i]."' WHERE  ".$_custom_prefixe_."_custom_champ = $idchamp AND ".$_custom_prefixe_."_custom_$dtype = '".$EXVAL[$i]."'";
			mysql_query($requete);*/
			$requete="insert into ".$_custom_prefixe_."_custom_lists (".$_custom_prefixe_."_custom_champ, ".$_custom_prefixe_."_custom_list_value, ".$_custom_prefixe_."_custom_list_lib, ordre) values($idchamp, '".$VALUE[$i]."','".$ITEM[$i]."','".$ORDRE[$i]."')";
			mysql_query($requete);
		}			
	}
	
	$param[UNSELECT_ITEM][0][VALUE]=stripslashes($UNSELECT_ITEM_VALUE);
	$param[UNSELECT_ITEM][0][value]="<![CDATA[".stripslashes($UNSELECT_ITEM_LIB)."]]>";	
	$param[DEFAULT_VALUE][0][value]=stripslashes($DEFAULT_VALUE);
	$param[CHECKBOX_NB_ON_LINE][0][value]=stripslashes($CHECKBOX_NB_ON_LINE);
	$options=array_to_xml($param,"OPTIONS");
	?>
	<script>
	opener.document.formulaire.<?php echo $name; ?>_options.value="<?php echo str_replace("\n","\\n",addslashes($options)); ?>";
	opener.document.formulaire.<?php echo $name; ?>_for.value="list";
	self.close();
	</script>
	<?php
} else {
	print "<h3>".$msg[procs_options_param].$name."</h3><hr />";
	if (!$first) {
		if($options){
			$param=_parser_text_no_function_("<?xml version='1.0' encoding='".$charset."'?>\n".$options,"OPTIONS");
		}
		if ($param["FOR"]!="list")  {
			$param=array();
			$param["FOR"]="list";
		}
		$MULTIPLE=$param[MULTIPLE][0][value];
		$AUTORITE=$param[AUTORITE][0][value];
		$CHECKBOX=$param[CHECKBOX][0][value];
		$CHECKBOX_NB_ON_LINE=$param[CHECKBOX_NB_ON_LINE][0][value];
		$NUM_AUTO=$param[NUM_AUTO][0][value];
		$UNSELECT_ITEM_VALUE=$param[UNSELECT_ITEM][0][VALUE];
		$UNSELECT_ITEM_LIB=$param[UNSELECT_ITEM][0][value];
		$DEFAULT_VALUE=$param[DEFAULT_VALUE][0][value];
		
		//Récupération des valeurs de la liste
		if ($idchamp) {
			$requete="select ".$_custom_prefixe_."_custom_list_value, ".$_custom_prefixe_."_custom_list_lib, ordre from ".$_custom_prefixe_."_custom_lists where ".$_custom_prefixe_."_custom_champ=$idchamp order by ordre";
			$resultat=mysql_query($requete);
			if (mysql_numrows($resultat)) {
				$i=0;
				while (($r=mysql_fetch_array($resultat))) {
					$ITEM[$i]=$r[$_custom_prefixe_."_custom_list_lib"];
					$VALUE[$i]=$r[$_custom_prefixe_."_custom_list_value"];
					$ORDRE[$i]=$r["ordre"];
					$i++;
				}
			}
		}
	} else {
		$CHECKBOX_NB_ON_LINE=stripslashes($CHECKBOX_NB_ON_LINE);
		$UNSELECT_ITEM_VALUE=stripslashes($UNSELECT_ITEM_VALUE);
		$UNSELECT_ITEM_LIB=stripslashes($UNSELECT_ITEM_LIB);
		$DEFAULT_VALUE=stripslashes($DEFAULT_VALUE);
		for ($i=0; $i<count($ITEM); $i++) {
			$ITEM[$i]=stripslashes($ITEM[$i]);
			$VALUE[$i]=stripslashes($VALUE[$i]);
			$ORDRE[$i]=stripslashes($ORDRE[$i]);
		}
		if ($first==2) {
			/*
			 * On regarde si il n'y a pas un doubon dans les valeurs quand elle existe
			 */
			$temp2=$VALUE;
			if( is_array($temp2)) {
				foreach ( $temp2 as $key => $value ) {
			       if($value === ""){
			       		unset($temp2[$key]);
			       }
				}
				$temp=array_flip($temp2);
			}
			if(is_array($temp2) && (count($temp) != count($temp2))){
				?>
				<script>
				alert("<?php echo $msg["parperso_valeur_existe_liste"];?>");
				history.go(-1);
				</script>
				<?php
				exit();
			}
			if($NUM_AUTO){
				if(!$VALUE && !$ITEM){
					$ITEM[count($ITEM)]="";
					$VALUE[count($ITEM)-1]="1";
					$ORDRE[count($ITEM)-1]="";
				} else {
					$ITEM[count($ITEM)]="";
					$VALUE[count($ITEM)-1]=(max(array_map("tonum",$VALUE))*1)+1;
					$ORDRE[count($ITEM)-1]="";
				}
			} else {
				$ITEM[count($ITEM)]="";
				$VALUE[count($ITEM)-1]="";
				$ORDRE[count($ITEM)-1]="";
			}
		}
		if ($first==4) {
			//Tri des options
			if($ITEM){
				$ITEM_REVERSE=$ITEM;
				reset($ITEM_REVERSE);
				while (list($key,$val)=each($ITEM_REVERSE)) {
					$ITEM_REVERSE[$key]=convert_diacrit($ITEM_REVERSE[$key]);
				}
				/*asort($ITEM_REVERSE);*/
				reset($ITEM_REVERSE);
				natcasesort($ITEM_REVERSE);
				reset($ITEM_REVERSE);
				$n_o=0;
				while (list($key,$val)=each($ITEM_REVERSE)) {
					$ORDRE[$key]=$n_o;
					$n_o++;
				}
			}	
		}
	}

	?>
	<form class='form-<?php echo $current_module ?>' name="formulaire" action="options_list.php" method="post">
		<h3><?php echo $type_list_empr[$type]; ?></h3>
			<div class='form-contenu'>
				<input type="hidden" name="first" value="0">
				<input type="hidden" name="name" value="<?php echo $name; ?>">
				<input type="hidden" name="type" value="<?php echo $type; ?>">
				<input type="hidden" name="idchamp" value="<?php echo $idchamp; ?>">
				<input type="hidden" name="_custom_prefixe_" value="<?php echo $_custom_prefixe_; ?>">
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
						<td><?php echo $msg[pprocs_options_liste_checkbox]; ?></td>
						<td>
							<input type="checkbox" value="yes" name="CHECKBOX" <?php if ($CHECKBOX=="yes") echo "checked"; ?>/>
							&nbsp;<?php echo $msg[pprocs_options_liste_checkbox_nb_on_line]; ?><input class='saisie-2em' type="text" name="CHECKBOX_NB_ON_LINE" value="<?php echo htmlentities($CHECKBOX_NB_ON_LINE,ENT_QUOTES,$charset); ?>"/>
						</td>					
					</tr>					
					<tr>
						<td><?php echo $msg[num_auto_list]; ?></td>
						<td><input type="checkbox" value="yes" name="NUM_AUTO" <?php if ($NUM_AUTO=="yes") echo "checked"; ?>></td>
					</tr>
					<tr>
						<td><?php echo $msg[procs_options_choix_vide]; ?></td>
						<td><?php echo $msg[procs_options_value]; ?> : <input type="text" size="5" name="UNSELECT_ITEM_VALUE" value="<?php echo htmlentities($UNSELECT_ITEM_VALUE,ENT_QUOTES,$charset); ?>">&nbsp;<?php echo $msg[procs_options_label]; ?> : <input type="text" name="UNSELECT_ITEM_LIB" value="<?php echo htmlentities($UNSELECT_ITEM_LIB,ENT_QUOTES,$charset); ?>"></td>
					</tr>
					<tr>
						<td><?php echo $msg["proc_options_default_value"]; ?></td>
						<td><?php echo $msg[procs_options_value]; ?> : <input type="text" class="saisie-10em" name="DEFAULT_VALUE" value="<?php echo htmlentities($DEFAULT_VALUE,ENT_QUOTES,$charset);?>"></td>
					</tr>
				</table>
			<hr /><?php echo $msg[procs_options_liste_options]; ?><br />
	<?php 
	if ($idchamp) {
		?>
		<table width=100% border=1>
		<?php
		echo "<tr><td></td><td><b>".$msg["parperso_options_list_value"]."</b></td><td><b>".$msg["parperso_options_list_lib"]."</b></td><td><b>".$msg["parperso_options_list_order"]."</b></td></tr>\n";
		$n=0;
		$requete="SELECT datatype FROM ".$_custom_prefixe_."_custom WHERE idchamp = $idchamp";
		$resultat = mysql_query($requete);
		$dtype = mysql_result($resultat,0,0);
		
		for ($i=0; $i<count($ITEM); $i++) {
			if($DEL[$i]!=1) {
				//Recherche de la valeur dans les notices
				$is_deletable=true;
				if($VALUE[$i] !== "") {
					$r_deletable="select count(".$_custom_prefixe_."_custom_origine) as C,".$_custom_prefixe_."_custom_".$dtype." as T from ".$_custom_prefixe_."_custom_values where ".$_custom_prefixe_."_custom_champ=".$idchamp." and ".$_custom_prefixe_."_custom_".$dtype."='".addslashes($VALUE[$i])."' GROUP BY T";
					$r_del=mysql_query($r_deletable);
					if ($r_del) {
						$objdel = mysql_fetch_object($r_del);
						if ($objdel->T != $VALUE[$i]){
							$is_deletable=true;
						}elseif($objdel->C > 0){
							$is_deletable=false;
						}else{
							$is_deletable=true;
						}
					}
				}
				echo "<tr><td ".(!$is_deletable?"title='".htmlentities($msg[perso_field_used],ENT_QUOTES,$charset)."' ":"")."><input type=\"hidden\" name=\"EXVAL[]\" value=\"".htmlentities($VALUE[$i],ENT_QUOTES,$charset)."\"><input type=\"checkbox\" name=\"DEL[$n]\" value=\"1\" ".(!$is_deletable?"disabled='disabled' ":"")."></td>
					<td ".(!$is_deletable?"title='".htmlentities($msg[perso_field_used],ENT_QUOTES,$charset)."' ":"")."><input class='saisie-10em' type=\"text\" value=\"".htmlentities($VALUE[$i],ENT_QUOTES,$charset)."\" name=\"VALUE[]\" ".(!$is_deletable?"readonly='readonly' ":"")."></td>
					<td><input class='saisie-20em' type=\"text\" value=\"".htmlentities($ITEM[$i],ENT_QUOTES,$charset)."\" name=\"ITEM[]\"></td>";
				echo "<td><input class='saisie-10em' type=\"text\" value=\"".htmlentities($ORDRE[$i],ENT_QUOTES,$charset)."\" name=\"ORDRE[]\"></td>";
				echo "</tr>";
				$n++;
			}
		}
	} else {
		echo "<b>".$msg["parperso_options_list_before_rec"]."</b>";
	}
	?>
	</table>
	</div>
	<?php 
	if ($idchamp) {
		?>
		<input class="bouton" type="submit" value="<?php echo $msg[ajouter]; ?>" onClick="this.form.first.value=2">&nbsp;
		<input class="bouton" type="submit" value="<?php echo $msg[procs_options_suppr_options_coche]; ?>" onClick="this.form.first.value=3">&nbsp;
		<input class="bouton" type="submit" value="<?php echo $msg["proc_options_sort_list"];?>" onClick="this.form.first.value=4">&nbsp;
		<?php 
	}
	?>
	<input class="bouton" type="submit" value="<?php echo $msg[77]; ?>" onClick="this.form.first.value=1">
	</form>
	<?php
}
?>
</body>
</html>