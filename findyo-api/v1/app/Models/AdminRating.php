<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hashids\Hashids;

class AdminRating extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'adminrating';

    public $timestamps = true;

    protected $hidden = array('created_at', 'updated_at');

    public $appends = ['cid'];

    public function getCIdAttribute()
    {
        $hashids = new Hashids("AdminRating", 15);
        return $hashids->encode($this->id);
    }
}
