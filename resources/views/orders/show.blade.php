<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Chi tiết đơn hàng #{{ $order->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Chi tiết đơn hàng <span class="text-primary">#{{ $order->id }}</span></h3>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold">Thông tin khách hàng</div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Họ tên:</strong> {{ $order->customer_name }}</p>
                        <p class="mb-1"><strong>SĐT:</strong> {{ $order->customer_phone }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ $order->customer_email }}</p>
                        <p class="mb-0"><strong>Địa chỉ:</strong> {{ $order->customer_address }}</p>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-white fw-bold">Sản phẩm mua</div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Phân loại</th>
                                    <th>Đơn giá</th>
                                    <th>SL</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->variant->product->name }}</td>
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
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">TỔNG CỘNG:</td>
                                    <td class="fw-bold text-danger fs-5">{{ number_format($order->total_amount) }} đ</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white fw-bold">Xử lý đơn hàng</div>
                    <div class="card-body">
                        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label fw-bold">Chi nhánh phụ trách:</label>

                                @if(Auth::user()->role == 0)
                                    <select name="branch_id" class="form-select">
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}" 
                                                {{ $order->branch_id == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text text-muted small">
                                        <i class="bi bi-info-circle"></i> Admin có thể đổi chi nhánh nếu kho hiện tại gặp sự cố.
                                    </div>

                                @else
                                    <input type="text" class="form-control bg-light" value="{{ $order->branch->name ?? 'Chưa phân bổ' }}" readonly>
                                    <input type="hidden" name="branch_id" value="{{ $order->branch_id }}">
                                @endif
                            </div>

                            <hr>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Trạng thái đơn hàng:</label>
                                <select name="status" class="form-select form-select-lg">
                                    <option value="0" {{ $order->status == 0 ? 'selected' : '' }}>⏳ Chờ xử lý</option>
                                    <option value="1" {{ $order->status == 1 ? 'selected' : '' }}>✅ Đã xác nhận</option>
                                    <option value="2" {{ $order->status == 2 ? 'selected' : '' }}>🚚 Đang giao hàng</option>
                                    <option value="3" {{ $order->status == 3 ? 'selected' : '' }}>🎉 Hoàn thành</option>
                                    <option value="4" {{ $order->status == 4 ? 'selected' : '' }}>❌ Đã hủy</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-2">
                                <i class="bi bi-floppy"></i> Lưu Thay Đổi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>