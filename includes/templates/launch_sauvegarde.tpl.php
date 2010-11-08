<?php

// +-------------------------------------------------+
// | PMB                                                                      |
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: launch_sauvegarde.tpl.php,v 1.7 2009-05-16 11:19:54 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".tpl.php")) die("no access");

$container='
<script>
function checkForm(f)
{
	if (f.sauv_timer[1].checked) {
		if (f.sauv_delay.value=="") {
			alert("'.$msg["sauv_launch_no_delay"].'");
			return false;
		}
	}
	if (f.sauv_timer[2].checked) {
		if (f.sauv_time_hour.value=="") {
			alert("'.$msg["sauv_launch_no_hour"].'");
			return false;
		}
		if (f.sauv_time_min.value=="") {
			alert("'.$msg["sauv_lauchn_no_min"].'");
			return false;
		}
	}
	return true;
}
</script>
<form name="launch_sauvegarde" action="timer.php" method="post">
<table>
<th class"brd">
	<center>'.$msg["sauv_launch_tree_titre"].'<br /><i>'.$msg["sauv_launch_tree_help"].'</i></center>
</th>
<th class="brd" colspan="2">
	<center>'.$msg["sauv_launch_title"].'</center>
</th>
<tr>
	<td class="brd" rowspan=10 valign=top >!!sauvegardes_tree!!</td>
	<td class="nobrd" colspan="2">
		<input type="radio" name="sauv_timer" value="1" checked 
			onChange="
				this.form.sauv_time_hour.value=\'\'; 
				this.form.sauv_time_min.value=\'\'; 
				this.form.sauv_delay.value=\'\';">&nbsp;'.$msg["sauv_launch_now"].'
	</td>
</tr>
<tr>
	<td class="nobrd"><input type="radio" name="sauv_timer" value="2" onChange="this.form.sauv_time_hour.value=\'\'; this.form.sauv_time_min.value=\'\'">&nbsp;'.$msg["sauv_launch_in"].'&nbsp;</td>
	<td class="nobrd"><input type="text" name="sauv_delay" size="10" onChange="if (!this.form.sauv_timer[1].checked) { this.value=\'\'; return} if (isNaN(this.value)) { alert(\''.$msg["sauv_launch_nan_message"].'\'); this.value=\'\'; this.focus(); }">&nbsp;'.$msg["sauv_launch_mn"].'</td>
</tr>
<tr>
	<td class="nobrd"><input type="radio" name="sauv_timer" value="3" onChange="this.form.sauv_delay.value=\'\'">&nbsp;'.$msg["sauv_launch_at"].'&nbsp;</td>
	<td class="nobrd">
		<input type="text" name="sauv_time_hour" size="2" 
			onChange="
					if (!this.form.sauv_timer[2].checked) {this.value=\'\'; return} 
					if (isNaN(this.value)) { 
						alert(\''.$msg["sauv_launch_nan_message"].'\'); 
						this.value=\'\'; this.focus(); 
					} else { 
						if ((this.value<0)||(this.value>23)) { 
							alert(\''.$msg["sauv_launch_bad_hour"].'\'); 
							this.value=\'\'; this.focus();}
					}">&nbsp;'.$msg["sauv_launch_h"].'&nbsp;
		<input type="text" name="sauv_time_min"  size="2" 
			onChange="
					if (!this.form.sauv_timer[2].checked) { this.value=\'\'; return} 
					if (isNaN(this.value)) {
						alert(\''.$msg["sauv_launch_nan_message"].'\'); 
						this.value=\'\'; this.focus(); 
					} else { 
						if ((this.value<0)||(this.value>59)) { 
							alert(\''.$msg["sauv_launch_bad_min"].'\'); 
							this.value=\'\'; 
							this.focus();}}"> '.$msg["sauv_launch_mn"].'
	</td>
</tr>
<!--
-->
</table>
<div class="right">
<input type="submit" value="'.$msg["sauv_launch_launch"].'" class="bouton" 
	onClick="
		if (confirm(\''.$msg["sauv_launch_confirm"].'\')) return checkForm(this.form); 
		else return false;">
</div>
<div class="row"></div>
</form>';
?>