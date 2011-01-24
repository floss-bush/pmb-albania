<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: category.tpl.php,v 1.30 2010-12-06 15:53:22 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

// templates pour la gestion des catégories

// $categ_browser : template du browser de catégories
$categ_browser = "

<br />
<div class='row'>
	!!browser_top!!
	!!browser_header!!<hr />
</div>
<div class='row'>
	<table border='0'>
		!!browser_content!!
	</table>
</div>";

// $category_form : template du form de catégories
$selector_prop = "dependent=yes, width=$selector_x_size, height=$selector_y_size, resizable=1, scrollbars=yes, resizable=yes";
$select_categ_prop = "scrollbars=yes, location=no, toolbar=no, dependent=yes, resizable=yes";
$category_form = jscript_unload_question()."
<script type='text/javascript'>
<!--
	function test_form(form)
	{
		if(document.getElementById('category_libelle_defaut').value.length == 0)
			{
				var msg = \"$msg[thes_libelle_categ_ref_manquant]\"+\"\\n!!lang_def_js!!\";
				alert(msg);
				return false;
			}
		unload_off();
		return true;
	}
	
function confirm_delete() {
        result = confirm(\"${msg[confirm_suppr]}\");
        if(result) {
        	unload_off();
            document.location='./autorites.php?categ=categories&sub=delete&parent=!!parent!!&id=!!id!!';
		} else
            document.forms['categ_form'].elements['category_libelle!!lang_def_cle!!'].focus();
    }
-->
</script>
<form class='form-$current_module' id='categ_form' name='categ_form' method='post' action='!!action!!'>
<h3>!!form_title!!</h3>
<div class='form-contenu'>

	<!-- libelle defaut -->
	<div class='row'>
		<label class='etiquette' >".htmlentities($msg[103], ENT_QUOTES, $charset)."</label><label class='etiquette'>!!lang_def!!</label>
		<!-- bt_lib_trad -->
	</div>
	<div class='row'>
		<input type='text' class='saisie-80em' id='category_libelle_defaut' name='category_libelle!!lang_def_cle!!' value=\"!!lang_def_libelle!!\" />
	</div>
	<!--	libelle traductions-->
	<div id='lib_trad' class='form-$current_module' style='display:none' >
		!!c_libelle_trad!!
	</div>
	
	<div class='row'>
		<!--	note application defaut -->
		<div class='colonne2'>
			<label class='etiquette'>".htmlentities($msg[categ_na], ENT_QUOTES, $charset)."</label><label class='etiquette'>!!lang_def!!</label>
		</div>
	
		<!--	commentaire defaut -->
		<div class='colonne_suite'>
			<label class='etiquette'>".htmlentities($msg[categ_commentaire], ENT_QUOTES, $charset)."</label><label class='etiquette'>!!lang_def!!</label>
			<!-- bt_cm_na_trad -->
		</div>
	
	</div>
	
	<div class='row'>
		<!-- note application defaut -->
		<div class='colonne2'>
			<textarea class='saisie-50em' id='category_na' name='category_na!!lang_def_cle!!' cols='40' rows='2' wrap='virtual'>!!lang_def_na!!</textarea>
		</div>
		<!-- commentaire defaut -->
		<div class='colonne_suite'>
			<textarea class='saisie-50em' id='category_comment' name='category_cm!!lang_def_cle!!' cols='40' rows='2' wrap='virtual'>!!lang_def_cm!!</textarea>
		</div>
	</div>
	
	<div id='cm_na_trad' class='row' style='display:none' >
		<!--note application et commentaire traductions -->
		!!cm_na_trad!!
	</div>
	
	<!--categ_parent -->
	<!-- renvoivoir -->
	<!-- renvoivoiraussi -->
	<div class='row'>
		<div class='left'>
			<div class='row'>
				<label class='etiquette' >".htmlentities($msg[categ_num_aut], ENT_QUOTES, $charset)."</label>
			</div>
			<div class='row'>
				<!-- numero_autorite -->
			</div>
		</div>
		<div class='right'>
			<!-- imprimer_thesaurus -->
		</div>
	</div>
	<!-- aut_link -->
	<div class='row'>
	</div>
</div>

<!--boutons	-->
<div class='row'>
	<div class='left'>
		<input type='button' class='bouton' value='$msg[76]' onClick=\"unload_off();document.location='./autorites.php?categ=categories&sub=&parent=!!id_parent!!'\" />
		<input type='submit' class='bouton' value='$msg[77]' onClick=\"return test_form(this.form)\" />
		<!-- remplace_categ -->
		!!voir_notices!!
	</div>
	<div class='right'>
		<!-- delete_button -->
	</div>
</div>
<div class='row'></div>
</form>
<script type='text/javascript'>
	document.forms['categ_form'].elements['category_libelle!!lang_def_cle!!'].focus();

	function bascule_trad(item) {
		var elt = document.getElementById(item);
		if (elt.style.display == 'none') elt.style.display = ''; else elt.style.display = 'none'; 
	}
</script>";

$select_categ_prop = "scrollbars=yes, toolbar=no, dependent=yes, resizable=yes";

$add_see_also="
<script>
	function fonction_selecteur_categ() {
		name=this.getAttribute('id').substring(4);
		name_id = name.substr(0,7)+'_id'+name.substr(7);
		openPopUp('./select.php?what=categorie&caller=categ_form&p1='+name_id+'&p2='+name+'&dyn=1', 'select_categ', 700, 500, -2, -2, '$select_categ_prop');
	}
	function fonction_raz_categ() {
		name=this.getAttribute('id').substring(4);
		name_id = name.substr(0,7)+'_id'+name.substr(7);
		name_rec = name.substr(0,7)+'_rec'+name.substr(7);
		document.getElementById(name_id).value=0;
		document.getElementById(name).value='';
		document.getElementById(name_rec).checked=false;
	}
	function add_categ() {
		template = document.getElementById('addcateg');
		categ=document.createElement('div');
		categ.className='row';

		suffixe = eval('document.categ_form.max_categ.value')
		nom_id = 'f_categ'+suffixe
		f_categ = document.createElement('input');
		f_categ.setAttribute('name',nom_id);
		f_categ.setAttribute('id',nom_id);
		f_categ.setAttribute('type','text');
		f_categ.className='saisie-80emr';
		f_categ.setAttribute('readonly','');
		f_categ.setAttribute('value','');
		
		f_categ_rec = document.createElement('input');
		f_categ_rec.name = 'f_categ_rec'+suffixe;
		f_categ_rec.setAttribute('id','f_categ_rec'+suffixe);
		f_categ_rec.setAttribute('type','checkbox');
		f_categ_rec.setAttribute('value','1');		

		del_f_categ = document.createElement('input');
		del_f_categ.setAttribute('id','del_f_categ'+suffixe);
		del_f_categ.onclick=fonction_raz_categ;
		del_f_categ.setAttribute('type','button');
		del_f_categ.className='bouton_small';
		del_f_categ.setAttribute('readonly','');
		del_f_categ.setAttribute('value','$msg[raz]');
		
		f_categ_id = document.createElement('input');
		f_categ_id.name='f_categ_id'+suffixe;
		f_categ_id.setAttribute('type','hidden');
		f_categ_id.setAttribute('id','f_categ_id'+suffixe);
		f_categ_id.setAttribute('value','');
		
		categ.appendChild(f_categ);
		space=document.createTextNode(' ');
		categ.appendChild(space);
		categ.appendChild(f_categ_rec);
		categ.appendChild(space);
		categ.appendChild(del_f_categ);
		categ.appendChild(f_categ_id);

		template.appendChild(categ);
		
		document.categ_form.max_categ.value=suffixe*1+1*1 ;
	}
</script>";
	
$categ0 = "
	<div class='row'>
		<input type='text' class='saisie-80emr' id='f_categ!!icateg!!' name='f_categ!!icateg!!' readonly value=\"!!categ_libelle!!\" /><input type='checkbox' id='f_categ_rec!!icateg!!' name='f_categ_rec!!icateg!!' !!chk!! />&nbsp;<input type='button' class='bouton_small' value='$msg[raz]' onclick=\"this.form.f_categ!!icateg!!.value=''; this.form.f_categ_id!!icateg!!.value='0'; this.form.f_categ_rec!!icateg!!.checked=false; \" />
		<input type='button' class='bouton_small' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=categorie&caller=categ_form&p1=f_categ_id!!icateg!!&p2=f_categ!!icateg!!&dyn=1&parent=!!parent!!&id2=!!id!!', 'select_categ', 700, 500, -2, -2, '$select_categ_prop')\" />
		<input type='hidden' name='f_categ_id!!icateg!!' id='f_categ_id!!icateg!!' value='!!categ_id!!' /><input type='button' class='bouton_small' value='+' onClick=\"add_categ();\"/>
	</div>";
	
$categ1 = "
	<div class='row'>
		<input type='text' class='saisie-80emr' id='f_categ!!icateg!!' name='f_categ!!icateg!!' readonly value=\"!!categ_libelle!!\" /><input type='checkbox' id='f_categ_rec!!icateg!!' name='f_categ_rec!!icateg!!' !!chk!! />&nbsp;<input type='button' class='bouton_small' value='$msg[raz]' onclick=\"this.form.f_categ!!icateg!!.value=''; this.form.f_categ_id!!icateg!!.value='0'; \" /><input type='hidden' name='f_categ_id!!icateg!!' id='f_categ_id!!icateg!!' value='!!categ_id!!' />
	</div>";

$form_categ_parent = "
	<div class='row'>
		<label class='etiquette' for='form_categparent'>".htmlentities($msg[categ_parent], ENT_QUOTES, $charset)."</label>
	</div>
	<div class='row'>
		<input type='text' class='saisie-80emr' name='category_parent' readonly value=\"!!parent_libelle!!\" />
		<input type='button' class='bouton_small' value='$msg[raz]' onclick=\"this.form.category_parent.value=''; this.form.category_parent_id.value='0'; \" />
		<input type='button' class='bouton_small' onclick=\"openPopUp('./select.php?what=categorie&caller=categ_form&p1=category_parent_id&p2=category_parent&keep_tilde=1&parent=!!parent!!&id2='+document.categ_form.category_parent_id.value, 'select_categ', 700, 500, -2, -2, '$select_categ_prop')\" title='$msg[157]' value='$msg[parcourir]' />
		<input type='hidden' name='category_parent_id' value='!!parent_value!!' />
	</div>";

$form_renvoivoir = "
	<div class='row'>
		<label class='etiquette' for='form_renvoivoir'>".htmlentities($msg[categ_renvoi], ENT_QUOTES, $charset)."</label>
	</div>
	<div class='row'>
		<input type='text' class='saisie-80emr' name='category_voir' size='48' readonly value=\"!!voir_libelle!!\" />
		<input type='button' class='bouton_small' value='$msg[raz]' onclick=\"this.form.category_voir.value=''; this.form.category_voir_id.value='0'; \" />
		<input type='button' class='bouton_small' onclick=\"openPopUp('./select.php?what=categorie&caller=categ_form&p1=category_voir_id&p2=category_voir&parent=!!parent!!&id2='+document.categ_form.category_voir_id.value, 'select_categ', 700, 500, -2, -2, '$select_categ_prop')\" title='$msg[157]' value='$msg[parcourir]' />
		<input type='hidden' name='category_voir_id' value='!!voir_value!!' />
	</div>";

$form_renvoivoiraussi = "
	<div class='row'>
		<label class='etiquette' for='form_renvoivoir'>".$msg['renvoi_voir_aussi'].$msg['renvoi_reciproque']."</label>
	</div>
	!!renvoi_voir_aussi!!";

$form_num_aut = "
	<input type='text' class='saisie-20em' id='num_aut' name='num_aut' value=\"!!num_aut!!\" />";
	
// $categ_replace : form remplacement categorie
$form_categ_replace = "
<script src='javascript/ajax.js'></script>
<form class='form-$current_module' name='categ_replace' method='post' action='./autorites.php?categ=categories&sub=categ_replace&id=!!id!!&parent=!!parent!!' onSubmit=\"return false\" >
<h3>$msg[159] !!old_categ_libelle!! </h3>
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='par'>".htmlentities($msg[160], ENT_QUOTES, $charset)."</label>
	</div>
	<div class='row'>
		<input type='text' class='saisie-80emr' name='by_libelle' id='by_libelle' value=\"\" completion=\"categories_mul\" autfield=\"by\" />
		<input type='button' class='bouton_small' value='$msg[raz]' onclick=\"this.form.by_libelle.value=''; this.form.by.value='0'; \" />
		<input type='button' class='bouton_small' onclick=\"openPopUp('./select.php?what=categorie&caller=categ_replace&p1=by&p2=by_libelle&keep_tilde=1&parent=0&deb_rech='+".pmb_escape()."(this.form.by_libelle.value), 'select_categ', 700, 500, -2, -2, '$select_categ_prop')\" value='$msg[parcourir]' />
		<input type='hidden' name='by' id='by' value='0'>
	</div>
	<div class='row'>		
		<input id='aut_link_save' name='aut_link_save' type='checkbox'  value='1'>".$msg["aut_replace_link_save"]."
	</div>	
</div>
<div class='row'>
	<input type='button' class='bouton' value='$msg[76]' onClick=\"document.location='./autorites.php?categ=categories&sub=categ_form&id=!!id!!&parent=!!parent!!';\">
	<input type='button' class='bouton' value='$msg[159]' id='btsubmit' onClick=\"this.form.submit();\" >
	</div>
</form>
<script type='text/javascript'>
	ajax_parse_dom();
	document.forms['categ_replace'].elements['by_libelle'].focus();
</script>
";
?>