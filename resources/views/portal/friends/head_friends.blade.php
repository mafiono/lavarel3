<div class="box-links">
    <div class="fcenter title-form-registo brand-title brand-color aleft">
        Convidar Amigos
    </div>
    <?php
    $menu = [
            ['key' => 'convites', 'name' => 'Enviar convites', 'link' => '/amigos/convites'],
            ['key' => 'rede', 'name' => 'Rede de amigos', 'link' => '/amigos/rede'],
    ]; ?>
    @foreach($menu as $item)
        <div class="col-lg-12 div-link">
            <a class="btn btn-menu brand-trans {{$active==$item['key']?'sel':''}}"
               href="{{$item['link']}}">{{$item['name']}}</a>
        </div>
    @endforeach
</div>