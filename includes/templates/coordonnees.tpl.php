<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: coordonnees.tpl.php,v 1.24 2009-05-16 11:19:55 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

//	------------------------------------------------------------------------------
//	$coord_form : template form des coordonnées des bibliothèques 
//	------------------------------------------------------------------------------

$coord_form = "
<form class='form-".$current_module."' id='coordform' name='coordform' method='post' action=\"./admin.php?categ=acquisition&sub=entite&action=update&id=!!id!!\">
<h3>!!form_title!!</h3>
<!--    Contenu du form    -->
<div class='form-contenu'>

	<div class='row'>
		<label class='etiquette' for='raison'>".htmlentities($msg[acquisition_raison_soc],ENT_QUOTES,$charset)."</label>
	</div>
	<div class='row'>
		<input type=text id='raison' name='raison' value=\"!!raison!!\" class='saisie-50em' />
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
			<input type='text' id='siret' name='siret' value='!!siret!!' class='saisie-30em' />
		</div>
	</div>

	<div class='colonne_suite'>
		<div class='row'>
			<label class='etiquette' for='rcs'>".htmlentities($msg[acquisition_rcs],ENT_QUOTES,$charset)."</label>
		</div>
		<div class='row'>
			<input type='text' id='rcs' name='rcs' value='!!rcs!!' class='saisie-30em' />
		</div>
	</div>

	<div class= 'colonne2'>
		<div class='row'>
			<label class='etiquette' for='naf' >".htmlentities($msg[acquisition_naf],ENT_QUOTES,$charset)."</label>
		</div>
		<div class='row'>
			<input type='text' id='naf' name='naf' value='!!naf!!' class='saisie-10em' />
		</div>
	</div>

	<div class='colonne_suite'>
		<div class='row'>
			<label class='etiquette' for='tva'>".htmlentities($msg[acquisition_tva],ENT_QUOTES,$charset)."</label>
		</div>
		<div class='row'>
			<input type='text' id='tva' name='tva' value='!!tva!!' class='saisie-30em' />
		</div>
	</div>
	<div class='row'>
		<label class='etiquette' for='site_web'>".htmlentities($msg[acquisition_site_web],ENT_QUOTES,$charset)."</label>
	</div>
	<div class='row'>
		<input type='text' id='site_web' name='site_web' value='!!site_web!!' class='saisie-30em' />
	</div>
	<div class='row'>
		<label class='etiquette' for='co_logo'>".htmlentities($msg[acquisition_logo],ENT_QUOTES,$charset)."</label>
	</div>
	<div class='row'>
		<input type='text' id='co_logo' name='logo' value='!!logo!!' class='saisie-30em' />
	</div>

	<br /><hr />
	<div class='row'>
		<label class='etiquette'>$msg[acquisition_autorisations]</label>
	</div>
	<div class='row'>
		<!-- autorisations -->
	</div>
	<div class='row'></div>
</div>	
<!-- Boutons -->
<div class='row'>
	<div class='left'>
		<input class='bouton' type='button' value=' $msg[76] ' onclick=\"document.location='./admin.php?categ=acquisition&sub=entite'\" />&nbsp;
		<input class='bouton' type='submit' value=' $msg[77] ' onclick=\"return test_form(this.form)\" />
	</div>
	<div class='right'>
		<!-- bouton_sup -->
	</div>
	<div class='row'></div>
</div>
</form>
<div class='row'></div>
<script type='text/javascript'>
	document.forms['coordform'].elements['raison'].focus();
</script>
";


//    ----------------------------------------------------
//    Coordonnées pour bibliothèque
//    ----------------------------------------------------

$ptab[1] = "
<div id='racine' class='parent' >
	<br />
    <input type='hidden' id='max_coord' name='max_coord' value='!!max_coord!!' />
	<div class='colonne'>
		<a href=\"javascript:expandAll()\"><img src='./images/expand_all.gif' border='0' id='expandall'></a>
		<a href=\"javascript:collapseAll()\"><img src='./images/collapse_all.gif' border='0' id='collapseall'></a>
	</div>

	<div id='el1Child' class='row'>
		<input type='hidden' name='no_[1]' id='no_[1]' value='!!id1!!' /> 
		<input type='hidden' name='mod_[1]' id='mod_[1]' value='0' />
   		<div class='colonne80'>
			<img src='./images/plus.gif' class='img_plus' name='imEx' id='el_1_Img' border='0' onclick=\"expandBase('el_1_', true); return false;\" />
			<label class='etiquette'>!!adresse!!</label>
		</div>
	</div>

	<div id='el_1_Child' name='el_1_Child' class='child' style='display:none'>
	
		<div class ='row'></div>

		<div class ='row'>
			<div class='colonne' style='width:95%;'>
				<div class ='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_coord_lib],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne_suite'>
					<input type='text' class='saisie-60em' id='lib_[1]' name='lib_[1]' value='!!lib_1!!' onchange=\"mod_coord_el('1'); \" />
				</div>
			</div>
			<div class='colonne_suite'>
				<input type='button' 'id=raz_1' class='bouton' value='".$msg[raz]."' title='".$msg[acquisition_bt_raz_title]."' onclick=\"raz_coord_el('1'); \" />
			</div>
		</div>

		<div class ='row'>
			<div class='colonne' style='width:95%;'>
				<div class ='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_contact],ENT_QUOTES,$charset)." 
				</div>
				<div class='colonne_suite'>
					<input type='text' class='saisie-60em' id='cta_[1]' name='cta_[1]' value='!!cta_1!!' onchange=\"mod_coord_el('1'); \" />
				</div>
			</div>
		</div>

		<div class ='row'>
			<div class='colonne' style='width:95%;'>
				<div class ='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_adr],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne_suite'>
					<input type='text' class='saisie-60em' id='ad1_[1]' name='ad1_[1]' value='!!ad1_1!!' onchange=\"mod_coord_el('1'); \" />
				</div>
			</div>
		</div>

		<div class='row'>
			<div class='colonne' style='width:95%;'>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					&nbsp;
				</div>
				<div class='colonne_suite'>	
					<input type='text' class='saisie-60em' id='ad2_[1]' name='ad2_[1]' value='!!ad2_1!!' onchange=\"mod_coord_el('1'); \" />
				</div>
			</div>
		</div>

		<div class='row'>
			<div class='colonne' style='width:95%;'>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_cp],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne10'> 
					<input type='text' class='saisie-5em' id='cpo_[1]' name='cpo_[1]' value='!!cpo_1!!' onchange=\"mod_coord_el('1'); \" />
				</div>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_ville],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne10'> 
					<input type='text' class='saisie-20em' id='vil_[1]' name='vil_[1]' value='!!vil_1!!' onchange=\"mod_coord_el('1'); \" />
				</div>
			</div>
		</div>

		<div class='row'>
			<div class='colonne' style='width:95%;'>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_etat],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne10'> 
					<input type='text' class='saisie-15em' id='eta_[1]' name='eta_[1]' value='!!eta_1!!' onchange=\"mod_coord_el('1'); \" />
				</div>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_pays],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne10'> 
	 				<input type='text' class='saisie-20em' id='pay_[1]' name='pay_[1]' value='!!pay_1!!' onchange=\"mod_coord_el('1'); \" />
				</div>
			</div>
		</div>



		<div class='row'>
			<div class='colonne' style='width:95%;'>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_tel1],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne10'>
					<input type='text' class='saisie-10em' id='te1_[1]' name='te1_[1]' value='!!te1_1!!' onchange=\"mod_coord_el('1'); \" />
				</div>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_tel2],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne10'>
					<input type='text' class='saisie-10em' id='te2_[1]' name='te2_[1]' value='!!te2_1!!' onchange=\"mod_coord_el('1'); \" />
				</div>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_fax],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne10'> 
	 				<input type='text' class='saisie-10em' id='fax_[1]' name='fax_[1]' value='!!fax_1!!' onchange=\"mod_coord_el('1'); \" />
				</div>
			</div>
		</div>

		<div class='row'>
			<div class='colonne' style='width:95%;'>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_mail],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne_suite'>
					<input type='text' class='saisie-60em' id='ema_[1]' name='ema_[1]' value='!!ema_1!!' onchange=\"mod_coord_el('1'); \" />
				</div>
			</div>
		</div>
	</div>

	<br />
";

$ptab[10] = "
	<div id='el2Child' class='row'>
		<input type='hidden' name='no_[2]' id='no_[2]' value='!!id2!!' /> 
		<input type='hidden' name='mod_[2]' id='mod_[2]' value='0' />
   		<div class='colonne80'>
			<img src='./images/plus.gif' class='img_plus' name='imEx' id='el_2_Img' border='0' onclick=\"expandBase('el_2_', true); return false;\" />
			<label class='etiquette'>".htmlentities($msg[acquisition_adr_liv],ENT_QUOTES,$charset)."</label>
		</div>
	</div>
	
	<div id='el_2_Child' name='el_2_Child' class='child' style='display:none'>

		<div class='row'></div>
		<div class='row'>
			<div class='colonne' style='width:95%;'>
				<div class ='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_coord_lib],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne_suite'>
					<input type='text' class='saisie-60em' id='lib_[2]' name='lib_[2]' value='!!lib_2!!' onchange=\"mod_coord_el('2'); \" />			
				</div>
			</div>
			<div class='colonne_suite'>
				<input type='button' id='raz_2' class='bouton' value='".$msg[raz]."' title='".$msg[acquisition_bt_raz_title]."' onclick=\"raz_coord_el('2'); \" />
			</div>
		</div>
		
		<div class ='row'>
			<div class='colonne' style='width:95%;'>
				<div class ='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_contact],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne_suite'>
					<input type='text' class='saisie-60em' id='cta_[2]' name='cta_[2]' value='!!cta_2!!' onchange=\"mod_coord_el('2'); \" />
				</div>
			</div>
		</div>

		<div class ='row'>
			<div class='colonne' style='width:95%;'>
				<div class ='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_adr],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne_suite'>
					<input type='text' class='saisie-60em' id='ad1_[2]' name='ad1_[2]' value='!!ad1_2!!' onchange=\"mod_coord_el('2'); \" />
				</div>
			</div>
		</div>

		<div class='row'>
			<div class='colonne' style='width:95%;'>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					&nbsp;
				</div>
				<div class='colonne_suite'>	
					<input type='text' class='saisie-60em' id='ad2_[2]' name='ad2_[2]' value='!!ad2_2!!' onchange=\"mod_coord_el('2'); \" />
				</div>
			</div>
		</div>

		<div class='row'>
			<div class='colonne' style='width:95%;'>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_cp],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne10'> 
					<input type='text' class='saisie-5em' id='cpo_[2]' name='cpo_[2]' value='!!cpo_2!!' onchange=\"mod_coord_el('2'); \" />
				</div>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_ville],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne10'> 
					<input type='text' class='saisie-20em' id='vil_[2]' name='vil_[2]' value='!!vil_2!!' onchange=\"mod_coord_el('2'); \" />
				</div>
			</div>		
		</div>

		<div class='row'>
			<div class='colonne' style='width:95%;'>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_etat],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne10'> 
					<input type='text' class='saisie-15em' id='eta_[2]' name='eta_[2]' value='!!eta_2!!' onchange=\"mod_coord_el('2'); \" />
				</div>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_pays],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne10'> 
	 				<input type='text' class='saisie-20em' id='pay_[2]' name='pay_[2]' value='!!pay_2!!' onchange=\"mod_coord_el('2'); \" />
				</div>
			</div>
		</div>



		<div class='row'>
			<div class='colonne' style='width:95%;'>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_tel1],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne10'>
					<input type='text' class='saisie-10em' id='te1_[2]' name='te1_[2]' value='!!te1_2!!' onchange=\"mod_coord_el('2'); \" />
				</div>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_tel2],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne10'>	
					<input type='text' class='saisie-10em' id='te2_[2]' name='te2_[2]' value='!!te2_2!!' onchange=\"mod_coord_el('2'); \" />
				</div>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_fax],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne10'> 
	 				<input type='text' class='saisie-10em' id='fax_[2]' name='fax_[2]' value='!!fax_2!!' onchange=\"mod_coord_el('2'); \" />
				</div>
			</div>
		</div>

		<div class='row'>
			<div class='colonne' style='width:95%;'>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_mail],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne_suite'>
					<input type='text' class='saisie-60em' id='ema_[2]' name='ema_[2]' value='!!ema_2!!' onchange=\"mod_coord_el('2'); \" />
				</div>
			</div>
		</div>
	</div>

	<br />
";

$ptab[11] = "

    <!--coord_repetables-->
	
</div>
<div class='row'>
	<input type='button' class='bouton' value='".$msg[acquisition_bt_add]."' onclick=\"add_coord();\" />
</div>

";

//    ----------------------------------------------------
//     Coordonnées répétables
//    ----------------------------------------------------
$ptab[2] = "
	<div id='el!!no_X!!Child' class='row'>
		<input type='hidden' name='no_[!!no_X!!]' id='no_[!!no_X!!]' value='!!idX!!' /> 
		<input type='hidden' name='mod_[!!no_X!!]' id='mod_[!!no_X!!]'value='0' />
		<div class='colonne80'>
			<img src='./images/plus.gif' class='img_plus' name='imEx' id='elX_!!no_X!!_Img' border='0' onclick=\"expandBase('elX_!!no_X!!_', true); return false;\" />
			<label class='etiquette'>".htmlentities($msg[acquisition_adr].' !!no_X!!',ENT_QUOTES,$charset)."</label>
		</div>
	</div>	

	<div id='elX_!!no_X!!_Child' name='elX_!!no_X!!_Child' class='child' style='display:none'>

		<div class ='row'></div>
		<div class ='row'>
			<div class='colonne' style='width:95%;'>
				<div class ='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_coord_lib],ENT_QUOTES,$charset)." 
				</div>
				<div class='colonne_suite'>
					<input type='text' class='saisie-60em' id='lib_[!!no_X!!]' name='lib_[!!no_X!!]' value='!!lib_X!!' onchange=\"mod_coord_el('!!no_X!!'); \" />
				</div>
			</div>
			<div class='colonne_suite'>
				<input type='button' id='raz_!!no_X!!' class='bouton' value='".$msg[raz]."' title='".$msg[acquisition_bt_raz_title]."' onclick=\"raz_coord_el('!!no_X!!'); \" />
			</div>
		</div>

		<div class ='row'>
			<div class='colonne' style='width:95%;'>
				<div class ='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_contact],ENT_QUOTES,$charset)." 
				</div>
				<div class='colonne_suite'>
					<input type='text' class='saisie-60em' id='cta_[!!no_X!!]' name='cta_[!!no_X!!]' value='!!cta_X!!' onchange=\"mod_coord_el('!!no_X!!'); \" />
				</div>
			</div>
		</div>

		<div class ='row'>
			<div class='colonne' style='width:95%;'>
				<div class ='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_adr],ENT_QUOTES,$charset)." 
				</div>
				<div class='colonne_suite'>
					<input type='text' class='saisie-60em' id='ad1_[!!no_X!!]' name='ad1_[!!no_X!!]' value='!!ad1_X!!' onchange=\"mod_coord_el('!!no_X!!');\" />
				</div>
			</div>
		</div>

		<div class='row'>
			<div class='colonne' style='width:95%;'>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					&nbsp;
				</div>
				<div class='colonne_suite'>	
					<input type='text' class='saisie-60em' id='ad2_[!!no_X!!]' name='ad2_[!!no_X!!]' value='!!ad2_X!!' onchange=\"mod_coord_el('!!no_X!!');\" />
				</div>
			</div>
		</div>

		<div class='row'>
			<div class='colonne' style='width:95%;'>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_cp],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne10'> 
					<input type='text' class='saisie-5em' id='cpo_[!!no_X!!]' name='cpo_[!!no_X!!]' value='!!cpo_X!!' onchange=\"mod_coord_el('!!no_X!!');\" />
				</div>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_ville],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne10'> 
					<input type='text' class='saisie-20em' id='vil_[!!no_X!!]' name='vil_[!!no_X!!]' value='!!vil_X!!' onchange=\"mod_coord_el('!!no_X!!');\" />
				</div>
			</div>
		</div>

		<div class='row'>
			<div class='colonne' style='width:95%;'>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_etat],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne10'> 
					<input type='text' class='saisie-15em' id='eta_[!!no_X!!]' name='eta_[!!no_X!!]' value='!!eta_X!!' onchange=\"mod_coord_el('!!no_X!!');\" />
				</div>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_pays],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne10'> 
	 				<input type='text' class='saisie-20em' id='pay_[!!no_X!!]' name='pay_[!!no_X!!]' value='!!pay_X!!' onchange=\"mod_coord_el('!!no_X!!');\" />
				</div>
			</div>
		</div>

		<div class='row'>
			<div class='colonne' style='width:95%;'>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_tel1],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne10'>
					<input type='text' class='saisie-10em' id='te1_[!!no_X!!]' name='te1_[!!no_X!!]' value='!!te1_X!!' onchange=\"mod_coord_el('!!no_X!!');\" />
				</div>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_tel2],ENT_QUOTES,$charset)."
				</div>	
				<div class='colonne10'>				
					<input type='text' class='saisie-10em' id='te2_[!!no_X!!]' name='te2_[!!no_X!!]' value='!!te2_X!!' onchange=\"mod_coord_el('!!no_X!!');\" />
				</div>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_fax],ENT_QUOTES,$charset)." 
		 		</div>
				<div class='colonne10'>
					<input type='text' class='saisie-10em' id='fax_[!!no_X!!]' name='fax_[!!no_X!!]' value='!!fax_X!!' onchange=\"mod_coord_el('!!no_X!!');\" />
				</div>
			</div>
		</div>

		<div class='row'>
			<div class='colonne' style='width:95%;'>
				<div class='colonne10' style='text-align:right; margin-right:0.25em'>
					".htmlentities($msg[acquisition_mail],ENT_QUOTES,$charset)."
				</div>
				<div class='colonne10'>
					<input type='text' class='saisie-60em' id='ema_[!!no_X!!]' name='ema_[!!no_X!!]' value='!!ema_X!!' onchange=\"mod_coord_el('!!no_X!!');\" />
				</div>
			</div>
		</div>
	</div>

";


//    ----------------------------------------------------
//     Bouton de suppression
//    ----------------------------------------------------
$ptab[3] = "<input class='bouton' type='button' value=' $msg[supprimer] ' onclick=\"javascript:confirmation_delete('!!id!!', '!!raison_suppr!!')\" />";


//    ----------------------------------------------------
//     table autorisations
//    ----------------------------------------------------
$ptab[4] = "
	<span class='usercheckbox'>
		<input  type='checkbox' id='user_aut[!!user_id!!]' name='user_aut[!!user_id!!]' !!checked!! value='!!user_id!!' />
	<label for='user_aut[!!user_id!!]' >!!user_name!!</label>
	</span>&nbsp;
";


$script = "
<script type='text/javascript' src='./javascript/tabform.js'></script>

<script type='text/javascript'>

function test_form(form)
{
	if(form.raison.value.length == 0)
	{
		alert('".$msg[acquisition_raison_soc_vide]."');
		document.forms['coordform'].elements['raison'].focus();
		return false;	
	}
	return true;
}

function raz_coord_el(el) {
	document.getElementById('mod_['+el+']').value='-1'; //Indique que l'élement est supprimé
	document.getElementById('lib_['+el+']').value='';
	document.getElementById('cta_['+el+']').value='';
	document.getElementById('ad1_['+el+']').value='';
	document.getElementById('ad2_['+el+']').value='';
	document.getElementById('cpo_['+el+']').value='';
	document.getElementById('vil_['+el+']').value='';
	document.getElementById('eta_['+el+']').value='';
	document.getElementById('pay_['+el+']').value='';
	document.getElementById('te1_['+el+']').value='';
	document.getElementById('te2_['+el+']').value='';
	document.getElementById('fax_['+el+']').value='';
	document.getElementById('ema_['+el+']').value='';
    }
    
function raz_coord() {
	var el = this.getAttribute('id').substring(4);
	document.getElementById('mod_['+el+']').value='-1'; //Indique que l'élement est supprimé
	document.getElementById('lib_['+el+']').value='';
	document.getElementById('cta_['+el+']').value='';
	document.getElementById('ad1_['+el+']').value='';
	document.getElementById('ad2_['+el+']').value='';
	document.getElementById('cpo_['+el+']').value='';
	document.getElementById('vil_['+el+']').value='';
	document.getElementById('eta_['+el+']').value='';
	document.getElementById('pay_['+el+']').value='';
	document.getElementById('te1_['+el+']').value='';
	document.getElementById('te2_['+el+']').value='';
	document.getElementById('fax_['+el+']').value='';
	document.getElementById('ema_['+el+']').value='';
    }
    
function mod_coord_el(el) {
	document.getElementById('mod_['+el+']').value='1'; //Indique que l'élement est modifié
}

function mod_coord() {
	var el = this.getAttribute('id').substring(5);
	el = el.substring(0, el.length-1);
	document.getElementById('mod_['+el+']').value='1'; //Indique que l'élement est modifié
}

function expandElement() {
	var el = this.getAttribute('id').substring(4);
	el = el.substring(0, el.length-4);
	expandBase('elX_'+el+'_', true);
}

function add_coord() {

    template = document.getElementById('racine');

	//récup numéro coordonnée+1
	suf = document.coordform.max_coord;
	suf.value++;
	suffixe = suf.value;

	//creation ligne entete
    row1=document.createElement('div');
    row1.setAttribute('id','el'+suffixe+'Child');
    row1.className='row';
    
		no = document.createElement('input');
		no.setAttribute('type', 'hidden');
	    no.setAttribute('name','no_['+suffixe+']');
	    no.setAttribute('id','no_['+suffixe+']');
	    no.setAttribute('value','0');	    
	    
	    mod = document.createElement('input');
		mod.setAttribute('type', 'hidden');
	    mod.setAttribute('name','mod_['+suffixe+']');
	    mod.setAttribute('id','mod_['+suffixe+']');
	    mod.setAttribute('value','-1');
	    
	    col11 = document.createElement('div');
	    col11.className='colonne80';
	    
	    	img111 = document.createElement('img');
	    	img111.setAttribute('src', './images/minus.gif');
	    	img111.setAttribute('name', 'imEx');
	    	img111.setAttribute('id', 'elX_'+suffixe+'_Img');
	    	img111.className='img_plus';
	    	img111.setAttribute('border', '0');
	    	img111.onclick=expandElement;
				    	
	    	lab111 = document.createElement('label');
	    	lab111.className='etiquette';
	    		lib1111=document.createTextNode(' ".$msg[acquisition_adr]." '+suffixe);
	    	lab111.appendChild(lib1111);
	        		
	    col11.appendChild(img111);
		col11.appendChild(lab111);
		
	row1.appendChild(no);
	row1.appendChild(mod);
	row1.appendChild(col11);
			

	chd=document.createElement('div');
	chd.setAttribute('id', 'elX_'+suffixe+'_Child');
	chd.setAttribute('name', 'elX_'+suffixe+'_Child');
	chd.className='child';
	chd.setAttribute('style','display:block;');


		row2=document.createElement('div');
		row2.className='row';


		row3=document.createElement('div');
		row3.className='row';
	
			col31=document.createElement('div');
			col31.className='colonne';
			col31.setAttribute('style','width:95%;');
	
				col311=document.createElement('div');
				col311.className='colonne10';
				col311.setAttribute('style','text-align:right;margin-right:0.25em;');
				
					lib3111=document.createTextNode(' ".$msg[acquisition_coord_lib]."');
				
				col311.appendChild(lib3111);
				
				col312=document.createElement('div');
				col312.className='colonne_suite';
				
					in3121=document.createElement('input');
					in3121.setAttribute('type','text');
					in3121.className='saisie-60em';
					in3121.setAttribute('id','lib_['+suffixe+']');
					in3121.setAttribute('name','lib_['+suffixe+']');
					in3121.onchange = mod_coord;
					
				col312.appendChild(in3121);
			
			col31.appendChild(col311);
			col31.appendChild(col312);
		
			col32=document.createElement('div');
			col32.className='colonne_suite';

	 			bt321=document.createElement('input');
	 			bt321.setAttribute('type', 'button');
	 			bt321.setAttribute('id', 'raz_'+suffixe)
			 	bt321.className='bouton';
			 	bt321.setAttribute('title', '".$msg[acquisition_bt_raz_title]."');
			 	bt321.setAttribute('value', '".$msg[raz]."');
			 	bt321.onclick = raz_coord;
		
			col32.appendChild(bt321);

		row3.appendChild(col31);
		row3.appendChild(col32);


		row4=document.createElement('div');
		row4.className='row';
		
			col41=document.createElement('div');
			col41.className='colonne';
			col41.setAttribute('style', 'width:95%;');
			
				col411=document.createElement('div');
				col411.className='colonne10';
				col411.setAttribute('style', 'text-align:right; margin-right:0.25em;');
				
					lib4111=document.createTextNode('".$msg[acquisition_contact]."');
		
				col411.appendChild(lib4111);
			
				col412=document.createElement('div');
				col412.className='colonne_suite';
			
					in4121=document.createElement('input');
					in4121.setAttribute('type','text');
					in4121.className='saisie-60em';
					in4121.setAttribute('id','cta_['+suffixe+']');
					in4121.setAttribute('name','cta_['+suffixe+']');
					in4121.onchange = mod_coord;
					
				col412.appendChild(in4121);
			
			col41.appendChild(col411);
			col41.appendChild(col412);
		
		row4.appendChild(col41);
				

		row5=document.createElement('div');
		row5.className='row';
		
			col51=document.createElement('div');
			col51.className='colonne';
			col51.setAttribute('style', 'width:95%;');
			
				col511=document.createElement('div');
				col511.className='colonne10';
				col511.setAttribute('style', 'text-align:right; margin-right:0.25em;');
											
					lib5111=document.createTextNode('".$msg[acquisition_adr]."');
				
				col511.appendChild(lib5111);
				
				col512=document.createElement('div');
				col512.className='colonne_suite';
				
					in5121=document.createElement('input');
					in5121.setAttribute('type','text');
					in5121.className='saisie-60em';
					in5121.setAttribute('id','ad1_['+suffixe+']');
					in5121.setAttribute('name','ad1_['+suffixe+']');
					in5121.onChange = mod_coord;
				
				col512.appendChild(in5121);
				
			col51.appendChild(col511);
			col51.appendChild(col512);
			
		row5.appendChild(col51);	
				
				
		row6=document.createElement('div');
		row6.className='row';
		
			col61=document.createElement('div');
			col61.className='colonne';
			col61.setAttribute('style', 'width:95%;');

				col611=document.createElement('div');
				col611.className='colonne10';
				col611.setAttribute('style', 'text-align:right;margin-right:0.25em;');
					
					sp6111=document.createTextNode('\u00A0');
			
				col611.appendChild(sp6111);
			
				col612=document.createElement('div');
				col612.className='colonne_suite';
				
					in6121=document.createElement('input');
					in6121.setAttribute('type','text');
					in6121.className='saisie-60em';
					in6121.setAttribute('id','ad2_['+suffixe+']');
					in6121.setAttribute('name','ad2_['+suffixe+']');
					in6121.onChange = mod_coord;
				
				col612.appendChild(in6121);

			col61.appendChild(col611);
			col61.appendChild(col612);
		
		row6.appendChild(col61);


		row7=document.createElement('div');
		row7.className='row';

			col71=document.createElement('div');
			col71.className='colonne';
			col71.setAttribute('style', 'width:95%;');
		
				col711=document.createElement('div');
				col711.className='colonne10';
				col711.setAttribute('style', 'text-align:right; margin-right:0.25em;');
					
					lib7111=document.createTextNode('".$msg[acquisition_cp]."');
			
				col711.appendChild(lib7111);
				
				col712=document.createElement('div');
				col712.className='colonne10';
				
					in7121=document.createElement('input');
					in7121.setAttribute('type','text');
					in7121.className='saisie-5em';
					in7121.setAttribute('id','cpo_['+suffixe+']');
					in7121.setAttribute('name','cpo_['+suffixe+']');
					in7121.onChange = mod_coord;
				
				col712.appendChild(in7121);
			
				col713=document.createElement('div');
				col713.className='colonne10';
				col713.setAttribute('style', 'text-align:right; margin-right:0.25em;');
				
					lib7131=document.createTextNode('".$msg[acquisition_ville]."');
		
				col713.appendChild(lib7131);
			
				col714=document.createElement('div');
				col714.className='colonne10';
				
					in7141=document.createElement('input');
					in7141.setAttribute('type','text');
					in7141.className='saisie-20em';
					in7141.setAttribute('id','vil_['+suffixe+']');
					in7141.setAttribute('name','vil_['+suffixe+']');
					in7141.onChange = mod_coord;
				
				col714.appendChild(in7141);	

			col71.appendChild(col711);
			col71.appendChild(col712);
			col71.appendChild(col713);
			col71.appendChild(col714);
			
		row7.appendChild(col71);
		

		row8=document.createElement('div');
		row8.className='row';

			col81=document.createElement('div');
			col81.className='colonne';
			col81.setAttribute('style', 'width:95%;');
		
				col811=document.createElement('div');
				col811.className='colonne10';
				col811.setAttribute('style', 'text-align:right; margin-right:0.25em;');
					
					lib8111=document.createTextNode('".$msg[acquisition_etat]."');
			
				col811.appendChild(lib8111);
			
				col812=document.createElement('div');
				col812.className='colonne10';
					
					in8121=document.createElement('input');
					in8121.setAttribute('type','text');
					in8121.className='saisie-15em';
					in8121.setAttribute('id','eta_['+suffixe+']');
					in8121.setAttribute('name','eta_['+suffixe+']');
					in8121.onChange = mod_coord;
					
				col812.appendChild(in8121);
			
				col813=document.createElement('div');
				col813.className='colonne10';
				col813.setAttribute('style', 'text-align:right; margin-right:0.25em;');
				
					lib8131=document.createTextNode('".$msg[acquisition_pays]."');
		
				col813.appendChild(lib8131);
			
				col814=document.createElement('div');
				col814.className='colonne10';
				
					in8141=document.createElement('input');
					in8141.setAttribute('type','text');
					in8141.className='saisie-20em';
					in8141.setAttribute('id','pay_['+suffixe+']');
					in8141.setAttribute('name','pay_['+suffixe+']');
					in8141.onChange = mod_coord;
				
				col814.appendChild(in8141);	

			col81.appendChild(col811);
			col81.appendChild(col812);
			col81.appendChild(col813);			
			col81.appendChild(col814);
		
		row8.appendChild(col81);


		row9=document.createElement('div');
		row9.className='row';

			col91=document.createElement('div');
			col91.className='colonne';
			col91.setAttribute('style', 'width:95%;');
		
				col911=document.createElement('div');
				col911.className='colonne10';
				col911.setAttribute('style', 'text-align:right; margin-right:0.25em;');
					
					lib9111=document.createTextNode('".$msg[acquisition_tel1]."');
			
				col911.appendChild(lib9111);
			
				col912=document.createElement('div');
				col912.className='colonne10';
					
					in9121=document.createElement('input');
					in9121.setAttribute('type','text');
					in9121.className='saisie-10em';
					in9121.setAttribute('id','te1_['+suffixe+']');
					in9121.setAttribute('name','te1_['+suffixe+']');
					in9121.onChange = mod_coord;
					
				col912.appendChild(in9121);
			
				col913=document.createElement('div');
				col913.className='colonne10';
				col913.setAttribute('style', 'text-align:right; margin-right:0.25em;');
					
					lib9131=document.createTextNode('".$msg[acquisition_tel2]."');
				
				col913.appendChild(lib9131);
				
				col914=document.createElement('div');
				col914.className='colonne10';
									
					in9141=document.createElement('input');
					in9141.setAttribute('type','text');
					in9141.className='saisie-10em';
					in9141.setAttribute('id','te2_['+suffixe+']');
					in9141.setAttribute('name','te2_['+suffixe+']');
					in9141.onChange = mod_coord;
					
				col914.appendChild(in9141);
				
			
				col915=document.createElement('div');
				col915.className='colonne10';
				col915.setAttribute('style', 'text-align:right; margin-right:0.25em;');
				
					lib9151=document.createTextNode('".$msg[acquisition_fax]."');
				
				col915.appendChild(lib9151);
				
				
				col916=document.createElement('div');
				col916.className='colonne10';				

					in9161=document.createElement('input');
					in9161.setAttribute('type','text');
					in9161.className='saisie-10em';
					in9161.setAttribute('id','fax_['+suffixe+']');
					in9161.setAttribute('name','fax_['+suffixe+']');
					in9161.onChange = mod_coord;
				
				col916.appendChild(in9161);	
			
			col91.appendChild(col911);
			col91.appendChild(col912);
			col91.appendChild(col913);
			col91.appendChild(col914);
			col91.appendChild(col915);
			col91.appendChild(col916);
	
		row9.appendChild(col91);
	
	
		rowa=document.createElement('div');
		rowa.className='row';
		
			cola1=document.createElement('div');
			cola1.className='colonne';
			cola1.setAttribute('style', 'width:95%;')
			
				cola11=document.createElement('div');
				cola11.className='colonne10';
				cola11.setAttribute('style', 'text-align:right; margin-right:0.25em;');
				
					liba111=document.createTextNode('".$msg[acquisition_mail]."');
		
				cola11.appendChild(liba111);
			
				cola12=document.createElement('div');
				cola12.className='colonne_suite';
				
					ina121=document.createElement('input');
					ina121.setAttribute('type','text');
					ina121.className='saisie-60em';
					ina121.setAttribute('id','ema_['+suffixe+']');
					ina121.setAttribute('name','ema_['+suffixe+']');
					ina121.onChange = mod_coord;
					
				cola12.appendChild(ina121);
			
			cola1.appendChild(cola11);
			cola1.appendChild(cola12);
			
		rowa.appendChild(cola1);

		
	chd.appendChild(row2);
	chd.appendChild(row3);
	chd.appendChild(row4);
	chd.appendChild(row5);
	chd.appendChild(row6);
	chd.appendChild(row7);
	chd.appendChild(row8);
	chd.appendChild(row9);
	chd.appendChild(rowa);
	
	ret2=document.createElement('br');
		
template.appendChild(row1);
template.appendChild(chd);
template.appendChild(ret2);

}

</script>";

?>

