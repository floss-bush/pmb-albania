<?php 

/* Génération automatique de la structure de la bases de données
* Le script a besoin de trois fichiers générés par le logiciel DeZign :
* Fichier links.html : rapport HTML les relations entre tables (Tools->Reports->Sorted list of all relationships (HTML)
* Fichier datadictionary.txt : export des définitions des tables en format CSV (File->Export->Export Repository to text file->Tables and colums... et sélectionnez Represent booléean True/False not quoted)
* Fichier scheme.gif : export image du schéma de base de données (File->Export->Export diagram to image)
*/

error_reporting(0);
//Lecture des relations
$fp=fopen("links.html","r");
$str=fread($fp,filesize("links.html"));
fclose($fp);

//Remplacer les fins de ligne de tableau par des sauts de ligne
$str=str_replace("</TR>","\n",$str);

//Remplacer les fins de case de tableau par des ';'
$str=str_replace("</TD>",";",$str);

//Supression des tags HTML restants
$str=strip_tags($str);

//Remplacement des doubles apostrophes par une seule
$str=str_replace("''","'",$str);

//Création du tableau de toutes les lignes
$ln=explode("\n",$str);

//Version à la 4ième ligne
$tv=explode(";",$ln[3]);
$version=$tv[1];

//On commence à la 9ième ligne car les 8 premières sont l'entête du projet
for ($i=8; $i<count($ln); $i+=5)
{
	//Une relation est exprimée sur 8 lignes :
	//1: nom_de_la_realtion;
	//2: Description;xxxxxxx;
	//3: Phrase;xxxxxxxx;
	//4: From entity;nom_de_la_table_origine;
	//5: To entity;nom_de_la_table_de_destination;
	
	//Récupération de la phrase
	$phrase=explode(";",$ln[$i+2]);
	//Récupération de la table origine
	$from=explode(";",$ln[$i+3]);
	//Récupération de la table destination
	$to=explode(";",$ln[$i+4]);
	
	//Si la phrase n'est pas vide
	if ($phrase[1]!="")
	{
		$t=array();
		$t["PHRASE"]=$phrase[1];
		
		//Inscription de la phrase et de la table liée dans le tableau des relations de la table From	
		$t["LINKED"]=$to[1];
		if (!isset($rel[$from[1]])) $rel[$from[1]]=array();
		if (array_search($t,$rel[$from[1]])===false)
			$rel[$from[1]][]=$t;
		//Inscription de la phrase et de la table liée dans le tableau des relations de la table To	
		$t["LINKED"]=$from[1];
		if (!isset($rel[$to[1]])) $rel[$to[1]]=array();
		if (array_search($t,$rel[$to[1]])===false)
			$rel[$to[1]][]=$t;
	}
}

//Lecture du fichier de description des tables
$fp=fopen("datadictionary.txt","r");
$str=fread($fp,filesize("datadictionary.txt"));
fclose($fp);

//Création du tableau de toutes les lignes
$ln=explode("\n",$str);

//Lecture des tables
for ($i=1; $i<count($ln); $i++)
{
	//Récupération des paramètres de chaque colonne des tables
	$param=explode(";",$ln[$i]);
	if (!isset($tables_inv[$param[0]]))
	{
		$t=array();
		$t["NAME"]=$param[0];
		$t["DESCRIPTION"]=$param[1];
		$t["ABBRV"]=$param[2];
		$tables[]=$t;
		$tables_inv[$param[0]]=count($tables)-1;
	}
}

//Lecture des champs des tables
for ($i=1; $i<count($ln); $i++)
{
	$param=explode(";",$ln[$i]);
	$index=$tables_inv[$param[0]];
	$t=array();
	$t["NAME"]=$param[3];
	$t["DATATYPE"]=$param[4]." - ".$param[5]." - ".$param[6];
	if ($param[8]=="True") $t["PRIMARY"]="Clef primaire"; else $t["PRIMARY"]="&nbsp;";
	if ($param[10]=="True") $t["UNIQUE"]="Unique"; else $t["UNIQUE"]="&nbsp;";
	if ($param[11]=="True") $t["REQUIRED"]="Obligatoire"; else $t["REQUIRED"]="&nbsp;";
	if ($param[12]!="") $t["LINKED"]="<a href=\"db_description.php?table=".$tables_inv[$param[12]]."&table_old=$index\" onClick=\"parent.tables.location='db_tables.php#".$tables_inv[$param[12]]."'\">".$param[12]."</a>.".$param[13]; else $t["LINKED"]="&nbsp;";
	if ($param[14]!="") $t["DESCRIPTION"]=$param[14]; else $t["DESCRIPTION"]="&nbsp;";
	if ($param[16]!="") $t["DEFAUT"]=$param[16]; else $t["DEFAUT"]="&nbsp;";
	$tables[$index]["COLUMS"][]=$t;
}
?>