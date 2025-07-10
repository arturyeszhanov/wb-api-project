<?php

namespace App\Console\Commands;

use App\Models\Sale;

class FetchSalesCommand extends AbstractFetchCommand
{
    protected $signature = 'wb:fetch-sales';
    protected $description = 'Получает данные о продажах';
    protected $endpoint = 'sales';
    protected $modelClass = Sale::class;

    protected function storeItem(array $item): void
    {
        $data = [
            'g_number'            => $item['g_number'] ?? null,
            'date'                => $item['date'] ?? null,
            'last_change_date'    => $item['last_change_date'] ?? null,
            'supplier_article'    => $item['supplier_article'] ?? null,
            'tech_size'           => $item['tech_size'] ?? null,
            'barcode'             => $item['barcode'] ?? null,
            'total_price'         => isset($item['total_price']) ? round((float) $item['total_price'], 2) : null,
            'discount_percent'    => isset($item['discount_percent']) ? (int) $item['discount_percent'] : null,
            'is_supply'           => array_key_exists('is_supply', $item) ? (bool) $item['is_supply'] : null,
            'is_realization'      => array_key_exists('is_realization', $item) ? (bool) $item['is_realization'] : null,
            'promo_code_discount' => isset($item['promo_code_discount']) ? round((float) $item['promo_code_discount'], 2) : null,
            'warehouse_name'      => $item['warehouse_name'] ?? null,
            'country_name'        => $item['country_name'] ?? null,
            'oblast_okrug_name'   => $item['oblast_okrug_name'] ?? null,
            'region_name'         => $item['region_name'] ?? null,
            'income_id'           => isset($item['income_id']) ? (int) $item['income_id'] : null,
            'sale_id'             => $item['sale_id'] ?? null,
            'odid'                => isset($item['odid']) ? (int) $item['odid'] : null,
            'spp'                 => isset($item['spp']) ? (int) $item['spp'] : null,
            'for_pay'             => isset($item['for_pay']) ? round((float) $item['for_pay'], 2) : null,
            'finished_price'      => isset($item['finished_price']) ? round((float) $item['finished_price'], 2) : null,
            'price_with_disc'     => isset($item['price_with_disc']) ? round((float) $item['price_with_disc'], 2) : null,
            'nm_id'               => isset($item['nm_id']) ? (int) $item['nm_id'] : null,
            'subject'             => $item['subject'] ?? null,
            'category'            => $item['category'] ?? null,
            'brand'               => $item['brand'] ?? null,
            'is_storno'           => array_key_exists('is_storno', $item) ? (bool) $item['is_storno'] : null,
        ];
    
        $this->modelClass::updateOrCreate(
            [
                'g_number' => $data['g_number'],
                'sale_id' => $data['sale_id'],
            ],
            $data
        );
        
        
    }
    
}