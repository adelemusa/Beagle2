+<?php
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





$BEAR=array("j"=>"LOOP","k"=>"LOOP","l"=>"LOOP","m"=>"LOOP","n"=>"LOOP","o"=>"LOOP","p"=>"LOOP","q"=>"LOOP","r"=>"LOOP","s"=>"LOOP","t"=>"LOOP","u"=>"LOOP","v"=>"LOOP","w"=>"LOOP","x"=>"LOOP","y"=>"LOOP","z"=>"LOOP","^"=>"LOOP","a"=>"STEM","b"=>"STEM","c"=>"STEM","d"=>"STEM","e"=>"STEM","f"=>"STEM","g"=>"STEM","h"=>"STEM","i"=>"STEM","="=>"STEM","A"=>"STEM_branch","B"=>"STEM_branch","C"=>"STEM_branch","D"=>"STEM_branch","E"=>"STEM_branch","F"=>"STEM_branch","G"=>"STEM_branch","H"=>"STEM_branch","I"=>"STEM_branch","J"=>"STEM_branch","?"=>"LEFTINTERNALLOOP","!"=>"LEFTINTERNALLOOP","\""=>"LEFTINTERNALLOOP","#"=>"LEFTINTERNALLOOP","$"=>"LEFTINTERNALLOOP","%"=>"LEFTINTERNALLOOP","&"=>"LEFTINTERNALLOOP","'"=>"LEFTINTERNALLOOP","("=>"LEFTINTERNALLOOP",")"=>"LEFTINTERNALLOOP","+"=>"LEFTINTERNALLOOP","["=>"BULGELEFT","?"=>"LEFTINTERNALLOOP_branch","K"=>"LEFTINTERNALLOOP_branch","L"=>"LEFTINTERNALLOOP_branch","M"=>"LEFTINTERNALLOOP_branch","N"=>"LEFTINTERNALLOOP_branch","O"=>"LEFTINTERNALLOOP_branch","P"=>"LEFTINTERNALLOOP_branch","Q"=>"LEFTINTERNALLOOP_branch","R"=>"LEFTINTERNALLOOP_branch","S"=>"LEFTINTERNALLOOP_branch","T"=>"LEFTINTERNALLOOP_branch","U"=>"LEFTINTERNALLOOP_branch","V"=>"LEFTINTERNALLOOP_branch","W"=>"LEFTINTERNALLOOP_branch","{"=>"BULGELFETBRANCH","?"=>"RIGHTINTERNALLOOP","2"=>"RIGHTINTERNALLOOP","3"=>"RIGHTINTERNALLOOP","4"=>"RIGHTINTERNALLOOP","5"=>"RIGHTINTERNALLOOP","6"=>"RIGHTINTERNALLOOP","7"=>"RIGHTINTERNALLOOP","8"=>"RIGHTINTERNALLOOP","9"=>"RIGHTINTERNALLOOP","0"=>"RIGHTINTERNALLOOP",">"=>"RIGHTINTERNALLOOP","]"=>"BULGERIGHT","?"=>"RIGHTINTERNALLOOP_branch","Y"=>"RIGHTINTERNALLOOP_branch","Z"=>"RIGHTINTERNALLOOP_branch","~"=>"RIGHTINTERNALLOOP_branch","?"=>"RIGHTINTERNALLOOP_branch","_"=>"RIGHTINTERNALLOOP_branch","|"=>"RIGHTINTERNALLOOP_branch","/"=>"RIGHTINTERNALLOOP_branch","\\"=>"RIGHTINTERNALLOOP_branch","@"=>"RIGHTINTERNALLOOP_branch","}"=>"BULGERIGTHBRANCH",":"=>"BRANCH");
if(!file_exists("./export_beagle.txt")){
$res=fopen("./export_beagle.txt", "w") or die("Unable to open file!");
$myfile = fopen("./results_beagle.txt", "r") or die("Unable to open file!");
while(!feof($myfile)){
	$line=rtrim(fgets($myfile),"\r\n");
	if (strcmp(rtrim($line,"\r\n"),"")!=0) {
		$field=explode("|",$line);
		$rnaName1=substr($field[0],1);
		$rnaName2=$field[1];
		$score=trim($field[2]);
		$s1=rtrim(fgets($myfile),"\r\n");
		$rnaStr1=rtrim(fgets($myfile),"\r\n");
		$b1=rtrim(fgets($myfile),"\r\n");
		$s2=rtrim(fgets($myfile),"\r\n");
		$rnaStr2=rtrim(fgets($myfile),"\r\n");
		$b2=rtrim(fgets($myfile),"\r\n");
		
		$tot=strlen(str_replace("-","",$s1));
		$tot_s2=strlen(str_replace("-","",$s2));
	
		$strId=0;
		$seqId=0;
		$strSim=0;
		$b1Struct=0;
		$b2Struct=0;
		for($i=0;$i<strlen($s1);$i++){
			if(strcmp($s1[$i],"-")!=0 && strcmp("-",$s2[$i])!=0){
				if(strcmp($s1[$i],$s2[$i])==0){
					$seqId++;
				}
				if(strcmp($b1[$i],$b2[$i])==0){
					$strId++;
				}
				if(strcmp($BEAR[$b1[$i]],$BEAR[$b2[$i]])==0){
					$strSim++;
				}
				if(strcmp($BEAR[$b1[$i]],"STEM")==0 || strcmp($BEAR[$b1[$i]],"STEM_branch")==0){
					$b1Struct++;
				}
				if(strcmp($BEAR[$b2[$i]],"STEM")==0 || strcmp($BEAR[$b2[$i]],"STEM_branch")==0){
                                        $b2Struct++;
                                }
			}
		}

$scoreNorm=$score/strlen($s1);

##############viene calcolato lo zscore###################
        $group1=trovaGruppi($b1Struct/$tot,$tot);
        $group2=trovaGruppi($b2Struct/$tot_s2,$tot_s2);
        $cmd="";

	if(strcmp($group1,"41")==0){
		$cmd="grep {$group1}_{$group2}  ../../script/zscoreValues|cut -f 2,3";
        }elseif(strcmp($group2,"41")==0){
		$cmd="grep {$group2}_{$group1}  ../../script/zscoreValues| cut -f 2,3";
        }elseif($group1<$group2){
		$cmd="grep {$group1}_{$group2}  ../../script/zscoreValues|cut -f 2,3";
        }else{
		$cmd="grep {$group2}_{$group1}  ../../script/zscoreValues| cut -f 2,3";
        }

        $tmp_res=exec($cmd);
        $array_res=explode("\t",$tmp_res);
        $zScore=round(($scoreNorm-$array_res[0])/$array_res[1],3);
##############viene calcolato il pvalue###################
        $cmd="";
	if(strcmp($group1,"41")==0){
		$cmd="sed ':a;N;$!ba;s/\\n/\\t/g' ../../script/10k/pair{$group1}_{$group2}.ranknorm";
	}elseif(strcmp($group2,"41")==0){
		$cmd="sed ':a;N;$!ba;s/\\n/\\t/g' ../../script/10k/pair{$group2}_{$group1}.ranknorm";
	}elseif($group1<$group2){
                $cmd="sed ':a;N;$!ba;s/\\n/\\t/g' ../../script/10k/pair{$group1}_{$group2}.ranknorm";
        }else{
                $cmd="sed ':a;N;$!ba;s/\\n/\\t/g' ../../script/10k/pair{$group2}_{$group1}.ranknorm";
        }
        $tmp_res=exec($cmd);
        $array_res=explode("\t",$tmp_res);
//      print_r($array_res);
        $pValue_lower=array_filter($array_res,function($valore)use($scoreNorm){ return $valore > $scoreNorm; });
//      print_r($pValue_lower);
        $pValue=(count($pValue_lower))/10000;
        if($pValue==0) $pValue=0.0001;

###########################################################
/*
		$cmd="";
		$tmplen=strlen($b1);
		$tmp_scoreNorm=$score/$tmplen;
		$tmp_structS1=$b1Struct/$tot;
		$tmp_structS2=$b2Struct/$tot_s2;
		$cmd="python ../../script/reportzp.py {$tmp_scoreNorm} {$tmp_structS1} {$tot} {$tmp_structS2} {$tot_s2}";
	        $risultati=array();
        	exec($cmd,$risultati);
	        $pValue=explode("] ",$risultati[1]);
	        $pValue=$pValue[1];
	        $zScore=explode("] ",$risultati[0]);
	        $zScore=$zScore[1];
*/		
		fwrite($res,">".$rnaName1."|".$rnaName2."|NW:".$score."|SeqIdentity:".round(($seqId/$tot)*100,2)."|StrIdentity:".round(($strId/$tot)*100,2)."|StrSimilarity:".round(($strSim/$tot)*100,2)."|P-value:".$pValue."|Z-score:".$zScore."\n");
		fwrite($res,$s1."\n");
		fwrite($res,$rnaStr1."\n");
		fwrite($res,$b1."\n");
		fwrite($res,$s2."\n");
		fwrite($res,$rnaStr2."\n");
		fwrite($res,$b2."\n\n");
	}
						
}
fclose($res);
fclose($myfile);	
}

?>
