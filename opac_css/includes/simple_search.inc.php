<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: simple_search.inc.php,v 1.95 2010-06-18 15:27:41 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// recherche simple
require_once($base_path."/classes/marc_table.class.php");
require_once($base_path."/includes/javascript/form.inc.php");
require_once($base_path."/includes/empr.inc.php");
require_once($class_path."/search.class.php");
require_once($class_path."/thesaurus.class.php");
require_once($class_path."/search_persopac.class.php");

function simple_search_content($value='',$css) {
	global $dbh;
	global $msg;
	global $charset;
	global $lang;
	global $css;
	global $search_type;
	global $class_path;
	global $es;
	global $lvl;
	global $include_path;
	global $opac_allow_extended_search,$opac_allow_term_search,$opac_allow_external_search;
	global $typdoc;
	global $opac_search_other_function, $opac_search_show_typdoc;
	global $opac_thesaurus;
	global $id_thes;
	global $base_path;
	global $opac_allow_tags_search;
	global $opac_show_onglet_empr;
	global $external_env;
	global $user_query;
	global $source;
	global $opac_recherches_pliables;
	global $opac_show_help;
	global $onglet_persopac,$opac_allow_personal_search;
	global $search_form_perso,$search_form,$search_form_perso_limitsearch,$limitsearch;
	global $opac_show_onglet_help;
	global $search_in_perio;
	
	include($include_path."/templates/simple_search.tpl.php");
	if ($opac_search_other_function) require_once($include_path."/".$opac_search_other_function);
	
	// pour la DSI
	global $opac_allow_bannette_priv ; // bannettes privees autorisees ?
	global $bt_cree_bannette_priv ;
	if ($opac_allow_bannette_priv && ($bt_cree_bannette_priv || $_SESSION['abon_cree_bannette_priv']==1)) $_SESSION['abon_cree_bannette_priv'] = 1 ;
	else $_SESSION['abon_cree_bannette_priv'] = 0 ;
	
	global $script_test_form;

	switch ($opac_show_onglet_empr) {
		case 1:
			$empr_link_onglet="./index.php?search_type_asked=connect_empr";
			break;
		case 2:
			$empr_link_onglet="./empr.php";
			break;
	}
	$search_p= new search_persopac();
	$onglets_search_perso=$search_p->directlink_user;	
	$onglets_search_perso_form=$search_p->directlink_user_form;
	switch ($search_type) {
		case "simple_search":
			// les tests de formulaire
			$result = $script_test_form;	
			$tests = test_field("search_input", "query", "recherche");
			$result = str_replace("!!tests!!", $tests, $result);
	
			// les typ_doc
			if ($opac_search_show_typdoc) {
				$query = "SELECT typdoc FROM notices where typdoc!='' GROUP BY typdoc";
				$result2 = mysql_query($query, $dbh);
				$toprint_typdocfield = " <select name='typdoc'>";
				$toprint_typdocfield .= "  <option ";
				$toprint_typdocfield .=" value=''";
				if ($typdoc=='') $toprint_typdocfield .=" selected";
				$toprint_typdocfield .=">".$msg["simple_search_all_doc_type"]."</option>\n";
				$doctype = new marc_list('doctype');
				while (($rt = mysql_fetch_row($result2))) {
					$obj[$rt[0]]=1;
				}	
				foreach ($doctype->table as $key=>$libelle){
					if ($obj[$key]==1){
						$toprint_typdocfield .= "  <option ";
						$toprint_typdocfield .= " value='$key'";
						if ($typdoc == $key) $toprint_typdocfield .=" selected";
						$toprint_typdocfield .= ">".htmlentities($libelle,ENT_QUOTES, $charset)."</option>\n";
					}
				}
				$toprint_typdocfield .= "</select>";
			} else $toprint_typdocfield="";
				
			if ($opac_search_other_function) $toprint_typdocfield.=search_other_function_filters();
	
			$toprint_typdocfield.="<br />";
			
			// le contenu
			$result .= $search_input;

			// on met la valeur a jour
			$result = str_replace("!!user_query!!", htmlentities($value,ENT_QUOTES,$charset), $result);
			$result = str_replace("<!--!!typdoc_field!!-->", $toprint_typdocfield, $result);
			
			if (!$opac_recherches_pliables) 
				$ou_chercher="<div id='simple_search_zone'>".do_ou_chercher()."</div>";
			elseif ($opac_recherches_pliables==1) 
				$ou_chercher="<div id='simple_search_zone'>".gen_plus_form("zsimples",$msg["rechercher_dans"],do_ou_chercher(),false)."</div>" ;
			elseif ($opac_recherches_pliables==2) 
				$ou_chercher="<div id='simple_search_zone'>".gen_plus_form("zsimples",$msg["rechercher_dans"],do_ou_chercher(),true)."</div>" ;
			elseif ($opac_recherches_pliables==3)
				// les options de recherches sont invisibles, pas dépliables. 
				$ou_chercher="\n".do_ou_chercher_hidden()."\n" ;
			
			$result = str_replace("<!--!!ou_chercher!!-->", $ou_chercher, $result);

			// on se place dans le bon champ
			// $result .= form_focus("search_input", "query");
			$others="";
			if ($opac_allow_personal_search) $others.="<li><a href=\"./index.php?search_type_asked=search_perso\">".$msg["search_perso_menu"]."</a></li>";
			$others.=$onglets_search_perso;
			if ($opac_allow_extended_search) $others.="<li><a href=\"./index.php?search_type_asked=extended_search\">".$msg["extended_search"]."</a></li>";
			if ($opac_allow_term_search) $others.="<li><a href=\"./index.php?search_type_asked=term_search\">".$msg["term_search"]."</a></li>";
			if ($opac_allow_tags_search) $others.="<li><a href=\"./index.php?search_type_asked=tags_search\">".$msg["tags_search"]."</a></li>";
			if (($opac_show_onglet_empr==1)||(($opac_show_onglet_empr==2)&&($_SESSION["user_code"]))) {
				if (!$_SESSION["user_code"]) $others.="<li><a href=\"./index.php?search_type_asked=connect_empr\">".$msg["onglet_empr_connect"]."</a></li>";
					else $others.="<li><a href=\"$empr_link_onglet\">".$msg["onglet_empr_compte"]."</a></li>";
				}
			if ($opac_allow_external_search) $others.="<li><a href=\"./index.php?search_type_asked=external_search&external_type=simple\">".$msg["connecteurs_external_search"]."</a></li>";
			$result=str_replace("!!others!!",$others,$result);
			$result.=$onglets_search_perso_form;
			break;
			
		//Recherche avancee
		case "extended_search":
			global $mode_aff;

			if ($mode_aff) {
				if ($mode_aff=="aff_module") {
					//ajout de la recherche dans l'historique 
					$_SESSION["nb_queries"]=$_SESSION["nb_queries"]+1;
					$n=$_SESSION["nb_queries"];
					$_SESSION["notice_view".$n]=$_SESSION["last_module_search"];
					switch ($_SESSION["last_module_search"]["search_mod"]) {
						case 'etagere_see':
							//appel de la fonction tableau_etagere du fichier etagere_func.inc.php
							$r1 = $msg["etagere_query"];
							$t=array();
							$t=tableau_etagere($_SESSION["last_module_search"]["search_id"]);
							$r=$r1." '".$t[0]["nometagere"]." : ".$t[0]["commentetagere"]."'";
						break;
						case 'categ_see':
							// instanciation de la categorie
							$ourCateg = new categorie($_SESSION["last_module_search"]["search_id"]);
							$r1 = $msg["category"];
							$r=$r1." '".$ourCateg->libelle."'";
						break;
						case 'indexint_see':
							// instanciation de la classe indexation
							$r1= $msg["indexint_search"];
							$ourIndexint = new indexint($_SESSION["last_module_search"]["search_id"]);
							$r=$r1." '".$ourIndexint->name." ".$ourIndexint->comment."'";
							
						break;
						case 'section_see':
							$resultat=mysql_query("select location_libelle from docs_location where idlocation=".$_SESSION["last_module_search"]["search_location"]);
							$j=mysql_fetch_array($resultat);
							$localisation_=$j["location_libelle"];
							mysql_free_result($resultat);
							$resultat=mysql_query("select section_libelle from docs_section where idsection=".$_SESSION["last_module_search"]["search_id"]);
							$j=mysql_fetch_array($resultat);
							$section_=$j["section_libelle"];
							mysql_free_result($resultat);
							$r1 = $localisation_." => ".$msg["section"];
							$r=$r1." '".$section_."'";
						break;
					}
					$_SESSION["human_query".$n]=$r;
					$_SESSION["search_type".$n]="module";
				} else {
					if ($_SESSION["last_query"]) {
						$n=$_SESSION["last_query"];
					} else {
						$n=$_SESSION["nb_queries"];	
					}	
				}	
	       		//générer les critères de la multi_critères
	       		global $search;
	       		$search[0]="s_1";
	       		$op_="EQ";
	       								
				//operateur
    			$op="op_0_".$search[0];
    			global $$op;
    			$$op=$op_;
    		    		
    			//contenu de la recherche
    			$field="field_0_".$search[0];
    			$field_=array();
    			$field_[0]=$n;
    			global $$field;
    			$$field=$field_;
    	    	
    	    	//opérateur inter-champ
    			$inter="inter_0_".$search[0];
    			global $$inter;
    			$$inter="";
    		    		
    			//variables auxiliaires
    			$fieldvar_="fieldvar_0_".$search[0];
    			global $$fieldvar_;
    			$$fieldvar_="";
    			$fieldvar=$$fieldvar_;
			}
			
			if($search_in_perio){
				global $search;
				$search[0]="f_34";
				//opérateur
	    		$op="op_0_".$search[0];
	    		global $$op;
	    		$op_ ="EQ";
	    		$$op=$op_;	    		    			
	    		//contenu de la recherche
	    		$field="field_0_".$search[0];
	    		$field_=array();
	    		$field_[0]=$search_in_perio;
	    		global $$field;
	    		$$field=$field_;
	    		
	    		$search[1]="f_42";
	    		//opérateur
	    		$op="op_1_".$search[0];
	    		global $$op;
	    		$op_ ="BOOLEAN";
	    		$$op=$op_;	    		    				    		
			}
			$es=new search();
			if($onglet_persopac){				
				$search_form=$search_form_perso;
			}
			if($limitsearch){				
				$search_form=$search_form_perso_limitsearch;
			}
			$result=$es->show_form("./index.php?lvl=$lvl&search_type_asked=extended_search","./index.php?lvl=search_result&search_type_asked=extended_search");
			$others="<li><a href=\"./index.php?search_type_asked=simple_search\">".$msg["simple_search"]."</a></li>\n";
			if ($opac_allow_personal_search) $others.="<li><a href=\"./index.php?search_type_asked=search_perso\">".$msg["search_perso_menu"]."</a></li>";
			$others.=$onglets_search_perso;
			if ($opac_allow_term_search) $others2="<li><a href=\"./index.php?search_type_asked=term_search\">".$msg["term_search"]."</a></li>\n";
			else $others2="" ;
			if ($opac_allow_tags_search) $others2.="<li><a href=\"./index.php?search_type_asked=tags_search\">".$msg["tags_search"]."</a></li>";
			if (($opac_show_onglet_empr==1)||(($opac_show_onglet_empr==2)&&($_SESSION["user_code"]))) {
				if (!$_SESSION["user_code"]) $others2.="<li><a href=\"./index.php?search_type_asked=connect_empr\">".$msg["onglet_empr_connect"]."</a></li>";
				else $others2.="<li><a href=\"$empr_link_onglet\">".$msg["onglet_empr_compte"]."</a></li>";
			}
			if ($opac_allow_external_search) $others2.="<li><a href=\"./index.php?search_type_asked=external_search&external_type=simple\">".$msg["connecteurs_external_search"]."</a></li>";
			$result=str_replace("!!others!!",$others,$result);
			$result=str_replace("!!others2!!",$others2,$result);
			$result="<div id='search'>".$result."</div>";
			$result.=$onglets_search_perso_form;
			break;
		//Recherche avancee
		case "external_search":
			//Si c'est une multi-critere, on l'affiche telle quelle
			global $external_type; 
			if ($external_type) $_SESSION["ext_type"]=$external_type; 
			global $mode_aff;
			//Affinage
			if ($mode_aff) {
				if ($mode_aff=="aff_module") {
					//ajout de la recherche dans l'historique 
					$_SESSION["nb_queries"]=$_SESSION["nb_queries"]+1;
					$n=$_SESSION["nb_queries"];
					$_SESSION["notice_view".$n]=$_SESSION["last_module_search"];
					switch ($_SESSION["last_module_search"]["search_mod"]) {
						case 'etagere_see':
							//appel de la fonction tableau_etagere du fichier etagere_func.inc.php
							$r1 = $msg["etagere_query"];
							$t=array();
							$t=tableau_etagere($_SESSION["last_module_search"]["search_id"]);
							$r=$r1." '".$t[0]["nometagere"]." : ".$t[0]["commentetagere"]."'";
						break;
						case 'categ_see':
							// instanciation de la catégorie
							$ourCateg = new categorie($_SESSION["last_module_search"]["search_id"]);
							$r1 = $msg["category"];
							$r=$r1." '".$ourCateg->libelle."'";
						break;
						case 'indexint_see':
							// instanciation de la classe indexation
							$r1= $msg["indexint_search"];
							$ourIndexint = new indexint($_SESSION["last_module_search"]["search_id"]);
							$r=$r1." '".$ourIndexint->name." ".$ourIndexint->comment."'";
							
						break;
						case 'section_see':
							$resultat=mysql_query("select location_libelle from docs_location where idlocation=".$_SESSION["last_module_search"]["search_location"]);
							$j=mysql_fetch_array($resultat);
							$localisation_=$j["location_libelle"];
							mysql_free_result($resultat);
							$resultat=mysql_query("select section_libelle from docs_section where idsection=".$_SESSION["last_module_search"]["search_id"]);
							$j=mysql_fetch_array($resultat);
							$section_=$j["section_libelle"];
							mysql_free_result($resultat);
							$r1 = $localisation_." => ".$msg["section"];
							$r=$r1." '".$section_."'";
						break;
					}
					$_SESSION["human_query".$n]=$r;
					$_SESSION["search_type".$n]="module";
				} else {
					if ($_SESSION["last_query"]) {
						$n=$_SESSION["last_query"];
					} else {
						$n=$_SESSION["nb_queries"];	
					}	
				}	
			}
			
			if ($_SESSION["ext_type"]=="multi") {
				global $search;
				
				if (!$search) {
					$search[0]="s_2";
					$op_0_s_2="EQ";
					$field_0_s_2=array();
				} else {
					//Recherche du champp source, s'il n'est pas present, on decale tout et on l'ajoute
					$flag_found=false;
					for ($i=0; $i<count($search); $i++) {
						if ($search[$i]=="s_2") { $flag_found=true; break; }
					}
					if (!$flag_found) {
						//Pas trouve, on decale tout !!
						for ($i=count($search)-1; $i>=0; $i--) {
							$search[$i+1]=$search[$i];
							decale("field_".$i."_".$search[$i],"field_".($i+1)."_".$search[$i]);
							decale("op_".$i."_".$search[$i],"op_".($i+1)."_".$search[$i]);
							decale("inter_".$i."_".$search[$i],"inter_".($i+1)."_".$search[$i]);
							decale("fieldvar_".$i."_".$search[$i],"fieldvar_".($i+1)."_".$search[$i]);
						}
						$search[0]="s_2";
						$op_0_s_2="EQ";
						$field_0_s_2=array();
					}
				}
				
				if ($mode_aff) {
					//générer les critères de la multi_critères
		       		$search[1]="s_1";
		       		$op_="EQ";
		       								
					//opérateur
	    			$op="op_1_".$search[1];
	    			global $$op;
	    			$$op=$op_;
	    		    		
	    			//contenu de la recherche
	    			$field="field_1_".$search[1];
	    			$field_=array();
	    			$field_[0]=$n;
	    			global $$field;
	    			$$field=$field_;
	    	    	
	    	    	//opérateur inter-champ
	    			$inter="inter_1_".$search[1];
	    			global $$inter;
	    			$$inter="and";
	    		    		
	    			//variables auxiliaires
	    			$fieldvar_="fieldvar_1_".$search[1];
	    			global $$fieldvar_;
	    			$$fieldvar_="";
	    			$fieldvar=$$fieldvar_;
				}
				$es=new search("search_fields_unimarc");
				$result=$es->show_form("./index.php?lvl=$lvl&search_type_asked=external_search","./index.php?lvl=search_result&search_type_asked=external_search");
			} else {
				global $mode_aff;
				//Si il y a une mode d'affichage demandé, on construit l'écran correspondant
				if ($mode_aff) {
					$f=get_field_text($n);
					$user_query=$f[0];
					$look=$f[1];
					global $$look;
					$$look=1;	
					global $look_FIRSTACCESS;
					$look_FIRSTACCESS=1;
				} else {
					if ($external_env) {
						$external_env=unserialize(stripslashes($external_env));
						foreach ($external_env as $varname=>$varvalue) {
							global $$varname;
							$$varname=$varvalue;
						}
					}
				}
				$result=$search_input;
				$result=str_replace("!!user_query!!",htmlentities(stripslashes($user_query),ENT_QUOTES,$charset),$result);
				$result = str_replace("<!--!!ou_chercher!!-->", do_ou_chercher(), $result);
				$result = str_replace("<!--!!sources!!-->", do_sources(), $result);
			}
			$others="<li><a href=\"./index.php?search_type_asked=simple_search\">".$msg["simple_search"]."</a></li>\n";
			if ($opac_allow_personal_search) $others.="<li><a href=\"./index.php?search_type_asked=search_perso\">".$msg["search_perso_menu"]."</a></li>";		
			$others.=$onglets_search_perso;	
			if ($opac_allow_extended_search) $others.="<li><a href=\"./index.php?search_type_asked=extended_search\">".$msg["extended_search"]."</a></li>";
			if ($opac_allow_term_search) $others.="<li><a href=\"./index.php?search_type_asked=term_search\">".$msg["term_search"]."</a></li>\n";
			if ($opac_allow_tags_search) $others.="<li><a href=\"./index.php?search_type_asked=tags_search\">".$msg["tags_search"]."</a></li>";
			if (($opac_show_onglet_empr==1)||(($opac_show_onglet_empr==2)&&($_SESSION["user_code"]))) {
				if (!$_SESSION["user_code"]) $others.="<li><a href=\"./index.php?search_type_asked=connect_empr\">".$msg["onglet_empr_connect"]."</a></li>";
				else $others.="<li><a href=\"$empr_link_onglet\">".$msg["onglet_empr_compte"]."</a></li>";
			}
			$others2="";
			$result=str_replace("!!others!!",$others,$result);
			$result=str_replace("!!others2!!",$others2,$result);
			$result="<div id='search'>".$result."</div>";
			$result.=$onglets_search_perso_form;
			break;
			
		//Recherche par termes
		case "term_search":
			global $search_term;
			global $term_click;
			global $page_search;
			global $opac_term_search_height;
			global $opac_show_help;
			
			if (!$opac_term_search_height) $height=300; 
			else $height=$opac_term_search_height;
			
			$search_form_term = "
			<div id='search'>
			<ul class='search_tabs'>!!others!!".
				($opac_show_onglet_help ? "<li><a href=\"./index.php?lvl=infopages&pagesid=$opac_show_onglet_help\">".$msg["search_help"]."</a></li>": '')."
			</ul>
			<div id='search_crl'></div>
			<form class='form-$current_module' name='term_search_form' method='post' action='./index.php?lvl=$lvl&search_type_asked=term_search'>
				<div class='form-contenu'>
				<!-- sel_thesaurus -->
							".$msg["term_search_search_for"]." <input type='text' class='saisie-50em' name='search_term' value='".htmlentities(stripslashes($search_term),ENT_QUOTES,$charset)."'>
					<!--	Bouton Rechercher -->
						<input type='submit' class='boutonrechercher' value='$msg[142]' onClick=\"this.form.page_search.value=''; this.form.term_click.value='';\"/>\n";
			if ($opac_show_help) $search_form_term .= "<input type='submit' class='bouton' value='$msg[search_help]' onClick='window.open(\"help.php?whatis=search_terms\", \"search_help\", \"scrollbars=yes, toolbar=no, dependent=yes, width=400, height=400, resizable=yes\"); return false' />\n";
			$search_form_term .= "<input type='hidden' name='term_click' value='".htmlentities(stripslashes($term_click),ENT_QUOTES,$charset)."'/>
				<input type='hidden' name='page_search' value='".$page_search."'/>
				</div>
			</form>
			<script type='text/javascript'>
				document.forms['term_search_form'].elements['search_term'].focus();
				</script>
			</div>
			";

			//recuperation du thesaurus session 
			if(!$id_thes) $id_thes = thesaurus::getSessionThesaurusId();
			else thesaurus::setSessionThesaurusId($id_thes);
			
			//affichage du selectionneur de thesaurus et du lien vers les thesaurus
			$liste_thesaurus = thesaurus::getThesaurusList();
			$sel_thesaurus = '';
			$lien_thesaurus = '';
			
			if ($opac_thesaurus != 0) {	 //la liste des thesaurus n'est pas affichée en mode monothesaurus
				$sel_thesaurus = "<select class='saisie-30em' id='id_thes' name='id_thes' ";
				$sel_thesaurus.= "onchange = \"document.location = './index.php?lvl=index&search_type_asked=term_search&id_thes='+document.getElementById('id_thes').value; \">" ;
				foreach($liste_thesaurus as $id_thesaurus=>$libelle_thesaurus) {
					$sel_thesaurus.= "<option value='".$id_thesaurus."' "; ;
					if ($id_thesaurus == $id_thes) $sel_thesaurus.= " selected";
					$sel_thesaurus.= ">".htmlentities($libelle_thesaurus,ENT_QUOTES, $charset)."</option>";
				}
				$sel_thesaurus.= "<option value=-1 ";
				if ($id_thes == -1) $sel_thesaurus.= "selected ";
				$sel_thesaurus.= ">".htmlentities($msg['thes_all'],ENT_QUOTES, $charset)."</option>";
				$sel_thesaurus.= "</select>&nbsp;";
				$lien_thesaurus = "<a href='./autorites.php?categ=categories&sub=thes'>".$msg[thes_lien]."</a>";
			
			}	
			$search_form_term=str_replace("<!-- sel_thesaurus -->",$sel_thesaurus,$search_form_term);
			$search_form_term=str_replace("<!-- lien_thesaurus -->",$lien_thesaurus,$search_form_term);
			
			$result=$search_form_term;

			$others="";
			$others.="<li><a href=\"./index.php?search_type_asked=simple_search\">".$msg["simple_search"]."</a></li>";
			if ($opac_allow_personal_search) $others.="<li><a href=\"./index.php?search_type_asked=search_perso\">".$msg["search_perso_menu"]."</a></li>";
			$others.=$onglets_search_perso;
			if ($opac_allow_extended_search) $others.="<li><a href=\"./index.php?search_type_asked=extended_search\">".$msg["extended_search"]."</a></li>";
			$others.="<li id='current'>".$msg["search_by_terms"]."</li>";
			if ($opac_allow_tags_search) $others.="<li><a href=\"./index.php?search_type_asked=tags_search\">".$msg["tags_search"]."</a></li>";
			if (($opac_show_onglet_empr==1)||(($opac_show_onglet_empr==2)&&($_SESSION["user_code"]))) {
				if (!$_SESSION["user_code"]) $others.="<li><a href=\"./index.php?search_type_asked=connect_empr\">".$msg["onglet_empr_connect"]."</a></li>";
				else $others.="<li><a href=\"$empr_link_onglet\">".$msg["onglet_empr_compte"]."</a></li>";
			}
			if ($opac_allow_external_search) $others.="<li><a href=\"./index.php?search_type_asked=external_search&external_type=simple\">".$msg["connecteurs_external_search"]."</a></li>";
			$result=str_replace("!!others!!",$others,$result);
			$result.="
			<a name='search_frame'/>
			<iframe style='border: solid 1px black;' name='term_search' class='frame_term_search' src='".$base_path."/term_browse.php?search_term=".rawurlencode(stripslashes($search_term))."&term_click=".rawurlencode(stripslashes($term_click))."&page_search=$page_search&id_thes=$id_thes' width='100%' height='".$height."'></iframe>
			<br /><br />";
			$result.=$onglets_search_perso_form;
		break;
		
		case "tags_search":
			// les tests de formulaire
			$result = $script_test_form;	
			$tests = test_field("search_input", "query", "recherche");
			$result = str_replace("!!tests!!", $tests, $result);
			
			if ($opac_search_other_function) $toprint_typdocfield.=search_other_function_filters();
	
			// le contenu
			$result .= $search_input;
			
			// on met la valeur a jour
			$result = str_replace("!!user_query!!", htmlentities($value,ENT_QUOTES,$charset), $result);
			$result = str_replace("<!--!!typdoc_field!!-->", "", $result);
			$result = str_replace("<!--!!ou_chercher!!-->","" , $result);

			// on se place dans le bon champ
			// $result .= form_focus("search_input", "query");
			$others="";
			$others="<li><a href=\"./index.php?search_type_asked=simple_search\">".$msg["simple_search"]."</a></li>\n";
			if ($opac_allow_personal_search) $others.="<li><a href=\"./index.php?search_type_asked=search_perso\">".$msg["search_perso_menu"]."</a></li>";
			$others.=$onglets_search_perso;
			if ($opac_allow_extended_search) $others.="<li><a href=\"./index.php?search_type_asked=extended_search\">".$msg["extended_search"]."</a></li>";
			if ($opac_allow_term_search) $others.="<li><a href=\"./index.php?search_type_asked=term_search\">".$msg["term_search"]."</a></li>";
			if ($opac_allow_tags_search) $others.="<li id='current'>".$msg["tags_search"]."</li>";
			if (($opac_show_onglet_empr==1)||(($opac_show_onglet_empr==2)&&($_SESSION["user_code"]))) {
				if (!$_SESSION["user_code"]) $others.="<li><a href=\"./index.php?search_type_asked=connect_empr\">".$msg["onglet_empr_connect"]."</a></li>";
				else $others.="<li><a href=\"$empr_link_onglet\">".$msg["onglet_empr_compte"]."</a></li>";
			}
			if ($opac_allow_external_search) $others.="<li><a href=\"./index.php?search_type_asked=external_search&external_type=simple\">".$msg["connecteurs_external_search"]."</a></li>";
			$result=str_replace("!!others!!",$others,$result);
			// Ajout de la liste des tags
			if($user_query=="") {
				$result.= "<h3><span>$msg[search_result_for]<b>".htmlentities(stripslashes($user_query),ENT_QUOTES,$charset)."</b></span></h3>";
				$tag = new tags();
				$result.=  $tag->listeAlphabetique();
			}	
			$result.=$onglets_search_perso_form;	
			break;

		// *****************
		// Pour affichage compte emprunteur en onglet	
		case "connect_empr":
			// les tests de formulaire
			$result = $search_input;
			$others="";
			$others="<li><a href=\"./index.php?search_type_asked=simple_search\">".$msg["simple_search"]."</a></li>\n";
			if ($opac_allow_personal_search) $others.="<li><a href=\"./index.php?search_type_asked=search_perso\">".$msg["search_perso_menu"]."</a></li>";
			$others.=$onglets_search_perso;			
			if ($opac_allow_extended_search) $others.="<li><a href=\"./index.php?search_type_asked=extended_search\">".$msg["extended_search"]."</a></li>";
			if ($opac_allow_term_search) $others.="<li><a href=\"./index.php?search_type_asked=term_search\">".$msg["term_search"]."</a></li>";
			if ($opac_allow_tags_search) $others.="<li><a href=\"./index.php?search_type_asked=tags_search\">".$msg["tags_search"]."</a></li>";
			if ($opac_show_onglet_empr) {
				if (!$_SESSION["user_code"]) $others.="<li id='current'>".$msg["onglet_empr_connect"]."</li>";
				else $others.="<li id='current'>".$msg["onglet_empr_compte"]."</li>";
			}
			if ($opac_allow_external_search) $others.="<li><a href=\"./index.php?search_type_asked=external_search&external_type=simple\">".$msg["connecteurs_external_search"]."</a></li>";
			$result=str_replace("!!account_or_form_empr_connect!!",affichage_onglet_compte_empr(),$result);
			$result=str_replace("!!others!!",$others,$result);
			$result.=$onglets_search_perso_form;
			break;
		case "search_perso":
			// les tests de formulaire
			$result = $search_input;
			$others="";
			$others="<li><a href=\"./index.php?search_type_asked=simple_search\">".$msg["simple_search"]."</a></li>\n";
			if ($opac_allow_personal_search) $others.="<li id='current'>".$msg["search_perso_menu"]."</li>";
			$others.=$onglets_search_perso;			
			if ($opac_allow_extended_search) $others.="<li><a href=\"./index.php?search_type_asked=extended_search\">".$msg["extended_search"]."</a></li>";
			if ($opac_allow_term_search) $others.="<li><a href=\"./index.php?search_type_asked=term_search\">".$msg["term_search"]."</a></li>";
			if ($opac_allow_tags_search) $others.="<li><a href=\"./index.php?search_type_asked=tags_search\">".$msg["tags_search"]."</a></li>";
			if (($opac_show_onglet_empr==1)||(($opac_show_onglet_empr==2)&&($_SESSION["user_code"]))) {
				if (!$_SESSION["user_code"]) $others.="<li><a href=\"./index.php?search_type_asked=connect_empr\">".$msg["onglet_empr_connect"]."</a></li>";
				else $others.="<li><a href=\"$empr_link_onglet\">".$msg["onglet_empr_compte"]."</a></li>";
			}			
			if ($opac_allow_external_search) $others.="<li><a href=\"./index.php?search_type_asked=external_search&external_type=simple\">".$msg["connecteurs_external_search"]."</a></li>";
			
			$search_p= new search_persopac();
			$result=str_replace("!!contenu!!",$search_p->do_list(),$result);
			$result=str_replace("!!others!!",$others,$result);
		break;
			
	}
	return $result;
}

function do_ou_chercher () {
	global $look_TITLE,
	       $look_AUTHOR,
	       $look_PUBLISHER,
	       $look_TITRE_UNIFORME,
	       $look_COLLECTION,
	       $look_SUBCOLLECTION,
	       $look_CATEGORY,
	       $look_INDEXINT,
	       $look_KEYWORDS,
	       $look_ABSTRACT,
	       $look_ALL,
	       $look_DOCNUM,
	       $look_CONTENT;

	global $look_FIRSTACCESS ; // si 0 alors premier Acces : la rech par defaut est cochee
	
	// pour mise en service de cette precision de recherche : commenter cette partie 
	/*
	$look_TITLE = "1" ;        
	$look_AUTHOR = "1" ;              
	$look_PUBLISHER = "1" ;           
	$look_COLLECTION = "1" ;          
	$look_SUBCOLLECTION = "1" ;       
	$look_CATEGORY = "1" ;            
	$look_INDEXINT = "1" ;            
	$look_KEYWORDS = "1" ;            
	$look_ABSTRACT = "1" ;            
	$look_CONTENT = "1" ;  
	return "";
	*/
	// pour mise en service de cette precision de recherche : commenter jusque la
	
	// on recupere les globales de ce qui est autorise en recherche dans le parametrage de l'OPAC
	global	$opac_modules_search_title,
		$opac_modules_search_author,
		$opac_modules_search_publisher,
		$opac_modules_search_titre_uniforme,
		$opac_modules_search_collection,
		$opac_modules_search_subcollection,
		$opac_modules_search_category,
		$opac_modules_search_indexint,
		$opac_modules_search_keywords,
		$opac_modules_search_abstract,
		$opac_modules_search_all,
		$opac_modules_search_docnum,
		$pmb_indexation_docnum,
		$opac_allow_tags_search;
		// $opac_modules_search_content; inutilise pour l'instant, le search_abstract cherche aussi dans les notes de contenu
	
	global $msg,$get_query;
	
	if (!$look_FIRSTACCESS && !$get_query ) {
		// premier acces :
		if ($opac_modules_search_title==2) $look_TITLE=1;
		if ($opac_modules_search_author==2) $look_AUTHOR=1 ;
		if ($opac_modules_search_publisher==2) $look_PUBLISHER = 1 ; 
		if ($opac_modules_search_titre_uniforme==2) $look_TITRE_UNIFORME = 1 ; 
		if ($opac_modules_search_collection==2) $look_COLLECTION = 1 ;	
		if ($opac_modules_search_subcollection==2) $look_SUBCOLLECTION = 1 ;
		if ($opac_modules_search_category==2) $look_CATEGORY = 1 ;
		if ($opac_modules_search_indexint==2) $look_INDEXINT = 1 ;
		if ($opac_modules_search_keywords==2) $look_KEYWORDS = 1 ;
		if ($opac_modules_search_abstract==2) $look_ABSTRACT = 1 ;
		if ($opac_modules_search_all==2) $look_ALL = 1 ;
		if ($opac_modules_search_docnum==2) $look_DOCNUM = 1;
	}
	if ($look_TITLE) 		$checked_TITLE = "checked" ;        
	if ($look_AUTHOR)		$checked_AUTHOR = "checked" ;              
	if ($look_PUBLISHER)		$checked_PUBLISHER = "checked" ;   
	if ($look_TITRE_UNIFORME)		$checked_TITRE_UNIFORME = "checked" ;          
	if ($look_COLLECTION)		$checked_COLLECTION = "checked" ;          
	if ($look_SUBCOLLECTION)	$checked_SUBCOLLECTION = "checked" ;       
	if ($look_CATEGORY)		$checked_CATEGORY = "checked" ;            
	if ($look_INDEXINT)		$checked_INDEXINT = "checked" ;            
	if ($look_KEYWORDS)		$checked_KEYWORDS = "checked" ;            
	if ($look_ABSTRACT)		$checked_ABSTRACT = "checked" ;    
	if ($look_ALL)		$checked_ALL = "checked" ; 
	if ($look_DOCNUM) $checked_DOCNUM = "checked";         

	if (!($look_TITLE || $look_AUTHOR || $look_PUBLISHER || $look_TITRE_UNIFORME || $look_COLLECTION || $look_SUBCOLLECTION || $look_CATEGORY || $look_INDEXINT || $look_KEYWORDS || $look_ABSTRACT || $look_ALL || $look_DOCNUM)) {
		$checked_TITLE = "checked" ;
		$look_TITLE = "1" ;
		$checked_AUTHOR = "checked" ;
		$look_AUTHOR = "1" ;
	}

	$ou_chercher_tab=array();
	if ($opac_modules_search_title>0) $ou_chercher_tab[] = "\n<span style='width: 30%; float: left;'><input type='checkbox' name='look_TITLE' id='look_TITLE' value='1' $checked_TITLE /><label for='look_TITLE'> $msg[titles] </label></span>";
	if ($opac_modules_search_author>0) $ou_chercher_tab[] = "\n<span style='width: 30%; float: left;'><input type='checkbox' name='look_AUTHOR' id='look_AUTHOR' value='1' $checked_AUTHOR /><label for='look_AUTHOR'> $msg[authors] </label></span>";
	if ($opac_modules_search_publisher>0) $ou_chercher_tab[] = "\n<span style='width: 30%; float: left;'><input type='checkbox' name='look_PUBLISHER' id='look_PUBLISHER' value='1' $checked_PUBLISHER /><label for='look_PUBLISHER'> $msg[publishers] </label></span>";
	if ($opac_modules_search_titre_uniforme>0) $ou_chercher_tab[] = "\n<span style='width: 30%; float: left;'><input type='checkbox' name='look_TITRE_UNIFORME' id='look_TITRE_UNIFORME' value='1' $checked_TITRE_UNIFORME/><label for='look_TITRE_UNIFORME'> ".$msg["titres_uniformes"]." </label></span>";
	if ($opac_modules_search_collection>0) $ou_chercher_tab[] = "\n<span style='width: 30%; float: left;'><input type='checkbox' name='look_COLLECTION' id='look_COLLECTION' value='1' $checked_COLLECTION /><label for='look_COLLECTION'> $msg[collections] </label></span>";
	if ($opac_modules_search_subcollection>0) $ou_chercher_tab[] = "\n<span style='width: 30%; float: left;'><input type='checkbox' name='look_SUBCOLLECTION' id='look_SUBCOLLECTION' value='1' $checked_SUBCOLLECTION /><label for='look_SUBCOLLECTION'> $msg[subcollections] </label></span>";
	if ($opac_modules_search_category>0) $ou_chercher_tab[] = "\n<span style='width: 30%; float: left;'><input type='checkbox' name='look_CATEGORY' id='look_CATEGORY' value='1' $checked_CATEGORY /><label for='look_CATEGORY'> $msg[categories] </label></span>";
	if ($opac_modules_search_indexint>0) $ou_chercher_tab[] = "\n<span style='width: 30%; float: left;'><input type='checkbox' name='look_INDEXINT' id='look_INDEXINT' value='1' $checked_INDEXINT /><label for='look_INDEXINT'> $msg[indexint] </label></span>";
	if ($opac_modules_search_keywords>0) {	
		$ou_chercher_skey = "\n<span style='width: 30%; float: left;'><input type='checkbox' name='look_KEYWORDS' id='look_KEYWORDS' value='1' $checked_KEYWORDS /><label for='look_KEYWORDS'> ";
	 	if($opac_allow_tags_search)	$ou_chercher_skey .= $msg['tag'];
	 	else $ou_chercher_skey .= $msg['keywords'];
	 	$ou_chercher_skey .= "</label></span>";
	 	$ou_chercher_tab[] = $ou_chercher_skey ; 
	}
	if ($opac_modules_search_abstract>0) $ou_chercher_tab[] = "\n<span style='width: 30%; float: left;'><input type='checkbox' name='look_ABSTRACT' id='look_ABSTRACT' value='1' $checked_ABSTRACT /><label for='look_ABSTRACT'> $msg[abstract] </label></span>";
	if ($opac_modules_search_all>0) $ou_chercher_tab[] = "\n<span style='width: 30%; float: left;'><input type='checkbox' name='look_ALL' id='look_ALL' value='1' $checked_ALL /><label for='look_ALL'> ".$msg['tous']." </label></span>";
	if (($pmb_indexation_docnum && $opac_modules_search_docnum)>0) $ou_chercher_tab[] = "\n<span style='width: 30%; float: left;'><input type='checkbox' name='look_DOCNUM' id='look_DOCNUM' value='1' $checked_DOCNUM /><label for='look_DOCNUM'> ".$msg['docnum']." </label></span>";

	$ou_chercher = "<div class='row'>" ;
	for ($nbopac_smodules=0;$nbopac_smodules<count($ou_chercher_tab);$nbopac_smodules++) {
		if ((($nbopac_smodules+1)/3)==(($nbopac_smodules+1) % 3)) $ou_chercher .= "</div><div class='row'>" ;
		$ou_chercher .= $ou_chercher_tab[$nbopac_smodules];
	}
	
	$ou_chercher .= "</div><div style='clear: both;'><input type='hidden' name='look_FIRSTACCESS' value='1' /></div>" ;
	$ou_chercher = str_replace ("<div class='row'></div>", "", $ou_chercher ) ;
	return $ou_chercher;
}

function do_ou_chercher_hidden () {

	// on récupère les globales de ce qui est autorisé en recherche dans le paramétrage de l'OPAC
	global	$opac_modules_search_title,
		$opac_modules_search_author,
		$opac_modules_search_publisher,
		$opac_modules_search_titre_uniforme,
		$opac_modules_search_collection,
		$opac_modules_search_subcollection,
		$opac_modules_search_category,
		$opac_modules_search_indexint,
		$opac_modules_search_keywords,
		$opac_modules_search_abstract,
		$opac_modules_search_docnum,
		$opac_modules_search_all ;
	
	$ou_chercher_hidden = '' ;
	if ($opac_modules_search_title>1) $ou_chercher_hidden .= "<input type='hidden' name='look_TITLE' id='look_TITLE' value='1' />";
	if ($opac_modules_search_author>1) $ou_chercher_hidden .= "<input type='hidden' name='look_AUTHOR' id='look_AUTHOR' value='1' />";
	if ($opac_modules_search_publisher>1) $ou_chercher_hidden .= "<input type='hidden' name='look_PUBLISHER' id='look_PUBLISHER' value='1' />";
	if ($opac_modules_search_titre_uniforme>1) $ou_chercher_hidden .= "<input type='hidden' name='look_TITRE_UNIFORME' id='look_TITRE_UNIFORME' value='1' />";
	if ($opac_modules_search_collection>1) $ou_chercher_hidden .= "<input type='hidden' name='look_COLLECTION' id='look_COLLECTION' value='1' />";
	if ($opac_modules_search_subcollection>1) $ou_chercher_hidden .= "<input type='hidden' name='look_SUBCOLLECTION' id='look_SUBCOLLECTION' value='1' />";
	if ($opac_modules_search_category>1) $ou_chercher_hidden .= "<input type='hidden' name='look_CATEGORY' id='look_CATEGORY' value='1' />";
	if ($opac_modules_search_indexint>1) $ou_chercher_hidden .= "<input type='hidden' name='look_INDEXINT' id='look_INDEXINT' value='1' />";
	if ($opac_modules_search_keywords>1) $ou_chercher_hidden .= "<input type='hidden' name='look_KEYWORDS' id='look_KEYWORDS' value='1' /> ";
	if ($opac_modules_search_abstract>1) $ou_chercher_hidden .= "<input type='hidden' name='look_ABSTRACT' id='look_ABSTRACT' value='1' />";
	if ($opac_modules_search_all>1) $ou_chercher_hidden .= "<input type='hidden' name='look_ALL' id='look_ALL' value='1' />";
	if ($opac_modules_search_docnum>1) $ou_chercher_hidden .= "<input type='hidden' name='look_DOCNUM' id='look_DOCNUM' value='1' />";
	
	return $ou_chercher_hidden;
}

function get_field_text($n) {
	$typ_search=$_SESSION["notice_view".$n]["search_mod"];
	switch($_SESSION["notice_view".$n]["search_mod"]) {
		case 'title':
			$valeur_champ=$_SESSION["user_query".$n];
			$typ_search="look_TITLE";
			break;
		case 'all':
			$valeur_champ=$_SESSION["user_query".$n];
			$typ_search="look_ALL";
			break;
		case 'abstract':
			$valeur_champ=$_SESSION["user_query".$n];
			$typ_search="look_ABSTRACT";
			break;
		case 'keyword':
			$valeur_champ=$_SESSION["user_query".$n];
			$typ_search="look_KEYWORDS";
			break;
		case 'author_see':
			//Recherche de l'auteur
			$author_id=$_SESSION["notice_view".$n]["search_id"];
			$requete="select concat(author_name,', ',author_rejete) from authors where author_id=".$author_id;
			$r_author=mysql_query($requete);
			if (@mysql_num_rows($r_author)) {
				$valeur_champ=mysql_result($r_author,0,0);
			}
			$typ_search="look_AUTHOR";
		break;
		case 'categ_see':
			//Recherche de la categorie
			$categ_id=$_SESSION["notice_view".$n]["search_id"];
			$requete="select libelle_categorie from categories where num_noeud=".$categ_id;
			$r_cat=mysql_query($requete);
			if (@mysql_num_rows($r_cat)) {
				$valeur_champ=mysql_result($r_cat,0,0);
			}
			$typ_search="look_CATEGORY";
		break;		
		case 'indexint_see':	
			//Recherche de l'indexation
			$indexint_id=$_SESSION["notice_view".$n]["search_id"];
			$requete="select indexint_name from indexint where indexint_id=".$indexint_id;
			$r_indexint=mysql_query($requete);
			if (@mysql_num_rows($r_indexint)) {
				$valeur_champ=mysql_result($r_indexint,0,0);
			}
			$typ_search="look_INDEXINT";
		break;		
		case 'coll_see':	
			//Recherche de l'indexation
			$coll_id=$_SESSION["notice_view".$n]["search_id"];
			$requete="select collection_name from collections where collection_id=".$coll_id;
			$r_coll=mysql_query($requete);
			if (@mysql_num_rows($r_coll)) {
				$valeur_champ=mysql_result($r_coll,0,0);
			}
			$typ_search="look_COLLECTION";
		break;		
		case 'publisher_see':	
			//Recherche de l'editeur
			$publisher_id=$_SESSION["notice_view".$n]["search_id"];
			$requete="select ed_name from publishers where ed_id=".$publisher_id;
			$r_pub=mysql_query($requete);
			if (@mysql_num_rows($r_pub)) {
				$valeur_champ=mysql_result($r_pub,0,0);
			}
			$typ_search="look_PUBLISHER";
		break;		
		case 'titre_uniforme_see':	
			//Recherche de titre uniforme
			$tu_id=$_SESSION["notice_view".$n]["search_id"];
			$requete="select tu_name from titres_uniformes where ed_id=".$tu_id;
			$r_tu=mysql_query($requete);
			if (@mysql_num_rows($r_tu)) {
				$valeur_champ=mysql_result($r_tu,0,0);
			}
			$typ_search="look_TITRE_UNIFORME";
		break;				
		case 'subcoll_see':	
			//Recherche de l'editeur
			$subcoll_id=$_SESSION["notice_view".$n]["search_id"];
			$requete="select sub_coll_name from sub_collections where sub_coll_id=".$subcoll_id;
			$r_subcoll=mysql_query($requete);
			if (@mysql_num_rows($r_subcoll)) {
				$valeur_champ=mysql_result($r_subcoll,0,0);
			}
			$typ_search="look_SUBCOLLECTION";
		break;
	}
	return array($valeur_champ,$typ_search);
}

function do_sources() {
	global $charset,$source, $dbh, $msg;
	$r="";
	if (!$source) $source=array();
	//Recherche des sources
    $requete="SELECT connectors_categ_sources.num_categ, connectors_sources.source_id, connectors_categ.connectors_categ_name as categ_name, connectors_categ.opac_expanded, connectors_sources.name, connectors_sources.comment, connectors_sources.repository, connectors_sources.opac_allowed, source_sync.cancel FROM connectors_sources LEFT JOIN connectors_categ_sources ON (connectors_categ_sources.num_source = connectors_sources.source_id) LEFT JOIN connectors_categ ON (connectors_categ.connectors_categ_id = connectors_categ_sources.num_categ) LEFT JOIN source_sync ON (connectors_sources.source_id = source_sync.source_id AND connectors_sources.repository=2) WHERE connectors_sources.opac_allowed=1 ORDER BY connectors_categ_sources.num_categ DESC, connectors_sources.name";
    $resultat=mysql_query($requete, $dbh);
    if ($source) $_SESSION["checked_sources"]=$source;
    if ($_SESSION["checked_sources"]&&(!$source)) $source=$_SESSION["checked_sources"];
    //gen_plus_form("zsources",$msg["connecteurs_source_label"],"<!--!!sources!!-->",true)
    $old_categ = 0;
    $count = 0;
    $paquets_de_sources = array();
    $paquets_de_source = array();
    while (($srce=mysql_fetch_object($resultat))) {
    	if ($old_categ !== $srce->num_categ) {
    		//$msg["connecteurs_source_label"]
    		if ($paquets_de_source) $paquets_de_sources[] = $paquets_de_source; 
    		$paquets_de_source = array();
    		$paquets_de_source["id"] = $srce->num_categ;
       		$paquets_de_source["name"] = $srce->categ_name ? $srce->categ_name : $msg["source_no_category"];
    		$paquets_de_source["opac_expanded"] = $srce->opac_expanded ? true : false;
       		
			// gen_plus_form("zsources".$count, $srce->categ_name ,"sdfsdfsdfsdf",true);
	   		$count++;
	   		$old_categ = $srce->num_categ;
    	}
   		$paquets_de_source["content"] .="<div style='width:30%; float:left'>
				<input type='checkbox' ".($srce->cancel==2 ? 'DISABLED' : "")." name='source[]' value='".$srce->source_id."' id='source_".$srce->source_id."_".$count."' onclick='change_source_checkbox(source_".$srce->source_id."_".$count.", ".$srce->source_id.");'";
   		if (array_search($srce->source_id,$source)!==false) {
   			$paquets_de_source["content"] .= " checked";
   		}
   		$paquets_de_source["content"] .= "/>".($srce->cancel==2 ? "<s>" : "")."<label for='source_".$srce->source_id."_".$count."'><img src='images/".($srce->repository==1?"entrepot.png":"globe.gif")."'/>&nbsp;".htmlentities($srce->name.($srce->comment?" : ".$srce->comment:""),ENT_QUOTES,$charset).($srce->cancel==2 ? "</s> <i>(".$msg["source_blocked"].")</i>" : "")."</label>
			</div>";
    }
	if ($paquets_de_source) $paquets_de_sources[] = $paquets_de_source; 
    foreach($paquets_de_sources as $paquets_de_source) {
    	$r .= gen_plus_form("zsources".$paquets_de_source["id"], $paquets_de_source["name"], $paquets_de_source["content"], $paquets_de_source["opac_expanded"])."\n\n";
    }
   	$r.="<div class='row'></div>";
   	return $r;
}

function decale($var,$var1) {
	global $$var;
	global $$var1;
	$$var1=$$var;
}