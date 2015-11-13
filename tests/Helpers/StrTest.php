<?php

namespace Nobox\LazyStrings\Tests\Helpers;

use Nobox\LazyStrings\Helpers\Str;
use Orchestra\Testbench\TestCase;

class StrTest extends TestCase
{
    protected $str;

    public function setUp()
    {
        parent::setUp();

        $this->str = new Str();
    }

    public function testStripNewlines()
    {
        $newline = $this->str->strip("some.key.here\n");
        $newlines = $this->str->strip("\nsome.key.here\n\n");
        $normal = $this->str->strip("some.key.here");
        $return = $this->str->strip("some.key.here\r");
        $returns = $this->str->strip("\r\rsome.key.here\r");

        $this->assertSame('some.key.here', $newline);
        $this->assertSame('some.key.here', $newlines);
        $this->assertSame('some.key.here', $normal);
        $this->assertSame('some.key.here', $return);
        $this->assertSame('some.key.here', $returns);
    }

    public function testStringHasDots()
    {
        $dotted = 'this.string.has.dots';
        $oneDot = 'only.onedot';
        $noDots = 'cool';

        $this->assertTrue($this->str->hasDots($dotted));
        $this->assertTrue($this->str->hasDots($oneDot));
        $this->assertFalse($this->str->hasDots($noDots));
    }

    public function testConvertDottedStringToArray()
    {
        $oneDimension = [
            'title' => 'This is your page title'
        ];

        $twoDimensions = [
            'tagline' => [
                'cta' => 'Click here'
            ]
        ];

        $threeDimensions = [
            'meta' => [
                'seo' => [
                    'description' => 'This is some description'
                ]
            ]
        ];

        $fourDimensions = [
            'home' => [
                'howto' => [
                    'wildcard' => [
                        'title' => 'The wildcard title'
                    ]
                ]
            ]
        ];

        $fiveDimensions = [
            'about' => [
                'projects' => [
                    'freelance' => [
                        'php' => [
                            'first' => 'This is my first project'
                        ]
                    ]
                ]
            ]
        ];

        $this->assertSame($oneDimension, $this->str->convertToArray('title', 'This is your page title'));
        $this->assertSame($twoDimensions, $this->str->convertToArray('tagline.cta', 'Click here'));
        $this->assertSame($threeDimensions, $this->str->convertToArray('meta.seo.description', 'This is some description'));
        $this->assertSame($fourDimensions, $this->str->convertToArray('home.howto.wildcard.title', 'The wildcard title'));
        $this->assertSame($fiveDimensions, $this->str->convertToArray('about.projects.freelance.php.first', 'This is my first project'));
    }

    /**
     * @expectedException Exception
     */
    public function testArrayHasTooManyDimensions()
    {
        $this->str->convertToArray('one.two.three.four.five.six', 'Hello World.');
    }
}
