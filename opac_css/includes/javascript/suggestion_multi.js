// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: suggestion_multi.js,v 1.3 2009-11-30 10:39:25 kantin Exp $

function add_line(nb_ligne){
	
	var tableau = document.getElementById("tableau_multi_sugg");
	var btn_add = document.getElementById("add_line_"+nb_ligne);
	var td_act_btn = document.getElementById("act_btn_"+nb_ligne);
	td_act_btn.removeChild(btn_add);
	var tr = document.createElement("tr");
	var nb = nb_ligne+1;
	tr.setAttribute("id","sugg_"+nb);
	
	var td_tit = document.createElement("td");
	var input_tit = document.createElement("input")
	input_tit.setAttribute("id","sugg_tit_"+nb);
	input_tit.setAttribute("name","sugg_tit_"+nb);
	td_tit.appendChild(input_tit);
	
	var td_aut = document.createElement("td");
	var input_aut = document.createElement("input")
	input_aut.setAttribute("id","sugg_aut_"+nb);
	input_aut.setAttribute("name","sugg_aut_"+nb);
	td_aut.appendChild(input_aut);
	
	var td_edi = document.createElement("td");
	var input_edi = document.createElement("input")
	input_edi.setAttribute("id","sugg_edi_"+nb);
	input_edi.setAttribute("name","sugg_edi_"+nb);
	td_edi.appendChild(input_edi);
	
	var td_code = document.createElement("td");
	var input_code = document.createElement("input")
	input_code.setAttribute("id","sugg_code_"+nb);
	input_code.setAttribute("name","sugg_code_"+nb);
	td_code.appendChild(input_code);
	
	var td_prix = document.createElement("td");
	var input_prix = document.createElement("input")
	input_prix.setAttribute("id","sugg_prix_"+nb);
	input_prix.setAttribute("name","sugg_prix_"+nb);
	td_prix.appendChild(input_prix);
	
	var td_url = document.createElement("td");
	var input_url = document.createElement("input")
	input_url.setAttribute("id","sugg_url_"+nb);
	input_url.setAttribute("name","sugg_url_"+nb);
	td_url.appendChild(input_url);
	
	var td_com = document.createElement("td");
	var area_com = document.createElement("textarea")
	area_com.setAttribute("id","sugg_com_"+nb);
	area_com.setAttribute("name","sugg_com_"+nb);
	td_com.appendChild(area_com);
	
	var td_date = document.createElement("td");
	var input_date = document.createElement("input");
	input_date.setAttribute("id","sugg_date_"+nb);
	input_date.setAttribute("name","sugg_date_"+nb);
	td_date.appendChild(input_date);
	
	var td_src = document.createElement("td");
	var select_src = document.getElementById("sugg_src_"+nb_ligne).cloneNode(true);
	select_src.setAttribute("id","sugg_src_"+nb);
	select_src.setAttribute("name","sugg_src_"+nb);
	td_src.appendChild(select_src);
	
	var td_qte = document.createElement("td");
	var input_qte = document.createElement("input")
	input_qte.setAttribute("id","sugg_qte_"+nb);
	input_qte.setAttribute("name","sugg_qte_"+nb);
	input_qte.setAttribute("value","1");
	td_qte.appendChild(input_qte);
	
	var td_button = document.createElement("td");
	td_button.setAttribute("id","act_btn_"+nb);
	var btn_addline = document.createElement("input");
	btn_addline.setAttribute("type","button");
	btn_addline.setAttribute("value","+");
	btn_addline.setAttribute("name","add_line_"+nb);
	btn_addline.setAttribute("id","add_line_"+nb);	
	btn_addline.onclick=function(){add_line(nb)};
	var btn_delline = document.createElement("input");
	btn_delline.setAttribute("type","button");
	btn_delline.setAttribute("value","X");
	btn_delline.setAttribute("name","del_line_"+nb_ligne);
	btn_delline.setAttribute("id","del_line_"+nb_ligne);
	btn_delline.onclick=function(){del_line(nb_ligne)};
	td_button.appendChild(btn_addline);
	document.getElementById("act_btn_"+nb_ligne).appendChild(btn_delline);
	
	document.getElementById("max_nblignes").value = nb+1;
	
	tr.appendChild(td_tit);
	tr.appendChild(td_aut);
	tr.appendChild(td_edi);
	tr.appendChild(td_code);
	tr.appendChild(td_prix);
	tr.appendChild(td_url);
	tr.appendChild(td_com);	
	tr.appendChild(td_date);
	tr.appendChild(td_src);
	tr.appendChild(td_qte);
	tr.appendChild(td_button);
	tableau.appendChild(tr);
	
	enableAll(nb_ligne);
	disableAll(nb);
}

function del_line(nb_ligne){
	if(!document.getElementById("add_line_"+nb_ligne)){
		var tableau = document.getElementById("tableau_multi_sugg");
		var ligne = document.getElementById("sugg_"+nb_ligne);
		tableau.removeChild(ligne);
	}
}

function disableAll(nbligne){
	document.getElementById("sugg_tit_"+nbligne).disabled = true;
	document.getElementById("sugg_aut_"+nbligne).disabled = true;
	document.getElementById("sugg_edi_"+nbligne).disabled = true;
	document.getElementById("sugg_code_"+nbligne).disabled = true;
	document.getElementById("sugg_prix_"+nbligne).disabled = true;
	document.getElementById("sugg_url_"+nbligne).disabled = true;
	document.getElementById("sugg_com_"+nbligne).disabled = true;
	document.getElementById("sugg_date_"+nbligne).disabled = true;
	document.getElementById("sugg_src_"+nbligne).disabled = true;
	document.getElementById("sugg_qte_"+nbligne).disabled = true;
}

function enableAll(nbligne){
	document.getElementById("sugg_tit_"+nbligne).disabled = false;
	document.getElementById("sugg_aut_"+nbligne).disabled = false;
	document.getElementById("sugg_edi_"+nbligne).disabled = false;
	document.getElementById("sugg_code_"+nbligne).disabled = false;
	document.getElementById("sugg_prix_"+nbligne).disabled = false;
	document.getElementById("sugg_url_"+nbligne).disabled = false;
	document.getElementById("sugg_com_"+nbligne).disabled = false;
	document.getElementById("sugg_date_"+nbligne).disabled = false;
	document.getElementById("sugg_src_"+nbligne).disabled = false;
	document.getElementById("sugg_qte_"+nbligne).disabled = false;
}
