<?php
// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: description.inc.php,v 1.1 2010-07-08 15:28:34 arenou Exp $

$submenu.= "		
			".$class_param->descriptions[$quoi]."<br />
			<img src='$visionneuse_path/".$class_param->screenshoots[$quoi]."' title='$quoi' alt='$quoi' width='500px'/><br />
			mimetypes supportés :<br />
			<ul>";

foreach($class_param->classMimetypes[$quoi] as $mimetype){
$submenu.="
				<li>$mimetype</li>
";	
}
$submenu.="				
			</ul>";
?>
