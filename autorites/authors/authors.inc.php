<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: authors.inc.php,v 1.12 2010-12-06 15:51:18 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// fonctions communes aux pages de gestion des autorités
require('./autorites/auth_common.inc.php');

// classe de gestion des auteurs
include("$class_path/author.class.php");
include("$include_path/templates/authors.tpl.php");

// gestion des auteurs
print "<h1>".$msg[140]."&nbsp;: ". $msg[133]."</h1>";


switch($sub) {
	case 'reach':
		include('./autorites/authors/authors_list.inc.php');
		break;
	case 'delete':
		$auteur = new auteur($id);
		$sup_result = $auteur->delete();
		if(!$sup_result)
			include('./autorites/authors/authors_list.inc.php');
		else {
			error_message($msg[132], $sup_result, 1, "./autorites.php?categ=auteurs&sub=author_form&id=$id");
		}
		break;
	case 'replace':
		if(!$by) {
			$auteur = new auteur($id);
			$auteur->replace_form();
		} else {
			// routine de remplacement
			$auteur = new auteur($id);
			$rep_result = $auteur->replace($by,$aut_link_save);
			if(!$rep_result)
				include('./autorites/authors/authors_list.inc.php');
			else {
				error_message($msg[132], $rep_result, 1, "./autorites.php?categ=auteurs&sub=author_form&id=$id");
			}
		}
		break;
	case 'update':
		// mettre à jour responsabilité id
		// mise à jour d'un auteur
		$author = array(
				'type' 			=> $author_type,
				'name' 			=> $author_nom,
				'rejete' 		=> $author_rejete,
				'date' 			=> $date,
				'author_web'	=> $author_web,
				'author_comment'=> $author_comment,
				'voir_id' 		=> $voir_id,
				'lieu'			=> $lieu,
				'ville'			=> $ville,
				'pays'			=> $pays,
				'subdivision'	=> $subdivision,
				'numero'		=> $numero);
		$auteur = new auteur($id);
		$auteur->update($author);
		$type_autorite=$author_type;
		include('./autorites/authors/authors_list.inc.php');
		break;
	case 'author_form':
		// création/modification d'un responsable
		if(!$id) $auteur = new auteur(); // affichage du form pour création
			else $auteur = new auteur ($id); // affichage du form pour modification
		$auteur->show_form($type_autorite);
		break;
	case 'author_last':
		$last_param=1;
		$tri_param = "order by author_id desc ";
		$limit_param = "limit 0, $pmb_nb_lastautorities ";
		$clef = "";
		$nbr_lignes = 0 ;
		include('./autorites/authors/authors_list.inc.php');
		break;
	case 'duplicate':
		$auteur = new auteur($id);
		$auteur->id=0 ;
		$auteur->duplicate_from_id = $id ; 
		$auteur->type = $type_autorite;
		$auteur->show_form($type_autorite);
		break;
	default:
		// affichage du début de la liste
		include('./autorites/authors/authors_list.inc.php');
		break;
}
