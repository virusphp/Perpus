<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Auth;
use App\Book;
use Session;
use File;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\BorrowLog;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\BookException;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $books = Book::with('author');
            return Datatables::of($books)->addColumn('action', function($book){
                    //mengembalikan ke dalam view di datatable/_action
                    return view('datatable._action', [
                        'model' => $book,
                        'form_url' => route('books.destroy', $book->id),
                        'edit_url' => route('books.edit', $book->id),
                        'confirm_message' => 'Yakin mau menghapus' . $book->name. '?'
                ]);
            })->make(true);
        }

        $html = $htmlBuilder
                ->addColumn(['data' => 'title', 'name'=>'title', 'title'=>'Judul'])
                ->addColumn(['data' => 'amount', 'name'=>'amount', 'title'=>'Jumlah'])
                ->addColumn(['data' => 'author.name', 'name'=>'author.name', 'title'=>'Penulis'])
                ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'', 'orderable'=>false, 'searchable'=>false]);

        return view('books.index')->with(compact('html'));
    }

    public function borrow($id)
    {
        try {
            $book = Book::findOrFail($id);
            //memanggail method borrow pada model
            Auth::user()->borrow($book);
            Session::flash('flash_notification', [
                'level' => 'success',
                'message' => 'Berhasil meminjam '. $book->title
            ]);
        } catch (BookException $e) {
            Session::flash('flash_notification', [
                'level' => 'danger',
                'message' => $e->getMessage()
            ]);
        } catch (ModelNotFaundException $e) {
            Session::flash('flash_notification', [
                'level' => 'danger',
                'message' => "Buku Tidak di temukan."
            ]);
        }

        return redirect('/');
    }
    // method bisa di pake buat checklist pake AJAX
    public function returnBack($book_id)
    {
        $borrowLog = BorrowLog::where('user_id', Auth::user()->id)     
                                ->where('book_id', $book_id)
                                ->where('is_returned', 0)
                                ->first();
        if ($borrowLog) {
            $borrowLog->is_returned = true;
            $borrowLog->save();

            Session::flash('flash_notification', [
                'level'=> 'success',
                'message' => 'Berhasil mengembalikan ' . $borrowLog->book->title
            ]);
        }

        return redirect('/home');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBookRequest $request)
    {
        // $this->validate($request, [
        //     'title' => 'required|unique:books,title',
        //     'author_id' => 'required|exists:authors,id',
        //     'amount' => 'required|numeric',
        //     'cover' => 'mimes:jpg,jpeg,bmp,png|max:2048'
        // ]);
        
        $data = $request->except('cover');
        $book = Book::create($data);
        
        $cover = $this->handleRequest($request);
        $book->cover = $cover;
        $book->save();
        
        
        Session::flash('flash_notification', [
            'level' => 'success',
            "message" => "Berhasil menyimpan $book->title"
        ]);

        return redirect()->route('books.index');

    }

    private function handleRequest($request)
    {
        if ($request->hasFile('cover')) 
        {
            $image = $request->file('cover');
            $extension = $image->getClientOriginalExtension();
            $filename = md5(time()) . '.' . $extension;
            $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';
            $image->move($destinationPath, $filename);
        }

        //return $filename;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        return view('books.edit', compact('book'));
    }

    private function removeImage($image)
    {
        if (! empty($image)) {
            $imagePath = public_path() . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR .$image;
            if( file_exists($imagePath)) unlink($imagePath);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBookRequest $request, $id)
    {
        $book = Book::findOrFail($id);

        $oldCover = $book->cover;
        $data = $request->except('cover');
        // $book->update($data);
        if (!$book->update($data)) return redirect()->back();
       
        $cover = $this->handleRequest($request);
        $book->cover = $cover;
        $book->save();
        if ($oldCover !== $book->cover) {
            $this->removeImage($oldCover);
        }
        
         Session::flash('flash_notification', [
                'level' => 'success',
                'message' => 'Buku berhasil di simpan '.$book->title
            ]);

         return redirect()->route('books.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $this->removeImage($book->cover);
        // $book->delete();
        if (!$book->delete()) return redirect()->back();
        Session::flash('flash_notification', [
            'level' => 'success',
            'message' => 'Berhasil Menghapus buku '.$book->cover
        ]);
        return redirect()->route('books.index');
    }
}
