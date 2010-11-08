<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: progressiondemande.class.php,v 1.2 2010-02-23 16:27:22 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

class progressiondemande{
	
	var $id_element = 0;
	var $champ_entree = "";
	var $champ_sortie = "";
	var $display="";
	var $idobjet = 0;
	
	function progressiondemande($id_elt,$fieldElt){
		global $quoifaire;
		
		$this->id_element = $id_elt;
		$format_affichage = explode('/',$fieldElt);
		$this->champ_entree = $format_affichage[0];
		if($format_affichage[1]) $this->champ_sortie = $format_affichage[1];		
		$ids = explode("_",$id_elt);
		$this->idobjet = $ids[1];
		
	}
	
	function make_display(){
		global $msg, $dbh,$charset;
		
		$rqt = "select progression from demandes where id_demande='".$this->idobjet."'";
		$res = mysql_query($rqt,$dbh);
		$act = mysql_fetch_object($res);
		
		$display ="";
		$submit = "<input type='submit' class='bouton' name='soumission' id='soumission' value='".$msg['demandes_valid_progression']."'/>";
		switch($this->champ_entree){			
			case 'text':
				$display = "<form method='post'><input type='text' class='saisie-5em' id='save_".$this->id_element."' name='save_".$this->id_element."' value='".htmlentities($act->progression,ENT_QUOTES,$charset)."' />$submit</form>";
				break;
			case 'img';
				$display = "<form method='post'><input type='text' class='saisie-5em' id='save_".$this->id_element."' name='save_".$this->id_element."' value='".htmlentities($act->progression,ENT_QUOTES,$charset)."' />$submit</form>";
				break;
			default:
				$display = "<label id='".$this->id_element."' />".htmlentities($act->progression,ENT_QUOTES,$charset)."</label>";
				break;
		}		
		$this->display = $display;
	}
	
	function update(){
		
		global $dbh, $progressiondemande;		
		
		$req = "update demandes set progression='".$progressiondemande."' where id_demande='".$this->idobjet."'";
		mysql_query($req,$dbh);
		
		switch($this->champ_sortie){
			case 'img':
				$this->display = "<img src=\"./images/jauge.png\" height='15px' width=\"".$progressiondemande."%\" title='".$progressiondemande."%' />";
				break;
			default:
				$this->display = $progressiondemande."%";
				break;
		}
				
	}
}
?>