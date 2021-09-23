<?php

namespace Misbah\ExcelValidator;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Misbah\ExcelValidator\Validator\NoSpace;
use Misbah\ExcelValidator\Validator\Required;
use Misbah\ExcelValidator\Validator\ValidatorInterface;

class ExcelValidator
{
    protected $validators = [
        NoSpace::class,
        Required::class,
    ];

    protected $errorBag = [];

    public function validate($path)
    {
        $this->errorBag = [];
        $reader = new Xlsx();
        $spreadsheet = $reader->load($path);
        $activeSheet = $spreadsheet->getActiveSheet()->toArray();
        $headers = $activeSheet[0];

        $rules = $this->getRules($headers);

        // Row number start from 1 to exclude header row
        for ($rowNumber = 1; $rowNumber < count($activeSheet); $rowNumber++) {
            $currentRow = $activeSheet[$rowNumber];
            for ($columnNumber = 0; $columnNumber < count($currentRow); $columnNumber++) {
                if ($rules[$columnNumber] === null) {
                    continue;
                }

                $cell = $currentRow[$columnNumber];

                $this->validateCell($cell, $rules[$columnNumber], $rowNumber + 1, $headers[$columnNumber]);
            }
        }

        return $this->getErrors();
    }

    public function getrules($headers): array
    {
        return array_map(function ($header) {
            foreach ($this->validators as $validator) {
                $instance = new $validator();
                if ($instance->getRule($header)) {
                    return $instance->getKey();
                }
            }
        }, $headers);
    }

    public function validateCell($cell, $rule, $rowNumber, $columnName)
    {
        $columnName = str_replace(['*', '#'], '', $columnName);

        foreach ($this->validators as $validator) {
            $instance = new $validator();
            if ($rule === $instance->getKey()) {
                if ($instance->validate($cell)) {
                    $this->addError($instance->getMessage($columnName), $rowNumber);
                }
            }
        }
    }

    public function addError($message, $rowNumber)
    {
        $this->errorBag[$rowNumber][] = $message;
    }

    public function getErrors()
    {
        return $this->errorBag;
    }
}
