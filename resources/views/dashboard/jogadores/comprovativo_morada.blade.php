@extends('layouts.dashboard')

@section('content')

    <section id="main-content">
        <section class="wrapper">

            <div class="row mt">
                <div class="col-lg-12">
              
                    <div class="border-head">
                        <h3>Jogador > {{ $jogador->jogadorConta->utilizador}} > Comprovativo de Morada</h3>
                    </div>
                
                    @include('dashboard.messages')
                    
                    <div class="content-panel">

                            @if ($jogador->documentacao->count())

                                <table id="table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Descrição</th>
                                            <th>Data de Upload</th>
                                            <th>Opções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jogador->documentacao as $documento)
                                            <tr>
                                                <td>{{ $documento->descricao }}</td>                                         
                                                <td>{{ $documento->created_at }}</td>
                                                <td>
                                                    {!! link_to_route('/dashboard/jogadores/comprovativos/download', 'Download', array($documento->id), array('class' => 'btn btn-sm btn-info')) !!} 
                                                </td>                                                
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Descrição</th>
                                            <th>Data de Upload</th>
                                            <th>Opções</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            @else
                                <div class="alert alert-info alert-dismissable">
                                    <i class="fa fa-info"></i>
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    Não existe documentação.
                                </div>
                            @endif
                    </div>
            </div>

            <div class="col-lg-12 mt">
                <div class="form-panel">
                    <h4 class="mb"><i class="fa fa-angle-right"></i> Estado do Jogador</h4>

                    {!! Form::open(['route' => ['dashboard/jogadores/comprovativos', $jogador->id], 'class' => 'form-horizontal style-form', 'id' => 'saveForm']) !!}
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Estado</label>
                            <div class="col-sm-10">
                                {!! Form::select('estado', $estados, $jogador->jogadorConta->estado, ['class'=>'form-control', 'id'=>'estado']) !!}
                                <span class="has-error error" style="display:none;"> </span>                                                         
                            </div>
                        </div>
                          
                        <div class="form-group">  
                            <div class="col-sm-10">
                                <label><input type="checkbox" name='email' class="sendEmail"> Enviar email</a></label>                          
                            </div>
                        </div>

                        <div class="form-group emailText" style="display:none;">
                            <label class="col-sm-2 col-sm-2 control-label">Observações</label>
                            <div class="col-sm-10">
                                {!! Form::textarea('observacoes', '', ['class'=>'form-control', 'id'=>'observacoes']) !!}
                                <span class="has-error error" style="display:none;"> </span>
                                <span class="warning-color" style="display:none;">Por favor preencha o texto a enviar no email</span>
                            </div>
                        </div>                        

                        <div class="form-group">  
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-theme formSubmit">Guardar</button>
                            </div>
                        </div>

                    {!! Form::close() !!}
                </div>              
                
            </div>
              
            </div><! --/row -->
        </section>
    </section>

@stop

@section('scripts')

    <!-- DATA TABLES SCRIPT -->
    {!! HTML::script('assets/dashboard/js/plugins/datatables/jquery.dataTables.js'); !!}
    {!! HTML::script('assets/dashboard/js/plugins/datatables/dataTables.bootstrap.js'); !!}

    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}


    <!-- page script -->
    <script type="text/javascript">

        $(function() {
            $('.sendEmail').on('change', function(){
                if($('.sendEmail').prop('checked') == true) {
                    $('.emailText').show();
                    $('#observacoes').addClass('required');
                }else{
                    $('.emailText').hide();
                    $('#observacoes').removeClass('required');
                }
            });
        });

    </script>

@stop