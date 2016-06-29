@extends('portal.profile.layout', [
    'active1' => 'perfil',
    'middle' => 'portal.profile.head_profile',
    'active2' => 'autenticacao'])

@section('sub-content')


    <style>
        .settings-table th:nth-child(1) {
            text-align: left;
            width: 100px;
        }
        .settings-table th:nth-child(2), .settings-table th:nth-child(3) {
            text-align: center;
            width: 170px;
        }
        .settings-table th:nth-child(4) {
            width: 100px;
        }
        .settings-table td:nth-child(1) {
            text-align: left;
        }
        .settings-table td:nth-child(1), .settings-table td:nth-child(4) {
            width: 100px;
        }
        .settings-table td:nth-child(2), .settings-table td:nth-child(3) {
            text-overflow: clip;
            width: 170px;
            max-width: 170px;
        }

    </style>

    <div class="settings-col">
        <table class="settings-table">
            <thead>
            <tr>
                <th>Mensagem</th>
                <th>Data</th>

            </tr>
            </thead>
            <tbody>
            @foreach($messages as $message)
                <tr>
                    @if($message->viewed == "1")

                        <td style = "max-width:200px;overflow-y: auto;" class="settings-text-darker">{{$message->text}}</td>
                        <td>{{$message->created_at}} </td>

                    @else
                        <td style = "background-color: #c5ffc5;max-width:200px;overflow-y: auto;" class="settings-text-darker">{{$message->text}}</td>
                        <td style = "background-color: #c5ffc5;">{{$message->created_at}} </td>
                        @endif
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
@stop





@section('scripts')
    <script>

    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
    $.ajax({
    type: "POST",
    url: '/mensagens/read',
    dataType: "json",

    });

    });

    </script>
@stop