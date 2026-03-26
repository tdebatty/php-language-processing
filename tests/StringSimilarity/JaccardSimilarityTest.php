<?php

namespace webd\language\StringSimilarity;

use PHPUnit\Framework\TestCase;

class JaccardSimilarityTest extends TestCase
{
    /**
     * @covers webd\language\StringSimilarity\JaccardSimilarity::similarity
     */
    public function testSimilarityIdentical()
    {
        $sim = new JaccardSimilarity();
        $a = "hello world";
        $this->assertEquals(1.0, $sim->similarity($a, $a));
    }

    /**
     * @covers webd\language\StringSimilarity\JaccardSimilarity::similarity
     */
    public function testSimilarityCompletelyDifferent()
    {
        $sim = new JaccardSimilarity();
        $a = "abcdef";
        $b = "ghijkl";
        $this->assertEquals(0.0, $sim->similarity($a, $b));
    }

    /**
     * @covers webd\language\StringSimilarity\JaccardSimilarity::similarity
     */
    public function testSimilarityEmptyStrings()
    {
        $sim = new JaccardSimilarity();
        $this->assertEquals(0.0, $sim->similarity("", ""));
    }

    /**
     * @covers webd\language\StringSimilarity\JaccardSimilarity::similarity
     */
    public function testSimilarityCustomN()
    {
        $sim = new JaccardSimilarity(3);
        $a = "abcde"; // 3-grams: abc, bcd, cde
        $b = "cdefg"; // 3-grams: cde, def, efg
        // intersection: cde = 1; union: abc, bcd, cde, def, efg = 5
        $expected = 1 / 5;
        $this->assertEqualsWithDelta($expected, $sim->similarity($a, $b), 1e-9);
    }

    /**
     * @covers webd\language\StringSimilarity\JaccardSimilarity::similarity
     */
    public function testSimilaritySymmetry()
    {
        $sim = new JaccardSimilarity();
        $a = "test string";
        $b = "string test";
        $this->assertEqualsWithDelta(
            $sim->similarity($a, $b),
            $sim->similarity($b, $a),
            1e-9
        );
    }

    /**
     * @covers webd\language\StringSimilarity\JaccardSimilarity::similarity
     */
    public function testSimilarityWithN1()
    {
        $sim = new JaccardSimilarity(1);
        $a = "aabb"; // chars: a,b
        $b = "abbb"; // chars: a,b
        // intersection: a,b = 2; union: a,b = 2 => 1.0
        $this->assertEquals(1.0, $sim->similarity($a, $b));
    }
}
