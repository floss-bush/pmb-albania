<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: chklnk.inc.php,v 1.8 2009-05-16 11:11:53 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once ("$class_path/curl.class.php");
require_once ("$class_path/caddie.class.php");
require_once ("$include_path/misc.inc.php");

$admin_layout = str_replace('!!menu_sous_rub!!', $msg[chklnk_titre], $admin_layout);
print $admin_layout;

if (!$suite) {
	echo $admin_chklnk_form ;
} else {
	echo "<h1>".$msg['chklnk_verifencours']."</h1>" ;
	error_reporting (E_ERROR | E_PARSE | E_WARNING);
	@set_time_limit($pmb_set_time_limit) ;
	$curl = new Curl();
	$curl->limit=1;
	mysql_query("set wait_timeout=3600");
	if ($chknoti) {
		if ($ajtnoti) {
			$cad=new caddie($idcaddienot);
			$liencad="&nbsp;<a href=\"./catalog.php?categ=caddie&sub=gestion&quoi=panier&action=&object_type=NOTI&idcaddie=$idcaddienot\">".$cad->name."</a>";
		} else $liencad="";
		echo "<div class='row'><hr /></div><div class='row'><label class='etiquette' >".$msg['chklnk_verifnoti']."</label>".$liencad."</div>
			<div class='row'>";
		$q = "select notice_id, tit1, lien from notices where lien!='' and lien is not null ";
		$r = mysql_query($q) ;
		while ($o=mysql_fetch_object($r)) {
			$response = $curl->get($o->lien);
			if (!$response) {
				echo "<div class='row'><a href=\"./catalog.php?categ=isbd&id=".$o->notice_id."\">".$o->tit1."</a>&nbsp;<a href=\"".$o->lien."\">".$o->lien."</a> <span class='erreur'>".$curl->error."</span></div>";
				if ($ajtnoti) $cad->add_item($o->notice_id,'NOTI');
			} elseif ($response->headers['Status-Code']!='200') {
				echo "<div class='row'><a href=\"./catalog.php?categ=isbd&id=".$o->notice_id."\">".$o->tit1."</a>&nbsp;<a href=\"".$o->lien."\">".$o->lien."</a> <span class='erreur'>".$response->headers['Status-Code']." -> ".$curl->reponsecurl[$response->headers['Status-Code']]."</span></div>";
				if ($ajtnoti) $cad->add_item($o->notice_id,'NOTI');
			}
			flush();
		}
		echo "</div>";
		flush();
	}

	if ($chkenum) {
		$resl="";
		if ($ajtenum) {
			$cad=new caddie($idcaddielnk);
			$liencad="&nbsp;<a href=\"./catalog.php?categ=caddie&sub=gestion&quoi=panier&action=&object_type=NOTI&idcaddie=$idcaddielnk\">".$cad->name."</a>";
		} else $liencad="";
		echo "<div class='row'><hr /></div><div class='row'><label class='etiquette' >".$msg['chklnk_verifenum']."</label>".$liencad."</div>
			<div class='row'>";
		$q = "select notice_id, tit1, explnum_url, explnum_id from notices join explnum on explnum_notice=notice_id where explnum_url!='' and explnum_url is not null ";
		$r = mysql_query($q) or die(mysql_error()."<br />".$q);
		while ($o=mysql_fetch_object($r)) {
			$response = $curl->get($o->explnum_url);
			if (!$response) {
				echo "<div class='row'><a href=\"./catalog.php?categ=edit_explnum&id=".$o->notice_id."&explnum_id=".$o->explnum_id."\">".$o->tit1."</a>&nbsp;<a href=\"".$o->explnum_url."\">".$o->explnum_url."</a> <span class='erreur'>".$curl->error."</span></div>";
				if ($ajtenum) $cad->add_item($o->notice_id,'NOTI');
			} elseif ($response->headers['Status-Code']!='200') {
				echo "<div class='row'><a href=\"./catalog.php?categ=edit_explnum&id=".$o->notice_id."&explnum_id=".$o->explnum_id."\">".$o->tit1."</a>&nbsp;<a href=\"".$o->explnum_url."\">".$o->explnum_url."</a> <span class='erreur'>".$response->headers['Status-Code']." -> ".$curl->reponsecurl[$response->headers['Status-Code']]."</span></div>";
				if ($ajtenum) $cad->add_item($o->notice_id,'NOTI');
			}
			flush();
		}
		echo "</div>";
		flush();
	}

	if ($chkbull) {
		$resl="";
		if ($ajtbull) {
			$cad=new caddie($idcaddiebul);
			$liencad="&nbsp;<a href=\"./catalog.php?categ=caddie&sub=gestion&quoi=panier&action=&object_type=NOTI&idcaddie=$idcaddiebul\">".$cad->name."</a>";
		} else $liencad="";
		echo "<div class='row'><hr /></div><div class='row'><label class='etiquette' >".$msg['chklnk_verifbull']."</label>".$liencad."</div>
			<div class='row'>";
		$q = "select bulletin_id, concat(tit1,' ',bulletin_numero,' ',date_date) as tit, explnum_url, explnum_id, notice_id from notices join bulletins on notice_id=bulletin_notice join explnum on explnum_bulletin=bulletin_id where explnum_url!='' and explnum_url is not null ";
		$r = mysql_query($q) or die(mysql_error()."<br />".$q);
		while ($o=mysql_fetch_object($r)) {
			$response = $curl->get($o->explnum_url);
			if (!$response) {
				echo "<div class='row'><a href=\"./catalog.php?categ=serials&sub=bulletinage&action=explnum_form&bul_id=".$o->bulletin_id."&explnum_id=".$o->explnum_id."\">".$o->tit."</a>&nbsp;<a href=\"".$o->explnum_url."\">".$o->explnum_url."</a> <span class='erreur'>".$curl->error."</span></div>";
				if ($ajtbull) $cad->add_item($o->bulletin_id,'BULL');
			} elseif ($response->headers['Status-Code']!='200') {
				echo "<div class='row'><a href=\"./catalog.php?categ=serials&sub=bulletinage&action=explnum_form&bul_id=".$o->bulletin_id."&explnum_id=".$o->explnum_id."\">".$o->tit."</a>&nbsp;<a href=\"".$o->explnum_url."\">".$o->explnum_url."</a> <span class='erreur'>".$response->headers['Status-Code']." -> ".$curl->reponsecurl[$response->headers['Status-Code']]."</span></div>";
				if ($ajtbull) $cad->add_item($o->bulletin_id,'BULL');
			}
			flush();
		}
		echo "</div>";
		flush();
	}

	if ($chkautaut) {
		$resl="";
		echo "<div class='row'><hr /></div><div class='row'><label class='etiquette' >".$msg['chklnk_verifautaut']."</label></div>
			<div class='row'>";
		$q = "select author_id, concat(author_name,', ',author_rejete,' - ',author_date) as nom_auteur, author_web from authors where author_web!='' and author_web is not null order by index_author ";
		$r = mysql_query($q) or die(mysql_error()."<br />".$q);
		while ($o=mysql_fetch_object($r)) {
			$response = $curl->get($o->author_web);
			if (!$response) {
				echo "<div class='row'><a href=\"./autorites.php?categ=auteurs&sub=author_form&id=".$o->author_id."\">".$o->nom_auteur."</a>&nbsp;<a href=\"".$o->author_web."\">".$o->author_web."</a> <span class='erreur'>".$curl->error."</span></div>";
			} elseif ($response->headers['Status-Code']!='200') {
				echo "<div class='row'><a href=\"./autorites.php?categ=auteurs&sub=author_form&id=".$o->author_id."\">".$o->nom_auteur."</a>&nbsp;<a href=\"".$o->author_web."\">".$o->author_web."</a> <span class='erreur'>".$response->headers['Status-Code']." -> ".$curl->reponsecurl[$response->headers['Status-Code']]."</span></div>";
			}
			flush();
		}
		echo "</div>";
		flush();
	}

	if ($chkautpub) {
		$resl="";
		echo "<div class='row'><hr /></div><div class='row'><label class='etiquette' >".$msg['chklnk_verifautpub']."</label></div>
			<div class='row'>";
		$q = "select ed_id, concat(ed_name,' - ',ed_ville,' - ',ed_pays) as nom_pub, ed_web from publishers where ed_web!='' and ed_web is not null order by index_publisher ";
		$r = mysql_query($q) or die(mysql_error()."<br />".$q);
		while ($o=mysql_fetch_object($r)) {
			$response = $curl->get($o->ed_web);
			if (!$response) {
				echo "<div class='row'><a href=\"./autorites.php?categ=editeurs&sub=editeur_form&id=".$o->ed_id."\">".$o->nom_pub."</a>&nbsp;<a href=\"".$o->ed_web."\">".$o->ed_web."</a> <span class='erreur'>".$curl->error."</span></div>";
			} elseif ($response->headers['Status-Code']!='200') {
				echo "<div class='row'><a href=\"./autorites.php?categ=editeurs&sub=editeur_form&id=".$o->ed_id."\">".$o->nom_pub."</a>&nbsp;<a href=\"".$o->ed_web."\">".$o->ed_web."</a> <span class='erreur'>".$response->headers['Status-Code']." -> ".$curl->reponsecurl[$response->headers['Status-Code']]."</span></div>";
			}
			flush();
		}
		echo "</div>";
		flush();
	}

	if ($chkautcol) {
		echo "<div class='row'><hr /></div><div class='row'><label class='etiquette' >".$msg['chklnk_verifautcol']."</label></div>
			<div class='row'>";
		$q = "select collection_id, concat(collection_name,' - ',collection_issn) as nom_col, collection_web from collections where collection_web!='' and collection_web is not null order by index_coll ";
		$r = mysql_query($q) or die(mysql_error()."<br />".$q);
		while ($o=mysql_fetch_object($r)) {
			$response = $curl->get($o->collection_web);
			if (!$response) {
				echo "<div class='row'><a href=\"./autorites.php?categ=collections&sub=collection_form&id=".$o->collection_id."\">".$o->nom_col."</a>&nbsp;<a href=\"".$o->collection_web."\">".$o->collection_web."</a> <span class='erreur'>".$curl->error."</span></div>";
			} elseif ($response->headers['Status-Code']!='200') {
				echo "<div class='row'><a href=\"./autorites.php?categ=collections&sub=collection_form&id=".$o->collection_id."\">".$o->nom_col."</a>&nbsp;<a href=\"".$o->collection_web."\">".$o->collection_web."</a> <span class='erreur'>".$response->headers['Status-Code']." -> ".$curl->reponsecurl[$response->headers['Status-Code']]."</span></div>";
			}
			flush();
		}
		echo "</div>";
		flush();
	}

	if ($chkautsco) {
		$resl="";
		echo "<div class='row'><hr /></div><div class='row'><label class='etiquette' >".$msg['chklnk_verifautsco']."</label></div>
			<div class='row'>";
		$q = "select sub_coll_id, concat(sub_coll_name,' - ',sub_coll_issn) as nom_sco, subcollection_web from sub_collections where subcollection_web!='' and subcollection_web is not null order by index_sub_coll ";
		$r = mysql_query($q) or die(mysql_error()."<br />".$q);
		while ($o=mysql_fetch_object($r)) {
			$response = $curl->get($o->subcollection_web);
			if (!$response) {
				echo "<div class='row'><a href=\"./autorites.php?categ=souscollections&sub=collection_form&id=".$o->sub_coll_id."\">".$o->nom_sco."</a>&nbsp;<a href=\"".$o->subcollection_web."\">".$o->subcollection_web."</a> <span class='erreur'>".$curl->error."</span></div>";
			} elseif ($response->headers['Status-Code']!='200') {
				echo "<div class='row'><a href=\"./autorites.php?categ=souscollections&sub=collection_form&id=".$o->sub_coll_id."\">".$o->nom_sco."</a>&nbsp;<a href=\"".$o->subcollection_web."\">".$o->subcollection_web."</a> <span class='erreur'>".$response->headers['Status-Code']." -> ".$curl->reponsecurl[$response->headers['Status-Code']]."</span></div>";
			}
		}
		echo "</div>";
		flush();
	}

	echo "<div class='row'><hr /></div><h1>".$msg['chklnk_fin']."</h1>";
}
