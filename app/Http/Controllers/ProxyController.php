<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProxyController extends Controller
{
    protected $apiUrl = 'http://109.73.206.144:6969/api';
    protected $apiKey = 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie';

    public function orders(Request $request)
    {
        $response = Http::get("{$this->apiUrl}/orders", [
            'dateFrom' => $request->query('dateFrom'),
            'dateTo'   => $request->query('dateTo'),
            'page'     => $request->query('page', 1),
            'limit'    => $request->query('limit', 500),
            'key'      => $this->apiKey,
        ]);

        return $response->json();
    }

    public function sales(Request $request)
    {
        $response = Http::get("{$this->apiUrl}/sales", [
            'dateFrom' => $request->query('dateFrom'),
            'dateTo'   => $request->query('dateTo'),
            'page'     => $request->query('page', 1),
            'limit'    => $request->query('limit', 500),
            'key'      => $this->apiKey,
        ]);

        return $response->json();
    }

    public function stocks(Request $request)
    {
        $response = Http::get("{$this->apiUrl}/stocks", [
            'dateFrom' => $request->query('dateFrom'),
            'dateTo'   => $request->query('dateTo'),
            'page'     => $request->query('page', 1),
            'limit'    => $request->query('limit', 500),
            'key'      => $this->apiKey,
        ]);

        return $response->json();
    }

    public function incomes(Request $request)
    {
        $response = Http::get("{$this->apiUrl}/incomes", [
            'dateFrom' => $request->query('dateFrom'),
            'dateTo'   => $request->query('dateTo'),
            'page'     => $request->query('page', 1),
            'limit'    => $request->query('limit', 500),
            'key'      => $this->apiKey,
        ]);

        return $response->json();
    }
}
