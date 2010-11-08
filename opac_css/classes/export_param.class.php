<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: export_param.class.php,v 1.1 2009-05-04 15:09:03 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");


define("EXP_DEFAULT_OPAC",1);
define("EXP_DEFAULT_GESTION",2);
define("EXP_GLOBAL_CONTEXT",3);
define("EXP_SESSION_CONTEXT",4);

class export_param {
	
// ---------------------------------------------------------------
//		propriétés de la classe
// ---------------------------------------------------------------
	var $context=0;
	var $tab_params=array();
	var	$export_art_link=0;
	var $export_bulletinage=0;
	var $export_bull_link=0;
	var $export_perio_link=0;
	var $export_notice_perio_link=0;
	var $export_notice_art_link=0;
	var $export_mere=0;
	var $export_fille=0;
	var $export_notice_mere_link=0;
	var $export_notice_fille_link=0;
	var $generer_liens=0;
	
	
// ---------------------------------------------------------------
//		Constructeur de la classe
// ---------------------------------------------------------------	
	
	function export_param($context=2){		
		$this->context = $context;
		$this->init_var($this->context);
	}
	
	/***
	 * Affiche les paramètres d'export correspondant à la gestion
	 ***/
	function init_var($context){
		
		global $exportparam_export_art_link, $exportparam_export_bulletinage, $exportparam_export_bull_link, $exportparam_export_perio_link;
		global $exportparam_export_notice_perio_link, $exportparam_export_notice_art_link, $exportparam_export_mere, $exportparam_export_fille,	$exportparam_generer_liens;
		global $exportparam_export_notice_mere_link, $exportparam_export_notice_fille_link;
		global $opac_exp_export_art_link, $opac_exp_export_bulletinage, $opac_exp_export_bull_link, $opac_exp_export_perio_link, $opac_exp_export_notice_perio_link;
		global $opac_exp_export_notice_art_link, $opac_exp_export_mere, $opac_exp_export_fille, $opac_exp_generer_liens, $opac_exp_export_notice_mere_link, $opac_exp_export_notice_fille_link;
		global $genere_lien, $mere, $fille, $art_link, $bull_link, $perio_link, $bulletinage, $notice_art, $notice_perio, $notice_mere, $notice_fille;
		
		
		if($context==EXP_DEFAULT_GESTION){
			$this->export_art_link=$exportparam_export_art_link;
			$this->export_bull_link=$exportparam_export_bull_link;
			$this->export_perio_link=$exportparam_export_perio_link;
			$this->export_bulletinage=$exportparam_export_bulletinage;
			$this->export_notice_perio_link=$exportparam_export_notice_perio_link;
			$this->export_notice_art_link=$exportparam_export_notice_art_link;
			$this->export_mere=$exportparam_export_mere;
			$this->export_fille=$exportparam_export_fille;
			$this->generer_liens=$exportparam_generer_liens;
			$this->export_notice_mere_link=$exportparam_export_notice_mere_link;
			$this->export_notice_fille_link=$exportparam_export_notice_fille_link;			
		} elseif($context==EXP_DEFAULT_OPAC){
			$this->export_art_link=$opac_exp_export_art_link;
			$this->export_bull_link=$opac_exp_export_bull_link;
			$this->export_perio_link=$opac_exp_export_perio_link;
			$this->export_bulletinage=$opac_exp_export_bulletinage;
			$this->export_notice_perio_link=$opac_exp_export_notice_perio_link;
			$this->export_notice_art_link=$opac_exp_export_notice_art_link;
			$this->export_mere=$opac_exp_export_mere;
			$this->export_fille=$opac_exp_export_fille;
			$this->generer_liens=$opac_exp_generer_liens;
			$this->export_notice_mere_link=$opac_exp_export_notice_mere_link;
			$this->export_notice_fille_link=$opac_exp_export_notice_fille_link;			
		} elseif($context==EXP_SESSION_CONTEXT){
			$this->export_art_link=$_SESSION["param_export"]["art_link"];
			$this->export_bull_link=$_SESSION["param_export"]["bull_link"];
			$this->export_perio_link=$_SESSION["param_export"]["perio_link"];
			$this->export_bulletinage=$_SESSION["param_export"]["bulletinage"];
			$this->export_notice_perio_link=$_SESSION["param_export"]["notice_perio"];
			$this->export_notice_art_link=$_SESSION["param_export"]["notice_art"];
			$this->export_mere=$_SESSION["param_export"]["mere"];
			$this->export_fille=$_SESSION["param_export"]["fille"];
			$this->generer_liens=$_SESSION["param_export"]["genere_lien"];
			$this->export_notice_mere_link=$_SESSION["param_export"]["notice_mere"];
			$this->export_notice_fille_link=$_SESSION["param_export"]["notice_fille"];
		} elseif($context==EXP_GLOBAL_CONTEXT) {
			$this->export_art_link=$art_link;
			$this->export_bull_link=$bull_link;
			$this->export_perio_link=$perio_link;
			$this->export_bulletinage=$bulletinage;
			$this->export_notice_perio_link=$notice_perio;
			$this->export_notice_art_link=$notice_art;
			$this->export_mere=$mere;
			$this->export_fille=$fille;
			$this->generer_liens=$genere_lien;
			$this->export_notice_mere_link=$notice_mere;
			$this->export_notice_fille_link=$notice_fille;
		}		
	}
	
	function init_session(){
		global $genere_lien, $mere, $fille, $art_link, $bull_link, $perio_link, $bulletinage, $notice_art, $notice_perio, $notice_mere, $notice_fille;
		
		$_SESSION["param_export"]["genere_lien"]=$genere_lien;
		$_SESSION["param_export"]["mere"]=$mere;
		$_SESSION["param_export"]["fille"]=$fille;
		$_SESSION["param_export"]["notice_mere"]=$notice_mere;
		$_SESSION["param_export"]["notice_fille"]=$notice_fille;
		$_SESSION["param_export"]["bull_link"]=$bull_link;
		$_SESSION["param_export"]["art_link"]=$art_link;
		$_SESSION["param_export"]["perio_link"]=$perio_link;
		$_SESSION["param_export"]["bulletinage"]=$bulletinage;
		$_SESSION["param_export"]["notice_perio"]=$notice_perio;
		$_SESSION["param_export"]["notice_art"]=$notice_art;
			
	}
	
	/***
	 * Affiche les paramètres d'export correspondant à la gestion
	 ***/	
	
	function check_default_param(){
		
		global $form_param;

		if($this->generer_liens){
			$form_param = str_replace('!!checked_0!!','checked',$form_param);
			$form_param = str_replace('!!display_list!!','',$form_param);
		}
		else {
			$form_param = str_replace('!!checked_0!!','',$form_param);
			$form_param = str_replace('!!display_list!!','display:none',$form_param);
		}
		
		if($this->export_fille){
			$form_param = str_replace('!!checked_2!!','checked',$form_param);
			$form_param = str_replace('!!disabled_4!!','',$form_param);
		} else { 
			$form_param = str_replace('!!checked_2!!','',$form_param);
			$form_param = str_replace('!!disabled_4!!','disabled',$form_param);
		}
		
		if($this->export_mere){
			$form_param = str_replace('!!checked_1!!','checked',$form_param);
			$form_param = str_replace('!!disabled_3!!','',$form_param);
		} else {
			$form_param = str_replace('!!checked_1!!','',$form_param);
			$form_param = str_replace('!!disabled_3!!','disabled',$form_param);
		}
		
		if($this->export_bull_link){
			$form_param = str_replace('!!checked_3!!','checked',$form_param);
		} else {
			$form_param = str_replace('!!checked_3!!','',$form_param);
		}
		
		if($this->export_perio_link){
			$form_param = str_replace('!!checked_4!!','checked',$form_param);
			$form_param = str_replace('!!disabled_1!!','',$form_param);
		} else {
			$form_param = str_replace('!!checked_4!!','',$form_param);
			$form_param = str_replace('!!disabled_1!!','disabled',$form_param);
		}
		
		if($this->export_art_link){
			$form_param = str_replace('!!checked_5!!','checked',$form_param);
			$form_param = str_replace('!!disabled_2!!','',$form_param);
		} else {
			$form_param = str_replace('!!checked_5!!','',$form_param);
			$form_param = str_replace('!!disabled_2!!','disabled',$form_param);
		}
		
		if($this->export_bulletinage)
			$form_param = str_replace('!!checked_6!!','checked',$form_param);
		else 
			$form_param = str_replace('!!checked_6!!','',$form_param);		
		
		if($this->export_notice_perio_link)
			$form_param = str_replace('!!checked_7!!','checked',$form_param);
		else 
			$form_param = str_replace('!!checked_7!!','',$form_param);	
		
		if($this->export_notice_art_link)
			$form_param = str_replace('!!checked_8!!','checked',$form_param);
		else
			$form_param = str_replace('!!checked_8!!','',$form_param);
			
		if($this->export_notice_mere_link)
			$form_param = str_replace('!!checked_9!!','checked',$form_param);
	    else 
			$form_param = str_replace('!!checked_9!!','',$form_param);
			
		if($this->export_notice_fille_link)
			$form_param = str_replace('!!checked_10!!','checked',$form_param);
		 else 
			$form_param = str_replace('!!checked_10!!','',$form_param);
			
	
		return $form_param;
	}
	
	/***
 	 * Mise à jour des paramètres dans la base
 	 ***/	
	function update(){
		global $dbh;		
		
		if(!$this->tab_params)
			return;
		
		//construction de la requete			
		foreach($this->tab_params as $cle=>$valeur){
			$requete="UPDATE parametres SET ";		
			$affectation='';
			$affectation .= " valeur_param='".$valeur."' WHERE sstype_param='$cle'";		
			$requete .= $affectation;
			mysql_query($requete,$dbh);
		}	

		return;
	}
	
	/***
 	 * Récupération des paramètres dans un tableau selon le contexte
 	 ***/
	function get_parametres($context){

		if($context == EXP_DEFAULT_GESTION){
			$parametres["generer_liens"]=$this->generer_liens*1;
			$parametres["export_mere"]=$this->export_mere*1;
			$parametres["export_fille"]=$this->export_fille*1;
			$parametres["export_notice_art_link"]=$this->export_notice_art_link*1;
			$parametres["export_notice_perio_link"]=$this->export_notice_perio_link*1;
			$parametres["export_bulletinage"]=$this->export_bulletinage*1;
			$parametres["export_bull_link"]=$this->export_bull_link*1;
			$parametres["export_perio_link"]=$this->export_perio_link*1;
			$parametres["export_art_link"]=$this->export_art_link*1;
			$parametres["export_notice_mere_link"]=$this->export_notice_mere_link*1;
			$parametres["export_notice_fille_link"]=$this->export_notice_fille_link*1;
		} elseif ($context == EXP_DEFAULT_OPAC){
			$parametres["exp_generer_liens"]=$this->generer_liens*1;
			$parametres["exp_export_mere"]=$this->export_mere*1;
			$parametres["exp_export_fille"]=$this->export_fille*1;
			$parametres["exp_export_notice_art_link"]=$this->export_notice_art_link*1;
			$parametres["exp_export_notice_perio_link"]=$this->export_notice_perio_link*1;
			$parametres["exp_export_bulletinage"]=$this->export_bulletinage*1;
			$parametres["exp_export_bull_link"]=$this->export_bull_link*1;
			$parametres["exp_export_perio_link"]=$this->export_perio_link*1;
			$parametres["exp_export_art_link"]=$this->export_art_link*1;
			$parametres["exp_export_notice_mere_link"]=$this->export_notice_mere_link*1;
			$parametres["exp_export_notice_fille_link"]=$this->export_notice_fille_link*1;
		} elseif ($context == EXP_GLOBAL_CONTEXT || $context == EXP_SESSION_CONTEXT){
			$parametres["genere_lien"]=$this->generer_liens*1;
			$parametres["mere"]=$this->export_mere*1;
			$parametres["fille"]=$this->export_fille*1;
			$parametres["notice_art"]=$this->export_notice_art_link*1;
			$parametres["notice_perio"]=$this->export_notice_perio_link*1;
			$parametres["bulletinage"]=$this->export_bulletinage*1;
			$parametres["bull_link"]=$this->export_bull_link*1;
			$parametres["perio_link"]=$this->export_perio_link*1;
			$parametres["art_link"]=$this->export_art_link*1;
			$parametres["notice_mere"]=$this->export_notice_mere_link*1;
			$parametres["notice_fille"]=$this->export_notice_fille_link*1;
		}
		
		if($parametres)
			$this->tab_params = $parametres;
		return $parametres;
	}
	
}
?>