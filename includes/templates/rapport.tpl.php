<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: rapport.tpl.php,v 1.3 2009-10-13 07:29:33 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

$form_rapport = "
<script src='./javascript/drag_n_drop.js' type='text/javascript'></script>
<script src='./javascript/tablist.js' type='text/javascript'></script>
<script src='./javascript/rapport_dnd.js' type='text/javascript'></script>
<script src='./javascript/http_request.js' type='text/javascript'></script>
<h1>".$msg['demandes_gestion']." : ".$msg['demandes_rapport_generation']."</h1>
<form class='form-".$current_module."' id='rapport' name='rapport'  method='post' action=\"!!form_action!!\">
	<h3>".$msg['demandes_rapport_realisation']."</h3>
	<input type='hidden' name='act' id='act' />
	<input type='hidden' name='idobject' id='idobject' value='!!idobject!!'/>
	<div class='form-contenu'>
		<div class='colonne2' >
			<div class='row'>
				<label class='etiquette'>!!titre_gauche!!</label>	
			</div>
			<div class='row'>
				!!list_obj!!
			</div>
		</div>
		<div class='colonne2' id='col_rapport'>
			<div class='row'>
				<label class='etiquette'>".$msg['demandes_rapport']."</label>	
			</div>
			<div class='row' id='liste_rapport'>
				!!list_obj_rapport!!
			</div> 
			<div class='row' id='add_com' style='display:none'></div>
			<div class='row' id='receptor' style='height:20px;' highlight=\"rap_highlight\" downlight=\"rap_downlight\" recepttype=\"dropzone\" recept=\"yes\">
			</div>
		</div>
		<div class='row'> </div>
	</div>
	<div class='row'>
		<label class='etiquette' >".$msg['demandes_rapport_exp_format']."</label>
		!!liste_export!!
	</div>
	<div class='row'>	
		<input type='button' class='bouton' value='".$msg['demandes_retour']."' onClick=\"!!cancel_action!!\"/>
	</div>	
</form>
<script type='text/javascript'>window.onload=function(){init_action();}</script>
<script type='text/javascript'>

function delete_item(id){

	if(confirm('".$msg['demandes_rapport_del_item']."')) {
		var url= './ajax.php?module=demandes&categ=rapport&quoifaire=del_item&id_item='+id;
		var action = new http_request();
		action.request(url);
		var id= document.getElementById('idobject').value;
		document.location='./demandes.php?categ=gestion&act=rapport&iddemande='+id;
	}
	
}
</script>
";
?>