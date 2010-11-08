<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: search.tpl.php,v 1.23 2010-06-21 09:14:04 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

//Template du formulaire de recherches avancées
$search_form="
<script src=\"javascript/ajax.js\"></script>
<form class='form-$current_module' name='search_form' action='!!url!!' method='post' onsubmit='return false' >
	<h3><div class='left'>".($mode==8?$msg["search_expl"]:(($mode==6||$categ=='consult')?$msg["search_extended"]:($_SESSION["ext_type"]=="simple"?$msg["connecteurs_external_simple"]:$msg["connecteurs_external_multi"])))."</div><!--!!precise_h3!!--><div class='row'></div></h3>
	<div class='form-contenu'>
		<!--!!before_form!!--> 
		<div class='row'>!!limit_search!!";
if(!$limited_search){
	$search_form .= "
			<label class='etiquette' for='add_field'>".$msg["search_add_field"]."</label> !!field_list!! ";
	if(!$pmb_extended_search_auto){	
		$search_form .="	<input type='button' class='bouton' value='".$msg["925"]."' onClick=\"if (this.form.add_field.value!='') { this.form.action='!!url!!'; this.form.target=''; this.form.submit();} else { alert('".$msg["multi_select_champ"]."'); }\"/>";
	}
}
$search_form .=" </div>
 <br />
		<div class='row'>
			!!already_selected_fields!!
		</div>
	</div>";
if($mode==8)$search_form.="<!--!!limitation_affichage!!-->";		


if( $mode!=7 && $mode!=8 ) $search_form.="
	<div class='row'>
			<input type='button' class='bouton' value='".$msg["142"]."' onClick=\"this.form.launch_search.value=1; this.form.action='!!result_url!!'; this.form.page.value=''; !!target_js!! this.form.submit()\"/>
			".(($categ=='consult') ? "" : "<input type='button' class='bouton' value='".$msg["search_perso_save"]."' onClick=\"this.form.launch_search.value=1; this.form.action='!!memo_url!!'; this.form.page.value=''; !!target_js!! this.form.submit()\"/>")."
	</div>";

if($mode==7 || $mode==8) $search_form.=	"
	<div class='row'>
		<div class='left'>
			<input type='button' class='bouton' value='".$msg["142"]."' onClick=\"this.form.launch_search.value=1; this.form.action='!!result_url!!'; this.form.page.value=''; !!target_js!! this.form.submit()\"/>
		</div>
		
		<div class='row'/>	
	</div>";
		
$search_form.="
	<input type='hidden' name='delete_field' value=''/>
	<input type='hidden' name='launch_search' value=''/>
	<input type='hidden' name='page' value='!!page!!'/>
	<input type='hidden' name='id_equation' value='!!id_equation!!'/>
	<input type='hidden' name='priv_pro' value='$priv_pro'/>
	<input type='hidden' name='id_empr' value='$id_empr'/>
	<input type='hidden' name='id_connector_set' value='!!id_connector_set!!'/>
</form>
<script>ajax_parse_dom();</script>

";

//<input type='submit' class='bouton' value='".$msg["142"]."' onClick=\"this.form.launch_search.value=1; this.form.action='!!result_url!!'; this.form.page.value=''; !!target_js!! \"/>
?>