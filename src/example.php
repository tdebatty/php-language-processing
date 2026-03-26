<?php

require __DIR__ . "/../vendor/autoload.php";

// ------------ n-gram string similarity

use webd\language\StringSimilarity\DiceCoefficient;
use webd\language\StringSimilarity\JaccardSimilarity;


$dice = new DiceCoefficient(2);
$jaccard = new JaccardSimilarity(2);

$a = "context";
$b = "contact";

// 0.5
echo $dice->similarity($a, $b) . PHP_EOL;

// context : ["co", "on", "nt", "te", "ex", "xt"]
// contact : ["co", "on", "nt", "ta", "ac", "ct"]
// jacccard similarity : 3 / 9
// 0.33333
echo $jaccard->similarity($a, $b) . PHP_EOL;

// ------------ string distance

use webd\language\StringDistance;

$string1 = "You won 10000$";
$string2 = "You won 15500$";

// 2
echo "Edit distance : " . StringDistance::editDistance($string1, $string2) . PHP_EOL;

// 2
echo "Levenshtein : " . StringDistance::levenshtein($string1, $string2) . PHP_EOL;


$lcs = new \webd\language\LCS($string1, $string2);
// You won 100$
echo $lcs->value() . PHP_EOL;

// 12
echo $lcs->length() . PHP_EOL;

// 4
echo $lcs->distance() . PHP_EOL;

// -------------- jaro-winkler string similarity

// 0.96428571428571
echo "Jaro-Winkler : " . StringDistance::jaroWinkler($string1, $string2) . PHP_EOL;

// 0.98809523809524
echo "Jaro-Winkler (prefix scale = 0.2) : " . StringDistance::jaroWinkler($string1, $string2, 0.2) . PHP_EOL;

// -------------- stemming

use webd\language\PorterStemmer;

// analyz
echo "analyzing => " . PorterStemmer::stem("analyzing") . PHP_EOL;

// abandon
echo "abandoned => " . PorterStemmer::stem("abandoned") . PHP_EOL;

// inclin
echo "inclination => " . PorterStemmer::stem("inclination") . PHP_EOL;

// -------------  SpamSum, aka ssdeep, aka Context-Triggered Piecewize Hashing (CTPH)
$s = new \webd\language\SpamSum;
// 192:x+cMdRiWqk2YODjCoG4OU88/ffcQ+lsCYDIlp6+TF244htoJFUjw:krovCLA9byp6+52jhtnjw
echo $s->HashString(file_get_contents(__DIR__ . "/SpamSum.php")) . PHP_EOL;
