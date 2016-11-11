@extends('portal.profile.layout', [
    'active1' => 'jogo_responsavel',
    'middle' => 'portal.responsible_gaming.head_responsible_gaming',
    'active2' => 'last_logins'])

@section('sub-content')

    <div class="bs-wp">

        <table class="table table-striped" style="width:90%">
            <thead>
            <tr>
                <th style="color: #4a7fb3;font-size:14px;">Data</th>
                <th style="color: #4a7fb3;font-size:14px;">Tipo</th>
                <th style="color: #4a7fb3;font-size:14px;">IP</th>
            </tr>
            </thead>
            <tbody>
            <?php
            /** @var $session \App\UserSession */
            ?>
            @foreach($sessions as $session)
                <tr>
                    <td style="color: #4a7fb3;font-size:12px;">{{$session->created_at->format('Y-m-d H:i')}}</td>
                    <td style="color: #4a7fb3;font-size:12px;">{{trans('sessions_types.'.$session->session_type)}}</td>
                    <td style="color: #4a7fb3;font-size:12px;">{{$session->ip}}</td>

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