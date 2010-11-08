<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: z3950_func.inc.php,v 1.16 2008-11-24 15:23:32 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

function z_gen_combo_box ( $selected , $nom ) {
	global $msg;
	$requete="select attr_libelle from z_attr group by attr_libelle order by attr_libelle ";
	$champ_code="attr_libelle";
	$champ_info="attr_libelle";
	$on_change="";
	$liste_vide_code="0";
	$liste_vide_info=$msg['catalog_Z3950_aucun_element'];
	$option_premier_code="";
	$option_premier_info="";
	$gen_liste_str="";
	$resultat_liste=mysql_query($requete);
	$gen_liste_str = "<select name=\"$nom\" onChange=\"$on_change\">\n" ;
	$nb_liste=mysql_numrows($resultat_liste);
	if ($nb_liste==0) {
		$gen_liste_str.="<option value=\"$liste_vide_code\">$liste_vide_info</option>\n" ;
		} else {
			if ($option_premier_info!="") {	
				$gen_liste_str.="<option value=\"".$option_premier_code."\" ";
				if ($selected==$option_premier_code) $gen_liste_str.="selected" ;
				$gen_liste_str.=">".$option_premier_info."\n";
				}
			$i=0;
			while ($i<$nb_liste) {
				$gen_liste_str.="<option value=\"".mysql_result($resultat_liste,$i,$champ_code)."\" " ;
				if ($selected==mysql_result($resultat_liste,$i,$champ_code)) {
					$gen_liste_str.="selected" ;
					}
				$gen_liste_str.=">".$msg["z3950_".mysql_result($resultat_liste,$i,$champ_info)]."</option>\n" ;
				$i++;
				}
			}
	$gen_liste_str.="</select>\n" ;
	return $gen_liste_str ;
	} /* fin gen_combo_box */

function zshow_isbd($isbd, $lien) {
	global $msg;
	
	$retour="
	<div class='row'>
		<b>$lien</b>
		</div>
	<div class='row'>
		$isbd
		</div>
	";
	return $retour;
}

function showButRes(){
	print "
		<script type='text/javascript'><!--
		afficherFenetre();		
		function afficherFenetre() {
		    if(top.framedepartz3950.droite_but.document.getElementById('visible2')){
				top.framedepartz3950.droite_but.document.getElementById('visible2').style.visibility=\"visible\";
			}else {
				setTimeout(afficherFenetre,500);
		    }
		}
		-->
		</script>";
		flush();
}

function hideJoke(){
	print "
		<script type='text/javascript'>
		<!--
		afficherFenetreJoke();
		function afficherFenetreJoke() {
			if(top.framedepartz3950.droite.document.getElementById('joke')){
				top.framedepartz3950.droite.document.getElementById('joke').style.visibility=\"hidden\";
			}
			else {
				setTimeout(afficherFenetreJoke,500);
			}		
		}
		-->
		</script>";
		flush();
}

function affiche_jsscript ($texte, $couleur, $ID_bib) {
	print ("<script type='text/javascript'>
		<!--\n");
	if ($texte != "") {
		print ("
		afficherFenetreHaut();
		function afficherFenetreHaut() {
			if(top.framedepartz3950.droite.document.getElementById(\"z$ID_bib\")){
			    top.framedepartz3950.droite.document.getElementById(\"z$ID_bib\").firstChild.nodeValue=\"$texte\";\n
			} else {
				setTimeout(afficherFenetreHaut,500);	
			}
		}");
	}
	if ($couleur != "") {
		print ("
		afficherFenHautCouleur();
		function afficherFenHautCouleur() {
			if(top.framedepartz3950.droite.document.getElementById(\"z$ID_bib\")){
				top.framedepartz3950.droite.document.getElementById(\"z$ID_bib\").style.backgroundColor=\"$couleur\";\n
			}else {
				setTimeout(afficherFenHautCouleur,500);
			}
		}");
	}
	print ("//-->
		</script>\n");
	flush ();
	}

function create_expl($f_ex_cb, $id, $f_ex_typdoc, $f_ex_cote, $f_ex_section, $f_ex_statut, $f_ex_location, $f_ex_cstat, $f_ex_note, $f_ex_prix, $f_ex_owner,$f_ex_comment='' ) {
	global $dbh;
	
	$new_expl = 0;
	$expl_retour = 0;
	$requete = "SELECT expl_id FROM exemplaires WHERE expl_cb='$f_ex_cb' ";
	$res = mysql_query($requete, $dbh);
	$nbr_lignes = @mysql_num_rows($res);
	if ($nbr_lignes) {
		$valid_requete = 0 ;
		$lu=mysql_fetch_array($res);
		$expl_retour = $lu['expl_id'];
		} else {
			$valid_requete = 1;
			}
	if($valid_requete) {
		$requete = 'INSERT INTO exemplaires SET create_date=sysdate(), ';
		$requete .= "expl_cb='${f_ex_cb}'";
		$requete .= ", expl_notice=${id}";
		$requete .= ", expl_typdoc=${f_ex_typdoc}";
		$requete .= ", expl_cote='${f_ex_cote}'";
		$requete .= ", expl_section=${f_ex_section}";
		$requete .= ", expl_statut=${f_ex_statut}";
		$requete .= ", expl_location=${f_ex_location}";
		$requete .= ", expl_codestat=${f_ex_cstat}";
		$requete .= ", expl_note='".${f_ex_note}."'";
		$requete .= ", expl_comment='".${f_ex_comment}."'";
		$requete .= ", expl_prix='${f_ex_prix}'";
		$requete .= ", expl_owner='${f_ex_owner}'";
		$result = mysql_query($requete, $dbh);
		$expl_retour = mysql_insert_id();
		audit::insert_creation(AUDIT_EXPL,$expl_retour) ;
		$new_expl=1;
		}
	$retour = array($new_expl,$expl_retour);
	return $retour;
	}
