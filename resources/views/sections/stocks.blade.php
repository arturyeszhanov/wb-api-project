@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-4">Остатки</h2>

    <div class="overflow-x-auto rounded shadow bg-white">
        <table class="min-w-full table-auto text-sm">
            <thead>
                <tr class="bg-gray-200 text-left text-sm uppercase whitespace-nowrap">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Дата</th>
                    <th class="px-4 py-2 hidden md:table-cell">Артикул</th>
                    <th class="px-4 py-2 hidden lg:table-cell">Штрихкод</th>
                    <th class="px-4 py-2">Кол-во</th>
                    <th class="px-4 py-2 hidden md:table-cell">В поставке</th>
                    <th class="px-4 py-2 hidden md:table-cell">На реализации</th>
                    <th class="px-4 py-2 hidden lg:table-cell">Склад</th>
                    <th class="px-4 py-2 hidden lg:table-cell">Цена</th>
                    <th class="px-4 py-2 hidden xl:table-cell">Скидка</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stocks as $stock)
                    <tr class="border-t hover:bg-gray-50 whitespace-nowrap">
                        <td class="px-4 py-2">{{ $stock->id }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($stock->date)->format('Y-m-d') }}</td>
                        <td class="px-4 py-2 hidden md:table-cell">{{ $stock->supplier_article }}</td>
                        <td class="px-4 py-2 hidden lg:table-cell">{{ $stock->barcode }}</td>
                        <td class="px-4 py-2">{{ $stock->quantity }}</td>
                        <td class="px-4 py-2 hidden md:table-cell">
                            @if($stock->is_supply)
                                <span class="text-green-600 font-semibold">Да</span>
                            @else
                                <span class="text-gray-500">Нет</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 hidden md:table-cell">
                            @if($stock->is_realization)
                                <span class="text-green-600 font-semibold">Да</span>
                            @else
                                <span class="text-gray-500">Нет</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 hidden lg:table-cell">{{ $stock->warehouse_name }}</td>
                        <td class="px-4 py-2 hidden lg:table-cell">{{ $stock->price }} ₽</td>
                        <td class="px-4 py-2 hidden xl:table-cell">{{ $stock->discount }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $stocks->links() }}
    </div>
@endsection
