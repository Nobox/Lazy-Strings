<?php

namespace Nobox\LazyStrings\Tests;

use Mockery;
use Nobox\LazyStrings\Str;
use Nobox\LazyStrings\LazyStrings;
use Nobox\LazyStrings\Validator;
use PHPUnit_Framework_TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class LazyStringsTest extends PHPUnit_Framework_TestCase
{
    private $url = 'http://docs.google.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/export?format=csv';

    public function setUp()
    {
        $settings = [
            'url' => 'csv url',
            'sheets' => [], // sheets array mapping
            'target' => '', // location where to store the strings array
            'backup' => '', // location where to store the strings json
            'nested' => false // whether or not you want to use nested strings
        ];
    }

    /**
     * Cleanup generated strings directories and files.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->removeDirectory(__DIR__.'/en');
        $this->removeDirectory(__DIR__.'/es');
        $this->removeDirectory(__DIR__.'/pt');
        $this->removeFile(__DIR__.'/es.json');
        $this->removeFile(__DIR__.'/en.json');
        $this->removeFile(__DIR__.'/pt.json');
    }

    /**
     * @test
     */
    public function nested_strings_are_generated_from_google_docs()
    {
        $lazyStrings = new LazyStrings([
            'url' => $this->url,
            'sheets' => [
                'en' => 0,
                'es' => 1329731586,
                'pt' => 1443604037,
            ],
            'target' => __DIR__,
            'backup' => __DIR__,
            'nested' => true
        ]);

        $expectedStrings = [
            'en' => [
                'title' => 'Your page title',
                'tagline' => 'Your page tagline',
                'laravel' => 'PHP Framework',
                'header' => [
                    'hero' => [
                        'headline' => 'Hero headlines',
                        'subject' => 'Main hero subject',
                    ]
                ],
                'footer' => [
                    'notice' => 'All rights reserved',
                    'copyrights' => 'Copyrights copy',
                ],
                'home' => [
                    'welcome' => [
                        'tutorial' => [
                            'title' => 'Tutorial Title',
                            'description' => 'Tutorial Description',
                        ],
                        'picks' => [
                            'title' => 'Picks Title',
                            'description' => 'Picks Description',
                        ],
                    ],
                    'social' => [
                        'facebook' => [
                            'title' => 'Facebook Title',
                            'content' => 'Facebook Content',
                            'description' => 'Facebook Description',
                        ],
                        'twitter' => [
                            'title' => 'Twitter Title',
                            'content' => 'Twitter Content',
                            'description' => 'Twitter Description',
                        ]
                    ]
                ]
            ],

            'es' => [
                'title' => 'Titulo de la pagina',
                'tagline' => 'Tagline de la pagina',
                'laravel' => 'Framework de PHP',
                'header' => [
                    'hero' => [
                        'headline' => 'Headlines del hero',
                        'subject' => 'Tema del hero',
                    ]
                ],
                'footer' => [
                    'notice' => 'Todos los derechos reservados',
                    'copyrights' => 'Derechos de autor',
                ],
                'home' => [
                    'welcome' => [
                        'tutorial' => [
                            'title' => 'Titulo del tutorial',
                            'description' => 'Descripcion del tutorial',
                        ],
                        'picks' => [
                            'title' => 'Titulos de la seleccion',
                            'description' => 'Descripcion de la seleccion',
                        ],
                    ],
                    'social' => [
                        'facebook' => [
                            'title' => 'Titulo de Facebook',
                            'content' => 'Contenido de Facebook',
                            'description' => 'Descripcion de Facebook',
                        ],
                        'twitter' => [
                            'title' => 'Titulo de Twitter',
                            'content' => 'Contenido de Twitter',
                            'description' => 'Descripcion de Twitter',
                        ]
                    ]
                ]
            ],

            'pt' => [
                'title' => 'Titulo de la pagina',
                'tagline' => 'Tagline de la pagina',
                'laravel' => 'Framework de PHP',
                'header' => [
                    'hero' => [
                        'headline' => 'Headlines del hero',
                        'subject' => 'Tema del hero',
                    ]
                ],
                'footer' => [
                    'notice' => 'Todos los derechos reservados',
                    'copyrights' => 'Derechos de autor',
                ],
                'home' => [
                    'welcome' => [
                        'tutorial' => [
                            'title' => 'Titulo del tutorial',
                            'description' => 'Descripcion del tutorial',
                        ],
                        'picks' => [
                            'title' => 'Titulos de la seleccion',
                            'description' => 'Descripcion de la seleccion',
                        ],
                    ],
                    'social' => [
                        'facebook' => [
                            'title' => 'Titulo de Facebook',
                            'content' => 'Contenido de Facebook',
                            'description' => 'Descripcion de Facebook',
                        ],
                        'twitter' => [
                            'title' => 'Titulo de Twitter',
                            'content' => 'Contenido de Twitter',
                            'description' => 'Descripcion de Twitter',
                        ]
                    ]
                ]
            ],
        ];

        $strings = $lazyStrings->generate();

        $this->assertSame($strings, $expectedStrings);
        $this->assertArrayHasKey('en', $strings);
        $this->assertArrayHasKey('es', $strings);
        $this->assertArrayHasKey('pt', $strings);
        $this->assertArrayHasKey('title', $strings['en']);
        $this->assertArrayHasKey('tagline', $strings['en']);
        $this->assertArrayHasKey('laravel', $strings['en']);
        $this->assertArrayHasKey('header', $strings['en']);
        $this->assertArrayHasKey('hero', $strings['en']['header']);
        $this->assertArrayHasKey('headline', $strings['en']['header']['hero']);
        $this->assertArrayHasKey('subject', $strings['en']['header']['hero']);
        $this->assertArrayHasKey('footer', $strings['en']);
        $this->assertArrayHasKey('notice', $strings['en']['footer']);
        $this->assertArrayHasKey('copyrights', $strings['en']['footer']);
        $this->assertArrayHasKey('home', $strings['en']);
        $this->assertArrayHasKey('welcome', $strings['en']['home']);
        $this->assertArrayHasKey('tutorial', $strings['en']['home']['welcome']);
        $this->assertArrayHasKey('title', $strings['en']['home']['welcome']['tutorial']);
        $this->assertArrayHasKey('description', $strings['en']['home']['welcome']['tutorial']);
        $this->assertArrayHasKey('picks', $strings['en']['home']['welcome']);
        $this->assertArrayHasKey('title', $strings['en']['home']['welcome']['picks']);
        $this->assertArrayHasKey('description', $strings['en']['home']['welcome']['picks']);
        $this->assertArrayHasKey('social', $strings['en']['home']);
        $this->assertArrayHasKey('facebook', $strings['en']['home']['social']);
        $this->assertArrayHasKey('title', $strings['en']['home']['social']['facebook']);
        $this->assertArrayHasKey('content', $strings['en']['home']['social']['facebook']);
        $this->assertArrayHasKey('description', $strings['en']['home']['social']['facebook']);
        $this->assertArrayHasKey('twitter', $strings['en']['home']['social']);
        $this->assertArrayHasKey('title', $strings['en']['home']['social']['twitter']);
        $this->assertArrayHasKey('content', $strings['en']['home']['social']['twitter']);
        $this->assertArrayHasKey('description', $strings['en']['home']['social']['twitter']);
    }

    /**
     * @test
     */
    public function nested_strings_are_generated_from_google_docs_with_appended_sheet()
    {
        $lazyStrings = new LazyStrings([
            'url' => $this->url,
            'sheets' => [
                'en' => [0, 1626663029],
                'es' => 1329731586,
                'pt' => 1443604037,
            ],
            'target' => __DIR__,
            'backup' => __DIR__,
            'nested' => true
        ]);

        $expectedStrings = [
            'en' => [
                'title' => 'Your page title',
                'tagline' => 'Your page tagline',
                'laravel' => 'PHP Framework',
                'header' => [
                    'hero' => [
                        'headline' => 'Hero headlines',
                        'subject' => 'Main hero subject',
                    ]
                ],
                'footer' => [
                    'notice' => 'All rights reserved',
                    'copyrights' => 'Copyrights copy',
                ],
                'home' => [
                    'welcome' => [
                        'tutorial' => [
                            'title' => 'Tutorial Title',
                            'description' => 'Tutorial Description',
                        ],
                        'picks' => [
                            'title' => 'Picks Title',
                            'description' => 'Picks Description',
                        ],
                    ],
                    'social' => [
                        'facebook' => [
                            'title' => 'Facebook Title',
                            'content' => 'Facebook Content',
                            'description' => 'Facebook Description',
                        ],
                        'twitter' => [
                            'title' => 'Twitter Title',
                            'content' => 'Twitter Content',
                            'description' => 'Twitter Description',
                        ]
                    ]
                ],
                'poll' => [
                    'question' => [
                        '1' => [
                            'title' => 'Title of your question',
                            'answers' => [
                                'a' => 'First answer',
                                'b' => 'Second answer',
                                'c' => 'Third answer',
                                'd' => 'Fourth answer',
                            ]
                        ]
                    ]
                ],
            ]
        ];

        $strings = $lazyStrings->generate();

        $this->assertSame($strings['en'], $expectedStrings['en']);
        $this->assertArrayHasKey('poll', $strings['en']);
        $this->assertArrayHasKey('question', $strings['en']['poll']);
        $this->assertArrayHasKey('1', $strings['en']['poll']['question']);
        $this->assertArrayHasKey('title', $strings['en']['poll']['question']['1']);
        $this->assertArrayHasKey('answers', $strings['en']['poll']['question']['1']);
        $this->assertArrayHasKey('a', $strings['en']['poll']['question']['1']['answers']);
        $this->assertArrayHasKey('b', $strings['en']['poll']['question']['1']['answers']);
        $this->assertArrayHasKey('c', $strings['en']['poll']['question']['1']['answers']);
        $this->assertArrayHasKey('d', $strings['en']['poll']['question']['1']['answers']);
    }

    /**
     * @test
     */
    public function it_stores_strings_in_json_format()
    {
        $lazyStrings = new LazyStrings([
            'url' => $this->url,
            'sheets' => [
                'en' => 0,
                'es' => 1329731586,
                'pt' => 1443604037,
            ],
            'target' => __DIR__,
            'backup' => __DIR__,
            'nested' => true
        ]);

        $strings = $lazyStrings->generate();

        $this->assertTrue(file_exists(__DIR__.'/es.json'));
        $this->assertTrue(file_exists(__DIR__.'/en.json'));
        $this->assertTrue(file_exists(__DIR__.'/pt.json'));
    }

    /**
     * Remove generated file.
     *
     * @return void
     */
    private function removeFile($path)
    {
        if (file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * Remove a directory and it's contents.
     *
     * @param string $path
     *
     * @return void
     */
    private function removeDirectory($path)
    {
        $iterator = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }

        rmdir($path);
    }
}
