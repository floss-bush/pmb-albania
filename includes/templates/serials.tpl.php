<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: serials.tpl.php,v 1.135.2.2 2011-06-17 12:43:45 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");
//	template pour la gestion des périodiques

$serial_header = "
	<h1>!!page_title!!</h1>";

$serial_footer = "";

$serial_access_form ="
<script type='text/javascript'>
<!--
      function aide_regex()
      {
            var fenetreAide;
            var prop = 'scrollbars=yes, resizable=yes';
            fenetreAide = openPopUp('./help.php?whatis=regex', 'regex_howto', 500, 400, -2, -2, prop);
      }
	function test_form()
	{
		if (document.serial_search.user_query.value=='') {
			document.serial_search.user_query.value='*';
			}
		return true;
	}
-->
</script>

<form class='form-$current_module' name='serial_search' method='post' action='./catalog.php?categ=serials&sub=search' onSubmit='return test_form();' >
<h3>".$msg["recherche"]." : $msg[771]</h3>
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette'>$msg[bulletin_mention_titre_court]</label>
	</div>
	<div class='row'>
		<input class='saisie-inline' id='user_query' type='text' size='36' name='user_query' value='!!user_query!!' />
		</div>
	<div class='row'>
		<span class='astuce'>$msg[155]
			<a class='aide' title='$msg[1900]$msg[1901]$msg[1902]' href='./help.php?whatis=regex' onclick='aide_regex();return false;'>$msg[1550]</a>
			</span>
		</div>
	<div class='row'>
		<label class='etiquette'>$msg[165]</label>
	</div>
	<div class='row'>
		<input class='saisie-inline' id='issn_query' type='text' size='36' name='issn_query' value='!!issn_query!!' />
		</div>
	</div>
<div class='row'>
	<input class='bouton' type='submit' value='$msg[142]' />
	</div>
</form>
<script type=\"text/javascript\">
	document.forms['serial_search'].elements['user_query'].focus();
</script>
";

$serial_access_form = str_replace('!!user_query!!', htmlentities(stripslashes($user_query ),ENT_QUOTES, $charset), $serial_access_form);
$serial_access_form = str_replace('!!issn_query!!', htmlentities(stripslashes($issn_query ),ENT_QUOTES, $charset), $serial_access_form);

// template pour le form de catalogage
$select1_prop = "toolbar=no, dependent=yes, resizable=yes, scrollbars=yes";
$select2_prop = "toolbar=no, dependent=yes, resizable=yes, scrollbars=yes";
$select3_prop = "toolbar=no, dependent=yes, resizable=yes, scrollbars=yes";
$select_categ_prop = "toolbar=no, dependent=yes, resizable=yes, scrollbars=yes";

// nombre de parties du form
$nb_onglets = 5;

//	----------------------------------------------------
// 	  $ptab[0] : contenu de l'onglet 0 (Titre)
//	----------------------------------------------------
$ptab[0] = "
<!-- onglet 0 -->
<div id='el0Parent' class='parent'>
	<div class='row'>
		<h3>
			<img src='./images/minus.gif' class='img_plus' align='top' name='imEx' id='el0Img' title='$msg[236]' border='0' onClick=\"expandBase('el0', true); return false;\" />
			$msg[712]
		</h3>
	</div>
</div>

<div id='el0Child' class='child' etirable='yes' title='".htmlentities($msg[236],ENT_QUOTES, $charset)."' >
    <div id='el0Child_0' title='".htmlentities($msg[237],ENT_QUOTES, $charset)."' movable='yes'>
	<div class='row'>
		<label class='etiquette' for='f_tit1'>$msg[237]</label>
	</div>
	<div class='row'>
		<input id='f_tit1' type='text' class='saisie-80em' name='f_tit1' value=\"!!tit1!!\" />
	</div>
	</div>

    <div id='el0Child_1' title='".htmlentities($msg[239],ENT_QUOTES, $charset)."' movable='yes'>
	<div class='row'>
		<label class='etiquette' for='f_tit3'>$msg[239]</label>
	</div>
	<div class='row'>
		<input class='saisie-80em' type='text' id='f_tit3' name='f_tit3' value=\"!!tit3!!\" />
	</div>
	</div>

    <div id='el0Child_2' title='".htmlentities($msg[240],ENT_QUOTES, $charset)."' movable='yes'>
	<div class='row'>
		<label class='etiquette' for='f_tit4'>$msg[240]</label>
	</div>
	<div class='row'>
		<input class='saisie-80em' id='f_tit4' type='text' name='f_tit4' value=\"!!tit4!!\"  />
	</div>
	</div>
</div>
";

$ptab_bul[0] = "
<!-- onglet 0 -->
<div id='el0Parent' class='parent'>
	<div class='row'>
		<h3>
			<img src='./images/plus.gif' class='img_plus' align='top' name='imEx' id='el0Img' title='$msg[236]' border='0' onClick=\"expandBase('el0', true); return false;\" />
			$msg[712]
		</h3>
	</div>
</div>

<div id='el0Child' class='child' etirable='yes' title='".htmlentities($msg[236],ENT_QUOTES, $charset)."' >
    <div id='el0Child_0' title='".htmlentities($msg[239],ENT_QUOTES, $charset)."' movable='yes'>
	<div class='row'>
		<label class='etiquette' for='f_tit3'>$msg[239]</label>
	</div>
	<div class='row'>
		<input class='saisie-80em' type='text' id='f_tit3' name='f_tit3' value=\"!!tit3!!\" />
	</div>
	</div>

    <div id='el0Child_1' title='".htmlentities($msg[240],ENT_QUOTES, $charset)."' movable='yes'>
	<div class='row'>
		<label class='etiquette' for='f_tit4'>$msg[240]</label>
	</div>
	<div class='row'>
		<input class='saisie-80em' id='f_tit4' type='text' name='f_tit4' value=\"!!tit4!!\"  />
	</div>
	</div>
</div>
";

//	----------------------------------------------------
// 	  $ptab[1] : contenu de l'onglet 1 (Mention de responsabilité)
//	----------------------------------------------------
$aut_fonctions = new marc_list('function');

$ptab[1] = "
<script>
    function fonction_selecteur_auteur() {
        name=this.getAttribute('id').substring(4);
        name_id = name.substr(0,6)+'_id'+name.substr(6);
        openPopUp('./select.php?what=auteur&caller=notice&param1='+name_id+'&param2='+name+'&dyn=1&deb_rech='+".pmb_escape()."(document.getElementById(name).value), 'select_author2', 400, 400, -2, -2, '$select1_prop');
    }
    function fonction_selecteur_auteur_change(field) {
    	// id champ text = 'f_aut'+n+suffixe
    	// id champ hidden = 'f_aut'+n+'_id'+suffixe; 
    	// select.php?what=auteur&caller=notice&param1=f_aut0_id&param2=f_aut0&deb_rech='+t
        name=field.getAttribute('id');
        name_id = name.substr(0,6)+'_id'+name.substr(6);
        openPopUp('./select.php?what=auteur&caller=notice&param1='+name_id+'&param2='+name+'&dyn=1&deb_rech='+".pmb_escape()."(document.getElementById(name).value), 'select_author2', 400, 400, -2, -2, '$select1_prop');
    }
    function fonction_raz_auteur() {
        name=this.getAttribute('id').substring(4);
        name_id = name.substr(0,6)+'_id'+name.substr(6);
        document.getElementById(name_id).value=0;
        document.getElementById(name).value='';
    }
    function fonction_selecteur_fonction() {
        name=this.getAttribute('id').substring(4);
        name_code = name.substr(0,4)+'_code'+name.substr(4);
        openPopUp('./select.php?what=function&caller=notice&param1='+name_code+'&param2='+name+'&dyn=1', 'select_fonction2', 400, 400, -2, -2, '$select1_prop');
    }
    function fonction_raz_fonction() {
        name=this.getAttribute('id').substring(4);
        name_code = name.substr(0,4)+'_code'+name.substr(4);
        document.getElementById(name_code).value=0;
        document.getElementById(name).value='';
    }
    function add_aut(n) {
        template = document.getElementById('addaut'+n);
        aut=document.createElement('div');
        aut.className='row';

        // auteur
        colonne=document.createElement('div');
        colonne.className='colonne2';
        row=document.createElement('div');
        row.className='row';
        suffixe = eval('document.notice.max_aut'+n+'.value');
        nom_id = 'f_aut'+n+suffixe;
        f_aut0 = document.createElement('input');
        f_aut0.setAttribute('name',nom_id);
        f_aut0.setAttribute('id',nom_id);
        f_aut0.setAttribute('type','text');
        f_aut0.className='saisie-30emr';
        f_aut0.setAttribute('value','');
		f_aut0.setAttribute('completion','authors');
        f_aut0.setAttribute('autfield','f_aut'+n+'_id'+suffixe);

        sel_f_aut0 = document.createElement('input');
        sel_f_aut0.setAttribute('id','sel_f_aut'+n+suffixe);
        sel_f_aut0.setAttribute('type','button');
        sel_f_aut0.className='bouton';
        sel_f_aut0.setAttribute('readonly','');
        sel_f_aut0.setAttribute('value','$msg[parcourir]');
        sel_f_aut0.onclick=fonction_selecteur_auteur;

        del_f_aut0 = document.createElement('input');
        del_f_aut0.setAttribute('id','del_f_aut'+n+suffixe);
        del_f_aut0.onclick=fonction_raz_auteur;
        del_f_aut0.setAttribute('type','button');
        del_f_aut0.className='bouton';
        del_f_aut0.setAttribute('readonly','');
        del_f_aut0.setAttribute('value','$msg[raz]');

        f_aut0_id = document.createElement('input');
        f_aut0_id.name='f_aut'+n+'_id'+suffixe;
        f_aut0_id.setAttribute('type','hidden');
        f_aut0_id.setAttribute('id','f_aut'+n+'_id'+suffixe);
        f_aut0_id.setAttribute('value','');

        //f_aut0_content.appendChild(f_aut0);
		row.appendChild(f_aut0);
        space=document.createTextNode(' ');
        row.appendChild(space);
        row.appendChild(sel_f_aut0);
        space=document.createTextNode(' ');
        row.appendChild(space);
        row.appendChild(del_f_aut0);
        row.appendChild(f_aut0_id);
        colonne.appendChild(row);
        aut.appendChild(colonne);
		
        // fonction
        colonne=document.createElement('div');
        colonne.className='colonne_suite';
        row=document.createElement('div');
        row.className='row';
        suffixe = eval('document.notice.max_aut'+n+'.value');
        nom_id = 'f_f'+n+suffixe;
        f_f0 = document.createElement('input');
        f_f0.setAttribute('name',nom_id);
        f_f0.setAttribute('id',nom_id);
        f_f0.setAttribute('type','text');
        f_f0.className='saisie-15emr';
        f_f0.setAttribute('value','".$aut_fonctions->table[$value_deflt_fonction]."');
		f_f0.setAttribute('completion','fonction');
        f_f0.setAttribute('autfield','f_f'+n+'_code'+suffixe);

        sel_f_f0 = document.createElement('input');
        sel_f_f0.setAttribute('id','sel_f_f'+n+suffixe);
        sel_f_f0.setAttribute('type','button');
        sel_f_f0.className='bouton';
        sel_f_f0.setAttribute('readonly','');
        sel_f_f0.setAttribute('value','$msg[parcourir]');
        sel_f_f0.onclick=fonction_selecteur_fonction;

        del_f_f0 = document.createElement('input');
        del_f_f0.setAttribute('id','del_f_f'+n+suffixe);
        del_f_f0.onclick=fonction_raz_fonction;
        del_f_f0.setAttribute('type','button');
        del_f_f0.className='bouton';
        del_f_f0.setAttribute('readonly','readonly');
        del_f_f0.setAttribute('value','$msg[raz]');
	
        f_f0_code = document.createElement('input');
        f_f0_code.name='f_f'+n+'_code'+suffixe;
        f_f0_code.setAttribute('type','hidden');
        f_f0_code.setAttribute('id','f_f'+n+'_code'+suffixe);
        f_f0_code.setAttribute('value','$value_deflt_fonction');

        row.appendChild(f_f0);
        space=document.createTextNode(' ');
        row.appendChild(space);
        row.appendChild(sel_f_f0);
        space=document.createTextNode(' ');
        row.appendChild(space);
        row.appendChild(del_f_f0);
        row.appendChild(f_f0_code);
        colonne.appendChild(row);

        aut.appendChild(colonne);
        template.appendChild(aut);
        eval('document.notice.max_aut'+n+'.value=suffixe*1+1*1');
        ajax_pack_element(f_aut0);
		ajax_pack_element(f_f0);
    }

    function fonction_selecteur_categ() {
        name=this.getAttribute('id').substring(4);
        name_id = name.substr(0,7)+'_id'+name.substr(7);
        openPopUp('./select.php?what=categorie&caller=notice&p1='+name_id+'&p2='+name+'&dyn=1', 'select_categ', 700, 500, -2, -2, '$select_categ_prop');
    }
    function fonction_raz_categ() {
        name=this.getAttribute('id').substring(4);
        name_id = name.substr(0,7)+'_id'+name.substr(7);
        document.getElementById(name_id).value=0;
        document.getElementById(name).value='';
    }
    function add_categ() {
        template = document.getElementById('addcateg');
        categ=document.createElement('div');
        categ.className='row';

        suffixe = eval('document.notice.max_categ.value')
        nom_id = 'f_categ'+suffixe
        f_categ = document.createElement('input');
        f_categ.setAttribute('name',nom_id);
        f_categ.setAttribute('id',nom_id);
        f_categ.setAttribute('type','text');
        f_categ.className='saisie-80emr';
        f_categ.setAttribute('value','');
		f_categ.setAttribute('completion','categories_mul');
        f_categ.setAttribute('autfield','f_categ_id'+suffixe);
 
        del_f_categ = document.createElement('input');
        del_f_categ.setAttribute('id','del_f_categ'+suffixe);
        del_f_categ.onclick=fonction_raz_categ;
        del_f_categ.setAttribute('type','button');
        del_f_categ.className='bouton';
        del_f_categ.setAttribute('readonly','');
        del_f_categ.setAttribute('value','$msg[raz]');

        f_categ_id = document.createElement('input');
        f_categ_id.name='f_categ_id'+suffixe;
        f_categ_id.setAttribute('type','hidden');
        f_categ_id.setAttribute('id','f_categ_id'+suffixe);
        f_categ_id.setAttribute('value','');

        categ.appendChild(f_categ);
        space=document.createTextNode(' ');
        categ.appendChild(space);
        categ.appendChild(del_f_categ);
        categ.appendChild(f_categ_id);

        template.appendChild(categ);

        document.notice.max_categ.value=suffixe*1+1*1 ;
        ajax_pack_element(f_categ);
    }
    function fonction_selecteur_lang() {
        name=this.getAttribute('id').substring(4);
        name_id = name.substr(0,6)+'_code'+name.substr(6);
        openPopUp('./select.php?what=lang&caller=notice&p1='+name_id+'&p2='+name, 'select_lang', 400, 400, -2, -2, '$select2_prop');
    }
    function fonction_raz_lang() {
        name=this.getAttribute('id').substring(4);
        name_id = name.substr(0,6)+'_code'+name.substr(6);
        document.getElementById(name_id).value=0;
        document.getElementById(name).value='';
    }
    function add_lang() {
        template = document.getElementById('addlang');
        lang=document.createElement('div');
        lang.className='row';

        suffixe = eval('document.notice.max_lang.value')
        nom_id = 'f_lang'+suffixe
        f_lang = document.createElement('input');
        f_lang.setAttribute('name',nom_id);
        f_lang.setAttribute('id',nom_id);
        f_lang.setAttribute('type','text');
        f_lang.className='saisie-30emr';
        f_lang.setAttribute('value','');
		f_lang.setAttribute('completion','langue');
        f_lang.setAttribute('autfield','f_lang_code'+suffixe);
 
        del_f_lang = document.createElement('input');
        del_f_lang.setAttribute('id','del_f_lang'+suffixe);
        del_f_lang.onclick=fonction_raz_lang;
        del_f_lang.setAttribute('type','button');
        del_f_lang.className='bouton';
        del_f_lang.setAttribute('readonly','');
        del_f_lang.setAttribute('value','$msg[raz]');

        sel_f_lang = document.createElement('input');
        sel_f_lang.setAttribute('id','sel_f_lang'+suffixe);
        sel_f_lang.setAttribute('type','button');
        sel_f_lang.className='bouton';
        sel_f_lang.setAttribute('readonly','');
        sel_f_lang.setAttribute('value','$msg[parcourir]');
        sel_f_lang.onclick=fonction_selecteur_lang;

        f_lang_code = document.createElement('input');
        f_lang_code.name='f_lang_code'+suffixe;
        f_lang_code.setAttribute('type','hidden');
        f_lang_code.setAttribute('id','f_lang_code'+suffixe);
        f_lang_code.setAttribute('value','');

        lang.appendChild(f_lang);
        space=document.createTextNode(' ');
        lang.appendChild(space);
        lang.appendChild(del_f_lang);
        lang.appendChild(space.cloneNode(false));
        lang.appendChild(sel_f_lang);
        lang.appendChild(f_lang_code);

        template.appendChild(lang);

        document.notice.max_lang.value=suffixe*1+1*1 ;
        ajax_pack_element(f_lang);
    }

    function fonction_selecteur_langorg() {
        name=this.getAttribute('id').substring(4);
        name_id = name.substr(0,9)+'_code'+name.substr(9);
        openPopUp('./select.php?what=lang&caller=notice&p1='+name_id+'&p2='+name, 'select_lang', 400, 400, -2, -2, '$select2_prop');
    }
    function fonction_raz_langorg() {
        name=this.getAttribute('id').substring(4);
        name_id = name.substr(0,9)+'_code'+name.substr(9);
        document.getElementById(name_id).value=0;
        document.getElementById(name).value='';
    }
    function add_langorg() {
        template = document.getElementById('addlangorg');
        langorg=document.createElement('div');
        langorg.className='row';

        suffixe = eval('document.notice.max_langorg.value')
        nom_id = 'f_langorg'+suffixe
        f_langorg = document.createElement('input');
        f_langorg.setAttribute('name',nom_id);
        f_langorg.setAttribute('id',nom_id);
        f_langorg.setAttribute('type','text');
        f_langorg.className='saisie-30emr';
        f_langorg.setAttribute('value','');
		f_langorg.setAttribute('completion','langue');
        f_langorg.setAttribute('autfield','f_langorg_code'+suffixe);
 
        del_f_langorg = document.createElement('input');
        del_f_langorg.setAttribute('id','del_f_langorg'+suffixe);
        del_f_langorg.onclick=fonction_raz_langorg;
        del_f_langorg.setAttribute('type','button');
        del_f_langorg.className='bouton';
        del_f_langorg.setAttribute('readonly','');
        del_f_langorg.setAttribute('value','$msg[raz]');

        sel_f_langorg = document.createElement('input');
        sel_f_langorg.setAttribute('id','sel_f_langorg'+suffixe);
        sel_f_langorg.setAttribute('type','button');
        sel_f_langorg.className='bouton';
        sel_f_langorg.setAttribute('readonly','');
        sel_f_langorg.setAttribute('value','$msg[parcourir]');
        sel_f_langorg.onclick=fonction_selecteur_langorg;

        f_lang_codeorg = document.createElement('input');
        f_lang_codeorg.name='f_langorg_code'+suffixe;
        f_lang_codeorg.setAttribute('type','hidden');
        f_lang_codeorg.setAttribute('id','f_langorg_code'+suffixe);
        f_lang_codeorg.setAttribute('value','');

        langorg.appendChild(f_langorg);
        space=document.createTextNode(' ');
        langorg.appendChild(space);
        langorg.appendChild(del_f_langorg);
        langorg.appendChild(space.cloneNode(false));
        langorg.appendChild(sel_f_langorg);
        langorg.appendChild(f_lang_codeorg);

        template.appendChild(langorg);

        document.notice.max_langorg.value=suffixe*1+1*1 ;
        ajax_pack_element(f_langorg);
    }

</script>
<div id='el1Parent' class='parent'>
	<div class='row'>
	<h3>
		<img src='./images/plus.gif' class='img_plus' name='imEx' id='el1Img' onClick=\"expandBase('el1', true); return false;\" title='$msg[243]' border='0' />
		$msg[243]
	</h3>
	</div>
</div>
<div id='el1Child' class='child' etirable='yes' title='".htmlentities($msg[243],ENT_QUOTES, $charset)."'>
    <div id='el1Child_0' title='".htmlentities($msg[244],ENT_QUOTES, $charset)."' movable='yes'>
	<!--	Auteur principal	-->
	<div class='row'>
		<div class='colonne2'>
			<label for='f_aut0' class='etiquette'>$msg[244]</label>
			<div class='row'>
				<input type='text' completion='authors' autfield='f_aut0_id' id='auteur0' class='saisie-30emr' name='f_aut0' value=\"!!aut0!!\" />

				<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=auteur&caller=notice&param1=f_aut0_id&param2=f_aut0&deb_rech='+".pmb_escape()."(this.form.f_aut0.value), 'select_author0', 400, 400, -2, -2, '$select1_prop')\" />
				<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_aut0.value=''; this.form.f_aut0_id.value='0'; \" />
				<input type='hidden' name='f_aut0_id' id='f_aut0_id' value=\"!!aut0_id!!\" />
				</div>
			</div>
		<!--	Fonction	-->
		<div class='colonne_suite'>
			<label for='f_f0' class='etiquette'>$msg[245]</label>
			<div class='row'>
		        <input type='text' class='saisie-15emr' id='f_f0' name='f_f0' value=\"!!f0!!\" completion=\"fonction\" autfield=\"f_f0_code\" />
				<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=function&caller=notice&p1=f_f0_code&p2=f_f0', 'select_func0', 400, 400, -2, -2, '$select2_prop')\" />
				<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_f0.value=''; this.form.f_f0_code.value='0'; \" />
				<input type='hidden' name='f_f0_code' id='f_f0_code' value=\"!!f0_code!!\" />
				</div>
			</div>
		</div>
	</div>

    <div id='el1Child_2' title='".htmlentities($msg[246],ENT_QUOTES, $charset)."' movable='yes'>
	<!--	autres auteurs	-->
	<div class='row'>
		<div class='row'>
			<label for='f_aut1' class='etiquette'>$msg[246]</label>
			<input type='hidden' name='max_aut1' value=\"!!max_aut1!!\" />
		</div>
		!!autres_auteurs!!
		<div id='addaut1'>
			<input type='button' class='bouton' value='+' onClick=\"add_aut(1);\"/>
			</div>
		</div>
	</div>

    <div id='el1Child_3' title='".htmlentities($msg[247],ENT_QUOTES, $charset)."' movable='yes'>
	<!--	Auteurs secondaires 	-->
	<div class='row'>
		<div class='row'>
			<label for='f_aut2' class='etiquette'>$msg[247]</label>
			<input type='hidden' name='max_aut2' value=\"!!max_aut2!!\" />
		</div>
		!!auteurs_secondaires!!
		<div id='addaut2'>
			<input type='button' class='bouton' value='+' onClick=\"add_aut(2);\"/>
			</div>
		</div>
	</div>	
</div>
";

//	----------------------------------------------------
//	Autres auteurs
//	----------------------------------------------------
$ptab[11] = "
		<div id='el1Child_2b_first' class='colonne2'>
			<div class='row'>
               	<input type='text' class='saisie-30emr' completion='authors' autfield='f_aut1_id!!iaut!!' id='f_aut1!!iaut!!' name='f_aut1!!iaut!!' value=\"!!aut1!!\" />

				<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=auteur&caller=notice&param1=f_aut1_id!!iaut!!&param2=f_aut1!!iaut!!&deb_rech='+".pmb_escape()."(this.form.f_aut1!!iaut!!.value), 'select_author2', 400, 400, -2, -2, '$select1_prop')\" />
				<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_aut1!!iaut!!.value=''; this.form.f_aut1_id!!iaut!!.value='0'; \" />
				<input type='hidden' name='f_aut1_id!!iaut!!' id='f_aut1_id!!iaut!!' value=\"!!aut1_id!!\" />
				</div>
			</div>
		<!--	Fonction	-->
		<div id='el1Child_2b_others' class='colonne_suite'>
			<div class='row'>
                <input type='text' class='saisie-15emr' id='f_f1!!iaut!!' name='f_f1!!iaut!!' completion='fonction' autfield='f_f1_code!!iaut!!' value=\"!!f1!!\" />
				<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=function&caller=notice&p1=f_f1_code!!iaut!!&p2=f_f1!!iaut!!', 'select_func2', 400, 400, -2, -2, '$select2_prop')\" />
				<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_f1!!iaut!!.value=''; this.form.f_f1_code!!iaut!!.value='0'; \" />
				<input type='hidden' name='f_f1_code!!iaut!!' id='f_f1_code!!iaut!!' value=\"!!f1_code!!\" />
				</div>
			</div>
	" ;

//	----------------------------------------------------
//	Autres secondaires
//	----------------------------------------------------
$ptab[12] = "
		<div id='el1Child_3b_first' class='colonne2'>
			<div class='row'>
             	<input type='text' class='saisie-30emr' completion='authors' autfield='f_aut2_id!!iaut!!' id='f_aut2!!iaut!!' name='f_aut2!!iaut!!' value=\"!!aut2!!\" />

				<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=auteur&caller=notice&param1=f_aut2_id!!iaut!!&param2=f_aut2!!iaut!!&deb_rech='+".pmb_escape()."(this.form.f_aut2!!iaut!!.value), 'select_author2', 400, 400, -2, -2, '$select1_prop')\" />
				<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_aut2!!iaut!!.value=''; this.form.f_aut2_id!!iaut!!.value='0'; \" />
				<input type='hidden' name='f_aut2_id!!iaut!!' id='f_aut2_id!!iaut!!' value=\"!!aut2_id!!\" />
				</div>
			</div>
		<!--	Fonction	-->
		<div id='el1Child_3b_others' class='colonne_suite'>
			<div class='row'>
                <input type='text' class='saisie-15emr' id='f_f2!!iaut!!' name='f_f2!!iaut!!' completion='fonction' autfield='f_f2_code!!iaut!!' value=\"!!f2!!\" />
				<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=function&caller=notice&p1=f_f2_code!!iaut!!&p2=f_f2!!iaut!!', 'select_func2', 400, 400, -2, -2, '$select2_prop')\" />
				<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_f2!!iaut!!.value=''; this.form.f_f2_code!!iaut!!.value='0'; \" />
				<input type='hidden' name='f_f2_code!!iaut!!' id='f_f2_code!!iaut!!' value=\"!!f2_code!!\" />
				</div>
			</div>
	" ;


//	----------------------------------------------------
// 	  $ptab[2] : contenu de l'onglet 2 Editeurs
//	----------------------------------------------------
$ptab[2] = "
<!-- onglet 2 -->
<div id='el2Parent' class='parent'>
    <h3>
    <img src='./images/plus.gif' class='img_plus' name='imEx' id='el2Img' border='0' onClick=\"expandBase('el2', true); return false;\" />
    ".$msg['serial_onglet_editeurs']."
    </h3>
</div>

<div id='el2Child' class='child' etirable='yes' title='".htmlentities($msg[249],ENT_QUOTES, $charset)."'>

<div id='el2Child_0' title='".htmlentities($msg[164],ENT_QUOTES, $charset)."' movable='yes'>
<!--    Editeur    -->
	<div id='el2Child_0a' class='row'>
    	<label for='f_ed1' class='etiquette'>$msg[164]</label>
		</div>
	<div id='el2Child_0b' class='row'>
		<input type='text' completion='publishers' autfield='f_ed1_id' id='f_ed1' name='f_ed1' value=\"!!ed1!!\" class='saisie-30emr' />

	    <input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=editeur&caller=notice&p1=f_ed1_id&p2=f_ed1&p3=f_coll_id&p4=f_coll&p5=f_subcoll_id&p6=f_subcoll&deb_rech='+this.form.f_ed1.value, 'select_ed1', 400, 400, -2, -2, '$select1_prop')\" />
    	<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_ed1.value=''; this.form.f_ed1_id.value='0'; \" />
	    <input type='hidden' name='f_ed1_id' id='f_ed1_id' value=\"!!ed1_id!!\" />
		</div>
	</div>

	<div id='el2Child_4' title='".htmlentities($msg[252],ENT_QUOTES, $charset)."' movable='yes'>
	<!--    Année    -->
		<div id='el2Child_4a' class='row'>
			<label for='f_year' class='etiquette'>$msg[252]</label>
		</div>
		<div id='el2Child_4b' class='row'>
			<input type='text' class='saisie-30em' id='f_year' name='f_year' value=\"!!year!!\" />
	    </div>
	</div>
	
	<div id='el2Child_7' title='".htmlentities($msg[254],ENT_QUOTES, $charset)."' movable='yes'>
	<!--    Autre éditeur    -->
	<div id='el2Child_7a' class='row'>
    	<label for='f_ed2' class='etiquette'>$msg[254]</label>
		</div>
	<div id='el2Child_7b' class='row'>
    	<input type='text'   class='saisie-30emr' id='f_ed2' name='f_ed2' value=\"!!ed2!!\" 
	    onchange=\"openPopUp('./select.php?what=editeur&caller=notice&p1=f_ed2_id&p2=f_ed2&p3=dummy&p4=dummy&p5=dummy&p6=dummy&deb_rech='+this.form.f_ed2.value, 'select_ed1', 400, 400, -2, -2, '$select1_prop')\" />
    	<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=editeur&caller=notice&p1=f_ed2_id&p2=f_ed2&p3=dummy&p4=dummy&p5=dummy&p6=dummy&deb_rech='+this.form.f_ed2.value, 'select_ed1', 400, 400, -2, -2, '$select1_prop')\" />
	    <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_ed2.value=''; this.form.f_ed2_id.value='0'; \" />
    	<input type='hidden' name='dummy' />
	    <input type='hidden' name='f_ed2_id' value=\"!!ed2_id!!\" />
		</div>
	</div>
</div>
";

//	----------------------------------------------------
//	ISBN, EAN ou no. commercial
// 	  $ptab[30] : contenu de l'onglet 30
//	----------------------------------------------------
$ptab[30] = "
<!-- onglet 30 -->
<div id='el30Parent' class='parent'>
<h3>
	<img src='./images/plus.gif' class='img_plus' name='imEx' id='el30Img' title='$msg[255]' border='0' onClick=\"expandBase('el30', true); return false;\" />
	$msg[serial_ISSN]
</h3>
</div>

<div id='el30Child' class='child' etirable='yes' title='".htmlentities($msg[serial_ISSN],ENT_QUOTES, $charset)."'>
	<div id='el30Child_0' title='$msg[serial_ISSN]' movable='yes'>
		<!--	ISBN, EAN ou no. commercial	-->
		<div id='el30Child_0a' class='row'>
			<label for='f_cb' class='etiquette'>$msg[serial_ISSN]</label>
			</div>
		<div id='el30Child_0b' class='row'>
			<input class='saisie-20emr' id='f_cb' name='f_cb' readonly value=\"!!cb!!\" />
			<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./catalog/setcb.php?notice_id=!!notice_id!!', 'getcb', 220, 100, -2, -2, 'toolbar=no, resizable=yes')\" />
			<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_cb.value=''; \" />
			</div>
		</div>
	</div>
";


//	----------------------------------------------------
// 	  $ptab[3] : contenu de l'onglet 3 (Notes)
//	----------------------------------------------------
$ptab[3] = "
<!-- onglet 3 -->
<div id='el5Parent' class='parent'>
<h3>
    <img src='./images/plus.gif' class='img_plus' name='imEx' id='el5Img' title='$msg[263]' border='0' onClick=\"expandBase('el5', true); return false;\" />
    $msg[264]
</h3>
</div>

<div id='el5Child' class='child' etirable='yes' title='".htmlentities($msg[264],ENT_QUOTES, $charset)."'>

<div id='el5Child_0' title='".htmlentities($msg[265],ENT_QUOTES, $charset)."' movable='yes'>
<!--    Note générale    -->
<div id='el5Child_0a' class='row'>
    <label for='f_n_gen' class='etiquette'>$msg[265]</label>
</div>
<div id='el5Child_0b' class='row'>
    <textarea id='f_n_gen' class='saisie-80em' name='f_n_gen' rows='3' wrap='virtual'>!!n_gen!!</textarea>
</div>
</div>

<div id='el5Child_1' title='".htmlentities($msg[266],ENT_QUOTES, $charset)."' movable='yes'>
<!--    Note de contenu    -->
<div id='el5Child_1a' class='row'>
    <label for='f_n_contenu' class='etiquette'>$msg[266]</label>
</div>
<div id='el5Child_1b' class='row'>
    <textarea class='saisie-80em' id='f_n_contenu' name='f_n_contenu' rows='5' wrap='virtual'>!!n_contenu!!</textarea>
</div>
</div>

<div id='el5Child_2' title='".htmlentities($msg[267],ENT_QUOTES, $charset)."' movable='yes'>
<!--    Résumé/extrait    -->
<div id='el5Child_2a' class='row'>
    <label for='f_n_resume' class='etiquette'>$msg[267]</label>
</div>
<div id='el5Child_2b' class='row'>
    <textarea class='saisie-80em' id='f_n_resume' name='f_n_resume' rows='5' wrap='virtual'>!!n_resume!!</textarea>
</div>
</div>
</div>
";

//	----------------------------------------------------
// 	  $ptab[4] : contenu de l'onglet 4 (Indexation)
//	----------------------------------------------------
$ptab[4] = "
<!-- onglet 4 -->
<div id='el6Parent' class='parent'>
<h3>
    <img src='./images/plus.gif' class='img_plus' name='imEx' id='el6Img' title=\"$msg[268]\" border='0' onClick=\"expandBase('el6', true); return false;\" />
    $msg[269]
</h3>
</div>

<div id='el6Child' class='child' etirable='yes' title='".htmlentities($msg[269],ENT_QUOTES, $charset)."'>

<div id='el6Child_0' title='".htmlentities($msg[134],ENT_QUOTES, $charset)."' movable='yes'>
    <!--    Catégories    -->
    <div id='el6Child_0a' class='row'>
        <label for='f_categ' class='etiquette'>$msg[134]</label>
    </div>
    <input type='hidden' name='max_categ' value=\"!!max_categ!!\" />
    !!categories_repetables!!
    <div id='addcateg'/>
        </div>
</div>

<div id='el6Child_1' title='".htmlentities($msg[indexint_catal_title],ENT_QUOTES, $charset)."' movable='yes'>
    <!--    indexation interne    -->
    <div id='el6Child_1a' class='row'>
        <label for='f_categ' class='etiquette'>$msg[indexint_catal_title]</label>
    </div>
    <div id='el6Child_1b' class='row'>
        <input type='text' class='saisie-80emr' id='f_indexint' name='f_indexint' value=\"!!indexint!!\" completion=\"indexint\" autfield=\"f_indexint_id\" />
        <input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=indexint&caller=notice&param1=f_indexint_id&param2=f_indexint&parent=0&deb_rech='+".pmb_escape()."(this.form.f_indexint.value)+'&typdoc='+(this.form.typdoc.value)+'&num_pclass=!!num_pclass!!', 'select_categ', 600, 320, -2, -2, '$select3_prop')\" />
        <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_indexint.value=''; this.form.f_indexint_id.value='0'; \" />
        <input type='hidden' name='f_indexint_id' id='f_indexint_id' value='!!indexint_id!!' />
    </div>

</div>

<div id='el6Child_2' title='".htmlentities($msg[324],ENT_QUOTES, $charset)."' movable='yes'>
    <!--    Indexation libre    -->
    <div id='el6Child_2a' class='row'>
        <label for='f_indexation' class='etiquette'>$msg[324]</label>
    </div>
    <div id='el8Child_2b' class='row'>
        <textarea class='saisie-80em' id='f_indexation' completion='tags' keys='113' name='f_indexation' rows='3' wrap='virtual'>!!f_indexation!!</textarea>
    </div>
    <div id='el8Child_2_comment' class='row'>
        <span>$msg[324]$msg[1901]$msg[325]</span>
    </div>
</div>
</div>
";
//	----------------------------------------------------
//	 Categories repetables
// 	  $ptab[40]
//	----------------------------------------------------
$ptab[40] = "
    <div id='el6Child_0b_first' class='row'>
        <input type='text' class='saisie-80emr' id='f_categ!!icateg!!' name='f_categ!!icateg!!' value=\"!!categ_libelle!!\" completion=\"categories_mul\" autfield=\"f_categ_id!!icateg!!\" />

        <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_categ!!icateg!!.value=''; this.form.f_categ_id!!icateg!!.value='0'; \" />
        <input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=categorie&caller=notice&p1=f_categ_id!!icateg!!&p2=f_categ!!icateg!!&dyn=1&parent=0', 'select_categ', 700, 500, -2, -2, '$select_categ_prop')\" />
        <input type='hidden' name='f_categ_id!!icateg!!' id='f_categ_id!!icateg!!' value='!!categ_id!!' />
        <input type='button' class='bouton' value='+' onClick=\"add_categ();\"/>
    </div>
	";
$ptab[401] = "
    <div id='el6Child_0b_others' class='row'>
        <input type='text' class='saisie-80emr' id='f_categ!!icateg!!' name='f_categ!!icateg!!' value=\"!!categ_libelle!!\" completion=\"categories_mul\" autfield=\"f_categ_id!!icateg!!\" />

        <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_categ!!icateg!!.value=''; this.form.f_categ_id!!icateg!!.value='0'; \" />
        <input type='hidden' name='f_categ_id!!icateg!!' id='f_categ_id!!icateg!!' value='!!categ_id!!' />
    </div>
	";

//    ----------------------------------------------------
//     Langue de la publication
//       $ptab[7] : contenu de l'onglet 7 (langues)
//    ----------------------------------------------------

$ptab[5] = "
<!-- onglet 7 -->
<div id='el7Parent' class='parent'>
<h3>
    <img src='./images/plus.gif' class='img_plus' name='imEx' id='el7Img' title='langues' border='0' onClick=\"expandBase('el7', true); return false;\" />
    $msg[710]
</h3>
</div>

<div id='el7Child' class='child' etirable='yes' title='".htmlentities($msg[710],ENT_QUOTES, $charset)."'>

<div id='el7Child_0' title='".htmlentities($msg[710],ENT_QUOTES, $charset)."' movable='yes'>
    <!--    Langues    -->
    <div id='el7Child_0a' class='row'>
        <label for='f_langue' class='etiquette'>$msg[710]</label>
    </div>
    <input type='hidden' name='max_lang' value=\"!!max_lang!!\" />
    !!langues_repetables!!
    <div id='addlang'/>
        </div>
</div>

<div id='el7Child_1' title='".htmlentities($msg[711],ENT_QUOTES, $charset)."' movable='yes'>
    <!--    Langues    -->
    <div id='el7Child_1a' class='row'>
        <label for='f_langorg' class='etiquette'>$msg[711]</label>
    </div>
    <input type='hidden' name='max_langorg' value=\"!!max_langorg!!\" />
    !!languesorg_repetables!!
    <div id='addlangorg'/>
        </div>
</div>

</div>
";

//    ----------------------------------------------------
//     Langues repetables
//       $ptab[70]
//    ----------------------------------------------------
$ptab[50] = "
    <div id='el7Child_0a' class='row'>
        <input type='text' class='saisie-30emr' id='f_lang!!ilang!!' name='f_lang!!ilang!!' value=\"!!lang!!\" completion=\"langue\" autfield=\"f_lang_code!!ilang!!\" />
        <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_lang!!ilang!!.value=''; this.form.f_lang_code!!ilang!!.value=''; \" />
		<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=lang&caller=notice&p1=f_lang_code!!ilang!!&p2=f_lang!!ilang!!', 'select_lang', 400, 400, -2, -2, '$select2_prop')\" />
        <input type='hidden' name='f_lang_code!!ilang!!' id='f_lang_code!!ilang!!' value='!!lang_code!!' />
        <input type='button' class='bouton' value='+' onClick=\"add_lang();\"/>
    </div>
    ";

$ptab[501] = "
    <div id='el7Child_0a' class='row'>
        <input type='text' class='saisie-30emr' id='f_lang!!ilang!!' name='f_lang!!ilang!!' value=\"!!lang!!\" completion=\"langue\" autfield=\"f_lang_code!!ilang!!\" />
        <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_lang!!ilang!!.value=''; this.form.f_lang_code!!ilang!!.value=''; \" />
		<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=lang&caller=notice&p1=f_lang_code!!ilang!!&p2=f_lang!!ilang!!', 'select_lang', 400, 400, -2, -2, '$select2_prop')\" />
        <input type='hidden' name='f_lang_code!!ilang!!' id='f_lang_code!!ilang!!' value='!!lang_code!!' />
    </div>
    ";

//    ----------------------------------------------------
//     Langues originales repetables
//       $ptab[71]
//    ----------------------------------------------------
$ptab[51] = "
    <div id='el7Child_0b' class='row'>
        <input type='text' class='saisie-30emr' id='f_langorg!!ilangorg!!' name='f_langorg!!ilangorg!!' value=\"!!langorg!!\" completion=\"langue\" autfield=\"f_langorg_code!!ilangorg!!\" />
        <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_langorg!!ilangorg!!.value=''; this.form.f_langorg_code!!ilangorg!!.value=''; \" />
		<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=lang&caller=notice&p1=f_langorg_code!!ilangorg!!&p2=f_langorg!!ilangorg!!', 'select_lang', 400, 400, -2, -2, '$select2_prop')\" />
        <input type='hidden' name='f_langorg_code!!ilangorg!!' id='f_langorg_code!!ilangorg!!' value='!!langorg_code!!' />
        <input type='button' class='bouton' value='+' onClick=\"add_langorg();\"/>
    </div>
    ";
$ptab[511] = "
    <div id='el7Child_0b' class='row'>
        <input type='text' class='saisie-30emr' id='f_langorg!!ilangorg!!' name='f_langorg!!ilangorg!!' value=\"!!langorg!!\" completion=\"langue\" autfield=\"f_langorg_code!!ilangorg!!\" />
        <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_langorg!!ilangorg!!.value=''; this.form.f_langorg_code!!ilangorg!!.value=''; \" />
		<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=lang&caller=notice&p1=f_langorg_code!!ilangorg!!&p2=f_langorg!!ilangorg!!', 'select_lang', 400, 400, -2, -2, '$select2_prop')\" />
        <input type='hidden' name='f_langorg_code!!ilangorg!!' id='f_langorg_code!!ilangorg!!' value='!!langorg_code!!' />
    </div>
    ";


//	----------------------------------------------------
// 	  $ptab[6] : contenu de l'onglet 6 (Liens (ressources electroniques))
//	----------------------------------------------------
$ptab[6] = "
<!-- onglet 6 serials.tpl.php -->
<div id='el8Parent' class='parent'>
<h3>
    <img src='./images/plus.gif' class='img_plus' name='imEx' id='el8Img' onClick=\"expandBase('el8', true); return false;\" title='$msg[274]' border='0' />
    $msg[274]
</h3>
</div>

<div id='el8Child' class='child' etirable='yes' title='".htmlentities($msg[274],ENT_QUOTES, $charset)."'>

<div id='el8Child_0' title='".htmlentities($msg[275],ENT_QUOTES, $charset)."' movable='yes'>
<!--    URL associée    -->
<div id='el8Child_0a' class='row'>
    <label for='f_l' class='etiquette'>$msg[275]</label>
</div>
<div id='el8Child_0b' class='row'>
    <input name='f_lien' type='text' class='saisie-80em' id='f_lien' value=\"!!lien!!\" maxlength='255' />
    <input class='bouton' type='button' onClick=\"var l=document.getElementById('f_lien').value; eval('window.open(\''+l+'\')');\" title='$msg[CheckLink]' value='$msg[CheckButton]' />
</div>
</div>

<div id='el8Child_1' title='".htmlentities($msg[276],ENT_QUOTES, $charset)."' movable='yes'>
<!--    Format électronique de la ressource    -->
<div id='el8Child_1a' class='row'>
    <label for='f_eformat' class='etiquette'>$msg[276]</label>
</div>
<div id='el8Child_1b' class='row'>
    <input type='text' class='saisie-80em' id='f_eformat' name='f_eformat' value=\"!!eformat!!\" />
</div>
</div>
</div>
";

//	----------------------------------------------------
//	Champs personnalises
// 	  $ptab[7] : Contenu de l'onglet 7 (champs personnalises)
//	----------------------------------------------------

$ptab[7] = "
<!-- onglet 7 -->
<div id='el9Parent' class='parent'>
<h3>
    <img src='./images/plus.gif' class='img_plus' name='imEx' id='el9Img' onClick=\"expandBase('el9', true); return false;\" title='".$msg["notice_champs_perso"]."' border='0' /> ".$msg["notice_champs_perso"]."
</h3>
</div>
<div id='el9Child' class='child' etirable='yes' title='".$msg["notice_champs_perso"]."'>
!!champs_perso!!
</div>
";

//    ----------------------------------------------------
//    Champs de gestion
//       $ptab[8] : Contenu de l'onglet 8 (champs de gestion)
//    ----------------------------------------------------

$ptab[8] = "
<!-- onglet 8 -->
<div id='el10Parent' class='parent'>
<h3>
    <img src='./images/plus.gif' class='img_plus' name='imEx' id='el10Img' onClick=\"expandBase('el10', true); return false;\" title='".$msg["notice_champs_gestion"]."' border='0' /> ".$msg["notice_champs_gestion"]."
</h3>
</div>

<div id='el10Child' class='child' etirable='yes' title='".htmlentities($msg[notice_champs_gestion],ENT_QUOTES, $charset)."'>
	<div id='el10Child_0' title='".htmlentities($msg[notice_statut_gestion],ENT_QUOTES, $charset)."' movable='yes'>
		<div id='el10Child_0a' class='row'>
		    <label for='f_notice_statut' class='etiquette'>$msg[notice_statut_gestion]</label>
		</div>
		<div id='el10Child_0b' class='row'>
			!!notice_statut!!
		</div>
	</div>
	<div id='el10Child_1' title='".htmlentities($msg[notice_commentaire_gestion],ENT_QUOTES, $charset)."' movable='yes'>
		<!--    commentaire de gestion    -->
		<div id='el10Child_1a' class='row'>
		    <label for='f_commentaire_gestion' class='etiquette'>$msg[notice_commentaire_gestion]</label>
		</div>
		<div id='el10Child_1b' class='row'>
		    <textarea class='saisie-80em' id='f_commentaire_gestion' name='f_commentaire_gestion' rows='1' wrap='virtual'>!!commentaire_gestion!!</textarea>
		</div>
	</div>
	<div id='el10Child_2' title='".htmlentities($msg[notice_thumbnail_url],ENT_QUOTES, $charset)."' movable='yes'>
		<!--    URL vignette speciale    -->
		<div id='el10Child_2a' class='row'>
		    <label for='f_thumbnail_url' class='etiquette'>$msg[notice_thumbnail_url]</label>
		</div>
		<div id='el10Child_2b' class='row'>
		    <input type=text class='saisie-80em' id='f_thumbnail_url' name='f_thumbnail_url' rows='1' wrap='virtual' value=\"!!thumbnail_url!!\" />
		</div>
	</div>
	<div id='el10Child_3' title='".htmlentities($msg["opac_show_bulletinage"],ENT_QUOTES, $charset)."' movable='yes' !!display_bulletinage!!>
		<div id='el10Child_3a' class='row'>
		    <input type='checkbox' value='1' id='opac_visible_bulletinage' name='opac_visible_bulletinage'  !!opac_visible_bulletinage!! />
			<label for='opac_visible_bulletinage' class='etiquette'>".$msg["opac_show_bulletinage"]."</label>    		    
		</div>
		<div id='el10Child_3b' class='row'>			
		</div>
	</div>	
	<div id='el10Child_4' title='".htmlentities($msg['admin_menu_acces'],ENT_QUOTES, $charset)."' movable='yes'>
		<!-- Droits d'acces -->		
		<!-- rights_form -->
	</div>
	
</div>
";


//    ----------------------------------------------------
//    Collation
//       $ptab[41] : contenu de l'onglet 41 (collation)
//    ----------------------------------------------------

$ptab[41] = "
<!-- onglet 41 -->
<div id='el41Parent' class='parent'>
    <h3>
        <img src='./images/plus.gif' class='img_plus' name='imEx' id='el41Img' title='$msg[257]' border='0' onClick=\"expandBase('el41', true); return false;\" />
        $msg[258]
    </h3>
</div>

<div id='el41Child' class='child' etirable='yes' title='".htmlentities($msg[258],ENT_QUOTES, $charset)."'>

<div id='el41Child_0' title='".htmlentities($msg[259],ENT_QUOTES, $charset)."' movable='yes'>
<!--    Importance matérielle (nombre de pages, d'éléments...)    -->
<div id='el41Child_0a' class='row'>
    <label for='f_npages' class='etiquette'>$msg[259]</label>
</div>
<div id='el41Child_0b' class='row'>
    <input type='text' class='saisie-80em' id='f_npages' name='f_npages' value=\"!!npages!!\" />
</div>
</div>

<div id='el41Child_1' title='".htmlentities($msg[260],ENT_QUOTES, $charset)."' movable='yes'>
<!--    Autres caractèristiques matérielle (ill., ...)    -->
<div id='el41Child_1a' class='row'>
    <label for='f_ill' class='etiquette'>$msg[260]</label>
</div>
<div id='el41Child_1b' class='row'>
    <input type='text' class='saisie-80em' id='f_ill' name='f_ill' value=\"!!ill!!\" />
</div>
</div>

<div id='el41Child_2' title='".htmlentities($msg[261],ENT_QUOTES, $charset)."' movable='yes'>
<!--    Format    -->
<div id='el41Child_2a' class='row'>
    <label for='f_size' class='etiquette'>$msg[261]</label>
</div>
<div id='el41Child_2b' class='row'>
    <input type='text' class='saisie-80em' id='f_size' name='f_size' value=\"!!size!!\" />
</div>
</div>

<div id='el41Child_3' title='".htmlentities($msg[4050],ENT_QUOTES, $charset)."' movable='yes'>
<!--    Prix    -->
<div id='el41Child_3a' class='row'>
    <label for='f_prix' class='etiquette'>$msg[4050]</label>
</div>
<div id='el41Child_3b' class='row'>
    <input type='text' class='saisie-80em' id='f_prix' name='f_prix' value=\"!!prix!!\" />
</div>

</div>

<div id='el41Child_4' title='".htmlentities($msg[262],ENT_QUOTES, $charset)."' movable='yes'>
<!--    Matériel d'accompagnement    -->
<div id='el41Child_4a' class='row'>
    <label for='f_accomp' class='etiquette'>$msg[262]</label>
</div>
<div id='el41Child_4b' class='row'>
    <input type='text' class='saisie-80em' id='f_accomp' name='f_accomp' value=\"!!accomp!!\" />
</div>
</div>
</div>
";
        
//    ----------------------------------------------------
//    Champs de relations
//       $ptab[11] : Contenu de l'onglet 11 (champs de relations)
//    ----------------------------------------------------
$ptab[130]="
		<div class='colonne4'>
			<div id='el11Child_0a' class='row'>
	   			 <label for='f_rel_type_!!n_rel!!' class='etiquette'>$msg[notice_type_relations]</label>
			</div>
			<div id='el11Child_0b' class='row'>
				!!f_notice_type_relations!!
			</div>
		</div>
		<div class='colonne_suite'>
			<div id='el11Child_0c' class='row'>
	   			<label for='f_rel_!!n_rel!!' class='etiquette'>$msg[notice_relations]</label>
			</div>
			<div id='el11Child_0d' class='row'>
				<input type='text' class='saisie-80emr' id='f_rel_!!n_rel!!' name='f_rel_!!n_rel!!' value=\"!!notice_relations_libelle!!\" completion=\"notice\" autfield=\"f_rel_id_!!n_rel!!\" autexclude=\"!!notice_id_no_replace!!\"/>
				<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_rel_!!n_rel!!.value=''; this.form.f_rel_id_!!n_rel!!.value='0';this.form.f_rel_rank_!!n_rel!!.value='0'; \"/>
	   		    <input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=notice&caller=notice&param1=f_rel_id_!!n_rel!!&param2=f_rel_!!n_rel!!&no_display=!!notice_id_no_replace!!', 'select_notice', 700, 500, -2, -2, '$select_categ_prop')\"/>
				<input type='hidden' id='f_rel_id_!!n_rel!!' name='f_rel_id_!!n_rel!!' value='!!notice_relations_id!!'/>
				<input type='hidden' id='f_rel_rank_!!n_rel!!' name='f_rel_rank_!!n_rel!!' value='!!notice_relations_rank!!'/>
				&nbsp;<input type='button' class='bouton' value='+' onClick=\"add_rel();\"/>
			</div>
		</div>
		<div class='row'></div>";
		
$ptab[131]="
		<div class='colonne4'>
			<div id='el11Child_0b' class='row'>
				!!f_notice_type_relations!!
			</div>
		</div>
		<div class='colonne_suite'>
			<div id='el11Child_0d' class='row'>
				<input type='text' class='saisie-80emr' id='f_rel_!!n_rel!!' name='f_rel_!!n_rel!!' value=\"!!notice_relations_libelle!!\" completion=\"notice\" autfield=\"f_rel_id_!!n_rel!!\" autexclude=\"!!notice_id_no_replace!!\"/>
				<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_rel_!!n_rel!!.value=''; this.form.f_rel_id_!!n_rel!!.value='0';this.form.f_rel_rank_!!n_rel!!.value='0'; \"/>
	   		    <input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=notice&caller=notice&param1=f_rel_id_!!n_rel!!&param2=f_rel_!!n_rel!!&no_display=!!notice_id_no_replace!!', 'select_notice', 700, 500, -2, -2, '$select_categ_prop')\"/>
				<input type='hidden' id='f_rel_id_!!n_rel!!' name='f_rel_id_!!n_rel!!' value='!!notice_relations_id!!'/>
				<input type='hidden' id='f_rel_rank_!!n_rel!!' name='f_rel_rank_!!n_rel!!' value='!!notice_relations_rank!!'/>
			</div>
		</div>
		<div class='row'></div>";

$ptab[13] = "
<script>
	sel='';
	function fonction_selecteur_rel() {
        name=this.getAttribute('id').substring(4);
        name_id = name.substr(0,6)+'id_'+name.substr(6);
        openPopUp('./select.php?what=notice&caller=notice&param1='+name_id+'&param2='+name+'&no_display=!!notice_id_no_replace!!', 'select_author2', 400, 400, -2, -2,'$select1_prop');
        name_rank = name.substr(0,6)+'_rank_'+name.substr(6);
        document.getElementById(name_rank).value=0;
    }

	function fonction_raz_rel() {
        name=this.getAttribute('id').substring(4);
        name_id = name.substr(0,6)+'_id_'+name.substr(6);
        document.getElementById(name_id).value=0;
        document.getElementById(name).value='';
    }

	function add_rel() {
		rel=document.getElementById('el11Child_0');
        reldiv=document.createElement('div');
        reldiv.className='row';
		
		suffixe = document.notice.max_rel.value;
		
		//Création de la relation supplémentaire
		colonne_sel=document.createElement('div');
        colonne_sel.className='colonne4';
        row_sel=document.createElement('div');
        row_sel.className='row';
        nom_sel_id = 'f_rel_type_'+suffixe;
        
		if (!sel) {
			sel=document.notice.f_rel_type_0;
		}
		
		f_rel_type0=sel.cloneNode(true);
		f_rel_type0.setAttribute('name',nom_sel_id);	
		row_sel.appendChild(f_rel_type0);
		
		colonne=document.createElement('div');
        colonne.className='colonne_suite';
        row=document.createElement('div');
        row.className='row';
        
        nom_id = 'f_rel_'+suffixe;
        f_rel0 = document.createElement('input');
        f_rel0.setAttribute('name',nom_id);
        f_rel0.setAttribute('id',nom_id);
        f_rel0.setAttribute('type','text');
        f_rel0.className='saisie-80emr';
        f_rel0.setAttribute('value','');
        f_rel0.setAttribute('completion','notice');
        f_rel0.setAttribute('autfield','f_rel_id_'+suffixe);

        sel_f_rel0 = document.createElement('input');
        sel_f_rel0.setAttribute('id','sel_f_rel_'+suffixe);
        sel_f_rel0.setAttribute('type','button');
        sel_f_rel0.className='bouton';
        sel_f_rel0.setAttribute('readonly','');
        sel_f_rel0.setAttribute('value','$msg[parcourir]');
        sel_f_rel0.onclick=fonction_selecteur_rel;

        del_f_rel0 = document.createElement('input');
        del_f_rel0.setAttribute('id','del_f_rel_'+suffixe);
        del_f_rel0.onclick=fonction_raz_rel;
        del_f_rel0.setAttribute('type','button');
        del_f_rel0.className='bouton';
        del_f_rel0.setAttribute('readonly','');
        del_f_rel0.setAttribute('value','$msg[raz]');

        f_rel0_id = document.createElement('input');
        f_rel0_id.name='f_rel_id_'+suffixe;
        f_rel0_id.setAttribute('type','hidden');
        f_rel0_id.setAttribute('id','f_rel_id_'+suffixe);
        f_rel0_id.setAttribute('value','');
        
        f_rel0_rank = document.createElement('input');
        f_rel0_rank.name='f_rel_rank_'+suffixe;
        f_rel0_rank.setAttribute('type','hidden');
        f_rel0_rank.setAttribute('id','f_rel_rank_'+suffixe);
        f_rel0_rank.setAttribute('value','0');
        
        row.appendChild(f_rel0);
        space=document.createTextNode(' ');
        row.appendChild(space);
        row.appendChild(del_f_rel0);
        space=document.createTextNode(' ');
        row.appendChild(space);
        row.appendChild(sel_f_rel0);
        row.appendChild(f_rel0_id);
		row.appendChild(f_rel0_rank);

		colonne.appendChild(row);
		colonne_sel.appendChild(row_sel);        

        reldiv.appendChild(colonne_sel);
        reldiv.appendChild(colonne);
        
        rel.appendChild(reldiv);
        
        document.notice.max_rel.value=(suffixe*1)+(1*1);
        ajax_pack_element(f_rel0);
	}
</script>
<!-- onglet 13 -->
<div id='el11Parent' class='parent'>
<h3>
    <img src='./images/plus.gif' class='img_plus' name='imEx' id='el11Img' onClick=\"expandBase('el11', true); return false;\" title='".$msg["notice_relations"]."' border='0' /> ".$msg["notice_relations"]."
</h3>
</div>

<div id='el11Child' class='child' etirable='yes' title='".htmlentities($msg["notice_relations"],ENT_QUOTES, $charset)."'>
	<input type='hidden' name='max_rel' value=\"!!max_rel!!\" />
	<div id='el11Child_0' title='".htmlentities($msg[notice_relations],ENT_QUOTES, $charset)."' movable='yes'>
		!!notice_relations!!
	</div>
</div>
";


//	----------------------------------------------------
// 	  $form_notice : Nouveau périodique
//	----------------------------------------------------
$serial_top_form = jscript_unload_question();
$serial_top_form.= "
<!-- script de gestion des onglets -->
<script type='text/javascript' src='./javascript/tabform.js'>
</script>
<script type='text/javascript'>
<!--
	function test_notice(form)
	{
		titre1 = form.f_tit1.value; 
		titre1 = titre1.replace(/^\s+|\s+$/g, ''); //trim la valeur
		if(titre1.length == 0)
			{
				alert(\"$msg[277]\");
				return false;
			}
		return check_form();
	}
-->
</script>
<script src='javascript/ajax.js'></script>
<script src='javascript/move.js'></script>
<form class='form-$current_module' id='notice' name='notice' method='post' action='./catalog.php?categ=serials&sub=update'>
<h3><div class='left'>!!form_title!!</div><div class='right'>";
if ($PMBuserid==1 && $pmb_form_editables==1) $serial_top_form.="<input type='button' class='bouton_small' value='".$msg["catal_edit_format"]."' onClick=\"expandAll(); move_parse_dom(relative)\" id=\"bt_inedit\"/><input type='button' class='bouton_small' value='Relatif' onClick=\"expandAll(); move_parse_dom((!relative))\" style=\"display:none\" id=\"bt_swap_relative\"/>";
if ($pmb_form_editables==1) $serial_top_form.="<input type='button' class='bouton_small' value=\"".$msg["catal_origin_format"]."\" onClick=\"get_default_pos(); expandAll();  ajax_parse_dom(); if (inedit) move_parse_dom(relative); else initIt();\"/>";
$serial_top_form.="</div></h3>&nbsp; 
<div class='form-contenu'>
<div class='row'>
   	!!doc_type!! !!location!!
	</div>
<div class='row'>
	<a href=\"javascript:expandAll()\"><img src='./images/expand_all.gif' border='0' id=\"expandall\"></a>
	<a href=\"javascript:collapseAll()\"><img src='./images/collapse_all.gif' border='0' id=\"collapseall\"></a>";
$serial_top_form .= "
	<input type='hidden' name='b_level' value='!!b_level!!' />
	<input type='hidden' name='h_level' value='!!h_level!!' />
	<input type='hidden' name='serial_id' value='!!id!!' />
	<input type='hidden' name='id_form' value='!!id_form!!' />
	</div>
	!!tab0!!
	<hr class='spacer' />
	!!tab1!!
	<hr class='spacer' />
	!!tab2!!
	<hr class='spacer' />
	!!tab30!!
	<hr class='spacer' />
	!!tab3!!
	<hr class='spacer' />
	!!tab4!!
	<hr class='spacer' />
	!!tab5!!
	<hr class='spacer' />
	!!tab6!!
	<hr class='spacer' />
	!!tab7!!
	<hr class='spacer' />
	!!tab11!!
	<hr class='spacer' />
	!!tab8!!
	<hr class='spacer' />
</div>
<div class='row'>
	<input type='button' class='bouton' value='$msg[76]' !!annul!!>&nbsp;
	<input type='button' class='bouton' value='$msg[77]' id='btsubmit' onClick=\"if (test_notice(this.form)) {unload_off();this.form.submit();}\" />
	!!link_audit!!
	</div>
</form>
<script type='text/javascript'>
	get_pos();
	ajax_parse_dom();
	document.forms['notice'].elements['f_tit1'].focus();
</script>
";

$message_search = "
<div class='row'>
	<h2>".$msg[401]."</h2>
</div>
";

$serial_action_bar = "
<script type=\"text/javascript\">
	<!--

	var has_bulletin = !!nb_bulletins!!;
	var has_expl = !!nb_expl!!;
	
	function confirm_serial_delete()
	{
	phrase1 = \"$msg[serial_SupConfirm]\";
	phrase2 = \"$msg[serial_SupNbBulletin] \";
	phrase3 = \"$msg[serial_SupExemplaire]\";
	if(!has_bulletin && !has_expl) {
		result = confirm(phrase1);
	} else if (has_bulletin){
		result = confirm(phrase2 + has_bulletin + \"\\n\" + phrase1);  
		if(result && has_expl)
		  result = confirm(phrase3 + has_expl + \"\\n\" + phrase1);
		if(result)
			result = confirm(phrase1);  
	}
	if(result)
		document.location = './catalog.php?categ=serials&sub=delete&serial_id=!!serial_id!!';
	}
	-->	
</script>
<div class='left'>
	<input type='button' class='bouton' onclick=\"document.location='./catalog.php?categ=serials&sub=serial_form&id=!!serial_id!!'\" value='$msg[62]' />&nbsp;
	<input type='button' class='bouton' value='$msg[4002]' onClick=\"document.location='./catalog.php?categ=serials&sub=bulletinage&action=bul_form&serial_id=!!serial_id!!&bul_id=0'\" />&nbsp;
	<input type='button' class='bouton' value=' $msg[explnum_ajouter_doc] ' onClick=\"document.location='./catalog.php?categ=serials&sub=explnum_form&serial_id=!!serial_id!!&explnum_id=0'\">
	<input type='button' class='bouton' value='$msg[158]'  onClick=\"document.location='./catalog.php?categ=serials&sub=serial_replace&serial_id=!!serial_id!!'\" />&nbsp;";
if ($acquisition_active) {
	$serial_action_bar.="<input type='button' class='bouton' value='".$msg["acquisition_sug_do"]."' onclick=\"document.location='./catalog.php?categ=sug&action=modif&id_bibli=0&id_notice=!!serial_id!!'\" />&nbsp;";
}
$serial_action_bar.="</div>
<div class='right'>
	!!delete_serial_button!!
	</div>
<div class='row'></div>
";
//<input type='button' class='bouton' onclick=\"confirm_serial_delete();\" value='$msg[63]' />
$bul_action_bar = "
<script type=\"text/javascript\">
	<!--
	
	var has_expl = !!nb_expl!!;
	
	function confirm_bul_delete()
	{
		phrase1 = \"$msg[serial_SupBulletin]\";
		phrase2 = \"$msg[serial_SupExemplaire]\";
		
		if(!has_expl) {
			result = confirm(phrase1);
		} else {
			result = confirm(phrase2 + has_expl + \"\\n\" + phrase1);
			if(result)
				result = confirm(phrase1);
		}	
		
		if(result)
			document.location = './catalog.php?categ=serials&sub=bulletinage&action=delete&bul_id=!!bul_id!!';
		else
			document.forms['addex'].elements['noex'].focus();
	}
	-->	
</script>
<div class='left'>
	<input type='button' class='bouton' onclick=\"document.location='./catalog.php?categ=serials&sub=bulletinage&action=bul_form&bul_id=!!bul_id!!'\" value='$msg[62]' />&nbsp;
	<input type='button' class='bouton' value='$msg[158]'  onClick=\"document.location='./catalog.php?categ=serials&sub=bulletin_replace&serial_id=!!serial_id!!&bul_id=!!bul_id!!'\" />&nbsp;
</div>
<div class='right'>
	!!bulletin_delete_button!!
</div>
<div class='row'></div>
";

$serial_bul_form = jscript_unload_question();
$serial_bul_form.= "
<script type='text/javascript'>
<!--
	function test_form(form)
	{
		test1 = form.bul_no.value+form.bul_date.value+form.bul_titre.value;// concaténation des valeurs à tester
		test = test1.replace(/^\s+|\s+$/g, ''); //trim de la valeur
		if(test.length == 0)
			{
				alert(\"$msg[serial_BulletinDate]\");
				form.bul_no.focus();
				return false;
			}
		return true;
	}
-->
</script>
<script type='text/javascript' src='javascript/tabform.js'></script>
<script type='text/javascript' src='javascript/ajax.js'></script>
";
if ($pmb_form_editables) $serial_bul_form.="<script type='text/javascript' src='javascript/move.js'></script>";
$serial_bul_form .= "
<!-- serial_bul_form -->
<form class='form-$current_module' name='notice' method='post' action='./catalog.php?categ=serials&sub=bulletinage&action=update' onSubmit='return false;'>
<h3><div class='left'>!!form_title!!</div><div class='right'>";
if ($PMBuserid==1 && $pmb_form_editables==1) $serial_bul_form.="<input type='button' class='bouton_small' value='Editer format' onClick=\"expandAll(); move_parse_dom(relative)\" id=\"bt_inedit\"/><input type='button' class='bouton_small' value='Relatif' onClick=\"expandAll(); move_parse_dom((!relative))\" style=\"display:none\" id=\"bt_swap_relative\"/>";
if ($pmb_form_editables==1) $serial_bul_form.="<input type='button' class='bouton_small' value=\"Format d'origine\" onClick=\"get_default_pos(); expandAll();  ajax_parse_dom(); if (inedit) move_parse_dom(relative); else initIt();\"/>";
$serial_bul_form.="</div></h3>
<div class='row'></div>
<div class='form-contenu'>
<div class='row'>
   	!!doc_type!! !!location!!
	</div>
<div class='row'>
		<a href=\"javascript:expandAll()\"><img src='./images/expand_all.gif' border='0' id=\"expandall\"></a>
		<a href=\"javascript:collapseAll()\"><img src='./images/collapse_all.gif' border='0' id=\"collapseall\"></a>
		<input type=\"hidden\" name=\"b_level\" value=\"!!b_level!!\">
		<input type=\"hidden\" name=\"h_level\" value=\"!!h_level!!\">
</div>

<!-- onglet bul -->
<div id='elbulParent' class='parent'>
	<div class='row'>
		<h3>
			<img src='./images/minus.gif' class='img_plus' align='top' name='imEx' id='elbulImg' title=\"".$msg[perio_bull_form_info_bulletin]."\" border='0' onClick=\"expandBase('elbul', true); return false;\"/>
			".$msg[perio_bull_form_info_bulletin]."
		</h3>
	</div>
</div>
<div id='elbulChild' class='child' title='".htmlentities($msg[perio_bull_form_info_bulletin],ENT_QUOTES, $charset)."' >
<div class='colonne2'>
	<div class='row'>
		<label class='etiquette' for='bul_no'>$msg[4025]</label>
	</div>
	<div class='row'>
		<input type='text' id='bul_no' name='bul_no' value='!!bul_no!!' class='saisie-20em' />
		<input type='hidden' name='bul_id' value='!!bul_id!!' />
		<input type='hidden' name='serial_id' value='!!serial_id!!' />
	</div>
</div>
<div class='colonne_suite'>
	<div class='row'>
		<label class='etiquette' for='bul_cb'>$msg[bulletin_code_barre]</label>
		</div>
	<div class='row'>
		<input class='saisie-20emr' id='bul_cb' name='bul_cb' readonly value=\"!!bul_cb!!\" />
		<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./catalog/setcb.php?formulaire_appelant=notice&objet_appelant=bul_cb&bulletin=1&notice_id=!!bul_id!!', 'getcb', 220, 100, -2, -2, 'toolbar=no, resizable=yes')\" />
		<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.bul_cb.value=''; \" />
		</div>
	</div>
<div class='colonne3'>
	<div class='row'>
		<label class='etiquette' >$msg[4026]</label>
	</div>
	<div class='row'>
		!!date_date!!
	</div>
</div>
<div class='colonne_suite'>
	<div class='row'>
		<label class='etiquette' >$msg[bulletin_mention_periode]</label>
	</div>
	<div class='row'>
		<input type='text' id='bul_date' name='bul_date' value='!!bul_date!!' class='saisie-50em' />
	</div>
</div>
<div class='row'>
	<div class='row'>
		<label class='etiquette' >$msg[bulletin_mention_titre]</label>
	</div>
	<div class='row'>
		<input type='text' id='bul_titre' name='bul_titre' value='!!bul_titre!!' class='saisie-50em' />
	</div>
</div>
</div>
<!-- Formulaire de notice de bulletin -->
!!tab0!!
<hr class='spacer' />
!!tab1!!
<!--<hr class='spacer' />
!!tab2!!-->
<hr class='spacer' />
!!tab3!!
<hr class='spacer' />
!!tab4!!
<hr class='spacer' />
!!tab41!!
<hr class='spacer' />
!!tab5!!
<hr class='spacer' />
!!tab6!!
<hr class='spacer' />
!!tab7!!
<hr class='spacer' />
!!tab8!!
<hr class='spacer' />
</div>
<div class='row'>
	<input type=\"button\" class=\"bouton\" value=\"$msg[76]\" onClick=\"unload_off();history.go(-1);\" />&nbsp;<input type=\"button\" class=\"bouton\" value=\"$msg[77]\" onClick=\"if (test_form(this.form)) {unload_off();this.form.submit();}\" />
	!!link_audit!!
	</div>
</form>
<script type='text/javascript'>".($pmb_form_editables?"get_pos(); ":"")."
	ajax_parse_dom();
	if (document.forms['notice']) {
		if (document.forms['notice'].elements['f_tit1']) document.forms['notice'].elements['f_tit1'].focus();
			else document.forms['notice'].elements['bul_no'].focus();
	} else document.forms['serial_bul_form'].elements['bul_no'].focus();

</script>

";

/* à partir d'ici, template du forme de catalogage de dépouillement */
//	----------------------------------------------------
// 	  $pdeptab[0] : contenu de l'onglet 0 (zone de titre)

$pdeptab[0] = "
<!-- onglet 0 -->
<div id='el0Parent' class='parent'>
	<div class='row'>
		<h3>
			<img src='./images/minus.gif' class='img_plus' align='top' name='imEx' id='el0Img' title='$msg[236]' border='0' onClick=\"expandBase('el0', true); return false;\" />
			$msg[712]
		</h3>
	</div>
</div>

<div id='el0Child' class='child' etirable='yes' title='".htmlentities($msg[236],ENT_QUOTES, $charset)."' >
    <div id='el0Child_0' title='".htmlentities($msg[237],ENT_QUOTES, $charset)."' movable='yes'>
	<div class='row'>
		<label class='etiquette' for='f_tit1'>$msg[237]</label>
	</div>
	<div class='row'>
		<input id='f_tit1' type='text' class='saisie-80em' name='f_tit1' value=\"!!tit1!!\" />
	</div>
	</div>

    <div id='el0Child_1' title='".htmlentities($msg[239],ENT_QUOTES, $charset)."' movable='yes'>
	<div class='row'>
		<label class='etiquette' for='f_tit3'>$msg[239]</label>
	</div>
	<div class='row'>
		<input class='saisie-80em' type='text' id='f_tit3' name='f_tit3' value=\"!!tit3!!\" />
	</div>
	</div>

    <div id='el0Child_2' title='".htmlentities($msg[240],ENT_QUOTES, $charset)."' movable='yes'>
	<div class='row'>
		<label class='etiquette' for='f_tit4'>$msg[240]</label>
	</div>
	<div class='row'>
		<input class='saisie-80em' id='f_tit4' type='text' name='f_tit4' value=\"!!tit4!!\"  />
	</div>
	</div>
</div>
";

//	----------------------------------------------------
// 	  $pdeptab[1] : contenu de l'onglet 1 (mention de responsabilité)
//	----------------------------------------------------
$aut_fonctions= new marc_list('function');

$pdeptab[1] = "
<script>
    function fonction_selecteur_auteur() {
        name=this.getAttribute('id').substring(4);
        name_id = name.substr(0,6)+'_id'+name.substr(6);
        openPopUp('./select.php?what=auteur&caller=notice&param1='+name_id+'&param2='+name+'&dyn=1&deb_rech='+".pmb_escape()."(document.getElementById(name).value), 'select_author2', 400, 400, -2, -2, '$select1_prop');
    }
    function fonction_selecteur_auteur_change(field) {
    	// id champ text = 'f_aut'+n+suffixe
    	// id champ hidden = 'f_aut'+n+'_id'+suffixe; 
    	// select.php?what=auteur&caller=notice&param1=f_aut0_id&param2=f_aut0&deb_rech='+t
        name=field.getAttribute('id');
        name_id = name.substr(0,6)+'_id'+name.substr(6);
        openPopUp('./select.php?what=auteur&caller=notice&param1='+name_id+'&param2='+name+'&dyn=1&deb_rech='+".pmb_escape()."(document.getElementById(name).value), 'select_author2', 400, 400, -2, -2, '$select1_prop');
    }
    function fonction_raz_auteur() {
        name=this.getAttribute('id').substring(4);
        name_id = name.substr(0,6)+'_id'+name.substr(6);
        document.getElementById(name_id).value=0;
        document.getElementById(name).value='';
    }
    function fonction_selecteur_fonction() {
        name=this.getAttribute('id').substring(4);
        name_code = name.substr(0,4)+'_code'+name.substr(4);
        openPopUp('./select.php?what=function&caller=notice&param1='+name_code+'&param2='+name+'&dyn=1', 'select_fonction2', 400, 400, -2, -2, '$select1_prop');
    }
    function fonction_raz_fonction() {
        name=this.getAttribute('id').substring(4);
        name_code = name.substr(0,4)+'_code'+name.substr(4);
        document.getElementById(name_code).value=0;
        document.getElementById(name).value='';
    }
    function add_aut(n) {
        template = document.getElementById('addaut'+n);
        aut=document.createElement('div');
        aut.className='row';

        // auteur
        colonne=document.createElement('div');
        colonne.className='colonne2';
        row=document.createElement('div');
        row.className='row';
        suffixe = eval('document.notice.max_aut'+n+'.value')
        nom_id = 'f_aut'+n+suffixe
        f_aut0 = document.createElement('input');
        f_aut0.setAttribute('name',nom_id);
        f_aut0.setAttribute('id',nom_id);
        f_aut0.setAttribute('type','text');
        f_aut0.className='saisie-30emr';
        f_aut0.setAttribute('value','');
		f_aut0.setAttribute('completion','authors');
        f_aut0.setAttribute('autfield','f_aut'+n+'_id'+suffixe);

        sel_f_aut0 = document.createElement('input');
        sel_f_aut0.setAttribute('id','sel_f_aut'+n+suffixe);
        sel_f_aut0.setAttribute('type','button');
        sel_f_aut0.className='bouton';
        sel_f_aut0.setAttribute('readonly','');
        sel_f_aut0.setAttribute('value','$msg[parcourir]');
        sel_f_aut0.onclick=fonction_selecteur_auteur;

        del_f_aut0 = document.createElement('input');
        del_f_aut0.setAttribute('id','del_f_aut'+n+suffixe);
        del_f_aut0.onclick=fonction_raz_auteur;
        del_f_aut0.setAttribute('type','button');
        del_f_aut0.className='bouton';
        del_f_aut0.setAttribute('readonly','');
        del_f_aut0.setAttribute('value','$msg[raz]');

        f_aut0_id = document.createElement('input');
        f_aut0_id.name='f_aut'+n+'_id'+suffixe;
        f_aut0_id.setAttribute('type','hidden');
        f_aut0_id.setAttribute('id','f_aut'+n+'_id'+suffixe);
        f_aut0_id.setAttribute('value','');

        //f_aut0_content.appendChild(f_aut0);
		row.appendChild(f_aut0);
        space=document.createTextNode(' ');
        row.appendChild(space);
        row.appendChild(sel_f_aut0);
        space=document.createTextNode(' ');
        row.appendChild(space);
        row.appendChild(del_f_aut0);
        row.appendChild(f_aut0_id);
        colonne.appendChild(row);
        aut.appendChild(colonne);
		
        // fonction
        colonne=document.createElement('div');
        colonne.className='colonne_suite';
        row=document.createElement('div');
        row.className='row';
        suffixe = eval('document.notice.max_aut'+n+'.value');
        nom_id = 'f_f'+n+suffixe;
        f_f0 = document.createElement('input');
        f_f0.setAttribute('name',nom_id);
        f_f0.setAttribute('id',nom_id);
        f_f0.setAttribute('type','text');
        f_f0.className='saisie-15emr';
        f_f0.setAttribute('value','".$aut_fonctions->table[$value_deflt_fonction]."');
		f_f0.setAttribute('completion','fonction');
        f_f0.setAttribute('autfield','f_f'+n+'_code'+suffixe);

        sel_f_f0 = document.createElement('input');
        sel_f_f0.setAttribute('id','sel_f_f'+n+suffixe);
        sel_f_f0.setAttribute('type','button');
        sel_f_f0.className='bouton';
        sel_f_f0.setAttribute('readonly','');
        sel_f_f0.setAttribute('value','$msg[parcourir]');
        sel_f_f0.onclick=fonction_selecteur_fonction;

        del_f_f0 = document.createElement('input');
        del_f_f0.setAttribute('id','del_f_f'+n+suffixe);
        del_f_f0.onclick=fonction_raz_fonction;
        del_f_f0.setAttribute('type','button');
        del_f_f0.className='bouton';
        del_f_f0.setAttribute('readonly','readonly');
        del_f_f0.setAttribute('value','$msg[raz]');
		
        f_f0_code = document.createElement('input');
        f_f0_code.name='f_f'+n+'_code'+suffixe;
        f_f0_code.setAttribute('type','hidden');
        f_f0_code.setAttribute('id','f_f'+n+'_code'+suffixe);
        f_f0_code.setAttribute('value','$value_deflt_fonction');

        row.appendChild(f_f0);
        space=document.createTextNode(' ');
        row.appendChild(space);
        row.appendChild(sel_f_f0);
        space=document.createTextNode(' ');
        row.appendChild(space);
        row.appendChild(del_f_f0);
        row.appendChild(f_f0_code);
        colonne.appendChild(row);

        aut.appendChild(colonne);
        template.appendChild(aut);
        eval('document.notice.max_aut'+n+'.value=suffixe*1+1*1');
        ajax_pack_element(f_aut0);
		ajax_pack_element(f_f0);
    }

    function fonction_selecteur_categ() {
        name=this.getAttribute('id').substring(4);
        name_id = name.substr(0,7)+'_id'+name.substr(7);
        openPopUp('./select.php?what=categorie&caller=notice&p1='+name_id+'&p2='+name+'&dyn=1', 'select_categ', 700, 500, -2, -2, '$select_categ_prop');
    }
    function fonction_raz_categ() {
        name=this.getAttribute('id').substring(4);
        name_id = name.substr(0,7)+'_id'+name.substr(7);
        document.getElementById(name_id).value=0;
        document.getElementById(name).value='';
    }
    function add_categ() {
        template = document.getElementById('addcateg');
        categ=document.createElement('div');
        categ.className='row';

        suffixe = eval('document.notice.max_categ.value')
        nom_id = 'f_categ'+suffixe
        f_categ = document.createElement('input');
        f_categ.setAttribute('name',nom_id);
        f_categ.setAttribute('id',nom_id);
        f_categ.setAttribute('type','text');
        f_categ.className='saisie-80emr';
        f_categ.setAttribute('value','');
		f_categ.setAttribute('completion','categories_mul');
        f_categ.setAttribute('autfield','f_categ_id'+suffixe);
 
        del_f_categ = document.createElement('input');
        del_f_categ.setAttribute('id','del_f_categ'+suffixe);
        del_f_categ.onclick=fonction_raz_categ;
        del_f_categ.setAttribute('type','button');
        del_f_categ.className='bouton';
        del_f_categ.setAttribute('readonly','');
        del_f_categ.setAttribute('value','$msg[raz]');

        f_categ_id = document.createElement('input');
        f_categ_id.name='f_categ_id'+suffixe;
        f_categ_id.setAttribute('type','hidden');
        f_categ_id.setAttribute('id','f_categ_id'+suffixe);
        f_categ_id.setAttribute('value','');

        categ.appendChild(f_categ);
        space=document.createTextNode(' ');
        categ.appendChild(space);
        categ.appendChild(del_f_categ);
        categ.appendChild(f_categ_id);

        template.appendChild(categ);

        document.notice.max_categ.value=suffixe*1+1*1 ;
        ajax_pack_element(f_categ);
    }
    function fonction_selecteur_lang() {
        name=this.getAttribute('id').substring(4);
        name_id = name.substr(0,6)+'_code'+name.substr(6);
        openPopUp('./select.php?what=lang&caller=notice&p1='+name_id+'&p2='+name, 'select_lang', 400, 400, -2, -2, '$select2_prop');
    }
    function fonction_raz_lang() {
        name=this.getAttribute('id').substring(4);
        name_id = name.substr(0,6)+'_code'+name.substr(6);
        document.getElementById(name_id).value=0;
        document.getElementById(name).value='';
    }
    function add_lang() {
        template = document.getElementById('addlang');
        lang=document.createElement('div');
        lang.className='row';

        suffixe = eval('document.notice.max_lang.value')
        nom_id = 'f_lang'+suffixe
        f_lang = document.createElement('input');
        f_lang.setAttribute('name',nom_id);
        f_lang.setAttribute('id',nom_id);
        f_lang.setAttribute('type','text');
        f_lang.className='saisie-30emr';
        f_lang.setAttribute('value','');
		f_lang.setAttribute('completion','langue');
        f_lang.setAttribute('autfield','f_lang_code'+suffixe);
 
        del_f_lang = document.createElement('input');
        del_f_lang.setAttribute('id','del_f_lang'+suffixe);
        del_f_lang.onclick=fonction_raz_lang;
        del_f_lang.setAttribute('type','button');
        del_f_lang.className='bouton';
        del_f_lang.setAttribute('readonly','');
        del_f_lang.setAttribute('value','$msg[raz]');

        sel_f_lang = document.createElement('input');
        sel_f_lang.setAttribute('id','sel_f_lang'+suffixe);
        sel_f_lang.setAttribute('type','button');
        sel_f_lang.className='bouton';
        sel_f_lang.setAttribute('readonly','');
        sel_f_lang.setAttribute('value','$msg[parcourir]');
        sel_f_lang.onclick=fonction_selecteur_lang;

        f_lang_code = document.createElement('input');
        f_lang_code.name='f_lang_code'+suffixe;
        f_lang_code.setAttribute('type','hidden');
        f_lang_code.setAttribute('id','f_lang_code'+suffixe);
        f_lang_code.setAttribute('value','');

        lang.appendChild(f_lang);
        space=document.createTextNode(' ');
        lang.appendChild(space);
        lang.appendChild(del_f_lang);
        lang.appendChild(space.cloneNode(false));
        lang.appendChild(sel_f_lang);
        lang.appendChild(f_lang_code);

        template.appendChild(lang);

        document.notice.max_lang.value=suffixe*1+1*1 ;
        ajax_pack_element(f_lang);
    }

    function fonction_selecteur_langorg() {
        name=this.getAttribute('id').substring(4);
        name_id = name.substr(0,9)+'_code'+name.substr(9);
        openPopUp('./select.php?what=lang&caller=notice&p1='+name_id+'&p2='+name, 'select_lang', 400, 400, -2, -2, '$select2_prop');
    }
    function fonction_raz_langorg() {
        name=this.getAttribute('id').substring(4);
        name_id = name.substr(0,9)+'_code'+name.substr(9);
        document.getElementById(name_id).value=0;
        document.getElementById(name).value='';
    }
    function add_langorg() {
        template = document.getElementById('addlangorg');
        langorg=document.createElement('div');
        langorg.className='row';

        suffixe = eval('document.notice.max_langorg.value')
        nom_id = 'f_langorg'+suffixe
        f_langorg = document.createElement('input');
        f_langorg.setAttribute('name',nom_id);
        f_langorg.setAttribute('id',nom_id);
        f_langorg.setAttribute('type','text');
        f_langorg.className='saisie-30emr';
        f_langorg.setAttribute('value','');
		f_langorg.setAttribute('completion','langue');
        f_langorg.setAttribute('autfield','f_langorg_code'+suffixe);
 
        del_f_langorg = document.createElement('input');
        del_f_langorg.setAttribute('id','del_f_langorg'+suffixe);
        del_f_langorg.onclick=fonction_raz_langorg;
        del_f_langorg.setAttribute('type','button');
        del_f_langorg.className='bouton';
        del_f_langorg.setAttribute('readonly','');
        del_f_langorg.setAttribute('value','$msg[raz]');

        sel_f_langorg = document.createElement('input');
        sel_f_langorg.setAttribute('id','sel_f_langorg'+suffixe);
        sel_f_langorg.setAttribute('type','button');
        sel_f_langorg.className='bouton';
        sel_f_langorg.setAttribute('readonly','');
        sel_f_langorg.setAttribute('value','$msg[parcourir]');
        sel_f_langorg.onclick=fonction_selecteur_langorg;

        f_lang_codeorg = document.createElement('input');
        f_lang_codeorg.name='f_langorg_code'+suffixe;
        f_lang_codeorg.setAttribute('type','hidden');
        f_lang_codeorg.setAttribute('id','f_langorg_code'+suffixe);
        f_lang_codeorg.setAttribute('value','');

        langorg.appendChild(f_langorg);
        space=document.createTextNode(' ');
        langorg.appendChild(space);
        langorg.appendChild(del_f_langorg);
        langorg.appendChild(space.cloneNode(false));
        langorg.appendChild(sel_f_langorg);
        langorg.appendChild(f_lang_codeorg);

        template.appendChild(langorg);

        document.notice.max_langorg.value=suffixe*1+1*1 ;
        ajax_pack_element(f_langorg);
    }


</script>
<div id='el1Parent' class='parent'>
	<div class='row'>
	<h3>
		<img src='./images/plus.gif' class='img_plus' name='imEx' id='el1Img' onClick=\"expandBase('el1', true); return false;\" title='$msg[243]' border='0' />
		$msg[243]
	</h3>
	</div>
</div>
<div id='el1Child' class='child' etirable='yes' title='".htmlentities($msg[243],ENT_QUOTES, $charset)."'>
    <div id='el1Child_0' title='".htmlentities($msg[244],ENT_QUOTES, $charset)."' movable='yes'>
	<!--	Auteur principal	-->
	<div class='row'>
		<div class='colonne2'>
			<label for='f_aut0' class='etiquette'>$msg[244]</label>
			<div class='row'>
				<input type='text' completion='authors' autfield='f_aut0_id' id='auteur0' class='saisie-30emr' name='f_aut0' value=\"!!aut0!!\" />

				<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=auteur&caller=notice&param1=f_aut0_id&param2=f_aut0&deb_rech='+".pmb_escape()."(this.form.f_aut0.value), 'select_author0', 400, 400, -2, -2, '$select1_prop')\" />
				<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_aut0.value=''; this.form.f_aut0_id.value='0'; \" />
				<input type='hidden' name='f_aut0_id' id='f_aut0_id' value=\"!!aut0_id!!\" />
				</div>
			</div>
		<!--	Fonction	-->
		<div class='colonne_suite'>
			<label for='f_f0' class='etiquette'>$msg[245]</label>
			<div class='row'>
		        <input type='text' class='saisie-15emr' id='f_f0' name='f_f0' value=\"!!f0!!\" completion=\"fonction\" autfield=\"f_f0_code\" />
				<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=function&caller=notice&p1=f_f0_code&p2=f_f0', 'select_func0', 400, 400, -2, -2, '$select2_prop')\" />
				<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_f0.value=''; this.form.f_f0_code.value='0'; \" />
				<input type='hidden' name='f_f0_code' id='f_f0_code' value=\"!!f0_code!!\" />
				</div>
			</div>
		</div>
	</div>

    <div id='el1Child_2' title='".htmlentities($msg[246],ENT_QUOTES, $charset)."' movable='yes'>
	<!--	autres auteurs	-->
	<div class='row'>
		<div class='row'>
			<label for='f_aut1' class='etiquette'>$msg[246]</label>
			<input type='hidden' name='max_aut1' value=\"!!max_aut1!!\" />
		</div>
		!!autres_auteurs!!
		<div id='addaut1'/>
			<input type='button' class='bouton' value='+' onClick=\"add_aut(1);\"/>
			</div>
		</div>
	</div>

    <div id='el1Child_3' title='".htmlentities($msg[247],ENT_QUOTES, $charset)."' movable='yes'>
	<!--	Auteurs secondaires 	-->
	<div class='row'>
		<div class='row'>
			<label for='f_aut2' class='etiquette'>$msg[247]</label>
			<input type='hidden' name='max_aut2' value=\"!!max_aut2!!\" />
		</div>
		!!auteurs_secondaires!!
		<div id='addaut2'/>
			<input type='button' class='bouton' value='+' onClick=\"add_aut(2);\"/>
			</div>
		</div>
	</div>	
</div>
";

//	----------------------------------------------------
//	Autres auteurs
//	----------------------------------------------------
$pdeptab[11] = "
		<div id='el1Child_2b_first' class='colonne2'>
			<div class='row'>
               	<input type='text' class='saisie-30emr' completion='authors' autfield='f_aut1_id!!iaut!!' id='f_aut1!!iaut!!' name='f_aut1!!iaut!!' value=\"!!aut1!!\" />

				<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=auteur&caller=notice&param1=f_aut1_id!!iaut!!&param2=f_aut1!!iaut!!&deb_rech='+".pmb_escape()."(this.form.f_aut1!!iaut!!.value), 'select_author2', 400, 400, -2, -2, '$select1_prop')\" />
				<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_aut1!!iaut!!.value=''; this.form.f_aut1_id!!iaut!!.value='0'; \" />
				<input type='hidden' name='f_aut1_id!!iaut!!' id='f_aut1_id!!iaut!!' value=\"!!aut1_id!!\" />
				</div>
			</div>
		<!--	Fonction	-->
		<div id='el1Child_2b_others' class='colonne_suite'>
			<div class='row'>
                <input type='text' class='saisie-15emr' id='f_f1!!iaut!!' name='f_f1!!iaut!!' completion='fonction' autfield='f_f1_code!!iaut!!' value=\"!!f1!!\" />
				<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=function&caller=notice&p1=f_f1_code!!iaut!!&p2=f_f1!!iaut!!', 'select_func2', 400, 400, -2, -2, '$select2_prop')\" />
				<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_f1!!iaut!!.value=''; this.form.f_f1_code!!iaut!!.value='0'; \" />
				<input type='hidden' name='f_f1_code!!iaut!!' id='f_f1_code!!iaut!!' value=\"!!f1_code!!\" />
				</div>
			</div>
	" ;

//	----------------------------------------------------
//	Autres secondaires
//	----------------------------------------------------
$pdeptab[12] = "
		<div id='el1Child_3b_first' class='colonne2'>
			<div class='row'>
             	<input type='text' class='saisie-30emr' completion='authors' autfield='f_aut2_id!!iaut!!' id='f_aut2!!iaut!!' name='f_aut2!!iaut!!' value=\"!!aut2!!\" />

				<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=auteur&caller=notice&param1=f_aut2_id!!iaut!!&param2=f_aut2!!iaut!!&deb_rech='+".pmb_escape()."(this.form.f_aut2!!iaut!!.value), 'select_author2', 400, 400, -2, -2, '$select1_prop')\" />
				<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_aut2!!iaut!!.value=''; this.form.f_aut2_id!!iaut!!.value='0'; \" />
				<input type='hidden' name='f_aut2_id!!iaut!!' id='f_aut2_id!!iaut!!' value=\"!!aut2_id!!\" />
				</div>
			</div>
		<!--	Fonction	-->
		<div id='el1Child_3b_others' class='colonne_suite'>
			<div class='row'>
                <input type='text' class='saisie-15emr' id='f_f2!!iaut!!' name='f_f2!!iaut!!' completion='fonction' autfield='f_f2_code!!iaut!!' value=\"!!f2!!\" />
				<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=function&caller=notice&p1=f_f2_code!!iaut!!&p2=f_f2!!iaut!!', 'select_func2', 400, 400, -2, -2, '$select2_prop')\" />
				<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_f2!!iaut!!.value=''; this.form.f_f2_code!!iaut!!.value='0'; \" />
				<input type='hidden' name='f_f2_code!!iaut!!' id='f_f2_code!!iaut!!' value=\"!!f2_code!!\" />
				</div>
			</div>
	" ;

//	----------------------------------------------------
// 	  $pdeptab[2] : contenu de l'onglet 2 (pagination)

$pdeptab[2] = "
<!-- onglet 2 -->
<div id='el2Parent' class='parent'>
	<h3><img src='./images/plus.gif' class='img_plus' name='imEx' id='el2Img' title=\"pagination\" border='0' onClick=\"expandBase('el2', true); return false;\">
	$msg[serial_Pagination]
	</h3>
</div>
<div id='el2Child' class='child' etirable='yes' title='".htmlentities($msg[serial_Pagination],ENT_QUOTES, $charset)."'>

<div id='el2Child_0' title='".htmlentities($msg[serial_Pagination],ENT_QUOTES, $charset)."' movable='yes'>
	<div  id='el2Child_0a' class='row'>
		<label class='etiquette' for='pagination'>$msg[serial_Pagination]</label>
	</div>
	<div id='el2Child_0b' class='row'>
		<input type='text' class='saisie-80em' name='pages' value=\"!!pages!!\">
	</div>
</div>
</div>
";

//	----------------------------------------------------
// 	  $pdeptab[3] : contenu de l'onglet 3 (notes)

$pdeptab[3] = "
<!-- onglet 3 -->
<div id='el5Parent' class='parent'>
<h3>
    <img src='./images/plus.gif' class='img_plus' name='imEx' id='el5Img' title='$msg[263]' border='0' onClick=\"expandBase('el5', true); return false;\" />
    $msg[264]
</h3>
</div>

<div id='el5Child' class='child' etirable='yes' title='".htmlentities($msg[264],ENT_QUOTES, $charset)."'>

<div id='el5Child_0' title='".htmlentities($msg[265],ENT_QUOTES, $charset)."' movable='yes'>
<!--    Note générale    -->
<div id='el5Child_0a' class='row'>
    <label for='f_n_gen' class='etiquette'>$msg[265]</label>
</div>
<div id='el5Child_0b' class='row'>
    <textarea id='f_n_gen' class='saisie-80em' name='f_n_gen' rows='3' wrap='virtual'>!!n_gen!!</textarea>
</div>
</div>

<div id='el5Child_1' title='".htmlentities($msg[266],ENT_QUOTES, $charset)."' movable='yes'>
<!--    Note de contenu    -->
<div id='el5Child_1a' class='row'>
    <label for='f_n_contenu' class='etiquette'>$msg[266]</label>
</div>
<div id='el5Child_1b' class='row'>
    <textarea class='saisie-80em' id='f_n_contenu' name='f_n_contenu' rows='5' wrap='virtual'>!!n_contenu!!</textarea>
</div>
</div>

<div id='el5Child_2' title='".htmlentities($msg[267],ENT_QUOTES, $charset)."' movable='yes'>
<!--    Résumé/extrait    -->
<div id='el5Child_2a' class='row'>
    <label for='f_n_resume' class='etiquette'>$msg[267]</label>
</div>
<div id='el5Child_2b' class='row'>
    <textarea class='saisie-80em' id='f_n_resume' name='f_n_resume' rows='5' wrap='virtual'>!!n_resume!!</textarea>
</div>
</div>
</div>
";

//	----------------------------------------------------
// 	  $pdeptab[4] : contenu de l'onglet 4 (indexation)

$pdeptab[4] = "
<!-- onglet 4 -->
<div id='el6Parent' class='parent'>
<h3>
    <img src='./images/plus.gif' class='img_plus' name='imEx' id='el6Img' title=\"$msg[268]\" border='0' onClick=\"expandBase('el6', true); return false;\" />
    $msg[269]
</h3>
</div>

<div id='el6Child' class='child' etirable='yes' title='".htmlentities($msg[269],ENT_QUOTES, $charset)."'>

<div id='el6Child_0' title='".htmlentities($msg[134],ENT_QUOTES, $charset)."' movable='yes'>
    <!--    Catégories    -->
    <div id='el6Child_0a' class='row'>
        <label for='f_categ' class='etiquette'>$msg[134]</label>
    </div>
    <input type='hidden' name='max_categ' value=\"!!max_categ!!\" />
    !!categories_repetables!!
    <div id='addcateg'/>
        </div>
</div>

<div id='el6Child_1' title='".htmlentities($msg[indexint_catal_title],ENT_QUOTES, $charset)."' movable='yes'>
    <!--    indexation interne    -->
    <div id='el6Child_1a' class='row'>
        <label for='f_categ' class='etiquette'>$msg[indexint_catal_title]</label>
    </div>
    <div id='el6Child_1b' class='row'>
        <input type='text' class='saisie-80emr' id='f_indexint' name='f_indexint' value=\"!!indexint!!\" completion=\"indexint\" autfield=\"f_indexint_id\" />
        <input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=indexint&caller=notice&param1=f_indexint_id&param2=f_indexint&parent=0&deb_rech='+".pmb_escape()."(this.form.f_indexint.value)+'&typdoc='+(this.form.typdoc.value)+'&num_pclass=!!num_pclass!!', 'select_categ', 600, 320, -2, -2, '$select3_prop')\" />
        <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_indexint.value=''; this.form.f_indexint_id.value='0'; \" />
        <input type='hidden' name='f_indexint_id' id='f_indexint_id' value='!!indexint_id!!' />
    </div>

</div>

<div id='el6Child_2' title='".htmlentities($msg[324],ENT_QUOTES, $charset)."' movable='yes'>
    <!--    Indexation libre    -->
    <div id='el6Child_2a' class='row'>
        <label for='f_indexation' class='etiquette'>$msg[324]</label>
    </div>
    <div id='el8Child_2b' class='row'>
        <textarea class='saisie-80em' id='f_indexation' completion='tags' keys='113' name='f_indexation' rows='3' wrap='virtual'>!!f_indexation!!</textarea>
    </div>
    <div id='el8Child_2_comment' class='row'>
        <span>$msg[324]$msg[1901]$msg[325]</span>
    </div>
</div>
</div>
";
//	----------------------------------------------------
//	 Categories repetables
// 	  $ptab[40]
//	----------------------------------------------------
$pdeptab[40] = "
    <div id='el6Child_0b_first' class='row'>
        <input type='text' class='saisie-80emr' id='f_categ!!icateg!!' name='f_categ!!icateg!!' value=\"!!categ_libelle!!\" completion=\"categories_mul\" autfield=\"f_categ_id!!icateg!!\" />

        <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_categ!!icateg!!.value=''; this.form.f_categ_id!!icateg!!.value='0'; \" />
        <input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=categorie&caller=notice&p1=f_categ_id!!icateg!!&p2=f_categ!!icateg!!&dyn=1&parent=0', 'select_categ', 700, 500, -2, -2, '$select_categ_prop')\" />
        <input type='hidden' name='f_categ_id!!icateg!!' id='f_categ_id!!icateg!!' value='!!categ_id!!' />
        <input type='button' class='bouton' value='+' onClick=\"add_categ();\"/>
    </div>
    ";
$pdeptab[401] = "
    <div id='el6Child_0b_others' class='row'>
        <input type='text' class='saisie-80emr' id='f_categ!!icateg!!' name='f_categ!!icateg!!' value=\"!!categ_libelle!!\" completion=\"categories_mul\" autfield=\"f_categ_id!!icateg!!\" />

        <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_categ!!icateg!!.value=''; this.form.f_categ_id!!icateg!!.value='0'; \" />
        <input type='hidden' name='f_categ_id!!icateg!!' id='f_categ_id!!icateg!!' value='!!categ_id!!' />
    </div>
    ";

//	----------------------------------------------------
// 	  $pdeptab[5] : contenu de l'onglet 5 (langues)
//    ----------------------------------------------------

$pdeptab[5] = "
<!-- onglet 7 -->
<div id='el7Parent' class='parent'>
<h3>
    <img src='./images/plus.gif' class='img_plus' name='imEx' id='el7Img' title='langues' border='0' onClick=\"expandBase('el7', true); return false;\" />
    $msg[710]
</h3>
</div>

<div id='el7Child' class='child' etirable='yes' title='".htmlentities($msg[710],ENT_QUOTES, $charset)."'>

<div id='el7Child_0' title='".htmlentities($msg[710],ENT_QUOTES, $charset)."' movable='yes'>
    <!--    Langues    -->
    <div id='el7Child_0a' class='row'>
        <label for='f_langue' class='etiquette'>$msg[710]</label>
    </div>
    <input type='hidden' name='max_lang' value=\"!!max_lang!!\" />
    !!langues_repetables!!
    <div id='addlang'/>
        </div>
</div>

<div id='el7Child_1' title='".htmlentities($msg[711],ENT_QUOTES, $charset)."' movable='yes'>
    <!--    Langues    -->
    <div id='el7Child_1a' class='row'>
        <label for='f_langorg' class='etiquette'>$msg[711]</label>
    </div>
    <input type='hidden' name='max_langorg' value=\"!!max_langorg!!\" />
    !!languesorg_repetables!!
    <div id='addlangorg'/>
        </div>
</div>

</div>
";

//    ----------------------------------------------------
//     Langues répétables
//       $ptab[70]
//    ----------------------------------------------------
$pdeptab[50] = "
    <div id='el7Child_0a' class='row'>
        <input type='text' class='saisie-30emr' id='f_lang!!ilang!!' name='f_lang!!ilang!!' value=\"!!lang!!\" completion=\"langue\" autfield=\"f_lang_code!!ilang!!\" />
        <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_lang!!ilang!!.value=''; this.form.f_lang_code!!ilang!!.value=''; \" />
		<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=lang&caller=notice&p1=f_lang_code!!ilang!!&p2=f_lang!!ilang!!', 'select_lang', 400, 400, -2, -2, '$select2_prop')\" />
        <input type='hidden' name='f_lang_code!!ilang!!' id='f_lang_code!!ilang!!' value='!!lang_code!!' />
        <input type='button' class='bouton' value='+' onClick=\"add_lang();\"/>
    </div>
    ";

$pdeptab[501] = "
    <div id='el7Child_0a' class='row'>
        <input type='text' class='saisie-30emr' id='f_lang!!ilang!!' name='f_lang!!ilang!!' value=\"!!lang!!\" completion=\"langue\" autfield=\"f_lang_code!!ilang!!\" />
        <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_lang!!ilang!!.value=''; this.form.f_lang_code!!ilang!!.value=''; \" />
		<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=lang&caller=notice&p1=f_lang_code!!ilang!!&p2=f_lang!!ilang!!', 'select_lang', 400, 400, -2, -2, '$select2_prop')\" />
        <input type='hidden' name='f_lang_code!!ilang!!' id='f_lang_code!!ilang!!' value='!!lang_code!!' />
    </div>
    ";

//    ----------------------------------------------------
//     Langues originales répétables
//       $ptab[71]
//    ----------------------------------------------------
$pdeptab[51] = "
    <div id='el7Child_0b' class='row'>
        <input type='text' class='saisie-30emr' id='f_langorg!!ilangorg!!' name='f_langorg!!ilangorg!!' value=\"!!langorg!!\" completion=\"langue\" autfield=\"f_langorg_code!!ilangorg!!\" />
        <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_langorg!!ilangorg!!.value=''; this.form.f_langorg_code!!ilangorg!!.value=''; \" />
		<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=lang&caller=notice&p1=f_langorg_code!!ilangorg!!&p2=f_langorg!!ilangorg!!', 'select_lang', 400, 400, -2, -2, '$select2_prop')\" />
        <input type='hidden' name='f_langorg_code!!ilangorg!!' id='f_langorg_code!!ilangorg!!' value='!!langorg_code!!' />
        <input type='button' class='bouton' value='+' onClick=\"add_langorg();\"/>
    </div>
    ";
$pdeptab[511] = "
    <div id='el7Child_0b' class='row'>
        <input type='text' class='saisie-30emr' id='f_langorg!!ilangorg!!' name='f_langorg!!ilangorg!!' value=\"!!langorg!!\" completion=\"langue\" autfield=\"f_langorg_code!!ilangorg!!\" />
        <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_langorg!!ilangorg!!.value=''; this.form.f_langorg_code!!ilangorg!!.value=''; \" />
		<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=lang&caller=notice&p1=f_langorg_code!!ilangorg!!&p2=f_langorg!!ilangorg!!', 'select_lang', 400, 400, -2, -2, '$select2_prop')\" />
        <input type='hidden' name='f_langorg_code!!ilangorg!!' id='f_langorg_code!!ilangorg!!' value='!!langorg_code!!' />
    </div>
    ";



//	----------------------------------------------------
// 	  $pdeptab[6] : contenu de l'onglet 6 (liens)

$pdeptab[6] = "
<!-- onglet 6 serials.tpl.php bis -->
<div id='el8Parent' class='parent'>
<h3>
    <img src='./images/plus.gif' class='img_plus' name='imEx' id='el8Img' onClick=\"expandBase('el8', true); return false;\" title='$msg[274]' border='0' />
    $msg[274]
</h3>
</div>

<div id='el8Child' class='child' etirable='yes' title='".htmlentities($msg[274],ENT_QUOTES, $charset)."'>

<div id='el8Child_0' title='".htmlentities($msg[275],ENT_QUOTES, $charset)."' movable='yes'>
<!--    URL associée    -->
<div id='el8Child_0a' class='row'>
    <label for='f_l' class='etiquette'>$msg[275]</label>
</div>
<div id='el8Child_0b' class='row'>
    <input name='f_lien' type='text' class='saisie-80em' id='f_lien' value=\"!!lien!!\" maxlength='255' />
    <input class='bouton' type='button' onClick=\"var l=document.getElementById('f_lien').value; eval('window.open(\''+l+'\')');\" title='$msg[CheckLink]' value='$msg[CheckButton]' />
</div>
</div>

<div id='el8Child_1' title='".htmlentities($msg[276],ENT_QUOTES, $charset)."' movable='yes'>
<!--    Format électronique de la ressource    -->
<div id='el8Child_1a' class='row'>
    <label for='f_eformat' class='etiquette'>$msg[276]</label>
</div>
<div id='el8Child_1b' class='row'>
    <input type='text' class='saisie-80em' id='f_eformat' name='f_eformat' value=\"!!eformat!!\" />
</div>
</div>
</div>
";

//	----------------------------------------------------
//	Champs personalisés
// 	  $ptab[7] : Contenu de l'onglet 7 (champs personalisés)
//	----------------------------------------------------

$pdeptab[7] = "
<!-- onglet 7 -->
<div id='el9Parent' class='parent'>
<h3>
    <img src='./images/plus.gif' class='img_plus' name='imEx' id='el9Img' onClick=\"expandBase('el9', true); return false;\" title='".$msg["notice_champs_perso"]."' border='0' /> ".$msg["notice_champs_perso"]."
</h3>
</div>
<div id='el9Child' class='child' etirable='yes' title='".$msg["notice_champs_perso"]."'>
!!champs_perso!!
</div>
";

//    ----------------------------------------------------
//    Champs de gestion
//       $ptab[8] : Contenu de l'onglet 8 (champs de gestion)
//    ----------------------------------------------------

$pdeptab[8] = "
<!-- onglet 8 -->
<div id='el10Parent' class='parent'>
<h3>
    <img src='./images/plus.gif' class='img_plus' name='imEx' id='el10Img' onClick=\"expandBase('el10', true); return false;\" title='".$msg["notice_champs_gestion"]."' border='0' /> ".$msg["notice_champs_gestion"]."
</h3>
</div>

<div id='el10Child' class='child' etirable='yes' title='".htmlentities($msg[notice_champs_gestion],ENT_QUOTES, $charset)."'>
	<div id='el10Child_0' title='".htmlentities($msg[notice_statut_gestion],ENT_QUOTES, $charset)."' movable='yes'>
		<div id='el10Child_0a' class='row'>
		    <label for='f_notice_statut' class='etiquette'>$msg[notice_statut_gestion]</label>
		</div>
		<div id='el10Child_0b' class='row'>
			!!notice_statut!!
		</div>
	</div>
	<div id='el10Child_1' title='".htmlentities($msg[notice_commentaire_gestion],ENT_QUOTES, $charset)."' movable='yes'>
		<!--    commentaire de gestion    -->
		<div id='el10Child_1a' class='row'>
		    <label for='f_commentaire_gestion' class='etiquette'>$msg[notice_commentaire_gestion]</label>
		</div>
		<div id='el10Child_1b' class='row'>
		    <textarea class='saisie-80em' id='f_commentaire_gestion' name='f_commentaire_gestion' rows='1' wrap='virtual'>!!commentaire_gestion!!</textarea>
		</div>
	</div>
	<div id='el10Child_2' title='".htmlentities($msg[notice_thumbnail_url],ENT_QUOTES, $charset)."' movable='yes'>
		<!--    URL vignette speciale    -->
		<div id='el10Child_2a' class='row'>
		    <label for='f_thumbnail_url' class='etiquette'>$msg[notice_thumbnail_url]</label>
		</div>
		<div id='el10Child_2b' class='row'>
		    <input type=text class='saisie-80em' id='f_thumbnail_url' name='f_thumbnail_url' rows='1' wrap='virtual' value=\"!!thumbnail_url!!\" />
		</div>
	</div>
	<div id='el10Child_4' title='".htmlentities($msg['admin_menu_acces'],ENT_QUOTES, $charset)."' movable='yes'>
		<!-- Droits d'acces -->		
		<!-- rights_form -->
	</div>
</div>
";


//	-----------------------------------------------------------
// 	  $analysis_top : formulaire de notice de dépouillement
global $pmb_catalog_verif_js;
$analysis_top_form = jscript_unload_question();
$analysis_top_form.= "
<!-- script de gestion des onglets -->
<script type='text/javascript' src='./javascript/tabform.js'></script>
".($pmb_catalog_verif_js!= "" ? "<script type='text/javascript' src='./javascript/$pmb_catalog_verif_js'></script>":"")."
<script type='text/javascript'>
<!--
	function test_notice(form)
	{
		if(form.f_tit1.value.length == 0)
			{
				alert(\"$msg[277]\");
				return false;
			}

		if(document.forms['notice'].elements['perio_type_use_existing']){
			var perio_type = document.forms['notice'].elements['perio_type_use_existing'].checked;
		  	var bull_type =  document.forms['notice'].elements['bull_type_use_existing'].checked;
		  	var perio_type_new = document.forms['notice'].elements['perio_type_new'].checked;
		  	var bull_type_new =  document.forms['notice'].elements['bull_type_new'].checked;
		  	
		  	if(!perio_type && bull_type) {
		  		alert(\"".$msg['z3950_bull_already_linked']."\")
		  		return false;
		  	}
		  	if(perio_type_new && (document.getElementById('f_perio_new').value == '')){
		  		alert(\"".$msg['z3950_serial_title_mandatory']."\")
		  		return false;
		  	}
		  	
		  	if(bull_type_new && (document.getElementById('f_bull_new_titre').value == '') && (document.getElementById('f_bull_new_mention').value == '')
		  	&& (document.getElementById('f_bull_new_date').value == '') && (document.getElementById('f_bull_new_num').value == '')){
		  		alert(\"".$msg['z3950_fill_bull']."\")
		  		return false;
		  	}
		  	
		  	if(perio_type && bull_type && (document.getElementById('bul_id').value) == '0'){
	        		alert(\"".$msg['z3950_no_bull_selected']."\")
	        		return false;
	        }
        }";
if($pmb_catalog_verif_js!= ""){
	$analysis_top_form.= "
		var check = check_perso_analysis_form()
		if(check == false) return false;";
} 
$analysis_top_form.= "
		return check_form();
	}
-->
</script>
<script src='javascript/ajax.js'></script>
<script src='javascript/move.js'></script>
<form class='form-$current_module' name='notice' id='notice' method='post' action='./catalog.php?categ=serials&sub=analysis&action=update'>
<h3><div class='left'>!!form_title!!</div><div class='right'>";
if ($PMBuserid==1 && $pmb_form_editables==1) $analysis_top_form.="<input type='button' class='bouton_small' value='Editer format' onClick=\"expandAll(); move_parse_dom(relative)\" id=\"bt_inedit\"/><input type='button' class='bouton_small' value='Relatif' onClick=\"expandAll(); move_parse_dom((!relative))\" style=\"display:none\" id=\"bt_swap_relative\"/>";
if ($pmb_form_editables==1) $analysis_top_form.="<input type='button' class='bouton_small' value=\"Format d'origine\" onClick=\"get_default_pos(); expandAll();  ajax_parse_dom(); if (inedit) move_parse_dom(relative); else initIt();\"/>";
$analysis_top_form.="</div></h3>&nbsp; 
<div class='form-contenu'>
<div class='row'>
	!!doc_type!!  !!location!!
</div>
<div class='row'>
	<a href=\"javascript:expandAll()\"><img src='./images/expand_all.gif' border='0' id=\"expandall\"></a>
	<a href=\"javascript:collapseAll()\"><img src='./images/collapse_all.gif' border='0' id=\"collapseall\"></a>";

$analysis_top_form .= "
	<input type=\"hidden\" name=\"b_level\" value=\"!!b_level!!\">
	<input type=\"hidden\" name=\"h_level\" value=\"!!h_level!!\">
	<input type=\"hidden\" name=\"serial_id\" id=\"serial_id\" value=\"!!id!!\">
	<input type=\"hidden\" name=\"bul_id\" id=\"bul_id\" value=\"!!bul_id!!\">
	<input type=\"hidden\" name=\"analysis_id\" value=\"!!analysis_id!!\">
	<input type=\"hidden\" name=\"id_form\" value=\"!!id_form!!\">
	</div>
	!!type_catal!!
	!!tab0!!
	<hr class='spacer' />
	!!tab1!!
	<hr class='spacer' />
	!!tab2!!
	<hr class='spacer' />
	!!tab3!!
	<hr class='spacer' />
	!!tab4!!
	<hr class='spacer' />
	!!tab5!!
	<hr class='spacer' />
	!!tab6!!
	<hr class='spacer' />
	!!tab7!!
	<hr class='spacer' />
	!!tab8!!
	<hr class='spacer' />
	</div>
<div class='row'>
	<div class='left'>
		<input type='button' class='bouton' value='$msg[76]' onClick=\"unload_off();history.go(-1);\" />
    	<input type='button' class='bouton' value='$msg[77]' id='btsubmit' onClick=\"if (test_notice(this.form)) {unload_off();this.form.submit();}\" />
		!!link_audit!!    
    </div>		
	<div class='right'>!!link_supp!!</div>		
</div>
<div class='row'></div>
</form>
<script type='text/javascript'>".($pmb_form_editables?"get_pos(); ":"")."
	ajax_parse_dom();
	document.forms['notice'].elements['f_tit1'].focus();
	</script>
";

function notice_bul_form() {
}
$notice_bulletin_form = jscript_unload_question();
$notice_bulletin_form.="<div class='row'>
		<a href=\"javascript:expandAll()\"><img src='./images/expand_all.gif' border='0' id=\"expandall\"></a>
		<a href=\"javascript:collapseAll()\"><img src='./images/collapse_all.gif' border='0' id=\"collapseall\"></a>
		!!doc_type!! !!location!!
		<input type=\"hidden\" name=\"b_level\" value=\"!!b_level!!\">
		<input type=\"hidden\" name=\"h_level\" value=\"!!h_level!!\">
		<input type=\"hidden\" name=\"serial_id\" value=\"!!id!!\">
		<input type=\"hidden\" name=\"bul_id\" value=\"!!bul_id!!\">
		<input type=\"hidden\" name=\"analysis_id\" value=\"!!analysis_id!!\">
		<input type=\"hidden\" name=\"id_form\" value=\"!!id_form!!\">
	</div>
	!!serial_bul_form!!
	!!tab0!!
	<hr class='spacer' />
	!!tab1!!
	<hr class='spacer' />
	!!tab2!!
	<hr class='spacer' />
	!!tab3!!
	<hr class='spacer' />
	!!tab4!!
	<hr class='spacer' />
	!!tab5!!
	<hr class='spacer' />
	!!tab6!!
	<hr class='spacer' />
	!!tab7!!
	<hr class='spacer' />
	!!tab8!!
	<hr class='spacer' />
	</div>
";

//$serial_bul_form=str_replace("!!serial_bul_form!!",$serial_bul_form,$notice_bulletin_form);

$liste_script ="
<script type=\"text/javascript\" src=\"./javascript/tablist.js\"></script>
";

// Modif ER suppression du form en liste_debut et liste_fin : <form class='form-$current_module' name=\"notice_list\" class=\"notice-bu\">
$liste_debut ="
<a href=\"javascript:expandAll()\"><img src='./images/expand_all.gif' border='0' id=\"expandall\"></a>
<a href=\"javascript:collapseAll()\"><img src='./images/collapse_all.gif' border='0' id=\"collapseall\"></a>
";

$liste_fin = "";

// template pour le form de saisie code barre (périodiques)
// création d'un exemplaire rattaché à un bulletin
//if($pmb_numero_exemplaire_auto>0) $num_exemplaire_test="if(eval(form.option_num_auto.checked == false ))";
if($pmb_numero_exemplaire_auto==1 || $pmb_numero_exemplaire_auto==3) $num_exemplaire_test="var r=false;try { r=form.option_num_auto.checked;} catch(e) {};if(r==false) ";
if ($pmb_rfid_activate==1 ) {
	$num_exemplaire_rfid_test="if(0)";	
}	
$bul_cb_form = "
<script type='text/javascript'>
<!--
	function test_form(form)
	{
		$num_exemplaire_rfid_test 
		$num_exemplaire_test 
		if(form.noex.value.length == 0)
			{
				alert(\"$msg[292]\");
				document.forms['addex'].elements['noex'].focus();
				return false;
			}
		return true;
	}
-->
</script>
<form class='form-$current_module' name='addex' method='post' action='./catalog.php?categ=serials&sub=bulletinage&action=expl_form&bul_id=!!bul_id!!&expl_id=0'>
<div class='row'>
	<h3>$msg[290]</h3>
	</div>
<!--	Contenu du form	-->
<div class='form-contenu'>
	<div class='row'>
		!!etiquette!!
		</div>
	<div class='row'>
		!!saisie_num_expl!!
		</div>
	</div>
<div class='row'>
	!!btn_ajouter!!
	<input type='button' class='bouton' value=' $msg[explnum_ajouter_doc] ' onClick=\"document.location='./catalog.php?categ=serials&sub=bulletinage&action=explnum_form&bul_id=!!bul_id!!&explnum_id=0'\">
	</div>
</form>
<script type='text/javascript'>
	document.forms['addex'].elements['noex'].focus();
</script>
";

//	----------------------------------
//	$bul_expl_form :form de saisie/modif exemplaire de bulletin
if ($pmb_rfid_activate==1 && $pmb_rfid_serveur_url ) {
	if($pmb_rfid_driver=="ident")  $script_erase="init_rfid_erase(rfid_ack_erase);";
	else $script_erase="rfid_ack_erase(1);";
	
	$rfid_script_catalog="
		$rfid_js_header
		<script type='text/javascript'>
			var flag_cb_rfid=0;
			flag_program_rfid_ask=0;
			init_rfid_read_cb(0,f_expl);
			nb_part_readed=0;
			
			function f_expl(cb) {
				nb_part_readed=cb.length;
				if(flag_program_rfid_ask==1) {
					program_rfid();
					flag_cb_rfid=0; 
					return;
				}
				if(cb.length==0) {
					flag_cb_rfid=1;
					return;
				} 
				if(!cb[0]) {
					flag_cb_rfid=0; 
					return;
				}
				if(document.getElementById('f_ex_cb').value	== cb[0]) flag_cb_rfid=1;
				else  flag_cb_rfid=0;
				if(document.getElementById('f_ex_cb').value	== '') {	
					flag_cb_rfid=0;				
					document.getElementById('f_ex_cb').value=cb[0];
				}
			}

			function script_rfid_encode() {
				if(!flag_cb_rfid && flag_rfid_active) {
				    var confirmed = confirm(\"".addslashes($msg['rfid_programmation_confirmation'])."\");
				    if (confirmed) {
						return false;
				    } 
				}
			}
			
			function program_rfid_ask() {
				if (flag_semaphore_rfid_read==1) {
					flag_program_rfid_ask=1;
				} else {
					program_rfid();
				}
			}

			function program_rfid() {
				flag_semaphore_rfid=1;
				flag_program_rfid_ask=0; 
				var nbparts = document.getElementById('f_ex_nbparts').value;	
				if(nb_part_readed!= nbparts) {
					flag_semaphore_rfid=0;
					alert(\"".addslashes($msg['rfid_programmation_nbpart_error'])."\");
					return;
				}
				$script_erase
			}
			
			function rfid_ack_erase(ack) {
				var cb = document.getElementById('f_ex_cb').value;
				var nbparts = document.getElementById('f_ex_nbparts').value;	
				if(!nbparts)nbparts=1;
				init_rfid_write_etiquette(cb,nbparts,rfid_ack_write);				
			}

			function rfid_ack_write(ack) {
				alert (\"".addslashes($msg['rfid_etiquette_programmee_message'])."\");
				flag_semaphore_rfid=0;
			}

		</script>
";
	$rfid_program_button="<input  type=button class='bouton' value=' ". $msg['rfid_configure_etiquette_button']." ' onClick=\"program_rfid_ask();\">";	
}else {	
	$rfid_script_catalog="";
	$rfid_program_button="";
}
		
$bul_expl_form = jscript_unload_question();
$bul_expl_form.="
$rfid_script_catalog
<script type='text/javascript'>
<!--
	function test_form(form) {
		!!questionrfid!!
		if((form.f_ex_cb.value.length == 0) || (form.expl_cote.value.length == 0)) {
			alert(\"$msg[304]\");
			return false;
		}
		unload_off();
		return check_form();
	}
	function calcule_section(selectBox) {
		for (i=0; i<selectBox.options.length; i++) {
			id=selectBox.options[i].value;
		    list=document.getElementById(\"docloc_section\"+id);
		    list.style.display=\"none\";
		}
	
		id=selectBox.options[selectBox.selectedIndex].value;
		list=document.getElementById(\"docloc_section\"+id);
		list.style.display=\"block\";
	}
-->
</script>
<form class='form-$current_module' name='expl' id='expl-form' method='post' action='!!action!!'>
<h3>$msg[300]</h3>
<div class='form-contenu'>
<div class='row'>
	<div class='colonne3'>
		<!-- code barre -->
		<label class='etiquette' for='f_ex_cb'>$msg[291]</label>
		<div class='row'>
			<input type='text' class='saisie-20emr' id=\"f_ex_cb\" value='!!cb!!' name='f_ex_cb' readonly>
			<input type=button class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./catalog/expl/setcb.php', 'ex_getcb', 220, 100, -2, -2, 'toolbar=no')\">
			</div>
		</div>
</div>
<div class='row'>
	<div class='colonne3'>
		<!-- cote -->
			<label class='etiquette' for='f_ex_cote'>$msg[296]</label>
		<div class='row'>
			<input type='text' class='text' id=\"f_ex_cote\" name='expl_cote' value='!!cote!!' />
			</div>
		</div>
	<div class='colonne3'>
		<!-- type document -->
		<label class='etiquette' for='f_ex_typdoc'>$msg[294]</label>
		<div class='row'>
			!!type_doc!!
			</div>
		</div>
	<div class='colonne3'>
		<!-- Nombre de parties -->
		<label class='etiquette' for='f_ex_nbparts'>".$msg["expl_nbparts"]."</label>
		<div class='row'>
			<input type='text' class='saisie-5em' id=\"f_ex_nbparts\" value='!!nbparts!!' name='f_ex_nbparts' >
			</div>
		</div>
	</div>
<div class='row'>
	<div class='colonne3'>
		<!-- localisation -->
		<label class='etiquette' for='f_ex_location'>$msg[298]</label>
		<div class='row'>
			!!localisation!!
			</div>
		</div>

	<div class='colonne3'>
		<!-- section -->
		<label class='etiquette' for='f_ex_section'>$msg[295]</label>
		<div class='row'>
			!!section!!
			</div>
		</div>

	<div class='colonne3'>
		<!-- propriétaire -->
		<label class='etiquette' for='f_ex_owner'>$msg[651]</label> 
		<div class='row'>
			!!owner!!
			</div>
		</div>
	</div>
<div class='row'>
	<div class='colonne3'>
		<!-- statut -->
		<label class='etiquette' for='f_ex_statut'>$msg[297]</label>
		<div class='row'>
			!!statut!!
			</div>
		</div>
	<div class='colonne3'>
		<!-- code stat -->
		<label class='etiquette' for='f_ex_cstat'>$msg[299]</label>
		<div class='row'>
			!!codestat!!
			</div>
		</div>
	!!type_antivol!!
	</div>

<!-- notes -->
<div class='row'>
	<div class='colonne2'>
	<label class='etiquette' for='f_ex_note'>$msg[264]</label>
	</div>
	<div class='colonne2'><!-- msg_exp_cre_date --></div>
</div>
<div class='row'>
	<div class='colonne2'>
		<textarea name='expl_note' id='f_ex_note' class='saisie-80em'>!!note!!</textarea>
	</div>
	<div class='colonne2'><!-- exp_cre_date --></div>
</div>
<div class='row'>
	<div class='colonne2'>
		<label class='etiquette' for='f_ex_comment'>$msg[expl_zone_comment]</label>
	</div>
	<div class='colonne2'><!-- msg_exp_upd_date --></div>
</div>
<div class='row'>
	<div class='colonne2'>
		<textarea name='f_ex_comment' id='f_ex_comment' class='saisie-80em'>!!comment!!</textarea>
	</div>
	<div class='colonne2'><!-- exp_upd_date --></div>
</div>
	
<!-- prix -->
<div class='row'>
	<label class='etiquette' for='f_ex_prix'>$msg[4050]</label>
	</div>
<div class='row'>
	<input type='text' class='text' name='expl_prix' id='f_ex_prix' value=\"!!prix!!\" />
</div>
<div class='row'></div>
!!champs_perso!!
<div class='row'></div>
</div>
<div class='row'>
		<br />
	<div class='left'>
		<input type='button' class='bouton' value=' $msg[76] ' onClick=\"unload_off();history.go(-1);\" />
		<input type='submit' class='bouton' value=' $msg[77] ' onClick=\"return test_form(this.form)\" />
		$rfid_program_button
		!!bt_dupliquer!!
		!!link_audit!!
	</div>	
	<div class='right'>!!del!!</div>
		<!-- chams de gestion -->
		<input type=\"hidden\" name=\"expl_bulletin\" value=\"!!bul_id!!\">
		<input type=\"hidden\" name=\"id_form\" value=\"!!id_form!!\">
		<input type=\"hidden\" name=\"org_cb\" value=\"!!org_cb!!\">
		<input type=\"hidden\" name=\"expl_id\" value=\"!!expl_id!!\">
</div>
<div class='row'></div>
</form>
<script type=\"text/javascript\">
	<!--
	document.forms['expl'].elements['expl_cote'].focus();
	
	function confirm_expl_delete() {
		phrase = \"{$msg[confirm_suppr_serial_expl]}\";
		result = confirm(phrase);
		if(result) {
			unload_off();
			document.location = './catalog.php?categ=serials&sub=bulletinage&action=expl_delete&bul_id=!!bul_id!!&expl_id=!!expl_id!!';			
		}	
	}
	-->	
</script>
";

$serial_edit_access ="
<script type='text/javascript'>
<!--
	function test_form(form)
	{
		if(form.user_query.value.length == 0)
			{
				alert(\"$msg[141]\");
				form.user_query.focus();
				return false;
			}
		return true;
	}
-->
</script>
<form class='form-$current_module' name='serial_search' method='post' action='./edit.php?categ=serials&sub=!!etat!!'>
<div class='form-contenu'>
	<div class='row'>
		<label class='etiquette' for='form_cb'>!!message!!</label>
	</div>
	<div class='row'>
		<input class='saisie-80em' id='user_query' type='text' name='user_query' value='!!user_query!!' />
	</div>
	</div>
<div class='row'>
	<input class='bouton' type='submit' value='$msg[89]' onClick='return test_form(this.form)' />
	</div>
</form>
<script type=\"text/javascript\">
	document.forms['serial_search'].elements['user_query'].focus();
</script>
";
$serial_edit_access = str_replace('!!user_query!!', htmlentities(stripslashes($user_query ),ENT_QUOTES, $charset), $serial_edit_access);

$serial_list_tmpl = "
<h1>$msg[1152] \"<strong>!!cle!!</strong>\"</h1>
<table border='0' width='100%'>
!!list!!
</table>
<div class='row'>
!!nav_bar!!
</div>
";

// $perio_replace : form remplacement periodique
$perio_replace = "
<form class='form-$current_module' name='perio_replace' method='post' action='./catalog.php?categ=serials&sub=serial_replace&serial_id=!!serial_id!!'>
<h3>$msg[159] !!old_perio_libelle!! </h3>
<div class='form-contenu'>
    <div class='row'>
        <label class='etiquette' for='par'>$msg[160]</label>
        </div>
    <div class='row'>
        <input type='text' class='saisie-50emr' value='' name='perio_libelle' readonly>
        <input class='bouton' type='button' onclick=\"openPopUp('./select.php?what=perio&caller=perio_replace&param1=by&param2=perio_libelle&no_display=!!serial_id!!', 'select_perio', 600, 400, -2, -2, '$selector_prop')\" title='$msg[157]' value='$msg[parcourir]' />
        <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.perio_libelle.value=''; this.form.by.value='0'; \" />
        <input type='hidden' name='by' value=''>
        </div>
    </div>
<div class='row'>
    <input type='button' class='bouton' value='$msg[76]' onClick=\"history.go(-1);\">
    <input type='submit' class='bouton' value='$msg[159]'>
    </div>
</form>
";
// $bulletin_replace : form remplacement bulletin
$bulletin_replace = "
<form class='form-$current_module' name='bulletin_replace' method='post' action='./catalog.php?categ=serials&sub=bulletin_replace&serial_id=!!serial_id!!&bul_id=!!bul_id!!'>
<h3>$msg[159] !!old_bulletin_libelle!! </h3>
<div class='form-contenu'>
    <div class='row'>
        <label class='etiquette' for='par'>$msg[160]</label>
    </div>
    <div class='row'>
        <input type='text' class='saisie-50emr' value='' name='bulletin_libelle' readonly>
        <input class='bouton' type='button' onclick=\"openPopUp('./select.php?what=bulletin&caller=bulletin_replace&param1=by&param2=bulletin_libelle&no_display=!!bul_id!!', 'select_bulletin', 600, 500, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\" title='$msg[157]' value='$msg[parcourir]' />
        <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.bulletin_libelle.value=''; this.form.by.value='0'; \" />
        <input type='hidden' name='by' value=''>
    </div>
    <div class='row'>
	!!del_depouillement!!   
	</div>
</div>
	
<div class='row'>
    <input type='button' class='bouton' value='$msg[76]' onClick=\"history.go(-1);\">
    <input type='submit' class='bouton' value='$msg[159]'>
    </div>
</form>
";
//	----------------------------------
//	$bul_expl_form1 :form de saisie/modif exemplaire bulletinage

if ($pmb_rfid_activate==1 && $pmb_rfid_serveur_url ) {
	
	$rfid_script_bulletine="
		$rfid_js_header
		<script type='text/javascript'>
			var flag_cb_rfid=0;
			flag_program_rfid_ask=0;
			init_rfid_read_cb(0,f_expl);
			
			function f_expl(cb) {
				if(flag_program_rfid_ask==1) {
					program_rfid();
					flag_cb_rfid=0; 
					return;
				}
				if(cb.length==0) {
					flag_cb_rfid=1;
					return;
				} 
				if(!cb[0]) {
					flag_cb_rfid=0; 
					return;
				}
				if(document.getElementById('f_ex_cb').value	== cb[0]) flag_cb_rfid=1;
				else  flag_cb_rfid=0;
				if(document.getElementById('f_ex_cb').value	== '') {	
					flag_cb_rfid=0;				
					document.getElementById('f_ex_cb').value=cb[0];
				}
			}

			function script_rfid_encode() {
				if(!flag_cb_rfid && flag_rfid_active) {
				    var confirmed = confirm(\"".addslashes($msg['rfid_programmation_confirmation'])."\");
				    if (confirmed) {
						return false;
				    } 
				}
			}
			
			function program_rfid_ask() {
				if (flag_semaphore_rfid_read==1) {
					flag_program_rfid_ask=1;
				} else {
					program_rfid();
				}
			}

			function program_rfid() {
				flag_semaphore_rfid=1;
				flag_program_rfid_ask=0;
				var cb = document.getElementById('f_ex_cb').value;	
				init_rfid_erase(rfid_ack_erase);
			}
			
			function rfid_ack_erase(ack) {
				var cb = document.getElementById('f_ex_cb').value;
				init_rfid_write_etiquette(cb,rfid_ack_write);
				
			}

			function rfid_ack_write(ack) {
				alert (\"".addslashes($msg['rfid_etiquette_programmee_message'])."\");
				flag_semaphore_rfid=0;
			}

		</script>
";

	$rfid_program_button="<input  type=button class='bouton_small' value=' ". $msg['rfid_configure_etiquette_button']." ' onClick=\"program_rfid_ask();\">";	
}else {	
	$rfid_script_bulletine="";
	$rfid_program_button="";
}


$bul_expl_form1 ="
$rfid_script_bulletine
<script type='text/javascript'>
<!--

function test_form(form)
{
	!!questionrfid!!
	if((form.f_ex_cb.value.length == 0) || (form.expl_cote.value.length == 0))
	{
		alert(\"$msg[304]\");
		return false;
	}
	
	
	
	return check_form();
}
function calcule_section(selectBox) {
	for (i=0; i<selectBox.options.length; i++) {
		id=selectBox.options[i].value;
	    list=document.getElementById(\"docloc_section\"+id);
	    list.style.display=\"none\";
	}
	id=selectBox.options[selectBox.selectedIndex].value;
	list=document.getElementById(\"docloc_section\"+id);
	list.style.display=\"block\";
}
-->
</script>
<form class='form-$current_module' name='expl' id='expl-form' method='post' action='!!action!!'>
<h3>$msg[300] !!titre!!</h3>
<div class='form-contenu'>
	<div class='row'>
		<div class='colonne3'>
			<!-- code barre -->
			<label class='etiquette' for='f_ex_cb'>$msg[291]</label>
			<div class='row'>
				<input type='text' class='text' id=\"f_ex_cb\" value='!!cb!!' name='f_ex_cb' >
			</div>
		</div>
		<div class='colonne3'>
			<!-- cote -->
				<label class='etiquette' for='f_ex_cote'>$msg[296]</label>
			<div class='row'>
				<input type='text' class='text' id=\"f_ex_cote\" name='expl_cote' value='!!cote!!' />
			</div>
		</div>
		<div class='colonne3'>
			<!-- type document -->
			<label class='etiquette' for='f_ex_typdoc'>$msg[294]</label>
			<div class='row'>
				!!type_doc!!
			</div>
		</div>
	</div>
	<div class='row'>
		<div class='colonne3'>
			<!-- localisation -->
			<label class='etiquette' for='f_ex_location'>$msg[298]</label>
			<div class='row'>
				!!localisation!!
			</div>
		</div>
	
		<div class='colonne3'>
			<!-- section -->
			<label class='etiquette' for='f_ex_section'>$msg[295]</label>
			<div class='row'>
				!!section!!
			</div>
		</div>
	
		<div class='colonne3'>
			<!-- propriétaire -->
			<label class='etiquette' for='f_ex_owner'>$msg[651]</label> 
			<div class='row'>
				!!owner!!
			</div>
		</div>
	</div>
	<div class='row'>
		<div class='colonne3'>
			<!-- statut -->
			<label class='etiquette' for='f_ex_statut'>$msg[297]</label>
			<div class='row'>
				!!statut!!
			</div>
		</div>
		<div class='colonne3'>
			<!-- code stat -->
			<label class='etiquette' for='f_ex_cstat'>$msg[299]</label>
			<div class='row'>
				!!codestat!!
			</div>
		</div>
		!!type_antivol!!
	</div>	
	<!-- notes -->
	<div class='row'>
		<label class='etiquette' for='f_ex_note'>$msg[expl_message]</label>
	</div>
	<div class='row'>
		<textarea name='expl_note' id='f_ex_note' class='saisie-80em'>!!note!!</textarea>
	</div>
	<div class='row'>
		<label class='etiquette' for='f_ex_comment'>$msg[expl_zone_comment]</label>
	</div>
	<div class='row'>
		<textarea name='expl_comment' id='f_ex_comment' class='saisie-80em'>!!comment!!</textarea>
	</div>
	
	<!-- prix -->
	<div class='row' id='expl_prix'>
		<label class='etiquette' for='f_ex_prix'>$msg[4050]</label>
	</div>
	<div class='row' id='expl_prix'>
		<input type='text' class='text' name='expl_prix' id='f_ex_prix' value=\"!!prix!!\" />
	</div>
	!!champs_perso!!
	<div class='row'></div>
	<hr />
	<h3>$msg[abonnements_titre_donnees_bulletin]</h3>
	<div class='row'>
		<div class='colonne3'>		
				<label class='etiquette' for='bul_no'>$msg[4025]</label>
			<div class='row'>
				<input type='text' id='bul_no' name='bul_no' value='!!bul_no!!' class='saisie-20em' />
			</div>
		</div>
		<div class='colonne3'>
			
			<div class='row'>
			!!destinataire!!
			</div>
		</div>
	</div>
	<div class='row'>
		<div class='row'>
			<label class='etiquette' >$msg[4026]</label>
		</div>
		<div class='row'>
			!!date_date!!
		</div>
	</div>
	<div class='row'>
		<div class='row'>
			<label class='etiquette' >$msg[bulletin_mention_periode]</label>
		</div>
		<div class='row'>
			<input type='text' id='bul_date' name='bul_date' value='!!bul_date!!' class='saisie-50em' />
		</div>
	</div>
	<div class='row'>
		<div class='row'>
			<label class='etiquette' >$msg[bulletin_mention_titre]</label>
		</div>
		<div class='row'>
			<input type='text' id='bul_titre' name='bul_titre' value='!!bul_titre!!' class='saisie-50em' />
		</div>
	</div>
</div>
	<div class='left'>
		<input type='submit' class='bouton_small' value=' $msg[77] ' name='bouton_enregistre' onClick=\"return test_form(this.form)\" />
		$rfid_program_button
	</div>	
		<!-- chams de gestion -->
		<input type=\"hidden\" name=\"expl_bulletin\" value=\"!!bul_id!!\">
		<input type=\"hidden\" name=\"id_form\" value=\"!!id_form!!\">
		<input type=\"hidden\" name=\"org_cb\" value=\"!!org_cb!!\">
		<input type=\"hidden\" name=\"expl_id\" value=\"!!expl_id!!\">

		<input type=\"hidden\" name=\"serial_id\" value=\"!!serial_id!!\">
		<input type=\"hidden\" name=\"numero\" value=\"!!numero!!\">
</form>
!!focus!!
";

$analysis_type_form = "
		<div class='row' id='zone_article'>		
		<input type='hidden' name='id_sug' value='!!id_sug!!' />
		<div class='colonne3'>
			<h3>".$msg['acquisition_catal_perio']."</h3>
			<input type=\"radio\" id=\"perio_type_use_existing\"  value=\"use_existing\" name=\"perio_type\"  !!perio_type_use_existing!!><label for=\"perio_type_use_existing\">".$msg["acquisition_catal_perio_exist"]."</label>
			<blockquote>
			    <div class='row'>
		            <label for='f_perio_existing' class='etiquette'>".$msg[233]."</label>
		            <div class='row' >
						<input type='text' completion='perio' autfield='serial_id' id='f_perio_existing' class='saisie-30emr' name='f_perio_existing' value=\"\" />
		                <input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=perio&caller=notice&param1=serial_id&param2=f_perio_existing&deb_rech='+".pmb_escape()."(this.form.f_perio_existing.value), 'select_perio', 600, 600, -2, -2, '$select1_prop');this.form.f_bull_existing.value=''; this.form.bul_id.value='0'; \" />
		              	<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_perio_existing.value=''; this.form.serial_id.value='0';this.form.f_bull_existing.value=''; this.form.bul_id.value='0'; \" />		               	
		            </div>					
				</div>
			</blockquote>
			<input type=\"radio\" id=\"perio_type_new\"  value=\"insert_new\" name=\"perio_type\" !!perio_type_new!!><label for=\"perio_type_new\">".$msg["acquisition_catal_perio_new"]."</label>
			<blockquote>
			    <div class='row'>
		            <label for='f_perio_new' class='etiquette'>".$msg[233]."</label>
		            <div class='row' >
						<input type='text' id='f_perio_new' class='saisie-50em' name='f_perio_new' value=''/>
		            </div>					
				</div>
				<div class='row'>
		            <label for='f_perio_new_issn' class='etiquette'>".$msg[z3950_issn]."</label>
		            <div class='row' >
						<input type='text' id='f_perio_new_issn' class='saisie-50em' name='f_perio_new_issn' value=''/>
		            </div>					
				</div>
			</blockquote>
		</div>
		<div class='colonne3'>
			<h3>".$msg['acquisition_catal_bull']."</h3>
			<input type=\"radio\" id=\"bull_type_use_existing\" !!bull_type_use_existing!! value=\"use_existing\" name=\"bull_type\"><label for=\"bull_type_use_existing\">".$msg["acquisition_catal_bull_exist"]."</label>
			<blockquote>
			    <div class='row'>
		            <label for='f_bull_existing' class='etiquette'>".$msg['abonnements_titre_numerotation']."/".$msg[4026]."</label>
		            <div class='row' >
						<input type='text' completion='bull' autfield='bul_id' id='f_bull_existing' class='saisie-30emr' name='f_bull_existing' linkfield='serial_id' value=\"\" ' />
		                <input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=bulletin&caller=notice&param1=bul_id&param2=f_bull_existing&no_display='+this.form.bul_id.value+'&deb_rech='+".pmb_escape()."(this.form.f_bull_existing.value)+'&idperio='+this.form.serial_id.value, 'select_bull', 600, 600, -2, -2, '$select1_prop')\" />
		              	<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_bull_existing.value=''; this.form.bul_id.value='0'; \" />
		            </div>					
				</div>
			</blockquote>
			<input type=\"radio\" id=\"bull_type_new\" !!bull_type_new!! value=\"insert_new\" name=\"bull_type\"><label for=\"bull_type_new\">".$msg["acquisition_catal_bull_new"]."</label>
			<blockquote>
			    <div class='row'>
			    	<div class='colonne2'>
			    		<div class='row' >
			            	<label for='f_bull_new_num' class='etiquette'>".$msg['abonnements_titre_numerotation']."</label>
						</div>
			            <div class='row' >
							<input type='text' id='f_bull_new_num' class='saisie-20em' name='f_bull_new_num' value=''/>
			            </div>	
		         	</div>	
		         	<div class='colonne2'>
			    		<div class='row' >
			            	<label for='f_bull_new_titre' class='etiquette'>".$msg[233]."</label>
						</div>
			            <div class='row' >
							<input type='text' id='f_bull_new_titre' class='saisie-50em' name='f_bull_new_titre' value='' />
			            </div>	
		         	</div>				
				</div>
				<div class='row'>
					<div class='colonne2'>
						<div class='row'>
							<label class='etiquette' >$msg[4026]</label>
						</div>
						<div class='row'>
							!!date_date!!
						</div>
					</div>
					<div class='colonne2'>
						<div class='row'>
							<label class='etiquette' >".$msg['bulletin_mention_periode']."</label>
						</div>
						<div class='row'>
							<input type='text' id='f_bull_new_mention' name='f_bull_new_mention' value='' class='saisie-50em' />
						</div>
					</div>
				</div>
			</blockquote>
		</div>
	</div>
";