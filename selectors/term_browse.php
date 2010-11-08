<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: term_browse.php,v 1.7 2007-03-10 09:47:24 touraine37 Exp $
//
if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// Frames pour naviger par terme

$base_path="..";                            
$base_auth = ""; 
$base_nobody=1;

require_once ("$base_path/includes/init.inc.php");  

$base_query = "caller=$caller&p1=$p1&p2=$p2&no_display=$no_display&bt_ajouter=$bt_ajouter&parent=&dyn=$dyn&keep_tilde=$keep_tilde";
$base_query.= "&id_thes=$id_thes";
?>
<frameset rows="120,*">
	<frame name="term_search" src="term_search.php?user_input=<?php echo rawurlencode(stripslashes($user_input)); ?>&f_user_input=<?php echo rawurlencode(stripslashes($f_user_input));?>&<?php echo $base_query;?>">
	<frame name="term_show" src="">
</frameset>
