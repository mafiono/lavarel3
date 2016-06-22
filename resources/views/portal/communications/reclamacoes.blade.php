    @extends('portal.profile.layout', [
        'active1' => 'comunicacao',
        'middle' => 'portal.communications.head_communication',
        'form' => array('route' => array('comunicacao/reclamacoes'),'id' => 'saveForm'),
        'btn' => 'Gravar',
        'active2' => 'reclamacoes'])

    @section('sub-content')

        <div class="col-xs-6 dash-right">
            <div class="title-form-registo brand-title brand-color aleft">
                Histórico Reclamações
            </div>
            <div class="registo-form consulta-form" style = "height:400px;overflow-y:auto;">
                <?php foreach($complaints as $complaint){ ?>

                <label>Reclamação</label>
                    <input type="text" name="reclamacao" value="{{ $complaint->complaint }}" disabled="disabled" />
                    <input type="text" name="data" value="{{ $complaint->data }}" disabled="disabled" />
                    <input type="text" name="solucao" value="Solução : {{ $complaint->solution }}" disabled="disabled" />
                    <input type="text" name="resultado" value="{{ $complaint->result }}" disabled="disabled" />


                <?php } ?>
             </div>
        </div>
        <div class="col-xs-6">
            <div class="title-form-registo brand-title brand-color aleft">
                Nova Reclamação
            </div>
            <div class="box">
                <div class="box-body">

                    <div class="row">
                        <div class="col-xs-12">
                            @include('partials.input-text', ['field' => 'reclamacao', 'icon' => '', 'required' => true])
                        </div>
                    </div>
                </div>
            </div>
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