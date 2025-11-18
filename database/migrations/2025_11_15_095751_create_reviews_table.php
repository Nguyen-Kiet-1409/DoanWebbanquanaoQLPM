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
        Schema::create('reviews', function (Blueprint $table) {
        $table->id();
        
        // 1. Ai đánh giá? (Nối với bảng users)
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        
        // 2. Đánh giá sản phẩm nào? (Nối với bảng products)
        $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
        
        // 3. Số sao (1 đến 5)
        $table->tinyInteger('rating')->default(5);
        
        // 4. Nội dung bình luận
        $table->text('comment')->nullable();
        
        // 5. Trạng thái (Để sau này Admin duyệt bài nếu cần, tạm thời cho hiện luôn = 1)
        $table->tinyInteger('status')->default(1); 

        $table->timestamps(); // Ngày giờ đánh giá
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
