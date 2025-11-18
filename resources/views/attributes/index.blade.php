<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý Thuộc tính</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Danh sách Thuộc tính</h1>
        
        <div class="d-flex gap-2 mb-3">
            <a href="{{ route('attributes.create') }}" class="btn btn-primary">Thêm Loại (Màu, Size)</a>
            <a href="{{ route('attribute-values.create') }}" class="btn btn-success">Thêm Giá trị (Xanh, S, M)</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Tên thuộc tính</th>
                    <th>Các giá trị</th> <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attributes as $attribute)
                <tr>
                    <td>{{ $attribute->id }}</td>
                    <td><strong>{{ $attribute->name }}</strong></td>
                    
                    <td>
                        @foreach($attribute->values as $val)
                            <a href="{{ route('attribute-values.edit', $val->id) }}" class="badge bg-secondary text-decoration-none">
                                {{ $val->value }} <i class="bi bi-pencil-square"></i>
                            </a>
                        @endforeach
                    </td>

                    <td>
                        <form action="{{ route('attributes.destroy', $attribute->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Xóa cha là xóa hết con đó nha, chắc chưa?')">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>