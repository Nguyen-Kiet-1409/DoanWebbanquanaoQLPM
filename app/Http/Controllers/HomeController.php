<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\OrderItem;
use App\Models\ProductVariant;

class HomeController extends Controller
{
    public function index()
    {
        // 1. SẢN PHẨM MỚI (Lấy 8 sản phẩm mới nhất)
        $newProducts = Product::where('status', 1)
                            ->with('category', 'variants')
                            ->orderBy('created_at', 'desc')
                            ->take(8)
                            ->get();

        // 2. SẢN PHẨM GIẢM GIÁ (Lấy 4 sản phẩm có sale_price)
        $saleProducts = Product::where('status', 1)
                            ->with('category', 'variants')
                            ->whereHas('variants', function($query) {
                                $query->whereNotNull('sale_price');
                            })
                            ->inRandomOrder() // Lấy ngẫu nhiên
                            ->take(4)
                            ->get();

        // 3. SẢN PHẨM BÁN CHẠY (Logic phức tạp nhất)
        // Lấy 10 ID biến thể bán chạy nhất
        $bestSellingVariantIds = OrderItem::select('product_variant_id', DB::raw('SUM(quantity) as total_sold'))
                            ->groupBy('product_variant_id')
                            ->orderBy('total_sold', 'desc')
                            ->limit(10)
                            ->pluck('product_variant_id');
        
        // Từ 10 biến thể đó, tìm ra 4 sản phẩm cha
        $productIds = ProductVariant::whereIn('id', $bestSellingVariantIds)->pluck('product_id')->unique();
        
        $bestSellers = Product::whereIn('id', $productIds)
                            ->with('category', 'variants')
                            ->take(4)
                            ->get();

        // Gửi cả 3 danh sách sang View
        return view('home.index', compact('newProducts', 'saleProducts', 'bestSellers'));
    }

    public function show($id)
    {
        // Lấy sản phẩm theo ID, kèm theo các biến thể và đánh giá
        $product = Product::with([
            'category', 
            'variants.attributeValues', 
            'reviews.user', 
            'variants.inventories.branch' //Lấy tồn kho và tên chi nhánh
        ])->findOrFail($id);
        
        // Lấy thêm 4 sản phẩm liên quan (cùng danh mục) để gợi ý
        $relatedProducts = Product::where('category_id', $product->category_id)
                                  ->where('id', '!=', $id) // Trừ sản phẩm đang xem ra
                                  ->take(4)
                                  ->get();

        return view('home.show', compact('product', 'relatedProducts'));
    }

    // Xem lịch sử đơn hàng của tôi
    public function myOrders()
    {
        $userId = \Illuminate\Support\Facades\Auth::id();

        // Lấy đơn hàng của người đang đăng nhập, sắp xếp mới nhất lên đầu
        $orders = \App\Models\Order::where('user_id', \Illuminate\Support\Facades\Auth::id())
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('home.my_orders', compact('orders'));
    }

    // Xem chi tiết một đơn hàng cụ thể
    public function myOrderDetail($id)
    {
        // 1. Lấy ID người dùng VÀ GÁN VÀO BIẾN
        $userId = \Illuminate\Support\Facades\Auth::id(); 

        // 2. Lấy đơn hàng (dùng biến $userId)
        $order = \App\Models\Order::where('id', $id)
            ->where('user_id', $userId) // Dùng ở đây
            ->with('items.variant.product', 'items.variant.attributeValues')
            ->firstOrFail(); 

        // 3. Xóa cache thông báo (dùng biến $userId)
        $cacheKey = 'user_notifications_' . $userId; // Dùng ở đây
        \Illuminate\Support\Facades\Cache::forget($cacheKey);

        return view('home.order_detail', compact('order'));
    }
}