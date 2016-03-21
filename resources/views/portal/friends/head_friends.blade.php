<div class="box-links">
    <div class="fcenter title-form-registo brand-title brand-color aleft">
        Convidar Amigos
    </div>
    <?php
    $menu = [
            ['name' => 'ENVIAR CONVITES', 'link' => '/amigos/convites'],
            ['name' => 'REDE DE AMIGOS', 'link' => '/amigos/rede'],
    ]; ?>
    @foreach($menu as $item)
        <div class="col-lg-12 div-link">
            <a class="btn btn-menu brand-trans {{$active==$item['name']?'sel':''}}"
               href="{{$item['link']}}">{{$item['name']}}</a>
        </div>
    @endforeach
</div>