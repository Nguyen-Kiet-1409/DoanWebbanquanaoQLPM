<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thêm Chi nhánh mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Thêm Chi nhánh mới</div>
                    <div class="card-body">
                        
                        <form action="{{ route('branches.store') }}" method="POST">
                            @csrf <div class="mb-3">
                                <label class="form-label">Tên chi nhánh</label>
                                <input type="text" name="name" class="form-control" required placeholder="Ví dụ: Chi nhánh ABC">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Địa chỉ</label>
                                <input type="text" name="address" class="form-control" required placeholder="Ví dụ: 123 Đường ABC...">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" name="phone" class="form-control">
                            </div>

                            <button type="submit" class="btn btn-success">Lưu lại</button>
                            <a href="{{ route('branches.index') }}" class="btn btn-secondary">Hủy</a>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>