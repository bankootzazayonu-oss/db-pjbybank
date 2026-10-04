<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use App\Models\Activity;
use App\Models\Review;
use App\Models\Collection;
class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
{
    $user = $request->user();

    // หนังที่ User เคยรีวิว
    $reviews = Review::with('activity')
        ->where('user_id', $user->id)
        ->latest()
        ->get();

    // Tier List ที่ User สร้าง
    $collections = Collection::where('user_id', $user->id)
        ->latest()
        ->get();

    // หนังที่ User เป็นคนเสนอเข้าระบบ
    $submittedMovies = Activity::where('user_id', $user->id)
        ->latest()
        ->get();

    return view('profile.edit', [
        'user' => $user,
        'reviews' => $reviews,
        'collections' => $collections,
        'submittedMovies' => $submittedMovies,
    ]);
}

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
