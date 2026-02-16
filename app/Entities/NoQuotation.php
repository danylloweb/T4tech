<?php

namespace App\Entities;

use MongoDB\Laravel\Eloquent\Model;

class NoQuotation extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'no_quotations';

    protected $fillable = [
        'ref_id',
        'ref_parent_id',
        'readed'
    ];

    protected $casts = [
        'readed'     => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
