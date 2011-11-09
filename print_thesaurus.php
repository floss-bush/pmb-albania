<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: print_thesaurus.php,v 1.10.2.1 2011-09-06 09:11:23 jpermanne Exp $

$base_path = ".";
$base_auth = "AUTORITES_AUTH";
$base_title = $msg[print_thes_title];
$base_nobody=1;
$base_noheader=1;

require($base_path."/includes/init.inc.php");
@set_time_limit(0);

// constantes
			$color[1]="black";
			$color[2]="#c9e9ff"; // bleu
			$color[3]="#c6ffc5"; // vert
			$color[4]="#ffedc5"; // saumon
			$color[5]="#fcffc5"; // jaune
			$color[6]="#d7d8ff"; // violet
		
			$fontsize[1]=" font-size:1.2em; ";
			$fontsize[2]=" font-size:1.0em; ";
			$fontsize[3]=" font-size:0.9em; "; 
			$fontsize[4]=" font-size:0.8em; "; 
			$fontsize[5]=" font-size:0.8em; "; 
			$fontsize[6]=" font-size:0.8em; "; 
			$fontsize[7]=" font-size:0.8em; "; 
			$fontsize[8]=" font-size:0.8em; "; 
			$fontsize[9]=" font-size:0.8em; "; 

			$paddingmargin[0]=" padding-bottom: 10px; ";
			$paddingmargin[1]=" padding-bottom: 10px; ";
			$paddingmargin[2]=" padding-bottom: 8px; ";
			$paddingmargin[3]=" padding-bottom: 6px; ";
			$paddingmargin[4]=" ";
			$paddingmargin[5]=" ";
			$paddingmargin[6]=" ";
			$paddingmargin[7]=" ";
			$paddingmargin[8]=" ";
			$paddingmargin[9]=" ";


if ($action!="print") {
	print $std_header;
	print "<h3>".$msg[print_thes_title]."</h3>\n";
	print "<form name='print_options' action='print_thesaurus.php?action=print' method='post'>
		<b>".$msh[print_thes_options]."</b>
		<blockquote>".$msg[print_thes_list_type]."
			<select name='typeimpression'>";
	if ($id_noeud_origine) 	print "\n<option value='arbo' selected>".$msg[print_thes_arbo]."</option>
				<option value='alph' disabled>".$msg[print_thes_alph]."</option>
				<option value='rota' disabled>".$msg[print_thes_rota]."</option>";
	else print "\n<option value='arbo' selected>".$msg[print_thes_arbo]."</option>
				<option value='alph' >".$msg[print_thes_alph]."</option>
				<option value='rota' >".$msg[print_thes_rota]."</option>";
	
	print "\n</select>
		</blockquote>
		<blockquote>
			<input type='checkbox' name='aff_note_application' CHECKED value='1' />&nbsp;".$msg[print_thes_na]."<br />
			<input type='checkbox' name='aff_commentaire' CHECKED value='1' />&nbsp;".$msg[print_thes_comment]."<br />
			<input type='checkbox' name='aff_voir' CHECKED value='1'/>&nbsp;".$msg[print_thes_voir]."<br />
			<input type='checkbox' name='aff_voir_aussi' CHECKED value='1'/>&nbsp;".$msg[print_thes_ta]."<br />
			<input type='checkbox' name='aff_tg' CHECKED value='1'/>&nbsp;".$msg[print_thes_tg]."<br />
			<input type='checkbox' name='aff_ts' CHECKED value='1'/>&nbsp;".$msg[print_thes_ts]."
		</blockquote>
		<b>".$msg["print_output_title"]."</b>
		<blockquote>
			<input type='radio' name='output' value='printer' checked/>&nbsp;".$msg["print_output_printer"]."<br />
			<input type='radio' name='output' value='tt'/>&nbsp;".$msg["print_output_writer"]."
		</blockquote>
		<input type='hidden' name='aff_langue' value='fr_FR'>
		<input type='hidden' name='id_noeud_origine' value='$id_noeud_origine'>
		<input type='hidden' name='aff_num_thesaurus' value='";
	if ($aff_num_thesaurus>0) print $aff_num_thesaurus;
	else die( "> Error with # of thesaurus");
	print "'><center><input type='submit' value='".$msg["print_print"]."' class='bouton'/>&nbsp;<input type='button' value='".$msg["print_cancel"]."' class='bouton' onClick='self.close();'/></center>";
	print "</body></html>";
	}

$rqlang = "select langue_defaut from thesaurus where id_thesaurus=".$aff_num_thesaurus ;
$reslang = mysql_query($rqlang) or die("<br />Query 'langue_defaut' failed ".mysql_error()."<br />".$rqlang);
$objlang = mysql_fetch_object($reslang);
if ($objlang->langue_defaut) $aff_langue = $objlang->langue_defaut;
else $aff_langue ="fr_FR";

if ($action=="print") {
	if ($output=="tt") {
		header("Content-Type: application/word");
		header("Content-Disposition: attachement; filename=thesaurus.doc");
	}
	print "<html><body style='font-family : Arial, Helvetica, Verdana, sans-serif;'>";
	print "<h2>".$msg["print_thes_titre_".$typeimpression]."</h2>";
	switch($typeimpression) {
		case "arbo":
			$res.="<td width=10% bgcolor='".$color[$niveau-9]."'> </td>";
			
			if ($id_noeud_origine) {
				// un noeud était fourni pour n'imprimer que cette branche
				$id_noeud_top = $id_noeud_origine ;
			} else {
				$rqt_id_noeud_top = "select id_noeud from noeuds where autorite='TOP' and num_thesaurus=".$aff_num_thesaurus ;
				$result_rqt_id_noeud_top = mysql_query($rqt_id_noeud_top) or die("Query 'TOP' failed");
				$obj_id_noeud_top = mysql_fetch_object($result_rqt_id_noeud_top);
				$id_noeud_top = $obj_id_noeud_top->id_noeud;
			}
			
			// premier parcours pour calculer la profondeur du thésaurus : $profondeurmax
			$niveau=0;
			$resultat="";
			$profondeurmax=0;
			enfants($id_noeud_top, $niveau, $resultat, $profondeurmax, false);
			/// deuxième parcours, cette fois-ci on imprime
			$niveau=0;
			$resultat="";
			echo "<table width=100% cellspacing=0 cellpadding=3>";
			enfants($id_noeud_top, $niveau, $resultat, $profondeurmax, true);
			echo "</table>" ;
			break;
		case "alph":
			$rqt = "select id_noeud from noeuds n, categories c where c.num_thesaurus=$aff_num_thesaurus and n.num_thesaurus=$aff_num_thesaurus and id_noeud=num_noeud and langue='$aff_langue' and autorite!='TOP' and autorite!='ORPHELINS' and autorite!='NONCLASSES' order by libelle_categorie ";
			$result = mysql_query($rqt) or die("Query alpha failed");
			while ($obj_id_noeud = mysql_fetch_object($result)){
				echo infos_categorie($obj_id_noeud->id_noeud);
			}
			break;
		case "rota":
			$mots=array();
			if (file_exists("$include_path/marc_tables/$aff_langue/empty_words_thesaurus")) {
				$mots_vides_thesaurus=true;
				include("$include_path/marc_tables/$aff_langue/empty_words_thesaurus");
			} else $mots_vides_thesaurus=false;  
			$rqt = "select id_noeud, libelle_categorie, index_categorie from noeuds n, categories c where c.num_thesaurus=$aff_num_thesaurus and n.num_thesaurus=$aff_num_thesaurus and id_noeud=num_noeud and langue='$aff_langue' and autorite!='TOP' and autorite!='ORPHELINS' and autorite!='NONCLASSES' order by libelle_categorie ";
			$result = mysql_query($rqt) or die("Query rota failed");
			while ($obj = mysql_fetch_object($result)) {
				// récupération de l'index du libellé, nettoyage
				$icat=$obj->index_categorie ;
				// si mots vides supplémentaires
				if ($mots_vides_thesaurus) {
					// suppression des mots vides
					if (is_array($empty_word_thesaurus)) {
						foreach($empty_word_thesaurus as $dummykey=>$word) {
							$word = convert_diacrit($word);
							$icat = pmb_preg_replace("/^${word}$|^${word}\s|\s${word}\s|\s${word}\$/i", ' ', $icat);
						}
					}
				}
				$icat = trim($icat);
				// echo "<br />".$obj->id_noeud." - ".$icat ;
				$icat = pmb_preg_replace('/\s+/', ' ', $icat);

				// l'index est propre, on va pouvoir exploser sur espace.
				$mot=array();
				// index non vide (des fois que le ménage précédent l'aie vidé complètement)
				if ($icat) {
					$mot = explode(' ',$icat);
					for ($imot=0;$imot<count($mot);$imot++) {
						if ($mot[$imot]) {
							$mots[$mot[$imot]][]=$obj->id_noeud ;
						}
					}
				}
			}
			// on a un super tableau de mots
			ksort($mots, SORT_STRING);
			echo "<table>";
			foreach ($mots as $mot=>$idiz) {
				// on parcourt tous les mots trouvés
				$rqt="select libelle_categorie, num_noeud from categories where num_noeud in(".implode(",",$idiz).") and langue='".$aff_langue."' order by index_categorie";
				$ressql = mysql_query($rqt) or die ($rqt."<br /><br />".mysql_error());
				while ($data=mysql_fetch_object($ressql)) {
					// on parcourt toutes les catégories utilisant ce mot pour chercher la position d'utilisation du mot
					$catnette = " ".str_replace(" - ","   ",strtolower(strip_empty_chars_thesaurus($data->libelle_categorie)))." ";
					$catnette = str_replace(" -","  ",$catnette);
					$catnette = str_replace("- ","  ",$catnette);
					$posdeb=strpos($catnette," ".$mot." ");
					$posfin=$posdeb+strlen($mot);
					// echo "<br /><br />deb $posdeb - fin: $posfin mot: $mot LIB: ".$data->libelle_categorie ;
					echo "
						<tr>
							<td align=right valign=top>".substr($data->libelle_categorie,0,$posdeb)."</td>
							<td align=left valign=top><b>".substr($data->libelle_categorie,$posdeb,$posfin-$posdeb)."</b>".substr($data->libelle_categorie,$posfin);
					echo infos_categorie($data->num_noeud, false, true)."</td></tr>";
				}
			}
			// print_r($mots);
			echo "</table>";
			break;
	}
	// pied de page
	print "</body></html>";

	}

mysql_close($dbh);

function infos_noeud($idnoeud, $niveau, $profondeurmax) {

	global $dbh, $aff_langue;
	global $aff_note_application, $aff_commentaire, $aff_voir, $aff_voir_aussi, $aff_tg, $aff_ts;
	global $color, $fontsize, $paddingmargin ;
	global $id_noeud_origine;
	
	// récupération info du noeud
	$rqt = "select num_noeud, libelle_categorie, num_parent, note_application, comment_public, case when langue='$aff_langue' then '' else langue end as trad, langue from categories,noeuds where num_noeud = id_noeud and num_noeud='$idnoeud' order by trad ";
	$ressql = mysql_query($rqt) or die ($rqt."<br /><br />".mysql_error());
	while ($data=mysql_fetch_object($ressql)) {
		$res.= "\n<tr>";
		$niv=$niveau-1;
		switch($niv) {
			case 10:
				$res.="<td width=10% bgcolor='".$color[$niveau-9]."'> </td>";
			case 9:
				$res.="<td width=10% bgcolor='".$color[$niveau-8]."'> </td>";
			case 8:
				$res.="<td width=10% bgcolor='".$color[$niveau-7]."'> </td>";
			case 7:
				$res.="<td width=10% bgcolor='".$color[$niveau-6]."'> </td>";
			case 6:
				$res.="<td width=10% bgcolor='".$color[$niveau-5]."'> </td>";
			case 5:
				$res.="<td width=10% bgcolor='".$color[$niveau-4]."'> </td>";
			case 4:
				$res.="<td width=10% bgcolor='".$color[$niveau-3]."'> </td>";
			case 3:
				$res.="<td width=10% bgcolor='".$color[$niveau-2]."'> </td>";
			case 2:
				$res.="<td width=10% bgcolor='".$color[$niveau-1]."'> </td>";
			case 1:
				$res.="<td width=10% bgcolor='".$color[$niveau]."'> </td>";
		}

		$printingBranche = false;
		// afin d'avoir les bons colspan sur la branche en cas d'impression d'une branche
		if ($id_noeud_origine==$idnoeud){
			$niveau=$niveau+1 ;
			$printingBranche = true;
		} 

		if (($data->note_application || $data->comment_public) && ($aff_note_application || $aff_commentaire)) {
			$style="style='border-top: 1px dotted gray;border-bottom: 1px dotted gray; ";
			$largeur="40%";
		} else {
			$style="style='";
			$largeur="70%";
		}
		$style.=" ".$fontsize[$niveau]." ".$paddingmargin[$niveau]." '";
		if ($data->trad) $res.="<td colspan='".($profondeurmax-($niveau-1))."' width=$largeur valign=top $style><font color='blue'>".$data->trad."</font> ".$data->libelle_categorie."";
		else $res.="<td colspan='".($profondeurmax-($niveau-1))."' width=$largeur valign=top $style>".$data->libelle_categorie;

		//TERME GÉNÉRAL DANS LE CAS DE L'IMPRESSION D'UNE BRANCHE
		if ($printingBranche){
			$rqttg = "select libelle_categorie from categories where num_noeud = '".$data->num_parent."'";
			$restg = mysql_query($rqttg) or die ($rqttg."<br /><br />".mysql_error());
			if (mysql_num_rows($restg)) {
				$datatg=mysql_fetch_object($restg);
				$res.= "<br /><font color='blue'>TG ".$datatg->libelle_categorie."</font>";
			}		
		} 
			
		if ($aff_voir_aussi) {
			$rqtva = "select libelle_categorie from categories, voir_aussi where num_noeud_orig=$idnoeud and num_noeud=num_noeud_dest and categories.langue='".$data->langue."' and voir_aussi.langue='".$data->langue."' order by libelle_categorie " ;
			$resva = mysql_query($rqtva) or die ($rqtva."<br /><br />".mysql_error());
			if (mysql_num_rows($resva)) {
				$res.= "\n<font color='green'>";
				while ($datava=mysql_fetch_object($resva)) $res.= "<br />TA ".$datava->libelle_categorie;
				$res.= "</font>";
			}
			
		}
		if ($aff_voir) {
			$rqtva = "select libelle_categorie from categories, noeuds where num_renvoi_voir=$idnoeud and num_noeud=id_noeud and categories.langue='".$data->langue."' order by libelle_categorie " ;
			$resva = mysql_query($rqtva) or die ($rqtva."<br /><br />".mysql_error());
			if (mysql_num_rows($resva)) {
				$res.= "\n<font size=-1>";
				while ($datava=mysql_fetch_object($resva)) $res.= "<br />EP <i>".$datava->libelle_categorie."</i>";
				$res.= "</font>";
			}
		}
		$res.="</td>";
		if ($aff_note_application && $data->note_application) $res.="<td width=30% valign=top $style><font color=#ff706d>".$data->note_application."</font></td>";
		if ($aff_commentaire && $data->comment_public) $res.="<td width=30% valign=top $style><font color=black>".$data->comment_public."</font></td>";
		$res.="\n</tr>";
	}
	return $res ;
}

function infos_categorie($idnoeud, $printcategnoeud=true, $forcer_em=false) {

	global $dbh, $aff_langue;
	global $aff_note_application, $aff_commentaire, $aff_voir, $aff_voir_aussi, $aff_tg, $aff_ts;
	
	// récupération info du noeud
	$rqt = "select num_noeud, num_parent, libelle_categorie, note_application, comment_public, case when langue='$aff_langue' then '' else langue end as trad, langue from categories join noeuds on num_noeud=id_noeud where num_noeud='$idnoeud' order by trad ";
	$ressql = mysql_query($rqt) or die ($rqt."<br /><br />".mysql_error());
	while ($data=mysql_fetch_object($ressql)) {

		if ($data->trad) $res.="<br /><font color=blue>".$data->trad."</font> ".$data->libelle_categorie."";
		elseif ($printcategnoeud) $res.="<br /><br /><b>".$data->libelle_categorie."</b>";

		// EP et EM
		if ($aff_voir) {
			$rqtva = "select libelle_categorie from categories, noeuds where num_renvoi_voir=$idnoeud and num_noeud=id_noeud and categories.langue='".$data->langue."' order by libelle_categorie " ;
			$resva = mysql_query($rqtva) or die ($rqtva."<br /><br />".mysql_error());
			if (mysql_num_rows($resva)) {
				$res.= "\n<font size=-1>";
				while ($datava=mysql_fetch_object($resva)) $res.= "<br />EP <i>".$datava->libelle_categorie."</i>";
				$res.= "</font>";
			}
		}
		if ($aff_voir || $forcer_em) {
			$rqtva = "select libelle_categorie from categories, noeuds where id_noeud=$idnoeud and num_noeud=num_renvoi_voir and categories.langue='".$data->langue."' order by libelle_categorie " ;
			$resva = mysql_query($rqtva) or die ($rqtva."<br /><br />".mysql_error());
			if (mysql_num_rows($resva)) {
				$res.= "\n<font size=-1>";
				while ($datava=mysql_fetch_object($resva)) $res.= "<br />EM <i>".$datava->libelle_categorie."</i>";
				$res.= "</font>";
			}
		}

		// TG
		if ($aff_tg) {
			$rqttg = "select libelle_categorie from categories join noeuds on num_noeud=id_noeud where num_noeud='$data->num_parent' and libelle_categorie not like '~%' and categories.langue='".$data->langue."' " ;
			$restg = mysql_query($rqttg) or die ($rqttg."<br /><br />".mysql_error());
			if (mysql_num_rows($restg)) {
					$res.= "\n<font color=black>";
					while ($datatg=mysql_fetch_object($restg)) $res.= "<br />TG ".$datatg->libelle_categorie;
					$res.= "</font>";
				}
		}
		
		// TS
		if ($aff_ts) {
			$rqtts = "select libelle_categorie from categories join noeuds on num_noeud=id_noeud where num_parent='$data->num_noeud' and libelle_categorie not like '~%' and categories.langue='".$data->langue."' " ;
			$rests = mysql_query($rqtts) or die ($rqttg."<br /><br />".mysql_error());
			if (mysql_num_rows($rests)) {
					$res.= "\n<font color=black>";
					while ($datats=mysql_fetch_object($rests)) $res.= "<br />TS ".$datats->libelle_categorie;
					$res.= "</font>";
				}
		}		
		// TA
		if ($aff_voir_aussi) {
			$rqtva = "select libelle_categorie from categories, voir_aussi where num_noeud_orig=$idnoeud and num_noeud=num_noeud_dest and categories.langue='".$data->langue."' and voir_aussi.langue='".$data->langue."' order by libelle_categorie " ;
			$resva = mysql_query($rqtva) or die ($rqtva."<br /><br />".mysql_error());
			if (mysql_num_rows($resva)) {
				$res.= "\n<font color=green>";
				while ($datava=mysql_fetch_object($resva)) $res.= "<br />TA ".$datava->libelle_categorie;
				$res.= "</font>";
			}
			
		}
		
		if ($aff_note_application && $data->note_application) $res.="<br /><font color=#ff706d>NA ".$data->note_application."</font>";
		if ($aff_commentaire && $data->comment_public) $res.="<br /><font color=black>PU ".$data->comment_public."</font>";
	}
	return $res ;
}

function enfants($id, $niveau, &$resultat, &$profondeurmax, $imprimer=false) {

	global $dbh, $aff_langue;

	if ($imprimer) {
		$resultat=infos_noeud($id, $niveau, $profondeurmax) ;
		echo $resultat;
		flush();
	} elseif ($niveau>$profondeurmax) $profondeurmax=$niveau; 
	
	// chercher les enfants
	$rqt = "select id_noeud from noeuds, categories where num_parent='$id' and id_noeud=num_noeud and langue='$aff_langue' and autorite!='TOP' and autorite!='ORPHELINS' and autorite!='NONCLASSES' order by libelle_categorie ";
	$res = mysql_query($rqt) ;
	if (mysql_num_rows($res)) {
		$niveau++;
		while ($data=mysql_fetch_object($res)) {
			enfants($data->id_noeud, $niveau, $resultat, $profondeurmax, $imprimer);
		}
	}
}

function strip_empty_chars_thesaurus($string) {
	// traitement des diacritiques
	$string = convert_diacrit($string);

	// Mis en commentaire : qu'en est-il des caractères non latins ???
	// SUPPRIME DU COMMENTAIRE : ER : 12/05/2004 : ça fait tout merder...
	// RECH_14 : Attention : ici suppression des éventuels "
	//          les " ne sont plus supprimés 
	$string = stripslashes($string) ;
	$string = pmb_alphabetic('^a-z0-9\s', ' ',pmb_strtolower($string));
	
	// espaces en début et fin
	$string = pmb_preg_replace('/^\s+|\s+$/', '', $string);
	
	return $string;
}



?>