<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: rss_flux.class.php,v 1.22 2010-05-27 14:45:12 arenou Exp $

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
	var $contenu_du_flux = "" ; // le flux genere precedemment et mis en cache, vide si doit etre renouvele
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
			$requete = "SELECT id_rss_flux, nom_rss_flux, link_rss_flux, descr_rss_flux, lang_rss_flux, copy_rss_flux, editor_rss_flux, webmaster_rss_flux, ttl_rss_flux, img_url_rss_flux, img_title_rss_flux, img_link_rss_flux, format_flux, export_court_flux,";
			$requete .= "IF(date_add(rss_flux_last,INTERVAL ttl_rss_flux MINUTE)>sysdate(),rss_flux_content,'') as contenu_du_flux ";
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
				$this->contenu_du_flux		= $temp->contenu_du_flux ;
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
				$this->contenu_du_flux="";
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
		global $dbh ;
	
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
		
		
		$rqt="select idcaddie as id_obj, name as name_obj from caddie where type='NOTI' order by name ";
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
	
		// afin de revenir où on etait : $form_cb, le critere de recherche
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
		$req .= "format_flux       ='".$temp->format_flux        ."', " ;
		$req .= "export_court_flux ='".$temp->export_court_flux  ."' " ;
		
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
		while ($res = mysql_fetch_object($res_nb)) {
			$this->num_bannettes[]=$res->num_contenant ;
			$this->nb_bannettes++ ;
		}
	}
	
	// ---------------------------------------------------------------
	//		generation du fichier XML
	// ---------------------------------------------------------------
	function xmlfile() {
		
		global $pmb_bdd_version, $charset ;
		if (!$charset) $charset='ISO-8859-1';
		
		if (!$this->id_rss_flux) die();
		$this->envoi="<?xml version=\"1.0\" encoding=\"".$charset."\"?>
		<!-- RSS generated by PMB on ".addslashes(date("D, d/m/Y H:i:s"))." -->
		<rss version=\"2.0\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\">
			<channel>
				<title>".htmlspecialchars ($this->nom_rss_flux,ENT_QUOTES, $charset)."</title>
				<link>".htmlspecialchars ($this->link_rss_flux,ENT_QUOTES, $charset)."</link>
				<description>".htmlspecialchars ($this->descr_rss_flux,ENT_QUOTES, $charset)."</description>
				<language>".htmlspecialchars ($this->lang_rss_flux,ENT_QUOTES, $charset)."</language>
				<copyright>".htmlspecialchars ($this->copy_rss_flux,ENT_QUOTES, $charset)."</copyright>
				<lastBuildDate>".addslashes(date("D, d M Y H:i:s T"))."</lastBuildDate>
				<docs>http://backend.userland.com/rss</docs>
				<generator>PMB Version ".$pmb_bdd_version."</generator>
				<managingEditor>".htmlspecialchars ($this->editor_rss_flux,ENT_QUOTES, $charset)."</managingEditor>
				<webMaster>".htmlspecialchars ($this->webmaster_rss_flux,ENT_QUOTES, $charset)."</webMaster>
				<ttl>".$this->ttl_rss_flux."</ttl>";
		if ($this->img_url_rss_flux) {
			$this->envoi.="
				<image>
					<url>".htmlspecialchars ($this->img_url_rss_flux,ENT_QUOTES, $charset)."</url>
					<title>".htmlspecialchars ($this->img_title_rss_flux,ENT_QUOTES, $charset)."</title>
					<link>".htmlspecialchars ($this->img_link_rss_flux,ENT_QUOTES, $charset)."</link>
				</image>" ;
		}
		
		$this->envoi.="
				!!items!!
				</channel>
			</rss>
		";
	}
	
	// ---------------------------------------------------------------
	//		stocke_cache($cache) : stockage du flux en cache pour eviter de le recalculer a chaque appel 
	// ---------------------------------------------------------------
	function stocke_cache() {
		global $dbh;
		global $msg;
		
		if (!$this->id_rss_flux) return $msg[dsi_flux_no_access]; // impossible d'acceder 
	
		$requete = "update rss_flux set rss_flux_content='".addslashes($this->contenu_du_flux)."', rss_flux_last=sysdate() WHERE id_rss_flux='$this->id_rss_flux'";
		mysql_query($requete, $dbh);
	}
	
	
	// ---------------------------------------------------------------
	//		id des notices concernees, attention, on n'envoie que les publiques (statuts de notices)
	// ---------------------------------------------------------------
	function items_notices() {
	
		global $dbh, $liens_opac ;
		global $charset;
		global $opac_flux_rss_notices_order ;
		global $opac_notice_affichage_class;
		if (!$opac_flux_rss_notices_order) $opac_flux_rss_notices_order="index_serie, tnvol, index_sew";	
		if (!$charset) $charset='ISO-8859-1';
		
		if ($this->nb_bannettes) {
			$rqt[] = "select distinct notice_id, index_sew, create_date, update_date, index_serie, tnvol 
					from notices join bannette_contenu on num_notice=notice_id 
							join notice_statut on statut=id_notice_statut 
					where notice_visible_opac=1 and notice_visible_opac_abon=0 and num_bannette in (".implode(",",$this->num_bannettes).") ";
		}
		if ($this->nb_paniers) {
			$rqt[] = "select distinct notice_id, index_sew, create_date, update_date, index_serie, tnvol 
					from notices join caddie_content on object_id=notice_id 
							join notice_statut on statut=id_notice_statut 
					where notice_visible_opac=1 and notice_visible_opac_abon=0 and caddie_id in (".implode(",",$this->num_paniers).") ";
		}
		$rqtfinale = implode(' union ',$rqt) ;
		mysql_query ("create temporary table tmpfluxrss ENGINE=MyISAM $rqtfinale ",$dbh); // Thu, 27 Apr 2006 23:40:11 +0100
		$query_not = "select distinct notice_id, index_sew, DATE_FORMAT(create_date,'%a, %e %b %Y %T +0100') as pubdate from tmpfluxrss order by $opac_flux_rss_notices_order" ;
		$res = mysql_query ($query_not,$dbh) or die(mysql_error()."<br />".$query_not);
		while (($tmp=mysql_fetch_object($res))) {
			if($opac_notice_affichage_class != ""){
				$notice = new $opac_notice_affichage_class($tmp->notice_id, $liens_opac, "", 1);
			}else $notice = new notice_affichage($tmp->notice_id, $liens_opac, "", 1);
			$notice->visu_expl = 0 ;
			$notice->visu_explnum = 0 ;
			$notice->do_header();
			$retour_aff .= "<item>
								<title>".htmlspecialchars ($notice->notice_header_without_html,ENT_QUOTES, $charset)."</title>
								<pubDate>".htmlspecialchars ($tmp->pubdate,ENT_QUOTES, $charset)."</pubDate>
								<link>".htmlspecialchars (str_replace("!!id!!", $tmp->notice_id, $liens_opac['lien_rech_notice']),ENT_QUOTES, $charset)."</link>" ;

			$desc='';
			$desc_explnum='';
			if ($this->export_court_flux) {
				$notice->do_isbd(1,0);
				$desc=$notice->notice_isbd;
			} else {
				switch ($this->format_flux) {
					case 'TITLE' :
						$desc='';
						break;
					case 'ABSTRACT' :
						$desc=$notice->notice->n_resume.'<br />';
						break;
					case 'ISBD' :
					default :
						$notice->do_isbd(0,0);
						$desc=$notice->notice_isbd;
						$desc_explnum=$this->do_explnum($tmp->notice_id);
				}
			}
			$image = $this->do_image($notice->notice->code) ;
			$desc = str_replace("<br />","<br/>",$desc);
			$retour_aff .= "	<description>".htmlspecialchars(strip_tags($image.$desc,"<table><tr><td><br/><img>"),ENT_QUOTES, $charset)."</description>";
			$retour_aff .= $desc_explnum;
			$retour_aff .= "</item>" ;
		
		}
		$this->notices = $retour_aff ;
	}
	
	
	function do_image($code) {
		global $opac_show_book_pics ;
		global $opac_book_pics_url ;
		global $opac_url_base ;
	
		if (($code<>"") && ($opac_show_book_pics=='1') && ($opac_book_pics_url)) {
			$code_chiffre = pmb_preg_replace('/-|\.| /', '', $code);
			$url_image = $opac_book_pics_url ;
			$url_image_ok = $opac_url_base."getimage.php?url_image=".urlencode($url_image)."&noticecode=$code_chiffre" ;
			$image = "<img src='".$url_image_ok."' align='right' hspace='4' vspace='2'>";
		} else $image="" ;
		return $image ;
	}
	
	
	// fonction retournant les infos d'exemplaires numeriques pour une notice
	function do_explnum($no_notice) {
	
		global $dbh;
		global $charset;
		global $opac_url_base ;
		
		if (!$no_notice) return "";
		if (!$charset) $charset='ISO-8859-1';
		
		create_tableau_mimetype() ;
		
		// recuperation du nombre d'exemplaires
		$requete = "SELECT explnum_id, explnum_notice, explnum_nom, explnum_mimetype, explnum_url, length(explnum_data) as taille ";
		$requete .= "FROM explnum join notices on explnum_notice=notice_id join notice_statut on statut=id_notice_statut ";
		$requete .= "WHERE explnum_notice='$no_notice' and explnum_visible_opac=1 and explnum_visible_opac_abon=0 ";
		$requete .= "ORDER BY explnum_mimetype, explnum_id ";
		$res = mysql_query($requete, $dbh);
		
		$retour = "";
		while (($expl = mysql_fetch_object($res))) {
			$url=htmlspecialchars ($opac_url_base."doc_num.php?explnum_id=".$expl->explnum_id,ENT_QUOTES, $charset) ;
			$mime=htmlspecialchars ($expl->explnum_mimetype,ENT_QUOTES, $charset) ;
			$retour .= "<enclosure url=\"".$url."\" type=\"".$mime."\" length=\"".$expl->taille."\" />";
		}
		return $retour;
	}


} # fin de definition
