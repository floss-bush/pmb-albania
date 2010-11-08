<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: marguerite_browser.inc.php,v 1.11 2009-05-16 10:52:52 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$marguerite_img ="<SCRIPT LANGUAGE=\"JavaScript\"><!--
var js = 1.0;

Version = parseInt(navigator.appVersion);

if (navigator.appName == \"Netscape\")
    js = ((Version >= 4) ? 1.2 : ( (Version == 3) ? 1.1 : 1.0 ));
else
    if (navigator.appVersion.indexOf('MSIE') != -1) 
        js = ((Version >= 4) ? 1.1 : 1.0);

function changeImagemap(newImage) {
    if (js > 1.0) document ['boxImage'].src = eval(newImage + \".src\");
}

if (js > 1.0) {
	mapMarg = new Image();
	mapMarg.src = \"images/marg.gif\";

    map0 = new Image();
    map0.src  = \"images/marg0.gif\";

    map1 = new Image();
    map1.src  = \"images/marg1.gif\";

    map2 = new Image();
    map2.src  = \"images/marg2.gif\";

    map3 = new Image();
    map3.src  = \"images/marg3.gif\";

    map4 = new Image();
    map4.src  = \"images/marg4.gif\";

    map5 = new Image();
    map5.src  = \"images/marg5.gif\";

    map6 = new Image();
    map6.src  = \"images/marg6.gif\";

    map7 = new Image();
    map7.src  = \"images/marg7.gif\";

    map8 = new Image();
    map8.src  = \"images/marg8.gif\";

    map9 = new Image();
    map9.src  = \"images/marg9.gif\";

}



//-->

	mapMarg = new Image();
	mapMarg.src = \"images/marg.gif\";

    map0 = new Image();
    map0.src  = \"images/marg0.gif\";

    map1 = new Image();
    map1.src  = \"images/marg1.gif\";

    map2 = new Image();
    map2.src  = \"images/marg2.gif\";

    map3 = new Image();
    map3.src  = \"images/marg3.gif\";

    map4 = new Image();
    map4.src  = \"images/marg4.gif\";

    map5 = new Image();
    map5.src  = \"images/marg5.gif\";

    map6 = new Image();
    map6.src  = \"images/marg6.gif\";

    map7 = new Image();
    map7.src  = \"images/marg7.gif\";

    map8 = new Image();
    map8.src  = \"images/marg8.gif\";

    map9 = new Image();
    map9.src  = \"images/marg9.gif\";

</SCRIPT>
<map name=\"image-map\">
  <area shape='poly' coords='176,14,265,43,178,168' HREF='index.php?lvl=indexint_see&id=!!id100!!&main=1'
   onMouseOver='changeImagemap(\"map1\");self.status=\"".$msg["put_mouse_over_petals"]."\";document.getElementById(\"marguerite_petal_text\").innerHTML = \"!!100!!\";return true'
   onMouseOut='changeImagemap(\"mapMarg\");self.status=\"\";document.getElementById(\"marguerite_petal_text\").innerHTML = \"".$msg["put_mouse_over_petals"]."\";return true'>
  <area shape='poly' coords='265,44,322,119,177,171' HREF='index.php?lvl=indexint_see&id=!!id200!!&main=1'
   onMouseOver='changeImagemap(\"map2\");self.status=\"".$msg["put_mouse_over_petals"]."\";document.getElementById(\"marguerite_petal_text\").innerHTML = \"!!200!!\";return true'
   onMouseOut='changeImagemap(\"mapMarg\");self.status=\"\";document.getElementById(\"marguerite_petal_text\").innerHTML = \"".$msg["put_mouse_over_petals"]."\";return true'>
  <area shape='poly' coords='177,172,321,121,323,216' HREF='index.php?lvl=indexint_see&id=!!id300!!&main=1'
   onMouseOver='changeImagemap(\"map3\");self.status=\"".$msg["put_mouse_over_petals"]."\";document.getElementById(\"marguerite_petal_text\").innerHTML = \"!!300!!\";return true'
   onMouseOut='changeImagemap(\"mapMarg\");self.status=\"\";document.getElementById(\"marguerite_petal_text\").innerHTML = \"".$msg["put_mouse_over_petals"]."\";return true'>
  <area shape='poly' coords='176,174,321,218,269,298' HREF='index.php?lvl=indexint_see&id=!!id400!!&main=1'
   onMouseOver='changeImagemap(\"map4\");self.status=\"".$msg["put_mouse_over_petals"]."\";document.getElementById(\"marguerite_petal_text\").innerHTML = \"!!400!!\";return true'
   onMouseOut='changeImagemap(\"mapMarg\");self.status=\"\";document.getElementById(\"marguerite_petal_text\").innerHTML = \"".$msg["put_mouse_over_petals"]."\";return true'>
  <area shape='poly' coords='267,299,178,330,176,177' HREF='index.php?lvl=indexint_see&id=!!id500!!&main=1'
   onMouseOver='changeImagemap(\"map5\");self.status=\"".$msg["put_mouse_over_petals"]."\";document.getElementById(\"marguerite_petal_text\").innerHTML = \"!!500!!\";return true'
   onMouseOut='changeImagemap(\"mapMarg\");self.status=\"\";document.getElementById(\"marguerite_petal_text\").innerHTML = \"".$msg["put_mouse_over_petals"]."\";return true'>
  <area shape='poly' coords='172,171,174,326,83,297' HREF='index.php?lvl=indexint_see&id=!!id600!!&main=1'
   onMouseOver='changeImagemap(\"map6\");self.status=\"".$msg["put_mouse_over_petals"]."\";document.getElementById(\"marguerite_petal_text\").innerHTML = \"!!600!!\";return true'
   onMouseOut='changeImagemap(\"mapMarg\");self.status=\"\";document.getElementById(\"marguerite_petal_text\").innerHTML = \"".$msg["put_mouse_over_petals"]."\";return true'>
  <area shape='poly' coords='172,167,23,217,82,293' HREF='index.php?lvl=indexint_see&id=!!id700!!&main=1'
   onMouseOver='changeImagemap(\"map7\");self.status=\"".$msg["put_mouse_over_petals"]."\";document.getElementById(\"marguerite_petal_text\").innerHTML = \"!!700!!\";return true'
   onMouseOut='changeImagemap(\"mapMarg\");self.status=\"\";document.getElementById(\"marguerite_petal_text\").innerHTML = \"".$msg["put_mouse_over_petals"]."\";return true'>
  <area shape='poly' coords='23,125,26,218,173,170' HREF='index.php?lvl=indexint_see&id=!!id800!!&main=1'
   onMouseOver='changeImagemap(\"map8\");self.status=\"".$msg["put_mouse_over_petals"]."\";document.getElementById(\"marguerite_petal_text\").innerHTML = \"!!800!!\";return true'
   onMouseOut='changeImagemap(\"mapMarg\");self.status=\"\";document.getElementById(\"marguerite_petal_text\").innerHTML = \"".$msg["put_mouse_over_petals"]."\";return true'>
  <area shape='poly' coords='173,168,24,122,80,42' HREF='index.php?lvl=indexint_see&id=!!id900!!&main=1'
   onMouseOver='changeImagemap(\"map9\");self.status=\"".$msg["put_mouse_over_petals"]."\";document.getElementById(\"marguerite_petal_text\").innerHTML = \"!!900!!\";return true'
   onMouseOut='changeImagemap(\"mapMarg\");self.status=\"\";document.getElementById(\"marguerite_petal_text\").innerHTML = \"".$msg["put_mouse_over_petals"]."\";return true'>
  <area shape='poly' coords='171,165,78,39,167,10' HREF='index.php?lvl=indexint_see&id=!!id000!!&main=1'
   onMouseOver='changeImagemap(\"map0\");self.status=\"".$msg["put_mouse_over_petals"]."\";document.getElementById(\"marguerite_petal_text\").innerHTML = \"!!000!!\";return true'
   onMouseOut='changeImagemap(\"mapMarg\");self.status=\"\";document.getElementById(\"marguerite_petal_text\").innerHTML = \"".$msg["put_mouse_over_petals"]."\";return true'>
</map>
<img NAME=\"boxImage\" src=\"images/marg.gif\" width=\"348\" height=\"341\" border=\"0\" USEMAP=\"#image-map\">";

$rqt = " select indexint_id, indexint_comment, indexint_name from indexint where indexint_name in ('000','100','200','300','400','500','600','700','800','900') ";
$res = mysql_query($rqt, $dbh);
while($indexint=mysql_fetch_object($res)) {
	$indexint->indexint_comment = pmb_preg_replace('/\r/', ' ', $indexint->indexint_comment);
	$indexint->indexint_comment = pmb_preg_replace('/\n/', ' ', $indexint->indexint_comment);
	$marguerite_img = pmb_preg_replace("/!!".$indexint->indexint_name."!!/m", htmlentities($indexint->indexint_comment,ENT_QUOTES,$charset), $marguerite_img);
	$marguerite_img = pmb_preg_replace("/!!id".$indexint->indexint_name."!!/", $indexint->indexint_id, $marguerite_img);
	}
print preg_replace('/!!indexint_title!!/m',$msg["colors_marguerite"], $decimal_see_header);
print "<center>".$marguerite_img;
print "<div id=\"marguerite_petal_text\">".$msg["put_mouse_over_petals"]."</div></center><br />";
print $decimal_see_footer;
