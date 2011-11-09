<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// | creator : Emmanuel PACAUD < emmanuel.pacaud@univ-poitiers.fr>            |
// +-------------------------------------------------+
// $Id: z3950_notice.class.php,v 1.117.2.1 2011-07-08 09:59:15 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($class_path."/parametres_perso.class.php");
require_once($class_path."/notice.class.php");
require_once($base_path."/catalog/z3950/manual_categorisation.inc.php");
require_once("$include_path/marc_tables/$lang/empty_words");
require_once("$class_path/iso2709.class.php");
require_once("$class_path/author.class.php");
require_once("$class_path/serie.class.php");
require_once("$class_path/editor.class.php");
require_once("$class_path/collection.class.php");
require_once("$class_path/subcollection.class.php");
require_once("$class_path/origine_notice.class.php");
require_once("$class_path/audit.class.php");
require_once("$class_path/expl.class.php");
require_once("$include_path/templates/expl.tpl.php");
require_once("$class_path/acces.class.php");
require_once("$class_path/upload_folder.class.php");

class z3950_notice {
	var $bibliographic_level;
	var $hierarchic_level;

	var $titles;
	var $serie;
	var $nbr_in_serie;
	
	var $authors;
	var $author_functions;
	
	var $editors;
	var $collection;
	var $subcollection;
	var $nbr_in_collection;
	var $mention_edition;	// mention d'édition (1ère, deuxième...)
	var $isbn;

	var $page_nbr;
	var $illustration;
	var $size;
	var $prix;
	var $accompagnement;

	var $general_note;
	var $content_note;
	var $abstract_note;

	var $internal_index;
	
	var $dewey ;
	var $free_index;
	var $matieres_index;

	var $link_url;
	var $link_format;

	var $language_code;
	var $original_language_code;

	var $action;
	
	var $origine_notice = array() ;
	var $orinot_id = 0 ;
	
	var $aut_array = array(); // tableau des auteurs
	var $categories =	array();// les categories
	var $categorisation_type = "categorisation_auto";

	var $info_600_3 = array() ;
	var $info_600_a = array() ;
	var $info_600_b = array() ;
	var $info_600_c = array() ;
	var $info_600_d = array() ;	
	var $info_600_f = array() ;
	var $info_600_g = array() ;
	var $info_600_j = array() ;
	var $info_600_p = array() ;
	var $info_600_t = array() ;
	var $info_600_x = array() ;
	var $info_600_y = array() ;
	var $info_600_z = array() ;
	
	var $info_601_3 = array() ;
	var $info_601_a = array() ;
	var $info_601_b = array() ;
	var $info_601_c = array() ;
	var $info_601_d = array() ;
	var $info_601_e = array() ;
	var $info_601_f = array() ;
	var $info_601_g = array() ;
	var $info_601_h = array() ;
	var $info_601_j = array() ;
	var $info_601_t = array() ;
	var $info_601_x = array() ;
	var $info_601_y = array() ;
	var $info_601_z = array() ;
	
	var $info_602_3 = array() ;
	var $info_602_a = array() ;
	var $info_602_f = array() ;
	var $info_602_j = array() ;
	var $info_602_t = array() ;
	var $info_602_x = array() ;
	var $info_602_y = array() ;
	var $info_602_z = array() ;
	
	var $info_604_3 = array() ;
	var $info_604_a = array() ;
	var $info_604_h = array() ;
	var $info_604_i = array() ;
	var $info_604_j = array() ;
	var $info_604_k = array() ;
	var $info_604_l = array() ;
	var $info_604_x = array() ;
	var $info_604_y = array() ;
	var $info_604_z = array() ;
	
	var $info_605_3 = array() ;
	var $info_605_a = array() ;
	var $info_605_h = array() ;
	var $info_605_i = array() ;
	var $info_605_k = array() ;
	var $info_605_l = array() ;
	var $info_605_m = array() ;
	var $info_605_n = array() ;
	var $info_605_q = array() ;
	var $info_605_r = array() ;
	var $info_605_s = array() ;
	var $info_605_u = array() ;
	var $info_605_w = array() ;
	var $info_605_j = array() ;
	var $info_605_x = array() ;
	var $info_605_y = array() ;
	var $info_605_z = array() ;
	
	var $info_606_3 = array() ;
	var $info_606_a = array() ;
	var $info_606_j = array() ;
	var $info_606_x = array() ;
	var $info_606_y = array() ;
	var $info_606_z = array() ;	
	
	var $info_607_3 = array() ;
	var $info_607_a = array() ;
	var $info_607_j = array() ;
	var $info_607_x = array() ;
	var $info_607_y = array() ;
	var $info_607_z = array() ;
	
	var $info_608_3 = array() ;
	var $info_608_a = array() ;
	var $info_608_j = array() ;
	var $info_608_x = array() ;
	var $info_608_y = array() ;
	var $info_608_z = array() ;
	
	var $tu_500 = array();
	var $tu_500_i = array();
	var $tu_500_j = array();
	var $tu_500_l = array();
	var $tu_500_n = array();
	var $tu_500_r = array();
	var $tu_500_s = array();

	var $info_503 = array();
	var $info_503_d = array();
	var $info_503_j = array();
	
	//bulletin
	var $bull_id;
	var $bull_num;
	var $bull_date;
	var $bull_mention;
	var $bull_titre;
	//perio
	var $perio_titre;
	var $perio_issn;
	var $perio_id;
	
	var $thumbnail_url = "";
	var $doc_nums = array();
	
	var $message_retour="";
	var $notice="";
	
	function z3950_notice ($type, $marc = NULL, $source_id=0) {
		$type = strtolower ($type);
		if ($type == 'form') {
			$this->from_form ();
		} else {
		 	if ($marc != NULL) {
				if ($type == 'sutrs') $type= 'unimarc';
				$this->notice_type=$type;
				$this->notice=$marc;
				$this->source_id = $source_id;
				$record = new iso2709_record ($marc, AUTO_UPDATE);
				if ($type == 'unimarc') $this->from_unimarc ($record);
					else $this->from_usmarc ($record);
				if(function_exists("traite_info_subst")){
					traite_info_subst($this);
				}
			}
		}
	}
				
	function substitute ($tag, $value, &$string) {
		global $charset;
		$string = str_replace ("!!$tag!!", htmlentities($value,ENT_QUOTES, $charset), $string);
	}

	function insert_in_database () {
		global $dbh ;
		global $class_path;
		global $gestion_acces_active, $gestion_acces_user_notice, $gestion_acces_empr_notice;
		global $res_prf, $chk_rights;
		
		$new_notice = 0;
		$notice_retour = 0;
		$long_maxi = mysql_field_len(mysql_query ("SELECT code FROM notices limit 1") ,0);
		$isbn = rtrim (substr ($this->isbn, 0, $long_maxi));
		if ($isbn != "") {
			if (isISBN($isbn)) {
				if (strlen($isbn)==13) {
					$isbn1=formatISBN($isbn,13);
				} else {
					$isbn1=formatISBN($isbn,10);
				}
			}
			$sql_rech="select notice_id from notices where code = '".$isbn."' ";
			if ($isbn1) $sql_rech.=" or code='".$isbn1."' ";
			$sql_result_rech = mysql_query ($sql_rech) or die ("Couldn't select notice ! = ".$sql_rech);
			if (mysql_num_rows($sql_result_rech) == 0) {
				$new_notice=1; 
			} else {
				$new_notice=0;
				$lu = mysql_fetch_array($sql_result_rech);
				$notice_retour = $lu['notice_id'];
			}
		} else {
			$new_notice = 1;
		}

		if ($new_notice == 0) {
			$retour = array ($new_notice, $notice_retour);
			return $retour;
		}

		for ($i = 0; $i < 2; $i++) {
			if ($this->editors[$i]['id']) {
				$editor_ids[$i] = $this->editors[$i]['id'];
			} else {
				$editor_ids[$i] = editeur::import ($this->editors[$i]);
			}
		}
		
		$this->collection['parent'] = $editor_ids[0];
		if ($this->collection["id"]) {
			$collection_id = $this->collection["id"];
		} else { 
			$collection_id = collection::import ($this->collection);
		}
		$this->subcollection['coll_parent'] = $collection_id;
		if ($this->subcollection["id"]) {
			$subcollection_id = $this->subcollection["id"];
		} else { 
			$subcollection_id = subcollection::import ($this->subcollection);
		}
		$serie_id = serie::import(stripslashes($this->serie));

		/* traitement de Dewey */
		if (!$this->internal_index) {
			if (!$this->dewey["new_comment"])
				$this->dewey["new_comment"] = "";
			if (!$this->dewey["new_pclass"])
				$this->dewey["new_pclass"] = "";			
			$this->internal_index = indexint::import(clean_string($this->dewey[0]), clean_string($this->dewey["new_comment"]), clean_string($this->dewey["new_pclass"]));
		} 
				
		$title_indexes_wew = $this->serie." ".$this->nbr_in_serie ;
		for ($i = 0; $i < 4; $i++) {
			$title_indexes_wew .= " ".$this->titles[$i] ;
		}
		$title_indexes_sew = strip_empty_words ($title_indexes_wew);
		$this->serie ? $serie_index = ' '.strip_empty_words ($this->serie).' ' : $serie_index='' ;
		
		$this->free_index ? $this->matieres_index = ' '.strip_empty_words($this->free_index).' ' : $this->matieres_index = '';
		
		$this->general_note	? $index_n_gen = ' '.strip_empty_words($this->general_note).' ' : $index_n_gen = '';
		$this->content_note	? $index_n_contenu = ' '.strip_empty_words($this->content_note).' ' : $index_n_contenu = '';
		$this->abstract_note ? $index_n_resume = ' '.strip_empty_words($this->abstract_note).' ' : $index_n_resume = '';
		
		$date_parution_z3950 = notice::get_date_parution($this->year);
	
		/* Origine de la notice */
		$this->orinot_id = origine_notice::import($this->origine_notice);
		if ($this->orinot_id==0) $orinot_id=1 ;
				
		$sql_ins = "insert into notices (
			typdoc          ,
			code        	,
			tit1            ,
			tit2            ,
			tit3            ,
			tit4            ,
			tparent_id      ,
			tnvol           ,
			ed1_id          ,
			ed2_id          ,
			year            ,
			npages          ,
			ill             ,
			size            ,
			accomp          ,
			coll_id         ,
			subcoll_id      ,
			nocoll          ,
			mention_edition ,
			n_gen           ,
			n_contenu       ,
			n_resume        ,
			indexint,
			index_serie,
			index_sew,
			index_wew,
			statut,
			commentaire_gestion,
			signature,
			thumbnail_url,
			index_l,
			index_matieres,
			niveau_biblio,
			niveau_hierar,
			lien,
			eformat,
			origine_catalogage,
			prix,
	        index_n_gen,
			index_n_contenu,
			index_n_resume,
			create_date,
			date_parution
			) values (
			'".$this->document_type."',	
			'".$this->isbn."',	
			'".$this->titles[0]."',
			'".$this->titles[1]."',
			'".$this->titles[2]."',
			'".$this->titles[3]."',
			'".$serie_id."',
			'".$this->nbr_in_serie."',
			".$editor_ids[0]." ,
			".$editor_ids[1]." ,
			'".$this->year."',
			'".$this->page_nbr."',
			'".$this->illustration."',
			'".$this->size."',
			'".$this->accompagnement."',
			".$collection_id." ,
			".$subcollection_id." ,
			'".$this->nbr_in_collection."',
			'".$this->mention_edition."',
			'".$this->general_note."',
			'".$this->content_note."',
			'".$this->abstract_note."',
			'".$this->internal_index."',
			'".$serie_index."',
			' ".$title_indexes_sew." ',
			'".$title_indexes_wew."',
			'".$this->statut."',
			'".$this->commentaire_gestion."',
			'".$this->signature."',
			'".$this->thumbnail_url."',
			'".clean_tags($this->free_index)."',
			'".$this->matieres_index."',
			'".$this->bibliographic_level."',
			'".$this->hierarchic_level."',
			'".$this->link_url."',
			'".$this->link_format."',
			'".$this->orinot_id."',
			'".$this->prix."',
			'".$index_n_gen."',
			'".$index_n_contenu."',
			'".$index_n_resume."',
			sysdate(),
			'".$date_parution_z3950."'
		 )";
		
	
		$sql_result_ins = mysql_query($sql_ins) or die ("Couldn't insert into table notices : ".$sql_ins);
		$notice_retour = mysql_insert_id();
		audit::insert_creation (AUDIT_NOTICE, $notice_retour) ;
		
				
		if ($gestion_acces_active==1) {
			$ac= new acces();
			//traitement des droits acces user_notice
			if ($gestion_acces_user_notice==1) {			
				$dom_1= $ac->setDomain(1);
				$dom_1->storeUserRights(0, $notice_retour);
			}
			//traitement des droits acces empr_notice
			if ($gestion_acces_empr_notice==1) {			
				$dom_2= $ac->setDomain(2);
				$dom_2->storeUserRights(0, $notice_retour);
			}
		}
		
		// purge de la base des responsabilités de la notice intégrée...
		if ($notice_retour) {
			$rqt_del = "delete from responsability where responsability_notice='$notice_retour'" ;
			$sql_result_del = mysql_query($rqt_del) or die ("Couldn't purge table responsability : ".$rqt_del);
		}
		$rqt_ins = "insert into responsability (responsability_author, responsability_notice, responsability_fonction, responsability_type, responsability_ordre ) VALUES ";
		for ($i=0 ; $i<sizeof($this->aut_array) ; $i++ ){
			$aut['name']=clean_string($this->aut_array[$i]['entree']);
			$aut['rejete']=clean_string($this->aut_array[$i]['rejete']);
			$aut['date']=clean_string($this->aut_array[$i]['date']);
			$aut['type']=$this->aut_array[$i]['type_auteur'];
			$aut['subdivision']=clean_string($this->aut_array[$i]['subdivision']);
			$aut['numero']=clean_string($this->aut_array[$i]['numero']);
			$aut['lieu']=clean_string($this->aut_array[$i]['lieu']);
			$aut['ville']=clean_string($this->aut_array[$i]['ville']);
			$aut['pays']=clean_string($this->aut_array[$i]['pays']);
			$aut['web']=clean_string($this->aut_array[$i]['web']);
			$aut['author_comment']=clean_string($this->aut_array[$i]['author_comment']);
			if (!$this->aut_array[$i]["id"])
				$this->aut_array[$i]["id"] = auteur::import($aut);
			if ($this->aut_array[$i]["id"]) {
				$rqt = $rqt_ins . " (".$this->aut_array[$i]["id"].",".$notice_retour.",'".$this->aut_array[$i]['fonction']."',".$this->aut_array[$i]['responsabilite']."," . $i . ") " ; 
				$res_ins = mysql_query($rqt, $dbh);
			}
		}
		// traitement des titres uniformes	
		global $pmb_use_uniform_title;
		if ($pmb_use_uniform_title) {
			if(count($this->titres_uniformes)) {
				$ntu=new tu_notice($notice_retour);
				$ntu->update($this->titres_uniformes);
			}
		}
		// traitement des langues
		// langues
		global $max_lang, $max_langorg;
		$f_lang_form = array();
		$f_langorg_form = array() ;
		for ($i=0; $i< $max_lang ; $i++) {
			$var_langcode = "f_lang_code$i" ;
			global $$var_langcode ;
			if ($$var_langcode) $f_lang_form[] =  array ('code' => $$var_langcode);
		}
		// langues originales
		for ($i=0; $i< $max_langorg ; $i++) {
			$var_langorgcode = "f_langorg_code$i" ;
			global $$var_langorgcode;
			if ($$var_langorgcode) $f_langorg_form[] =  array ('code' => $$var_langorgcode);
		}

		$rqt_del = "delete from notices_langues where num_notice='$notice_retour' ";
		$res_del = mysql_query($rqt_del, $dbh);
		$rqt_ins = "insert into notices_langues (num_notice, type_langue, code_langue) VALUES ";
		while (list ($key, $val) = each ($f_lang_form)) {
			$tmpcode_langue=$val['code'];
			if ($tmpcode_langue) {
				$rqt = $rqt_ins . " ('$notice_retour',0, '$tmpcode_langue') " ; 
				$res_ins = mysql_query($rqt, $dbh);
			}
		}
	
		// traitement des langues originales
		$rqt_ins = "insert into notices_langues (num_notice, type_langue, code_langue) VALUES ";
		while (list ($key, $val) = each ($f_langorg_form)) {
			$tmpcode_langue=$val['code'];
			if ($tmpcode_langue) {
				$rqt = $rqt_ins . " ('$notice_retour',1, '$tmpcode_langue') " ; 
				$res_ins = @mysql_query($rqt, $dbh);
			}
		}
	
		
		// traitement des categories
		if ($this->categorisation_type == "categorisation_auto") {
			traite_categories_enreg($notice_retour,$this->categories);			
		} else {
			$rqt_del = "delete from notices_categories where notcateg_notice='$notice_retour' ";
			$res_del = @mysql_query($rqt_del, $dbh);
			
			$rqt_ins = "insert into notices_categories (notcateg_notice, num_noeud, ordre_categorie) VALUES ";
			
			$rqt_ins_values = array();
			foreach ($this->categories as $category) {
				$id_categ=$category['categ_id'];
				if ($id_categ) {
					$rqt_ins_values[] = " ('$notice_retour','$id_categ', $i) " ; 
				}				
			}
			$rqt_ins .= implode(",", $rqt_ins_values);
			$res_ins = @mysql_query($rqt_ins, $dbh);
		}
		
		//Traitement des champs personalisés
		$p_perso=new parametres_perso("notices");
		$nberrors=$p_perso->check_submited_fields();
		$p_perso->rec_fields_perso($notice_retour);
		
		//Traitement import perso
		global $notice_id,$notice_org,$notice_type_org;
		if (function_exists('z_recup_noticeunimarc_suite') && function_exists('z_import_new_notice_suite')) {
			$notice_id=$notice_retour;
			if(!$notice_org) 
				$notice_tmp = $this->notice;
			z_recup_noticeunimarc_suite($notice_tmp ? $notice_tmp : $notice_org);
			z_import_new_notice_suite();
			$notice_tmp="";
		}
		
		// Mise à jour de la table notices_global_index
		notice::majNoticesGlobalIndex($notice_retour);
		// Mise à jour de la table notices_mots_global_index
		notice::majNoticesMotsGlobalIndex($notice_retour);
		
		//Documents numériques
		//highlight_string(print_r($this->doc_nums, true));
		foreach($this->doc_nums as $doc_num) {
			if (!$doc_num["a"])
				continue;
			if ($doc_num["__nodownload__"]) {
				explnum_add_url($notice_retour, $doc_num["b"], $doc_num["a"]);
			} else {
				explnum_add_from_url($notice_retour, $doc_num["b"], $doc_num["a"], true, $this->source_id, $doc_num["f"]);
			}
		}
		
		//Si on catalog un article on recrée l'arborescence
		global $biblio_notice;
		if($biblio_notice == 'art'){	
			//Perios
			if(!$this->perio_id){
				$new_perio = new serial();
				$values=array();
				$values['tit1'] = $this->perio_titre;
				$values['code'] = $this->perio_issn;
				$values['niveau_biblio'] = "s";
				$values['niveau_hierar'] = "1";
				$this->perio_id = $new_perio->update($values);
			}
			
			//Bulletin
			if($this->bull_id){
				$req_art = "insert into analysis set analysis_bulletin='".$this->bull_id."', 
					analysis_notice='".$notice_retour."'";
				mysql_query($req_art,$dbh);
				$req = "update bulletins set bulletin_notice='".$this->perio_id."' where bulletin_id='".$this->bull_id."'";
				mysql_query($req,$dbh);
			} else {
				$new_bull = new bulletinage(0,$this->perio_id);
				$values = array();
				$values['bul_no'] = $this->bull_num;
				$values['bul_date'] = $this->bull_mention;
				$values['date_date'] = $this->bull_date;
				$values['bul_titre'] = $this->bull_titre;
				$new_bull->update($values);
				$this->bull_id =$new_bull->bulletin_id;
				$req_art = "insert into analysis set analysis_bulletin='".$this->bull_id."', 
					analysis_notice='".$notice_retour."'";
				mysql_query($req_art,$dbh);
			}
		}
		
		$retour = array ($new_notice, $notice_retour);	
		return $retour;
	} 

	function update_in_database ($id_notice=0) {
		global $dbh ;
		$new_notice = 2;
		$notice_retour = $id_notice;
		
		if (!$id_notice) {
			$retour = array (2, 0);	
			return $retour;
			}
		// traitement des titres uniformes	
		global $pmb_use_uniform_title;
		if ($pmb_use_uniform_title) {
			if(count($this->titres_uniformes)) {
				$ntu=new tu_notice($id_notice);
				$ntu->update($this->titres_uniformes);
			}
		}
			
		for ($i = 0; $i < 2; $i++) {
			if ($this->editors[$i]['id'])
				$editor_ids[$i] = $this->editors[$i]['id'];
			else 
				$editor_ids[$i] = editeur::import ($this->editors[$i]);
		}
		
		if ($this->collection["id"])
			$collection_id = $this->collection["id"];
		else {
			$this->collection['parent'] = $editor_ids[0];
			$collection_id = collection::import ($this->collection);
		}
		
		if ($this->subcollection["id"]) {
			$subcollection_id = $this->subcollection["id"];
		}
		else {
			$this->subcollection['coll_parent'] = $collection_id;
			$subcollection_id = subcollection::import ($this->subcollection);
			$serie_id = serie::import(stripslashes($this->serie));			
		}

		/* traitement de Dewey */
		if (!$this->internal_index) {
			if (!$this->dewey["new_comment"])
				$this->dewey["new_comment"] = "";
			if (!$this->dewey["new_pclass"])
				$this->dewey["new_pclass"] = "";			
			$this->internal_index = indexint::import(clean_string($this->dewey[0]), clean_string($this->dewey["new_comment"]), clean_string($this->dewey["new_pclass"]));
		}
				
		$title_indexes_wew = $this->serie ;
		for ($i = 0; $i < 4; $i++) {
			$title_indexes_wew .= " ".$this->titles[$i] ;
			}
		$title_indexes_sew = strip_empty_words ($title_indexes_wew);
		$serie_index = strip_empty_words ($this->serie);
		
		$this->free_index ? $this->matieres_index = ' '.strip_empty_words($this->free_index).' ' : $this->matieres_index = '';
		
		$this->general_note	?	$index_n_gen = strip_empty_words($this->general_note) : $index_n_gen = '';
		$this->content_note	?	$index_n_contenu = strip_empty_words($this->content_note) : $index_n_contenu = '';
		$this->abstract_note	?	$index_n_resume = strip_empty_words($this->abstract_note) : $index_n_resume = '';
		
		$date_parution_z3950 = notice::get_date_parution($this->year);
		/* Origine de la notice */
		$this->orinot_id = origine_notice::import($this->origine_notice);
		if ($this->orinot_id==0) $this->orinot_id=1 ;
						
		$sql_ins = "update notices set
			typdoc           	='".$this->document_type."',
			code        	        ='".$this->isbn."',	            
			tit1                    ='".$this->titles[0]."',             
			tit2                    ='".$this->titles[1]."',             
			tit3                    ='".$this->titles[2]."',             
			tit4                    ='".$this->titles[3]."',             
			tparent_id              ='".$serie_id."',                    
			tnvol                   ='".$this->nbr_in_serie."',          
			ed1_id                  =".$editor_ids[0]." ,                
			ed2_id                  =".$editor_ids[1]." ,                
			year                    ='".$this->year."',                  
			npages                  ='".$this->page_nbr."',              
			ill                     ='".$this->illustration."',          
			size                    ='".$this->size."',                  
			accomp                  ='".$this->accompagnement."',        
			coll_id                 =".$collection_id." ,                
			subcoll_id              =".$subcollection_id." ,             
			nocoll                  ='".$this->nbr_in_collection."',     
			mention_edition         ='".$this->mention_edition."',       
			n_gen                   ='".$this->general_note."',          
			n_contenu               ='".$this->content_note."',          
			n_resume                ='".$this->abstract_note."',         
			indexint                ='".$this->internal_index."',        
			index_serie             =' ".$serie_index." ',               
			index_sew               =' ".$title_indexes_sew." ',         
			index_wew               ='".$title_indexes_wew."',           
			statut					='".$this->statut."',
			commentaire_gestion		='".$this->commentaire_gestion."',
			thumbnail_url			='".$this->thumbnail_url."',
			index_l                 ='".clean_tags($this->free_index)."',            
			index_matieres          ='".$this->matieres_index."',      
			niveau_biblio           ='".$this->bibliographic_level."',   
			niveau_hierar           ='".$this->hierarchic_level."',      
			lien                    ='".$this->link_url."',              
			eformat                 ='".$this->link_format."',           
			origine_catalogage      ='".$this->orinot_id."',             
			prix                    ='".$this->prix."',                  
	        index_n_gen             ='".$index_n_gen."',               
			index_n_contenu         ='".$index_n_contenu."',           
			index_n_resume          ='".$index_n_resume."',
			date_parution 			='".$date_parution_z3950."'             
			where notice_id='$id_notice' ";
		//echo "<pre>";
		//print_r($this->aut_array);
		//echo "</pre>";
		//echo $sql_ins."<br />";
		//echo "<pre>";
		//print_r($this->categories);
		//echo "</pre>";
		//exit;
		$sql_result_ins = mysql_query($sql_ins) or die ("Couldn't update notices : ".$sql_ins);
		$notice_retour = $id_notice ;
		audit::insert_modif (AUDIT_NOTICE, $id_notice) ;
		
		// purge de la base des responsabilités de la notice intégrée...
		if ($notice_retour) {
			$rqt_del = "delete from responsability where responsability_notice='$notice_retour'" ;
			$sql_result_del = mysql_query($rqt_del) or die ("Couldn't purge table responsability : ".$rqt_del);
			}
		$rqt_ins = "insert into responsability (responsability_author, responsability_notice, responsability_fonction, responsability_type, responsability_ordre) VALUES ";
		for ($i=0 ; $i<sizeof($this->aut_array) ; $i++ ){
			$aut['name']=clean_string($this->aut_array[$i]['entree']);
			$aut['rejete']=clean_string($this->aut_array[$i]['rejete']);
			$aut['date']=clean_string($this->aut_array[$i]['date']);
			$aut['type']=$this->aut_array[$i]['type_auteur'];
			$aut['subdivision']=clean_string($this->aut_array[$i]['subdivision']);
			$aut['numero']=clean_string($this->aut_array[$i]['numero']);
			$aut['lieu']=clean_string($this->aut_array[$i]['lieu']);
			$aut['ville']=clean_string($this->aut_array[$i]['ville']);
			$aut['pays']=clean_string($this->aut_array[$i]['pays']);
			$aut['web']=clean_string($this->aut_array[$i]['web']);
			$aut['author_comment']=clean_string($this->aut_array[$i]['author_comment']);
			if (!$this->aut_array[$i]["id"])
				$this->aut_array[$i]["id"] = auteur::import($aut);
			if ($this->aut_array[$i]["id"]) {
				$rqt = $rqt_ins . " (".$this->aut_array[$i]["id"].",".$notice_retour.",'".$this->aut_array[$i]['fonction']."',".$this->aut_array[$i]['responsabilite'].",".$i.") " ; 
				$res_ins = mysql_query($rqt, $dbh);
			}
		}
		
		// traitement des categories
		if ($this->categorisation_type == "categorisation_auto") {
			traite_categories_enreg($notice_retour,$this->categories);			
		}
		else {
			$rqt_del = "delete from notices_categories where notcateg_notice='$notice_retour' ";
			$res_del = @mysql_query($rqt_del, $dbh);
			
			$rqt_ins = "insert into notices_categories (notcateg_notice, num_noeud, ordre_categorie) VALUES ";
			
			$rqt_ins_values = array();
			foreach ($this->categories as $category) {
				$id_categ=$category['categ_id'];
				if ($id_categ) {
					$rqt_ins_values[] = " ('$notice_retour','$id_categ', $i) " ; 
				}				
			}
			$rqt_ins .= implode(",", $rqt_ins_values);
			$res_ins = @mysql_query($rqt_ins, $dbh);
		}
		
		// traitement des langues
		// langues
		global $max_lang, $max_langorg;
		$f_lang_form = array();
		$f_langorg_form = array() ;
		for ($i=0; $i< $max_lang ; $i++) {
			$var_langcode = "f_lang_code$i" ;
			global $$var_langcode ;
			if ($$var_langcode) $f_lang_form[] =  array ('code' => $$var_langcode);
			}
		// langues originales
		for ($i=0; $i< $max_langorg ; $i++) {
			$var_langorgcode = "f_langorg_code$i" ;
			global $$var_langorgcode;
			if ($$var_langorgcode) $f_langorg_form[] =  array ('code' => $$var_langorgcode);
			}

		$rqt_del = "delete from notices_langues where num_notice='$notice_retour' ";
		$res_del = mysql_query($rqt_del, $dbh);
		$rqt_ins = "insert into notices_langues (num_notice, type_langue, code_langue) VALUES ";
		while (list ($key, $val) = each ($f_lang_form)) {
			$tmpcode_langue=$val['code'];
			if ($tmpcode_langue) {
				$rqt = $rqt_ins . " ('$notice_retour',0, '$tmpcode_langue') " ; 
				$res_ins = mysql_query($rqt, $dbh);
				}
			}
	
		// traitement des langues originales
		$rqt_ins = "insert into notices_langues (num_notice, type_langue, code_langue) VALUES ";
		while (list ($key, $val) = each ($f_langorg_form)) {
			$tmpcode_langue=$val['code'];
			if ($tmpcode_langue) {
				$rqt = $rqt_ins . " ('$notice_retour',1, '$tmpcode_langue') " ; 
				$res_ins = @mysql_query($rqt, $dbh);
				}
			}
	
		//Traitement import perso
		global $notice_id,$notice_org,$notice_type_org;
		if (function_exists('recup_noticeunimarc_suite')) {
			//Suppression des champs persos
			$requete="delete from notices_custom_values where notices_custom_origine=".$notice_retour;
			@mysql_query($requete);
			$notice_id=$notice_retour;
			z_recup_noticeunimarc_suite($notice_org);
			z_import_new_notice_suite();
			}
		
		// Mise à jour de la table notices_global_index
		notice::majNoticesGlobalIndex($notice_retour);
		
		//Documents numériques
		//highlight_string(print_r($this->doc_nums, true));
		foreach($this->doc_nums as $doc_num) {
			if (!$doc_num["a"])
				continue;
			explnum_add_from_url($notice_retour, $doc_num["b"], $doc_num["a"], false,$this->source_id, $doc_num["f"]);
		}
		
		$retour = array ($new_notice, $notice_retour);	
		return $retour;
		} 

	function get_form ($action, $id_notice=0,$retour=false,$article=false) {
		// construit le formulaire de catalogage pré-rempli
		global $msg;
		global $charset;
		global $include_path;
		global $base_path;
		global $dbh ;
		global $znotices_id;
		global $item;
	
		$fonction = new marc_list('function');

		$this->action = $action;

		include("$include_path/templates/z3950_form.tpl.php");

		// mise à jour de l'entête du formulaire
		$form_notice = str_replace('!!libelle_form!!', $this->libelle_form, $form_notice);
		
		// mise à jour des flags de niveau hiérarchique
		$form_notice = str_replace('!!b_level!!', $this->bibliographic_level, $form_notice);
		$form_notice = str_replace('!!h_level!!', $this->hierarchic_level, $form_notice);

		for ($i = 0; $i < 4; $i++) {
			$this->substitute ("title_$i", $this->titles[$i], $ptab[0]);
			}
		$this->substitute ("serie", $this->serie, $ptab[0]);
		$this->substitute ("nbr_in_serie", $this->nbr_in_serie, $ptab[0]);
		$form_notice = str_replace('!!tab0!!', $ptab[0], $form_notice);
		 
		// mise à jour de l'onglet 1
		// constitution de la mention de responsabilité
		//echo "<pre>";
		//print_r($this->aut_array);
		//echo "</pre>";
		$nb_autres_auteurs = 0 ;
		$nb_auteurs_secondaires = 0 ;//print "<pre>";print_r($this->aut_array);print "</pre>";
		for ($as = 0 ; $as < sizeof($this->aut_array) ; $as++ ){
			if ($this->aut_array[$as]["responsabilite"]===0) {
				$numrows = 0;
				if ($this->aut_array[$as]["author_date"]) {
					$sql_author_find = "SELECT author_id, author_name, author_rejete, author_date FROM authors WHERE author_name = '".addslashes($this->aut_array[$as]["entree"])."' AND author_rejete = '".addslashes($this->aut_array[$as]["rejete"])."' AND author_type = '".$this->aut_array[$as]["type_auteur"]."' AND author_date ='".addslashes($this->aut_array[$as]["author_date"])."'";
					$res = mysql_query($sql_author_find);
					$numrows = mysql_num_rows($res);
				}
				if (!$numrows) {
					$sql_author_find = "SELECT author_id, author_name, author_rejete, author_date FROM authors WHERE author_name = '".addslashes($this->aut_array[$as]["entree"])."' AND author_rejete = '".addslashes($this->aut_array[$as]["rejete"])."' AND author_type = '".$this->aut_array[$as]["type_auteur"]."'";
					$res = mysql_query($sql_author_find);
					$numrows = mysql_num_rows($res);					
				}
				if ($numrows == 1) {
					$existing_author = mysql_fetch_array($res);
					$existing_author_id = $existing_author["author_id"];
				}
				else $existing_author_id = 0;
				$this->substitute ("author0_type_use_existing", $existing_author_id ? "checked" : "", $ptab[1]);
				$this->substitute ("author0_type_insert_new", $existing_author_id ? "" : "checked", $ptab[1]);
				if ($existing_author_id) {
					$this->substitute ("f_author_name_0_existing", $existing_author["author_name"].", ".$existing_author["author_rejete"].($existing_author["author_date"] ? " (".$existing_author["author_date"].")" : ""), $ptab[1]);
					$this->substitute ("f_aut0_existing_id", $existing_author_id, $ptab[1]);
				}
				else {
					$this->substitute ("f_author_name_0_existing", '', $ptab[1]);
					$this->substitute ("f_aut0_existing_id", 0, $ptab[1]);					
				}

				$this->substitute ("author_name_0", $this->aut_array[$as]["entree"], $ptab[1]);
				$this->substitute ("author_rejete_0", $this->aut_array[$as]["rejete"], $ptab[1]);
				$this->substitute ("author_date_0", $this->aut_array[$as]["date"], $ptab[1]);
				$this->substitute ("author_function_0", $this->aut_array[$as]["fonction"], $ptab[1]);
				$this->substitute ("author_function_label_0", $fonction->table[$this->aut_array[$as]["fonction"]], $ptab[1]);
				$this->substitute ("author_lieu_0", $this->aut_array[$as]["lieu"], $ptab[1]);
				$this->substitute ("author_pays_0", $this->aut_array[$as]["pays"], $ptab[1]);
				
				for ($type = 70; $type <= 72; $type++) {
					if ($this->aut_array[$as]["type_auteur"] == $type) 
						$sel = " selected";
					else $sel = "";
					$this->substitute ("author_type_".$type."_0", $sel, $ptab[1]);
					if($this->aut_array[$as]["type_auteur"] == '70')
					    $this->substitute ('display_0','none', $ptab[1]);
					else 
						$this->substitute ('display_0','', $ptab[1]);
				}						
					
				}
			if ($this->aut_array[$as]["responsabilite"]==1) {
				if ($this->aut_array[$as]["entree"] == "") continue; 
				$ptab_aut_autres = str_replace('!!iaut!!', $nb_autres_auteurs, $ptab[11]) ;

				$numrows = 0;
				if ($this->aut_array[$as]["author_date"]) {
					$sql_author_find = "SELECT author_id, author_name, author_rejete, author_date FROM authors WHERE author_name = '".addslashes($this->aut_array[$as]["entree"])."' AND author_rejete = '".addslashes($this->aut_array[$as]["rejete"])."' AND author_type = '".$this->aut_array[$as]["type_auteur"]."' AND author_date ='".addslashes($this->aut_array[$as]["author_date"])."'";
					$res = mysql_query($sql_author_find);
					$numrows = mysql_num_rows($res);
				}
				if (!$numrows) {
					$sql_author_find = "SELECT author_id, author_name, author_rejete, author_date FROM authors WHERE author_name = '".addslashes($this->aut_array[$as]["entree"])."' AND author_rejete = '".addslashes($this->aut_array[$as]["rejete"])."' AND author_type = '".$this->aut_array[$as]["type_auteur"]."'";
					$res = mysql_query($sql_author_find);
					$numrows = mysql_num_rows($res);					
				}
				if ($numrows == 1) {
					$existing_author = mysql_fetch_array($res);
					$existing_author_id = $existing_author["author_id"];
				}
				else $existing_author_id = 0;
				$this->substitute ("author1_type_use_existing_", $existing_author_id ? "checked" : "", $ptab_aut_autres);
				$this->substitute ("author1_type_insert_new_", $existing_author_id ? "" : "checked", $ptab_aut_autres);
				if ($existing_author_id) {
					$this->substitute ("f_aut1", $existing_author["author_name"].", ".$existing_author["author_rejete"].($existing_author["author_date"] ? " (".$existing_author["author_date"].")" : ""), $ptab_aut_autres);
					$this->substitute ("f_aut1_id", $existing_author_id, $ptab_aut_autres);
				}
				else {
					$this->substitute ("f_aut1", '', $ptab_aut_autres);
					$this->substitute ("f_aut1_id", '', $ptab_aut_autres);
				}

				$this->substitute ("author_name_1", $this->aut_array[$as]["entree"], $ptab_aut_autres);
				$this->substitute ("author_rejete_1", $this->aut_array[$as]["rejete"], $ptab_aut_autres);
				$this->substitute ("author_date_1", $this->aut_array[$as]["date"], $ptab_aut_autres);
				$this->substitute ("author_function_1", $this->aut_array[$as]["fonction"], $ptab_aut_autres);
				$this->substitute ("author_function_label_1", $fonction->table[$this->aut_array[$as]["fonction"]], $ptab_aut_autres);
				$this->substitute ("author_lieu_1", $this->aut_array[$as]["lieu"], $ptab_aut_autres);
				$this->substitute ("author_pays_1", $this->aut_array[$as]["pays"], $ptab_aut_autres);
				for ($type = 70; $type <= 72; $type++) {
					if ($this->aut_array[$as]["type_auteur"] == $type) $sel = " selected";
						else $sel = "";
					$this->substitute ("author_type_".$type."_1", $sel, $ptab_aut_autres);
					if($this->aut_array[$as]["type_auteur"] == '70')
						$this->substitute ('display_1'.$nb_autres_auteurs,'none', $ptab_aut_autres);
					else 
						$this->substitute ('display_1'.$nb_autres_auteurs,'', $ptab_aut_autres);
				}
					
				$autres_auteurs .= $ptab_aut_autres ;
				$nb_autres_auteurs++ ;
				}
				
			
			if ($this->aut_array[$as]["responsabilite"]==2) {
				if ($this->aut_array[$as]["entree"] == "") continue; 
				$ptab_aut_autres = str_replace('!!iaut!!', $nb_auteurs_secondaires, $ptab[12]) ;
				
				$numrows = 0;
				if ($this->aut_array[$as]["author_date"]) {
					$sql_author_find = "SELECT author_id, author_name, author_rejete, author_date FROM authors WHERE author_name = '".addslashes($this->aut_array[$as]["entree"])."' AND author_rejete = '".addslashes($this->aut_array[$as]["rejete"])."' AND author_type = '".$this->aut_array[$as]["type_auteur"]."' AND author_date ='".addslashes($this->aut_array[$as]["author_date"])."'";
					$res = mysql_query($sql_author_find);
					$numrows = mysql_num_rows($res);
				}
				if (!$numrows) {
					$sql_author_find = "SELECT author_id, author_name, author_rejete, author_date FROM authors WHERE author_name = '".addslashes($this->aut_array[$as]["entree"])."' AND author_rejete = '".addslashes($this->aut_array[$as]["rejete"])."' AND author_type = '".$this->aut_array[$as]["type_auteur"]."'";
					$res = mysql_query($sql_author_find);
					$numrows = mysql_num_rows($res);					
				}
				if ($numrows == 1) {
					$existing_author = mysql_fetch_array($res);
					$existing_author_id = $existing_author["author_id"];
				}
				else $existing_author_id = 0;
				$this->substitute ("author2_type_use_existing_", $existing_author_id ? "checked" : "", $ptab_aut_autres);
				$this->substitute ("author2_type_insert_new_", $existing_author_id ? "" : "checked", $ptab_aut_autres);
				if ($existing_author_id) {
					$this->substitute ("f_aut2", $existing_author["author_name"].", ".$existing_author["author_rejete"].($existing_author["author_date"] ? " (".$existing_author["author_date"].")" : ""), $ptab_aut_autres);
					$this->substitute ("f_aut2_id", $existing_author_id, $ptab_aut_autres);
				}
				else {
					$this->substitute ("f_aut2", '', $ptab_aut_autres);
					$this->substitute ("f_aut2_id", 0, $ptab_aut_autres);
				}
				
				$this->substitute ("author_name_2", $this->aut_array[$as]["entree"], $ptab_aut_autres);
				$this->substitute ("author_rejete_2", $this->aut_array[$as]["rejete"], $ptab_aut_autres);
				$this->substitute ("author_date_2", $this->aut_array[$as]["date"], $ptab_aut_autres);
				$this->substitute ("author_function_2", $this->aut_array[$as]["fonction"], $ptab_aut_autres);
				$this->substitute ("author_function_label_2", $fonction->table[$this->aut_array[$as]["fonction"]], $ptab_aut_autres);
				$this->substitute ("author_lieu_2", $this->aut_array[$as]["lieu"], $ptab_aut_autres);
				$this->substitute ("author_pays_2", $this->aut_array[$as]["pays"], $ptab_aut_autres);
				for ($type = 70; $type <= 72; $type++) {
					if ($this->aut_array[$as]["type_auteur"] == $type) 
						$sel = " selected";
					else $sel = "";
					$this->substitute ("author_type_".$type."_2", $sel, $ptab_aut_autres);
					if($this->aut_array[$as]["type_auteur"] == '70')
						$this->substitute ('display_2'.$nb_auteurs_secondaires,'none', $ptab_aut_autres);
					else 
						$this->substitute ('display_2'.$nb_auteurs_secondaires,'', $ptab_aut_autres);
				}
				$auteurs_secondaires .= $ptab_aut_autres ;
				$nb_auteurs_secondaires++ ;
			}
		}
		// au cas ou pas d'auteur principal : on fait le ménage dans le formulaire
		$this->substitute ("author_name_0", "", $ptab[1]);
		$this->substitute ("author_rejete_0", "", $ptab[1]);
		$this->substitute ("author_date_0", "", $ptab[1]);
		$this->substitute ("author_function_0", "", $ptab[1]);
		$this->substitute ("author_function_label_0", "", $ptab[1]);
		$this->substitute ("f_author_name_0_existing", "", $ptab[1]);
		$this->substitute ("f_aut0_existing_id", "", $ptab[1]);
		$this->substitute ("author0_type_use_existing", "", $ptab[1]);
		$this->substitute ("author0_type_insert_new", "checked", $ptab[1]);
		$this->substitute ("author_lieu_0", "", $ptab[1]);
		$this->substitute ("author_pays_0", "", $ptab[1]);
		$this->substitute ('display_0','none', $ptab[1]);

		
		$ptab[1] = str_replace('!!max_aut1!!', $nb_autres_auteurs+1, $ptab[1]);
		$ptab[1] = str_replace('!!iaut_added1!!', $nb_autres_auteurs, $ptab[1]);
		$ptab[1] = str_replace('!!max_aut2!!', $nb_auteurs_secondaires+1, $ptab[1]);
		$ptab[1] = str_replace('!!iaut_added2!!', $nb_auteurs_secondaires, $ptab[1]);
		
		$ptab[1] = str_replace('!!autres_auteurs!!', $autres_auteurs, $ptab[1]);
		$ptab[1] = str_replace('!!auteurs_secondaires!!', $auteurs_secondaires, $ptab[1]);
		$form_notice = str_replace('!!tab1!!', $ptab[1], $form_notice);
		
		//Editeur 1
		//On tente avec toutes les infos
		if ($this->editors[0]['name'] && $this->editors[0]['ville']) {
			$sql_find_publisher = "SELECT ed_id,ed_ville,ed_name FROM publishers WHERE ed_name = '".addslashes($this->editors[0]['name'])."' AND ed_ville = '".addslashes($this->editors[0]['ville'])."'";
			$res = mysql_query($sql_find_publisher);
			if (mysql_num_rows($res) == 1) {
				$existing_publisher = mysql_fetch_array($res);
				$existing_publisher_id = $existing_publisher["ed_id"];
			}			
		}
		//Non? Le nom sans ville peut être alors?
		if (!$existing_publisher_id && $this->editors[0]['name'] && $this->editors[0]['ville']){
			$sql_find_publisher = "SELECT ed_id,ed_ville,ed_name FROM publishers WHERE ed_name = '".addslashes($this->editors[0]['name'])."' AND ed_ville = ''";
			$res = mysql_query($sql_find_publisher);
			if (mysql_num_rows($res) == 1) {
				$existing_publisher = mysql_fetch_array($res);
				$existing_publisher_id = $existing_publisher["ed_id"];
			}
		}
		//Juste le nom alors?
		if (!$existing_publisher_id && $this->editors[0]['name'] && !$this->editors[0]['ville']){
			$sql_find_publisher = "SELECT ed_id,ed_ville,ed_name FROM publishers WHERE ed_name = '".addslashes($this->editors[0]['name'])."'";
			$res = mysql_query($sql_find_publisher);
			if (mysql_num_rows($res) == 1) {
				$existing_publisher = mysql_fetch_array($res);
				$existing_publisher_id = $existing_publisher["ed_id"];
			}
		}
		if (!$existing_publisher_id) 
			$existing_publisher_id = 0;
		$this->substitute ("editor_type_use_existing", $existing_publisher_id ? 'checked' : '', $ptab[2]);
		$this->substitute ("editor_type_insert_new", $existing_publisher_id ? '' : 'checked', $ptab[2]);
		if ($existing_publisher_id) {
			$editor = new editeur($existing_publisher_id);
			$editor_display = $editor->display;
			if (!$editor_display) {
				$info_ville = $existing_publisher["ed_ville"] ? ' ('.$existing_publisher["ed_ville"].')' : "";
				$editor_display = $existing_publisher["ed_name"].$info_ville;
			}
			$this->substitute ("f_ed1", $editor_display, $ptab[2]);
			$this->substitute ("f_ed1_id", $existing_publisher_id, $ptab[2]);
		}
		else {
			$this->substitute ("f_ed1", '', $ptab[2]);
			$this->substitute ("f_ed1_id", '', $ptab[2]);
		}
		
		$this->substitute ("editor_name_0", $this->editors[0]['name'], $ptab[2]);
		$this->substitute ("editor_ville_0", $this->editors[0]['ville'], $ptab[2]);
		
		//Editeur 2
		//On tente avec toutes les infos
		if ($this->editors[1]['name'] && $this->editors[1]['ville']) {
			$sql_find_publisher2 = "SELECT ed_id,ed_ville,ed_name FROM publishers WHERE ed_name = '".addslashes($this->editors[1]['name'])."' AND ed_ville = '".addslashes($this->editors[1]['ville'])."'";
			$res = mysql_query($sql_find_publisher2);
			if (mysql_num_rows($res) == 1) {
				$existing_publisher2 = mysql_fetch_array($res);
				$existing_publisher_id2 = $existing_publisher2["ed_id"];
			}			
		}
		//Non? Le nom sans ville peut être alors?
		if (!$existing_publisher_id2 && $this->editors[1]['name'] && $this->editors[1]['ville']){
			$sql_find_publisher2 = "SELECT ed_id,ed_ville,ed_name FROM publishers WHERE ed_name = '".addslashes($this->editors[1]['name'])."' AND ed_ville = ''";
			$res = mysql_query($sql_find_publisher2);
			if (mysql_num_rows($res) == 1) {
				$existing_publisher2 = mysql_fetch_array($res);
				$existing_publisher_id2 = $existing_publisher2["ed_id"];
			}
		}
		//Juste le nom alors?
		if (!$existing_publisher_id2 && $this->editors[1]['name'] && !$this->editors[1]['ville']){
			$sql_find_publisher2 = "SELECT ed_id,ed_ville,ed_name FROM publishers WHERE ed_name = '".addslashes($this->editors[1]['name'])."'";
			$res = mysql_query($sql_find_publisher2);
			if (mysql_num_rows($res) == 1) {
				$existing_publisher2 = mysql_fetch_array($res);
				$existing_publisher_id2 = $existing_publisher2["ed_id"];
			}
		}
		if (!$existing_publisher_id2) 
			$existing_publisher_id2 = 0;
		
		$this->substitute ("editor1_type_use_existing", $existing_publisher_id2 ? 'checked' : '', $ptab[2]);
		$this->substitute ("editor1_type_insert_new", $existing_publisher_id2 ? '' : 'checked', $ptab[2]);
		if ($existing_publisher_id2) {
			$editor = new editeur($existing_publisher_id2);
			$editor_display = $editor->display;
			if (!$editor_display) {
				$info_ville = $existing_publisher2["ed_ville"] ? ' ('.$existing_publisher2["ed_ville"].')' : "";
				$editor_display = $existing_publisher2["ed_name"].$info_ville;
			}
			$this->substitute ("f_ed11", $editor_display, $ptab[2]);
			$this->substitute ("f_ed11_id", $existing_publisher_id2, $ptab[2]);
		}
		else {
			$this->substitute ("f_ed11", '', $ptab[2]);
			$this->substitute ("f_ed11_id", '', $ptab[2]);
		}
				
		$this->substitute ("editor_name_1", $this->editors[1]['name'], $ptab[2]);
		$this->substitute ("editor_ville_1", $this->editors[1]['ville'], $ptab[2]);
		
		//Collection
		if ($existing_publisher_id && $this->collection['name']) {
			$sql_collection_find = "SELECT collection_id, collection_name FROM collections WHERE collection_name = '".addslashes($this->collection['name'])."' AND collection_parent = '".$existing_publisher_id."'";
			$res = mysql_query($sql_collection_find);
			if (mysql_num_rows($res) == 1) {
				$existing_collection = mysql_fetch_array($res);
				$existing_collection_id = $existing_collection["collection_id"];
			}
			else $existing_collection_id = 0;			
		}
		else $existing_collection_id = 0;
		
		$this->substitute ("collection_type_use_existing", $existing_collection_id ? 'checked' : '', $ptab[2]);
		$this->substitute ("collection_type_insert_new", $existing_collection_id ? '' : 'checked', $ptab[2]);
		
		if ($existing_collection_id) {
			$this->substitute ("f_coll_existing", $existing_collection["collection_name"], $ptab[2]);
			$this->substitute ("f_coll_existing_id", $existing_collection_id ? '' : 'checked', $ptab[2]);
		}
		else {
			$this->substitute ("f_coll_existing", '', $ptab[2]);
			$this->substitute ("f_coll_existing_id", '0', $ptab[2]);			
		}
		$this->substitute ("collection_name", $this->collection['name'], $ptab[2]);
		$this->substitute ("collection_issn", $this->collection['issn'], $ptab[2]);
		
		//Sous Collection
		if ($existing_collection_id && $this->subcollection['name']) {
			$sql_subcollection_find = "SELECT sub_coll_id, sub_coll_name FROM sub_collections WHERE sub_coll_name = '".addslashes($this->subcollection['name'])."' AND sub_coll_parent = '".$existing_collection_id."'";
			$res = mysql_query($sql_subcollection_find) or die(mysql_error()."<br />$sql_subcollection_find");
			if (mysql_num_rows($res) == 1) {
				$existing_subcollection = mysql_fetch_array($res);
				$existing_subcollection_id = $existing_subcollection["sub_coll_id"];
			}
			else $existing_subcollection_id = 0;
		}
		else $existing_subcollection_id = 0;
		
		$this->substitute ("subcollection_type_use_existing", $existing_subcollection_id ? 'checked' : '', $ptab[2]);
		$this->substitute ("subcollection_type_insert_new", $existing_subcollection_id ? '' : 'checked', $ptab[2]);
		
		if ($existing_subcollection_id) {
			$this->substitute ("f_subcoll_existing", $existing_subcollection["sub_coll_name"], $ptab[2]);
			$this->substitute ("f_subcoll_existing_id", $existing_subcollection_id ? '' : 'checked', $ptab[2]);
		}
		else {
			$this->substitute ("f_subcoll_existing", '', $ptab[2]);
			$this->substitute ("f_subcoll_existing_id", '0', $ptab[2]);			
		}
		$this->substitute ("subcollection_name", $this->subcollection['name'], $ptab[2]);
		$this->substitute ("subcollection_issn", $this->subcollection['issn'], $ptab[2]);

		$this->substitute ("nbr_in_collection", $this->nbr_in_collection, $ptab[2]);

		$this->substitute ("year", $this->year, $ptab[2]);
		
		$this->substitute ("mention_edition", $this->mention_edition, $ptab[2]);
		
		$form_notice = str_replace('!!tab2!!', $ptab[2], $form_notice);
		
		$this->substitute ("isbn", $this->isbn, $ptab[3]);

		$form_notice = str_replace('!!tab3!!', $ptab[3], $form_notice);
		
		$this->substitute ("page_nbr", $this->page_nbr, $ptab[4]);
		$this->substitute ("illustration", $this->illustration, $ptab[4]);
		$this->substitute ("prix", $this->prix, $ptab[4]);
		$this->substitute ("accompagnement", $this->accompagnement, $ptab[4]);
		$this->substitute ("size", $this->size, $ptab[4]);
		$form_notice = str_replace('!!tab4!!', $ptab[4], $form_notice);

		$this->substitute ("general_note", $this->general_note, $ptab[5]);
		$this->substitute ("content_note", $this->content_note, $ptab[5]);
		$this->substitute ("abstract_note", $this->abstract_note, $ptab[5]);
		$form_notice = str_replace('!!tab5!!', $ptab[5], $form_notice);

		// indexation interne
		$pclassement_sql = "SELECT * FROM pclassement";
		$res = mysql_query($pclassement_sql);
		$pclassement_count = mysql_num_rows($res);
		if (!$pclassement_count)
			$pclassement_count = 1;

		if ($pclassement_count > 1) {
			$pclassements = array();
			while ($row = mysql_fetch_assoc($res)) {
				$pclassements[] = array("id" => $row["id_pclass"], "name" => $row["name_pclass"]);
			}

			$pclassement_combobox = '<select name="f_indexint_new_pclass">';
			foreach($pclassements as $pclassement) {
				$pclassement_combobox .= '<option value="'.$pclassement["id"].'">'.$pclassement["name"].'</option>';
			}
			$pclassement_combobox .= '</select>';
		}
		else
			$pclassement_combobox = "";
		
		$ptab[6] = str_replace("!!multiple_pclass_combo_box!!", $pclassement_combobox, $ptab[6]);

		$index_int_sql = "SELECT indexint_name, indexint_comment, indexint_id, name_pclass FROM indexint LEFT JOIN pclassement ON (pclassement.id_pclass = indexint.num_pclass) WHERE indexint_name = '".addslashes($this->dewey[0])."'";
		$res = mysql_query($index_int_sql, $dbh);
		$num_rows = mysql_num_rows($res);
		if ($num_rows == 1) {
			$the_row = mysql_fetch_assoc($res);
			$this->substitute ("indexint", $the_row["indexint_name"].': '.$the_row["indexint_comment"], $ptab[6]);
			$this->substitute ("indexint_id", $the_row["indexint_id"], $ptab[6]);
			
			$this->substitute ("indexint_type_use_existing", 'checked', $ptab[6]);
			$this->substitute ("indexint_type_insert_new", '', $ptab[6]);
			$this->substitute ("multiple_index_int_propositions", '', $ptab[6]);
		}
		else if ($num_rows > 1) {
			
			$index_ints = array();
			while($row = mysql_fetch_assoc($res)) {
				$index_ints[] = array("id" => $row["indexint_id"], "name" => $row["indexint_name"], "comment" => $row["indexint_comment"], "pclass" => $row["name_pclass"]);
			}
			
			$form_indexint_proposition = "<ul>";
			foreach ($index_ints as $index_int) {
				$form_indexint_proposition .= "<li><b>[".$index_int["pclass"]."]</b> ".$index_int["name"].": ".$index_int["comment"].'&nbsp;';
				$jsaction = "document.getElementById('indexint_type_use_existing').checked=1; document.getElementById('f_indexint').value='".addslashes($index_int["name"].' - '.$index_int["comment"])."'; document.getElementById('f_indexint_id').value='".addslashes($index_int["id"])."'";
				$form_indexint_proposition .= '<input type="button" class="bouton" value="'.$msg["notice_integre_indexint_use"].'" onclick="'.$jsaction.'">';
				$form_indexint_proposition .= '</li>';
			}
			$form_indexint_proposition .= "</ul>";
			$ptab[6] = str_replace("!!multiple_index_int_propositions!!", $form_indexint_proposition, $ptab[6]);
			
			$this->substitute ("indexint", "", $ptab[6]);
			$this->substitute ("indexint_id", "", $ptab[6]);
			$this->substitute ("indexint_type_use_existing", 'checked', $ptab[6]);
			$this->substitute ("indexint_type_insert_new", '', $ptab[6]);			
		}
		else {
			$this->substitute ("indexint", "", $ptab[6]);
			$this->substitute ("indexint_id", "", $ptab[6]);
			$this->substitute ("indexint_type_use_existing", '', $ptab[6]);
			$this->substitute ("indexint_type_insert_new", 'checked', $ptab[6]);
			$this->substitute ("multiple_index_int_propositions", '', $ptab[6]);
		}
		$this->substitute ("indexint_new_name", $this->dewey[0], $ptab[6]);
		$this->substitute ("indexint_new_comment", "", $ptab[6]);

		// indexation libre
		$this->substitute ("f_free_index", $this->free_index, $ptab[6]);
		global $pmb_keyword_sep ;
		$sep="'$pmb_keyword_sep'";
		if (!$pmb_keyword_sep) $sep="' '";
		if(ord($pmb_keyword_sep)==0xa || ord($pmb_keyword_sep)==0xd) $sep=$msg['catalogue_saut_de_ligne'];
		$ptab[6]= str_replace("!!sep!!", htmlentities($sep,ENT_QUOTES, $charset), $ptab[6]);

		$form_notice = str_replace('!!tab6!!', $ptab[6], $form_notice);
			
		// Gestion des titres uniformes 	
		$nb_tu=sizeof($this->tu_500);
		for ($i=0 ; $i<$nb_tu ; $i++ ) {
			$value_tu[$i]['name'] = $this->tu_500[$i]['a'];
			$ntu_data[$i]->tu->name = $this->tu_500[$i]['a'];			
			$value_tu[$i]['tonalite'] = $this->tu_500[$i]['u'];
			
			for($j=0;$j<count($this->tu_500_r[$i]);$j++) {	
				$value_tu[$i]['distrib'][$j]= $this->tu_500_r[$i][$j];	
			}
			for($j=0;$j<count($this->tu_500_s[$i]);$j++) {		
				$value_tu[$i]['ref'][$j]= $this->tu_500_s[$i][$j];			
			}
		
			if(($tu_id=titre_uniforme::import_tu_exist($value_tu,1))){
				// 	le titre uniforme est déjà existant
				$ntu_data[$i]->num_tu= $tu_id;
			} else {	
				// le titre uniforme n'est pas existant
				for($j=0;$j<count($this->tu_500_n[$i]);$j++) {	
					$value_tu[$i]['comment'].= $this->tu_500_r[$i][$j];
					if(($j+1)<count($this->tu_500_n[$i]))$value_tu[$i]['comment'].="\n";
				}	
				for($j=0;$j<count($this->tu_500_j[$i]);$j++) {		
					$value_tu[$i]['subdiv'][$j]= $this->tu_500_j[$i][$j];			
				}	
			}
			// memorisation du niveau biblio de ce titre uniforme
			for($j=0;$j<count($this->tu_500_i[$i]);$j++) {	
				$ntu_data[$i]->titre.= $this->tu_500_i[$i][$j];	
				if(($j+1)<count($this->tu_500_i[$i]))$ntu_data[$i]->titre.="; ";	
			}			
			$ntu_data[$i]->date=$this->tu_500[$i]['k'];
			for($j=0;$j<count($this->tu_500_l[$i]);$j++) {	
				$ntu_data[$i]->sous_vedette.= $this->tu_500_l[$i][$j];	
				if(($j+1)<count($this->tu_500_l[$i]))$ntu_data[$i]->sous_vedette.="; "; 
			}			
			$ntu_data[$i]->langue=$this->tu_500[$i]['m'];
			$ntu_data[$i]->version=$this->tu_500[$i]['q'];
			$ntu_data[$i]->mention=$this->tu_500[$i]['w'];
				
		}
	
		// serialisation des champs de l'autorité titre uniforme   
		global $pmb_use_uniform_title;
		if ($pmb_use_uniform_title) {
			$memo_value_tu="<input type='hidden' name='memo_value_tu' value=\"". rawurlencode(serialize($value_tu))."\">";
			$ptab[230] = str_replace("!!titres_uniformes!!", $memo_value_tu.tu_notice::get_form_import("notice",$ntu_data), $ptab[230]);
			$form_notice = str_replace('!!tab230!!', $ptab[230], $form_notice);
		}				

		// mise à jour de l'onglet 7 : langues
		// langues répétables
		$lang = new marc_list('lang');
		if (sizeof($this->language_code)==0) $max_lang = 1 ;
			else $max_lang = sizeof($this->language_code) ; 
		for ($i = 0 ; $i < $max_lang ; $i++) {
			if ($i) $ptab_lang = str_replace('!!ilang!!', $i, $ptab[701]) ;
				else $ptab_lang = str_replace('!!ilang!!', $i, $ptab[70]) ;
			if ( sizeof($this->language_code)==0 ) { 
				$ptab_lang = str_replace('!!lang_code!!', '', $ptab_lang);
				$ptab_lang = str_replace('!!lang!!', '', $ptab_lang);		
				} else {
					$ptab_lang = str_replace('!!lang_code!!', $this->language_code[$i], $ptab_lang);
					$ptab_lang = str_replace('!!lang!!',htmlentities($lang->table[$this->language_code[$i]],ENT_QUOTES, $charset), $ptab_lang);
					}
			$lang_repetables .= $ptab_lang ;
			}
		$ptab[7] = str_replace('!!max_lang!!', $max_lang, $ptab[7]);
		$ptab[7] = str_replace('!!langues_repetables!!', $lang_repetables, $ptab[7]);

		// langues originales répétables
		if (sizeof($this->original_language_code)==0) $max_langorg = 1 ;
			else $max_langorg = sizeof($this->original_language_code) ; 
		for ($i = 0 ; $i < $max_langorg ; $i++) {
			if ($i) $ptab_lang = str_replace('!!ilangorg!!', $i, $ptab[711]) ;
				else $ptab_lang = str_replace('!!ilangorg!!', $i, $ptab[71]) ;
			if ( sizeof($this->original_language_code)==0 ) { 
				$ptab_lang = str_replace('!!langorg_code!!', '', $ptab_lang);
				$ptab_lang = str_replace('!!langorg!!', '', $ptab_lang);		
				} else {
					$ptab_lang = str_replace('!!langorg_code!!', $this->original_language_code[$i], $ptab_lang);
					$ptab_lang = str_replace('!!langorg!!',htmlentities($lang->table[$this->original_language_code[$i]],ENT_QUOTES, $charset), $ptab_lang);
					}
			$langorg_repetables .= $ptab_lang ;
			}
		$ptab[7] = str_replace('!!max_langorg!!', $max_langorg, $ptab[7]);
		$ptab[7] = str_replace('!!languesorg_repetables!!', $langorg_repetables, $ptab[7]);

		$form_notice = str_replace('!!tab7!!', $ptab[7], $form_notice);

		$this->substitute ("link_url", $this->link_url, $ptab[8]);
		$this->substitute ("link_format", $this->link_format, $ptab[8]);

		$form_notice = str_replace('!!tab8!!', $ptab[8], $form_notice);

		// définition de la page cible du form
		$form_notice = str_replace('!!action!!', $this->action, $form_notice);

		// ajout des selecteurs
		$select_doc = new marc_select('doctype', 'typdoc', $this->document_type);
		$form_notice = str_replace('!!document_type!!', $select_doc->display, $form_notice);
		if($article){
			$form_notice = str_replace('!!checked_mono!!', "", $form_notice);
			$form_notice = str_replace('!!checked_art!!', "selected", $form_notice);
		} else {
			$form_notice = str_replace('!!checked_mono!!', "selected", $form_notice);
			$form_notice = str_replace('!!checked_art!!', "", $form_notice);
		}
		
		//Zone des perios et des bulletins pour les articles
		$zone_article_form  = str_replace("!!perio_titre!!",$this->perio_titre[0],$zone_article_form );
		$zone_article_form  = str_replace("!!perio_issn!!",$this->perio_issn[0],$zone_article_form );
		$zone_article_form  = str_replace("!!bull_date!!",$this->bull_mention[0],$zone_article_form );
		$zone_article_form  = str_replace("!!bull_titre!!",$this->bull_titre[0],$zone_article_form );
		$zone_article_form  = str_replace("!!bull_num!!",$this->bull_num[0],$zone_article_form );
		
		if($this->bull_date[0]) {
			$date_date_formatee = formatdate($this->bull_date[0]);
			$date_date_hid = $this->bull_date[0];
		} else {
			$date_date_formatee = '';
			$date_date_hid = '';
		}
		$date_clic = "onClick=\"openPopUp('./select.php?what=calendrier&caller=notice&date_caller=&param1=f_bull_new_date&param2=date_date_lib&auto_submit=NO&date_anterieure=YES', 'date_date', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\"  ";
		$date_date = "<input type='hidden' id='f_bull_new_date' name='f_bull_new_date' value='$date_date_hid' />
			<input class='saisie-10em' type='text' name='date_date_lib' value='".$date_date_formatee."' />
			<input class='bouton' type='button' name='date_date_lib_bouton' value='".$msg["bouton_calendrier"]."' ".$date_clic." />";
		$zone_article_form = str_replace("!!date_date!!",$date_date,$zone_article_form);
		
		//On cherche si le perio existe
		if($this->perio_titre[0] && $this->perio_issn[0]){
			$req="select notice_id, tit1 from notices where niveau_biblio='s' and niveau_hierar='1' 
					and tit1='".addslashes($this->perio_titre[0])."'
					and code='".addslashes($this->perio_issn[0])."' limit 1";
			$res_perio = mysql_query($req,$dbh);
			$num_rows_perio = mysql_num_rows($res_perio);
		}
		if (!$num_rows_perio){
			if($this->perio_titre[0]){
				$req="select notice_id, tit1 from notices where niveau_biblio='s' and niveau_hierar='1' 
					and tit1='".addslashes($this->perio_titre[0])."'
					limit 1";
				$res_perio = mysql_query($req,$dbh);
				$num_rows_perio = mysql_num_rows($res_perio);
			}
		}
		if (!$num_rows_perio){
			if($this->perio_issn[0]){
				$req="select notice_id, tit1 from notices where niveau_biblio='s' and niveau_hierar='1' 
						and code='".addslashes($this->perio_issn[0])."' limit 1";
				$res_perio = mysql_query($req,$dbh);
				$num_rows_perio = mysql_num_rows($res_perio);
			}
		}	
		if ($num_rows_perio == 1) {
			$perio_found = mysql_fetch_object($res_perio);
			$idperio = $perio_found->notice_id;
			$zone_article_form  = str_replace("!!f_perio_existing!!",htmlentities($perio_found->tit1,ENT_QUOTES,$charset),$zone_article_form );
			$zone_article_form  = str_replace("!!f_perio_existing_id!!",$perio_found->notice_id,$zone_article_form );
			$zone_article_form  = str_replace("!!perio_type_new!!","",$zone_article_form );
			$zone_article_form  = str_replace("!!perio_type_use_existing!!","checked",$zone_article_form );
		} else {
			$idperio = 0;
			$zone_article_form  = str_replace("!!f_perio_existing!!","",$zone_article_form );
			$zone_article_form  = str_replace("!!f_perio_existing_id!!","",$zone_article_form );
			$zone_article_form  = str_replace("!!perio_type_new!!","checked",$zone_article_form );
			$zone_article_form  = str_replace("!!perio_type_use_existing!!","",$zone_article_form );
		}
		
		//On cherche si le bulletin existe
		if($this->bull_num[0] && $idperio){
			$req="select bulletin_id, bulletin_numero from bulletins where bulletin_notice='".$idperio."' and  bulletin_numero like'".addslashes($this->bull_num[0])."%' limit 1";
			$res_bull = mysql_query($req,$dbh);
			$num_rows_bull = mysql_num_rows($res_bull);
		}
		if(!$num_rows_bull){
			if($this->bull_date[0] && $idperio){
				$req="select bulletin_id, bulletin_numero from bulletins where bulletin_notice='".$idperio."' and date_date='".addslashes($this->bull_date[0])."' limit 1";
				$res_bull = mysql_query($req,$dbh);
				$num_rows_bull = mysql_num_rows($res_bull);
			}
		}
		if(!$num_rows_bull){
			if($this->bull_mention[0] && $idperio ){
				$req="select bulletin_id, bulletin_numero from bulletins where bulletin_notice='".$idperio."' and mention_date='".addslashes($this->bull_mention[0])."' limit 1";
				$res_bull = mysql_query($req,$dbh);
				$num_rows_bull = mysql_num_rows($res_bull);
			} 
		}				
		if ($num_rows_bull == 1) {
			$bull_found = mysql_fetch_object($res_bull);
			$zone_article_form  = str_replace("!!f_bull_existing!!",htmlentities($bull_found->bulletin_numero,ENT_QUOTES,$charset),$zone_article_form );
			$zone_article_form  = str_replace("!!f_bull_existing_id!!",$bull_found->bulletin_id,$zone_article_form );
			$zone_article_form  = str_replace("!!bull_type_new!!","",$zone_article_form );
			$zone_article_form  = str_replace("!!bull_type_use_existing!!","checked",$zone_article_form );
		} else {
			$zone_article_form  = str_replace("!!f_bull_existing!!","",$zone_article_form );
			$zone_article_form  = str_replace("!!f_bull_existing_id!!","",$zone_article_form );
			$zone_article_form  = str_replace("!!bull_type_new!!","checked",$zone_article_form );
			$zone_article_form  = str_replace("!!bull_type_use_existing!!","",$zone_article_form );
		}				
		$form_notice = str_replace("!!zone_article!!",$zone_article_form,$form_notice);
		if($article) 
			$form_notice = str_replace("!!display_zone_article!!","",$form_notice);
		else $form_notice = str_replace("!!display_zone_article!!","none",$form_notice);
		
		if($item){
			$form_notice = str_replace('!!notice_entrepot!!', "<input type='hidden' name='item' value='$item' />", $form_notice);
		} else $form_notice = str_replace('!!notice_entrepot!!', "", $form_notice);
		
		$form_notice = str_replace('!!orinot_nom!!', $this->origine_notice[nom], $form_notice);
		$form_notice = str_replace('!!orinot_pays!!', $this->origine_notice[pays], $form_notice);
		//Traitement du 503 "titre de forme" pour le Musée des beaux arts de Nantes 
		global $tableau_503;
		$tableau_503 = array( 	"info_503" => $this->info_503,
								"info_503_d" => $this->info_503_d,
								"info_503_j" => $this->info_503_j);
					
		// traitement des catégories : affichage dans le formulaire
		
		
		$tableau_600 = array(
								"info_600_3" => $this->info_600_3,
								"info_600_a" => $this->info_600_a,					
								"info_600_b" => $this->info_600_b,								
								"info_600_c" => $this->info_600_c,								
								"info_600_d" => $this->info_600_d,								
								"info_600_f" => $this->info_600_f,
								"info_600_g" => $this->info_600_g,
								"info_600_j" => $this->info_600_j,
								"info_600_p" => $this->info_600_p,
								"info_600_t" => $this->info_600_t,
								"info_600_x" => $this->info_600_x,
								"info_600_y" => $this->info_600_y,
								"info_600_z" => $this->info_600_z);
		$tableau_601 = array(
								"info_601_3" => $this->info_601_3,
								"info_601_a" => $this->info_601_a,				
								"info_601_b" => $this->info_601_b,								
								"info_601_c" => $this->info_601_c,								
								"info_601_d" => $this->info_601_d,
								"info_601_e" => $this->info_601_e,								
								"info_601_f" => $this->info_601_f,
								"info_601_g" => $this->info_601_g,
								"info_601_h" => $this->info_601_h,
								"info_601_j" => $this->info_601_j,
								"info_601_t" => $this->info_601_t,
								"info_601_x" => $this->info_601_x,
								"info_601_y" => $this->info_601_y,
								"info_601_z" => $this->info_601_z);
		$tableau_602 = array(
								"info_602_3" => $this->info_602_3,
								"info_602_a" => $this->info_602_a,
								"info_602_f" => $this->info_602_f,
								"info_602_j" => $this->info_602_j,			
								"info_602_t" => $this->info_602_t,
								"info_602_x" => $this->info_602_x,
								"info_602_y" => $this->info_602_y,
								"info_602_z" => $this->info_602_z);
		$tableau_604 = array(
								"info_604_3" => $this->info_604_3,
								"info_604_a" => $this->info_604_a,			
								"info_604_h" => $this->info_604_h,
								"info_604_i" => $this->info_604_i,	
								"info_604_j" => $this->info_604_j,
								"info_604_k" => $this->info_604_k,
								"info_604_l" => $this->info_604_l,
								"info_604_x" => $this->info_604_x,
								"info_604_y" => $this->info_604_y,
								"info_604_z" => $this->info_604_z);
		$tableau_605 = array(
								"info_605_3" => $this->info_605_3,
								"info_605_a" => $this->info_605_a,
								"info_605_h" => $this->info_605_h,
								"info_605_i" => $this->info_605_i,	
								"info_605_k" => $this->info_605_k,
								"info_605_l" => $this->info_605_l,		
								"info_605_m" => $this->info_605_m,
								"info_605_n" => $this->info_605_n,
								"info_605_q" => $this->info_605_q,
								"info_605_r" => $this->info_605_r,
								"info_605_s" => $this->info_605_s,
								"info_605_u" => $this->info_605_u,
								"info_605_w" => $this->info_605_w,	
								"info_605_j" => $this->info_605_j,
								"info_605_x" => $this->info_605_x,
								"info_605_y" => $this->info_605_y,
								"info_605_z" => $this->info_605_z);
		$tableau_606 = array(
								"info_606_3" => $this->info_606_3,
								"info_606_a" => $this->info_606_a,
								"info_606_j" => $this->info_606_j,
								"info_606_x" => $this->info_606_x,
								"info_606_y" => $this->info_606_y,
								"info_606_z" => $this->info_606_z);
		$tableau_607 = array(
								"info_607_3" => $this->info_607_3,
								"info_607_a" => $this->info_607_a,
								"info_607_j" => $this->info_607_j,
								"info_607_x" => $this->info_607_x,
								"info_607_y" => $this->info_607_y,
								"info_607_z" => $this->info_607_z);			
		$tableau_608 = array(
								"info_608_3" => $this->info_608_3,
								"info_608_a" => $this->info_608_a,
								"info_608_j" => $this->info_608_j,
								"info_608_x" => $this->info_608_x,
								"info_608_y" => $this->info_608_y,
								"info_608_z" => $this->info_608_z);
			
		
		// catégories
		$max_categ = 1;
		$ptab_categ = str_replace('!!icateg!!', 0, $ptab[60]) ;
		$ptab_categ = str_replace('!!categ_id!!', 0, $ptab_categ);
		$ptab_categ = str_replace('!!categ_libelle!!', '', $ptab_categ);		
		$ptab[6] = str_replace("!!categories_repetables!!", $ptab_categ, $ptab[6]);
		
		$traitement_rameau=traite_categories_for_form($tableau_600,$tableau_601,$tableau_602,$tableau_605,$tableau_606,$tableau_607,$tableau_608);
		if (!is_array($traitement_rameau)) {
			$traitement_rameau = array("form" => $traitement_rameau, "message" => "");
		}
		$form_notice = str_replace('!!message_rameau!!', $traitement_rameau["message"], $form_notice);
		$form_notice = str_replace('!!traitement_rameau!!', $traitement_rameau["form"], $form_notice);

		$manual_categorisation_form = get_manual_categorisation_form($tableau_600,$tableau_601,$tableau_602,$tableau_604,$tableau_605,$tableau_606,$tableau_607,$tableau_608);
		$form_notice = str_replace('!!manual_categorisation!!', $manual_categorisation_form, $form_notice);

		//Mise à jour de l'onglet 9
		$p_perso=new parametres_perso("notices");
		if(function_exists("param_perso_form")) {
			param_perso_form($p_perso);			
		}
		
		//pour Pubmed et DOI, on regarde si on peut remplier un champ résolveur...
		if(count($this->others_ids)>0){
			foreach($p_perso->t_fields as $key => $t_field){
				if($t_field['TYPE']  =="resolve"){
					$field_options = _parser_text_no_function_("<?xml version='1.0' encoding='".$charset."'?>\n".$t_field['OPTIONS'], "OPTIONS");
					foreach($field_options['RESOLVE'] as $resolve){
						//pubmed = 1 | DOI = 2
						foreach($this->others_ids as $other_id){
							if($other_id['b'] == "PMID" && $resolve['ID']=="1"){
								//on a le champ perso résolveur PubMed
								$p_perso->values[$key][]=$other_id['a']."|1";
							}else if($other_id['b'] == "DOI" && $resolve['ID']=="2"){
								//on a le champ perso résolveur DOI
								$p_perso->values[$key][]=$other_id['a']."|2";
							}
						}
					}
				}
			}
		}
		
		if (!$p_perso->no_special_fields) {
			$perso_=$p_perso->show_editable_fields($id_notice,true);
			$perso="";
			for ($i=0; $i<count($perso_["FIELDS"]); $i++) {
				$p=$perso_["FIELDS"][$i];
				$perso.="<div class='row'>
					<label for='".$p["NAME"]."' class='etiquette'>".$p["TITRE"]."</label>
					</div>
					<div class='row'>
					".$p["AFF"]."
					</div>
					";
			}
			$perso.=$perso_["CHECK_SCRIPTS"];
			$ptab[9]=str_replace("!!champs_perso!!",$perso,$ptab[9]);
		} else 
			$ptab[9]="\n<script>function check_form() { return true; }</script>\n";
		$form_notice = str_replace('!!tab9!!', $ptab[9], $form_notice);

		// champs de gestion
		global $deflt_notice_statut;
		if ($id_notice) {
			$rqt_statut="select statut from notices where notice_id='$id_notice' ";
			$res_statut=mysql_query($rqt_statut);
			$stat = mysql_fetch_object($res_statut) ;
			$select_statut = gen_liste_multiple ("select id_notice_statut, gestion_libelle from notice_statut order by 2", "id_notice_statut", "gestion_libelle", "id_notice_statut", "form_notice_statut", "", $stat->statut, "", "","","",0) ;
			} else $select_statut = gen_liste_multiple ("select id_notice_statut, gestion_libelle from notice_statut order by 2", "id_notice_statut", "gestion_libelle", "id_notice_statut", "form_notice_statut", "", $deflt_notice_statut, "", "","","",0) ;
		$ptab[10] = str_replace('!!notice_statut!!', $select_statut, $ptab[10]);
		$ptab[10] = str_replace('!!commentaire_gestion!!',htmlentities($this->commentaire_gestion,ENT_QUOTES, $charset), $ptab[10]);
		$ptab[10] = str_replace('!!thumbnail_url!!',htmlentities($this->thumbnail_url,ENT_QUOTES, $charset), $ptab[10]);
		$form_notice = str_replace('!!tab10!!', $ptab[10], $form_notice);
		
		// Documents Numériques
		$docnum_infos = "";
		$count = 0;
		$upload_doc_num="";
		if($this->source_id){
			$requete="select * from connectors_sources where source_id=".$this->source_id."";
			$resultat=mysql_query($requete);
			if (mysql_num_rows($resultat)) {
				$r=mysql_fetch_object($resultat);
				if(!$r->upload_doc_num) $upload_doc_num = "checked";
			}	
		}		
		foreach ($this->doc_nums as $doc_num) {
			$docnum_info = $ptab[1111];
//			$alink = '<a target="_blank" href="'.htmlspecialchars($doc_num["a"]).'">'.htmlspecialchars($doc_num["a"]).'</a>';
			$docnum_info = str_replace('!!docnum_url!!', htmlspecialchars($doc_num["a"]), $docnum_info);
			$docnum_info = str_replace('!!docnum_caption!!', htmlspecialchars($doc_num["b"]), $docnum_info);
			$docnum_info = str_replace('!!docnum_filename!!', htmlspecialchars($doc_num["f"]), $docnum_info);
			$docnum_info = str_replace('!!docnumid!!', $count, $docnum_info);
			$docnum_info = str_replace('!!upload_doc_num!!', $upload_doc_num, $docnum_info);
			$docnum_infos .= $docnum_info;
			$count++;
		}
		if (!$docnum_infos)
			$docnum_infos = $msg["noticeintegre_nodocnum"];
		$ptab[1110] = str_replace('!!docnum_count!!', $count, $ptab[1110]);
		$ptab[1110] = str_replace('!!docnums!!', $docnum_infos, $ptab[1110]);
		$form_notice = str_replace('!!tab11!!', $ptab[1110], $form_notice);	
		
		
		// maxman: le bouton "retour resultats de la recherche" devient en liens 
		$aac=explode('&',$action);
		$retact='&'.$aac[2].'&'.$aac[5].'&'.$aac[6];
		global $force;
		if (!$retour)
			$retares="<a href='./catalog.php?categ=z3950&action=display".$retact."'>".$msg[z3950_retour_a_resultats]."</a>";
		else {
			if($this->message_retour)
				$retares="<input type='button' class='bouton' onclick='history.go(-1);' value='".$this->message_retour."' />";
			else{
				if($force == 1 ){
					$retares="<a href='javascript:history.go(-2);'>".$msg[z3950_retour_a_resultats]."</a>";	
				}else{
					$retares="<a href='javascript:history.go(-1);'>".$msg[z3950_retour_a_resultats]."</a>";	
				}
			} 
		}
		$form_notice = str_replace('!!retour_a_resultats!!', $retares, $form_notice);
		
		if (!$id_notice) $form_notice = str_replace('!!bouton_integration!!', $msg[z3950_integr_not_seule], $form_notice);
			else $form_notice = str_replace('!!bouton_integration!!', $msg[notice_z3950_remplace_catal], $form_notice);
		$form_notice = str_replace('!!id_notice!!', $id_notice, $form_notice);
		$form_notice = str_replace('!!notice!!',$znotices_id,$form_notice);
		$form_notice = str_replace('!!notice_type!!',$this->notice_type,$form_notice);
		
		return $form_notice;
	}

	// Traitement retour du formulaire
	function from_form () {
		global $typdoc, $b_level, $h_level, $f_title_0, $f_title_1, $f_title_2, $f_title_3, $f_serie, $f_nbr_in_serie ;
		global $f_editor_name_0, $f_editor_ville_0, $f_editor_name_1, $f_editor_ville_1, $f_collection_name, $f_collection_issn, $f_subcollection_name, $f_subcollection_issn,
			$f_nbr_in_collection, $f_year, $f_mention_edition, $f_cb, $f_page_nbr, $f_illustration, $f_size, $f_prix, $f_accompagnement,
			$f_general_note, $f_content_note, $f_abstract_note, 
			$f_indexint, $f_indexint_id, $f_free_index,
			$f_language_code, $f_original_language_code,
			$f_link_url, $f_link_format,
			$f_orinot_nom, $f_orinot_pays,
			$form_notice_statut, $f_commentaire_gestion, $f_thumbnail_url ;
		global $categ_pas_trouvee, $pmb_keyword_sep, $categorisation_type; 

		global $pmb_use_uniform_title;
		if ($pmb_use_uniform_title) {
			global $max_titre_uniforme;
			if ($max_titre_uniforme) {
				global $memo_value_tu;
				$value_tu=unserialize(rawurldecode($memo_value_tu));
				// Titres uniformes
				for ($i=0; $i<$max_titre_uniforme ; $i++) {
					$var_tu_id = "f_titre_uniforme_code$i" ;
					$var_tu_titre = "f_titre_uniforme$i" ;
					
					$var_ntu_titre = "ntu_titre$i" ;
					$var_ntu_date = "ntu_date$i" ;
					$var_ntu_sous_vedette = "ntu_sous_vedette$i" ;
					$var_ntu_langue = "ntu_langue$i" ;
					$var_ntu_version = "ntu_version$i" ;
					$var_ntu_mention = "ntu_mention$i" ;
					global $$var_tu_id,$$var_tu_titre,$$var_ntu_titre,$$var_ntu_date,$$var_ntu_sous_vedette,$$var_ntu_langue,$$var_ntu_version,$$var_ntu_mention;
					
					if($$var_tu_titre) {
						// on crée un nouveau titre uniforme si un id et un titre différent, ou si pas d'id
						if($$var_tu_id && ($$var_tu_titre != $value_tu[$i]["name"] ) || (!$$var_tu_id)) {
							$value_tu[$i]["name"]=$$var_tu_titre;
							$tu_id=titre_uniforme::import($value_tu[$i],1);
						} else {
							$tu_id=$$var_tu_id;
						}
						// il est soit existant, soit créé
						if($tu_id) {
							$this->titres_uniformes[] = array (
								'tu_titre' => $$var_tu_titre,
								'num_tu' => $tu_id,
								'ntu_titre' => $$var_ntu_titre,
								'ntu_date' => $$var_ntu_date,
								'ntu_sous_vedette' => $$var_ntu_sous_vedette,
								'ntu_langue' => $$var_ntu_langue,
								'ntu_version' => $$var_ntu_version,
								'ntu_mention' => $$var_ntu_mention )
							;
						}	
					}				
				}
			}
		}		

		$this->document_type = clean_string ($typdoc);

		$this->bibliographic_level = clean_string ($b_level);
		$this->hierarchic_level = clean_string ($h_level);

		$this->titles[0] = clean_string ($f_title_0);
		$this->titles[1] = clean_string ($f_title_1);
		$this->titles[2] = clean_string ($f_title_2);
		$this->titles[3] = clean_string ($f_title_3);
		$this->serie = clean_string ($f_serie);
		$this->nbr_in_serie = clean_string ($f_nbr_in_serie);

		$this->aut_array = array () ;
		global $max_aut1, $max_aut2 ;
		
		// auteur principal
		global $author0_type;
		if ($author0_type == "use_existing") {
			global $f_existing_f0_code, $f_aut0_existing_id;
			if ($f_aut0_existing_id)
				$this->aut_array[] = array(
					'fonction' => $f_existing_f0_code,
					'id' => $f_aut0_existing_id,
					'responsabilite' => 0 );
		} else {
			global $f_author_name_0, $f_author_rejete_0, $f_author_date_0, $f_author_type_0, $f_author_function_0, $f_author_lieu_0, $f_author_pays_0  ;
			$this->aut_array[] = array(
				'entree' => stripslashes($f_author_name_0),
				'rejete' => stripslashes($f_author_rejete_0),
				'date' => stripslashes($f_author_date_0),
				'type_auteur' => $f_author_type_0,
				'fonction' => $f_author_function_0,
				'id' => 0,
				'responsabilite' => 0,
				'lieu' =>  $f_author_lieu_0,
				'pays' => $f_author_pays_0 );			
		}

		// autres auteurs
		for ($i=0; $i< $max_aut1 ; $i++) {
			$var_aut_name = "f_author_name_1$i" ;
			$var_aut_rejete = "f_author_rejete_1$i" ;
			$var_aut_date = "f_author_date_1$i" ;
			$var_aut_type_auteur = "f_author_type_1$i" ;
			$var_aut_function = "f_author_function_1$i" ;
			$var_auth_type_use = "author1_type_$i";
			$var_aut_pays = "f_author_lieu_1$i" ;
			$var_aut_lieu = "f_author_pays_1$i" ;
			
			global $$var_aut_name, $$var_aut_rejete, $$var_aut_date, $$var_aut_type_auteur, $$var_aut_function, $$var_auth_type_use, $$var_aut_lieu, $$var_aut_pays;
			if ($$var_auth_type_use == "use_existing") {
				$a_id = "f_aut1_id$i";
				$a_code = "f_f1_code$i";
				global $$a_code, $$a_id;
				if ($$a_id)
					$this->aut_array[] = array(
						'fonction' => $$a_code,
						'id' => $$a_id,
						'responsabilite' => 1 );
			} else if ($$var_aut_name) 
				$this->aut_array[] = array(
					'entree' => stripslashes($$var_aut_name),
					'rejete' => stripslashes($$var_aut_rejete),
					'date' => stripslashes($$var_aut_date),
					'type_auteur' => $$var_aut_type_auteur,
					'fonction' => $$var_aut_function,
					'id' => 0,
					'responsabilite' => 1,
					'lieu' => $$var_aut_lieu,
					'pays' => $$var_aut_pays );
			}
		// auteurs secondaires
		for ($i=0; $i< $max_aut2 ; $i++) {
			$var_aut_name = "f_author_name_2$i" ;
			$var_aut_rejete = "f_author_rejete_2$i" ;
			$var_aut_date = "f_author_date_2$i" ;
			$var_aut_type_auteur = "f_author_type_2$i" ;
			$var_aut_function = "f_author_function_2$i" ;
			$var_auth_type_use = "author2_type_$i";
			$var_aut_pays = "f_author_lieu_2$i" ;
			$var_aut_lieu = "f_author_pays_2$i" ;
			global $$var_aut_name, $$var_aut_rejete, $$var_aut_date, $$var_aut_type_auteur, $$var_aut_function, $$var_auth_type_use, $$var_aut_lieu, $$var_aut_pays;

			if ($$var_auth_type_use == "use_existing") {
				$a_id = "f_aut2_id$i";
				$a_code = "f_f2_code$i";
				global $$a_code, $$a_id;
				if ($$a_id)
					$this->aut_array[] = array(
						'fonction' => $$a_code,
						'id' => $$a_id,
						'responsabilite' => 2);
			}
			else if ($$var_aut_name) 
				$this->aut_array[] = array(
					'entree' => stripslashes($$var_aut_name),
					'rejete' => stripslashes($$var_aut_rejete),
					'date' => stripslashes($$var_aut_date),
					'type_auteur' => $$var_aut_type_auteur,
					'fonction' => $$var_aut_function,
					'id' => 0,
					'responsabilite' => 2,
					'lieu' => $$var_aut_lieu,
					'pays' => $$var_aut_pays);
			}

		global $editor_type, $f_ed1_id;
		if ($editor_type == "use_existing") {
			if ($f_ed1_id) {
				$this->editors[0]['id'] = $f_ed1_id;
			}
		}
		else {
			$this->editors[0]['name'] = clean_string (stripslashes($f_editor_name_0));
			$this->editors[0]['ville'] = clean_string (stripslashes($f_editor_ville_0));
			$this->editors[0]['id'] = 0;			
		}

		global $editor1_type, $f_ed11_id;
		if ($editor1_type == "use_existing") {
			if ($f_ed11_id) {
				$this->editors[1]['id'] = $f_ed11_id;
			}
		}
		else {
			$this->editors[1]['name'] = clean_string (stripslashes($f_editor_name_1));
			$this->editors[1]['ville'] = clean_string (stripslashes($f_editor_ville_1));
			$this->editors[1]['id'] = 0;			
		}

		global $collection_type;
		if ($collection_type == "use_existing") {
			global $f_coll_existing_id;
			$this->collection['id'] = $f_coll_existing_id;			
		}
		else {
			$this->collection['name'] = clean_string (stripslashes($f_collection_name));
			$this->collection['issn'] = clean_string (stripslashes($f_collection_issn));
			$this->collection['id'] = 0;			
		}
		
		global $subcollection_type;
		if ($subcollection_type == "use_existing") {
			global $f_subcoll_existing_id;
			$this->subcollection['id'] = $f_subcoll_existing_id;			
		}
		else {
			$this->subcollection['name'] = clean_string (stripslashes($f_subcollection_name));
			$this->subcollection['issn'] = clean_string (stripslashes($f_subcollection_issn));
			$this->subcollection['id'] = 0;			
		}		
		
		$this->nbr_in_collection = clean_string ($f_nbr_in_collection);

		$this->year = clean_string ($f_year);
		$this->mention_edition = clean_string ($f_mention_edition);

		$this->isbn = clean_string ($f_cb);

		$this->page_nbr = clean_string ($f_page_nbr);
		$this->illustration = clean_string ($f_illustration);
		$this->size = clean_string ($f_size);
		$this->prix = clean_string ($f_prix);
		$this->accompagnement = clean_string ($f_accompagnement);

		$this->general_note = $f_general_note;
		$this->content_note = $f_content_note;
		$this->abstract_note = $f_abstract_note;

		// catégories
		if ($categorisation_type == "categorisation_auto") {
			$this->categories = traite_categories_from_form();
			$this->categorisation_type = "categorisation_auto";
		}
		else {
			global $max_categ;
			$categories = array();
			for($i=0, $count=$max_categ; $i<$count; $i++) {
				$a_categ_id_name = "f_categ_id".$i;
				global $$a_categ_id_name;
				
				if (!$$a_categ_id_name)
					continue;
				$acategory = array("categ_id" => $$a_categ_id_name);
				
				$categories[] = $acategory;
			}
			$this->categories = $categories;
			$this->categorisation_type = "categorisation_manual";
		}
		
		global $indexint_type;
		if ($indexint_type == "use_existing") {
			$this->dewey[0] = clean_string (stripslashes($f_indexint));
			$this->internal_index = $f_indexint_id ;			
		}
		else {
			global $f_indexint_new, $f_indexint_new_comment, $f_indexint_new_pclass;
			$this->dewey[0] = clean_string (stripslashes($f_indexint_new));
			$this->dewey["new_comment"] = clean_string (stripslashes($f_indexint_new_comment));
			$this->dewey["new_pclass"] = clean_string (stripslashes($f_indexint_new_pclass));
			$this->internal_index = 0;			
		}
		
		// $categ_pas_trouvee ; est un tableau de catégories pas trouvées, 
		//		mots clés pas présents dans $this->categories mais à conserver en zone libre

		if (!isset($categ_pas_trouvee)) $categ_pas_trouvee=array();
		for ($i=0;$i<count($categ_pas_trouvee);$i++) 
			if (trim($categ_pas_trouvee[$i])) 
				$categ_pas_trouvee_pasvide[]=addslashes(trim($categ_pas_trouvee[$i]));

		if ($f_free_index) $categ_pas_trouvee_pasvide[]=$f_free_index;
		
		if(is_array($categ_pas_trouvee_pasvide) && count($categ_pas_trouvee_pasvide)){
			$f_free_index=implode($pmb_keyword_sep, $categ_pas_trouvee_pasvide);
		}
		$this->free_index = clean_string ($f_free_index);

		$this->language_code = clean_string ($f_language_code);
		$this->original_language_code = clean_string ($f_original_language_code);
		
		$this->link_url = clean_string ($f_link_url);
		$this->link_format = clean_string ($f_link_format);

		$this->origine_notice[nom] = clean_string ($f_orinot_nom);
		$this->origine_notice[pays] = clean_string ($f_orinot_pays);

		$this->statut = $form_notice_statut ;
		$this->commentaire_gestion  = $f_commentaire_gestion ;
		$this->thumbnail_url		= $f_thumbnail_url;
		
		//Document Numérique
		global $doc_num_count;
		for ($i=0; $i<$doc_num_count; $i++) {
			$include_cb_name = "include_doc_num".$i;
			global $$include_cb_name;
			if ($$include_cb_name) {
				$docnum_filename_name = "doc_num_filename".$i;
				global $$docnum_filename_name;
				$docnum_caption_name = "doc_num_caption".$i;
				global $$docnum_caption_name;
				$docnum_url_name = "doc_num_url".$i;
				global $$docnum_url_name;
				$docnum_nodownload_name = "doc_num_nodownload".$i;
				global $$docnum_nodownload_name;
				if ($$docnum_nodownload_name)
					$nodownload = 1;
				else
					$nodownload = 0;
									
				$this->doc_nums[] = array("a" => $$docnum_url_name, "b" => $$docnum_caption_name, "f" => $$docnum_filename_name, "__nodownload__" => $nodownload);
			}
		}
		
		global $perio_type,$bull_type;
		//Périos
		if($perio_type == 'use_existing'){
			global $f_perio_existing_id;
			$this->perio_id = $f_perio_existing_id;			
		} else {
			global $f_perio_new, $f_perio_new_issn;
			$this->perio_titre = clean_string ($f_perio_new);
			$this->perio_issn = clean_string ($f_perio_new_issn);
		}
		
		//Bulletins
		if($bull_type == 'use_existing'){
			global $f_bull_existing_id;
			$this->bull_id = $f_bull_existing_id;			
		} else {
			global $f_bull_new_num, $f_bull_new_titre, $f_bull_new_date, $f_bull_new_mention;
			$this->bull_num = clean_string ($f_bull_new_num);
			$this->bull_titre = clean_string ($f_bull_new_titre);
			$this->bull_date = clean_string ($f_bull_new_date);
			$this->bull_mention = clean_string ($f_bull_new_mention);
		}
	}

	function process_isbn ($isbn) {
		/* We've got everything, let's have a look if ISBN already exists in notices table */
		$isbn_nettoye = preg_replace('/-|\.| |\(|\)|\[|\]|\:|\;|[A-WY-Z]/i', '', $isbn);
		$isbn_nettoye_13 = substr($isbn_nettoye,0,13);
		$isbn_nettoye_10 = substr($isbn_nettoye,0,10);
		$isbn_OK = "";
		if(isEAN($isbn_nettoye_13)) { /* it's an EAN -> convert it to ISBN */
			$isbn_OK = EANtoISBN($isbn_nettoye_13);
		} 
		if (!$isbn_OK) {
			if (isISBN($isbn_nettoye_10)) {
				$isbn_OK = formatISBN($isbn_nettoye_10);
			}
		}
		if (!$isbn_OK) $isbn_OK = clean_string($isbn);

		return $isbn_OK;
	}
		
	function process_author ($name, $rejete, $type, $date) {
		if (!$rejete) {
			$field = explode (',', $name, 2);
			$name = $field[0];
			$rejete = $field[1];
		}
		
		$name = $this->clean_field ($name);
		$rejete = $this->clean_field ($rejete);
			
		$author['name'] = $name;
		$author['rejete'] = $rejete;
		$author['type'] = $type;
		$author['date'] = $date;

		return $author;
	}
		
	function clean_field ($field) {
		return preg_replace('/-$| $|\($|\)$|\[$|\]$|\:$|\;$|\/$\|\$|([^ ].)\.$|,$/', '$1', trim ($field));
	}

	function from_usmarc ($record) {
		$this->document_type = $record->inner_guide[dt];
		$this->bibliographic_level = $record->inner_guide[bl];
		$this->hierarchic_level = $record->inner_guide[hl];

		if ($this->hierarchic_level=="") {
			if ($this->bibliographic_level=="s") $this->hierarchic_level="1";
			if ($this->bibliographic_level=="m") $this->hierarchic_level="0";
		}

		for ($i=0;$i<count($record->inner_directory);$i++) {
			$cle=$record->inner_directory[$i]['label'];
			switch($cle) {
				case "020": /* isbn */
					$isbn = $record->get_subfield($cle,'a');
					break;
				case "041": /* language */
					$this->language_code = $record->get_subfield_array($cle,"a");
					break;
				case "245": /* titles */
					$tit = $record->get_subfield($cle, "a", "b", "n", "p");
					break;
				case "246": /* titles */
					$tit_sup=$record->get_subfield($cle, "a");
					break;
				case "247": /* former title */
					$tit_for=$record->get_subfield($cle, "a");
					break;
				case "250": /* mention_edition */
					$subfield = $record->get_subfield($cle,"a");
					$this->mention_edition = $subfield[0];
					break;
				case "260": /* publisher */
					$editor = $record->get_subfield($cle,"a","b","c");
					break;
				case "300": /* description */
					$subfield = $record->get_subfield($cle,"a");
					$this->page_nbr = $this->clean_field ($subfield[0]);
					$subfield = $record->get_subfield($cle,"b");
					$this->illustration = $this->clean_field ($subfield[0]);
					$subfield = $record->get_subfield($cle,"c");
					$this->size = $this->clean_field ($subfield[0]);
					$subfield = $record->get_subfield($cle,"e");
					$this->accompagnement = $this->clean_field ($subfield[0]);
					break;
				case "022": /* collection */
					$collection_022=$record->get_subfield($cle,"a");
					break;
				case "222": /* collection */
					$collection_222=$record->get_subfield($cle,"a","v","x");
					break;
				case "440": /* collection */
					$collection_440=$record->get_subfield($cle,"a","v","x");
					break;
				case "500": /* inside */
					$general_note = $record->get_subfield($cle,"a");
					break;
				case "502": /* abstract */
					$content_note = $record->get_subfield($cle,"a");
					break;
				case "520": /* abstract */
					$abstract_note = $record->get_subfield($cle,"a");
					break;
				case "082": /* Dewey */
					$this->dewey=$record->get_subfield($cle,"a");
					break;
				case "100":
					$aut_100=$record->get_subfield($cle,"a","d","e","4");
					break;
				case "110":
					$aut_110=$record->get_subfield($cle,"a","d","e","4");
					break;
				case "111":
					$aut_111=$record->get_subfield($cle,"a","d","e","4");
					break;
				case "700":
					$aut_700=$record->get_subfield($cle,"a","d","e","4");
					break;
				case "710":
					$aut_710=$record->get_subfield($cle,"a","d","e","4");
					break;
				case "711":
					$aut_711=$record->get_subfield($cle,"a","d","e","4");
					break;
				case "856":
					$subfield = $record->get_subfield($cle,"u","q");
					$this->ressource = $subfield[0];
					break;
				case "650":
					$this->info_606_a=$record->get_subfield_array_array($cle,"a");
					$this->info_606_3=$record->get_subfield_array_array($cle,"3");
					$this->info_606_j=$record->get_subfield_array_array($cle,"j");
					$this->info_606_x=$record->get_subfield_array_array($cle,"x");
					$this->info_606_y=$record->get_subfield_array_array($cle,"y");
					$this->info_606_z=$record->get_subfield_array_array($cle,"z");
					break;
				case "653":
					$index_sujets=$record->get_subfield($cle,"a");
					break;
				default:
					break;

			} /* end of switch */

		} /* end of for */

		$this->isbn = $this->process_isbn ($isbn[0]);

		/* INSERT de la notice OK, on va traiter les auteurs
		10# : personnal : type auteur 70                71# : collectivités : type auteur 71
		1 seul en 700                                   idem pour les déclinaisons          
		n en 701 n en 702
		les 7#0 tombent en auteur principal : responsability_type = 0
		les 7#1 tombent en autre auteur : responsability_type = 1
		les 7#2 tombent en auteur secondaire : responsability_type = 2
		*/
		$this->aut_array = array();
		/* on compte tout de suite le nbre d'enreg dans les répétables */
		$nb_repet_700=sizeof($aut_700);
		$nb_repet_710=sizeof($aut_710);
		$nb_repet_711=sizeof($aut_711);

		/* renseignement de aut0 */
		if ($aut_100[0][a]!="") { /* auteur principal en 100 ? */
				
			$author=$this->process_author($aut_100[0]['a'],$aut_100[0]['b'],'','');
			
			$this->aut_array[] = array(
				"entree" => $author['name'], //$aut_100[0]['a'],
				"rejete" => $author['rejete'], //$aut_100[0]['b'],
				"type_auteur" => "70",
				"fonction" => convert_usmarc_unimarc_functions($aut_100[0][4]),
				"id" => 0,
				"responsabilite" => 0 ) ;
			} elseif ($aut_110[0]['a']!="") { /* auteur principal en 110 ? */
				$author=$this->process_author($aut_110[0]['a'],$aut_110[0]['b'],'','');
				$this->aut_array[] = array(
					"entree" => $author['name'], //$aut_110[0]['a'],
					"rejete" => $author['rejete'], //$aut_110[0]['b'],
					"type_auteur" => "71",
					"fonction" => convert_usmarc_unimarc_functions($aut_110[0][4]),
					"id" => 0,
					"responsabilite" => 0 ) ;
				} elseif ($aut_111[0]['a']!="") { /* auteur principal en 111 ? */
					$author=$this->process_author($aut_111[0]['a'],$aut_111[0]['b'],'','');
					$this->aut_array[] = array(
						"entree" => $author['name'], //$aut_111[0]['a'],
						"rejete" => $author['rejete'], //$aut_111[0]['b'],
						"type_auteur" => "71",
						"fonction" => convert_usmarc_unimarc_functions($aut_111[0][4]),
						"id" => 0,
						"responsabilite" => 0 ) ;
					} 
	
		/* renseignement de aut1 */
		for ($i=0 ; $i < $nb_repet_700 ; $i++) {
			$author=$this->process_author($aut_700[$i]['a'],$aut_700[$i]['b'],'','');
			$this->aut_array[] = array(
				"entree" => $author['name'], //$aut_700[$i]['a'],
				"rejete" => $author['rejete'], //$aut_700[$i]['b'],
				"type_auteur" => "70",
				"fonction" => convert_usmarc_unimarc_functions($aut_700[$i][4]),
				"id" => 0,
				"responsabilite" => 1 ) ;
			}
		for ($i=0 ; $i < $nb_repet_710 ; $i++) {
			$author=$this->process_author($aut_710[$i]['a'],$aut_710[$i]['b'],'','');
			$this->aut_array[] = array(
				"entree" => $author['name'], //$aut_710[$i]['a'],
				"rejete" => $author['rejete'], //$aut_710[$i]['b'],
				"type_auteur" => "71",
				"fonction" => convert_usmarc_unimarc_functions($aut_710[$i][4]),
				"id" => 0,
				"responsabilite" => 1 ) ;
			}
		/* renseignement de aut2 */
		for ($i=0 ; $i < $nb_repet_711 ; $i++) {
			$author=$this->process_author($aut_711[$i]['a'],$aut_711[$i]['b'],'','');
			$this->aut_array[] = array(
				"entree" => $author['name'], //$aut_711[$i]['a'],
				"rejete" => $author['rejete'], //$aut_711[$i]['b'],
				"type_auteur" => "71",
				"fonction" => convert_usmarc_unimarc_functions($aut_711[$i][4]),
				"id" => 0,
				"responsabilite" => 2 ) ;
			}
		
		/* Editors */
		$this->year = preg_replace ("/[^0-9\[\]()]/", "", $editor[0][c]);

		$this->editors[0]['name'] = $this->clean_field ($editor[0][b]);
		$this->editors[0]['ville'] = $this->clean_field ($editor[0][a]);

		$this->editors[1]['name'] = $this->clean_field ($editor[1][b]);
		$this->editors[1]['ville'] = $this->clean_field ($editor[1][a]);
		
		/* ici traitement des collections */
		$coll_name = "";
		$subcoll_name = "";
		$coll_issn = "";
		$subcoll_issn = "";
		$nocoll = "";

		/* traitement de 222$a, si rien alors 440$a pour la collection */
		if ($collection_222[0][a]!="") {
			$coll_name = $this->clean_field($collection_222[0][a]);
			$coll_issn = $collection_022[0][a];
		} elseif ($collection_440[0][a]!="") {
			$coll_name = $this->clean_field($collection_440[0][a]);
			$coll_issn = $collection_440[0][x];
		}

		/* traitement de 222 1, si rien alors 440 1 pour la sous-collection */
		if ($collection_222[1][a]!="") {
			$subcoll_name = $this->clean_field($collection_222[1][a]);
			$subcoll_issn = $collection_022[1][a];
		} elseif ($collection_440[1][a]!="") {
			$subcoll_name = $this->clean_field($collection_440[1][a]);
			$subsubcoll_issn = $collection_440[1][x];
		}

		/* gaffe au nocoll, en principe en 440$v */
		if ($collection_440[0][v]!="") {
			$this->nbr_in_collection = $collection_440[0][v];
		} else 
		$this->nbr_in_collection = "";

		$this->collection['name']=clean_string($coll_name);
		$this->collection['issn']=clean_string($coll_issn);

		$this->subcollection['name']=clean_string($subcoll_name);
		$this->subcollection['issn']=clean_string($subcoll_issn);
				
		/* Series */
		$this->serie = clean_string ($tit[0][p]);
		$this->nbr_in_serie = clean_string ($tit[0][n]);
		
		/* traitement ressources */
		$this->link_url = $ressource[0]["u"];
		$this->link_format = $ressource[0]["2"];

		/* traitement des titres */
		$this->titles[0] = preg_replace('/-$| $|\||\($|\)$|\[$|\]$|\:$|\;$|\/$|\\$|\.+$/', '', trim($tit[0][a]));
		$this->titles[1] = preg_replace('/-$| $|\||\($|\)$|\[$|\]$|\:$|\;$|\/$\|\$|\.+$/', '', trim($tit_sup[0][a]));
		$this->titles[2] = preg_replace('/-$| $|\||\($|\)$|\[$|\]$|\:$|\;$|\/$\|\$|\.+$/', '', trim($tit_for[0][a]));
		$this->titles[3] = preg_replace('/-$| $|\||\($|\)$|\[$|\]$|\:$|\;$|\/$\|\$|\.+$/', '', trim($tit[0][b]));

		$this->general_note = "";
		$this->content_note = "";
		$this->abstract_note = "";

		/* prepare index_titre field for searching */				
		for ($i = 0; $i < count ($abstract_note); $i++) {
			$this->abstract_note .= $abstract_note[$i]." ";
		}
		for ($i = 0; $i < count($general_note); $i++) {
			$this->general_note .= $general_note[$i]." ";
		}
		for ($i = 0; $i < count ($content_note); $i++) {
			$this->content_note .= $content_note[$i]." ";
		}
		if (trim ($this->abstract_note) == "") 
			$this->abstract_note = $this->general_note." ".$this->content_note;

		$this->matieres_index = strip_empty_words ($this->content_note." ".$this->general_note." ".$this->abstract_note);
		
		global $pmb_keyword_sep ;
		if (!$pmb_keyword_sep) $pmb_keyword_sep=" ";
		if (is_array($index_sujets)) $this->free_index = implode ($pmb_keyword_sep,$index_sujets);
			else $this->free_index = $index_sujets;
	
	}

	function from_unimarc ($record) {
		$this->document_type = $record->inner_guide[dt];
		$this->bibliographic_level = $record->inner_guide[bl];
		$this->hierarchic_level = $record->inner_guide[hl];

		if ($this->hierarchic_level=="") {
			if ($this->bibliographic_level=="s") $this->hierarchic_level="1";
			if ($this->bibliographic_level=="m") $this->hierarchic_level="0";
		}
		if(function_exists("param_perso_prepare"))  param_perso_prepare($record);
		$indicateur = array();
		for ($i=0;$i<count($record->inner_directory);$i++) {
			$cle=$record->inner_directory[$i]['label'];
			$indicateur[$cle][]=substr($record->inner_data[$i]['content'],0,2);
			switch($cle) {
				case "010": /* isbn */
					$isbn = $record->get_subfield($cle,'a');
					$subfield = $record->get_subfield($cle,"d");
					$this->prix = $subfield[0];
					break;
				case "014": /* isbn */
					$this->others_ids = $record->get_subfield($cle,'a','b');
					break;
				case "071": /* barcode */
					$cb = $record->get_subfield($cle,"a");
					break;
				case "101": /* language */
					//martizva NOTE: some server send language in UPCASE!!! 
					// $subfield = $record->get_subfield($cle,"a");
					// $this->language_code = strtolower($subfield[0]);
					// $subfield = $record->get_subfield($cle,"c");
					// $this->original_language_code = strtolower($subfield[0]);
					$this->language_code = $record->get_subfield_array($cle,"a");
					$this->original_language_code = $record->get_subfield_array($cle,"c");
					break;
				case "200": /* titles */
					$tit = $record->get_subfield($cle, 'a', 'c', 'd', 'e', 'v');
					$tit_200a=$record->get_subfield_array($cle, 'a');
					$tit_200c=$record->get_subfield_array($cle, 'c');
					$tit_200d=$record->get_subfield_array($cle, 'd');
					$tit_200e=$record->get_subfield_array($cle, 'e');
					$tit_200v=$record->get_subfield_array($cle, 'v');
					break;
				case "205": /* mention_edition */
					$subfield = $record->get_subfield($cle,"a");
					$this->mention_edition = $subfield[0];
					break;
				case "210": /* publisher */
					$editor = $record->get_subfield($cle,"a","c","d");
					break;
				case "215": /* description */
					$subfield = $record->get_subfield($cle,"a");
					$this->page_nbr = $subfield[0];
					$subfield = $record->get_subfield($cle,"c");
					$this->illustration = $subfield[0];
					$subfield = $record->get_subfield($cle,"d");
					$this->size = $subfield[0];
					$subfield = $record->get_subfield($cle,"e");
					$this->accompagnement = $subfield[0];
					break;
				case "225": /* collection */
					$collection_225 = $record->get_subfield($cle,"a","i","v","x");
					break;
				case "300": /* inside */
					$general_note = $record->get_subfield($cle,"a");
					break;
				case "327": /* inside */
					$content_note=$record->get_subfield_array($cle,"a");
					break;
				case "330": /* abstract */
					$abstract_note = $record->get_subfield($cle,"a");
					break;
				case "345": /* EAN */
					$EAN=$record->get_subfield($cle,"b");
					break;
				case "410": /* collection */
					$collection_410 = $record->get_subfield($cle,"t","v","x");
					break;
				case "411": /* sub-collection */
					$collection_411 = $record->get_subfield($cle,"t","v","x");
					break;
				case "461": /* series ou perios*/
					if($this->bibliographic_level == 'a' && $this->hierarchic_level == '2'){
						$perio = $record->get_subfield($cle,"t","x","v","d","e");
						$this->perio_titre = $record->get_subfield_array($cle,"t");
						$this->perio_issn = $record->get_subfield_array($cle,"x");
						if($record->get_subfield_array($cle,"v")) $this->bull_num = $record->get_subfield_array($cle,"v");
						if($record->get_subfield_array($cle,"d")) $this->bull_date = $record->get_subfield_array($cle,"d");
						if($record->get_subfield_array($cle,"e")) $this->bull_mention = $record->get_subfield_array($cle,"e");
					} else $serie = $record->get_subfield($cle,"t","v");
					break;
				case "463":/* Bulletins */
					$bulletin_463 = $record->get_subfield($cle,"t","v","d","e");
					$this->bull_num = $record->get_subfield_array($cle,"v");
					$this->bull_date = $record->get_subfield_array($cle,"d");
					$this->bull_mention = $record->get_subfield_array($cle,"e");
					$this->bull_titre = $record->get_subfield_array($cle,"t");
					break;
				case "500": /* Titres uniformes */
					$this->tu_500 = $record->get_subfield($cle,"a","k","m","q","u","v","w");
					$this->tu_500_i = $record->get_subfield_array_array($cle,"i");
					$this->tu_500_j = $record->get_subfield_array_array($cle,"j");
					$this->tu_500_l = $record->get_subfield_array_array($cle,"l");	
					$this->tu_500_n = $record->get_subfield_array_array($cle,"n");									
					$this->tu_500_r = $record->get_subfield_array_array($cle,"r");
					$this->tu_500_s = $record->get_subfield_array_array($cle,"s");					
					break;
				case "503": /* Titres de forme */
					$this->info_503 = $record->get_subfield($cle,"a","e","f","h","m","n");
					$this->info_503_d = $record->get_subfield_array_array($cle,"d");
					$this->info_503_j = $record->get_subfield_array_array($cle,"j");				
					break;					
				case "600": // 600 PERSONAL NAME USED AS SUBJECT
					$this->info_600_3=$record->get_subfield_array_array($cle,"3");
					$this->info_600_a=$record->get_subfield_array_array($cle,"a");
					$this->info_600_b=$record->get_subfield_array_array($cle,"b");
					$this->info_600_c=$record->get_subfield_array_array($cle,"c");
					$this->info_600_d=$record->get_subfield_array_array($cle,"d");
					$this->info_600_f=$record->get_subfield_array_array($cle,"f");
					$this->info_600_g=$record->get_subfield_array_array($cle,"g");
					$this->info_600_j=$record->get_subfield_array_array($cle,"j");
					$this->info_600_p=$record->get_subfield_array_array($cle,"p");
					$this->info_600_t=$record->get_subfield_array_array($cle,"t");
					$this->info_600_x=$record->get_subfield_array_array($cle,"x");
					$this->info_600_y=$record->get_subfield_array_array($cle,"y");
					$this->info_600_z=$record->get_subfield_array_array($cle,"z");
					break;
				case "601": // 601 CORPORATE BODY NAME USED AS SUBJECT
					$this->info_601_3=$record->get_subfield_array_array($cle,"3");
					$this->info_601_a=$record->get_subfield_array_array($cle,"a");
					$this->info_601_b=$record->get_subfield_array_array($cle,"b");
					$this->info_601_c=$record->get_subfield_array_array($cle,"c");
					$this->info_601_d=$record->get_subfield_array_array($cle,"d");
					$this->info_601_e=$record->get_subfield_array_array($cle,"e");
					$this->info_601_f=$record->get_subfield_array_array($cle,"f");
					$this->info_601_g=$record->get_subfield_array_array($cle,"g");
					$this->info_601_h=$record->get_subfield_array_array($cle,"h");
					$this->info_601_t=$record->get_subfield_array_array($cle,"t");
					$this->info_601_j=$record->get_subfield_array_array($cle,"j");
					$this->info_601_x=$record->get_subfield_array_array($cle,"x");
					$this->info_601_y=$record->get_subfield_array_array($cle,"y");
					$this->info_601_z=$record->get_subfield_array_array($cle,"z");
					break;
				case "602": // 602 FAMILY NAME USED AS SUBJECT
					$this->info_602_3=$record->get_subfield_array_array($cle,"3");
					$this->info_602_a=$record->get_subfield_array_array($cle,"a");
					$this->info_602_f=$record->get_subfield_array_array($cle,"f");
					$this->info_602_t=$record->get_subfield_array_array($cle,"t");
					$this->info_602_j=$record->get_subfield_array_array($cle,"j");
					$this->info_602_x=$record->get_subfield_array_array($cle,"x");
					$this->info_602_y=$record->get_subfield_array_array($cle,"y");
					$this->info_602_z=$record->get_subfield_array_array($cle,"z");
					break;
				case "604": // 604 AUTEUR-TITRE USED AS SUBJECT
					$this->info_604_a=$record->get_subfield_array_array($cle,"a");
					$this->info_604_h=$record->get_subfield_array_array($cle,"h");
					$this->info_604_i=$record->get_subfield_array_array($cle,"i");
					$this->info_604_j=$record->get_subfield_array_array($cle,"j");
					$this->info_604_k=$record->get_subfield_array_array($cle,"k");
					$this->info_604_l=$record->get_subfield_array_array($cle,"l");
					$this->info_604_x=$record->get_subfield_array_array($cle,"x");
					$this->info_604_y=$record->get_subfield_array_array($cle,"y");
					$this->info_604_z=$record->get_subfield_array_array($cle,"z");
					break;
				case "605": // 605 TITLE USED AS SUBJECT
					$this->info_605_3=$record->get_subfield_array_array($cle,"3");
					$this->info_605_a=$record->get_subfield_array_array($cle,"a");
					$this->info_605_h=$record->get_subfield_array_array($cle,"h");
					$this->info_605_i=$record->get_subfield_array_array($cle,"i");
					$this->info_605_j=$record->get_subfield_array_array($cle,"j");
					$this->info_605_k=$record->get_subfield_array_array($cle,"k");
					$this->info_605_l=$record->get_subfield_array_array($cle,"l");
					$this->info_605_m=$record->get_subfield_array_array($cle,"m");
					$this->info_605_n=$record->get_subfield_array_array($cle,"n");
					$this->info_605_q=$record->get_subfield_array_array($cle,"q");
					$this->info_605_r=$record->get_subfield_array_array($cle,"r");
					$this->info_605_s=$record->get_subfield_array_array($cle,"s");
					$this->info_605_u=$record->get_subfield_array_array($cle,"u");
					$this->info_605_w=$record->get_subfield_array_array($cle,"w");
					$this->info_605_j=$record->get_subfield_array_array($cle,"j");
					$this->info_605_x=$record->get_subfield_array_array($cle,"x");
					$this->info_605_y=$record->get_subfield_array_array($cle,"y");
					$this->info_605_z=$record->get_subfield_array_array($cle,"z");
					break;
				case "606": // RAMEAU / TOPICAL NAME USED AS SUBJECT
					$this->info_606_3=$record->get_subfield_array_array($cle,"3");
					$this->info_606_a=$record->get_subfield_array_array($cle,"a");
					$this->info_606_j=$record->get_subfield_array_array($cle,"j");
					$this->info_606_x=$record->get_subfield_array_array($cle,"x");
					$this->info_606_y=$record->get_subfield_array_array($cle,"y");
					$this->info_606_z=$record->get_subfield_array_array($cle,"z");
					break;
				case "607": // 607 GEOGRAPHICAL NAME USED AS SUBJECT
					$this->info_607_3=$record->get_subfield_array_array($cle,"3");
					$this->info_607_a=$record->get_subfield_array_array($cle,"a");
					$this->info_607_j=$record->get_subfield_array_array($cle,"j");
					$this->info_607_x=$record->get_subfield_array_array($cle,"x");
					$this->info_607_y=$record->get_subfield_array_array($cle,"y");
					$this->info_607_z=$record->get_subfield_array_array($cle,"z");
					break;
				case "608": // 608 Vedette matière de forme, de genre ou des caractéristiques physiques
					$this->info_608_3=$record->get_subfield_array_array($cle,"3");
					$this->info_608_a=$record->get_subfield_array_array($cle,"a");
					$this->info_608_j=$record->get_subfield_array_array($cle,"j");
					$this->info_608_x=$record->get_subfield_array_array($cle,"x");
					$this->info_608_y=$record->get_subfield_array_array($cle,"y");
					$this->info_608_z=$record->get_subfield_array_array($cle,"z");
					break;
				case "610": /* mots clé */
					$index_sujets=$record->get_subfield($cle,"a");
					break;
				case "676": /* Dewey */
					$this->dewey=$record->get_subfield($cle,"a");
					break;
				case "686": /* PCDM */
					if (!$this->dewey)
						$this->dewey=$record->get_subfield($cle,"a");
					break;
				case "700":
					$aut_700=$record->get_subfield($cle,"a","b","c","d","4","f","N");
					break;
				case "701":
					$aut_701=$record->get_subfield($cle,"a","b","c","d","4","f","N");
					break;
				case "702":
					$aut_702=$record->get_subfield($cle,"a","b","c","d","4","f","N");
					break;
				case "710":
					$aut_710=$record->get_subfield($cle,"a","b","c","g","d","4","f","e","k","l","m","n");
					break;
				case "711":
					$aut_711=$record->get_subfield($cle,"a","b","c","g","d","4","f","e","k","l","m","n");
					break;
				case "712":
					$aut_712=$record->get_subfield($cle,"a","b","c","g","d","4","f","e","k","l","m","n");
					break;
				case "801": /* origine du catalogage */
					$origine_notice=$record->get_subfield($cle,"a","b");
					break;
				case "856":
					$ressource = $record->get_subfield($cle,"u","q");
					break;
				case "995": /* infos de la BDP */
					$info_995 = $record->get_subfield($cle,"a","b","c","d","f","k","m","n","o","r","u");
					break;
				case "896": /* Thumbnail */
					$this->thumbnail_url = $record->get_subfield($cle,"a");
					$this->thumbnail_url = $this->thumbnail_url[0];
					break;
				//Documents numériques
				case "897":
					$this->doc_nums = $record->get_subfield($cle,"a", "b","f");
					break;
				default:
					break;

			} /* end of switch */

		} /* end of for */

		$this->isbn = $this->process_isbn ($isbn[0]);

		/* INSERT de la notice OK, on va traiter les auteurs
		70# : personnal : type auteur 70                71# : collectivités : type auteur 71
		1 seul en 700                                   idem pour les déclinaisons          
		n en 701 n en 702
		les 7#0 tombent en auteur principal : responsability_type = 0
		les 7#1 tombent en autre auteur : responsability_type = 1
		les 7#2 tombent en auteur secondaire : responsability_type = 2
		*/
		$this->aut_array = array();
		/* on compte tout de suite le nbre d'enreg dans les répétables */
		$nb_repet_701=sizeof($aut_701);
		$nb_repet_711=sizeof($aut_711);
		$nb_repet_702=sizeof($aut_702);
		$nb_repet_712=sizeof($aut_712);

		/* renseignement de aut0 */
		if ($aut_700[0][a]!="") { /* auteur principal en 700 ? */
			$this->aut_array[] = array(
				"entree" => $aut_700[0]['a'],
				"rejete" => $aut_700[0]['b'],
				"author_comment" => $aut_700[0]['c']." ".$aut_700[0]['d'],
				"date" => $aut_700[0]['f'],
				"type_auteur" => "70",
				"fonction" => $aut_700[0][4],
				"id" => 0,
				"responsabilite" => 0 ,
				"ordre" => 0 ) ;
		} elseif ($aut_710[0]['a']!="") { /* auteur principal en 710 ? */
			if(substr($indicateur["710"][0],0,1)=="1")	$type_auteur="72";
			else $type_auteur="71";
			
			$lieu=$aut_710[0]['e'];
			if(!$lieu)$lieu=$aut_710[0]['k'];	
			$this->aut_array[] = array(
				"entree" => $aut_710[0]['a'],
				"rejete" => $aut_710[0]['g'],
				"subdivision" => $aut_710[0]['b'],
				"author_comment" => $aut_710[0]['c'],
				"numero" => $aut_710[0]['d'],
				"ville" => $aut_710[0]['l'],
				"web" => $aut_710[0]['n'],
				"date" => $aut_710[0]['f'],
				"type_auteur" => $type_auteur*1,
				"fonction" => $aut_710[0][4],
				"id" => 0,
				"responsabilite" => 0,
				"ordre" => 0 ,
				"lieu" => $lieu,
				"pays" => $aut_710[0]['m'] ) ;
		} 
	
		/* renseignement de aut1 */
		for ($i=0 ; $i < $nb_repet_701 ; $i++) {
			$this->aut_array[] = array(
				"entree" => $aut_701[$i]['a'],
				"rejete" => $aut_701[$i]['b'],
				"author_comment" => $aut_701[$i]['c']." ".$aut_701[$i]['d'],
				"date" => $aut_701[$i]['f'],
				"type_auteur" => "70",
				"fonction" => $aut_701[$i][4],
				"id" => 0,
				"responsabilite" => 1,
				"ordre" => ($i+1) ) ;
			}
		for ($i=0 ; $i < $nb_repet_711 ; $i++) {
			if(substr($indicateur["711"][$i],0,1)=="1")	$type_auteur="72";
			else $type_auteur="71";	
			
			$lieu=$aut_711[$i]['e'];
			if(!$lieu)$lieu=$aut_711[$i]['k'];	
			$this->aut_array[] = array(
				"entree" => $aut_711[$i]['a'],
				"rejete" => $aut_711[$i]['g'],
				"subdivision" => $aut_711[$i]['b'],
				"author_comment" => $aut_711[$i]['c'],
				"numero" => $aut_711[$i]['d'],
				"ville" => $aut_711[$i]['l'],
				"web" => $aut_711[$i]['n'],
				"date" => $aut_711[$i]['f'],
				"type_auteur" => $type_auteur*1,
				"fonction" => $aut_711[$i][4],
				"id" => 0,
				"responsabilite" => 1,
				"lieu" => $lieu,
				"pays" => $aut_711[$i]['m'],
				"ordre" => ($i+1) ) ;
			}
		/* renseignement de aut2 */
		for ($i=0 ; $i < $nb_repet_702 ; $i++) {
			$this->aut_array[] = array(
				"entree" => $aut_702[$i]['a'],
				"rejete" => $aut_702[$i]['b'],
				"author_comment" => $aut_702[$i]['c']." ".$aut_702[$i]['d'],
				"date" => $aut_702[$i]['f'],
				"type_auteur" => "70",
				"fonction" => $aut_702[$i][4],
				"id" => 0,
				"responsabilite" => 2,
				"ordre" => ($i+1) ) ;
			}
		for ($i=0 ; $i < $nb_repet_712 ; $i++) {
			if(substr($indicateur["712"][$i],0,1)=="1")	$type_auteur="72";
			else $type_auteur="71";	
			$lieu=$aut_712[$i]['e'];
			if(!$lieu)$lieu=$aut_712[$i]['k'];
						
			$this->aut_array[] = array(
				"entree" => $aut_712[$i]['a'],
				"rejete" => $aut_712[$i]['g'],
				"subdivision" => $aut_712[$i]['b'],
				"author_comment" => $aut_712[$i]['c'],
				"numero" => $aut_712[$i]['d'],
				"ville" => $aut_712[$i]['l'],
				"web" => $aut_712[$i]['n'],
				"date" => $aut_712[$i]['f'],
				"type_auteur" => $type_auteur*1,
				"fonction" => $aut_712[$i][4],
				"id" => 0,
				"responsabilite" => 2,
				"lieu" => $lieu,
				"pays" => $aut_712[$i]['m'],
				"ordre" => ($i+1) ) ;
			}
		/*  Added for some italian z39.50 server 
		Some adjustment to clean the values from symbol like << and others */
		for ($i=0 ; $i < $nb_repet_701+$nb_repet_711+$nb_repet_702 +$nb_repet_712+1 ; $i++) {
			$this->aut_array[$i]['entree']=del_more_garbage($this->aut_array[$i]['entree']);
			$this->aut_array[$i]['rejete']=del_more_garbage($this->aut_array[$i]['rejete']);
		}
		
		/* traitement des éditeurs */
		$this->year=clean_string($editor[0][d]);

		$this->editors[0]['name']=clean_string($editor[0][c]);
		$this->editors[0]['ville']=clean_string($editor[0][a]);

		$this->editors[1]['name']=clean_string($editor[1][c]);
		$this->editors[1]['ville']=clean_string($editor[1][a]);
		
		/*  Added for some italian z39.50 server 
		Some adjustment to clean the values from symbol like << and others */
		$this->editors[0]['name']=del_more_garbage($this->editors[0]['name']);
		$this->editors[1]['name']=del_more_garbage($this->editors[1]['name']);
		
		/* traitement des collections */
		$coll_name="";
		$subcoll_name="";
		$coll_issn="";
		$subcoll_issn="";
		$nocoll_ins="";

		// Collection : traitement de 225 et 410, préférence donnée au 410
		if ($collection_410[0][t]) $coll_name=$collection_410[0][t];
		elseif ($collection_225[0][a]!="") $coll_name=$collection_225[0][a];
		
		if ($collection_410[0][x]) $coll_issn=$collection_410[0][x];
		elseif ($collection_225[0][x]!="") $coll_issn=$collection_225[0][x];
		
		
		// Sous-collection : traitement de 225$i et 411, préférence donnée au 411
		if ($collection_411[0][t]) $subcoll_name=$collection_411[0][t];
		elseif ($collection_225[0][i]!="") $subcoll_name=$collection_225[0][i];
		
		if ($collection_411[0][x]) $subcoll_issn=$collection_411[0][x];

		// Numéro dans la collection, présent en 411$v, sinon en 410$v et enfin en 225$v
		if     ($collection_411[0][v]!="") $this->nbr_in_collection = $collection_411[0][v];
		elseif ($collection_410[0][v]!="") $this->nbr_in_collection = $collection_410[0][v];
		elseif ($collection_225[0][v]!="") $this->nbr_in_collection = $collection_225[0][v];
		else $this->nbr_in_collection = "";

		$this->collection['name']=clean_string($coll_name);
		$this->collection['issn']=clean_string($coll_issn);

		$this->subcollection['name']=clean_string($subcoll_name);
		$this->subcollection['issn']=clean_string($subcoll_issn);

		/*  Added for some italian z39.50 server 
		Some adjustment to clean the values from symbol like << and others */
		$this->collection['name']=del_more_garbage($this->collection['name']);
		$this->subcollection['name']=del_more_garbage($this->subcollection['name']);
		
		/* Series  TODO: Check if it's Ok */
		$this->serie = clean_string ($serie[0][t]);
		if ($this->serie)
			$this->nbr_in_serie = $serie[0][v];
		else
			$this->nbr_in_serie = "";
			
		/* Traitement des notes */				
		$this->general_note = "";
		$this->content_note = "";
		$this->abstract_note = "";
		if (!$abstract_note) $abstract_note=array();
		$this->abstract_note= implode("\n",$abstract_note);
	
		if (!$general_note) $general_note=array();
		$this->general_note= implode("\n",$general_note);
	
		if (!$content_note) $content_note=array();
		$this->content_note= implode("\n",$content_note);
	
		/* traitement ressources */
		$this->link_url = $ressource[0]["u"];
		$this->link_format = $ressource[0]["q"];

		/* Titles processing */
		$tit[0]['a'] = implode (" ; ",$tit_200a);
		$tit[0]['c'] = implode (" ; ",$tit_200c);
		$tit[0]['d'] = implode (" ; ",$tit_200d);
		$tit[0]['e'] = implode (" ; ",$tit_200e);
	
		$this->titles[0] = preg_replace('/-$| $|\||\($|\)$|\[$|\]$|\:$|\;$|\/$\|\$|\.$/', '', trim($tit[0][a]));
		$this->titles[1] = preg_replace('/-$| $|\||\($|\)$|\[$|\]$|\:$|\;$|\/$\|\$|\.$/', '', trim($tit[0][c]));
		$this->titles[2] = preg_replace('/-$| $|\||\($|\)$|\[$|\]$|\:$|\;$|\/$\|\$|\.$/', '', trim($tit[0][d]));
		$this->titles[3] = preg_replace('/-$| $|\||\($|\)$|\[$|\]$|\:$|\;$|\/$\|\$|\.$/', '', trim($tit[0][e]));
		
		/*  Added for some italian z39.50 server 
		Some adjustment to clean the values from symbol like << and others */
		$this->titles[0]=del_more_garbage($this->titles[0]);
		$this->titles[1]=del_more_garbage($this->titles[1]);
		$this->titles[2]=del_more_garbage($this->titles[2]);
		$this->titles[3]=del_more_garbage($this->titles[3]);
		
		global $pmb_keyword_sep ;
		if (!$pmb_keyword_sep) $pmb_keyword_sep=" ";
		if (is_array($index_sujets)) $this->free_index = implode ($pmb_keyword_sep,$index_sujets);
			else $this->free_index = $index_sujets;
			
		$this->origine_notice['nom']=clean_string($origine_notice[0]['b']);
		$this->origine_notice['pays']=clean_string($origine_notice[0]['a']);
		
	}

	function get_isbd_display () {
		$tdoc = new marc_list('doctype');

		if ($this->aut_array[0]['rejete'])
			$author = $this->aut_array[0]['rejete']." ".$this->aut_array[0]['entree'];
		else
			$author = $this->aut_array[0]['entree'];

		$display = 
			$this->titles[0]." [".$tdoc->table[$this->document_type]."] / ".
			$author.".&nbsp;- ".
			$this->editors[0]['name'].", ".
			$this->year.".&nbsp;- ".
			$this->page_nbr." : ".
			$this->illustration." ; ".
			$this->size.".&nbsp;- (".
			$this->collection['name'].". ".
			$this->subcollection['name']." ; ".
			$this->nbr_in_collection.").<br />".
			$this->isbn.
			"<br /><font size='1'>".$this->abstract_note."</font>";
		
		return array ($this->isbn, $this->titles[0], $author, $display);
	}
	
	/*
	 * Transforme les variables de classe comme si elles étaient postées
	 */
	function var_to_post(){
		
		global $dbh, $deflt_notice_statut;
		
		$this->document_type = 	addslashes($this->document_type);
		$this->isbn = addslashes($this->isbn);	
		$this->titles[0] = addslashes($this->titles[0]); 
		$this->titles[1] = addslashes($this->titles[1]); 
		$this->titles[2] = addslashes($this->titles[2]); 
		$this->titles[3] = addslashes($this->titles[3]); 
		$serie_id = addslashes($serie_id) ;
		$this->nbr_in_serie = addslashes($this->nbr_in_serie); 
		$editor_ids[0] = addslashes($editor_ids[0]);
		$editor_ids[1] = addslashes($editor_ids[1]);
		$this->year = addslashes($this->year) ;
		$this->page_nbr = addslashes($this->page_nbr) ;
		$this->illustration = addslashes($this->illustration) ;
		$this->size = addslashes($this->size) ;
		$this->accompagnement = addslashes($this->accompagnement) ;
		$collection_id = addslashes($collection_id);
		$subcollection_id =	addslashes($subcollection_id);
		$this->nbr_in_collection = addslashes($this->nbr_in_collection);
		$this->mention_edition = addslashes($this->mention_edition); 
		$this->general_note = addslashes($this->general_note) ;
		$this->content_note = addslashes($this->content_note);
		$this->abstract_note = addslashes($this->abstract_note) ;
		$this->internal_index = addslashes($this->internal_index); 
		$serie_index = addslashes($serie_index) ;
		$title_indexes_sew = addslashes($title_indexes_sew);
		$title_indexes_wew = addslashes($title_indexes_wew) ;
		$this->statut = $deflt_notice_statut ;
		$this->commentaire_gestion = addslashes($this->commentaire_gestion); 
		$this->thumbnail_url = addslashes($this->thumbnail_url) ;
		$this->free_index = addslashes($this->free_index);
		$this->matieres_index = addslashes($this->matieres_index) ;
		$this->bibliographic_level = addslashes($this->bibliographic_level); 
		$this->hierarchic_level = addslashes($this->hierarchic_level) ;
		$this->link_url = addslashes($this->link_url) ;
		$this->link_format = addslashes($this->link_format) ;
		$this->orinot_id = addslashes($this->orinot_id) ;
		$this->prix = addslashes($this->prix);
		
		if($this->bibliographic_level == 'a' && $this->hierarchic_level=='2'){
			//Pério et bulletin
			$this->perio_titre = addslashes($this->perio_titre[0]);
			$this->perio_issn = addslashes($this->perio_issn[0]);
			$this->bull_titre = addslashes($this->bull_titre[0]);
			$this->bull_date = addslashes($this->bull_date[0]);
			$this->bull_mention = addslashes($this->bull_mention[0]);
			$this->bull_num = addslashes($this->bull_num[0]);
			
			//On cherche si le perio existe
			if($this->perio_titre && $this->perio_issn){			
				$req="select notice_id, tit1, code from notices where niveau_biblio='s' and niveau_hierar='1' 
						and tit1='".$this->perio_titre."'
						and code='".$this->perio_issn."' limit 1";
				$res_perio = mysql_query($req,$dbh);
				$num_rows_perio = mysql_num_rows($res_perio);
			}
			if (!$num_rows_perio){
				if($this->perio_titre){
					$req="select notice_id, tit1, code from notices where niveau_biblio='s' and niveau_hierar='1' 
						and tit1='".$this->perio_titre."'
						limit 1";
					$res_perio = mysql_query($req,$dbh);
					$num_rows_perio = mysql_num_rows($res_perio);
				}
			}
			if (!$num_rows_perio){
				if($this->perio_issn){
					$req="select notice_id, tit1, code from notices where niveau_biblio='s' and niveau_hierar='1' 
							and code='".$this->perio_issn."' limit 1";
					$res_perio = mysql_query($req,$dbh);
					$num_rows_perio = mysql_num_rows($res_perio);
				}
			}	
			if ($num_rows_perio == 1) {
				$perio_found = mysql_fetch_object($res_perio);
				$this->perio_titre = addslashes($perio_found->tit1);
				$this->perio_issn = addslashes($perio_found->code);
				$this->perio_id = addslashes($perio_found->notice_id);
			} 
			//On cherche si le bulletin existe
			if($this->bull_num && $this->perio_id){
				$req="select bulletin_id, bulletin_numero, date_date, mention_date, bulletin_titre from bulletins where bulletin_notice='".$this->perio_id."' and  bulletin_numero like '".addslashes($this->bull_num)."%' limit 1";
				$res_bull = mysql_query($req,$dbh);
				$num_rows_bull = mysql_num_rows($res_bull);
			}
			if(!$num_rows_bull){
				if($this->bull_date && $this->perio_id){
					$req="select bulletin_id, bulletin_numero, date_date, mention_date, bulletin_titre from bulletins where bulletin_notice='".$this->perio_id."' and date_date='".$this->bull_date."' limit 1";
					$res_bull = mysql_query($req,$dbh);
					$num_rows_bull = mysql_num_rows($res_bull);
				}
			}
			if(!$num_rows_bull){
				if($this->bull_mention && $this->perio_id){
					$req="select bulletin_id, bulletin_numero, date_date, mention_date, bulletin_titre from bulletins where bulletin_notice='".$this->perio_id."' and mention_date='".$this->bull_mention."' limit 1";
					$res_bull = mysql_query($req,$dbh);
					$num_rows_bull = mysql_num_rows($res_bull);
				} 
			}				
			if ($num_rows_bull == 1) {
				$bull_found = mysql_fetch_object($res_bull);
				$this->bull_titre = addslashes($bull_found->bulletin_titre);
				$this->bull_date = addslashes($bull_found->date_date);
				$this->bull_mention = addslashes($bull_found->mention_date);
				$this->bull_num = addslashes($bull_found->bulletin_numero);
				$this->bull_id = $bull_found->bulletin_id;
			}
			
			global $biblio_notice;
			$biblio_notice = "art";
		} 		
	}
}

