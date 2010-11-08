// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: requests_frame.js,v 1.6 2009-06-25 16:33:15 dbellamy Exp $

/*
 * Nécessite drag_n_drop.js
 * Necessite select.js
 */

//Un peu de calcul sur la hauteur des div dans la frame !!!
function req_setFrameSize(){
	var fh=getWindowHeight();
	var pos=findPos(document.getElementById('req_tree_div'));
	var row_2=document.getElementById('row_2');
	var row_2h=fh-(pos[1]*2);
	row_2.style.height=row_2h+'px';
}

req_setFrameSize();

var req_nb_lines=0;
var req_cur_line=0;
var joi_link= new Array();

var min_length=0;

var up_icon=new Image();
up_icon.src='../../images/up.png';
var down_icon=new Image();
down_icon.src='../../images/down.png';
var remove_icon=new Image();
remove_icon.src='../../images/cross.png';
var up_sort_icon=new Image();
up_sort_icon.src='../../images/fleche_diago_haut.png';
var down_sort_icon=new Image();
down_sort_icon.src='../../images/fleche_diago_bas.png';
var no_sort_icon=new Image();
no_sort_icon.src='../../images/stop.png';
var move_icon=new Image();
move_icon.src ='../../images/drag_symbol.png';
var add_icon=new Image();
add_icon.src ='../../images/add.png';
var pen_icon=new Image();
pen_icon.src ='../../images/b_edit.png';


//Modification drag_n_drop.js
drag_icon.src='../../images/drag_symbol.png';
drag_empty_icon.scr='../../images/drag_empty_symbol.png';


var att=document.createElement('DIV');
att.setAttribute('id','att');
att.style.zIndex='1000';


var req_tab=document.getElementById('req_tab'); 

var tpost= new Array();
var idx=0;


//Ajout ligne requete
function req_addLine() {
	var req_type=parent.document.getElementById('req_type');
	var tr=req_addReqLine(req_type.value);
	req_tab.appendChild(tr);
	parse_drag(tr);
}

//Ajout ligne requete (suite)
function req_addReqLine(req_type) {

	req_nb_lines++;
	req_cur_line++;
	
	var tr;
	var td1;
	var td2;
	var td3;
	var td4;
	var td5;
	var td6;
	var td7;

	switch (req_type) {

		case '2' :	//Selection

			tr=req_addRow(req_cur_line);

			td1=req_addDataCell(req_cur_line);
			tr.appendChild(td1);	
		
			td2=req_addFilterCell(req_cur_line);
			tr.appendChild(td2);

			td3=req_addAliasCell(req_cur_line);
			tr.appendChild(td3);

			td4=req_addVisibilityCell(req_cur_line);
			tr.appendChild(td4);

			td5=req_addGroupCell(req_cur_line);
			tr.appendChild(td5);

			td6=req_addSortCell(req_cur_line);
			tr.appendChild(td6);

			td7=req_addActionCell(req_cur_line);
			tr.appendChild(td7);

			break;

		case '3' :	//Insertion

			tr=req_addRow(req_cur_line);
			
			td1=req_addDataCell(req_cur_line);
			tr.appendChild(td1);	

			td2=req_addValueCell(req_cur_line);			
			tr.appendChild(td2);

			td3=req_addMandatoryCell(req_cur_line);			
			tr.appendChild(td3);
			
			td4=req_addActionCell(req_cur_line);
			tr.appendChild(td4);
			
			break;

		case '4' :	//Mise a jour

			tr=req_addRow(req_cur_line);

			td1=req_addDataCell(req_cur_line);
			tr.appendChild(td1);

			td2=req_addValueCell(req_cur_line);			
			tr.appendChild(td2);

			td3=req_addFilterCell(req_cur_line);
			tr.appendChild(td3);

			td4=req_addActionCell(req_cur_line);
			tr.appendChild(td4);
			
			break;

		case '5' :	//Suppression

			tr=req_addRow(req_cur_line);
	
			td1=req_addDataCell(req_cur_line);
			tr.appendChild(td1);

			td2=req_addFilterCell(req_cur_line);
			tr.appendChild(td2);

			td3=req_addActionCell(req_cur_line);
			tr.appendChild(td3);
			
			break;

		default :
			break;
	}
	return tr;
}

//Ajout ligne
function req_addRow(lig){

	var tr=document.createElement('TR');
	tr.setAttribute('id','R_'+lig);
	tr.setAttribute('recept','yes');
	tr.setAttribute('recepttype','row');
	tr.setAttribute('highlight','row_highlight');
	tr.setAttribute('downlight','row_downlight');
	tr.setAttribute('draggable','yes');
	tr.setAttribute('dragtype','row');
	tr.setAttribute('handler','R_HA_'+lig);
	tr.setAttribute('dragtext',msg_move);
	return tr;
}

//Ajout cellule donnees
function req_addDataCell(lig) {
	
	var td=document.createElement('TD');
	td.setAttribute('id','C_DA_'+lig);
	td.setAttribute('name','DA');
	var d=req_addContainer();
	td.appendChild(d);
	return td;
}

//Ajout Sélecteur
function req_addSelect(order,values) {
	var d=req_addContainer('no',order);
	var t_values = values.split(",");
	var s=document.createElement('select');
	s.setAttribute('name','SE');
	for(var j=0;j<t_values.length;j++){
		var o=document.createElement('option');
		o.text=t_values[j];
		try{
			s.add(o,null);
		}catch(err) {
			s.add(o);
		}
	}
	d.appendChild(s);
	s.selectedIndex=0;
	return d;
}

function req_addContainer(recept,order,text) {
	
	var d=document.createElement('DIV');
	if(recept!='no'){
		idx++;
		d.setAttribute('id','DC_'+idx);
		d.setAttribute('name','DC');
		d.setAttribute('recept','yes');
		d.setAttribute('recepttype','cell');
		d.setAttribute('highlight','cell_highlight');
		d.setAttribute('downlight','cell_downlight');
		d.appendChild(req_addPen());
	}
	if(order!=null && order!='') {
		d.setAttribute('order',order);
	}	
	if(recept=='no' && text!=null && text!='') {
		d.innerHTML=text;
	}
	d.className='req_container';
	return d;
}

//Ajout nouveau container dans cellule 
function req_addNextContainer() {
	
	var par=this.parentNode;
	var c=req_addContainer(null);
	par.insertBefore(c,this);
	
	var a1=document.createElement('IMG');
	a1.setAttribute('src',remove_icon.src);
	a1.className='bt_add_arg';
	a1.onclick=req_removeNextContainer;
	par.insertBefore(a1,c);
	
	var a2=this.cloneNode(false);
	a2.onclick=req_addNextContainer;
	par.insertBefore(a2,a1);

	init_recept();
}

//Suppression container dans cellule
function req_removeNextContainer() {
	
	var prev=this.previousSibling;
	var par=this.parentNode;
	for(n=0;n<3;n++) {
		var elt=prev.nextSibling;
		req_removeChilds(elt);
		par.removeChild(elt);
	}
	init_recept();
}


//Ajout cellule filtre
function req_addFilterCell(lig) {
	
	var td=document.createElement('TD');
	td.setAttribute('id','C_FI_'+lig);
	td.setAttribute('name','FI');
	var d=req_addContainer();
	td.appendChild(d);

	var a=document.createElement('IMG');
	a.setAttribute('src',add_icon.src);
	a.className='bt_add_arg';
	a.onclick=req_addNextContainer;
	td.appendChild(a);

	return td;
}

//Ajout cellule alias
function req_addAliasCell(lig) {

	var td=document.createElement('TD');
	td.setAttribute('name','AL');
	var i=document.createElement('INPUT');
	i.setAttribute('type','text');
	i.className='in_cell';
	i.value='';
	td.appendChild(i);
	return td;
}

//Ajout champ visibilite
function req_addVisibilityCell(lig) {

	var td=document.createElement('TD');
	td.setAttribute('name','VI');
	var i=document.createElement('INPUT');
	i.setAttribute('type','checkbox');
	i.value='1';
	i.checked=true;
	td.appendChild(i);
	return td;
}

//Ajout cellule regroupement
function req_addGroupCell(lig) {
	
	var td=document.createElement('TD');
	td.setAttribute('name','GR');
	var i=document.createElement('INPUT');
	i.setAttribute('type','checkbox');
	i.value='1'
	td.appendChild(i);
	return td;
}

//Ajout cellule tri
function req_addSortCell(lig) {

	var td=document.createElement('TD');
	td.setAttribute('name','SO');
	var i1=document.createElement('INPUT');
	i1.setAttribute('type','hidden');
	i1.value='0';
	td.appendChild(i1);
	var i2=document.createElement('IMG');
	i2.setAttribute('src',no_sort_icon.src);
	i2.className='stop_bt';
	i2.onclick=req_switchSort;
	td.appendChild(i2);
	return td;
}

//Ajout cellule action
function req_addActionCell(lig) {

	var td=document.createElement('TD');
	var i1=document.createElement('IMG');
	i1.setAttribute('src',up_icon.src);
	i1.className='up_bt';
	i1.onclick=moveLineUp;
	td.appendChild(i1);
	var i2=document.createElement('IMG');
	i2.setAttribute('src',down_icon.src);
	i2.className='down_bt';
	i2.onclick=moveLineDown;
	td.appendChild(i2);
	var i3=document.createElement('IMG');
	i3.setAttribute('src',remove_icon.src);
	i3.className='cross_bt';
	i3.onclick=req_removeReqLine;
	td.appendChild(i3);			
	var i4=document.createElement('IMG');
	i4.setAttribute('src',move_icon.src);
	i4.setAttribute('id','R_HA_'+lig);
	i4.className='req_row_handler';
	td.appendChild(i4);
	return td;
}

//Ajout cellule valeur
function req_addValueCell(lig){ 

	var td=document.createElement('TD');
	td.setAttribute('id','C_VA_'+lig);
	td.setAttribute('name','VA');
	var d=req_addContainer();
	td.appendChild(d);
	return td;

}

//Ajout cellule obligatoire 
function req_addMandatoryCell(lig) {

	var td=document.createElement('TD');
	td.setAttribute('name','MA');
	var i=document.createElement('INPUT');
	i.setAttribute('type','checkbox');
	i.setAttribute('unchecked','unchecked');
	i.setAttribute('disabled','disabled');
	td.appendChild(i);
	return td;
}

//Ajout bouton saisie libre
function req_addPen() {
	var i=document.createElement('IMG');
	i.setAttribute('src',pen_icon.src);
	i.className='bt_pen';
	i.onclick=req_addTextZone;
	return i;	
}

//Ajout zone de texte
function req_addTextZone() {

	var elt=this;
	var par=elt.parentNode;
	//Champ info visible
	var vi=document.createElement('INPUT');
	vi.setAttribute('type','text');
	vi.setAttribute('name','TE');
	vi.className='in_cell_text';
	vi.value='';
	
	//Bouton effacer
	elt.setAttribute('src',remove_icon.src);
	elt.className='bt_cross_elt';
	elt.onclick=req_clearCell;

	par.appendChild(vi);
	par.setAttribute('recept','no');
	//Maj drag_n_drop
	init_recept();
	cell_downlight(par);
	//focus
	vi.focus();
}

//Suppression ligne de requete
function req_removeReqLine(){

	var cr=this.parentNode.parentNode;
	var i=cr.rowIndex;
	req_removeChilds(cr);
	var tab=cr.parentNode;
	tab.deleteRow(i);
	var req_nb_lines=document.getElementById('req_nb_lines');
	req_nb_lines--;
	init_recept();
}

//Changement tri
function req_switchSort() {
	
	var elt = this.previousSibling;
	switch (elt.value) {
		case '0' :	
			this.setAttribute('src',up_sort_icon.src);
			elt.value='1';
			break;
		case '1' :	
			this.setAttribute('src',down_sort_icon.src);
			elt.value='2';
			break;
		case '2' :	
		default :
			this.setAttribute('src',no_sort_icon.src);
			elt.value='0';
			break;

	}
	return false;
}


//Soumission du formulaire de requete
function req_submitFrame(chk){

	if('1'!=chk) var chk=0;

	var url= "../../ajax.php?module=admin&categ=req&fname=buildRequest";
	url+='&req_univ='+parent.document.getElementById('req_univ').value;
	url+='&req_type='+parent.document.getElementById('req_type').value;
	url+='&req_nb_lines='+escape(req_nb_lines);
	
	tpost= new Array();
	var data='';
	//pour chaque ligne du tableau req_tab, parcours des cellules
	for(var i=1;i<req_tab.rows.length;i++) {
		//tableau des cellules d'une ligne
		var tc=req_tab.rows[i].cells;
		//pour chaque cellule, parcours en recursif pour recuperer les elements a poster
		
		for(var j=0;j<tc.length;j++) {
			for(var k=0;k<tc[j].childNodes.length;k++) {
				req_getContent(tc[j].childNodes[k],i,tc[j].getAttribute('name'));
			}
		}
	}
	//pour chaque ligne du tableau req_joi, parcours des cellules
	var req_joi=document.getElementById('req_joi');
	for(var i=1;i<req_joi.rows.length;i++) {
		//tableau des cellules d'une ligne
		var tc=req_joi.rows[i].cells;
		//pour chaque cellule, parcours en recursif pour recuperer les elements a poster
		for(var j=0;j<tc.length;j++) {
			joi_getContent(tc[j]);
		}
		
	}
	data+= '&'+tpost.join('&');
	data+= '&LI[B]='+escape(document.getElementById('LI_B').value);
	data+= '&LI[Q]='+escape(document.getElementById('LI_Q').value);
	data+= '&chk='+chk;
	
	// On initialise la classe:
	var buildRequest = new http_request();
	// Execution de la requete
	if(buildRequest.request(url,true,data)){
		// Il y a une erreur. Afficher le message retourne
		alert ( buildRequest.get_text());			
	} else {
		var res=buildRequest.get_text();
		
//TODO ajouter resultat verification requete 
		if(chk==1) {
			parent.document.getElementById('req_code').value=res;
			parent.req_hideFrame();
		} else {
			document.getElementById('spy').innerHTML=res;
		}
	}
	return false;
}

function req_getContent(n,rn,cl) {

	if(n.nodeName=='INPUT') {
		var n_name=n.getAttribute('name');
		if (n_name==null) {
			n_name='';
		} else {
			n_name='['+n_name+']';
		}
		switch (n.type) {
			case 'radio' :
			case 'checkbox' :
				if (n.checked) {
					tpost.push(cl+'['+rn+'][]'+n_name+'='+escape(n.value));
				}
				break;
			default :
				tpost.push(cl+'['+rn+'][]'+n_name+'='+escape(n.value));
				break;	
		}
	}
	if(n.nodeName=='SELECT') {
		tpost.push(cl+'['+rn+'][]'+'[TE]'+'='+escape(n.options[n.selectedIndex].value));
	}
	if(n.nodeName=='DIV' && n.getAttribute('order')!=null) {
		tpost.push(cl+'['+rn+'][][order]='+escape(n.getAttribute('order')));
	}
	for(var j=0;j<n.childNodes.length;j++) {
		req_getContent(n.childNodes[j],rn,cl);
	}
	return ;
}


function joi_getContent(n) {
	if(n.nodeName=='INPUT') {
		var n_name=n.getAttribute('name');
		if (n.checked) {
			tpost.push('JO['+n_name+']='+escape(n.value));
		}
	}
	for(var j=0;j<n.childNodes.length;j++) {
		joi_getContent(n.childNodes[j]);
	}
	return ;
}

//Deplacement ligne vers le haut
function moveLineUp() {
	
	var cr=this.parentNode.parentNode;
	var tab=cr.parentNode;
	if (cr.rowIndex < 2) {
		tab.appendChild(cr);
	} else {
		var pr=cr.previousSibling;
		tab.insertBefore(cr, pr);
	}
	try {
		recalc_recept();
	} catch(err){} //drag_n_drop.js non implante
}

//Deplacement ligne vers le bas
function moveLineDown() {

	var cr=this.parentNode.parentNode;
	var tab=cr.parentNode;
	
	//Recherche derniere ligne table
	var lr=tab.rows[(tab.rows.length-1)];
	
	//Recherche premiere ligne table
	var fr=tab.rows[0];
	
	if (cr.rowIndex == lr.rowIndex) {
		var sr=fr.nextSibling;
		tab.insertBefore(cr,sr); 
	} else {
		var nr=cr.nextSibling;
		tab.insertBefore(nr,cr);
	}
	try {
		recalc_recept();
	} catch(err){} //drag_n_drop.js non implante
}

//Suppression noeuds fils
function req_removeChilds(elt) {
	while (elt.hasChildNodes()){
		req_removeChilds(elt.lastChild);
		if (elt.lastChild.nodeType=='1') {
			var r_ids=elt.lastChild.getAttribute('r_ids');
			if (r_ids)req_hideJoin(r_ids);
		} 
		elt.removeChild(elt.lastChild);
	}
}

//Insertion d'un element deplace dans une cellule
function cell_cell(dragged,targetted) {

	var dragged_type=dragged.getAttribute('dragged_type');
	switch (dragged_type) {
		
		case 'FI':	//Champ
			var t_id=targetted.getAttribute('id');
			
			//Champ info cache
			var hi=document.createElement('INPUT');
			hi.setAttribute('type','hidden');
			hi.setAttribute('name','FI');
			var d_id=dragged.getAttribute('dragged_id');
			var r_ids=req_showJoin(d_id);
			if (r_ids) hi.setAttribute('r_ids',r_ids);
			hi.value=d_id;
			
			//Champ info visible
			var vi=document.createTextNode(dragged.getAttribute('dragtext'));
			
			//Bouton effacer			
			var i=document.createElement('IMG');
			i.setAttribute('src',remove_icon.src);
			i.className='bt_cross_elt';
			i.onclick=req_clearCell;
			
			//Transfert
			targetted.innerHTML='';
			targetted.appendChild(i);
			targetted.appendChild(hi);
			targetted.appendChild(vi);
			targetted.setAttribute('recept','no');
			
			//Maj drag_n_drop
			init_recept();
			cell_downlight(targetted);
			break;

		case 'FU':	//Fonction
			var t_id=targetted.getAttribute('id');
			var c_type=targetted.parentNode.getAttribute('name');
			var cs = new Array();
			
			//Champ info cache
			var hi=document.createElement('INPUT');
			hi.setAttribute('type','hidden');
			hi.setAttribute('name','FU');
			var d_id=dragged.getAttribute('dragged_id');
			hi.value=d_id;
			cs[cs.length]=hi;

			//Recuperation des zones attributs
			var f=req_getAttributes(d_id,c_type);
			var p_rb=f.getElementsByTagName('params').item(0).getAttribute('remove');			
			var p=f.getElementsByTagName('param');
		
			for(var j=0;j<p.length;j++) {
				
				var p_o=p[j].getAttribute('order');
				var p_c=p[j].getAttribute('content');
				var p_rf=p[j].getAttribute('repeat_from');
				var p_v=p[j].getAttribute('value');
				var d=null;
				
				switch(p_c) {
				
					case 'arg' :
						d=req_addContainer(null,p_o);	//tag argument
						cs[cs.length] = d;
						if(p_rf) { //partie repetable
							var a=document.createElement('IMG');
							a.setAttribute('src',add_icon.src);
							a.setAttribute('repeat_from',(p_rf));
							a.className='bt_add_arg';
							a.onclick=req_addArg;
							cs[cs.length]=a;
						}
						break;

					case 'keyword' :	//tag mot-cle
						d=req_addContainer('no',p_o,p_v);
						cs[cs.length]=d;				
						break;
						
					case 'list' :
						d=req_addSelect(p_o,p_v);
						cs[cs.length]=d;				
						break;
				
					default :
						break;
				}
				
				if(d!=null && (p_o==p_rb)) {
					//Bouton effacer		
					var i=document.createElement('IMG');
					i.setAttribute('src',remove_icon.src);
					i.className='bt_cross_fct';
					i.onclick=req_clearCell;
					d.insertBefore(i,d.firstChild);					
				}
			}
			//fin de fonction
			var he=document.createElement('INPUT');
			he.setAttribute('type','hidden');
			he.setAttribute('name','FU_E');
			he.value=d_id;
			cs[cs.length]=he;

			//Transfert
			targetted.innerHTML='';
			targetted.setAttribute('recept','no');
			for(var j=0;j<cs.length;j++) {
				targetted.appendChild(cs[j]);
			}			
			
			//Maj drag_n_drop
			init_recept();
			cell_downlight(targetted);
			break;

		case 'SU':	//Sous requete
			var t_id=targetted.getAttribute('id');
			
			//Champ info cache
			var hi=document.createElement('INPUT');
			hi.setAttribute('type','hidden');
			hi.setAttribute('name','SU');
			var d_id=dragged.getAttribute('dragged_id');
			var r_ids=req_showJoin(d_id);
			if (r_ids) hi.setAttribute('r_ids',r_ids);
			hi.value=d_id;
			
			//Champ info visible
			var vi=document.createTextNode(dragged.getAttribute('dragtext'));
			
			//Bouton effacer			
			var i=document.createElement('IMG');
			i.setAttribute('src',remove_icon.src);
			i.className='bt_cross_elt';
			i.onclick=req_clearCell;
			
			//Transfert
			targetted.innerHTML='';
			targetted.appendChild(i);
			targetted.appendChild(hi);
			targetted.appendChild(vi);
			targetted.setAttribute('recept','no');
			
			//Maj drag_n_drop
			init_recept();
			cell_downlight(targetted);
			break;

		default:
			cell_downlight(targetted);
			break;
	}

}

function cell_celldropzone(dragged,dropzone) {
	var dz_id=dropzone.getAttribute('id');
	req_addLine();
	var t_dz=dz_id.split('_');
	var targetted=document.getElementById('C_'+t_dz[1]+'_'+req_cur_line).firstChild;
	cell_cell(dragged,targetted);
	cell_downlight(dropzone);
}

//Suppression Element
function req_clearCell(){
	var elt=this;
	while(elt.getAttribute('name') != 'DC') {
		elt=elt.parentNode;
	}
	req_removeChilds(elt);	

	//Bouton 			
	elt.appendChild(req_addPen());
	//elt.setAttribute('recepttype','cell');
	elt.setAttribute('recept','yes');
	elt.className='req_container';
	init_recept();
}

//Ajout argument
function req_addArg() {
	
	var elt=this;
	var par=elt.parentNode;
	
	var p_rf=this.getAttribute('repeat_from');
	var p_rt=this.previousSibling.getAttribute('order');
	var n = new Array();
	do {
		elt=elt.previousSibling;
		n[n.length]=elt;
	}while(elt.getAttribute('order')!=p_rf);
	
	if(n.length) {

		var a=document.createElement('IMG');
		a.setAttribute('src',add_icon.src);
		a.setAttribute('repeat_from',p_rf);
		a.className='bt_add_arg';
		a.onclick=req_addArg;
		par.insertBefore(a,this);
		
		var a0 = par.removeChild(this);
		par.insertBefore(a0,a);
		
		var a1=document.createElement('IMG');
		a1.setAttribute('src',remove_icon.src);
		a1.setAttribute('remove_to',p_rt);
		a1.className='bt_add_arg';
		a1.onclick=req_removeArg;
		par.insertBefore(a1,a);

		while(n.length) {
			var d=n.pop();
			if(d.getAttribute('id')) {
				d1=d.cloneNode(false);
				d1.setAttribute('recepttype','cell');
				idx++;
				d1.setAttribute('id','DC_'+idx);
				d1.appendChild(req_addPen());
			} else {
				d1=d.cloneNode(true);
			}
			par.insertBefore(d1,a);
		}
		init_recept();
	}		
}


//Suppression argument
function req_removeArg() {
	
	var elt=this;
	var par=elt.parentNode;
	var p_rt=this.getAttribute('remove_to');
	
	var n=new Array();
	n[0]=elt;
	while(elt.getAttribute('order') != p_rt) {
		elt=elt.nextSibling;
		n[n.length]=elt;
	}
	n[n.length]=elt.nextSibling;
	
	while(n.length) {
		elt=n.pop();
		req_removeChilds(elt);
		par.removeChild(elt);
	}
	init_recept();
}


//Calcul attribut relation et Affichage relation
function req_showJoin(id) {
	var tr_ids= new Array();
	var r_= id.split('_');						
	for (var i=0;i<r_.length;i++){
		if (r_[i].substring(0,1)=='R') {
			tr_ids[tr_ids.length]=r_[i];
			if(joi_link[r_[i]]) joi_link[r_[i]]++; else joi_link[r_[i]]=1;
			var tr=document.getElementById(r_[i]);
			try {
				tr.style.display='table-row';
			} catch(err){
				tr.style.display='block';
			}
		}
	}
	return tr_ids.join('_');
}

//Suppression affichage relation
function req_hideJoin(id) {
	var r_= id.split('_');						
	for (var i=0;i<r_.length;i++){
		if(joi_link[r_[i]]) joi_link[r_[i]]--; else joi_link[r_[i]]=0;
		if(joi_link[r_[i]]==0){
			var tr=document.getElementById(r_[i]);
			tr.style.display='none';
		}
	}
}


//Demande des attributs de fonction
function req_getAttributes(fct_id,c_type){
	
	var url= "../../ajax.php?module=admin&categ=req&fname=getAttributes";
	url+='&fct_id='+escape(fct_id);
	if(c_type != null){
		url+='&c_type='+escape(c_type);
	}
		// On initialise la classe:
	var getAttr = new http_request();
	// Execution de la requete
	if(getAttr.request(url)){
		// Il y a une erreur. Afficher le message retourne
		alert ( getAttr.get_text());			
	}else { 
		//alert ( getAttr.get_text());
		return getAttr.get_xml();
	}
}


//Fonctions de drag and drop pour les lignes de table

//Insertion avant la ligne survolee
function row_row(dragged,targetted) {

	var tab=targetted.parentNode;
	tab.insertBefore(dragged,targetted);
	row_downlight(targetted);
	recalc_recept();
}

//Mise en evidence ligne survolee
function row_highlight(obj) {
	obj.style.background="#CCC";
}

//Extinction ligne survolee
function row_downlight(obj) {
	obj.style.background="";
}


//Fonctions de drag and drop pour les cellules de table

//Mise en evidence cellule survolee
function cell_highlight(obj) {
	obj.style.background="#CCC";
}

//Extinction cellule survolee
function cell_downlight(obj) {
	obj.style.background="";
}
