<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load job listings from file
        $jobListings = include database_path('seeders/data/job_listings.php');

        // Get user ids from user variable
        $userIds = User::pluck('id')->toArray();
        // pluck() => give a field value from the database
        // pluck() => gives all values of id field

        foreach ($jobListings as &$listing) {
            // Assign random user id to each job listing
            $listing['user_id'] = $userIds[array_rand($userIds)];

            // Add timestamp
            $listing['created_at'] = now();
            $listing['updated_at'] = now();
        }

        // Insert Job Listing
        DB::table('job_listings')->insert($jobListings);
        echo 'Jobs created successfully!';
    }
}
