<?php

namespace webd\language;

use PHPUnit\Framework\TestCase;


class PorterStemmerTest extends TestCase
{

    /**
     * @var PorterStemmer
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() : void
    {
        $this->object = new PorterStemmer;
    }

    /**
     * @covers webd\language\PorterStemmer::Stem
     * @todo   Implement testStem().
     */
    public function testStem()
    {
        $this->assertEquals(PorterStemmer::Stem("caresses"), "caress");
    }
}
