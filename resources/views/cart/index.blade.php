<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Giỏ hàng của bạn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-light bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ route('home') }}">&larr; Tiếp tục mua sắm</a>
        </div>
    </nav>

    <div class="container">
        <h2 class="mb-4 fw-bold">Giỏ hàng của bạn</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('cart') && count(session('cart')) > 0)
            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-0">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Đơn giá</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total = 0; @endphp
                                    @foreach(session('cart') as $id => $details)
                                        @php $total += $details['price'] * $details['quantity'] @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset($details['image']) }}" width="60" height="60" class="rounded border me-3" style="object-fit: cover">
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">{{ $details['name'] }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ number_format($details['price']) }} đ</td>
                                            <td>
                                                <input type="number" value="{{ $details['quantity'] }}" class="form-control text-center" style="width: 70px" min="1">
                                            </td>
                                            <td class="fw-bold text-primary">
                                                {{ number_format($details['price'] * $details['quantity']) }} đ
                                            </td>
                                            <td>
                                                <a href="{{ route('cart.remove', $id) }}" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Xóa nhé?')">
                                                    <i class="bi bi-trash-fill"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white fw-bold">Cộng giỏ hàng</div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <span>Tạm tính:</span>
                                <span class="fw-bold">{{ number_format($total) }} đ</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-4">
                                <span class="h5 fw-bold">Tổng cộng:</span>
                                <span class="h5 fw-bold text-danger">{{ number_format($total) }} đ</span>
                            </div>
                            <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 py-2 fw-bold">
                                TIẾN HÀNH THANH TOÁN
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-cart-x display-1 text-muted"></i>
                <p class="mt-3 text-muted">Giỏ hàng của bạn đang trống</p>
                <a href="{{ route('home') }}" class="btn btn-primary">Đi mua sắm ngay</a>
            </div>
        @endif
    </div>

</body>
</html>