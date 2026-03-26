<?php

namespace webd\language\StringSimilarity;

/**
 * Description of JaccardSimilarity
 *
 * @author tibo
 */
class JaccardSimilarity implements StringSimilarity
{
    use NGramTrait;

    private int $n;

    public function __construct(int $n = 2)
    {
        $this->n = $n;
    }

    public function similarity(string $a, string $b): float
    {
        $ngramsA = array_unique($this->ngrams($a, $this->n));
        $ngramsB = array_unique($this->ngrams($b, $this->n));

        $intersection = array_intersect($ngramsA, $ngramsB);
        $union = array_unique(array_merge($ngramsA, $ngramsB));

        if (count($union) === 0) {
            return 0.0;
        }

        return count($intersection) / count($union);
    }
}
