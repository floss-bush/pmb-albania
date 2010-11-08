<?php
// +-------------------------------------------------+
// ? 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: import_ldap.inc.php,v 1.14 2009-05-16 11:12:02 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$include_path/ldap_param.inc.php");
require_once("$include_path/templates/ldap_users.tpl.php");

# IMPORTANT NOTE:
# the $fields ldap variable (list of ldap fields to read) MUST BEGIN with 
# uid, sn, cn (or givenName), ....   
# sn MUST contain the last name
# cn (or,now, givenName) MUST contain the first name 
# in my scheme the ldap fields holds 
# departmentNumber = student class
# employeenumber = codice fiscale (taxoffice code)
# employeetype = sex
# postofficebox = city
# postalcode = cap
# postaladdress = address
# homephone = tel 

define ('INDEX_NOM',1);  
define ('INDEX_PRENOM',2);  
define ('SEX','employeetype');

// return list of ldap users of a declared group (LDAP_GROUPS list)
// and write it in the file ./temp/ldap_users.txt (now commented!!!)
// the return list is passed at import_empr.inc.php via a hidden form field

function norm_name($v){
	$ret="";
	$v=str_replace("'",' ',$v);
	$xx=explode(' ',$v);
	foreach ($xx as $x){
		$ret .= ucwords(strtolower($x));
	}
	return $ret;
}

function users_ldap($gid){
	$ret="";

	$fields = explode(",",LDAP_FIELDS);
	$filter = str_replace('GID',$gid,LDAP_FILTER);

	$conn = ldap_connect( LDAP_SERVER, LDAP_PORT);

	ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, LDAP_PROTO);

	$b = ldap_bind($conn);
	$r = ldap_search($conn, LDAP_BASEDN,$filter,$fields,0,0);

	$info = ldap_get_entries($conn, $r);
	ldap_close($conn);

# DEBUG
#	printr ($info);
#	print "infocount=".$info['count']."\n";
#	print_r($fields,"");
	
	for($k = 0; $k<$info["count"]; $k++)  {

		#print "fieldcount=".count($fields)."<br />";

		for($j = 0; $j<count($fields) ; $j++) {
			
				$v=rtrim($info[$k][strtolower($fields[$j])][0],',');

# DEBUG
#				$y=strtolower($fields[$j]);
#				$z=$info[$k][$y][0];			
#				print "$j = $y = $z<br />";

				if ($j==0){ // after uid , lang
					$ret .= "$v|".LDAP_LANG."|";
					
//				}elseif ($fields[$j]=="gecos" || $fields[$j]=="displayname"){
//
//					$v=ucwords(strtolower($v));
//					$cn=explode(' ',$v);
//					$c=$n="";
//					for ($i=0;$i<count($cn)-1;$i++){$c .= $cn[$i];}
//					$n=$cn[count($cn)-1];
//					$ret .= "$c|$n|";

				}elseif ($j==INDEX_NOM){

					$c = norm_name($v);
					$ret .= "$c|";


				}elseif ($j==INDEX_PRENOM){  
					
					$n = norm_name($v);
					$ret .= "$n|";

				}elseif ($fields[$j]==SEX){  

					$s=strtolower($v);
					if ($s=='m')		$s = "1|";
					elseif ($s=='f')	$s = "2|";
					else				$s = "0|";
						
					$ret .= "$s|";

				}else{
					$ret .= "$v|";
				}
		}
		$ret = rtrim($ret,'|');
		$ret .= ";";
	}
    $fp=fopen("./temp/ldap_users.txt","w");
    fwrite($fp,$ret);
    fclose($fp);
	return $ret;
}


// return the gidnumber of a ldap group
function gid_ldap($grp){
	$ret=0;
	$fields = array('gidnumber');
	$conn = ldap_connect( LDAP_SERVER, LDAP_PORT);
	ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, LDAP_PROTO);
	$b = ldap_bind($conn);
	$r = ldap_search($conn, LDAP_BASEDN, "cn=$grp",$fields);
	$info = ldap_get_entries($conn, $r);
	ldap_close($conn);
	$ret=$info[0]['gidnumber'][0];
	return $ret;
}

// check id server ldap is ok
function ldap_ok(){
	global $msg;
	$ldap_error=1;
	$ret=0;
	if (LDAP_SERVER){
		$conn=@ldap_connect(LDAP_SERVER,LDAP_PORT);  // must be a valid LDAP server!
		ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, LDAP_PROTO);
		if ($conn) {
			$x=@ldap_read($conn,LDAP_BASEDN,LDAP_FILTER);
			if (preg_match('/resource/i',(string)$x))
			{
				$ldap_error=0;
				$ret=1;
				ldap_close($conn);
			}
		}
		if ($ldap_error) {
			print "<h2>".$msg["ldap_error"]."</h2>";
			print "<h2>".$msg["ldap_erro2"]."</h2>";
		}
	}else{
		print "<h2>".$msg["ldap_noserver"]."</h2>";
	}
	return $ret;
}

// phase1 = choice of ldap group
function choice_ldap_group() {
	global $msg;
	global $charset;
	global $current_module ;
	global $form_ldap_groups;
	if (ldap_ok()){
		$lista_grp=explode(',',LDAP_GROUPS);
		$opz_grp='';
		foreach($lista_grp as $dummykey=>$v) {
			$opz_grp .= "<option value='".$v."'>$v</option>";
		}
		$form_ldap_groups=str_replace('!!opz_grp!!',$opz_grp,$form_ldap_groups);
		print $form_ldap_groups;
	}
}

// phase2 = show of ldap users for import
// phase3 = by import_empr.inc.php

function show_users_ldap($uu,$pag,$npp) {
	global $msg;
	global $charset;
	global $current_module ;
	global $form_show_ldap_users;

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
		//$nav_bar .= "<input type='submit' class='bouton' name='btsubmit' value='<' />";
		$nav_barL = "<input type='image' src='./images/left.gif' border='0' alt='$msg[48]' hspace='0' align='middle' title='$msg[48]' class='bouton-nav' name='btsubmit' value='<' />";
	}else{
		//$nav_bar .= "<input type='submit' class='bouton' name='btsubmit' value='<' />";
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
				<td valign='top'>
					<input type='checkbox' id='id_to_del$r' name='usrdel[]' value='$cc[0]'/>
				</td>
				<td valign='top'>
					$cc[0]
				</td>";
		for ($j=2;$j<count($cc);$j++){
				$usr_entry .= "<td valign='top'>$cc[$j]</td>";
		}

		if ($k % 2) {
				$pair_impair = "even";
		} else {
				$pair_impair = "odd";
		}

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

	$form_show_ldap_users=str_replace('!!npp_ctrl!!',$npp_ctrl,$form_show_ldap_users);
	$form_show_ldap_users=str_replace('!!nav_barL!!',$nav_barL,$form_show_ldap_users);
	$form_show_ldap_users=str_replace('!!nav_barC!!',$nav_barC,$form_show_ldap_users);
	$form_show_ldap_users=str_replace('!!nav_barR!!',$nav_barR,$form_show_ldap_users);
	$form_show_ldap_users=str_replace('!!usr_list!!',$usr_list,$form_show_ldap_users);
	$form_show_ldap_users=str_replace('!!hid_vars!!',$hid_vars,$form_show_ldap_users);


	print $form_show_ldap_users;

}

//        <input type='submit' class='bouton' name='indietro' value='indietro'/>
//        <input type='submit' class='bouton' name='avanti' value='avanti'/>


	// affichage du lien pr?c?dent si n?c?ssaire
//    if($precp > 0){
//        $nav_bar .= "<a href='$PHP_SELF?action=categ=empr&sub=ldap&action=ldapOK&xpag=$precp'>
//                     <img src='./images/left.gif' border='0' alt='$msg[48]' hspace='6' align='middle' title='$msg[48]' ///></a>";
//    }
//    for($i = 1; $i <= $npag; $i++) {
//        if($i==$pag){
//            $nav_bar .= "<strong>pag. $i/$npag</strong>";
//        }
//    }
//    if($nextp<=$npag) {
//        $nav_bar .= "<a href='$PHP_SELF?categ=empr&sub=ldap&action=ldapOK&xpag=$nextp'>
//           <img src='./images/right.gif' border='0' alt='$msg[49]' hspace='6' align='middle' title='$msg[49]' /></a>";
//    }



//------------------- main -------------------
$op=$_POST[btsubmit];
switch($action)
{
	case 'ldapOK':

		switch($op){
				case '=':
					//$pag=$_POST['pag'];
					$npp=$_POST['npp'];
					show_users_ldap($uu,1,$npp);
					break;
				case '<':
					$pag=max(1,$_POST['pag']-1);
					$npp=$_POST['npp'];
					show_users_ldap($uu,$pag,$npp);
					break;

				case '>':
					$pag=$_POST['pag']+1;
					show_users_ldap($uu,$pag,$npp);
					break;

				case $msg[del_ldap_usr]:
					$xx=$_POST['usrdel'];
					$uu=$_POST['uu'];
					$pag=$_POST['pag'];
					$npp=$_POST['npp'];
					foreach ($xx as $dummykey=>$x){
						$u="/$x"."[^;]+;/";
						$uu=preg_replace($u,'',$uu,1);
					}
					show_users_ldap($uu,$pag,$npp);
					break;
				case $msg[import_ldap_exe]:
					$uu=$_POST['uu'];
					$uu=str_replace(';',"\n",$uu);
#					$uu=str_replace('|',',',$uu);
					$fp=fopen("./temp/ldap_users.txt","w");
					fwrite($fp,$uu);
					fclose($fp);
					$action="";
					$from_ldap='1';
					require_once("./admin/empr/import_empr.inc.php");
					break;
				default:
					$uu=users_ldap(gid_ldap($_POST['ldap_grp']));
					$pag=$_POST['pag'];
					$npp=$_POST['npp'];
					if (!$pag) $pag=1;
					show_users_ldap($uu,$pag,$npp);
					break;
		}
		break;

	default:
		choice_ldap_group();
		break;
}
