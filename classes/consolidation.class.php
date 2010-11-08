<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: consolidation.class.php,v 1.4 2010-08-12 12:33:31 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");


require_once ($class_path . "/parse_format.class.php");

define("DEFAULT_CONSO",1);
define("INTERVAL_CONSO",2);
define("ECHEANCE_CONSO",3);

class consolidation {
	
	var $mode=1;
	var $date_debut='';
	var $date_fin='';
	var $echeance='';
	var $list_idview = array();
	
	function consolidation($mode=1,$date_debut='',$date_fin='',$echeance='',$list_ck=''){
		$this->mode = $mode;
		$this->date_debut = $date_debut;
		$this->date_fin = $date_fin;
		$this->echeance = $echeance;
		$this->list_idview = $list_ck;
	}
	
	function make_consolidation(){
		
		switch($this->mode){
			
			case INTERVAL_CONSO:
				$this->calculer_sur_periode($this->date_debut,$this->date_fin);
				$this->consolider();
				break;
			
			case ECHEANCE_CONSO:
				$this->calculer_until($this->echeance);
				$this->consolider();
				break;
				
			default:
				$this->calculer_since_last();
				$this->consolider();
				break;			
			
		}
	}
	
	/**
	 * Fonction qui permet d'extraire un lot de log entre des dates précises
	 */
	function calculer_sur_periode($date_deb,$date_fin){
		global $dbh;		

		for($i=0;$i<sizeof($this->list_idview);$i++){
			$id_vue=$this->list_idview[$i];
			$req = "create temporary table logs_filtre_$id_vue select * from statopac where date_log between '".addslashes($date_deb)."' and '".addslashes($date_fin)."'";
			mysql_query($req,$dbh);
		}
	}
	
	/**
	 * Fonction qui permet d'extraire un lot de log depuis le début des enregistrements jusqu'à l'échéance fixée
	 */
	function calculer_until($echeance){
		global $dbh;
		for($i=0;$i<sizeof($this->list_idview);$i++){
			$id_vue=$this->list_idview[$i];
			$req = "create temporary table logs_filtre_$id_vue select * from statopac where date_log <='".addslashes($echeance)."'";
			mysql_query($req,$dbh);
		}
	}
	
	/**
	 * Fonction qui permet d'extraire un lot de log depuis la date de dernière consolidation
	 */	
	function calculer_since_last(){
		global $dbh;
		
		for($i=0;$i<sizeof($this->list_idview);$i++){
			$id_vue=$this->list_idview[$i];
			$req_vue = "select max(date_consolidation) from statopac_vues where id_vue='".addslashes($id_vue)."'";
			$res_vue = mysql_query($req_vue,$dbh);
			if($res_vue) $date_max = mysql_result($res_vue,0,0);
			
			$req = "create temporary table logs_filtre_$id_vue select * from statopac where date_log between '".addslashes($date_max)."' and now()";
			mysql_query($req,$dbh);
		}		
	}
	
	/**
	 * Fonction qui créée les tables dynamiques consolidées
	 */
	function consolider(){
		global $dbh, $tab_val, $liste_tabfiltre, $pmb_set_time_limit ;
		
		set_time_limit($pmb_set_time_limit);
		
		$this->show_progress_bar();
		
		for($i=0;$i<sizeof($this->list_idview);$i++){
			$nom_vue=mysql_result(mysql_query("select nom_vue from statopac_vues where id_vue=".$this->list_idview[$i]),0,0);
			$this->set_progress_text(" $nom_vue : ");
			$this->set_progress_percent(0);
			//Consultation de la table temporaire
			$id_vue=$this->list_idview[$i];
			$rqt_tempo="SELECT * from logs_filtre_$id_vue";
			$res_tempo=mysql_query($rqt_tempo, $dbh);
			$n_total=mysql_num_rows($res_tempo);
			//Test pour savoir si la structure des colonnes a été modifiée
			$rqt_sum="select sum(maj_flag) from statopac_vues_col where num_vue=$id_vue";
			$res_sum=mysql_query($rqt_sum);
			$flag = mysql_result($res_sum,0,0);
			if($this->mode == DEFAULT_CONSO){
				//On crée la table de consolidation associée dynamiquement
				if($flag){
					$rqt_trunc = "DROP TABLE  statopac_vue_".addslashes($id_vue);
					@mysql_query($rqt_trunc, $dbh);
				}
				$rqt_create = "CREATE TABLE IF NOT EXISTS statopac_vue_".addslashes($id_vue)." (id_ligne INT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY)";
				mysql_query($rqt_create, $dbh);	
			} else {
				//On vide la table dynamique si elle existe
				if($flag)
					$rqt_trunc = "DROP TABLE  statopac_vue_".addslashes($id_vue);
				else $rqt_trunc = "TRUNCATE TABLE  statopac_vue_".addslashes($id_vue);
				@mysql_query($rqt_trunc, $dbh);
				$rqt_create = "CREATE TABLE IF NOT EXISTS statopac_vue_".addslashes($id_vue)." (id_ligne INT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY)";
				mysql_query($rqt_create, $dbh);	
			} 
			
			// création des colonnes de la table de la vue
			$rqt_col = "select id_col, nom_col, expression, filtre, datatype from statopac_vues_col where num_vue='".addslashes($id_vue)."'";
			$res_col=mysql_query($rqt_col, $dbh);
			$cols_vue=array();
			while(($col=mysql_fetch_object($res_col))){			
				//On ajoute les champs (indicateurs)
				$cols_vue[]=$col;
				if($col->datatype == 'small_text')
					$type_col = 'varchar(255)';
				else $type_col = $col->datatype; 
				$rqt_addfield = "ALTER TABLE statopac_vue_".addslashes($id_vue)." ADD ".addslashes(trim($col->nom_col))." ".addslashes($type_col)." NOT NULL";
				mysql_query($rqt_addfield);
			}		
			
			$tab_val =array();
			$liste_tabfiltre = array();
			$n=0;
			while(($ligne=mysql_fetch_array($res_tempo))){
				
				$percent=round(($n/$n_total)*100);
				if ($percent_conserve!=$percent) { // $percent%5==0 && 
					$this->set_progress_percent($percent);
					$percent_conserve=$percent;
				}
				$n++;
				
				$resultat =array();
				
				foreach ($cols_vue as $col) {			
					
					// si filtre, pour chaque ligne de log :
					if($col->filtre){
						$rqt_create = "CREATE TEMPORARY TABLE  filtre_".$ligne[0]."_".$col->id_col." (
							`id_log` int( 8 ) unsigned NOT NULL AUTO_INCREMENT ,
							`date_log` timestamp NOT NULL default CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
							`url_demandee` varchar( 255 ) NOT NULL default '',
							`url_referente` varchar( 255 ) NOT NULL default '',
							`get_log` blob NOT NULL ,
							`post_log` blob NOT NULL ,
							`num_session` varchar( 255 ) NOT NULL default '',
							`server_log` blob NOT NULL ,
							`empr_carac` blob NOT NULL ,
							`empr_doc` blob NOT NULL ,
							`empr_expl` blob NOT NULL ,
							`nb_result` blob NOT NULL ,
							 `gen_stat` blob NOT NULL ,
							PRIMARY KEY ( `id_log` )
						)";
						mysql_query($rqt_create, $dbh);
						$parser=new parse_format('consolidation.inc.php');										
						$parser->cmd = $col->filtre ;
						$parser->environnement['tempo']="logs_filtre_$id_vue";
						$parser->environnement['num_ligne']=$ligne[0];
						$print_format->environnement['ligne']=$ligne;
						$val_filtre = $parser->exec_cmd_conso();
						$filtre_tab = $this->creer_filtre($col->filtre,$val_filtre,$id_vue,$ligne[0],"filtre_".$ligne[0]."_".$col->id_col);
					}	
					
					$print_format=new parse_format('consolidation.inc.php');										
					$print_format->cmd = $col->expression;
					$print_format->environnement['tempo']="logs_filtre_$id_vue";
					$print_format->environnement['num_ligne']=$ligne[0];
					$print_format->environnement['ligne']=$ligne;
					if($col->filtre)$print_format->environnement['filtre']= $filtre_tab;
					$resultat[$col->nom_col] = $print_format->exec_cmd_conso();
					
				}

				$champ="";
				$values="";	
				foreach($resultat as $cle_col=>$valeur){
					$champ .= ($champ ? ','.addslashes($cle_col) : addslashes($cle_col));
					$values .= ($values ? ',\''.addslashes($valeur).'\'' : '\''.addslashes($valeur).'\'');
				}
				mysql_query("insert into  statopac_vue_".addslashes($id_vue)." ($champ) values ($values)", $dbh);			
			}
			
			//On supprime les tables de filtres
			foreach ($liste_tabfiltre as $key=>$val){
				mysql_query("DROP TABLE ".$val);
			}
			
			mysql_query("UPDATE statopac_vues  SET date_consolidation=now() WHERE id_vue='".addslashes($id_vue)."'");
			if($flag) mysql_query("UPDATE statopac_vues_col  SET maj_flag=0 WHERE num_vue='".addslashes($id_vue)."'");
			
		}
	}
	
	/**
	 * Fonction qui permet de créer un filtre de résultat par rapport à une valeur 
	 */
	function creer_filtre($filtre,$valeur_filtre,$id_vue,$ligne_repere,$table){
		global $dbh,$tab_val, $liste_tabfiltre;
		
		$table_filtre ="";
		foreach($tab_val as $key=>$val){
			if($filtre == $val['filtre']){ 
				if($valeur_filtre == $val['valeur_filtre']){
					$table_filtre = $val['table_filtre'];
					 mysql_query("DROP TABLE ".$table);
					break;
				} else {
					mysql_query("DROP TABLE ".$val['table_filtre']);
					$ind=array_search($val['table_filtre'],$liste_tabfiltre) ;
					if($ind != false)
						unset($liste_tabfiltre[$ind]);	
					unset($tab_val[$key]);
				}
			} 				
		}
		if(!$table_filtre){
			$liste_tabfiltre[] = $table;
			$rqt="SELECT * from logs_filtre_$id_vue";
			$res=mysql_query($rqt, $dbh);
		
			while($ligne_log=mysql_fetch_object($res)) {
			
				$format=new parse_format('consolidation.inc.php');	
				$format->environnement['tempo']="logs_filtre_$id_vue";
				$format->environnement['num_ligne']=$ligne_log->id_log;									
				$format->cmd = $filtre;
				$val_filtre_courant = $format->exec_cmd_conso();
				if($val_filtre_courant == $valeur_filtre){				
					$rqt="insert ignore into ".$table."  select * from logs_filtre_$id_vue where id_log='".addslashes($ligne_log->id_log)."'";
					mysql_query($rqt,$dbh);				
				}
			}				
		}
		if(!$valeur_filtre) 
			$valeur_filtre="no_value";
		if(!$table_filtre)
			$tab_val[] = array('valeur_filtre' => $valeur_filtre, 'filtre' => $filtre, 'table_filtre' => $table);	
			
		return ($table_filtre ? $table_filtre : $table);
	}
	
	function show_progress_bar(){
		print "<div class='row' style='text-align:center; width:80%; border: 1px solid #000000; padding: 4px;'>
			<div style='text-align:left; width:100%; height:16px;'>
				<img id='progress' src='images/jauge.png' style='width:1px; height:16px'/>
			</div>
			<div style='text-align:center'>
				<span id='progress_text'></span>&nbsp;
				<span id='progress_percent'></span>
			</div>
		</div>";
		flush();
	}
	
	function init_progress_bar() {
		print "<script>document.getElementById('progress').src='images/jauge.png'</script>";
		flush();
	}
	
	function set_progress_percent($percent) {
		print "<script>document.getElementById('progress').style.width='$percent%';
				document.getElementById('progress_percent').innerHTML='$percent%';
		</script>";
		flush();
	}
	
	function set_progress_text($text){
		global $charset;
		print "<script>document.getElementById('progress_text').innerHTML='".htmlentities($text,ENT_QUOTES,$charset)."';</script>";
		flush();
	}
}
?>