<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sel_origine.tpl.php,v 1.11 2010-02-08 11:28:15 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], "tpl.php")) die("no access");

// templates du sélecteur origine

//--------------------------------------------
//	$nb_per_page : nombre de lignes par page
//--------------------------------------------
// nombre de références par pages
if ($nb_per_page_a_select != "") 
	$nb_per_page = $nb_per_page_a_select ;
	else $nb_per_page = 10;

//-------------------------------------------
//	$sel_header : header
//-------------------------------------------

$sel_header = "
<ul class='sel_navbar' >";
	if($filtre == "ONLY_EMPR"){
		$sel_header .="
			<li id='sel_navbar_empr' !!is_current_empr!! ><a href=\"./select.php?what=origine&caller=$caller&sub=empr&param1=$param1&param2=$param2&param3=$param3&param4=$param4&param5=$param5&param6=$param6&callback=$callback&filtre=$filtre&user_input=$user_input\" >".htmlentities($msg['selector_lib_empr'],ENT_QUOTES, $charset)."</a></li>
		";
	} elseif($filtre == "ONLY_USER"){
		$sel_header .="
			<li id='sel_navbar_user' !!is_current_user!! ><a href=\"./select.php?what=origine&caller=$caller&sub=user&param1=$param1&param2=$param2&param3=$param3&param4=$param4&param5=$param5&param6=$param6&callback=$callback&filtre=$filtre&user_input=$user_input\" >".htmlentities($msg['selector_lib_user'],ENT_QUOTES, $charset)."</a></li>
		";
	} else{
		$sel_header .="
			<li id='sel_navbar_empr' !!is_current_empr!! ><a href=\"./select.php?what=origine&caller=$caller&sub=empr&param1=$param1&param2=$param2&param3=$param3&param4=$param4&param5=$param5&param6=$param6&callback=$callback&user_input=$user_input\" >".htmlentities($msg['selector_lib_empr'],ENT_QUOTES, $charset)."</a></li>
			<li id='sel_navbar_user' !!is_current_user!! ><a href=\"./select.php?what=origine&caller=$caller&sub=user&param1=$param1&param2=$param2&param3=$param3&param4=$param4&param5=$param5&param6=$param6&callback=$callback&user_input=$user_input\" >".htmlentities($msg['selector_lib_user'],ENT_QUOTES, $charset)."</a></li>
	";
	}
$sel_header .= "	
</ul>
<div class='row'>
";


//-------------------------------------------
//	$jscript : script de m.a.j. du parent
//-------------------------------------------
$jscript = "
<script type='text/javascript'>
<!--
function set_parent(f_caller, orig_value, lib_orig_value, typ_value, poi_value, loc )
{	
	try{
	  window.opener.document.forms[f_caller].elements['$param1'].value = orig_value;
	} catch(err){}
	try{
		window.opener.document.forms[f_caller].elements['$param2'].value = reverse_html_entities(lib_orig_value);
	} catch(err){}
	try{
		window.opener.document.forms[f_caller].elements['$param3'].value = typ_value;
	} catch(err){}
	try{
		window.opener.document.forms[f_caller].elements['$param4'].value = poi_value;
	} catch(err){}
	try{
		window.opener.document.forms[f_caller].elements['$param5'].value = poi_value;
	} catch(err){}
	try{
	   window.opener.document.getElementById('$param6').innerHTML = poi_value;
	} catch(err){}
	if(loc){
		try{
	   window.opener.document.getElementById('dmde_loc').value = loc;
		} catch(err){}
	}
";
	
if ($callback) $jscript.="\n	window.opener.".$callback."();\n";
$jscript .= "	window.close();
}

-->
</script>
";

//-------------------------------------------
//	$sel_search_form : module de recherche
//-------------------------------------------
$sel_search_form ="
<script type='text/javascript'>
<!--
function test_form(form)
{
	if(form.f_user_input.value.length == 0)
	{
		form.f_user_input.value = '*';
	}
	return true;
}
-->
</script>
<form name='search_form' method='post' action='$base_url'>";
if($pmb_lecteurs_localises)
	$sel_search_form .="<div>!!sel_loc!!</div>";
$sel_search_form .="
<input type='text' name='f_user_input' value=\"!!deb_rech!!\">
&nbsp;
<input type='submit' class='bouton_small' value='$msg[142]' onclick='return test_form(this.form)'>
</form>
<script type='text/javascript'>
<!--
	document.forms['search_form'].elements['f_user_input'].focus();
-->
</script>
<hr />
";

//-------------------------------------------
//	$sel_footer : footer
//-------------------------------------------
$sel_footer = "
</div>
";
