<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: cout.class.php,v 1.2 2010-02-23 16:27:22 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

class cout{
	
	var $id_element = 0;
	var $champ_entree = "";
	var $champ_sortie = "";
	var $display="";
	var $idobjet = 0;
	
	function cout($id_elt,$fieldElt){
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
		
		$rqt = "select cout from demandes_actions where id_action='".$this->idobjet."'";
		$res = mysql_query($rqt,$dbh);
		$act = mysql_fetch_object($res);
		
		$display ="";
		$submit = "<input type='submit' class='bouton' name='soumission' id='soumission' value='".$msg['demandes_valid_progression']."'/>";
		switch($this->champ_entree){			
			case 'text':
				$display = "<form method='post'><input type='text' class='saisie-5em' id='save_".$this->id_element."' name='save_".$this->id_element."' value='".htmlentities($act->cout,ENT_QUOTES,$charset)."' />$submit</form>";
				break;
			default:
				$display = "<label id='".$this->id_element."' />".htmlentities($act->cout,ENT_QUOTES,$charset)."</label>";
				break;
		}
		$this->display = $display;
	}
	
	function update(){
		
		global $dbh, $cout, $pmb_gestion_devise;		
		
		$req = "update demandes_actions set cout='".$cout."' where id_action='".$this->idobjet."'";
		mysql_query($req,$dbh);
		
		switch($this->champ_sortie){
			default :
				if(strpos($cout,$pmb_gestion_devise) !== false)
					$this->display = $cout;
				else $this->display = $cout.$pmb_gestion_devise;
			break;
		}
	}
}
?>