<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: aut_link.tpl.php,v 1.2 2010-09-21 10:17:51 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

// templates pour la gestion des lien entre autorités
$add_aut_link="
<script>
	!!js_aut_link_table_list!!
	
	function fonction_raz_aut_link() {
		var name=this.getAttribute('id').substring(4);
		var name_id = name.substr(0,10)+'_id'+name.substr(10);
		var libelle= name.substr(0,10)+'_libelle'+name.substr(10);
		document.getElementById(name_id).value=0;
		document.getElementById(libelle).value='';
	}
	function add_aut_link() {
		var template = document.getElementById('add_aut_link');
		var aut_link=document.createElement('div');
		aut_link.className='row';
		
		var suffixe = eval(document.getElementById('max_aut_link').value);
		
		var nom_id = 'f_aut_link_type'+suffixe;
		var sel=document.getElementById('f_aut_link_type0');
		f_aut_link_type=sel.cloneNode(true);
		f_aut_link_type.setAttribute('name',nom_id);
		f_aut_link_type.setAttribute('id',nom_id);		
		
		var nom_id = 'f_aut_link_libelle'+suffixe
		var f_aut_link = document.createElement('input');
		f_aut_link.setAttribute('name',nom_id);
		f_aut_link.setAttribute('id',nom_id);
		f_aut_link.setAttribute('type','text');
		f_aut_link.className='saisie-80emr';
		f_aut_link.setAttribute('readonly','');
		f_aut_link.setAttribute('value','');
	
		var nom_id = 'f_aut_link_reciproc'+suffixe
		var f_aut_link_reciproc = document.createElement('input');
		f_aut_link_reciproc.setAttribute('name',nom_id);
		f_aut_link_reciproc.setAttribute('id',nom_id);
		f_aut_link_reciproc.setAttribute('type','checkbox');
		
		var del_f_aut_link = document.createElement('input');
		del_f_aut_link.setAttribute('id','del_f_aut_link'+suffixe);
		del_f_aut_link.onclick=fonction_raz_aut_link;
		del_f_aut_link.setAttribute('type','button');
		del_f_aut_link.className='bouton_small';
		del_f_aut_link.setAttribute('readonly','');
		del_f_aut_link.setAttribute('value','".$msg["raz"]."');
		
		var f_aut_link_id = document.createElement('input');
		f_aut_link_id.name='f_aut_link_id'+suffixe;
		f_aut_link_id.setAttribute('type','hidden');
		f_aut_link_id.setAttribute('id','f_aut_link_id'+suffixe);
		f_aut_link_id.setAttribute('value','');				

		var f_aut_link_table = document.createElement('input');
		f_aut_link_table.name='f_aut_link_table'+suffixe;
		f_aut_link_table.setAttribute('type','hidden');
		f_aut_link_table.setAttribute('id','f_aut_link_table'+suffixe);
		f_aut_link_table.setAttribute('value','');	
		
		var nom_id = 'f_aut_link_comment'+suffixe
		var f_aut_link_comment = document.createElement('textarea');
		f_aut_link_comment.setAttribute('name',nom_id);
		f_aut_link_comment.setAttribute('type','text');
		f_aut_link_comment.setAttribute('rows','2');
		f_aut_link_comment.setAttribute('cols','62');
		f_aut_link_comment.className='saisie-80em';

		var div_el = document.createElement('div');
    	var div_name = 'aut_link_viewcomment'+suffixe;
    	div_el.setAttribute('id',div_name);		
		div_el.className='row';
		div_el.style.display='none';
		div_el.appendChild(f_aut_link_comment);	
		
		var img_plus = document.createElement('img');
		img_plus.name='img_plus'+suffixe;
		img_plus.setAttribute('id','img_plus'+suffixe);		
		img_plus.className='img_plus';
		img_plus.setAttribute('hspace','3');	
		img_plus.setAttribute('border','0');	
		img_plus.setAttribute('src','./images/plus.gif');
		var onclick='if(document.getElementById(\"aut_link_viewcomment'+suffixe+'\").style.display==\"none\") {getElementById(\"img_plus'+suffixe+'\").setAttribute(\"src\",\"./images/minus.gif\");document.getElementById(\"aut_link_viewcomment'+suffixe+'\").style.display=\"inline\";}else {getElementById(\"img_plus'+suffixe+'\").setAttribute(\"src\",\"./images/plus.gif\");document.getElementById(\"aut_link_viewcomment'+suffixe+'\").style.display=\"none\";} ';
		img_plus.setAttribute('onclick',onclick);			
		
		aut_link.appendChild(f_aut_link_type);		
		aut_link.appendChild(document.createTextNode(' '));
		aut_link.appendChild(img_plus);
		aut_link.appendChild(document.createTextNode(' '));
		aut_link.appendChild(f_aut_link);
		aut_link.appendChild(document.createTextNode(' '));
		aut_link.appendChild(f_aut_link_reciproc);	
		aut_link.appendChild(document.createTextNode(' '));
		aut_link.appendChild(del_f_aut_link);
		aut_link.appendChild(f_aut_link_id);
		aut_link.appendChild(f_aut_link_table);		
		
		template.appendChild(aut_link);		
		template.appendChild(div_el);
		document.getElementById('max_aut_link').value = suffixe*1+1*1;
	}
</script>";
	/*
	 * 
	 	<div id="elacquisitionParent" class="parent" width="100%">
					<img src="./images/plus.gif" class="img_plus" name="imEx" id="elacquisitionImg" title="détail" onclick="expandBase('elacquisition', true); return false;" hspace="3" border="0">
		</div> 
					
		<div id="elacquisitionChild" class="child" style="margin-bottom: 6px; display: block;">

		</div> 

	 * 
	 */
$aut_link0 = "
	<input type='hidden' name='max_aut_link' id='max_aut_link' value='!!max_aut_link!!'/>
	<div class='row'>
			!!aut_table_list!!
		<input type='button' class='bouton_small' value='".$msg["parcourir"]."' 
		onclick=\"
			var selObj=document.getElementById('f_aut_link_table_list');
			var selIndex=selObj.selectedIndex;
			var table= selObj.options[selIndex].value;
			openPopUp(
			aut_link_table_select[table]+table, 
			'select_categ', 700, 500, -2, -2, '$select_categ_prop')\" />
		<input type='hidden' name='f_aut_link_id!!aut_link!!' id='f_aut_link_id!!aut_link!!' value='!!aut_link_id!!' />
		<input type='hidden' name='f_aut_link_table!!aut_link!!' id='f_aut_link_table!!aut_link!!' value='!!aut_link_table!!' />		
		<input type='button' class='bouton_small' value='+' onClick=\"add_aut_link();\"/>
	
	</div>
	
	<div class='row'>
		!!aut_link_type!!
		<img class='img_plus' src='./images/plus.gif' id='img_plus!!aut_link!!'
			onclick=\"
				if(document.getElementById('aut_link_viewcomment!!aut_link!!').style.display=='none') {
					document.getElementById('img_plus!!aut_link!!').src='./images/minus.gif';
					document.getElementById('aut_link_viewcomment!!aut_link!!').style.display='inline';
				}
				else {
					document.getElementById('img_plus!!aut_link!!').src='./images/plus.gif';
					document.getElementById('aut_link_viewcomment!!aut_link!!').style.display='none';
				}
			\" 
		/>		
		<input type='text' class='saisie-80emr' id='f_aut_link_libelle!!aut_link!!' name='f_aut_link_libelle!!aut_link!!' readonly value=\"!!aut_link_libelle!!\" />
		<input id='f_aut_link_reciproc!!aut_link!!' name='f_aut_link_reciproc!!aut_link!!' !!aut_link_reciproc!! type='checkbox'>
		<input type='button' class='bouton_small' value='".$msg["raz"]."' onclick=\"this.form.f_aut_link_libelle!!aut_link!!.value=''; this.form.f_aut_link_id!!aut_link!!.value='0'; \" />
	</div>
	<div class='row' id='aut_link_viewcomment!!aut_link!!' style='display:none;'>
		<textarea class='saisie-80em' name='f_aut_link_comment!!aut_link!!' cols='62' rows='2' >!!aut_link_comment!!</textarea>	
	</div>
	";
	
$aut_link1 = "
	<div class='row'>
		!!aut_link_type!!
		<img class='img_plus' src='./images/plus.gif' id='img_plus!!aut_link!!'
			onclick=\"
				if(document.getElementById('aut_link_viewcomment!!aut_link!!').style.display=='none') {
					document.getElementById('img_plus!!aut_link!!').src='./images/minus.gif';
					document.getElementById('aut_link_viewcomment!!aut_link!!').style.display='inline';
				}
				else {
					document.getElementById('img_plus!!aut_link!!').src='./images/plus.gif';
					document.getElementById('aut_link_viewcomment!!aut_link!!').style.display='none';
				}
			\" 
		/>
		<input type='text' class='saisie-80emr' id='f_aut_link_libelle!!aut_link!!' name='f_aut_link_libelle!!aut_link!!' readonly value=\"!!aut_link_libelle!!\" />
		<input id='f_aut_link_reciproc!!aut_link!!' name='f_aut_link_reciproc!!aut_link!!' !!aut_link_reciproc!! type='checkbox'>
		<input type='button' class='bouton_small' value='".$msg["raz"]."' onclick=\"this.form.f_aut_link_libelle!!aut_link!!.value=''; this.form.f_aut_link_id!!aut_link!!.value='0'; \" />
		<input type='hidden' name='f_aut_link_id!!aut_link!!' id='f_aut_link_id!!aut_link!!' value='!!aut_link_id!!' />
		<input type='hidden' name='f_aut_link_table!!aut_link!!' id='f_aut_link_table!!aut_link!!' value='!!aut_link_table!!' />
	</div>
	<div class='row' id='aut_link_viewcomment!!aut_link!!' style='display:none;'>
		<textarea class='saisie-80em' name='f_aut_link_comment!!aut_link!!' cols='62' rows='2' >!!aut_link_comment!!</textarea>	
	</div>";

$form_aut_link = "
	<div class='row'>
		<label class='etiquette' for='form_aut_link'>".$msg["aut_link"].$msg["renvoi_reciproque"]."</label>
	</div>
	<div id='add_aut_link'>
		!!aut_link_contens!!
	</div>";
?>