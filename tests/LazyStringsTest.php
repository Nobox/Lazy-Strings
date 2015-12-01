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

        $strings = $this->lazyStrings->generate();

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
                        'one' => [
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

        $strings = $this->lazyStrings->generate();

        $this->assertSame($strings['en'], $expectedStrings['en']);
        $this->assertArrayHasKey('poll', $strings['en']);
        $this->assertArrayHasKey('question', $strings['en']['poll']);
        $this->assertArrayHasKey('one', $strings['en']['poll']['question']);
        $this->assertArrayHasKey('title', $strings['en']['poll']['question']['one']);
        $this->assertArrayHasKey('answers', $strings['en']['poll']['question']['one']);
        $this->assertArrayHasKey('a', $strings['en']['poll']['question']['one']['answers']);
        $this->assertArrayHasKey('b', $strings['en']['poll']['question']['one']['answers']);
        $this->assertArrayHasKey('c', $strings['en']['poll']['question']['one']['answers']);
        $this->assertArrayHasKey('d', $strings['en']['poll']['question']['one']['answers']);
    }

    protected function setUpMocks()
    {
        $this->filesystem->shouldReceive('exists')->times(6)->andReturn(false);
        $this->filesystem->shouldReceive('makeDirectory')->times(6);
        $this->filesystem->shouldReceive('put')->times(6);
    }
}
