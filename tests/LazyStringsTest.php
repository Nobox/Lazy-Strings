<?php

use Nobox\LazyStrings\LazyStrings;
use Illuminate\Filesystem\Filesystem;

class LazyStringsTest extends Orchestra\Testbench\TestCase
{
    protected $lazyStrings;
    protected $file;

    public function setUp()
    {
        parent::setUp();

        $this->lazyStrings = new LazyStrings(new Filesystem);
    }

    public function testParseCopyFromCsv()
    {
        $correctDocUrl = 'http://docs.google.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/export?format=csv&single=true&gid=0';
        $parsed = $this->lazyStrings->parse($correctDocUrl);
        $this->assertArrayHasKey('foo', $parsed, 'Strings array not parsed correctly.');
    }
}
