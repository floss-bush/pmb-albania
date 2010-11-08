<?php
// +-------------------------------------------------+
// ? 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: empr_exldap.inc.php,v 1.4 2008-11-19 03:59:26 maxman Exp $


if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");
require_once("$include_path/ldap_param.inc.php");
require_once("$include_path/templates/ldap_users.tpl.php");
require_once ("$class_path/emprunteur.class.php");

function find_exldap_users(){
	global $dbh;
	$ret="";
	$fields = explode(",",LDAP_FIELDS);
	$conn = ldap_connect( LDAP_SERVER, LDAP_PORT);
	ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, LDAP_PROTO);
	$b = ldap_bind($conn);

	$clause = "WHERE empr_ldap = 1" ;

	$req = "SELECT COUNT(1) FROM empr $clause ";
	$res = mysql_query($req, $dbh);
	$nbr_lignes = @mysql_result($res, 0, 0);
	if ($nbr_lignes > 0) {
		$req = "SELECT * FROM empr $clause ORDER BY empr_cb, empr_prenom, empr_nom ";
		$res = @mysql_query($req, $dbh);
		while(($empr=mysql_fetch_object($res))) {
			$filter = "(uid=$empr->empr_cb)";
			$r = ldap_search($conn, LDAP_BASEDN,$filter,$fields,0,0);
			$info = ldap_get_entries($conn, $r);
			# if empr n'existe pas dans ldap...
			$uid=$info[0][uid][0];
			if (!$uid){
				# cherche prêts
				$req = "SELECT COUNT(1) FROM pret,empr WHERE pret.pret_idempr = ".$empr->id_empr;
				$rpr = mysql_query($req, $dbh);
				$npr = mysql_result($rpr, 0, 0);
				# s'il n'y a pas des prêts...
				if (!$npr){
					$ret .= $empr->id_empr.'|'.$empr->empr_cb.'|'.$empr->empr_nom.'|'.$empr->empr_prenom.';' ;
					}
				}
			}
		}
	ldap_close($conn);
	$fp=fopen("./temp/exldap_users.txt","w");
    fwrite($fp,$ret);
    fclose($fp);
	return $ret;
}

function show_exldap_users($uu,$pag,$npp) {
	global $msg;
	global $charset;
	global $current_module ;
	global $form_show_exldap_users;

	$auu=explode(';',$uu);
	$nuu=count($auu);
	if (!$npp) $npp=10;
	$npag = ceil($nuu/$npp);
	$nextp = $pag+1;
	$precp = $pag-1;
	
	$npp_ctrl="
	<input type='text' class='saisie-4emc' name='npp' value='$npp' />
	<input type='image' src='./images/tick.gif' border='0' alt='$msg[708]' hspace='0' align='middle' title='$msg[708]'  class='bouton-nav' name='btsubmit' value='=' />
	";

	if($precp > 0){
		$nav_barL = "<input type='image' src='./images/left.gif' border='0' alt='$msg[48]' hspace='0' align='middle' title='$msg[48]' class='bouton-nav' name='btsubmit' value='<' />";
	}else{
		$nav_barL = "<input  disabled type='image' src='./images/left.gif' border='0' alt='$msg[48]' hspace='0' align='middle' title='$msg[48]' class='bouton-nav' name='btsubmit' value='<' />";
	}

	$nav_barC = "$pag/$npag";

	if($nextp<=$npag) {
		$nav_barR .= "<input type='image' src='./images/right.gif' border='0' alt='$msg[49]' hspace='0' align='middle' title='$msg[48]'  class='bouton-nav' name='btsubmit' value='>' />";
	}else{
		$nav_barR = "<input disabled type='image' src='./images/right.gif' border='0' alt='$msg[49]' hspace='0' align='middle' title='$msg[48]'  class='bouton-nav' name='btsubmit' value='>' /> ";
	}


	if(!$pag) $pag=1;

	$iniz=($pag-1)*$npp;
	$fine=min($nuu,$iniz+$npp);
	$r=1;
	for ($k=$iniz;$k<$fine;$k++){
		$cc=explode('|',$auu[$k]);
		if ($cc[0]){
			$usr_entry="
				<td valign='top'><input type='checkbox' id='id_to_del$r' name='usrdel[]' value='$cc[0]'/></td>
				<td valign='top'>$cc[1]</td>
				<td valign='top'>$cc[2]</td>
				<td valign='top'>$cc[3]</td>";

			if ($k % 2) $pair_impair = "even"; else	$pair_impair = "odd";
		
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"setCheckboxColumn('id_to_del$r')\" ";

			$usr_list .= "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>
						<td valign='top'>$usr_entry</td>
						</tr>";
			}

		$r++;
	}

//    $pag++;
	$hid_vars="
		<input type='hidden' name='pag' value='$pag' />
		<input type='hidden' name='uu' value='$uu'>";

	$form_show_exldap_users=str_replace('!!npp_ctrl!!',$npp_ctrl,$form_show_exldap_users);
	$form_show_exldap_users=str_replace('!!nav_barL!!',$nav_barL,$form_show_exldap_users);
	$form_show_exldap_users=str_replace('!!nav_barC!!',$nav_barC,$form_show_exldap_users);
	$form_show_exldap_users=str_replace('!!nav_barR!!',$nav_barR,$form_show_exldap_users);
	$form_show_exldap_users=str_replace('!!usr_list!!',$usr_list,$form_show_exldap_users);
	$form_show_exldap_users=str_replace('!!hid_vars!!',$hid_vars,$form_show_exldap_users);

	print $form_show_exldap_users;

}

function erase_exldap_users($uu){
	$auu=explode(';',$uu);
	$nuu=count($auu);
	$n=0;
	foreach ($auu as $u){
		$cc=explode('|',$u);
		$id=$cc[0];
		$req = "DELETE FROM empr WHERE id_empr=$id";
      	$res = mysql_query($req);
      	if ($res) ++$n;
	}
//	print "<h2> utenti exldap eliminati: $n su $nuu</h2>";
}

//------------------- main -------------------
$op=$_POST[btsubmit];
switch($action)
{
	case 'exldapDEL':
		switch($op){
			case '=':
				//$pag=$_POST['pag'];
				$npp=$_POST['npp'];
				show_exldap_users($uu,1,$npp);
				break;
			case '<':
				$pag=max(1,$_POST['pag']-1);
				$npp=$_POST['npp'];
				show_exldap_users($uu,$pag,$npp);
				break;
			case '>':
				$pag=$_POST['pag']+1;
				show_exldap_users($uu,$pag,$npp);
				break;

			case $msg[exldap_elimina]:
				$uu=$_POST['uu'];
				erase_exldap_users($uu);
				$uu=find_exldap_users();
				$pag=1;
				$npp=10;
				show_exldap_users($uu,$pag,$npp);
				break;
				
			case $msg[exldap_conserva]:
				$xx=$_POST['usrdel'];
				$uu=$_POST['uu'];
				$pag=$_POST['pag'];
				$npp=$_POST['npp'];
				foreach ($xx as $dummykey=>$x){
					$u="/$x"."[^;]+;/";
					$uu=preg_replace($u,'',$uu,1);
				}
				show_exldap_users($uu,$pag,$npp);
				break;
			case $msg[exldap_normale]:
				$xx=$_POST['usrdel'];
				$uu=$_POST['uu'];
				$pag=$_POST['pag'];
				$npp=$_POST['npp'];
				foreach ($xx as $dummykey=>$x){
					$u="/$x"."[^;]+;/";
					$uu=preg_replace($u,'',$uu,1);
					$req = "UPDATE empr SET empr_ldap=0 WHERE id_empr=$x";
					$res = mysql_query($req, $dbh);
				}
				show_exldap_users($uu,$pag,$npp);
				break;		
			default:
					$pag=$_POST['pag'];
					$npp=$_POST['npp'];
					if (!$pag) $pag=1;
					show_exldap_users($uu,$pag,$npp);
				break;
		}
		break;

	default:
		$uu=find_exldap_users();
		$pag=$_POST['pag'];
		$npp=$_POST['npp'];
		if (!$pag) $pag=1;
		show_exldap_users($uu,$pag,$npp);
		break;
}
