<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: suggestion_multi.class.php,v 1.12 2010-04-02 09:39:37 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($include_path."/templates/suggestion_multi.tpl.php");
require_once($class_path."/suggestions_origine.class.php");
require_once($class_path."/suggestions.class.php");
require_once($class_path."/suggestions_categ.class.php");
require_once($class_path."/i_2709.class.php");
require_once($class_path."/suggestions_unimarc.class.php");
require_once($class_path."/docs_location.class.php");

class suggestion_multi{
	
	var $liste_sugg=array();
	
	/**
	 * Constructeur
	 */
	function suggestion_multi($tableau_sugg=array()){
		$this->liste_sugg = $tableau_sugg;
	}
	
	/**
	 * Formulaire de saisie des suggestions multiples
	 */
	function display_form(){
		global $dbh, $multi_sug_form,$charset,$msg, $src_liste;
		global $PMBusernom;
		global $PMBuserprenom;
		global $origine_id, $type_origine, $acquisition_sugg_categ, $acquisition_sugg_localises;
		
		$req = "select * from suggestions_source order by libelle_source";
		$res= mysql_query($req,$dbh);
		$option = "<option value='0' selected>".htmlentities($msg['acquisition_sugg_no_src'],ENT_QUOTES,$charset)."</option>";
		$select="";
		while(($src=mysql_fetch_object($res))){
			$select = ($src_liste == $src->id_source ? "selected" : "");
			$option .= "<option value='".$src->id_source."' $select >".htmlentities($src->libelle_source,ENT_QUOTES,$charset)."</option>";
		}	
		if(!$this->liste_sugg){
			$multi_sug_form = str_replace("!!max_lignes!!","1",$multi_sug_form);	
			$ligne = "<tr id='sugg_0'>
					<td><input type='texte' name='sugg_tit_0' id='sugg_tit_0' value='' disabled /></td>
					<td><input type='texte' name='sugg_aut_0' id='sugg_aut_0' value=''disabled  /></td>
					<td><input type='texte' name='sugg_edi_0' id='sugg_edi_0' value='' disabled /></td>
					<td><input type='texte' name='sugg_code_0' id='sugg_code_0' value='' disabled /></td>
					<td><input type='texte' name='sugg_prix_0' id='sugg_prix_0' value='' disabled /></td>
					<td><input type='texte' name='sugg_url_0' id='sugg_url_0' value='' disabled /></td>
					<td><textarea name='sugg_com_0' id='sugg_com_0' disabled ></textarea></td>
					<td><input type='texte' name='sugg_date_0' id='sugg_date_0' value='' disabled /></td>
					<td>
						<select id='sugg_src_0' name='sugg_src_0' disabled>
							$option
						</select>
					</td>
					<td><input type='texte' name='sugg_qte_0' id='sugg_qte_0' value='1' disabled /></td>
					<td id='act_btn_0'><input type='button' class='bouton' name='add_line_0' id='add_line_0' value='+' onclick=\"add_line(0);\"/></td>
				</tr>";
		} else {
			$multi_sug_form = str_replace("!!max_lignes!!",count($this->liste_sugg),$multi_sug_form);	
			for($i=0;$i<=count($this->liste_sugg);$i++){
				if($this->liste_sugg[$i]){
					$ligne .= "<tr id='sugg_$i'>
						<td><input type='texte' name='sugg_tit_$i' id='sugg_tit_$i' value='".htmlentities($this->liste_sugg[$i]['titre'],ENT_QUOTES,$charset)."' /></td>
						<td><input type='texte' name='sugg_aut_$i' id='sugg_aut_$i' value='".htmlentities($this->liste_sugg[$i]['auteur'],ENT_QUOTES,$charset)."' /></td>
						<td><input type='texte' name='sugg_edi_$i' id='sugg_edi_$i' value='".htmlentities($this->liste_sugg[$i]['editeur'],ENT_QUOTES,$charset)."' /></td>
						<td><input type='texte' name='sugg_code_$i' id='sugg_code_$i' value='".htmlentities($this->liste_sugg[$i]['code'],ENT_QUOTES,$charset)."' /></td>
						<td><input type='texte' name='sugg_prix_$i' id='sugg_prix_$i' value='".$this->liste_sugg[$i]['prix']."' /></td>
						<td><input type='texte' name='sugg_url_$i' id='sugg_url_$i' value='".htmlentities($this->liste_sugg[$i]['url'],ENT_QUOTES,$charset)."' /></td>
						<td><textarea name='sugg_com_$i' id='sugg_com_$i'></textarea></td>
						<td><input type='texte' name='sugg_date_$i' id='sugg_date_$i' value='".htmlentities($this->liste_sugg[$i]['date'],ENT_QUOTES,$charset)."' /></td>
						<td>
							<select id='sugg_src_$i' name='sugg_src_$i'>
								$option
							</select>
						</td>
						<td><input type='texte' name='sugg_qte_$i' id='sugg_qte_$i' value='1' /></td>";
					
					if($i==count($this->liste_sugg)){	
						$ligne .= "<td id='act_btn_$i'><input type='button' class='bouton' name='add_line_$i' id='add_line_$i' value='+' onclick=\"add_line($i);\"/></td>";
					} else { 	
						$ligne .= "<td id='act_btn_$i'><input type='button' class='bouton' name='del_line_$i' id='del_line_$i' value='X' onclick=\"del_line($i);\"/></td>";
					}
					
					if($this->liste_sugg[$i]['id_uni']) 
						$ligne .= "<input type='hidden' name='id_unimarc_$i' id='id_unimarc_$i' value='".$this->liste_sugg[$i]['id_uni']."'/> ";
					
					$ligne .= "</tr>";
				}					
			}
		}
		$multi_sug_form = str_replace('!!ligne!!',$ligne,$multi_sug_form);
		
		if(!$origine_id){
			$multi_sug_form = str_replace('!!id_user!!',SESSuserid,$multi_sug_form);
			$multi_sug_form = str_replace('!!type_user!!',0,$multi_sug_form);				
			$multi_sug_form = str_replace('!!user_txt!!',$PMBusernom.", ".$PMBuserprenom,$multi_sug_form);
		} else  {
			$multi_sug_form = str_replace('!!id_user!!',$origine_id,$multi_sug_form);
			$multi_sug_form = str_replace('!!type_user!!',$type_origine,$multi_sug_form);
			
			if($type_origine)
				$req = "select concat(empr_prenom,' ',empr_nom) as nom from empr where id_empr='".$origine_id."'";
			 else $req = "select concat(prenom,' ',nom) as nom from users where userid='".$origine_id."'";
			$res = mysql_query($req,$dbh);
			$empr = mysql_fetch_object($res); 
			$multi_sug_form = str_replace('!!user_txt!!',$empr->nom,$multi_sug_form);
		}
		
		//Selecteur Affecter a une categorie
		if ($acquisition_sugg_categ == '1' ) { 		
			$sel_categ = "<label class='etiquette' >".htmlentities($msg['acquisition_sug_sel_categ'],ENT_QUOTES, $charset)."</label>&nbsp;";		
			$tab_categ = suggestions_categ::getCategList();
			$sel_categ.= "<select class='saisie-25em' id='num_categ' name='num_categ'>";
			$sel_categ.= "<option value='-1'>".htmlentities($msg['acquisition_sug_tous'],ENT_QUOTES, $charset)."</option>";
			foreach($tab_categ as $id_categ=>$lib_categ){
				$sel_categ.= "<option value='".$id_categ."' > ";
				$sel_categ.= htmlentities($lib_categ,ENT_QUOTES, $charset)."</option>";
			}
			$sel_categ.= "</select>";
		} else {
			$sel_categ = "";
		}
		//Selecteur de localisation
		if ($acquisition_sugg_localises) {
			$list_locs .= "<label class='etiquette' >".htmlentities($msg['acquisition_sug_sel_localisation'],ENT_QUOTES, $charset)."</label>&nbsp;";
			if ($sugg_location_id) $temp_location=$sugg_location_id;
			else $temp_location=0;
			$locs=new docs_location();
			$list_locs.=$locs->gen_combo_box_sugg($temp_location,1);
		} else {
			$list_locs='';
		}
				
		$multi_sug_form = str_replace('!!categorie!!',$sel_categ,$multi_sug_form);
		$multi_sug_form = str_replace('!!localisation!!',$list_locs,$multi_sug_form);
		return $multi_sug_form;		
	}
	
	/**
	 * Enregistrement de la suggestion multiple
	 */
	function save(){
		
		global $dbh, $max_nblignes, $msg, $id_user, $type_user, $sugg_location_id, $num_categ;
		
		for($i=0;$i<$max_nblignes;$i++){
				$tit = "sugg_tit_".$i;	$aut = "sugg_aut_".$i;	$edi = "sugg_edi_".$i;
				$code = "sugg_code_".$i; $prix = "sugg_prix_".$i; $com = "sugg_com_".$i;
				$url = "sugg_url_".$i; $qte = "sugg_qte_".$i; $src = "sugg_src_".$i;
				$date = "sugg_date_".$i; $unimarc = "id_unimarc_".$i;
				global $$tit, $$aut, $$edi, $$code, $$com, $$prix, $$url, $$qte, $$src, $$date, $$unimarc;
				
				if(isset($$tit)){
					if(!is_numeric($$qte)){
						 print "<strong>".$msg['acquisition_sugg_qte_error']."</strong>";
						 return;
					} else if(!$$tit || (!$$edi && !$$aut && !$$code)) {
						 print "<strong>".str_replace('\n','<br />',$msg['acquisition_sug_ko'])."</strong>";
						 return;
					} else if(!suggestions::exists($id_user,$$tit,$$aut,$$edi,$$code)) {						
						$req="insert into suggestions set 
								titre='".$$tit."',
								auteur='".$$aut."',
								editeur='".$$edi."',
								code='".$$code."',
								prix='".$$prix."',
								commentaires='".$$com."',
								url_suggestion='".$$url."',
								nb='".$$qte."',
								sugg_source='".$$src."',
								statut=1,
								date_publication='".$$date."',
								date_creation='".date("Y-m-d")."',
								num_categ='".$num_categ."',
								sugg_location='".$sugg_location_id."'
								";
						
						if($$unimarc){
							$uni = new suggestions_unimarc($$unimarc);
							$req .= ", notice_unimarc ='".addslashes($uni->sugg_uni_notice)."'";							
						} 
						mysql_query($req,$dbh);	
						
						$sug_orig = new suggestions_origine($id_user, mysql_insert_id());
						$sug_orig->type_origine = $type_user;
						$sug_orig->save();
					}				
				}
		}
		if (is_object($uni)) $uni->delete();
		print "<b>".$msg['acquisition_sugg_ok']."</b>";
	}
	
	function traite_notice($notice_iso2709,$n_notice) {
		$notice=new iso2709_notices($notice_iso2709);
		$n_notice--;
		if (!$notice->error) {
			$this->liste_sugg[$n_notice]['code'] = ($notice->fields['010'][0]['a'][0] ? $notice->fields['010'][0]['a'][0] : $notice->fields['011'][0]['a'][0] );
			$this->liste_sugg[$n_notice]['prix'] = $notice->fields['010'][0]['d'][0];
			$this->liste_sugg[$n_notice]['titre'] = $notice->fields['200'][0]['a'][0];
			$this->liste_sugg[$n_notice]['editeur'] = $notice->fields['210'][0]['a'][0];
			$this->liste_sugg[$n_notice]['date']= $notice->fields['210'][0]['d'][0];
			$this->liste_sugg[$n_notice]['auteur'] = ( $notice->fields['700'][0]['a'][0] ? $notice->fields['700'][0]['a'][0] : ($notice->fields['710'][0]['a'][0] ? $notice->fields['710'][0]['a'][0] :  $notice->fields['701'][0]['a'][0].", ".$notice->fields['701'][0]['b'][0])) ;
			$this->liste_sugg[$n_notice]['url'] = $notice->fields['856'][0]['u'][0];	
			
			//Enregistrement de la suggestion unimarc
			$uni = new suggestions_unimarc();
			$uni->sugg_uni_notice = $notice_iso2709;
			$uni->sugg_uni_num_notice = $n_notice;
			$uni->sugg_uni_origine = $this->ori_unimarc;
			$uni->save();
			$this->liste_sugg[$n_notice]['id_uni'] = $uni->sugg_uni_id;
		}
	}
	
	function create_table_from_uni() {
		global $charset,$file_in,$suffix;
		
		//Lecture des notices
		if(!$suffix)
			$fp=@fopen("temp/$file_in","r");
		else 
			$fp=@fopen("temp/$file_in.$suffix~","r");
		if ($fp) {
			$n=1;
			$car=0x1d;
			$i=false;
			$notice="";
			$notices="";
			$this->ori_unimarc = microtime()."_unimulti";
			while (!feof($fp)) {
				$notices.=fread($fp,4096);
				$i=strpos($notices,$car);
				while ($i!==false) {
					$notice=substr($notices,0,$i+1);
					$this->traite_notice($notice,$n);
					$n++;
					$notices=substr($notices,$i+1);
					$i=strpos($notices,$car);
				}
			}
			if ($notices!="") {
				$notice=$notices;
				$this->traite_notice($notice);
				$n++;
			}	
		}
		fclose($fp);
		if(!$suffix)
			unlink("temp/$file_in");
		else 
			unlink("temp/$file_in.$suffix~");
	}
}
?>