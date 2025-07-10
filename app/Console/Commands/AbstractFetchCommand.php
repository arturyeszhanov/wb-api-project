<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

abstract class AbstractFetchCommand extends Command
{
    protected $baseUrl = 'http://109.73.206.144:6969/api';
    protected $endpoint;
    protected $modelClass;
    protected $dateFrom;

    public function handle()
    {
        $this->setDateRange();
        // Если данных в таблице нет — загружаем всю историю
        if ($this->isFirstImport()) {
            $this->setInitialFullImportDateRange();
            $this->info("Первый импорт: загружаем все данные с {$this->dateFrom}");
        } else {
                $this->setDateRange();
                $this->info("Данные за последние 3 дня: загружаем данные с {$this->dateFrom} по {$this->dateTo}");
            }

        $page = 1;
        $limit = 500;
        $totalRecords = 0;
        
        $this->info("Начинается импорт {$this->endpoint} с {$this->dateFrom} по {$this->dateTo}");
        
        do {
            $response = Http::retry(3, 1000)->get("{$this->baseUrl}/{$this->endpoint}", [
                'page' => $page,
                'dateFrom' => $this->dateFrom,
                'dateTo' => $this->dateTo,
                'key' => env('WB_API_KEY'),
                'limit' => $limit
            ]);
        
            if (!$response->successful()) {
                $this->error("Ошибка при запросе: " . $response->body());
                return Command::FAILURE;
            }
        
            $body = $response->json();
            $data = $body['data'] ?? [];
            $meta = $body['meta'] ?? [];
        
            foreach ($data as $item) {
                try {
                    $isNew = $this->storeItem($item);
                    if ($isNew) {
                        $newRecords++;
                    }
                } catch (\Throwable $e) {
                    $this->error("❌ Ошибка при обработке записи:");
                    $this->error(json_encode($item));
                    $this->error($e->getMessage());
                }
            }
            
        
            $count = count($data);
            $totalRecords += $count;
        
            $this->info("Обработана страница $page: $count записей");
        
            $page++;
        } while (isset($meta['current_page'], $meta['last_page']) && $meta['current_page'] < $meta['last_page']);
        
        $this->afterImport();
        
        $this->info("Импорт {$this->endpoint} завершён.");
        $this->info("Диапазон дат: с {$this->dateFrom} по {$this->dateTo}");
        $this->info("Всего обработано записей: $totalRecords");
        
        return Command::SUCCESS;
        
    }

    // Проверяет, пустая ли таблица (это первый импорт?)
    protected function isFirstImport(): bool
    {
        return $this->modelClass::count() === 0;
    }

    protected function setInitialFullImportDateRange(): void
    {   
        // Определяем диапозон для первого запуска: 2025-07-01 или дата старта работы компании
        $this->dateFrom = '2025-07-01';
        $this->dateTo = now()->format('Y-m-d');
    }

    protected function setDateRange(): void
    {
        // По умолчанию: последние 2 дня
        $this->dateFrom = now()->subDays(1)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    protected function afterImport(): void
    {

    }

    abstract protected function storeItem(array $item): void;
}