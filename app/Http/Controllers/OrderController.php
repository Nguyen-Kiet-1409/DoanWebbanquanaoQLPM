<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Inventory;
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
{
    // 1. Danh sách đơn hàng
    public function index()
    {
        Cache::forget('new_orders_count'); // Xóa cache khi admin vào xem
        $user = Auth::user();

        if ($user->role == 0) {
            // Nếu là Admin (Role 0) -> Xem hết, đơn mới nhất lên đầu
            $orders = Order::orderBy('created_at', 'desc')->get();
        } else {
            // Nếu là Nhân viên -> Chỉ xem đơn thuộc chi nhánh mình
            $orders = Order::where('branch_id', $user->branch_id)->orderBy('created_at', 'desc')->get();
        }

        return view('orders.index', compact('orders'));
    }

    // 2. Xem chi tiết đơn hàng
    public function show($id)
    {
        // Lấy đơn hàng
        $order = Order::with('items.variant.product', 'items.variant.attributeValues')->findOrFail($id);
        
        // Lấy danh sách chi nhánh để Admin chọn phân bổ
        $branches = \App\Models\Branch::all(); 

        return view('orders.show', compact('order', 'branches'));
    }

    // 3. Cập nhật trạng thái và phân bổ
    public function updateStatus(Request $request, $id)
    {
        $order = Order::with('items')->findOrFail($id);
        
        $oldStatus = $order->status;
        $newStatus = $request->status;
        
        $oldBranchId = $order->branch_id; // Lưu lại kho cũ (Vĩnh Xương)
        $newBranchId = $request->input('branch_id', $oldBranchId); // Kho mới (Tân Châu)

        // --- LOGIC 1: XỬ LÝ CHUYỂN KHO (ĐIỀU CHUYỂN) ---
        // Nếu có sự thay đổi chi nhánh VÀ đơn hàng chưa bị hủy
        if ($oldBranchId != $newBranchId && $oldStatus != 4) {
            
            foreach ($order->items as $item) {
                // 1. Hoàn trả số lượng cho kho cũ (Vĩnh Xương)
                $oldInv = Inventory::where('product_variant_id', $item->product_variant_id)
                                   ->where('branch_id', $oldBranchId)
                                   ->first();
                if ($oldInv) $oldInv->increment('quantity', $item->quantity);

                // 2. Trừ số lượng ở kho mới (Tân Châu)
                $newInv = Inventory::updateOrCreate(
                    ['product_variant_id' => $item->product_variant_id, 'branch_id' => $newBranchId],
                    ['quantity' => \DB::raw("quantity - {$item->quantity}")] // Trừ đi, chấp nhận âm nếu kho thiếu
                );
            }
        }

        // Nếu trạng thái thay đổi VÀ đơn hàng này có khách hàng (user_id)
        if ($oldStatus != $newStatus && $order->user_id) {
            $userId = $order->user_id; // ID của khách
            $cacheKey = 'user_notifications_' . $userId; // Khóa cache riêng
            
            // Lấy danh sách thông báo cũ (nếu có)
            $notifications = Cache::get($cacheKey, []);
            // Thêm ID đơn hàng này vào
            $notifications[] = $order->id;
            
            // Lưu lại danh sách mới vào cache (lưu trong 1 ngày)
            Cache::put($cacheKey, array_unique($notifications), now()->addDay());
        }

        // Cập nhật thông tin đơn hàng
        $order->status = $newStatus;
        $order->branch_id = $newBranchId;
        $order->save();

        // --- LOGIC 2: HOÀN KHO KHI HỦY ĐƠN (Code cũ) ---
        if ($newStatus == 4 && $oldStatus != 4) {
            foreach ($order->items as $item) {
                $inventory = Inventory::where('product_variant_id', $item->product_variant_id)
                                      ->where('branch_id', $order->branch_id) // Lúc này đã là branch mới
                                      ->first();
                if ($inventory) $inventory->increment('quantity', $item->quantity);
            }
        }

        return redirect()->back()->with('success', 'Cập nhật đơn hàng thành công!');
    }
}