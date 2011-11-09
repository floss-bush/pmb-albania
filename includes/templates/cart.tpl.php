<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: cart.tpl.php,v 1.42 2011-03-15 16:53:00 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

require_once("$include_path/templates/export_param.tpl.php");

// templates pour la gestion des paniers

// template pour le form de création d'un panier
$cart_form = "
<script type=\"text/javascript\">
function test_form(form)
{
	if(form.cart_name.value.length == 0)
	{
		alert(\"$msg[caddie_name_oblig]\");
		return false;
	}
	return true;
}
</script>

<form class='form-$current_module' name='cart_form' method='post' action='!!formulaire_action!!'>
<h3>$msg[new_cart]</h3>
<div class='form-contenu'>
<!--	type	-->
<!--memo_contexte-->
<div class='row'>
	<label class='etiquette' for='form_type'>$msg[caddie_name]</label>
	</div>
<div class='row'>
	<input type='text' class='saisie-80em' name='cart_name' value='' />
	</div>
<div class='row'>
	<label class='etiquette' for='form_type'>$msg[caddie_type]</label>
	</div>
<div class='row'>
	!!cart_type_select!!
	</div>
<div class='row'>
	<label class='etiquette' for='form_type'>$msg[caddie_comment]</label>
	</div>
<div class='row'>
	<input type='text' class='saisie-80em' name='cart_comment' value='' />
	</div>
<div class='row'>
	<label class='etiquette' for='form_type'>$msg[caddie_autorisations]</label>
		<input type='button' class='bouton_small' value='".$msg['tout_cocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,1);' align='middle'>
		<input type='button' class='bouton_small' value='".$msg['tout_decocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,0);' align='middle'>
	</div>
<div class='row'>
	!!autorisations_users!!
	</div>
</div>
<!--	boutons	-->
<div class='row'>
	<input type='button' class='bouton' value='$msg[76]' onClick=\"history.go(-1);\">
	<input type='submit' value='$msg[77]' class='bouton' onClick=\"return test_form(this.form)\" />
	<input type='hidden' name='form_actif' value='1'>
	</div>
</form>
<script type=\"text/javascript\">
		document.forms['cart_form'].elements['cart_name'].focus();
</script>
";

$cart_edit_form = "
<script type=\"text/javascript\">
function test_form(form)
{
	if(form.cart_name.value.length == 0)
	{
		alert(\"$msg[caddie_name_oblig]\");
		return false;
	}
	return true;
}
</script>

<form class='form-$current_module' name='cart_form' method='post' action='!!formulaire_action!!'>
<h3>$msg[edit_cart]</h3>
<div class='form-contenu'>
<!--	type	-->
<div class='row'>
	<label class='etiquette' for='form_type'>$msg[caddie_name]</label>
	</div>
<div class='row'>
	<input type='text' class='saisie-80em' name='cart_name' value='!!name!!' />
	</div>
<div class='row'>
	<label class='etiquette' for='form_type'>$msg[caddie_type]</label>
	</div>
<div class='row'>
	!!cart_type!!
	</div>
<div class='row'>
	<label class='etiquette' for='form_type'>$msg[caddie_comment]</label>
	</div>
<div class='row'>
	<input type='text' class='saisie-80em' name='cart_comment' value='!!comment!!' />
	</div>
<div class='row'>
	<label class='etiquette' for='form_type'>$msg[caddie_autorisations]</label>
	<input type='button' class='bouton_small' value='".$msg['tout_cocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,1);' align='middle'>
	<input type='button' class='bouton_small' value='".$msg['tout_decocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,0);' align='middle'>
	</div>
<div class='row'>
	!!autorisations_users!!
	</div>
</div>
<!--	boutons	-->
<div class='row'>
	<div class='left'>
		<input type='button' class='bouton' value='$msg[76]' onClick=\"document.location='!!formulaire_annuler!!';\">&nbsp;
		<input type='submit' value='$msg[77]' class='bouton' onClick=\"return test_form(this.form)\" />
		<input type='hidden' name='form_actif' value='1'>
	</div>
	<div class='right'>
		<input type='button' class='bouton' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete(!!idcaddie!!,'!!name_suppr!!')\" />
	</div>
</div>
<div class='row'></div>
</form>
<script type=\"text/javascript\">
		document.forms['cart_form'].elements['cart_name'].focus();
</script>
";

// $expl_cb_caddie_tmpl : template pour le form de saisie code-barre
if ($pmb_rfid_activate==1 && $pmb_rfid_serveur_url ) {
	if(!$rfid_port)$rfid_port= get_rfid_port();	

	$rfid_script="
		$rfid_js_header
		<script type='text/javascript'>
			var cb_lu =new Array();	
			init_rfid_read_cb(0,f_expl);
			
			function f_expl(cb) {
				// il y a une ou plusieurs étiquette rfid
				for (i=0; i < cb.length; i++) {					
					//alert ( cb[i]);
					if(!cb_lu[ cb[i]]) Ajax_add_cb( cb[i]);
					cb_lu[ cb[i]]=1;
				}
				//document.getElementById('form_cb_expl').value='';	
				//document.getElementById('form_cb_expl').focus();
			}

			function Ajax_add_cb(cb_doc) {
				var req_add = new http_request();		
				// Construction de la requette 			
				var url= './ajax.php?module=catalog&categ=caddie&sub=$sub&moyen=douchette&action=add_item&idcaddie=!!idcaddie!!&form_cb_expl='+cb_doc;

				// Exécution de la requette POST
				if(req_add.request(url,1)){
					// Il y a une erreur. Afficher le message retourné
					alert ( req_add.get_text() );			
				}else { 
					// commit
					var xml = req_add.get_xml();				
					var param= XMl_to_array(xml, 'param');
					//alert ('!! '+param['nb_item']);
					document.getElementById('nb_item').innerHTML=param['nb_item'];
					document.getElementById('nb_item_pointe').innerHTML=param['nb_item_pointe'];
					document.getElementById('nb_item_base').innerHTML=param['nb_item_base'];
					document.getElementById('nb_item_base_pointe').innerHTML=param['nb_item_base_pointe'];
					document.getElementById('nb_item_blob').innerHTML=param['nb_item_blob'];
					document.getElementById('nb_item_blob_pointe').innerHTML=param['nb_item_blob_pointe'];
					one_more_ligne (param);
				}
			}
			
			function one_more_ligne (param) {
				tr = document.createElement('TR');
				
				//message
				var td = document.createElement('TD');
				td.setAttribute('class','erreur');
				td.appendChild(document.createTextNode(param['message_ajout_expl']));	
				tr.appendChild(td);

				//exemplaire
				var td = document.createElement('TD');
				var obj_1 = document.createElement('a');
				if(param['expl_id']>0) {
					obj_1.setAttribute('href', './catalog.php?categ=edit_expl&id='+param['expl_notice']+'&expl_id='+param['expl_id']);						
					obj_1.appendChild(document.createTextNode(param['form_cb_expl'])); 
					td.appendChild(obj_1);
				} else {
					td.appendChild(document.createTextNode(param['form_cb_expl']));	
				}
				tr.appendChild(td);
				
				//Notice
				var td = document.createElement('TD');
				if(param['expl_id']>0) {
					var obj_1 = document.createElement('a');			
					obj_1.setAttribute('href', './catalog.php?categ=isbd&id='+param['expl_notice']);
					obj_1.appendChild(document.createTextNode(param['titre'])); 
					td.appendChild(obj_1);
				}
				tr.appendChild(td);
				
				document.getElementById('table_cb').appendChild(tr);
			}
		</script>
";

	$table_cb="		
		<div class='row'>
			<table id='table_cb' name='table_cb'>
			</table>
		</div>
		";
}else {
	$rfid_script='';
	$table_cb='';
}	

$expl_cb_caddie_tmpl = "
$rfid_script
!!script!!
<h3>!!title!!</h3>
<form class='form-$current_module' name='saisie_cb_ex' method='post' action='!!form_action!!' onSubmit='return test_form(this)'>
<h3>!!titre_formulaire!!</h3>
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_cb_expl'>!!message!!</label>
		</div>
	<div class='row'>
		<input class='saisie-20em' type='text' id='form_cb_expl' name='form_cb_expl' value=''  />
		</div>
	</div>
<div class='row'>
	<input type='submit' class='bouton' value='$msg[502]' />
	</div>
</form>
$table_cb
<script type='text/javascript'>
document.forms['saisie_cb_ex'].elements['form_cb_expl'].focus();
</script>
";

$begin_result_expl_liste_unique = "
<script type=\"text/javascript\" src=\"./javascript/tablist.js\"></script>
";

// $cart_procs_form : template form procédures stockées
$cart_procs_form = "
<form class='form-$current_module' name='maj_proc' method='post' action='!!action!!'>
<h3>!!form_title!!</h3>
<!--	Contenu du form	-->
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_type'>$msg[caddie_procs_type]</label>
		</div>
	<div class='row'>
		<select name='f_proc_type'>
		<option value='SELECT'>$msg[caddie_procs_type_SELECT]</option>
		<option value='ACTION'>$msg[caddie_procs_type_ACTION]</option>
		</select>
		</div>
	<div class='row'>
		<label class='etiquette' for='form_name'>$msg[705]</label>
		</div>
	<div class='row'>
		<input type='text' name='f_proc_name' value='!!name!!' maxlength='255' class='saisie-50em' />
		</div>
	<div class='row'>
		<label class='etiquette' for='form_code'>$msg[706]</label>
		</div>
	<div class='row'>
		<textarea cols='70' rows='8' name='f_proc_code'>!!code!!</textarea><br />
		".$msg['cart_ex_selection']." select notice_id as <b>object_id</b>, <b>'NOTI'</b> as object_type from notices where ...<br />
		'NOTI' / 'EXPL' / 'BULL'<br />
		".$msg['cart_ex_action']." update exemplaires set expl_statut=!!nouveau_statut!! where expl_id in (CADDIE(<b>EXPL</b>))<br />
		EXPL / NOTI / BULL
		</div>
	<div class='row'>
		<label class='etiquette' for='form_comment'>$msg[707]</label>
		</div>
	<div class='row'>
		<input type='text' name='f_proc_comment' value='!!comment!!' maxlength='255' class='saisie-50em' />
		</div>
	<div class='row'>
		<label class='etiquette' for='form_comment'>$msg[procs_autorisations]</label>
		<input type='button' class='bouton_small' value='".$msg['tout_cocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,1);' align='middle'>
		<input type='button' class='bouton_small' value='".$msg['tout_decocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,0);' align='middle'>
		</div>
	<div class='row'>
		!!autorisations_users!!
		</div>
	</div>
<!-- Boutons -->
<div class='row'>
	<input type='button' class='bouton' value='$msg[76]' onClick='document.location=\"./catalog.php?categ=caddie&sub=gestion&quoi=procs\"' />&nbsp;
	<input type='submit' class='bouton' value='$msg[77]' onClick=\"return test_form(this.form)\" />&nbsp;
	</div>
</form>
<script type='text/javascript'>document.forms['maj_proc'].elements['f_proc_name'].focus();</script>
";

$cart_proc_view_remote = "
<h3><span onclick='menuHide(this,event)'>>!!form_title!!</span></h3>
<!--    Contenu du form    -->
<div class='form-contenu'>
	<div class='row'>
		!!additional_information!!
	</div>
	<div class=colonne2>
		<div class='row'>
		<label class='etiquette' for='form_name'>$msg[remote_procedures_procedure_name]</label>
		</div>
		<div class='row'>
		<input type='text' readonly name='f_proc_name' value='!!name!!' maxlength='255' class='saisie-50em' />
		</div>
	</div>
	<div class='row'>
		<label class='etiquette' for='form_code'>$msg[caddie_procs_type]</label>
		</div>
	<div class='row'>
		!!ptype!!
	</div>
	<div class='row'>
		<label class='etiquette' for='form_code'>$msg[remote_procedures_procedure_sql]</label>
		</div>
	<div class='row'>
		<textarea cols='80' readonly rows='8' name='f_proc_code'>!!code!!</textarea>
	</div>
	<div class='row'>
		<label class='etiquette' for='form_comment'>$msg[remote_procedures_procedure_comment]</label>
		</div>
	<div class='row'>
		<input type='text' readonly name='f_proc_comment' value='!!comment!!' maxlength='255' class='saisie-50em' />
	</div>
	<div class='row'>
		!!parameters_title!!
	</div>
	<div class='row'>
		!!parameters_content!!
	</div>
</div>
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input type='button' class='bouton' value='".$msg["remote_procedures_back"]."' onClick='document.location=\"./catalog.php?categ=caddie&sub=gestion&quoi=remote_procs\"' />&nbsp;
		<input class='bouton' type='button' value=\"".$msg["remote_procedures_import"]."\" onClick=\"document.location='./catalog.php?categ=caddie&sub=gestion&quoi=remote_procs&action=import_remote&id=!!id!!'\" />
		</div>
</div>
<div class='row'></div>
<script type='text/javascript'>document.forms['maj_proc'].elements['f_proc_name'].focus();</script>";


// template form édition procédures stockées
$cart_procs_edit_form = "
<hr /><form class='form-$current_module' name='maj_proc' method='post' action='!!action!!'>
<h3>!!form_title!!</h3>
<!--	Contenu du form	-->
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_type'>$msg[caddie_procs_type]</label>
		</div>
	<div class='row'>
		!!type!!
		</div>
	<div class='row'>
		<label class='etiquette' for='form_name'>$msg[705]</label>
		</div>
	<div class='row'>
		<input type='text' name='f_proc_name' value='!!name!!' maxlength='256' class='saisie-50em' />
		</div>
	<div class='row'>
		<label class='etiquette' for='form_code'>$msg[706]</label>
		</div>
	<div class='row'>
		<textarea cols='70' rows='8' name='f_proc_code'>!!code!!</textarea><br />
		".$msg['cart_ex_selection']." select notice_id as <b>object_id</b>, <b>'NOTI'</b> as object_type from notices where ...<br />
		'NOTI' / 'EXPL' / 'BULL'<br />
		".$msg['cart_ex_action']." update exemplaires set expl_statut=!!nouveau_statut!! where expl_id in (CADDIE(<b>EXPL</b>))<br />
		EXPL / NOTI / BULL
		</div>
	<div class='row'>
		<label class='etiquette' for='form_comment'>$msg[707]</label>
		</div>
	<div class='row'>
		<input type='text' name='f_proc_comment' value='!!comment!!' maxlength='256' class='saisie-50em' />
		</div>
	<div class='row'>
		<label class='etiquette' for='form_comment'>$msg[procs_autorisations]</label>
		<input type='button' class='bouton_small' value='".$msg['tout_cocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,1);' align='middle'>
		<input type='button' class='bouton_small' value='".$msg['tout_decocher_checkbox']."' onclick='check_checkbox(document.getElementById(\"auto_id_list\").value,0);' align='middle'>
		</div>
	<div class='row'>
		!!autorisations_users!!
		</div>
	</div>
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input type='button' class='bouton' value='$msg[76]' onClick='document.location=\"./catalog.php?categ=caddie&sub=gestion&quoi=procs\"' />&nbsp;
		<input type='submit' class='bouton' value='$msg[77]' onClick=\"return test_form(this.form)\" />&nbsp;
		!!exec_button!!
	</div>
	<div class='right'>
		<input type='button' class='bouton' value=' $msg[supprimer] ' onClick=\"javascript:confirmation_delete(!!id!!,'!!name_suppr!!')\" />
	</div>
</div>
<div class='row'></div>
</form>
<script type='text/javascript'>document.forms['maj_proc'].elements['f_proc_name'].focus();</script>
";

$notice_linked_suppr_form="
<div class='row'>&nbsp;</div>
<div class='row'>
	<input type='checkbox' name='supp_notice_linked' value='1'>".$msg["caddie_supp_notice_linked"]."
</div>
<div class='row'>
	<input type='checkbox' name='supp_notice_linked_expl_num' value='1'>".$msg["caddie_supp_notice_linked_expl_num"]."
</div>
<div class='row'>
	<input type='checkbox' name='supp_notice_linked_cascade' value='1'>".$msg["caddie_supp_notice_linked_cascade"]."
</div>
";

$cart_choix_quoi = "
<hr /><form class='form-$current_module' name='maj_proc' method='post' action='!!action!!' >
<h3>!!titre_form!!</h3>
<!--	Contenu du form	-->
<div class='form-contenu'>
<div class=row>
	<div class=colonne2>
		<div class='row'>
			<input type='checkbox' name='elt_flag' value='1'>$msg[caddie_item_marque]";
	if ($quelle=="supprbase" || $quelle=="supprpanier") $cart_choix_quoi .= "&nbsp;<input type='checkbox' name='elt_flag_inconnu' value='1'>$msg[caddie_item_blob]";
	$cart_choix_quoi .= "
		</div>
		<div class='row'>&nbsp;</div>
		<div class='row'>
			<input type='checkbox' name='elt_no_flag' value='1'>$msg[caddie_item_NonMarque]";
	if ($quelle=="supprbase" || $quelle=="supprpanier") $cart_choix_quoi .= "&nbsp;<input type='checkbox' name='elt_no_flag_inconnu' value='1'>$msg[caddie_item_blob]";
	$cart_choix_quoi .= "
		</div>
	</div>
	<div class=colonne_suite>
		!!bull_not_ou_dep!!
	</div>
</div>
<!--suppr_link-->
<div class='row'></div>
</div>
<!-- Boutons -->
<div class='row'>
	<input type='button' class='bouton' value='$msg[76]' onClick='document.location=\"!!action_cancel!!\"' />&nbsp;
	<input type='submit' class='bouton' value='!!bouton_valider!!' !!onclick_valider!!/>&nbsp;
	</div>
</form>
";

// $cart_choix_quoi_not_ou_dep : template form choix des éléments notice de bulletin ou dépouillements de bulletin
$cart_choix_quoi_not_ou_dep = "
	<div class='row'>
		<input type='checkbox' name='bull_dep' value='1'>$msg[caddie_transfert_BULL_DEP]
		</div>
	<div class='row'>&nbsp;</div>
	<div class='row'>
		<input type='checkbox' name='bull_not' value='1'>$msg[caddie_transfert_BULL_NOT]
		</div>
";

// $cart_choix_quoi_action : template form choix des éléments à traiter pour une procédure d'action
$cart_choix_quoi_action = "
<hr /><form class='form-$current_module' name='maj_proc' method='post' action='' >
<h3>".$msg["caddie_choix_action"]."</h3>
<!--	Contenu du form	-->
<div class='form-contenu'>
	<div class='row'>
		<input type='checkbox' name='elt_flag' value='1'>$msg[caddie_item_marque]
		</div>
	<div class='row'>
		<input type='checkbox' name='elt_no_flag' value='1'>$msg[caddie_item_NonMarque]
		</div>
	</div>
</form>
";

// $cart_choix_quoi_exporter : template form choix des éléments à exporter
$cart_choix_quoi_exporter = "
<hr /><form class='form-$current_module' name='maj_proc' method='post' action='!!action!!' >
<h3>!!titre_form!!</h3>
<!--	Contenu du form	-->
<div class='form-contenu'>
	<div class='row'>
		<input type='checkbox' id='elt_flag' name='elt_flag' value='1'>
		<label for='elt_flag'>$msg[caddie_item_marque]</label>
		</div>
	<div class='row'>
		<input type='checkbox' id='elt_no_flag' name='elt_no_flag' value='1'>
		<label for='elt_no_flag'>$msg[caddie_item_NonMarque]</label>
		</div>
	<div class='row'>
		Type d'export  !!export_type!!
	</div>
	<div class='row'>
		<input type='checkbox' value='1' id='keep_expl' name='keep_expl'> 
		<label for='keep_expl'>$msg[caddie_Conserver995]</label>
	</div>
	<div class='row'>!!form_param!!</div>
	
<!-- Boutons -->
	<div class='row'>
		<input type='button' class='bouton' value='$msg[76]' onClick='document.location=\"!!action_cancel!!\"' />&nbsp;
		<input type='submit' class='bouton' value='!!bouton_valider!!' />&nbsp;
	</div>
</form>
";

// $cart_choix_quoi_edition : template form choix des éléments à éditer
$cart_choix_quoi_edition = "
<hr /><form class='form-$current_module' name='maj_proc' method='post' action='!!action!!' >
<h3>!!titre_form!!</h3>
<!--	Contenu du form	-->
<div class='form-contenu'>
	<div class='row'>
		<input type='checkbox' name='elt_flag' value='1'>$msg[caddie_item_marque]
		</div>
	<div class='row'>
		<input type='checkbox' name='elt_no_flag' value='1'>$msg[caddie_item_NonMarque]
		</div>		
	<!-- notice_template -->
	</div>
<!-- Boutons -->
<div class='row'>
	<input type='button' class='bouton' value='$msg[76]' onClick='document.location=\"!!action_cancel!!\"' />&nbsp;
	<!-- !!boutons_supp!! -->
	</div>
</form>
";


// $cart_choix_quoi_impr_cote : template form choix des éléments pour impression des étiquettes de cote
$cart_choix_quoi_impr_cote = "
<hr />
<form class='form-".$current_module."' name='maj_proc' method='post' action='!!action!!' >
<h3>!!titre_form!!</h3>
<!--	Contenu du form	-->
<div class='form-contenu'>
	<div class='row'>
		<input type='checkbox' id='elt_flag' name='elt_flag' value='1' !!elt_flag_chk!! >".$msg[caddie_item_marque]."
	</div>
	<div class='row'>
		<input type='checkbox' id='elt_no_flag' name='elt_no_flag' value='1' !!elt_no_flag_chk!! >".$msg[caddie_item_NonMarque]."
	</div>

	<br />

	<div class='row'>
		<!--label_fmt_sel-->
	</div>

	<br />

	<div class='row'>
		<div class='colonne' style='float:left;width:45%;'>
			<!--label_fmt_dis-->
		</div>
		<div class='colonne' style='float:right;width:45%;'>
			<!--label_con_dis-->
		</div>
	</div>

	<br />

	<div class='row'>
		<label class='etiquette'>".htmlentities($msg['first_row_impr'], ENT_QUOTES, $charset)."</label>
		<input type='text' id='first_row' name='first_row' class='saisie-2em' style='text-align:right;' value='1' />
		<label class='etiquette'>".htmlentities($msg['first_col_impr'], ENT_QUOTES, $charset)."</label>
		<input type='text' id='first_col' name='first_col' class='saisie-2em' style='text-align:right;' value='1' />
	</div>

</div>
<!-- Boutons -->
<div class='row'>
	<input type='button' class='bouton' value='$msg[76]' onClick='document.location=\"!!action_cancel!!\"' />&nbsp;
	<input type='submit' class='bouton' value='!!bouton_valider!!' onClick=\"return confirm(); \" />&nbsp;
</div>
</form>

<script type='text/javascript'>

function confirm() {


	if ( (document.forms['maj_proc'].elements['elt_flag'].checked==false) && (document.forms['maj_proc'].elements['elt_no_flag'].checked==false) ) {
		alert(\"".$msg['param_err_impr']."\");
		return false;
	}

	<!--label_fmt_ver-->
	<!--label_con_ver-->

}

</script>

";
