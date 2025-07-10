@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-4">Приходы</h2>

    <div class="overflow-x-auto rounded shadow bg-white">
        <table class="min-w-full table-auto text-sm">
            <thead>
                <tr class="bg-gray-200 text-left text-sm uppercase whitespace-nowrap">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Дата</th>
                    <th class="px-4 py-2 hidden md:table-cell">Артикул</th>
                    <th class="px-4 py-2 hidden lg:table-cell">Размер</th>
                    <th class="px-4 py-2 hidden lg:table-cell">Штрихкод</th>
                    <th class="px-4 py-2 hidden xl:table-cell">Кол-во</th>
                    <th class="px-4 py-2">Сумма</th>
                    <th class="px-4 py-2 hidden xl:table-cell">Склад</th>
                    <th class="px-4 py-2 hidden xl:table-cell">Дата закрытия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($incomes as $income)
                    <tr class="border-t hover:bg-gray-50 whitespace-nowrap">
                        <td class="px-4 py-2">{{ $income->income_id }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($income->date)->format('Y-m-d') }}</td>
                        <td class="px-4 py-2 hidden md:table-cell">{{ $income->supplier_article }}</td>
                        <td class="px-4 py-2 hidden lg:table-cell">{{ $income->tech_size }}</td>
                        <td class="px-4 py-2 hidden lg:table-cell">{{ $income->barcode }}</td>
                        <td class="px-4 py-2 hidden xl:table-cell">{{ $income->quantity }}</td>
                        <td class="px-4 py-2">{{ number_format($income->total_price, 2, ',', ' ') }} ₽</td>
                        <td class="px-4 py-2 hidden xl:table-cell">{{ $income->warehouse_name }}</td>
                        <td class="px-4 py-2 hidden xl:table-cell">{{ \Carbon\Carbon::parse($income->date_close)->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $incomes->links() }}
    </div>
@endsection
