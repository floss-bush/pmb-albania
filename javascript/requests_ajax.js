// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: requests_ajax.js,v 1.2 2008-03-19 11:48:25 dbellamy Exp $

requete = new Array();
line=new Array();
not_show=new Array();
last_word=new Array();
m_length=new Array();

function ajax_pack_element(elt) {
	com = elt.getAttribute('completion');
	switch (com) {
		case null:
			alert ('No completion attribute');
			break;
		case 'req_fiel' :
			set_default_container(elt);
			set_default_listener(elt);
			set_requests_constraints(elt);
			break;
		default :
			if (((elt.getAttribute("type")=='text')||(elt.nodeName=="TEXTAREA"))&&(elt.getAttribute("id"))) {
				//Insertion d'un div containeur
				set_default_container(elt);
				set_default_listener(elt);
				set_default_constraints(elt);
			}
			break;
	}
}

function set_default_container(elt){
	id=elt.getAttribute("id");
	w=elt.clientWidth;
	d1=document.createElement("div");
	d1.setAttribute("id","d"+id);
	d1.style.width=w+"px";
	d1.className='req_ajax_div';
	d1.style.display='none';
	document.getElementById('att').appendChild(d1);	
}

function set_default_listener(elt){
	if (elt.addEventListener) {
		elt.addEventListener("keyup",function(e) { ajax_update_info(e,'up'); },false);
		elt.addEventListener("keypress",function(e) { ajax_update_info(e,'press'); },false);
		elt.addEventListener("blur",function(e) { ajax_hide_list(e); },false);
	} else if (elt.attachEvent) {
		elt.attachEvent("onkeyup",function() { ajax_update_info(window.event,'up'); });
		elt.attachEvent("onpress",function() { ajax_update_info(window.event,'press'); });
		elt.attachEvent("onblur",function() { ajax_hide_list(window.event); });
	}
}

function set_default_constraints(elt){
	id=elt.getAttribute("id");
	requete[id]='';
	line[id]=0;
	not_show[id]=true;
	last_word[id]='';
	m_length[id]=1;	
}

function set_requests_constraints(elt){
	id=elt.getAttribute("id");
	requete[id]='';
	line[id]=0;
	not_show[id]=true;
	last_word[id]='';
	m_length[id]=0;	
}

function isFirefox1() {
	if(navigator.userAgent.indexOf("Firefox")!=-1){
		var versionindex=navigator.userAgent.indexOf("Firefox")+8
		if (parseInt(navigator.userAgent.substr(versionindex))>1) {
			if (navigator.userAgent.substr(versionindex,7)=="2.0.0.1") 
				return true;
			else
				return false;
		} else return true;
	} else return true;
}

function findPos(obj) {
	var curleft = curtop = 0
	if (obj.offsetParent) {
		curleft = obj.offsetLeft
		curtop = obj.offsetTop
		while (obj = obj.offsetParent) {
				curleft += obj.offsetLeft;
				curtop += obj.offsetTop;
		}
	}
	return [curleft,curtop];
}

function ajax_parse_dom() {
	var inputs=document.getElementsByTagName("input");
	for (i=0; i<inputs.length; i++) {
		ajax_pack_element(inputs[i]);
	}
	var textareas=document.getElementsByTagName("textarea");
	for (i=0; i<textareas.length; i++) {
		ajax_pack_element(textareas[i]);
	}
}

function ajax_hide_list(e,ac) {
	if (e.target) var id=e.target.getAttribute("id"); else var id=e.srcElement.getAttribute("id");
	setTimeout("if(document.getElementById('"+id+"')){ document.getElementById('d"+id+"').style.display='none';} not_show['"+id+"']=true;",500);
}

function ajax_set_datas(sp_name,id) {
	var sp=document.getElementById(sp_name);
	var text=sp.firstChild.nodeValue;
	var autfield=document.getElementById(id).getAttribute("autfield");
	if (autfield) document.getElementById(autfield).value=sp.getAttribute("autid");
	document.getElementById(id).value=text;
	document.getElementById(id).focus();
	document.getElementById("d"+id).style.display='none';
	not_show[id]=true;
}

function ajax_update_info(e,code) {
	if (e.target) var id=e.target.getAttribute("id"); else var id=e.srcElement.getAttribute("id");
	switch (e.keyCode) {
		case 27:	//ESC
			if (document.getElementById("d"+id).style.display=="block") {
				document.getElementById("d"+id).style.display='none';
				not_show[id]=true;
				e.cancelBubble = true;
				if (e.stopPropagation) { e.stopPropagation(); }
			}
			break;
		case 40:	//ARROW DOWN
			if ((code=="up")&&(e.target)&&(isFirefox1())) {
				if ((document.getElementById("d"+id).style.display=="none")&&(document.getElementById(id).value.length>=m_length[id])) {
					p=document.getElementById(id);
					poss=findPos(p);
					poss[1]+=p.clientHeight;
					document.getElementById("d"+id).style.left=poss[0]+"px";
					document.getElementById("d"+id).style.top=poss[1]+"px";
					document.getElementById("d"+id).style.display='block';
					not_show[id]=false;
					ajax_creerRequete(id);
					if (requete[id]) {
						last_word[id]=document.getElementById(id).value;
						ajax_get_info(id);
					}
					e.cancelBubble = true;
					if (e.stopPropagation) e.stopPropagation();
				}
				break;
			}
			next_line=line[id]+1;
			if (document.getElementById("d"+id).style.display=="block") {
				if (document.getElementById("l"+id+"_"+next_line)==null) break;
				old_line=line[id];
				line[id]++;
				sp=document.getElementById("l"+id+"_"+line[id]);
				sp.style.background='#000088';
				sp.style.color='#FFFFFF';
				p=document.getElementById(id);
				poss=findPos(p);
				poss[1]+=p.clientHeight;
				sp.style.left=poss[0]+"px";
				sp.style.top=poss[1]+"px";
				if (old_line) {
					sp_old=document.getElementById("l"+id+"_"+old_line);
					sp_old.style.background='';
					sp_old.style.color='#000000';
					sp.parentNode.scrollTop=sp_old.offsetTop;
				}
			} else {
				if ((document.getElementById("d"+id).style.display=="none")&&(document.getElementById(id).value.length>=m_length[id])) {
					p=document.getElementById(id);
					poss=findPos(p);
					poss[1]+=p.clientHeight;
					document.getElementById("d"+id).style.left=poss[0]+"px";
					document.getElementById("d"+id).style.top=poss[1]+"px";
					document.getElementById("d"+id).style.display='block';
					not_show[id]=false;
					ajax_creerRequete(id);
					if (requete[id]) {
						last_word[id]=document.getElementById(id).value;
						ajax_get_info(id);
					}
					e.cancelBubble = true;
					if (e.stopPropagation) e.stopPropagation();
				}
			}
			
			break;
		case 38:	//ARROW UP
			if ((code=="up")&&(e.target)&&(isFirefox1())) break;
			if (document.getElementById("d"+id).style.display=="block") {
				old_line=line[id];
				if (line[id]>0) line[id]--;
				if (line[id]>0) {
					sp=document.getElementById("l"+id+"_"+line[id]);
					sp.style.background='#000088';
					sp.style.color='#FFFFFF';
				}
				if (old_line) {
					sp_old=document.getElementById("l"+id+"_"+old_line);
					sp_old.style.background='';
					sp_old.style.color='#000000';
					try{
						sp.parentNode.scrollTop=sp.offsetTop;
					}catch(err){}
				}
			}
			break;
		case 9:		//TAB
			document.getElementById("d"+id).style.display='none';
			not_show[id]=true;
			break;
		case 13:	//ENTER
			if (code=="press") break;
			if ((line[id])&&(document.getElementById("d"+id).style.display=="block")) {
				var sp=document.getElementById("l"+id+"_"+line[id]);
				var text=sp.firstChild.nodeValue;
				var autfield=document.getElementById(id).getAttribute("autfield");
				if (autfield) {
					var autid=sp.getAttribute("autid");
					document.getElementById(autfield).value=autid;
				}
				document.getElementById(id).value=text;
				document.getElementById("d"+id).style.display='none';
				not_show[id]=true;
			}
			e.cancelBubble = true;
			if (e.stopPropagation) e.stopPropagation();
			break;
		case 113:	//F2
			if ((document.getElementById("d"+id).style.display=="none")&&(document.getElementById(id).value.length>=m_length[id])) {
				p=document.getElementById(id);
				poss=findPos(p);
				poss[1]+=p.clientHeight;
				document.getElementById("d"+id).style.left=poss[0]+"px";
				document.getElementById("d"+id).style.top=poss[1]+"px";
				document.getElementById("d"+id).style.display='block';
				not_show[id]=false;
				ajax_creerRequete(id);
				if (requete[id]) {
					last_word[id]=document.getElementById(id).value;
					ajax_get_info(id);
				}
				e.cancelBubble = true;
				if (e.stopPropagation) e.stopPropagation();
			}
			break;
		default:
			if ((last_word[id]==document.getElementById(id).value)&&(last_word[id])) break;
			if ((document.getElementById(id).value!="")&&(!not_show[id])) {
				ajax_creerRequete(id);
				if (requete[id]) {
					last_word[id]=document.getElementById(id).value;
					ajax_get_info(id);
				}
			} else {
				document.getElementById("d"+id).style.display='none';
				if (document.getElementById(id).value=="") not_show[id]=true;
			}
			last_word[id]=document.getElementById(id).value;
			break;
	}
}

function ajax_creerRequete(id) {
	
	try {
		requete[id]=new XMLHttpRequest();
	} catch (essaimicrosoft) {
		try {
			requete[id]=new ActiveXObject("Msxml2.XMLHTTP");
		} catch (autremicrosoft) {
			try {
				requete[id]=new ActiveXObject("Microsoft.XMLHTTP");
			} catch (echec) {
				requete[id]=null;
			}
		}
	}
}

function ajax_show_info(id) {
	if (requete[id].readyState==4) {
		if (requete[id].status=="200") {
			cadre=document.getElementById("d"+id);
			cadre.innerHTML=requete[id].responseText;
			line[id]=0;
			if (requete[id].responseText=="") {
				document.getElementById("d"+id).style.display='none';
			} else {
				p=document.getElementById(id);
				poss=findPos(p);
				poss[1]+=p.clientHeight+1;
				document.getElementById("d"+id).style.left=poss[0]+"px";
				document.getElementById("d"+id).style.top=poss[1]+"px";
				document.getElementById("d"+id).style.display='block';
			}
		} //else alert("Erreur : le serveur a répondu "+requete.responseText);
	}
}

function ajax_get_info(id) {
	
	elt=document.getElementById(id);
	com=elt.getAttribute('completion');
	datas="datas="+escape(elt.value)+"&id="+escape(id)+"&completion="+escape(com);
	if (elt.getAttribute("autfield")) {
		autfield = elt.getAttribute("autfield") ;
		datas+= "&autfield="+escape(autfield);
	} 
	if (elt.getAttribute("autexclude")) {
		autexclude = elt.getAttribute("autexclude") ;
		datas+= "&autexclude="+escape(autexclude);
	}
	if (elt.getAttribute("linkfield")) {
		linkfield = document.getElementById(elt.getAttribute("linkfield")).value ;
		datas+= "&linkfield="+escape(linkfield);
	}
	if (elt.getAttribute("typdoc")) {
		typdoc = document.getElementById(elt.getAttribute("typdoc")).value ;
		datas+= "&typdoc="+escape(typdoc);
	}

	switch (com) {
		case 'req_fiel' :
			if (document.getElementById('req_univ')) {
				req_univ = document.getElementById('req_univ').value ;
				datas+= "&req_univ="+escape(req_univ);
			}
			requete[id].open("POST","requests_selector.php",true);
			requete[id].onreadystatechange=function() { ajax_show_info(id) };
			break;			
		default :
			requete[id].open("POST","ajax_selector.php",true);
			requete[id].onreadystatechange=function() { ajax_show_info(id) };
			break;
	}
	requete[id].setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	requete[id].send(datas);
			
}
