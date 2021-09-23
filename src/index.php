<?php

use Misbah\ExcelValidator\ExcelValidator;

require './vendor/autoload.php';

// Change the file variable accordingly

// $file = 'storage/Type_A.xlsx';
// $file = 'storage/Type_B.xlsx';
$file = 'storage/Type_C.xls';

$validator = new ExcelValidator();

$result = $validator->validate($file);

print_r($result);