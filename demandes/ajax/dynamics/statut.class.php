<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: statut.class.php,v 1.2 2010-02-23 16:27:22 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/demandes_actions.class.php");

class statut{
	
	var $id_element = 0;
	var $champ_entree = "";
	var $champ_sortie = "";
	var $display="";
	var $idobjet = 0;
	
	function statut($id_elt,$fieldElt){
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
		global $msg, $dbh,$charset;
		
		$display ="";
		$submit = "<input type='submit' class='bouton' name='soumission' id='soumission' value='".$msg['demandes_valid_progression']."'/>";
		$action = new demandes_actions($this->idobjet);
		switch($this->champ_entree){			
			case 'selector':
				$display = "
				<form method='post'>".$action->getStatutSelector($action->statut_action,true).$submit."</form>";
				break;
			default:
				$display = "<label id='".$this->id_element."' />".htmlentities($action->statut_action,ENT_QUOTES,$charset)."</label>";
				break;
		}
		$this->display = $display;
	}
	
	function update(){		
		global $dbh, $statut;		
		
		$req = "update demandes_actions set statut_action='".$statut."' where id_action='".$this->idobjet."'";
		mysql_query($req,$dbh);
		
		
		$action = new demandes_actions($this->idobjet);
		$display = "";
		switch($this->champ_sortie){
			default:
				for($i=1;$i<count($action->list_statut)+1;$i++){
					if($action->list_statut[$i]['id'] == $statut){	
						$display =  $action->list_statut[$i]['comment'];
						break;
					}
				}
			break;
		}
		
		$this->display = $display;		
	}
}
?>