<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: dico_synonymes.tpl.php,v 1.3 2008-03-03 13:47:17 ohennequin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

// template pour le form de dictionnaire des synonymes

$select_prop = "scrollbars=yes, toolbar=no, dependent=yes, resizable=yes";

$aff_liste_mots="
<script type='text/javascript'>
<!--
	function test_form(form)
	{
		if(form.word_search.value.length == 0)
			{
				alert('Le champ de recherche est vide.');
				return false;
			} else return true;
	}
-->
</script>
<h1>".$msg["semantique"]." : ".$msg["dico_syn"]."</h1>
<div class='row'>
	<form class='form-$current_module' name='search_mots' method='post' action='./autorites.php?categ=semantique&sub=synonyms&action=search' onSubmit='if (test_form(search_mots)) return true; else return false;'>
	<h3>".$msg["357"]." : ".$msg["dico_syn"]."</h3>\n
	<div class='form-contenu'>
		<input type='text' class='saisie-30em' name='word_search' value=''>
	</div>
	<div class='row'>
	<div class='left'><input type='submit' class='bouton' value='".$msg["142"]."'>\n
	&nbsp;<input type='button' class='bouton' value='".$msg["word_create"]."' onclick=\"document.location='./autorites.php?categ=semantique&sub=synonyms&action=view';\"></div>\n
	!!see_last_words!!
	</div>
	<div class='row'></div>	
	</form>
	</div>\n
<div class='row'>&nbsp;</div>\n
<div class='row'><h3>".$msg["search_words_libelle"]." !!cle!!</h3></div>\n
!!lettres!!\n
<div class='row'>&nbsp;</div>\n
!!liste_mots!!\n
<script> document.search_mots.word_search.focus(); </script>
";

//template pour le form ajout/modification d'un mot
$aff_modif_mot="
<script src='javascript/ajax.js'></script>
!!mots_js!!\n
<h1>".$msg["semantique"]." : ".$msg["dico_syn"]."</h1>
<div class='row'>&nbsp;</div>
<form class='form-$current_module' id='words' name='words' method='post' action='!!action!!&action=modif'>\n
<h3><div class='left'>".$msg["syn_word"]."</div></h3><div class='row'></div><hr class='spacer' />\n
<div class='form-contenu'>
".$msg["word_selected"]." : <input type='text' class='saisie-20em' name='word_selected' value=\"!!mot_original!!\">\n
<input type='hidden' name='word_code_selected' value='!!id_mot!!'>
<input type='hidden' name='max_word' value=\"!!max_word!!\" />
<div class='row'>&nbsp;</div>
<b>".$msg["word_syn"]." :</b><div class='row'>&nbsp;</div>
!!mots_lie!!
<div id='addword'/>
</div>\n
</div><div class='row'><hr class='spacer' />
<div class='left'><input type='button' class='bouton' value='".$msg["76"]."' onClick=\"history.back(-1);\">&nbsp;<input type='button' class='bouton' value='".$msg["77"]."' onClick='document.words.submit();'></div>
!!supprimer!!
</div><div class='row'></div></form>\n
<script> 
ajax_pack_element(document.words.f_word0);
document.words.word_selected.focus(); 
</script>";

//fonctions ajax ajout de zones de texte
$mot_js="
<script>
	function fonction_selecteur_word() {
        name=this.getAttribute('id').substring(4);
		name_id = name.substr(0,6)+'_code'+name.substr(6);
        openPopUp('./select.php?what=synonyms&caller=words&p1='+name_id+'&p2='+name, 'select_word', 400, 400, -2, -2, '$select_prop');
    }
    function fonction_raz_word() {
        name=this.getAttribute('id').substring(4);
		name_id = name.substr(0,6)+'_code'+name.substr(6);
        document.getElementById(name).value='';
		document.getElementById(name_id).value='';
    }
    function add_word() {
        template = document.getElementById('addword');
        word=document.createElement('div');
        word.className='row';

        suffixe = eval('document.words.max_word.value')
        nom_id = 'f_word'+suffixe
        f_word = document.createElement('input');
        f_word.setAttribute('name',nom_id);
        f_word.setAttribute('id',nom_id);
        f_word.setAttribute('type','text');
        f_word.className='saisie-30emr';
        f_word.setAttribute('value','');
		f_word.setAttribute('completion','synonyms');
        
		id = 'f_word_code'+suffixe
		f_word_code = document.createElement('input');
		f_word_code.setAttribute('name',id);
        f_word_code.setAttribute('id',id);
        f_word_code.setAttribute('type','hidden');
		f_word_code.setAttribute('value','');
 
        del_f_word = document.createElement('input');
        del_f_word.setAttribute('id','del_f_word'+suffixe);
        del_f_word.onclick=fonction_raz_word;
        del_f_word.setAttribute('type','button');
        del_f_word.className='bouton';
        del_f_word.setAttribute('readonly','');
        del_f_word.setAttribute('value','$msg[raz]');

        sel_f_word = document.createElement('input');
        sel_f_word.setAttribute('id','sel_f_word'+suffixe);
        sel_f_word.setAttribute('type','button');
        sel_f_word.className='bouton';
        sel_f_word.setAttribute('readonly','');
        sel_f_word.setAttribute('value','$msg[parcourir]');
        sel_f_word.onclick=fonction_selecteur_word;

        word.appendChild(f_word);
		word.appendChild(f_word_code);
        space=document.createTextNode(' ');
        word.appendChild(space);
        word.appendChild(del_f_word);
        word.appendChild(space.cloneNode(false));
        word.appendChild(sel_f_word);
        
        template.appendChild(word);

        document.words.max_word.value=suffixe*1+1*1 ;
        ajax_pack_element(f_word);
    }
</script>";

//template de zone de texte pour chaque mot lié				
$aff_mot_lie="
<div class='row'>
<input type='text' class='saisie-30emr' id='f_word!!iword!!' name='f_word!!iword!!' value=\"!!word!!\" autfield='f_word_code!!iword!!' completion=\"synonyms\" />
<input type='hidden' id='f_word_code!!iword!!' name='f_word_code!!iword!!' value='!!id_word!!'>
<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_word!!iword!!.value='';this.form.f_word_code!!iword!!.value=''; \" />
<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=synonyms&caller=words&p1=f_word_code!!iword!!&p2=f_word!!iword!!&deb_rech='+escape(this.form.f_word!!iword!!.value), 'select_word', 400, 400, -2, -2, '$select_prop')\" />
!!bouton_ajouter!!
</div>\n";
?>
