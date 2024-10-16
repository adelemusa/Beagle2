<?php
// Define constants for limits to replace magic numbers
define('LIMIT_LOW', 88);
define('LIMIT_MEDIUM', 128);
define('LIMIT_HIGH', 500);

function trovaGruppi($struct, $ln) {
    $slim = array(
        array(0.48, 0.5555556, 0.627907, 0.7027027),
        array(0.5333333, 0.5858586, 0.629032, 0.6741573),
        array(0.5324675, 0.5733333, 0.6054054, 0.6424002)
    );

    if ($ln < LIMIT_LOW) {
        $lbin = 1;
    } elseif ($ln < LIMIT_MEDIUM) {
        $lbin = 2;
    } elseif ($ln < LIMIT_HIGH) {
        $lbin = 3;
    } else {
        return "41"; // Returning "41" directly since $lbin = 4 and $sbin = 1
    }

    if ($struct < $slim[$lbin - 1][0]) {
        $sbin = 1;
    } elseif ($struct < $slim[$lbin - 1][1]) {
        $sbin = 2;
    } elseif ($struct < $slim[$lbin - 1][2]) {
        $sbin = 3;
    } elseif ($struct < $slim[$lbin - 1][3]) {
        $sbin = 4;
    } else {
        $sbin = 5;
    }

    return $lbin . $sbin;
}

$BEAR = array(
    "j" => "LOOP", "k" => "LOOP", "l" => "LOOP", "m" => "LOOP", "n" => "LOOP",
    "o" => "LOOP", "p" => "LOOP", "q" => "LOOP", "r" => "LOOP", "s" => "LOOP",
    "t" => "LOOP", "u" => "LOOP", "v" => "LOOP", "w" => "LOOP", "x" => "LOOP",
    "y" => "LOOP", "z" => "LOOP", "^" => "LOOP", "a" => "STEM", "b" => "STEM",
    "c" => "STEM", "d" => "STEM", "e" => "STEM", "f" => "STEM", "g" => "STEM",
    "h" => "STEM", "i" => "STEM", "=" => "STEM", "A" => "STEM_branch",
    "B" => "STEM_branch", "C" => "STEM_branch", "D" => "STEM_branch",
    "E" => "STEM_branch", "F" => "STEM_branch", "G" => "STEM_branch",
    "H" => "STEM_branch", "I" => "STEM_branch", "J" => "STEM_branch",
    "?" => "LEFTINTERNALLOOP", "!" => "LEFTINTERNALLOOP", "\" => "RIGHTINTERNALLOOP"
);
?>
