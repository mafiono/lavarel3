@extends('portal.sign_up.register')

@section('content')
    <div class="register_step2">
        <div class="header">
            Está a 2 passo de começar a apostar!
            <i id="register-close" class="cp-cross"></i>
        </div>
        <div class="content">
            <div align="center" style="margin-top:10px">
                <ul class="breadcrumb flat">
                    <li>1. REGISTAR</li>
                    <li class="active">2. VALIDAR</li>
                    <li>3. DEPOSITAR</li>
                    <li>e</li>
                </ul>
            </div>
            @if(isset($selfExclusion) && $selfExclusion)
                <div class="icon"><i class="cp-exclamation-circle"></i></div>
                <div class="header">
                    Lamentamos mas de momento o Serviço de Regulação e Inspeção de Jogos não permite validar os seus
                    detalhes.
                    <br>
                    <br>Para mais informações contactenos em <a
                            href="mailto:apoio@casinoportugal.pt">apoio@casinoportugal.pt</a>
                </div>
            @endif
            @if(isset($identity) && $identity)
                <div class="icon"><i class="cp-check-circle"></i></div>
                <div class="header">A sua conta foi criada com sucesso!<br>
                    Foi enviada uma mensagem de confirmação para<br>a sua conta de email.
                </div>

                <div class="icon"><i class="cp-exclamation-circle"></i></div>
                <div class="header">Infelizmente, não nos foi possível verificar a sua identidade com base nos dados
                    introduzidos! Por favor forneça um documento comprovativo.
                </div>
            @endif
            @if(isset($erro) && $erro !== null)
                <div class="icon"><i class="cp-exclamation-circle"></i></div>
                <div class="header">{{$erro}}</div>
            @endif
        </div>
        @if(isset($identity) && $identity)
            <div class="footer bs-wp">
                {!!   Form::open(array('route' => array('registar/step2'), 'method'=>'POST', 'files'=>true,'id' => 'saveForm')) !!}
                <div class="row">
                    <div class="col-xs-12">
                        @include('portal.partials.input-file', [
                            'field' => 'upload',
                            'name' => 'selecionar arquivo',
                            'autoSubmit' => false,
                        ])
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="actions" style="margin-bottom:10px;">
                            <button type="submit" class="submit">CONCLUIR</button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        @else
            <div class="footer">
                <div class="actions" style="margin-bottom:10px;">
                    <button type="submit" class="submit" onclick="top.location.replace('/')">CONCLUIR</button>
                </div>
            </div>
        @endif
    </div>
    @if ($addRegisterTracker)
        <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/860696449/?label=WaSQCO3YknUQgd-0mgM&amp;guid=ON&amp;script=0"/>
        <script>
            ga('send','event','popup','open','registo');
        </script>
    @endif
@stop

