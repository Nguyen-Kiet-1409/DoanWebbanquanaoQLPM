<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Quản lý Nhân viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-3">
            <h3>Danh sách Nhân viên</h3>
            <a href="{{ route('staff.create') }}" class="btn btn-primary">Thêm Nhân viên</a>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Thuộc Chi nhánh</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($staffs as $staff)
                        <tr>
                            <td>{{ $staff->name }}</td>
                            <td>{{ $staff->email }}</td>
                            <td><span class="badge bg-info text-dark">{{ $staff->branch->name ?? 'Chưa gán' }}</span></td>
                            <td>
                                <form action="{{ route('staff.destroy', $staff->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Xóa?')">Xóa</button>
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