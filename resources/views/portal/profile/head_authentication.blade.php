<div class="col-xs-4 dash-right">
    <div class="box-links">
        <div class="fcenter title-form-registo brand-title brand-color aleft">
            Autenticação
        </div>
        <?php
        $menu = [
                ['key' => 'identidade', 'name' => 'Identidade', 'link' => '/perfil/autenticacao'],
                ['key' => 'morada', 'name' => 'Morada', 'link' => '/perfil/autenticacao/morada'],
        ]; ?>
        @foreach($menu as $item)
            <div class="col-lg-12 div-link">
                <a class="btn btn-menu brand-trans {{$active==$item['key']?'sel':''}}"
                   href="{{$item['link']}}">{{ucwords(strtolower($item['name']))}}</a>
            </div>
        @endforeach
    </div>
</div>