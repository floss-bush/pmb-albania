<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: livraisons.tpl.php,v 1.25 2009-06-03 06:06:35 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");


$livlist_form = "
<form class='form-$current_module' id='act_list_form' name='act_list_form' method='post' action=\"\" >
	<div class='form-contenu'>
		<table width='100%' ><tbody>
			<tr>
			<th>".htmlentities($msg['38'], ENT_QUOTES, $charset)."</th>
			<th>".htmlentities($msg['acquisition_act_num_cde'],ENT_QUOTES, $charset)."</th>
			<th>".htmlentities($msg['acquisition_ach_fou2'], ENT_QUOTES, $charset)."</th>
			<th>".htmlentities($msg['acquisition_liv_date_rec'], ENT_QUOTES, $charset)."</th>
			<th>".htmlentities($msg['acquisition_statut'], ENT_QUOTES, $charset)."</th>
			<th class='act_cell_chkbox' >&nbsp;</th>
			<!-- chk_th -->
			</tr>
			<!-- liv_list -->
		</tbody></table>
	</div>
	<div class='row'>
		<div class='left'></div>
		<div class='right'><!-- bt_chk --></div>
	</div>
	<div class='row'>&nbsp;</div>
	<div class='row'>
		<div class='left'><!-- bt_list --></div>
		<div class='right'><!-- bt_sup --></div>
	</div>
	<div class='row'></div>
</form>
<!-- script -->
<br />
<div class='form' >
	<!-- nav_bar -->
</div>
";


//	------------------------------------------------------------------------------
//	$livr_modif_form : template de création/modification pour les livraisons 
//	------------------------------------------------------------------------------
$livr_modif_form = "
<form class='form-".$current_module."' id='livr_modif' name='livr_modif' method='post' action=\"\" 
	onsubmit=\"	if(document.getElementById('cb').value=='') return false;
						list_lig.complete_form(); 
						list_lig.document.forms['frame_modif'].setAttribute('action', 'frame_livraison.php?action=search'); 
						list_lig.document.forms['frame_modif'].submit(); 
						return false; \">

	<h3>!!form_title!!</h3>
	<div class='row'></div>
	<!--    Contenu du form    -->
	<div class='form-contenu'>
		<div class='row'>
			<div class='colonne2'>
				<div class='colonne2' >			
					<label class='etiquette'>".htmlentities($msg['acquisition_coord_lib'], ENT_QUOTES, $charset)."</label>
				</div>
				<div class='colonne_suite'>
					!!lib_bibli!!
				</div>
			</div>
		</div>
		<div class='row'>
			<div class='colonne2'>
				<div class='colonne2' >			
					<label class='etiquette'>".htmlentities($msg['acquisition_budg_exer'], ENT_QUOTES, $charset)."</label>
				</div>
				<div class='colonne_suite'>
					!!lib_exer!!
				</div>
			</div>
		</div>
		<div class='row'>
			<hr />
		</div>	


		<div class='row'>
			<div class='colonne2'>
				<div class='colonne2' >			
					<label class='etiquette'>".htmlentities($msg['653'], ENT_QUOTES, $charset)."</label>
				</div>
				<div class='colonne_suite'>
					<div class='left'>!!date_cre!!</div>
				</div>
			</div>
			<div class='colonne2'>
				<div class='colonne5'>
		    		<label class='etiquette'>".htmlentities($msg['acquisition_ach_fou2'], ENT_QUOTES, $charset)."</label>&nbsp;
				</div>
				<div class='colonne_suite'>
					<input type='hidden' id='id_fou' name='id_fou' value='!!id_fou!!' />
					!!lib_fou!!
				</div>
			</div>
		</div>

		<div class='row'>
			<div class='colonne2'>
				<div class='colonne2'>
		    		<label class='etiquette'>".htmlentities($msg['38'], ENT_QUOTES, $charset)."</label>
				</div>
				<div class='colonne2'>
					<input type='hidden' id='id_liv' name='id_liv' value='!!id_liv!!' /> 
					!!numero!!
				</div>
			</div>
			<div class='colonne2'>
			</div>
		</div>

		<div class='row'></div>

		<br /> 

		<div class='row'>
			<img id='comment_Img' src='./images/plus.gif' class='img_plus' onclick=\"javascript:expandBase('comment_', true);\"/>
    		<label class='etiquette'>".htmlentities($msg['acquisition_commentaires'], ENT_QUOTES, $charset)."</label>&nbsp;
		</div>
		<div class='row' style='margin-left:30px'>
			<textarea  id='comment_Child' name='comment_Child' class='saisie-80em' style='display:none;' cols='62' rows='6' wrap='virtual'>!!comment!!</textarea>
		</div>
		<hr />

		<div class='row'>
			<div class='colonne3'>
				<div class='colonne2'>
					<label class='etiquette'>".htmlentities($msg['acquisition_act_num_cde'], ENT_QUOTES, $charset)."</label>
				</div>
				<div class='colonne2'>
					<input type='hidden' id='id_cde' name='id_cde' value = '!!id_cde!!' />
					<span class='curent'>!!num_cde!!</span>
				</div>
			</div>
			<div class = 'colonne_suite'>&nbsp;</div>
		</div>
		<div class='row'>
			<div class='colonne3'>
				<div class='colonne2'>
					<label class='etiquette'>".htmlentities($msg['acquisition_liv_ref_fou'], ENT_QUOTES, $charset)."</label>
				</div>
				<div class='colonne2'>
					<input id='ref' name='ref' class='saisie-10em' type='text' value='!!ref!!' />\n
				</div>
			</div>
			<div class='colonne_suite'>&nbsp;</div>
		</div>
		<div class='row'>&nbsp;</div>
		
		<!-- form_search -->
		<div>
			<table width='100%' frame='all' style='table-layout:fixed; '>
				<tr>
					<th width='3%'>&nbsp;</th>
					<th width='12%'>".htmlentities($msg['acquisition_act_tab_code'], ENT_QUOTES, $charset)."</th>
					<th width='45%'>".htmlentities($msg['acquisition_act_tab_lib'], ENT_QUOTES, $charset)."</th>
					<th width='20%'>".htmlentities($msg['acquisition_act_tab_sol'], ENT_QUOTES, $charset)."</th>
					<th width='20%'>".htmlentities($msg['acquisition_act_tab_rec'], ENT_QUOTES, $charset)."</th>
				</tr>
			</table>
		</div>

		<iframe class='acquisition' name='list_lig' id='list_lig' width='100%' height='350' ></iframe>		
			
		<div class='row'></div>
	
		<div class='right'>	
			<!-- bouton_sup_lig -->
			<input type='button' id='bt_sup_lig' name='bt_sup_lig' class='bouton' style='display:none;' value='".addslashes($msg['acquisition_del_chk_lig'])."'  
					onclick=\"list_lig.complete_form(); 
					list_lig.document.forms['frame_modif'].setAttribute('action', 'frame_livraison.php?action=sup_lig&id_bibli=".$id_bibli."'); 
					list_lig.document.forms['frame_modif'].submit(); \" />
		</div>
	
		<div class='row'></div>
	
	</div>

	<div class='row'>
		<div class='left'>
			<input type='button' class='bouton' value='".$msg['76']."' onclick=\"document.location='./acquisition.php?categ=ach&sub=livr&action=list&id_bibli=$id_bibli' \" />
			<!-- bouton_enr -->
			<!-- bouton_audit -->
		</div>
		<div class='right'>
			<!-- bouton_sup -->
		</div>
	<div class='row'></div>

</form>
<br /><br />
<script type='text/javascript' src='./javascript/tablist.js'></script>
<!-- frame_show -->
<script type='text/javascript'>
try {
	document.forms['livr_modif'].elements['cb'].focus();	
} catch (e) {}
</script>
";


//	------------------------------------------------------------------------------
//	template de création/modification pour les lignes de livraisons
//	------------------------------------------------------------------------------
$frame_modif = " 
<table width='100%' frame='all' style='table-layout:fixed;background-color:transparent;' >

	<form id='frame_modif' name='frame_modif' method='post' action=\"\" >
		<input type='hidden' id='max_lig' name='max_lig' value='!!max_lig!!' />
		<input type='hidden' id='id_cde' name='id_cde' value='0' />
		<input type='hidden' id='id_liv' name='id_liv' value='!!id_liv!!' />
		<input type='hidden' id='comment' name='comment' value='' />
		<input type='hidden' id='ref' name='ref' value='' />
		<input type='hidden' id='cb' name='cb' value='' />
		<input type='hidden' id='auto' name='auto' value='0' />
		<input type='hidden' id='max_lig_liv' name='max_lig_liv' value='!!max_lig_liv!!' />
		<!-- lignes -->
	</form>
</table>
<div class = 'row'></div>			

<!-- error -->
<!-- warning -->

<script type='text/javascript' >

//Mise à jour de la fenetre parent
maj_parent();

function complete_form() {
	document.forms['frame_modif'].elements['id_liv'].value = window.parent.document.forms['livr_modif'].elements['id_liv'].value;
	document.forms['frame_modif'].elements['id_cde'].value = window.parent.document.forms['livr_modif'].elements['id_cde'].value;
	document.forms['frame_modif'].elements['comment'].value = window.parent.document.forms['livr_modif'].elements['comment_Child'].value;
	document.forms['frame_modif'].elements['ref'].value = window.parent.document.forms['livr_modif'].elements['ref'].value;
	try {
		document.forms['frame_modif'].elements['cb'].value = window.parent.document.forms['livr_modif'].elements['cb'].value;
	} catch (e) {}
	if (window.parent.document.forms['livr_modif'].elements['auto'].checked ) document.forms['frame_modif'].elements['auto'].value = '1';
}

function maj_parent () {
	try {
		window.parent.document.forms['livr_modif'].elements['cb'].value = '';
	} catch (e) {}
}

<!-- bouton_sup_lig -->	

</script>
<!-- focus -->
</body></html>
";


$frame_row = "
<tr>
	<td width='3%'>
		<label class='etiquette' >!!no!!</label>
		<input type='hidden' id='id_lig[!!no!!]' name='id_lig[!!no!!]' value='!!id_lig!!' /> 
		<input type='hidden' id='id_prod[!!no!!]' name='id_prod[!!no!!]' value='!!id_prod!!' />
	</td>
	<td width='12%' valign='top' style='overflow:hidden;' >
		<input type='hidden' id='code[!!no!!]' name='code[!!no!!]' value='!!code!!' />\n
		!!code!!
	</td>
	<td width='45%' valign='top' style='overflow:hidden;' >
		<input type='hidden' id='lib[!!no!!]' name='lib[!!no!!]' value='!!hidden_lib!!' />
		!!lib!!
	</td>
	<td width='20%' valign='top' style='text-align:right;padding-right:5px;' >
		<input type='hidden' id='sol[!!no!!]' name='sol[!!no!!]' value='!!sol!!' />
		!!sol!!
	</td>
	<td width='20%' valign='top'>
		<a name='ancre[!!no!!]'></a>
		<input type='text' id='rec[!!no!!]' name='rec[!!no!!]' style='text-align:right;width:95%' tabindex='1' value='!!rec!!' />
	</td>
</tr>			
";


$frame_row_bl_header="<tr class='tab_sep'><td width='3%'>&nbsp;</td><td width='12%'>&nbsp;</td><td width='45%'><strong>".htmlentities($msg['acquisition_liv_sai'], ENT_QUOTES, $charset)."</strong></td><td width='20%'>&nbsp;</td><td width='20%'>&nbsp;</td></tr>";

$frame_row_bl = "
<tr>
	<td width='3%'>
		<input type='checkbox' tabindex='-1' id='chk[!!no!!]' name='chk[!!no!!]' value='1' />			
		<input type='hidden' id='id_lig[!!no!!]' name='id_lig[!!no!!]' value='!!id_lig!!' /> 
		<input type='hidden' id='id_prod[!!no!!]' name='id_prod[!!no!!]' value='!!id_prod!!' />
	</td>
	<td width='12%' valign='top' style='overflow:hidden;' >
		<input type='hidden' id='code[!!no!!]' name='code[!!no!!]' value='!!code!!' />\n
		!!code!!
	</td>
	<td width='45%' valign='top' style='overflow:hidden;' >
		<input type='hidden' id='lib[!!no!!]' name='lib[!!no!!]' value='!!hidden_lib!!' />
		!!lib!!
	</td>
	<td width='20%' valign='top' >
		&nbsp;
	</td>
	<td width='20%' valign='top' style='text-align:right;padding-right:5px;' >
		<input type='hidden' id='rec[!!no!!]' name='rec[!!no!!]' value='!!rec!!' />
		!!rec!!
	</td>
</tr>			
";


$frame_row_arc="
<tr>
	<td width='3%'>
		<label class='etiquette' >!!no!!</label>
	</td>
	<td width='12%' valign='top' style='overflow:hidden;' >
		!!code!!
	</td>
	<td width='45%' valign='top' style='overflow:hidden;' >
		!!lib!!
	</td>
	<td width='20%' valign='top' style='text-align:right;padding-right:5px;' >
		!!sol!!
	</td>
	<td width='20%' valign='top' style='text-align:right;padding-right:5px;' >
		!!rec!!
	</td>
</tr>			
";


$frame_show_from_cde = "<script type='text/javascript'>document.getElementById('list_lig').src= './acquisition/achats/livraisons/frame_livraison.php?action=from_cde&id_bibli=$id_bibli&id_cde=$id_cde' </script>";
$frame_show = "<script type='text/javascript'>document.getElementById('list_lig').src= './acquisition/achats/livraisons/frame_livraison.php?action=show_lig&id_bibli=$id_bibli&id_cde=!!id_cde!!&id_liv=$id_liv' </script>";


$bt_sup_lig = "	bt = window.parent.document.getElementById('bt_sup_lig');
				bt.setAttribute('style','display:block;'); ";

$no_bt_sup_lig = " bt = window.parent.document.getElementById('bt_sup_lig');
				   bt.setAttribute('style','display:none;'); ";

$bt_sup = "<input type='button' class='bouton' value='".$msg['63']."' 
			onclick=\"if (document.forms['livr_modif'].elements['id_liv'].value == 0) {return false; } 
				r = confirm('".addslashes($msg['acquisition_liv_sup'])."');
				if(r){
					document.forms['livr_modif'].setAttribute('action', './acquisition.php?categ=ach&sub=livr&action=delete&id_bibli=".$id_bibli."'); 
					document.forms['livr_modif'].submit();} \" />";			 

$bt_enr = "<input type='button' class='bouton' value='".$msg['77']."' onclick=\"list_lig.complete_form(); 
				list_lig.document.forms['frame_modif'].setAttribute('action', 'frame_livraison.php?action=update&id_bibli=$id_bibli'); 
				list_lig.document.forms['frame_modif'].submit(); 
				return false;\" />";

$bt_audit = "<input type='button' class='bouton' value='".$msg['audit_button']."' onClick=\"openPopUp('./audit.php?type_obj=4&object_id=".$id_liv."', 'audit_popup', 700, 500, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes')\" title='".$msg['audit_button']."' />";

$form_search = "	<hr />
			<div class='row'>
				<div class = 'colonne2'>
					<label class='etiquette'>".htmlentities($msg['663'], ENT_QUOTES, $charset)."</label>
					<input type='text' id='cb' name='cb' class='saisie-1Oem' value='' />
					<input type='submit' class='bouton' value='".$msg['142']."' />
				</div>
				<div class='colonne2'>
					<label class='etiquette' for='auto'>".htmlentities($msg['acquisition_liv_auto'], ENT_QUOTES, $charset)."</label>				
					<input type='checkbox' id='auto' name='auto' value='1' unchecked='unchecked' /> 
				</div>
			</div>
			<div class='row'>&nbsp;</div>";

$retour_liste = "<script type='text/javascript'>window.parent.location='../../../acquisition.php?categ=ach&sub=livr&action=list&id_bibli=".$id_bibli."'; </script></body></html>"; 

?>
