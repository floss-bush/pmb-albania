<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: export.inc.php,v 1.9 2008-07-31 05:01:51 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/marc_table.class.php");
require_once("$class_path/category.class.php");
function _export_($id,$keep_expl) {
	$requete="select * from notices where notice_id=$id";
	$resultat=mysql_query($requete);
	
	$rn=mysql_fetch_object($resultat);
	
	$dt=$rn->typdoc;
	$bl=$rn->niveau_biblio;
	
	$notice.="<notice>\n";
	$notice.="  <rs>n</rs>\n";
  	$notice.="  <dt>".$dt."</dt>\n";
  	$notice.="  <bl>".$bl."</bl>\n";
  	$notice.="  <hl>*</hl>\n";
  	$notice.="  <el>1</el>\n";
  	$notice.="  <ru>i</ru>\n";
	
	
	//ISBN ou autre et PRIX
	if ($rn->code || $rn->prix ) {
		$notice_prix_code_temp="";
		if ($rn->code) $notice_prix_code_temp.="    <s c='a'>".htmlspecialchars($rn->code)."</s>\n";
		if ($rn->prix) $notice_prix_code_temp.="    <s c='d'>".htmlspecialchars($rn->prix)."</s>\n";
		if ($notice_prix_code_temp)
			$notice.="  <f c='010'>\n".$notice_prix_code_temp."  </f>\n";
	}
	
	//Langues
	$notice_langue_temp="";
	$notice_org_langue_temp="";
	$rqttmp_lang = "select code_langue from notices_langues where num_notice='$rn->notice_id' and type_langue=0 ";
	$restmp_lang = mysql_query($rqttmp_lang);
	while ($tmp_lang = mysql_fetch_object($restmp_lang)) $notice_langue_temp.="    <s c='a'>".htmlspecialchars($tmp_lang->code_langue)."</s>\n";  

	$rqttmp_lang = "select code_langue from notices_langues where num_notice='$rn->notice_id' and type_langue=1 ";
	$restmp_lang = mysql_query($rqttmp_lang);
	while ($tmp_lang = mysql_fetch_object($restmp_lang)) $notice_org_langue_temp.="    <s c='c'>".htmlspecialchars($tmp_lang->code_langue)."</s>\n";

	if ($notice_langue_temp || $notice_org_langue_temp) {
		$notice.="  <f c='101'>\n";
		$notice.=$notice_langue_temp ;
		$notice.=$notice_org_langue_temp ;
		$notice.="  </f>\n";
		}
	
	//Titres
	if ($rn->tit1) {
		$notice.="  <f c='200'>\n";
	    $notice.="    <s c='a'>".htmlspecialchars($rn->tit1)."</s>\n";
		if ($rn->tit2) {
		    $notice.="    <s c='c'>".htmlspecialchars($rn->tit2)."</s>\n";
		}
		if ($rn->tit3) {
		    $notice.="    <s c='d'>".htmlspecialchars($rn->tit3)."</s>\n";
		}
		if ($rn->tit4) {
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

	//Editeur
	if ($rn->ed1_id) {
	    $requete="select * from publishers where ed_id=".$rn->ed1_id;
		$resultat=mysql_query($requete);
		$red=mysql_fetch_object($resultat);
		$notice.="  <f c='210' ind='  '>\n";
		$notice.="    <s c='c'>".htmlspecialchars($red->ed_name)."</s>\n";
		if ($red->ed_ville) $notice.="    <s c='a'>".htmlspecialchars($red->ed_ville)."</s>\n";
		//Year
		if ($rn->year) {
			$notice.="    <s c='d'>".htmlspecialchars($rn->year)."</s>\n";
		}
		$notice.="  </f>\n";
	}
	if ($rn->ed2_id) {
	    $requete="select * from publishers where ed_id=".$rn->ed2_id;
		$resultat=mysql_query($requete);
		$red=mysql_fetch_object($resultat);
		$notice.="  <f c='210' ind='  '>\n";
		$notice.="    <s c='c'>".htmlspecialchars($red->ed_name)."</s>\n";
		if ($red->ed_ville) $notice.="    <s c='a'>".htmlspecialchars($red->ed_ville)."</s>\n";
		$notice.="  </f>\n";
	}
		
	//Collation
	if ($rn->npages || $rn->ill || $rn->size || $rn->accomp) {
	    $notice.="  <f c='215'>\n";
	    if ($rn->npages) $notice.="    <s c='a'>".htmlspecialchars($rn->npages)."</s>\n";
		if ($rn->ill)    $notice.="    <s c='c'>".htmlspecialchars($rn->ill)."</s>\n";
		if ($rn->size)   $notice.="    <s c='d'>".htmlspecialchars($rn->size)."</s>\n";
		if ($rn->accomp) $notice.="    <s c='e'>".htmlspecialchars($rn->accomp)."</s>\n";
		$notice.="  </f>\n";
		}
	
	//Collection
	if ($rn->coll_id) {
		$requete="select collection_name from collections where collection_id=".$rn->coll_id;
		$resultat=mysql_query($requete);
		$notice.="  <f c='225'>\n";
		$notice.="    <s c='a'>".htmlspecialchars(mysql_result($resultat,0,0))."</s>\n";	
		//sous-collection
		if ($rn->subcoll_id){
			$requete="select * from sub_collections where sub_coll_id=".$rn->subcoll_id;
			$resultat=mysql_query($requete);		    
			$subcoll=mysql_fetch_object($resultat);
			$notice.="    <s c='i'>".htmlspecialchars($subcoll->sub_coll_name)."</s>\n";
		}
		if ($rn->nocoll) $notice.="    <s c='v'>".htmlspecialchars($rn->nocoll)."</s>\n";
		$notice.="  </f>\n";
	}
	
	//Notes
	//Générale
	if ($rn->n_gen) {
	    $notice.="  <f c='300'>\n";
		$notice.="    <s c='a'>".htmlspecialchars($rn->n_gen)."</s>\n";
		$notice.="  </f>\n";
	}
	//de contenu
	if ($rn->n_contenu) {
	    $notice.="  <f c='327'>\n";
		$notice.="    <s c='a'>".htmlspecialchars($rn->n_contenu)."</s>\n";
		$notice.="  </f>\n";
	}
	//Résumé
	if ($rn->n_resume) {
	    $notice.="  <f c='330'>\n";
		$notice.="    <s c='a'>".htmlspecialchars($rn->n_resume)."</s>\n";
		$notice.="  </f>\n";
	}
	
	//dewey
	if ($rn->indexint) {
		$requete = "select * from indexint where indexint_id=".$rn -> indexint;
		$resultat = mysql_query($requete);
		if ($code_dewey=mysql_fetch_object($resultat)) {
			$notice.="  <f c='676'>\n";
			$notice.="    <s c='a'>".htmlspecialchars( $code_dewey -> indexint_name)."</s>\n";
			$notice.="    <s c='l'>".htmlspecialchars( $code_dewey -> indexint_comment)."</s>\n";
			$notice.="  </f>\n";
			}
	}
	
	//Titre de série
	$serie="";
	if ($rn->tparent_id!=0 || $rn->tnvol!==false) {
		$requete="select serie_name from series where serie_id=".$rn->tparent_id;
		$resultat=mysql_query($requete);
		if (mysql_num_rows($resultat)) $serie=mysql_result($resultat,0,0);
		$notice_461temp="";
		if ($serie!=="") $notice_461temp.="    <s c='t'>".htmlspecialchars($serie)."</s>\n";
		if ($rn->tnvol) $notice_461temp.="    <s c='v'>".htmlspecialchars($rn->tnvol)."</s>\n";
		if ($notice_461temp) $notice.="  <f c='461' ind='  '>\n".$notice_461temp."  </f>\n";
	}
	
	//Auteurs
	$requete="select author_name, author_rejete, author_type, responsability_fonction, responsability_type from authors, responsability where responsability_notice=$id and responsability_author=author_id order by author_type, responsability_fonction, responsability_type ";
	$resultat=mysql_query($requete);
	if (mysql_num_rows($resultat)) {
		for ($i=0; $i<mysql_num_rows($resultat); $i++) {
		$resptype=mysql_result($resultat,$i, 4);
		$prenom=mysql_result($resultat,$i, 1);
	    if (!$resptype) {
		//Auteur principal
		$notice.="  <f c='700' ind='  '>\n";
		
		if (!$prenom) {
			$notice.="    <s c='a'>".htmlspecialchars(mysql_result($resultat,$i, 0))."</s>\n";			
		} 
		else {
		$notice.="    <s c='a'>".htmlspecialchars(mysql_result($resultat,$i, 0))."</s>\n";
		$notice.="    <s c='b'>".htmlspecialchars(mysql_result($resultat,$i, 1))."</s>\n";
		}
		$notice.="    <s c='4'>".htmlspecialchars(mysql_result($resultat,$i, 3))."</s>\n";
		$notice.="  </f>\n";		
		}
		if ($resptype==1) {
		//Co-auteurs
		$notice.="  <f c='701' ind='  '>\n";
			if (!$prenom) {				
			$notice.="    <s c='a'>".htmlspecialchars(mysql_result($resultat,$i, 0))."</s>\n";
			} 
			else {
			$notice.="    <s c='a'>".htmlspecialchars(mysql_result($resultat,$i, 0))."</s>\n";			
			$notice.="    <s c='b'>".htmlspecialchars(mysql_result($resultat,$i, 1))."</s>\n";
			}
			$notice.="    <s c='4'>".htmlspecialchars(mysql_result($resultat,$i, 3))."</s>\n";
			$notice.="  </f>\n";		
		}
		if ($resptype==2) {
		//Auteurs secondaires
		$notice.="  <f c='702' ind='  '>\n";
		if (!$prenom) {
				$notice.="    <s c='a'>".htmlspecialchars(mysql_result($resultat,$i, 0))."</s>\n";
		}
		else {
		$notice.="    <s c='a'>".htmlspecialchars(mysql_result($resultat,$i, 0))."</s>\n";				
		$notice.="    <s c='b'>".htmlspecialchars(mysql_result($resultat,$i, 1))."</s>\n";
		}
		$notice.="    <s c='4'>".htmlspecialchars(mysql_result($resultat,$i, 3))."</s>\n";
		$notice.="  </f>\n";
		}
		}
	}	
	
	//Lien
	if ($rn->lien) {
	    $notice.="  <f c='856'>\n";
		$notice.="    <s c='u'>".htmlspecialchars($rn->lien)."</s>\n";
		if ($rn->eformat) $notice.="    <s c='q'>".htmlspecialchars($rn->eformat)."</s>\n";
		$notice.="  </f>\n";
	}
	
	
	//Périodique
	if ($rn->niveau_biblio=="a") {
		//Récupération du titre du périodique
		$requete="select tit1,bulletin_numero,bulletin_notice,mention_date, date_date, bulletin_titre from notices, bulletins, analysis where analysis_notice=$id and analysis_bulletin=bulletin_id and bulletin_notice=notice_id";
		$resultat=mysql_query($requete);
		$r_bull=@mysql_fetch_object($resultat);
		$data_bull="";
		if (($r_bull)&&($r_bull->tit1)) {
			if ($r_bull->tit1) $data_bull.="    <s c='t'>".htmlspecialchars($r_bull->tit1)."</s>\n";
			if ($r_bull->bulletin_numero) $data_bull.="	  <s c='v'>".htmlspecialchars($r_bull->bulletin_numero)."</s>\n";
			if ($r_bull->mention_date) $data_bull.="    <s c='d'>".htmlspecialchars($r_bull->mention_date)."</s>\n";
			if ($r_bull->bulletin_titre) $data_bull.="    <s c='u'>".htmlspecialchars($r_bull->bulletin_titre)."</s>\n";
			if ($r_bull->date_date) $data_bull.="    <s c='e'>".htmlspecialchars($r_bull->date_date)."</s>\n";
			if ($rn->npages) $data_bull.="    <s c='p'>".htmlspecialchars($rn->npages)."</s>\n";
		}
		if ($data_bull) $notice.="  <f c='464'>\n".$data_bull."  </f>\n";
	}
	
	//Mots_clés
	if ($rn->index_l) {
	    $notice.="  <f c='610'>\n";
		$notice.="    <s c='a'>".htmlspecialchars($rn->index_l)."</s>\n";
		$notice.="  </f>\n";
	}
	
	$requete="SELECT libelle_categorie FROM categories, notices_categories WHERE notcateg_notice=$id and categories.num_noeud = notices_categories.num_noeud ORDER BY ordre_categorie";
	$resultat=mysql_query($requete);
	
	//Descripteurs
	if (mysql_num_rows($resultat)) {
	    for ($i=0; $i<mysql_num_rows($resultat); $i++) {
			$notice.="  <f c='606'>\n";
			$notice.="     <s c='a'>".htmlspecialchars(mysql_result($resultat,$i))."</s>\n";
			$notice.="  </f>\n";
		}
	}
		
	//Thème(s) 
	$requete="select ncl.notices_custom_list_lib from notices_custom_lists ncl, notices_custom_values ncv, notices_custom nc where ncv.notices_custom_origine=$id and ncv.notices_custom_champ=nc.idchamp and name='theme' and ncv.notices_custom_champ=ncl.notices_custom_champ and ncv.notices_custom_integer=ncl.notices_custom_list_value";
	$resultat=mysql_query($requete);
	if (mysql_num_rows($resultat)) {
	    for ($i=0; $i<mysql_num_rows($resultat); $i++) {
			$notice.="  <f c='900'>\n";
			$notice.="    <s c='a'>".htmlspecialchars(mysql_result($resultat,$i))."</s>\n";
			$notice.="  </f>\n";
		}
	}
	//Genre(s)
	$requete="select ncl.notices_custom_list_lib from notices_custom_lists ncl, notices_custom_values ncv, notices_custom nc where ncv.notices_custom_origine=$id and ncv.notices_custom_champ=nc.idchamp and name='genre' and ncv.notices_custom_champ=ncl.notices_custom_champ and ncv.notices_custom_integer=ncl.notices_custom_list_value";
	$resultat=mysql_query($requete);
	if (mysql_num_rows($resultat)) {
	    for ($i=0; $i<mysql_num_rows($resultat); $i++) {
			$notice.="  <f c='901'>\n";
			$notice.="    <s c='a'>".htmlspecialchars(mysql_result($resultat,$i))."</s>\n";
			$notice.="  </f>\n";
		}
	}
	//Niveau
	$requete="select ncl.notices_custom_list_lib from notices_custom_lists ncl, notices_custom_values ncv, notices_custom nc where ncv.notices_custom_origine=$id and ncv.notices_custom_champ=nc.idchamp and name='niveau' and ncv.notices_custom_champ=ncl.notices_custom_champ and ncv.notices_custom_integer=ncl.notices_custom_list_value";
	$resultat=mysql_query($requete);
	if (mysql_num_rows($resultat)) {
	    for ($i=0; $i<mysql_num_rows($resultat); $i++) {
			$notice.="  <f c='906'>\n";
			$notice.="    <s c='a'>".htmlspecialchars(mysql_result($resultat,$i))."</s>\n";
			$notice.="  </f>\n";
		}
	}
	//Discipline
	$requete="select ncl.notices_custom_list_lib from notices_custom_lists ncl, notices_custom_values ncv, notices_custom nc where ncv.notices_custom_origine=$id and ncv.notices_custom_champ=nc.idchamp and name='discipline' and ncv.notices_custom_champ=ncl.notices_custom_champ and ncv.notices_custom_integer=ncl.notices_custom_list_value";
	$resultat=mysql_query($requete);
	if (mysql_num_rows($resultat)) {
	    for ($i=0; $i<mysql_num_rows($resultat); $i++) {
			$notice.="  <f c='902'>\n";
			$notice.="    <s c='a'>".htmlspecialchars(mysql_result($resultat,$i))."</s>\n";
			$notice.="  </f>\n";
		}
	}
	//Année de péremption
	$requete="select ncv.notices_custom_integer from notices_custom_values ncv, notices_custom nc where ncv.notices_custom_origine=$id and ncv.notices_custom_champ=nc.idchamp and name='annee_peremption'";
	$resultat=mysql_query($requete);
	if (mysql_num_rows($resultat)) {
	    $notice.="  <f c='903'>\n";
		$notice.="    <s c='a'>".htmlspecialchars(mysql_result($resultat,0))."</s>\n";
		$notice.="  </f>\n";
	}
	//Date de saisie
	$requete="select ncv.notices_custom_date from notices_custom_values ncv, notices_custom nc where ncv.notices_custom_origine=$id and ncv.notices_custom_champ=nc.idchamp and name='date_creation'";
	$resultat=mysql_query($requete);
	if (mysql_num_rows($resultat)) {
			$notice.="  <f c='904'>\n";
			$notice.="    <s c='a'>".htmlspecialchars(mysql_result($resultat,0))."</s>\n";
			$notice.="  </f>\n";
		}
	//Type doc
	$requete="select ncl.notices_custom_list_lib from notices_custom_lists ncl, notices_custom_values ncv, notices_custom nc where ncv.notices_custom_origine=$id and ncv.notices_custom_champ=nc.idchamp and name='type_nature' and ncv.notices_custom_champ=ncl.notices_custom_champ and ncv.notices_custom_integer=ncl.notices_custom_list_value";
	$resultat=mysql_query($requete);
	if (mysql_num_rows($resultat)) {
	    $notice.="  <f c='905'>\n";
		$notice.="    <s c='a'>".htmlspecialchars(mysql_result($resultat,0))."</s>\n";
		$notice.="  </f>\n";
	}
	//Origine
	$requete="select orinot_nom from notices, origine_notice where notice_id=$id and origine_catalogage=orinot_id";
	$resultat=mysql_query($requete);
	if (mysql_num_rows($resultat)) {
	    $notice.="  <f c='801'>\n";
		$notice.="    <s c='b'>".htmlspecialchars(mysql_result($resultat,0))."</s>\n";
		$notice.="  </f>\n";
	}	
	
	// Ajout, eventuel, des exemplaires :
	if($keep_expl) {
		$requete = "select expl_cb, expl_typdoc, expl_cote, expl_section, expl_statut, expl_note, expl_comment from exemplaires where expl_notice = $id";
		$resultat = mysql_query($requete);
		$nb = mysql_num_rows($resultat);
		for($i=0; $i < $nb ; $i++) {
			$expl =@mysql_fetch_object($resultat);
			$notice.="  <f c='995'>\n";
			if($expl->expl_cb && $expl->expl_cb != "") {
				$notice.="    <s c='f'>".htmlspecialchars($expl->expl_cb)."</s>\n";
			}
			if($expl->expl_typdoc && $expl->expl_typdoc != "") {
				$notice.="    <s c='r'>".htmlspecialchars($expl->expl_typdoc)."</s>\n";
			}
			if($expl->expl_cote && $expl->expl_cote != "") {
				$notice.="    <s c='k'>".htmlspecialchars($expl->expl_cote)."</s>\n";
			}
			if($expl->expl_section && $expl->expl_section != "") {
				$notice.="    <s c='t'>".htmlspecialchars($expl->expl_section)."</s>\n";
			}
			if($expl->expl_statut && $expl->expl_statut != "") {
				$notice.="    <s c='q'>".htmlspecialchars($expl->expl_statut)."</s>\n";
			}
			if($expl->expl_note && $expl->expl_note != "") {
				$notice.="    <s c='u'>".htmlspecialchars($expl->expl_note)."</s>\n";
			}
			$notice.="  </f>\n";
		}
	}
	
	$notice.="</notice>\n";
	return $notice;
}

?>