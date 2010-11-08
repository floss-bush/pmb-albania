// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: driver.js,v 1.13 2010-06-16 12:19:04 ngantier Exp $

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
	proxies.NedapRfidWebService.readLabel.func = result_read_cb;
	proxies.NedapRfidWebService.readLabel(SerialPort,200,true);
	
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
	//alert(dump(retVal));

	// lecture de cb exemplaire
	
	for (i=0; i<proxies_response_xml.length; i++) {		
		if(proxies_response_xml[i]['DocumentNumber']) {
			if(proxies_response_xml[i]['Usage']=="PatronCard"){
				array_cb_empr[nb_patroncard++]=proxies_response_xml[i]['DocumentNumber'];
			} else { 
				array_cb_expl[nb_doc]=proxies_response_xml[i]['DocumentNumber'];			
				array_cb_index[nb_doc]=proxies_response_xml[i]['ItemNumber'];
				array_cb_count[nb_doc]=proxies_response_xml[i]['TotalItems'];
			//	array_cb_eas[nb_doc]=proxies_response_xml[i]['TypeEAS'];	
				nb_doc++;								
			}		
		}
	}
			
	if(f_expl_client)	f_expl_client(array_cb_expl,array_cb_index,array_cb_count,array_cb_eas);
	if(f_empr_client)	f_empr_client(array_cb_empr);
	setTimeout('read_cb()',1500);
	flag_semaphore_rfid_read=0;
}

/*
function result_read_cb (retVal) {
	var i;
	var array_cb_expl=new Array();
	var array_cb_empr=new Array();
	var nb_doc=0;
	var nb_patroncard=0;
	flag_rfid_active_test=1;
	flag_rfid_active=1;
	// proxies_response_xml a la place de retval
	// lecture de cb exemplaire
	
	for (i=0; i<proxies_response_xml.length; i++) {		
		if(proxies_response_xml[i]['DocumentNumber']) {
			if(proxies_response_xml[i]['Usage']=="PatronCard"){
				array_cb_empr[nb_patroncard++]=proxies_response_xml[i]['DocumentNumber'];
			} else { 
				array_cb_expl[nb_doc++]=proxies_response_xml[i]['DocumentNumber'];								
			}		
		}
	}
	if(f_expl_client)	f_expl_client(array_cb_expl);
	if(f_empr_client)	f_empr_client(array_cb_empr);
	setTimeout('read_cb()',2000);
	flag_semaphore_rfid_read=0;
}
*/
function read_uid(f_ack) {	

	flag_rfid_active_test=0;
	flag_semaphore_rfid_read=1;
	f_ack_read_uid=f_ack;
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	proxies.NedapRfidWebService.readLabel.func = result_read_uid;
	proxies.NedapRfidWebService.readLabel(SerialPort,200,true);
}

function result_read_uid (retVal) {
	var i;
	var nb_doc=0;
	var liste_uid=new Array();
	flag_rfid_active_test=1;
	flag_rfid_active=1;
	
	for (i=0; i<proxies_response_xml.length; i++) {						
		liste_uid[nb_doc++]=proxies_response_xml[i]['UID'];
	}
	flag_semaphore_rfid_read=0;
	if(f_ack_read_uid) f_ack_read_uid(liste_uid);
}

// Detect présence d'élement rfid
function init_rfid_detect(ack_detect) {
	if(!flag_rfid_active) return;
	f_ack_detect=ack_detect;
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	proxies.NedapRfidWebService.readLabel.func = result_detect;
	proxies.NedapRfidWebService.readLabel(SerialPort,300,true);  
}	
function result_detect(retVal) {
	var flag;
	flag=false;
	if(proxies_response_xml.length) flag=true;	 
	if(f_ack_detect)f_ack_detect(flag);
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
	if(!nbtags)nbtags=1;
	write_etiquette_data.nbtags=nbtags;
	read_uid(rfid_write_etiquette_suite1); 
    
}

function rfid_write_etiquette_suite1 (retVal) {
	if(!flag_rfid_active) return;
	f_ack_write=write_etiquette_data.ack_write;
	
	if(!proxies_response_xml.length) {
		alert('Aucune étiquette détectée!');
		return;
	}else if(proxies_response_xml.length != write_etiquette_data.nbtags) {
		alert('Il y a plusieurs étiquettes !'+proxies_response_xml.length+' ' + write_etiquette_data.nbtags);
		return;
	} 
	write_etiquette_data.proxies_response_xml=proxies_response_xml;
	write_etiquette_data.ptr_nb=1;
	var uid=proxies_response_xml[0]['UID'];
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	proxies.NedapRfidWebService.WriteFrenchLabel_native.func = result_write;
	proxies.NedapRfidWebService.WriteFrenchLabel_native(SerialPort,200,uid,write_etiquette_data.cb,LibraryCode,write_etiquette_data.ptr_nb, write_etiquette_data.nbtags,0,1,'','','','','');  
	       
}

function result_write(retVal) {
	if(write_etiquette_data.ptr_nb < write_etiquette_data.proxies_response_xml.length) {		
		var uid=write_etiquette_data.proxies_response_xml[write_etiquette_data.ptr_nb]['UID'];
		write_etiquette_data.ptr_nb++;  
		netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
		proxies.NedapRfidWebService.WriteFrenchLabel_native.func = result_write;
		proxies.NedapRfidWebService.WriteFrenchLabel_native(SerialPort,200,uid,write_etiquette_data.cb,LibraryCode,write_etiquette_data.ptr_nb, write_etiquette_data.nbtags,0,1,'','','','','');
		return;
	}

	if(f_ack_write)f_ack_write(retVal['WriteFrenchLabel_nativeResult']);
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
	
	if(proxies_response_xml.length != 1) {
		if(proxies_response_xml.length > 1) alert('Il y a plusieurs étiquettes !');
		else if(proxies_response_xml.length == 0) alert('Aucune étiquette détectée!');
		return;
	}	
	var uid=proxies_response_xml[0]['UID'];
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	proxies.NedapRfidWebService.WriteFrenchLabel_native.func = result_write_empr;
	proxies.NedapRfidWebService.WriteFrenchLabel_native(SerialPort,200,uid,write_patron_data.cb,LibraryCode,1,1,1,1,'','','','','');  
	       
}

function result_write_empr(retVal) {
	if(f_ack_write_empr)f_ack_write_empr(retVal['WriteFrenchLabel_nativeResult']);
}     

// Active / désactive un antivol
function init_rfid_antivol (cb,level,ack_antivol) {
	if(!flag_rfid_active) return;
	f_ack_antivol=ack_antivol;
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	if(level) {
		proxies.NedapRfidWebService.EnableBarcodeEAS.func = result_ack_antivol;
		proxies.NedapRfidWebService.EnableBarcodeEAS(SerialPort,200,cb);    
	} else {
		proxies.NedapRfidWebService.DisableBarcodeEAS.func = result_ack_antivol_disable;
		proxies.NedapRfidWebService.DisableBarcodeEAS(SerialPort,200,cb);    
	}	    
}  

function result_ack_antivol(retVal) {
	var statut;
	if (retVal['EnableBarcodeEASResult']!='true') statut=0;
	else statut=1;
	if(f_ack_antivol)f_ack_antivol(statut);
}  

function result_ack_antivol_disable(retVal) {
	var statut;
	if (retVal['DisableBarcodeEASResult']!='true') statut=0;
	else statut=1;
	if(f_ack_antivol)f_ack_antivol(statut);
}  

// Active / désactive tous les antivols
var rfid_antivol_all_data=new Array();
function init_rfid_antivol_all (level,ack_antivol) {
	f_ack_antivol_all=ack_antivol;
	
	rfid_antivol_all_data.ack=ack_antivol;
	rfid_antivol_all_data.level=level;
	read_uid(rfid_antivol_all_suite1);    
}  

function rfid_antivol_all_suite1(){
	var i;
	rfid_antivol_all_data.cbliste=new Array();
	var nb_cb=0;
	for(i=0;i<proxies_response_xml.length;i++) {
		if(proxies_response_xml[i]['Usage']=="ProductTag"){
			if(proxies_response_xml[i]['DocumentNumber']){
				rfid_antivol_all_data.cbliste[nb_cb++]=	proxies_response_xml[i]['DocumentNumber']
			}	
		}		
	}	
	if(nb_cb == 0){
		alert('Aucune étiquette détectée!');
		if(f_ack_antivol_all)f_ack_antivol_all(0);
		return;
	}		
	rfid_antivol_all_suite2();
} 
function rfid_antivol_all_suite2(){
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	if(rfid_antivol_all_data.level) {
		proxies.NedapRfidWebService.EnableBarcodeEAS.func = result_ack_antivol_all;
		proxies.NedapRfidWebService.EnableBarcodeEAS(SerialPort,200,rfid_antivol_all_data.cbliste[0]);    
	} else {
		proxies.NedapRfidWebService.DisableBarcodeEAS.func = result_ack_antivol_disable2;
		proxies.NedapRfidWebService.DisableBarcodeEAS(SerialPort,200,rfid_antivol_all_data.cbliste[0]);    
	}	
	rfid_antivol_all_data.cbliste = effacer_ligne_tableau(rfid_antivol_all_data.cbliste,0);  
} 

function result_ack_antivol_all(retVal) {
	if (rfid_antivol_all_data.cbliste.length) {
		rfid_antivol_all_suite2();
		return;
	}	
	if(f_ack_antivol_all)f_ack_antivol_all(retVal['EnableBarcodeEASResult']);
} 

function result_ack_antivol_disable2(retVal) {
	if (rfid_antivol_all_data.cbliste.length) {
		rfid_antivol_all_suite2();
		return;
	}	
	if(f_ack_antivol_all)f_ack_antivol_all(retVal['DisableBarcodeEASResult']);
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

function mode1_read_cb() {		
	flag_semaphore_rfid_read=1;
	if(!rfid_focus_active) {setTimeout('mode1_read_cb()',1500); return;}
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	proxies.NedapRfidWebService.readLabel.func = mode1_result_read_cb;
	proxies.NedapRfidWebService.readLabel(SerialPort,200,true);	
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
	// lecture de cb exemplaire	
	for (i=0; i<proxies_response_xml.length; i++) {		
		if(proxies_response_xml[i]['DocumentNumber']) {
			if(proxies_response_xml[i]['Usage']=="PatronCard"){
				array_cb_empr[nb_patroncard++]=proxies_response_xml[i]['DocumentNumber'];
			} else { 
				array_cb_expl[nb_doc]=proxies_response_xml[i]['DocumentNumber'];			
				array_cb_index[nb_doc]=proxies_response_xml[i]['ItemNumber'];
				array_cb_count[nb_doc]=proxies_response_xml[i]['TotalItems'];
				array_cb_uid[nb_doc]=proxies_response_xml[i]['UID'];	
			//	array_cb_eas[nb_doc]=proxies_response_xml[i]['TypeEAS'];	
				nb_doc++;								
			}		
		}
	}	
	if(f_expl_client)	f_expl_client(array_cb_expl,array_cb_index,array_cb_count,array_cb_eas,array_cb_uid);
	if(f_empr_client)	f_empr_client(array_cb_empr);
}


function mode1_init_rfid_antivol(cb,level,ack_antivol) {
	
	f_ack_antivol=ack_antivol;
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	if(level) {
		proxies.NedapRfidWebService.EnableBarcodeEAS.func = mode1_result_ack_antivol;
		proxies.NedapRfidWebService.EnableBarcodeEAS(SerialPort,200,cb);    
	} else {
		proxies.NedapRfidWebService.DisableBarcodeEAS.func = mode1_result_ack_antivol_disable;
		proxies.NedapRfidWebService.DisableBarcodeEAS(SerialPort,200,cb);    
	}	    
}  

function mode1_result_ack_antivol(retVal) {
	var statut;
	if (retVal['EnableBarcodeEASResult']!='true') statut=0;
	else statut=1;
	if(f_ack_antivol)f_ack_antivol(statut);
}  

function mode1_result_ack_antivol_disable(retVal) {
	var statut;
	if (retVal['DisableBarcodeEASResult']!='true') statut=0;
	else statut=1;
	if(f_ack_antivol)f_ack_antivol(statut);
}  




function mode1_init_rfid_antivol_uid(uid,level,ack_antivol) {
	
	f_ack_antivol=ack_antivol;
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	if(level) {
		proxies.NedapRfidWebService.EnableEAS.func = mode1_result_ack_antivol_uid;
		proxies.NedapRfidWebService.EnableEAS(SerialPort,200,uid);    
	} else {
		proxies.NedapRfidWebService.DisableEAS.func = mode1_result_ack_antivol_disable_uid;
		proxies.NedapRfidWebService.DisableEAS(SerialPort,200,uid);    
	}	    
}  

function mode1_result_ack_antivol_uid(retVal) {
	var statut;
	if (retVal['EnableEASResult']!='true') statut=0;
	else statut=1;
	if(f_ack_antivol)f_ack_antivol(statut);
}  

function mode1_result_ack_antivol_disable_uid(retVal) {
	var statut;
	if (retVal['DisableEASResult']!='true') statut=0;
	else statut=1;
	if(f_ack_antivol)f_ack_antivol(statut);
}  





