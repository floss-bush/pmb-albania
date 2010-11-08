<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: liste_simple.class.php,v 1.1 2009-09-24 13:18:58 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");
require_once($include_path."/templates/liste_simple.tpl.php");

/*
 * Classe générique qui permet la création d'un liste simple id/libellé
 */
class liste_simple{
	
	var $table ='';
	var $colonne_id_nom = '';
	var $colonne_lib_nom = ''; 
	var $id_liste = 0;
	var $lib_liste = '';
	var $messages = array();
	var $actions = array();
	
	function liste_simple($table,$col_id_name,$col_lib_name,$id_liste=0){
		global $dbh; 
		$this->table = $table;
		$this->colonne_id_nom = $col_id_name;
		$this->colonne_lib_nom = $col_lib_name;
		
		$this->id_liste = $id_liste;
		
		if(!$this->id_liste){
			$this->lib_liste ='';
		} else {
			$req = "select $this->colonne_lib_nom as lib from $this->table where $this->colonne_id_nom ='".$this->id_liste."'";
			$res = mysql_query($req,$dbh);
			$list = mysql_fetch_object($res);
			$this->lib_liste = $list->lib;
		}
		
		$this->setParametres();
	}
	
	/*
 	 * Gestion des actions
	 */
	function proceed($action){
		
		switch($action){
			
			case 'save':
				$this->save();
				$this->show_form();
				break;
			case 'modif':
			case 'add':
				$this->show_edit_form();
				break;
			case 'del':
				$ko = $this->delete();
				if(!$ko) $this->show_form();
				break;
			default:
				$this->show_form();
				break;
		}
	}
	
	/*
	 * Fonction qui affecte tous les paramètres de la classe
	 */
	function setParametres(){
		$this->setMessages();
		$this->setActions();
	}
	
	/*
	 * Affectation des messages
	 */
	function setMessages($ajout_titre="",$modif_titre="",$confirm_del="",$add_btn="", $no_list="", $used="", $selector_all=""){
		global $msg;
		
		$ajout_titre ? $this->messages['ajout_titre'] = $ajout_titre : $this->messages['ajout_titre'] = 'list_simple_ajout';
		$modif_titre ? $this->messages['modif_titre'] = $modif_titre : $this->messages['modif_titre'] = 'list_simple_modif';
		$confirm_del ? $this->messages['confirm_del'] = $confirm_del : $this->messages['confirm_del'] = 'list_simple_del';
		$add_btn ? $this->messages['add_btn'] = $add_btn : $this->messages['add_btn'] = 'list_simple_add_btn';
		$no_list ? $this->messages['no_list'] = $no_list : $this->messages['no_list'] = 'list_simple_no_list';
		$used ? $this->messages['object_used'] = $used : $this->messages['object_used'] = 'list_simple_used';
		$selector_all ? $this->messages['selector_all'] = $selector_all : $this->messages['selector_all'] = 'list_simple_all';
	}
	
	/*
	 * Définition des actions
	 */
	function setActions($base,$form_act){
		
		$this->actions['base'] = $base;
		$this->actions['form'] = $form_act;
	}

	/*
	 * Formulaire d'ajout/modification
	 */
	function show_edit_form(){
		global $liste_simple_form, $msg, $charset;
		
		if(!$this->id_liste){
			$liste_simple_form = str_replace('!!form_title!!',$msg[$this->messages['ajout_titre']],$liste_simple_form);
			$liste_simple_form = str_replace('!!libelle!!','',$liste_simple_form);
			$liste_simple_form = str_replace('!!bouton_sup!!','',$liste_simple_form);
			$liste_simple_form = str_replace('!!id_liste!!','',$liste_simple_form);
		} else {
			$liste_simple_form .= "<script type='text/javascript'>
				function confirm_del(){
					result = confirm(\"".$msg[$this->messages['confirm_del']]."\");
        			return result;
        		}
        		</script>";
			$liste_simple_form = str_replace('!!id_liste!!',$this->id_liste,$liste_simple_form);
			$liste_simple_form = str_replace('!!form_title!!',$msg[$this->messages['modif_titre']],$liste_simple_form);
			$liste_simple_form = str_replace('!!libelle!!',htmlentities($this->lib_liste, ENT_QUOTES, $charset),$liste_simple_form);
			$btn_sup = "<input class='bouton' type='submit' name='del' id='del' value='$msg[63]' onclick='this.form.act.value=\"del\"; return confirm_del();'";
			$liste_simple_form = str_replace('!!bouton_sup!!',$btn_sup,$liste_simple_form);
		}
		
		$liste_simple_form = str_replace('!!list_simple_action!!',$this->actions['form'],$liste_simple_form);
		print $liste_simple_form;
	}
	
	
	/*
	 * Formulaire de présentation
	 */
	function show_form(){
		global $dbh;
		global $msg;
		global $charset;
		
		$display='';
		$display= "<table>
		<tr>
			<th>".htmlentities($msg[103], ENT_QUOTES, $charset)."</th>
		</tr>";
		$tab_list =array();
		$req = "select * from $this->table order by $this->colonne_lib_nom";
		$res=mysql_query($req,$dbh);
		while ($row = mysql_fetch_object($res)){
			$nom = $this->colonne_lib_nom;
			$id = $this->colonne_id_nom;
			$tab_list[$row->$id] = $row->$nom;
		}
		
		if(count($tab_list) == 0){
			$display .= "<tr><td>".$msg[$this->messages['no_list']]."</td></tr>";
		} 
		$parity=1;
		foreach($tab_list as $id_list=>$lib_list) {
			if ($parity % 2) {
				$pair_impair = "even";
			} else {
				$pair_impair = "odd";
			}
			$parity += 1;
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='".$this->actions['base']."&act=modif&id_liste=$id_list';\" ";
	        $display .= "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td><i>".htmlentities($lib_list, ENT_QUOTES, $charset)."</i></td>";
			$display .= "</tr>";
		}
		$display .= "</table>
			<input class='bouton' type='button' value=' ".$msg[$this->messages['add_btn']]." ' onClick=\"document.location='".$this->actions['base']."&act=add'\" />";
		
		print $display;
	}
	
	/*
	 * Création/Modification
	 */
	function save(){
		
		global $dbh, $libelle;
		
		if(!$this->id_liste){
			$req = "insert into $this->table set $this->colonne_lib_nom='".$libelle."'";
		} else {
			$req="update $this->table set $this->colonne_lib_nom='".$libelle."' where $this->colonne_id_nom='".$this->id_liste."'";
		}		
		mysql_query($req,$dbh);
	}	
	
	/*
	 * Suppression
	 */
	function delete(){
		global $dbh,$msg;		
		
		$error = false;
		if($this->hasElements()){
			error_message($msg[321],$msg[$this->messages['object_used']],1, $this->actions['base']);
			$error=true;
		} else {		
			$req="delete from $this->table where $this->colonne_id_nom='".$this->id_liste."'";
			mysql_query($req,$dbh);
		}
		
		return $error;
	}
	
	
	/*
	 * Retourne un sélecteur correspondant à la liste
	 */
	function getListSelector($idliste=0,$action='',$default=false){
		global $dbh,$charset,$msg;
		
		$req = "select * from $this->table order by $this->colonne_lib_nom";
		$res = mysql_query($req,$dbh);
		$select = "";
		$selector = "<select name='$this->colonne_id_nom' $action >";
		if($default) $selector .= "<option value='0'>".htmlentities($msg[$this->messages['selector_all']],ENT_QUOTES,$charset)."</option>";
		while(($list=mysql_fetch_object($res))){
			$id = $this->colonne_id_nom;
			$nom = $this->colonne_lib_nom;
			if($idliste == $list->$id) $select="selected";
			$selector .= "<option value='".$list->$id."' $select>".htmlentities($list->$nom,ENT_QUOTES,$charset)."</option>";
			$select = "";
		}
		$selector .= "</select>";
		
		return $selector;
	}
	

	//Vérifie si le thème de demande est utilisé dans les demandes	
	function hasElements(){		
	}
}

/*
 * Classe des types de demandes
 */
class demandes_types extends liste_simple {
	
	/*
	 * Définition des paramètres
	 */
	function setParametres(){
		$this->setMessages('demandes_ajout_type','demandes_modif_type','demandes_del_type','demandes_add_type','demandes_no_type_available','demandes_used_type');
		$this->setActions('admin.php?categ=demandes&sub=type','admin.php?categ=demandes&sub=type');
	}
	/*
	 * Vérifie si le type de demande est utilisé dans les demandes
	 */	
	function hasElements(){
		
		global $dbh;
		
		$q = "select count(1) from demandes where type_demande = '".$this->id_liste."' ";
		$r = mysql_query($q, $dbh); 
		return mysql_result($r, 0, 0);
	}
}

/*
 * Classe des thèmes de demandes
 */
class demandes_themes extends liste_simple {
	
	/*
	 * Définition des paramètres
	 */
	function setParametres(){
		$this->setMessages('demandes_ajout_theme','demandes_modif_theme','demandes_del_theme','demandes_add_theme','demandes_no_theme_available','demandes_used_theme');
		$this->setActions('admin.php?categ=demandes&sub=theme','admin.php?categ=demandes&sub=theme');
	}
	/*
	 * Vérifie si le thème de demande est utilisé dans les demandes
	 */	
	function hasElements(){
		
		global $dbh;
		
		$q = "select count(1) from demandes where theme_demande = '".$this->id_liste."' ";
		$r = mysql_query($q, $dbh); 
		return mysql_result($r, 0, 0);
	}
}
?>