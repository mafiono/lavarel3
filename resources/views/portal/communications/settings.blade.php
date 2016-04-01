@extends('portal.profile.layout', [
    'active1' => 'comunicacao',
    'middle' => 'portal.communications.head_communication',
    'active2' => 'definicoes',
    'form' => array('route' => array('comunicacao/definicoes'),'id' => 'saveForm'),
    'btn' => 'Guardar'])

@section('sub-content')

    <div class="col-xs-12 fleft">
            <div class="title-form-registo brand-title brand-color aleft">
                Definições de Comunicação
            </div>
            <div class="brand-descricao descricao-mbottom aleft">
            Defina as sua preferências na forma como gostaria de ser contactado.
            </div>

        <div class="col-xs-5 clear comunica_form">
                      <div class="comunica_label neut-color fleft">
              Email
                      </div>
                      <div class="switch fright">
                        <?php !empty($settings['email']) && $settings['email'] == 1 ? $checked = 'checked' : $checked = '';?>
                        <input id="email" class="cmn-toggle cmn-toggle-round" name="email" type="checkbox" {{$checked}}>
                        <label for="email"></label>
                        <span class="has-error error" style="display:none;"> </span>
                      </div>
                      <div class="clear"></div>
                </div>
        <div class="col-xs-5 clear comunica_form">
                      <div class="comunica_label neut-color fleft">
              Telefone
                      </div>
                      <?php !empty($settings['phone']) && $settings['phone'] == 1 ? $checked = 'checked' : $checked = '';?>
                      <div class="switch fright">
                        <input id="phone" class="cmn-toggle cmn-toggle-round" name="phone" type="checkbox" {{$checked}}>
                        <label for="phone"></label>
                        <span class="has-error error" style="display:none;"> </span>
                      </div>
                      <div class="clear"></div>
                </div>
        <div class="col-xs-5 clear comunica_form">
                      <div class="comunica_label neut-color fleft">
              Sms
                      </div>
                      <?php !empty($settings['sms']) && $settings['sms'] == 1 ? $checked = 'checked' : $checked = '';?>
                      <div class="switch fright">
                        <input id="sms" class="cmn-toggle cmn-toggle-round" name="sms" type="checkbox" {{$checked}}>
                        <label for="sms"></label>
                        <span class="has-error error" style="display:none;"> </span>
                      </div>
                      <div class="clear"></div>
                </div>
        <div class="col-xs-5 clear comunica_form">
                      <div class="comunica_label neut-color fleft">
              Correio
                      </div>
                      <?php !empty($settings['mail']) && $settings['mail'] == 1 ? $checked = 'checked' : $checked = '';?>
                      <div class="switch fright">
                        <input id="mail" class="cmn-toggle cmn-toggle-round" name="mail" type="checkbox" {{$checked}}>
                        <label for="mail"></label>
                        <span class="has-error error" style="display:none;"> </span>
                      </div>
                      <div class="clear"></div>
                </div>
        <div class="col-xs-5 clear comunica_form">
                      <div class="comunica_label neut-color fleft">
              Newsletter
                      </div>
                      <?php !empty($settings['newsletter']) && $settings['newsletter'] == 1 ? $checked = 'checked' : $checked = '';?>
                      <div class="switch fright">
                        <input id="newsletter" class="cmn-toggle cmn-toggle-round" name="newsletter" id="newsletter" type="checkbox" {{$checked}}>
                        <label for="newsletter"></label>
                        <span class="has-error error" style="display:none;"> </span>
                      </div>
                      <div class="clear"></div>
                </div>
        <div class="col-xs-5 clear comunica_form">
                      <div class="comunica_label neut-color fleft">
                Chat
                      </div>
                      <?php !empty($settings['chat']) && $settings['chat'] == 1 ? $checked = 'checked' : $checked = '';?>
                      <div class="switch fright">
                        <input id="chat" name="chat" class="cmn-toggle cmn-toggle-round" type="checkbox" {{$checked}}>
                        <label for="chat"></label>
                        <span class="has-error error" style="display:none;"> </span>
                      </div>
                      <div class="clear"></div>
                </div>
        <div class="clear"></div>

        @include('portal.messages')
        </div>
    <div class="clear"></div>
@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}    
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}
@stop