<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hashids\Hashids;

class UserRating extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'user_rating';

    public $timestamps = true;

    protected $hidden = array('created_at', 'updated_at');

    public function UserPostRating()
    {
        return $this->belongsTo(UserPostRating::class,'id');
    }

    public function AdminRating()
    {
        return $this->belongsTo(AdminRating::class,'id');
    }
}
