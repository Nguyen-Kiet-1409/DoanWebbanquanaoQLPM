<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Sửa Giá trị</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card w-50 mx-auto shadow">
            <div class="card-header bg-warning text-dark fw-bold">Sửa Giá trị: {{ $value->value }}</div>
            <div class="card-body">
                <form action="{{ route('attribute-values.update', $value->id) }}" method="POST">
                    @csrf
                    @method('PUT') <div class="mb-3">
                        <label class="form-label fw-bold">Thuộc tính Cha</label>
                        <select name="attribute_id" class="form-select">
                            @foreach($attributes as $att)
                                <option value="{{ $att->id }}" {{ $value->attribute_id == $att->id ? 'selected' : '' }}>
                                    {{ $att->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Giá trị</label>
                        <input type="text" name="value" class="form-control" value="{{ $value->value }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    <a href="{{ route('attributes.index') }}" class="btn btn-secondary">Hủy</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>