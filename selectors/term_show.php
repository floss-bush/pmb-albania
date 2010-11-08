<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: term_show.php,v 1.12 2010-01-07 10:50:04 kantin Exp $

$base_path="..";                            
$base_auth = ""; 

require_once ("$base_path/includes/init.inc.php"); 
require_once("$class_path/term_show.class.php"); 
require_once ("$javascript_path/misc.inc.php");
require_once($base_path."/selectors/templates/category.tpl.php");

print reverse_html_entities();

//Récupération des paramètres du formulaire appellant
$base_query = "caller=$caller&p1=$p1&p2=$p2&no_display=$no_display&bt_ajouter=$bt_ajouter&parent=&history=".rawurlencode(stripslashes($term))."&dyn=$dyn&keep_tilde=$keep_tilde&id_thes=$id_thes";

echo $jscript_term;


function parent_link($categ_id,$categ_see) {
	global $caller;
	global $charset;
	global $thesaurus_mode_pmb ;
	
	if ($categ_see) $categ=$categ_see; else $categ=$categ_id;
	$tcateg =  new category($categ);
	
	if ($tcateg->commentaire_public) {
		$zoom_comment = "<div id='zoom_comment".$tcateg->id."' style='border: solid 2px #555555; background-color: #FFFFFF; position: absolute; display:none; z-index: 2000;'>".htmlentities($tcateg->commentaire_public,ENT_QUOTES, $charset)."</div>" ;
		$java_comment = " onmouseover=\"z=document.getElementById('zoom_comment".$tcateg->id."'); z.style.display=''; \" onmouseout=\"z=document.getElementById('zoom_comment".$tcateg->id."'); z.style.display='none'; \"" ;
	} else {
		$zoom_comment = "" ;
		$java_comment = "" ;
	}
	
	if ($thesaurus_mode_pmb) $nom_tesaurus='['.$tcateg->thes->getLibelle().'] ' ;
		else $nom_tesaurus='' ;
	$link="<a href=\"\" onclick=\"set_parent('$caller', '$tcateg->id', '".htmlentities(addslashes($nom_tesaurus.$tcateg->catalog_form),ENT_QUOTES, $charset)."'); return false;\" $java_comment><span class='plus_terme'><span>+</span></span></a>$zoom_comment";
	$visible=true;
	$r=array("VISIBLE"=>$visible,"LINK"=>$link);
	return $r;
}

$ts=new term_show(stripslashes($term),"term_show.php",$base_query,"parent_link",$keep_tilde, $id_thes);
echo pmb_bidi($ts->show_notice());
?>