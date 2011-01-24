<?php
// +-------------------------------------------------+
// | 2002-2007 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: abts_modeles.class.php,v 1.31 2010-11-10 10:03:38 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once($include_path."/templates/abts_modeles.tpl.php");
require_once($class_path."/serial_display.class.php");
require_once($include_path."/abts_func.inc.php");

class abts_modele {
	var $modele_id; //Numéro du modèle
	var $modele_name; //Nom du modèle
	var $num_notice; //numéro de la notice liée
	var $num_periodicite; //Identifiant de la périodicité
	var $duree_abonnement; //Durée de l'abonnement
	var $date_debut; //Date de début de validité du modèle
	var $date_fin; //Date de fin de validité du modèle
	var $days; //Jours de la semaine exclus
	var $day_month; //Jours du mois exclus
	var $week_month; //Semaine du mois exclue
	var $week_year; //Semaine de l'année exclue
	var $month_year; //Mois dans l'année exclu
	var $error; //Erreur
	var $error_message; //Message d'erreur
	
	function abts_modele($modele_id="") {
		if ($modele_id) {
			$requete="select * from abts_modeles where modele_id=".$modele_id;
			$resultat=mysql_query($requete);
			if (mysql_num_rows($resultat)) {
				$r=mysql_fetch_object($resultat);
				$this->modele_id=$r->modele_id;
				$this->modele_name=$r->modele_name;
				$this->num_notice=$r->num_notice;
				$this->num_periodicite=$r->num_periodicite;
				$this->duree_abonnement=$r->duree_abonnement;
				$this->date_debut=$r->date_debut;
				$this->date_fin=$r->date_fin;
				$this->days=$r->days;
				$this->day_month=$r->day_month;
				$this->week_month=$r->week_month;
				$this->week_year=$r->week_year;
				$this->month_year=$r->month_year;			
				
				$this->num_cycle = $r->num_cycle;				
				$this->num_combien = $r->num_combien;
			
				$this->num_increment = $r->num_increment;
				$this->num_date_unite = $r->num_date_unite;
				$this->num_increment_date = $r->num_increment_date;
								
				$this->num_depart = $r->num_depart;
				$this->vol_actif = $r->vol_actif;
				$this->vol_increment = $r->vol_increment;
				$this->vol_date_unite = $r->vol_date_unite;
				$this->vol_increment_numero = $r->vol_increment_numero;
				$this->vol_increment_date = $r->vol_increment_date;
				$this->vol_cycle = $r->vol_cycle;
				$this->vol_combien = $r->vol_combien;
				$this->vol_depart = $r->vol_depart;
				$this->tom_actif = $r->tom_actif;	
				$this->tom_increment = $r->tom_increment;
				$this->tom_date_unite = $r->tom_date_unite;
				$this->tom_increment_numero = $r->tom_increment_numero;
				$this->tom_increment_date = $r->tom_increment_date;
				$this->tom_cycle = $r->tom_cycle;
				$this->tom_combien = $r->tom_combien;
				$this->tom_depart = $r->tom_depart;
				$this->format_aff = $r->format_aff;
				$this->format_periode = $r->format_periode;
			} else {
				$this->error=true;
				$this->error_message="Le modèle demandé n'existe pas";
			}
		}
	}
	
	function getData() {
	if ($this->modele_id) {
			$requete="select * from abts_modeles where modele_id=".$this->modele_id;
			$resultat=mysql_query($requete);
			if (mysql_num_rows($resultat)) {
				$r=mysql_fetch_object($resultat);				
				$this->modele_name=$r->modele_name;
				$this->num_notice=$r->num_notice;
				$this->num_periodicite=$r->num_periodicite;
				$this->duree_abonnement=$r->duree_abonnement;
				$this->date_debut=$r->date_debut;
				$this->date_fin=$r->date_fin;
				$this->days=$r->days;
				$this->day_month=$r->day_month;
				$this->week_month=$r->week_month;
				$this->week_year=$r->week_year;
				$this->month_year=$r->month_year;			
				
				$this->num_cycle = $r->num_cycle;				
				$this->num_combien = $r->num_combien;
				$this->num_increment = $r->num_increment;
				$this->num_date_unite = $r->num_date_unite;
				$this->num_increment_date = $r->num_increment_date;
					
				$this->num_depart = $r->num_depart;
				$this->vol_actif = $r->vol_actif;
				$this->vol_increment = $r->vol_increment;
				$this->vol_date_unite = $r->vol_date_unite;
				$this->vol_increment_numero = $r->vol_increment_numero;
				$this->vol_increment_date = $r->vol_increment_date;
				$this->vol_cycle = $r->vol_cycle;
				$this->vol_combien = $r->vol_combien;
				$this->vol_depart = $r->vol_depart;
				$this->tom_actif = $r->tom_actif;	
				$this->tom_increment = $r->tom_increment;
				$this->tom_date_unite = $r->tom_date_unite;
				$this->tom_increment_numero = $r->tom_increment_numero;
				$this->tom_increment_date = $r->tom_increment_date;
				$this->tom_cycle = $r->tom_cycle;
				$this->tom_combien = $r->tom_combien;
				$this->tom_depart = $r->tom_depart;
				$this->format_aff = $r->format_aff;
				$this->format_periode = $r->format_periode;
				
			} else {
				$this->error=true;
				$this->error_message="Le modèle demandé n'existe pas";
			}
		}
	}
	
	function set_perio($num_notice) {
		$this->num_notice=0;
		$requete="select niveau_biblio from notices where notice_id=".$num_notice;
		$resultat=mysql_query($requete);
		if (mysql_num_rows($resultat)) {
			if (mysql_result($resultat,0,0)=="s")
				$this->num_notice=$num_notice;
		} else {
			$this->error=true;
			$this->error_message="La notice liée n'existe pas ou n'est pas un périodique";
		}
	}
	
	function show_modele() {
		global $modele_view,$serial_id;
		$perio=new serial_display($this->num_notice,1);
		$r=$modele_view;
		$r=str_replace("!!view_id_modele!!","catalog.php?categ=serials&sub=modele&serial_id=$serial_id&modele_id=$this->modele_id",$r);
		$r=str_replace("!!id_modele!!",$this->modele_id,$r);
		$r=str_replace("!!modele_header!!",$this->modele_name,$r);
		
		$r=str_replace("!!num_periodicite!!",sql_value("SELECT libelle from abts_periodicites where periodicite_id='".$this->num_periodicite."'"),$r);
		$r=str_replace("!!duree_abonnement!!",$this->duree_abonnement,$r);
		$r=str_replace("!!date_debut!!",format_date($this->date_debut),$r);
		$r=str_replace("!!date_fin!!",format_date($this->date_fin),$r);
													
		$r=str_replace("!!nombre_de_series!!",sql_value("select sum(nombre_recu) from abts_grille_modele where num_modele='$this->modele_id' and type_serie ='1'"),$r);
		$r=str_replace("!!nombre_de_horsseries!!",sql_value("select sum(nombre_recu) from abts_grille_modele where num_modele='$this->modele_id' and type_serie ='2'"),$r);
			
		return $r;
	}
	
	function show_form() {
		global $modele_form;
		global $serial_header;
		global $msg;
		global $charset;
		global $tpl_del_bouton,$tpl_copy_bouton;
		
		$r=$serial_header.$modele_form;
		
		if (!$this->modele_id) {
			$r=str_replace('!!page_title!!', $msg[4000].$msg[1003].$msg["abts_modeles_add_title"], $r);
			$r=str_replace('!!libelle_form!!', $msg["abts_modeles_add_title"], $r);
			// Valeur par défaut
			$this->duree_abonnement= 12;		
			$this->num_cycle = 0;
			$this->num_combien = 12;
			
			$this->num_increment = 0;
			$this->num_date_unite = 1;
			$this->num_increment_date = 12;
				
			$this->num_depart = 1;
			$this->vol_actif = 0;
			$this->vol_increment = 0;
			$this->vol_date_unite = 1; //mois
			$this->vol_increment_numero = 12;
			$this->vol_increment_date = 12;
			$this->vol_cycle = 0;
			$this->vol_combien = 1;
			$this->vol_depart = 1;
			$this->tom_actif = 0;	
			$this->tom_increment = 10;
			$this->tom_date_unite = 2;
			$this->tom_increment_numero = 1;
			$this->tom_increment_date = 1;
			$this->tom_cycle = 0;
			$this->tom_combien = 1;
			$this->tom_depart = 1;
			$this->format_aff = "N°\$NUM;";
			$this->format_periode = "#date(\$DATE;);";
		} else {
			$r=str_replace('!!page_title!!', $msg[4000].$msg[1003].$msg["abts_modeles_modify_title"], $r);
			$r=str_replace('!!libelle_form!!', $msg["abts_modeles_modify_title"], $r);
		}
		//Construction du formulaire
		if ($this->modele_id){
			$del_bouton=$tpl_del_bouton; 
			$copy_bouton=$tpl_copy_bouton; 
		}else{ 
			$copy_bouton=$del_bouton="";
		}	
		$this->getData();
		$r=str_replace("!!del_button!!",$del_bouton,$r);
		$r=str_replace("!!copy_bouton!!",$copy_bouton,$r);
		
		//Remplacement des valeurs
		$r=str_replace("!!modele_id!!",htmlentities($this->modele_id,ENT_QUOTES,$charset),$r);
		$r=str_replace("!!modele_name!!",htmlentities($this->modele_name,ENT_QUOTES,$charset),$r);
		
		//Notice mère
		$perio=new serial_display($this->num_notice,1);
		$r=str_replace("!!num_notice_libelle!!",$perio->header,$r);
		$r=str_replace("!!num_notice!!",$this->num_notice,$r);
		
		//Périodicité
		$requete="select periodicite_id,libelle from abts_periodicites";
		$resultat=mysql_query($requete);
		$liste_perio="<select name='num_periodicite'>\n<option value='0'>".$msg['abonnements_periodicite_manuel']."</option>\n";
		while ($rp=mysql_fetch_object($resultat)) {
			$liste_perio.="<option value='".$rp->periodicite_id."' ".($this->num_periodicite==$rp->periodicite_id?"selected":"").">".htmlentities($rp->libelle,ENT_QUOTES,$charset)."</option>\n";
		}
		$liste_perio.="</select>";
		$r=str_replace("!!num_periodicite!!",$liste_perio,$r);
		
		//Durée d'abonnement
		$r=str_replace("!!duree_abonnement!!",$this->duree_abonnement,$r);
		
		//Date de début
		if (!$this->date_debut || $this->date_debut == "0000-00-00") $date_debut=date("Ymd",time()); else $date_debut=$this->date_debut;
		
		$r=str_replace("!!date_debut!!",str_replace("-","",$date_debut),$r);
		$r=str_replace("!!date_debut_lib!!",formatdate($date_debut),$r);
		
		//Date de fin
		if (!$this->date_fin || $this->date_fin == "0000-00-00") $date_fin=sql_value("SELECT DATE_ADD('$date_debut', INTERVAL 1 YEAR)"); else $date_fin=$this->date_fin;
		
		$r=str_replace("!!date_fin!!",str_replace("-","",$date_fin),$r);
		$r=str_replace("!!date_fin_lib!!",format_date($date_fin),$r);
		
		//Jours de la semaine exclus
		$days="
		<table>";
		$days_t="<tr>";
		$days_v="<tr>";
		for ($i=1; $i<8; $i++) {
			$days_t.="<td>".$msg["week_days_short_".$i]."</td>";	
			$days_v.="<td><input type='checkbox' value='$i' ".(!$this->days[$i-1] && $this->modele_id ?"checked":"yes")." name='days[$i]'/></td>";
		}
		$days_v.="</tr>";
		$days_t.="</tr>";
		$days.=$days_t."\n".$days_v."
		</table>";
		$r=str_replace("!!days!!",$days,$r);
		
		//Jours du mois exclus
		$day_month="
		<table>";
		for ($j=0;$j<3;$j++) {
			$days_t="<tr>";
			$days_v="<tr>";
			for ($i=0; $i<15; $i++) {
				if (($j*(14+1)+$i+1)>31) break;
				$days_t.="<td>".($j*(14+1)+$i+1)."</td>";
				$days_v.="<td><input type='checkbox' value='".($j*(15)+$i+1)."' ".(!$this->day_month[($j*(15)+$i)] && $this->modele_id ?"checked":"yes")." name='day_month[".($j*(15)+$i+1)."]'/></td>";
			}
			$days_v.="</tr>";
			$days_t.="</tr>";
			$day_month.=$days_t."\n".$days_v."\n";
		}
		$day_month.="
		</table>";
		$r=str_replace("!!days_month!!",$day_month,$r);
		
		//Semaines dans le mois exclues
		$week_month="
		<table>";
		$days_t="<tr>";
		$days_v="<tr>";
		for ($i=1; $i<7; $i++) {
			$days_t.="<td>".$i."</td>";
			$days_v.="<td><input type='checkbox' value='$i' ".(!$this->week_month[$i-1] && $this->modele_id ?"checked":"yes")." name='week_month[$i]'/></td>";
		}
		$days_v.="</tr>";
		$days_t.="</tr>";
		$week_month.=$days_t."\n".$days_v."
		</table>";
		$r=str_replace("!!week_month!!",$week_month,$r);
		
		//Semaines dans l'année exclues
		$week_year="
		<table>";
		$nb_x=15;
		$nb=54;
		for ($j=0;$j<($nb/$nb_x);$j++) {
			$days_t="<tr>";
			$days_v="<tr>";
			for ($i=0; $i<$nb_x; $i++) {
				if (($j*($nb_x)+$i+1)>($nb)) break;
				$days_t.="<td>".($j*($nb_x)+$i+1)."</td>";
				$days_v.="<td><input type='checkbox' value='".($j*($nb_x)+$i+1)."' ".(!$this->week_year[($j*($nb_x)+$i)] && $this->modele_id ?"checked":"yes")." name='week_year[".($j*($nb_x)+$i+1)."]'/></td>";
			}
			$days_v.="</tr>";
			$days_t.="</tr>";
			$week_year.=$days_t."\n".$days_v."\n";
		}
		$week_year.="
		</table>";
		$r=str_replace("!!week_year!!",$week_year,$r);		
	
		//Mois dans l'année exclus
		$month_year="
		<table>";
		$nb_x=6;
		$nb=12;
		for ($j=0;$j<($nb/$nb_x);$j++) {
			$days_t="<tr>";
			$days_v="<tr>";
			for ($i=0; $i<$nb_x; $i++) {
				if (($j*($nb_x)+$i+1)>($nb)) break;
				$days_t.="<td>".$msg[($j*($nb_x)+$i)+1006]."</td>";
				$days_v.="<td><input type='checkbox' value='".($j*($nb_x)+$i+1)."' ".(!$this->month_year[($j*($nb_x)+$i)] && $this->modele_id ?"checked":"yes")." name='month_year[".($j*($nb_x)+$i+1)."]'/></td>";
			}
			$days_v.="</tr>";
			$days_t.="</tr>";
			$month_year.=$days_t."\n".$days_v."\n";
		}
		$month_year.="
		</table>";
		$r=str_replace("!!month_year!!",$month_year,$r);		

		//Numérotation:  Numéro
		$selected[$this->num_date_unite]= "selected='selected'";
		$str_unite="
			<select id='num_date_unite' name='num_date_unite'>
				<option value='0'$selected[0]>$msg[abonnements_periodicite_unite_jour]</option>
				<option value='1'$selected[1]>$msg[abonnements_periodicite_unite_mois]</option>
				<option value='2'$selected[2]>$msg[abonnements_periodicite_unite_annee]</option>
			</select>";
		$selected[$this->num_date_unite]= '';
		
		$num="<table>";

		$num_t="<tr>";
		$num_v="<tr>";
		$num_t.="<td>".$msg["abonnements_actif"]."</td>";
		$num_v.="<td><input type='checkbox' value='1' checked='checked'  disabled='disabled' name='facilite' id='facilite' /></td>";
		$num_t.="</tr>";
		$num_v.="</tr>";
		$num.=$num_t."\n".$num_v."\n";
			
		$num_t="<tr>";
		$num_v="<tr>";	
		$num_t.="<td>".$msg["abonnements_cyclique"]."</td>";
		$num_v.="<td><input type='checkbox' value='1' ".($this->num_cycle ?"checked":"yes")." name='num_cycle[1]' id='num_cycle'  onClick='gere_num(\"num_cycle\");'/></td>";
		$num_t.="</tr>";
		$num_v.="</tr>";
		$num.=$num_t."\n".$num_v."\n";
			
		$num_t="<tr>";
		$num_v="<tr>";
		$num_t.="<td></td>";
		$num_v.="<td></td>";
		$num_t.="<td><input type='radio' name='num_increment' id='num_increment' value='0' ".($this->num_increment==0 ?"checked":"yes").">".$msg["abonnements_incrementation_selon_numero_1"]."<input type='text' size='5' name='num_combien' id='num_combien' value='$this->num_combien'/>".$msg["abonnements_incrementation_selon_numero_2"]."</td>";
		$num_v.="<td><input type='radio' name='num_increment' id='num_increment1' value='1' ".($this->num_increment==1 ?"checked":"yes").">".$msg["abonnements_incrementation_selon_date_1"]."<input type='text' size='5' name='num_increment_date' id='num_increment_date' value='$this->num_increment_date'/>$str_unite".$msg["abonnements_incrementation_selon_date_2"]."</td>";
		$num_t.="<td>".$msg["abonnements_numero_depart"]."</td>";
		$num_v.="<td><input type='text' size='5' name='num_depart' id='num_depart' value='$this->num_depart' /></td>";		
		$num_t.="</tr>";
		$num_v.="</tr>";
		$num.=$num_t."\n".$num_v."\n";	

		$num.="</table>";
		$r=str_replace("!!numero!!",$num,$r);		
		
		//Numérotation: Volume
		$selected[$this->vol_date_unite]= "selected='selected'";
		$str_unite="
			<select id='vol_date_unite' name='vol_date_unite'>
				<option value='0'$selected[0]>$msg[abonnements_periodicite_unite_jour]</option>
				<option value='1'$selected[1]>$msg[abonnements_periodicite_unite_mois]</option>
				<option value='2'$selected[2]>$msg[abonnements_periodicite_unite_annee]</option>
			</select>";
		$selected[$this->vol_date_unite]= '';
		
		$vol="<table>";

		$vol_t="<tr>";
		$vol_v="<tr>";
		$vol_t.="<td>".$msg["abonnements_actif"]."</td>";
		$vol_v.="<td><input type='checkbox' value='1' ".($this->vol_actif && $this->modele_id ?"checked":"yes")." name='vol_actif[1]' id='vol_actif'  onClick='gere_num(\"vol_actif\");'/></td>";
		$vol_t.="</tr>";
		$vol_v.="</tr>";
		$vol.=$vol_t."\n".$vol_v."\n";

		$vol_t="<tr>";
		$vol_v="<tr>";
		$vol_t.="<td>".$msg["abonnements_incrementation"]."</td>";
		$vol_t.="</tr>";
		$vol_v.="</tr>";
		$vol.=$vol_t."\n".$vol_v."\n";

		$vol_t="<tr>";
		$vol_v="<tr>";
		$vol_t.="<td></td>";
		$vol_v.="<td></td>";
		$vol_t.="<td><input type='radio' name='vol_increment' id='vol_increment' value='0' ".($this->vol_increment==0 ?"checked":"yes").">".$msg["abonnements_incrementation_selon_numero_1"]."<input type='text' size='5' name='vol_increment_numero' id='vol_increment_numero' value='$this->vol_increment_numero'/>".$msg["abonnements_incrementation_selon_numero_2"]."</td>";
		$vol_v.="<td><input type='radio' name='vol_increment' id='vol_increment1' value='1' ".($this->vol_increment==1 ?"checked":"yes").">".$msg["abonnements_incrementation_selon_date_1"]."<input type='text' size='5' name='vol_increment_date' id='vol_increment_date' value='$this->vol_increment_date'/>$str_unite".$msg["abonnements_incrementation_selon_date_2"]."</td>";
		$vol_t.="</tr>";
		$vol_v.="</tr>";
		$vol.=$vol_t."\n".$vol_v."\n";	

		$vol_t="<tr>";
		$vol_v="<tr>";
		$vol_t.="<td>".$msg["abonnements_cyclique"]."</td>";
		$vol_v.="<td><input type='checkbox' value='1' ".($this->vol_cycle && $this->modele_id ?"checked":"yes")." name='vol_cycle[1]' id='vol_cycle'  onClick='gere_num(\"vol_cycle\");'/></td>";
		$vol_t.="<td>".$msg["abonnements_combien"]."</td>";
		$vol_v.="<td><input type='text' size='5' name='vol_combien' id='vol_combien' value='$this->vol_combien'/></td>";
		$vol_t.="<td>".$msg["abonnements_numero_depart"]."</td>";
		$vol_v.="<td><input type='text' size='5' name='vol_depart' id='vol_depart' value='$this->vol_depart'/></td>";
		$vol_t.="</tr>";
		$vol_v.="</tr>";
		$vol.=$vol_t."\n".$vol_v."\n";
		$vol.="</table>";
		$r=str_replace("!!volume!!",$vol,$r);		
			
		//Numérotation: Tome
		$selected[$this->tom_date_unite]= "selected='selected'";
		$str_unite="
			<select id='tom_date_unite' name='tom_date_unite'>
				<option value='0'$selected[0]>$msg[abonnements_periodicite_unite_jour]</option>
				<option value='1'$selected[1]>$msg[abonnements_periodicite_unite_mois]</option>
				<option value='2'$selected[2]>$msg[abonnements_periodicite_unite_annee]</option>
			</select>";

		$vol="<table>";
		$tom="<table>";

		$tom_t="<tr>";
		$tom_v="<tr>";
		$tom_t.="<td>".$msg["abonnements_actif"]."</td>";
		$tom_v.="<td><input type='checkbox' value='1' ".($this->tom_actif && $this->modele_id ?"checked":"yes")." name='tom_actif[1]' id='tom_actif'   onClick='gere_num(\"tom_actif\");'/></td>";
		$tom_t.="</tr>";
		$tom_v.="</tr>";
		$tom.=$tom_t."\n".$tom_v."\n";
		
		$tom_t="<tr>";
		$tom_v="<tr>";
		$tom_t.="<td>".$msg["abonnements_incrementation"]."</td>";
		$tom_t.="</tr>";
		$tom_v.="</tr>";
		$tom.=$tom_t."\n".$tom_v."\n";

		$tom_t="<tr>";
		$tom_v="<tr>";
		$tom_t.="<td></td>";
		$tom_v.="<td></td>";
		$tom_t.="<td><input type='radio' name='tom_increment' id='tom_increment' value='0' ".($this->tom_increment==0 ?"checked":"yes").">".$msg["abonnements_incrementation_selon_volume_1"]."<input type='text' size='5' name='tom_increment_numero' id='tom_increment_numero' value='$this->tom_increment_numero'/>".$msg["abonnements_incrementation_selon_volume_2"]."</td>";
		$tom_v.="<td><input type='radio' name='tom_increment' id='tom_increment1'  value='1' ".($this->tom_increment==1 ?"checked":"yes").">".$msg["abonnements_incrementation_selon_date_1"]."<input type='text' size='5' name='tom_increment_date' id='tom_increment_date' value='$this->tom_increment_date'/>$str_unite".$msg["abonnements_incrementation_selon_date_2"]."</td>";
		$tom_t.="</tr>";
		$tom_v.="</tr>";
		$tom.=$tom_t."\n".$tom_v."\n";	

		$tom_t="<tr>";
		$tom_v="<tr>";
		$tom_t.="<td>".$msg["abonnements_cyclique"]."</td>";
		$tom_v.="<td><input type='checkbox' value='1' ".($this->tom_cycle && $this->modele_id ?"checked":"yes")." name='tom_cycle[1]' id='tom_cycle' onClick='gere_num(\"tom_cycle\");'/></td>";
		$tom_t.="<td>".$msg["abonnements_combien"]."</td>";
		$tom_v.="<td><input type='text' size='5' name='tom_combien' id='tom_combien' value='$this->tom_combien'/></td>";
		$tom_t.="<td>".$msg["abonnements_numero_depart"]."</td>";
		$tom_v.="<td><input type='text' size='5' name='tom_depart' id='tom_depart' value='$this->tom_depart'/></td>";
		$tom_t.="</tr>";
		$tom_v.="</tr>";
		$tom.=$tom_t."\n".$tom_v."\n";

		$tom.="</table>";

		$r=str_replace("!!tome!!",$tom,$r);		
				
		$format="<table>";

		$format_t="<tr>";
		$format_v="<tr>";
		$format_t.="<td>".$msg["abonnements_format_numero"]."</td>";
		$format_v.="<td><input type='text' size='100' name='format_aff' id='format_aff' value='".htmlentities($this->format_aff,ENT_QUOTES,$charset)."'/></td>";
		$format_t.="</tr>";
		$format_v.="</tr>";
		$format.=$format_t."\n".$format_v."\n";
	
		$r=str_replace("!!format!!",$format,$r);		
	
		$format_t="<tr>";
		$format_v="<tr>";
		$format_t.="<td>".$msg["abonnements_format_periode"]."</td>";
		$format_v.="<td><input type='text' size='100' name='format_periode' id='format_periode' value='".htmlentities($this->format_periode,ENT_QUOTES,$charset)."'/></td>";
		$format_t.="</tr>";
		$format_v.="</tr>";
		$format=$format_t."\n".$format_v."\n";
		
		$format.="</table>";

		$r=str_replace("!!format_periode!!",$format,$r);		
		
		global $serial_id;
		$r=str_replace("!!action!!","./catalog.php?categ=serials&sub=modele&serial_id="."$serial_id",$r);
		$r=str_replace("!!serial_id!!",$serial_id,$r);

		
		if ($this->modele_id) {
			
			$calend="
			<script type=\"text/javascript\" src='./javascript/select.js'></script>
			<script type=\"text/javascript\" src='./javascript/ajax.js'></script>";   
					
$calend.= <<<ENDOFTEXT
			<script type="text/javascript">
			function ad_date(obj,e) {		
				if(!e) e=window.event;
				var tgt = e.target || e.srcElement; // IE doesn't use .target
				var strid = tgt.id;
				var type = tgt.tagName;
				e.cancelBubble = true;
				if (e.stopPropagation) e.stopPropagation();					
				var id_obj=document.getElementById(obj);
				var pos=findPos(id_obj);
				if(type!='A'){
					var url="./catalog/serials/modele/modele_parution_edition.php?act=change&serial_id=!!serial_id!!&modele_id=!!modele_id!!&date_parution="+obj+"&type_serie=1&numero=";	
				}
				else{
					var url="./catalog/serials/modele/modele_parution_edition.php?serial_id=!!serial_id!!&modele_id=!!modele_id!!&date_parution="+obj+"&type_serie=1&numero=";
				}
				var notice_view=document.createElement("iframe");
				notice_view.setAttribute('id','frame_periodique');
				notice_view.setAttribute('name','periodique');
				notice_view.src=url; 
				
				var att=document.getElementById("att");	
				notice_view.style.visibility="hidden";
				notice_view.style.display="block";
				notice_view=att.appendChild(notice_view);
				if(type=='A'){		
					w=notice_view.clientWidth;
					h=notice_view.clientHeight;
					notice_view.style.left=pos[0]+"px";
					notice_view.style.top=pos[1]+"px";
					notice_view.style.visibility="visible";			
					}	
			}
			function kill_frame_periodique() {
				var notice_view=document.getElementById("frame_periodique");
				notice_view.parentNode.removeChild(notice_view);	
			}
			</script>	
ENDOFTEXT;
			$calend=str_replace("!!serial_id!!",$serial_id,$calend);
			$calend=str_replace("!!modele_id!!",$this->modele_id,$calend);	
			
			$base_url="./catalog.php?categ=serials&sub=modele&serial_id="."$serial_id&modele_id=$this->modele_id";
			$base_url_mois='';	

			$calend.= "<div id='calendrier_tab' style='width:99%'>" ;
			$date = $this->date_debut;
			$calend.= "<A name='ancre_calendrier'></A>"; 
				
			$year=sql_value("SELECT YEAR('$date')");
			$cur_year=$year;
			//debut expand
			$calend.="
			<div class='row'>&nbsp;</div>
			<div id='abts_year_$year' class='notice-parent'>
				<img src='./images/minus.gif' class='img_plus' name='imEx' id='abts_year_$year"."Img' title='détail' border='0' onClick=\"expandBase('abts_year_$year', true); return false;\" hspace='3'>
				<span class='notice-heada'>
					$year
	    		</span>
			</div>
			<div id='abts_year_$year"."Child' startOpen='Yes' class='notice-child' style='margin-bottom:6px;width:94%'> ";	
						
			$i=sql_value("SELECT MONTH('$date')");	
			if($i==2 || $i==5 || $i==8 || $i==11) {
					$calend.= "<div class='row' style='padding-top: 5px'><div class='colonne3'>&nbsp;";
					$calend.= "</div>\n";		
			}
			if($i==3 || $i==6 || $i==9 || $i==12) {
					$calend.= "<div class='row' style='padding-top: 5px'><div class='colonne3'>&nbsp;";
					$calend.= "</div>\n";
					$calend.= "<div class='colonne3' style='padding-left: 3px'>&nbsp;";	
					$calend.= "</div>\n";		
			}
			do {
				$year=sql_value("SELECT YEAR('$date')");	
				if($year!=$cur_year){
					$calend.= "</div>";
					$calend.="
					<div class='row'></div>
					<div id='abts_year_$year' class='notice-parent'>
						<img src='./images/plus.gif' class='img_plus' name='imEx' id='abts_year_$year"."Img' title='détail' border='0' onClick=\"expandBase('abts_year_$year', true); return false;\" hspace='3'>
						<span class='notice-heada'>
							$year
			    		</span>
					</div>
					<div id='abts_year_$year"."Child' class='notice-child' style='margin-bottom:6px;display:none;width:94%'>
					";	
					$cur_year=$year;
				}						
				$i=sql_value("SELECT MONTH('$date')");	
				
				if ($i==1 || $i==4 || $i==7 || $i==10 ) $calend.= "<div class='row' style='padding-top: 5px'><div class='colonne3'>";
				else 
					$calend.= "<div class='colonne3' style='padding-left: 3px'>";
				$calend.= pmb_bidi(calendar_gestion(str_replace("-","",$date), 0, $base_url, $base_url_mois,0,$this->modele_id,0));
				$calend.= "</div>\n";
				if ($i==3 || $i==6 || $i==9 || $i==12 ) $calend.="</div>\n";
				
				$date=sql_value("SELECT DATE_ADD('$date', INTERVAL 1 MONTH)");
				$diff=sql_value("SELECT DATEDIFF('$date_fin','$date')");			
			}
			while($diff>=0);	
			//fin expand
			$calend.= "</div>";								
			$calend.= "</div>\n";
			$calend.="<script type='text/javascript'>parent.location.href='#ancre_calendrier';</script>";			
			$r.=$calend;
		}
		return $r;
	}
	
	function gen_date(){
		global $dbh;
		global $msg;
		global $include_path;
		if($this->modele_id) {
			$dummy = "delete FROM abts_grille_modele WHERE num_modele='$this->modele_id' ";
			mysql_query($dummy, $dbh);		
				
			$date = construitdateheuremysql($this->date_debut);	
			$date_fin = construitdateheuremysql($this->date_fin);		
			
			//Lire la périodicté
			$duree=1;
			$requete="select duree,unite from abts_periodicites where periodicite_id='$this->num_periodicite'";
			$resultat=mysql_query($requete);
			if($r=mysql_fetch_object($resultat)) {
				$duree=$r->duree;
				$unite=$r->unite;									
				do {				
					$dayofweek=sql_value("SELECT DAYOFWEEK('$date')");	//1 = Dimanche, 2 = Lundi, ... 7 = Samedi
					if($dayofweek==1)$dayofweek=8;
					$dayofweek--;
					$day=sql_value("SELECT DAYOFMONTH('$date')");	// 1 à 31
					$month=sql_value("SELECT MONTH('$date')");	//1 à 12 
					$week=sql_value("SELECT WEEK('$date',5)") + 1;//0 ... 53
					
					//calcul numero de semaine dans le mois
					$weekofmonth=($day+7-$dayofweek)/7+1;
	
					//Mois dans l'année exclu
					if($this->month_year[$month-1])
						if($this->week_year[$week-1])
							if($this->week_month[$weekofmonth-1])
								if($this->day_month[$day-1])
									if($this->days[$dayofweek-1]) {
										//c'est un jour prévu de réception	
										$requete = "INSERT INTO abts_grille_modele SET num_modele='$this->modele_id', date_parution ='$date', type_serie = '1'";
										mysql_query($requete, $dbh);
									}							
					// Calcul de la date suivante à analyser et la sortie du while					
					if($unite==0) $sql_add="INTERVAL $duree DAY";
					if($unite==1) $sql_add="INTERVAL $duree MONTH";	
					if($unite==2) $sql_add="INTERVAL $duree YEAR";	
					$date=sql_value("SELECT DATE_ADD('$date', $sql_add)");
					$diff=sql_value("SELECT DATEDIFF('$date_fin','$date')");									
				}
				while($diff>=0);	
			}
		}
	}
	
	function update() {
		global $dbh;
		global $msg;
		global $include_path;
		global $act;
		
		if(!$this->modele_name)	return false;
		
		// nettoyage des valeurs en entrée
		$this->modele_name = clean_string($this->modele_name); 
	
		// construction de la requête
		$requete = "SET modele_name='".addslashes($this->modele_name)."', ";
		$requete .= "num_notice='$this->num_notice', ";
		$requete .= "num_periodicite='$this->num_periodicite', ";
		$requete .= "duree_abonnement='$this->duree_abonnement', ";
		$requete .= "date_debut='$this->date_debut', ";
		$requete .= "date_fin='$this->date_fin', ";
		$requete .= "days='$this->days', ";
		$requete .= "day_month='$this->day_month', ";
		$requete .= "week_month='$this->week_month', ";
		$requete .= "week_year='$this->week_year', ";
		$requete .= "month_year='$this->month_year', ";
		
		$requete .= "num_cycle='$this->num_cycle', ";		
		$requete .= "num_increment='$this->num_increment', ";
		$requete .= "num_combien='$this->num_combien', ";
		$requete .= "num_date_unite='$this->num_date_unite', ";
		$requete .= "num_increment_date='$this->num_increment_date', ";
		$requete .= "num_depart='$this->num_depart', ";
		$requete .= "vol_actif='$this->vol_actif', ";
		$requete .= "vol_increment='$this->vol_increment', ";
		$requete .= "vol_date_unite='$this->vol_date_unite', ";
		$requete .= "vol_increment_numero='$this->vol_increment_numero', ";
		$requete .= "vol_increment_date='$this->vol_increment_date', ";
		$requete .= "vol_cycle='$this->vol_cycle', ";
		$requete .= "vol_combien='$this->vol_combien', ";
		$requete .= "vol_depart='$this->vol_depart', ";
		$requete .= "tom_actif='$this->tom_actif', ";
		$requete .= "tom_increment='$this->tom_increment', ";
		$requete .= "tom_date_unite='$this->tom_date_unite', ";
		$requete .= "tom_increment_numero='$this->tom_increment_numero', ";
		$requete .= "tom_increment_date='$this->tom_increment_date', ";
		$requete .= "tom_cycle='$this->tom_cycle', ";
		$requete .= "tom_combien='$this->tom_combien', ";
		$requete .= "tom_depart='$this->tom_depart', ";
		$requete .= "format_aff='".addslashes($this->format_aff)."', ";
		$requete .= "format_periode='".addslashes($this->format_periode)."' ";
		
		if($this->modele_id) {
			// update: s'assurer que le nom de modèle n'existe pas déjà
			$dummy = "SELECT * FROM abts_modeles WHERE modele_name='".addslashes($this->modele_name)."' and num_notice='$this->num_notice' and modele_id!='$this->modele_id' ";
			$check = mysql_query($dummy, $dbh);
			if(mysql_num_rows($check)) {
				require_once("$include_path/user_error.inc.php");
				warning($msg["abonnements_titre_creation_edition_modele"], $msg["abonnements_erreur_creation_doublon_modele"]." ($this->modele_name).");
				return FALSE;
			}			
			$requete = 'UPDATE abts_modeles '.$requete;
			$requete .= ' WHERE modele_id='.$this->modele_id.' LIMIT 1;';
			if(mysql_query($requete, $dbh)) {
				
				if($act=="gen") $this->gen_date();				
				return TRUE;
			}else {
				require_once("$include_path/user_error.inc.php");
				warning($msg["abonnements_titre_creation_edition_modele"], $msg["abonnements_titre_creation_edition_modele_impossible"]);
				return FALSE;
			}
		} else {
				
			// s'assurer que le modèle n'existe pas déjà
			$dummy = "SELECT * FROM abts_modeles WHERE modele_name='".addslashes($this->modele_name)."' and num_notice='$this->num_notice'";
			$check = mysql_query($dummy, $dbh);
			if(mysql_num_rows($check)) {
				require_once("$include_path/user_error.inc.php");
				warning($msg["abonnements_titre_creation_edition_modele"], $msg["abonnements_erreur_creation_doublon_modele"]." ($this->modele_name).");
				return FALSE;
			}
			$requete = 'INSERT INTO abts_modeles '.$requete.';';
		
			if(mysql_query($requete, $dbh)) {
				$this->modele_id=mysql_insert_id();
				
				if($act=="gen") $this->gen_date();
				return TRUE;
			} else {
				require_once("$include_path/user_error.inc.php");
				warning($msg["abonnements_titre_creation_edition_modele"], $msg["abonnements_titre_creation_edition_modele_impossible"]);
				return FALSE;
			}
		}		
	}
	
	function delete()
	{
		global $dbh;
		global $msg;
		global $include_path;

		$dummy = "delete FROM abts_modeles WHERE modele_id='$this->modele_id' ";
		$check = mysql_query($dummy, $dbh);	
							
		$dummy = "delete FROM abts_grille_modele WHERE num_modele='$this->modele_id' ";
		$check = mysql_query($dummy, $dbh);			
	}
	
	function proceed() {
		global $include_path;
		global $act;
		global $serial_id,$msg,$modele_name,$num_notice,$num_periodicite,$duree_abonnement,$date_debut,$date_fin,$days,$day_month,$week_month,$week_year,$month_year,$date_parution;			
		global $num_cycle,$num_combien,$num_depart,$vol_actif,$vol_increment,$vol_date_unite,$vol_increment_numero,
		$vol_increment_date,$vol_cycle,$vol_combien,$vol_depart,$tom_actif,$tom_increment,$tom_date_unite,$tom_increment_numero,
		$tom_increment_date,$tom_cycle,$tom_combien,$tom_depart,$month_year,$format_aff,$format_periode,$new_serial_id,$num_increment,$num_date_unite,$num_increment_date;
		global $dbh,$include_path;
		
		switch ($act) {
			case 'update':								
				// mise à jour modèle
				$this->modele_name= stripslashes($modele_name);
				$this->num_notice= $num_notice;
				$this->num_periodicite= $num_periodicite;
				$this->duree_abonnement= $duree_abonnement;
				$this->date_debut=$date_debut;
				$this->date_fin= $date_fin;
				$this->days=calc_selection($days,7);
				$this->day_month=calc_selection($day_month,31);
				$this->week_month=calc_selection($week_month,6);
				$this->week_year= calc_selection($week_year,54);
				$this->month_year=calc_selection($month_year,12);
				$this->num_cycle = 1-calc_selection($num_cycle,1);
				$this->num_combien = $num_combien;
				$this->num_depart = $num_depart;
				$this->num_increment=$num_increment;
				$this->num_date_unite=$num_date_unite;
				$this->num_increment_date=$num_increment_date;
				$this->vol_actif = 1-calc_selection($vol_actif,1);
				$this->vol_increment = $vol_increment;
				$this->vol_date_unite = $vol_date_unite;
				$this->vol_increment_numero = $vol_increment_numero;
				$this->vol_increment_date = $vol_increment_date;
				$this->vol_cycle = 1-calc_selection($vol_cycle,1);
				$this->vol_combien = $vol_combien;
				$this->vol_depart = $vol_depart;
				$this->tom_actif = 1-calc_selection($tom_actif,1);	
				$this->tom_increment = $tom_increment;
				$this->tom_date_unite = $tom_date_unite;
				$this->tom_increment_numero = $tom_increment_numero;
				$this->tom_increment_date = $tom_increment_date;
				$this->tom_cycle = 1-calc_selection($tom_cycle,1);
				$this->tom_combien = $tom_combien;
				$this->tom_depart = $tom_depart;
				$this->format_aff = stripslashes($format_aff);
				$this->format_periode = stripslashes($format_periode);
				$this->update();										
				print $this->show_form();		
			break;
			case 'gen':								
				// mise à jour modèle
				$this->modele_name= stripslashes($modele_name);
				$this->num_notice= $num_notice;
				$this->num_periodicite= $num_periodicite;
				$this->duree_abonnement= $duree_abonnement;
				$this->date_debut=$date_debut;
				$this->date_fin= $date_fin;
				$this->days=calc_selection($days,7);
				$this->day_month=calc_selection($day_month,31);
				$this->week_month=calc_selection($week_month,6);
				$this->week_year= calc_selection($week_year,54);
				$this->month_year=calc_selection($month_year,12);
				$this->num_cycle = 1-calc_selection($num_cycle,1);
				$this->num_combien = $num_combien;
				$this->num_depart = $num_depart;
				$this->vol_actif = 1-calc_selection($vol_actif,1);
				$this->vol_increment = $vol_increment;
				$this->vol_date_unite = $vol_date_unite;
				$this->vol_increment_numero = $vol_increment_numero;
				$this->vol_increment_date = $vol_increment_date;
				$this->vol_cycle = 1-calc_selection($vol_cycle,1);
				$this->vol_combien = $vol_combien;
				$this->vol_depart = $vol_depart;
				$this->tom_actif = 1-calc_selection($tom_actif,1);	
				$this->tom_increment = $tom_increment;
				$this->tom_date_unite = $tom_date_unite;
				$this->tom_increment_numero = $tom_increment_numero;
				$this->tom_increment_date = $tom_increment_date;
				$this->tom_cycle = 1-calc_selection($tom_cycle,1);
				$this->tom_combien = $tom_combien;
				$this->tom_depart = $tom_depart;
				$this->format_aff = stripslashes($format_aff);
				$this->format_periode = stripslashes($format_periode);
				$this->update();										
				print $this->show_form();		
			break;			
			case 'copy':
				// mise à jour modèle
				$requete = "select type_serie, numero,date_parution,type_serie,nombre_recu from abts_grille_modele where num_modele='$this->modele_id'";
				$resultat=mysql_query($requete);
				
				//Création du nouveau modèle
				$this->modele_id='';
				$this->num_notice=$new_serial_id;
				$this->modele_name= clean_string($msg["abonnement_nom_nouveau_modele"]); 
				$serial_id=$new_serial_id;
				$this->update();
				$requete = "delete FROM abts_grille_modele WHERE num_modele='$this->modele_id'";
				mysql_query($requete, $dbh);		
				//recopie des infos du calendrier
				if(mysql_num_rows($resultat)) { 
					while(($r=mysql_fetch_object($resultat))){				
						$date_parution=$r->date_parution;
						$type_serie=$r->type_serie;
						$nombre_recu=$r->nombre_recu;
						$numero=$r->numero;
						
						$requete = "INSERT INTO abts_grille_modele SET num_modele='$this->modele_id', date_parution ='$date_parution', type_serie = '$type_serie', nombre_recu= '$nombre_recu'";
						mysql_query($requete, $dbh);
					}
				}			
				print $this->show_form();		
			break;
			case 'del':	
				// Verif si abonnements associés
				$requete="select abt_name,abts_abts.abt_id as ab_id from abts_abts_modeles,abts_abts where modele_id='$this->modele_id' and abts_abts.abt_id=abts_abts_modeles.abt_id";
				$resultat=mysql_query($requete);
				if (mysql_num_rows($resultat)) {	
					while(($r=mysql_fetch_object($resultat))) {
						$liste.="<a href=\"catalog.php?categ=serials&sub=abon&serial_id=".$this->num_notice."&abt_id=".$r->ab_id."\">".$r->abt_name."</a><br />";	
					}
					require_once("$include_path/user_error.inc.php");
					warning(htmlentities($msg["abonnements_titre_effacement_modele"],ENT_QUOTES,$charset), htmlentities($msg["abonnements_effacement_modele_erreur"],ENT_QUOTES,$charset)."<br /><strong>".$liste."</strong>");
					print $this->show_form();		
					return;	
				}
				$this->delete();		
				print "<div class='row'><div class='msg-perio'>".$msg[maj_encours]."</div></div>";
				$id_form = md5(microtime());
				$retour = "./catalog.php?categ=serials&sub=view&serial_id=$serial_id&view=modele";
				print "<form class='form-$current_module' name=\"dummy\" method=\"post\" action=\"$retour\" style=\"display:none\">
					<input type=\"hidden\" name=\"id_form\" value=\"$id_form\">
					</form>
					<script type=\"text/javascript\">document.dummy.submit();</script>
					</div>";
			break;
			default:
				print $this->show_form();
				break;
		}
	}
}

class abts_modeles {
	
	var $modeles = array(); //Tableau des IDs des modèles
	
    function abts_modeles($id_perio) {
    	$requete="select modele_id from abts_modeles where num_notice=$id_perio";
    	$resultat=mysql_query($requete);
    	while ($r=mysql_fetch_object($resultat)) {
    		$modele=new abts_modele($r->modele_id);
    		if (!$modele->error) $this->modeles[]=$modele;
    	}
    }
    
    function show_list() {
    	global $modele_list,$msg;
    	$r=$modele_list;
    	$modeles="";
    	if (count($this->modeles)) {
    		for ($i=0; $i<count($this->modeles); $i++) {
    			$modeles.=$this->modeles[$i]->show_modele();
    		}
    	} else $modeles=$msg["abts_modeles_no_modele"];
    	return str_replace("!!modele_list!!",$modeles,$r);
    }
}

function calc_selection($val,$size)
{
	$ret='';
	for ($i=0; $i<$size; $i++) {
		if(!isset($val[$i+1])) $ret .='1'; else $ret .='0';
	}		
	return $ret;
}	

function sql_value($rqt)
{
	$result=mysql_query($rqt);
	$row = mysql_fetch_row($result);
	return $row[0];
}
?>