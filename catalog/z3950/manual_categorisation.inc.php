<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: manual_categorisation.inc.php,v 1.7 2009-10-09 13:45:02 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/category.class.php");
include("$include_path/templates/z3950_form.tpl.php");

function get_category_automap_information($libelle) {
	global $dbh;
	$sql = "SELECT num_thesaurus, num_noeud, libelle_thesaurus, libelle_categorie FROM categories LEFT JOIN thesaurus ON (categories.num_thesaurus = thesaurus.id_thesaurus) WHERE libelle_categorie = '".addslashes($libelle)."'";

	$result = array();
	$res = mysql_query($sql, $dbh);
	while (($row = mysql_fetch_assoc($res))) {
		$aresult = array();
		$result[$row["num_thesaurus"]][] = $row;
	}
	return $result;
}

function get_manual_categorisation_form($tableau_600="",$tableau_601="",$tableau_602="",$tableau_604="",$tableau_605="",$tableau_606="",$tableau_607="",$tableau_608="") {
	global $dbh, $charset, $ptab, $msg;
	$glue = ' -- ';

	$thesaurus = array();
	$thesaurus_sql = "SELECT id_thesaurus, libelle_thesaurus FROM thesaurus";
	$res = mysql_query($thesaurus_sql, $dbh);
	while (($row = mysql_fetch_assoc($res)))
		$thesaurus[] = $row;

	$vedettes = array();
	//600
	for ($i=0, $count=count($tableau_600['info_600_a']); $i<$count; $i++) {
		$apieces = array();
		$apieces = array_merge($apieces, $tableau_600['info_600_a'][$i]);
		$apieces = array_merge($apieces, $tableau_600['info_600_b'][$i]);
		$apieces = array_merge($apieces, $tableau_600['info_600_c'][$i]);
		$apieces = array_merge($apieces, $tableau_600['info_600_d'][$i]);
		$apieces = array_merge($apieces, $tableau_600['info_600_f'][$i]);
		$apieces = array_merge($apieces, $tableau_600['info_600_g'][$i]);
		$apieces = array_merge($apieces, $tableau_600['info_600_j'][$i]);
		$apieces = array_merge($apieces, $tableau_600['info_600_p'][$i]);
		$apieces = array_merge($apieces, $tableau_600['info_600_t'][$i]);
		$apieces = array_merge($apieces, $tableau_600['info_600_x'][$i]);
		$apieces = array_merge($apieces, $tableau_600['info_600_y'][$i]);
		$apieces = array_merge($apieces, $tableau_600['info_600_z'][$i]);
		$vedette = implode($glue, $apieces);
		$vedettes[] = $vedette;
	}
	//601
	for ($i=0, $count=count($tableau_601['info_601_a']); $i<$count; $i++) {
		$apieces = array();
		$apieces = array_merge($apieces, $tableau_601['info_601_a'][$i]);
		$apieces = array_merge($apieces, $tableau_601['info_601_b'][$i]);
		$apieces = array_merge($apieces, $tableau_601['info_601_c'][$i]);
		$apieces = array_merge($apieces, $tableau_601['info_601_d'][$i]);
		$apieces = array_merge($apieces, $tableau_601['info_601_e'][$i]);
		$apieces = array_merge($apieces, $tableau_601['info_601_f'][$i]);
		$apieces = array_merge($apieces, $tableau_601['info_601_g'][$i]);
		$apieces = array_merge($apieces, $tableau_601['info_601_h'][$i]);
		$apieces = array_merge($apieces, $tableau_601['info_601_j'][$i]);
		$apieces = array_merge($apieces, $tableau_601['info_601_t'][$i]);
		$apieces = array_merge($apieces, $tableau_601['info_601_x'][$i]);
		$apieces = array_merge($apieces, $tableau_601['info_601_y'][$i]);
		$apieces = array_merge($apieces, $tableau_601['info_601_z'][$i]);
		$vedette = implode($glue, $apieces);
		$vedettes[] = $vedette;
	}
	//602
	for ($i=0, $count=count($tableau_602['info_602_a']); $i<$count; $i++) {
		$apieces = array();
		$apieces = array_merge($apieces, $tableau_602['info_602_a'][$i]);
		$apieces = array_merge($apieces, $tableau_602['info_602_f'][$i]);
		$apieces = array_merge($apieces, $tableau_602['info_602_j'][$i]);
		$apieces = array_merge($apieces, $tableau_602['info_602_t'][$i]);
		$apieces = array_merge($apieces, $tableau_602['info_602_x'][$i]);
		$apieces = array_merge($apieces, $tableau_602['info_602_y'][$i]);
		$apieces = array_merge($apieces, $tableau_602['info_602_z'][$i]);
		$vedette = implode($glue, $apieces);
		$vedettes[] = $vedette;
	}
	//604
	for ($i=0, $count=count($tableau_604['info_604_a']); $i<$count; $i++) {
		$apieces = array();
		$apieces = array_merge($apieces, $tableau_604['info_604_a'][$i]);
		$apieces = array_merge($apieces, $tableau_604['info_604_h'][$i]);
		$apieces = array_merge($apieces, $tableau_604['info_604_i'][$i]);
		$apieces = array_merge($apieces, $tableau_604['info_604_j'][$i]);
		$apieces = array_merge($apieces, $tableau_604['info_604_k'][$i]);
		$apieces = array_merge($apieces, $tableau_604['info_604_l'][$i]);
		$apieces = array_merge($apieces, $tableau_604['info_604_x'][$i]);
		$apieces = array_merge($apieces, $tableau_604['info_604_y'][$i]);
		$apieces = array_merge($apieces, $tableau_604['info_604_z'][$i]);
		$vedette = implode($glue, $apieces);
		$vedettes[] = $vedette;
	}
	//605
	for ($i=0, $count=count($tableau_605['info_605_a']); $i<$count; $i++) {
		$apieces = array();
		$apieces = array_merge($apieces, $tableau_605['info_605_a'][$i]);
		$apieces = array_merge($apieces, $tableau_605['info_605_h'][$i]);
		$apieces = array_merge($apieces, $tableau_605['info_605_i'][$i]);
		$apieces = array_merge($apieces, $tableau_605['info_605_j'][$i]);
		$apieces = array_merge($apieces, $tableau_605['info_605_k'][$i]);
		$apieces = array_merge($apieces, $tableau_605['info_605_l'][$i]);
		$apieces = array_merge($apieces, $tableau_605['info_605_m'][$i]);
		$apieces = array_merge($apieces, $tableau_605['info_605_n'][$i]);
		$apieces = array_merge($apieces, $tableau_605['info_605_q'][$i]);
		$apieces = array_merge($apieces, $tableau_605['info_605_r'][$i]);
		$apieces = array_merge($apieces, $tableau_605['info_605_s'][$i]);
		$apieces = array_merge($apieces, $tableau_605['info_605_u'][$i]);
		$apieces = array_merge($apieces, $tableau_605['info_605_w'][$i]);
		$apieces = array_merge($apieces, $tableau_605['info_605_j'][$i]);
		$apieces = array_merge($apieces, $tableau_605['info_605_x'][$i]);
		$apieces = array_merge($apieces, $tableau_605['info_605_y'][$i]);
		$apieces = array_merge($apieces, $tableau_605['info_605_z'][$i]);
		$vedette = implode($glue, $apieces);
		$vedettes[] = $vedette;
	}
	//606
	for ($i=0, $count=count($tableau_606['info_606_a']); $i<$count; $i++) {
		$apieces = array();
		$apieces = array_merge($apieces, $tableau_606['info_606_a'][$i]);
		$apieces = array_merge($apieces, $tableau_606['info_606_j'][$i]);
		$apieces = array_merge($apieces, $tableau_606['info_606_x'][$i]);
		$apieces = array_merge($apieces, $tableau_606['info_606_y'][$i]);
		$apieces = array_merge($apieces, $tableau_606['info_606_z'][$i]);
		$vedette = implode($glue, $apieces);
		$vedettes[] = $vedette;
	}
	//607
	for ($i=0, $count=count($tableau_607['info_607_a']); $i<$count; $i++) {
		$apieces = array();
		$apieces = array_merge($apieces, $tableau_607['info_607_a'][$i]);
		$apieces = array_merge($apieces, $tableau_607['info_607_j'][$i]);
		$apieces = array_merge($apieces, $tableau_607['info_607_x'][$i]);
		$apieces = array_merge($apieces, $tableau_607['info_607_y'][$i]);
		$apieces = array_merge($apieces, $tableau_607['info_607_z'][$i]);
		$vedette = implode($glue, $apieces);
		$vedettes[] = $vedette;
	}
	//608
	for ($i=0, $count=count($tableau_608['info_608_a']); $i<$count; $i++) {
		$apieces = array();
		$apieces = array_merge($apieces, $tableau_608['info_608_a'][$i]);
		$apieces = array_merge($apieces, $tableau_608['info_608_j'][$i]);
		$apieces = array_merge($apieces, $tableau_608['info_608_x'][$i]);
		$apieces = array_merge($apieces, $tableau_608['info_608_y'][$i]);
		$apieces = array_merge($apieces, $tableau_608['info_608_z'][$i]);
		$vedette = implode($glue, $apieces);
		$vedettes[] = $vedette;
	}	
	$result = "";
	$count=0;
	$automap_js = "var node_to_captions = [];\n";
	foreach($vedettes as $vedette) {
		$automap_info = get_category_automap_information($vedette);
		if ($automap_info) {
			$thesaurus_combo_box = "&nbsp;<i>".$msg["notice_integre_categorisation_manual_use_from_thesaurus"].'</i>&nbsp;<select onchange="automap_category(!!name!!)" name = "!!name!!">';
			$thesaurus_combo_box .= '<option value=""> </option>';
			foreach($automap_info as $automap_infuu) {
				$count=0;
				foreach ($automap_infuu as $automap_infu) {
					$acategory = new category($automap_infu["num_noeud"]);
					$dapath = "[".$automap_infu["libelle_thesaurus"]."] ";
					foreach($acategory->path_table as $node) {
						$dapath .= $node["libelle"]." -- ";
					}
					$dapath .= $automap_infu["libelle_categorie"];
					
					$option_title = count($automap_infuu) > 1 ? $dapath : "";
					$option_content = htmlentities($automap_infu["libelle_thesaurus"], ENT_QUOTES, $charset);
					$option_content .= ($count>0 ? " - ".$count : "");
					$thesaurus_combo_box .= '<option title="'.$option_title.'" value="'.$automap_infu["num_noeud"].'">'.$option_content.'</option>';

					$automap_js .= "node_to_captions[".$automap_infu["num_noeud"]."] = '".addslashes($dapath)."';\n";
					$count++;					
				}
			}
			$thesaurus_combo_box .= '</select>';
		}
		else 
			$thesaurus_combo_box = "<i>".$msg["notice_integre_categorisation_manual_none_from_thesaurus"]."</i>";
		
		$line = $ptab[60];
		z3950_notice::substitute("icateg", $count, $line);
		z3950_notice::substitute("vedette_libelle", $vedette, $line);
		z3950_notice::substitute("categ_libelle", "", $line);
		$acombobox = $thesaurus_combo_box;
		z3950_notice::substitute("name", "thesaurus_select_".$count, $acombobox);		
		$line = str_replace("!!thesaurus_select!!", $acombobox, $line);
		z3950_notice::substitute("thesaurus_select", $acombobox, $line);
		
		$result .= $line;
		$count++;
	}
	$automap_js_function = "
	function automap_category(aselect) {
		if (aselect.value == '') {
//			document.getElementById('f_categ'+id).value = '';
//			document.getElementById('f_categ_id'+id).value = '';
			return;
		}

		var avalue = aselect.value;
		var acaption = node_to_captions[aselect.value];
		categ_count=document.notice.max_categ.value;
		for (i=0; i<categ_count; i++) {
			if (document.getElementById('f_categ_id'+i).value == avalue)
				return;
		}
		for (i=0; i<categ_count; i++) {
			if ((document.getElementById('f_categ_id'+i).value == '')  || (document.getElementById('f_categ_id'+i).value == '0')) {
				document.getElementById('f_categ'+i).value = acaption;
				document.getElementById('f_categ_id'+i).value = avalue;				
				return;
			}
		}

		add_categ();
		id = categ_count;
		document.getElementById('f_categ'+id).value = acaption;
		document.getElementById('f_categ_id'+id).value = avalue;
	}
";
	$automap_js = "<script>\n".$automap_js_function."\n\n".$automap_js."</script>";
	$result = $automap_js."\n\n".$result;
	return $result;
}

?>