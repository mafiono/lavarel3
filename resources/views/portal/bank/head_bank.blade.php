<div class="box-links">
    <div class="fcenter title-form-registo brand-title brand-color aleft">
        Banco
    </div>
    <?php
    $menu = [
        ['key' => 'saldo', 'name' => 'Saldo', 'link' => '/banco/saldo'],
        ['key' => 'depositar', 'name' => 'Depositar', 'link' => '/banco/depositar'],
        ['key' => 'pagamentos', 'name' => 'Conta de pagamentos', 'link' => '/banco/conta-pagamentos'],
        ['key' => 'levantar', 'name' => 'Levantar', 'link' => '/banco/levantar'],
    ]; ?>
    @foreach($menu as $item)
        <div class="col-lg-12 div-link">
            <a class="btn btn-menu brand-trans {{$active==$item['key']?'sel':''}}"
               href="{{$item['link']}}">{{$item['name']}}</a>
        </div>
    @endforeach
</div>