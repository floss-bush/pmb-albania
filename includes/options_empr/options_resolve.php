<?php
 // +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: options_resolve.php,v 1.6.2.1 2011-05-19 14:03:03 arenou Exp $

//Gestion des options de type resolve
$base_path = "../..";
$base_auth = "CATALOGAGE_AUTH|ADMINISTRATION_AUTH";
include ($base_path."/includes/init.inc.php");

require_once ("$include_path/parser.inc.php");
require_once ("$include_path/fields_empr.inc.php");

if(!$dtype && $idchamp){
	$requete="SELECT datatype FROM ".$_custom_prefixe_."_custom WHERE idchamp = $idchamp";
	$resultat = mysql_query($requete);
	$dtype = mysql_result($resultat,0,0);
}
$options = stripslashes($options);
//Si enregistrer
if ($first==1) {
	$param["FOR"] = "resolve";
	$param[SIZE][0][value] = stripslashes($SIZE*1);
	$param[REPEATABLE][0][value] = $REPEATABLE ? 1 : 0;
	if(count($RESOLVE[id])==0){
	 	$param[RESOLVE][0][id] = "1";
	 	$param[RESOLVE][0][label] = "Pubmed";
	 	$param[RESOLVE][0][value] = "http://www.ncbi.nlm.nih.gov/pubmed/!!id!!";
	 	$param[RESOLVE][1][id] = "2";
	 	$param[RESOLVE][1][label] = "DOI";
	 	$param[RESOLVE][1][value] = "http://dx.doi.org/!!id!!";
	}
	for($i=0; $i<count($RESOLVE[id]);$i++){
		if($RESOLVE[id][$i] && $RESOLVE[label][$i] && $RESOLVE[value][$i]){
			$param[RESOLVE][$i][id] = $RESOLVE[id][$i];
			$param[RESOLVE][$i][label] = $RESOLVE[label][$i];
			$param[RESOLVE][$i][value] = "<![CDATA[".$RESOLVE[value][$i]."]]>";
		}	
	}

	$options = array_to_xml($param, "OPTIONS");

	?>
	<script>
		opener.document.formulaire.<?php  echo $name; ?>_options.value="<?php  echo str_replace("\n", "\\n", addslashes($options));?> ";
		opener.document.formulaire.<?php  echo $name; ?>_for.value="resolve";
		self.close();
	</script>
	<?php
}else{
	if ($first == 2){
		$param["FOR"] = "resolve";
		$param[SIZE][0][value] = stripslashes($SIZE*1);
		$param[REPEATABLE][0][value] = $REPEATABLE ? 1 : 0;
		$param[RESOLVE]= array();
		for($i=0; $i<count($RESOLVE[id]);$i++){
			if(count($checked)==0 || (count($checked)>0 && !in_array($RESOLVE[id][$i],$checked))){
				if($RESOLVE[id][$i] && $RESOLVE[label][$i] && $RESOLVE[value][$i]){
					$array= array(
						id => $RESOLVE[id][$i],
						label => $RESOLVE[label][$i],
						value =>"<![CDATA[".$RESOLVE[value][$i]."]]>"
					);
					$param[RESOLVE][]=$array;
				}	
			}
		}
		
		$options = array_to_xml($param, "OPTIONS");
	}
	?> 
	<h3><?php  echo $msg[procs_options_param].$name;?> </h3>
	<hr />
	
	<?php
	if (!$options) $options = "
	<OPTIONS></OPTIONS>";
	 $param = _parser_text_no_function_("<?xml version='1.0' encoding='".$charset."'?>\n".$options, "OPTIONS");
	if ($param["FOR"] != "resolve") {
		$param = array();
		$param["FOR"] = "resolve";
	}
	//Formulaire

	?> 
	<form class='form-<?php echo $current_module ?>' name="formulaire" action="options_resolve.php" method="post">
		<h3><?php  echo $type_list_empr[$type];?> </h3>
		<div class='form-contenu'>
			<input type="hidden" name="first" value="1">
			<input type="hidden" name="idchamp" value="<?php echo $idchamp; ?>">
			<input type="hidden" name="_custom_prefixe_" value="<?php echo $_custom_prefixe_;?>">
			<input type="hidden" name="dtype" $param[RESOLVE][$i][VALUE],value="<?php echo $dtype;?>">
			<input type="hidden" name="name" value="<?php  echo htmlentities($name,ENT_QUOTES,$charset);?>">
			<table class='table-no-border' width=100%>
				<tr>
					<td><?php  echo $msg[procs_options_text_taille];?></td>
					<td><input class='saisie-10em' type="text" name="SIZE" value="<?php  echo htmlentities($param[SIZE][0][value],ENT_QUOTES,$charset);?>"></td>
				</tr>	
				<tr>
					<td><?php  echo $msg[persofield_textrepeat];?> </td>
					<td><input type="checkbox" name="REPEATABLE" <?php  echo $param[REPEATABLE][0][value] ? ' checked ' : "";?>></td>
				</tr>
				<tr>
					<td colspan="2">
						<?php 
						if($idchamp){
						?>
						<table>
							<tr>
								<td><?php echo $msg[procs_options_resolve_options];?></td>
							</tr>
							<tr>
								<td>
									<table border="1" id="resolve_table" style="text-align:center">
										<tr>
											<th></th>
											<th><?php echo $msg[procs_options_resolve_options_id];?></th>
											<th><?php echo $msg[procs_options_resolve_options_label];?></th>
											<th><?php echo $msg[procs_options_resolve_options_link];?></th>
										</tr>
								<?php
									$max = 0;
									for($i=0; $i<count($param[RESOLVE]);$i++){
										$requete="select count(".$_custom_prefixe_."_custom_$dtype) from ".$_custom_prefixe_."_custom_values where ".$_custom_prefixe_."_custom_champ=".$idchamp." and SUBSTRING_INDEX(".$_custom_prefixe_."_custom_$dtype,'|',-1) like '".$param[RESOLVE][$i][ID]."'";
										$res = mysql_query($requete);
										if(mysql_num_rows($res)) $nb = mysql_result($res,0,0);
										else $nb = 0;
										print "
										<tr>
											<td><input type='checkbox' name='checked[]' value='".htmlentities($param[RESOLVE][$i][ID],ENT_QUOTES,$charset)."' ".($nb > 0 ? "disabled=true": "")."/></td>
											<td><input type='text' name='RESOLVE[id][]' size='2' value='".htmlentities($param[RESOLVE][$i][ID],ENT_QUOTES,$charset)."' readonly='true'/></td>
											<td><input type='text' name='RESOLVE[label][]' size='10' value='".htmlentities($param[RESOLVE][$i][LABEL],ENT_QUOTES,$charset)."'/></td>
											<td><input type='text' name='RESOLVE[value][]' size='30' value='".htmlentities($param[RESOLVE][$i][value],ENT_QUOTES,$charset)."'/></td>
										</tr>";
									}
								?>			
									</table>
								</td>
							<tr>
						</table>
						<?php 
						}else{
							echo "<b>".$msg["parperso_options_list_before_rec"]."</b>"; 
						}?>
					</td>
				</tr>	
			</table>
		</div>
		<input class="bouton" type="submit" value="<?php echo $msg[ajouter]; ?>" onclick="add_entry();return false;">&nbsp;
		<input class="bouton" type="submit" value="<?php echo $msg[procs_options_suppr_options_coche]; ?>" onClick="this.form.first.value=2">&nbsp;
		<input class="bouton" type="submit" value="<?php  echo $msg[77];?>">
	</form>
	<script type="text/javascript">
		var tab = new Array();
		<?php
			for($i=0; $i<count($param[RESOLVE]);$i++){
				print "
		tab[$i] = ".$param[RESOLVE][$i][ID].";";
			}
		?>
		function getMaxId() {
			var max = 0;
			for(var i=0 ; i<tab.length; i++){
				if(tab[i]>max) max = tab[i];
			}
			return max;
		}
		function add_entry(){
			var new_id = getMaxId()+1;
			tab.push(new_id);
			var table = document.getElementById("resolve_table");
			var row = table.insertRow(table.rows.length);
			var cell = row.insertCell(row.cells.length);
			var check = document.createElement("input");
			check.setAttribute("type","checkbox");
			check.setAttribute("name","checked[]");
			check.setAttribute("value",new_id);		
			cell.appendChild(check);	
			var cell1 = row.insertCell(row.cells.length);
			var id = document.createElement("input");
			id.setAttribute("type","text");
			id.setAttribute("name","RESOLVE[id][]");
			id.setAttribute('readonly','true');
			id.setAttribute("size","2");
			id.setAttribute("value",new_id);
			cell1.appendChild(id);
			var cell2 = row.insertCell(row.cells.length);
			var label = document.createElement("input");
			label.setAttribute("type","text");
			label.setAttribute("name","RESOLVE[label][]");
			label.setAttribute("size","10");
			cell2.appendChild(label);
			var cell3 = row.insertCell(row.cells.length);	
			var link = document.createElement("input");
			link.setAttribute("type","text");
			link.setAttribute("name","RESOLVE[value][]");
			link.setAttribute("size","30");
			cell3.appendChild(link);
		}
	</script>
	<?php
	 }
?>
</body>
</html>