<?php

use Nobox\LazyStrings\Generators\LazyConfigGenerator;
use Illuminate\Filesystem\Filesystem;

class LazyConfigGeneratorTest extends Orchestra\Testbench\TestCase
{
    /**
     * Dummy data to generate config file
     *
     * @var array
     **/
    private $configData = array(
        'doc_url'       => 'http://docs.google.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/export?format=csv',
        'target_folder' => 'lazy-strings',
        'strings_route' => 'lazy/build-copy'
    );

    /**
     * The lazy config generator instance
     *
     * @var LazyConfigGenerator
     **/
    private $configGenerator;

    /**
     * Set things up before running
     *
     * @return void
     **/
    public function setUp()
    {
        parent::setUp();

        $this->configGenerator = new LazyConfigGenerator(new Filesystem);
    }

    /**
     * Testing when you get the config file template
     *
     * @return void
     **/
    public function testGetConfigTemplate()
    {
        $generatedTemplate = $this->configGenerator->getTemplate($this->configData);
        $expectedTemplate = file_get_contents(__DIR__ . '/stubs/config.txt');

        $this->assertEquals($expectedTemplate, $generatedTemplate, 'Templates are not equal!');
    }

    /**
     * Testing generation of config file
     *
     * @return void
     **/
    public function testGenerateConfigUsingTemplate()
    {
        $file = Mockery::mock('Illuminate\Filesystem\Filesystem[put]');

        $file->shouldReceive('put')
             ->with(app_path() . '/config/lazy-strings.php', file_get_contents(__DIR__ . '/stubs/config.txt'))
             ->once()
             ->andReturn(1);

        $this->configGenerator->create($this->configData);
    }

}
