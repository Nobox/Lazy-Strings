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
    protected $filesystem;
    protected $validator;
    protected $str;
    protected $url = 'http://docs.google.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/export?format=csv';

    public function setUp()
    {
        parent::setUp();

        $this->filesystem = Mockery::mock('Illuminate\Filesystem\Filesystem');
        $this->validator = new LazyValidator;
        $this->str = new Str;
        $this->lazyStrings = new LazyStrings($this->filesystem, $this->validator, $this->str);
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
        $this->lazyStrings->setCsvUrl($this->url);
        $this->lazyStrings->setSheets([
            'en' => 0,
            'es' => 1329731586,
            'pt' => 1443604037,
        ]);

        $this->setUpMocks();

        $expectedStrings = [
            'en' => [
                'foo' => 'Hello!',
                'lazy' => 'LazyStrings',
                'laravel' => 'PHP Framework',
                'something' => [
                    'else' => [
                        'here' => 'Yeah'
                    ]
                ]
            ],

            'es' => [
                'foo' => 'bar - es',
                'lazy' => 'LazyStrings (ES)',
                'laravel' => 'PHP Framework (ES)',
            ],

            'pt' => [
                'foo' => 'bar - pt',
                'lazy' => 'LazyStrings (PT)',
                'laravel' => 'PHP Framework (PT)',
            ],
        ];

        $strings = $this->lazyStrings->generate();

        $this->assertSame($strings, $expectedStrings);
        $this->assertArrayHasKey('en', $strings);
        $this->assertArrayHasKey('es', $strings);
        $this->assertArrayHasKey('pt', $strings);
        $this->assertArrayHasKey('foo', $strings['en']);
        $this->assertArrayHasKey('lazy', $strings['en']);
        $this->assertArrayHasKey('laravel', $strings['en']);
        $this->assertArrayHasKey('something', $strings['en']);
        $this->assertArrayHasKey('else', $strings['en']['something']);
        $this->assertArrayHasKey('here', $strings['en']['something']['else']);
        $this->assertArrayHasKey('foo', $strings['es']);
        $this->assertArrayHasKey('lazy', $strings['es']);
        $this->assertArrayHasKey('laravel', $strings['es']);
        $this->assertArrayHasKey('foo', $strings['pt']);
        $this->assertArrayHasKey('lazy', $strings['pt']);
        $this->assertArrayHasKey('laravel', $strings['pt']);
    }

    public function testStringsAreGeneratedWithAppendedSheet()
    {
        $this->lazyStrings->setCsvUrl($this->url);
        $this->lazyStrings->setSheets([
            'en' => [0, 1626663029],
            'es' => 1329731586,
            'pt' => 1443604037,
        ]);

        $this->setUpMocks();

        $expectedStrings = [
            'en' => [
                'foo' => 'Hello!',
                'lazy' => 'LazyStrings',
                'laravel' => 'PHP Framework',
                'something' => [
                    'else' => [
                        'here' => 'Yeah'
                    ]
                ],
                'another' => [
                    'thing' => 'extra value in EN',
                    'extra-thing' => 'This is an extra thing',
                    'in-english' => 'Another one in English',
                ],
            ]
        ];

        $strings = $this->lazyStrings->generate();

        $this->assertSame($strings['en'], $expectedStrings['en']);
        $this->assertArrayHasKey('foo', $strings['en']);
        $this->assertArrayHasKey('lazy', $strings['en']);
        $this->assertArrayHasKey('another', $strings['en']);
        $this->assertArrayHasKey('thing', $strings['en']['another']);
        $this->assertArrayHasKey('extra-thing', $strings['en']['another']);
        $this->assertArrayHasKey('in-english', $strings['en']['another']);
    }

    protected function setUpMocks()
    {
        $this->filesystem->shouldReceive('exists')->times(6)->andReturn(false);
        $this->filesystem->shouldReceive('makeDirectory')->times(6);
        $this->filesystem->shouldReceive('put')->times(6);
    }
}
