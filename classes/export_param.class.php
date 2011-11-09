<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: export_param.class.php,v 1.2.4.2 2011-05-26 08:15:10 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once("$include_path/templates/export_param.tpl.php");

define("EXP_DEFAULT_OPAC",1);
define("EXP_DEFAULT_GESTION",2);
define("EXP_GLOBAL_CONTEXT",3);
define("EXP_SESSION_CONTEXT",4);
define("EXP_DSI_CONTEXT",5);


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
	var $dsi_params=array();	
	
// ---------------------------------------------------------------
//		Constructeur de la classe
// ---------------------------------------------------------------	
	
	function export_param($context=2,$dsi_param=array()){		
		$this->context = $context;
		$this->dsi_params=$dsi_param;
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
		
		switch($context){
		   
			case EXP_DEFAULT_GESTION :
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
			break;		
			case EXP_DEFAULT_OPAC :
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
			break;		
			case EXP_SESSION_CONTEXT :
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
			break;
			case EXP_DSI_CONTEXT :
				if($this->dsi_params){
					$this->export_art_link=$this->dsi_params["art_link"];
					$this->export_bull_link=$this->dsi_params["bull_link"];
					$this->export_perio_link=$this->dsi_params["perio_link"];
					$this->export_bulletinage=$this->dsi_params["bulletinage"];
					$this->export_notice_perio_link=$this->dsi_params["notice_perio"];
					$this->export_notice_art_link=$this->dsi_params["notice_art"];
					$this->export_mere=$this->dsi_params["mere"];
					$this->export_fille=$this->dsi_params["fille"];
					$this->generer_liens=$this->dsi_params["genere_lien"];
					$this->export_notice_mere_link=	$this->dsi_params["notice_mere"];
					$this->export_notice_fille_link=$this->dsi_params["notice_fille"];
				}	
			break;
			default :
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
			break;
		}		
	}
	
	/***
	 * Initialisation des paramètres d'export de la session
	 ***/
	function init_session(){
		global $genere_lien, $mere, $fille, $art_link, $bull_link, $perio_link, $bulletinage, $notice_art, $notice_perio, $notice_mere, $notice_fille;
		
		$_SESSION["param_export"]='';
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
	 * Construction de la chaîne à passer en paramètres dans l'adresse (pour les iframes) 
	 ***/
	function get_parametres_to_string(){
				
		return "genere_lien=".rawurlencode($this->generer_liens)."&mere=".rawurlencode($this->export_mere)."&fille=".rawurlencode($this->export_fille).
			"&notice_mere=".rawurlencode($this->export_notice_mere_link)."&notice_fille=".rawurlencode($this->export_notice_fille_link)."&art_link=".rawurlencode($this->export_art_link).
		"&perio_link=".rawurlencode($this->export_perio_link)."&bull_link=".rawurlencode($this->export_bull_link)."&bulletinage=".rawurlencode($this->export_bulletinage).
		"&notice_perio=".rawurlencode($this->export_notice_perio_link)."&notice_art=".rawurlencode($this->export_notice_art_link);
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

	//n'importe quoi !		
	//		if(!$this->tab_params)
	//			return;
	//		
	//		//construction de la requete			
	//		foreach($this->tab_params as $cle=>$valeur){
	//			$requete="UPDATE parametres SET ";		
	//			$affectation='';
	//			$affectation .= " valeur_param='".$valeur."' WHERE sstype_param='$cle'";		
	//			mysql_query($requete,$dbh);
	//		}

	
		$requetes = array();
		switch ($this->context){
			case EXP_DEFAULT_GESTION :
				$requetes[] = "update parametres set valeur_param='".$this->generer_liens."' WHERE type_param = 'exportparam' and sstype_param='generer_liens'";
				$requetes[] = "update parametres set valeur_param='".$this->export_mere."' WHERE type_param = 'exportparam' and sstype_param='export_mere'";
				$requetes[] = "update parametres set valeur_param='".$this->export_fille."' WHERE type_param = 'exportparam' and sstype_param='export_fille'";
				$requetes[] = "update parametres set valeur_param='".$this->export_notice_art_link."' WHERE type_param = 'exportparam' and sstype_param='export_notice_art_link'";
				$requetes[] = "update parametres set valeur_param='".$this->export_notice_perio_link."' WHERE type_param = 'exportparam' and sstype_param='export_notice_perio_link'";
				$requetes[] = "update parametres set valeur_param='".$this->export_bulletinage."' WHERE type_param = 'exportparam' and sstype_param='export_bulletinage'";
				$requetes[] = "update parametres set valeur_param='".$this->export_bull_link."' WHERE type_param = 'exportparam' and sstype_param='export_bull_link'";
				$requetes[] = "update parametres set valeur_param='".$this->export_perio_link."' WHERE type_param = 'exportparam' and sstype_param='export_perio_link'";
				$requetes[] = "update parametres set valeur_param='".$this->export_art_link."' WHERE type_param = 'exportparam' and sstype_param='export_art_link'";
				$requetes[] = "update parametres set valeur_param='".$this->export_notice_mere_link."' WHERE type_param = 'exportparam' and sstype_param='export_notice_mere_link'";
				$requetes[] = "update parametres set valeur_param='".$this->export_notice_fille_link."' WHERE type_param = 'exportparam' and sstype_param='export_notice_fille_link'";				
			break;
			case EXP_DEFAULT_OPAC :
				$requetes[] = "update parametres set valeur_param='".$this->generer_liens."' WHERE type_param = 'opac' and sstype_param='exp_generer_liens'";
				$requetes[] = "update parametres set valeur_param='".$this->export_mere."' WHERE type_param = 'opac' and sstype_param='exp_export_mere'";
				$requetes[] = "update parametres set valeur_param='".$this->export_fille."' WHERE type_param = 'opac' and sstype_param='exp_export_fille'";
				$requetes[] = "update parametres set valeur_param='".$this->export_notice_art_link."' WHERE type_param = 'opac' and sstype_param='exp_export_notice_art_link'";
				$requetes[] = "update parametres set valeur_param='".$this->export_notice_perio_link."' WHERE type_param = 'opac' and sstype_param='exp_export_notice_perio_link'";
				$requetes[] = "update parametres set valeur_param='".$this->export_bulletinage."' WHERE type_param = 'opac' and sstype_param='exp_export_bulletinage'";
				$requetes[] = "update parametres set valeur_param='".$this->export_bull_link."' WHERE type_param = 'opac' and sstype_param='exp_export_bull_link'";
				$requetes[] = "update parametres set valeur_param='".$this->export_perio_link."' WHERE type_param = 'opac' and sstype_param='exp_export_perio_link'";
				$requetes[] = "update parametres set valeur_param='".$this->export_art_link."' WHERE type_param = 'opac' and sstype_param='exp_export_art_link'";
				$requetes[] = "update parametres set valeur_param='".$this->export_notice_mere_link."' WHERE type_param = 'opac' and sstype_param='exp_export_notice_mere_link'";
				$requetes[] = "update parametres set valeur_param='".$this->export_notice_fille_link."' WHERE type_param = 'opac' and sstype_param='exp_export_notice_fille_link'";
			break;
		}
		foreach($requetes as $rqt){
			mysql_query($rqt,$dbh);
		}
		return;
	}
	
	/***
 	 * Récupération des paramètres dans un tableau selon le contexte
 	 ***/
	function get_parametres($context){
		
		$this->context = $context;
		switch ($context){
			case EXP_DEFAULT_GESTION :
				$parametres["genere_lien"]=$this->generer_liens*1;
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
			break;
			case EXP_DEFAULT_OPAC :
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
			break;
			case EXP_DSI_CONTEXT :
				$parametres = $this->dsi_params; 
			break;
			default :
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
			break;
		}
		
		if($parametres)
			$this->tab_params = $parametres;
		return $parametres;
	}
	
}
?>