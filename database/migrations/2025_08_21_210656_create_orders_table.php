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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
             $table->string('customer_name');
            $table->enum('customer_type', ['REGULAR', 'PREMIUM', 'VIP'])->default('REGULAR');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->enum('status', ['PENDING','PROCESSING','SHIPPED','DELIVERED','CANCELLED'])->default('PENDING');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
