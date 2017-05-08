@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li class="active">Buku</li>
                </ul>
                
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h class="panel-title">Buku</h>
                    </div>

                    <div class="panel-body">
                        <p><a href="{{ route('books.create') }}" class="btn btn-primary">Tambah</a></p>

                        {!! $html->table(['class' => 'table-hover']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {!! $html->scripts() !!}
@endsection