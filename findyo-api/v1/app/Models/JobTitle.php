<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hashids\Hashids;

class JobTitle extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'job_titles';

    public $timestamps = true;

    protected $hidden = array('created_at', 'updated_at');

    public $appends = ['cId'];

    public function getCIdAttribute()
    {
        //Controller name should be provided
        $hashids = new Hashids("PostCategory", 15);
        return $hashids->encode($this->id);
    }
}
