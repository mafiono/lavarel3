<?php
    $items = [
            ['game'=>'futebol', 'name'=>'Futebol'],
            ['game'=>'futebol1', 'name'=>'Futebol1'],
            ['game'=>'futebol2', 'name'=>'Futebol2'],
            ['game'=>'futebol3', 'name'=>'Futebol3'],
            ['game'=>'futebol4', 'name'=>'Futebol4'],
    ];
?>
<ul class="sub-menu">
    <li {{$game=='index'?'class=sel':''}}><a href="/info/regras/sports">SportsBook</a></li>
    @foreach($items as $item)
        <li {{$game==$item['game']?'class=sel':''}}><a href="/info/regras/sports/{{$item['game']}}">{{$item['name']}}</a></li>
    @endforeach
</ul>