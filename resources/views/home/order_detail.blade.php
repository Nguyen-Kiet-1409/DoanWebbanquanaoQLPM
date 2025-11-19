<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Chi tiết đơn hàng #{{ $order->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

    @include('layouts.header')

    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Chi tiết đơn hàng <span class="text-primary">#{{ $order->id }}</span></h3>
            <a href="{{ route('my.orders') }}" class="btn btn-secondary">&larr; Quay lại danh sách</a>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white fw-bold">Thông tin nhận hàng</div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Người nhận:</strong> {{ $order->customer_name }}</p>
                        <p class="mb-1"><strong>SĐT:</strong> {{ $order->customer_phone }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ $order->customer_email }}</p>
                        <p class="mb-0"><strong>Địa chỉ:</strong> {{ $order->customer_address }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-8 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white fw-bold">Trạng thái đơn hàng</div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Ngày đặt hàng:</span>
                            <span class="fw-bold">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phương thức thanh toán:</span>
                            <span class="fw-bold">{{ $order->payment_method }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Tình trạng:</span>
                            @if($order->status == 0)
                                <span class="badge bg-warning text-dark">⏳ Chờ xử lý</span>
                            @elseif($order->status == 1)
                                <span class="badge bg-info">✅ Đã xác nhận - Đang chuẩn bị hàng</span>
                            @elseif($order->status == 2)
                                <span class="badge bg-primary">🚚 Đang giao hàng</span>
                            @elseif($order->status == 3)
                                <span class="badge bg-success">🎉 Giao hàng thành công</span>
                            @else
                                <span class="badge bg-danger">❌ Đã hủy</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold">Sản phẩm đã mua</div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Phân loại</th>
                                    <th>Đơn giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $img = $item->variant->image ?? $item->variant->product->main_image;
                                            @endphp
                                            <img src="{{ asset($img) }}" width="50" height="50" class="rounded border me-2" style="object-fit: cover">
                                            <span class="fw-bold">{{ $item->variant->product->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @foreach($item->variant->attributeValues as $val)
                                            <span class="badge bg-secondary">{{ $val->value }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ number_format($item->price) }} đ</td>
                                    <td>x{{ $item->quantity }}</td>
                                    <td class="fw-bold">{{ number_format($item->price * $item->quantity) }} đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">TỔNG CỘNG THANH TOÁN:</td>
                                    <td class="fw-bold text-danger fs-5">{{ number_format($order->total_amount) }} đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>