<?php

namespace Nobox\LazyStrings\Tests;

use Illuminate\Filesystem\Filesystem;
use Mockery;
use Nobox\LazyStrings\Helpers\Str;
use Nobox\LazyStrings\LazyStrings;
use Nobox\LazyStrings\Validators\LazyValidator;
use Orchestra\Testbench\TestCase;

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
        $this->validator = new LazyValidator;
        $this->str = new Str;
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

    public function testStringsAreGeneratedFromGoogleDoc()
    {
        $url = 'http://docs.google.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/export?format=csv';

        $this->file->shouldReceive('exists')->twice()->andReturn(false);
        $this->file->shouldReceive('makeDirectory')->twice();
        $this->file->shouldReceive('put')->twice();

        $this->lazyStrings->setCsvUrl($url);
        $this->lazyStrings->setSheets(['en' => 0]);

        $generated = $this->lazyStrings->generate();

        $this->assertArrayHasKey('en', $generated, 'Strings array not parsed correctly.');
        $this->assertArrayHasKey('foo', $generated['en'], 'Strings array not parsed correctly.');
    }
}
