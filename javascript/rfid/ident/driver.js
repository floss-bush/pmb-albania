// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: driver.js,v 1.7 2010-06-21 09:15:48 ngantier Exp $

var f_empr_client;
var f_expl_client;
var f_ack_write;	
var f_ack_erase;
var f_ack_detect;
var f_ack_write_empr;
var f_ack_antivol_all;
var f_ack_antivol;
var flag_semaphore_rfid=0;
var flag_semaphore_rfid_read=0;
var flag_rfid_active=1;
var rfid_active_test=1;
var rfid_active_test_exec=0;
var flag_semaphore_antivol=0;
var rfid_focus_active=1;

function init_rfid_read_cb(empr_client,expl_client){	
	f_empr_client=empr_client;
	f_expl_client=expl_client;
	// RFID init
	
	try {
		//netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
		/*
Dans pref.js , toutes les instances de Firefox fermées, rajouter:
user_pref("capability.policy.default.XMLHttpRequest.open", "allAccess");
user_pref("capability.policy.default.CDATASection.nodeValue", "allAccess");
user_pref("capability.policy.default.Element.attributes", "allAccess");
user_pref("capability.policy.default.Element.childNodes", "allAccess");
user_pref("capability.policy.default.Element.firstChild", "allAccess");
user_pref("capability.policy.default.Element.getElementsByTagName", "allAccess");
user_pref("capability.policy.default.Element.tagName", "allAccess");
user_pref("capability.policy.default.HTMLCollection.length", "allAccess");
user_pref("capability.policy.default.HTMLCollection.item", "allAccess");
user_pref("capability.policy.default.Text.nodeValue", "allAccess");
user_pref("capability.policy.default.XMLDocument.documentElement", "allAccess");
user_pref("capability.policy.default.XMLDocument.getElementsByTagName", "allAccess");
user_pref("capability.policy.default.XMLHttpRequest.channel", "allAccess");
user_pref("capability.policy.default.XMLHttpRequest.open", "allAccess");
user_pref("capability.policy.default.XMLHttpRequest.responseText", "allAccess");
user_pref("capability.policy.default.XMLHttpRequest.responseXML", "allAccess");
user_pref("capability.policy.default.XMLHttpRequest.send", "allAccess");
user_pref("capability.policy.default.XMLHttpRequest.setRequestHeader", "allAccess");
		
		
		*/
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
	read_cb_ex(); return;	
	if(!rfid_active_test_exec) {
		rfid_active_test_exec++;
		setTimeout('timeout()',4000);
	}
	if (flag_semaphore_rfid) {
		setTimeout('read_cb()',1500); 
		return;
	}
	if (flag_semaphore_antivol) {
		return;
	}
	flag_rfid_active_test=0;
	if(!rfid_focus_active) {setTimeout('read_cb()',1500); return;}	
	flag_semaphore_rfid_read=1;
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	proxies.RfidWebServices.Read.func = result_read_cb;
	proxies.RfidWebServices.Read(SerialPort);
}

function result_read_cb (retVal) {
	var i;
	var array_cb_expl=new Array();
	var array_cb_empr=new Array();
	flag_rfid_active_test=1;
	flag_rfid_active=1;
	// lecture de cb exemplaire
	if (retVal['astrDocumentIdentifiers']) {
		for (i=0; i<retVal['astrDocumentIdentifiers'].length; i++) {					
			array_cb_expl[i]=retVal['astrDocumentIdentifiers'][i];
		}
	}	
	// lecture de carte emprunteur
	if (retVal['astrPatronIdentifiers']) {				
		for (i=0; i<retVal['astrPatronIdentifiers'].length; i++) {				
			array_cb_empr[i]=retVal['astrPatronIdentifiers'][i];
		}	
	}	
	if(f_expl_client)	f_expl_client(array_cb_expl);
	if(f_empr_client)	f_empr_client(array_cb_empr);
	setTimeout('read_cb()',1500);
	flag_semaphore_rfid_read=0;
}
function read_cb_ex() {	
	if(flag_disable_antivol) {
		return;
	}
	if(!rfid_active_test_exec) {
		rfid_active_test_exec++;
		setTimeout('timeout()',4000);
	}
	if (flag_semaphore_rfid) {
		setTimeout('read_cb_ex()',1500); 
		return;
	}
	if (flag_semaphore_antivol) {
		return;
	}
	flag_rfid_active_test=0;
	flag_semaphore_rfid_read=1;
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	proxies.RfidWebServices.ReadEx.func = result_read_cb_ex;
	proxies.RfidWebServices.ReadEx(SerialPort);
}
function dump(arr,level) {
var dumped_text = "";
if(!level) level = 0;

//The padding given at the beginning of the line.
var level_padding = "";
for(var j=0;j<level+1;j++) level_padding += "    ";

if(typeof(arr) == 'object') { //Array/Hashes/Objects
 for(var item in arr) {
  var value = arr[item];
 
  if(typeof(value) == 'object') { //If it is an array,
   dumped_text += level_padding + "'" + item + "' ...\n";
   dumped_text += dump(value,level+1);
  } else {
   dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
  }
 }
} else { //Stings/Chars/Numbers etc.
 dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
}
return dumped_text;
} 

function result_read_cb_ex (retVal) {
	var i;
	var array_cb_expl=new Array();
	var array_cb_empr=new Array();
	var array_cb_index=new Array();
	var array_cb_count=new Array();
	var array_cb_eas=new Array();
	
	flag_rfid_active_test=1;
	flag_rfid_active=1;
	//alert(dump(retVal));

	// lecture de cb exemplaire
	if (retVal['aobjItems']) {
	
		for (i=0; i<retVal['aobjItems'].length; i++) {					
			array_cb_expl[i]=retVal['aobjItems'][i]['DocumentId'];
			array_cb_index[i]=retVal['aobjItems'][i]['ItemIndex'];
			array_cb_count[i]=retVal['aobjItems'][i]['ItemCount'];
			array_cb_eas[i]=retVal['aobjItems'][i]['EasState'];
		}
	}	
	// lecture de carte emprunteur
	if (retVal['astrPatronIdentifiers']) {				
		for (i=0; i<retVal['astrPatronIdentifiers'].length; i++) {				
			array_cb_empr[i]=retVal['astrPatronIdentifiers'][i];
		}	
	}		
	if(f_expl_client)	f_expl_client(array_cb_expl,array_cb_index,array_cb_count,array_cb_eas);
	if(f_empr_client)	f_empr_client(array_cb_empr);
	setTimeout('read_cb()',1500);
	flag_semaphore_rfid_read=0;
}
// Detect le nombre d'élement rfid
function init_rfid_detect(ack_detect) {
	if(!flag_rfid_active) return;
	f_ack_detect=ack_detect;
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	proxies.RfidWebServices.DetectTag.func = result_detect;
	proxies.RfidWebServices.DetectTag(SerialPort);    
}	
function result_detect(retVal) {
	if(f_ack_detect)f_ack_detect(retVal['bOneOrMoreTagsDetected']);
}

// Efface tout !!!
function init_rfid_erase(ack_erase) {
	f_ack_erase=ack_erase;
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	proxies.RfidWebServices.EraseAllTags.func = result_erase;
	proxies.RfidWebServices.EraseAllTags(SerialPort);    
}	
function result_erase(retVal) {
	if(f_ack_erase)f_ack_erase(retVal['EraseAllTagsResult']);
}
	
// Programme une étiquette
function init_rfid_write_etiquette (cb,nbtags,ack_write) {
	if(!flag_rfid_active) return;
	f_ack_write=ack_write;
	if(!nbtags)nbtags=1;
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	proxies.RfidWebServices.WriteDocument.func = result_write;
	proxies.RfidWebServices.WriteDocument(SerialPort,cb,nbtags);
}
function result_write(retVal) {
	if(f_ack_write)f_ack_write(retVal['byNumberOfTags']);
}  
  
// Programme une carte lecteur
function init_rfid_write_empr (cb,ack_write) {
	if(!flag_rfid_active) return;
	f_ack_write_empr=ack_write;
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	proxies.RfidWebServices.WritePatron.func = result_write_empr;
	proxies.RfidWebServices.WritePatron(SerialPort,cb);         
}  
function result_write_empr(retVal) {
	if(f_ack_write_empr)f_ack_write_empr(retVal['WritePatronResult']);
}     

// Active / désactive un antivol
function init_rfid_antivol (cb,level,ack_antivol) {
	if(!flag_rfid_active) return;
	f_ack_antivol=ack_antivol;
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	proxies.RfidWebServices.SetEas.func = result_ack_antivol;
	proxies.RfidWebServices.SetEas(SerialPort,cb,level);         
}  
function result_ack_antivol(retVal) {
	var statut;
	if(retVal['SetEasResult']==0) statut=1;
	else statut=0;//<0
	if(f_ack_antivol)f_ack_antivol(statut);
}  

// Active / désactive tous les antivols
function init_rfid_antivol_all (level,ack_antivol) {
	f_ack_antivol_all=ack_antivol;
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	proxies.RfidWebServices.SetAllEas.func = result_ack_antivol_all;
	proxies.RfidWebServices.SetAllEas(SerialPort,level);         
}  
function result_ack_antivol_all(retVal) {
	var statut;
	if(retVal['SetAllEasResult']==0) statut=1;
	else statut=0;
	if(f_ack_antivol_all)f_ack_antivol_all(statut);
}      
 
//Getinfo
function init_rfid_GetInfo() {
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	proxies.RfidWebServices.GetInfo.func = result_GetInfo;
	proxies.RfidWebServices.GetInfo(SerialPort);    
}	
function result_GetInfo(retVal) {
	var i, info;
	info='';
	for (i=0; i<retVal['astrReturnInfos'].length; i++) {
		info+=retVal['astrReturnInfos'][i] + '\n';
	}
	alert (info);
}   
          




//Pour le prêt a la chaine mode1

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



function mode1_read_cb() {		
	flag_semaphore_rfid_read=1;
	if(!rfid_focus_active) {setTimeout('mode1_read_cb()',1500); return;}
	netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead');
	proxies.RfidWebServices.ReadEx.func = mode1_result_read_cb;
	proxies.RfidWebServices.ReadEx(SerialPort);
	
}

function mode1_result_read_cb (retVal) {
	var i;
	var array_cb_expl=new Array();
	var array_cb_empr=new Array();
	var array_cb_index=new Array();
	var array_cb_count=new Array();
	var array_cb_eas=new Array();

	var array_cb_uid=new Array();
	
	flag_semaphore_rfid_read=0;
	// lecture de cb exemplaire
	if (retVal['aobjItems']) {
	
		for (i=0; i<retVal['aobjItems'].length; i++) {					
			array_cb_expl[i]=retVal['aobjItems'][i]['DocumentId'];
			array_cb_index[i]=retVal['aobjItems'][i]['ItemIndex'];
			array_cb_count[i]=retVal['aobjItems'][i]['ItemCount'];
			array_cb_eas[i]=retVal['aobjItems'][i]['EasState'];
		}
	}	
	// lecture de carte emprunteur
	if (retVal['astrPatronIdentifiers']) {				
		for (i=0; i<retVal['astrPatronIdentifiers'].length; i++) {				
			array_cb_empr[i]=retVal['astrPatronIdentifiers'][i];
		}	
	}		
	if(f_expl_client)	f_expl_client(array_cb_expl,array_cb_index,array_cb_count,array_cb_eas,array_cb_uid);
	if(f_empr_client)	f_empr_client(array_cb_empr);
}

