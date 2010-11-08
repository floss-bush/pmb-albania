<?php
// +-------------------------------------------------+
// � 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: edition_func.inc.php,v 1.4 2009-05-16 11:11:53 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/parametres_perso.class.php");

// Affichage tabulaire du contenu d'un caddie
function afftab_empr_cart_objects ($idcaddie=0, $flag="" , $no_flag = "" ) {
global $msg;
global $dbh;
global $worksheet ;
global $myCart ;
global $dest ;
global $entete_bloc;
global $max_aut ;
global $max_perso;
global $res_compte3 ;

global $etat_table ; // permet de savoir si les tag table sont ouverts ou ferm�s

if (($flag=="") && ($no_flag=="")) {
	$no_flag = 1;
	$flag = 1;
	}

$requete = "SELECT empr_caddie_content.* FROM empr_caddie_content where empr_caddie_id='".$idcaddie."' ";
if ($flag && $no_flag ) $complement_clause = "";
if (!$flag && $no_flag ) $complement_clause = " and flag is null ";
if ($flag && !$no_flag ) $complement_clause = " and flag is not null ";
if (!$flag && !$no_flag ) return ;
$requete .= $complement_clause." order by object_id";

$liste=array();
$result = mysql_query($requete, $dbh) or die($requete."<br />".mysql_error($dbh));
if(mysql_num_rows($result)) {
	while ($temp = mysql_fetch_object($result)) 
		$liste[] = array('object_id' => $temp->object_id, 'flag' => $temp->flag ) ;
	} else return;

switch($dest) {
	case "TABLEAU":
		break;
	case "TABLEAUHTML":
	default:
		echo pmb_bidi("<h1>".$msg['panier_num']." $idcaddie / ".$myCart->name."</h1>");
		echo pmb_bidi($myCart->comment."<br />");
		
		break;
	}

	// boucle de parcours des exemplaires trouv�s
	$entete=0;
	while(list($cle, $empr) = each($liste)) {
		$rqt_tout = "select id_empr,empr_cb,empr_nom,empr_prenom,empr_adr1,empr_adr2,empr_cp,empr_ville,empr_pays,empr_mail,empr_tel1,empr_tel2,empr_prof,empr_year,";
		$rqt_tout .=" empr_categ.libelle as categ,";
		$rqt_tout .=" empr_codestat.libelle as code_stat,statut_libelle,location_libelle,type_abt_libelle,";
		$rqt_tout .=" empr_creation,empr_modif,empr_sexe,empr_login,empr_date_adhesion,empr_date_expiration,empr_msg,empr_lang,empr_ldap,last_loan_date,date_fin_blocage,total_loans";
		$rqt_tout .=" from empr left join type_abts on id_type_abt=type_abt, empr_categ, empr_codestat, empr_statut, docs_location";
		$rqt_tout .=" where id_empr='".$empr[object_id]."' and empr_categ=id_categ_empr and empr_codestat=idcode and empr_statut=idstatut and empr_location=idlocation";
		if (!$entete) {
			extrait_info_empr($rqt_tout, 1, $empr[flag]);
			$entete=1;
			} else extrait_info_empr($rqt_tout, 0, $empr[flag]);
		} // fin de liste
return;
}

function extrait_info_empr ($sql="", $entete=1, $flag="") {
	global $dbh ;
	global $dest ;
	global $worksheet ;
	global $entete_bloc;
	global $msg;
	
	global $debligne_excel;
	global $etat_table ; // permet de savoir si les tag table sont ouverts ou ferm�s
	
	global $max_aut ; // le nombre max de colonnes d'auteurs
	
	if (!$debligne_excel) $debligne_excel = 0 ;
	
	$res = @mysql_query($sql, $dbh);
	$nbr_lignes = @mysql_num_rows($res);
	$nbr_champs = @mysql_num_fields($res);
             		
	if ($nbr_lignes) {
		switch($dest) {
			case "TABLEAU":
				if ($entete) {
					$worksheet->write_string((1+$debligne_excel),0,$msg["caddie_mess_edition_".$entete_bloc]);
					$debligne_excel++ ;
					}
				for($i=0; $i < $nbr_champs; $i++) {
					// ent�te de colonnes
					$fieldname = mysql_field_name($res, $i);
					if ($entete) {
						$worksheet->write_string((1+$debligne_excel),0,$msg['caddie_action_marque']);
						$worksheet->write_string((1+$debligne_excel),($i+1),${fieldname});
						}
					}
				if ($entete) $debligne_excel++ ;
             		        		
				for($i=0; $i < $nbr_lignes; $i++) {
					$debligne_excel++;
					$row = mysql_fetch_row($res);
					if ($flag) $worksheet->write_string(($i+$debligne_excel),0,"X");
					$j=0;
					foreach($row as $dummykey=>$col) {
						if(!$col) $col=" ";
						$worksheet->write_string(($i+$debligne_excel),($j+1),$col);
						$j++;
						}
					}
				break;
			case "TABLEAUHTML":
				if ($entete) {
					if ($etat_table) echo "\n</table>";
					echo "<h3>".$msg["caddie_mess_edition_".$entete_bloc]."</h3>";
					echo "\n<table><th align='left'>".$msg['caddie_action_marque']."</th>";
					$etat_table = 1 ;
					}
				for($i=0; $i < $nbr_champs; $i++) {
					$fieldname = mysql_field_name($res, $i);
					if ($entete) print("<th align='left'>${fieldname}</th>");
					}
				for($i=0; $i < $nbr_lignes; $i++) {
					$row = mysql_fetch_row($res);
					echo "<tr>";
					if ($flag) print "<td>X</td>"; else print "<td>&nbsp;</td>";
					foreach($row as $dummykey=>$col) {
						if (is_numeric($col)){
 							$col = "'".$col ;
							}
						if(!$col) $col="&nbsp;";
						print pmb_bidi("<td>$col</td>");
						}
					echo "</tr>";
					}
				break;
			default:
				if ($entete) {
					if ($etat_table) echo "\n</table>";
					echo "<h3>".$msg["caddie_mess_edition_".$entete_bloc]."</h3>";
					echo "\n<table><th align='left'>".$msg['caddie_action_marque']."</th>";
					$etat_table = 1 ;
					}
				for($i=0; $i < $nbr_champs; $i++) {
					$fieldname = mysql_field_name($res, $i);
					if ($entete) print("<th align='left'>${fieldname}</th>");
					}
				$odd_even=0;
				for($i=0; $i < $nbr_lignes; $i++) {
					$row = mysql_fetch_row($res);
					if ($odd_even==0) {
						echo "	<tr class='odd'>";
						$odd_even=1;
						} else if ($odd_even==1) {
							echo "	<tr class='even'>";
							$odd_even=0;
							}
					if ($flag) print "<td>X</td>"; else print "<td>&nbsp;</td>";
					foreach($row as $dummykey=>$col) {
						if(!$col) $col="&nbsp;";
						print pmb_bidi("<td>$col</td>");
						}
					echo "</tr>";
					}
				break;
			} // fin switch
		} // fin if nbr_lignes
	} // fin fonction extrait_info

	
