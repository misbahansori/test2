<?php

namespace Misbah\ExcelValidator;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ExcelValidator
{
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
                $cell = $currentRow[$columnNumber];
                var_dump($cell);
            }
        }
        // return $activeSheet;
    }

    public function getrules($headers)
    {
        return array_map(function($header) {
            if (strpos($header, '*') !== false) {
                return 'required';
            }
            if (strpos($header, '#') !== false) {
                return 'no_space';
            }
        }, $headers);
    }
}
