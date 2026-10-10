<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    /**
     * แสดงรายการแพลตฟอร์มทั้งหมด พร้อมตัวนับจำนวนภาพยนตร์
     */
    public function index(Request $request)
    {
        $search = $request->query('q');

        $query = Platform::withCount('activities');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $platforms = $query->orderBy('name')->get();

        return view('admin.platforms.index', compact('platforms', 'search'));
    }

    /**
     * บันทึกแพลตฟอร์มใหม่
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:platforms,name',
            'logo' => 'nullable|string|max:255',
        ], [
            'name.required' => 'กรุณากรอกชื่อแพลตฟอร์ม',
            'name.unique' => '️ แพลตฟอร์มนี้มีอยู่ในระบบแล้ว',
        ]);

        Platform::create([
            'name' => trim($request->name),
            'logo' => $request->logo ? trim($request->logo) : null,
        ]);

        return back()->with('success', ' เพิ่มแพลตฟอร์ม "' . $request->name . '" เข้าสู่ระบบแล้ว');
    }

    /**
     * บันทึกการแก้ไขชื่อแพลตฟอร์ม
     */
    public function update(Request $request, Platform $platform)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:platforms,name,' . $platform->id,
            'logo' => 'nullable|string|max:255',
        ], [
            'name.required' => 'กรุณากรอกชื่อแพลตฟอร์ม',
            'name.unique' => '️ ชื่อแพลตฟอร์มนี้มีอยู่ในระบบแล้ว',
        ]);

        $platform->update([
            'name' => trim($request->name),
            'logo' => $request->filled('logo') ? trim($request->logo) : $platform->logo,
        ]);

        return back()->with('success', ' อัปเดตข้อมูลแพลตฟอร์มเรียบร้อยแล้ว');
    }

    /**
     * ลบแพลตฟอร์ม (ตัดความสัมพันธ์ในตาราง pivot อัตโนมัติ)
     */
    public function destroy(Platform $platform)
    {
        $name = $platform->name;
        $platform->activities()->detach();
        $platform->delete();

        return back()->with('success', '️ ลบแพลตฟอร์ม "' . $name . '" เรียบร้อยแล้ว');
    }
}
