<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: suggestions.tpl.php,v 1.33 2010-03-04 10:30:07 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

//	------------------------------------------------------------------------------
//	$search_form : template de recherche pour les suggestions
//	------------------------------------------------------------------------------

$sug_search_form = "
<form class='form-".$current_module."' id='search' name='search' method='post' action=\"!!action!!\">
	<h3>!!form_title!!</h3>
	<!--    Contenu du form    -->
	<div class='form-contenu'>
		
		<div class='row'>
			<div class='colonne3'>
				<input type='text' class='saisie-30em' id='user_input' name='user_input' value='!!user_input!!' />
			</div>
		</div>
		<div class='row'>	
			";
if ($acquisition_sugg_localises == '1') {
	$sug_search_form.="
			<div class='colonne3'>
				<label class='etiquette'>".htmlentities($msg['acquisition_location'], ENT_QUOTES, $charset)."</label>
				<div class='row'>
					<!-- sel_location -->
				</div>	
			</div>";
}
if ($acquisition_sugg_categ == '1') {
	$sug_search_form.="
  			<div class='colonne3'>
				<label class='etiquette'>".htmlentities($msg['acquisition_categ'], ENT_QUOTES, $charset)."</label>
				<div class='row'>	
					<!-- sel_categ -->
				</div>
			</div>";
}			
$sug_search_form.= "
			<script type='text/javascript'>
				function filtrer_user(){
					document.forms['search'].submit();
				}
			</script>
			<div class='colonne3'>
				<label class='etiquette'>".htmlentities($msg['acquisition_sug_etat'], ENT_QUOTES, $charset)."</label>
				<div class='row'>
					<!-- sel_state -->
				</div>
			</div>
			<div class='colonne3'>
				<label class='etiquette'>".htmlentities($msg['acquisition_sugg_filtre_src'], ENT_QUOTES, $charset)."</label>
				<div class='row'>
					!!sug_filtre_src!!
				</div>
			</div>
			<div class='colonne3'>
				<label class='etiquette'>".htmlentities($msg['acquisition_sugg_filtre_user'], ENT_QUOTES, $charset)."</label>
				<div class='row'>
					<input type='hidden' id='user_id' name='user_id' value='!!user_id!!'/>
					<input type='hidden' id='user_statut' name='user_statut' value='!!user_statut!!' />
					<input type='text' id='user_txt' name='user_txt' class='saisie-20emr' value='!!user_txt!!'/>
					<input type='button' class='bouton_small' value='X'  onclick=\"this.form.user_id.value=0;this.form.user_statut.value=0;this.form.user_txt.value=''\"/>
					<input type='button' class='bouton_small' value='...' onclick=\"openPopUp('./select.php?what=origine&caller=search&param1=user_id&param2=user_txt&param3=user_statut&deb_rech='+escape(this.form.user_txt.value)+'&callback=filtrer_user', 'select_user', 400, 400, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes')\" />				
				</div>
			</div>
		</div>
		
		<div class='row'></div>
	</div>		
	
	<div class='row'>
		<div class='left'>
			<input type='submit' class='bouton' value='".$msg['142']."' />
			<!-- bouton_add -->
		</div>
		<div class='right'>
			<!-- lien_last -->
		</div>
	</div>

	<div class='row'></div>
</form>
<br />
";

//	------------------------------------------------------------------------------
// $sug_list_form : template de liste pour les suggestions
//	------------------------------------------------------------------------------

$sug_list_form ="
<form class='form-$current_module' id='sug_list_form' name='sug_list_form' method='post' action=\"\" >
	<div class='form-contenu'>
		<table width='100%'><tr>
			<th>".htmlentities($msg['acquisition_sug_dat_cre'], ENT_QUOTES, $charset)."</th>
			<th>".htmlentities($msg['acquisition_sug_tit'], ENT_QUOTES, $charset)."</th>
			<th>".htmlentities($msg['acquisition_sug_edi'], ENT_QUOTES, $charset)."</th>
			<th>".htmlentities($msg['acquisition_sug_aut'], ENT_QUOTES, $charset)."</th>
			<th>".htmlentities($msg['acquisition_sug_etat'], ENT_QUOTES, $charset)."</th>	
			<th>".htmlentities($msg['acquisition_sug_iscat'], ENT_QUOTES, $charset)."</th>";

if ($acquisition_sugg_categ == '1') {
	$sug_list_form.="<th>".htmlentities($msg['acquisition_categ'], ENT_QUOTES, $charset)."</th>";
}		
$sug_list_form.="
	<th>".htmlentities($msg['acquisition_sugg_src'], ENT_QUOTES, $charset)."</th>
	<th>".htmlentities($msg['acquisition_sugg_date_publication'], ENT_QUOTES, $charset)."</th>	
	<th>".htmlentities($msg['acquisition_sugg_piece_jointe'], ENT_QUOTES, $charset)."</th>"
;	
$sug_list_form.="				
			<th>&nbsp;</th></tr>
			<!-- sug_list -->
		</table>
	</div>
	<div class='row'>
		<div class='left'><!-- bt_imp -->&nbsp;<!-- bt_exporter -->&nbsp;<!-- bt_todo --><span class='child' ><!-- to_categ --></span></div>
		<div class='right'><!-- bt_chk --></div>
	</div>
	<div class='row'>&nbsp;</div>
	<div class='row'>
		<div class='left'><!-- bt_list --></div>
		<div class='right'><!-- bt_sup --></div>
	</div>
	<div class='row'>
		<div class='left'></div>
		<div class='row'></div>
	</div>
</form>
<!-- script -->
<br />
<div class='form' >
	<!-- nav_bar -->
</div>
";

//	------------------------------------------------------------------------------
// $sug_modif_form : template du form de suggestions
//	------------------------------------------------------------------------------
$sug_modif_form = "
<script language='javascript' type='text/javascript'>
function isnum(sText){
	var valid_chars = '0123456789.';
	for (var i = 0; i < sText.length; i++) {		
		if (valid_chars.indexOf(sText.charAt(i)) == -1) {
			if(i == 0) {
				if(sText.charAt(i) != '-')
					return false;
			} else return false;		
		} 
		
	}	
	return true;
}
		
function add_numeric_obj(obj,inc) {
	var id_obj = document.getElementById(obj);
	if(!isnum(id_obj.value)) id_obj.value=0;
	id_obj.value = parseInt(id_obj.value) + inc ;
}
</script>
<form class='form-$current_module' id='sug_modif_form' name='sug_modif_form' method='post' action='!!action!!' enctype='multipart/form-data'>
	<h3>!!form_title!!</h3>
	<div class='form-contenu'>
	
		<div class='row'>
			<div class='colonne5'>
				<label class='etiquette' >".htmlentities($msg['acquisition_sug_dat_cre'], ENT_QUOTES, $charset)."</label>
			</div>
			<div class='colonne5'>
				<label class='etiquette' >".htmlentities($msg['acquisition_sug_orig'], ENT_QUOTES, $charset)."</label>
			</div>
			<div class='colonne5'>
				<label class='etiquette' >".htmlentities($msg['acquisition_sug_poi'], ENT_QUOTES, $charset)."</label>
			</div>
			<div class='colonne5'>
				<label class='etiquette' >".htmlentities($msg['acquisition_sug_etat'], ENT_QUOTES, $charset)."</label>
			</div>
			<div class='colonne5'>
			";
if ($acquisition_sugg_categ=='1'){
	$sug_modif_form.= "<label class='etiquette' >".htmlentities($msg['acquisition_categ'], ENT_QUOTES, $charset)."</label>";
} else {
	$sug_modif_form.= "&nbsp;";
}
$sug_modif_form.="		
			</div>
		</div>
		<div class='row'>
			<div class='colonne5'>
				!!dat_cre!!
			</div>
			<div class='colonne5'>
				<input type='hidden' id='orig' name='orig' value='!!orig!!' />
				<input type='hidden' id='typ' name='typ' value='!!typ!!' />
				<input type='hidden' id='id_notice' name='id_notice' value='!!id_notice!!' />
				!!lib_orig!!
				!!creator_ajout!!
				!!list_user!!
				<div id='user_list'></div>
			</div>
<!--
			<div class='colonne5'>
				<input type='hidden' id='poi' name='poi' value='!!poi!!' />
				<input type='text' id='poi_tot' name='poi_tot' class='saisie-10emd' readonly='readonly' value='!!poi_tot!!' />
			</div>
-->
			<div class='colonne5'>
				<input type='hidden' id='poi' name='poi' value='!!poi!!' />
				<input type='hidden' id='poi_tot' name='poi_tot' value='!!poi_tot!!' />
				<span id='aff_poi_tot' >!!poi_tot!!</span>
			</div>
			<div class='colonne5'>
				<input type='hidden' id='statut' name='statut' value='!!statut!!' />
				!!lib_statut!!
			</div>
			<div class='colonne5'>";
if ($acquisition_sugg_categ=='1'){
	$sug_modif_form.= "!!categ!!";
} else {
	$sug_modif_form.= "&nbsp;";
}
$sug_modif_form.="
			</div>
		</div>
		
		<div class='row'>
		
			<div class='colonne5'>
				<label class='etiquette'>".htmlentities($msg['acquisition_sug_qte'], ENT_QUOTES, $charset)."</label>
				<div class='row'>
					<!-- nombre_expl -->
					<input  class='bouton_small' type='button' value='-' onclick=\"add_numeric_obj('nombre_expl',-1)\">
					<input maxLength='4' size='2' value='!!nombre_expl!!' id='nombre_expl' name='nombre_expl' >
					<input class='bouton_small' type='button' value='+' onclick=\"add_numeric_obj('nombre_expl',1)\">
				</div>	
			</div>
		
";	
if ($acquisition_sugg_localises == '1') {
$sug_modif_form.="
			<div class='colonne5'>
				<label class='etiquette'>".htmlentities($msg['acquisition_location'], ENT_QUOTES, $charset)."</label>
				<div class='row'>
					<!-- sel_location -->
				</div>	
			</div>";
		}
$sug_modif_form.="	
		<div class='colonne5'>
			<label class='etiquette' >".htmlentities($msg['acquisition_sugg_filtre_src'], ENT_QUOTES, $charset)."</label>
			<div class='row'>
				!!liste_source!!
			</div>
		</div>
		</div>	
		<div class='row'></div>
		<div class='row'><hr /></div>

		<div class='row'>
			<label class='etiquette' >".htmlentities($msg['acquisition_sug_tit'], ENT_QUOTES, $charset)."</label>
		</div>
		<div class='row'>
			<input type='text/javascript' id='tit' name='tit' class='saisie-60em' value='!!tit!!' />
			!!lien!!
		</div>

		<div class='row'>
			<label class='etiquette' >".htmlentities($msg['acquisition_sug_edi'], ENT_QUOTES, $charset)."</label>
		</div>
		<div class='row'>
			<input type='text/javascript' id='edi' name='edi' class='saisie-30em' value='!!edi!!' />
		</div>

		<div class='row'>
			<label class='etiquette' >".htmlentities($msg['acquisition_sug_aut'], ENT_QUOTES, $charset)."</label>
		</div>
		<div class='row'>
			<input type='text/javascript' id='aut' name='aut' class='saisie-30em' value='!!aut!!' />
		</div>

		<div class='row'>
			<label class='etiquette' >".htmlentities($msg['acquisition_sug_cod'], ENT_QUOTES, $charset)."</label>
		</div>
		<div class='row'>
			<input type='text/javascript' id='cod' name='cod' class='saisie-30em' value='!!cod!!' />
		</div>

		<div class='row'>
			<label class='etiquette' >".htmlentities($msg['acquisition_sug_pri'], ENT_QUOTES, $charset)."</label>
		</div>
		<div class='row'>
			<input type='text/javascript' id='pri' name='pri' class='saisie-10em' value='!!pri!!' />
		</div>

		<div class='row'>
			<label class='etiquette' >".htmlentities($msg['acquisition_sug_url'], ENT_QUOTES, $charset)."</label>
		</div>
		<div class='row'>
			<input type='text/javascript' id='url_sug' name='url_sug' class='saisie-80em' value='!!url_sug!!' />
			<!-- url_sug -->
		</div>
		<div class='row'>
			<label class='etiquette' >".htmlentities($msg['acquisition_sug_com'], ENT_QUOTES, $charset)."</label>
		</div>
		<div class='row'>
			<textarea id='com' name='com' class='saisie-80em' cols='62' rows='6' wrap='virtual'>!!com!!</textarea>
		</div>		
		<div class='row'>
			<label class='etiquette' >".htmlentities($msg['acquisition_sugg_date_publication'], ENT_QUOTES, $charset)."</label>
		</div>
		<div class='row'>
			<input type='text' id='date_publi' name='date_publi' value='!!date_publi!!'>
			<input type='button' class='bouton' id='date_publi_sug' name='date_publi_sug' value='...' onClick=\"openPopUp('./select.php?what=calendrier&caller=sug_modif_form&param1=date_publi&param2=date_publi&auto_submit=NO&date_anterieure=YES', 'date_publi', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\"/>
		</div>
		<div class='row'>
			<label class='etiquette' >".htmlentities($msg['acquisition_sugg_piece_jointe'], ENT_QUOTES, $charset)."</label>
		</div>
		!!div_pj!!
		<div class='row'></div>		
	</div>	

	<!--	boutons	-->
	<div class='row'>
		<div class='left'>
			<input type='button' class='bouton' value='$msg[76]' $back_url />
			<input type='submit' class='bouton' value='$msg[77]' onClick=\"return test_form(this.form);  \" />
			<!-- bouton_cat -->
		</div>
		<div class='right'>
			<!-- bouton_sup -->
		</div>
	</div>

	<div class='row'></div>
</form>


<script type='text/javascript'>

	document.getElementById('tit').focus();
	function test_form(form) {
		
		var ret = true;
		if((form.tit.value.length == 0) || (((form.aut.value.length == 0) && (form.edi.value.length == 0)) 
			&& (form.cod.value.length == 0) 
			&& (form.piece_jointe_sug.value.length == 0))){	    	
			ret = false;
	    }
	    
	    if(!ret)
	    	 alert(\"$msg[acquisition_sug_ko]\");
		return ret;
			
	}
	
	function ajax_origine(){
		
		var action = new http_request();
		var url = './ajax.php?module=acquisition&categ=sugg&id_sugg=!!id_sug!!&quoifaire=ajout_origine';
		action.request(url, true, \"orig=\"+document.getElementById('orig').value+\"&type_orig=\"+document.getElementById('typ').value);
		if(action.get_status() == 0){
			if(document.getElementById('ori')){
				document.getElementById('ori').style.display = 'none';
				document.getElementById('oriChild').style.display = 'none';
			}
			if(document.getElementById('creator_lib_orig')){
				document.getElementById('creator_lib_orig').style.display = 'none';
				document.getElementById('creator_btn_orig').style.display = 'none';
			}
			document.getElementById('user_list').innerHTML = action.get_text();
			document.getElementById('orig').value = 0;
			document.getElementById('typ').value = 0;
			document.getElementById('creator_lib_orig').value = '';
			document.getElementById('creator_lib_orig_ajax').value = '';
		}
		
	}
	

</script>
";


$orig_form_mod = "
	<input type='text' id='lib_orig' name='lib_orig' class='saisie-10emr' value='!!lib_orig!!' onchange=\"openPopUp('./select.php?what=origine&caller=sug_modif_form&param1=orig&param2=lib_orig&param3=typ&param4=poi&param5=poi_tot&param6=aff_poi_tot&deb_rech='+escape(this.form.lib_orig.value), 'select_orig', 400, 400, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes')\" />
	<input type='button' class='bouton_small' value='...' onclick=\"openPopUp('./select.php?what=origine&caller=sug_modif_form&param1=orig&param2=lib_orig&param3=typ&param4=poi&param5=poi_tot&param6=aff_poi_tot&deb_rech='+escape(this.form.lib_orig.value), 'select_orig', 400, 400, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes')\" />";

$bt_chk ="<input type='button' id='bt_chk' class='bouton_small' value='$msg[acquisition_sug_checkAll]' onClick=\"checkAll('sug_list_form', 'chk', check); return false;\" />";
$bt_supChk = "<input type='button' class='bouton_small' value='$msg[63]' onClick=\"supChk();\" />";


$bt_imp = "<input type='button' class='bouton_small' value='$msg[imprimer]' onClick=\"!!imp!!\" />";
$bt_exporter = "<input type='button' class='bouton_small' value='".$msg['admin_Expvers']."' onClick=\"!!exp!!\" /><!-- list_export -->";

$lk_url_sug = "<a href='!!url_sug!!' target='_blank'><img src='./images/globe.gif' border='0'/></a>";


$script = "
<script type='text/javascript'>

	var check = true;

	//Coche et décoche les éléments de la liste
	function checkAll(the_form, the_objet, do_check) {
	
		var elts = document.forms[the_form].elements[the_objet+'[]'] ;
		var elts_cnt  = (typeof(elts.length) != 'undefined')
	              ? elts.length
	              : 0;
	
		if (elts_cnt) {
			for (var i = 0; i < elts_cnt; i++) {
				elts[i].checked = do_check;
			} 
		} else {
			elts.checked = do_check;
		}
		if (check == true) {
			check = false;
			document.getElementById('bt_chk').value = '$msg[acquisition_sug_uncheckAll]';
		} else {
			check = true;
			document.getElementById('bt_chk').value = '$msg[acquisition_sug_checkAll]';	
		}
		return true;
	}


	//Vérifie que le nb d'élements minimum passé en paramètre est coché
	function verifChk(nb_to_chk) {
		
		var elts = document.forms['sug_list_form'].elements['chk[]'];
		var elts_cnt  = (typeof(elts.length) != 'undefined')
	              ? elts.length
	              : 0;
		nb_chk = 0;
		if (elts_cnt) {
			for(var i=0; i < elts.length; i++) {
				if (elts[i].checked) nb_chk++;
			}
		} else {
			if (elts.checked) nb_chk++;
		}
		if (nb_chk < nb_to_chk) {
			alert(\"".$msg['acquisition_sug_msg_nocheck']." \"+nb_to_chk+\" ".$msg['acquisition_sug_msg_nocheck2']."\");
			return false;	
		}
		return true;
	}

	<!-- script_list -->
";

$script.="

	function fusVal(){
		if(!verifChk(1)) return false;
		r = confirm(\"".$msg['acquisition_sug_msg_fusVal']."\");
		if (r) {
			document.forms['sug_list_form'].setAttribute('action', './acquisition.php?categ=sug&action=fusVal&nb_per_page=".$nb_per_page."');
			document.forms['sug_list_form'].submit();
			return true;	
		}
		return false;
	}

</script>";

$bt_fusVal = "<input type='button' class='bouton' value='$msg[acquisition_sug_bt_fus]' onClick=\"fusVal(); \" />";

