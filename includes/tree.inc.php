<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: tree.inc.php,v 1.4 2007-03-10 09:46:46 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

//Création d'un arbre de navigation dans les catégories

function gen_div($categ_parent,$level,$prefix_name, $is_last, $last_before, $js) {
	global $cnt;
	global $base_path;
	
	//On cherche d'abord les dossiers
   	//$requete="select distinct a0.categ_id as id,a0.categ_libelle as libelle from categories as a0, categories as a1 where a0.categ_parent=$categ_parent and a1.categ_parent=a0.categ_id ";
   	$requete="SELECT distinct a0.categ_id as id, a0.categ_libelle as libelle, IF(a1.categ_id is null,0,1) AS dossier, a0.categ_parent as parent FROM categories a0 left JOIN categories a1 ON a1.categ_parent = a0.categ_id where a0.categ_parent=$categ_parent order by libelle ";
	$resultat=mysql_query($requete);
   	$d=0;
   	$f=0;
   	while ($res=mysql_fetch_object($resultat)) {
   		if (!$res->dossier) {
   			$docs[ID][$f]=$res->id;
   			$docs[LIBELLE][$f]=$res->libelle;
   			$f++;
   			} else {
		   		$folders[ID][$d]=$res->id;
   				$folders[LIBELLE][$d]=$res->libelle;
   				$d++;
   				}
   	}
   	
   	if (!isset($folders[ID])) $folders[ID]=array();
   	
   	for ($i=0; $i<count($folders[ID]); $i++) {
   		echo "<div id=\"".$prefix_name.".".$i."\">";
   		$t_l=explode(",",$last_before);
   		for ($j=0; $j<$level-1; $j++) {
   			if (!$t_l[$j]) $img_vert="$base_path/images/tree/ftv2vertline.gif"; else $img_vert="$base_path/images/tree/ftv2blank.gif";
   			echo "<img src=\"$img_vert\" border=0 align=\"center\">";
   		}
   		if (($i==count($folders[ID])-1)&&(count($docs)==0)) $img_node="$base_path/images/tree/ftv2plastnode.gif"; else $img_node="$base_path/images/tree/ftv2pnode.gif";
   		if (($i==count($folders[ID])-1)&&(count($docs)==0)) $last=1; else $last=0; 
   		echo "<img src=\"".$img_node."\" border=0 align=center onClick=\"expand('".$prefix_name.".".$i."',$last)\" id=\"node-".$prefix_name.".".$i."\">";
   		echo "<img src=\"$base_path/images/tree/ftv2folderclosed.gif\" border=0 align=center id=\"folder-".$prefix_name.".".$i."\">";
   		$nb_notices=$cnt[$folders[ID][$i]];
   		$link=str_replace("!!id!!",$folders[ID][$i],$js);
   		if ($nb_notices) echo "<a href=\"\" onClick=\"$link\">".$folders[LIBELLE][$i]." ($nb_notices)</a>"; else echo $folders[LIBELLE][$i];
   		flush();
   		echo "<div id=\"".$prefix_name.".".$i."-contains"."\" style=\"display:none\">";
   		$last_before_=$last_before;
   		if ($last_before_!="") $last_before_.=",";
	    	$last_before_.=$last;
   		gen_div($folders[ID][$i],$level+1,$prefix_name.".".$i,$last,$last_before_,$js);
   		
   		echo "</div>\n";
   		echo "</div>\n";
   	}
   	
   	for ($i=0; $i<count($docs[ID]); $i++) {
   		$link=str_replace("!!id!!",$docs[ID][$i],$js);
   		echo "<div id=\"doc-".$prefix_name.".".$i."\">";
   		$t_l=explode(",",$last_before);
   		for ($j=0; $j<$level-1; $j++) {
   			if (!$t_l[$j]) $img_vert="$base_path/images/tree/ftv2vertline.gif"; else $img_vert="$base_path/images/tree/ftv2blank.gif";
   			echo "<img src=\"$img_vert\" border=0 align=\"center\">";
   		}
   		if ($i==count($docs[ID])-1) $img_node="$base_path/images/tree/ftv2lastnode.gif"; else $img_node="$base_path/images/tree/ftv2node.gif";
   		echo "<img src=\"".$img_node."\" border=0 align=center>";
   		echo "<img src=\"$base_path/images/tree/ftv2doc.gif\" border=0 align=center>";
   		$nb_notices=$cnt[$docs[ID][$i]];
   		if ($nb_notices!=0) echo "<a href=\"\" onClick=\"$link\">".$docs[LIBELLE][$i]." ($nb_notices)</a>"; else echo $docs[LIBELLE][$i];
   		echo "</div>\n";
   		flush();
   	}
}

function tree($js)
{
	global $base_path;
	global $cnt;
	echo "<script>
	function expand(div_name, last) {
     if (last) {
         nodep=\"$base_path/images/tree/ftv2plastnode.gif\";
         nodem=\"$base_path/images/tree/ftv2mlastnode.gif\";
     } else {
         nodep=\"$base_path/images/tree/ftv2pnode.gif\";
         nodem=\"$base_path/images/tree/ftv2mnode.gif\";
     }
     div_contain=document.getElementById(div_name+\"-contains\");
     if (div_contain.style.display==\"none\") {
          div_contain.style.display=\"block\";
          document.getElementById(\"node-\"+div_name).setAttribute(\"src\",nodem);
          document.getElementById(\"folder-\"+div_name).setAttribute(\"src\",\"$base_path/images/tree/ftv2folderopen.gif\");
     } else {
          div_contain.style.display=\"none\";
          document.getElementById(\"node-\"+div_name).setAttribute(\"src\",nodep);
          document.getElementById(\"folder-\"+div_name).setAttribute(\"src\",\"$base_path/images/tree/ftv2folderclosed.gif\");
     }
	}
	</script>
	<div id=\"1\"><img src=\"$base_path/images/tree/ftv2folderopen.gif\" border=0 align=\"center\">Catégories";

	$categ_id=0;
	$level=1;
	$prefix_name="1";
	
	for ($i=1; $i<=4; $i++)
	{
		$requete="select count(notice_id), categ$i from notices group by categ$i";
		$resultat=mysql_query($requete);
		while (list($n,$c)=mysql_fetch_row($resultat)) {
			$cnt[$c]+=$n;
		}
	}
	gen_div($categ_id,$level,$prefix_name,1,"",$js);
	echo "</div>";
}