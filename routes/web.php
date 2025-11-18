<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Import các Controller CHỈ dành cho Gói 2
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DashboardController;

// --- DÁN CÁC "USE" CỦA GÓI 3 VÀO ĐÂY ---
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttributeValueController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\InventoryController;

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

    // Quản lý Thuộc tính
    Route::resource('attributes', AttributeController::class);
    Route::resource('attribute-values', AttributeValueController::class);

    // 2. Dán đoạn này vào BÊN TRONG nhóm middleware(['auth']):
    // Quản lý Profile cá nhân
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Các route xử lý Biến thể (Variant) ---
    Route::get('/products/{product}/variants', [ProductVariantController::class, 'index'])->name('product.variants.index');
    Route::post('/products/{product}/variants', [ProductVariantController::class, 'store'])->name('product.variants.store');
    Route::get('/variants/{variant}/edit', [ProductVariantController::class, 'edit'])->name('product.variants.edit');
    Route::put('/variants/{variant}', [ProductVariantController::class, 'update'])->name('product.variants.update');
    Route::delete('/variants/{variant}', [ProductVariantController::class, 'destroy'])->name('product.variants.destroy');

    // --- Các route xử lý Tồn kho (Inventory) ---
    Route::get('/products/{product}/inventory', [InventoryController::class, 'edit'])->name('inventory.edit');
    Route::post('/products/{product}/inventory', [InventoryController::class, 'update'])->name('inventory.update');
});

// Các route xác thực (Login/Register)
// SẼ DO THÀNH VIÊN C (GÓI 4) THÊM VÀO SAU.
// require __DIR__.'/auth.php';
// Auth::routes();
require __DIR__.'/auth.php';