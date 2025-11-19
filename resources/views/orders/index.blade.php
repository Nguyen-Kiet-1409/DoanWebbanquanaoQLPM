<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Quản lý Đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary">Danh sách Đơn hàng</h2>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Về Dashboard</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Mã Đơn</th>
                            <th>Khách hàng</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Thanh toán</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td class="fw-bold">#{{ $order->id }}</td>
                            <td>
                                <div>{{ $order->customer_name }}</div>
                                <small class="text-muted">{{ $order->customer_phone }}</small>
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="fw-bold text-danger">{{ number_format($order->total_amount) }} đ</td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $order->payment_method }}</span>
                            </td>
                            <td>
                                @if($order->status == 0)
                                    <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                @elseif($order->status == 1)
                                    <span class="badge bg-info">Đã xác nhận</span>
                                @elseif($order->status == 2)
                                    <span class="badge bg-primary">Đang giao</span>
                                @elseif($order->status == 3)
                                    <span class="badge bg-success">Hoàn thành</span>
                                @elseif($order->status == 4)
                                    <span class="badge bg-danger">Đã hủy</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> Xem
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>