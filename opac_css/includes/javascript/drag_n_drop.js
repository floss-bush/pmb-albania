// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: drag_n_drop.js,v 1.5 2009-01-19 16:20:49 kantin Exp $

draggable=new Array();
recept=new Array();
//state="";
is_down=false;
dragup=true;
posxdown=0;
posydown=0;
current_drag=null;
dragged=null;
r_x=new Array();
r_y=new Array();
r_width=new Array();
r_height=new Array();

//Trouve la position absolue d'un objet dans la page
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

//Récupère les coordonnées du click souris
function getCoordinate(e) {
	var posx = 0;
	var posy = 0;
	if (!e) var e = window.event;
	if (e.pageX || e.pageY) 	{
		posx = e.pageX;
		posy = e.pageY;
	}
	else if (e.clientX || e.clientY) 	{
		posx = e.clientX + document.body.scrollLeft
			+ document.documentElement.scrollLeft;
		posy = e.clientY + document.body.scrollTop
			+ document.documentElement.scrollTop;
	}
	return [posx,posy];
}

//Handler : Click sur un élément draggable
function mouse_down_draggable(e) {
	//On annule tous les comportements par défaut du navigateur (ex : sélection de texte)
	if (!e) var e=window.event;
	if (e.stopPropagation) {
		e.preventDefault();
		e.stopPropagation();
	} else { 
		e.cancelBubble=true;
		e.returnValue=false;
	}
	//Récupération de l'élément d'origine qui a reçu l'évênement
	if (e.target) var targ=e.target; else var targ=e.srcElement;
	
	//On nettoie tout drag en cours
	posxdown=0;
	posydown=0;
	is_down=false;
	if (current_drag) current_drag.parentNode.removeChild(current_drag);
	current_drag=null;
	dragged=null;
	
	//Recherche du premier parent qui a draggable comme attribut
	while (targ.getAttribute("draggable")!="yes") {
		targ=targ.parentNode;
	}
	//On stocke l'élément d'origine
	dragged=targ;
	//Stockage des coordonnées d'origine du click
	var pos=getCoordinate(e);
	posxdown=pos[0];
	posydown=pos[1];
	//Il y a un élément en cours de drag !
	is_down=true;
	//Appel de la fonction callback before si elle existe
	if (targ.getAttribute("callback_before")) {
		eval(targ.getAttribute("callback_before")+"(targ,e)");
	}
	//Création du clone qui bougera
	create_dragged(targ);
}

//Evênement : passage au dessus d'un élément draggable : on affiche un 
// petit symbole pour signifier qu'il est draggable
function mouse_over_draggable(e) {
	if (!e) var e=window.event;
	if (e.target) var targ=e.target; else var targ=e.srcElement;
	
	//Recherche du premier parent qui a draggable
	while (targ.getAttribute("draggable")!="yes") {
		targ=targ.parentNode;
	}
		
	//On met un petit symbole "drap"
	//Recherche de la position
	var pos=findPos(targ);
	var posPere=findPos(targ.parentNode);
	//Création d'un <div><image/></div> au dessu de l'élement
	var drag_symbol=document.createElement("div");
	drag_symbol.setAttribute("id","drag_symbol_"+targ.getAttribute("id"));
	drag_symbol.style.position="absolute";	
	drag_symbol.style.top=pos[1]+"px";
	drag_symbol.style.zIndex=1000;
	img_symbol=document.createElement("img");
	img_symbol.setAttribute("src","images/drag_symbol.png");
	drag_symbol.appendChild(img_symbol);
			
	var decalage=pos[0]-posPere[0];
	if((targ.offsetWidth+img_symbol.width+decalage)<targ.parentNode.offsetWidth)
		drag_symbol.style.left=(pos[0]+targ.offsetWidth)+"px";
	else { 
		drag_symbol.style.left= (pos[0]+(targ.parentNode.offsetWidth-img_symbol.width-decalage))+"px";
	}
	//Affichage à partir de l'ancre
	document.getElementById("att").appendChild(drag_symbol);
	
}

//Evênement : on sort du survol d'un élément "draggable"
function mouse_out_draggable(e) {
	if (!e) var e=window.event;
	if (e.target) var targ=e.target; else var targ=e.srcElement;
	
	//Recherche du premier parent qui a draggable
	while (targ.getAttribute("draggable")!="yes") {
		targ=targ.parentNode;
	}
	//Suppression du petit symnbole
	drag_symbol=document.getElementById("drag_symbol_"+targ.getAttribute("id"));
	if (drag_symbol) drag_symbol.parentNode.removeChild(drag_symbol);
}

//Quand on relache le clone, y-a-t-il un élément recepteur en dessous ? Si oui, on retourne l'id
function is_on() {
	var i;
	if (current_drag!=null) {
		var pos=findPos(current_drag);
		for (i=0; i<recept.length; i++) {
			var isx=0;
			var isy=0;
			if ((pos[0]>r_x[i])&&(pos[0]<parseFloat(r_x[i])+parseFloat(r_width[i]))) isx++; 
			if (((parseFloat(pos[0])+parseFloat(current_drag.offsetWidth))>r_x[i])&&((parseFloat(pos[0])+parseFloat(current_drag.offsetWidth))<parseFloat(r_x[i])+parseFloat(r_width[i]))) isx++;
			if ((pos[1]>r_y[i])&&(pos[1]<parseFloat(r_y[i])+parseFloat(r_height[i]))) isy++;
			if (((parseFloat(pos[1])+parseFloat(current_drag.offsetHeight))>r_y[i])&&((parseFloat(pos[1])+parseFloat(current_drag.offsetHeight))<parseFloat(r_y[i])+parseFloat(r_height[i]))) isy++;
			if (parseInt(isx)*parseInt(isy)) return recept[i];			 
		}
	}
	return false;
}

//Si la souris est au dessus du document et qu'on est en cours de drag, on annule tous les 
// comportements par défaut du navigateur
function mouse_over(e) {
	if (!e) var e=window.event;
	if (is_down) {
		if (e.stopPropagation) {
			e.preventDefault();
			e.stopPropagation();
		} else {
			e.cancelBubble = true;
			e.returnValue=false;
		}
	}
}

//On relache le bouton en cours de drag
function up_dragged(e) {
	//Si il y a un clone en cours de mouvement, on le supprime, on remet tout à zéro et on 
	// appelle la fonction qui gère le drag si elle existe et qu'il y a un récepteur en dessous 
	if (current_drag!=null) {
		//Y-a-t-il un récepteur en dessous du lâché ?
		target=is_on();
		//Appel de la fonction callback_after si elle existe
		if (dragged.getAttribute("callback_after")) {
			eval(dragged.getAttribute("callback_after")+"(dragged,e,'"+target+"')");
		}
		//Remise à zero
		posxdown=0;
		posydown=0;
		is_down=false;
		if (current_drag) current_drag.parentNode.removeChild(current_drag);
		current_drag=null;
		//Si il y a un récepteur : callback de la fonction d'association si elle existe 
		if (target) {
			if (eval("typeof "+dragged.getAttribute("dragtype")+"_"+document.getElementById(target).getAttribute("recepttype")+"=='function'")) {
				eval(dragged.getAttribute("dragtype")+"_"+document.getElementById(target).getAttribute("recepttype")+"(dragged,document.getElementById(target))");
			}
		}
		//On nettoie la référence à l'élément d'origine
		dragged=null;
	}
}

//Evênement : Déplacement du clone (draggage)
function move_dragged(e) {
	if (!e) var e=window.event;
	//Si il y a un drag en cours 
	if (is_down) {
		//On annulle tous les comportements par défaut du navigateur
		if (e.stopPropagation) {
			e.preventDefault();
			e.stopPropagation();
		} else {
			e.cancelBubble = true;
			e.returnValue=false;
		}
		//Déplacement
		var pos=getCoordinate(e);
		//Positionnement du clone pour que le pointeur de la souris soit au milieu !
		var encx=current_drag.offsetWidth;
		var ency=current_drag.offsetHeight;
		current_drag.style.left=(pos[0]-(encx/2))+"px";
		current_drag.style.top=(pos[1]-(ency/2))+"px";
	}
}

//Création du clone
function create_dragged(targ) {
	//Recherche de la position d'origine
	initpos=findPos(targ);
	//Création du clone si nécéssaire
	if (current_drag==null) {
		current_drag=document.createElement("div");
		current_drag.setAttribute("id",targ.getAttribute("id")+"drag_");
		current_drag.className="dragged";
		current_drag.appendChild(targ.cloneNode(true));
		current_drag.style.position="absolute";
		current_drag.style.visibility="hidden";
		current_drag=document.getElementById("att").appendChild(current_drag);
		initpos=findPos(targ);
		current_drag.style.left=initpos[0]+"px";
		current_drag.style.top=initpos[1]+"px";
		current_drag.style.zIndex=2000;
		current_drag.style.visibility="visible";
		current_drag.style.cursor="move";
	}
}

//Parcours de l'arbre HTML pour trouver les éléments qui on les attributs draggable ou recept
function parse_drag(node) {
	var i;
	var childs;
	var c;
	
	//Pour tous les fils du noeud passé 
	childs=node.childNodes;
	if (childs.length) {
		for (i=0; i<childs.length; i++) {
			c=childs[i];
			//Pour un fils, si c'est un noeud de type element (1), alors on regarde ses attributs 
			if (c.nodeType=="1") {
				//C'est un récepteur
				if (c.getAttribute("recept")=="yes") {
					recept[recept.length]=c.getAttribute("id");
				} 
				//C'est un draggable
				if (c.getAttribute("draggable")=="yes") {
					draggable[draggable.length]=c.getAttribute("id");
				}
				//Si il a des enfants, on parse ses enfants !
				if (c.hasChildNodes()) parse_drag(c);
			}
		}
	} 
}

//Initialisation des fonctionnalités (a appeller à la fin du chargement de la page)
function init_drag() {
	var i;
	
	//Recherche de tous les éléments draggables et des récepteurs
	parse_drag(document.body);
	
	//Calcul des encombrements des récepteurs
	for (i=0; i<recept.length; i++)  {
		var r=document.getElementById(recept[i]);
		var pos=findPos(r);
		r_x[i]=pos[0];
		r_y[i]=pos[1];
		r_width[i]=r.offsetWidth;
		r_height[i]=r.offsetHeight;
	}
	
	//Implémentation des gestionnaires d'évênement pour les éléments draggables
	for (i=0; i<draggable.length; i++)  {
		document.getElementById(draggable[i]).onmousedown=function(e) {
			mouse_down_draggable(e);
		}
		document.getElementById(draggable[i]).onmouseover=function(e) {
			mouse_over_draggable(e);
		}
		document.getElementById(draggable[i]).onmouseout=function(e) {
			mouse_out_draggable(e);
		}
	}
	//On surveille tout ce qui se passe dans le document au niveau de la souris (sauf click down !)
	document.onmousemove=function (e) {
		move_dragged(e);
	}
	document.onmouseup=function (e) {
		up_dragged(e);
	}
	document.onmouseover=function (e) {
		mouse_over(e);
	}
}