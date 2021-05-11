<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hashids\Hashids;

class Skill extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'skills';

    protected $hidden = array('created_at', 'updated_at');

    public $timestamps = true;

    public $appends = ['cid'];

    public function getCidAttribute()
    {
        //Controller name should be provided
        $hashids = new Hashids("Skills", 15);
        return $hashids->encode($this->id);
    }
}
