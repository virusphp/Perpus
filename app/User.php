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
use Session;

class User extends Authenticatable
{
    // use Notifiable;

    use EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_verified' => 'boolean',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function borrowLogs()
    {
        return $this->hasMany('App\BorrowLog');
    }

    public function borrow(Book $book)
    {
        // cek apakah masih ada stok buku
        if ($book->stock < 1) {
            throw new BookException("Buku $book->title sedang tidak tersedia.");
        }
        // cek apakah buku ini sedang dipinjam oleh user
        if($this->borrowLogs()->where('book_id',$book->id)->where('is_returned', 0)->count() > 0 ) {
            throw new BookException("Buku $book->title sedang Anda pinjam.");
        }

        $borrowLog = BorrowLog::create(['user_id'=>$this->id, 'book_id'=>$book->id]);
        return $borrowLog;
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
    
    public function sendVerification()
    {
        $token = $this->generateVerificationToken();
        $user = $this;

        Mail::send('auth.emails.verification', compact('user', 'token'), function ($m) use ($user) {
            $m->to($user->email, $user->name)->subject('Verifikasi Akun Larapus');
        });
    }
    
    public function verify()
    {
        $this->is_verified = 1;
        $this->verification_token = null;
        $this->save();
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function($member){
            
            // jika member peminjaman member lebih dari 1
            if ($member->borrowLogs->count() > 0 ) {
                $html = "Member masih meminjam buku sebanyak :";
                $book = $member->borrowLogs->count();
                $html .= "<br> $book Buku";
                $html .= "<ul>";

                // foreach($member->borrowLogs as $book) {
                //     $html .= "<li>$book->user_id</li>";
                // }
                $html .= '</ul>';
                Session::flash("flash_notification", [
                    "level"=>"danger",
                    "message"=>$html
                ]);

                return false;
            }
        });

    }
}
