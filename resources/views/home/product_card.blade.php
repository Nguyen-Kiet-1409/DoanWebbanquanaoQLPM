<div class="col">
    <div class="card product-card h-100 shadow-sm">
        
        <div class="product-img-container">
            <a href="{{ route('product.detail', $product->id) }}">
                @if($product->main_image)
                    <img src="{{ asset($product->main_image) }}" alt="{{ $product->name }}">
                @else
                    <div class="d-flex align-items-center justify-content-center h-100 text-muted"><i class="bi bi-image fs-1"></i></div>
                @endif
            </a>
            <span class="badge {{ $badge_color }} position-absolute top-0 start-0 m-2 fs-6">{{ $badge_text }}</span>
        </div>

        <div class="card-body text-center d-flex flex-column">
            <h6 class="card-title mb-2">
                <a href="{{ route('product.detail', $product->id) }}" class="text-decoration-none text-dark fw-bold text-uppercase">
                    {{ Str::limit($product->name, 40) }}
                </a>
            </h6>
            
            <div class="mb-2">
                <span class="badge bg-light text-secondary border">{{ $product->category->name ?? 'Khác' }}</span>
            </div>

            <div class="mb-3 mt-auto">
                @if($product->variants->count() > 0)
                    @php
                        // Logic tìm giá: Ưu tiên giá sale_price, nếu không có thì lấy price
                        $minSalePrice = $product->variants->whereNotNull('sale_price')->min('sale_price');
                        $minPrice = $product->variants->min('price');
                        
                        $displayPrice = $minSalePrice ?? $minPrice; // Hiển thị giá sale nếu có
                        $originalPrice = ($minSalePrice && $minPrice > $minSalePrice) ? $minPrice : null;
                    @endphp
                    
                    <span class="text-primary fw-bold fs-5">{{ number_format($displayPrice) }} đ</span>
                    @if($originalPrice)
                        <span class="text-muted text-decoration-line-through small">{{ number_format($originalPrice) }} đ</span>
                    @endif
                @else
                    <span class="text-muted fst-italic">Đang cập nhật giá</span>
                @endif
            </div>

            <a href="{{ route('product.detail', $product->id) }}" class="btn btn-outline-primary rounded-pill w-100">
                Xem chi tiết
            </a>
        </div>
    </div>
</div>