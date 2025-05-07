<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buy_order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('sell_order_id')->constrained('orders')->onDelete('cascade');
            $table->unsignedBigInteger('price');
            $table->decimal('quantity', 12, 3);
            $table->unsignedBigInteger('commission_buyer');
            $table->unsignedBigInteger('commission_seller');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
