<div class="box-links">

    <?php
    $menu = [
            ['key' => 'convites', 'name' => 'Enviar convites', 'link' => '/amigos/convites'],
            ['key' => 'rede', 'name' => 'Rede de amigos', 'link' => '/amigos/rede'],
    ]; ?>
    @foreach($menu as $item)
        <div class="bloco{{$active==$item['key']?'sel':''}}">
            <a class="btn btn-menu brand-trans {{$active==$item['key']?'sel':''}}"
               href="{{$item['link']}}">{{$item['name']}}</a>
        </div>
    @endforeach
</div>