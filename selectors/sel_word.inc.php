<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sel_word.inc.php,v 1.3 2009-05-16 10:52:44 dbellamy Exp $letter $mot

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");		

// contenu popup sélection de mot
require('./selectors/templates/sel_word.tpl.php');	

// la variable $caller, passée par l'URL, contient le nom du form appelant
$baseurl = "./select.php?what=synonyms&caller=$caller&p1=$p1&p2=$p2";

switch ($action) {
case 'add':
	//ajout de l'url
	$add_word=str_replace("!!action!!",$baseurl,$add_word);
	print $add_word;
	break;
case 'modif':
	if($f_word_add) {
		//vérification de l'existence
		$rqt="select id_mot, mot from mots left join linked_mots on (num_mot=id_mot) where mot='".$f_word_add."' and id_mot not in (select num_mot from linked_mots where linked_mots.num_linked_mot=0) group by id_mot";
		//$rqt="select id_mot from mots where mot='".$f_word_add."'";
		$execute_query=mysql_query($rqt);
		if (!$execute_query||!mysql_num_rows($execute_query)) {	
				@mysql_query("INSERT INTO mots (mot) values ('".addslashes($f_word_add)."')");
				$deb_rech=$f_word_add;
				$letter=convert_diacrit(pmb_strtolower(pmb_substr($deb_rech,0,1)));
		} else print "<script> alert('".$msg["word_exist"]."'); document.location='".$baseurl."&action=add';</script>"; 
	} else print "<script> alert('".$msg["word_error"]."'); document.location='".$baseurl."&action=add';</script>";
default :

	//-------------------------------------------
	//	$jscript : script de m.a.j. du parent
	//-------------------------------------------
	$jscript = "
	<script type='text/javascript'>
	<!--
	function set_parent(id_value,libelle_value)
	{
		window.opener.document.forms['$caller'].elements['$p1'].value = id_value;
		window.opener.document.forms['$caller'].elements['$p2'].value = reverse_html_entities(libelle_value);
		window.close();
	}
	-->
	</script>
	";

	$words_for_syn=array();
	$words_for_syn1=array();
	//recherche des mots
	$rqt="select id_mot, mot from mots left join linked_mots on (num_mot=id_mot) where id_mot not in (select num_mot from linked_mots where linked_mots.num_linked_mot=0) group by id_mot order by mot";
	$execute_query=mysql_query($rqt);
	while ($r=mysql_fetch_object($execute_query)) {
		$words_for_syn[$r->id_mot]=stripslashes($r->mot);
		$words_for_syn1[$r->id_mot]=convert_diacrit(pmb_strtolower($r->mot));
	}
	
	if (count($words_for_syn)) {
		//toutes les lettres de l'alphabet dans un tableau
		$alphabet=array();
		$alphabet[]='';
		for ($i=97;$i<=122;$i++) {
			$alphabet[]=chr($i);	
		}
		$bool=false;
		foreach($words_for_syn as $val) {
			if ($val!="") {
				$carac=convert_diacrit(pmb_strtolower(pmb_substr($val,0,1)));
				if ($bool==false) {
					if ($deb_rech) $premier_carac=convert_diacrit(pmb_strtolower(pmb_substr($deb_rech,0,1)));
						else $premier_carac=$carac;
					$bool=true;
				}
				if (array_search($carac,$alphabet)===FALSE) $alphabet_num[]=$carac;
			}
		}
		//dédoublonnage du tableau des autres caractères
		if (count($alphabet_num)) $alphabet_num = array_unique($alphabet_num);
		
		if (!$letter) {
			if (count($alphabet_num)) $letter="My";
				elseif ($premier_carac) $letter=$premier_carac;
				else $letter="a";
		} elseif (!array_search($letter,$alphabet)) $letter="My";
		
		// affichage d'un sommaire par lettres
		$affichage_lettres="<div class='row' style='margin-left:10px;'>";
		if (count($alphabet_num)) {
			if ($letter=='My') $affichage_lettres.="<font size='+1'><strong><u>#</u></strong></font> ";
				else $affichage_lettres.="<a href='$baseurl&letter=My'>#</a> ";
		}
		foreach($alphabet as $char) {
			$present = pmb_preg_grep("/^$char/i", $words_for_syn1);
			if(sizeof($present) && strcasecmp($letter, $char))
				$affichage_lettres.="<a href='$baseurl&letter=$char'>$char</a> ";
			else if(!strcasecmp($letter, $char))
				$affichage_lettres.="<font size='+1'><strong><u>$char</u></strong></font> ";
			else $affichage_lettres.="<span class='gris'>".$char."</span> ";
		}
		$affichage_lettres.="</div>";
	
		//affichage des mots
		$affichage_mots="<div class='row' style='margin-left:10px;'>";
		
		$compt=0;
		if (!$page) $page=1;
		if (!$nb_per_page) $nb_per_page=$nb_per_page_select;
		//parcours du tableau de mots, découpage en colonne et détermination des valeurs par rapport à la pagination et la lettre
		foreach ($words_for_syn as $key=>$valeur_syn) {
			if ($valeur_syn!="") {
				
				if ($letter!='My') {
					if (preg_match("/^$letter/i", convert_diacrit(pmb_strtolower($valeur_syn)))) {
						if (($compt>=(($page-1)*$nb_per_page))&&($compt<($page*$nb_per_page))) {
								$affichage_mots.="<a href='#' onClick=\"set_parent('".$key."','".htmlentities(addslashes($valeur_syn),ENT_QUOTES,$charset)."')\">";
								$affichage_mots.=htmlentities($valeur_syn,ENT_QUOTES,$charset)."</a><br />\n";
							} 
							$compt++;
						}
				} else {
					if (pmb_substr($valeur_syn,0,1)=='0'||!array_search(convert_diacrit(pmb_strtolower(pmb_substr($valeur_syn,0,1))),$alphabet)) {
						if (($compt>=(($page-1)*$nb_per_page))&&($compt<($page*$nb_per_page))) {
							$affichage_mots.="<a href='#' onClick=\"set_parent('".$key."','".htmlentities(addslashes($valeur_syn),ENT_QUOTES,$charset)."')\">";
							$affichage_mots.=htmlentities($valeur_syn,ENT_QUOTES,$charset)."</a><br />\n";
						}					
					} 
					$compt++;
				} 
			}
		}
		$affichage_mots.="</div>";
		$affichage_mots.="<div class='row'>&nbsp;</div><hr />\n";
		//affichage de la pagination
		$affichage_mots.=aff_pagination ($baseurl."&user_input=$user_input&letter=".$letter, $compt, $nb_per_page, $page) ;
		$affichage_mots.="<div class='row'>&nbsp;</div>\n";
	}
	//ajout du script
	$sel_word=str_replace("!!jscript!!",$jscript,$sel_word);
	//ajout des lettres
	$sel_word=str_replace("!!lettres!!",$affichage_lettres,$sel_word);
	//ajout des mots
	$sel_word=str_replace("!!liste_mots!!",$affichage_mots,$sel_word);
	//ajout de l'url
	$sel_word=str_replace("!!action!!",$baseurl,$sel_word);
	print $sel_word;
}
?>