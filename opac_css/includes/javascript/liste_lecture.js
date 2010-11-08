// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: liste_lecture.js,v 1.3 2009-10-26 13:35:36 kantin Exp $

/*
 * Création du formulaire de saisie d'envoi d'une demande
 */
function make_mail_form(id){
	
	var action = new http_request();
	var url = "./ajax.php?module=ajax&categ=liste_lecture&id="+id+"&quoifaire=show_form";
	
	action.request(url);
	
	if(action.get_status() == 0){		
		document.getElementById("maillist_"+id).style.display = "";
		document.getElementById("maillist_"+id).innerHTML = action.get_text();
		eval("document.getElementById(\"send_mail_\"+id).onclick=function() { send_mail("+id+"); }");
		eval("document.getElementById(\"cancel_\"+id).onclick=function() { cancel("+id+"); }");
	}
	
}

/*
 * Création du formulaire de saisie d'un refus demande
 */
function make_refus_form(list){
	
	var action = new http_request();
	var url = "./ajax.php?module=ajax&categ=liste_lecture&quoifaire=show_refus_form";
	
	action.request(url);
	
	if(action.get_status() == 0){		
		document.getElementById("refus_dmde").style.display = "";
		document.getElementById("refus").style.display = "none";
		document.getElementById("accept").style.display = "none";
		document.getElementById("refus_dmde").innerHTML = action.get_text();
		eval("document.getElementById(\"cancel\").onclick=function() { cancel_refus(); }");
	}
	
}

/*
 * Annulation du formulaire de demande
 */
function cancel(id){	
	document.getElementById("maillist_"+id).style.display = "none";	
}

/*
 * Annulation du formulaire de refus
 */
function cancel_refus(){	
	document.getElementById("refus_dmde").style.display = "none";	
	document.getElementById("refus").style.display = "";
	document.getElementById("accept").style.display = "";
}

/*
 * Envoi du mail de demande d'accès
 */
function send_mail(id){
	var action = new http_request();
	var com = document.getElementById("liste_demande_"+id).value;
	var id_empr = document.getElementById("id_empr").value;
	var url = "./ajax.php?module=ajax&categ=liste_lecture&id="+id+"&quoifaire=send_demande";	

	action.request(url, true, "com="+com+"&id_empr="+id_empr);
	
	if(action.get_status() == 0){
		document.getElementById("img_confi_"+id).src = "./images/hourglass.png"; 
		document.getElementById("img_confi_"+id).title = "En cours de demande";	
		document.getElementById("maillist_"+id).style.display = "none";	
		eval("document.getElementById(\"liste_\"+id).onclick=function(){demandeEnCours();}");
	}
}


