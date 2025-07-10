<?php

namespace App\Console\Commands;

use App\Models\Order;

class FetchOrdersCommand extends AbstractFetchCommand
{
    protected $signature = 'wb:fetch-orders';
    protected $description = 'Получает данные о заказах';
    protected $endpoint = 'orders';
    protected $modelClass = Order::class;

    protected function storeItem(array $item): void
    {
        $data = [
            'g_number'          => $item['g_number'] ?? null,
            'date'              => $item['date'] ?? null,
            'last_change_date'  => $item['last_change_date'] ?? null,
            'supplier_article'  => $item['supplier_article'] ?? null,
            'tech_size'         => $item['tech_size'] ?? null,
            'barcode'           => $item['barcode'] ?? null,
            'total_price'       => $item['total_price'] ?? null,
            'discount_percent'  => $item['discount_percent'] ?? null,
            'warehouse_name'    => $item['warehouse_name'] ?? null,
            'oblast'            => $item['oblast'] ?? null,
            'income_id'         => $item['income_id'] ?? null,
            'odid'              => $item['odid'] ?? null,
            'nm_id'             => $item['nm_id'] ?? null,
            'subject'           => $item['subject'] ?? null,
            'category'          => $item['category'] ?? null,
            'brand'             => $item['brand'] ?? null,
            'is_cancel'         => $item['is_cancel'] ?? null,
            'cancel_dt'         => $item['cancel_dt'] ?? null,
        ];
        

        $this->modelClass::updateOrCreate(
            [
                'g_number' => $data['g_number'],
                'g_number' => $data['g_number'],
            ],
            $data
        );
    }
}
