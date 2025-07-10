<?php
namespace App\Http\Controllers;
use App\Models\Sale;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Income;

use App\Http\Controllers\Controller;

class MainController extends Controller
{
    public function index() {
        return view('index');
    }

    public function sales() {
        $sales = Sale::latest()->paginate(30);
        return view('sections.sales', compact('sales'));
    }

    public function orders() {
        $orders = Order::orderBy('date', 'desc')->paginate(30);
        return view('sections.orders', compact('orders'));
    }

    public function stocks() {
        $stocks = Stock::orderBy('date', 'desc')->paginate(30);
        return view('sections.stocks', compact('stocks'));
    }

    public function incomes() {
        $incomes = Income::latest()->paginate(30);
        return view('sections.incomes', compact('incomes'));
    }
}
