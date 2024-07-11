<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'permission_id',
        'role_id',
    ];
    
    public function getPermission() {
        return Permission::find($this->permission_id);
    }

    public function getRole() {
        return Role::find($this->role_id);
    }
}
