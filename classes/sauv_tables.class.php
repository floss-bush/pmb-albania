<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sauv_tables.class.php,v 1.11 2009-05-16 11:21:58 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

//Formulaire de gestion des groupes de tables
include ($include_path."/templates/tables_form.tpl.php");
class sauv_table {

	//Données
	var $sauv_table_id; //Identifiant
	var $sauv_table_nom; //Nom du lieu
	var $sauv_table_tables; //Liste des tables
	var $act; //Action

	function sauv_table() {
		global $sauv_table_id; //Données reçues du formulaire
		global $sauv_table_nom;
		global $sauv_table_tables;
		global $act;

		//Stockage des données reçues
		$this -> sauv_table_id = $sauv_table_id;
		$this -> sauv_table_nom = $sauv_table_nom;
		$this -> sauv_table_tables = $sauv_table_tables;
		$this -> act = $act;
	}
	
	function verifTables() {
		global $msg;
		if (!is_array($this->sauv_table_tables)) {
			echo "<script>alert(\"".$msg["sauv_tables_valid_form_error_tables"]."\"); history.go(-1);</script>";
			exit();
		}
	}

	function verifName() {
		global $msg;
		//Erreur, si nom de groupe de tables vide
		if ($this->sauv_table_nom == "") {
			echo "<script>alert(\"".$msg["sauv_tables_valid_form_error_groupe_name"]."\"); history.go(-1);</script>";
			exit();
		}
		$requete="select sauv_table_id from sauv_tables where ( sauv_table_nom='".$this->sauv_table_nom."' and sauv_table_id !='".$this -> sauv_table_id."')";
		$resultat=mysql_query($requete) or die(mysql_error());
		if (mysql_num_rows($resultat)!=0) {
			echo "<script>alert(\"".$msg["sauv_tables_valid_form_error_duplicate"]."\"); history.go(-1);</script>";
			exit();
		}
	}

	//Traitement de l'action reçue du formulaire (à appeller juste après l'instanciation de la classe)
	//Renvoie le formulaire à afficher
	function proceed() {

		global $first;

		switch ($this -> act) {
			//Enregistrer
			case "update" :
				//Si sauv_lieu_id vide alors création
				if ($this -> sauv_table_id == "") {
					$this->verifName();
					$requete = "insert into sauv_tables (sauv_table_nom) values('')";
					mysql_query($requete) or die(mysql_error());
					$this -> sauv_table_id = mysql_insert_id();
					$first="";
				}
				//Update avec les données reçues
				$this->verifTables();
				$this->verifName();
				$requete = "update sauv_tables set sauv_table_nom='".$this -> sauv_table_nom."', sauv_table_tables='".implode(",", $this -> sauv_table_tables)."' where sauv_table_id=".$this -> sauv_table_id;
				mysql_query($requete) or die(mysql_error());
				$first="";
				break;
				//Supprimer
			case "delete" :
				$requete = "delete from sauv_tables where sauv_table_id=".$this -> sauv_table_id;
				mysql_query($requete) or die(mysql_error());
				$this -> sauv_table_id = "";
				$first = 0;
				break;
				//Annuler
			case "cancel" :
				echo "<script>history.go(-2);</script>";
				exit();
				break;
			case "update_unsaved" :
				$this->updateUnsaved();
				break;
				//Visualiser
			default :
				//Ne rien faire, le numéro de la fiche est déjà dans $this->sauv_lieu_id
		}
		return $this -> showForm();
	}

	//Préaparation du formulaire pour affichage
	function showForm() {
		global $form;
		global $first;
		global $msg;
		
		//Si première connexion
		if (!$first) {
			$form = "<center><h3>".$msg["sauv_tables_sel_or_add"]."</h3></center>";
		} else {
			if ($this -> act != "show_unsaved") {
				//Formulaire normal

				//Si identifiant non vide
				if ($this -> sauv_table_id) {
					//Récupération des données de la fiche
					$requete = "select sauv_table_nom,sauv_table_tables from sauv_tables where sauv_table_id=".$this -> sauv_table_id;
					$resultat = mysql_query($requete);
					if (mysql_num_rows($resultat) != 0)
						list ($this -> sauv_table_nom, $this -> sauv_table_tables) = mysql_fetch_row($resultat);
					//$form = "<center><b>".$this -> sauv_table_nom."</b></center>".$form;
					$form = str_replace("!!quel_table!!", $this -> sauv_table_nom, $form);
					$form = str_replace("!!delete!!", "<input type=\"submit\" value=\"".$msg["sauv_supprimer"]."\" onClick=\"if (confirm('".$msg["sauv_tables_confirm_delete"]."')) { this.form.act.value='delete'; return true; } else { return false; }\" class=\"bouton\">", $form);
				} else {
					//Sinon : Nouvelle fiche
					//$form = "<center><b>".$msg["sauv_tables_new"]."</b></center>".$form;
					$form = str_replace("!!quel_table!!", $msg["sauv_tables_new"], $form);
					$form = str_replace("!!delete!!", "", $form);
					$tTablesSelected = array();
				}
				$form = str_replace("!!sauv_table_id!!", $this -> sauv_table_id, $form);
				$form = str_replace("!!sauv_table_nom!!", $this -> sauv_table_nom, $form);

				//Liste des tables
				$tTablesSelected = explode(",", $this -> sauv_table_tables);
				$requete = "show tables";
				$resultat = mysql_query($requete) or die(mysql_error());
				$tTables = array();
				while (list ($table) = mysql_fetch_row($resultat)) {
					$tTables[] = $table;
				}

				$tables_list = "<table width=100%>";
				$curCol = 0;
				$nMaxCol = 3;
				$tables_list.= "<tr>";
				for ($i = 0; $i < count($tTables); $i ++) {
					$tables_list.= "<td class='brd'><input type=\"checkbox\" name=\"sauv_table_tables[]\" value=\"".$tTables[$i]."\" ";
					$as=array_search($tTables[$i], $tTablesSelected);
					if (( $as!== false)&&($as!==null)) {
						$tables_list.= "checked";
					}
					$tables_list.= " class=\"saisie-simple\">&nbsp;".$tTables[$i]."</td>";
					$curCol ++;
					if ($curCol == $nMaxCol) {
						$tables_list.= "</tr>\n";
						$curCol = 0;
					}
				}
				for ($i = 1; $i < $nMaxCol - $curCol; $i ++) {
					$tables_list.= "<td>&nbsp;</td>";
				}
				if ($curCol < $nMaxCol -1)
					$tables_list.= "</tr>\n";
				$tables_list.= "</table>\n";

				$form = str_replace("!!tables_list!!", $tables_list, $form);
			} else {
				//Tables non sauvegardées
				//$form = "<center><b>".$msg["sauv_tables_unsaved_tables"]."</b></center>\n";
				$form = "";				
				$form.="<form class='form-$current_module' name=\"sauv_tables\" action=\"admin.php?categ=sauvegarde&sub=tables\" method=\"post\">\n";
				$form.="<input type=\"hidden\" name=\"act\" value=\"update_unsaved\">\n";
				$form.="<input type=\"hidden\" name=\"first\" value=\"1\">\n";
				$form.="<table class='nobrd'>\n";
				$form.="<th class='brd' colspan=2><center>".$msg["sauv_tables_unsaved_tables"]."</center></th>\n";
				
				//Récupération de la liste
				$requete = "select sauv_table_id, sauv_table_nom, sauv_table_tables from sauv_tables order by sauv_table_nom";
				$resultat = mysql_query($requete) or die(mysql_error());
				$tTables = "";
				while ($res = mysql_fetch_object($resultat)) {
					$tTables.= ",".$res -> sauv_table_tables;
					$group=array();
					$group["NOM"]=$res->sauv_table_nom;
					$group["ID"]=$res->sauv_table_id;
					$groupList[]=$group;
				}
				
				//Recherche des tables non intégrées dans les groupes
				$tTablesSelected = explode(",", $tTables);
				$requete = "show tables";
				$resultat = mysql_query($requete) or die(mysql_error());
				$unsavedTables = array();
				while (list ($table) = mysql_fetch_row($resultat)) {
					$as=array_search($table, $tTablesSelected);
					if (( $as!== NULL)&&($as!==false)) {
						//Do nothing
					} else {
						$unsavedTables[] = $table;
					}
				}
				
				//Affichage des affectations possibles pour chaque table
				for ($i=0; $i<count($unsavedTables); $i++) {
					$form.="<tr><td class='brd'>".$msg["sauv_tables_table_word"]." <b>".$unsavedTables[$i]."</b></td><td class='brd'>";
					$form.=$msg["sauv_tables_affect_to_group"]." <br /><font size=1><i>".$msg["sauv_tables_dont_affect"]."</i></font><br /><select name=\"".$unsavedTables[$i]."[]\" multiple class=\"saisie-simple\">\n";
					for ($j=0; $j<count($groupList); $j++) {
						$form.="<option value=\"".$groupList[$j]["ID"]."\">".$groupList[$j]["NOM"]."</option>\n";
					}
					$form.="</select></td></tr>\n";
				}
				$form.="</table>";
				$form.="
					<center>
					<input type=\"submit\" value=\"".$msg["76"]."\" onClick=\"this.form.act.value='cancel'\" class=\"bouton\">
					<input type=\"submit\" value=\"".$msg[77]."\" class=\"bouton\">&nbsp;
					</center>";
				$form.="</form>";
			}
		}
		return $form;
	}

	//Tratitement du retour du formulaire des tables non intégrées dans les groupes
	function updateUnsaved()
	{
		global $first;
		
		//Récupération de la liste
		$requete = "select sauv_table_id, sauv_table_nom, sauv_table_tables from sauv_tables ";
		$resultat = mysql_query($requete) or die(mysql_error());
		$tTables = "";
		while ($res = mysql_fetch_object($resultat)) {
			$tTables.= ",".$res -> sauv_table_tables;
		}
				
		//Recherche des tables non intégrées dans les groupes
		$tTablesSelected = explode(",", $tTables);
		$requete = "show tables";
		$resultat = mysql_query($requete) or die(mysql_error());
		$unsavedTables = array();
		while (list ($table) = mysql_fetch_row($resultat)) {
			$as=array_search($table, $tTablesSelected);
			if (( $as!== false)&&($as!==null)) {
				//Do nothing
			} else {
				$unsavedTables[] = $table;
			}
		}
		
		$n=count($unsavedTables);
		for ($i=0; $i<count($unsavedTables); $i++) {
			$nomTable=$unsavedTables[$i];
			global $$nomTable;
			$table=$$nomTable;
			if (is_array($table)) {
				$n--;
				for ($j=0; $j<count($table); $j++) {
					$requete="update sauv_tables set sauv_table_tables=concat(sauv_table_tables,',','".$nomTable."') where sauv_table_id=".$table[$j];
					mysql_query($requete) or die(mysql_error());
				}
			}
		}
		if ($n!=0) $this -> act="show_unsaved"; else $first="";
	}

	//Affichage de la liste des tables existantes dans la base
	//linkToForm : true = rend la liste interactive avec le formulaire
	function showTree($linkToForm = true) {
		global $dbh;
		global $msg;
		
		$tree_h.= "<center>".$msg["sauv_tables_tree_title"]."</center>\n";
		$tree_h1.= "<form><table class='nobrd'><th class='brd'!!tree_h!!</th>";

		//Récupération de la liste
		$requete = "select sauv_table_id, sauv_table_nom, sauv_table_tables from sauv_tables order by sauv_table_nom";
		$resultat = mysql_query($requete, $dbh) or die(mysql_error());
		$tTables = "";
		while ($res = mysql_fetch_object($resultat)) {
			$tTables.= ",".$res -> sauv_table_tables;
			$tree.= "<tr><td class='nobrd'>";
			$tree.= "<img src=\"images/table.png\" border=0 align=center>&nbsp;";
			if ($linkToForm == true) {
				$tree.= "<a href=\"admin.php?categ=sauvegarde&sub=tables&act=show&sauv_table_id=".$res -> sauv_table_id."&first=1\">";
			}
			$tree.= $res -> sauv_table_nom;
			if ($linkToForm == true) {
				$tree.= "</a>";
			}
			$tree.= "</td></tr>\n";
		}
		$tree.= "</table>";

		//Recherche des tables non intégrées dans les groupes
		$tTablesSelected = explode(",", $tTables);
		$requete = "show tables";
		$resultat = mysql_query($requete) or die(mysql_error());
		$unsavedTables = array();
		while (list ($table) = mysql_fetch_row($resultat)) {
			$as=array_search($table, $tTablesSelected);
			if (( $as!== false)&&($as!==null)) {
				//Do nothing
			} else {
				$unsavedTables[] = $table;
			}
		}

		if (count($unsavedTables) != 0) {
			$tree_h.= "<br /><center><b><font color=#BB0000> ".sprintf($msg["sauv_tables_warning_unsaved"],count($unsavedTables))."</font></b></center><br />";
			$tree_h1.= "<tr><td class='nobrd'><img src=\"images/table_unsaved.png\" border=0 align=center>&nbsp;<a href=\"admin.php?categ=sauvegarde&sub=tables&act=show_unsaved&first=1\">".$msg["sauv_tables_unsaved_tables"]."</a></td></tr>\n";
		}
		//Nouveau groupe
		if ($linkToForm) {
//			$tree.= "<center><a href=\"admin.php?categ=sauvegarde&sub=tables&act=show&sauv_table_id=&first=1\">".$msg["sauv_tables_add_group"]."</a></center>";
			$tree.="
				<div class='center'><center>
				<input type=\"button\" value=\"".$msg["sauv_tables_add_group"]."\" 
					class=\"bouton\" 
					onClick=\"document.location='./admin.php?categ=sauvegarde&sub=tables&act=show&sauv_table_id=&first=1';\" />
				</center></div></form>";
		}
		$tree_h1=str_replace('!!tree_h!!',$tree_h,$tree_h1);	
		return $tree_h1.$tree;
	}
}
?>