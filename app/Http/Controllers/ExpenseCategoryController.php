<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpenseCategory;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = ExpenseCategory::withCount('expenses')
            ->latest()
            ->get();
            
        return view('expenses.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expenses.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name',
            'type' => 'required|in:fixed,variable,other',
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'اسم التصنيف مطلوب',
            'name.unique' => 'هذا التصنيف موجود بالفعل',
            'type.required' => 'نوع المصروف مطلوب',
            'type.in' => 'نوع المصروف غير صحيح',
        ]);

        ExpenseCategory::create([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
        ]);

        return redirect()->route('expense-categories.index')
            ->with('success', 'تم إضافة التصنيف بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->load('expenses');
        return view('expenses.categories.show', compact('expenseCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExpenseCategory $expenseCategory)
    {
        return view('expenses.categories.edit', [
            'category' => $expenseCategory
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name,' . $expenseCategory->id,
            'type' => 'required|in:fixed,variable,other',
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'اسم التصنيف مطلوب',
            'name.unique' => 'هذا التصنيف موجود بالفعل',
            'type.required' => 'نوع المصروف مطلوب',
            'type.in' => 'نوع المصروف غير صحيح',
        ]);

        $expenseCategory->update([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
        ]);

        return redirect()->route('expense-categories.index')
            ->with('success', 'تم تحديث التصنيف بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseCategory $expenseCategory)
    {
        // Check if category has expenses
        if ($expenseCategory->expenses()->count() > 0) {
            return redirect()->route('expense-categories.index')
                ->with('error', 'لا يمكن حذف التصنيف لأنه يحتوي على مصاريف مرتبطة به');
        }

        $expenseCategory->delete();

        return redirect()->route('expense-categories.index')
            ->with('success', 'تم حذف التصنيف بنجاح');
    }
}