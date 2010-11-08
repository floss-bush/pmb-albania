<?php
// +-------------------------------------------------+
// © 2005 Guillaume Boitel g.boitel@wanadoo.fr
// +-------------------------------------------------+
// $Id: create_proc.class.php,v 1.7 2009-05-16 11:22:56 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

//Classe de gestion des créations de procédures

require_once($include_path."/parser.inc.php");
require_once($class_path."/parametres_perso.class.php");
require_once($include_path."/templates/create_proc.tpl.php");

class create_proc {
	var $nom_proc;		// nom de la procédure
	var $comment;		// commentaire de la procédure
	var $userautorisation;	// utilisateur pouvant utiliser cette procédure
	var $print_field;	// champs à afficher
	var $fixed_params;	// paramètres fixes
	var $op_param;		// opérature lié au paramètre fixe
	var $val_param;		// valeur lié au paramèter fixe
	var $dynamic_params;	// paramètres variables
	var $op_var;		// opérature lié au paramètre fixe
	var $val_var;		// valeur lié au paramèter fixe
	var $list_fields;	// champs interrogeable
	var $operateur;		// listes des opérateurs
	var $op_type;		// liens entre types de données et opérateurs
	var $pp;		// champs perso
	var $etape;		// etape de la création
	var $r;			// champs !!field_list!!
	var $sf;		// champs !!already_selected_fields!!
	var $url;		// url courrante
	var $url_next;		// url pour l'étape suivante
	
	// constructeur
	function create_proc($nom_proc,$comment,$userautorisation,$print_field, $fixed_params, $op_param, $val_param, $dynamic_params, $op_var, $val_var) {
		$this->nom_proc = $nom_proc;
		$this->comment = $comment;
		$this->userautorisation = $userautorisation;
		$this->print_field = $print_field;
		$this->fixed_params = $fixed_params;
		$this->op_param = $op_param;
		$this->val_param = $val_param;
		$this->dynamic_params = $dynamic_params;
		$this->op_var = $op_var;
		$this->val_var = $val_var;
		$this->parse_config();
		$this->pp=new parametres_perso("notices");
		$this->r="";
		$this->sf="";
		$this->url="";
		$this->url_next="";
	}
	
	// parser du fichier de configuration
	function parse_config(){
		global $include_path;
		global $lang;
		
		$fp=fopen($include_path."/create_proc/$lang.xml","r") or die("Can't find XML file");
		$xml=fread($fp,filesize($include_path."/create_proc/$lang.xml"));
		fclose($fp);
		$param=_parser_text_no_function_($xml, "PMBFIELDS");
		for($i=0; $i<count($param["LISTFIELDS"][0]["ITEM"]); $i++) {
			$this->list_fields[$param["LISTFIELDS"][0]["ITEM"][$i]["ID"]]["TITLE"]=$param["LISTFIELDS"][0]["ITEM"][$i]["TITRE"];
			$this->list_fields[$param["LISTFIELDS"][0]["ITEM"][$i]["ID"]]["SEPARATOR"]=$param["LISTFIELDS"][0]["ITEM"][$i]["SEPARATEUR"];
			$this->list_fields[$param["LISTFIELDS"][0]["ITEM"][$i]["ID"]]["DATATYPE"]=$param["LISTFIELDS"][0]["ITEM"][$i]["TYPE"];
			$this->list_fields[$param["LISTFIELDS"][0]["ITEM"][$i]["ID"]]["CHAMP"]=$param["LISTFIELDS"][0]["ITEM"][$i]["CHAMP"];
			$this->list_fields[$param["LISTFIELDS"][0]["ITEM"][$i]["ID"]]["TABLE"]=$param["LISTFIELDS"][0]["ITEM"][$i]["TABLE"];
			$this->list_fields[$param["LISTFIELDS"][0]["ITEM"][$i]["ID"]]["JOINTURE"]=$param["LISTFIELDS"][0]["ITEM"][$i]["JOINTURE"];
			$this->list_fields[$param["LISTFIELDS"][0]["ITEM"][$i]["ID"]]["INDEX"]=$param["LISTFIELDS"][0]["ITEM"][$i]["INDEX"];
		}
		for($i=0; $i<count($param["TYPEFIELDS"][0]["FIELD"]); $i++) {
			for($j=0; $j<count($param["TYPEFIELDS"][0]["FIELD"][$i]["QUERY"]); $j++) {
				$this->op_type[$param["TYPEFIELDS"][0]["FIELD"][$i]["DATATYPE"]][$j]=$param["TYPEFIELDS"][0]["FIELD"][$i]["QUERY"][$j]["FOR"];
			}
		}
		for($i=0; $i<count($param["OPERATORS"][0]["OPERATOR"]); $i++) {
			$this->operateur[$param["OPERATORS"][0]["OPERATOR"][$i]["NAME"]]["TITRE"]=$param["OPERATORS"][0]["OPERATOR"][$i]["value"];
			$this->operateur[$param["OPERATORS"][0]["OPERATOR"][$i]["NAME"]]["DEB"]=$param["OPERATORS"][0]["OPERATOR"][$i]["DEB"];
			$this->operateur[$param["OPERATORS"][0]["OPERATOR"][$i]["NAME"]]["FIN"]=$param["OPERATORS"][0]["OPERATOR"][$i]["END"];
		}
	}
	
	// modification de la priorité d'affichage
	function mod_prio($up){
		$tmp = $this->print_field[$up-1];
		$this->print_field[$up-1]=$this->print_field[$up];
		$this->print_field[$up]=$tmp;
		unset($tmp);
	}
	
	// formulaire du choix des paramètres fixes
	function choix_param() {
		global $charset;
		global $msg;
		
		// données provenant du formulaire
		global $add_param;
		global $delete_param;
		
		// ajout d'un paramètre
		if (($add_param)&&($delete_param==="")){
			$this->fixed_params[]=$add_param;
		}
				
		//Génération de la liste des champs possibles
		$this->r="<select name='add_param' id='add_param'>\n";
		
		//Champs fixes
		reset($this->list_fields);
		$open_optgroup=0;
		while (list($id,$lf)=each($this->list_fields)) {
			if ($lf["SEPARATOR"]) {
				if ($open_optgroup) $this->r.="</optgroup>\n";
				$this->r.="<optgroup label='".htmlentities($lf["SEPARATOR"],ENT_QUOTES,$charset)."' class='erreur'>\n";
				$open_optgroup=1;
			}
			$this->r.="<option value='f_".$id."' style='color:#000000'>".htmlentities($lf["TITLE"],ENT_QUOTES,$charset)."</font></option>\n";
		}
		if ($open_optgroup) $this->r.="</optgroup>\n";
		
		//Champs perso
		if (!$this->pp->no_special_fields) {
			$this->r.="<optgroup label='".$msg["search_custom"]."' class='erreur'>\n";
			reset($this->pp->t_fields);
			while (list($id,$pf)=each($this->pp->t_fields)) {
				$this->r.="<option value='p_".$id."' style='color:#000000'>".htmlentities($pf["TITRE"],ENT_QUOTES,$charset)."</option>\n";
			}
			$this->r.="</optgroup>\n";
		}
		$this->r.="</select>";
		
		//Affichage des champs déjà saisis
		$n=0;
		$this->sf="<table class='table-no-border'>\n";
		if(!((count($this->fixed_params)==0) || ((count($this->fixed_params)==1)&&($delete_param!="")))) $this->sf.="<tr><td><b>".$msg["crit"]."</b></td><td><b>".$msg["type_op"]."</b></td><td><b>".$msg[1604]."</b></td><td><b>".$msg[63]."</b></td><tr>";
		for ($i=0; $i<count($this->fixed_params); $i++) {
			if ((string)$i!=$delete_param) {
				$this->sf.="<tr>";
				$this->sf.="<td>";
				$this->sf.="<input type='hidden' name='fixed_params[]' value='".$this->fixed_params[$i]."'>";
				$f=explode("_",$this->fixed_params[$i]);
				
				// Affichage du nom du champ
				if ($f[0]=="f") {
					$this->sf.=htmlentities($this->list_fields[$f[1]]["TITLE"],ENT_QUOTES,$charset);
				} else {
					$this->sf.=htmlentities($this->pp->t_fields[$f[1]]["TITRE"],ENT_QUOTES,$charset);
				}
				$this->sf.="</td>";
				
				// Affichage du type de condition
				
				$this->sf.="<td>";
				$this->sf.="<select name='op_param[]'>\n";
				if($f[0]=="f") {
					for($j=0; $j<count($this->op_type[$this->list_fields[$f[1]]["DATATYPE"]]); $j++) {
						$this->sf.="<option value='".$this->op_type[$this->list_fields[$f[1]]["DATATYPE"]][$j]."'";
						if($this->op_param[$i] == $this->op_type[$this->list_fields[$f[1]]["DATATYPE"]][$j]) $this->sf .=" selected";
						$this->sf.=">".htmlentities($this->operateur[$this->op_type[$this->list_fields[$f[1]]["DATATYPE"]][$j]]["TITRE"],ENT_QUOTES,$charset)."</option>\n";
					}
				} else {
					for($j=0; $j<count($this->op_type[$this->pp->t_fields[$f[1]]["DATATYPE"]]); $j++) {
						$this->sf.="<option value='".$this->op_type[$this->pp->t_fields[$f[1]]["DATATYPE"]][$j]."'";
						if($this->op_param[$i] == $this->op_type[$this->pp->t_fields[$f[1]]["DATATYPE"]][$j]) $this->sf .=" selected";
						$this->sf.=">".htmlentities($this->operateur[$this->op_type[$this->pp->t_fields[$f[1]]["DATATYPE"]][$j]]["TITRE"],ENT_QUOTES,$charset)."</option>\n";
					}
				}
				$this->sf.="</select>\n";
				$this->sf.="</td>";
				
				// Affichage de la valeur de condition
				$this->sf.="<td>";
				$this->sf.="<input type='text' name='val_param[]' value='".htmlentities($this->val_param[$i],ENT_QUOTES,$charset)."' size='60'/>";
				$this->sf.="</td>";
				
				// Boutton de supression
				$this->sf.="<td width='20'><input type='button' class='bouton' value='".$msg["raz"]."' onClick=\"this.form.delete_param.value='".$n."'; this.form.action='$url'; this.form.target=''; this.form.submit();\"></td>";
				
				$this->sf.="</tr>\n";
				$n++;
			}
		}
		$this->sf.="</table>\n";
		// Champs cachés
		$this->sf.="<input type='hidden' name='nom_proc' value='".htmlentities(stripslashes($this->nom_proc),ENT_QUOTES,$charset)."'>";
		$this->sf.="<input type='hidden' name='comment' value='".htmlentities(stripslashes($this->comment),ENT_QUOTES,$charset)."'>";
		for($i=0; $i<count($this->userautorisation); $i++) {
			$this->sf.="<input type='hidden' name='userautorisation[]' value='".$this->userautorisation[$i]."'>";
		}
		for($i=0; $i<count($this->print_field); $i++) {
			$this->sf.="<input type='hidden' name='print_field[]' value='".$this->print_field[$i]."'>";
		}
		for($i=0; $i<count($this->dynamic_params); $i++) {
			$this->sf.="<input type='hidden' name='dynamic_params[]' value='".$this->dynamic_params[$i]."'>";
		}
		for($i=0; $i<count($this->op_var); $i++) {
			$this->sf.="<input type='hidden' name='op_var[]' value='".$this->op_var[$i]."'>";
		}
		for($i=0; $i<count($this->val_var); $i++) {
			$this->sf.="<input type='hidden' name='val_var[]' value='".htmlentities(stripslashes($this->val_var[$i]),ENT_QUOTES,$charset)."'>";
		}
		
		$this->sf.="<input type='hidden' name='delete_param' value=''/>";
	}
	
	// formulaire du choix des paramètres variables
	function choix_var(){
		global $charset;
		global $msg;
		
		// données provenant du formulaire
		global $add_var;
		global $delete_var;
		
		// ajout d'un paramètre
		if (($add_var)&&($delete_var==="")){
			$this->dynamic_params[]=$add_var;
		}
				
		//Génération de la liste des champs possibles
		$this->r="<select name='add_var' id='add_var'>\n";
		
		//Champs fixes
		reset($this->list_fields);
		$open_optgroup=0;
		while (list($id,$lf)=each($this->list_fields)) {
			if ($lf["SEPARATOR"]) {
				if ($open_optgroup) $this->r.="</optgroup>\n";
				$this->r.="<optgroup label='".htmlentities($lf["SEPARATOR"],ENT_QUOTES,$charset)."' class='erreur'>\n";
				$open_optgroup=1;
			}
			$this->r.="<option value='f_".$id."' style='color:#000000'>".htmlentities($lf["TITLE"],ENT_QUOTES,$charset)."</font></option>\n";
		}
		if ($open_optgroup) $this->r.="</optgroup>\n";
		
		//Champs perso
		if (!$this->pp->no_special_fields) {
			$this->r.="<optgroup label='".$msg["search_custom"]."' class='erreur'>\n";
			reset($this->pp->t_fields);
			while (list($id,$pf)=each($this->pp->t_fields)) {
				$this->r.="<option value='p_".$id."' style='color:#000000'>".htmlentities($pf["TITRE"],ENT_QUOTES,$charset)."</option>\n";
			}
			$this->r.="</optgroup>\n";
		}
		$this->r.="</select>";
		
		//Affichage des champs déjà saisis
		$n=0;
		$this->sf="<table class='table-no-border'>\n";
		if(!((count($this->dynamic_params)==0) || ((count($this->dynamic_params)==1)&&($delete_var!="")))) $this->sf.="<tr><td><b>".$msg["crit"]."</b></td><td><b>".$msg["type_op"]."</b></td><td><b>".$msg[103]."</b></td><td><b>".$msg[63]."</b></td><tr>";
		for ($i=0; $i<count($this->dynamic_params); $i++) {
			if ((string)$i!=$delete_var) {
				$this->sf.="<tr>";
				$this->sf.="<td>";
				$this->sf.="<input type='hidden' name='dynamic_params[]' value='".$this->dynamic_params[$i]."'>";
				$f=explode("_",$this->dynamic_params[$i]);
				
				// Affichage du nom du champ
				if ($f[0]=="f") {
					$this->sf.=htmlentities($this->list_fields[$f[1]]["TITLE"],ENT_QUOTES,$charset);
				} else {
					$this->sf.=htmlentities($this->pp->t_fields[$f[1]]["TITRE"],ENT_QUOTES,$charset);
				}
				$this->sf.="</td>";
				
				// Affichage du type de condition
				
				$this->sf.="<td>";
				$this->sf.="<select name='op_var[]'>\n";
				if($f[0]=="f") {
					for($j=0; $j<count($this->op_type[$this->list_fields[$f[1]]["DATATYPE"]]); $j++) {
						$this->sf.="<option value='".$this->op_type[$this->list_fields[$f[1]]["DATATYPE"]][$j]."'";
						if($this->op_var[$i] == $this->op_type[$this->list_fields[$f[1]]["DATATYPE"]][$j]) $this->sf .=" selected";
						$this->sf.=">".htmlentities($this->operateur[$this->op_type[$this->list_fields[$f[1]]["DATATYPE"]][$j]]["TITRE"],ENT_QUOTES,$charset)."</option>\n";
					}
				} else {
					for($j=0; $j<count($this->op_type[$this->pp->t_fields[$f[1]]["DATATYPE"]]); $j++) {
						$this->sf.="<option value='".$this->op_type[$this->pp->t_fields[$f[1]]["DATATYPE"]][$j]."'";
						if($this->op_var[$i] == $this->op_type[$this->pp->t_fields[$f[1]]["DATATYPE"]][$j]) $this->sf .=" selected";
						$this->sf.=">".htmlentities($this->operateur[$this->op_type[$this->pp->t_fields[$f[1]]["DATATYPE"]][$j]]["TITRE"],ENT_QUOTES,$charset)."</option>\n";
					}
				}
				$this->sf.="</select>\n";
				$this->sf.="</td>";
				
				// Affichage de la valeur de condition
				$this->sf.="<td>";
				$this->sf.="<input type='text' name='val_var[]' value='".htmlentities($this->val_var[$i],ENT_QUOTES,$charset)."' size='60'/>";
				$this->sf.="</td>";
				
				// Boutton de supression
				$this->sf.="<td width='20'><input type='button' class='bouton' value='".$msg["raz"]."' onClick=\"this.form.delete_var.value='".$n."'; this.form.action='$url'; this.form.target=''; this.form.submit();\"></td>";
				
				$this->sf.="</tr>\n";
				$n++;
			}
		}
		$this->sf.="</table>\n";
		// Champs cachés
		$this->sf.="<input type='hidden' name='nom_proc' value='".htmlentities(stripslashes($this->nom_proc),ENT_QUOTES,$charset)."'>";
		$this->sf.="<input type='hidden' name='comment' value='".htmlentities(stripslashes($this->comment),ENT_QUOTES,$charset)."'>";
		for($i=0; $i<count($this->userautorisation); $i++) {
			$this->sf.="<input type='hidden' name='userautorisation[]' value='".$this->userautorisation[$i]."'>";
		}
		for($i=0; $i<count($this->print_field); $i++) {
			$this->sf.="<input type='hidden' name='print_field[]' value='".$this->print_field[$i]."'>";
		}
		for($i=0; $i<count($this->fixed_params); $i++) {
			$this->sf.="<input type='hidden' name='fixed_params[]' value='".$this->fixed_params[$i]."'>";
		}
		for($i=0; $i<count($this->op_param); $i++) {
			$this->sf.="<input type='hidden' name='op_param[]' value='".$this->op_param[$i]."'>";
		}
		for($i=0; $i<count($this->val_param); $i++) {
			$this->sf.="<input type='hidden' name='val_param[]' value='".htmlentities(stripslashes($this->val_param[$i]),ENT_QUOTES,$charset)."'>";
		}
		
		$this->sf.="<input type='hidden' name='delete_var' value=''/>";
	}
	
	// formulaire du choix des champs à afficher
	function choix_champ() {
		global $charset;
		global $msg;
		
		// données provenant du formulaire
		global $add_field;
		global $delete_field;
		global $add_prio;
		global $min_prio;
		
		// ajout d'un champ
		if (($add_field)&&($delete_field==="")&&($add_prio==="")&&($min_prio==="")){
			$this->print_field[]=$add_field;
		}
		
		// Identifiant du champ supprimé
		for($i=0; $i<count($this->print_field); $i++) {
			if ((string)$i==$delete_field) $delete_id = $this->print_field[$i];
		}
		
		// Changement de priorité
		if($add_prio!="") $this->mod_prio($add_prio);
		if($min_prio!="") $this->mod_prio($min_prio+1);	
		
		//Génération de la liste des champs possibles
		$this->r="<select name='add_field' id='add_field'>\n";
		
		//Champs fixes
		reset($this->list_fields);
		$open_optgroup=0;
		while (list($id,$lf)=each($this->list_fields)) {
			if ($lf["SEPARATOR"]) {
				if ($open_optgroup) $this->r.="</optgroup>\n";
				$this->r.="<optgroup label='".htmlentities($lf["SEPARATOR"],ENT_QUOTES,$charset)."' class='erreur'>\n";
				$open_optgroup=1;
			}
			if((in_array("f_".$id, $this->print_field)===false) || "f_".$id == $delete_id) // Ne pas afficher un champ déjà présent (sauf si il vient d'être suprimé)
				$this->r.="<option value='f_".$id."' style='color:#000000'>".htmlentities($lf["TITLE"],ENT_QUOTES,$charset)."</font></option>\n";
		}
		if ($open_optgroup) $this->r.="</optgroup>\n";
		
		//Champs perso
		if (!$this->pp->no_special_fields) {
			$this->r.="<optgroup label='".$msg["search_custom"]."' class='erreur'>\n";
			reset($this->pp->t_fields);
			while (list($id,$pf)=each($this->pp->t_fields)) {
				if((in_array("p_".$id, $this->print_field)===false) || "p_".$id == $delete_id) // Ne pas afficher un champ déjà présent (sauf si il vient d'être suprimé)
				$this->r.="<option value='p_".$id."' style='color:#000000'>".htmlentities($pf["TITRE"],ENT_QUOTES,$charset)."</option>\n";
			}
			$this->r.="</optgroup>\n";
		}
		$this->r.="</select>";
		
		//Affichage des champs déjà saisis
		$n=0;
		$this->sf="<table class='table-no-border'>\n";
		for ($i=0; $i<count($this->print_field); $i++) {
			if ((string)$i!=$delete_field) {
				$this->sf.="<tr>";
				$this->sf.="<td>";
				$this->sf.="<input type='hidden' name='print_field[]' value='".$this->print_field[$i]."'>";
				$f=explode("_",$this->print_field[$i]);
				
				// Affichage du nom du champ
				if ($f[0]=="f") {
					$this->sf.=htmlentities($this->list_fields[$f[1]]["TITLE"],ENT_QUOTES,$charset);
				} else {
					$this->sf.=htmlentities($this->pp->t_fields[$f[1]]["TITRE"],ENT_QUOTES,$charset);
				}
				$this->sf.="</td>";
				
				// Boutton de supression
				$this->sf.="<td width='20'><input type='button' class='bouton' value='".$msg["raz"]."' onClick=\"this.form.delete_field.value='".$n."'; this.form.action='$url'; this.form.target=''; this.form.submit();\"></td>";
				
				// Bouttons de priorités
				if( ($i==0) || (($i==1)&&($delete_field=="0")) ) $this->sf .="<td width='20'>&nbsp;</td>";
				else $this->sf.="<td width='20'><input type='button' class='bouton' value='+' onClick=\"this.form.add_prio.value='".$n."'; this.form.action='$url'; this.form.target=''; this.form.submit();\"></td>";
				if( ($i==count($this->print_field)-1) || (($i==count($this->print_field)-2)&&($delete_field==count($this->print_field)-1)) ) $this->sf .="<td width='20'>&nbsp;</td>";
				else $this->sf.="<td width='20'><input type='button' class='bouton' value='-' onClick=\"this.form.min_prio.value='".$n."'; this.form.action='$url'; this.form.target=''; this.form.submit();\"></td>";
				
				$this->sf.="</tr>\n";
				$n++;
			}
		}
		$this->sf.="</table>\n";
		// Champs cachés
		$this->sf.="<input type='hidden' name='nom_proc' value='".htmlentities(stripslashes($this->nom_proc),ENT_QUOTES,$charset)."'>";
		$this->sf.="<input type='hidden' name='comment' value='".htmlentities(stripslashes($this->comment),ENT_QUOTES,$charset)."'>";
		for($i=0; $i<count($this->userautorisation); $i++) {
			$this->sf.="<input type='hidden' name='userautorisation[]' value='".$this->userautorisation[$i]."'>";
		}
		for($i=0; $i<count($this->fixed_params); $i++) {
			$this->sf.="<input type='hidden' name='fixed_params[]' value='".$this->fixed_params[$i]."'>";
		}
		for($i=0; $i<count($this->op_param); $i++) {
			$this->sf.="<input type='hidden' name='op_param[]' value='".$this->op_param[$i]."'>";
		}
		for($i=0; $i<count($this->val_param); $i++) {
			$this->sf.="<input type='hidden' name='val_param[]' value='".htmlentities(stripslashes($this->val_param[$i]),ENT_QUOTES,$charset)."'>";
		}
		for($i=0; $i<count($this->dynamic_params); $i++) {
			$this->sf.="<input type='hidden' name='dynamic_params[]' value='".$this->dynamic_params[$i]."'>";
		}
		for($i=0; $i<count($this->op_var); $i++) {
			$this->sf.="<input type='hidden' name='op_var[]' value='".$this->op_var[$i]."'>";
		}
		for($i=0; $i<count($this->val_var); $i++) {
			$this->sf.="<input type='hidden' name='val_var[]' value='".htmlentities(stripslashes($this->val_var[$i]),ENT_QUOTES,$charset)."'>";
		}
		
		$this->sf.="<input type='hidden' name='delete_field' value=''/>";
		$this->sf.="<input type='hidden' name='add_prio' value=''/>";
		$this->sf.="<input type='hidden' name='min_prio' value=''/>";
	}
	
	// création de la requête SQL
	function make_proc() {
		global $msg;
		global $current_module;
		global $base_path;
		
		// récupération des champs à afficher
		if(count($this->print_field)==0) return "erreur"; // gestion de l'erreur a améliorer
		$champs = array();
		for($i=0; $i<count($this->print_field); $i++) {
			if(substr($this->print_field[$i],0,1)=="f") {
				if($this->list_fields[substr($this->print_field[$i],2)]["TABLE"] == "notices") $latable = "notices";
				else $latable = "ta_".$i;
				$champs[] =$latable.".".$this->list_fields[substr($this->print_field[$i],2)]["CHAMP"]." AS '".addslashes($this->list_fields[substr($this->print_field[0],2)]["TITLE"])."'";
			} else {
				$champs[] ="ncva_".$i.".notices_custom_".$this->pp->t_fields[substr($this->print_field[$i],2)]["DATATYPE"]." AS '".addslashes($this->pp->t_fields[substr($this->print_field[$i],2)]["TITRE"])."'";
			}
		}
		$liste_champs = implode(", ", $champs);
		
		// récupération des tables
		$tables = array();
		$param = array("print_field" => "a",   "fixed_params" => "f",   "dynamic_params" => "d");
		foreach($param as $key => $value) {
			for($i=0; $i<count($this->$key); $i++) {
				if(substr($this->{$key}[$i],0,1)=="f") { // champs du fichier de configuration
					// table principale
					if($this->list_fields[substr($this->{$key}[$i],2)]["TABLE"]=="notices") {
						$tables[]="notices";
					} else {
						$tables[]=$this->list_fields[substr($this->{$key}[$i],2)]["TABLE"]." AS t".$value."_".$i;
					}
					// jointures
					for($j=0; $j<count($this->list_fields[substr($this->{$key}[$i],2)]["JOINTURE"]); $j++) {
						for($k=0; $k<count($this->list_fields[substr($this->{$key}[$i],2)]["JOINTURE"][$j]["TABLE"]); $k++) {
							if($this->list_fields[substr($this->{$key}[$i],2)]["JOINTURE"][$j]["TABLE"][$k]["NAME"]=="notices") {
								$tables[]="notices";
							} else if($this->list_fields[substr($this->{$key}[$i],2)]["JOINTURE"][$j]["TABLE"][$k]["NAME"] != $this->list_fields[substr($this->{$key}[$i],2)]["TABLE"]) {
								$tables[]=$this->list_fields[substr($this->{$key}[$i],2)]["JOINTURE"][$j]["TABLE"][$k]["NAME"]." AS t".$value."_j_".$i;
							}
						}
					}
				} else { // champs perso
					$tables[]="notices_custom_values AS ncv".$value."_".$i;
				}
			}
		}
		// éliminer les doublons
		$tables = array_unique($tables);
		$liste_tables = implode(", ", $tables);
		
		// construction des clauses
		$where = "";
		
		// jointures pour les champs a afficher
		$jointure = array();
		for($i=0; $i<count($this->print_field); $i++) {
			if(substr($this->print_field[$i],0,1)=="f") {
				for($j=0; $j<count($this->list_fields[substr($this->print_field[$i],2)]["JOINTURE"]); $j++) {
					if($this->list_fields[substr($this->print_field[$i],2)]["JOINTURE"][$j]["TABLE"][0]["NAME"] == $this->list_fields[substr($this->print_field[$i],2)]["TABLE"]) $tleft="ta_".$i;
					else if($this->list_fields[substr($this->print_field[$i],2)]["JOINTURE"][$j]["TABLE"][0]["NAME"] == "notices") $tleft="notices";
					else $tleft="ta_j_".$i;
					if($this->list_fields[substr($this->print_field[$i],2)]["JOINTURE"][$j]["TABLE"][1]["NAME"] == $this->list_fields[substr($this->print_field[$i],2)]["TABLE"]) $tright="ta_".$i;
					if($this->list_fields[substr($this->print_field[$i],2)]["JOINTURE"][$j]["TABLE"][1]["NAME"] == "notices") $tright="notices";
					else $tright="ta_j_".$i;
					$jointure[] = $tleft.".".$this->list_fields[substr($this->print_field[$i],2)]["JOINTURE"][$j]["TABLE"][0]["ID"][0]["value"]."=".$tright.".".$this->list_fields[substr($this->print_field[$i],2)]["JOINTURE"][$j]["TABLE"][1]["ID"][0]["value"];
				}
			} else { // champs perso
				$jointure[] = "ncva_".$i.".notices_custom_origine = notices.notice_id";
				$jointure[] = "ncva_".$i.".notices_custom_champ = ".substr($this->print_field[$i],2); 
			}
		}
		
		// conditions fixes
		for($i=0; $i<count($this->fixed_params); $i++) {
			if(substr($this->fixed_params[$i],0,1)=="f") {
				for($j=0; $j<count($this->list_fields[substr($this->fixed_params[$i],2)]["JOINTURE"]); $j++) {
					if($this->list_fields[substr($this->fixed_params[$i],2)]["JOINTURE"][$j]["TABLE"][0]["NAME"] == $this->list_fields[substr($this->fixed_params[$i],2)]["TABLE"]) $tleft="tf_".$i;
					else if($this->list_fields[substr($this->fixed_params[$i],2)]["JOINTURE"][$j]["TABLE"][0]["NAME"] == "notices") $tleft="notices";
					else $tleft="tf_j_".$i;
					if($this->list_fields[substr($this->fixed_params[$i],2)]["JOINTURE"][$j]["TABLE"][1]["NAME"] == $this->list_fields[substr($this->fixed_params[$i],2)]["TABLE"]) $tright="tf_".$i;
					if($this->list_fields[substr($this->fixed_params[$i],2)]["JOINTURE"][$j]["TABLE"][1]["NAME"] == "notices") $tright="notices";
					else $tright="tf_j_".$i;
					$jointure[] = $tleft.".".$this->list_fields[substr($this->fixed_params[$i],2)]["JOINTURE"][$j]["TABLE"][0]["ID"][0]["value"]."=".$tright.".".$this->list_fields[substr($this->fixed_params[$i],2)]["JOINTURE"][$j]["TABLE"][1]["ID"][0]["value"];
				}
				if($this->list_fields[substr($this->fixed_params[$i],2)]["TABLE"] == "notices") $tf = "notices";
				else $tf = "tf_".$i;
				switch($this->op_param[$i]) {
					case "CONTAINS_ALL" :
						$op=" AND ";
						$argu = explode(" ",$this->val_param[$i]);
						for($j=0; $j<count($argu); $j++) {
							$argu[$j] = $tf.".".$this->list_fields[substr($this->fixed_params[$i],2)]["INDEX"].$this->operateur[$this->op_param[$i]]["DEB"].pmb_strtolower(convert_diacrit($argu[$j])).$this->operateur[$this->op_param[$i]]["FIN"];
						}
						$jointure[] =" (".implode($op,$argu).") ";
						break;
					case "CONTAINS_AT_LEAST" :
						$op=" OR ";
						$argu = explode(" ",$this->val_param[$i]);
						for($j=0; $j<count($argu); $j++) {
							$argu[$j] = $tf.".".$this->list_fields[substr($this->fixed_params[$i],2)]["INDEX"].$this->operateur[$this->op_param[$i]]["DEB"].pmb_strtolower(convert_diacrit($argu[$j])).$this->operateur[$this->op_param[$i]]["FIN"];
						}
						$jointure[] =" (".implode($op,$argu).") ";
						break;
					default :
						$jointure[] = $tf.".".$this->list_fields[substr($this->fixed_params[$i],2)]["INDEX"].$this->operateur[$this->op_param[$i]]["DEB"].pmb_strtolower(convert_diacrit($this->val_param[$i])).$this->operateur[$this->op_param[$i]]["FIN"];
				}
			} else { // champs perso
				$jointure[] = "ncvf_".$i.".notices_custom_origine = notices.notice_id";
				$jointure[] = "ncvf_".$i.".notices_custom_champ = ".substr($this->fixed_params[$i],2);
				switch($this->op_param[$i]) {
					case "CONTAINS_ALL" :
						$op=" AND ";
						$argu = explode(" ",$this->val_param[$i]);
						for($j=0; $j<count($argu); $j++) {
							$argu[$j] = "ncvf_".$i.".notices_custom_".$this->pp->t_fields[substr($this->fixed_params[$i],2)]["DATATYPE"].$this->operateur[$this->op_param[$i]]["DEB"].$argu[$j].$this->operateur[$this->op_param[$i]]["FIN"];
						}
						$jointure[] =" (".implode($op,$argu).") ";
						break;
					case "CONTAINS_AT_LEAST" :
						$op=" OR ";
						$argu = explode(" ",$this->val_param[$i]);
						for($j=0; $j<count($argu); $j++) {
							$argu[$j] = "ncvf_".$i.".notices_custom_".$this->pp->t_fields[substr($this->fixed_params[$i],2)]["DATATYPE"].$this->operateur[$this->op_param[$i]]["DEB"].$argu[$j].$this->operateur[$this->op_param[$i]]["FIN"];
						}
						$jointure[] =" (".implode($op,$argu).") ";
						break;
					default :
					$jointure[] = "ncvf_".$i.".notices_custom_".$this->pp->t_fields[substr($this->fixed_params[$i],2)]["DATATYPE"].$this->operateur[$this->op_param[$i]]["DEB"].$this->val_param[$i].$this->operateur[$this->op_param[$i]]["FIN"];
				}
			}
		}
		
		// conditions dynamiques
		for($i=0; $i<count($this->dynamic_params); $i++) {
			if(substr($this->dynamic_params[$i],0,1)=="f") {
				for($j=0; $j<count($this->list_fields[substr($this->dynamic_params[$i],2)]["JOINTURE"]); $j++) {
					if($this->list_fields[substr($this->dynamic_params[$i],2)]["JOINTURE"][$j]["TABLE"][0]["NAME"] == $this->list_fields[substr($this->dynamic_params[$i],2)]["TABLE"]) $tleft="td_".$i;
					else if($this->list_fields[substr($this->dynamic_params[$i],2)]["JOINTURE"][$j]["TABLE"][0]["NAME"] == "notices") $tleft="notices";
					else $tleft="td_j_".$i;
					if($this->list_fields[substr($this->dynamic_params[$i],2)]["JOINTURE"][$j]["TABLE"][1]["NAME"] == $this->list_fields[substr($this->dynamic_params[$i],2)]["TABLE"]) $tright="td_".$i;
					if($this->list_fields[substr($this->dynamic_params[$i],2)]["JOINTURE"][$j]["TABLE"][1]["NAME"] == "notices") $tright="notices";
					else $tright="td_j_".$i;
					$jointure[] = $tleft.".".$this->list_fields[substr($this->dynamic_params[$i],2)]["JOINTURE"][$j]["TABLE"][0]["ID"][0]["value"]."=".$tright.".".$this->list_fields[substr($this->dynamic_params[$i],2)]["JOINTURE"][$j]["TABLE"][1]["ID"][0]["value"];
				}
				if($this->list_fields[substr($this->dynamic_params[$i],2)]["TABLE"] == "notices") $td = "notices";
				else $td = "td_".$i;
				$jointure[] = $td.".".$this->list_fields[substr($this->dynamic_params[$i],2)]["INDEX"].$this->operateur[$this->op_var[$i]]["DEB"]."!!d_".$i."!!".$this->operateur[$this->op_var[$i]]["FIN"];
			} else { // champs perso
				$jointure[] = "ncvd_".$i.".notices_custom_origine = notices.notice_id";
				$jointure[] = "ncvd_".$i.".notices_custom_champ = ".substr($this->dynamic_params[$i],2);
				$jointure[] = "ncvd_".$i.".notices_custom_".$this->pp->t_fields[substr($this->dynamic_params[$i],2)]["DATATYPE"].$this->operateur[$this->op_var[$i]]["DEB"]."!!d_".$i."!!".$this->operateur[$this->op_var[$i]]["FIN"];
			}
		}
		
		$where .= implode(" AND ", $jointure);
		
		$requete = html_entity_decode("SELECT ".$liste_champs." FROM ".$liste_tables." WHERE ".$where);
		
		$param_var = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
		$param_var .= "<FIELDS>\n";
		for($i=0; $i<count($this->dynamic_params); $i++) { // A améliorer, pour l'instant tout est mis en type text !!!
			$param_var .= "<FIELD NAME=\"d_".$i."\" MANDATORY=\"yes\">\n";
			$param_var .= " <ALIAS><![CDATA[".$this->val_var[$i]."]]></ALIAS>\n";
			$param_var .= " <TYPE>text</TYPE>\n";
			$param_var .= " <OPTIONS FOR=\"text\">\n";
			$param_var .= " <SIZE>20</SIZE>\n";
			$param_var .= " <MAXSIZE>20</MAXSIZE>\n";
			$param_var .= " </OPTIONS>\n";
			$param_var .= " </FIELD>\n";
		}
 		$param_var .= "</FIELDS>";
		
		// insertion de la procédure dans la base de données
		$dbh = connection_mysql();
		$req = "INSERT INTO procs (name, requete, comment, autorisations, parameters) VALUES ('".$this->nom_proc."', '".addslashes($requete)."', '".$this->comment."', '".implode(" ",$this->userautorisation)."', '".addslashes($param_var)."')";
		$result = mysql_query($req, $dbh);
		
		// on n'utilse pas le template prévu pour cette étape
		$create_proc_form="
<form class='form-$current_module' name='search_form' action='!!url!!' method='post'>
	<h3>".$msg["create_proc"]." (!!etape!!/5)</h3>
	<div class='form-contenu'>
		!!resultat!!
	</div>
	<div class='row'>
		<input type='submit' class='bouton' value='".$msg["re_proc"]."' onClick=\"this.form.etape.value=1; this.form.action='!!url_next!!'; this.form.page.value=''; \"/>
	</div>
</form>";

		if($result) {
			$resultat = $msg["proc_ok"];
		} else {
			$resultat = $msg["proc_fail"];
		}
		
		$create_proc_form=str_replace("!!resultat!!",$resultat,$create_proc_form);
		return $create_proc_form;
	}
	
	function choix_info() {
		global $msg;
		global $current_module;
		global $base_path;
		
		// on n'utilse pas le template prévu pour cette étape
		$create_proc_form="
<form class='form-$current_module' name='search_form' action='!!url!!' method='post'>
	<h3>".$msg["create_proc"]." (!!etape!!/5)</h3>
	<div class='form-contenu'>
		<div class='erreur' align='justify'><img src='".$base_path."/images/alert.gif'>".$msg["warn_create_proc"]."</div>
		<br />
		<div class='row'>
			<label class='etiquette' for='form_name'>$msg[705]</label>
		</div>
		<div class='row'>
			<input type='text' name='nom_proc' value='' maxlength='255' class='saisie-50em' />
		</div>
		<div class='row'>
			<label class='etiquette' for='form_comment'>$msg[707]</label>
		</div>
		<div class='row'>
			<input type='text' name='comment' value='' maxlength='255' class='saisie-50em' />
		</div>
		<div class='row'>
			<label class='etiquette' for='form_comment'>$msg[procs_autorisations]</label>
			<input type='button' class='bouton_small' value='".$msg['tout_cocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,1);' align='middle'>
			<input type='button' class='bouton_small' value='".$msg['tout_decocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,0);' align='middle'>
		</div>
		<div class='row'>
			!!autorisations_users!!
		</div>
	</div>
	<div class='row'>
		<input type='submit' class='bouton' value='".$msg["502"]."' onClick=\"this.form.etape.value=!!etape_next!!; this.form.action='!!url_next!!'; this.form.page.value=''; \"/>
	</div>
	<input type='hidden' name='etape' value='!!etape!!'/>
</form>";
		
		
		// récupération des utilisateurs
		$dbh = connection_mysql();
		$requete_users = "SELECT userid, username FROM users order by username ";
		$res_users = mysql_query($requete_users, $dbh);
		$autorisation=array();
		while (list($all_userid,$all_username)=mysql_fetch_row($res_users)) {
			$autorisation[]=array(0,$all_userid,$all_username);
			}
		$autorisations_users="";
		while (list($row_number, $row_data) = each($autorisation)) {
			if ($row_data[0]) $autorisations_users.="<input type='checkbox' name='userautorisation[]' value='".$row_data[1]."' checked class='checkbox'>&nbsp;".$row_data[2]."&nbsp;&nbsp;";
			else $autorisations_users.="<input type='checkbox' name='userautorisation[]' value='".$row_data[1]."' class='checkbox'>&nbsp;".$row_data[2]."&nbsp;&nbsp;";
		}
		$create_proc_form = str_replace('!!autorisations_users!!', $autorisations_users, $create_proc_form);
	
			
		return $create_proc_form;
	}
	
	// Gestion de l'affichage
	function show_form($url,$url_next, $etape=1) {
		// $url : url courrante
		// $url_next : url pour l'étape suivante
		global $create_proc_form;
		global $msg;
		
		$this->url = $url;
		$this->url_next = $url_next;
		$this->etape = $etape;
		
		switch($this->etape) {
			case 1:
				$create_proc_form = $this->choix_info();
				break;
			case 2:
				$this->choix_param();
				$txtmsg=$msg["choix_param"];
				break;
			case 3:
				$this->choix_var();
				$txtmsg=$msg["choix_var"];
				break;
			case 4:
				$this->choix_champ();
				$txtmsg=$msg["choix_champ"];
				break;
			case 5:
				$create_proc_form = $this->make_proc();
				break;
			default :
				$create_proc_form = $this->etape =1;
				$this->choix_info();
				break;
		}
		
		// Modification du template
		$create_proc_form=str_replace("!!txtmsg!!",$txtmsg,$create_proc_form);
		$create_proc_form=str_replace("!!field_list!!",$this->r,$create_proc_form);
		$create_proc_form=str_replace("!!already_selected_fields!!",$this->sf,$create_proc_form);
		$create_proc_form=str_replace("!!url!!",$this->url,$create_proc_form);
		$create_proc_form=str_replace("!!url_next!!",$this->url_next,$create_proc_form);
		$create_proc_form=str_replace("!!etape!!",$this->etape,$create_proc_form);
		$create_proc_form=str_replace("!!etape_next!!",$this->etape+1,$create_proc_form);
		
		return $create_proc_form;
	}
}
?>
