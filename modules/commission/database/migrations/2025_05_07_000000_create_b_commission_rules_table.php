<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('commission_rules', function (Blueprint $table) {
            $table->id();
            $table->decimal('from_amount', 12, 3);
            $table->decimal('to_amount',   12, 3);
            $table->decimal('percentage',  5, 2);
            $table->unsignedBigInteger('minimum_fee');
            $table->unsignedBigInteger('maximum_fee');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commission_rules');
    }
};
