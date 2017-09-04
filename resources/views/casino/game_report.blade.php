<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report</title>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/pure/0.6.2/pure-min.css">
</head>

<body style="height: 100%; padding: 20px 80px;">
    @if ($token)
    <h1>Reporte do ultimo jogo</h1>
    <h2>({{$token->sessions[0]->game->name}})</h2>
    <table class="pure-table">
        <thead>
            <tr>
                <th>Data</th>
                <th>Detalhe</th>
                <th>Valor</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
        @foreach($token->sessions as $session)
            @foreach($session->rounds as $round)
                @foreach($round->transactions as $transaction)
                    <tr>
                        <td>{{$transaction->created_at->format('d/m/Y H:i')}}</td>
                        <td>Aposta nº {{$round->id}}</td>
                        <td>{{ number_format(($transaction->type=='bet' ? -1 : 1) * ($transaction->amount + $transaction->amount_bonus), 2) }} €</td>
                        <td>{{ number_format($transaction->final_balance + $transaction->final_bonus, 2) }} €</td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach
        </tbody>
    </table>
    <h3>Total: {{$total}} €</h3>
    @else
        <h1>Sem actividade <button class="pure-button" onclick="window.close()">Sair</button></h1>
    @endif
</body>

</html>