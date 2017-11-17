@if (strpos(Request::url(), 'desportos') !== false)
    @include('portal.meta_tags.desportos')
@elseif (strpos(Request::url(), 'direto') !== false)
    @include('portal.meta_tags.direto')
@elseif (strpos(Request::url(), 'jogo_responsavel') !== false)
    @include('portal.meta_tags.jogo_responsavel')
@elseif (strpos(Request::url(), 'pagamentos') !== false)
    @include('portal.meta_tags.pagamentos')
@elseif (strpos(Request::url(), 'politica_privacidade') !== false)
    @include('portal.meta_tags.politica_privacidade')
@elseif (strpos(Request::url(), 'registar') !== false)
    @include('portal.meta_tags.registar')
@elseif (strpos(Request::url(), 'sobre_nos') !== false)
    @include('portal.meta_tags.sobre_nos')
@elseif (strpos(Request::url(), 'termos_e_condicoes') !== false)
    @include('portal.meta_tags.termos_e_condicoes')
@elseif (strpos(Request::url(), 'casino') !== false)
    @include('portal.meta_tags.casino')
@else
    @include('portal.meta_tags.default')
@endif