<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Thêm Nhân viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card w-50 mx-auto">
            <div class="card-header bg-primary text-white">Thêm Tài khoản Nhân viên</div>
            <div class="card-body">
                <form action="{{ route('staff.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Họ tên</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email đăng nhập</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Làm việc tại Chi nhánh</label>
                        <select name="branch_id" class="form-select" required>
                            <option value="">-- Chọn chi nhánh --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Tạo tài khoản</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>