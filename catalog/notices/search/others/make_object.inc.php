<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: make_object.inc.php,v 1.10 2009-05-16 11:12:04 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// ----------------------------------------------------------------------------
//	test_other_query() : analyse d'une rech. sur zones de notes
// ----------------------------------------------------------------------------
// Armelle : a priori plus utilisé
function test_other_query($n_res=0, $n_gen=0, $n_tit=0, $n_mat=0, $query, $operator=TRUE, $force_regexp=FALSE) {
	// fonction d'analyse d'une recherche sur titre
	// la fonction retourne un tableau :
	
	$query_result = array(  'type' => 0,
	                        'restr' => '',
	                        'order' => '',
	                        'display',
	                        'nbr_rows' => 0);
	
	// $query_result['type'] = type de la requête :
	// 0 : rien (problème) 
	// 1: match/against
	// 2: regexp
	// 3: regexp pure sans traitement
	// $query_result['restr'] = critères de restriction
	// $query_result['order'] = critères de tri
	// $query_result['nbr_rows'] = nombre de lignes qui matchent
	// $query_result['display'] = affichage en clair de la requête utilisateur
	// si operator TRUE La recherche est booléenne AND
	// si operator FALSE La recherche est booléenne OR
	// si force_regexp : la recherche est forcée en mode regexp
	
	$stopwords = FALSE;
	global $dbh;

	// initialisation opérateur
	$operator ? $dopt = 'AND' : $dopt = 'OR';
	
	$query = pmb_strtolower($query);
	
	// espaces en début et fin
	$query = pmb_preg_replace('/^\s+|\s+$/', '', $query);
	
	// espaces en double
	$query = pmb_preg_replace('/\s+/', ' ', $query);
	
	// contrôle de la requete
	if(!$query) return $query_result;
	
	// déterminer si la requête est une regexp
	// si c'est le cas, on utilise la saisie utilisateur sans modification
	// (on part du principe qu'il sait ce qu'il fait)
	
	if(pmb_preg_match('/\^|\$|\[|\]|\.|\*|\{|\}|\|/', $query)) {
		// regexp pure : pas de modif de la saisie utilisateur
		$query_result['type'] = 3;
		if ($n_res) $query_result['restr'] =  "n_resume REGEXP '$query' OR n_contenu REGEXP '$query' ";
			else $query_result['restr'] =  "";
		if ($n_gen) {
			if ($query_result['restr']) $query_result['restr'].=" OR ";
			$query_result['restr'].= " n_gen REGEXP '$query'";
			}
		if ($n_tit) {
			if ($query_result['restr']) $query_result['restr'].=" OR ";
			$query_result['restr'].= " tit1 REGEXP '$query' OR tit2 REGEXP '$query' OR tit3 REGEXP '$query' OR tit4 REGEXP '$query' OR index_serie REGEXP '$query' ";
			}
		if ($n_mat) {
			if ($query_result['restr']) $query_result['restr'].=" OR ";
			$query_result['restr'].= " index_l REGEXP '$query' ";
			}
		$query_result['order'] = "index_serie ASC, tnvol ASC, index_sew ASC";
		$query_result['display'] = $query;
		} else {
			// nettoyage de la chaîne
			$query = pmb_preg_replace("/[\(\)\,\;\'\!\-\+]/", ' ', $query);
			
			// on supprime les mots vides
			$query = strip_empty_words($query);
			
			// contrôle de la requete
			if(!$query) return $query_result;
			
			// la saisie est splitée en un tableau
			$tab = pmb_split('/\s+/', $query);
			
			// on cherche à détecter les mots de moins de 4 caractères (stop words)
			// si il y des mots remplissant cette condition, c'est la méthode regexp qui sera employée
			foreach($tab as $dummykey=>$word) {
				if(pmb_strlen($word) < 4) {
					$stopwords = TRUE;
					break;
					}
				}
			
			if($stopwords || $force_regexp) {
				// méthode REGEXP
				$query_result['type'] = 2;
				// constitution du membre restricteur
				// premier mot
				if ($n_res) $query_result['restr'] =  "( n_resume REGEXP '${tab[0]}' OR n_contenu REGEXP '${tab[0]}' ";
					else $query_result['restr'] =  "";
				if ($n_gen) {
					if ($query_result['restr']) $query_result['restr'].=" OR  n_gen REGEXP '${tab[0]}'";
						else $query_result['restr']= "( n_gen REGEXP '${tab[0]}'";
					}
				if ($n_tit) {
					if ($query_result['restr']) $query_result['restr'].=" OR tit1 REGEXP '${tab[0]}' OR tit2 REGEXP '${tab[0]}' OR tit3 REGEXP '${tab[0]}' OR tit4 REGEXP '${tab[0]}' OR index_serie REGEXP '${tab[0]}'";
						else $query_result['restr']= "( tit1 REGEXP '${tab[0]}' OR tit2 REGEXP '${tab[0]}' OR tit3 REGEXP '${tab[0]}' OR tit4 REGEXP '${tab[0]}' OR index_serie REGEXP '${tab[0]}' ";
					}
				if ($n_mat) {
					if ($query_result['restr']) $query_result['restr'].=" OR index_l REGEXP '${tab[0]}' ";
						else $query_result['restr']= "( index_l REGEXP '${tab[0]}' ";
					}
				$query_result['restr'].=") ";
				
				//$query_result['restr'] = "(n_resume REGEXP '${tab[0]}'";
				//$query_result['restr'] .= " OR n_contenu REGEXP '$tab[0]')";
				$query_result['display'] = $tab[0];
				for ($i = 1; $i < sizeof($tab); $i++) {
					$query_suite="";					
					if ($n_res) $query_suite =  " ( n_resume REGEXP '${tab[$i]}' OR n_contenu REGEXP '${tab[$i]}' ";
					if ($n_gen) {
						if ($query_suite) $query_suite.=" OR  n_gen REGEXP '${tab[$i]}'";
							else $query_suite= "( n_gen REGEXP '${tab[$i]}'";
						}
					if ($n_tit) {
						if ($query_suite) $query_suite.=" OR tit1 REGEXP '${tab[$i]}' OR tit2 REGEXP '${tab[$i]}' OR tit3 REGEXP '${tab[$i]}' OR tit4 REGEXP '${tab[$i]}' OR index_serie REGEXP '${tab[$i]}'";
							else $query_suite= "( tit1 REGEXP '${tab[$i]}' OR tit2 REGEXP '${tab[$i]}' OR tit3 REGEXP '${tab[$i]}' OR tit4 REGEXP '${tab[$i]}' OR index_serie REGEXP '${tab[$i]}' ";
						}
					if ($n_mat) {
						if ($query_suite) $query_suite.=" OR index_l REGEXP '${tab[$i]}' ";
							else $query_suite= "( index_l REGEXP '${tab[$i]}' ";
						}
					if ($query_suite) {
						$query_suite.=" ) ";
						$query_result['restr'] .= " $dopt ".$query_suite ;
						}
					
					//$query_result['restr'] .= " $dopt (n_resume REGEXP '${tab[$i]}'";
					//$query_result['restr'] .= " OR n_contenu REGEXP '${tab[$i]}')";
					$query_result['display'] .= " $dopt ${tab[$i]}";
			      		}
			      	//echo "<br /><br /><br />".$query_result['restr']."<br /><br /><br />";
				// contitution de la clause de tri
				$query_result['order'] = "index_serie ASC, tnvol ASC, index_sew ASC";
				} else {
					// méthode FULLTEXT
					$query_result['type'] = 1;
					// membre restricteur
					if ($n_res) $query_result['restr'] =  "( MATCH (n_resume, n_contenu) AGAINST ('${tab[0]}') ";
						else $query_result['restr'] =  "";
					if ($n_gen) {
						if ($query_result['restr']) $query_result['restr'].=" OR MATCH (n_gen) AGAINST ('${tab[0]}') ";
							else $query_result['restr']= "( MATCH (n_gen) AGAINST ('${tab[0]}') ";
						}
					if ($n_tit) {
						if ($query_result['restr']) $query_result['restr'].=" OR MATCH (index_wew) AGAINST ('${tab[0]}') ";
							else $query_result['restr']= "( MATCH (index_wew) AGAINST ('${tab[0]}')  ";
						}
					if ($n_mat) {
						if ($query_result['restr']) $query_result['restr'].=" OR MATCH (index_matieres) AGAINST ('${tab[0]}') ";
							else $query_result['restr']= "( MATCH (index_matieres) AGAINST ('${tab[0]}') ";
						}
					$query_result['restr'].=") ";
					
					//$query_result['restr'] = "MATCH (n_resume, n_contenu) AGAINST ('${tab[0]}')";
					$query_result['display'] = $tab[0];
					for ($i = 1; $i < sizeof($tab); $i++) {
						$query_suite="";					
						if ($n_res) $query_suite =  " ( MATCH (n_resume, n_contenu) AGAINST ('${tab[$i]}') ";
						if ($n_gen) {
							if ($query_suite) $query_suite.=" OR MATCH (n_gen) AGAINST ('${tab[$i]}') ";
								else $query_suite= "( MATCH (n_gen) AGAINST ('${tab[$i]}')";
							}
						if ($n_tit) {
							if ($query_suite) $query_suite.=" OR MATCH (index_wew) AGAINST ('${tab[$i]}') ";
								else $query_suite= "( MATCH (index_wew) AGAINST ('${tab[$i]}') ";
							}
						if ($n_mat) {
							if ($query_suite) $query_suite.=" OR MATCH (index_matieres) AGAINST ('${tab[$i]}') ";
								else $query_suite= "( MATCH (index_matieres) AGAINST ('${tab[$i]}') ";
							}
						if ($query_suite) {
							$query_suite.=" ) ";
							$query_result['restr'] .= " $dopt ".$query_suite ;
							}
						//$query_result['restr'] .= " $dopt MATCH";
						//$query_result['restr'] .= " (n_resume, n_contenu)";
						//$query_result['restr'] .= " AGAINST ('${tab[$i]}')";
						$query_result['display'] .= " $dopt ${tab[$i]}";
						}
					// membre de tri
					$query_result['order'] = "index_serie ASC, tnvol ASC, index_sew ASC";
					}
			}
	
	// récupération du nombre de lignes
	$rws = "SELECT count(1) FROM notices WHERE ${query_result['restr']}";
	$result = @mysql_query($rws, $dbh);
	$query_result['nbr_rows'] = mysql_result($result, 0, 0);
	
	return $query_result;
	
	}
