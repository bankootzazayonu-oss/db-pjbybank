<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    // แสดงหน้าจอหมวดหมู่ทั้งหมด
    public function index()
    {
        $types = Type::latest()->get();
        return view('admin.types.index', compact('types'));
    }

    // รับข้อมูลจากฟอร์มเพื่อบันทึก
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:types,name',
        ], [
            'name.unique' => '⚠️ หมวดหมู่นี้มีอยู่ในระบบแล้ว',
            'name.required' => 'กรุณากรอกชื่อหมวดหมู่'
        ]);

        Type::create(['name' => $request->name]);
        return back()->with('success', '✅ เพิ่มหมวดหมู่เรียบร้อยแล้ว');
    }

    // ลบหมวดหมู่
    public function destroy(Type $type)
    {
        $type->delete();
        return back()->with('success', '🗑️ ลบหมวดหมู่เรียบร้อยแล้ว');
    }
}