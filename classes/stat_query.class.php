<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: stat_query.class.php,v 1.4 2009-05-30 13:46:11 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once ($class_path . "/parameters.class.php");
require_once("$include_path/templates/stat_opac.tpl.php");

class stat_query {
	
	var $id_query;
	var $action;
	var $id_vue_liee;
	
	function stat_query($id,$action,$idvue=0){
		$this->id_query=$id;
		$this->action=$action;
		$this->id_vue_liee = $idvue;
	}
	
	function proceed(){
		
		global $dbh; 
		global $msg, $id;
		
		switch($this->action){
			case 'configure':
				$hp=new parameters($this->id_query,"statopac_request");
				$hp->show_config_screen("admin.php?categ=opac&sub=stat&section=view_list&act=update_config&id_req=$this->id_query","admin.php?categ=opac&sub=stat&section=view_list");
				break;
			case 'update_config':
				$hp=new parameters($this->id_query,"statopac_request");
				$hp->update_config("admin.php?categ=opac&sub=stat&section=view_list");
				break;
			case 'update_request':
				//Ajout/Modification d'une requete
				if(!$this->id_vue_liee){
					$this->id_vue_liee = $this->get_vue_associee($this->id_query);
				}
				print $this->do_form_request($this->id_query,$this->id_vue_liee);
				break;
			case 'save_request':
				if(!$this->id_vue_liee){
					$this->id_vue_liee = $this->get_vue_associee($this->id_query);
				}
				$this->save_request($this->id_query,$this->id_vue_liee);
				break;
			case 'suppr_request':
				//Suppression d'une vue
				$this->delete_request($this->id_query);
				break;
			case 'final':
				$hp=new parameters($this->id_query,"statopac_request");
				if (preg_match_all("|!!(.*)!!|U",$hp->proc->requete,$query_parameters)) {
					$hp->get_final_query();
					$code=$hp->final_query;
					$id=$this->id_query;
				}				 
				include("./admin/opac/stat/execute.inc.php");
				break;
			case 'exec_req':
				// form pour params et validation
				$this->run_form($this->id_query, $dbh);
				break;					
			default:
				break;
		}
	}
		
	//Supprime une requete
	function delete_request($id_req){
		if($id_req){
			$req="DELETE FROM statopac_request where idproc='".$id_req."'";
			$resultat=mysql_query($req);
		}
	}
	
	
	//Affiche le formulaire de saisie d'une requete
	function do_form_request($request_id='',$vue_id=''){
		global $stat_view_request_form, $msg, $charset;
		
		if(!$request_id){
			$stat_view_request_form = str_replace('!!request_title!!',$msg['stat_create_query'],$stat_view_request_form);
			$stat_view_request_form = str_replace('!!name_request!!','',$stat_view_request_form);
			$stat_view_request_form = str_replace('!!code!!','',$stat_view_request_form);
			$stat_view_request_form = str_replace('!!comment!!','',$stat_view_request_form);
			$stat_view_request_form = str_replace('!!id_req!!','',$stat_view_request_form);
			$stat_view_request_form = str_replace('!!id_view!!',$vue_id,$stat_view_request_form);
			
			$rqt_colnom="select nom_col from statopac_vues_col where num_vue='".$vue_id."'";
			$res=mysql_query($rqt_colnom);
			if(mysql_num_rows($res) == 0){
				$stat_view_request_form = str_replace('!!liste_cols!!',$msg['stat_no_col_associate'],$stat_view_request_form);
			} else {
				$liste = "<select style='width:100%; height:140px' multiple='yes' ondblclick='right_to_left()' name='nom_col[]' >";
				$i=0;
				while(($col_nom = mysql_fetch_object($res))){
					$liste.= "<option value=$i>$col_nom->nom_col</option>";
					$i++;
				}
				$liste.="</select>";
				$stat_view_request_form = str_replace('!!liste_cols!!',$liste,$stat_view_request_form);
			}
			return $stat_view_request_form;
		} elseif($vue_id) {
			$stat_view_request_form = str_replace('!!request_title!!',$msg['stat_alter_query'],$stat_view_request_form);	
			$rqt = "select name , requete , comment from statopac_request where idproc='".$request_id."'";
			$resultat=mysql_query($rqt);	
			while(($req = mysql_fetch_object($resultat))){
				$stat_view_request_form = str_replace('!!name_request!!',htmlentities($req->name,ENT_QUOTES,$charset),$stat_view_request_form);
				$stat_view_request_form = str_replace('!!code!!',htmlentities($req->requete,ENT_QUOTES,$charset),$stat_view_request_form);
				$stat_view_request_form = str_replace('!!comment!!',htmlentities($req->comment,ENT_QUOTES,$charset),$stat_view_request_form);				
			}		
			$stat_view_request_form = str_replace('!!id_req!!',$request_id,$stat_view_request_form);
			$stat_view_request_form = str_replace('!!id_view!!',$vue_id,$stat_view_request_form);
			
			$rqt_colnom="select nom_col from statopac_vues_col where num_vue='".$vue_id."'";
			$res=mysql_query($rqt_colnom);
			if(mysql_num_rows($res) == 0){
				$stat_view_request_form = str_replace('!!liste_cols!!',$msg['stat_no_col_associate'],$stat_view_request_form);
			} else {
				$liste = "<select style='width:100%; height:140px' multiple='yes' ondblclick='right_to_left()' name='nom_col[]'>";
				$i=0;
				while(($col_nom = mysql_fetch_object($res))){
					$liste.= "<option value=$i>$col_nom->nom_col</option>";
					$i++;
				}
				$liste.="</select>";
				$stat_view_request_form = str_replace('!!liste_cols!!',$liste,$stat_view_request_form);
			}
		}
		
		return $stat_view_request_form;
	}
	

	//Insere ou enregistre une requete
	function save_request($request_id='', $vue_id=''){
		global $f_request_name, $f_request_code, $f_request_comment, $msg;
		
		$chaine = strpos($f_request_code,'VUE()');
		if($chaine !==false){
			if((!$request_id) && $vue_id){
					$req = "INSERT INTO statopac_request(name,requete,comment,num_vue) VALUES ('".$f_request_name."', '".$f_request_code."','".$f_request_comment."','".$vue_id."')";
					mysql_query($req);
			} else {
					$req = "UPDATE statopac_request SET name='".$f_request_name."', requete='".$f_request_code."', num_vue='".$vue_id."', comment='".$f_request_comment."' WHERE idproc='".$request_id."'";
					mysql_query($req);
			}
		} else{
			error_form_message($msg["stat_wrong_query_format"]);
		}
	}
	
	//Formulaire d'execution
	function run_form($id, $dbh) {
		global $msg;
		global $charset;
		
		$hp=new parameters($id,"statopac_request");
		if (preg_match_all("|!!(.*)!!|U",$hp->proc->requete,$query_parameters))
			$hp->gen_form("admin.php?categ=opac&sub=stat&section=view_list&act=final&id=$id");
		else echo "<script>document.location='admin.php?categ=opac&sub=stat&section=view_list&act=final&id=$id'</script>";
	}
	
	
	function get_vue_associee($id_req){
		
		$rqt="select num_vue from statopac_request where idproc='".addslashes($id_req)."'";
		$res = mysql_query($rqt);
		
		return mysql_result($res,0,0);
	}
}
?>