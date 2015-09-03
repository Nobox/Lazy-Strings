<?php

namespace Nobox\LazyStrings\Tests\Validators;

use Nobox\LazyStrings\Validators\LazyValidator;
use Orchestra\Testbench\TestCase;

class LazyValidatorTest extends TestCase
{
    protected $validator;

    public function setUp()
    {
        parent::setUp();

        $this->validator = new LazyValidator();
    }

    public function testValidDocUrl()
    {
        $urlValidation = $this->validator->validateDocUrl('http://docs.google.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/export?format=csv');
        $this->assertTrue($urlValidation, 'Failing with a valid url!');
    }

    public function testEmptyStringUrl()
    {
        $emptyStringValidation = $this->validator->validateDocUrl('');
        $this->assertFalse($emptyStringValidation, 'An empty string is passing as a valid url!');
    }

    public function testIncorrectUrl()
    {
        $incorrectUrlValidation = $this->validator->validateDocUrl('http://example.com');
        $this->assertFalse($incorrectUrlValidation, 'Google doc url is not valid!');
    }

    public function testNullUrl()
    {
        $nullValidation = $this->validator->validateDocUrl(null);
        $this->assertFalse($nullValidation, 'Google doc url must NOT be null!');
    }

    /**
     * @expectedException Exception
     */
    public function testNoSheetsProvidedExceptionIsThrown()
    {
        $this->validator->validateSheets([]);
    }
}
