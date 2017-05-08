<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BorrowLog extends Model
{
    protected $fillable = ['book_id', 'user_id', 'is_returned'];
    protected $casts = [
        'is_returned' => 'boolean',
    ];

    //Peminjaman dipunyai buku
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    //Peminjaman dipunyai user
    public function user($value='')
    {
        return $this->belongsTo(User::class);
    }

    public function scopeReturned($query)
    {
        return $query->where('is_returned', 1);
    }

    public function scopeBorrowed($query)
    {
        return $query->where('is_returned', 0);
    }
}
