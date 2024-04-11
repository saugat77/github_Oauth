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
        /* After the Request is checked from FileUploadRequest it gets redirected here
        and then i used jsonFile->path() is used to access the files content directly when
        using file_get_contents method and json decode is used so that it can make every object into array form and
         if it is found null it returns back with erorr message*/

        $jsonFile = $request->file('jsonFile');

        // Read the content of the JSON file and decode it
        $jsonData = json_decode(file_get_contents($jsonFile->path()), true);

        if ($jsonData === null) {
            return redirect('dashboard')->with('error', 'Invalid JSON format');
        }


        if (empty($jsonData)) {
            return redirect('dashboard')->with('error', 'JSON data is empty');
        }
        /*Here I have initialized a array so that for each json decoded
        value we got in array to be saved in exceldata array variable and at first
        record we only take first record of the array and then from the first record we
         take the heading from the key becoz the dummy json of the users is in same format
         and then add the heading at first in the array after that we run foreach loop to take
          all the array present and push it to exceldata and after the loop we create export function to export the data
         and after that we download it using the maatwebsite package to download it in Excel Format
          */
        $excelData = [];

        $firstRecord = reset($jsonData);
        $headings = array_keys($firstRecord);
        $excelData[] = $headings;

        foreach ($jsonData as $record) {
            $excelData[] = array_values($record);
        }
        $export = new JsonExport($excelData);

        return Excel::download($export, 'export.xlsx');
    }
}
