<?php

namespace App\Http\Controllers;

use App\Exports\JsonExport;
use App\Http\Requests\FileUploadRequest;
use Illuminate\Support\Facades\Queue;
use Maatwebsite\Excel\Facades\Excel;

class FileController extends Controller
{
    public function index()
    {
        return view('files.index');
    }

    public function upload(FileUploadRequest $request)
    {
        if ($request->hasFile('jsonFile')) {
            // Get the uploaded JSON file
            $jsonFile = $request->file('jsonFile');

            // Read the content of the JSON file and decode it
            $jsonData = json_decode(file_get_contents($jsonFile->path()), true);

            if ($jsonData === null) {
                return redirect('dashboard')->with('error', 'Invalid JSON format');
            }

            // Check if $jsonData is empty
            if (empty($jsonData)) {
                return redirect('dashboard')->with('error', 'JSON data is empty');
            }
            // Initialize an empty array to store Excel data
            $excelData = [];

            // Extract the first record to determine the headings
            $firstRecord = reset($jsonData);
            $headings = array_keys($firstRecord);
            $excelData[] = $headings; // Add headings as the first row

            // Add each record's values as a new row in the Excel data
            foreach ($jsonData as $record) {
                $excelData[] = array_values($record);
            }
            $export = new JsonExport($excelData);

            return Excel::download($export, 'invoices.xlsx');
        }
    }
}
