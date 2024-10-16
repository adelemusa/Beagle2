<?php
if (file_exists("export_beagle.txt")) {
    $lines = file("export_beagle.txt");
    $alignments = 0;
    foreach ($lines as $line) {
        if (strpos($line, '>') === 0) {
            $alignments++;
        }
    }
    
    $total = (int) file_get_contents("info");

    if ($total > 0) {
        $percentage = round(($alignments / $total) * 100, 2);
        echo $percentage;
    } else {
        echo 0; // Handle case where total might be 0 to avoid division by zero
    }
} else {
    echo -1;
}
?>
