<div class="box-links">
    <div class="fcenter title-form-registo brand-title brand-color aleft">
        Promoções e Bónus
    </div>
    <?php
    $menu = [
            ['key' => 'por_utilizar', 'name' => 'Por Utilizar', 'link' => '/promocoes'],
            ['key' => 'pendentes', 'name' => 'Pendentes', 'link' => '/promocoes/pendentes'],
            ['key' => 'utilizados', 'name' => 'Utilizados', 'link' => '/promocoes/utilizados'],
    ]; ?>
    @foreach($menu as $item)
        <div class="col-lg-12 div-link">
            <a class="btn btn-menu brand-trans {{$active==$item['key']?'sel':''}}"
               href="{{$item['link']}}">{{$item['name']}}</a>
        </div>
    @endforeach
</div>