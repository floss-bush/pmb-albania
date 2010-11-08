<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: install_inc.php,v 1.10 2009-05-16 11:04:16 dbellamy Exp $

// plus rien ici : reprise d'un script d'une autre install
if(preg_match('/install_inc\.php/', $_SERVER['REQUEST_URI'])) {
	include('../../includes/forbidden.inc.php'); forbidden();
	}

$header="
<html>
<head>
	<title>PMB : installazione</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$charset\">
	<style type=\"text/css\">
	body
	{
		font-family: \"Verdana\", \"Arial\", sans-serif;
		background: #eeeae4;
		text-align: center;
	}
	.bouton {
		color: #fff;
		font-size: 12pt;
		font-weight: bold;
		border: 1px outset #D47800;
		background-color: #5483AC;
		}

	.bouton:hover {
		border-style: inset;
		border: 1px solid #ED8600;
		background-color: #7DC2FF;
		}
	#conteneur
	{

	}
	input.saisie:focus
	{
		background: #666;
		color: #fff;
	}
	td.etiquete
	{
		text-align: right;
		font-weight: bold;
		font-size: smaller;
	}
	h2
	{
		color: #090051;
	}
	</style>
</head>
<body>
";

$footer="
    <p> 
      <input type=\"submit\" class='bouton' value=\"Installa la base dati\">
      <input type=\"hidden\" name=\"lang\" value=\"$lang\">
      <input type=\"hidden\" name=\"charset\" value=\"$charset\">
      <input type=\"hidden\" name=\"Submit\" value=\"OK\">
    </p>
  </form>
</div>

</body>
</html>
";

$body="
<div id=\"conteneur\"> 
  <h3 align=\"left\">Questa pagina permette la creazione del database sul vostro server</h3>
  <h3 align=\"center\"><font color=red>Italian set of data may be out of sync with the main version of PMB. 
		After this installation, you just have to connect normally to PMB, then go to Adminstration > Tools > database update.
		Just click on 'Click here to start update.' till it says 'Your database is up to date in version $pmb_version_database_as_it_should_be !'
	</font></h3>
  <div align=\"left\"> 
    <p>Per poter fornire valori adeguati ai parametri qui sotto devi conoscere un p&ograve; di informazioni.</p>
  </div>
  <blockquote> 
    <div align=\"left\"> 
      <p><b>1 Hai il permesso di creare un database sul server MySQL?</b> </p>
    </div>
    <ul>
      <li> 
        <div align=\"left\">&Egrave; certamente cos&igrave; se hai una macchina autonoma: in questo caso serve la password dell'amministratore del server MySQL.</div>
      </li>
      <li> 
        <div align=\"left\">Probabilmente non è questo il caso se vuoi installare PMB in hosting su una macchina di un provider. In questo caso servono i parametri di accesso al database che ti sono stati comunicati dal provider: 
        i parametri di creazione del database PMB saranno ignorati. Le tabelle saranno create 
          nel database che ti &egrave; stato assegnato, attenzione TABELLE CON LO STESSO NOME VERRANNO SOVRASCRITTE...</div>
      </li>
    </ul>
    <div align=\"left\"> 
      <p><b>2 Desiderate popolare la nuova base dati con valori di default?</b></p>
    </div>
    <ul>
      <li> 
        <div align=\"left\">Il minimo : utente admin e parametri dell'applicazione - indispensabile.</div>
      </li>
      <li> 
        <div align=\"left\">L'essenziale : le tabelle di sistema in modo da essere operativi velocemente, ci&ograve; che serve per effettuare il backup e una lista di server Z39.50</div>
      </li>
      <li> 
        <div align=\"left\">Un insieme di dati di test: schede bibliografiche, lettori, 
          opere al fine di provare immediatamente PMB.</div>
      </li>
    </ul>
    <div align=\"left\"> 
      <p><b>3 Quale tesauro (categorie gerarchiche di classificazione delle opere) vuoi caricare?</b></p>
    </div>
<!--
    <ul>
      <li> 
        <div align=\"left\">UNESCO : th&eacute;saurus de l'UNESCO, en fran&ccedil;ais, anglais et espagnol,  
          assez important et bien fait.</div>
      </li>
      <li> 
        <div align=\"left\">Agneaux : th&eacute;saurus plus petit, plus simple, 
          mais tr&egrave;s bien fait.</div>
      </li>
      <li> 
        <div align=\"left\">ENVIRONNEMENT : un th&eacute;saurus possible pour un 
          fonds documentaire ax&eacute; 'environnement'.<br />
        </div>
      </li>
    </ul>
//-->
  <div align=\"left\"> 
      <p><b>4 Quale indicizzazione vuoi utilizzare ?</b></p>
    </div>
    <ul>
      <li> 
        <div align=\"left\">Dewey : indicizzazione decimale Dewey.</div>
      </li>
<!--
      <li> 
        <div align=\"left\">BM de Chambéry : indexation décimale utilisée à la BM de Chambéry, complète et bien documentée.</div>
      </li>
      <li> 
        <div align=\"left\">100 cases du savoir ou Marguerite des couleurs : indexation décimale de 100 entrées, adaptées à la présentation 100 cases ou la Marguerite type BCDI.</div>
      </li>
//-->
    </ul>
	<div align=\"left\">
	<font color='red'>
      <p>L'installazione italiana di PMB al momento non fornisce tesauri, la classificazione Dewey proposta va intesa come semplice proposta e va certamente rivista </p>
      <p>Saremo grati a chiunque sia in grado di fornire Tesauri, Categorie o Indicizzazioni decimali complete e ben fatte.</p>
	</font>
	</div>
	</blockquote>
  <hr />
  <form method=\"post\" action=\"install_rep.php\">
  <h2 align=\"left\">Parametri di sistema</h2>
    <p align=\"left\">&Egrave; necessario disporre delle credenziali di amministrazione del server MySql 
    		per poter effettuare tutte le operazioni connesse con la creazione della base dati. : </p>
    <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
      <tr> 
        <td width=\"200\" class=\"etiquete\">Utente MySql:</td>
        <td><input class=\"saisie\" name=\"usermysql\" type=\"text\" id=\"usermysql\" value=\"root\"></td>
      </tr>
      <tr> 
        <td width=\"200\" class=\"etiquete\">Password:</td>
        <td><input class=\"saisie\" name=\"passwdmysql\" type=\"password\" id=\"passwdmysql\"></td>
      </tr>
      <tr> 
        <td width=\"200\" class=\"etiquete\">Server:</td>
        <td><input class=\"saisie\" name=\"dbhost\" type=\"text\" id=\"dbhost\" value=\"localhost\"></td>
      </tr>
      <tr> 
        <td width=\"200\" class=\"etiquete\"><em>Database:</em></td>
        <td><input class=\"saisie\" name=\"dbnamedbhost\" type=\"text\" onChange=\"
        if (this.form.dbnamedbhost.value!='') {
        	this.form.user.value='';
        	this.form.passwd.value='';
        	this.form.dbname.value='';
        	this.form.user.style.display = 'none';
        	this.form.passwd.style.display = 'none';
        	this.form.dbname.style.display = 'none';
        	document.getElementById('fixeuser').style.display = 'inline';
        	document.getElementById('fixepasswd').style.display = 'inline';
        	document.getElementById('fixedbname').style.display = 'inline';
        	} else {
        		this.form.user.style.display = 'block';
        		this.form.passwd.style.display = 'block';
        		this.form.dbname.style.display = 'block';
        		document.getElementById('fixeuser').style.display = 'none';
        		document.getElementById('fixepasswd').style.display = 'none';
        		document.getElementById('fixedbname').style.display = 'none';
        		}
        	
        \"></td>
      </tr>
    </table>
    <p align=\"left\">Inserendo il nome del Database la sezione \"Parametri PMB\" qui sotto verr&agrave; ignorata: 
    	le tabelle di PMB saranno create nel database a voi riservato ad esempio dal vostro fornitore di hosting.</p>
    <hr />
    <h2 align=\"left\">Parametri PMB</h2>
    <p align=\"left\">Se, nella sezione precedente,  non hai indicato un Database, 
      devi inserire qui l'utente MySQL e la password da utilizzare per la connessione al database,
      il cui nome deve essere indicato ugualmente. </p>
    <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
      <tr> 
        <td width=\"200\" class=\"etiquete\">Utente PMB:</td>
        <td><input class=\"saisie\" type=\"text\" name=\"user\" value=\"bibli\"><div id=\"fixeuser\" style=\"display:none\"><strong><font color=\"#FF0000\">Fixé par les paramètres système</font></strong></div></td>
      </tr>
      <tr> 
        <td width=\"200\" class=\"etiquete\">Password:</td>
        <td><input class=\"saisie\" name=\"passwd\" type=\"text\" value=\"bibli\"><div id=\"fixepasswd\" style=\"display:none\"><strong><font color=\"#FF0000\">Fixé par les paramètres système</font></strong></div></td>
      </tr>
      <tr> 
        <td width=\"200\" class=\"etiquete\">Database PMB :</td>
        <td><input class=\"saisie\" type=\"text\" name=\"dbname\" value=\"bibli\"><div id=\"fixedbname\" style=\"display:none\"><strong><font color=\"#FF0000\">Fixé par les paramètres système</font></strong></div></td>
      </tr>
    </table>
    <p align=\"left\">Attenzione: un database con lo stesso nome verr&agrave; distrutto e le tavole che contiene perse definitivamente.</p>
    <hr />
    <h2 align=\"left\">Caricamento dei dati PMB</h2>
    <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
      <tr> 
        <td width=\"200\" align=right><div align=\"right\"> 
            <span><strong><font color=\"#FF0000\">Obbligatorio</font></strong></span><input name=structure type=checkbox value='1' checked readonly style=\"display:none\">
          </div></td>
        <td align=left> Creare la struttura del database</td>
      </tr>
      <tr> 
        <td width=\"200\" align=right><div align=\"right\"> 
            <span><strong><font color=\"#FF0000\">Obbligatorio</font></strong></span><input name=minimum type=checkbox value='1' checked readonly style=\"display:none\">
          </div></td>
        <td align=left> Installare il minimo</td>
      </tr>
      <tr> 
        <td width=\"200\" align=right><div align=\"right\"><span id=\"fixeessential\" style=\"display:none\"><strong><font color=\"#FF0000\">Obligatoire</font></strong></span> 
            <input type=checkbox name=essential value='1'>
          </div></td>
        <td align=left> Installare i dati essenziali per operare rapidamente</td>
      </tr>
      <tr> 
        <td width=\"200\" align=right><div align=\"right\"> 
            <input type=checkbox name=data_test value='1' onClick=\"
        if (this.form.data_test.checked) {
        	this.form.essential.checked = true ;
        	this.form.thesaurus[2].checked = true ;
        	this.form.indexint[3].checked = true ;
        	document.getElementById('fixeessential').style.display = 'inline';
        	document.getElementById('fixeagneaux').style.display = 'inline';
        	document.getElementById('fixe100cases').style.display = 'inline';
        	} else {
        		document.getElementById('fixeessential').style.display = 'none';
        		document.getElementById('fixeagneaux').style.display = 'none';
        		document.getElementById('fixe100cases').style.display = 'none';
        		}
        \">
          </div></td>
        <td align=left> Inserire i dati di prova</td>
      </tr>
    </table>
    <br />
    <hr />
<!--    
    <h2 align=\"left\">Scelta del tesauro</h2>
    <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
      <tr> 
        <td width=\"200\" align=right><input name=thesaurus type=radio value='aucun'></td>
        <td align=left> Nessun tesauro</td>
      </tr>
      <tr> 
        <td width=\"200\" align=right><input name=thesaurus type=radio value='unesco'></td>
        <td align=left> UNESCO</td>
      </tr>
      <tr> 
        <td width=\"200\" align=right><span id=\"fixeagneaux\" style=\"display:none\"><strong><font color=\"#FF0000\">Obligatoire</font></strong></span><input name=thesaurus type=radio value='agneaux'></td>
        <td align=left> AGNEAUX</td>
      </tr>
      <tr> 
        <td width=\"200\" align=right><input name=thesaurus type=radio value='environnement'></td>
        <td align=left> ENVIRONNEMENT</td>
      </tr>
    </table>
    <br />
    <hr />
//-->
    <h2 align=\"left\">Scelta dell'indicizzazione interna</h2>
    <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
      <tr> 
        <td width=\"200\" align=right><input name=indexint type=radio value='aucun'></td>
        <td align=left> Nessuna indicizzazione decimale</td>
      </tr>
        <td width=\"200\" align=right><input name=indexint type=radio value='dewey'></td>
        <td align=left> Dewey</td>
      </tr>
<!--
      <tr> 
        <td width=\"200\" align=right><input name=indexint type=radio value='chambery'></td>
        <td align=left> BM de Chambéry</td>
      </tr>
      <tr> 
      <tr> 
        <td width=\"200\" align=right><span id=\"fixe100cases\" style=\"display:none\"><strong><font color=\"#FF0000\">Obligatoire</font></strong></span><input name=indexint type=radio value='marguerite'></td>
        <td align=left> 
        100 cases du savoir ou Marguerite des catégories</td>
      </tr>
//-->
    </table>
    <br />
    <hr />
";
?>

