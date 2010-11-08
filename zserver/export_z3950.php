<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: export_z3950.php,v 1.6 2008-03-26 12:55:54 ohennequin Exp $

$base_path="../..";
include($base_path."/includes/db_param.inc.php");
include($base_path."/admin/convert/export.class.php");
include($base_path."/admin/convert/xml_unimarc.class.php");
require_once($base_path."/includes/isbn.inc.php");

function make_error($nerr,$err_message) {
	echo $nerr."@".$err_message."@";
	exit();
}

if (!@mysql_connect(SQL_SERVER,USER_NAME,USER_PASS)) make_error(1,"Could'nt connect to database server"); 
if (!@mysql_select_db(DATA_BASE)) make_error(2,"Database unknown");

//Commande envoyée
$command=$_GET["command"];
//Requete
$query=$_GET["query"];
function construct_query($query,$not,$level,$argn="") {
	//La requête commence-t-elle par and, or ou and not ?
	$pos=strpos($query,"and not");
	if (($pos!==false)&&($pos==0)) {
		$ope="and not";
	} else {
		$pos=strpos($query,"or");
		if (($pos!==false)&&($pos==0)) {
			$ope="or";
		} else {
			$pos=strpos($query,"and");
			if (($pos!==false)&&($pos==0)) {
				$ope="and";
			} else $ope="";
		}
	}
	if ($ope!="") {
		$arqs=array();
		preg_match("/^".$ope." arg".$level."!1\((.*)\) arg".$level."!2\((.*)\)$/",$query,$args);
		$return1=construct_query($args[1],0,$level+1,1);
		if ($ope=="and not")
			$return2=construct_query($args[2],1,$level+1,2);
		else
			$return2=construct_query($args[2],0,$level+1,2);
		if ($ope=="and not") $ope="and";
		$requete="create temporary table r$level ";
		if ($ope=="and") {
			$requete.="select $return1.notice_id from $return1, $return2 where $return1.notice_id=$return2.notice_id";
			@mysql_query($requete);
		}
		else {
			$requete.="select notice_id from $return1";
			@mysql_query($requete);
			$requete="insert into r$level select $return2.notice_id from $return2,$return1 where $return2.notice_id!=$return1.notice_id";
			@mysql_query($requete);
		}
		$return="r$level";
	} else {
		$use=explode("=",$query);
		switch ($use[0]) {
			//Titre
			case 4:
				if ($not)
					$requete="select notice_id from notices where (tit1 not like '%".$use[1]."%' and tit2 not like '%".$use[1]."%' and tit3 not like '%".$use[1]."%' and tit4 not like '%".$use[1]."%')";
				else
					$requete="select notice_id from notices where (tit1 like '%".$use[1]."%' or tit2 like '%".$use[1]."%' or tit3 like '%".$use[1]."%' or tit4 like '%".$use[1]."%')";
				break;
			//ISBN
			case 7:
			    if(isISBN($use[1])) {
					// si la saisie est un ISBN
					$code = formatISBN($use[1]);
					// si échec, ISBN erroné on le prend sous cette forme
					if(!$code) $code = $use[1];
			    } else $code = $use[1];
				if ($not)
					$requete="select notice_id from notices where (code!='".$code."')";
				else
					$requete="select notice_id from notices where (code='".$code."')";
				break;
			case 1003:
				if ($not) {
					$requete="create temporary table aut1 select notice_id,concat(author_name,' ',author_rejete) as auth from notices left join authors on author_id=aut1_id";
					@mysql_query($requete);
					$requete="create temporary table aut2 select notice_id,concat(author_name,' ',author_rejete) as auth from notices left join authors on author_id=aut2_id";
					@mysql_query($requete);
					$requete="create temporary table aut3 select notice_id,concat(author_name,' ',author_rejete) as auth from notices left join authors on author_id=aut3_id";
					@mysql_query($requete);
					$requete="create temporary table aut4 select notice_id,concat(author_name,' ',author_rejete) as auth from notices left join authors on author_id=aut4_id";
					@mysql_query($requete);
					$requete="create temporary table aut select aut1.notice_id, concat(ifnull(aut1.auth,''),' ',ifnull(aut2.auth,''),' ',ifnull(aut3.auth,''),' ',ifnull(aut4.auth,'')) as auth from aut1, aut2, aut3, aut4 where aut2.notice_id=aut1.notice_id and aut3.notice_id=aut1.notice_id and aut4.notice_id=aut1.notice_id";
				    @mysql_query($requete);
				    $requete="select notice_id from aut where auth not like '%".$use[1]."%'";
				}
				else 
					$requete="select notice_id from notices,authors where (concat(author_name,' ',author_rejete) like '%".$use[1]."%' and (author_id=aut1_id or author_id=aut2_id or author_id=aut3_id or author_id=aut3_id))";
				break;

			// Sujet - Catégories
			case 21:
				if ($not) {
				    $requete="CREATE TEMPORARY TABLE cat SELECT DISTINCT notices_categories.notcateg_notice as notice_id, index_categorie as cat FROM categories, notices_categories WHERE notcateg_categorie = categ_id ORDER BY notices_categories.ordre_categorie";
				    @mysql_query($requete);
					$requete="SELECT DISTINCT notice_id FROM cat WHERE cat not like '%".$use[1]."%'";
					} else { 
						$requete="SELECT DISTINCT notice_id FROM notices_categories, categories, notices WHERE categ_id=notcateg_categorie AND notice_id=notcateg_notice AND index_categorie like '%".$use[1]."%'";
						}
				break;
			default:
				make_error(3,"1=".$use[0]);
				break;
		}
		$requete="create temporary table r".$level."_".$argn." ".$requete;
		@mysql_query($requete);
		$return="r".$level."_".$argn;
	}
	return $return;
}

/*
function construct_query($query,&$sup_tables,$not,$level) {
	//La requête commence-t-elle par and, or ou and not ?
	$pos=strpos($query,"and not");
	if (($pos!==false)&&($pos==0)) {
		$ope="and not";
	} else {
		$pos=strpos($query,"or");
		if (($pos!==false)&&($pos==0)) {
			$ope="or";
		} else {
			$pos=strpos($query,"and");
			if (($pos!==false)&&($pos==0)) {
				$ope="and";
			} else $ope="";
		}
	}
	
	//Si il y a un opérateur, on récupère les deux aguments et on les traite comme des nouvelles requetes
	if ($ope!="") {
		$arqs=array();
		preg_match("/^".$ope." arg".$level."!1\((.*)\) arg".$level."!2\((.*)\)$/",$query,$args);
		$return1=construct_query($args[1],$sup_tables,0,$level+1);
		if ($ope=="and not")
			$return2=construct_query($args[2],$sup_tables,1,$level+1);
		else
			$return2=construct_query($args[2],$sup_tables,0,$level+1);
		if ($ope=="and not") $ope="and";
		$return = sprintf("%s %s %s",$return1,$ope,$return2);
	} else {
		$use=explode("=",$query);
		switch ($use[0]) {
			//Titre
			case 4:
				if ($not)
					$return="(tit1 not like '%".$use[1]."%' and tit2 not like '%".$use[1]."%' and tit3 not like '%".$use[1]."%' and tit4 not like '%".$use[1]."%')";
				else
					$return="(tit1 like '%".$use[1]."%' or tit2 like '%".$use[1]."%' or tit3 like '%".$use[1]."%' or tit4 like '%".$use[1]."%')";
				break;
			//ISBN
			case 7:
			    if(isISBN($use[1])) {
					// si la saisie est un ISBN
					$code = formatISBN($use[1]);
					// si échec, ISBN erroné on le prend sous cette forme
					if(!$code) $code = $use[1];
			    }
				if ($not)
					$return="(code!='".$code."')";
				else
					$return="(code='".$code."')";
				break;
			case 1003:
				if ($not)
					$return="(concat(author_name,' ',author_rejete) not like '%".$use[1]."%' and (author_id=aut1_id or author_id=aut2_id or author_id=aut3_id or author_id=aut4_id))";
				else 
					$return="(concat(author_name,' ',author_rejete) like '%".$use[1]."%' and (author_id=aut1_id or author_id=aut2_id or author_id=aut3_id or author_id=aut3_id))";
				$sup_tables.=",authors";
				break;
			default:
				make_error(3,"1=".$use[0]);
				break;
		}
	}
	return $return;
}
*/

switch ($command) {
	case "search":
		$sup_tables="";
		$sql_query=construct_query($query,0,0);
		$sql_query="select notice_id from $sql_query limit 100";
		$resultat=@mysql_query($sql_query);
		echo "0@No errors@";
		echo @mysql_num_rows($resultat);
		while (list($id)=@mysql_fetch_row($resultat)) {
			echo "@$id";
		}
		break;
	case "get_notice":
		$id=$query;
		$e = new export(array($id));
		$e -> get_next_notice();
		$toiso = new xml_unimarc();
		$toiso->XMLtoiso2709_notice($e->notice);
		echo "0@No errors@";
		echo $toiso->notices_[0];
		break;
}
