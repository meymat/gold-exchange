<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()
                ->onDelete('cascade');
            $table->enum('type', ['buy', 'sell']);
            $table->unsignedBigInteger('price');
            $table->decimal('initial_quantity', 12, 3);
            $table->decimal('remaining_quantity', 12, 3);
            $table->foreignId('status_id')
                ->constrained('b_order_statuses')
                ->onUpdate('cascade');
            $table->timestamp('executed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
