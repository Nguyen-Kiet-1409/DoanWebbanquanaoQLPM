<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>{{ $product->name }} - WebQuaAo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

    @include('layouts.header')

    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <a href="{{ route('cart.index') }}" class="fw-bold text-success text-decoration-underline ms-2">Xem giỏ hàng ngay</a>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-5">
                <div class="card shadow-sm border-0">
                    <img id="main-product-img" src="{{ asset($product->main_image) }}" class="card-img-top" alt="{{ $product->name }}">
                </div>
                </div>

            <div class="col-md-7">
                <div class="card shadow-sm border-0 p-4">
                    <h2 class="fw-bold">{{ $product->name }}</h2>
                    <p class="text-muted">Danh mục: <span class="text-primary">{{ $product->category->name ?? 'Chưa phân loại' }}</span></p>
                    
                    <h3 class="text-danger fw-bold mb-4">
                        @if($product->variants->count() > 0)
                            {{ number_format($product->variants->min('price')) }} đ 
                            @if($product->variants->min('price') != $product->variants->max('price'))
                                - {{ number_format($product->variants->max('price')) }} đ
                            @endif
                        @else
                            Liên hệ
                        @endif
                    </h3>

                    <p>{{ $product->description }}</p>

                    <hr>

                    <form action="{{ route('cart.add') }}" method="POST"> @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Chọn Loại (Màu sắc / Kích thước):</label>
                            
                            <select name="product_variant_id" id="variant-select" class="form-select form-select-lg" required>
                                <option value="" data-image="{{ asset($product->main_image) }}" data-price="">-- Vui lòng chọn --</option>
                                
                                @foreach($product->variants as $variant)
                                    @php
                                        $variantImage = $variant->image ? asset($variant->image) : asset($product->main_image);
                                    @endphp

                                    <option value="{{ $variant->id }}" 
                                        data-image="{{ $variant->image ? asset($variant->image) : asset($product->main_image) }}" 
                                        data-price="{{ number_format($variant->price) }} đ"
                                        
                                        data-inventories='{{ json_encode($variant->inventories->map(function($inv) {
                                            return [
                                                'branch_id' => $inv->branch_id,
                                                'branch_name' => $inv->branch->name,
                                                'quantity' => $inv->quantity
                                            ];
                                        })) }}'
                                        >
                                    
                                    @foreach($variant->attributeValues as $val)
                                        {{ $val->value }} 
                                    @endforeach
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Chọn Chi nhánh (Kho hàng):</label>
                            <select name="branch_id" id="branch-select" class="form-select form-select-lg" disabled>
                                <option value="">-- Vui lòng chọn loại áo trước --</option>
                            </select>
                            <div class="mt-2 text-muted fst-italic" id="stock-display"></div>
                        </div>

                        <h3 class="text-danger fw-bold mb-4" id="product-price">
                        </h3>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Số lượng:</label>
                                <input type="number" name="quantity" class="form-control form-control-lg" value="1" min="1">
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            @auth
                                <button type="submit" name="action" value="add" class="btn btn-outline-primary btn-lg flex-grow-1">
                                    <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                                </button>
                                
                                <button type="submit" name="action" value="buy_now" class="btn btn-primary btn-lg flex-grow-1">
                                    Mua ngay
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-warning btn-lg">
                                    <i class="bi bi-box-arrow-in-right"></i> Đăng nhập để mua hàng
                                </a>
                            @endauth
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Đánh giá sản phẩm ({{ $product->reviews->count() }})</h4>
                    </div>
                    <div class="card-body">
                        @forelse($product->reviews as $review)
                            <div class="mb-3 border-bottom pb-3">
                                <div class="d-flex justify-content-between">
                                    <h6 class="fw-bold">{{ $review->user->name ?? 'Khách ẩn danh' }}</h6>
                                    <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                                </div>
                                <div class="text-warning mb-1">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                    @endfor
                                </div>
                                <p class="mb-0">{{ $review->comment }}</p>
                            </div>
                        @empty
                            <p class="text-muted text-center py-3">Chưa có đánh giá nào. Hãy là người đầu tiên!</p>
                        @endforelse

                        @auth
                            <hr>
                            <h5 class="mb-3 fw-bold">Viết đánh giá của bạn</h5>
                            
                            <form action="{{ route('reviews.store') }}" method="POST"> 
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Chọn sao:</label>
                                    <select name="rating" class="form-select w-auto shadow-sm">
                                        <option value="5">⭐⭐⭐⭐⭐ - Tuyệt vời</option>
                                        <option value="4">⭐⭐⭐⭐ - Tốt</option>
                                        <option value="3">⭐⭐⭐ - Bình thường</option>
                                        <option value="2">⭐⭐ - Tệ</option>
                                        <option value="1">⭐ - Rất tệ</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nhận xét:</label>
                                    <textarea name="comment" class="form-control shadow-sm" rows="3" placeholder="Chia sẻ cảm nhận của bạn về sản phẩm này..." required></textarea>
                                </div>

                                <button type="submit" class="btn btn-success px-4">
                                    <i class="bi bi-send"></i> Gửi đánh giá
                                </button>
                            </form>
                        @else
                            <div class="alert alert-info text-center mt-3 shadow-sm">
                                Vui lòng <a href="{{ route('login') }}" class="fw-bold text-decoration-underline">Đăng nhập</a> để viết đánh giá.
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

    </div>

    <footer class="bg-dark text-white pt-4 pb-2 text-center mt-auto">
        <p>&copy; 2025 NKL SHOP. All rights reserved.</p>
    </footer>

    <script>
    const variantSelect = document.getElementById('variant-select');
    const branchSelect = document.getElementById('branch-select');
    const stockDisplay = document.getElementById('stock-display');
    const priceDisplay = document.getElementById('product-price');
    const mainImage = document.getElementById('main-product-img');
    const qtyInput = document.querySelector('input[name="quantity"]');
    const btnAddToCart = document.querySelector('button[value="add"]');
    const btnBuyNow = document.querySelector('button[value="buy_now"]');

    // Hàm khóa/mở nút mua
    function toggleButtons(disable) {
        btnAddToCart.disabled = disable;
        btnBuyNow.disabled = disable;
        if(disable) {
            btnAddToCart.innerText = "Hết hàng / Chưa chọn kho";
            btnBuyNow.classList.add('disabled');
        } else {
            btnAddToCart.innerHTML = '<i class="bi bi-cart-plus"></i> Thêm vào giỏ';
            btnBuyNow.innerText = "Mua ngay";
            btnBuyNow.classList.remove('disabled');
        }
    }

    // Mặc định khóa nút mua khi mới vào
    toggleButtons(true);

    // 1. KHI CHỌN LOẠI ÁO (BIẾN THỂ)
    variantSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const inventoryData = JSON.parse(selectedOption.getAttribute('data-inventories') || '[]');
        const newImage = selectedOption.getAttribute('data-image');
        const newPrice = selectedOption.getAttribute('data-price');

        // Đổi ảnh và giá
        if(newImage) mainImage.src = newImage;
        if(newPrice) priceDisplay.innerText = newPrice;

        // Reset ô chọn chi nhánh
        branchSelect.innerHTML = '<option value="">-- Chọn chi nhánh --</option>';
        branchSelect.disabled = false;
        stockDisplay.innerText = '';
        toggleButtons(true); // Khóa nút mua lại, chờ chọn chi nhánh

        // Đổ dữ liệu vào ô Chi nhánh
        if (inventoryData.length > 0) {
            inventoryData.forEach(inv => {
                const option = document.createElement('option');
                option.value = inv.branch_id;
                option.setAttribute('data-qty', inv.quantity);
                
                // Logic hiển thị tên chi nhánh
                if (inv.quantity > 0) {
                    option.text = `${inv.branch_name} (Còn ${inv.quantity})`;
                } else {
                    option.text = `${inv.branch_name} (Hết hàng)`;
                    option.disabled = true; // Khóa dòng này không cho chọn
                    option.style.color = 'red';
                }
                branchSelect.appendChild(option);
            });
        } else {
            branchSelect.innerHTML = '<option value="">Tạm hết hàng trên toàn hệ thống</option>';
            branchSelect.disabled = true;
        }
    });

    // 2. KHI CHỌN CHI NHÁNH
    branchSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            const stock = parseInt(selectedOption.getAttribute('data-qty'));
            
            // Cập nhật dòng thông báo tồn kho
            stockDisplay.innerText = `Sẵn sàng giao hàng: ${stock} sản phẩm tại kho này.`;
            stockDisplay.className = "mt-2 text-success fw-bold";
            
            // Cập nhật max số lượng cho ô nhập
            qtyInput.max = stock;
            qtyInput.value = 1; // Reset về 1

            // Mở khóa nút mua
            toggleButtons(false);
        } else {
            stockDisplay.innerText = "";
            toggleButtons(true);
        }
    });

    // 3. KIỂM TRA KHI NHẬP SỐ LƯỢNG QUÁ LỐ
    qtyInput.addEventListener('change', function() {
        const max = parseInt(this.max);
        if(parseInt(this.value) > max) {
            alert(`Kho chỉ còn ${max} sản phẩm thôi bạn ơi!`);
            this.value = max;
        }
    });
</script>

</body>
</html>