<?php

namespace webd\language;

class StringDistance {

    /**
     * The higher the Jaro–Winkler distance for two strings is, the more 
     * similar the strings are. The Jaro–Winkler distance metric is designed 
     * and best suited for short strings such as person names. The score is 
     * normalized such that 0 equates to no similarity and 1 is an exact match.
     * 
     * @param type $string1
     * @param type $string2
     * @return int
     */
    public static function Jaro($string1, $string2) {

        $str1_len = strlen($string1);
        $str2_len = strlen($string2);

        // theoretical distance
        $distance = (int) floor(min($str1_len, $str2_len) / 2.0);

        // get common characters
        $commons1 = self::getCommonCharacters($string1, $string2, $distance);
        $commons2 = self::getCommonCharacters($string2, $string1, $distance);

        if (($commons1_len = strlen($commons1)) == 0) {
            return 0;
        }
        
        if (($commons2_len = strlen($commons2)) == 0) {
            return 0;
        }

        // calculate transpositions
        $transpositions = 0;
        $upperBound = min($commons1_len, $commons2_len);
        for ($i = 0; $i < $upperBound; $i++) {
            if ($commons1[$i] != $commons2[$i]) {
                $transpositions++;
            }
        }
        $transpositions /= 2.0;

        // return the Jaro distance
        return ($commons1_len / ($str1_len) + $commons2_len / ($str2_len) + 
                ($commons1_len - $transpositions) / ($commons1_len)) / 3.0;
    }
    
    public static function JaroWinkler($string1, $string2, $PREFIXSCALE = 0.1) {

        $JaroDistance = self::Jaro($string1, $string2);
        $prefixLength = self::getPrefixLength($string1, $string2);
        return $JaroDistance + $prefixLength * $PREFIXSCALE * (1.0 - $JaroDistance);
    }

   protected static function getCommonCharacters($string1, $string2, $allowedDistance) {

        $str2_len = strlen($string2);
        $commonCharacters = '';
        if (!empty($string1)) {
            foreach (str_split($string1) as $i => $char) {
                $search = strpos($string2, $char, $i <= $allowedDistance ? 0 : min($i - $allowedDistance, $str2_len));
                if ($search !== false && $search <= $i + $allowedDistance + 1) {
                    $commonCharacters .= $char;
                }
            }
        }

        return $commonCharacters;
    }

    protected static function getPrefixLength($string1, $string2, $MINPREFIXLENGTH = 4) {

        $n = min(array($MINPREFIXLENGTH, strlen($string1), strlen($string2)));

        for ($i = 0; $i < $n; $i++) {
            if ($string1[$i] != $string2[$i]) {
                // return index of first occurrence of different characters 
                return $i;
            }
        }

        // first n characters are the same   
        return $n;
    }
    
    /**
     * Returns the minimum number of single-character edits 
     * (i.e. insertions, deletions or substitutions) required to change one 
     * word into the other
     * @param type $string1
     * @param type $string2
     * @return type
     */
    public static function Levenshtein($string1, $string2) {
        return levenshtein($string1, $string2);
    }
    
    /**
     * Levenshtein($string1, $string2)
     * @param type $string1
     * @param type $string2
     * @return type
     */
    public static function EditDistance($string1, $string2) {
        return self::Levenshtein($string1, $string2);
    }
    
}

