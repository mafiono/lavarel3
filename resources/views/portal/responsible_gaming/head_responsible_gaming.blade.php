<div class="box-links">
    <div class="fcenter title-form-registo brand-title brand-color aleft">
        Jogo Responsável
    </div>
    <?php
    $menu = [
            ['key' => 'limites_deposito', 'name' => 'Limites de depósito', 'link' => '/jogo-responsavel/limites'],
            ['key' => 'limites_apostas', 'name' => 'Limites de apóstas', 'link' => '/jogo-responsavel/limites/apostas'],
            ['key' => 'auto-exclusao', 'name' => 'Auto-exclusão', 'link' => '/jogo-responsavel/autoexclusao'],
    ]; ?>
    @foreach($menu as $item)
        <div class="col-lg-12 div-link">
            <a class="btn btn-menu brand-trans {{$active==$item['key']?'sel':''}}"
               href="{{$item['link']}}">{{$item['name']}}</a>
        </div>
    @endforeach
</div>