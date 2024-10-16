<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>BEAGLE aligner</title>
<link rel="stylesheet" href="../../tablesorter-master/css/theme.dropbox.css">
<link href="../../css/myStyle.css" rel="stylesheet" type="text/css" />
<script src="../../tablesorter-master/jquery-v1.1.js"></script>
<script src="../../tablesorter-master/jquery.tablesorter.js"></script>
<script src="../../tablesorter-master/jquery.tablesorter.pager.js"></script>
<script src="../../tablesorter-master/jquery.tablesorter.widgets.js"></script>
<script>
var interval1;
var interval2;

async function checkCompletion() {
    try {
        const response = await fetch("getPercentage.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: new URLSearchParams({ "path": "your-path-here" }) // Replace with actual path
        });

        if (response.ok) {
            const result = await response.text();
            if (result == 1) {
                clearInterval(interval1);
                document.getElementById("secondstep").style.display = "block";
                document.getElementById("current").innerHTML = "";
                interval2 = setInterval(() => checkDone(), 1000);
            } else {
                document.getElementById("current").innerHTML = `<center>Analysis not yet ready. Current percentage ${Math.round(result * 100)}</center>`;
            }
        } else {
            console.error("Failed to check completion.");
        }
    } catch (error) {
        console.error("Error:", error);
    }
}

async function checkDone() {
    // Implement the check done logic here
}
</script>
</head>
<body>
<div id="wrapper">
<!-- Your content goes here -->
</div>
</body>
</html>
