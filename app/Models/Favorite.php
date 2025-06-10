<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    protected $fillable = [
        'user_id',
        'username',
        'name',
        'professional_headline',
        'location',
        'picture_url',
        'opportunity_id',
        'type',
        'summary',
        'company_name',
        'min_salary',
        'max_salary'
    ];

    protected $casts = [
        'min_salary' => 'decimal:2',
        'max_salary' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function isProfile(): bool
    {
        return $this->type === 'profile';
    }
    
    public function isOpportunity(): bool
    {
        return $this->type === 'opportunity';
    }
}
