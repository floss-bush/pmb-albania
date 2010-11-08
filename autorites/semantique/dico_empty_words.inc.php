<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: dico_empty_words.inc.php,v 1.3 2010-02-23 10:29:24 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/semantique.class.php");
require_once("$include_path/templates/dico_empty_words.tpl.php");

$baseurl="./autorites.php?categ=semantique&sub=empty_words&action=".$action;

//si on recherche une clé spécifique, on remplace !!cle!! par la clé sinon par rien
if ($search_empty_word) $autorites_list_empty_word=str_replace("!!cle!!","'".stripslashes($search_empty_word)."'",$autorites_list_empty_word);
		else $autorites_list_empty_word=str_replace("!!cle!!","",$autorites_list_empty_word);

switch ($action) {
	case 'add':
		print $autorites_add_empty_word;
	break;
	case 'update':
		if ($text_empty_word) {
			//vérification de l'existence du mot
			$rqt_exist="select id_mot from mots where mot='".addslashes($text_empty_word)."'";
			$query_exist=mysql_query($rqt_exist);
			if (!mysql_num_rows($query_exist)) {
				//insertion d'un nouveau mot
				$rqt_ins="insert into mots (mot) values ('".addslashes($text_empty_word)."')";
				@mysql_query($rqt_ins);
				//recherche de l'id du mot inséré
				$rqt_search_id="select id_mot from mots where mot='".addslashes($text_empty_word)."'";
				$query_search_id=mysql_query($rqt_search_id);
				if ($query_search_id&&mysql_num_rows($query_search_id)) {
					$r=mysql_fetch_object($query_search_id);
					//insertion dans la table de lien entre mots que le mot est vide
					$rqt_ins="insert into linked_mots (num_mot,num_linked_mot,type_lien) values ('".$r->id_mot."',0,3)";
					@mysql_query($rqt_ins);		
					semantique::gen_table_empty_word();		
				}
			} else {
				print "<script> alert('".$msg["word_exist"]."'); document.location='./autorites.php?categ=semantique&sub=empty_words&action=add';</script>";
			}			
		}
		$baseurl="&text_empty_word=".rawurlencode($text_empty_word);
	break;
	case 'search':
		if ($search_empty_word) {
			$search_empty_word=str_replace("*","%",$search_empty_word);
			$clause=" and mot like '".$search_empty_word."%'";	
		}
		if ($type_mot_vide) {
			$types_mots_vides=$type_mot_vide;
			$autorites_list_empty_word=str_replace("!!checked".$type_mot_vide."!!","checked",$autorites_list_empty_word);	
		} else $autorites_list_empty_word=str_replace("!!checked0!!","checked",$autorites_list_empty_word);	
		$baseurl.="&search_empty_word=".rawurlencode($search_empty_word)."&type_mot_vide=".$type_mot_vide;
		break;
	case 'last_words':
		if (!$nb_per_page) $nb_per_page=$nb_per_page_search;
		$tri=" id_mot desc";
		break;
	case 'calculate':
		if ($nb_noti) {
			$rqt="select count(notice_id) from notices";
			$execute_query=mysql_query($rqt);
			$r=mysql_fetch_array($execute_query);
			$pmb_nb_noti_calc_empty_words=$nb_noti;
			@mysql_query("update parametres set valeur_param='".$nb_noti."' where type_param='pmb' and sstype_param='nb_noti_calc_empty_words'");
			$nb_noti=floor(($r[0]*$nb_noti)/100);
			semantique::calculate_empty_words($nb_noti);
			semantique::gen_table_empty_word();
		}	
		break;
	case 'del':
		if ($id_mot&&$type_lien) {
			@mysql_query("delete from linked_mots where num_mot=".$id_mot." and num_linked_mot=0 and type_lien=".$type_lien);	
			$rqt="select num_mot from linked_mots where num_mot=".$id_mot." or num_linked_mot=".$id_mot;
			$query_exist=mysql_query($rqt);
			if ($query_exist&&!mysql_num_rows($query_exist)) @mysql_query("delete from mots where id_mot=".$id_mot);	
			semantique::gen_table_empty_word();	
		}
		break;
	default:
		
	break;
}

if ($action!='add') {
	//nombre de notices pour le calcul
	if (!$pmb_nb_noti_calc_empty_words) $pmb_nb_noti_calc_empty_words="50";
	$autorites_list_empty_word=str_replace("!!nb_noti!!",$pmb_nb_noti_calc_empty_words,$autorites_list_empty_word);
	//pagination
	if (!$page) $page=1;
	if (!$nb_per_page) $nb_per_page=$nb_per_page_gestion;
	$limit="limit ".(($page-1)*$nb_per_page).",".$nb_per_page;
	//tous les mots vides
	if (!$type_mot_vide) $types_mots_vides="2,3,4";
	//trier par mot ou derniers ajoutés
	if (!$tri) $tri=" mot"; 
	$autorites_list_empty_word=str_replace("!!checked0!!","checked",$autorites_list_empty_word);
	$autorites_list_empty_word=str_replace("!!checked2!!","",$autorites_list_empty_word);	
	$autorites_list_empty_word=str_replace("!!checked3!!","",$autorites_list_empty_word);	
	$autorites_list_empty_word=str_replace("!!checked4!!","",$autorites_list_empty_word);
	//calcul du nombre de mots
	$rqt1="select id_mot from mots,linked_mots where (linked_mots.type_lien in ($types_mots_vides))$clause and linked_mots.num_mot=mots.id_mot";
	$execute_query1=mysql_query($rqt1);
	$compt=mysql_num_rows($execute_query1);
	//recherche des mots vides calculés et saisis
	$rqt="select id_mot,mot,type_lien from mots,linked_mots where (linked_mots.type_lien in ($types_mots_vides))$clause and linked_mots.num_mot=mots.id_mot order by $tri $limit";
	$execute_query=mysql_query($rqt);
	$liste_mots="<tr>
			<th>".$msg["word_selected"]."</th>
			<th width='50%'>&nbsp;</th>
			<th>".$msg["empty_word_calculated"]."</th>
			<th>".$msg["empty_word_created"]."</th>
			<th>".$msg["no_empty_word"]."</th>
			<th>&nbsp;</th>
	   		</tr>\n";	
	if ($execute_query) {
		if ($compt) {
			$parity=1;
			//affichage
			while ($r=mysql_fetch_object($execute_query)) {
				if ($parity % 2) {
					$pair_impair = "even";
					} else {
						$pair_impair = "odd";
						}
				$parity += 1;
				$liste_mots.="<tr class='$pair_impair'><td><strong>".stripslashes($r->mot)."</strong></td>";
				$liste_mots.="<td width='50%'>&nbsp;</td><td><input type='radio' id='type_lien2_".$r->id_mot."' name='type_lien2_".$r->id_mot."' onClick='modify_type_mot_vide(".$r->id_mot.",2);'";
				if ($r->type_lien==2) $liste_mots.=" checked";
				$liste_mots.="></td><td><input type='radio' id='type_lien3_".$r->id_mot."' name='type_lien3_".$r->id_mot."' onClick='modify_type_mot_vide(".$r->id_mot.",3);'";
				if ($r->type_lien==3) $liste_mots.=" checked";
				$liste_mots.="></td><td><input type='radio' id='type_lien4_".$r->id_mot."' name='type_lien4_".$r->id_mot."' onClick='modify_type_mot_vide(".$r->id_mot.",4);'";
				if ($r->type_lien==4) $liste_mots.=" checked";
				$liste_mots.="></td><td><div class='right'><input type='button' class='bouton_small' value='".$msg["63"]."' onClick=\"response=confirm('".$msg["word_del_confirm"]."'); if (response) document.location='./autorites.php?categ=semantique&sub=empty_words&action=del&id_mot=".$r->id_mot."&type_lien=".$r->type_lien."';\"></div></td>";
				$liste_mots.="</tr>\n";
			}
			//affichage de la pagination
			$autorites_list_empty_word=str_replace("!!pagination!!","<div class='row'>".aff_pagination ($baseurl, $compt, $nb_per_page, $page)."</div>",$autorites_list_empty_word);
		} else $autorites_list_empty_word=str_replace("!!pagination!!","",$autorites_list_empty_word);
		$autorites_list_empty_word=str_replace("!!see_last_words!!","<div class='right'><a href='./autorites.php?categ=semantique&sub=empty_words&action=last_words'>".$msg["see_last_words_added"]."</a></div>",$autorites_list_empty_word);
	} 
	$autorites_list_empty_word=str_replace("!!liste_mots!!",$liste_mots,$autorites_list_empty_word);	
	print $autorites_list_empty_word;
}
?>
