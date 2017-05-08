<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;

class Book extends Model
{
    protected $fillable = ['title', 'author_id', 'amount'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function borrowLogs()
    {
        return $this->hasMany(BorrowLog::class);
    }

    public function getStockAttribute()
    {
        $borrowed = $this->borrowLogs()->borrowed()->count();
        $stock = $this->amount - $borrowed;
        return $stock;
    }

    public static function boot()
    {
        Parent::boot();

        self::updating(function($book){
            if ($book->amount < $book->borrowed) {
                Session::flash('flash_notification',[
                    'level' => 'danger',
                    'message' => 'Jumlah buku'. $book->title .'Harus >=' . $book->borrowed
                ]);

                return false;
            }
        });

        self::deleting(function($book) {
            if ($book->borrowLogs()->count() > 0) {
                Session::flash('flash_notification', [
                    'level' => 'danger',
                    'message' => "Buku $book->title sudah pernah di pinjam."
                ]);
                return false;
            }
        });
    }

    public function getBorrowedAttribute()
    {
        return $this->borrowLogs()->borrowed()->count();
    }
}
