<?php

namespace App\Http\Controllers;
use App\Imports\CandidateImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;
class ImportController extends Controller
{
     /**
    * @return \Illuminate\Support\Collection
    */


      public function importExportView()
    {
       return view('import');
    }
    public function import(Request $request) 
    {
        $messages = [
            'file.required'      => 'The  csv file is required.',
            'file.mimes'      => 'file type is not csv.',
        ];
        $request->validate([
            'file' => 'max:10240|required|mimes:csv,xlsx',
        ],$messages);
        
        try{
            Excel::import(new CandidateImport,request()->file('file')); 
        }catch ( ValidationException $e ){

        return response()->json(['success'=>'errorList','message'=> $e->errors()]);
        }
          
        return redirect()->route('import.View')->with('success','Candidates import successfully!');
    }
}
