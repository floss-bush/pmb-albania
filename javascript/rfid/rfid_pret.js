// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: rfid_pret.js,v 1.16.2.1 2011-05-20 08:59:46 ngantier Exp $
var count = 0;
var this_param=new Array();	
var key_this_param=new Array();	
var req;
var pret_erreur='';
var id_empr;
var flag_error=0;
var cb_empr;
var mode_lecture_cb=new Array();	
var cb_print=new Array();	
var flag_ajax_confirm_pret_ask=0;
var timer_read=1500;
var flag_force_pret=0;
var nb_doc_error=0;
var lecteur_actif=0;
var flag_antivol_retour=1;
var cb_filtre=new Array();	
var flag_confirm_button=0;	
var flag_force_pret_ask=0;
var memo_cb_doc=new Array();
var memo_demande_force_pret=new Array();
var serveur_rfid_actif=0;	
var list_erreur_cb_count=new Array();
var flag_disable_antivol=0;

function init_rfid_pret(id,cb,mode){
	id_empr=id;
	cb_empr=cb;
	req = new http_request();	
	if(!mode)init_rfid_read_cb(f_read_empr,f_read_expl);
	else mode1_init_rfid_read_cb(f_read_empr,mode1_f_read_expl);
}
function init_sans_rfid_pret(id,cb){
	id_empr=id;
	cb_empr=cb;
	req = new http_request();	
}

function f_read_empr(cb) {	
	// lecture de carte emprunteur				
	if(cb.length > 1) {
		alert('Il y trop de carte lecteur');
	} else if(cb.length == 1) {
		// il y a une carte lecteur
		var cb_read= cb[0];
		if(cb_empr != cb_read ) alert('La carte lecteur a changé '+cb_read+ '  ' +cb_empr);		
	} else {
		//la carte du lecteur absente
	}			
}
function disable_obj(id,level) {
	if(!serveur_rfid_actif) return;
	var curleft = curtop = 0;
	var obj=document.getElementById('disable_'+id);	
	if(obj)document.getElementById('att').removeChild(obj);
	if(!level) {

		document.getElementById('indicateur').src="./images/sauv_succeed.png";
		return;
	}
	document.getElementById('indicateur').src="./images/sauv_failed.png";
	var obj=document.getElementById(id);
	if (obj.offsetParent) {
		curleft = obj.offsetLeft;
		curtop = obj.offsetTop;
		var obj_parent=obj;
		while (obj_parent = obj_parent.offsetParent) {
				curleft += obj_parent.offsetLeft;
				curtop += obj_parent.offsetTop;
		}
	}
	var width=obj.clientWidth;
	var height=obj.clientHeight;
	
	var div_disable = document.createElement('div');
	div_disable.setAttribute('id', 'disable_'+id);
	div_disable.style.width=width+"px";	
	div_disable.style.height=height+"px";	
	div_disable.style.left=curleft+"px";
	div_disable.style.top=curtop+"px";
	div_disable.style.position="absolute";
//	div_disable.style.backgroundImage="url(images/disable.png)";
	div_disable.style.zIndex=1000;
	
	var obj_att=document.getElementById('att');
	obj_att.appendChild(div_disable);
}

function f_read_expl(cb,index,indexcount,antivol) {
	var j,i,found=0,instable,nb_parties=0;
	serveur_rfid_actif=1;
	
	disable_obj('table_pret_tmp',1);

	lecteur_actif=1;
	if(flag_force_pret==1) {
		return;
	}
	if(flag_force_pret_ask) {
		flag_semaphore_rfid_read=0;
		force_pret(memo_demande_force_pret[0],memo_demande_force_pret[1],memo_demande_force_pret[2],memo_demande_force_pret[3]);
		return;
	}
		
	if(flag_ajax_confirm_pret_ask) {
		flag_semaphore_rfid_read=0;
		confirm_pret(); 
		return;
	}
	if(flag_disable_antivol) {
		return;
	}
	nb_doc_error=0;
	if (cb.length) {
		var nb=key_this_param.length;
		for (i=nb; i>=0; i--) {			
			if(	key_this_param[i]) {	
				var cb_expl=key_this_param[i];
				found=0;
				for (j=0; j<cb.length; j++) {				
					if(cb[j] == cb_expl){
						found=1;
						//break;
					}
				}
				//alert(key_this_param.length+' liste '+cb_print[cb_expl]+ ' '+cb_expl);			
				if( cb_print[cb_expl]==2 ) {						
					del_ligne_erreur(cb_expl,this_param[cb_expl]['count'],this_param[cb_expl]['id_expl'],this_param[cb_expl]['status'] );
					key_this_param.splice(i,1);		
					cb_print[cb_expl]=0;
					
					//flag_error=1;				
				}else							
				if( this_param[cb_expl]  )
				if( (found==0) && (this_param[cb_expl]['count']>0) && (mode_lecture_cb[cb_expl]=='rfid') ){
					//alert('1 '+flag_error+ ' '+cb_expl);			
					disable_obj('table_pret_tmp',1);
					cb_print[cb_expl]=0;
					del_pret(cb_expl,this_param[cb_expl]['count'],this_param[cb_expl]['id_expl'],this_param[cb_expl]['status'] );
					key_this_param.splice(i,1);	
				}

				
			}	
		}	
		// vérif des parties
		var info_cb_list=new Array();
		var info_cb_count_verif=new Array();
		var info_cb_count=new Array();
		list_erreur_cb_count=new Array();
		if(indexcount) {
			for (j=0; j<cb.length; j++) {
				
				if(	indexcount[j]>1) {
					if(!info_cb_count_verif[cb[j]]) info_cb_count_verif[cb[j]]=0;
					info_cb_count_verif[cb[j]]++;
					info_cb_count[cb[j]]=indexcount[j];
				}		
			}
			for(var obj_cb in info_cb_count_verif){
				nb_parties+=info_cb_count_verif[obj_cb]-1;
				if(info_cb_count[obj_cb] !=	info_cb_count_verif[obj_cb]) {
					list_erreur_cb_count[obj_cb]=info_cb_count[obj_cb] - info_cb_count_verif[obj_cb];
				}	
			}
		}
		for (j=0; j<cb.length; j++) {				
			if(flag_error ==0) {
				if(!this_param[cb[j]] || !this_param[cb[j]]['count']) {
					disable_obj('table_pret_tmp',1);
					mode_lecture_cb[cb[j]]='rfid';			
					flag_error=Ajax_add_cb(cb[j]);
					
					cb_print[cb[j]]=1;
					//if( flag_error !=0 ) break;
				}
			} else {				
				if(!cb_print[cb[j]] ) {	
					nb_doc_error++;
					cb_print[cb[j]]=2;
					mode_lecture_cb[cb[j]]='rfid';		
					Ajax_get_info_expl(cb[j]);			
				}	
			}	
		}
		
	} else {
		// aucun document		
		for (i=0; i<key_this_param.length; i++) {
			if(key_this_param[i]) {
				var cb_expl=key_this_param[i];
				if( this_param[cb_expl] )
				if( (this_param[cb_expl]['count']>0) && (mode_lecture_cb[cb_expl]=='rfid')  ){
					disable_obj('table_pret_tmp',1);
					del_pret(cb_expl,this_param[cb_expl]['count'],this_param[cb_expl]['id_expl'],this_param[cb_expl]['status'] );
					//supprimer le document du tableau
					key_this_param.splice(i,1);
					cb_print[cb_expl]=0;
				}
			}	
		}
	}
	var indication='';
	if(cb.length-nb_parties) indication="<font size='2'>( "+(cb.length-nb_parties)+" )</font>";
	document.getElementById('indicateur_nb_doc').innerHTML=indication;
	disable_obj('table_pret_tmp',0);	
	if(flag_confirm_button) document.getElementById('div_confirm_pret').style.display='inline';
	else document.getElementById('div_confirm_pret').style.display='none';
}

function init_rfid_retour()	{
	req = new http_request();	
	init_rfid_read_cb(0,read_retour);		
}
function init_sans_rfid_retour()	{
	req = new http_request();	
	
}
function init_rfid_empr(id,cb){
	req = new http_request();	
	init_rfid_read_cb(read_carte_empr,0);		
}

function XMl2array(cb_expl,xml, NodeName) {
	var i,j,found;
	var param = xml.getElementsByTagName(NodeName).item(0);
	this_param[cb_expl] = new Array();		
	for (j=0;j< param.childNodes.length;j++) {
		if (param.childNodes[j].nodeType == 1) {		
			var key = param.childNodes[j].nodeName;					
			if (param.childNodes[j].firstChild) {
				var val = param.childNodes[j].firstChild.nodeValue;
			} else val='';
			// Memorise les paramètres
			this_param[cb_expl][key] = val;	
		}
	}	
	found=0;
	for (i=0; i<key_this_param.length; i++) {
		if(key_this_param[i] == cb_expl){
			found=1;
			break;
		}
	}
	if(found==0)key_this_param[key_this_param.length]	= cb_expl;				
} 

function one_more_ligne_erreur_suite (cb_expl,data) {
	count++;
	tr = document.createElement('TR');
	
	this_param[cb_expl]['count']=count;
	tr.setAttribute('id', 'tr_'+count);
	//tr.setAttribute('disabled', 'true');
	tr.setAttribute('class','erreur');
	//Code barre exemplaire
	var td_1 = document.createElement('TD');
	var obj_1 = document.createElement('a');
	obj_1.setAttribute('href', './circ.php?categ=visu_ex&form_cb_expl='+this_param[cb_expl]['cb_expl']);
	obj_1.id = 'obj_1_'+count;					
	obj_1.appendChild(document.createTextNode(this_param[cb_expl]['cb_expl'])); 
	td_1.appendChild(obj_1);
	tr.appendChild(td_1);

	//Titre
	var td_2 = document.createElement('TD');
	if(this_param[cb_expl]['libelle'])
		td_2.appendChild(document.createTextNode(this_param[cb_expl]['libelle']));	
	tr.appendChild(td_2);

	//Support
	var td_3 = document.createElement('TD');
	if(this_param[cb_expl]['libelle'])
		td_3.appendChild(document.createTextNode(this_param[cb_expl]['tdoc_libelle']));	
	tr.appendChild(td_3);

	//error_message
	var td_4 = document.createElement('TD');
	if(this_param[cb_expl]['status']!=0) {
		td_4.setAttribute('class','erreur');
	}	
	td_4.appendChild(document.createTextNode(this_param[cb_expl]['error_message']));	
	tr.appendChild(td_4);
	//byId('table_pret_tmp').getElementsByTagName('tbody')[0].appendChild(tr);
	document.getElementById('table_pret_tmp').appendChild(tr);
}

function addslashes(ch) {
	ch = ch.replace(/\\/g,"\\\\")
	ch = ch.replace(/\'/g,"\\'")
	ch = ch.replace(/\"/g,"\\\"")
return ch
}

	
function one_more_ligne (cb_expl,data) {
	count++;
	tr = document.createElement('TR');
	if(count==1) {			
		var td_0 = document.createElement('Th');
		td_0.appendChild(document.createTextNode(this_param[cb_expl]['msg_293']));
		tr.appendChild(td_0);

		var td_0 = document.createElement('Th');
		td_0.appendChild(document.createTextNode(this_param[cb_expl]['msg_652']));		
		tr.appendChild(td_0);

		var td_0 = document.createElement('Th');			
		td_0.appendChild(document.createTextNode(this_param[cb_expl]['msg_294']));	
		tr.appendChild(td_0);

		var td_0 = document.createElement('Th');			
		td_0.innerHTML = '&nbsp'
		tr.appendChild(td_0);
		
		var td_0 = document.createElement('Th');			
		td_0.innerHTML = '&nbsp'
		tr.appendChild(td_0);

		var td_0 = document.createElement('Th');			
		td_0.innerHTML = '&nbsp';	
		tr.appendChild(td_0);

		var td_0 = document.createElement('Th');			
		td_0.innerHTML = '&nbsp';
		tr.appendChild(td_0);
				
		//byId('table_pret_tmp').getElementsByTagName('tbody')[0].appendChild(tr);
		document.getElementById('table_pret_tmp').appendChild(tr);
		tr = document.createElement('TR');
	}
	this_param[cb_expl]['count']=count;

	tr.setAttribute('id', 'tr_'+count);
	if(count%2) {
		tr.setAttribute('class','odd');
		tr.setAttribute('onmouseout','this.className=\'odd\'');
	}else {
		tr.setAttribute('class','even');
		tr.setAttribute('onmouseout','this.className=\'even\'');
	}
	tr.setAttribute('onmouseover','this.className=\'surbrillance\'');
	
	//Code barre exemplaire
	var td_1 = document.createElement('TD');
	var obj_1 = document.createElement('a');
	obj_1.setAttribute('href', './circ.php?categ=visu_ex&form_cb_expl='+this_param[cb_expl]['cb_expl']);
	obj_1.id = 'obj_1_'+count;					
	obj_1.appendChild(document.createTextNode(this_param[cb_expl]['cb_expl'])); 
	td_1.appendChild(obj_1);
	tr.appendChild(td_1);

	//Titre
	var td_2 = document.createElement('TD');
	if(this_param[cb_expl]['libelle'])
		td_2.appendChild(document.createTextNode(this_param[cb_expl]['libelle']));	
	tr.appendChild(td_2);

	//Support
	var td_3 = document.createElement('TD');
	if(this_param[cb_expl]['libelle'])
		td_3.appendChild(document.createTextNode(this_param[cb_expl]['tdoc_libelle']));	
	tr.appendChild(td_3);

	// commentaire expl
	var td_2 = document.createElement('TD');
	if(this_param[cb_expl]['expl_comment']) {
		td_2.setAttribute('class','erreur');
		td_2.appendChild(document.createTextNode(this_param[cb_expl]['expl_comment']));
	}		
	tr.appendChild(td_2);	

	//error_message
	var td_4 = document.createElement('TD');
	if(this_param[cb_expl]['status']!=0) {
		td_4.setAttribute('class','erreur');
	}	
	if(list_erreur_cb_count[cb_expl]) {
		td_4.setAttribute('class','erreur');
		if(this_param[cb_expl]['error_message']) this_param[cb_expl]['error_message']+=". ";
		this_param[cb_expl]['error_message']+="Nombre d'éléments manquants: "+list_erreur_cb_count[cb_expl];		
	}	
	td_4.appendChild(document.createTextNode(this_param[cb_expl]['error_message']));	
	tr.appendChild(td_4);

	//Boutton d'annulation du pret effectué (ou pas, si erreur)
	var td_5 = document.createElement('TD');
	if(mode_lecture_cb[cb_expl]!='rfid') {	
		td_5.setAttribute('style','text-align:center');		
		var obj_5 = document.createElement('input');
		obj_5.setAttribute('class', 'bouton');
		obj_5.setAttribute('type', 'button');
		obj_5.setAttribute('name', 'suppr_pret_'+count);
		obj_5.setAttribute('id', 'suppr_pret_'+count);
		obj_5.setAttribute('value', 'X');
		obj_5.setAttribute('expl_id', this_param[cb_expl]['id_expl']);		
		obj_5.setAttribute('onclick','del_pret(\"'+addslashes(cb_expl)+'\",'+ count +',\'' + this_param[cb_expl]['id_expl'] +'\',\'' + this_param[cb_expl]['status'] +'\');' );		
		obj_5.appendChild(document.createTextNode(this_param[cb_expl]['cb_expl'])); 
		td_5.appendChild(obj_5);
	}	
	tr.appendChild(td_5);

	//Boutton de forcage (si erreur forcable)
	var td_6 = document.createElement('TD');
	if(this_param[cb_expl]['status']>0){
		td_6.setAttribute('style','text-align:center');		
		var obj_6 = document.createElement('input');
		obj_6.setAttribute('class', 'bouton');
		obj_6.setAttribute('type', 'button');
		obj_6.setAttribute('name', 'force_pret_'+count);
		obj_6.setAttribute('id', 'force_pret_'+count);
		obj_6.setAttribute('value', this_param[cb_expl]['msg_finance_pret_force_pret']);
		obj_6.setAttribute('expl_id', this_param[cb_expl]['id_expl']);
		obj_6.setAttribute('onclick','force_pret(\''+addslashes(cb_expl)+'\' ,'+ count +',\'' + this_param[cb_expl]['id_expl'] +'\',\'' + this_param[cb_expl]['forcage'] +'\');' );		
		obj_6.appendChild(document.createTextNode(this_param[cb_expl]['cb_expl'])); 
		td_6.appendChild(obj_6);
	}
	tr.appendChild(td_6);

	//byId('table_pret_tmp').getElementsByTagName('tbody')[0].appendChild(tr);
	document.getElementById('table_pret_tmp').appendChild(tr);
	//Si erreur on désactive la possibilité d'ajout de document
	pret_erreur='';
	if(this_param[cb_expl]['status']!=0){		
		pret_erreur='force_pret(\"'+addslashes(cb_expl) +'\",'+ count +',\'' + this_param[cb_expl]['id_expl'] +'\',\'\');'
		var obj_ajouter = document.getElementById('ajouter');
		obj_ajouter.setAttribute('disabled' ,'true');
		flag_confirm_button=0;			
	} else {
		//Aucune erreur, bouton effectuer le pret definitif actif
		if(!nb_doc_error)//document.getElementById('div_confirm_pret').style.display='inline';
			flag_confirm_button=1;		
		cb_print[cb_expl]=1;	
	} 
}
function force_pret(cb_expl,count,id_expl,forcage) {
	if(!id_expl) return;
	disable_obj('table_pret_tmp',1);
	
	if (flag_semaphore_rfid_read && serveur_rfid_actif) {
		flag_force_pret_ask=1;
		memo_demande_force_pret[0]=cb_expl;
		memo_demande_force_pret[1]=count;
		memo_demande_force_pret[2]=id_expl;
		memo_demande_force_pret[3]=forcage;
		return;
	}	
	flag_force_pret_ask=0;
	flag_force_pret=1;
	del_pret(cb_expl,count,id_expl,1);
	// Construction de la requette 			
	var url= "./ajax.php?module=circ&categ=pret_ajax&sub=add_cb&id_expl=" + id_expl + "&id_empr=" + id_empr + "&forcage=" + forcage;	
	// Exécution de la requette
	if(req.request(url,1)){
		// Il y a une erreur. Afficher le message retourné
		alert ( req.get_text() );			
	}else { 
		// commit				
		var xml = req.get_xml();
		XMl2array(cb_expl,xml, 'param');
		one_more_ligne(cb_expl);
		document.getElementById('cb_doc').value='';
		document.getElementById('cb_doc').focus();
		flag_force_pret=0;
		if(!serveur_rfid_actif) {
			if(flag_confirm_button) document.getElementById('div_confirm_pret').style.display='inline';
			else document.getElementById('div_confirm_pret').style.display='none';	
		}	
		return 1;	
	}
	
	flag_force_pret=0;
	
}

function del_pret(cb_expl,count,id_expl,status){
	
	if(!this_param[cb_expl])return;
	if(cb_print[cb_expl]==2){
		del_ligne_erreur(cb_expl,count,id_expl,status);
		return;
	}
	cb_print[cb_expl]=0;
	if(status==0){
		// Supression du pret dans la base	
		var url= "./ajax.php?module=circ&categ=pret_ajax&sub=del_pret&id_expl=" + id_expl;
		if(req.request(url,1)){
			// Il y a une erreur. Afficher le message retourné
			alert ( req.get_text() );			
		} else { 
			// Le pret est supprimé		
		}
	}
	// Supression du pret dans l'affichage	
	var tr = document.getElementById('tr_'+count);	
	if(tr)document.getElementById('table_pret_tmp').removeChild(tr);
	if(pret_erreur && (status==0)) {
		var chaine=pret_erreur;
		pret_erreur='';
		eval(chaine);
	} else {	
		// Reactiver le champ de saisie de code et le bouton Ajouter
		var obj_cb_doc = document.getElementById('cb_doc');
		var obj_ajouter = document.getElementById('ajouter');
		var flag_confirme=1;
		for (i=0; i<key_this_param.length; i++) {					
			if(cb_print[key_this_param[i]]==1) {
				//document.getElementById('div_confirm_pret').style.display='inline';
				flag_confirm_button=1;	
				flag_confirme=0;
				break;
			}
		}
		if (flag_confirme) {
			flag_confirm_button=0;		
		}			
		if(obj_cb_doc.getAttribute('disabled')!= null) obj_cb_doc.removeAttribute('disabled');
		if(obj_ajouter.getAttribute('disabled')!= null) obj_ajouter.removeAttribute('disabled');
		document.getElementById('cb_doc').value='';
		document.getElementById('cb_doc').focus();		
	}
	flag_error =0;
	this_param[cb_expl]['count']=0;			
	if(!serveur_rfid_actif){
		if(flag_confirm_button) document.getElementById('div_confirm_pret').style.display='inline';
		else document.getElementById('div_confirm_pret').style.display='none';
	}
}

function del_ligne_erreur(cb_expl,count,id_expl,status){
	if(!this_param[cb_expl])return;

	// Supression du pret dans l'affichage	
	var tr = document.getElementById('tr_'+count);	
	document.getElementById('table_pret_tmp').removeChild(tr);
	this_param[cb_expl]['count']=0;			
	if(!serveur_rfid_actif) {
		if(flag_confirm_button) document.getElementById('div_confirm_pret').style.display='inline';
		else document.getElementById('div_confirm_pret').style.display='none';
	}	
}
function Ajax_add_cb(cb_doc) {	
	// Récupération de la valeur de l'objet 			
	if(!cb_doc || !id_empr)return 1;	
	// Construction de la requette 			
	var url= "./ajax.php?module=circ&categ=pret_ajax&sub=add_cb&cb_doc=" + cb_doc + "&id_empr=" + id_empr;
	// Exécution de la requette POST
	if(req.request(url,1)){
		// Il y a une erreur. Afficher le message retourné
		alert ( req.get_text() );			
	}else { 
		// commit
		var xml = req.get_xml();
		XMl2array(cb_doc,xml, 'param');
		memo_cb_doc[cb_doc]= this_param[cb_doc];	
		one_more_ligne(cb_doc);
		
		document.getElementById('cb_doc').value='';	
		document.getElementById('cb_doc').focus();
		if(!serveur_rfid_actif) {
			if(flag_confirm_button) document.getElementById('div_confirm_pret').style.display='inline';
			else document.getElementById('div_confirm_pret').style.display='none';
		}	
		return this_param[cb_doc]['status'];		
	}
}

function Ajax_get_info_expl(cb_doc) {
	// Récupération de la valeur de l'objet 			
	if(!cb_doc )return 1;
	
	if(memo_cb_doc[cb_doc]) {	
		memo_cb_doc[cb_doc]['error_message']='';
		memo_cb_doc[cb_doc]['status']='';	
		this_param[cb_doc]=	memo_cb_doc[cb_doc];	
		found=0;
		for (i=0; i<key_this_param.length; i++) {
			if(key_this_param[i] == cb_doc){
				found=1;
				break;
			}
		}
		if(found==0)key_this_param[key_this_param.length] = cb_doc;					
		one_more_ligne_erreur_suite(cb_doc);
		return this_param[cb_doc]['status'];
	}
	// Construction de la requette 			
	var url= "./ajax.php?module=circ&categ=pret_ajax&sub=get_info_expl&cb_doc=" + cb_doc;
	// Exécution de la requette POST
	if(req.request(url,1)){
		// Il y a une erreur. Afficher le message retourné
		alert ( req.get_text() );			
	}else { 
		// commit
		var xml = req.get_xml();
		
		XMl2array(cb_doc,xml, 'param');
		memo_cb_doc[cb_doc]= this_param[cb_doc];	
		one_more_ligne_erreur_suite(cb_doc);		
		return this_param[cb_doc]['status'];		
	}
}

function Ajax_confirm_pret() {
	if(!lecteur_actif){
		confirm_pret();
		return;
	}	
	if(flag_semaphore_rfid_read==0) {
		confirm_pret();
		return;
	}	
	flag_ajax_confirm_pret_ask=1;
}

function confirm_pret() {
	var debug='';
	var i,count=0;
	var liste_pret_url='';
	cb_pret_liste=new Array();
	ptr_cb_pret_liste=0;
	flag_semaphore_antivol=1;
		
	var url= "./ajax.php?module=circ&categ=pret_ajax&sub=confirm_pret&id_empr="+id_empr;
	for (i=0; i<key_this_param.length; i++) {					
		var cb_expl=key_this_param[i];
		if(cb_print[cb_expl]==1) {
			var id_expl=this_param[cb_expl]['id_expl'];				
			liste_pret_url+="&id_expl[]=" + id_expl;	
			cb_pret_liste[count++]=cb_expl;							
		}	
	}
	
	if(liste_pret_url) {
		if(req.request(url+liste_pret_url,1)) {
			// Il y a une erreur. Afficher le message retourné
			alert ( req.get_text() );			
		}else { 	
			// ok
			//var xml = req.get_xml();			
		}
	}
	flag_disable_antivol=1;
	init_rfid_antivol (cb_pret_liste[ptr_cb_pret_liste],0,ack_antivol_pret);	
	setTimeout('no_ack_antivol_pret()',6000*key_this_param.length);	
}

function no_ack_antivol_pret(retVal) {
		alert ('La commande de l\'antivol n\'a pas répondu !');
		document.location="./circ.php?&categ=pret&id_empr="+id_empr;	
}

function ack_antivol_pret(retVal) {
	if(!retVal && mode_lecture_cb[cb_pret_liste[ptr_cb_pret_liste]]=='rfid') 
		alert ('L\'antivol de l\'exemplaire '+cb_pret_liste[ptr_cb_pret_liste]+' n\'a pas été désactivé !');
	if(cb_pret_liste[++ptr_cb_pret_liste]) {
	// suivant	
		init_rfid_antivol (cb_pret_liste[ptr_cb_pret_liste],0,ack_antivol_pret);		
	} else {
		// fin des désactiv	ation antivol, on ferme l'iframe 
		document.location="./circ.php?&categ=pret&id_empr="+id_empr;
	}		
}

function read_carte_empr(cb) {
	// lecture de carte emprunteur				
	if(cb.length > 1) {
		alert('Il y trop de carte lecteur');
	}else if(cb.length == 1){
		// il y a une carte lecteur
		var cb_read= cb[0];
		document.getElementById('form_cb').value=cb_read;
		document.saisie_cb_ex.submit(); 
	} else {
		//la carte du lecteur absente
	}					
}

function one_more_ligne_retour (cb_expl) {
	count++;
	tr = document.createElement('TR');
	if(count==1) {	
		var td_0 = document.createElement('Th');
		tr.appendChild(td_0);		
				
		var td_0 = document.createElement('Th');
		td_0.appendChild(document.createTextNode(this_param[cb_expl]['msg_293']));
		tr.appendChild(td_0);

		var td_0 = document.createElement('Th');
		td_0.appendChild(document.createTextNode(this_param[cb_expl]['msg_652']));		
		tr.appendChild(td_0);

		var td_0 = document.createElement('Th');			
		//td_0.appendChild(document.createTextNode(this_param[cb_expl]['msg_294']));	
		tr.appendChild(td_0);
		
		var td_0 = document.createElement('Th');				
		tr.appendChild(td_0);
		
		var td_0 = document.createElement('Th');			
		td_0.appendChild(document.createTextNode(this_param[cb_expl]['msg_rfid_retour_emprunteur_titre']));	
		tr.appendChild(td_0);

		var td_0 = document.createElement('Th');
		tr.appendChild(td_0);		
				
		document.getElementById('table_retour_tmp').appendChild(tr);
		tr = document.createElement('TR');
	}

	this_param[cb_expl]['count']=count;

	tr.setAttribute('id', 'tr_'+count);
	if(count%2) {
		tr.setAttribute('class','odd');
		tr.setAttribute('onmouseout','this.className=\'odd\'');
	}else {
		tr.setAttribute('class','even');
		tr.setAttribute('onmouseout','this.className=\'even\'');
	}
	tr.setAttribute('onmouseover','this.className=\'surbrillance\'');
	
	var td_4 = document.createElement('TD');
	if(this_param[cb_expl]['retour_message']) {
		td_4.setAttribute('class','erreur');	
		td_4.appendChild(document.createTextNode(this_param[cb_expl]['retour_message']));	
	}	
	tr.appendChild(td_4);
	
	//Code barre exemplaire
	var td_1 = document.createElement('TD');
	var obj_1 = document.createElement('a');
	obj_1.setAttribute('href', './circ.php?categ=visu_ex&form_cb_expl='+this_param[cb_expl]['cb_expl']);
	obj_1.id = 'obj_1_'+count;					
	obj_1.appendChild(document.createTextNode(this_param[cb_expl]['cb_expl'])); 
	td_1.appendChild(obj_1);
	tr.appendChild(td_1);

	//Titre
	var td_2 = document.createElement('TD');
	if(this_param[cb_expl]['libelle'])
		td_2.appendChild(document.createTextNode(this_param[cb_expl]['libelle']));	
	tr.appendChild(td_2);
	// infos type, localisation, section	
	var td_2 = document.createElement('TD');
	var field;
		if(this_param[cb_expl]['type_doc'])	field=this_param[cb_expl]['type_doc'];
		if(this_param[cb_expl]['location'])	field+=', '+this_param[cb_expl]['location'];
		if(this_param[cb_expl]['section'])	field+=', '+this_param[cb_expl]['section'];
		if(field)td_2.appendChild(document.createTextNode(field));	
	tr.appendChild(td_2);

	// commentaire expl
	var td_2 = document.createElement('TD');
	if(this_param[cb_expl]['expl_comment']) {
		td_2.setAttribute('class','erreur');
		td_2.appendChild(document.createTextNode(this_param[cb_expl]['expl_comment']));
	}		
	tr.appendChild(td_2);	
	
	//Emprunteur
	var td_3 = document.createElement('TD');
	if(this_param[cb_expl]['empr_nom']) {
		var obj_3 = document.createElement('a');
		obj_3.setAttribute('href', './circ.php?categ=pret&form_cb='+this_param[cb_expl]['empr_cb']);					
		obj_3.appendChild(document.createTextNode(this_param[cb_expl]['empr_prenom']+' '+this_param[cb_expl]['empr_nom'])); 
		td_3.appendChild(obj_3);		
	}				
	tr.appendChild(td_3);

	if(list_erreur_cb_count[cb_expl]) {
		if(this_param[cb_expl]['error_message']) this_param[cb_expl]['error_message']+=". ";
		this_param[cb_expl]['error_message']+="Nombre d'éléments manquants: "+list_erreur_cb_count[cb_expl];		
	}	
	//error_message
	var td_4 = document.createElement('TD');
	if(this_param[cb_expl]['error_message']) {
		td_4.setAttribute('class','erreur');
		td_4.appendChild(document.createTextNode(this_param[cb_expl]['error_message']));
	}		
	tr.appendChild(td_4);

	document.getElementById('table_retour_tmp').appendChild(tr);
}

function Ajax_do_retour(cb_doc) {
	// Récupération de la valeur de l'objet 			
	if(!cb_doc )return 1;			
	// Construction de la requette 			
	var url= "./ajax.php?module=circ&categ=pret_ajax&sub=do_retour&cb_doc=" + cb_doc;
	if(req.request(url,1)){
		// Il y a une erreur. Afficher le message retourné
		alert ( req.get_text() );			
	}else { 		
		// commit
		var xml = req.get_xml();
		XMl2array(cb_doc,xml, 'param');	
		one_more_ligne_retour(cb_doc);			
		return 1;		
	}
}

function read_retour(cb,index,indexcount,antivol) {
	var j,i,found;
	var flag_antivol=0;

	// vérif des parties
	var info_cb_list=new Array();
	var info_cb_count_verif=new Array();
	var info_cb_count=new Array();
	list_erreur_cb_count=new Array();
	if(indexcount || antivol) {
		for (j=0; j<cb.length; j++) {
			
			if(	indexcount[j]>1) {
				if(!info_cb_count_verif[cb[j]]) info_cb_count_verif[cb[j]]=0;
				info_cb_count_verif[cb[j]]++;
				info_cb_count[cb[j]]=indexcount[j];
			}		
		}
		for(var obj_cb in info_cb_count_verif){
			if(info_cb_count[obj_cb] !=	info_cb_count_verif[obj_cb]) {
				list_erreur_cb_count[obj_cb]=info_cb_count[obj_cb] - info_cb_count_verif[obj_cb];
				
			}	
		}	
	}	
	for (j=0; j<cb.length; j++) {
		var cb_expl=cb[j];
		found=0;
		for (i=0; i<key_this_param.length; i++) {	
			if(key_this_param[i] == cb_expl){
				// Déjà lu et retour effectué
				found=1;
				break;
			}
		}
		if (found==0) {	
			//C'est un nouveau document, faire la requette ajax du retour
			flag_antivol=1;
			Ajax_do_retour(cb_expl);		
			key_this_param[key_this_param.length]=cb_expl;
		}	
	}
	if(flag_antivol && flag_antivol_retour) {
		init_rfid_antivol_all (1,retour_ack_antivol);
	}
	flag_antivol_retour	=1;	
					
}		
		
function retour_ack_antivol(ack) {
	
}





//************************************************** pret mode1







var mode1_liste_cb=new Array();
var mode1_liste_expl_id=new Array();
var mode1_liste_uid=new Array();
var mode1_liste_cb_forcage=new Array();
var mode1_liste_cb_read_type=new Array();// (rfid ou douchette?)
var mode1_liste_cb_echec_antivol=new Array();// liste des cb dont la désactivation de l'antivol a échoué
var mode1_flag_rfid_activite=0;
var mode1_flag_pmb_activite=0;
var mode1_flag_pmb_erreur=0; 
var mode1_flag_rfid_erreur=0;
var mode1_activite_level=0;
var mode1_tableau_expl_count=0;
var mode1_tab_cb_antivol=new Array();
var mode1_timeout_antivol;
var mode1_timeout_read;
var mode1_list_erreur_cb_count=new Array();

function is_in_array(tableau,chaine) {
	for (var i=0; i<tableau.length; i++) {	
		if(tableau[i] == chaine)	return 1;
	}
	return 0;
}
	
function mode1_f_read_expl(cb,index,indexcount,antivol,uid) {	
	
	if( mode1_flag_pmb_erreur==1)return;
	if(mode1_flag_rfid_activite) return;
	var tab_cb_deja_traite = new Array();
	var tab_cb_nouveau = new Array();
	var tab_cb_pmb_request = new Array();
	var nb_parties=0;
	
	mode1_tab_cb_antivol = new Array();
	
	// Montrer le bouton supprimer si pas présent sur la platine
	for (var i=0; i<mode1_liste_cb.length; i++) {
		if(!is_in_array(cb,mode1_liste_cb[i])){
			document.getElementById('suppr_pret_'+mode1_liste_cb[i]).style.display='inline';			
		}	
	}	
		
	if (cb.length) { 
		// affecter liste cb déjà traité (présent dans  liste_cb et lu sur la platine)
		for (var i=0; i<cb.length; i++) {
			if(is_in_array(mode1_liste_cb,cb[i])){
				tab_cb_deja_traite[tab_cb_deja_traite.length]=cb[i];		
				document.getElementById('suppr_pret_'+cb[i]).style.display='none';
			}	
		}
		// affecter liste nouveau cb (pas dans  mode1_liste_cb)
		for (var i=0; i<cb.length; i++) {
			if(!is_in_array(mode1_liste_cb,cb[i])){
				tab_cb_nouveau[tab_cb_nouveau.length]=cb[i];
				mode1_liste_cb[mode1_liste_cb.length]=cb[i];
				mode1_liste_uid[mode1_liste_uid.length]=uid[i];			
			} 
		}
	}	
	
	// vérif des parties
	var info_cb_count_verif=new Array();
	var info_cb_count=new Array();
	mode1_list_erreur_cb_count=new Array();
	if(indexcount) {
		for (var j=0; j<cb.length; j++) {			
			if(	indexcount[j]>1) {
				if(!info_cb_count_verif[cb[j]]) info_cb_count_verif[cb[j]]=0;
				info_cb_count_verif[cb[j]]++;
				info_cb_count[cb[j]]=indexcount[j];
			}	
		}		
		for(var obj_cb in info_cb_count_verif){			
			nb_parties+=info_cb_count_verif[obj_cb]-1;
			if(info_cb_count[obj_cb] !=	info_cb_count_verif[obj_cb]) {
				mode1_list_erreur_cb_count[obj_cb]=info_cb_count[obj_cb] - info_cb_count_verif[obj_cb];
			}	
		}		
	}
	
	// pour tous les nouveaux cb
	for (var i=0; i<tab_cb_nouveau.length; i++) {
		// créer nouveaux objects dans le tableau de prêt
		mode1_one_more_ligne(tab_cb_nouveau[i]);
		// Ajout dans liste pmb à interroger
		tab_cb_pmb_request[i]=tab_cb_nouveau[i];
		// Ajout dans liste antivol à désactiver
		mode1_tab_cb_antivol[mode1_tab_cb_antivol.length]=tab_cb_nouveau[i];
	} // fin  des nouveaux cb
	
	// Erreur du nombre de parties
	for (var i=0; i<mode1_liste_cb.length; i++) {
		if(is_in_array(cb,mode1_liste_cb[i])){
			if(mode1_list_erreur_cb_count[mode1_liste_cb[i]]) {
				if(!document.getElementById('erreur_'+mode1_liste_cb[i]).innerHTML )
					document.getElementById('erreur_'+mode1_liste_cb[i]).innerHTML = "Nombre d'éléments manquants: "+mode1_list_erreur_cb_count[mode1_liste_cb[i]];		;
			}		
		}	
	}
		
	// pour tous les cb déjà traités (présent dans  mode1_liste_cb et lu sur la platine)
	for (var i=0; i<tab_cb_deja_traite.length; i++) {
		if(is_in_array(mode1_liste_cb_echec_antivol,tab_cb_deja_traite[i])){
			// Ajout dans liste antivol à désactiver
			mode1_tab_cb_antivol[mode1_tab_cb_antivol.length]=tab_cb_deja_traite[i];	
		} 
	}	
		
	// Si liste pmb à interroger non vide
	if(tab_cb_pmb_request.length){
		// Alerte activité pmb
		mode1_activite_level++;
		flag_pmb_activite =1;
		mode1_flag_pmb_erreur = 0;
		// Requête serveur PMB pour lire infos expl et effectuer prêts temporaires de cette liste	
		mode1_do_pret_liste(tab_cb_pmb_request);
	}	
	
	// Si  liste antivol à désactiver non vide			
	if(mode1_tab_cb_antivol.length){					
		// Alerte activité rfid		
		mode1_activite_level++;
		mode1_flag_rfid_activite = 1;
		mode1_flag_rfid_erreur = 0;
		// désactiver les antivols de cette liste
		mode1_desactive_antivol_liste();
	}
			
	if(!mode1_flag_pmb_activite && !mode1_flag_pmb_erreur && !mode1_flag_rfid_activite && !mode1_flag_rfid_erreur &&  mode1_liste_cb.length) {
		//Afficher bouton confirm
		document.getElementById('div_confirm_pret').style.display='inline';
		document.getElementById('confirm_pret').setAttribute('onclick','mode1_confirm_pret();' );	
		
	}	else document.getElementById('div_confirm_pret').style.display='none';
	if(!mode1_flag_pmb_activite && !mode1_flag_rfid_activite ) {
		// armer nouvelle lecture rfid			
		mode1_timeout_read=setTimeout('mode1_read_cb()',1500); 	
	}		
			
}
function mode1_desactive_antivol_liste(){
	mode1_ptr_cb_pret_liste=0;
	mode1_flag_rfid_erreur=0;
	init_rfid_antivol (mode1_tab_cb_antivol[mode1_ptr_cb_pret_liste],0,mode1_ack_antivol_pret);	
	mode1_timeout_antivol=setTimeout('mode1_no_ack_antivol_pret()',4000*mode1_tab_cb_antivol.length);	
	document.getElementById('indicateur').src="./images/orange.png";
}

function mode1_no_ack_antivol_pret(retVal) {
	alert ('La commande de l\'antivol n\'a pas répondu !');
	mode1_timeout_read=setTimeout('mode1_read_cb()',0);
	mode1_flag_rfid_activite=0;
}

function mode1_ack_antivol_pret(retVal) {
	
	if(!retVal ) {
		//	alert ('L\'antivol de l\'exemplaire '+mode1_tab_cb_antivol[mode1_ptr_cb_pret_liste]+' n\'a pas été désactivé !');
		if(!is_in_array(mode1_liste_cb_echec_antivol,mode1_tab_cb_antivol[mode1_ptr_cb_pret_liste])){
			mode1_liste_cb_echec_antivol[mode1_liste_cb_echec_antivol.length]=mode1_tab_cb_antivol[mode1_ptr_cb_pret_liste];
		}	
		mode1_flag_rfid_erreur=1;
	}else {
		// Antivol bien désactivé
		var antivol = document.getElementById('antivol_'+mode1_tab_cb_antivol[mode1_ptr_cb_pret_liste]);	
		if(antivol)document.getElementById('td_1_'+mode1_tab_cb_antivol[mode1_ptr_cb_pret_liste]).removeChild(antivol);	
		for (var i=0; i<mode1_liste_cb_echec_antivol.length; i++) {
			if(mode1_liste_cb_echec_antivol[i]==mode1_tab_cb_antivol[mode1_ptr_cb_pret_liste]){
				mode1_liste_cb_echec_antivol.splice(i,1);
				break;
			}
		}
	}	
	if(mode1_tab_cb_antivol[++mode1_ptr_cb_pret_liste]) {
		// suivant	
		init_rfid_antivol (mode1_tab_cb_antivol[mode1_ptr_cb_pret_liste],0,mode1_ack_antivol_pret);		
	} else {
		// fin des désactiv	ation antivol on relance les lecture
		mode1_flag_rfid_activite=0;
		clearTimeout(mode1_timeout_antivol);
		if(!mode1_flag_pmb_activite && !mode1_flag_pmb_erreur || mode1_flag_rfid_erreur) mode1_timeout_read=setTimeout('mode1_read_cb()',0);
		
		if (mode1_flag_pmb_activite)document.getElementById('indicateur').src="./images/sauv_failed.png";
		else document.getElementById('indicateur').src="./images/sauv_succeed.png";
	}		
}

// Ajout de prêt en saisie manuelle
function mode1_add_cb(cb_expl){
	if(is_in_array(mode1_liste_cb,cb_expl)) return;
	
	mode1_liste_cb[mode1_liste_cb.length]=cb_expl;
	mode1_liste_cb_read_type[cb_expl]=1;
	
	mode1_one_more_ligne (cb_expl);
	var antivol = document.getElementById('antivol_'+cb_expl);	
	if(antivol)document.getElementById('td_1_'+cb_expl).removeChild(antivol);	
	
	var cb_list= new Array();
	cb_list[0]=cb_expl; 
	mode1_do_pret_liste(cb_list);
}

function mode1_do_pret_liste(cb_list,del_pret){	
	if (mode1_flag_pmb_activite)document.getElementById('indicateur').src="./images/sauv_failed.png";
	// Construction de la requette
	var url= "./ajax.php?module=circ&categ=pret_ajax&sub=add_cb_list&id_empr=" + id_empr;	
	for (var i=0; i<cb_list.length; i++) {
		url+="&cb_list[]="+cb_list[i];
		if(mode1_liste_cb_forcage[cb_list[i]]){
			url+="&force["+cb_list[i]+"]="+mode1_liste_cb_forcage[cb_list[i]];
		}	
	}
	if(del_pret)url+="&del_pret=1";
	// Exécution de la requette 
	req.request(url,1,"",1,mode1_do_pret_callback,mode1_do_pret_callback_error);
}

function mode1_XMl2array(xml, NodeName) {
	var i,j,found;
	mode1_flag_pmb_erreur=0;
	for(var i=0; i<xml.getElementsByTagName(NodeName).length; i++){
		var param = xml.getElementsByTagName(NodeName).item(i);
		var cb_expl;
		
		var info= new Array();
		for (j=0;j< param.childNodes.length;j++) {
			if (param.childNodes[j].nodeType == 1) {		
				var key = param.childNodes[j].nodeName;					
				if (param.childNodes[j].firstChild) {
					var val = param.childNodes[j].firstChild.nodeValue;
				} else val='';
				// Memorise les paramètres
				info[key]= val;
				if(key=="cb_expl") cb_expl=val;
			}
		}	
		if(cb_expl){
			document.getElementById('titre_'+cb_expl).innerHTML=info["libelle"];
			document.getElementById('support_'+cb_expl).innerHTML=info["tdoc_libelle"];
			document.getElementById('comment_'+cb_expl).innerHTML=info["expl_comment"];
			document.getElementById('erreur_'+cb_expl).innerHTML=info["error_message"];
			if(info["expl_id"]) mode1_liste_expl_id[cb_expl]=info["expl_id"]; 
			else{
				info["status"]=-1;
			}
			var bt_force=document.getElementById('force_pret_'+cb_expl);
			if(bt_force)document.getElementById('forcage_'+cb_expl).removeChild(bt_force);
			
			if(!mode1_flag_pmb_erreur && info["status"]==1){						
				var obj_6 = document.createElement('input');
				obj_6.setAttribute('class', 'bouton');
				obj_6.setAttribute('type', 'button');
				obj_6.setAttribute('name', 'force_pret_'+cb_expl);
				obj_6.setAttribute('id', 'force_pret_'+cb_expl);
				obj_6.setAttribute('value', 'Forcer');
				obj_6.setAttribute('expl_id', info["expl_id"]);
				obj_6.setAttribute('onclick','mode1_force_pret(\''+addslashes(cb_expl)+'\',\''+info["forcage"]+'\');' );					
				document.getElementById('forcage_'+cb_expl).appendChild(obj_6);				
			} 
			if(info["status"]!=0) {
				mode1_flag_pmb_erreur=1;
				document.getElementById('suppr_pret_'+cb_expl).style.display='inline';
				document.getElementById('div_confirm_pret').style.display='none';
			}	
		}	
	}
} 


function mode1_do_pret_callback(infopmb,el){
	var xml =  req.get_xml();
	//alert (req.get_text());
	mode1_XMl2array(xml, 'param');
	mode1_flag_pmb_activite=0;
	if(!mode1_flag_rfid_activite && !mode1_flag_pmb_erreur && !flag_semaphore_rfid_read) mode1_timeout_read=setTimeout('mode1_read_cb()',0);
	
	if (mode1_flag_rfid_activite)document.getElementById('indicateur').src="./images/orange.png";
	else document.getElementById('indicateur').src="./images/sauv_succeed.png";

}

function mode1_do_pret_callback_error(status,text,el){
}

function mode1_force_pret(cb_expl,forcage) {
	if(!mode1_liste_expl_id[cb_expl]) return;
	mode1_liste_cb_forcage[cb_expl]=forcage;
	mode1_do_pret_liste(mode1_liste_cb,1);
}

function mode1_del_pret(cb_expl){		
	clearTimeout(mode1_timeout_read);
	// Supression du pret dans l'affichage	
	var tr = document.getElementById('tr_'+cb_expl);	
	if(tr)document.getElementById('table_pret_tmp').removeChild(tr);
	mode1_liste_expl_id[cb_expl]='';
	for (var i=0; i<mode1_liste_cb.length; i++) {
		if(mode1_liste_cb[i]==cb_expl){
			mode1_liste_cb.splice(i,1);
			mode1_liste_cb_forcage.splice(i,1);
			break;
		}
	}
	mode1_do_pret_liste(mode1_liste_cb,1);

}	

function mode1_confirm_pret() {		
	var url= "./circ.php?module=circ&categ=pret&confirm_pret=1&id_empr="+id_empr;
	for (i=0; i<mode1_liste_cb.length; i++) {
		if(mode1_liste_expl_id[mode1_liste_cb[i]]){
			url+="&id_expl[]=" + mode1_liste_expl_id[mode1_liste_cb[i]];
		}	
	}
	document.location=url;
}


function mode1_one_more_ligne (cb_expl,data) {	
	mode1_tableau_expl_count++;
	tr = document.createElement('TR');
	if(mode1_tableau_expl_count==1) {			
		
		var td_0 = document.createElement('Th');
		td_0.appendChild(document.createTextNode("No."));
		tr.appendChild(td_0);

		var td_0 = document.createElement('Th');
		td_0.appendChild(document.createTextNode("Titre"));		
		tr.appendChild(td_0);

		var td_0 = document.createElement('Th');			
		td_0.appendChild(document.createTextNode("Support"));	
		tr.appendChild(td_0);

		var td_0 = document.createElement('Th');			
		td_0.innerHTML = '&nbsp'
		tr.appendChild(td_0);
		
		var td_0 = document.createElement('Th');			
		td_0.innerHTML = '&nbsp'
		tr.appendChild(td_0);

		var td_0 = document.createElement('Th');			
		td_0.innerHTML = '&nbsp';	
		tr.appendChild(td_0);

		var td_0 = document.createElement('Th');			
		td_0.innerHTML = '&nbsp';
		tr.appendChild(td_0);
				
		document.getElementById('table_pret_tmp').appendChild(tr);		
		tr = document.createElement('TR');
		
	}
	tr.setAttribute('id', 'tr_'+cb_expl);
	if(mode1_tableau_expl_count%2) {
		tr.setAttribute('class','odd');
		tr.setAttribute('onmouseout','this.className=\'odd\'');
	}else {
		tr.setAttribute('class','even');
		tr.setAttribute('onmouseout','this.className=\'even\'');
	}
	tr.setAttribute('onmouseover','this.className=\'surbrillance\'');
	
	var oImg=document.createElement("img");
	oImg.setAttribute('src', './images/orange_small.png');
	oImg.setAttribute('align', 'top');
	oImg.id = 'antivol_'+cb_expl;
	//oImg.setAttribute('alt', 'Antivol non désactivé');
	// software-update-urgent.png
	// ./images/sauv_succeed.png
	
	
	//Code barre exemplaire
	var td_1 = document.createElement('TD');
	td_1.id = 'td_1_'+cb_expl;		
	var obj_1 = document.createElement('a');
	obj_1.setAttribute('href', './circ.php?categ=visu_ex&form_cb_expl='+cb_expl);
	obj_1.id = 'obj_1_'+cb_expl;					
	obj_1.appendChild(document.createTextNode(cb_expl)); 
	td_1.appendChild(oImg);
	td_1.appendChild(obj_1);
	
	tr.appendChild(td_1);
			
	//Titre
	var td_2 = document.createElement('TD');
	td_2.id = 'titre_'+cb_expl;		
	td_2.innerHTML = '&nbsp';		
	tr.appendChild(td_2);

	//Support
	var td_3 = document.createElement('TD');
	td_3.id = 'support_'+cb_expl;		
	td_3.innerHTML = '&nbsp';	
	tr.appendChild(td_3);

	// commentaire expl
	var td_2 = document.createElement('TD');
	td_2.setAttribute('class','erreur');
	td_2.id = 'comment_'+cb_expl;		
	td_2.innerHTML = '&nbsp';				
	tr.appendChild(td_2);	

	//error_message
	var td_4 = document.createElement('TD');
	td_4.setAttribute('class','erreur');
	td_4.id = 'erreur_'+cb_expl;				
	td_4.innerHTML = '&nbsp';		
	tr.appendChild(td_4);

	//Boutton d'annulation du pret effectué (ou pas, si erreur)
	var td_5 = document.createElement('TD');

	td_5.setAttribute('style','text-align:center');		
	var obj_5 = document.createElement('input');
	obj_5.setAttribute('class', 'bouton');
	obj_5.setAttribute('type', 'button');
	obj_5.setAttribute('name', 'suppr_pret_'+cb_expl);
	obj_5.setAttribute('id', 'suppr_pret_'+cb_expl);
	obj_5.setAttribute('value', 'X');
	obj_5.setAttribute('style', 'display:none');	
	obj_5.setAttribute('onclick','mode1_del_pret(\"'+addslashes(cb_expl)+'\");' );		
	obj_5.appendChild(document.createTextNode(cb_expl)); 
	td_5.appendChild(obj_5);
	tr.appendChild(td_5);

	//Boutton de forcage (si erreur forcable)
	var td_6 = document.createElement('TD');
	td_6.id = 'forcage_'+cb_expl;	
	td_6.setAttribute('style','text-align:center');	
	tr.appendChild(td_6);
		
	document.getElementById('table_pret_tmp').appendChild(tr);

}
