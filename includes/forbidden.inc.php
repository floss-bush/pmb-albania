<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+

function forbidden($image_path='../images') {

print "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN'
  'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' lang='fr'>
  <head>
  	<META HTTP-EQUIV=\"pragma\" CONTENT=\"no-cache\">
	<META HTTP-EQUIV=\"expires\" CONTENT=\"Wed, 30 Sept 2001 12:00:00 GMT\">
    <title>
      PMB. Forbidden Zone
    </title>
  </head>
  <body bgcolor=\"#ffffff\">
  	$page
    <br />

    <br />

    <br />

    <div align=\"center\">
    	<strong>PMB. Forbidden Zone</strong><br />
      <img src=\"$image_path/forbidden.jpg\" title=\"forbidden\" alt=\"forbidden\">
    </div>
  </body>
</html>
";

die;
}

