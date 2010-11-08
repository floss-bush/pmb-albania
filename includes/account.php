<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: account.php,v 1.40 2009-07-03 09:35:36 kantin Exp $

// définition du minimum nécéssaire 
$base_path=".";                            
$base_auth = "PREF_AUTH|ADMINISTRATION_AUTH";  
$base_title = "\$msg[933]";    
require_once ("$base_path/includes/init.inc.php");  

// modules propres à account.php ou à ses sous-modules
include("$include_path/account.inc.php");
include("$include_path/templates/account.tpl.php");
require_once("$include_path/user_error.inc.php");
require_once("$class_path/thesaurus.class.php");
require_once("$base_path/admin/users/users_func.inc.php");
require_once("$class_path/actes.class.php");
require_once("$class_path/suggestions_map.class.php");


print $menu_bar;
print $account_layout;

if($use_shortcuts) {
	include("$include_path/shortcuts/circ.sht");
}

if(!$modified) {
	$user_params = get_account_info(SESSlogin);
	// constitution des paramètres utilisateurs
	$requete_param = "SELECT * FROM users WHERE userid='$PMBuserid' LIMIT 1 ";
	$res_param = mysql_query($requete_param, $dbh);
	$field_values = mysql_fetch_row ( $res_param );
	
	$param_user="<div class='row'><b>".$msg["1500"]."</b></div>\n";
	$deflt_user="<div class='row'><b>".$msg["1501"]."</b></div>\n";

	$i = 0;
	while ($i < mysql_num_fields($res_param)) {
		$field = mysql_field_name($res_param, $i) ;
		$field_deb = substr($field,0,6);
		switch ($field_deb) {

			case "deflt_" :
				if ($field=="deflt_styles") {
					$deflt_user_style="
						<div class='colonne60'><div class='row'>".
						$msg[$field]."&nbsp;:&nbsp;</div></div>\n
						<div class='colonne_suite'><div_class='row'>"			
						.make_user_style_combo($field_values[$i]).
						"</div></div>\n";
				} elseif ($field=="deflt_docs_location") {
					//visibilité des exemplaires
					if ($pmb_droits_explr_localises && $explr_visible_mod) $where_clause_explr = "idlocation in (".$explr_visible_mod.") and";
					else $where_clause_explr = "";
					$selector = gen_liste ("select distinct idlocation, location_libelle from docs_location, docsloc_section where $where_clause_explr num_location=idlocation order by 2 ", "idlocation", "location_libelle", 'form_'.$field, "account_calcule_section(this);", $field_values[$i], "", "","","",0);
					$deflt_user.="
						<div class='row'><div class='colonne60'>".
						$msg[$field]."&nbsp;:&nbsp;</div>\n
						<div class='colonne_suite'>"			
						.$selector.
						"</div></div>\n";
				} elseif ($field=="deflt_docs_section") {
					// calcul des sections
					$selector="";
					if ($pmb_droits_explr_localises && $explr_visible_mod) $where_clause_explr = "where idlocation in (".$explr_visible_mod.")";
					else $where_clause_explr = "";
					$rqtloc = "SELECT idlocation FROM docs_location $where_clause_explr order by location_libelle";
					$resloc = mysql_query($rqtloc, $dbh);
					while ($loc=mysql_fetch_object($resloc)) {
						$requete = "SELECT idsection, section_libelle FROM docs_section, docsloc_section where idsection=num_section and num_location='$loc->idlocation' order by section_libelle";
						$result = mysql_query($requete, $dbh);
						$nbr_lignes = mysql_num_rows($result);
						if ($nbr_lignes) {			
							if ($loc->idlocation==$deflt_docs_location ) $selector .= "<div id=\"docloc_section".$loc->idlocation."\" style=\"display:block\">\r\n";
							else $selector .= "<div id=\"docloc_section".$loc->idlocation."\" style=\"display:none\">\r\n";
							$selector .= "<select name='f_ex_section".$loc->idlocation."' id='f_ex_section".$loc->idlocation."'>\r\n";
							while($line = mysql_fetch_row($result)) {
								$selector .= "<option value='$line[0]'";
								$line[0] == $deflt_docs_section ? $selector .= ' SELECTED>' : $selector .= '>';
					 			$selector .= htmlentities($line[1],ENT_QUOTES, $charset).'</option>\r\n';
								}                                         
							$selector .= '</select></div>';
							}                 
						}
					$deflt_user.="
						<div class='row'><div class='colonne60'>".
						$msg[$field]."&nbsp;:&nbsp;</div>\n
						<div class='colonne_suite'>"			
						.$selector.
						"</div></div>\n";
				} elseif ($field=="deflt_upload_repertoire") { 
					$selector = "";
					$req = "select repertoire_id, repertoire_nom from upload_repertoire";
					$res = mysql_query($req, $dbh);
					$selector .=  "<div id='upload_section'>";
					$selector .= "<select name='form_deflt_upload_repertoire'>";
					$selector .= "<option value='0'>".$msg[upload_repertoire_sql]."</option>";
					while(($rep = mysql_fetch_object($res))){
						$selector .= "<option value='".$rep->repertoire_id."'";
						$selector .= (($deflt_upload_repertoire == $rep->repertoire_id) ? 'SELECTED' : '') . ">";
						$selector .= htmlentities($rep->repertoire_nom,ENT_QUOTES,$charset)."</option>";
					}
					$selector .=  "</select></div>";
					$deflt_user.="
						<div class='row'>
							<div class='colonne60'>".$msg[$field]."&nbsp;:&nbsp;
							</div>
							<div class='colonne_suite'>".$selector."
							</div>
						</div>";		
			 	} else {
					$deflt_table = substr($field,6);
					$requete="select * from ".$deflt_table." order by 2";
					$resultat_liste=mysql_query($requete,$dbh);
					$nb_liste=mysql_num_rows($resultat_liste);
					if ($nb_liste==0) {
						$deflt_user.="" ;
					} else {
						$deflt_user.="
							<div class='row'><div class='colonne60'>".
							$msg[$field]."&nbsp;:&nbsp;</div>\n";
						$deflt_user.= "
							<div class='colonne_suite'>						
							<select class='saisie-30em' name=\"form_".$field."\">";
									
						$j=0;
						while ($j<$nb_liste) {
							$liste_values = mysql_fetch_row ( $resultat_liste );
							$deflt_user.="<option value=\"".$liste_values[0]."\" " ;
							if ($field_values[$i]==$liste_values[0]) {
								$deflt_user.="selected" ;
							}
							$deflt_user.=">".$liste_values[1]."</option>\n" ;
							$j++;
						}
						$deflt_user.="</select></div></div><br />\n" ;
					}
				} // fin else
				break;
			case "param_" :
				if ($field=="param_allloc") {
					$param_user_allloc="<div class='row'><div class='colonne60'>".$msg[$field]."</div>\n
						<div class='colonne_suite'>
						<input type='checkbox' class='checkbox'";
					if ($field_values[$i]==1) $param_user_allloc.=" checked"; 
					$param_user_allloc.=" value='1' name='form_$field'></div></div>\n" ;
				} else {
					$param_user.="<div class='row'><input type='checkbox' class='checkbox'";
					if ($field_values[$i]==1) $param_user.=" checked"; 
					$param_user.=" value='1' name='form_$field'>" ;
					$param_user.="&nbsp; $msg[$field]</div>" ;
				}
				break ;
			case "value_" :
				if ($field == 'value_deflt_fonction'){
					$flist=new marc_list('function');
					$f=$flist->table[$field_values[$i]];
					$value_user.="<div class='row'><div class='colonne60'>					
					$msg[$field]&nbsp;:&nbsp;</div>\n
					<div class='colonne_suite'>
					<input type='text' class='saisie-30emr' id='form_value_deflt_fonction_libelle' name='form_value_deflt_fonction_libelle' value='".htmlentities($f,ENT_QUOTES, $charset)."' />
					<input type='button' class='bouton_small' value='".$msg['parcourir']."' onclick=\"openPopUp('./select.php?what=function&caller=account_form&p1=form_value_deflt_fonction&p2=form_value_deflt_fonction_libelle', 'select_func0', 400, 400, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes')\" />
					<input type='button' class='bouton_small' value='X' onclick=\"this.form.elements['form_value_deflt_fonction'].value='';this.form.elements['form_value_deflt_fonction_libelle'].value='';return false;\" />
					<input type='hidden' name='form_value_deflt_fonction' id='form_value_deflt_fonction' value=\"$field_values[$i]\" />
					</div></div><br />";
				} else if ($field == 'value_deflt_lang'){
					$llist=new marc_list('lang');
					$l=$llist->table[$field_values[$i]];
					$value_user.="<div class='row'><div class='colonne60'>					
					$msg[$field]&nbsp;:&nbsp;</div>\n
					<div class='colonne_suite'>
					<input type='text' class='saisie-30emr' id='form_value_deflt_lang_libelle' name='form_value_deflt_lang_libelle' value='".htmlentities($l,ENT_QUOTES, $charset)."' />
					<input type='button' class='bouton_small' value='".$msg['parcourir']."' onclick=\"openPopUp('./select.php?what=lang&caller=account_form&p1=form_value_deflt_lang&p2=form_value_deflt_lang_libelle', 'select_lang', 400, 400, -2, -2, 'scrollbars=yes, toolbar=no, dependent=yes, resizable=yes')\" />
					<input type='button' class='bouton_small' value='X' onclick=\"this.form.elements['form_value_deflt_lang'].value='';this.form.elements['form_value_deflt_lang_libelle'].value='';return false;\" />
					<input type='hidden' name='form_value_deflt_lang' id='form_form_value_deflt_lang' value=\"$field_values[$i]\" />
					</div></div><br />";
				} else if ($field == 'value_deflt_relation'){
					$value_user.="<div class='row'><div class='colonne60'>					
					$msg[value_deflt_relation]&nbsp;:&nbsp;</div>\n
					<div class='colonne_suite'>";
					//Rï¿½cupï¿½ration des types de relation
					$liste_type_relation=new marc_select("relationtypeup","form_value_deflt_relation",$field_values[$i]);
					$type_relation=$liste_type_relation->display;
					$value_user.=$type_relation;
					$value_user.="</div></div><br />";
				} else {
					$value_user.="<div class='row'><div class='colonne60'>					
					$msg[$field]&nbsp;:&nbsp;</div>\n
					<div class='colonne_suite'>
					<input type='text' class='saisie-20em' name='form_$field' value='".htmlentities($field_values[$i],ENT_QUOTES, $charset)."' />
					</div></div><br />";
				}
				break ;
			case "deflt2" :
				if ($field=="deflt2docs_location") {
					// localisation des lecteurs
					$deflt_table = substr($field,6);
					$requete="select * from ".$deflt_table." order by 2";
					$resultat_liste=mysql_query($requete,$dbh);
					$nb_liste=mysql_num_rows($resultat_liste);
					if ($nb_liste==0) {
						$deflt_user.="" ;
					} else {
						$deflt_user.="
							<div class='row'><div class='colonne60'>".
							$msg[$field]."&nbsp;:&nbsp;</div>\n";
						$deflt_user.= "
							<div class='colonne_suite'>						
							<select class='saisie-30em' name=\"form_".$field."\">";
									
						$j=0;
						while ($j<$nb_liste) {
							$liste_values = mysql_fetch_row ( $resultat_liste );
							$deflt_user.="<option value=\"".$liste_values[0]."\" " ;
							if ($field_values[$i]==$liste_values[0]) {
								$deflt_user.="selected" ;
							}
							$deflt_user.=">".$liste_values[1]."</option>\n" ;
							$j++;
						}
						$deflt_user.="</select></div></div>!!param_allloc!!<br />\n" ;
					}
				} else {
					$deflt_table = substr($field,6);
					$requete="select * from ".$deflt_table."  order by 2 ";
					$resultat_liste=mysql_query($requete,$dbh);
					$nb_liste=mysql_numrows($resultat_liste);
					if ($nb_liste==0) {
						$deflt_user.="" ;
					} else {
						$deflt_user.="
							<div class='row'><div class='colonne60'>".
							$msg[$field]."&nbsp;:&nbsp;</div>\n";
						$deflt_user.= "
							<div class='colonne_suite'>						
							<select class='saisie-30em' name=\"form_".$field."\">";
									
						$j=0;
						while ($j<$nb_liste) {
							$liste_values = mysql_fetch_row ( $resultat_liste );
							$deflt_user.="<option value=\"".$liste_values[0]."\" " ;
							if ($field_values[$i]==$liste_values[0]) {
								$deflt_user.="selected" ;
							}
							$deflt_user.=">".$liste_values[1]."</option>\n" ;
							$j++;
						}
						$deflt_user.="</select></div></div>\n" ;
					}
				}
				break;
			case "xmlta_" :
				$deflt_table = substr($field,6);
				$deflt_user.="
					<div class='row'><div class='colonne60'>".
					$msg[$field]."&nbsp;:&nbsp;</div>\n";
				$deflt_user.= "
					<div class='colonne_suite'>";
				$select_doc = new marc_select("$deflt_table", "form_".$field, $field_values[$i], "");
				$deflt_user.= $select_doc->display ;
				$deflt_user.="</div></div>\n" ;
				break;
			case "deflt3" :
				$q='';
				$t=array();
				switch($field) {
					case "deflt3bibli":
						$q="select 0,'".addslashes($msg['deflt3none'])."' union ";
						$q.="select id_entite, raison_sociale from entites where type_entite='1' order by 2 ";
						break;
					case "deflt3exercice":
						$q="select 0,'".addslashes($msg['deflt3none'])."' union ";
						$q.="select id_exercice, libelle from exercices order by 2 ";
						break;
					case "deflt3rubrique":
						$q="select 0,'".addslashes($msg['deflt3none'])."' union ";
						$q.="select id_rubrique, concat(budgets.libelle,':',rubriques.libelle) from rubriques join budgets on num_budget=id_budget order by 2 ";
						break;
					case "deflt3dev_statut":
						$t=actes::getStatelist(TYP_ACT_DEV);
						break;
					case "deflt3cde_statut":
						$t=actes::getStatelist(TYP_ACT_CDE);
						break;
					case "deflt3liv_statut":
						$t=actes::getStatelist(TYP_ACT_LIV);
						break;
					case "deflt3fac_statut":
						$t=actes::getStatelist(TYP_ACT_FAC);
						break;
					case "deflt3sug_statut":
						$m=new suggestions_map();
						$t=$m->getStateList();
						break;
				}
				if($q) {
					$r=mysql_query($q, $dbh) or die ("<br />".mysql_error()."<br />".$q."<br />");
					$nb=mysql_num_rows($r);
					while($row=mysql_fetch_row($r)) {
						$t[$row[0]]=$row[1];
					}
				}
				if (count($t)) {
					$deflt3user.="<div class='row'><div class='colonne60'>".$msg[$field]."&nbsp;:&nbsp;</div>\n";
					$deflt3user.= "<div class='colonne_suite'><select class='saisie-30em' name=\"form_".$field."\">";
					foreach($t as $k=>$v) {
						$deflt3user.="<option value=\"".$k."\" " ;
						if ($field_values[$i]==$k) {
							$deflt3user.="selected" ;
						}
						$deflt3user.=">".htmlentities($v, ENT_QUOTES, $charset)."</option>\n" ;
					}
					$deflt3user.="</select></div></div><br />\n";
				}
				break;
			case "speci_" :
				$speci_func = substr($field, 6);
				eval('$speci_user.= get_'.$speci_func.'($PMBuserid, $field_values, $i, \'account_form\');'); 
				break;
			default :
				break ;
		}
			
		$i++;
	}

	$param_default="
		<div class='row'><hr /></div>
			$param_user
		<div class='row'><hr /></div>
			".str_replace("!!param_allloc!!",$param_user_allloc,$deflt_user)."
		<br />
		<div class='row'><hr /></div>
			$value_user";
	if ($speci_user || $deflt3user) {
		$param_default.= "<div class='row'><hr /></div>";
		$param_default.=$deflt3user;
		$param_default.=$speci_user;
		$param_default.= "<div class='row'></div>";	
	}
	$account_form = str_replace('!!all_user_param!!', $param_default, $account_form);
	// fin gestion des paramètres personalisés du user
	
	$account_form = str_replace('!!combo_user_style!!', make_user_style_combo($stylesheet), $account_form);
	$account_form = str_replace('!!combo_user_lang!!', make_user_lang_combo($user_params->user_lang), $account_form);
	$account_form = str_replace('!!nb_per_page_search!!', $user_params->nb_per_page_search, $account_form);
	$account_form = str_replace('!!nb_per_page_select!!', $user_params->nb_per_page_select, $account_form);
	$account_form = str_replace('!!nb_per_page_gestion!!', $user_params->nb_per_page_gestion, $account_form);
	print $account_form;

} else {
		
	// code de mise à jour
	// constitution des variables MySQL
	// mise à jour de la date d'update 
	
	$names[] = 'last_updated_dt';
	$values[] = "'".today()."'";
	
	$names[] = 'user_lang';
	$values[] = "'$user_lang'";
	
	if ($form_pwd) {
		$names[] = 'pwd';
		$values[] = "password('$form_pwd')";
	}
	
	if($form_nb_per_page_search >= 1) {
		$names[] = 'nb_per_page_search';
		$values[] = "'$form_nb_per_page_search'";
	}
	
	if($form_nb_per_page_select >= 1) {
		$names[] = 'nb_per_page_select';
		$values[] = "'$form_nb_per_page_select'";
	}
	
	if($form_nb_per_page_gestion >= 1) {
		$names[] = 'nb_per_page_gestion';
		$values[] = "'$form_nb_per_page_gestion'";
	}
	
	if(strcmp($form_style, $stylesheet)) {
		$names[] .= 'deflt_styles';
		$values[] .= "'$form_style'";
	}
	
			
	/* insérer ici la maj des param et deflt */
	
	//maj thesaurus par defaut en session
	if ($form_deflt_thesaurus) thesaurus::setSessionThesaurusId($form_deflt_thesaurus);
			
	$requete_param = "SELECT * FROM users WHERE userid='$PMBuserid' LIMIT 1 ";
	$res_param = mysql_query($requete_param, $dbh);
	$field_values = mysql_fetch_row ( $res_param );
	$i = 0;
	while ($i < mysql_num_fields($res_param)) {
		$field = mysql_field_name($res_param, $i) ;
		$field_deb = substr($field,0,6);
		switch ($field_deb) {
			case "deflt_" :
				if ($field == "deflt_styles") {
					$dummy[$i+8]=$field."='".$form_style."'";
				} elseif ($field == "deflt_docs_section") {
					$formlocid="f_ex_section".$form_deflt_docs_location ;
					$dummy[$i+8]=$field."='".$$formlocid."'";
				} else {
					$var_form = "form_".$field;
					$dummy[$i+8]=$field."='".$$var_form."'";
				}
				break;
			case "deflt2" :
				$var_form = "form_".$field;
				$dummy[$i+8]=$field."='".$$var_form."'";
				break ;
			case "param_" :
				$var_form = "form_".$field;
				$dummy[$i+8]=$field."='".$$var_form."'";
				break ;
			case "value_" :
				$var_form = "form_".$field;
				$dummy[$i+8]=$field."='".$$var_form."'";
				break ;
			case "xmlta_" :
				$var_form = "form_".$field;
				$dummy[$i+8]=$field."='".$$var_form."'";
				break ;
			case "deflt3" :
				$var_form = "form_".$field;
				$dummy[$i+8]=$field."='".$$var_form."'";
				break ;
			case "speci_" :
				$speci_func = substr($field, 6);
				eval('$dummy[$i+8].= set_'.$speci_func.'();');
				break;
			default :
				break ;
		}
		$i++;
	}

	if(!empty($dummy)) {
		$set = join($dummy, ", ");
		$set = " , ".$set ;
	} else $set = "" ;
		
	if(sizeof($names) == sizeof($values)) {
		while(list($cle, $valeur) = each($names)) {
			$n_values ? $n_values .= ", $valeur=${values[$cle]}" : $n_values = "$valeur=${values[$cle]}";
	    }
		$requete = "UPDATE users SET $n_values $set , last_updated_dt=curdate() WHERE username='".SESSlogin."' ";
		$result = @mysql_query($requete, $dbh);
		if($result) {
			$loc = "index.php" ;
			if (SESSrights & ADMINISTRATION_AUTH) 
				$loc="admin.php";
			if (SESSrights & EDIT_AUTH) 
				$loc="edit.php";
			if (SESSrights & AUTORITES_AUTH) 
				$loc="autorites.php";
			if (SESSrights & CATALOGAGE_AUTH) 
				$loc="catalog.php";
			if (SESSrights & CIRCULATION_AUTH) 
				$loc="circ.php";
			print $msg["937"]." <!-- back to main page --> <script type=\"text/javascript\"> document.location=\"./".$loc."\"; </script>";
		} else {
			// c'est parti en vrac : erreur MySQL
			warning($msg["281"], $msg["936"]);
		}
	}
}
	
print "</div></div>";
print $account_layout_end;
print $extra;
print $extra_info;
print $footer;

mysql_close($dbh);
