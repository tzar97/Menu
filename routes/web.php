<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChefOrderController;
use App\Http\Controllers\SalesController;

Route::get('/', function () {return view('inicio');});


Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);

// Rutas para CartController

    // Mostrar los productos en el carrito del usuario
Route::get('/cart', [CartController::class, 'show']);
    
    // Agregar un producto al carrito
Route::post('/cart/add', [CartController::class, 'addToCart']);
    
    // Finalizar el carrito y cambiar el estado a "pendiente"
Route::post('/cart/finalize', [CartController::class, 'finalizeCart']);
    
    // Eliminar un producto del carrito
Route::delete('/cart/remove', [CartController::class, 'removeFromCart']);
    
    // Confirmar el carrito y marcarlo como "pedido"
Route::post('/cart/checkout', [CartController::class, 'checkout']);




// Rutas para ChefOrderController
Route::get('/orders/pending', [ChefOrderController::class, 'getPendingOrders']); // Obtener pedidos pendientes
Route::post('/orders/{id}/start', [ChefOrderController::class, 'startOrder']); // Iniciar pedido
Route::post('/orders/{id}/complete', [ChefOrderController::class, 'completeOrder']); // Completar pedido
Route::delete('/orders/{id}', [ChefOrderController::class, 'deleteOrder']);


Route::get('/sales', [SalesController::class, 'getSales']);
