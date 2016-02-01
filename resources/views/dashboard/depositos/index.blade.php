@extends('layouts.dashboard')

@section('content')

    <section id="main-content">
        <section class="wrapper">

            <div class="row mt">
                <div class="col-lg-12">
              
                    <div class="border-head">
                        <h3>Listagem de Depósitos</h3>
                    </div>
                
                    
                    <div class="content-panel">

                            @if ($depositos->count())

                                <table id="table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Utilizador</th>
                                            <th>Valor</th>
                                            <th>Data</th>
                                            <th>Opções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($depositos as $deposito)
                                            <tr>
                                                <td>{{ $deposito->jogadorConta->utilizador }}</td>                                         
                                                <td>{{ $deposito->credito }}</td>                                         
                                                <td>{{ $deposito->data }}</td>                                       
                                                <td> 
                                                    <a href="/dashboard/movimentos/{{$deposito->id}}" class="btn btn-sm btn-info"><i class="fa fa-list"></i> Ver</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Utilizador</th>
                                            <th>Valor</th>
                                            <th>Data</th>
                                            <th>Opções</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            @else
                                <div class="alert alert-info alert-dismissable">
                                    <i class="fa fa-info"></i>
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    Não existem depósitos.
                                </div>
                            @endif
                    </div>
                </div>
                
            </div>
        </section>
    </section>

@stop

@section('scripts')

    <!-- DATA TABLES SCRIPT -->
    {!! HTML::script('assets/dashboard/js/plugins/datatables/jquery.dataTables.js'); !!}
    {!! HTML::script('assets/dashboard/js/plugins/datatables/dataTables.bootstrap.js'); !!}

    <!-- page script -->
    <script type="text/javascript">

        $(function() {
            $("#table").dataTable();
        });

    </script>

@stop