<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Type;
use App\Models\Activity;
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

    // 🟢 แอดมินกดบันทึกการแก้ไขชื่อหมวดหมู่
    public function update(Request $request, Type $type)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:types,name,' . $type->id,
        ], [
            'name.unique' => '⚠️ ชื่อหมวดหมู่นี้มีอยู่ในระบบแล้ว',
            'name.required' => 'กรุณากรอกชื่อหมวดหมู่'
        ]);

        $type->update(['name' => $request->name]);

        return back()->with('success', '✅ แก้ไขชื่อหมวดหมู่เรียบร้อยแล้ว');
    }

    // 🔴 แอดมินกดลบหมวดหมู่ (เหลือตัวเดียว พร้อมระบบดักจับกันฐานข้อมูลพัง)
    public function destroy(Type $type)
    {
        // 🛡️ ตรวจสอบว่ามีหนังเรื่องไหนใช้หมวดหมู่นี้อยู่หรือไม่
        $hasMovies = Activity::where('type_id', $type->id)->exists();

        if ($hasMovies) {
            return back()->with('error', '❌ ไม่สามารถลบได้! เนื่องจากมีภาพยนตร์ใช้หมวดหมู่นี้อยู่ กรุณาเปลี่ยนหมวดหมู่ของภาพยนตร์ก่อน');
        }

        $type->delete();

        return back()->with('success', '🗑️ ลบหมวดหมู่เรียบร้อยแล้ว');
    }
}