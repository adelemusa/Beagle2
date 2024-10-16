<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BEAGLE aligner</title>
<link rel="stylesheet" href="../../tablesorter-master/css/theme.dropbox.css">
<link href="../../css/myStyle.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../tablesorter-master/jquery-v1.1.js"></script>
<script type="text/javascript" src="../../tablesorter-master/jquery.tablesorter.js"></script>
<script type="text/javascript" src="../../tablesorter-master/jquery.tablesorter.pager.js"></script>

<!-- tablesorter widgets (optional) -->
<script type="text/javascript" src="../../tablesorter-master/jquery.tablesorter.widgets.js"></script>
<script>
var interval1;
var interval2;

function checkCompletion(){
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function(){
                if (xmlhttp.readyState==4 && xmlhttp.status==200){
                        if(xmlhttp.responseText==1){
					clearInterval(interval1);
					document.getElementById("secondstep").style.display="block";
					document.getElementById("current").innerHTML="";
					interval2=setInterval(function(){checkDone();}, 1000);
                        }
			else {
				document.getElementById("current").innerHTML="<center>Analysis not yet ready. Current percentage "+Math.round(xmlhttp.responseText*100)+"%</center>";
			}
                }
        }
        xmlhttp.open("GET","getPercentage.php",true);
        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xmlhttp.send();
}

function checkDone() {
	var xmlhttp;
	if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else { // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
  		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
    			document.getElementById("percdone").innerHTML=xmlhttp.responseText+"%";
			//alert(xmlhttp.responseText);
			if (xmlhttp.responseText=="100") clearInterval(interval2);
			if (xmlhttp.responseText=="-1") exportFile2();
    		}
	}
	xmlhttp.open("GET","checkDoneSearchResults.php",true);
	xmlhttp.send();
}

function exportFile2(){
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById("loadgif").innerHTML="";
			document.getElementById("loading").innerHTML="Your results are ready! <a style=\"color: #000;text-decoration: none; border-bottom-width: 2px; border-bottom-style: dotted; border-bottom-color: #2387ba;\" href=\"./export_beagle.txt\" target=\"_blank\">Download Them!</a><br/> Thank you for using Beagle!";			
		}
	}
	xmlhttp.open("GET","export.php",true);
	xmlhttp.send();
	return false;
}

function exportFile(){
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			window.open('export_beagle.txt','_blank')
		}
	}
	xmlhttp.open("GET","export.php",true);
	xmlhttp.send();
	
	return false;
}

function clearColor(){
	$(".alignment_color").each(function() {
		current_color = getComputedStyle(this).getPropertyValue("background-color");
		var lastComma=current_color.lastIndexOf(',');
		var a=0;
		if(current_color.slice(lastComma)==", 0.8)"){
			a=0.01;
			
		}else{
			a=0.8;
			}
		var newColor = current_color.slice(0, lastComma + 1) + a + ")";
		$(this).css('background-color', newColor);
		
    });
}

function createImage(str){
	
	
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			$("#"+str).append(xmlhttp.responseText);
		}
	}
	xmlhttp.open("POST","getImage.php",true);
	xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlhttp.send("accession="+str);
	
	return false;
}
</script>
<script type="text/javascript">
$(function(){

  $('.tablesorter-childRow td').hide();

  $(".tablesorter-dropbox")
    .tablesorter({
      theme : 'blue',
      // this is the default setting
      cssChildRow: "tablesorter-childRow"
    })
    .tablesorterPager({
      container: $("#pager"),
      positionFixed: false
    });

  $('.tablesorter').delegate('.toggle', 'click' ,function(){

    $(this).closest('tr').nextUntil('tr:not(.tablesorter-childRow)').find('td').toggle();

	var idName=$(this).closest('tr').nextUntil('tr:not(.tablesorter-childRow)').find('.seq').attr('id')
	var myElem = document.getElementById(idName+"_images");
	var myElem2 = document.getElementById(idName+"_button");

	if (myElem2.src=="/beagle/results/images/toggle-reduce.png"){
		document.getElementById(idName+"_button").src="/beagle/results/images/toggle-expand.png";
	}else{
		document.getElementById(idName+"_button").src="/beagle/results/images/toggle-reduce.png";
	}
	
	if (myElem == null){
		createImage(idName);
	}

  });

});
</script>
</head>

<body>
<div id="wrapper">

<?php include '../header.txt';?>
<div style="min-height:500px;" id="main">

<!--Funzioni Php che mi serviranno-->
<?php

function ricercaDicotomica($array,$size,$value){
	$startIndex=0;
	$endIndex=$size-1;
	$pivot=0;
	if($endIndex<0){
		return $pivot;
	}elseif($array[$startIndex]<$value){
		return 1;
	}elseif($array[$endIndex]>$value){
		return 10000;
	}

	while( $startIndex <= $endIndex){
		$pivot=round(($startIndex+$endIndex)/2);
		if($array[$pivot]==$value){
			return $pivot;
		}
		if($array[$pivot]>$value){
			$startIndex=$pivot+1;
		}else{
			$endIndex=$pivot-1;
		}
	}
	//if($array($pivot)<$value){
		return $pivot;
	//}else{
	//	return $pivot-1;
	//}
}

function trovaGruppi($struct,$ln){
	$llim=array(88,128,500);
   $slim=array(array(0.48,0.5555556,0.627907,0.7027027),array(0.5333333, 0.5858586, 0.629032,0.6741573),array(0.5324675, 0.5733333, 0.6054054, 0.6424002));
   $lbin=0;
   $sbin=0;

	if($ln < $llim[0]){
		$lbin = 1;
	}elseif($ln < $llim[1]) {
		$lbin = 2;
	}elseif($ln < $llim[2]){
		$lbin = 3;
	}else {
 		$lbin = 4;
 		$sbin = 1;
 		return $lbin.$sbin;
	}


	if($struct < $slim[$lbin-1][0]){
		$sbin = 1;
	}elseif ($struct < $slim[$lbin-1][1]){
	   $sbin = 2;
	}elseif ($struct < $slim[$lbin-1][2]){
	   $sbin = 3;
	}elseif ($struct < $slim[$lbin-1][3]){
	   $sbin = 4;
	}else {
	   $sbin = 5;
	}
	return $lbin.$sbin;
}

function colora($s1,$str1,$s2,$str2,$id){
	$gradient = array ("229,0,23","228,12,21","227,25,20","226,38,19","225,51,18","225,63,17","224,76,16","223,89,14","222,102,13","221,114,12","221,127,11","220,140,10","219,153,9","218,165,8","217,178,6","217,191,5","216,204,4","215,216,3","214,229,2","213,242,1","213,255,0","212,242,12","212,230,25","212,217,38","212,205,51","212,192,63","211,180,76","211,167,89","211,155,102","211,142,114","211,130,127","210,117,140","210,105,153","210,92,165","210,80,178","210,67,191","209,54,204","209,42,216","209,29,229","209,17,242","209,5,255","199,4,255","189,4,255","179,4,255","169,4,255","160,3,255","150,3,255","140,3,255","130,3,255","120,2,255","111,2,255","101,2,255","91,2,255","81,1,255","71,1,255","62,1,255","52,0,255","42,0,255","32,0,255","22,0,255","13,0,255","12,9,255","11,19,255","11,28,255","10,38,255","9,47,255","9,57,255","8,66,255","7,76,255","7,85,255","6,95,255","5,105,255","5,114,255","4,124,255","3,133,255","3,143,255","2,152,255","1,162,255","1,171,255","0,181,255","0,191,255","0,194,244","0,197,233","0,201,223","0,204,212","0,207,202","0,211,191","0,214,181","0,217,170","0,221,160","0,224,149","0,228,139","0,231,128","0,234,118","0,238,107","0,241,97","0,244,86","0,248,76","0,251,65","0,255,55");
	
	$cmd="sed -n 2,2p info";
	$locale=exec($cmd);
		
	if(strcmp($locale,"local")!=0){
		#GLOBALE
		$accession="alignment{$id}";
		$contatore="cat ./images/{$accession} | sed '/^\s*$/d' | wc -l";
		$conta=exec($contatore);
		$step=1;
		if (($conta+1)<100){
			$step=round(100/($conta+1));
		}else{
			$step=1;
		}
		$estremi=array();
		$inizio=0;
		
		$myfile1 = fopen("./images/{$accession}", "r") or die("Unable to open file!");
		while(!feof($myfile1)){
			$values=rtrim(fgets($myfile1),"\r\n,");
			if(!empty($values)){
				$field=explode(",",$values);
				$estremi[]=array($field[0],$field[count($field)-1]);
			}
		}
		fclose($myfile1);
		$resS1=str_split($s1);
		$resS2=str_split($s2);
		$resStr1=str_split($str1);
		$resStr2=str_split($str2);
		for($i=0;$i<count($estremi);$i++){
			$indexStart=$estremi[$i][0]-1;
			$indexFine=$estremi[$i][1]-1;
			$offsetStart=floor($indexStart/60);
			$offsetEnd=floor($indexFine/60);
			
			if($offsetStart==$offsetEnd){
				$resS1[$indexStart]="<span class=\"alignment_color\" style=\"background-color: rgba({$gradient[$inizio]},0.8); \">".$resS1[$indexStart];
				$resS2[$indexStart]="<span class=\"alignment_color\" style=\"background-color: rgba({$gradient[$inizio]},0.8); \">".$resS2[$indexStart];
				$resStr1[$indexStart]="<span class=\"alignment_color\" style=\"background-color: rgba({$gradient[$inizio]},0.8);\">".$resStr1[$indexStart];
				$resStr2[$indexStart]="<span class=\"alignment_color\" style=\"background-color: rgba({$gradient[$inizio]},0.8);\">".$resStr2[$indexStart];			
				
				$resS1[$indexFine]=$resS1[$indexFine]."</span>";
				$resS2[$indexFine]=$resS2[$indexFine]."</span>";
				$resStr1[$indexFine]=$resStr1[$indexFine]."</span>";
				$resStr2[$indexFine]=$resStr2[$indexFine]."</span>";
			}else{
				
					$resS1[$indexStart]="<span class=\"alignment_color\" style=\"background-color: rgba({$gradient[$inizio]},0.8); \">".$resS1[$indexStart];
					$resS2[$indexStart]="<span class=\"alignment_color\" style=\"background-color: rgba({$gradient[$inizio]},0.8); \">".$resS2[$indexStart];
					$resStr1[$indexStart]="<span class=\"alignment_color\" style=\"background-color: rgba({$gradient[$inizio]},0.8);\">".$resStr1[$indexStart];
					$resStr2[$indexStart]="<span class=\"alignment_color\" style=\"background-color: rgba({$gradient[$inizio]},0.8);\">".$resStr2[$indexStart];
					
				for($j=1;$j<=$offsetEnd-$offsetStart;$j++){
					
					$newEnd=(($offsetStart+$j)*60)-1;
					$newStart=(($offsetStart+$j)*60);
					
					$resS1[$newEnd]=$resS1[$newEnd]."</span>";
					$resS2[$newEnd]=$resS2[$newEnd]."</span>";
					$resStr1[$newEnd]=$resStr1[$newEnd]."</span>";
					$resStr2[$newEnd]=$resStr2[$newEnd]."</span>";
					
					$resS1[$newStart]="<span class=\"alignment_color\" style=\"background-color: rgba({$gradient[$inizio]},0.8); \">".$resS1[$newStart];
					$resS2[$newStart]="<span class=\"alignment_color\" style=\"background-color: rgba({$gradient[$inizio]},0.8); \">".$resS2[$newStart];
					$resStr1[$newStart]="<span class=\"alignment_color\" style=\"background-color: rgba({$gradient[$inizio]},0.8);\">".$resStr1[$newStart];
					$resStr2[$newStart]="<span class=\"alignment_color\" style=\"background-color: rgba({$gradient[$inizio]},0.8);\">".$resStr2[$newStart];			
				
					
					
				}
	
				$resS1[$indexFine]="{$resS1[$indexFine]}</span>";
				$resS2[$indexFine]=$resS2[$indexFine]."</span>";
				$resStr1[$indexFine]=$resStr1[$indexFine]."</span>";
				$resStr2[$indexFine]=$resStr2[$indexFine]."</span>";
					
			}
			$inizio=($inizio+$step)%100;
		}
	}else{
		#LOCALE
		$resS1=str_split($s1);
		$resS2=str_split($s2);
		$resStr1=str_split($str1);
		$resStr2=str_split($str2);
		$indexStart=0;
		$indexFine=strlen($s1);
		$offsetStart=floor($indexStart/60);
		$offsetEnd=floor($indexFine/60);
			
			if($offsetStart==$offsetEnd){
				$resS1[$indexStart]="<span class=\"alignment_color\" style=\"background-color: rgba(10,38,255,0.8); \">".$resS1[$indexStart];
				$resS2[$indexStart]="<span class=\"alignment_color\" style=\"background-color: rgba(10,38,255,0.8); \">".$resS2[$indexStart];
				$resStr1[$indexStart]="<span class=\"alignment_color\" style=\"background-color: rgba(10,38,255,0.8);\">".$resStr1[$indexStart];
				$resStr2[$indexStart]="<span class=\"alignment_color\" style=\"background-color: rgba(10,38,255,0.8);\">".$resStr2[$indexStart];			
				
				$resS1[$indexFine]=$resS1[$indexFine]."</span>";
				$resS2[$indexFine]=$resS2[$indexFine]."</span>";
				$resStr1[$indexFine]=$resStr1[$indexFine]."</span>";
				$resStr2[$indexFine]=$resStr2[$indexFine]."</span>";
			}else{
				
					$resS1[$indexStart]="<span class=\"alignment_color\" style=\"background-color: rgba(10,38,255,0.8); \">".$resS1[$indexStart];
					$resS2[$indexStart]="<span class=\"alignment_color\" style=\"background-color: rgba(10,38,255,0.8); \">".$resS2[$indexStart];
					$resStr1[$indexStart]="<span class=\"alignment_color\" style=\"background-color: rgba(10,38,255,0.8);\">".$resStr1[$indexStart];
					$resStr2[$indexStart]="<span class=\"alignment_color\" style=\"background-color: rgba(10,38,255,0.8);\">".$resStr2[$indexStart];
					
				for($j=1;$j<=$offsetEnd-$offsetStart;$j++){
					
					$newEnd=(($offsetStart+$j)*60)-1;
					$newStart=(($offsetStart+$j)*60);
					
					$resS1[$newEnd]=$resS1[$newEnd]."</span>";
					$resS2[$newEnd]=$resS2[$newEnd]."</span>";
					$resStr1[$newEnd]=$resStr1[$newEnd]."</span>";
					$resStr2[$newEnd]=$resStr2[$newEnd]."</span>";
					
					$resS1[$newStart]="<span class=\"alignment_color\" style=\"background-color: rgba(10,38,255,0.8); \">".$resS1[$newStart];
					$resS2[$newStart]="<span class=\"alignment_color\" style=\"background-color: rgba(10,38,255,0.8); \">".$resS2[$newStart];
					$resStr1[$newStart]="<span class=\"alignment_color\" style=\"background-color: rgba(10,38,255,0.8);\">".$resStr1[$newStart];
					$resStr2[$newStart]="<span class=\"alignment_color\" style=\"background-color: rgba(10,38,255,0.8);\">".$resStr2[$newStart];			
				
					
					
				}
	
				$resS1[$indexFine]="{$resS1[$indexFine]}</span>";
				$resS2[$indexFine]=$resS2[$indexFine]."</span>";
				$resStr1[$indexFine]=$resStr1[$indexFine]."</span>";
				$resStr2[$indexFine]=$resStr2[$indexFine]."</span>";
			}
	}
	return array($resS1,$resS2,$resStr1,$resStr2);
}

function getScore($s1,$b1,$s2,$b2,$cont){
	$BEAR=array("j"=>"LOOP","k"=>"LOOP","l"=>"LOOP","m"=>"LOOP","n"=>"LOOP","o"=>"LOOP","p"=>"LOOP","q"=>"LOOP","r"=>"LOOP","s"=>"LOOP","t"=>"LOOP","u"=>"LOOP","v"=>"LOOP","w"=>"LOOP","x"=>"LOOP","y"=>"LOOP","z"=>"LOOP","^"=>"LOOP","a"=>"STEM","b"=>"STEM","c"=>"STEM","d"=>"STEM","e"=>"STEM","f"=>"STEM","g"=>"STEM","h"=>"STEM","i"=>"STEM","="=>"STEM","A"=>"STEM_branch","B"=>"STEM_branch","C"=>"STEM_branch","D"=>"STEM_branch","E"=>"STEM_branch","F"=>"STEM_branch","G"=>"STEM_branch","H"=>"STEM_branch","I"=>"STEM_branch","J"=>"STEM_branch","?"=>"LEFTINTERNALLOOP","!"=>"LEFTINTERNALLOOP","\""=>"LEFTINTERNALLOOP","#"=>"LEFTINTERNALLOOP","$"=>"LEFTINTERNALLOOP","%"=>"LEFTINTERNALLOOP","&"=>"LEFTINTERNALLOOP","'"=>"LEFTINTERNALLOOP","("=>"LEFTINTERNALLOOP",")"=>"LEFTINTERNALLOOP","+"=>"LEFTINTERNALLOOP","["=>"BULGELEFT","?"=>"LEFTINTERNALLOOP_branch","K"=>"LEFTINTERNALLOOP_branch","L"=>"LEFTINTERNALLOOP_branch","M"=>"LEFTINTERNALLOOP_branch","N"=>"LEFTINTERNALLOOP_branch","O"=>"LEFTINTERNALLOOP_branch","P"=>"LEFTINTERNALLOOP_branch","Q"=>"LEFTINTERNALLOOP_branch","R"=>"LEFTINTERNALLOOP_branch","S"=>"LEFTINTERNALLOOP_branch","T"=>"LEFTINTERNALLOOP_branch","U"=>"LEFTINTERNALLOOP_branch","V"=>"LEFTINTERNALLOOP_branch","W"=>"LEFTINTERNALLOOP_branch","{"=>"BULGELFETBRANCH","?"=>"RIGHTINTERNALLOOP","2"=>"RIGHTINTERNALLOOP","3"=>"RIGHTINTERNALLOOP","4"=>"RIGHTINTERNALLOOP","5"=>"RIGHTINTERNALLOOP","6"=>"RIGHTINTERNALLOOP","7"=>"RIGHTINTERNALLOOP","8"=>"RIGHTINTERNALLOOP","9"=>"RIGHTINTERNALLOOP","0"=>"RIGHTINTERNALLOOP",">"=>"RIGHTINTERNALLOOP","]"=>"BULGERIGHT","?"=>"RIGHTINTERNALLOOP_branch","Y"=>"RIGHTINTERNALLOOP_branch","Z"=>"RIGHTINTERNALLOOP_branch","~"=>"RIGHTINTERNALLOOP_branch","?"=>"RIGHTINTERNALLOOP_branch","_"=>"RIGHTINTERNALLOOP_branch","|"=>"RIGHTINTERNALLOOP_branch","/"=>"RIGHTINTERNALLOOP_branch","\\"=>"RIGHTINTERNALLOOP_branch","@"=>"RIGHTINTERNALLOOP_branch","}"=>"BULGERIGTHBRANCH",":"=>"BRANCH");
	$seqId=0;
	$strId=0;
	$strSim=0;
	$tot=strlen(str_replace("-","",$s1));
	$tot_2=strlen(str_replace("-","",$s2));
	$myfile = fopen("./images/alignment{$cont}", "w") or die("Unable to open file!");
	$fileS1 = fopen("./images/alignment{$cont}_1", "w") or die("Unable to open file!");
	$fileS2 = fopen("./images/alignment{$cont}_2", "w") or die("Unable to open file!");
	$gap=0;
	$gap1=0;
	$gap2=0;
	$b1Struct=0;
	$b2Struct=0;
	for($i=0;$i<strlen($s1);$i++){
		if(strcmp($s1[$i],"-")!=0 && strcmp("-",$s2[$i])!=0){
			$gap=0;
			if(strcmp($s1[$i],$s2[$i])==0){
				$seqId++;
			}
			if(strcmp($b1[$i],$b2[$i])==0){
				$strId++;
			}
			if(strcmp($BEAR[$b1[$i]],$BEAR[$b2[$i]])==0){
				$strSim++;
				fwrite($myfile,($i+1).",");
				fwrite($fileS1,($i+1)-$gap1.",");
				fwrite($fileS2,($i+1)-$gap2.",");
			}else{
				fwrite($myfile,"\n");
				fwrite($fileS1,"\n");
				fwrite($fileS2,"\n");
			}
			if(strcmp($BEAR[$b1[$i]],"STEM")==0 || strcmp($BEAR[$b1[$i]],"STEM_branch")==0){
                                        $b1Struct++;
                        }
                        if(strcmp($BEAR[$b2[$i]],"STEM")==0 || strcmp($BEAR[$b2[$i]],"STEM_branch")==0){
                                       $b2Struct++;
                       }
		}else{
			if($gap==0 && $i!=0){
				fwrite($myfile,"\n");
				fwrite($fileS1,"\n");
				fwrite($fileS2,"\n");
			}
			$gap++;
			if(strcmp($s1[$i],"-")==0){
				$gap1++;
			}
			if(strcmp($s2[$i],"-")==0){
				$gap2++;
			}
		}
	}
	fclose($myfile);
	fclose($fileS1);
	fclose($fileS2);
	return array(round(($seqId/$tot)*100,2),round(($strId/$tot)*100,2),round(($strSim/$tot)*100,2),($b1Struct/$tot),$tot,($b2Struct/$tot_2),$tot_2);
}

function creaTabellaRisultati(){
	echo "<span style=\"margin-bottom:4px;float:right;text-align:right;color: #000;text-decoration: none; border-bottom-width: 2px; border-bottom-style: dotted; border-bottom-color: #2387ba; cursor: hand; cursor: pointer;\" onclick=\"exportFile()\">Download Results</span>
	<span style=\"margin-bottom:4px;float:left;text-align:right;color: #000;text-decoration: none; border-bottom-width: 2px; border-bottom-style: dotted; border-bottom-color: #2387ba; cursor: hand; cursor: pointer;\" onclick=\"clearColor()\">Toggle Color</span>
	<table class=\"tablesorter-dropbox\"><colgroup><col width=\"42px\" />   
	<col width=\"268px\" /> 
	<col width=\"268px\" />
    <col width=\"80px\" />
    <col width=\"80px\" />
    <col width=\"80px\" />
    <col width=\"80px\" />
    <col width=\"80px\" />
  </colgroup>
  <thead>
    <tr>
      <th></th>
      <th>RNA 1 (Id)</th>
      <th>RNA 2 (Id)</th>
      <th>Seq Id (%)</th>
      <th>Str Id (%)</th>
      <th>Str Sim(%)</th>
      <th>p-value</th>
      <th>z-score</th>
    </tr>
  </thead>
  <tbody>";
$myfile = fopen("results_beagle.txt", "r") or die("Unable to open file!");
// Output one line until end-of-file
#echo "<tr>";
$counter=0;
while(!feof($myfile)){
	$line=fgets($myfile);
	if (strcmp(rtrim($line,"\r\n"),"")!=0) {
	
	$field=explode("|",$line);
	$rnaName1=substr($field[0],1);
	$rnaName2=$field[1];
	$score=trim($field[2]);
	$rnaSeq1=rtrim(fgets($myfile),"\r\n");
	$rnaStr1=rtrim(fgets($myfile),"\r\n");
	$rnaBear1=rtrim(fgets($myfile),"\r\n");
	$rnaSeq2=rtrim(fgets($myfile),"\r\n");
	$rnaStr2=rtrim(fgets($myfile),"\r\n");
	$rnaBear2=rtrim(fgets($myfile),"\r\n");
	list($seqId,$strId,$strSim,$b1Struct,$len1,$b2Struct,$len2)=getScore($rnaSeq1,$rnaBear1,$rnaSeq2,$rnaBear2,$counter);
	
	$scoreNorm=($score/strlen($rnaSeq1));
	
##############viene calcolato lo zscore###################	
	$group1=trovaGruppi($b1Struct,$len1);
	$group2=trovaGruppi($b2Struct,$len2);
	$cmd="";

	if($group1<$group2 || $group1==41){
		$cmd="grep {$group1}_{$group2}	../../script/zscoreValues|cut -f 2,3";
	}else {
		$cmd="grep {$group2}_{$group1}	../../script/zscoreValues| cut -f 2,3";
	}

	$tmp_res=exec($cmd);
	$array_res=explode("\t",$tmp_res);
	$zScore=round(($scoreNorm-$array_res[0])/$array_res[1],3);

##############viene calcolato il pvalue###################
	$cmd="";
	if($group1<$group2 || $group1==41){
		$cmd="sed ':a;N;$!ba;s/\\n/\\t/g' ../../script/10k/pair{$group1}_{$group2}.ranknorm";
	}else {
		$cmd="sed ':a;N;$!ba;s/\\n/\\t/g' ../../script/10k/pair{$group2}_{$group1}.ranknorm";
	}
	$tmp_res=exec($cmd);
	$array_res=explode("\t",$tmp_res);
//	print_r($array_res);
//	$pValue_lower=array_filter($array_res,function($valore)use($scoreNorm){ return $valore > $scoreNorm; });
	$pValue_lower=ricercaDicotomica($array_res,count($array_res),$scoreNorm);
	$pValue=$pValue_lower/10000;
//	print_r($pValue_lower);
//	$pValue=(count($pValue_lower))/10000;
	
	if($pValue==0) $pValue=0.0001;
	
###########################################################
	
	echo "<tr><td style=\"text-align: center;\" rowspan=\"2\"><a href=\"#\" class=\"toggle\"><img id=\"alignment".$counter."_button\" src=\"../images/toggle-expand.png\" width=\"18\" height=\"18\" alt=\"expand\" /></a></td>";
	echo "<td>".$rnaName1."</td>";
	echo "<td>".$rnaName2."</td>";
	
	echo "<td>".$seqId."</td>";
	echo "<td>".$strId."</td>";
	echo "<td>".$strSim."</td>";
	echo "<td>".$pValue."</td>";
	echo "<td>".$zScore."</td>";
	echo "</tr>";
	echo "<tr class=\"tablesorter-childRow\"><td style=\"font-family:Monaco, monospace; line-height:normal;\" colspan=\"7\"><div> <pre class=\"seq\" id=\"alignment".$counter."\">";
	#echo $rnaStr1."<br>".$rnaSeq1."<br>".$rnaSeq2."<br>".$rnaStr2."<br>";
	list($sc1,$sc2,$st1,$st2)=colora($rnaSeq1,$rnaStr1,$rnaSeq2,$rnaStr2,$counter);

	$numerogap1=0;
	$numerogap2=0;
	$oldNumber1=0;
	$oldNumber2=0;
	
	for ($riga=1;$riga<(strlen($rnaBear1)/60);$riga++){
		$oldNumber1=$numerogap1;
		$oldNumber2=$numerogap2;
		$gapped1=substr($rnaSeq1,(60*($riga-1)),60);
		$gapped2=substr($rnaSeq2,(60*($riga-1)),60);
		
		$countSequenceGap1=substr_count($gapped1,"-");
		$countSequenceGap2=substr_count($gapped2,"-");
		
		$numerogap1=$numerogap1+$countSequenceGap1;
		$numerogap2=$numerogap2+$countSequenceGap2;
		
		echo "       \t"."\t".implode(array_slice($st1,(60*($riga-1)),60))."\n";
		echo substr($rnaName1,0,6)."\t".((60*($riga-1)+1)-$oldNumber1)."\t".implode(array_slice($sc1,(60*($riga-1)),60))."\t".((60*$riga)-$numerogap1)."\n";
		echo substr($rnaName2,0,6)."\t".((60*($riga-1)+1)-$oldNumber2)."\t".implode(array_slice($sc2,(60*($riga-1)),60))."\t".((60*$riga)-$numerogap2)."\n";
		echo "       \t"."\t".implode(array_slice($st2,(60*($riga-1)),60))."\n\n";
	}
	
	if ((strlen($rnaBear1)%60)!=0){
		
		$oldNumber1=$numerogap1;
		$oldNumber2=$numerogap2;
		$gapped1=substr($rnaSeq1,(60*($riga-1)));
		$gapped2=substr($rnaSeq2,(60*($riga-1)));
		
		$countSequenceGap1=substr_count($gapped1,"-");
		$countSequenceGap2=substr_count($gapped2,"-");
		
		$numerogap1=$numerogap1+$countSequenceGap1;
		$numerogap2=$numerogap2+$countSequenceGap2;
		
		echo "       \t"."\t".implode(array_slice($st1,(60*($riga-1))))."\n";
		echo substr($rnaName1,0,6)."\t".((60*($riga-1)+1)-$oldNumber1)."\t".implode(array_slice($sc1,(60*($riga-1))))."\t".(strlen($rnaBear1)-$numerogap1)."\n";
		echo substr($rnaName2,0,6)."\t".((60*($riga-1)+1)-$oldNumber2)."\t".implode(array_slice($sc2,(60*($riga-1))))."\t".(strlen($rnaBear2)-$numerogap2)."\n";
		echo "       \t"."\t".implode(array_slice($st2,(60*($riga-1))))."\n\n";
	}
	$counter++;
#	echo $rnaStr1.$rnaSeq1.$rnaSeq2.$rnaStr2;
	echo "</pre></div></td></tr>";
	}
}
fclose($myfile);
	echo" </tbody>
</table><div id=\"pager\" class=\"pager\">
  <form>
    <input type=\"button\" value=\"&lt;&lt;\" class=\"first\" />
    <input type=\"button\" value=\"&lt;\" class=\"prev\" />
    <input type=\"text\" class=\"pagedisplay\"/>
    <input type=\"button\" value=\"&gt;\" class=\"next\" />
    <input type=\"button\" value=\"&gt;&gt;\" class=\"last\" />
    <select class=\"pagesize\">
      <option selected=\"selected\"  value=\"10\">10</option>
      <option value=\"20\">20</option>
      <option value=\"30\">30</option>
      <option value=\"40\">40</option>
    </select>
  </form>
</div>";
}
?>
<!--Qui si può riempire la pagina -->

<?php
$cmd="sed -n 3,3p info";
$comparison=exec($cmd);
if($comparison=="search"){
	echo "<div style=\"text-align:center;\">The raw alignments are ready :<a style=\"color: #000;text-decoration: none; border-bottom-width: 2px; border-bottom-style: dotted; border-bottom-color: #2387ba;\" href=\"./results_beagle.txt\" target=\"_blank\">Download Them!</a><br/></div>";
	echo "<script>interval1=setInterval(function(){checkCompletion();},1000);</script>";
	echo "<br/>";
	echo "<div id=\"current\"></div>";
	echo "<div id=\"secondstep\" style=\"display:none\"> <div id=\"loading\" style=\"text-align:center;\">We are computing p-value and z-score for your alignments.<br>Your results will be available soon... Please be patient.<br>Percent done <span id='percdone'></span></div>";
	echo "<center><span id=\"loadgif\" style=\"text-align:center;\"><img width=\"48px\" src=\"../images/loading.gif\" /></span></center></div>";
} else {
	creaTabellaRisultati();
}
?>
<div style="margin-left:auto; margin-right:auto; margin-bottom:auto;">
    <!-- MP4 video acting like a GIF -->
    <video width="80" height="80" autoplay loop muted playsinline>
        <source src="../images/beagle_gif.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>
</div>
<?php include '../footer.txt';?>
</div>
</body>
</html>

