<?php

namespace Misbah\ExcelValidator\Validator;

use Misbah\ExcelValidator\Validator\ValidatorInterface;

class NoSpace implements ValidatorInterface
{
    public function getKey(): string
    {
        return 'no_space';
    }

    public function getRule($header): bool
    {
        return strpos($header, '#') !== false;
    }

    public function getMessage($columnName): string 
    {
        return "$columnName should not contain space";
    }

    public function validate($cell): bool
    {
        return strpos($cell, ' ') !== false;
    }
}
