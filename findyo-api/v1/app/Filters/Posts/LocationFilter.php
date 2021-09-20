<?php

namespace App\Filters\Posts;

class LocationFilter
{
    public function filter($builder, $value)
    {
        return $builder->where('location_id', $value);
    }
}
