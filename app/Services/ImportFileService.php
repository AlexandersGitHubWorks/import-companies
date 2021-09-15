<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Support\Facades\File;

class ImportFileService
{
    protected $filePath;

    public function __construct($file)
    {
        $this->filePath = $file;
    }

    public function run()
    {
        $csvFile = storage_path("import/chunks/{$this->filePath}");

        $fh = fopen($csvFile, 'r') or die("could not open $csvFile.");

        while ($line = fgetcsv($fh, 0, ';')) {
            if (! is_numeric($line[0])) continue;

            $company = new Company();
            $company->orgnumber = $line[0];
            $company->name = $line[1];
            $company->save();
        }

        fclose($fh);
    }
}
