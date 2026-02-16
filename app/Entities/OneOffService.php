<?php

namespace App\Entities;

use MongoDB\Laravel\Eloquent\Model;

/**
 * Class OneOffService.
 *
 * @package namespace App\Entities;
 */
class OneOffService extends Model
{
    protected $connection = 'mongodb';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'identifier',
        'name',
        'enabled',
        'visibility',
        'limits',
        'terms',
        'worker_label',
        'variants',
        'variants_attributes',
        'benefits',
        'other_attributes',
        'images',
        'family_variant',
        'associations',
        'values',
        'documents',
        'videos',
        'groups',
        'categories',
        'family',
    ];

    protected array $dates = [
        'created_at',
        'updated_at'
    ];
}
