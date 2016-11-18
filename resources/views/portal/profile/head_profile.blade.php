<div class="box-links">

    <?php
    $menu = [
            ['key' => 'info', 'name' => 'Info. pessoal', 'link' => '/perfil'],
            ['key' => 'autenticacao', 'name' => 'Autenticação', 'link' => '/perfil/autenticacao'],
            ['key' => 'codes', 'name' => 'Códigos Acesso', 'link' => '/perfil/codigos'],
    ]; ?>
    @foreach($menu as $item)
        <div class="bloco {{$active==$item['key']?'sel':''}}">
            <a class="btn btn-menu brand-trans {{$active==$item['key']?'sel':''}}"
               href="{{$item['link']}}">{{$item['name']}}</a>
        </div>
    @endforeach
</div>