<?php


$gradient = array("E50017","E40C15","E31914","E22613","E13312","E13F11","E04C10","DF590E","DE660D","DD720C","DD7F0B","DC8C0A","DB9909","DAA508","D9B206","D9BF05","D8CC04","D7D803","D6E502","D5F201","D5FF00","D4F20C","D4E619","D4D926","D4CD33","D4C03F","D3B44C","D3A759","D39B66","D38E72","D3827F","D2758C","D26999","D25CA5","D250B2","D243BF","D136CC","D12AD8","D11DE5","D111F2","D105FF","C704FF","BD04FF","B304FF","A904FF","A003FF","9603FF","8C03FF","8203FF","7802FF","6F02FF","6502FF","5B02FF","5101FF","4701FF","3E01FF","3400FF","2A00FF","2000FF","1600FF","0D00FF","0C09FF","0B13FF","0B1CFF","0A26FF","092FFF","0939FF","0842FF","074CFF","0755FF","065FFF","0569FF","0572FF","047CFF","0385FF","038FFF","0298FF","01A2FF","01ABFF","00B5FF","00BFFF","00C2F4","00C5E9","00C9DF","00CCD4","00CFCA","00D3BF","00D6B5","00D9AA","00DDA0","00E095","00E48B","00E780","00EA76","00EE6B","00F161","00F456","00F84C","00FB41","00FF37");

$accession=$_POST["accession"]; 
$results="";
$printed=false;
if (file_exists("./images/".$accession."_0.jpeg")){
#aggiungi direttamente le immagini
echo "<div id=\"".$accession."_images\" style=\"float:left;\"><img width=\"380\" src=\"./images/".$accession."_0.jpeg\"/></div><div id=\"".$accession."_images\" style=\"float:left;\"><img style=\"margin-left:3px;\" width=\"380\" src=\"./images/".$accession."_1.jpeg\"/></div>";

}else{
	
#Apri il file con gli allineamenti, prendi le sequenze e crea le immagini.	
	$myfile = fopen("results_beagle.txt", "r") or die("Unable to open file!");
	$counter=0;
	while(!feof($myfile) && !$printed){
		if (strcmp($accession,"alignment".$counter)==0) {
			$line=fgets($myfile);
			$field=explode("|",$line);
			$rnaName1=substr($field[0],1);
			$rnaName2=$field[1];
			$score=$field[2];
			
			$rnaSeq1=rtrim(fgets($myfile),"\r\n");
			$seqMod1=str_replace('-','',$rnaSeq1);			

			$lenNoGap1=strlen(str_replace('-','',$rnaSeq1));
			$lenConGap1=strlen($rnaSeq1);

			
			$rnaStr1=rtrim(fgets($myfile),"\r\n");
			$strMod1=str_replace('-','',$rnaStr1);
			
			$rnaBear1=rtrim(fgets($myfile),"\r\n");
			
			$rnaSeq2=rtrim(fgets($myfile),"\r\n");
			$lenS2=strlen(str_replace('-','',$rnaSeq2));
			$seqMod2=str_replace('-','',$rnaSeq2);

			$lenNoGap2=strlen(str_replace('-','',$rnaSeq2));
			$lenConGap2=strlen($rnaSeq2);

			
			$rnaStr2=rtrim(fgets($myfile),"\r\n");
			$strMod2=str_replace('-','',$rnaStr2);
					
			$rnaBear2=rtrim(fgets($myfile),"\r\n");
			
			#QUESTA PARTE SERVE PER RISOLVERE IL PROBLEMA DEL LOCALE
			$cmd="sed -n 2,2p info";
			$locale=exec($cmd);
			$offset1=0;
			$offset2=0;
		
			if(strcmp($locale,"local")==0){
				
				$counter=0;
				$newSeq1="";
				$newStr1="";
				$newSeq2="";
				$newStr2="";
				
				#cerco la sequenza originale nel file1
				$trovato=false;
				$fileInput = fopen("./input1", "r") or die("Unable to open file!");
				while(!feof($fileInput) && !$trovato){
					$line1=rtrim(fgets($fileInput),"\r\n");
					if(strcmp($line1,">".$rnaName1)==0){
						$newSeq1=rtrim(fgets($fileInput),"\r\n");
						$newStr1=str_replace("-",".",fgets($fileInput));

						$target=str_replace("-","",$rnaSeq1);

						preg_match("/{$target}/", $newSeq1, $matches, PREG_OFFSET_CAPTURE);
						$offset1=$matches[0][1];
						fgets($fileInput);
						$trovato=true;
					}else{
						fgets($fileInput);
						fgets($fileInput);
						fgets($fileInput);
					}
				}
				fclose($fileInput);
				
				#cerco la sequenza originale nel file2
				$trovato=false;
				$fileInput = fopen("./input1", "r") or die("Unable to open file!");
				while(!feof($fileInput) && !$trovato){
					
					$line1=rtrim(fgets($fileInput),"\r\n");
					if(strcmp($line1,">".$rnaName2)==0){
						
						$newSeq2=rtrim(fgets($fileInput),"\r\n");
						$newStr2=str_replace("-",".",fgets($fileInput));
						$target=(str_replace("-","",$rnaSeq2));

						preg_match("/{$target}/", $newSeq2, $matches, PREG_OFFSET_CAPTURE);						
						$offset2=$matches[0][1];
						fgets($fileInput);
						$trovato=true;
					}else{
						fgets($fileInput);
						fgets($fileInput);
						fgets($fileInput);
					}
				}
				fclose($fileInput);
				#$fff = fopen("iii", "w") or die("Unable to open file!");
				$seqMod1=$newSeq1;
				$seqMod2=$newSeq2;
				$strMod1=$newStr1;
				$strMod2=$newStr2;
				#fwrite($fff,$rnaSeq2);
				#fwrite($fff,"\n");
				#fwrite($fff,$seqMod2);
				#fwrite($fff,"\n");
				#fwrite($fff,$newSeq2);
				#fclose($fff);

				
			}
			#FINE DELLA PARTE DEL LOCALE
			
			
			#qui comincia la creazione delle immagini
			$cmd="java -cp ../../jar/VARNAv3-91-src.jar fr.orsay.lri.varna.applications.VARNAcmd -zoom \"0.9\" -sequenceDBN \"".$seqMod1."\" -structureDBN \"".$strMod1."\" -title ".$rnaName1." -titlesize 12 -o ./images/".$accession."_0.jpeg ";
			
			$cmd_1="java -cp ../../jar/VARNAv3-91-src.jar fr.orsay.lri.varna.applications.VARNAcmd -zoom \"0.9\" -sequenceDBN \"".$seqMod2."\" -structureDBN \"".$strMod2."\" -title ".$rnaName2." -titlesize 12 -o ./images/".$accession."_1.jpeg ";
			
			
			
			if(strcmp($locale,"local")!=0){
				#qui comincia l'aggiunta dei colori solo quando non è locale
				
				$contatore="cat ./images/{$accession}_1 | sed '/^\s*$/d' | wc -l";
				$conta=exec($contatore);
				$step=1;
				if (($conta+1)<100){
					$step=round(100/($conta+1));
				
					$inizio=0;
					$styleCounter=1;
					$myfile1 = fopen("./images/{$accession}_1", "r") or die("Unable to open file!");
					$myfile2 = fopen("./images/{$accession}_2", "r") or die("Unable to open file!");
					while(!feof($myfile1)){
						$values=rtrim(fgets($myfile1),"\r\n,");
						$values2=rtrim(fgets($myfile2),"\r\n,");
						if(!empty($values)){
							$cmd.="-basesStyle{$styleCounter} fill=#{$gradient[$inizio]} -applyBasesStyle{$styleCounter}on \"".$values."\" ";
							$cmd_1.="-basesStyle{$styleCounter} fill=#{$gradient[$inizio]} -applyBasesStyle{$styleCounter}on \"".$values2."\" ";
							$inizio=$inizio+$step;
							$styleCounter++;
						}
					}
					fclose($myfile1);
				}else{
					$inizio=0;
					$styleCounter=1;
					$myfile1 = fopen("./images/{$accession}_1", "r") or die("Unable to open file!");
					$myfile2 = fopen("./images/{$accession}_2", "r") or die("Unable to open file!");
					while(!feof($myfile1)){
						$values=rtrim(fgets($myfile1),"\r\n,");
						$values2=rtrim(fgets($myfile2),"\r\n,");
						if(!empty($values)){
							$cmd.="-basesStyle{$styleCounter} fill=#{$gradient[$inizio]} -applyBasesStyle{$styleCounter}on \"".$values."\" ";
							$cmd_1.="-basesStyle{$styleCounter} fill=#{$gradient[$inizio]} -applyBasesStyle{$styleCounter}on \"".$values2."\" ";
							$inizio=($inizio+$step)%100;
							$styleCounter++;
						}
					}
					fclose($myfile1);
					fclose($myfile2);
				}
				#$f=fopen("stringa","w")or die("Unable to open file!");
				#fwrite($f,$conta);
				#fclose($f);
				
			}else{
				
				#QUESTA E' LA COLORAZIONE NEL CASO DI SEQUENZA LOCALE
				
#				$values1=array_combine(range(0,$lenConGap1),range($offset1+1,$offset1+1+$lenConGap1));
#				$values2=array_combine(range(0,$lenConGap2),range($offset2+1,$offset2+1+$lenConGap2));
				$cmd.="-basesStyle1 fill=#0A26FF -applyBasesStyle1on \"".implode(",",range($offset1+1,$offset1+$lenNoGap1))."\" ";
				$cmd_1.="-basesStyle1 fill=#0A26FF -applyBasesStyle1on \"".implode(",",range($offset2+1,$offset2+$lenNoGap2))."\" ";
				
			}
			
			exec($cmd);

			exec($cmd_1);
			
			
			
			$printed=true;
			echo "<div id=\"".$accession."_images\" style=\"float:left;\"><img width=\"380\" src=\"./images/".$accession."_0.jpeg\"/></div><div id=\"".$accession."_images\" style=\"float:left;\"><img style=\"margin-left:3px;\" width=\"380\" src=\"./images/".$accession."_1.jpeg\"/></div>";
		}else{
			fgets($myfile);
			fgets($myfile);
			fgets($myfile);
			fgets($myfile);
			fgets($myfile);
			fgets($myfile);
			fgets($myfile);
			$counter++;
		}
	}
	fclose($myfile);	
}
?> 
