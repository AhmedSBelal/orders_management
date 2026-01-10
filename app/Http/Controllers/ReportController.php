<?php

namespace App\Http\Controllers;

use Excel;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\ProfitLossReportExport;


class ReportController extends Controller
{

    public function profitLoss(Request $request)
    {
        // Get date range from request or default to current month
        $from = $request->from ? Carbon::parse($request->from)->startOfDay() : Carbon::now()->startOfMonth();
        $to = $request->to ? Carbon::parse($request->to)->endOfDay() : Carbon::now()->endOfDay();
        
        // Get selected statuses from request
        $selectedStatuses = $request->statuses ?? [];
        
        // If no statuses selected, use default active statuses
        if (empty($selectedStatuses)) {
            $selectedStatuses = ['تم الشحن', 'قيد التنفيذ', 'تم التسليم'];
        }
        
        // Get all available statuses for the filter dropdown
        $allStatuses = \App\Models\OrderStatus::pluck('name')->toArray();

        // ============================================
        // 1. REVENUE ANALYSIS - USING ACTUAL PIVOT PRICES
        // ============================================
        
        // Base query for orders in date range and selected statuses
        $ordersBaseQuery = Order::whereBetween('orders.created_at', [$from, $to])
            ->join('order_statuses', 'orders.status_id', '=', 'order_statuses.id')
            ->whereIn('order_statuses.name', $selectedStatuses);

        // Total Revenue - Use actual price from order_product pivot table
        $totalRevenue = DB::table('orders')
            ->join('order_product', 'orders.id', '=', 'order_product.order_id')
            ->join('order_statuses', 'orders.status_id', '=', 'order_statuses.id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->whereIn('order_statuses.name', $selectedStatuses)
            ->sum(DB::raw('order_product.quantity * order_product.price')) ?? 0;

        // Total Deposited
        $totalDeposited = Order::whereBetween('created_at', [$from, $to])
            ->whereHas('status', function ($query) use ($selectedStatuses) {
                $query->whereIn('name', $selectedStatuses);
            })
            ->sum('deposited');

        // Outstanding Amount
        $outstandingAmount = $totalRevenue - $totalDeposited;

        // Orders Count
        $totalOrders = Order::whereBetween('created_at', [$from, $to])
            ->whereHas('status', function ($query) use ($selectedStatuses) {
                $query->whereIn('name', $selectedStatuses);
            })
            ->count();

        // Average Order Value
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Revenue by Status - Using pivot prices
        $revenueByStatus = DB::table('orders')
            ->join('order_product', 'orders.id', '=', 'order_product.order_id')
            ->join('order_statuses', 'orders.status_id', '=', 'order_statuses.id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->select(
                'order_statuses.name',
                DB::raw('COUNT(DISTINCT orders.id) as orders_count'),
                DB::raw('SUM(order_product.quantity * order_product.price) as total')
            )
            ->groupBy('order_statuses.name')
            ->get();

        // Revenue by Source (come_from) - Using pivot prices
        $revenueBySource = DB::table('orders')
            ->join('order_product', 'orders.id', '=', 'order_product.order_id')
            ->join('order_statuses', 'orders.status_id', '=', 'order_statuses.id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->whereIn('order_statuses.name', $selectedStatuses)
            ->select(
                'orders.come_from',
                DB::raw('COUNT(DISTINCT orders.id) as orders_count'),
                DB::raw('SUM(order_product.quantity * order_product.price) as total_revenue')
            )
            ->groupBy('orders.come_from')
            ->orderByDesc('total_revenue')
            ->get();

        // ============================================
        // 2. EXPENSES ANALYSIS
        // ============================================
        $totalExpenses = Expense::whereBetween('expense_date', [$from, $to])->sum('amount');

        // Expenses by Category Type
        $expensesByType = Expense::whereBetween('expense_date', [$from, $to])
            ->join('expense_categories', 'expenses.expense_category_id', '=', 'expense_categories.id')
            ->select('expense_categories.type', DB::raw('SUM(expenses.amount) as total'))
            ->groupBy('expense_categories.type')
            ->get()
            ->pluck('total', 'type');

        // Detailed Expenses by Category
        $expensesByCategory = Expense::whereBetween('expense_date', [$from, $to])
            ->join('expense_categories', 'expenses.expense_category_id', '=', 'expense_categories.id')
            ->select(
                'expense_categories.name',
                'expense_categories.type',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(expenses.amount) as total')
            )
            ->groupBy('expense_categories.name', 'expense_categories.type')
            ->orderByDesc('total')
            ->get();

        // ============================================
        // 3. PRODUCT PROFITABILITY ANALYSIS
        // ============================================
        $productProfitability = DB::table('order_product')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('order_statuses', 'orders.status_id', '=', 'order_statuses.id')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->whereIn('order_statuses.name', $selectedStatuses)
            ->select(
                'products.id',
                'products.name',
                'products.price as retail_price',
                'products.wholesale_price',
                'products.cost',
                DB::raw('SUM(order_product.quantity) as total_quantity'),
                DB::raw('SUM(order_product.price * order_product.quantity) as total_revenue'),
                DB::raw('SUM(products.cost * order_product.quantity) as total_cost'),
                DB::raw('SUM((order_product.price - products.cost) * order_product.quantity) as gross_profit'),
                DB::raw('COUNT(DISTINCT orders.id) as orders_count'),
                DB::raw('SUM(CASE WHEN orders.is_wholesale = 1 THEN order_product.quantity ELSE 0 END) as wholesale_quantity'),
                DB::raw('SUM(CASE WHEN orders.is_wholesale = 0 THEN order_product.quantity ELSE 0 END) as retail_quantity')
            )
            ->groupBy(
                'products.id', 
                'products.name', 
                'products.price', 
                'products.wholesale_price', 
                'products.cost'
            )
            ->get();

        // Add product-specific expenses
        foreach ($productProfitability as $product) {
            $productExpenses = Expense::whereBetween('expense_date', [$from, $to])
                ->where('product_id', $product->id)
                ->sum('amount');

            $product->product_expenses = $productExpenses;
            $product->net_profit = $product->gross_profit - $productExpenses;
            $product->profit_margin = $product->total_revenue > 0
                ? ($product->net_profit / $product->total_revenue) * 100
                : 0;
            $product->actual_cost_per_unit = $product->total_quantity > 0
                ? ($product->total_cost + $productExpenses) / $product->total_quantity
                : 0;
                
            $product->avg_selling_price = $product->total_quantity > 0
                ? $product->total_revenue / $product->total_quantity
                : 0;
        }

        // Sort by net profit and get top/bottom performers
        $productProfitability = $productProfitability->sortByDesc('net_profit');
        $mostProfitable = $productProfitability->take(5);
        $leastProfitable = $productProfitability->reverse()->take(5)->reverse();

        // ============================================
        // 4. ADVERTISING/SOURCE EFFECTIVENESS
        // ============================================
        $sourceEffectiveness = DB::table('orders')
            ->join('order_product', 'orders.id', '=', 'order_product.order_id')
            ->join('order_statuses', 'orders.status_id', '=', 'order_statuses.id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->whereIn('order_statuses.name', $selectedStatuses)
            ->select(
                'orders.come_from',
                DB::raw('COUNT(DISTINCT orders.id) as orders_count'),
                DB::raw('SUM(order_product.quantity * order_product.price) as total_revenue'),
                DB::raw('AVG(order_product.quantity * order_product.price) as avg_order_value'),
                DB::raw('SUM(CASE WHEN orders.is_wholesale = 1 THEN 1 ELSE 0 END) as wholesale_orders'),
                DB::raw('SUM(CASE WHEN orders.is_wholesale = 0 THEN 1 ELSE 0 END) as retail_orders')
            )
            ->groupBy('orders.come_from')
            ->get();

        // Calculate advertising ROI
        foreach ($sourceEffectiveness as $source) {
            $adExpenses = Expense::whereBetween('expense_date', [$from, $to])
                ->whereHas('category', function ($q) {
                    $q->where('type', 'marketing')->orWhere('type', 'advertising');
                })
                ->where(function($q) use ($source) {
                    $q->where('notes', 'like', '%' . $source->come_from . '%');
                })
                ->sum('amount');

            $source->ad_expenses = $adExpenses;
            $source->cost_per_order = $source->orders_count > 0
                ? $adExpenses / $source->orders_count
                : 0;
            $source->roi = $adExpenses > 0
                ? (($source->total_revenue - $adExpenses) / $adExpenses) * 100
                : 0;
        }

        // ============================================
        // 5. PROFIT & LOSS CALCULATION
        // ============================================
        $grossProfit = $totalRevenue - $productProfitability->sum('total_cost');
        $netProfit = $grossProfit - $totalExpenses;
        $profitMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0;
        $breakEvenPoint = $totalExpenses;

        // ============================================
        // 6. MONTHLY COMPARISON
        // ============================================
        $monthlyComparison = null;
        $isCurrentMonth = $from->isSameMonth(Carbon::now()) && $to->isSameMonth(Carbon::now());

        if ($isCurrentMonth) {
            $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
            $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

            $lastMonthRevenue = DB::table('orders')
                ->join('order_product', 'orders.id', '=', 'order_product.order_id')
                ->join('order_statuses', 'orders.status_id', '=', 'order_statuses.id')
                ->whereBetween('orders.created_at', [$lastMonthStart, $lastMonthEnd])
                ->whereIn('order_statuses.name', $selectedStatuses)
                ->sum(DB::raw('order_product.quantity * order_product.price')) ?? 0;

            $lastMonthExpenses = Expense::whereBetween('expense_date', [$lastMonthStart, $lastMonthEnd])
                ->sum('amount');

            $lastMonthProfit = $lastMonthRevenue - $lastMonthExpenses;

            $monthlyComparison = [
                'revenue_change' => $lastMonthRevenue > 0 ? (($totalRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0,
                'expense_change' => $lastMonthExpenses > 0 ? (($totalExpenses - $lastMonthExpenses) / $lastMonthExpenses) * 100 : 0,
                'profit_change' => abs($lastMonthProfit) > 0 ? (($netProfit - $lastMonthProfit) / abs($lastMonthProfit)) * 100 : 0,
            ];
        }

        // ============================================
        // 7. WHOLESALE VS RETAIL ANALYSIS
        // ============================================
        $wholesaleVsRetail = DB::table('orders')
            ->join('order_product', 'orders.id', '=', 'order_product.order_id')
            ->join('order_statuses', 'orders.status_id', '=', 'order_statuses.id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->whereIn('order_statuses.name', $selectedStatuses)
            ->select(
                DB::raw('SUM(CASE WHEN orders.is_wholesale = 1 THEN 1 ELSE 0 END) as wholesale_orders'),
                DB::raw('SUM(CASE WHEN orders.is_wholesale = 0 THEN 1 ELSE 0 END) as retail_orders'),
                DB::raw('SUM(CASE WHEN orders.is_wholesale = 1 THEN order_product.quantity * order_product.price ELSE 0 END) as wholesale_revenue'),
                DB::raw('SUM(CASE WHEN orders.is_wholesale = 0 THEN order_product.quantity * order_product.price ELSE 0 END) as retail_revenue')
            )
            ->first();

        // ============================================
        // 8. KEY PERFORMANCE INDICATORS (KPIs)
        // ============================================
        $kpis = [
            'total_revenue' => $totalRevenue,
            'total_deposited' => $totalDeposited,
            'outstanding_amount' => $outstandingAmount,
            'total_expenses' => $totalExpenses,
            'gross_profit' => $grossProfit,
            'net_profit' => $netProfit,
            'profit_margin' => $profitMargin,
            'total_orders' => $totalOrders,
            'average_order_value' => $averageOrderValue,
            'break_even_point' => $breakEvenPoint,
            'wholesale_orders' => $wholesaleVsRetail->wholesale_orders ?? 0,
            'retail_orders' => $wholesaleVsRetail->retail_orders ?? 0,
            'wholesale_revenue' => $wholesaleVsRetail->wholesale_revenue ?? 0,
            'retail_revenue' => $wholesaleVsRetail->retail_revenue ?? 0,
            'selected_statuses' => implode(', ', $selectedStatuses),
        ];

        // ============================================
        // 9. DAILY TREND ANALYSIS
        // ============================================
        $dailyTrends = DB::table('orders')
            ->join('order_product', 'orders.id', '=', 'order_product.order_id')
            ->join('order_statuses', 'orders.status_id', '=', 'order_statuses.id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->whereIn('order_statuses.name', $selectedStatuses)
            ->select(
                DB::raw('DATE(orders.created_at) as date'),
                DB::raw('COUNT(DISTINCT orders.id) as orders_count'),
                DB::raw('SUM(order_product.quantity * order_product.price) as revenue'),
                DB::raw('SUM(CASE WHEN orders.is_wholesale = 1 THEN 1 ELSE 0 END) as wholesale_orders'),
                DB::raw('SUM(CASE WHEN orders.is_wholesale = 0 THEN 1 ELSE 0 END) as retail_orders')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('reports.profit_loss', compact(
            'from',
            'to',
            'kpis',
            'revenueByStatus',
            'revenueBySource',
            'expensesByType',
            'expensesByCategory',
            'productProfitability',
            'mostProfitable',
            'leastProfitable',
            'sourceEffectiveness',
            'monthlyComparison',
            'dailyTrends',
            'wholesaleVsRetail',
            'allStatuses',
            'selectedStatuses'
        ));
    }

    public function exportReport(Request $request, $format = 'pdf')
    {
        // Get the date range from the request
        $from = $request->input('from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $to = $request->input('to', Carbon::now()->format('Y-m-d'));

        // Parse the dates
        $from = Carbon::parse($from)->startOfDay();
        $to = Carbon::parse($to)->endOfDay();

        // Get all the data needed for the report (reuse the same logic as in profitLoss method)
        // ============================================
        // 1. REVENUE ANALYSIS
        // ============================================
        $ordersQuery = Order::whereBetween('created_at', [$from, $to]);

        // Total Revenue (using total_price_after_discount if exists, otherwise total_price)
        $totalRevenue = $ordersQuery->sum(DB::raw('COALESCE(total_price_after_discount, total_price)'));

        // Total Deposited (actual cash received)
        $totalDeposited = $ordersQuery->sum('deposited');

        // Outstanding Amount (money still owed)
        $outstandingAmount = $totalRevenue - $totalDeposited;

        // Orders Count
        $totalOrders = $ordersQuery->count();

        // Average Order Value
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $revenueByStatus = Order::whereBetween('orders.created_at', [$from, $to])
            ->join('order_statuses', 'orders.status_id', '=', 'order_statuses.id')
            ->select('order_statuses.name', DB::raw('SUM(COALESCE(orders.total_price_after_discount, orders.total_price)) as total'))
            ->groupBy('order_statuses.name')
            ->get();

        // Revenue by Source (come_from)
        $revenueBySource = Order::whereBetween('created_at', [$from, $to])
            ->select(
                'come_from',
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(COALESCE(total_price_after_discount, total_price)) as total_revenue')
            )
            ->groupBy('come_from')
            ->orderByDesc('total_revenue')
            ->get();

        // ============================================
        // 2. EXPENSES ANALYSIS
        // ============================================
        $expensesQuery = Expense::whereBetween('expense_date', [$from, $to]);

        $totalExpenses = $expensesQuery->sum('amount');

        // Expenses by Category Type
        $expensesByType = Expense::whereBetween('expense_date', [$from, $to])
            ->join('expense_categories', 'expenses.expense_category_id', '=', 'expense_categories.id')
            ->select('expense_categories.type', DB::raw('SUM(expenses.amount) as total'))
            ->groupBy('expense_categories.type')
            ->get()
            ->pluck('total', 'type');

        // Detailed Expenses by Category
        $expensesByCategory = Expense::whereBetween('expense_date', [$from, $to])
            ->join('expense_categories', 'expenses.expense_category_id', '=', 'expense_categories.id')
            ->select(
                'expense_categories.name',
                'expense_categories.type',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(expenses.amount) as total')
            )
            ->groupBy('expense_categories.name', 'expense_categories.type')
            ->orderByDesc('total')
            ->get();

        // ============================================
        // 3. PRODUCT PROFITABILITY ANALYSIS
        // ============================================
        $productProfitability = DB::table('order_product')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->select(
                'products.id',
                'products.name',
                'products.price',
                'products.cost',
                DB::raw('SUM(order_product.quantity) as total_quantity'),
                DB::raw('SUM(products.price * order_product.quantity) as total_revenue'),
                DB::raw('SUM(products.cost * order_product.quantity) as total_cost'),
                DB::raw('SUM((products.price - products.cost) * order_product.quantity) as gross_profit'),
                DB::raw('COUNT(DISTINCT orders.id) as orders_count')
            )
            ->groupBy('products.id', 'products.name', 'products.price', 'products.cost')
            ->orderByDesc('gross_profit')
            ->get();

        // Add product-specific expenses to calculate net profit per product
        foreach ($productProfitability as $product) {
            $productExpenses = Expense::whereBetween('expense_date', [$from, $to])
                ->where('product_id', $product->id)
                ->sum('amount');

            $product->product_expenses = $productExpenses;
            $product->net_profit = $product->gross_profit - $productExpenses;
            $product->profit_margin = $product->total_revenue > 0
                ? ($product->net_profit / $product->total_revenue) * 100
                : 0;
            $product->actual_cost_per_unit = $product->total_quantity > 0
                ? ($product->total_cost + $productExpenses) / $product->total_quantity
                : 0;
        }

        // Most and Least Profitable Products
        $mostProfitable = $productProfitability->sortByDesc('net_profit')->take(5);
        $leastProfitable = $productProfitability->sortBy('net_profit')->take(5);

        // ============================================
        // 4. ADVERTISING/SOURCE EFFECTIVENESS
        // ============================================
        $sourceEffectiveness = Order::whereBetween('created_at', [$from, $to])
            ->select(
                'come_from',
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(COALESCE(total_price_after_discount, total_price)) as total_revenue'),
                DB::raw('AVG(COALESCE(total_price_after_discount, total_price)) as avg_order_value')
            )
            ->groupBy('come_from')
            ->get();

        // Calculate cost per order from advertising expenses
        foreach ($sourceEffectiveness as $source) {
            $adExpenses = Expense::whereBetween('expense_date', [$from, $to])
                ->whereHas('category', function ($q) {
                    $q->where('type', 'marketing')->orWhere('type', 'advertising');
                })
                ->where('notes', 'like', '%' . $source->come_from . '%')
                ->sum('amount');

            $source->ad_expenses = $adExpenses;
            $source->cost_per_order = $source->orders_count > 0
                ? $adExpenses / $source->orders_count
                : 0;
            $source->roi = $adExpenses > 0
                ? (($source->total_revenue - $adExpenses) / $adExpenses) * 100
                : 0;
        }

        // ============================================
        // 5. PROFIT & LOSS CALCULATION
        // ============================================
        $grossProfit = $totalRevenue - $productProfitability->sum('total_cost');
        $netProfit = $grossProfit - $totalExpenses;
        $profitMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0;

        // Break-even Analysis
        $breakEvenPoint = $totalExpenses;

        // ============================================
        // 6. MONTHLY COMPARISON (if viewing current month)
        // ============================================
        $isCurrentMonth = $from->isSameMonth(Carbon::now()) && $to->isSameMonth(Carbon::now());
        $monthlyComparison = null;

        if ($isCurrentMonth) {
            $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
            $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

            $lastMonthRevenue = Order::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
                ->sum(DB::raw('COALESCE(total_price_after_discount, total_price)'));

            $lastMonthExpenses = Expense::whereBetween('expense_date', [$lastMonthStart, $lastMonthEnd])
                ->sum('amount');

            $lastMonthProfit = $lastMonthRevenue - $lastMonthExpenses;

            $monthlyComparison = [
                'revenue_change' => $lastMonthRevenue > 0 ? (($totalRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0,
                'expense_change' => $lastMonthExpenses > 0 ? (($totalExpenses - $lastMonthExpenses) / $lastMonthExpenses) * 100 : 0,
                'profit_change' => $lastMonthProfit > 0 ? (($netProfit - $lastMonthProfit) / $lastMonthProfit) * 100 : 0,
            ];
        }

        // ============================================
        // 7. KEY PERFORMANCE INDICATORS (KPIs)
        // ============================================
        $kpis = [
            'total_revenue' => $totalRevenue,
            'total_deposited' => $totalDeposited,
            'outstanding_amount' => $outstandingAmount,
            'total_expenses' => $totalExpenses,
            'gross_profit' => $grossProfit,
            'net_profit' => $netProfit,
            'profit_margin' => $profitMargin,
            'total_orders' => $totalOrders,
            'average_order_value' => $averageOrderValue,
            'break_even_point' => $breakEvenPoint,
        ];

        // ============================================
        // 8. DAILY TREND ANALYSIS
        // ============================================
        $dailyTrends = Order::whereBetween('created_at', [$from, $to])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(COALESCE(total_price_after_discount, total_price)) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Prepare data for the view
        $data = [
            'kpis' => $kpis,
            'productProfitability' => $productProfitability,
            'mostProfitable' => $mostProfitable,
            'leastProfitable' => $leastProfitable,
            'sourceEffectiveness' => $sourceEffectiveness,
            'expensesByType' => $expensesByType,
            'expensesByCategory' => $expensesByCategory,
            'revenueByStatus' => $revenueByStatus,
            'dailyTrends' => $dailyTrends,
            'monthlyComparison' => $monthlyComparison,
            'from' => $from,
            'to' => $to,
        ];

        // Only Excel export is now available
        if ($format === 'excel') {
            // Generate Excel
            $filename = 'profit-loss-report-' . $from->format('Y-m-d') . '-to-' . $to->format('Y-m-d') . '.xlsx';

            // Use Laravel Excel to export
            return Excel::download(new ProfitLossReportExport($data), $filename);
        }

        // If format is not recognized, redirect back with error
        return redirect()->back()->with('error', 'Only Excel export is available. PDF export has been disabled.');
    }
}