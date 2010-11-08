<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: dico_empty_words.tpl.php,v 1.4 2009-05-16 11:19:54 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

//$autorites_list_empty_word : template form recherche et liste de mots vides
$autorites_list_empty_word="
<script type='text/javascript'>
<!--
	function test_form(form)
	{
		if(form.search_empty_word.value.length == 0)
			{
				alert('Le champ de recherche est vide.');
				return false;
			} else return true;
	}
	
	function test_calcul(form)
	{
		if (form.nb_noti.value.length!=0&&!isNaN(form.nb_noti.value)&&parseInt(form.nb_noti.value)<=100&&parseInt(form.nb_noti.value)>0) {
			return true;
		} else {
			alert('".$msg["no_valid_pourcent"]."');
			return false;
		}
	}

	function modify_type_mot_vide(id_mot,type_lien) {
		var url= \"./ajax.php?module=autorites&categ=type_empty_word&fname=modify_type_empty_word\";
		var state_col = new http_request();
		if(state_col.request(url,1,\"&id_mot=\" + id_mot + \"&type_lien=\" + type_lien)) alert(state_col.get_text());
		else {
			switch (type_lien) {
				case 2:
					document.getElementById('type_lien2_' + id_mot).checked=true;
					document.getElementById('type_lien3_' + id_mot).checked=false;
					document.getElementById('type_lien4_' + id_mot).checked=false;
				break;
				case 3:
					document.getElementById('type_lien2_' + id_mot).checked=false;
					document.getElementById('type_lien3_' + id_mot).checked=true;
					document.getElementById('type_lien4_' + id_mot).checked=false;
				break;
				case 4:
					document.getElementById('type_lien2_' + id_mot).checked=false;
					document.getElementById('type_lien3_' + id_mot).checked=false;
					document.getElementById('type_lien4_' + id_mot).checked=true;
				break;
			}
			return 1;
		}
	}
-->
</script>
<div class='row'>
	<h1>".$msg["semantique"]." : ".$msg["dico_empty_words"]."</h1>
</div>
<div class='row'>
	<form class='form-$current_module' name='search_mots_vides' method='post' action='./autorites.php?categ=semantique&sub=empty_words&action=search' onSubmit='if (test_form(search_mots_vides)) return true; else return false;'>
	<h3>".$msg["357"]." : ".$msg["dico_empty_words"]."</h3>\n
	<div class='form-contenu'>
		<input type='text' class='saisie-30em' name='search_empty_word' value=''><br />
	</div>
	<div class='row'>
	<input type='radio' name='type_mot_vide' value='0' onClick='document.search_mots_vides.submit();' !!checked0!!>&nbsp;&nbsp;".$msg["all_empty_word"]."&nbsp;&nbsp;
	<input type='radio' name='type_mot_vide' value='2' onClick='document.search_mots_vides.submit();' !!checked2!!>&nbsp;&nbsp;".$msg["empty_words_calculated"]."&nbsp;&nbsp;
	<input type='radio' name='type_mot_vide' value='3' onClick='document.search_mots_vides.submit();' !!checked3!!>&nbsp;&nbsp;".$msg["empty_words_created"]."&nbsp;&nbsp;
	<input type='radio' name='type_mot_vide' value='4' onClick='document.search_mots_vides.submit();' !!checked4!!>&nbsp;&nbsp;".$msg["no_empty_words"]."</div>
	<div class='row'>&nbsp;</div>
	<div class='row'><div class='left'><input type='submit' class='bouton' value='".$msg["142"]."'>\n
	&nbsp;<input class='bouton' type='button' value='".$msg["add_empty_word"]."' onClick=\"document.location='./autorites.php?categ=semantique&sub=empty_words&action=add'\" /></div>\n
	!!see_last_words!!
	</div>
	<div class='row'></div>
	</form>
	</div><div class='row'>&nbsp;</div><div class='row'><form name='calcul_mots_vides' method='post' action='./autorites.php?categ=semantique&sub=empty_words&action=calculate' onSubmit='if (test_calcul(calcul_mots_vides)) return true; else return false;'><strong>".$msg["libelle_seuil_notices_calcul"]."</strong>		
		<input type='text' class='saisie-10em' name='nb_noti' value='!!nb_noti!!'>&nbsp;<strong>%</strong>&nbsp;&nbsp;<input type='submit' class='bouton' value='".$msg["actualiser"]."'></form></div><div class='row'>&nbsp;</div>
	<div class='row'><h3>".$msg["search_words_libelle"]." !!cle!!</h3></div>	
	<div class='row'>
	<table>\n			
	!!liste_mots!!
</table>\n
</div>!!pagination!!<div class='row'>&nbsp;</div>
<script type='text/javascript'>document.forms['search_mots_vides'].elements['search_empty_word'].focus();</script>\n";

//$autorites_add_empty_word : template form ajout de mot vide
$autorites_add_empty_word="<div class='row'>
			<h1>".$msg["semantique"]." : ".$msg["dico_empty_words"]."</h1>
			</div><form name='add_empty_word' class='form-$current_module' method=post action=\"./autorites.php?categ=semantique&sub=empty_words&action=update\">
<h3>".$msg["add_empty_word"]."</h3>
<!--    Contenu du form    -->
<div class='form-contenu'>
	<div class='row'>
		<input type='text' name='text_empty_word' value='' class='saisie-30em' />
		</div>
	
</div>
<input type='button' class='bouton' value='".$msg["76"]."' onClick=\"history.back(-1);\">
<input type='submit' class='bouton' value='".$msg["ajouter"]."'>
</form>
<script type='text/javascript'>document.forms['add_empty_word'].elements['text_empty_word'].focus();</script>
";
?>
