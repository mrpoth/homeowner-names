<?php

namespace App\Services;

class CsvParserService
{
    public function parse(string $filename): array
    {
        $rows = [];

        if (($handle = fopen($filename, 'r')) !== false) {
            fgetcsv($handle);

            while (($data = fgetcsv($handle)) !== false) {
                $rows[] = $data[0];
            }
            fclose($handle);
        }

        return $rows;
    }
}