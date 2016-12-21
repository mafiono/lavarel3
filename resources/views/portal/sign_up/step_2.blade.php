@extends('layouts.register')

@section('content')
    <div class="register_step2">
        <div class="header">
            Está a 2 passo de começar a apostar!
            <i id="info-close" class="fa fa-times"></i>
        </div>
        <div class="content">
            <div align="center" style="margin-top:10px">
                <div class="breadcrumb flat">
                    <a href="#">1. REGISTAR</a>
                    <a href="#" class="active">2. VALIDAR</a>
                    <a href="#">3. DEPOSITAR</a>
                    <a href="#">e</a>
                </div>
            </div>
            @if(isset($selfExclusion) && $selfExclusion)
                <div class ="icon"><i class="fa fa-exclamation-circle"></i></div>
                <div class="header">
                    Lamentamos mas de momento o Serviço de Regulação e Inspeção de Jogos não permite validar os seus detalhes.
                    <br>
                    <br>Para mais informações contactenos em <a href="mailto:apoio@casinoportugal.pt">apoio@casinoportugal.pt</a>
                </div>
            @endif
            @if(isset($identity) && $identity)
                <div class ="icon"><i class="fa fa-check-circle"></i></div>
                <div class="header">A sua conta foi criada com sucesso!<br>
                    Foi enviada uma mensagem de confirmação para<br>a sua conta de email.</div>

                <div class ="icon"><i class="fa fa-exclamation-circle"></i></div>
                <div class="header">Infelizmente, não nos foi possível verificar a sua identidade com base nos dados introduzidos! Por favor forneça um documento comprovativo.</div>
            @endif
            @if(isset($erro) && $erro !== null)
                <div class ="icon"><i class="fa fa-exclamation-circle"></i></div>
                <div class="header">{{$erro}}</div>
            @endif
        </div>
        @if(isset($identity) && $identity)
            <div class="footer">
                <div class="upload">
                    <div id="imagem" style="cursor:pointer;"><img src="/assets/portal/img/uploadregisto.png"/></div>
                    {!! Form::open(array('url'=>'/registar/step2', 'method'=>'POST', 'files'=>true, 'id' => 'saveForm')) !!}

                    <div style="display:none"><input type="File" name="upload" id="upload">
                    </div>
                    <div id="ficheiro"></div>

                    <div class="actions" style="margin-bottom:10px;">
                        <button type="submit" class="submit">CONCLUIR</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        @else
            <div class="footer">
                <div class="actions" style="margin-bottom:10px;">
                    <button type="submit" class="submit" onclick="top.location.replace('/')">CONCLUIR</button>
                </div>
            </div>
        @endif
    </div>

    @include('portal.popup-alert')
@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/rx.umd.min.js')) !!}

    {!! HTML::script(URL::asset('/assets/portal/js/registo/step2.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/registo/tooltip.js')); !!}

@stop