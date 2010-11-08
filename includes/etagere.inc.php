<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: etagere.inc.php,v 1.12 2009-05-16 11:20:27 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// affichage des etagères existantes
function aff_etagere($action, $bouton_ajout=1) {
global $msg;
global $PMBuserid;
global $charset, $opac_url_base;

$liste = etagere::get_etagere_list();
if(sizeof($liste)) {
	print "<table>";
	print "<tr><th>".$msg['etagere_name']."</th><th>".$msg["etagere_cart_count"]."</th><th>".$msg['etagere_visible_date']."</th><th>".$msg['etagere_visible_accueil']."</th></tr>";
	$parity=1;
	while(list($cle, $valeur) = each($liste)) {
		$rqt_autorisation=explode(" ",$valeur['autorisations']);
		if (array_search ($PMBuserid, $rqt_autorisation)!==FALSE || $PMBuserid==1) {			
			$link = "./catalog.php?categ=etagere&sub=$action&action=edit_etagere&idetagere=".$valeur['idetagere'];
			if ($parity % 2) {
				$pair_impair = "even";
			} else {
				$pair_impair = "odd";
			}
			$parity += 1;
	        	
        	$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" ";
        	$td_javascript_click=" onmousedown=\"document.location='$link';\" ";
        	print pmb_bidi("<tr class='$pair_impair' $tr_javascript >
           			<td $td_javascript_click style='cursor: pointer'><strong>".$valeur['name']."</strong>");
           	if ($valeur['comment']) print pmb_bidi(" (".$valeur['comment'].")");
           	print "</td><td $td_javascript_click style='cursor: pointer'>" ;
			print $valeur['nb_paniers'];
           	print "</td><td $td_javascript_click style='cursor: pointer'>" ;                	
           	if ($valeur['validite']) print $msg['etagere_visible_date_all'] ;
			else print $msg['etagere_visible_date_du']." ".$valeur['validite_date_deb_f']." ".$msg['etagere_visible_date_fin']." ".$valeur['validite_date_fin_f'] ;
           	print "</td><td>" ;
           	if ($valeur['visible_accueil']) print "X<br /><a href='".$opac_url_base."index.php?lvl=etagere_see&id=".$valeur['idetagere']."' target=_blank>".$opac_url_base."index.php?lvl=etagere_see&id=".$valeur['idetagere']."</a>" ;
			else print "<br /><a href='".$opac_url_base."index.php?lvl=etagere_see&id=".$valeur['idetagere']."' target=_blank>".$opac_url_base."index.php?lvl=etagere_see&id=".$valeur['idetagere']."</a>" ;
           	print "</td>
			</tr>";
		}
	}
	print "</table>";
} else {
	print $msg['etagere_no_etagere'];
}
if ($bouton_ajout) print "<div class='row'>
	<input class='bouton' type='button' value=' $msg[etagere_new_etagere] ' onClick=\"document.location='./catalog.php?categ=etagere&sub=gestion&action=new_etagere'\" />
	</div>"; 

}

// affichage des autorisations sur les etageres
function aff_form_autorisations_etagere ($param_autorisations="1", $creation_etagere="1") {
global $dbh;
global $msg;
global $PMBuserid;

$requete_users = "SELECT userid, username FROM users order by username ";
$res_users = mysql_query($requete_users, $dbh);
$all_users=array();
while (list($all_userid,$all_username)=mysql_fetch_row($res_users)) {
	$all_users[]=array($all_userid,$all_username);
}
if ($creation_etagere) $param_autorisations.=" ".$PMBuserid ;

$autorisations_donnees=explode(" ",$param_autorisations);
for ($i=0 ; $i<count($all_users) ; $i++) {
	if (array_search ($all_users[$i][0], $autorisations_donnees)!==FALSE) $autorisation[$i][0]=1;
	else $autorisation[$i][0]=0;
	$autorisation[$i][1]= $all_users[$i][0];
	$autorisation[$i][2]= $all_users[$i][1];
}
$autorisations_users="";
$id_check_list='';
while (list($row_number, $row_data) = each($autorisation)) {
	$id_check="auto_".$row_data[1];
	if($id_check_list)$id_check_list.='|';
	$id_check_list.=$id_check;	
	if ($row_data[1]==1) $autorisations_users.="<span class='usercheckbox'><input type='checkbox' name='etagere_autorisations[]' value='".$row_data[1]."' id='$id_check' checked class='checkbox' readonly /><label for='$id_check' class='normlabel'>&nbsp;".$row_data[2]."</label></span>&nbsp;";
	elseif ($row_data[0]) $autorisations_users.="<span class='usercheckbox'><input type='checkbox' name='etagere_autorisations[]' value='".$row_data[1]."' id='$id_check' checked class='checkbox' /><label for='$id_check' class='normlabel'>&nbsp;".$row_data[2]."</label></span>&nbsp;";
	else $autorisations_users.="<span class='usercheckbox'><input type='checkbox' name='etagere_autorisations[]' value='".$row_data[1]."' id='$id_check' class='checkbox' /><label for='$id_check' class='normlabel'>&nbsp;".$row_data[2]."</label></span>&nbsp;";
}
$autorisations_users.="<input type='hidden' id='auto_id_list' name='auto_id_list' value='$id_check_list' >";
return $autorisations_users;
}
	
function verif_droit_etagere($id) {
	global $msg;
	global $PMBuserid;
	global $dbh ;
	
	if ($id) {
		$requete = "SELECT autorisations FROM etagere WHERE idetagere='$id' ";
		$result = @mysql_query($requete, $dbh);
		if(mysql_num_rows($result)) {
			$temp = mysql_fetch_object($result);
			$rqt_autorisation=explode(" ",$temp->autorisations);
			if (array_search ($PMBuserid, $rqt_autorisation)!==FALSE || $PMBuserid == 1) return $id ;
				else return 0 ;
			} else return 0;
		} else return 0 ;
	}
