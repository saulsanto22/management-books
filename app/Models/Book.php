<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
        use HasFactory;
    
     protected $fillable = ['title', 'author', 'published_year', 'isbn', 'stock'];

    public function borrowers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'book_loans')
                    ->withPivot(['borrowed_at', 'returned_at'])
                    ->withTimestamps();
    }
}
