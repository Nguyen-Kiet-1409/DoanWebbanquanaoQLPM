<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thêm Thuộc tính</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">Thêm Thuộc tính (Màu, Size...)</div>
                    <div class="card-body">
                        <form action="{{ route('attributes.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Tên thuộc tính</label>
                                <input type="text" name="name" class="form-control" required placeholder="Ví dụ: Màu sắc, Kích thước...">
                            </div>
                            <a href="{{ route('attributes.index') }}" class="btn btn-secondary">Quay lại</a>
                            <button type="submit" class="btn btn-success">Lưu lại</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>