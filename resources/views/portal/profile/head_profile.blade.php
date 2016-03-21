<div class="box-links">
    <div class="fcenter title-form-registo brand-title brand-color aleft">
        Perfil
    </div>
    <?php
    $menu = [
            ['name' => 'INFO. PESSOAL', 'link' => '/perfil'],
            ['name' => 'AUTENTICAÇÃO', 'link' => '/perfil/autenticacao'],
            ['name' => 'PASSWORD', 'link' => '/perfil/password'],
            ['name' => 'CÓDIGO PIN', 'link' => '/perfil/codigo-pin'],
    ]; ?>
    @foreach($menu as $item)
        <div class="col-lg-12 div-link">
            <a class="btn btn-menu brand-trans {{$active==$item['name']?'sel':''}}"
               href="{{$item['link']}}">{{$item['name']}}</a>
        </div>
    @endforeach
</div>