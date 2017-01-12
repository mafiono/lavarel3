@extends('portal.profile.layout', [
    'active1' => 'jogo_responsavel',
    'middle' => 'portal.responsible_gaming.head_responsible_gaming',
    'active2' => 'last_logins'])

@section('sub-content')

    <div class="last_logins table-like">
        <div class="row header">
            <div class="col-xs-4">Data</div>
            <div class="col-xs-4 text-center">Tipo</div>
            <div class="col-xs-4 text-right">IP</div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="place">
                    @foreach($sessions as $session)
                        <div class="row">
                            <div class="col-xs-4">{{$session->created_at->format('Y-m-d H:i')}}</div>
                            <div class="col-xs-4 text-center">{{trans('sessions_types.'.$session->session_type)}}</div>
                            <div class="col-xs-4 text-right">{{$session->ip}}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    {!! HTML::script('assets/portal/js/plugins/jquery-slimscroll/jquery.slimscroll.min.js'); !!}
    <script>
        $(function() {
            $(".last_logins .place").slimScroll({
                height: '600px',
                allowPageScroll: true
            });
        });
    </script>
@stop