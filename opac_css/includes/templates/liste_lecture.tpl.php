<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: liste_lecture.tpl.php,v 1.16 2010-08-19 07:35:07 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], "tpl.php")) die("no access");

$liste_lecture_prive = "
<h3><span>$msg[list_lecture_private]</span></h3>
	<div id='onglets_list'>
		<ul class='list_tabs'>
			<li id='!!my_current!!' >
				<a class='!!my_current!!' href='./empr.php?tab=lecture&lvl=private_list&sub=my_list'>$msg[list_lecture_mylist]</a>
			</li>
			<li id='!!current_shared!!'>
				<a class='!!current_shared!!' href='./empr.php?tab=lecture&lvl=private_list&sub=shared_list'>$msg[list_lecture_myshared]</a>
			</li>	
		</ul>
		<div class='row' id='div_mylist'>
			<script>
			function confirm_delete() {
	       			result = confirm(\"${msg[list_lecture_confirm_suppr]}\");
	       			if(result) {
	       				return true;
					} else
	           			return false;
	   			}
			</script>
			!!listes!!
		</div>
	</div>
";

$liste_lecture_public = "
<h3><span>$msg[list_lecture_public]</span></h3>
<script type'text/.javascript'>
function demandeEnCours(){
	alert(\"".$msg['list_lecture_already_requested']."\");
}
</script>
<form  name='liste_lecture_public' method='post' action='./empr.php' >	
<input type='hidden' id='lvl' name='lvl' />
<input type='hidden' id='sub' name='sub' />
<input type='hidden' id='act' name='act' />
<input type='hidden' id='page' name='page' value='' />
	<div id='public_list'>
		<div id='list_cadre' style='border: 1px solid rgb(204, 204, 204); overflow: auto; height: 250px;padding:2px;'>
			!!public_list!!
		</div>
	</div>
	<br />
	<div class='row'>
		!!inscrire_btn!!
		!!desinscrire_btn!!
	</div>
</form>
";


$liste_gestion = "
	
<form class='form_liste_lecture' name='liste_lecture' method='post' action='index.php?lvl=show_list&sub=view&id_liste=!!id_liste!!' >	
	<script type='text/javascript'>
		function delete_from_liste(id_liste,idempr){
			
			var conf = confirm(\"".$msg['list_lecture_delete_subscriber']."\");
			if(conf){
				var action = new http_request();
				var url = './ajax.php?module=ajax&categ=liste_lecture&id='+id_liste+'&empr='+idempr+'&quoifaire=delete_empr';
				action.request(url);
				if(action.get_status() == 0){
					document.getElementById('inscrit_list').innerHTML = action.get_text();
				}
				
			} else return false;	
		}
		
		function confirm_delete_noti(){
			var is_check=false;
			var elts = document.getElementsByName('notice[]') ;
			if (!elts) is_check = false ;
			var elts_cnt  = (typeof(elts.length) != 'undefined')
	                  ? elts.length
	                  : 0;
			if (elts_cnt) {
				for (var i = 0; i < elts_cnt; i++) { 		
					if (elts[i].checked) {
						res = confirm('".$msg[list_lecture_confirm_delete]."');
						if(res) 
							return true;
						else 
							return false;
					}
				}
			} 
			if(!is_check){
				alert('".$msg[list_lecture_no_ck]."');
				return false;
			}
	        
			return is_check;
		}	
	</script>
	<input type='hidden' id='act' name='act' />
	<input type='hidden' id='notice_filtre' name='notice_filtre' value='!!notice_filtre!!' />
	<input type='hidden' id='id_liste' name='id_liste' value='!!id_liste!!' />
	<div class='row'>
		<input type='button' class='bouton' name='cancel' onclick='document.location=\"./empr.php?tab=lecture&lvl=private_list\";' value='".$msg['list_lecture_back']."' />					
		!!print_btn!!
	</div>
	<br />
	<div class='row'>
		<div class='left'>
			<input type='submit' class='bouton' name='list_in' onclick='this.form.act.value=\"list_in\";' value='".$msg['list_lecture_list_in']."' />
			<input type='submit' class='bouton' name='list_out' onclick='this.form.act.value=\"list_out\";this.form.target=\"cart_info\";this.form.action=\"cart_info.php?lvl=listlecture&id=!!id_liste!!\";' value='".$msg['list_lecture_list_out']."' />";
	if($opac_show_suggest && $opac_allow_multiple_sugg)
		 $liste_gestion .= "	<input type='submit' class='bouton' name='multi_sugg' onclick='this.form.act.value=\"transform_list\";this.form.action=\"empr.php?lvl=make_multi_sugg\";' value='".$msg['transform_list_to_multisugg']."' />";						
	$liste_gestion .= "</div>
		<div class='right'>
			<input type='submit' class='bouton' name='suppr_checked' onclick='this.form.act.value=\"suppr_ck\";return confirm_delete_noti(); return false;' value='".$msg['list_lecture_suppr_checked']."' />
			<input type='submit' class='bouton' name='suppr' onclick='this.form.act.value=\"suppr\";this.form.action=\"empr.php?tab=lecture&lvl=private_list\";return confirm_delete();' value='".$msg['list_lecture_suppression']."' />
		</div>	
	</div>
	<div class='row'>			
	</div>
	<h3><span>!!titre_liste!!</span></h3>
	<div class='form-contenu'>
			<script>
			function test_form(form){
				if(form.list_name.value.length == 0){
					alert(\"$msg[list_lecture_name_dont_filled]\");
					return false;
				} 
				return true;
			}
			function confirm_delete() {
       			result = confirm(\"${msg[list_lecture_confirm_suppr]}\");
       			if(result) {
       				return true;
				} else
           			return false;
   			}
   			
   			function activerConfidentiel() {
   				if(document.getElementById('cb_share').checked){
   					document.getElementById('cb_confidential').disabled=false;
   					document.getElementById('lab_conf').style.color = \"black\";
   				}
   				else {
   					document.getElementById('cb_confidential').disabled=true;
   					document.getElementById('lab_conf').style.color = \"gray\";
   				}
   			}
			</script>
			<div class='row'>
				<div class='colonne2'>
					<div class='row'>
						<label class='etiquette'>$msg[list_lecture_name] &nbsp;</label>
					</div>
					<div class='row'>
						<input type='text' class='saisie-20em' name='list_name' value='!!name_list!!' />
					</div>
					<br />
					<div class='row'>
						<label class='etiquette'>$msg[list_lecture_comment] &nbsp;</label>
					</div>
					<div class='row'>	
						<textarea name='list_comment' rows='2' cols='50'/>!!list_comment!!</textarea>
					</div>
					<br />
					<div class='row'>	
						<input type='checkbox' id='cb_share' name='cb_share' !!checked!! onclick=\"activerConfidentiel()\" /><label for='cb_share'>$msg[list_lecture_share_with_users]</label>
						( <input type='checkbox' id='cb_confidential' name='cb_confidential' !!disabled_conf!! !!checked_conf!! /><label id='lab_conf' style=\"color:!!color_conf!!\" for='cb_confidential'>$msg[list_lecture_confidential]</label> ) 
					</div>
					<div class='row'>	
						<input type='checkbox' id='cb_readonly' name='cb_readonly' !!checked_only!!  /><label for='cb_readonly'>$msg[list_lecture_readonly]</label> 
					</div>
					<br />
					<div class='row'>
						<input type='submit' class='bouton' name='save_list' onclick='this.form.act.value=\"save\";this.form.action=\"empr.php?tab=lecture&lvl=private_list\";return test_form(this.form);' value='".$msg['list_lecture_save']."' />
					</div>	
				</div>
				<div class='colonne2'>
					!!inscrit_list!!							
				</div>
				<div class='row'></div>					
			</div>
			<hr />
			<div class='row'>
				!!liste_notice!!
			</div>			
	</div>
</form>
";

$liste_lecture_consultation="
<form class='form_liste_lecture' name='liste_lecture' method='post' action='./index.php?lvl=show_list&sub=consultation&id_liste=!!id_liste!!' >	
		<input type='hidden' id='act' name='act' />
		<input type='hidden' id='notice_filtre' name='notice_filtre' value='!!notice_filtre!!' />
		<input type='hidden' id='id_liste' name='id_liste' value='!!id_liste!!' />
		<div class='row'>
				<input type='button' class='bouton' name='cancel' onclick='document.location=\"./empr.php?tab=lecture&lvl=private_list\";' value='".$msg['list_lecture_back']."' />
				!!abo_btn!!
				<input type='submit' class='bouton' name='list_out' onclick='this.form.act.value=\"list_out\";this.form.target=\"cart_info\";this.form.action=\"cart_info.php?lvl=listlecture&sub=consult&id=!!id_liste!!\";' value='".$msg['list_lecture_list_out']."' />
				!!add_noti_btn!!
			</div>
		<h3><span> !!nom_liste!! !!proprio!!</span></h3>
		<div class='form-contenu'>
			<div id='aut_see' class='row'>
				<label><strong>!!liste_comment!!</strong></label>
			</div>
			<br />
			<div class='row'>
			   !!liste_notice!!
			</div>
			<br />
		</div>
</form>

";

$liste_demande = "
<h3><span>$msg[list_lecture_demande]</span></h3>
<form  name='liste_lecture_demande' method='post' action='./empr.php' >	
<input type='hidden' id='lvl' name='lvl' />
<input type='hidden' id='sub' name='sub' />
<input type='hidden' id='action' name='act' />
	<div id='demande_list'>
		<div id='list_cadre' style='border: 1px solid rgb(204, 204, 204); overflow: auto; height: 200px;padding:2px;'>
			!!demande_list!!
		</div>
	</div>
	<br />
	<div id='refus_dmde' style='diplay:none'>
	</div>
	<br />
	<div class='row'>
		!!accepter_btn!!
		!!refuser_btn!!
	</div>
</form>
";
?>