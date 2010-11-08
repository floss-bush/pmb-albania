<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: rss_flux.class.php,v 1.12 2009-12-22 09:43:04 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

// definition de la classe de gestion des 'flux RSS'
class rss_flux {

// ---------------------------------------------------------------
//		proprietes de la classe
// ---------------------------------------------------------------
	var $id_rss_flux = 0;	
	var $nom_rss_flux = ""; 
	var $link_rss_flux = "" ;
	var $descr_rss_flux = "" ;
	var $lang_rss_flux = "" ;
	var $copy_rss_flux = "" ;
	var $editor_rss_flux = "" ;
	var $webmaster_rss_flux = "" ;
	var $ttl_rss_flux = 0 ;
	var $img_url_rss_flux = "" ;
	var $img_title_rss_flux = "" ;
	var $img_link_rss_flux = "" ;

	var	$format_flux = "";
	var $export_court_flux = 0;

	var	$nb_paniers = 0;
	var	$nb_bannettes = 0;
	var	$num_paniers = array();
	var	$num_bannettes = array();
	var	$notices = "";
	
	// ---------------------------------------------------------------
	//		constructeur
	// ---------------------------------------------------------------
	function rss_flux($id=0) {
		if ($id) {
			$this->id_rss_flux = $id;
			$this->getData();
		} else {
			$this->id_rss_flux = 0;
			$this->getData();
		}
	}
	
	
	// ---------------------------------------------------------------
	//		getData() : recuperation infos
	// ---------------------------------------------------------------
	function getData() {
		global $dbh;
		
		if (!$this->id_rss_flux) {
			// pas d'identifiant. on retourne un tableau vide
		 	$this->id_rss_flux=0;
		 	$this->nom_rss_flux = "" ;
			$this->link_rss_flux = "" ;
			$this->descr_rss_flux = "" ;
			$this->lang_rss_flux = "" ;
			$this->copy_rss_flux = "" ;
			$this->editor_rss_flux = "" ;
			$this->webmaster_rss_flux = "" ;
			$this->ttl_rss_flux = 0 ;
			$this->img_url_rss_flux = "" ;
			$this->img_title_rss_flux = "" ;
			$this->img_link_rss_flux = "" ;
			$this->format_flux = "";
			$this->export_court_flux = 0;
			$this->compte_elements();
		} else {
			$requete = "SELECT id_rss_flux, nom_rss_flux, link_rss_flux, descr_rss_flux, lang_rss_flux, copy_rss_flux, editor_rss_flux, webmaster_rss_flux, ttl_rss_flux, img_url_rss_flux, img_title_rss_flux, img_link_rss_flux, format_flux, export_court_flux ";
			$requete .= "FROM rss_flux WHERE id_rss_flux='".$this->id_rss_flux."' " ;
			$result = mysql_query($requete, $dbh) or die ($requete."<br /> in rss_flux.class.php : ".mysql_error());
			if(mysql_num_rows($result)) {
				$temp = mysql_fetch_object($result);
			 	$this->id_rss_flux			= $temp->id_rss_flux ;
				$this->nom_rss_flux			= $temp->nom_rss_flux ;
				$this->link_rss_flux 		= $temp->link_rss_flux ;     
				$this->descr_rss_flux 		= $temp->descr_rss_flux ;    
				$this->lang_rss_flux 		= $temp->lang_rss_flux ;     
				$this->copy_rss_flux 		= $temp->copy_rss_flux ;     
				$this->editor_rss_flux 		= $temp->editor_rss_flux ;   
				$this->webmaster_rss_flux 	= $temp->webmaster_rss_flux ;
				$this->ttl_rss_flux 		= $temp->ttl_rss_flux ;      
				$this->img_url_rss_flux 	= $temp->img_url_rss_flux ;  
				$this->img_title_rss_flux 	= $temp->img_title_rss_flux ;
				$this->img_link_rss_flux 	= $temp->img_link_rss_flux ; 
				$this->format_flux			= $temp->format_flux ;
				$this->export_court_flux	= $temp->export_court_flux;
				$this->compte_elements();
				
			} else {
				// pas de flux avec cette cle
			 	$this->id_rss_flux=0;
			 	$this->nom_rss_flux = "" ;
				$this->link_rss_flux = "" ;
				$this->descr_rss_flux = "" ;
				$this->lang_rss_flux = "" ;
				$this->copy_rss_flux = "" ;
				$this->editor_rss_flux = "" ;
				$this->webmaster_rss_flux = "" ;
				$this->ttl_rss_flux = 0 ;
				$this->img_url_rss_flux = "" ;
				$this->img_title_rss_flux = "" ;
				$this->img_link_rss_flux = "" ;
				$this->format_flux="";
				$this->export_court_flux = 0;
				$this->compte_elements();
			}
		}
	}

		
	// ---------------------------------------------------------------
	//		show_form : affichage du formulaire de saisie
	// ---------------------------------------------------------------
	function show_form() {
	
		global $msg, $charset;
		global $dsi_flux_form;
		global $dbh, $PMBuserid;
	
		if($this->id_rss_flux) {
			$action = "./dsi.php?categ=fluxrss&sub=&id_rss_flux=$this->id_rss_flux&suite=update";
			$button_delete = "<input type='button' class='bouton' value='$msg[63]' onClick=\"confirm_delete();\">";
			$libelle = $msg['dsi_flux_form_modif'];
		} else {
			$action = "./dsi.php?categ=fluxrss&sub=&id_rss_flux=0&suite=update";
			$libelle = $msg['dsi_flux_form_creat'];
			$button_delete ='';
		}
	
		$dsi_flux_form = str_replace('!!libelle!!', $libelle, $dsi_flux_form);
		$dsi_flux_form = str_replace('!!id_rss_flux!!', $this->id_rss_flux, $dsi_flux_form);
		$dsi_flux_form = str_replace('!!action!!', $action, $dsi_flux_form);
		$dsi_flux_form = str_replace('!!nom_rss_flux!!'			, htmlentities($this->nom_rss_flux			,ENT_QUOTES, $charset), $dsi_flux_form);
		$dsi_flux_form = str_replace('!!link_rss_flux!!'		, htmlentities($this->link_rss_flux     	,ENT_QUOTES, $charset), $dsi_flux_form);
		$dsi_flux_form = str_replace('!!descr_rss_flux!!'		, htmlentities($this->descr_rss_flux    	,ENT_QUOTES, $charset), $dsi_flux_form);
		$dsi_flux_form = str_replace('!!lang_rss_flux!!'		, htmlentities($this->lang_rss_flux     	,ENT_QUOTES, $charset), $dsi_flux_form);
		$dsi_flux_form = str_replace('!!copy_rss_flux!!'		, htmlentities($this->copy_rss_flux     	,ENT_QUOTES, $charset), $dsi_flux_form);
		$dsi_flux_form = str_replace('!!editor_rss_flux!!'		, htmlentities($this->editor_rss_flux   	,ENT_QUOTES, $charset), $dsi_flux_form);
		$dsi_flux_form = str_replace('!!webmaster_rss_flux!!'	, htmlentities($this->webmaster_rss_flux	,ENT_QUOTES, $charset), $dsi_flux_form);
		$dsi_flux_form = str_replace('!!ttl_rss_flux!!'			, htmlentities($this->ttl_rss_flux      	,ENT_QUOTES, $charset), $dsi_flux_form);
		$dsi_flux_form = str_replace('!!img_url_rss_flux!!'		, htmlentities($this->img_url_rss_flux  	,ENT_QUOTES, $charset), $dsi_flux_form);
		$dsi_flux_form = str_replace('!!img_title_rss_flux!!'	, htmlentities($this->img_title_rss_flux	,ENT_QUOTES, $charset), $dsi_flux_form);
		$dsi_flux_form = str_replace('!!img_link_rss_flux!!'	, htmlentities($this->img_link_rss_flux 	,ENT_QUOTES, $charset), $dsi_flux_form);
		$dsi_flux_form = str_replace('!!format_flux!!'			, htmlentities($this->format_flux       	,ENT_QUOTES, $charset), $dsi_flux_form);
		$dsi_flux_form = str_replace('!!export_court!!'			, $this->export_court_flux ? 'checked' : '', $dsi_flux_form);
		
		$rqt="select idcaddie as id_obj, name as name_obj from caddie where type='NOTI' ";
		if ($PMBuserid!=1) $rqt.=" and (autorisations='$PMBuserid' or autorisations like '$PMBuserid %' or autorisations like '% $PMBuserid %' or autorisations like '% $PMBuserid') ";
		$rqt.=" order by name ";
		
		$result = mysql_query($rqt, $dbh) or die ($rqt."<br /> in rss_flux.class.php : ".mysql_error());
		$paniers = "";
		while (($contenant = mysql_fetch_object($result))) {
			if (array_search($contenant->id_obj,$this->num_paniers)!==false) $checked="checked" ; 
				else $checked="" ;
			$paniers .= "<div class='usercheckbox'>
							<input  type='checkbox' id='paniers[".$contenant->id_obj."]' name='paniers[]' ".$checked." value='".$contenant->id_obj."' />
							<label for='paniers[".$contenant->id_obj."]' >".htmlentities($contenant->name_obj,ENT_QUOTES, $charset)."</label>
						</div>";	
		}
		$dsi_flux_form = str_replace('!!paniers!!', $paniers,  $dsi_flux_form);
		
		$rqt="select id_bannette as id_obj, nom_bannette as name_obj from bannettes where proprio_bannette=0 order by nom_bannette ";
		$result = mysql_query($rqt, $dbh) or die ($rqt."<br /> in rss_flux.class.php : ".mysql_error());
		$bannettes = "";
		while (($contenant = mysql_fetch_object($result))) {
			if (array_search($contenant->id_obj,$this->num_bannettes)!==false) $checked="checked" ; 
				else $checked="" ;
			$bannettes .= "<div class='usercheckbox'>
							<input  type='checkbox' id='bannettes[".$contenant->id_obj."]' name='bannettes[]' ".$checked." value='".$contenant->id_obj."' />
							<label for='bannettes[".$contenant->id_obj."]' >".htmlentities($contenant->name_obj,ENT_QUOTES, $charset)."</label>
							</div>";	
		}
		$dsi_flux_form = str_replace('!!bannettes!!', $bannettes,  $dsi_flux_form);
		
		$dsi_flux_form = str_replace('!!delete!!', $button_delete,  $dsi_flux_form);
	
		// afin de revenir ou on etait : $form_cb, le critere de recherche
		global $form_cb ;
		$dsi_flux_form = str_replace('!!form_cb!!', $form_cb,  $dsi_flux_form);
		print $dsi_flux_form;
	}
	
	// ---------------------------------------------------------------
	//		delete() : suppression 
	// ---------------------------------------------------------------
	function delete() {
		global $dbh;
		global $msg;
		
		if (!$this->id_rss_flux) return $msg[dsi_flux_no_access]; // impossible d'acceder 
	
		$requete = "delete from rss_flux_content WHERE num_rss_flux='$this->id_rss_flux'";
		mysql_query($requete, $dbh);
	
		$requete = "delete from rss_flux WHERE id_rss_flux='$this->id_rss_flux'";
		mysql_query($requete, $dbh);
	}
	
	
	// ---------------------------------------------------------------
	//		update 
	// ---------------------------------------------------------------
	function update($temp) {
	
		global $dbh;
		
		if ($this->id_rss_flux) {
			// update
			$req = "UPDATE rss_flux set ";
			$clause = " WHERE id_rss_flux='".$this->id_rss_flux."' ";
		} else {
			$req = "insert into rss_flux set ";
			$clause = "";
		}
		$req .= "id_rss_flux       ='".$temp->id_rss_flux        ."', " ;
		$req .= "nom_rss_flux      ='".$temp->nom_rss_flux       ."', " ;
		$req .= "link_rss_flux     ='".$temp->link_rss_flux      ."', " ;
		$req .= "descr_rss_flux    ='".$temp->descr_rss_flux     ."', " ;
		$req .= "lang_rss_flux     ='".$temp->lang_rss_flux      ."', " ;
		$req .= "copy_rss_flux     ='".$temp->copy_rss_flux      ."', " ;
		$req .= "editor_rss_flux   ='".$temp->editor_rss_flux    ."', " ;
		$req .= "webmaster_rss_flux='".$temp->webmaster_rss_flux ."', " ;
		$req .= "ttl_rss_flux      ='".$temp->ttl_rss_flux       ."', " ;
		$req .= "img_url_rss_flux  ='".$temp->img_url_rss_flux   ."', " ;
		$req .= "img_title_rss_flux='".$temp->img_title_rss_flux ."', " ;
		$req .= "img_link_rss_flux ='".$temp->img_link_rss_flux  ."', " ;
		$req .= "export_court_flux ='".$temp->export_court_flux  ."', " ;
		$req .= "format_flux       ='".$temp->format_flux        ."' " ;
	
		$req.=$clause ;
		$res = mysql_query($req, $dbh) or die ($req) ;
		if (!$this->id_rss_flux) $this->id_rss_flux = mysql_insert_id() ;
		if (!$this->id_rss_flux) die ("Pb grave pendant l'enregistrement du flux");
		
		mysql_query("delete from rss_flux_content where num_rss_flux='$this->id_rss_flux' " ) ;
		for ($i=0;$i<count($temp->num_paniers);$i++) {
			mysql_query("insert into rss_flux_content set num_rss_flux='$this->id_rss_flux', type_contenant='CAD', num_contenant='".$temp->num_paniers[$i]."' " ) ;
		}
	
		for ($i=0;$i<count($temp->num_bannettes);$i++) {
			mysql_query("insert into rss_flux_content set num_rss_flux='$this->id_rss_flux', type_contenant='BAN', num_contenant='".$temp->num_bannettes[$i]."' " ) ;
		}
	}
	
	
	// ---------------------------------------------------------------
	//		compte_elements() : methode pour pouvoir recompter en dehors !
	// ---------------------------------------------------------------
	function compte_elements() {
		global $dbh ;
		
		$this->nb_paniers=0;
		$this->nb_bannettes=0;
		$this->num_paniers=array();
		$this->num_bannettes=array();
	
		$req_nb = "SELECT num_contenant from rss_flux_content WHERE num_rss_flux='".$this->id_rss_flux."' and type_contenant='CAD' " ;
		$res_nb = mysql_query($req_nb, $dbh) or die ($req_nb."<br /> in rss_flux.class.php : ".mysql_error());
		while (($res = mysql_fetch_object($res_nb))) {
			$this->num_paniers[]=$res->num_contenant ;
			$this->nb_paniers++ ;
		}
		
		$req_nb = "SELECT num_contenant from rss_flux_content WHERE num_rss_flux='".$this->id_rss_flux."' and type_contenant='BAN' " ;
		$res_nb = mysql_query($req_nb, $dbh) or die ($req_nb."<br /> in rss_flux.class.php : ".mysql_error());
		while (($res = mysql_fetch_object($res_nb))) {
			$this->num_bannettes[]=$res->num_contenant ;
			$this->nb_bannettes++ ;
		}
	}

} # fin de definition
