// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: misc.js,v 1.4 2009-03-17 21:04:57 gueluneau Exp $


// Fonction check_checkbox : Permet de changer l'états d'une liste de checkbox.
// checkbox_list : Liste d'id des checkbox séparée par |
// level: 1 (checked) ou 0;
function check_checkbox(checkbox_list,level) {
	var ids,id,state;
	if(level) state=true; else state=false;
	ids=checkbox_list.split('|');
	while (ids.length>0) {
		id=ids.shift();
		document.getElementById(id).checked = state;
	}
}


/* -------------------------------------------------------------------------------------
 *		Déroulement du menu vertical sur clic, enregistrement
 *		des préférences sur ctrl+clic avec ajax
 *
 *		menuHide - setMenu - menuSelectH3 - setMenuHide - menuAutoHide
 * ----------------------------------------------------------------------------------- */

/* -----------------------------------------------------------------------------------
 * Fonction menuHide
 * gestionnaire général pour masquer le menu, declenche sur onclick du <span>
 */
// si l'utilisateur n'enregistre pas de préférences,  on rétracte/déplie le menu.
function menuHide(obj,event){
	var ctrl = event.ctrlKey;
	if (ctrl){setMenu(event);}
	else {menuHideObject(obj);}
}

/* -----------------------------------------------------------------------------------
 * Fonction setMenu
 * sauve-restaure les preferences sur le déroulement par défaut du menu selectionne
 */
// Variables globales
var hlist=new Array();
var hclasses=new Array();

function setMenu(){
	var menu = document.getElementById("menu");
	var childs = menu.childNodes;
	var parseH3=0;
	
	//on relève l'etat du menu
	var values="";
	var j=1;
	for(i=0; i<childs.length; i++){
		if(childs[i].tagName=='H3'){
			hlist[j]=childs[i];
			hclasses[j]=hlist[j].className;
			parseH3=1;
			j++;
		} else if (childs[i].tagName=='UL' && parseH3==1){
			if(childs[i].style.display=='none'){values+='f,';}
			else{values+='t,';}
			parseH3=0;
		}
	}
	//requete ajax pour sauvegarder l'etat
	savehide = new http_request();
	var url= "./ajax.php?module=ajax&categ=menuhide&fname=setpref";
	url=encodeURI(url) 
	var page = document.getElementsByTagName("body")[0].className;
	page=encodeURI(page)
	values=encodeURI(values)
	savehide.request(url,1,"&page="+page+"&values="+values);
	if(savehide.get_text()!=0){
		alert(savehide.get_text());
	} else {
		for(i=1; i<hlist.length; i++){
			setTimeout("hlist["+i+"].className=\"setpref\"",i*15);
			setTimeout("hlist["+i+"].className=hclasses["+i+"]",i*15+500);
		}
	}
}

/* -------------------------------------------------------------------------------------
 * Fonction menuHideObject
 * Masque ou affiche le menu sous le H3 sélectionné
 */
function menuHideObject(obj,force) {
	var pointer=obj;
	do{
		pointer=pointer.nextSibling;
		if (pointer.tagName=='H3' || pointer.tagName=='DIV'){
			break;
		}
		if (pointer.tagName=='UL'){
			if (force==undefined){
				if (pointer.style.display=='none'){
					pointer.style.display='block';
					menuSelectH3(pointer,"");
				}
				else {
					pointer.style.display='none';
					menuSelectH3(pointer,"selected");
				}
			} else {
				if (force==0){
					pointer.style.display='block';
					menuSelectH3(pointer,"");
				}
				else {
					pointer.style.display='none';
					menuSelectH3(pointer,"selected");
				}
			}
		}
	}while(pointer.nextSibling);
}
/* -------------------------------------------------------------------------------------
 * Fonction menuSelectH3()
 * Attribue au menuH3 selectionne une nouvelle classe css (a priori purement esthetique)
 */
function menuSelectH3(ulChild,selectState){
	prec=ulChild.previousSibling;
	if(navigator.appName != "Microsoft Internet Explorer"){
		prec=prec.previousSibling;
	}
	if(prec.tagName=='H3'){
		prec.className=selectState;
	}
}

/* --------------------------------------------------------------------------------------
 * Fonction menuGlobalHide
 * Force le depliement d'une liste de menus, masque tous les autres.
 */
function menuGlobalHide(boollist){
	var boollist=boollist.split(",");	
	var menu = document.getElementById("menu");
	var fils = menu.childNodes;
	var j=0;
	for(i=0; i<fils.length; i++){
		if(fils[i].tagName=='H3'){
			if(boollist[j]=='t'){
				menuHideObject(fils[i],0);
			} else {
				menuHideObject(fils[i],1);
			}
			j++;
		}
	}
}

/* --------------------------------------------------------------------------------------
 * Fonction menuAutoHide
 * Recuppere les preferences d'affichage de l'user, si != 0 elles sont définies
 * et on deplie/replie les menus avec l'appel à menuGlobalHide
 */
function menuAutoHide(){
	if (!trueids) {
		var getHide = new http_request();
		var url = "./ajax.php?module=ajax&categ=menuhide&fname=getpref";
		url=encodeURI(url)
		var page = document.getElementsByTagName("body")[0].className;
		page=encodeURI(page)
		getHide.request(url,1,"&page="+page);	
		if(getHide.get_text()!=0){
			menuGlobalHide(getHide.get_text());	
		}
	} else if (trueids!="0") menuGlobalHide(trueids);	
}