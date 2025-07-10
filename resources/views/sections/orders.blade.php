@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-4">Заказы</h2>

    <div class="overflow-x-auto rounded shadow bg-white">
        <table class="min-w-full table-auto text-sm">
            <thead>
                <tr class="bg-gray-200 text-left text-sm uppercase whitespace-nowrap">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Дата</th>
                    <th class="px-4 py-2 hidden md:table-cell">Артикул</th>
                    <th class="px-4 py-2 hidden lg:table-cell">Штрихкод</th>
                    <th class="px-4 py-2 hidden xl:table-cell">Склад</th>
                    <th class="px-4 py-2 hidden xl:table-cell">Область</th>
                    <th class="px-4 py-2">Сумма</th>
                    <th class="px-4 py-2 hidden md:table-cell">Скидка</th>
                    <th class="px-4 py-2 hidden md:table-cell">Отмена</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr class="border-t hover:bg-gray-50 whitespace-nowrap">
                        <td class="px-4 py-2">{{ $order->id }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($order->date)->format('Y-m-d') }}</td>
                        <td class="px-4 py-2 hidden md:table-cell">{{ $order->supplier_article }}</td>
                        <td class="px-4 py-2 hidden lg:table-cell">{{ $order->barcode }}</td>
                        <td class="px-4 py-2 hidden xl:table-cell">{{ $order->warehouse_name }}</td>
                        <td class="px-4 py-2 hidden xl:table-cell">{{ $order->oblast }}</td>
                        <td class="px-4 py-2">{{ $order->total_price }} ₽</td>
                        <td class="px-4 py-2 hidden md:table-cell">{{ $order->discount_percent }}%</td>
                        <td class="px-4 py-2 hidden md:table-cell">
                            @if($order->is_cancel)
                                <span class="text-red-600 font-semibold">Да</span>
                            @else
                                <span class="text-green-600">Нет</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
@endsection
