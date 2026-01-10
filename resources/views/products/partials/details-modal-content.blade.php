{{-- resources/views/products/partials/details-modal-content.blade.php --}}

<div class="row">
    <!-- Product Images -->
    @if($product->images->isNotEmpty())
        <div class="col-12 mb-3">
            <h6 class="border-bottom pb-2 mb-3">صور المنتج</h6>
            <div class="d-flex flex-wrap gap-2">
                @foreach($product->images as $image)
                    <img src="{{ asset('storage/' . $image->photo_path) }}" 
                        alt="{{ $product->name }}" 
                        class="rounded border"
                        style="width: 100px; height: 100px; object-fit: cover; cursor: pointer;"
                        onclick="window.open('{{ asset('storage/' . $image->photo_path) }}', '_blank')">
                @endforeach
            </div>
        </div>
    @endif

    <!-- Basic Information -->
    <div class="col-md-6 mb-3">
        <h6 class="border-bottom pb-2 mb-3">المعلومات الأساسية</h6>
        <table class="table table-sm">
            <tbody>
                <tr>
                    <td class="fw-bold text-end" style="width: 40%;">اسم المنتج:</td>
                    <td>{{ $product->name }}</td>
                </tr>
                @if($product->description)
                    <tr>
                        <td class="fw-bold text-end">الوصف:</td>
                        <td>{{ $product->description }}</td>
                    </tr>
                @endif
                @if($product->type)
                    <tr>
                        <td class="fw-bold text-end">النوع:</td>
                        <td><span class="badge bg-info">{{ $product->type }}</span></td>
                    </tr>
                @endif
                @if($product->tailor_name)
                    <tr>
                        <td class="fw-bold text-end">الخياط:</td>
                        <td>{{ $product->tailor_name }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Pricing Information -->
    <div class="col-md-6 mb-3">
        <h6 class="border-bottom pb-2 mb-3">التكاليف والأسعار</h6>
        <table class="table table-sm">
            <tbody>
                <tr>
                    <td class="fw-bold text-end" style="width: 40%;">التكلفة:</td>
                    <td><span class="text-danger">{{ number_format($product->cost, 2) }} ج</span></td>
                </tr>
                <tr>
                    <td class="fw-bold text-end">السعر القطاعي:</td>
                    <td><span class="text-primary">{{ number_format($product->price, 2) }} ج</span></td>
                </tr>
                @if($product->wholesale_price)
                    <tr>
                        <td class="fw-bold text-end">سعر الجملة:</td>
                        <td>
                            <span class="text-success">{{ number_format($product->wholesale_price, 2) }} ج</span>
                            @if($discountPercentage > 0)
                                <span class="badge bg-success ms-2">خصم {{ number_format($discountPercentage, 1) }}%</span>
                            @endif
                        </td>
                    </tr>
                @endif
                <tr>
                    <td class="fw-bold text-end">الربح القطاعي:</td>
                    <td><span class="text-success fw-bold">{{ number_format($profitMarginRetail, 2) }} ج</span></td>
                </tr>
                @if($product->wholesale_price)
                    <tr>
                        <td class="fw-bold text-end">الربح بالجملة:</td>
                        <td><span class="text-info fw-bold">{{ number_format($profitMarginWholesale, 2) }} ج</span></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Additional Information -->
    <div class="col-12">
        <h6 class="border-bottom pb-2 mb-3">معلومات إضافية</h6>
        <table class="table table-sm">
            <tbody>
                <tr>
                    <td class="fw-bold text-end" style="width: 20%;">تاريخ الإضافة:</td>
                    <td>{{ $product->created_at->format('Y-m-d h:i A') }}</td>
                </tr>
                <tr>
                    <td class="fw-bold text-end">آخر تحديث:</td>
                    <td>{{ $product->updated_at->format('Y-m-d h:i A') }}</td>
                </tr>
                @if($product->user)
                    <tr>
                        <td class="fw-bold text-end">أضيف بواسطة:</td>
                        <td>{{ $product->user->f_name }} {{ $product->user->l_name }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="fw-bold text-end">عدد الصور:</td>
                    <td><span class="badge bg-primary">{{ $product->images->count() }}</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 text-center">
    <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary btn-sm" target="_blank">
        <i class="fas fa-external-link-alt"></i> عرض الصفحة الكاملة
    </a>
    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-success btn-sm">
        <i class="fas fa-edit"></i> تعديل المنتج
    </a>
</div>