<?php

namespace App\Filters\Posts;

class CategoryFilter
{
    public function filter($builder, $value)
    {
        return $builder->where('category_id', $value);
    }
}
