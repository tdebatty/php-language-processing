<?php

namespace webd\language;

use PHPUnit\Framework\TestCase;

class SpamSumTest extends TestCase
{
    /**
     * @var SpamSum
     */
    protected $object;

    protected $str1 = "A string, for example the title of a spam, or the text of an email, or even the content of a "
            . "webpage... Who knows? With some additional characters to make it long enough... and some more "
            . "characters, I hope I will evnetually reach a sufficient lenght....\n";

    protected function setUp(): void
    {
        $this->object = new SpamSum();
    }

    /**
     * @covers webd\language\SpamSum::hashString
     * @todo   Implement testHashString().
     */
    public function testHashString()
    {
        $this->assertEquals(
            $this->object->hashString($this->str1),
            "6:MZEYWZDrpCGgFLLELGrX+TPdLgN98M6S8HROQ9Svb:M+hpTGgiNiM58LSj",
        );
    }

    /**
     * @covers webd\language\SpamSum::setHashLength
     * @todo   Implement testSetHashLength().
     */
    public function testSetHashLength()
    {
        $s = new SpamSum();
        $s->setHashLength(10);
        $s->hashString($this->str1);
        $this->assertEquals("M0Gj58Lo", $s->left());
    }

    /**
     * @covers webd\language\SpamSum::setLetters
     * @todo   Implement testSetLetters().
     */
    public function testSetLetters()
    {
        $s = new SpamSum();
        $s->setLetters(8);
        $s->hashString($this->str1);
        $this->assertEquals(
            "EBEAGBDDBCGAFDDEDGDHGDHFDAFFEECCEHBGAFCHD",
            $s->left(),
        );
    }

    public function testSetMinBlocksize()
    {
        $s = new SpamSum();
        $s->setMinBlocksize(1);
        $s->hashString($this->str1);
        $this->assertEquals(
            "4:M1yuN7qZF30RqjKgBDlWdH0eKyXCBMqGUAiDmNA1XEGAnFNuoILPaFAAhNj:MLN7qZvjKgJU0VmC7GmSFL8PaFAAhh",
            $s->__toString(),
        );
    }


    /**
     * @covers webd\language\SpamSum::blockSize
     * @todo   Implement testBlockSize().
     */
    public function testBlockSize()
    {
    }

    /**
     * @covers webd\language\SpamSum::left
     * @todo   Implement testLeft().
     */
    public function testLeft()
    {
    }

    /**
     * @covers webd\language\SpamSum::right
     * @todo   Implement testRight().
     */
    public function testRight()
    {
    }
}
