@foreach($session->rounds as $round)
    @foreach($round->transactions as $transaction)
        <div class="row" style="background: #dddddd; font-size: 10px">
            <div class="col-xs-3">{{$transaction->created_at->format('d/m/Y H:i')}}</div>
            <div class="col-xs-5 text-center ellipsis">Aposta nº {{$round->id}}</div>
            <div class="col-xs-2 text-right">{{ number_format(($transaction->type=='bet' ? -1 : 1) * ($transaction->amount + $transaction->amount_bonus), 2) }} €</div>
            <div class="col-xs-2 text-right">{{ number_format($transaction->final_balance + $transaction->final_bonus, 2) }} €</div>
        </div>
    @endforeach
@endforeach