<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: demandes_notes.tpl.php,v 1.3 2010-02-23 16:27:22 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

$form_table_note ="
<script src='./javascript/tablist.js' type='text/javascript'></script>
<script type='text/javascript'>
function confirm_delete()
{
	phrase = \"{$msg[demandes_note_confirm_suppr]}\";
	result = confirm(phrase);
	if(result){
		return true;
	}
	return false;
}
</script>
<form action=\"./demandes.php?categ=notes\" method=\"post\" name=\"modif_notes\" onsubmit=\"if(document.forms['modif_notes'].act.value == 'suppr_note') return confirm_delete();\"> 
	<h3>".htmlentities($msg['demandes_note_liste'], ENT_QUOTES, $charset)."</h3>
	<input type='hidden' name='act' id='act' />
	<input type='hidden' name='idaction' id='idaction' value='!!idaction!!'/>
	<input type='hidden' name='idnote' id='idnote'/>
	<div class='form-contenu'>
		!!liste_notes!!
	</div>
	<div class='row'>
		<input type='submit' class='bouton' value='".$msg['demandes_note_add']."' onclick='this.form.act.value=\"add_note\"'/>
	</div>
</form>
";

$form_modif_note="
<h1>".$msg['demandes_gestion']." : ".$msg['demandes_notes']."</h1>
<script type='text/javascript'>
function confirm_delete()
{
	phrase = \"{$msg[demandes_note_confirm_suppr]}\";
	result = confirm(phrase);
	if(result){
		return true;
	}
	return false;
}
</script>
<h2>!!path!!</h2>
<form class='form-".$current_module."' id='modif_note' name='modif_note' method='post' action=\"./demandes.php?categ=action\">
	<h3>!!form_title!!</h3>
	<input type='hidden' id='act' name='act' />
	<input type='hidden' id='idnote' name='idnote' value='!!idnote!!'/>
	<input type='hidden' id='idaction' name='idaction' value='!!idaction!!'/>
	<div class='form-contenu'>
		<div class='row'>
			<label class='etiquette'>".$msg['demandes_note_date']."</label>
		</div>
		<div class='row'>
			<input type='hidden' id='date_note' name='date_note' value='!!date_note!!' />
			<input type='button' class='bouton' id='date_note_btn' name='date_note_btn' value='!!date_note_btn!!' onClick=\"openPopUp('./select.php?what=calendrier&caller=modif_note&date_caller=!!date_note!!&param1=date_note&param2=date_note_btn&auto_submit=NO&date_anterieure=YES', 'date_note', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\"/>
		</div>
		<div class='row'>
			<label class='etiquette'>".$msg['demandes_note_contenu']."</label>
		</div>
		<div class='row'>
			<textarea id='contenu_note' style='width:99%' name='contenu_note'  rows='15' wrap='virtual'>!!contenu!!</textarea>
		</div>
		<div class='row'>
			<input type='checkbox' name='ck_prive' id='ck_prive' value='1' !!ck_prive!! />
			<label for ='ck_prive' class='etiquette'>".$msg['demandes_note_privacy']."</label>	
		</div>
		<div class='row'>
			<input type='checkbox' name='ck_rapport' id='ck_rapport' value='1' !!ck_rapport!!/>
			<label for='ck_rapport' class='etiquette'>".$msg['demandes_note_rapport']."</label>
		</div>
		<div class='row' !!style!!>
			<label class='etiquette'>".$msg['demandes_note_parente']."</label>
		</div>
		<div class='row' !!style!!>
			<input type='hidden' name='id_note_parent' id='id_note_parent' value='!!id_note_parent!!'/>
			<input type='texte' name='parent_txt' id='parent_txt' class='saisie-50em' readonly='' value='!!parent_text!!' />
			<input type='button' class='bouton' value='X' name='del_parent' id='del_parent' onclick='this.form.parent_txt.value=\"\";this.form.id_note_parent.value=\"0\";'/>
			<input type='button' class='bouton' value='...' name='add_parent' id='add_parent' onclick=\"openPopUp('./select.php?what=notes&caller=modif_note&param1=id_note_parent&param2=parent_txt&idaction=!!idaction!!&current_note=!!idnote!!', 'select_notes', 700, 500, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes')\"/>
		</div>
	</div>
	<div class='row'>
		<div class='left'>
			<input type='button' class='bouton' value='$msg[76]' onClick=\"!!cancel_action!!\" />
			<input type='submit' class='bouton' value='$msg[77]' onClick='this.form.act.value=\"save_note\" ; return test_form(this.form); ' />
		</div>
		<div class='right'>
			!!btn_suppr!!
		</div>
	</div>
	<div class='row'></div>
</form>
<script type='text/javascript'>
function test_form(form) {	

	if((form.contenu_note.value.length == 0)){
		alert(\"$msg[demandes_note_create_ko]\");
		return false;
    } 
    
	return true;
		
}
</script>
";

?>