<style>.label-as-badge {
        border-radius: 1em;
        color: #880000;
    }</style>

<div class="box-links">
    <div class="fcenter title-form-registo brand-title brand-color aleft">
        Comunicação
    </div>
    <?php
    if(\App\Lib\Notifications::getMensagens() >0){
    $menu = [
            ['key' => 'mensagens', 'name' => 'Mensagens', 'link' => '/comunicacao/mensagens', 'count' => \App\Lib\Notifications::getMensagens()],
            ['key' => 'definicoes', 'name' => 'Definições', 'link' => '/comunicacao/definicoes'],
            ['key' => 'reclamacoes', 'name' => 'Reclamações', 'link' => '/comunicacao/reclamacoes'],
    ];
        }else{
    $menu = [
            ['key' => 'mensagens', 'name' => 'Mensagens', 'link' => '/comunicacao/mensagens'],
            ['key' => 'definicoes', 'name' => 'Definições', 'link' => '/comunicacao/definicoes'],
            ['key' => 'reclamacoes', 'name' => 'Reclamações', 'link' => '/comunicacao/reclamacoes'],
    ];
        }
    ?>
    @foreach($menu as $item)
        <div class="col-lg-12 div-link">
            <a class="btn btn-menu brand-trans {{$active==$item['key']?'sel':''}}"
               href="{{$item['link']}}">{{$item['name']}}
                @if(isset($item['count']))
                    <span class="label label-default label-as-badge">{{$item['count']}}</span>
                @endif
            </a>
        </div>
    @endforeach
</div>