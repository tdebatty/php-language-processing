<?php

namespace webd\language;

use PHPUnit\Framework\TestCase;

class StringDistanceTest extends TestCase
{

    /**
     * @var StringDistance
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() : void
    {
        $this->object = new StringDistance;
    }


    /**
     * @covers webd\language\Distance::Jaro
     */
    public function testJaro()
    {
        $this->assertEqualsWithDelta(0.944, StringDistance::Jaro("MARTHA", "MARHTA"), 0.001, "");
    }

    /**
     * @covers webd\language\Distance::JaroWinkler
     */
    public function testJaroWinkler()
    {
        $this->assertEqualsWithDelta(0.961, StringDistance::JaroWinkler("MARTHA", "MARHTA", 0.1), 0.001, "");
    }
    
    public function testLevenshtein()
    {
        $this->assertEquals(6, StringDistance::Levenshtein("bordure", "contexte"));
    }
}
