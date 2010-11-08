<?php
// +-------------------------------------------------+
// � 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: external_common.inc.php,v 1.9 2009-11-17 20:19:21 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//Enregistrement du type de recherche externe avant toute chose pour quye le message soit bon dans le formulaire template
if ($external_type) $_SESSION["ext_type"]=$external_type;
if (!$_SESSION["ext_type"]) $_SESSION["ext_type"]="simple";

require_once($class_path."/search.class.php");
require_once($class_path."/searcher.class.php");
require_once($class_path."/mono_display_unimarc.class.php");
require_once($include_path."/external.inc.php");
require_once($class_path."/z3950_notice.class.php");

function decale($var,$var1) {
	global $$var;
	global $$var1;
	$$var1=$$var;
}

function do_sources() {
	global $charset,$source,$msg,$first;
	$r="<script>
		function debloque(source_id) {
				for(i=0; i<document.search_form.elements.length; i++)
				{
					if(document.search_form.elements[i].name == 'source[]')	{
						if (document.search_form.elements[i].value == source_id) {
							document.search_form.elements[i].checked = true;
							document.getElementById('debloque_source_'+source_id).value='1';
							document.getElementById('spandebloque_'+document.search_form.elements[i].id).style.display='none';
							document.getElementById('label_'+document.search_form.elements[i].id).className='';
						}
					}
				}	
		}
	</script>
	<!-- <script type='text/javascript' src='./javascript/tabform.js'></script> -->
	<script type=\"text/javascript\" src=\"./javascript/tablist.js\"></script>
	";
	
	$r.="<div class='row'>
	<a href=\"javascript:expandAll(); rec_expand_collapse('expand');\"><img src='./images/expand_all.gif' border='0' id=\"expandall\"></a>

	<a href=\"javascript:collapseAll(); rec_expand_collapse('collapse');\"><img src='./images/collapse_all.gif' border='0' id=\"collapseall\"></a>	<input type='hidden' name='b_level' value='m' />
	<input type='hidden' name='h_level' value='0' />
	<input type='hidden' name='first' value='1'/>
	</div>";

    
	//Recherche des sources
    $requete="SELECT connectors_categ_sources.num_categ, connectors_sources.source_id, connectors_categ.connectors_categ_name as categ_name, connectors_sources.name, connectors_sources.comment, connectors_sources.repository, connectors_sources.opac_allowed, source_sync.cancel FROM connectors_sources LEFT JOIN connectors_categ_sources ON (connectors_categ_sources.num_source = connectors_sources.source_id) LEFT JOIN connectors_categ ON (connectors_categ.connectors_categ_id = connectors_categ_sources.num_categ) LEFT JOIN source_sync ON (connectors_sources.source_id = source_sync.source_id AND connectors_sources.repository=2) ORDER BY connectors_categ_sources.num_categ DESC, connectors_sources.name ";

    $resultat=mysql_query($requete);
    if ($source) $_SESSION["checked_sources"]=$source;
    if ($_SESSION["checked_sources"]&&(!$source)) $source=$_SESSION["checked_sources"];
    if (!is_array($source)) $source=array();
    
    $old_categ = 0;
    $r .= "<div>";
    $count=0;
    $debloque_form_outputed = array();
    while ($srce=mysql_fetch_object($resultat)) {
    	
    	if ($old_categ !== $srce->num_categ) {
    		$count++;
    		$old_categ = $srce->num_categ;
    		if (!$srce->categ_name)
    			$srce->categ_name = $msg["source_no_category"];
    		$open="open_".$count;
    		global $$open;
    		if ((!$first)&&($_SESSION["sources_open_".$count])) $$open=1; else if ($first) $_SESSION["sources_open_".$count]=$$open;
    		$img_plus=$$open?"./images/minus.gif":"./images/plus.gif";
			$r .= '</div><div id="elconn'.$count.'Parent" class="parent" width="100%">
			<h3>
			<img src="'.$img_plus.'" class="img_plus" name="imEx" id="elconn'.$count.'Img" title="'.$msg["connector_external_plus_detail"].'" border="0" onClick="expandBase(\'elconn'.$count.'\', true); if (document.getElementById(\'elconn'.$count.'Child\').style.display==\'none\') document.search_form.open_'.$count.'.value=0; else  document.search_form.open_'.$count.'.value=1; return false;" hspace="3">&nbsp;
			'.$srce->categ_name.'
			</h3>
			</div><div id=\'elconn'.$count.'Child\' class=\'child\' '.($$open?"startOpen='Yes'":"").' style=\'display:none\'><input type="hidden" name="open_'.$count.'" id="open_'.$count.'" value="'.$$open.'"/>';
    	}
    	
   		$r.="<div style='width:33%; float:left'>
				<input type='checkbox' name='source[]' value='".$srce->source_id."' id='source_".$srce->source_id."_".$count."' onclick='change_source_checkbox(source_".$srce->source_id."_".$count.", ".$srce->source_id.");'";
   		if ((array_search($srce->source_id,$source)!==false)&&($srce->cancel!=2)) {
   			$r.=" checked";
   		}
   		$r.="/><label for='source_".$srce->source_id."_".$count."' ".($srce->cancel==2?"class='erreur'":"")." id='label_source_".$srce->source_id."_".$count."'><img src='images/".($srce->repository==1?"entrepot.png":"globe.gif")."'/>&nbsp;".htmlentities($srce->name.($srce->comment?" : ".$srce->comment:""),ENT_QUOTES,$charset);
   		$r .= "</label>";
   		if ($srce->cancel==2) {
   			if (!isset($debloque_form_outputed[$srce->source_id])) {
   				$r.=" <input type='hidden' name='debloque_source_".$srce->source_id."' id='debloque_source_".$srce->source_id."' value='0'/>";
   				$debloque_form_outputed[$srce->source_id] = true;   				
   			}
   			$r.=" <span id='spandebloque_source_".$srce->source_id."_".$count."'>(<a href='#' onClick='debloque(".$srce->source_id.");'>".$msg["connecteurs_debloque"]."</a>)</span>";
   		}
   		$r.="</div>";
    }
   	$r.="</div><div class='row'></div>";
   	if ($count) {
   		$r.="
   		<script type=\"text/javascript\">
			function rec_expand_collapse(type) {
				var i;
				for (i=1; i<=$count; i++) {
					if (type=='expand') {
						document.getElementById('open_'+i).value=1;
   					} else {
   						document.getElementById('open_'+i).value=0;
   					}
				}
			}
		</script>";
   	}
   	return $r;
}

//Instanciation de la classe de recherche
//Si c'est une multi
if ($_SESSION["ext_type"]=="multi") {
	$sc=new search(false,"search_fields_unimarc");
} else {
	$sc=new search(false,"search_simple_fields_unimarc");
}

//Si c'est une simple 
if ($_SESSION["ext_type"]=="simple") {
	//Si �a vient d'une autre recherche, on transforme !
 	if ((string)$from_mode!="") {
 		//R�cup�ration des variables
 		switch ($from_mode) {
 			case "0":
 				if ($code) {
 					$op_="STARTWITH";
					
					$search[0]="f_20";
					//op�rateur
		    		$op="op_0_".$search[0];
		    		global $$op;
		    		$$op=$op_;
		    		    			
		    		//contenu de la recherche
		    		$field="field_0_".$search[0];
		    		$field_=array();
		    		$field_[0]=$code;
		    		global $$field;
		    		$$field=$field_;
		    	    	
		    		//op�rateur inter-champ
		    		$inter="inter_0_".$search[0];
		    		global $$inter;
		    		$$inter="";
		    			    		
		    		//variables auxiliaires
		    		$fieldvar_="fieldvar_0_".$search[0];
		    		global $$fieldvar_;
		    		$$fieldvar_="";
		    		$fieldvar=$$fieldvar_;
 				} else searcher_title::convert_simple_multi_unimarc($_SESSION["CURRENT"]);
 				break;
			case "1":
				searcher_subject::convert_simple_multi_unimarc($_SESSION["CURRENT"]);
				break;
			case "3":
				searcher_publisher::convert_simple_multi_unimarc($_SESSION["CURRENT"]);
				break;
 		}
 	} else {
		if ($external_env) {
			$external_env=unserialize(stripslashes($external_env));
			foreach ($external_env as $varname=>$varvalue) {
				global $$varname;
				$$varname=$varvalue;
			}
		}
 	}
} else {
	if ($from_mode==6) {
		//R�cup�ration de l'environnement
		$search=$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["POST"]["search"];
   		//Pour chaque champ
   		for ($i=0; $i<count($search); $i++) {
	   	 	//R�cup�ration de l'op�rateur
	   	 	$op="op_".$i."_".$search[$i];
	   	 	global $$op;
	   	 	$$op=$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["POST"][$op];
	   	 			    			
	    	//R�cup�ration du contenu de la recherche
	    	$field_="field_".$i."_".$search[$i];
	    	global $$field_;
	    	$$field_=$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["POST"][$field_];
	    	$field=$$field_;
	    	
	    	//R�cup�ration de l'op�rateur inter-champ
	    	$inter="inter_".$i."_".$search[$i];
	    	global $$inter;
	    	$$inter=$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["POST"][$inter];
	    	    		
	    	//R�cup�ration des variables auxiliaires
	    	$fieldvar_="fieldvar_".$i."_".$search[$i];
	    	global $$fieldvar_;
	    	$$fieldvar_=$_SESSION["session_history"][$_SESSION["CURRENT"]]["QUERY"]["POST"][$fieldvar_];
	    	$fieldvar=$$fieldvar_;
	    }
	}
}

if ($serialized_search) {
	$sc->unserialize_search(stripslashes($serialized_search));
}
?>
