<?php
$path=$_POST["path"]; 
$percentage=0;

$cmd="grep ^\> ./{$path}/results_beagle.txt |wc -l";
$alignments=exec($cmd);

$cmd="head -1 ./{$path}/info";
$total=exec($cmd);


$percentage=$alignments/$total;

echo $percentage;
?> 