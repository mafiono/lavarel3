@extends('layouts.dashboard')

@section('content')

    <section id="main-content">
        <section class="wrapper">

            <div class="row mt">
                <div class="col-lg-12">
              
                    <div class="border-head">
                        <h3>Listagem de Jogadores</h3>
                    </div>
                
                    
                    <div class="content-panel">

                            @if ($jogadores->count())

                                <table id="table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Utilizador</th>
                                            <th>Nome</th>
                                            <th>Data de Nascimento</th>
                                            <th>Estado</th>
                                            <th>Data de Criação</th>
                                            <th>Opções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jogadores as $jogador)
                                            <tr>
                                                <td>{{ $jogador->jogadorConta->utilizador }}</td>                                         
                                                <td>{{ $jogador->nome_completo }}</td>                                         
                                                <td>{{ $jogador->data_nascimento }}</td>                                       
                                                <td>{{ $jogador->jogadorConta->Estado->estado }}</td>                                           
                                                <td>{{ $jogador->created_at }}</td>
                                                <td> 
                                                    <a href="/dashboard/jogadores/comprovativos/{{$jogador->id}}" class="btn btn-sm btn-info"><i class="fa fa-list"></i> Comprovativos</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Utilizador</th>
                                            <th>Nome</th>
                                            <th>Data de Nascimento</th>
                                            <th>Estado</th>
                                            <th>Data de Criação</th>
                                            <th>Opções</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            @else
                                <div class="alert alert-info alert-dismissable">
                                    <i class="fa fa-info"></i>
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    Não existem jogadores.
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