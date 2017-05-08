<?php
// @ts-check
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use App\Book;
use App\BorrowLog;
use App\Exceptions\BookException;
// use Illuminate\Support\Facades\Mail;
use Mail;

class User extends Authenticatable
{
    use Notifiable;

    use EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function borrowLogs()
    {
        return $this->hasMany(BorrowLog::class);
    }

    public function borrow(Book $book)
    {
        //cek masih ada stok buku atau tidak
        if ($book->stock < 1) {
            throw new BookException("Buku $book->title sedang tidak tersedia.");
        }
        // mengecek buku ini sedang di pinjam
        if ($this->borrowLogs()->where('book_id', $book->id)->where('is_returned', 0)->count() > 0)
        {
            throw new BookException("Buku $book->title sedang Anda Pinjam.");
        }
        $borrowLog = BorrowLog::create(['user_id'=>$this->id, 'book_id'=>$book->id]);
        return $borrowLog;
    }

    public function sendVerification()
    {   
        $token = $this->generateVerificationToken();
        $user = $this;
        // Mail::send('auth.emails.verification', compact('user', 'token'), function ($m) use ($user) {
        //     $m->to($user->email, $user->name)->subject('Verifikasi Akun Larapus');
        // });
        Mail::send('auth.emails.verification', ['user' => $user, 'token' => $token], function ($m) use ($user) {
            $m->from('hello@app.com', 'Your Application');

            $m->to($user->email, $user->name)->subject('Verifikasi Akun Larapus');
        });
    }

    public function verify()
    {
         $this->is_verified = 1;
         $this->verification_token = null;
         $this->save();
    }

    public function generateVerificationToken()
    {
         $token = $this->verification_token;
         if (!$token) {
             $token = str_random(40);
             $this->verification_token = $token;
             $this->save();
         }
         return $token;
    }
}
