<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.69 2010-04-16 05:31:24 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

echo window_title($database_window_title.$msg[5].$msg[1003].$msg[1001]);

// on a besoin des fonctions emprunteurs
require_once('./circ/empr/empr_func.inc.php');
require_once('./circ/expl/expl_func.inc.php');
require_once('./circ/pret_func.inc.php');
require_once("$class_path/serial_display.class.php");
require_once("$class_path/emprunteur.class.php");
require_once("$class_path/mono_display.class.php");
require_once("$class_path/notice.class.php");
require_once("$class_path/author.class.php");
require_once("$class_path/editor.class.php");
require_once("$class_path/collection.class.php");
require_once("$class_path/subcollection.class.php");
require_once("$class_path/serie.class.php");
require_once("$class_path/indexint.class.php");
require_once("$class_path/category.class.php");
require_once("$include_path/notice_authors.inc.php");
require_once("$include_path/notice_categories.inc.php");
require_once("$include_path/expl_info.inc.php") ;
require_once("$include_path/explnum.inc.php") ;
require_once("$include_path/resa_func.inc.php") ;
require_once("$include_path/isbn.inc.php");
require_once("$class_path/docs_location.class.php");
require_once("$class_path/bannette.class.php");

if (($categ=='pretrestrict') && ($form_login) && ($form_password)) {
	$query = "select id_empr, empr_cb from empr where empr_login='$form_login' and empr_password='$form_password' " ; 
	$result = mysql_query($query, $dbh);
	$id_empr = @mysql_result($result, '0', 'id_empr');
	$form_cb = @mysql_result($result, '0', 'empr_cb');
	if (($id_empr) && ($form_cb)) $categ='pret' ;	
}
if (SESSrights & RESTRICTCIRC_AUTH) $sub="" ;

switch($categ) {
	case 'pret':
		switch($sub) {
			case 'pret_prolongation':
				if ($id_doc) {
					$id_bloc=$id_doc;
				}
				if ($id_bloc) {
					include('./circ/prolongation.inc.php');
					$query_empr="select id_empr from empr where empr_cb='$form_cb'";
					$result_empr=mysql_query($query_empr);
					$id_empr=mysql_result($result_empr,0,'id_empr');
					$bloc_prolongation=0;
					if ($id_empr) {
						require_once("$class_path/emprunteur.class.php");
						$temp=prolonger($id_bloc);
						if ($temp!="") {
							$erreur_affichage=$temp;
						} else {
							$erreur_affichage="<table border='0' cellpadding='1' height='40'><tr><td width='30'><span><img src='./images/info.png' /></span></td>
							<td width='100%'><span class='erreur'>${msg[390]}</span></td></tr></table>";
						}
						$ficEmpr = new emprunteur($id_empr, $erreur_affichage, FALSE, 1);
						$affichage= $ficEmpr->fiche;
						print pmb_bidi($affichage);
					} else {
						// prolongation d'un prêt. exemplaire ou emprunteur inconnu
						error_message($msg[391], $msg[392], 1, './circ.php');
					}
				} else {
					include("./circ/pret.inc.php");
				}
				break;
			case 'compte':
				//Gestion des comptes financiers
				include("./circ/comptes.inc.php");
				break;
			case 'pret_prolongation_bloc':
				if ($id_doc) {
					$id_bloc=$id_doc;
				}
				if ($id_bloc) {
					require_once("./circ/prolongation.inc.php");
				
					$ids=split("  ",$id_bloc);
					$date_retour=$date_retbloc;
					$query_empr="select id_empr from empr where empr_cb='$form_cb'";
					$result_empr=mysql_query($query_empr);
					$id_empr=mysql_result($result_empr,0,'id_empr');				
					$temp="";
					$bloc_prolongation=1;
					
					if (($id_empr)&&(count($ids)>0)) {
						require_once("$class_path/emprunteur.class.php");
						foreach ($ids as $dummykey=>$id){
							$temp .= prolonger($id);
						}
						if ($temp!="") {
							$erreur_affichage=$temp;
						} else {
							$erreur_affichage="<table border='0' cellpadding='1' height='40'><tr><td width='30'><span><img src='./images/info.png' /></span></td>
							<td width='100%'><span class='erreur'>${msg[prets_prolong]}</span></td></tr></table>";
						}
						$ficEmpr = new emprunteur($id_empr, $erreur_affichage, FALSE, 1);
						$affichage= $ficEmpr->fiche;
						print pmb_bidi($affichage);
					} else {
						// prolongation d'un prêt. exemplaire ou emprunteur inconnu
						error_message($msg[391], $msg[392], 1, './circ.php');
					}
				} else {
					include("./circ/pret.inc.php");
				}
				break;
			case 'pret_express':
				$pe_isbn=traite_code_isbn(stripslashes($pe_isbn));
				$suite_rqt="";
				$requete_idexpl = "select expl_id from exemplaires where expl_cb='".addslashes($pe_excb)."'";
				$result = mysql_query($requete_idexpl, $dbh);
				if(mysql_num_rows($result)==0) {
					if (isISBN($pe_isbn)) {
						if (strlen($pe_isbn)==13)
							$suite_rqt=" or code='".formatISBN($pe_isbn,13)."' ";
						else $suite_rqt="or code='".formatISBN($pe_isbn,10)."' ";
					}
					$acreer = 1 ;
					if ($pe_isbn) {
						$requete = "select notice_id from notices where code='".addslashes($pe_isbn)."' ".$suite_rqt." and niveau_biblio='m' and niveau_hierar='0' ";	
						$result = mysql_query($requete, $dbh);
						if ($tmp_not = mysql_fetch_object($result)) {
							$id_notice=$tmp_not->notice_id;
							$acreer = 0 ;
						}
					}
					if ($acreer) {
						$ind_wew = " ".$pe_titre." " ;
						$ind_sew = strip_empty_words($ind_wew) ; 
						$requete = "INSERT INTO notices SET code='".addslashes($pe_isbn)."', tit1='$pe_titre', statut='$pmb_pret_express_statut', index_sew=' $ind_sew ', index_wew='$ind_wew', niveau_biblio='m', niveau_hierar='0', create_date=sysdate() ";	
						$result = mysql_query($requete, $dbh);
						if (!$result) die ('ERROR PE: insert into notice');
						$id_notice=mysql_insert_id();
						
						audit::insert_creation (AUDIT_NOTICE, $id_notice) ;
						notice::majNoticesGlobalIndex($id_notice,1);
						notice::majNoticesMotsGlobalIndex($id_notice);
						
						if ($gestion_acces_active==1) {
							require_once("$class_path/acces.class.php");
							$ac= new acces();
							
							//traitement des droits acces user_notice
							if ($gestion_acces_user_notice==1) {			
								$dom_1= $ac->setDomain(1);
								$dom_1->storeUserRights(0, $id_notice);
							}
							//traitement des droits acces empr_notice
							if ($gestion_acces_empr_notice==1) {			
								$dom_2= $ac->setDomain(2);
								$dom_2->storeUserRights(0, $id_notice);
							}
						}						
						
					}
					if (!$id_notice) die ('ERROR PE: aucun id_notice pour exemplaire...');
					
					// exemplaire express
					if ($pe_excb=="") $pe_excb='PE'.rand(0,100000);
					
					$requete = "INSERT INTO exemplaires 
								SET expl_cb='$pe_excb', 
									expl_notice='$id_notice', 
									expl_typdoc='$pe_tdoc', 
									expl_location='$deflt_docs_location',
									expl_section='$deflt_docs_section',
									expl_statut='$deflt_docs_statut',
									expl_codestat='$deflt_docs_codestat',
									expl_owner='$deflt_lenders'
									";
					$result = mysql_query($requete, $dbh);
					if (!$result) {
						error_message($msg[350], $msg['pecb_already_exist'], 1 ,'');
						exit();
					}
					$id_expl= mysql_insert_id();
					
					if (preg_match('/^PE/',$pe_excb)) {
						//redefine exemplaires.expl_cb if $pe_excb is random  
						$pe_excb='PE'.$id_expl;
						$requete = "UPDATE exemplaires SET expl_cb='$pe_excb' WHERE expl_id='$id_expl'";
						$result = mysql_query($requete, $dbh);
						if (!$result) die ('ERROR PE: update exemplaires');
					}
					$cb_doc=$pe_excb;		
					$rqtstatut = "select gestion_libelle from notice_statut where id_notice_statut='$pmb_pret_express_statut' ";	
					$resstatut = mysql_fetch_object(mysql_query($rqtstatut, $dbh));
					$noteexpress = addslashes($resstatut->gestion_libelle) ;
					$requete = "UPDATE exemplaires SET expl_note='$noteexpress' WHERE expl_id='$id_expl'";
					$result = mysql_query($requete, $dbh);
					include("./circ/pret.inc.php");
				} else error_message($msg[350], $msg['pecb_already_exist'], 1 ,'');
				break;
			case 'suppr_resa_from_fiche':
				include("./circ/listeresa/main.inc.php");
				include("./circ/pret.inc.php");
				break;
			case 'suppr_resa_planning_from_fiche' :
				include("./circ/resa_planning/main.inc.php");
				include("./circ/pret.inc.php");
				break;	
			case 'show_late':
				include("./circ/show_late.inc.php");
				break;		
			default:
				include("./circ/pret.inc.php");
				break;
		}
		break;
	case 'retour':
		include("./circ/retour.inc.php");
		break;
	case 'retour_secouru':
		include("./circ/retour_secouru_download.inc.php");
		break;
	case 'retour_secouru_int':
		include("./circ/retour_secouru.inc.php");
		break;
	case 'resa':
		include("./circ/resa/main.inc.php");
		break;
	case 'express':
		include("./circ/express/main.inc.php");
		break;
	case 'visu_rech':
		include("./circ/visu_rech/visu_rech.inc.php");
		break;
	case 'empr_update':
		// update/insert d'un emprunteur
		include("./circ/empr/empr_update.inc.php");
		break;
	case 'empr_create':
		// récupération code barre en vue création d'un emprunteur
		include("./circ/empr/empr_create.inc.php");
		break;
	case 'empr_delete':
		// suppression d'un emprunteur
		include("./circ/empr/delete.inc.php");
		break;
	case 'empr_saisie':
		// affichage formulaire de saisie d'un emprunteur
		include("./circ/empr/empr_saisie.inc.php");
		break;
	case 'empr_duplicate':
		echo window_title($database_window_title.$msg["empr_duplicate"].$msg[1003].$msg[1001]);
		$rqt = "select max(id_empr+1) as max_id from empr ";
		$res = mysql_query($rqt, $dbh);
		$id_initial = mysql_fetch_object($res);
		$id_a_creer = (string)$id_initial->max_id;
		// modif pour nouvelle méthode d'incrémentation	*********************************************************************
		$pmb_num_carte_auto_array=array();
		$pmb_num_carte_auto_array=explode(",",$pmb_num_carte_auto);

		if ($pmb_num_carte_auto_array[0] == "1" ) {
			$rqt = "select max(empr_cb+1) as max_cb from empr ";
			$res = mysql_query($rqt, $dbh);
			$cb_initial = mysql_fetch_object($res);
			$cb_a_creer = (string)$cb_initial->max_cb;
		} elseif ($pmb_num_carte_auto_array[0] == "2" ) {
			$long_prefixe = $pmb_num_carte_auto_array[1];
			$nb_chiffres = $pmb_num_carte_auto_array[2];
			$prefix = $pmb_num_carte_auto_array[3];
		    $rqt =  "SELECT SUBSTRING(empr_cb,".($long_prefixe+1).") AS max_cb, SUBSTRING(empr_cb,1,".($long_prefixe*1).") AS prefixdb FROM empr ORDER BY max_cb DESC limit 0,1" ; // modif f cerovetti pour sortir dernier code barre tri par ASCII
			$res = mysql_query($rqt, $dbh);
			$cb_initial = mysql_fetch_object($res);
			$cb_a_creer = ($cb_initial->max_cb*1)+1;
			if (!$nb_chiffres) $nb_chiffres=strlen($cb_a_creer);
			if (!$prefix) $prefix = $cb_initial->prefixdb;
			$cb_a_creer = $prefix.substr((string)str_pad($cb_a_creer, $nb_chiffres, "0", STR_PAD_LEFT),-$nb_chiffres);
			// fin modif pour nouvelle méthode d'incrémentation*******************************************************************			
		} else $cb_a_creer="";
		show_empr_form("./circ.php?categ=empr_update","./circ.php?categ=empr_create",$dbh, $id, (string)$cb_a_creer,(string)$id_a_creer);
		break;
	case 'visu_ex':
		// visualisation d'un exemplaire
		include("./circ/visu_ex.inc.php");
		break;
	case 'note_ex':
		// visualisation d'un exemplaire
		include("./circ/note_ex.inc.php");
		break;
	case 'groups':
		// interface de gestion des groupes
		include("./circ/groups/group_main.inc.php");
		break;
	case 'listeresa':
		// gestion des réservations
		include("./circ/listeresa/main.inc.php");
		break;
	case 'resa_planning':
		// gestion des réservations planifiées
		include("./circ/resa_planning/main.inc.php");
		break;
	case 'relance':
		//Gestion des relances
		include("./circ/relance/main.inc.php");
		break;
	case 'caddie':
		include('./circ/caddie/caddie.inc.php');
		break;
	case 'sug' :
		//Création de suggestion
		include("./circ/suggestions/make_sug.inc.php");
		break;		
	case 'resa_from_catal' :
		// on est en pose de résa en arrivant avec un id_çnotice ou bulletin mais sans emprunteur
		if ($id_notice) get_cb( $msg['reserv_doc'], $msg[34], $msg[circ_tit_form_cb_empr], './circ.php?categ=pret&id_notice='.$id_notice, 0);
		elseif ($id_bulletin) get_cb( $msg['reserv_doc'], $msg[34], $msg[circ_tit_form_cb_empr], './circ.php?categ=pret&id_bulletin='.$id_bulletin, 0);
		break;		
	case 'trans' :
		// Transferts entre bibliothèques
		include("./circ/transferts/main.inc.php");
		break;
	case 'rfid_prog' :
		// programmer les étiquettes rfid en masse
		include("./circ/rfid/rfid_prog.inc.php");
		break;	
	case 'rfid_del' :
		// effacer les étiquettes rfid en masse	
		include("./circ/rfid/rfid_del.inc.php");
		break;				
	case 'rfid_read' :
		// lire les étiquettes rfid en masse
		include("./circ/rfid/rfid_read.inc.php");
		break;	
	case 'ret_todo' :
		// voir les exemplaires qui nécessitent un traitement non effectué lors d'un retour
		include("./circ/ret_todo/ret_todo.inc.php");
		break;			
	default:
		if (SESSrights & RESTRICTCIRC_AUTH) get_login_empr_pret ( $msg[13], $msg[34], $msg[circ_tit_form_cb_empr], './circ.php?categ=pretrestrict', 0);
		else get_cb( $msg[13], $msg[34], $msg[circ_tit_form_cb_empr], './circ.php?categ=pret', 0);
		break;
	}
