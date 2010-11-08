<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: execute.inc.php,v 1.20 2010-04-16 08:06:37 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// include d'exécution d'une procédure

$is_external = isset($execute_external) && $execute_external;
if ($is_external) {
	$nbr_lignes = 1;
}
else {
	if ($PMBuserid!=1) 
		$where=" and (autorisations='$PMBuserid' or autorisations like '$PMBuserid %' or autorisations like '% $PMBuserid %' or autorisations like '% $PMBuserid') ";
	$requete = "SELECT * FROM caddie_procs WHERE idproc=$id $where ";
	$res = mysql_query($requete, $dbh);
	
	$nbr_lignes = mysql_num_rows($res);
	$urlbase = "./catalog.php?categ=caddie&sub=gestion&quoi=procs&action=final&id=$id";
}


if($nbr_lignes) {
	// récupération du résultat
	if ($is_external) {
		$idp = $id;
		$name = $execute_external_procedure->name;
		$code = $execute_external_procedure->sql;
		$commentaire = $execute_external_procedure->comment;
	}
	else {
		$row = mysql_fetch_row($res);
		$idp = $row[0];
		$name = $row[2];
		$commentaire = $row[4];
		if (!$code)
			$code = $row[3];
		$commentaire = $row[4];
	}
	
	if (!$is_external)
	print pmb_bidi("
		<h3>
			$msg[procs_execute] \" $name \" 
			<input type='button' class='bouton' value='$msg[62]'  onClick='document.location=\"./catalog.php?categ=caddie&sub=gestion&quoi=procs&action=modif&id=$id\"' />
			<input type='button' class='bouton' value='$msg[708]' onClick='document.location=\"./catalog.php?categ=caddie&sub=gestion&quoi=procs&action=execute&id=$id\"' />&nbsp;
			</h3>
		<br /><strong>$name</strong> : $commentaire<hr />");
	else 
		print "<br />
			<h3>".$msg["remote_procedures_executing"]." $name</h3>
			<br />$commentaire<hr />
				<input type='button' class='bouton' value='$msg[remote_procedures_back]' onClick='document.location=\"./catalog.php?categ=caddie&sub=gestion&quoi=remote_procs\"' />
				<input type='button' class='bouton' value='$msg[708]' onClick='document.location=\"./catalog.php?categ=caddie&sub=gestion&quoi=remote_procs&action=execute_remote&id=$id\"' />
				<input type='button' class='bouton' value='$msg[remote_procedures_import]' onClick='document.location=\"./catalog.php?categ=caddie&sub=gestion&quoi=remote_procs&action=import_remote&id=$id\"' />
			<br /><br />";
	
	$linetemp = explode(";", $code);
	for ($i=0;$i<count($linetemp);$i++) if (trim($linetemp[$i])) $line[]=trim($linetemp[$i]);
	while(list($cle, $valeur)= each($line)) {
		if($valeur) {
			// traitement tri des colonnes
			if ($sortfield != "") {
				// on cherche à trier sur le champ $trifield
				// compose la chaîne de tri
				$tri = $sortfield;
				if ($desc == 1) $tri .= " DESC";
					else $tri .= " ASC";
				// on enlève les doubles espaces dans la procédure
				$valeur = ereg_replace("/\s+/", " ", $valeur);
				// supprime un éventuel ; à la fin de la requête
				$valeur = ereg_replace("/;$/", "", $valeur);
				// on recherche la première occurence de ORDER BY
				$s = stristr($valeur, "order by");
				if ($s) {
					// y'a déjà une clause order by... moins facile...
					// il faut qu'on sache si on aura besoin de mettre une virgule ou pas
					if ( ereg(",", $s) ) {
						$virgule = true;
						} else if ( ! ereg("${sortfield}", $s)) {
							$virgule = true;
							} else {
								$virgule = false;
								}
					if ($virgule) {
						$tri .= ", ";
						}
					// regarde si le champ est déjà dans la liste des champs à trier et le remplace si besoin
					$new_s = preg_replace("/$sortfield, /", "", $s);
					$new_s = preg_replace("/$sortfield/", "", $new_s);
					// ajoute la clause order by correcte
					$new_s = preg_replace("/order\s+by\s+/i", "order by $tri", $new_s);
					// replace l'ancienne chaîne par la nouvelle
					$valeur = str_replace($s, $new_s, $valeur);
					} else {
						$valeur .= " order by $tri";
						}
				}

			print pmb_bidi("<strong>$msg[procs_ligne] $cle </strong>:&nbsp;$valeur<br /><br />");
			if ( (pmb_strtolower(pmb_substr($valeur,0,6))=="select") || (pmb_strtolower(pmb_substr($valeur,0,6))=="create") ) {
				} else {
					echo "rqt=".$valeur."=<br />" ;
					error_message_history("Requête invalide","Vous ne pouvez tester que des requêtes de sélection",1);
					exit();
				}
			if (!explain_requete($valeur)) die("<br /><br />".$valeur."<br /><br />".$msg["proc_param_explain_failed"]."<br /><br />".$erreur_explain_rqt);
			$res = @mysql_query($valeur, $dbh);
			$nbr_lignes = @mysql_num_rows($res);
			$nbr_champs = @mysql_num_fields($res);

			if($nbr_lignes) {
				echo "<table >";
				for($i=0; $i < $nbr_champs; $i++) {
					// ajout de liens pour trier les pages
					$fieldname = mysql_field_name($res, $i);
					$sortasc = "<a href='${urlbase}&sortfield=".($i+1)."&desc=0'>asc</a>";
					$sortdesc = "<a href='${urlbase}&sortfield=".($i+1)."&desc=1'>desc</a>";
					print("<th>${fieldname}</th>");
					}

				for($i=0; $i < $nbr_lignes; $i++) {
					$row = mysql_fetch_row($res);
					echo "<tr>";
					foreach($row as $dummykey=>$col) {
						if(!$col) $col="&nbsp;";
						print pmb_bidi("<td>$col</td>");
						}
					echo "</tr>";
					}
				echo "</table><hr />";
				} else {
					print "<br /><font color='#ff0000'>".$msg['admin_misc_lignes']." ".mysql_affected_rows($dbh);
					$err = mysql_error($dbh);
					if ($err) print "<br />$err";
					echo "</font><hr />";
				}
			}
		} // fin while

	} else {
		print $msg["proc_param_query_failed"];
		}
