<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: sort.class.php,v 1.20 2009-11-16 15:30:45 mbertin Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php"))
	die("no access");

require_once ($include_path . "/parser.inc.php");
require_once ($include_path . "/templates/sort.tpl.php");

/**
 * Classe d'abstraction d'acces aux données des tris stockés
 * l'admin utilise la base et l'opac les sessions 
 */
class dataSort {

	var $typeData; //base ou session
	var $sortName;
	
	//pour le parcours des tris
	var $tabParcours;
	var $posParcours;

	function dataSort($sName,$tData) {
		$this->sortName = $sName;
		$this->typeData = $tData;
	}
	
	/**
	 * Retourne un tableau avec le nom du tri et la construction du tri
	 */
	function recupTriParId($id) {
		switch($this->typeData) {
			case 'base':
				$result = mysql_query("SELECT nom_tri, tri_par FROM tris WHERE id_tri=" . $id);
				if ($result) {
					$tab = mysql_fetch_assoc($result);
					mysql_free_result($result);
					return $tab;
				} else
					return null;
				break;
			case 'session':
				$tab["nom_tri"] = "";
				$tab["tri_par"] = $_SESSION["sort".$this->sortName.$id];
				return $tab;
				break;
		}
	}
	

	/**
	 * Pour initialiser un parcours des tris
	 * Retourne le nombre de tris
	 */
	function initParcoursTris($objSort) {
		//on initialise la position du parcours
		$this->posParcours = 0;
		$this->nbResult=0;
		$this->tabParcours=null;
		
		switch($this->typeData) {
			
			case 'base':
				$result = mysql_query("SELECT id_tri, nom_tri, tri_par FROM tris WHERE tri_reference='" . $this->sortName . "' ORDER BY nom_tri;");
				//echo "SELECT id_tri, nom_tri, tri_par FROM tris WHERE tri_reference='" . $this->sortName . "' ORDER BY nom_tri<br />";
				if ($result) {
					
					//on charge les tris dans un tableau
					while ($this->tabParcours[$this->nbResult] = mysql_fetch_assoc($result)) {
						$this->nbResult++;
					}
					mysql_free_result($result);
					
					//s'il n'y a pas de tris
					if ($this->nbResult==0) {
						//on vide la session stockant le tri en cours
						$_SESSION["tri"] = "";
					}
					
					return $this->nbResult;
				} else {
					$_SESSION["tri"] = "";
					return 0;
				}
				break;
			
			case 'session':
				$this->nbResult=$_SESSION["nb_sort".$this->sortName];

				//s'il n'y a pas de tris
				if ($this->nbResult<=0) {
					//on vide la session stockant le tri en cours
					$_SESSION["last_sort".$this->sortName]="";
				} else {
					//on charge les tris dans un tableau
					for($i=0; $i<$this->nbResult; $i++) {
						$this->tabParcours[$i]["id_tri"] = $i;
						$this->tabParcours[$i]["nom_tri"] = $objSort->descriptionTri($_SESSION["sort".$this->sortName.$i]);
						$this->tabParcours[$i]["tri_par"] = $_SESSION["sort".$this->sortName.$i];
					}
				}
				return $this->nbResult;
				break;
		}
		
	}
	
	/**
	 * Renvoi le tri suivant dans un parcours
	 */
	function parcoursTriSuivant() {
		switch($this->typeData) {
			case 'base':
				$result = $this->tabParcours[$this->posParcours];
				$this->posParcours++;
				return $result;
				break;
			case 'session':
				$result = $this->tabParcours[$this->posParcours];
				$this->posParcours++;
				return $result;
				break;
		}
	}
	
	/**
	 * Enregistre un tri
	 */
	function enregistreTri($id,$nomTri,$desTri) {
		global $msg;
		global $charset;

		switch($this->typeData) {
			case 'base':

				//$criteres = implode(",",$desTri);
				$txt_requete = "";
				
				//modif ou insertion ?
				if ($id != "") {
					//on modifie le tri avec les nouveaux criteres
					$txt_requete = "UPDATE tris SET nom_tri='" . htmlentities($nomTri, ENT_QUOTES, $charset) . "', tri_par='" . $desTri . "' ";
					$txt_requete .= "WHERE id_tri='" . $id . "'";
				} else {
					//on vérifie que le nom de tri n'existe pas
					$txt_requete = "SELECT id_tri FROM tris WHERE nom_tri='" . $nomTri . "'";
					$txt_requete .= " AND tri_reference='" . $this->sortName . "'";
					if (mysql_num_rows(mysql_query($txt_requete)) == 0) {
						//on genere la requete d'insertion
						$txt_requete = "INSERT INTO tris (id_tri, tri_reference, nom_tri, tri_par) ";
						$txt_requete .= "VALUES ('','" . $this->sortName . "','" . htmlentities($nomTri, ENT_QUOTES, $charset) . "','" . $desTri . "')";
					} else {
						//le nom existe : on le dit
						return "<script>alert ('" . $msg['tri_existant'] . "');history.back();</script>";
					}
				}
				
				if ($txt_requete!="") {
					
					//execution de la requete de modif ou d'insertion
					$requete = mysql_query($txt_requete);
					
					if (!$requete) {
						// il y a eu une erreur d'execution de la requete
						return "Erreur mysql : " . $txt_requete . "<br />" . mysql_error();
					} else
						return "";
				} else
					return "";

				break;

			case 'session':

				//si nombre de tris enregistrés dans la session n'est pas null, parcours des variables de session pour l'existence tri et sauvegarde
				if ($_SESSION["nb_sort".$this->sortName]<=0) {
					$_SESSION["sort".$this->sortName.$_SESSION["nb_sort".$this->sortName]]=htmlentities($desTri,ENT_QUOTES,$charset);
					$_SESSION["nb_sort".$this->sortName]++;
				} else {
					$bool=false;
					for ($i=0;$i<$_SESSION["nb_sort".$this->sortName];$i++) {
						if ($_SESSION["sort".$this->sortName.$i]==htmlentities($desTri,ENT_QUOTES,$charset)) {	
							$bool=true;
						}
					}
					if ($bool==true) {
						return "<script>alert ('".$msg['tri_existant']."');</script>";
					} else {
						$_SESSION["sort".$this->sortName.$_SESSION["nb_sort".$this->sortName]]=htmlentities($desTri,ENT_QUOTES,$charset);
						$_SESSION["nb_sort".$this->sortName]++;
					}		
				}

				break;

		}
	}
	
	/**
	 * Supprime un tri
	 */
	function supprimeTri($idTri) {
		switch($this->typeData) {
			case 'base':
				// on vérifie que le tri existe bien
				if (mysql_num_rows(mysql_query("SELECT * FROM tris WHERE id_tri='" . $idTri . "'")) > 0) {
					//c'est ok on supprime
					$result = mysql_query("DELETE FROM tris WHERE id_tri='" . $idTri . "'");
					if (!$result) {
						print "Erreur mysql : " . mysql_error();
					}
				}
				break;
			case 'session':
			
				//on charge les tris dans un tableau sauf celui à supprimer
				$posTab = 0;
				for($i=0; $i<$_SESSION["nb_sort".$this->sortName]; $i++) {
					if ($i != $idTri) {
						//ce n'est pas le tri a supprimer
						//on le stocke dans le tableau
						$tabParcours[$posTab] = $_SESSION["sort".$this->sortName.$i];
						$posTab++;
					}
				}
				
				//on rempli les variables session sans l'element a supprimer
				for($i=0; $i<$posTab; $i++) {
					$_SESSION["sort".$this->sortName.$i] = $tabParcours[$i];
				}
				$_SESSION["nb_sort".$this->sortName]--;
				
				//si il ne subsiste plus d'historique de tris, mise à null des variables de session
				if ($_SESSION["nb_sort".$this->sortName]==0) {
					$_SESSION["last_sort".$this->sortName]="";
				} 
				
				break;
		}
	}
	
}



/**
 * Classe de tri des résultats de recherche dans le catalogue
 * Utilise une variable de session("tri") pour stocker le tri en cours
 * 
 */
class sort {
	//var $sort_name;
	var $params;
	var $error = false;
	var $error_message = "";
	var $table_tri_tempo = "tri_tempo"; //table temporaire à utiliser
	var $table_primary_tri_tempo; //Clé primaire de la table temporaire à créer
	var $limit; //limitation des enregistrements à utiliser dans la requête de tri pour le pager
	var $champs_select; //champs éventuels à retourner dans la requête
	var $table_select; //table éventuelle à retourner dans la requête
	var $table_primary_key_select; //clé de la table éventuelle à retourner dans la requête
	var $dSort; // objet d'acces aux informations 


	/**
	 * Applique le tri donné
	 * @$sort_name nom du tri à appliquer
	 */
	function sort($sort_name, $accesTri) {
		
		if ($sort_name) {
			$sname = $sort_name;
		} else {
			$sname = 'notices';
		}
		
		
		if ($accesTri) {
			$this->dSort = new dataSort($sname,$accesTri);
		} else {
			$this->dSort = new dataSort($sname,'base');
		}
		//on charge le fichier XML
		$this->parse();
	}


	/**
	 * Affiche l'écran de choix des tris enregistrés
	 */
	function show_tris_form() {
		global $show_tris_form;
		global $ligne_tableau_tris;
		global $msg;

		if ($this->dSort->initParcoursTris($this) == 0 ) { 
			//il n'y a pas de tris enregistrés
			
			//on renvoie un message pour le dire
			$tris = $msg['aucun_tri'];
			
		} else {
			// creation du tableau de la liste des tris enregistrés
			$parity = 1;
			$tris = "";

			//affichage des enregistrements de tris possibles
			while ($result = $this->dSort->parcoursTriSuivant()) {
				
				//gestion du surlignage une ligne sur 2 
				if ($parity % 2)
					$pair_impair = "even";
				else
					$pair_impair = "odd";
				
				//html d'une ligne
				$tristemp = str_replace("!!id_tri!!", $result['id_tri'], $ligne_tableau_tris);
				$tristemp = str_replace("!!nom_tri!!", $result['nom_tri'], $tristemp);
				$tristemp = str_replace("!!pair_impair!!", $pair_impair, $tristemp);
				$tris .= $tristemp;
				
				$parity += 1;
			}
		}
		
		//on remplace dans le template les informations issues de la base
		$tris_form = str_replace("!!sortname!!", $this->dSort->sortName, $show_tris_form);
		$tris_form = str_replace("!!liste_tris!!", $tris, $tris_form);
		return $tris_form;
	}


	/**
	 * 
	 */
	function show_sel_form($id_tri=0) {
		switch($this->dSort->typeData) {
			case 'base':
				return $this->show_sel_formAdmin($id_tri);
				break;
			case 'session':
				return $this->show_sel_formOPAC($id_tri);
				break;
		}
	}




	/**
	 * Affiche l'écran de sélection des criteres de tri
	 */
	function show_sel_formAdmin($id_tri) {
		global $show_sel_form;
		global $charset;
		global $msg;

		//les champs de tris possible
		$fields = $this->params["FIELD"];
		
		//initialisation des variables
		$liste_selectionnes = "";
		$nom_du_tri = "";

		//génération de la liste des criteres
		$liste_criteres = "";

		//si id_tri est renseigné, c'est alors une modification du tri sélectionné
		if ($id_tri!=0) {
			$result = $this->dSort->recupTriParId($id_tri);
			//$requete = mysql_query("SELECT nom_tri, tri_par FROM tris WHERE id_tri='" . $id_tri . "'");
			if ($result) {
				//$result = mysql_fetch_array($requete);
				$nom_du_tri = $result['nom_tri'];

				//recherche et décomposition du tri
				$tri_par = explode(",", $result['tri_par']);

				for ($i = 0; $i < count($tri_par); $i++) {
					
					//on decompose la description du critere de tri (c_num_2)
					$tri_par1 = explode("_", $tri_par[$i]);
					
					for ($j = 0; $j < count($fields); $j++) {
						//on parcours tous les champs (pour récuperer le nom)
						if ($fields[$j]["ID"] == $tri_par1[2]) {
							//on est dans le bon champs
							
							//on determine le type et le sens du tri pour l'affichage							
							switch ($tri_par1[1]) {
								case 'num' :
									if ($tri_par1[0] == "c")
										$debut = "0-9 ";
									else
										$debut = "9-0 ";
									break;
								case 'text' :
									if ($tri_par1[0] == "c")
										$debut = "A-Z ";
									else
										$debut = "Z-A ";
									break;
							}

							//la liste des champs sélectionnés
							$liste_selectionnes .= "<option value='" . $tri_par1[0] . "_" . $tri_par1[1] . "_" . $tri_par1[2] . "'>";
							$liste_selectionnes .= $debut . "" . htmlentities($msg[$fields[$j]["NAME"]], ENT_QUOTES, $charset);
							$liste_selectionnes .= "</option>\n";
							
							//ce champ est utilise donc on ne l'affichera pas
							$fields[$j]["UTILISE"] = true;
						
						}
					}
				}

				//on créé la liste des criteres restants
				for ($j = 0; $j < count($fields); $j++) {
					// sans les champs déja utilisés
					if ($fields[$j]["UTILISE"]!=true)
						$liste_criteres .= "<option value='c_" . $fields[$j]["TYPE"] . "_" . $fields[$j]["ID"] . "'>" . htmlentities($msg[$fields[$j]["NAME"]], ENT_QUOTES, $charset) . "</option>\n";
				}
				
			}
		} else {
			//on créé la liste des criteres
			for ($j = 0; $j < count($fields); $j++) {
				$liste_criteres .= "<option value='c_" . $fields[$j]["TYPE"] . "_" . $fields[$j]["ID"] . "'>" . htmlentities($msg[$fields[$j]["NAME"]], ENT_QUOTES, $charset) . "</option>\n";
			}
		}

		//on remplace toutes les variables dans le template
		$sel_form = str_replace("!!id_tri!!", $id_tri, $show_sel_form);
		$sel_form = str_replace("!!sortname!!", $this->dSort->sortName, $sel_form);
		$sel_form = str_replace("!!nom_tri!!", $nom_du_tri, $sel_form);
		$sel_form = str_replace("!!liste_criteres!!", $liste_criteres, $sel_form);
		$sel_form = str_replace("!!liste_selectionnes!!", $liste_selectionnes, $sel_form);
		
		return $sel_form;
	}

    function show_sel_formOPAC() {
    	global $show_sel_form;
		global $liste_criteres_tri;
		global $charset;
		global $msg;
		global $opac_nb_max_criteres_tri;

		$fields = $this->params["FIELD"];

    	for ($i=0;$i<count($fields);$i++) {
    		$liste_criteres.="<option value='".$fields[$i]["ID"]."'>".htmlentities($msg[$fields[$i]["NAME"]],ENT_QUOTES,$charset)."</option>\n";
    	}
    	
    	$listes_tri = "";
    	for ($i=1;$i<$opac_nb_max_criteres_tri;$i++) {
    		$listes_tri .= str_replace("!!idLigne!!",$i,$liste_criteres_tri);
    	}

    	$sel_form = str_replace("!!liste_criteres_tri!!", $listes_tri, $show_sel_form);
    	$sel_form = str_replace("!!liste_criteres!!", $liste_criteres, $sel_form);

    	return $sel_form;
    }

	/**
	 * Enregistre les criteres de tri dans la table tris
	 */
	function sauvegarder($id_tri, $nom_tri, $tris_par) {
		global $msg;
		global $charset;

		$criteres = implode(",",$tris_par);

		return $this->dSort->enregistreTri($id_tri,$nom_tri,$criteres);

	}

	/**
	 * Supprime un tri sauvegarder
	 */
	function supprimer($id_tri) {
		$this->dSort->supprimeTri($id_tri);
	}
	
	/**
	 * Retourne le texte de description du tri à partir de sa description
	 */
	function descriptionTri($desTri) {
		global $msg;

		//récuperations des champs
		$fields = $this->params["FIELD"];
		
		$tris_par = explode(",",$desTri);
		
		$trier_par_texte = "";
		foreach ($tris_par as $selectValue) {
			//découpage du champ (ex : c_num_2 (croissance ou décroissance (c ou d),
			//type de champ (num,text,...) et id du champ)
			$temp = explode("_", $selectValue);

			//on genere le texte descriptif à afficher
			for ($i = 0; $i < count($fields); $i++) {
				if ($fields[$i]["ID"] == $temp[2]) {
					$trier_par_texte .= $msg[$fields[$i]["NAME"]] . " ";
					if ($temp[0] == "c") {
						$trier_par_texte .= $msg["tri_texte_croissant"];
					} else {
						$trier_par_texte .= $msg["tri_texte_decroissant"];
					}
					$trier_par_texte .= ",";
				}
			}
		}
		//on enleve la derniere virgule et on ajoute la )
		$trier_par_texte = substr($trier_par_texte, 0, strlen($trier_par_texte) - 1);
		
		return $trier_par_texte;
	}
	
	
	/**
	 * Retourne le texte de description du tri a partir d'un id
	 */
	function descriptionTriParId($id_tri) {
		global $msg;

		if ($id_tri!="") {
			
			//récupération de la description du tri
			$result = $this->dSort->recupTriParId($id_tri);

			$tris_par = explode(",",$result['tri_par']);
			$nom_tri = $result['nom_tri'];
			
			$trier_par_texte = "(" . $this->descriptionTri($result['tri_par']) . ")";
			
			//on concatene le message complet
			$trier_par_texte = $nom_tri." ".$trier_par_texte;
		
			return $trier_par_texte;
		} else
			return "";
				
	}
	
	
	/**
	 * Applique le tri sélectionner
	 * Renvoi la requete finale utilisant les criteres de tri
	 */
	function appliquer_tri($idTri, $selectTempo, $nomColonneIndex,$debLimit,$nbLimit) {
		global $msg;

		//récuperations des champs
		$fields = $this->params["FIELD"];
		$tableEnCours = $this->table_tri_tempo;

		//creation de la table de tri
		//$cmd_table = "DROP TABLE " . $tableEnCours;
		//mysql_query ($cmd_table);
		//$cmd_table = "CREATE TABLE " . $tableEnCours . " ENGINE=MyISAM (".$selectTempo.")";
		$cmd_table = "CREATE TEMPORARY TABLE " . $tableEnCours . " ENGINE=MyISAM (".$selectTempo.")";
		//echo $cmd_table."<br />";
		mysql_query ($cmd_table);
		$cmd_table = "ALTER TABLE " . $tableEnCours . " PRIMARY KEY " . $nomColonneIndex;
		mysql_query ($cmd_table);	

		//récupération de la description du tri
		$result = $this->dSort->recupTriParId($idTri);

		$trier_par = explode(",",$result['tri_par']);

		//parcours des champs sur lesquels trier		
		for ($j = 0; $j < count($trier_par); $j++) {
			//découpage du champ (ex : c_num_2 (croissance ou décroissance (c ou d),
			//type de champ (num,text,...) et id du champ)
			$temp = explode("_", $trier_par[$j]);

			//on parcours tous les champs de tri possible
			for ($i = 0; $i < count($fields); $i++) {

				//afin de trouver ceux sur lesquels le tri s'applique
				if ($fields[$i]["ID"] == $temp[2]) {
					//on est sur un champ de tri

					//suivant le type de champ
					switch ($fields[$i]["TYPEFIELD"]) {
						case "internal":
							//c'est un champ de la requete de base
							
							//on verifie que le champ est dans la table temporaire
							$requete_fields = mysql_query("SELECT * FROM " . $tableEnCours . " LIMIT 1");
							$x = 0;
							while ($x < mysql_num_fields($requete_fields)) {
								$ligne = mysql_fetch_field($requete_fields, $x);
								if ($ligne->name == $fields[$i]["TABLEFIELD"][0][value]) {
									//le champ est la donc on ajoute le champ au order
									$orderby .= $this->ajoutOrder($fields[$i]["TABLEFIELD"][0][value],$temp[0]) . ",";
									$x = mysql_num_fields($requete_fields);
								}
								$x++;
							}
							mysql_free_result($requete_fields);
							break;

						case "select":
							//une requete union est nécéssaire
							
							//le nom du champ on ajoute tb pour corriger le probleme des noms numeriques
							$nomChamp = "tb".$fields[$i]["NAME"];
							
							//on ajoute la colonne au orderby
							$orderby .= $this->ajoutOrder($nomChamp,$temp[0]) . ",";
													
							//on ajoute la colonne à la table temporaire
							$this->ajoutColonneTableTempo($tableEnCours, $nomChamp, $temp[1]);

							
							//on parcours la ou les tables pour generer les updates
							for ($x = 0; $x < count($fields[$i]["TABLE"]); $x++) {
								
								$requete = $this->genereRequeteUpdate($fields[$i]["TABLE"][$x], $tableEnCours, $nomChamp, $nomColonneIndex);
								
								//echo("updateSort:".$requete."<br />");
								mysql_query ($requete);
							}

							break;

					} //switch
				} //if ($fields[$i]["ID"] == $temp[2]) {
			} //for ($i = 0; $i < count($fields); $i++) {
		} //for ($j = 0; $j < count($trier_par); $j++) {
		
		
		if ($orderby!="") {

			//on enleve la derniere virgule
			$orderby = substr($orderby, 0, strlen($orderby) - 1);

			//on va classer la table tempo suivant les criteres donnés
			$requete = "ALTER TABLE " . $tableEnCours ." ORDER BY ". $orderby;
			//echo("alter:".$requete."<br />");
			mysql_query ($requete);
		}

		//on retourne la requete sur la table de tri
    	if ($this->table_select!="") {
    		//c'est une requete avec des informations extérieurs
    		$requete = "SELECT " . $nomColonneIndex . "," . $this->champs_select;
    		$requete .= " FROM " . $this->table_tri_tempo . "," . $this->table_select;
    		$requete .= " WHERE " . $this->table_select . "." . $this->table_primary_key_select;
    		$requete .= "=" . $this->table_tri_tempo . "." . $nomColonneIndex;
    		$requete .= " GROUP BY " . $nomColonneIndex;	
    		if ($orderby!="") $requete .= " ORDER BY " . $orderby;
    		$requete .= " LIMIT " . $debLimit . "," . $nbLimit;
    	} else {
			if ($nbLimit>0) {
	    		//requete de base sur la table triée avec limit
				$requete = "SELECT * FROM " . $tableEnCours . " LIMIT " . $debLimit . "," . $nbLimit; 
			} else {
	    		//requete de base sur la table triée
				$requete = "SELECT " . $nomColonneIndex . " FROM " . $tableEnCours;
			}
    	}
    	
    	//echo $requete."<br />";
		return $requete;

	}
	
	/**
	 * Ajoute une colonne à la table temporaire du nom et du type précisé
	 */
	function ajoutColonneTableTempo($nomTable, $nomCol,$type) {
		
		//d'abord on ajoute la colonne
		$cmd_table = "ALTER TABLE " . $nomTable . " ADD " . $nomCol . " ";
		
		//en fonction du type on met le type mysql
		switch($type) {
			case "num":
				$cmd_table .= "integer";
				break;
			case "text":
			default:
				$cmd_table .= "text";
				break;
		}
		
		//execution de l'ajout de la colonne
		mysql_query ($cmd_table);
		
	}
	
	/**
	 * Renvoi le nom du champ et l'ordre de tri SQL
	 */
	function ajoutOrder($nomChp,$typeorder) {
		
		$tmpTxt = $nomChp;
		
		//suivant le type de tri
		switch ($typeorder) {
			case "c":
				$tmpTxt .= " ASC";
				break;
			case "d":
			default:
				$tmpTxt .= " DESC";
				break;
		}
		
		
		return $tmpTxt;
	}
	
	/**
	 * Genere la requete select d'un element table
	 */
	function genereRequeteUpdate($desTable, $nomTable, $nomChp, $nomColonneTempo) {


		//echo "desTable:";
		//print_r($desTable);
		
		$tables = $nomTable . "," .$this->params["REFERENCE"];
		$groupby = "";
		
		//
		//SELECT de base pour la récupération des informations
		//
		$extractinfo_sql = "SELECT ".$this->params["REFERENCE"].'.'.$this->params["REFERENCEKEY"].", ".$this->ajoutIfNull($desTable["TABLEFIELD"][0])." AS ".$nomChp." FROM ".$nomTable.' LEFT JOIN '.$this->params["REFERENCE"].' ON ('.$this->params["REFERENCE"].'.'.$this->params["REFERENCEKEY"].' = '.$nomTable.'.'.$this->params["REFERENCEKEY"].')';
		
		//
		//On ajout les éventuelles liaisons
		//
		for ($x = 0; $x <= count($desTable["LINK"]); $x++) {
			switch ($desTable["LINK"][$x]["TYPE"]) {
				case "n1" :
					if ($desTable["LINK"][$x]["TABLEKEY"][0][value]) {
						$extractinfo_sql .= " LEFT JOIN " . $desTable["LINK"][$x]["TABLE"][0][value];
						$extractinfo_sql .= " ON " . $desTable["NAME"] . "." . $desTable["LINK"][$x]["EXTERNALFIELD"][0][value];
						$extractinfo_sql .= "=" . $desTable["LINK"][$x]["TABLE"][0][value] . "." . $desTable["LINK"][$x]["TABLEKEY"][0][value];
					} else {
						$extractinfo_sql .= " LEFT JOIN " . $desTable["NAME"];
						$extractinfo_sql .= " ON " . $this->params["REFERENCE"] . "." . $this->params["REFERENCEKEY"];
						$extractinfo_sql .= "=" . $desTable["NAME"] . "." . $desTable["LINK"][$x]["EXTERNALFIELD"][0][value];
					}
					break;
				case "1n" :
					$extractinfo_sql .= " LEFT JOIN " . $desTable["NAME"];
					$extractinfo_sql .= " ON (" . $desTable["NAME"] . "." . $desTable["TABLEKEY"][0][value];
					$extractinfo_sql .= "=" . $this->params["REFERENCE"] . "." . $desTable["LINK"][$x]["REFERENCEFIELD"][0][value] . ") ";
					break;
				case "nn" :
					$extractinfo_sql .= " LEFT JOIN " . $desTable["LINK"][$x]["TABLE"][0][value];
					$extractinfo_sql .= " ON (" . $nomTable . "." . $this->params["REFERENCEKEY"];
					$extractinfo_sql .= "=" . $desTable["LINK"][$x]["TABLE"][0][value] . "." . $desTable["LINK"][$x]["REFERENCEFIELD"][0][value] . ") ";
					
					if ($desTable["LINK"][$x]["TABLEKEY"][0][value]) {
						$extractinfo_sql .= " LEFT JOIN " . $desTable["NAME"];
						$extractinfo_sql .= " ON (" . $desTable["LINK"][$x]["TABLE"][0][value] . "." . $desTable["LINK"][$x]["TABLEKEY"][0][value];
						$extractinfo_sql .= "=" . $desTable["NAME"] . "." . $desTable["LINK"][$x]["EXTERNALFIELD"][0][value] . ") ";
					} else {
						$extractinfo_sql .= " LEFT JOIN " . $desTable["NAME"];
						$extractinfo_sql .= " ON (" . $desTable["LINK"][$x]["TABLE"][0][value] . "." . $desTable["LINK"][$x]["EXTERNALFIELD"][0][value];
						$extractinfo_sql .= "=" . $desTable["NAME"] . "." . $desTable["TABLEKEY"][0][value] . ") ";
						
					}
					
					break;
			}
		}

		//
		//On applique les restrictions GROUP BY et ORDER BY
		//
		if (isset($desTable["ORDERBY"])) {
			$extractinfo_sql .= " ORDER BY ".$this->ajoutIfNull($desTable["ORDERBY"][0]);		
		}

		$extractinfo_sql = "SELECT * FROM (".$extractinfo_sql.") AS asubquery";

		if (isset($desTable["GROUPBY"])) {
			$extractinfo_sql .= " GROUP BY ".$desTable["GROUPBY"][0]["value"];
		}

		//echo '<br /><br />'.$extractinfo_sql.'<br /><br />';

		//
		//On met le tout dans une table temporaire
		//
		$sql = "DROP TEMPORARY TABLE IF EXISTS ".$nomTable."_update";
		mysql_query($sql);
		$temporary2_sql = "CREATE TEMPORARY TABLE ".$nomTable."_update ENGINE=MyISAM (".$extractinfo_sql.")";
		//echo '<br /><br />'.$temporary2_sql.'<br /><br />';
		mysql_query($temporary2_sql);

		//
		//Et on rempli la table tri_tempo avec les éléments de la table temporaire
		//
		$requete = "UPDATE " . $this->params["REFERENCE"].", ".$nomTable.", ".$nomTable."_update";
		$requete .= " SET " . $nomTable.".".$nomChp . " = " . $nomTable."_update.".$nomChp;
		
		//le lien vers la table de tri temporaire
		$requete .= " WHERE " . $nomTable.".".$this->params["REFERENCEKEY"];
		$requete .= "=" . $nomTable."_update.".$this->params["REFERENCEKEY"];
		$requete .= " AND ".$nomTable."_update.".$nomChp." IS NOT NULL";
		$requete .= " AND ".$nomTable."_update.".$nomChp." != ''";
		
		//si on a un filtre supplementaire
		if (isset($desTable["FILTER"])) {
			//un order by est précisé
			$requete .= " AND " . $desTable["FILTER"][0][value];	
		}

		return $requete;
		
	}


	/**
	 * Ajoute le ifnull si précisé
	 */
	function ajoutIfNull($tableau) {
		if (isset($tableau["NULLVALUE"])) {
			$tmpTxt = "IFNULL(" . $tableau[value] . ",'" . $tableau["NULLVALUE"] . "')"; 
		} else {
			$tmpTxt = $tableau[value];
		}
	
		return $tmpTxt;	
	}


	/**
	 * Parse les fichiers XML de parametres
	 * il y a un fichier par type de tris
	 */
	function parse() {
		global $include_path;
		global $$params_name;
		
		$params_name = $this->dSort->sortName . "_params";
		$params = $$params_name;

		if ($params) {
			$this->params = $params;
		} else {
			$nomfichier = $include_path . "/sort/" . $this->dSort->sortName . "/sort.xml";

			if (file_exists($nomfichier)) {
				$fp = fopen($nomfichier, "r");
			}

			if ($fp) {
				//un fichier est ouvert donc on le lit
				$xml = fread($fp, filesize($nomfichier));
				//on le ferme
				fclose($fp);
				//on le parse pour le transformer en tableau
				$params = _parser_text_no_function_($xml, "SORT");
				//on le stocke dans la classe
				$this->params = $params;
			} else {
				$this->error = true;
				$this->error_message = "Can't open definition file";
			}
		}
	}
}
?>