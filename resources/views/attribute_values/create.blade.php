<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Thêm Giá trị</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card w-50 mx-auto">
            <div class="card-header bg-success text-white">
                Thêm Giá trị (Ví dụ: Đỏ, Xanh, S, M...)
            </div>
            <div class="card-body">
                <form action="{{ route('attribute-values.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Thuộc tính Cha</label>
                        <select name="attribute_id" class="form-select">
                            @foreach($attributes as $att)
                                <option value="{{ $att->id }}">{{ $att->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Giá trị</label>
                        <input type="text" name="value" class="form-control" placeholder="Ví dụ: Đỏ" required>
                        <small class="text-muted">Nhập từng cái một rồi bấm Lưu.</small>
                    </div>

                    <button type="submit" class="btn btn-success">Lưu lại</button>
                    <a href="{{ route('attributes.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
                </form>

                @if(session('success'))
                    <div class="alert alert-success mt-3">{{ session('success') }}</div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>