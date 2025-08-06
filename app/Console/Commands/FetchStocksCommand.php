<?php

namespace App\Console\Commands;

use App\Models\Stock;

class FetchStocksCommand extends AbstractFetchCommand
{$this->info(">>>>> КОМАНДА ЗАПУСТИЛАСЬ <<<<<");
    protected $signature = 'wb:fetch-stocks';
    protected $description = 'Получает остатки товаров';
    protected $endpoint = 'stocks';
    protected $modelClass = Stock::class;

    protected function setDateRange(): void
    {
        // Переопределяем диапазон: только текущий день
        $this->dateFrom = now()->subDay()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    protected function storeItem(array $item): void
    {
        $data = [
            'date' => $item['date'] ?? null,
            'last_change_date' => $item['last_change_date'] ?? null,
            'supplier_article' => $item['supplier_article'] ?? null,
            'tech_size' => $item['tech_size'] ?? null,
            'barcode' => $item['barcode'] ?? null,
            'quantity' => $item['quantity'] ?? null,
            'is_supply' => $item['is_supply'] ?? null,
            'is_realization' => $item['is_realization'] ?? null,
            'quantity_full' => $item['quantity_full'] ?? null,
            'warehouse_name' => $item['warehouse_name'] ?? null,
            'in_way_to_client' => $item['in_way_to_client'] ?? null,
            'in_way_from_client' => $item['in_way_from_client'] ?? null,
            'nm_id' => $item['nm_id'] ?? null,
            'subject' => $item['subject'] ?? null,
            'category' => $item['category'] ?? null,
            'brand' => $item['brand'] ?? null,
            'sc_code' => $item['sc_code'] ?? null,
            'price' => $item['price'] ?? null,
            'discount' => $item['discount'] ?? null,
        ];

        $this->modelClass::updateOrCreate(
            [
                'date' => $data['date'],
                'barcode' => $data['barcode'],
                'warehouse_name' => $data['warehouse_name'],
                'last_change_date' => $data['last_change_date'],
                'is_supply' => $data['is_supply'],
            ],
            $data
        );
    }
}
