<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Logic phân quyền:
        // Nếu là Admin (Role 0): Xem toàn bộ thống kê
        // Nếu là Nhân viên (Role 1): Chỉ xem thống kê của chi nhánh mình
        
        if ($user->role == 0) {
            $totalOrders = Order::count();
            // Chỉ tính doanh thu các đơn đã hoàn thành (status = 3)
            $revenue = Order::where('status', 3)->sum('total_amount');
            $totalProducts = Product::count();
            $totalCustomers = User::where('role', 2)->count();
            
            // Lấy 5 đơn mới nhất
            $recentOrders = Order::orderBy('created_at', 'desc')->take(5)->get();
        } else {
            // Dành cho nhân viên (chỉ tính theo branch_id)
            $branchId = $user->branch_id;
            $totalOrders = Order::where('branch_id', $branchId)->count();
            $revenue = Order::where('branch_id', $branchId)->where('status', 3)->sum('total_amount');
            $totalProducts = Product::count(); // Sản phẩm là chung
            $totalCustomers = User::where('role', 2)->count(); // Khách là chung
            
            $recentOrders = Order::where('branch_id', $branchId)->orderBy('created_at', 'desc')->take(5)->get();
        }

        $newOrdersCount = Cache::get('new_orders_count', 0);
        $newReviewsCount = Cache::get('new_reviews_count', 0);

        return view('dashboard', compact(
            'totalOrders', 'revenue', 'totalProducts', 'totalCustomers', 'recentOrders', 
            'newOrdersCount', 'newReviewsCount'
        ));
    }
}