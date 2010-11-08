// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: dynamic_element.js,v 1.3 2010-03-16 10:56:50 kantin Exp $

/*
 * Cette classe a pour but d'implémenter un système générique d'évènement Ajax sur un noeud HTML
 */

var elt_editable = true;

function dynamic_element(idElt,moduleElt,typeElt,fieldElt){
	this.id = idElt;
	this.module = moduleElt;
	this.fieldType = fieldElt;
	this.dataType = typeElt;
	
	var obj = this;
	
	this.addAction = function(){
		document.getElementById(this.id).parentNode.onclick = keep_context(this,"changeHtml");
	}
	
	this.changeHtml = function(){	
		var action = new http_request();
		var url = "./ajax_dynamics.php?module="+this.module+"&fieldElt="+this.fieldType+"&typeElt="+this.dataType+"&id_elt="+this.id+"&quoifaire=edit";
		
		if(elt_editable){
			action.request(url);	
			if(action.get_status() == 0){
				elt_editable = false;
				var div_affichage = document.getElementById(this.id);
				div_affichage.style.display = "none";
				document.getElementById(this.id).parentNode.onclick="";
				document.getElementById(this.id).innerHTML = action.get_text();
				div_affichage.style.display = "";
				document.getElementById('soumission').onclick=function(e) { 
					if (!e) var e=window.event;
					if (e.stopPropagation) {
						e.preventDefault();
						e.stopPropagation();
					} else { 
						e.cancelBubble=true;
						e.returnValue=false;
					}
					obj.saveElt(e);
					return false;
				};
			}
		}
	}
	
	this.saveElt = function(e){
			
		var action = new http_request();
		var url = "./ajax_dynamics.php?module="+this.module+"&fieldElt="+this.fieldType+"&typeElt="+this.dataType+"&id_elt="+this.id+"&quoifaire=save";
		var valeur = document.getElementById('save_'+this.id).value;
		elt_editable = true;
		if(this.dataType.indexOf('progression')!=-1){
			if(valeur > 100 || isNaN(valeur)){
				eval('alert_'+this.dataType+'();');
				return false;
			} 
		} else {
			if(isNaN(valeur)){
				eval('alert_'+this.dataType+'();');	
				return false;
			}
		}
		action.request(url,true,this.dataType+"="+encodeURI(valeur));
		if(action.get_status() == 0){
			var div_affichage = document.getElementById(this.id);
			div_affichage.style.display = "none";
			document.getElementById(this.id).parentNode.onclick="";
			document.getElementById(this.id).innerHTML = action.get_text();
			div_affichage.style.display = "";
			elt_editable = true;
			this.addAction();
		}
	}
	
}

/*
 * Fonction de parcours de l'arbre HTML
 */
var dyn_elts = new Array();

function parse_dynamic_elts(){
	
	parse_dynamic(document.body);
	
	for(k=0;k<dyn_elts.length;k++){
		dyn_elts[k].addAction();
	}
}

function parse_dynamic(elt){
	var i;
	var enfant;
	var nb;
	
	if(elt.nodeType==1){
		if(elt.getAttribute("dynamics")){
			nb=dyn_elts.length;
			dyn_ppte = elt.getAttribute("dynamics").split(',');
			if(elt.getAttribute("id")){
				var params="";
				if(elt.getAttribute("dynamics_params")){
					var params =  elt.getAttribute("dynamics_params");
				}
				dyn_elts[nb] = new dynamic_element(elt.getAttribute("id"),dyn_ppte[0],dyn_ppte[1],params);				
			}
		}
	}
	if(elt.hasChildNodes()){
		for(i=0;i<elt.childNodes.length;i++){
			enfant = elt.childNodes[i];
			parse_dynamic(enfant);
		}
	}
}





