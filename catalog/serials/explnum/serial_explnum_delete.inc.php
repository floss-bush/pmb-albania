<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id$

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// suppression d'un exemplaire numérique de pério
echo str_replace('!!page_title!!', $msg[4000].$msg[1003].$msg[explnum_doc_associe], $serial_header);

print "<div class=\"row\"><div class=\"msg-perio\">".$msg['catalog_notices_suppression']."</div></div>";

$expl = new explnum($explnum_id);
$expl->delete();

$id_form = md5(microtime());
$retour = "./catalog.php?categ=serials&sub=view&serial_id=$serial_id";
print "
	<form class='form-$current_module' name='dummy' method='post' action='$retour' style='display:none'>
		<input type='hidden' name='id_form' value='$id_form'>
	</form>
	<script type='text/javascript'>document.dummy.submit();</script>
	";


		

