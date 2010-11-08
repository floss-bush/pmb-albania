<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: reindex.inc.php,v 1.26 2009-05-16 11:11:53 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// la taille d'un paquet de notices
$lot = REINDEX_PAQUET_SIZE; // defini dans ./params.inc.php

// taille de la jauge pour affichage
$jauge_size = GAUGE_SIZE;
$jauge_size .= "px";

// initialisation de la borne de départ
if (!isset($start)) $start=0;

$v_state=urldecode($v_state);

// on commence par :
if (!isset($index_quoi)) $index_quoi='NOTICES';

switch ($index_quoi) {
	case 'NOTICES':
	
		if (!$count) {
			$notices = mysql_query("SELECT count(1) FROM notices", $dbh);
			$count = mysql_result($notices, 0, 0);
		}
		
		print "<br /><br /><h2 align='center'>".htmlentities($msg["nettoyage_reindex_notices"], ENT_QUOTES, $charset)."</h2>";
		
		$query = mysql_query("SELECT notice_id,tparent_id,tit1,tit2,tit3,tit4,index_l, n_gen, n_contenu, n_resume, tnvol FROM notices LIMIT $start, $lot");
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
			
			while(($row = mysql_fetch_object($query))) {
				// constitution des pseudo-indexes
				
				// titre de série
				if ($row->tparent_id) {
					$tserie = new serie($row->tparent_id);
					$ind_serie = $tserie->name;
				} else {
					$ind_serie = '';
				}  
				$ind_wew = $ind_serie." ".$row->tnvol." ".$row->tit1." ".$row->tit2." ".$row->tit3." ".$row->tit4 ;
				$ind_sew = strip_empty_words($ind_wew) ; 
				if ($row->tparent_id) $ind_serie = ' '.strip_empty_words($tserie->name).' ';
					else $ind_serie = "" ;
				$row->index_l=trim($row->index_l);
				$row->index_l ? $ind_matieres = ' '.strip_empty_words(str_replace($pmb_keyword_sep," ",$row->index_l)).' ' : $ind_matieres = '';
				$row->n_gen ? $ind_n_gen = ' '.strip_empty_words($row->n_gen).' ' : $ind_n_gen = '';
				$row->n_contenu ? $ind_n_contenu = ' '.strip_empty_words($row->n_contenu).' ' : $ind_n_contenu = '';
				$row->n_resume ? $ind_n_resume = ' '.strip_empty_words($row->n_resume).' ' : $ind_n_resume = '';
				
				
				$req_update = "UPDATE notices";
				$req_update .= " SET index_wew='".addslashes($ind_wew)."'";
				$req_update .= ", index_l='".addslashes(clean_tags($row->index_l))."'";
				$req_update .= ", index_sew=' ".$ind_sew." '";
				$req_update .= ", index_serie='$ind_serie'";
				$req_update .= ", index_n_gen='$ind_n_gen'";
				$req_update .= ", index_n_contenu='$ind_n_contenu'";
				$req_update .= ", index_n_resume='$ind_n_resume'";
				$req_update .= ", index_matieres='$ind_matieres'";
				$req_update .= " WHERE notice_id=$row->notice_id ";
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
				<input type='hidden' name='index_quoi' value=\"NOTICES\">
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
				$v_state .= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg["nettoyage_reindex_notices"], ENT_QUOTES, $charset)." $count ".htmlentities($msg["nettoyage_res_reindex_notices"], ENT_QUOTES, $charset);
				print "
					<form class='form-$current_module' name='current_state' action='./clean.php' method='post'>
					<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
					<input type='hidden' name='spec' value=\"$spec\">
					<input type='hidden' name='start' value='0'>
					<input type='hidden' name='count' value='0'>
					<input type='hidden' name='index_quoi' value=\"AUTEURS\">
					</form>
					<script type=\"text/javascript\"><!-- 
						setTimeout(\"document.forms['current_state'].submit()\",1000); 
						-->
					</script>";	
		}
	
		break ;
	
	case 'AUTEURS':
		if (!$count) {
			$elts = mysql_query("SELECT count(1) FROM authors", $dbh);
			$count = mysql_result($elts, 0, 0);
		}
		
		print "<br /><br /><h2 align='center'>".htmlentities($msg["nettoyage_reindex_authors"], ENT_QUOTES, $charset)."</h2>";
		
		$query = mysql_query("SELECT author_id as id,concat(author_name,' ',author_rejete,' ', author_lieu, ' ',author_ville,' ',author_pays,' ',author_numero,' ',author_subdivision) as auteur from authors LIMIT $start, $lot", $dbh);
		if (mysql_num_rows($query)) {
		
			// définition de l'état de la jauge
			$state = floor($start / ($count / $jauge_size));
			
			// mise à jour de l'affichage de la jauge
			print "<table border='0' align='center' width='$jauge_size' cellpadding='0'><tr><td class='jauge'>";
			print "<img src='../../images/jauge.png' width='$state' height='16'></td></tr></table>";
			
			// calcul pourcentage avancement
			$percent = floor(($start/$count)*100);
			
			// affichage du % d'avancement et de l'état
			print "<div align='center'>$percent%</div>";
			
			while(($row = mysql_fetch_object($query))) {
				// constitution des pseudo-indexes
				$ind_elt = strip_empty_chars($row->auteur); 
				$req_update = "UPDATE authors ";
				$req_update .= " SET index_author=' ${ind_elt} '";
				$req_update .= " WHERE author_id=$row->id ";
				$update = mysql_query($req_update, $dbh);
				}
			mysql_free_result($query);
			$next = $start + $lot;
			print "
				<form class='form-$current_module' name='current_state' action='./clean.php' method='post'>
				<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
				<input type='hidden' name='spec' value=\"$spec\">
				<input type='hidden' name='start' value=\"$next\">
				<input type='hidden' name='count' value=\"$count\">
				<input type='hidden' name='index_quoi' value=\"AUTEURS\">
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
			$v_state .= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg["nettoyage_reindex_authors"], ENT_QUOTES, $charset)." $count ".htmlentities($msg["nettoyage_res_reindex_authors"], ENT_QUOTES, $charset);
			print "
				<form class='form-$current_module' name='current_state' action='./clean.php' method='post'>
				<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
				<input type='hidden' name='spec' value=\"$spec\">
				<input type='hidden' name='start' value='0'>
				<input type='hidden' name='count' value='0'>
				<input type='hidden' name='index_quoi' value=\"EDITEURS\">
				</form>
				<script type=\"text/javascript\"><!-- 
					setTimeout(\"document.forms['current_state'].submit()\",1000); 
					-->
				</script>";	
		}
		break ;
	
	case 'EDITEURS':
		if (!$count) {
			$elts = mysql_query("SELECT count(1) FROM publishers", $dbh);
			$count = mysql_result($elts, 0, 0);
		}
		
		print "<br /><br /><h2 align='center'>".htmlentities($msg["nettoyage_reindex_publishers"], ENT_QUOTES, $charset)."</h2>";
		
		$query = mysql_query("SELECT ed_id as id, ed_name as publisher, ed_ville, ed_pays from publishers LIMIT $start, $lot");
		if (mysql_num_rows($query)) {
		
			// définition de l'état de la jauge
			$state = floor($start / ($count / $jauge_size));
			
			// mise à jour de l'affichage de la jauge
			print "<table border='0' align='center' width='$jauge_size' cellpadding='0'><tr><td class='jauge'>";
			print "<img src='../../images/jauge.png' width='$state' height='16'></td></tr></table>";
			
			// calcul pourcentage avancement
			$percent = floor(($start/$count)*100);
			
			// affichage du % d'avancement et de l'état
			print "<div align='center'>$percent%</div>";
			
			while(($row = mysql_fetch_object($query))) {
				// constitution des pseudo-indexes
				$ind_elt = strip_empty_chars($row->publisher." ".$row->ed_ville." ".$row->ed_pays); 
				$req_update = "UPDATE publishers ";
				$req_update .= " SET index_publisher=' ${ind_elt} '";
				$req_update .= " WHERE ed_id=$row->id ";
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
				<input type='hidden' name='index_quoi' value=\"EDITEURS\">
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
			$v_state .= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg["nettoyage_reindex_publishers"], ENT_QUOTES, $charset)." $count ".htmlentities($msg["nettoyage_res_reindex_publishers"], ENT_QUOTES, $charset);
			print "
				<form class='form-$current_module' name='current_state' action='./clean.php' method='post'>
				<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
				<input type='hidden' name='spec' value=\"$spec\">
				<input type='hidden' name='start' value='0'>
				<input type='hidden' name='count' value='0'>
				<input type='hidden' name='index_quoi' value=\"CATEGORIES\">
				</form>
				<script type=\"text/javascript\"><!-- 
					setTimeout(\"document.forms['current_state'].submit()\",1000); 
					-->
				</script>";	
		}
		break ;
	
	case 'CATEGORIES':
		if (!$count) {
			$elts = mysql_query("SELECT count(1) FROM categories", $dbh);
			$count = mysql_result($elts, 0, 0);
		}
		
		print "<br /><br /><h2 align='center'>".htmlentities($msg["nettoyage_reindex_categories"], ENT_QUOTES, $charset)."</h2>";
		
		$req = "select num_noeud, langue, libelle_categorie from categories limit $start, $lot ";
		$query = mysql_query($req, $dbh);
		 
		if (mysql_num_rows($query)) {
		
			// définition de l'état de la jauge
			$state = floor($start / ($count / $jauge_size));
			
			// mise à jour de l'affichage de la jauge
			print "<table border='0' align='center' width='$jauge_size' cellpadding='0'><tr><td class='jauge'>";
			print "<img src='../../images/jauge.png' width='$state' height='16'></td></tr></table>";
			
			// calcul pourcentage avancement
			$percent = floor(($start/$count)*100);
			
			// affichage du % d'avancement et de l'état
			print "<div align='center'>$percent%</div>";
			
			while($row = mysql_fetch_object($query)) {
				// constitution des pseudo-indexes
				$ind_elt = strip_empty_words($row->libelle_categorie, $row->langue); 
				
				$req_update = "UPDATE categories ";
				$req_update.= "SET index_categorie=' ${ind_elt} '";
				$req_update.= "WHERE num_noeud='".$row->num_noeud."' and langue='".$row->langue."' ";
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
				<input type='hidden' name='index_quoi' value=\"CATEGORIES\">
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
			$v_state .= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg["nettoyage_reindex_categories"], ENT_QUOTES, $charset)." $count ".htmlentities($msg["nettoyage_res_reindex_categories"], ENT_QUOTES, $charset);
			print "
				<form class='form-$current_module' name='current_state' action='./clean.php' method='post'>
				<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
				<input type='hidden' name='spec' value=\"$spec\">
				<input type='hidden' name='start' value='0'>
				<input type='hidden' name='count' value='0'>
				<input type='hidden' name='index_quoi' value=\"COLLECTIONS\">
				</form>
				<script type=\"text/javascript\"><!-- 
					setTimeout(\"document.forms['current_state'].submit()\",1000); 
					-->
				</script>";	
		}
		break ;
	
	case 'COLLECTIONS':
		if (!$count) {
			$elts = mysql_query("SELECT count(1) FROM collections", $dbh);
			$count = mysql_result($elts, 0, 0);
		}
		
		print "<br /><br /><h2 align='center'>".htmlentities($msg["nettoyage_reindex_collections"], ENT_QUOTES, $charset)."</h2>";
		
		$query = mysql_query("SELECT collection_id as id, collection_name as collection from collections LIMIT $start, $lot");
		if (mysql_num_rows($query)) {
		
			// définition de l'état de la jauge
			$state = floor($start / ($count / $jauge_size));
			
			// mise à jour de l'affichage de la jauge
			print "<table border='0' align='center' width='$jauge_size' cellpadding='0'><tr><td class='jauge'>";
			print "<img src='../../images/jauge.png' width='$state' height='16'></td></tr></table>";
			
			// calcul pourcentage avancement
			$percent = floor(($start/$count)*100);
			
			// affichage du % d'avancement et de l'état
			print "<div align='center'>$percent%</div>";
			
			while(($row = mysql_fetch_object($query))) {
				// constitution des pseudo-indexes
				$ind_elt = strip_empty_words($row->collection); 
				
				$req_update = "UPDATE collections ";
				$req_update .= " SET index_coll=' ${ind_elt} '";
				$req_update .= " WHERE collection_id=$row->id ";
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
				<input type='hidden' name='index_quoi' value=\"COLLECTIONS\">
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
			$v_state .= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg["nettoyage_reindex_collections"], ENT_QUOTES, $charset)." $count ".htmlentities($msg["nettoyage_res_reindex_collections"], ENT_QUOTES, $charset);
			print "
				<form class='form-$current_module' name='current_state' action='./clean.php' method='post'>
				<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
				<input type='hidden' name='spec' value=\"$spec\">
				<input type='hidden' name='start' value='0'>
				<input type='hidden' name='count' value='0'>
				<input type='hidden' name='index_quoi' value=\"SOUSCOLLECTIONS\">
				</form>
				<script type=\"text/javascript\"><!-- 
					setTimeout(\"document.forms['current_state'].submit()\",1000); 
					-->
				</script>";	
		}
		break ;
	
	case 'SOUSCOLLECTIONS':
		if (!$count) {
			$elts = mysql_query("SELECT count(1) FROM sub_collections", $dbh);
			$count = mysql_result($elts, 0, 0);
		}
		
		print "<br /><br /><h2 align='center'>".htmlentities($msg["nettoyage_reindex_sub_collections"], ENT_QUOTES, $charset)."</h2>";
		
		$query = mysql_query("SELECT sub_coll_id as id, sub_coll_name as sub_collection from sub_collections LIMIT $start, $lot");
		if (mysql_num_rows($query)) {
		
			// définition de l'état de la jauge
			$state = floor($start / ($count / $jauge_size));
			
			// mise à jour de l'affichage de la jauge
			print "<table border='0' align='center' width='$jauge_size' cellpadding='0'><tr><td class='jauge'>";
			print "<img src='../../images/jauge.png' width='$state' height='16'></td></tr></table>";
			
			// calcul pourcentage avancement
			$percent = floor(($start/$count)*100);
			
			// affichage du % d'avancement et de l'état
			print "<div align='center'>$percent%</div>";
			
			while(($row = mysql_fetch_object($query))) {
				// constitution des pseudo-indexes
				$ind_elt = strip_empty_words($row->sub_collection); 
				
				$req_update = "UPDATE sub_collections ";
				$req_update .= " SET index_sub_coll=' ${ind_elt} '";
				$req_update .= " WHERE sub_coll_id=$row->id ";
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
				<input type='hidden' name='index_quoi' value=\"SOUSCOLLECTIONS\">
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
			$v_state .= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg["nettoyage_reindex_sub_collections"], ENT_QUOTES, $charset)." $count ".htmlentities($msg["nettoyage_res_reindex_sub_collections"], ENT_QUOTES, $charset);
			print "
				<form class='form-$current_module' name='current_state' action='./clean.php' method='post'>
				<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
				<input type='hidden' name='spec' value=\"$spec\">
				<input type='hidden' name='start' value='0'>
				<input type='hidden' name='count' value='0'>
				<input type='hidden' name='index_quoi' value=\"SERIES\">
				</form>
				<script type=\"text/javascript\"><!-- 
					setTimeout(\"document.forms['current_state'].submit()\",1000); 
					-->
				</script>";	
		}
		break ;
	
	case 'SERIES':
		if (!$count) {
			$elts = mysql_query("SELECT count(1) FROM series", $dbh);
			$count = mysql_result($elts, 0, 0);
		}
		
		print "<br /><br /><h2 align='center'>".htmlentities($msg["nettoyage_reindex_series"], ENT_QUOTES, $charset)."</h2>";
		
		$query = mysql_query("SELECT serie_id as id, serie_name from series LIMIT $start, $lot");
		if (mysql_num_rows($query)) {
		
			// définition de l'état de la jauge
			$state = floor($start / ($count / $jauge_size));
			
			// mise à jour de l'affichage de la jauge
			print "<table border='0' align='center' width='$jauge_size' cellpadding='0'><tr><td class='jauge'>";
			print "<img src='../../images/jauge.png' width='$state' height='16'></td></tr></table>";
			
			// calcul pourcentage avancement
			$percent = floor(($start/$count)*100);
			
			// affichage du % d'avancement et de l'état
			print "<div align='center'>$percent%</div>";
			
			while(($row = mysql_fetch_object($query))) {
				// constitution des pseudo-indexes
				$ind_elt = strip_empty_words($row->serie_name); 
				
				$req_update = "UPDATE series ";
				$req_update .= " SET serie_index=' ${ind_elt} '";
				$req_update .= " WHERE serie_id=$row->id ";
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
				<input type='hidden' name='index_quoi' value=\"SERIES\">
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
			$v_state .= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg["nettoyage_reindex_series"], ENT_QUOTES, $charset)." $count ".htmlentities($msg["nettoyage_res_reindex_series"], ENT_QUOTES, $charset);
			print "
				<form class='form-$current_module' name='current_state' action='./clean.php' method='post'>
				<input type='hidden' name='v_state' value=\"".urlencode($v_state)."\">
				<input type='hidden' name='spec' value=\"$spec\">
				<input type='hidden' name='start' value='0'>
				<input type='hidden' name='count' value='0'>
				<input type='hidden' name='index_quoi' value=\"DEWEY\">
				</form>
				<script type=\"text/javascript\"><!-- 
					setTimeout(\"document.forms['current_state'].submit()\",1000); 
					-->
				</script>";	
		}
		break ;
	
	case 'DEWEY':
		if (!$count) {
			$elts = mysql_query("SELECT count(1) FROM indexint", $dbh);
			$count = mysql_result($elts, 0, 0);
		}
		
		print "<br /><br /><h2 align='center'>".htmlentities($msg["nettoyage_reindex_indexint"], ENT_QUOTES, $charset)."</h2>";
		
		$query = mysql_query("SELECT indexint_id as id, concat(indexint_name,' ',indexint_comment) as index_indexint from indexint LIMIT $start, $lot");
		if (mysql_num_rows($query)) {
		
			// définition de l'état de la jauge
			$state = floor($start / ($count / $jauge_size));
			
			// mise à jour de l'affichage de la jauge
			print "<table border='0' align='center' width='$jauge_size' cellpadding='0'><tr><td class='jauge'>";
			print "<img src='../../images/jauge.png' width='$state' height='16'></td></tr></table>";
			
			// calcul pourcentage avancement
			$percent = floor(($start/$count)*100);
			
			// affichage du % d'avancement et de l'état
			print "<div align='center'>$percent%</div>";
			
			while(($row = mysql_fetch_object($query))) {
				// constitution des pseudo-indexes
				$ind_elt = strip_empty_words($row->index_indexint); 
				
				$req_update = "UPDATE indexint ";
				$req_update .= " SET index_indexint=' ${ind_elt} '";
				$req_update .= " WHERE indexint_id=$row->id ";
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
				<input type='hidden' name='index_quoi' value=\"DEWEY\">
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
			$v_state .= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg["nettoyage_reindex_indexint"], ENT_QUOTES, $charset)." $count ".htmlentities($msg["nettoyage_res_reindex_indexint"], ENT_QUOTES, $charset);
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
		$spec = $spec - INDEX_NOTICES;
		$v_state .= "<br /><img src=../../images/d.gif hspace=3>".htmlentities($msg["nettoyage_reindex_fini"], ENT_QUOTES, $charset);
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
