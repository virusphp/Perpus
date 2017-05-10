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
                        Seleamat Datang di menu Administrasi Larapus. Silahkan PIlih Menu Administrasi yang di inginkan
                        <hr>
                        <h4>Data Statistik</h4>
                        <canvas id="chartPenulis" width="400" height="400"></canvas>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/Chart.min.js') }}"></script>
    <script>
    

        var data = {
            labels: {!! json_encode($authors) !!},
            datasets: [
                {
                    fillColor : "rgba(151,187,205,0.5)",
                    strokeColor : "rgba(151,187,205,0.8)",
                    pointColor : "rgba(220,220,220,1)",
                    pointStrokeColor : "#fff",
                    pointHighlightFill : "#fff",
                    pointHighlightStroke : "rgba(220,220,220,1)",
                    highlightFill : "rgba(151,187,205,0.75)",
                    highlightStroke : "rgba(151,187,205,1)",
                    data : {!! json_encode($books) !!}
                }
            ]
        }

        var ctx = document.getElementById('chartPenulis').getContext('2d');
        var myLineChart = new Chart(ctx).Radar(data);
        
    </script>
@endsection