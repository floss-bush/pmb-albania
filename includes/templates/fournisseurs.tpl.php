<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: fournisseurs.tpl.php,v 1.23 2010-10-15 14:19:38 ngantier Exp $


if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

//	------------------------------------------------------------------------------
//	$coord_form2 : template form edition des coordonnées des fournisseurs 
//	------------------------------------------------------------------------------

$coord_form2 = "
<form class='form-".$current_module."' id='coordform' name='coordform' method='post' action=\"./acquisition.php?categ=ach&sub=fourn&action=update&id_bibli=!!id_bibli!!&id=!!id!!\">
<h3>!!form_title!!</h3>
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
		<hr />
	</div>	
	<div class='row'>
			<label class='etiquette' for='raison'>".htmlentities($msg['acquisition_raison_soc'],ENT_QUOTES,$charset)."</label>
	</div>
	<div class='row'>
		<input type=text id='raison' name='raison' value=\"!!raison!!\" class='saisie-50em' />
	</div>
	<div class='row'>
			<label class='etiquette' for='num_cp'>".htmlentities($msg['acquisition_num_cp_client'],ENT_QUOTES,$charset)."</label>
	</div>
	<div class='row'>
		<input type=text id='num_cp' name='num_cp' value=\"!!num_cp!!\" class='saisie-25em' />
	</div>
	<div class= 'row'>
		!!contact!!
	</div>
	<hr />
	<div class='row'>
		<label class='etiquette' for='comment'>".htmlentities($msg[acquisition_commentaires],ENT_QUOTES,$charset)."</label>
	</div>
	<div class='row'>
		<textarea id='comment' name='comment' class='saisie-80em' cols='62' rows='6' wrap='virtual'>!!commentaires!!</textarea>
	</div>
	<div class= 'colonne2'>
		<div class='row'>
			<label class='etiquette' for='siret'>".htmlentities($msg[acquisition_siret],ENT_QUOTES,$charset)."</label>
		</div>
		<div class='row'>
			<input type='text' id='siret' name='siret' value='!!siret!!' class='saisie-50em' />
		</div>
	</div>

	<div class='colonne_suite'>
		<div class='row'>
			<label class='etiquette' for='rcs'>".htmlentities($msg[acquisition_rcs],ENT_QUOTES,$charset)."</label>
		</div>
		<div class='row'>
			<input type='text' id='rcs' name='rcs' value='!!rcs!!' class='saisie-50em' />
		</div>
	</div>

	<div class= 'colonne2'>
		<div class='row'>
			<label class='etiquette' for='naf'>".htmlentities($msg[acquisition_naf],ENT_QUOTES,$charset)."</label>
		</div>
		<div class='row'>
			<input type='text' id='naf' name='naf' value='!!naf!!' class='saisie-50em' />
		</div>
	</div>

	<div class='colonne_suite'>
		<div class='row'>
			<label class='etiquette' for='tva' >".htmlentities($msg[acquisition_tva],ENT_QUOTES,$charset)."</label>
		</div>
		<div class='row'>
			<input type='text' id='tva' name='tva' value='!!tva!!' class='saisie-50em' />
		</div>
	</div>
	<div class='row'>
		<label class='etiquette' for='site_web'>".htmlentities($msg[acquisition_site_web],ENT_QUOTES,$charset)."</label>
	</div>
	<div class='row'>
		<input type='text' id='site_web' name='site_web' value='!!site_web!!' class='saisie-50em' />
	</div>

	<br />
	<div class='row'></div>
</div>	
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onclick=\"document.location='./acquisition.php?categ=ach&sub=fourn&action=list&id_bibli=!!id_bibli!!'\" />&nbsp;
		<input class='bouton' type='submit' value=' $msg[77] ' onclick=\"return test_form(this.form)\" />
	</div>
	<div class='right'>
		<!-- bouton_sup -->
	</div>
	<div class='row'></div>
</div>
</form>
<br /><br />
<div class='row'></div>
<script type='text/javascript'>
	document.forms['coordform'].elements['raison'].focus();
</script>
";


//	------------------------------------------------------------------------------
//	$search_form : template de recherche pour les fournisseurs
//	------------------------------------------------------------------------------
$search_form = "
<form class='form-".$current_module."' id='search' name='search' method='post' action=\"!!action!!\">
	<h3>!!form_title!!</h3>
	<!--    Contenu du form    -->
	<div class='form-contenu'>
		<div class='row'>
			<div class='colonne2'>
				<input type='text' class='saisie-60em' id='user_input' name='user_input' value='!!user_input!!'/>
			</div>
		</div>
		<div class='row'>
			<div class='colonne2'>
				<label class='etiquette'>".htmlentities($msg['acquisition_coord_lib'], ENT_QUOTES, $charset)."</label>
			</div>
		</div>	
		<div class='row'>
			<div class='colonne2'>
				<!-- sel_bibli -->
			</div>
		</div>		
		<div class='row'></div>
	</div>
	<div class='row'>
		<div class='left'>
			<input type='submit' class='bouton' value='$msg[142]' />
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
//	$cond_form : template form liste des conditions commerciales  
//	------------------------------------------------------------------------------

$cond_form = "
<form class='form-".$current_module."' id='condform' name='condform' method='post' action=\"./acquisition.php?categ=ach&sub=fourn&action=updatecond&id_bibli=!!id_bibli!!&id=!!id!!\">
<h3>!!form_title!!</h3>
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
		<hr />
	</div>	
	<div class='row'>
		<div class='colonne2'>
			<div class='colonne2' >			
				<label class='etiquette'>".htmlentities($msg['acquisition_ach_fou2'], ENT_QUOTES, $charset)."</label>
			</div>
			<div class='colonne_suite'>
				!!raison!!
			</div>
		</div>
	</div>
	<div class='row'>&nbsp;
	</div>	

	<div class='row'>
		<div class='colonne2'>
			<label class='etiquette'>".htmlentities($msg['acquisition_fou_paie'], ENT_QUOTES, $charset)."</label>
		</div>
		<div class='colonne_suite'>
		</div>
	</div>
	<div class='row'>
		<div class='colonne2'>
			<!-- paiements -->
		</div>
		<div class='colonne_suite'>
		</div>
	</div>
	<div class='row'>&nbsp;</div>
	<div class='row'><hr /></div>
	<!-- frame -->
	<!-- bt_add -->
</div>	
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onclick=\"document.location='./acquisition.php?categ=ach&sub=fourn&id_bibli=!!id_bibli!!'\" />&nbsp;
		<input class='bouton' type='submit' value=' $msg[77] ' />
	</div>
	<div class='row'></div>
</div>
</form>
<div class='row'></div>
";


//	------------------------------------------------------------------------------
//	$frame : trame de visualisation des offres produits 
//	------------------------------------------------------------------------------

$frame = "
<table>
	<tr>
		<th>".htmlentities($msg['acquisition_type_prod'],ENT_QUOTES,$charset)."</th>
		<th>".htmlentities($msg['acquisition_remise'],ENT_QUOTES,$charset)."</th>
	</tr>
	<!-- frames_rows -->
</table>
";

$bt_add = "<input type='button' class='bouton' value='".htmlentities($msg['acquisition_rem_bt_add'],ENT_QUOTES,$charset)."' 
			onclick=\"document.forms['condform'].setAttribute('action', './acquisition.php?categ=ach&sub=fourn&action=modrem&id_bibli=".$id_bibli."&id=".$id."&id_prod=0');
						document.forms['condform'].submit();\" />";


//	------------------------------------------------------------------------------
//	$rem_form : template form des offres de remises par type de produits  
//	------------------------------------------------------------------------------

$rem_form = "
<form class='form-".$current_module."' id='remform' name='remform' method='post' action=\"./acquisition.php?categ=ach&sub=fourn&action=updaterem&id_bibli=!!id_bibli!!&id=!!id_fou!! \">
<h3>!!form_title!!</h3>
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
		<hr />
	</div>	
	<div class='row'>
		<div class='colonne2'>
			<div class='colonne2' >			
				<label class='etiquette'>".htmlentities($msg['acquisition_ach_fou2'], ENT_QUOTES, $charset)."</label>
			</div>
			<div class='colonne_suite'>
				!!raison!!
			</div>
		</div>
	</div>
	<div class='row'>&nbsp;
	</div>	


	<div class='row'>
		<label class='etiquette'>".htmlentities($msg['acquisition_type_prod'], ENT_QUOTES, $charset)."</label>
	</div>
	<div class='row'>
		<input type='hidden' id='id_prod' name='id_prod' value='!!id_prod!!' />
		!!lib_prod!!
	</div>
	<div class='row'>
		<label class='etiquette'>".htmlentities($msg['acquisition_remise'], ENT_QUOTES, $charset)."</label>
	</div>
	<div class='row'>
		<input type='text' id='rem' name='rem' class='saisie-10em' style='text-align:right' value='!!rem!!' />&nbsp;%
	</div>
	<div class='row'>
		<label class='etiquette'>".htmlentities($msg['acquisition_commentaires'],ENT_QUOTES,$charset)."</label>
	</div>
	<div class='row'>
		<textarea id='comment' class='saisie-80em' name='comment' cols='62' rows='6' wrap='virtual'>!!commentaires!!</textarea>
	</div>

	<div class='row'>&nbsp;</div>
</div>	
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input type='button' class='bouton' value=' $msg[76] ' onclick=\"document.location='./acquisition.php?categ=ach&sub=fourn&action=cond&id_bibli=!!id_bibli!!&id=!!id_fou!!'\" />&nbsp;
		<input type='submit' class='bouton' value=' $msg[77] ' />
	</div>
	<div class='right'>
		!!bouton_sup!!
	</div>
	<div class='row'></div>
</div>
</form>
<div class='row'></div>
";

$bt_sup = "<input class='bouton' type='button' value=' $msg[63] ' onclick=\"
				var r = confirm('".$msg['confirm_suppr']."');
				if(r) {
					document.location='./acquisition.php?categ=ach&sub=fourn&action=deleterem&id_bibli=!!id_bibli!!&id=!!id_fou!!& id_prod=!!id_prod!!';
				}
				return false; \" />";
?>
