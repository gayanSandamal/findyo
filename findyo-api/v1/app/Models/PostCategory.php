<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hashids\Hashids;

class PostCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'post_category';

    public $timestamps = true;

    protected $hidden = array('created_at', 'updated_at');

    public $appends = ['cid'];

    public function getCIdAttribute()
    {
        //Controller name should be provided
        $hashids = new Hashids("JobTitle", 15);
        return $hashids->encode($this->id);
    }
}
