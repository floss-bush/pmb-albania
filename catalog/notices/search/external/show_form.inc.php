<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: show_form.inc.php,v 1.3 2008-08-28 14:08:29 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");


//Si c'est une multi
if ($_SESSION["ext_type"]=="multi") {
	if (!$search) {
		$search[0]="s_2";
		$op_0_s_2="EQ";
		$field_0_s_2=array();
	} else {
		//Recherche du champp source, s'il n'est pas présent, on décale tout et on l'ajoute
		$flag_found=false;
		for ($i=0; $i<count($search); $i++) {
			if ($search[$i]=="s_2") { $flag_found=true; break; }
		}
		if (!$flag_found) {
			//Pas trouvé, on décale tout !!
			for ($i=count($search)-1; $i>=0; $i--) {
				$search[$i+1]=$search[$i];
				decale("field_".$i."_".$search[$i],"field_".($i+1)."_".$search[$i]);
				decale("op_".$i."_".$search[$i],"op_".($i+1)."_".$search[$i]);
				decale("inter_".$i."_".$search[$i],"inter_".($i+1)."_".$search[$i]);
				decale("fieldvar_".$i."_".$search[$i],"fieldvar_".($i+1)."_".$search[$i]);
			}
			$search[0]="s_2";
			$op_0_s_2="EQ";
			$field_0_s_2=array();
		}
	}
	$sources="";
} else {
	//sinon s'il y a un environnement, on le restaure
	if (count($search)) {
		//Recherche du champp source, s'il n'est pas présent, on décale tout et on l'ajoute
		$flag_found=false;
		for ($i=0; $i<count($search); $i++) {
			if ($search[$i]=="s_2") { $flag_found=true; break; }
		}
		if ($flag_found) {
			$source=$field_0_s_2;
			//On décale tout !!
			for ($i=0; $i<count($search)-1; $i++) {
				$search[$i]=$search[$i+1];
				decale("field_".($i+1)."_".$search[$i],"field_".$i."_".$search[$i]);
				decale("op_".($i+1)."_".$search[$i],"op_".$i."_".$search[$i]);
				decale("inter_".($i+1)."_".$search[$i],"inter_".$i."_".$search[$i]);
				decale("fieldvar_".($i+1)."_".$search[$i],"fieldvar_".$i."_".$search[$i]);
			}
			//On efface le dernier
			unset($search[$i]);
		}
	}
	$sources=do_sources();
}

if (isset($notice_id))
	$notice_id_info = "&notice_id=".$notice_id;
else
	$notice_id_info = "";

$form_to_show=$sc->show_form("./catalog.php?categ=search&mode=7".$notice_id_info,"./catalog.php?categ=search&mode=7&sub=launch".$notice_id_info);
if ($_SESSION["ext_type"]=="simple") {
	$form_to_show=str_replace("<!--!!precise_h3!!-->","<div class='right' style='font-size:0.8em'>".sprintf($msg["connecteurs_search_multi"],"catalog.php?categ=search&mode=7&external_type=multi".$notice_id_info)."</div>",$form_to_show);
	$form_to_show=str_replace("<!--!!before_form!!-->","<h3>".$msg["connecteurs_source_label"]."</h3>\n".$sources."<h3>".$msg["connecteurs_external_criterias"]."</h3>",$form_to_show);
}
print $form_to_show;
?>
