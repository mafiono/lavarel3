<div class="box-links">
    <div class="fcenter title-form-registo brand-title brand-color aleft">
        Jogo Responsável
    </div>
    <?php
    $menu = [
            ['name' => 'LIMITES DE DEPÓSITO', 'link' => '/jogo-responsavel/limites'],
            ['name' => 'LIMITES DE APÓSTAS', 'link' => '/jogo-responsavel/limites/apostas'],
            ['name' => 'AUTO-EXCLUSÃO', 'link' => '/jogo-responsavel/autoexclusao'],
    ]; ?>
    @foreach($menu as $item)
        <div class="col-lg-12 div-link">
            <a class="btn btn-menu brand-trans {{$active==$item['name']?'sel':''}}"
               href="{{$item['link']}}">{{$item['name']}}</a>
        </div>
    @endforeach
</div>