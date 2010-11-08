<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: classements.class.php,v 1.4 2007-03-10 09:25:48 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

// définition de la classe de gestion des classements de la DSI

class classement {
// propriétés
var $id_classement ;
var $nom_classement = '';
var $type_classement = 'BAN';

// ---------------------------------------------------------------
//	constructeur
// ---------------------------------------------------------------
function classement ($id_classement=0) {
	$this->id_classement = $id_classement;
	$this->getData();
	}

// ---------------------------------------------------------------
function getData() {
	global $dbh;
	if(!$this->id_classement) {
		$this->type_classement	= 'BAN';
		$this->nom_classement	= '';
		} else {
			$requete = "SELECT type_classement, nom_classement FROM classements WHERE id_classement='$this->id_classement' ";
			$result = @mysql_query($requete, $dbh);
			if (mysql_num_rows($result)) {
				$temp = mysql_fetch_object($result);
				mysql_free_result($result);
				$this->type_classement = $temp->type_classement;
				$this->nom_classement = $temp->nom_classement;
				} else {
					$this->id_classement = 0;
					$this->type_classement = '';
					$this->nom_classement = '';
					}
			}
	}

// ---------------------------------------------------------------
function delete() {
	global $dbh;
	if ($this->id_classement==1) return ;
	$requete = "delete FROM classements where id_classement='".$this->id_classement."' ";
	$result = @mysql_query($requete, $dbh);
	}

// ---------------------------------------------------------------
function update($temp) {
	global $dbh;
	if ($this->id_classement) {
		$req = "update classements set nom_classement='".$temp->nom_classement."' where id_classement='".$this->id_classement."'";
		$result = mysql_query($req, $dbh);
		} else {
			$req = "insert into classements set nom_classement='".$temp->nom_classement."', type_classement='".$temp->type_classement."' ";
			$result = @mysql_query($req, $dbh);
			$this->id_classement = mysql_insert_id() ;
			$this->getData() ;
			}
	}
	
// ---------------------------------------------------------------
//		show_form : affichage du formulaire de saisie
// ---------------------------------------------------------------
function show_form($type="pro") {

	global $msg, $charset;
	global $dsi_classement_form;
	
	if ($this->id_classement) {
		$action = "./dsi.php?categ=options&sub=classements&id_classement=$this->id_classement&suite=update";
		$button_delete = "<input type='button' class='bouton' value='$msg[63]' ";
		$button_delete .= "onClick=\"confirm_delete();\">";
		$libelle = $msg['dsi_clas_form_modif'];
		$type_classement = $msg['dsi_clas_type_class_'.$this->type_classement] ;
		} else {
			$action = "./dsi.php?categ=options&sub=classements&id_classement=0&suite=update";
			$libelle = $msg['dsi_clas_form_creat'];
			$button_delete ='';
			$type_classement = "<select id='type_classement' name='type_classement'><option value='BAN'>".$msg['dsi_clas_type_class_BAN']."</option><option value='EQU'>".$msg['dsi_clas_type_class_EQU']."</OPTION></select>";
			}

	$dsi_classement_form = str_replace('!!libelle!!', $libelle, $dsi_classement_form);

	$dsi_classement_form = str_replace('!!id_classement!!', $this->id_classement, $dsi_classement_form);
	$dsi_classement_form = str_replace('!!action!!', $action, $dsi_classement_form);
	$dsi_classement_form = str_replace('!!nom_classement!!', htmlentities($this->nom_classement,ENT_QUOTES, $charset), $dsi_classement_form);


	$dsi_classement_form = str_replace('!!type_classement!!', $type_classement, $dsi_classement_form);
	
	if ($this->id_classement==1) $button_delete="";
	$dsi_classement_form = str_replace('!!delete!!', $button_delete,  $dsi_classement_form);
	print $dsi_classement_form;
	}

} // fin de déclaration de la classe classement
  
