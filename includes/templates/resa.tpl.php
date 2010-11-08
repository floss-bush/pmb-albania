<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: resa.tpl.php,v 1.14 2009-11-27 15:00:14 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

// en-tête et pied de page
$layout_begin = "
<div class=\"row\">
	<h1>$msg[350]</h1>
  $msg[353] <a href='./circ.php?categ=pret&form_cb=!!cb_lecteur!!&groupID=$groupID'>!!nom_lecteur!!</a>
</div>
";

$menu_search_commun = "
	<div class='row'>
		<a href='./circ.php?categ=resa&mode=0&id_empr=$id_empr&groupID=$groupID'>$msg[354]</a>
		<a href='./circ.php?categ=resa&mode=1&id_empr=$id_empr&groupID=$groupID'>$msg[355]</a>
		<a href='./circ.php?categ=resa&mode=5&id_empr=$id_empr&groupID=$groupID'>".$msg["search_by_terms"]."</a>
		<a href='./circ.php?categ=resa&mode=2&id_empr=$id_empr&groupID=$groupID'>$msg[356]</a>
		<a href='./circ.php?categ=resa&mode=3&id_empr=$id_empr&groupID=$groupID'>$msg[search_by_panier]</a>
		<a href='./circ.php?categ=resa&mode=6&id_empr=$id_empr&groupID=$groupID'>".$msg["search_extended"]."</a>
	</div>
";

$menu_search_visu_rech = "<h1>$msg[show] : !!mode_recherche!!</h1>";

$menu_search[0] = $menu_search_commun;
$menu_search[1] = $menu_search_commun;
$menu_search[2] = $menu_search_commun;
$menu_search[3] = $menu_search_commun;
$menu_search[4] = $menu_search_commun;
$menu_search[6] = $menu_search_commun;

// le menu de recherche pour la visu périodique
$menu_search[5] = "<div class=\"row\">
	<h1>!!nom_serial!!</h1></div>
".$menu_search_commun;

//	----------------------------
//	Form: Other Search
//	----------------------------
$unq = md5(microtime());
$RESA_other_search ="
<script type='text/javascript'>
	function test_form(form) {
		// on checke si le champ de saisie est renseigné
		if(form.other_query.value.length == 0) {
			alert(\"$msg[414]\");
			document.forms['other_search_form'].elements['other_query'].focus();
			return false;
			}
		return true;
	}
</script>

<form class='form-$current_module' id='other_search_form' name='other_search_form' method='post' action='./circ.php?categ=resa&mode=4&id_empr=$id_empr&groupID=$groupID&unq=$unq'>
<h3>".$msg[4053]."</h3>

<div class='form-contenu'>
<!--	Termes de recherche	-->
<div class='row'>
	<label class='etiquette' for='other_query'>$msg[label_search_terms]</label>
	</div>
<div class='row'>
	<input class='saisie-80em' id='other_query' type='text' value='!!other_query!!' name='other_query' />
	</div>

<!--	Chercher tous les mots	-->
<div class='row'>
	<input type='radio' id='search_type' name='search_type' value='1' checked='checked' />$msg[905]
	<input type='radio' id='search_type' name='search_type' value='0' />$msg[906]
	</div>
	
<div class='row'>
		<label class='etiquette' for='bla'>$msg[where_to_search]</label>
		</div>
	<div class='row'>
		<div class='saisie-contenu'>
			<input type='checkbox' id='n_resume_flag' name='n_resume_flag' checked='checked' value='1' />$msg[1903] / $msg[1904]
			</div>
		</div>
	<div class='row'>
		<div class='saisie-contenu'>
			<input type='checkbox' id='n_gen_flag' name='n_gen_flag' checked='checked' value='1' />$msg[1912]
			</div>
		</div>
	<div class='row'>
		<div class='saisie-contenu'>
			<input type='checkbox' id='n_titres_flag' name='n_titres_flag' checked='checked' value='1' />$msg[1910]
			</div>
		</div>
	<div class='row'>
		<div class='saisie-contenu'>
			<input type='checkbox' id='n_matieres_flag' name='n_matieres_flag' checked='checked' value='1' />$msg[1911]
			</div>
		</div>
<!--	Formes fléchies
<div class='row'>
	<label for='etiquette'>$msg[1906]$msg[1907]</label>
	</div>
<div class='row'>
	<input type='radio' id='accept_subset' name='accept_subset' value='1' checked='checked' />$msg[1906]
	</div>
<div class='row'>
	<input type='radio' id='accept_subset' name='accept_subset' value='0' />$msg[1909]
	</div>
-->
<hr class='spacer' />

<!--	Résultats par page	-->
<div class='row'>
	<label class='etiquette' for='res_per_page'>$msg[1905]$msg[1901]</label>
	<select id='res_per_page' name='res_per_page'>
		<option value='5'>5</option>
		<option value='10'>10</option>
		<option value='15'>15</option>
		<option value='20'>20</option>
		<option value='25'>25</option>
		<--<option value='$nb_per_page_a_search' selected='selected'>$nb_per_page_a_search</option>-->
		</select>
	</div>

</div>

<!--	Bouton d'envoi	-->
<div class='row'>
	<input class='bouton' type='submit' value='$msg[142]' onClick=\"return test_form(this.form)\" />
	</div>
</form>

<script type='text/javascript'>
      document.forms['other_search_form'].elements['other_query'].focus();
</script>
";

$resa_liste_jscript_GESTION_INFO_GESTION = "
	<script type='text/javascript' src='./javascript/ajax.js'></script>
	<script type='text/javascript'>
		function choisiExpl(obj) {	
			
		
			kill_frame_expl();
		
			var pos=findPos(obj);
			var id_resa = 	obj.getAttribute('id_resa');	
			var notice = 	obj.getAttribute('idnotice');	
			var bul = 		obj.getAttribute('idbul');	
			var loc = 		obj.getAttribute('loc');	
			
			var url='./circ/listeresa/liste_expl_dispo.php?idnotice='+notice+'&idbulletin='+bul+'&loc='+loc+'&id_resa='+id_resa;
			var expl_view=document.createElement('iframe');
			expl_view.setAttribute('id','frame_trans_expls');
			expl_view.setAttribute('name','expls');
			expl_view.src=url; 
			
			var att=document.getElementById('att');	
			expl_view.style.visibility='hidden';
			expl_view.style.display='block';
			expl_view=att.appendChild(expl_view);

			expl_view.style.position='relative';
			expl_view.style.zIndex='1000';
			expl_view.style.left=(pos[0]-500)+'px';
			expl_view.style.top=(pos[1])+'px';
						
			expl_view.style.visibility='visible';						
		}
		
		function kill_frame_expl() {
			var expl_view=document.getElementById('frame_trans_expls');
			if (expl_view)
				expl_view.parentNode.removeChild(expl_view);	
		}
		
		function selExpl(cbExpl,id_resa) {
			kill_frame_expl();
			cbinp = document.getElementById('form_cb_expl');
			cbinp.value = cbExpl;
			cbinp.name = 'cb_trans';
			
			res = document.getElementById('transfert_id_resa');
			res.value = id_resa;
			
			document.saisie_cb_ex.submit();
		}
		</script>		
";

$ajout_resa_jscript_choix_loc_retrait = "
	<script type='text/javascript' src='./javascript/ajax.js'></script>
	<script>
		function chgLocRetrait(idResa,idLoc) {
			var url= './ajax.php?module=circ&categ=transferts&action=loc_retrait&id=' + idResa + '&loc=' + idLoc;
			var maj_loc = new http_request();
			if(maj_loc.request(url,false,'',false)){
				// Il y a une erreur. Afficher le message retourné
				alert ( '" . $msg["540"] . " : ' + maj_loc.get_text() );			
			} else {
				document.getElementById('msg_chg_loc').innerHTML = maj_loc.get_text();
			}
		}
	</script>
	
";

