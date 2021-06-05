<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hashids\Hashids;

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
        return $this->hasMany(PostImagers::class,'post_id');
    }
}
