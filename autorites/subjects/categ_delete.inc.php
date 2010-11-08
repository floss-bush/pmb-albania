<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: categ_delete.inc.php,v 1.11 2009-05-16 11:12:03 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/category.class.php");
require_once ("$class_path/noeuds.class.php");


if (noeuds::hasChild($id)) {

	error_message($msg[321], $msg[322], 1, "./autorites.php?categ=categories&id=$id&sub=categ_form&parent=$parent");
	exit();
	
} elseif (noeuds::isTarget($id)){
	
	error_message($msg[321], $msg[thes_suppr_impossible_renvoi_voir], 1, "./autorites.php?categ=categories&id=$id&sub=categ_form&parent=$parent");
	exit();

} elseif (noeuds::isProtected($id)) {
	
	error_message($msg[321], $msg[thes_suppr_impossible_protege], 1, "./autorites.php?categ=categories&id=$id&sub=categ_form&parent=$parent");
	exit();
	
} elseif (noeuds::isUsedInNotices($id)) {
	if ($forcage == 1) {
		$tab= unserialize( urldecode($ret_url) );
		foreach($tab->GET as $key => $val){
			$GLOBALS[$key] = $val;	    
		}	
		foreach($tab->POST as $key => $val){
			$GLOBALS[$key] = $val;
		}
		$requete="DELETE FROM notices_categories WHERE num_noeud=".$id;
		mysql_query($requete, $dbh);
		noeuds::delete($id);
	}  else {			
		$requete="SELECT notcateg_notice FROM notices_categories WHERE num_noeud=".$id." ORDER BY ordre_categorie";
		$result_cat=mysql_query($requete, $dbh);
		if (mysql_num_rows($result_cat)) {
			//affichage de l'erreur, en passant tous les param postés (serialise) pour l'éventuel forcage 	
			$tab->POST = $_POST;
			$tab->GET = $_GET;
			$ret_url= urlencode(serialize($tab));
			require_once("$class_path/mono_display.class.php");
			require_once("$class_path/serial_display.class.php");
		   
			print "
				<br /><div class='erreur'>$msg[540]</div>
				<script type='text/javascript' src='./javascript/tablist.js'></script>
				<script>
					function confirm_delete() {
						phrase = \"{$msg[autorite_confirm_suppr_categ]}\";
						result = confirm(phrase);
						if(result) form.submit();
					}	
				</script>
				<div class='row'>
					<div class='colonne10'>
						<img src='./images/error.gif' align='left'>
					</div>
					<div class='colonne80'>
						<strong>".$msg["autorite_suppr_categ_titre"]."</strong>
					</div>
				</div>
				<div class='row'>
					<form class='form-$current_module' name='dummy'  method='post' action='./autorites.php?categ=categories&sub=delete&parent=$parent&id=$id'>					
						<input type='hidden' name='forcage' value='1'>
						<input type='hidden' name='ret_url' value='$ret_url'>
						<input type='button' name='ok' class='bouton' value=' $msg[89] ' onClick='history.go(-1);'>
						<input type='submit' class='bouton' name='bt_forcage' value=' ".htmlentities($msg["autorite_suppr_categ_forcage_button"], ENT_QUOTES)." '  onClick=\"confirm_delete();return false;\">
					</form>				
				</div>";
			while (($r_cat=mysql_fetch_object($result_cat))) {
				$requete="select signature, niveau_biblio ,notice_id from notices where notice_id=".$r_cat->notcateg_notice." limit 20";
				$result=mysql_query($requete, $dbh);	
				if (($r=mysql_fetch_object($result))) {

					if($r->niveau_biblio != 's' && $r->niveau_biblio != 'a') {
						// notice de monographie
						$nt = new mono_display($r->notice_id);
					} else {
						// on a affaire à un périodique
						$nt = new serial_display($r->notice_id,1);
					}
					echo "
						<div class='row'>
						$nt->result
				 	    </div>";
				}	
				echo "<script type='text/javascript'>document.forms['dummy'].elements['ok'].focus();</script>";	
			}
			exit();
		}	
	}	
//	error_message($msg[321], $msg[categ_delete_used], 1, "./autorites.php?categ=categories&id=$id&sub=categ_form&parent=$parent");
//	exit();
	
} else {
	
	noeuds::delete($id);
}

include('./autorites/subjects/default.inc.php');

?>
