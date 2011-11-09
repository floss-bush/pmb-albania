<?php
// +-------------------------------------------------+
// | 2002-2007 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: peruser.inc.php,v 1.2.2.2 2011-09-07 07:38:27 jpermanne Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//Initialisation des classes
require_once($class_path."/external_services.class.php");
require_once($include_path."/templates/external_services.tpl.php");

$es=new external_services();
$es_rights=new external_services_rights($es);

//Mise à jour des droits d'un objet en fonction de la demande particulière d'un utilisateur
function update_rights_for_user(&$es_r,$val) {
	global $iduser;
	
	//Selon la valeur de $val : 0=pas de droits, 1=droit normal, 2=anonyme
	switch ($val) {
		case 0:
			if ($es_r->anonymous_user==$iduser) 
				$es_r->anonymous_user=0;
			else if (array_search($iduser,$es_r->users)!==false) {
				//Réécriture du tableau des users
				//Copie
				$tusers=$es_r->users;
				$es_r->users=array();
				for ($j=0; $j<count($tusers); $j++) {
					if ($tusers[$j]!=$iduser) $es_r->users[]=$tusers[$j];
				}
			}
			break;
		case 1:
			if ($es_r->anonymous_user==$iduser) {
				$es_r->anonymous_user=0;
				//Insertion dans le tableau
				$es_r->users[]=$iduser;
			} else if (array_search($iduser,$es_r->users)===false) {
				$es_r->users[]=$iduser;
			}
			break;
		case 2:
			if (array_search($iduser,$es_r->users)!==false) {
				//Si il existe dans les users, on le supprime
				//Réécriture du tableau des users
				//Copie
				$tusers=$es_r->users;
				$es_r->users=array();
				for ($j=0; $j<count($tusers); $j++) {
					if ($tusers[$j]!=$iduser) $es_r->users[]=$tusers[$j];
				}
			}
			$es_r->anonymous_user=$iduser;
			break;
	}
}

//Enregistrement des droits si nécessaire
if ($is_not_first) {
	foreach ($es->catalog->groups as $group_name => &$group_content) {
		$val = isset($grp_right[$group_name]) && $grp_right[$group_name];
		$es_r=$es_rights->get_rights($group_name,"");
		update_rights_for_user($es_r,$val);
		//On enregistre les droits pour ce groupe
		$es_rights->set_rights($es_r);
		if ($es_rights->error) print "<script>alert(\"Il y a eu une erreur lors de l'insertion des droits du groupe $group_name : ".$es_rights->error_message."\");</script>";
		
		//On fait la même chose pour les méthodes du groupe !
		foreach ($group_content->methods as $method_name => &$method_content) {
			$val = isset($mth_right[$group_name][$method_name]) && $mth_right[$group_name][$method_name];
			$es_r=$es_rights->get_rights($group_name,$method_name);
			update_rights_for_user($es_r,$val);
			//On enregistre les droits pour ce groupe
			$es_rights->set_rights($es_r);
			if ($es_rights->error) print "<script>alert(\"Il y a eu une erreur lors de l'insertion des droits de la methode ".$method_name." du groupe $group_name : ".$es_rights->error_message."\");</script>";
		}
	}
}

//Génération de la liste des utilisateurs
$list_users="<select name='iduser' onChange='this.form.submit();'>\n";
foreach ($es_rights->users as $userid=>$user) {
	if (!$iduser) {
		$iduser=$userid;
	}
	$list_users.="	<option value='".$userid."' ".($userid==$iduser?"selected":"").">".htmlentities($user->username,ENT_QUOTES,$charset)."</option>\n";
}
$list_users.="</select>";

//Génération du tableau des droits

$table_rights="<table style='width:100%'>
<thead><th colspan='3'>Groupe</th><th colspan='3'>Droits pour l'utilisateur</th></thead>
";

//Pour chaque groupe
$group_list=$es->get_group_list();
for ($i=0; $i<count($group_list); $i++) {
	$group=$group_list[$i];
	
	$rights=$es_rights->get_rights($group["name"],"");
	
	$has_basics=(!$es_rights->has_basic_rights($iduser,$group["name"],"")?"disabled='disabled'":"");
	
	$full_group_allowed = array_search($iduser,$rights->users)!==false;
	$table_rights.= "<tr class='".($i%2?"even":"odd")."'><td><br /><b>".htmlentities($group["name"],ENT_QUOTES,$charset)."</b><br /><br /></td><td colspan='2'><i>".htmlentities($group["description"],ENT_QUOTES,$charset)."</i></td>
	<td colspan=\"3\">
		<a name=\"".htmlentities($group["name"], ENT_QUOTES, $charset)."\"/><input id=\"nonavailable_".$group["name"]."\" name=\"grp_right[".$group["name"]."]\" ".($full_group_allowed ? "checked" : "")." value=\"1\" onclick=\"enable_or_disable_group_checboxes('".$group["name"]."')\" type=\"checkbox\">&nbsp;<label class='label' for='nonavailable_".htmlentities($group["name"],ENT_QUOTES,$charset)."'>Autoriser tout</label>
	</td>
	</tr>";

	$table_rights.= "<thead><td></td><th colspan='2'>".htmlentities($msg["external_services_peruser_methode"],ENT_QUOTES,$charset)."</th><th colspan='3'>".htmlentities($msg["external_services_peruser_methode_autorisees"],ENT_QUOTES,$charset)."<br />
	</th></thead>";
	
	//Pour chaque méthode
	for ($j=0; $j<count($group["methods"]); $j++) {
		$method=$group["methods"][$j];
		
		$rights=$es_rights->get_rights($group["name"],$method["name"]);
		
		$has_basics=(!$es_rights->has_basic_rights($iduser,$group["name"],$method["name"])?"disabled='disabled'":"");
		
		$method_checked = !$full_group_allowed && array_search($iduser,$rights->users)!==false;
		$method_enabled = !$full_group_allowed;
		
		$table_rights.= "<tr class='".($i%2?"even":"odd")."'>
		".(!$j?"<td rowspan='".count($group["methods"])."'>&nbsp;</td>":"")."
		<td><b>".htmlentities($method["name"],ENT_QUOTES,$charset)."</b></td><td><i>".htmlentities($method["description"],ENT_QUOTES,$charset)."</i></td>
		<td></td>
		<td>
		<a name=\"".htmlentities($group["name"], ENT_QUOTES, $charset).'_'.htmlentities($method["name"], ENT_QUOTES, $charset)."\"/><input type='checkbox' es_group='".$group["name"]."' $has_basics value='1' ".(!$method_enabled ? "disabled" : "")." ".($method_checked ? "checked" : "")." name='mth_right[".htmlentities($group["name"]."][".$method["name"]."]",ENT_QUOTES,$charset)."]' id='available_".htmlentities($group["name"]."_".$method["name"],ENT_QUOTES,$charset)."'>
		</td>
		<td>
		</td>
		</tr>";
	}
}

$table_rights.="</table>\n";

$js_funcs = <<<JS
	<script type="text/javascript">
	function enable_or_disable_group_checboxes(group_name) {
      var enable_or_disable = document.getElementById("nonavailable_"+group_name).checked;
	  var c = new Array();
	  c = document.getElementsByTagName('input');
	  for (var i = 0; i < c.length; i++)
	  {
	  	var es_group = "";
	  	if (c[i].attributes.getNamedItem('es_group') != null)
	  		es_group = c[i].attributes.getNamedItem('es_group').nodeValue;
	    if ((c[i].type == 'checkbox') && (es_group == group_name)) {
		    if (enable_or_disable) {
		      c[i].checked = false;
		      c[i].disabled = true;
		    }
		    else {
		      c[i].disabled = false;
		      c[i].checked = true;
		    }
	    }
	  }
	}
	</script>

JS;

echo $js_funcs;

$es_admin_peruser=str_replace("!!user!!",$list_users,$es_admin_peruser);
$es_admin_peruser=str_replace("!!table_rights!!",$table_rights,$es_admin_peruser);

print $es_admin_peruser;
?>