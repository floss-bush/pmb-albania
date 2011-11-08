<?php
// +-------------------------------------------------+
// | 2002-2007 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: abts_pointage.class.php,v 1.34 2010-11-10 10:03:38 ngantier Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php"))
	die("no access");

require_once ($include_path . "/templates/abts_pointage.tpl.php");
require_once ($class_path . "/serial_display.class.php");
require_once ($include_path . "/abts_func.inc.php");
require_once ($include_path . "/misc.inc.php");
require_once ($class_path . "/parse_format.class.php");

class abts_pointage {
	var $num_notice; //notice id
	var $error; //Erreur
	var $error_message; //Message d'erreur

	function abts_pointage($notice_id = "") {
		//Verif de l'id de la notice 
		if ($notice_id) {
			$this->num_notice = 0;
			$requete = "select niveau_biblio from notices where notice_id=" . $notice_id;
			$resultat = mysql_query($requete);
			if (mysql_result($resultat, 0, 0) == "s")
				$this->num_notice = $notice_id;

			else {
				$this->error = true;
				$this->error_message = "La notice li�e n'existe pas ou n'est pas un p�riodique";
			}

		}
	}

	function getData() {

	}

	function get_bulletinage() {
		global $msg;
		global $dbh;
		global $pointage_form, $pointage_list;
		global $location_view, $deflt_docs_location;

		$print_format=new parse_format();

		if (!$location_view) $location_view = $deflt_docs_location;
		
		if($this->num_notice) $and_rqt_notice=" and notice_id =". $this->num_notice ;
		
		$cpt_a_recevoir = $cpt_en_retard = $cpt_en_alerte = 0;
		$numero_modele = '';
		$requete = "
		 select * from (
			SELECT id_bull,num_abt,abts_grille_abt.date_parution,modele_id,type,numero,nombre,ordre,state,fournisseur,abt_name,num_notice,tit1,date_debut, date_fin,cote
			FROM abts_grille_abt ,abts_abts, notices 
			WHERE abts_grille_abt.date_parution <= CURDATE() and abt_id=num_abt and notice_id= num_notice and location_id='$location_view'	$and_rqt_notice
			union 
			select id_bull,num_abt,prochain.date_parution,modele_id,type,numero,nombre,ordre,state,fournisseur,abt_name,num_notice,tit1,date_debut, date_fin ,cote
			from (
				SELECT id_bull,num_abt,abts_grille_abt.date_parution,modele_id,type,numero,nombre,ordre,state,fournisseur,abt_name,num_notice,tit1,date_debut, date_fin ,cote
				FROM abts_grille_abt ,abts_abts, notices 
				WHERE abts_grille_abt.date_parution > CURDATE()  and abt_id=num_abt and notice_id= num_notice and location_id='$location_view'	$and_rqt_notice ORDER BY abts_grille_abt.date_parution,tit1,ordre,abt_name 
		) as prochain group by type,ordre,num_abt,modele_id) as liste_bull order by date_parution,tit1,ordre,abt_name;
		";	
		$resultat = mysql_query($requete);
		while ($r = mysql_fetch_object($resultat)) {
			$numero = $r->numero;
			$libelle_numero = $numero;
			$volume = "";
			$tome = "";

			if (!$numero_modele[$r->modele_id]) {
				$requete = "SELECT modele_name,num_cycle,num_combien,num_increment,num_date_unite,num_increment_date,num_depart,vol_actif,vol_increment,vol_date_unite,vol_increment_numero,vol_increment_date,vol_cycle,vol_combien,vol_depart,tom_actif,tom_increment,tom_date_unite,tom_increment_numero,tom_increment_date,tom_cycle,tom_combien,tom_depart, format_aff 
							FROM abts_modeles WHERE modele_id=$r->modele_id";
				$resultat_n = mysql_query($requete);
				if ($r_n = mysql_fetch_object($resultat_n)) {
					$numero_modele[$r->modele_id]['modele_name'] = $r_n->modele_name;
					$numero_modele[$r->modele_id]['num_cycle'] = $r_n->num_cycle;
					$numero_modele[$r->modele_id]['num_combien'] = $r_n->num_combien;
					$numero_modele[$r->modele_id]['num_increment'] = $r_n->num_increment;
					$numero_modele[$r->modele_id]['num_date_unite'] = $r_n->num_date_unite;
					$numero_modele[$r->modele_id]['num_increment_date'] = $r_n->num_increment_date;
					$numero_modele[$r->modele_id]['num_depart'] = $r_n->num_depart;
					$numero_modele[$r->modele_id]['vol_actif'] = $r_n->vol_actif;
					$numero_modele[$r->modele_id]['vol_increment'] = $r_n->vol_increment;
					$numero_modele[$r->modele_id]['vol_date_unite'] = $r_n->vol_date_unite;
					$numero_modele[$r->modele_id]['vol_increment_numero'] = $r_n->vol_increment_numero;
					$numero_modele[$r->modele_id]['vol_increment_date'] = $r_n->vol_increment_date;
					$numero_modele[$r->modele_id]['vol_cycle'] = $r_n->vol_cycle;
					$numero_modele[$r->modele_id]['vol_combien'] = $r_n->vol_combien;
					$numero_modele[$r->modele_id]['vol_depart'] = $r_n->vol_depart;
					$numero_modele[$r->modele_id]['tom_actif'] = $r_n->tom_actif;
					$numero_modele[$r->modele_id]['tom_increment'] = $r_n->tom_increment;
					$numero_modele[$r->modele_id]['tom_date_unite'] = $r_n->tom_date_unite;
					$numero_modele[$r->modele_id]['tom_increment_numero'] = $r_n->tom_increment_numero;
					$numero_modele[$r->modele_id]['tom_increment_date'] = $r_n->tom_increment_date;
					$numero_modele[$r->modele_id]['tom_cycle'] = $r_n->tom_cycle;
					$numero_modele[$r->modele_id]['tom_combien'] = $r_n->tom_combien;
					$numero_modele[$r->modele_id]['tom_depart'] = $r_n->tom_depart;
					$numero_modele[$r->modele_id]['format_aff'] = $r_n->format_aff;
				}
				$numero_modele[$r->modele_id]['date_debut'] = $r->date_debut;
				//confection de la requette sql pour les num cyclique date
				$requette = $numero_modele[$r->modele_id]['num_increment_date'];
				if ($numero_modele[$r->modele_id]['num_date_unite'] == 1)	$requette .= " month ";
				elseif ($numero_modele[$r->modele_id]['num_date_unite'] == 2) $requette .= " year ";
				else $requette .= " day ";
				$numero_modele[$r->modele_id]['num_date_sql'] = $requette;
				$numero_modele[$r->modele_id]['num_date_fin_cycle'] = sql_value("SELECT DATE_ADD('" . $numero_modele[$r->modele_id]['date_debut'] . "', INTERVAL " . $numero_modele[$r->modele_id]['num_date_sql'] . ")");

				//confection de la requette sql pour les vol cyclique date
				$requette = $numero_modele[$r->modele_id]['vol_increment_date'];
				if ($numero_modele[$r->modele_id]['vol_date_unite'] == 1) $requette .= " month ";
				elseif ($numero_modele[$r->modele_id]['vol_date_unite'] == 2) $requette .= " year ";
				else $requette .= " day ";
				$numero_modele[$r->modele_id]['vol_date_sql'] = $requette;
				$numero_modele[$r->modele_id]['vol_date_fin_cycle'] = sql_value("SELECT DATE_ADD('" . $numero_modele[$r->modele_id]['date_debut'] . "', INTERVAL " . $numero_modele[$r->modele_id]['vol_date_sql'] . ")");

				//confection de la requette sql pour les tom cyclique date
				$requette = $numero_modele[$r->modele_id]['tom_increment_date'];
				if ($numero_modele[$r->modele_id]['tom_date_unite'] == 1) $requette .= " month ";
				elseif ($numero_modele[$r->modele_id]['tom_date_unite'] == 2) $requette .= " year ";
				else $requette .= " day ";
				$numero_modele[$r->modele_id]['tom_date_sql'] = $requette;
				$numero_modele[$r->modele_id]['tom_date_fin_cycle'] = sql_value("SELECT DATE_ADD('" . $numero_modele[$r->modele_id]['date_debut'] . "', INTERVAL " . $numero_modele[$r->modele_id]['tom_date_sql'] . ")");
				
			}
			if( $r->type != 2){
				if (!$numero_modele[$r->modele_id][$r->num_abt]) {
					$requete = "SELECT num,vol, tome, delais,	critique FROM abts_abts_modeles WHERE modele_id=$r->modele_id and abt_id=$r->num_abt";
					$resultat_n = mysql_query($requete);
					if ($r_abt = mysql_fetch_object($resultat_n)) {
						$numero_modele[$r->modele_id][$r->num_abt]['num'] = $r_abt->num;
						$numero_modele[$r->modele_id][$r->num_abt]['vol'] = $r_abt->vol;
						$numero_modele[$r->modele_id][$r->num_abt]['tom'] = $r_abt->tome;
						$numero_modele[$r->modele_id][$r->num_abt]['delais'] = $r_abt->delais;
						$numero_modele[$r->modele_id][$r->num_abt]['critique'] = $r_abt->critique;
						$numero_modele[$r->modele_id][$r->num_abt]['start_num'] = $r_abt->num;
						$numero_modele[$r->modele_id][$r->num_abt]['start_vol'] = $r_abt->vol;
						$numero_modele[$r->modele_id][$r->num_abt]['start_tom'] = $r_abt->tome;
					}							
					//Calculer � partir du numero de debut du mod�le 
					/*
					$number = $numero_modele[$r->modele_id][$r->num_abt]['num'];
					//$numero_modele[$r->modele_id][$r->num_abt]['num'] = $r_n->num_depart;
					for ($i = $r_n->num_depart; $i < $number; $i++)	{
							increment_bulletin($r->modele_id, $numero_modele[$r->modele_id],$r->num_abt);
					}
					*/	
					$numero_modele[$r->modele_id][$r->num_abt]['date_parution'] = $r->date_parution;
					$numero_modele[$r->modele_id][$r->num_abt]['num']--;
					increment_bulletin($r->modele_id, $numero_modele[$r->modele_id],$r->num_abt);	
					//permet de d�terminer s'im	
					$numero_modele[$r->modele_id][$r->num_abt]['ordre'] = $r->ordre;
					
				} elseif (($numero_modele[$r->modele_id][$r->num_abt]['date_parution'] != $r->date_parution) || ($numero_modele[$r->modele_id][$r->num_abt]['ordre'] != $r->ordre)) {
					$numero_modele[$r->modele_id][$r->num_abt]['date_parution'] = $r->date_parution;
					$numero_modele[$r->modele_id][$r->num_abt]['ordre'] = $r->ordre;
					increment_bulletin($r->modele_id, $numero_modele[$r->modele_id],$r->num_abt);			
				}
			}
			
			if ($r->type == 1) {				
				$numero_modele[$r->modele_id][abt_name] = $r->abt_name;
				$libelle_abonnement = $numero_modele[$r->modele_id]['modele_name'] . " / " . $numero_modele[$r->modele_id]['abt_name'];
				
				
				$numero = $numero_modele[$r->modele_id][$r->num_abt]['num'];
				
				$volume = $numero_modele[$r->modele_id][$r->num_abt]['vol'];
				$tome = $numero_modele[$r->modele_id][$r->num_abt]['tom'];
				$format_aff = $numero_modele[$r->modele_id]['format_aff'];
				if($format_aff){
					$print_format->var_format['DATE'] = $r->date_parution;
					$print_format->var_format['TOM'] = $tome;
					$print_format->var_format['VOL'] = $volume;
					$print_format->var_format['NUM'] = $numero;
					$print_format->var_format['START_NUM'] = $numero_modele[$r->modele_id][$r->num_abt]['start_num'];
					$print_format->var_format['START_VOL'] = $numero_modele[$r->modele_id][$r->num_abt]['start_vol'];
					$print_format->var_format['START_TOM'] = $numero_modele[$r->modele_id][$r->num_abt]['start_tom'];
					$print_format->var_format['START_DATE'] = $r->date_debut;
					$print_format->var_format['END_DATE'] = $r->date_fin;
										
					$print_format->cmd = $format_aff;
					$libelle_numero=$print_format->exec_cmd();
				}	
				else {
					$libelle_numero="";
					if($tome)$libelle_numero.="Tome $tome ";
					if($volume)$libelle_numero.="Vol $volume ";
					if($numero)$libelle_numero.="N�$numero";					
				}
			}
			else if ($r->type == 2) {				
				$numero_modele[$r->modele_id][abt_name] = $r->abt_name;
				$libelle_abonnement = $numero_modele[$r->modele_id]['modele_name'] . " / " . $numero_modele[$r->modele_id]['abt_name'];
				
				$volume = $numero_modele[$r->modele_id][$r->num_abt]['vol'];
				$tome = $numero_modele[$r->modele_id][$r->num_abt]['tom'];
				$format_aff = $numero_modele[$r->modele_id]['format_aff'];
				if($format_aff){
					$print_format->var_format['DATE'] = $r->date_parution;
					$print_format->var_format['TOM'] = $tome;
					$print_format->var_format['VOL'] = $volume;
					$print_format->var_format['NUM'] = "HS".$numero;
					$print_format->var_format['START_NUM'] = $numero_modele[$r->modele_id][$r->num_abt]['start_num'];
					$print_format->var_format['START_VOL'] = $numero_modele[$r->modele_id][$r->num_abt]['start_vol'];
					$print_format->var_format['START_TOM'] = $numero_modele[$r->modele_id][$r->num_abt]['start_tom'];
					$print_format->var_format['START_DATE'] = $r->date_debut;
					$print_format->var_format['END_DATE'] = $r->date_fin;
										
					$print_format->cmd = $format_aff;
					$libelle_numero=$print_format->exec_cmd();
				}	
				else {
					$libelle_numero="";
					if($tome)$libelle_numero.="Tome $tome ";
					if($volume)$libelle_numero.="Vol $volume ";
					if($numero)$libelle_numero.="HS N�$numero";					
				}
			}
			
			if ($r->state == 0) {			
				$obj = $r->id_bull;
				$fiche['date_parution']=$r->date_parution;
				$fiche['periodique']="<a href=\"./catalog.php?categ=serials&sub=view&serial_id=" . $r->num_notice . "\">$r->tit1</a>";
				$fiche['libelle_notice']=$r->tit1;
				$fiche['libelle_numero']=$libelle_numero;
				$fiche['libelle_abonnement']=$libelle_abonnement;
				$fiche['link_recu']="onClick='bulletine(\"$obj\",event);'";
				$fiche['link_non_recevable']="onClick='nonrecevable(\"$obj\",event);'";
				$fiche['fournisseur_id']=$r->fournisseur;
				$fiche['location_id']=$r->location_id;
				$fiche['TOM']=$tome;
				$fiche['VOL']=$volume;
				$fiche['NUM']=$numero;
				$fiche['cote'] = $r->cote;

				//Test des retards
				$diff = sql_value("SELECT DATEDIFF(CURDATE(),'$r->date_parution')");
				if($diff<0) $retard=3;
				elseif ($diff <= $numero_modele[$r->modele_id][$r->num_abt]["delais"])	$retard=0;
				elseif ($diff <= $numero_modele[$r->modele_id][$r->num_abt]["critique"]) $retard=1;
				else $retard=2;
				$fiche_bulletin[$retard][$obj]=$fiche;					
			}
		}	
		return $fiche_bulletin;
	}	

	function show_form() {
		global $msg, $charset;
		global $dbh;
		global $pointage_form, $pointage_list;
		global $location_view, $deflt_docs_location,$serial_id,$pmb_abt_end_delay;
		
		if (!$location_view) $location_view = $deflt_docs_location;
		$form = $pointage_form;

		$form .=<<<ENDOFTEXT
		<script type="text/javascript" src='./javascript/select.js'></script>
		<script type="text/javascript" src='./javascript/ajax.js'></script>
		<script type="text/javascript">
		function bulletine(obj,e) {	
			
			if(!e) e=window.event;
			
			var tgt = e.target || e.srcElement; // IE doesn't use .target
			var strid = tgt.id;
			var type = tgt.tagName;
			var obj_2=obj+"_2";
			var obj_3=obj+"_3";
			e.cancelBubble = true;				
				
			var id_obj=document.getElementById(obj_2);
			var pos=findPos(id_obj);
			
			var num=id_obj.getAttribute('num');	
			var nume=id_obj.getAttribute('nume');	
			var vol=id_obj.getAttribute('vol');	
			var tom=id_obj.getAttribute('tom');	
			
			var url="./catalog/serials/pointage/pointage_exemplarise.php?id_bull="+obj+"&numero="+num+"&nume="+nume+"&vol="+vol+"&tom="+tom+"";
			
			var notice_view=document.createElement("iframe");
			notice_view.setAttribute('id','frame_periodique');
			notice_view.setAttribute('name','periodique');
			notice_view.src=url; 
			
			var att=document.getElementById("att");	
			notice_view.style.visibility="hidden";
			notice_view.style.display="block";
			notice_view=att.appendChild(notice_view);

			notice_view.style.width="750px";
			notice_view.style.height="600px";
			notice_view.style.left=(pos[0]-720)+"px";
			notice_view.style.top=(pos[1]+15)+"px";
						
			notice_view.style.visibility="visible";						
		}
		
		function nonrecevable(obj,e) {	
			
			if(!e) e=window.event;
			
			var tgt = e.target || e.srcElement; // IE doesn't use .target
			var strid = tgt.id;
			var type = tgt.tagName;
			var obj_2=obj+"_2";
			var obj_3=obj+"_3";
			e.cancelBubble = true;				
				
			var id_obj=document.getElementById(obj_2);
			var pos=findPos(id_obj);
			
			var num=id_obj.getAttribute('num');	
			
			var url="./catalog/serials/pointage/pointage_exemplarise.php?nonrecevable=1&id_bull="+obj+"&numero="+num+"";
			
			var notice_view=document.createElement("iframe");
			notice_view.setAttribute('id','frame_periodique');
			notice_view.setAttribute('name','periodique');
			notice_view.src=url; 
			
			var att=document.getElementById("att");	
			notice_view.style.visibility="hidden";
			notice_view.style.display="block";
			notice_view=att.appendChild(notice_view);

			notice_view.style.width="700px";
			notice_view.style.height="400px";
					
			w=notice_view.clientWidth;
			h=notice_view.clientHeight;

			posx=(getWindowWidth()/2-(w/2))<0?0:(getWindowWidth()/2-(w/2))
			posy=(getWindowHeight()/2-(h/2))<0?0:(getWindowHeight()/2-(h/2));
			notice_view.style.left=posx+"px";
			notice_view.style.top=posy+"px";
		}
		
		function kill_frame_periodique() {
			var notice_view=document.getElementById("frame_periodique");
			notice_view.parentNode.removeChild(notice_view);	
		}


		function imprime() {
			var selectBox=document.getElementById("location_id");
			value=selectBox.options[selectBox.selectedIndex].value;
			document.location="./pdf.php?pdfdoc=liste_bulletinage&act=print&location_view="+value;
		}		
		
ENDOFTEXT;
		$link_bulletinage="";
		if ($serial_id) {
			$link_bulletinage = "&serial_id=$serial_id"; 
		}
				
		$form.= "
			function localisation_change(selectBox) {			
			id=selectBox.options[selectBox.selectedIndex].value;
			document.location='./catalog.php?categ=serials&sub=pointage".$link_bulletinage."&location_view='+id;
		}
		</script>	
		";


		// select "localisation"
		$form_localisation = gen_liste("select distinct idlocation, location_libelle from docs_location order by location_libelle ", "idlocation", "location_libelle", 'location_id', "localisation_change(this);", $location_view, "", "", "", "", 0);
		$link_bulletinage="";
		if ($serial_id) {
			$requete = "SELECT tit1 from notices WHERE notice_id= $serial_id";
			$resultat = mysql_query($requete);
			if ($r = mysql_fetch_object($resultat)) {
				
				$link_bulletinage = "<a href='./catalog.php?categ=serials&sub=view&serial_id=$serial_id&location=$location_view'>"
					.$r->tit1."</a>"; 
			}	
			$form_localisation.=$link_bulletinage;
		}
		
		$form = str_replace('!!localisation!!',$form_localisation , $form);
		$header_table = "<table class='sortable'>			
						<th>" .	$msg['pointage_label_date'] . "</th>
						<th>" . $msg['pointage_label_notice'] . "</th>
						<th>" . $msg['pointage_label_numero'] . "</th>
						<th>" . $msg['pointage_label_abonnement'] . "</th>
						<th>" . $msg['pointage_label_a_recevoir'] . "</th>
						<th>" . $msg['pointage_label_recu'] . "</th>
						<th>" . $msg['pointage_label_supprimer_et_conserver'] . "</th>
						<th>" . $msg['pointage_label_voir_bulletin'] . "</th>	";													
		$liste_bulletin=$this->get_bulletinage();
		$a_recevoir = $en_retard = $en_alerte = "";
		$cpt_a_recevoir = $cpt_en_retard = $cpt_en_alerte = 0;					
		
		if($liste_bulletin){
			//Tri par type de retard
			asort($liste_bulletin);
	
			foreach($liste_bulletin as $retard => $bulletin_retard){
				$cpt=0;
				$contenu='';
				foreach($bulletin_retard as $id_bull => $fiche){
					if (++$cpt % 2) $pair_impair = "even"; else $pair_impair = "odd";
					$contenu_tmp = "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>";
					$contenu_tmp .= "<td><strong>" . formatdate($fiche['date_parution']) . "</strong></td>";
					$contenu_tmp .= "<td>".$fiche['periodique']."</td>";
					$contenu_tmp .= "<td>".$fiche['libelle_numero']."</td>";
					$contenu_tmp .= "<td>".$fiche['libelle_abonnement']."</td>";
					$contenu_tmp .= "<td><input name='".$id_bull."' id='".$id_bull."_1' checked='checked'  value='1' type='radio'></td>";
					$contenu_tmp .= "<td><input name='".$id_bull."' id='".$id_bull."_2' value='2' nume='". $fiche['NUM']."' vol='". $fiche['VOL']."'	tom='". $fiche['TOM']."' num='". htmlentities($fiche['libelle_numero'],ENT_QUOTES, $charset)."'  type='radio' ".$fiche['link_recu']." ></td>";
					$contenu_tmp .= "<td><input name='".$id_bull."' id='".$id_bull."_3' value='3' type='radio' ".$fiche['link_non_recevable']." ></td>";
					$contenu_tmp .= "<td id='". $id_bull."_bul'>&nbsp</td>";
					$contenu_tmp .= "</tr>";	
					$contenu=$contenu_tmp.$contenu;
								
				}
				$contenu = $header_table . $contenu . "</table>";
				if($cpt && $retard==3){
					$prochain_numero = gen_plus_form("prochain_numero", $msg["pointage_label_prochain_numero"] . " ($cpt)", $contenu);
					$cpt_prochain_numero= $cpt;				
				}				
				if($cpt && $retard==0){
					$a_recevoir = gen_plus_form("a_recevoir", $msg["pointage_label_a_recevoir"] . " ($cpt)", $contenu);
					$cpt_a_recevoir= $cpt;				
				}	
				if($cpt && $retard==1){
					$en_retard = gen_plus_form("en_retard", $msg["pointage_label_en_retard"] . " ($cpt)", $contenu);	
					$cpt_en_retard=	$cpt;		
				}			
				if($cpt && $retard==2){
					$en_alerte = gen_plus_form("en_alerte", $msg["pointage_label_depasse"] . " ($cpt)", $contenu);	
					$cpt_en_alerte=	$cpt;	
				}				
			}	
		}	
		$pointage_list = str_replace('!!prochain_numero!!', $prochain_numero, $pointage_list);
		$pointage_list = str_replace('!!a_recevoir!!', $a_recevoir, $pointage_list);
		$pointage_list = str_replace('!!en_retard!!', $en_retard, $pointage_list);
		$pointage_list = str_replace('!!en_alerte!!', $en_alerte, $pointage_list);
		// Gestion des abonnements qui arrive a terme
		if(!$pmb_abt_end_delay || !is_numeric($pmb_abt_end_delay)) $pmb_abt_end_delay=30;
		$header_table = "<table>			
					<th>" .	$msg['pointage_label_date_fin'] . "</th>		
					<th>" . $msg['pointage_label_abonnement'] . "</th>";			
		$requete = "SELECT abt_id,abt_name,tit1,num_notice, date_fin
					FROM abts_abts,notices
					WHERE date_fin BETWEEN CURDATE() AND  DATE_ADD(CURDATE(), INTERVAL $pmb_abt_end_delay DAY)
					and notice_id= num_notice
					and location_id='$location_view'
					ORDER BY date_fin,abt_name";
		$resultat = mysql_query($requete);	
		$cpt=0;
		$contenu='';
		while ($r = mysql_fetch_object($resultat)) {
			if (++$cpt % 2) $pair_impair = "even"; else $pair_impair = "odd";
			$contenu .= "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>";
			$contenu .= "<td><strong>" . formatdate($r->date_fin) . "</strong></td>";
			$contenu .= "<td><a href=\"./catalog.php?categ=serials&sub=abon&serial_id=" . $r->num_notice . "&abt_id=" . $r->abt_id . "\">".$r->tit1." / ".$r->abt_name."</a></td>";		
			$contenu .= "</tr>";				
		}
		$contenu = $header_table . $contenu . "</table>";
		$fin_abonnement='';
		if($cpt){
			$fin_abonnement = gen_plus_form("fin_abonnement", $msg["pointage_alerte_fin_abonnement"] . " ($cpt)", $contenu);			
		}	
		// Gestion des abonnements dont la date est d�pass�e
		$requete = "SELECT abt_id,abt_name,tit1,num_notice, date_fin
					FROM abts_abts,notices
					WHERE date_fin < CURDATE()
					and notice_id= num_notice
					and location_id='$location_view'
					ORDER BY date_fin,abt_name";		
		$resultat = mysql_query($requete);	
		$cpt=0;
		$contenu='';
		while ($r = mysql_fetch_object($resultat)) {
			if (++$cpt % 2) $pair_impair = "even"; else $pair_impair = "odd";
			$contenu .= "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>";
			$contenu .= "<td><strong>" . formatdate($r->date_fin) . "</strong></td>";
			$contenu .= "<td><a href=\"./catalog.php?categ=serials&sub=abon&serial_id=" . $r->num_notice . "&abt_id=" . $r->abt_id . "\">".$r->tit1." / ".$r->abt_name."</a></td>";	
			$contenu .= "</tr>";				
		}
		$contenu = $header_table . $contenu . "</table>";
		$abonnement_depasse='';
		if($cpt){
			$abonnement_depasse = gen_plus_form("depasse_abonnement", $msg["pointage_alerte_abonnement_depasse"] . " ($cpt)", $contenu);			
		}				
				
		$pointage_list = str_replace('!!alerte_fin_abonnement!!', $fin_abonnement, $pointage_list);
		$pointage_list = str_replace('!!alerte_abonnement_depasse!!', $abonnement_depasse, $pointage_list);
		
		$form = str_replace('!!bultinage!!', $pointage_list, $form);
		if ($cpt_en_retard || $cpt_en_alerte)
			$form = str_replace("!!imprimer!!", "<input type=\"button\" class='bouton' value='" .
			$msg["abonnements_imprimer_lettres"] . "' onClick=\"imprime();\"/>", $form);			
		else $form = str_replace("!!imprimer!!", "", $form);
		$form = str_replace("!!action!!", "./catalog.php?categ=serials&sub=pointage&serial_id=" . "$serial_id&location_view=$location_view", $form);
		return $form;
	}


	function imprimer() {
		global $dbh;
		global $msg;
		global $include_path;
	}

	function proceed() {
		global $act;
		global $serial_id, $msg, $num_notice;
		
		switch ($act) {
			case 'print' :
				$liste_bulletin=$this->get_bulletinage();
				return $liste_bulletin;
				break;
			default :
				print $this->show_form();
				break;
		}
	}
}// Fin de la Classe

function increment_bulletin($modele_id, &$num,$num_abt) {
	// num_cycle 	num_combien 	num_increment 	num_date_unite 	num_increment_date 	num_depart 	
	// vol_actif 	vol_increment 	vol_date_unite 	vol_increment_numero 	vol_increment_date 	vol_cycle 	vol_combien 	vol_depart 	
	// tom_actif 	tom_increment 	tom_date_unite 	tom_increment_numero 	tom_increment_date 	tom_cycle 	tom_combien 	tom_depart 	
	// format_aff			
	$num[$num_abt]['num']++;

	if ($num['num_cycle']) {
		if (!$num['num_increment']) { //numero cyclique selon un nombre de bulletin
			if ($num[$num_abt]['num'] > $num['num_combien']) {
				$num[$num_abt]['num'] = $num['num_depart'];
			}
		} else { // numero cyclique selon la date
			if (sql_value("SELECT DATEDIFF('" . $num['num_date_fin_cycle'] . "','" . $num[$num_abt]['date_parution'] . "')") <= 0) {
				$num[$num_abt]['num'] = $num['num_depart'];
				$num['num_date_fin_cycle'] = sql_value("SELECT DATE_ADD('" . $num['num_date_fin_cycle'] . "', INTERVAL " . $num['num_date_sql'] . ")");
			}
		}
	}

	if ($num['vol_actif']) {
		if ($num['inc_vol'] == 1) {
			$num[$num_abt]['vol']++;
			$num['inc_vol'] = 0;
		}
		if (!$num['vol_increment']) { //volume s'incr�mente selon un nombre de bulletin
			$modulo = ($num[$num_abt]['num']) % ($num['vol_increment_numero']);
			if ($modulo == 0) {
				$num['inc_vol'] = 1;
			}
		} else { // volume s'incr�mente selon la date 			
			if (sql_value("SELECT DATEDIFF('" . $num['vol_date_fin_cycle'] . "','" . $num[$num_abt]['date_parution'] . "')") <= 0) {
				$num[$num_abt]['vol']++;
				$num['vol_date_fin_cycle'] = sql_value("SELECT DATE_ADD('" . $num['vol_date_fin_cycle'] . "', INTERVAL " . $num['vol_date_sql'] . ")");
			}
		}
		// Si volume est cyclique
		if ($num['vol_cycle']) {
			if ($num[$num_abt]['vol'] > $num['vol_combien']) {
				$num[$num_abt]['vol'] = $num['vol_depart'];
			}
		}
	}

	if ($num['tom_actif']) {
		if (($num['inc_tom'] == 1) && ($num['val_vol'] != $num[$num_abt]['vol'])) {
			$num[$num_abt]['tom']++;
			$num['inc_tom'] = 0;
		}
		if (!$num['tom_increment']) { //tome s'incr�mente selon un nombre de volume
			if ($num['val_vol'] != $num[$num_abt]['vol']) {
				$num['val_vol'] = $num[$num_abt]['vol'];
				$modulo = ($num[$num_abt]['vol']) % ($num['tom_increment_numero']);
				if ($modulo == 0) {
					$num['inc_tom'] = 1;
				}
			}
		} else { // tome s'incr�mente selon la date
			if (sql_value("SELECT DATEDIFF('" . $num['tom_date_fin_cycle'] . "','" . $num[$num_abt]['date_parution'] . "')") <= 0) {
				$num[$num_abt]['tom']++;
				$num['tom_date_fin_cycle'] = sql_value("SELECT DATE_ADD('" . $num['tom_date_fin_cycle'] . "', INTERVAL " . $num['tom_date_sql'] . ")");
			}
		}
		// Si tome est cyclique
		if ($num['tom_cycle']) {
			if ($num[$num_abt]['tom'] > $num['tom_combien']) {
				$num[$num_abt]['tom'] = $num['tom_depart'];
			}
		}
	}
}

function calc_selection($val, $size) {
	$ret = '';
	for ($i = 0; $i < $size; $i++) {
		if (!isset ($val[$i +1]))
			$ret .= '1';
		else
			$ret .= '0';
	}
	return $ret;
}

function sql_value($rqt) {
	if ($result = mysql_query($rqt))
		if ($row = mysql_fetch_row($result))
			return $row[0];
	return '';
}

function gen_plus_form($id, $titre, $contenu) {
	return "	
		<div class='row'></div>
		<div id='$id' class='notice-parent'>
			<img src='./images/plus.gif' class='img_plus' name='imEx' id='$id" . "Img' title='d�tail' border='0' onClick=\"expandBase('$id', true); return false;\" hspace='3'>
			<span class='notice-heada'>
				$titre
			</span>
		</div>
		<div id='$id" . "Child' class='notice-child' style='margin-bottom:6px;display:none;width:94%'>
			$contenu
		</div>
		";
}
?>