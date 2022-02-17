<table>
    <tr>
        <td colspan="8" style="font-weight: bold; text-align: center">
            Сводный отчет по операторам:
        </td>
    </tr>
    <tr>
        <td colspan="8" style="font-weight: bold; text-align: center">
            с: {{ $from }} по: {{ $to }}
        </td>
    </tr>
    @foreach($data as $name => $stat)
        <tr></tr>
        <tr>
            <td style="text-align: center; font-weight: bold">
                Оператор
            </td>
            <td style="text-align: center; font-weight: bold">Дата</td>
            <td style="text-align: center; font-weight: bold">Количество заказов</td>
            <td style="text-align: center; font-weight: bold">
                Ставка за день
            </td>
            <td style="text-align: center; font-weight: bold">
                Процент с заказов
            </td>
            <td style="text-align: center; font-weight: bold">Сумма доставки</td>
            <td style="text-align: center; font-weight: bold">Сумма в офис</td>
            <td style="text-align: center; font-weight: bold">Сумма авансов</td>
            <td style="text-align: center; font-weight: bold">Сумма штрафов</td>
        </tr>
        @foreach($stat['data'] as $date => $data)
            <tr>
                @if($loop->first)
                    <td rowspan="{{ count($stat['data']) }}" style="text-align: center; vertical-align: center">
                        {{ $name }}
                    </td>
                @endif
                <td>{{ $date }}</td>
                <td>{{ $data['orders_count'] }}</td>
                <td>0</td>
                <td>0</td>
                <td>{{ $data['delivery_cost'] }}</td>
                <td>{{ $data['sum_to_office'] }}</td>
                <td>0</td>
                <td>0</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2">Итого:</td>
            <td>{{ $stat['total']['orders_count'] }}</td>
            <td></td>
            <td></td>
            <td>{{ $stat['total']['delivery_cost'] }}</td>
            <td>{{ $stat['total']['sum_to_office'] }}</td>
            <td></td>
            <td></td>
        </tr>
    @endforeach
    <tr></tr>
    <tr>
        <td colspan="2">Всего:</td>
        <td>{{ $total['orders_count'] }}</td>
        <td>{{ $total['delivery_cost'] }}</td>
        <td>{{ $total['sum_to_office'] }}</td>
    </tr>
</table>
