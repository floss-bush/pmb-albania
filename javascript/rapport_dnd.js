/* +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: rapport_dnd.js,v 1.3 2010-02-08 11:28:09 kantin Exp $ */

/****************************************
 * 	Fonctions pour le D&D du rapport	*	
 ****************************************/

var element_rapport = new Array();

/*
 * Depot d'un objet exportable dans le rapport
 */
function export_dropzone(dragged,target){
	
	var url= "./ajax.php?module=demandes&categ=rapport&quoifaire=add_note";
	var action = new http_request();
	
	var com = document.getElementById("elt_"+dragged.getAttribute("idelt")+"Child").innerHTML;
	action.request(url,true,"idobject="+document.getElementById('idobject').getAttribute('value')+
			"&comment="+encodeURIComponent(com)+"&idnote="+dragged.getAttribute("idelt"));
	if(action.get_status() == 0){
		document.getElementById("liste_rapport").innerHTML = action.get_text() ;
		target.style.background = "";
		dragged.parentNode.removeChild(dragged);		
		init_drag();
	}
	
}


/*
 * Ajout d'un élément du rapport en fin de liste
 */
function rapport_dropzone(dragged,target){
	
	if(dragged.getAttribute("id") == target.getAttribute("id")){
		rap_downlight(target);
		return;
	}
	
	dragged.parentNode.appendChild(dragged);	
	
	rap_downlight(target);
	recalc_recept();
		
	update_order(dragged,target);
}

/*
 * Sur survol du recepteur
 */
function rap_highlight(obj) {
	if(obj.getAttribute("titre") == 'yes')
		obj.style.background="#DECDEC";
	else obj.style.background="#DDD";
}

/*
 * On quitte le survol du recepteur
 */
function rap_downlight(obj) {
	if(obj.getAttribute("titre") == 'yes')
		obj.style.background="#DECDEC";
	else obj.style.background="";
}

/*
 * Tri des elements du rapport
 */

function rapport_rapport(dragged,target){
	
	if(dragged.getAttribute("id") == target.getAttribute("id")){
		rap_downlight(target);
		return;
	}
	
	var pere=target.parentNode;
	pere.insertBefore(dragged,target);
	
	rap_downlight(target);
	recalc_recept();
		
	update_order(dragged,target);
}


/*
 * Mis à jour de l'ordre
 */
function update_order(source,cible){
	
	var src_order = source.getAttribute("order");
	var target_order = cible.getAttribute("order");
	var pere = source.parentNode;

	var index = 0;
	for(var i=0;i<pere.childNodes.length;i++){
		if(pere.childNodes[i].nodeType == 1){
			pere.childNodes[i].setAttribute("order",index);
			index++;
		}
	}
	//On ajoute en fin de ligne donc pas de cible, on prend l'ordre du plus grand élément
	if(!target_order)
		target_order = index;
	
	var url= "./ajax.php?module=demandes&categ=rapport&quoifaire=up_order";
	var action = new http_request();
	action.request(url,true,"idsource="+source.getAttribute("iditem")+"&ordre_cible="+target_order+"&ordre_source="+src_order);
}


/*
 * Ajout d'un element exportable dans la liste
 */
function export_rapport(dragged,target){	
	
	
	var url= "./ajax.php?module=demandes&categ=rapport&quoifaire=add_note";
	var action = new http_request();

	var com = document.getElementById("elt_"+dragged.getAttribute("idelt")+"Child").innerHTML;
	action.request(url,true,"idobject="+document.getElementById('idobject').getAttribute('value')+"&comment="+encodeURIComponent(com)+
					"&idnote="+dragged.getAttribute("idelt")+"&ordre_cible="+target.getAttribute("order"));
	if(action.get_status() == 0){
		document.getElementById("liste_rapport").innerHTML = action.get_text() ;
		target.style.background = "";
		dragged.parentNode.removeChild(dragged);		
		init_drag();
	}
}

/*
 * Initialise la fonction de controle clic pour l'ajout d'éléments
 */
function init_action(){
	var div = document.getElementById("col_rapport");
	document.onclick = function(e){
		if(!e) e = window.event;
		if(document.getElementById("popup_action")){
			e.cancelBubble = true;
			if (e.stopPropagation) e.stopPropagation();
			document.getElementById("popup_action").parentNode.removeChild(document.getElementById("popup_action"));
		}
	}
	div.onclick=function(e) {	
		if(!e) e = window.event;	
		if ((e.ctrlKey) || (e.metaKey)) {
			e.cancelBubble = true;
			if (e.stopPropagation) e.stopPropagation();
			if (document.getElementById("popup_action")) document.getElementById("popup_action").parentNode.removeChild(document.getElementById("popup_action"));
			popup=document.createElement("div");
			popup.setAttribute("id","popup_action");
			popup.style.border="#000 1px solid";
			popup.style.background="#EEE";
			popup.style.position="absolute";
			popup.style.zIndex=10;
			popup.style.left=e.pageX+"px";
			popup.style.top=e.pageY+"px";
			var idcible = e.target.getAttribute("id");
			var text_popup = 
				"<div style=\"width:100%;background:#FFF;border-bottom:#000 2px solid;border-top:#000 1px solid;text-align:center\"><b>Actions</b></div>" +
				"<div class='row' onmouseover='this.style.background=\"#666\";' onmouseout='this.style.background=\"\"' onclick='add_element(0,\""+idcible+"\");'>Ajouter un commentaire</div>" +
				"<div class='row' onmouseout='this.style.background=\"\"' onmouseover='this.style.background=\"#666\";' onclick='add_element(1,\""+idcible+"\");';>Ajouter un titre</div>";
			
			popup.innerHTML = text_popup;
			document.body.appendChild(popup);
		} else if (document.getElementById("popup_action")){ 
			e.cancelBubble = true;
			if (e.stopPropagation) e.stopPropagation();
			document.getElementById("popup_action").parentNode.removeChild(document.getElementById("popup_action"));
		}
	}

}

/*
 * Ajout d'un élément au rapport
 *  type = 0 : Commentaire
 *  type = 1 : Titre
 */
function add_element(type,idcible){
	
	var url= "./ajax.php?module=demandes&categ=rapport&quoifaire=show_addcom";
	var action = new http_request();
	action.request(url);
	if(action.get_status() == 0){
		document.getElementById("add_com").innerHTML = action.get_text();
		document.getElementById("add_com").style.display="";
		eval("document.getElementById(\"save_com\").onclick=function() { save_com("+type+",'"+idcible+"'); }");
		eval("document.getElementById(\"cancel_com\").onclick=function() { cancel(); }");
	}
	if (document.getElementById("popup_action")) 
		document.getElementById("popup_action").parentNode.removeChild(document.getElementById("popup_action"));
}

/*
 * Annulation de la saisie de commentaire
 */
function cancel(){
	document.getElementById("add_com").style.display="none";
}

/*
 * Enregistrement du commentaire
 */
function save_com(type,idcible){
	
	var ordre ="";
	if(idcible.indexOf("rap_drag",0) != -1) 
		ordre = "&ordre_cible="+document.getElementById(idcible).getAttribute("order");
	
	var url= "./ajax.php?module=demandes&categ=rapport&quoifaire=add_note";
	var action = new http_request();
	var id = document.getElementById("idobject").getAttribute("value") ;
	var com = document.getElementById("comment").value ;
	action.request(url,true,"idtype="+type+"&idobject="+id+"&comment="+encodeURIComponent(com)+ordre);
	if(action.get_status() == 0){
		document.getElementById("add_com").style.display="none";
		document.getElementById("liste_rapport").innerHTML = action.get_text() ;
		init_drag();
	}
}

/*
 * Modification d'une note
 */
function modif_item(id){
	var url= "./ajax.php?module=demandes&categ=rapport&quoifaire=show_addcom&id="+id;
	var action = new http_request();
	action.request(url);
	if(action.get_status() == 0){
		document.getElementById("add_com").innerHTML = action.get_text();
		document.getElementById("add_com").style.display="";
		eval("document.getElementById(\"save_com\").onclick=function() { update_com("+id+"); }");
		eval("document.getElementById(\"cancel_com\").onclick=function() { cancel(); }");
	}
	
}

/*
 * Mis a jour de la note
 */
function update_com(id){
	var url= "./ajax.php?module=demandes&categ=rapport&quoifaire=update_com";
	var action = new http_request();
	var com = document.getElementById("comment").value ;
	var idobject = document.getElementById("idobject").getAttribute("value") ;
	action.request(url,true,"id="+id+"&idobject="+idobject+"&comment="+encodeURIComponent(com));
	if(action.get_status() == 0){
		document.getElementById("add_com").style.display="none";
		document.getElementById("liste_rapport").innerHTML = action.get_text() ;
		init_drag();
	}
}