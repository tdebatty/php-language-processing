<?php

require __DIR__ . "/../vendor/autoload.php";

use webd\language\StringDistance;

$string1 = "You won 10000$";
$string2 = "You won 15500$";

// 2
echo "Edit distance : " . StringDistance::editDistance($string1, $string2) . "\n";

// 2
echo "Levenshtein : " . StringDistance::levenshtein($string1, $string2) . "\n";

// 0.96428571428571
echo "Jaro-Winkler : " . StringDistance::jaroWinkler($string1, $string2) . "\n";

// 0.98809523809524
echo "Jaro-Winkler (prefix scale = 0.2) : " . StringDistance::jaroWinkler($string1, $string2, 0.2) . "\n";

use webd\language\PorterStemmer;

// analyz
echo "analyzing => " . PorterStemmer::stem("analyzing") . "\n";

// abandon
echo "abandoned => " . PorterStemmer::stem("abandoned") . "\n";

// inclin
echo "inclination => " . PorterStemmer::stem("inclination") . "\n";

$lcs = new \webd\language\LCS($string1, $string2);
// You won 100$
echo $lcs->value() . "\n";

// 12
echo $lcs->length() . "\n";

// 4
echo $lcs->distance() . "\n";

// SpamSum, aka ssdeep, aka Context-Triggered Piecewize Hashing (CTPH):
$s = new \webd\language\SpamSum;
// 192:x+cMdRiWqk2YODjCoG4OU88/ffcQ+lsCYDIlp6+TF244htoJFUjw:krovCLA9byp6+52jhtnjw
echo $s->HashString(file_get_contents(__DIR__ . "/SpamSum.php")) . "\n";
