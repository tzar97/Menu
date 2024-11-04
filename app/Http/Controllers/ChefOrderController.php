<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderItem; // Asegúrate de importar el modelo OrderItem
use App\Models\Order;      // Importa la clase Order
use Illuminate\Support\Facades\DB;


class ChefOrderController extends Controller
{
    public function getPendingOrders()
    {
    $pendingOrders = Order::with(['orderItems' => function($query) {
        $query->select('id', 'order_id', 'product_id', 'quantity', 'price', 'status')
              ->with('product:id,name'); // Asegúrate de cargar el nombre del producto
    }])
    ->where('status', 'pendiente')
    ->get();

    return response()->json($pendingOrders);
    }


    

    public function startOrder($id) {
        $orderItem = OrderItem::findOrFail($id);
        $orderItem->status = 'en preparación';
        $orderItem->save();
    
        return response()->json(['message' => 'Pedido en preparación']);
    }


    public function completeOrder($id, Request $request)
    {
    try {
        // Encuentra la orden
        $order = Order::with('orderItems')->findOrFail($id);
        
        // Detiene el temporizador y calcula el tiempo de preparación
        $preparationTime = $request->input('preparationTime');
        
        // Marca la orden como finalizada
        $order->status = 'finalizado';
        $order->save();

        // Itera sobre cada item de la orden para registrar la venta
        foreach ($order->orderItems as $item) {
            DB::table('ventas')->insert([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'preparation_time' => $preparationTime,
                'sale_date' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return response()->json(['message' => 'Pedido completado y registrado en ventas'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al completar el pedido y registrar en ventas', 'details' => $e->getMessage()], 500);
    }
    }



    
    public function deleteOrder($id) {
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json(['message' => 'Pedido eliminado']);
    }
}
