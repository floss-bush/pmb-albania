// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: ajax.js,v 1.9 2009-10-14 12:13:55 kantin Exp $

requete=new Array();
line=new Array();
not_show=new Array();
last_word=new Array();
ids=new Array();
dontblur=false;

function isFirefox1() {
	if(navigator.userAgent.indexOf("Firefox")!=-1){
		var versionindex=navigator.userAgent.indexOf("Firefox")+8
		if (parseInt(navigator.userAgent.substr(versionindex))>1) {
			if (parseInt(navigator.userAgent.substr(versionindex))==2) {
				if (navigator.userAgent.substr(versionindex,7)=="2.0.0.2") 
					return false;
				else
					return true;
			} else return true;
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

function show_simulate(id) {
	p=document.getElementById(id);
	poss=findPos(p);
	poss[1]+=p.clientHeight;
	document.getElementById('d'+id).style.left=poss[0]+'px';
	document.getElementById('d'+id).style.top=poss[1]+'px';
	document.getElementById('d'+id).style.display='block';
	not_show[id]=false;
	ajax_creerRequete(id);
	if (requete[id]) {
		last_word[id]=document.getElementById(id).value;
		ajax_get_info(id);
	}
}

function simulate_event(id) {
	if (document.getElementById("d"+id).style.display=="none") {
		if (document.getElementById(id).value=="") {
			document.getElementById(id).value="*";
		}
		setTimeout("show_simulate('"+id+"')",400);		
	}
}


function ajax_pack_element(inputs) {
	var id="";
	n=ids.length;
	if (inputs.getAttribute("completion")) {
		if (((inputs.getAttribute("type")=="text")||(inputs.nodeName=="TEXTAREA"))&&(inputs.getAttribute("id"))) {
			ids[n]=inputs.getAttribute("id");
			id=ids[n];
			//Insertion d'un div parent
			w=inputs.clientWidth;
			d=document.createElement("span");
			d.style.width=w+"px";
			p=inputs.parentNode;
			var input=inputs;
			p.replaceChild(d,inputs);
			d.appendChild(input);
			d1=document.createElement("div");
			d1.setAttribute("id","d"+id);
			d1.style.width=w+"px";
			d1.style.border="1px #000 solid";
			d1.style.left="0px";
			d1.style.top="0px";
			d1.style.display="none";
			d1.style.position="absolute";
			d1.style.backgroundColor="#FFFFFF";
			d1.style.zIndex=1000;
			document.getElementById('att').appendChild(d1);
			if (input.addEventListener) {
				input.addEventListener("keyup",function(e) { ajax_update_info(e,'up',id); },false);
				input.addEventListener("keypress",function(e) { ajax_update_info(e,'press',id); },false);
				input.addEventListener("blur",function(e) { ajax_hide_list(e); },false);
			} else if (input.attachEvent) {
				input.attachEvent("onkeyup",function() { ajax_update_info(window.event,'up',id); });
				input.attachEvent("onpress",function() { ajax_update_info(window.event,'press',id); });
				input.attachEvent("onblur",function() { ajax_hide_list(window.event); });
			}
		}
	}
	requete[id]="";
	line[id]=0;
	not_show[id]=true;
	last_word[id]="";	
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

function ajax_hide_list(e) {
	if (!dontblur) {
		if (e.target) var id=e.target.getAttribute("id"); else var id=e.srcElement.getAttribute("id");
		setTimeout("document.getElementById('d"+id+"').style.display='none'; not_show['"+id+"']=true;",500);
	} else dontblur=false;
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
	if (e.target)
	{
		var id=e.target.getAttribute("id");
	}
	else{
		var id=e.srcElement.getAttribute("id");
	}	
	switch (e.keyCode) {
		case 27:
			if (document.getElementById("d"+id).style.display=="block") {
				document.getElementById("d"+id).style.display='none';
				not_show[id]=true;
				e.cancelBubble = true;
				if (e.stopPropagation) { e.stopPropagation(); }
			}
			break;
		case 40:	
		if(document.getElementById(id).value=="")	document.getElementById(id).value="*";
			if ((code=="up")&&(e.target)&&(isFirefox1())) {
				if ((document.getElementById("d"+id).style.display=="none")&&(document.getElementById(id).value!="")) {
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
				if (old_line) {
					sp_old=document.getElementById("l"+id+"_"+old_line);
					sp_old.style.background='';
					sp_old.style.color='#000000';
				}
			} else {
				if ((document.getElementById("d"+id).style.display=="none")&&(document.getElementById(id).value!="")) {
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
		case 38:
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
				}
			}
			break;
		case 9:
			document.getElementById("d"+id).style.display='none';
			not_show[id]=true;
			break;
		case 13:
			if (code=="press") break;
			if ((line[id])&&(document.getElementById("d"+id).style.display=="block")) {
				var sp=document.getElementById("l"+id+"_"+line[id]);
				var text=sp.firstChild.nodeValue;
				var autfield=document.getElementById(id).getAttribute("autfield");
				var div_cache=document.getElementById("c"+id+"_"+line[id]);
				
				if (autfield) {
					var autid=sp.getAttribute("autid");
					document.getElementById(autfield).value=autid;
				}
				if(div_cache){
					document.getElementById(id).value=div_cache.firstChild.nodeValue;
				} else {
					document.getElementById(id).value=text;
				}
				document.getElementById("d"+id).style.display='none';
				not_show[id]=true;
			}
			e.cancelBubble = true;
			if (e.stopPropagation){
				e.stopPropagation();
			}
			break;
		case 113:
			if ((document.getElementById("d"+id).style.display=="none")&&(document.getElementById(id).value!="")) {
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
				poss[1]+=p.clientHeight;
				document.getElementById("d"+id).style.left=poss[0]+"px";
				document.getElementById("d"+id).style.top=poss[1]+"px";
				document.getElementById("d"+id).style.display='block';
			}
		} else alert("Erreur : le serveur a répondu "+requete.responseText);
	}
}

function ajax_get_info(id) {
	var autexclude = '' ;
	var autfield = '' ;
	var linkfield = '' ;
	
	requete[id].open("POST","ajax_selector.php",true);
	requete[id].onreadystatechange=function() { ajax_show_info(id) };
	requete[id].setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	
	if (document.getElementById(id).getAttribute("autexclude")) autexclude = document.getElementById(id).getAttribute("autexclude") ;
	if (document.getElementById(id).getAttribute("linkfield")) linkfield = document.getElementById(document.getElementById(id).getAttribute("linkfield")).value ;
	if (document.getElementById(id).getAttribute("autfield")) autfield = document.getElementById(id).getAttribute("autfield") ;
		
	requete[id].send("datas="+escape(document.getElementById(id).value)+"&id="+escape(id)+"&completion="+escape(document.getElementById(id).getAttribute("completion"))+"&autfield="+escape(autfield)+"&autexclude="+escape(autexclude)+"&linkfield="+escape(linkfield));
}
