<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: demandes.tpl.php,v 1.8 2011-03-24 18:34:19 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

// $acquisition_menu : menu page acquisition
$demandes_menu = "
<div id='menu'>
	<h3 onclick='menuHide(this,event)'>".$msg['demandes_menu_liste']."</h3>
	<ul>
		<li><a href='./demandes.php?categ=list&idetat=0'>".$msg['demandes_menu_all']."</a></li>
		<li><a href='./demandes.php?categ=list&idetat=1'>".$msg['demandes_menu_a_valide']."</a></li>
		<li><a href='./demandes.php?categ=list&idetat=2&iduser=".SESSuserid."'>".$msg['demandes_menu_en_cours']."</a></li>
		<li><a href='./demandes.php?categ=list&idetat=3&iduser=".SESSuserid."'>".$msg['demandes_menu_refuse']."</a></li>
		<li><a href='./demandes.php?categ=list&idetat=4&iduser=".SESSuserid."'>".$msg['demandes_menu_fini']."</a></li>
		<li><a href='./demandes.php?categ=list&idetat=5&iduser=".SESSuserid."'>".$msg['demandes_menu_abandon']."</a></li>
		<li><a href='./demandes.php?categ=list&idetat=6&iduser=".SESSuserid."'>".$msg['demandes_menu_archive']."</a></li>
		<li><a href='./demandes.php?categ=list&iduser=-1'>".$msg[demandes_menu_not_assigned]."</a></li>				
	</ul>	
	<h3 onclick='menuHide(this,event)'>".$msg['demandes_menu_action']."</h3>
	<ul>
		<li><a href='./demandes.php?categ=action&sub=com'>".$msg['demandes_menu_comm']."</a></li>
		<li><a href='./demandes.php?categ=action&sub=rdv_plan'>".$msg['demandes_menu_rdv_planning']."</a></li>
		<li><a href='./demandes.php?categ=action&sub=rdv_val'>".$msg['demandes_menu_rdv_a_valide']."</a></li>
	</ul>
	<div id='div_alert' class='erreur'>$aff_alerte</div>
</div>
";

// $demandes_layout : layout page demandes
$demandes_layout = "
<div id='conteneur' class='$current_module'>
$demandes_menu
<div id='contenu'>
";


// $demandes_layout_end : layout page demandes (fin)
$demandes_layout_end = "
</div>
</div>
";


$form_filtre_demande = "
 <script type='text/javascript'>
	function filtrer_user(){
 		document.forms['search'].submit();
	} 
</script>
<h1>".$msg['demandes_gestion']." : ".$msg['demandes_search_form']."</h1>
<form class='form-".$current_module."' id='search' name='search' method='post' action=\"./demandes.php?categ=list\">
	<h3>".$msg['demandes_search_filtre_form']."</h3>
	<input type='hidden' name='act' id='act' />
	<div class='form-contenu'>
		<div class='row'>
			<label class='etiquette'>".$msg['demandes_titre']."</label>
		</div>
		<div class='row'>
			<input type='texte' class='saisie-30em' name='user_input' id='user_input' value='!!user_input!!'/>
		</div>
		<div class='row'>
			<div class='colonne3'>
				<label class='etiquette'>".$msg['demandes_user_filtre']."</label>
			</div>
			<div class='colonne3'>
				<label class='etiquette'>".$msg['demandes_etat_filtre']."</label>
			</div>
			<div class='colonne3'>
				<label class='etiquette'>".$msg['demandes_periode_filtre']."</label>
			</div>
		</div>
		<div class='row'>
			<div class='colonne3'>
				<input type='hidden' id='idempr' name='idempr' value='!!idempr!!' />
				<input type='text' id='empr_txt' name='empr_txt' class='saisie-20emr' value='!!empr_txt!!'/>
				<input type='button' class='bouton_small' value='...' onclick=\"openPopUp('./select.php?what=origine&caller=search&param1=idempr&param2=empr_txt&deb_rech='+escape(this.form.empr_txt.value)+'&filtre=ONLY_EMPR&callback=filtrer_user".($pmb_lecteurs_localises ? "&empr_loca='+this.form.dmde_loc.value": "'").", 'select_user', 400, 400, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes')\" />
				<input type='button' class='bouton_small' value='X' onclick=\"document.getElementById('idempr').value=0;document.getElementById('empr_txt').value='';\" />
			</div>
			<div class='colonne3'>
				!!state!!
			</div>
			<div class='colonne3'>
				!!periode!!
			</div>
		</div>
		<div class='row'> 
			<div class='colonne3'>
				<label class='etiquette'>".$msg['demandes_affectation_filtre']."</label>
			</div>
			<div class='colonne3'>
				<label class='etiquette'>".$msg['demandes_theme_filtre']."</label>
			</div>
			<div class='colonne3'>
				<label class='etiquette'>".$msg['demandes_type_filtre']."</label>
			</div>
		</div>
		<div class='row'>
			<div class='colonne3'>
				!!affectation!!
			</div>
			<div class='colonne3'>
				!!theme!!
			</div>
			<div class='colonne3'>
				!!type!!
			</div>
		</div>";
if($pmb_lecteurs_localises)
$form_filtre_demande .="
		<div class='row'> 
			<div class='colonne3'>
				<label class='etiquette'>".$msg['demandes_localisation_filtre']."</label>
			</div>
		</div>
		<div class='row'>
			<div class='colonne3'>
				!!localisation!!
			</div>
		</div>";
$form_filtre_demande .="
		<div class='row'></div>	
	</div>
	<div class='row'></div>
	<div class='row'>
		<input type='submit' class='bouton' name='search_dmd' id='search_dmd' value='".$msg['demandes_search']."' onclick='this.form.act.value=\"search\"'/>
		<input type='submit' class='bouton' name='new_dmd' id='new_dmd' value='".$msg['demandes_new']."' onclick='this.form.act.value=\"new\"'/>
	</div>
</form>

";

$form_liste_demande ="
<script src='./javascript/dynamic_element.js' type='text/javascript'></script>
<script type='text/javascript'>
 function verifChk(txt) {
		
	var elts = document.forms['liste'].elements['chk[]'];
	var elts_cnt  = (typeof(elts.length) != 'undefined')
              ? elts.length
              : 0;
	nb_chk = 0;
	if (elts_cnt) {
		for(var i=0; i < elts.length; i++) {
			if (elts[i].checked) nb_chk++;
		}
	} else {
		if (elts.checked) nb_chk++;
	}
	if (nb_chk == 0) {
		alert(\"".$msg['demandes_nocheck']."\");
		return false;	
	}
	
	if(txt == 'suppr'){
		var sup = confirm(\"".$msg['demandes_confirm_suppr']."\");
		if(!sup) 
			return false;
		return true;
	}
	
	return true;
}

function alert_progressiondemande(){
	alert(\"".$msg['demandes_progres_ko']."\");
}
</script>
<form class='form-".$current_module."' id='liste' name='liste' method='post' action=\"./demandes.php?categ=list\">
	<input type='hidden' name='act' id='act' />
	<input type='hidden' name='state' id='state' />
	<h3>".$msg['demandes_liste']."</h3>
	<div class='form-contenu'>
		<table>
			<tbody>
				<tr>
					<th>".$msg['demandes_theme']."</th>
					<th>".$msg['demandes_type']."</th>
					<th>".$msg['demandes_titre']."</th>
					<th>".$msg['demandes_etat']."</th>
					<th>".$msg['demandes_date_dmde']."</th>
					<th>".$msg['demandes_date_prevue']."</th>
					<th>".$msg['demandes_date_butoir']."</th>
					<th>".$msg['demandes_demandeur']."</th>
					<th>".$msg['demandes_attribution']."</th>
					<th>".$msg['demandes_progression']."</th>
					<th>".$msg['demandes_notice']."</th>					
					<th></th>
				</tr>
				!!liste_dmde!!				
			</tbody>
		</table>
	</div>
	<div class='row'>
		<div class='left'>
			!!btn_etat!!
			!!btn_attribue!!
		</div>
		<div class='right'>
			!!btn_suppr!!
		</div>
	</div>
	<div class='row'></div>
</form>	
<script>parse_dynamic_elts();</script>
";

$form_modif_demande = "
<script type='text/javascript'>
	function confirm_delete(){
		
		var sup = confirm(\"".$msg['demandes_confirm_suppr']."\");
		if(!sup)
			return false;
		return true;	
	}
</script>
<h1>".$msg['demandes_gestion']." : ".$msg['admin_demandes']."</h1>
<form class='form-".$current_module."' id='modif_dmde' name='modif_dmde' method='post' action=\"!!form_action!!\">
	<h3>!!form_title!!</h3>
	<input type='hidden' id='act' name='act' />
	<input type='hidden' id='iddemande' name='iddemande' value='!!iddemande!!'/>
	<div class='form-contenu'>
		<div class='row'>
			<div class='colonne2'>		
				<label class='etiquette'>".$msg['demandes_theme']."</label>
			</div>
			<div class='colonne2'>
				<label class='etiquette'>".$msg['demandes_type']."</label>
			</div>
		</div>
		<div class='row'>
			<div class='colonne2'>
				!!select_theme!!
			</div>
			<div class='colonne2'>
				!!select_type!!
			</div>
		</div>
			<div class='row'>
			<div class='colonne2'>
				<label class='etiquette'>".$msg['demandes_etat']."</label>
			</div>
			<div class='colonne2'>
				<label class='etiquette'>".$msg['demandes_progression']."</label>
			</div>
		</div>
		<div class='row'>
			<div class='colonne2'>
				!!select_etat!!
			</div>
			<div class='colonne2'>
				<input type='texte' class='saisie-10em' name='progression' id='progression' value='!!progression!!' />
			</div>
		</div>	
		<div class='row'>
			<label class='etiquette'>".$msg['demandes_titre']."</label>
		</div>
		<div class='row'>
			<input class='saisie-50em' type='texte' id='titre' name='titre' value='!!titre!!' />
		</div>
		<div class='row'>
			<label class='etiquette'>".$msg['demandes_sujet']."</label>
		</div>
		<div class='row'>
			<textarea id='sujet' name='sujet' cols='55' rows='4' wrap='virtual'>!!sujet!!</textarea>
		</div>
		<div class='row'>
			<div class='colonne3'>
				<label class='etiquette'>".$msg['demandes_date_dmde']."</label>
			</div>
			<div class='colonne3'>
				<label class='etiquette'>".$msg['demandes_date_prevue']."</label>
			</div>
			<div class='colonne3'>
				<label class='etiquette'>".$msg['demandes_date_butoir']."</label>
			</div>
		</div>
		<div class='row'>
			<div class='colonne3'>
				<input type='hidden' id='date_debut' name='date_debut' value='!!date_debut!!' />
				!!date_demande!!
			</div>
			<div class='colonne3'>
				<input type='hidden' id='date_prevue' name='date_prevue' value='!!date_prevue!!' />
				<input type='button' class='bouton' id='date_prevue_btn' name='date_prevue_btn' value='!!date_prevue_btn!!' onClick=\"openPopUp('./select.php?what=calendrier&caller=modif_dmde&date_caller=!!date_prevue!!&param1=date_prevue&param2=date_prevue_btn&auto_submit=NO&date_anterieure=YES', 'date_prevue', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\"/>
			</div>
			<div class='colonne3'>
				<input type='hidden' id='date_fin' name='date_fin' value='!!date_fin!!' />
				<input type='button' class='bouton' id='date_fin_btn' name='date_fin_btn' value='!!date_fin_btn!!' onClick=\"openPopUp('./select.php?what=calendrier&caller=modif_dmde&date_caller=!!date_fin!!&param1=date_fin&param2=date_fin_btn&auto_submit=NO&date_anterieure=YES', 'date_fin', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\"/>
			</div>
		</div>
		<div class='row'>
			<div class='colonne3'>
				<label class='etiquette'>".$msg['demandes_demandeur']."</label>
			</div>
			<div class='colonne3'>
				<label class='etiquette'>".$msg['demandes_attribution']."</label>
			</div>
			<div class='colonne3'>&nbsp;</div>
		</div>
		<div class='row'>
			<div class='colonne3'>
				<input type='hidden' id='idempr' name='idempr' value='!!idempr!!' />
				<input type='text' id='empr_txt' name='empr_txt' class='saisie-20emr' value='!!empr_txt!!'/>
				<input type='button' class='bouton_small' value='X'	onclick=\"this.form.id_empr.value='0';this.form.empr_txt.value='';\"/>	
				<input type='button' class='bouton_small' value='...' onclick=\"openPopUp('./select.php?what=origine&caller=modif_dmde&param1=idempr&param2=empr_txt&deb_rech='+escape(this.form.empr_txt.value)+'&filtre=ONLY_EMPR', 'select_user', 400, 400, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes')\" />
			</div>
			<div class='colonne3'>
				!!select_user!!
			</div>
			<div class='colonne3'>&nbsp;</div>
		</div>	
		<div class='row'></div>	
	</div>
	<div class='row'>
		<div class='left'>
			<input type='button' class='bouton' value='$msg[76]' onClick=\"!!cancel_action!!\" />
			<input type='submit' class='bouton' value='$msg[77]' onClick='this.form.act.value=\"save\" ; return test_form(this.form); ' />
		</div>
		<div class='right'>
			!!btn_suppr!!
		</div>
	</div>
	<div class='row'></div>
</form>

<script type='text/javascript'>
	function test_form(form) {	

		if(isNaN(form.progression.value) || form.progression.value > 100){
	    	alert(\"$msg[demandes_progres_ko]\");
			return false;
	    }
		if((form.titre.value.length == 0) ||  (form.empr_txt.value.length == 0) || (form.date_debut.value.length == 0)||  (form.date_fin.value.length == 0)){
			alert(\"$msg[demandes_create_ko]\");
			return false;
	    } 
	    
	    var deb =form.date_debut.value;
	    var fin = form.date_fin.value;
	   
	    if(deb>fin){
	    	alert(\"$msg[demandes_date_ko]\");
	    	return false;
	    }
		return true;
			
	}
</script>
";

$form_consult_dmde = "
<h1>".$msg['demandes_gestion']." : ".$msg['admin_demandes']."</h1>
<script src='./javascript/demandes.js' type='text/javascript'></script>
<script src='./javascript/tablist.js' type='text/javascript'></script>
<script src='./javascript/select.js' type='text/javascript'></script>
<script type='text/javascript'>
	function confirm_delete(){
		
		var sup = confirm(\"".$msg['demandes_confirm_suppr']."\");
		if(!sup)
			return false;
		return true;	
	}
	
	function alert_progressiondemande(){
		alert(\"".$msg['demandes_progres_ko']."\");
	}
</script>
<form class='form-".$current_module."' id='see_dmde' name='see_dmde' method='post' action=\"./demandes.php?categ=gestion\">
	<h3>!!icone!!!!form_title!!</h3>
	<input type='hidden' id='iddemande' name='iddemande' value='!!iddemande!!'/>
	<input type='hidden' id='act' name='act' />
	<input type='hidden' id='state' name='state' />
	<div class='form-contenu'>
		<div class='row'>
			<div class='colonne3'>
				<label class='etiquette'>".$msg['demandes_theme']." : </label>
				!!theme_dmde!!
			</div>
			<div class='colonne3'>		
				<label class='etiquette'>".$msg['demandes_etat']." : </label>
				!!etat_dmde!!
			</div>
			<div class='colonne3'>
				<label class='etiquette'>".$msg['demandes_date_dmde']." : </label>
				!!date_dmde!!
			</div>
		</div>
		<div class='row'>
			<div class='colonne3'>
				<label class='etiquette'>".$msg['demandes_sujet']." : </label>
				!!sujet_dmde!!
			</div>
			<div class='colonne3'>
				<label class='etiquette'>".$msg['demandes_demandeur']." : </label>
				!!demandeur!!
			</div>
			<div class='colonne3'>
				<label class='etiquette'>".$msg['demandes_date_prevue']." : </label>
				!!date_prevue_dmde!!
			</div>
		</div>
		<div class='row'>
			<div class='colonne3'>
				<label class='etiquette'>".$msg['demandes_type']." : </label>
				!!type_dmde!!
			</div>
			<div class='colonne3'>
				<label class='etiquette'>".$msg['demandes_attribution']." : </label>
				!!attribution!!
			</div>
			<div class='colonne3'>
				<label class='etiquette'>".$msg['demandes_date_butoir']." : </label>
				!!date_butoir_dmde!!
			</div>
		</div>	
		
		<div class='row'>
			<div class='colonne3'>
				&nbsp;
			</div>	
			<div class='colonne3'>
				&nbsp;			
			</div>
			<div class='colonne3'>
				<label class='etiquette' >".$msg['demandes_progression']." : </label>
				<span id='progressiondemande_!!iddemande!!' name='progressiondemande_!!iddemande!!' dynamics='demandes,progressiondemande' dynamics_params='text'>!!progression_dmde!!</span>
			</div>
		</div>
		<div class='row'></div>
	</div>
	<div class='row'>
		!!btn_etat!!
	</div>
	<div class='row'>
		<div class='left'>
			<input type='button' class='bouton' value='".$msg['demandes_retour']."' onClick=\"document.location='./demandes.php?categ=list'\" />
			<input type='submit' class='bouton' value='$msg[62]' onClick='this.form.act.value=\"modif\" ; ' />			
			!!btns_notice!!
		</div>
		<div class='right'>
			<input type='submit' class='bouton' value='$msg[63]' onClick='this.form.act.value=\"suppr_noti\" ; return confirm_delete();' />
		</div>
	</div>
	<div class='row'></div>
</form>
";

$form_liste_docnum ="
<form class='form-".$current_module."' id='liste_action' name='liste_action' method='post'>
	<h3 id='htitle'>".$msg['demandes_liste_docnum']."</h3>
	<input type='hidden' id='act' name='act' />
	<input type='hidden' id='iddemande' name='iddemande' value='!!iddemande!!'/>
	<div class='form-contenu' >
		<div class='row'>
			!!liste_docnum!!	
		</div>
	</div>
	<div class='row'>
		<div class='left'>
			<input type='button' class='bouton' value='".$msg['demandes_retour']."' onClick=\"history.go(-1)\" />
			!!btn_attach!!	
		</div>
		<div class='right'>
			<input type='button' class='bouton' name='btn_chk' id='btn_chk' value='".$msg['tout_cocher_checkbox']."' onClick=\"check_all('liste_action','chk',true);\" />
			<input type='button' class='bouton' name='btn_chk' id='btn_chk' value='".$msg['tout_decocher_checkbox']."' onClick=\"check_all('liste_action','chk',false);\" />
			<input type='button' class='bouton' name='btn_chk' id='btn_chk' value='".$msg['inverser_checkbox']."' onClick=\"inverser('liste_action','chk');\" />
		</div>
	</div>
	<div class='row' /> 
</form>

<script type='text/javascript'>

function check_all(the_form,the_objet,do_check){

	var elts = document.forms[the_form].elements[the_objet+'[]'] ;
	var elts_cnt  = (typeof(elts.length) != 'undefined')
              ? elts.length
              : 0;

	if (elts_cnt) {
		for (var i = 0; i < elts_cnt; i++) {
			elts[i].checked = do_check;
		} 
	} else {
		elts.checked = do_check;
	}
	return true;
}

function inverser(the_form,the_objet){

	var elts = document.forms[the_form].elements[the_objet+'[]'] ;
	var elts_cnt  = (typeof(elts.length) != 'undefined')
              ? elts.length
              : 0;

	if (elts_cnt) {
		for (var i = 0; i < elts_cnt; i++) {
			if(elts[i].checked == true) elts[i].checked = false;
			else elts[i].checked = true;
		} 
	} 
	return true;
}

 function verifChk() {
		
	var elts = document.forms['liste_action'].elements['chk[]'];
	var elts_cnt  = (typeof(elts.length) != 'undefined')
              ? elts.length
              : 0;
	nb_chk = 0;
	if (elts_cnt) {
		for(var i=0; i < elts.length; i++) {
			if (elts[i].checked) nb_chk++;
		}
	} else {
		if (elts.checked) nb_chk++;
	}
	if (nb_chk == 0) {
		var sup = confirm(\"".$msg['demandes_confirm_attach_docnum']."\");
		if(!sup) 
			return false;
		return true;
	}
	
	return true;
}
</script>
";
?>