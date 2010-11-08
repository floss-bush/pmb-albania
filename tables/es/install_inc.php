<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: install_inc.php,v 1.8 2009-05-16 11:04:15 dbellamy Exp $

// plus rien ici : reprise d'un script d'une autre install
if(preg_match('/install_inc\.php/', $_SERVER['REQUEST_URI'])) {
	include('../../includes/forbidden.inc.php'); forbidden();
	}

$header="
<html>
<head>
	<title>PMB : instalación</title>
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
  <h3 align=\"left\">Esta p&aacute;gina permite crear la base de datos en tu servidor</h3>
  <h3 align=\"center\"><font color=red>Spanish set of data may be out of sync with the main version of PMB. 
		After this installation, you just have to connect normally to PMB, then go to Adminstration > Tools > database update.
		Just click on 'Click here to start update.' till it says 'Your database is up to date in version $pmb_version_database_as_it_should_be !'
	</font></h3>
  <div align=\"left\"> 
    <p>Debes conocer algunas informaciones para poder introducir los par&aacute;metros que se piden m&aacute;s abajo 
	con  los valores adecuados.</p>
  </div>
  <blockquote> 
    <div align=\"left\"> 
      <p>1 Quieres y puedes crear una base de datos en tu servidor MySQL ? </p>
    </div>
    <ul>
      <li> 
        <div align=\"left\">Si est&aacute;s en un ordenador en modo aut&oeacute;nomo o 
		local : da la contrase&ntilde;a del usuario administrador del servidor .</div>
      </li>
      <li> 
        <div align=\"left\">Si instalas PMB en un servidor externo (cuenta Free por ejemplo) : 
		debes dar los par&aacute;metros de acceso a la base de datos de ese servidor : los par&aacute;metros 
		de creaci&eoacute;n de la base de datos ser&aacute;n ignorados. Las tablas se 
		crear&aacute;n en tu base de datos habitual, atenci&oacute;n 
		si las tablas ya existen se reemplazar&aacute;n....</div>
      </li>
    </ul>
    <div align=\"left\"> 
      <p>2 Quieres llenar tu base de datos con datos? </p>
    </div>
    <ul>
      <li> 
        <div align=\"left\">Lo mínimo : usuario admin y par&aacute;metros 
		de la aplicaci&oacute;n : indispensable.</div>
      </li>
      <li> 
        <div align=\"left\">Lo esencial : los par&aacute;metros adicionales de la
		base de datos para poderla inicar r&aacute;pidamente, sin tener que crear 
		todos los par&aacute;metros para catalogar una obra, con los par&aacute;metros 
		de las copias de seguridad, y finalmente los par&aacute;metros para las búsquedas 
		Z39.50</div>
      </li>
      <li> 
        <div align=\"left\">Un juego de datos de demo completo : algunos registros,
		usuarios, obras, para poder probar PMB en seguida. Este juego de datos se basa 
		en el tesauro UNESCO que se incluye obligatoriamente.</div>
      </li>
    </ul>
    <div align=\"left\"> 
      <p>3 Qu&eacute; tesauro (categor&iacute;as jer&aacute;rquicas para indexar
	  los documentos) quieres incluir ?</p>
    </div>
    <ul>
      <li> 
        <div align=\"left\">UNESCO : tesauro de la UNESCO, en franc&eacute;s, anglais et espagnol 
          muy importante y bien constru&iacute;do.</div>
      </li>
      <li> 
        <div align=\"left\">Agneaux : tesauro m&aacute;s peque&ntilde;o, m&aacute;s sencillo, pero 
		muy bien hecho tambi&eacute;n.</div>
      </li>
      <li> 
        <div align=\"left\">MEDIO AMBIENTE : un tesauro para un centro con un fondo documental 
		sobre medio mabiente.<br />
        </div>
      </li>
    </ul>
  <div align=\"left\"> 
      <p>4 Qu&eacute; indexaci&oacute;n quieres usar ?</p>
    </div>
    <ul>
      <li> 
        <div align=\"left\">Estilo Dewey : indexaci&oacute;n decimal similar a la Dewey.</div>
      </li>
      <li> 
        <div align=\"left\">BM de Chamb&eacute;ry : indexaci&oacute;n decimal que se usa en la BM de Chamb&eacute;ry, 
		completa y bien documentada.</div>
      </li>
      <li> 
        <div align=\"left\">100 casos del saber o Margarita de los colores : indexaci&oacute;n decimal de 100 
		entradas, adaptadas a la presentaci&oacute;n 100 casos o Margarita tipo BCDI.</div>
      </li>
    </ul></blockquote>
  <hr />
  <form method=\"post\" action=\"install_rep.php\">
  <h2 align=\"left\">Par&aacute;metros del sistema</h2>
    <p align=\"left\">Necesitamos las informaciones de conexi&oacute;n al servidor 
	como administrador para poder realizar todas las operaciones de creaci&oacute;n 
	de la base de datos : </p>
    <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
      <tr> 
        <td width=\"200\" class=\"etiquete\">Usuario MySql :</td>
        <td><input class=\"saisie\" name=\"usermysql\" type=\"text\" id=\"usermysql\" value=\"root\"></td>
      </tr>
      <tr> 
        <td width=\"200\" class=\"etiquete\">Contrase&ntilde;a :</td>
        <td><input class=\"saisie\" name=\"passwdmysql\" type=\"password\" id=\"passwdmysql\"></td>
      </tr>
      <tr> 
        <td width=\"200\" class=\"etiquete\">Servidor :</td>
        <td><input class=\"saisie\" name=\"dbhost\" type=\"text\" id=\"dbhost\" value=\"localhost\"></td>
      </tr>
      <tr> 
        <td width=\"200\" class=\"etiquete\"><em>Base de datos:</em></td>
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
    <p align=\"left\">Si vas a llenar la base de datos con datos, debes ignorar la l&iacute;nea 
	&quot;Par&aacute;metros&quot; de aquí abajo : las tablas de PMB se crera&aacute;n en la base 
	de datos que hayas indicado, por ejemplo de tu servidor.</p>
    <hr />
    <h2 align=\"left\">Par&aacute;metros PMB</h2>
    <p align=\"left\">Si no has precisado la base de datos en la l&iacute;nea anterior 
	debes precisar aqu&iacute; el usuario MySQL y su contraseña que ser&aacute;n usadas por 
      PMB para conectarse a la base de datos de la cual se debe poner el nombre igualmente. </p>
    <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
      <tr> 
        <td width=\"200\" class=\"etiquete\">Usuario PMB :</td>
        <td><input class=\"saisie\" type=\"text\" name=\"user\" value=\"bibli\"><div id=\"fixeuser\" style=\"display:none\"><strong><font color=\"#FF0000\">Fijado por los par&aacute;metros del sistema</font></strong></div></td>
      </tr>
      <tr> 
        <td width=\"200\" class=\"etiquete\">Contrase&ntilde;a :</td>
        <td><input class=\"saisie\" name=\"passwd\" type=\"text\" value=\"bibli\"><div id=\"fixepasswd\" style=\"display:none\"><strong><font color=\"#FF0000\">Fijado por los par&aacute;metros del sistema</font></strong></div></td>
      </tr>
      <tr> 
        <td width=\"200\" class=\"etiquete\">Base de datos PMB :</td>
        <td><input class=\"saisie\" type=\"text\" name=\"dbname\" value=\"bibli\"><div id=\"fixedbname\" style=\"display:none\"><strong><font color=\"#FF0000\">Fijado por los par&aacute;metros del sistema</font></strong></div></td>
      </tr>
    </table>
    <p align=\"left\">Atenci&oacute;n si existe una base de datos con el mismo nombre ser&aacute; 
	destru&iacute;da, y las tablas que contenga definitivamente perdidas.</p>
    <hr />
    <h2 align=\"left\">Cargar datos PMB</h2>
    <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
      <tr> 
        <td width=\"200\" align=right><div align=\"right\"> 
            <span><strong><font color=\"#FF0000\">Obligatorio</font></strong></span><input name=structure type=checkbox value='1' checked readonly style=\"display:none\">
          </div></td>
        <td align=left> Crear la estructura de la base de datos</td>
      </tr>
      <tr> 
        <td width=\"200\" align=right><div align=\"right\"> 
            <span><strong><font color=\"#FF0000\">Obligatorio</font></strong></span><input name=minimum type=checkbox value='1' checked readonly style=\"display:none\">
          </div></td>
        <td align=left> Introducir los datos m&iacute;nimo</td>
      </tr>
      <tr> 
        <td width=\"200\" align=right><div align=\"right\"><span id=\"fixeessential\" style=\"display:none\"><strong><font color=\"#FF0000\">Obligatorio</font></strong></span> 
            <input type=checkbox name=essential value='1'>
          </div></td>
        <td align=left> Introducir los datos m&iacute;nimos esenciales para iniciar r&aacute;pidamente</td>
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
        <td align=left> Introducir los datos del juego de test de pruebas</td>
      </tr>
    </table>
    <br />
    <hr />
    <h2 align=\"left\">Escoge tesauro</h2>
    <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
      <tr> 
        <td width=\"200\" align=right><input name=thesaurus type=radio value='aucun'></td>
        <td align=left> Ning&uacute;n tesauro</td>
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
        <td align=left> MEDIO AMBIENTE</td>
      </tr>
    </table>
    <br />
    <hr />
    <h2 align=\"left\">Escoge la clasificaci&oacute;n</h2>
    <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
      <tr> 
        <td width=\"200\" align=right><input name=indexint type=radio value='aucun'></td>
        <td align=left> Ninguna clasificaci&oacute;n decimal</td>
      </tr>
      <tr> 
        <td width=\"200\" align=right><input name=indexint type=radio value='chambery'></td>
        <td align=left> BM de Chamb&eacute;ry</td>
      </tr>
      <tr> 
        <td width=\"200\" align=right><input name=indexint type=radio value='dewey'></td>
        <td align=left> Estilo Dewey</td>
      </tr>
      <tr> 
        <td width=\"200\" align=right><span id=\"fixe100cases\" style=\"display:none\"><strong><font color=\"#FF0000\">Obligatoire</font></strong></span><input name=indexint type=radio value='marguerite'></td>
        <td align=left> 100 casos del saber o Margarita de los colores</td>
      </tr>
    </table>
    <br />
    <hr />
";
?>

