<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hashids\Hashids;

class LocationLevel extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    protected $table = 'location_levels';

    public $timestamps = true;

    protected $hidden = array('created_at', 'updated_at');

    public $appends = ['cid'];

    public function getCIdAttribute()
    {
        $hashids = new Hashids("LocationLevel", 15);
        return $hashids->encode($this->id);
    }

    public function Locations()
    {
        return $this->hasMany(Location::class,'parent');
    }
}
