<?php

namespace App\Http\Controllers;

use App\Jobs\ImportJob;
use App\Models\Company;

class HomeController extends Controller
{
    public function index()
    {
        return view('welcome', [
            'companies' => Company::paginate(20),
        ]);
    }

    public function import()
    {
        //TODO: download file of companies data (use guzzle)
        //Done: display flash message about starting import
        //TODO: prevent duplication of import process
        //TODO: Import Service via contract

        ImportJob::dispatch();

        return redirect()->home()->with('message', 'Import is started!');
    }
}
