<?php

use Nobox\LazyStrings\Generators\LazyConfigGenerator;

class LazyConfigGeneratorTest extends Orchestra\Testbench\TestCase
{
    /**
     * Dummy data to generate config file
     *
     * @var array
     **/
    private $configData = array(
        'doc_url' => 'http://docs.google.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/export?format=csv',
        'target_folder' => 'lazystrings',
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

        $this->configGenerator = new LazyConfigGenerator();
    }

    /**
     * Testing when you get the config file template
     *
     * @return void
     **/
    public function testGetConfigTemplate()
    {
        $generatedTemplate = $this->configGenerator->getConfigTemplate($this->configData);
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
             ->with(app_path() . '/config/lazystrings.php', file_get_contents(__DIR__ . '/stubs/config.txt'))
             ->once()
             ->andReturn(1);

        $this->configGenerator->setFilesystem($file);
        $this->configGenerator->createConfig($this->configData);
    }

}
