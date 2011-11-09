// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: move.js,v 1.9.2.1 2011-05-27 08:34:50 arenou Exp $

down=false;
down_parent=false;
child_move="";
decx=0;
decy=0;
posx=0;
posy=0;
pheight=0;
parent_move="";
parent_min=6;
parent_last_h=0;
relative=true;
formatpage="";
inedit=false;

function move(e) {
	e.cancelBubble = true;
	if (e.stopPropagation) e.stopPropagation();
	if (e.currentTarget.getAttribute("id")==child_move) {
		if (down==true) {
			var zx=posx*1+(e.screenX-decx)*1;
			if (zx<0) zx=0;
			var px=e.currentTarget.offsetParent.clientWidth;
			if (zx+e.currentTarget.clientWidth>px) zx=px-e.currentTarget.clientWidth;
			zx=zx+"px";
			var zy=posy*1+(e.screenY-decy)*1;
			if (zy<0) zy=0;
			var py=e.currentTarget.offsetParent.clientHeight;
			if (zy+e.currentTarget.clientHeight>py-6) {
				var nheight=zy+e.currentTarget.clientHeight+6;
				e.currentTarget.offsetParent.style.height=nheight+"px";
			}
			zy=zy+"px";
			e.currentTarget.style.left=zx;
			e.currentTarget.style.top=zy;
		}
	}
}

function move_parent(e) {
	if (!parent_move) parent_move="";
	var bord=e.currentTarget.clientHeight;
	if ((e.layerY>=bord-2)&&(e.layerY<=bord+2)) {
		e.currentTarget.style.cursor="n-resize";
	} else e.currentTarget.style.cursor="";
	if (e.currentTarget.getAttribute("id")==parent_move) {
		if (down_parent==true) {
			var nheight=pheight*1+(e.screenY-decy)*1;
			if ((nheight>parent_min)&&(nheight<parent_last_h)) {
				e.target.style.height=nheight+"px";
				parent_last_h=nheight;
			}
		}
	} else {
		down_parent=false;
		parent_move="";
	}
}

function malert(message) {
	me=document.getElementById("message");
	me.firstChild.nodeValue=message;
}

function change_onglet(onglet,champ) {
	var relp=relative;
	champ=document.getElementById(champ);
	onglet=document.getElementById(onglet);
	onglet.appendChild(champ);
	if (onglet.getAttribute("invert")=="yes") relp=(!relative); else relp=relative;
	if (!relp) {
		champ.style.position="absolute";
		champ.style.border="#999 2px solid";
		champ.style.background="#DDD"; 
	} else {
		champ.style.position="";
		champ.style.border="";
		champ.style.background=""; 
	}
	if (!relp) resize_onglet(onglet,true);
}

function resize_onglet(onglet,force) {
		var i=0;
		if ((!onglet.style.height)||(onglet.style.height=="0px")||(force)) {
			//Recherche de la hauteur maximale
			var childs=onglet.childNodes;
			var max=0;
			for (i=0; i<childs.length; i++) {
				var fromTop=childs[i].offsetTop;
				var cheight=childs[i].clientHeight;
				if (fromTop*1+cheight*1>max) max=fromTop*1+cheight*1;
			}
			var nheight=max*1+6*1
			onglet.style.height=nheight+"px";
		}
}

function place_fields(onglet) {
	//placement de tous les champs
	var j=0;
	var py=0;
	var childs=onglet.childNodes;
	for (j=0; j<childs.length; j++) {
		if (childs[j].nodeType==1) {
			if (childs[j].getAttribute("movable")=="yes") {
				childs[j].style.top=py+"px";
				py+=childs[j].clientHeight;
			}
		}
	}
}

function go_before(child_name) {
	child=document.getElementById(child_name);
	var childs=child.parentNode.childNodes;
	for (i=0; i<childs.length; i++) {
		if (childs[i].nodeType==1) {
			if (childs[i].getAttribute("id")==child_name) {
				break;
			}
		}
	}
	if (i<childs.length) {
		//recherche du suivant
		for (i=i+1;i<childs.length;i++) {
			if (childs[i].nodeType==1) {
				if (childs[i].getAttribute("movable")=="yes") {
					break;
				}
			}
		} 
		if (i<childs.length) {
			swap=child.parentNode.replaceChild(child,childs[i]);
			child.parentNode.insertBefore(swap,child);
		}
	}
	if (document.getElementById("popup_onglet")) document.getElementById("popup_onglet").parentNode.removeChild(document.getElementById("popup_onglet"));
}

function go_after(child_name) {
	child=document.getElementById(child_name);
	var childs=child.parentNode.childNodes;
	for (i=0; i<childs.length; i++) {
		if (childs[i].nodeType==1) {
			if (childs[i].getAttribute("id")==child_name) {
				break;
			}
		}
	}
	if ((i<childs.length)&&(i>0)) {
		//recherche du précédent
		for (i=i-1;i>=0;i--) {
			if (childs[i].nodeType==1) {
				if (childs[i].getAttribute("movable")=="yes") {
					break;
				}
			}
		} 
		if (i>=0) {
			child_i=childs[i];
			swap=child.parentNode.replaceChild(child_i,child);
			child_i.parentNode.insertBefore(swap,child_i);
		}
	}
	if (document.getElementById("popup_onglet")) document.getElementById("popup_onglet").parentNode.removeChild(document.getElementById("popup_onglet"));
}

function get_onglet_title(onglet) {
	var stop=false;
	var previous=onglet;
	while (!stop) {
		previous=previous.previousSibling;
		if (previous) {
			if (previous.nodeType==1) stop=true; 
		} else stop=true;
	}
	return previous;
}

function descendre_onglet(onglet_name) {
	child=document.getElementById(onglet_name);
	child_title=get_onglet_title(child);

	var childs=child.parentNode.childNodes;
	for (i=0; i<childs.length; i++) {
		if (childs[i].nodeType==1) {
			if (childs[i].getAttribute("id")==onglet_name) {
				break;
			}
		}
	}
	if (i<childs.length) {
		//recherche du suivant
		for (i=i+1;i<childs.length;i++) {
			if (childs[i].nodeType==1) {
				if (childs[i].getAttribute("etirable")=="yes") {
					break;
				}
			}
		} 
		if (i<childs.length) {
			//Recherche du titre de l'onglet
			new_title=get_onglet_title(childs[i]);
			swap=child.parentNode.replaceChild(child,childs[i]);
			swap_title=child_title.parentNode.replaceChild(child_title,new_title);
			child.parentNode.insertBefore(swap,child_title);
			child.parentNode.insertBefore(swap_title,swap);
		}
	}
	if (document.getElementById("popup_onglet")) document.getElementById("popup_onglet").parentNode.removeChild(document.getElementById("popup_onglet"));
}

function monter_onglet(onglet_name) {
	onglet=document.getElementById(onglet_name);
	onglet_title=get_onglet_title(onglet);
	var childs=onglet.parentNode.childNodes;
	for (i=0; i<childs.length; i++) {
		if (childs[i].nodeType==1) {
			if (childs[i].getAttribute("id")==onglet_name) {
				break;
			}
		}
	}
	if ((i<childs.length)&&(i>0)) {
		//recherche du précédent
		for (i=i-1;i>=0;i--) {
			if (childs[i].nodeType==1) {
				if (childs[i].getAttribute("etirable")=="yes") {
					break;
				}
			}
		} 
		if (i>=0) {
			child_i=childs[i];
			child_i_title=get_onglet_title(child_i);
			child_i.parentNode.insertBefore(onglet,child_i_title);
			child_i.parentNode.insertBefore(onglet_title,onglet);
		}
	}
	if (document.getElementById("popup_onglet")) document.getElementById("popup_onglet").parentNode.removeChild(document.getElementById("popup_onglet"));
}

function go_first(child_name) {
	child=document.getElementById(child_name);
	child.parentNode.appendChild(child);
	if (document.getElementById("popup_onglet")) document.getElementById("popup_onglet").parentNode.removeChild(document.getElementById("popup_onglet"));
}

function go_last(child_name) {
	child=document.getElementById(child_name);
	var childs=child.parentNode.childNodes;
	//Recherche du premier movable !!
	for (i=0;i<childs.length;i++) {
		if (childs[i].nodeType==1) {
			if (childs[i].getAttribute("movable")=="yes") {
				break;
			}
		}
	} 
	if (i<childs.length) {
		child.parentNode.insertBefore(child,childs[i]);
	}
	if (document.getElementById("popup_onglet")) document.getElementById("popup_onglet").parentNode.removeChild(document.getElementById("popup_onglet"));
}

function invisible(child_name) {
	child=document.getElementById(child_name);
	child.style.display="none";
	child.setAttribute("hide","yes");
	if (document.getElementById("popup_onglet")) document.getElementById("popup_onglet").parentNode.removeChild(document.getElementById("popup_onglet"));
}

function invisible_onglet(onglet_name) {
	child=document.getElementById(onglet_name);
	child.style.display="none";
	child.setAttribute("hide","yes");
	var stop=false;
	var previous=child;
	while (!stop) {
		previous=previous.previousSibling;
		if (previous) {
			if (previous.nodeType==1) { previous.style.display="none"; stop=true; }
		} else stop=true;
	}
	if (document.getElementById("popup_onglet")) document.getElementById("popup_onglet").parentNode.removeChild(document.getElementById("popup_onglet"));
}

function visible(child_name) {
	child=document.getElementById(child_name);
	child.style.display="";
	child.setAttribute("hide","");
}

function visible_onglet(onglet_name) {
	child=document.getElementById(onglet_name);
	child.style.display="";
	child.setAttribute("hide","");
	var stop=false;
	var previous=child;
	while (!stop) {
		previous=previous.previousSibling;
		if (previous) {
			if (previous.nodeType==1) { previous.style.display=""; stop=true; }
		} else stop=true;
	}
	place_fields(child)
	resize_onglet(child,true);
}

function save_all(e) {
	var xml="<formpage relative='"+(relative?"yes":"no")+"'>\n";
	var etn=0;
	var grille_typdoc='a';
	var grille_niveau_biblio='m';
	var grille_loc=0;
	var relp=relative;
	
	if (document.getElementById("popup_onglet")) document.getElementById("popup_onglet").parentNode.removeChild(document.getElementById("popup_onglet"));
	etirables=document.getElementsByTagName("div");
	var i=0;
	for (i=0; i<etirables.length; i++) {
		if (etirables[i].getAttribute("etirable")=="yes") {
			if (etirables[i].getAttribute("invert")=="yes") relp=(!relative); else relp=relative;
			etn++;
			xml+="  <etirable id='"+etirables[i].getAttribute("id")+"' visible='"+(etirables[i].getAttribute("hide")=="yes"?"no":"yes")+"' "+(etirables[i].getAttribute("invert")=="yes"?"invert='yes' ":"")+" order='"+etn+"' ";
			if (!relp) xml+=" width='"+etirables[i].clientWidth+"' height='"+etirables[i].clientHeight+"'";
			xml+="/>\n";
		}
	}
	for (i=0; i<etirables.length; i++) {
		if (etirables[i].getAttribute("movable")=="yes") {
			if (etirables[i].parentNode.getAttribute("invert")=="yes") relp=(!relative); else relp=relative;
			xml+="  <movable id='"+etirables[i].getAttribute("id")+"' visible='"+(etirables[i].style.display=="none"?"no":"yes")+"' parent='"+etirables[i].parentNode.getAttribute("id")+"'";
			if (!relp) xml+=" left='"+etirables[i].offsetLeft+"' top='"+etirables[i].offsetTop+"'";
			xml+="/>\n";
		}
	}
	xml+="</formpage>";
	ajax_creerRequete("sauve_notice");
	requete["sauve_notice"].open("POST","save_notice_pos.php",true);
	requete["sauve_notice"].onreadystatechange=function() { move_saved() };
	requete["sauve_notice"].setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	grille_typdoc=document.notice.typdoc.value;
	grille_niveau_biblio=document.notice.b_level.value;
	if (document.notice.grille_location) grille_loc=document.notice.grille_location.value;
	requete["sauve_notice"].send("datas="+escape(xml)+"&grille_typdoc="+escape(grille_typdoc)+"&grille_niveau_biblio="+escape(grille_niveau_biblio)+"&grille_location="+escape(grille_loc));
}

function move_fields(domXML) {
	root=domXML.getElementsByTagName("formpage");
	relative=root[0].getAttribute("relative");
	if (relative=="yes") relative=true; else relative=false;
	
	var relp=relative;
	
	var etirables=domXML.getElementsByTagName("etirable");
	var parent_onglet=document.getElementById(etirables[0].getAttribute("id")).parentNode;
	var onglet=new Array();
	var onglet_titre=Array();
	var fields= new Array();
	var id=0;
	
	for (i=0; i<etirables.length; i++) {
		//Onglets flottants
		id=etirables[i].getAttribute("id");
		//on regénère le dom des textarea, le navigateur se contente d'affecter la propriété value... 
		var text_areas = document.getElementById(id).getElementsByTagName('textarea');
		for(var x=0 ; x<text_areas.length ; x++){
			if(!text_areas[x].firstChild){
				text_areas[x].appendChild(document.createTextNode(text_areas[x].value));
			}
		}
		//on regénère le dom des select, le navigateur se contente d'affecter la propriété selected sans recréer l'attribut... 
		var selects = document.getElementById(id).getElementsByTagName('select');
		for(var x=0 ; x<selects.length ; x++){
			for(var y=0 ; y<selects[x].options.length ; y++){
				if(selects[x].options[y].selected){
					selects[x].options[y].setAttribute('selected','selected');
				}
			}
		}
		onglet[i]=document.getElementById(id).cloneNode(true);
		if (etirables[i].getAttribute("invert")=="yes") onglet[i].setAttribute("invert","yes"); else onglet[i].setAttribute("invert","");
		var onglet_tit=get_onglet_title(document.getElementById(id));
		onglet_titre[i]=onglet_tit.cloneNode(true);
		parent_onglet.removeChild(document.getElementById(id));
		parent_onglet.removeChild(onglet_tit);
	}
	for (i=0; i<etirables.length; i++) {
		//Remise en ordre
		parent_onglet.appendChild(onglet_titre[i]);
		parent_onglet.appendChild(onglet[i]);
		if (onglet[i].getAttribute("invert")=="yes") 
			relp=(!relative)
		else relp=relative;
		onglet[i].style.position=relp?"":"relative";
		if (!relp) onglet[i].style.height=etirables[i].getAttribute("height")+"px"; else onglet[i].style.height="";
		if (etirables[i].getAttribute("visible")=="no") {
			onglet_titre[i].style.display="none";
			onglet[i].style.display="none";
			onglet[i].setAttribute("hide","yes");
		} else {
			onglet_titre[i].style.display="block";
			onglet[i].style.display="block";
			onglet[i].setAttribute("hide","");
		}
	}
	
	var movables=domXML.getElementsByTagName("movable");

	for (i=0; i<movables.length; i++) {
		id=movables[i].getAttribute("id");
		var parent_id=movables[i].getAttribute("parent");
		var mov=document.getElementById(id);
		if (mov != null) {
			var new_mov=mov.cloneNode(true);
			mov.parentNode.removeChild(mov);
			document.getElementById(parent_id).appendChild(new_mov);
			//Positionnement en fonction de relative
			if (document.getElementById(parent_id).getAttribute("invert")=="yes") 
				relp=(!relative) 
			else relp=relative;
			new_mov.style.position=relp?"":"absolute";
			if (!relp) {
				new_mov.style.left=movables[i].getAttribute("left")+"px";
				new_mov.style.top=movables[i].getAttribute("top")+"px";
			} else {
				new_mov.style.left="";
				new_mov.style.top="";
			} 
			if (movables[i].getAttribute("visible")=="no") {
				new_mov.style.display="none";
			} else {
				new_mov.style.display="block";
			}
		}
	}
	parent_onglet.style.visibility="visible";
}

function move_getted_pos() {
	if (requete["get_notice"].readyState==4) {
		if (requete["get_notice"].status=="200") {
			var formatpage=requete["get_notice"].responseXML;
			if (formatpage) {
				move_fields(formatpage);
			}
		} else formatpage=null;
	}
}

function get_pos() {
	var grille_typdoc='a';
	var grille_niveau_biblio='m';

	ajax_creerRequete("get_notice");
	requete["get_notice"].open("POST","load_notice_pos.php",false);
	requete["get_notice"].setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	grille_typdoc=document.notice.typdoc.value;
	grille_niveau_biblio=document.notice.b_level.value;
	requete["get_notice"].send("grille_typdoc="+escape(grille_typdoc)+"&grille_niveau_biblio="+escape(grille_niveau_biblio));
	move_getted_pos();
}

function get_default_pos() {
	var grille_typdoc='a';
	var grille_niveau_biblio='m';

	ajax_creerRequete("get_notice");
	requete["get_notice"].open("POST","load_notice_pos.php",false);
	requete["get_notice"].setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	grille_niveau_biblio=document.notice.b_level.value;
	requete["get_notice"].send("grille_typdoc=default&grille_niveau_biblio="+escape(grille_niveau_biblio));
	move_getted_pos();
}

function move_saved() {
	if (requete["sauve_notice"].readyState==4) {
		if (requete["sauve_notice"].status=="200") {
			if (requete["sauve_notice"].responseText=="OK") {
				alert("La sauvegarde est réalisée !");
			} else alert("Erreur : le serveur a répondu "+requete["sauve_notice"].responseText);
		} else alert("Erreur : le serveur a répondu "+requete["sauve_notice"].responseText);
	}
}

function invert_onglet(id) {
	var relp=relative;
	var onglet=document.getElementById(id);
	
	if (document.getElementById("popup_onglet")) document.getElementById("popup_onglet").parentNode.removeChild(document.getElementById("popup_onglet"));
	
	if (onglet.getAttribute("invert")=="yes") {
		onglet.setAttribute("invert","");
		relp=relative;
	} else {
		onglet.setAttribute("invert","yes");
		relp=(!relative);
	}
	
	if (relp) onglet.style.position=""; else onglet.style.position="relative";
	
	movables=document.getElementsByTagName("div");
	for(i=0; i<movables.length; i++) {
		if ((movables[i].getAttribute("movable")=="yes")&&(movables[i].parentNode.getAttribute("id")==id)) {			
			if (!relp) {
				movables[i].style.border="#999 2px solid";
				movables[i].style.background="#DDD";
			} else {
				movables[i].style.border="";
				movables[i].style.background="";
			}
			movables[i].style.position=relp?"":"absolute";
		}
	}
	if (!relp) resize_onglet(onglet,true); else onglet.style.height="";
}

function move_parse_dom(rel) {
	relative=rel;
	inedit=true;
	var i=0;
	//Rendre visible la liste des localisations
	if (document.getElementById("grille_location"))
		document.getElementById("grille_location").style.display="block";
	//Rendre invisible le bouton d'édition et visible le bouton de switch
	if (!relative) 
		document.getElementById("bt_swap_relative").value="Passer en positionnement RELATIF";
	else
		document.getElementById("bt_swap_relative").value="Passer en positionnement ABSOLU";
	document.getElementById("bt_swap_relative").style.display="";
	document.getElementById("bt_inedit").style.display="none";
	movables=document.getElementsByTagName("div");
	for(i=0; i<movables.length; i++) {
		if (movables[i].getAttribute("movable")=="yes") {
			if (movables[i].parentNode.getAttribute("invert")=="yes") relp=(!relative); else relp=relative;
			if (!relp) {
				movables[i].style.border="#999 2px solid";
				movables[i].style.background="#DDD";
			} else {
				movables[i].style.border="";
				movables[i].style.background="";
			}
			movables[i].style.position=relp?"":"absolute";
			movables[i].onmousedown=function(e) {
				e.cancelBubble = true;
				if (e.stopPropagation) e.stopPropagation();
				down=true;
				child_move=e.currentTarget.getAttribute("id");
				sx=document.getElementById("sx");
				sy=document.getElementById("sy");
				sid=document.getElementById("sid");
				posx=e.currentTarget.style.left;
				posy=e.currentTarget.style.top;
				if (posx.substr(-2,2)=="px") posx=posx.substr(0,posx.length-2);
				if (posy.substr(-2,2)=="px") posy=posy.substr(0,posy.length-2);
				decx=e.screenX;
				decy=e.screenY;
			}
			movables[i].onmousemove=move;
			movables[i].onmouseup=function(e) {
				e.cancelBubble = true;
				if (e.stopPropagation) e.stopPropagation();
				down=false;
			}
			movables[i].onmouseover=function(e) {
				e.currentTarget.style.cursor="pointer";
			}
			movables[i].onclick=function(e) {
				var i;
				var relp=relative;
				if (e.ctrlKey) {
					if (e.currentTarget.parentNode.getAttribute("invert")=="yes") relp=(!relative); else relp=relative;
					if (document.getElementById("popup_onglet")) document.getElementById("popup_onglet").parentNode.removeChild(document.getElementById("popup_onglet"));
					e.cancelBubble = true;
					if (e.stopPropagation) e.stopPropagation();
					popup=document.createElement("div");
					popup.setAttribute("id","popup_onglet");
					popup.style.border="#000 1px solid";
					popup.style.background="#EEE";
					popup.style.position="absolute";
					popup.style.zIndex=10;
					popup.style.left=e.pageX+"px";
					popup.style.top=e.pageY+"px";
					var etirables=document.getElementsByTagName("div");
					var textHtml="<div style='width:100%;background:#FFF;border-bottom:#000 2px solid;text-align:center'><b>"+(e.currentTarget.getAttribute("title")?e.currentTarget.getAttribute("title"):e.currentTarget.getAttribute("id"))+"</b></div>";
					textHtml+="<div onmouseover='this.style.background=\"#666\"; this.style.color=\"#FFF\";' onmouseout='this.style.background=\"#CCC\"; this.style.color=\"#000\";' style='width:100%;background:#CCC' onClick='go_last(\""+e.currentTarget.getAttribute("id")+"\")'>"+(!relp?"Passer au dernier plan":"Passer en premier")+"</div>";
					textHtml+="<div onmouseover='this.style.background=\"#666\"; this.style.color=\"#FFF\";' onmouseout='this.style.background=\"#CCC\"; this.style.color=\"#000\";' style='width:100%;background:#CCC' onClick='go_after(\""+e.currentTarget.getAttribute("id")+"\")'>"+(!relp?"Passer derrière":"Monter")+"</div>";
					textHtml+="<div onmouseover='this.style.background=\"#666\"; this.style.color=\"#FFF\";' onmouseout='this.style.background=\"#CCC\"; this.style.color=\"#000\";' style='width:100%;background:#CCC' onClick='go_before(\""+e.currentTarget.getAttribute("id")+"\")'>"+(!relp?"Passer devant":"Descendre")+"</div>";
					textHtml+="<div onmouseover='this.style.background=\"#666\"; this.style.color=\"#FFF\";' onmouseout='this.style.background=\"#CCC\"; this.style.color=\"#000\";' style='width:100%;background:#CCC' onClick='go_first(\""+e.currentTarget.getAttribute("id")+"\")'>"+(!relp?"Passer au premier plan":"Passer en dernier")+"</div>";
					textHtml+="<div onmouseover='this.style.background=\"#666\"; this.style.color=\"#FFF\";' onmouseout='this.style.background=\"#CCC\"; this.style.color=\"#000\";' style='width:100%;background:#CCC' onClick='invisible(\""+e.currentTarget.getAttribute("id")+"\")'>Rendre invisible</div>";
					var textHtml_visible="";
					for(i=0; i<etirables.length; i++) {
						if ((etirables[i].getAttribute("movable")=="yes")&&(etirables[i].style.display=="none")) {
							textHtml_visible+="<div onmouseover='this.style.background=\"#666\"; this.style.color=\"#FFF\";' onmouseout='this.style.background=\"#EEE\"; this.style.color=\"#000\";' style='width:100%' onclick='visible(\""+etirables[i].getAttribute("id")+"\"); this.parentNode.parentNode.removeChild(this.parentNode);'>&nbsp;&nbsp;"+(etirables[i].getAttribute("title")?etirables[i].getAttribute("title"):etirables[i].getAttribute("id"))+"</div>";
						}
					}
					if (textHtml_visible) {
						textHtml+="<div style='width:100%;background:#CCC;color:#333;'>Rendre visible :</div>";
						textHtml+=textHtml_visible;
					}
					textHtml+="<div style='width:100%;background:#CCC;color:#333;'>Déplacer dans l'onglet :</div>";
					for(i=0; i<etirables.length; i++) {
						if (etirables[i].getAttribute("etirable")=="yes") {
							textHtml+="<div onmouseover='this.style.background=\"#666\"; this.style.color=\"#FFF\";' onmouseout='this.style.background=\"#EEE\"; this.style.color=\"#000\";' style='width:100%' onclick='change_onglet(\""+etirables[i].getAttribute("id")+"\",\""+e.currentTarget.getAttribute("id")+"\"); this.parentNode.parentNode.removeChild(this.parentNode);'>&nbsp;&nbsp;"+(etirables[i].getAttribute("title")?etirables[i].getAttribute("title"):etirables[i].getAttribute("id"))+"</div>";
						}
					}
					textHtml+="<div style='width:100%;background:#FFF;border-bottom:#000 2px solid;border-top:#000 1px solid;text-align:center'><b>"+(e.currentTarget.parentNode.getAttribute("title")?e.currentTarget.parentNode.getAttribute("title"):e.currentTarget.parentNode.getAttribute("id"))+"</b></div>";
					textHtml+="<div onmouseover='this.style.background=\"#666\"; this.style.color=\"#FFF\";' onmouseout='this.style.background=\"#CCC\"; this.style.color=\"#000\";' style='width:100%;background:#CCC' onClick='monter_onglet(\""+e.currentTarget.parentNode.getAttribute("id")+"\")'>Monter l'onglet</div>";
					textHtml+="<div onmouseover='this.style.background=\"#666\"; this.style.color=\"#FFF\";' onmouseout='this.style.background=\"#CCC\"; this.style.color=\"#000\";' style='width:100%;background:#CCC' onClick='descendre_onglet(\""+e.currentTarget.parentNode.getAttribute("id")+"\")'>Descendre l'onglet</div>";
					textHtml+="<div onmouseover='this.style.background=\"#666\"; this.style.color=\"#FFF\";' onmouseout='this.style.background=\"#CCC\"; this.style.color=\"#000\";' style='width:100%;background:#CCC' onClick='invert_onglet(\""+e.currentTarget.parentNode.getAttribute("id")+"\")'>Positionner en "+(relp?"absolu":"relatif")+"</div>";
					textHtml+="<div onmouseover='this.style.background=\"#666\"; this.style.color=\"#FFF\";' onmouseout='this.style.background=\"#CCC\"; this.style.color=\"#000\";' style='width:100%;background:#CCC' onClick='invisible_onglet(\""+e.currentTarget.parentNode.getAttribute("id")+"\")'>Rendre invisible l'onglet</div>";
					textHtml_visible="";
					for(i=0; i<etirables.length; i++) {
						if ((etirables[i].getAttribute("etirable")=="yes")&&(etirables[i].getAttribute("hide")=="yes")) {
							textHtml_visible+="<div onmouseover='this.style.background=\"#666\"; this.style.color=\"#FFF\";' onmouseout='this.style.background=\"#EEE\"; this.style.color=\"#000\";' style='width:100%' onclick='visible_onglet(\""+etirables[i].getAttribute("id")+"\"); this.parentNode.parentNode.removeChild(this.parentNode);'>&nbsp;&nbsp;"+(etirables[i].getAttribute("title")?etirables[i].getAttribute("title"):etirables[i].getAttribute("id"))+"</div>";
						}
					}
					if (textHtml_visible) {
						textHtml+="<div style='width:100%;background:#CCC;color:#333;'>Rendre visible les onglet(s) :</div>";
						textHtml+=textHtml_visible;
					}
					textHtml+="<div onmouseover='this.style.background=\"#666\"; this.style.color=\"#FFF\";' onmouseout='this.style.background=\"#CCC\"; this.style.color=\"#000\";' style='width:100%;background:#CCC' onClick='save_all(event);'>Sauver</div>";
					popup.innerHTML=textHtml;
					document.body.appendChild(popup);
					popup.onmouseover=function(e) {
						e.currentTarget.style.cursor="default";
					}
				}
			}
		}
		if (movables[i].getAttribute("etirable")=="yes") {
			if (movables[i].getAttribute("invert")=="yes") relp=(!relative); else relp=relative;
			movables[i].style.border="#000 1px solid";
			movables[i].style.position=relp?"":"relative";
			movables[i].onmousedown=function(e) {
				var bord=e.currentTarget.clientHeight;
				if ((e.layerY>=bord-2)&&(e.layerY<=bord+2)) {
					down_parent=true;
					decy=e.screenY;
					pheight=e.currentTarget.clientHeight
					parent_last_h=pheight;
					parent_move=e.currentTarget.getAttribute("id");
					//Recherche de la hauteur maximale
					var childs=e.currentTarget.childNodes;
					var max=0;
					for (i=0; i<childs.length; i++) {
						var fromTop=childs[i].offsetTop;
						var cheight=childs[i].clientHeight;
						if (fromTop*1+cheight*1>max) max=fromTop*1+cheight*1;
					}
					parent_min=max*1+6*1;
				}
			}
			movables[i].ondblclick=function(e) {
				if (relative) return;
				var bord=e.currentTarget.clientHeight;
				if ((e.layerY>=bord-2)&&(e.layerY<=bord+2)) {
					down_parent=false;
					//Recherche de la hauteur maximale
					var childs=e.currentTarget.childNodes;
					var max=0;
					for (i=0; i<childs.length; i++) {
						var fromTop=childs[i].offsetTop;
						var cheight=childs[i].clientHeight;
						if (fromTop*1+cheight*1>max) max=fromTop*1+cheight*1;
					}
					var nheight=max*1+6*1
					e.currentTarget.style.height=nheight+"px";
				}
			}
			movables[i].onmousemove=move_parent;
			movables[i].onmouseout=function(e) {
				down_parent=false;
				parent_move="";
				e.currentTarget.style.cursor="";
			}
			movables[i].onmouseup=function(e) {
				down_parent=false;
				parent_move="";
				e.currentTarget.style.cursor="";
				//e.target.zIndex=0;
			}
			movables[i].onclick=function(e) {
				var i;
				var relp;
				if (e.ctrlKey) {
					if (e.currentTarget.getAttribute("invert")=="yes") relp=(!relative); else relp=relative;
					if (document.getElementById("popup_onglet")) document.getElementById("popup_onglet").parentNode.removeChild(document.getElementById("popup_onglet"));
					e.cancelBubble = true;
					if (e.stopPropagation) e.stopPropagation();
					popup=document.createElement("div");
					popup.setAttribute("id","popup_onglet");
					popup.style.border="#000 1px solid";
					popup.style.background="#EEE";
					popup.style.position="absolute";
					popup.style.zIndex=10;
					popup.style.left=e.pageX+"px";
					popup.style.top=e.pageY+"px";
					var etirables=document.getElementsByTagName("div");
					var textHtml="<div style='width:100%;background:#FFF;border-bottom:#000 2px solid;text-align:center'><b>"+(e.currentTarget.getAttribute("title")?e.currentTarget.getAttribute("title"):e.currentTarget.getAttribute("id"))+"</b></div>";
					textHtml+="<div onmouseover='this.style.background=\"#666\"; this.style.color=\"#FFF\";' onmouseout='this.style.background=\"#CCC\"; this.style.color=\"#000\";' style='width:100%;background:#CCC' onClick='monter_onglet(\""+e.currentTarget.getAttribute("id")+"\")'>Monter l'onglet</div>";
					textHtml+="<div onmouseover='this.style.background=\"#666\"; this.style.color=\"#FFF\";' onmouseout='this.style.background=\"#CCC\"; this.style.color=\"#000\";' style='width:100%;background:#CCC' onClick='descendre_onglet(\""+e.currentTarget.getAttribute("id")+"\")'>Descendre l'onglet</div>";
					textHtml+="<div onmouseover='this.style.background=\"#666\"; this.style.color=\"#FFF\";' onmouseout='this.style.background=\"#CCC\"; this.style.color=\"#000\";' style='width:100%;background:#CCC' onClick='invert_onglet(\""+e.currentTarget.getAttribute("id")+"\")'>Positionner en "+(relp?"absolu":"relatif")+"</div>";
					textHtml+="<div onmouseover='this.style.background=\"#666\"; this.style.color=\"#FFF\";' onmouseout='this.style.background=\"#CCC\"; this.style.color=\"#000\";' style='width:100%;background:#CCC' onClick='invisible_onglet(\""+e.currentTarget.getAttribute("id")+"\")'>Rendre invisible l'onglet</div>";
					var textHtml_visible="";
					for(i=0; i<etirables.length; i++) {
						if ((etirables[i].getAttribute("etirable")=="yes")&&(etirables[i].getAttribute("hide")=="yes")) {
							textHtml_visible+="<div onmouseover='this.style.background=\"#666\"; this.style.color=\"#FFF\";' onmouseout='this.style.background=\"#EEE\"; this.style.color=\"#000\";' style='width:100%' onclick='visible_onglet(\""+etirables[i].getAttribute("id")+"\"); this.parentNode.parentNode.removeChild(this.parentNode);'>&nbsp;&nbsp;"+(etirables[i].getAttribute("title")?etirables[i].getAttribute("title"):etirables[i].getAttribute("id"))+"</div>";
						}
					}
					if (textHtml_visible) {
						textHtml+="<div style='width:100%;background:#CCC'>Rendre visible les onglet(s) :</div>";
						textHtml+=textHtml_visible;
					}
					textHtml_visible="";
					for(i=0; i<etirables.length; i++) {
						if ((etirables[i].getAttribute("movable")=="yes")&&(etirables[i].style.display=="none")) {
							textHtml_visible+="<div onmouseover='this.style.background=\"#666\"; this.style.color=\"#FFF\";' onmouseout='this.style.background=\"#EEE\"; this.style.color=\"#000\";' style='width:100%' onclick='visible(\""+etirables[i].getAttribute("id")+"\"); this.parentNode.parentNode.removeChild(this.parentNode);'>&nbsp;&nbsp;"+(etirables[i].getAttribute("title")?etirables[i].getAttribute("title"):etirables[i].getAttribute("id"))+"</div>";
						}
					}
					if (textHtml_visible) {
						textHtml+="<div style='width:100%;background:#FFF;border-bottom:#000 2px solid;border-top:#000 1px solid;text-align:center'><b>Champs</b></div>";
						textHtml+="<div style='width:100%;background:#CCC'>Rendre visible :</div>";
						textHtml+=textHtml_visible;
					}
					textHtml+="<div onmouseover='this.style.background=\"#666\"; this.style.color=\"#FFF\";' onmouseout='this.style.background=\"#CCC\"; this.style.color=\"#000\";' style='width:100%;background:#CCC' onClick='save_all(event);'>Sauver</div>";
					popup.innerHTML=textHtml;
					document.body.appendChild(popup);
					popup.onmouseover=function(e) {
						e.currentTarget.style.cursor="default";
					}
				}
			}
		}
	}
	for(i=0; i<movables.length; i++) {
		if (movables[i].getAttribute("etirable")=="yes") {
			if (movables[i].getAttribute("invert")=="yes") relp=(!relative); else relp=relative;
			if (!relp) {
				//placement de tous les champs
				var j=0;
				var py=0;
				if (!movables[i].style.height) {
					var childs=movables[i].childNodes;
					for (j=0; j<childs.length; j++) {
						if (childs[j].nodeType==1) {
							if (childs[j].getAttribute("movable")=="yes") {
								childs[j].style.top=py+"px";
								py+=childs[j].clientHeight;
							}
						}
					}
				}
				resize_onglet(movables[i],false);
			} else {
				movables[i].style.height="";
			}
		}
	}
}

document.onclick=function(e) {
	if (e) {
		if (e.target.nodeType==1)
			if  ((e.target.parentNode.getAttribute("id")!="popup_onglet")&&(e.target.getAttribute("id")!="popup_onglet"))
				if (document.getElementById("popup_onglet")) document.getElementById("popup_onglet").parentNode.removeChild(document.getElementById("popup_onglet"));
	}
}