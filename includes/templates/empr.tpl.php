<?php
// +-------------------------------------------------+
// ? 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: empr.tpl.php,v 1.128.2.3 2011-05-12 13:45:23 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

// templates pour les forms emprunteurs
//   ----------------------------------
$select1_prop = "scrollbars=yes, toolbar=no, dependent=yes, resizable=yes";

// template pour la liste emprunteurs
$empr_list_tmpl = "
<h1>$msg[57] \"<strong>!!cle!!</strong>\" !!where_intitule!! <!--!!nb_total!!--></h1>
!!filters_list!!

!!list!!

<div class='row'>
!!nav_bar!!
</div>
";
// -----------------------------------

// script1 - script2
// niveau de test sur le form de saisie cl? emprunteur
// script0 : aucun test
// script1 : on peut saisir des lettres
// script2 : on ne peut pas saisir des lettres
$script0 = "
<script type='text/javascript'>
<!--
function test_form(form)
	{
		return true;
	}
-->
</script>
";
if ($pmb_rfid_activate==1 ) {
	$num_empr_rfid_test="if(0)";	
} else 	{
	$num_empr_rfid_test='';
}	
$script1 = "
<script type='text/javascript'>
<!--
function test_form(form)
	{
		$num_empr_rfid_test
		if(form.form_cb.value.length == 0)
			{
				alert(\"$msg[326]\");
				form.form_cb.focus();
				return false;
			}
		return true;
	}
-->
</script>
";
$script2 = "
<script type='text/javascript'>
<!--
function test_form(form)
	{
		if(form.form_cb.value.length == 0)
			{
				alert(\"$msg[326]\");
				form.form_cb.focus();
				return false;
			}
		var exp = new RegExp('[a-zA-Z]','g');
		if(exp.test(form.form_cb.value))
			{
				alert(\"$msg[327]\");
				form.form_cb.value = '';
				form.form_cb.focus();
				return false;
			}
		return true;
	}
-->
</script>
";

// $empr_cb_tmpl : template pour le form de saisie code-barre en recherche
if ($pmb_rfid_activate==1 && $pmb_rfid_serveur_url ) {

$empr_cb_tmpl = "
<script type='text/javascript'>
<!--
function aide_search_empr()
	{
		var fenetreAide;
		var prop = 'scrollbars=yes, resizable=yes';
		fenetreAide = openPopUp('./help.php?whatis=search_empr', 'regex_howto', 500, 400, -2,- 2, prop);
	}
function test_form(form) {
	if (form.form_cb.value.length == 0) {
		form.form_cb.value='*';
		}
	return true;
	}

-->

</script>
$rfid_js_header
!!script!!

<h1>!!title!!</h1>
<form class='form-$current_module' id='saisie_cb_ex' name='saisie_cb_ex' method='post' action='!!form_action!!' onSubmit='return test_form(this)'>
<h3>!!titre_formulaire!!</h3>
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_cb'>!!message!!</label>
		</div>
	<div class='row'>
		<input class='saisie-20em' id='form_cb' type='text' name='form_cb' value='!!cb_initial!!' title='$msg[3000]' /> !!restrict_location!!
		</div>
	</div>
<div class='row'>
	<input type='submit' class='bouton' value='$msg[502]'/>
	</div>
</form>
<script type='text/javascript'>
document.forms['saisie_cb_ex'].elements['form_cb'].focus();
	init_rfid_empr();
</script>";
} else
$empr_cb_tmpl = "
<script type='text/javascript'>
<!--
function aide_search_empr()
	{
		var fenetreAide;
		var prop = 'scrollbars=yes, resizable=yes';
		fenetreAide = openPopUp('./help.php?whatis=search_empr', 'regex_howto', 500, 400, -2 ,-2, prop);
	}
function test_form(form) {
	if (form.form_cb.value.length == 0) {
		form.form_cb.value='*';
		}
	return true;
	}
-->
</script>
!!script!!

<h1>!!title!!</h1>
<form class='form-$current_module' id='saisie_cb_ex' name='saisie_cb_ex' method='post' action='!!form_action!!' onSubmit='return test_form(this)'>
<h3>!!titre_formulaire!!</h3>
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_cb'>!!message!!</label>
		</div>
	<div class='row'>
		<input class='saisie-20em' id='form_cb' type='text' name='form_cb' value='!!cb_initial!!' title='$msg[3000]' /> !!restrict_location!!
		</div>
	</div>
<div class='row'>
	<input type='submit' class='bouton' value='$msg[502]'/>
	</div>
</form>
<script type='text/javascript'>
document.forms['saisie_cb_ex'].elements['form_cb'].focus();
</script>";

// $login_empr_pret_tmpl : template pour le form de saisie login/password en mode circ restreint
$login_empr_pret_tmpl = "
<script type='text/javascript'>
<!--
function test_form(form) {
	if (form.form_login.value.length == 0 || form.form_password.value.length == 0) {
		return false;
		}
	return true;
	}
-->
</script>
<h1>!!title!!</h1>
<form class='form-$current_module' id='saisie_empr_login_password' name='saisie_empr_login_password' method='post' action='!!form_action!!' onSubmit='return test_form(this)'>
<h3>!!titre_formulaire!!</h3>
<div class='form-contenu'>
<div class='colonne3'>
	<div class='row'>
		<label class='etiquette' for='form_login'>".$msg[empr_login]."</label>
		</div>
	<div class='row'>
		<input class='saisie-20em' id='form_login' type='text' name='form_login' value='' title=\"".$msg[empr_login]."\" />
		</div>
	</div>
<div class='colonne_suite'>
	<div class='row'>
		<label class='etiquette' for='form_password'>".$msg[empr_password]."</label>
		</div>
	<div class='row'>
		<input class='saisie-20em' id='form_password' type='password' name='form_password' value='' title=\"".$msg[empr_password]."\" />
		</div>
	</div>
<div class='row'>&nbsp;</div>
</div>

<div class='row'>
	<input type='submit' class='bouton' value='$msg[502]'/>
	</div>
</form>
<script type='text/javascript'>
document.forms['saisie_empr_login_password'].elements['form_login'].focus();
</script>";

// $empr_cb_tmpl_create : template pour le form de saisie code-barre en création
$empr_cb_tmpl_create = "
!!script!!
<h1>!!title!!</h1>
<form class='form-$current_module' id='saisie_cb_ex' name='saisie_cb_ex' method='post' action='!!form_action!!' onSubmit='return test_form(this)'>
<h3>!!titre_formulaire!!</h3>
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_cb'>!!message!!</label>
	</div>
	<div class='row'>
		<input class='saisie-20em' id='form_cb' type='text' name='form_cb' value='!!cb_initial!!'  />
	</div>
</div>
<div class='row'>
	<input type='submit' class='bouton' value='$msg[502]' />
	</div>
</form>
<script type='text/javascript'>
document.forms['saisie_cb_ex'].elements['form_cb'].focus();
</script>
";

// $PDF_win_prop : propriétés pour la fen?tre d'impression PDF fiche lecteur
$PDF_win_prop = "toolbar=no, dependent=yes, resizable=yes";

if ($pmb_rfid_activate==1 ) {
	if(!$rfid_port) $rfid_port= get_rfid_port();
	if($pmb_rfid_serveur_url) {
		$indicateur_rfid="<img src='./images/sauv_succeed.png' id='indicateur' align='top' ><span  class='erreur' id='indicateur_nb_doc'></span>";
	}	
	if( $pmb_rfid_serveur_url){
		$script_rfid_antivol="
		
		<script language=\"JavaScript\">
			init_rfid_pret(!!id!!,'!!cb!!',$pmb_rfid_pret_mode);			
			window.onfocus=function(){rfid_focus_active=1;}
			window.onblur=function(){rfid_focus_active=0;}
		</script>
		";
		
	} else {
		$script_rfid_antivol="
		<script language=\"JavaScript\">
		init_sans_rfid_pret(!!id!!,'!!cb!!');
		</script>";			
	}	
	if($pmb_rfid_pret_mode)
	$rfid_input_cb="<input type='text' class='saisie-15em' id='cb_doc' name='cb_doc' tabindex='1' value='' /><input  type='button'  id='ajouter' onClick=\"if(document.getElementById('cb_doc').value) flag_error =mode1_add_cb(document.getElementById('cb_doc').value);document.getElementById('cb_doc').value=''\" name='ajouter' class='bouton' value='$msg[925]' />";
	else
	$rfid_input_cb="<input type='text' class='saisie-15em' id='cb_doc' name='cb_doc' tabindex='1' value='' /><input  type='button'  id='ajouter' onClick=\"mode_lecture_cb[document.getElementById('cb_doc').value]='cb';flag_error =Ajax_add_cb(document.getElementById('cb_doc').value);\" name='ajouter' class='bouton' value='$msg[925]' />";
	
	$empr_pret_allowed="
		<div class='left'>
		$rfid_js_header
		<script src='./javascript/rfid/rfid_pret.js'></script>
			
		$script_rfid_antivol
		$rfid_input_cb
		$indicateur_rfid
		</div>
		<div class='right'>
			<input type='button' name='express' id='express' class='bouton' value='$msg[pret_express]' onClick=\"document.location='./circ.php?categ=express&id_empr=!!id!!&groupID=$groupID'\"/>
		</div>
		<div class='row'>
			<table id='table_pret_tmp' name='table_pret_tmp'>
			</table>
		</div>
		<div class='row' id='div_confirm_pret' style='display:none'>
		<h3><input type='button' name='confirm_pret' id='confirm_pret' class='bouton' tabindex='2' value='".$msg["bt_confirm_pret"]."' onClick=\"Ajax_confirm_pret();\"/>
	    	&nbsp;<label id=nb_tmp_pret></label></h3>
		</div>
		";
} else
	$empr_pret_allowed="
	<div class='left'>
		<input type='text' class='saisie-15em' id='cb_doc' name='cb_doc' value='' /><input type='submit' name='ajouter' class='bouton' value='$msg[925]' />
	</div>
	<div class='right'>
		<input type='button' name='express' class='bouton' value='$msg[pret_express]' onClick=\"document.location='./circ.php?categ=express&id_empr=!!id!!&groupID=$groupID'\"/>
	</div>
	";
if($pmb_printer_ticket_url) {
	$printer_ticket_script="		
	<script language='JavaScript'>
	function send_print_ticket(cmd) {
		// Construction de la requï¿½te 
		var url='$pmb_printer_ticket_url'; 
		// On initialise la classe:
		var req = new http_request();
		try {
			netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
		} catch (e) {
			alert(e);
	  		exit();
		}
		// Exï¿½cution de la requï¿½te
		if(req.request(url,1,'xml='+escape(cmd))){
			// Il y a une erreur. Afficher le message retournï¿½
			alert ( req.get_text() );			
		}else { 
			// la commande est bien passï¿½e
			return 1;	
		}
	}		
	function print_ticket(url) {
		// Construction de la requï¿½te 
		// On initialise la classe:
		var req = new http_request();
		try {
			netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
		} catch (e) {
			alert(e);
	  		exit();
		}
		// Exï¿½cution de la requette
		if(req.request(url)){
			// Il y a une erreur. Afficher le message retournï¿½
			alert ( req.get_text() );			
		}else { 
			// la commande est bien passï¿½e		
			send_print_ticket(req.get_text());
			return 1;	
		}
	}
	</script>";
	
	$printer_ticket_link="&nbsp;<a href='#' onclick=\"print_ticket('./ajax.php?module=circ&categ=print_pret&sub=all&id_empr=!!id!!'); return false;\"><img src='./images/print.gif' alt='Imprimer...' title='Imprimer...' align='middle' border='0'></a>";
} else {
	$printer_ticket_script="";
	$printer_ticket_link="";
}
									
$empr_tmpl = "
$printer_ticket_script
<!-- script de confirmation de suppression -->
<script type=\"text/javascript\">
	
	function confirm_delete()
	{
		result = confirm(\"${msg[932]}\");
		if(result)
				document.location = \"./circ.php?categ=empr_delete&id=!!id!!&form_cb=!!cb!!&groupID=$groupID\";
		else
				document.forms['pret_doc'].elements['cb_doc'].focus();
	}

	function check_cb(form)
	{
		x=document.forms['prolong_bloc'].elements['id_bloc'].value;
	    y=form.id_doc.value;
		z='';

		patt=new RegExp(' '+y+' ','g');

		if (patt.test(x)) 
			z=x.replace(patt,'');
		else
			z=x+' '+y+' ';
		document.forms['prolong_bloc'].elements['id_bloc'].value = z;
	}

	function check_allcb(form)
	{
	    y=form.id_inpret.value;
		ids=y.split('|');
		while (ids.length>0) {
			id=ids.shift();
			if (document.forms['prolong'+id].elements['cbox_prol']) document.forms['prolong'+id].elements['cbox_prol'].click();
		}
	}
</script>
<script type='text/javascript' src='./javascript/tablist.js'></script>
<div id=\"el!!id!!Parent\" class=\"notice-parent\">
   		<h1><div class='left'><img src=\"./images/plus.gif\" class=\"img_plus\" name=\"imEx\" id=\"el!!id!!Img\" title=\"".$msg['admin_param_detail']."\" border=\"0\" onClick=\"expandBase('el!!id!!', true); return false;\">
   		!!image_caddie_empr!! !!prenom!! !!nom!! <font size='2'>$msg[empr_nb_pret]: !!info_nb_pret!! $msg[empr_nb_resa]: !!info_nb_resa!! !!header_format!!</font></div><div class='right'><font size='2'>!!empr_resume!! !!empr_statut_libelle!!</font></div></h1>
   		</div>
	<div class='row'><div class='right'>!!empr_picture!!</div></div>
<div id=\"el!!id!!Child\" class=\"notice-child\" style=\"margin-left:7px;display:none;\"!!depliee!!>
<script type='text/javascript'>
	initIt();
</script>
<div id='bloc_adresse_empr'>
	<div class='colonne3'>
		<div class='row'>
			!!adr1!!
			</div>
		<div class='row'>
			!!adr2!!
			</div>
		<div class='row'>
			!!cp!!&nbsp;!!ville!!
			</div>
		<div class='row'>
			!!pays!!
			</div>
		<div class='row'>
			<strong>!!tel1!!</strong> / <strong>!!tel2!!</strong>
			</div>
		<div class='row'>
			$msg[58]$msg[1901] !!mail_all!!
			</div>
	</div>
	<div class='colonne3'>
		<div class='row'>
			<strong>$msg[74] : </strong>!!prof!!
			</div>
		<div class='row'>
			<strong>$msg[75] : </strong>!!date!!
			</div>
		<div class='row'>
			<strong>$msg[125] : </strong>!!sexe!!
			</div>
	</div>
	<div class='colonne_suite'></div>


<div class='row'>
<div class='colonne3'>
	<div class='row'>
		<strong>$msg[1403]</strong>
		</div>
	<div class='row'>
		<strong>$msg[1401] : </strong>!!adhesion!!
		</div>
	<div class='row'>
		<strong>$msg[1402] : </strong>!!expiration!!
		</div>
	</div>
<div class='colonne3'>
	<div class='row'>
		<strong>$msg[60] : </strong>!!codestat!!
		</div>
	<div class='row'>
		<strong>$msg[59] : </strong>!!categ!!
		</div>
	<div class='row'>
		<strong>$msg[38] : </strong>!!cb!!
		</div>
	</div>
	!!abonnement!!
<div class='colonne_suite'>
	<!-- !!localisation!! -->
	<div class='row'>!!groupes!!
		</div>
	<div class='row'>
		<strong>$msg[empr_login] : </strong>!!empr_login!!
		</div>
	<div class='row'>
			!!empr_pwd!!
		</div>
</div>
</div>
<div class='row'></div>
!!perso!!
<div class='row'></div>
</div>
</div>";
if ($ldap_accessible)
	$empr_tmpl .= "<div class='row'>
		<strong>$msg[empr_authldap]: </strong>!!info_authldap!!
	</div>";
$empr_tmpl .= "
<div class='row'>
	<div class='erreur'>!!empr_date_depassee!!</div>
	</div>

<div class='row'>
	<div class='erreur'>!!empr_msg!!</div>
	</div>
!!comptes!!
!!relance!!
<hr />
<div class='row'>
	<div class='left'>
		<input type='button' name='modifier' class='bouton' value='$msg[62]' onClick=\"document.location='./circ.php?categ=empr_saisie&id=!!id!!&groupID=$groupID';\" />
		<input type='button' name='dupliquer' class='bouton' value='$msg[empr_duplicate_button]' onClick=\"document.location='./circ.php?categ=empr_duplicate&id=!!id!!';\" />
		<input type='button' name='imprimercarte' class='bouton' value='$msg[imprimer_carte]' onClick=\"openPopUp('./pdf.php?pdfdoc=carte-lecteur&id_empr=!!id!!', 'print_PDF', 600, 500, -2, -2, '$PDF_win_prop');\" />
		!!voir_sugg!!
	</div>
	<div class='right'>			
		<input type='button' name='supprimer' class='bouton' value='$msg[supprimer]' onClick=\"confirm_delete()\" />
		</div>
	</div>
<br /><br />
<h3>$msg[ajouterpret]</h3>
";
if ($pmb_rfid_activate==1) {
	if(!$pmb_rfid_pret_mode) {
		$empr_tmpl .= "
		<form class='form-$current_module' name='pret_doc' onsubmit=\"if(!document.getElementById('cb_doc').value && document.getElementById('div_confirm_pret').style.display=='inline'){Ajax_confirm_pret();return false;}
		Ajax_add_cb(document.getElementById('cb_doc').value);return false;\">
		";
	}else {	
		$empr_tmpl .= "
		<form class='form-$current_module' name='pret_doc' onsubmit=\"if(!document.getElementById('cb_doc').value && document.getElementById('div_confirm_pret').style.display=='inline'){mode1_confirm_pret();return false;}
		mode1_add_cb(document.getElementById('cb_doc').value);document.getElementById('cb_doc').value='';return false;\">
		";
	}	
} else {
	$empr_tmpl .= "
	<form class='form-$current_module' name='pret_doc' action='circ.php' method='post'>
	";
}
$empr_tmpl .= "
<!--
<div class='row'>
	<label for='cb_doc' class='etiquette'>$msg[61]</label>
	</div>
-->
<div class='row'>
		!!empr_case_pret!!
		<input type='hidden' name='cb_empr' value='!!cb!!' />
		<input type='hidden' name='id_empr' id='id_empr' value='!!id!!' />
		<input type='hidden' name='group_id' value='$group_id' />
		<input type='hidden' name='groupID' value='$groupID' />
		<input type='hidden' name='categ' value='pret' />
		<input type='hidden' name='sub' value='pret_suite' />
		<input type='hidden' name='force_finance' value='$force_finance' />
</div>
<div class='row'></div>
</form>

<script type='text/javascript'>
if (document.forms['pret_doc'].elements['cb_doc']!=undefined)
	document.forms['pret_doc'].elements['cb_doc'].focus();
</script>

<!-- <h3>$msg[379]</h3> -->
<div class='row'>
	!!pret_msg!! &nbsp;
	</div>
<script type='text/javascript' src='./javascript/sorttable.js'></script>
<table class='sortable'>
	<thead>
	<tr>
	<form class='form-$current_module' name='prolong_bloc' action='circ.php'>
		<th colspan='6'>
			<h3>$msg[349] &nbsp;(!!nb_prets_encours!!)&nbsp;&nbsp;
			<input type='button' name='imprimerlistedocs' class='bouton' value='$msg[imprimer]' onClick=\"openPopUp('./pdf.php?pdfdoc=ticket_pret&id_empr=!!id!!', 'print_PDF', 600, 500, -2, -2, '$PDF_win_prop');\" />
			&nbsp;<input type='button' name='imprimerlistedocs' class='bouton' value='$msg[imprimer_liste_pret]' onClick=\"openPopUp('./pdf.php?pdfdoc=liste_pret&id_empr=!!id!!', 'print_PDF', 600, 500, -2, -2, '$PDF_win_prop');\" />
			&nbsp;<input type='button' name='maillistedocs' class='bouton' value='$msg[email]' onClick=\"openPopUp('./pdf.php?pdfdoc=mail_liste_pret&id_empr=!!id!!', 'print_PDF', 600, 500, -2, -2, '$PDF_win_prop');\" />
			$printer_ticket_link
			&nbsp;!!lettre_retard!!&nbsp;!!mail_retard!!&nbsp;
			!!bt_histo_relance!!
			!!voir_tout_pret!!
			</h3>
		</th>
		<th>$msg[pret_bloc_prolong]</th>
		<th class='date_retour'>!!prol_date!!</th>
		<th></th>
	</form>
	</tr>
	<tr>
	<form class='form-$current_module' name='sel_bloc'>
		<th>$msg[293]</th>
		<th size='50%'>$msg[652]</th>
		<th><center>$msg[294]</center></th>
		<th><center>$msg[298]<br />$msg[295]</center></th>
		<th><center>$msg[653]</center></th>
		<th><center>$msg[pret_date_retour_initial]</center></th>
		<th><center>$msg[pret_compteur_prolongation]</center></th>
		<th><center>$msg[654]</center></th>
		<th class='sorttable_nosort'>
			!!bouton_cocher_prolong!!
			<input type='hidden' name='id_inpret' value=\"!!id_inpret!!\">
		</th>
	</form>
	</tr>
	</thead>
	<tbody>
	!!pret_list!!
	</tbody>
	</table>
<div class='row'><hr /></div>
<div class='row'>
	<div class='left'>
		<h3>$msg[350]&nbsp;<input type='button' name='Ajouterresa' class='bouton' value='$msg[925]' onClick=\"document.location='./circ.php?categ=resa&id_empr=!!id!!&groupID=$groupID';\" /></h3>
	</div>
	<div class='right'><span id='msg_chg_loc' class='erreur'></span></div>
</div>	
<div class='row'></div>
!!resa_list!!
";
if ($pmb_resa_planning) {
	$empr_tmpl.= "
	<div class='row'><hr /></div>
	<div class='row'>
		<h3>".$msg['resa_menu_planning']."&nbsp;<input type='button' name='Ajouter_resa_planning' class='bouton' value='".$msg[925]."' onClick=\"document.location='./circ.php?categ=resa_planning&resa_action=search_resa&id_empr=!!id!!&groupID=$groupID';\" /></h3>
	</div>
	!!resa_planning_list!!
";
}

//*************************************************************************************************************************
$empr_tmpl_consultation = "
<div id=\"el!!id!!Parent\" class=\"notice-parent\">
	<div class='left'>
		<img src=\"./images/plus.gif\" class=\"img_plus\" name=\"imEx\" id=\"el!!id!!Img\" title=\"".$msg['admin_param_detail']."\" border=\"0\" onClick=\"expandBase('el!!id!!', true); return false;\">
   		!!image_suppr_caddie_empr!!&nbsp;!!image_caddie_empr!! &nbsp; <a href=!!lien_vers_empr!!>!!nom!! !!prenom!!</a>
   	</div>
   	<div class='right'>
   		!!empr_statut_libelle!!
   	</div>
</div>
<div id=\"el!!id!!Child\" class=\"notice-child\" style=\"margin-left:7px;display:none;\"!!depliee!!>
<div class='left'>
	<div id='bloc_adresse_empr' class='row'>
		<div class='colonne3'>
			<div class='row'>
				!!adr1!!
			</div>
			<div class='row'>
				!!adr2!!
			</div>
			<div class='row'>
				!!cp!!&nbsp;!!ville!!
			</div>
			<div class='row'>
				!!pays!!
			</div>
			<div class='row'>
				<strong>!!tel1!!</strong> / <strong>!!tel2!!</strong>
			</div>
			<div class='row'>
				$msg[58]$msg[1901] !!mail_all!!
			</div>
		</div>
		<div class='colonne3'>
			<div class='row'>
				<strong>$msg[74] : </strong>!!prof!!
			</div>
			<div class='row'>
				<strong>$msg[75] : </strong>!!date!!
			</div>
			<div class='row'>
				<strong>$msg[125] : </strong>!!sexe!!
			</div>
		</div>
		<div class='colonne_suite'>
		</div>
	</div>
	<div id='bloc_adhesion' class='row'>
		<div class='colonne3'>
			<div class='row'>
				<strong>$msg[1403]</strong>
			</div>
			<div class='row'>
				<strong>$msg[1401] : </strong>!!adhesion!!
			</div>
			<div class='row'>
				<strong>$msg[1402] : </strong>!!expiration!!
			</div>
		</div>
		<div class='colonne3'>
			<div class='row'>
				<strong>$msg[60] : </strong>!!codestat!!
			</div>
			<div class='row'>
				<strong>$msg[59] : </strong>!!categ!!
			</div>
			<div class='row'>
				<strong>$msg[38] : </strong>!!cb!!
			</div>
			!!abonnement!!
		</div>
		<div class='colonne_suite'>
			<!-- !!localisation!! -->
			<div class='row'>
				!!groupes!!
			</div>
			<div class='row'>
				<strong>$msg[empr_login] : </strong>!!empr_login!!
			</div>
			<div class='row'>
				!!empr_pwd!!
			</div>
		</div>
	</div>
	<div id=bloc_suite class='row'>
		<div class='row'></div>
		!!perso!!
		<div class='row'></div>
		!!empr_msg!!";
if ($ldap_accessible)
	$empr_tmpl_consultation .= "
		<div class='row'>
			<strong>$msg[empr_authldap]: </strong>!!info_authldap!!
		</div>";
$empr_tmpl_consultation .= "
	</div>
</div><div class='right'>!!empr_picture!!</div>
<div class='row'></div>
</div>
<div class='row'></div>
";

// propriï¿½tï¿½s du sï¿½lecteur de groupe
$select2_prop = "scrollbars=yes, toolbar=no, dependent=yes, width=300, height=400, resizable=yes";


if ($pmb_rfid_activate==1 && $pmb_rfid_serveur_url ) {
		
	$rfid_script_empr="
		$rfid_js_header	
		<script type='text/javascript'>
			var flag_cb_rfid=0;
			flag_program_rfid_ask=0;

			init_rfid_read_cb(f_empr,0);
			
			function f_empr(cb) {
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
				if(document.getElementById('f_cb').value == cb[0]) flag_cb_rfid=1;
				else  flag_cb_rfid=0;
				if(document.getElementById('f_cb').value == '') {	
					flag_cb_rfid=0;				
					document.getElementById('f_cb').value=cb[0];
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
				var cb = document.getElementById('f_cb').value;	
				init_rfid_erase(rfid_ack_erase);
			}
			
			function rfid_ack_erase(ack) {
				var cb = document.getElementById('f_cb').value;
				init_rfid_write_empr(cb,rfid_ack_write);
				
			}
			function rfid_ack_write(ack) {				
				alert (\"".addslashes($msg['rfid_etiquette_programmee_message'])."\");
				flag_semaphore_rfid=0;
			}
			
		</script>
";

	$rfid_program_button="<input  type=button class='bouton' value=' ". $msg['rfid_configure_etiquette_button']." ' onClick=\"program_rfid_ask();\">";	
}else {	
	$rfid_script_empr="";
	$rfid_program_button="";
}



// $empr_form : template pour le form lecteur
$empr_form = jscript_unload_question()."
$rfid_script_empr
<script type='text/javascript'>
	function test_form(form) {
		!!questionrfid!!
		if(form.form_nom.value.length == 0) {
			alert(\"$msg[65]\");
			form.form_nom.focus();
			return false;
		}
		unload_off();
		return check_form();
	}
</script>
<h1>!!entete!!</h1>
<form class='form-$current_module' id='empr_form' name='empr_form' method='post' action='!!form_action!!&id=!!id!!&groupID=$groupID'>
	<input type='hidden' name='form_cb' value='!!cb!!' />
	<input type='hidden' name='groupID' value='$groupID' />
	<input type='hidden' name='debit' value='0' />
	<div style='float:left'><h3>!!nom!!&nbsp;!!prenom!!</h3></div><div style='float:right'><label for='form_statut' class='etiquette'>".$msg["empr_statut_menu"]."</label>&nbsp;<select id='form_statut' name='form_statut'>!!statut!!</select></div>
	<div class='row'></div>
<div class='form-contenu'>
	<div class='row'>

		<!--   Nom   -->
		<div class='colonne3'>
			<div class='row'>
				<label class='etiquette' for='form_nom'>$msg[67]</label>
			</div>
			<div class='row'>
				<input type='text' class='saisie-20em' id='form_nom' name='form_nom' value='!!nom!!' />
			</div>
		</div>

		<!--   Pr?nom   -->
		<div class='colonne3'>
			<div class='row'>
				<label for='form_prenom' class='etiquette'>$msg[68]</label>
			</div>
			<div class='row'>
				<input type='text' class='saisie-20em' id='form_prenom' name='form_prenom' value='!!prenom!!' />
			</div>
		</div>

		<!--   CB   -->
		<div class='colonne_suite'>
			<div class='row'>
				<label for='form_cb' class='etiquette'>$msg[38]</label>
			</div>
			<div class='row'>
				<input class='saisie-10emr' size='15' id='f_cb' name='f_cb' readonly value=\"!!cb!!\" />
				<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./circ/setcb.php?f_cb='+this.form.f_cb.value, 'getcb', 220, 200, -2, -2, 'toolbar=no, resizable=yes')\" />
			</div>
		</div>
	</div>

	<div class='row'>&nbsp;</div>

	<div class='row'>
		<!--   Adresse 1   -->
		<div class='colonne2'>
			<div class='row'>
				<label for='form_adr1' class='etiquette'>$msg[69]</label>
			</div>
			<div class='row'>
				<input type='text' class='saisie-40em' size='50' id='form_adr1' name='form_adr1' maxlength='255' value='!!adr1!!' />
			</div>
		</div>
		<!--   Code postal   -->
		<div class='colonne10'>
			<div class='row'>
				<label for='form_cp' class='etiquette'>$msg[71]</label>
			</div>
			<div class='row'>
				<input type='text' class='saisie-5em' id='form_cp' name='form_cp' maxlength='10' value='!!cp!!' onchange=\"openPopUp('./select.php?what=codepostal&caller=empr_form&param1=form_ville&param2=form_cp&deb_rech='+this.form.form_cp.value, 'select_codepostal', 400, 400, -2, -2, '$select1_prop')\" />
			</div>
		</div>
		<!--   Ville   -->
		<div class='colonne_suite'>
			<div class='row'>
				<label for='form_ville' class='etiquette'>$msg[72]</label>
			</div>
			<div class='row'>
				<input type='text' class='saisie-20em' id='form_ville' name='form_ville' value=\"!!ville!!\" />
				<input type='button'  class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=codepostal&caller=empr_form&param1=form_ville&param2=form_cp&deb_rech='+this.form.form_cp.value, 'select_codepostal', 400, 400, -2, -2, '$select1_prop')\" />
			</div>
		</div>
	</div>

	<div class='row'>
		<!--   Adresse 2   -->
		<div class='colonne2'>
			<div class='row'>
				<label for='form_adr2' class='etiquette'>$msg[70]</label>
			</div>
			<div class='row'>
				<input type='text' class='saisie-40em' size='50' id='form_adr2' name='form_adr2' maxlength='255' value='!!adr2!!' />
			</div>
		</div>
		<!--   Pays   -->
		<div class='colonne_suite'>
			<div class='row'>
				<label for='form_pays' class='etiquette'>$msg[empr_pays]</label>
			</div>
			<div class='row'>
				<input type='text' class='saisie-40em' id='form_pays' name='form_pays' maxlength='255' value='!!pays!!' />
			</div>
		</div>
	</div>

	<div class='row'>&nbsp;</div>

	<div class='row'>
		<!--   Tï¿½lï¿½phone 1   -->
		<div class='colonne4'>
			<div class='row'>
				<label for='form_tel1' class='etiquette'>$msg[73]</label>
			</div>
			<div class='row'>
				<input type='text' class='saisie-15em' id='form_tel1' name='form_tel1' value='!!tel1!!' />
				<label for='form_sms' class='etiquette'>".$msg['send_sms']."</label>
				<input type='checkbox' id='form_sms' name='form_sms' value='1' !!sms!! />				
			</div>
		</div>
		<!--   Tï¿½lï¿½phone 2   -->
		<div class='colonne4'>
			<div class='row'>
				<label for='form_tel2' class='etiquette'>".$msg['73tel2']."</label>
			</div>
			<div class='row'>
				<input type='text' class='saisie-15em' id='form_tel2' name='form_tel2' value='!!tel2!!' />
			</div>
		</div>
		<!--   E-mail   -->
		<div class='colonne_suite'>
			<div class='row'>
				<label for='form_mail' class='etiquette'>$msg[58]</label>
			</div>
			<div class='row'>
				<input type='text'  class='saisie-20em' id='form_mail' name='form_mail' value='!!mail!!' />
			</div>
		</div>
	</div>

	<div class='row'>&nbsp;<hr /></div>
	
	<div class='row'>
		<!--   Profession   -->
		<div class='colonne4'>
			<div class='row'>
				<label for='form_prof' class='etiquette'>$msg[74]</label>
			</div>
			<div class='row'>
				<input type='text' class='saisie-20em' id='form_prof' name='form_prof' value='!!prof!!'>
			</div>
		</div>
		<!--   Sexe   -->
		<div class='colonne4'>
			<div class='row'>
				<label class='etiquette' for='form_sexe'>$msg[125]</label>
			</div>
			<div class='row'>
				<select class='saisie-15em' id='form_sexe' name='form_sexe'>
					<option value='1' sexe_select_1>$msg[126]</option>
					<option value='2' sexe_select_2>$msg[127]</option>
					<option value='0' sexe_select_0>$msg[128]</option>
				</select>
			</div>
		</div>
		<!--   Date de naissance   -->
		<div class='colonne_suite'>
			<div class='row'>
				<label for='form_year' class='etiquette'>$msg[75]</label>
			</div>
			<div class='row'>
				<input type='text'  class='saisie-10em' id='form_year' name='form_year' maxlength='4' value='!!year!!' />
			</div>
		</div>
	</div>

	<div class='row'><hr /></div>

	<div class='row'>
		<!--   Cat?gorie   -->
		<div class='colonne4'>
			<div class='row'>
				<label for='form_categ' class='etiquette'>$msg[59]</label>
			</div>
			<div class='row'>
				<select id='form_categ' name='form_categ' class='saisie-20em'>
					!!categ!!
				</select>
			</div>
		</div>
		<!--   Code statistique   -->
		<div class='colonne4'>
			<div class='row'>
				<label for='form_codestat' class='etiquette'>$msg[60]</label>
			</div>
			<div class='row'>
				<select name='form_codestat' id='form_codestat' class='saisie-20em'>!!cstat!!</select>
			</div>
		</div>
		<!--   Ajout ? un groupe existant   -->
		<div class='colonne_suite'>
			<div class='row'>
				<label for='form_ajoutgroupe' class='etiquette'>$msg[empr_form_ajoutgroupe]</label>
			</div>
			<div class='row'>
				!!groupe_ajout!!
			</div>
		</div>
	</div>

	<div class='row'></div>
	<div class='row'>
		<!--   Localisation du lecteur   -->
		<!-- !!localisation!! -->
	</div>
	
	<div class='row'><hr /></div>

	<div class='row'>
		<!--   Adh?sion   -->
		<div class='colonne4'>
			<div class='row'>
				<label for='form_adhe_ini' class='etiquette'>".$msg[1403]." : ".$msg[1401]."</label>
			</div>
			<div class='row'>
				<strong>!!adhesion!!</strong>
			</div>
		</div>
		<div class='colonne4'>
			<div class='row'>
				<label for='form_adhe_end' class='etiquette'>".$msg[1403]." : ".$msg[1402]."</label>
			</div>
			<div class='row'>
				<strong>!!expiration!!</strong>
			</div>
		</div>
		<!--   Relance adh?sion -->
		<div class='colonne_suite'>&nbsp;
			!!adhesion_proche_depassee!!
		</div>
	</div>

	<div class='row'>
		<!--Type d'abonnement -->
		!!typ_abonnement!!
	</div>
	
	<div class='row'><hr /></div>

	<div class='row'>
		<!--   Langue   -->
		<div class='colonne4'>
			<div class='row'>
				<label for='' class='etiquette'>$msg[empr_langue_opac]</label>
			</div>
			<div class='row'>
				!!combo_empr_lang!!
			</div>
		</div>
		<div class='colonne4'>
			<div class='row'>
				<label for='form_empr_login' class='etiquette'>$msg[empr_login]</label>
			</div>
			<div class='row'>
				<input type='text' class='saisie-15em' id='form_empr_login' name='form_empr_login' value='!!empr_login!!' />
			</div>
		</div>";
	
	if ($ldap_accessible)
		$empr_form .= "<!-- AuthLDAP - MaxMan -->
		<div class='colonne4'>
			<div class='row'>
				<label for='form_ldap' class='etiquette'>AuthLDAP</label>
			</div>
			<div class='row'>
				<input type='checkbox' id='form_ldap' name='form_ldap' !!ldap!!/>
			</div>
		</div>";		
	$empr_form .= "
		<div class='colonne_suite'>
			<div class='row'>
				<label for='form_empr_password' class='etiquette'>$msg[empr_password]</label>
			</div>
			<div class='row'>
				<input type='text' class='saisie-15em' id='form_empr_password' name='form_empr_password' value='' maxlength='255' />
			</div>
		</div>
	</div>
	
	<div class='row'><hr /></div>

	<div class='row'>
		<!--   Message   -->
		<div class='row'>
			<label for='form_codestat' class='etiquette'>$msg[523]</label>
		</div>
		<div class='row'>
			<textarea id='f_message_empr' class='saisie-80em' name='form_empr_msg' cols='62' rows='2' wrap='virtual'>!!empr_msg!!</textarea>
		</div>
	</div>

	<div class='row'><hr /></div>

	<div class='row'>
		!!champs_perso!!
	</div>
	<div class='row'></div>
</div>
<div class='row'>
	<input type='button' class='bouton' value='$msg[76]' onclick='unload_off();history.go(-1);' />
	<input type='submit' class='bouton' value='$msg[77]' onClick=\"return test_form(this.form)\" />
	$rfid_program_button
	<input type='hidden' name='group_id' value='$group_id' />
	</div>
</form>

<script type='text/javascript'>
	document.forms['empr_form'].elements['form_nom'].focus();
	</script>
";

// $empr_edit_tmpl : template pour le form de saisie nom dans la page ?dition des emprunteurs
$empr_edit_tmpl = "
<form class='form-$current_module' name='saisie_cb_ex' method='post' action='!!form_action!!'>
	<div class='form-contenu'>
		<div class='row'>
			<label class='etiquette' for='form_cb'>!!message!!</label>
		</div>
		<div class='row'>
			<input type='text' class='saisie-20em' id='form_cb' name='form_cb' value='' />
		</div>
	</div>
	<div class='row'>
		<input type='submit' class='bouton' value='Ok' />
		<input type='hidden' name='group_id' value='$group_id'>
	</div>
	<script type='text/javascript'>
		document.forms['saisie_cb_ex'].elements['form_cb'].focus();
	</script>
</form>";

$empr_tmpl_fiche_affichage = "
<div class='row'>
	<h1>!!prenom!! !!nom!! <font size='2'>$msg[empr_nb_pret]: !!info_nb_pret!! $msg[empr_nb_resa]: !!info_nb_resa!!</font>&nbsp;<input type=button class=bouton  onclick=\"document.location='./circ.php?categ=pret&form_cb=!!cb!!';\" value='".htmlentities($msg['retour_goto_pret'],ENT_QUOTES, $charset)."'></h1>
	</div>
<div class='colonne3'>
	<strong>$msg[1401] : </strong>!!adhesion!!
	</div>
<div class='colonne_suite'>
	<strong>$msg[1402] : </strong>!!expiration!!
	</div>
<div class='row'>
	<div class='erreur'>!!empr_date_depassee!!</div>
	</div>
<div class='row'>
	<div class='erreur'>!!empr_msg!!</div>
	</div>
<hr />
<div class='row'>
	<h3>$msg[349] &nbsp;(!!nb_prets_encours!!)&nbsp;&nbsp;</h3>
	</div>
<script type='text/javascript' src='./javascript/sorttable.js'></script>
<table>
	<tr>
	<th>$msg[293]</th>
	<th>$msg[652]</th>
	<th>$msg[294]</th>
	<th>$msg[653]</th>
	<th><center>$msg[pret_date_retour_initial]</center></th>
	<th><center>$msg[pret_compteur_prolongation]</center></th>
	<th>$msg[654]</th>
	</tr>
	!!pret_list!!
	</table>

<div class='row'>&nbsp;</div>
<div class='row'>
	<h3>$msg[350]&nbsp;</h3>
	</div>
	!!resa_list!!
";

$empr_comptes_tmpl="
	<div class='row'>
		<h1>!!prenom!! !!nom!! <font size='2'>$msg[empr_nb_pret]: !!info_nb_pret!! $msg[empr_nb_resa]: !!info_nb_resa!!</font></h1>
	</div>
	<div class='row'><a href='circ.php?categ=pret&id_empr=$id'>".$msg["finance_form_empr_go_back"]."</a></div>
	<div class='row'>
		<div class='colonne2'><h1>!!type_compte!!</h1></div><div class='colonne2' style='text-align:right'><h1>".$msg["finance_solde"]." !!solde!!<br />".$msg["finance_not_validated"]." : !!non_valide!!</h1></div>
	</div>
	<form name='compte_form' method='post' action='./circ.php?categ=pret&sub=compte&typ_compte=!!typ_compte!!&id=$id'>
		<div class='row' id='selector_transaction_list'>
			<div class='colonne3'><input type='radio' name='show_transactions' value='1' id='show_transactions_1' !!checked1!! onClick=\"list_transactions.document.location='./circ/list_transactions.php?id_compte=!!id_compte!!&show_transactions=1';\"/><label for='show_transactions_1'>".$msg["finance_form_empr_ten_last"]."</label></div>
			<div class='colonne3'><input type='radio' name='show_transactions' value='2' id='show_transactions_2' !!checked2!! onClick=\"list_transactions.document.location='./circ/list_transactions.php?id_compte=!!id_compte!!&show_transactions=2';\"/><label for='show_transactions_2'>".$msg["finance_form_empr_not_validated"]."</label></div>
			<div class='colonne3'><input type='radio' name='show_transactions' value='3' id='show_transactions_3' !!checked3!! onClick=\"list_transactions.document.location='./circ/list_transactions.php?id_compte=!!id_compte!!&show_transactions=3&date_debut='+this.form.date_debut.value;\"/><label for='show_transactions_3'>".$msg["finance_form_empr_tr_from"]." </label><input type='text' size='10' name='date_debut' value='!!date_debut!!'></div>
		</div>
		<div class='row'>&nbsp;</div>
		<iframe name='list_transactions' width='100%' height='250' src='./circ/list_transactions.php?id_compte=!!id_compte!!&show_transactions=!!show_transactions!!&date_debut=!!date_debut!!'></iframe>
		<div class='row'>&nbsp;</div>
		<div class='row' id='buttons_transaction_list'>
		<table>
		<tr><td style='text-align:left'>
			<input type='button' class='bouton' value='".$msg["finance_but_valenc"]."' onClick=\"list_transactions.document.form_transactions.act.value='valenc'; list_transactions.document.form_transactions.submit()\"><br />
			<input type='button' class='bouton' value='".$msg["finance_but_enc"]."' onClick=\"list_transactions.document.form_transactions.act.value='encnoval'; list_transactions.document.form_transactions.submit()\">
		</td>
		<td style='text-align:center'>
			<input type='button' class='bouton' value='".$msg["finance_but_val"]."' onClick=\"list_transactions.document.form_transactions.act.value='val'; list_transactions.document.form_transactions.submit()\"><br />
			<input type='button' class='bouton' value='".$msg["finance_but_supr"]."' onClick=\"if (confirm('".addslashes($msg["finance_confirm_supr"])."')) { list_transactions.document.form_transactions.act.value='supr'; list_transactions.document.form_transactions.submit() }\">
		</td>
		<td style='text-align:right'>
		<input type='button' class='bouton' value='".$msg["finance_but_cred"]."' onClick=\"list_transactions.document.form_transactions.act.value='special'; list_transactions.document.form_transactions.submit()\">
		</td></tr>
		</table>
		</div>
	</form>
";

$empr_retard_tpl ="
	<script type='text/javascript' src='./javascript/tablist.js'></script>
	<script type='text/javascript' src='./javascript/sorttable.js'></script>
	<div class='row'>
		<h1>!!prenom!! !!nom!! <font size='2'>".$msg[empr_nivo_relance].": !!nivo_relance!! </font></h1>
	</div>
	<div class='row'><a href='circ.php?categ=pret&id_empr=!!id!!'>".$msg["finance_form_empr_go_back"]."</a></div>
	<h3>".$msg["empr_histo_late"]."</h3>
	
		!!liste_retard!!
	
			
	<div class='row'>&nbsp;</div>
	<script type='text/javascript'>
		 initIt();
	</script>
";
