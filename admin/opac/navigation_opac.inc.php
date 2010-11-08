<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: navigation_opac.inc.php,v 1.1 2010-05-18 14:27:44 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion du lien entre la localisation et la section et le type de navigation

$admin_expl_nagopac_ligne_loc="
	<tr style='height:2.5em;'>
		<td colspan=2><strong>!!libelle_localisation!!</strong></td>
	</tr>
	<tr>
		<th>".$msg[295]."</th>
		<th>".$msg["exemplaire_admin_navigopac_pclass_utilise"]."</th>
	</tr>";

$admin_expl_nagopac_new_ligne="
		<!-- ligne_loc -->
		<tr class='!!pair_impair!!' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='!!pair_impair!!'\" >
			<td>!!libelle_section!!</td>
			<td>!!plan_classement!!
					<input type='hidden' value='!!location_id!!' name='id_localisation_!!num_ligne!!'/>
					<input type='hidden' value='!!section_id!!' name='id_section_!!num_ligne!!'/>
			</td>
		</tr>	
		<!-- nouvelle ligne -->";

$admin_expl_nagopac="
<form class='form-".$current_module."' name='navigopac' method='post' action=\"./admin.php?categ=opac&sub=navigopac&action=save\">
	<h3>".$msg["exemplaire_admin_navigopac_entete_form"]."</h3>
	<div class='form-contenu'>
	<!-- info_enregistrée -->	
	<table>
		<!-- nouvelle ligne -->	
	</table>
	</div>
	<div class='row'>
		<input class='bouton' type='submit' value='".$msg[77]."'/>
	</div>
	<div class='row'></div>
</form>";

function show_navigopac($dbh){
	global $msg,$thesaurus_classement_mode_pmb,$thesaurus_classement_defaut;
	global $charset;
	global $admin_expl_nagopac,$admin_expl_nagopac_new_ligne,$admin_expl_nagopac_ligne_loc;// les templates utilisés

	$requete = "SELECT location_libelle,section_libelle,num_pclass,idsection,idlocation FROM docsloc_section JOIN docs_location ON num_location=idlocation JOIN docs_section ON num_section=idsection ORDER BY location_libelle,section_libelle";
	$res = mysql_query($requete, $dbh);
	$nbr = mysql_num_rows($res);
	
	$requete_pclass = "SELECT id_pclass,name_pclass FROM pclassement";
	$res_pclass = mysql_query($requete_pclass, $dbh);
	$tabl_pclass=array();
	if(mysql_num_rows($res_pclass)){
		while ($ligne=mysql_fetch_object($res_pclass)) {
			$tabl_pclass[$ligne->id_pclass]=$ligne->name_pclass;
		}
	}
	$parity=1;
	$old_localisation="";
	for($i=0;$i<$nbr;$i++) {
		$row=mysql_fetch_object($res);
		
		//on met dans le formulaire les champs caché
		$new_ligne=$admin_expl_nagopac_new_ligne;
		$new_ligne=str_replace("!!num_ligne!!",$i,$new_ligne);
		$new_ligne=str_replace("!!location_id!!",$row->idlocation,$new_ligne);
		$new_ligne=str_replace("!!section_id!!",$row->idsection,$new_ligne);
		
		//Localisation
		if($row->location_libelle != $old_localisation){//Si on a changé de localisation
			$new_ligne=str_replace("<!-- ligne_loc -->",str_replace("!!libelle_localisation!!",htmlentities($row->location_libelle,ENT_QUOTES,$charset),$admin_expl_nagopac_ligne_loc),$new_ligne);//On insert la "case" avec le libellé de la localisation
	    	$old_localisation=$row->location_libelle;//On enregistre le dernier libellé
	    	$parity=1;
	    }
	    
	    //alternance des lignes
	    if ($parity % 2) {
			$pair_impair = "even";
		}else{
			$pair_impair = "odd";
		}
		$new_ligne=str_replace("!!pair_impair!!",$pair_impair,$new_ligne);
		$parity+=1;
		
		//Section
		$new_ligne=str_replace("!!libelle_section!!",htmlentities($row->section_libelle,ENT_QUOTES,$charset),$new_ligne);
		
		//Plan de classement
		//On affiche un selecteur
		$selector= "<select name='pclass_".$i."' id='pclass_".$i."' style='cursor: pointer'>";
		$selector .= "<option value='-1'";
		if ($row->num_pclass == -1) {
			$selector .= 'SELECTED';
		}
		$selector .= ">".$msg["exemplaire_admin_navigopac_par_aut"]."</option>";
		
		$selector .= "<option value='0'";
		if ($row->num_pclass == 0) {
			$selector .= 'SELECTED';
		}
		$selector .= ">".$msg["exemplaire_admin_navigopac_pas_navig"]."</option>";
		if($thesaurus_classement_mode_pmb && count($tabl_pclass) > 1){
			foreach ( $tabl_pclass as $key => $value ) {
       			$selector .= "<option value='".$key."'";
       			if ($key == $row->num_pclass ) {
					$selector .= 'SELECTED';
				}
				$selector .= ">";
				$selector .= htmlentities($value,ENT_QUOTES,$charset)."</option>";
			}
		}else{
			$selector .= "<option value='".$thesaurus_classement_defaut."'";
   			if ($thesaurus_classement_defaut == $row->num_pclass ) {
				$selector .= 'SELECTED';
			}
			$selector .= ">";
			$selector .= htmlentities($tabl_pclass[$thesaurus_classement_defaut],ENT_QUOTES,$charset)."</option>";
		}
		$selector .=  "</select>";
		$new_ligne=str_replace("!!plan_classement!!",$selector,$new_ligne);
		//On ajoute la nouvelle ligne au formulaire
		$admin_expl_nagopac=str_replace("<!-- nouvelle ligne -->",$new_ligne,$admin_expl_nagopac);
	}
	print pmb_bidi($admin_expl_nagopac);
}

switch($action) {
	case 'save':
		$i=0;
		$id_pclass="pclass_".$i;
		//echo "valeur : ".$$id_pclass."<br>";
		while(isset($$id_pclass)){
			//On enregistre la valeur
			$idsection="id_section_".$i;
			$idlocalisation="id_localisation_".$i;
			//echo "localisation : ".$$idlocalisation." Section : ".$$idsection."<br>";
			$requete="UPDATE docsloc_section SET num_pclass='".$$id_pclass."' WHERE num_location='".$$idlocalisation."' AND num_section='".$$idsection."' ";
			mysql_query($requete,$dbh);
			//echo "valeur : ".$$id_pclass." de : ".$id_pclass."<br>";
			$i++;
			$id_pclass="pclass_".$i;
		}	
		$admin_expl_nagopac=str_replace("<!-- info_enregistrée -->","<div class='erreur'>".$msg["exemplaire_admin_navigopac_modif_sauv"]."</div>",$admin_expl_nagopac);
		show_navigopac($dbh);
		break;
	default:
		show_navigopac($dbh);
		break;
}
