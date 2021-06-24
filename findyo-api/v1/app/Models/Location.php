<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hashids\Hashids;

class Location extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'locations';

    public $timestamps = true;

    protected $hidden = array('created_at', 'updated_at');

    public $appends = ['cid'];

    public function getCIdAttribute()
    {
        $hashids = new Hashids("Location", 15);
        return $hashids->encode($this->id);
    }

    public function LocationLevel()
    {
        return $this->belongsTo(LocationLevel::class,'location_level_id');
    }
}
