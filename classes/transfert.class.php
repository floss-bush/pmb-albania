<?php
// +-------------------------------------------------+
// | 2002-2007 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: transfert.class.php,v 1.24.2.1 2011-05-23 12:46:24 ngantier Exp $

if (stristr ( $_SERVER ['REQUEST_URI'], ".class.php" ))
	die ( "no access" );

require_once ("$include_path/templates/transferts.tpl.php");
require_once ("$include_path/resa.inc.php");
require_once ("$include_path/resa_func.inc.php");

//********************************************************************************************
// Classe de gestion des transferts d'exemplaire entre localisations
//********************************************************************************************

class transfert {
	
	//********************************************************************************************
	// fonctions générales
	//********************************************************************************************
	
	// constructeur
	function transfert() {
	
	}
	
	//********************************************************************************************
	
	function _creer_transfert( $id_expl, $src, $dest, $t_trans, $date_ret='', $origine=0, $ori_comp ='', $motif='', $sens=0, $etat=0) {
	
		//on recupere le no de notice
		$rqt = "SELECT expl_notice, expl_bulletin, expl_statut, expl_section  
				FROM exemplaires 
				WHERE expl_id=".$id_expl;
		$res = mysql_query ( $rqt );
		$expl = mysql_fetch_object($res);
		//$id_notice = mysql_result ( $res, 0 );
	
		// verif si déjà existrant
		$rqt = "Select * from transferts where  
			num_notice=".$expl->expl_notice." and
			num_bulletin=".$expl->expl_bulletin." and  
			type_transfert=$t_trans and
			etat_transfert=0 and 
			origine=$origine and
			origine_comp ='".addslashes($ori_comp)."' and
			source=$src and
			destinations =$dest ";
			
		$res=mysql_query ( $rqt );
		if (mysql_num_rows($res)) {
			$obj_data = mysql_fetch_object($res);
			$num=$obj_data->id_transfert;				
		} else {
		
			//on cree l'enregistrement dans la table transferts
			$rqt = "INSERT INTO transferts ( 
						num_notice, num_bulletin, date_creation,  
						type_transfert, etat_transfert, 
						origine, origine_comp, 
						source, destinations,  
						date_retour, motif ) VALUES (". 
						$expl->expl_notice . ", " . $expl->expl_bulletin . ", NOW(),".  
						$t_trans . ", 0, ".
						$origine . ", '" . addslashes($ori_comp) . "',".  
						$src . ", '" . $dest . "', 
						'" . $date_ret . "', '" .	addslashes($motif) . "' )";
			mysql_query ( $rqt );
	
			//on recupere l'id du transfert crée
			$num = mysql_insert_id ();
		}	
		$rqt = "Select * from transferts_demande where
					num_transfert=$num and					  
					sens_transfert=$sens and 
					num_location_source=$src and  
					num_location_dest=$dest and
					num_expl=$id_expl  
					";
		$res=mysql_query ( $rqt );

		if (!mysql_num_rows($res)) {
				
			//la table transferts_demande
			$rqt = "INSERT INTO transferts_demande (
						num_transfert, date_creation,  
						sens_transfert, num_location_source,  
						num_location_dest, num_expl, 
						statut_origine, section_origine, 
						etat_demande ) VALUES (". 
						$num . ", NOW(), ". 
						$sens . ", " . $src . ", ". 
						$dest . ", " . $id_expl . ", ". 
						$expl->expl_statut . ", " . $expl->expl_section . ", ". 
						$etat .")";
			mysql_query ( $rqt );
		}
		return $num;
	}
	
	//change le statut d'un exemplaire
	function _change_statut_exemplaire( $id_expl, $id_statut ) {
	
		$rqt = 	"UPDATE exemplaires SET transfert_statut_origine = expl_statut WHERE expl_id=".$id_expl;
		mysql_query ( $rqt );
		
		//changement du statut
		$rqt = 	"UPDATE exemplaires SET expl_statut=".$id_statut." WHERE expl_id=".$id_expl;
		mysql_query ( $rqt );
	}
	
	//change la localisation d'un exemplaire
	function _change_localisation_exemplaire( $id_expl, $id_localisation, $sauve_loc = false ) {
		
		//sauvegarde de la localisation
		if ($sauve_loc) {
			$rqt = "UPDATE exemplaires SET transfert_location_origine = expl_location WHERE expl_id=".$id_expl;
			mysql_query ( $rqt );
		}
		
		//changement de la localisation
		$rqt = "UPDATE exemplaires SET expl_location=".$id_localisation." WHERE expl_id=".$id_expl;
		mysql_query ( $rqt );
	
	}
	
	//retourne le no de transfert à partir de son no d'exemplaire
	function _explcb_2_transid($cbEx, $etat, $sens) {
		global $deflt_docs_location;
		//on recupere l'id de l'exemplaire*		
		if($sens) {
			$loc_req= " AND num_location_dest=".$deflt_docs_location ;
		} else {
			$loc_req= " AND num_location_source=".$deflt_docs_location ;
		}
		$rqt = "SELECT id_transfert 
					FROM transferts 
						INNER JOIN transferts_demande ON id_transfert=num_transfert 
						INNER JOIN exemplaires ON num_expl=expl_id  
					WHERE etat_transfert=0 AND expl_cb='".$cbEx."' AND etat_demande=".$etat. " $loc_req " ;
		$res = mysql_query ( $rqt );
		if (mysql_num_rows($res))
			return mysql_result ( $res, 0 ); 
		else
			return 0;
	}

	//retourne le no de transfert à partir de son no d'exemplaire
	function _transid_2_explcb($transId, $etat) {
		//on recupere l'id de l'exemplaire
		$rqt = "SELECT expl_cb 
					FROM transferts 
						INNER JOIN transferts_demande ON id_transfert=num_transfert 
						INNER JOIN exemplaires ON num_expl=expl_id 
					WHERE id_transfert=".$transId." AND etat_demande=".$etat;
		$res = mysql_query ( $rqt );
		if (mysql_num_rows($res))
			return mysql_result ( $res, 0 ); 
		else
			return 0;
	}
	
	//retourne le no de transfert à partir de son no d'exemplaire
	function _transid_2_explid($transId, $etat) {
		//on recupere l'id de l'exemplaire
		$rqt = "SELECT num_expl 
					FROM transferts 
						INNER JOIN transferts_demande ON id_transfert=num_transfert 
					WHERE id_transfert=".$transId." AND etat_demande=".$etat;
		$res = mysql_query ( $rqt );
		if (mysql_num_rows($res))
			return mysql_result ( $res, 0 ); 
		else
			return 0;
	}
	
	//sauvegarde la localisation de l'exemplaire
	function _sauve_localisation_exemplaire($idExpl) {
		$rqt = "UPDATE exemplaires SET transfert_location_origine=expl_location WHERE expl_id=".$idExpl;
		mysql_query ( $rqt );
	}
	
	//restaure le statut de l'exemplaire
	function _restaure_statut($idTrans) {
		//Récupération des informations d'origine
		$rqt = "SELECT statut_origine, num_expl FROM transferts INNER JOIN transferts_demande ON id_transfert=num_transfert 
					WHERE id_transfert=".$idTrans." AND sens_transfert=0";
		$res = mysql_query($rqt);
		$obj_data = mysql_fetch_object($res);
		//on met à jour
		$rqt = "UPDATE exemplaires SET expl_statut=".$obj_data->statut_origine." WHERE expl_id=".$obj_data->num_expl;
		mysql_query ( $rqt );
	}
	
	//restaure la section de l'exemplaire
	function _restaure_section($idTrans) {
		//Récupération des informations d'origine
		$rqt = "SELECT section_origine, num_expl FROM transferts INNER JOIN transferts_demande ON id_transfert=num_transfert 
					WHERE id_transfert=".$idTrans." AND sens_transfert=0";
		$res = mysql_query($rqt);
		$obj_data = mysql_fetch_object($res);
		
		//on met à jour
		$rqt = "UPDATE exemplaires SET expl_section=".$obj_data->section_origine." WHERE expl_id=".$obj_data->num_expl;
		mysql_query ( $rqt );
	}
	
	//restaure la localisation de l'exemplaire
	function _restaure_localisation($idTrans) {
		
		//Récupération des informations d'origine
		$rqt = "SELECT source, num_expl FROM transferts INNER JOIN transferts_demande ON id_transfert=num_transfert 
				WHERE id_transfert=".$idTrans." AND sens_transfert=0";
		$res = mysql_query($rqt);
		$obj_data = mysql_fetch_object($res);
		
		//on met à jour
		$rqt = "UPDATE exemplaires SET expl_location=".$obj_data->source." WHERE expl_id=".$obj_data->num_expl;
		mysql_query ( $rqt );
/*
		$rqt = 	"UPDATE exemplaires " . 
				"SET expl_location = transfert_location_origine " . 
				"WHERE expl_id = " . $idExpl;

		mysql_query ( $rqt );
*/
	}
	
	//********************************************************************************************
	// pour le retour de pret d'exemplaires
	//********************************************************************************************

	//sur un retour d'exemplaire on change la localisation
	function retour_exemplaire_change_localisation($expl_id) {
		global $transferts_retour_change_localisation;
		global $deflt_docs_location;
		
		$rqt = "SELECT expl_location FROM exemplaires WHERE expl_id=".$expl_id;
		$res = mysql_query ( $rqt );
		$locOri = mysql_result ( $res, 0 );
		
		$this->_change_localisation_exemplaire($expl_id, $deflt_docs_location, ($transferts_retour_change_localisation == "1"));
		
		$rqt = "SELECT idsection FROM exemplaires INNER JOIN docs_section ON expl_section=idsection INNER JOIN docsloc_section ON idsection=num_section 
				WHERE expl_id=".$expl_id." AND num_location=".$deflt_docs_location;
		$res = mysql_query($rqt);
		if (mysql_num_rows($res)==0) {
			//la section n'existe pas pour cette localisation !
			//on cherche la premiere section dispo
			$rqt = 	"SELECT idsection FROM docs_section INNER JOIN docsloc_section ON idsection=num_section WHERE num_location=".$deflt_docs_location." LIMIT 1";
			$res = mysql_query($rqt);
			$id_section = mysql_result($res,0);
		} else 
			$id_section = mysql_result($res,0);

		//changement de la localisation
		$rqt = 	"UPDATE exemplaires SET expl_section=".$id_section." WHERE expl_id=" . $expl_id;
		mysql_query ($rqt);
		return $locOri;
	}

	function is_retour_exemplaire_loc_origine($expl_id) {
		$rqt = "SELECT expl_location,expl_cb, transfert_location_origine,transfert_statut_origine, expl_section FROM exemplaires WHERE expl_id=".$expl_id;
		$res = mysql_query ( $rqt );
		$expl = mysql_fetch_object ( $res );		
		//$num = $this->enregistre_retour_cb($expl->expl_cb);
		
		global $deflt_docs_location;
		//on recupere l'id de l'exemplaire*		

	

		$rqt = "SELECT id_transfert 
					FROM transferts 
						INNER JOIN transferts_demande ON id_transfert=num_transfert 
						INNER JOIN exemplaires ON num_expl=expl_id  
					WHERE etat_transfert=0 AND expl_cb='".$expl->expl_cb."' AND etat_demande=3  AND num_location_source=".$deflt_docs_location ;

		$res = mysql_query ( $rqt );
		if (mysql_num_rows($res))
			return  mysql_result ( $res, 0 ); 
		else
			return 0;		
			
		
	}
	//sur un retour d'exemplaire sur sa localisation d'origine alors qu'il était localisé ailleur (par un transfert)
	// il faut donc cloturer le retour programmé et rétablir la localisation, section cet exemplaire 
	function retour_exemplaire_loc_origine($expl_id) {
		$rqt = "SELECT expl_location,expl_cb, transfert_location_origine,transfert_statut_origine, expl_section FROM exemplaires WHERE expl_id=".$expl_id;
		$res = mysql_query ( $rqt );
		$expl = mysql_fetch_object ( $res );		
		//$num = $this->enregistre_retour_cb($expl->expl_cb);
		
		global $deflt_docs_location;
		//on recupere l'id de l'exemplaire*		

		$loc_req= " AND num_location_source=".$deflt_docs_location ;

		$rqt = "SELECT id_transfert 
					FROM transferts 
						INNER JOIN transferts_demande ON id_transfert=num_transfert 
						INNER JOIN exemplaires ON num_expl=expl_id  
					WHERE etat_transfert=0 AND expl_cb='".$expl->expl_cb."' AND etat_demande=3  AND num_location_source=".$deflt_docs_location ;

		$res = mysql_query ( $rqt );
		if (mysql_num_rows($res))
			$idTrans= mysql_result ( $res, 0 ); 
		else
			return 0;		
		
		$this->enregistre_retour ( $idTrans );
		$rqt = "SELECT  location_libelle  
			FROM transferts_demande,docs_location 
			WHERE num_location_source=idlocation and num_transfert=".$idTrans." AND etat_demande=5";
		$res = mysql_query ( $rqt );
		$value = mysql_fetch_array ( $res );
		$this->new_location_libelle=$value[0];
		
		$num = $this->enregistre_reception_cb($expl->expl_cb, 0,0);		
		//purge les restes de transfert intermédiaire...
		$rqt = "update transferts,transferts_demande, exemplaires set etat_transfert=1							
				WHERE id_transfert=num_transfert and num_expl=expl_id  and etat_transfert=0 AND expl_cb='".$expl->expl_cb."' " ;
		 mysql_query ( $rqt );
		return $num;
	}	
	
	//sur un retour d'exemplaire on genere un transfert de retour
	function retour_exemplaire_genere_transfert_retour($expl_id) {
		global $transferts_retour_etat_transfert;
		global $transferts_retour_motif_transfert;
		global $deflt_docs_location;
		
		//on recupere la localisation de l'exemplaire
		//elle va servir pour la destination du transfert
		$rqt = "SELECT expl_location FROM exemplaires WHERE expl_id=".$expl_id;
		$res = mysql_query ( $rqt );
		$dest_id = mysql_result ( $res, 0 );
		
		//création du transfert
		$num = $this->_creer_transfert( $expl_id, $deflt_docs_location, $dest_id, 0, '', 3, '', $transferts_retour_motif_transfert, 1, 0);
		
		$this->enregistre_validation($num);
		
		if ($transferts_retour_etat_transfert == "1")
			$this->enregistre_envoi($num);
		return $num;
	}
	
	//restaure la localisation apres une sauvegarde
	function retour_exemplaire_restaure_localisation($expl_id, $loc_id) {
		$rqt = "UPDATE exemplaires SET expl_location=".$loc_id." WHERE expl_id=".$expl_id;
		mysql_query ( $rqt );
	}
	
	//on supprime le transfert généré	
	function retour_exemplaire_supprime_transfert($expl_id, $idTrans) {
		$this->_restaure_statut($idTrans);
		$rqt = "DELETE FROM transferts WHERE id_transfert=" . $idTrans;
		mysql_query ( $rqt );
		$rqt = "DELETE FROM transferts_demande WHERE num_transfert=" . $idTrans;
		mysql_query ( $rqt );
	}
	
	//********************************************************************************************
	// pour la circulation
	//********************************************************************************************
	
	//enregistre la validation d'un exemplaire à partir de son cb
	function enregistre_validation_cb($cbEx) {
		$idTrans = $this->_explcb_2_transid ( $cbEx, 0,0 );
		if ($idTrans != 0) {
			$this->enregistre_validation ( $idTrans );
			$rqt = "SELECT  location_libelle  
				FROM transferts_demande,docs_location 
				WHERE num_location_dest=idlocation and num_transfert=".$idTrans." AND etat_demande=1";
			$res = mysql_query ( $rqt );
			$value = mysql_fetch_array ( $res );
			$this->new_location_libelle=$value[0];			
			return $cbEx;
		} else
			return false;
	}
	
	//enregistre la validation d'une liste de transferts
	function enregistre_validation($listeTransferts) {
		global $transferts_statut_validation;
		
		$tabTrans = explode ( ",", $listeTransferts );
		
		foreach ( $tabTrans as $transId ) {
			//pour chacun des transferts sélectionnés

			//on met a jour l'etat de la demande => on passe en validé
			$rqt = "UPDATE transferts INNER JOIN transferts_demande ON id_transfert=num_transfert 
					SET etat_demande=1, date_visualisee = NOW() 
					WHERE id_transfert=".$transId." AND etat_demande=0 ";
			mysql_query ( $rqt );

			//on recupere l'id de l'exemplaire
			$idExpl = $this->_transid_2_explid ( $transId, 1 );
			
			//on change le statut de l'exemplaire
			$this->_change_statut_exemplaire( $idExpl, $transferts_statut_validation);
		
		} // foreach
	
	}
	
	//enregistre le refus sur une liste de transfert
	function enregistre_refus($listeTransferts, $motif) {
		$tabTrans = explode ( ",", $listeTransferts );
		foreach ( $tabTrans as $transId ) {
			//pour chacun des transferts sélectionnés

			//on met a jour l'etat de la demande => on passe en validé
			$rqt = "UPDATE transferts INNER JOIN transferts_demande ON id_transfert=num_transfert 
					SET etat_demande = 4, date_visualisee = NOW(), motif_refus = '".$motif."' 
					WHERE id_transfert=".$transId." AND etat_demande<2 ";
			mysql_query ( $rqt );
		
			//on restaure le statut au cas ou il aurais été modifié...
			$this->_restaure_statut($transId);
		}
	}
	
	//valide l'envoi d'un exemplaire
	function enregistre_envoi_cb($cbEx) {
		$idTrans = $this->_explcb_2_transid ( $cbEx, 1 ,0);
		if ($idTrans != 0) {
			$this->enregistre_envoi ( $idTrans );
			$rqt = "SELECT  location_libelle  
				FROM transferts_demande,docs_location 
				WHERE num_location_dest=idlocation and num_transfert=".$idTrans." AND etat_demande=2";
			$res = mysql_query ( $rqt );
			$value = mysql_fetch_array ( $res );
			$this->new_location_libelle=$value[0];
			return $cbEx;
		} else
			return false;
	}
	
	//valide l'envoi d'une liste de transferts
	function enregistre_envoi($listeTransferts) {
		global $transferts_statut_transferts;
		global $transferts_validation_actif;
		global $transferts_statut_validation;
		
		$tabTrans = explode ( ",", $listeTransferts );
		
		foreach ( $tabTrans as $transId ) {
			//pour chacun des transferts sélectionnés
			$idExpl = $this->_transid_2_explid ( $transId, 1 ,0);
			
			if ( ($transferts_validation_actif == "1") && ($transferts_statut_validation != "0") )
				//si la validation est active et le changement de statut activé
				//on restaure le statut sauvegardé 
				$this->_restaure_statut($transId);
			
			//on change le statut et on le sauvegarde
			$this->_change_statut_exemplaire($idExpl, $transferts_statut_transferts, true ,$transId);
			
			//on met a jour l'etat de la demande => on passe en envoyé
			$rqt = "UPDATE transferts INNER JOIN transferts_demande ON id_transfert=num_transfert 
					SET etat_demande=2, date_envoyee=NOW() 
					WHERE id_transfert=".$transId." AND etat_demande = 1";
			mysql_query ( $rqt );
		}
	}
	
	//effectue la reception d'un exemplaire
	function enregistre_reception_cb($cbEx, $idStatut, $idSection) {
		$idTrans = $this->_explcb_2_transid ( $cbEx, 2 ,1);
		if ($idTrans != 0) {
			$this->enregistre_reception ( $idTrans, $idStatut, $idSection );
			return $cbEx;
		} else
			return false;
	}
	
	/*Autorise ou pas le prêt, et si transfert, on valide la reception
	 retourne:
		 1: Prêt interdit
		 2: prêt forcable
		 0: prêt ok
	*/
	function check_pret($cbEx,$force=0) {
		global $transferts_pret_statut_transfert,$msg;
		global $deflt_docs_location;
			
		$this->check_pret_error_message='';
		//on recupere l'id de l'exemplaire
		$rqt = "SELECT id_transfert, sens_transfert, num_location_source, num_location_dest
				FROM transferts, transferts_demande, exemplaires						
				WHERE id_transfert=num_transfert and num_expl=expl_id  and expl_cb='".$cbEx."' AND etat_demande=2" ;
		$res = mysql_query ( $rqt );
		if (mysql_num_rows($res)){	
			$obj_data = mysql_fetch_object($res);
			$rqt_loc = "SELECT  location_libelle FROM transferts_demande,docs_location	WHERE num_location_source=idlocation and num_transfert=".$obj_data->id_transfert;
			$res_loc = mysql_query ( $rqt_loc );
			$value = mysql_fetch_array ( $res_loc );
			$location_source_libelle=$value[0];
			$rqt_loc = "SELECT  location_libelle FROM transferts_demande,docs_location	WHERE num_location_dest=idlocation and num_transfert=".$obj_data->id_transfert;
			$res_loc = mysql_query ( $rqt_loc );
			$value = mysql_fetch_array ( $res_loc );
			$location_dest_libelle=$value[0];
			
			if(!$obj_data->sens_transfert && ($deflt_docs_location == $obj_data->num_location_source)) {
				// c'est un envoi, coté du propriétaire: l'exemplaire aurai dû partir...
				if(!$transferts_pret_statut_transfert) {
					// prêt interdit
					$this->check_pret_error_message=str_replace("!!dest_location!!",$location_dest_libelle, $msg["transferts_check_pret_erreur_envoi"]);
					return 1;
				}
				else {
					// forçable en prêt, on le laisse en transfert ?
					$this->check_pret_error_message=str_replace("!!dest_location!!",$location_dest_libelle, $msg["transferts_check_pret_erreur_envoi"]);
					return 2;
				}				
			}				
			if(!$obj_data->sens_transfert && ($deflt_docs_location == $obj_data->num_location_dest)) {
				// c'est un envoi, coté destinataire: l'exemplaire aurai dû être réceptionné avant un prêt...
				if($force) {
					$res_rcp = $this->enregistre_reception_cb($cbEx, 0, 0);
					$this->_restaure_statut($obj_data->id_transfert);
					if ($res_rcp==false) return 1; 
				} else {
					$this->check_pret_error_message=str_replace("!!source_location!!",$location_source_libelle, $msg["transferts_check_pret_erreur_reception"]);
					return 2; 	
				}
			}	
			if($obj_data->sens_transfert && ($deflt_docs_location == $obj_data->num_location_source)) {
				// c'est un retour, coté destinataire: l'exemplaire aurai du être retourné et non prêté...
				if(!$transferts_pret_statut_transfert) {
					// prêt interdit
					$this->check_pret_error_message=str_replace("!!dest_location!!",$location_source_libelle, $msg["transferts_check_pret_erreur_envoi"]);
					return 1;
				}
				else {
					// forçable en prêt, on le laisse en transfert ?
					$this->check_pret_error_message=str_replace("!!dest_location!!",$location_source_libelle, $msg["transferts_check_pret_erreur_envoi"]);
					return 2;
				}			
			}						
			if($obj_data->sens_transfert && ($deflt_docs_location == $obj_data->num_location_dest)) {
				// c'est un retour, coté du propriétaire: l'exemplaire aurai dû être réceptionné avant un prêt...
				if($force) {
					$res_rcp = $this->enregistre_reception_cb($cbEx, 0, 0);
					$this->_restaure_statut($obj_data->id_transfert);
					if ($res_rcp==false) return 1; 
				} else {
					$this->check_pret_error_message=str_replace("!!source_location!!",$location_dest_libelle, $msg["transferts_check_pret_erreur_reception"]);
					return 2; 	
				}					
			}							
		}
		return 0;
	}

	//effectue la reception d'une liste de transferts
	function enregistre_reception($listeTransferts, $idStatut, $listeSections) {
		global $deflt_docs_location;
		
		$tabTrans = explode ( ",", $listeTransferts );
		$tabSections =  explode ( ",", $listeSections );
		
		$idSection = current($tabSections);
		
		foreach ( $tabTrans as $transId ) {
			//on recupere l'id de l'exemplaire
			$noEx = $this->_transid_2_explid ( $transId, 2 );
			
			//le sens du transfert
			$rqt = "SELECT sens_transfert, type_transfert, origine, origine_comp 
					FROM transferts INNER JOIN transferts_demande ON id_transfert=num_transfert 
					WHERE id_transfert=".$transId." AND etat_demande = 2";
			$res = mysql_query ( $rqt );
			$value = mysql_fetch_array ( $res );
			$sensTrans = $value[0];
			$typeTrans = $value[1];
			$origine = $value[2];
			$origineComp = $value[3];
			
			if ($sensTrans == 1) {
				//c'est un retour !
				//on cloture le transfert 
				$rqt = 	"UPDATE transferts SET etat_transfert=1 WHERE id_transfert=".$transId;
				mysql_query ( $rqt );
				
				if ($typeTrans == 1) {
					//si c'est un aller/retour
					//on restaure la localisation sauvegardé de l'exemplaire
					$this->_restaure_localisation($transId);
				}				
				if($idSection){					
					$rqt = "UPDATE exemplaires INNER JOIN transferts_demande ON num_expl=expl_id INNER JOIN transferts ON id_transfert=num_transfert 
								SET expl_section=".$idSection." 
								WHERE id_transfert=".$transId." AND etat_demande = 2";
					mysql_query ( $rqt );
				} else {
					//on restaure le section sauvegardé de l'exemplaire
					$this->_restaure_section($transId);
				}				
				if($idStatut) {
					//on met à jour le statut et la localisation de l'exemplaire
					$rqt = "UPDATE exemplaires INNER JOIN transferts_demande ON num_expl=expl_id INNER JOIN transferts ON id_transfert=num_transfert 
							SET expl_statut=".$idStatut.", expl_location = num_location_dest 
							WHERE id_transfert=".$transId." AND etat_demande = 2";
					mysql_query ( $rqt );
				} else {
					//on restaure le statut sauvegardé de l'exemplaire
					$this->_restaure_statut($transId);
				}
				
			} else {
				//c'est un transfert
				
				// aller simple ?
				if ($typeTrans == 0) {
					//on cloture le transfert => pas de gestion du retour
					$rqt = 	"UPDATE transferts SET etat_transfert=1 WHERE id_transfert=".$transId;
					mysql_query ( $rqt );
				
				} else {
					//c'est l'aller donc
					if ($origine==4 ) {
						//c'est un transfert suite a une resa donc
						//on recupere le cb pour
						$explcb = $this->_transid_2_explcb($transId,2);
					}
					$id_section = $idSection;
					if ($idSection==0) {
						//chercher la meme section dans le nouveau site
						$rqt = "SELECT idsection 
								FROM exemplaires INNER JOIN docs_section ON expl_section=idsection INNER JOIN docsloc_section ON idsection=num_section 
								WHERE expl_id=".$noEx." AND num_location=".$deflt_docs_location;
						$res = mysql_query($rqt);
						if (mysql_num_rows($res)==0) {
							//la section n'existe pas pour cette localisation !
							//on cherche la premiere section dispo
							$rqt = "SELECT idsection 
									FROM docs_section INNER JOIN docsloc_section ON idsection=num_section 
									WHERE num_location=".$deflt_docs_location." LIMIT 1";
							$res = mysql_query($rqt);
							$id_section = mysql_result($res,0);
						} else 
							$id_section = mysql_result($res,0);
					}
					
					$rqt = "UPDATE exemplaires INNER JOIN transferts_demande ON num_expl=expl_id INNER JOIN transferts ON id_transfert=num_transfert 
							SET expl_section=".$id_section." 
							WHERE id_transfert=".$transId." AND etat_demande = 2";
					mysql_query ( $rqt );
					
				} //fin du else de if ($typeTrans == 0)
				
				//on met à jour le statut et la localisation de l'exemplaire
				if($idStatut) {
					$rqt = "UPDATE exemplaires INNER JOIN transferts_demande ON num_expl=expl_id INNER JOIN transferts ON id_transfert=num_transfert 
							SET expl_statut=".$idStatut.", expl_location = num_location_dest 
							WHERE id_transfert=".$transId." AND etat_demande = 2";
					mysql_query ( $rqt );
				}else {
					//on restaure le statut sauvegardé de l'exemplaire
					$this->_restaure_statut($transId);
				}
				// Traitement de la résa				
				if ($origine==4 && $typeTrans) {
					//c'est un transfert suite a une resa donc
					//valider la resa 
					$id_resa_validee = affecte_cb($explcb,$origineComp);
					//on genere la lettre de confirmation
					alert_empr_resa($id_resa_validee); 				
				}
			
			} //fin du else de if ($sensTrans == 0)
			
			//on met a jour l'etat de la demande => on passe en receptionné et terminer
			$rqt = "UPDATE transferts INNER JOIN transferts_demande ON id_transfert=num_transfert 
					SET etat_demande=3, date_reception=NOW() 
					WHERE id_transfert=".$transId." AND etat_demande = 2";
			mysql_query ( $rqt );
			
			//on passe à la section suivante
			$idSection = next($tabSections);
			
		} //fin du while
	
	}
	
	//lance le retour d'un exemplaire
	function enregistre_retour_cb($cbEx) {
		$idTrans = $this->_explcb_2_transid ( $cbEx, 3, 1 );
		if ($idTrans != 0) {
			$this->enregistre_retour ( $idTrans );
			$rqt = "SELECT  location_libelle  
				FROM transferts_demande,docs_location 
				WHERE num_location_source=idlocation and num_transfert=".$idTrans." AND etat_demande=5";
			$res = mysql_query ( $rqt );
			$value = mysql_fetch_array ( $res );
			$this->new_location_libelle=$value[0];
			return $cbEx;
		} else
			return false;
	
	}
	
	//effectue le retour d'une liste de transferts
	function enregistre_retour($listeTransferts) {
		global $transferts_statut_transferts;
		$tabTrans = explode ( ",", $listeTransferts );
		foreach ( $tabTrans as $transId ) {
			//on met a jour l'etat de la demande => on passe en receptionné et terminer
			$rqt = "UPDATE transferts INNER JOIN transferts_demande ON id_transfert=num_transfert 
					SET etat_demande=5 
					WHERE id_transfert=".$transId." AND etat_demande=3";
			mysql_query ( $rqt );
			
			//on recupere les infos de la demande de l'aller
			$rqt = "SELECT num_location_source, num_location_dest, num_expl 
					FROM transferts_demande 
					WHERE num_transfert=".$transId." AND etat_demande=5";
			$res = mysql_query ( $rqt );
			$value = mysql_fetch_array ( $res );
			
			//on insert l'information d'envoi du retour
			$rqt = "INSERT INTO transferts_demande (num_transfert, date_creation, sens_transfert, num_location_source, 
						num_location_dest, num_expl, etat_demande, date_visualisee, date_envoyee) VALUES (". 
						$transId.", NOW(), 1, $value[1], $value[0], $value[2], 2, NOW(), NOW())";
			mysql_query ( $rqt );
			
			//on met à jour le statut de l'exemplaire avec l'etat défini pour la validation
			$rqt = "UPDATE exemplaires SET expl_statut=".$transferts_statut_transferts." 
					WHERE expl_id=".$value[2];
			mysql_query ( $rqt );
		}
	}

	//change la date de retour d'un transfert
	function change_date_retour($idTransfert,$date_retour) {
		$rqt = "UPDATE transferts SET date_retour='".$date_retour."' WHERE id_transfert=".$idTransfert;
		mysql_query ( $rqt );
	}
	
	//cloture un ou plusieurs transferts
	function cloture_transferts($listeTransferts) {
		global $transferts_statut_transferts;
		
		$tabTrans = explode ( ",", $listeTransferts );
		
		foreach ( $tabTrans as $transId ) {	
			//on cloture le transfert
			$rqt = 	"UPDATE transferts SET etat_transfert=1 WHERE id_transfert=".$transId;
			mysql_query ( $rqt );
		}
	}
	
	function ajoute_demande($transId, $source, $motif, $dateRetour) {
		global $deflt_docs_location;
		global $transferts_validation_actif;
	
		//on met a jour l'etat de la demande => on passe en refus traité et la date de retour souhaitée
		$rqt = "UPDATE transferts INNER JOIN transferts_demande ON id_transfert=num_transfert 
				SET etat_demande=6, date_retour='".$dateRetour."', motif='$motif'
				WHERE id_transfert=".$transId." AND etat_demande=4";
		mysql_query ( $rqt );

		//recuperation des informations pour déterminer le nouveau no d'exemplaire
		$rqt = "SELECT num_notice, num_bulletin 
				FROM transferts 
				WHERE id_transfert=".$transId;
		$res = mysql_query ( $rqt );
		$value = mysql_fetch_array ( $res );
		
		//on a besoin du no d'exemplaire pour la source donnée
		$rqt = "SELECT expl_id 
				FROM exemplaires 
				WHERE expl_notice=".$value[0]." AND expl_bulletin=".$value[1]." AND expl_location=".$source;
		$id_expl = mysql_result(mysql_query($rqt),0);		
		
		//la table transferts_demande
		$rqt = "INSERT INTO transferts_demande (num_transfert, date_creation, sens_transfert, num_location_source, num_location_dest, num_expl, etat_demande) 
				VALUES (".$transId.", NOW(), 0, ".$source.", ".$deflt_docs_location.", ".$id_expl.", 0)";
		mysql_query ( $rqt );
		// $num pas initialisé ?????????????
		if ($transferts_validation_actif == "0")
			//pas d'étape de validation => etape d'envoi direct
			$this->enregistre_validation($num);
		
	}
	
	//********************************************************************************************
	// pour les réservations
	//********************************************************************************************
	
	function transfert_pour_resa($cb_expl,$dest,$resa_id) {
		global $transferts_resa_etat_transfert;
		global $transferts_resa_motif_transfert;
		global $transferts_nb_jours_pret_defaut;
	
		//récuperation des infos de l'exemplaire
		$rqt = "SELECT expl_id, expl_location FROM exemplaires WHERE expl_cb='".$cb_expl."'";
		$res = mysql_query($rqt);
		$obj_expl = mysql_fetch_object($res);
		
		//generation de la date de retour par défaut
		$date_retour = mktime(0, 0, 0, date("m"), date("d")+$transferts_nb_jours_pret_defaut, date("Y"));
		$date_retour_mysql = date("Y-m-d", $date_retour);
			
		//génération du transfert
		$num = $this->_creer_transfert( $obj_expl->expl_id, $obj_expl->expl_location, $dest, 1, $date_retour_mysql, 4, $resa_id, $transferts_resa_motif_transfert);	

		if ($transferts_resa_etat_transfert == "1")
			//on valide
			$this->enregistre_validation($num);

		// lier la résa au transfert	
		$rqt = "UPDATE transferts_demande SET resa_trans=$resa_id WHERE num_transfert=$num and num_expl='".$obj_expl->expl_id."' and etat_demande=0 ";
		mysql_query ( $rqt );

	}

	//********************************************************************************************
	// pour l'affichage des exemplaires
	//********************************************************************************************

	// dit si un exemplaire est transférable.
	function est_transferable($expl) {
		global $deflt_docs_location;
		global $transferts_transfert_transfere_actif;
		global $PMBuserid;
		
		$rqt = "SELECT expl_location, transfert_location_origine, transfert_flag 
				FROM exemplaires INNER JOIN docs_statut ON expl_statut=idstatut 
				WHERE expl_id=".$expl;
		$res = mysql_query ($rqt) or die (mysql_error()."<br /><br />".$rqt);
		$value = mysql_fetch_array ($res);
		$loc_expl = $value[0];
		$loc_expl_ori = $value[1];
		$trans_aut = $value[2];
		
		//on verifie que le pret est autorisé
		if ($trans_aut==0)	return false;
		
		// si l'exemplaire est ici: pas transférable	
		if ($deflt_docs_location == $loc_expl)	return false;
/*		
		//on verifie que l'exemplaire n'est pas déja sur le site de l'utilisateur
		if ($deflt_docs_location != $loc_expl) {
			
			//si les transferts d'exemplaires deja transféré ne sont pas autorisés
			if ($transferts_transfert_transfere_actif == "0") {
				//si ce n'est pas la localisation d'origine
				if ($loc_expl != $loc_expl_ori) {
					//si la localisation d'origine n'a pas la valeure par défaut(0)
					if ($loc_expl_ori != 0)
						return false;
				}
			}
		} else
			return false;
*/	
		$rqt = "SELECT COUNT(1) FROM pret WHERE pret_idexpl=".$expl; 
		$res = mysql_query ( $rqt );
		if (mysql_result ( $res, 0 ) )return false;
					
		//on verifie qu'un transfert n'est pas déja demande
		$rqt = "SELECT COUNT(1) 
				FROM transferts INNER JOIN transferts_demande ON id_transfert=num_transfert 
				WHERE etat_transfert=0 AND num_expl=".$expl." AND etat_demande<4";
		$res = mysql_query ( $rqt );
		$nbTrans = mysql_result ( $res, 0 );
		
		if ($nbTrans != 0)
			return false;
		
		return true;
	}
	
	// dit si un exemplaire est doit doit faire l'objet d'un retour
	function est_retournable($expl) {
		global $deflt_docs_location;
		global $msg;
		
		$rqt = "SELECT id_transfert, sens_transfert, num_location_source, num_location_dest,expl_location
			FROM transferts, transferts_demande, exemplaires						
			WHERE id_transfert=num_transfert and num_expl=expl_id  and num_expl='".$expl."' AND etat_demande=3 and etat_transfert=0" ;
		$res = mysql_query ( $rqt );
		if (mysql_num_rows($res)){	
			$obj_data = mysql_fetch_object($res);
			
			$rqt_loc = "SELECT  location_libelle FROM transferts_demande,docs_location	WHERE num_location_source=idlocation and num_transfert=".$obj_data->id_transfert;
			$res_loc = mysql_query ( $rqt_loc );		
			$obj_loc = mysql_fetch_object($res_loc);
	
			$this->location_libelle_source=$obj_loc->location_libelle;
			return(true);
		}	
	}	
	
	//genere une demande de transfert
	function creer_transfert_catalogue($expl_id, $dest_id, $date_ret, $motif) {
		global $transferts_validation_actif;

		//on recupere les informations manquantes sur l'exemplaire
		$rqt = "SELECT expl_location FROM exemplaires WHERE expl_id=".$expl_id;
		$res = mysql_query ( $rqt );
		$src_id = mysql_result($res,0);
		
		//on creer le transfert
		$num = $this->_creer_transfert( $expl_id, $src_id, $dest_id, 1, $date_ret, 3, '', $motif, 0, 0 );

		if ($transferts_validation_actif == "0")
			//pas d'étape de validation => etape d'envoi
			$this->enregistre_validation($num);
		
	}

	//********************************************************************************************
	// pour l'administration
	//********************************************************************************************
	
	//enregistre les parametres
	function admin_enregistre_params($tab_param) {
		
		foreach ( $tab_param as $param ) {
			$varGlobal = $param["prefix"]."_".$param["nom"];
			global $$varGlobal;
			global $$param["champ"];
			
			$val_saisie = $$param["champ"];
			
			//on enregistre dans la variable globale
			$$varGlobal = stripslashes($val_saisie);
			//puis dans la base
			$rqt = "UPDATE parametres SET valeur_param='".$val_saisie."' 
					WHERE type_param='".$param["prefix"]."' AND sstype_param='".$param["nom"]."'";
			mysql_query ( $rqt );
		}
	}
	
	//change l'ordre de la localisation
	function admin_enregistre_ordre_localisation($sens, $id) {
		
		//on recuper l'ordre
		$rqt = "SELECT transfert_ordre FROM docs_location WHERE idlocation=".$id;
		$ordreBase = mysql_fetch_array ( mysql_query ( $rqt ) );
		
		//on recupere l'id de la 2eme localisation
		$rqt = "SELECT idLocation FROM docs_location WHERE transfert_ordre=".($ordreBase[0] + $sens);
		$idSecond = mysql_fetch_array ( mysql_query ( $rqt ) );
		
		//on met a jour l'ordre
		$rqt = "UPDATE docs_location SET transfert_ordre=".($ordreBase[0] + $sens)." WHERE idLocation=".$id;
		mysql_query ( $rqt );
		
		//puis celui du 2eme
		$rqt = "UPDATE docs_location SET transfert_ordre=".$ordreBase[0]." WHERE idLocation=".$idSecond[0];
		mysql_query ( $rqt );
	}
	
	//enregistre le nouveau statut par défaut d'un site
	function admin_enregistre_statuts_defaut($id, $statut) {
		//on met à jour l'enregistrement
		$rqt = "UPDATE docs_location SET transfert_statut_defaut=".$statut." WHERE idlocation=".$id;
		mysql_query ( $rqt );
	}
	
	//purge l'historique des transferts
	function admin_purge_historique($datefin) {
		$rqt = "DELETE transferts.*, transferts_demande.* 
				FROM transferts INNER JOIN transferts_demande 
				WHERE transferts.etat_transfert=1 AND transferts.date_creation<'".$datefin."' AND num_transfert=id_transfert";
		mysql_query ( $rqt );
	}

}

?>