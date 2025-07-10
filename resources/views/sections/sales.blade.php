@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-4">Продажи</h2>
    <div class="overflow-x-auto rounded shadow bg-white">
        <table class="min-w-full table-auto text-sm">
            <thead>
                <tr class="bg-gray-200 text-left text-sm uppercase whitespace-nowrap">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Дата</th>
                    <th class="px-4 py-2">Сумма</th>
                    <th class="px-4 py-2 hidden md:table-cell">Артикул</th>
                    <th class="px-4 py-2 hidden lg:table-cell">Штрихкод</th>
                    <th class="px-4 py-2 hidden lg:table-cell">Склад</th>
                    <th class="px-4 py-2 hidden xl:table-cell">Регион</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @foreach($sales as $sale)
                    <tr class="border-t hover:bg-gray-50 whitespace-nowrap">
                        <td class="px-4 py-2">{{ $sale->id }}</td>
                        <td class="px-4 py-2">{{ $sale->date}}</td>
                        <td class="px-4 py-2">{{ $sale->total_price }} ₽</td>
                        <td class="px-4 py-2 hidden md:table-cell">{{ $sale->supplier_article }}</td>
                        <td class="px-4 py-2 hidden lg:table-cell">{{ $sale->barcode }}</td>
                        <td class="px-4 py-2 hidden lg:table-cell">{{ $sale->warehouse_name }}</td>
                        <td class="px-4 py-2 hidden xl:table-cell">{{ $sale->region_name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $sales->links() }}
    </div>
@endsection
