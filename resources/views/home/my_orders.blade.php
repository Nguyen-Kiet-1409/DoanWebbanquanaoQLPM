<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Đơn hàng của tôi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

    @include('layouts.header')

    <div class="container mt-5 mb-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-bold">Lịch sử mua hàng</h3>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
    //shshs
    <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Mã đơn</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Thanh toán</th>
                            <th>Trạng thái</th>
                            <th>Chi tiết</th> </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="fw-bold text-primary">#{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="fw-bold">{{ number_format($order->total_amount) }} đ</td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $order->payment_method }}</span>
                            </td>
                            <td>
                                @if($order->status == 0)
                                    <span class="badge bg-warning text-dark">⏳ Chờ xử lý</span>
                                @elseif($order->status == 1)
                                    <span class="badge bg-info">✅ Đã xác nhận</span>
                                @elseif($order->status == 2)
                                    <span class="badge bg-primary">🚚 Đang giao</span>
                                @elseif($order->status == 3)
                                    <span class="badge bg-success">🎉 Hoàn thành</span>
                                @else
                                    <span class="badge bg-danger">❌ Đã hủy</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('my.order.detail', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Xem
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                Bạn chưa mua đơn hàng nào. <a href="{{ route('home') }}">Mua sắm ngay!</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>