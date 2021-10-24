<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hashids\Hashids;

class UserPostRating extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'userpost_rating';

    public $timestamps = true;

    protected $hidden = array('created_at', 'updated_at');

    public $appends = ['cid'];

    public function getCIdAttribute()
    {
        $hashids = new Hashids("UserPostRating", 15);
        return $hashids->encode($this->id);
    }

    public function UserRatings()
    {
        return $this->hasMany(UserRating::class,'userpost_rating_id');
    }
}
