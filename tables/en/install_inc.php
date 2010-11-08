<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: install_inc.php,v 1.9 2009-05-16 11:04:16 dbellamy Exp $

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
      <input type=\"submit\" class='bouton' value=\"Create the database\">
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
  <h3 align=\"left\">This page allows the creation of the database on your server</h3>
  <h3 align=\"center\"><font color=red>English set of data may be out of sync with the main version of PMB. 
		After this installation, you just have to connect normally to PMB, then go to Adminstration > Tools > database update.
		Just click on 'Click here to start update.' till it says 'Your database is up to date in version $pmb_version_database_as_it_should_be !'
	</font></h3>
  <div align=\"left\"> 
    <p>You must know a certain amount of information before you can fill in the
       parameters below with the adequate values.</p>
  </div>
  <blockquote> 
    <div align=\"left\"> 
      <p>1 If you wish, can you effectively create a database on your MySQL server? </p>
    </div>
    <ul>
      <li> 
        <div align=\"left\">If you are on a machine in administrator mode, 
          this is definitely the case : so give the administrator user's password
          for the MySQL server.</div>
      </li>
      <li> 
        <div align=\"left\">If you are accessing PMB on a remote machine
          (Free account for exemple), this isn't the case. You must give your 
          access parametres for your database: the parameters for PMB database
          creation will be ignored. The tables 
          will be created in your usual database, warning if 
          it already exists, it will be replaced...</div>
      </li>
    </ul>
    <div align=\"left\"> 
      <p>2 If you wish to fill your database with data?</p>
    </div>
    <ul>
      <li> 
        <div align=\"left\">The minimum : admin user and application parameters :
        mandatory.</div>
      </li>
      <li> 
        <div align=\"left\">In essence: the additional parameters of the database
	  to be able to quick-start, without having everything created for
          inserting an item, backup parameters, 
          and finally parameters for Z39.50 searches.</div>
      </li>
      <li> 
        <div align=\"left\">A set of complete tests : some volumes, borrowers,
          items to be able to test PMB. This test set is based on
          the UNESCO thesaurus which would be obligatory to include.</div>
      </li>
    </ul>
    <div align=\"left\"> 
      <p>3 Which thesaurus (categories item classification hierarchies)
         do you wish to install?</p>
    </div>
    <ul>
      <li> 
        <div align=\"left\">UNESCO : UNESCO's thesaurus, in French, english and spanish, 
          important enough and done well.</div>
      </li>
      <li> 
        <div align=\"left\">Agneaux : smaller, simpler thesaurus, 
          but done very well.</div>
      </li>
      <li> 
        <div align=\"left\">ENVIRONNEMENT : a thesaurus possible for an
          'environnemental' library.<br />
        </div>
      </li>
    </ul>
  <div align=\"left\"> 
      <p>4 Which index system would you like to use?</p>
    </div>
    <ul>
      <li> 
        <div align=\"left\">Dewey Style : decimal index similar to a Dewey system.       </div>
      </li>
      <li> 
        <div align=\"left\">Chambery Library: decimal index used in the Chambery
       library, complete and well documented.</div>
      </li>
      <li> 
        <div align=\"left\">100 cases of knowlege or colour Marguerite : decimal index of 100 entries, adapted for the presentation of 100 cases or the Marguerite flower type display.</div>
      </li>
    </ul></blockquote>
  <hr />
  <form method=\"post\" action=\"install_rep.php\">
  <h2 align=\"left\">System Parameters</h2>
    <p align=\"left\">We need administrator server connection information 
      before carrying out all the operations
      for creation of the database: </p>
    <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
      <tr> 
        <td width=\"200\" class=\"etiquete\">MySql user :</td>
        <td><input class=\"saisie\" name=\"usermysql\" type=\"text\" id=\"usermysql\" value=\"root\"></td>
      </tr>
      <tr> 
        <td width=\"200\" class=\"etiquete\">Password :</td>
        <td><input class=\"saisie\" name=\"passwdmysql\" type=\"password\" id=\"passwdmysql\"></td>
      </tr>
      <tr> 
        <td width=\"200\" class=\"etiquete\">Server :</td>
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
    <p align=\"left\">If you select &quot;database&quot;, the 
      heading &quot;PMB Parameters&quot; below will be ignored
      : the PMB tables will be created in the database
      selected, for example your home database.</p>
    <hr />
    <h2 align=\"left\">PMB Parameters</h2>
    <p align=\"left\">If you haven't selected the database
      in the preceeding heading, you must specify here
      the MySQL user and password which will be used by
      PMB to connect to the database, thus the database name must be also be completed.</p>
    <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
      <tr> 
        <td width=\"200\" class=\"etiquete\">PMB User:</td>
        <td><input class=\"saisie\" type=\"text\" name=\"user\" value=\"bibli\"><div id=\"fixeuser\" style=\"display:none\"><strong><font color=\"#FF0000\">Fixed by system parameters</font></strong></div></td>
      </tr>
      <tr> 
        <td width=\"200\" class=\"etiquete\">Password :</td>
        <td><input class=\"saisie\" name=\"passwd\" type=\"text\" value=\"bibli\"><div id=\"fixepasswd\" style=\"display:none\"><strong><font color=\"#FF0000\">Fixed by system parameters</font></strong></div></td>
      </tr>
      <tr> 
        <td width=\"200\" class=\"etiquete\">PMB database:</td>
        <td><input class=\"saisie\" type=\"text\" name=\"dbname\" value=\"bibli\"><div id=\"fixedbname\" style=\"display:none\"><strong><font color=\"#FF0000\">Fixed by system parameters</font></strong></div></td>
      </tr>
    </table>
    <p align=\"left\">Warning if a database with the same name already exists, 
      it will be destroyed, and its tables will be completely lost.</p>
    <hr />
    <h2 align=\"left\">Loading PMB data</h2>
    <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
      <tr> 
        <td width=\"200\" align=right><div align=\"right\"> 
            <span><strong><font color=\"#FF0000\">Mandatory</font></strong></span><input name=structure type=checkbox value='1' checked readonly style=\"display:none\">
          </div></td>
        <td align=left> Create the structure of the database</td>
      </tr>
      <tr> 
        <td width=\"200\" align=right><div align=\"right\"> 
            <span><strong><font color=\"#FF0000\">Mandatory</font></strong></span><input name=minimum type=checkbox value='1' checked readonly style=\"display:none\">
          </div></td>
        <td align=left> Insert the minimum</td>
      </tr>
      <tr> 
        <td width=\"200\" align=right><div align=\"right\"><span id=\"fixeessential\" style=\"display:none\"><strong><font color=\"#FF0000\">Mandatory</font></strong></span> 
            <input type=checkbox name=essential value='1'>
          </div></td>
        <td align=left> Insert the essential data for quick-start</td>
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
        <td align=left> Insert the operational test case data </td>
      </tr>
    </table>
    <br />
    <hr />
    <h2 align=\"left\">Choice of thesaurus</h2>
    <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
      <tr> 
        <td width=\"200\" align=right><input name=thesaurus type=radio value='aucun'></td>
        <td align=left> No thesaurus</td>
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
    <h2 align=\"left\">Choice of internal index</h2>
    <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
      <tr> 
        <td width=\"200\" align=right><input name=indexint type=radio value='aucun'></td>
        <td align=left> No decimal index</td>
      </tr>
      <tr> 
        <td width=\"200\" align=right><input name=indexint type=radio value='chambery'></td>
        <td align=left> Chambery library</td>
      </tr>
      <tr> 
        <td width=\"200\" align=right><input name=indexint type=radio value='dewey'></td>
        <td align=left> Dewey Style</td>
      </tr>
      <tr> 
        <td width=\"200\" align=right><span id=\"fixe100cases\" style=\"display:none\"><strong><font color=\"#FF0000\">Obligatoire</font></strong></span><input name=indexint type=radio value='marguerite'></td>
        <td align=left> 100 cases of knowlege or Category Marguerite flower</td>
      </tr>
    </table>
    <br />
    <hr />
";
?>

