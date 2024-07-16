<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function getBooks() {

        $books = Book::
        where('publisher_id', $this->id)
        ->where('status', 1)
        ->get();

        return $books;
    }

    public function getAdmins() {

        $records = RoleUser::where('publisher_id', $this->id)->get();

        $admins = collect();

        foreach ($records as $r) {
            $admins->add($r->getUser());
        }

        return $admins;
    }
    
}
