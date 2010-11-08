// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: domain.js,v 1.2 2009-07-28 17:01:07 dbellamy Exp $

var nb_todo=0;
var nb_done=0;
var pbar=document.getElementById('pbar');
var pbar_img=document.getElementById('pbar_img');
var pbar_percent=document.getElementById('pbar_percent');

//initialisation ressources
function pbar_init() {
	nb_todo=0;
	nb_done=0;
	document.getElementById('pbar_ini_msg').style.display='block';
	document.getElementById('pbar_end_msg').style.display='none';
	pbar.style.display='block';
	nb_todo=dom_updateResources(1);
	while(nb_done < nb_todo) {
		nb_done=dom_updateResources(2);
		pbar_progress();
	}
	nb_done=dom_updateResources(3);	
	pbar_end();
	return false;
}

//Mise a jour barre de progression
function pbar_progress() {
	var p=0;
	if(nb_todo>0) {
		if(nb_done>nb_todo) nb_done=nb_todo;
		var p=Math.floor((nb_done/nb_todo)*100);
	}
	pbar_img.style.width=p+'%';
	pbar_percent.innerHTML=nb_done+" / "+nb_todo+" -- "+p+'%';
	return false;
}

//affichage etat final
function pbar_end() {
	document.getElementById('pbar_ini_msg').style.display='none';
	document.getElementById('pbar_end_msg').style.display='block';
	return false;
}

//Demande nb elements a modifier
function dom_updateResources(step){
	
	var url='';
	var chk_sav_spe_rights=0;

	switch(step) {
		case 1 : 
			url= "./ajax.php?module=admin&categ=acces&dom_id="+document.getElementById('dom_id').value+"&fname=getNbResourcesToUpdate";
			break;
		case 2 :
			if(document.getElementById('chk_sav_spe_rights').checked) {
				chk_sav_spe_rights=1;
			}
			url= "./ajax.php?module=admin&categ=acces&dom_id="+document.getElementById('dom_id').value+"&fname=updateRessources&nb_done="+nb_done+"&chk_sav_spe_rights="+chk_sav_spe_rights;
			break;
		case 3 :
		default :
			url= "./ajax.php?module=admin&categ=acces&dom_id="+document.getElementById('dom_id').value+"&fname=cleanResources";
			break;
	}
	// On initialise la classe:
	var getAttr = new http_request();
	// Exécution de la requete
	if(getAttr.request(url)){
		// Il y a une erreur. Afficher le message retourné
		alert (getAttr.get_text());			
	}else { 
		//alert(getAttr.get_text());
		return (parseInt(getAttr.get_text()));
	}
	return false;
}


