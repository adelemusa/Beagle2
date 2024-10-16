<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BEAGLE aligner</title>
<link href="css/myStyle.css" rel="stylesheet" type="text/css" />
<script>
function checkCompletion(path){
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			if(xmlhttp.responseText==1){
					clearInterval(progress);
					document.getElementById("percentage").innerHTML=100;
					 setTimeout(function(){
								window.location.replace("http://160.80.34.135/"+path+"/results.php")	}, 1000);
		 
			}else{
				 document.getElementById("percentage").innerHTML=Math.round(xmlhttp.responseText*100);
			}
	
		}
	}
	xmlhttp.open("POST","getPercentage.php",true);
	xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlhttp.send("path="+path);
}

	
</script>


</head>

<body>
<div id="wrapper">
<?php
function generateFolder(){
	$inputHash=date('dMy_G_i_s');
	$rand=mt_rand();
	$today=date('dMy');
	$code=hash('md5',"{$inputHash}{$rand}");
	return "results/{$today}_{$code}";
}

function checkNucleotides($seq){
	$seq1=strtolower($seq);
	if (preg_match("/^[acgturyswkmbdhvn]+$/",$seq1)){
		return true;
	} else {
		return false;
	}
}

function checkBrackets($str){
	if (preg_match("/^[.()]+$/",$str)){
		return true;
	} else {
		return false;
	}
}
function checkBear($br){
#	$bear="abcdefghi=lmnopqrstuvwxyz\^\!\"\#\$\%\&\'\(\)\+234567890\>\\\[\\\]\:ABCDEFGHIJKLMNOPQRSTUVW\{YZ\~\?\_\|\/\\\}\@";
	$bear="abcdefghi=lmnopqrstuvwxyz^!\"#$%&\'()+234567890>[]:ABCDEFGHIJKLMNOPQRSTUVW{YZ~?_|/\}@";
	$safe=preg_quote($bear,"/");
	if(preg_match("/^[{$safe}]+$/",$br)){
		return true;
	} else {
		return false;
	}
}

function readInput($fasta,$path,$input){
	#Questa funziona verifica che l'input sia corretto e divide le sequenze da foldare da quelle già foldate
	#echo "./{$path}/input{$input}<br/>";
	$file1=fopen("./{$path}/input{$input}","w") or die("Unable to open file!");
	$file2=fopen("./{$path}/input{$input}_daFoldare","w") or die("Unable to open file!");
	$contatore=0;
	$seq="";
	$str="";
	$bear="";
	$name="";
	$array=explode("\r\n",rtrim($fasta,"\r\n"));
	if(strcmp(substr($array[0],0,1),">")!=0){
		return array("ERROR",$contatore);
	}
	for($i=0 ;$i<count($array);$i++){
		if(strcmp(substr($array[$i],0,1),">")==0){
			$contatore++;
			if (strcmp($name,"")==0){
				$name=str_replace(' ', '', $array[$i]);
				#$name=$array[$i];
			}else{
				if(strcmp($str,"")==0 && strlen($seq)<5000 && strcmp($bear,"")==0){
					fwrite($file2,$name."\n");
					fwrite($file2,$seq."\n");
				} else if(strcmp($str,"")!=0 && strlen($seq)==strlen($str) && strcmp($bear,"")==0){
					fwrite($file1,$name."\n");
					fwrite($file1,$seq."\n");
					fwrite($file1,$str."\n");
				} else if (strcmp($str,"")!=0 && strlen($seq)==strlen($bear) && strlen($seq)==strlen($str) && strcmp($bear,"")!=0){
					fwrite($file1,$name."\n");
					fwrite($file1,$seq."\n");
					fwrite($file1,$str."\n");
					fwrite($file1,$bear."\n");
				}else{
					return array("ERROR",$contatore);
				}
				$seq="";
				$str="";
				$bear="";
				$name=str_replace(' ', '', $array[$i]);
				#$name=$array[$i];
			}
		}else if(checkNucleotides($array[$i])){
			#echo "salvo le sequenze<br/>$seq<br/>$array[$i]<br/>";
			$seq.=$array[$i];
		}else if(checkBrackets($array[$i])){
			#echo "salvo le strutture<br/>";
			$str.=$array[$i];
		}else if(checkBear($array[$i])){
			#echo "salvo i BEAR<br/>";
			$bear.=$array[$i];
		}else{
			return array("ERROR",$contatore);
			
		}
	}
#Ultimo passaggio prima della fine della funzione
	if(strcmp($str,"")==0 && strlen($seq)<5000 && strcmp($bear,"")==0){
		fwrite($file2,$name."\n");
		fwrite($file2,$seq);
	} else if(strcmp($str,"")!=0 && strlen($seq)==strlen($str) && strcmp($bear,"")==0){
		fwrite($file1,$name."\n");
		fwrite($file1,$seq."\n");
		fwrite($file1,$str);
	} else if (strcmp($str,"")!=0 && strlen($seq)==strlen($bear) && strlen($seq)==strlen($str) && strcmp($bear,"")!=0){
		fwrite($file1,$name."\n");
		fwrite($file1,$seq."\n");
		fwrite($file1,$str."\n");
		fwrite($file1,$bear."\n");
	}else{
		return array("ERROR",$contatore);
	}
#Fine ultimo controllo	
	fclose($file1);
	fclose($file2);
	chmod("./{$path}/input{$input}",0777);
	chmod("./{$path}/input{$input}_daFoldare",0777);
	if ($contatore>0){
		#echo "$contatore<br/><br/><br/>";
		return $contatore;
	}else{
		return array("ERROR",$contatore);
	}
}


?>


<?php include 'header.txt';?>
<div style="height:500px" id="main">
Thanks for using <strong>BEAGLE</strong> !
<br/><br/>
We are currently aligning your RNA secondary structures. At the end of the computation you will be automatically redirected to the results page. Alternatively, you can bookmark the following link and access to results at your own pace :<br />
<!--Qui si può riempire la pagina -->
<?php 
#-p per i pairwise; -l per i locali
$tipoComparazione = ($_POST["comparison"] === "pairwise") ? "-p" : "";
$locale = ($_POST["globalLocal"] === "Local") ? "-l" : "";

#Path dei risultati
$path=generateFolder();
#Creo le cartelle per i risultati
mkdir($path);
mkdir("$path/images");

if(is_numeric($_POST["gapInsertion"])&& is_numeric($_POST["gapExtension"]) && is_numeric($_POST["sequenceBonus"])){
	if($_POST["analysis"]=="twosets"){
		$res1=readInput($_POST["input1"],$path,1);
		$res2=readInput($_POST["input2"],$path,2);

		if(strcmp($res1[0],"ERROR")==0){
			
			echo "<p style=\"font-size:16px; color:red; font-weight: bold;\">Ops, it seems that an error occured while processing <i>input1</i> sequence <i>$res1[1]</i>.Please go back, check your input and try again.</p>";
			$cmd=fopen("rm -r ./{$path}/*");
			exec($cmd);
			$cmd=fopen("rmdir ./{$path}");
			exec($cmd);
			
		}else if(strcmp($res2[0],"ERROR")==0){
			
			echo "<p style=\"font-size:16px; color:red; font-weight: bold;\">Ops, it seems that an error occured while processing <i>input2</i> sequence <i>$res2[1]</i>. Please go back, check your input and try again.</p>";
			$cmd=fopen("rm -r ./{$path}/*");
			exec($cmd);
			$cmd=fopen("rmdir ./{$path}");
			exec($cmd);
			
		}else if((($res1*$res2)>10000) && strcmp($tipoComparazione,"-p")!=0){
			
			echo "<p style=\"font-size:16px; color:red; font-weight: bold;\">Ops, it seems that you requested for more than 10000 alignments. Please go back, check your input and try again.</p>";
			$cmd=fopen("rm -r ./{$path}/*");
			exec($cmd);
			$cmd=fopen("rmdir ./{$path}");
			exec($cmd);
			
		} else if(strcmp($tipoComparazione,"-p")==0 && $res1!=$res2 && !isset($_POST["dataset"])){
			
			echo "<p style=\"font-size:16px; color:red; font-weight: bold;\">Ops, it seems that input1 and input2 have not the same cardinality. Please go back, check your input and try again.</p>";
			$cmd="rm -r ./{$path}/*";
			exec($cmd);
			$cmd="rmdir ./{$path}";
			exec($cmd);
			
		} else{
			#Se tutti i controlli sono stati passati allora siamo in questa fase
			$file1=fopen("./{$path}/info","w") or die("Unable to open file!");
			if(strcmp($tipoComparazione,"-p")==0){
				fwrite($file1,$res1."\n");
			}else{
				fwrite($file1,$res1*$res2."\n");
			}
			if(strcmp($locale,"-l")==0){
				fwrite($file1,"local\n");
			}else{
				fwrite($file1,"global\n");
			}
			
			fclose($file1);
			$cmd="cp ./results/results.php ./{$path}";
			exec($cmd);
			$cmd="cp ./results/checkDoneSearchResults.php ./{$path}";
			exec($cmd);
			$cmd="cp ./results/export.php ./{$path}";
			exec($cmd);
			$cmd="cp ./results/getImage.php ./{$path}";
			exec($cmd);
                        $cmd="cp ./getPercentage.php ./{$path}";
                        exec($cmd);
			$file1=fopen("./{$path}/results_beagle.txt","w") or die("Unable to open file!");
			fclose($file1);
			chmod("./{$path}/results_beagle.txt",0777);
	
			$dim1=filesize("./{$path}/input1_daFoldare");
			$dim2=filesize("./{$path}/input2_daFoldare");
			if($dim1!=0){
				$cmd="RNAfold --noPS < ./{$path}/input1_daFoldare > ./{$path}/output1_daFoldare";
				exec($cmd);
				$cmd="cut -d\  -f1 ./{$path}/output1_daFoldare >> ./{$path}/input1";
				exec($cmd);
				$cmd="rm ./{$path}/input1_daFoldare";
				exec($cmd);
			}else{
				$cmd="rm ./{$path}/input1_daFoldare";
				exec($cmd);
			}
			if($dim2!=0){
				$cmd="RNAfold --noPS < ./{$path}/input2_daFoldare > ./{$path}/output2_daFoldare";
				exec($cmd);
				$cmd="cut -d\  -f1 ./{$path}/output2_daFoldare >> ./{$path}/input2";
				exec($cmd);
				$cmd="rm ./{$path}/input2_daFoldare";
				exec($cmd);
			}else{
				$cmd="rm ./{$path}/input2_daFoldare";
				exec($cmd);
			}
			echo "<br/><br/>";
			
			$cmd="java -jar ./jar/AMBeR.jar {$tipoComparazione} {$locale} -g {$_POST["gapInsertion"]}  -e {$_POST["gapExtension"]} -b {$_POST["sequenceBonus"]} -input1 ./{$path}/input1 -input2 ./{$path}/input2 -outfile ./{$path}/results_beagle.txt &";
	
			#echo str_replace("/{$path}","",$cmd);
	
			exec($cmd);
			
			echo "http://160.80.34.135/$path/results.php <br/>";
			
			#echo "<a target=\"_self\" href=\"http://160.80.34.123/~eugenio/AMBeR/".$path."/results.php\">Click Here</a> to jump to results";
			
			echo "<br/><br/>";
			echo "<div style=\"display: inline;\" id=\"progress\">We have currently performed the <div style=\"display: inline;\" id=\"percentage\">0</div>% of your alignments.</div>";
			echo "<script>var progress=setInterval(checkCompletion,500,\"{$path}\");</script>";
		}
	##################################################################
	#QUI COMINCIA LA PARTE CON SEARCH                                #
	##################################################################
	} else if($_POST["analysis"]=="search"){
		
			$res3=$_POST["dataset"];
			$res1=readInput($_POST["input1"],$path,1);
			
			if(strcmp($res3,"rfam")==0){
				$dts="rfam.fa";
				$dts_cardinality=29649;
			}else if(strcmp($res3,"utrMM")==0){
				$dts="3UTR_MM.fa";
				$dts_cardinality=40883;
			}else if(strcmp($res3,"utrHS")==0){
				$dts="3UTR_HS.fa";
				$dts_cardinality=63286;
			}else if(strcmp($res3,"lncrnaMM")==0){
				$dts="lncRNA_MM.fa";
				$dts_cardinality=9955;
			}else if(strcmp($res3,"lncrnaHS")==0){
				$dts="lncRNA_HS.fa";
				$dts_cardinality=24460;
			}else if(strcmp($res3,"GSE146952_vivo")==0){
				$dts="GSE146952_vivo.fa";
				$dts_cardinality=301;
			}else if(strcmp($res3,"GSE146952_vivo")==0){
				$dts="GSE146952_vivo.fa";
				$dts_cardinality=301;
			}else if(strcmp($res3,"GSE145805_HeLa")==0){
				$dts="GSE145805_HeLa.fasta";
				$dts_cardinality=51662;
			}else if(strcmp($res3,"GSE145805_HEK293")==0){
				$dts="GSE145805_HEK293.fasta";
				$dts_cardinality=61663;
			}else if(strcmp($res3,"GSE145805_H9hESC")==0){
				$dts="GSE145805_H9hESC.fasta";
				$dts_cardinality=61953;
			}else if(strcmp($res3,"NONCODEv6_zebrafish")==0){
				$dts="NONCODEv6_zebrafish.db";
				$dts_cardinality=4852;
			}else if(strcmp($res3,"NONCODEv6_cow")==0){
				$dts="NONCODEv6_cow.db";
				$dts_cardinality=23515;
			}else if(strcmp($res3,"NONCODEv6_chimp")==0){
				$dts="NONCODEv6_chimp.db";
				$dts_cardinality=18004;
			}else if(strcmp($res3,"NONCODEv6_gorilla")==0){
				$dts="NONCODEv6_gorilla.db";
				$dts_cardinality=18105;
			}else if(strcmp($res3,"NONCODEv6_fruitfly")==0){
				$dts="NONCODEv6_fruitfly.db";
				$dts_cardinality=42848;
			}else if(strcmp($res3,"NONCODEv6_opossum")==0){
				$dts="NONCODEv6_opossum.db";
				$dts_cardinality=27167;
			}else if(strcmp($res3,"NONCODEv6_chicken")==0){
				$dts="NONCODEv6_chicken.db";
				$dts_cardinality=12850;
			}
		
			$cmd="cp ./datasets/{$dts} ./{$path}/input2";
			exec($cmd);


		if(strcmp($res1[0],"ERROR")==0){
			
			echo "<p style=\"font-size:16px; color:red; font-weight: bold;\">Ops, it seems that an error occured while processing <i>input1</i> sequence <i>$res1[1]</i>.Please go back, check your input and try again.</p>";
			$cmd=fopen("rm -r ./{$path}/*");
			exec($cmd);
			$cmd=fopen("rmdir ./{$path}");
			exec($cmd);
			
		}else if($res1>1){
			
			echo "<p style=\"font-size:16px; color:red; font-weight: bold;\">Ops, it seems that your input contains more than 1 sequence. Please go back, check your input and try again.</p>";
			$cmd=fopen("rm -r ./{$path}/*");
			exec($cmd);
			$cmd=fopen("rmdir ./{$path}");
			exec($cmd);
			
		} else{
			#Se tutti i controlli sono stati passati allora siamo in questa fase
			$file1=fopen("./{$path}/info","w") or die("Unable to open file!");
			#####Scrivere il numero degli allineamenti attesi
			fwrite($file1,$res1*$dts_cardinality."\n");
			
			if(strcmp($locale,"-l")==0){
				fwrite($file1,"local\n");
			}else{
				fwrite($file1,"global\n");
			}
			fwrite($file1,"search\n");
			
			fclose($file1);
			$cmd="cp ./results/results.php ./{$path}";
			exec($cmd);
			$cmd="cp ./results/export.php ./{$path}";
			exec($cmd);
			$cmd="cp ./results/getImage.php ./{$path}";
			exec($cmd);
			$cmd="cp ./results/checkDoneSearchResults.php ./{$path}";
			exec($cmd);
			$cmd="cp ./getPercentage.php ./{$path}";
                        exec($cmd);
			$file1=fopen("./{$path}/results_beagle.txt","w") or die("Unable to open file!");
			fclose($file1);
			chmod("./{$path}/results_beagle.txt",0777);
	
			$dim1=filesize("./{$path}/input1_daFoldare");
			if($dim1!=0){
				$cmd="RNAfold --noPS < ./{$path}/input1_daFoldare > ./{$path}/output1_daFoldare";
				exec($cmd);
				$cmd="cut -d\  -f1 ./{$path}/output1_daFoldare >> ./{$path}/input1";
				exec($cmd);
				$cmd="rm ./{$path}/input1_daFoldare";
				exec($cmd);
			}else{
				$cmd="rm ./{$path}/input1_daFoldare";
				exec($cmd);
			}
			echo "<br/><br/>";
			
			#$cmd="java -jar ./jar/AMBeR.jar {$locale} -g {$_POST["gapInsertion"]}  -e {$_POST["gapExtension"]} -b {$_POST["sequenceBonus"]} -input1 ./{$path}/input1 -input2 ./datasets/{$dts} -outfile ./{$path}/results_beagle.txt &";
			$cmd="java -jar ./jar/AMBeR.jar {$locale} -g {$_POST["gapInsertion"]}  -e {$_POST["gapExtension"]} -b {$_POST["sequenceBonus"]} -input1 ./{$path}/input1 -input2 ./datasets/{$dts}/ -outfile ./{$path}/results_beagle.txt &";
	
			#echo str_replace("/{$path}","",$cmd);
	
			#exec($cmd);
			pclose(popen($cmd,"r"));
			
			echo "http://160.80.34.135/$path/results.php <br/>";
			
			#echo "<a target=\"_self\" href=\"http://160.80.34.123/~eugenio/AMBeR/".$path."/results.php\">Click Here</a> to jump to results";
			
			echo "<br/><br/>";
			echo "<div style=\"display: inline;\" id=\"progress\">We have currently performed the <div style=\"display: inline;\" id=\"percentage\">0</div>% of your alignments.</div>";
			echo "<script>var progress=setInterval(checkCompletion,500,\"{$path}\");</script>";
		}
		
	
	##################################################################
	#QUI COMINCIA LA PARTE CON ONESET                                #
	##################################################################
	}else if($_POST["analysis"]=="oneset"){
			echo $path."<br/>";
			$res1=readInput($_POST["input1"],$path,1);


		if(strcmp($res1[0],"ERROR")==0){
			
			echo "<p style=\"font-size:16px; color:red; font-weight: bold;\">Ops, it seems that an error occured while processing <i>input1</i> sequence <i>$res1[1]</i>.Please go back, check your input and try again.</p>";
			$cmd=fopen("rm -r ./{$path}/*");
			exec($cmd);
			$cmd=fopen("rmdir ./{$path}");
			exec($cmd);
		}else if($res1<2){
			echo "<p style=\"font-size:16px; color:red; font-weight: bold;\">Ops, it seems that your input contains less than 2 sequences. Please go back, check your input and try again.</p>";
                        $cmd=fopen("rm -r ./{$path}/*");
                        exec($cmd);
                        $cmd=fopen("rmdir ./{$path}");
                        exec($cmd);
		}else if($res1>300){
			echo "<p style=\"font-size:16px; color:red; font-weight: bold;\">Ops, it seems that your input contains more than 300 sequences. Please go back, check your input and try again.</p>";
			$cmd=fopen("rm -r ./{$path}/*");
			exec($cmd);
			$cmd=fopen("rmdir ./{$path}");
			exec($cmd);
		} else{
			#Se tutti i controlli sono stati passati allora siamo in questa fase
			$file1=fopen("./{$path}/info","w") or die("Unable to open file!");
			#####Scrivere il numero degli allineamenti attesi
			fwrite($file1,($res1*($res1-1))/2.0."\n");
			#fwrite($file1,($res1*$res1)."\n");

			if(strcmp($locale,"-l")==0){
				fwrite($file1,"local\n");
			}else{
				fwrite($file1,"global\n");
			}
			
			fclose($file1);
			$cmd="cp ./results/results.php ./{$path}";
			exec($cmd);
			$cmd="cp ./results/export.php ./{$path}";
			exec($cmd);
			$cmd="cp ./results/oneset/getImage.php ./{$path}";
			exec($cmd);
			$cmd="cp ./results/checkDoneSearchResults.php ./{$path}";
			exec($cmd);
			$cmd="cp ./getPercentage.php ./{$path}";
                        exec($cmd);
			$file1=fopen("./{$path}/results_beagle.txt","w") or die("Unable to open file!");
			fclose($file1);
			chmod("./{$path}/results_beagle.txt",0777);
	
			$dim1=filesize("./{$path}/input1_daFoldare");
			if($dim1!=0){
				$cmd="RNAfold --noPS < ./{$path}/input1_daFoldare > ./{$path}/output1_daFoldare";
				exec($cmd);
				$cmd="cut -d\  -f1 ./{$path}/output1_daFoldare >> ./{$path}/input1";
				exec($cmd);
				$cmd="rm ./{$path}/input1_daFoldare";
				exec($cmd);
			}else{
				$cmd="rm ./{$path}/input1_daFoldare";
				exec($cmd);
			}
			echo "<br/><br/>";
			
			#$cmd="java -jar ./jar/AMBeRConONESET.jar {$locale} -c 1 -g {$_POST["gapInsertion"]}  -e {$_POST["gapExtension"]} -b {$_POST["sequenceBonus"]} -input1 ./{$path}/input1 -outfile ./{$path}/results_beagle.txt &";
			$cmd="java -jar ./jar/AMBeR.jar {$locale} -c 1 -g {$_POST["gapInsertion"]}  -e {$_POST["gapExtension"]} -b {$_POST["sequenceBonus"]} -input1 ./{$path}/input1 -outfile ./{$path}/results_beagle.txt &";
	
			#echo str_replace("/{$path}","",$cmd);
	
			#exec($cmd);
			pclose(popen($cmd,"r"));
			
			echo "http://160.80.34.135/$path/results.php <br/>";
			
			#echo "<a target=\"_self\" href=\"http://160.80.34.123/~eugenio/AMBeR/".$path."/results.php\">Click Here</a> to jump to results";
			
			echo "<br/><br/>";
			echo "<div style=\"display: inline;\" id=\"progress\">We have currently performed the <div style=\"display: inline;\" id=\"percentage\">0</div>% of your alignments.</div>";
			echo "<script>var progress=setInterval(checkCompletion,2000,\"{$path}\");</script>";
		}
		
	###################################################################	
	}else{
		echo "<p style=\"font-size:16px; color:red; font-weight: bold;\">Ops, it seems that an input is missing. Please go back, check your input and try again.</p>";
		$cmd=fopen("rm -r ./{$path}/*");
		exec($cmd);
		$cmd=fopen("rmdir ./{$path}");
		exec($cmd);
	}
}else{
	echo "<p style=\"font-size:16px; color:red; font-weight: bold;\">Ops, it seems that one of your parameters is wrong. Please go back, check your input and try again.</p>";

}

?></div>

<div style="margin-left:auto; margin-right:auto; margin-bottom:auto;">
    <!-- MP4 video acting like a GIF -->
    <video width="80" height="80" autoplay loop muted playsinline>
        <source src="../images/beagle_gif.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>
<?php include 'footer.txt';?>
</div>
</body>
</html>
