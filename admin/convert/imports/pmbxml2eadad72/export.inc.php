<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: export.inc.php,v 1.5 2009-08-11 13:57:47 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/marc_table.class.php");
require_once("$class_path/category.class.php");
require_once($class_path."/parametres_perso.class.php");

function _export_($id,$keep_expl=0) {

	$requete="select * from notices where notice_id=$id";
	$resultat=mysql_query($requete);
	$rn=mysql_fetch_object($resultat);
	
	$dt=$rn->typdoc;
	$bl=$rn->niveau_biblio;
	$hl=$rn->niveau_hierar;
	
	
	$notice.="<notice>\n";
	$notice.="  <rs>n</rs>\n";
  	$notice.="  <dt>".$dt."</dt>\n";
  	$notice.="  <bl>".$bl."</bl>\n";
  	$notice.="  <hl>".$hl."</hl>\n";
  	$notice.="  <el>1</el>\n";
  	$notice.="  <ru>i</ru>\n";
		
	//ISBN
	if ($rn->code!='') {
		$notice.="  <f c='010' ind=' '>\n";
		$notice.="    <s c='a'>".htmlspecialchars($rn->code)."</s>\n";
		$notice.="  </f>\n";
	}
	
	//Langage
	$rqttmp_lang = "select type_langue,code_langue from notices_langues where num_notice='$id' order by 1 ";
	$restmp_lang = mysql_query($rqttmp_lang);
	if(mysql_num_rows($restmp_lang)) {
		$ind="0 ";
		$notice_langue_temp="";
		while (($tmp_lang = mysql_fetch_object($restmp_lang))) {
			if($tmp_lang->type_langue) {
				$ind="1 ";
				$notice_langue_temp.="    <s c='c'>".htmlspecialchars($tmp_lang->code_langue)."</s>\n";
			} else {
				$notice_langue_temp.="    <s c='a'>".htmlspecialchars($tmp_lang->code_langue)."</s>\n";
			}
		}
		$notice.="  <f c='101' ind='".$ind."'>\n";
		$notice.=$notice_langue_temp;
		$notice.="  </f>\n";
	} 
	
	//Titre
	if ($rn->tit1!='') {
		$notice.="  <f c='200' ind='1 '>\n";
	    $notice.="    <s c='a'>".htmlspecialchars($rn->tit1)."</s>\n";
		if ($rn->tit2!='') {
		    $notice.="    <s c='c'>".htmlspecialchars($rn->tit2)."</s>\n";
		}
		if ($rn->tit3!='') {
		    $notice.="    <s c='d'>".htmlspecialchars($rn->tit3)."</s>\n";
		}
		if ($rn->tit4!='') {
		    $notice.="    <s c='e'>".htmlspecialchars($rn->tit4)."</s>\n";
		}
		$notice.="  </f>\n";
	}
	
	//Mention d'édition
	if ($rn->mention_edition) {
		$notice.="  <f c='205' ind='  '>\n";
		$notice.="    <s c='a'>".htmlspecialchars($rn->mention_edition)."</s>\n";
		$notice.="  </f>\n";
	}

	//Editeurs
	if ($rn->ed1_id) {
	    $requete="select * from publishers where ed_id=".$rn->ed1_id;
		$resultat=mysql_query($requete);
		$red=mysql_fetch_object($resultat);
		$notice.="  <f c='210' ind='  '>\n";
		$notice.="    <s c='c'>".htmlspecialchars($red->ed_name)."</s>\n";
		if ($red->ed_ville!='') $notice.="    <s c='a'>".htmlspecialchars($red->ed_ville)."</s>\n";
		if ($rn->year!='') $notice.="    <s c='d'>".htmlspecialchars($rn->year)."</s>\n";
		$notice.="  </f>\n";
	}
	if ($rn->ed2_id) {
	    $requete="select * from publishers where ed_id=".$rn->ed2_id;
		$resultat=mysql_query($requete);
		$red=mysql_fetch_object($resultat);
		$notice.="  <f c='210' ind='  '>\n";
		$notice.="    <s c='c'>".htmlspecialchars($red->ed_name)."</s>\n";
		if ($red->ed_ville!='') $notice.="    <s c='a'>".htmlspecialchars($red->ed_ville)."</s>\n";
		if ($rn->year!='') $notice.="    <s c='d'>".htmlspecialchars($rn->year)."</s>\n";
		$notice.="  </f>\n";
	}
		
	//Collation
	if ($rn->npages || $rn->ill || $rn->size || $rn->accomp) {
	    $notice.="  <f c='215' ind='  '>\n";
	    if ($rn->npages) $notice.="    <s c='a'>".htmlspecialchars($rn->npages)."</s>\n";
		if ($rn->ill)    $notice.="    <s c='c'>".htmlspecialchars($rn->ill)."</s>\n";
		if ($rn->size)   $notice.="    <s c='d'>".htmlspecialchars($rn->size)."</s>\n";
		if ($rn->accomp) $notice.="    <s c='e'>".htmlspecialchars($rn->accomp)."</s>\n";
		$notice.="  </f>\n";
	}
	
	//Collection
	if ($rn->coll_id) {
		$requete="select * from collections where collection_id=".$rn->coll_id;
		$resultat=mysql_query($requete);
		if (($col = mysql_fetch_object($resultat))) {
			$notice.="  <f c='225' ind='2 '>\n";
			$notice.="    <s c='a'>".htmlspecialchars($col->collection_name)."</s>\n";
			if ($rn->nocoll!='') $notice.="    <s c='v'>".htmlspecialchars($rn->nocoll)."</s>\n";
			if ($col->collection_issn!='') $notice.="    <s c='x'>".htmlspecialchars($col->collection_issn)."</s>\n";	
		}

		//sous-collection
		if ($rn->subcoll_id){
			$requete="select * from sub_collections where sub_coll_id=".$rn->subcoll_id;
			$resultat=mysql_query($requete);		    
			if (($subcol = mysql_fetch_object($resultat))) {
				$notice.="    <s c='i'>".htmlspecialchars($subcol->sub_coll_name)."</s>\n";
			}
		}
		$notice.="  </f>\n";	
	}
	
	//Notes
	//Générale
	if ($rn->n_gen) {
	    $notice.="  <f c='300' ind='  '>\n";
		$notice.="    <s c='a'>".htmlspecialchars($rn->n_gen)."</s>\n";
		$notice.="  </f>\n";
	}
	//de contenu
	if ($rn->n_contenu) {
	    $notice.="  <f c='327' ind='  '>\n";
		$notice.="    <s c='a'>".htmlspecialchars($rn->n_contenu)."</s>\n";
		$notice.="  </f>\n";
	}
	//Résumé
	if ($rn->n_resume) {
	    $notice.="  <f c='330' ind='  '>\n";
		$notice.="    <s c='a'>".htmlspecialchars($rn->n_resume)."</s>\n";
		$notice.="  </f>\n";
	}

	//Titre de série
	$serie="";
	if ($rn->tparent_id!=0 || $rn->tnvol!='') {
		$requete="select serie_name from series where serie_id=".$rn->tparent_id;
		$resultat=mysql_query($requete);
		if (mysql_num_rows($resultat)) $serie=mysql_result($resultat,0,0);
		$notice.="  <f c='461' ind=' 0'>\n";
		if ($serie!='') $notice.="    <s c='t'>".htmlspecialchars($serie)."</s>\n";
		if ($rn->tnvol) $notice.="    <s c='v'>".htmlspecialchars($rn->tnvol)."</s>\n";
		$notice.="  </f>\n";
	}
	
	if($bl=='a') {
		//liens vers les périodiques et bulletins pour les notices d'article
		$req_link = "SELECT notice_id, tit1, code ";
		$req_link.= "bulletin_id, bulletin_numero, date_date, mention_date, bulletin_titre, bulletin_numero ";
		$req_link.= "from analysis,bulletins,notices WHERE analysis_notice=".$id." and bulletin_id=analysis_bulletin and bulletin_notice=notice_id ";
		$result_link=mysql_query($req_link);
		if (mysql_num_rows($result_link)) { 
			$row=mysql_fetch_object($result_link);
			$notice.="  <f c='461' ind='  '>\n";
			$notice.="    <s c='t'>".htmlspecialchars($row->tit1)."</s>\n";
			$notice.="    <s c='9'>lnk:perio</s>\n";
			$notice.="  </f>\n";
			$notice.="  <f c='463' ind='  '>\n";
			$notice.="    <s c='d'>".htmlspecialchars(formatdate($row->date_date))."</s>\n";
			if($row->mention_date) $notice.="    <s c='e'>".htmlspecialchars($row->mention_date)."</s>\n";
			$notice.="    <s c='v'>".htmlspecialchars($row->bulletin_numero)."</s>\n";
			if($row->bulletin_titre!='') $notice.="    <s c='t'>".htmlspecialchars($row->bulletin_titre)."</s>\n";
			$notice.="    <s c='9'>lnk:bull</s>\n";
			$notice.="  </f>\n";
		}					
	 }

	//Descripteurs
	$requete="SELECT libelle_categorie FROM categories, notices_categories WHERE notcateg_notice=".$id." and categories.num_noeud = notices_categories.num_noeud ORDER BY ordre_categorie";
	$resultat=mysql_query($requete);
	if (mysql_num_rows($resultat)) {
		while (($row=mysql_fetch_object($resultat))) {
			$notice.="  <f c='606' ind=' 1'>\n";
			$notice.="    <s c='a'>".htmlspecialchars($row->libelle_categorie)."</s>\n";
			$notice.="  </f>\n";
		}
	}

	//Auteurs			
	//Recherche des auteurs;
	$requete = "select author_type, author_name, author_rejete, author_date, responsability_fonction, responsability_type 
	,author_subdivision, author_lieu,author_ville, author_pays,author_numero,author_web
	from authors, responsability where responsability_notice=".$id." and responsability_author=author_id";
	$resultat = mysql_query($requete);

	while (($auth=mysql_fetch_object($resultat))) {				
		//Si c'est un 70 (individuel) alors on l'exporte
		if ($auth->author_type == "70") {
			// Personne physique
			$notice.="  <f c='".$auth->author_type.$auth->responsability_type."' ind=' 1'>\n";
			$notice.="    <s c='a'>".htmlspecialchars($auth->author_name)."</s>\n";
			if ($auth->author_rejete!='') $notice.="    <s c='b'>".htmlspecialchars($auth->author_rejete)."</s>\n";
			if ($auth->responsability_fonction!='') $notice.="    <s c='4'>".$auth->responsability_fonction."</s>\n";
			if ($auth->author_date!="") $notice.="    <s c='f'>".htmlspecialchars($auth->author_date)."</s>\n";
			if ($auth->author_web!='') $notice.="    <s c='N'>".htmlspecialchars($auth->author_web)."</s>\n";
			$notice.="  </f>\n";
		} elseif (($auth->author_type == "71") || ($auth->author_type == "72")) {
			//Collectivité
			$notice.="  <f c='".$auth->author_type.$auth->responsability_type;
			if ($auth->author_type == "71") {
				$notice.="' ind='02'>\n";
			} elseif ($auth->author_type == "72") {
				$notice.="' ind='12'>\n";
			}
			$notice.="    <s c='a'>".htmlspecialchars($auth->author_name)."</s>\n";
			if ($auth->author_subdivision!='') $notice.="    <s c='b'>".htmlspecialchars($auth->author_subdivision)."</s>\n";
			if ($auth->author_rejete!='') $notice.="    <s c='g'>".htmlspecialchars($auth->author_rejete)."</s>\n";
			if ($auth->author_numero!='') $notice.="    <s c=d'>".htmlspecialchars($auth->author_numero)."</s>\n";
			if ($auth->responsability_fonction!='') $notice.="    <s c='4'>".$auth->responsability_fonction."</s>\n";
			if ($auth->author_date!="") $notice.="    <s c='f'>".htmlspecialchars($auth->author_date)."</s>\n";
			$lieu=$auth->author_lieu;
			if($auth->author_ville) {
				if($lieu) $lieu.="; ";
				$lieu.=$auth->author_ville;
			}
			if($auth->author_pays) {
				if($lieu) $lieu.="; ";
				$lieu.=$auth->author_pays;
			}					
			if ($lieu!='') $notice.="    <s c='e'>".htmlspecialchars($lieu)."</s>\n";
			if ($auth->author_lieu!='') $notice.="    <s c='K'>".htmlspecialchars($auth->author_lieu)."</s>\n";
			if ($auth->author_ville!='') $notice.="    <s c='L'>".htmlspecialchars($auth->author_ville)."</s>\n";
			if ($auth->author_pays!='') $notice.="    <s c='M'>".htmlspecialchars($auth->author_pays)."</s>\n";
			if ($auth->author_web!='') $notice.="    <s c='N'>".htmlspecialchars($auth->author_web)."</s>\n";
			$notice.="  </f>\n";
		}					
	}
		
	//URL
	if ($rn->lien!='') {
	    $notice.="  <f c='856'>\n";
		$notice.="    <s c='u'>".htmlspecialchars($rn->lien)."</s>\n";
		if ($rn->eformat!='') $notice.="    <s c='q'>".htmlspecialchars($rn->eformat)."</s>\n";
		$notice.="  </f>\n";
	}
	
	//Champs perso de notice traite par la table notice_custom
	$mes_pp= new parametres_perso("notices");
	$mes_pp->get_values($id);
	$values = $mes_pp->values;
	foreach ( $values as $field_id => $vals ) {
		if($mes_pp->t_fields[$field_id]["EXPORT"]) { //champ exportable
			foreach ( $vals as $value ) {
				if ($value) {
					$notice.="  <f c='900' ind='  '>\n";
					$notice.="    <s c='a'>".htmlspecialchars($mes_pp->get_formatted_output(array($value),$field_id))."</s>\n";
					$notice.="    <s c='l'>".htmlspecialchars($mes_pp->t_fields[$field_id]["TITRE"])."</s>\n";
					$notice.="    <s c='n'>".htmlspecialchars($mes_pp->t_fields[$field_id]["NAME"])."</s>\n";
				 	$notice.="  </f>\n";;
				}
			} 
		}  
	}
	
	$notice.="</notice>\n";
	return $notice;
}

?>