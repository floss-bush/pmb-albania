<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: abo.inc.php,v 1.22 2010-01-27 09:30:33 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if ($id_empr) {
	$result_empr = mysql_query("select concat(ifnull(concat(empr_nom,' '),''),empr_prenom) as nom_prenom from empr where id_empr=$id_empr") ;
	$nom_prenom_abo = @ mysql_result($result_empr, '0', 'nom_prenom');
}

if ($nom_prenom_abo) print "<h1>".$msg[dsi_ban_abo]." : $nom_prenom_abo</h1>" ;
	else print "<h1>".$msg[dsi_ban_abo]."</h1>" ;

switch($suite) {
    case 'acces':
    	print dsi_list_bannettes_abo($id_empr) ;
        break;
    case 'search':
		$query = "select id_empr from empr join bannette_abon on id_empr=num_empr where empr_cb='$form_cb' limit 1";
		$result = mysql_query($query, $dbh);
		$id_empr = @ mysql_result($result, '0', 'id_empr');
		if (($id_empr) && ($form_cb)) {
			print dsi_list_bannettes_abo($id_empr) ;
		} else {
			print get_cb_dsi ($msg[circ_tit_form_cb_empr], $msg[34], './dsi.php?categ=bannettes&sub=abo&suite=search', $form_cb);
			$ret =  dsi_list_empr($form_cb) ;
			if ($ret['id_empr']) print dsi_list_bannettes_abo($ret['id_empr']) ;
				else print $ret['message'] ;
		}
		break;
    case 'transform_equ':
    	// mettre à jour l'équation
    	$equation = new equation($id_equation) ;
    	$s = new search() ;
    	$equ_human = $s->make_serialized_human_query(stripslashes($requete)) ;
    	$temp->id_equation=         $id_equation;		
		$temp->num_classement=      0;	
		$temp->nom_equation=        $equ_human;	
		$temp->comment_equation=	$equation->comment_equation ;
		$temp->requete=				$requete;	
		$temp->proprio_equation=	$equation->proprio_equation;	
		$temp->update_type=			"C";
    	$equation->update($temp); 
    	print dsi_list_bannettes_abo($id_empr) ;
		break;
    case 'modif':
    	$bannette = new bannette($id_bannette) ;
    	print $bannette->show_form("abo");  
    	if ($pmb_javascript_office_editor) print $pmb_javascript_office_editor ;
		break;
    case 'delete':
    	$bannette = new bannette($id_bannette) ;
    	$bannette->delete() ;
    	print dsi_list_bannettes_abo($id_empr) ;
		break;
    case 'update':
    	$bannette = new bannette($id_bannette) ;
    	$temp->id_bannette=          $id_bannette;		
		$temp->num_classement=       $num_classement;	
		$temp->nom_bannette=         $nom_bannette;	
		$temp->comment_gestion=	     $comment_gestion;
		$temp->comment_public=       $comment_public;	
		$temp->entete_mail =	     $entete_mail;
		$temp->piedpage_mail =	     $piedpage_mail;
		$temp->notice_tpl =	      	 $notice_tpl;
		$temp->proprio_bannette=	 $id_empr;	
		$temp->bannette_auto=        $bannette_auto;	
		$temp->periodicite=          $periodicite;	
		$temp->diffusion_email=	     $diffusion_email;
		$temp->nb_notices_diff=	     $nb_notices_diff;
		$temp->categorie_lecteurs=   $categorie_lecteurs;	
		$temp->update_type=			$update_type;
		$temp->date_last_envoi=      $form_date_last_envoi;
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
    	if($form_actif) $bannette->update($temp); 
    	print dsi_list_bannettes_abo($id_empr) ;
        break;
    default:
		echo window_title($database_window_title.$msg[dsi_menu_title]);
		print get_cb_dsi ($msg[circ_tit_form_cb_empr], $msg[34], './dsi.php?categ=bannettes&sub=abo&suite=search', $form_cb);
        break;
    }

