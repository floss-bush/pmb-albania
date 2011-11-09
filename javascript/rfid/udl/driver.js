// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: driver.js,v 1.6 2011-03-21 09:07:46 ngantier Exp $

var f_empr_client;
var f_expl_client;
var f_ack_write;	
var f_ack_erase;
var f_ack_detect;
var f_ack_write_empr;
var f_ack_antivol_all;
var f_ack_antivol;
var f_ack_read_uid;
var flag_semaphore_rfid=0;
var flag_semaphore_rfid_read=0;
var flag_rfid_active=1;
var rfid_active_test=1;
var rfid_active_test_exec=0;
var rfid_focus_active=1;

var tag_size_memory=23;  // 12 for Hammer

//03132333400000000000000

function init_rfid_read_cb(empr_client,expl_client){	
	f_empr_client=empr_client;
	f_expl_client=expl_client;
	// RFID init
	try {
		netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	} catch (e) {
		alert(e);
	  	exit();
	}
	read_cb();
}

function timeout() {
	if(!flag_rfid_active_test) {
		flag_rfid_active=0;
		return;
	}	
}

function read_cb() {	
	if(flag_disable_antivol) {
		return;
	}
	if(!rfid_active_test_exec) {
		rfid_active_test_exec++;
		setTimeout('timeout()',20000);
	}
	if (flag_semaphore_rfid || flag_semaphore_rfid_read) {
		setTimeout('read_cb()',1500); 
		return;
	}
	flag_rfid_active_test=0;
	if(!rfid_focus_active) {setTimeout('read_cb()',1500); return;}	
	flag_semaphore_rfid_read=1;	
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	var req_rfid = new http_request();	
	var url= url_serveur_rfid+"";		
	var cmd="<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:deis=\"http://deister.device.plugntrack.frequentiel.com/\"><soapenv:Header/><soapenv:Body><deis:readId/></soapenv:Body></soapenv:Envelope>"
	req_rfid.request(url,1,cmd,1,result_read_cb,0,0);	
}
var size_info_tag=12; // 12 ou 16, on le sait qu'avec une lecture
function hex2a(hex) {
    var str = '';
    for (var i = 0; i < hex.length; i += 2)
    	if(parseInt(hex.substr(i, 2), 16))
        str += String.fromCharCode(parseInt(hex.substr(i, 2), 16));
    return str;
}


function result_read_cb (retVal) {
	var i;
	var array_cb_expl=new Array();
	var array_cb_empr=new Array();
	var array_cb_index=new Array();
	var array_cb_count=new Array();
	var array_cb_eas=new Array();
	var nb_doc=0;
	var nb_patroncard=0;
		
	flag_rfid_active_test=1;
	flag_rfid_active=1;
	
	var info=retVal.substring(retVal.indexOf("<return>")+8,retVal.indexOf("</return>"));
	//00 00 00 00 00 00 00 00 00 00 01 63<
	
	// 00000000000000b005fb6300000000
	// b00833b2ddd9048035050000
	// 083031323334353637380000
	if(info.indexOf("Error")<0){	
		// pas d'info si expl ou empr...
		// etat antivol
		info='0'+info;
		if(info.substring(0,1)== 8)		array_cb_eas[0]=1;
		else array_cb_eas[0]=0;
		
		// cb
		array_cb_expl[0]=hex2a(info.substring(2));
		// piece
		array_cb_index[0]=1;
		array_cb_count[0]=1;
		
		//array_cb_empr[0]=info;							
	}
			
	if(f_expl_client)	f_expl_client(array_cb_expl,array_cb_index,array_cb_count,array_cb_eas);
	if(f_empr_client)	f_empr_client(array_cb_empr);
	setTimeout('read_cb()',1000);
	flag_semaphore_rfid_read=0;
}

// Detect présence d'élement rfid
function init_rfid_detect(ack_detect) {
	if(!flag_rfid_active) return;
	f_ack_detect=ack_detect;
	f_ack_detect(true);
}	
function result_detect(retVal) {
	
}

// Efface tout !!!
function init_rfid_erase(ack_erase) {
	f_ack_erase=ack_erase;
  	if(!flag_rfid_active) return;
	read_uid(rfid_erase_suite); 
}
	
function rfid_erase_suite(retVal) {
	if(!flag_rfid_active) return;
	
	if(proxies_response_xml.length != 1) {
		if(proxies_response_xml.length > 1) alert('Il y a plusieurs étiquettes !');
		else if(proxies_response_xml.length == 0) alert('Aucune étiquette détectée!');
		return;
	}	
	var uid=proxies_response_xml[0]['UID'];
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	proxies.NedapRfidWebService.WriteFrenchLabel_native.func = result_erase;
	proxies.NedapRfidWebService.WriteFrenchLabel_native(SerialPort,200,uid,'',LibraryCode,1,1,0,1,'','','','','');  	       
}	

function result_erase(retVal) {
	if(f_ack_erase)f_ack_erase(true);
}


var write_etiquette_data=new Array();
	


// Programme une étiquette
function init_rfid_write_etiquette (cb,nbtags,ack_write) {

	if(!flag_rfid_active) return;
	write_etiquette_data.ack_write=ack_write;
	write_etiquette_data.cb=cb;

	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	var req_rfid = new http_request();	
	var url= url_serveur_rfid+"";		
	//cb="3005fb63ac1f3841ec88046700000000";
	//  cb="800000000000000000000163";
	var cb_to_write="";
	
	// Antivol actif
//	cb_to_write+='80'; 
	cb_to_write+='0'; 	
	for(var i=0;i<cb.length;i++){
		var code= cb.charCodeAt(i).toString(16);
		cb_to_write+=code.charAt(0)+code.charAt(1);
	}	
	for(var i_cpt=cb.length*2 +1;i_cpt<tag_size_memory;i_cpt++){
		cb_to_write+='0';
	}
	
	var cmd="<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:deis=\"http://deister.device.plugntrack.frequentiel.com/\"><soapenv:Header/><soapenv:Body><deis:changeID><!--Optional:--><arg0>"+cb_to_write+"</arg0></deis:changeID></soapenv:Body></soapenv:Envelope>"
	req_rfid.request(url,1,cmd,1,result_write,0,0);	
	
}


function result_write(retVal) {	
	
	var info=retVal.substring(retVal.indexOf("<return>")+8,retVal.indexOf("</return>"));

	if(info.indexOf("Error")>=0){	
		alert(info);
		if(f_ack_write)f_ack_write(1);					
	}else if(f_ack_write)f_ack_write(0);
}  
  
// Programme une carte lecteur
var write_patron_data=new Array();
function init_rfid_write_empr (cb,ack_write) {
	if(!flag_rfid_active) return;
	
	write_patron_data.ack_write=ack_write;
	write_patron_data.cb=cb;
	read_uid(rfid_write_patron_suite1); 
}  

function rfid_write_patron_suite1 (retVal) {
	if(!flag_rfid_active) return;
	f_ack_write_empr=write_patron_data.ack_write;
	
	if(!retVal.length) {
		alert('Aucune étiquette détectée!');
		return;
	} 
	if(retVal.length>1) {
		alert('Il n\'y a pas le nombre requis d\'étiquettes: '+retVal.length+' détectées pour une seule nécessaire !');
		return;
	}	
	//alert('xxxxxxxxxxxxxx');
	var uid=retVal[0];
	
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	var req_rfid = new http_request();	
	var url= url_serveur_rfid+"cmd=prog_patron&out=json&uid="+uid+"&cb="+write_patron_data.cb+"&bib_id"+LibraryCode;		
	req_rfid.request(url,0,"",1,write_patron_data.ack_write,0,0);
}

function result_write_empr(retVal) {	
	try{ 
		var ret=eval('(' + retVal + ')');
	} catch(e){console.log(e);}
		
	if(f_ack_write_empr)f_ack_write_empr(ret.error);
}     

// Active / désactive un antivol
function init_rfid_antivol (cb,level,ack_antivol) {
	if(!flag_rfid_active) return;
	f_ack_antivol=ack_antivol;
	
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	var req_rfid = new http_request();	
	var url= url_serveur_rfid+"";		
	
	if(level)    
		var cmd="<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:deis=\"http://deister.device.plugntrack.frequentiel.com/\"><soapenv:Header/><soapenv:Body><deis:setSecurityStatus><arg0>true</arg0></deis:setSecurityStatus></soapenv:Body></soapenv:Envelope>"
	else
		var cmd="<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:deis=\"http://deister.device.plugntrack.frequentiel.com/\"><soapenv:Header/><soapenv:Body><deis:setSecurityStatus><arg0>false</arg0></deis:setSecurityStatus></soapenv:Body></soapenv:Envelope>"
	req_rfid.request(url,1,cmd,1,result_ack_antivol,0,0);	
	
}  

function result_ack_antivol(retVal) {
	var statut;
	var info=retVal.substring(retVal.indexOf("<return>")+8,retVal.indexOf("</return>"));

	if(info.indexOf("Error")>=0){	
		alert(info);
		if(f_ack_antivol)f_ack_antivol(0);					
	}else if(f_ack_antivol)f_ack_antivol(1);
}  


// Active / désactive tous les antivols
var rfid_antivol_all_data=new Array();
function init_rfid_antivol_all (level,ack_antivol) {
	f_ack_antivol_all=ack_antivol;
	
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	var req_rfid = new http_request();	
	var url= url_serveur_rfid+"";		
	
	if(level)    
		var cmd="<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:deis=\"http://deister.device.plugntrack.frequentiel.com/\"><soapenv:Header/><soapenv:Body><deis:setSecurityStatus><arg0>true</arg0></deis:setSecurityStatus></soapenv:Body></soapenv:Envelope>"
	else
		var cmd="<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:deis=\"http://deister.device.plugntrack.frequentiel.com/\"><soapenv:Header/><soapenv:Body><deis:setSecurityStatus><arg0>false</arg0></deis:setSecurityStatus></soapenv:Body></soapenv:Envelope>"
	req_rfid.request(url,1,cmd,1,result_ack_antivol,0,0);	

}  


//Getinfo
function init_rfid_GetInfo() {
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	proxies.NedapRfidWebService.GetInfo.func = result_GetInfo;
	proxies.NedapRfidWebService.GetInfo(SerialPort);    
}	

function result_GetInfo(retVal) {
	var i, info;
	info='';
	for (i=0; i<retVal['astrReturnInfos'].length; i++) {
		info+=retVal['astrReturnInfos'][i] + '\n';
	}
	alert (info);
}  

function effacer_ligne_tableau(array, valueOrIndex){
  var output=[];
  var j=0;
  for(var i in array){
    if (i!=valueOrIndex){
      output[j]=array[i];
      j++;
    }
  }
  return output;
} 
          


function mode1_init_rfid_read_cb(empr_client,expl_client){	
	f_empr_client=empr_client;
	f_expl_client=expl_client;
	// RFID init
	try {
		netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	} catch (e) {
		alert(e);
	  	exit();
	}
	mode1_read_cb();
}






// Pour le prêt a la chaine mode1
var flag_read_mode1=0;

function mode1_read_cb() {		
	if(flag_read_mode1){setTimeout('mode1_read_cb()',1500); return;}
	flag_semaphore_rfid_read=1;
	if(!rfid_focus_active) {setTimeout('mode1_read_cb()',1500); return;}
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	var req_rfid = new http_request();	
	var url= url_serveur_rfid+"";		
	var cmd="<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:deis=\"http://deister.device.plugntrack.frequentiel.com/\"><soapenv:Header/><soapenv:Body><deis:readId/></soapenv:Body></soapenv:Envelope>"
	req_rfid.request(url,1,cmd,1,mode1_result_read_cb,0,0);	
	flag_read_mode1=1;
}

function mode1_result_read_cb (retVal) {
	var i;
	var array_cb_expl=new Array();
	var array_cb_empr=new Array();
	var array_cb_index=new Array();
	var array_cb_count=new Array();
	var array_cb_eas=new Array();
	var array_cb_uid=new Array();
	var nb_doc=0;
	var nb_patroncard=0;		
	flag_semaphore_rfid_read=0;	
	flag_read_mode1=0;
	
	var info=retVal.substring(retVal.indexOf("<return>")+8,retVal.indexOf("</return>"));
	if(info.indexOf("Error")<0){	
		// pas d'info si expl ou empr...
		// etat antivol
		info='0'+info;
		if(info.substring(0,1)== 8)		array_cb_eas[0]=1;
		else array_cb_eas[0]=0;
		
		// cb
		array_cb_expl[0]=hex2a(info.substring(2));
		// piece
		array_cb_index[0]=1;
		array_cb_count[0]=1;
		
		//array_cb_empr[0]=info;							
	}

	if(f_expl_client)	f_expl_client(array_cb_expl,array_cb_index,array_cb_count,array_cb_eas,array_cb_uid);
	if(f_empr_client)	f_empr_client(array_cb_empr);
}


function mode1_init_rfid_antivol(cb,level,ack_antivol) {
	
	f_ack_antivol=ack_antivol;
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	var req_rfid = new http_request();	
	var url= url_serveur_rfid+"";		
	if(level)    
		var cmd="<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:deis=\"http://deister.device.plugntrack.frequentiel.com/\"><soapenv:Header/><soapenv:Body><deis:setSecurityStatus><arg0>true</arg0></deis:setSecurityStatus></soapenv:Body></soapenv:Envelope>"
	else
		var cmd="<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:deis=\"http://deister.device.plugntrack.frequentiel.com/\"><soapenv:Header/><soapenv:Body><deis:setSecurityStatus><arg0>false</arg0></deis:setSecurityStatus></soapenv:Body></soapenv:Envelope>"
	req_rfid.request(url,1,cmd,1,result_ack_antivol,0,0);	
    
}  



function mode1_init_rfid_antivol_uid(uid,level,ack_antivol) {
	
	f_ack_antivol=ack_antivol;
	
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	var req_rfid = new http_request();	
	var url= url_serveur_rfid+"";		
	if(level)    
		var cmd="<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:deis=\"http://deister.device.plugntrack.frequentiel.com/\"><soapenv:Header/><soapenv:Body><deis:setSecurityStatus><arg0>false</arg0></deis:setSecurityStatus></soapenv:Body></soapenv:Envelope>"
	else
		var cmd="<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:deis=\"http://deister.device.plugntrack.frequentiel.com/\"><soapenv:Header/><soapenv:Body><deis:setSecurityStatus><arg0>true</arg0></deis:setSecurityStatus></soapenv:Body></soapenv:Envelope>"
	req_rfid.request(url,1,cmd,1,result_ack_antivol,0,0);	
}  







