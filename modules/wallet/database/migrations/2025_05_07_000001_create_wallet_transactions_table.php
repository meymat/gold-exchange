<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()
                ->onDelete('cascade');
            $table->enum('type', ['credit', 'debit']);
            $table->string('description')->nullable();
            $table->decimal('gold_amount', 16, 3)->default(0);
            $table->unsignedBigInteger('rial_amount')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
