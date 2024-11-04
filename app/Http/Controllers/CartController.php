<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Order;

class CartController extends Controller
{
    // Mostrar todos los productos en el carrito del usuario
    public function show() {
        $userId = auth()->id();
        $cartItems = OrderItem::where('user_id', $userId)->where('status', 'en carrito')->get();
        return response()->json($cartItems);
    }

    // Agregar un producto al carrito del usuario
    public function addToCart(Request $request) {
        $orderItem = new OrderItem();
        $orderItem->user_id = auth()->id();  // Asigna el ID del usuario autenticado
        $orderItem->product_id = $request->input('product_id');
        $orderItem->quantity = $request->input('quantity');
        $orderItem->price = $request->input('price');
        $orderItem->status = 'en carrito';
        $orderItem->save();

        return response()->json(['message' => 'Producto añadido al carrito']);
    }
    
    // Finalizar el carrito y cambiar el estado a "pendiente"
    public function finalizeCart() {
        $userId = auth()->id();

        // Crear un nuevo pedido
        $order = Order::create([
            'table_id' => $userId, // Se usa table_id como número de mesa
            'status' => 'pendiente',
            
        ]);

        // Actualizar los artículos del carrito con el nuevo order_id
        OrderItem::where('user_id', $userId)
            ->where('status', 'en carrito')
            ->update([
                'order_id' => $order->id,
                'status' => 'pendiente'
            ]);

        return response()->json(['message' => 'Pedido finalizado y enviado al chef']);
    }
    
    // Eliminar un producto específico del carrito
    public function removeFromCart(Request $request) {
        $userId = auth()->id();
        $productId = $request->input('product_id');

        OrderItem::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('status', 'en carrito')
            ->delete();

        return response()->json(['message' => 'Producto eliminado del carrito']);
    }

    // Confirmar el carrito y marcarlo como pedido
    public function checkout(Request $request) {
        try {
            $cartItems = $request->input('cart'); // Recibe los datos enviados desde Vue
            
            // Crea el pedido en la tabla `orders`
            $order = Order::create([
                'table_id' => $request->input('table_id'), // Número de mesa enviado desde el frontend
                'status' => 'pendiente'
            ]);
    
            // Inserta cada producto del carrito en la tabla `order_items`
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id, // Asegúrate de que `order_id` esté presente
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'status' => 'pendiente'
                ]);
            }
    
            return response()->json(['message' => 'Pedido realizado con éxito']);
        } catch (\Exception $e) {
            Log::error('Error en checkout: ' . $e->getMessage());
    
            return response()->json(['message' => 'Error al realizar el pedido', 'error' => $e->getMessage()], 500);
        }
    }
    
}
