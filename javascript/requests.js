// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: requests.js,v 1.5 2009-06-25 16:33:15 dbellamy Exp $

//Nécessite select.js


//Changement du type de requete
function req_typeChg() {

	var req_type=document.getElementById('req_type');
	var req_code=document.getElementById('req_code');
	var req_univ1=document.getElementById('req_univ1');
	var req_univ2=document.getElementById('req_univ2');

	req_code.value='';
	req_killFrame();
	if (req_type.value=='1') {
		req_univ1.style.display='none';
		req_univ2.style.display='none';
		//req_code.removeAttribute('disabled');
	} else {
		req_univ1.style.display='block';
		req_univ2.style.display='block';
		//req_code.setAttribute('disabled','disabled');
	}
	return false;			
}

//Changement de l'univers
function req_univChg() {
	var req_frame=document.getElementById('req_frame');
	var req_code=document.getElementById('req_code');
	req_code.value='';
	if (req_frame!=null) {
		req_killFrame();
	}
	return false;
}

//Ouverture de la frame a partir du parent
function req_openFrame(e) {

	var req_frame=document.getElementById('req_frame');
	if (req_frame==null) {
		if(!e) e=window.event;			
		e.cancelBubble = true;
		if (e.stopPropagation) e.stopPropagation();			
		req_frame=document.createElement("iframe");		
		req_frame.setAttribute('id','req_frame');
		req_frame.setAttribute('name','req_frame');
		var req_type=document.getElementById('req_type').value;
		var req_univ=document.getElementById('req_univ').value;
		req_frame.src="./admin/proc/req_frame.php?action=add&req_type="+req_type+"&req_univ="+req_univ; 			
		var att=document.getElementById("att");	
		req_frame.style.visibility="hidden";
		req_frame.style.display="block";
		req_frame=att.appendChild(req_frame);		
		var w=getWindowWidth();
		var fw=w-60;
		var h=getWindowHeight();
		var fh=h-30;
		req_frame.style.width=fw+'px';
		req_frame.style.height=fh+'px';
		var posx=(w-fw)/2;
		var posy=(h-fh)/2;
		req_frame.style.left=posx+'px';
		req_frame.style.top=posy+'px';
	}
	req_frame.style.visibility="visible";	
	req_frame.style.display='block';	
	return false;
}

//Mise en arriere plan de la frame
function req_hideFrame() {
	var req_frame=document.getElementById('req_frame');
	if (req_frame!=null) {
		req_frame.style.visibility="hidden";
		req_frame.style.display='none';
	}
	return false;
}

//Destruction de la frame
function req_killFrame() {
	var req_frame=document.getElementById('req_frame');
	if (req_frame!=null) {
		req_frame.parentNode.removeChild(req_frame);
	}	
	return false;
}
