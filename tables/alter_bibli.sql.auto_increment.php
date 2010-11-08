<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: alter_bibli.sql.auto_increment.php,v 1.1 2006-10-31 12:18:38 touraine37 Exp $

$hostname = "localhost";
$username = "bibli";
$password = "bibli";
$dbName = "bibli";
mysql_connect($hostname,$username,$password) or die("Can't create connection");
$res1 = mysql_query("SHOW TABLES FROM $dbName");
$i=0;
$fp = fopen ("alter_bibli.sql", "w");
while($row1 = mysql_fetch_array($res1)){
	$res2 = mysql_db_query($dbName, "SHOW CREATE TABLE $row1[0]");
	while($row2 = mysql_fetch_array($res2)){
		preg_match("/\s*(\W\w+\W)(.*auto_increment)/", $row2[1], $matches);
		if($matches){
			$i++;
			$row2[0] = str_replace('`','',$row2[0]);
			$matches[1] = str_replace('`','',$matches[1]);
			$matches[2] = str_replace('`','',$matches[2]);
			$str = "ALTER TABLE ".$row2[0]." CHANGE ".$matches[1]." ".$matches[1]." ".$matches[2].";\n";
			fwrite($fp, $str);
		}
	}
	mysql_free_result($res2);
}
fclose($fp);
mysql_free_result($res1);
mysql_close();
echo "Entries created: ".$i."\n";
?>
