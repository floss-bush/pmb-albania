<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: recouvr_prix.class.php,v 1.1 2010-09-03 07:11:30 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

class recouvr_prix{
	
	var $id_element = 0;
	var $champ_entree = "";
	var $champ_sortie = "";
	var $display="";
	var $idobjet = 0;
	
	function recouvr_prix($id_elt,$fieldElt){
		global $quoifaire;
		
		$this->id_element = $id_elt;
		$format_affichage = explode('/',$fieldElt);
		$this->champ_entree = $format_affichage[0];
		if($format_affichage[1]) $this->champ_sortie = $format_affichage[1];		
		$ids = explode("_",$id_elt);
		$this->idobjet = $ids[1];
		
		switch($quoifaire){
			
			case 'edit':
				$this->make_display();
				break;
			case 'save':
				$this->update();
				break;
		}
	}
	
	function make_display(){
		global $msg, $charset,$pmb_gestion_devise,$dbh;

		$rqt="select montant, recouvr_type from recouvrements where recouvr_id='".$this->idobjet."'";
		$res = mysql_query($rqt,$dbh);
		$act = mysql_fetch_object($res);
		
		$display ="";
		$submit = "<input type='submit' class='bouton' name='soumission' id='soumission' value='".$msg['demandes_valid_progression']."'/>";
		switch($this->champ_entree){			
			case 'text':
				$display = "<form method='post' name='edition'><input type='text' class='saisie-5em' id='save_".$this->id_element."' name='save_".$this->id_element."' value='".htmlentities($act->cout,ENT_QUOTES,$charset)."' />$submit</form>
				<script type='text/javascript' >document.forms['edition'].elements['save_".$this->id_element."'].focus();</script>";
				break;
			default:
				$display = "<label id='".$this->id_element."' />".htmlentities($act->cout,ENT_QUOTES,$charset)."</label>";
				break;
		}
		$this->display = $display;
	}
	
	function update(){
		
		global $dbh, $recouvr_prix, $pmb_gestion_devise;		
		
		$req = "update recouvrements set montant='".$recouvr_prix."' where recouvr_id='".$this->idobjet."'";
		mysql_query($req,$dbh);
		if(!$recouvr_prix)$recouvr_prix="0.00";
		switch($this->champ_sortie){
			default :
				if(strpos($recouvr_prix,$pmb_gestion_devise) !== false)
					$this->display = $recouvr_prix;
				else $this->display = $recouvr_prix." ".$pmb_gestion_devise;
			break;
		}
	
	}
}
?>