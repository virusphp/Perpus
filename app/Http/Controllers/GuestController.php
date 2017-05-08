<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use App\Book;
use Entrust;

class GuestController extends Controller
{
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) 
        {
            $books = Book::with('author');
            return Datatables::of($books)
                    ->addColumn('stock', function($book) {
                        return $book->stock;
                    })
                    ->addColumn('action', function($book) {
                        if (Entrust::hasRole('admin')) return '';
                        return '<a href="'.route('books.borrow', $book->id).'" class="btn btn-xs btn-primary">Pinjam</a>';
                    })->make(true);
        }

        $html = $htmlBuilder
                ->addColumn(['data' => 'title', 'name'=>'title', 'title'=>'Judul'])
                ->addColumn(['data' => 'stock', 'name'=>'stock', 'title' => 'Stok', 'orderable'=>false, 'searchable' => false])
                ->addColumn(['data' => 'author.name', 'name'=>'author.name', 'title'=>'Penulis'])
                ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'', 'orderable'=>false, 'searchable'=>false]);

        return view('guest.index')->with(compact('html'));
    }
}
