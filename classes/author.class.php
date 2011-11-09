<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: author.class.php,v 1.61.2.1 2011-05-16 12:35:59 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

// définition de la classe de gestion des 'auteurs'
if ( ! defined( 'AUTEUR_CLASS' ) ) {
  define( 'AUTEUR_CLASS', 1 );
  
require_once($class_path."/notice.class.php");
require_once("$class_path/aut_link.class.php");

class auteur {
	
	// ---------------------------------------------------------------
	//		propriétés de la classe
	// ---------------------------------------------------------------	
	var $id;		// MySQL id in table 'authors'
	var $type;		// author type (70 or 71)
	var $name;		// author name
	var $rejete;		// author name (rejected element)
	var $date;		// dates
	var $author_web;	// web de l'auteur
	var $author_web_link;	// lien web de l'auteur
	var $see;		// 'see' author MySQL id
	var $see_libelle;	// printable form of 'see' author (in fact 'display' of retained form)
	var $display;		// usable form for displaying ( _name_, _rejete_ (_date1_-_date2_) )
	var $isbd_entry;	// isbd like version ( _rejete_ _name_ (_date1_-_date2_))
	var $isbd_entry_lien_gestion ; // lien sur le nom vers la gestion
	var $lieu;	// lieu du congrès
	var $ville;	// ville du congrès
	var $pays;	// pays du congrès
	var $subdivision;	// subdivision
	var $numero;		// numero de congrès
	var $author_comment ; // Commentaire, peut contenir du HTML
	var $duplicate_from_id = 0;
	
	// ---------------------------------------------------------------
	//		auteur($id) : constructeur
	// ---------------------------------------------------------------
	function auteur($id=0,$recursif=0) {
		// echo "AUTHOR.CLASS $id<br />" ;
		if($id) {
			// on cherche à atteindre une notice existante
			$this->recursif=$recursif;
			$this->id = $id;
			$this->getData();
			} else {
				// la notice n'existe pas
				$this->id = 0;
				$this->getData();
				}
		}
	
	// ---------------------------------------------------------------
	//		getData() : récupération infos auteur
	// ---------------------------------------------------------------
	function getData() {
		global $dbh,$msg;
		if(!$this->id) {
			// pas d'identifiant.
			$this->id = 0;
			$this->type	= '';
			$this->name	= '';
			$this->rejete = '';
			$this->date	= '';
			$this->author_web = '';
			$this->see = '';
			$this->see_libelle = '';
			$this->display = '';
			$this->isbd_entry = '';
			$this->author_comment	= '';
			$this->subdivision = '';
			$this->lieu	= '';
			$this->ville = '';
			$this->pays	= '';
			$this->numero = '';
		} else {
			$requete = "SELECT * FROM authors WHERE author_id=$this->id LIMIT 1 ";
			$result = @mysql_query($requete, $dbh);
			if(mysql_num_rows($result)) {
				$temp = mysql_fetch_object($result);
				mysql_free_result($result);
				$this->id		= $temp->author_id;
				$this->type		= $temp->author_type;
				$this->name		= $temp->author_name;
				$this->rejete		= $temp->author_rejete;
				$this->date		= $temp->author_date;
				$this->author_web	= $temp->author_web;
				$this->see		= $temp->author_see;
				$this->author_comment	= $temp->author_comment	;					
				//Ajout pour les congrès
				$this->subdivision	= $temp->author_subdivision	;
				$this->lieu	= $temp->author_lieu	;
				$this->ville = $temp->author_ville	;
				$this->pays	= $temp->author_pays	;
				$this->numero = $temp->author_numero	;
				if($this->type==71 ) {
					// C'est une collectivité
					$this->isbd_entry = $temp->author_name;
					$this->display = $temp->author_name;
						 
					if( $temp->author_subdivision ) {
						$this->isbd_entry .= ". ".$temp->author_subdivision;
						$this->display .= ". ".$temp->author_subdivision;
					}
					
					if($temp->author_rejete ) {
						$this->isbd_entry .= ", ".$temp->author_rejete;
						$this->display .= ", ".$temp->author_rejete;
						//$this->info_bulle=$temp->author_rejete; 
					}
					$liste_field=$liste_lieu=array();
					
					if($temp->author_numero) {
						$liste_field[]=	$temp->author_numero;
					}				
					if($temp->author_date) {
						$liste_field[]=	$temp->author_date;
					}
					if($temp->author_lieu) {
						$liste_lieu[]=	$temp->author_lieu;
					}
					if($temp->author_ville) {
						$liste_lieu[]=	$temp->author_ville;
					}	
					if($temp->author_pays) {
						$liste_lieu[]=	$temp->author_pays;
					}			
					if(count($liste_lieu))	$liste_field[]=	implode(", ",$liste_lieu);							
					if(count($liste_field))	{
						$liste_field=implode("; ",$liste_field);
						$this->isbd_entry .= ' ('.$liste_field.')';
						$this->display .= ' ('.$liste_field.')';
					}					
				} elseif( $this->type==72 ) {
					// C'est un congrès
					$libelle=$msg["congres_libelle"].": ";
					if($temp->author_rejete ) {
						$this->isbd_entry = $temp->author_name.", ".$temp->author_rejete;
						$this->display = $libelle.$temp->author_name.", ".$temp->author_rejete;
					} else {
						$this->isbd_entry = $temp->author_name;
						$this->display = $libelle.$temp->author_name;
					}										
					$liste_field=$liste_lieu=array();
					if($temp->author_subdivision) {
						$liste_field[]=	$temp->author_subdivision;
					}
					if($temp->author_numero) {
						$liste_field[]=	$temp->author_numero;
					}				
					if($temp->author_date) {
						$liste_field[]=	$temp->author_date;
					}
					if($temp->author_lieu) {
						$liste_lieu[]=	$temp->author_lieu;
					}
					if($temp->author_ville) {
						$liste_lieu[]=	$temp->author_ville;
					}	
					if($temp->author_pays) {
						$liste_lieu[]=	$temp->author_pays;
					}			
					if(count($liste_lieu))	$liste_field[]=	implode(", ",$liste_lieu);							
					if(count($liste_field))	{
						$liste_field=implode("; ",$liste_field);
						$this->isbd_entry .= ' ('.$liste_field.')';
						$this->display .= ' ('.$liste_field.')';
					}					
				} else {
					// auteur physique
					if($temp->author_rejete) {
						$this->isbd_entry = "$temp->author_name, $temp->author_rejete ";
						$this->display = "$temp->author_name, $temp->author_rejete";
					} else {
						$this->isbd_entry = $temp->author_name;
						$this->display = $temp->author_name;
					}					
					if($temp->author_date) {
						$this->isbd_entry .= ' ('.$temp->author_date.')';
					}
				}	
				// Ajoute un lien sur la fiche auteur si l'utilisateur à accès aux autorités
				if (SESSrights & AUTORITES_AUTH) $this->isbd_entry_lien_gestion = "<a href='./autorites.php?categ=auteurs&sub=author_form&id=".$this->id."' class='lien_gestion' title='".$this->info_bulle."'>".$this->display."</a>";
				else $this->isbd_entry_lien_gestion = $this->display;
				
				if($temp->author_web) $this->author_web_link = " <a href='$temp->author_web' target=_blank><img src='./images/globe.gif' border=0 /></a>";
				else $this->author_web_link = "" ;
					
				if($temp->author_see && !$this->recursif) {
					$see = new auteur($temp->author_see,1);
					$this->see_libelle = $see->display;
				} else {
					$this->see_libelle = '';
				}
			} else {
				// pas d'auteur avec cette clé
				$this->id = 0;
				$this->type	= '';
				$this->name = '';
				$this->rejete = '';
				$this->date = '';
				$this->author_web = '';
				$this->see = '';
				$this->see_libelle = '';
				$this->display = '';
				$this->isbd_entry = '';
				$this->author_web_link = "" ;
				$this->author_comment = '';
				$this->subdivision = '';
				$this->lieu	= '';
				$this->ville = '';
				$this->pays	= '';
				$this->numero = '';				
			}
		}
	}

	// ---------------------------------------------------------------
	//		show_form : affichage du formulaire de saisie
	// ---------------------------------------------------------------
	function show_form($type_autorite=70) {
	
		global $msg;
		global $author_form;
		global $dbh;
		global $charset;
				
		$liste_renvoyes = "";
		if($this->id) {
			$action = "./autorites.php?categ=auteurs&sub=update&id=$this->id";
			$libelle = $msg[199];
			$button_remplace = "<input type='button' class='bouton' value='$msg[158]' ";
			$button_remplace .= "onclick='unload_off();document.location=\"./autorites.php?categ=auteurs&sub=replace&id=$this->id\"'>";
			
			$button_voir = "<input type='button' class='bouton' value='$msg[voir_notices_assoc]' ";
			$button_voir .= "onclick='unload_off();document.location=\"./catalog.php?categ=search&mode=0&etat=aut_search&aut_id=$this->id\"'>";
			
			$button_delete = "<input type='button' class='bouton' value='$msg[63]' ";
			$button_delete .= "onClick=\"confirm_delete();\">";
			
			$requete = "SELECT * FROM authors WHERE ";
			$requete .= "author_see = '$this->id' ";
			$requete .= "ORDER BY author_name, author_rejete ";
			$res = @mysql_query($requete, $dbh);
			$nbr_lignes = mysql_num_rows($res);
			if ($nbr_lignes) {
				$liste_renvoyes = "<br /><div class='row'><h3>$msg[aut_list_renv_titre]</h3><table>" ;
				$parity=1;
				while(($author_renvoyes=mysql_fetch_object($res))) {
					$author_renvoyes->author_name = $author_renvoyes->author_name;
					$author_renvoyes->author_rejete = $author_renvoyes->author_rejete;
					if($author_renvoyes->author_rejete) $author_entry = $author_renvoyes->author_name.',&nbsp;'.$author_renvoyes->author_rejete;
						else $author_entry = $author_renvoyes->author_name;
					if($author_renvoyes->author_date)
						$author_entry .= "&nbsp;($author_renvoyes->author_date)";
					$link_auteur = "./autorites.php?categ=auteurs&sub=author_form&id=$author_renvoyes->author_id";
					if ($parity % 2) {
						$pair_impair = "even";
						} else {
							$pair_impair = "odd";
							}
					$parity += 1;
						$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" onmousedown=\"document.location='$link_auteur';\" ";
							$liste_renvoyes .= "<tr class='$pair_impair' $tr_javascript style='cursor: pointer'>
										<td valign='top'>
									$author_entry
									</td>
								</tr>";
						
					} // fin while
				$liste_renvoyes .= "</table></div>";  
				}
			} else {
				$action = './autorites.php?categ=auteurs&sub=update&id=';
				$libelle = $msg[207];
				$button_remplace = '';
				$button_delete ='';
				}
		
		//Si on est en modif ou non
		if(!$this->id){
			$this->type = $type_autorite;
			$author_form = str_replace('!!dupliquer!!',"",$author_form);
		} 
							
		// mise à jour de la zone type
		switch($this->type) {
			case 71:
				$sel_coll = " SELECTED";
				//Si on est en modif ou non
				if($this->id) {
					$libelle = $msg["aut_modifier_coll"];
					$bouton_dupliquer = "<input type='button' id='dupli_btn' value='".$msg["aut_duplicate"]."' class='bouton' onClick='unload_off();document.location=\"./autorites.php?categ=auteurs&sub=duplicate&type_autorite=".$this->type."&id=".$this->id."\"'/>";
					$author_form = str_replace('!!dupliquer!!',$bouton_dupliquer,$author_form);
				} else $libelle = $msg["aut_ajout_collectivite"];
				$completion_name="collectivite_name";
			break;
			case 72:
				//Si on est en modif ou non
				if($this->id) {
					$libelle = $msg["aut_modifier_congres"];
					$bouton_dupliquer = "<input type='button' id='dupli_btn' value='".$msg["aut_duplicate"]."' class='bouton' onClick='unload_off();document.location=\"./autorites.php?categ=auteurs&sub=duplicate&type_autorite=".$this->type."&id=".$this->id."\"'/>";
					$author_form = str_replace('!!dupliquer!!',$bouton_dupliquer,$author_form);
				} else $libelle = $msg["aut_ajout_congres"];
				$sel_congres = " SELECTED";
				$completion_name="congres_name";
			break;			
			default:
				$author_form = str_replace('!!display!!',"display:none",$author_form);
				$author_form = str_replace('!!dupliquer!!',"",$author_form);
				$sel_pp = " SELECTED";
				$completion_name=" ";
			break;
		}		
		
		$aut_link= new aut_link(AUT_TABLE_AUTHORS,$this->id);
		$author_form = str_replace('<!-- aut_link -->', $aut_link->get_form('saisie_auteur') , $author_form);
		
	
		$author_form = str_replace('!!id!!',						$this->id,															$author_form);
		$author_form = str_replace('!!action!!',					$action,															$author_form);
		$author_form = str_replace('!!libelle!!',					$libelle,															$author_form);
		$author_form = str_replace('!!author_nom!!',				htmlentities($this->name,ENT_QUOTES, $charset),						$author_form);
		$author_form = str_replace('!!author_rejete!!',				htmlentities($this->rejete,ENT_QUOTES, $charset),					$author_form);
		$author_form = str_replace('!!voir_id!!',					$this->see,															$author_form);
		$author_form = str_replace('!!voir_libelle!!',				htmlentities($this->see_libelle,ENT_QUOTES, $charset),	$author_form);
		$author_form = str_replace('!!date!!',						htmlentities($this->date,ENT_QUOTES, $charset),						$author_form);
		$author_form = str_replace('!!lieu!!',						htmlentities($this->lieu,ENT_QUOTES, $charset),						$author_form);
		$author_form = str_replace('!!ville!!',						htmlentities($this->ville,ENT_QUOTES, $charset),					$author_form);
		$author_form = str_replace('!!pays!!',						htmlentities($this->pays,ENT_QUOTES, $charset),						$author_form);
		$author_form = str_replace('!!subdivision!!',				htmlentities($this->subdivision,ENT_QUOTES, $charset),				$author_form);
		$author_form = str_replace('!!numero!!',					htmlentities($this->numero,ENT_QUOTES, $charset),					$author_form);			
		$author_form = str_replace('!!author_web!!',				htmlentities($this->author_web,ENT_QUOTES, $charset),				$author_form);
		$author_form = str_replace('!!sel_pp!!',					$sel_pp,															$author_form);
		$author_form = str_replace('!!sel_coll!!',					$sel_coll,															$author_form);
		$author_form = str_replace('!!sel_congres!!',				$sel_congres,														$author_form);
		$author_form = str_replace('!!remplace!!',					$button_remplace,													$author_form);
		$author_form = str_replace('!!voir_notices!!',				$button_voir,														$author_form);
		$author_form = str_replace('!!delete!!',					$button_delete,														$author_form);
		$author_form = str_replace('!!liste_des_renvoyes_vers!!',	$liste_renvoyes,													$author_form);
		$author_form = str_replace('!!completion_name!!',			$completion_name,													$author_form);
		$author_form = str_replace('!!type_autorite!!',				$this->type,													$author_form);
		// pour retour à la bonne page en gestion d'autorités
		// &user_input=".rawurlencode(stripslashes($user_input))."&nbr_lignes=$nbr_lignes&page=$page
		global $user_input, $nbr_lignes, $page ;
		$author_form = str_replace('!!user_input_url!!',			rawurlencode(stripslashes($user_input)),							$author_form);
		$author_form = str_replace('!!user_input!!',				htmlentities($user_input,ENT_QUOTES, $charset),						$author_form);
		$author_form = str_replace('!!nbr_lignes!!',				"",														$author_form);
		$author_form = str_replace('!!page!!',						$page,																$author_form);
		$author_form = str_replace('!!author_comment!!',			$this->author_comment,												$author_form);
		print $author_form;
		}
	
	// ---------------------------------------------------------------
	//		replace_form : affichage du formulaire de remplacement
	// ---------------------------------------------------------------
	function replace_form() {
		global $author_replace;
		global $msg;
		global $include_path;
	
		// a compléter
	
		if(!$this->id || !$this->name) {
			require_once("$include_path/user_error.inc.php");
			error_message($msg[161], $msg[162], 1, './autorites.php?categ=auteurs&sub=&id=');
			return false;
		}
	
		$author_replace=str_replace('!!old_author_libelle!!', $this->display, $author_replace);
		$author_replace=str_replace('!!id!!', $this->id, $author_replace);
		print $author_replace;
		return true;
	}
	
	
	// ---------------------------------------------------------------
	//		delete() : suppression de l'auteur
	// ---------------------------------------------------------------
	function delete() {
		global $dbh;
		global $msg;
		
		if(!$this->id)	// impossible d'accéder à cette notice auteur
			return $msg[403]; 
	
		// effacement dans les notices
		// récupération du nombre de notices affectées
		$requete = "SELECT count(1) FROM responsability WHERE ";
		$requete .= "responsability_author='$this->id' ";
	
		$res = mysql_query($requete, $dbh);
		$nbr_lignes = mysql_result($res, 0, 0);
		if($nbr_lignes) {
			// Cet auteur est utilisé dans des notices, impossible de le supprimer
			return '<strong>'.$this->display."</strong><br />${msg[402]}";
		}
		// liens entre autorités
		$aut_link= new aut_link(AUT_TABLE_AUTHORS,$this->id);
		$aut_link->delete();
		
		// effacement dans la table des auteurs
		$requete = "DELETE FROM authors WHERE author_id='$this->id' ";
		mysql_query($requete, $dbh);
		return false;
	}
	
	
	// ---------------------------------------------------------------
	//		replace($by) : remplacement de l'auteur
	// ---------------------------------------------------------------
	function replace($by,$link_save=0) {
	
		global $msg;
		global $dbh;
	
		if (($this->id == $by) || (!$this->id))  {
			return $msg[223];
		}
		$aut_link= new aut_link(AUT_TABLE_AUTHORS,$this->id);
		// "Conserver les liens entre autorités" est demandé
		if($link_save) {
			// liens entre autorités
			$aut_link->add_link_to(AUT_TABLE_AUTHORS,$by);
			// Voir aussi	
			if($this->see){		
				$requete = "UPDATE authors SET author_see='".$this->see."'  WHERE author_id='$by' ";
				@mysql_query($requete, $dbh);
			}		
		}
		$aut_link->delete();
		
		// remplacement dans les responsabilités
		$requete = "UPDATE responsability SET responsability_author='$by' WHERE responsability_author='$this->id' ";
		@mysql_query($requete, $dbh);
		
		// effacement dans les responsabilités
		$requete = "DELETE FROM responsability WHERE responsability_author='$this->id' ";
		@mysql_query($requete, $dbh);
		
		// effacement dans la table des auteurs
		$requete = "DELETE FROM authors WHERE author_id='$this->id' ";
		mysql_query($requete, $dbh);
		
		auteur::update_index($by);
		
		return FALSE;
	}
	
	// ---------------------------------------------------------------
	//		update($value) : mise à jour de l'auteur
	// ---------------------------------------------------------------
	function update($value) {
	
		global $dbh;
		global $msg;
		global $include_path;
		
		if(!$value['name'])
			return false;
	
		// nettoyage des chaînes en entrée		
		$value['name'] = clean_string($value['name']);
		$value['rejete'] = clean_string($value['rejete']);
		$value['date'] = clean_string($value['date']);
		$value['lieu'] = clean_string($value['lieu']);
		$value['ville'] = clean_string($value['ville']);
		$value['pays'] = clean_string($value['pays']);
		$value['subdivision'] = clean_string($value['subdivision']);
		$value['numero'] = clean_string($value['numero']);		
		
		// s'assurer que l'auteur n'existe pas déjà
		switch($value['type']) {
			case 71: // Collectivité
				$and_dedoublonnage=" and author_subdivision ='".$value['subdivision']."' ";
			break;
			case 72: // Congrès
				$and_dedoublonnage=" and author_numero ='".$value['numero']."' ";
			break;			
			default:
				$and_dedoublonnage='';
			break;
		}		
		$dummy = "SELECT * FROM authors WHERE author_name='".$value['name']."'";
		$dummy .= " AND author_rejete='".$value['rejete']."' ";
		$dummy .= "AND author_date='".$value[date]."' and author_id!='".$this->id."' $and_dedoublonnage ";

		$check = mysql_query($dummy, $dbh);
		if (mysql_num_rows($check)) {
			require_once("$include_path/user_error.inc.php");
			warning($msg[200], $msg[220]);
			return FALSE;
		}

		// s'assurer que la forme_retenue ne pointe pas dans les deux sens
		if ($this->id) {
			$dummy = "SELECT * FROM authors WHERE author_id='".$value[voir_id]."' and  author_see='".$this->id."'";
			$check = mysql_query($dummy, $dbh);
			if (mysql_num_rows($check)) {
				require_once("$include_path/user_error.inc.php");
				warning($msg[200], $msg['author_forme_retenue_error']);
				return FALSE;
			}
		}
		$requete  = "SET author_type='$value[type]', ";
		$requete .= "author_name='$value[name]', ";
		$requete .= "author_rejete='$value[rejete]', ";
		$requete .= "author_date='$value[date]', ";		
		$requete .= "author_lieu='".$value["lieu"]."', ";
		$requete .= "author_ville='".$value["ville"]."', ";
		$requete .= "author_pays='".$value["pays"]."', ";
		$requete .= "author_subdivision='".$value["subdivision"]."', ";
		$requete .= "author_numero='".$value["numero"]."', ";		
		$requete .= "author_web='$value[author_web]', ";
		$requete .= "author_see='$value[voir_id]', ";
		$requete .= "author_comment='$value[author_comment]', ";
		$word_to_index = $value["name"]." ".$value["rejete"]." ".$value["lieu"]." ".$value["ville"]." ".$value["pays"]." ".$value["numero"]." ".$value["subdivision"];
		if($value['type'] == 72) $word_to_index.= " ".$value["date"];
		$requete .= "index_author=' ".strip_empty_chars($word_to_index)." '";
	
		if($this->id) {
			// update
			// on checke s'il n'y a pas un renvoi circulaire
			if($this->id == $value['voir_id']) {
				require_once("$include_path/user_error.inc.php"); 
				warning($msg[199], $msg[222]);
				return FALSE;
			}
	
			$requete = 'UPDATE authors '.$requete;
			$requete .= ' WHERE author_id='.$this->id.' ;';
			if(mysql_query($requete, $dbh)) {
				auteur::update_index($this->id);
				// liens entre autorités
				$aut_link= new aut_link(AUT_TABLE_AUTHORS,$this->id);
				$aut_link->save_form();
				return TRUE;
			} else {
				require_once("$include_path/user_error.inc.php"); 
				warning($msg[199], $msg[208]);
				return FALSE;
			}
		} else {
			// creation
			$requete = 'INSERT INTO authors '.$requete.' ';
			if(mysql_query($requete, $dbh)) {
				$this->id=mysql_insert_id();
				// liens entre autorités
				$aut_link= new aut_link(AUT_TABLE_AUTHORS,$this->id);
				$aut_link->save_form();
				return TRUE;
			} else {
				require_once("$include_path/user_error.inc.php"); 
				warning($msg[200], $msg[221]);
				return FALSE;
			}
		}		
	}
		
	// ---------------------------------------------------------------
	//		import() : import d'un auteur
	// ---------------------------------------------------------------
	// fonction d'import de notice auteur (membre de la classe 'author');
	function import($data) {
	
		// cette méthode prend en entrée un tableau constitué des informations éditeurs suivantes :
		//  $data['type']  type de l'autorité (70 , 71 ou 72)
		//	$data['name'] 	élément d'entrée de l'autorité
		//	$data['rejete']	élément rejeté
		//	$data['date']	dates de l'autorité
		// 	$data['lieu']	lieu du congrès	210$e
		// 	$data['ville']	ville du congrès	
		// 	$data['pays']	pays du congrès
		// 	$data['subdivision']	210$b
		// 	$data['numero']	numero du congrès 210$d		
		//	$data['voir_id']	id de la forme retenue (sans objet pour l'import de notices)
		//	$data['author_comment']	commentaire
	
		global $dbh;
	
		// check sur le type de  la variable passée en paramètre
		if(!sizeof($data) || !is_array($data)) {
			// si ce n'est pas un tableau ou un tableau vide, on retourne 0
			return 0;
		}	
		// check sur les éléments du tableau (data['name'] ou data['rejete'] est requis).		
		$long_maxi_name = mysql_field_len(mysql_query("SELECT author_name FROM authors limit 1"),0);
		$long_maxi_rejete = mysql_field_len(mysql_query("SELECT author_rejete FROM authors limit 1"),0);
			
		$data['name'] = rtrim(substr(preg_replace('/\[|\]/', '', rtrim(ltrim($data['name']))),0,$long_maxi_name));
		$data['rejete'] = rtrim(substr(preg_replace('/\[|\]/', '', rtrim(ltrim($data['rejete']))),0,$long_maxi_rejete));	
	
		if(!$data['name'] && !$data['rejete'])
			return 0;
	
		// check sur le type d'autorité
		if(!$data['type']==70 && !$data['type']==71 && !$data['type']==72)
			return 0;	
	
		// tentative de récupérer l'id associée dans la base (implique que l'autorité existe)
	
		// préparation de la requête	
		$key0 = $data['type'];
		$key1 = addslashes($data['name']);
		$key2 = addslashes($data['rejete']);
		$key3 = addslashes($data['date']);
		$key4 = addslashes($data['subdivision']);
		$key5 = addslashes($data['lieu']);
		$key6 = addslashes($data['ville']);
		$key7 = addslashes($data['pays']);
		$key8 = addslashes($data['numero']);
		
		$data['lieu'] = addslashes($data['lieu']);
		$data['ville'] = addslashes($data['ville']);
		$data['pays'] = addslashes($data['pays']);
		$data['subdivision'] = addslashes($data['subdivision']);
		$data['numero'] = addslashes($data['numero']);
		$data['author_comment'] = addslashes($data['author_comment']);
		$data['web'] = addslashes($data['web']);
		
		$query = "SELECT author_id FROM authors WHERE author_type='${key0}' AND author_name='${key1}' AND author_rejete='${key2}' AND author_date='${key3}'";
		if ($data["type"]>70) {
			$query .= " and author_subdivision='${key4}' and author_lieu='${key5}' and author_ville='${key6}' and author_pays='${key7}' and author_numero='${key8}'";
		}
		$query .= " LIMIT 1";
		$result = @mysql_query($query, $dbh);
		if(!$result) die("can't SELECT in database");
		// résultat
	
		// récupération du résultat de la recherche
		$aut  = mysql_fetch_object($result);
		// du résultat et récupération éventuelle de l'id
		if($aut->author_id)
			return $aut->author_id;
	
		// id non-récupérée, il faut créer l'auteur
		$query = "INSERT INTO authors SET author_type='$key0', ";
		$query .= "author_name='$key1', ";
		$query .= "author_rejete='$key2', ";
		$query .= "author_date='$key3', ";
		$query .= "author_lieu='".$data['lieu']."', ";
		$query .= "author_ville='".$data['ville']."', ";
		$query .= "author_pays='".$data['pays']."', ";
		$query .= "author_subdivision='".$data['subdivision']."', ";
		$query .= "author_numero='".$data['numero']."', ";		
		$query .= "author_web='".$data['web']."', ";		
		$query .= "author_comment='".$data['author_comment']."', ";
		$word_to_index = $key1." ".$key2." ".$data['lieu']." ".$data['ville']." ".$data['pays']." ".$data['numero']." ".$data["subdivision"];
		if($key0 == "72") $word_to_index.= " ".$key3;
		$query .= "index_author=' ".strip_empty_chars($word_to_index)." ' ";
		
		$result = @mysql_query($query, $dbh);
		if(!$result) die("can't INSERT into table authors :<br /><b>$query</b> ");
	
		return mysql_insert_id($dbh);
	}
		
	// ---------------------------------------------------------------
	//		search_form() : affichage du form de recherche
	// ---------------------------------------------------------------
	function search_form($type_autorite=7) {
		global $user_query;
		global $msg;
		global $user_input,$charset;
		
		$sel_tout = ($type_autorite==7) ? 'selected': " ";
		$sel_pp = ($type_autorite==70) ? 'selected': " ";
		$sel_coll = ($type_autorite==71) ? 'selected' :" ";
		$sel_congres = ($type_autorite==72) ? 'selected' :" ";
		
		$libelleBtn=$msg[207];		
		if($type_autorite==7 || $type_autorite==70) $libelleBtn=$msg[207];
		elseif ($type_autorite==71) $libelleBtn=$msg["aut_ajout_collectivite"];
		elseif ($type_autorite==72)  $libelleBtn=$msg["aut_ajout_congres"];
		
		$libelleRech=$msg[133];		
		if($type_autorite==7 || $type_autorite==70) $libelleRech=$msg[133];
		elseif ($type_autorite==71) $libelleRech=$msg[204];
		elseif ($type_autorite==72)  $libelleRech=$msg["congres_libelle"];
		
		$url= "\"document.location = './autorites.php?categ=auteurs&sub=reach&id=&type_autorite='+this.value\"";	
		$sel_autorite_auteur.= "<select class='saisie-30em' id='id_autorite' name='type_autorite' onchange=$url>";
		$sel_autorite_auteur.= "<option value ='7' $sel_tout>".$msg["autorites_auteurs_all"]."</option>";
		$sel_autorite_auteur.= "<option value='70'$sel_pp>$msg[203]</option>";
		$sel_autorite_auteur.= "<option value='71'$sel_coll>$msg[204]</option>";
		$sel_autorite_auteur.= "<option value='72'$sel_congres>".$msg["congres_libelle"]."</option>";
		$sel_autorite_auteur.= "</select>";		
		
		$user_query = str_replace("<!-- sel_autorites -->", $sel_autorite_auteur, $user_query);		

		$user_query = str_replace ('!!user_query_title!!', $msg[357]." : ".$libelleRech , $user_query);
		$user_query = str_replace ('!!action!!', './autorites.php?categ=auteurs&sub=reach&id=', $user_query);
		$user_query = str_replace ('!!add_auth_msg!!', $libelleBtn , $user_query);
		$user_query = str_replace ('!!add_auth_act!!', './autorites.php?categ=auteurs&sub=author_form&type_autorite='.$type_autorite, $user_query);
		$user_query = str_replace ('<!-- lien_derniers -->', "<a href='./autorites.php?categ=auteurs&sub=author_last'>$msg[1310]</a>", $user_query);
		$user_query = str_replace("!!user_input!!",htmlentities(stripslashes($user_input),ENT_QUOTES, $charset),$user_query);
				
		print pmb_bidi($user_query) ;
		}
	//---------------------------------------------------------------
	// update_index($id) : maj des n-uplets la table notice_global_index en rapport avec cet author	
	//---------------------------------------------------------------
	function update_index($id) {
		global $dbh;
		// On cherche tous les n-uplet de la table notice correspondant à cet auteur.
		$found = mysql_query("select distinct responsability_notice from responsability where responsability_author='".$id."'",$dbh);
		// Pour chaque n-uplet trouvés on met a jour la table notice_global_index avec l'auteur modifié :
		while(($mesNotices = mysql_fetch_object($found))) {
			$notice_id = $mesNotices->responsability_notice;
			notice::majNoticesGlobalIndex($notice_id);
			notice::majNoticesMotsGlobalIndex($notice_id,'author');
		}
	}
	}
	
} // class auteur


