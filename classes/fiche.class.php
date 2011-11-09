<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: fiche.class.php,v 1.6.2.1 2011-07-01 13:29:03 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($include_path."/templates/fiche.tpl.php");
require_once($class_path."/parametres_perso.class.php");

class fiche{
	
	var $id_fiche = 0;
	var $p_perso = "";
	var $liste_ids =array();
	
	function fiche($id=0){
		global $prefix;
		$this->id_fiche = $id;
		
		$this->p_perso = new parametres_perso($prefix); 
	}
	
	/*
	 * Formulaire d'édition d'une fiche
	 */
	function show_edit_form(){
		
		global $form_edit_fiche,$msg, $charset, $act,$base_path;
		global $perso_word,$page;
		
		if($act == 'save_and_new')
			$perso_ = $this->p_perso->show_editable_fields(0);
		else $perso_ = $this->p_perso->show_editable_fields($this->id_fiche);
		if (!$this->p_perso->no_special_fields) {
			$perso="";
			$perso.=$perso_["CHECK_SCRIPTS"];
			for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
				$p=$perso_["FIELDS"][$i];
				$perso.="<div id='pperso_".$p["NAME"]."'  title=\"".htmlentities($p["TITRE"],ENT_QUOTES, $charset)."\">
							<div class='row'><label for='".$p["NAME"]."' class='etiquette'>".htmlentities($p["TITRE"],ENT_QUOTES, $charset)."</label></div>
							<div class='row'>".$p["AFF"]."</div>
						 </div>";
			}		
		}	
		if($act != 'save_and_new')$form_edit_fiche=str_replace('!!hidden_id!!',$this->id_fiche,$form_edit_fiche);	
		else $form_edit_fiche=str_replace('!!hidden_id!!','',$form_edit_fiche);
		if(!$this->id_fiche  || $act=='save_and_new'){
			$btn = "<input type='submit' class='bouton' value='".htmlentities($msg['fiche_save_and_new'],ENT_QUOTES,$charset)."' onclick='check_form();this.form.act.value=\"save_and_new\";' />";	
			$form_edit_fiche=str_replace('!!btn!!',$btn, $form_edit_fiche);
			$form_edit_fiche=str_replace('!!form_titre!!', $msg['fichier_form_saisie'], $form_edit_fiche);
			$form_edit_fiche=str_replace('!!btn_cancel!!',"",$form_edit_fiche);
			$form_edit_fiche=str_replace('!!btn_del!!',"",$form_edit_fiche);
			$form_edit_fiche=str_replace('!!form_action!!',"$base_path/fichier.php?categ=saisie", $form_edit_fiche);
		} else {
			$form_edit_fiche=str_replace('!!form_action!!',"$base_path/fichier.php?categ=consult&mode=search&sub=update&perso_word=$perso_word&page=$page&idfiche=".$this->id_fiche, $form_edit_fiche);
			$btn = "<input type='submit' class='bouton' value='".htmlentities($msg['77'],ENT_QUOTES,$charset)."' onclick='this.form.act.value=\"update\";' />";
			$form_edit_fiche=str_replace('!!btn!!',$btn, $form_edit_fiche);
			$form_edit_fiche=str_replace('!!form_titre!!', $msg['fichier_form_modify'], $form_edit_fiche);
			$form_edit_fiche=str_replace('!!btn_cancel!!',"<input type='button' class='bouton' value='".htmlentities($msg[76],ENT_QUOTES,$charset)."' 
			onclick=\"document.location='./fichier.php?categ=consult&mode=search&sub=view&perso_word=$perso_word&idfiche=".$this->id_fiche."';\" \>",$form_edit_fiche);
			$form_edit_fiche=str_replace('!!btn_del!!',"<input type='button' class='bouton' value='".htmlentities($msg[63],ENT_QUOTES,$charset)."' 
			onclick=\"document.location='./fichier.php?categ=consult&mode=search&sub=del&perso_word=$perso_word&idfiche=".$this->id_fiche."';\" \>",$form_edit_fiche);
		}
		$form_edit_fiche=str_replace('!!perso_fields!!', $perso, $form_edit_fiche);
		$form_edit_fiche=str_replace('!!visibility_prec!!',"style='display:none'",$form_edit_fiche);
		$form_edit_fiche=str_replace('!!visibility_suiv!!',"style='display:none'",$form_edit_fiche);
		$form_edit_fiche=str_replace('!!action_prec!!',"",$form_edit_fiche);
		$form_edit_fiche=str_replace('!!action_suiv!!',"",$form_edit_fiche);
		
		return $form_edit_fiche;
	
	}
	
	/*
	 * Affiche le formulaire de consultation d'une fiche
	 */
	function show_fiche_form(){
		
		global $form_edit_fiche,$msg,$charset;
		global $perso_word,$page;
		
		if(!$this->id_fiche) return;
		
		$values = $this->get_values($this->id_fiche);
		$this->get_info_navigation();

		foreach($values as $key=>$val){	
			$display .= "<div class='row'>
			<label class='etiquette'>".htmlentities($this->p_perso->t_fields[$key]['TITRE']." : ",ENT_QUOTES,$charset)."</label>";
			for($i=0;$i<count($val);$i++){
				$display.= "<span>".htmlentities($val[$i],ENT_QUOTES,$charset)."</span>";
			}
			$display .= "</div>";
		}	
		
		$btn = "<input type='button' class='bouton' value='".htmlentities($msg[62],ENT_QUOTES,$charset)."' onclick=\"
		document.location='./fichier.php?categ=consult&mode=search&sub=edit&perso_word=$perso_word&page=$page&idfiche=".$this->id_fiche."';\" />";
		$form_edit_fiche=str_replace('!!perso_fields!!', $display, $form_edit_fiche);
		$form_edit_fiche=str_replace('!!btn!!',$btn, $form_edit_fiche);
		$form_edit_fiche=str_replace('!!btn_cancel!!',"<input type='button' class='bouton' value='".htmlentities($msg[76],ENT_QUOTES,$charset)."' 
			onclick=\"document.location='./fichier.php?categ=consult&mode=search&perso_word=$perso_word&page=".$this->page."';\" \>",$form_edit_fiche);
		$form_edit_fiche=str_replace('!!form_titre!!', $msg['fichier_form_consult'], $form_edit_fiche);
		$form_edit_fiche=str_replace('!!hidden_id!!',$this->id_fiche,$form_edit_fiche);
	
		
		if($this->fiche_prec){
			$form_edit_fiche=str_replace('!!visibility_prec!!',"",$form_edit_fiche);
			$form_edit_fiche=str_replace('!!action_prec!!',
			"onclick=\"document.location='./fichier.php?categ=consult&mode=search&sub=view&perso_word=$perso_word&idfiche=".$this->fiche_prec."&i_search=".$this->i_fiche_prec."';\"",$form_edit_fiche);
		} else {
			$form_edit_fiche=str_replace('!!visibility_prec!!',"style='display:none';",$form_edit_fiche);
		}
		if($this->fiche_suiv){
			$form_edit_fiche=str_replace('!!visibility_suiv!!',"",$form_edit_fiche);
			$form_edit_fiche=str_replace('!!action_suiv!!',
			"onclick=\"document.location='./fichier.php?categ=consult&mode=search&sub=view&perso_word=$perso_word&idfiche=".$this->fiche_suiv."&i_search=".$this->i_fiche_suiv."';\"",$form_edit_fiche);
		} else {
			$form_edit_fiche=str_replace('!!visibility_suiv!!',"style='display:none';",$form_edit_fiche);
		}	
			
		$form_edit_fiche=str_replace('!!visibility_prec!!',"style='display:none';",$form_edit_fiche);
		$form_edit_fiche=str_replace('!!visibility_suiv!!',"style='display:none';",$form_edit_fiche);
		$form_edit_fiche=str_replace('!!action_prec!!',"",$form_edit_fiche);
		$form_edit_fiche=str_replace('!!action_suiv!!',"",$form_edit_fiche);
		$form_edit_fiche=str_replace('!!btn_del!!',"",$form_edit_fiche);
		
		return $form_edit_fiche;
	}
	
	
	/*
	 * Permmet lors de l'affichage d'une fiche de trouvé les infos de navigation en tenant compte de la recherche:
	 * id fiche précédante, 
	 * id fiche suivante
	 * page du retour à la liste, et du retour sur effacement
	 */
	function get_info_navigation(){
		global $dbh,$perso_word,$nb_per_page_search;
		global $i_search;
		
		$search_word = str_replace('*','%',$perso_word);
		
		$requete = "SELECT count(1) FROM fiche where infos_global like '%".$search_word."%' or index_infos_global like '%".$perso_word."%'";
		$res = mysql_query($requete, $dbh);
		$nbr_lignes = mysql_result($res, 0, 0);
					
		if(!$i_search) $limit="limit 0, 2";
		else $limit="limit ".($i_search-1).", 3";
		
		$req = "select id_fiche from fiche where infos_global like '%".$search_word."%' or index_infos_global like '%".$perso_word."%' $limit ";
		$res = mysql_query($req,$dbh);	
		$this->fiche_prec=0;
		$this->fiche_suiv=0;
		if ($nb=mysql_num_rows($res)) {	
			while($fic = mysql_fetch_object($res)){			
				$result[] = $fic->id_fiche;
			}
			if($i_search<1 && $nb>1){
				$this->fiche_suiv=$result[1];
				$this->i_fiche_suiv=$i_search+1;
			}
			if($i_search && $nb>1){				
				$this->fiche_prec=$result[0];
				$this->i_fiche_prec=$i_search-1;
				if($nb>2){
					$this->fiche_suiv=$result[2];
					$this->i_fiche_suiv=$i_search+1;
				}	
			}
		}
		$this->page=(int)(($i_search)/$nb_per_page_search)+1;	
	}

	/*
	 * Enregistrement d'une fiche
	 */
	function save(){
		global $prefix, $dbh,$msg,$charset;
		
		if(!$this->id_fiche){
			$req = "insert into fiche set infos_global='', index_infos_global=''"; 
			mysql_query($req,$dbh);
			$this->id_fiche = mysql_insert_id();
			print "<div class='row'><b>".htmlentities($msg['fiche_saved'],ENT_QUOTES,$charset)."</b></div>";
		} else {
			$req = "update fiche set infos_global='', index_infos_global='' where id_fiche='".$this->id_fiche."'"; 
			mysql_query($req,$dbh);
		}	
		//On met à jour les champs persos
		$this->p_perso->check_submited_fields();
		$this->p_perso->rec_fields_perso($this->id_fiche);
		
		//On met à jour l'index de la fiche
		$this->update_global_index($this->id_fiche);
	}
	
	/*
	 * suppression d'une fiche
	 */
	function delete(){		
		global $dbh;
		$req = "delete from fiche where id_fiche = ".$this->id_fiche;
		mysql_query($req,$dbh);	
		$req = "delete from ".$this->p_perso->prefix."_custom_custom_values where  ".$this->p_perso->prefix."_gestfic0_custom_origine = ".$this->id_fiche;
		mysql_query($req,$dbh);	
	}	
	
	/*
	 * Mis à jour de l'index d'une fiche
	 */
	function update_global_index($id){
		global $dbh, $prefix;
		
		$mots_perso=$this->p_perso->get_fields_recherche($id);
		if($mots_perso) {
			$infos_global.= $mots_perso.' ';
			$infos_global_index.= strip_empty_words($mots_perso).' ';	
		}
		$req = "update fiche set infos_global='".addslashes($infos_global)."', index_infos_global='".addslashes($infos_global_index)."' where id_fiche=$id";
		mysql_query($req,$dbh);		
	}
	
	/*
	 * Reindexation globale
	 */
	function reindex_all(){
		global $dbh;
		
		$req = "select id_fiche from fiche";
		$res = mysql_query($req,$dbh);
		while($fiche = mysql_fetch_object($res)){
			$this->update_global_index($fiche->id_fiche);
		}
	}
	
	/*
	 * Affiche le formulaire de reindexation
	 */
	function show_reindex_form(){
		global $form_reindex;
		
		print $form_reindex;
	}
	
	/*
	 * Affiche le formulaire/tableau résultat de recherche dans les champs persos
	 */
	function show_search_list($action='',$url_base='',$page=1){
		global $form_search, $fichier_menu_display, $perso_word, $prefix;
		global $dbh, $msg,$charset;
		global $nb_per_page_search;
		$search_word = str_replace('*','%',$perso_word);
		
		
		if(!$page) $page=1;
		$debut =($page-1)*$nb_per_page_search;
		$requete = "SELECT count(1) FROM fiche where infos_global like '%".$search_word."%' or index_infos_global like '%".$perso_word."%'";
		$res = mysql_query($requete, $dbh);
		$nbr_lignes = mysql_result($res, 0, 0);
		
		$req = "select id_fiche from fiche where infos_global like '%".$search_word."%' or index_infos_global like '%".$perso_word."%' LIMIT $debut,$nb_per_page_search ";
		$res = mysql_query($req,$dbh);
		
		while($fic = mysql_fetch_object($res)){			
			$result[$fic->id_fiche] = $this->get_values($fic->id_fiche,1);
		}
		$form_search = str_replace("!!perso_word!!",htmlentities(stripslashes($perso_word),ENT_QUOTES,$charset),$form_search);
		if(!$result){
			$form_search = str_replace("!!message_result!!",sprintf($msg['fichier_no_result_found'],$perso_word),$form_search);
			print $form_search;
		} else {
			$nav_bar = aff_pagination ($url_base, $nbr_lignes, $nb_per_page_search, $page, 10, false, true);      
			$form_search = str_replace("!!message_result!!","",$form_search);
			print $form_search;
			print $this->display_results_tableau($result,"",$debut);
			print $nav_bar;
		}
		
	}
	
	/*
	 * On récupère les valeurs des champs visibles correspondant à la fiche
	 */
	function get_values($id_fiche,$visible=0){
		global $dbh;
		
		if ((!$this->p_perso->no_special_fields)&&($id_fiche)) {
			
			if($visible) 
				$clause = " and multiple='1' ";
			else $clause = "";
			
			$requete="select ".$this->p_perso->prefix."_custom_champ,".$this->p_perso->prefix."_custom_origine,
				".$this->p_perso->prefix."_custom_small_text, ".$this->p_perso->prefix."_custom_text, 
				".$this->p_perso->prefix."_custom_integer, ".$this->p_perso->prefix."_custom_date, 
				".$this->p_perso->prefix."_custom_float, titre 
				from ".$this->p_perso->prefix."_custom_values 
				join ".$this->p_perso->prefix."_custom on (idchamp=".$this->p_perso->prefix."_custom_champ $clause)
				where ".$this->p_perso->prefix."_custom_origine=".$id_fiche." order by ordre";
			$resultat=mysql_query($requete,$dbh);
			while ($r=mysql_fetch_array($resultat)) {
				if(($this->p_perso->t_fields[$r[$this->p_perso->prefix."_custom_champ"]]["TYPE"]) == "list"){
					$req = "select ".$this->p_perso->prefix."_custom_list_lib as lib from ".$this->p_perso->prefix."_custom_lists where ".$this->p_perso->prefix."_custom_champ='".$r[$this->p_perso->prefix."_custom_champ"]."' and ".$this->p_perso->prefix."_custom_list_value='".$r[$this->p_perso->prefix."_custom_".$this->p_perso->t_fields[$r[$this->p_perso->prefix."_custom_champ"]]["DATATYPE"]]."'";
					$res = mysql_query($req,$dbh);
					if(mysql_num_rows($res)){
						while($list = mysql_fetch_object($res)){
							$values[$r[$this->p_perso->prefix."_custom_champ"]][]=$list->lib;
						}
					}
				} else {
					if($this->p_perso->t_fields[$r[$this->p_perso->prefix."_custom_champ"]]['DATATYPE'] == 'date'){
						$values[$r[$this->p_perso->prefix."_custom_champ"]][]=formatdate($r[$this->p_perso->prefix."_custom_".$this->p_perso->t_fields[$r[$this->p_perso->prefix."_custom_champ"]]["DATATYPE"]]);
					} else{
						$values[$r[$this->p_perso->prefix."_custom_champ"]][]=$r[$this->p_perso->prefix."_custom_".$this->p_perso->t_fields[$r[$this->p_perso->prefix."_custom_champ"]]["DATATYPE"]];
					}
				}			
					
			}
		} else $values=array();
		return $values;
	}
	
	function display_results_tableau($liste_result,$back_url="",$i_search_deb=0){
		
		global $dbh, $charset, $msg;
		global $perso_word,$page;
		$req = "select * from ".$this->p_perso->prefix."_custom where multiple=1 order by ordre";//where multiple=1"; 
		$res = mysql_query($req,$dbh);
		$display = "<table id='result_table' width='100%'><tr>";
		$nb_field=0;
		//print"<pre>";print_r($liste_result);print"</pre>";
		while($champ = mysql_fetch_object($res)){
			//print"<pre>";print_r($champ);print"</pre>";
			$field_id[]=$champ->idchamp;
			if($champ->multiple)$display .= "<th>".htmlentities($champ->titre,ENT_QUOTES,$charset)."</th>";
			$field_visible[$nb_field++]=$champ->multiple;			
		}
		$display .= "</tr>";		
		$cpt_ligne=0;
		foreach($liste_result as $index=>$liste){
			if(!$cpt_ligne++%2)		$class = "class='odd'";
			else $class = "class='even'";
			$this->liste_ids[] = $index;	
			$display .= "<tr $class onclick=\"document.location='./fichier.php?categ=consult&mode=search&sub=view&perso_word=$perso_word&page=$page&idfiche=$index&i_search=".$i_search_deb++."';\">";
			foreach($field_id as $idchamp ){
			 	$display.= "<td>";
				if($liste[$idchamp]){ 					
					$cpt=0;			
					foreach($liste[$idchamp] as $cle=>$valeur){
						if($cpt)$display.="<br />";
						$display.= htmlentities($valeur,ENT_QUOTES,$charset);
						$cpt++;
					}					
				}
			 	$display.= "</td>";
			}
			$display .= "</tr>";
		}
		$display .= "</table>";
	
		if($this->liste_ids)
			$display.="<input type='hidden' id='liste_ids' name='liste_ids' value='".implode(",",$this->liste_ids)."'";
		
		if($back_url){
			$display .= "
			<div class='row'>
				<input type='button' class='bouton' value='".htmlentities($msg['fichier_result_list_return'],ENT_QUOTES,$charset)."' onclick='document.location=\"$back_url\"' />
			</div>
			";
		}
		return $display;
	}
	
	
}