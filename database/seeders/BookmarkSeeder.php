<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Job;

class BookmarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // get the test user
        $testUser = User::where('email', 'test@test.com')->firstOrFail();

        // get the job ids
        $jobIds = Job::pluck('id')->toArray();

        // select the random jobIds
        $randomJobIds = array_rand($jobIds, 3);

        // Attach selected jobs as the bookmarks for the test user
        foreach($randomJobIds as $jobId){
            $testUser->bookmarkedJobs()->attach($jobIds[$jobId]);
        }
    }
}
