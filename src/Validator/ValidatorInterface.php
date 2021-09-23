<?php

namespace Misbah\ExcelValidator\Validator;

interface ValidatorInterface
{
    public function getKey(): string;

    public function getRule($header): bool;

    public function getMessage($columnName): string;

    public function validate($cell): bool;
}
