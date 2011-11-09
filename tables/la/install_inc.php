<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: install_inc.php,v 1.12 2006/10/10 08:52:37 dbellamy Exp $

// plus rien ici : reprise d'un script d'une autre install
if(preg_match('/install_inc\.php/', $_SERVER['REQUEST_URI'])) {
	include('../../includes/forbidden.inc.php'); forbidden();
	}

$header="
<html>
<head>
	<title>PMB : installation</title>
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
      <input type=\"submit\" class='bouton' value=\"Cr&eacute;er la base\">
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
  <h3 align=\"left\">Cette page permet la cr&eacute;ation de la base de donn&eacute;es 
    sur votre serveur</h3>
  <h3 align=\"center\"><font color=red>Il se peut que le jeu de donn&eacute;es de test ne corresponde pas tout &agrave; fait &agrave; la version pr&eacute;sente de PMB. 
		Apr&egrave;s cette installation, il vous suffit de vous connecter normalement &agrave; PMB, allez en  Administration > Outils > Mise &agrave; jour de la base.
		Cliquez sur 'Cliquez ici pour commencer la mise &agrave; jour.' jusqu'&agrave; obtenir 'Votre base est &agrave; jour en version $pmb_version_database_as_it_should_be !'
	</font></h3>
  <div align=\"left\"> 
    <p>Vous devez connaitre un certain nombre d'informations afin de pouvoir remplir 
      les param&egrave;tres ci-dessous avec les valeurs ad&eacute;quates.</p>
  </div>
  <blockquote> 
    <div align=\"left\"> 
      <p>1 Souhaitez-vous, pouvez-vous cr&eacute;er effectivement une base de 
        donn&eacute;es sur votre serveur MySQL ? </p>
    </div>
    <ul>
      <li> 
        <div align=\"left\">Si vous &ecirc;tes sur une machine en mode autonome, 
          c'est certainement le cas : donnez alors le mot de passe de l'utilisateur 
          administrateur du serveur MySQL.</div>
      </li>
      <li> 
        <div align=\"left\">Si vous h&eacute;bergez PMB sur une machine distante 
          (compte Free par exemple), ce n'est pas le cas. Vous devez donnez vos 
          param&egrave;tres d'acc&egrave;s &agrave; votre base de donn&eacute;es 
          : les param&egrave;tres de cr&eacute;ation de la base PMB seront ignor&eacute;s. Les tables 
          seront cr&eacute;&eacute;es dans votre base habituelle, attention si 
          elles existent d&eacute;j&agrave;, elles seront remplac&eacute;es...</div>
      </li>
    </ul>
    <div align=\"left\"> 
      <p>2 Souhaitez-vous remplir votre base avec des donn&eacute;es ?</p>
    </div>
    <ul>
      <li> 
        <div align=\"left\">Le minimum : utilisateur admin et param&egrave;tres 
          de l'application : indispensable.</div>
      </li>
      <li> 
        <div align=\"left\">L'essentiel : des param&egrave;tres additionnels de 
          base afin de d&eacute;marrer rapidement, sans devoir tout cr&eacute;er 
          pour ins&eacute;rer un ouvrage, des param&egrave;tres de sauvegarde, 
          et enfin des param&egrave;tres pour les recherches Z39.50</div>
      </li>
      <li> 
        <div align=\"left\">Un jeu de tests complet : quelques notices, lecteurs, 
          ouvrages afin de pouvoir tester PMB de suite. Ce jeu de test se base 
          sur le th&eacute;saurus UNESCO qui sera obligatoirement inclus.</div>
      </li>
    </ul>
    <div align=\"left\"> 
      <p>3 Quel th&eacute;saurus (cat&eacute;gories hi&eacute;rarchis&eacute;es 
        de classement des ouvrages) voulez-vous ins&eacute;rer ?</p>
    </div>
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
  <div align=\"left\"> 
      <p>4 Quelle indexation voulez-vous utiliser ?</p>
    </div>
    <ul>
      <li> 
        <div align=\"left\">Style Dewey : indexation d&eacute;cimale similaire &agrave; une cotation Dewey.</div>
      </li>
      <li> 
        <div align=\"left\">BM de Chamb&eacute;ry : indexation d&eacute;cimale utilis&eacute;e &agrave; la BM de Chamb&eacute;ry, compl&egrave;te et bien document&eacute;e.</div>
      </li>
      <li> 
        <div align=\"left\">100 cases du savoir ou Marguerite des couleurs : indexation d&eacute;cimale de 100 entr&eacute;es, adapt&eacute;es &agrave; la pr&eacute;sentation 100 cases ou la Marguerite type BCDI.</div>
      </li>
    </ul></blockquote>
  <hr />
  <form method=\"post\" action=\"install_rep.php\">
  <h2 align=\"left\">Param&egrave;tres syst&egrave;me</h2>
    <p align=\"left\">Nous avons besoin des informations de connexion au serveur 
      en tant qu'administrateur afin de r&eacute;aliser toutes les op&eacute;rations 
      de cr&eacute;ation de la base de donn&eacute;es : </p>
    <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
      <tr> 
        <td width=\"200\" class=\"etiquete\">Utilisateur MySql :</td>
        <td><input class=\"saisie\" name=\"usermysql\" type=\"text\" id=\"usermysql\" value=\"root\"></td>
      </tr>
      <tr> 
        <td width=\"200\" class=\"etiquete\">Mot de passe :</td>
        <td><input class=\"saisie\" name=\"passwdmysql\" type=\"password\" id=\"passwdmysql\"></td>
      </tr>
      <tr> 
        <td width=\"200\" class=\"etiquete\">Serveur :</td>
        <td><input class=\"saisie\" name=\"dbhost\" type=\"text\" id=\"dbhost\" value=\"localhost\"></td>
      </tr>
      <tr> 
        <td width=\"200\" class=\"etiquete\"><em>Base de donn&eacute;es:</em></td>
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
    <p align=\"left\">Si vous remplissez &quot;Base de donn&eacute;es&quot;, la 
      rubrique &quot;Param&egrave;tres PMB&quot; ci-dessous sera ignor&eacute;e 
      : les tables de PMB seront cr&eacute;&eacute;es dans la base de donn&eacute;es 
      renseign&eacute;e, par exemple de votre h&eacute;bergement.</p>
    <hr />
    <h2 align=\"left\">Param&egrave;tres PMB</h2>
    <p align=\"left\">Si vous n'avez pas pr&eacute;cis&eacute; de base de donn&eacute;es 
      &agrave; la rubrique pr&eacute;c&eacute;dente, vous devez pr&eacute;ciser 
      ici l'utilisateur MySQL et son mot de passe qui seront utilis&eacute;s par 
      PMB pour se connecter &agrave; la base dont le nom doit &ecirc;tre renseign&eacute; 
      &eacute;galement. </p>
    <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
      <tr> 
        <td width=\"200\" class=\"etiquete\">Utilisateur PMB :</td>
        <td><input class=\"saisie\" type=\"text\" name=\"user\" value=\"bibli\"><div id=\"fixeuser\" style=\"display:none\"><strong><font color=\"#FF0000\">Fix&eacute; par les param&egrave;tres syst&egrave;me</font></strong></div></td>
      </tr>
      <tr> 
        <td width=\"200\" class=\"etiquete\">Mot de passe :</td>
        <td><input class=\"saisie\" name=\"passwd\" type=\"text\" value=\"bibli\"><div id=\"fixepasswd\" style=\"display:none\"><strong><font color=\"#FF0000\">Fix&eacute; par les param&egrave;tres syst&egrave;me</font></strong></div></td>
      </tr>
      <tr> 
        <td width=\"200\" class=\"etiquete\">Base de donn&eacute;es PMB :</td>
        <td><input class=\"saisie\" type=\"text\" name=\"dbname\" value=\"bibli\"><div id=\"fixedbname\" style=\"display:none\"><strong><font color=\"#FF0000\">Fix&eacute; par les param&egrave;tres syst&egrave;me</font></strong></div></td>
      </tr>
    </table>
    <p align=\"left\">Attention si une base portant le m&ecirc;me nom existe d&eacute;j&agrave;, 
      elle sera d&eacute;truite, et les tables qu'elle contient d&eacute;finitivement perdues.</p>
    <hr />
    <h2 align=\"left\">Chargement de donn&eacute;es PMB</h2>
    <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
      <tr> 
        <td width=\"200\" align=right><div align=\"right\"> 
            <span><strong><font color=\"#FF0000\">Obligatoire</font></strong></span><input name=structure type=checkbox value='1' checked readonly style=\"display:none\">
          </div></td>
        <td align=left> Cr&eacute;er la structure de la base de donn&eacute;es</td>
      </tr>
      <tr> 
        <td width=\"200\" align=right><div align=\"right\"> 
            <span><strong><font color=\"#FF0000\">Obligatoire</font></strong></span><input name=minimum type=checkbox value='1' checked readonly style=\"display:none\">
          </div></td>
        <td align=left> Ins&eacute;rer le minimum</td>
      </tr>
      <tr> 
        <td width=\"200\" align=right><div align=\"right\"><span id=\"fixeessential\" style=\"display:none\"><strong><font color=\"#FF0000\">Obligatoire</font></strong></span> 
            <input type=checkbox name=essential value='1'>
          </div></td>
        <td align=left> Ins&eacute;rer les donn&eacute;es essentielles pour d&eacute;marrer 
          rapidement</td>
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
        <td align=left> Ins&eacute;rer les donn&eacute;es du jeu de test op&eacute;rationnel</td>
      </tr>
    </table>
    <br />
    <hr />
    <h2 align=\"left\">Choix du th&eacute;saurus</h2>
    <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
      <tr> 
        <td width=\"200\" align=right><input name=thesaurus type=radio value='aucun'></td>
        <td align=left> Aucun th&eacute;saurus</td>
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
      <tr> 
        <td width=\"200\" align=right><input name=thesaurus type=radio value='motbis'></td>
        <td align=left> MotBis (fichier motbis.sql non fourni dans cette distribution, contacter pmb@sigb.net)</td>
      </tr>
    </table>
    <br />
    <hr />
    <h2 align=\"left\">Choix de l'indexation interne</h2>
    <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
      <tr> 
        <td width=\"200\" align=right><input name=indexint type=radio value='aucun'></td>
        <td align=left> Aucune indexation d&eacute;cimale</td>
      </tr>
      <tr> 
        <td width=\"200\" align=right><input name=indexint type=radio value='chambery'></td>
        <td align=left> BM de Chamb&eacute;ry</td>
      </tr>
      <tr> 
        <td width=\"200\" align=right><input name=indexint type=radio value='dewey'></td>
        <td align=left> Style Dewey</td>
      </tr>
      <tr> 
        <td width=\"200\" align=right><span id=\"fixe100cases\" style=\"display:none\"><strong><font color=\"#FF0000\">Obligatoire</font></strong></span><input name=indexint type=radio value='marguerite'></td>
        <td align=left> 100 cases du savoir ou Marguerite des cat&eacute;gories</td>
      </tr>
    </table>
    <br />
    <hr />
";
?>

