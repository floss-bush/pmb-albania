<?php
// +--------------------------------------------------------------------------+
// | PMB est sous licence GPL, la réutilisation du code est cadrée            |
// +--------------------------------------------------------------------------+
// $Id: print.php,v 1.28 2010-08-11 10:08:23 ngantier Exp $

//Impression

$base_path = ".";
$base_auth = "CATALOGAGE_AUTH|CIRCULATION_AUTH";
$base_title = "\$msg[print_title]";
$base_nobody=1;
$base_noheader=1;

require($base_path."/includes/init.inc.php");

if (($action_print=="")&&($_SESSION["PRINT"])) {
	if ($_SESSION["PRINT"]["output"]=="tt") {
		header("Content-Type: application/word");
		header("Content-Disposition: attachement; filename=liste.doc");
	}
}

require_once($class_path."/mono_display.class.php");
require_once($include_path."/notice_authors.inc.php");
require_once($include_path."/notice_categories.inc.php");
require_once($class_path."/author.class.php");
require_once($class_path."/editor.class.php");
require_once($include_path."/isbn.inc.php");
require_once($class_path."/collection.class.php");
require_once($class_path."/subcollection.class.php");
require_once($class_path."/serie.class.php");
require_once($include_path."/explnum.inc.php");
require_once($class_path."/category.class.php");
require_once($class_path."/indexint.class.php");
require_once($class_path."/search.class.php");
require_once($class_path."/serial_display.class.php");
require_once($include_path."/mail.inc.php");
require_once($class_path."/notice_tpl_gen.class.php");

if ($action_print=="print_prepare") {
	print $std_header;
	print "<h3>".$msg["print_options"]."</h3>\n";
	print "<form name='print_options' action='print.php?action_print=print' method='post'>
	<b>".$msg["print_size"]."</b>";
	if(!$notice_id) 
	print"
	<blockquote>
		<input type='radio' name='pager' value='1'/>&nbsp;".$msg["print_size_current_page"]."<br />
		<input type='radio' name='pager' value='0' checked='checked'/>&nbsp;".$msg["print_size_all"]."
	</blockquote>";

	$sort_info = $sort_id ? '<input type="hidden" name="sort_id" value="'.$sort_id.'">' : '';
	
	$onchange="
		var div_sel=document.getElementById('sel_notice_tpl');
		var div_sel2=document.getElementById('sel_notice_tpl2');
		var notice_tpl=document.getElementById('notice_tpl');
		var sel=notice_tpl.options[notice_tpl.selectedIndex].value;
	    if(sel>0){
	    	div_sel.style.display='none';
	    	div_sel2.style.display='none';
	    }else { 
	    	div_sel.style.display='block';
	    	div_sel2.style.display='block';
	    }		    
	";
	$sel_notice_tpl=notice_tpl_gen::gen_tpl_select("notice_tpl",0,$onchange);
	
	print"
	<b>".$msg["print_format_title"]."</b>
	<blockquote>
		$sel_notice_tpl
		<div id='sel_notice_tpl'>
			<input type='radio' name='short' value='1' checked='checked'/>&nbsp;".$msg["print_short_format"]."<br />
			<input type='radio' name='short' value='6'/>&nbsp;".$msg["print_long_format"]."<br />
			<input type='checkbox' name='header' id='header' value='1'  checked/>&nbsp;<label for='header'>".$msg["print_header"]."</label><br/>
			<input type='checkbox' name='permalink' value='1'/>&nbsp;".$msg["print_permalink"]."<br />
		</div>	
	</blockquote>
	<div id='sel_notice_tpl2'>
	<b>".$msg["print_ex_title"]."</b>
	<blockquote>
		<input type='radio' name='ex' value='1'/>&nbsp;".$msg["print_ex"]."<br />
		<b>".$msg["print_numeric_ex_title"]."</b>
		<blockquote>
			<input type='radio' name='exnum' value='1'/>&nbsp;".$msg["print_numeric_ex"]."<br />
			<input type='radio' name='exnum' value='0' checked='checked'/>&nbsp;".$msg["print_no_numeric_ex"]."
		</blockquote>
		<input type='radio' name='ex' value='0' checked='checked'/>&nbsp;".$msg["print_no_ex"]."
	</blockquote>
	</div>
	<b>".$msg["print_output_title"]."</b>
	<blockquote>
		<input type='radio' name='output' value='printer' checked='checked'/>&nbsp;".$msg["print_output_printer"]."<br />
		<input type='radio' name='output' value='tt'/>&nbsp;".$msg["print_output_writer"]."<br />
		<input type='radio' name='output' value='email'/>&nbsp;".$msg["print_output_email"]."<br />
		&nbsp;&nbsp;".$msg["print_emaildest"]."<input type='text' name='emaildest' value='' />
	</blockquote>
	<input type='hidden' name='current_print' value='$current_print'/>
	<input type='hidden' name='notice_id' value='$notice_id'/>".$sort_info."
	<center><input type='submit' value='".$msg["print_print"]."' class='bouton'/>&nbsp;<input type='button' value='".$msg["print_cancel"]."' class='bouton' onClick='self.close();'/></center>";
	print "</form></body></html>";
}

if ($action_print=="print") {
	if ($_SESSION["session_history"][$current_print]) {
		$_SESSION["PRINT"]=$_SESSION["session_history"][$current_print]["NOTI"];
		$_SESSION["PRINT"]["short"]=$short;
		$_SESSION["PRINT"]["ex"]=$ex;
		$_SESSION["PRINT"]["exnum"]=$exnum;
		$_SESSION["PRINT"]["output"]=$output;
		$_SESSION["PRINT"]["emaildest"]=$emaildest;
		$_SESSION["PRINT"]["pager"]=$pager;
		$_SESSION["PRINT"]["notice_id"]=$notice_id;
		$_SESSION["PRINT"]["permalink"]=$permalink;
		$_SESSION["PRINT"]["header"]=$header;
		$_SESSION["PRINT"]["notice_tpl"]=$notice_tpl;
	//	if ($sort_id)
			//$_SESSION["PRINT"]["sort_id"]=$sort_id;
			$_SESSION["PRINT"]["sort_id"]=$_SESSION['tri'];
		echo "<script>document.location='./print.php'</script>";
	} elseif ($notice_id) {
		$_SESSION["PRINT"]["short"]=$short;
		$_SESSION["PRINT"]["ex"]=$ex;
		$_SESSION["PRINT"]["exnum"]=$exnum;
		$_SESSION["PRINT"]["output"]=$output;
		$_SESSION["PRINT"]["emaildest"]=$emaildest;
		$_SESSION["PRINT"]["pager"]=$pager;
		$_SESSION["PRINT"]["notice_id"]=$notice_id;
		$_SESSION["PRINT"]["permalink"]=$permalink;
		$_SESSION["PRINT"]["header"]=$header;
		$_SESSION["PRINT"]["notice_tpl"]=$notice_tpl;
		echo "<script>document.location='./print.php'</script>";		
	} else {
		echo "<script>alert(\"".$msg["print_no_search"]."\"); self.close();</script>";
	}
}

$prefix_url_image=$opac_url_base;



if (($action_print=="")&&($_SESSION["PRINT"])) {

	
	$environement=$_SESSION["PRINT"];
	$limit='';
	if($environement["notice_id"]){
		$requete="select notice_id from notices where notice_id=".$environement["notice_id"];
	} elseif ($environement["TEXT_QUERY"]) {
		$requete=preg_replace('/limit\s+[0-9]\s*,*\s*[0-9]*\s*$/','',$environement["TEXT_QUERY"],1);
	} else {
		switch ($environement["SEARCH_TYPE"]) {
			case "extended":
				$sh=new search();
				$table=$sh->make_search();
				$requete="select notice_id from $table";
				break;
			case "cart":
				$requete="select object_id as notice_id from caddie_content where caddie_id=".$idcaddie;
				break;
		}
	}
	if ($environement["pager"]) {
		$limit="limit ".($nb_per_page_search*($environement["PAGE"]-1)).",$nb_per_page_search";
		$requete.=" $limit";
	}
	
	if ($environement["sort_id"]) {
		$sort = new sort('notices','base');
		$requete = $sort->appliquer_tri($environement["sort_id"] , $requete, "notice_id", 0, -1);
	}
	$resultat=@mysql_query($requete);

	$pheader = '<html><head><title>'.$msg['print_title'].'</title><meta http-equiv=Content-Type content="text/html; charset='.$charset.'" /></head><body>';
	$pheader.= '<style type="text/css">
		body { 	
			font-size: 10pt;
			font-family: verdana, geneva, helvetica, arial;
			color:#000000;
			background:#FFFFFF;
		}
		td {
			font-size: 10pt;
			font-family: verdana, geneva, helvetica, arial;
			color:#000000;
		}
		th {
			font-size: 10pt;
			font-family: verdana, geneva, helvetica, arial;
			font-weight:bold;
			color:#000000;
			background:#DDDDDD;
			text-align:left;
		}
		hr {
			border:none;
			border-bottom:1px solid #000000;
		}
		h3 {
			font-size: 12pt;
			color:#000000;
		} 
		</style>';
	
	$output_final.= $pheader;

	$date_today = formatdate(today()) ;
	$output_final.= '<h3>'.$date_today.'&nbsp;'.sprintf($msg["print_n_notices"],mysql_num_rows($resultat)).'</h3>';
	$output_final.= '<hr style="border:none;border-bottom:solid #000000 3px;"/>';
	
	if($_SESSION["PRINT"]["notice_tpl"])	$noti_tpl=new notice_tpl_gen($_SESSION["PRINT"]["notice_tpl"]);

	while (($r=mysql_fetch_object($resultat))) {
		if($noti_tpl) {
			$output_final.=$noti_tpl->build_notice($r->notice_id,$deflt2docs_location);
			$output_final.="<hr />";
		} else{
			$n=mysql_fetch_object(@mysql_query("select * from notices where notice_id=".$r->notice_id));
			if($n->niveau_biblio != 's' && $n->niveau_biblio != 'a') {
				$mono=new mono_display($n,$environement["short"],"",$environement["ex"],"","","",0,1,$environement["exnum"]);
				if ($environement["header"]) $output_final.= '<b>'.$mono->header.'</b><br /><br />';
				$output_final.= $mono->isbd;
			} else {
				$serial = new serial_display($n, $environement["short"], "", "", "", "", "", 0,1,$environement["exnum"] );
				if ($environement["header"]) $output_final.= '<b>'.$serial->header.'</b><br /><br />';
				$output_final.= $serial->isbd;
			}		
			if($environement["permalink"])
				$output_final .= "<br /><a href='".$pmb_opac_url."index.php?lvl=notice_display&id=".$r->notice_id."'>".substr($pmb_opac_url."index.php?lvl=notice_display&id=".$r->notice_id,0,80)."</a><br />";
			$output_final.="<hr />";
		}	
	}
	switch($environement['output']) {
		
		case 'email':
			$headers  = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=".$charset."\n";
			
			$f_objet_mail = $msg['print_emailobj']." - $biblio_name - $date_today ";
			$f_message_to_send = $output_final.'<br /><br />'.mail_bloc_adresse()."</body></html> ";
			$emaildest=$_SESSION["PRINT"]["emaildest"];
			
			$res_envoi=mailpmb("", $emaildest, $f_objet_mail, $f_message_to_send, $PMBuserprenom." ".$PMBusernom, $PMBuseremail, $headers, "", $PMBuseremailbcc);
			
			if ($res_envoi) {
				print "$pheader\n<br /><br /><center><h3>".sprintf($msg["print_emailsucceed"],$emaildest)."</h3><br /><a href=\"\" onClick=\"self.close(); return false;\">".$msg["print_emailclose"]."</a></center></body></html>" ;
			} else {
				print "$pheader\n<br /><br /><center><h3>".sprintf($msg["print_emailfailed"],$emaildest)."</h3><br /><a href=\"\" onClick=\"self.close(); return false;\">".$msg["print_emailclose"]."</a></center></body></html>" ;
			}
			break;	
		case 'printer':
			$output_final.= '<script type="text/javascript">self.print();</script>';
			$output_final.= '</body></html>';
			print pmb_bidi($output_final);
			break; 					
		case 'tt':
			$output_final.= '</body></html>';
			print pmb_bidi($output_final);
			break; 					
	}
	$_SESSION["PRINT"]=false;
}
?>