<?php

namespace webd\language\StringSimilarity;

/**
 * Description of NGramTrait
 *
 * @author tibo
 */
trait NGramTrait
{
    protected function ngrams(string $text, int $n): array
    {
        $text = mb_strtolower($text, 'UTF-8');
        $len = mb_strlen($text, 'UTF-8');

        $grams = [];
        for ($i = 0; $i <= $len - $n; $i++) {
            $grams[] = mb_substr($text, $i, $n, 'UTF-8');
        }

        return $grams;
    }
}
