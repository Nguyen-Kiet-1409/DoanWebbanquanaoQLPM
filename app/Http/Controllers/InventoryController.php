<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Branch;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // 1. Hiển thị bảng nhập kho
    public function edit($productId)
    {
        $user = \Illuminate\Support\Facades\Auth::user(); // Lấy user đang đăng nhập

        $product = Product::with('variants.attributeValues')->findOrFail($productId);

        // --- LOGIC PHÂN QUYỀN CHI NHÁNH ---
        if ($user->role == 0) {
            // Nếu là Admin (Role 0) -> Lấy TẤT CẢ chi nhánh
            $branches = Branch::all();
        } else {
            // Nếu là Nhân viên -> Chỉ lấy ĐÚNG chi nhánh của họ
            $branches = Branch::where('id', $user->branch_id)->get();
        }

        // Lấy dữ liệu tồn kho hiện tại
        $inventoryData = [];
        $inventories = Inventory::whereIn('product_variant_id', $product->variants->pluck('id'))->get();
        
        foreach($inventories as $inv) {
            $inventoryData[$inv->product_variant_id][$inv->branch_id] = $inv->quantity;
        }

        return view('inventory.edit', compact('product', 'branches', 'inventoryData'));
    }

    // 2. Lưu tồn kho
    public function update(Request $request, $productId)
    {
        // Dữ liệu gửi lên sẽ có dạng mảng 2 chiều: stocks[variant_id][branch_id] = số lượng
        $stocks = $request->input('stocks', []);

        foreach ($stocks as $variantId => $branchStocks) {
            foreach ($branchStocks as $branchId => $quantity) {
                // Nếu ô đó có nhập số (kể cả số 0)
                if (!is_null($quantity)) {
                    Inventory::updateOrCreate(
                        [
                            'product_variant_id' => $variantId,
                            'branch_id' => $branchId
                        ],
                        [
                            'quantity' => (int) $quantity
                        ]
                    );
                }
            }
        }

        return redirect()->route('products.index')->with('success', 'Cập nhật kho thành công!');
    }
}