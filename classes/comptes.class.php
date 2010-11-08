<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: comptes.class.php,v 1.8 2007-03-10 09:25:48 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

define(CMPTE_INIT,1);
define(CMPTE_CREATE,2);
define(CMPTE_REC_TRANSACTION,3);
define(CMPTE_VALIDATE_TRANSACTION,4);
define(CMPTE_UPDATE_SOLDE,5);

class comptes {

	var $id_compte; //Identifiant du compte en cours
	var $typ_compte; //Type de compte en cours de traitement
	var $compte; //Informations du compte
	
	var $error=false;
	var $error_message="";
	var $error_action=0;
	
    function comptes($id_compte="") {
    	global $msg;
    	
    	if ($id_compte) {
    		//Vérification que le compte existe
    		$requete="select id_compte,type_compte_id from comptes where id_compte='".$id_compte."'";
    		$resultat=mysql_query($requete);
    		if (@mysql_num_rows($resultat)) { 
    			$this->id_compte=$id_compte;
    			$this->typ_compte=mysql_result($resultat,0,1);
    		} else {
    			$this->error=true;
    			$this->error_message=sprintf($msg["cmpt_bad_id"],$id_compte);
    			$this->error_action=CMPTE_INIT;
    		}	
    	} else $this->id_compte="";
    }
    
    function is_typ_compte($typ_compte) {
    	$requete="select * from type_comptes where id_type_compte='".$typ_compte."'";
    	$resultat=mysql_query($requete);
    	if (mysql_num_rows($resultat)) {
    		$this->typ_compte=mysql_fetch_object($resultat);
    		return true; 
    	} else return false;
    }
    
   function is_valid() {
   		if (($this->id_compte)&&(!$error)) return true; else return false;
   }
    
    function must_be_unique() {
    	if ($this->typ_compte->multiple==0) return true; else return false;
    }
    
    function create_compte($libelle,$typ_compte,$proprio_id,$droits) {
    	global $msg;
    	
    	//Vérification validité du type de compte
    	if (!is_typ_compte($typ_compte)) {
    		$this->error=true;
    		$this->error_message=sprintf($msg["cmpt_bad_typ_compte"],$typ_compte);
    		$this->error_action=CMPTE_CREATE;
    		return false;
    	}  else {
    		//Vérification propriétaire
    		
    		//Vérification unicité si nécessaire
    		if (must_be_unique()) {
    			//Y-a-t-il déjà un compte existant pour ce propriétaire ?
    			$requete="select count(1) from comptes where type_compte_id='".$typ_compte."' and proprio_id='".$proprio_id."'";
    			$resultat=mysql_query($requete);
    			if (mysql_result($resultat,0,0)) {
    				$this->error=true;
    				$this->error_message=sprintf($msg["cmpt_not_unique"],$typ_compte,$proprio_id);
    				$this->error_action=CMPTE_CREATE;
    				return false;
    			}
    		}
    		//Création
    		$requete="insert into comptes (libelle,type_compte_id,proprio_id,droits) values('".addslashes($libelle)."',$typ_compte,$proprio_id,'".addslashes($droits)."')";
    		$resultat=mysql_query($requete);
    		if (!$resultat) {
    			$this->error=true;
    			$this->error_message=$msg["cmpt_create_failed"];
    			$this->error_action=CMPTE_CREATE;
    			return false;
    		}
    		
    		//Lecture des infos comptes
    		$this->id_compte=mysql_insert_id();
    		$requete="select * from comptes where id_compte=".$this->id_compte;
    		$resultat=mysql_query($requete);
    		$this->compte=mysql_fetch_object($resultat);
    	}
    	return true;
    }
    
    function record_transaction($date_prevue,$montant,$sens,$comment="",$encaissement=0) {
    	global $msg;
    	global $PMBuserid, $PMBusername;
    	if ($this->is_valid()) {
    		//Vérification du sens
    		if (($sens!=-1)&&($sens!=1)) {
    			$this->error=false;
    			$this->error_message=$msg["cmpt_bad_sens"];
    			$this->error_action=CMPTE_REC_TRANSACTION;
    			return false;
    		}
    		//Récupération des infos annexes
    		$machine=$_SERVER["REMOTE_ADDR"];
    		if (!$date_prevue) $date_prevue=date("Y-m-d");
    		$requete="insert into transactions (compte_id,user_id,user_name,machine,date_enrgt,date_prevue,montant,sens,commentaire,encaissement) values(".$this->id_compte.",$PMBuserid,'".addslashes($PMBusername)."','$machine',now(),'".$date_prevue."','$montant',$sens,'".addslashes($comment)."',$encaissement)";
    		if (!mysql_query($requete)) {
    			$this->error=true;
    			$this->error_message=sprintf($msg["cmpt_query_transaction_failed"],mysql_error());
    			$this->error_action=CMPTE_REC_TRANSACTION;
    			return false;
    		}
    		return mysql_insert_id();
    	} else return false;
    }
    
    function is_transaction_validate($id_transaction) {
    	$requete="select count(*) from transactions where id_transaction=$id_transaction and realisee=1";
    	$resultat=mysql_query($requete);
    	if (@mysql_result($resultat,0,0)) return true; else return false;
    }
    
    function transaction_exists($id_transaction) {
    	$requete="select count(*) from transactions where id_transaction=$id_transaction and compte_id=".$this->id_compte;
    	$resultat=mysql_query($requete);
    	if (@mysql_result($resultat,0,0)) return true; else return false;
    }
    
    function validate_transaction($id_transaction) {
    	global $msg;
    	if ($this->is_valid()) {
	    	if ($this->transaction_exists($id_transaction)) {
	    		if (!$this->is_transaction_validate($id_transaction)) {
		 	   		$requete="update transactions set realisee=1, date_effective=now() where id_transaction=$id_transaction";
 	  		 		mysql_query($requete);
	    			return true;
	    		} else {
	    			$this->error=false;
   		 			$this->error_message=sprintf($msg["cmpt_transaction_already_validate"],$id_transaction);
   		 			$this->error_action=CMPTE_VALIDATE_TRANSACTION;
   		 			return false;
	    		}
  	 	 	} else {
   		 		$this->error=false;
   		 		$this->error_message=sprintf($msg["cmpt_transaction_does_not_exists"],$id_transaction);
   		 		$this->error_action=CMPTE_VALIDATE_TRANSACTION;
	    		return false;
   		 	}
    	} else return false;
    }
    
    function delete_transaction($id_transaction) {
    	global $msg;
    	if ($this->is_valid()) {
	    	if ($this->transaction_exists($id_transaction)) {
	    		if (!$this->is_transaction_validate($id_transaction)) {
		 	   		$requete="delete from transactions where id_transaction=$id_transaction";
 	  		 		mysql_query($requete);
	    			return true;
	    		} else {
	    			$this->error=false;
   		 			$this->error_message=sprintf($msg["cmpt_transaction_already_validate"],$id_transaction);
   		 			$this->error_action=CMPTE_VALIDATE_TRANSACTION;
   		 			return false;
	    		}
  	 	 	} else {
   		 		$this->error=false;
   		 		$this->error_message=sprintf($msg["cmpt_transaction_does_not_exists"],$id_transaction);
   		 		$this->error_action=CMPTE_VALIDATE_TRANSACTION;
	    		return false;
   		 	}
    	} else return false;
    }
    
    function summarize_transactions($date_debut,$date_fin,$sens=0,$realisee=1) {
    	global $msg;
    	if ($this->is_valid()) {
    		if ($date_debut) $date_debut_terme=" and date_effective>='$date_debut'";
    		if ($date_fin) $date_fin_terme=" and date_effective<='$date_fin'";
    		if (($sens==-1)||($sens==1)) $sens_terme=" and sens=$sens";
    		if ($realisee!=-1) $realisee_terme=" and realisee=$realisee";
    		$requete="select sum(montant*sens) from transactions where compte_id=".$this->id_compte.$date_debut_terme.$date_fin_terme.$sens_terme.$realisee_terme;
    		$resultat=mysql_query($requete);
    		$montant=@mysql_result($resultat,0,0);
    		return $montant;
    	} else return false;
    }
    
    function get_transactions($date_debut,$date_fin,$sens=0,$realisee=-1, $limit=0, $order="desc") {
    	if ($this->is_valid()) {
    		if ($date_debut) $date_debut_terme=" and date_enrgt>='$date_debut'";
    		if ($date_fin) $date_fin_terme=" and date_enrgt<='$date_fin'";
    		if (($sens==-1)||($sens==1)) $sens_terme=" and sens=$sens";
    		if ($realisee!=-1) $realisee_terme=" and realisee=$realisee";
    		$requete="select * from transactions where compte_id=".$this->id_compte.$date_debut_terme.$date_fin_terme.$sens_terme.$realisee_terme." order by date_enrgt $order";
    		if ($limit) $requete.=" limit $limit";
    		$resultat=mysql_query($requete);
    		while ($r=mysql_fetch_object($resultat)) {
    			$t[]=$r;
    		}
    		return $t;
    	} else return false;
    }
    
    function update_solde() {
    	global $msg ;
    	if ($this->is_valid()) {
    		$solde=$this->summarize_transactions("","",0,1);
    		if ($solde=="") $solde=0;
    		if ($solde!==false) {
    			$requete="update comptes set solde=".$solde." where id_compte=".$this->id_compte;
    			$update=mysql_query($requete);
    			if (!$update) {
    				$this->error=false;
   		 			$this->error_message=sprintf($msg["cmpt_update_solde_query_failed"],mysql_error());
   		 			$this->error_action=CMPTE_UPDATE_SOLDE;
    			} else return $solde;
    		} else {
    			$this->error=false;
   		 		$this->error_message=$msg["cmpt_update_solde_summarize_failed"];
   		 		$this->error_action=CMPTE_UPDATE_SOLDE;
    		}
    	} else return false;
    }
    
    function get_compte_id_from_empr($empr_id,$typ_compte) {
    	$requete="select id_compte from comptes where proprio_id='$empr_id' and type_compte_id='".$typ_compte."'";
    	$resultat=mysql_query($requete);
    	if (@mysql_num_rows($resultat)==0) {
    		//Compte inexistant : création
    		$requete="insert into comptes (libelle,type_compte_id,solde,prepay_mnt,proprio_id) values('Created on ".date("Y-m-d")."',$typ_compte,0,0,$empr_id)";
     		$r=mysql_query($requete);
    		if ($r) return mysql_insert_id(); else return false;
    	}
    	if (@mysql_num_rows($resultat)>1) return false;
    	return mysql_result($resultat,0,0);
    }
    
    function get_empr_from_compte_id() {
    	$requete="select proprio_id from comptes where id_compte=".$this->id_compte;
    	$resultat=mysql_query($requete);
    	if (@mysql_num_rows($resultat)) return mysql_result($resultat,0,0); else return false;
    }
    
    function format($f) {
    	global $pmb_gestion_devise;
    	$neg="<span class='erreur'>%s %s</span>";
		$pos="%s %s";
    	return sprintf($f<0?$neg:$pos,sprintf("%01.2f",$f),$pmb_gestion_devise);
    }
    
    function format_simple($f) {
    	global $pmb_gestion_devise;
 		$pos="%s %s";
    	return sprintf($pos,sprintf("%01.2f",$f),$pmb_gestion_devise);
    }
    
    function get_typ_compte_lib($id_typ_compte) {
    	global $msg;
    	$r="";
    	switch ($id_typ_compte) {
    		case 1:
    			$r=$msg["finance_cmpte_abt"];
    			break;
    		case 2:
    			$r=$msg["finance_cmpte_amendes"];
    			break;
    		case 3:
    			$r=$msg["finance_cmpte_prets"];
    			break;
    		default:
    			$requete="select libelle from type_comptes where id_type_compte=".$id_typ_compte;
    			$resultat=mysql_query($requete);
    			if (@mysql_num_rows($resultat)) $r=mysql_result($resultat,0,0);
    	}
    	return $r;
    }
    
    function get_solde() {
    	if ($this->is_valid()) {
    		$requete="select solde from comptes where id_compte=".$this->id_compte;
    		$resultat=mysql_query($requete);
    		if (@mysql_num_rows($resultat)) return mysql_result($resultat,0,0); else return false;
    	} else return false;
    }
    
    function frais_relance($niveau) {
    	global $finance_relance_1, $finance_relance_2, $finance_relance_3;
    	
    	$frais=0;
    	
    	if ($niveau>0) $frais+=$finance_relance_1;
    	if ($niveau>1) $frais+=$finance_relance_2;
    	if ($niveau>2) $frais+=$finance_relance_3;
    	
    	return $frais;
    }
}
?>