<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Applicant extends Model
{
    protected $fillable = [
        'user_id',
        'job_id',
        'full_name',
        'contact_phone',
        'contact_email',
        'message',
        'location',
        'resume_path',
    ];

    // Relation to Job - an applicant belongs to a job
    public function job(): BelongsTo {
        return $this->belongsTo(Job::class);
    }

    // Relation to User - an applicant belongs to a user
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
