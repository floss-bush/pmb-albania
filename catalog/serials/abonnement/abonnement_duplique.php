<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: abonnement_duplique.php,v 1.1 2007-07-06 15:42:44 ngantier Exp $

// définition du minimum nécéssaire
$base_path="./../../..";
$base_auth = "CATALOGAGE_AUTH";
$base_title = "\$msg[6]";
require_once ("$base_path/includes/init.inc.php");

$templates = <<<ENDOFFILE
			<script type='text/javascript'>
				function Fermer() {
				 	
				 	parent.kill_frame_periodique();				 	
				}				
			</script>
<div style='width: 90%;'>
	<div id="bouton_fermer_notice_preview" class="right"><a href='#' onClick='parent.kill_frame_periodique();return false;'>X</a></div>
	!!form!!
</div>						
ENDOFFILE;

$form="<form class='form-$current_module' id='form_modele' name='form_modele' method='post' target='_parent' action='$base_path/catalog.php?categ=serials&sub=abon&abt_id=$abonnement_id&serial_id=$serial_id'>
	<div class='row'  ALIGN='center'>".$msg["abonnements_titre_nombre_duplication"]."</div>			
	<div class='row'>
	&nbsp;
	</div>
	<div class='row'   ALIGN='center'>
		<input type='text' size='4' name='nb_duplication' id='nb_duplication' value='1'/>	
	</div>	
	<div class='row'>
	&nbsp;
	</div>
	<div class='row'   ALIGN='center'>
		<input type='hidden' id='act' name='act' value='' />		
		<input class='bouton_small' value='".$msg["77"]."' onclick=\"document.getElementById('act').value='copy';this.form.submit();\" type='submit'>
		
	</div>
	</form>";

print str_replace("!!form!!",$form,$templates);
print "</body></html>"
?>