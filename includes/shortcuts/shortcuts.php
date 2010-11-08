<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: shortcuts.php,v 1.9 2008-12-19 14:57:05 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], "shortcuts.php")) die("no access");

if ( ! defined( 'SHORTCUTS' ) ) {
  define( 'SHORTCUTS', 1 );

$escape = 27;

print "
<script type='text/javascript'>
<!--
// affichage des raccourcis

function clean_raccourci() {
	setTimeout(\"top.document.getElementById('keystatus').firstChild.nodeValue=' '\",1000);
}

function touche(e) {
	if (!e) var e = window.event;
	if (e.keyCode) key = e.keyCode;
		else if (e.which) key = e.which;
	
	top.document.getElementById('keystatus').firstChild.nodeValue='$msg[97] - '+String.fromCharCode(key);
	top.document.getElementById('keystatus').style.color='#FF0000';
	key = String.fromCharCode(key);
	key = key.toLowerCase();
	key = key.charCodeAt(0);

	//Traitement des actions
	switch(key) {
		//case ".ord("s").":
		//	if (document.getElementById('btsubmit')) document.getElementById('btsubmit').focus();
		//	e.cancelBubble = true;
		//	if (e.stopPropagation) { e.stopPropagation(); }
		//	clean_raccourci();
		//	break;
		default:	
			switch(key) {
";
if($raclavier)
while(list($cle, $key) = each($raclavier)) {
	print "				case ".ord(pmb_strtolower($key[0]))." : document.location='$key[1]'; break;\n";
	}
print "				default : clean_raccourci(); break;\n";

print "			}
	}
	document.onkeypress=backhome;
}

function backhome(e){
	if (!e) var e = window.event;
	if (e.keyCode) key = e.keyCode;
		else if (e.which) key = e.which;

	if(key == $escape) {
		propagate=true;
		//Récupération de l'objet d'origine
		if (e.target) origine=e.target; else origine=e.srcElement;
	    if (origine.getAttribute('completion')) {
			id=origine.getAttribute('id');
			if (document.getElementById('d'+id).style.display=='block') {
				propagate=false;
			}
		}		
		if (propagate) {
			top.document.getElementById('keystatus').firstChild.nodeValue='$msg[97]';
			top.document.getElementById('keystatus').style.color='#FF0000';
			window.focus();
			document.onkeypress=touche;
		}
	}	
}

document.onkeypress=backhome;




//-->
</script>

";
} # fin déclaration

?>