<div class="col-xs-12">
    <div class="title">
        Saldo (EUR)
    </div>
    <div class="profile-table">
        <table>
            <thead>
            <tr>
                <th style="text-align: left">Disponível</th>
                <th style="text-align: left">Contabilistico</th>
                <th style="text-align: right">Bónus</th>
                <th style="text-align: right">Total</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ $authUser->balance->balance_available }}</td>
                <td>{{ $authUser->balance->balance_accounting }} @if ($authUser->balance->balance_reserved > 0)
                        <span class="text-warning" title="Saldo em Cativo">+{{ $authUser->balance->balance_reserved }}</span>
                    @endif</td>
                <td style="text-align: right">{{ $authUser->balance->balance_bonus }}</td>
                <td style="text-align: right"><b>{{ $authUser->balance->balance_total }}</b></td>
            </tr>
            </tbody>
        </table>

    </div>
</div>