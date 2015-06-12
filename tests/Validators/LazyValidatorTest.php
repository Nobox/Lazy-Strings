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

    /**
     * @expectedException Exception
     */
    public function testNoSheetsProvidedExceptionIsThrown()
    {
        LazyValidator::validateSheets(array());
    }
}
