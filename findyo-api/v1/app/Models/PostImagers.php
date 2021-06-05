<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hashids\Hashids;

class PostImagers extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    protected $table = 'post_imagers';

    public $timestamps = true;

    protected $hidden = array('created_at', 'updated_at');

    public function Post()
    {
        return $this->belongsTo(Post::class,'id');
    }
}
