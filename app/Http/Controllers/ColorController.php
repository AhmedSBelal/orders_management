<?php

namespace App\Http\Controllers;

use App\Http\Requests\Colors\ColorCreateRequest;
use App\Http\Requests\Colors\ColorFilterRequest;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ColorFilterRequest $request)
    {
        $title = 'colores';
        $colors = Color::colorsByFilter($request);
        return view('colors.index', compact('colors', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Add New colore';
        return view('colors.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ColorCreateRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::user()->id;
            Color::create($data);
            return redirect()->back()->with('success', 'New color has been created.');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(Color $color)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Color $color)
    {
        $title = 'Edit Color';
        return view('colors.edit', compact('color', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ColorCreateRequest $request, Color $color)
    {
        try {
            $color->update($request->validated());
            return redirect()->back()->with('success', 'Color has been updated.');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Color $color)
    {
        try {
            if ($color->delete()) {
                return redirect()->back()->with('success', 'Order deleted successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to delete order.');
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
