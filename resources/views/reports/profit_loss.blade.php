{{-- views/reports/profit_loss.blade.php --}}
{{-- @extends('layout.app-dashboard')

@section('title')
    تقرير الأرباح والخسائر
@endsection

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .kpi-card {
        border-radius: 12px;
        transition: transform 0.2s;
    }
    .kpi-card:hover {
        transform: translateY(-5px);
    }
    .profit-positive {
        color: #28a745;
    }
    .profit-negative {
        color: #dc3545;
    }
    .chart-container {
        position: relative;
        height: 300px;
    }
    .comparison-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }
    .section-header {
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .metric-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 1.5rem;
    }
    @media print {
        .no-print {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    
    <!-- Header with Date Range Filter -->
    <div class="row mb-4 no-print">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h4 class="mb-0">📊 تقرير الأرباح والخسائر</h4>
                            <p class="text-sm text-secondary mb-0">
                                من {{ \Carbon\Carbon::parse($from)->format('Y-m-d') }} إلى {{ \Carbon\Carbon::parse($to)->format('Y-m-d') }}
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <form method="GET" action="{{ route('reports.profit-loss') }}" class="row g-2">
                                <div class="col-md-5">
                                    <input type="text" name="from" id="from-date" class="form-control" 
                                           placeholder="من تاريخ" value="{{ request('from') }}" readonly>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="to" id="to-date" class="form-control" 
                                           placeholder="إلى تاريخ" value="{{ request('to') }}" readonly>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">عرض</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Performance Indicators -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card kpi-card {{ $kpis['net_profit'] >= 0 ? 'bg-gradient-success' : 'bg-gradient-danger' }}">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="metric-icon bg-white text-dark me-3">
                            💰
                        </div>
                        <div class="text-white">
                            <p class="text-sm mb-0 opacity-7">صافي الربح/الخسارة</p>
                            <h5 class="font-weight-bold mb-0">
                                {{ number_format($kpis['net_profit'], 2) }} جنيه
                            </h5>
                            @if($monthlyComparison)
                                <span class="comparison-badge bg-white text-dark">
                                    {{ $monthlyComparison['profit_change'] > 0 ? '↑' : '↓' }} 
                                    {{ abs(number_format($monthlyComparison['profit_change'], 1)) }}%
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card kpi-card bg-gradient-info">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="metric-icon bg-white text-dark me-3">
                            📈
                        </div>
                        <div class="text-white">
                            <p class="text-sm mb-0 opacity-7">إجمالي الإيرادات</p>
                            <h5 class="font-weight-bold mb-0">
                                {{ number_format($kpis['total_revenue'], 2) }} جنيه
                            </h5>
                            @if($monthlyComparison)
                                <span class="comparison-badge bg-white text-dark">
                                    {{ $monthlyComparison['revenue_change'] > 0 ? '↑' : '↓' }} 
                                    {{ abs(number_format($monthlyComparison['revenue_change'], 1)) }}%
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card kpi-card bg-gradient-warning">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="metric-icon bg-white text-dark me-3">
                            💸
                        </div>
                        <div class="text-white">
                            <p class="text-sm mb-0 opacity-7">إجمالي المصروفات</p>
                            <h5 class="font-weight-bold mb-0">
                                {{ number_format($kpis['total_expenses'], 2) }} جنيه
                            </h5>
                            @if($monthlyComparison)
                                <span class="comparison-badge bg-white text-dark">
                                    {{ $monthlyComparison['expense_change'] > 0 ? '↑' : '↓' }} 
                                    {{ abs(number_format($monthlyComparison['expense_change'], 1)) }}%
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card kpi-card bg-gradient-primary">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="metric-icon bg-white text-dark me-3">
                            📊
                        </div>
                        <div class="text-white">
                            <p class="text-sm mb-0 opacity-7">هامش الربح</p>
                            <h5 class="font-weight-bold mb-0">
                                {{ number_format($kpis['profit_margin'], 1) }}%
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional KPIs Row -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-secondary">عدد الطلبات</p>
                            <h5 class="font-weight-bold mb-0">{{ $kpis['total_orders'] }}</h5>
                        </div>
                        <div class="metric-icon bg-gradient-primary text-white">
                            🛒
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-secondary">متوسط قيمة الطلب</p>
                            <h5 class="font-weight-bold mb-0">{{ number_format($kpis['average_order_value'], 2) }}</h5>
                        </div>
                        <div class="metric-icon bg-gradient-info text-white">
                            💳
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-secondary">المبلغ المحصل</p>
                            <h5 class="font-weight-bold mb-0">{{ number_format($kpis['total_deposited'], 2) }}</h5>
                        </div>
                        <div class="metric-icon bg-gradient-success text-white">
                            ✅
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-secondary">المبلغ المتبقي</p>
                            <h5 class="font-weight-bold mb-0">{{ number_format($kpis['outstanding_amount'], 2) }}</h5>
                        </div>
                        <div class="metric-icon bg-gradient-warning text-white">
                            ⏳
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Profitability Analysis -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6 class="section-header">🏆 تحليل ربحية المنتجات</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">المنتج</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">الكمية المباعة</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">الإيرادات</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">التكلفة</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">المصروفات</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">صافي الربح</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">هامش الربح</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        مباع (جمله)
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        مباع (قطاعي)
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        متوسط سعر البيع
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">التكلفة الفعلية/الوحدة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productProfitability as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <h6 class="mb-0 text-sm">{{ $product->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $product->orders_count }} طلب</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm font-weight-bold">{{ $product->total_quantity }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm font-weight-bold">{{ number_format($product->total_revenue, 2) }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm">{{ number_format($product->total_cost, 2) }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm">{{ number_format($product->product_expenses, 2) }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm font-weight-bold {{ $product->net_profit >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                            {{ number_format($product->net_profit, 2) }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge badge-sm {{ $product->profit_margin >= 20 ? 'bg-gradient-success' : ($product->profit_margin >= 10 ? 'bg-gradient-warning' : 'bg-gradient-danger') }}">
                                            {{ number_format($product->profit_margin, 1) }}%
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm">{{ $product->wholesale_quantity }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm">{{ $product->retail_quantity }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm">
                                            {{ number_format($product->avg_selling_price, 2) }} ج
                                            @if($product->avg_selling_price < $product->retail_price)
                                                <small class="text-success d-block">
                                                    (خصم {{ number_format((($product->retail_price - $product->avg_selling_price) / $product->retail_price) * 100, 1) }}%)
                                                </small>
                                            @endif
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm">{{ number_format($product->actual_cost_per_unit, 2) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top & Bottom Performers -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <h6>🌟 أكثر المنتجات ربحية</h6>
                </div>
                <div class="card-body">
                    @foreach($mostProfitable as $product)
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <h6 class="mb-0">{{ $product->name }}</h6>
                            <p class="text-xs text-secondary mb-0">{{ $product->total_quantity }} وحدة</p>
                        </div>
                        <div class="text-end">
                            <h6 class="mb-0 profit-positive">{{ number_format($product->net_profit, 2) }} جنيه</h6>
                            <p class="text-xs text-secondary mb-0">{{ number_format($product->profit_margin, 1) }}% هامش</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <h6>⚠️ أقل المنتجات ربحية</h6>
                </div>
                <div class="card-body">
                    @foreach($leastProfitable as $product)
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <h6 class="mb-0">{{ $product->name }}</h6>
                            <p class="text-xs text-secondary mb-0">{{ $product->total_quantity }} وحدة</p>
                        </div>
                        <div class="text-end">
                            <h6 class="mb-0 {{ $product->net_profit >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                {{ number_format($product->net_profit, 2) }} جنيه
                            </h6>
                            <p class="text-xs text-secondary mb-0">{{ number_format($product->profit_margin, 1) }}% هامش</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Advertising Effectiveness -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6 class="section-header">📱 فعالية مصادر الطلبات (الإعلانات)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">المصدر</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">عدد الطلبات</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">إجمالي الإيرادات</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">متوسط قيمة الطلب</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">مصروفات الإعلان</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">تكلفة الطلب</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">العائد على الاستثمار</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sourceEffectiveness as $source)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <h6 class="mb-0 text-sm">{{ $source->come_from }}</h6>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm font-weight-bold">{{ $source->orders_count }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm">{{ number_format($source->total_revenue, 2) }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm">{{ number_format($source->avg_order_value, 2) }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm">{{ number_format($source->ad_expenses, 2) }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm">{{ number_format($source->cost_per_order, 2) }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge badge-sm {{ $source->roi >= 100 ? 'bg-gradient-success' : ($source->roi >= 0 ? 'bg-gradient-warning' : 'bg-gradient-danger') }}">
                                            {{ number_format($source->roi, 1) }}%
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Expenses Breakdown -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <h6>💸 المصروفات حسب النوع</h6>
                </div>
                <div class="card-body">
                    @if($expensesByType && count($expensesByType) > 0)
                        <div class="chart-container">
                            <canvas id="expensesByTypeChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <p class="text-secondary">لا توجد مصروفات في هذه الفترة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <h6>📋 تفاصيل المصروفات</h6>
                </div>
                <div class="card-body">
                    @if(count($expensesByCategory) > 0)
                        @foreach($expensesByCategory as $expense)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div>
                                <h6 class="mb-0">{{ $expense->name }}</h6>
                                <p class="text-xs text-secondary mb-0">
                                    {{ $expense->type }} - {{ $expense->count }} عملية
                                </p>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ number_format($expense->total, 2) }} جنيه</h6>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <p class="text-secondary">لا توجد مصروفات في هذه الفترة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue by Status -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <h6>📊 الإيرادات حسب حالة الطلب</h6>
                </div>
                <div class="card-body">
                    @if(count($revenueByStatus) > 0)
                        <div class="chart-container">
                            <canvas id="revenueByStatusChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <p class="text-secondary">لا توجد إيرادات في هذه الفترة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <h6>📈 الاتجاه اليومي</h6>
                </div>
                <div class="card-body">
                    @if(count($dailyTrends) > 0)
                        <div class="chart-container">
                            <canvas id="dailyTrendChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <p class="text-secondary">لا توجد بيانات في هذه الفترة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Wholesale vs Retail Analysis -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6 class="section-header">📊 مقارنة الجملة والقطاعي</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5 class="text-success">🛒 طلبات الجملة</h5>
                                    <h2 class="font-weight-bold">{{ $kpis['wholesale_orders'] }}</h2>
                                    <p class="text-sm text-secondary mb-1">عدد الطلبات</p>
                                    <h4 class="font-weight-bold">{{ number_format($kpis['wholesale_revenue'], 2) }} جنيه</h4>
                                    <p class="text-sm text-secondary">إجمالي الإيرادات</p>
                                    @if($kpis['wholesale_orders'] > 0)
                                        <p class="text-sm">متوسط الطلب: {{ number_format($kpis['wholesale_revenue'] / $kpis['wholesale_orders'], 2) }} جنيه</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5 class="text-info">👤 طلبات القطاعي</h5>
                                    <h2 class="font-weight-bold">{{ $kpis['retail_orders'] }}</h2>
                                    <p class="text-sm text-secondary mb-1">عدد الطلبات</p>
                                    <h4 class="font-weight-bold">{{ number_format($kpis['retail_revenue'], 2) }} جنيه</h4>
                                    <p class="text-sm text-secondary">إجمالي الإيرادات</p>
                                    @if($kpis['retail_orders'] > 0)
                                        <p class="text-sm">متوسط الطلب: {{ number_format($kpis['retail_revenue'] / $kpis['retail_orders'], 2) }} جنيه</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Comparison Chart -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="chart-container">
                                <canvas id="wholesaleRetailChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row no-print">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3 text-center">
                    <button onclick="window.print()" class="btn btn-primary me-2">
                        <i class="fas fa-print me-2"></i>طباعة التقرير
                    </button>
                    <a href="{{ route('reports.export', ['format' => 'excel']) }}" class="btn btn-success">
                        <i class="fas fa-file-excel me-2"></i>تصدير Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Initialize date pickers with click to open
    flatpickr("#from-date", {
        locale: "ar",
        dateFormat: "Y-m-d",
        maxDate: "today",
        defaultDate: "{{ request('from') ?: \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}",
        allowInput: false
    });

    flatpickr("#to-date", {
        locale: "ar",
        dateFormat: "Y-m-d",
        maxDate: "today",
        defaultDate: "{{ request('to') ?: \Carbon\Carbon::now()->format('Y-m-d') }}",
        allowInput: false
    });

    // Expenses by Type Chart
    @if($expensesByType && count($expensesByType) > 0)
    const expensesByTypeCtx = document.getElementById('expensesByTypeChart');
    if (expensesByTypeCtx) {
        new Chart(expensesByTypeCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($expensesByType->keys()) !!},
                datasets: [{
                    data: {!! json_encode($expensesByType->values()) !!},
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40',
                        '#4BC0C0',
                        '#9966FF'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
    @endif

    // Revenue by Status Chart
    @if(count($revenueByStatus) > 0)
    const revenueByStatusCtx = document.getElementById('revenueByStatusChart');
    if (revenueByStatusCtx) {
        new Chart(revenueByStatusCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($revenueByStatus->pluck('name')) !!},
                datasets: [{
                    label: 'الإيرادات',
                    data: {!! json_encode($revenueByStatus->pluck('total')) !!},
                    backgroundColor: '#36A2EB'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    @endif

    // Daily Trend Chart
    @if(count($dailyTrends) > 0)
    const dailyTrendCtx = document.getElementById('dailyTrendChart');
    if (dailyTrendCtx) {
        new Chart(dailyTrendCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! json_encode($dailyTrends->pluck('date')) !!},
                datasets: [{
                    label: 'الإيرادات اليومية',
                    data: {!! json_encode($dailyTrends->pluck('revenue')) !!},
                    borderColor: '#4BC0C0',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    @endif

    // Additional JavaScript for interactive features
    document.addEventListener('DOMContentLoaded', function() {
        // Add smooth scrolling to internal links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add tooltip initialization if needed
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>

<script>
    // Wholesale vs Retail Chart
    @if(isset($kpis['wholesale_orders']) && isset($kpis['retail_orders']))
    const wholesaleRetailCtx = document.getElementById('wholesaleRetailChart');
    if (wholesaleRetailCtx) {
        new Chart(wholesaleRetailCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['طلبات الجملة', 'طلبات القطاعي'],
                datasets: [
                    {
                        label: 'عدد الطلبات',
                        data: [{{ $kpis['wholesale_orders'] }}, {{ $kpis['retail_orders'] }}],
                        backgroundColor: ['#28a745', '#17a2b8']
                    },
                    {
                        label: 'الإيرادات (ألف جنيه)',
                        data: [
                            {{ number_format($kpis['wholesale_revenue'] / 1000, 2) }},
                            {{ number_format($kpis['retail_revenue'] / 1000, 2) }}
                        ],
                        backgroundColor: ['#20c997', '#0dcaf0'],
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'عدد الطلبات'
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'الإيرادات (ألف جنيه)'
                        }
                    }
                }
            }
        });
    }
    @endif
</script>
@endpush --}}


{{-- views/reports/profit_loss.blade.php --}}
@extends('layout.app-dashboard')

@section('title')
    تقرير الأرباح والخسائر
@endsection

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
{{-- Add Select2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .kpi-card {
        border-radius: 12px;
        transition: transform 0.2s;
    }
    .kpi-card:hover {
        transform: translateY(-5px);
    }
    .profit-positive {
        color: #28a745;
    }
    .profit-negative {
        color: #dc3545;
    }
    .chart-container {
        position: relative;
        height: 300px;
    }
    .comparison-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }
    .section-header {
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .metric-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 1.5rem;
    }
    @media print {
        .no-print {
            display: none;
        }
    }
    
    /* Multi-select styling */
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #d2d6da;
        border-radius: 0.375rem;
        min-height: 38px;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #e293d3;
        box-shadow: 0 0 0 2px rgba(226, 147, 211, 0.25);
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #e293d3;
        border-color: #d387c4;
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white;
        margin-right: 4px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #ffcccc;
    }
    
    .select2-container {
        width: 100% !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    
    <!-- Header with Date Range Filter -->
    <div class="row mb-4 no-print">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-lg-5">
                            <h4 class="mb-0">📊 تقرير الأرباح والخسائر</h4>
                            <p class="text-sm text-secondary mb-0">
                                من {{ \Carbon\Carbon::parse($from)->format('Y-m-d') }} إلى {{ \Carbon\Carbon::parse($to)->format('Y-m-d') }}
                                @if($kpis['selected_statuses'] != 'تم الشحن, قيد التنفيذ, تم التسليم')
                                    <br><small class="text-success">🟢 الحالات المختارة: {{ $kpis['selected_statuses'] }}</small>
                                @endif
                            </p>
                        </div>
                        <div class="col-lg-7">
                            <form method="GET" action="{{ route('reports.profit-loss') }}" class="row g-2">
                                <div class="col-md-3">
                                    <input type="text" name="from" id="from-date" class="form-control" 
                                        placeholder="من تاريخ" value="{{ request('from') }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="to" id="to-date" class="form-control" 
                                        placeholder="إلى تاريخ" value="{{ request('to') }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <select name="statuses[]" id="status-filter" class="form-control" multiple data-placeholder="اختر حالات الطلبات">
                                        @foreach($allStatuses as $status)
                                            <option value="{{ $status }}" 
                                                {{ in_array($status, $selectedStatuses) ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">عرض</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Performance Indicators -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card kpi-card {{ $kpis['net_profit'] >= 0 ? 'bg-gradient-success' : 'bg-gradient-danger' }}">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="metric-icon bg-white text-dark me-3">
                            💰
                        </div>
                        <div class="text-white">
                            <p class="text-sm mb-0 opacity-7">صافي الربح/الخسارة</p>
                            <h5 class="font-weight-bold mb-0">
                                {{ number_format($kpis['net_profit'], 2) }} جنيه
                            </h5>
                            @if($monthlyComparison)
                                <span class="comparison-badge bg-white text-dark">
                                    {{ $monthlyComparison['profit_change'] > 0 ? '↑' : '↓' }} 
                                    {{ abs(number_format($monthlyComparison['profit_change'], 1)) }}%
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card kpi-card bg-gradient-info">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="metric-icon bg-white text-dark me-3">
                            📈
                        </div>
                        <div class="text-white">
                            <p class="text-sm mb-0 opacity-7">إجمالي الإيرادات</p>
                            <h5 class="font-weight-bold mb-0">
                                {{ number_format($kpis['total_revenue'], 2) }} جنيه
                            </h5>
                            @if($monthlyComparison)
                                <span class="comparison-badge bg-white text-dark">
                                    {{ $monthlyComparison['revenue_change'] > 0 ? '↑' : '↓' }} 
                                    {{ abs(number_format($monthlyComparison['revenue_change'], 1)) }}%
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card kpi-card bg-gradient-warning">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="metric-icon bg-white text-dark me-3">
                            💸
                        </div>
                        <div class="text-white">
                            <p class="text-sm mb-0 opacity-7">إجمالي المصروفات</p>
                            <h5 class="font-weight-bold mb-0">
                                {{ number_format($kpis['total_expenses'], 2) }} جنيه
                            </h5>
                            @if($monthlyComparison)
                                <span class="comparison-badge bg-white text-dark">
                                    {{ $monthlyComparison['expense_change'] > 0 ? '↑' : '↓' }} 
                                    {{ abs(number_format($monthlyComparison['expense_change'], 1)) }}%
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card kpi-card bg-gradient-primary">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="metric-icon bg-white text-dark me-3">
                            📊
                        </div>
                        <div class="text-white">
                            <p class="text-sm mb-0 opacity-7">هامش الربح</p>
                            <h5 class="font-weight-bold mb-0">
                                {{ number_format($kpis['profit_margin'], 1) }}%
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional KPIs Row -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-secondary">عدد الطلبات</p>
                            <h5 class="font-weight-bold mb-0">{{ $kpis['total_orders'] }}</h5>
                        </div>
                        <div class="metric-icon bg-gradient-primary text-white">
                            🛒
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-secondary">متوسط قيمة الطلب</p>
                            <h5 class="font-weight-bold mb-0">{{ number_format($kpis['average_order_value'], 2) }}</h5>
                        </div>
                        <div class="metric-icon bg-gradient-info text-white">
                            💳
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-secondary">المبلغ المحصل</p>
                            <h5 class="font-weight-bold mb-0">{{ number_format($kpis['total_deposited'], 2) }}</h5>
                        </div>
                        <div class="metric-icon bg-gradient-success text-white">
                            ✅
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-secondary">المبلغ المتبقي</p>
                            <h5 class="font-weight-bold mb-0">{{ number_format($kpis['outstanding_amount'], 2) }}</h5>
                        </div>
                        <div class="metric-icon bg-gradient-warning text-white">
                            ⏳
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Profitability Analysis -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6 class="section-header">🏆 تحليل ربحية المنتجات</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">المنتج</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">الكمية المباعة</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">الإيرادات</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">التكلفة</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">المصروفات</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">صافي الربح</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">هامش الربح</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        مباع (جمله)
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        مباع (قطاعي)
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        متوسط سعر البيع
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">التكلفة الفعلية/الوحدة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productProfitability as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <h6 class="mb-0 text-sm">{{ $product->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $product->orders_count }} طلب</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm font-weight-bold">{{ $product->total_quantity }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm font-weight-bold">{{ number_format($product->total_revenue, 2) }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm">{{ number_format($product->total_cost, 2) }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm">{{ number_format($product->product_expenses, 2) }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm font-weight-bold {{ $product->net_profit >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                            {{ number_format($product->net_profit, 2) }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge badge-sm {{ $product->profit_margin >= 20 ? 'bg-gradient-success' : ($product->profit_margin >= 10 ? 'bg-gradient-warning' : 'bg-gradient-danger') }}">
                                            {{ number_format($product->profit_margin, 1) }}%
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm">{{ $product->wholesale_quantity }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm">{{ $product->retail_quantity }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm">
                                            {{ number_format($product->avg_selling_price, 2) }} ج
                                            @if($product->avg_selling_price < $product->retail_price)
                                                <small class="text-success d-block">
                                                    (خصم {{ number_format((($product->retail_price - $product->avg_selling_price) / $product->retail_price) * 100, 1) }}%)
                                                </small>
                                            @endif
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm">{{ number_format($product->actual_cost_per_unit, 2) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top & Bottom Performers -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <h6>🌟 أكثر المنتجات ربحية</h6>
                </div>
                <div class="card-body">
                    @foreach($mostProfitable as $product)
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <h6 class="mb-0">{{ $product->name }}</h6>
                            <p class="text-xs text-secondary mb-0">{{ $product->total_quantity }} وحدة</p>
                        </div>
                        <div class="text-end">
                            <h6 class="mb-0 profit-positive">{{ number_format($product->net_profit, 2) }} جنيه</h6>
                            <p class="text-xs text-secondary mb-0">{{ number_format($product->profit_margin, 1) }}% هامش</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <h6>⚠️ أقل المنتجات ربحية</h6>
                </div>
                <div class="card-body">
                    @foreach($leastProfitable as $product)
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <h6 class="mb-0">{{ $product->name }}</h6>
                            <p class="text-xs text-secondary mb-0">{{ $product->total_quantity }} وحدة</p>
                        </div>
                        <div class="text-end">
                            <h6 class="mb-0 {{ $product->net_profit >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                {{ number_format($product->net_profit, 2) }} جنيه
                            </h6>
                            <p class="text-xs text-secondary mb-0">{{ number_format($product->profit_margin, 1) }}% هامش</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Advertising Effectiveness -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6 class="section-header">📱 فعالية مصادر الطلبات (الإعلانات)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">المصدر</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">عدد الطلبات</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">إجمالي الإيرادات</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">متوسط قيمة الطلب</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">مصروفات الإعلان</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">تكلفة الطلب</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">العائد على الاستثمار</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sourceEffectiveness as $source)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <h6 class="mb-0 text-sm">{{ $source->come_from }}</h6>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm font-weight-bold">{{ $source->orders_count }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm">{{ number_format($source->total_revenue, 2) }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm">{{ number_format($source->avg_order_value, 2) }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm">{{ number_format($source->ad_expenses, 2) }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm">{{ number_format($source->cost_per_order, 2) }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge badge-sm {{ $source->roi >= 100 ? 'bg-gradient-success' : ($source->roi >= 0 ? 'bg-gradient-warning' : 'bg-gradient-danger') }}">
                                            {{ number_format($source->roi, 1) }}%
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Expenses Breakdown -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <h6>💸 المصروفات حسب النوع</h6>
                </div>
                <div class="card-body">
                    @if($expensesByType && count($expensesByType) > 0)
                        <div class="chart-container">
                            <canvas id="expensesByTypeChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <p class="text-secondary">لا توجد مصروفات في هذه الفترة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <h6>📋 تفاصيل المصروفات</h6>
                </div>
                <div class="card-body">
                    @if(count($expensesByCategory) > 0)
                        @foreach($expensesByCategory as $expense)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div>
                                <h6 class="mb-0">{{ $expense->name }}</h6>
                                <p class="text-xs text-secondary mb-0">
                                    {{ $expense->type }} - {{ $expense->count }} عملية
                                </p>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ number_format($expense->total, 2) }} جنيه</h6>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <p class="text-secondary">لا توجد مصروفات في هذه الفترة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue by Status -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <h6>📊 الإيرادات حسب حالة الطلب</h6>
                </div>
                <div class="card-body">
                    @if(count($revenueByStatus) > 0)
                        <div class="chart-container">
                            <canvas id="revenueByStatusChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <p class="text-secondary">لا توجد إيرادات في هذه الفترة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <h6>📈 الاتجاه اليومي</h6>
                </div>
                <div class="card-body">
                    @if(count($dailyTrends) > 0)
                        <div class="chart-container">
                            <canvas id="dailyTrendChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <p class="text-secondary">لا توجد بيانات في هذه الفترة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Wholesale vs Retail Analysis -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6 class="section-header">📊 مقارنة الجملة والقطاعي</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5 class="text-success">🛒 طلبات الجملة</h5>
                                    <h2 class="font-weight-bold">{{ $kpis['wholesale_orders'] }}</h2>
                                    <p class="text-sm text-secondary mb-1">عدد الطلبات</p>
                                    <h4 class="font-weight-bold">{{ number_format($kpis['wholesale_revenue'], 2) }} جنيه</h4>
                                    <p class="text-sm text-secondary">إجمالي الإيرادات</p>
                                    @if($kpis['wholesale_orders'] > 0)
                                        <p class="text-sm">متوسط الطلب: {{ number_format($kpis['wholesale_revenue'] / $kpis['wholesale_orders'], 2) }} جنيه</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5 class="text-info">👤 طلبات القطاعي</h5>
                                    <h2 class="font-weight-bold">{{ $kpis['retail_orders'] }}</h2>
                                    <p class="text-sm text-secondary mb-1">عدد الطلبات</p>
                                    <h4 class="font-weight-bold">{{ number_format($kpis['retail_revenue'], 2) }} جنيه</h4>
                                    <p class="text-sm text-secondary">إجمالي الإيرادات</p>
                                    @if($kpis['retail_orders'] > 0)
                                        <p class="text-sm">متوسط الطلب: {{ number_format($kpis['retail_revenue'] / $kpis['retail_orders'], 2) }} جنيه</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Comparison Chart -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="chart-container">
                                <canvas id="wholesaleRetailChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row no-print">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3 text-center">
                    <button onclick="window.print()" class="btn btn-primary me-2">
                        <i class="fas fa-print me-2"></i>طباعة التقرير
                    </button>
                    {{-- <a href="{{ route('reports.export', ['format' => 'pdf']) }}" class="btn btn-danger me-2">
                        <i class="fas fa-file-pdf me-2"></i>تصدير PDF
                    </a> --}}
                    <a href="{{ route('reports.export', ['format' => 'excel']) }}" class="btn btn-success">
                        <i class="fas fa-file-excel me-2"></i>تصدير Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('js')
{{-- jQuery (required for Select2) --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- Select2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Initialize date pickers with click to open
    flatpickr("#from-date", {
        locale: "ar",
        dateFormat: "Y-m-d",
        maxDate: "today",
        defaultDate: "{{ request('from') ?: \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}",
        allowInput: false
    });

    flatpickr("#to-date", {
        locale: "ar",
        dateFormat: "Y-m-d",
        maxDate: "today",
        defaultDate: "{{ request('to') ?: \Carbon\Carbon::now()->format('Y-m-d') }}",
        allowInput: false
    });

    // Expenses by Type Chart
    @if($expensesByType && count($expensesByType) > 0)
    const expensesByTypeCtx = document.getElementById('expensesByTypeChart');
    if (expensesByTypeCtx) {
        new Chart(expensesByTypeCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($expensesByType->keys()) !!},
                datasets: [{
                    data: {!! json_encode($expensesByType->values()) !!},
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40',
                        '#4BC0C0',
                        '#9966FF'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
    @endif

    // Revenue by Status Chart
    @if(count($revenueByStatus) > 0)
    const revenueByStatusCtx = document.getElementById('revenueByStatusChart');
    if (revenueByStatusCtx) {
        new Chart(revenueByStatusCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($revenueByStatus->pluck('name')) !!},
                datasets: [{
                    label: 'الإيرادات',
                    data: {!! json_encode($revenueByStatus->pluck('total')) !!},
                    backgroundColor: '#36A2EB'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    @endif

    // Daily Trend Chart
    @if(count($dailyTrends) > 0)
    const dailyTrendCtx = document.getElementById('dailyTrendChart');
    if (dailyTrendCtx) {
        new Chart(dailyTrendCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! json_encode($dailyTrends->pluck('date')) !!},
                datasets: [{
                    label: 'الإيرادات اليومية',
                    data: {!! json_encode($dailyTrends->pluck('revenue')) !!},
                    borderColor: '#4BC0C0',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    @endif

    // Additional JavaScript for interactive features
    document.addEventListener('DOMContentLoaded', function() {
        // Add smooth scrolling to internal links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add tooltip initialization if needed
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>

<script>
    // Wholesale vs Retail Chart
    @if(isset($kpis['wholesale_orders']) && isset($kpis['retail_orders']))
    const wholesaleRetailCtx = document.getElementById('wholesaleRetailChart');
    if (wholesaleRetailCtx) {
        new Chart(wholesaleRetailCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['طلبات الجملة', 'طلبات القطاعي'],
                datasets: [
                    {
                        label: 'عدد الطلبات',
                        data: [{{ $kpis['wholesale_orders'] }}, {{ $kpis['retail_orders'] }}],
                        backgroundColor: ['#28a745', '#17a2b8']
                    },
                    {
                        label: 'الإيرادات (ألف جنيه)',
                        data: [
                            {{ number_format($kpis['wholesale_revenue'] / 1000, 2) }},
                            {{ number_format($kpis['retail_revenue'] / 1000, 2) }}
                        ],
                        backgroundColor: ['#20c997', '#0dcaf0'],
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'عدد الطلبات'
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'الإيرادات (ألف جنيه)'
                        }
                    }
                }
            }
        });
    }
    @endif
</script>

<script>
    $(document).ready(function() {
        $('#status-filter').select2({
            placeholder: "اختر حالات الطلبات",
            allowClear: true,
            width: '100%'
        });

        // Set default selected statuses if none selected
        @if(empty($selectedStatuses))
        $('#status-filter').val(['تم الشحن', 'قيد التنفيذ', 'تم التسليم']).trigger('change');
        @endif
    });

    // Saved filter presets
    const savedFilters = {
        'all_active': ['تم الشحن', 'قيد التنفيذ', 'تم التسليم'],
        'only_completed': ['تم الشحن', 'تم التسليم'],
        'only_pending': ['قيد التنفيذ'],
        'all_except_cancelled': @json(array_diff($allStatuses, ['الغاء', 'مرتجع']))
    };

    // Apply filter preset
    function applyFilter(filterName) {
        if (savedFilters[filterName]) {
            $('#status-filter').val(savedFilters[filterName]).trigger('change');
        }
    }

    // Initialize date pickers
    flatpickr("#from-date", {
        locale: "ar",
        dateFormat: "Y-m-d",
        maxDate: "today",
        defaultDate: "{{ request('from') ?: \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}",
        allowInput: false
    });

    flatpickr("#to-date", {
        locale: "ar",
        dateFormat: "Y-m-d",
        maxDate: "today",
        defaultDate: "{{ request('to') ?: \Carbon\Carbon::now()->format('Y-m-d') }}",
        allowInput: false
    });
</script>
@endpush