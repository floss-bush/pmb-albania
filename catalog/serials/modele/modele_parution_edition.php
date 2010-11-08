<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: modele_parution_edition.php,v 1.6 2009-05-16 11:12:04 dbellamy Exp $

// définition du minimum nécéssaire
$base_path="./../../..";
$base_auth = "CATALOGAGE_AUTH";
$base_title = "\$msg[6]";
require_once ("$base_path/includes/init.inc.php");

$templates = <<<ENDOFFILE
			<script type='text/javascript'>
				function Fermer(obj,type_doc) {
					var id_obj=parent.document.getElementById(obj);
					if(type_doc==1) id_obj.className='lien_date'; 
					else if(type_doc==2) id_obj.className='lien_date_hs';
					else if(type_doc==3) id_obj.className='lien_date_hs_p';
					else id_obj.className='';
				 	parent.kill_frame_periodique();
				}				
			</script>
<div style='width: 90%;'>
	<div id="bouton_fermer_notice_preview" class="right"><a href='#' onClick='parent.kill_frame_periodique();return false;'>X</a></div>
	!!form!!
</div>						
ENDOFFILE;

$form="<form class='form-$current_module' id='form_modele' name='form_modele' method='post' action='./modele_parution_edition.php?modele_id=$modele_id&date_parution=$date_parution'>
	<div class='row'  ALIGN='center'>!!date_parution!!</div>			
	<div class='row'>
	<input type='checkbox' value='1' !!check_periodique!! name='doc_type[1]'/>".$msg["abonnements_serie"]."
	</div>
	<div class='row'>
	".$msg["abonnements_nombre_recu"]."
	</div>
	<div class='row'>
	<input type='text' size='4' name='nombre_recu' id='nombre_recu' value='!!nombre_recu!!'/>
	</div>	
	<hr />
	<div class='row'>
	<input type='checkbox' value='2' !!check_hors_serie!! name='doc_type[2]'/>".$msg["abonnements_hors_serie"]."
	</div>
	<div class='row'>
	".$msg["abonnements_attribuer_un_numero"]."
	</div>
	<div class='row'>
	<input type='text' size='15' name='numero' id='numero' value='!!numero!!'/>
	</div>
	&nbsp;
	<div class='row'>
		<input type='hidden' id='act' name='act' value='' />		
		<div class='left'>
			<input class='bouton_small' value='".$msg["77"]."' onclick=\"document.getElementById('act').value='update';this.form.submit();\" type='submit'>
		</div>
	</div>
	</form>";

	$type_doc=0;
switch ($act) {
	case 'update':				
		$requete = "delete FROM abts_grille_modele WHERE num_modele='$modele_id' and date_parution ='$date_parution'";
		mysql_query($requete, $dbh);			
		if (isset($doc_type[1])) {
			$form=str_replace("!!check_periodique!!","checked",$form);
			$requete = "INSERT INTO abts_grille_modele SET num_modele='$modele_id', date_parution ='$date_parution', type_serie = '1', nombre_recu= '$nombre_recu'";
			mysql_query($requete, $dbh);
			$type_doc=1;
		}
		if (isset($doc_type[2])) {
			$form=str_replace("!!check_hors_serie!!","checked",$form);
			$requete = "INSERT INTO abts_grille_modele SET num_modele='$modele_id', date_parution ='$date_parution', type_serie = '2', numero='$numero'";
			mysql_query($requete, $dbh);
			$type_doc+=2;
		}	
		$form="<script type='text/javascript'>Fermer('$date_parution','$type_doc');</script>";
	break;

	case 'change':
		$requete = "select type_serie, numero from abts_grille_modele where num_modele='$modele_id' and date_parution ='$date_parution'";
		$resultat=mysql_query($requete);
		if(mysql_num_rows($resultat)) { // Supprimer une réception
			while($r=mysql_fetch_object($resultat)) {
				$type_serie=$r->type_serie;
				$numero=$r->numero;
				if($type_serie==1) {
					$requete = "delete FROM abts_grille_modele WHERE num_modele='$modele_id' and date_parution ='$date_parution' and type_serie = '1'";
					mysql_query($requete, $dbh);
					$supprime=1;
				}
				if($type_serie==2)	$type_doc=2;
			}
		
		}
		if($supprime==0) { // Ajout
			$requete = "INSERT INTO abts_grille_modele SET num_modele='$modele_id', date_parution ='$date_parution', type_serie = '1'";
			mysql_query($requete, $dbh);
			$type_doc+=1;
		}		
		$form="<script type='text/javascript'>Fermer('$date_parution','$type_doc');</script>";		
	break;	

	default:
		$checked1="";
		$checked2="";
		$nombre_recu=1;
		$requete = "select type_serie, numero, nombre_recu from abts_grille_modele where num_modele='$modele_id' and date_parution ='$date_parution'";
		$resultat=mysql_query($requete);
		if(mysql_num_rows($resultat)) {
			while($r=mysql_fetch_object($resultat)){
				$type_serie=$r->type_serie;
				if($type_serie==1) {
					$checked1="checked";
					$nombre_recu=$r->nombre_recu;
				}	
				if($type_serie==2) {
					$numero=$r->numero;
					$checked2="checked";
				}
			}
		}
		$form=str_replace("!!numero!!",$numero,$form);
		$form=str_replace("!!check_periodique!!",$checked1,$form);
		$form=str_replace("!!check_hors_serie!!",$checked2,$form);
		$form=str_replace("!!date_parution!!",formatdate($date_parution),$form);
		$form=str_replace("!!nombre_recu!!",$nombre_recu,$form);
	break;
	}
	
print str_replace("!!form!!",$form,$templates);
print "</body></html>"
?>