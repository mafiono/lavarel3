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
                    <td class="settings-text-darker">{{$message->text}}</td>
                    <td>{{$message->created_at}} </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
@stop





@section('scripts')


@stop