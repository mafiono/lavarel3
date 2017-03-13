<div class="form-group row">
    <div class="col-xs-12 date">
        @if(!isset($hiddenLabel))
            {!! Form::label($field, $name) !!}
        @endif
        <div class="input-group">
            {!! Form::text($field, isset($value) ? $value : null, ['id' => $field, 'class' => 'form-control',
                'placeholder' => isset($hiddenLabel) ? $name : '']) !!}
            <button class="btn btn-default" type="button" onclick="$(this).parent().find('input').focus(); return false;"><i class="cp-calendar-o"></i></button>
            <span class="has-error error" style="display:none;"> </span>
        </div>
    </div>
</div>
