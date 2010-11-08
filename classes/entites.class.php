<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: entites.class.php,v 1.35 2010-01-11 15:47:37 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

require_once ($class_path.'/coordonnees.class.php');
require_once ($class_path.'/exercices.class.php');
require_once ($include_path.'/misc.inc.php');
require_once ($include_path.'/isbn.inc.php');
require_once ($include_path.'/marc_tables/'.$lang.'/empty_words');


class entites{
	
	
	var $id_entite = 0;				//Identifiant de l'entité	
	var $type_entite = 0;			//Type de l'entité (0=fournisseur, 1=bibliothèque)
	var $num_bibli = 0;				//Identifiant de la bibliothèque si Fournisseur, 0 sinon.
	var $raison_sociale = '';
	var $commentaires = '';
	var $siret = '';				//Numéro de Siret				
	var $naf = '';					//Code naf
	var $rcs = '';					//Code registre du commerce
	var $tva = '';					//Numéro de TVA intracommunautaire
	var $num_cp_client = '';		//Numéro de compte chez le fournisseur
	var $num_cp_compta = 0;			//Numéro de compte comptable (4)
	var $site_web = '';				//Url du site web de l'entité
	var $logo = '';					//Url du logo de l'entité
	var $autorisations = '';		//Autorisations d'accès à l'entité
	var $num_frais = 0;				//Identifiant des frais 
	var $num_paiement = 0;			//Identifiant du mode de paiement
	var $index_entite = '';			//Champ de recherche fulltext 

	 
	//Constructeur.	 
	function entites($id_entite= 0) {
		
		if ($id_entite) {
			$this->id_entite = $id_entite;
			$this->load();
		}
	}	
	
	
	// charge une entité à partir de la base.
	function load(){
	
		global $dbh;
		
		$q = "select * from entites where id_entite = '".$this->id_entite."' ";
		$r = mysql_query($q, $dbh) ;
		$obj = mysql_fetch_object($r);
		$this->type_entite = $obj->type_entite;
		$this->num_bibli = $obj->num_bibli;		
		$this->raison_sociale = $obj->raison_sociale;
		$this->commentaires = $obj->commentaires;
		$this->siret = $obj->siret;
		$this->naf = $obj->naf;
		$this->rcs = $obj->rcs;
		$this->tva = $obj->tva;
		$this->num_cp_client = $obj->num_cp_client;
		$this->num_cp_compta = $obj->num_cp_compta;
		$this->site_web = $obj->site_web;
		$this->logo = $obj->logo;
		$this->autorisations = $obj->autorisations;
		$this->num_frais = $obj->num_frais;
		$this->num_paiement = $obj->num_paiement;		

	}

	
	// enregistre une entité en base.
	function save(){
		
		global $dbh;

		if( $this->raison_sociale == '' ) die ("Erreur de création entités");

		//Nettoyage des valeurs en entrée
		$this->raison_sociale = clean_string($this->raison_sociale);
		$this->commentaires = clean_string($this->commentaires);
		$this->siret = clean_string($this->siret);
		$this->naf = clean_string($this->naf);
		$this->rcs = clean_string($this->rcs);
		$this->tva = clean_string($this->tva);
		$this->num_cp_client = clean_string($this->num_cp_client);
		$this->num_cp_compta = clean_string($this->num_cp_compta);
		$this->site_web = clean_string($this->site_web);
		$this->logo = clean_string($this->logo);

		if($this->id_entite) {
	
			$q = "update entites set type_entite = '".$this->type_entite."', num_bibli = '".$this->num_bibli."', raison_sociale = '".$this->raison_sociale."', commentaires = '".$this->commentaires."', ";
			$q.= "siret = '".$this->siret."', naf = '".$this->naf."', rcs = '".$this->rcs."', tva = '".$this->tva."', num_cp_client = '".$this->num_cp_client."', ";
			$q.= "num_cp_compta = '".$this->num_cp_compta."', site_web = '".$this->site_web."', logo = '".$this->logo."', autorisations = '".$this->autorisations."', ";
			$q.= "num_frais = '".$this->num_frais."', num_paiement = '".$this->num_paiement."', ";
			$q.= "index_entite = ' ".strip_empty_words($this->raison_sociale)." '";
			$q.= "where id_entite = '".$this->id_entite."' ";
			mysql_query($q, $dbh);

		} else {

			$q = "insert into entites set type_entite = '".$this->type_entite."', num_bibli = '".$this->num_bibli."', raison_sociale = '".$this->raison_sociale."', commentaires = '".$this->commentaires."', ";
			$q.= "siret = '".$this->siret."', naf = '".$this->naf."', rcs = '".$this->rcs."', tva = '".$this->tva."', num_cp_client = '".$this->num_cp_client."', ";
			$q.= "num_cp_compta = '".$this->num_cp_compta."', site_web = '".$this->site_web."', logo = '".$this->logo."' , autorisations = '".$this->autorisations."', ";
			$q.= "num_frais = '".$this->num_frais."', num_paiement = '".$this->num_paiement."' ";
			mysql_query($q, $dbh);
			$this->id_entite = mysql_insert_id($dbh);
			
		}

	}


	//supprime une entité de la base
	function delete($id_entite= 0) {
		
		global $dbh;

		if(!$id_entite) $id_entite = $this->id_entite; 	

		$q = "delete from entites where id_entite = '".$id_entite."' ";
		mysql_query($q, $dbh);

		$q = "delete from coordonnees where num_entite = '".$id_entite."' ";
		mysql_query($q, $dbh);
				
		$q = "delete from offres_remises where num_fournisseur = '".$id_entite."' ";
		mysql_query($q, $dbh);
		
		$q = "update abts_abts set fournisseur='0' where num_fournisseur = '".$id_entite."' ";
		mysql_query($q, $dbh);
				
	}


	//vérifie l'existence d'une entité en base à partir de son identifiant
	function exists($id_entite= 0) {
		
		global $dbh;

		$q = "SELECT count(1) from entites where id_entite = '".$id_entite."' ";
		$r = mysql_query($q, $dbh);
		return mysql_result($r, 0, 0);
				
	}

	//vérifie l'existence d'une entité en base à partir de sa raison sociale
	function exists_rs($raison_sociale= 0, $numero_bibli=0, $id_entite = 0) {
		//Contrainte à appliquer :
		/*
		 * type= 1 -> etablissement
		 * type = 0 -> fournisseur
		 * Pas de fournisseur avec la même raison sociale que l'établissement 
		 * Pas deux fournisseurs avec la même raison sociale dans un établissement
		 */
		global $dbh;
		
		$q = "select count(1) from entites where raison_sociale = '".$raison_sociale."' and num_bibli='".$numero_bibli."'";
		
		if($id_entite !== 0){
			$q.=" and id_entite != '".$id_entite."'";
		}
		$r = mysql_query($q, $dbh);
		return mysql_result($r, 0, 0);
				
	}

	
	//optimization de la table entites
	function optimize() {
		
		global $dbh;
		
		$opt = mysql_query('OPTIMIZE TABLE entites', $dbh);
		return $opt;
				
	}

	
	//Retourne une requete pour liste des bibliotheques 
	//si user!=0 la requete est limitee aux bibliotheques accessibles par celui-ci  
	function list_biblio($user=0) {
		
		$q = "select * from entites where type_entite = '1' ";
		if ($user) $q.= "and autorisations like('% ".$user." %') ";
		$q.= "order by raison_sociale ";
		return $q;
				
	}
	

	//Retourne la liste des fournisseurs dans un ResultSet
	function list_fournisseurs($id_bibli=0, $debut=0, $nb_per_page=0, $aq=0) {
		
		global $dbh;
		
		$restrict = '';
		if ($id_bibli) {
			$restrict.= "num_bibli = '".$id_bibli."' ";
		}
		if(!$aq) {
			$q = "select * from entites where type_entite = '0' ";
			if ($restrict) $q.="and ".$restrict;
			$q.= "order by raison_sociale ";
		} else {
			$members=$aq->get_query_members("entites","raison_sociale","index_entite","id_entite",$restrict);
			$q = "select *, ".$members["select"]." as pert from entites where ".$members["where"]." ";
			if ($restrict) {
				$q.= "and ".$members["restrict"]." ";
			}
			$q.= "order by pert desc ";
		}  
		if ($debut) {
			$q.="limit ".$debut ;
			if ($nb_per_page) $q.= ",".$nb_per_page;
		}
		if($nb_per_page && !$debut){
			$q.= "limit 0,".$nb_per_page;
		}
		$r = mysql_query($q, $dbh);
		return $r;				
	}


	//Compte le nb de fournisseurs pour une bibliothèque
	function getNbFournisseurs($id_bibli=0, $aq=0) {
		
		global $dbh;
		
		$restrict = '';
		if ($id_bibli) {
			$restrict.= "num_bibli = '".$id_bibli."' ";
		}
		
		if (!$aq) {
			$q = "select count(1) from entites where type_entite = '0' ";
			if ($restrict) $q.="and ".$restrict;
		} else {
			$q = $aq->get_query_count("entites","raison_sociale","index_entite", "id_entite", $restrict);
		}
		$r = mysql_query($q, $dbh); 
		return mysql_result($r, 0, 0);
				
	}


	//Retourne la liste des offres de remises par type de produit pour un fournisseur dans un ResultSet
	function listOffres($id_fou=0) {
		
		global $dbh;
		
		if (!$id_fou) $id_fou = $this->id_entite;
		$q = "select * from offres_remises, types_produits where num_fournisseur = '".$id_fou."' and id_produit = num_produit order by libelle ";
		$r = mysql_query($q, $dbh); 
		return $r;
				
	}


	//Retourne la liste des types de produits pour lesquels il n'y a pas d'offres pour un fournisseur (dans un ResultSet)
	function listNoOffres($id_fou=0) {
		
		global $dbh;
		
		if (!$id_fou) $id_fou = $this->id_entite;
		$q = "select num_produit from offres_remises where num_fournisseur = '".$id_fou."' ";
		$r = mysql_query($q, $dbh);
		$c = mysql_num_rows($r);
		$a = array();
		while(($row = mysql_fetch_object($r))) {
			$a[] = "'".$row->num_produit."'";
		}
		$l = implode(" , ", $a );
		
		$q = "select id_produit, libelle from types_produits ";
		if ($c) $q.= "where id_produit not in (".$l.") order by libelle";
		$r = mysql_query($q, $dbh);
		return $r;
				
	}


	//Retourne la liste des actes d'un type pour une bibliothèque dans un ResultSet
	function listActes($id_bibli, $type_acte, $statut='-1', $debut=0, $nb_per_page=0, $aq=0, $user_input='') {
		
		global $dbh;
		
		if ($statut == '-1') {		
			$filtre = '';
		} elseif ($statut == 32) {
			$filtre = "and ((actes.statut & 32) = 32) ";
		} else {
			$filtre = "and ((actes.statut & 32) = 0) and ((actes.statut & ".$statut.") = '".$statut."') ";
		}
		
				
		if(!$aq) {
			$q = "select * from actes where num_entite = '".$id_bibli."' ";
			$q.= "and type_acte = '".$type_acte."' ".$filtre." "; 
			$q.= "order by numero desc ";
			$q.= "limit ".$debut ;
			if ($nb_per_page) $q.= ",".$nb_per_page;
			
		} else {
	
			$isbn = '';
			$t_codes = array();
			
			if ($user_input!=='') {
				if (isEAN($user_input)) {
					// la saisie est un EAN -> on tente de le formater en ISBN
					$isbn = EANtoISBN($user_input);
					// si échec, on prend l'EAN comme il vient
					if($isbn) {
						$t_codes[] = $isbn;
						$t_codes[] = formatISBN($isbn,10);
					}
				} elseif (isISBN($user_input)) {
					// si la saisie est un ISBN
					$isbn = formatISBN($user_input);
					if($isbn) { 
						$t_codes[] = $isbn ;
						$t_codes[] = formatISBN($isbn,13);
					}
				} 
			}
			
			if (count($t_codes)) {

				$q = "select distinct(id_acte), actes.* from actes left join lignes_actes on num_acte=id_acte ";
				$q.= "where ( num_entite='".$id_bibli."' and type_acte='".$type_acte."' ".$filtre." ) ";
				$q.= "and ('0' ";
				foreach ($t_codes as $v) {
					$q.= "or code like '%".$v."%' ";
				}
				$q.=") ";
				$q.= "order by date_ech asc, numero asc limit ".$debut.",".$nb_per_page." ";
				
			} else {
			
				$members_actes = $aq->get_query_members("actes","numero","index_acte", "id_acte");
				$members_lignes = $aq->get_query_members("lignes_actes","code","index_ligne", "id_ligne");
				$q = "select distinct(id_acte), actes.*, max(".$members_actes["select"]."+".$members_lignes["select"].") as pert  from actes left join lignes_actes on num_acte=id_acte ";
				$q.= "where num_entite='".$id_bibli."' and type_acte='".$type_acte."' ".$filtre." ";
				$q.= "and (".$members_actes["where"]." or ".$members_lignes["where"].") ";
				$q.= "group by id_acte ";
				$q.= "order by pert desc limit ".$debut.",".$nb_per_page." ";
			}
		}  
		$r = mysql_query($q, $dbh);		
		return $r;				
	}


	//Compte le nb d'acte d'un type pour une bibliothèque
	function getNbActes($id_bibli, $type_acte, $statut='-1', $aq=0, $user_input='') {
		
		global $dbh;
		
		if ($statut == '-1') {		
			$filtre = '';
		} elseif ($statut == 32) {
			$filtre = "and ((actes.statut & 32) = 32) ";
		} else {
			$filtre = "and ((actes.statut & 32) = 0) and ((actes.statut & ".$statut.") = '".$statut."') ";
		}

		
		if (!$aq) {
			$q = "select count(1) from actes where num_entite = '".$id_bibli."' ";
			$q.= "and type_acte = '".$type_acte."' ".$filtre." "; 
		} else {

			$isbn = '';
			$t_codes = array();
			
			if ($user_input!=='') {
				if (isEAN($user_input)) {
					// la saisie est un EAN -> on tente de le formater en ISBN
					$isbn = EANtoISBN($user_input);
					if($isbn) {
						$t_codes[] = $isbn;
						$t_codes[] = formatISBN($isbn,10);
					}
				} elseif (isISBN($user_input)) {
					// si la saisie est un ISBN
					$isbn = formatISBN($user_input);
					if($isbn) { 
						$t_codes[] = $isbn ;
						$t_codes[] = formatISBN($isbn,13);
					}
				}
			}
			
			if (count($t_codes)) {

				$q = "select count(distinct(id_acte)) from actes left join lignes_actes on num_acte=id_acte ";
				$q.= "where ( num_entite='".$id_bibli."' and type_acte='".$type_acte."' ".$filtre." ) ";
				$q.= "and ('0' ";
				foreach ($t_codes as $v) {
					$q.= "or code like '%".$v."%' ";
				}
				$q.=") ";
				
			} else {
			
				$members_actes = $aq->get_query_members("actes","numero","index_acte", "id_acte");
				$members_lignes = $aq->get_query_members("lignes_actes","code","index_ligne", "id_ligne");
				$q = "select count(distinct(id_acte)) from actes left join lignes_actes on num_acte=id_acte ";
				$q.= "where ( num_entite='".$id_bibli."' and type_acte='".$type_acte."' ".$filtre." ) ";
				$q.= "and (".$members_actes["where"]." or ".$members_lignes["where"].") ";
				
			}
		}
		$r = mysql_query($q, $dbh);
		return mysql_result($r, 0, 0); 
				
	}


	//Compte le nb de coordonnées pour une entité
	function count_coordonnees($id_entite=0) {
		
		global $dbh;
		if (!$id_entite) $id_entite = $this->id_entite;
		$q = "select count(1) from coordonnees where num_entite = '".$id_entite."' ";
		$r = mysql_query($q, $dbh); 
		return mysql_result($r, 0, 0);
		
	}


	//Retourne un resultset contenant les coordonnées d'une entité
	//Si type_entite=1, retourne l'adresse principale (de facturation)
	//Si type_entite=2, retourne l'adresse de livraison 
	//Si type_entite=0, retourne les autres coordonnées
	//Si type_entite=-1, retourne toutes les coordonnées
	function get_coordonnees($id_entite=0, $type_coord=0, $debut=0, $nb_per_page=0) {
		
		global $dbh;
		if (!$id_entite) $id_entite = $this->id_entite;
		$q = "select * from coordonnees where num_entite = '".$id_entite."' ";
		if($type_coord != '-1') $q.= "and type_coord = '".$type_coord."' "; 
		if ($debut) {
			$q.="limit ".$debut ;
			if($nb_per_page) $q.= ",".$nb_per_page;
		}
		$r = mysql_query($q, $dbh);
		return $r;
		
	}


	//Compte le nb d'exercices pour une entité	
	function has_exercices($id_entite=0, $statut='-1') {
		
		global $dbh;
		if (!$id_entite) $id_entite = $this->id_entite;
		$q = "select count(1) from exercices where num_entite = '".$id_entite."' ";
		if($statut != '-1') $q.= "and statut = '".$statut."' ";		
		$r = mysql_query($q, $dbh); 
		return mysql_result($r, 0, 0);
		
	}


	//Compte le nb de budgets pour une entité	
	function has_budgets($id_entite=0) {
		
		global $dbh;
		if (!$id_entite) $id_entite = $this->id_entite;
		$q = "select count(1) from budgets where num_entite = '".$id_entite."' ";		
		$r = mysql_query($q, $dbh); 
		return mysql_result($r, 0, 0);
		
	}
	

	//Retourne les budgets actifs pour une entité sous forme de Resultset 
	function listBudgetsActifs($id_entite=0) {
		
		global $dbh;
		
		if (!$id_entite) $id_entite = $this->id_entite;
		$q = "select id_budget, libelle from budgets where num_entite = '".$id_entite."' and statut = '1' ";		
		$r = mysql_query($q, $dbh); 
		return $r;
		
	}


	//Retourne un Resultset contenant les rubriques finales des budgets d'une entite en fonction des droits de l'utilisateur courant si per_user=TRUE 	
	function listRubriquesFinales($id_entite=0, $id_exer, $per_user=FALSE, $debut=0, $nb_per_page=0){
		
		global $dbh;
		
		if (!$id_entite) $id_entite = $this->id_entite;
			
		$q = "select budgets.libelle as lib_bud, budgets.type_budget, budgets.montant_global, budgets.seuil_alerte, rubriques.* from budgets, rubriques left join rubriques as rubriques2 on rubriques.id_rubrique=rubriques2.num_parent ";
		$q.= "where budgets.statut = '1' and budgets.num_entite = '".$id_entite."'  and budgets.num_exercice = '".$id_exer."' and rubriques.num_budget = budgets.id_budget and rubriques2.num_parent is NULL ";
		if($per_user) {

			//Récupération de l'utilisateur
		 	$requete_user = "SELECT userid FROM users where username='".SESSlogin."' limit 1 ";
			$res_user = mysql_query($requete_user, $dbh);
			$row_user=mysql_fetch_row($res_user);
			$user_userid=$row_user[0];

		$q.= "and rubriques.autorisations like('% ".$user_userid." %') ";			
		}
		$q.= "order by budgets.libelle, rubriques.id_rubrique ";
		
		if ($debut) {
			$q.="limit ".$debut ;
			if($nb_per_page) $q.= ",".$nb_per_page;
		} else {
			if($nb_per_page) $q.= "limit ".$nb_per_page;
		}
		
		$r = mysql_query($q, $dbh); 
		return $r;
		
	}	


	//Retourne le nombre de rubriques finales des budgets actifs d'une entite en fonction des droits de l'utilisateur courant si per_user=TRUE 	
	function countRubriquesFinales($id_entite=0, $id_exer, $per_user=FALSE){
		
		global $dbh;
		
		if (!$id_entite) $id_entite = $this->id_entite;
			
		$q = "select count(1) from budgets, rubriques left join rubriques as rubriques2 on rubriques.id_rubrique=rubriques2.num_parent ";
		$q.= "where budgets.statut = '1' and budgets.num_entite = '".$id_entite."' and budgets.num_exercice = '".$id_exer."' and rubriques.num_budget = budgets.id_budget and rubriques2.num_parent is NULL ";
		if($per_user) {

			//Récupération de l'utilisateur
		 	$requete_user = "SELECT userid FROM users where username='".SESSlogin."' limit 1 ";
			$res_user = mysql_query($requete_user, $dbh);
			$row_user=mysql_fetch_row($res_user);
			$user_userid=$row_user[0];

		$q.= "and rubriques.autorisations like('% ".$user_userid." %') ";			
		}
		$r = mysql_query($q, $dbh); 
		return mysql_result($r, 0, 0);
		
	}	
	
		
	//Retourne les exercices courants d' une entité	
	function getCurrentExercices($id_entite=0) {
		
		if (!$id_entite) $id_entite = $this->id_entite;
		$q = "select id_exercice, libelle, statut from exercices where num_entite = '".$id_entite."' and (statut &  '".STA_EXE_ACT."') = '".STA_EXE_ACT."' ";
		$q.= "order by statut desc ";
		return $q;		
	}
	
		
	//Compte le nb de suggestions pour une entité
	function has_suggestions($id_entite=0) {
		
		global $dbh;
		if (!$id_entite) $id_entite = $this->id_entite;
		$q = "select count(1) from suggestions where num_entite = '".$id_entite."' ";		
		$r = mysql_query($q, $dbh); 
		return mysql_result($r, 0, 0);
		
	}
	
	
	//Compte le nb d'actes pour une entité
	function has_actes($id_entite=0) {
		
		global $dbh;
		if (!$id_entite) $id_entite = $this->id_entite;
		$q = "select count(1) from actes where num_entite = '".$id_entite."' ";		
		$r = mysql_query($q, $dbh); 
		return mysql_result($r, 0, 0);
		
	}


	//Màj des autorisations dans les rubriques lors de la màj des autorisations dans les entités
	function majAutorisations() {
		
		global $dbh;
				
			$q = "select id_budget from budgets where num_entite = '".$this->id_entite."' ";
			$r = mysql_query($q, $dbh);
			$nb = mysql_num_rows($r);
		
			if ($nb != '0') {			
				$liste= '';
				for ($i=0; $i<$nb; $i++) { 
					$row =mysql_fetch_row($r);
					$liste.= $row[0];
					if ($i<$nb-1) $liste.= ', ';
				}
			
			$q = "select id_rubrique, autorisations from rubriques where autorisations != '' and num_budget in (".$liste.") ";
			$r = mysql_query($q, $dbh); 
			$aut_entite = explode(' ',$this->autorisations);

			while(($row=mysql_fetch_object($r))) {
				
				$aut_rub = explode(' ',$row->autorisations);			
				$aut = array_intersect($aut_entite, $aut_rub);
				
				$q1 = "update rubriques set autorisations = '".' '.implode(' ',$aut).' '."' where id_rubrique = '".$row->id_rubrique."' ";
				mysql_query($q1, $dbh);
			}
		}
		
	}

	//Recuperation de l'etablissement session
	function getSessionBibliId() {
		global $deflt3bibli;
		if (!$_SESSION['id_bibli'] && $deflt3bibli) {
			$_SESSION['id_bibli']=$deflt3bibli;
		}
		return $_SESSION['id_bibli'];
	}

	//Definition de l'etablissement session
	function setSessionBibliId($id_bibli) {
		$_SESSION['id_bibli']=$id_bibli;
		return;
	}
	
}
?>