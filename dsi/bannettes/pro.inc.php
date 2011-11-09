<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: pro.inc.php,v 1.35.2.1 2011-06-16 10:18:52 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

print "<h1>".$msg[dsi_ban_pro]."</h1>" ;
switch($suite) {
    case 'acces':
    	$bannette = new bannette($id_bannette) ;
    	print $bannette->show_form(); 
    	
		if ($pmb_javascript_office_editor) print $pmb_javascript_office_editor ;
		break;
    case 'add':
    	$bannette = new bannette(0) ;
    	print $bannette->show_form();
    	if ($pmb_javascript_office_editor) print $pmb_javascript_office_editor ;
        break;
    case 'delete':
    	$bannette = new bannette($id_bannette) ;
    	print $bannette->delete();  
        print get_bannette_pro ($msg[dsi_ban_search], $msg[dsi_ban_search_nom], './dsi.php?categ=bannettes&sub=pro', stripslashes($form_cb));
		print pmb_bidi(dsi_list_bannettes_info($form_cb, 0, $id_classement)) ;
		break;
    case 'update':
    	$bannette = new bannette($id_bannette) ;
    	$anc_categorie_lecteurs=    $bannette->categorie_lecteurs ;
    	$temp->id_bannette=         $id_bannette;		
		$temp->num_classement=      $num_classement;	
		$temp->nom_bannette=        $nom_bannette;	
		$temp->comment_gestion=	    $comment_gestion;
		$temp->comment_public=      $comment_public;	
		$temp->entete_mail =	    $entete_mail;	
		$temp->piedpage_mail =	    $piedpage_mail;	
		$temp->notice_tpl =	    	$notice_tpl;
		$temp->proprio_bannette=	$proprio_bannette;	
		$temp->bannette_auto=       $bannette_auto;	
		$temp->periodicite=			$periodicite;	
		$temp->diffusion_email=		$diffusion_email;
		$temp->statut_not_account=	$statut_not_account;
		$temp->nb_notices_diff=		$nb_notices_diff;
		$temp->categorie_lecteurs=	$categorie_lecteurs;
		$temp->update_type=			$update_type;
		$temp->date_last_envoi=		$form_date_last_envoi;
    	$temp->num_panier=			$num_panier;	
    	$temp->limite_type=			$limite_type;	
    	$temp->limite_nombre=		$limite_nombre;	
    	$temp->typeexport=			$typeexport;
    	$temp->prefixe_fichier=		$prefixe_fichier; 
    	$temp->group_pperso=		$group_pperso; 
    	$temp->param_export=array("genere_lien" => $genere_lien,
    							  "mere"=>$mere,
    							  "fille"=>$fille,
    							  "notice_mere"=>$notice_mere, 
    							  "notice_fille"=>$notice_fille, 
    							  "art_link"=>$art_link, 
    							  "bull_link"=>$bull_link,
    							  "perio_link"=>$perio_link,
    							  "bulletinage"=>$bulletinage, 
    							  "notice_art"=>$notice_art, 
    							  "notice_perio"=>$notice_perio);
		if($form_actif) {
			$bannette->update($temp); 
		
	    	// changement d'une catégorie d'affectation
	    	if ($majautocateg && $bannette->id_bannette && ($categorie_lecteurs!=$anc_categorie_lecteurs) && $categorie_lecteurs) {
	    		$req_lec = "select id_empr from empr where empr_categ='$anc_categorie_lecteurs'" ;
	    		$res_lec=mysql_query($req_lec, $dbh) ;
	    		while ($lec=mysql_fetch_object($res_lec)) {
	    			mysql_query("delete from bannette_abon where num_empr='$lec->id_empr' and num_bannette='$id_bannette'", $dbh) ;
	    			}
	    		$req_lec = "select id_empr from empr where empr_categ='$categorie_lecteurs'" ;
	    		$res_lec=mysql_query($req_lec, $dbh) ;
	    		while ($lec=mysql_fetch_object($res_lec)) {
	    			mysql_query("insert into bannette_abon (num_bannette, num_empr) values('$id_bannette', '$lec->id_empr')", $dbh) ;
    			}
    		}
		}
    	print get_bannette_pro ($msg[dsi_ban_search], $msg[dsi_ban_search_nom], './dsi.php?categ=bannettes&sub=pro', stripslashes($nom_bannette));
			
		print pmb_bidi(dsi_list_bannettes_info($form_cb, $id_bannette, $id_classement)) ;
        break;
    case 'search':
		print get_bannette_pro ($msg[dsi_ban_search], $msg[dsi_ban_search_nom], './dsi.php?categ=bannettes&sub=pro', stripslashes($form_cb));
		print pmb_bidi(dsi_list_bannettes_info($form_cb, $id_bannette, $id_classement)) ;
		break;
    case 'affect_equation':
    	if ($faire=="enregistrer") {
    		//Enregistrer les affectations
    		// selectionner les equations affichées
    		if ($id_classement>0) $equ = "select id_equation from equations where num_classement='$id_classement' and proprio_equation=0";
    		if ($id_classement==0) $equ = "select id_equation from equations where proprio_equation=0 ";
    		if ($id_classement==-1) $equ = "select id_equation from equations, bannette_equation where proprio_equation=0 and num_bannette='$id_bannette' and num_equation=id_equation";
    		$res = mysql_query($equ, $dbh) or die (mysql_error()." $equ ") ;
    		if (!$bannette_equation) $bannette_equation = array();
			while ($equa=mysql_fetch_object($res)) {
				mysql_query("delete from bannette_equation where num_equation='$equa->id_equation' and num_bannette='$id_bannette' ", $dbh) ; 
				$as = array_search($equa->id_equation,$bannette_equation) ;
				if (($as!==false) && ($as!==null) ) mysql_query("insert into bannette_equation set num_equation='$equa->id_equation', num_bannette='$id_bannette'", $dbh) ; 
				}
    		}
    	$bannette = new bannette($id_bannette) ;
    	print bannette_equation ($bannette->nom_bannette, $id_bannette) ;
		break;
    case 'affect_lecteurs':
    	if ($faire=="enregistrer") {
    		//Enregistrer les affectations
    		
    		// selectionner la localisation affichée
    		if ($pmb_lecteurs_localises && (string)$empr_location_id!="0") {
				if ((string)$empr_location_id=="") $empr_location_id=$deflt2docs_location;
				$restrict_loc = " and empr_location=$empr_location_id ";
				} else $restrict_loc = "";
    		
    		// selectionner les catégories affichées
    		if ($lect_restrict) $lect_query = " and empr_nom like '".str_replace("*","%",$lect_restrict."*")."'  order by nom_prenom, empr_cb " ;
    			else $lect_query = " order by nom_prenom, empr_cb limit 20 ";
   		
    		if ($id_categorie>0) $equ = "select id_empr, concat(empr_nom, ' ', empr_prenom) as nom_prenom, empr_cb from empr where empr_categ='$id_categorie' $restrict_loc ".$lect_query;
    		if ($id_categorie==0) $equ = "select id_empr, concat(empr_nom, ' ', empr_prenom) as nom_prenom, empr_cb from empr where 1 $restrict_loc ".$lect_query;
    		if ($id_categorie==-1) $equ = "select id_empr, concat(empr_nom, ' ', empr_prenom) as nom_prenom, empr_cb from empr, bannette_abon where num_bannette='$id_bannette' and num_empr=id_empr $restrict_loc ".$lect_query;
    		$res = mysql_query($equ, $dbh) or die (mysql_error()." $equ ") ;
    		if (!$bannette_abon) $bannette_abon = array();
			while ($empr=mysql_fetch_object($res)) {
				mysql_query("delete from bannette_abon where num_empr='$empr->id_empr' and num_bannette='$id_bannette'", $dbh) ; 
				$as = array_search($empr->id_empr,$bannette_abon) ;
				if (($as!==false) && ($as!==null) ) mysql_query("insert into bannette_abon set num_empr='$empr->id_empr', num_bannette='$id_bannette'", $dbh) ; 
				}
    		}
    	$bannette = new bannette($id_bannette) ;
    	print bannette_lecteur ($bannette->nom_bannette, $id_bannette) ;
		break;
    default:
		echo window_title($database_window_title.$msg[dsi_menu_title]);
		print get_bannette_pro ($msg[dsi_ban_search], $msg[dsi_ban_search_nom], './dsi.php?categ=bannettes&sub=pro', stripslashes($form_cb));
		print pmb_bidi(dsi_list_bannettes_info($form_cb, $id_bannette, $id_classement)) ;
        break;
    }

