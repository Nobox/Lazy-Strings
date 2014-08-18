<?php

use Nobox\LazyStrings\Validators\LazyValidator;

class LazyValidatorTest extends Orchestra\Testbench\TestCase
{

    /**
     * Test a valid gooogle doc url
     *
     * @param type var Description
     **/
    public function testValidDocUrl()
    {
        $emptyStringValidation = LazyValidator::validateDocUrl('');
        $incorrectUrlValidation = LazyValidator::validateDocUrl('http://example.com');
        $urlValidation = LazyValidator::validateDocUrl('https://docs.google.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/export?format=csv');
        $nullValidation = LazyValidator::validateDocUrl(NULL);

        $this->assertFalse($emptyStringValidation, 'An empty string is passing as a valid url!');
        $this->assertFalse($incorrectUrlValidation, 'Google doc url is not valid!');
        $this->assertFalse($nullValidation, 'Google doc url must NOT be null!');
        $this->assertTrue($urlValidation, 'Failing with a valid url!');
    }
}
