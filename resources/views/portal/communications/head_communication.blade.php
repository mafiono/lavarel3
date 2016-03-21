<div class="box-links">
    <div class="fcenter title-form-registo brand-title brand-color aleft">
        Comunicação
    </div>
    <?php
    $menu = [
            ['name' => 'MENSAGENS', 'link' => '/comunicacao/mensagens'],
            ['name' => 'DEFINIÇÕES', 'link' => '/comunicacao/definicoes'],
    ]; ?>
    @foreach($menu as $item)
        <div class="col-lg-12 div-link">
            <a class="btn btn-menu brand-trans {{$active==$item['name']?'sel':''}}"
               href="{{$item['link']}}">{{$item['name']}}</a>
        </div>
    @endforeach
</div>