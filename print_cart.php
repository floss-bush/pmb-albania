<?php
// +-------------------------------------------------+
// � 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: print_cart.php,v 1.17 2009-05-16 11:17:05 dbellamy Exp $

//Ajout aux maniers

$base_path = ".";
$base_auth = "CATALOGAGE_AUTH";
$base_title = "\$msg[print_cart_title]";

require_once($base_path."/includes/init.inc.php");
require_once($class_path."/mono_display.class.php");
require_once($include_path."/notice_authors.inc.php");
require_once($include_path."/notice_categories.inc.php");
require_once($class_path."/author.class.php");
require_once($class_path."/editor.class.php");
require_once($include_path."/isbn.inc.php");
require_once($class_path."/collection.class.php");
require_once($class_path."/subcollection.class.php");
require_once($class_path."/serie.class.php");
require_once($include_path."/explnum.inc.php");
require_once($class_path."/category.class.php");
require_once($class_path."/indexint.class.php");
require_once($class_path."/search.class.php");
require_once($include_path."/cart.inc.php");
require_once($class_path."/caddie.class.php");
require_once($class_path."/sort.class.php");
require_once($class_path."/notice.class.php");


if ($action=="print_prepare") {
	print "<script type='text/javascript' src='./javascript/tablist.js'></script>";
	print "<h3>".$msg["print_cart_title"]."</h3>\n";
	print "<form name='print_options' action='print_cart.php?action=print' method='post'>";
	//Affichage de la s�lection des paniers
	$requete="select caddie.*,count(object_id) as nb_objects, count(flag=1) as nb_flags from caddie left join caddie_content on caddie_id=idcaddie group by idcaddie order by type, name, comment";
	$resultat=mysql_query($requete);
	$ctype="";
	while ($ca=mysql_fetch_object($resultat)) {
		$ca_auth=explode(" ",$ca->autorisations);
		$as=in_array(SESSuserid,$ca_auth);
		if (($as!==false)&&($as!==null)) {
			if ($ca->type!=$ctype) {
				$ctype=$ca->type;
				$print_cart[$ctype]["titre"]="<b>".$msg["caddie_de_".$ca->type]."</b><br />";
			}
			if (($parity=1-$parity)) $pair_impair = "even"; else $pair_impair = "odd";
			$tr_javascript=" onmouseover=\"this.className='surbrillance'\" onmouseout=\"this.className='$pair_impair'\" ";
		  	$print_cart[$ctype]["cart_list"].= pmb_bidi("<tr class='$pair_impair' $tr_javascript ><td><input type='checkbox' id='id_".$ca->idcaddie."' name='caddie[".$ca->idcaddie."]' value='".$ca->idcaddie."'>&nbsp;");	                
            $link = "print_cart.php?action=print&object_type=".$object_type."&idcaddie=".$ca->idcaddie."&item=$item&current_print=$current_print";	
            $print_cart[$ctype]["cart_list"].= pmb_bidi( "<a href='javascript:document.getElementById(\"id_".$ca->idcaddie."\").checked=true;document.forms[\"print_options\"].submit();' /><strong>".$ca->name."</strong>")	;		
            if ($ca->comment) $print_cart[$ctype]["cart_list"].=  pmb_bidi("<br /><small>(".$ca->comment.")</small>");
            $print_cart[$ctype]["cart_list"].=  pmb_bidi("</td>
            	<td><b>".$ca->nb_flags."</b>". $msg[caddie_contient_pointes]." / <b>$ca->nb_objects</b> </td>
            	<td>$aff_lien</td>
				</tr>");		
		}
	}
	print "
		<input type='radio' name='pager' value='1'/>&nbsp;".$msg["print_size_current_page"]."<br />
		<input type='radio' name='pager' value='0' checked/>&nbsp;".$msg["print_size_all"]."<br />	
		<input type='checkbox' id='include_child' name='include_child' >&nbsp;".$msg["cart_include_child"];
	print "<hr />";
		
	print pmb_bidi("<div class='row'><a href='javascript:expandAll()'><img src='./images/expand_all.gif' id='expandall' border='0'></a>
		<a href='javascript:collapseAll()'><img src='./images/collapse_all.gif' id='collapseall' border='0'></a>".$msg['caddie_add_search']."</div>");

	foreach($print_cart as $key => $cart_type) {
		print gen_plus($key,$cart_type["titre"],"<table border='0' cellspacing='0' width='100%'>".$cart_type["cart_list"]."</table>",1);
	}
	print "<input type='hidden' name='current_print' value='$current_print'/>";

	$boutons_select="<input type='submit' value='".$msg["print_cart_add"]."' class='bouton'/>&nbsp;<input type='button' value='".$msg["print_cancel"]."' class='bouton' onClick='self.close();'/>";
	$lien_creation=1;
	$object_type="NOTI";
	if ($lien_creation) {
		print "<div class='row'><hr />
			$boutons_select&nbsp;<input class='bouton' type='button' value=' $msg[new_cart] ' onClick=\"document.location='./cart.php?action=new_cart&object_type=".$object_type."&item=$item&current_print=$current_print'\" />
			</div>"; 
	} else {
		print "<div class='row'><hr />
			$boutons_select
			</div>"; 		
	}			
	print "</form>";
}


if ($action=="print") {
	if ($_SESSION["session_history"][$current_print]) {
		if($_SESSION["session_history"][$current_print]["NOTI"])
			$_SESSION["PRINT_CART"]=$_SESSION["session_history"][$current_print]["NOTI"];
		else if($_SESSION["session_history"][$current_print]["EXPL"])
			$_SESSION["PRINT_CART"]=$_SESSION["session_history"][$current_print]["EXPL"];
		$_SESSION["PRINT_CART"]["caddie"]=$caddie;
		$_SESSION["PRINT_CART"]["pager"]=$pager;
		$_SESSION["PRINT_CART"]["include_child"]=$include_child;
		echo "<script>document.location='./print_cart.php'</script>";
	} else {
		echo "<script>alert(\"".$msg["print_no_search"]."\"); self.close();</script>";
	}
}

if (($action=="")&&($_SESSION["PRINT_CART"])) {
	$environement=$_SESSION["PRINT_CART"];
	$object_type="NOTI";
	if ($environement["TEXT_QUERY"]) {
		$requete=$environement["TEXT_QUERY"];
		if ($_SESSION["tri"]) {
			$sort=new sort('notices','base');
			//$requete = $sort->appliquer_tri($_SESSION["tri"], $requete, "notice_id");
			if ($nb_per_page_search) {
				//$requete .= " LIMIT ".$page*$nb_per_page_search.",".$nb_per_page_search;
				$requete = $sort->appliquer_tri($_SESSION["tri"], $requete, "notice_id" , $page*$nb_per_page_search , $nb_per_page_search);
			} else {
				$requete = $sort->appliquer_tri($_SESSION["tri"], $requete, "notice_id" ,0 ,0);
			}
		}
		if (!$environement["pager"]) {
			$p=strpos($requete,"limit");
			if ($p) {
				$requete=substr($requete,0,$p);
			}  
		}
	} else {
		switch ($environement["SEARCH_TYPE"]) {
		
			case "extended":
				$sh=new search();
				$table=$sh->make_search();
				if ($environement["pager"]) $limit="limit ".($nb_per_page_search*$page).",$nb_per_page_search";
				$requete="select notice_id from $table ".$limit;
				break;
			case "cart":
				if ($environement["pager"]) $limit="limit ".($nb_per_page_search*($page-1)).",$nb_per_page_search";
				$requete="select object_id as notice_id from caddie_content where caddie_id=".$idcaddie." ".$limit;
				break;
			case "expl":
				$sh=new search(true,"search_fields_expl");
				$table=$sh->make_search();
				if ($environement["pager"]) $limit="limit ".($nb_per_page_search*$page).",$nb_per_page_search";				
				$requete="select expl_id as notice_id from $table ".$limit;
				$object_type="EXPL";
				break;				
		}
	}

	if ($environement["caddie"]) {
		foreach ($environement["caddie"] as $environement_caddie) {
			$c=new caddie($environement_caddie);
			$nb_items_before=$c->nb_item;
			$resultat=@mysql_query($requete);
			print mysql_error();				
			while (($r=mysql_fetch_object($resultat))) {
				if($environement["include_child"]) {					
					$tab_list_child=notice::get_list_child($r->notice_id);
					if(count($tab_list_child))
					foreach ($tab_list_child as $notice_id) {
						$c->add_item($notice_id,$object_type);
					}					
				} else	$c->add_item($r->notice_id,$object_type);
			}
			$c->compte_items();			
			$message.=sprintf($msg["print_cart_n_added"]."\\n",($c->nb_item-$nb_items_before),$c->name);
		}	
		print "<script>alert(\"$message\"); self.close();</script>";
	} else {
		print "<script>alert(\"".$msg["print_cart_no_cart_selected"]."\"); history.go(-1);</script>";
	}
	$_SESSION["PRINT_CART"]=false; 
}
print $footer;
?>
