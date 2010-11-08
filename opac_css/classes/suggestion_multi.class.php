<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: suggestion_multi.class.php,v 1.10 2009-12-28 15:34:12 kantin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($include_path."/templates/suggestion_multi.tpl.php");
require_once($base_path."/classes/notice.class.php");
require_once($base_path."/classes/suggestions_origine.class.php");
require_once($base_path."/classes/notice_affichage_unimarc.class.php");
require_once($base_path."/classes/suggestions.class.php");
require_once($base_path."/classes/suggestions_unimarc.class.php");

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
		global $dbh, $multi_sug_form,$charset, $msg, $sug_src;
		
		//On charge la liste des sources
		$req = "select * from suggestions_source order by libelle_source";
			$res= mysql_query($req,$dbh);
		
		$option = "<option value='0' selected>".htmlentities($msg['empr_sugg_no_src'],ENT_QUOTES,$charset)."</option>";
		while(($src=mysql_fetch_object($res))){
			$option .= "<option value='".$src->id_source."' ".($sug_src==$src->id_source ? 'selected' : '').">".htmlentities($src->libelle_source,ENT_QUOTES,$charset)."</option>";
		}
		
		if(!$this->liste_sugg){
			$nb_lignes=1;
			$multi_sug_form = str_replace('!!max_ligne!!',$nb_lignes,$multi_sug_form);
			$ligne = "
				<tr id='sugg_0'>
					<td><input type='texte' name='sugg_tit_0' id='sugg_tit_0' value=''  disabled /></td>
					<td><input type='texte' name='sugg_aut_0' id='sugg_aut_0' value='' disabled /></td>
					<td><input type='texte' name='sugg_edi_0' id='sugg_edi_0' value='' disabled /></td>
					<td><input type='texte' name='sugg_code_0' id='sugg_code_0' value='' disabled /></td>
					<td><input type='texte' name='sugg_prix_0' id='sugg_prix_0' value='' disabled /></td>
					<td><input type='texte' name='sugg_url_0' id='sugg_url_0' value='' disabled /></td>
					<td><textarea name='sugg_com_0' id='sugg_com_0' disabled ></textarea></td>
					<td><input type='texte' name='sugg_date_0' id='sugg_date_0' value='' disabled /></td>
					<td>
						<select id='sugg_src_0' name='sugg_src_0' disabled >
							$option
						</select>
					</td>
					<td><input type='texte' name='sugg_qte_0' id='sugg_qte_0' value='1' disabled /></td>
					<td id='act_btn_0'><input type='button' name='add_line_0' id='add_line_0' value='+' onclick=\"add_line(0);\"/></td>
				</tr>";
		} else {
			$liste = $this->liste_sugg;
			$this->ori_unimarc = microtime()."_unimulti";
			for($i=0;$i<count($liste);$i++){
				$ext=false;
				$notice_id=0;
				if(strpos($liste[$i],'es') !== false){
					$id_noti = str_replace('es','',$liste[$i]);			
					$entrepots_localisations = array();
					$entrepots_localisations_sql = "SELECT * FROM entrepots_localisations ORDER BY loc_visible DESC";
					$res = mysql_query($entrepots_localisations_sql);
					while ($row = mysql_fetch_array($res)) {
						$entrepots_localisations[$row["loc_code"]] = array("libelle" => $row["loc_libelle"], "visible" => $row["loc_visible"]); 
					}
					
					//Traitement de la notice uni
					$uni = new suggestions_unimarc();
					$uni->entrepot_to_unimarc($id_noti);
					$uni->sugg_uni_num_notice = $id_noti;
					$uni->sugg_uni_origine = $this->ori_unimarc;
					$uni->save();
					
					$aff = new notice_affichage_unimarc($id_noti,'', 0,0, $entrepots_localisations);
					$aff->fetch_data();
					$titre = $aff->notice->tit1;
					$auteur = ($aff->auteurs_principaux ? $aff->auteurs_principaux : $aff->auteurs_tous);
					$editeur =$aff->publishers[0]["name"]; 
					$code = $aff->code;
					$prix =$aff->prix;
					$date=$aff->year;
					$url=($aff->notice->lien ? $aff->notice->lien : ($aff->notice->eformat ? $aff->notice->eformat :($aff->notice->lien_texte ? $aff->notice->lien_texte : '')));
					$ext=true;
				} else{					
					$requete = "SELECT tit1 as titre, ed_name as editeur, CONCAT(author_name,' ',author_rejete) as auteur, prix, code, lien, year 
					FROM notices LEFT JOIN responsability ON responsability_notice=notice_id 
					LEFT JOIN authors ON responsability_author=author_id LEFT JOIN publishers ON ed1_id=ed_id
					WHERE notice_id=".$liste[$i];
					$result = mysql_query($requete,$dbh);
					$sug = mysql_fetch_object($result);
					$titre = $sug->titre;
					$auteur = $sug->auteur;
					$editeur =$sug->editeur; 
					$code = $sug->code;
					$prix =$sug->prix;
					$date =$sug->year;
					$url = $sug->lien;
					$notice_id = $liste[$i];
				}
				
				$ligne .= "<tr id='sugg_$i'>
					<td><input type='texte' name='sugg_tit_$i' id='sugg_tit_$i' value='".htmlentities($titre,ENT_QUOTES,$charset)."' /></td>
					<td><input type='texte' name='sugg_aut_$i' id='sugg_aut_$i' value='".htmlentities($auteur,ENT_QUOTES,$charset)."' /></td>
					<td><input type='texte' name='sugg_edi_$i' id='sugg_edi_$i' value='".htmlentities($editeur,ENT_QUOTES,$charset)."' /></td>
					<td><input type='texte' name='sugg_code_$i' id='sugg_code_$i' value='".htmlentities($code,ENT_QUOTES,$charset)."' /></td>
					<td><input type='texte' name='sugg_prix_$i' id='sugg_prix_$i' value='".$prix."' /></td>
					<td><input type='texte' name='sugg_url_$i' id='sugg_url_$i' value='".htmlentities($url,ENT_QUOTES,$charset)."' /></td>
					<td><textarea name='sugg_com_$i' id='sugg_com_$i'></textarea></td>
					<td><input type='texte' name='sugg_date_$i' id='sugg_date_$i' value='".htmlentities($date,ENT_QUOTES,$charset)."' /></td>
					<td>
						<select id='sugg_src_$i' name='sugg_src_$i'>
							$option
						</select>
					</td>
					<td><input type='texte' name='sugg_qte_$i' id='sugg_qte_$i' value='1' /></td>";
				
				if($i==count($liste)-1){	
					$ligne .= "<td id='act_btn_$i'><input type='button' name='add_line_$i' id='add_line_$i' value='+' onclick=\"add_line($i);\"/></td>";
				} else { 	
					$ligne .= "<td id='act_btn_$i'><input type='button' name='del_line_$i' id='del_line_$i' value='X' onclick=\"del_line($i);\"/></td>";
				}
				if($ext) $ligne .= "<input type='hidden' name='id_unimarc_$i' id='id_unimarc_$i' value='".$uni->sugg_uni_id."'/> "; 
				if($notice_id) $ligne .= "<input type='hidden' name='id_notice_$i' id='id_notice_$i' value='".$notice_id."' /> "; 
				
				$ligne .= "</tr>";				
			}
			$multi_sug_form = str_replace('!!max_ligne!!',$i,$multi_sug_form);
		}
		$multi_sug_form = str_replace('!!ligne!!',$ligne,$multi_sug_form);
		
		return $multi_sug_form;		
	}
	
	/*
	 * Enregistrement d'une suggestion multiple
	 */
	function save(){
		
		global $dbh, $max_nblignes, $msg, $id_empr, $empr_location, $num_categ;
		
		for($i=0;$i<$max_nblignes;$i++){		
				$tit = "sugg_tit_".$i;	$aut = "sugg_aut_".$i;	$edi = "sugg_edi_".$i;
				$code = "sugg_code_".$i; $prix = "sugg_prix_".$i; $com = "sugg_com_".$i;
				$url = "sugg_url_".$i; $qte = "sugg_qte_".$i; $src = "sugg_src_".$i;
				$date = "sugg_date_".$i; $unimarc = "id_unimarc_".$i; $notice =  "id_notice_".$i;
				global $sug_tr, $$tit, $$aut, $$edi, $$code, $$com, $$prix, $$url, $$qte, $$src, $$date, $$unimarc, $$notice;
				
				if(isset($$tit)){
					if(!is_numeric($$qte)){
						 print "<strong>".$msg[empr_sugg_qte_error]."<strong>";
						 return;
					} else if(!$$tit || (!$$edi && !$$aut && !$$code)) {
						 print "<strong>".str_replace('\n','<br />',$msg['empr_sugg_ko'])."<strong>";
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
								sugg_location='".$empr_location."',
								num_categ='".$num_categ."'
								";
						if($$unimarc){
							$uni = new suggestions_unimarc($$unimarc);
							$req .= ", notice_unimarc ='".addslashes($uni->sugg_uni_notice)."'";							
						}
						if($$notice){
							$req .= ", num_notice ='".$$notice."'";
						}
						mysql_query($req,$dbh);	
						

						$sug_orig = new suggestions_origine($id_empr, mysql_insert_id());
						$sug_orig->type_origine = 1;
						$sug_orig->save();
					}
				}
		}
		if($uni) $uni->delete();
		print $msg['empr_sugg_ok'];
	}
	
	
}
?>