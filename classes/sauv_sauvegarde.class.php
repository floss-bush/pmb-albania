<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sauv_sauvegarde.class.php,v 1.7 2009-05-16 11:21:58 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

//Formulaire de gestion des lieux
include ($include_path."/templates/sauvegardes_form.tpl.php");
class sauv_sauvegarde {

	//Données
	var $sauv_sauvegarde_id; //Identifiant
	var $sauv_sauvegarde_nom; //Nom de la sauvegarde
	var $sauv_sauvegarde_file_prefix; //Péfixe du fichier de sauvegarde
	var $sauv_sauvegarde_tables; //Groupes de tables
	var $sauv_sauvegarde_lieux; //Lieux de sauvegarde
	var $sauv_sauvegarde_users; //Utilisateurs autorisés
	var $sauv_sauvegarde_compress; //Compression
	var $sauv_sauvegarde_compress_method; //Méthode de compression
	var $sauv_sauvegarde_zip_command; //Commande compression
	var $sauv_sauvegarde_unzip_command; //Commande de décompression
	var $sauv_sauvegarde_zip_ext; //Extension du fichier zippé
	var $sauv_sauvegarde_crypt; //Cryptage
	var $sauv_sauvegarde_key1; //Clé de cryptage 1
	var $sauv_sauvegarde_key2; //Clé de cryptage 2
	var $sauv_sauvegarde_erase_keys; //Ecraser les clés
	var $act; //Action

	function sauv_sauvegarde() {
		global $sauv_sauvegarde_id; //Identifiant
		global $sauv_sauvegarde_nom; //Nom de la sauvegarde
		global $sauv_sauvegarde_file_prefix; //Péfixe du fichier de sauvegarde
		global $sauv_sauvegarde_tables; //Groupes de tables
		global $sauv_sauvegarde_lieux; //Lieux de sauvegarde
		global $sauv_sauvegarde_users; //Utilisateurs autorisés
		global $sauv_sauvegarde_compress; //Compression
		global $sauv_sauvegarde_compress_method; //Méthode de compression
		global $sauv_sauvegarde_zip_command; //Commande compression
		global $sauv_sauvegarde_unzip_command; //Commande de décompression
		global $sauv_sauvegarde_zip_ext; //Extension du fichier zippé
		global $sauv_sauvegarde_crypt; //Cryptage
		global $sauv_sauvegarde_key1; //Clé de cryptage 1
		global $sauv_sauvegarde_key2; //Clé de cryptage 2
		global $sauv_sauvegarde_erase_keys; //Ecraser les clés
		global $act; //Action


		//Stockage des données reçues
		$this-> sauv_sauvegarde_id = $sauv_sauvegarde_id;
		$this-> sauv_sauvegarde_nom = $sauv_sauvegarde_nom;
		$this-> sauv_sauvegarde_file_prefix = $sauv_sauvegarde_file_prefix;
		$this-> sauv_sauvegarde_tables = $sauv_sauvegarde_tables;
		$this-> sauv_sauvegarde_lieux = $sauv_sauvegarde_lieux;
		$this-> sauv_sauvegarde_users = $sauv_sauvegarde_users ; 
		$this-> sauv_sauvegarde_compress = $sauv_sauvegarde_compress;
		$this-> sauv_sauvegarde_compress_method = $sauv_sauvegarde_compress_method;
		$this-> sauv_sauvegarde_zip_command = $sauv_sauvegarde_zip_command;
		$this-> sauv_sauvegarde_unzip_command = $sauv_sauvegarde_unzip_command;
		$this-> sauv_sauvegarde_zip_ext = $sauv_sauvegarde_zip_ext; 
		$this-> sauv_sauvegarde_crypt =  $sauv_sauvegarde_crypt;
		$this-> sauv_sauvegarde_key1 = $sauv_sauvegarde_key1;
		$this-> sauv_sauvegarde_key2 = $sauv_sauvegarde_key2;
		$this-> sauv_sauvegarde_erase_keys = $sauv_sauvegarde_erase_keys;
		$this-> act = $act;
	}
	
	function verifName() {
		global $msg;
		
		$requete="select sauv_sauvegarde_id from sauv_sauvegardes where sauv_sauvegarde_nom='".$this->sauv_sauvegarde_nom."'";
		$resultat=mysql_query($requete) or die(mysql_error());
		if (mysql_num_rows($resultat)!=0) {
			echo "<script>alert(\"".$msg["sauv_sauvegardes_valid_form_error_name"]."\"); history.go(-1);</script>";
			exit();
		}
	}
	
	function verifGeneral() {
		global $msg;
		
		if (!is_array($this->sauv_sauvegarde_tables)) {
			$msg_=$msg["sauv_sauvegardes_valid_form_error_one_group"];
		} else {
			if (!is_array($this->sauv_sauvegarde_users)) {
				$msg_=$msg["sauv_sauvegardes_valid_form_error_one_user"];
			}
		}
		if ($msg_!="") {
			echo "<script>alert(\"$msg_\"); history.go(-1);</script>";
			exit();
		}
		
	}
	
	function makeUpdateQuery()
	{
		$r_tables=@implode(",",$this->sauv_sauvegarde_tables);
		$r_lieux=@implode(",",$this->sauv_sauvegarde_lieux);
		$r_users=@implode(",",$this->sauv_sauvegarde_users);
		$r_compress_command=$this->sauv_sauvegarde_compress_method;
		
		if ($this->sauv_sauvegarde_compress_method=="external") {
			$r_compress_command.=":".$this->sauv_sauvegarde_zip_command.":".$this->sauv_sauvegarde_unzip_command.":".$this->sauv_sauvegarde_zip_ext;
		} else {
			$r_compress_command.="::";
		}
		if ($this->sauv_sauvegarde_key1!="") $r_key1=md5($this->sauv_sauvegarde_key1);
		if ($this->sauv_sauvegarde_key2!="") $r_key2=md5($this->sauv_sauvegarde_key2);
		
		$requete="update sauv_sauvegardes set ";
		$requete.="sauv_sauvegarde_nom='".$this->sauv_sauvegarde_nom."'";
		$requete.=",sauv_sauvegarde_file_prefix='".$this->sauv_sauvegarde_file_prefix."'";
		$requete.=",sauv_sauvegarde_tables='".$r_tables."'";
		$requete.=",sauv_sauvegarde_lieux='".$r_lieux."'";
		$requete.=",sauv_sauvegarde_users='".$r_users."'";
		$requete.=",sauv_sauvegarde_compress=".$this->sauv_sauvegarde_compress;
		$requete.=",sauv_sauvegarde_compress_command='".$r_compress_command."'";
		$requete.=",sauv_sauvegarde_crypt=".$this->sauv_sauvegarde_crypt;
		if ($this->sauv_sauvegarde_erase_keys==1) $requete.=",sauv_sauvegarde_key1='".$r_key1."'";
		if ($this->sauv_sauvegarde_erase_keys==1) $requete.=",sauv_sauvegarde_key2='".$r_key2."'";
		
		$requete.=" where sauv_sauvegarde_id=".$this->sauv_sauvegarde_id;
		return $requete;
	}
	
	//Traitement de l'action reçue du formulaire (à appeller juste après l'instanciation de la classe)
	//Renvoie le formulaire à afficher
	function proceed() {
		
		global $first;
		
		switch ($this -> act) {
			//Enregistrer
			case "update" :
				$this->verifGeneral();
				//Si sauv_sauvegarde_id vide alors création
				if ($this -> sauv_sauvegarde_id == "") {
					$this->verifName();
					$requete = "insert into sauv_sauvegardes (sauv_sauvegarde_nom) values('')";
					mysql_query($requete) or die(mysql_error());
					$this -> sauv_sauvegarde_id = mysql_insert_id();
					$first="";
					$this->sauv_sauvegarde_erase_keys=1;
				}
				//Update avec les données reçues
				$requete = $this->makeUpdateQuery();
				mysql_query($requete) or die(mysql_error());
				$first="";
				break;
				//Supprimer
			case "delete" :
				$requete = "delete from sauv_sauvegardes where sauv_sauvegarde_id=".$this -> sauv_sauvegarde_id;
				mysql_query($requete) or die(mysql_error());
				$this -> sauv_sauvegarde_id = "";
				$first = 0;
				break;
				//Annuler
			case "cancel" :
				echo "<script>history.go(-2);</script>";
				exit();
				break;
				//Visualiser
			default :
				//Ne rien faire, le numéro de la fiche est déjà dans $this->sauv_lieu_id
		}
		return $this -> showForm();
	}

	function showSelectList($values,$table,$id_field,$name_field,$select_name) {
		$select="<select name=\"".$select_name."[]\" multiple>\n";
		$tValues=explode(",",$values);
		$requete="select $id_field,$name_field from $table";
		$resultat=mysql_query($requete) or die(mysql_error());
		while (list($id,$nom)=mysql_fetch_row($resultat)) {
			$select.="<option value=\"".$id."\"";
			$as=array_search($id,$tValues);
			if (($as!==false)&&($as!==null)) {
				$select.="selected";
			}
			$select.=">".$nom."</option>\n";
		}
		$select.="</select>\n";
		return $select;
	}
	

	//Préaparation du formulaire pour affichage
	function showForm() {
		global $form;
		global $first;
		global $msg;
		
		//Si première connexion
		if (!$first) {
			$form = "<center><h3>".$msg["sauv_sauvegardes_sel_or_add"]."</h3></center>";
		} else {
			//Si identifiant non vide
			if ($this -> sauv_sauvegarde_id) {
				//Récupération des données de la fiche
				$requete="select sauv_sauvegarde_nom,sauv_sauvegarde_file_prefix,sauv_sauvegarde_tables,sauv_sauvegarde_lieux,sauv_sauvegarde_users,sauv_sauvegarde_compress,sauv_sauvegarde_compress_command,sauv_sauvegarde_crypt,sauv_sauvegarde_key1,sauv_sauvegarde_key2 from sauv_sauvegardes where sauv_sauvegarde_id=".$this -> sauv_sauvegarde_id;
				$resultat = mysql_query($requete);
				if (mysql_num_rows($resultat) != 0)
					$r = mysql_fetch_object($resultat);
				//$form = "<center><b>".$r -> sauv_sauvegarde_nom."</b></center>".$form;
				$form = str_replace("!!quel_proc!!", $r -> sauv_sauvegarde_nom, $form);
				$form = str_replace("!!delete!!", "<input type=\"submit\" value=\"".$msg["sauv_supprimer"]."\" onClick=\"if (confirm('".$msg["sauv_sauvegardes_confirm_delete"]."')) { this.form.act.value='delete'; return true; } else { return false; }\" class=\"bouton\">", $form);
			} else {
				//Sinon : Nouvelle fiche
				//$form = "<center><b>".$msg["sauv_sauvegardes_new"]."</b></center>".$form;
				$form = str_replace("!!quel_proc!!", $msg["sauv_sauvegardes_new"], $form);
				$form = str_replace("!!delete!!", "", $form);
			}
			$form = str_replace("!!sauv_sauvegarde_id!!", $this -> sauv_sauvegarde_id, $form);
			$form = str_replace("!!sauv_sauvegarde_nom!!", $r -> sauv_sauvegarde_nom, $form);
			$form = str_replace("!!sauv_sauvegarde_file_prefix!!", $r->sauv_sauvegarde_file_prefix, $form);
			$form = str_replace("!!sauv_sauvegarde_tables!!", $this->showSelectList($r->sauv_sauvegarde_tables,"sauv_tables","sauv_table_id","sauv_table_nom","sauv_sauvegarde_tables"), $form);
			$form = str_replace("!!sauv_sauvegarde_lieux!!", $this->showSelectList($r->sauv_sauvegarde_lieux,"sauv_lieux","sauv_lieu_id","sauv_lieu_nom","sauv_sauvegarde_lieux"), $form);
			$form = str_replace("!!sauv_sauvegarde_users!!", $this->showSelectList($r->sauv_sauvegarde_users,"users","userid","username","sauv_sauvegarde_users"), $form);
			if ($r->sauv_sauvegarde_compress==1) {
				$form=str_replace("!!checked_compress_yes!!","checked",$form);
				$form=str_replace("!!checked_compress_no!!","",$form);
			} else {
				$form=str_replace("!!checked_compress_no!!","checked",$form);
				$form=str_replace("!!checked_compress_yes!!","",$form);
			}
			if ($r->sauv_sauvegarde_crypt==1) {
				$form=str_replace("!!checked_crypt_yes!!","checked",$form);
				$form=str_replace("!!checked_crypt_no!!","",$form);
			} else {
				$form=str_replace("!!checked_crypt_no!!","checked",$form);
				$form=str_replace("!!checked_crypt_yes!!","",$form);
			}
			$values=array("internal","external");
			$libs=array($msg["sauv_sauvegardes_compr_bz2"],$msg["sauv_sauvegardes_compr_externe"]);
			if ($r->sauv_sauvegarde_compress_command=="") {
				$compression_method="internal";
			} else {
				$compress_command=explode(":",$r->sauv_sauvegarde_compress_command);
				$compression_method=$compress_command[0];
			}
			$select_method="<select name=\"sauv_sauvegarde_compress_method\" class=\"saisie-simple\">\n";
			for ($i=0; $i<count($values); $i++) {
				$select_method.="<option value=\"".$values[$i]."\"";
				if ($values[$i]==$compression_method) $select_method.=" selected";
				$select_method.=">".$libs[$i]."</option>\n";
			}
			$select_method.="</select>\n";
			$form=str_replace("!!sauv_sauvegarde_compress_method!!",$select_method,$form);
			$form=str_replace("!!sauv_sauvegarde_zip_command!!",$compress_command[1],$form);
			$form=str_replace("!!sauv_sauvegarde_unzip_command!!",$compress_command[2],$form);
			$form=str_replace("!!sauv_sauvegarde_zip_ext!!",$compress_command[3],$form);
			$form = str_replace("!!sauv_sauvegarde_crypt!!", $r -> sauv_sauvegarde_crypt, $form);
			if (($r->sauv_sauvegarde_key1!="")||($r->sauv_sauvegarde_key2!="")) {
				$form=str_replace("!!sauv_sauvegarde_erase_keys!!","<tr><td>&nbsp;</td><td><input type=\"radio\" value=\"0\" name=\"sauv_sauvegarde_erase_keys\" class=\"saisie-simple\" checked >&nbsp;".$msg["sauv_sauvegardes_dont_erase_keys"]."<br /><input type=\"radio\" value=\"1\" name=\"sauv_sauvegarde_erase_keys\" class=\"saisie-simple\">&nbsp;".$msg["sauv_sauvegardes_erase_keys"]."</td></tr>\n",$form);
				$form = str_replace("!!crypt_msg!!",$msg["sauv_sauvegardes_erase_msg_keys_exists"],$form);
			} else {
				$form=str_replace("!!sauv_sauvegarde_erase_keys!!","<input type=\"hidden\" name=\"sauv_sauvegarde_erase_keys\" value=\"1\">",$form);
				$form = str_replace("!!crypt_msg!!",$msg["sauv_sauvegardes_erase_msg_keys_not_exists"],$form);
			}
		}
		return $form;
	}

	//Affichage de la liste des lieux existants dans la base
	//linkToForm : true = rend la liste interactive avec le formulaire
	function showTree($linkToForm = true) {
		global $dbh;
		global $msg;
		
		//$tree.= "<center><b>".$msg["sauv_sauvegardes_tree_title"]."</b></center>\n";
		$tree.= "<form><table class='nobrd'>\n";
		$tree.= "<th class='brd' <center>".$msg["sauv_sauvegardes_tree_title"]."</center></th>\n";
		//Récupération de la liste
		$requete = "select sauv_sauvegarde_id, sauv_sauvegarde_nom from sauv_sauvegardes order by sauv_sauvegarde_nom";
		$resultat = mysql_query($requete, $dbh) or die(mysql_error());
		while ($res = mysql_fetch_object($resultat)) {
			$tree.= "<tr><td class='nobrd'>";
			$tree.= "<img src=\"images/file.png\" border=0 align=center>&nbsp;";
			if ($linkToForm == true) {
				$tree.= "<a href=\"admin.php?categ=sauvegarde&sub=gestsauv&act=show&sauv_sauvegarde_id=".$res -> sauv_sauvegarde_id."&first=1\">";
			}
			$tree.= $res -> sauv_sauvegarde_nom;
			if ($linkToForm == true) {
				$tree.= "</a>";
			}
			$tree.= "</td></tr>\n";
		}
		$tree.= "</table>";
		//Nouveau lieu
		if ($linkToForm) {
			//$tree.= "<center><a href=\"admin.php?categ=sauvegarde&sub=gestsauv&act=show&sauv_sauvegarde_id=&first=1\">".$msg["sauv_sauvegardes_add_set"]."</a></center>";
			$tree.="
				<div class='center'><center>
				<input type=\"button\" value=\"".$msg["sauv_sauvegardes_add_set"]."\" 
					class=\"bouton\" 
					onClick=\"document.location='./admin.php?categ=sauvegarde&sub=gestsauv&act=show&sauv_sauvegarde_id=&first=1';\" />
				</center></div></form>";
			
		}
		return $tree;
	}
}
?>