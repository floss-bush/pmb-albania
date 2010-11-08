<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: func_epires.inc.php,v 1.16 2009-02-09 11:14:39 gueluneau Exp $

// DEBUT paramétrage propre à la base de données d'importation :
require_once($class_path."/serials.class.php");
require_once($class_path."/thesaurus.class.php");
require_once($class_path."/categories.class.php");
require_once($class_path."/noeuds.class.php");

function recup_noticeunimarc_suite($notice) {
	global $info_464,$info_200,$info_676;
	global $info_900,$info_901,$info_902,$info_903,$info_904,$info_910;
	global $rs,$bl,$dt;
	
	$info_464="";
	$info_900="";
	$info_901="";
	$info_902="";
	$info_903="";
	$info_904="";
	$info_910="";
	$info_200="";
	$info_676="";
	
	$record = new iso2709_record($notice, AUTO_UPDATE);
	$rs=$record->inner_guide["rs"]; 
	$bl=$record->inner_guide["bl"];
	$dt=$record->inner_guide["dt"];
	
	for ($i=0;$i<count($record->inner_directory);$i++) {
		$cle=$record->inner_directory[$i]['label'];
		switch($cle) {
			case "464":
				//C'est un périodique donc un dépouillement ou une notice objet
				$info_464=$record->get_subfield($cle,"t","v","p","d","z","e","u");
				break;
			case "200":
				$info_200=$record->get_subfield($cle,"a");
			default:
				break;
	
		} /* end of switch */
	
	} /* end of for */
	
	$info_900=$record->get_subfield("900","a");
	$info_901=$record->get_subfield("901","a");
	$info_902=$record->get_subfield("902","a");
	$info_903=$record->get_subfield("903","a");
	$info_910=$record->get_subfield("910","a");
	$info_904=$record->get_subfield_array_array("904","a");
	$info_676=$record->get_subfield("676","a");
	
} // fin recup_noticeunimarc_suite = fin récupération des variables propres à la bretagne

//trouve un champ perso et renvoi so id
function trouve_champ_perso($nom) {
	$rqt = "SELECT idchamp FROM notices_custom WHERE name='" . addslashes($nom) . "'";
	$res = mysql_query($rqt);
	
	if (mysql_num_rows($res)>0)
		return mysql_result($res,0);
	else
		return 0;
}

//trouve le thesaurus avec le code et renvoi son id
function trouve_thesaurus($code) {
	$rqt = "SELECT num_thesaurus FROM noeuds WHERE autorite='" . $code . "'";
	$res = mysql_query($rqt);
	
	if (mysql_num_rows($res)>0)
		return mysql_result($res,0);
	else
		return 0;
}

function import_new_notice_suite() {
	global $dbh ;
	global $notice_id ;
	
	global $info_464,$info_676 ;
	global $info_606_a,$info_606_x;
	global $info_900,$info_901,$info_902, $info_200,$info_903,$info_904,$info_910;
	global $rs,$bl,$dt;
	global $bulletin_ex;
	global $m_thess;
	
	//si on est en multi-thesaurus
	if (!$m_thess) {
		$rqt = "SELECT count(1) FROM thesaurus WHERE active=1";
	 	$m_thess = mysql_result(mysql_query($rqt),0,0);
	}
	
	//Cas des périodiques
	if (is_array($info_464)) {
		$requete="SELECT * FROM notices WHERE notice_id=$notice_id";
		$resultat=mysql_query($requete);
		$r=mysql_fetch_object($resultat);
		//Notice chapeau existe-t-elle ?
		$requete="SELECT notice_id FROM notices WHERE tit1='".addslashes($info_464[0]['t'])."' and niveau_hierar='1' and niveau_biblio='s'";
		$resultat=mysql_query($requete);
		if (@mysql_num_rows($resultat)) {
			//Si oui, récupération id
			$chapeau_id=mysql_result($resultat,0,0);
			
			//Mise à jour du champ commentaire de gestion si nécessaire
			if ($info_903[0]) {
				$requete="UPDATE notices SET commentaire_gestion=concat(commentaire_gestion,' ','".addslashes($info_903[0])."') WHERE notice_id=$chapeau_id";
				mysql_query($requete);
			}

			//Bulletin existe-t-il ?
			$requete="SELECT bulletin_id FROM bulletins WHERE bulletin_numero='".addslashes($info_464[0]['v'])."' AND mention_date='".addslashes($info_464[0]['d'])."' AND bulletin_notice=$chapeau_id";	
			$resultat=mysql_query($requete);
			if (@mysql_num_rows($resultat)) {
				//Si oui, récupération id bulletin
				$bulletin_id=mysql_result($resultat,0,0);	
			} else {
				//Si non, création bulletin
				$info=array();
				$bulletin=new bulletinage("",$chapeau_id);
				if ($info_464[0]['u']) 
					$info['bul_titre'] = addslashes($info_464[0]['u']);
				else $info['bul_titre'] = addslashes("Bulletin ".$info_464[0]['v']);
				
				$info['bul_no'] = addslashes($info_464[0]['v']);
				$info['bul_date'] = addslashes($info_464[0]['d']);
				if (!$info_464[0]['e']) {
					if ($info_902[0])
						$info['date_date']=$info_902[0];
				} else
					$info['date_date']=$info_464[0]['e'];
				$bulletin_id=$bulletin->update($info);
			}
		} else {
			//Si non, création notice chapeau et bulletin
			$chapeau=new serial();
			$info=array();
			$info['tit1']=addslashes($info_464[0]['t']);
			$info['niveau_biblio']='s';
			$info['niveau_hierar']='1';
			$info['typdoc']=$r->typdoc;

			$chapeau->update($info);
			$chapeau_id=$chapeau->serial_id;

			//Mise à jour du champ commentaire de gestion si nécessaire
			if ($info_903[0]) {
				$requete="UPDATE notices SET commentaire_gestion=concat(commentaire_gestion,' ','".addslashes($info_903[0])."') WHERE notice_id=$chapeau_id";
				mysql_query($requete);
			}
			
			$bulletin=new bulletinage("",$chapeau_id);
			$info=array();
			if ($info_464[0]['u'])
				$info['bul_titre']=addslashes($info_464[0]['u']);
			else $info['bul_titre']=addslashes("Bulletin ".$info_464[0]['v']);

			$info['bul_no']=addslashes($info_464[0]['v']);
			$info['bul_date']=addslashes($info_464[0]['d']);
			if (!$info_464[0]['e']) {
				if ($info_902[0])
					$info['date_date']=$info_902[0];
			} else
				$info['date_date']=$info_464[0]['e'];
			$bulletin_id=$bulletin->update($info);
		}
		
		//Passage de la notice en article
		$requete="UPDATE notices SET niveau_biblio='a', niveau_hierar='2', npages='".addslashes($info_464[0]['p'])."' WHERE notice_id=$notice_id";
		mysql_query($requete);
		$requete="INSERT INTO analysis (analysis_bulletin,analysis_notice) VALUES($bulletin_id,$notice_id)";
		mysql_query($requete);
		$bulletin_ex=$bulletin_id;
	} else 
		$bulletin_ex=0;
		

	//Traitement du thésaurus
	if ($m_thess>1) {
		//on est en multi-thesaurus
		for ($i=0; $i<count($info_606_a); $i++) {
			for ($j=0; $j<count($info_606_a[$i]); $j++) {
				$descripteur_tete=$info_606_a[$i][$j];
				$descripteur_fils=$info_606_x[$i][$j];
				
				//Recherche du thésaurus
				$thes_id=trouve_thesaurus($descripteur_tete);
				//Recherche du terme fils
				if ($thes_id>0) {
					$categ_id_fils=categories::searchLibelle(addslashes(trim($descripteur_fils)),$thes_id,"fr_FR");
					if (!$categ_id_fils) {
						//Création
						$new_thes=$thes_id==1?4:$thes_id;	//Choix du thesaurus Candidats descripteurs si descripteur inexistant
						$categ_id_fils=categories::searchLibelle(addslashes(trim($descripteur_fils)),$new_thes,"fr_FR");
						if (!$categ_id_fils) {
							$noeud=new noeuds();
							$noeud->num_thesaurus=($new_thes);
							$thesau=new thesaurus($new_thes);
							$noeud->num_parent=$thesau->num_noeud_racine;
							$noeud->save();
							$categ_id_fils=$noeud->id_noeud;
							//Création du libellé
							$categ=new categories($noeud->id_noeud,'fr_FR');
							$categ->libelle_categorie=$descripteur_fils;
							$categ->index_categorie=" ".strip_empty_words($descripteur_fils)." ";
							$categ->save();
						}
					}
					$requete="INSERT INTO notices_categories (notcateg_notice, num_noeud, ordre_categorie) VALUES ($notice_id,$categ_id_fils, ".($i+1).")";
					mysql_query($requete);
				}
			}
		} //for($i
	} else {
		//Traitement du thésaurus unique
		for ($i=0; $i<count($info_606_a); $i++) {
			for ($j=0; $j<count($info_606_a[$i]); $j++) {
				$descripteur_tete=$info_606_a[$i][$j];
				$descripteur_fils=$info_606_x[$i][$j];
			
				//Recherche du terme de tête
				//$requete="SELECT num_noeud FROM categories WHERE libelle_categorie='".addslashes($descripteur_tete)."' AND langue='fr_FR'";
				$requete="SELECT id_noeud FROM noeuds WHERE autorite='".addslashes($descripteur_tete)."'";
				$resultat=mysql_query($requete);
				if (@mysql_num_rows($resultat)) {
					//la tête existe !
					$categ_id_tete=mysql_result($resultat,0,0);
				} else {
					//Création de la tête
					//Nouveau Noeud !
					$th=new thesaurus(1);
					$noeud=new noeuds();
					$noeud->num_thesaurus=$th->id_thesaurus;
					$noeud->num_parent=$th->num_noeud_racine;
					$noeud->autorite=$descripteur_tete;
					$noeud->save();
					$categ_id_tete=$noeud->id_noeud;
					//Création du libellé
					$categ=new categories($noeud->id_noeud,'fr_FR');
					$categ->libelle_categorie=$descripteur_tete;
					$categ->index_categorie=" ".strip_empty_words($descripteur_tete)." ";
					$categ->save();
				}
				//Recherche du terme fils
				$categ_id_fils=categories::searchLibelle(addslashes($descripteur_fils),1,"fr_FR");
				if (!$categ_id_fils) {
					//Création
					$noeud=new noeuds();
					$noeud->num_thesaurus=1;
					$noeud->num_parent=$categ_id_tete;
					$noeud->save();
					$categ_id_fils=$noeud->id_noeud;
					//Création du libellé
					$categ=new categories($noeud->id_noeud,'fr_FR');
					$categ->libelle_categorie=$descripteur_fils;
					$categ->index_categorie=" ".strip_empty_words($descripteur_fils)." ";
					$categ->save();
				}
				$requete="INSERT INTO notices_categories (notcateg_notice, num_noeud, ordre_categorie) VALUES ($notice_id, $categ_id_fils, ".($i+1).")";
				mysql_query($requete);
			}
		}
	}
	
	//Indexation décimale
	if ($info_676[0]) {
		$requete="select indexint_id from indexint where indexint_name='".addslashes($info_676[0])."'";
		$resultat=mysql_query($requete);
		if (mysql_num_rows($resultat)) {
			$indexint=mysql_result($resultat,0,0);
		} else {
			$requete="insert into indexint (indexint_name) values('".addslashes($info_676[0])."')";
			mysql_query($requete);
			$indexint=mysql_insert_id();
		}
		$requete="update notices set indexint=".$indexint." where notice_id=".$notice_id;
		mysql_query($requete);
	}	
	
	//Organisme
	if ($info_900[0]) {
		$no_champ = trouve_champ_perso("op");
		if ($no_champ>0) {
			$requete="SELECT max(notices_custom_list_value*1) FROM notices_custom_lists WHERE notices_custom_champ=".$no_champ;
			$resultat=mysql_query($requete);
			$max=@mysql_result($resultat,0,0);
			$n=$max+1;
			$requete="SELECT notices_custom_list_value FROM notices_custom_lists WHERE notices_custom_list_lib='".addslashes($info_900[0])."' AND notices_custom_champ=".$no_champ;
			$resultat=mysql_query($requete);
			if (mysql_num_rows($resultat)) {
				$value=mysql_result($resultat,0,0);
			} else {
				$requete="INSERT INTO notices_custom_lists (notices_custom_champ,notices_custom_list_value,notices_custom_list_lib) VALUES($no_champ,$n,'".addslashes($info_900[0])."')";
				mysql_query($requete);
				$value=$n;
				$n++;
			}
			$requete="INSERT INTO notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_integer) VALUES($no_champ,$notice_id,$value)";
			mysql_query($requete);
		}
	}
	
	//Genre
	if ($info_901[0]) {
		$no_champ = trouve_champ_perso("gen");
		if ($no_champ>0) {
			$requete="SELECT max(notices_custom_list_value*1) FROM notices_custom_lists WHERE notices_custom_champ=".$no_champ;
			$resultat=mysql_query($requete);
			$max=@mysql_result($resultat,0,0);
			$n=$max+1;
			$requete="SELECT notices_custom_list_value FROM notices_custom_lists WHERE notices_custom_list_lib='".addslashes($info_901[0])."' AND notices_custom_champ=".$no_champ;
			$resultat=mysql_query($requete);
			if (mysql_num_rows($resultat)) {
				$value=mysql_result($resultat,0,0);
			} else {
				$requete="INSERT INTO notices_custom_lists (notices_custom_champ,notices_custom_list_value,notices_custom_list_lib) VALUES($no_champ,$n,'".addslashes($info_901[0])."')";
				mysql_query($requete);
				$value=$n;
				$n++;
			}
			$requete="INSERT INTO notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_integer) VALUES($no_champ,$notice_id,$value)";
			mysql_query($requete);
		}
	}

	//Type de texte
	if (count($info_904)) {
		$no_champ = trouve_champ_perso("typtext");
		if ($no_champ>0) {
			for ($i=0; $i<count($info_904); $i++) {
				for ($j=0; $j<count($info_904[$i]); $j++) {
					$requete="SELECT max(notices_custom_list_value*1) FROM notices_custom_lists WHERE notices_custom_champ=".$no_champ;
					$resultat=mysql_query($requete);
					$max=@mysql_result($resultat,0,0);
					$n=$max+1;
					$requete="SELECT notices_custom_list_value FROM notices_custom_lists WHERE notices_custom_list_lib='".addslashes($info_904[$i][$j])."' AND notices_custom_champ=".$no_champ;
					$resultat=mysql_query($requete);
					if (mysql_num_rows($resultat)) {
						$value=mysql_result($resultat,0,0);
					} else {
						$requete="INSERT INTO notices_custom_lists (notices_custom_champ,notices_custom_list_value,notices_custom_list_lib) VALUES($no_champ,$n,'".addslashes($info_904[$i][$j])."')";
						mysql_query($requete);
						$value=$n;
						$n++;
					}
					$requete="INSERT INTO notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_integer) VALUES($no_champ,$notice_id,$value)";
					mysql_query($requete);
				}
			}
		}
	}
	
	//Date de saisie
	if ($info_902[0]) {
		$no_champ = trouve_champ_perso("ds");
		if ($no_champ>0) {
			$requete="INSERT INTO notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_date) VALUES($no_champ,$notice_id,'".str_replace(".","-",$info_902[0])."')";
			mysql_query($requete);
		}
	}
	
	//N° de lot
	if ($info_903[0]) {
		$requete="UPDATE notices SET commentaire_gestion='".addslashes($info_903[0])."' WHERE notice_id=$notice_id";
		mysql_query($requete);
	}
	
	//Cas de la mise à jour des périodiques ou du champ bord (notices chapeau)
	if ($bl=="s") {
		//Si c'est un périodique
		if ($dt=="a") {
			//Passage de la notice en notice chapeau
			$requete="UPDATE notices SET niveau_biblio='s', niveau_hierar='1' WHERE notice_id=$notice_id";
			mysql_query($requete);
			//Recherche si la notice existe déjà par rapport au titre
			$requete="select notice_id FROM notices WHERE ucase(tit1)='".addslashes(strtoupper($info_200[0]))."' AND niveau_biblio='s' AND niveau_hierar='1' AND notice_id!=$notice_id";
			$resultat=mysql_query($requete);
			$update=false;
			if (mysql_num_rows($resultat)) {
				$update=true;
				$n_update=mysql_result($resultat,0,0);
				//Mise à jour de tous les bulletins
				$requete="UPDATE bulletins SET bulletin_notice=".$notice_id." WHERE bulletin_notice=".$n_update;
				mysql_query($requete);
				//Suppression de l'ancienne notice
				$requete="DELETE FROM notices WHERE notice_id=$n_update";
				mysql_query($requete);
				$requete="DELETE FROM notices_categories WHERE notcateg_notice=".$n_update;
				mysql_query($requete);
				$requete="DELETE FROM notices_custom_values WHERE notices_custom_origine=".$n_update;
				mysql_query($requete);
				$requete="DELETE FROM responsability WHERE responsability_author=".$n_update;
				mysql_query($requete);
			}
			
			if ((!$update)&&($rs!="n")) {
				//Si il n'y a pas de création, on supprime la notice
				$requete="DELETE FROM notices WHERE notice_id=$notice_id";
				mysql_query($requete);
				$requete="DELETE FROM notices_categories WHERE notcateg_notice=".$notice_id;
				mysql_query($requete);
				$requete="DELETE FROM notices_custom_values WHERE notices_custom_origine=".$notice_id;
				mysql_query($requete);
				$requete="DELETE FROM responsability WHERE responsability_author=".$notice_id;
				mysql_query($requete);
			}
		} else if ($dt=="l") {
			//Recherche si la notice existe déjà par rapport au titre
			$requete="select notice_id FROM notices WHERE ucase(tit1)='".addslashes(strtoupper($info_200[0]))."' AND typdoc='l' AND notice_id!=$notice_id";
			$resultat=mysql_query($requete);
			$update=false;
			if (mysql_num_rows($resultat)) {
				$update=true;
				$n_update=mysql_result($resultat,0,0);
				//Suppression de l'ancienne notice
				$requete="DELETE FROM notices WHERE notice_id=$n_update";
				mysql_query($requete);
				$requete="DELETE FROM notices_categories WHERE notcateg_notice=".$n_update;
				mysql_query($requete);
				$requete="DELETE FROM notices_custom_values WHERE notices_custom_origine=".$n_update;
				mysql_query($requete);
				$requete="DELETE FROM responsability WHERE responsability_author=".$n_update;
				mysql_query($requete);
			}
		} else if ($dt=="r") {
			//Mise à jour du champ bord
			if ($info_910[0]) {
				$no_champ = trouve_champ_perso("bord");
				if ($no_champ>0) {
					//Recherche si la notice existe déjà par rapport au titre
					$requete="SELECT notice_id FROM notices WHERE ucase(tit1)='".addslashes(strtoupper($info_200[0]))."' AND niveau_biblio='s' AND niveau_hierar='1' AND notice_id!=$notice_id";
					$resultat=mysql_query($requete);
					if (mysql_num_rows($resultat)) {
						$notice_update=mysql_result($resultat,0,0);
						$requete="UPDATE notices_custom_values SET notices_custom_text='".addslashes(str_replace("##","\n",$info_910[0]))."' WHERE notices_custom_champ=$no_champ AND notices_custom_origine=".$notice_update;
						mysql_query($requete);
						if (!mysql_affected_rows()) {
							$requete="INSERT INTO notices_custom_values (notices_custom_champ,notices_custom_origine,notices_custom_text) VALUES($no_champ,$notice_update,'".addslashes(str_replace("##","\n",$info_910[0]))."')";
							mysql_query($requete);
						}
					}
				}
			}
			//Suppression de la nouvelle notice
			$requete="DELETE FROM notices WHERE notice_id=".$notice_id;
			mysql_query($requete);
		}
	}
	
} // fin import_new_notice_suite
			
// TRAITEMENT DES EXEMPLAIRES ICI
function traite_exemplaires () {
	global $msg, $dbh ;
	
	global $prix, $notice_id, $info_995, $typdoc_995, $tdoc_codage, $book_lender_id, 
		$section_995, $sdoc_codage, $book_statut_id, $locdoc_codage, $codstatdoc_995, $statisdoc_codage,
		$cote_mandatory ;
	
	global $bulletin_ex;
		
	// lu en 010$d de la notice
	$price = $prix[0];
	
	// la zone 995 est répétable
	for ($nb_expl = 0; $nb_expl < sizeof ($info_995); $nb_expl++) {
		/* RAZ expl */
		$expl = array();
		
		/* préparation du tableau à passer à la méthode */
		$expl['cb'] 	    = $info_995[$nb_expl]['f'];
		$unique=false;
		$cb=$expl['cb'];
		$cb1=$cb;
		$n_cb=2;
		while (!$unique) {
			$requete="select 1 from exemplaires where expl_cb='".addslashes($cb1)."'";
			$resultat=mysql_query($requete);
			if (mysql_num_rows($resultat)) {
				$cb1=$cb." ".$n_cb;
				$n_cb++;
			} else $unique=true;
		}
		$expl['cb']=$cb1;
		if ($bulletin_ex) {
			$expl['bulletin']=$bulletin_ex;
			$expl['notice']=0;
		} else {
			$expl['notice']     = $notice_id ;
			$expl['bulletin']=0;
		}
		// $expl['typdoc']     = $info_995[$nb_expl]['r']; à chercher dans docs_typdoc
		$data_doc=array();
		//$data_doc['tdoc_libelle'] = $info_995[$nb_expl]['r']." -Type doc importé (".$book_lender_id.")";
		//$data_doc['tdoc_libelle'] = $typdoc_995[$info_995[$nb_expl]['r']];
		//if (!$data_doc['tdoc_libelle']) $data_doc['tdoc_libelle'] = "\$r non conforme -".$info_995[$nb_expl]['r']."-" ;
		$data_doc['duree_pret'] = 0 ; /* valeur par défaut */
		$data_doc['tdoc_codage_import'] = $info_995[$nb_expl]['r'] ;
		$data_doc['tdoc_libelle']=$info_995[$nb_expl]['r'] ;
		if ($tdoc_codage) $data_doc['tdoc_owner'] = $book_lender_id ;
			else $data_doc['tdoc_owner'] = 0 ;
		$expl['typdoc'] = docs_type::import($data_doc);
		
		$expl['cote'] = $info_995[$nb_expl]['k'];
         
        if (!trim($expl['cote'])) $expl['cote']="SC";
                      	
		// $expl['section']    = $info_995[$nb_expl]['q']; à chercher dans docs_section
		$data_doc=array();
		if (!$info_995[$nb_expl]['t']) 
			$info_995[$nb_expl]['t'] = "inconnu";
		$data_doc['section_libelle'] = $info_995[$nb_expl]['t'] ;
		$data_doc['sdoc_codage_import'] = $info_995[$nb_expl]['t'] ;
		if ($sdoc_codage) $data_doc['sdoc_owner'] = $book_lender_id ;
			else $data_doc['sdoc_owner'] = 0 ;
		$expl['section'] = docs_section::import($data_doc);
		
		$expl['statut'] = $book_statut_id;
		
		// $expl['location']   = $info_995[$nb_expl]['']; à fixer par combo_box
		// figé dans le code ici pour l'instant :
		//$info_995[$nb_expl]['localisation']="Bib princip"; /* biblio principale */
		$data_doc=array();
		$data_doc['location_libelle'] = "inconnu";
		if ($info_995[$nb_expl]['a']) {
			$data_doc['location_libelle'] = $info_995[$nb_expl]['a'];
			$data_doc['locdoc_codage_import'] = $info_995[$nb_expl]['a'];
		} else
			$data_doc['locdoc_codage_import']="Centre de documentation";

		if ($locdoc_codage) 
			$data_doc['locdoc_owner'] = $book_lender_id ;
		else 
			$data_doc['locdoc_owner'] = 0 ;
		$expl['location'] = docs_location::import($data_doc);
		
		// $expl['codestat']   = $info_995[$nb_expl]['q']; 'q' utilisé, éventuellement à fixer par combo_box
		$data_doc=array();
		//$data_doc['codestat_libelle'] = $info_995[$nb_expl]['q']." -Pub visé importé (".$book_lender_id.")";
		if (!$info_995[$nb_expl]['q']) 
			$info_995[$nb_expl]['q'] = "inconnu";
		$data_doc['codestat_libelle'] = $info_995[$nb_expl]['q'] ;
		$data_doc['statisdoc_codage_import'] = $info_995[$nb_expl]['q'] ;
		if ($statisdoc_codage) $data_doc['statisdoc_owner'] = $book_lender_id ;
			else $data_doc['statisdoc_owner'] = 0 ;
		$expl['codestat'] = docs_codestat::import($data_doc);
		
		
		// $expl['creation']   = $info_995[$nb_expl]['']; à préciser
		// $expl['modif']      = $info_995[$nb_expl]['']; à préciser
                      	
		$expl['note']       = $info_995[$nb_expl]['u'];
		$expl['prix']       = $price;
		$expl['expl_owner'] = $book_lender_id ;
		$expl['cote_mandatory'] = $cote_mandatory ;
		
		$expl['date_depot'] = substr($info_995[$nb_expl]['m'],0,4)."-".substr($info_995[$nb_expl]['m'],4,2)."-".substr($info_995[$nb_expl]['m'],6,2) ;      
		$expl['date_retour'] = substr($info_995[$nb_expl]['n'],0,4)."-".substr($info_995[$nb_expl]['n'],4,2)."-".substr($info_995[$nb_expl]['n'],6,2) ;
		
		$expl_id = exemplaire::import($expl);
		
		if ($expl_id == 0)
			$nb_expl_ignores++;
                      	
	} // fin for
} // fin traite_exemplaires	TRAITEMENT DES EXEMPLAIRES JUSQU'ICI

// fonction spécifique d'export de la zone 995
function export_traite_exemplaires ($ex=array()) {
	global $msg, $dbh ;
	
	$subfields["a"] = $ex -> lender_libelle;
	$subfields["c"] = $ex -> lender_libelle;
	$subfields["f"] = $ex -> expl_cb;
	$subfields["k"] = $ex -> expl_cote;
	$subfields["u"] = $ex -> expl_note;

	if ($ex->statusdoc_codage_import) $subfields["o"] = $ex -> statusdoc_codage_import;
	if ($ex -> tdoc_codage_import) $subfields["r"] = $ex -> tdoc_codage_import;
		else $subfields["r"] = "uu";
	if ($ex -> sdoc_codage_import) $subfields["q"] = $ex -> sdoc_codage_import;
		else $subfields["q"] = "u";
	
	global $export996 ;
	$export996['f'] = $ex -> expl_cb ;
	$export996['k'] = $ex -> expl_cote ;
	$export996['u'] = $ex -> expl_note ;

	$export996['m'] = substr($ex -> expl_date_depot, 0, 4).substr($ex -> expl_date_depot, 5, 2).substr($ex -> expl_date_depot, 8, 2) ;
	$export996['n'] = substr($ex -> expl_date_retour, 0, 4).substr($ex -> expl_date_retour, 5, 2).substr($ex -> expl_date_retour, 8, 2) ;

	$export996['a'] = $ex -> lender_libelle;
	$export996['b'] = $ex -> expl_owner;

	$export996['v'] = $ex -> location_libelle;
	$export996['w'] = $ex -> ldoc_codage_import;

	$export996['x'] = $ex -> section_libelle;
	$export996['y'] = $ex -> sdoc_codage_import;

	$export996['e'] = $ex -> tdoc_libelle;
	$export996['r'] = $ex -> tdoc_codage_import;

	$export996['1'] = $ex -> statut_libelle;
	$export996['2'] = $ex -> statusdoc_codage_import;
	$export996['3'] = $ex -> pret_flag;
	
	global $export_traitement_exemplaires ;
	$export996['0'] = $export_traitement_exemplaires ;
	
	return 	$subfields ;

}