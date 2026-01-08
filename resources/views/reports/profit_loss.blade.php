{{-- views/reports/profit_loss.blade.php --}}
@extends('layout.app-dashboard')

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

    <!-- Action Buttons -->
    <div class="row no-print">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3 text-center">
                    <button onclick="window.print()" class="btn btn-primary me-2">
                        <i class="fas fa-print me-2"></i>طباعة التقرير
                    </button>
                    <a href="{{ route('reports.export', ['format' => 'pdf']) }}" class="btn btn-danger me-2">
                        <i class="fas fa-file-pdf me-2"></i>تصدير PDF
                    </a>
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
@endpush