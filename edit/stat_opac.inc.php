<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: stat_opac.inc.php,v 1.5 2009-10-12 14:34:17 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

include("$class_path/parameters.class.php");

function show_stats($dbh) {
	
	global $msg;
 	global $charset;
 	global $PMBuserid, $javascript_path;
	
 	print "
		<script type=\"text/javascript\" src=\"".$javascript_path."/tablist.js\"></script>
		<a href=\"javascript:expandAll()\"><img src='./images/expand_all.gif' border='0' id=\"expandall\"></a>
		<a href=\"javascript:collapseAll()\"><img src='./images/collapse_all.gif' border='0' id=\"collapseall\"></a>
		";
	
 	$requete_vue = "select * from statopac_vues";
 	$res = mysql_query($requete_vue,$dbh);
 	$vue_affichage="";	
	if(mysql_num_rows($res) == 0){
		$vue_affichage="<br>".$msg["stat_no_view_created"]."<br>";
		return $vue_affichage;
	} else {		
		$vue_affichage="";
		$parity=1;
		while(($vue = mysql_fetch_object($res))){			
			$rqt="select * from statopac_request where num_vue='".addslashes($vue->id_vue)."'";
			$result = mysql_query($rqt);
			$liste_requete ="";
			while(($request = mysql_fetch_object($result))){
				if ($parity % 2) {
				$pair_impair = "even";
				} else {
					$pair_impair = "odd";
				}
				$parity++;
				$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./edit.php?categ=stat_opac&sub=&action=execute&id_proc=$request->idproc';\" ";
				$liste_requete.="\n<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>
						<td><strong>$request->name</strong><br>
							<small>$request->comment</small></td>
					</tr>";	
			}
			if($liste_requete){
				$tab_list="<table><tr><th colspan=4>".stripslashes(htmlentities($vue->nom_vue,ENT_QUOTES))."</th></tr>".$liste_requete."</table>";
				$vue_affichage .= "<div id='vue".$vue->id_vue."Parent' class='notice-parent'>";
				$lien = stripslashes(htmlentities($vue->nom_vue,ENT_QUOTES));
				$space = "<small><span style='margin-right: 3px;'><img src='./images/spacer.gif' width='10' height='10' /></span></small>";
				$vue_affichage .= "<img id='vue".$vue->id_vue."Img' class='img_plus' hspace='3' border='0' onClick=\"expandBase('vue".$vue->id_vue."',true);return false;\" title='requete' name='imEx' src=\"./images/plus.gif\" >";
				$vue_affichage .= "$space<span class='notice-heada'>$lien</span>";
				$vue_affichage .= "</div>";
				$vue_affichage .= "<div id='vue".$vue->id_vue."Child' class='notice-child' style='margin-bottom: 6px; display: none;'>$tab_list</div>";
			}				
		}
 	}
 	
	return $vue_affichage;
}

function show_results_stats($id_proc=0){

	global $msg, $dbh, $form_type, $categ, $numero_page,$limite_page, $sub;
	@set_time_limit ($pmb_set_time_limit);
	//Récupération des variables postées, on en aura besoin pour les liens
	$page=$_SERVER[SCRIPT_NAME];
	$requete = "SELECT idproc, name, requete, comment, num_vue FROM statopac_request where idproc='".$id_proc."' ";
	$res = mysql_query($requete, $dbh);
	$row=mysql_fetch_row($res);
	
	//Requete et calcul du nombre de pages à afficher selon la taille de la base 'pret'
	//********************************************************************************/
	
	// récupérer ici la procédure à lancer
	$sql = $row[2];
	$sql = str_replace("VUE()","statopac_vue_$row[4]",$sql);
	if (preg_match_all("|!!(.*)!!|U",$sql,$query_parameters) && $form_type=="") {
		$hp=new parameters($id_proc,"statopac_request");
		$hp->gen_form("edit.php?categ=stat_opac&sub=&action=execute&id_proc=$id_proc");
	} else {
			
		if (preg_match_all("|!!(.*)!!|U",$sql,$query_parameters)) {
			$hp=new parameters($id_proc,"statopac_request");
			$hp->get_final_query();
			$sql=$hp->final_query;
			$isparameters=1;
		}
		$sql = str_replace("VUE()","statopac_vue_$row[4]",$sql);
		// la procédure n'a pas de parm ou les paramètres ont été reçus
		if (!explain_requete($sql)) die("<br/><br/>".$sql."<br/><br/>".$msg["proc_param_explain_failed"]."<br><br>".$erreur_explain_rqt); 
		$req_nombre_lignes = mysql_query($sql, $dbh);
		$nombre_lignes = mysql_numrows($req_nombre_lignes);
		
		//Si aucune limite_page n'a été passée, valeur par défaut : 10
		if (!$limite_page) $limite_page = 10;
		$nbpages= $nombre_lignes / $limite_page; 
		
		// on arondi le nombre de page pour ne pas avoir de virgules, ici au chiffre supérieur 
		$nbpages_arrondi = ceil($nbpages); 
		
		// on enlève 1 au nombre de pages, car la 1ere page affichée ne fait pas partie des pages suivantes
		$nbpages_arrondi = $nbpages_arrondi - 1; 
		
		if (!$numero_page) $numero_page=0;
		
		$limite_mysql = $limite_page * $numero_page; 
		
		//REINITIALISATION DE LA REQUETE SQL
		switch($dest) {
			case "TABLEAU":
			case "TABLEAUHTML":
			case "TABLEAUCSV":
				break;
			default:
				echo "<h1>$row[1]</h1><h2>$row[3]</h2>";
				$sql = $sql." LIMIT ".$limite_mysql.", ".$limite_page; 
				echo "<p>";	
				break;
		}
		
		// on compte tout et on avise 
		if (!explain_requete($sql)) die("<br/><br/>".$sql."<br/><br/>".$msg["proc_param_explain_failed"]); 
		$res = @mysql_query($sql, $dbh) or die($sql."<br/><br/>".mysql_error());
		$nbr_lignes = @mysql_num_rows($res);
		$nbr_champs = @mysql_num_fields($res);

		if ($nbr_lignes) {
			switch($dest) {
				case "TABLEAU":
					$worksheet->write(0,0,$row[1]);
					$worksheet->write(0,1,$row[3]);
					for($i=0; $i < $nbr_champs; $i++) {
						// entête de colonnes
						$fieldname = mysql_field_name($res, $i);
						$worksheet->write(1,$i,${fieldname});
					}
              		        		
					for($i=0; $i < $nbr_lignes; $i++) {
						$row = mysql_fetch_row($res);
						$j=0;
						foreach($row as $dummykey=>$col) {
							if(trim($col)=='') $col=" ";
							$worksheet->write(($i+2),$j,$col);
							$j++;
						}
					}
					
					$workbook->close();
					header("Content-Type: application/x-msexcel; name=\""."Procedure_$id_proc".".xls"."\"");
					header("Content-Disposition: inline; filename=\""."Procedure_$id_proc".".xls"."\"");
					$fh=fopen($fname, "rb");
					fpassthru($fh);
					unlink($fname);
					break;
				case "TABLEAUHTML":
					echo "<h1>$row[1]</h1><h2>$row[3]</h2>$sql<br/>";						
					echo "<table>";
					for($i=0; $i < $nbr_champs; $i++) {
						$fieldname = mysql_field_name($res, $i);
						print("<th align='left'>${fieldname}</th>");
					}
       		        for($i=0; $i < $nbr_lignes; $i++) {
						$row = mysql_fetch_row($res);
						echo "<tr>";
						foreach($row as $dummykey=>$col) {
							if (is_numeric($col)){
								$col = "'".$col ;
							}
							if(trim($col)=='') $col="&nbsp;";
							print '<td>'.$col.'</td>';
						}
						echo "</tr>";
					}
					echo "</table>";
					break;
				case "TABLEAUCSV":
					for($i=0; $i < $nbr_champs; $i++) {
						$fieldname = mysql_field_name($res, $i);
						print("${fieldname}\t");
						}
					for($i=0; $i < $nbr_lignes; $i++) {
						$row = mysql_fetch_row($res);
						echo "\n";
						foreach($row as $dummykey=>$col) {
							/* if (is_numeric($col)) {
								$col = "\"'".(string)$col."\"" ;
							} */
							print "$col\t";
						}
					}
					break;
				default:
					echo "<table>";
					for($i=0; $i < $nbr_champs; $i++) {
						$fieldname = mysql_field_name($res, $i);
						print("<th align='left'>${fieldname}</th>");
						}
       		                	$odd_even=0;
					for($i=0; $i < $nbr_lignes; $i++) {
						$row = mysql_fetch_row($res);
						if ($odd_even==0) {
							echo "	<tr class='odd'>";
							$odd_even=1;
						} elseif ($odd_even==1) {
							echo "	<tr class='even'>";
							$odd_even=0;
						}
						foreach($row as $dummykey=>$col) {
							if(trim($col)=='') $col="&nbsp;";
							print '<td>'.$col.'</td>';
						}
						echo "</tr>";
					}
					echo "</table><hr>";
		
					echo "<p align=left size='-3' class='pn-normal'>
					<form name='navbar' class='form-edit' action='$page' method='post'>";
					echo "
					<input type='hidden' name='numero_page'  value='$numero_page' />
					<input type='hidden' name='id_proc'  value='$id_proc' />
					<input type='hidden' name='form_type'  value='gen_form' />
					<input type='hidden' name='categ'  value='$categ' />
					<input type='hidden' name='sub' value='$sub' />";
					if ($isparameters) echo $hp->get_hidden_values();
					
					// LIENS PAGE SUIVANTE et PAGE PRECEDENTE
					// si le nombre de page n'est pas 0 et si la variable numero_page n'est pas définie
					// dans cette condition, la variable numero_page est incrémenté et est inférieure à $nombre 
					
					// constitution des liens
					$suivante = $numero_page+1;
					$precedente = $numero_page-1;
					// affichage du lien précédent si nécéssaire
					if ($precedente >= 0)
						$nav_bar .= "<img src='./images/left.gif' border='0' title='$msg[48]' alt='[$msg[48]]' hspace='3' align='bottom' onClick=\"document.navbar.dest.value='';document.navbar.numero_page.value='$precedente'; document.navbar.limite_page.value='$limite_page'; document.navbar.submit(); \"/>" ;
					for ($i = 0; $i <=$nbpages_arrondi; $i++) {
						if($i==$numero_page) $nav_bar .= "<strong>".($i+1)."/".($nbpages_arrondi+1)."</strong>";
					}
					if ($suivante<=$nbpages_arrondi) $nav_bar .= "<img src='./images/right.gif' border='0' title='$msg[49]' alt='[$msg[49]]' hspace='3' align='bottom' onClick=\"document.navbar.dest.value='';document.navbar.numero_page.value='$suivante'; document.navbar.limite_page.value='$limite_page'; document.navbar.submit(); \" />";
					echo $nav_bar ;

					echo "
					<input type='hidden' name='dest' value='' />
					$msg[edit_cbgen_mep_afficher] <input type='text' name='limite_page' value='$limite_page' class='saisie-5em' /> $msg[1905]
					<input type='submit' class='bouton' value='".$msg['actualiser']."' onclick=\"this.form.dest.value='';document.navbar.numero_page.value=0;\" /><font size='4'>&nbsp;&nbsp;&nbsp;&nbsp;</font>
					<input type='image' src='./images/tableur.gif' border='0' onClick=\"this.form.dest.value='TABLEAU';\" alt='Export tableau EXCEL' title='Export tableau EXCEL' /><font size='4'>&nbsp;&nbsp;&nbsp;&nbsp;</font>
					<input type='image' src='./images/tableur_html.gif' border='0' onClick=\"this.form.dest.value='TABLEAUHTML';\" alt='Export tableau HTML' title='Export tableau HTML' />
					</form></p>";
					break;
				}
			} else {
				echo $msg["etatperso_aucuneligne"];
			}
			
			mysql_free_result ($res); 
		}
	
}

if(!$id_proc){
	print show_stats($dbh);
} else {
	print show_results_stats($id_proc);
}
?>