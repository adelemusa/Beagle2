<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>BEAGLE aligner</title>
<link href="css/myStyle.css" rel="stylesheet" type="text/css" />
<script>
function loadTextArea() {
    // Logic to load textarea dynamically (if required)
}

function loadSelect() {
    document.getElementById("option1").innerHTML = `
        <select id="dataset" name="dataset" style="width: 300px; text-align:left;">
            <option value="rfam">Structured Rfam</option>
            <option value="utrHS">3UTR - HomoSapiens</option>
            <option value="utrMM">3UTR - Mus Musculus</option>
            <option value="lncrnaHS">lncRNA - Homo Sapiens</option>
            <option value="lncrnaMM">lncRNA - Mus Musculus</option>
            <option value="nuovo_db">Nuovo_DB</option>
        </select>`;
}

function clearAll() {
    document.getElementById("input1").value = "";
    document.getElementById("input2").value = "";
}

function loadExample3() {
    // Logic to load example 3 (if required)
}
</script>
</head>
<body>
<div id="wrapper">
<!-- Content goes here -->
</div>
</body>
</html>
