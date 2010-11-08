<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: stat_view.class.php,v 1.9 2010-01-29 15:37:14 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once("$include_path/templates/stat_opac.tpl.php");
require_once ($class_path . "/parse_format.class.php");
require_once("$include_path/misc.inc.php");
require_once("$include_path/user_error.inc.php");
require_once ($class_path . "/consolidation.class.php");
require_once ($class_path . "/stat_query.class.php");

class stat_view {
	
	var $action='';
	var $section='';
	
	/**
	 * Constructeur
	 */
	function stat_view($section='',$act=''){
		$this->action = $act;
		$this->section = $section;
	}
	
	/**
	 * Execution des différentes actions
	 */
	function proceed(){
		global $msg, $id_col, $col_name, $expr_col, $expr_filtre, $view_name, $view_comment, $id_view; 
		global $id, $id_req, $move, $conso, $date_deb,$date_fin,$date_ech, $list_ck;
		
		if($id)
			$id_req=$id;
		
		switch($this->section){
			case 'view_list':
				switch($this->action){
					case 'save_view':
						//Enregistrement/Insertion d'une vue
						$this->save_view($id_view,$view_name,$view_comment);
						print $this->do_form();
					break;
					case 'suppr_view':
						//Suppression d'une vue
						$this->delete_view($id_view);
						print $this->do_form();
					break;
					case 'consolide_view':
						if($date_deb>$date_fin)
							error_form_message($msg['stat_wrong_date_interval']);
						elseif(!$list_ck)
							error_form_message($msg['stat_no_view_selected']);
						else { 
							$consolidation = new consolidation($conso,$date_deb,$date_fin,$date_ech, $list_ck);
							$consolidation->make_consolidation();
						}
						print $this->do_form();
					break;
					case 'reinit':
						//Réinitialisation de la vue
						$this->reinitialiser_view($id_view);
						print $this->do_form();
					break;
					//Actions liées aux requêtes
					case 'configure':
					case 'update_config':				
					case 'update_request':				
					case 'exec_req':
					case 'final':
						//Actions liées aux requêtes
						$stq = new stat_query($id_req,$this->action,$id_view);
						$stq->proceed();
						break;
					case 'save_request':				
					case 'suppr_request':
						$stq = new stat_query($id_req,$this->action,$id_view);
						$stq->proceed();
						print $this->do_form();
						break;
					default:
						print $this->do_form();
					break;
				}
				
			break;	
			case 'view_gestion':
				switch($this->action){
					case 'add_view':
						//ajout d'une vue
						//print $this->do_addview_form();
						break;					
					case 'update_view':
						//MaJ vue
						switch($move){
							case 'up':
								//Déplacer un élément dans la liste des colonnes
								$this->monter_element($id_col);
							break;
							case 'down':
								//Déplacer un élément dans la liste des colonnes
								$this->descendre_element($id_col);
							break;
						}	
					break;
					case 'save_col':
						//Enregistrement/Insertion d'une colonne
						$this->save_col($id_col,$col_name,$expr_col,$expr_filtre,$id_view);
					break;
					case 'suppr_col':
						//Suppression d'une colonne
						$this->delete_col($id_col);
					break;	
				}
				print $this->do_addview_form($id_view);
			break;
			case 'colonne':
				switch($this->action){
					case 'add_col':
						//ajout d'une colonne
						print $this->do_col_form();
					break;
					case 'save_col':
						//Enregistrement/Insertion d'une colonne
						$this->save_col($id_col,$col_name,$expr_col,$expr_filtre,$id_view);
						print $this->do_addview_form($id_view);
					break;
					case 'update_col':
						//MaJ colonne
						print $this->do_col_form($id_col);
					break;
					case 'suppr_col':
						//Suppression d'une colonne
						$this->delete_col($id_col);
						print $this->do_addview_form($id_view);
					break;	
				}
			break;
			case 'query':
				//Actions liées aux requêtes
				$stq = new stat_query($id_req,$this->action,$id_view);
				$stq->proceed();
			break;
			default:
			break;
		}
	}
	
	/**
	 * On fait appel au formulaire qui affiche la liste des vues
	 */
	function do_form(){
		global $stat_opac_view_form, $msg;	
		global $msg, $dbh;
 		global $charset;
 		global $javascript_path;
		
 		
	 	print "
			<script type=\"text/javascript\" src=\"".$javascript_path."/tablist.js\"></script>
			<a href=\"javascript:expandAll()\"><img src='./images/expand_all.gif' border='0' id=\"expandall\"></a>
			<a href=\"javascript:collapseAll()\"><img src='./images/collapse_all.gif' border='0' id=\"collapseall\"></a>
			";
		
	 	$requete_vue = "select * from statopac_vues order by date_consolidation desc";
	 	$res = mysql_query($requete_vue,$dbh);
	 	$vue_affichage="";	
		if(mysql_num_rows($res) == 0){			
			$stat_opac_view_form = str_replace('!!liste_vues!!',$msg["stat_no_view_created"],$stat_opac_view_form);
			$stat_opac_view_form = str_replace('!!options_conso!!','',$stat_opac_view_form);
			$stat_opac_view_form = str_replace('!!btn_consolide!!','',$stat_opac_view_form);
			return $stat_opac_view_form;
		} else {		
			$vue_affichage="";
			$parity=1;
			$btn_consolide= "<input class='bouton' type='submit' value=\"".$msg[stat_consolide_view]."\" onClick=\"this.form.act.value='consolide_view'; document.view.action='./admin.php?categ=opac&sub=stat&section=view_list'\"/>";
			while(($vue = mysql_fetch_object($res))){			
				$rqt="select * from statopac_request where num_vue='".addslashes($vue->id_vue)."' order by name";
				$result = mysql_query($rqt);
				$liste_requete ="";
				while(($request = mysql_fetch_object($result))){
					if ($parity % 2) {
					$pair_impair = "even";
					} else {
						$pair_impair = "odd";
					}
					$parity++;
					$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" ";
					$td_action = " onmousedown=\"document.location='./admin.php?categ=opac&sub=stat&section=query&act=update_request&id_req=$request->idproc&id_view=$vue->id_vue';\" ";
					$btn_exec = "<input type='submit' class='bouton_small' name='exec_request' value='$msg[708]' onClick='document.view.action=\"./admin.php?categ=opac&sub=stat&section=view_list\";this.form.act.value=\"exec_req\"; this.form.id_req.value=\"$request->idproc\"; this.form.id_view.value=\"$vue->id_vue\";'/>";
					$liste_requete.="\n<tr class='$pair_impair'  $tr_javascript style='cursor: pointer'>
							<td width=10>$btn_exec</td>
							<td $td_action><strong>$request->name</strong><br>
								<small>$request->comment</small></td><td>
						";	
					if (preg_match_all("|!!(.*)!!|U",$request->requete,$query_parameters)) $liste_requete.="<a href='admin.php?categ=opac&sub=stat&section=view_list&act=configure&id_req=".$request->idproc."'>".$msg["procs_options_config_param"]."</a>";
					$liste_requete.="</td></tr>";					
				}
				
					$tab_list="<table><tr><th colspan=4>".htmlentities($vue->nom_vue,ENT_QUOTES, $charset)."</th></tr>".$liste_requete."</table>";
					$lien = "<a href='./admin.php?categ=opac&sub=stat&section=view_gestion&act=update_view&id_view=$vue->id_vue'>".htmlentities($vue->nom_vue,ENT_QUOTES, $charset) ."</a>";
					$space = "<small><span style='margin-right: 3px;'><img src='./images/spacer.gif' width='10' height='10' /></span></small>";
					$checkbox = "<input type='checkbox' class='checkbox' id='box$vue->id_vue' name='list_ck[]' value='$vue->id_vue'/>"; 				
					$btn = "<div class='row'><input class='bouton_small' type='button' value=\"".$msg[stat_add_request]."\" onClick=\"document.location='./admin.php?categ=opac&sub=stat&section=query&act=update_request&id_view=$vue->id_vue';\"/></div>";		
					$libelle_titre = $space.$checkbox.$space.$lien.$space.formatdate($vue->date_consolidation,1);
					$vue_affichage.=gen_plus($vue->id_vue,$libelle_titre,$tab_list.$btn);
			}
			
			
			//Liste des options de consolidation
			$options .= "<div id='opt_consoParent' class='notice-parent'>";
			$options .= "<img id='opt_consoImg' class='img_plus' hspace='3' border='0' onClick=\"expandBase('opt_conso',true);return false;\" title='requete' name='imEx' src=\"./images/plus.gif\" >";
			$options .= "$space <span class='notice-heada'>$msg[stat_options_consolidation]</span>";
			$options .= "</div>";	
			$options_contenu ="<div class='row'>
					<input type='radio' class='radio' id='id_lot' name='conso' value='1'/> 
						<label for='id_lot'>$msg[stat_last_consolidation]</label> <br><br>
					<input type='radio' class='radio' id='id_interval' name='conso' value='2'/> 
						<label for='id_interval'>$msg[stat_interval_consolidation] </label><br><br>
					<input type='radio' class='radio' id='id_debut' name='conso' value='3'/> 
						<label for='id_debut'>$msg[stat_echeance_consolidation]</label><br>
				</div>
			";
			$options.="<div id='opt_consoChild' class='notice-child' style='margin-bottom: 6px; display: none;'>$options_contenu</div>";
			$stat_opac_view_form=str_replace("!!options_conso!!",$options,$stat_opac_view_form);
			$stat_opac_view_form=str_replace("!!liste_vues!!",$vue_affichage,$stat_opac_view_form);
			$stat_opac_view_form=str_replace("!!btn_consolide!!",$btn_consolide,$stat_opac_view_form);
			
			$btn_date_deb = "<input type='hidden' name='date_deb' value='!!date_deb!!'/><input type='button' name='date_deb_lib' class='bouton_small' value='!!date_deb_lib!!'   
				onClick=\"openPopUp('./select.php?what=calendrier&caller=view&date_caller=!!date_deb!!&param1=date_deb&param2=date_deb_lib&auto_submit=NO&date_anterieure=YES', 'date_deb', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\" />";
			$btn_date_fin = "<input type='hidden' name='date_fin' value='!!date_fin!!'/><input type='button' name='date_fin_lib' class='bouton_small'   value='!!date_fin_lib!!'
				onClick=\"openPopUp('./select.php?what=calendrier&caller=view&date_caller=!!date_fin!!&param1=date_fin&param2=date_fin_lib&auto_submit=NO&date_anterieure=YES', 'date_fin', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\" />";
			$btn_date_echeance = "<input type='hidden' name='date_ech' value='!!date_ech!!'/><input type='button' name='date_ech_lib' class='bouton_small' value='!!date_ech_lib!!'  
				onClick=\"openPopUp('./select.php?what=calendrier&caller=view&date_caller=!!date_ech!!&param1=date_ech&param2=date_ech_lib&auto_submit=NO&date_anterieure=YES', 'date_ech', 250, 300, -2, -2, 'toolbar=no, dependent=yes, resizable=yes')\" />";
			
			$date_debut = strftime("%Y-%m-%d", mktime(0, 0, 0, date('m'), date('d')-1, date('y'))); 
			$btn_date_deb=str_replace("!!date_deb!!",$date_debut,$btn_date_deb);
			$btn_date_deb=str_replace("!!date_deb_lib!!",formatdate($date_debut),$btn_date_deb);
			$date_fin = today();			
			$btn_date_fin=str_replace("!!date_fin!!",$date_fin,$btn_date_fin);
			$btn_date_fin=str_replace("!!date_fin_lib!!",formatdate($date_fin),$btn_date_fin);
			$date_echeance = today();
			$btn_date_echeance=str_replace("!!date_ech!!",$date_echeance,$btn_date_echeance);
			$btn_date_echeance=str_replace("!!date_ech_lib!!",formatdate($date_echeance),$btn_date_echeance);
			$stat_opac_view_form=str_replace("!!date_deb_btn!!",$btn_date_deb,$stat_opac_view_form);
			$stat_opac_view_form=str_replace("!!date_fin_btn!!",$btn_date_fin,$stat_opac_view_form);
			$stat_opac_view_form=str_replace("!!echeance_btn!!",$btn_date_echeance,$stat_opac_view_form);
	 	}
	 	
		return $stat_opac_view_form;
		
	}
	
	/**
	 * On fait appel au formulaire d'ajout d'une vue
	 */
	function do_addview_form($vue_id=''){
		global $stat_view_addview_form;
		global $msg, $charset;
		
		if(!$vue_id){
			$stat_view_addview_form=str_replace("!!name_view!!",'',$stat_view_addview_form);
			$stat_view_addview_form=str_replace("!!view_comment!!",'',$stat_view_addview_form);
			$stat_view_addview_form=str_replace("!!table_colonne!!",'',$stat_view_addview_form);
			$stat_view_addview_form=str_replace("!!bouton_add_col!!",'',$stat_view_addview_form);
			$stat_view_addview_form=str_replace("!!bouton_reinit_view!!",'',$stat_view_addview_form);
			$stat_view_addview_form=str_replace("!!btn_suppr!!",'',$stat_view_addview_form);
			$stat_view_addview_form=str_replace("!!view_title!!",$msg["stat_view_create_title"],$stat_view_addview_form);
			$stat_view_addview_form=str_replace("!!id_view!!",'',$stat_view_addview_form);
						
			return $stat_view_addview_form;
			
		} else {
			$btn_add_col = "<input class='bouton' type='submit'  value=\"".$msg[stat_add_col]."\" onClick='this.form.act.value=\"add_col\"; document.addview.action=\"./admin.php?categ=opac&sub=stat&section=colonne&action=addcol\";'/>";
			$bouton_reinit_view="<input class='bouton' type='submit'  value=\"".$msg[stat_reinit_view]."\" onClick='this.form.act.value=\"reinit\";'/>";
			$btn_suppr = "<input class='bouton' type='submit'  value='$msg[63]' onClick='if(confirm_delete()) this.form.act.value=\"suppr_view\";'/>";
			
			$requete = "select nom_vue, comment from statopac_vues where id_vue='".addslashes($vue_id)."'";
			$resultat = mysql_query($requete);
			while(($vue=mysql_fetch_object($resultat))){
				$stat_view_addview_form=str_replace("!!name_view!!",htmlentities($vue->nom_vue,ENT_QUOTES,$charset),$stat_view_addview_form);
				$stat_view_addview_form=str_replace("!!view_comment!!",htmlentities($vue->comment,ENT_QUOTES, $charset),$stat_view_addview_form);
			}			
			$stat_view_addview_form=str_replace("!!bouton_add_col!!",$btn_add_col,$stat_view_addview_form);
			$stat_view_addview_form=str_replace("!!bouton_reinit_view!!",$bouton_reinit_view,$stat_view_addview_form);
			$stat_view_addview_form=str_replace("!!btn_suppr!!",$btn_suppr,$stat_view_addview_form);
			$stat_view_addview_form=str_replace("!!id_view!!",$vue_id,$stat_view_addview_form);
				
			$res="";		
			$requete="select id_col, nom_col, expression, filtre, ordre, datatype from statopac_vues_col where num_vue='".$vue_id."' order by ordre";
			$resultat=mysql_query($requete);
			
			if(mysql_num_rows($resultat) == 0){
				$res="<div class='row'>".$msg["stat_no_col_associate"]."</div>";
				$stat_view_addview_form=str_replace("!!table_colonne!!",$res,$stat_view_addview_form);		
				$stat_view_addview_form=str_replace("!!view_title!!",$msg["stat_view_modif_title"],$stat_view_addview_form);
				return $stat_view_addview_form;
			} else {
				$res="<table width=100%>\n";
				$res.="<tr><th>".$msg["stat_col_order"]."</th><th>".$msg["stat_col_name"]."</th><th>".$msg["stat_col_expr"]."</th><th>".$msg["stat_col_filtre"]."</th><th>".$msg['stat_col_type']."</th>";
				$parity=1;
				$n=0;
				while ($r=mysql_fetch_object($resultat)) {
					if ($parity % 2) {
						$pair_impair = "even";
					} else {
						$pair_impair = "odd";
					}
		
					$parity+=1;
					$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\"  ";
					$action_td=" onmousedown=\"document.location='./admin.php?categ=opac&sub=stat&section=colonne&act=update_col&id_col=$r->id_col&id_view=$vue_id';\" ";
					$res.="<tr class='$pair_impair' style='cursor: pointer' $tr_javascript>";
					$res.="<td align='center'>";
				    $res.="<input type='button' class='bouton_small' value='-' onClick='document.location=\"./admin.php?categ=opac&sub=stat&section=view_gestion&act=update_view&move=down&id_col=".$r->id_col."&id_view=$vue_id\"'/></a>";
				    $res .= "<input type='button' class='bouton_small' value='+' onClick='document.location=\"./admin.php?categ=opac&sub=stat&section=view_gestion&act=update_view&move=up&id_col=".$r->id_col."&id_view=$vue_id\"'/>";
					$res.="</td>";
					$res.="<td $action_td align='center'><b>".htmlentities($r->nom_col,ENT_QUOTES,$charset)."</b></td>
						<td $action_td align='center'>".htmlentities($r->expression,ENT_QUOTES,$charset)."</td>
						<td $action_td align='center'>".htmlentities($r->filtre,ENT_QUOTES,$charset)."</td>
						<td $action_td align='center'>".htmlentities($r->datatype,ENT_QUOTES,$charset)."</td>";
				}
				$res.="</tr></table>";
				$stat_view_addview_form=str_replace("!!table_colonne!!",$res,$stat_view_addview_form);
				$stat_view_addview_form=str_replace("!!view_title!!",$msg["stat_view_modif_title"],$stat_view_addview_form);
			}
		}
		return $stat_view_addview_form;
	}
	
	/**
	 * On fait appel au formulaire d'ajout de colonne
	 */
	function do_col_form($id_col=''){
		global $stat_view_addcol_form, $msg, $charset, $id_view; 
		
		if(!$id_col)	{
			$stat_view_addcol_form=str_replace("!!col_name!!",'',$stat_view_addcol_form);
			$stat_view_addcol_form=str_replace("!!expr_col!!",'',$stat_view_addcol_form);
			$stat_view_addcol_form=str_replace("!!btn_suppr!!",'',$stat_view_addcol_form);
			$stat_view_addcol_form=str_replace("!!expr_filtre!!",'',$stat_view_addcol_form);
			$stat_view_addcol_form=str_replace("!!id_view!!",$id_view,$stat_view_addcol_form);
			$stat_view_addcol_form=str_replace("!!id_col!!",$id_col,$stat_view_addcol_form);
			$stat_view_addcol_form=str_replace("!!col_title!!",$msg["stat_col_create_title"],$stat_view_addcol_form);
						
			//liste des type de données
			$datatype_list=array("small_text"=>"Texte","text"=>"Texte large","integer"=>"Entier","date"=>"Date","datetime"=>"Date/Heure","float"=>"Nombre à virgule");
			$t_list="<select name='datatype'>\n";
			reset($datatype_list);
			foreach ($datatype_list as $key=>$val){
				$t_list.="<option value='".$key."'";
				$t_list.=">".htmlentities($val,ENT_QUOTES, $charset)."</option>\n";
			}
			$t_list.="</select>\n";
			$stat_view_addcol_form=str_replace("!!datatype!!",$t_list,$stat_view_addcol_form);
			
			return $stat_view_addcol_form;
		} else {
			$requete="select nom_col, expression, filtre, datatype from statopac_vues_col where id_col='".$id_col."'";
			$resultat=mysql_query($requete);
			while (($col=mysql_fetch_object($resultat))){
				$col_name = htmlentities($col->nom_col,ENT_QUOTES,$charset);
				$expr = htmlentities($col->expression,ENT_QUOTES,$charset);
				$filtre = htmlentities($col->filtre,ENT_QUOTES,$charset);
				$datatype = htmlentities($col->datatype,ENT_QUOTES,$charset);
			}
			$stat_view_addcol_form=str_replace("!!col_name!!",$col_name,$stat_view_addcol_form);
			$stat_view_addcol_form=str_replace("!!expr_col!!",$expr,$stat_view_addcol_form);
			$stat_view_addcol_form=str_replace("!!expr_filtre!!",$filtre,$stat_view_addcol_form);
			$btn_suppr = "<input class='bouton' type='submit'  value='$msg[63]' onClick='if(confirm_delete()) this.form.act.value=\"suppr_col\"';/>";
			$stat_view_addcol_form=str_replace("!!btn_suppr!!",$btn_suppr,$stat_view_addcol_form);
			$stat_view_addcol_form=str_replace("!!col_title!!",$msg["stat_col_modif_title"],$stat_view_addcol_form);
			$stat_view_addcol_form=str_replace("!!id_view!!",$id_view,$stat_view_addcol_form);
			$stat_view_addcol_form=str_replace("!!id_col!!",$id_col,$stat_view_addcol_form);
			
			//liste des types de données
			$datatype_list=array("small_text"=>"Texte","text"=>"Texte large","integer"=>"Entier","date"=>"Date","datetime"=>"Date/Heure","float"=>"Nombre à virgule");
			$t_list="<select name='datatype'>\n";
			reset($datatype_list);
			foreach ($datatype_list as $key=>$val){
				$t_list.="<option value='".$key."'";
				if ($datatype==$key) $t_list.=" selected";
				$t_list.=">".htmlentities($val,ENT_QUOTES, $charset)."</option>\n";
			}
			$t_list.="</select>\n";
			$stat_view_addcol_form=str_replace("!!datatype!!",$t_list,$stat_view_addcol_form);
			
		}
		
		return $stat_view_addcol_form;
	}
	
	/**
	 * On insere ou enregistre une colonne
	 */
	function save_col($id_col='', $col_name='',$expr_col='',$expr_filtre='', $vue_id=''){
		global $datatype;
		
		if((!$id_col) && $vue_id){
			$req_ordre = "select max(ordre) from statopac_vues_col where num_vue='".addslashes($vue_id)."'";
			$resultat = mysql_query($req_ordre);
			if($resultat) $order = mysql_result($resultat,0,0);
			else $order=0;
			$ordre = $order+1;
			$req = "INSERT INTO statopac_vues_col(nom_col,expression,filtre,num_vue, ordre,datatype) VALUES ('".$col_name."', '".$expr_col."','".$expr_filtre."','".$vue_id."','".$ordre."', '".$datatype."')";
			$resultat=mysql_query($req);
		} else {
			$rqt="select * from statopac_vues_col where nom_col='".$col_name."' and expression='".$expr_col."' and num_vue='".$vue_id."' and filtre='".$expr_filtre."' and datatype='".$datatype."'";
			$res_exist = mysql_query($rqt);
			if(mysql_num_rows($res_exist)){
				$modif=0;
			} else $modif=1;
			$req = "UPDATE statopac_vues_col SET nom_col='".$col_name."', expression='".$expr_col."', num_vue='".$vue_id."', filtre='".$expr_filtre."', datatype='".$datatype."', maj_flag=$modif  WHERE id_col='".$id_col."'";
			$resultat=mysql_query($req);
		}
	} 
	
	/**
	 * On insere ou enregistre une vue
	 */
	function save_view($vue_id='', $view_name='',$view_comment=''){
		if(!$vue_id){
			$req = "INSERT INTO statopac_vues(nom_vue,comment,date_consolidation) VALUES ('".$view_name."', '".$view_comment."',now())";
			mysql_query($req);
		} else {
			$req = "UPDATE statopac_vues SET nom_vue='".$view_name."', comment='".$view_comment."' WHERE id_vue='".$vue_id."'";
			mysql_query($req);
		}
	}
	
	/**
	 * Supprime une vue et ces colonnes associées
	 */
	function delete_view($vue_id){
		if($vue_id){
			$req="DELETE FROM statopac_vues where id_vue='".$vue_id."'";
			$resultat=mysql_query($req);
			$req="DELETE FROM statopac_vues_col where num_vue='".$vue_id."'";
			$resultat=mysql_query($req);
			$req="DELETE FROM statopac_request where num_vue='".$vue_id."'";
			$resultat=mysql_query($req);
		}
	}
	
	/**
	 * Réinitialise la vue à zéro
	 */
	function reinitialiser_view($vue_id=''){
		if($vue_id){
			$req="DELETE FROM statopac_vues_col where num_vue='".$vue_id."'";
			$resultat=mysql_query($req);
			$req="DELETE FROM statopac_request where num_vue='".$vue_id."'";
			$resultat=mysql_query($req);
			$req="DELETE FROM statopac_vue_".$vue_id;
			$resultat=mysql_query($req);
		}
	}
	
	/**
	 * Supprime une colonne
	 */
	function delete_col($id_col){
		if($id_col){
			$req="DELETE FROM statopac_vues_col where id_col='".$id_col."'";
			$resultat=mysql_query($req);
		}
	}

	/**
	 * Changer l'ordre dans la liste en montant un élément
	 */
	function monter_element($col_id=''){
		$requete="select ordre from statopac_vues_col where id_col='".$col_id."'";
		$resultat=mysql_query($requete);
		$ordre=mysql_result($resultat,0,0);
		$requete="select max(ordre) as ordre from statopac_vues_col where ordre<".addslashes($ordre);
		$resultat=mysql_query($requete);
		$ordre_max=@mysql_result($resultat,0,0);
		if ($ordre_max) {
			$requete="select id_col from statopac_vues_col where ordre='".addslashes($ordre_max)."' limit 1";
			$resultat=mysql_query($requete);
			$idcol_max=mysql_result($resultat,0,0);
			$requete="update statopac_vues_col set ordre='".addslashes($ordre_max)."' where id_col='".$col_id."'";
			mysql_query($requete); 
			$requete="update statopac_vues_col set ordre='".addslashes($ordre)."' where id_col='".addslashes($idcol_max)."'";
			mysql_query($requete);
		}
	}
	
	/**
	 * Changer l'ordre dans la liste en descendant un élément
	 */
	function descendre_element($col_id=''){
		$requete="select ordre from statopac_vues_col where id_col='".$col_id."'";
		$resultat=mysql_query($requete);
		$ordre=mysql_result($resultat,0,0);
		$requete="select min(ordre) as ordre from statopac_vues_col where ordre>".addslashes($ordre);
		$resultat=mysql_query($requete);
		$ordre_min=@mysql_result($resultat,0,0);
		if ($ordre_min) {
			$requete="select id_col from statopac_vues_col where ordre='".addslashes($ordre_min)."' limit 1";
			$resultat=mysql_query($requete);
			$idcol_min=mysql_result($resultat,0,0);
			$requete="update statopac_vues_col set ordre='".addslashes($ordre_min)."'  where id_col='".$col_id."'";
			mysql_query($requete);
			$requete="update statopac_vues_col set ordre='".addslashes($ordre)."'  where id_col='".addslashes($idcol_min)."'";
			mysql_query($requete);
		}
	}
	
	
	
	
	
	/**
	 * Verification de la presence et de la syntaxe des parametres de la requete
	 * retourne true si OK, le nom du parametre entre parentheses sinon
	 */
	function check_param($requete) {
		$query_parameters=array();
		//S'il y a des termes !!*!! dans la requête alors il y a des paramètres
		if (preg_match_all("|!!(.*)!!|U",$requete,$query_parameters)) {
			for ($i=0; $i<count($query_parameters[1]); $i++) {
				if (!preg_match("/^[A-Za-z][A-Za-z0-9_]*$/",$query_parameters[1][$i])) {
					return "(".$query_parameters[1][$i].")";
				}
			}
		}
		return true;
	}
	
	
}
?>