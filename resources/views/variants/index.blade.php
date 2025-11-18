<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Cấu hình Biến thể</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-0">Cấu hình Biến thể</h3>
                <small class="text-muted">Sản phẩm: <span class="fw-bold text-primary">{{ $product->name }}</span></small>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                &larr; Quay lại danh sách
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            
            <div class="col-md-4">
                <div class="card shadow-sm sticky-top" style="top: 20px; z-index: 1;">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-success fw-bold">+ Thêm Biến thể Mới</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('product.variants.store', $product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            @foreach($attributes as $attribute)
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-uppercase">{{ $attribute->name }}</label>
                                <select name="attribute_values[{{ $attribute->id }}]" class="form-select" required>
                                    <option value="">-- Chọn --</option>
                                    @foreach($attribute->values as $value)
                                        <option value="{{ $value->id }}">{{ $value->value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endforeach

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-uppercase">Ảnh riêng (Nếu có)</label>
                                <input type="file" name="variant_image" class="form-control" accept="image/*">
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-uppercase">Giá bán</label>
                                        <input type="number" name="price" class="form-control" required value="0">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-uppercase">Giá gốc</label>
                                        <input type="number" name="original_price" class="form-control" value="0">
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-success w-100 py-2">Lưu Biến thể</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">Danh sách các phiên bản hiện có</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 80px;">Ảnh</th>
                                    <th>Đặc điểm</th>
                                    <th>Giá bán</th>
                                    <th class="text-end">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($product->variants as $variant)
                                <tr>
                                    <td>
                                        @if($variant->image)
                                            <img src="{{ asset($variant->image) }}" class="rounded border" width="50" height="50" style="object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted border shadow-sm" style="width: 50px; height: 50px; font-size: 10px;">
                                                Gốc
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @foreach($variant->attributeValues->sortBy('attribute_id') as $val)
                                            <span class="badge bg-info text-dark border shadow-sm me-1">{{ $val->value }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">{{ number_format($variant->price) }} đ</span>
                                        @if($variant->original_price > $variant->price)
                                            <div class="small text-decoration-line-through text-muted" style="font-size: 12px">
                                                {{ number_format($variant->original_price) }} đ
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('product.variants.edit', $variant->id) }}" class="btn btn-sm btn-warning me-1 shadow-sm">
                                            <i class="bi bi-pencil"></i> Sửa
                                        </a>
                                        
                                        <form action="{{ route('product.variants.destroy', $variant->id) }}" method="POST" style="display:inline-block">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('Bạn chắc chắn muốn xóa phiên bản này?')">
                                                <i class="bi bi-trash"></i> Xóa
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted bg-light">
                                        Chưa có biến thể nào. Hãy thêm ở cột bên trái!
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div> 
        </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>