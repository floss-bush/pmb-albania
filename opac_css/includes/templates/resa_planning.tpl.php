<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: resa_planning.tpl.php,v 1.4 2009-05-16 10:52:55 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], "tpl.php")) die("no access");

// template pour le formulaire de pose de résa

$form_resa_dates = "
<h3>".$msg['resa_date_planning']."</h3>
<form action='./do_resa.php' method='post' name='dates_resa'>
		<table width='3%'>
			<tr>
				<td width='3%'>
					<label>$msg[resa_date_debut]</label><br />
					<input type='text' name='resa_date_debut' size='20' border='0' value=\"\" /><br />
					<label>$msg[resa_date_fin]</label><br />
					<input type='text' name='resa_date_fin' size='20' border='0' value=\"\" /><br />
					<input type='hidden' name='id_notice' value='$id_notice' >
					<input type='hidden' name='lvl' value='resa_planning' >
					<input type='hidden' name='connectmode' value='popup' >
				</td>
				<td width='3%'>
					<input type='submit' name='ok' value=\"".$msg[11]."\" class='bouton'>
				</td>
			</tr>
		</table>
		</form>
";

$form_resa_ok = "
<h3>".$msg['resa_date_planning']."</h3>
<span class='alerte'>".$msg['added_resa']."<br />".
$msg['resa_date_debut']."!!date_deb!!&nbsp;".$msg['resa_date_fin']."!!date_fin!!</span>
";
