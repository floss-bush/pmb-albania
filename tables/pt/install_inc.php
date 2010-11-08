<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: install_inc.php,v 1.3 2009-05-16 11:04:16 dbellamy Exp $

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
  <h3 align=\"left\">Esta p&aacute;gina permite criar&nbsp;a base de dados no seu servidor
</h3>
  <h3 align=\"center\"><font color=red>Portuguese set of data may be out of sync with the main version of PMB. 
  After this installation, you just have to connect normally to PMB, then go to Adminstration &gt; Tools &gt; database update. 
  Just click on 'Click here to start update.' till it says 'Your database is up to date in version 
$pmb_version_database_as_it_should_be !'
	</font></h3>
  <div align=\"left\"> 
    <p>Deve conhecer algumas informa&ccedil;&otilde;es para poder introduzir&nbsp;os par&acirc;metros que se pedem mais abaixo com&nbsp;os valores adequados.
.</p>
  </div>
  <blockquote> 
    <div align=\"left\"> 
      <p>1 Quer e pode criar uma base de dados no seu servidor MySQL ? </p>
    </div>
    <ul>
      <li> 
        <div align=\"left\">Se est&aacute; num computador em modo aut&oacute;nomo ou local : d&ecirc; a password do usu&aacute;rio administrador do servidor .</div>
      </li>
      <li> 
        <div align=\"left\">Se instala PMB num servidor
externo (conta Free por exemplo) : deve dar os par&acirc;metros de
acesso &agrave; base de dados desse servidor :&nbsp;os
par&acirc;metros de cria&ccedil;&atilde;o da base de dados
ser&atilde;o ignorados. As tabelas ser&atilde;o criadas na sua
base de dados habitual, aten&ccedil;&atilde;o se&nbsp;as
tabelas j&aacute; existem ser&atilde;o
substitu&iacute;das....</div>
      </li>
    </ul>
    <div align=\"left\"> 
      <p>2 Quer preencher a sua base de dados com dados? </p>
    </div>
    <ul>
      <li> 
        <div align=\"left\">O&nbsp;m&iacute;nimo :
usu&aacute;rio admin e par&acirc;metros da
aplica&ccedil;&atilde;o : indispens&aacute;vel.</div>
      </li>
      <li> 
        <div align=\"left\">O&nbsp;essencial
:&nbsp;os par&acirc;metros adicionais da base de dados para
poder inici&aacute;-la rapidamente, sem ter que criar
todos&nbsp;os par&acirc;metros para catalogar uma obra, com os
par&acirc;metros das c&oacute;pias de seguran&ccedil;a, e
finalmente, os par&acirc;metros para &agrave;s buscas&nbsp;
Z39.50</div>
      </li>
      <li> 
        <div align=\"left\">Um conjunto de dados de demo
completo : alguns registos, utilizadores, obras, para poder
experimentar PMB de seguida. Este conjunto de dados baseia-se no
thesaurus UNESCO que se inclui obrigatoriamente.</div>
      </li>
    </ul>
    <div align=\"left\"> 
      <p>3 Que&nbsp;thesaurus (categor&iacute;as
hier&aacute;rquicas para indexar&nbsp;os documentos) quer
incluir ?</p>
    </div>
    <ul>
      <li> 
        <div align=\"left\">UNESCO :&nbsp;thesaurus
da UNESCO, en franc&ecirc;s, angalsi et espagnol, muito importante e bem
constru&iacute;do.</div>
      </li>
      <li> 
        <div align=\"left\">Agneaux :&nbsp;thesaurus
mais pequeno, mais simples, mas muito bem feito tamb&eacute;m.</div>
      </li>
      <li> 
        <div align=\"left\">Meio Ambiente : um thesaurus
para um centro com um fundo documental sobre o meio ambiente.<br />
        </div>
      </li>
    </ul>
  <div align=\"left\"> 
      <p>4 Que indexa&ccedil;&atilde;o quer usar ?</p>
    </div>
    <ul>
      <li> 
        <div align=\"left\">Estilo Dewey :
indexa&ccedil;&atilde;o decimal similar &agrave; Dewey.</div>
      </li>
      <li> 
        <div align=\"left\">BM de Chamb&eacute;ry :
indexa&ccedil;&atilde;o decimal que se usa na BM de
Chamb&eacute;ry, completa e bem documentada.</div>
      </li>
      <li> 
        <div align=\"left\">100 casos do saber ou
Margarida das cores : indexa&ccedil;&atilde;o decimal de 100
entradas, adaptadas &agrave; apresenta&ccedil;&atilde;o de
100 casos ou Margarida tipo BCDI.</div>
      </li>
    </ul></blockquote>
  <hr />
  <form method=\"post\" action=\"install_rep.php\">
  <h2 align=\"left\">Par&acirc;metros do sistema</h2>
    <p align=\"left\">Necessitamos das
informa&ccedil;&otilde;es de liga&ccedil;&atilde;o ao
servidor como administrador para poder realizar todas&nbsp;as
opera&ccedil;&otilde;es de cria&ccedil;&atilde;o da
base de dados : </p>
    <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
      <tr> 
        <td width=\"200\" class=\"etiquete\">Usu&aacute;rio MySql :</td>
        <td><input class=\"saisie\" name=\"usermysql\" type=\"text\" id=\"usermysql\" value=\"root\"></td>
      </tr>
      <tr> 
        <td width=\"200\" class=\"etiquete\">Password :</td>
        <td><input class=\"saisie\" name=\"passwdmysql\" type=\"password\" id=\"passwdmysql\"></td>
      </tr>
      <tr> 
        <td width=\"200\" class=\"etiquete\">Servidor :</td>
        <td><input class=\"saisie\" name=\"dbhost\" type=\"text\" id=\"dbhost\" value=\"localhost\"></td>
      </tr>
      <tr> 
        <td width=\"200\" class=\"etiquete\"><em>Base de dados:</em></td>
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
    <p align=\"left\">Se preencher&nbsp;a base de dados com dados, deve ignorar&nbsp;a linha Par&acirc;metros
de baixo :&nbsp;as tabelas de PMB ser&atilde;o criadas na base
de dados que tenha indicado, por exemplo do seu servidor.</p>
    <hr />
    <h2 align=\"left\">Par&acirc;metros PMB</h2>
    <p align=\"left\">Se n&atilde;o
precisou&nbsp;a base de dados na linha anterior, deve precisar aqui
o usu&aacute;rio MySQL e a sua password que ser&atilde;o usadas
por PMB para ligar-se &agrave; base de dados da qual se deve
p&ocirc;r o nome igualmente. </p>
    <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
      <tr> 
        <td width=\"200\" class=\"etiquete\">Usu&aacute;rio PMB :</td>
        <td><input class=\"saisie\" type=\"text\" name=\"user\" value=\"bibli\"><div id=\"fixeuser\" style=\"display:none\"><strong><font color=\"#FF0000\">Fixado&nbsp;pelos par&acirc;metros do sistema</font></strong></div></td>
      </tr>
      <tr> 
        <td width=\"200\" class=\"etiquete\">Password :</td>
        <td><input class=\"saisie\" name=\"passwd\" type=\"text\" value=\"bibli\"><div id=\"fixepasswd\" style=\"display:none\"><strong><font color=\"#FF0000\">Fixado&nbsp;pelos par&acirc;metros do sistema</font></strong></div></td>
      </tr>
      <tr> 
        <td width=\"200\" class=\"etiquete\">Base de datos PMB :</td>
        <td><input class=\"saisie\" type=\"text\" name=\"dbname\" value=\"bibli\"><div id=\"fixedbname\" style=\"display:none\"><strong><font color=\"#FF0000\">Fixado&nbsp;pelos par&acirc;metros do sistema</font></strong></div></td>
      </tr>
    </table>
    <p align=\"left\">Aten&ccedil;&atilde;o, se
existir uma base de dados com o mesmo nome ser&aacute;
destru&iacute;da, e&nbsp;as tabelas que contenha
definitivamente perdidas.</p>
    <hr />
    <h2 align=\"left\">Carregar dados PMB</h2>
    <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
      <tr> 
        <td width=\"200\" align=right><div align=\"right\"> 
            <span><strong><font color=\"#FF0000\">Obrigat&oacute;rio</font></strong></span><input name=structure type=checkbox value='1' checked readonly style=\"display:none\">
          </div></td>
        <td align=left> Criar a estrutura da base de dados</td>
      </tr>
      <tr> 
        <td width=\"200\" align=right><div align=\"right\"> 
            <span><strong><font color=\"#FF0000\">Obrigat&oacute;rio</font></strong></span><input name=minimum type=checkbox value='1' checked readonly style=\"display:none\">
          </div></td>
        <td align=left> Introduzir os dados m&iacute;nimo</td>
      </tr>
      <tr> 
        <td width=\"200\" align=right><div align=\"right\"><span id=\"fixeessential\" style=\"display:none\"><strong><font color=\"#FF0000\">Obrigat&oacute;rio</font></strong></span> 
            <input type=checkbox name=essential value='1'>
          </div></td>
        <td align=left> Introduzir os dados m&iacute;nimos essenciais para iniciar rapidamente</td>
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
        <td align=left> Introduzir&nbsp;os dados do conjunto de teste de provas</td>
      </tr>
    </table>
    <br />
    <hr />
    <h2 align=\"left\">Seleccionar thesaurus</h2>
    <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
      <tr> 
        <td width=\"200\" align=right><input name=thesaurus type=radio value='aucun'></td>
        <td align=left> Nenhum thesaurus</td>
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
        <td align=left> MEIO AMBIENTE</td>
      </tr>
    </table>
    <br />
    <hr />
    <h2 align=\"left\">Seleccionar&nbsp;a clasifica&ccedil;&atilde;o</h2>
    <table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
      <tr> 
        <td width=\"200\" align=right><input name=indexint type=radio value='aucun'></td>
        <td align=left> Nenhuma classifica&ccedil;&atilde;o decimal</td>
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
        <td align=left> 100 casos do saber ou Margarida das cores</td>
      </tr>
    </table>
    <br />
    <hr />
";
?>

