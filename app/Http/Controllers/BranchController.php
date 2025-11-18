<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;  

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branches = Branch::all();
        return view('branches.index', compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('branches.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate dữ liệu (kiểm tra xem người dùng có nhập đủ không)
        $request->validate([
            'name' => 'required',
            'address' => 'required',
        ]);

        // Tạo chi nhánh mới
        Branch::create($request->all());

        // Quay về trang danh sách và báo thành công
        return redirect()->route('branches.index')
                         ->with('success', 'Thêm chi nhánh thành công!');
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
