<?php
// +-------------------------------------------------+
// ï¿½ 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: search.class.php,v 1.17 2010-08-27 07:48:08 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

global $class_path,$include_path,$base_path;
require_once($class_path."/connecteurs.class.php");

//Classe de gestion de la recherche spï¿½cial "combine"

class external_sources {
	var $id;
	var $n_ligne;		//Numero de ligne du critere dans la multi-critere
	var $params;		//
	var $search;		//Classe d'origine de la recherche

	//Constructeur
    function external_sources($id,$n_ligne,$params,&$search) {
    	$this->id=$id;
    	$this->n_ligne=$n_ligne;
    	$this->params=$params;
    	$this->search=&$search;
    }
    
    //fonction de recuperation des operateurs disponibles pour ce champ spï¿½cial (renvoie un tableau d'opï¿½rateurs)
    function get_op() {
    	$operators = array();
    	$operators["EQ"]="=";
    	return $operators;
    }
    
    //fonction de recuperation de l'affichage de la saisie du critï¿½re
    function get_input_box() {
    	global $msg,$charset;
    	
    	//Recuperation de la valeur de saisie
    	$valeur_="field_".$this->n_ligne."_s_".$this->id;
    	global $$valeur_;
    	$valeur=$$valeur_;
    	
    	if ((!$valeur)&&($_SESSION["checked_sources"])) $valeur=$_SESSION["checked_sources"];
    	if (!is_array($valeur)) $valeur=array();
    	
    	//Recherche des sources
    	$requete="SELECT connectors_categ_sources.num_categ, connectors_sources.source_id, connectors_categ.connectors_categ_name as categ_name, connectors_sources.name, connectors_sources.comment, connectors_sources.repository, connectors_sources.opac_allowed, source_sync.cancel FROM connectors_sources LEFT JOIN connectors_categ_sources ON (connectors_categ_sources.num_source = connectors_sources.source_id) LEFT JOIN connectors_categ ON (connectors_categ.connectors_categ_id = connectors_categ_sources.num_categ) LEFT JOIN source_sync ON (connectors_sources.source_id = source_sync.source_id AND connectors_sources.repository=2) ORDER BY connectors_categ_sources.num_categ DESC, connectors_sources.name";
    	$resultat=mysql_query($requete);
    	$r="<select name='field_".$this->n_ligne."_s_".$this->id."[]' multiple='yes'>";
    	$current_categ=0;
    	$count = 0;
    	while ($source=mysql_fetch_object($resultat)) {
    		if ($current_categ !== $source->num_categ) {
    			$current_categ = $source->num_categ;
    			$source->categ_name = $source->categ_name ? $source->categ_name : $msg["source_no_category"];
    			$r .= "<optgroup label='".$source->categ_name."'>";
    			$count++;
    		}
    		$r.="<option id='op_".$source->source_id."_".$count."' value='".$source->source_id."'".(array_search($source->source_id,$valeur)!==false?" selected":"").">".htmlentities($source->name.($source->comment?" : ".$source->comment:""),ENT_QUOTES,$charset)."</option>\n";
    	}
    	$r.="</select>";
    	return $r;
    }
    
    //fonction de conversion de la saisie en quelque chose de compatible avec l'environnement
    function transform_input() {
    }
    
    //fonction de creation de la requete (retourne une table temporaire)
    function make_search() {	
    	global $selected_sources;
    	global $search;
    	global $msg;
    	
    	$error_messages = array();
    	
    	//On modifie l'operateur suivant !!
    	$inter_next="inter_".($this->n_ligne+1)."_".$search[$this->n_ligne+1];
    	global $$inter_next;
    	if ($$inter_next) $$inter_next="or";
    	
    	//Recuperation de la valeur de saisie
    	$valeur_="field_".$this->n_ligne."_s_".$this->id;
    	global $$valeur_;
    	$valeur=$$valeur_;
    	global $charset, $class_path,$include_path,$base_path;
    	
    	//Override le timeout du serveur mysql, pour être sûr que le socket dure assez longtemps pour aller jusqu'aux ajouts des résultats dans la base. 
		$sql = "set wait_timeout = 300";
		mysql_query($sql);
    	
    	$conn=new connecteurs();
    	
    	for ($i=0; $i<count($valeur); $i++) {
    		//Recherche de la source
    		$source=$conn->get_class_name($valeur[$i]);
    		require_once($base_path."/admin/connecteurs/in/$source/$source.class.php");
    		eval("\$src=new $source(\"".$base_path."/admin/connecteurs/in/".$source."\");");
    		$params=$src->get_source_params($valeur[$i]);
    		if ($params["REPOSITORY"]==2) {
    			$source_id=$valeur[$i];
    			$source_name_sql = "SELECT name FROM connectors_sources WHERE source_id = ".addslashes($source_id);
    			$source_name = mysql_result(mysql_query($source_name_sql), 0, 0);

    			$unimarc_query=$this->search->make_unimarc_query();
    			$search_id=md5(serialize($unimarc_query));

				//Suppression des vieilles notices
				//Vérification du ttl
				$ttl=$params["TTL"];
				$requete="delete from entrepot_source_$source_id where unix_timestamp(now())-unix_timestamp(date_import)>".$ttl.';';
				mysql_query($requete);

    			$requete="select count(1) from entrepot_source_$source_id where search_id='".addslashes($search_id)."'";
				$resultat=mysql_query($requete);
				$search_exists=mysql_result($resultat,0,0);

				$requete="select count(1) from entrepot_source_$source_id where search_id='".addslashes($search_id)."' and unix_timestamp(now())-unix_timestamp(date_import)>".$ttl;
				$resultat=mysql_query($requete);
				if ((mysql_result($resultat,0,0)) || ((!mysql_result($resultat,0,0))&&(!$search_exists))) {
					//Recherche si on a le droit
					$flag_search=true;
					$requete="select (unix_timestamp(now())-unix_timestamp(date_sync)) as sec from source_sync where source_id=$source_id";
					$res_sync=mysql_query($requete);
					if (mysql_num_rows($res_sync)) {
						$rsync=mysql_fetch_object($res_sync);
						if ($rsync->sec>300) {
							mysql_query("delete from source_sync where source_id=".$source_id);
						} else $flag_search=false;
					}
					if ($flag_search) {
						$flag_error=false;
						for ($j=0; $j<$params["RETRY"]; $j++) {
    						$src->search($valeur[$i],$unimarc_query,$search_id);
    						if (!$src->error) 
    							break; 
    						else { 
    							$flag_error=true; 
    							$error_messages[$source_name][] = $src->error_message; 
								/*print $src->error_message."<br />"*/;
    						}
						}
						//Il y a eu trois essais infructueux, on dï¿½sactive pendant 5 min !!
						if ($flag_error) {
							mysql_query("insert into source_sync (source_id,date_sync,cancel) values($source_id,now(),2)");
							$error_messages[$source_name][] = sprintf($msg["externalsource_isblocked"], date("H:i", time() + 5*60));
						}
					}
    			}
    		}
       	}
       	
	    if ($error_messages) {
			echo '<div class="external_error_messages">'.$msg["externalsource_error"].": ";
			foreach ($error_messages as $aname => $aerror_messages) {
				$aerror_messages = array_unique($aerror_messages);
				print '<span style="border-bottom: 1px dotted" title="'.implode($aerror_messages, ", ").'">'.$aname.'</span>';
				print "&nbsp;";
			}
			echo '</div>';
		}
       	
       	//Sources
       	$tvaleur=array();
       	for ($i=0; $i<count($valeur); $i++) {
       			$tvaleur[]=$valeur[$i];
       	}
       	$selected_sources=implode(",",$tvaleur);
    	$t_table="t_sources_".$this->n_ligne;
    	//$requete="create temporary table ".$t_table." select distinct recid as notice_id from entrepots where source_id in (".implode(",",$valeur).")";
    	$requete="create temporary table ".$t_table." (notice_id integer unsigned not null)";
    	mysql_query($requete);
		return $t_table; 
    }
    
    //fonction de traduction litterale de la requete effectuee (renvoie un tableau des termes saisis)
    function make_human_query() {
    	global $msg;
    	global $include_path;
    	
    	$litteral=array();
    	
    	//Rï¿½cupï¿½ration de la valeur de saisie 
    	$valeur_="field_".$this->n_ligne."_s_".$this->id;
    	global $$valeur_;
    	$valeur=$$valeur_;
    	
    	$requete="select name from connectors_sources where source_id in (".implode(",",$valeur).")";
    	$resultat=mysql_query($requete);
    	while ($r=mysql_fetch_object($resultat)) {
    		$litteral[]=$r->name;
    	}	
		return $litteral;    
    }
     
    function make_unimarc_query() {
    	return array();
    }
    
	//fonction de vï¿½rification du champ saisi ou sï¿½lectionnï¿½
    function is_empty($valeur) {
    	if (count($valeur)) return false; else return true;
    }
}
?>