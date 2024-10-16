<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BEAGLE aligner</title>
<link href="css/myStyle2.css" rel="stylesheet" type="text/css" />

</head>

<body>
<div id="wrapper">

<?php include 'header.txt';?>

<div id="main">

<h1 style="all:none;"><a name="index">Index</a></h1>
<br/>
<ol>
  <li><a style="color:blue;" href="#algorithm">Description of the algorithm</a></li>
  <li><a style="color:blue;" href="#options">Comparison Options</a></li>
  <li><a style="color:blue;" href="#inputFormat">Input format</a></li>
  <li><a style="color:blue;" href="#parameters">Parameters</a></li>
  <li><a style="color:blue;" href="#datasets">Datasets</a></li>
  <li><a style="color:blue;" href="#output">Output</a></li>
</ol>

<br />

<div style="text-align:left;"><h2 style="float:left;color: #000;text-decoration: none; cursor: hand; cursor: pointer;"><a name="algorithm">Description of the algorithm</a></h2>  <span style="float:right;color: #000;text-decoration: none; border-bottom-width: 2px; border-bottom-style: dotted; border-bottom-color: #2387ba; cursor: hand; cursor: pointer;"><a style="color:black;" href="#index">Back to the top</a></span></div>
<div style="clear:both;"></div>
<br />


<div style="text-align:justify;">
The BEAGLE (BEar Alignment Global and Local) web-server performs pairwise alignments of RNA secondary structure. The method exploits a new encoding for RNA secondary structure (BEAR) and a substitution matrix for RNA structural elements (MBR) (Mattei et <i>al.,</i> 2014). The BEAR encoding allows to include structural information within a string of characters where each character of the encoding stores the information about the type and length of the secondary structure elements the nucleotide belongs to (Fig. 1).<br/>
<p style="text-align:center;"><img style="margin:10px,10px,0px,0px;width:621px;" src="images/3in1.jpg"/></p>
<span style="font-size:12px;"><strong>Figure 1.</strong> The BEAR encoding. (A) The BEAR structural alphabet. Different sets of characters are associated with the different RNA basic structures(loop, internal loop, stem and bulge on the right side of a stem, and bulge on the left side, denoted here as L, I, S, BL and BR, respectively), with different characters used for basic structures of different length. (B) RNA hairpin with the constituent substructures (loop, stem, bulges and internal loop) highlighted in different colors. On the top right, the BEAR characters corresponding to each substructure, shown with the same colors. On the bottom right, the hairpin RNA sequence is shown associated with its dot-bracket and its BEAR secondary structure descriptions. (C) Conversion into BEAR of an RNA secondary structure. An RNA secondary structure, extracted from Rfam, is shown, containing four non-branching structures depicted in boxes. The resulting BEAR conversion of the non-branching and branching structures is shown in blue below the secondary structure. A ‘:’ character is assigned to the remaining nucleotides that do not belong to non-branching or branching structures (reported in black).
</span>
<br/><br/>Transition rates between secondary structure elements were computed on a set of evolutionally related BEAR-encoded RNAs (Fig.2).<br/>
<p style="text-align:center;"><img style="margin:10px,10px,0px,0px;width:421px;" src="images/Cover.png"/></p> 
<span style="font-size:12px;"><strong>Figure 2.</strong> Graphical representation of the MBR. This figure shows a subset of rows/columns of the MBR matrix, using a color-coding to show substitution rate patterns: color scale represents log-odds scores from lower (blue) to higher (red). Rows and columns are elements of RNA secondary structure of different length and every cell stores the log-odds value for the substitution of one element with another element. The cells in the principal diagonal always have the highest value in the respective row and column. Substitutions between elements belonging to the same group (i.e. stems, loops and interior loops) display higher log-odds values than substitutions between elements belonging to different groups. The ‘. . . ’ notation indicates that some rows/columns were omitted from the graphical matrix representation.
</span><br/><br/>
The BEAR encoding uses an alphabet of 83 characters so the size of the MBR is 83x83. The total number of possible pairs is 3486 (83*(83+1)/2) among which 221 has a score higher than 1, corresponding to pairs occurring more than expected. The plot below shows the distribution of the score in the matrix (Fig. 3).
<p style="text-align:center;"><img style="margin:10px,10px,0px,0px;width:421px;" src="images/mbr_scores.png"/></p>
<br/>
The BEAGLE method implements a modified version of the  the Needleman-Wunsch algorithm for global alignment and the Smith-Waterman algorithm for local alignment, for the comparison of BEAR-encoded RNA secondary structures, using the MBR (or any other user-provided substitution matrix for BEAR characters) to guide the alignment.
</div>

<br />
<br />

<div style="text-align:left;"><h2 style="float:left;color: #000;text-decoration: none; cursor: hand; cursor: pointer;"><a name="options">Comparison options</a></h2>  <span style="float:right;color: #000;text-decoration: none; border-bottom-width: 2px; border-bottom-style: dotted; border-bottom-color: #2387ba; cursor: hand; cursor: pointer;"><a style="color:black;" href="#index">Back to the top</a></span></div>

<div style="clear:both;"></div>

BEAGLE offers three kinds of comparisons:
<ol>
  <li>One set</li>
  This option accepts one set of RNAs as input and the server will perform all the possible pairwise alignments among those RNAs. Maximum 300 RNAs accepted in input. The input sequences must be supplied using the textarea.<br/><br/>
  <li>Two sets</li>
  This option accepts two sets of RNAs, namely the query and target set. The sequences in the <i>query set</i> will be aligned to the sequences in the <i>target set</i>. For more information about the comparison modes available see the <a style="color:blue;" href="#parameters">parameters section</a>. The input sequences must be supplied using the two textareas.
  <br/><br/>
  <li>Search</li>
  	Using this option, an input RNA will be compared to one of the five pre-compiled RNA datasets, namely human lncRNAs, mouse lncRNAs, human 3' UTR, mouse 3'UTR and structured Rfam. For more information about the available datasets see the <a style="color:blue;" href="#datasets">datasets section</a>.
</ol>
<br />
<br />



<div style="text-align:center;"><h2 style="float:left;color: #000;text-decoration: none; cursor: hand; cursor: pointer;"><a name="inputFormat">Input format</a></h2>  <span style="float:right;color: #000;text-decoration: none; border-bottom-width: 2px; border-bottom-style: dotted; border-bottom-color: #2387ba; cursor: hand; cursor: pointer;"><a style="color:black;" href="#index">Back to the top</a></span></div>

<div style="clear:both;"></div>
<br />
All the comparison options required the input sequences to be supplied using the textarea in the home page.
<br/><br/>
The input sequences are accepted in FASTA format:<br/>
-The line containing the name and/or the description of the sequence starts with a ">";<br/>
-The words following the ">" are interpreted as the RNA id;<br/>
-The following line reports the RNA nucleotide sequence;
-The subsequent line characters are interpreted as secondary structure information (Optional)<br/>
<br/>
or<br/>
<br/>
FASTB format:<br/>
-The line containing the name and/or the description of the sequence starts with a ">";<br/>
-The words following the ">" are interpreted as the RNA id;<br/>
- The following line reports the RNA nucleotide sequence;<br/>
-The subsequent line characters are interpreted as secondary structure information in the BEAR alphabet.<br/>
<br/>

The IUPAC notation is accepted for nucleotides (case-insensitive).<br/>
The secondary structure must be supplied using dot-bracket notation; only '( . )' characters will be accepted by the program.<br/>
<br/>

Example of a well formatted input file:
<pre>
>X06054.1/711637
GGGCCCGUCGUCUAGCCUGGUUAGGACGCUGCCCUGACGCGGCAGAAAUCCUGGGUUCAAGUCCCAGCGGGCCCA
</pre>
In this case the secondary strucure for the sequence will be computed on the fly using RNAfold (Vienna package), with the minimum free energy prediction method.<br/>
<br/>
or
<br/>
<pre>
>X06054.1/711637
GGGCCCGUCGUCUAGCCUGGUUAGGACGCUGCCCUGACGCGGCAGAAAUCCUGGGUUCAAGUCCCAGCGGGCCCA
(((((((..((((..........)))).(((((.......))))).....(((((.......)))))))))))).
</pre>

or
<br/>
<pre>
>X06054.1/711637
GGGCCCGUCGUCUAGCCUGGUUAGGACGCUGCCCUGACGCGGCAGAAAUCCUGGGUUCAAGUCCCAGCGGGCCCA
(((((((..((((..........)))).(((((.......))))).....(((((.......)))))))))))).
GGGGGGG::ddddssssssssssdddd:eeeeepppppppeeeee:::::eeeeepppppppeeeeeGGGGGGG:
</pre>

The input may contain many sequences e.g. :<br/>
<pre>
>X06054.1/711637
GGGCCCGUCGUCUAGCCUGGUUAGGACGCUGCCCUGACGCGGCAGAAAUCCUGGGUUCAAGUCCCAGCGGGCCCA
(((((((..((((..........)))).(((((.......))))).....(((((.......)))))))))))).
GGGGGGG::ddddssssssssssdddd:eeeeepppppppeeeee:::::eeeeepppppppeeeeeGGGGGGG:
>AP000063.1/5917959095
GCGGGGGUGCCCGAGCCUGGCCAAAGGGGUCGGGCUCAGGACCCGAUGGCGUAGGCCUGCGUGGGUUCAAAUCCCACCCCCCGCA
(((((((..(((.............))).(((((.......)))))..............(((((.......)))))))))))).
>AP000989.1/7327973354
GCGGCCGUCGUCUAGUCUGGAUUAGGACGCUGGCCUUCCAAGCCAGUAAUCCCGGGUUCAAAUCCCGGCGGCCGCA
(((((((..((((...........)))).(((((.......))))).....(((((.......)))))))))))).
>AE006696.1/291218
GCCGCCGUAGCUCAGCCCGGGAGAGCGCCCGGCUGAAGACCGGGUUGUCCGGGGUUCAAGUCCCCGCGGCGGCA
(((((((..((((.........)))).(((((.......))))).....(((((.......)))))))))))).

</pre>

<div style="text-align:left;"><h2 style="float:left;color: #000;text-decoration: none; cursor: hand; cursor: pointer;"><a name="parameters">Parameters</a></h2>  <span style="float:right;color: #000;text-decoration: none; border-bottom-width: 2px; border-bottom-style: dotted; border-bottom-color: #2387ba; cursor: hand; cursor: pointer;"><a style="color:black;" href="#index">Back to the top</a></span></div>

<div style="clear:both;"></div>
<br />

<strong>GAP INSERTION</strong><br/>
Cost of starting a gap in the alignment<br/>
<br/>

<strong>GAP EXTENSION</strong><br/>
Cost of extending an alignment gap.<br/>
<br/>
<strong>SEQUENCE BONUS</strong><br/>
Extra score for aligning two identical nucleotides.<br/>
<br/>

<strong>GLOBAL/LOCAL</strong><br/>
Allows the user to choose between global and local alignment<br/>
	<br/>
	<strong>DATASETS (only for "Search" comparison option)</strong><br/>
Allows the user to choose one of the pre-compiled datasets. Graphical output will not be available for this option.<br/>
	<br/>
<strong>COMPARISON METHOD (only for "Two sets" comparison option)</strong><br/>
In the "<i>All vs. All</i>" mode, each RNA in the query set will be aligned with each RNA in the target set producing n x m alignments where n is the cardinality of the query set and m is the cardinality of the target set.<br/>


In the "<i>One to One</i>" mode, the first RNA in the query set will be aligned with the first RNA in the target, the second RNA in the query set with the second of the target set and so on and so forth. The cardinalities of the two sets must be equal. 

In both cases max 10 000 alignments are allowed.

<br />
<br />

<div style="text-align:left;"><h2 style="float:left;color: #000;text-decoration: none; cursor: hand; cursor: pointer;"><a name="datasets">Datasets</a></h2>  <span style="float:right;color: #000;text-decoration: none; border-bottom-width: 2px; border-bottom-style: dotted; border-bottom-color: #2387ba; cursor: hand; cursor: pointer;"><a style="color:black;" href="#index">Back to the top</a></span></div>

<div style="clear:both;"></div>
<ol>
  <li><strong>Human lncRNAs</strong></li>
	This dataset consists in all the Human lncRNAs smaller than 10000 nucleotides, folded using RNAfold (minimum free energy method) program from <a target="_blank" style="color:blue;" href="http://www.tbi.univie.ac.at/RNA/">Vienna package</a>.
	 The lncRNA were retrieved from the <a target="_blank" style="color:blue;" href="http://www.gencodegenes.org/">GENCODE website</a>; release 22 (GRCh38.p2). <br/><br/>
  <li><strong>Mouse lncRNAs</strong></li>
  This dataset consists in all the Mouse lncRNAs smaller than 10000 nucleotides, folded using RNAfold (minimum free energy method) program from <a target="_blank" style="color:blue;" href="http://www.tbi.univie.ac.at/RNA/">Vienna package</a>.
	The lncRNA were retrieved from the <a target="_blank" style="color:blue;" href="http://www.gencodegenes.org/">GENCODE website</a>; release M4 (GRCm38.p3).
  <br/><br/>
  <li><strong>Human 3' UTR</strong></li>
  	This dataset consists in all the Human 3' UTR.
	 The sequences along with their secondary structures were downloaded using the "Table Browser" tool from <a target="_blank" style="color:blue;" href="https://genome.ucsc.edu/cgi-bin/hgTables?command=start">UCSC</a>.
	 Assembly:GRCh38/hg38; Track:UCSC Genes; Table:foldUTR3.<br/><br/>
	<li><strong>Mouse 3' UTR</strong></li>
  	This dataset consists in all the Mouse 3' UTR.
	 The sequences along with their secondary structures were downloaded using the "Table Browser" tool from <a target="_blank" style="color:blue;" href="https://genome.ucsc.edu/cgi-bin/hgTables?command=start">UCSC</a>.
	 Assembly:GRCm38/mm10; Track:UCSC Genes; Table:foldUTR3.<br/><br/>
  	<li><strong>Structured Rfam</strong></li>
  	This dataset consists in all the RNAs from <a target="_blank" style="color:blue;" href="http://rfam.xfam.org/">Rfam</a> (v.11) belonging to a family annotated with a consensus secondary structure. We used RNAfold (minimum free energy method) to fold all the Rfam RNAs. In order to improve the prediction accuracy, for each RNA we used as structural constraints for the folding the consensus secondary structure of its belonging family as described previously (Mattei et al., 2014).  	
  	<br/><br/>
</ol>

<br />
<br />

<div style="text-align:left;"><h2 style="float:left;color: #000;text-decoration: none; cursor: hand; cursor: pointer;"><a name="output">Output</a></h2>  <span style="float:right;color: #000;text-decoration: none; border-bottom-width: 2px; border-bottom-style: dotted; border-bottom-color: #2387ba; cursor: hand; cursor: pointer;"><a  style="color:black;" href="#index">Back to the top</a></span></div>
<div style="clear:both;"></div>
<br />
  The results page reports a table containing all the computed pairwise alignments. Each row of the table contains the two input RNA ids and alignment statistics such as sequence and structural identity percentages and the structural similarity percentage.
  Moreover, also two measures for the statistical significance of the alignments are reported: p-value and z-score.
  Results can be sorted according to one of the previous parameters by clicking the selected column header (one of the parameters inside the red box in the figure below).
 <p> <img style="margin:8px,8px,0px,0px; width:823px;" src="images/documentationTableResults.png"/></p>
  By clicking on the "plus image" (blue bloxes in the figure above), the sequence and structure color-coded alignment for that specific pair of RNAs appears along with a color-coded graphical representation of the RNA secondary structures. The color code helps the user to identify ungapped structural regions between the two RNAs (see figure below).
    <p><img style="margin:10px,10px,0px,0px;" src="images/documentationTableResults2.png"/></p>

The purpose of the colors is to help the user to identify common un-gapped sub-structures between the RNAs. Different colors are associated to different sub-structures. These colors are not to be intended as conservation measures.

  <br/><br/>Description of the alignment scores:<br /><br />
  
  The <strong>Str Id</strong> (structural identity percentage) is computed as the fraction of paired bases encoded with an identical BEAR character.<br />
  The <strong>Str Sim</strong> (structural similarity percentage) is computed as the fraction of paired bases encoded with two different BEAR characters belonging to the same RNA structural element (e.g. two different characters ancoding for stems with different lengths).<br />
  The <strong>p-value and z-score</strong> are computed using as background the distribution of the scores obtained aligning unrelated RNA sequences(as detailed in the Supplementary Material of Mattei <em>et al.</em>, sumbitted). We suggest to consider the z-score as the reference statistic measure and consider as significant all the alignments having a z-score higher than 3.<br />

  <br />
  By clicking on <i> export results</i>, it is possible to download all the pairwise alignments.
  The exported file will be formatted in a FASTA-like format as follow:<br /><br />
  
  -The first line containing starts with a ">" followed by the name of the first sequence, the name of the second and the alignment scores divided by '|'<br />
  -Next line represents the aligned nucleotide sequence of the first RNA<br />
  -Next line represents the aligned secondary structure of the first RNA<br />
  -Next line represents the aligned BEAR characters of the first RNA<br />
  -Next line represents the aligned nucleotide sequence of the second RNA<br />
  -Next line represents the aligned secondary structure of the second RNA<br />
  -Next line represents the aligned BEAR characters of the second RNA<br />
</p>
<p>Example:</p>
<i>Input1:</i>
<pre>
>X06054
GGGCCCGUCGUCUAGCCUGGUUAGGACGCUGCCCUGACGCGGCAGAA
(((((((..((((..........)))).(((((.......)))))..
</pre>
<i>Input2:</i>
<pre>
>AP000063
GCGGGGGUGCCCGAGCCUGGCCAAAGGGGUCGGGCUCAGGACCCGAU
(((((((..(((.............))).(((((.......))))).
</pre>
<i>Output:</i>
<pre>
>X06054|AP000063|NW:83.56|SeqIdentity:44.71|StrIdentity:67.06|StrSimilarity:84.71|P-value:0.007|Z-score:2.80855
# the name of the first sequence, the name of the second, the alignment score, the sequence identity
 percentage, the structural identity percentage, the structural similarity percentage, P-value,
  Z-score, divided by '|'
GGGCCCGUCGUCUAGCCUGG-UUAGGACGCUGCCCUGACGCGGCAGAA #primary sequence first sequence
(((((((..((((.......-...)))).(((((.......))))).. #secondary structure first sequence
GGGGGGG::ddddsssssss-sssdddd:eeeeepppppppeeeee:: #BEAR encoding first sequence
GCGGGGGUGCCCGAGCCUGGCCAAAGGGGUCGGGCUCAGGACCCGAU  #primary sequence second sequence
(((((((..(((.............))).(((((.......))))).  #secondary structure second sequence
GGGGGGG::cccvvvvvvvvvvvvvccc:eeeeepppppppeeeee:  #BEAR encoding second sequence
</pre>

</div>
<?php include 'footer.txt';?>
</div>
</body>
</html>
