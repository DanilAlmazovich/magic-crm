<table>
    @foreach($operators as $operator)
        <tr>
            <td colspan="11" style="text-align: center; font-weight: bold"></td>
        </tr>
        <tr>
            <td colspan="11" style="text-align: center; font-weight: bold">
                Оператор: {{ $operator->name }}
            </td>
        </tr>
        <tr>
            @if ($from || $operator->orders_min_created_at)
                <td colspan="11" style="text-align: center; font-weight: bold">
                    с: {{ $from ?: (new \Carbon\Carbon($operator->orders_min_created_at))->format('d.m.Y') }}
                    по: {{ $to }}
                </td>
            @endif
        </tr>
        <tr>
            <td style="font-weight: bold">№</td>
            <td style="font-weight: bold">Номер заказа</td>
            <td style="font-weight: bold">Клиент</td>
            <td style="font-weight: bold">Адрес</td>
            <td style="font-weight: bold">Примечание</td>
            <td style="font-weight: bold">Курьер</td>
            <td style="font-weight: bold">Дата</td>
            <td style="font-weight: bold">Доставка</td>
            <td style="font-weight: bold">В офис</td>
            <td style="font-weight: bold">Сумма</td>
            <td style="font-weight: bold">Оплата</td>
        </tr>
        @foreach($operator->orders as $order)
            <tr>
                <td style="height: 40px; vertical-align: center">{{ $loop->index + 1 }}</td>
                <td style="vertical-align: center">{{ $order->id }}</td>
                <td style="vertical-align: center">{{ $order->client->phone }}</td>
                <td style="vertical-align: center; word-wrap: break-word; width: 40px">
                    {{ $order->addresses->first()->address ?? null }}
                </td>
                <td style="vertical-align: center; word-wrap: break-word; width: 50px">
                    {{ $order->comment }}
                </td>
                <td style="vertical-align: center">
                    @foreach($order->statuses as $status)
                        {{ $status->created_at }} - курьер {{ $status->subject->full_name ?? null }}, назначен
                        {{ $status->reportable->name ?? null }}
                        <br />
                    @endforeach
                </td>
                <td style="vertical-align: center">
                    {{ $order->created_at->format('d.m.y H:i') }}
                </td>
                <td style="vertical-align: center">{{ $order->delivery_cost }}</td>
                <td style="vertical-align: center">{{ $order->sum_to_office }}</td>
                <td style="vertical-align: center">{{ $order->amount }}</td>
                <td style="vertical-align: center"></td>
            </tr>
        @endforeach
        <tr>
            <td colspan="7" style="vertical-align: center">Итого:</td>
            <td style="vertical-align: center">
                {{ $operator->orders->sum('delivery_cost') }}
            </td>
            <td style="vertical-align: center">
                {{ $operator->orders->sum('sum_to_office') }}
            </td>
            <td style="vertical-align: center">
                {{ $operator->orders->sum('amount') }}
            </td>
        </tr>
    @endforeach
</table>
