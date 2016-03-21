<div class="box-links">
    <div class="fcenter title-form-registo brand-title brand-color aleft">
        Perfil
    </div>
    <?php
    $menu = [
            ['key' => 'info', 'name' => 'Info. pessoal', 'link' => '/perfil'],
            ['key' => 'autenticacao', 'name' => 'Autenticação', 'link' => '/perfil/autenticacao'],
            ['key' => 'password', 'name' => 'Password', 'link' => '/perfil/password'],
            ['key' => 'pin', 'name' => 'Código pin', 'link' => '/perfil/codigo-pin'],
    ]; ?>
    @foreach($menu as $item)
        <div class="col-lg-12 div-link">
            <a class="btn btn-menu brand-trans {{$active==$item['key']?'sel':''}}"
               href="{{$item['link']}}">{{$item['name']}}</a>
        </div>
    @endforeach
</div>