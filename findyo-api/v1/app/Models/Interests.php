<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hashids\Hashids;

class Interests extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'intrests';

    public $timestamps = true;

    protected $hidden = array('created_at', 'updated_at');

    public $appends = ['cid'];

    public function getCIdAttribute()
    {
        $hashids = new Hashids("Interests", 15);
        return $hashids->encode($this->id);
    }
}
