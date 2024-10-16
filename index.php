<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BEAGLE aligner</title>
<link href="css/myStyle.css" rel="stylesheet" type="text/css" />
<script>
function loadTextArea(){
//document.getElementById("text1").innerHTML="<textarea style=\"width:100%; padding:0;margin-top:2px;font-family:\"Courier New\", Courier,Consolas,Monaco,Lucida Console,Liberation Mono,DejaVu Sans Mono,Bitstream Vera Sans Mono, monospace;\" id=\"input2\" name=\"input2\" rows=\"7\"></textarea>";
//document.getElementById("option1").innerHTML="";
//document.getElementById("expand2").checked=false;
}

function loadSelect(){
	//document.getElementById("expand1").checked=false;
	//document.getElementById("text1").innerHTML="";
	document.getElementById("option1").innerHTML ="<select id=\"dataset\" name=\"dataset\"  style=\"width: 300px; text-align:left;\"><option value=\"GSE146952_vivo\">GSE146952 vivo</option><option value=\"GSE74353_vivo\">GSE74353 vivo</option><option value=\"rfam\">Structured Rfam</option><option value=\"utrHS\">3UTR - HomoSapiens</option><option value=\"utrMM\">3UTR - Mus Musculus</option><option value=\"lncrnaHS\">lncRNA - Homo Sapiens</options><option value=\"lncrnaMM\">lncRNA - Mus Musculus</option><option value=\"nuovo_db\">Nuovo_DB</option></select>";
}

function clearAll(){
	document.getElementById("input1"
).value="";
	document.getElementById("input2").value="";
}
function loadExample3(){
	option3();
	document.getElementById("search").checked=true;
	loadTextArea();
//	document.getElementById("input1").value=">AE005314.1/4287-4216\nGGUGAGGUGUCCGAGUGGCUGAAGGAGCACGCCUGGAAAGUGUGUAUACGGCAACGUAUCGGGGGUUCGAAU\n..((((...(((...........))).(((((.......)))))....(.............)..))))...\n::DDDD:::ccctttttttttttccc:eeeeepppppppeeeee::::avvvvvvvvvvvvva::DDDD:::\n>AP000989.1/73279-73354\nGCGGCCGUCGUCUAGUCUGGAUUAGGACGCUGGCCUUCCAAGCCAGUAAUCCCGGGUUCAAAUCCCGGCGGCCGCA\n(((((((..((((...........)))).(((((.......))))).....(((((.......)))))))))))).";
	document.getElementById("input1").value=">AE005314.1/4287-4216\nGGUGAGGUGUCCGAGUGGCUGAAGGAGCACGCCUGGAAAGUGUGUAUACGGCAACGUAUCGGGGGUUCGAAU\n..((((...(((...........))).(((((.......)))))....(.............)..))))...\n::DDDD:::ccctttttttttttccc:eeeeepppppppeeeee::::avvvvvvvvvvvvva::DDDD:::\n";
	document.getElementById("input2").value=">X00916.1/823-889\nGCCUCGGUGGCUCAGCCUGGUAGAGCGCCUGACUUGUAAUCAGGUGGUCGGGGGUUCGAAUCCCCCC\n......(..((((.........)))).(((((.......))))).....(((((.......))))))\n::::::A::ddddrrrrrrrrrdddd:eeeeepppppppeeeee:::::eeeeepppppppeeeeeA\n>AE006696.1/291-218\nGCCGCCGUAGCUCAGCCCGGGAGAGCGCCCGGCUGAAGACCGGGUUGUCCGGGGUUCAAGUCCCCGCGGCGGCA\n(((((((..((((.........)))).(((((.......))))).....(((((.......)))))))))))).\nGGGGGGG::ddddrrrrrrrrrdddd:eeeeepppppppeeeee:::::eeeeepppppppeeeeeGGGGGGG:\n>AP000989.1/73279-73354\nGCGGCCGUCGUCUAGUCUGGAUUAGGACGCUGGCCUUCCAAGCCAGUAAUCCCGGGUUCAAAUCCCGGCGGCCGCA\n(((((((..((((...........)))).(((((.......))))).....(((((.......)))))))))))).\n";
}
function loadExample2(){
	option2();
	document.getElementById("twosets").checked=true;
	loadTextArea();
	document.getElementById("input1").value=">AE005314.1/4287-4216\nGGUGAGGUGUCCGAGUGGCUGAAGGAGCACGCCUGGAAAGUGUGUAUACGGCAACGUAUCGGGGGUUCGAAU\n..((((...(((...........))).(((((.......)))))....(.............)..))))...\n::DDDD:::ccctttttttttttccc:eeeeepppppppeeeee::::avvvvvvvvvvvvva::DDDD:::\n>AP000989.1/73279-73354\nGCGGCCGUCGUCUAGUCUGGAUUAGGACGCUGGCCUUCCAAGCCAGUAAUCCCGGGUUCAAAUCCCGGCGGCCGCA\n(((((((..((((...........)))).(((((.......))))).....(((((.......)))))))))))).";
	document.getElementById("input2").value=">X00916.1/823-889\nGCCUCGGUGGCUCAGCCUGGUAGAGCGCCUGACUUGUAAUCAGGUGGUCGGGGGUUCGAAUCCCCCC\n......(..((((.........)))).(((((.......))))).....(((((.......))))))\n::::::A::ddddrrrrrrrrrdddd:eeeeepppppppeeeee:::::eeeeepppppppeeeeeA\n>AE006696.1/291-218\nGCCGCCGUAGCUCAGCCCGGGAGAGCGCCCGGCUGAAGACCGGGUUGUCCGGGGUUCAAGUCCCCGCGGCGGCA\n(((((((..((((.........)))).(((((.......))))).....(((((.......)))))))))))).\nGGGGGGG::ddddrrrrrrrrrdddd:eeeeepppppppeeeee:::::eeeeepppppppeeeeeGGGGGGG:\n>AP000989.1/73279-73354\nGCGGCCGUCGUCUAGUCUGGAUUAGGACGCUGGCCUUCCAAGCCAGUAAUCCCGGGUUCAAAUCCCGGCGGCCGCA\n(((((((..((((...........)))).(((((.......))))).....(((((.......)))))))))))).\n";
}
function loadExample1(){
	option1();
	document.getElementById("oneset").checked=true;
	loadTextArea();
	document.getElementById("input1").value=">AE005314.1/4287-4216\nGGUGAGGUGUCCGAGUGGCUGAAGGAGCACGCCUGGAAAGUGUGUAUACGGCAACGUAUCGGGGGUUCGAAU\n..((((...(((...........))).(((((.......)))))....(.............)..))))...\n::DDDD:::ccctttttttttttccc:eeeeepppppppeeeee::::avvvvvvvvvvvvva::DDDD:::\n>AP000989.1/73279-73354\nGCGGCCGUCGUCUAGUCUGGAUUAGGACGCUGGCCUUCCAAGCCAGUAAUCCCGGGUUCAAAUCCCGGCGGCCGCA\n(((((((..((((...........)))).(((((.......))))).....(((((.......)))))))))))).";
	document.getElementById("input2").value=">X00916.1/823-889\nGCCUCGGUGGCUCAGCCUGGUAGAGCGCCUGACUUGUAAUCAGGUGGUCGGGGGUUCGAAUCCCCCC\n......(..((((.........)))).(((((.......))))).....(((((.......))))))\n::::::A::ddddrrrrrrrrrdddd:eeeeepppppppeeeee:::::eeeeepppppppeeeeeA\n>AE006696.1/291-218\nGCCGCCGUAGCUCAGCCCGGGAGAGCGCCCGGCUGAAGACCGGGUUGUCCGGGGUUCAAGUCCCCGCGGCGGCA\n(((((((..((((.........)))).(((((.......))))).....(((((.......)))))))))))).\nGGGGGGG::ddddrrrrrrrrrdddd:eeeeepppppppeeeee:::::eeeeepppppppeeeeeGGGGGGG:\n>AP000989.1/73279-73354\nGCGGCCGUCGUCUAGUCUGGAUUAGGACGCUGGCCUUCCAAGCCAGUAAUCCCGGGUUCAAAUCCCGGCGGCCGCA\n(((((((..((((...........)))).(((((.......))))).....(((((.......)))))))))))).\n";
}

function option1() {
	document.getElementById("secondset").style.display="none";
	document.getElementById("referenceset").style.display="none";
	document.getElementById("comparisonmethod").style.display="none";
	document.getElementById("desc").innerHTML="It compares all the sequences in one RNA set.";
	clearAll();
}
function option2() {
	document.getElementById("secondset").style.display="block";
	document.getElementById("referenceset").style.display="none";
	document.getElementById("comparisonmethod").style.display="block";
        document.getElementById("desc").innerHTML="It compares the queried RNA set against the target set.<br>Max. 10 000 aligments allowed. \"Pairwise\" requires same length sets.";
	clearAll();
}
function option3() {
	document.getElementById("secondset").style.display="none";
	document.getElementById("referenceset").style.display="block";
	document.getElementById("comparisonmethod").style.display="none";
        document.getElementById("desc").innerHTML="It compares the input RNA against one of the precompiled datasets.<br>No graphic output. Results will be available to download.";
	clearAll();
}




</script>

</head>

<body>
<div id="wrapper">
<?php include 'header.txt';?>
    <!--Qui finisce il menu -->
  <div id="main">
      <h1>Pairwise RNA Structural Alignment</h1><!--
/*//	<b style="color:red;">IMPORTANT:</b><br/>	<pre>Due to hacker attack, the server was moved to this temporary address.//Users are kindly requested to be patient, and report possible problems.//Everything should be fixed in few hours</pre>*/
--><br>
      <b>BEAGLE</b> is a new alignment tool exploiting a new encoding for RNA secondary structure and  a substitution matrix of RNA structural elements to  perform global and local alignments. <span style="color:red;font-size:14px;">Max 10 000 alignments allowed</span><br /><p>Have a quick question or suggestions?<a href="/contacts.php" style="color:#2387ba"> Contact us</a></p>
          <p>Use BEAGLE 2.0 in your research? <a href="/How_to_cite_us.php" style="color:#2387ba"> Please cite us</a></p>
          <p>Thank you for using BEAGLE 2.0!</p>
          
      <br />
      <form style="text-align:center;" action="aligning.php" method="POST">
<hr style="margin:15px;">
	<center><b>1) Select Analysis Type</b>
	<div><br>
	<input id="oneset" type="radio" value="oneset" name="analysis" onclick="option1()" checked>One set</input>
	<input id="twosets" type="radio" value="twosets" name="analysis" onclick="option2()">Two sets</input>
	<input id="search" type="radio" value="search" name="analysis" onclick="option3()">Search<br><br></input>
	<div style="background-color:#49a2cfad;width:100%;height:60px;border-radius: 25px;
"><br><b>Description</b>: <span id="desc">It processes all sequences in one starting set<span></div>
	</div>
	</center>
<br>
    <br>
<span style="float:left;"><b>Load an example: </b>&nbsp;</span><span style="float:left;color: #000;text-decoration: none; border-bottom-width: 2px; border-bottom-style: dotted; border-bottom-color: #2387ba; cursor: hand; cursor: pointer;"onclick="loadExample1()"><b> One Set</b></span><span style="float:left;margin-left:4px;">-</span>
<span style="margin-left:4px;float:left;color: #000;text-decoration: none; border-bottom-width: 2px; border-bottom-style: dotted; border-bottom-color: #2387ba; cursor: hand; cursor: pointer;"onclick="loadExample2()"><b>Two sets</b></span><span style="float:left;margin-left:4px;">-</span>
<span style="margin-left:4px;float:left;color: #000;text-decoration: none; border-bottom-width: 2px; border-bottom-style: dotted; border-bottom-color: #2387ba; cursor: hand; cursor: pointer;"onclick="loadExample3()"><b>Search</b></span>
<span style="float:right;color: #000;text-decoration: none; border-bottom-width: 2px; border-bottom-style: dotted; border-bottom-color: #2387ba; cursor: hand; cursor: pointer;"onclick="clearAll()"><b>Clear All</b></span><br>


<hr style="margin:15px;">
    
 
<center><b>2) Input</b><br><br>

<div style="text-align:left;margin-bottom:2px">Enter or paste the QUERY set of RNA sequences in a <a style="color: #000;text-decoration: none; border-bottom-width: 2px; border-bottom-style: dotted; border-bottom-color: #2387ba;" href="/documentation.php#inputFormat" target="_blank">supported format</a> :</div><br>
<textarea style="width:100%; padding:0;margin-top:2px; font-family: "Courier New", Courier, Consolas,Monaco,Lucida Console,Liberation Mono,DejaVu Sans Mono,Bitstream Vera Sans Mono,monospace;" id="input1" name="input1" rows="7"></textarea>
        <br />
<div id="secondset" style="display:none">
<div style="text-align:left;">Enter or paste the TARGET set of RNA sequences in a <a style="color: #000;text-decoration: none; border-bottom-width: 2px; border-bottom-style: dotted; border-bottom-color: #2387ba;" href="/beagle/documentation.php#inputFormat" target="_blank">supported format</a> :</div>
<textarea style="width:100%; padding:0;margin-top:2px;font-family:"Courier New", Courier,Consolas,Monaco,Lucida Console,Liberation Mono,DejaVu Sans Mono,Bitstream Vera Sans Mono, monospace;" id="input2" name="input2" rows="7"></textarea>
</div>
<hr style="margin:15px;">
<b>3) Parameters</b>
<br>
    <br>
<div id="referenceset" style="display:none">
Reference Set:
<select id="dataset" name="dataset"  style="width: 300px; text-align:left;">
<option value="rfam">Structured Rfam</option>
<option value="utrHS">3UTR - HomoSapiens</option>
<option value="utrMM">3UTR - Mus Musculus</option>
<option value="lncrnaHS">lncRNA - Homo Sapiens</options>
<option value="lncrnaMM">lncRNA - Mus Musculus</option>
<option value="GSE146952_vivo">	icSHAPE-MaP human small-RNA(HEK293T) Luo Q. et al.2021</option>
<option value="GSE74353_vivo">Human RNAs (HEK293T) Lu Z, et al. 2016</option>
<option value="GSE145805_HeLa">Human RNAs  (HeLa),Sun L. et al., 2021</option>
<option value="GSE145805_HEK293"> Human RNAs (HEK293), Sun L. et al., 2021</option>
<option value="GSE145805_H9hESC">Human RNAs (H9hESC),Sun L. et al., 2021</option>
<option value="NONCODEv6_zebrafish">NONCODEv6_zebrafish</option>
<option value="NONCODEv6_cow">NONCODEv6_cow</option>
<option value="NONCODEv6_chimp">NONCODEv6_chimp</option>
<option value="NONCODEv6_gorilla">NONCODEv6_gorilla</option>
<option value="NONCODEv6_yeast">NONCODEv6_yeast</option>
<option value="NONCODEv6_fruitfly">NONCODEv6_fruitfly</option>
<option value="NONCODEv6_yeast">NONCODEv6_yeast</option>
</select>
</div>
<table cellpadding="10" align="center">
          <tbody><tr>
            <td width="170">GAP INSERTION</td>
            <td align="center" width="103"><input type="text" value="2.0" name="gapInsertion" style="width:30px; text-align:center;"></td>
            <td width="205">SEQUENCE BONUS</td>
            <td align="center" width="96"><input type="text" value="0.6" name="sequenceBonus" style="width:30px; text-align:center;"></td>
          </tr>
          <tr>
            <td>GAP EXTENSION</td>
            <td align="center"><input type="text" value="0.7" name="gapExtension" style="width:30px; text-align:center;"></td>
            <td>GLOBAL/LOCAL</td>
            <td align="center"><select name="globalLocal">
              <option value="Global">Global</option>
              <option value="Local">Local</option>
            </select></td>
          </tr>
	<tbody>
</table>
          <div id="comparisonmethod" style="display:none">
            COMPARISON METHOD <br>
            <select name="comparison">
              <option selected="selected" value="allVsAll">All Vs. All</option>
              <option value="pairwise">One to One</option>
            </select>
	</div>



<hr style="margin:15px;">



        <input style="font-size:16px; text-align:center;" type="submit" value="Align" />
      </form><br /><br>
    
  </div>
     <?php include 'footer.txt';?>
</div>
</body>
</html>
