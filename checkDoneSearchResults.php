<?php
if (file_exists("export_beagle.txt")) {
	$cmd="grep ^\> export_beagle.txt |wc -l";
	$res = exec($cmd);
	$res = explode(" ",$res);
	$cmd="head -1 info";
	$tot = exec($cmd);
	echo round($res[0]/$tot,2)*100;
}
else echo -1;
?>
