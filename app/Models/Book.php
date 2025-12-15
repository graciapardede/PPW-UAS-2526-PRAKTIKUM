<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'publisher',
        'year',
        'description',
        'stock',
        'available_stock',
    ];

    protected $casts = [
        'year' => 'integer',
        'stock' => 'integer',
        'available_stock' => 'integer',
    ];

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
}
