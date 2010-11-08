<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sync.inc.php,v 1.8 2009-07-21 12:42:31 erwanmartin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

@set_time_limit(0);
//@register_shutdown_function("cancel_sync");
//ignore_user_abort(true);

/*
Cette page ne fait pas la synchro, elle génère une requete ajax qui elle la fera
* */

$conn->get_sources();
print "<div style='width:710px'>
<div class='row' style='text-align:center'><h3>Synchronisation de ".$conn->sources[$source_id]["NAME"]."</h3></div>
<div class='row' style='border:#000000 1px solid;padding:4px'>
<div class='row' style='text-align:center'>Progression : <span id='percent'>0%</span></div>
<div class='row' style='text-align:left'><img src='images/jauge.png' width='1%' height='16px' id='progress_bar'/><div class='row'>
<div class='row' style='text-align:center'>Nb enregistrements reçus : <span id='nlu'>&nbsp;</span>/Nb total : <span id='ntotal'>&nbsp;</span><div class='row'>
<div class='row' style='text-align:center' id='sync_message'></div>
<div id='erreurpos'></div>
</div>";

//Vérification qu'il n'y a pas de synchronisation en cours...
$is_already_sync=false;
$recover_env="";
$recover=false;
$requete="select * from source_sync where source_id=$source_id";
$resultat=mysql_query($requete);
if (mysql_num_rows($resultat)) {
	$rs_s=mysql_fetch_object($resultat);
	if (!$rs_s->cancel) {
		print "<div class='row' style='text-align:center'><div class='erreur'>".$msg["connecteurs_sync_currentexists"]."</div>";
		$is_already_sync=true;
	} else {
		$recover=true;
		$recover_env=$rs_s->env;
		$env = array();
		print "<div class='row' style='text-align:center'><div class='erreur'>".$msg["connecteurs_sync_resuming"]."</div>";
	}
} 
else {
	if (isset($_GET["env"]))
		$env = unserialize($_GET["env"]);
	else
		$env = $conn->get_maj_environnement($source_id);
	
	if (isset($_GET["converted"]))
		$env["converted"] = 1;
	if (isset($_GET["outputtype"]))
		$env["outputtype"] = $_GET["outputtype"];
	if (isset($_GET["suffix"]))
		$env["suffix"] = $_GET["suffix"];
}
//Le bouton annuler abort la requète de synchro et en génère une autre synchrone cette fois qui fait l'annulation.
print "<div class='row' style='text-align:center'><input type='button' id='cancel_sync' class='bouton' value ='Annuler' onClick='document.getElementById(\"sync_message\").innerHTML=\"<blink>Annulation en cours...</blink>\"; request.abort();abort_request(); document.location=\"admin.php?categ=connecteurs&sub=in\";'/><br /><input type='button' style='visibility:hidden;' id='get_back' class='bouton' value ='".$msg["654"]."' onClick='document.location=\"admin.php?categ=connecteurs&sub=in\";'/></div>
</div>";

//highlight_string(print_r($env, true));

?>
<script language="javascript" type="text/javascript">
	function abort_request() {
		//Envoi une requete synchrone pour annuler la synchro en cours
		var request = false;
		   try {
		     request = new XMLHttpRequest();
		   } catch (trymicrosoft) {
		     try {
		       request = new ActiveXObject("Msxml2.XMLHTTP");
		     } catch (othermicrosoft) {
		       try {
		         request = new ActiveXObject("Microsoft.XMLHTTP");
		       } catch (failed) {
		         request = false;
		       }  
		     }
		   }

		   if (!request)
		     alert("Error initializing XMLHttpRequest!");

	     var url = "ajax.php?module=admin&categ=sync&cancel=1&id=<?php echo $id;?>&source_id=<?php echo $source_id; ?>";
	     request.open("GET", url, false);
	     request.send(null);
	}

	//Génère la requete de synchronisation:
	
	var request = false;
   try {
     request = new XMLHttpRequest();
   } catch (trymicrosoft) {
     try {
       request = new ActiveXObject("Msxml2.XMLHTTP");
     } catch (othermicrosoft) {
       try {
         request = new ActiveXObject("Microsoft.XMLHTTP");
       } catch (failed) {
         request = false;
       }  
     }
   }

   function updatePage() {
	   var IE6 = false /*@cc_on || @_jscript_version < 5.7 @*/;
   		if (IE6 && (request.readyState == 3)) {
	   		return;
   		}
   		
   		if ((request.readyState > 2)) {
	        if (request.status == 200) {
		 		var serverResponse = request.responseText;
//		 		alert(serverResponse);
  			    eval(serverResponse);
	        }
	   	}
   }
   
   if (!request)
     alert("Error initializing XMLHttpRequest!");

   	 var url = "ajax.php?module=admin&categ=sync&id=<?php echo $id;?>&source_id=<?php echo $source_id; ?>&env=<?php echo urlencode(serialize($env))?>";
     request.open("GET", url, true);
     request.onreadystatechange = updatePage;
     //Il faut mettre ces lignes sinon sous ie6 on obtient une "unspecified error"
     if(!document.all) {
    	 request.setRequestHeader("Connection", "close");
     }
     request.send(null);
</script>
<?



?>
