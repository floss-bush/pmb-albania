<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: acces.tpl.php,v 1.5 2009-07-28 17:01:07 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

$dom_view_form="
<!-- domaine -->
<br />
<table>
	<tbody>
	<!-- rows -->
	</tbody>
</table>
<br />
<!-- maj -->
<form class='form-".$current_module."' id='dom_ctl_list' name='dom_ctl_list' method='post' action=\"\">
<h3>!!rights_header!!</h3>
<div class='form-contenu'>	
	<!-- dom_glo_rights_form -->
	<strong><!-- prf_rights_lib --></strong>
	<div class='dom_div'>
	<table class='dom_tab' >
		<!-- prf_rights_tabs -->
	</table>
	</div>
</div>
<div id='pbar' class='pbar' style='display:none;'>
	<h3><span id='pbar_ini_msg' >".htmlentities($msg['dom_ini'],ENT_QUOTES,$charset)."</span><span id='pbar_end_msg' style='display:none;'>".htmlentities($msg['dom_end'],ENT_QUOTES,$charset)."</span></h3>
	<div class='pbar_frame' >
		<div class='row' >".htmlentities($msg['pbar_progress'],ENT_QUOTES,$charset)."<span id='pbar_percent'>0%</span></div>
		<div class='pbar_gauge'><img id='pbar_img' src='images/jauge.png' width='0%'/></div>
	</div>
</div>
<div class='row'>
	<div class='left'>
		<input type='button' class='bouton' value='".$msg['654']."' onclick=\"document.location='./admin.php?categ=acces' \" />
		<!-- bt_enr -->
		<!-- bt_app -->
		<!-- chk_sav_spe_rights -->
	</div>
</div>
<div class='row'></div>
<input type='hidden' id='dom_id' value='$id' />
</form>
<br /><br />
<script src=\"./javascript/domain.js\" type=\"text/javascript\"></script>";

$user_prf_list_form="
<br />
<table>
	<tbody>
	<!-- rows -->
	</tbody>
</table>
<br />
<!-- maj -->
<form class='form-".$current_module."' id='user_prf_list' name='user_prf_list' method='post' action=\"\">
	<h3>!!form_title!!</h3>
	<div class='row'></div>
	<!--    Contenu du form    -->
	<div class='form-contenu'>
		<div class='row'>	
			<label class='etiquette'>".htmlentities($msg['dom_prop_chx'], ENT_QUOTES, $charset)."</label>
		</div>
		<div class='row'></div>
		<div class='row'>
			<!-- properties -->
		</div>
		<div class='row'>
			<!-- bt_calc -->
		</div>
		<hr />
		<!-- used_list_form -->
		<!-- calc_list_form -->
		<!-- unused_list_form -->
		<div class='row'></div>
	</div>
	<div class='row'>
		<div class='left'>
			<input type='button' class='bouton' value='".$msg['654']."' onclick=\"document.location='./admin.php?categ=acces&sub=domain&action=view&id=".$id."' \" />
			<!-- bt_enr -->
		</div>
		<div class='right'>
			<!-- bt_sup -->
		</div>
	</div>
	<div class='row'></div>
</form>
<br /><br />";

$used_list_form="
		<div class='row'>	
			<label class='etiquette'>!!used_list_lib!!</label>
		</div>
		
		<table>
			<tbody id='used_prf_tab'>
				<!-- used_profiles -->
			</tbody>
		</table>";

$calc_list_form="
		<div class='row'>	
			<label class='etiquette'>!!calc_list_lib!!</label>
		</div>
		
		<table>
			<tbody id='calc_prf_tab'>
				<!-- calc_profiles -->
			</tbody>
		</table>";

$unused_list_form="
		<br />
		<hr />
		<br />
		<div class='row'>	
			<label class='etiquette'>!!unused_list_lib!!</label>
		</div>
		
		<table>
			<tbody id='unused_prf_tab'>
				<!-- unused_profiles -->
			</tbody>
		</table>";

$maj_form="
<span id='recorded' class='recorded'>".htmlentities($msg['rights_recorded'],ENT_QUOTES,$charset)."</span>
<script type='text/javascript'>
function maj_clear() {
	document.getElementById('recorded').innerHTML='';
}
setTimeout('maj_clear()',1000);
</script>";

$res_prf_list_form="
<br />
<table>
	<tbody>
	<!-- rows -->
	</tbody>
</table>
<br />
<!-- maj -->
<form class='form-".$current_module."' id='res_prf_list' name='res_prf_list' method='post' action=\"\">
	<h3>!!form_title!!</h3>
	<div class='row'></div>
	<!--    Contenu du form    -->
	<div class='form-contenu'>
		<div class='row'>	
			<label class='etiquette'>".htmlentities($msg['dom_prop_chx'], ENT_QUOTES, $charset)."</label>
		</div>
		<div class='row'></div>
		<div class='row'>
			<!-- properties -->
		</div>
		<div class='row'>
			<!-- bt_calc -->
		</div>
		<hr />
		<!-- used_list_form -->
		<!-- calc_list_form -->
		<!-- unused_list_form -->
		<div class='row'></div>
	</div>
	<div class='row'>
		<div class='left'>
			<input type='button' class='bouton' value='".$msg['654']."' onclick=\"document.location='./admin.php?categ=acces&sub=domain&action=view&id=".$id."' \" />
			<!-- bt_enr -->
		</div>
		<div class='right'>
			<!-- bt_sup -->
		</div>
	</div>
	
	<div class='row'></div>
</form>
<br /><br />";

$dom_rights_form = "
<tr>
	<th style='width:30%;'></th>
	<th style='width:70%;'>!!usr_prf_header!!</th>
</tr>
<tr>
	<th style='width:30%;'>!!res_prf_header!!</th>
	<td style='width:70%;'>
		<table frame='all'>
			<!-- rows -->
		</table>
	</td>
</tr>";

$dom_glo_rights_form = "
<strong>".htmlentities($msg['dom_glo_rights_lib'],ENT_QUOTES,$charset)."</strong>
<table >
	<!-- rows -->
</table>
<br />";

?>