<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-uppercase text-primary" href="{{ route('home') }}">
            <i class="bi bi-shop"></i> NKL SHOP
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link active fw-bold" href="{{ route('home') }}">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Sản phẩm mới</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Khuyến mãi</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Danh mục</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Áo Nam</a></li>
                        <li><a class="dropdown-item" href="#">Quần Nữ</a></li>
                    </ul>
                </li>
            </ul>
            
            <div class="d-flex gap-2 align-items-center">
                
                <a href="{{ route('cart.index') }}" class="btn btn-outline-dark position-relative border-0 me-2">
                    <i class="bi bi-cart3 fs-5"></i>
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ count(session('cart')) }}
                        </span>
                    @endif
                </a>

                @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle rounded-pill" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            
                            @if(Auth::user()->role != 2)
                                <li>
                                    <a class="dropdown-item text-primary fw-bold" href="{{ route('dashboard') }}">
                                        <i class="bi bi-speedometer2"></i> Trang Quản trị
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif

                            <li>
    <a class="dropdown-item d-flex justify-content-between align-items-center" href="{{ route('my.orders') }}">
        <span>
            <i class="bi bi-bag-check"></i> Đơn hàng của tôi
                    </span>
                    
                    @if(isset($notificationCount) && $notificationCount > 0)
                        <span class="badge bg-danger rounded-pill" title="Bạn có {{ $notificationCount }} cập nhật mới">
                            {{ $notificationCount }}
                        </span>
                    @endif
                </a>
            </li>

                            <li><a class="dropdown-item" href="#"><i class="bi bi-person-gear"></i> Tài khoản</a></li>
                            <li><hr class="dropdown-divider"></li>
                            
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right"></i> Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary px-4 rounded-pill">Đăng nhập</a>
                @endauth
            </div>
        </div>
    </div>
</nav>