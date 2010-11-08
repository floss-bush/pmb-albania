<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.36 2010-07-28 09:21:38 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$include_path/resa.inc.php");
require_once("$include_path/resa_func.inc.php");
require_once("$include_path/templates/resa.tpl.php");

if ($action=="suppr_resa" && $impression_confirmation) {
	$action = "imprimer_confirmation" ;
	// c'est une demande manuelle d'impression
	$bouton_impr_conf = 1 ;
} 
 
switch($action) {
	case 'valide_cb':
		if ($sub == 'encours') {
			if ($form_cb_expl) {
				if (!verif_cb_expl($form_cb_expl))  {
					$msg_a_pointer = "<br /><div class='erreur'>";
					$msg_a_pointer .=  "<strong>$form_cb_expl&nbsp;: ${msg[367]}</strong><br />";
					$msg_a_pointer .= "</div>" ;
					break ;
				}
				if (verif_cb_utilise_en_pret($form_cb_expl))  {
					$msg_a_pointer = "<br /><div class='erreur'>";
					$msg_a_pointer .=  "<strong>$form_cb_expl&nbsp;: ${msg[387]}</strong><br />";
					$msg_a_pointer .= "</div>" ;
					break ;
				}
				if (verif_cb_utilise ($form_cb_expl)) {
					$msg_a_pointer = "<br /><div class='erreur'>";
					$msg_a_pointer .=  "<strong>$form_cb_expl: ".$msg[resa_doc_utilise]."</strong><br />";
					$msg_a_pointer .= "</div>" ;
					break ;
				}
				$id_resa_validee = affecte_cb ($form_cb_expl) ;
				if ($id_resa_validee!=0) {
					if ($pmb_transferts_actif=="1") {
						//generation d'un transfert si nécéssaire
						$res_transfert = resa_transfert($id_resa_validee,$form_cb_expl);
						if ($res_transfert!=0) {
							$rqt = "SELECT location_libelle FROM docs_location WHERE idlocation=".$res_transfert;
							$lib_loc = mysql_result(mysql_query($rqt),0);			
						
							//desaffecte_cb($form_cb_expl,$id_resa_validee);
							$msg_a_pointer = "<br /><div class='erreur'>";
							$msg_a_pointer .=  "<strong>".$form_cb_expl.": ".str_replace("!!site_dest!!",$lib_loc,$msg["transferts_circ_resa_validation_alerte"])."</strong><br />";
							$msg_a_pointer .= "</div>" ;
						} else{
							//sinon on alerte l'emprunteur
							alert_empr_resa($id_resa_validee) ;
							$requete="SELECT empr_cb, empr_nom, empr_prenom, location_libelle FROM resa JOIN empr ON resa_idempr=id_empr JOIN docs_location ON resa_loc_retrait=idlocation  WHERE id_resa=".$id_resa_validee."";
							$res=mysql_query($requete);
							$msg_a_pointer = "<div class='row'>";
							$msg_a_pointer .="<div class='erreur'>".$msg["circ_retour_ranger_resa"]."</div>";
							$msg_a_pointer .= "<span style='margin-left:2em;'><strong>".$msg["circ_retour_resa_par"]." : </strong><a href='./circ.php?categ=pret&form_cb=".rawurlencode(mysql_result($res,0,0))."'>".htmlentities(mysql_result($res,0,1),ENT_QUOTES,$charset).", ".htmlentities(mysql_result($res,0,2),ENT_QUOTES,$charset)."</a></span><br/>";
							$msg_a_pointer .= "<span style='margin-left:2em;'><strong>".$msg["376"]." : </strong><a href='./circ.php?categ=visu_ex&form_cb_expl=".rawurlencode($form_cb_expl)."'>".htmlentities($form_cb_expl,ENT_QUOTES,$charset)."</a></span><br/>";
							$msg_a_pointer .= "<span style='margin-left:2em;'><strong>".$msg["circ_retour_loc_retrait"]." : </strong>".htmlentities(mysql_result($res,0,3),ENT_QUOTES,$charset)."</span><br/>";
							$msg_a_pointer .= "</div>" ;
						}	
					}else{
						//sinon on alerte l'emprunteur
						alert_empr_resa($id_resa_validee) ;
						$requete="SELECT empr_cb, empr_nom, empr_prenom, location_libelle FROM resa JOIN empr ON resa_idempr=id_empr JOIN docs_location ON resa_loc_retrait=idlocation  WHERE id_resa=".$id_resa_validee."";
						$res=mysql_query($requete);
						$msg_a_pointer = "<div class='row'>";
						$msg_a_pointer .="<div class='erreur'>".$msg["circ_retour_ranger_resa"]."</div>";
						$msg_a_pointer .= "<span style='margin-left:2em;'><strong>".$msg["circ_retour_resa_par"]." : </strong><a href='./circ.php?categ=pret&form_cb=".rawurlencode(mysql_result($res,0,0))."'>".htmlentities(mysql_result($res,0,1),ENT_QUOTES,$charset).", ".htmlentities(mysql_result($res,0,2),ENT_QUOTES,$charset)."</a></span><br/>";
						$msg_a_pointer .= "<span style='margin-left:2em;'><strong>".$msg["376"]." : </strong><a href='./circ.php?categ=visu_ex&form_cb_expl=".rawurlencode($form_cb_expl)."'>".htmlentities($form_cb_expl,ENT_QUOTES,$charset)."</a></span><br/>";
						$msg_a_pointer .= "<span style='margin-left:2em;'><strong>".$msg["circ_retour_loc_retrait"]." : </strong>".htmlentities(mysql_result($res,0,3),ENT_QUOTES,$charset)."</span><br/>";
						$msg_a_pointer .= "</div>" ;
					}
						
				} //if ($id_resa_validee)
			} else {
				/*
				//c'est pour un transfert
				$rqt = 	"SELECT id_resa ".
						"FROM resa, exemplaires ".
						"WHERE expl_notice= resa_idnotice ".
							"AND expl_bulletin=resa_idbulletin ".
							"AND expl_cb='" . $cb_trans . "' ".
							"AND resa_cb='' ".
							"AND resa_date_fin='0000-00-00' ";
				print $rqt;
				$res = mysql_query($rqt);
				if (mysql_num_rows($res)) {
					$id_resa = mysql_result($res,0);*/
					/*
					si la loc de l'exemplaire sélectionné est identique à la loc du retrait => pas de transfert
					sinon on génère le transfert entre la loc de l'exemplaire et celle du retrait				
				
				*/
				
					$loc_destination=resa_transfert($transfert_id_resa,$cb_trans);
					if($loc_destination){
						$rqt = "SELECT location_libelle FROM docs_location WHERE idlocation=".$loc_destination;
						$lib_loc = mysql_result(mysql_query($rqt),0);			
				
						$msg_a_pointer= "<br /><div class='erreur'>";
						$msg_a_pointer.="<strong>" . $cb_trans . ": " . str_replace("!!site_dest!!", $lib_loc, $msg["transferts_circ_resa_validation_alerte"]) . "</strong><br />";
						$msg_a_pointer.="</div>";	
					} else {
						$msg_a_pointer= "<br /><div class='erreur'>";
						$msg_a_pointer.="<strong>". str_replace("!!cb_trans!!", $cb_trans, $msg["transferts_resa_refus"]) . "</strong><br />";
						$msg_a_pointer.="</div>";	
					}
					/*
					$trans = new transfert();
					$trans->transfert_pour_resa($cb_trans, $deflt_docs_location, $transfert_id_resa);					
				
					$rqt = "SELECT location_libelle FROM docs_location WHERE idlocation=".$deflt_docs_location;
					$lib_loc = mysql_result(mysql_query($rqt),0);			
				
					//desaffecte_cb($form_cb_expl,$id_resa_validee);
					$msg_a_pointer = "<br /><div class='erreur'>";
					$msg_a_pointer .=  "<strong>" . $cb_trans . ": " . str_replace("!!site_dest!!", $lib_loc, $msg["transferts_circ_resa_validation_alerte"]) . "</strong><br />";
					$msg_a_pointer .= "</div>" ;
					*/
				//} //if (mysql_num_rows($res))
			} // if ($form_cb_expl) else
		} //if ($sub == 'encours')
		break;

	case 'suppr_resa':
		// récupérer les items
		for ($i=0 ; $i < sizeof($suppr_id_resa) ; $i++) {
			// récup éventuelle du cb
			$cb_recup = recupere_cb ($suppr_id_resa[$i]) ;
			// archivage resa
			$rqt_arch = "UPDATE resa_archive, resa SET resarc_anulee = 1 WHERE  id_resa = '".$suppr_id_resa[$i]."' AND resa_arc = resarc_id "; 
			mysql_query ($rqt_arch, $dbh);
			// suppression
			$rqt = "delete from resa where id_resa='".$suppr_id_resa[$i]."' ";
			$res = mysql_query ($rqt, $dbh) ;			
			// réaffectation du doc éventuellement
			if (!verif_cb_utilise ($cb_recup)) {
				if (!($id_resa_validee=affecte_cb ($cb_recup)) && $cb_recup) {
					// cb non réaffecté, il faut transférer les infos de la résa dans la table des docs à ranger
					$rqt = "insert into resa_ranger (resa_cb) values ('".$cb_recup."') ";
					$res = mysql_query ($rqt, $dbh) ;
				} else alert_empr_resa($id_resa_validee) ;
			}
		}	
		break;

	case 'imprimer_confirmation':
		// récupérer les items
		if (!$suppr_id_resa) $suppr_id_resa=array() ;
		$tmp_in_resa = implode(",",$suppr_id_resa) ;
		if ($tmp_in_resa) {
			$rqt = "select id_resa, resa_idempr, resa_confirmee from resa where id_resa in (".$tmp_in_resa.") and resa_cb is not null and resa_cb!='' order by resa_idempr ";
			$res = mysql_query ($rqt, $dbh) ;
			alert_empr_resa ($tmp_in_resa);		
		} else 	print alert_jscript($msg['no_resa_selected']);
		break;
	case 'suppr_cb':
		if (!$form_cb_expl) break ;
		$msg_a_ranger = "<br /><div class='erreur'>";
		$aff_a_ranger .= "<hr />" ;
		// récupérer l'exemplaire
		$query = "select expl_id from exemplaires where expl_cb='$form_cb_expl'";
		$result = mysql_query($query, $dbh);
		if(!mysql_num_rows($result)) {
			// exemplaire inconnu
			$aff_a_ranger .= "<strong>$form_cb_expl&nbsp;: ${msg[367]}</strong><br />";
		} else {
			$expl_lu = mysql_fetch_object($result) ;
			if($stuff = get_expl_info($expl_lu->expl_id)) {
				$stuff = check_pret($stuff);
				$aff_a_ranger .=  print_info($stuff,1,0,0)."<br />";
			} else {
				$aff_a_ranger .=  "<strong>$form_cb_expl&nbsp;: ${msg[395]}</strong><br />";
			}
		}
		$rqt = "delete from resa_ranger where resa_cb='".$form_cb_expl."' ";
		$res = mysql_query ($rqt, $dbh) ;
		if (mysql_affected_rows()) $msg_a_ranger .= $msg[resa_docrange] ;
			else $msg_a_ranger .= $msg[resa_docrange_non] ;
		$msg_a_ranger = str_replace('!!cb!!', $form_cb_expl, $msg_a_ranger );
		$msg_a_ranger .= "</div>" ;
		break;
}


switch($sub) {
	case 'docranger':
		print "<h1>$msg[resa_menu] > ".$msg["resa_menu_liste_".$sub]."</h1>" ;
		get_cb_expl("", $msg[661], $msg[resa_suppr_doc], "./circ.php?categ=listeresa&sub=$sub&action=suppr_cb", 1);
		print $msg_a_ranger.$aff_a_ranger ;
		print "<h3>".$msg['resa_liste_docranger']."</h3>" ;
		print pmb_bidi(resa_ranger_list ()) ;
		break;
		
	case 'depassee':
		print "<h1>$msg[resa_menu] > ".$msg["resa_menu_liste_".$sub]."</h1>" ;
		print pmb_bidi(resa_list (0, 0, 0,"","resa_date_fin < CURDATE() and resa_date_fin<>'0000-00-00' ",1,"./circ.php?categ=listeresa&sub=$sub")) ;
		break;
	
	case 'suppr_resa_from_fiche':
		break;
	
	default:
	case 'encours':
		print "<h1>$msg[resa_menu] > ".$msg["resa_menu_liste_".$sub]."</h1>" ;
		get_cb_expl("", $msg[661], $msg[resa_pointage_doc], "./circ.php?categ=listeresa&sub=$sub&action=valide_cb&f_loc=$f_loc", 1);

		//un message à afficher
		print $msg_a_pointer ;
		
		//la clause de restriction
		$cl_where = "(resa_date_fin >= CURDATE() or resa_date_fin='0000-00-00')";
/*		
		if ($pmb_transferts_actif=="1") {
		
			if ($f_loc=="")
				$f_loc = $deflt_docs_location;

			if ($f_loc!="0") {

				switch ($transferts_choix_lieu_opac) {
					case "1":
						//retrait de la resa sur lieu choisi par le lecteur
						$cl_where .= " AND resa_loc_retrait=" . $f_loc;
					break;					
					case "2":
						//retrait de la resa sur lieu fixé
						if ($f_loc!=$transferts_site_fixe)
							$cl_where .= " AND 0";
					break;			
					case "3":
						//retrait de la resa sur lieu exemplaire
					default:
						//retrait de la resa sur lieu lecteur
						$cl_where .= " AND empr_location=" . $f_loc;
					break;
				
						
				} //switch $transferts_choix_lieu_opac

			} //if ($f_loc!="0")
			
		} //if pmb_transferts_actif 
*/		
		//on affiche la liste
		echo $resa_liste_jscript_GESTION_INFO_GESTION;
		print pmb_bidi(resa_list (0, 0, 0,"", $cl_where,1,"./circ.php?categ=listeresa&sub=$sub")) ;
		break;
}

