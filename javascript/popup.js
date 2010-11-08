// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: popup.js,v 1.4 2008-09-04 07:55:43 dbellamy Exp $

// openPopUp : permet d'afficher une popup de la taille et à la position donnée
//		la fonction gere aussi l'autoCentrage de la popup
//		(ATTENTION au mode double ecran : la fonction ne gere pas le centrage par rapport à la fenetre mais par rapport à la taille écran !!)
//
//MyFile :	nom du fichier contenant le code HTML du pop-up
//MyWindow :	nom de la fenetre (ne pas mettre d'espace)
//MyWidth :	entier indiquant la largeur de la fenetre en pixels
//MyHeight :	entier indiquant la hauteur de la fenetre en pixels
//MyLeft :	entier indiquant la position du haut de la fenetre en pixels (-1 pour centrer, -2 pour laisser le navigateur gérer)
//MyTop :	entier indiquant la position gauche de la fenetre en pixels (-1 pour centrer, -2 pour laisser le navigateur gérer)
//MyParam :	Les parametres supplementaires pour la methode open (par def :infobar=no, status=no, scrollbars=no, menubar=no)
function openPopUp(MyFile,MyWindow,MyWidth,MyHeight,MyLeft,MyTop,MyParam) {
var ns4 = (document.layers)? true:false;		//NS 4
var ie4 = (document.all)? true:false;			//IE 4
var dom = (document.getElementById)? true:false;	//DOM
var xMax, yMax, xOffset, yOffset;

	//les valeurs par défaut
	MyParam = MyParam || 'infobar=no, status=no, scrollbars=no, toolbar=no, menubar=no';
	//MyTop = MyTop || -1;
	MyTop=0;
	//MyLeft = MyLeft || -1;
	MyLeft=0;

	if ((MyTop==-1)||(MyLeft==-1)) {
		//fonction de centrage
		//on determine la taille yMax et xMax suivant le navigateur
		if (ie4 || dom)	{
			xMax = screen.width;
			yMax = screen.height;
			}
		else if (ns4) {
			xMax = window.outerWidth;
			yMax = window.outerHeight;
			} else {
				xMax = 800;
				yMax = 600;
			}
		//on calcule le centrage
		xOffset = (xMax - MyWidth)/2;
		yOffset = (yMax - MyHeight)/2;
	} else {
		//une position a été fixée
		xOffset = MyLeft;
		yOffset = MyTop;
	}

	//on precise la taille pour la methode open	
	var fParam = 'width='+MyWidth
			+',height='+MyHeight;

	//on precise la position uniquement si on est pas en mode -2 (position gérée par le navigateur)
	if ((MyTop!=-2)&&(MyLeft!=-2)) {
		fParam = fParam +',screenX='+xOffset
				+',screenY='+yOffset
				+',top='+yOffset
				+',left='+xOffset;
	}

	//on ajoute les parametres en plus 
	var fParam = MyParam + ',' + fParam;

	//on ouvre la popup
	w = window.open(MyFile,MyWindow,fParam);

	//on force la taille 
	//w.window.resizeTo(MyWidth,MyHeight);
	
	//on force la position  uniquement si on est pas en mode -2 (position gérée par le navigateur)
	if ((MyTop!=-2)&&(MyLeft!=-2)) {
		w.window.moveTo(xOffset,yOffset);
	}

	//on force le focus
	w.window.focus();
	return w;
}
