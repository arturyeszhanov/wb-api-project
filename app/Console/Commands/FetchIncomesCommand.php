<?php

namespace App\Console\Commands;

use App\Models\Income;

class FetchIncomesCommand extends AbstractFetchCommand
{
    protected $signature = 'wb:fetch-incomes';
    protected $description = 'Получает данные о доходах';
    protected $endpoint = 'incomes';
    protected $modelClass = Income::class;

    protected function storeItem(array $item): void
    {
        $data = [
            'income_id'         => $item['income_id'] ?? null,
            'number'            => $item['number'] ?? null,
            'date'              => $item['date'] ?? null,
            'last_change_date'  => $item['last_change_date'] ?? null,
            'supplier_article'  => $item['supplier_article'] ?? null,
            'tech_size'         => $item['tech_size'] ?? null,
            'barcode'           => $item['barcode'] ?? null,
            'quantity'          => $item['quantity'] ?? null,
            'total_price'       => $item['total_price'] ?? null,
            'date_close'        => $item['date_close'] ?? null,
            'warehouse_name'    => $item['warehouse_name'] ?? null,
            'nm_id'             => $item['nm_id'] ?? null,
        ];        

        $this->modelClass::updateOrCreate(
            [
                'income_id' => $data['income_id'],
                'number' => $data['number'],
                'warehouse_name' => $data['warehouse_name'],
            ],
            $data
        );
    }
}
