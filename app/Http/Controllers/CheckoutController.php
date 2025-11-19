<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Inventory;
use Illuminate\Support\Facades\Cache;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart');
        if(!$cart || count($cart) == 0) return redirect()->route('home');
        return view('checkout.index');
    }

    public function store(Request $request)
    {
        // 1. Validate và Tính tiền
        $request->validate([
            'customer_name' => 'required',
            'customer_phone' => 'required',
            'customer_address' => 'required',
            'payment_method' => 'required'
        ]);

        $cart = session()->get('cart');
        $totalAmount = 0;

        // Lấy thông tin sản phẩm đầu tiên trong giỏ để xem nó thuộc chi nhánh nào
        $firstItem = reset($cart); 
        $branchId = $firstItem['branch_id'] ?? null; // Lấy ID chi nhánh ra

        foreach($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        // 2. Tạo đơn hàng (Trạng thái ban đầu là Chưa thanh toán)
        $order = Order::create([
            'user_id' => Auth::id(),
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'total_amount' => $totalAmount,
            'payment_method' => $request->payment_method,
            'status' => 0, // 0: Chờ xử lý / Chưa thanh toán
            'branch_id' => $branchId
        ]);

        // 3. Lưu chi tiết đơn hàng
        foreach($cart as $variantId => $details) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_variant_id' => $variantId,
                'quantity' => $details['quantity'],
                'price' => $details['price'],
            ]);

            // --- ĐOẠN MỚI: TRỪ SỐ LƯỢNG TỒN KHO ---
            $inventory = Inventory::where('product_variant_id', $variantId)
                                  ->where('branch_id', $branchId)
                                  ->first();
            
            if($inventory) {
                $inventory->decrement('quantity', $details['quantity']);
            }
        }

        // 4. Xử lý thanh toán
        if ($request->payment_method == 'VNPAY') {
            // Nếu chọn VNPAY -> Chuyển hướng sang trang thanh toán
            return $this->createVnpayUrl($order);
        }

        // --- BẮN PHÁO HIỆU: CÓ ĐƠN MỚI ---
        Cache::increment('new_orders_count');

        //Đặt xong
        session()->forget('cart');
        return redirect()->route('home')->with('success', 'Đặt hàng thành công! Mã đơn: #' . $order->id);
    }

    // --- HÀM TẠO URL VNPAY ---
    private function createVnpayUrl($order)
    {
        // CẤU HÌNH VNPAY
        $vnp_TmnCode = "DPFY6P56"; // Mã website demo
        $vnp_HashSecret = "597IQQD8SH5YX4ZQ97668NF29NC6ORCU"; // Chuỗi bí mật demo
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('vnpay.return'); // Route trả về sau khi thanh toán

        $vnp_TxnRef = $order->id; // Mã đơn hàng
        $vnp_OrderInfo = "Thanh toan don hang #" . $order->id;
        $vnp_OrderType = "billpayment";
        $vnp_Amount = $order->total_amount * 100; // VNPAY tính đơn vị là đồng (x100)
        $vnp_Locale = "vn";
        $vnp_IpAddr = request()->ip();

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return redirect($vnp_Url);
    }

    // --- HÀM XỬ LÝ KHI VNPAY TRẢ VỀ ---
    public function vnpayReturn(Request $request)
    {
        // Lấy thông tin đơn hàng
        $orderId = $request->vnp_TxnRef;
        $order = Order::find($orderId);

        // Kiểm tra kết quả trả về (00 là thành công)
        if ($request->vnp_ResponseCode == "00") {
            // --- TRƯỜNG HỢP THÀNH CÔNG ---
            if($order) {
                $order->status = 1; // 1 = Đã xác nhận / Đã thanh toán
                $order->save();
            }
            // --- BẮN PHÁO HIỆU: CÓ ĐƠN MỚI ---
            Cache::increment('new_orders_count');

            session()->forget('cart');
            return redirect()->route('home')->with('success', 'Thanh toán VNPAY thành công! Đơn hàng #' . $orderId);
        
        } else {
            // --- TRƯỜNG HỢP THẤT BẠI / HỦY ---
            // Nếu thanh toán lỗi, ta đổi trạng thái đơn hàng thành "Đã hủy" (4) ngay lập tức
            if($order) {
                $order->status = 4; // 4 = Đã hủy
                $order->save();
            }
            
            return redirect()->route('checkout.index')->with('error', 'Lỗi: Bạn đã hủy thanh toán hoặc giao dịch thất bại.');
        }
    }
}