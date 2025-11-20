<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Quản lý Đánh giá</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary">Quản lý Đánh giá</h2>
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
                            <th class="ps-3">Khách hàng</th>
                            <th>Sản phẩm</th>
                            <th>Đánh giá (Sao)</th>
                            <th>Bình luận</th>
                            <th>Ngày</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $review)
                        <tr>
                            <td class="ps-3">
                                <i class="bi bi-person-circle"></i> {{ $review->user->name ?? 'Khách ẩn danh' }}
                            </td>
                            <td>
                                <a href="{{ route('product.detail', $review->product->id) }}" class="text-dark text-decoration-none small">
                                    {{ $review->product->name ?? 'Sản phẩm đã xóa' }}
                                </a>
                            </td>
                            <td>
                                <span class="text-warning fw-bold">
                                    {{ $review->rating }} <i class="bi bi-star-fill"></i>
                                </span>
                            </td>
                            <td class="small fst-italic" style="max-width: 300px;">
                                "{{ $review->comment }}"
                            </td>
                            <td class="small text-muted">{{ $review->created_at->format('d/m/Y') }}</td>
                            <td>
                                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa bình luận này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </form>
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