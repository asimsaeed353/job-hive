<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicantController extends Controller
{
    // @desc store the job applicant details
    // @route jobs/{job}/apply
    public function store(Request $request, Job $job): RedirectResponse
    {

        // check if user has already applied to the job
        $existingApplicant = Applicant::where('job_id', $job->id)
                                        ->where('user_id', auth()->id())
                                        ->exists();

        if($existingApplicant) {
            return redirect()->back()->with('error', 'You have already applied to this job');
        }
        // Validate request
        $validatedData = $request->validate([
           'full_name' => 'required|string',
           'contact_phone' => 'string',
           'contact_email' => 'required|string|email',
           'message' => 'string',
           'location' => 'string',
           'resume' => 'required|file|mimes:pdf|max:2048',
        ]);

        // check for the file and save the file
        if($request->hasFile('resume')){
            $path = $request->file('resume')->store('resumes', 'public');
            $validatedData['resume_path'] = $path;
        }

        // store the application
        $application = new Applicant($validatedData);
        $application->job_id = $job->id;
        $application->user_id = auth()->id();
        $application->save();


        return redirect()->back()->with('success', 'You application is submitted successfully');
    }

    // @desc delete the applicant
    // @route applicants/{applicant}
    public function destroy($id): RedirectResponse{
        $applicant = Applicant::findOrFail($id);
        $applicant->delete();
        return redirect()->route('dashboard')->with('success', 'Applicant Deleted Successfully');
    }
}
