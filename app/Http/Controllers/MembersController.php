<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\User;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Facades\Datatables;

class MembersController extends Controller
{
    //

    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $member = Role::where('name', 'member')->first()->users;
            return Datatables::of($member)
                    ->addColumn('action', function($member) {
                        return view('datatable._member-action', compact('member'));
                    })->make(true);
        }

        $html = $htmlBuilder
                    ->addColumn(['data' => 'name', 'name'=>'name', 'title'=>'Nama'])
                    ->addcolumn(['data'=> 'email', 'name'=>'email', 'title' => 'Email'])
                    ->addColumn(['data'=> 'action', 'name'=>'action', 'title' => '', 'orderable'=>false, 'searchable' => false]);
        
        return view('members.index', compact('html'));

    }

    public function show($id)
    {
        $member = User::find($id);
        return view('members.show', compact('member'));
    }

    public function destroy($id)
    {
        # code...
    }
}
