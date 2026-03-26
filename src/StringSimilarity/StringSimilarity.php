<?php

namespace webd\language\StringSimilarity;

/**
 * Description of StringSimilarity
 *
 * @author tibo
 */
interface StringSimilarity
{
    public function similarity(string $a, string $b): float;
}
