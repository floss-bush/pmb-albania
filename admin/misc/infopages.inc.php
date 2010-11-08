<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: infopages.inc.php,v 1.1 2008-08-29 09:58:37 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion des pages d'information

function show_infopages($dbh) {
	global $msg, $charset, $opac_url_base;

	print "<table>
		<tr>
			<th width='3%'>".$msg['infopages_id_infopage']."</th>
			<th width='3%'>".$msg['infopage_valid_infopage']."</th>
			<th>".$msg['infopage_title_infopage']."</th>
			<th>".$msg['infopage_lien_direct']."</th>
		</tr>";

	$requete = "select id_infopage, title_infopage, content_infopage, valid_infopage from infopages order by valid_infopage DESC, title_infopage ";
	$res = mysql_query($requete, $dbh);

	$nbr = mysql_num_rows($res);

	$parity=1;
	for($i=0;$i<$nbr;$i++) {
		$row=mysql_fetch_object($res);
		if ($parity % 2) {
			$pair_impair = "even";
		} else {
			$pair_impair = "odd";
		}
		$parity += 1;
		$tr_javascript="class='$pair_impair' style='cursor: pointer' onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" ";
		$td_javascript="onmousedown=\"document.location='./admin.php?categ=infopages&sub=infopages&action=modif&id=$row->id_infopage';\" ";
		print "<tr $tr_javascript>";
		print "<td $td_javascript align='right'><b>".$row->id_infopage."</b></td>";
		if ($row->valid_infopage) $visible="X" ; 
		else $visible="&nbsp;" ;
		print "<td $td_javascript align='center' class='erreur'>$visible</td>" ;
		print "<td $td_javascript>".htmlentities($row->title_infopage, ENT_QUOTES, $charset)."</td>";
		print "<td><a href=\"".$opac_url_base."index.php?lvl=infopages&pagesid=".$row->id_infopage."\" target=_blank>".htmlentities($opac_url_base."index.php?lvl=infopages&pagesid=".$row->id_infopage, ENT_QUOTES, $charset)."</a></td>" ;
        print "</tr>";
		}
	print "</table>
		<input class='bouton' type='button' value=\" ".$msg['infopages_bt_ajout']." \" onClick=\"document.location='./admin.php?categ=infopages&sub=infopages&action=add'\" />";
	}

function infopage_form($id=0, $title_infopage="", $content_infopage="", $valid_infopage=1) {
	global $msg, $pmb_javascript_office_editor;
	global $admin_infopages_form;
	global $charset;
	
	if ($pmb_javascript_office_editor) 
		print $pmb_javascript_office_editor ;


	$admin_infopages_form = str_replace('!!id!!', $id, $admin_infopages_form);

	if (!$id) $admin_infopages_form = str_replace('!!form_title!!', $msg['infopages_creer'], $admin_infopages_form);
	else $admin_infopages_form = str_replace('!!form_title!!', $msg['infopages_modifier'], $admin_infopages_form);

	$admin_infopages_form = str_replace('!!title_infopage!!', htmlentities($title_infopage,ENT_QUOTES, $charset), $admin_infopages_form);
	$admin_infopages_form = str_replace('!!libelle_suppr!!', htmlentities(addslashes($title_infopage),ENT_QUOTES, $charset), $admin_infopages_form);

	$admin_infopages_form = str_replace('!!content_infopage!!', htmlentities($content_infopage,ENT_QUOTES, $charset), $admin_infopages_form);

	if ($valid_infopage) 
		$checkbox="checked"; 
	else 
		$checkbox="";
	$admin_infopages_form = str_replace('!!checkbox!!', $checkbox, $admin_infopages_form);

	print confirmation_delete("./admin.php?categ=infopages&sub=infopages&action=del&id=");
	print "<script type=\"text/javascript\">
		function test_form(form) {
		if(form.form_title_infopage.value.length == 0) {
			alert(\"".$msg[98]."\");
			return false;
		}
		return true;
		}
		</script>";
	print $admin_infopages_form;
	}

$admin_layout = str_replace('!!menu_sous_rub!!', $msg['infopages_admin_menu'], $admin_layout);
print $admin_layout;

switch($action) {
	case 'update':
		$set_values = "SET title_infopage='$form_title_infopage', content_infopage='$form_content_infopage', valid_infopage='$form_valid_infopage' " ;
		if($id) {
			$requete = "UPDATE infopages $set_values WHERE id_infopage='$id' ";
			$res = mysql_query($requete, $dbh);
		} else {
			$requete = "INSERT INTO infopages $set_values ";
			$res = mysql_query($requete, $dbh);
		}
		show_infopages($dbh);
		break;
	case 'add':
		if (empty($form_title_infopage)) infopage_form(0, $form_title_infopage, $form_content_infopage, $form_valid_infopage);
		else show_infopages($dbh);
		break;
	case 'modif':
		if($id){
			$requete = "select id_infopage, title_infopage, content_infopage, valid_infopage from infopages WHERE id_infopage='$id' ";
			$res = mysql_query($requete, $dbh);
			if(mysql_num_rows($res)) {
				$row=mysql_fetch_object($res);
				infopage_form($row->id_infopage, $row->title_infopage, $row->content_infopage, $row->valid_infopage);
			} else {
				show_infopages($dbh);
			}
		} else {
			show_infopages($dbh);
		}
		break;
	case 'del':
		if($id) {
			$requete = "DELETE from infopages WHERE id_infopage='$id' ";
			$res = mysql_query($requete, $dbh);
			show_infopages($dbh);
		} else show_infopages($dbh);
		break;
	default:
		show_infopages($dbh);
		break;
	}
