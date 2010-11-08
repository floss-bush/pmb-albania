<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: liens_actes.class.php,v 1.8 2009-01-16 08:58:05 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

class liens_actes{
	
	
	var $num_acte = 0;				//Numéro d'acte
	var $num_acte_lie = 0;			//Numéro d'acte lié

	 
	//Constructeur.	 
	function liens_actes($num_acte= 0, $num_acte_lie= 0 ) {
		
		global $dbh;
	
		$this->num_acte = $num_acte;
		$this->num_acte_lie = $num_acte_lie;
		if (!$num_acte || !$num_acte_lie) die ("Erreur de création liens_actes");

		$q = "select count(1) from liens_actes where num_acte = '".$num_acte."' and num_acte_lie = '".$num_acte_lie."' ";
		$r = mysql_query($q, $dbh);
		if (mysql_result($r, 0, 0) == 0) {
			$q = "insert into liens_actes set num_acte = '".$num_acte."', num_acte_lie = '".$num_acte_lie."' ";
			$r = mysql_query($q, $dbh);
		}

	}	


	//supprime un lien entre actes de la base
	function delete($num_acte) {
		
		global $dbh;

		$q = "delete from liens_actes where num_acte = '".$num_acte."' or num_acte_lie = '".$num_acte."' ";
		$r = mysql_query($q, $dbh);
				
	}


	//recherche l'acte pere de l'acte passé en paramètre
	function getParent($num_acte_lie) {
		
		global $dbh;

		$q = "select num_acte from liens_actes where num_acte_lie = '".$num_acte_lie."' limit 1";
		$r = mysql_query($q, $dbh);
		if (mysql_num_rows($r)) return mysql_result($r, 0, 0); else return '0';  
	}


	
	//recherche du devis origine de l'acte passe en parametre
	function getDevis($num_acte_lie) {
		
		global $dbh;
		$q = "select num_acte from liens_actes join actes on num_acte=id_acte and type_acte='".TYP_ACT_DEV."' where num_acte_lie = '".$num_acte_lie."' limit 1";
		$r = mysql_query($q, $dbh);
		if (mysql_num_rows($r)) return mysql_result($r, 0, 0); else return '0';  
	}
	
	
	//recherche les enfants de l'acte passé en paramètre
	function getChilds($num_acte, $type_acte=-1) {
		
		global $dbh;

		$q = "select num_acte_lie, numero, type_acte, statut from liens_actes, actes where num_acte = '".$num_acte."' and id_acte = num_acte_lie ";
		if ($type_acte != -1) $q.= "and type_acte = '".$type_acte."' ";
		$q.= "order by type_acte, numero";
		$r = mysql_query($q, $dbh);
		return $r;
	}	

	
	//optimization de la table liens_actes
	function optimize() {
		
		global $dbh;
		
		$opt = mysql_query('OPTIMIZE TABLE liens_actes', $dbh);
		return $opt;
				
	}
	
				
}
?>