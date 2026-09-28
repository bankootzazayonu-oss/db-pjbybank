<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Director;
use Illuminate\Http\Request;

class DirectorController extends Controller
{
    public function index()
    {
        $directors = Director::latest()->get();
        return view('admin.directors.index', compact('directors'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Director::create($request->all());
        return back()->with('success', 'เพิ่มผู้กำกับเรียบร้อยแล้ว');
    }

    public function destroy(Director $director)
    {
        $director->delete();
        return back()->with('success', 'ลบผู้กำกับเรียบร้อยแล้ว');
    }
}