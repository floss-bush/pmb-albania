// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: actes.js,v 1.5 2009-11-30 11:46:26 dbellamy Exp $


/*
variables a déclarer dans le formulaire appelant:

 msg_parcourir			//valeur bouton parcourir
 msg raz				//valeur bouton suppression
 msg_no_fou				//message si pas de fournisseur défini
 msg_act_vide			//message si acte vide
 acquisition_budget		//budget obligatoire ?
 msg_no_bud				//message si pas de budget défini
 
 act_nblines			//nb de lignes
 act curline 			//n° de ligne courante
 
*/

var gestion_tva=document.getElementById('gestion_tva').value;
var act_type=document.getElementById('act_type').value;
var tot_ht=document.getElementById('tot_ht');
var tot_tva=document.getElementById('tot_tva');
var tot_ttc=document.getElementById('tot_ttc');
var tot_expl=document.getElementById('tot_expl');
var precision=2;
var mod=0; 
 
//Ajout ligne acte
function act_addLine() {

	var te=document.getElementById('act_tab');
	act_nblines++;
	act_curline++;
	
	var tr=act_addRow(act_curline);

	switch (act_type) {

		case '0' :	//Commande

			var td1=act_addCodeCell(act_curline);
			tr.appendChild(td1);
			var td2=act_addTitleCell(act_curline);
			tr.appendChild(td2);			
			var td3=act_addQtyCell(act_curline);
			tr.appendChild(td3);			
			var td4=act_addPriceCell(act_curline);
			tr.appendChild(td4);			
			var td5=act_addTypeCell(act_curline);
			tr.appendChild(td5);	
			var td6=act_addRubriqueCell(act_curline);
			tr.appendChild(td6);			
			var td7=act_addActionCell(act_curline);
			tr.appendChild(td7);
			te.appendChild(tr);		
			act_getPreviousType(tr);
			act_getPreviousRubrique(tr);
			document.getElementById('bt_add_line').focus();
			td1.firstChild.focus();
			break;

		case '1' :	//Devis

			var td1=act_addCodeCell(act_curline);
			tr.appendChild(td1);
			var td2=act_addTitleCell(act_curline);
			tr.appendChild(td2);			
			var td3=act_addQtyCell(act_curline);
			tr.appendChild(td3);			
			var td4=act_addPriceCell(act_curline);
			tr.appendChild(td4);			
			var td5=act_addTypeCell(act_curline);
			tr.appendChild(td5);			
			var td6=act_addActionCell(act_curline);
			tr.appendChild(td6);			
			te.appendChild(tr);
			act_getPreviousType(tr);
			document.getElementById('bt_add_line').focus();
			td1.firstChild.focus();
			break;
		
		default :
		break;
	}
	act_calc();
}

//Ajout ligne
function act_addRow(lig){

	var tr=document.createElement('TR');
	tr.setAttribute('id','R_'+lig);
	return tr;
}

//Ajout cellule code
function act_addCodeCell(lig) {
	
	var td=document.createElement('TD');
	var i=document.createElement('INPUT');
	i.setAttribute('type','text');
	i.setAttribute('id','code['+lig+']');
	i.setAttribute('name','code['+lig+']');
	i.setAttribute('tabindex','1');
	i.className='in_cell';
	i.setAttribute('value','');
	td.appendChild(i);
	
	var b=document.createElement('INPUT');
	b.setAttribute('type','button');
	b.setAttribute('tabindex','1');
	b.className='bouton_small';
	b.style.width='20px';
	b.setAttribute('value', msg_parcourir);
	b.onclick=function(){act_getCode(this);};
	td.appendChild(b);

	var b1=document.createElement('INPUT');
	b1.setAttribute('type','button');
	b1.setAttribute('tabindex','1');
	b1.className='bouton_small';
	b1.style.width='20px';
	b1.setAttribute('value', msg_raz);
	b1.onclick=function(){act_delCode(this);};
	td.appendChild(b1);
	
	return td;
}

//Ouverture du popup de recherche de notice/bulletin/frais/abonnements
function act_getCode(elt) {
	
	var cr=elt.parentNode.parentNode.getAttribute('id').substring(2);
	var code=document.forms['act_modif'].elements['code['+cr+']'].value;
	var lib=document.forms['act_modif'].elements['lib['+cr+']'].value;
	var typ_lig=document.forms['act_modif'].elements['typ_lig['+cr+']'].value; 
	var deb_rech='';
	if (code!='') {
		deb_rech=code;
	} else{
		if (lib!=''){
			deb_rech= lib;
		}
	}
	var typ_query='notice';
	switch(typ_lig) {
		case '2' :
			typ_query='bulletin';
			break;
		case '3' :
			typ_query='frais';
			break;
		case '4' :
			typ_query='abt';
			break;
		case '5' :
			typ_query='article';
			break;
	}
	mod=1;
	openPopUp("select.php?what=acquisition_notice&caller=act_modif&cr="+cr+"&deb_rech="+escape(deb_rech)+"&typ_query="+escape(typ_query) , 'select_notice', 1024, 300, 0, 0, 'infobar=no, status=no, scrollbars=yes, toolbar=no, menubar=no, dependent=yes, resizable=yes');
	return false;
}

function act_delCode(elt) {

	var cr=elt.parentNode.parentNode.getAttribute('id').substring(2);
	document.getElementById('code['+cr+']').value='';
	document.getElementById('lib['+cr+']').value='';
	document.getElementById('typ_lig['+cr+']').value='0';
	document.getElementById('id_prod['+cr+']').value='0';
	document.getElementById('id_sug['+cr+']').value='0';
	document.getElementById('prix['+cr+']').value='0.00';
	return false;
}

//Ajout cellule titre
function act_addTitleCell(lig) {
	
	var td=document.createElement('TD');
	var i=document.createElement('TEXTAREA');
	i.setAttribute('id','lib['+lig+']');
	i.setAttribute('name','lib['+lig+']');
	i.setAttribute('tabindex','1');
	i.className='in_cell';
	i.setAttribute('rows','2');
	i.setAttribute('wrap','virtual');
	td.appendChild(i);
	return td;
}

//Ajout cellule quantite
function act_addQtyCell(lig) {

	var td=document.createElement('TD');
	var i=document.createElement('INPUT');
	i.setAttribute('type','text');
	i.setAttribute('id','qte['+lig+']');
	i.setAttribute('name','qte['+lig+']');
	i.setAttribute('tabindex','1');
	i.className='in_cell_nb';
	i.setAttribute('value','1');
	td.appendChild(i);
	return td;
}

//Ajout cellule prix
function act_addPriceCell(lig) {
	
	var td=document.createElement('TD');
	var i=document.createElement('INPUT');
	i.setAttribute('type','text');
	i.setAttribute('id','prix['+lig+']');
	i.setAttribute('name','prix['+lig+']');
	i.setAttribute('tabindex','1');
	i.className='in_cell_nb';
	i.setAttribute('value','0');
	td.appendChild(i);
	return td;
}

//Ajout cellule type
function act_addTypeCell(lig) {

	var td=document.createElement('TD');
	var i=document.createElement('INPUT');
	i.setAttribute('type','hidden');
	i.setAttribute('id','typ['+lig+']');
	i.setAttribute('name','typ['+lig+']');
	i.setAttribute('value','0');
	td.appendChild(i);
	
	var i1=document.createElement('INPUT');
	i1.setAttribute('type','text');
	i1.setAttribute('id','lib_typ['+lig+']');
	i1.setAttribute('name','lib_typ['+lig+']');
	i1.setAttribute('tabindex','1');
	i1.className='in_cell_ro';
	i1.setAttribute('value','');
	td.appendChild(i1);

	var b=document.createElement('INPUT');
	b.setAttribute('type','button');
	b.setAttribute('tabindex','1');
	b.className='in_cell_ro';
	b.className='bouton_small';
	b.style.width='20px';
	b.setAttribute('value', msg_parcourir);
	b.onclick=function() {act_getType(this);};
	td.appendChild(b);

	var b1=document.createElement('INPUT');
	b1.setAttribute('type','button');
	b1.setAttribute('tabindex','1');
	b1.className='bouton_small';
	b1.style.width='20px';
	b1.setAttribute('value', msg_raz);
	b1.onclick=function(){act_delType(this);};
	td.appendChild(b1);
		
	switch (gestion_tva) {
		case '1' :
		case '2' :
			var s=document.createTextNode(' ');
			td.appendChild(s);
			var i2=document.createElement('INPUT');
			i2.setAttribute('type','text');
			i2.setAttribute('id', 'tva['+lig+']');
			i2.setAttribute('name', 'tva['+lig+']');
			i2.setAttribute('tabindex','1');
			i2.className='in_cell_nb';
			i2.style.width='20%';
			i2.setAttribute('value', '0');
			td.appendChild(i2);
			var n=document.createTextNode(' %');
			td.appendChild(n);
			break;
		default:
			break;
	}

	var s1=document.createTextNode(' ');
	td.appendChild(s1);
	var i3=document.createElement('INPUT');
	i3.setAttribute('type','text');
	i3.setAttribute('id', 'rem['+lig+']');
	i3.setAttribute('name', 'rem['+lig+']');
	i3.setAttribute('tabindex','1');
	i3.className='in_cell_nb';
	i3.style.width='20%';
	i3.setAttribute('value', '0');
	td.appendChild(i3);
	var n1=document.createTextNode(' %');
	td.appendChild(n1);
	return td;
}

//Ouverture du popup de recherche de type de produit
function act_getType(elt) {

	var cr=elt.parentNode.parentNode.getAttribute('id').substring(2);
	openPopUp("select.php?what=types_produits&caller=act_modif&param1=typ["+cr+"]&param2=lib_typ["+cr+"]&param3=rem["+cr+"]&param4=tva["+cr+"]&id_fou="+document.getElementById('id_fou').value+"&close=1", 'select_notice', 400, 400, -2, -2, 'infobar=no, status=no, scrollbars=yes, toolbar=no, menubar=no, dependent=yes, resizable=yes');
	return false;
}

function act_delType(elt) {

	var cr=elt.parentNode.parentNode.getAttribute('id').substring(2);
	document.getElementById('typ['+cr+']').value='0';
	document.getElementById('lib_typ['+cr+']').value='';
	document.getElementById('tva['+cr+']').value='0.00';
	document.getElementById('rem['+cr+']').value='0.00';
	act_calc();
	return false;
}

function act_getPreviousType(tr) {

	var i=tr.rowIndex;
	if (i<2) return false;
	var te = tr.parentNode;
	var cr = tr.getAttribute('id').substring(2);
	var pr = te.rows[i-1].getAttribute('id').substring(2);
	document.getElementById('typ['+cr+']').value=document.getElementById('typ['+pr+']').value;
	document.getElementById('lib_typ['+cr+']').value=document.getElementById('lib_typ['+pr+']').value;
	try{
		document.getElementById('tva['+cr+']').value=document.getElementById('tva['+pr+']').value;
	} catch(err){}
	document.getElementById('rem['+cr+']').value=document.getElementById('rem['+pr+']').value;
	return false;
}

//Ajout cellule rubrique budgetaire
function act_addRubriqueCell(lig) {

	var td=document.createElement('TD');
	var i=document.createElement('INPUT');
	i.setAttribute('type','hidden');
	i.setAttribute('id','rub['+lig+']');
	i.setAttribute('name','rub['+lig+']');
	i.setAttribute('value','0');
	td.appendChild(i);
	
	var i1=document.createElement('INPUT');
	i1.setAttribute('type','text');
	i1.setAttribute('id','lib_rub['+lig+']');
	i1.setAttribute('name','lib_rub['+lig+']');
	i1.setAttribute('tabindex','1');
	i1.className='in_cell_ro';
	i1.setAttribute('value','');
	td.appendChild(i1);
	
	var b=document.createElement('INPUT');
	b.setAttribute('type','button');
	b.setAttribute('tabindex','1');
	b.className='bouton_small';
	b.style.width='20px';
	b.setAttribute('value', msg_parcourir);
	b.onclick=function(){act_getRubrique(this);};
	td.appendChild(b);

	var b1=document.createElement('INPUT');
	b1.setAttribute('type','button');
	b1.setAttribute('tabindex','1');
	b1.className='bouton_small';
	b1.style.width='20px';
	b1.setAttribute('value', msg_raz);
	b1.onclick=function(){act_delRubrique(this);};
	td.appendChild(b1);

	return td;
}

//Ouverture du popup de recherche de rubrique budgetaire
function act_getRubrique(elt) {

	var cr=elt.parentNode.parentNode.getAttribute('id').substring(2);
	openPopUp("select.php?what=rubriques&caller=act_modif&param1=rub["+cr+"]&param2=lib_rub["+cr+"]&id_bibli="+document.getElementById('id_bibli').value+"&id_exer="+document.getElementById('id_exer').value+"&close=1", 'select_notice', 400, 400, -2, -2, 'infobar=no, status=no, scrollbars=yes, toolbar=no, menubar=no, dependent=yes, resizable=yes');
	return false;
}

function act_delRubrique(elt) {

	var cr=elt.parentNode.parentNode.getAttribute('id').substring(2);
	document.getElementById('rub['+cr+']').value='0';
	document.getElementById('lib_rub['+cr+']').value='';
	return false;
}

function act_getPreviousRubrique(tr) {
	
	var i=tr.rowIndex;
	if (i<2) return false;
	var te = tr.parentNode;
	var cr = tr.getAttribute('id').substring(2);
	var pr = te.rows[i-1].getAttribute('id').substring(2);
	document.getElementById('rub['+cr+']').value=document.getElementById('rub['+pr+']').value;
	document.getElementById('lib_rub['+cr+']').value=document.getElementById('lib_rub['+pr+']').value;
	return false;
}

//Ajout cellule action
function act_addActionCell(lig) {

	var td=document.createElement('TD');
	var i=document.createElement('INPUT');
	i.setAttribute('type', 'checkbox');
	i.setAttribute('id','chk['+lig+']');
	i.setAttribute('name','chk['+lig+']');
	i.setAttribute('tabindex','1');
	i.setAttribute('value','1');
	td.appendChild(i);
	
	var i1=document.createElement('INPUT');
	i1.setAttribute('type', 'hidden');
	i1.setAttribute('id','id_sug['+lig+']');
	i1.setAttribute('name','id_sug['+lig+']');
	i1.setAttribute('value','0');
	td.appendChild(i1);
	
	var i2=document.createElement('INPUT');
	i2.setAttribute('type', 'hidden');
	i2.setAttribute('id','id_lig['+lig+']');
	i2.setAttribute('name','id_lig['+lig+']');
	i2.setAttribute('value','0');
	td.appendChild(i2);

	var i3=document.createElement('INPUT');
	i3.setAttribute('type', 'hidden');
	i3.setAttribute('id','typ_lig['+lig+']');
	i3.setAttribute('name','typ_lig['+lig+']');
	i3.setAttribute('value','0');
	td.appendChild(i3);

	var i4=document.createElement('INPUT');
	i4.setAttribute('type', 'hidden');
	i4.setAttribute('id','id_prod['+lig+']');
	i4.setAttribute('name','id_prod['+lig+']');
	i4.setAttribute('value','0');
	td.appendChild(i4);
	return td;
}

//Suppression lignes cochees
function act_delLines(){

	var n=act_curline;
	var i,j,c,cr,tab;
	for (i=1;i<=n;i++) {
		c=document.getElementById('chk['+i+']');
		try {
			if(c.checked) {
				cr=c.parentNode.parentNode;
				j=cr.rowIndex;
				act_removeChilds(cr);
				tab=cr.parentNode;
				tab.deleteRow(j);
				act_nblines--;
			}
		}catch(err) {}
	}
	act_calc();
	return false;
}

//Suppression noeuds fils
function act_removeChilds(elt) {

	while (elt.hasChildNodes()){
		act_removeChilds(elt.lastChild);
		elt.removeChild(elt.lastChild);
	}
}

//calcul du total
function act_calc(){

	act_clean();
	var mnt_ht=0;
	var mnt_ttc=0;
	var mnt_tva=0;
	var n=act_curline;
	var i,q,p,t,r;
	var qt=0; 
	for (i=1;i<=n;i++) {
		try {
			q=document.getElementById('qte['+i+']').value;
			qt=qt+(q*1);
			p=document.getElementById('prix['+i+']').value;
			if (gestion_tva!=0) {
				t=document.getElementById('tva['+i+']').value;
			}
			r=document.getElementById('rem['+i+']').value;
			switch(gestion_tva) {
				case '1' :
					mnt_ht=mnt_ht+(q*p*((100-r)/100));
					mnt_tva=mnt_tva+(q*p*((100-r)/100)*(t/100));
					break;
				case '2' :
					mnt_ttc=mnt_ttc+(q*p*((100-r)/100));
					mnt_ht=mnt_ht+((q*p*((100-r)/100))/(1+(t/100))) ;
					break;
				default :
					mnt_ttc=mnt_ttc+(q*p*((100-r)/100));
					break;
			}
		}catch(err){}
	}
	switch(gestion_tva) {
	case '1' :
		tot_ht.value=mnt_ht.toFixed(precision);
		tot_tva.value=mnt_tva.toFixed(precision);
		tot_ttc.value=(mnt_ht+mnt_tva).toFixed(precision);
		break;
	case '2' :
		tot_ht.value=mnt_ht.toFixed(precision);
		tot_tva.value=(mnt_ttc-mnt_ht).toFixed(precision);
		tot_ttc.value=mnt_ttc.toFixed(precision);
		break;
	default :
		tot_ttc.value=mnt_ttc.toFixed(precision);
		break;
	}
	tot_expl.value=qt;
	return false;
}

//Nettoyage des valeurs en fonction de leur type
function act_clean() {

	if (gestion_tva!=0) {
		tot_ht.value='0';
		tot_tva.value='0';
	} 
	tot_ttc.value='0';
	var n=act_curline;
	var i;
	for (i=1;i<=n;i++) {
		try {
			val_clean(document.getElementById('qte['+i+']'), true,false);
			val_clean(document.getElementById('prix['+i+']'), false,false);
			if (gestion_tva!=0) {
				val_clean(document.getElementById('tva['+i+']'), false,false);
			}
			val_clean(document.getElementById('rem['+i+']'), false,true);
		}
		catch (err) {}
	}
	return false;
}


//Nettoyage des valeurs
function val_clean(x, int ,neg) {

	var v=x.value;
	v=v.replace(/,/g,".");
	if (neg) { 
		v=v.replace(/[^0-9|\.|-]/g,"");
	} else {
		v=v.replace(/[^0-9|\.]/g,"");
	}
	if (int) {
		x.value=new Number(v).toFixed(0);
	} else {
		x.value=new Number(v).toFixed(precision);
	}
	return false;
}


//Verification formulaire
function act_verif() {

	if (document.getElementById('id_fou').value==0) {
		alert(msg_no_fou);
		return false; 
	} 
	if (act_nblines<1) {
		alert(msg_act_vide); 
		return false;
	} 		
	var t,i,j,rub;
	if (acquisition_budget==1) {
		t=document.getElementById('act_tab').parentNode;
		for (i=1;i<t.rows.length;i++) {
			j=t.rows[i].getAttribute('id').substring(2);
			rub=document.getElementById('rub['+j+']');
			if (rub.value==0) {
				alert(msg_no_bud);
				document.getElementById('lib_rub['+t.rows[i].getAttribute('id').substring(2)+']').focus();
				return false;
			}
		} 
	}
	return true;
} 

//une fonction pout tout cocher/decocher
function act_switchCheck() {

	var n=act_curline;
	var i,c;
	for (i=1;i<=n;i++) {
		try {
			c=document.forms['act_modif'].elements['chk['+i+']'];
			c.checked = !c.checked;
		}catch(err) {}
	}
	return false;
}


//Pour verifier les doublons sur les commandes
function act_lineAlreadyExists(lig, id_prod, typ_lig) {

	if (typ_lig==0) return false;
	
	var t=document.getElementById('act_tab').parentNode;
	var i,j,x,y;
	for (i=1;i<t.rows.length;i++) {
	
		j=t.rows[i].getAttribute('id').substring(2);
		if (j!=lig) {
			x=document.getElementById('id_prod['+j+']').value;
			y=document.getElementById('typ_lig['+j+']').value;
	
			if (x==id_prod && y==typ_lig) {
				return j;
			}		
		} 
	}
	return false;
}

//Pour chercher la premiere ligne vide
function act_getEmptyLine() {

	var t=document.getElementById('act_tab').parentNode;
	var i,j,x,y,z;
	
	for (i=1;i<t.rows.length;i++) {
	
		j=t.rows[i].getAttribute('id').substring(2);

		x=document.getElementById('code['+j+']').value;
		y=document.getElementById('lib['+j+']').value;
		z=document.getElementById('id_prod['+j+']').value;
		if (x=='' && y=='' && z==0) { 
			return j;
		}
	}
	act_addLine();
	return act_curline;;
}











