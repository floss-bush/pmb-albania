// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: http_request.js,v 1.8 2008/02/19 17:25:04 dbellamy Exp $

//Class javascript permettant d'effectuer les requêtes Http (Ajax) vers le serveur
function http_request() {
	
	this.request = http_send_request;
	this.get_status = get_status;
	this.get_text = get_text;
	this.get_xml = get_xml;	
	this.get_id_req = get_id_req;	
	var req_status = 0;
	var req, f_return, f_error,id_req;	
	
	/* 
	* Fonction http_send_request
	* Traitement:
	* 	Permet d'envoyer une requête http vers le serveur
	*  	Le status de la requête est mémorisé dans req_status, interrogeable par la méthode get_status
	*  	La réponse est mémorisée dans text, interrogeable par la méthode get_text 
	* 	Par defaut, si seul url est renseigné, la requête est exécutée en GET, synchrone.
	* 
	* Paramètres d'entrée:
	* 
	*	url, est l'url du serveur 
	*	post_flag, méthode d'envoi des paramètres: true pour POST, false pour GET.
	*	post_param, texte des paramètres passés par la methode POST: &p1=value1&p2=value2 ...
	*	async_flag, true pour asynchrone ou false pour synchrone 
	*	f_return, fonction de callback si pas d'erreur d'exécution de la requête Http
	*	f_error, fonction de callback si erreur
	* 	 
	* Paramètres de sortie:
	* 	retourne 
	* 		0 ,	pas d'erreur 
	* 		>200, erreur http
	*    	-1 , autre erreur  
	*/

	function http_send_request(url, post_flag ,post_param, async_flag, func_return, func_error,ident_req) {
		// If these inputs are not defined
		if (!post_flag) post_flag = false;
		if (!async_flag) async_flag = false;
		if(ident_req)id_req=ident_req;
		f_return = func_return;
		f_error = func_error;
		req_status = -1;		
		req = http_create_request();
		if(req){
			if(post_flag == true){
				req.open("POST", url, async_flag);
				req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			} else {		
				req.open("GET", url, async_flag);			
			}
			if (async_flag) req.onreadystatechange = http_callback;	
			req.send(post_param);
			if(async_flag == false){
				http_callback();
			}
		}	
		return(req_status);
	}	
	
	function get_status() {
		return(req_status);	
	}	
	function get_id_req() {
		return(id_req);	
	}		
	function get_xml() {
		return(req.responseXML);		
	}
	
	function get_text() {
		return(req.responseText);		
	}
	
	function http_create_request() {
		var request = false;
		try {
			request = new ActiveXObject('Msxml2.XMLHTTP');
		}
		catch (err2) {
			try {
				request = new ActiveXObject('Microsoft.XMLHTTP');
			}
			catch (err3) {
				try {
					request = new XMLHttpRequest();
				}
				catch (err1) {
					request = false;
				}
			}
		}
		return request;
	}
	
	// Fonction qui traite la réponse Http
	function http_callback() {			
		if(req.readyState == 4)	{
			if(req.status == 200) { // No error
				req_status = 0;
				if(f_return) {
					if(id_req)f_return(req.responseText,id_req);
					else f_return(req.responseText);
				}					
			} else {
				req_status = req.status;				
				if(f_error)  {
					if(id_req)f_error(req.status,req.responseText,id_req);
					else f_error(req.status,req.responseText);
				}			
	       	}
		} //else, le statut reste à -1;
	}

}// End class


function XMl_to_array(xml, NodeName) {
	var i,j;
	var param = xml.getElementsByTagName(NodeName).item(0);
	var this_param = new Array();		
	for (j=0;j< param.childNodes.length;j++) {
		if (param.childNodes[j].nodeType == 1) {		
			var key = param.childNodes[j].nodeName;					
			if (param.childNodes[j].firstChild) {
				var val = param.childNodes[j].firstChild.nodeValue;
			} else val='';
			// Mémorise les paramètres
			this_param[key] = val;	
		}
	}
	return 	this_param;			
} 

