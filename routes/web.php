<?php

use Illuminate\Support\Facades\Route;

// Import các Controller CHỈ dành cho Gói 2
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Đây là nơi bạn đăng ký các web route cho GÓI 2 (Admin cơ bản).
| Các route cho Storefront (Gói 5) và Auth (Gói 4) sẽ do
| các thành viên khác thêm vào sau.
|
*/

// Route mặc định, có thể để hoặc xóa tùy ý
Route::get('/', function () {
    return view('welcome'); // Giả sử Gói 4 (Auth/Layouts) sẽ cung cấp file này
});


// 2. NHÓM QUẢN TRỊ (Phải đăng nhập mới xem được)
// Các route này sẽ tạm thời chưa hoạt động cho đến khi 
// Thành viên C (Gói 4) merge code "Xác thực" (auth) của họ.
Route::middleware(['auth'])->group(function () {

    // Dashboard (Trang quản trị chính)
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['verified']) // 'auth' đã có ở group
        ->name('dashboard');

    // Quản lý Chi nhánh
    Route::resource('branches', BranchController::class);

    // Quản lý Danh mục
    Route::resource('categories', CategoryController::class);

    // Quản lý Sản phẩm
    Route::resource('products', ProductController::class);

    // Quản lý Nhân viên
    Route::resource('staff', StaffController::class);

});

// Các route xác thực (Login/Register)
// SẼ DO THÀNH VIÊN C (GÓI 4) THÊM VÀO SAU.
// require __DIR__.'/auth.php';
// Auth::routes();