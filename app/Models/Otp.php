<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'user_id',
        'otp_code',
        'number',
        'used',
        'status',
    ];

    public function getUser() {
        return User::find($this->user_id);
    }
}
