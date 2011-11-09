<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: ireps_pdl.inc.php,v 1.2 2011-04-15 11:29:42 touraine37 Exp $

function search_other_function_filters() { //OK
	global $charset,$ireps_ss_type,$ireps_location,$ireps_public,$ireps_expertise, $ireps_indexint;
	
	if(!isset($ireps_ss_type)) $ireps_ss_type='0';
	if(!isset($ireps_location)) $ireps_location='0';
	if(!isset($ireps_indexint)) $ireps_indexint='0';
	
	$ireps_js_location="var ireps_location_code= new Array();\n var ireps_location_libelle = new Array();\n";
	
	//Pour la sélection par sous type
	$q="select notices_custom_list_value,notices_custom_list_lib from notices_custom_lists where notices_custom_champ='3' order by notices_custom_list_lib";
	$r=mysql_query($q);
	$ireps_sel_ss_type="<select name='ireps_ss_type'>" ;
	$ireps_sel_ss_type.="<option value='0' ";
	if(!$ireps_ss_type) $ireps_sel_ss_type.="selected=\"selected\" ";
	$ireps_sel_ss_type.=">Tous sous-types de documents</option>";
	$incr=0;
	if (mysql_num_rows($r)) {
		while (($row = mysql_fetch_object($r))) {
			$selected="";
			if ($row->notices_custom_list_value==$ireps_ss_type) {
				$selected="selected=\"selected\"";
			} else {
				$selected='';
			}
			$ireps_sel_ss_type.= "<option value='$row->notices_custom_list_value' $selected>".$row->notices_custom_list_lib."</option>";
			$ireps_js_location.="ireps_location_code[$incr] = \"".$row->notices_custom_list_value."\";\n";
			$ireps_js_location.="ireps_location_libelle[$incr] = \"".$row->notices_custom_list_lib."\";\n";
			$incr++;
		}
	}
	$ireps_sel_ss_type.="</select>";
	
	//Pour la sélection par localisation
	$q="select idlocation,location_libelle, if(left(location_libelle, 5 )='IREPS',1,2) as o from docs_location where location_visible_opac='1' order by o,cp";
	$r=mysql_query($q);
	$ireps_sel_location="<select name='ireps_location'>" ;
	$ireps_sel_location.="<option value='0' ";
	if(!$ireps_location) $ireps_sel_location.="selected=\"selected\" ";
	$ireps_sel_location.=">Toutes localisations</option>";
	if (mysql_num_rows($r)) {
		while (($row = mysql_fetch_object($r))) {
			$selected="";
			if ($row->idlocation==$ireps_location) {
				$selected="selected=\"selected\"";
			} else {
				$selected='';
			}
			$ireps_sel_location.= "<option value='".$row->idlocation."' $selected>".$row->location_libelle."</option>";
		}
	}
	$ireps_sel_location.="</select>";

	//Pour la sélection par public cible
	$q="select notices_custom_list_value,notices_custom_list_lib from notices_custom_lists where notices_custom_champ='4' order by notices_custom_list_lib";
	$r=mysql_query($q);
	$ireps_sel_public="<select name='ireps_public'>" ;
	$ireps_sel_public.="<option value='0' ";
	if(!$ireps_public) $ireps_sel_public.="selected=\"selected\" ";
	$ireps_sel_public.=">Tous publics</option>";
	if (mysql_num_rows($r)) {
		while (($row = mysql_fetch_object($r))) {
			$selected="";
			if ($row->notices_custom_list_value==$ireps_public) {
				$selected="selected=\"selected\"";
			} else {
				$selected='';
			}
			$ireps_sel_public.= "<option value='$row->notices_custom_list_value' $selected>".$row->notices_custom_list_lib."</option>";
		}
	}
	$ireps_sel_public.="</select>";
	
	//Pour la sélection par expertise
	$q="select notices_custom_list_value,notices_custom_list_lib from notices_custom_lists where notices_custom_champ='8' order by notices_custom_list_lib";
	$r=mysql_query($q);
	$ireps_sel_expertise="<select name='ireps_expertise'>" ;
	$ireps_sel_expertise.="<option value='0' ";
	if(!$ireps_expertise) $ireps_sel_expertise.="selected=\"selected\" ";
	$ireps_sel_expertise.=">Expertise r&eacute;gionale</option>";
	if (mysql_num_rows($r)) {
		while (($row = mysql_fetch_object($r))) {
			$selected="";
			if ($row->notices_custom_list_value==$ireps_expertise) {
				$selected="selected=\"selected\"";
			} else {
				$selected='';
			}
			$ireps_sel_expertise.= "<option value='$row->notices_custom_list_value' $selected>".$row->notices_custom_list_lib."</option>";
		}
	}
	$ireps_sel_expertise.="</select>";
	
	$ireps_js="<script type=\"text/javascript\">".$ireps_js_location."
	document.search_input.typdoc.onchange=par_doc_type;
	par_doc_type();
	
	function par_doc_type(){
		
		switch(document.search_input.typdoc.options[document.search_input.typdoc.selectedIndex].value){
			case 'a'://Ouvrages/Etudes/Rapports
				var visible = new Array('2','4','5','6','7','8','10');
				trouve_select(visible);
				affiche_selecteurs_outils('none');
				break;
			case 'x'://Périodique
				var visible = new Array('2','24');
				trouve_select(visible);
				affiche_selecteurs_outils('none');
				break;
			case 'y'://Outils d'intervention
				var visible = new Array('11','12','13','14','15','16','17','18','19','20','21','22','23');
				trouve_select(visible);
				affiche_selecteurs_outils('block');
				break;
			default :
				var visible = new Array();
				trouve_select(visible);
				affiche_selecteurs_outils('none');
				break;
		}
	}
		
	function trouve_select(visible){
		var ss_typdoc=document.search_input.ireps_ss_type;
		var mon_select=false;
		var index_select = ss_typdoc.options[ss_typdoc.selectedIndex].value;
		for (var i=0; i<ss_typdoc.options.length;i++){
			ss_typdoc.options[i]=null;
			i--;
		}
		ss_typdoc.options[0] = new Option('Tous sous-types de documents','0');
		var incr=1;
		for (var i=0; i<ireps_location_code.length;i++){
			if(visible.length){
				for(var j=0; j<visible.length;j++){
					if(ireps_location_code[i] == visible[j]){
						ss_typdoc.options[incr] = new Option(ireps_location_libelle[i],ireps_location_code[i]);
						if(index_select == ireps_location_code[i]){
							ss_typdoc.options[incr].selected=true;
							mon_select=true;
						}
						incr++;
					}
				}
			}else{
				ss_typdoc.options[incr] = new Option(ireps_location_libelle[i],ireps_location_code[i]);
				if(index_select == ireps_location_code[i]){
					ss_typdoc.options[incr].selected=true;
					mon_select=true;
				}
				incr++;
			}
		}
		if(mon_select == false){
			ss_typdoc.options[0].selected=true;
		}
	}

	function affiche_selecteurs_outils(visible) {
		document.getElementById('ireps_search_options').style.display=visible;
	}
	
	</script>";
	
	// pour la sélection par plan de classement
	$entete_indexint['A'] = "Santé publique, promotion de la santé";
	$entete_indexint['B'] = "Système sanitaire et social";
	$entete_indexint['C'] = "Prise en charge, soins";
	$entete_indexint['D'] = "Développement des compétences";
	$entete_indexint['E'] = "Violences";
	$entete_indexint['F'] = "Accidents";
	$entete_indexint['G'] = "Hygiène de vie";
	$entete_indexint['H'] = "Environnement et santé";
	$entete_indexint['I'] = "Santé et sexualité";
	$entete_indexint['J'] = "Conduites addictives";
	$entete_indexint['K'] = "Ages et temps de la vie";
	$entete_indexint['L'] = "Populations et milieux spécifiques";
	$entete_indexint['M'] = "Prévention, dépistage";
	$entete_indexint['N'] = "Education du patient";
	$entete_indexint['O'] = "Pathologies et problèmes de santé";
	$q="select indexint_id, indexint_name, indexint_comment from indexint where num_pclass=1 order by indexint_name";
	$r=mysql_query($q);
	$ireps_sel_indexint="<select name='ireps_indexint'>" ;
	$ireps_sel_indexint.="<option value='0' ";
	if(!$ireps_indexint) $ireps_sel_indexint.="selected=\"selected\" ";
	$ireps_sel_indexint.=">Toutes thématiques</option>";
	$anc_chap="";
	if (mysql_num_rows($r)) {
		while (($row = mysql_fetch_object($r))) {
			if (substr($row->indexint_name,0,1)!=$anc_chap) {
				if ($anc_chap!="") $ireps_sel_indexint.= "</optgroup>"; 
				$anc_chap=substr($row->indexint_name,0,1);
				$ireps_sel_indexint.= "<optgroup label=\"".htmlentities($entete_indexint[$anc_chap],ENT_QUOTES,$charset)."\">";
			}
			
			$selected="";
			if ($row->indexint_id==$ireps_indexint) {
				$selected="selected=\"selected\"";
			} else {
				$selected='';
			}
			$ireps_sel_indexint.= "<option value=\"".$row->indexint_id."\" $selected>".htmlentities($row->indexint_comment,ENT_QUOTES,$charset)."</option>";
		}
	}
	$ireps_sel_indexint.="</optgroup></select>";
	

	$ireps_aff =$ireps_sel_ss_type.$ireps_sel_indexint.$ireps_sel_location;
	$ireps_aff.="<div id='ireps_search_options' style='display:none;'>";
	$ireps_aff.=$ireps_sel_public.$ireps_sel_expertise;
	$ireps_aff.='</div>';
	$ireps_aff.=$ireps_js;
	return $ireps_aff;
}

function search_other_function_clause(&$clause) { //KO
	global $ireps_ss_type,$ireps_location,$ireps_public,$ireps_expertise, $ireps_indexint;
	$r="";
	$from="";
	$where="";
	if ($ireps_ss_type) {
		$from=",notices_custom_values p3 ";
		$where=" and (p3.notices_custom_integer='".$ireps_ss_type."' and p3.notices_custom_champ='3' and p3.notices_custom_origine=notice_id)";
	}
	
	if ($ireps_location) {
		$where.=" and notice_id in (select expl_notice from exemplaires where expl_location='".$ireps_location."' ";
		$where.="UNION select num_notice from bulletins join exemplaires on expl_bulletin=bulletin_id  where expl_location='".$ireps_location."' ";
		$where.="UNION select analysis_notice from analysis join bulletins on analysis_bulletin=bulletin_id join exemplaires on expl_bulletin=bulletin_id where expl_location='".$ireps_location."' ";
		$where.="UNION select explnum_notice from explnum join explnum_location on num_explnum=explnum_id where num_location='".$ireps_location."' ";
		$where.=")";
	}

	if ($ireps_public) {
		$from.=",notices_custom_values p4 ";
		$where.=" and (p4.notices_custom_integer='".$ireps_public."' and p4.notices_custom_champ='4' and p4.notices_custom_origine=notice_id)";
	}
	
	if ($ireps_expertise) {
		$from.=",notices_custom_values p8 ";
		$where.=" and (p8.notices_custom_small_text='".$ireps_expertise."' and p8.notices_custom_champ='8' and p8.notices_custom_origine=notice_id)";
	}
	
	if ($ireps_indexint) {
		$from.=" ";
		$where.=" and (indexint='".$ireps_indexint."')";
	}
	
	if ($where) $r=($from?$from." ":" ").$clause.$where; else $r=$clause;
	if ($clause==$r) return false; else {
		$clause=$r;
		return true;
	}
}

function search_other_function_has_values() { //OK
	global $ireps_ss_type,$ireps_location,$ireps_public,$ireps_expertise, $ireps_indexint;
	if ($ireps_ss_type || $ireps_location || $ireps_public || $ireps_expertise || $ireps_indexint) return true; else return false;
}


function search_other_function_rec_history($n) { //OK
	global $ireps_ss_type,$ireps_location,$ireps_public,$ireps_expertise, $ireps_indexint;
	$_SESSION["ireps_ss_type".$n]=$ireps_ss_type;
	$_SESSION["ireps_location".$n]=$ireps_location;
	$_SESSION["ireps_public".$n]=$ireps_public;
	$_SESSION["ireps_expertise".$n]=$ireps_expertise;
	$_SESSION["ireps_indexint"].$n=$ireps_indexint;
	
}

function search_other_function_get_history($n) { //OK
	global $ireps_ss_type,$ireps_location,$ireps_public,$ireps_expertise, $ireps_indexint;
	$ireps_ss_type=$_SESSION["ireps_ss_type".$n];
	$ireps_location=$_SESSION["ireps_location".$n];
	$ireps_public=$_SESSION["ireps_public".$n];
	$ireps_expertise=$_SESSION["ireps_expertise".$n];
	$ireps_indexint=$_SESSION["ireps_indexint".$n];
}

function search_other_function_human_query($n) { //OK
	$ret="";
	$ireps_ss_type=$_SESSION["ireps_ss_type".$n];
	$ireps_location=$_SESSION["ireps_location".$n];
	$ireps_public=$_SESSION["ireps_public".$n];
	$ireps_expertise=$_SESSION["ireps_expertise".$n];
	$ireps_indexint=$_SESSION["ireps_indexint".$n];
	
	$app="";
	if ($ireps_ss_type) {
		$q="select notices_custom_list_lib from notices_custom_lists where notices_custom_champ='3' and notices_custom_list_value='".$ireps_ss_type."' limit 1 ";
		$r=mysql_query($q);
		if (mysql_num_rows($r)) {
			$app=mysql_result($r,0,0);
		}
	}
	if ($app) $ret.="Sous-type de document : ".$app;
	
	$app="";
	if ($ireps_location) {
 		$q="select location_libelle from docs_location where idlocation='".$ireps_location."'";
       	$r=mysql_query($q);
       	if (mysql_num_rows($r)) {
			$app=mysql_result($r,0,0);
		}
	}
	if($ret && $app) $ret.=", ";
	if($app) $ret.="Localisation : ".$app;
	
	$app="";
	if ($ireps_public) {
 		$q="select notices_custom_list_lib from notices_custom_lists where notices_custom_champ='4' and notices_custom_list_value='".$ireps_public."' limit 1 ";
       	$r=mysql_query($q);
       	if (mysql_num_rows($r)) {
			$app=mysql_result($r,0,0);
		}
	}
	if($ret && $app) $ret.=", ";
	if($app) $ret.="Public cible : ".$app;
	
	$app="";
	if ($ireps_expertise) {
 		$q="select notices_custom_list_lib from notices_custom_lists where notices_custom_champ='8' and notices_custom_list_value='".$ireps_expertise."' limit 1 ";
       	$r=mysql_query($q);
       	if (mysql_num_rows($r)) {
			$app=mysql_result($r,0,0);
		}
	}
	if($ret && $app) $ret.=", ";
	if($app) $ret.="Outil expertisé : ".$app;
	
	$app="";
	if ($ireps_indexint) {
 		$q="select indexint_comment from indexint where indexint_id'".$ireps_indexint."' limit 1 ";
       	$r=mysql_query($q);
       	if (mysql_num_rows($r)) {
			$app=mysql_result($r,0,0);
		}
	}
	if($ret && $app) $ret.=", ";
	if($app) $ret.="Thématique : ".$app;
	
	return $ret;
}


function search_other_function_post_values() { //OK
	global $ireps_ss_type,$ireps_location,$ireps_public,$ireps_expertise, $ireps_indexint;
	$retour ="<input type=\"hidden\" name=\"ireps_ss_type\" value=\"$ireps_ss_type\">\n";
	$retour.="<input type=\"hidden\" name=\"ireps_location\" value=\"$value\">\n";
	$retour.="<input type=\"hidden\" name=\"ireps_public\" value=\"$ireps_public\">\n";
	$retour.="<input type=\"hidden\" name=\"ireps_expertise\" value=\"$ireps_expertise\">\n";
	$retour.="<input type=\"hidden\" name=\"ireps_indexint\" value=\"$ireps_indexint\">\n";
	
	return $retour;
}









?>