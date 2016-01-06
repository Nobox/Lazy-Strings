<?php

namespace Nobox\LazyStrings\Tests;

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

    public function testConvertDottedStringToArrayWithTwoDimensions()
    {
        $twoDimensions = [
            'tagline' => [
                'cta' => 'Click here'
            ]
        ];

        $this->assertSame($twoDimensions, $this->str->convertToArray('tagline.cta', 'Click here'));
    }

    public function testConvertDottedStringToArrayWithThreeDimensions()
    {
        $threeDimensions = [
            'meta' => [
                'seo' => [
                    'description' => 'This is some description'
                ]
            ]
        ];

        $this->assertSame($threeDimensions, $this->str->convertToArray('meta.seo.description', 'This is some description'));
    }

    public function testConvertDottedStringToArrayWithFourDimensions()
    {
        $fourDimensions = [
            'home' => [
                'howto' => [
                    'wildcard' => [
                        'title' => 'The wildcard title'
                    ]
                ]
            ]
        ];

        $this->assertSame($fourDimensions, $this->str->convertToArray('home.howto.wildcard.title', 'The wildcard title'));
    }

    public function testConvertDottedStringToArrayWithFiveDimensions()
    {
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

        $this->assertSame($fiveDimensions, $this->str->convertToArray('about.projects.freelance.php.first', 'This is my first project'));
    }

    public function testConvertDottedStringToArrayWithSixDimensions()
    {
        $sixDimensions = [
            'about' => [
                'footer' => [
                    'social' => [
                        'twitter' => [
                            'followers' => [
                                'count' => '500'
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->assertSame($sixDimensions, $this->str->convertToArray('about.footer.social.twitter.followers.count', '500'));
    }

    public function testConvertDottedStringWithNumericKeysToArray()
    {
        $expected = [
            'question' => [
                '1' => [
                    'answers' => [
                        'a' => 'This is some answer.'
                    ]
                ]
            ]
        ];

        $this->assertSame($expected, $this->str->convertToArray('question.1.answers.a', 'This is some answer.'));
    }
}
