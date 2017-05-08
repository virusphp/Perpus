<?php

use Illuminate\Database\Seeder;
use App\Author; 
use App\Book;
use App\BorrowLog;
use App\User;

class BooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //contoh penulis
        $author1 = Author::create(['name' => 'Slamet Sugandi']);
        $author2 = Author::create(['name' => 'Aman Setya Budi']);
        $author3 = Author::create(['name' => 'Triastono Taufiq']);

        //contoh buku
        $book1 = Book::create(['title' => 'Indahnya Menikah Dini', 'amount' => 2, 'author_id' => $author1->id]);
        $book2 = Book::create(['title' => 'Bermain Sambil Belajar', 'amount' => 2, 'author_id' => $author2->id]);
        $book3 = Book::create(['title' => 'Berkah setelah menikah', 'amount' => 2, 'author_id' => $author3->id]);
        $book4 = Book::create(['title' => 'Menikah Menjamin Pintu Rizki', 'amount' => 2, 'author_id' => $author1->id]);


        //ocntoh peminjaman buku
        $member = User::where('email', 'member@gmail.com')->first();
        BorrowLog::create(['user_id' => $member->id, 'book_id' => $book1->id, 'is_returned' => 0]);
        BorrowLog::create(['user_id' => $member->id, 'book_id' => $book2->id, 'is_returned' => 0]);
        BorrowLog::create(['user_id' => $member->id, 'book_id' => $book3->id, 'is_returned' => 1]);
    }
}
