<?php

namespace Misbah\ExcelValidator;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ExcelValidator
{
    protected $errorBag = [];

    public function validate($path)
    {
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

    public function getrules($headers)
    {
        return array_map(function ($header) {
            if (strpos($header, '*') !== false) {
                return 'required';
            }
            if (strpos($header, '#') !== false) {
                return 'no_space';
            }
        }, $headers);
    }

    public function validateCell($cell, $rule, $rowNumber, $columnName)
    {
        $columnName = str_replace(['*', '#'], '', $columnName);

        if ($rule === 'required') {
            if (empty($cell)) {
                $this->addError("Missing value in field $columnName", $rowNumber);
            }
        }
        if ($rule === 'no_space') {
            if (strpos($cell, ' ') !== false) {
                $this->addError("$columnName should not contain space", $rowNumber);
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
