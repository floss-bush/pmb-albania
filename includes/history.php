<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: history.php,v 1.26 2009-05-16 11:17:05 dbellamy Exp $

//Transmission ensuite du fichier converti
$base_path = ".";
$base_auth = "CATALOGAGE_AUTH";
$base_title = "\$msg[histo_title]";
$base_nobody=1;

include($base_path."/includes/error_report.inc.php") ;
if ($_POST["act"]=="export") {
	if (isset($_POST["sel"])) $base_noheader=1;
	$base_nosession=0;
	// ATTENTION : était à 1 pour permettre l'envoi du header et pas du ccokie avant, bug IE mais finalement, marche arrière...
}
require($base_path."/includes/init.inc.php");

if ($act) {
	switch ($act) {
		case 'del':
			require_once($base_path."/includes/history_functions.inc.php");
			
			//parcours du tableau des recherches de l'historique cochées
					
			for ($i=0;$i<=count($sel)-1;$i++) {
				$t1=array();
				$t1=suppr_histo($sel[$i]-1,$t1);
				$t1=reorg_tableau_suppr($t1);
				foreach ($t1 as $key => $value) {
					if ($key!=$value) {
						$_SESSION["session_history"][$value]=$_SESSION["session_history"][$key];
						if ($_SESSION["session_history"][$value]["NOTI"]["SEARCH_TYPE"]=="extended") {
							for ($j=0;$j<=count($_SESSION["session_history"][$value]["NOTI"]["POST"]["search"])-1;$j++) {
								if ($_SESSION["session_history"][$value]["NOTI"]["POST"]["search"][$j]=="s_1") {
									$t2=array();
									$t2[0]=$t1[$_SESSION["session_history"][$key]["NOTI"]["POST"]["field_".$j."_".$_SESSION["session_history"][$key]["NOTI"]["POST"]["search"][$j]][0]];
									$_SESSION["session_history"][$value]["NOTI"]["POST"]["field_".$j."_".$_SESSION["session_history"][$value]["NOTI"]["POST"]["search"][$j]][0]=$t2[0];
									$_SESSION["session_history"][$value]["QUERY"]["POST"]["field_".$j."_".$_SESSION["session_history"][$value]["QUERY"]["POST"]["search"][$j]][0]=$t2[0];
								}
							}
						}
						$_SESSION["session_history"][$key]=array();
						unset($_SESSION["session_history"][$key]);
					}
				}
				
			}
			$_SESSION["CURRENT"]=$_SESSION["session_history"][count($_SESSION["session_history"])];	
			break;
		case 'delall':
			$_SESSION["session_history"]=array();
			$_SESSION["CURRENT"]=false;
			break;
		case 'export':
			if ($sel) {
				header("Content-Type: text/txt");
				header('Content-Disposition: attachment; filename="search.rsh"');
				$export=array();
				$f=0;
				for ($i=0; $i<count($sel); $i++) {
					$export[$f]=$_SESSION["session_history"][$sel[$i]-1];
					$f++;
					for ($j=0;$j<=count($_SESSION["session_history"])-1;$j++) {
						if ($_SESSION["session_history"][$j]["NOTI"]["SEARCH_TYPE"]=="extended") {
							for ($x=0;$x<=count($_SESSION["session_history"][$j]["NOTI"]["POST"]["search"])-1;$x++) {
								if ($_SESSION["session_history"][$j]["NOTI"]["POST"]["search"][$x]=="s_1") {
    								if ($_SESSION["session_history"][$j]["NOTI"]["POST"]["field_".$x."_".$_SESSION["session_history"][$j]["NOTI"]["POST"]["search"][$x]][0]==$sel[$i]-1) {
										$export[$f]=$_SESSION["session_history"][$j];
										$export[$f]["NOTI"]["POST"]["field_".$x."_".$_SESSION["session_history"][$j]["NOTI"]["POST"]["search"][$x]][0]=$f-1;
										$export[$f]["QUERY"]["POST"]["field_".$x."_".$_SESSION["session_history"][$j]["QUERY"]["POST"]["search"][$x]][0]=$f-1;
										$f++;
									}		
								}	
							}
						}	
					}
				}
				$export_serialized=serialize($export);
				print $export_serialized;
				exit();
			} else {
				$alert=htmlentities($msg["no_search_selected"],ENT_QUOTES,$charset);
			}
			break;
		case 'import':
			print "<body class='catalog'><div id='contenu-frame'><table width='100%'><tr><td align='left'><h3>".$msg["histo_import_title"]."</h3></td><td align='right'><a href='#' onClick=\"parent.document.getElementById('history').style.display='none'; return false;\"><img src='images/close.gif' border='0' align='center'></a></td></tr></table>";
			print "<form name='history_form' method='post' action='history.php?act=import2' enctype='multipart/form-data' class='form-catalog'>";
			print "<div class='form-contenu'>";
			print $msg["histo_select_file"]."<br />";
			print "<input type='file' name='search_file'/><br /><br />";
			print "</div>";
			print "<center><input type='submit' value='".$msg["histo_import_button"]."' class='bouton'/>&nbsp;<input type='button' value='".$msg["print_cancel"]."' class='bouton' onClick=\"document.location='history.php'; return false;\"/></center>";
			print "</form>";
			print "</div></body>";
			print "</html>";
			exit();
			break;
		case 'import2':
			$error=false;
			if ($_FILES['search_file']['tmp_name']) {
				$fp=@fopen($_FILES['search_file']['tmp_name'],'r');
				if ($fp) {
					$searches=fread($fp,filesize($_FILES['search_file']['tmp_name']));
					$import=unserialize($searches);
					if ($import) {
						for ($i=0; $i<count($import); $i++) {
							for ($x=0;$x<=count($import[$i]["NOTI"]["POST"]["search"])-1;$x++) {
								if ($import[$i]["NOTI"]["POST"]["search"][$x]=="s_1") {
									$import[$i]["NOTI"]["POST"]["field_".$x."_".$import[$i]["NOTI"]["POST"]["search"][$x]][0]=$import[$i]["NOTI"]["POST"]["field_".$x."_".$import[$i]["NOTI"]["POST"]["search"][$x]][0]+count($_SESSION["session_history"]);
									$import[$i]["QUERY"]["POST"]["field_".$x."_".$import[$i]["QUERY"]["POST"]["search"][$x]][0]=$import[$i]["QUERY"]["POST"]["field_".$x."_".$import[$i]["QUERY"]["POST"]["search"][$x]][0]+count($_SESSION["session_history"]);
								}
							}
						}
						for ($i=0; $i<count($import); $i++) {
							$_SESSION["session_history"][]=$import[$i];
						}
					} else $error=true;
					fclose($fp);
					unlink($_FILES['search_file']['tmp_name']);
				} else $error=true;
			} else $error=true;
			if ($error) $alert=$msg["histo_upload_failed"];
			break;
		case 'save':
			$save=serialize($_SESSION['session_history']);
			$requete="replace into admin_session values(".SESSuserid.",'".addslashes($save)."')";
			$r=mysql_query($requete);
			if (!$r) $alert="La sauvegarde a échouée !"; else $alert=$msg["histo_save_done"];
			break;
	}
}
print "<body class='catalog'><div id='contenu-frame'><table width='100%'><tr><td align='left'><h3>".$msg["histo_title"]."</h3></td><td align='right'><a href='#' onClick=\"parent.document.getElementById('history').style.display='none'; return false;\"><img src='images/close.gif' border='0' align='center'></a></td></tr></table>";
print "<form name='history_form' method='post' action='history.php'>";
if ($alert) {
	print "<script>alert(\"".$alert."\")</script>";
}
print "<input type='hidden' name='act' value=''/>";
if (count($_SESSION["session_history"])) {
	print $begin_result_liste."&nbsp;";
	print "<a href='#' onClick=\"document.history_form.act.value='del'; document.history_form.submit(); return false;\"><img src='images/suppr_coche.gif' alt=\"".$msg["histo_del_selected"]."\" title=\"".$msg["histo_del_selected"]."\" /></a>&nbsp;";
	print "<a href='#' onClick=\"document.history_form.act.value='delall'; document.history_form.submit(); return false;\"><img src='images/suppr_all.gif' alt=\"".$msg["histo_del_histo"]."\" title=\"".$msg["histo_del_histo"]."\" /></a>&nbsp;";
	print "&nbsp;<a href='#' onClick=\"document.history_form.act.value='save'; document.history_form.submit(); return false;\"><img src='images/save.gif' alt=\"".$msg["histo_save_histo"]."\" title=\"".$msg["histo_save_histo"]."\" /></a>&nbsp;";
	print "&nbsp;<a href='#' onClick=\"document.history_form.act.value='export'; document.history_form.submit(); return false;\"><img src='images/upload.gif' alt=\"".$msg["histo_export_selected"]."\" title=\"".$msg["histo_export_selected"]."\" /></a>&nbsp;";
	print "&nbsp;<a href='#' onClick=\"document.history_form.act.value='import'; document.history_form.submit(); return false;\"><img src='images/download.gif' alt=\"".$msg["histo_import_searches"]."\" title=\"".$msg["histo_import_searches"]."\" /></a><br />";
	for ($i=count($_SESSION["session_history"])-1; $i>=0; $i--) {
		$javascript_template ="
		<div id=\"el!!id!!Parent\" class=\"notice-parent\">
    		<input type='checkbox' name='sel[]' value='".($i+1)."'/><img src=\"./images/plus.gif\" class=\"img_plus\" name=\"imEx\" id=\"el!!id!!Img\" title=\"détail\" border=\"0\" onClick=\"expandBase('el!!id!!', true); return false;\" hspace=\"3\">
    		<span class=\"notice-heada\">!!query!!</span>
    		<br />
		</div>
		<div id=\"el!!id!!Child\" !!sO!! class=\"notice-child\" style=\"margin-bottom:6px;width:auto;border:none;display:none;\">
        !!subqueries!!
 		</div>";
 		
 		$query_prep=($i+1).")";
 		if (!$_SESSION["session_history"][$i]["QUERY"]["NOLINK"]) $query_prep.=" <a href='#' onClick=\"parent.document.location='recall.php?t=QUERY&current=$i'; return false;\">";
 		$query_prep.=$_SESSION["session_history"][$i]["QUERY"]["HUMAN_TITLE"]." : ".$_SESSION["session_history"][$i]["QUERY"]["HUMAN_QUERY"];
 		if (!$_SESSION["session_history"][$i]["QUERY"]["NOLINK"]) $query_prep.="</a>";
 		$to_print=str_replace("!!query!!",$query_prep,$javascript_template);
		$subqueries="";
		if (($_SESSION["session_history"][$i]["AUT"])||($_SESSION["session_history"][$i]["NOTI"])||($_SESSION["session_history"][$i]["EXPL"])) {
			$subqueries.="<table width='100%' id='history_table'>";
			if (($_SESSION["session_history"][$i]["AUT"])&&($_SESSION["session_history"][$i]["NOTI"])) {
				$image="./images/branch.png"; 
				$background="./images/branch_background.png";
			} else {
				$image="./images/branch_final.png";
				$background="";
			}
			if ($_SESSION["session_history"][$i]["AUT"]) $subqueries.="<tr><td width='15px' valign='top' style=\"background:url('$background') repeat-y;\"><img src='$image' align='center'/></td><td><a href='#' onClick=\"parent.document.location='recall.php?t=AUT&current=$i'; return false;\"><b>A</b> ".$_SESSION["session_history"][$i]["AUT"]["HUMAN_QUERY"].", page ".$_SESSION["session_history"][$i]["AUT"]["PAGE"]."</a></td></tr>\n";
			if ($_SESSION["session_history"][$i]["NOTI"]) {
				$subqueries.="<tr><td width='15' valign='top'><img src='./images/branch_final.png' align='center'/></td><td><a href='#' onClick=\"parent.document.location='recall.php?t=NOTI&current=$i'; return false;\"><b>N</b> ".$_SESSION["session_history"][$i]["NOTI"]["HUMAN_QUERY"].", page ".$_SESSION["session_history"][$i]["NOTI"]["PAGE"]."</a>";
				if (!$_SESSION["session_history"][$i]["NOTI"]["NOPRINT"])
					$subqueries.="&nbsp;<a href='#' onClick=\"openPopUp('./print_cart.php?current_print=$i&action=print_prepare','print',500, 600, -2, -2, 'scrollbars=yes,menubar=0'); return false;\"><img src='./images/basket_small_20x20.gif' border='0' align='center' alt=\"".$msg["histo_add_to_cart"]."\" title=\"".$msg["histo_add_to_cart"]."\"></a>&nbsp;<a href='#' onClick=\"w=openPopUp('./print.php?current_print=$i&action_print=print_prepare','print',500,600,-2,-2,'scrollbars=yes,menubar=0'); return false;\"><img src='./images/print.gif' border='0' align='center' alt=\"".$msg["histo_print"]."\" title=\"".$msg["histo_print"]."\"/></a>";
					if ($pmb_allow_external_search) 
						$subqueries.="&nbsp;<a href='#' onClick=\"parent.document.location='recall.php?t=NOTI&current=$i&external=1'; return false;\"><img src='./images/external_search.png' border='0' align='center' alt=\"".$msg["connecteurs_external_search_sources"]."\" title=\"".$msg["connecteurs_external_search_sources"]."\"/></a>";
				$subqueries.="</td></tr>\n";
			}
			if ($_SESSION["session_history"][$i]["EXPL"]) {
				$subqueries.="<tr><td width='15' valign='top'><img src='./images/branch_final.png' align='center'/></td><td><a href='#' onClick=\"parent.document.location='recall.php?t=EXPL&current=$i'; return false;\"><b>N</b> ".$_SESSION["session_history"][$i]["EXPL"]["HUMAN_QUERY"].", page ".$_SESSION["session_history"][$i]["EXPL"]["PAGE"]."</a>";
				$subqueries.="</td></tr>\n";
			}			
			$subqueries.="</table>";
		}
		$to_print=str_replace("!!subqueries!!",$subqueries,$to_print);
		$to_print=str_replace("!!id!!",$i+1,$to_print);
		if (($_SESSION["CURRENT"]!==false)&&($_SESSION["CURRENT"]==$i))
			$to_print=str_replace("!!sO!!","startOpen=\"Yes\"",$to_print);
		else
			$to_print=str_replace("!!sO!!","",$to_print);
		print pmb_bidi($to_print);
	}
} else {
	print "<b>".$msg["histo_empty"]."</b><br />";
	print "<a href='#' onClick=\"document.history_form.act.value='import'; document.history_form.submit(); return false;\"><img src='images/download.gif' alt=\"".$msg["histo_import_searches"]."\" title=\"".$msg["histo_import_searches"]."\" align='center'/></a><br />";
}
print "</form>";
print "</div></body></html>";
?>