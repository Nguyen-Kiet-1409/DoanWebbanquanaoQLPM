<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Thanh toán - NKL SHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    @include('layouts.header')

    <div class="container mt-5 mb-5">
        <div class="row">
            
            <div class="col-md-7">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold text-primary">Thông tin giao hàng</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('checkout.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label">Họ và tên</label>
                                <input type="text" name="customer_name" class="form-control" value="{{ Auth::user()->name }}" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="customer_email" class="form-control" value="{{ Auth::user()->email }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="text" name="customer_phone" class="form-control" value="{{ Auth::user()->phone }}" required placeholder="Nhập SĐT...">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Địa chỉ nhận hàng</label>
                                <textarea name="customer_address" class="form-control" rows="3" required placeholder="Số nhà, đường, phường/xã...">{{ Auth::user()->address }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Phương thức thanh toán</label>
                                <div class="list-group">
                                    <label class="list-group-item d-flex gap-3 align-items-center">
                                        <input class="form-check-input flex-shrink-0" type="radio" name="payment_method" value="COD" checked>
                                        <span>
                                            <strong class="d-block">Thanh toán khi nhận hàng (COD)</strong>
                                            <small class="text-muted">Bạn chỉ phải thanh toán khi nhận được hàng.</small>
                                        </span>
                                    </label>

                                    <label class="list-group-item d-flex gap-3 align-items-center">
                                        <input class="form-check-input flex-shrink-0" type="radio" name="payment_method" value="VNPAY">
                                        <span>
                                            <strong class="d-block">Thanh toán Online (VNPAY)</strong>
                                            <small class="text-muted">Thanh toán qua thẻ ATM/Tài khoản ngân hàng.</small>
                                            <img src="https://cdn.haitrieu.com/wp-content/uploads/2022/10/Icon-VNPAY-QR.png" height="30" class="mt-1">
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 mt-3">XÁC NHẬN ĐẶT HÀNG</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold">Đơn hàng của bạn</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @php $total = 0; @endphp
                            @foreach(session('cart') as $details)
                                @php $total += $details['price'] * $details['quantity'] @endphp
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset($details['image']) }}" width="50" class="rounded me-2">
                                        <div>
                                            <h6 class="my-0">{{ Str::limit($details['name'], 20) }}</h6>
                                            <small class="text-muted">x {{ $details['quantity'] }}</small>
                                        </div>
                                    </div>
                                    <span class="text-muted">{{ number_format($details['price'] * $details['quantity']) }} đ</span>
                                </li>
                            @endforeach
                            
                            <li class="list-group-item d-flex justify-content-between bg-light">
                                <span class="fw-bold">Tổng cộng</span>
                                <strong class="text-danger fs-5">{{ number_format($total) }} đ</strong>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>