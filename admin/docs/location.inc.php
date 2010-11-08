<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: location.inc.php,v 1.28 2010-01-21 16:22:36 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// gestion des codes localisation exemplaires
?>
<script type="text/javascript">
function test_form(form) {
	if(form.form_libelle.value.length == 0) {
		alert("<?php echo $msg[98]; ?>");
		return false;
	}
	return true;
}

</script>
<?php
function show_location($dbh) {
	global $msg,$pmb_location_reservation,$current_module;
	
	if($pmb_location_reservation) print "<h1>".$msg["admin_location_list_title"]."</h1>";
	
	print "<table>
		<tr>
			<th>".$msg[103]."</th>
			<th>".$msg['opac_object_visible_short']."</th>
			<th>".$msg['proprio_codage_proprio']."</th>
			<th>".$msg['import_codage']."</th>
		</tr>";

	$requete = "SELECT idlocation,location_libelle, locdoc_owner, locdoc_codage_import, lender_libelle, location_visible_opac, css_style FROM docs_location left join lenders on locdoc_owner=idlender ORDER BY location_libelle";
	$res = mysql_query($requete, $dbh);

	$nbr = mysql_num_rows($res);

	$parity=1;
	for($i=0;$i<$nbr;$i++) {
		$row=mysql_fetch_object($res);
		$memo_location[]=$row;
		if ($parity % 2) {
			$pair_impair = "even";
		} else {
			$pair_impair = "odd";
		}
		$parity += 1;
		$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='./admin.php?categ=docs&sub=location&action=modif&id=$row->idlocation';\" ";
                if ($row->locdoc_owner) print pmb_bidi("<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td><i>$row->location_libelle</i></td>");     
                	else print pmb_bidi("<tr class='$pair_impair' $tr_javascript style='cursor: pointer'><td><strong>$row->location_libelle</strong></td>");
		if ($row->location_visible_opac) $visible="X" ; 
		else $visible="&nbsp;" ;
		print "<td>$visible</td>" ;
		print pmb_bidi("<td>$row->lender_libelle</td>") ;
        print pmb_bidi("<td>$row->locdoc_codage_import</td></tr>");
	}
	print "</table>
		<input class='bouton' type='button' value=' $msg[106] ' onClick=\"document.location='./admin.php?categ=docs&sub=location&action=add'\" />";

	if($pmb_location_reservation) {
		$form_res_location= 
		"<h1>".$msg["admin_location_resa_title"]."</h1>
		<form class='form-$current_module' id='userform' name='userform' method='post' action='./admin.php?categ=docs&sub=location&action=resa_loc'>
		";	
		$form_res_location.=
		"<table>
			<tr>
				<th>".$msg["admin_location_resa_empr_loc"]."</th>";
		$requete="select * from resa_loc";
		$res = mysql_query($requete, $dbh);	
		if(mysql_num_rows($res)) {
			while(($row=mysql_fetch_object($res))) {
				$resa_liste[$row->resa_emprloc][$row->resa_loc]=1;				
			}
		}		
		foreach($memo_location as $row) {
			$form_res_location.="<th>".$row->location_libelle."</th>";		
			if ($parity++ % 2) {
				$pair_impair = "even";
			} else {
				$pair_impair = "odd";
			}
			$ligne.="</tr><tr class='$pair_impair'><td>".$row->location_libelle."</td>";		
			foreach($memo_location as $row1) {
				if($resa_liste[$row->idlocation][$row1->idlocation]) $check=" checked='checked' ";
				else $check="";
				$ligne.="<td><input value='1' name='matrice_loc[".$row1->idlocation."][".$row->idlocation."]' type='checkbox' $check ></td>";		
			}	
		}		
		$form_res_location.=$ligne."
			</tr>		
			</table>
			<input class='bouton' type='submit' value=' ".$msg["admin_location_resa_memo"]." ' />
			<input type='hidden' name='form_actif' value='1'>
			</form>";		
		print $form_res_location;
	}	
}

function location_form($libelle="", $locdoc_codage_import="", $locdoc_owner=0, $id=0, $location_pic="", $location_visible_opac=1, $name = "", $adr1 = "", $adr2 = "", $cp = "", $town = "", $state = "", $country = "", $phone = "", $email = "", $website = "", $logo = "", $commentaire="", $num_infopage=0, $css_style="" ) {
	global $msg;
	global $admin_location_form;
	global $charset;
	
	$admin_location_form = str_replace('!!id!!', $id, $admin_location_form);

	if(!$id) $admin_location_form = str_replace('!!form_title!!', $msg[106], $admin_location_form);
	else $admin_location_form = str_replace('!!form_title!!', $msg[107], $admin_location_form);

	$admin_location_form = str_replace('!!libelle!!', htmlentities($libelle,ENT_QUOTES, $charset), $admin_location_form);
	$admin_location_form = str_replace('!!libelle_suppr!!', htmlentities(addslashes($libelle),ENT_QUOTES, $charset), $admin_location_form);

	$admin_location_form = str_replace('!!location_pic!!', htmlentities($location_pic,ENT_QUOTES, $charset), $admin_location_form);

	if($location_visible_opac) $checkbox="checked"; else $checkbox="";
	$admin_location_form = str_replace('!!checkbox!!', $checkbox, $admin_location_form);

	$admin_location_form = str_replace('!!locdoc_codage_import!!', $locdoc_codage_import, $admin_location_form);
	$combo_lender= gen_liste ("select idlender, lender_libelle from lenders order by lender_libelle ", "idlender", "lender_libelle", "form_locdoc_owner", "", $locdoc_owner, 0, $msg[556],0,$msg["proprio_generique_biblio"]) ;
	$admin_location_form = str_replace('!!lender!!', $combo_lender, $admin_location_form);
	
	$admin_location_form = str_replace('!!loc_name!!', 	htmlentities($name,ENT_QUOTES, $charset)     , $admin_location_form);
	$admin_location_form = str_replace('!!loc_adr1!!', 	htmlentities($adr1,ENT_QUOTES, $charset)     , $admin_location_form);
	$admin_location_form = str_replace('!!loc_adr2!!', 	htmlentities($adr2,ENT_QUOTES, $charset)     , $admin_location_form);
	$admin_location_form = str_replace('!!loc_cp!!', 	$cp       , $admin_location_form);
	$admin_location_form = str_replace('!!loc_town!!', 	htmlentities($town,ENT_QUOTES, $charset)     , $admin_location_form);
	$admin_location_form = str_replace('!!loc_state!!', 	htmlentities($state,ENT_QUOTES, $charset)    , $admin_location_form);
	$admin_location_form = str_replace('!!loc_country!!', 	htmlentities($country,ENT_QUOTES, $charset)  , $admin_location_form);
	$admin_location_form = str_replace('!!loc_phone!!', 	$phone    , $admin_location_form);
	$admin_location_form = str_replace('!!loc_email!!', 	$email    , $admin_location_form);
	$admin_location_form = str_replace('!!loc_website!!', 	$website  , $admin_location_form);
	$admin_location_form = str_replace('!!loc_logo!!', 	$logo     , $admin_location_form);
	$admin_location_form = str_replace('!!loc_commentaire!!', htmlentities($commentaire,ENT_QUOTES, $charset), $admin_location_form);

	$requete = "SELECT id_infopage, title_infopage FROM infopages where valid_infopage=1 ORDER BY title_infopage ";
	$infopages = gen_liste ($requete, "id_infopage", "title_infopage", "form_num_infopage", "", $num_infopage, 0, $msg[location_no_infopage], 0,$msg[location_no_infopage], 0) ;
	$admin_location_form = str_replace('!!loc_infopage!!', $infopages, $admin_location_form);
	
	$admin_location_form = str_replace('!!css_style!!', $css_style, $admin_location_form);
	
	print confirmation_delete("./admin.php?categ=docs&sub=location&action=del&id=");
	print $admin_location_form;
}

switch($action) {
	case 'update':
		// vérification validité des données fournies.
		if($form_actif) {
			$requete = " SELECT count(1) FROM docs_location WHERE (location_libelle='$form_libelle' AND idlocation!='$id' )  LIMIT 1 ";
			$res = mysql_query($requete, $dbh);
			$nbr = mysql_result($res, 0, 0);
			if ($nbr > 0) {
				error_form_message($form_libelle.$msg["docs_label_already_used"]);
			} else {
				// O.K.,  now if item already exists UPDATE else INSERT
				$set_values = "SET location_libelle='$form_libelle', locdoc_codage_import='$form_locdoc_codage_import', locdoc_owner='$form_locdoc_owner', location_pic='$form_location_pic', location_visible_opac='$form_location_visible_opac', name= '$form_locdoc_name', adr1= '$form_locdoc_adr1', adr2= '$form_locdoc_adr2', cp= '$form_locdoc_cp', town= '$form_locdoc_town', state= '$form_locdoc_state', country= '$form_locdoc_country', phone= '$form_locdoc_phone', email= '$form_locdoc_email', website= '$form_locdoc_website', logo= '$form_locdoc_logo', commentaire='$form_locdoc_commentaire', num_infopage='$form_num_infopage', css_style='$form_css_style' " ;
				if($id) {
					$requete = "UPDATE docs_location $set_values WHERE idlocation='$id' ";
					$res = mysql_query($requete, $dbh);
				} else {
					$requete = "INSERT INTO docs_location $set_values ";
					$res = mysql_query($requete, $dbh);
				}
			}
		}	
		show_location($dbh);
		break;
	case 'add':
		if(empty($form_libelle) && empty($form_pret)) location_form();
			else show_location($dbh);
		break;
	case 'resa_loc':
		if($form_actif) {
			$requete = "truncate table resa_loc";
			mysql_query($requete, $dbh);
			if(is_array($matrice_loc))foreach($matrice_loc as $loc_bibli=>$val) {
				foreach($val as $loc_empr=>$val1) {
					$requete = "INSERT INTO resa_loc SET resa_loc='$loc_bibli', resa_emprloc='$loc_empr'";
					mysql_query($requete, $dbh);
				}
			}
		}	
		show_location($dbh);
		break;		
	case 'modif':
		if($id){
			$requete = "SELECT location_libelle, locdoc_codage_import, locdoc_owner, location_pic, location_visible_opac, location_visible_opac, name, adr1, adr2, cp, town, state, country, phone, email, website, logo, commentaire, num_infopage, css_style FROM docs_location WHERE idlocation='$id' ";
			$res = mysql_query($requete, $dbh) or die(mysql_error()."<br />$requete");
			if(mysql_num_rows($res)) {
				$row=mysql_fetch_object($res);
				location_form($row->location_libelle, $row->locdoc_codage_import, $row->locdoc_owner, $id, $row->location_pic, $row->location_visible_opac, $row->name, $row->adr1, $row->adr2, $row->cp, $row->town, $row->state, $row->country, $row->phone, $row->email, $row->website, $row->logo, $row->commentaire, $row->num_infopage, $row->css_style);
			} else {
				show_location($dbh);
			}
		} else {
			show_location($dbh);
		}
		break;
	case 'del':
		if($id) {
			$total1 = mysql_result (mysql_query("select count(1) from exemplaires where expl_location='".$id."' ", $dbh), 0, 0);
			$total2 = mysql_result (mysql_query("select count(1) from users where deflt2docs_location='".$id."' or deflt_docs_location='".$id."'", $dbh), 0, 0);
			$total3 = mysql_result (mysql_query("select count(1) from empr where empr_location='".$id."' ", $dbh), 0, 0);
			$total4 = mysql_result(mysql_query("select count(1) from abts_abts where location_id ='".$id."' ", $dbh), 0, 0);
			$total5 = mysql_result(mysql_query("select count(1) from collections_state where location_id ='".$id."' ", $dbh), 0, 0);
			if (($total1+$total2+$total3+$total4+$total5)==0) {
				$requete = "DELETE FROM docs_location WHERE idlocation=$id ";
				$res = mysql_query($requete, $dbh);
				show_location($dbh);
			} else {
				$msg_suppr_err = $msg[location_used] ;
				if ($total1) $msg_suppr_err .= "<br />- ".$msg[location_used_docs] ;
				if ($total2) $msg_suppr_err .= "<br />- ".$msg[location_used_users] ;
				if ($total3) $msg_suppr_err .= "<br />- ".$msg[location_used_empr] ;
				if ($total4) $msg_suppr_err .= "<br />- ".$msg["location_used_abts"] ;
				if ($total5) $msg_suppr_err .= "<br />- ".$msg["location_used_collections_state"] ;
				error_message(	$msg[294], $msg_suppr_err, 1, 'admin.php?categ=docs&sub=location&action=');
			}
		} else show_location($dbh);
		break;
	default:
		show_location($dbh);
		break;
	}
