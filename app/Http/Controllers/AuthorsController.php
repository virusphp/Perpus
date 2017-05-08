<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Author;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Session;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;

class AuthorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $authors = Author::select(['id', 'name']);
            return Datatables::of($authors)
                // membuat colom action dengan melempar data author
                ->addColumn('action', function($author){
                    //mengembalikan ke dalam view di datatable/_action
                    return view('datatable._action', [
                        'model' => $author,
                        'form_url' => route('authors.destroy', $author->id),
                        'edit_url' => route('authors.edit', $author->id),
                        'confirm_message' => 'Yakin mau menghapus' . $author->name. '?'
                ]);
            })->make(true);
        }
        $html = $htmlBuilder
                    ->addColumn(['data' => 'name', 'name'=>'name', 'title'=>'Nama'])
                    ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'', 'orderable'=>false, 'searchable'=>false]);
       
        return view('authors.index')->with(compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('authors.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAuthorRequest $request)
    {
       
        $author = Author::create($request->only('name'));
        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Berhasil menyimpan $author->name"
        ]);
        return redirect()->route('authors.index');
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
        $author = Author::findOrFail($id);
        return view('authors.edit', compact('author'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAuthorRequest $request, $id)
    {
        $author = Author::findOrFail($id);
        // $this->validate($request, [
        //     'name' => 'required|unique:authors,name,'.$id
        // ]);
        $author->update($request->only('name'));
        Session::flash('flash_notification', [
            'level' => 'success',
            'message' => 'berhasil menyimpan '.$author->name
        ]);
        return redirect()->route('authors.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $author = Author::findOrFail($id);

        if(!$author->delete()) return redirect()->back();

        Session::flash('flash_notification', [
            'level' => 'success',
            'message' => 'berhasil Menghapus '. $author->name
        ]);
        
        return redirect()->route('authors.index');
    }
}
