<div class="box-links">

    <?php
    $menu = [
            ['key' => 'por_utilizar', 'name' => 'Por Utilizar', 'link' => '/promocoes'],
            ['key' => 'activos', 'name' => 'Em Utilização', 'link' => '/promocoes/activos'],
            ['key' => 'utilizados', 'name' => 'Utilizados', 'link' => '/promocoes/utilizados'],
    ]; ?>
    @foreach($menu as $item)
        <div class="bloco{{$active==$item['key']?'sel':''}}">
            <a class="btn btn-menu brand-trans {{$active==$item['key']?'sel':''}}"
               href="{{$item['link']}}">{{$item['name']}}</a>
        </div>
    @endforeach
</div>

