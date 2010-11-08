<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// | creator : Eric ROBERT                                                    |
// | modified : ...                                                           |
// +-------------------------------------------------+
// $Id: z_progression_visible2.php,v 1.6 2009-05-16 11:12:03 dbellamy Exp $

// définition du minimum nécéssaire 
$base_path="../..";
$base_auth = "CATALOGAGE_AUTH";  
$base_title = "";    
require_once ("$base_path/includes/init.inc.php");  


// avec MSIE le bouton resultats est toujour visible
// avec Mozilla/Firefox il devient visible à la fin de yaz_wait()
// avec autres browser je ne sais pas ;-)
 
$visi='hidden';
if (preg_match('/\bMSIE\b/i',$_SERVER[HTTP_USER_AGENT])){
	$visi='"visible"';
}

print "
<!--
$_SERVER[HTTP_USER_AGENT]
-->
<br />
<div id='visible2' style='width:95%; visibility:$visi;'>
<form class='form-$current_module' name='zform_results' method='post' action='../../catalog.php?categ=z3950&action=display&id_notice=$id_notice' target='_top'>

<div class='left'>
	$msg[z3950_trier_par]
	<select name='tri1'>
		<option value='auteur' selected>$msg[z3950_auteur]</option>
		<option value='isbn'>$msg[z3950_isbn]</option>
		<option value='bib_nom'>$msg[z3950_serveur]</option>
		<option value='titre'>$msg[z3950_titre]</option>
    </select>
	$msg[z3950_tri_suite]
	<select name='tri2'>
		<option value='auteur' selected>$msg[z3950_auteur]</option>
		<option value='isbn'>$msg[z3950_isbn]</option>
		<option value='bib_nom'>$msg[z3950_serveur]</option>
		<option value='titre'>$msg[z3950_titre]</option>
	</select>
	<input type='submit' name='submit' class='bouton' value='$msg[z3950_results]'>
</div>
<div class='right'>
	<a href='../../catalog.php?categ=z3950&id_notice=$id_notice' target='_top' >$msg[z3950_autre_rech]</a>&nbsp;&nbsp;
</div>
<div class='row'></div>	
<input type='hidden' name='last_query_id' value='$last_query_id'>
<input type='hidden' name='clause' value='$clause'>
</div></form>
<script type='text/javascript'>document.forms['zform_results'].elements['submit'].focus();</script>
</div>";

?>
