<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: cbgenlibre.inc.php,v 1.17 2010-05-05 15:06:04 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// récupération des valeurs par défaut:
if ($pmb_param_etiq_codes_barres) $mep_etiq_cb=unserialize($pmb_param_etiq_codes_barres);
	else $mep_etiq_cb=array();

$sel_type=stripslashes($sel_type);

if($action =="delete" && $sel_type!="default" && $sel_type!="new" && $mep_etiq_cb[$sel_type]) {
	unset($mep_etiq_cb[$sel_type]);
	// update param dans pmb_param_etiq_codes_barres
	$pmb_param_etiq_codes_barres=	serialize($mep_etiq_cb);
	$req="UPDATE parametres set valeur_param='".addslashes($pmb_param_etiq_codes_barres)."' where type_param='pmb' and sstype_param='param_etiq_codes_barres' limit 1";
	mysql_query($req);	
	$sel_type="default";
}

// cas de mémorisation du formulaire 
if($action =="memo") {
	$sel_type=stripslashes($type_cb_libelle);
	$mep_etiq_cb[$sel_type]=array();
	$mep_etiq_cb[$sel_type]['type_cb_name']=stripslashes($type_cb_name);
	$mep_etiq_cb[$sel_type]['type_cb_libelle']=stripslashes($type_cb_libelle);
	$mep_etiq_cb[$sel_type][bibli_name]=stripslashes($bibli_name);
	$mep_etiq_cb[$sel_type][nbr_cb]=$nbr_cb;
	$mep_etiq_cb[$sel_type][ORIENTATION]=$ORIENTATION;
	$mep_etiq_cb[$sel_type][CBG_NBR_X_CELLS]=$CBG_NBR_X_CELLS;
	$mep_etiq_cb[$sel_type][CBG_NBR_Y_CELLS]=$CBG_NBR_Y_CELLS;
	$mep_etiq_cb[$sel_type][CBG_LEFT_MARGIN]=$CBG_LEFT_MARGIN;
	$mep_etiq_cb[$sel_type][CBG_RIGHT_MARGIN]=$CBG_RIGHT_MARGIN;
	$mep_etiq_cb[$sel_type][CBG_TOP_MARGIN]=$CBG_TOP_MARGIN;
	$mep_etiq_cb[$sel_type][CBG_BOTTOM_MARGIN]=$CBG_BOTTOM_MARGIN;
	$mep_etiq_cb[$sel_type][CBG_INNER_LEFT_MARGIN]=$CBG_INNER_LEFT_MARGIN;
	$mep_etiq_cb[$sel_type][CBG_INNER_RIGHT_MARGIN]=$CBG_INNER_RIGHT_MARGIN;
	$mep_etiq_cb[$sel_type][CBG_INNER_TOP_MARGIN]=$CBG_INNER_TOP_MARGIN;
	$mep_etiq_cb[$sel_type][CBG_INNER_BOTTOM_MARGIN]=$CBG_INNER_BOTTOM_MARGIN;
	$mep_etiq_cb[$sel_type][CBG_TEXT_HEIGHT]=$CBG_TEXT_HEIGHT;
	$mep_etiq_cb[$sel_type][CBG_TEXT_FONT_SIZE]=$CBG_TEXT_FONT_SIZE;
	$mep_etiq_cb[$sel_type][CBG_CB_TEXT_SIZE]=$CBG_CB_TEXT_SIZE;
	$mep_etiq_cb[$sel_type][CBG_CB_RES]=$CBG_CB_RES;	
	
	// update param dans pmb_param_etiq_codes_barres
	$pmb_param_etiq_codes_barres=	serialize($mep_etiq_cb);
	$req="UPDATE parametres set valeur_param='".addslashes($pmb_param_etiq_codes_barres)."' where type_param='pmb' and sstype_param='param_etiq_codes_barres' limit 1";
	//print $req;
	mysql_query($req);
}

if(!$sel_type)$sel_type="default";
if(!is_array($mep_etiq_cb['default']) || $sel_type=="new") {
	$mep_etiq_cb[$sel_type]=array();
	if( $sel_type!="new")$mep_etiq_cb[$sel_type]['type_cb_name']=$msg['edit_cbgen_name_default'];
	if( $sel_type!="new")$mep_etiq_cb[$sel_type]['type_cb_libelle']="default"; 
	if (!$mep_etiq_cb[$sel_type][bibli_name]                  ) $mep_etiq_cb[$sel_type][bibli_name]=stripslashes($biblio_name);
	if (!$mep_etiq_cb[$sel_type][nbr_cb]                      ) $mep_etiq_cb[$sel_type][nbr_cb]=50;
	if (!$mep_etiq_cb[$sel_type][ORIENTATION]                 ) $mep_etiq_cb[$sel_type][ORIENTATION]='P';
	if (!$mep_etiq_cb[$sel_type][CBG_NBR_X_CELLS]             ) $mep_etiq_cb[$sel_type][CBG_NBR_X_CELLS]='4';
	if (!$mep_etiq_cb[$sel_type][CBG_NBR_Y_CELLS]             ) $mep_etiq_cb[$sel_type][CBG_NBR_Y_CELLS]='19';
	if (!$mep_etiq_cb[$sel_type][CBG_LEFT_MARGIN]             ) $mep_etiq_cb[$sel_type][CBG_LEFT_MARGIN]='6';
	if (!$mep_etiq_cb[$sel_type][CBG_RIGHT_MARGIN]            ) $mep_etiq_cb[$sel_type][CBG_RIGHT_MARGIN]='6';
	if (!$mep_etiq_cb[$sel_type][CBG_TOP_MARGIN]              ) $mep_etiq_cb[$sel_type][CBG_TOP_MARGIN]='13';
	if (!$mep_etiq_cb[$sel_type][CBG_BOTTOM_MARGIN]           ) $mep_etiq_cb[$sel_type][CBG_BOTTOM_MARGIN]='13';
	if (!$mep_etiq_cb[$sel_type][CBG_INNER_LEFT_MARGIN]       ) $mep_etiq_cb[$sel_type][CBG_INNER_LEFT_MARGIN]='4';
	if (!$mep_etiq_cb[$sel_type][CBG_INNER_RIGHT_MARGIN]      ) $mep_etiq_cb[$sel_type][CBG_INNER_RIGHT_MARGIN]='4';
	if (!$mep_etiq_cb[$sel_type][CBG_INNER_TOP_MARGIN]        ) $mep_etiq_cb[$sel_type][CBG_INNER_TOP_MARGIN]='1';
	if (!$mep_etiq_cb[$sel_type][CBG_INNER_BOTTOM_MARGIN]     ) $mep_etiq_cb[$sel_type][CBG_INNER_BOTTOM_MARGIN]='1';
	if (!$mep_etiq_cb[$sel_type][CBG_TEXT_HEIGHT]             ) $mep_etiq_cb[$sel_type][CBG_TEXT_HEIGHT]='2';
	if (!$mep_etiq_cb[$sel_type][CBG_TEXT_FONT_SIZE]          ) $mep_etiq_cb[$sel_type][CBG_TEXT_FONT_SIZE]='6';
	if (!$mep_etiq_cb[$sel_type][CBG_CB_TEXT_SIZE]            ) $mep_etiq_cb[$sel_type][CBG_CB_TEXT_SIZE]='3';
	if (!$mep_etiq_cb[$sel_type][CBG_CB_RES]                  ) $mep_etiq_cb[$sel_type][CBG_CB_RES]='1';
	if($sel_type!="new") {
		$pmb_param_etiq_codes_barres=	serialize($mep_etiq_cb);
		$req="UPDATE parametres set valeur_param='".addslashes($pmb_param_etiq_codes_barres)."' where type_param='pmb' and sstype_param='param_etiq_codes_barres' limit 1";
		//print $req;
		mysql_query($req);	
	}	
}
if ($mep_etiq_cb[$sel_type][ORIENTATION]=='P') $selected_mep_orientation_P="selected";
		else $selected_mep_orientation_L="selected";
		
if( $sel_type == 'default' )	$selected= " selected='selected' "; else $selected='';
$sel_type_tpl="
	<select name='sel_type' size='1' onchange='type_change(this);'>
	  <option value='default' $selected>".$msg['edit_cbgen_name_default']."</option>";	  
	  foreach($mep_etiq_cb as $type) {
	  	if(is_array($type)) {	  
		  	if( $type['type_cb_libelle'] == $sel_type  )	$selected= " selected='selected' "; else $selected='';
		  	if( $type['type_cb_libelle'] && $type['type_cb_libelle']!='default' ) $sel_type_tpl.="<option value='".htmlentities($type['type_cb_libelle'],ENT_QUOTES,$charset)."' $selected>".htmlentities($type['type_cb_name'],ENT_QUOTES,$charset)."</option>	";
	  	}
	  }
if( $sel_type == 'new' )	$selected= " selected='selected' "; else $selected='';	  	  
$sel_type_tpl.="	  
	  <option value='new' $selected >".$msg['edit_cbgen_name_new']."</option>
</select>
";
	  
if( $sel_type != 'default' )$button_memorise="<input class='bouton' type='button' value='".$msg['edit_cbgen_save']."' onClick=\"submit_memorise();\"/>";
if( $sel_type != 'default' && $sel_type != 'new' )$button_delete="<input class='bouton' type='button' value='".$msg['edit_cbgen_delete']."' onClick=\"confirm_delete();\"/>";

// $cbgen_query : form de demande d'info pour génération
$cbgen_query = "
<script type='text/javascript'>
<!--
	function test_form(form)
	{
		if (form.source[0].checked)     // autoinc
		{
			if (form.cb_first.value == '')
			{
				alert(\"$msg[851]\");
				form.cb_first.focus();
				return false;
			}
			if ((parseInt(form.nbr_cb.value) < 0) || (form.nbr_cb.value == ''))
			{
				alert(\"$msg[850]\");
				form.nbr_cb.focus();
				return false;
			}
		}
		else if (form.source[1].checked)     // fromfile
		{
			if (form.userfile.value == '')
			{
				alert(\"$msg[852]\");
				form.userfile.focus();
				return false;
			}
		}
		return true;
	}
	
	function test_memorise(form)
	{
		if (form.type_cb_name.value == '') {
			alert(\"".$msg['edit_cbgen_cb_name_invalid']."\");
			form.type_cb_name.focus();
			return false;
		}
		if (form.type_cb_libelle.value == '' || form.type_cb_libelle.value == 'default'  || form.type_cb_libelle.value == 'new')
		{
			form.type_cb_libelle.value ='';
			alert(\"".$msg['edit_cbgen_cb_libelle_invalid']."\");
			form.type_cb_libelle.focus();
			return false;
		}			
		return true;
	}
	function type_change(selectBox) {
		id=selectBox.options[selectBox.selectedIndex].value;
		document.location='./edit.php?categ=cbgen&sub=$sub&sel_type='+escape(id);
	}	
	function submit_genere() {
		if(test_form(document.forms['cbgen_query'])) {
			document.forms['cbgen_query'].submit();	
		}			
	}
	function submit_memorise() {
		if(test_memorise(document.forms['cbgen_query'])) {
			document.forms['cbgen_query'].setAttribute('action', './edit.php?categ=cbgen&sub=$sub&sel_type=".rawurlencode($sel_type)."&action=memo');
			document.forms['cbgen_query'].submit();
		}			
	}
	function confirm_delete() {
		result = confirm(\"".$msg["edit_cbgen_delete_confirm"]."\");
		if(result)	document.location='./edit.php?categ=cbgen&sub=$sub&action=delete&sel_type=".rawurlencode($sel_type)."';
	}
-->
</script>

<form class='form-$current_module' id = 'cbgen_query' name='cbgen_query' method='post' enctype='multipart/form-data' action='edit/generate.php' >
<div class='form-contenu'>

	<div class='row'>
		<label class='etiquette' for='bibli_name'>$msg[800]</label><br />
		<input class='saisie-80em' id='bibli_name' type='text' name='bibli_name' value=\"".htmlentities($mep_etiq_cb[$sel_type]["bibli_name"],ENT_QUOTES,$charset)."\" />
	</div>

	<div class='row'>
		<input type='radio' name='source' value='autoinc' checked='checked' id='source' /><span>$msg[808]</span>
	</div>


	<div class='row'>
		<blockquote><label class='etiquette' for='cb_first'>$msg[801]</label><br />
		<input class='saisie-20em' type='text' name='cb_first' id='cb_first' /></blockquote>
	</div>

	<div class='row'>
		<blockquote><label class='etiquette' for='nbr_cb'>$msg[802]</label><br />
		<input class='saisie-20em' type='text' name='nbr_cb' id='nbr_cb' value=\"".$mep_etiq_cb[$sel_type][nbr_cb]."\" /></blockquote>
	</div>

	<div class='row'>
		<input type='radio' name='source' value='fromfile' id='source' /><span>$msg[809]</span>
	</div>

	<div class='row'>
		<blockquote><label class='etiquette' for='userfile'>$msg[807]</label><br />
		<input type='file' name='userfile' size='80' id='userfile' /></blockquote>
	</div>

	<div class='row' id='show_layout_button'>$msg[edit_cbgen_mep_etiq]
	$sel_type_tpl
	<input type='button' class='bouton' value='$msg[edit_cbgen_mep_afficher]' onClick=\"javascript:document.getElementById('layout_mep').style.display='block';document.getElementById('show_layout_button').style.display='none'\">
	</div>
<!-- A déplacer dans le fichier langue -->
<div id='layout_mep' style='display:none;'>$msg[edit_cbgen_mep_etiq]
<input type='button' class='bouton' value='$msg[edit_cbgen_mep_masquer]' onClick=\"javascript:document.getElementById('layout_mep').style.display='none';document.getElementById('show_layout_button').style.display='block'\"><br />
<hr />
<!-- Ajout du changement possible de format de page -->
<label class='etiquette'>$msg[edit_cbgen_type_cb_label] </label>
<input class='saisie-20em' id='type_cb_name' type='text' class='text' name='type_cb_name' value=\"".htmlentities($mep_etiq_cb[$sel_type]['type_cb_name'],ENT_QUOTES,$charset)."\" />
<label class='etiquette'>$msg[edit_cbgen_type_cb_libelle] </label> 
<input class='saisie-20em' id='type_cb_libelle' type='text' class='text' name='type_cb_libelle' value=\"".htmlentities($mep_etiq_cb[$sel_type]['type_cb_libelle'],ENT_QUOTES,$charset)."\" />
<br /><br />
<label class='etiquette'>$msg[edit_cbgen_mep_orientation] </label>
<select name='ORIENTATION' size='1'>
  <option value='P' $selected_mep_orientation_P>$msg[edit_cbgen_mep_portrait]</option>
  <option value='L' $selected_mep_orientation_L>$msg[edit_cbgen_mep_paysage]</option>
</select><br />
<label class='etiquette'>$msg[edit_cbgen_mep_nbr_x_cells]</label><br />
<input class='saisie-20em' id='CBG_NBR_X_CELLS' type='text' class='text' name='CBG_NBR_X_CELLS' value=\"".$mep_etiq_cb[$sel_type][CBG_NBR_X_CELLS]."\"/><br />

<label class='etiquette'>$msg[edit_cbgen_mep_nbr_y_cells]</label><br />
<input class='saisie-20em' id='CBG_NBR_Y_CELLS' type='text' class='text' name='CBG_NBR_Y_CELLS' value=\"".$mep_etiq_cb[$sel_type][CBG_NBR_Y_CELLS]."\" /><br />

<label class='etiquette'>$msg[edit_cbgen_mep_margin]</label><br />
<input class='saisie-20em' id='CBG_LEFT_MARGIN' type='text' class='text' name='CBG_LEFT_MARGIN' value=\"".$mep_etiq_cb[$sel_type][CBG_LEFT_MARGIN]."\" /> $msg[edit_cbgen_mep_left]<br />
<input class='saisie-20em' id='CBG_RIGHT_MARGIN' type='text' class='text' name='CBG_RIGHT_MARGIN' value=\"".$mep_etiq_cb[$sel_type][CBG_RIGHT_MARGIN]."\" /> $msg[edit_cbgen_mep_right]<br />
<input class='saisie-20em' id='CBG_TOP_MARGIN' type='text' class='text' name='CBG_TOP_MARGIN' value=\"".$mep_etiq_cb[$sel_type][CBG_TOP_MARGIN]."\" /> $msg[edit_cbgen_mep_top]<br />
<input class='saisie-20em' id='CBG_BOTTOM_MARGIN' type='text' class='text' name='CBG_BOTTOM_MARGIN' value=\"".$mep_etiq_cb[$sel_type][CBG_BOTTOM_MARGIN]."\" /> $msg[edit_cbgen_mep_bottom]<br />

<label class='etiquette'>$msg[edit_cbgen_mep_inner_margin]</label><br />
<input class='saisie-20em' id='CBG_INNER_LEFT_MARGIN' type='text' class='text' name='CBG_INNER_LEFT_MARGIN' value=\"".$mep_etiq_cb[$sel_type][CBG_INNER_LEFT_MARGIN]."\" /> $msg[edit_cbgen_mep_left]<br />
<input class='saisie-20em' id='CBG_INNER_RIGHT_MARGIN' type='text' class='text' name='CBG_INNER_RIGHT_MARGIN' value=\"".$mep_etiq_cb[$sel_type][CBG_INNER_RIGHT_MARGIN]."\" /> $msg[edit_cbgen_mep_right]<br />
<input class='saisie-20em' id='CBG_INNER_TOP_MARGIN' type='text' class='text' name='CBG_INNER_TOP_MARGIN' value=\"".$mep_etiq_cb[$sel_type][CBG_INNER_TOP_MARGIN]."\" /> $msg[edit_cbgen_mep_top]<br />
<input class='saisie-20em' id='CBG_INNER_BOTTOM_MARGIN' type='text' class='text' name='CBG_INNER_BOTTOM_MARGIN' value=\"".$mep_etiq_cb[$sel_type][CBG_INNER_BOTTOM_MARGIN]."\" /> $msg[edit_cbgen_mep_bottom]<br />

<label class='etiquette'>$msg[edit_cbgen_mep_text_height]</label><br />
<input class='saisie-20em' id='CBG_TEXT_HEIGHT' type='text' class='text' name='CBG_TEXT_HEIGHT' value=\"".$mep_etiq_cb[$sel_type][CBG_TEXT_HEIGHT]."\" /><br />
<label class='etiquette'>$msg[edit_cbgen_mep_text_font_size]</label><br />
<input class='saisie-20em' id='CBG_TEXT_FONT_SIZE' type='text' class='text' name='CBG_TEXT_FONT_SIZE' value=\"".$mep_etiq_cb[$sel_type][CBG_TEXT_FONT_SIZE]."\" /><br />
<label class='etiquette'>$msg[edit_cbgen_mep_text_size]</label><br />
<input class='saisie-20em' id='CBG_CB_TEXT_SIZE' type='text' class='text' name='CBG_CB_TEXT_SIZE' value=\"".$mep_etiq_cb[$sel_type][CBG_CB_TEXT_SIZE]."\" /><br />
<label class='etiquette'>$msg[edit_cbgen_mep_cb_res]</label><br />
$msg[edit_cbgen_mep_cb_res_details]<br />
<input class='saisie-20em' id='CBG_CB_RES' type='text' class='text' name='CBG_CB_RES' value=\"".$mep_etiq_cb[$sel_type][CBG_CB_RES]."\" /><br />
$msg[edit_cbgen_mep_cb_res_note]<br />

</div>

</div>
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value='$msg[804]' onClick=\"submit_genere();\"/>
		$button_memorise
	</div>
	<div class='right'>
		$button_delete
	</div>	
</div>
</form>

<script type='text/javascript'>
	document.forms['cbgen_query'].elements['bibli_name'].focus();
</script>
";

print $cbgen_query;

?>
