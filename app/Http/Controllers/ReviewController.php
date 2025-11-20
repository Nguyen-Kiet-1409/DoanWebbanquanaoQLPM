<?php
namespace App\Http\Controllers;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ReviewController extends Controller
{
    public function index()
    {
        Cache::forget('new_reviews_count');
        
        // Lấy danh sách đánh giá, mới nhất lên đầu
        $reviews = Review::with('user', 'product')->orderBy('created_at', 'desc')->get();
        return view('reviews.index', compact('reviews'));
    }

    public function destroy($id)
    {
        Review::destroy($id);
        return redirect()->back()->with('success', 'Đã xóa đánh giá!');
    }

    // Hàm lưu đánh giá của khách hàng
    public function store(Request $request)
    {
        // 1. Kiểm tra dữ liệu đầu vào
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        // 2. Lưu vào database
        \App\Models\Review::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(), // Lấy ID người đang đăng nhập
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 1, // Mặc định hiện luôn (hoặc để 0 nếu muốn Admin duyệt)
        ]);

        // --- BẮN PHÁO HIỆU: CÓ REVIEW MỚI ---
        Cache::increment('new_reviews_count');

        // 3. Quay lại trang sản phẩm
        return redirect()->back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }
}