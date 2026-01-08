<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProfitLossReportExport implements WithMultipleSheets
{
    protected $data;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function sheets(): array
    {
        return [
            new KpisSheet($this->data),
            new ProductProfitabilitySheet($this->data),
            new SourceEffectivenessSheet($this->data),
            new ExpensesSheet($this->data),
        ];
    }
}

class KpisSheet implements FromCollection, WithTitle
{
    protected $data;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function collection()
    {
        $kpis = $this->data['kpis'];
        
        return collect([
            ['المؤشر', 'القيمة'],
            ['صافي الربح/الخسارة', $kpis['net_profit']],
            ['إجمالي الإيرادات', $kpis['total_revenue']],
            ['إجمالي المصروفات', $kpis['total_expenses']],
            ['هامش الربح', $kpis['profit_margin'] . '%'],
            ['عدد الطلبات', $kpis['total_orders']],
            ['متوسط قيمة الطلب', $kpis['average_order_value']],
            ['المبلغ المحصل', $kpis['total_deposited']],
            ['المبلغ المتبقي', $kpis['outstanding_amount']],
        ]);
    }
    
    public function title(): string
    {
        return 'مؤشرات الأداء الرئيسية';
    }
}

class ProductProfitabilitySheet implements FromCollection, WithTitle
{
    protected $data;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function collection()
    {
        $products = $this->data['productProfitability'];
        
        $rows = collect([
            ['المنتج', 'الكمية المباعة', 'الإيرادات', 'التكلفة', 'المصروفات', 'صافي الربح', 'هامش الربح', 'التكلفة الفعلية/الوحدة']
        ]);
        
        foreach ($products as $product) {
            $rows->push([
                $product->name,
                $product->total_quantity,
                $product->total_revenue,
                $product->total_cost,
                $product->product_expenses,
                $product->net_profit,
                $product->profit_margin . '%',
                $product->actual_cost_per_unit
            ]);
        }
        
        return $rows;
    }
    
    public function title(): string
    {
        return 'تحليل ربحية المنتجات';
    }
}

class SourceEffectivenessSheet implements FromCollection, WithTitle
{
    protected $data;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function collection()
    {
        $sources = $this->data['sourceEffectiveness'];
        
        $rows = collect([
            ['المصدر', 'عدد الطلبات', 'إجمالي الإيرادات', 'متوسط قيمة الطلب', 'مصروفات الإعلان', 'تكلفة الطلب', 'العائد على الاستثمار']
        ]);
        
        foreach ($sources as $source) {
            $rows->push([
                $source->come_from,
                $source->orders_count,
                $source->total_revenue,
                $source->avg_order_value,
                $source->ad_expenses,
                $source->cost_per_order,
                $source->roi . '%'
            ]);
        }
        
        return $rows;
    }
    
    public function title(): string
    {
        return 'فعالية مصادر الطلبات';
    }
}

class ExpensesSheet implements FromCollection, WithTitle
{
    protected $data;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function collection()
    {
        $expenses = $this->data['expensesByCategory'];
        
        $rows = collect([
            ['النوع', 'الاسم', 'عدد العمليات', 'الإجمالي']
        ]);
        
        foreach ($expenses as $expense) {
            $rows->push([
                $expense->type,
                $expense->name,
                $expense->count,
                $expense->total
            ]);
        }
        
        return $rows;
    }
    
    public function title(): string
    {
        return 'تفاصيل المصروفات';
    }
}