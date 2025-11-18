<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;

class AttributeValueController extends Controller
{
    // Hiển thị form nhập liệu
    public function create()
    {
        // Lấy danh sách cha (Màu sắc, Kích thước) để hiện ra dropdown cho chọn
        $attributes = Attribute::all();
        return view('attribute_values.create', compact('attributes'));
    }

    // Lưu vào CSDL
    public function store(Request $request)
    {
        $request->validate([
            'attribute_id' => 'required',
            'value' => 'required'
        ]);

        AttributeValue::create($request->all());

        // Lưu xong thì quay lại chính trang đó để nhập tiếp cái khác cho lẹ
        return redirect()->back()->with('success', 'Đã thêm giá trị thành công! Hãy nhập tiếp.');
    }

    // 3. Hiện form sửa
    public function edit($id)
    {
        $value = AttributeValue::findOrFail($id);
        $attributes = Attribute::all(); // Lấy danh sách cha để chọn lại nếu cần
        return view('attribute_values.edit', compact('value', 'attributes'));
    }

    // 4. Lưu thay đổi
    public function update(Request $request, $id)
    {
        $request->validate([
            'attribute_id' => 'required',
            'value' => 'required'
        ]);

        $val = AttributeValue::findOrFail($id);
        $val->update($request->all());

        return redirect()->route('attributes.index')->with('success', 'Đã sửa thành công!');
    }
}