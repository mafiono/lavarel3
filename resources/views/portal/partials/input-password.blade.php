<div class="form-group row">
    <div class="col-xs-12">
        {!! Form::label($field, $name) !!}
        <div class="input-group">
            {!! Form::password($field, ['id' => $field, 'class' => 'form-control', 'autocomplete' => 'off']) !!}
            <span class="has-error error" style="display:none;"> </span>
        </div>
    </div>
</div>
