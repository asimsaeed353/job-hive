<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpFoundation\RedirectResponse;

class JobController extends Controller
{
    // Implement JobPolicies
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $jobs = Job::latest()->paginate(6);
        return view('jobs.index')->with('jobs', $jobs);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) : RedirectResponse
    {
        $validatedData = $request->validate([
           'title' => 'required|string|max:255',
            'description' => 'required|string',
            'salary' => 'required|integer',
            'tags' => 'nullable|string',
            'job_type' => 'required|string',
            'remote' => 'required|boolean',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zipcode' => 'nullable|string',
            'contact_email' => 'required|string',
            'contact_phone' => 'nullable|string',
            'company_name' => 'required|string',
            'company_description' => 'nullable|string',
            'company_logo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'company_website' => 'nullable|url'
        ]);

        // Hardcoded user ID
        $validatedData['user_id'] = auth()->user()->id;

        // Check for the file, store it under /public and store the path
        if($request->hasFile('company_logo')){

            // Store the file and save its path
            $path = $request->file('company_logo')->store('logos', 'public');
            // stored in /public/logos

            // Store the path in the database
            $validatedData['company_logo'] = $path;
        }

       // Submit data to the database
        Job::create($validatedData);

        return redirect()->route('jobs.index')->with('success', 'Job Listing Created Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job) : View
    {
        return view('jobs.show')->with('job', $job);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Job $job): View
    {
        // check if user is authorized to do this action (using JobPolicy)
        $this->authorize('update', $job);

        return view('jobs.edit')->with('job', $job);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Job $job)
    {

        // check if user is authorized to do this action (using JobPolicy)
        $this->authorize('update', $job);

        $validatedData = $request->validate([
           'title' => 'required|string|max:255',
            'description' => 'required|string',
            'salary' => 'required|integer',
            'tags' => 'nullable|string',
            'job_type' => 'required|string',
            'remote' => 'required|boolean',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zipcode' => 'nullable|string',
            'contact_email' => 'required|string',
            'contact_phone' => 'nullable|string',
            'company_name' => 'required|string',
            'company_description' => 'nullable|string',
            'company_logo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'company_website' => 'nullable|url'
        ]);

        // Check for the file, store it under /public and store the path
        if($request->hasFile('company_logo')){

            Storage::delete('public/logos/' . basename($job->logo));

            // Store the file and save its path
            $path = $request->file('company_logo')->store('logos', 'public');
            // stored in /public/logos

            // Store the path in the database
            $validatedData['company_logo'] = $path;
        }

       // Submit data to the database
        $job->update($validatedData);

        return redirect()->route('jobs.index')->with('success', 'Job Listing Created Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job) : RedirectResponse
    {
        // check if user is authorized to do this action (using JobPolicy)
        $this->authorize('delete', $job);

        if($job->company_logo){
            Storage::delete('public/logos/' . $job->company_logo);
        }

        $job->delete();

        // check if the request has come from the dashboard
        if(request()->query('from') == 'dashboard'){
            return redirect()->route('dashboard')->with('success', 'Job Listing Deleted Successfully!');
        }

        return redirect()->route('jobs.index')->with('success', 'Job Listing Deleted Successfully!');
    }
}
