<?php

namespace App\Filters\Posts;

class PostTypeFilter
{
    public function filter($builder, $value)
    {
        return $builder->where('post_type', $value);
    }
}
