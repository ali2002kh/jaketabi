<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role_id',
    ];

    public function getUser() {
        return User::find($this->user_id);
    }

    public function getRole() {
        return Role::find($this->role_id);
    }
}
