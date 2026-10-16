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
    public function edit(Request $request): View
    {
        $user = $request->user();

        $reviews = Review::with('activity')
            ->where('User_ID', $user->User_ID)
            ->latest('CreatedAt')
            ->get();

        $collections = Collection::where('User_ID', $user->User_ID)
            ->latest('CreatedAt')
            ->get();

        $submittedMovies = Activity::where('Submitted_By', $user->User_ID)
            ->latest('CreatedAt')
            ->get();

        return view('profile.edit', [
            'user' => $user,
            'reviews' => $reviews,
            'collections' => $collections,
            'submittedMovies' => $submittedMovies,
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email') || $request->user()->isDirty('Email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

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
