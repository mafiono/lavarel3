<div class="form-group row">
    <div class="col-xs-12">
        @if(!isset($hiddenLabel))
            {!! Form::label($field, $name) !!}
        @endif
        <div class="input-group">
            {!! Form::text($field, isset($value) ? $value : null, ['id' => $field, 'class' => 'form-control', 'id' => 'search',
                'placeholder' => isset($hiddenLabel) ? $name : '']) !!}
            <span class="has-error error" style="display:none;"> </span>
        </div>
    </div>
</div>
