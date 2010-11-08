<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: misc.inc.php,v 1.11 2009-03-17 21:04:57 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// fonctions javascript diverses

// ----------------------------------------------------
//			mise à jour du titre de la fenêtre
// ----------------------------------------------------
function window_title($title='PMB') {
	$title = pmb_preg_replace('/\"/m', "'", $title);
	return "<script type='text/javascript'>document.title=\"$title\";window.status=\"$title\";</script>";
	}

function form_focus($form, $element) {
	return "<script type='text/javascript'>document.forms['$form'].elements['$element'].focus();</script>";
	}

function confirmation_delete($url) {
	
	global $msg;
	
	return "<script type='text/javascript'>
		function confirmation_delete(param,element) {
        		result = confirm(\"".$msg['confirm_suppr_de']." '\"+element+\"' ?\");
        		if(result) document.location = \"$url\"+param ;
   			}</script>";
	}

function reverse_html_entities() {
	return "<script type='text/javascript'>
		function replace_texte(string,text,by) {
		    var strLength = string.length, txtLength = text.length;
		    if ((strLength == 0) || (txtLength == 0)) return string;
		
		    var i = string.indexOf(text);
		    if ((!i) && (text != string.substring(0,txtLength))) return string;
		    if (i == -1) return string;
		
		    var newstr = string.substring(0,i) + by;
		
		    if (i+txtLength < strLength)
		        newstr += replace_texte(string.substring(i+txtLength,strLength),text,by);
		
		    return newstr;
		}
		
		function reverse_html_entities(text) {
		    
		    text = replace_texte(text,'&quot;',unescape('%22'));
		    text = replace_texte(text,'&amp;',unescape('%26'));
		    text = replace_texte(text,'&lt;',unescape('%3C'));
		    text = replace_texte(text,'&gt;',unescape('%3E'));
		    text = replace_texte(text,'&nbsp;',unescape('%A0'));
		    text = replace_texte(text,'&iexcl;',unescape('%A1'));
		    text = replace_texte(text,'&cent;',unescape('%A2'));
		    text = replace_texte(text,'&pound;',unescape('%A3'));
		    text = replace_texte(text,'&yen;',unescape('%A5'));
		    text = replace_texte(text,'&brvbar;',unescape('%A6'));
		    text = replace_texte(text,'&sect;',unescape('%A7'));
		    text = replace_texte(text,'&uml;',unescape('%A8'));
		    text = replace_texte(text,'&copy;',unescape('%A9'));
		    text = replace_texte(text,'&ordf;',unescape('%AA'));
		    text = replace_texte(text,'&laquo;',unescape('%AB'));
		    text = replace_texte(text,'&not;',unescape('%AC'));
		    text = replace_texte(text,'&shy;',unescape('%AD'));
		    text = replace_texte(text,'&reg;',unescape('%AE'));
		    text = replace_texte(text,'&macr;',unescape('%AF'));
		    text = replace_texte(text,'&deg;',unescape('%B0'));
		    text = replace_texte(text,'&plusmn;',unescape('%B1'));
		    text = replace_texte(text,'&sup2;',unescape('%B2'));
		    text = replace_texte(text,'&sup3;',unescape('%B3'));
		    text = replace_texte(text,'&acute;',unescape('%B4'));
		    text = replace_texte(text,'&micro;',unescape('%B5'));
		    text = replace_texte(text,'&para;',unescape('%B6'));
		    text = replace_texte(text,'&middot;',unescape('%B7'));
		    text = replace_texte(text,'&cedil;',unescape('%B8'));
		    text = replace_texte(text,'&sup1;',unescape('%B9'));
		    text = replace_texte(text,'&ordm;',unescape('%BA'));
		    text = replace_texte(text,'&raquo;',unescape('%BB'));
		    text = replace_texte(text,'&frac14;',unescape('%BC'));
		    text = replace_texte(text,'&frac12;',unescape('%BD'));
		    text = replace_texte(text,'&frac34;',unescape('%BE'));
		    text = replace_texte(text,'&iquest;',unescape('%BF'));
		    text = replace_texte(text,'&Agrave;',unescape('%C0'));
		    text = replace_texte(text,'&Aacute;',unescape('%C1'));
		    text = replace_texte(text,'&Acirc;',unescape('%C2'));
		    text = replace_texte(text,'&Atilde;',unescape('%C3'));
		    text = replace_texte(text,'&Auml;',unescape('%C4'));
		    text = replace_texte(text,'&Aring;',unescape('%C5'));
		    text = replace_texte(text,'&AElig;',unescape('%C6'));
		    text = replace_texte(text,'&Ccedil;',unescape('%C7'));
		    text = replace_texte(text,'&Egrave;',unescape('%C8'));
		    text = replace_texte(text,'&Eacute;',unescape('%C9'));
		    text = replace_texte(text,'&Ecirc;',unescape('%CA'));
		    text = replace_texte(text,'&Euml;',unescape('%CB'));
		    text = replace_texte(text,'&Igrave;',unescape('%CC'));
		    text = replace_texte(text,'&Iacute;',unescape('%CD'));
		    text = replace_texte(text,'&Icirc;',unescape('%CE'));
		    text = replace_texte(text,'&Iuml;',unescape('%CF'));
		    text = replace_texte(text,'&ETH;',unescape('%D0'));
		    text = replace_texte(text,'&Ntilde;',unescape('%D1'));
		    text = replace_texte(text,'&Ograve;',unescape('%D2'));
		    text = replace_texte(text,'&Oacute;',unescape('%D3'));
		    text = replace_texte(text,'&Ocirc;',unescape('%D4'));
		    text = replace_texte(text,'&Otilde;',unescape('%D5'));
		    text = replace_texte(text,'&Ouml;',unescape('%D6'));
		    text = replace_texte(text,'&times;',unescape('%D7'));
		    text = replace_texte(text,'&Oslash;',unescape('%D8'));
		    text = replace_texte(text,'&Ugrave;',unescape('%D9'));
		    text = replace_texte(text,'&Uacute;',unescape('%DA'));
		    text = replace_texte(text,'&Ucirc;',unescape('%DB'));
		    text = replace_texte(text,'&Uuml;',unescape('%DC'));
		    text = replace_texte(text,'&Yacute;',unescape('%DD'));
		    text = replace_texte(text,'&THORN;',unescape('%DE'));
		    text = replace_texte(text,'&szlig;',unescape('%DF'));
		    text = replace_texte(text,'&agrave;',unescape('%E0'));
		    text = replace_texte(text,'&aacute;',unescape('%E1'));
		    text = replace_texte(text,'&acirc;',unescape('%E2'));
		    text = replace_texte(text,'&atilde;',unescape('%E3'));
		    text = replace_texte(text,'&auml;',unescape('%E4'));
		    text = replace_texte(text,'&aring;',unescape('%E5'));
		    text = replace_texte(text,'&aelig;',unescape('%E6'));
		    text = replace_texte(text,'&ccedil;',unescape('%E7'));
		    text = replace_texte(text,'&egrave;',unescape('%E8'));
		    text = replace_texte(text,'&eacute;',unescape('%E9'));
		    text = replace_texte(text,'&ecirc;',unescape('%EA'));
		    text = replace_texte(text,'&euml;',unescape('%EB'));
		    text = replace_texte(text,'&igrave;',unescape('%EC'));
		    text = replace_texte(text,'&iacute;',unescape('%ED'));
		    text = replace_texte(text,'&icirc;',unescape('%EE'));
		    text = replace_texte(text,'&iuml;',unescape('%EF'));
		    text = replace_texte(text,'&eth;',unescape('%F0'));
		    text = replace_texte(text,'&ntilde;',unescape('%F1'));
		    text = replace_texte(text,'&ograve;',unescape('%F2'));
		    text = replace_texte(text,'&oacute;',unescape('%F3'));
		    text = replace_texte(text,'&ocirc;',unescape('%F4'));
		    text = replace_texte(text,'&otilde;',unescape('%F5'));
		    text = replace_texte(text,'&ouml;',unescape('%F6'));
		    text = replace_texte(text,'&divide;',unescape('%F7'));
		    text = replace_texte(text,'&oslash;',unescape('%F8'));
		    text = replace_texte(text,'&ugrave;',unescape('%F9'));
		    text = replace_texte(text,'&uacute;',unescape('%FA'));
		    text = replace_texte(text,'&ucirc;',unescape('%FB'));
		    text = replace_texte(text,'&uuml;',unescape('%FC'));
		    text = replace_texte(text,'&yacute;',unescape('%FD'));
		    text = replace_texte(text,'&thorn;',unescape('%FE'));
		    text = replace_texte(text,'&yuml;',unescape('%FF'));
		    return text;
		
		}
		</script>";
	}

function jscript_checkbox() {
	
print "
	<script type='text/javascript'>
	function setCheckboxes(the_form, the_objet, do_check) {
		var elts = document.forms[the_form].elements[the_objet+'[]'] ;
		var elts_cnt  = (typeof(elts.length) != 'undefined')
                  ? elts.length
                  : 0;

		if (elts_cnt) {
			for (var i = 0; i < elts_cnt; i++) {
				elts[i].checked = do_check;
				} // end for
			} else {
				elts.checked = do_check;
				} // end if... else
		return true;
	} // end of the 'setCheckboxes()' function
	
	
	</script>";
}

function jscript_checkboxb() {
	
print "<script type='text/javascript'>
		function unSetCheckboxes(the_form, the_objet) {
		var elts = document.forms[the_form].elements[the_objet+'[]'] ;
		var elts_cnt  = (typeof(elts.length) != 'undefined')
                  ? elts.length
                  : 0;

		if (elts_cnt) {
			for (var i = 0; i < elts_cnt; i++) { 
				if (elts[i].checked!=0) {
					elts[i].checked = 0;
				} else {
					elts[i].checked = 1;
				}
			} // end for
		} else {
			if (elts.checked!=0) {
				elts.checked = 0;
			} else {
				elts.checked = 1;
			}
		} // end if... else
		return true;
	} // end of the 'unSetCheckboxes()' function
	
	function verifCheckboxes(the_form, the_objet) {
		var bool=false;
		var elts = document.forms[the_form].elements[the_objet+'[]'] ;
		if (!elts) return false ;
		var elts_cnt  = (typeof(elts.length) != 'undefined')
                  ? elts.length
                  : 0;
		if (elts_cnt) {
			for (var i = 0; i < elts_cnt; i++) { 		
				if (elts[i].checked) {
					bool = true;
				}
			}
		} else {
			return elts.checked;
			// bool = false;
		}
		return bool;
	}
	</script>";
}

function jscript_unload_question() {
	global $msg;
	return "
	<script type=\"text/javascript\">

	    function unload_off(){
	    	window.onbeforeunload = '';
	    }
	    function unload_on(){
	    	window.onbeforeunload = function(e){
	        	return '".$msg['message_unload_page']."';
	   		}
	    }   
	    unload_on();    
	</script>";
}

function auto_hide_getprefs() {
	global $current_module;
	if(!$_SESSION["AutoHide"][$current_module] or sizeof($_SESSION["AutoHide"][$current_module])<1){
		$trueids="0";
	} else {
		$trueids="";
		foreach($_SESSION["AutoHide"][$current_module] as $idh3 => $boolh3){
			if($boolh3=="True"){$trueids.="t,";}
			elseif($boolh3=="False"){$trueids.="f,";}
		}
	}
	return "<script type=\"text/javascript\">var trueids=\"".$trueids."\"</script>";
}

/* fonction JS de vérification du code de contrôle EAN13 : 12 caractères + 1 de contrôle
en peévision de certains contrôles
function ccc13(form) {
	factor = 3;
	sum = 0;

	for (index = form.numero.value.length; index > 0; --index) {
		sum = sum + form.numero.value.substring (index-1, index) * factor;
		factor = 4 - factor;
		}
	cc = ((1000 - sum) % 10);
	form.chiffre.value = "0" + form.numero.value + cc;
	result = form.chiffre.value;
	form.cc.value = cc;

	if (form.numero.value.length!=12){
		alert("BE CAREFUL ! Please key in 12 digits");
		form.cc.value = "";
		form.chiffre.value = "";
		}
	}
*/

?>
