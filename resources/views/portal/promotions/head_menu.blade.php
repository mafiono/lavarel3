<div id="redeem-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Resgate do bonus</h4>
            </div>
            <div class="modal-body">
                <p>Tem a certeza que pretende resgatar o bonus.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary">Resgatar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="col-xs-3 dash-right">
    <div class="box-links">
        <div class="fcenter title-form-registo brand-title brand-color aleft">
            Tipo
        </div>
        <?php
        $menu = [
                ['key' => 'desportos', 'name' => 'Desportos', 'link' => '/promocoes/porusar/desportos'],
                ['key' => 'casino', 'name' => 'Casino', 'link' => '/promocoes/porusar/casino'],
                ['key' => 'rede', 'name' => 'Rede Amigos', 'link' => '/promocoes/porusar/rede'],
        ]; ?>
        @foreach($menu as $item)
            <div class="col-lg-12 div-link">
                <a class="btn btn-menu brand-trans {{$active==$item['key']?'sel':''}}"
                   href="{{$item['link']}}">{{ucwords(strtolower($item['name']))}}</a>
            </div>
        @endforeach
    </div>
</div>