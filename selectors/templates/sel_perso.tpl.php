<?php
// +-------------------------------------------------+

// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sel_perso.tpl.php,v 1.7 2009-05-16 10:52:55 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], "tpl.php")) die("no access");

// templates du sélecteur indexint

//--------------------------------------------
//	$nb_per_page : nombre de lignes par page
//--------------------------------------------
// nombre de références par pages
if ($nb_per_page_s_select != "") 
	$nb_per_page = $nb_per_page_s_select ;
	else $nb_per_page = 10;

//-------------------------------------------
//	$sel_header : header
//-------------------------------------------
$sel_header = "
<div class='row'>
	<label for='titre_select_indexint' class='etiquette'>!!select_title!!</label>
	</div>
<div class='row'>
";


//-------------------------------------------
//	$jscript : script de m.a.j. du parent
//-------------------------------------------

$jscript_ = "
<script type='text/javascript'>
<!--
function set_parent_w(f_caller, id_value, libelle_value,w)
{
	dyn=$dyn;
	nomchamp='$perso_name';
	if (dyn) {
		n_chp=w.opener.document.forms[f_caller].elements['n_'+nomchamp].value;
		flag = 1;
		//Vérification que la valeur du champ perso n'est pas déjà sélectionnée
		for (i=0; i<n_chp; i++) {	
			if (w.opener.document.getElementById('f_'+nomchamp+'_'+i).value==libelle_value) {
				alert('".$msg["persovalue_already_in_use"]."');
				flag = 0;
				break;
			}
		}
		if (flag) {
			for (i=0; i<n_chp; i++) {
				if ((w.opener.document.getElementById('f_'+nomchamp+'_'+i).value==0)||(w.opener.document.getElementById('f_'+nomchamp+'_'+i).value=='')) break;
			}

			try{
				if (i==n_chp) w.opener.add_$perso_name();
			} catch(e){
				i=0;
				w.opener.document.getElementById('f_'+nomchamp+'_'+i).value=reverse_html_entities(libelle_value);
				parent.parent.close();
			}
			w.opener.document.getElementById(nomchamp+'_'+i).value = id_value;
			w.opener.document.getElementById('f_'+nomchamp+'_'+i).value = reverse_html_entities(libelle_value);
		}
	} else {
		w.opener.document.forms[f_caller].elements['$p1'].value=id_value;
		w.opener.document.forms[f_caller].elements['$p2'].value=reverse_html_entities(libelle_value);
		parent.parent.close();
	}
}
-->
</script>
";

$jscript = $jscript_."
<script type='text/javascript'>
<!--
function set_parent(f_caller, id_value, libelle_value)
{
	set_parent_w(f_caller, id_value, libelle_value,parent);
}
-->
</script>
";

//-------------------------------------------
//	$sel_search_form : module de recherche
//-------------------------------------------
$sel_search_form ="
<form name='search_form' method='post' action='$base_url'>
<input type='text' name='f_user_input' value=\"!!deb_rech!!\" />
&nbsp;
<input type='submit' class='bouton_small' value='$msg[142]' /><br />
</form>
<script type='text/javascript'>
<!--
	document.forms['search_form'].elements['f_user_input'].focus();
-->
</script>
";


//-------------------------------------------
//	$sel_footer : footer
//-------------------------------------------
$sel_footer = "
</div>
";
