<div class="box-links">

    <?php
    $menu = [
        ['key' => 'saldo', 'name' => 'Saldo', 'link' => '/banco/saldo'],
        ['key' => 'depositar', 'name' => 'Depositar', 'link' => '/banco/depositar'],
        ['key' => 'pagamentos', 'name' => 'Conta Pagamentos', 'link' => '/banco/conta-pagamentos'],
        ['key' => 'levantar', 'name' => 'Levantar', 'link' => '/banco/levantar'],
    ]; ?>
    @foreach($menu as $item)
        <div class="bloco{{$active==$item['key']?'sel':''}}">
            <a class="btn btn-menu brand-trans {{$active==$item['key']?'sel':''}}"
               href="{{$item['link']}}">{{$item['name']}}</a>
        </div>
    @endforeach
</div>