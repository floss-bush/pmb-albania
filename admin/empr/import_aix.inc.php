<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: import_aix.inc.php,v 1.2 2009-06-10 09:09:06 dbellamy Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once("$class_path/emprunteur.class.php");
require_once("$include_path/misc.inc.php");

function show_import_choix_fichier() {
	
	global $msg;
	global $current_module ;
	
	print "
	<form class='form-$current_module' name='form_empr' ENCTYPE=\"multipart/form-data\" method='post' action=\"./admin.php?categ=empr&sub=implec&action=1\" >
		<h3>Import des lecteurs</h3>
		<div class='form-contenu'>
			<div class='row'>
				<label class='etiquette' >".$msg["import_lec_fichier"]."</label>
		        <input name='import_lec' accept='text/plain' type='file' class='saisie-80em' size='60' />
			</div>	
		    <br />
			<div class='row'>
		        <input type='radio' name='type_import' id='nl' value='nouveau_lect' checked='checked' />
		        <label class='etiquette' for='nl' >Nouveaux lecteurs</label>
		        (ajoute ou modifie les lecteurs pr&eacute;sents dans le fichier)
		        <br />
		        <input type='radio' name='type_import' id='ml' value='maj_complete'>
		        <label class='etiquette' for='ml' >Mise à jour compl&egrave;te</label>
		        (supprime les lecteurs non pr&eacute;sents dans le fichier et qui n&apos;ont pas de pr&ecirc;ts en cours)
		    </div>
		    <div class='row'></div>
		</div>
		<div class='row'>
			<input type='submit' class='bouton' value='Importer les lecteurs' />
		</div>
	</form>
	<br />
	<form class='form-$current_module' name='form_pret' ENCTYPE=\"multipart/form-data\" method='post' action=\"./admin.php?categ=empr&sub=implec&action=2\" >
		<h3>Import des pr&ecirc;ts</h3>
		<div class='form-contenu'>
			<div class='row'>
				<label class='etiquette' >".$msg["import_lec_fichier"]."</label>
		        <input name='import_lec' accept='text/plain' type='file' class='saisie-80em' size='60' />
			</div>	
		    <br />
			<div class='row'>
				<img src='images/licence.png' />
	        	<strong>Vous devez avoir import&eacute; les exemplaires et les lecteurs avant cette &eacute;tape.</strong>
		    </div>
		    <div class='row'></div>
		</div>
		<div class='row'>
			<input type='submit' class='bouton' value='Importer les pr&ecirc;ts' />
		</div>
	</form>";
}


function import_lecteurs($type_import){

	global $dbh;
	global $text,$n,$t_xml;
	global $deflt2docs_location;

	//La structure du fichier xml doit être la suivante : 
	 /*    
	<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
	<inm:Results productTitle="Superdoc Premium" productVersion="9.00" xmlns:inm="http://www.inmagic.com/webpublisher/query">
	<inm:Recordset setCount="3">
	<inm:Record setEntry="0">
	<inm:Date-de-creation>10/01/2007</inm:Date-de-creation>
	<inm:ID>103</inm:ID>
	<inm:Centre>CDI LYC. MENDES FRANCE</inm:Centre>
	<inm:Numero-Emprunteur>00001987</inm:Numero-Emprunteur>
	<inm:Nom>SOULIER</inm:Nom>
	<inm:Prenom>ALAIN</inm:Prenom>
	<inm:Nom-Prenom>SOULIER ALAIN</inm:Nom-Prenom>
	<inm:Civilite />
	<inm:Service>C.D.I.</inm:Service>
	<inm:Telephone />
	<inm:Fax />
	<inm:Mel />
	<inm:Adresse />
	<inm:Code-Postal />
	<inm:Ville />
	<inm:Pays />
	<inm:Notes />
	<inm:Exclusion-du-pret />
	<inm:Groupe />
	<inm:Droits />
	<inm:DateFinDroits />
	<inm:DroitEmprunteur>CDI LYC. MENDES FRANCE : Professeur</inm:DroitEmprunteur>
	</inm:Record>...
	*/
	
    
	
    //Upload du fichier
    if (!($_FILES['import_lec']['tmp_name'])) {
        print "Cliquez sur Pr&eacute;c&eacute;dent et choisissez un fichier";
        exit;
    } elseif (!(move_uploaded_file($_FILES['import_lec']['tmp_name'], "./temp/".basename($_FILES['import_lec']['tmp_name'])))) {
        print "Le fichier n'a pas pu être t&eacute;l&eacute;charg&eacute;. Voici plus d'informations :<br />";
        print_r($_FILES)."<p>";
        exit;
    }
    $fichier = @fopen( "./temp/".basename($_FILES['import_lec']['tmp_name']), "r" );
       
    if ($fichier) {
    	
    	print "<br /><br />";
    	print "T&eacute;l&eacute;chargement du fichier effectu&eacute;.<br /><hr />";
    	
        if ($type_import == 'maj_complete') {
    		
        	Print "Suppression des groupes et lecteurs sans prêts.<br /><br />";
        	
        	//Vide la table empr_groupe
            mysql_query("DELETE FROM empr_groupe",$dbh);
            //Supprime les lecteurs qui n'ont pas de prêts en cours
            $req_select_verif_pret = "SELECT id_empr FROM empr left join pret on id_empr=pret_idempr WHERE pret_idempr is null ";
            $select_verif_pret = mysql_query($req_select_verif_pret,$dbh);
            while (($verif_pret = mysql_fetch_array($select_verif_pret))) {
            	//pour tous les lecteurs qui n'ont pas de pret en cours
                emprunteur::del_empr($verif_pret["id_empr"]);
            }
        }
        
        
        print "Traitement du fichier en cours.<br />";
		
        $nb_ok=0;
        $tab_err=array();
        
        //definition header et footer
        $header = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><inm:results>";
        $footer = "</inm:results>"; 
	
        while (!feof($fichier)) {
        	
        	$buffer="";
        	$deb=FALSE;
        	$i=0;
        	
        	while($i<200 && !feof($fichier)) {
            	$line= fgets($fichier, 4096);
            	if( (strpos($line,"<inm:Recordset")===FALSE) && (strpos($line,"<inm:Record")!==FALSE) ) {
            		$deb=TRUE;
            	}
            	if($deb) {
            		$buffer.=trim($line);
            	}
            	if(strpos($line,"</inm:Record>")!==FALSE) {
            		$deb=FALSE;
            		$i++;
            	}
        	}
        	
			if($buffer) {
				$buffer=$header.$buffer.$footer;
	        	//print "<hr />";print htmlentities($buffer,ENT_QUOTES,$charset);print "<br />";
	        	
		        //parse buffer
		        $text='';
				$t_xml=array();
				$n=0;
				
				$encoding="UTF-8";
				$parser = xml_parser_create($encoding);
				xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, $encoding);		
				xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, true);
				xml_set_element_handler($parser, "debutBalise", "finBalise");
				xml_set_character_data_handler($parser, "texte");
				
				if ( !xml_parse( $parser, $buffer, TRUE ) ) {
					die( sprintf( "erreur XML %s à la ligne: %d", 
						xml_error_string(xml_get_error_code($parser ) ),
						xml_get_current_line_number($parser) ) );
				}
				xml_parser_free($parser);
				

				//traitement des enregistrements
				for($i=1;$i<=count($t_xml);$i++) {
					
					//il faut au minimum un nom ou un prénom
					$t_xml[$i]['INM:NOM'][0]=trim($t_xml[$i]['INM:NOM'][0]);
					$t_xml[$i]['INM:PRENOM'][0]=trim($t_xml[$i]['INM:PRENOM'][0]);					
					if( ($t_xml[$i]['INM:NOM'][0]!='') || ($t_xml[$i]['INM:PRENOM'][0]!='') ) {
						
						$e_data=array();
						//print "Enregistrement n° ".$t_xml[$i]['INM:ID'][0]."<br />";
						
						//localisation
						$e_data['location'] = $deflt2docs_location;
						
						//nom + prenom
						if($t_xml[$i]['INM:NOM'][0]!='') {
							$e_data['nom']=$t_xml[$i]['INM:NOM'][0];
							$e_data['prenom']=$t_xml[$i]['INM:PRENOM'][0];
						} else {
							$e_data['nom']=$t_xml[$i]['INM:NOM'][0];
							$e_data['prenom']='';
						}
						
						//cb emprunteur
						$t_xml[$i]['INM:NUMERO-EMPRUNTEUR'][0]=trim($t_xml[$i]['INM:NUMERO-EMPRUNTEUR'][0]);
						if($t_xml[$i]['INM:NUMERO-EMPRUNTEUR'][0]!='') {
							$e_data['cb'] = trim($t_xml[$i]['INM:NUMERO-EMPRUNTEUR'][0]);
						} else { 
							$q="select (count(*)+1) from empr";
							$r=mysql_query($q,$dbh);
							$x=mysql_result($r,0,0);
							$e_data['cb'] = 'PMB_'.$x;
						}
						
						
						//civilité
						$t_xml[$i]['INM:CIVILITE'][0]=substr(strtolower(trim($t_xml[$i]['INM:CIVILITE'][0])),0,2);
						switch($t_xml[$i]['INM:CIVILITE'][0]) {
							case 'm.':
							case 'mr':
							case 'mo':
								$e_data['sexe']=1;
								break;
							case 'ma':
							case 'me':
							case 'ml':
								$e_data['sexe']=2;
								break;
							default :
								$e_data['sexe']=0;
								break;
						}
						
						//tel
						$e_data['tel1']=trim($t_xml[$i]['INM:TELEPHONE'][0]);
						//mail
						$e_data['mail']=trim($t_xml[$i]['INM:MEL'][0]);
						//adresse
						$e_data['adr1']=trim($t_xml[$i]['INM:ADRESSE'][0]);
						//cp
						$e_data['cp']=trim($t_xml[$i]['INM:CP'][0]);
						//ville
						$e_data['ville']=trim($t_xml[$i]['INM:VILLE'][0]);
						//pays
						$e_data['pays']=trim($t_xml[$i]['INM:PAYS'][0]);
						//notes
						$e_data['msg']=trim($t_xml[$i]['INM:NOTES'][0]);
						
						//categorie
						$t_xml[$i]['INM:DROITEMPRUNTEUR'][0]=strtolower($t_xml[$i]['INM:DROITEMPRUNTEUR'][0]);
						$t_xml[$i]['INM:DROITEMPRUNTEUR'][0]=convert_diacrit($t_xml[$i]['INM:DROITEMPRUNTEUR'][0]);
						if( strpos($t_xml[$i]['INM:DROITEMPRUNTEUR'][0],"eleve")!==FALSE ) {
							$e_data['categ']=1;
						} elseif ( strpos($t_xml[$i]['INM:DROITEMPRUNTEUR'][0],"professeur")!==FALSE ) {
							$e_data['categ']=2;
						} else {
							$e_data['categ']=3;
						}
						
						//code statistique
						$e_data['codestat']=1;
						
						//statut
						if(strtolower(trim($t_xml[$i]['INM:EXCLUSION-DU-PRET'][0]))=='yes') {
							$e_data['statut']=2;
						} else {
							$e_data['statut']=1;
						}
						
						//date creation lecteur
						$t_xml[$i]['INM:DATE-DE-CREATION'][0]=trim($t_xml[$i]['INM:DATE-DE-CREATION'][0]);
						$e_data['date_creation']=substr($t_xml[$i]['INM:DATE-DE-CREATION'][0],6,4).'-'.substr($t_xml[$i]['INM:DATE-DE-CREATION'][0],3,2).'-'.substr($t_xml[$i]['INM:DATE-DE-CREATION'][0],0,2);
						
						//date adhesion
						$e_data['date_adhesion']=today();
						
						//date fin adhesion
						$qda="select duree_adhesion from empr_categ where id_categ_empr='".$e_data['categ']."' ";
						$rda=mysql_query($qda,$dbh);
						if(mysql_num_rows($rda)) {
							$da=mysql_result($rda,0,0);
						}else {
							$da=365;
						}
						$qd="select date_add('".$e_data['date_adhesion']."', INTERVAL ".$da." DAY) ";
						$rd=mysql_query($qd,$dbh);
						if(mysql_num_rows($rd)) {
							$de=mysql_result($rd,0,0);
						}
						$e_data['date_expiration']=$de;
						
						//login
						$e_data['login']=emprunteur::do_login($e_data['nom'],$e_data['prenom']);
						
						//import lecteur
						$e=new emprunteur();
						$e_id=0;
						$e_id=$e->import($e_data);
						if($e_id) {
							
							$nb_ok++;
							
							//groupe et champ perso service
							
							$t_xml[$i]['INM:SERVICE'][0]=trim($t_xml[$i]['INM:SERVICE'][0]);
							if ($t_xml[$i]['INM:SERVICE'][0]) {
	
								//groupe
								$qg="select groupe_id from groupe where libelle_groupe='".addslashes($t_xml[$i]['INM:SERVICE'][0])."' limit 1 ";
								$rg=mysql_query($qg,$dbh);
								if(mysql_num_rows($rg)) {
									$g_id=mysql_result($rg,0,0);
								} else {
									$qg="insert into groupe set libelle_groupe='".addslashes($t_xml[$i]['INM:SERVICE'][0])."' ";
									mysql_query($qg,$dbh);
									$g_id=mysql_insert_id($dbh);
								}
								$qeg = "insert into empr_groupe (empr_id,groupe_id) values ($e_id,$g_id) ";
								mysql_query($qeg,$dbh);
								
								//champ perso service
								$qn="select idchamp from empr_custom where name='service' ";
								$rn=mysql_query($qn,$dbh);
								if (mysql_num_rows($rn)) {
									$idc=mysql_result($rn,0,0);
									$requete="select max(empr_custom_list_value*1) from empr_custom_lists where empr_custom_champ=$idc ";
									$resultat=mysql_query($requete,$dbh);
									$max=@mysql_result($resultat,0,0);
									$n=$max+1;
									$requete="select empr_custom_list_value from empr_custom_lists where empr_custom_list_lib='".addslashes($t_xml[$i]['INM:SERVICE'][0])."' and empr_custom_champ=$idc ";
									$resultat=mysql_query($requete,$dbh);
									if (mysql_num_rows($resultat)) {
										$value=mysql_result($resultat,0,0);
									} else {
										$requete="insert into empr_custom_lists (empr_custom_champ,empr_custom_list_value,empr_custom_list_lib) values($idc,$n,'".addslashes($t_xml[$i]['INM:SERVICE'][0])."')";
										mysql_query($requete,$dbh);
										$value=$n;
										$n++;
									}
									$requete="insert into empr_custom_values (empr_custom_champ,empr_custom_origine,empr_custom_integer) values($idc,$e_id,$value)";
									mysql_query($requete,$dbh);
								}
							}
						} else {
							$tab_err[]=$t_xml[$i]['INM:ID'][0];
						}
						
					} else {
						$tab_err[]=$t_xml[$i]['INM:ID'][0];
					}
				}
			}        	        	
        }

        fclose($fichier);
       	unlink("./temp/".basename($_FILES['import_lec']['tmp_name']));
        print "Traitement du fichier termin&eacute;.";
        print "<br /><hr />";
  
  		print "Nombre de lecteurs import&eacute;s : ".$nb_ok."<br />";
  		print "Nombre d'erreurs de traitement : ".count($tab_err)."<br /><hr />";
  		
  		if(count($tab_err)) {
	  		for ($i=0;$i<count($tab_err);$i++) {
				print "Erreur &agrave; l&apos;enregistrement n° ".$tab_err[$i]."<br />";
  			}
			print "<hr /><br />";
  		}
        
        
    } else {
		print "Le fichier n&apos;a pu &ecirc;tre lu .";
    }
    
}


function import_prets() {
	global $dbh;
	global $text,$n,$t_xml;

	//La structure du fichier xml doit être la suivante : 
	 /*    
	<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
	<inm:Results productTitle="Superdoc Premium" productVersion="9.00" xmlns:inm="http://www.inmagic.com/webpublisher/query">
	<inm:Recordset setCount="126">
	<inm:Record setEntry="0">
	<inm:Numero-de-pret>42</inm:Numero-de-pret>
	<inm:Centre>CDI LYC. MENDES FRANCE</inm:Centre>
	<inm:Code-Barre-Objet>015246</inm:Code-Barre-Objet>
	<inm:Emprunteur>CAREMANTRANT Stephanie</inm:Emprunteur>
	<inm:Code-emprunteur>00001812</inm:Code-emprunteur>
	<inm:MailEmprunteur />
	<inm:Date-du-pret>08/10/2004</inm:Date-du-pret>
	<inm:Duree-du-pret>14</inm:Duree-du-pret>
	<inm:Retour-prevu-le>22/10/2004</inm:Retour-prevu-le>
	<inm:Date-de-retour />
	<inm:Notes />
	<inm:Origine-du-pret />
	<inm:IDCatalogue />
	<inm:TitreDoc />
	<inm:NomGrpDoc />
	<inm:DateResa />
	<inm:DateFinResa />
	<inm:DateCourrierResa />
	<inm:EtatDoc>En pr&#xea;t</inm:EtatDoc>
	<inm:DateProlongation />
	<inm:DateCourrierRetard>mardi 25 novembre 2008</inm:DateCourrierRetard>
	<inm:DateCourrierRetard>jeudi 16 octobre 2008</inm:DateCourrierRetard>
	...
	</inm:Record>...
	*/
	
    //Upload du fichier
    if (!($_FILES['import_lec']['tmp_name'])) {
        print "Cliquez sur Pr&eacute;c&eacute;dent et choisissez un fichier";
        exit;
    } elseif (!(move_uploaded_file($_FILES['import_lec']['tmp_name'], "./temp/".basename($_FILES['import_lec']['tmp_name'])))) {
        print "Le fichier n'a pas pu être t&eacute;l&eacute;charg&eacute;. Voici plus d'informations :<br />";
        print_r($_FILES)."<p>";
        exit;
    }
    $fichier = @fopen( "./temp/".basename($_FILES['import_lec']['tmp_name']), "r" );
        
    if ($fichier) {
    	
    	print "<br /><br />";
    	print "T&eacute;l&eacute;chargement du fichier effectu&eacute;.<br /><hr />";
    	
        print "Traitement du fichier en cours.<br />";
		
        $nb_ok=0;
        $tab_err=array();
        
        //definition header et footer
        $header = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><inm:results>";
        $footer = "</inm:results>"; 
	
        while (!feof($fichier)) {
        	
        	$buffer="";
        	$deb=FALSE;
        	$i=0;
        	
        	while($i<200 && !feof($fichier)) {
            	$line= fgets($fichier, 4096);
            	if( (strpos($line,"<inm:Recordset")===FALSE) && (strpos($line,"<inm:Record")!==FALSE) ) {
            		$deb=TRUE;
            	}
            	if($deb) {
            		$buffer.=trim($line);
            	}
            	if(strpos($line,"</inm:Record>")!==FALSE) {
            		$deb=FALSE;
            		$i++;
            	}
        	}
        	
			if($buffer) {
				$buffer=$header.$buffer.$footer;
	        	//print "<hr />";print htmlentities($buffer,ENT_QUOTES,$charset);print "<br />";
	        	
		        //parse buffer
		        $text='';
				$t_xml=array();
				$n=0;
				
				$encoding="UTF-8";
				$parser = xml_parser_create($encoding);
				xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, $encoding);		
				xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, true);
				xml_set_element_handler($parser, "debutBalise", "finBalise");
				xml_set_character_data_handler($parser, "texte");
				
				if ( !xml_parse( $parser, $buffer, TRUE ) ) {
					die( sprintf( "erreur XML %s à la ligne: %d", 
						xml_error_string(xml_get_error_code($parser ) ),
						xml_get_current_line_number($parser) ) );
				}
				xml_parser_free($parser);
				

				//traitement des enregistrements
				for($i=1;$i<=count($t_xml);$i++) {
					
					//il faut un cb exemplaire,un cb lecteur, une date de pret, une date de retour prévu et pas de date de retour
					$t_xml[$i]['INM:CODE-BARRE-OBJET'][0]=trim($t_xml[$i]['INM:CODE-BARRE-OBJET'][0]);
					$t_xml[$i]['INM:CODE-EMPRUNTEUR'][0]=trim($t_xml[$i]['INM:CODE-EMPRUNTEUR'][0]);		
					$t_xml[$i]['INM:DATE-DU-PRET'][0]=trim($t_xml[$i]['INM:DATE-DU-PRET'][0]);			
					$t_xml[$i]['INM:RETOUR-PREVU-LE'][0]=trim($t_xml[$i]['INM:RETOUR-PREVU-LE'][0]);
					$t_xml[$i]['INM:DATE-DE-RETOUR'][0]=trim($t_xml[$i]['INM:DATE-DE-RETOUR'][0]);
					
					if( (!$t_xml[$i]['INM:DATE-DE-RETOUR'][0]) && ($t_xml[$i]['INM:CODE-BARRE-OBJET'][0]!='') && ($t_xml[$i]['INM:CODE-EMPRUNTEUR'][0]!='') && ($t_xml[$i]['INM:DATE-DU-PRET'][0]!='') && ($t_xml[$i]['INM:RETOUR-PREVU-LE'][0]!='') ) {
						
						//print "Enregistrement n° ".$t_xml[$i]['INM:NUMERO-DE-PRET'][0]."<br />";

						//id exemplaire
						$expl_id=0;
						$q="select expl_id from exemplaires where expl_cb='".$t_xml[$i]['INM:CODE-BARRE-OBJET'][0]."' ";
						$r=mysql_query($q,$dbh);
						if(mysql_num_rows($r)) {
							$expl_id=mysql_result($r,0,0);
						} else {
							$tab_err[]=$t_xml[$i]['INM:NUMERO-DE-PRET'][0];
							continue;
						}
						
						//id lecteur
						$empr_id=0;
						$q="select id_empr from empr where empr_cb='".$t_xml[$i]['INM:CODE-EMPRUNTEUR'][0]."' ";
						$r=mysql_query($q,$dbh);
						if(mysql_num_rows($r)) {
							$empr_id=mysql_result($r,0,0);
						} else {
							$tab_err[]=$t_xml[$i]['INM:NUMERO-DE-PRET'][0];
							continue;
						}
						
						//date pret
						$date_pret=substr($t_xml[$i]['INM:DATE-DU-PRET'][0],6,4).'-'.substr($t_xml[$i]['INM:DATE-DU-PRET'][0],3,2).'-'.substr($t_xml[$i]['INM:DATE-DU-PRET'][0],0,2);
						//date retour
						$date_retour=substr($t_xml[$i]['INM:RETOUR-PREVU-LE'][0],6,4).'-'.substr($t_xml[$i]['INM:RETOUR-PREVU-LE'][0],3,2).'-'.substr($t_xml[$i]['INM:RETOUR-PREVU-LE'][0],0,2);
						
						
						// insert pret 
						$q = "INSERT INTO pret SET pret_idempr = '".$empr_id."', pret_idexpl = '".$expl_id."', pret_date   = '".$date_pret."', ";
						$q.= "pret_retour = '".$date_retour."', retour_initial = '".$date_retour."' ";
						mysql_query($q,$dbh);
											
					} else {
						$tab_err[]=$t_xml[$i]['INM:NUMERO-DE-PRET'][0];
					}
				}
			}        	        	
        }

        fclose($fichier);
        unlink("./temp/".basename($_FILES['import_lec']['tmp_name']));
		print "Traitement du fichier termin&eacute;.";
        print "<br /><hr />";
  
  		print "Nombre de lecteurs import&eacute;s : ".$nb_ok."<br />";
  		print "Nombre d'erreurs de traitement : ".count($tab_err)."<br /><hr />";
  		
  		if(count($tab_err)) {
	  		for ($i=0;$i<count($tab_err);$i++) {
				print "Erreur &agrave; l&apos;enregistrement n° ".$tab_err[$i]."<br />";
  			}
			print "<hr /><br />";
  		}
        
        
    } else {
		print "Le fichier n&apos;a pu &ecirc;tre lu .";
    }
}


//Méthodes du parser
function debutBalise($parser, $tag, $att) {
	return;
}

function finBalise($parser, $tag) {
	
	global $text, $t_xml, $n;
	if ($text==='')return;
	switch($tag) {

		case 'INM:ID':
		case 'INM:NUMERO-DE-PRET' :
			$n=$n+1;
			$t_xml[$n]=array();
			$t_xml[$n][$tag][]=$text;
			break;
		default :
			if ($n) $t_xml[$n][$tag][]=$text;
			break;
	}
	$text = '';
	return;
}

function texte($parser, $data) {
	
	global $text;
	if (trim($data)) $text.= $data;
	return;
}


switch($action) {
    case 1:
        import_lecteurs($type_import);
        break;
    case 2:
    	import_prets();
        break;
    default:
        show_import_choix_fichier();
        break;
}

?>



