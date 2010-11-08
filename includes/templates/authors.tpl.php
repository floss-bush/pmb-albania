<?php 
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: authors.tpl.php,v 1.26 2010-06-16 12:13:47 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

$selector_prop = "toolbar=no, dependent=yes,resizable=yes, scrollbars=yes";

//	----------------------------------
// $author_form : form saisie auteur
// champs :
//	author_type : 70/71 (select)
//	author_nom element d'entrée
//	author_rejete element rejeté
//	date1 (text max:4) date 1
//	date2 (text max:4) date 2
//	voir_id (hidden) id de la forme retenue
//	voir_libelle 
$author_form = jscript_unload_question()."
<script type='text/javascript'>
<!--
	function test_form(form)
	{
		if(form.author_nom.value.length == 0)
			{
				alert(\"$msg[213]\");
				return false;
			}

		if(form.voir_libelle.value.length == 0)
			{
				form.voir_id.value='';
			}
		unload_off();	
		return true;
	}

function confirm_delete() {
        result = confirm(\"${msg[confirm_suppr]}\");
        if(result) {
        	unload_off();
            document.location='./autorites.php?categ=auteurs&sub=delete&id=!!id!!&user_input=!!user_input_url!!&page=!!page!!&nbr_lignes=!!nbr_lignes!!';
		} else
            document.forms['saisie_auteur'].elements['author_nom'].focus();
    }
	function check_link(id) {
		w=window.open(document.getElementById(id).value);
		w.focus();
	}
-->
</script>
<script type='text/javascript'>

function display_part(type)
{
	
	var collectivite_part = document.getElementById('collectivite_part');
	
	var dupli_exist = document.getElementById('dupli_btn');
	
	if(type == '70'){
		collectivite_part.style.display = 'none';
		if(dupli_exist)
			document.getElementById('dupli_btn').style.visibility = 'hidden';
		
		document.getElementById('author_nom').setAttribute('completion', '');	
		
	} else {		
		collectivite_part.style.display = 'table-cell';
		if(dupli_exist)	
			document.getElementById('dupli_btn').style.visibility = 'visible';
		if(type == '71') 
			document.getElementById('author_nom').setAttribute('completion', 'collectivite_name');
		else  document.getElementById('author_nom').setAttribute('completion', 'congres_name');	
	}  
		
	var libelle_titre = document.getElementById('libelle_titre');
	if(type == '70') {
		libelle_titre.innerHTML='".addslashes($msg[207])."';
	} else if(type == '71'){
		libelle_titre.innerHTML='".addslashes($msg["aut_ajout_collectivite"])."';
	} else if(type == '72'){
		libelle_titre.innerHTML='".addslashes($msg["aut_ajout_congres"])."';
	}
} 

</script>
<script src='javascript/ajax.js'></script>
<form class='form-$current_module' id='saisie_auteur' name='saisie_auteur' method='post' action='!!action!!' onSubmit=\"return false\" >
<h3><label id='libelle_titre'>!!libelle!!</label></h3>
<div class='form-contenu'>
<!--	type	-->
<div class='row'>
	<label class='etiquette' for='author_type_sel'>$msg[205]</label>
	</div>
<div class='row'>
	<select name='author_type' id='author_type_sel'  onchange='display_part(this.value)'>
		<option value='70'!!sel_pp!!>$msg[203]</option>
		<option value='71'!!sel_coll!!>$msg[204]</option>
		<option value='72'!!sel_congres!!>".$msg["congres_libelle"]."</option>		
	</select>
	</div>

<div class='colonne2'>
	<!--	nom	-->
	<div class='row'>
		<label class='etiquette' for='author_nom'>$msg[201]</label>
		</div>
	<div class='row'>
		<input type='text' class='saisie-30em' id='author_nom' name='author_nom' autfield='rien' completion='!!completion_name!!' value=\"!!author_nom!!\" />
		<input id='rien' name='rien' value='' type='hidden'>
		</div>
        </div>
<div class='colonne_suite'>
	<!--	rejete	-->
	<div class='row'>
		<label class='etiquette' for='form_rejete'>$msg[202]</label>
		</div>
	<div class='row'>
		<input type='text' class='saisie-30em' id='form_rejete' name='author_rejete' value=\"!!author_rejete!!\"  />
		</div>
        </div>
<!--	dates	-->
<div class='row'>
	<label class='etiquette' for='form_dates'>$msg[713]</label>
	</div>
<div class='row'>
	<input type='text' class='saisie-80em' id='form_dates' name='date' value='!!date!!'>
	</div>
	
<div id='collectivite_part' style='!!display!!'>	
	
<!--	lieu	-->
<div class='row'>
	<label class='etiquette' for='form_lieu'>".$msg["congres_lieu_libelle"]."</label>
	</div>
<div class='row'>
	<input type='text' class='saisie-80em' id='form_lieu' name='lieu' value='!!lieu!!'>
	</div>	
<div class='colonne2'>
	<!--	ville	-->
	<div class='row'>
		<label class='etiquette' for='form_ville'>".$msg["congres_ville_libelle"]."</label>
		</div>
	<div class='row'>
		<input type='text' class='saisie-30em' id='form_ville' name='ville' value=\"!!ville!!\" />
		</div>
        </div>
<div class='colonne_suite'>
	<!--	pays	-->
	<div class='row'>
		<label class='etiquette' for='form_pays'>".$msg["congres_pays_libelle"]."</label>
		</div>
	<div class='row'>
		<input type='text' class='saisie-30em' id='form_pays' name='pays' value=\"!!pays!!\"  />
		</div>
        </div>
<div class='colonne2'>
	<!--	subdivision	-->
	<div class='row'>
		<label class='etiquette' for='form_subdivision'>".$msg["congres_subdivision_libelle"]."</label>
		</div>
	<div class='row'>
		<input type='text' class='saisie-30em' id='form_subdivision' name='subdivision' value=\"!!subdivision!!\" />
		</div>
        </div>
<div class='colonne_suite'>
	<!--	numero	-->
	<div class='row'>
		<label class='etiquette' for='form_numero'>".$msg["congres_numero_libelle"]."</label>
		</div>
	<div class='row'>
		<input type='text' class='saisie-30em' id='form_numero' name='numero' value=\"!!numero!!\"  />
		</div>
        </div>		
</div>	
<!--	forme retenue	-->
<div class='row'>
	<label class='etiquette' for='voir_libelle'>$msg[206]</label>
	</div>
<div class='row'>
	<input type='text' class='saisie-50emr' id='voir_libelle' name='voir_libelle' value=\"!!voir_libelle!!\" completion=\"authors\" autfield=\"voir_id\" autexclude=\"!!id!!\"
    onkeypress=\"if (window.event) { e=window.event; } else e=event; if (e.keyCode==9) { openPopUp('./select.php?what=auteur&caller=saisie_auteur&param1=voir_id&param2=voir_libelle', 'select_author', $selector_x_size, $selector_x_size, -2, -2, '$selector_prop'); }\" />

	<input class='bouton' type='button' onclick=\"openPopUp('./select.php?what=auteur&caller=saisie_auteur&param1=voir_id&param2=voir_libelle', 'select_author', $selector_x_size, $selector_x_size, -2, -2, '$selector_prop')\" title='$msg[157]' value='$msg[parcourir]' />
	<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.voir_libelle.value=''; this.form.voir_id.value='0'; \" />
	<input type='hidden' value='!!voir_id!!' name='voir_id' id='voir_id' />
	</div>
<!-- web -->
<div class='row'>
	<label class='etiquette' for='author_web'>$msg[147]</label>
	</div>
<div class='row'>
	<input type='text' class='saisie-80em' name='author_web' id='author_web' value=\"!!author_web!!\" maxlength='255' />
	<input class='bouton' type='button' onClick=\"check_link('author_web')\" title='$msg[CheckLink]' value='$msg[CheckButton]' />
</div>

<!-- Commentaire -->
<div class='row'>
	<label class='etiquette' for='author_comment'>$msg[author_comment]</label>
</div>
<div class='row'>
	<textarea class='saisie-80em' id='author_comment' name='author_comment' cols='62' rows='4' wrap='virtual'>!!author_comment!!</textarea>
</div>
<!-- aut_link -->
</div>
<!--	boutons	-->
<div class='row'>
	<div class='left'>
		<input type='button' class='bouton' value='$msg[76]' onClick=\"unload_off();document.location='./autorites.php?categ=auteurs&sub=reach&user_input=!!user_input_url!!&page=!!page!!&nbr_lignes=!!nbr_lignes!!&type_autorite=!!type_autorite!!';\" />
		<input type='button' value='$msg[77]' class='bouton' id='btsubmit' onClick=\"if (test_form(this.form)) this.form.submit();\" />
		!!remplace!!
		!!voir_notices!!
		!!dupliquer!!
		<input type='hidden' name='page' value='!!page!!' />
		<input type='hidden' name='nbr_lignes' value='!!nbr_lignes!!' />
		<input type='hidden' name='user_input' value=\"!!user_input!!\" />
		</div>
	<div class='right'>
		!!delete!!
		</div>
	</div>
<div class='row'></div>
</form>
<script type='text/javascript'>
	ajax_parse_dom();
	document.forms['saisie_auteur'].elements['author_nom'].focus();
</script>
!!liste_des_renvoyes_vers!!
";

// $author_replace : form remplacement auteur
$author_replace = "
<script src='javascript/ajax.js'></script>
<form class='form-$current_module' name='author_replace' method='post' action='./autorites.php?categ=auteurs&sub=replace&id=!!id!!' onSubmit=\"return false\" >
<h3>$msg[159] !!old_author_libelle!! </h3>
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='par'>$msg[160]</label>
		</div>
	<div class='row'>
		<input type='text' class='saisie-50emr' id='author_libelle' name='author_libelle' value=\"\" completion=\"authors\" autfield=\"by\" autexclude=\"!!id!!\"
    	onkeypress=\"if (window.event) { e=window.event; } else e=event; if (e.keyCode==9) { openPopUp('./select.php?what=auteur&caller=author_replace&param1=by&param2=author_libelle&no_display=!!id!!', 'select_ed', $selector_x_size, $selector_x_size, -2, -2, '$selector_prop'); }\" />

		<input class='bouton' type='button' onclick=\"openPopUp('./select.php?what=auteur&caller=author_replace&param1=by&param2=author_libelle&no_display=!!id!!', 'select_ed', $selector_x_size, $selector_x_size, -2, -2, '$selector_prop')\" title='$msg[157]' value='$msg[parcourir]' />
		<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.author_libelle.value=''; this.form.by.value='0'; \" />
		<input type='hidden' name='by' id='by' value=''>
		</div>
	</div>
<div class='row'>
	<input type='button' class='bouton' value='$msg[76]' onClick=\"document.location='./autorites.php?categ=auteurs&sub=author_form&id=!!id!!';\">
	<input type='button' class='bouton' value='$msg[159]' id='btsubmit' onClick=\"this.form.submit();\" >
	</div>
</form>
<script type='text/javascript'>
	ajax_parse_dom();
	document.forms['author_replace'].elements['author_libelle'].focus();
</script>
";
