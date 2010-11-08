<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: rfid.tpl.php,v 1.12 2010-09-22 08:53:25 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

// templates pour la gestion rfid

if($pmb_rfid_driver=="ident") $script_erase="init_rfid_erase(rfid_ack_erase);";
else $script_erase="rfid_ack_erase(ack);";

$rfid_programmer_tmpl ="
$rfid_js_header
<script type='text/javascript'>
	var cb_expl,cb_type;
	var count=0;
	function commit_cb(cb_doc) {
		cb_expl = document.getElementById('form_cb_expl').value;	
		if(cb_expl=='') {
			document.getElementById('form_cb_expl').focus();
			return;
		}
		init_rfid_detect(rfid_ack_detect);
	}

	function rfid_ack_detect (ack) {
		if(ack == 'false') {
			alert (\"".addslashes($msg['rfid_etiquette_non_presente_message'])."\");
			document.getElementById('form_cb_expl').value='';	
			document.getElementById('form_cb_expl').focus();
			return;
		}
		$script_erase
	}
	
	function rfid_ack_erase(ack) {	
		cb_type = document.getElementById('cb_type').checked;
		if(cb_type == true) {
			var nb_parts=document.getElementById('nb_parts').value;
			if(!(nb_parts >0)) nb_parts=1;
			init_rfid_write_etiquette(cb_expl,nb_parts,rfid_ack_write);
		}	
		else
			init_rfid_write_empr(cb_expl,rfid_ack_write_empr);
	}
	function rfid_ack_write(ack) {
		init_rfid_antivol_all(1,rfid_ack_write_antivol);
	}
	function rfid_ack_write_empr(ack) {
		//alert (\"".addslashes($msg['rfid_etiquette_programmee_message'])."\");
		add_ligne();
		count++;
		document.getElementById('form_cb_expl').value='';	
		document.getElementById('form_cb_expl').focus();
	}
	
	function rfid_ack_write_antivol(ack) {
		//alert (\"".addslashes($msg['rfid_etiquette_programmee_message'])."\");
		add_ligne();
		count++;
		document.getElementById('form_cb_expl').value='';	
		document.getElementById('form_cb_expl').focus();
	}

	function rfid_ack_antivol_actif(ack) {
		alert (\"".addslashes($msg['rfid_antivol_active_message'])."\");
	}
	function rfid_ack_antivol_inactif(ack) {
		alert (\"".addslashes($msg['rfid_antivol_desactive_message'])."\");
	}
	function add_ligne() {
		var tr = document.createElement('TR');
		if(count%2) {
			tr.setAttribute('class','odd');
			tr.setAttribute('onmouseout','this.className=\'odd\'');
		}else {
			tr.setAttribute('class','even');
			tr.setAttribute('onmouseout','this.className=\'even\'');
		}
		tr.setAttribute('onmouseover','this.className=\'surbrillance\'');

		//exemplaire
		var td = document.createElement('TD');
		td.appendChild(document.createTextNode(cb_expl));	
		tr.appendChild(td);

		var td = document.createElement('TD');
		td.setAttribute('class','erreur');
		td.appendChild(document.createTextNode('".addslashes($msg['rfid_cb_programation_ok_message'])."'));	
		tr.appendChild(td);	

		document.getElementById('table_cb').appendChild(tr);
	}
</script>

<h1>".$msg['rfid_programmation_etiquette_titre']."</h1>
<form class='form-$current_module' name='rfid_prog' onSubmit='commit_cb();return false;' >
<h3>".$msg['rfid_programmation_etiquette_titre_form']."</h3>
<div class='form-contenu'>
	<div class='row'>
		<input name='cb_type' id='cb_type' value='0' checked='checked' type='radio'>".$msg['rfid_cb_type_etiquette']."
		<input name='cb_type' id='cb_type' value='1' type='radio'>".$msg['rfid_cb_type_lecteur']."
	</div>
	<div class='row'>
		<label class='etiquette' for='form_cb_expl'>".$msg['rfid_cb_titre']."</label>
	</div>
	<div class='row'>
		<input class='saisie-20em' type='text' id='form_cb_expl' name='form_cb_expl' value='' />
		<input class='saisie-5em' type='text' id='nb_parts' name='nb_parts' value='1'  />
		<input type='submit' class='bouton' value='$msg[502]'/>
	</div>
	<div class='row'>
		<table id='table_cb' name='table_cb'>
		</table>
	</div>	
</div>
</form>

<form class='form-$current_module' name='rfid_prog_antivol' onSubmit='commit_cb();return false;' >
<h3>".$msg['rfid_programmation_antivol_titre_form']."</h3>
<div class='form-contenu'>
	<div class='row'>
		<input type='button' class='bouton' value='".$msg['rfid_activation_antivol_bouton']."' onclick='init_rfid_antivol_all(1,rfid_ack_antivol_actif);'/>
		<input type='button' class='bouton' value='".$msg['rfid_desactivation_antivol_bouton']."' onclick='init_rfid_antivol_all(0,rfid_ack_antivol_inactif);'/>
	</div>
</div>	
</form>
<script type='text/javascript'>
document.forms['rfid_prog'].elements['form_cb_expl'].focus();
</script>
";


$rfid_effacer_tmpl ="
$rfid_js_header
<script type='text/javascript'>
	var cb_expl,cb_type;

	function commit_effacer(cb_doc) {
		init_rfid_detect(rfid_ack_detect);
	}

	function rfid_ack_detect (ack) {
		if(ack == 'false') {
			alert (\"".addslashes($msg['rfid_etiquette_non_presente_message'])."\");
			return;
		}
		init_rfid_erase(rfid_ack_erase);
	}
	
	function rfid_ack_erase(ack) {	
		alert (\"".addslashes($msg['rfid_cb_effacement_ok_message'])."\");
	}

</script>

<h1>".$msg['rfid_effacement_etiquette_titre']."</h1>
<form class='form-$current_module' name='rfid_prog' onSubmit='commit_cb();return false;' >
<h3>".$msg['rfid_effacement_etiquette_titre']."</h3>
<div class='form-contenu'>

	<div class='row'>
		<input type='button' class='bouton' value='".$msg['rfid_effacement_bouton']."' onclick='commit_effacer()'/>
		</div>
	<div class='row'>
		<table id='table_cb' name='table_cb'>
	</table>
</div>
</form>
";

$rfid_lire_tmpl ="
$rfid_js_header
<script type='text/javascript'>
	
	var count=0;
	var count_lecteur=0;
	init_rfid_read_cb(f_lecteur,f_expl);
	
	function f_lecteur(cb) {
		var i;	
		// il y a une ou plusieurs étiquette rfid		
		del_ligne_lecteur();
		for (i=0; i < cb.length; i++) {		
			add_ligne_lecteur( cb[i]);
			count_lecteur++;
		}	
	}

	function f_expl(cb,index,indexcount,antivol) {
		var i;
		var info='';
		// il y a une ou plusieurs étiquette rfid
		del_ligne();

		for (i=0; i < cb.length; i++) {		
		info='';
			if(indexcount) {
				if(indexcount[i]>1) {
					info=', '+index[i]+'/'+indexcount[i];
				}	
				if(antivol[i]) {
					info+=', Antivol: '+antivol[i];
				}	
			}				
			add_ligne_expl( cb[i] + info);
			count++;
		}
	}

	function del_ligne() {	
		var i;
		for (i=0; i < count; i++) {		
			// Supression du pret dans l'affichage	
			var tr = document.getElementById('tr_'+i);	
			document.getElementById('table_cb').removeChild(tr);
	    }	
		count=0;
	}
	
	function del_ligne_lecteur() {	
		var i;
		for (i=0; i < count_lecteur; i++) {		
			// Supression du pret dans l'affichage	
			var tr = document.getElementById('tr_lecteur_'+i);	
			document.getElementById('table_lecteur').removeChild(tr);
	    }	
		count_lecteur=0;
	}
	
	function add_ligne_expl(cb_expl) {	
		var tr = document.createElement('TR');

		tr.setAttribute('id', 'tr_'+count);
		if(count%2) {
			tr.setAttribute('class','odd');
			tr.setAttribute('onmouseout','this.className=\'odd\'');
		}else {
			tr.setAttribute('class','even');
			tr.setAttribute('onmouseout','this.className=\'even\'');
		}
		tr.setAttribute('onmouseover','this.className=\'surbrillance\'');

		//exemplaire
		var td = document.createElement('TD');
		td.appendChild(document.createTextNode(cb_expl));	
		tr.appendChild(td);

		document.getElementById('table_cb').appendChild(tr);
	}
	
	function add_ligne_lecteur(cb_lecteur) {
		var tr = document.createElement('TR');

		tr.setAttribute('id', 'tr_lecteur_'+count_lecteur);
		if(count_lecteur%2) {
			tr.setAttribute('class','odd');
			tr.setAttribute('onmouseout','this.className=\'odd\'');
		}else {
			tr.setAttribute('class','even');
			tr.setAttribute('onmouseout','this.className=\'even\'');
		}
		tr.setAttribute('onmouseover','this.className=\'surbrillance\'');

		//cb lecteur
		var td = document.createElement('TD');
		td.appendChild(document.createTextNode(cb_lecteur));	
		tr.appendChild(td);

		document.getElementById('table_lecteur').appendChild(tr);
	}
</script>

<h1>".$msg['rfid_lecture_etiquette_titre']."</h1>
<form class='form-$current_module' name='rfid_prog' onSubmit='commit_cb();return false;' >
<h3>".$msg['rfid_lecture_etiquette_titre_form']." ( ".$_SERVER['REMOTE_ADDR']." )</h3>
<div class='form-contenu'>
	<div class='row'>
		<h3>".$msg['rfid_cb_type_etiquette'].":</h3>
	</div>
	<div class='row'>
		<table id='table_cb' name='table_cb'>
		</table>
	</div>
	<div class='row'>
	<hr />
		<h3>".$msg['rfid_cb_type_lecteur'].":</h3>
	</div>
	<div class='row'>
		<table id='table_lecteur' name='table_lecteur'>
		</table>
	</div>	
</div>
</form>
";

