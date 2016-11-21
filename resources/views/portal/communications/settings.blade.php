@extends('portal.profile.layout', [
    'active1' => 'comunicacao',
    'middle' => 'portal.communications.head_communication',
    'active2' => 'definicoes',
    'form' => array('route' => array('comunicacao/definicoes'),'id' => 'saveForm'),
    'btn' => 'Guardar'])

@section('sub-content')
    <div class="center">

        <div class="title">
            Definições de Comunicação
        </div>

        <div class="texto" style="margin-top:15px;">
            Os pedidos de levantamento serão efetuados na conta acima indicada. A alteração desta conta
            inviabiliza o processamento de levantamentos por um
            período de 48 horas, necessário para rotinas de confirmação de titular.
        </div>

        <div class="grupo" style="float:left; width:45%; margin-left:40px; margin-top:30px;">
            <div class="grupo-title" style="width:100px;">
                Email
            </div>
            <div style="float: left; width: 10%;">
                <?php !empty($settings['email']) && $settings['email'] == 1 ? $checked = 'checked' : $checked = '';?>
                <input id="email" class="settings-switch" name="email" type="checkbox" {{$checked}}>
                <label for="email"></label>
                <span class="has-error error" style="display:none;"> </span>
            </div>
            <div class="clear"></div>

        </div>


        <div class="grupo" style="width:45%;float:left;margin-top:30px;">
            <div class="grupo-title" style="width:100px;">
                Telefone
            </div>
            <div style="float: left; width: 10%;">
                <?php !empty($settings['phone']) && $settings['phone'] == 1 ? $checked = 'checked' : $checked = '';?>

                <input id="phone" class="settings-switch" name="phone" type="checkbox" {{$checked}}>
                <label for="phone"></label>
                <span class="has-error error" style="display:none;"> </span>
            </div>
            <div class="clear"></div>
        </div>

        <div class="grupo" style="width:45%;float:left; margin-left:40px;">
            <div class="grupo-title" style="width:100px;">
                Sms
            </div>
            <div style="float: left; width: 10%;">
                <?php !empty($settings['sms']) && $settings['sms'] == 1 ? $checked = 'checked' : $checked = '';?>

                <input id="sms" class="settings-switch" name="sms" type="checkbox" {{$checked}}>
                <label for="sms"></label>
                <span class="has-error error" style="display:none;"> </span>
            </div>
            <div class="clear"></div>
        </div>

        <div class="grupo" style="width:45%;float:left;">
            <div class="grupo-title" style="width:100px;">
                Correio
            </div>
            <div style="float: left; width: 10%;">
                <?php !empty($settings['mail']) && $settings['mail'] == 1 ? $checked = 'checked' : $checked = '';?>

                <input id="mail" class="settings-switch" name="mail" type="checkbox" {{$checked}}>
                <label for="mail"></label>
                <span class="has-error error" style="display:none;"> </span>
            </div>
            <div class="clear"></div>
        </div>

        <div class="grupo" style="width:45%;float:left; margin-left:40px;">
            <div class="grupo-title" style="width:100px;">
                Newsletter
            </div>
            <div style="float: left; width: 10%;">
                <?php !empty($settings['newsletter']) && $settings['newsletter'] == 1 ? $checked = 'checked' : $checked = '';?>

                <input id="newsletter" class="settings-switch" name="newsletter" id="newsletter"
                       type="checkbox" {{$checked}}>
                <label for="newsletter"></label>
                <span class="has-error error" style="display:none;"> </span>
            </div>
            <div class="clear"></div>
        </div>

        <div class="grupo" style="width:45%;float:left;">
            <div class="grupo-title" style="width:100px;">
                Chat
            </div>
            <div style="float: left; width: 10%;">
                <?php !empty($settings['chat']) && $settings['chat'] == 1 ? $checked = 'checked' : $checked = '';?>

                <input id="chat" name="chat" class="settings-switch" type="checkbox" {{$checked}}>
                <label for="chat"></label>
                <span class="has-error error" style="display:none;"> </span>
            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}
@stop