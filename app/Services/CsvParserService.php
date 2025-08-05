<?php

namespace App\Services;

use Exception;

class CsvParserService
{
    public function parse(string $rawInput): array
    {
        if (empty(trim($rawInput))) {
            throw new Exception('Input CSV data is empty.');
        }

        $rows = [];
        $lines = explode("\n", $rawInput);

        array_shift($lines);

        foreach ($lines as $line) {
            $data = str_getcsv($line, ',', '"', '\\');
            if (!empty($data[0])) {
                $rows[] = $data[0];
            }
        }

        return $rows;
    }
}
