<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: acquisition_notice.inc.php,v 1.18 2009-12-24 15:28:25 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], "inc.php")) die("no access");


// la variable $caller, passée par l'URL, contient le nom du form appelant
$base_url = "./select.php?what=acquisition_notice";
$base_url.= "&caller=$caller";		//nom de la fenetre appellante
$base_url.= "&cr=$cr";				//numero de ligne appellante
$base_url.= "&no_display=$no_display";
$base_url.= "&bt_ajouter=$bt_ajouter";

// contenu popup selection 
require_once("$class_path/sel_searcher.class.php");
require_once("$base_path/selectors/templates/sel_searcher_templates.tpl.php");

if (!$typ_query) {
	$typ_query='notice';
}

$tab_choice = array(0=>'notice', 1=>'bulletin', 2=>'abt', 3=>'article', 4=>'frais');

switch ($typ_query) {

	case 'notice' :
		$sh=new sel_searcher_notice_mono($base_url); 
		
		$sh->tab_choice = $tab_choice;
		$sh->elt_b_list = $elt_b_list_notice;
		$sh->elt_r_list = $elt_r_list_notice;
		$sh->elt_r_list_values = array(0=>'result', 1=>'nb_expl');
		$sh->action = "<a href='#' onclick=\"set_parent('!!notice_id!!', '!!code!!', '!!titre!!', '!!auteur1!!', '!!editeur1!!', '!!ed_date!!', '!!collection!!', '!!prix!!');\">!!display!!</a> ";
		$sh->action_values = array(0=>'notice_id', 1=>'code', 2=>'titre', 3=>'auteur1', 4=>'editeur1', 5=>'ed_date', 6=>'collection', 7=>'prix');
		$sh->back_script = "
		<script type='text/javascript'>
			<!--
			function set_parent(notice_id, code, titre, auteur1, editeur1, ed_date, collection, prix) {
			
				var ex=window.opener.act_lineAlreadyExists(0, notice_id, '1');
				var q,cr;
				var v=1;
				if (ex!=false) {
					q=window.opener.document.forms['$caller'].elements['qte['+ex+']'];
					v=q.value;
					r=prompt('".addslashes($msg['acquisition_act_mod_qte'])."', v);
					if (r) {
						q.value=r;
						window.opener.act_calc();
						return false;
					} return false;
				}
				if (window.opener.mod==1) {
					cr='$cr';
				} else {
					cr = window.opener.act_getEmptyLine(); 
				} 
				window.opener.mod=0;
				window.opener.document.forms['$caller'].elements['typ_lig['+cr+']'].value = '1';
				window.opener.document.forms['$caller'].elements['id_prod['+cr+']'].value = notice_id;
				window.opener.document.forms['$caller'].elements['code['+cr+']'].value = reverse_html_entities(code);
				var taec=titre;
				if (auteur1 != '') taec=taec+'\\n'+auteur1;
				if (editeur1 != '') taec=taec+'\\n'+editeur1;
				if (editeur1 != '' && ed_date != '') taec=taec+', '+reverse_html_entities(ed_date);
				else if (ed_date != '') taec=taec+'\\n'+reverse_html_entities(ed_date);
				if (collection != '') taec=taec+'\\n'+collection; 
				window.opener.document.forms['$caller'].elements['lib['+cr+']'].value = reverse_html_entities(taec);
				window.opener.document.forms['$caller'].elements['prix['+cr+']'].value = reverse_html_entities(prix);
				window.opener.act_calc();
				q=window.opener.document.forms['$caller'].elements['qte['+cr+']'];
				q.value=v;
				q.focus();
			}
			-->
		</script>";

		//extension de la recherche
		//statut
		$q ="select distinct id_notice_statut, gestion_libelle from notice_statut order by 2 " ;
		if (!$notice_statut_query) {
			$notice_statut_query=$deflt_notice_statut;
		}
		$notice_statut_form = gen_liste($q, 'id_notice_statut', 'gestion_libelle', 'notice_statut_query', '', $notice_statut_query, '', '', '-1', $msg['tous_statuts_notice'] , 0);
		$extended_query=$notice_statut_form;
		//type document
		if (!$doctype_query) {
			$doctype_query=$xmlta_doctype;
		}
		$doctype_form = new marc_select('doctype', 'doctype_query', $doctype_query, '',  '-1', $msg['tous_types_docs']);
		$extended_query.=$doctype_form->display;
		
		if ($deb_rech!='') {
			$elt_query=$deb_rech; 
			$sh->etat='first_search';
		}
		$sh->run();
		break;

	case 'article' :
		$sh=new sel_searcher_notice_article($base_url); 
		
		$sh->tab_choice = $tab_choice;
		$sh->elt_b_list = $elt_b_list_article;
		$sh->elt_r_list = $elt_r_list_article;
		$sh->elt_r_list_values = array(0=>'result');
		$sh->action = "<a href='#' onclick=\"set_parent('!!notice_id!!', '!!titre!!', '!!auteur1!!', '!!in_bull!!', '!!prix!!');\">!!display!!</a> ";
		$sh->action_values = array(0=>'notice_id', 1=>'titre', 3=>'auteur1', 4=>'in_bull', 5=>'prix');
		$sh->back_script = "
		<script type='text/javascript'>
			<!--
			function set_parent(notice_id, titre, auteur1, in_bull, prix) {
			
				var ex=window.opener.act_lineAlreadyExists(0, notice_id, '5');
				var q,cr;
				var v=1;
				if (ex!=false) {
					q=window.opener.document.forms['$caller'].elements['qte['+ex+']'];
					v=q.value;
					r=prompt('".addslashes($msg['acquisition_act_mod_qte'])."', v);
					if (r) {
						q.value=r;
						window.opener.act_calc();
						return false;
					} return false;
				}
				if (window.opener.mod==1) {
					cr='$cr';
				} else {
					cr = window.opener.act_getEmptyLine(); 
				} 
				window.opener.mod=0;
				window.opener.document.forms['$caller'].elements['typ_lig['+cr+']'].value = '5';
				window.opener.document.forms['$caller'].elements['id_prod['+cr+']'].value = notice_id;
				var taec=titre;
				if (auteur1 != '') taec=taec+'\\n'+auteur1;
				if (in_bull != '') taec=taec+'\\n'+in_bull;
				window.opener.document.forms['$caller'].elements['lib['+cr+']'].value = reverse_html_entities(taec);
				window.opener.document.forms['$caller'].elements['prix['+cr+']'].value = reverse_html_entities(prix);
				window.opener.act_calc();
				q=window.opener.document.forms['$caller'].elements['qte['+cr+']'];
				q.value=v;
				q.focus();
			}
			-->
		</script>";

		//extension de la recherche
		//statut
		$q ="select distinct id_notice_statut, gestion_libelle from notice_statut order by 2 " ;
		if (!$notice_statut_query) {
			$notice_statut_query=$deflt_notice_statut;
		}
		$notice_statut_form = gen_liste($q, 'id_notice_statut', 'gestion_libelle', 'notice_statut_query', '', $notice_statut_query, '', '', '-1', $msg['tous_statuts_notice'] , 0);
		$extended_query=$notice_statut_form;
		//type document
		if (!$doctype_query) {
			$doctype_query=$xmlta_doctype;
		}
		$doctype_form = new marc_select('doctype', 'doctype_query', $doctype_query, '',  '-1', $msg['tous_types_docs']);
		$extended_query.=$doctype_form->display;
		
		if ($deb_rech!='') {
			$elt_query=$deb_rech; 
			$sh->etat='first_search';
		}
		$sh->run();
		break;
		
	case 'bulletin' :
		$sh=new sel_searcher_bulletin($base_url); 
		
		$sh->tab_choice = $tab_choice;
		$sh->elt_b_list = $elt_b_list_bulletin;
		$sh->elt_r_list = $elt_r_list_bulletin;
		$sh->elt_r_list_values = array(0=>'result', 1=>'nb_expl');
		$sh->action = "<a href='#' onclick=\"set_parent('!!bulletin_id!!', '!!titre!!',  '!!editeur1!!', '!!numero!!', '!!aff_date!!', '!!prix!!', '!!code!!');\">!!display!!</a> ";
		$sh->action_values = array(0=>'bulletin_id', 1=>'titre', 2=>'editeur1', 3=>'numero', 4=>'aff_date', 5=>'prix', 6=>'code');
		$sh->back_script = "
		<script type='text/javascript'>
			<!--
			function set_parent(bulletin_id, titre, editeur1, numero, aff_date, prix, code) {

				var ex=window.opener.act_lineAlreadyExists(0, bulletin_id, '2');
				var q,cr;
				var v=1;
				if (ex!=false) {
					q=window.opener.document.forms['$caller'].elements['qte['+ex+']'];
					v=q.value;
					r=prompt('".addslashes($msg['acquisition_act_mod_qte'])."', v);
					if (r) {
						q.value=r;
						window.opener.act_calc();
						return false;
					} return false;
				}
				if (window.opener.mod==1) {
					cr='$cr';
				} else {
					cr = window.opener.act_getEmptyLine(); 
				} 
				window.opener.mod=0;
				window.opener.document.forms['$caller'].elements['typ_lig['+cr+']'].value = '2';
				window.opener.document.forms['$caller'].elements['id_prod['+cr+']'].value = bulletin_id;
				window.opener.document.forms['$caller'].elements['code['+cr+']'].value = reverse_html_entities(code);
				var tnde=titre;
				if (numero!='') tnde=tnde+'.\\n'+numero;
				if (aff_date!='') tnde=tnde+aff_date;
				if (editeur1!='') tnde=tnde+'\\n'+editeur1;
				window.opener.document.forms['$caller'].elements['lib['+cr+']'].value = reverse_html_entities(tnde);
				window.opener.document.forms['$caller'].elements['prix['+cr+']'].value = reverse_html_entities(prix);
				window.opener.act_calc();
				q=window.opener.document.forms['$caller'].elements['qte['+cr+']'];
				q.value=v;
				q.focus();
			}
			-->
		</script>";
		$sh->aut_b_list=$aut_b_list_bulletin;
		$sh->aut_r_list=$aut_r_list_bulletin;
		$sh->aut_r_list_values = array(0=>'result', 1=>'nb_bull');
		if ($deb_rech!='') {
			$elt_query=$deb_rech; 
			$sh->etat='first_search';
		}
		$sh->run();
		break;
		
	case 'frais' :
		$sh=new sel_searcher_frais($base_url); 
		
		$sh->tab_choice = $tab_choice;
		$sh->elt_b_list= $elt_b_list_frais; 
		$sh->elt_r_list= $elt_r_list_frais;
		$sh->elt_r_list_values = array(0=>'result', 1=>'lib_montant');
		$sh->action = "<a href='#' onclick=\"set_parent('!!id_frais!!', '!!libelle!!', '!!montant!!','!!taux_tva!!');\">!!display!!</a> ";
		$sh->action_values = array(0=>'id_frais', 1=>'libelle', 2=>'montant', 3=>'taux_tva');
		$sh->back_script = "
		<script type='text/javascript'>
			<!--
			function set_parent(id_frais, libelle, montant, taux_tva) {
			
				var ex=window.opener.act_lineAlreadyExists(0, id_frais, '3');
				var q,cr;
				var v=1;
				if (ex!=false) {
					q=window.opener.document.forms['$caller'].elements['qte['+ex+']'];
					v=q.value;
					r=prompt('".addslashes($msg['acquisition_act_mod_qte'])."', v);
					if (r) {
						q.value=r;
						window.opener.act_calc();
						return false;
					} return false;
				}
				if (window.opener.mod==1) {
					cr='$cr';
				} else {
					cr = window.opener.act_getEmptyLine(); 
				} 
				window.opener.mod=0;
				window.opener.document.forms['$caller'].elements['typ_lig['+cr+']'].value = '3';
				window.opener.document.forms['$caller'].elements['id_prod['+cr+']'].value = id_frais;
				window.opener.document.forms['$caller'].elements['code['+cr+']'].value = '';
				window.opener.document.forms['$caller'].elements['lib['+cr+']'].value = reverse_html_entities(libelle);
				window.opener.document.forms['$caller'].elements['prix['+cr+']'].value = reverse_html_entities(montant);
				try {
					window.opener.document.forms[f_caller].elements['tva['+cr+']'].value = reverse_html_entities(taux_tva);
				} catch(err){}
				window.opener.act_calc();
				q=window.opener.document.forms['$caller'].elements['qte['+cr+']'];
				q.value=v;
				q.focus();
			}
			-->
		</script>";
		
		if ($elt_query=='') {
			$elt_query='*'; 
			$sh->etat='first_search';
		} 
		
		$sh->run();
		break;		

	case 'abt' :
		$sh=new sel_searcher_abt($base_url); 
		
		$sh->tab_choice = $tab_choice;
		$sh->elt_b_list = $elt_b_list_abt;
		$sh->elt_r_list = $elt_r_list_abt;
		$sh->elt_r_list_values = array(0=>'result', 1=>'aff_date_echeance');
		$sh->action = "<a href='#' onclick=\"set_parent('!!abt_id!!', '!!code!!', '!!titre!!',  '!!editeur1!!', '!!periodicite!!', '!!duree!!', '!!aff_date_debut!!', '!!prix!!');\">!!display!!</a> ";
		$sh->action_values = array(0=>'abt_id', 1=>'code', 2=>'titre', 3=>'editeur1', 4=>'periodicite', 5=>'duree', 6=>'aff_date_debut', 7=>'prix');
		$sh->back_script = "
		<script type='text/javascript'>
			<!--
			function set_parent(abt_id, code, titre, editeur1, periodicite, duree, aff_date_debut, prix) {

				var ex=window.opener.act_lineAlreadyExists(0, abt_id, '4');
				var q,cr;
				var v=1;
				if (ex!=false) {
					q=window.opener.document.forms['$caller'].elements['qte['+ex+']'];
					v=q.value;
					r=prompt('".addslashes($msg['acquisition_act_mod_qte'])."', v);
					if (r) {
						q.value=r;
						window.opener.act_calc();
						return false;
					} return false;
				}
				if (window.opener.mod==1) {
					cr='$cr';
				} else {
					cr = window.opener.act_getEmptyLine(); 
				} 
				window.opener.mod=0;
				window.opener.document.forms['$caller'].elements['typ_lig['+cr+']'].value = '4';
				window.opener.document.forms['$caller'].elements['id_prod['+cr+']'].value = abt_id;
				window.opener.document.forms['$caller'].elements['code['+cr+']'].value = reverse_html_entities(code);
				var tabt='".addslashes($msg['pointage_label_abonnement'])."'; 
				tabt=tabt+' '+duree+' ".addslashes($msg['abonnements_periodicite_unite_mois'])."';
				tabt=tabt+'\\n".addslashes($msg['abonnements_date_debut'])." : '+aff_date_debut;
				tabt=tabt+'\\n'+titre;
				if (editeur1!='') tabt=tabt+'\\n'+editeur1;
				window.opener.document.forms['$caller'].elements['lib['+cr+']'].value = reverse_html_entities(tabt);
				window.opener.document.forms['$caller'].elements['prix['+cr+']'].value = reverse_html_entities(prix);
				window.opener.act_calc();
				q=window.opener.document.forms['$caller'].elements['qte['+cr+']'];
				q.value=v;
				q.focus();
			}
			-->
		</script>";
		//extension de la recherche
		//localisation
		$q ="select distinct idlocation, location_libelle from docs_location, docsloc_section where num_location=idlocation order by 2 " ;
		if (!$location_query) {
			$location_query=$deflt_docs_location;
		}
		$location_form = gen_liste($q, "idlocation", "location_libelle", 'location_query', "", $location_query, "", "", '-1', $msg['all_location'] , 0);
		$extended_query=$location_form;
		//echeance
		if ($date_ech_query=='-1') {
			$date_ech_query_lib=$msg['parperso_nodate'];
		} elseif (!$date_ech_query) {
			$q = "select date_add(curdate(), interval 1 month) ";
			$r = mysql_query($q, $dbh);
			$date_ech_query=mysql_result($r, 0, 0);
			$date_ech_query_lib=format_date($date_ech_query);
		} else {
			$date_ech_query_lib=format_date($date_ech_query);
		}
		
		$date_ech_form =htmlentities($msg['acquisition_abt_ech'], ENT_QUOTES, $charset)."&nbsp;&lt;<input type='hidden' id='date_ech_query' name='date_ech_query' value='".$date_ech_query."' />
			<input type='button' id='date_ech_query_lib' class='bouton_small' value='".$date_ech_query_lib."' onclick=\"var date_c='';if (this.form.elements['date_ech_query'].value!='-1') date_c=this.form.elements['date_ech_query'].value; openPopUp('./select.php?what=calendrier&caller='+this.form.name+'&date_caller='+date_c+'&param1=date_ech_query&param2=date_ech_query_lib&auto_submit=NO&date_anterieure=YES', 'date_date_test', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\" />
			<input type='button' class='bouton_small' style='width:25px;' value='".$msg['raz']."' onclick=\"this.form.elements['date_ech_query_lib'].value='".$msg['parperso_nodate']."'; this.form.elements['date_ech_query'].value='-1';\" />";
		$extended_query.=$date_ech_form;
		
		if ($deb_rech!='') {
			$elt_query=$deb_rech; 
			$sh->etat='first_search';
		}

		$sh->run();
		break;

	case 'panier' :
		break;
		
	default	:
		print 'No query type defined<br />';
		break;
}

print $sel_footer;	
?>