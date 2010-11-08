<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: suggestion_source.class.php,v 1.1 2009-07-31 14:37:10 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($include_path."/templates/suggestion_source.tpl.php");

class suggestion_source{
	
	var $id_source=0;
	var $libelle_source='';
	
	/*
	 * Constructeur
	 */
	function suggestion_source($id=0){
		global $dbh;
		
		$this->id_source = $id;
		
		if(!$this->id_source){
			$this->libelle_source ='';
		} else {
			$req="select libelle_source from suggestions_source where id_source='".$this->id_source."'";
			$res = mysql_query($req,$dbh);
			$src = mysql_fetch_object($res);
			$this->libelle_source = $src->libelle_source;
		}
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
	 * Formulaire d'ajout/modification
	 */
	function show_edit_form(){
		global $src_form, $msg, $charset;
		
		if(!$this->id_source){
			$src_form = str_replace('!!form_title!!',$msg[acquisition_ajout_src],$src_form);
			$src_form = str_replace('!!libelle!!','',$src_form);
			$src_form = str_replace('!!bouton_sup!!','',$src_form);
			$src_form = str_replace('!!id!!','',$src_form);
		} else {
			$src_form .= "<script type='text/javascript'>
				function confirm_del_src(){
					result = confirm(\"".$msg['acquisition_sugg_source_del']."\");
        			return result;
        		}
        		</script>";
			$src_form = str_replace('!!id!!',$this->id_source,$src_form);
			$src_form = str_replace('!!form_title!!',$msg[acquisition_modif_src],$src_form);
			$src_form = str_replace('!!libelle!!',htmlentities($this->libelle_source, ENT_QUOTES, $charset),$src_form);
			$btn_sup = "<input class='bouton' type='submit' name='del_src' id='del_src' value='$msg[63]' onclick='this.form.act.value=\"del\"; return confirm_del_src();'";
			$src_form = str_replace('!!bouton_sup!!',$btn_sup,$src_form);
		}
		
		print $src_form;
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
		$tab_src =array();
		$req = "select * from suggestions_source order by libelle_source";
		$res=mysql_query($req,$dbh);
		while ($row = mysql_fetch_object($res)){
			$tab_src[$row->id_source] = $row->libelle_source;
		}
		
		if(count($tab_src) == 0){
			$display .= "<tr><td>".$msg[acquisition_no_src_available]."</td></tr>";
		} 
		$parity=1;
		foreach($tab_src as $id_src=>$lib_src) {
			if ($parity % 2) {
				$pair_impair = "even";
			} else {
				$pair_impair = "odd";
			}
			$parity += 1;
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=acquisition&sub=src&act=modif&id_src=$id_src';\" ";
	        $display .= "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td><i>".htmlentities($lib_src, ENT_QUOTES, $charset)."</i></td>";
			$display .= "</tr>";
		}
		$display .= "</table>
			<input class='bouton' type='button' value=' ".$msg[acquisition_ajout_src]." ' onClick=\"document.location='./admin.php?categ=acquisition&sub=src&act=add'\" />";
		
		print $display;
	}
	
	/*
	 * Création/Modification
	 */
	function save(){
		
		global $dbh, $libelle;
		
		if(!$this->id_source){
			$req = "insert into suggestions_source set libelle_source='".$libelle."'";
		} else {
			$req="update suggestions_source set libelle_source='".$libelle."' where id_source='".$this->id_source."'";
		}		
		mysql_query($req,$dbh);
	}
	
	//Suppression d'une source
	function delete(){
		global $dbh,$msg;		
		
		$error = false;
		if($this->hasSuggestions()){
			error_message($msg[321],$msg['acquisition_sugg_source_used'],1, 'admin.php?categ=acquisition&sub=src');
			$error=true;
		} else {		
			$req="delete from suggestions_source where id_source='".$this->id_source."'";
			mysql_query($req,$dbh);
		}
		
		return $error;
	}
	
	//Vérifie si la source de suggestions est utilisee dans les suggestions	
	function hasSuggestions(){
		
		global $dbh;
		
		$q = "select count(1) from suggestions where sugg_source = '".$this->id_source."' ";
		$r = mysql_query($q, $dbh); 
		return mysql_result($r, 0, 0);
		
	}
}
?>