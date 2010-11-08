<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: avis.tpl.php,v 1.3 2009-05-16 10:52:55 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], "tpl.php")) die("no access");

$avis_tpl_header = "<div id='titre-popup'>".$msg[notice_title_avis]."</div>";

$avis_tpl_form = "<center>".$msg[avis_explications]."</center><br />
	<form id='f' name='f' method='post' action='avis.php?todo=save'>
				<div class='row'><label>".$msg[avis_appreciation]."</label>
					<span class='echelle_avis'>
					$msg[avis_note_1]
					<input type='radio' name='note' id='note_1' value='1' />
					<input type='radio' name='note' id='note_2' value='2' />
					<input type='radio' name='note' id='note_3' value='3' checked />
					<input type='radio' name='note' id='note_4' value='4' />
					<input type='radio' name='note' id='note_5' value='5' />
					$msg[avis_note_5]
					</span>
					</div>
		       <input type='hidden' name='noticeid' value='".$noticeid."'>
		       <input type='hidden' name='login' value='".$login."'>

				<div class='row'><label>".$msg[avis_sujet]."</label><br />
					<input type='text' name='sujet' size='50'/>
					</div>

				<div class='row'><label>".$msg[avis_avis]."</label><br />
					<textarea name='commentaire' cols='50' rows='4'></textarea>
					</div>

		      <div class='row'>
		        <input type='submit' class='bouton' name='Submit' value='".$msg[avis_bt_envoyer]."'>
		        <input type='button' class='bouton' value='".$msg[avis_bt_retour]."' onclick='javascript:document.location.href=\"avis.php?todo=liste&noticeid=".$noticeid."\"; return false;'>
		      </div>
		</form>";

$avis_tpl_post_add=	"
	<div align='center'><br /><br />".$msg[avis_msg_validation]."
	<br /><br /><a href='#' onclick='window.close()'>".$msg[avis_fermer]."</a>";

$avis_tpl_post_add_pb="<div align='center'><br /><br />".$msg[avis_msg_pb];

// si paramétrage formulaire particulier
if (file_exists($base_path.'/includes/templates/avis_subst.tpl.php')) require_once($base_path.'/includes/templates/avis_subst.tpl.php'); 

