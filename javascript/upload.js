// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: upload.js,v 1.3 2010-01-22 16:18:57 kantin Exp $


//Ouverture de la frame a partir du parent
function upload_openFrame(e) {

	up_frame=document.getElementById('up_frame');
	if (up_frame==null) {
		if(!e) e=window.event;			
		e.cancelBubble = true;
		if (e.stopPropagation) e.stopPropagation();			
		up_frame=document.createElement("iframe");		
		up_frame.setAttribute('id','up_frame');
		up_frame.setAttribute('name','up_frame');
		up_frame.src="./admin/upload/upload_frame.php"; 		
		var att=document.getElementById("att");	
		up_frame.style.visibility="hidden";
		up_frame.style.display="block";
		up_frame=att.appendChild(up_frame);		
		var btn_pos = findPos(document.getElementById("upload_path"));
		var pos_contenu = findPos(document.getElementById("contenu"));
		var w=getWindowWidth();
		var h=getWindowHeight();
		var wTop= document.documentElement.scrollTop;
		up_frame.style.width=Math.round(0.7*w)+'px';
		up_frame.style.height=Math.round(0.6*h)+'px';
		
		up_frame.style.left=pos_contenu[0]+'px';
		up_frame.style.top=wTop+'px';
	}
	up_frame.style.visibility="visible";	
	up_frame.style.display='block';
	document.onmousedown=clic;
	return false;
}

function copy_to_div(texte, id){
	var up_div = window.parent.document.getElementById("path");
	up_div.value = tab_libelle[texte];	
	
	if(window.parent.document.getElementById("id_rep") == null){
		var id_hidden = window.parent.document.createElement("input");
		id_hidden.setAttribute("name", "id_rep");
		id_hidden.setAttribute("id", "id_rep");
		id_hidden.setAttribute("type","hidden");
		id_hidden.setAttribute("value",id);
		up_div.appendChild(id_hidden);
	} else window.parent.document.getElementById("id_rep").value = id;
	
	up_killFrame();
}


function clic(e){
  	if (!e) var e=window.event;
	if (e.stopPropagation) {
		e.preventDefault();
		e.stopPropagation();
	} else { 
		e.cancelBubble=true;
		e.returnValue=false;
	}
  	up_killFrame();
  	document.onmousedown='';
}



//Mise en arriere plan de la frame
function up_hideFrame() {
	up_frame=document.getElementById('up_frame');
	if (up_frame!=null) {
		up_frame.style.visibility="hidden";
		up_frame.style.display='none';
	}
	return false;
}

//Destruction de la frame
function up_killFrame() {
	up_frame=window.parent.document.getElementById('up_frame');
	if (up_frame!=null) {
		up_frame.parentNode.removeChild(up_frame);
	}	
	return false;
}

//Fonction Ajax qui teste l'existence du fichier
function testing_file(id){
	var upl_check = document.getElementById('upload').checked;
	if(upl_check){
		
		var fichier = ( document.getElementById('f_fichier').value ? document.getElementById('f_fichier').value : document.getElementById('f_nom').value );
		
		var action = new http_request();
		var url = "./ajax.php?module=catalog&categ=explnum&id="+id+"&id_repertoire="+document.getElementById('id_rep').value+"&fichier="+fichier+"&quoifaire=exist_file";
		
		action.request(url);
		if(action.get_status() == 0){
			if(action.get_text() != "0"){
				return ecraser_fichier(action.get_text());
			}
		} 
	} 
	return true;
}
