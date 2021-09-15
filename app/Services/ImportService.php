<?php

namespace App\Services;

use App\Jobs\ImportFileJob;
use App\Models\Company;
use Illuminate\Support\Facades\File;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;

class ImportService
{
    public function run()
    {
        Company::truncate();

        $files = $this->prepareData();

        $batchJobs = [];

        foreach ($files as $file) {
            $batchJobs[] = new ImportFileJob($file->getFilename());
        }

        Bus::batch($batchJobs)
            ->finally(function (Batch $batch) {
                File::deleteDirectory(storage_path("import/chunks"));
            })->dispatch();
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
