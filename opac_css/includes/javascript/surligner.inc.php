<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: surligner.inc.php,v 1.13 2010-04-20 13:29:26 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], "inc.php")) die("no access");

require_once("$class_path/analyse_query.class.php");
require_once("$include_path/marc_tables/$lang/empty_words");

$carac_spec = new XMLlist("$include_path/messages/diacritiqueiso-8859-1.xml");
$carac_spec->analyser();
$carac = $carac_spec->table;
		
reset($carac_spec->table);

//Nettoyage de la chaine recherchée
function nettoyer_chaine($tree="",&$tableau,&$tableau_l,$aq,$not) {
	global $empty_word;
	
	if ($tree=="") $tree=$aq->tree;	
	
	for ($i=0; $i<count($tree); $i++) {
		$mot = "";
		if ($tree[$i]->not) $mul=-1; else $mul=1; 
		if ($tree[$i]->sub==null) {
			if ($not*$mul==1) 
				if ($tree[$i]->literal){	
					$mot = str_replace("*","\w*",$tree[$i]->word); 
					if($mot)
						$tableau_l[]= $mot;
				} else{
					$mot = str_replace("*","\w*",$tree[$i]->word); 				
					if(strlen($tree[$i]->word)<=1) 
						$mot = "";				
				    if($mot){			    	
						$tableau[]= $mot;
				    }
				}
		} else { 
		$not=$not*$mul;
		nettoyer_chaine($tree[$i]->sub,$tableau,$tableau_l,$aq,$not); 
		}
	}
}	

$tableau=array();
$tableau_l=array();
if ($user_query && (trim($user_query) != "*")) {
	$aq=new analyse_query(stripslashes($user_query),0,0,1,0);
	if (!$aq->error) {
		nettoyer_chaine("",$tableau,$tableau_l,$aq,1);
	}
	
}

$inclure_recherche = "<script>\n";
$inclure_recherche .= "terms=new Array('".implode("','",$tableau)."');\n";
$inclure_recherche .= "terms_litteraux=new Array('".implode("','",addslashes_array($tableau_l))."');\n";
$inclure_recherche .= "\n";	

$inclure_recherche .= "codes=new Array();\n";
$j=0;
	
	
foreach($carac_spec->table as $key=>$val) {
$values=explode("|",substr($val,1,strlen($val)-2));

	$i=0;
	$temp="[";
	while ($values[$i]!="")
	{
		$temp .=$values[$i];
		$i++;
	}
	$temp .= "]";
	$inclure_recherche .= "codes['$key']='$temp';\n";
	$j++;
}

$inclure_recherche .= "
function remplacer_carac(mot)
{
var x;	
var chaine;
var reg;				
chaine=mot;\n";
	foreach($carac_spec->table as $key=>$val) {
		$inclure_recherche .= "reg=new RegExp(codes['$key'], 'g');\n";
		$inclure_recherche .= "chaine=chaine.replace(reg, '$key');\n";			
		}		
$inclure_recherche .= "return(chaine);		
} 	";

$inclure_recherche .= "
		
function trouver_mots_f(obj,mot,couleur,litteral,onoff) {
	var i;
	var chaine;
	if (obj.hasChildNodes()) {
		var childs=new Array();
		childs=obj.childNodes;
		
		for (i=0; i<childs.length; i++) {
			
			if (childs[i].nodeType==3) {
				if (litteral==0){
					chaine=childs[i].data.toLowerCase();
					chaine=remplacer_carac(chaine);
				} else {
					chaine=childs[i].data;
				}
				 
				var reg_mot = new RegExp(mot+' *','gi');	
				if (chaine.match(reg_mot)) {
					var elt_found = chaine.match(reg_mot);
					var chaine_display = childs[i].data;
					var reg = 0;
					for(var k=0;k<elt_found.length;k++){
						reg = chaine.indexOf(elt_found[k],reg); 
						if (onoff==1) {
							after_shave=chaine_display.substring(reg+elt_found[k].length);
							sp=document.createElement('span');
							if (couleur % 6!=0) {
								sp.className='text_search'+couleur;
							} else {
								sp.className='text_search0';
							}
							nmot=document.createTextNode(chaine_display.substring(reg,reg+elt_found[k].length));
							childs[i].data=chaine_display.substring(0,reg);
							sp.appendChild(nmot);
						
							if (after_shave) {
								var aftern=document.createTextNode(after_shave);
							} else var aftern='';
						
							if (i<childs.length-1) {
								obj.insertBefore(sp,childs[i+1]);
								if (aftern) { obj.insertBefore(aftern,childs[i+2]); }
							} else {
								obj.appendChild(sp);
								if (aftern) obj.appendChild(aftern);
							}
							chaine_display ='';
							i++;
						} else {
							obj.replaceChild(childs[i],obj);
						}
					}
				}
			} else if (childs[i].nodeType==1){
				trouver_mots_f(childs[i],mot,couleur,litteral,onoff);
			}
		}
	}
}
		
function rechercher(onoff) {
	obj=document.getElementById('res_first_page');
	if (!obj) {
		obj=document.getElementById('resultatrech_liste');
		if(obj) if (obj.getElementsByTagName('blockquote')[0]) {
			obj=obj.getElementsByTagName('blockquote')[0];
		}
	}
	if (obj) {
		if (terms[0]!='')
		{
			for (var i=0; i<terms.length; i++) {
				trouver_mots_f(obj,terms[i],i,0,onoff);			
			}
		}
		if (terms_litteraux[0]!='')
		{
			for (var i=0; i<terms_litteraux.length; i++) {
				trouver_mots_f(obj,terms_litteraux[i],i+terms.length,1,onoff);			
			}
		}
	}
}
	
	


</script>";	


?>
