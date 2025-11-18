<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            // Chi nhánh sẽ xử lý đơn hàng này
            $table->foreignId('branch_id')
                  ->nullable()
                  ->constrained('branches')
                  ->onDelete('set null');

            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->text('customer_address');
            
            $table->decimal('total_amount', 12, 2);
            $table->string('payment_method')->default('COD');
            $table->tinyInteger('status')->default(0); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};