// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: demandes.js,v 1.3 2010-02-23 16:27:22 kantin Exp $


/*
 * Affiche le contenu de la notice dans un div
 */
function show_notice(id){
	
	if(!document.getElementById("pannel_notice")){
		
		var pannel = document.createElement("div");
		pannel.setAttribute("id","pannel_notice");
		pannel.style.border="#000000 solid 1px";
		pannel.style.width="500px";
		pannel.style.left = document.body.clientWidth*1 - document.getElementById("see_dmde").clientWidth*1+"px";
		pannel.style.top=  document.getElementById("see_dmde").clientHeight*1+"px";
		pannel.style.position="absolute";
		pannel.style.background="#FFFFFF";
		pannel.style.overflow="auto";
		pannel.style.zIndex=1500;

		document.getElementById("att").appendChild(pannel);
		document.onmousedown=clic;
		pannel.onmousedown=clic;
	}

	var url = "./ajax.php?module=demandes&categ=dmde&quoifaire=show_notice";
	var action = new http_request();
	action.request(url, true, "idnotice="+id);
	if(action.get_status() == 0){
		document.getElementById("pannel_notice").innerHTML = action.get_text();
	}
}

/*
 * Evenement du clic pour fermeture
 */
function clic(e){
  	if (!e) var e=window.event;
	if (e.stopPropagation) {
		e.preventDefault();
		e.stopPropagation();
	} else { 
		e.cancelBubble=true;
		e.returnValue=false;
	}
  	
	var div_noti = document.getElementById("pannel_notice");
	var is_child = false;
	
	if(e.currentTarget == div_noti) is_child = true;
	
	if(is_child){
		document.onmousedown=clic;
		pannel.onmousedown=clic;
	} else {
		close_pannel();
		document.onmousedown='';
	}
}

/*
 * Ferme la pannel de visualisation de la notice
 */
function close_pannel(){
	document.getElementById("att").removeChild(document.getElementById("pannel_notice"));
}
