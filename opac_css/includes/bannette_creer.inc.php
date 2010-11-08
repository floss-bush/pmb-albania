<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: bannette_creer.inc.php,v 1.17 2009-05-16 10:52:45 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if (!$opac_allow_bannette_priv) die ("Accès interdit") ; 

require_once($class_path."/search.class.php");
require_once($class_path."/bannette.class.php");
require_once($base_path."/includes/bannette_func.inc.php");

// afin de résoudre un pb d'effacement de la variable $id_empr par empr_included, bug à trouver
if (!$id_empr) $id_empr=$_SESSION["id_empr_session"] ;

print "<div id='aut_details'>\n";

print "<h3><span>".$msg['dsi_bt_bannette_priv']."</span></h3>\n";

if ($enregistrer==1 && !$nom_bannette) $enregistrer = 2 ;
if (!$enregistrer) $equation = search::serialize_search();
	else $equation = stripslashes($equation) ;

if ($equation) {
	// on arrive de la rech multi-critères
	$s = new search() ;
	$equ_human = $s->make_serialized_human_query($equation) ;
	if ($enregistrer=='1') {
		$qui = addslashes($empr_nom." ".$empr_prenom) ;

		$rqt_equation = "insert into equations (id_equation, num_classement, nom_equation, comment_equation, requete, proprio_equation) ";
		$rqt_equation.= "VALUES (0,0,'".addslashes($equ_human)."','$qui -> $nom_bannette','".addslashes($equation)."', $id_empr)" ;
		mysql_query($rqt_equation);
		$id_equation = mysql_insert_id() ;
		
		// paramétrage OPAC: choix du nom de la bibliothèque comme expéditeur
		$requete = "select location_libelle, email, adr1, cp, town from empr, docs_location where empr_location=idlocation and id_empr='$id_empr' ";
		$res = mysql_query($requete, $dbh);
		$loc=mysql_fetch_object($res) ;
	

		
		
		if (!$periodicite || $periodicite>200) $periodicite=15 ;
		$entete_email = "<SPAN style=\'FONT-SIZE: 11pt; FONT-FAMILY: Arial\'>".addslashes($msg['dsi_priv_mail_1'])."!!public!!</SPAN><br /><SPAN style=\'FONT-SIZE: 10pt; FONT-FAMILY: Arial\'>".addslashes($msg['dsi_priv_mail_2'])." «&nbsp;".addslashes($msg['empr_my_account'])."&nbsp;» > «&nbsp;".addslashes($msg['dsi_bannette_acceder'])."&nbsp;»&nbsp;:&nbsp; !!public!! - !!date!! </SPAN><br />" ;
		$entete_email .=addslashes($loc->location_libelle."<br />".$loc->adr1."<br />".$loc->cp." ".$loc->town)."<br />";
		$entete_email .=addslashes($msg['dsi_priv_mail_3'])."&nbsp;: <A href=\'mailto:".$loc->email."\'>".$loc->email."</A><br />";
		$entete_email .="<hr style=''border:none; border-bottom:solid #000000 3px;''/>!!equation!!" ;
		$date_last_envoi = "DATE_SUB(sysdate(), INTERVAL $periodicite DAY)" ;
		$rqt_bannette = "insert into bannettes " ;
		$rqt_bannette.= "set id_bannette=0, "; 
		$rqt_bannette.= "    num_classement=0, "; 
		$rqt_bannette.= "    nom_bannette='$qui > $nom_bannette', "; 
		$rqt_bannette.= "    comment_gestion='$qui > $nom_bannette', "; 
		$rqt_bannette.= "    comment_public='$nom_bannette', "; 
		$rqt_bannette.= "    entete_mail='$entete_email', "; 
		$rqt_bannette.= "    date_last_remplissage=$date_last_envoi, "; 
		$rqt_bannette.= "    date_last_envoi=$date_last_envoi, "; 
		$rqt_bannette.= "    proprio_bannette='$id_empr', "; 
		$rqt_bannette.= "    bannette_auto=1, "; 
		$rqt_bannette.= "    periodicite='$periodicite', "; 
		$rqt_bannette.= "    diffusion_email=1, "; 
		$rqt_bannette.= "    categorie_lecteurs=0, "; 
		$rqt_bannette.= "    nb_notices_diff=30, "; 
		$rqt_bannette.= "    typeexport='$typeexport', "; 
		$rqt_bannette.= "    update_type='C', "; 
		$rqt_bannette.= "    prefixe_fichier='$nom_bannette' "; 
		mysql_query($rqt_bannette);
		$id_bannette = mysql_insert_id() ;
		
		$rqt_bannette_equation = "INSERT INTO bannette_equation (num_bannette, num_equation) VALUES ($id_bannette, $id_equation)" ;
		mysql_query($rqt_bannette_equation);
		
		$rqt_bannette_abon = "INSERT INTO bannette_abon (num_bannette, num_empr, actif) VALUES ($id_bannette, $id_empr, 0)" ;
		mysql_query($rqt_bannette_abon);
		
		// bannette créée, on supprime le bouton des rech multicritères
		$_SESSION['abon_cree_bannette_priv'] = 0 ;
		print "<br />" ;
		print pmb_bidi(str_replace("!!nom_bannette!!", stripslashes($nom_bannette), $msg['dsi_bannette_creer_resultat'])) ;
		print "<br /><br />" ;
		// pour construction correcte du mail de diffusion 
		$liens_opac = array() ;
		$bannette = new bannette($id_bannette) ;
		$bannette->vider();
		print pmb_bidi($bannette->remplir());
		$bannette->diffuser($equ_human);
		} else {
			$s = new search() ;
			$equ_human = $s->make_serialized_human_query($equation) ;
			
			if ($opac_allow_bannette_export) {
				$exp = start_export::get_exports();
				$liste_exports = "<tr>
							<td align=right>".$msg['dsi_ban_typeexport']."</td>
							<td><select name='typeexport'>" ;
				$liste_exports .= "<option value='' selected>".$msg[dsi_ban_noexport]."</option>";
				for ($i=0;$i<count($exp);$i++) {
					$liste_exports .= "<option value='".$exp[$i]["PATH"]."' >".$exp[$i]["NAME"]."</option>";
				}
				$liste_exports .= "</select></td>
							</tr>" ;
			} else $liste_exports = "";
			
			
			print pmb_bidi($equ_human."<br /><br />") ;
			print "<form name='creer_dsi' method='post'>
					<input type='hidden' name='equation' value=\"".htmlentities($equation,ENT_QUOTES, $charset)."\" />
					<input type='hidden' name='enregistrer' value='1' />
					<input type='hidden' name='lvl' value='bannette_creer' />
					<table>
						<tr>
							<td align=right>".$msg['dsi_priv_form_nom']."</td>
							<td><input type='text' name='nom_bannette' value='' /></td>
							</tr>
						<tr>
							<td align=right>".$msg['dsi_priv_form_periodicite']."</td>
							<td><input type='text' name='periodicite' value='15' /></td>
							</tr>
						$liste_exports
						</table>
					<input type='submit' class='bouton' value=\"".$msg[dsi_bannette_creer_sauver]."\"/>
					</form>
					" ;
					
			}
	} else {
		// y'a un binz, pas d'équation...
		}


print "</div><!-- fermeture #aut_details -->\n";	
?>