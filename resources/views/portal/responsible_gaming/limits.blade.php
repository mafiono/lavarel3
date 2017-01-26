<?php
    $warningText = new stdClass();
    $app->instance('warningText', $warningText);
?>
@extends('portal.profile.layout', [
    'active1' => 'jogo_responsavel',
    'middle' => 'portal.responsible_gaming.head_responsible_gaming',
    'active2' => 'limites_apostas'

    ])


@section('sub-content')
    <div class="limites">
        <div class="row">
            <div class="col-xs-12">
                <div class="title">Limites de Depósito (EUR)</div>
            </div>
        </div>
        {!! Form::open(array('route' => array('jogo-responsavel/limites/depositos'),'id' => 'saveFormDeposits')) !!}

            @include('portal.responsible_gaming.input', [
                'label' => 'Limite Diário',
                'typeId' => 'daily_deposit',
                'key' => 'limit_deposit_daily'
            ])

            @include('portal.responsible_gaming.input', [
                'label' => 'Limite Semanal',
                'typeId' => 'weekly_deposit',
                'key' => 'limit_deposit_weekly'
            ])

            @include('portal.responsible_gaming.input', [
                'label' => 'Limite Mensal',
                'typeId' => 'monthly_deposit',
                'key' => 'limit_deposit_monthly',
                'final' => 'Definir'
            ])

            <div class="row">
                <div class="col-xs-12">
                    @foreach($warningText as $key => $value)
                        <p class="warning-msg">{{$value}}</p>
                        <?php unset($warningText->$key); ?>
                    @endforeach
                </div>
            </div>
        {!! Form::close() !!}

        <div class="row">
            <div class="col-xs-12">
                <div class="title">Limites de Apostas (EUR)</div>
            </div>
        </div>

        {!! Form::open(array('route' => array('jogo-responsavel/limites/apostas'),'id' => 'saveFormBets')) !!}

            @include('portal.responsible_gaming.input', [
               'label' => 'Limite Diário',
               'typeId' => 'daily_bet',
               'key' => 'limit_betting_daily'
           ])

            @include('portal.responsible_gaming.input', [
                'label' => 'Limite Semanal',
                'typeId' => 'weekly_bet',
                'key' => 'limit_betting_weekly'
            ])

            @include('portal.responsible_gaming.input', [
                'label' => 'Limite Mensal',
                'typeId' => 'monthly_bet',
                'key' => 'limit_betting_monthly',
                'final' => 'Definir'
            ])

            <div class="row">
                <div class="col-xs-12">
                    @foreach($warningText as $key => $value)
                        <p class="warning-msg">{{$value}}</p>
                        <?php unset($warningText->$key); ?>
                    @endforeach
                </div>
            </div>

        {!! Form::close() !!}
    </div>
@stop