<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: budgets.inc.php,v 1.5 2009-01-13 17:30:16 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion des budgets
require_once("$class_path/entites.class.php");
require_once("$class_path/exercices.class.php");
require_once("$class_path/budgets.class.php");
require_once("$class_path/rubriques.class.php");
require_once("$include_path/templates/budgets.tpl.php");


//Affiche la liste des etablissements
function show_list_biblio() {
	
	global $msg, $charset;
	global $tab_bib, $nb_bib;
	global $current_module;

	//Affiche de la liste des etablissements auxquels a acces l'utilisateur si > 1
	if ($nb_bib == '1') {
		show_list_bud($tab_bib[0][0]);		
		exit;
	}
	
	$def_bibli=entites::getSessionBibliId();
	if (in_array($def_bibli, $tab_bib[0])) {
		show_list_bud($def_bibli);
		exit;		
	}			

	$aff = "<form class='form-".$current_module."' id='list_biblio_form' name='list_biblio_form' method='post' action=\"\" >";
	$aff.= "<h3>".htmlentities($msg['acquisition_menu_chx_ent'], ENT_QUOTES, $charset)."</h3><div class='row'></div>";
	$aff.= "<table>";

	$parity=1;
	foreach($tab_bib[0] as $k=>$v) {
		if ($parity % 2) {
			$pair_impair = "even";
		} else {
			$pair_impair = "odd";
		}
		$parity += 1;
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='".$pair_impair."'\" onmousedown=\"document.forms['list_biblio_form'].setAttribute('action','./acquisition.php?categ=ach&sub=bud&action=list&id_bibli=".$v."');document.forms['list_biblio_form'].submit(); \" ";
        $aff.= "<tr class='".$pair_impair."' ".$tr_javascript." style='cursor: pointer'><td><i>".htmlentities($tab_bib[1][$k], ENT_QUOTES, $charset)."</i></td></tr>";
	}
	$aff.=" </table></form>";
	print $aff;
}


//Affiche la liste des budgets
function show_list_bud($id_bibli) {
	
	global $dbh, $msg, $charset;
	
	//Affichage du formulaire de recherche
	show_search_form($id_bibli);
	
	//Affichage de la liste des budgets
	$form = "<table>
	<tr>
		<th>".htmlentities($msg[103],ENT_QUOTES,$charset)."</th>
		<th>".htmlentities($msg[acquisition_statut],ENT_QUOTES,$charset)."</th>
		<th>".htmlentities($msg['acquisition_budg_exer'],ENT_QUOTES,$charset)."</th>
	</tr>";

	$q = budgets::listByEntite($id_bibli);
	$r = mysql_query($q, $dbh);
	$nb = mysql_num_rows($r);

	$parity=1;
	for($i=0;$i<$nb;$i++) {
		$row=mysql_fetch_object($r);
			if ($parity % 2) {
				$pair_impair = "even";
			} else {
				$pair_impair = "odd";
			}
			$parity += 1;
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./acquisition.php?categ=ach&sub=bud&action=show&id_bibli=$row->num_entite&id_bud=$row->id_budget';\" ";
	        $form.="<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td><i>".htmlentities($row->libelle, ENT_QUOTES, $charset)."</i></td>";
	        $form.='<td>';
	        switch ($row->statut) {
	        	case STA_BUD_VAL :
	        		$form.=htmlentities($msg[acquisition_statut_actif],ENT_QUOTES,$charset) ;
	        		break;
	        	case  STA_BUD_CLO :
	        		$form.=htmlentities($msg[acquisition_statut_clot],ENT_QUOTES,$charset) ;
	        		break;
	        	default:
	        		$form.=htmlentities($msg[acquisition_budg_pre],ENT_QUOTES,$charset) ;
	        		break;
	        }
			$form.="</td>";
			
			$exer = new exercices($row->num_exercice);
			$form.='<td>'.htmlentities($exer->libelle, ENT_QUOTES, $charset)."</td></tr>";
	}
	$form.="</table>";
	
	print $form;
}


//Affiche le formulaire de recherche
function show_search_form($id_bibli) {
	
	global $msg, $charset;
	global $search_form;	
	global $tab_bib;
	
	$form = $search_form;
	$titre = htmlentities($msg['acquisition_voir_bud'], ENT_QUOTES, $charset);
	
	//Creation selecteur etablissement
	$sel_bibli ="<select class='saisie-50em' id='id_bibli' name='id_bibli' onchange=\"document.forms['search'].setAttribute('action', './acquisition.php?categ=ach&sub=bud&action=list');document.forms['search'].submit(); \" >";
	foreach($tab_bib[0] as $k=>$v) {
		$sel_bibli.="<option value='".$v."' ";
		if($v==$id_bibli) $sel_bibli.="selected='selected' ";
		$sel_bibli.=">".htmlentities($tab_bib[1][$k], ENT_QUOTES, $charset)."</option>";
	}
	$sel_bibli.="</select>";

	$form=str_replace('!!form_title!!', $titre , $form);
	$form=str_replace('<!-- sel_bibli -->', $sel_bibli, $form);
	print $form;
}


//Affiche le formulaire d'un budget
function show_bud($id_bibli=0, $id_bud=0) {

	global $dbh, $msg, $charset;
	global $view_bud_form;
	global $view_lig_rub_form, $lig_rub_img, $view_tot_rub_form;
	global $pmb_gestion_devise;
	global $acquisition_gestion_tva;
	
	if (!$id_bibli || !$id_bud) return;

	show_search_form($id_bibli);
	
	//Recuperation budget
	$bud= new budgets($id_bud);
	$lib_bud = htmlentities($bud->libelle, ENT_QUOTES, $charset);
	$mnt_bud = $bud->montant_global;
	$devise = $pmb_gestion_devise;
	switch ($acquisition_gestion_tva) {
		case '0' :
			$htttc=htmlentities($msg['acquisition_ttc'], ENT_QUOTES, $charset);
			$k_htttc='ttc';
			break;
		default:
			$htttc=htmlentities($msg['acquisition_ht'], ENT_QUOTES, $charset);
			$k_htttc='ht';
			break;
	}
	if(!$bud->type_budget) {
		$typ_bud = htmlentities($msg['acquisition_budg_aff_rub'], ENT_QUOTES, $charset);
	} else {
		$typ_bud = htmlentities($msg['acquisition_budg_aff_glo'], ENT_QUOTES, $charset);
	}
	//montant total pour budget par rubriques
	if ($bud->type_budget == TYP_BUD_GLO) {
		$mnt['tot'][$k_htttc] = $bud->montant_global;
		$totaux = array('tot'=>$mnt['tot'][$k_htttc], 'ava'=>0, 'eng'=>0, 'fac'=>0, 'pay'=>0, 'sol'=>0);
	} else {
		$totaux = array('tot'=>0, 'ava'=>0, 'eng'=>0, 'fac'=>0, 'pay'=>0, 'sol'=>0);
	}

	switch ($bud->statut) {
		case STA_BUD_VAL :
			$sta_bud = htmlentities($msg['acquisition_statut_actif'],ENT_QUOTES,$charset);
			break;
		case STA_BUD_CLO :
			$sta_bud = htmlentities($msg['acquisition_statut_clot'],ENT_QUOTES,$charset);
			break;
		case STA_BUD_PRE :
		default :
			$sta_bud = htmlentities($msg['acquisition_budg_pre'],ENT_QUOTES,$charset);
			break;	
	}
	$seu_bud = $bud->seuil_alerte;
	
	//Recuperation exercice
	$exer = new exercices($bud->num_exercice);
	$lib_exer = htmlentities($exer->libelle, ENT_QUOTES, $charset);

	$form = $view_bud_form;
	
	$lib_mnt_bud=number_format($mnt_bud,'2','.',' ');
	$form = str_replace('!!lib_bud!!', $lib_bud, $form);
	$form = str_replace('!!lib_exer!!', $lib_exer, $form);
	$form = str_replace('!!mnt_bud!!', $lib_mnt_bud, $form);
	$form = str_replace('!!devise!!', $devise, $form);
	$form = str_replace('!!htttc!!', $htttc, $form);
	$form = str_replace('!!typ_bud!!', $typ_bud, $form);
	$form = str_replace('!!sta_bud!!', $sta_bud, $form);
	$form = str_replace('!!seu_bud!!', $seu_bud, $form);
	
	//recuperation de la liste complete des rubriques
	$q = budgets::listRubriques($id_bud, 0);	
	$list_n1 = mysql_query($q, $dbh); 
	while(($row=mysql_fetch_object($list_n1))) {
		
		$form = str_replace('<!-- rubriques -->', $view_lig_rub_form.'<!-- rubriques -->', $form);
		$form = str_replace('<!-- marge -->', '', $form);
		$nb_sr = rubriques::countChilds($row->id_rubrique);
		if ($nb_sr) {
			$form = str_replace('<!-- img_plus -->', $lig_rub_img, $form);
		} else {
			$form = str_replace('<!-- img_plus -->', '', $form);
		}
		$form = str_replace('!!id_rub!!', $row->id_rubrique, $form);
		$form = str_replace('!!id_parent!!', $row->num_parent, $form);			
		$libelle = htmlentities($row->libelle, ENT_QUOTES, $charset);
		$form = str_replace('!!lib_rub!!', $libelle, $form);
		
		//montant total pour budget par rubriques
		$mnt['tot'][$k_htttc] = $row->montant;
		//montant a valider
		$mnt['ava'] = rubriques::calcAValider($row->id_rubrique);
		//montant engage
		$mnt['eng'] = rubriques::calcEngage($row->id_rubrique);
		//montant facture
		$mnt['fac'] = rubriques::calcFacture($row->id_rubrique);
		//montant paye
		$mnt['pay'] = rubriques::calcPaye($row->id_rubrique);
		//solde
		$mnt['sol'][$k_htttc]=$mnt['tot'][$k_htttc]-$mnt['eng'][$k_htttc];  
		
		foreach($totaux as $k=>$v) {
			$totaux[$k]=$v+$mnt[$k][$k_htttc];
		}
		$lib_mnt=array();
		foreach($mnt as $k=>$v) {
			$lib_mnt[$k]=number_format($mnt[$k][$k_htttc],2,'.',' ');
		}
		if ($bud->type_budget == TYP_BUD_GLO ) {
			$lib_mnt['tot']='&nbsp;';
			$lib_mnt['sol']='&nbsp;';			
		}
		foreach($lib_mnt as $k=>$v) {
			$form = str_replace('!!mnt_'.$k.'!!', $lib_mnt[$k], $form);
		}
		
		if($nb_sr) {
			$form = str_replace('<!-- sous_rub -->', '<!-- sous_rub'.$row->id_rubrique.' -->', $form);
			afficheSousRubriques($bud, $row->id_rubrique, $form, 1);
		} else {
			$form = str_replace('<!-- sous_rub -->', '', $form);
		}
	}
	$form = str_replace('<!-- totaux -->', $view_tot_rub_form, $form);
	if($bud->type_budget==TYP_BUD_GLO){
		$totaux['tot']=$bud->montant_global;
		$totaux['sol']=$totaux['tot']-$totaux['eng'];
	}
	foreach($totaux as $k=>$v) {
		if(is_numeric($v)) {
			$totaux[$k]=number_format($v,2,'.',' ');			
		} else {
			$totaux[$k]='&nbsp;';
		}
		$form = str_replace('!!mnt_'.$k.'!!', $totaux[$k], $form);
	}
	print $form;	
}


//Affiche les sous-rubriques d'une rubrique
function afficheSousRubriques($bud, $id_rub, &$form, $indent=0) {
	
	global $dbh, $charset;
	global $view_lig_rub_form, $lig_rub_img, $lig_indent;
	global $acquisition_gestion_tva;

		switch ($acquisition_gestion_tva) {
		case '0' :
			$k_htttc='ttc';
			break;
		default:
			$k_htttc='ht';
			break;
	}
	$id_bud = $bud->id_budget;
	$q = budgets::listRubriques($id_bud, $id_rub);
	$list_n = mysql_query($q, $dbh); 
	while(($row=mysql_fetch_object($list_n))){
			
		$form = str_replace('<!-- sous_rub'.$id_rub.' -->', $view_lig_rub_form.'<!-- sous_rub'.$id_rub.' -->', $form);
		$marge = '';
		for($i=0;$i<$indent;$i++){
			$marge.= $lig_indent;
		}
		$form = str_replace('<!-- marge -->', $marge, $form);
		
		$nb_sr = rubriques::countChilds($row->id_rubrique);
		if ($nb_sr) {
			$form = str_replace('<!-- img_plus -->', $lig_rub_img, $form);
		} else {
			$form = str_replace('<!-- img_plus -->', '', $form);
		}
		$form = str_replace('<!-- sous_rub -->', '<!-- sous_rub'.$row->id_rubrique.' -->', $form);
		$form = str_replace('!!id_rub!!', $row->id_rubrique, $form);
		$form = str_replace('!!id_parent!!', $row->num_parent, $form);
		$libelle = htmlentities($row->libelle, ENT_QUOTES, $charset);
		$form = str_replace('!!lib_rub!!', $libelle, $form);

		//montant total
		$mnt['tot'][$k_htttc]=$row->montant;
		//montant a valider
		$mnt['ava'] = rubriques::calcAValider($row->id_rubrique);
		//montant engage
		$mnt['eng'] = rubriques::calcEngage($row->id_rubrique);
		//montant facture
		$mnt['fac'] = rubriques::calcFacture($row->id_rubrique);
		//montant paye
		$mnt['pay'] = rubriques::calcPaye($row->id_rubrique);
		//solde 
		$mnt['sol'][$k_htttc]=$mnt['tot'][$k_htttc]-$mnt['eng'][$k_htttc];
		$lib_mnt=array();
		foreach($mnt as $k=>$v) {
			$lib_mnt[$k]=number_format($mnt[$k][$k_htttc],2,'.',' ');
		}
		if ($bud->type_budget == TYP_BUD_GLO ) {
			$lib_mnt['tot']='&nbsp;';
			$lib_mnt['sol']='&nbsp;';			
		}
		foreach($lib_mnt as $k=>$v) {
			$form = str_replace('!!mnt_'.$k.'!!', $v, $form);
		}
						
		if ($nb_sr) {
			afficheSousRubriques($bud, $row->id_rubrique, $form, $indent+1);
		}
	}
}



//Traitement des actions
print "<h1>".htmlentities($msg['acquisition_ach_ges'],ENT_QUOTES, $charset)."&nbsp;:&nbsp;".htmlentities($msg['acquisition_menu_ref_budget'],ENT_QUOTES, $charset)."</h1>";

switch($action) {

	case 'list':
		entites::setSessionBibliId($id_bibli);
		show_list_bud($id_bibli);
		break;

	case 'show':
		show_bud($id_bibli, $id_bud);
		break;

	default:
		show_list_biblio();	
		break;
		
}
?>
