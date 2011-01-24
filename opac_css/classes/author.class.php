<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: author.class.php,v 1.23 2010-10-07 07:42:28 arenou Exp $

// définition de la classe de gestion des 'auteurs'

if ( ! defined( 'AUTEUR_CLASS' ) ) {
  define( 'AUTEUR_CLASS', 1 );

class auteur {

	// ---------------------------------------------------------------
	//		propriétés de la classe
	// ---------------------------------------------------------------

	var $id;            // MySQL id in table 'authors'
	var $type;          // author type (70 or 71)
	var $name;          // author name
	var $rejete;        // author name (rejected element)
	var $date;          // dates
	var $see;           // 'see' author MySQL id
	// var $see_libelle;  //// printable form of 'see' author (in fact 'display' of retained form)
	var $display;      //// usable form for displaying ( _name_, _rejete_ (_date_) )
	var $isbd_entry;   //// isbd like version ( _rejete_ _name_ (_date_))
	var $author_web;	// web de l'auteur
	var $author_comment; //
	var $author_web_link;	// lien web de l'auteur


// ---------------------------------------------------------------
//		auteur($id) : constructeur
// ---------------------------------------------------------------
function auteur($id)
{
	// on regarde si on a une notice-objet ou un id de notice
	if (is_object($id)) {
		$this->get_primaldatafrom($id);
	} else {
		$this->id = $id;
		$this->get_primaldata();
	}
	$this->get_otherdata();
}

// ---------------------------------------------------------------
//  get_primaldata() : récupération infos auteur à partir de l'id
// ---------------------------------------------------------------
function get_primaldata() {
	global $dbh;
	$requete = "SELECT * FROM authors WHERE author_id=$this->id LIMIT 1 ";
	$result = @mysql_query($requete, $dbh);
	if(mysql_num_rows($result)) {
		$obj = mysql_fetch_object($result);
		mysql_free_result($result);
		$this->get_primaldatafrom($obj);
		} else {
			// pas d'auteur avec cette clé
			$this->id          = 0;
			$this->type        = '';
			$this->name        = '';
			$this->rejete      = '';
			$this->date       = '';
			$this->see         = '';
			$this->display     = '';
			$this->isbd_entry  = '';
			$this->author_web = '' ;
			$this->author_comment = '' ;
			$this->subdivision = '';
			$this->lieu	= '';
			$this->salle = '';
			$this->ville = '';
			$this->pays	= '';
			$this->numero = '';			
			}
	}

// ---------------------------------------------------------------
//  get_primaldatafrom($obj) : récupération infos auteur à partir d'un auteur-objet
// ---------------------------------------------------------------
function get_primaldatafrom($obj) {
	$this->id       = $obj->author_id;
	$this->type     = $obj->author_type;
	$this->name     = $obj->author_name;
	$this->rejete   = $obj->author_rejete;
	$this->date     = $obj->author_date;
	$this->see      = $obj->author_see;
	$this->author_web = $obj->author_web;
	$this->author_comment = $obj->author_comment;
	//Ajout pour les congrès
	$this->subdivision	= $obj->author_subdivision	;
	$this->lieu	= $obj->author_lieu	;
	$this->salle = $obj->author_salle	;
	$this->ville = $obj->author_ville	;
	$this->pays	= $obj->author_pays	;
	$this->numero = $obj->author_numero	;	
}

// ---------------------------------------------------------------
function get_otherdata() {
	global $msg;
	if($this->type==71 ) {		
		// C'est une collectivité		
		if($this->subdivision) {
			$this->isbd_entry = $this->name." ".$this->subdivision;
			$this->display = $this->name.", ".$this->subdivision;
		} else {
			$this->isbd_entry = $this->name;
			$this->display = $this->name;
		}
		if($this->rejete ) {
			$this->info_bulle=$this->rejete; 
		}		
		$liste_field=$liste_lieu=array();
		if($this->numero) {
			$liste_field[]=	$this->numero;
		}				
		if($this->date) {
			$liste_field[]=	$this->date;
		}
		if($this->lieu) {
			$liste_lieu[]=	$this->lieu;
		}
		if($this->ville) {
			$liste_lieu[]=	$this->ville;
		}	
		if($this->pays) {
			$liste_lieu[]=	$this->pays;
		}
		if(count($liste_lieu))	$liste_field[]=	implode(", ",$liste_lieu);
		if(count($liste_field))	{
			$liste_field=implode("; ",$liste_field);
			$this->isbd_entry .= ' ('.$liste_field.')';
			$this->display .= ' ('.$liste_field.')';
		}
	} elseif( $this->type==72) {		
		// C'est un congrès
		$libelle=$msg["congres_libelle"].": ";

		if($this->rejete) {
			$this->isbd_entry = $libelle.$this->name." ".$this->rejete;
			$this->display = $libelle.$this->name." ".$this->rejete;
		} else {
			$this->isbd_entry = $this->name;
			$this->display = $this->name;
		}		
		$liste_field=$liste_lieu=array();
		if($this->subdivision) {
			$liste_field[]=	$this->subdivision;
		}		
		if($this->numero) {
			$liste_field[]=	$this->numero;
		}				
		if($this->date) {
			$liste_field[]=	$this->date;
		}
		if($this->lieu) {
			$liste_lieu[]=	$this->lieu;
		}
		if($this->ville) {
			$liste_lieu[]=	$this->ville;
		}	
		if($this->pays) {
			$liste_lieu[]=	$this->pays;
		}
		if(count($liste_lieu))	$liste_field[]=	implode(", ",$liste_lieu);
		if(count($liste_field))	{
			$liste_field=implode("; ",$liste_field);
			$this->isbd_entry .= ' ('.$liste_field.')';
			$this->display .= ' ('.$liste_field.')';
		}
	} else {
		// C'est un auteur physique
		if($this->rejete) {
			$this->isbd_entry = "$this->name, $this->rejete";
			$this->display = "$this->rejete $this->name";
		} else {
			$this->isbd_entry = "$this->name";
			$this->display = "$this->name";
		}
		if($this->date) $this->isbd_entry .= ' ('.$this->date.')';
	}
	if($this->author_web) $this->author_web_link = " <a href='$this->author_web' target='_blank'><img src='./images/globe.gif' border='0' /></a>";
	else $this->author_web_link = "" ;

}

function get_similar_name($author_type='72',$from=0,$number=30) {
	global $dbh;
	if($author_type) $and_author_type = " and author_type='$author_type' ";
	$requete = "SELECT * FROM authors WHERE author_name='".$this->name."' and author_id != ".$this->id." $and_author_type order by author_date, author_lieu  LIMIT $from, $number";
	$result = @mysql_query($requete, $dbh);
	if(mysql_num_rows($result)) {
		$i=0;
		while(($obj = mysql_fetch_object($result))) {
			$this->similar_name[$i]->id       = $obj->author_id;
			$this->similar_name[$i]->type     = $obj->author_type;
			$this->similar_name[$i]->name     = $obj->author_name;
			$this->similar_name[$i]->rejete   = $obj->author_rejete;
			$this->similar_name[$i]->date     = $obj->author_date;
			$this->similar_name[$i]->see      = $obj->author_see;
			$this->similar_name[$i]->author_web = $obj->author_web;
			$this->similar_name[$i]->author_comment = $obj->author_comment;
			$this->similar_name[$i]->subdivision	= $obj->author_subdivision	;
			$this->similar_name[$i]->lieu	= $obj->author_lieu	;
			$this->similar_name[$i]->salle = $obj->author_salle	;
			$this->similar_name[$i]->ville = $obj->author_ville	;
			$this->similar_name[$i]->pays	= $obj->author_pays	;
			$this->similar_name[$i]->numero = $obj->author_numero;
			$requete = "SELECT count(distinct responsability_notice) FROM responsability WHERE responsability_author=".$this->similar_name[$i]->id;			
			$res_count = mysql_query($requete);			
			if ($res_count) $this->similar_name[$i]->nb_notice =  mysql_result($res_count,0,0); 
			else $this->similar_name[$i]->nb_notice=0;
			$i++;		
		}
	}	
}
function print_similar_name($nb_by_line=3) {
	// Template
	global $base_path,
		$author_display_similar_congres, 
		$author_display_similar_congres_ligne, 
		$author_display_similar_congres_element;
	
	$nb=count($this->similar_name);	
	$congres="";
	for($i=0;$i<$nb;$i++) {
		$data=$this->similar_name[$i];
		
		$label= $data->numero." ".$data->date." ".$data->lieu;
		$detail= "";
		if($this->type!=71)	$detail.= $data->rejete." ";
		$detail.= $data->subdivision." "
		.$data->salle." "
		.$data->ville." "
		.$data->pays;
		if($data->nb_notice) {
			$detail.=" (".$data->nb_notice.")";
			$img_folder="<img src='$base_path/images/folder_search.gif' border=0 align='absmiddle'>";	
		}else {
			$img_folder="<img src='$base_path/images/folder.gif' border=0 align='absmiddle'>";
		}
		
		$congres_element = str_replace("!!congres_label!!",$label, $author_display_similar_congres_element);
		$congres_element = str_replace("!!img_folder!!",$img_folder, $congres_element);
		$congres_element = str_replace("!!congres_id!!",$data->id, $congres_element);
		$congres_element = str_replace("!!congres_detail!!",$detail, $congres_element);
		$congres_ligne.=$congres_element;  
		if(!(($i+1)%$nb_by_line) || (($i+1)==$nb)) {
			$congres.= str_replace("!!congres_ligne!!",$congres_ligne, $author_display_similar_congres_ligne);	
			$congres_ligne='';
		} 
	}
	if ($nb) $congres_contens= str_replace("!!congres_contens!!",$congres, $author_display_similar_congres);
	return 	$congres_contens;
	
}
function print_congres_titre() {
	$print=$this->name;
	if($this->type==71 && $this->subdivision) {
		// Collectivité
		$print.= " ".$this->subdivision;  
	}
	elseif($this->rejete) {
		$print.= " ".$this->rejete;
	}		
	$liste_field=$liste_lieu=array();
	if($this->subdivision && !$this->type==71) {
		$liste_field[]=	$this->subdivision;
	}
	if($this->numero) {
		$liste_field[]=	$this->numero;
	}				
	if($this->date) {
		$liste_field[]=	$this->date;
	}
	if($this->lieu) {
		$liste_lieu[]=	$this->lieu;
	}
	if($this->ville) {
		$liste_lieu[]=	$this->ville;
	}	
	if($this->pays) {
		$liste_lieu[]=	$this->pays;
	}
	if(count($liste_lieu))	$liste_field[]=	implode(", ",$liste_lieu);
	if(count($liste_field))	{
		$liste_field=implode("; ",$liste_field);
		$print .= ' > '.$liste_field;
	}	
	return $print;
	
}
// ---------------------------------------------------------------
function print_resume($level = 2,$css='') {
	global $css;
	if(!$this->id)
		return;

	// adaptation par rapport au niveau de détail souhaité
	switch ($level) {
		// case x :
		case 1 :
			global $author_level1_display;
			global $author_level1_no_dates_info;

			$author_display = $author_level1_display;
			$author_no_dates_info = $author_level1_no_dates_info;
			break;

		case 2 :
		default :
			global $author_level2_display;
			global $author_level2_no_dates_info;
			global $author_level2_display_congres;
			
			if($this->type==72) {
				$author_display = $author_level2_display_congres;
			} else {
				$author_display = $author_level2_display;
			}
			
			$author_no_dates_info = $author_level2_no_dates_info;
		break;
	}

	$print = $author_display;

	// remplacement des champs statiques
	$print = str_replace("!!id!!", $this->id, $print);
	$print = str_replace("!!name!!", $this->name, $print);
	$print = str_replace("!!rejete!!", $this->rejete, $print);
	$print = str_replace("!!lieu!!", $this->lieu, $print);
	$print = str_replace("!!ville!!", $this->lieu, $print);
	$print = str_replace("!!pays!!", $this->pays, $print);
	$print = str_replace("!!numero!!", $this->numero, $print);
	$print = str_replace("!!subdivision!!", $this->subdivision, $print);
	if ($this->author_web) $print = str_replace("!!site_web!!", "<a href='$this->author_web' target='_blank'><img src='./images/globe.gif' border='0' /></a>", $print);
		else $print = str_replace("!!site_web!!", "", $print);
	$print = str_replace("!!date!!", $this->date, $print);
	$print = str_replace("!!aut_comment!!", nl2br($this->author_comment), $print);

	// remplacement des champs dynamiques
	if ((ereg("!!allname!!", $print)) || (ereg("!!allnamenc!!", $print))) {
		if($this->type==71) {
			// Collectivité
			$remplacement = $this->name;
			if ($this->subdivision) $remplacement = $remplacement." ".$this->subdivision;
			if($this->rejete ) {
				 $this->info_bulle=$this->rejete; 
			}
			$liste_field=$liste_lieu=array();
			if($this->numero) {
				$liste_field[]=	$this->numero;
			}				
			if($this->date) {
				$liste_field[]=	$this->date;
			}
			if($this->lieu) {
				$liste_lieu[]=	$this->lieu;
			}
			if($this->ville) {
				$liste_lieu[]=	$this->ville;
			}	
			if($this->pays) {
				$liste_lieu[]=	$this->pays;
			}
			if(count($liste_lieu))	$liste_field[]=	implode(", ",$liste_lieu);
			if(count($liste_field))	{
				$liste_field=implode("; ",$liste_field);
				$remplacement .= ' ('.$liste_field.')';
			}	
		} elseif($this->type==72) {
			// Congrès
			$remplacement = $this->name;
			if ($this->rejete != "") $remplacement = $remplacement." ".$this->rejete;
			$liste_field=$liste_lieu=array();
			if($this->subdivision) {
				$liste_field[]=	$this->subdivision;
			}			
			if($this->numero) {
				$liste_field[]=	$this->numero;
			}				
			if($this->date) {
				$liste_field[]=	$this->date;
			}
			if($this->lieu) {
				$liste_lieu[]=	$this->lieu;
			}
			if($this->ville) {
				$liste_lieu[]=	$this->ville;
			}	
			if($this->pays) {
				$liste_lieu[]=	$this->pays;
			}
			if(count($liste_lieu))	$liste_field[]=	implode(", ",$liste_lieu);
			if(count($liste_field))	{
				$liste_field=implode("; ",$liste_field);
				$remplacement .= ' ('.$liste_field.')';
			}	
		} else {
			// auteur physique
			$remplacement = $this->name;
			if ($this->rejete != "") $remplacement = $this->rejete." ".$remplacement;			
		}	
		if (ereg("!!allname!!", $print)) {
			$remplacement = "<a href='index.php?lvl=author_see&id=$this->id' title='".$this->info_bulle."'>$remplacement</a>";
			$print = str_replace("!!allname!!", $remplacement, $print);
		} else $print = str_replace("!!allnamenc!!", $remplacement, $print);
	}

	if (ereg("!!dates!!", $print)) {
		if ($this->date != "") {
			$remplacement = " ($this->date)";
			} else $remplacement = $author_no_dates_info;
		$print = str_replace("!!dates!!", $remplacement, $print);
	}

	return $print;
	}
} # fin de définition de la classe auteur

} # fin de délaration
