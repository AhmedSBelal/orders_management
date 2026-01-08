<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Expense;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ExpenseCategory;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with(['category', 'order', 'product'])
            ->latest()
            ->paginate(10);

        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('expenses.create', [
            'categories' => ExpenseCategory::all(),
            'orders'     => Order::all(),
            'products'   => Product::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);

        Expense::create([
            'user_id' => auth()->id(),
            'expense_category_id' => $request->expense_category_id,
            'order_id' => $request->order_id,
            'product_id' => $request->product_id,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
        ]);

        return redirect()->route('expenses.index')
            ->with('success', 'تم تسجيل المصروف بنجاح');
    }

    public function edit(Expense $expense)
    {
        return view('expenses.edit', [
            'expense' => $expense,
            'categories' => ExpenseCategory::all(),
            'orders' => Order::all(),
            'products' => Product::all(),
        ]);
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);

        $expense->update([
            'expense_category_id' => $request->expense_category_id,
            'order_id' => $request->order_id,
            'product_id' => $request->product_id,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
        ]);

        return redirect()->route('expenses.index')
            ->with('success', 'تم تحديث المصروف بنجاح');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        
        return redirect()->route('expenses.index')
            ->with('success', 'تم حذف المصروف بنجاح');
    }

}
