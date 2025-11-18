<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        // Lấy danh sách kèm theo tên danh mục
        $products = Product::with('category')->orderBy('id', 'desc')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        // Lấy danh mục để hiển thị ra dropdown cho chọn
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // 1. Validate dữ liệu
        $request->validate([
            'name' => 'required|unique:products,name',
            'category_id' => 'required',
            'main_image' => 'required|image|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // 2. Xử lý upload ảnh (Code mới sửa)
        if ($request->hasFile('main_image')) {
            $file = $request->file('main_image');
            
            // Đặt tên file
            $filename = time() . '_' . $file->getClientOriginalName();

            // Di chuyển file vào thư mục public/products trực tiếp
            // (Cách này bỏ qua storage link ảo, lưu thẳng vào nơi hiển thị luôn)
            $file->move(public_path('storage/products'), $filename);
            
            // Lưu đường dẫn vào Database
            $data['main_image'] = 'storage/products/' . $filename;
        }

        // 3. Lưu vào Database
        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // 1. Xóa ảnh trong thư mục (nếu có)
        if ($product->main_image) {
            // Chuyển đường dẫn từ 'storage/products/...' sang 'public/products/...' để xóa được
            $imagePath = str_replace('storage/', 'public/', $product->main_image);
            
            if (Storage::exists($imagePath)) {
                Storage::delete($imagePath);
            }
        }

        // 2. Xóa dữ liệu trong database
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Đã xóa sản phẩm và ảnh thành công!');
    }
}