<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: term_browse.php,v 1.5 2007-03-10 09:03:18 touraine37 Exp $
//
// Frames pour naviger par terme

$base_path="../../..";                            
$base_auth = ""; 
$base_nobody=1;

require_once ("$base_path/includes/init.inc.php");  

$base_query = "id_empr=$id_empr&groupID=$groupID&unq=$unq";
?>
<frameset rows="120,*">
	<frame name="term_search" src="term_search.php?user_input=<?php echo rawurlencode(stripslashes($search_term)); ?>&f_user_input=<?php echo rawurlencode(stripslashes($search_term));?>&<?php echo $base_query;?>&id_thes=<?php echo $id_thes; ?>">
	<frame name="term_show" src="">
</frameset>
