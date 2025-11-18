<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Quản lý Sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #eee;
        }
        .action-btn {
            padding: 4px 8px;
            font-size: 14px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary fw-bold">Danh sách Sản phẩm</h2>
            <a href="{{ route('products.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-lg"></i> Thêm sản phẩm
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Quản lý</th> </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td class="ps-3 fw-bold text-secondary">#{{ $product->id }}</td>
                            <td>
                                @if($product->main_image)
                                    <img src="{{ asset($product->main_image) }}" class="product-img shadow-sm">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center product-img text-muted small">
                                        No IMG
                                    </div>
                                @endif
                            </td>
                            <td style="max-width: 250px;">
                                <h6 class="mb-0 fw-bold text-dark">{{ $product->name }}</h6>
                                <small class="text-muted fst-italic" style="font-size: 12px;">{{ $product->slug }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark bg-opacity-25 border border-info">
                                    {{ $product->category->name ?? 'Chưa phân loại' }}
                                </span>
                            </td>
                            <td>
                                @if($product->status == 1)
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Đang bán</span>
                                @else
                                    <span class="badge bg-secondary"><i class="bi bi-x-circle"></i> Ngưng</span>
                                @endif
                            </td>
                            
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('product.variants.index', $product->id) }}" class="btn btn-primary action-btn" title="Cấu hình Biến thể">
                                            <i class="bi bi-layers"></i> Biến thể
                                        </a>
                                        <a href="{{ route('inventory.edit', $product->id) }}" class="btn btn-secondary action-btn" title="Quản lý Kho">
                                            <i class="bi bi-box-seam"></i> Kho
                                        </a>
                                    </div>

                                    <div class="btn-group" role="group">
                                        <a href="#" class="btn btn-warning action-btn" title="Sửa thông tin">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger action-btn" style="border-top-left-radius: 0; border-bottom-left-radius: 0;" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="card-footer bg-white">
                <small class="text-muted">Hiển thị {{ count($products) }} sản phẩm</small>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>