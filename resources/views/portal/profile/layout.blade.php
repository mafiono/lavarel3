@if (isset($form))
    {!! Form::open($form) !!}
@endif

@yield('sub-content')

@if (isset($form))
    <div class="profile-button-right">
        <input type="submit" value="{{$btn or 'Guardar'}}"/>
    </div>
@endif
@if (isset($form))
    {!! Form::close() !!}
@endif