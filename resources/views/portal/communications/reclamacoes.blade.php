@extends('portal.profile.layout', [
    'active1' => 'comunicacao',
    'form' => array('route' => array('comunicacao/reclamacoes'),'id' => 'saveForm'),
    'btn' => 'Guardar',
    'active2' => 'reclamacoes'])

@section('sub-content')
    <div class="row">
        <div class="col-xs-12">
            <div class="title">
                Histórico Reclamações
            </div>
        </div>
    </div>
    <div class="complains table-like">
        <div class="row header">
            <div class="col-xs-3">Data</div>
            <div class="col-xs-4">Situação</div>
            <div class="col-xs-5 text-right">Assunto</div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="place">
                    @foreach($complaints as $complaint)
                        <div class="row complain">
                            <div class="col-xs-3">{{$complaint->data->format('Y-m-d')}}</div>
                            <div class="col-xs-4">{{$complaint->result}}</div>
                            <div class="col-xs-5 ellipsis">{{$complaint->complaint}}</div>
                            <div class="col-xs-12 details" style="display: none">
                                <div class="user">{{$complaint->complaint}}</div>
                                @if(!empty($complaint->solution))
                                    <div class="staff">
                                        <span class="date">{{$complaint->solution_time}}</span> {{$complaint->staff->name}} escreveu:
                                        <div class="msg">{{$complaint->solution}}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
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