<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductVariant;

class CartController extends Controller
{
    // 1. Xem giỏ hàng
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    // 2. Thêm vào giỏ
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_variant_id' => 'required',
            'quantity' => 'required|integer|min:1',
            'branch_id' => 'required'
        ]);

        $variantId = $request->product_variant_id;
        $quantity = $request->quantity;
        $branchId = $request->branch_id;

        // Lấy thông tin chi tiết biến thể (Kèm tên SP cha và màu/size)
        $variant = ProductVariant::with('product', 'attributeValues')->findOrFail($variantId);

        // Lấy giỏ hàng hiện tại từ session
        $cart = session()->get('cart', []);

        // Tạo tên đầy đủ
        $attrString = $variant->attributeValues->pluck('value')->implode(' - ');
        $fullName = $variant->product->name . ' (' . $attrString . ')';

        // Kiểm tra: Nếu sản phẩm này đã có trong giỏ thì cộng dồn số lượng
        if(isset($cart[$variantId])) {
            $cart[$variantId]['quantity'] += $quantity;
        } else {
            // Nếu chưa có thì thêm mới
            $cart[$variantId] = [
                'name' => $fullName,
                'price' => $variant->price,
                'image' => $variant->image ? $variant->image : $variant->product->main_image,
                'quantity' => $quantity,
                'max_stock' => 99, // Sau này sẽ check tồn kho ở đây
                'branch_id' => $branchId
            ];
        }

        // Lưu ngược lại vào session
        session()->put('cart', $cart);

        // Kiểm tra xem người dùng bấm nút nào?
        $action = $request->input('action');

        if ($action == 'buy_now') {
            // Nếu bấm "Mua ngay" -> Chuyển hướng sang trang Giỏ hàng
            return redirect()->route('cart.index');
        } else {
            // Nếu bấm "Thêm vào giỏ" -> Ở lại trang cũ và thông báo
            return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng thành công!');
        }

        return redirect()->route('cart.index')->with('success', 'Đã thêm vào giỏ hàng!');
    }

    // 3. Xóa sản phẩm khỏi giỏ
    public function remove($id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Đã xóa sản phẩm!');
    }
    
    // 4. Cập nhật số lượng (Dành cho bước sau)
    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('success', 'Đã cập nhật giỏ hàng');
        }
    }
}