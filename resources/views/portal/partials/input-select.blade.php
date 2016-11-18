<div class="form-group row">
    <div class="col-xs-12">
        {!! Form::label($field, $name) !!}
        <div class="input-group">
            {!! Form::select($field, $options?: [], isset($value) ? $value : null, ['id' => $field, 'class' => 'form-control']) !!}
        </div>
        <span class="has-error error" style="display:none;"> </span>
    </div>
</div>