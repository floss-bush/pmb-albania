<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice_tpl.inc.php,v 1.15.2.7 2011-10-07 14:13:05 arenou Exp $
require_once ($include_path . "/misc.inc.php");

$func_format['b_empty']= aff_b_empty;
$func_format['a_empty']= aff_a_empty;
$func_format['not_empty']= aff_not_empty;
$func_format['if']= aff_if;
$func_format['gen_tpl']=aff_gen_tpl;
$func_format['gen_plus']= aff_gen_plus;
$func_format['replace']= replace_str;

$func_format['isbd']= aff_isbd;
$func_format['title']= aff_title;
$func_format['parallel_title']= aff_parallel_title;
$func_format['authors']= aff_auteurs;
$func_format['author']= aff_auteur_principal;
$func_format['author_1']= aff_auteur_autre;
$func_format['author_2']= aff_auteur_secondaire;
$func_format['publisher']= aff_ed1;
$func_format['publisher_1']= aff_ed2;
$func_format['year_publication']= aff_year_publication;
$func_format['date_publication']= aff_date_publication;
$func_format['resume']= aff_resume;
$func_format['contenu']= aff_contenu;
$func_format['note']= aff_note;
$func_format['categories']= aff_categories;
$func_format['header_link']= aff_header_link;
$func_format['is_article']= aff_is_article;
$func_format['is_serial']= aff_is_serial;
$func_format['is_bull']= aff_is_bull;
$func_format['is_mono']= aff_is_mono;
$func_format['nom_revue']=aff_nom_revue;
$func_format['date_bulletin']=aff_date_bulletin;
$func_format['numero_bulletin']=aff_numero_bulletin;
$func_format['expl_num']=aff_expl_num;

$func_format['isbn']=aff_isbn;
$func_format['issn']=aff_issn;
$func_format['img_url']=aff_img_url;
$func_format['img']=aff_img;
$func_format['get_expl']=get_expl;
$func_format['collection']=aff_collection;
$func_format['collation']=aff_collation;
$func_format['page']=aff_page;
$func_format['lang']=aff_lang;
$func_format['lang_or']=aff_lang_or;
$func_format['cost']=aff_cost;
$func_format['url']=aff_url;
$func_format['p_perso']=aff_p_perso;
$func_format['notice_field']=aff_notice_field;

$func_format['extract_path']=aff_extract_path;
$func_format['format_date']=aff_format_date;
$func_format['trim']=aff_trim;
$func_format['substr']=aff_substr;
$func_format['ifequal']=aff_ifequal;
$func_format['lastchr']=aff_lastchr;

$func_format['publisher_name']=aff_publisher_name;
$func_format['publisher_place']=aff_publisher_place;
$func_format['mention_edition']=aff_mention_edition;
$func_format['get_notice_tpl']=aff_get_notice_tpl;

$func_format['get_parents_in_tpl']=aff_get_parents_in_tpl;
$func_format['get_childs_in_tpl']=aff_get_childs_in_tpl;

$func_format['authors_by_type']=aff_authors_by_type;
$func_format['authors_by_type_dir']=aff_authors_by_type_dir;

$func_format['permalink']=aff_permalink;
$parser_environnement = array();

function replace_str($param) {
    global $parser_environnement;
    if(!$parser_environnement['id_notice']) return "";
    $notice=gere_global();   
    return str_replace("!!".$param[1]."!!", $notice['notice_info']->$param[1], $param[0]);     
}

function aff_p_perso($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();	
	if(!$param[1]) $field="VALUE";
	else $field="TITRE";
	return $notice['notice_info']->parametres_perso[$param[0]][$field];	
}

function aff_notice_field($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();	
	return $notice['notice_info']->notice->$param[0];	
}

function aff_url($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();	
	return $notice['notice_info']->notice->lien;	
}

function aff_page($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();	
	return $notice['notice_info']->notice->npages;	
}

function aff_cost($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();	
	return $notice['notice_info']->notice->prix;	
}

function aff_collation($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();	
	return $notice['notice_info']->memo_collation;	
}

function aff_lang($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();	
	$display=array();
	foreach($notice['notice_info']->memo_lang as $line){
		$display[]=$line['langue'];
	}
	if(!$param[0]) $sep="; "; else $sep=$param[0];
	return implode ($sep,$display);		
}

function aff_lang_or($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();	
	$display=array();
	foreach($notice['notice_info']->memo_lang_or as $line){
		$display[]=$line['langue'];
	}
	if(!$param[0]) $sep="; "; else $sep=$param[0];
	return implode ($sep,$display);		
}

function aff_collection($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();	
	return $notice['notice_info']->memo_collection;
}

function aff_gen_tpl($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();	
	foreach($param[0] as $line){
		$tpl=$param[1];
		$tpl=str_replace("!!parity!!", ($i++&1) ? "odd" : "even", $tpl) ;
		foreach($line as $key=>$val){			
			$tpl=str_replace("!!$key!!", $val, $tpl);		
			$tpl=str_replace("!!p_perso_$key!!", $val, $tpl);	
		}			
		// p_perso de la notice
		while(($p_perso=strstr($tpl,"p_perso_notice"))){
			$pos_end=strpos($p_perso,"!!");
			$name=substr($p_perso,0,$pos_end);
			$name=substr($name,14);
			$val= $notice['notice_info']->parametres_perso[$name]["VALUE"];	
			$tpl=str_replace("!!p_perso_notice_$name!!", $val, $tpl);			
		}		
		// p_perso de l'exemplaire 	
		while(($p_perso=strstr($tpl,"p_perso_"))){
			$pos_end=strpos($p_perso,"!!");
			$name=substr($p_perso,0,$pos_end);
			$name=substr($name,8);
			
			$val= $line->parametres_perso[$name]["VALUE"];	
			$tpl=str_replace("!!p_perso_$name!!", $val, $tpl);			
		}	
		$display.=$tpl;
	}
	return $display;
}

function get_expl($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();	
	if($notice['notice_info']->memo_notice_type==1) return "";
	return $notice['notice_info']->memo_exemplaires;
}

function aff_img_url($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();	
	return $notice['notice_info']->memo_url_image;
}

function aff_img($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();	
	return $notice['notice_info']->memo_image;
}	
		
function aff_issn($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();
	return $notice['notice_info']->memo_isbn;
}

function aff_isbn($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();
	return $notice['notice_info']->memo_isbn;
}
function aff_ed1($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();
	return $notice['notice_info']->memo_ed1;
}

function aff_ed2($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();
	
	return $notice['notice_info']->memo_ed2;
}

function aff_year_publication($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();
	
	return $notice['notice_info']->memo_year;
}

function aff_date_publication($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();
	
	return $notice['notice_info']->memo_date;
}

/*

 * Minimaliste
<b>
	#header_link(#title(); #a_empty(#author();, / );,2);
</b>
<br />
<span class="resume">
	#resume();
</span>
<br />
<span class="source">
#if(#is_article();,
#nom_revue(); - #date_publication();,
#publisher(); #a_empty(#year_publication();, - ););
</span>
 
***********************
  Exemple pour l'ARNT
***********************
<h2>
#header_link(#title();,2);
</h2>
<br />
<p class="resume">
	#resume();
</p>
<p class="source">
#b_empty(#author();#b_empty(#author_1();, - );,<br />);
#if(#is_article();,
#nom_revue();#a_empty(#numero_bulletin();, - ); - #date_bulletin();,
#b_empty(#publisher();, - ); #year_publication(););
</p>


#gen_plus(
#header_link(#title(); #a_empty(#author();, / );,2);
,

<span class="resume">#resume();</span>
<br>
<span class="source">
#if(#is_article();,
#nom_revue(); - #date_publication();,
#publisher(); #a_empty(#year_publication();, - ););
</span>
);

 * 
 */

function gere_global(){
	global $notice_data,$parser_environnement;
	
	if(!$notice_data[$parser_environnement['id_notice']]['notice_info']) {
		$notice_data[$parser_environnement['id_notice']] ['notice_info']= new notice_info($parser_environnement['id_notice']);
	}	
	return	$notice_data[$parser_environnement['id_notice']];
}

function aff_gen_plus($param) {
	global $parser_environnement;

	if($param[2]) $max=" startOpen=\"Yes\""; else $max='';
	return"
	<script type='text/javascript' src='./javascript/tablist.js'></script>

	<div class='row'></div>
	<div id='".$parser_environnement['id_notice']."' class='notice-parent'>
		<img src='./images/plus.gif' class='img_plus' name='imEx' id='".$parser_environnement['id_notice']."Img' title='".addslashes($msg['plus_detail'])."' border='0' onClick=\"expandBase('".$parser_environnement['id_notice']."', true); return false;\" hspace='3'>
		<span class='notice-heada'>
			".$param[0]."
		</span>
	</div>
	<div id='".$parser_environnement['id_notice'].""."Child' class='notice-child' style='margin-bottom:6px;display:none;width:94%' $max>
		".$param[1]."
	</div>
	";
	
}

function aff_b_empty($param) {
	if($param[0]) {
		return $param[0].$param[1];
	}
	return "";
}

function aff_a_empty($param) {
	if($param[0]) {
		return $param[1].$param[0];
	}
	return "";
}

function aff_not_empty($param) {
	if($param[0]) {
		return $param[1];
	} else return $param[0];
	return "";
}

function aff_if($param) {
	if($param[0]) {
		return $param[1];
	} else return $param[2];
	return "";
}

function aff_is_article($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();
	
	if (($notice['notice_info']->niveau_biblio=="a")&&($notice['notice_info']->niveau_hierar==2)) return 1; else return 0;
}

function aff_is_serial($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();	
	if (($notice['notice_info']->niveau_biblio=="s")&&($notice['notice_info']->niveau_hierar==1)) return 1; else return 0;
}

function aff_is_mono($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();	
	if (($notice['notice_info']->niveau_biblio=="m")&&($notice['notice_info']->niveau_hierar==0)) return 1; else return 0;
}

function aff_is_bull($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();	
	if (($notice['notice_info']->niveau_biblio=="b")&&($notice['notice_info']->niveau_hierar==2)) return 1; else return 0;
}

function aff_isbd($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();
	
	return $notice['notice_info']->isbd;
}

function aff_title($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();
	return $notice['notice_info']->memo_titre;
}

function aff_parallel_title($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();

	return $notice['notice_info']->memo_titre_parallele;
}

function aff_auteur_principal($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();
	return $notice['notice_info']->memo_auteur_principal;
}

function aff_auteur_autre($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();
	if($param[0]) $sep = $param[0];
	else $sep= " ; ";
	if($param[1]){
		for($i=0 ; $i < $param[1] ; $i++){
			$aut[]=$notice['notice_info']->memo_auteur_autre_tab[$i];
		}
		if(count($notice['notice_info']->memo_auteur_autre_tab) > $param[$i]) $aut[] = "et al.";
		return implode($sep,$aut);
	}
	return implode($sep,$notice['notice_info']->memo_auteur_autre_tab);
}

function aff_auteur_secondaire($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();
	if($param[0]) $sep = $param[0];
	else $sep= " ; ";
	if($param[1]){
		for($i=0 ; $i < $param[1] ; $i++){
			$aut[]=$notice['notice_info']->memo_auteur_autre_tab[$i];
		}
		if(count($notice['notice_info']->memo_auteur_autre_tab) > $param[$i]) $aut[] = "et al.";
		return implode($sep,$aut);
	}	
	return implode($sep,$notice['notice_info']->memo_auteur_secondaire_tab);	
}


// Travail ER
function aff_auteurs($param) {
	global $fonction_auteur;
	// $param[0] = 0=principal seul, 1=principal+autres, 2=tous
	// $param[1] = nombre maxi d'auteurs à afficher
	// $param[2] = séparateur entre auteurs
	// $param[3] = séparateur entre principal/autres/secondaires
	// $param[4] = afficher la fonction : 0=non, 1=toujours
	// $param[5] = afficher "et al." si plus d'auteurs que le maxi
	global $parser_environnement, $dbh;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();
	$rqt_count="select count(*) as nb from responsability where responsability_notice='".$parser_environnement['id_notice']."' ";
	$res_sql_count = mysql_query($rqt_count, $dbh);
	$res_count=mysql_fetch_object($res_sql_count);
	$rqt = "select author_id, responsability_fonction, responsability_type 
			from responsability, authors 
			where responsability_notice='".$parser_environnement['id_notice']."' 
				and responsability_author=author_id
				and responsability_type<='".$param[0]."'  
			order by responsability_type, responsability_ordre " ;
	if ($param[1]>0) $rqt .= " limit 0,".$param[1] ; 
	$res_sql = mysql_query($rqt, $dbh);
	while ($authors=mysql_fetch_object($res_sql)) {
		$aut_detail=new auteur($authors->author_id);
		if ($authors->responsability_fonction && $param[4]==1) $aut_detail->isbd_entry .= ", ".$fonction_auteur[$authors->responsability_fonction];
		if ($authors->responsability_type==0) $aut[]=$aut_detail->isbd_entry;
		if ($authors->responsability_type==1) $aut1[]=$aut_detail->isbd_entry;
		if ($authors->responsability_type==2) $aut2[]=$aut_detail->isbd_entry;
		}
	if (count($aut1)) $aut[]=implode($param[2],$aut1);
	if (count($aut2)) $aut[]=implode($param[2],$aut2);
	if ($param[1]>0 && $param[5] && $res_count->nb>$param[1]) $aut[]="et al.";
	if (count($aut)) return implode($param[3],$aut);
	
	return "";
}

function aff_resume($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();
	
	return nl2br($notice['notice_info']->notice->n_resume);
}
function aff_contenu($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();
	
	return nl2br($notice['notice_info']->notice->n_contenu);
}
function aff_note($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();
	
	return nl2br($notice['notice_info']->notice->n_gen);
}

function aff_categories($param) {
	// $param[0] = 0=tous thésaurus, sinon thesaurus id=$param[0]
	// $param[1] = séparateur entre categories
	// $param[2] = séparateur entre thesaurus
	// $param[3] = langue à prendre en compte
	// $param[4] = afficher le nom du ou des thesaurus en entête, 0 ou 1
	
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();

	//Descripteurs
	if ($param[0]>0) $restrict_thes=" and catlg.num_thesaurus='".$param[0]."' and catdef.num_thesaurus='".$param[0]."' ";
	
	$requete="SELECT libelle_thesaurus as thesnom, if(catlg.libelle_categorie is not null,catlg.libelle_categorie,catdef.libelle_categorie) as categnom FROM notices_categories left join categories catlg on (catlg.num_noeud = notices_categories.num_noeud and catlg.langue='".$param[3]."') left join categories catdef on (catdef.num_noeud = notices_categories.num_noeud), thesaurus thesdef  where catdef.num_thesaurus=thesdef.id_thesaurus and notcateg_notice='".$parser_environnement['id_notice']."' and (catdef.langue=thesdef.langue_defaut or catdef.langue is null) $restrict_thes ORDER BY libelle_thesaurus, ordre_categorie";
	$resultat=mysql_query($requete);
	$thes_conserve="";$res="";
	$juste_apresthes=true;
	while (($cat = mysql_fetch_object($resultat))) {
		if ($thes_conserve!=$cat->thesnom) {
			if (!$res && $param[4]) $res="[".$cat->thesnom."] ";
			elseif ($param[4]) $res.=$param[2]."[".$cat->thesnom."] ";
			elseif ($res) $res.=$param[2];
			$thes_conserve=$cat->thesnom;
			$juste_apresthes=true;
		}
		if ($juste_apresthes) {
			$res.=$cat->categnom;
			$juste_apresthes=false;
		} elseif ($res) $res.=$param[1].$cat->categnom;
		else $res.=$cat->categnom;
	}
	return $res;

}

function aff_header_link($param) {
	global $pmb_opac_url,$use_opac_url_base,$opac_url_base;
	
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();	
	switch($param[1]){
		case 2:
			if($notice['notice_info']->notice->lien) {
				$libelle="<a href=\"".$notice['notice_info']->notice->lien."\">".$param[0];
				if (!$use_opac_url_base) $libelle.= "<img src=\"./images/globe.gif\" border=\"0\" align=\"middle\" hspace=\"3\"";
				else	 $libelle.= "<img src=\"".$pmb_opac_url."images/globe.gif\" border=\"0\" align=\"middle\" hspace=\"3\"";
				$libelle.= "alt=\"". $notice['notice_info']->notice->eformat. "\" title=\"". $notice['notice_info']->notice->eformat ."\">";
				$libelle.="</a>";
			} else	{
				$libelle="<a href=\"".$pmb_opac_url."?lvl=notice_display&id=".$parser_environnement['id_notice']."&code=!!code!!&emprlogin=!!login!!&date_conex=!!date_conex!!\">$param[0]</a>";
			}
		break;
		default:
			$libelle="<a href=\"".$pmb_opac_url."?lvl=notice_display&id=".$parser_environnement['id_notice']."&code=!!code!!&emprlogin=!!login!!&date_conex=!!date_conex!!\">$param[0]</a>";
		break;
	}
	return $libelle;
}

function aff_nom_revue($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "Voila";
	$notice=gere_global();
	return $notice["notice_info"]->serial_title;
}

function aff_date_bulletin($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "1er janvier 1970";
	$notice=gere_global();
	if ($notice["notice_info"]->bulletin_mention_date) $format=$notice["notice_info"]->bulletin_mention_date." (%s)"; else $format="%s";
	return sprintf($format,$notice["notice_info"]->bulletin_date_date);
}

function aff_numero_bulletin($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "1er janvier 1970";
	$notice=gere_global();
	
	return $notice["notice_info"]->bulletin_numero;
}

function aff_expl_num($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "1er janvier 1970";
	$notice=gere_global();
	
	return $notice["notice_info"]->memo_explnum_assoc;
}

/*
 * $param[0] : pattern
 * $param[1] : chaine 
 */
function aff_extract_path($param){
	if(preg_match("\"".$param[0]."\"",$param[1],$output)){;
		return $output[1];
	}else return "";
}

/*
 * $param[0] : date
 * $param[1] : format 
 */
function aff_format_date($param){
	//si c'est pas une date potable, on arrete là...
	if(!preg_match(getDatePattern(),$param[1]) && !preg_match(getDatePattern("short"),$param[1]) && !preg_match(getDatePattern("year"),$param[1])){
		return $param[1];
	}
	$date = detectFormatDate($param[1]);
	$year = substr($date,0,4);
	$month = substr($date,5,2);
	$day = substr($date,8,2);
	return date($param[0],mktime(0,0,0,$month,$day,$year));
}

function aff_trim($param){
	return trim($param[0]);	
}

function aff_substr($param){
	if($param[1] && $param[2]) $sc =substr($param[0],$param[1],$param[2]);
	else if($param[1] && !$param[2]) $sc =substr($param[0],$param[1]);
	else $sc = "";
	return $sc; 
}

function aff_lastchr($param){
	return substr($param[0],strlen($param[0])-1);
}

/*
 * $param[0],$param[1] : chaines à comparer
 * $param[2] : valeur si égale
 * $param[3] : valeur si différente
 * 
 */
function aff_ifequal($param){
	if($param[0] == $param[1]) return $param[2];
	else return $param[3];
}

function aff_publisher_name($param){
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();
	return $notice['notice_info']->memo_ed1_name;
}

function aff_publisher_place($param){
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();
	return $notice['notice_info']->memo_ed1_place;
}

function aff_mention_edition($param){
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();
	return $notice['notice_info']->memo_mention_edition;
}

function aff_get_notice_tpl($param){
	global $parser_environnement;
	global $deflt2docs_location;
	$id_notice = $parser_environnement['id_notice'];
	$template_notice = new notice_tpl_gen($parser_environnement['id_template']);
	$notice = $template_notice->build_notice($param[0],$deflt2docs_location,true);
	$parser_environnement['id_notice']=$id_notice;
	return $notice;
}

function aff_get_parents_in_tpl($param){
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();
	$result="";
	foreach($notice['notice_info']->memo_notice_mere as $parent){
		$result.= " In : ".aff_get_notice_tpl(array($parent));
	}
	return $result;
}

function aff_get_childs_in_tpl($param){
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();
	$result="";
	foreach($notice['notice_info']->memo_notice_fille as $child){
		$result.= aff_get_notice_tpl(array($child));
	}
	return $result;
}

function aff_authors_by_type($param){
	global $fonction_auteur;
	// $param[0] = 0=principal seul, 1=principal+autres, 2=tous	
	// $param[1] = nombre maxi d'auteurs à afficher
	// $param[2] = séparateur entre auteurs
	// $param[3] = séparateur entre principal/autres/secondaires
	// $param[4] = afficher la fonction : 0=non, 1=toujours
	// $param[5] = afficher "et al." si plus d'auteurs que le maxi
	// $param[6] = 70=physique, 71=collectivités, 72=congrès (séparé parune virgule...)
	global $parser_environnement, $dbh;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();
	
	$param[6] = explode(",",$param[6]);
	$param[6] = implode("','",$param[6]);
	
	$rqt_count="select count(*) as nb from responsability where responsability_notice='".$parser_environnement['id_notice']."' ";
	$res_sql_count = mysql_query($rqt_count, $dbh);
	$res_count=mysql_fetch_object($res_sql_count);
	$rqt = "select author_id, responsability_fonction, responsability_type 
			from responsability, authors 
			where responsability_notice='".$parser_environnement['id_notice']."' 
				and responsability_author=author_id
				and author_type in('".$param[6]."') 
				and responsability_type<='".$param[0]."'  
			order by responsability_type, responsability_ordre " ;
	if ($param[1]>0) $rqt .= " limit 0,".$param[1] ; 

	$res_sql = mysql_query($rqt, $dbh);
	while ($authors=mysql_fetch_object($res_sql)) {
		$aut_detail=new auteur($authors->author_id);
		if ($authors->responsability_fonction && $param[4]==1) $aut_detail->isbd_entry .= ", ".$fonction_auteur[$authors->responsability_fonction];
		if ($authors->responsability_type==0) $aut[]=$aut_detail->isbd_entry;
		if ($authors->responsability_type==1) $aut1[]=$aut_detail->isbd_entry;
		if ($authors->responsability_type==2) $aut2[]=$aut_detail->isbd_entry;
		}
	if (count($aut1)) $aut[]=implode($param[2],$aut1);
	if (count($aut2)) $aut[]=implode($param[2],$aut2);
	if ($param[5] && $res_count->nb>$param[1]) $aut[]="et al.";
	if (count($aut)) return implode($param[3],$aut);
	
	return "";
}

function aff_authors_by_type_dir($param){
	global $fonction_auteur;
	// $param[0] = 0=principal seul, 1=principal+autres, 2=tous	
	// $param[1] = nombre maxi d'auteurs à afficher
	// $param[2] = séparateur entre auteurs
	// $param[3] = séparateur entre principal/autres/secondaires
	// $param[4] = afficher la fonction : 0=non, 1=toujours
	// $param[5] = afficher "et al." si plus d'auteurs que le maxi
	// $param[6] = 70=physique, 71=collectivités, 72=congrès (séparé parune virgule...)
	global $parser_environnement, $dbh;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();
	
	$param[6] = explode(",",$param[6]);
	$param[6] = implode("','",$param[6]);
	
	$rqt_count="select count(*) as nb from responsability where responsability_notice='".$parser_environnement['id_notice']."' ";
	$res_sql_count = mysql_query($rqt_count, $dbh);
	$res_count=mysql_fetch_object($res_sql_count);
	$rqt = "select author_id, responsability_fonction, responsability_type 
			from responsability, authors 
			where responsability_notice='".$parser_environnement['id_notice']."' 
				and responsability_author=author_id
				and author_type in('".$param[6]."') 
				and responsability_type<='".$param[0]."'  
			order by responsability_type, responsability_ordre " ;
	if ($param[1]>0) $rqt .= " limit 0,".$param[1] ; 

	$res_sql = mysql_query($rqt, $dbh);
	while ($authors=mysql_fetch_object($res_sql)) {
		$aut_detail=new auteur($authors->author_id);
		if ($authors->responsability_fonction && $param[4]==1 && $authors->responsability_fonction == "651") $aut_detail->isbd_entry .= " (dir.)";
		if ($authors->responsability_type==0) $aut[]=$aut_detail->isbd_entry;
		if ($authors->responsability_type==1) $aut1[]=$aut_detail->isbd_entry;
		if ($authors->responsability_type==2) $aut2[]=$aut_detail->isbd_entry;
		}
	if (count($aut1)) $aut[]=implode($param[2],$aut1);
	if (count($aut2)) $aut[]=implode($param[2],$aut2);
	if ($param[5] && $res_count->nb>$param[1]) $aut[]="et al.";
	if (count($aut)) return implode($param[3],$aut);
	
	return "";
}

function aff_permalink($param){
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "";
	$notice=gere_global();
	return $notice['notice_info']->permalink;	
}
