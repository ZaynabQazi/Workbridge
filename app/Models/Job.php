<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
        'company_id', 'category_id', 'title', 'description', 'requirements',
        'salary_range', 'location', 'employment_type', 'deadline', 'status', 'approved_at',
    ];

    protected $casts = [
        'deadline' => 'date',
        'approved_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function savedBy()
    {
        return $this->hasMany(SavedJob::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
