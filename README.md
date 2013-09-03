php-language-processing
=======================

A PHP library for language processing. Includes string distance function (Levenshtein, Jaro-Winkler,...), stemming, etc.

Usage
-----

```php
use webd\language\StringDistance;

$string1 = "You won 10000$";
$string2 = "You won 15500$";

echo "Edit distance : " . StringDistance::EditDistance($string1, $string2);
echo "Levenshtein : " . StringDistance::Levenshtein($string1, $string2);
echo "Jaro-Winkler : " . StringDistance::JaroWinkler($string1, $string2);
echo "Jaro-Winkler (prefix scale = 0.2) : " . StringDistance::JaroWinkler($string1, $string2, 0.2);

use webd\language\PorterStemmer;
echo "analyzing => " . PorterStemmer::Stem("analyzing");
echo "abandoned => " . PorterStemmer::Stem("abandoned");
echo "inclination => " . PorterStemmer::Stem("inclination");
```