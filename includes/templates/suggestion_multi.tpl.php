<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: suggestion_multi.tpl.php,v 1.9 2009-12-28 15:34:12 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

$multi_sug_form= "<div id='make_mul_sugg'>
<h1>".htmlentities($msg['acquisition_sug_ges'], ENT_QUOTES, $charset)."</h1>
<div id='make_mul_sugg-container'>
<script src='./javascript/suggestion_multi.js' type='text/javascript'></script>
<script>
	function check_fields(nb_ligne){
	var retour=true;
	var qte_error = false;
	var txt_error = false;

	for(var i=0;i<nb_ligne;i++){
		if (document.getElementById('sugg_'+i)){
			if((document.getElementById('sugg_tit_'+i).disabled == true) && (i==0) ){
				alert(\"".$msg[sugg_no_field_fill]."\");
				return false;
			} else if(document.getElementById('sugg_tit_'+i).disabled == true) 
					break;	
			
			var tit = document.getElementById('sugg_tit_'+i).value;
			var aut = document.getElementById('sugg_aut_'+i).value;
			var edi = document.getElementById('sugg_edi_'+i).value;
			var qte = document.getElementById('sugg_qte_'+i).value;
			var cod = document.getElementById('sugg_code_'+i).value;
			if(!tit || (!aut && !edi && !cod)){
				document.getElementById('sugg_'+i).className = 'erreur_saisie';
				retour=false;
				txt_error = true;
			} else if(isNaN(qte)){
				document.getElementById('sugg_'+i).className = 'erreur_saisie';
				retour=false;
				qte_error = true;
			} else{
				document.getElementById('sugg_'+i).className = '';
			}
		}
	}

	if(qte_error){
		alert('".$msg[acquisition_sugg_qte_error]."');
	} else if(txt_error){
		alert(\"".$msg[acquisition_sug_ko]."\");
	}
		
	return retour;
}
</script>
<form action=\"acquisition.php?categ=sug&sub=multi\" method=\"post\" name=\"sug_multi\" onsubmit=\"return check_fields(document.getElementById('max_nblignes').value);\"> 
	<h3>".htmlentities($msg['acquisition_make_mul_sugg'], ENT_QUOTES, $charset)."</h3>
	<input type='hidden' name='act' id='act' />
	<input type='hidden' name='max_nblignes' id='max_nblignes' value='!!max_lignes!!'/>
	<div class='form-contenu'>
		<table class='tab_sug'>
			<tbody id='tableau_multi_sugg'>
			<tr>
				<th style='width:15%'>".htmlentities($msg["acquisition_sugg_tit"], ENT_QUOTES, $charset)."</th>
				<th style='width:15%'>".htmlentities($msg["acquisition_sugg_aut"], ENT_QUOTES, $charset)."</th>
				<th style='width:12%'>".htmlentities($msg["acquisition_sugg_edi"], ENT_QUOTES, $charset)."</th>
				<th style='width:8%'>".htmlentities($msg["acquisition_sugg_code"], ENT_QUOTES, $charset)."</th>
				<th style='width:5%'>".htmlentities($msg["acquisition_sugg_prix"], ENT_QUOTES, $charset)."</th>
				<th style='width:12%'>".htmlentities($msg["acquisition_sugg_url"], ENT_QUOTES, $charset)."</th>
				<th style='width:15%'>".htmlentities($msg["acquisition_sugg_comment"], ENT_QUOTES, $charset)."</th>
				<th style='width:7%'>".htmlentities($msg["acquisition_sugg_date_publication"], ENT_QUOTES, $charset)."</th>
				<th style='width:10%'>".htmlentities($msg["acquisition_sugg_src"], ENT_QUOTES, $charset)."</th>
				<th style='width:3%'>".htmlentities($msg["acquisition_sugg_qte"], ENT_QUOTES, $charset)."</th>
				<th style='width:1%'></th>
			</tr>
			!!ligne!!
			</tbody>
		</table>
		<div class='row'>
			<div class='colonne3'>
				<label class='etiquette'>".htmlentities($msg['acquisition_sugg_origine_user'], ENT_QUOTES, $charset)."</label>					
				<input type='hidden' id='id_user' name='id_user' value='!!id_user!!' />
				<input type='hidden' id='type_user' name='type_user' value='!!type_user!!' />
				<input type='text' id='user_txt' name='user_txt' class='saisie-20emr' value='!!user_txt!!'/>
				<input type='button' class='bouton_small' value='X'  onclick=\"this.form.id_user.value=0;this.form.type_user.value=0;this.form.user_txt.value=''\"/>
				<input type='button' class='bouton_small' value='...' onclick=\"openPopUp('./select.php?what=origine&caller=sug_multi&param1=id_user&param2=user_txt&param3=type_user&deb_rech='+escape(this.form.user_txt.value), 'select_user', 400, 400, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes')\" />				
			</div>
			<div class='colonne3'>
				!!localisation!!
			</div>
			<div class='colonne3'>
				!!categorie!!
			</div>			
		</div>	
		<div class='row'></div>		
	</div>	
	<div class='row'>
		<div class='left'>
			<input type='submit' class='bouton' name='save_multi_sugg' value='$msg[acquisition_sugg_save_multi]' onclick='this.form.act.value=\"save_multi_sugg\";' />
		</div>
	</div>
	<div class='row'></div>
</form>
</div>
</div>";


$import_sug_form="
<div id='import_sug'>
<h1>".htmlentities($msg['acquisition_sug_ges'], ENT_QUOTES, $charset)."</h1>
<div id='import_sug-container'>
<form action=\"acquisition.php?categ=sug&sub=import\" method=\"post\" enctype=\"multipart/form-data\"> 
	<h3>".htmlentities($msg['acquisition_sugg_import_title'], ENT_QUOTES, $charset)."</h3>
	<input type='hidden' name='act' id='act' />
	<input type='hidden' name='explnum_id' value='!!explnum_id!!' /> 
	<input type='hidden' name='origine_id' value='!!origine_id!!' />
	<input type='hidden' name='type_origine' value='!!type_origine!!' />
	<div class='form-contenu'>
		<div class='row'>
			<label for='import file' class='etiquette'>$msg[acquisition_sugg_file_to_import]</label>
		</div>
		<div class='row'>
			!!import_file!!	
		</div>
		<div class='row'>
			<label for='src_liste' class='etiquette'>$msg[acquisition_sugg_srcliste]</label>
		</div>
		<div class='row'>
			!!liste_source!!
		</div>
		<div class='row'>
			<label for='import_liste' class='etiquette'>$msg[acquisition_sugg_importliste]</label>
		</div>
		<div class='row'>
			!!liste_import!!
		</div>
	</div>
	<input type='button' class='bouton' value=\"".$msg[76]."\" onClick=\"document.location='acquisition.php?categ=sug&action=list' \"/>&nbsp;<input type='submit' class='bouton' name='import_sugg' value='$msg[acquisition_sugg_btn_import]' onclick='this.form.act.value=\"import_sugg\"' />
</form>
</div>
</div>"
?>