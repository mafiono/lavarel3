@extends('portal.profile.layout', [
    'active1' => 'jogo_responsavel',
    'middle' => 'portal.responsible_gaming.head_responsible_gaming',
    'active2' => 'last_logins'])

@section('sub-content')

    <div class="col-xs-12 fleft" id="last_logins">
        <div class="title-form-registo brand-title brand-color aleft">
            Ultimos Logins
        </div>

        <table class="settings-table">
            <thead>
            <tr>
                <th class="col-5">Tipo</th>
                <th class="col-3">Ip</th>
                <th class="col-4">Data</th>
            </tr>
            </thead>
            <tbody>
            <?php
            /** @var $session \App\UserSession */
            ?>
            @foreach($sessions as $session)
                <tr>
                    <td class="col-5">{{trans('sessions_types.'.$session->session_type)}}</td>
                    <td class="col-3">{{$session->ip}}</td>
                    <td class="col-4">{{$session->created_at->format('Y-m-d H:i')}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@stop


@section('scripts')
    {!! HTML::script('assets/portal/js/plugins/jquery-slimscroll/jquery.slimscroll.min.js'); !!}
    <script>
        $(function() {
            $("#last_logins").slimScroll({
                width: '100%',
                height: '440px'
            });
        });
    </script>
@stop