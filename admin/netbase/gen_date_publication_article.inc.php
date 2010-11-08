<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: gen_date_publication_article.inc.php,v 1.3 2009-05-16 11:11:53 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$req="select date_date,analysis_notice from analysis,bulletins where analysis_bulletin=bulletin_id";	
$res=mysql_query($req,$dbh);	
if(mysql_num_rows($res))while (($row = mysql_fetch_object ($res))) {
	$year=substr($row->date_date,0,4);
	if($year) {
		$req="UPDATE notices SET year='$year' where notice_id=".$row->analysis_notice;
		mysql_query($req,$dbh);
	}		
} 

$spec = $spec - GEN_DATE_PUBLICATION_ARTICLE;

$v_state=urldecode($v_state);
$v_state.= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg["gen_date_publication_article_end"], ENT_QUOTES, $charset).".";

print "<form class='form-$current_module' name='process_state' action='./clean.php' method='post'>
			<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
			<input type='hidden' name='spec' value=\"$spec\">
		</form>
		<script type=\"text/javascript\"><!--
			document.forms['process_state'].submit();
			-->
		</script>";
