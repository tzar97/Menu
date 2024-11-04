<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabla de Pedidos (orders)
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('table_id')->nullable(); // ID de la mesa (sin relación con la tabla users)
            $table->string('status')->default('pendiente'); // Estado del pedido
            $table->timestamps();
            $table->integer('mesa')->unique()->nullable(); // Opción única para evitar números repetidos

        });

        // Tabla de Artículos del Pedido (order_items)
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // Relación con la tabla orders
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Relación con la tabla products
            $table->integer('quantity'); // Cantidad de cada producto
            $table->decimal('price', 8, 2); // Precio del producto
            $table->string('status')->default('pendiente');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar primero la tabla order_items, ya que depende de orders
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
