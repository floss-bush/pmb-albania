<?php
$filename="trad4_pmb_unicode_UTF8.txt";
$fp=fopen($filename,"r");
$fw=fopen("ar_AR.xml","w+");

fwrite($fw,"<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n");
fwrite($fw,"<!DOCTYPE XMLlist SYSTEM \"XMLlist.dtd\">\n");
fwrite($fw,"<XMLlist>\n");

$l=0;
while (!feof($fp)) {
	$line=fgets($fp,4096);
	if ($l>0) {
		$line=rtrim($line);
	
		$cols=explode("\t",$line);
		fwrite($fw,"<entry code=\"".$cols[0]."\">".$cols[2]."</entry>\n");
	}
	$l++;
}
fwrite($fw,"</XMLlist>");
fclose($fw);
?>