<?php

namespace App\Services;

class PersonParserService
{
    public function getHomeOwnerNames(string $input)
    {
        $namesFromCsv = $this->csvParser($input);
    
    }

    private function csvParser(string $filename): array
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
