<?php
// +-------------------------------------------------+
// ? 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: expl.tpl.php,v 1.64 2011-01-25 16:35:19 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

$select_categ_prop = "scrollbars=yes, toolbar=no, dependent=yes, resizable=yes";

//if($pmb_numero_exemplaire_auto>0) $num_exemplaire_test="if(eval(form.option_num_auto.checked == false ))";
if($pmb_numero_exemplaire_auto==1 || $pmb_numero_exemplaire_auto==2) $num_exemplaire_test="var r=false;try { r=form.option_num_auto.checked;} catch(e) {};if(r==false) ";

// permet d'autoriser un no exemplaire vide en mode rfid
if ($pmb_rfid_activate==1 ) {
	$num_exemplaire_rfid_test="if(0)";	
}	
// $expl_new : form pour creation d'un exemplaire
$expl_new = "
<script type='text/javascript'>
<!--
	function test_form(form) {
		$num_exemplaire_rfid_test 
		$num_exemplaire_test 
		if(form.noex.value.length == 0 ) {
				alert(\"$msg[292]\");
				document.forms['addex'].elements['noex'].focus();
				return false;
			}
		return true;
	}
-->
</script>
<form class='form-$current_module' name='addex' method='post' action='./catalog.php?categ=expl_create&id=!!id!!'>
<div class='row'>
	<h3>$msg[290]</h3>
	</div>
<!--	Contenu du form	-->
<div class='form-contenu'>
	<div class='row'>
		!!etiquette!!
		</div>
	<div class='row'>
		!!saisie_num_expl!! 
		</div>
	</div>
<div class='row'>
	!!btn_ajouter!!
	<input type='button' class='bouton' value=' $msg[explnum_ajouter_doc] ' onClick=\"document.location='./catalog.php?categ=explnum_create&id=!!id!!'\" />
	</div>
</form>
<script type='text/javascript'>
	document.forms['addex'].elements['noex'].focus();
</script>
";
 
// script1expl - script2expl
// niveau de test sur le form de saisie code barre exemplaire
// script1expl : on peut saisir des lettres
// script2expl : on ne peut pas saisir des lettres
$script1expl = "
<script type='text/javascript'>
<!--
function test_form(form) {
		if(form.form_cb_expl.value.length == 0) {
				alert(\"$msg[326]\");
				form.form_cb_expl.focus();
				return false;
			}
		form.form_cb_expl.value = form.form_cb_expl.value.replace(/ /g, '');
		return true;
	}
-->
</script>
";
$script2expl = "
<script type='text/javascript'>
<!--
function test_form(form) {
	if(form.form_cb_expl.value.length == 0) {
				alert(\"$msg[326]\");
				form.form_cb_expl.focus();
				return false;
			}
		return true;
	}
-->
</script>
";

GLOBAL $pmb_antivol;
if($pmb_antivol>0) {
$antivol_form="
	<div class='colonne3'>
		<!-- type antivol -->
		<label class='etiquette' for='f_ex_type_antivol'>$msg[type_antivol]</label>
		<div class='row'>
			!!type_antivol!!
			</div>
		</div>
";
}
else $antivol_form="";
		
if ($pmb_rfid_activate==1 && $pmb_rfid_serveur_url ) {

	if($pmb_rfid_driver=="ident")  $script_erase="init_rfid_erase(rfid_ack_erase);";
	else $script_erase="rfid_ack_erase(1);";

	$rfid_script_catalog="
		$rfid_js_header
		<script type='text/javascript'>
			var flag_cb_rfid=0;
			flag_program_rfid_ask=0;
			init_rfid_read_cb(0,f_expl);
			nb_part_readed=0;
			function f_expl(cb) {
				nb_part_readed=cb.length;
				if(flag_program_rfid_ask==1) {
					program_rfid();
					flag_cb_rfid=0; 
					return;
				}
				if(cb.length==0) {
					flag_cb_rfid=1;
					return;
				} 
				if(!cb[0]) {
					flag_cb_rfid=0; 
					return;
				}
				if(document.getElementById('f_ex_cb').value	== cb[0]) flag_cb_rfid=1;
				else  flag_cb_rfid=0;
				if(document.getElementById('f_ex_cb').value	== '') {	
					flag_cb_rfid=0;				
					document.getElementById('f_ex_cb').value=cb[0];
				}
			}

			function script_rfid_encode() {
				if(!flag_cb_rfid && flag_rfid_active) {
				    var confirmed = confirm(\"".addslashes($msg['rfid_programmation_confirmation'])."\");
				    if (confirmed) {
						return false;
				    } 
				}
			}
			
			function program_rfid_ask() {
				if (flag_semaphore_rfid_read==1) {
					flag_program_rfid_ask=1;
				} else {
					program_rfid();
				}
			}

			function program_rfid() {
				flag_semaphore_rfid=1;
				flag_program_rfid_ask=0; 
				var nbparts = document.getElementById('f_ex_nbparts').value;	
				if(nb_part_readed!= nbparts) {
					flag_semaphore_rfid=0;
					alert(\"".addslashes($msg['rfid_programmation_nbpart_error'])."\");
					return;
				}
				$script_erase
			}
			
			function rfid_ack_erase(ack) {
				var cb = document.getElementById('f_ex_cb').value;
				var nbparts = document.getElementById('f_ex_nbparts').value;	
				if(!nbparts)nbparts=1;
				init_rfid_write_etiquette(cb,nbparts,rfid_ack_write);
				
			}

			function rfid_ack_write(ack) {				
				init_rfid_antivol_all(1,rfid_ack_antivol_actif);				
			}
			
			function rfid_ack_antivol_actif(ack) {
				alert (\"".addslashes($msg['rfid_etiquette_programmee_message'])."\");
				flag_semaphore_rfid=0;
			}			

		</script>
";
	$rfid_program_button="<input  type=button class='bouton' value=' ". $msg['rfid_configure_etiquette_button']." ' onClick=\"program_rfid_ask();\" />";	
}else {	
	$rfid_script_catalog="";
	$rfid_program_button="";
}

//Création de la cote en ajax
if($pmb_prefill_cote_ajax){
	$ajax_expl_cote = "
		<script type='text/javascript' src='./javascript/ajax.js' ></script>
		<script>ajax_parse_dom();</script>
	";
} else $ajax_expl_cote ="";

// $expl_form :form de saisie/modif exemplaire
$expl_form =jscript_unload_question();
$expl_form.="
$rfid_script_catalog
<script type='text/javascript'>
<!--
	function test_form(form) {
		!!questionrfid!!
		if((form.f_ex_cb.value.length == 0) || (form.f_ex_cote.value.length == 0)) {
			alert(\"$msg[304]\");
			return false;
		}
		unload_off();
		return check_form();
	}
	function calcule_section(selectBox) {
		for (i=0; i<selectBox.options.length; i++) {
			id=selectBox.options[i].value;
		    list=document.getElementById(\"docloc_section\"+id);
		    list.style.display=\"none\";
			}
	
		id=selectBox.options[selectBox.selectedIndex].value;
		list=document.getElementById(\"docloc_section\"+id);
		list.style.display=\"block\";
		}
-->
</script>
<form class='form-$current_module' name='expl' method='post' action='!!action!!' >
<h3>$msg[300]</h3>
<div class='form-contenu' >
<div class='row'>
	<!-- code barre -->
	<label class='etiquette' for='f_ex_cb'>$msg[291]</label>
	<div class='row'>
			<input type='text' class='saisie-20emr' id=\"f_ex_cb\" value='!!cb!!' name='f_ex_cb' readonly='readonly' />
			<input type=button class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./catalog/expl/setcb.php', 'ex_getcb', 220, 100, -2, -2, 'toolbar=no')\" />
	</div>
</div>
<div class='row'>
	<div class='colonne3'>
		<!-- cote -->
			<label class='etiquette' for='f_ex_cote'>$msg[296]</label>
		<div class='row'>
			<input type='text' class='saisie-20em' id=\"f_ex_cote\" name='f_ex_cote' value='!!cote!!' !!expl_ajax_cote!!/>
			</div>
		</div>
	<div class='colonne3'>
		<!-- type document -->
		<label class='etiquette' for='f_ex_typdoc'>$msg[294]</label>
		<div class='row'>
			!!type_doc!!
			</div>
		</div>
	<div class='colonne3'>
		<!-- Nombre de parties -->
		<label class='etiquette' for='f_ex_nbparts'>".$msg["expl_nbparts"]."</label>
		<div class='row'>
			<input type='text' class='saisie-5em' id=\"f_ex_nbparts\" value='!!nbparts!!' name='f_ex_nbparts' />
			</div>
		</div>
	</div>
<div class='row'>
	<div class='colonne3'>
		<!-- localisation -->
		<label class='etiquette' for='f_ex_location'>$msg[298]</label>
		<div class='row'>
			!!localisation!!
			</div>
		</div>

	<div class='colonne3'>
		<!-- section -->
		<label class='etiquette' for='f_ex_section'>$msg[295]</label>
		<div class='row'>
			!!section!!
			</div>
		</div>

	<div class='colonne3'>
		<!-- proprietaire -->
		<label class='etiquette' for='f_ex_owner'>$msg[651]</label> 
		<div class='row'>
			!!owner!!
			</div>
		</div>
	</div>
<div class='row'>
	<div class='colonne3'>
		<!-- statut -->
		<label class='etiquette' for='f_ex_statut'>$msg[297]</label>
		<div class='row'>
			!!statut!!
			</div>
		</div>
	<div class='colonne3'>
		<!-- code stat -->
		<label class='etiquette' for='f_ex_cstat'>$msg[299]</label>
		<div class='row'>
			!!codestat!!
			</div>
		</div>
	".$antivol_form."
	</div>

<!-- notes -->
<div class='row'>
	<div class='colonne2'>
		<label class='etiquette' for='f_ex_note'>$msg[expl_message]</label>
	</div>
	<div class='colonne2'><!-- msg_exp_cre_date --></div>
</div>
<div class='row'>
	<div class='colonne2'>
		<textarea name='f_ex_note' id='f_ex_note' class='saisie-80em'>!!note!!</textarea>
	</div>
	<div class='colonne2'><!-- exp_cre_date --></div>
</div>
<div class='row'>
	<div class='colonne2'>
		<label class='etiquette' for='f_ex_comment'>$msg[expl_zone_comment]</label>
	</div>
	<div class='colonne2'><!-- msg_exp_upd_date --></div>
</div>
<div class='row'>
	<div class='colonne2'>
		<textarea name='f_ex_comment' id='f_ex_comment' class='saisie-80em'>!!comment!!</textarea>
	</div>
	<div class='colonne2'><!-- exp_upd_date --></div>
</div>

<!-- prix -->
<div class='row'>
	<label class='etiquette' for='f_ex_prix'>$msg[4050]</label>
	</div>
<div class='row'>
	<input type='text' class='text' name='f_ex_prix' id='f_ex_prix' value=\"!!prix!!\" />
</div>
<div class='row'></div>
!!champs_perso!!
<div class='row'></div>
</div>
<div class='row'>
	<div class='left'>
		<input type='button' class='bouton' value=' $msg[76] ' onClick=\"unload_off();history.go(-1);\" />
		!!modifier!!
		$rfid_program_button
		!!link_audit!!
		</div>
	<div class='right'>
		!!supprimer!!
		</div>
	</div>
<div class='row'></div>
</form>
<script type=\"text/javascript\">document.forms['expl'].elements['f_ex_cote'].focus();</script>
$ajax_expl_cote
";

// template pour le form de saisie du message
$expl_msg_form = "
<h1>$msg[377]</h1>
<form class='form-$current_module' name='msg_form' method='post' action='./circ.php?categ=note_ex&cb=".rawurlencode(stripslashes($cb))."&id=$id&action=submit'>
<div class='row'>
	<h3>!!notice!!</h3>
	</div>
<div class='row'>
	<b>$msg[232] : <strong>".stripslashes($cb)."</strong></b>
	</div>
<div class='form-contenu'>
	<!-- notes -->
	<div class='row'>
		<label class='etiquette' for='message_content'>$msg[expl_message]</label>
		</div>
	<div class='row'>
		<textarea name='message_content' id='message_content' class='saisie-80em'>!!message!!</textarea>
		</div>
	<div class='row'>
		<label class='etiquette' for='f_ex_comment'>$msg[expl_zone_comment]</label>
		</div>
	<div class='row'>
		<textarea name='f_ex_comment' id='f_ex_comment' class='saisie-80em'>!!comment!!</textarea>
		</div>
</div>
<div class='row'>
	<input class='bouton' type='button' value='$msg[76]' onClick=\"document.location='./circ.php?categ=visu_ex&form_cb_expl=".$cb."'\" />
	<input class='bouton' type='submit' value='$msg[77]' />
</div>
</form>
<script type='text/javascript'>
<!--
	document.forms['msg_form'].elements['message_content'].focus();	
-->
</script> 
";

//	$expl_pointage_base : ecran de pointage des exemplaires apres import
$expl_pointage_base = "
<script type='text/javascript'>
<!--
	function test_noex(soumission) {
		if (soumission == '1') {
			if(document.forms['pointage_expl'].noex.value.length == 0) {
				alert(\"".$msg[292]."\");
				document.forms['pointage_expl'].noex.focus();
				return false;
				}
			}
		document.forms['pointage_expl'].noex.value = document.forms['pointage_expl'].noex.value.replace(/ /g, '');
		document.forms['pointage_expl'].submit();
		return false;
		}
-->
</script>
<form class='form-$current_module' name='pointage_expl' method='post' action='./pointage_expl.php?categ=import&sub=pointage_expl'>
<h3>$msg[569]</h3>
<!--	Contenu du form	-->
<div class='form-contenu'>

	<div class='row'>
		<div class='colonne4'>
			<!-- CB -->
			<label class='etiquette' for='f_ex_statut'>$msg[291]</label>
			<div class='row'>
				<input type='text' class='saisie-20em' name='noex' value=''>
				</div>
		</div>
	
		<div class='colonne4'>
			<!-- statut -->
			<label class='etiquette' for='f_ex_stat'>$msg[297]</label>
			<div class='row'>
				!!book_statut_id!!
			</div>
		</div>
		
		<div class='colonne4'>
			<!-- section -->
			<label class='etiquette' for='f_ex_section'>$msg[295]</label>
			<div class='row'>
				!!book_section_id!!
			</div>
		</div>
		
		<div class='colonne_suite'>
			<!-- localisation -->
			<label class='etiquette' for='f_ex_location'>$msg[298]</label>
			<div class='row'>
				!!book_location_id!!
			</div>
		</div>
	</div>
	<div class='row'>
		<div class='colonne4'>
			<div class='row'>
				&nbsp;
			</div>
		</div>
	
		<div class='colonne4'>
			<!-- typdoc=support -->
			<label class='etiquette' for='f_ex_typdoc'>$msg[294]</label>
			<div class='row'>
				!!book_doctype_id!! 
			</div>
		</div>
			
		<div class='colonne4'>
			<!-- codestat -->
			<label class='etiquette' for='f_ex_cstat'>$msg[299]</label>
			<div class='row'>
				!!book_codestat_id!! 
			</div>
		</div>
			
		<div class='colonne_suite'>
			<!-- owner -->
			<label class='etiquette' for='f_ex_owner'>$msg[651]</label>
			<div class='row'>
				!!book_lender_id!! 
			</div>
		</div>
	</div>
	<div class='row'> </div>

</div>	
<input type='submit' class='bouton' value=' ".$msg[89]." ' onClick=\"return test_noex('1')\" />
<input type='hidden' name='action' value='pointage' />
<script type='text/javascript'>
	document.forms['pointage_expl'].elements['noex'].focus();
	</script>
<div class='row'>
	!!explencoursdevalidation!!
	</div>
</form>
";

//	$expl_pointage : ecran de pointage des exemplaires apres import
$expl_pointage = "
<hr />
!!notice!!
<hr />
<table width='100%'>
		<tr>
			<td>
			</td>
			<td>
			</td>
			<td> $msg[563]
			</td>
		</tr>
		<tr>
			<td>
				$msg[296] <!-- cote -->
			</td>
			<td><b>
				!!cote!! </b>
			<td>
			</td>
		</tr>
		<tr>
			<td>
				$msg[294] <!-- type doc -->
			</td>
			<td>
				!!type_doc!!
			</td>
			<td>
			<font color='red' size='-1'> !!nouveau_support!! </font>
			</td>
		</tr>
		<tr>
			<td>
				$msg[295] <!-- section -->
			</td>
			<td>
				!!section!!
			</td>
			<td>
				 <font color='red' size='-1'> !!nouvelle_section!! </font>
			</td>
		</tr>
		<tr>
			<td>
				$msg[297] <!-- statut -->
			</td>
			<td>
				!!statut!!
			</td>
			<td>
				 <font color='red' size='-1'> !!nouveau_statut!! </font>
			</td>
		</tr>
		<tr>
			<td>
				$msg[298] <!-- localisation -->
			</td>
			<td>
				!!localisation!!
			</td>
			<td>
				<font color='red' size='-1'> !!nouvelle_location!! </font>
			</td>
		</tr>
		<tr>
			<td>
				$msg[651] <!-- owner -->
			</td>
			<td>
				!!owner!!
			</td>
			<td>
				<font color='red' size='-1'> !!nouveau_proprio!! </font>
			</td>
		</tr>
		<tr>
			<td>
				$msg[299] <!-- code stat -->
			</td>
			<td>
				!!codestat!!
			</td>
			<td>
				<font color='red' size='-1'> !!nouveau_codestat!! </font>
			</td>
		</tr>
		<tr>
			<td valign='top'>
				$msg[expl_message] <!-- zone de notes -->
			</td>
			<td colspan='2' >
				<textarea name='expl_note' cols='35'>!!note!!</textarea>
				<input type='hidden' name='noex_valide' value='!!noex_valide!!' />
			</td>
		</tr>
		<tr>
			<td valign='top'>
				$msg[expl_zone_comment] <!-- zone de commentaire non bloquant -->
			</td>
			<td colspan='2' >
				<textarea name='expl_comment' cols='35'>!!comment!!</textarea>
			</td>
		</tr>
</table>
<input type='button' class='bouton' value=' $msg[76] ' onClick=\"document.location='!!annuler_action!!';\" />
<input type='submit' class='bouton' value=' $msg[77] ' onClick=\"return test_noex('0')\" />
";


if ($pmb_rfid_activate==1 && $pmb_rfid_serveur_url ) {	
	// $expl_cb_tmpl : template pour le form de saisie code-barre
	$expl_cb_tmpl = "
	!!script!!
	$rfid_js_header
	<script type='text/javascript'>
		var cb_lu =new Array();	
		init_rfid_read_cb(0,f_expl);
		
		function f_expl(cb) {
			// il y a une ou plusieurs étiquette rfid
			if( cb.length>0) {	
				if (document.getElementById('expl_cb').value != cb[0]) {	
					document.getElementById('form_cb_expl').value=cb[0];
					document.saisie_cb_ex.submit();
				}
			} 
		}
	</script>
	<h1>!!title!!</h1>
	<form class='form-$current_module' name='saisie_cb_ex' method='post' action='!!form_action!!' onSubmit='return test_form(this)'>
	<h3>!!titre_formulaire!!</h3>
	<div class='form-contenu'>
		<div class='row'>
			<label class='etiquette' for='form_cb_expl'>!!message!!</label>
			</div>
		<div class='row'>
			<input class='saisie-20em' type='text' id='form_cb_expl' name='form_cb_expl' value=''  />
			<input type='hidden' id='expl_cb' name='expl_cb' value='!!expl_cb!!' />
			<input type='hidden' id='transfert_id_resa' name='transfert_id_resa' value='' />
			</div>
		</div>
	<div class='row'>
		<input type='submit' class='bouton' value='$msg[502]' />
		</div>
	</form>
	<script type='text/javascript'>
	document.forms['saisie_cb_ex'].elements['form_cb_expl'].focus();
	</script>
	";	
	
} else {
	// $expl_cb_tmpl : template pour le form de saisie code-barre
	$expl_cb_tmpl = "
	!!script!!
	<h1>!!title!!</h1>
	<form class='form-$current_module' name='saisie_cb_ex' method='post' action='!!form_action!!' onSubmit='return test_form(this)'>
	<h3>!!titre_formulaire!!</h3>
	<div class='form-contenu'>
		<div class='row'>
			<label class='etiquette' for='form_cb_expl'>!!message!!</label>
			</div>
		<div class='row'>
			<input class='saisie-20em' type='text' id='form_cb_expl' name='form_cb_expl' value=''  />
				<input type='hidden' id='transfert_id_resa' name='transfert_id_resa' value='' />
			</div>
		</div>
	<div class='row'>
		<input type='submit' class='bouton' value='$msg[502]' />
		</div>
	</form>
	<script type='text/javascript'>
	document.forms['saisie_cb_ex'].elements['form_cb_expl'].focus();
	</script>
	";
}

if ($pmb_rfid_activate==1 && $pmb_rfid_serveur_url ) {	
	// $expl_cb_tmpl : template pour le form de saisie code-barre
	$expl_cb_tmpl_recep = "
	!!script!!
	$rfid_js_header
	<script type='text/javascript'>
		var cb_lu =new Array();	
		init_rfid_read_cb(0,f_expl);
		
		function f_expl(cb) {
			// il y a une ou plusieurs étiquette rfid
			if( cb.length>0) {					
				if (document.getElementById('expl_cb').value != cb[0]) {	
					document.getElementById('form_cb_expl').value=cb[0];
					document.saisie_cb_ex.submit();
				}
			} 
		}
	</script>
	<h1>!!title!!</h1>
	<form class='form-$current_module' name='saisie_cb_ex' method='post' action='!!form_action!!' onSubmit='return test_form(this)'>
	<h3>!!titre_formulaire!!</h3>
	<div class='form-contenu'>
		<div class='row'>
			<label class='etiquette' for='form_cb_expl'>!!message!!</label>
		</div>
		<div class='row'>
			<input class='saisie-20em' type='text' id='form_cb_expl' name='form_cb_expl' value=''  />
			<input type='hidden' id='expl_cb' name='expl_cb' value='!!expl_cb!!' />
		</div>
		<div class='row'>
			<div class='left'>
				<div class='row'>
					<label class='etiquette' for='statut_reception'>".$msg["transferts_circ_reception_lbl_statuts"]."</label>
				</div>
				<div class='row'>
					<select name='statut_reception'>!!liste_statuts!!</select>
				</div>
			</div>
			<div class='left'>&nbsp;&nbsp;</div>
			<div class='left'>
				<div class='row'>
					<label class='etiquette' for='section_reception'>".$msg["transferts_circ_reception_lbl_sections"]."</label>
				</div>
				<div class='row'>
					<select name='section_reception'>!!liste_sections!!</select>
				</div>
			</div>
		</div>
		<div class='row'></div>
	</div>
	<div class='row'>
		<input type='submit' class='bouton' value='$msg[502]' />
		</div>
	</form>
	<script type='text/javascript'>
	document.forms['saisie_cb_ex'].elements['form_cb_expl'].focus();
	</script>
	";	
	
} else {
	// $expl_cb_tmpl : template pour le form de saisie code-barre
	$expl_cb_tmpl_recep = "
	!!script!!
	<h1>!!title!!</h1>
	<form class='form-$current_module' name='saisie_cb_ex' method='post' action='!!form_action!!' onSubmit='return test_form(this)'>
	<h3>!!titre_formulaire!!</h3>
	<div class='form-contenu'>
		<div class='row'>
			<label class='etiquette' for='form_cb_expl'>!!message!!</label>
		</div>
		<div class='row'>
			<input class='saisie-20em' type='text' id='form_cb_expl' name='form_cb_expl' value=''  />
		</div>
		<div class='row'>
			<div class='left'>
				<div class='row'>
					<label class='etiquette' for='statut_reception'>".$msg["transferts_circ_reception_lbl_statuts"]."</label>
				</div>
				<div class='row'>
					<select name='statut_reception'>!!liste_statuts!!</select>
				</div>
			</div>
			<div class='left'>&nbsp;&nbsp;</div>
			<div class='left'>
				<div class='row'>
					<label class='etiquette' for='section_reception'>".$msg["transferts_circ_reception_lbl_sections"]."</label>
				</div>
				<div class='row'>
					<select name='section_reception'>!!liste_sections!!</select>
				</div>
			</div>
		</div>
		<div class='row'></div>
	</div>
	<div class='row'>
		<input type='submit' class='bouton' value='$msg[502]' />
	</div>
	</form>
	<script type='text/javascript'>
	document.forms['saisie_cb_ex'].elements['form_cb_expl'].focus();
	</script>
	";
}


if ($pmb_rfid_activate==1  ) {	
	if(!$pmb_rfid_serveur_url)$pmb_rfid_antivol_activate=0;
	else $pmb_rfid_antivol_activate=1;
	$expl_cb_retour_tmpl = "
		!!script!!
		$rfid_js_header
		<script src='./javascript/rfid/rfid_pret.js'></script>
		<script type='text/javascript'>
			function retour_manuel() {
				var cb=new Array(); 
				cb[0]=document.getElementById('form_cb_expl').value;
				document.getElementById('form_cb_expl').value='';
				document.getElementById('form_cb_expl').focus();
				flag_antivol_retour=0;
				read_retour(cb);				
			}

		</script>
		
		<h1>!!title!!</h1>
			<form class='form-retour-expl' name='saisie_cb_ex' method='post' onSubmit=\"retour_manuel();return false\">
		<!--
			<h3>!!titre_formulaire!!</h3>
			<div class='form-contenu'>
		-->
		<div class='row'>
			<label class='etiquette' for='form_cb_expl'>!!message!!</label>
		</div>
		<div class='row'>
			<input class='saisie-20em' type='text' id='form_cb_expl' name='form_cb_expl' value=''  />
			&nbsp;&nbsp;
			<input type='button' class='bouton' value='$msg[502]' onClick=\"retour_manuel();\"/>
		</div>
		</form>
		<div class='row'>
			<table id='table_retour_tmp' name='table_retour_tmp'>
			</table>
		</div>
		<script type='text/javascript'>
			document.forms['saisie_cb_ex'].elements['form_cb_expl'].focus();";
		if ($pmb_rfid_serveur_url) {	
			$expl_cb_retour_tmpl.= "
			init_rfid_retour();
			</script>
			";
		} else {
			$expl_cb_retour_tmpl.= "
			init_sans_rfid_retour();
			</script>
			";				
		}
} else
	$expl_cb_retour_tmpl = "
		!!script!!
		<h1>!!title!!</h1>
		<form class='form-retour-expl' name='saisie_cb_ex' method='post' action='!!form_action!!' onSubmit='return test_form(this)'>
		<!--
			<h3>!!titre_formulaire!!</h3>
			<div class='form-contenu'>
		-->
		<div class='row'>
			<label class='etiquette' for='form_cb_expl'>!!message!!</label>
		</div>
		<div class='row'>
			<input class='saisie-20em' type='text' id='form_cb_expl' name='form_cb_expl' value=''  />
			&nbsp;&nbsp;
			<input type='submit' class='bouton' value='$msg[502]' />
		</div>
		<!--
			</div>
			<div class='row'>
			<input type='submit' class='bouton' value='$msg[502]' />
			</div>
		-->
		</form>
		<script type='text/javascript'>
			document.forms['saisie_cb_ex'].elements['form_cb_expl'].focus();
		</script>
		";



//	$expl_pret :form d'affichage exemplaire pr?t?
$expl_pret ="
<table class='expl-form' align='center' cellspacing='3px'>
		<tr>
			<td colspan='2' class='listheader'>
				$msg[300]
			</td>
		</tr>
		<tr>
			<td align='right'>
				$msg[233] <!-- Titre -->
			</td>
			<td align='left'>
				!!titre!!
			</td>
		</tr>
		<tr>
			<td align='right'>
				$msg[234] <!-- Auteur -->
			</td>
			<td align='left'>
				!!auteur!!
			</td>
		</tr>
		<tr>
			<td align='right'>
				$msg[66] <!-- code barre -->
			</td>
			<td align='left'>
				!!cb!!
			</td>
		</tr>
		<tr>
			<td align='right'>
				$msg[296] <!-- cote -->
			</td>
			<td align='left'>
				!!cote!!
			</td>
		</tr>
		<tr>
			<td align='right'>
				$msg[294] <!-- type doc -->
			</td>
			<td align='left'>
				!!type_doc!!
			</td>
		</tr>
		<tr>
			<td align='right'>
				$msg[295] <!-- section -->
			</td>
			<td align='left'>
				!!section!!
			</td>
		</tr>
		<tr>
			<td align='right'>
				$msg[298] <!-- localisation -->
			</td>
			<td align='left'>
				!!localisation!!
			</td>
		</tr>
		<tr>
			<td align='right'>
				$msg[651] <!-- owner -->
			</td>
			<td align='left'>
				!!owner!!
			</td>
		</tr>
		<tr>
			<td align='right' valign='top'>
				$msg[expl_message] <!-- zone de notes -->
			</td>
			<td align='left'>
				!!note!!
			</td>
		</tr>
		<tr>
			<td align='right' valign='top'>
				$msg[expl_zone_comment] <!-- zone de commentaire non bloquant -->
			</td>
			<td align='left'>
				!!comment!!
			</td>
		</tr>
		<tr>
			<td colspan='2'>
						<table border='0' width='100%' cellspacing='0'>
							<tr>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td class='listheader'>
									$msg[349]
								</td>
							</tr>
							<tr>
								<td>
									!!pret_list!!
								</td>
							</tr>
						</table>
					</td>
		</tr>
	</table>
";

$expl_view_form="
<table class='expl-form' align='center' cellspacing='3px'>
		<tr>
			<td align='right'>
				<label class='etiquette'>$msg[296]</label> <!-- cote -->
			</td>
			<td align='left'>
				!!cote!!
			</td>
			<td align='right'>
				<label class='etiquette'>$msg[294]</label>  <!-- support -->
			</td>
			<td align='left'>
				!!type_doc!!
			</td>			
		</tr>
		<tr>
			<td align='right'>
				<label class='etiquette'>$msg[298]</label>  <!-- localisation -->
			</td>
			<td align='left'>
				!!localisation!!
			</td>
			<td align='right'>
				<label class='etiquette'>$msg[295]</label>  <!-- section -->
			</td>
			<td align='left'>
				!!section!!
			</td>			
		</tr>
		<tr>
			<td align='right'>
				<label class='etiquette'>$msg[651]</label>  <!-- owner -->
			</td>
			<td align='left'>
				!!owner!!
			</td>
			<td align='right'>
				<label class='etiquette'>$msg[297]</label>  <!-- statut -->
			</td>
			<td align='left'>
				!!statut!!
			</td>			
		</tr>
		<tr>
			<td align='right'>
				<label class='etiquette'>$msg[299]</label>  <!-- code statistique -->
			</td>
			<td align='left'>
				!!codestat!!
			</td>						
			<td align='right' valign='top'>
				<label class='etiquette'>".$msg['expl_message']."</label>  <!-- zone de notes -->
			</td>
			<td align='left'>
				!!note!!
			</td>
		</tr>
		!!champs_perso!!
	</table>
	<hr />
";
