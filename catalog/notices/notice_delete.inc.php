<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: notice_delete.inc.php,v 1.34 2011-03-29 08:08:26 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");


//verification des droits de modification notice
$acces_m=1;
if ($id!=0 && $gestion_acces_active==1 && $gestion_acces_user_notice==1) {
	require_once("$class_path/acces.class.php");
	$ac= new acces();
	$dom_1= $ac->setDomain(1);
	$acces_m = $dom_1->getRights($PMBuserid,$id,8);
}

if ($acces_m==0) {
	
	error_message('', htmlentities($dom_1->getComment('mod_noti_error'), ENT_QUOTES, $charset), 1, '');
	
} else {
	
	require_once($class_path."/parametres_perso.class.php");
	
	// suppression d'une notice
	print "<div class='row'><h1>{$msg[416]}</h1></div>";
	if($id) {
		$query = "select count(1) as qte from exemplaires where expl_notice=$id";
		$result = mysql_query($query, $dbh);
		$expl = mysql_result($result, 0, 0); 
		if($expl) {
			// il y a des exemplaires : impossible de supprimer cette notice
			error_message($msg[416], $msg[420], 1, "./catalog.php?categ=isbd&id=$id");
		} else {
			$query="select count(*) as qte from notices_relations where linked_notice=$id";
			// SUPPRIME DU WHERE : num_notice=$id or 
			$result = mysql_query($query, $dbh);
			$notc = mysql_result($result, 0, 0); 
			if ($notc) {
				error_message($msg[416], $msg["notice_parent_used"], 1, "./catalog.php?categ=isbd&id=$id");
			} else {
				$query = "select count(1) from demandes where num_notice=$id";
				$result = mysql_query($query, $dbh);
				$dmde = mysql_result($result, 0, 0); 
				if($dmde) 
					error_message($msg[416], $msg["notice_demande_used"], 1, "./catalog.php?categ=isbd&id=$id");
				else {
					$abort_delete = 0;
					$query = "select count(1) as qte, name from caddie_content, caddie where type='NOTI' and object_id='$id' and caddie_id=idcaddie group by name";
					$result = mysql_query($query, $dbh);
					$caddie = @mysql_result($result, 0, 0);
					// La notice est au moins dans un caddie
					if ($caddie) {
						$abort_delete = 1;
						switch ($pmb_confirm_delete_from_caddie) {
							case 0: //On interdit
								$name = mysql_result($result, 0, 'name'); 
								error_message($msg[416], $msg['suppr_notice_dans_caddie'].$name, 1, "./catalog.php?categ=isbd&id=$id");							
								break;
							case 1: //
								$abort_delete = 0;
								break;
							case 2:
								if (isset($caddie_confirmation) && $caddie_confirmation) {
									$abort_delete = 0;
								}
								else {
									$name = mysql_result($result, 0, 'name');	
									echo $msg['suppr_notice_dans_caddie_info'].$name."<br /><br />".$msg["confirm_suppr"]."?<br />";
									echo '<input type="button" class="bouton" onClick="document.location = \'./catalog.php?categ=delete&id='.$id.'&caddie_confirmation=1\'" value="'.$msg['63'].'">&nbsp;';
									echo '<input type="button" class="bouton" onClick="history.go(-1)" value="'.$msg['76'].'">';
								}
								break; 
						}
					} 
					if (!$abort_delete){		// suppression de la notice
						$ret_param="";
						$query="select linked_notice from notices_relations where num_notice=$id";		
						$result = mysql_query($query, $dbh);
						$not_mere = 0;
						if (mysql_numrows($result)) $not_mere = mysql_result($result, 0, 0);
						if ($not_mere > 0){
							// perio ou mono?
							$n=mysql_fetch_object(@mysql_query("select * from notices where notice_id=".$not_mere));
							if ($n->niveau_biblio == 'm'|| $n->niveau_biblio == 'b') {
								$ret_param="?categ=isbd&id=$not_mere";
							} elseif ($n->niveau_biblio == 's' || $n->niveau_biblio == 'a') {
								$ret_param= "?categ=serials&sub=view&serial_id=$not_mere";
							}							
						}
						//si intégré depuis une source externe, on suprrime aussi la référence
						$query="delete from notices_externes where num_notice=".$id;
						@mysql_query($query, $dbh);
						notice::del_notice($id);							
						// affichage du message suppression en cours puis redirect vers page de catalogage
						print "<div class=\"row\"><div class='msg-perio'>".$msg['suppression_en_cours']."</div></div>
							<script type=\"text/javascript\">
								document.location='./catalog.php".$ret_param."';
							</script>";
						
					}				
				}
			}	
		}
	} else {
		error_message($msg[416], "${msg[417]} : ${msg[418]}", 1, "./catalog.php");
	}

}
?>
