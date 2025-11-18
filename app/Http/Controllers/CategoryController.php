<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // 1. Xem danh sách
    public function index()
    {
        // Lấy danh sách mới nhất xếp lên đầu
        $categories = Category::orderBy('id', 'desc')->get(); 
        
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    // 2. Hiển thị form thêm mới
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    // 3. Lưu dữ liệu vào Database
    public function store(Request $request)
    {
        // Validate: Bắt buộc phải nhập tên
        $request->validate([
            'name' => 'required|unique:categories,name' // Tên không được trùng
        ]);

        // Lấy toàn bộ dữ liệu gửi lên
        $data = $request->all();

        // Tự động tạo slug từ tên (Ví dụ: "Áo Thun" -> "ao-thun")
        $data['slug'] = Str::slug($request->name);

        // Lưu vào CSDL
        Category::create($data);

        // Quay về trang danh sách
        return redirect()->route('categories.index')
                         ->with('success', 'Thêm danh mục thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
