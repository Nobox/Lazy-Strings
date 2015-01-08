<?php

use Nobox\LazyStrings\Generators\LazyConfigGenerator;
use Illuminate\Filesystem\Filesystem;

class LazyConfigGeneratorTest extends Orchestra\Testbench\TestCase
{
    protected $configData = array(
        'doc_url'       => 'http://docs.google.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/export?format=csv',
        'target_folder' => 'lazy-strings',
        'strings_route' => 'lazy/build-copy'
    );

    public function tearDown()
    {
        Mockery::close();
    }

    public function testGetConfigTemplate()
    {
        $configGenerator = new LazyConfigGenerator(new Filesystem);
        $generatedTemplate = $configGenerator->getTemplate($this->configData);
        $expectedTemplate = file_get_contents(__DIR__ . '/stubs/config.txt');

        $this->assertEquals($expectedTemplate, $generatedTemplate, 'Templates are not equal!');
    }

    public function testConfigFileExists()
    {
        $file = Mockery::mock('Illuminate\Filesystem\Filesystem');

        $file->shouldReceive('get')->atLeast()->times(1);

        $file->shouldReceive('exists')
             ->once()
             ->andReturn(true);

        $file->shouldReceive('put')->never();

        $configGenerator = new LazyConfigGenerator($file);
        $configGenerator->create($this->configData);
    }

}
