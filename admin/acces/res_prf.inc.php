<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: res_prf.inc.php,v 1.5 2009-07-28 17:01:07 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/acces.class.php");
require_once("$include_path/templates/acces.tpl.php");

//recuperation domaine
if (!$id) return;
if (!$ac) {
	$ac= new acces();
	$t_cat= $ac->getCatalog();
}
if (!$dom) {
	$dom=$ac->setDomain($id);
}


echo window_title($database_window_title.$msg[7].$msg[1003].$msg[1001]);
//construction menu
$admin_menu_acces = "<h1>".htmlentities($msg["admin_menu_acces"], ENT_QUOTES, $charset)."<span>&nbsp;&gt;&nbsp;!!menu_sous_rub!!</span></h1>";
$admin_menu_acces.= "<div class='hmenu'>";
foreach($t_cat as $k=>$v) {
	$lib=htmlentities($v['comment'], ENT_QUOTES, $charset);
	$admin_menu_acces.= '<span';
	if ($id==$k) {
		$admin_menu_acces.= " class='selected'";
		$menu_sous_rub=$lib;
		$menu_sous_rub.= '&nbsp;&gt;&nbsp;'.htmlentities($dom->getComment('res_prf_lib'), ENT_QUOTES, $charset);
	}
	$admin_menu_acces.= "><a href='./admin.php?categ=acces&sub=domain&action=view&id=".$k."'>$lib</a></span>";
}
unset($v);
$admin_menu_acces.= '</div>';
$admin_menu_acces=str_replace('!!menu_sous_rub!!',$menu_sous_rub, $admin_menu_acces);
$admin_layout = str_replace('!!menu_contextuel!!', $admin_menu_acces, $admin_layout);
print $admin_layout;


//Affiche la liste des profils ressources
function show_res_prf_list($id,$maj=false) {

	global $dbh, $msg, $charset;
	global $dom;
	global $res_prf_list_form,$used_list_form,$maj_form;

	$form = $res_prf_list_form;
	$form = str_replace('!!form_title!!', htmlentities($dom->getComment('res_prf_lib'), ENT_QUOTES, $charset), $form);

	//affichage lien roles utilisateurs
	$txt = htmlentities($dom->getComment('user_prf_lib'),ENT_QUOTES,$charset);
	$row = "<tr style=\"cursor: pointer;\" onmousedown=\"document.location='./admin.php?categ=acces&sub=user_prf&action=list&id=$id';\" ";
	$row.= "onmouseout=\"this.className='even'\" onmouseover=\"this.className='surbrillance'\" class=\"even\"><td><strong>$txt</strong></td></tr>";
	//affichage lien profils ressources
	$txt = htmlentities($dom->getComment('res_prf_lib'),ENT_QUOTES,$charset);
	$row.= "<tr style=\"cursor: pointer;\" onmousedown=\"document.location='./admin.php?categ=acces&sub=res_prf&action=list&id=$id';\" ";
	$row.= "onmouseout=\"this.className='odd'\" onmouseover=\"this.className='surbrillance'\" class=\"odd\"><td><strong>$txt</strong></td></tr>";
	$form = str_replace ('<!-- rows -->', $row, $form);
	
	$t=$dom->getResourceProperties();
	if (count($t)) {
		$p_form = "";
		foreach($t as $k=>$v){
			$p_form.= "<div class='row'>";
			$p_form.= "<input type='checkbox' id='chk_prop[$k]' name='chk_prop[]' value='".$k."' />";
			$p_form.= "&nbsp;<label class='etiquette' for='chk_prop[$k]'>".htmlentities($v['lib'], ENT_QUOTES, $charset)."</label>";
			$p_form.= "</div>";
		}
		$form = str_replace('<!-- properties -->', $p_form, $form);
	}
	
	$rows ="<tr><th>".htmlentities($msg['dom_prf_name'], ENT_QUOTES, $charset)."</th><th>".htmlentities($msg['dom_prf_use'], ENT_QUOTES, $charset)."</th><th>".htmlentities($msg['dom_prf_rule'], ENT_QUOTES, $charset)."</th></tr>";
	$parity = 1;
	$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='odd'\" ";
	$rows.= "<tr class='odd' ".$tr_javascript.">";
	$rows.= "<td>".htmlentities($dom->getComment('res_prf_def_lib'), ENT_QUOTES, $charset)."</td>";
	$rows.= "<td></td>";
	$rows.= "<td></td>";
	$rows.= "</tr>";
	
	$q=$dom->loadResourceProfiles();
	$r=mysql_query($q, $dbh);
	if (mysql_num_rows($r)) {
		
		//generation selecteur
		$selector = "<select name='!!sel_name!!' id='!!sel_name!!'>";
		$selector.= "<option value=\"0\" >".htmlentities($dom->getComment('res_prf_def_lib'), ENT_QUOTES, $charset)."</option>";
		while(($row = mysql_fetch_object($r))) {
			$selector .= "<option value=\"".$row->prf_id."\" >";
	 		$selector .= htmlentities($row->prf_name, ENT_QUOTES, $charset)."</option>";
		}                                         
		$selector .= "</select>";                 
		$selector .= "<script type=\"text/javascript\">!!sel_script!!</script>";

		mysql_data_seek($r,0);
		while(($row=mysql_fetch_object($r))) {
			
			if ($parity % 2) {
				$pair_impair = 'even';
			} else {
				$pair_impair = 'odd';
			}
			$parity += 1;
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='".$pair_impair."'\" ";
	        $rows.= "<tr class='".$pair_impair."' ".$tr_javascript.">";
	        $rows.= "<td><input type='text' class='in_cell' id='prf_lib[".$row->prf_id."]' name='prf_lib[".$row->prf_id."]' value='".htmlentities($row->prf_name, ENT_QUOTES, $charset)."' /></td>";
	        $rows.= "<td>";
	        $rows.= $selector;
	        $rows = str_replace('!!sel_name!!', "prf_used[".$row->prf_id."]", $rows);
	        $rows = str_replace('!!sel_script!!', "document.getElementById(\"prf_used[".$row->prf_id."]\").value=\"".$row->prf_used."\";" ,$rows);
	        $rows.= "</td>";
	        $rows.= "<td>";
	        $rows.= nl2br(htmlentities($row->prf_hrule,ENT_QUOTES, $charset));
	        $rows.= "<input type=hidden id='prf_hrule[".$row->prf_id."]' name='prf_hrule[".$row->prf_id."]' value='".$row->prf_hrule."' />";
	        $rows.= "</td>";
	        $rows.= "<input type='hidden' id='prf_id[".$row->prf_id."]' name='prf_id[".$row->prf_id."]' value='".$row->prf_id."' />";
	        $rows.= "<input type='hidden' id='prf_rule[".$row->prf_id."]' name='prf_rule[".$row->prf_id."]' value='".$row->prf_rule."' />";
	        $rows.= "</tr>";
		}
	}
	$used_list_form = str_replace('!!used_list_lib!!', htmlentities($dom->getComment('res_prf_used_list_lib'),ENT_QUOTES,$charset),$used_list_form);
	$used_list_form = str_replace('<!-- used_profiles -->', $rows,$used_list_form);
	$form = str_replace('<!-- used_list_form -->',$used_list_form,$form);
	
	$bt_calc = "<input type='button' onclick=\"
		this.form.action='./admin.php?categ=acces&sub=res_prf&action=calc&id=$id'; 
		this.form.submit();return false;\" 
		value=\"".$dom->getComment('res_prf_bt_calc')."\" class='bouton' />";
	$form = str_replace('<!-- bt_calc -->', $bt_calc,$form);

	$bt_enr = "<input type='button' onclick=\"
		this.form.action='./admin.php?categ=acces&sub=res_prf&action=update&id=$id'; 
		this.form.submit();return false;\" 
		value=\"".addslashes($msg['77'])."\" class='bouton' />";
	$form = str_replace('<!-- bt_enr -->', $bt_enr,$form);
	
	$bt_sup = "<input type='button' onclick=\"
		document.location='./admin.php?categ=acces&sub=res_prf&action=delete&id=$id';return false;\" 
		value=\"".addslashes($msg['63'])."\" class='bouton' />";
	$form = str_replace('<!-- bt_sup -->', $bt_sup,$form);
	
	if ($maj) {
		$form = str_replace('<!-- maj -->',$maj_form,$form);
	}
	print $form;
}


//Affiche la liste des profils ressources apres calcul
function show_calc_res_prf_list($id) {

	global $dbh,$msg,$charset;
	global $dom;
	global $res_prf_list_form,$calc_list_form,$unused_list_form;
	global $chk_prop;

	$form = $res_prf_list_form;
	$form = str_replace('!!form_title!!', htmlentities($dom->getComment('res_prf_lib'), ENT_QUOTES, $charset), $form);

	//affichage lien roles utilisateurs
	$txt = htmlentities($dom->getComment('user_prf_lib'),ENT_QUOTES,$charset);
	$row = "<tr style=\"cursor: pointer;\" onmousedown=\"document.location='./admin.php?categ=acces&sub=user_prf&action=list&id=$id';\" ";
	$row.= "onmouseout=\"this.className='even'\" onmouseover=\"this.className='surbrillance'\" class=\"even\"><td><strong>$txt</strong></td></tr>";
	//affichage lien profils ressources
	$txt = htmlentities($dom->getComment('res_prf_lib'),ENT_QUOTES,$charset);
	$row.= "<tr style=\"cursor: pointer;\" onmousedown=\"document.location='./admin.php?categ=acces&sub=res_prf&action=list&id=$id';\" ";
	$row.= "onmouseout=\"this.className='odd'\" onmouseover=\"this.className='surbrillance'\" class=\"odd\"><td><strong>$txt</strong></td></tr>";
	$form = str_replace ('<!-- rows -->', $row, $form);
	
	$t=$dom->getResourceProperties();
	if (count($t)) {
		$p_form = "";
		foreach($t as $k=>$v){
			$p_form.= "<div class='row'>";
			$p_form.= "<input type='checkbox' id='chk_prop[$k]' name='chk_prop[]' value='".$k."' />";
			$p_form.= "&nbsp;<label class='etiquette' for='chk_prop[$k]'>".htmlentities($v['lib'], ENT_QUOTES, $charset)."</label>";
			$p_form.= "</div>";
		}
		$form = str_replace('<!-- properties -->', $p_form, $form);
	}
	
	$rows = "<tr><th>".htmlentities($msg['dom_prf_name'], ENT_QUOTES, $charset)."</th><th>".htmlentities($msg['dom_prf_rule'], ENT_QUOTES, $charset)."</th><tr>";
	$parity = 1;
	$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='odd'\" ";
	$rows.= "<tr class='odd' ".$tr_javascript.">";
	$rows.= "<td>".htmlentities($dom->getComment('res_prf_def_lib'), ENT_QUOTES, $charset)."</td>";
	$rows.= "<td></td>";
	$rows.= "</tr>";
	
	
	//nouveaux profils
	$t_calc=$dom->calcResourceProfiles($chk_prop);
	$t_reused=array();

	if (count($t_calc)) {

		foreach($t_calc as $k=>$v) {
			
			if ($v['old']) $t_reused[]=$v['old'];
			if ($parity % 2) {
				$pair_impair = 'even';
			} else {
				$pair_impair = 'odd';
			}
			$parity += 1;
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='".$pair_impair."'\" ";
	        $rows.= "<tr class='".$pair_impair."' ".$tr_javascript.">";
	        $rows.= "<td>".htmlentities($v['name'], ENT_QUOTES, $charset)."</td>";
	        $rows.= "<input type='hidden' id='prf_lib[".$k."]' name='prf_lib[".$k."]' value='".htmlentities($v['name'], ENT_QUOTES, $charset)."' />";
	        $rows.= "<td>";
	        $rows.= nl2br(htmlentities($v['hrule'],ENT_QUOTES, $charset));	        
	        $rows.= "<input type=hidden id='prf_hrule[".$k."]' name='prf_hrule[".$k."]' value='".$v['hrule']."' />";
	        $rows.= "</td>";
	        $rows.= "<input type='hidden' id='prf_id[".$k."]' name='prf_id[".$k."]' value='".$v['old']."' />";
	        $rows.= "<input type='hidden' id='prf_rule[".$k."]' name='prf_rule[".$k."]' value='".$v['rule']."' />";
	        $rows.= "<input type='hidden' id='prf_used[".$k."]' name='prf_used[".$k."]' value='".$v['old']."' />";
	        $rows.= "</tr>";
		}
	}
	$calc_list_form = str_replace('!!calc_list_lib!!', htmlentities($dom->getComment('res_prf_calc_list_lib'),ENT_QUOTES,$charset),$calc_list_form);
	$calc_list_form = str_replace('<!-- calc_profiles -->', $rows,$calc_list_form);
	$form = str_replace('<!-- calc_list_form -->', $calc_list_form,$form);

	
	//anciens profils inutilises a reaffecter
	$q_unused=$dom->loadUsedResourceProfiles($t_reused);
	$r_unused=mysql_query($q_unused,$dbh);
	if(mysql_num_rows($r_unused)){
		
		//generation selecteur
		$selector = "<select name='!!sel_name!!' id='!!sel_name!!'>";
		$selector.= "<option value=\"0\" >".htmlentities($dom->getComment('res_prf_def_lib'), ENT_QUOTES, $charset)."</option>";
		foreach($t_calc as $k=>$v) {
			$selector.= "<option value=\"".$v['old']."\" >";
			$selector.= htmlentities($v['name'], ENT_QUOTES, $charset)."</option>";
		}
		$selector .= "</select>";                 
		$selector .= "<script type=\"text/javascript\">!!sel_script!!</script>";
		
		$parity = 0;
		$rows="<tr><th>".htmlentities($msg['dom_prf_name'], ENT_QUOTES, $charset)."</th><th>".htmlentities($msg['dom_prf_use'], ENT_QUOTES, $charset)."</th><th>".htmlentities($msg['dom_prf_rule'], ENT_QUOTES, $charset)."</th><tr>";
		while(($row_unused=mysql_fetch_object($r_unused))) {
			
			if ($parity % 2) {
				$pair_impair = 'even';
			} else {
				$pair_impair = 'odd';
			}
			$parity += 1;
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='".$pair_impair."'\" ";
	        $rows.= "<tr class='".$pair_impair."' ".$tr_javascript.">";
	        $rows.= "<td>".htmlentities($row_unused->prf_name, ENT_QUOTES, $charset)."</td>";
			$rows.= "<td>";
	     	$rows.= $selector;
	        $rows = str_replace('!!sel_name!!', "prf_used[".$row_unused->prf_id."]", $rows);
	        $rows = str_replace('!!sel_script!!', "document.getElementById(\"prf_used[".$row_unused->prf_id."]\").value=\"".$row_unused->prf_used."\";" ,$rows);
	        $rows.= "</td>";
	        $rows.= "<td>";
	        $rows.= nl2br(htmlentities($row_unused->prf_hrule,ENT_QUOTES, $charset));
	        $rows.= "</td>";
	        $rows.= "<input type='hidden' id='unused_prf_id[".$row_unused->prf_id."]' name='unused_prf_id[".$row_unused->prf_id."]' value='".$row_unused->prf_id."' />";
	        $rows.= "</tr>";
			
		}
		$unused_list_form = str_replace('!!unused_list_lib!!',htmlentities($dom->getComment('res_prf_unused_list_lib'),ENT_QUOTES,$charset),$unused_list_form);
		$unused_list_form = str_replace('<!-- unused_profiles -->',$rows,$unused_list_form);
		$form = str_replace('<!-- unused_list_form -->',$unused_list_form,$form);
	}
	
	$bt_calc = "<input type='button' onclick=\"
		this.form.action='./admin.php?categ=acces&sub=res_prf&action=calc&id=$id'; 
		this.form.submit();return false;\" 
		value=\"".$dom->getComment('res_prf_bt_calc')."\" class='bouton' />";
	$form = str_replace('<!-- bt_calc -->', $bt_calc,$form);

	$bt_enr = "<input type='button' onclick=\"
		this.form.action='./admin.php?categ=acces&sub=res_prf&action=update&id=$id'; 
		this.form.submit();return false;\" 
		value=\"".addslashes($msg['77'])."\" class='bouton' />";
	$form = str_replace('<!-- bt_enr -->', $bt_enr,$form);
	
	print $form;
}



switch ($action) {
	case'calc' :
		if (count($chk_prop)) {
			show_calc_res_prf_list($id);
		} else {
			error_form_message(addslashes($msg['dom_prop_chx_err']));
		}
		break;

	case 'update' :
		$dom->saveResourceProfiles($prf_id, $prf_lib, $prf_rule, $prf_hrule, $prf_used, $unused_prf_id);
		show_res_prf_list($id,true);
		break;
		
	case 'delete' :
		$dom->deleteResourceProfiles();
		show_res_prf_list($id);
		break;
		
	case 'list':
	default:
		show_res_prf_list($id);
		break;
}
?>