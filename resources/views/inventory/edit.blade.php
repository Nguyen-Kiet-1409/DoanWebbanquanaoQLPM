<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Quản lý Tồn kho</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Quản lý Kho: <span class="text-primary">{{ $product->name }}</span></h3>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <form action="{{ route('inventory.update', $product->id) }}" method="POST">
                    @csrf                  
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 80px;">Ảnh</th> <th class="text-start">Phiên bản (Màu/Size)</th>
                                @foreach($branches as $branch)
                                    <th>{{ $branch->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->variants as $variant)
                            <tr>
                                <td>
                                    @if($variant->image)
                                        <img src="{{ asset($variant->image) }}" class="rounded border shadow-sm" width="50" height="50" style="object-fit: cover;">
                                    @elseif($product->main_image)
                                        <img src="{{ asset($product->main_image) }}" class="rounded border shadow-sm opacity-50" width="50" height="50" style="object-fit: cover;">
                                    @else
                                        <span class="badge bg-light text-muted border">No IMG</span>
                                    @endif
                                </td>

                                <td class="text-start fw-bold">
                                    @foreach($variant->attributeValues as $val)
                                        <span class="badge bg-info text-dark border shadow-sm">{{ $val->value }}</span>
                                    @endforeach
                                    <div class="small text-muted mt-1">Giá: {{ number_format($variant->price) }}đ</div>
                                </td>

                                @foreach($branches as $branch)
                                    <td>
                                        <input type="number" 
                                            name="stocks[{{ $variant->id }}][{{ $branch->id }}]" 
                                            class="form-control text-center mx-auto fw-bold text-primary" 
                                            style="width: 100px;"
                                            min="0"
                                            value="{{ $inventoryData[$variant->id][$branch->id] ?? 0 }}">
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-success btn-lg">Lưu Tồn Kho</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>