<?php

// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: export_param.tpl.php,v 1.1 2009-05-04 15:09:04 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

$form_entete_param="
<form class='form-$current_module'  name=\"export_param_form\" action=\"!!action!!\" method=\"post\">
	<h3>!!param_title!!</h3>
	<div class='form-contenu'>
		!!form_param!!
		<div class='row'>
			<input class=\"bouton\" type=\"button\" onclick=\"document.location='./admin.php?categ=convert';\" value=$msg[76] />
			<input id=\"btnsubmit\"class=\"bouton\" type=\"submit\" onclick=\"this.form.act.value='update';this.form.submit();\" value=$msg[77] />
			<input type='hidden'  name='act' value=''>
		</div>
	</div>
</form>
";
	
$form_param ="
<div class='row'>
	<input type=\"checkbox\" id=\"genere_lien\" name=\"genere_lien\" value='1' !!checked_0!! onclick=\"param_display();\"/> 
	<label for=\"genere_lien\">$msg[export_genere_liens]</label>
</div>

<div id='list_param' style=\"!!display_list!!\">
	<blockquote>	
		<div class='row'>
			<h3>
				$msg[export_notice_liee]
			</h3>
		</div>			
		<blockquote>	
		<div class='row'>
			<input type=\"checkbox\" id=\"mere\"\ name=\"mere\" value='1' !!checked_1!! onclick=\"param_activate();\"  />
			<label for=\"mere\">$msg[export_mere]</label>
		</div>	
		<div class='row'>
			<input type=\"checkbox\" id=\"fille\" name=\"fille\" value='1' !!checked_2!! onclick=\"param_activate();\" /> 
			<label for=\"fille\">$msg[export_fille]</label>
		</div>
		<div class='row'>
			<input type=\"checkbox\" id=\"notice_mere\" name=\"notice_mere\" value='1' !!checked_9!! !!disabled_3!!/> 
			<label for=\"notice_mere\">$msg[export_notice_mere]</label>
		</div>
		<div class='row'>
			<input type=\"checkbox\" id=\"notice_fille\" name=\"notice_fille\" value='1' !!checked_10!! !!disabled_4!!/> 
			<label for=\"notice_fille\">$msg[export_notice_fille]</label>
		</div>
		</blockquote>	
	</blockquote>
	
	<blockquote>
		<div class='row'>
			<h3>
				$msg[export_struct_perio]
			</h3>
		</div>	
		<blockquote>
    	<div class='row'>
			<input type=\"checkbox\" id=\"bull_link\" name=\"bull_link\"  value='1' !!checked_3!! onclick=\"param_activate();\"/> 
			<label for=\"bull_link\"> $msg[export_bull_link]</label>
		</div>
		<div class='row'>
			<input type=\"checkbox\" id=\"perio_link\" name=\"perio_link\" value='1'  !!checked_4!! onclick=\"param_activate();\"/>
			<label for=\"perio_link\"> $msg[export_perio_link]</label>
		</div>
		<div class='row'>
			<input type=\"checkbox\" id=\"art_link\" name=\"art_link\"  value='1' !!checked_5!! onclick=\"param_activate();\"/> 
			<label for=\"art_link\"> $msg[export_art_link]</label>
		</div>
		<div class='row'>
			<input type=\"checkbox\"  id=\"bulletinage\" name=\"bulletinage\" !!checked_6!! value='1' /> 
			<label for=\"bulletinage\">$msg[export_bulletinage]</label>
		</div>		
		<div class='row'>
			<input type=\"checkbox\"  id=\"notice_perio\" name=\"notice_perio\" !!checked_7!! value='1' !!disabled_1!! /> 
			<label for=\"notice_perio\">$msg[export_notice_perio]</label>
		</div>
		<div class='row'>
			<input type=\"checkbox\" id=\"notice_art\" name=\"notice_art\"/  !!checked_8!! value='1' !!disabled_2!! /> 
			<label for=\"notice_art\">$msg[export_notice_art]</label>
		</div>
		</blockquote>
	<blockquote>
	</div>
</div>

<script>

	function param_display(){
		if(document.getElementById('genere_lien').checked){
			document.getElementById('genere_lien').checked = 'checked';	
			document.getElementById('list_param').style.display=\"\";		
		} else {
			document.getElementById('genere_lien').checked = '';	
			document.getElementById('list_param').style.display=\"none\";
		}
	}
	
	function param_activate(){
			
		if(document.getElementById('perio_link').checked){	
			document.getElementById('notice_perio').disabled='';
		} else {
			document.getElementById('notice_perio').disabled='disabled';
		}
		
		if(document.getElementById('art_link').checked){	
			document.getElementById('notice_art').disabled='';
		} else {
			document.getElementById('notice_art').disabled='disabled';
		}

		if(document.getElementById('mere').checked){	
			document.getElementById('notice_mere').disabled='';
		} else {
			document.getElementById('notice_mere').disabled='disabled';
		}	
		
		if(document.getElementById('fille').checked){	
			document.getElementById('notice_fille').disabled='';
		} else {
			document.getElementById('notice_fille').disabled='disabled';
		}	
	}
	
</script>


";
?>