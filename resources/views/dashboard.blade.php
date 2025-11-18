<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Dashboard Quản trị</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="#">ADMIN DASHBOARD</a>
            <div class="d-flex gap-3">
                <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm">Về trang chủ</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-danger btn-sm">Đăng xuất</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4">
        
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-success shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">DOANH THU (Hoàn thành)</h6>
                                <h2 class="my-2 fw-bold">{{ number_format($revenue) }} đ</h2>
                            </div>
                            <i class="bi bi-cash-coin fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-primary shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">TỔNG ĐƠN HÀNG</h6>
                                <h2 class="my-2 fw-bold">{{ $totalOrders }}</h2>
                            </div>
                            <i class="bi bi-bag-check fs-1 opacity-50"></i>
                        </div>
                        <a href="{{ route('orders.index') }}" class="text-white text-decoration-none small">Xem chi tiết &rarr;</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-warning shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0 text-dark">SẢN PHẨM</h6>
                                <h2 class="my-2 fw-bold text-dark">{{ $totalProducts }}</h2>
                            </div>
                            <i class="bi bi-box-seam fs-1 opacity-50 text-dark"></i>
                        </div>
                        <a href="{{ route('products.index') }}" class="text-dark text-decoration-none small">Quản lý kho &rarr;</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-info shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">KHÁCH HÀNG</h6>
                                <h2 class="my-2 fw-bold">{{ $totalCustomers }}</h2>
                            </div>
                            <i class="bi bi-people fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header fw-bold bg-white">
                        <i class="bi bi-grid-fill text-primary me-2"></i> Menu Quản Lý Nhanh
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-2 flex-wrap">
                            
                            <a href="{{ route('products.index') }}" class="btn btn-outline-primary position-relative">
                                <i class="bi bi-box-seam"></i> Sản phẩm & Kho
                            </a>
                            
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-primary position-relative">
                                <i class="bi bi-receipt"></i> Đơn hàng
                                @if(isset($newOrdersCount) && $newOrdersCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $newOrdersCount }}
                                    </span>
                                @endif
                            </a>

                            @if(Auth::user()->role == 0)
                                <div class="vr mx-2"></div> 
                                
                                <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary position-relative">
                                    <i class="bi bi-star-fill"></i> Đánh giá
                                    @if(isset($newReviewsCount) && $newReviewsCount > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{ $newReviewsCount }}
                                        </span>
                                    @endif
                                </a>

                                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-tags"></i> Danh mục
                                </a>
                                <a href="{{ route('branches.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-shop"></i> Chi nhánh
                                </a>
                                <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-person-badge"></i> Nhân viên
                                </a>
                                <a href="{{ route('attributes.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-list-ul"></i> Thuộc tính
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-5">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-clock-history text-danger me-2"></i> Đơn hàng mới nhất
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr>
                            <td class="fw-bold">#{{ $order->id }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td class="text-danger fw-bold">{{ number_format($order->total_amount) }} đ</td>
                            <td>
                                @if($order->status == 0) <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                @elseif($order->status == 1) <span class="badge bg-info">Đã xác nhận</span>
                                @elseif($order->status == 2) <span class="badge bg-primary">Đang giao</span>
                                @elseif($order->status == 3) <span class="badge bg-success">Hoàn thành</span>
                                @else <span class="badge bg-danger">Hủy</span> @endif
                            </td>
                            <td>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary">Xem</a>
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