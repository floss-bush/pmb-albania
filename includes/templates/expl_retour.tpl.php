<?php
// +-------------------------------------------------+
// ? 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: expl_retour.tpl.php,v 1.9 2010-11-09 08:59:25 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

$script_magnetique_tpl="
<script language='javascript' type='text/javascript'>
var requete = null;

function creerRequette(){
	if(window.XMLHttpRequest) // Firefox
		requete = new XMLHttpRequest();
	else if(window.ActiveXObject) // Internet Explorer
  		requete = new ActiveXObject('Microsoft.XMLHTTP');
	else { // XMLHttpRequest non supporté par le navigateur
   		alert('Votre navigateur ne supporte pas les objets XMLHTTPRequest...');
    	return;
	}
}

function magnetise(commande){
	creerRequette();
	try {
  		netscape.security.PrivilegeManager.enablePrivilege('UniversalBrowserRead'); 		
  	} catch (e) {
  	  alert(e);
  	  exit();
 	} 
	requete.open('GET', 'http://localhost:30000/?send_value='+commande+'&command=Send', false);
	requete.send(null);
	if(requete.readyState != 4) alert('Requête antivol non effectuée !');
}
<!--call_script_magnetique-->
</script>
";

$script_antivol_rfid="
<script language='javascript' type='text/javascript'>
	flag_antivol_retour	=1;
	init_rfid_antivol ('!!expl_cb!!',1,ack_antivol_retour);	
	function ack_antivol_retour(){		
		flag_antivol_retour	=0;
		flag_semaphore_rfid=0;
		flag_semaphore_rfid_read=0;
		init_rfid_read_cb(0,f_expl);
	}	
</script>
";

$confirmation_retour_tpl="
<div class='right'>
	<input type='button' class='bouton' 
		name='confirm_ret' value='".$msg['retour_confirm']."'
		onClick=\"document.location='./circ.php?categ=retour&cb_expl=!!expl_cb!!'\">
</div>";

$retour_ok_tpl="
<div class='right'>
	<font color='RED'><b>".$msg["retour_ok"]."</b></font>
</div>
";	
$retour_intouvable_tpl="
<div class='right'>
	<font color='RED'><b>".$msg[605]."</b></font>
</div>
";	

$form_retour_tpl="		
	<!--antivol_script-->
	!!html_erreur_site_tpl!!
	!!piege_resa_ici!!	
	!!message_del_pret!!	
	!!message_resa!!
	!!message_transfert!!
	<div class='row'>
		<div class='left'>
			<strong>!!libelle!!</strong>
		</div>
		!!message_retour!!
	</div>	
	<div class='row'>		
		<table border='0' cellspacing='1'>
			<tr>
				<th>".$msg[293]."</th>
				<th>".$msg[296]."</th>
				<th>".$msg[294]."</th>
				<th>".$msg[298]."</th>
				<th>".$msg[295]."</th>
				<th>".$msg[651]."</th>
				<th></th>
			</tr>
			<tr>
				<td><a href='circ.php?categ=visu_ex&form_cb_expl=!!expl_cb!!'>!!expl_cb!!</a></td>
				<td>!!expl_cote!!</td>
				<td>!!type_doc!!</td>
				<td>!!location!!</td>
				<td>!!section!!</td>
				<td>!!expl_owner!!</td>
				<td><img src='./images/basket_small_20x20.gif' alt='basket' title='".$msg[400]."' onclick=\"openPopUp('./cart.php?object_type=EXPL&item=!!expl_id!!', 'cart', 600, 700, -2, -2, 'toolbar=no, dependent=yes, resizable=yes, scrollbars=yes')\" align='middle'></td>				
			</tr>
		</table>
		!!perso_aff!!	
	</div>
	!!expl_note!!		
	!!expl_lastempr!!		
	!!expl_empr!!	
";
$rfid_retour_script = "
!!script!!
$rfid_js_header
	<script type='text/javascript'>
		var cb_lu =new Array();	
		var post_rfid=0;
		window.onfocus=function(){rfid_focus_active=1;}
		window.onblur=function(){rfid_focus_active=0;}
		
		//antivol_test//					
		init_rfid_read_cb(0,f_expl);
		//memo_cb_rfid_js//
		if(!memo_cb_rfid_js.length) post_rfid=1;
		
		function f_expl(cb,index,indexcount,antivol) {
			// il y a une ou plusieurs étiquette rfid
			var indication='';
			var nb_parties=0;
			
			// vérif des parties		
			var info_cb_list=new Array();
			var info_cb_count_verif=new Array();
			var info_cb_count=new Array();
			list_erreur_cb_count=new Array();
			if(indexcount) {
				for (j=0; j<cb.length; j++) {					
					if(	indexcount[j]>1) {
						if(!info_cb_count_verif[cb[j]]) info_cb_count_verif[cb[j]]=0;
						info_cb_count_verif[cb[j]]++;
						info_cb_count[cb[j]]=indexcount[j];
					}		
				}
				for(var obj_cb in info_cb_count_verif){
					nb_parties+=info_cb_count_verif[obj_cb]-1;
					if(info_cb_count[obj_cb] !=	info_cb_count_verif[obj_cb]) {
						list_erreur_cb_count[obj_cb]=info_cb_count[obj_cb] - info_cb_count_verif[obj_cb];
					}	
				}
			}
						
			var count_present=0;
			for (var j=0; j<memo_cb_rfid_js.length;j++) {		
				for (var i=0; i<cb.length;i++) {								
					if(memo_cb_rfid_js[j]==cb[i]){
						count_present++;
					}
				}				
			}			
			if(cb.length-nb_parties) indication=\"<font size='2'>( \" + count_present+ \" / \" + (cb.length-nb_parties) +\" )</font>\";
			document.getElementById('indicateur_nb_doc').innerHTML=indication;
		
			if(!post_rfid)return;
			for (var i=0; i<cb.length;i++) {					
				var found=0;
				for (var j=0; j<memo_cb_rfid_js.length;j++) {					
					if(memo_cb_rfid_js[j]==cb[i]){
						found=1;
					}
				}	
				if(!found) {		
					if(list_erreur_cb_count[cb[i]]) {
						alert('Nombre d\'éléments manquants: '+list_erreur_cb_count[cb[i]]);
					}
					// ce cb n'a pas encore été rendu, on le traite
					document.getElementById('form_cb_expl').value=cb[i];
					document.saisie_cb_ex.submit();
					break;
				}			
			}			
		}	
		function f_submit_rfid(form) {
			if(form.form_cb_expl.value.length == 0) {
				post_rfid=1;
				return false;
			}
			return true;		
		}		
	</script>
	
	<h1>!!title!!</h1>
	<form class='form-$current_module' name='saisie_cb_ex' method='post' action='!!form_action!!' onSubmit=\"return f_submit_rfid(this);\">	
		<div class='row'>
			<label class='etiquette' for='form_cb_expl'>!!message!!</label>
			</div>
		<div class='row'>
			<input class='saisie-20em' type='text' id='form_cb_expl' name='form_cb_expl' value=''  />
			<input type='hidden' id='expl_cb' name='expl_cb' value='!!expl_cb!!' />
			&nbsp;&nbsp;
			<input type='submit' class='bouton' value='$msg[502]' />&nbsp;<span class='erreur' id='indicateur_nb_doc'></span>
		</div>
		<!--memo_cb_rfid_form-->
	</form>
	<script type='text/javascript'>
		document.forms['saisie_cb_ex'].elements['form_cb_expl'].focus();
	</script>
";