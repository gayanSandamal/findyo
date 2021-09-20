<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Hashids\Hashids;
use App\Filters\Posts\PostFilter;

class Post extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    protected $table = 'posts';

    public $timestamps = true;

    protected $hidden = array('created_at', 'updated_at');

    public $appends = ['cid'];

    public function getCIdAttribute()
    {
        //Controller name should be provided
        $hashids = new Hashids("Post", 15);
        return $hashids->encode($this->id);
    }

    public function PostImages()
    {
        return $this->hasMany(PostImagers::class, 'post_id');
    }
    public function scopeFilter(Builder $builder, $request)
    {
        return (new PostFilter($request))->filter($builder);
    }
}
