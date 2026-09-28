<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function index()
    {
        $types = Type::latest()->get();
        return view('admin.types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Type::create($request->all());
        return back()->with('success', 'เพิ่มหมวดหมู่เรียบร้อยแล้ว');
    }

    public function destroy(Type $type)
    {
        $type->delete();
        return back()->with('success', 'ลบหมวดหมู่เรียบร้อยแล้ว');
    }
}