<table>
    @foreach($data as $partner)
        <tr>
            <td colspan="5" style="font-weight: bold; text-align: center">
                {{ $partner->full_name }}
            </td>
        </tr>
        @foreach($partner->branches as $branch)
            <tr>
                <td colspan="5" style="font-weight: bold; text-align: center">
                    {{ $partner->full_name }}: {{ $branch->full_name }}
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold">№</td>
                <td style="font-weight: bold">Дата</td>
                <td style="font-weight: bold">Курьер</td>
                <td style="font-weight: bold">Позиции</td>
                <td style="font-weight: bold">Стоимость</td>
                <td style="font-weight: bold">Посуда</td>
                <td style="font-weight: bold">Итого</td>
                <td style="font-weight: bold">Комиссия</td>
            </tr>
            @foreach($branch->orderBranches as $orderBranch)
                @foreach($orderBranch->orderItems as $item)
                    <tr>
                        @if ($loop->first)
                            <td rowspan="{{ $orderBranch->orderItems->count() }}" style="vertical-align: center">
                                {{ $orderBranch->order_id }}
                            </td>
                            <td  rowspan="{{ $orderBranch->orderItems->count() }}" style="vertical-align: center">
                                {{ Carbon\Carbon::make($orderBranch->created_at)->format('d.m.Y H:i') }}
                            </td>
                            <td rowspan="{{ $orderBranch->orderItems->count() }}" style="vertical-align: center">
                                {{ $orderBranch->order->courier->full_name ?? '' }}
                            </td>
                        @endif
                        <td>{{ $item->name }} x {{ $item->quantity }}</td>
                        <td>{{ $item->price * $item->quantity }}</td>
                        <td>{{ $item->dishes_price }}</td>
                        @if($loop->first)
                            <td rowspan="{{ $orderBranch->orderItems->count() }}" style="vertical-align: center">
                                {{ $orderBranch->orderItems->getTotalPrice() }}
                            </td>
                            <td rowspan="{{ $orderBranch->orderItems->count() }}" style="vertical-align: center">
                                {{ $orderBranch->orderItems->getTotalPrice() * $partner->remuneration }}
                            </td>
                        @endif
                    </tr>
                @endforeach
            @endforeach
            <tr>
                <td colspan="5" style="text-align: right">
                    Итого {{ $branch->full_name }}:
                </td>
                <td style="font-weight: bold">{{ $branch->orderBranches->getTotalDishesPrice() }}</td>
                <td style="font-weight: bold">{{ $branch->orderBranches->getTotalPrice() }}</td>
                <td style="font-weight: bold">{{ $branch->orderBranches->getTotalPrice() * $partner->remuneration }}</td>
            </tr>
            @if(! $loop->last)
                <tr><td colspan="6"></td></tr>
            @endif
        @endforeach
        <tr>
            <td colspan="5" style="text-align: right">
                Итого {{ $partner->full_name }}:
            </td>
            <td style="font-weight: bold">{{ $partner->branches->getTotalDishesPrice() }}</td>
            <td style="font-weight: bold">{{ $partner->branches->getTotalPrice() }}</td>
            <td style="font-weight: bold">{{ $partner->branches->getTotalPrice() * $partner->remuneration }}</td>
        </tr>
        <tr><td colspan="6"></td></tr>
    @endforeach
</table>
