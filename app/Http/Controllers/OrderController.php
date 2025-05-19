<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Reporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{

   public function index()
{
    return view('orders.index', [
        'orders' => [],
        'headers' => [],
        'esDinamico' => false
    ]);
}


    public function simularApi()
{

    
    $orders = Order::limit(10)->get(); // simula 10 registros de un wip para el ejemplo

    if ($orders->isEmpty()) {
        return response()->json(['headers' => [], 'rows' => []]);
    }

    $headers = array_keys($orders[0]->toArray());
    $rows = $orders->map(function ($order) {
        return array_values($order->toArray());
    });

    return response()->json([
        'headers' => $headers,
        'rows' => $rows
    ]);
}

public function cargarDesdeApiSimulada()
{
    // Llama directamente al método simularApi sin hacer HTTP
    $response = $this->simularApi();

    $data = $response->getData(true); // Convierte a array

    return view('orders.index', [
        'orders' => $data['rows'],
        'headers' => $data['headers'],
        'esDinamico' => true
    ]);
}









}
