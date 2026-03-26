<?php

namespace webd\language\StringSimilarity;

/**
 * Description of DiceCoefficient
 *
 * @author tibo
 */
class DiceCoefficient implements StringSimilarity
{
    use NGramTrait;

    private int $n;

    public function __construct(int $n = 2)
    {
        $this->n = $n;
    }

    public function similarity(string $a, string $b): float
    {
        if ($a === $b) {
            return 1.0;
        }

        $ngramsA = $this->ngrams($a, $this->n);
        $ngramsB = $this->ngrams($b, $this->n);

        if (empty($ngramsA) || empty($ngramsB)) {
            return 0.0;
        }

        $countsA = array_count_values($ngramsA);
        $countsB = array_count_values($ngramsB);

        $intersection = 0;

        foreach ($countsA as $gram => $countA) {
            if (isset($countsB[$gram])) {
                $intersection += min($countA, $countsB[$gram]);
            }
        }

        return (2 * $intersection) / (count($ngramsA) + count($ngramsB));
    }
}
