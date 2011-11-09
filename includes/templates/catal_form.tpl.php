<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: catal_form.tpl.php,v 1.110.2.1 2011-09-23 13:24:50 jpermanne Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

global $base_path;

// template pour le form de catalogage

$select1_prop = "scrollbars=yes, toolbar=no, dependent=yes, resizable=yes";
$select2_prop = "scrollbars=yes, toolbar=no, dependent=yes, resizable=yes";
$select3_prop = "scrollbars=yes, toolbar=no, dependent=yes, resizable=yes";
$selector_prop = "scrollbars=yes, toolbar=no, dependent=yes, resizable=yes";
$select_categ_prop = "scrollbars=yes, toolbar=no, dependent=yes, resizable=yes";

// nombre de parties du form
$nb_onglets = 9;

//    ----------------------------------------------------
//       $ptab[0] : contenu de l'onglet 0 (zone de titre)
$ptab[0] = "
<!-- onglet 0 -->
<div id='el0Parent' class='parent' >
    <h3>
    <img src='./images/minus.gif' class='img_plus' align='bottom' name='imEx' id='el0Img' title='$msg[236]' border='0' onClick=\"expandBase('el0', true); return false;\" />
    $msg[712]
    </h3>
    </div>

<div id='el0Child' class='child' etirable='yes' title='".htmlentities($msg[236],ENT_QUOTES, $charset)."' >
    <div id='el0Child_0' title='".htmlentities($msg[237],ENT_QUOTES, $charset)."' movable='yes'>
    <!--    Titre    -->
	<div id='el0Child_0a' class='row'>
        <label for='f_tit1' class='etiquette'>$msg[237]</label>
        </div>
    <div id='el0Child_0b' class='row'>
        <input type='text' class='saisie-80em' id='f_tit1' name='f_tit1' value=\"!!tit1!!\" />
        </div>
	</div>

    <div id='el0Child_1' title='".htmlentities($msg[238],ENT_QUOTES, $charset)."' movable='yes'>
    <!--    Titre propre d'un auteur différent    -->
    <div id='el0Child_1a' class='row'>
        <label for='f_tit2' class='etiquette'>$msg[238]</label>
        </div>
    <div id='el0Child_1b' class='row'>
        <input type='text' class='saisie-80em' id='f_tit2' name='f_tit2' value=\"!!tit2!!\" />
        </div>
	</div>

    <div id='el0Child_2' title='".htmlentities($msg[239],ENT_QUOTES, $charset)."' movable='yes'>
    <!--    Titre parallèle    -->
    <div id='el0Child_2a' class='row'>
        <label for='f_tit3' class='etiquette'>$msg[239]</label>
        </div>
    <div id='el0Child_2b' class='row'>
        <input type='text' class='saisie-80em' id='f_tit3' name='f_tit3' value=\"!!tit3!!\" />
        </div>
	</div>

    <div id='el0Child_3' title='".htmlentities($msg[240],ENT_QUOTES, $charset)."' movable='yes'>
    <!--    Complément du titre    -->
    <div id='el0Child_3a' class='row'>
        <label for='f_tit4' class='etiquette'>$msg[240]</label>
        </div>
    <div id='el0Child_3b' class='row'>
        <input type='text' class='saisie-80em' id='f_tit4' name='f_tit4' value=\"!!tit4!!\" />
        </div>
	</div>

    <div id='el0Child_4' title='".htmlentities($msg[241],ENT_QUOTES, $charset)."' movable='yes'>
    <!--    Partie de    -->
    <div class='row'>
        <div id='el0Child_4a' class='colonne2'>
            <label for='f_tparent' class='etiquette'>$msg[241]</label>
            <div class='row'>
		        <input type='text' class='saisie-30emr' id='f_tparent' name='f_tparent' value=\"!!tparent!!\" completion=\"serie\" autfield=\"f_tparent_id\" />
                <input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=serie&caller=notice&param1=f_tparent_id&param2=f_tparent&deb_rech='+".pmb_escape()."(this.form.f_tparent.value), 'select_serie', 400, 500, -2, -2, '$select1_prop')\" />
                <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_tparent.value=''; this.form.f_tparent_id.value='0'; \" />
                <input type='hidden' name='f_tparent_id' id='f_tparent_id' value=\"!!tparent_id!!\" />
                </div>
            </div>
    <!--    No. de partie    -->
        <div id='el0Child_5a' class='colonne_suite'>
            <label for='f_tnvol' class='etiquette'>$msg[242]</label>
            <div class='row'>
                <input type='text' class='saisie-10em' id='f_tnvol' name='f_tnvol' maxlength='100' value=\"!!tnvol!!\" />
                </div>
            </div>
		<div class='row'></div>
        </div>
	</div>
</div>
";


//    ----------------------------------------------------
//     Titres uniformes
//       $ptab[230] : contenu de l'onglet 230 (Titres uniformes)
//    ----------------------------------------------------
global $pmb_use_uniform_title;
if ($pmb_use_uniform_title) {
	$ptab[230] = "
	<!-- onglet 230 -->
	<div id='el230Parent' class='parent'>
	<h3>
	    <img src='./images/plus.gif' class='img_plus' name='imEx' id='el230Img' title='titres_uniformes' border='0' onClick=\"expandBase('el230', true); return false;\" />
	    ".$msg["catal_onglet_titre_uniforme"]."
	</h3>
	</div>
	
	<div id='el230Child' class='child' etirable='yes' title='".htmlentities($msg["aut_menu_titre_uniforme"],ENT_QUOTES, $charset)."'>
	
	<div id='el230Child_0' title='".htmlentities($msg["aut_menu_titre_uniforme"],ENT_QUOTES, $charset)."' movable='yes'>
	<!--    Titres uniformes    -->    
	!!titres_uniformes!!   
	</div>
	
	</div>
	";
} else $ptab[230] = "";

//    ----------------------------------------------------
//    Mention de responsabilité
//       $ptab[1] : contenu de l'onglet 1 (mention de responsabilité)
//    ----------------------------------------------------
$aut_fonctions= new marc_list('function');

$ptab[1] = "
<script>
    function fonction_selecteur_auteur() {
        name=this.getAttribute('id').substring(4);
        name_id = name.substr(0,6)+'_id'+name.substr(6);
        openPopUp('./select.php?what=auteur&caller=notice&param1='+name_id+'&param2='+name+'&dyn=1&deb_rech='+".pmb_escape()."(document.getElementById(name).value), 'select_author2', 500, 400, -2, -2, '$select1_prop');
    }
    function fonction_selecteur_auteur_change(field) {
    	// id champ text = 'f_aut'+n+suffixe
    	// id champ hidden = 'f_aut'+n+'_id'+suffixe; 
    	// select.php?what=auteur&caller=notice&param1=f_aut0_id&param2=f_aut0&deb_rech='+t
        name=field.getAttribute('id');
        name_id = name.substr(0,6)+'_id'+name.substr(6);
        openPopUp('./select.php?what=auteur&caller=notice&param1='+name_id+'&param2='+name+'&dyn=1&deb_rech='+".pmb_escape()."(document.getElementById(name).value), 'select_author2', 500, 400, -2, -2, '$select1_prop');
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
        openPopUp('./select.php?what=function&caller=notice&param1='+name_code+'&param2='+name+'&dyn=1', 'select_fonction2', 500, 400, -2, -2, '$select1_prop');
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
        openPopUp('./select.php?what=lang&caller=notice&p1='+name_id+'&p2='+name, 'select_lang', 500, 400, -2, -2, '$select2_prop');
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
        openPopUp('./select.php?what=lang&caller=notice&p1='+name_id+'&p2='+name, 'select_lang', 500, 400, -2, -2, '$select2_prop');
    }window.open
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
<!-- onglet 1 -->
<div id='el1Parent' class='parent'>
    <h3>
    <img src='./images/plus.gif' class='img_plus' name='imEx' id='el1Img' onClick=\"expandBase('el1', true); return false;\" title='$msg[243]' border='0' />
    $msg[243]
    </h3>
    </div>

<div id='el1Child' class='child' etirable='yes' title='".htmlentities($msg[243],ENT_QUOTES, $charset)."'>
    <div id='el1Child_0' title='".htmlentities($msg[244],ENT_QUOTES, $charset)."' movable='yes'>
    <!--    Auteur principal    -->
    <div class='row'>
        <div id='el1Child_0a' class='colonne2' id='colonne60'>
            <label for='f_aut0' class='etiquette'>$msg[244]</label>
            <div class='row' >
				<input type='text' completion='authors' autfield='f_aut0_id' id='auteur0' class='saisie-30emr' name='f_aut0' value=\"!!aut0!!\" />
                <input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=auteur&caller=notice&param1=f_aut0_id&param2=f_aut0&deb_rech='+".pmb_escape()."(this.form.f_aut0.value), 'select_author0', 500, 400, -2, -2, '$select1_prop')\" />
              	<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_aut0.value=''; this.form.f_aut0_id.value='0'; \" />
               	<input type='hidden' name='f_aut0_id' id='f_aut0_id' value=\"!!aut0_id!!\" />
            </div>
		</div>
        <!--    Fonction    -->
        <div id='el1Child_1a' class='colonne_suite' id='colonne_suite'>
            <label for='f_f0' class='etiquette'>$msg[245]</label>
            <div class='row'>
		        <input type='text' class='saisie-15emr' id='f_f0' name='f_f0' value=\"!!f0!!\" completion=\"fonction\" autfield=\"f_f0_code\" />
                <input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=function&caller=notice&p1=f_f0_code&p2=f_f0', 'select_func0', 500, 400, -2, -2, '$select2_prop')\" />
                <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_f0.value=''; this.form.f_f0_code.value='0'; \" />
                <input type='hidden' name='f_f0_code' id='f_f0_code' value=\"!!f0_code!!\" />
                </div>
            </div>
        </div>
	</div>

    <div id='el1Child_2' title='".htmlentities($msg[246],ENT_QUOTES, $charset)."' movable='yes'>
	    <!--    Autres auteurs    -->
	    <div id='el1Child_2a' class='row'>
	    	<div class='row'>
		        <label for='f_aut1' class='etiquette'>$msg[246]</label>
		        <input type='hidden' name='max_aut1' value=\"!!max_aut1!!\" />
	        </div>
	        <div class='row' id='addaut1'>
		        !!autres_auteurs!!
			</div>
		</div>
	</div>
    <div id='el1Child_3' title='".htmlentities($msg[247],ENT_QUOTES, $charset)."' movable='yes'>
	    <!--    Auteurs secondaires     -->
	    <div  id='el1Child_3a' class='row'>
	    	<div class='row'>
		        <label for='f_aut2' class='etiquette'>$msg[247]</label>
		        <input type='hidden' name='max_aut2' value=\"!!max_aut2!!\" />
	        </div>
	        <div class='row' id='addaut2'>
	        	!!auteurs_secondaires!!
			</div>
		</div>
	</div>
</div>
";

//    ----------------------------------------------------
//    Autres auteurs
//    ----------------------------------------------------
$ptab[11] = "
        <div id='el1Child_2b_first' class='colonne2'>
            <div class='row'>
               	<input type='text' class='saisie-30emr' completion='authors' autfield='f_aut1_id!!iaut!!' id='f_aut1!!iaut!!' name='f_aut1!!iaut!!' value=\"!!aut1!!\" />
				<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=auteur&caller=notice&param1=f_aut1_id!!iaut!!&param2=f_aut1!!iaut!!&deb_rech='+".pmb_escape()."(this.form.f_aut1!!iaut!!.value), 'select_author2', 500, 400, -2, -2, '$select1_prop')\" />
                <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_aut1!!iaut!!.value=''; this.form.f_aut1_id!!iaut!!.value='0'; \" />
                <input type='hidden' name='f_aut1_id!!iaut!!' id='f_aut1_id!!iaut!!' value=\"!!aut1_id!!\" />
            </div>
        </div>
    	<!--    Fonction    -->
        <div  id='el1Child_2b_others' class='colonne_suite'>
            <div class='row'>
                <input type='text' class='saisie-15emr' id='f_f1!!iaut!!' name='f_f1!!iaut!!' completion='fonction' autfield='f_f1_code!!iaut!!' value=\"!!f1!!\" />
                <input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=function&caller=notice&p1=f_f1_code!!iaut!!&p2=f_f1!!iaut!!', 'select_func2', 500, 400, -2, -2, '$select2_prop')\" />
                <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_f1!!iaut!!.value=''; this.form.f_f1_code!!iaut!!.value='0'; \" />
                <input type='hidden' name='f_f1_code!!iaut!!' id='f_f1_code!!iaut!!' value=\"!!f1_code!!\" />
                &nbsp;<input type='button' class='bouton' value='+' onClick=\"add_aut(1);\"/>
            </div>
        </div>
    " ;

//    ----------------------------------------------------
//    Autres secondaires
//    ----------------------------------------------------
$ptab[12] = "
        <div id='el1Child_3b_first' class='colonne2'>
            <div class='row'>
             	<input type='text' class='saisie-30emr' completion='authors' autfield='f_aut2_id!!iaut!!' id='f_aut2!!iaut!!' name='f_aut2!!iaut!!' value=\"!!aut2!!\" />
				<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=auteur&caller=notice&param1=f_aut2_id!!iaut!!&param2=f_aut2!!iaut!!&deb_rech='+".pmb_escape()."(this.form.f_aut2!!iaut!!.value), 'select_author2', 500, 400, -2, -2, '$select1_prop')\" />
                <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_aut2!!iaut!!.value=''; this.form.f_aut2_id!!iaut!!.value='0'; \" />
                <input type='hidden' name='f_aut2_id!!iaut!!' id='f_aut2_id!!iaut!!' value=\"!!aut2_id!!\" />
            </div>
        </div>
        <!--    Fonction    -->
        <div id='el1Child_3b_others' class='colonne_suite'>
            <div class='row'>
                <input type='text' class='saisie-15emr' id='f_f2!!iaut!!' name='f_f2!!iaut!!' completion='fonction' autfield='f_f2_code!!iaut!!' value=\"!!f2!!\" />
                <input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=function&caller=notice&p1=f_f2_code!!iaut!!&p2=f_f2!!iaut!!', 'select_func2', 500, 400, -2, -2,'$select2_prop')\" />
                <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_f2!!iaut!!.value=''; this.form.f_f2_code!!iaut!!.value='0'; \" />
                <input type='hidden' name='f_f2_code!!iaut!!' id='f_f2_code!!iaut!!' value=\"!!f2_code!!\" />
                &nbsp;<input type='button' class='bouton' value='+' onClick=\"add_aut(2);\"/>
            </div>
        </div>
    " ;

//    ----------------------------------------------------
//    Adresse, éditeurs, collection
//    ----------------------------------------------------
$ptab[2] = "
<!-- onglet 2 -->
<div id='el2Parent' class='parent'>
    <h3>
    <img src='./images/plus.gif' class='img_plus' name='imEx' id='el2Img' border='0' onClick=\"expandBase('el2', true); return false;\" />
    $msg[249]
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
    <input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=editeur&caller=notice&p1=f_ed1_id&p2=f_ed1&p3=f_coll_id&p4=f_coll&p5=f_subcoll_id&p6=f_subcoll&deb_rech='+this.form.f_ed1.value, 'select_ed1', 500, 400, -2, -2, '$select1_prop')\" />
    <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_ed1.value=''; this.form.f_ed1_id.value='0'; \" />
    <input type='hidden' name='f_ed1_id' id='f_ed1_id' value=\"!!ed1_id!!\" />
</div>

</div>

<div id='el2Child_1' title='".htmlentities($msg[250],ENT_QUOTES, $charset)."' movable='yes'>
<div class='row'>
    <!--    Collection    -->
    <div id='el2Child_1a' class='colonne2'>
    <label for='f_coll' class='etiquette'>$msg[250]</label>
    <div class='row'>
		<input type='text' completion='collections' autfield='f_coll_id' id='f_coll' name='f_coll' value=\"!!coll!!\" class='saisie-30emr' linkfield='f_ed1_id' />
        <input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=collection&caller=notice&p1=f_ed1_id&p2=f_ed1&p3=f_coll_id&p4=f_coll&p5=f_subcoll_id&p6=f_subcoll&deb_rech='+this.form.f_coll.value, 'select_coll', 500, 400, -2, -2, '$select1_prop')\" />
        <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_coll.value=''; this.form.f_coll_id.value='0'; \" />
        <input type='hidden' name='f_coll_id' id='f_coll_id' value=\"!!coll_id!!\" />
        </div>
        </div>
    <!--    No. dans la collection    -->
    <div id='el2Child_2a' class='colonne_suite'>
        <label for='f_nocoll' class='etiquette'>$msg[253]</label>
        <div class='row'>
            <input type='text' class='saisie-15em' id='f_nocoll' name='f_nocoll' value=\"!!nocoll!!\" />
            </div>
        </div>
	</div>

</div>

<div id='el2Child_3' title='".htmlentities($msg[251],ENT_QUOTES, $charset)."' movable='yes'>
<div id='el2Child_3a' class='row'>
    <!--    Sous collection    -->
        <label for='f_subcoll' class='etiquette'>$msg[251]</label>
        <div class='row'>
		<input type='text' completion='subcollections' autfield='f_subcoll_id' id='f_subcoll' name='f_subcoll' value=\"!!subcoll!!\" class='saisie-30emr' linkfield='f_coll_id' />

		<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=subcollection&caller=notice&p1=f_ed1_id&p2=f_ed1&p3=f_coll_id&p4=f_coll&p5=f_subcoll_id&p6=f_subcoll&deb_rech='+this.form.f_subcoll.value, 'select_subcoll', 500, 400, -2, -2, '$select1_prop')\" />
		<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_subcoll.value=''; this.form.f_subcoll_id.value='0'; \" />
		<input type='hidden' id='f_subcoll_id' name='f_subcoll_id' value=\"!!subcoll_id!!\" />
		</div>
    </div>
</div>

<div id='el2Child_4' title='".htmlentities($msg[252],ENT_QUOTES, $charset)."' movable='yes'>
<div id='el2Child_4a' class='row'>&nbsp;</div>
<div class='row'>
    <!--    Année    -->
    <div id='el2Child_5a' class='colonne2'>
        <label for='f_year' class='etiquette'>$msg[252]</label>
        <div class='row'>
            <input type='text' class='saisie-30em' id='f_year' name='f_year' value=\"!!year!!\" />
            </div>
        </div>

    <div id='el2Child_6a' class='colonne_suite'>
        <label for='f_mention_edition' class='etiquette'>$msg[mention_edition]</label>
        <div class='row'>
            <input type='text' class='saisie-20em' id='f_mention_edition' name='f_mention_edition' value=\"!!mention_edition!!\" />
            </div>
        </div>

    </div>
</div>

<div id='el2Child_7' title='".htmlentities($msg[254],ENT_QUOTES, $charset)."' movable='yes'>
<!--    Autre éditeur    -->
<div id='el2Child_7a' class='row'>
    <label for='f_ed2' class='etiquette'>$msg[254]</label>
</div>
<div id='el2Child_7b' class='row'>
    <input type='text' completion='publishers' autfield='f_ed2_id' id='f_ed2' name='f_ed2' value=\"!!ed2!!\" class='saisie-30emr' />
    <input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=editeur&caller=notice&p1=f_ed2_id&p2=f_ed2&p3=dummy&p4=dummy&p5=dummy&p6=dummy&deb_rech='+this.form.f_ed2.value, 'select_ed1', 500, 400, -2, -2, '$select1_prop')\" />
    <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_ed2.value=''; this.form.f_ed2_id.value='0'; \" />
    <input type='hidden' name='dummy' />
    <input type='hidden' name='f_ed2_id' id='f_ed2_id' value=\"!!ed2_id!!\" />
</div>
</div>
</div>
";

//    ----------------------------------------------------
//    ISBN, EAN ou no. commercial
//       $ptab[3] : contenu de l'onglet 3
//    ----------------------------------------------------
$ptab[3] = "
<!-- onglet 3 -->
<div id='el3Parent' class='parent'>
<h3>
    <img src='./images/plus.gif' class='img_plus' name='imEx' id='el3Img' title='$msg[255]' border='0' onClick=\"expandBase('el3', true); return false;\" />
    $msg[255]
</h3>
</div>

<div id='el3Child' class='child' etirable='yes' title='".htmlentities($msg[255],ENT_QUOTES, $charset)."'>
<div id='el3Child_0' title='$msg[255]' movable='yes'>
<!--    ISBN, EAN ou no. commercial    -->
<div id='el3Child_0a' class='row'>
    <label for='f_cb' class='etiquette'>$msg[255]</label>
</div>
<div id='el3Child_0b' class='row'>
    <input class='saisie-20emr' id='f_cb' name='f_cb' readonly value=\"!!cb!!\" />
    <input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./catalog/setcb.php?notice_id=!!notice_id!!', 'getcb', 300, 150, -2, -2, 'toolbar=no, resizable=yes')\" />
    <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_cb.value=''; \" />
</div>
</div>
</div>
";

//    ----------------------------------------------------
//    Collation
//       $ptab[4] : contenu de l'onglet 4 (collation)
//    ----------------------------------------------------

$ptab[4] = "
<!-- onglet 4 -->
<div id='el4Parent' class='parent'>
    <h3>
        <img src='./images/plus.gif' class='img_plus' name='imEx' id='el4Img' title='$msg[257]' border='0' onClick=\"expandBase('el4', true); return false;\" />
        $msg[258]
    </h3>
</div>

<div id='el4Child' class='child' etirable='yes' title='".htmlentities($msg[258],ENT_QUOTES, $charset)."'>

<div id='el4Child_0' title='".htmlentities($msg[259],ENT_QUOTES, $charset)."' movable='yes'>
<!--    Importance matérielle (nombre de pages, d'éléments...)    -->
<div id='el4Child_0a' class='row'>
    <label for='f_npages' class='etiquette'>$msg[259]</label>
</div>
<div id='el4Child_0b' class='row'>
    <input type='text' class='saisie-80em' id='f_npages' name='f_npages' value=\"!!npages!!\" />
</div>
</div>

<div id='el4Child_1' title='".htmlentities($msg[260],ENT_QUOTES, $charset)."' movable='yes'>
<!--    Autres caractèristiques matérielle (ill., ...)    -->
<div id='el4Child_1a' class='row'>
    <label for='f_ill' class='etiquette'>$msg[260]</label>
</div>
<div id='el4Child_1b' class='row'>
    <input type='text' class='saisie-80em' id='f_ill' name='f_ill' value=\"!!ill!!\" />
</div>
</div>

<div id='el4Child_2' title='".htmlentities($msg[261],ENT_QUOTES, $charset)."' movable='yes'>
<!--    Format    -->
<div id='el4Child_2a' class='row'>
    <label for='f_size' class='etiquette'>$msg[261]</label>
</div>
<div id='el4Child_2b' class='row'>
    <input type='text' class='saisie-80em' id='f_size' name='f_size' value=\"!!size!!\" />
</div>
</div>

<div id='el4Child_3' title='".htmlentities($msg[4050],ENT_QUOTES, $charset)."' movable='yes'>
<!--    Prix    -->
<div id='el4Child_3a' class='row'>
    <label for='f_prix' class='etiquette'>$msg[4050]</label>
</div>
<div id='el4Child_3b' class='row'>
    <input type='text' class='saisie-80em' id='f_prix' name='f_prix' value=\"!!prix!!\" />
</div>

</div>

<div id='el4Child_4' title='".htmlentities($msg[262],ENT_QUOTES, $charset)."' movable='yes'>
<!--    Matériel d'accompagnement    -->
<div id='el4Child_4a' class='row'>
    <label for='f_accomp' class='etiquette'>$msg[262]</label>
</div>
<div id='el4Child_4b' class='row'>
    <input type='text' class='saisie-80em' id='f_accomp' name='f_accomp' value=\"!!accomp!!\" />
</div>
</div>
</div>
";

//    ----------------------------------------------------
//    Notes
//       $ptab[5] : contenu de l'onglet 5 (notes)
//    ----------------------------------------------------
$ptab[5] = "
<!-- onglet 5 -->
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
    <textarea id='f_n_contenu' class='saisie-80em' name='f_n_contenu' rows='5' wrap='virtual'>!!n_contenu!!</textarea>
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

//    ----------------------------------------------------
//    Indexation
//       $ptab[6] : contenu de l'onglet 6 (indexation)
//    ----------------------------------------------------
$ptab[6] = "
    <!-- onglet 6 -->
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
        <input type='text' class='saisie-80emr' id='f_indexint' name='f_indexint' value=\"!!indexint!!\" completion=\"indexint\" autfield=\"f_indexint_id\"  typdoc=\"typdoc\" />
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
        <textarea class='saisie-80em' id='f_indexation' name='f_indexation' rows='3' wrap='virtual' completion='tags' keys='113'>!!f_indexation!!</textarea>
    </div>
    <div id='el8Child_2_comment' class='row'>
        <span>$msg[324]$msg[1901]$msg[325]</span>
    </div>
</div>
</div>
";

//    ----------------------------------------------------
//     Catégories répétables
//       $ptab[60]
//    ----------------------------------------------------
$ptab[60] = "
    <div id='el6Child_0b_first' class='row'>
        <input type='text' class='saisie-80emr' id='f_categ!!icateg!!' name='f_categ!!icateg!!' value=\"!!categ_libelle!!\" completion=\"categories_mul\" autfield=\"f_categ_id!!icateg!!\" />

        <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_categ!!icateg!!.value=''; this.form.f_categ_id!!icateg!!.value='0'; \" />
        <input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=categorie&caller=notice&p1=f_categ_id!!icateg!!&p2=f_categ!!icateg!!&dyn=1&parent=0&deb_rech=', 'select_categ', 700, 500, -2, -2, '$select_categ_prop')\" />
        <input type='hidden' name='f_categ_id!!icateg!!' id='f_categ_id!!icateg!!' value='!!categ_id!!' />
        <input type='button' class='bouton' value='+' onClick=\"add_categ();\"/>
    </div>
    ";
$ptab[601] = "
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

$ptab[7] = "
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
$ptab[70] = "
    <div id='el7Child_0a' class='row'>
        <input type='text' class='saisie-30emr' id='f_lang!!ilang!!' name='f_lang!!ilang!!' value=\"!!lang!!\" completion=\"langue\" autfield=\"f_lang_code!!ilang!!\" />
        <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_lang!!ilang!!.value=''; this.form.f_lang_code!!ilang!!.value=''; \" />
		<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=lang&caller=notice&p1=f_lang_code!!ilang!!&p2=f_lang!!ilang!!', 'select_lang', 500, 400, -2, -2, '$select2_prop')\" />
        <input type='hidden' name='f_lang_code!!ilang!!' id='f_lang_code!!ilang!!' value='!!lang_code!!' />
        <input type='button' class='bouton' value='+' onClick=\"add_lang();\"/>
    </div>
    ";

$ptab[701] = "
    <div id='el7Child_0a' class='row'>
        <input type='text' class='saisie-30emr' id='f_lang!!ilang!!' name='f_lang!!ilang!!' value=\"!!lang!!\" completion=\"langue\" autfield=\"f_lang_code!!ilang!!\" />
        <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_lang!!ilang!!.value=''; this.form.f_lang_code!!ilang!!.value=''; \" />
		<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=lang&caller=notice&p1=f_lang_code!!ilang!!&p2=f_lang!!ilang!!', 'select_lang', 500, 400, -2, -2, '$select2_prop')\" />
        <input type='hidden' name='f_lang_code!!ilang!!' id='f_lang_code!!ilang!!' value='!!lang_code!!' />
    </div>
    ";

//    ----------------------------------------------------
//     Langues originales répétables
//       $ptab[71]
//    ----------------------------------------------------
$ptab[71] = "
    <div id='el7Child_0b' class='row'>
        <input type='text' class='saisie-30emr' id='f_langorg!!ilangorg!!' name='f_langorg!!ilangorg!!' value=\"!!langorg!!\" completion=\"langue\" autfield=\"f_langorg_code!!ilangorg!!\" />
        <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_langorg!!ilangorg!!.value=''; this.form.f_langorg_code!!ilangorg!!.value=''; \" />
		<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=lang&caller=notice&p1=f_langorg_code!!ilangorg!!&p2=f_langorg!!ilangorg!!', 'select_lang', 500, 400, -2, -2, '$select2_prop')\" />
        <input type='hidden' name='f_langorg_code!!ilangorg!!' id='f_langorg_code!!ilangorg!!' value='!!langorg_code!!' />
        <input type='button' class='bouton' value='+' onClick=\"add_langorg();\"/>
    </div>
    ";
$ptab[711] = "
    <div id='el7Child_0b' class='row'>
        <input type='text' class='saisie-30emr' id='f_langorg!!ilangorg!!' name='f_langorg!!ilangorg!!' value=\"!!langorg!!\" completion=\"langue\" autfield=\"f_langorg_code!!ilangorg!!\" />
        <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_langorg!!ilangorg!!.value=''; this.form.f_langorg_code!!ilangorg!!.value=''; \" />
		<input type='button' class='bouton' value='$msg[parcourir]' onclick=\"openPopUp('./select.php?what=lang&caller=notice&p1=f_langorg_code!!ilangorg!!&p2=f_langorg!!ilangorg!!', 'select_lang', 500, 400, -2, -2, '$select2_prop')\" />
        <input type='hidden' name='f_langorg_code!!ilangorg!!' id='f_langorg_code!!ilangorg!!' value='!!langorg_code!!' />
    </div>
    ";

//    ----------------------------window.open------------------------
//    Liens
//       $ptab[8] : contenu de l'onglet 8 (liens)
//    ----------------------------------------------------

$ptab[8] = "
<!-- onglet 8 -->
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
    <input name='f_lien' type='text' class='saisie-80em' id='f_lien' value=\"!!lien!!\" />
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

//    ----------------------------------------------------
//    Champs personalisés
//       $ptab[9] : Contenu de l'onglet 9 (champs personalisés)
//    ----------------------------------------------------

$ptab[9] = "
<!-- onglet 9 -->
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
				<input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.f_rel_!!n_rel!!.value=''; this.form.f_rel_id_!!n_rel!!.value='0'; this.form.f_rel_rank_!!n_rel!!.value='0';\"/>
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
        name_rank = name.substr(0,6)+'_rank_'+name.substr(6);
        document.getElementById(name_rank).value=0;
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

//    ----------------------------------------------------
//    Champs de gestion
//       $ptab[10] : Contenu de l'onglet 10 (champs de gestion)
//    ----------------------------------------------------

$ptab[10] = "
<!-- onglet 10 -->
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
	<div id='el10Child_3' title='".htmlentities($msg['admin_menu_acces'],ENT_QUOTES, $charset)."' movable='yes'>
		<!-- Droits d'acces -->		
		<!-- rights_form -->
	</div>
		
</div>";


// $form_notice : formulaire de notice
global $pmb_catalog_verif_js;
$form_notice = jscript_unload_question();
$form_notice.= "
<!-- script de gestion des onglets -->
<script type='text/javascript' src='./javascript/tabform.js'></script>
".($pmb_catalog_verif_js!= "" ? "<script type='text/javascript' src='$base_path/javascript/$pmb_catalog_verif_js'></script>":"")."
<script type='text/javascript'>
<!--
    function test_notice(form)
    {
    ";
if($pmb_catalog_verif_js!= ""){
	$form_notice.= "
		var check = check_perso_form()
		if(check == false) return false;";
} 
$form_notice.= "
		titre1 = form.f_tit1.value; 
		titre1 = titre1.replace(/^\s+|\s+$/g, ''); //trim la valeur
        if(titre1.length == 0) {
           alert(\"$msg[277]\");  
           return false;
		}
		return check_form();
    }
-->
</script>
<script src='javascript/ajax.js'></script>
<script src='javascript/move.js'></script>
<form class='form-$current_module' id='notice' name='notice' method='post' action='!!action!!'>
<h3><div class='left'>!!libelle_form!!</div><div class='right'>";
if ($PMBuserid==1 && $pmb_form_editables==1) $form_notice.="<input type='button' class='bouton_small' value='".$msg["catal_edit_format"]."' onClick=\"expandAll(); move_parse_dom(relative)\" id=\"bt_inedit\"/><input type='button' class='bouton_small' value='Relatif' onClick=\"expandAll(); move_parse_dom((!relative))\" style=\"display:none\" id=\"bt_swap_relative\"/>";
if ($pmb_form_editables==1) $form_notice.="<input type='button' class='bouton_small' value=\"".$msg["catal_origin_format"]."\" onClick=\"get_default_pos(); expandAll();  ajax_parse_dom(); if (inedit) move_parse_dom(relative); else initIt();\"/>";
$form_notice.="</div></h3>&nbsp; 
<div class='form-contenu'>
<div class='row'>
    	!!doc_type!! !!location!!
    </div>
<div class='row'>
	<a href=\"#\" onclick=\"expandAll();return false;\"><img src='./images/expand_all.gif' border='0' id=\"expandall\"></a>
	<a href=\"#\" onclick=\"collapseAll();return false;\"><img src='./images/collapse_all.gif' border='0' id=\"collapseall\"></a>";
$form_notice .= "	<input type='hidden' name='b_level' value='!!b_level!!' />
	<input type='hidden' name='h_level' value='!!h_level!!' />
	</div>
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
!!tab6!!";
global $pmb_use_uniform_title;
if ($pmb_use_uniform_title) $form_notice .= "<hr class='spacer' />!!tab230!!";
$form_notice .= "<hr class='spacer' />
!!tab7!!
<hr class='spacer' />
!!tab8!!
<hr class='spacer' />
!!tab9!!
<hr class='spacer' />
!!tab11!!
<hr class='spacer' />
!!tab10!!
<hr class='spacer' />
</div>

<div class='row'>
	<div class='left'>
    !!link_annul!!
    <input type='button' class='bouton' value='$msg[77]' id='btsubmit' onClick=\"if (test_notice(this.form)) {unload_off();this.form.submit();}\" />
    !!link_remplace!!
    !!link_duplicate!!
    !!link_audit!!
    !!link_z3950!!
	</div>
	<div class='right'>
    !!link_supp!!
	</div>
</div>
<div class='row'></div>
</form>
<script>".($pmb_form_editables?"get_pos(); ":"")."ajax_parse_dom();</script>
";

// $notice_replace : form remplacement notice
$notice_replace = "
<form class='form-$current_module' name='notice_replace' method='post' action='./catalog.php?categ=remplace&id=!!id!!'>
<h3>$msg[159] !!old_notice_libelle!! </h3>
<div class='form-contenu'>
    <div class='row'>
        <label class='etiquette' for='par'>$msg[160]</label>
        </div>
    <div class='row'>
        <input type='text' class='saisie-50emr' value='' name='notice_libelle' readonly>
        <input class='bouton' type='button' onclick=\"openPopUp('./select.php?what=notice&caller=notice_replace&param1=by&param2=notice_libelle&no_display=!!id!!', 'select_notice', 600, 400, -2, -2, '$selector_prop')\" title='$msg[157]' value='$msg[parcourir]' />
        <input type='button' class='bouton' value='$msg[raz]' onclick=\"this.form.notice_libelle.value=''; this.form.by.value='0'; \" />
        <input type='hidden' name='by' value=''>
        </div>
    </div>
<div class='row'>
    <input type='button' class='bouton' value='$msg[76]' onClick=\"history.go(-1);\">
    <input type='submit' class='bouton' value='$msg[159]'>
    </div>
</form>
";