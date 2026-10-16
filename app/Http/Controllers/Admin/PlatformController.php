<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
  
    public function index(Request $request)
    {
        $search = $request->query('q');

        $query = Platform::withCount('activities');

        if ($search) {
            $query->where('Platform_Name', 'like', '%' . $search . '%');
        }

        $platforms = $query->orderBy('Platform_Name')->get();

        return view('admin.platforms.index', compact('platforms', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:platforms,Platform_Name',
            'logo' => 'nullable|string|max:255',
        ], [
            'name.required' => 'กรุณากรอกชื่อแพลตฟอร์ม',
            'name.unique' => '️ แพลตฟอร์มนี้มีอยู่ในระบบแล้ว',
        ]);

        Platform::create([
            'Platform_Name' => trim($request->name),
            'Logo' => $request->logo ? trim($request->logo) : null,
        ]);

        return back()->with('success', ' เพิ่มแพลตฟอร์ม "' . $request->name . '" เข้าสู่ระบบแล้ว');
    }

    public function update(Request $request, Platform $platform)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:platforms,Platform_Name,' . $platform->Platform_ID . ',Platform_ID',
            'logo' => 'nullable|string|max:255',
        ], [
            'name.required' => 'กรุณากรอกชื่อแพลตฟอร์ม',
            'name.unique' => '️ ชื่อแพลตฟอร์มนี้มีอยู่ในระบบแล้ว',
        ]);

        $platform->update([
            'Platform_Name' => trim($request->name),
            'Logo' => $request->filled('logo') ? trim($request->logo) : $platform->Logo,
        ]);

        return back()->with('success', ' อัปเดตข้อมูลแพลตฟอร์มเรียบร้อยแล้ว');
    }

    public function destroy(Platform $platform)
    {
        $name = $platform->Platform_Name;
        $platform->activities()->detach();
        $platform->delete();

        return back()->with('success', '️ ลบแพลตฟอร์ม "' . $name . '" เรียบร้อยแล้ว');
    }
}
