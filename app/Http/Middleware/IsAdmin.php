<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // เช็กว่าล็อกอินแล้ว และ role เป็น admin หรือไม่
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // ถ้าไม่ใช่ ให้เตะกลับไปหน้าแรก
        return redirect('/')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้ (เฉพาะ Admin)');
    }
}