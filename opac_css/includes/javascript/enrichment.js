// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: enrichment.js,v 1.3.2.4 2011-06-24 08:16:03 arenou Exp $

function getEnrichment(notice_id){
	var ul = findNoticeElement(notice_id);
	if(ul && !ul.getAttribute("enrichment")){
		var check = new http_request();
		check.request('./ajax.php?module=ajax&categ=enrichment&action=gettype&id='+notice_id,false,"",true,gotTypeOfEnrichment);
		ul.setAttribute("enrichment","done");
	}
}

function gotTypeOfEnrichment(response){
	var types = eval('('+response+')');
	var menu = new Array();
	var ul = findNoticeElement(types.notice_id);
	for (key in types.result){
		for(var i=0 ; i< types.result[key].type.length ; i++){
			var exist = false;
			for(var j=0 ; j< menu.length ; j++){
				if(menu[j].code == types.result[key].type[i].code && menu[j].label == types.result[key].type[i].label){
					exist = true;
					break;
				}
			}
			if(!exist){
				createMenuItem(types.result[key].type[i],types.notice_id,ul);
				var div = document.createElement("div");
				div.setAttribute("id","div_"+types.result[key].type[i].code+types.notice_id);
				div.setAttribute("style","display:none");
				ul.parentNode.appendChild(div);
				menu.push(types.result[key].type[i]);
			}
		}
	}
}

function gotEnrichment(response){
	var enrichment = eval('('+response+')');
	for (key in enrichment.result){
		for(type in enrichment.result[key]){
			if(type != "source_label"){
				var content = document.getElementById("div_"+type+enrichment.notice_id);
				content.innerHTML += "" +
				"<div class='row'>&nbsp;</div>" +
				"<div class='row'>" +
				"<span style='font-weight:bold;'>"+enrichment.result[key].source_label+"</span>" +
				"</div>" +
				"<div class='row'>"+enrichment.result[key][type].content+"</div>";
				if(enrichment.result[key][type].callback){
					eval(enrichment.result[key][type].callback);
				}
			}
		}
	}
	//on supprime l'image de chargement
	var img = document.getElementById('patience'+enrichment.notice_id);
	if(img) img.parentNode.removeChild(img);
}

function createMenuItem(item,notice_id,parent){	
	var li = document.createElement("li");
	li.setAttribute("id","onglet_"+item.code+notice_id);
	li.setAttribute("class","isbd_public_inactive");
	var a = document.createElement("a");
	a.innerHTML = item.label;
	a.setAttribute("href", "#");
	a.setAttribute("onclick", "return false;");
	li.appendChild(a),
	parent.appendChild(li);
	if(a.addEventListener){
		a.addEventListener("click",function(){
			show_what(item.code,notice_id);
			if(!a.hasAttribute('enrichment')){
				a.setAttribute('enrichment','done');
				var patience= document.createElement("img");
				patience.setAttribute('src',"images/patience.gif");
				patience.setAttribute('align','middle');
				patience.setAttribute('id','patience'+notice_id);
				a.parentNode.appendChild(patience);
				var check = new http_request();
				check.request('./ajax.php?module=ajax&categ=enrichment&action=enrichment&type='+item.code+'&id='+notice_id,false,"",true,gotEnrichment);
			}
			return false;	
		},false);		
	} else if (a.attachEvent) {
		a.attachEvent("onclick",function(){
			show_what(item.code,notice_id);
			if(!a.getAttribute('enrichment')){
				a.setAttribute('enrichment','done');
				var patience= document.createElement("img");
				patience.setAttribute('src',"images/patience.gif");
				patience.setAttribute('align','middle');
				patience.setAttribute('id','patience'+notice_id);
				a.parentNode.appendChild(patience);
				var check = new http_request();
				check.request('./ajax.php?module=ajax&categ=enrichment&action=enrichment&type='+item.code+'&id='+notice_id,false,"",true,gotEnrichment);
			}
			return false;	
		});
	}
}

function findNoticeElement(id){
	//cas des notices classiques
	var domNotice = document.getElementById("el"+id+'Child');
	//notice_display
	if(!domNotice) domNotice = document.getElementById("notice");
	if(domNotice){
		var uls = domNotice.getElementsByTagName('ul');
		for (var i=0 ; i<uls.length ; i++){
			if(uls[i].getAttribute('id') == "onglets_isbd_public"){
				var ul = uls[i];
				break;
			}
		}
	} else{
		var li = document.getElementById("onglet_isbd"+id);
		if(!li) var li = document.getElementById("onglet_public"+id);
		var ul = li.parentNode;
	}
	return ul;
}

// la méthode show_what... mais en mieux...
function show_what(quoi, id) {
	switch(quoi){
		case "EXPL_LOC" :
			document.getElementById('div_expl_loc' + id).style.display = 'block';
			document.getElementById('div_expl' + id).style.display = 'none';		
			document.getElementById('onglet_expl' + id).className = 'isbd_public_inactive';		
			document.getElementById('onglet_expl_loc' + id).className = 'isbd_public_active';
			break;
		case "EXPL" :
			document.getElementById('div_expl_loc' + id).style.display = 'none';
			document.getElementById('div_expl' + id).style.display = 'block';
			document.getElementById('onglet_expl' + id).className = 'isbd_public_active';
			document.getElementById('onglet_expl_loc' + id).className = 'isbd_public_inactive';
			break;
		default :
			quoi= quoi.toLowerCase();
			var ul = findNoticeElement(id);
			var items  = ul.getElementsByTagName('li');
			for (var i=0 ; i<items.length ; i++){
				if(items[i].getAttribute("id") == "onglet_"+quoi+id){
					items[i].className = "isbd_public_active";
					document.getElementById("div_"+quoi+id).style.display = "block";
				}else{
					if(items[i].className != "onglet_tags" && items[i].className != "onglet_basket"){
						items[i].className = "isbd_public_inactive";	
						document.getElementById(items[i].getAttribute("id").replace("onglet","div")).style.display = "none";
					}
				}
			}			
			break;
	}
}