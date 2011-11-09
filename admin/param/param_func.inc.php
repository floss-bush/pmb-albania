<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: param_func.inc.php,v 1.17 2011-04-11 09:24:58 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// affichage du form de création/modification paramêtres

require_once($include_path."/parser.inc.php");

function param_form($id_param=0, $type_param="", $sstype_param="", $valeur_param="", $comment_param="") {
	global $msg;
	global $admin_param_form;
	global $charset;

	$title = $msg[1606]; // modification

	$admin_param_form = str_replace('!!form_title!!', $title, $admin_param_form);
	$admin_param_form = str_replace('!!id_param!!', $id_param, $admin_param_form);
	$admin_param_form = str_replace('!!type_param!!', $type_param, $admin_param_form);
	$admin_param_form = str_replace('!!sstype_param!!', $sstype_param, $admin_param_form);
	$admin_param_form = str_replace('!!valeur_param!!', htmlentities($valeur_param,ENT_QUOTES,$charset), $admin_param_form);
	$admin_param_form = str_replace('!!comment_param!!', htmlentities($comment_param,ENT_QUOTES,$charset), $admin_param_form);

	print $admin_param_form;
	
	}

function _section_($param) {
	global $section_table;
	
	$section_table[$param["NAME"]]["LIB"]=$param["value"];
	$section_table[$param["NAME"]]["ORDER"]=$param["ORDER"];
}

function show_param($dbh) {

	global $msg;
	global $begin_result_liste;
	global $form_type_param, $form_sstype_param ; // si modif , ces valeurs sont connues, on va faire une ancre avec
	global $lang;
	global $include_path;
	global $section_table;
	
	$allow_section=0;
	
	if (file_exists($include_path."/section_param/$lang.xml")) {
		_parser_($include_path."/section_param/$lang.xml",array("SECTION"=>"_section_"),"PMBSECTIONS");
		$allow_section=1;
	}
	print $begin_result_liste ;
	
	$requete = "select * from parametres where gestion=0 order by type_param, section_param, sstype_param ";
	$res = mysql_query($requete, $dbh);
	$i=0;
	while($param=mysql_fetch_object($res)) {
		if (!$type_param) {
			$type_param=$param->type_param;
			$creer = 1;
			$fincreer = 0;
			$odd_even=0;
		} elseif ($type_param!=$param->type_param) {
			$type_param=$param->type_param;
			$creer = 1;
			$fincreer = 1;
			$odd_even=0;
		} else {
			$creer = 0;
			$fincreer = 0;
		}
		if (($section_param!=$param->section_param)&&($allow_section)) {
			$section_param=$param->section_param;
			$creer_section=1;
		} else $creer_section=0; 


		if ($fincreer) {
			print "\n</table></div>";
		}
		if ($creer) {
			
			$lab_param = $msg["param_".$type_param];
			if ($lab_param=="") $lab_param=$type_param;
			
			print "\n<div id=\"el".$type_param."Parent\" class='parent' width=\"100%\">
					<img src=\"./images/plus.gif\" class=\"img_plus\" name=\"imEx\" id=\"el".$type_param."Img\" title=\"".$msg['admin_param_detail']."\" border=\"0\" onClick=\"expandBase('el".$type_param."', true); return false;\" hspace=\"3\">
					<span class='heada'>".$lab_param."</span>
					<br />
					</div>\n
					<div id=\"el".$type_param."Child\" class=\"child\" style=\"margin-bottom:6px;display:none;\"";
			if ($form_type_param==$type_param) {
				print " startOpen='Yes' " ;
			}
			print ">";
			print "\n<table><tr>";
			print "
				<th>".$msg[1603]."</th>
				<th>".$msg[1604]."</th>
				<th>".$msg['param_explication']."</th></tr>";
		}
		if ($odd_even==0) {
			$class_liste="odd";
			$odd_even=1;
		} else if ($odd_even==1) {
			$class_liste="even";
			$odd_even=0;
		}
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$class_liste'\" onmousedown=\"document.location='./admin.php?categ=param&action=modif&id_param=".$param->id_param."';\" ";
		if ($creer_section) { print "\n<tr><th colspan='3'><b>".$section_table[$section_param]["LIB"]."</b></th></tr>"; }
		if ( $param->type_param==$form_type_param && $param->sstype_param==$form_sstype_param) {
			print "\n<tr class='$class_liste' $tr_javascript style='cursor: pointer; background: #FF2222;'>
				<td valign='top'><a name='justmodified' ></a>$param->sstype_param</td>";
			} else {
				print "\n<tr class='$class_liste' $tr_javascript style='cursor: pointer'>
					<td valign='top'>$param->sstype_param</td>";
				}	
		print "<td class='ligne_data'>$param->valeur_param</td><td valign='top'>$param->comment_param</td>\n</tr>";
		} // fin while
	print "</table></div>";
	
	}
