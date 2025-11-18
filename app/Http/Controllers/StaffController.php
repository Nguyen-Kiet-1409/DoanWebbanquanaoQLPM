<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    // Danh sách nhân viên
    public function index()
    {
        // Chỉ lấy những user có role = 1 (Nhân viên)
        $staffs = User::where('role', 1)->with('branch')->get();
        return view('staff.index', compact('staffs'));
    }

    // Form thêm mới
    public function create()
    {
        $branches = Branch::all();
        return view('staff.create', compact('branches'));
    }

    // Lưu nhân viên
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'branch_id' => 'required' // Bắt buộc phải chọn chi nhánh
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 1, // Mặc định Role 1 là Nhân viên
            'branch_id' => $request->branch_id,
        ]);

        return redirect()->route('staff.index')->with('success', 'Tạo nhân viên thành công!');
    }
    
    // Hàm xóa (nếu cần)...
    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('staff.index')->with('success', 'Đã xóa nhân viên!');
    }
}