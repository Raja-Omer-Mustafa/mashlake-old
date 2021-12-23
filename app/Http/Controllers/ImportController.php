<?php

namespace App\Http\Controllers;
use App\Imports\CandidateImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;
use App\User;
use Modules\Job\Models\Job;
use Modules\Job\Models\JobCandidate;
use Modules\Candidate\Models\CandidateCvs;
use Auth;
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
        ];
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlx,xls',
        ],$messages);
        
        try{
            Excel::import(new CandidateImport,request()->file('file')); 
        }catch ( ValidationException $e ){

        return response()->json(['success'=>'errorList','message'=> $e->errors()]);
        }
          
        return redirect()->route('import.View')->with('success','Candidates import successfully!');
    }
    public function AssignJob(Request $request)
    {
        $candidate = User::find($request->input('candidate-id'));
        $cv_id = CandidateCvs::where('origin_id', '=', $candidate->id)->first();
        if(empty($cv_id->id)){
            return redirect()->back()->with('error', __("Choose a cv"));
        }
        if(empty($request->input('message'))){
            return redirect()->back()->with('error','please enter a message');
        }

        else {
            $candidateJob =Job::find($request->job);
            $user = JobCandidate::create([
            'job_id' =>$candidateJob->id,
            'company_id' => $candidateJob->company_id,
            'candidate_id' => $request->input('candidate-id'),
            'cv_id' => $cv_id->id,
            'status' => 'pending',
            'create_user' => Auth::user(),
            'message' => $request->input('message')

            ]);
         } 
         return redirect()->route('candidate.admin.index')->with('success','job assigned successfully!');

       

    }
}
