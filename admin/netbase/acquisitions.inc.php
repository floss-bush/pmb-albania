<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: acquisitions.inc.php,v 1.4 2009-12-24 15:28:25 mbertin Exp $


if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// la taille d'un paquet a traiter
$lot = ACQUISITION_PAQUET_SIZE; // defini dans ./params.inc.php

// taille de la jauge pour affichage
$jauge_size = GAUGE_SIZE;
$jauge_size .= "px";

// initialisation de la borne de départ
if (!isset($start)) $start=0;

// on commence par :
if (!isset($index_quoi)) $index_quoi='SUGGESTIONS';

$v_state=urldecode($v_state);

switch ($index_quoi) {
	
	case 'SUGGESTIONS':
		if (!$count) {
			$actes = mysql_query("SELECT count(1) FROM suggestions", $dbh);
			$count = mysql_result($actes, 0, 0);
		}
		
		print "<br /><br /><h2 align='center'>".htmlentities($msg["nettoyage_reindex_sug"], ENT_QUOTES, $charset)."</h2>";
	
		$query = mysql_query("SELECT id_suggestion, titre, editeur, auteur, code, commentaires FROM suggestions LIMIT ".$start.", ".$lot." ");
		if(mysql_num_rows($query)) {
		
			// définition de l'état de la jauge
			$state = floor($start / ($count / $jauge_size));
			$state .= "px";
			// mise à jour de l'affichage de la jauge
			print "<table border='0' align='center' width='$jauge_size' cellpadding='0'><tr><td class='jauge' width='100%'>";
			print "<img src='../../images/jauge.png' width='$state' height='16px'></td></tr></table>";
			
			// calcul pourcentage avancement
			$percent = floor(($start/$count)*100);
			
			// affichage du % d'avancement et de l'état
			print "<div align='center'>$percent%</div>";
			
			while($row = mysql_fetch_object($query)) {
				
				// index acte
				$req_update = "UPDATE suggestions ";
				$req_update.= "SET index_suggestion = ' ".strip_empty_words($row->titre)." ".strip_empty_words($row->editeur)." ".strip_empty_words($row->auteur)." ".$row->code." ".strip_empty_words($row->commentaires)." ' ";
				$req_update.= "WHERE id_suggestion = ".$row->id_suggestion." ";
				$update = mysql_query($req_update);
				
		
			}
			mysql_free_result($query);
			$next = $start + $lot;
			print "
				<form class='form-$current_module' name='current_state' action='./clean.php' method='post'>
				<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
				<input type='hidden' name='spec' value=\"$spec\">
				<input type='hidden' name='start' value=\"$next\">
				<input type='hidden' name='count' value=\"$count\">
				<input type='hidden' name='index_quoi' value=\"SUGGESTIONS\">
				</form>
				<script type=\"text/javascript\"><!-- 
					setTimeout(\"document.forms['current_state'].submit()\",1000); 
					-->
				</script>";
		} else {
			// mise à jour de l'affichage de la jauge
			print "<table border='0' align='center' width='$table_size' cellpadding='0'><tr><td class='jauge'>";
			print "<img src='../../images/jauge.png' width='$jauge_size' height='16'></td></tr></table>";
			print "<div align='center'>100%</div>";
			$v_state .= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg["nettoyage_reindex_sug"], ENT_QUOTES, $charset)." $count ".htmlentities($msg["nettoyage_res_reindex_sug"], ENT_QUOTES, $charset);
			print "
				<form class='form-$current_module' name='current_state' action='./clean.php' method='post'>
				<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
				<input type='hidden' name='spec' value=\"$spec\">
				<input type='hidden' name='start' value='0'>
				<input type='hidden' name='count' value='0'>
				<input type='hidden' name='index_quoi' value=\"ENTITES\">
				</form>
				<script type=\"text/javascript\"><!-- 
					setTimeout(\"document.forms['current_state'].submit()\",1000); 
					-->
				</script>";	
		}
	
		break ;

	case 'ENTITES' :
		if (!$count) {
			$entites = mysql_query("SELECT count(1) FROM entites", $dbh);
			$count = mysql_result($entites, 0, 0);
		}
		
		print "<br /><br /><h2 align='center'>".htmlentities($msg["nettoyage_reindex_ent"], ENT_QUOTES, $charset)."</h2>";
	
		$query = mysql_query("SELECT id_entite, raison_sociale FROM entites LIMIT ".$start.", ".$lot." ");
		if(mysql_num_rows($query)) {
		
			// définition de l'état de la jauge
			$state = floor($start / ($count / $jauge_size));
			$state .= "px";
			// mise à jour de l'affichage de la jauge
			print "<table border='0' align='center' width='$jauge_size' cellpadding='0'><tr><td class='jauge' width='100%'>";
			print "<img src='../../images/jauge.png' width='$state' height='16px'></td></tr></table>";
			
			// calcul pourcentage avancement
			$percent = floor(($start/$count)*100);
			
			// affichage du % d'avancement et de l'état
			print "<div align='center'>$percent%</div>";
			
			while($row = mysql_fetch_object($query)) {
				
				// index acte
				$req_update = "UPDATE entites ";
				$req_update.= "SET index_entite = ' ".strip_empty_words($row->raison_sociale)." ' ";
				$req_update.= "WHERE id_entite = ".$row->id_entite." ";
				$update = mysql_query($req_update);
				
		
			}
			mysql_free_result($query);
			$next = $start + $lot;
			print "
				<form class='form-$current_module' name='current_state' action='./clean.php' method='post'>
				<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
				<input type='hidden' name='spec' value=\"$spec\">
				<input type='hidden' name='start' value=\"$next\">
				<input type='hidden' name='count' value=\"$count\">
				<input type='hidden' name='index_quoi' value=\"ENTITES\">
				</form>
				<script type=\"text/javascript\"><!-- 
					setTimeout(\"document.forms['current_state'].submit()\",1000); 
					-->
				</script>";
		} else {
			// mise à jour de l'affichage de la jauge
			print "<table border='0' align='center' width='$table_size' cellpadding='0'><tr><td class='jauge'>";
			print "<img src='../../images/jauge.png' width='$jauge_size' height='16'></td></tr></table>";
			print "<div align='center'>100%</div>";
			$v_state .= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg["nettoyage_reindex_ent"], ENT_QUOTES, $charset)." $count ".htmlentities($msg["nettoyage_res_reindex_ent"], ENT_QUOTES, $charset);
			print "
				<form class='form-$current_module' name='current_state' action='./clean.php' method='post'>
				<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
				<input type='hidden' name='spec' value=\"$spec\">
				<input type='hidden' name='start' value='0'>
				<input type='hidden' name='count' value='0'>
				<input type='hidden' name='index_quoi' value=\"ACTES\">
				</form>
				<script type=\"text/javascript\"><!-- 
					setTimeout(\"document.forms['current_state'].submit()\",1000); 
					-->
				</script>";	
		}
	
		break ;

	case 'ACTES':
	
		if (!$count) {
			$actes = mysql_query("SELECT count(1) FROM actes", $dbh);
			$count = mysql_result($actes, 0, 0);
		}
		
		print "<br /><br /><h2 align='center'>".htmlentities($msg["nettoyage_reindex_act"], ENT_QUOTES, $charset)."</h2>";
		
		$query = mysql_query("SELECT actes.id_acte, actes.numero, entites.raison_sociale, actes.commentaires, actes.reference FROM actes, entites where num_fournisseur=id_entite LIMIT ".$start.", ".$lot." ");
		if(mysql_num_rows($query)) {
		
			// définition de l'état de la jauge
			$state = floor($start / ($count / $jauge_size));
			$state .= "px";
			// mise à jour de l'affichage de la jauge
			print "<table border='0' align='center' width='$jauge_size' cellpadding='0'><tr><td class='jauge' width='100%'>";
			print "<img src='../../images/jauge.png' width='$state' height='16px'></td></tr></table>";
			
			// calcul pourcentage avancement
			$percent = floor(($start/$count)*100);
			
			// affichage du % d'avancement et de l'état
			print "<div align='center'>$percent%</div>";
			
			while($row = mysql_fetch_object($query)) {
				
				// index acte
				$req_update = "UPDATE actes ";
				$req_update.= "SET index_acte = ' ".$row->numero." ".strip_empty_words($row->raison_sociale)." ".strip_empty_words($row->commentaires)." ".strip_empty_words($row->reference)." ' ";
				$req_update.= "WHERE id_acte = ".$row->id_acte." ";
				$update = mysql_query($req_update);
				
				//index lignes_actes
				$query_2 = mysql_query("SELECT id_ligne, code, libelle FROM lignes_actes where num_acte = '".$row->id_acte."' ");
				if (mysql_num_rows($query_2)){
					while ($row_2 = mysql_fetch_object($query_2)) {
						
						$req_update_2 = "UPDATE lignes_actes ";
						$req_update_2.= "SET index_ligne = ' ".strip_empty_words($row_2->libelle)." ' ";
						$req_update_2.= "WHERE id_ligne = ".$row_2->id_ligne." ";
						$update_2 = mysql_query($req_update_2);
						
					}
					mysql_free_result($query_2);
				}			
			}
			mysql_free_result($query);
			$next = $start + $lot;
			print "
				<form class='form-$current_module' name='current_state' action='./clean.php' method='post'>
				<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
				<input type='hidden' name='spec' value=\"$spec\">
				<input type='hidden' name='start' value=\"$next\">
				<input type='hidden' name='count' value=\"$count\">
				<input type='hidden' name='index_quoi' value=\"ACTES\">
				</form>
				<script type=\"text/javascript\"><!-- 
					setTimeout(\"document.forms['current_state'].submit()\",1000); 
					-->
				</script>";
		} else {
			// mise à jour de l'affichage de la jauge
			print "<table border='0' align='center' width='$table_size' cellpadding='0'><tr><td class='jauge'>";
			print "<img src='../../images/jauge.png' width='$jauge_size' height='16'></td></tr></table>";
			print "<div align='center'>100%</div>";
			$v_state .= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg["nettoyage_reindex_act"], ENT_QUOTES, $charset)." $count ".htmlentities($msg["nettoyage_res_reindex_act"], ENT_QUOTES, $charset);
			print "
				<form class='form-$current_module' name='current_state' action='./clean.php' method='post'>
				<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
				<input type='hidden' name='spec' value=\"$spec\">
				<input type='hidden' name='start' value='0'>
				<input type='hidden' name='count' value='0'>
				<input type='hidden' name='index_quoi' value=\"FINI\">
				</form>
				<script type=\"text/javascript\"><!-- 
					setTimeout(\"document.forms['current_state'].submit()\",1000); 
					-->
				</script>";	
		}
	
		break ;
	
	
	case 'FINI':
		$spec = $spec - INDEX_ACQUISITIONS;
		$v_state .= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg["nettoyage_reindex_acq_fini"],ENT_QUOTES,$charset);
		print "
			<form class='form-$current_module' name='process_state' action='./clean.php?spec=$spec&start=0' method='post'>
				<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
			</form>
			<script type=\"text/javascript\"><!--
				setTimeout(\"document.forms['process_state'].submit()\",1000);
				-->
			</script>";
		break ;
	
	
}
