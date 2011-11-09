<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: explnum.inc.php,v 1.54.2.2 2011-09-15 12:47:34 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/curl.class.php");
require_once("$class_path/indexation_docnum.class.php");
require_once("$class_path/upload_folder.class.php");
require_once("$class_path/explnum.class.php");

if (!function_exists('file_put_contents')) {
    function file_put_contents($filename, $data) {
        $f = @fopen($filename, 'w');
        if (!$f) {
            return false;
        } else {
            $bytes = fwrite($f, $data);
            fclose($f);
            return $bytes;
        }
    }
}


// charge le tableau des extensions/mimetypes, on en a besoin en maj comme en affichage
function create_tableau_mimetype() {
	
	global $lang;
	global $include_path;
	global $_mimetypes_bymimetype_, $_mimetypes_byext_ ;
	
	if (sizeof($_mimetypes_bymimetype_)) return;
	$_mimetypes_bymimetype_ = array();
	$_mimetypes_byext_ = array();

	require_once ("$include_path/parser.inc.php") ;
	
	$fonction = array ("MIMETYPE" => "__mimetype__");
	_parser_("$include_path/mime_types/$lang.xml", $fonction, "MIMETYPELIST" ) ;
}


function __mimetype__($param) {
	
	global $_mimetypes_bymimetype_, $_mimetypes_byext_ ;
	
	$mimetype_rec = array() ;
	$mimetype_rec["plugin"] = $param["PLUGIN"] ;
	$mimetype_rec["icon"] = $param["ICON"] ;
	$mimetype_rec["label"] = $param["LABEL"] ;
	$mimetype_rec["embeded"] = $param["EMBEDED"] ;
	
	$_mimetypes_bymimetype_[$param["NAME"]] = $mimetype_rec ;
	
	for ($i=0; $i<count($param["EXTENSION"]) ; $i++  ) {
		$mimetypeext_rec = array() ;
		$mimetypeext_rec = $mimetype_rec ;
		$mimetypeext_rec["mimetype"] = $param["NAME"] ;
		if ($param["EXTENSION"][$i]["LABEL"]) {
			$mimetypeext_rec["label"] =  $param["EXTENSION"][$i]["LABEL"] ;
		}
		$_mimetypes_byext_[$param["EXTENSION"][$i]["value"]] = $mimetypeext_rec ;
	}
}


function extension_fichier($fichier) {
	
	$f = strrev($fichier);
	$ext = substr($f, 0, strpos($f,"."));
	return strtolower(strrev($ext));
}


function icone_mimetype ($mimetype, $ext) {
	
	global $_mimetypes_bymimetype_, $_mimetypes_byext_ ;
	// trouve l'icone associee au mimetype
	// sinon trouve l'icone associee a l'extension
	if ($_mimetypes_bymimetype_[$mimetype]["icon"]) return $_mimetypes_bymimetype_[$mimetype]["icon"] ;
	if ($_mimetypes_byext_[$ext]["icon"]) return $_mimetypes_byext_[$ext]["icon"] ;
	return "unknown.gif" ;
}


function trouve_mimetype ($fichier, $ext='') {
	
	global $_mimetypes_byext_ ;
	
	if ($ext!='') {
		// chercher le mimetype associe a l'extension : si trouvee nickel, sinon : ""
		if ($_mimetypes_byext_[$ext]["mimetype"]) return $_mimetypes_byext_[$ext]["mimetype"] ;
	}
	if (extension_loaded('mime_magic')) {
		$mime_type = mime_content_type($fichier) ;
		if ($mime_type) return $mime_type ;
	}
	return '';
}


function reduire_image ($userfile_name) {
	
	global $pmb_vignette_x ;
	global $pmb_vignette_y ;
	
	if (!$pmb_vignette_x) $pmb_vignette_x=100 ;
	if (!$pmb_vignette_y) $pmb_vignette_y=100 ;
	
	if(file_exists("./temp/$userfile_name")){
		$bidon = "./temp/$userfile_name";
	} else {
		$bidon = $userfile_name;
	}

	$error = true;
	if(extension_loaded('imagick')) {
		$error=false;
		try {
			$img = new Imagick($bidon);
			$img->setIteratorIndex(0);
			$img->thumbnailimage($pmb_vignette_x,0);
			$img->setImageFormat( "png" );
			$img->setCompression(Imagick::COMPRESSION_LZW);
			$img->setCompressionQuality(90);
			$contenu_vignette = $img->getImageBlob();			
		} catch(Exception $ex) {
			echo $ex->getMessage();
			$error=true;
		}		
	}
	
	if ($error) {
		$size =@getimagesize($bidon);
		/*   ".gif"=>"1",
	         ".jpg"=>"2",
	         ".jpeg"=>"2",
	         ".png"=>"3",
	         ".swf"=>"4",
	         ".psd"=>"5",
	         ".bmp"=>"6");
		*/
		switch ($size[2]) {
			case 1:
				$src_img = imagecreatefromgif($bidon);
			 	break;
			case 2:
				$src_img = imagecreatefromjpeg($bidon);
				break;
			case 3:
				$src_img = imagecreatefrompng($bidon);
				break;
			case 6:
				$src_img = imagecreatefromwbmp($bidon);
				break;
			default:
				echo "Impossible de créer une vignette avec le fichier $userfile_name ! <!-- ".$size[2]." -->";
				break;
		}
		$erreur_vignette = 0 ;
		if ($src_img) {
			$rs=$pmb_vignette_x/$pmb_vignette_y;
			$taillex=imagesx($src_img);
			$tailley=imagesy($src_img);
			if (!$taillex || !$tailley) return "" ;
			if (($taillex>$pmb_vignette_x)||($tailley>$pmb_vignette_y)) {
				$r=$taillex/$tailley;
				if (($r<1)&&($rs<1)) {
					//Si x plus petit que y et taille finale portrait 
					//Si le format final est plus large en proportion
					if ($rs>$r) {
						$new_h=$pmb_vignette_y; 
						$new_w=$new_h*$r; 
					} else {
						$new_w=$pmb_vignette_x;
						$new_h=$new_w/$r;
					}
				} else if (($r<1)&&($rs>=1)){ 
					//Si x plus petit que y et taille finale paysage
					$new_h=$pmb_vignette_y;
					$new_w=$new_h*$r;  
				} else if (($r>1)&&($rs<1)) {
					//Si x plus grand que y et taille finale portrait
					$new_w=$pmb_vignette_x;
					$new_h=$new_w/$r;
				} else {
					//Si x plus grand que y et taille finale paysage
					if ($rs<$r) {
						$new_w=$pmb_vignette_x;
						$new_h=$new_w/$r;
					} else {
						$new_h=$pmb_vignette_y;
						$new_w=$new_h*$r;
					}
				}
			} else {
				$new_h = $tailley ;
				$new_w = $taillex ;
			}
			$dst_img=imagecreatetruecolor($pmb_vignette_x,$pmb_vignette_y);
			ImageSaveAlpha($dst_img, true);
			ImageAlphaBlending($dst_img, false);
			imagefilledrectangle($dst_img,0,0,$pmb_vignette_x,$pmb_vignette_y,imagecolorallocatealpha($dst_img, 0, 0, 0, 127));
			imagecopyresized($dst_img,$src_img,round(($pmb_vignette_x-$new_w)/2),round(($pmb_vignette_y-$new_h)/2),0,0,$new_w,$new_h,ImageSX($src_img),ImageSY($src_img));
			imagepng($dst_img, "./temp/".SESSid);
			$fp = fopen("./temp/".SESSid , "r" ) ;
			$contenu_vignette = fread ($fp, filesize("./temp/".SESSid));
			if (!$fp || $contenu_vignette=="") $erreur_vignette++ ;
			fclose ($fp) ;
			unlink("./temp/".SESSid);
		} else {
			$contenu_vignette = "" ;
		}
	}
	return $contenu_vignette ;
}


function construire_vignette($vignette_name='', $userfile_name='', $url='') {
	if ($vignette_name) {
		$contenu_vignette = reduire_image($vignette_name);
	} elseif ($userfile_name) {
		$contenu_vignette = reduire_image($userfile_name);
	} elseif ($url) {
		$contenu_vignette = reduire_image($url);
	} else {
		$contenu_vignette = "";
	}
	return $contenu_vignette ;
}


function explnum_update($f_explnum_id, $f_notice, $f_bulletin, $f_nom, $f_url, $retour, $conservervignette=0, $f_statut_chk=0) {
	
	global $dbh, $msg,$scanned_image,$scanned_image_ext ;
	global $current_module, $pmb_explnum_statut;
	global $ck_index, $scanned_texte, $up_place, $path, $id_rep;
	
	create_tableau_mimetype() ;
	
	if ($f_explnum_id) {
		$requete = "UPDATE explnum SET ";
		$limiter = " WHERE explnum_id='$f_explnum_id' ";
	} else {
		$requete = "INSERT INTO explnum SET ";
		$limiter = "";
	}
	
	print "<div class=\"row\"><h1>$msg[explnum_doc_associe]</h1>";
	
	$erreur=0;
	$userfile_name = $_FILES['f_fichier']['name'] ;
	$userfile_temp = $_FILES['f_fichier']['tmp_name'] ;
	$userfile_moved = basename($userfile_temp);
	
	$vignette_name = $_FILES['f_vignette']['name'] ;
	$vignette_temp = $_FILES['f_vignette']['tmp_name'] ;
	$vignette_moved = basename($vignette_temp);
	
	$userfile_name = preg_replace("/ |'|\\|\"|\//m", "_", $userfile_name);
	$vignette_name = preg_replace("/ |'|\\|\"|\//m", "_", $vignette_name);
	
	$userfile_ext = '';
	if ($userfile_name) {
		$userfile_ext = extension_fichier($userfile_name);
	}
	
	if ($f_explnum_id) {
		// modification
		// si $userfile_name est vide on ne fera pas la maj du data
		if (($scanned_image)||($userfile_name)) {
			//Avant tout, y-a-t-il une image extérieure ?
			if ($scanned_image) {
				//Si oui !
				$tmpid=str_replace(" ","_",microtime());
				$fp=@fopen("./temp/scanned_$tmpid.".$scanned_image_ext,"w+");
				if ($fp) {
					fwrite($fp,base64_decode($scanned_image));
					$nf=1;
					$part_name="scanned_image_".$nf;
					global $$part_name;
					while ($$part_name) {
						fwrite($fp,base64_decode($$part_name));
						$nf++;
						$part_name="scanned_image_".$nf;
						global $$part_name;
					}
					fclose($fp);
					$fic=1;
					$maj_data = 1;
					$userfile_name="scanned_$tmpid.".$scanned_image_ext;
					$userfile_ext=$scanned_image_ext;
					$userfile_moved = $userfile_name;
					$f_url="";
				} else $erreur++;
			} else if ($userfile_name) {
				if (move_uploaded_file($userfile_temp,'./temp/'.$userfile_moved)) {					
					$fic=1;
					$f_url="";
					$maj_data = 1;
					move_uploaded_file($vignette_temp,'./temp/'.$vignette_moved) ;
					
				} else {
					$erreur++;
				}
			}
			$contenu_vignette = construire_vignette($vignette_moved, $userfile_moved) ;
			$maj_vignette = 1 ;
			$mimetype = trouve_mimetype($userfile_moved, $userfile_ext) ;
			if (!$mimetype) $mimetype="application/data";
			$maj_mimetype = 1 ;
		} else {
			if ($vignette_name) {
				move_uploaded_file($vignette_temp,'./temp/'.$vignette_moved) ;
				$contenu_vignette = construire_vignette($vignette_moved, $userfile_moved) ;
				$maj_vignette = 1 ;
			}
			if ($f_url) {
				move_uploaded_file($vignette_temp,'./temp/'.$vignette_moved) ;
				$contenu_vignette = construire_vignette($vignette_moved, $userfile_moved) ;
				$maj_vignette = 1 ;
				$mimetype="URL";
				$maj_mimetype = 1 ;
				$contenu="";
				$maj_data=1 ;
			}
		}
	} else {
		// creation
		//Y-a-t-il une image exterieure ?
		if ($scanned_image) {
			//Si oui !
			$tmpid=str_replace(" ","_",microtime());
			$fp=@fopen("./temp/scanned_$tmpid.".$scanned_image_ext,"w+");
			if ($fp) {
				fwrite($fp,base64_decode($scanned_image));
				$nf=1;
				$part_name="scanned_image_".$nf;
				global $$part_name;
				while ($$part_name) {
					fwrite($fp,base64_decode($$part_name));
					$nf++;
					$part_name="scanned_image_".$nf;
					global $$part_name;
				}
				fclose($fp);
				$fic=1;
				$maj_data = 1;
				$userfile_name="scanned_$tmpid.".$scanned_image_ext;
				$userfile_ext=$scanned_image_ext;
				$userfile_moved = $userfile_name;
				$f_url="";
			} else $erreur++;
		} else if (move_uploaded_file($userfile_temp,'./temp/'.$userfile_moved)) {
			$fic=1;
			$f_url="";
			$maj_data = 1;
		} elseif (!$f_url) $erreur++;
	
		move_uploaded_file($vignette_temp,'./temp/'.$vignette_moved) ;
		$contenu_vignette = construire_vignette($vignette_moved, $userfile_moved);
		$maj_vignette = 1 ;
		
		if (!$f_url && !$fic) $erreur++ ; 
		if ($f_url) {
			$mimetype = "URL" ;
		} else {
			$mimetype = trouve_mimetype($userfile_moved,$userfile_ext) ;
			if (!$mimetype) $mimetype="application/data";
		}
		$maj_mimetype = 1 ;
	}
	
	
	
	$upfolder = new upload_folder($id_rep);
	if ($fic) {
		$is_upload = false;
		if(!$f_explnum_id && ($path && $up_place)){
			if($upfolder->isHashing()){
				$rep = $upfolder->hachage($userfile_name);
				@mkdir($rep);
				$path = $upfolder->formate_path_to_nom($rep);
				$file_name = $rep.$userfile_name;				
			} else {				 
				$file_name = $upfolder->formate_nom_to_path($path).$userfile_name;
			}
			$path = $upfolder->formate_path_to_save($path);
			$file_name = $upfolder->encoder_chaine($file_name);
			rename('./temp/'.$userfile_moved,$file_name);
			$is_upload = true;
		} else $file_name = './temp/'.$userfile_moved;
		$fp = fopen($file_name , "r" ) ;
		$contenu = fread ($fp, filesize($file_name));
		if (!$fp || $contenu=="") $erreur++ ;
		fclose ($fp) ;
	}
	
	//Dans le cas d'une modification, on regarde si il y a eu un déplacement du stockage
	if ($f_explnum_id){	
		$explnum = new explnum($f_explnum_id);		
		if($explnum->isEnBase() && ($up_place && $path)){
			$explnum->remove_from_base($path,$id_rep);
			$contenu="";
			$is_upload = false;
		} elseif($explnum->isEnUpload() && (!$up_place)){
			$contenu = $explnum->remove_from_upload();
			$id_rep=0;
			$path="";
		} elseif($explnum->isEnUpload() && ($up_place && $path)){
			$path = $explnum->change_rep_upload($upfolder, $upfolder->formate_nom_to_path($path));
			$path = $upfolder->formate_path_to_save($upfolder->formate_path_to_nom($path));
		}
	}
		
	if (!$f_nom) {
		if ($userfile_name) $f_nom = $userfile_name ;
		elseif ($f_url) $f_nom = $f_url ;
		else $f_nom = "-x-x-x-x-" ;
	}

	if ($userfile_name && !$is_upload) unlink($file_name);
	if ($vignette_name) unlink('./temp/'.$vignette_moved);
	        
	if (!$erreur) {
		$requete .= " explnum_notice='$f_notice'";
		$requete .= ", explnum_bulletin='$f_bulletin'";
		$requete .= ", explnum_nom='$f_nom'";
		$requete .= ", explnum_url='$f_url'";
		if ($maj_mimetype)
			$requete .= ", explnum_mimetype='".$mimetype. "' ";
		if ($maj_data ) {
			if(!$is_upload ) $requete .= ", explnum_data='".addslashes($contenu)."'";
			$requete .= ", explnum_nomfichier='".addslashes($userfile_name)."'";
			$requete .= ", explnum_extfichier='".addslashes($userfile_ext)."'";
		}
		if ($maj_vignette && !$conservervignette) {
			$requete .= ", explnum_vignette='".addslashes($contenu_vignette)."'";
		}
		if ($pmb_explnum_statut=='1') {
			$requete.= ", explnum_statut='".$f_statut_chk."'";
		}	
		$requete.= ", explnum_repertoire='".$id_rep."'";
		$requete.= ", explnum_path='".$path."'";
		
		$requete .= $limiter;
		mysql_query($requete, $dbh) ;
		
		
		//Indexation du document
		global $pmb_indexation_docnum;
				   			
		if($pmb_indexation_docnum){										
			if(!$f_explnum_id && $ck_index){			
				$id_explnum = mysql_insert_id();
				$indexation = new indexation_docnum($id_explnum, $scanned_texte);
				$indexation->indexer();
			} elseif($f_explnum_id && $ck_index){
				$indexation = new indexation_docnum($f_explnum_id, $scanned_texte);
				$indexation->indexer();				
			} elseif($f_explnum_id && !$ck_index){
				$indexation = new indexation_docnum($f_explnum_id);
				$indexation->desindexer();	
			}			 
		}		
		
		// on reaffiche l'ISBD
		print "<div class='row'><div class='msg-perio'>".$msg['maj_encours']."</div></div>";
		$id_form = md5(microtime());
		if (mysql_error()) {
			echo "MySQL error : ".mysql_error() ;
			print "
				<form class='form-$current_module' name=\"dummy\" method=\"post\" action=\"$retour\" >
					<input type='submit' class='bouton' name=\"id_form\" value=\"Ok\">
					</form>";
			print "</div>";
			exit ;
		}
		print "
		<form class='form-$current_module' name=\"dummy\" method=\"post\" action=\"$retour\" style=\"display:none\">
			<input type=\"hidden\" name=\"id_form\" value=\"$id_form\">
			</form>";
		print "<script type=\"text/javascript\">document.dummy.submit();</script>";

	} else {
		eval("\$bid=\"".$msg['explnum_erreurupload']."\";");
		print "<div class='row'><div class='msg-perio'>".$bid."</div></div>";
		print "
			<form class='form-$current_module' name=\"dummy\" method=\"post\" action=\"$retour\" >
				<input type='submit' class='bouton' name=\"id_form\" value=\"Ok\">
			</form>";
	}
		
	print "</div>";
}


function explnum_add_from_url($f_notice_id, $f_nom, $f_url, $overwrite=true, $source_id=0, $filename= "") {
	
	global $dbh;
	
	if (!$overwrite) {
		$sql_find = "SELECT count(*) FROM explnum WHERE explnum_notice = ".$f_notice_id." AND explnum_nom = '".addslashes($f_nom)."'";
		$res = mysql_query($sql_find, $dbh);
		$count = mysql_result($res, 0, 0);
		if ($count)
			return;		
	}
	$sql_delete = "DELETE FROM explnum WHERE explnum_notice = ".$f_notice_id." AND explnum_nom = '".addslashes($f_nom)."' ";
	mysql_query($sql_delete, $dbh);
	
	$aCurl = new Curl();
	$content = $aCurl->get($f_url);
	$content = $content->body;
	
	$origine=str_replace(" ","",microtime());
	$origine=str_replace("0.","",$origine);
	$original_filename = basename($f_url);
	if( $filename != "") $afilename = $filename;
	else $afilename = $origine.$original_filename;
	if (!$original_filename)
		$original_filename = $afilename;
		
	file_put_contents("temp/".$afilename, $content);
	
	$vignette = construire_vignette("", $afilename);
	
	create_tableau_mimetype();
	$afilename_ext=extension_fichier($afilename);
	$mimetype = trouve_mimetype("temp/".$afilename, $afilename_ext);
	$extension = strrchr($afilename, '.');
	
	//si la source du connecteur est précisé, on regarde si on a pas un répertoire associé
	if ($source_id){
		$check_rep = "select rep_upload from connectors_sources where source_id = ".$source_id;
		$res = mysql_query($check_rep);
		if(mysql_num_rows($res)){
			$rep_upload = mysql_result($res,0,0);
		}
	}
	if($rep_upload != 0){
		$upload_folder = new upload_folder($rep_upload);
		$rep_path = $upload_folder->get_path($afilename);
		copy("temp/".$afilename,$rep_path.$afilename);
		
		$path =$upload_folder->formate_path_to_save($upload_folder->formate_path_to_nom($rep_path));
		$insert_sql = "INSERT INTO explnum (explnum_notice, explnum_nom, explnum_nomfichier, explnum_mimetype, explnum_extfichier, explnum_vignette, explnum_repertoire, explnum_path) VALUES (";
		$insert_sql .= $f_notice_id.",";
		$insert_sql .= "'".addslashes($f_nom)."',";
		$insert_sql .= "'".addslashes($afilename)."',";
		$insert_sql .= "'".addslashes($mimetype)."',";
		$insert_sql .= "'".addslashes($extension)."',";
		$insert_sql .= "'".addslashes($vignette)."',";
		$insert_sql .= "'".addslashes($rep_upload)."',";
		$insert_sql .= "'".addslashes($path)."'";
		$insert_sql .= ")";		
	}else{
		$insert_sql = "INSERT INTO explnum (explnum_notice, explnum_nom, explnum_nomfichier, explnum_mimetype, explnum_extfichier, explnum_data, explnum_vignette) VALUES (";
		$insert_sql .= $f_notice_id.",";
		$insert_sql .= "'".addslashes($f_nom)."',";
		$insert_sql .= "'".addslashes($afilename)."',";
		$insert_sql .= "'".addslashes($mimetype)."',";
		$insert_sql .= "'".addslashes($extension)."',";
		$insert_sql .= "'".addslashes($content)."',";
		$insert_sql .= "'".addslashes($vignette)."'";
		$insert_sql .= ")";
	}
	mysql_query($insert_sql, $dbh);
	
	unlink("temp/".$afilename);
}


function explnum_add_url($f_notice_id, $f_nom, $f_url, $overwrite=true) {
	
	global $dbh;
	
	if (!$overwrite) {
		$sql_find = "SELECT count(*) FROM explnum WHERE explnum_notice = ".$f_notice_id." AND explnum_nom = ".addslashes($f_nom);
		$res = mysql_query($sql_find, $dbh);
		$count = mysql_result($res, 0, 0);
		if ($count)
			return;		
	}
	$sql_delete = "DELETE FROM explnum WHERE explnum_notice = ".$f_notice_id." AND explnum_nom = ".addslashes($f_nom);
	mysql_query($sql_delete, $dbh);
	
	$original_filename = basename($f_url);
	$extension = strrchr($original_filename, '.');
	
	$insert_sql = "INSERT INTO explnum (explnum_notice, explnum_nom, explnum_nomfichier, explnum_url, explnum_mimetype, explnum_extfichier) VALUES (";
	$insert_sql .= $f_notice_id.",";
	$insert_sql .= "'".addslashes($f_nom)."',";
	$insert_sql .= "'".addslashes($original_filename)."',";
	$insert_sql .= "'".addslashes($f_url)."',";
	$insert_sql .= "'"."URL"."',";
	$insert_sql .= "'".addslashes($extension)."'";
	$insert_sql .= ")";
	mysql_query($insert_sql, $dbh);
}


// fonction retournant les infos d'exemplaires numeriques pour une notice ou un bulletin donne
function show_explnum_per_notice($no_notice, $no_bulletin, $link_expl='',$param_aff=array()) {
	
	// params :
	// $link_expl= lien associe a l'exemplaire avec !!explnum_id!! a mettre a jour
	global $dbh;
	global $charset;

	if (!$no_notice && !$no_bulletin) return "";

	global $_mimetypes_bymimetype_, $_mimetypes_byext_ ;
	create_tableau_mimetype() ;

	// recuperation du nombre d'exemplaires
	$requete = "SELECT explnum_id, explnum_notice, explnum_bulletin, explnum_nom, explnum_mimetype, explnum_url, explnum_data, explnum_vignette, explnum_nomfichier, explnum_extfichier FROM explnum WHERE ";
	if ($no_notice) $requete .= "explnum_notice='$no_notice' ";
		else $requete .= "explnum_bulletin='$no_bulletin' ";
	if($no_notice)
		$requete .= "union SELECT explnum_id, explnum_notice, explnum_bulletin, explnum_nom, explnum_mimetype, explnum_url, explnum_data, explnum_vignette, explnum_nomfichier, explnum_extfichier
			FROM explnum, bulletins
			WHERE bulletin_id = explnum_bulletin
			AND bulletins.num_notice='".$no_notice."'";
	$requete .= " order by explnum_mimetype, explnum_id ";
	$res = mysql_query($requete, $dbh) or die ($requete." ".mysql_error());
	$nb_ex = mysql_num_rows($res);
	
	if($nb_ex) {
		// on recupere les donnees des exemplaires
		$i = 1 ;
		while (($expl = mysql_fetch_object($res))) {
			if ($i==1) $ligne="<tr><td class='docnum' width='25%'>!!1!!</td><td class='docnum' width='25%'>!!2!!</td><td class='docnum' width='25%'>!!3!!</td><td class='docnum' width='25%'>!!4!!</td></tr>" ;
			if ($link_expl) {
				$tlink = str_replace("!!explnum_id!!", $expl->explnum_id, $link_expl);
				$tlink = str_replace("!!notice_id!!", $expl->explnum_notice, $tlink);					
				$tlink = str_replace("!!bulletin_id!!", $expl->explnum_bulletin, $tlink);					
			} 
			$alt = htmlentities($expl->explnum_nom." - ".$expl->explnum_mimetype,ENT_QUOTES, $charset) ;
			
			global $prefix_url_image ;
			if ($prefix_url_image) $tmpprefix_url_image = $prefix_url_image; 
				else $tmpprefix_url_image = "./" ;
	
			if ($expl->explnum_vignette) $obj="<img src='".$tmpprefix_url_image."vig_num.php?explnum_id=$expl->explnum_id' alt='$alt' title='$alt' border='0'>";
				else // trouver l'icone correspondant au mime_type
				$obj="<img src='".$tmpprefix_url_image."images/mimetype/".icone_mimetype($expl->explnum_mimetype, $expl->explnum_extfichier)."' alt='$alt' title='$alt' border='0'>";
			
			$expl_liste_obj = "<center>";
			$expl_liste_obj .= "<a href='".$tmpprefix_url_image."doc_num.php?explnum_id=$expl->explnum_id' alt='$alt' title='$alt' target='_blank'>".$obj."</a><br />" ;
			
			if ($_mimetypes_byext_[$expl->explnum_extfichier]["label"]) $explmime_nom = $_mimetypes_byext_[$expl->explnum_extfichier]["label"] ;
			elseif ($_mimetypes_bymimetype_[$expl->explnum_mimetype]["label"]) $explmime_nom = $_mimetypes_bymimetype_[$expl->explnum_mimetype]["label"] ;
			else $explmime_nom = $expl->explnum_mimetype ;
			if($param_aff["mine_type"]) $explmime_nom="";
			if ($tlink) {
				$expl_liste_obj .= "<a href='$tlink'>";
				$expl_liste_obj .= htmlentities($expl->explnum_nom,ENT_QUOTES, $charset)."</a><div class='explnum_type'>".htmlentities($explmime_nom,ENT_QUOTES, $charset)."</div>";
			} else {
				$expl_liste_obj .= htmlentities($expl->explnum_nom,ENT_QUOTES, $charset)."<div class='explnum_type'>".htmlentities($explmime_nom,ENT_QUOTES, $charset)."</div>";
			}
			
			$expl_liste_obj .= "</center>";
			$ligne = str_replace("!!$i!!", $expl_liste_obj, $ligne);
			$i++;
			if ($i==5) {
				$ligne_finale .= $ligne ;
				$i=1;
			}
		}
		if (!$ligne_finale) $ligne_finale = $ligne ;
		elseif ($i!=1) $ligne_finale .= $ligne ;
		
		$ligne_finale = str_replace('!!2!!', "&nbsp;", $ligne_finale);
		$ligne_finale = str_replace('!!3!!', "&nbsp;", $ligne_finale);
		$ligne_finale = str_replace('!!4!!', "&nbsp;", $ligne_finale);
		
	} else return "";
	$entry .= "<table class='docnum'>$ligne_finale</table>";
	return $entry;
}
?>