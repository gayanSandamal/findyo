<?php

namespace App\Filters\Posts;

use App\Filters\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;

class PostFilter extends AbstractFilter
{
    protected $filters = [
        'category_id' => CategoryFilter::class,
        'location_id' => LocationFilter::class,
        'post_type' => PostTypeFilter::class
    ];
}
