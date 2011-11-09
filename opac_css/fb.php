<?php
// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: fb.php,v 1.1 2011-04-15 15:16:02 arenou Exp $


$title= $_GET['title'];
$desc= $_GET['desc'];
$url= $_GET['url'];
$id= $_GET['id'];

print "
<html xmlns='http://www.w3.org/1999/xhtm'
      xmlns:og='http://ogp.me/ns#'
      xmlns:fb='http://www.facebook.com/2008/fbml'>
	<head>
		<meta name='title' content='$title' />
		<meta name='description' content='$desc' />
		<title>$title</title>
		
		<script type='text/javascript'>
			document.location='$url&id=$id';
		</script>
	</head>
</html>";
?>
