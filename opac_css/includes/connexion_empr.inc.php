<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: connexion_empr.inc.php,v 1.7 2009-05-16 10:52:45 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

function do_formulaire_connexion() {
	global $msg ;
	global $dbh ;
	global $id_notice ;
	global $id_bulletin ;
	global $lvl;
	
	switch ($lvl) {
		case ('resa_planning') : 
			$loginform ="<br />
				<h3>".$msg['resa_doit_etre_abon']."</h3>
				<blockquote><form action='do_resa.php' method='post' name='loginform'>
				<label>$msg[resa_empr_login]</label><br />
				<input type='text' name='login' size='20' border='0' value=\"".$msg['common_tpl_cardnumber']."\" onFocus=\"this.value='';\"><br />
				<label>$msg[resa_empr_password]</label><br />
				<input type='password' name='password' size='20' border='0' value='' onFocus=\"this.value='';\"><br />
				<input type='hidden' name='id_notice' value='$id_notice' >
				<input type='hidden' name='lvl' value='resa_planning' >
				<input type='hidden' name='connectmode' value='popup' >
				<input type='submit' name='ok' value=\"".$msg[11]."\" class='bouton'>
				</form>
				</blockquote>";
			break;
	
		case ('avis_add') :	
		case ('avis_liste') :	
		case ('avis_save') :	
		case ('avis_') :
			global $todo, $noticeid ;	
			$loginform ="<br />
				<h3>".$msg['avis_doit_etre_abon']."</h3>
				<blockquote><form action='avis.php' method='post' name='loginform'>
				<label>$msg[sugg_empr_login]</label><br />
				<input type='text' name='login' size='20' border='0' value=\"".$msg['common_tpl_cardnumber']."\" onFocus=\"this.value='';\"><br />
				<label>$msg[sugg_empr_password]</label><br />
				<input type='password' name='password' size='20' border='0' value='' onFocus=\"this.value='';\"><br />
				<input type='hidden' name='lvl' value='$lvl' >
				<input type='hidden' name='todo' value='$todo' >
				<input type='hidden' name='noticeid' value='$noticeid' >
				<input type='submit' name='ok' value=\"".$msg[11]."\" class='bouton'>
				</form>
				</blockquote>";
			break;
	
		case ('tags') :
			global $noticeid ;	
			$loginform ="<br />
				<h3>".$msg['tag_doit_etre_abon']."</h3>
				<blockquote><form action='addtags.php' method='post' name='loginform'>
				<label>$msg[sugg_empr_login]</label><br />
				<input type='text' name='login' size='20' border='0' value=\"".$msg['common_tpl_cardnumber']."\" onFocus=\"this.value='';\"><br />
				<label>$msg[sugg_empr_password]</label><br />
				<input type='password' name='password' size='20' border='0' value='' onFocus=\"this.value='';\"><br />
				<input type='hidden' name='lvl' value='$lvl' >
				<input type='hidden' name='noticeid' value='$noticeid' >
				<input type='submit' name='ok' value=\"".$msg[11]."\" class='bouton'>
				</form>
				</blockquote>";
			break;
	
		case ('make_sugg') :	
			$loginform ="<br />
				<h3>".$msg['sugg_doit_etre_abon']."</h3>
				<blockquote><form action='do_resa.php' method='post' name='loginform'>
				<label>$msg[sugg_empr_login]</label><br />
				<input type='text' name='login' size='20' border='0' value=\"".$msg['common_tpl_cardnumber']."\" onFocus=\"this.value='';\"><br />
				<label>$msg[sugg_empr_password]</label><br />
				<input type='password' name='password' size='20' border='0' value='' onFocus=\"this.value='';\"><br />
				<input type='hidden' name='lvl' value='make_sugg' >
				<input type='hidden' name='connectmode' value='popup' >
				<input type='submit' name='ok' value=\"".$msg[11]."\" class='bouton'>
				</form>
				</blockquote>";
			break;
	
		default;
		case ('resa') : 
			$loginform ="<br />
				<h3>".$msg['resa_doit_etre_abon']."</h3>
				<blockquote><form action='do_resa.php' method='post' name='loginform'>
				<label>$msg[resa_empr_login]</label><br />
				<input type='text' name='login' size='20' border='0' value=\"".$msg['common_tpl_cardnumber']."\" onFocus=\"this.value='';\"><br />
				<label>$msg[resa_empr_password]</label><br />
				<input type='password' name='password' size='20' border='0' value='' onFocus=\"this.value='';\"><br />
				<input type='hidden' name='id_notice' value='$id_notice' >
				<input type='hidden' name='id_bulletin' value='$id_bulletin' >
				<input type='hidden' name='lvl' value='resa' >
				<input type='hidden' name='connectmode' value='popup' >
				<input type='submit' name='ok' value=\"".$msg[11]."\" class='bouton'>
				</form>
				</blockquote>";
			break;
		
	
	}				
	return $loginform ;
	
}
