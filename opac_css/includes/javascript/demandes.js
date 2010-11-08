// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: demandes.js,v 1.3 2010-02-08 11:28:09 kantin Exp $

/*
 * Création du formulaire de saisie d'une action
 */
function show_form(id,type){
	
	var action = new http_request();
	var url = "./ajax.php?module=ajax&categ=demandes&id="+id+"&quoifaire=show_form&type="+type;
	
	action.request(url);
	
	if(action.get_status() == 0){		
		document.getElementById("saisie_form").style.display = "";
		document.getElementById("saisie_form").innerHTML = action.get_text();
		document.getElementById("btn_grp").style.display = "none";
		self.location.href="#anchor_form";
		eval("document.getElementById(\"ask\").onclick=function() { if(refuser_enregistrement()) save_ask("+id+",'"+type+"');  }");
		eval("document.getElementById(\"cancel\").onclick=function() { cancel(); }");
	}
	
}

/*
 * Annulation du formulaire d'action
 */
function cancel(){	
	document.getElementById("btn_grp").style.display = "";	
	document.getElementById("saisie_form").style.display = "none";
}

/*
 * Enregistrement d'une action Question/Réponse
 */
function save_ask(id,type){
	var action = new http_request();
	var suj = document.getElementById("sujet").value;
	var com = document.getElementById("detail").value;
	var date = (document.getElementById("date_rdv") ? document.getElementById("date_rdv").value : '') ;
	var url = "./ajax.php?module=ajax&categ=demandes&quoifaire=save_ask";	
	
	action.request(url, true, "detail="+encodeURIComponent(com)+"&sujet="+encodeURIComponent(suj)+"&id="+id+"&type="+type+"&date_rdv="+date);

	if(action.get_status() == 0){
		document.getElementById("saisie_form").style.display = "none";
		document.getElementById("btn_grp").style.display="";
		document.getElementById("act_list").innerHTML = action.get_text();  		
		document.getElementById("act_list").style.display="";
	}
}

/*
 * formulaire d'ajout d'une note
 */
function addnote(idaction,idnote,iddemande){
	
	var action = new http_request();
	var url = "./ajax.php?module=ajax&categ=demandes&id_action="+idaction+"&quoifaire=add_note";
	
	action.request(url);
	
	if(action.get_status() == 0){		
		document.getElementById("saisie_form").style.display = "";
		document.getElementById("saisie_form").innerHTML = action.get_text();
		document.getElementById("btn_grp").style.display = "none";
		self.location.href="#anchor_form";
		eval("document.getElementById(\"save_note\").onclick=function() { save_note("+idaction+","+idnote+","+iddemande+"); }");
		eval("document.getElementById(\"cancel\").onclick=function() { cancel(); }");
	}
	
}

/*
 * Enregistrement de la note
 */
function save_note(idaction,idnote,iddemande){
	var action = new http_request();
	var com = document.getElementById("contenu").value;
	var url = "./ajax.php?module=ajax&categ=demandes&id_action="+idaction+"&id_note="+idnote+"&id_demande="+iddemande+"&quoifaire=save_note";	
	
	action.request(url, true, "contenu="+encodeURIComponent(com));

	if(action.get_status() == 0){
		document.getElementById("saisie_form").style.display = "none";
		document.getElementById("btn_grp").style.display="";
		document.getElementById("act_list").innerHTML = action.get_text();  		
		document.getElementById("act_list").style.display="";
	}
}