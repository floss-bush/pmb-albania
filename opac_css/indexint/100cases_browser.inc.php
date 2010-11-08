<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: 100cases_browser.inc.php,v 1.12 2009-05-16 10:52:52 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

print preg_replace('/!!indexint_title!!/m',$msg["100_cases_table"], $decimal_see_header);

$hundred_cases_table ="
<script language=\"JavaScript\">
function switchOver(cellule,imagefond)
{
if (document.all)
{
cellule.style.background = \"url(\"+imagefond+\") repeat bottom left\";
}
else
cellule.style.background = \"url(\"+imagefond+\") repeat bottom left\";
}
</script>
\n<table border=\"2\" cellpadding=\"1\" cellspacing=\"1\">";

$i=0;
$j=0;
$hundred_cases_table .= "\n<tr height=55>";
while ($j < 10) {
	if ($j==0 or $j==1 or $j==7 or $j==8) $color="#ffffff"; 
		else $color="#000000";
	$hundred_cases_table .= "\n\t<td style=\"background:url(images/dewey".$j.".gif) repeat bottom left;\" onmouseover=\"switchOver(this,'images/dewey".$j.".gif')\" onmouseout=\"switchOver(this,'images/dewey".$j.".gif')\"><a style=\"color:$color;font-size:smaller;\" href=index.php?lvl=indexint_see&id=!!id".$j.$i."0!!&main=1><b>".$j.$i."0<br />!!".$j.$i."0!!</b></a></td>";
	$j++;
	}
$hundred_cases_table .= "\n</tr>";

$i = 1;
while ($i < 10) {
	$j=0;
	$hundred_cases_table .= "\n<tr height=55>";
	while ($j < 10) {
    		if ($j==0 or $j==1 or $j==7 or $j==8) $color="#ffffff";
    			else $color="#000000";
		$hundred_cases_table .= "\n\t<td style=\"filter:blendTrans(Duration=0.7);background:url(images/dewey".$j."cell.gif) repeat bottom left;\" onmouseover=\"switchOver(this,'images/dewey".$j.".gif')\" onmouseout=\"switchOver(this,'images/dewey".$j."cell.gif')\"><a style=\"color:$color;font-size:smaller;\" href=index.php?lvl=indexint_see&id=!!id".$j.$i."0!!&main=1>!!".$j.$i."0!!<br /><div align=right>".$j.$i."0</div></a></td>";
		$j++;
		}
	$i++;
	$hundred_cases_table .= "\n</tr>";
	// La valeur affiche est $i avant l'incrémentation (post-incrémentation)
	}
$hundred_cases_table .= "\n</table>";

$rqt = " select indexint_id, indexint_comment, indexint_name from indexint where indexint_name REGEXP \"^..0$\" ";
$res = mysql_query($rqt, $dbh);
while($indexint=mysql_fetch_object($res)) {
	$indexint->indexint_comment = pmb_preg_replace('/\r/', ' ', $indexint->indexint_comment);
	$indexint->indexint_comment = pmb_preg_replace('/\n/', ' ', $indexint->indexint_comment);
	$hundred_cases_table = pmb_preg_replace("/!!".$indexint->indexint_name."!!/m", htmlentities($indexint->indexint_comment,ENT_QUOTES,$charset), $hundred_cases_table);
	$hundred_cases_table = pmb_preg_replace("/!!id".$indexint->indexint_name."!!/", $indexint->indexint_id, $hundred_cases_table);
	}

print pmb_bidi($hundred_cases_table);
print "<br />";
print $decimal_see_footer;
