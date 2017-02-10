<div class="form-group row">
    <div class="col-xs-12" style="overflow-x:visible">
        {!! Form::label($field, $name) !!}
        <div class="input-group">
            {!! Form::text($field, isset($value) ? $value : null, ['id' => $field, 'class' => 'form-control' , 'disabled' => 'disabled']) !!}
        </div>
    </div>
</div>
