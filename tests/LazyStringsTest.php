<?php

use Nobox\LazyStrings\LazyStrings;

class LazyStringsTest extends Orchestra\Testbench\TestCase
{
    /**
     * The LazyStrings instance
     *
     * @var LazyStrings
     **/
    private $lazyStrings;

    /**
     * Correct url to google doc
     *
     * @var string
     **/
    private $correctDocUrl = 'http://docs.google.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/export?format=csv&single=true&gid=0';

    /**
     * Incorrect url to google doc
     *
     * @var string
     **/
    private $incorrectDocUrl = 'http://fail.com';

    /**
     * Set things up before running
     *
     * @return void
     **/
    public function setUp()
    {
        parent::setUp();

        $this->lazyStrings = new LazyStrings();
    }

    /**
     * Test when getting copy from csv path
     *
     * @return void
     **/
    public function testGetCopyFromCsv()
    {
        $generatedStrings = $this->lazyStrings->getCopyCsv($this->correctDocUrl);
        $stringsArray = json_decode($generatedStrings, true);

        $this->assertArrayHasKey('foo', $stringsArray, 'Strings array not parsed correctly.');
    }

    /**
     * Test exception when 0 sheets are provided
     *
     * @expectedException Exception
     * @expectedExceptionMessage No sheets were provided.
     **/
    public function testNoSheetsProvidedException()
    {
        $this->lazyStrings->generateStrings();
    }
}
