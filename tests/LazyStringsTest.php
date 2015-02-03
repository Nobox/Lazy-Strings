<?php namespace Nobox\Tests\LazyStrings;

use Nobox\LazyStrings\LazyStrings;
use Illuminate\Filesystem\Filesystem;
use Orchestra\Testbench\TestCase;
use Mockery;

class LazyStringsTest extends TestCase
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

    protected function getPackageProviders($app)
    {
        return ['Nobox\LazyStrings\LazyStringsServiceProvider'];
    }

    public function testGeneratedStrings()
    {
        $url = 'http://docs.google.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/export?format=csv';

        $this->file->shouldReceive('exists')->atLeast()->times(1);
        $this->file->shouldReceive('makeDirectory')->atLeast()->times(1);
        $this->file->shouldReceive('put')->atLeast()->times(1);

        $this->lazyStrings->setCsvUrl($url);
        $this->lazyStrings->setSheets(array('en' => 0));

        $generated = $this->lazyStrings->generate();

        $this->assertArrayHasKey('en', $generated, 'Strings array not parsed correctly.');
        $this->assertArrayHasKey('foo', $generated['en'], 'Strings array not parsed correctly.');
    }
}
