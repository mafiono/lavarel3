<div class="box-links">
    <div class="fcenter title-form-registo brand-title brand-color aleft">
        Banco
    </div>
    <?php
    $menu = [
            ['name' => 'SALDO', 'link' => '/banco/saldo'],
            ['name' => 'DEPOSITAR', 'link' => '/banco/depositar'],
            ['name' => 'CONTA DE PAGAMENTOS', 'link' => '/banco/conta-pagamentos'],
            ['name' => 'LEVANTAR', 'link' => '/banco/levantar'],
    ]; ?>
    @foreach($menu as $item)
        <div class="col-lg-12 div-link">
            <a class="btn btn-menu brand-trans {{$active==$item['name']?'sel':''}}"
               href="{{$item['link']}}">{{$item['name']}}</a>
        </div>
    @endforeach
</div>