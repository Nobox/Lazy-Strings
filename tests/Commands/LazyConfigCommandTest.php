<?php

use Symfony\Component\Console\Tester\CommandTester;
use Nobox\LazyStrings\Commands\LazyConfigCommand;

class LazyConfigCommandTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test command output
     *
     * @return void
     **/
    public function testOutput()
    {
        $commandTester = new CommandTester(new LazyConfigCommand);
        $commandTester->execute([]);

        $this->assertEquals("Initialize LazyStrings config.\n", $commandTester->getDisplay());
    }
}
