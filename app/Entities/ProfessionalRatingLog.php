<?php

namespace App\Entities;

use MongoDB\Laravel\Eloquent\Model;

class ProfessionalRatingLog extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'professional_rating_logs';

    protected $fillable = [
        'author_id',
        'author_email',
        'author_name',
        'professional_rating_id',
        'old_comment',
        'comment',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
