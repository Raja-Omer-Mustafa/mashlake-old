<?php

namespace App\Http\Controllers;
use App\Imports\CandidateImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class ImportController extends Controller
{
     /**
    * @return \Illuminate\Support\Collection
    */
    public function import(Request $request) 
    {
        // $validated = $request->validate([
        // 'file' => 'required|mimes:csv,txt',
        // ]);
        

        Excel::import(new CandidateImport,request()->file('file'));   
        return redirect()->route('admin.index')->with('success','Candidates import successfully!');
    }
}
