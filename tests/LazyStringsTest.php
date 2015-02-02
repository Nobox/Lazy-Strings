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

        $this->file = Mockery::mock('Illuminate\Filesystem\Filesystem');
        $this->lazyStrings = new LazyStrings($this->file);
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testGeneratedStrings()
    {
        $this->file->shouldReceive('exists')->atLeast()->times(1);
        $this->file->shouldReceive('makeDirectory')->atLeast()->times(1);
        $this->file->shouldReceive('put')->atLeast()->times(1);

        $this->lazyStrings->setCsvUrl('http://docs.google.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/export?format=csv');
        $generated = $this->lazyStrings->generate();

        $this->assertArrayHasKey('en', $generated, 'Strings array not parsed correctly.');
        $this->assertArrayHasKey('foo', $generated['en'], 'Strings array not parsed correctly.');
    }
}
