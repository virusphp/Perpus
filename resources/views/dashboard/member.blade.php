@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-tittle">Dashboard</h2>
                    </div>

                    <div class="panel-body">
                        Selamat datang di Larapus 
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td>Buku dipinjam</td>
                                    <td>
                                        @if ($borrowLogs->count() == 0)
                                            Tidak ada buku di pinjam
                                        @endif
                                        <ul>
                                            @foreach($borrowLogs as $borrowLog)
                                                <li>
                                                {!! Form::open(['url' => route('books.return', $borrowLog->book_id),
                                                                'method' => 'put', 'class' => 'form-inline js-confirm',
                                                                 'data-confirm' => "anda yakin hendak mengembalikan ".$borrowLog->book->title . "?"]) !!}
                                                                 
                                                        {{ $borrowLog->book->title }} 
                                                        => {!! Form::submit('Kembalikan', ['class' => 'btn bn-xs btn-default']) !!}
                                                {!! Form::close() !!}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>   
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection