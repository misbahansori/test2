<?php

namespace Misbah\ExcelValidator\Validator;

use Misbah\ExcelValidator\Validator\ValidatorInterface;

class Required implements ValidatorInterface
{
    public function getKey(): string
    {
        return 'required';
    }

    public function getRule($header): bool
    {
        return strpos($header, '*') !== false;
    }

    public function getMessage($columnName): string 
    {
        return "Missing value in field $columnName";
    }

    public function validate($cell): bool
    {
        return empty($cell);
    }
}
