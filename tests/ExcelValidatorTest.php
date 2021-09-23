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
        $this->assertSame($this->validator->validate("storage/Type_A.xlsx"), [
            3 => [
                'Missing value in field Field_A',
                'Field_B should not contain space',
                'Missing value in field Field_D'
            ],
            4 => [
                'Missing value in field Field_A',
                'Missing value in field Field_E'
            ]
        ]);

        $this->assertEqualsCanonicalizing($this->validator->validate("storage/Type_B.xlsx"), [
            3 => [
                'Missing value in field Field_A',
                'Field_B should not contain space',
            ],
            4 => [
                'Missing value in field Field_A',
            ]
        ]);
    }

    public function testGetRules()
    {
        $headers = ['Field_A*', '#Field_B', 'Field_C', 'Field_D*', 'Field_E*'];
        $expectedRules = ['required', 'no_space', null, 'required', 'required'];

        $this->assertSame($this->validator->getrules($headers), $expectedRules);
    }

    public function testValidateCellRequired()
    {
        $this->validator->validateCell('', 'required', 1, 'Field_A*');

        $this->assertEqualsCanonicalizing($this->validator->getErrors(), [
            1 => ['Missing value in field Field_A']
        ]);

        $this->validator->validateCell(null, 'required', 2, 'Field_B*');
        
        $this->assertEqualsCanonicalizing($this->validator->getErrors(), [
            1 => ['Missing value in field Field_A'],
            2 => ['Missing value in field Field_B']
        ]);
    }

}
