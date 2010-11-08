<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice_tpl.inc.php,v 1.7 2010-07-08 08:53:56 ngantier Exp $
require_once ($include_path . "/misc.inc.php");

$func_format['b_empty']= aff_b_empty;
$func_format['a_empty']= aff_a_empty;
$func_format['not_empty']= aff_not_empty;
$func_format['if']= aff_if;

$func_format['isbd']= aff_isbd;
$func_format['title']= aff_title;
$func_format['parallel_title']= aff_parallel_title;
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
$func_format['header_link']= aff_header_link;

$func_format['gen_plus']= aff_gen_plus;
$func_format['is_article']= aff_is_article;
$func_format['nom_revue']=aff_nom_revue;
$func_format['date_bulletin']=aff_date_bulletin;
$func_format['numero_bulletin']=aff_numero_bulletin;
$func_format['expl_num']=aff_expl_num;

$parser_environnement = array();


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
		<img src='./images/plus.gif' class='img_plus' name='imEx' id='".$parser_environnement['id_notice']."Img' title='détail' border='0' onClick=\"expandBase('".$parser_environnement['id_notice']."', true); return false;\" hspace='3'>
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
	if(!$parser_environnement['id_notice']) return "Le rouge et le noir / Stendhal";
	$notice=gere_global();
	
	if (($notice['notice_info']->niveau_biblio=="a")&&($notice['notice_info']->niveau_hierar==2)) return 1; else return 0;
}

function aff_isbd($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "Le rouge et le noir / Stendhal";
	$notice=gere_global();
	
	return $notice['notice_info']->isbd;
}

function aff_title($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "Le rouge et le noir / Stendhal";
	$notice=gere_global();

	return $notice['notice_info']->memo_titre;
}

function aff_parallel_title($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "Le rouge et le noir / Stendhal";
	$notice=gere_global();

	return $notice['notice_info']->memo_titre_parallele;
}

function aff_auteur_principal($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "Stendhal";
	$notice=gere_global();
	
	return $notice['notice_info']->memo_auteur_principal;
}

function aff_auteur_autre($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "Stendhal";
	$notice=gere_global();
	
	return $notice['notice_info']->memo_auteur_autre;
}
function aff_auteur_secondaire($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "Stendhal";
	$notice=gere_global();
	
	return $notice['notice_info']->memo_auteur_secondaire;
}

function aff_resume($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "Stendhal";
	$notice=gere_global();
	
	return nl2br($notice['notice_info']->notice->n_resume);
}
function aff_contenu($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "Stendhal";
	$notice=gere_global();
	
	return nl2br($notice['notice_info']->notice->n_contenu);
}
function aff_note($param) {
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "Stendhal";
	$notice=gere_global();
	
	return nl2br($notice['notice_info']->notice->n_gen);
}

function aff_header_link($param) {
	global $pmb_opac_url,$use_opac_url_base,$opac_url_base;
	
	global $parser_environnement;
	if(!$parser_environnement['id_notice']) return "Le rouge et le noir / Stendhal";
	$notice=gere_global();
	
	switch($param[1]){
		case 2:
			if($notice['notice_info']->notice->lien) {
				$libelle="<a href=\"".$notice['notice_info']->notice->lien."&code=!!code!!&emprlogin=!!login!!&date_conex=!!date_conex!!\" target=\"__LINK__\">".$param[0];
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