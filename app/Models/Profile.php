<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'image',
        'birth_date',
        'user_id',
    ];

    public function getUser() {

        return User::find($this->user_id);
    }
}
