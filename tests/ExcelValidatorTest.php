<?php

use PHPUnit\Framework\TestCase;
use Misbah\ExcelValidator\ExcelValidator;

class ExcelValidatorTest extends TestCase
{
    public $validator;

    public function setUp(): void
    {
        parent::setUp();
        $this->validator = new ExcelValidator();
    }

    public function testValidate()
    {
        $this->assertSame($this->validator->validate("storage/Type_A.xlsx"), 'validating excel');
    }

    public function testGetRules()
    {
        $headers = ['Field_A*', '#Field_B', 'Field_C', 'Field_D*', 'Field_E*'];
        $expectedRules = ['required', 'no_space', null, 'required', 'required'];

        $this->assertSame($this->validator->getrules($headers), $expectedRules);
    }
}
