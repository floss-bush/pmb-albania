<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: general.inc.php,v 1.2 2010-01-30 14:34:18 erwanmartin Exp $

//Administration générale des droits des services externes

require_once($class_path."/external_services.class.php");
require_once($include_path."/templates/external_services.tpl.php");

$es=new external_services();
$es_rights=new external_services_rights($es);

function users_list($group, $method, $users, $parent_users) {
	global $charset;
	global $es_rights;
	global $msg;
	$list_users=$es_rights->possible_users($group,$method);

	$count = 0;
	
	$result="<ul>\n";
	for ($j=0; $j<count($list_users); $j++) {
		if (array_search($list_users[$j],$users)!==false) {
			//Si l'utilisateur a les droits pour le groupe entier, on ne l'affiche pas dans le détail
			$group_authorized = in_array($es_rights->users[$list_users[$j]]->userid, $parent_users);
			if (!$group_authorized) {
				$page_link_href = 'admin.php?categ=external_services&sub=peruser&iduser='.$es_rights->users[$list_users[$j]]->userid.'#'.urlencode($group).($method ? '_'.urlencode($method) : "");
				$user_name_display = htmlentities($es_rights->users[$list_users[$j]]->username,ENT_QUOTES,$charset);
				$result.="<li><a href=".$page_link_href.">".$user_name_display."</a></li>\n";
				++$count;				
			}
		}
	}
	$result.="</ul>";
	
	//A-t-on trouvé des utilisateur? Si non, on affiche 'Aucun'
	if (!$count) {
		return "<ul><li><i>".$msg["es_user_auth_none"]."</i></li></ul>";
	}
	
	return $result;
}

$table_rights="<table style='width:100%'>
<thead><th colspan='3'>Groupe</th><th colspan='3'>Utilisateurs autorisés</th></thead>
";

//pour chaque groupe
$group_list=$es->get_group_list();
for ($i=0; $i<count($group_list); $i++) {
	$group=$group_list[$i];
	
	$rights_group=$es_rights->get_rights($group["name"],"");
	
	$table_rights.= "<tr class='".($i%2?"even":"odd")."'><td><b>".htmlentities($group["name"],ENT_QUOTES,$charset)."</b></td><td colspan='2'><i>".htmlentities($group["description"],ENT_QUOTES,$charset)."</i></td>
	<td>
	<input type='hidden' name='group[".$group["name"]."]' value='1'/>
	</td>
	<td colspan='3'>".users_list($group["name"],'',$rights_group->users,array())."</td>
	
	</tr>";
	
	$table_rights.= "<thead><td></td><th colspan='2'>Méthode</th><th colspan='3'>Utilisateurs autorisés</th></thead>";
	
	//Pour chaque méthode
	for ($j=0; $j<count($group["methods"]); $j++) {
		$method=$group["methods"][$j];
		
		$rights=$es_rights->get_rights($group["name"],$method["name"]);
		
		$table_rights.= "<tr class='".($i%2?"even":"odd")."'>
		".(!$j?"<td rowspan='".count($group["methods"])."'>&nbsp;</td>":"")."
		<td><b>".htmlentities($method["name"],ENT_QUOTES,$charset)."</b></td><td><i>".htmlentities($method["description"],ENT_QUOTES,$charset)."</i></td>
		<td></td>
		<td></td>
		<td>".users_list($group["name"],$method["name"],$rights->users,$rights_group->users)."</td>
		</tr>";
	}
	
}
$table_rights.= "</table>";

print str_replace("!!table_rights!!",$table_rights,$es_admin_general);
?>