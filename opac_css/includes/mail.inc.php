<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: mail.inc.php,v 1.18 2010-07-07 13:33:30 arenou Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

if (!defined('PHP_EOL')) define ('PHP_EOL', strtoupper(substr(PHP_OS,0,3) == 'WIN') ? "\r\n" : "\n");

require_once($class_path."/class.phpmailer.php") ;

function mailpmb($to_nom="", $to_mail, $obj="", $corps="", $from_name="", $from_mail, $headers, $copie_CC="", $copie_BCC="", $faire_nl2br=0, $pieces_jointes=array(),$reply_name="",$reply_mail="") {

	global $opac_mail_methode, $opac_mail_html_format ;
	global $charset;
	
	if (!is_array($pieces_jointes)) $pieces_jointes=array();
	
	$param = explode(",",$opac_mail_methode) ;
	if (!$param) $param=array() ;

	$mail = new PHPmailer();
	$mail->CharSet = $charset;

	if ($copie_CC) $destinataires_CC = explode(";",$copie_CC) ;
		else $destinataires_CC = array();
	if ($copie_BCC) $destinataires_BCC = explode(";",$copie_BCC) ;
		else $destinataires_BCC = array();
	$destinataires = explode(";",$to_mail) ;

	switch ($param[0]) {
		case 'smtp':
			// $pmb_mail_methode = méthode, hote:port, auth, name, pass
			$mail->IsSMTP();
			$mail->Host=$param[1];
			if ($param[2]) {
				$mail->SMTPAuth=true ;
				$mail->Username=$param[3] ;
				$mail->Password=$param[4] ;
				if ($param[5]) $mail->SMTPSecure = $param[5]; // pour traitement connexion SSL
				}
			break ;
		default:
		case 'php':
			$mail->IsMail();
			$to_nom="";
			break;
	}

	if ($opac_mail_html_format) $mail->IsHTML(true);
	$mail->From=$from_mail;
	$mail->FromName=$from_name;
	for ($i=0; $i<count($destinataires); $i++) {
		$mail->AddAddress($destinataires[$i], $to_nom);
		}
	for ($i=0; $i<count($destinataires_CC); $i++) {
		$mail->AddCC($destinataires_CC[$i]);
		}
	for ($i=0; $i<count($destinataires_BCC); $i++) {
		$mail->AddBCC($destinataires_BCC[$i]);
		}
	if($reply_mail && $reply_name)
		$mail->AddReplyTo($reply_mail, $reply_name);
	else $mail->AddReplyTo($from_mail, $from_name);	
	$mail->Subject=$obj;
	if ($opac_mail_html_format) {
		if ($faire_nl2br) $mail->Body=wordwrap(nl2br($corps),70);
		else $mail->Body=wordwrap($corps,70);
	} else {
		$corps=str_replace("<hr />",PHP_EOL."*******************************".PHP_EOL,$corps);
		$corps=str_replace("<hr />",PHP_EOL."*******************************".PHP_EOL,$corps);
		$corps=str_replace("<br />",PHP_EOL,$corps);
		$corps=str_replace("<br />",PHP_EOL,$corps);
		$corps=str_replace(PHP_EOL.PHP_EOL.PHP_EOL,PHP_EOL.PHP_EOL,$corps);
		$corps=strip_tags($corps);
		$corps=html_entity_decode($corps,ENT_QUOTES, $charset) ;
		$mail->Body=wordwrap($corps,70);
	}
	for ($i=0; $i<count($pieces_jointes) ; $i++) {
		if ($pieces_jointes[$i]["contenu"] && $pieces_jointes[$i]["nomfichier"]) 
			$mail->AddStringAttachment($pieces_jointes[$i]["contenu"], $pieces_jointes[$i]["nomfichier"]) ;
	}		

	if (!$mail->Send()) {
		$retour=false;
		global $error_send_mail ;
		$error_send_mail[] = $mail->ErrorInfo ;
		// echo $mail->ErrorInfo."<br /><br /><br /><br />";
		// echo $mail->Body ;
		} else $retour=true ;
	if ($param[0]=='smtp') $mail->SmtpClose();
	unset($mail);

	return $retour ;
	}
	