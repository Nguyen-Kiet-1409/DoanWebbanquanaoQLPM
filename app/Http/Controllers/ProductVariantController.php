<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Attribute;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    // 1. Hiển thị trang cấu hình
    public function index($productId)
    {
        // Tìm sản phẩm
        $product = Product::with('variants.attributeValues')->findOrFail($productId);
        
        // Lấy tất cả thuộc tính (Màu, Size) để hiện ra Dropdown chọn
        $attributes = Attribute::with('values')->get();

        return view('variants.index', compact('product', 'attributes'));
    }

    // 2. Lưu biến thể (Logic quan trọng)
    public function store(Request $request, $productId)
    {
        $request->validate([
            'price' => 'required|numeric',
            'attribute_values' => 'required|array|min:1',
            'variant_image' => 'nullable|image|max:2048', // Kiểm tra ảnh
        ]);

        $variant = new ProductVariant();
        $variant->product_id = $productId;
        $variant->price = $request->price;
        $variant->original_price = $request->original_price ?? 0;

        // --- ĐOẠN CODE MỚI: XỬ LÝ ẢNH ---
        if ($request->hasFile('variant_image')) {
            $file = $request->file('variant_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Lưu thẳng vào thư mục public/storage/products/variants
            $file->move(public_path('storage/products/variants'), $filename);
            
            // Lưu đường dẫn vào CSDL
            $variant->image = 'storage/products/variants/' . $filename;
        }
        // --------------------------------

        $variant->save(); 

        if ($request->has('attribute_values')) {
            $variant->attributeValues()->attach($request->attribute_values);
        }

        return redirect()->back()->with('success', 'Đã thêm biến thể thành công!');
    }

    // 3. Xóa biến thể
    public function destroy($variantId)
    {
        ProductVariant::destroy($variantId);
        return redirect()->back()->with('success', 'Đã xóa biến thể!');
    }

    // 4. Hiển thị form sửa biến thể
    public function edit($variantId)
    {
        $variant = ProductVariant::with('attributeValues')->findOrFail($variantId);
        
        // Lấy danh sách thuộc tính (Màu, Size) để hiển thị ra Dropdown cho người dùng chọn lại
        $attributes = Attribute::with('values')->get();

        return view('variants.edit', compact('variant', 'attributes'));
    }

    // 5. Cập nhật biến thể
    public function update(Request $request, $variantId)
    {
        $request->validate([
            'price' => 'required|numeric',
            'variant_image' => 'nullable|image|max:2048',
        ]);

        $variant = ProductVariant::findOrFail($variantId);
        $variant->price = $request->price;
        $variant->original_price = $request->original_price ?? 0;

        // Xử lý thay đổi ảnh
        if ($request->hasFile('variant_image')) {
            // Xóa ảnh cũ nếu có
            if ($variant->image) {
                $oldPath = str_replace('storage/', 'public/', $variant->image);
                if (file_exists(public_path($oldPath))) unlink(public_path($oldPath));
            }

            // Lưu ảnh mới
            $file = $request->file('variant_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/products/variants'), $filename);
            $variant->image = 'storage/products/variants/' . $filename;
        }

        $variant->save();

        // Cập nhật lại thuộc tính (Màu/Size)
        if ($request->has('attribute_values')) {
            // Hàm sync sẽ xóa cũ thay mới hoàn toàn
            $variant->attributeValues()->sync($request->attribute_values);
        }

        // Quay lại trang danh sách biến thể của sản phẩm đó
        return redirect()->route('product.variants.index', $variant->product_id)
                         ->with('success', 'Cập nhật biến thể thành công!');
    }
}