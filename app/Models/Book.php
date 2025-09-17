<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = ['title','author','published_year','isbn','stock'];

    public function borrowers() {
        return $this->belongsToMany(User::class, 'book_loans')
            ->withPivot(['loaned_at','returned_at'])
            ->withTimestamps();
    }
}
