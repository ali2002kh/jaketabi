<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function getPermissions() {

        $permissions = collect();
        $records = PermissionRole::where('role_id', $this->id)->get();

        foreach($records as $r) {
            $p = $r->getPermission();
            $permissions->add($p);
        }

        return $permissions;
    }
}
