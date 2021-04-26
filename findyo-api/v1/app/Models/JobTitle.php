<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class JobTitle extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'job_titles';

    public $timestamps = true;

    protected $hidden = array('created_at', 'updated_at');

    public $appends = ['CId'];

    public function getCIdAttribute()
    {
        return Crypt::encryptString($this->id);
    }
}
