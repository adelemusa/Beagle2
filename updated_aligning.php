<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>BEAGLE aligner</title>
<link href="css/myStyle.css" rel="stylesheet" type="text/css" />
<script>
async function checkCompletion(path) {
    try {
        const response = await fetch("getPercentage.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: new URLSearchParams({ "path": path })
        });

        if (response.ok) {
            const percentage = await response.text();
            if (percentage == 1) {
                clearInterval(progress);
                document.getElementById("percentage").innerHTML = 100;
                setTimeout(() => {
                    window.location.replace("http://beagle.bio.uniroma2.it/" + path + "/results.php");
                }, 1000);
            } else {
                document.getElementById("percentage").innerHTML = Math.round(percentage * 100);
            }
        } else {
            console.error("Failed to fetch the percentage.");
        }
    } catch (error) {
        console.error("Error:", error);
    }
}
</script>
</head>
<body>
<div id="wrapper">
<?php
function generateFolder() {
    $inputHash = date('dMy_G_i_s');
    $rand = mt_rand();
    $today = date('dMy');
    $code = hash('md5', "{$inputHash}{$rand}");
    return "results/{$today}_{$code}";
}

function checkNucleotides($seq) {
    $seq1 = strtolower($seq);
    return preg_match("/^[acgturyswkmbdhvn]+$/", $seq1);
}

function checkBrackets($str) {
    return preg_match("/^[.()]+$/", $str);
}
?>
</div>
</body>
</html>
