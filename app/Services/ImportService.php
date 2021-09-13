<?php

namespace App\Services;

use App\Jobs\ImportFileJob;
use App\Models\Company;
use Illuminate\Support\Facades\File;

class ImportService
{
    public function run()
    {
        Company::truncate();

        $files = $this->prepareData();

        foreach ($files as $file) {
            ImportFileJob::dispatch($file->getFilename());
        }
    }

    protected function prepareData()
    {
        $chunksPath = storage_path('import/chunks');
        File::isDirectory($chunksPath) or File::makeDirectory($chunksPath, 0777);

        $file = storage_path('import/brreg-companies.csv');
        $newFile = storage_path('import/chunks/chunk_');
        shell_exec("split -l 10000 {$file} {$newFile}");

        return File::files($chunksPath);
    }
}
