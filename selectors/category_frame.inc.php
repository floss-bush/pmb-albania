<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: category_frame.inc.php,v 1.12 2010-12-15 13:37:03 arenou Exp $
//
// Frames pour les catégories : il faut faire deux frames pour pouvoir naviger par terme

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

$base_query = "caller=$caller&p1=$p1&p2=$p2&no_display=$no_display&bt_ajouter=$bt_ajouter&dyn=$dyn&keep_tilde=$keep_tilde&parent=$parent&id2=$id2&deb_rech=".rawurlencode(stripslashes($deb_rech))."&callback=".$callback."&infield=".$infield;

?>
<script>self.focus();</script>
<frameset rows="135,*" border=0>
	<frame name="category_search" src="./selectors/category.php?<?php echo $base_query;?>">
	<frame name="category_browse" src="">
</frameset>