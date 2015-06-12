<?php namespace Nobox\LazyStrings\Tests;

use Nobox\LazyStrings\LazyStrings;
use Illuminate\Filesystem\Filesystem;
use Orchestra\Testbench\TestCase;
use Mockery;

class LazyStringsTest extends TestCase
{
    protected $lazyStrings;
    protected $file;
    protected $validator;
    protected $str;

    public function setUp()
    {
        parent::setUp();

        $this->file = Mockery::mock('Illuminate\Filesystem\Filesystem');
        $this->validator = Mockery::mock('Nobox\LazyStrings\Validators\LazyValidator');
        $this->str = Mockery::mock('Nobox\LazyStrings\Helpers\Str');
        $this->lazyStrings = new LazyStrings($this->file, $this->validator, $this->str);
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
        $this->validator->shouldReceive('validateDocUrl')->atLeast()->times(1)
                        ->with($url)->andReturn(true)->getMock();
        $this->validator->shouldReceive('validateSheets')->once()->with(array('en' => 0))
                        ->andReturnNull()->getMock();
        $this->str->shouldReceive('strip')->atLeast()->times(1)
                        ->andReturn('foo')->getMock();

        $this->lazyStrings->setCsvUrl($url);
        $this->lazyStrings->setSheets(array('en' => 0));

        $generated = $this->lazyStrings->generate();

        $this->assertArrayHasKey('en', $generated, 'Strings array not parsed correctly.');
        $this->assertArrayHasKey('foo', $generated['en'], 'Strings array not parsed correctly.');
    }
}
