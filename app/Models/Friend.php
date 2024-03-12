<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender',
        'receiver',
        'accepted',
    ];

    public function getSender() {
        return User::find($this->sender);
    }

    public function getReceiver() {
        return User::find($this->receiver);
    }
}
