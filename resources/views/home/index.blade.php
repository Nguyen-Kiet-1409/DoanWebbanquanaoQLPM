<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NKL SHOP - Thời trang đẳng cấp</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        .hero-banner {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?q=80&w=2070&auto=format&fit=crop');
            background-size: cover; background-position: center; height: 400px;
            color: white; display: flex; align-items: center; justify-content: center; text-align: center;
        }
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none; border-radius: 10px; overflow: hidden;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .product-img-container {
            height: 280px; overflow: hidden; background-color: #f8f9fa; position: relative;
        }
        .product-img-container img {
            width: 100%; height: 100%; object-fit: contain; transition: transform 0.5s ease;
        }
        .product-card:hover .product-img-container img { transform: scale(1.05); }
        .section-title {
            text-align: center; margin-bottom: 3rem;
        }
        .section-title h2 {
            font-weight: 700; text-transform: uppercase;
        }
        .section-title .divider {
            width: 80px; height: 4px; background-color: #0d6efd; margin: 0 auto;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    @include('layouts.header')

    <header class="hero-banner">
        <div class="container">
            <h1 class="display-4 fw-bold">Thời trang Phong cách 2025</h1>
            <p class="lead mb-4">Khám phá bộ sưu tập mới nhất với ưu đãi lên đến 50%</p>
            <a href="#new-products" class="btn btn-light btn-lg rounded-pill px-4 fw-bold">Mua ngay</a>
        </div>
    </header>

    <div class="container my-5">
        
        <section id="new-products" class="mb-5">
            <div class="section-title">
                <h2>Sản phẩm Mới</h2>
                <div class="divider"></div>
            </div>
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                @foreach($newProducts as $product)
                    @include('home.product_card', ['badge_text' => 'Mới', 'badge_color' => 'bg-danger'])
                @endforeach
            </div>
        </section>

        <hr class="my-5">

        <section id="best-sellers" class="mb-5">
            <div class="section-title">
                <h2>Bán chạy nhất</h2>
                <div class="divider"></div>
            </div>
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                @foreach($bestSellers as $product)
                    @include('home.product_card', ['badge_text' => 'Hot', 'badge_color' => 'bg-warning text-dark'])
                @endforeach
            </div>
        </section>

        <hr class="my-5">

        <section id="on-sale" class="mb-5">
            <div class="section-title">
                <h2>Đang Giảm giá</h2>
                <div class="divider"></div>
            </div>
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                @foreach($saleProducts as $product)
                    @include('home.product_card', ['badge_text' => 'Sale', 'badge_color' => 'bg-success'])
                @endforeach
            </div>
        </section>

    </div>

    @include('layouts.footer') <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>