@extends('portal.profile.layout', [
    'active1' => 'comunicacao',
    'middle' => 'portal.communications.head_communication',
    'form' => array('route' => array('comunicacao/reclamacoes'),'id' => 'saveForm'),
    'btn' => 'Gravar',
    'active2' => 'reclamacoes'])

@section('sub-content')

    <div class="row">
        <div class="col-xs-12">
            <div class="title">
                Histórico Reclamações
            </div>
            <div class="wrap-table">
                <table class="table table-striped" style="width:100%">
                    <thead>
                    <tr>
                        <th style="color: #4a7fb3;font-size:14px;">Data</th>
                        <th style="color: #4a7fb3;font-size:14px;">Situação</th>
                        <th style="color: #4a7fb3;font-size:14px;">Assunto</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($complaints as $complaint)
                        <tr onclick="showcomplain(this)">
                            <td style="color: #4a7fb3;font-size:12px;">{{$complaint->data}}</td>
                            <td style="color: #4a7fb3;font-size:12px;">{{$complaint->result}}</td>
                            <td style="color: #4a7fb3;font-size:12px;">{{$complaint->complaint}}</td>

                        </tr>
                        <tr id="{{$complaint->id}}" style="display:none;border:1px solid #4a7fb3">

                            <td colspan="3">
                                <div class="texto" style="color:#4a7fb3">{{$complaint->updated_at}}&nbsp; staff
                                    escreveu:
                                </div>
                                <div class="texto">{{$complaint->solution}}</div>
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="title">
                Exponha o seu problema
            </div>
        </div>
        <div class="col-xs-12">
            @include('portal.partials.input-text-area', ['field' => 'reclamacao', 'icon' => '', 'required' => true])
        </div>
    </div>
@stop

@section('scripts')

    <script>
        function showcomplain(el){
            $(el).next().toggle();
        }
    </script>
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
@stop