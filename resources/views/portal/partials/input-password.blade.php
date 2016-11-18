<div class="form-group row">
    <div class="col-xs-12">
        {!! Form::label($field, trans('customers.'.$field).($required?' *':'')) !!}
        <div class="input-group">
            <div class="input-group-addon"><i class="fa {{$icon ?: 'fa-user'}}"></i></div>
            {!! Form::password($field, ['id' => $field, 'class' => 'form-control']) !!}
        </div>
    </div>
</div>
