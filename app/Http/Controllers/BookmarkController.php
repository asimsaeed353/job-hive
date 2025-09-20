<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookmarkController extends Controller
{
    public function index(): View
    {
            $user = Auth::user();

            $bookmarks = $user->bookmarkedJobs()->orderBy('job_user_bookmarks.created_at', 'desc')->paginate(9);
            return view('jobs.bookmarked')->with('bookmarks', $bookmarks);
    }

    // @desc create new bookmarked job
    // @route POST /bookmarks/{job}
    public function store(Job $job): RedirectResponse
    {
            $user = Auth::user();

            // Check if user already has this job bookmarked
            if($user->bookmarkedJobs()->where('job_id', $job->id)->exists()){
                return back()->with('error', 'Job is already bookmarked.');
            }

            $user->bookmarkedJobs()->attach($job->id);

            return back()->with('success', 'Job is bookmarked successfully');
    }

    // @desc remove new bookmarked job
    // @route DELETE /bookmarks/{job}
    public function destroy(Job $job): RedirectResponse
    {
            $user = Auth::user();

            // Check if user already has this job bookmarked
            if(!$user->bookmarkedJobs()->where('job_id', $job->id)->exists()){
                return back()->with('error', 'Job is not bookmarked.');
            }

            $user->bookmarkedJobs()->detach($job->id);

            return back()->with('success', 'Bookmark removed successfully');
    }
}
