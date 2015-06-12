<?php namespace Nobox\LazyStrings\Tests\Validators;

use Nobox\LazyStrings\Validators\LazyValidator;
use Orchestra\Testbench\TestCase;

class LazyValidatorTest extends TestCase
{
    public function testValidDocUrl()
    {
        $urlValidation = LazyValidator::validateDocUrl('http://docs.google.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/export?format=csv');
        $this->assertTrue($urlValidation, 'Failing with a valid url!');
    }

    public function testEmptyStringUrl()
    {
        $emptyStringValidation = LazyValidator::validateDocUrl('');
        $this->assertFalse($emptyStringValidation, 'An empty string is passing as a valid url!');
    }

    public function testIncorrectUrl()
    {
        $incorrectUrlValidation = LazyValidator::validateDocUrl('http://example.com');
        $this->assertFalse($incorrectUrlValidation, 'Google doc url is not valid!');
    }

    public function testNullUrl()
    {
        $nullValidation = LazyValidator::validateDocUrl(null);
        $this->assertFalse($nullValidation, 'Google doc url must NOT be null!');
    }

    public function testStripNewlines()
    {
        $newline = LazyValidator::strip("some.key.here\n");
        $newlines = LazyValidator::strip("\nsome.key.here\n\n");
        $normal = LazyValidator::strip("some.key.here");
        $return = LazyValidator::strip("some.key.here\r");
        $returns = LazyValidator::strip("\r\rsome.key.here\r");

        $this->assertSame('some.key.here', $newline);
        $this->assertSame('some.key.here', $newlines);
        $this->assertSame('some.key.here', $normal);
        $this->assertSame('some.key.here', $return);
        $this->assertSame('some.key.here', $returns);
    }

    /**
     * @expectedException Exception
     */
    public function testNoSheetsProvidedExceptionIsThrown()
    {
        LazyValidator::validateSheets(array());
    }
}
