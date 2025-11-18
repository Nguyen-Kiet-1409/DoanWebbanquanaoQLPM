<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Sửa Biến thể</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card w-50 mx-auto shadow">
            <div class="card-header bg-warning text-dark fw-bold">
                Sửa Biến thể (ID: {{ $variant->id }})
            </div>
            <div class="card-body">
                <form action="{{ route('product.variants.update', $variant->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h6 class="card-title fw-bold text-primary">Đặc điểm</h6>
                            
                            @php
                                $currentIds = $variant->attributeValues->pluck('id')->toArray();
                            @endphp

                            @foreach($attributes as $attribute)
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-uppercase">{{ $attribute->name }}</label>
                                <select name="attribute_values[{{ $attribute->id }}]" class="form-select">
                                    @foreach($attribute->values as $value)
                                        <option value="{{ $value->id }}" 
                                            {{ in_array($value->id, $currentIds) ? 'selected' : '' }}>
                                            {{ $value->value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Giá bán (VNĐ)</label>
                        <input type="number" name="price" class="form-control" value="{{ $variant->price }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Giá gốc</label>
                        <input type="number" name="original_price" class="form-control" value="{{ $variant->original_price }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Thay đổi ảnh (Nếu cần)</label>
                        <input type="file" name="variant_image" class="form-control">
                        @if($variant->image)
                            <div class="mt-2">
                                <img src="{{ asset($variant->image) }}" width="80" class="img-thumbnail">
                                <small class="d-block text-muted">Ảnh hiện tại</small>
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    <a href="{{ route('product.variants.index', $variant->product_id) }}" class="btn btn-secondary">Hủy</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>