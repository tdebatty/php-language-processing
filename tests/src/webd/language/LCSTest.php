<?php

namespace webd\language;

use PHPUnit\Framework\TestCase;

class LCSTest extends TestCase
{

    /**
     * @var LCS
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() : void
    {
        $this->object = new LCS("BACBAD", "BATBAD");
    }


    /**
     * @covers webd\language\LCS::LCS
     * @todo   Implement testLCS().
     */
    public function testValue()
    {
        $this->assertEquals($this->object->value(), "BABAD");
    }

    /**
     * @covers webd\language\LCS::length
     * @todo   Implement testLength().
     */
    public function testLength()
    {
        $this->assertEquals($this->object->length(), 5);
    }
    
    public function testDistance()
    {
        $this->assertEquals($this->object->distance(), 2);
    }

    /**
     * @covers webd\language\LCS::__toString
     * @todo   Implement test__toString().
     */
    public function test__toString()
    {
    }
}
